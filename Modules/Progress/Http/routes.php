<?php

Route::group(['middleware' => 'web', 'prefix' => 'progress', 'namespace' => 'Modules\Progress\Http\Controllers'], function()
{
    Route::get('/', 'ProgressController@index');
    Route::get('/show', 'ProgressController@show');
    Route::get('/create', 'ProgressController@create');
    Route::post('/saveprogress','ProgressController@saveprogress');
    Route::post('/updatetermyn','ProgressController@edit');

    Route::post('/saveschedule','ProgressController@saveschedule');
    Route::get('/tambah','ProgressController@tambah');
    Route::get('/photo',"ProgressController@photo");

    Route::get('/update-ipk', 'ProgressController@updateipk');
    Route::get('/update-progress', 'ProgressController@updateprogress');
    Route::post('/simpan-ipk', 'ProgressController@simpanipk');
    Route::post('/simpan-progress', 'ProgressController@simpanprogress');

    Route::get('/sik', 'ProgressController@allsik');
    Route::get('/sik-biaya', 'ProgressController@sikbiaya');
    Route::get('/sik-nonbiaya', 'ProgressController@siknonbiaya');
    Route::post('/input-siknonbiaya', 'ProgressController@insiknonbiaya');
    Route::get('/detailsiknon', 'ProgressController@detailsiknon');
    Route::post('/update-siknonbiaya', 'ProgressController@updatesiknon');
    Route::post('/input-sikbiaya', 'ProgressController@inputsikbiaya');
    Route::get('/detailsikbiaya', 'ProgressController@detailsikbiaya');
    Route::post('/update-sikbiaya', 'ProgressController@updatesikbiaya');
    Route::get('/request-approval', 'ProgressController@requestapproval');
    Route::post('/saveschedulevo','ProgressController@saveschedulevo');

    Route::get('/update-progressvo', 'ProgressController@updateprogressvo');
    Route::post('/simpan-progressvo', 'ProgressController@simpanprogressvo');

    Route::get('/pengajuan', 'ProgressController@index_pengajuan');
    Route::get('/pengajuan/detail', 'ProgressController@detail_pengajuan');
    Route::post('/pengajuan/persetujuan-pengajuan', 'ProgressController@persetujuan_pengajuan');
    Route::post('/progress_ipk', 'ProgressController@progress_ipk');
    Route::post('/simpan-ipk-progress', 'ProgressController@simpan_ipk_progress');

    Route::post('/simpan-progress-pertahap', 'ProgressController@simpanprogress_pertahap');

});
