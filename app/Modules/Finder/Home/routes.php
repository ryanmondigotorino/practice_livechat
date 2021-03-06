<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => '{slug}/', 'namespace' => '\App\Modules\Finder\Home','middleware' => ['web','finder','revalidate'],'guard' => 'finder'], function(){
    Route::get('/','HomeController@index')->name('finder.profile.index');
    Route::post('/get-first-acc','HomeController@getfirstacc')->name('finder.profile.get-first-acc');
    Route::post('/image-upload','HomeController@imageupload')->name('finder.profile.image-upload');
    Route::get('/edit-account','HomeController@editaccount')->name('finder.profile.edit-account');
    Route::post('/edit-account-save','HomeController@editaccountsave')->name('finder.profile.edit-account-save');
    Route::get('/change-password','HomeController@changepassword')->name('finder.profile.change-password');
    Route::post('/change-password-submit','HomeController@changepasswordsubmit')->name('finder.profile.change-password-submit');
});

Route::group(['prefix' => 'view/{slug}', 'namespace' => '\App\Modules\Finder\Home','middleware' => ['web','finder','revalidate'],'guard' => 'finder'], function(){
    Route::get('/','HomeController@view')->name('finder.profile.view');
    Route::post('/send-message','HomeController@sendmessage')->name('finder.profile.send-message');
});