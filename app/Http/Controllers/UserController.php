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

use App\BlueMail;

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

        ini_set('session.gc_maxlifetime', 7200);

        session_set_cookie_params(7200);

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

        $sessions = DB::select('SELECT s.id, s.quiz_id as quiz_id, s.user_id as user_id, q.quiz_name as quiz_name,  q.clarif_flag as clarif_flag,q.cover_image as cover_image, u.username as username
                                FROM sessions s 
                                LEFT JOIN quizes q 
                                ON s.quiz_id = q.id
                                LEFT JOIN users u
                                ON s.user_id = u.id
                                WHERE s.user_id = :user_id
                                GROUP by s.id', ['user_id' => $user_id]);
        
        $quizzes = Quize::where('user_id', $user_id)->get();

        foreach($quizzes as $quiz){
            $quiz->quiestions_count = HelperController::questionsCount($quiz->id);
        }

        return view('myPage', ['user' => $user, 'sessions' => $sessions, 'quizzes' => $quizzes]);

    }

    public function quizHome(Request $request){

        $_SESSION['quiz_id'] = $request->id;

        if($request->educational == 1) $_SESSION['educational'] = 1;
        else $_SESSION['educational'] = 0;
        
        $quiz = Quize::find($request->id);

        $quiz->short_description = JsonController::cleanTags($quiz->short_description);
        $quiz->quiz_description = JsonController::cleanTags($quiz->quiz_description);

        if($quiz->is_bundle == 1){

            $linked = JsonController::getLinkedQuizes($request->id);

            return view('quizHome', ['quiz' => $quiz, 'linked' => $linked]);
            
        }
        else{

            $trial = 0;

            if(!empty($_SESSION['try_quiz'])){
                // dd("try");
                $_SESSION['educational'] = 1;
                $questions = HelperController::trialQuestions($request->id);

                // dd($questions);

                $trial = 1;

                $parts = 1;
            }
            else{

                $questions = Question::where('qz_id', $quiz->id)->orderBy('q_order', 'asc')->get();

                $this->totalPerPart = $quiz->per_part;

                $parts  = (int)(ceil(count($questions)/$this->totalPerPart));
            }

            if(!isset($questions[0])){
                return redirect('/')->with('error', 'Empty Quiz');
            }
            // dd($questions[0]);

            $_SESSION['questions'] = $questions;


            return view('quizHome', ['quiz' => $quiz, 'questions' => $questions, 'parts' => $parts, 'per_part' => $this->totalPerPart]);

        }

    }

    public static function quizQuestion(Request $request){

        if(!isset($_SESSION['quiz_id']) || empty($_SESSION['quiz_id']) || empty($_SESSION['questions']) || !isset($_SESSION['questions'])){
            return redirect('/quizes');
        }

        $quiz = Quize::find($_SESSION['quiz_id']);

        $qn_index = $request->id; // number in array of total < -------

        if(!isset($_SESSION['questions'][$qn_index]) || empty($_SESSION['questions'][$qn_index])){
            return redirect('/quizes');
        }

        $qn_id = $_SESSION['questions'][$qn_index]->id;

        $question = Question::find($qn_id); // < ------- Find plz

        if($question == NULL){
            return redirect('/')->with('error', 'Empty Quiz');
        }

        $answers = Answer::where('qn_id', $qn_id)->get(); // < ------ of all

        $aferDot = $qn_index%$quiz->per_part;

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

        return view('quizQuestion', ['qn_index' => $qn_index, 'question' => $question, 'answers' => $answers]);

    }

    public function quizAnswer(Request $request){

        if(!isset($_SESSION['quiz_id']) || empty($_SESSION['quiz_id'])){
            return redirect('/quizes');
        }

        $qn_index = $request->qn_index;

        $qn_id = $_SESSION['questions'][$qn_index]->id;

        $question = Question::find($qn_id); // < -------

       // if($question->clarification != NULL) $question->clarification = HelperController::formatClarification($question->clarification);
        if($question->clarification != NULL) $question->clarification = $question->clarification;

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

        return view('quizAnswer', 
                ['qn_index' => $qn_index, 
                'question' => $question, 
                'correct_a' => $correct_a, 
                'correct_a_flag' => $correct_a_flag,
                'final_flag' => $final_flag,
                ]);

    }
    
    public function quizFinal(Request $request){

        if(!isset($_SESSION['quiz_id']) || empty($_SESSION['quiz_id'])){
            return redirect('/quizes');
        }

        return view('quizFinal', ['quiz_id' => $_SESSION['quiz_id']]);

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
        
        $params = explode("-", $param1);

        $user_id = $params[0];
        
        $quizes = explode(".", $params[1]);

        array_pop($quizes);

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
            'old_password' => 'required|min:5',
            // 'password' => 'required|min:6|confirmed',
            // 'password_confirmation' => 'required|min:6',
            'password' => HelperController::passwordValidation(1),
        ]);

        $user = User::where('password', $request->old_password)->first();

        if(empty($user->email)){
            return view('changePassword')->with('error', 'Wrong Password!');
        }

        $user->password = $request->password;
        $user->save();

        return redirect('/myPage')->with('success', 'Your Password has beed changed!');

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

    public function disableQuiz($id){

        $quiz = Quize::find($id);
        $quiz->quiz_sts = 3;
        $quiz->save();

        return redirect('/myPage')->with('success', 'The Quiz has been disabled. It won\'t appear on website directories!');
    }

    public function inviteQuiz(){

        $quizzes = Quize::where('user_id', $_SESSION['user_id'])
                            ->where('quiz_sts', 0)
                            ->get();

        $user = User::find($_SESSION['user_id']);

        // dd($user);

        return view('inviteQuiz', ['quizzes' =>  $quizzes, 'user' => $user]);
    }

    public function doInviteQuiz(Request $request){

        $request->validate([

            'quiz_id' => 'required|max:5',
            'friend_name' => 'required|max:255',
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
        ]);

        $quiz = Quize::find($request->quiz_id);

        $info = [
            'quiz' => $quiz,
            'friend_name' => $request->friend_name,
            'email' => $request->email,
        ];

        $responce = BlueMail::sendQuizInvite($info);

        return redirect('/myPage')->with('success', 'The invite has beeen sent!');
    }

    public static function changeUserEmail(){

        return view('changeUserEmail');

    }
    //
    public static function doChangeUserEmail(Request $request){

        $request->validate([
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
        ]); 

        $user = User::find($_SESSION['user_id']);

        $user->email_temp = $request->email;

        $user->email_change_hash = HomeController::generateRandomString(16);

        $user->save();

        $responce = BlueMail::changeEmailEmail($_SESSION['user_id'], $user->email_temp, $user->email_change_hash);

        return redirect('/myPage')->with('success', 'Check your new email ('.$request->email.') for confirmation!');

    }

}
