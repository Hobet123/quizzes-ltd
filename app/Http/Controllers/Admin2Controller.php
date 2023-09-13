<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use File;

use App\Admin;
use App\User;
use App\Home;

use App\Session;
use App\Find;

use App\Xlsx;
use App\Quize;
use App\Question;
use App\Answer;

use ZipArchive;

use App\Http\Controllers\XlsxController;

use App\Http\Controllers\ManageUserControler;
use App\Http\Controllers\JsonControler;

use Illuminate\Support\Facades\DB;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Hash;

class Admin2Controller extends Controller
{
    public function __construct()
    {
        // server should keep session data for AT LEAST 1 hour
        ini_set('session.gc_maxlifetime', 3600);

        // each client should remember their session id for EXACTLY 1 hour
        session_set_cookie_params(3600);

        session_start();

        if (empty($_SESSION['admin']) || $_SESSION['admin'] != 1) {

            session_destroy();

            header('Location: /admin/');
            
            die();
        }
    }



    public function uploadDuQuiz()
    {
        return view('admin.uploadDuQuiz');
    }

    public function startDuQuiz(Request $request)
    {

        $request->validate([
            'quiz_name' => 'required|max:255',
            'quiz_order' => 'integer|max:2000',
    
            'category' => 'required|max:255',
            'meta_keywords' => 'required|max:255',
            'featured' => 'max:2',
            'active' => 'max:2',
            'quiz_price' => 'required|numeric|max:100000',
            'short_description' => 'required|max:1000',
            'quiz_description' => 'max:10000',
            'cover_image' => 'image|mimes:jpg,png,jpeg,gif,svg|max:200000',
            'per_part' => 'required|max:2',
        ]);

        $new_quiz = new Quize;

        $new_quiz->quiz_name = $request->quiz_name;
        $new_quiz->quiz_order = ($request->quiz_order) ? $request->quiz_order : 777;

        $new_quiz->sef_url = JsonController::setSEFurl($request->quiz_name);

        $new_quiz->category = $request->category;
        $new_quiz->meta_keywords = $request->meta_keywords;

        $new_quiz->featured = ($request->featured == 1) ? 1 : 0;
        $new_quiz->active = ($request->active == 1) ? 1 : 0;

        $new_quiz->quiz_price = $request->quiz_price;
        $new_quiz->short_description = $request->short_description;
        $new_quiz->quiz_description = $request->quiz_description;
        $new_quiz->per_part = $request->per_part;

        $new_quiz->save();

        $quiz_id = $new_quiz->id;

        $_SESSION['quiz_id'] = $quiz_id;

        /*
            Cover Image Upload
        */
        
        $cover_image = null;

        if ($request->cover_image != null) {

            $file = $request->file('cover_image');

            $cover_image = 'c_' . $quiz_id . '.' . $file->getClientOriginalExtension();
            $path = $request->cover_image->move(public_path() . '/cover_images', $cover_image);
        }

        $new_quiz->cover_image = $cover_image;
        $new_quiz->save();

        /*
        End Cover Uplad

        Create folder folder for questions
        */
        $dir_path = public_path() . '/questions_images/q_' . $quiz_id;

        if(!is_dir($dir_path)){
            mkdir($dir_path, 0700);
        }

        return redirect('/admin/addDuQA')->with('quiz_id', $quiz_id);

    }

    public function addDuQA(){

        return view('admin.addDuQA');

    }

    public function doAddDuQA(Request $request)
    {
        $request->validate([
            'question' => 'required|max:255',
            'clarification' => 'max:1000',
            'q_image' => 'image|mimes:jpg,png,jpeg,gif,svg|max:200000',
            'answer_1' => 'required|max:255',
            'answer_2' => 'required|max:255',
        ]);

        $question = new Question;
        $question->qz_id = $_SESSION['quiz_id'];
        $question->q_name = $request->question;
        $question->clarification = $request->clarification;
        $question->save();

        if(isset($request->clarification) && $request->clarification != NULL){
            $quize = Quize::find($_SESSION['quiz_id']);
            $quize->clarif_flag = 1;
            $quize->save(); 
        }


        $question_id = $question->id;
        /*
            question Image Upload
        */
        $q_image = NULL; 

        if ($request->q_image != null) {

            $file = $request->file('q_image');
            $q_image = 'ques_' . $question_id . '.' . $file->getClientOriginalExtension();
            $path = $request->q_image->move(public_path() . '/questions_images/q_'.$_SESSION['quiz_id'].'/sample_images', $q_image);
        }

        $question->q_image = $q_image;
        $question->save();
        /*
            End Question Image Upload
        */

        for($i = 1; $i < 20; $i++){

            $temp = "answer_".$i;

            if(isset($request->$temp)){
                
                $answer = new Answer;

                $answer->qn_id = $question_id;
                $answer->a_name = $request->$temp;

                if($request->correct_a == $i){
                    $answer->a_correct = 1;
                }

                $answer->save();

            }
            
        }

        if(isset($_SESSION['editing_quiz']) && $_SESSION['editing_quiz'] == 1){

            $quiz_id = $_SESSION['quiz_id'];

            unset($_SESSION['editing_quiz']);
            unset($_SESSION['quiz_id']);
            
            return redirect('/admin/editQuizQAs/'.$quiz_id)->with('success', 'Question added!');
        }
        else{
            if($request->submit == "Add Question"){
                return redirect('/admin/addDuQA')->with('success', 'Question added!');
            }
            if($request->submit == "Finish"){
                return redirect('/adminhome')->with('success', 'Quiz added!');
            }
        }
    }

