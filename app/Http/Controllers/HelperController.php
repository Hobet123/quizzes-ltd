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

use App\Http\Controllers\XlsxController;

use App\Http\Controllers\ManageUserControler;

use Illuminate\Support\Facades\DB;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Hash;

class HelperController extends Controller
{
    public function __construct()
    {}

    public static function initialRules(){

        $initial_rules = [
            'quiz_name' => 'required|max:255',
            'quiz_order' => 'integer|max:2000',
            'category' => 'required|max:255',
            'meta_keywords' => 'required|max:255',
            'featured' => 'max:2',
            'active' => 'max:2',
            'quiz_price' => 'required|numeric|max:100000',
            'short_description' => 'required|max:1000',
            'quiz_description' => 'max:100000',
            'cover_image' => 'image|mimes:jpg,png,jpeg,gif,svg|max:200000',
        ];

        return $initial_rules;
    }

    public static function quizToDB($request, $qz_id = 0, $extra_rules = []){

        $initial_rules = [
            'quiz_name' => 'required|max:255',
            'quiz_order' => 'integer|max:2000',

            'category' => 'required|max:255',
            'meta_keywords' => 'required|max:255',
            'featured' => 'max:2',
            'active' => 'max:2',
            'quiz_price' => 'required|max:255',
            'short_description' => 'required|max:1000',
            'quiz_description' => 'max:100000',
            'cover_image' => 'image|mimes:jpg,png,jpeg,gif,svg|max:200000',
        ];

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

        $new_quiz->sef_url = self::setSEFurl($request->quiz_name);

        $new_quiz->category = $request->category;
        $new_quiz->meta_keywords = $request->meta_keywords;

        $new_quiz->featured = ($request->featured == 1) ? 1 : 0;
        $new_quiz->active = ($request->active == 1) ? 1 : 0;
        $new_quiz->is_bundle = ($request->is_bundle == 1) ? 1 : 0;

        $new_quiz->quiz_price = $request->quiz_price;
        $new_quiz->short_description = $request->short_description;
        $new_quiz->quiz_description = $request->quiz_description;

        // $new_quiz->per_part = $request->per_part;
        $new_quiz->save();

        $qz_id = $quiz_id = $new_quiz->id;
        /*
            Cover Image
        */
        // $cover_image = null;

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

    public static function trialQuestions_back($quiz_id){

        $questions = Question::where('qz_id', $quiz_id)->get();

        // dd($questions);

        $increment = intval(count($questions) / 5);

        // dd($increment);

        $startIndex = $increment - 1;
        
        $selectedQuesions = [];

        if($increment == 0){
            $selectedQuesions = $questions;
        }
        else{
            for ($i = $startIndex; $i < count($questions); $i += $increment) {
                $selectedQuesions[] = $questions[$i]['id'];
            }

            $selectedQuesions = Question::whereIn('id', array_values($selectedQuesions))->get();

            // dd($questions);

            // dd(array_values($selectedQuesions));
            //$indexedStudentIds = array_values($studentIds);
        }
        

        
        // dd($selectedQuesions);

        return $selectedQuesions;

    }

    public static function trialQuestions($quiz_id){

        $count = Question::where('qz_id', $quiz_id)->count();

        // $count = count($questions);

        if($count > 60) $limit = 8;
        if($count > 30) $limit = 6;
        else $limit = 5;

        $sQuesions = Question::where('qz_id', $quiz_id)->limit($limit)->get();

        // dd($sQuesions);

        return $sQuesions;

    }

    public static function userRules(){

        $user_rules = [

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
            'password' => [
                'required',
                'min:8',
                'confirmed',
                function ($attribute, $value, $fail) {
                    if (!preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,16}$/', $value)) {
                        $fail('The '.$attribute.' must be between 8 and 16 characters and contain at least one uppercase letter, one digit, and one special character.');
                    }
                },
                
            ],
        ];

        return $user_rules;

    }

    public static function userRulesEdit(){

        $user_rules = [

            'username' => 'required|max:30',
            'email' => [
                'required',
                'max:150',
                // 'unique:users',
                function ($attribute, $value, $fail) {
                    if (!preg_match('/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/', $value)) {
                        $fail('Wrong email format. Email should have (@) and (.) (ex: username@domain.com)');
                    }
                },
            ],
            'phone' => 'max:15',
            'password' => [
                'required',
                'min:8',
                // 'confirmed',
                function ($attribute, $value, $fail) {
                    if (!preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,16}$/', $value)) {
                        $fail('The '.$attribute.' must be between 8 and 16 characters and contain at least one uppercase letter, one digit, and one special character.');
                    }
                },
                
            ],
        ];

        return $user_rules;

    }
    //userRulesEdit


}
