<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => '{slug}/chat-room', 'namespace' => '\App\Modules\Finder\Chatroom','middleware' => ['web','finder','revalidate'],'guard' => 'finder'], function(){
    Route::get('/','ChatroomController@index')->name('finder.chat-room.index');
});