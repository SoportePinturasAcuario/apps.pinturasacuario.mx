<?php

use Illuminate\Http\Request;

Route::prefix('scanning')->group(function () {
	Route::resource('article', 'Scanning\ArticleController');
});