<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use File;

use App\Admin;
use App\User;
use App\Home;

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

class JsonController extends Controller
{
    public function __construct()
    {
        // server should keep session data for AT LEAST 1 hour
        // ini_set('session.gc_maxlifetime', 3600);

        // // each client should remember their session id for EXACTLY 1 hour
        // session_set_cookie_params(3600);

        // session_start();

        // if (empty($_SESSION['admin']) || $_SESSION['admin'] != 1) {

        //     session_destroy();

        //     header('Location: /admin/');
            
        //     die();
        // }
    }



    public function uploadJson(){

        return view('admin.uploadJson');

    }


    public function doUploadJson(Request $request){


        $request->validate([
            'quiz_name' => 'required|max:255',
            'category' => 'required|max:255',
            'meta_keywords' => 'required|max:255',
            'featured' => 'max:2',
            'active' => 'max:2',
            'quiz_price' => 'required|max:255',
            'short_description' => 'required|max:255',
            'quiz_description' => 'max:1000',
            'cover_image' => 'image|mimes:jpg,png,jpeg,gif,svg|max:200000',
            'json' => 'required|mimes:json,JSON|max:20000',
            'per_part' => 'required|max:2',
        ]);

        $new_quiz = new Quize;

        $new_quiz->quiz_name = $request->quiz_name;
        $new_quiz->meta_keywords = $request->meta_keywords;

        //meta_description

        if($request->featured == 1){
            $new_quiz->featured = 1;
        }

        if($request->active == 1){
            $new_quiz->active = 1;
        }

        $new_quiz->quiz_name = $request->quiz_name;

        $new_quiz->quiz_price = $request->quiz_price;
        $new_quiz->short_description = $request->short_description;
        $new_quiz->quiz_description = $request->quiz_description;

        $new_quiz->per_part = $request->per_part;
        $new_quiz->save();

        $qz_id = $quiz_id = $new_quiz->id;
        /*
            Cover Image
        */
        $cover_image = null;

        if ($request->cover_image != null) {

            $file = $request->file('cover_image');

            $cover_image = 'c_' . $quiz_id . '.' . $file->getClientOriginalExtension();
            $path = $request->cover_image->move(public_path() . '/cover_images', $cover_image);
        }

        $new_quiz->cover_image = $cover_image;
        $new_quiz->save();

        $qz_id = $new_quiz->id;

        /*
           JSON  
        */

        $json_file = $request->file('json');
        
        $json_file_name = 'j_' . $quiz_id . '.' . $json_file->getClientOriginalExtension();

        $result = $request->json->move(public_path() . '/json_files', $json_file_name);

        //
        $path = public_path() . '/json_files/'.$json_file_name;

        $contents = File::get($path);

        $json = json_decode($contents);

        foreach($json->questions as $cur){

            $new_question = new Question;

            $new_question->qz_id = $qz_id;
            $new_question->q_name = $cur->question;

            $new_question->save();

            $qn_id = $new_question->id;

            $correct_answer = $cur->answer;
            
            $i=0;

            foreach($cur->options as $cur_option){
                
                $a_correct = ($i == $correct_answer ? 1 : 0);

                $new_answer = new Answer;

                $new_answer->qn_id = $qn_id;
                $new_answer->a_name = $cur_option;
                $new_answer->a_correct = $a_correct;

                $new_answer->save();

                $i++;

            }
        }

        return view('admin.quizzes')->with('success', 'Your JSON quiz was successfully added!');

    }

    public function testJson(){

        $path = public_path().'/json/docker.json';

    //   dd($path);
 
        // $data = file_get_contents(public_path().'/json/docker.json');

        // dd($data);

        $contents = File::get($path);

        $json = json_decode($contents);

        // dd($json->questions[0]->question);

        foreach($json->questions as $cur){

            echo $cur->question;

            //add questions to quesions

            echo "<br>";

            $correct_answer = $cur->answer;

            $i=0;

            foreach($cur->options as $cur_option){
                echo " - ".$cur_option;

                if($i == $correct_answer){
                    echo "- 1";
                }
                else{
                    echo "- 0";
                }
                $i++;

                //add answer
                echo "<br>";

            }
            echo "<br>";
        }

        // return view('admin.uploadJson');
    }


    // BUNDLE

    public function uploadBundle(){
        return view('bundle');
    }

    public static function filterQuizzes(Request $request){

        // Simulate fetching categories based on the keyword
        $keyword = $_GET['keyword'] ?? '';

        $quizes = Quize::where('quiz_name', 'like', '%'.$request->keyword.'%')->get();

        $categories = [];

        foreach($quizes as $quiz){
            $categories[] = ["ID" => $quiz->id, "Category_name" => $quiz->quiz_name];
        }

        // $categories = [
        //     ["ID" => 1, "Category_name" => "Cat1"],
        // ];

        header('Content-Type: application/json');

        echo json_encode($categories);

    }

    public static function doUploadBundle(Request $request){

        dd($request);

    }

}
