<?php

namespace App\Http\Controllers;

use App\Admin;
use App\User;
use App\Home;
use App\Quize;
use App\Find;

use App\Categorie;

use App\BlueMail;

use Mail;

use App\Mail\FeedbackMail;
use App\Mail\GeneralMail;

use App\Http\Controllers\HomeController;

use App\Http\Controllers\HelperController;

use App\Http\Controllers\JsonController;

use App\Http\Controllers\CategorieController;

use Illuminate\Support\Facades\DB;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Hash;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        session_reset();
        session_start();
    }

    public function index(){

        $quizes = Quize::where('featured', 1)
                        ->where('active', 1)
                        ->where('quiz_sts', 0)
                        ->where('public', 0)
                        ->orderBy('quiz_order', 'asc')
                        ->orderBy('id', 'asc')
                        ->limit(3)
                        ->get();

        $home = Home::first();

        foreach($quizes as $quiz){
            $categories= self:: getCatLinks($quiz->id);
            $quiz->categories = substr($categories, 1);
        }

        // dd($quizes);

        return view('home')->with(['quizes' => $quizes, 'home' => $home]);

    }

    public function quizes(){

        $quizes = Quize::where('active', 1)
                        ->where('quiz_sts', 0)
                        ->where('public', 0)
                        ->orderBy('quiz_order', 'asc')
                        ->get();

        foreach($quizes as $quiz){
            $categories= self:: getCatLinks($quiz->id);
            $quiz->categories = substr($categories, 1);
        }

        // dd($quizes);

        return view('quizes')->with(['quizes' => $quizes]);

    }

    public function category($cat_id){ //specific category by cat_id

        $quizes = DB::table('quizes')
            ->join('categorie_quize', 'quizes.id', '=', 'categorie_quize.qz_id')
            ->join('categories', 'categories.id', '=', 'categorie_quize.cat_id')
            ->select('quizes.*', 'categorie_quize.*', 'categories.*')
            ->where('categories.id', $cat_id)
            ->where('quizes.active', 1)
            ->where('quizes.quiz_sts', 0)
            ->where('quizes.public', 0)
            ->where('categories.id', $cat_id)
            ->orWhere('categories.parent_id', $cat_id)
            ->distinct() // Add this line
            ->get();

        $category = Categorie::find($cat_id);

        foreach($quizes as $quiz){
            $categories= self::getCatLinks($quiz->qz_id);
            $quiz->categories = substr($categories, 1);
        }

        // dd($quizes);

        return view('quizes')->with(['quizes' => $quizes, 'category' => $category]);
    }

    public function categoriesTree(){ //all categories tree

                $query = DB::select("SELECT DISTINCT c.id, c.cat_name, c.parent_id
                            FROM categories c
                            INNER JOIN categorie_quize cq 
                                ON c.id = cq.cat_id
                            INNER JOIN quizes q 
                                ON cq.qz_id = q.id
                            WHERE q.public = 0 and q.active = 1 and q.quiz_sts = 0
                            --
                            ;");

                // dd($query);

                $cat_tree = [];

                return view('categoriesTree')->with(['query' => $query]);

    }

    public static function getCatLinks($qz_id){

        $categories = "";

        $cats = DB::table('categorie_quize')
        ->select("*")
        ->where('qz_id', $qz_id)
        ->get();

        foreach($cats as $cat){
            $cur = Categorie::find($cat->cat_id);
            $categories.= ", <a href='/category/" . $cat->cat_id . "'>$cur->cat_name</a>"; 
        }

        return $categories;
    }

    public function search(Request $request){

        $keyword = $request->keyword;

        if(!empty($keyword) && $keyword != NULL){
            $find = new Find;
            $find->keys = $keyword;
            $find->save();
        }

        $quizes = Quize::where('quiz_name', 'like', '%'.$keyword.'%')
                        ->where('active', 1)
                        ->where('quiz_sts', 0)
                        ->get();

        $count = count($quizes);

        if($count == 0){
            $quizes = Quize::where('active', 1)->get();
        }

        return view('quizes')->with(['quizes' => $quizes, 'count' => $count]);

    }

    public function quizDetails(Request $request){

        $no_index = null;

        if(is_numeric($request->sef_url)){
            $quiz = Quize::where('id', $request->sef_url)->first();

            $no_index = 1;
            // dd($quiz);
        }
        else{
            $quiz = Quize::where('sef_url', $request->sef_url)->first();       
        }

        // dd($request->path() );

        $linked = 0;

        if(!empty($quiz->is_bundle) && $quiz->is_bundle == 1){

            $linked = JsonController::getLinkedQuizes($quiz->id);
            
        }

        $quiz->short_description = JsonController::cleanTags($quiz->short_description);
        $quiz->quiz_description = JsonController::cleanTags($quiz->quiz_description);

        /* */
        $categories= self::getCatLinks($quiz->id);
        $quiz->categories = substr($categories, 1);
        /**/
        return view('quizDetails', ['quiz' => $quiz, 'linked' => $linked, 'no_index' => $no_index]);

    }

    public function tryQuiz($id){

        $quiz = Quize::where('id', $id)->first();

        if(empty($_SESSION['user'])){

            session_destroy();
            session_start();
    
            $_SESSION['user'] = 1;
            $_SESSION['user_id'] = 777;
        }

        $_SESSION['try_quiz'] = 1;

        // dd($_SESSION);

        return redirect('/quizHome/'.$quiz->id)->with('quiz', $quiz);

    }

    public function cart(){
        return view('cart');
    }

    public function userTryLogin(Request $request)
    {

        $request->validate([
            'email' => 'required|max:255',
            'password' => 'required|max:255',
        ]);

        // dd($request->email);

        $result = User::where('email', $request->email)
            ->where('password', $request->password)
            ->first();

            // dd($result);

        if ($result == null) {

            return redirect('/logIn')->with('error', 'Wrong email and/or password!');

        } 
        elseif($result->confirmed_email != 1) {

            $responce = BlueMail::confirmEmail($result->email, $result->email_hash);

            return redirect('/logIn')->with('error', 'Please confirm your email to login. Email has beed resent to '.$request->email);

        }
        else {

            session_destroy();
            session_start();

            $_SESSION['user'] = 1;

            $_SESSION['user_id'] = $result->id;

            $_SESSION['username'] = self::cropUsername($result->username);

            if(!empty($_SESSION['cart']) && $_SESSION['cart'] == 1){
                $page = '/cart';
            }
            else{

                $page = '/myPage';
            }

            /*** add session ***/

            if(!empty($request->quiz_token)){
                $resp = HelperController::addToSession($result->id, $request->quiz_token); 
            }

            $re = BlueMail::contactCheck("check");

            return redirect($page)->with('success', 'You are successfuly logged in!');
        }
    }

    public function adminTryLogin(Request $request)
    {

        $request->validate([
            'username' => 'required|max:255',
            'password' => 'required|max:255',
        ]);

        
        $result = User::where('username', $request->username)
                        ->where('password', $request->password)
                        ->where('is_admin', "!=", 0)
                        // ->where('is_admin', 1)
                        // ->where('is_admin', 1)
                        // ->orwhere('is_admin', 2)
                        ->first();
        
        // dd($result);    

        if ($result == null) {
            return redirect('/warden')->with('error', 'Wrong username and/or password or you dont have permissions!');
        } else {

            session_destroy();
            session_start();

            $_SESSION['admin'] = 1;
            $_SESSION['admin_username'] = $result->username;

            if($result->is_admin == 2){

                $_SESSION['super_admin'] = 2;

            }

            return redirect('/adminhome')->with('success', 'You are successfuly logged in!');
        }
    }

    public function signUp(){

        return view('signUp');

    }

    public function doSignUp(Request $request)
    {

        $request->validate([

            'username' => 'required|max:30',
            'email' => [
                'required',
                'max:150',
                'unique:users',
                function ($attribute, $value, $fail) {
                    if (!preg_match('/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/', $value)) {
                        $fail('Wrong email format. Email should have (@) and (.) (ex: username@domain.com)');
                    }
                },
            ],
            'phone' => 'max:15',
            'password' => HelperController::passwordValidation(1),
            'agree' => 'required|max:2',
        ]);

        $user = new User();

        $user->username = $request->username;
        $user->phone = $request->phone;
        $user->password = $request->password;
        $user->email = $request->email;

        $email_hash = self::generateRandomString(16);

        $user->email_hash = $email_hash;

        $user->agree = 1;

        $user->save();

        if(!empty($request->quiz_token)){

            $resp = HelperController::addToSession($user->id, $request->quiz_token);

        }

        $responce = BlueMail::confirmEmail($user->email, $user->email_hash);

        if(!empty($_SESSION['cart']) && $_SESSION['cart'] == 1){
            $page = '/cart';
        }
        else{
            $page = '/logIn';
        }

        return redirect($page)->with('success', 'Please confirm your email. It was sent to '.$request->email);

    }

    public static function generateRandomString($length = 10) {

        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }


    public static function confirmEmail($email_hash)
    {

        $user = User::where('email_hash', $email_hash)->first();

        if(!empty($user)){

            $user->confirmed_email = 1;

            $user->save();

            return redirect('/logIn')->with('success', 'Email has been confirmed.');

        }
        else{
            // dd('error');
            return redirect('/logIn')->with('error', 'Wrong code.');
        }

    }

    public function logIn(Request $request){

        $quiz_token = ($request->quiz_token) ? $request->quiz_token : NULL;

        return view('logIn')->with(['quiz_token' => $quiz_token]);
    }

    public function ppCompleted(){

        return view('ppCompleted')->with('success', 'Your payment was processed! Enjoy your quizzes!');
    }

    public function ppCanceled(){
        // dd($_REQUEST);
        return redirect('/')->with('error', 'Your payment was canceled!');
    }

    public function ppNotify(){ 
        return true;
    }

    public function resetPassword(Request $request){

        $request->validate([
            'email' => 'email|required|max:255',
        ]);

        $user = User::where('email', $request->email)->first();

        if($user == null){
            return redirect('/forgotPassword')->with('error', $request->email." is not registered with our website. Please try again.");
        }

        $email_hash2 = self::generateRandomString(16);

        $user->email_hash2 = $email_hash2;

        $user->save();

        $responce = BlueMail::resetPasswordEmail($user->email, $user->email_hash2);

        return redirect('/')->with('success', 'Please click link in your email to reset password. It was sent to '.$request->email);

    }

    public static function sendEmailResetPassword($user)
    {

        $to      = $user->email;

        $message = 'Click link below to reset your password: '; 

        $email_link = env('APP_URL');
        $email_link .= '/resetPasswordLink/'.$user->email_hash2;

        $feedback = ['message' => $message, 
                     'subject' => env('WEBSITE_NAME').' - Reset your password',
                     'email_link' => $email_link,
                     'email_template' => 'reset_password'
                    ];

        $responce = Mail::to($user->email)->send(new GeneralMail($feedback));

        //dd($responce);
    
        return $responce;
    }

    public function resetPasswordLink($email_hash2){

        // dd($email_hash2);

        $user = User::where('email_hash2', $email_hash2)->first();

        if(!empty($user->email)){
            return view('/resetPassword')->with('user', $user);
        }
        else{
            return view('/logIn')->with('error', 'Wrong Email Hash!');
        }

    }

    public function doResetPassword(Request $request){

        $request->validate([
            'password' => HelperController::passwordValidation(1),
        ]);

        $user = User::where('email', $request->email)->first();

        $user->password = $request->password;


        $user->save();

        return view('/logIn')->with('success', 'Your password has been reset. Please login!');

    }

    public function doContactUs(Request $request){

        $request->validate([
            'email' => 'email|required|min:6',
            'message' => 'required|min:10',
        ]);

        $to      = env('WEBSITE_EMAIL');

        $message = "<b>User's Email:</b> ".$request->email."<br><br>";
        $message .= "<b>User's Message:</b> ".$request->message;

        $responce = BlueMail::contactUs($message);

        return redirect('/contactUs')->with('success', $responce);
    }

    public function contactUs(){

        return view('contactUs');
    }

    public function privacy(){
        return view('privacy');
    }

    public function terms(){
        return view('terms');
    }

    public function forgotPassword(){
        return view('forgotPassword');
    }

    public static function pageStatic($cur){

        $pageStatic = Home::where('page_name_url', $cur)->first();

        $pageStatic = (object) $pageStatic;
        
        $pageStatic->main_text = str_replace("&lt;", "<", $pageStatic->main_text);
        $pageStatic->main_text = str_replace("&gt;", ">", $pageStatic->main_text);


        return view('pageStatic', ['home' => $pageStatic]);
    }

    public static function cropUsername($username){

        $formatedU = $username;

        $temp = substr($username, 0, strpos($username, " "));

        if($temp != NULL){
            $formatedU = $temp;
        }
         
        $user_len = strlen($formatedU);

        if($user_len > 8){

                $formatedU = substr($username, 0, 8)."...";

        }

        return $formatedU;

    }

    public static function setPassword($email_hash)
    {

        $user = User::where('email_hash', $email_hash)->first();

        // dd($user);

        if(!empty($user)){

            $user->confirmed_email = 1;

            $user->save();

            return view('setPassword', ['success' => 'Please set your password', 'user' => $user]);

        }
        else{
            return redirect('/logIn')->with('error', 'Wrong code.');
        }
    }

    public static function doSetPassword(Request $ewquest)
    {

        $request->validate([
            'password' => HelperController::passwordValidation(1),
        ]);

        $user = User::find($request->user_id);

        $user->password = $request->password;

        $user->save();

        return redirect('/logIn')->with('success', 'Wrong code.');

    }

    public static function inviteQuizLink($link)
    {

        $quiz = Quize::where('quiz_token', $link)->first();

        if($quiz == NULL){
            return redirect('/')->with('error', 'Wrong Code Link!');
        }

        return view('signUp',['success' => 'Sign up to access your quize/s. If you are already registered 
        <a href="/logIn?quiz_token='.$link.'" style="color: white; text-decoration: underline;">
        <button type="button" class="btn btn-danger">Login</button></a>', 'quiz_token' => $link]);

    }

    public static function changeUserEmail(Request $request){

        $user = User::where('id', $request->user_id)
                    ->where('email_change_hash', $request->hash)
                    ->first();

        if($user == NULL){
            dd("here1");
            return redirect('/')->with('error', 'Submitted wrong code to update login email!');
        }
        else{
            // dd("here3");

            $user->email = $user->email_temp;
            $user->save();

            return redirect('/logIn')->with('success', 'Login with newly updated email!');

        }

        dd($user);


    }

}
 