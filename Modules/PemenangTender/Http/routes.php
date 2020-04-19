<?php

Route::group(['middleware' => 'web', 'prefix' => 'pemenangtender', 'namespace' => 'Modules\PemenangTender\Http\Controllers'], function()
{
    Route::get('/', 'PemenangTenderController@index');
    Route::get('/show','PemenangTenderController@show');
    Route::get('/detail/{id}','PemenangTenderController@detail');
    Route::get('/detail_data/{id}','PemenangTenderController@detail_data');
});
