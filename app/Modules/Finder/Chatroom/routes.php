<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => '{slug}/message/{slugto?}', 'namespace' => '\App\Modules\Finder\Chatroom','middleware' => ['web','finder','revalidate'],'guard' => 'finder'], function(){
    Route::get('/','ChatroomController@index')->name('finder.chat-room.index');
    Route::get('/send-chat','ChatroomController@sendchat')->name('finder.chat-room.send-chat');
    Route::post('/send-chat-sample','ChatroomController@sendchatsample')->name('finder.chat-room.send-chat-sample');
});