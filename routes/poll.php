<?php

use Illuminate\Http\Request;

Route::prefix('poll')->group(function () {
    // Poll
        // Form
            // Qualification
            Route::get('poll/{poll}/form/{form}/qualification', 'Poll\PollController@form_qualification');

    // Apply
        Route::get('apply/query', 'Poll\ApplyController@query');
});