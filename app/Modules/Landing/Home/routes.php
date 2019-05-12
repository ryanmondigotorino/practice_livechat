<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => '/', 'namespace' => '\App\Modules\Landing\Home','middleware' => ['web']], function(){
    Route::get('/','HomeController@index')->name('landing.home.index');
    Route::post('/login-save','HomeController@loginSave')->name('landing.home.login-save');
    Route::post('/logout','HomeController@logout')->name('landing.home.logout');
});