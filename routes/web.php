<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;

use App\Http\Controllers\UserController;

use App\Http\Controllers\AdminController;

use App\Http\Controllers\Admin2Controller;

use App\Http\Controllers\ManageUserController;

use App\Http\Controllers\SessionController;

use App\Http\Controllers\HelperController;

use App\Http\Controllers\XlsxController;

use App\Http\Controllers\JsonController;

/*

| Web Routes

*/

// User's section

Route::get('/', [HomeController::class, 'index']);

///error
Route::get('/error', [HomeController::class, 'index']);


Route::get('/quizHome/{id}', [UserController::class, 'quizHome']);

Route::any('/quizQuestion/{id}', [UserController::class, 'quizQuestion']);

Route::any('/quizAnswer', [UserController::class, 'quizAnswer']);

Route::any('/quizFinal/{id}', [UserController::class, 'quizFinal']);

Route::get('/logout', [UserController::class, 'logout']);

//Admin Login

Route::view('/admin', 'admin.adminlogin');

Route::post('/admintrylogin', [HomeController::class, 'adminTryLogin']);

Route::get('/adminhome', [AdminController::class, 'index']);

Route::post('/admin/doUploadQuiz', [AdminController::class, 'doUploadQuiz']);

Route::get('/admin/uploadquiz', [AdminController::class, 'uploadQuiz']);

//

Route::get('/unzip', [AdminController::class, 'unzipQz']);

Route::get('/delete_quiz/{id}', [AdminController::class, 'deleteQuiz']);

Route::post('/usertrylogin', [HomeController::class, 'userTryLogin']);

Route::get('/quiz_question/{qn_id}', [UserController::class, 'quizQuestion']);

//start_quiz_part

Route::get('/start_quiz_part/{quiz_part}', [UserController::class, 'startQuizPart']);

Route::post('/answer/{qn_id}', [UserController::class, 'Answer']);

//

Route::post('/admintrylogin', [HomeController::class, 'adminTryLogin']);

Route::get('/adminhome', [AdminController::class, 'index']);

// Manage users of quezes route

Route::get('/admin/createUser', [ManageUserController::class, 'createUser']);

Route::get('/admin/editUser/{id}', [ManageUserController::class, 'editUser']);

Route::get('/admin/sendUserEmail/{user_id}', [ManageUserController::class, 'sendUserEmail']);

Route::post('/admin/doCreateUser', [ManageUserController::class, 'doCreateUser']);

Route::post('/admin/doEditUser', [ManageUserController::class, 'doEditUser']);

Route::get('/admin/deleteUser/{id}', [ManageUserController::class, 'deleteUser']);

// Manage Sessions

Route::get('/admin/addSession', [SessionController::class, 'addSession']);

Route::post('/admin/doAddSession', [SessionController::class, 'doAddSession']);

Route::get('/admin/deleteSession/{id}', [SessionController::class, 'deleteSession']);

//Admin Logout

Route::get('/admin/logout', [AdminController::class, 'logout']);

// Helper function

Route::get('/admin/removeDir/{id}', [HelperController::class, 'removeDir']);

// Edit Quiz

Route::get('/admin/editQuiz/{id}', [AdminController::class, 'editQuiz']);

Route::post('/admin/doEditQuiz', [AdminController::class, 'doEditQuiz']);

//Plain Quiz *****************************************************************

Route::get('/admin/editQuizQAs/{id}', [Admin2Controller::class, 'editQuizQAs']);

Route::get('/admin/uploadDuQuiz', [Admin2Controller::class, 'uploadDuQuiz']);

Route::post('/admin/startDuQuiz', [Admin2Controller::class, 'startDuQuiz']);

Route::get('/admin/addDuQA', [Admin2Controller::class, 'addDuQA']);

Route::post('/admin/doAddDuQA', [Admin2Controller::class, 'doAddDuQA']);

Route::get('/admin/deleteQuestion/{quiz_id}/{qn_id}', [Admin2Controller::class, 'deleteQuestion']);

//Question/s Edit

Route::post('/admin/questionsOrder', [Admin2Controller::class, 'questionsOrder']);

Route::get('/admin/addDuQATo/{quiz_id}', [Admin2Controller::class, 'addDuQATo']);

//look at doAddDuQA

