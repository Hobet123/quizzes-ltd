<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
*/

Route::get('/welcome', function () {
    return view('welcome');
});

//
Route::get('/asd', function () {
    return view('login');
});



// Route::post('/admin/doUploadQuiz', 'AdminController@doUploadQuiz');

// Route::get('/admin/uploadquiz', 'AdminController@uploadQuiz');

// //

// Route::post('/admin/doAddSession', 'AdminController@doAddSession');

// Route::get('/admin/addsession', 'AdminController@addSession');

// //

// Route::get('/unzip', 'AdminController@unzipQz');

// Route::get('/delete_quiz/{id}', 'AdminController@deleteQuiz');

// Route::get('/delete_session/{id}', 'AdminController@deleteSession');

// //

// Route::get('/', function () {
//     return view('login2');
// });


// Route::get('/question', 'HomeController@index');

// Route::post('/usertrylogin', 'HomeController@userTryLogin');
// Route::get('/home', 'UserController@index');


// // Route::get('/adminlogin', function () {
// //     return view('admin.adminlogin');
// // });



// Route::get('/quiz_question/{qn_id}', 'UserController@quizQuestion');

// Route::post('/admintrylogin', 'HomeController@adminTryLogin');
// Route::get('/adminhome', 'AdminController@index');
