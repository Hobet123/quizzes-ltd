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
        // server should keep session data for AT LEAST 1 hour
        ini_set('session.gc_maxlifetime', 3600);

        // each client should remember their session id for EXACTLY 1 hour
        session_set_cookie_params(3600);

        session_start();

        if (empty($_SESSION['admin']) || $_SESSION['admin'] != 1) {

            session_destroy();

            header('Location: /warden');
            
            die();
        }

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

    public function deleteQuiz(Request $request){

        // dd($request);

        $quiz_id = $request->id;

        $result = DB::delete('delete from bundle_quize where qz_id = :qz_id', ['qz_id' => $quiz_id]);

        $result = DB::delete('delete from categorie_quize where qz_id = :qz_id', ['qz_id' => $quiz_id]);

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

        return redirect('/adminhome')->with('success', 'Quiz deleted');
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

    public function uploadQuiz()
    {
        return view('admin.uploadquiz');
    }

    public function doUploadQuiz(Request $request)
    {

        $request->validate([
            'quiz_name' => 'required|max:255',
            'quiz_order' => 'integer|max:2000',

            'category' => 'max:255',

            'meta_keywords' => 'required|max:255',
            'featured' => 'max:2',
            'active' => 'max:2',
            'quiz_price' => 'required|numeric|max:100000',
            'short_description' => 'required|max:1000',
            'quiz_description' => 'max:10000',
            'cover_image' => 'image|mimes:jpg,png,jpeg,gif,svg|max:200000',
            'xlsx' => 'required|mimes:xlsx, XLSX|max:20000',
            'questions_images' => 'mimes:zip, ZIP|max:20000',
            'per_part' => 'required|max:2',
        ]);

        $extra_rules = [
            'xlsx' => 'mimes:xlsx, XLSX|max:20000',
            'questions_images' => 'mimes:zip, ZIP|max:20000',
            'per_part' => 'required|max:2',
        ];

        /*
            create New Quiz
        */
        $new_quiz = new Quize;

        $new_quiz->quiz_name = $request->quiz_name;
        $new_quiz->quiz_order = ($request->quiz_order) ? $request->quiz_order : 777;

        $new_quiz->sef_url = JsonController::setSEFurl($request->quiz_name);

        $new_quiz->category = $request->category;
        $new_quiz->meta_keywords = $request->meta_keywords;
        

        if($request->featured == 1){
            $new_quiz->featured = 1;
        }
        if($request->active == 1){
            $new_quiz->active = 1;
        }

        $new_quiz->quiz_price = $request->quiz_price;

        $new_quiz->short_description = $request->short_description;

        $new_quiz->quiz_description = $request->quiz_description;

        $new_quiz->per_part = $request->per_part;

        $new_quiz->save();

        $quiz_id = $new_quiz->id;
        /*
            Add Cats
        */
        $quizes_to_link = CategorieController::getCatsQuizes($request);
        $result = CategorieController::linkCatsToQuizes($quiz_id, $quizes_to_link);
        /*
            End Add Cats
        */
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
            Questions Images ZIP FILE Upload
        */
        if ($request->questions_images != null) {

            $file = $request->file('questions_images');

            $questions_images = 'q_' . $quiz_id . '.' . $file->getClientOriginalExtension();
            $path = $request->questions_images->move(public_path() . '/questions_images', $questions_images);

            self::unzipQz($quiz_id, $questions_images);
        }

        /*
            MAIN XLSX UPLOAD
        */

        $file = $request->file('xlsx');

        $xlsx_name = 'x_' . $quiz_id . '.' . $file->getClientOriginalExtension();
        $path = $request->xlsx->move(public_path() . '/xlsx_files', $xlsx_name);

        /*
            PROCESS QUIZ put to DB
        */

        $result = XlsxController::proccessQuiz($quiz_id, $xlsx_name);

        /*

        */

        if ($result == true) {
            return redirect('/adminhome')->with('success', 'Quiz successfully uploaded');
        } else {
            return view('admin.uploadquiz')->with('error', 'Wrong XLSX format. Please check sructure');;
        }

        return view('admin.uploadquiz');

        //return redirect('/adminhome')->with('success', 'You are successfuly logged in as admin!');
    }

    public static function editQuiz($id)
    {

        $quiz = Quize::find($id);

        $_SESSION['quiz_id'] = $quiz->id;

        $cats = CategorieController::getLinkedCats($id);

        // dd($cats);

        return view('admin.editQuiz', ['quiz' => $quiz, 'cats' => $cats]);

    }

    public function doEditQuiz(Request $request)
    {

        // dd($request);

        $request->validate([
            'quiz_name' => 'required|max:255',
            'quiz_order' => 'integer|max:2000',

            'category' => 'max:255',
            'meta_keywords' => 'required|max:255',
            'featured' => 'max:2',
            'active' => 'max:2',
            'quiz_price' => 'required|numeric|max:100000',
            'short_description' => 'required|max:1000',
            'quiz_description' => 'max:10000',
            'cover_image' => 'image|mimes:jpg,png,jpeg,gif,svg|max:200000',
            'xlsx' => 'mimes:xlsx, XLSX|max:20000',
            'questions_images' => 'mimes:zip, ZIP|max:20000',
            'json' => 'mimes:json,JSON|max:20000',
            'per_part' => 'required|max:2',
        ]);

        $extra_rules = [
            'xlsx' => 'mimes:xlsx, XLSX|max:20000',
            'questions_images' => 'mimes:zip, ZIP|max:20000',
            'json' => 'required|mimes:json,JSON|max:20000',
            'per_part' => 'required|max:2',
        ];

        /*
            edit Quiz
        */
        $quiz_id = $request->quiz_id;

        $quiz = Quize::find($quiz_id);

        $quiz->quiz_name = $request->quiz_name;

        $quiz->quiz_order = ($request->quiz_order) ? $request->quiz_order : 777;

        $quiz->sef_url = JsonController::setSEFurl($request->quiz_name);

        $quiz->category = $request->category;
        $quiz->meta_keywords = $request->meta_keywords;
        
        $quiz->featured = ($request->featured == 1) ? 1 : 0;
        $quiz->active = ($request->active == 1) ? 1 : 0;
        
        $quiz->quiz_price = $request->quiz_price;
        $quiz->short_description = $request->short_description;
        $quiz->quiz_description = $request->quiz_description;
        $quiz->per_part = $request->per_part;

        $quiz->save();
        /*
            Add Cats
        */
        $quizes_to_link = CategorieController::getCatsQuizes($request);
        $result = CategorieController::linkCatsToQuizes($quiz_id, $quizes_to_link);
        /*
            End Add Cats
        */
        /*
            Delete and Cover Image Upload
        */
        if ($request->cover_image != null) {

            // dd($quiz->cover_image);

            File::delete(public_path()."/cover_images/".$quiz->cover_image);

            $file = $request->file('cover_image');

            $cover_image = 'c_' . $quiz_id . '.' . $file->getClientOriginalExtension();

            $path = $request->cover_image->move(public_path() . '/cover_images', $cover_image);

            $quiz->cover_image = $cover_image;

            $quiz->save();

        }
        /*
            MAIN UPLOAD
        */
        $result = true;

        /* Delete QA From DB */

        if ($request->xlsx != null || $request->json != null) {

            $qn_ids = Question::select('id')
                ->where('qz_id', $quiz_id)
                ->get();

            foreach ($qn_ids as $qn_id) {

                $qn_id = $qn_id->id;

                DB::delete('DELETE from answers WHERE qn_id = ?', [$qn_id]);

                Question::find($qn_id)->delete();

            }
        }
        /*
            XLSX
        */
        if ($request->xlsx != null) {

            /* Add file xlsx */

            $file = $request->file('xlsx');

            $xlsx_name = 'x_' . $quiz_id . '.' . $file->getClientOriginalExtension();
            $path = $request->xlsx->move(public_path() . '/xlsx_files', $xlsx_name);

            /*
                PROCESS QUIZ put to DB
            */

            $result = XlsxController::proccessQuiz($quiz_id, $xlsx_name);

            /*
                Questions Images ZIP FILE Upload
            */
            if ($request->questions_images != null) {

                File::deleteDirectory(public_path()."/question_images/q_".$quiz_id);

                $file = $request->file('questions_images');

                $questions_images = 'q_' . $quiz_id . '.' . $file->getClientOriginalExtension();
                $path = $request->questions_images->move(public_path() . '/questions_images', $questions_images);

                self::unzipQz($quiz_id, $questions_images);
            }
        }
        /*
            JSON
        */
        if ($request->json != null) {

            $result = JsonController::uploadJsonQA($request, $quiz_id);

        }
        /*
            
        */

        if ($result == true) {
            return redirect('/admin/quizzes')->with('success', 'Quiz successfully edited');
        } else {
            return view('admin.editQuiz')->with('error', 'Wrong XLSX format. Please check structure');
        }

        return view('admin.editQuiz');

    }

    public static function unzipQz($quiz_id, $zip_file) //q_24.zip
    {

        $zip = new ZipArchive();

        $parts = explode(".", $zip_file);

        $res = $zip->open(public_path() . '/questions_images/' . $zip_file);

        if ($res === TRUE) {

            if(!is_dir(public_path() . '/questions_images/' . $parts[0])){
                mkdir(public_path() . '/questions_images/' . $parts[0], 0700);
            }

            //mkdir(public_path() . '/questions_images/' . $parts[0], 0700);

            $zip->extractTo(public_path() . '/questions_images/' . $parts[0] . '/');

            $zip->close();

            File::delete(public_path() . '/questions_images/' . $zip_file);

        } 
        else {
            echo 'ZIP file was not extracted';
        }
    }

    // Man age users

    public static function addSession()
    {

        $quizes = Quize::all();

        return view('admin.addsession')->with('quizes', $quizes);
    }

    public static function logout(){

        session_destroy();

        return redirect('/adminhome');

    }

    /*
        Static Pages
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

    */

    public static function pages()
    {
        $pages = Home::get();

        return view('admin.pages')->with('pages', $pages);

    }

    public static function editPage($id)
    {
        // dd($id);

        $page = Home::find($id);

        return view('admin.editPage')->with('page', $page);

    }

    public static function doEditPage(Request $request)
    {
        // dd($request);

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

        return view('admin.quizzes')->with('quizzes', $quizzes);
        
    }

    public static function bundles()
    {

        $bundles = Quize::where('is_bundle', 1)->get();

        return view('admin.bundles')->with('bundles', $bundles);
        
    }



}
