<?php

use Illuminate\Http\Request;

Route::prefix('checklist')->group(function(){
	Route::resource('doc', 'Checklist\ChecklistController');	
	Route::resource('factura', 'Checklist\FacturaController');

    Route::get('ruta/withtrashed', 'Checklist\RutaController@withtrashed');
	Route::resource('ruta', 'Checklist\RutaController');

    Route::get('unidad/withtrashed', 'Checklist\UnidadController@withtrashed');
	Route::resource('unidad', 'Checklist\UnidadController');

    Route::get('fletera/withtrashed', 'Checklist\FleteraController@withtrashed');
	Route::resource('fletera', 'Checklist\FleteraController');

    Route::get('inspector/withtrashed', 'Checklist\InspectorController@withtrashed');
	Route::resource('inspector', 'Checklist\InspectorController');

    Route::get('transportista/withtrashed', 'Checklist\TransportistaController@withtrashed');
	Route::resource('transportista', 'Checklist\TransportistaController');

	Route::delete('file/{file}', 'Checklist\ChecklistController@fileDelete');
	Route::post('{checklist}/file', 'Checklist\ChecklistController@fileStore');
	Route::get('doc/{checklist}/format', 'Checklist\ChecklistController@format');
	Route::put('doc/{checklist}/finalize', 'Checklist\ChecklistController@finalize');
	Route::get('factura/bychecklistid/{checklist}', 'Checklist\FacturaController@byChecklistId');

	// Restors
	Route::put('ruta/{ruta}/restore', 'Checklist\RutaController@restore');
	Route::put('unidad/{unidad}/restore', 'Checklist\UnidadController@restore');
	Route::put('fletera/{fletera}/restore', 'Checklist\FleteraController@restore');
	Route::put('inspector/{inspector}/restore', 'Checklist\InspectorController@restore');
	Route::put('transportista/{transportista}/restore', 'Checklist\TransportistaController@restore');
});