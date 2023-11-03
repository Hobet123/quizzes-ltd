<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use File;

use App\Admin;
use App\User;
use App\Home;

use App\Session;
use App\Find;

use App\BlueMail;

use App\Xlsx;
use App\Quize;
use App\Question;
use App\Answer;

use ZipArchive;

use App\Http\Controllers\XlsxController;

use App\Http\Controllers\ManageUserControler;
use App\Http\Controllers\JsonControler;

use App\Http\Controllers\CategorieControler;

// use Spatie\Geocoder\Facades\Geocoder;
use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Crypt;
/*
*/
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\Filesystem;
/*
*/

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function __construct()
    {
        ini_set('session.gc_maxlifetime', 7200);
        session_set_cookie_params(7200);

        session_start();

        /*
            Plain quiz can be added/modified just for admin and user
        */ 

        if (empty($_SESSION['admin'])) {

            // session_destroy();

            header('Location: /warden/');
            
            die();
        }

        if(!empty($_SESSION['user_id'])) $_SESSION['layout'] = "app";
        else $_SESSION['layout']  = "app_admin";
        //ALTER TABLE tablename AUTO_INCREMENT = 1  
    }

    public function index()
    {
        $quizes = Quize::all();

        $users = User::all();

        $sessions = Session::all();

        //dd($sessions);

        $data = [];
        $data[0] = $quizes;
        $data[1] = $users;
        $data[2] = $sessions;

        return view('admin.adminhome')->with('data', $data);
    }

    public function uploadQuiz()
    {
        return view('admin.uploadQuizNew');
    }

    public function doUploadQuiz(Request $request)
    {

        $extra_rules = [
            'xlsx' => 'mimes:xlsx, XLSX|max:20000',
            'questions_images' => 'mimes:zip, ZIP|max:20000',
            'json' => 'mimes:json, XLSX|max:20000',
        ];

        if($request->xlsx == null && $request->json == null){
            return view('admin.uploadQuizNew')->with('error', 'Select ether XLSX or JSON file!');
        }

        $qz_id = $quiz_id = HelperController::quizToDB($request, $qz_id = 0, $extra_rules);

        /*
            public static function editXLSX()
        */
        if ($request->xlsx != null) {
            $result = XLSXController::editXLSX($request, $quiz_id);
        }
        // end xlsx
        /*
            JSON
        */
        if ($request->json != null) {
            $result = JsonController::uploadJsonQA($request, $quiz_id);
        }
        // end json

        if ($result == true) {
            return redirect('/admin/quizzes')->with('success', 'Quiz successfully uploaded');
        } else {
            return view('admin.uploadQuizNew')->with('error', 'Wrong XLSX format. Please check sructure');
        }

        return view('admin.uploadQuizNew');

        //return redirect('/adminhome')->with('success', 'You are successfuly logged in as admin!');
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

        $quiz->quiz_sts = $request->quiz_sts;

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

    public static function addSession()
    {

        $quizes = Quize::all();

        return view('admin.addsession')->with('quizes', $quizes);
    }

    public static function logout(){

        session_destroy();
        // $_SESSION['admin'];
        // $_SESSION['admin_username'];
        // $_SESSION['super_admin'];

        return redirect('/adminhome');

    }

    /*
        Static Pages Home Settings
    */

    public static function homeSetting()
    {
        $home = Home::find(1);

        return view('admin.homeSetting')->with('home', $home);

    }

    public static function doHomeSetting(Request $request)
    {
        // dd($request);

        $request->validate([
            'title' => 'required|max:255',
            'meta_keywords' => 'max:1000',
            'meta_description' => 'max:1000',
            'main_text' => 'max:1000',
            'copyright' => 'max:255',
        ]);

        $home_id = $request->home_id;

        $home = Home::find($home_id);

        $home->title = $request->title;

        $home->meta_keywords = $request->meta_keywords;
        $home->meta_description = $request->meta_description;

        $home->main_text = $request->main_text;

        $home->copyright = $request->copyright;

        $home->save();

        return redirect('/')->with('success', 'Updated!');

    }

    /*
        Edit static static pages
    */

    public static function pages()
    {
        $pages = Home::get();

        return view('admin.pages')->with('pages', $pages);

    }

    public static function editPage($id)
    {

        $page = Home::find($id);

        return view('admin.editPage')->with('page', $page);

    }

    public static function doEditPage(Request $request)
    {

        $request->validate([
            'page_name_url' => 'required|max:255',
            'title' => 'required|max:255',
            'meta_keywords' => 'max:1000',
            'meta_description' => 'max:1000',
            'main_text' => 'max:1000000',
            'copyright' => 'max:255',
        ]);

        $page_id = $request->page_id;

        $home = Home::find($page_id);

        $home->page_name_url = $request->page_name_url;

        $home->title = $request->title;

        $home->meta_keywords = $request->meta_keywords;
        $home->meta_description = $request->meta_description;

        $home->main_text = $request->main_text;

        $home->copyright = $request->copyright;

        $home->save();

        return redirect('/admin/pages')->with('success', 'Page Updated!');

    }

    /*
        Create Static Pages and views
    */

    public static function createPage()
    {

        return view('admin.createPage');

    }

    public static function doCreatePage(Request $request)
    {

        $request->validate([
            'page_name_url' => 'required|max:255',
            'title' => 'required|max:255',
            'meta_keywords' => 'max:1000',
            'meta_description' => 'max:1000',
            'main_text' => 'max:1000000',
            'copyright' => 'max:255',
        ]);

        $home = new Home;

        $home->page_name_url = $request->page_name_url;

        $home->title = $request->title;

        $home->meta_keywords = $request->meta_keywords;

        $home->meta_description = $request->meta_description;

        $home->main_text = $request->main_text;

        $home->copyright = $request->copyright;

        $home->save();

        return redirect('/admin/pages')->with('success', 'Page Created!');

    }

/*
    Admin menu pages
*/

    public static function users()
    {
        
        $users = User::where('is_admin', 0)->get();
        $admins = User::where('is_admin', "!=", 0)->get();

        return view('admin.users')->with(['users' => $users, 'admins' => $admins]);

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

        foreach($quizzes as $quiz){
            $quiz->quiestions_count = HelperController::questionsCount($quiz->id);
        }

        return view('admin.quizzes')->with('quizzes', $quizzes);
        
    }

    public static function bundles()
    {

        $bundles = Quize::where('is_bundle', 1)->get();

        return view('admin.bundles')->with('bundles', $bundles);
        
    }

    public static function grantAccess($user_id){

        $user = User::find($user_id);

        $responce = BlueMail::grantAccess($user->email, $user->username);

        return redirect("/admin/users")->with("success", "Grant Access Email has been sent to user");

    }

    public static function rrmdir($dir) {
        if (is_dir($dir)) {
          $objects = scandir($dir);
          foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
              if (filetype($dir."/".$object) == "dir") 
                 rrmdir($dir."/".$object); 
              else unlink   ($dir."/".$object);
            }
          }
          reset($objects);
          rmdir($dir);
        }
    }

    public function deleteQuiz(Request $request){

        // dd($request);

        $quiz_id = $request->id;

        $result = DB::delete('delete from bundle_quize where qz_id = :qz_id', ['qz_id' => $quiz_id]);
        $result = DB::delete('delete from categorie_quize where qz_id = :qz_id', ['qz_id' => $quiz_id]);
        $result = DB::delete('delete from sessions where quiz_id = :quiz_id', ['quiz_id' => $quiz_id]);

        $qn_ids = Question::select('id')
            ->where('qz_id', $quiz_id)
            ->get();

        foreach ($qn_ids as $qn_id) {
            $qn_id = $qn_id->id;
            DB::delete('DELETE from answers WHERE qn_id = ?', [$qn_id]);
            Question::find($qn_id)->delete();
        }

        $quiz = Quize::find($quiz_id);

        $cover_image = $quiz->cover_image;

        Quize::find($quiz_id)->delete();

        Session::where('quiz_id', $quiz_id)->delete();

        /* 
            Delete Cover Image and questions images
        */
        Storage::deleteDirectory(public_path()."public/question_images/q_".$quiz_id);
        File::deleteDirectory(public_path()."/question_images/q_".$quiz_id);

        return redirect('/admin/quizzes')->with('success', 'Quiz deleted');
    }



}
