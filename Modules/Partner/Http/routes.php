<?php

Route::group(['middleware' => 'web', 'prefix' => 'partner', 'namespace' => 'Modules\Partner\Http\Controllers'], function()
{
    Route::get('/', 'PartnerController@index');
    Route::get('/detail', 'PartnerController@show');
    Route::get('/add', 'PartnerController@create');
    Route::post('/store', 'PartnerController@store');
    Route::post('/tambah-project', 'PartnerController@tambahProject');
});
