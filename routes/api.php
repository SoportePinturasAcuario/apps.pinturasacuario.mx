<?php

use Illuminate\Http\Request;

// Others
Route::get('others/times', 'Others\StartAtController@index');

Route::post("password/email", "Auth\ForgotPasswordController@sendResetLinkEmail");
Route::post("password/reset", "Auth\ResetPasswordController@reset")->name("password.update");

// Login
Route::post('auth/login', 'AuthController@login');
// Register
Route::post('user', 'UserController@store');

// Auth
Route::group(['middleware' => ['jwt.auth'], 'prefix' => 'auth'], function () {
    // Logout
    Route::post('logout', 'AuthController@logout');

    // User
    Route::prefix('user')->group(function () {
        // Roles
        Route::get('{user}/rol', 'UserController@rolIndex');
        Route::post('{user}/rol/store', 'UserController@rolStore');
        Route::delete('{user}/rol/{rol}', 'UserController@rolDelete');
        // Apps
        Route::get('{user}/app', 'UserController@appIndex');
        Route::post('{user}/app/store', 'UserController@appStore');
        Route::delete('{user}/app/{app}', 'UserController@appDelete');

        Route::get('', 'UserController@data');
    });
    Route::resource('users', 'UserController');
    // Restore
    Route::put('users/{user}/restore', 'UserController@restore');
    // Roles
    Route::resource('rol', 'RolController');
    // Apps
    Route::resource('app', 'AppController');
});

Route::group(['middleware' => ['jwt.auth']], function () {
    // Collaborator
    // Restore
    Route::put('collaborator/{collaborator}/restore', 'CollaboratorController@restore');
    // Show(Everything Data)
    Route::get('collaborator/{collaborator}/witheverything', 'CollaboratorController@show_with_everything');
    Route::resource('collaborator', 'CollaboratorController');
});

// User
// Avatar Update
Route::post('user/{user}/avatar/update', 'UserController@avatarUpdate');

// RH
// Route::prefix('rh')->group(function () {
//     // Collaborator
//     Route::resource('collaborator', 'CollaboratorController')->except(['update', 'destroy']);
// });

// SH
Route::prefix('sh')->group(function () {
    // Collaborator
    Route::get('collaborator/{collaborator}/poll', 'CollaboratorController@polls');
    Route::get('collaborator/{collaborator}/poll/{poll}/forms', 'CollaboratorController@poll_forms');

    // Poll
    Route::resource('poll', 'Encuestas\PollController');

    // Collaborator
    Route::get('poll/{poll}/collaborator', 'Encuestas\PollController@collaborator_index');

    // Applies
    Route::get('poll/{poll}/applies', 'Encuestas\PollController@applies');

    // Qualification
    Route::get('poll/{poll}/qualification', 'Encuestas\PollController@qualification');

    // Form
    Route::resource('form', 'Encuestas\FormController');
    Route::get('form/{form}/applies', 'Encuestas\FormController@applies');
    Route::get('form/{form}/applies/all', 'Encuestas\FormController@appliesAll');

    // Apply
    Route::post('apply/{apply}/qualify', 'Encuestas\ApplyController@qualify');
    Route::resource('apply', 'Encuestas\ApplyController');
    Route::get('apply/{apply}/poll', 'Encuestas\ApplyController@poll');
    Route::get('apply/{apply}/form', 'Encuestas\ApplyController@form');
    Route::get('apply/{apply}/questions', 'Encuestas\ApplyController@questions');
    Route::get('apply/{apply}/finalize', 'Encuestas\ApplyController@finalize');
    Route::post('apply/applies', 'Encuestas\ApplyController@applies');
    Route::post('apply/wheredepartmentid', 'Encuestas\ApplyController@wheredepartmentid');

    // Qualification
    Route::resource('qualification', 'Encuestas\QualificationController');

    // Answer
    Route::resource('answer', 'Encuestas\AnswerController');
    Route::get('answer/apply/{apply}', 'Encuestas\AnswerController@answersApply');

    // Collaborator
    // Route::resource('collaborator', 'Encuestas\CollaboratorController')->except(['update', 'destroy']);
});

// Branchoffices
Route::get('branchoffice', 'BranchOfficeController@index');

// Department
Route::get('department', 'DepartmentController@index');

// Customers
Route::resource('customer', 'CustomerController');
Route::post('customer/search', 'CustomerController@search');

// Articles
Route::resource('article', 'Pa\ArticleController');
// Route::post('article/search', 'Pa\ArticleController@search');

// Approval
Route::put('approval/{approval}/updatestatus', 'Pa\ApprovalController@updateStatus');

// Export
Route::post('export', 'ExportController@export');

// CSV To Array
Route::post('csvtoarray', 'ExportController@csvtoarray');

// Archivos
Route::resource('file', 'FileController');
