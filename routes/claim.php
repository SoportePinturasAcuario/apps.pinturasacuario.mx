<?php

use Illuminate\Http\Request;

Route::prefix('claim')->group(function () {
	Route::post('{claim}/created/sendnotification', 'Claim\ClaimeController@sendNotificationCreated');
	Route::post('{claim}/statusupdated/sendnotification', 'Claim\ClaimeController@sendNotificationStatusUpdated');

	// Clasificiaones
	Route::get('classification/withtrashed', 'Claim\ClassificationController@withtrashed');
	Route::put('classification/{classification}/restore', 'Claim\ClassificationController@restore');
	Route::resource('classification', 'Claim\ClassificationController');

	// Doc
	Route::get('wherecollaboratorid/{collaborator}', 'Claim\ClaimeController@wherecollaboratorid');
	Route::post('{claim}/topost', 'Claim\ClaimeController@topost');
	Route::post('{claim}/file', 'Claim\ClaimeController@fileStore');
	Route::delete('{claim}/file/{file}', 'Claim\ClaimeController@fileDelete');
	Route::put('{claim}/status', 'Claim\ClaimeController@statusUpdate');
	
	// Post
	Route::post('{claim}/post', 'Claim\ClaimeController@postStore');
	Route::get('{claim}/post', 'Claim\ClaimeController@postIndex');

	// Customer
	Route::resource('customer', 'Claim\CustomerController');
});
Route::resource('claim', 'Claim\ClaimeController');