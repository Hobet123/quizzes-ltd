<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use File;

use App\Admin;
use App\User;

use App\Session;

use App\Xlsx;
use App\Quize;
use App\Question;
use App\Answer;

use ZipArchive;

use App\Http\Controllers\HomeController;

use App\Http\Controllers\JsonController;

use App\Http\Controllers\CategorieController;

use App\Http\Controllers\XlsxController;

use App\Http\Controllers\ManageUserControler;

use Illuminate\Support\Facades\DB;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Hash;

class HelperController extends Controller
{
    public function __construct()
    {
        session_start();
    }

    public static function initialRules(){

        $initial_rules = [
            'quiz_name' => 'required|max:255',
            'quiz_order' => 'integer|max:2000',
            'category' => 'max:255',
            'meta_keywords' => 'required|max:255',
            'featured' => 'max:2',
            'active' => 'max:2',
            'quiz_price' => 'numeric|max:100000',
            'short_description' => 'required|max:1000',
            'quiz_description' => 'max:10000',
            'cover_image' => 'image|mimes:jpg,png,jpeg,gif,svg|max:200000',
            'per_part' => 'max:2',
            'public' => 'max:2',
        ];

        return $initial_rules;
    }

    public static function quizToDB($request, $qz_id = 0, $extra_rules = []){

        $initial_rules = self::initialRules();

        $rules = array_merge($initial_rules, $extra_rules);

        $request->validate($rules);

        if($qz_id != 0){
            $new_quiz = Quize::find($qz_id);
        }
        else{
            $new_quiz = new Quize;
        }

        $new_quiz->quiz_name = $request->quiz_name;
        $new_quiz->quiz_order = ($request->quiz_order) ? $request->quiz_order : 999;

        $new_quiz->sef_url = JsonController::setSEFurl($request->quiz_name);

        $new_quiz->category = $request->category;
        $new_quiz->meta_keywords = $request->meta_keywords;


        if(empty($_SESSION['user'])){
            $new_quiz->featured = ($request->featured == 1) ? $new_quiz->featured = 1 : 0;
            // ($request->featured == 1) ? $new_quiz->featured = 1 : 0;

            $new_quiz->active = ($request->active == 1) ? 1 : 0;
            // ($request->active == 1) ? $new_quiz->active = 1 : 0;

            $new_quiz->is_bundle = ($request->is_bundle == 1) ? 1 : 0;
            // ($request->is_bundle == 1) ? $new_quiz->is_bundle = 1 : 0;


        }
            // $new_quiz->quiz_price = $request->quiz_price;
            $new_quiz->quiz_price = ($request->quiz_price != NULL) ? $request->quiz_price : 0;

        $new_quiz->short_description = $request->short_description;
        $new_quiz->quiz_description = $request->quiz_description;
        $new_quiz->per_part = $request->per_part;
        
        $new_quiz->quiz_sts = ($request->quiz_sts != NULL) ? $request->quiz_sts : 1;

        $new_quiz->public = $request->public;

        // dd($request->public);

        if($qz_id == 0){
            $new_quiz->quiz_token = self::generateQuizToken();//generateRandomString
        }

        

        $new_quiz->save();

        

        $qz_id = $quiz_id = $new_quiz->id;

        /*
            Add Cats
        */
        $quizes_to_link = CategorieController::getCatsQuizes($request);
        $result = CategorieController::linkCatsToQuizes($quiz_id, $quizes_to_link);
        /*
            End Add Cats
        */
        /*
            Cover Image
        */
        // $cover_image = null;

        if ($request->cover_image != null) {

            // File::delete(public_path()."/cover_images/".$quiz->cover_image);

            $file = $request->file('cover_image');

            $cover_image = 'c_' . $quiz_id . '.' . $file->getClientOriginalExtension();
            $path = $request->cover_image->move(public_path() . '/cover_images', $cover_image);
        }

        if(isset($cover_image)) $new_quiz->cover_image = $cover_image;

        $new_quiz->save();

        $qz_id = $new_quiz->id;

        return $qz_id;

    }

    public static function trialQuestions($quiz_id){

        $count = Question::where('qz_id', $quiz_id)->count();

        if($count > 60) $limit = 8;
        if($count > 30) $limit = 6;
        else $limit = 5;

        $sQuesions = Question::where('qz_id', $quiz_id)->limit($limit)->get();

        return $sQuesions;

    }

    public static function userRules($confirmed = 1, $unique =1){

        $user_rules = [
            'username' => 'required|max:30',
            'email' => self::emailValidation($confirmed, $unique),
            'phone' => 'max:15',
            'password' => self::passwordValidation($confirmed, $unique),
        ];

        return $user_rules;

    }

    /*
        Submit request and upload cover image
    */

