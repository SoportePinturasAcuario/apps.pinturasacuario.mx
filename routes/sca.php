<?php

use Illuminate\Http\Request;


Route::group(['prefix' => 'sca'], function () {
	// Record/Register
		// Store
		Route::post('record', 'Sca\RegisterController@store');
		// Store(Massive)
		Route::post('record/massive', 'Sca\RegisterController@store_massive');
		// Photo
		Route::post('record/{record}/photo', 'Sca\RegisterController@photo_store');

	// Checker
		// Initialize
		Route::get('checker/{checker}/initialize', 'Sca\CheckerController@initialize');
		// Handshake
		Route::post('checker/{checker}/handshake', 'Sca\CheckerController@handshake');
		// Index
		Route::get('checker', 'Sca\CheckerController@index');

	// Event(index)
		Route::get('event', 'Sca\EventController@index');

	// RegisterType(index)
		Route::get('registertype', 'Sca\RegisterTypeController@index');

	// Auth(JWT)
	Route::group(['middleware' => ['jwt.auth']], function () {
		// Record/Register
			// Query
			Route::get('record/query', 'Sca\RegisterController@query');
			// Report
			Route::get('record/report', 'Sca\RegisterController@report');
		Route::resource('record', 'Sca\RegisterController')->except(['store']);

		// Collaborator
			// Descriptor
			Route::get('collaborator/descriptor', 'Sca\CollaboratorController@descriptor_index');
			// Group
			Route::put('collaborator/{collaborator}/group/sync', 'Sca\CollaboratorController@group_sync');
			// Checkers
			Route::put('collaborator/{collaborator}/checker/sync', 'Sca\CollaboratorController@checker_sync');
			// Turn
			Route::delete('collaborator/{collaborator}/turn/{turn}', 'Sca\CollaboratorController@turn_delete');
			// Index(WithTrashed)
			Route::get('collaborator/withtrashed', 'Sca\CollaboratorController@index_with_trashed');
			// Show
			Route::get('collaborator/{collaborator}', 'Sca\CollaboratorController@show');

		// Descriptor
			// Query
			Route::get('descriptor/query', 'Sca\DescriptorController@query');
			// Massive
			Route::post('descriptor/massive', 'Sca\DescriptorController@massive');
		Route::resource('descriptor', 'Sca\DescriptorController');

		// Group
			Route::resource('group', 'Sca\GroupController');
			// Checker
			Route::put('group/{group}/checker/sync', 'Sca\GroupController@checker_sync');

		// Turn
			Route::get('turn/query', 'Sca\TurnController@query');
			Route::resource('turn', 'Sca\TurnController');
			// Collaborator
			Route::post('turn/{turn}/collaborator', 'Sca\TurnController@collaborator_store');

		// Event
		Route::resource('event', 'Sca\EventController')->except(['index']);

		// RegisterType
		Route::resource('registertype', 'Sca\RegisterTypeController')->except(['index']);

		// Reports
		Route::group(['prefix' => 'report'], function () {
			// Intidential
			Route::get('/intidentials', 'Sca\Reports\IntidentialController@index');
		});

		// Checker
			// Index(WithTrashed)
			Route::get('checker/withtrashed', 'Sca\CheckerController@index_with_trashed');
			// Restore
			Route::get('checker/{checker}/restore', 'Sca\CheckerController@restore');
			// Group
			Route::put('checker/{checker}/group/sync', 'Sca\CheckerController@group_sync');
			// Record/Register
			Route::get('checker/{checker}/record/photo', 'Sca\CheckerController@register_photo_index');
		Route::resource('checker', 'Sca\CheckerController')->except(['index']);
	});
});