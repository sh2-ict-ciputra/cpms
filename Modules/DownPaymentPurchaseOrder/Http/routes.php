<?php

Route::group(['middleware' => 'web', 'prefix' => 'downpaymentpurchaseorder', 'namespace' => 'Modules\DownPaymentPurchaseOrder\Http\Controllers'], function()
{
    Route::get('/', 'DownPaymentPurchaseOrderController@index');
    Route::get('/getData','DownPaymentPurchaseOrderController@getData');
    Route::get('/create','DownPaymentPurchaseOrderController@create');
    Route::get('/detail/{id}','DownPaymentPurchaseOrderController@detail');
    Route::get('/detail/step_dp/{id}','DownPaymentPurchaseOrderController@getDataDetail');


    Route::post('/store','DownPaymentPurchaseOrderController@store');
    Route::post('/getVendorListsPO','DownPaymentPurchaseOrderController@getVendorListsPO');
    Route::post('/update','DownPaymentPurchaseOrderController@update');
});