    public static function requestToDB($request, $qz_id = 0){ 

        if($qz_id != 0){
            $new_quiz = Quize::find($qz_id);
        }
        else{
            $new_quiz = new Quize;
        }

        $new_quiz->quiz_name = $request->quiz_name;
        $new_quiz->quiz_order = ($request->quiz_order) ? $request->quiz_order : 777;

        $new_quiz->sef_url = self::setSEFurl($request->quiz_name);

        $new_quiz->category = $request->category;
        $new_quiz->meta_keywords = $request->meta_keywords;

        $new_quiz->featured = ($request->featured == 1) ? 1 : 0;
        $new_quiz->active = ($request->active == 1) ? 1 : 0;
        $new_quiz->is_bundle = ($request->is_bundle == 1) ? 1 : 0;

        $new_quiz->quiz_price = $request->quiz_price;
        $new_quiz->short_description = $request->short_description;
        $new_quiz->quiz_description = $request->quiz_description;

        $new_quiz->save();

        $qz_id = $quiz_id = $new_quiz->id;
        /*
            Add Cats
        */
        $quizes_to_link = CategorieController::getCatsQuizes($request);
        $result = CategorieController::linkCatsToQuizes($qz_id, $quizes_to_link);
        /*
            End Add Cats
        */
        /*
            Cover Image
        */

        if ($request->cover_image != null) {

            $file = $request->file('cover_image');

            $cover_image = 'c_' . $quiz_id . '.' . $file->getClientOriginalExtension();
            $path = $request->cover_image->move(public_path() . '/cover_images', $cover_image);
        }

        if(isset($cover_image)) $new_quiz->cover_image = $cover_image;

        $new_quiz->save();

        $qz_id = $new_quiz->id;

        return $qz_id;

    }

    /*
        Check password validation
    */

    public static function passwordValidation($confirmed = 1){

        $validation =  [
            'required',
            'min:8',
            function ($attribute, $value, $fail) {
                if (!preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,16}$/', $value)) {
                    $fail('The '.$attribute.' must be between 8 and 16 characters and contain at least one uppercase letter, one digit, and one special character.');
                }
            },
            
        ];

        if($confirmed == 1){
            $validation[] = 'confirmed';    
        }

        return $validation;
    }

    /*
        Check email validation
    */

    public static function emailValidation($unique = 1){

        $validation =  [
            'required',
            'max:150',
            function ($attribute, $value, $fail) {
                if (!preg_match('/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/', $value)) {
                    $fail('Wrong email format. Email should have (@) and (.) (ex: username@domain.com)');
                }
            },
        ];

        if($unique == 1){
            $validation[] = 'unique:users';    
        }

        return $validation;
    }
    
    public static function deleteQAfromDB($quiz_id){

        $qn_ids = Question::select('id')
            ->where('qz_id', $quiz_id)
            ->get();

        foreach ($qn_ids as $qn_id) {

            $qn_id = $qn_id->id;

            DB::delete('DELETE from answers WHERE qn_id = ?', [$qn_id]);

            Question::find($qn_id)->delete();

        }

    }

    public static function formatClarification($clarif){

        
        $start = strpos($clarif, "[Learn more](");
        $end = strrpos($clarif, ")");

        $length = intval($end) - intval($start);

        $pre_url = substr($clarif, $start, $length);

        $url = str_replace("[Learn more](", "", $pre_url);

        $text = substr($clarif, 0, strpos($clarif, "[Learn more]("));

        $link ="<a href='{$url}' target='_blank'>Read More...</a>";     

        $full = $text."<br><br>".$link;

        return $full;

    }

    public static function questionsCount($qz_id){

        $result = 0;

        $result = Question::where('qz_id', '=', $qz_id)->count();

        //dd($result);

        return $result;
    }

    public static function generateQuizToken(){
        return HomeController::generateRandomString(16);
    }

    public static function addToSession($user_id, $quiz_token){

        $quiz = Quize::where('quiz_token', $quiz_token)->first();

        $session = new Session();

        $session->quiz_id = $quiz->id;
        $session->user_id = $user_id;

        $session->save();

        return true;

    }

    public static function createSession($user_id, $quiz_id){

        $session = new Session();

        $session->user_id = $user_id;
        $session->quiz_id = $quiz_id;

        $session->save();

        return true;
    }

    public static function changeQuizStatus($quiz_id, $quiz_sts){

        $quiz = Quize::find($quiz_id);
        $quiz->quiz_sts = $quiz_sts;
        $quiz->save();

        return true;
    }

    public static function userEmailByQuiz($user_id){

        $user = User::find($user_id);

        return $user->email;
    }

    public static function changeUserEmail($user_id){

        $user = User::find($user_id);

        

    }


}
