<?php

namespace App\Http\Controllers;

use App\Admin;
use App\User;
use App\Home;
use App\Quize;

use App\Models\PD;

use App\BlueMail;

use Mail;

use App\Mail\FeedbackMail;
use App\Mail\GeneralMail;

use App\Http\Controllers\JsonController;

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
        session_start();
    }

    public function index(){

        $quizes = Quize::where('featured', 1)
                        ->where('active', 1)
                        ->orderBy('quiz_order', 'asc')
                        ->orderBy('id', 'asc')
                        ->limit(3)
                        ->get();

        $home = Home::first();

        // dd($home);

        return view('home')->with(['quizes' => $quizes, 'home' => $home]);

    }

    public function quizes(){

        $quizes = Quize::where('active', 1)
                        ->orderBy('quiz_order', 'asc')
                        ->get();

        return view('quizes')->with(['quizes' => $quizes]);

    }

    public function search(Request $request){

        // dd($request->keyword);

        $keyword = $request->keyword;

        $quizes = Quize::where('quiz_name', 'like', '%'.$keyword.'%')
                        ->orWhere('meta_keywords', 'like', '%'.$keyword.'%')
                        ->orWhere('category', 'like', '%'.$keyword.'%')
                        ->get();

        // dd(count($quizes));

        $count = count($quizes);

        if($count == 0){
            $quizes = Quize::all();
        }

        return view('quizes')->with(['quizes' => $quizes, 'count' => $count]);

    }

    public function quizDetails($sef_url){

        $quiz = Quize::where('sef_url', $sef_url)->first();

        $linked = 0;

        if($quiz->is_bundle == 1){

            $linked = JsonController::getLinkedQuizes($quiz->id);
            
        }

        $quiz->short_description = JsonController::cleanTags($quiz->short_description);
        $quiz->quiz_description = JsonController::cleanTags($quiz->quiz_description);

        unset($_SESSION['user']);
        unset($_SESSION['user_id']);

        return view('quizDetails', ['quiz' => $quiz, 'linked' => $linked]);

    }

    public function tryQuiz($id){

        $quiz = Quize::where('id', $id)->first();

        session_destroy();
        session_start();

        $_SESSION['user'] = 1;
        $_SESSION['user_id'] = 777;
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

        if ($result == null) {
            return redirect('/logIn')->with('error', 'Wrong email and/or password!');
        } 
        // elseif(){

        // }
        else {

            // session_start();

            $_SESSION['user'] = 1;

            // dd($_SESSION['user']);

            $_SESSION['user_id'] = $result->id;

            $_SESSION['username'] = $result->username;

            if(!empty($_SESSION['cart']) && $_SESSION['cart'] == 1){
                $page = '/cart';
            }
            else{

                $page = '/myPage';
            }

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
                        ->where('is_admin', 1)
                        ->first();
        
        // dd($result);    

        if ($result == null) {
            return redirect('/admin')->with('error', 'Wrong username and/or password!');
        } else {

            $_SESSION['admin'] = 1;

            return redirect('/adminhome')->with('success', 'You are successfuly logged in!');
        }
    }

    public function signUp(){

        return view('signUp');

    }

    public function trySignUp(Request $request)
    {

        $request->validate([
            'username' => 'required|max:30',
            'phone' => 'max:15',
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required|min:6',
            'email' => 'email|required|max:150|unique:users',
            'agree' => 'required|max:2',
        ]);

        // dd($request);

        $user = new User();

        $user->username = $request->username;
        $user->phone = $request->phone;
        $user->password = $request->password;
        $user->email = $request->email;

        $email_hash = self::generateRandomString(16);

        $user->email_hash = $email_hash;

        $user->agree = 1;

        $user->save();

        //dd($user);

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

    public static function sendEmailConfirmEmail($user)
    {

        $to      = $user->email;
        $subject = 'Your access to Quizes';

        $message = 'Click link below to confirm your email: '; 

        $email_link = env('APP_URL');
        $email_link .= '/confirmEmail/'.$user->email_hash;

        $feedback = ['message' => $message, 
                     'subject' => env('WEBSITE_NAME').' - Confirm your email',
                     'email_link' => $email_link,
                     'email_template' => 'confirm_email'
                    ];

        $responce = Mail::to($user->email)->send(new GeneralMail($feedback));

        //dd($responce);
    
        return $responce;
    }

    public static function confirmEmail($email_hash)
    {

        $user = User::where('email_hash', $email_hash)->first();

        //dd($user);

        if(!empty($user)){

            // dd($user[0]);
            //dd('here');

            $user->confirmed_email = 1;

            $user->save();

            return redirect('/logIn')->with('success', 'Email has been confirmed.');

        }
        else{
            // dd('error');
            return redirect('/logIn')->with('error', 'Wrong code.');
        }

    }

    public function logIn()
    {
        // dd('login');
        return view('logIn');
    }

    //Paypal

    // public function checkout(){
    //     return view('checkout');
    // }

    public function ppCompleted(){

        // echo $_GET['PayerID'];
        // echo "<br>";
        // echo $_SESSION['user_id'];

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

        // dd($request);

        $user = User::where('email', $request->email)->first();

        // dd($user);  

        if($user == null){
            return redirect('/forgotPassword')->with('error', $request->email." is not registered with our website. Please try again.");
        }

        $email_hash2 = self::generateRandomString(16);

        $user->email_hash2 = $email_hash2;

        $user->save();

        $responce = BlueMail::resetPasswordEmail($user->email, $user->email_hash2);

        // $responce = self::sendEmailResetPassword($user);

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
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required|min:6',
        ]);

        $user = User::where('email', $request->email)->first();

        $user->password = $request->password;


        $user->save();

        // dd($request->email);

        return view('/logIn')->with('success', 'Your password has been reset. Please login!');

    }

    public function doContactUs(Request $request){

        $request->validate([
            'email' => 'email|required|min:6',
            'message' => 'required|min:10',
        ]);

        $to      = env('WEBSITE_EMAIL');

        $message = 'User Email: '.$request->email." - ";
        $message .= 'Message: '.$request->message; 

        // $feedback = ['message' => $message, 
        //              'subject' => env('WEBSITE_NAME').' - Contact Form',
        //              'email_template' => 'contact_us'
        //             ];

        // $responce = Mail::to($to)->send(new GeneralMail($feedback));

        $responce = BlueMail::contactUs($message);

        return redirect('/contactUs')->with('success', $responce);
    }

    public function contactUs(){
        
        echo PD::PD('127.0.0.1');
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

        //dd($pageStatic->page_name_url);

        $pageStatic = (object) $pageStatic;
        
        $pageStatic->main_text = str_replace("&lt;", "<", $pageStatic->main_text);

        $pageStatic->main_text = str_replace("&gt;", ">", $pageStatic->main_text);

        // dd($pageStatic->main_text);
        
        //  &lt;    &gt;

        return view('pageStatic', ['home' => $pageStatic]);

    }


}
 