<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Admin;
use App\User;
use App\Session;

use App\Xlsx;
use App\Quize;
use App\Question;
use App\Answer;

use ZipArchive;

use App\Http\Controllers\XlsxController;

// use Spatie\Geocoder\Facades\Geocoder;
use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Crypt;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Hash;

use App\BlueMail;

use Mail;

use App\Mail\FeedbackMail;
use App\Mail\GeneralMail;

class ManageUserController extends Controller
{
    public function __construct()
    {
        session_start();

        if (empty($_SESSION['admin']) || $_SESSION['admin'] != 1) {

            session_destroy();

            header('Location: /admin/');
            die();
        }
    }

    // Man age users

    public static function createUser()
    {

        return view('admin.createUser');

    }

    public function editUser(Request $request)
    {
        $user = User::find($request->id);

        return view('admin.editUser')->with('user', $user);
    }


    public static function doCreateUser(Request $request)
    {
        //dd($request);

        $request->validate([
            'username' => 'required|max:255',
            'email' => ['required', 'unique:users', 'max:255'],
            'password' => 'required|max:255',
        ]);

        $user = new User();

        $user->username = $request->username;
        $user->password = $request->password;
        $user->email = $request->email;
        $user->is_admin = $request->is_admin;
        $user->save();

        return redirect('/adminhome')->with('success', 'User Created');
    }

    public static function doEditUser(Request $request)
    {
        // dd($request->user_id);

        $request->validate([
            'username' => 'required|max:255',
            'phone' => 'max:255',
            'email' => 'max:255',
            'password' => 'required|max:255',
        ]);

        $user = User::find($request->user_id);

        $user->username = $request->username;
        $user->phone = $request->phone;
        $user->password = $request->password;
        $user->email = $request->email;
        $user->is_admin = $request->is_admin;
        $user->save();

        return redirect('/adminhome')->with('success', 'User Edited!');
    }


    public function deleteUser(Request $request)
    {
        User::find($request->id)->delete();

        Session::where('user_id', $request->id)->delete();

        return redirect('/adminhome')->with('success', 'User Deleted!');
    }
    /*
        Send user email
    */
    public static function sendUserEmail($user_id)
    {

        $user = User::where('id', $user_id)->first();

        // dd($user);

        if($user == null){
            return redirect('/adminhome/')->with('error', "Wrond user. Please try again. Email admin.");
        }

        $responce = BlueMail::sendUserEmail($user->email, $user->password);

        return redirect('/adminhome')->with('success', 'Email has been sent to user!');

    }

    /*
        old version
    */

    // public static function sendUserEmail_back($user_id)
    // {

    //     $user = User::find($user_id);

    //     $to      = $user->email;
    //     $subject = 'Your access to Quizes';

    //     $message = 'Visit '.env('WEBSITE_NAME').' to access your quizzes: '; 
    //     $username = $user->username;
    //     $password = $user->password;

    //     $feedback = ['message' => $message, 
    //                  'subject' => env('WEBSITE_NAME').' - Your access to Quizes',
    //                  'username' => $username,
    //                  'password' => $password,
    //                  'email_template' => 'access_to_quizzes'
    //                 ];

    //     try {
    //         Mail::to($user->email)->send(new GeneralMail($feedback));
    //     } catch (\Exception $e) { 
    //         dd($e->getMessage());
    //     }

    //     return redirect('/admin/editUser/'.$user->id)->with('success', 'Email has been sent to user!');

    // }
}
