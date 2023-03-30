<?php

use Illuminate\Http\Request;


Route::group(['prefix' => 'bda'], function () {
	// Course
	Route::resource('course', 'Bda\CourseController');
		// ContentIndex
		Route::put('course/{course}/contentindex', 'Bda\CourseController@content_index');
		// Collaborator
		Route::put('course/{course}/collaborator', 'Bda\CourseController@collaborator_sync');
		// Status
		Route::put('course/{course}/status', 'Bda\CourseController@status_update');

	// Resource
	Route::resource('resource', 'Bda\ResourceController');

	// Module
	Route::resource('module', 'Bda\ModuleController');

	// Collaborator
	Route::get('collaborator/{collaborator}/course', 'Bda\CollaboratorController@course_index');
	Route::get('collaborator/{collaborator}/course/{course}', 'Bda\CollaboratorController@course_show');
});