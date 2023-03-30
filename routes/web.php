<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// Auth::routes();

// Route::get("/", function(){
// 	return view("welcome");
//     // Storage::disk('gdrive')->put('test.txt', 'Hello World');
// });


Route::resource('test/file', "Test\FileController", [
	'names' => [
		'store' => 'test.file.store',
		'create' => 'test.file.create',
	],
]);