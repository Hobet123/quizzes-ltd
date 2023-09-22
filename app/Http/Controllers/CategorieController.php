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

use App\Categorie;

use ZipArchive;

use App\Http\Controllers\XlsxController;

use App\Http\Controllers\ManageUserControler;

use Illuminate\Support\Facades\DB;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Hash;

class CategorieController extends Controller
{
    public function __construct()
    {}

    public static function categories(){

        $cats = Categorie::all();

        foreach($cats as $cat){

            if($cat->parent_id != 0){

                $parent_cat = Categorie::where('id', $cat->parent_id)->first();

                if($parent_cat != NULL){

                    $cat->parent_cat_name = $parent_cat->cat_name;

                }
            } 
            // dd($cat);
        }

        // dd($cats);

        return view('admin.categories')->with(['cats' => $cats]);
        
    }

    public function uploadCat(){

        $cats = Categorie::all();

        return view('admin.uploadCat')->with(['cats' => $cats]);
    
    }

    public function doUploadCat(Request $request){

        $request->validate([
            'cat_name' => 'required|min:3|max:255',
        ]);

        $cat = new Categorie;

        $cat->cat_name = $request->cat_name;
        $cat->parent_id = $request->parent_id;

        $cat->save();

        return redirect('/admin/categories')->with(['success' => "The category has been uploaded!"]);

    }

    public function editCat($id){

        $cat = Categorie::find($id);
        $all_cats= Categorie::all();

        return view('admin.editCat')->with(['cat' => $cat, 'all_cats' => $all_cats]);
    
    }

    public function doEditCat(Request $request){

        $request->validate([
            'cat_name' => 'required|min:3|max:255',
        ]);

        $cat = Categorie::find($request->cat_id);

        $cat->cat_name = $request->cat_name;
        $cat->parent_id = $request->parent_id;

        $cat->save();

        return redirect('/admin/categories')->with(['success' => "The category has been edited!"]);
    
    }

    public function deleteCat($id){

        Categorie::find($id)->delete();

        return redirect('/admin/categories')->with(['success' => "The category has been deleted!"]);

    }

    public static function filterCategories(Request $request){

        // Simulate fetching categories based on the keyword
        $keyword = $_GET['keyword'] ?? '';

        $cats = Categorie::where('cat_name', 'like', '%'.$request->keyword.'%')->get();

        $categories = [];

        foreach($cats as $cat){
            $categories[] = ["ID" => $cat->id, "Category_name" => $cat->cat_name];
        }

        // $categories = [["ID" => 1, "Category_name" => "Cat1"],];

        header('Content-Type: application/json');

        echo json_encode($categories);

    }

    public static function getCatsQuizes($request){

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

    public static function linkCatsToQuizes($qz_id, $quizes_to_link){

        $result = DB::delete('delete from categorie_quize where qz_id = :qz_id', ['qz_id' => $qz_id]);

        foreach($quizes_to_link as $id => $value){
            $result = DB::insert('insert into categorie_quize (cat_id, qz_id) values (?, ?)', [$value, $qz_id]);
        }
        return true;
    }

    public static function getLinkedCats($qz_id){
        
        $linked = DB::select('select * from categorie_quize where qz_id = :qz_id', ['qz_id' => $qz_id]);

        $cats =[];

        // dd($linked);

        foreach($linked as $cur){
      
            $temp = Categorie::where('id', $cur->cat_id)->first();

            // dd($temp);

            $cats[] = $temp;

        }

        // dd($cats);

        return $cats;
    }



}
