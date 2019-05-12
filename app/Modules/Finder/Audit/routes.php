<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => '{slug}/audit', 'namespace' => '\App\Modules\Finder\Audit','middleware' => ['web','finder','revalidate'],'guard' => 'finder'], function(){
    Route::get('/','AuditController@index')->name('finder.audit.index');
    Route::get('/get-audit','AuditController@getaudit')->name('finder.audit.get-audit');
    Route::get('/download-xlsx','AuditController@downloadxlsx')->name('finder.audit.download-xlsx');
});
