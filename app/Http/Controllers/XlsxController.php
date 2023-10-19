<?php

namespace App\Http\Controllers;

use App\Admin;
use App\Xlsx;
use App\Quize;
use App\Question;
use App\Answer;
use Illuminate\Http\Request;

use File;

use ZipArchive;

use Illuminate\Database\Eloquent\Model;

class XlsxController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public static function proccessQuiz($quiz_id, $xlsx_name)
    {

        $basic = new Xlsx;

        if ($xlsx = $basic::parse(public_path() . '/xlsx_files/' . $xlsx_name)) {

            $xl_arr = $xlsx->rows();

            if($xl_arr[0][0] != "question") {
                return false;
            }

            array_shift($xl_arr);

            //dd($xl_arr);

            $result = self::writeQAs($quiz_id, $xl_arr);

        } else {
           dd($basic::parseError());
        }

        File::delete(public_path().'/xlsx_files/'.$xlsx_name);

        return $result;
    }

    public static function writeQAs($quiz_id, $xl_arr)
    {

        $qn_id = NULL;

        $flag_a = 1;

        $clarif_flag = 0;
        
        foreach ($xl_arr as $cur_line) {


            if ($cur_line[0] != NULL) {

                // dd($cur_line[4]);

                if($flag_a == 0){
                    return false;
                }

                $flag_a = 0;

                $cur_question = new Question;

                $cur_question->qz_id = $quiz_id;
                $cur_question->q_name = $cur_line[0];
                $cur_question->q_image = $cur_line[3];
                

                if (isset($cur_line[4]) && $cur_line[4] != NULL) {
                    $cur_question->clarification = $cur_line[4];
                    $clarif_flag = 1;
                }

                $cur_question->save();

                $qn_id = $cur_question->id;

            }
            elseif ($cur_line[0] == NULL && $cur_line[1] != NULL) {

                $cur_answer = new Answer;

                $cur_answer->qn_id = $qn_id;
                $cur_answer->a_name = $cur_line[1];
                $cur_answer->a_correct = $cur_line[2];

                if($cur_answer->a_correct == "1"){
                    $flag_a = 1;
                }

                $cur_answer->save();
            
            }
            else{
                return false;
            }
            
        }

        $quize = Quize::find($quiz_id);
        $quize->clarif_flag = $clarif_flag;
        $quize->save(); 

        return true;
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

            return true;

        } 
        else {
            echo 'ZIP file was not extracted';
        }
    }

    public static function editXLSX($request, $quiz_id){
        
        /* Add file xlsx */

        $file = $request->file('xlsx');

        $xlsx_name = 'x_' . $quiz_id . '.' . $file->getClientOriginalExtension();
        $path = $request->xlsx->move(public_path() . '/xlsx_files', $xlsx_name);

        /*
            PROCESS QUIZ put to DB
        */

        $result = self::proccessQuiz($quiz_id, $xlsx_name);

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

        return true;

    }

}
