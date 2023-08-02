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
        $new_quiz->sef_url = self::setSEFurl($request->quiz_name);
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

    public static function filterQuizzes(Request $request){

        // Simulate fetching categories based on the keyword
        $keyword = $_GET['keyword'] ?? '';

        $quizes = Quize::where('quiz_name', 'like', '%'.$request->keyword.'%')->get();

        $categories = [];

        foreach($quizes as $quiz){
            $categories[] = ["ID" => $quiz->id, "Category_name" => $quiz->quiz_name];
        }

        // $categories = [["ID" => 1, "Category_name" => "Cat1"],];

        header('Content-Type: application/json');

        echo json_encode($categories);

    }

    public function uploadBundle(){
        return view('admin.uploadBundle');
    }

    //editBundle
    public function editBundle($id){

        $bundle = Quize::find($id);

        $quizes = self::getLinkedQuizes($id);

        return view('admin.uploadBundle', ['bundle' => $bundle, 'quizes' => $quizes]);
    }

    public static function getLinkedQuizes($b_id){
        
        $linked = DB::select('select * from bundle_quize where bl_id = :bl_id', ['bl_id' => $b_id]);

        $quizes =[];

        foreach($linked as $cur){
      
            $temp = Quize::where('id', $cur->qz_id)->first();
            $quizes[] = $temp;

        }

        return $quizes;
    }

    public static function doEditBundle(Request $request){

        $bl_id = $request->bl_id;

        $quizes_to_link = self::getBubleQuizes($request);

        $bl_id = self::quizToDB($request, $bl_id);

        $result = self::linkBundleToQuizes($bl_id, $quizes_to_link);

        return redirect("/admin/bundles")->with('success', 'Your bundle was successfully edited!');
    }

    public static function doUploadBundle(Request $request){

        //Category_name-4
        $quizes_to_link = self::getBubleQuizes($request);

        $bl_id = self::quizToDB($request);

        $result = self::linkBundleToQuizes($bl_id, $quizes_to_link);

        return redirect("/admin/bundles")->with('success', 'Your bundle was successfully added!');
    }

    public static function getBubleQuizes($request){

        $formData = $request->all();
        $quizes_to_link = [];

        foreach($formData as $name => $value){

            if(strpos($name, "ategory_name")){

                $parts = explode("-", $name);
                $quizes_to_link[] = $parts[1];
            }

        }
        return $quizes_to_link;
    }

    public static function linkBundleToQuizes($bl_id, $quizes_to_link){

        $result = DB::delete('delete from bundle_quize where bl_id = :bl_id', ['bl_id' => $bl_id]);

        foreach($quizes_to_link as $id => $value){
            $result = DB::insert('insert into bundle_quize (bl_id, qz_id) values (?, ?)', [$bl_id, $value]);
        }
        return true;
    }

    //Base quiz

    public static function quizToDB($request, $qz_id = 0){

        $request->validate([
            'quiz_name' => 'required|max:255',
            'category' => 'required|max:255',
            'meta_keywords' => 'required|max:255',
            'featured' => 'max:2',
            'active' => 'max:2',
            'quiz_price' => 'required|max:255',
            'short_description' => 'required|max:1000',
            'quiz_description' => 'max:100000',
            'cover_image' => 'image|mimes:jpg,png,jpeg,gif,svg|max:200000',
        ]);



        if($qz_id != 0){
            $new_quiz = Quize::find($qz_id);
        }
        else{
            $new_quiz = new Quize;
        }

        $new_quiz->quiz_name = $request->quiz_name;
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

    public static function deleteBundle($id){

        $result = DB::delete('delete from bundle_quize where bl_id = :bl_id', ['bl_id' => $id]);
        $bundle = Quize::find($id);

        $bundle->delete();

        return redirect("/admin/bundles")->with('success', 'Bundle was delete!');
    }

    public static function setSEFurl($quiz_name = "qwerty"){

        // $quiz_name = "Electician's _ quiz:@asd@";

        $quiz_sef_url = "";

        for($i = 0; $i < strlen($quiz_name); $i++){

            if(ctype_alnum($quiz_name[$i]) == true){
                $quiz_sef_url.= $quiz_name[$i];
            }
            else{
                $quiz_sef_url .= "-";
            }

        }
        $quiz_sef_url = str_replace("---", "-", $quiz_sef_url);
        $quiz_sef_url = trim(str_replace("--", "-", $quiz_sef_url));
        $quiz_sef_url = trim($quiz_sef_url, "-");
        $quiz_sef_url = strtolower($quiz_sef_url);

        // dd($quiz_sef_url);
        return $quiz_sef_url;

    }

}
