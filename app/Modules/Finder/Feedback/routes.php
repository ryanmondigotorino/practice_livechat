<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => '{slug}/feedback', 'namespace' => '\App\Modules\Finder\Feedback','middleware' => ['web','finder','revalidate'],'guard' => 'finder'], function(){
    Route::get('/','FeedbackController@index')->name('finder.feedback.index');
    Route::post('/feedback-save','FeedbackController@feedbacksave')->name('finder.feedback.feedback-save');
});
