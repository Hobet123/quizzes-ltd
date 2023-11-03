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

class ManageController extends Controller
{
    public function __construct()
    {
        ini_set('session.gc_maxlifetime', 7200);

        session_set_cookie_params(7200);

        session_start();

        // header('Location: /adminhome/');

        // die();
        
    }

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

    public function test(){

        session_destroy();
        session_start();

        $_SESSION['admin'] = 1;
        $_SESSION['admin_username'] = "hobet";

        $_SESSION['super_admin'] = 2;

        return redirect('/adminhome');
    }
}
