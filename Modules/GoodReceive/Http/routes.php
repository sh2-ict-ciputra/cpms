<?php

Route::group(['middleware' => 'web', 'prefix' => 'goodreceive', 'namespace' => 'Modules\GoodReceive\Http\Controllers'], function()
{
    Route::get('/gr_dp','GoodReceiveController@grDp_index');
    Route::get('/gr_dp/create','GoodReceiveController@grDp_create');
    Route::get('/details/{id}','GoodReceiveController@details');
    Route::get('/details/items/{id}','GoodReceiveController@getDetailItems');

    Route::post('/gr_dp/store','GoodReceiveController@grDp_store');
    Route::post('/gr_dp/getItemPO','GoodReceiveController@grDp_getItemPO');
    Route::post('/gr_dp/checkisPO_DP','GoodReceiveController@grDp_checkisPO_DP');
    Route::post('/gr_dp/getVendorListsPO','GoodReceiveController@grDp_getVendorListsPO');

    Route::get('/gr_penerimaan_barang','GoodReceiveController@gr_penerimaan_barang_index');
    Route::get('/gr_penerimaan_barang/create','GoodReceiveController@gr_penerimaan_barang_create');

    Route::post('/gr_penerimaan_barang/getListsNomorPenerimaan','GoodReceiveController@gr_penerimaan_barang_getListsNomorPenerimaan');
    Route::post('/gr_penerimaan_barang/getListItemPenerimaan','GoodReceiveController@getListItemPenerimaan');
    Route::post('/gr_penerimaan_barang/store','GoodReceiveController@gr_penerimaan_barang_store');

    Route::get('/gr_penerimaan_barang/getData','GoodReceiveController@gr_penerimaan_barang_getData');


    // Route::get('/', 'GoodReceiveController@index');
    // Route::get('/create','GoodReceiveController@create');
    // Route::get('/getData','GoodReceiveController@getData');
    // Route::get('/Edit','GoodReceiveController@edit');

    // Route::post('/update','GoodReceiveController@update');
    // Route::post('/store','GoodReceiveController@store');
    // Route::post('/getItemsPenerimaanBarangPO','GoodReceiveController@getItemsPenerimaanBarangPO');

    // Route::post('/getItemPO','GoodReceiveController@getItemPO');

    Route::get('/getData','GoodReceiveController@getData');

    // Route::post('/getListsVendorPO','GoodReceiveController@getVendorListsPO');
    
});
