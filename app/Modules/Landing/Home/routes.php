<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => '/', 'namespace' => '\App\Modules\Landing\Home','middleware' => ['web']], function(){
    Route::get('/','HomeController@index')->name('landing.home.index');
    Route::post('/login-save','HomeController@loginSave')->name('landing.home.login-save');
    Route::post('/logout','HomeController@logout')->name('landing.home.logout');
    Route::get('/sign-up','HomeController@signup')->name('landing.home.sign-up');
    Route::get('/sign-up/account-verification/{userName}','HomeController@accountVerification')->name('landing.home.account-verification');
    Route::post('/sign-up-submit','HomeController@signupsubmit')->name('landing.home.sign-up-submit');
});