<?php

use Illuminate\Http\Request;

// Captures
Route::put('inventory/capture/{capture}/markasreviewed', 'Inventory\CaptureController@mark_as_reviewed');
	// Query
	Route::get('inventory/{inventory}/capture/query', 'Inventory\CaptureController@query');
	// Change request
	Route::post('inventory/capture/{capture}/changerequest', 'Inventory\CaptureController@change_request');
	// Delete(Soft)
	Route::post('inventory/capture/{capture}/delete', 'Inventory\CaptureController@destroy');
	// Update
	Route::put('inventory/capture/{capture}', 'Inventory\CaptureController@update');
	// Store
Route::group(['middleware' => ['inventory_status']], function(){
	Route::post('inventory/{inventory}/capture', 'Inventory\CaptureController@store');
	// Update
	Route::put('inventory/{inventory}/capture/{capture}', 'Inventory\CaptureController@update_capture');	
});

// Articles
Route::resource('inventory/article', 'Inventory\ArticleController');

// Capturists
Route::resource('inventory/capturist', 'Inventory\CapturistController');

// Teams
Route::resource('inventory/team', 'Inventory\TeamController');
	// Captures
	Route::get('inventory/{inventory}/team/{team}/capture', 'Inventory\InventoryController@team_capture_index');

// InventoryType
	Route::resource('inventory/inventorytype', 'Inventory\InventoryTypeController');

// Inventorys
	// Query
	Route::get('inventory/query', 'Inventory\InventoryController@query');
	// Template
	Route::get('inventory/{inventory}/templatestructure', 'Inventory\InventoryController@templatestructure');
	// Report
	Route::get('inventory/{inventory}/report', 'Inventory\InventoryController@report');
Route::resource('inventory', 'Inventory\InventoryController');
	// Inventory Teams
	Route::get('inventory/{inventory}/team', 'Inventory\InventoryController@team_index');
	Route::put('inventory/{inventory}/team', 'Inventory\InventoryController@team_sync');