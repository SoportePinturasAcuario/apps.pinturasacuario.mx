<?php

use Illuminate\Http\Request;

Route::prefix('iso')->group(function () {
    // Folder
    Route::post('folder/open', 'Iso\FolderController@open');
    Route::post('folder/create', 'Iso\FolderController@store');
    Route::put('folder/update', 'Iso\FolderController@update');

    // File
    Route::post('file', 'Iso\FileController@store');
    Route::get('file/show', 'Iso\FileController@show');

    // Auth
    Route::post('folder/destroy', 'Iso\FolderController@destroy');
    Route::post('file/destroy', 'Iso\FileController@destroy'); 
});