<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => '/', 'namespace' => '\App\Modules\Finder\Home','middleware' => ['web','finder','revalidate'],'guard' => 'finder'], function(){
    Route::get('/{slug}','HomeController@index')->name('finder.profile.index');
});