Route::get('/admin/editDuQA/{question_id}', [Admin2Controller::class, 'editDuQA']);

Route::post('/admin/doEditDuQA', [Admin2Controller::class, 'doEditDuQA']);

// admin menu links

///admin/users

Route::get('/admin/users', [Admin2Controller::class, 'users']);

Route::get('/admin/sessions', [Admin2Controller::class, 'sessions']);

Route::get('/admin/quizzes', [Admin2Controller::class, 'quizzes']);

Route::get('/admin/bundles', [Admin2Controller::class, 'bundles']);

// Front END User

Route::get('/quizes', [HomeController::class, 'quizes']);

Route::any('/search', [HomeController::class, 'search']);

Route::view('/signUp', 'signUp');

Route::post('/trySignUp', [HomeController::class, 'trySignUp']);

Route::get('/confirmEmail/{email_hash}', [HomeController::class, 'confirmEmail']);

// user pages

Route::view('/logIn', 'logIn');

Route::get('/myPage', [UserController::class, 'myPage']);

Route::get('/quizDetails/{sef_url}', [HomeController::class, 'quizDetails']);

Route::get('/tryQuiz/{id}', [HomeController::class, 'tryQuiz']);

//tryQuiz

Route::get('/cart', [HomeController::class, 'cart']);

//Paypal
//checkout
Route::get('/checkout', [UserController::class, 'checkout']);
//

Route::any('/pp_completed', [HomeController::class, 'ppCompleted']);

Route::any('/pp_canceled', [HomeController::class, 'ppCanceled']);

Route::any('/pp_notify', [HomeController::class, 'ppNotify']);

//addSessions

Route::any('/addSessions', [UserController::class, 'addSessions']);

//resetPassword
Route::get('/forgotPassword', [HomeController::class, 'forgotPassword']);

Route::post('/resetPassword', [HomeController::class, 'resetPassword']);

//resetPasswordLink

Route::get('/resetPasswordLink/{email_hash2}', [HomeController::class, 'resetPasswordLink']);

Route::post('/doResetPassword', [HomeController::class, 'doResetPassword']);

//change Password, Username

Route::get('/changePassword', [UserController::class,'changePassword']);

Route::get('/changeUsername', [UserController::class, 'changeUsername']);

Route::post('/doChangePassword', [UserController::class, 'doChangePassword']);

Route::post('/doChangeUsername', [UserController::class, 'doChangeUsername']);

Route::get('/contactUs', [HomeController::class, 'contactUs']);

Route::post('/doContactUs', [HomeController::class, 'doContactUs']);

//privacy
//terms

Route::get('/privacy', [HomeController::class, 'privacy']);
Route::get('/terms', [HomeController::class, 'terms']);

/*
    Static Pages
*/


Route::get('/admin/homeSetting', [AdminController::class, 'homeSetting']);
Route::post('/admin/doHomeSetting', [AdminController::class, 'doHomeSetting']);
//
//

Route::get('/admin/pages', [AdminController::class, 'pages']);

Route::get('/admin/editPage/{id}', [AdminController::class, 'editPage']);

Route::post('/admin/doEditPage', [AdminController::class, 'doEditPage']);

//admin/createPage
Route::get('/admin/createPage', [AdminController::class, 'createPage']);

Route::post('/admin/doCreatePage', [AdminController::class, 'doCreatePage']);

// view pages

Route::get('/pageStatic/{cur}', [HomeController::class, 'pageStatic']);

// JSON

Route::get('/admin/uploadJson', [JsonController::class, 'uploadJson']);

Route::post('/admin/doUploadJson', [JsonController::class, 'doUploadJson']);

//BUNDLE

Route::get('/admin/uploadBundle', [JsonController::class, 'uploadBundle']);

Route::any('/filterQuizzes', [JsonController::class, 'filterQuizzes'])->name('filter.quizzes');

Route::post('/admin/doUploadBundle', [JsonController::class, 'doUploadBundle']);

Route::get('/admin/editBundle/{id}', [JsonController::class, 'editBundle']);

Route::post('/admin/doEditBundle', [JsonController::class, 'doEditBundle']);

Route::get('/аdmin/deleteBundle/{id}', [JsonController::class, 'deleteBundle']);

//SE Friendly
Route::get('setSEFurl', [JsonController::class, 'setSEFurl']);


















