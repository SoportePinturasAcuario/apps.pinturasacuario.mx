<?php

use Illuminate\Http\Request;

Route::group(['prefix' => 'checker'], function () {
	// Route::resource('checkin', 'Checker\CheckinController');

	// // Biometric
	// Route::resource('biometric', 'Checker\BiometricController');
	// // Massive
	// Route::post('biometric/insert', 'Checker\BiometricController@insert');

	// Register
	Route::resource('register', 'Checker\RegisterController');

	// Collaborator
		// Descriptor
		Route::get('collaborator/descriptor', 'Checker\CollaboratorController@descriptor_index');

	// Descriptor
	Route::resource('descriptor', 'Checker\DescriptorController');
		// Massive
		Route::post('descriptor/insert', 'Checker\DescriptorController@insert');
});