<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Admin;
use App\User;

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

class ManageUserControler extends Controller
{
    public function __construct()
    {
        session_start();

        if (empty($_SESSION['admin']) || $_SESSION['admin'] != 1) {

            session_destroy();

            header('Location: /adminlogin/');
            die();
        }
    }

    // Man age users

    public static function createUser()
    {

        return view('admin.create_user');

    }

    public static function doCreateUser(Request $request)
    {
        dd($request);

        $request->validate([
            'name' => 'required|max:255',
            'email' => 'max:255',
            'password' => 'required|max:255',
        ]);

        $user = new User();

        $user->name = $request->username;
        $user->password = $request->password;
        $user->email = $request->email;
        $user->save();

        return redirect('/adminhome')->with('success', 'Quiz Session Created');
    }

    public function deleteSession(Request $request)
    {
        User::find($request->id)->delete();

        return redirect('/adminhome')->with('success', 'Session Deleted!');
    }
}
