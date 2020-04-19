<?php

Route::group(['middleware' => 'web', 'prefix' => 'home', 'namespace' => 'Modules\Home\Http\Controllers'], function()
{
    Route::get('/', 'HomeController@index')->middleware("auth");
    Route::get('/setautobudget','HomeController@setautobudget');
    Route::get('/setfasumbudget','HomeController@setfasumbudget');
});
