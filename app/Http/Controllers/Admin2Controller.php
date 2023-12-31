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

use App\Http\Controllers\AdminController;

use App\Http\Controllers\HelperController;

use App\Http\Controllers\XlsxController;

use App\Http\Controllers\ManageUserControler;

use App\Http\Controllers\JsonControler;

use Illuminate\Support\Facades\DB;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Hash;

class Admin2Controller extends Controller
{

    public $layout;

    public function __construct()
    {

        ini_set('session.gc_maxlifetime', 7200);
        session_set_cookie_params(7200);

        session_start();

        /*
            Plain quiz can be added/modified just for admin and user
        */ 

        if (empty($_SESSION['admin']) && empty($_SESSION['user'])) {

            session_destroy();

            header('Location: /warden/');
            
            die();
        }

        if(!empty($_SESSION['user_id'])) $_SESSION['layout'] = "app";
        
        else $_SESSION['layout']  = "app_admin";
    }

    public function uploadDuQuiz()
    { 
        return view('admin.uploadDuQuiz');
    }

    public function doUploadDuQuiz(Request $request)
    {
        $quiz_id = HelperController::quizToDB($request);

        $_SESSION['quiz_id'] = $quiz_id;

        $quiz = Quize::find($quiz_id);

        if(!empty($_SESSION['user_id'])){
            $quiz = Quize::find($quiz_id);
            $quiz->user_id = $_SESSION['user_id'];
           
        }

        $quiz->quiz_sts = 1;

        $quiz->save();

        /*
            add session to user
        */
        $resp = HelperController::createSession($quiz->user_id, $quiz->id);

        // /* General Validation */
        /*
        Create folder folder for questions
        */
        $dir_path = public_path() . '/questions_images/q_' . $quiz_id;

        if(!is_dir($dir_path)){
            mkdir($dir_path, 0700);
        }

        return redirect('/admin/addDuQA')->with('quiz_id', $quiz_id);

    }

    public static function editQuiz($id)
    {

        $quiz = Quize::find($id);

        $_SESSION['quiz_id'] = $quiz->id;

        $cats = CategorieController::getLinkedCats($id);

        return view('admin.editQuiz', ['quiz' => $quiz, 'cats' => $cats]);

    }

    public function doEditQuiz(Request $request)
    {

        $quiz_id = $request->quiz_id;

        $quiz = Quize::find($quiz_id);

        $extra_rules = [
            'xlsx' => 'mimes:xlsx, XLSX|max:20000',
            'questions_images' => 'mimes:zip, ZIP|max:20000',
        ];

        $qz_id = $quiz_id = HelperController::quizToDB($request, $quiz_id, $extra_rules);

        $quiz->save();

        /*
            MAIN UPLOAD For XLSX or JSON
        */
        $result = true;

        if ($request->xlsx != null || $request->json != null) {
            /*
                Delete Questions and Answers from DB
            */

            $r = HelperController::deleteQAfromDB($quiz_id);

            /*
                XLSX edit
            */
            if ($request->xlsx != null) {

                $result = XLSXController::editXLSX($request, $quiz_id);

            }
            /*
                JSON
            */
            if ($request->json != null) {

                $result = JsonController::uploadJsonQA($request, $quiz_id);

            }
        }

        
        if ($result == true) {
            
            if(!empty($_SESSION['user_id'])) $url = "/myPage";
            else $url = "/admin/quizzes";

            return redirect($url)->with('success', 'Quiz successfully edited');
        } 
        else {
            return view('admin.editQuiz')->with('error', 'Wrong XLSX format. Please check structure');
        }

        return view('admin.editQuiz');

    }

    public function addDuQA(){
        
        // echo $_SESSION['quiz_id'];

        return view('admin.addDuQA');

    }

    public function doAddDuQA(Request $request)
    {
        // dd($request);

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

        $resp = HelperController::changeQuizStatus($_SESSION['quiz_id'], 1); //change to Draft

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

                /*
                    quiz status
                */
                $quiz = Quize::find($_SESSION['quiz_id']);
                // goes to approval
                if(!empty($_SESSION['quiz_id'])){
                    $quiz->quiz_sts = 2;
                } 
                //done by admin
                else{
                    $quiz->quiz_sts = 0;
                } 
                //
                $quiestions_count = HelperController::questionsCount($quiz->id);
                $msg = "";
                if($quiestions_count < 6) $msg = " However please add more question to activate your quiz!";
                //

                $quiz->save(); 

                // dd($quiz);
                
                /*
                    end status update
                */
                
                unset($_SESSION['quiz_id']);

                if(!empty($_SESSION['user_id'])){
                    return redirect('/myPage')->with('success', 'Quiz added!'.$msg);
                }
                else{
                    return redirect('/adminhome')->with('success', 'Quiz added!'.$msg);
                }
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
            'clarification' => 'max:1000',
            'q_image' => 'image|mimes:jpg,png,jpeg,gif,svg|max:200000',
            'answer_1' => 'required|max:255',
            'answer_2' => 'required|max:255',
        ]);

        $question = Question::find($request->question_id);

        $question->q_name = $request->question;

        $question->clarification = $request->clarification;

        $question->save();

        // dd($_SESSION);
        $resp = HelperController::changeQuizStatus($_SESSION['quiz_id'], 1);

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
        return redirect('/admin/editQuizQAs/'.$_SESSION['quiz_id'])->with('success', 'Question edited!');

    }

    public static function submitApproval($quiz_id){

        HelperController::changeQuizStatus($quiz_id, 2);

        return redirect('/myPage')->with('success', 'Quiz submitted for approval!');
        
    }


}
