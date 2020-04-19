<?php

Route::group(['middleware' => 'web', 'prefix' => 'library', 'namespace' => 'Modules\Library\Http\Controllers'], function()
{
    Route::get('/', 'LibraryController@index');

    //Supplier
    Route::get('/supplier', 'LibraryController@showSupplier');
    Route::get('/supplier/datatable', 'LibraryController@getSupplierDataTable');
    Route::post('/supplier/detail', 'LibraryController@detailSupplier');

    Route::post('/supplier/pricelist/store', 'LibraryController@storeSupplierPricelist');
    Route::post('/supplier/pricelist/modify', 'LibraryController@modifySupplierPricelist');
    Route::post('/supplier/pricelist/delete', 'LibraryController@deleteSupplierPricelist');
    Route::post('/supplier/pricelist', 'LibraryController@getSupplierPricelist');
    Route::get('/supplier/pricelist/datatable', 'LibraryController@getSupplierPricelistDataTable');
    Route::get('/supplier/pricelist/download/{pricelist}', 'LibraryController@downloadFile');

    Route::post('/supplier/project', 'LibraryController@getSupplierProject');
    Route::post('/supplier/project/spk', 'LibraryController@getSupplierProjectSPK');
    Route::post('/supplier/project/spk/detail', 'LibraryController@getSupplierProjectSPKDetail');
    Route::post('/supplier/project/po', 'LibraryController@getSupplierProjectPO');
    Route::post('/supplier/project/po/detail', 'LibraryController@getSupplierProjectPODetail');
    
    //MOU
    Route::get('/mou', 'LibraryController@showMOU');
    Route::post('/mou/store', 'LibraryController@storeMOU');
    Route::post('/mou/modify', 'LibraryController@modifyMOU');
    Route::get('/mou/download/{mou}', 'LibraryController@downloadFileMOU');
    Route::get('/mou/datatable', 'LibraryController@getMOUDataTable');
    Route::post('/mou/select2/project', 'LibraryController@select2GetProject');
    Route::post('/mou/select2/supplier', 'LibraryController@select2GetSupplier');
    Route::post('/mou/select2/item', 'LibraryController@select2GetItem');

    //Harga-Satuan
    Route::get('/harga-satuan', 'LibraryController@showHargaSatuan');
    Route::get('/harga-satuan/get', 'LibraryController@getH');
    Route::post('/harga-satuan/coalist/devcost', 'LibraryController@getAjaxCoaItemDevCost');
    Route::post('/harga-satuan/coalist/concost', 'LibraryController@getAjaxCoaItemConCost');
    Route::get('/harga-satuan/datatable/devcost', 'LibraryController@getHargaSatuanDatatableDevCost');
    Route::get('/harga-satuan/datatable/concost', 'LibraryController@getHargaSatuanDatatableConCost');
    Route::post('/harga-satuan/update/tes', 'LibraryController@setIsDivider');
    Route::get('/harga-satuan/tesview', 'LibraryController@showTes');
    Route::get('/harga-satuan/tes', 'LibraryController@getListHargaSatuan');

    

    


});
