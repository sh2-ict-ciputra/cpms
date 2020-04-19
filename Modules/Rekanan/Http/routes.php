<?php

Route::group(['middleware' => 'web', 'prefix' => 'rekanan', 'namespace' => 'Modules\Rekanan\Http\Controllers'], function()
{
    Route::get('/all', 'RekananController@index');
    Route::get('/add','RekananController@create');
    Route::get('/detail','RekananController@show');
    Route::post('/ceknpwp','RekananController@ceknpwp');
    Route::post('/store','RekananController@store');
    Route::post('/update','RekananController@update');

    Route::post('/spesifikasi-add','RekananController@spesifikasi');
    Route::post('/spesifikasi-delete','RekananController@deletespesifikasi');

    Route::post('/blacklist','RekananController@blacklist');

    Route::post("/user-add",'RekananController@useradd');
    Route::post("/user-update",'RekananController@userupdate');

    Route::get("/user","UserRekananController@index");
    Route::get("/user/fail",'UserRekananController@fail');
    Route::post("/user/update-rekanan",'UserRekananController@update');
    Route::get("/user/contact","UserRekananController@contact");
    Route::post("/user/updatecontact","UserRekananController@storecontact");

    Route::get("/user/cabang","UserRekananController@cabang");
    Route::post("/user/savecabang","UserRekananController@savecabang");

    Route::get("/user/price","UserRekananController@pricelist");
    Route::post("/user/uploadprice","UserRekananController@savepricelist");
    Route::get("/user/tender","UserRekananController@tender");
    Route::get("/user/tender/detail","UserRekananController@tender_detail");

    //Penawaran Pertama
    Route::get('/user/tender/penawaran-add','UserRekananController@addpenawaran');
    Route::post('/user/tender/penawaran-save','UserRekananController@savepenawaran');
    Route::get('/user/tender/penawaran-update','UserRekananController@step1');
    Route::post('/user/tender/penawaran-update1','UserRekananController@updatepenawaran1');


    //Penawaran Kedua
    Route::get('/user/tender/penawaran-step2','UserRekananController@step2');
    Route::post('/user/tender/penawaran-update2','UserRekananController@updatepenawaran2');

    //Penawaran ketiga
    Route::get('/user/tender/penawaran-step3','UserRekananController@step3');
    Route::post('/user/tender/penawaran-update3','UserRekananController@updatepenawaran3');

    Route::get('/usulan','RekananController@usulan');
    Route::post('/usulan/save','RekananController@saveusulan');
    Route::get('/create-folder','RekananController@create_folder');

    Route::get("/user/spk","UserRekananController@view_spk");
    Route::get("/spk/detail","UserRekananController@detailspk");
    Route::get("/spk/perpanjang-spk","UserRekananController@perpanjangspk");
    Route::post("/spk/input-perpanjang","UserRekananController@inperpanjangspk");

    //Penawaran Berulang
    Route::get('/user/tender/penawaran-step','UserRekananController@stepberulang');
    Route::post('/user/tender/penawaran-update-berulang','UserRekananController@updatepenawaran_berulang');
    Route::get('/user/tender/downloaddoc','UserRekananController@downloaddoc');

    Route::post('/user/gantipassword','UserRekananController@ganti_password');
    Route::post('/user/progress','UserRekananController@progress_detail');

    Route::get("/spk/pengajuan","UserRekananController@pengajuan");
    Route::post("/spk/progress_tahap","UserRekananController@progress_tahap");
    Route::post("/user/spk/save-pengajuan","UserRekananController@save_pengajuan");
    Route::post("/user/spk/vo","UserRekananController@vo");
    Route::post("/user/spk/vo_detail","UserRekananController@vo_detail");
    Route::post("/user/spk/pekerjaan","UserRekananController@pekerjaan_spk");


    //Data Barang

    //Pricelist
    Route::get("/user/data-barang","UserRekananController@showDataBarang");
    Route::get("/user/data-barang/select2/getCatItem","UserRekananController@select2GetItemCat");
    Route::get("/user/data-barang/select2/getSubCatItem","UserRekananController@select2GetItemSubCat");
    Route::get("/user/data-barang/select2/getItem","UserRekananController@select2GetItem");
    Route::get("/user/pricelist/datatable","UserRekananController@ajaxPricelistDataTable");
    Route::post("/user/pricelist/store","UserRekananController@storeSupplierPricelist");
    //ListPO
    Route::get("/user/po/datatable","UserRekananController@getSupplierProject");
    //ListMOU
    Route::get("/user/mou/datatable","UserRekananController@getMOUDataTable");

});
