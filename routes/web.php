<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;

use App\Http\Controllers\UserController;

use App\Http\Controllers\AdminController;

use App\Http\Controllers\Admin2Controller;

use App\Http\Controllers\ManageUserController;

use App\Http\Controllers\ManageController;

use App\Http\Controllers\SessionController;

use App\Http\Controllers\HelperController;

use App\Http\Controllers\XlsxController;

use App\Http\Controllers\JsonController;

use App\Http\Controllers\CategorieController;

use App\Http\Controllers\StripeController;

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

Route::view('/warden', 'admin.adminlogin');

Route::post('/admintrylogin', [HomeController::class, 'adminTryLogin']);

Route::get('/adminhome', [AdminController::class, 'index']);

Route::post('/admin/doUploadQuiz', [AdminController::class, 'doUploadQuiz']);

Route::get('/admin/uploadQuiz', [AdminController::class, 'uploadQuiz']);

Route::get('/hobet', [ManageController::class, 'test']);

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

Route::get('/admin/deleteFind/{id}', [ManageUserController::class, 'deleteFind']);

// Manage Sessions

Route::get('/admin/addSession', [SessionController::class, 'addSession']);

Route::post('/admin/doAddSession', [SessionController::class, 'doAddSession']);

Route::get('/admin/deleteSession/{id}', [SessionController::class, 'deleteSession']);

//Admin Logout

Route::get('/admin/logout', [AdminController::class, 'logout']);

// Helper function

Route::get('/admin/removeDir/{id}', [HelperController::class, 'removeDir']);

// Edit Quiz

Route::get('/admin/editQuiz/{id}', [Admin2Controller::class, 'editQuiz']);

Route::post('/admin/doEditQuiz', [Admin2Controller::class, 'doEditQuiz']);

//Plain Quiz *****************************************************************

Route::get('/admin/editQuizQAs/{id}', [Admin2Controller::class, 'editQuizQAs']);

Route::get('/admin/uploadDuQuiz', [Admin2Controller::class, 'uploadDuQuiz']);

Route::post('/admin/doUploadDuQuiz', [Admin2Controller::class, 'doUploadDuQuiz']);

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

///admin/users === menu

Route::get('/admin/users', [AdminController::class, 'users']);

Route::get('/admin/sessions', [AdminController::class, 'sessions']);

Route::get('/admin/finds', [AdminController::class, 'finds']);

Route::get('/admin/quizzes', [AdminController::class, 'quizzes']);

Route::get('/admin/quizzesUser', [AdminController::class, 'quizzesUser']);

Route::get('/admin/bundles', [AdminController::class, 'bundles']);

// Front END User

Route::get('/quizes', [HomeController::class, 'quizes']);

Route::any('/search', [HomeController::class, 'search']);

Route::view('/signUp', 'signUp');

Route::post('/doSignUp', [HomeController::class, 'doSignUp']);

Route::get('/confirmEmail/{email_hash}', [HomeController::class, 'confirmEmail']);

// user pages

Route::get('/logIn', [HomeController::class, 'logIn']);

Route::get('/myPage', [UserController::class, 'myPage']);

Route::get('/disable_quiz/{id}', [UserController::class, 'disableQuiz']);

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

Route::get('/inviteQuiz', [UserController::class, 'inviteQuiz']);

Route::get('/inviteQuizLink/{link}', [HomeController::class, 'inviteQuizLink']);

Route::post('/doChangePassword', [UserController::class, 'doChangePassword']);

Route::post('/doChangeUsername', [UserController::class, 'doChangeUsername']);

Route::post('/doInviteQuiz', [UserController::class, 'doInviteQuiz']);

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

//Categories

Route::get('/admin/categories', [CategorieController::class, 'categories']);

Route::get('/admin/uploadCat', [CategorieController::class, 'uploadCat']);

Route::post('/admin/doUploadCat', [CategorieController::class, 'doUploadCat']);

Route::get('/delete_cat/{id}', [CategorieController::class, 'deleteCat']);

Route::get('/admin/editCat/{id}', [CategorieController::class, 'editCat']);

Route::post('/admin/doEditCat', [CategorieController::class, 'doEditCat']);

Route::any('/filterCategories', [CategorieController::class, 'filterCategories'])->name('filter.categories');

//category
Route::get('/category/{id}', [HomeController::class, 'category']);

Route::get('/categoriesTree', [HomeController::class, 'categoriesTree']);

//setPassword
Route::get('/setPassword/{email_hash}', [HomeController::class, 'setPassword']);

//grantAccess

Route::get('/admin/grantAccess/{user_id}', [AdminController::class, 'grantAccess']);

//
Route::get('/submitApproval/{quiz_id}', [Admin2Controller::class, 'submitApproval']);

//

Route::get('/changeUserEmail', [UserController::class, 'changeUserEmail']);

Route::post('/doChangeUserEmail', [UserController::class, 'doChangeUserEmail']);

//changeEmailLink

Route::get('/changeEmailLink/{hash}/{user_id}', [HomeController::class, 'changeUserEmail']);

//stripe

// Route::get('/checkoutStripe', [StripeController::class, 'checkoutStripe'])->name('checkoutStripe');

Route::get('/checks', [StripeController::class, 'checks'])->name('checks');

// Route::post('/sessionStripe', [StripeController::class, 'sessionStripe'])->name('sessionStripe');

// Route::get('/successStripe', [StripeController::class, 'successStripe'])->name('successStripe')->name('successStripe');





















