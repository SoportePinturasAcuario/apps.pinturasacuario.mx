<?php

use Illuminate\Http\Request;


Route::resource('waste', 'WasteController');

Route::get('waste/{waste}/finalize', 'WasteController@finalize');
Route::post('waste/{waste}/file', 'WasteController@file');
Route::delete('waste/{waste}/file/{file}', 'WasteController@fileDelete');
Route::post('waste/{waste}/approve', 'WasteController@approve');
Route::get('waste/{waste}/approvals', 'WasteController@approvals');

// Reporte
Route::get('waste/{waste}/report', 'WasteController@report');
