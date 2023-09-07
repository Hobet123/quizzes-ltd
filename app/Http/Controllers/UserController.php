<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Admin;
use App\User;
use App\Session;
use App\Home;

use App\Xlsx;
use App\Quize;
use App\Question;
use App\Answer;

use ZipArchive;

use App\Http\Controllers\XlsxController;
use App\Http\Controllers\JsonController;

use App\Http\Controllers\HelperController;

// use Spatie\Geocoder\Facades\Geocoder;
use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Crypt;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->totalPerPart = 6;

        session_start();

        if (empty($_SESSION['user']) || $_SESSION['user'] != 1) {

            session_destroy();

            header('Location: /error');

            die();
        }
    }

    public function myPage(){
        
        unset($_SESSION['educational']);
        
        $user_id = $_SESSION['user_id'];

        $user = User::find($user_id);

        $sessions = DB::select('SELECT s.id, s.quiz_id as quiz_id, s.user_id as user_id, q.quiz_name as quiz_name, q.cover_image as cover_image, u.username as username
                                FROM sessions s 
                                LEFT JOIN quizes q 
                                ON s.quiz_id = q.id
                                LEFT JOIN users u
                                ON s.user_id = u.id
                                WHERE s.user_id = :user_id
                                GROUP by s.id', ['user_id' => $user_id]);
            

        return view('myPage', ['user' => $user, 'sessions' => $sessions]);
    }

    public function quizHome(Request $request){

        // dd($request->id);
        // dd($_SESSION);
        // dd($request->educational);

        $_SESSION['quiz_id'] = $request->id;

        if($request->educational == 1) $_SESSION['educational'] = 1;
        else $_SESSION['educational'] = 0;
        
        $quiz = Quize::find($request->id);

        $quiz->short_description = JsonController::cleanTags($quiz->short_description);
        $quiz->quiz_description = JsonController::cleanTags($quiz->quiz_description);

        if($quiz->is_bundle == 1){

            $linked = JsonController::getLinkedQuizes($request->id);

            return view('quizHome', ['quiz' => $quiz, 'linked' => $linked]);

            // dd("here");
            
        }
        else{

            $trial = 0;

            if(!empty($_SESSION['try_quiz'])){
                $questions = HelperController::trialQuestions($request->id);
                $trial = 1;
            }
            else{
                $questions = Question::where('qz_id', $quiz->id)->orderBy('q_order', 'asc')->get();
            }

            // dd(count($questions));

            $_SESSION['questions'] = $questions;

            // dd($questions);

            $this->totalPerPart = $quiz->per_part;

            $parts  = (int)(ceil(count($questions)/$this->totalPerPart));

            return view('quizHome', ['quiz' => $quiz, 'questions' => $questions, 'parts' => $parts, 'per_part' => $this->totalPerPart]);

        }

    }

    public static function quizQuestion(Request $request){

        $quiz = Quize::find($_SESSION['quiz_id']);

        $qn_index = $request->id; // number in array of total < -------

        // dd($qn_index);

        $qn_id = $_SESSION['questions'][$qn_index]->id;

        $question = Question::find($qn_id); // < ------- Find plz
        
        // dd("here")

        $answers = Answer::where('qn_id', $qn_id)->get(); // < ------ of all

        // dd($answers);

        $aferDot = $qn_index%$quiz->per_part;

        // dd($aferDot);

        if($aferDot == 0){

            $_SESSION['correct'] = 0;

            $_SESSION['qns_count'] = 1; 

            $_SESSION['cur_qns'] = $cur_qns = $_SESSION['questions']->slice($qn_index, $quiz->per_part);

            ///////////////////////////////

            $_SESSION['total_qns'] = count($cur_qns); // <---important for count

            ////////////////////////////////////

        }
        else{
            ++$_SESSION['qns_count']; // <------
        }

        //dd($question);

        return view('quizQuestion', ['qn_index' => $qn_index, 'question' => $question, 'answers' => $answers]);

    }

    public function quizAnswer(Request $request){

        // dd($_SESSION['educational']);

        $qn_index = $request->qn_index;

        $qn_id = $_SESSION['questions'][$qn_index]->id;

        $question = Question::find($qn_id); // < -------

        if($question->clarification != NULL) $question->clarification = self::formatClarification($question->clarification);

        $answers = Answer::where('qn_id', $qn_id)->get();

        foreach($answers as $answer){
            if($answer->a_correct == 1){
                $correct_a = $answer;
                break;
            }
        }

        $correct_a_flag = 0;

        if($correct_a->id == $request->answer_id){
            $correct_a_flag = 1;
            ++$_SESSION['correct'];
        }

        $final_flag = 0;

        if($_SESSION['qns_count'] == $_SESSION['total_qns']){
            $final_flag = 1;
        }

        // dd($correct_a->a_name);

        return view('quizAnswer', 
                ['qn_index' => $qn_index, 
                'question' => $question, 
                'correct_a' => $correct_a, 
                'correct_a_flag' => $correct_a_flag,
                'final_flag' => $final_flag,
                ]);

    }
    
    public function quizFinal(Request $request){

      return view('quizFinal');

    }

    public function logout(){

        session_destroy();

        return redirect('/');

    }

    public function addSessions(Request $request){

        if(!empty($request->param1)){
            $param1 = $request->param1;
        }
        if(!empty($_GET['param1'])){
            $param1 = $_GET['param1'];
        }

        // dd($param1);
        
        $params = explode("-", $param1);

        $user_id = $params[0];
        
        $quizes = explode(".", $params[1]);

        array_pop($quizes);

        // echo $user_id;

        // dd($quizes);

        foreach($quizes as $cur){
            $s = new Session;
            $s->quiz_id = $cur;
            $s->user_id = $user_id;
            $s->save();
        }

        return redirect("/myPage")->with('flag', 1);

    }

    public function doChangePassword(Request $request){

        $request->validate([
            'old_password' => 'required|min:6',
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required|min:6',
        ]);

        $user = User::where('password', $request->old_password)->first();

        if(empty($user->email)){
            return view('changePassword')->with('error', 'Wrong Password!');
        }

        $user->password = $request->password;
        $user->save();

        // dd($user);

        return redirect('/')->with('success', 'Your Password has beed changed!');

    }

    public function doChangeUsername(Request $request){

        $request->validate([
            'new_username' => 'required|min:4',
        ]);

        $user = User::where('id', $_SESSION['user_id'])->first();

        $user->username = $request->new_username;
        $user->save();

        $_SESSION['username'] = $request->new_username;

        return redirect('/myPage')->with('success', 'Your Username has beed changed!');


    }
    
    public function checkout(){
        return view('checkout');
    }

    public function changePassword(){
        return view('changePassword');
    }

    public function changeUsername(){
        return view('changeUsername');
    }

    public static function formatClarification($clarif){

        
        $start = strpos($clarif, "[Learn more](");
        $end = strrpos($clarif, ")");

        $length = intval($end) - intval($start);

        $pre_url = substr($clarif, $start, $length);

        $url = str_replace("[Learn more](", "", $pre_url);

        $text = substr($clarif, 0, strpos($clarif, "[Learn more]("));

        $link ="<a href='{$url}' target='_blank'>Read More...</a>";     

        // dd($link);

        $full = $text."<br><br>".$link;

        // dd($full);

        return $full;

        //[Learn more](

        //(https://docs.oracle.com/javase/tutorial/java/IandI/abstract.html)

    }
}