    public static function editQuizQAs($id)
    {
        $_SESSION['quiz_id'] = $id;

        $quiz = Quize::find($id);

        $questions = Question::where('qz_id', $id)
                                    ->orderBy('q_order', 'asc')
                                    ->orderBy('id', 'asc')
                                    ->get();

        return view('admin.editQuizQAs', ['quiz' => $quiz, 'questions' => $questions]);

    }

    public static function deleteQuestion($quiz_id, $qn_id)
    {

        $r = Answer::where('qn_id', $qn_id)->delete();
        
        $r2 = Question::find($qn_id)->delete();

        return redirect('/admin/editQuizQAs/'.$quiz_id);

    }

    public static function questionsOrder(Request $request)
    {
        $input = $request->all();

        $i=0;

        foreach($input as $key => $value){

            if(strpos($key, "estion")){
                $question = Question::find($value);
                $question->q_order = $i++;
                $question->save();
            }

        }
        return redirect('/admin/editQuizQAs/'.$input['quiz_id']);
    }

    public static function addDuQATo($quiz_id)
    {

        $_SESSION['quiz_id'] = $quiz_id;
        $_SESSION['editing_quiz'] = 1;

        // dd($_SESSION['editing_quiz']);

        return view('admin.addDuQA');

    }

    public function editDuQA($question_id){

        // dd($_SESSION['quiz_id']);

        $_SESSION['question_id'] = $question_id;

        $question = Question::find($question_id);

        $answers = Answer::where('qn_id', $question_id)->get();
        
        // dd($question);

        return view('admin.editDuQA', ['question' => $question, 'answers' => $answers]);
    }

    public function doEditDuQA(Request $request)
    {
        // dd($request);

        $request->validate([
            'question' => 'required|max:255',
            'цларифицатион' => 'max:1000',
            'q_image' => 'image|mimes:jpg,png,jpeg,gif,svg|max:200000',
            'answer_1' => 'required|max:255',
            'answer_2' => 'required|max:255',
        ]);

        $question = Question::find($request->question_id);

        $question->q_name = $request->question;

        $question->clarification = $request->clarification;

        $question->save();

        $question_id = $question->id;

        /*
            question Image Upload if new
        */

        if ($request->q_image != null) {

            // remove old image

            File::delete(public_path() . '/questions_images/q_'.$_SESSION['quiz_id'].'/sample_images/'. $question->q_image);

            $file = $request->file('q_image');

            // dd($question_id);

            $q_image = 'ques_' . $question->id . '.' . $file->getClientOriginalExtension();

            $path = $request->q_image->move(public_path() . '/questions_images/q_'.$_SESSION['quiz_id'].'/sample_images', $q_image);

            $question->q_image = $q_image;

            $question->save();
        }
        /*
            End Question Image Upload
        */
        /*
            Answers
        */

        //remove old answers
        Answer::where('qn_id', $question_id)->delete();

        for($i = 1; $i < 20; $i++){

            $temp = "answer_".$i;

            if(isset($request->$temp)){
                
                $answer = new Answer;

                $answer->qn_id = $question_id;
                $answer->a_name = $request->$temp;

                if($request->correct_a == $i){
                    $answer->a_correct = 1;
                }

                $answer->save();

            }
            
        }

        return redirect('/admin/editQuiz/'.$_SESSION['quiz_id'])->with('success', 'Question edited!');

    }

    // main admin menu links

    public static function users()
    {
        
        $users = User::all();

        return view('admin.users')->with('users', $users);

    }

    public static function sessions()
    {
        
        $sessions = Session::all();

        return view('admin.sessions')->with('sessions', $sessions);

    }

    public static function finds()
    {
        
        $finds = Find::orderBy('id', 'desc')->get();

        return view('admin.finds')->with('finds', $finds);

    }

    public static function quizzes()
    {

        $quizzes = Quize::where('is_bundle', 0 )->get();

        return view('admin.quizzes')->with('quizzes', $quizzes);
        
    }

    public static function bundles()
    {

        $bundles = Quize::where('is_bundle', 1)->get();

        return view('admin.bundles')->with('bundles', $bundles);
        
    }


}
