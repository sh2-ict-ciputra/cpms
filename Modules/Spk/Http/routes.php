<?php

Route::group(['middleware' => 'web', 'prefix' => 'spk', 'namespace' => 'Modules\Spk\Http\Controllers'], function()
{
    Route::get('/', 'SpkController@index');
    Route::get('/create','SpkController@create');
    Route::get('/detail','SpkController@show');
    Route::post('/update','SpkController@update');
    Route::post('/update-date','SpkController@editdate');
    Route::post('/update-payment','SpkController@editpayment');
    Route::post('/create-termyn','SpkController@termyn');
    Route::post('/add-progress-detail','SpkController@termyndetail');
    Route::post('/update-progress-detail','SpkController@updatetermyn');
    Route::post('/approval','SpkController@approval');

    Route::get('/add-bap','SpkController@addbap');
    Route::post('/save-bap','SpkController@savebap');
    Route::get('/detail-bap','SpkController@detailbap');

    Route::get('/voucher-add','SpkController@addvoucher');

    Route::post('/update-dp/','SpkController@updatedp');
    Route::post('/save-dp','SpkController@savedptermin');

    Route::post('/save-retensi','SpkController@saveretensis');
    Route::post('/delete-retensi','SpkController@deleteretensi');
    Route::post('/minprogress','SpkController@saveprogress');

    Route::get('/approval_history','SpkController@approval_history');

    Route::get('/sik-create','SpkController@createsik');
    Route::post('/sik-store','SpkController@storesik');
    Route::get('/sik-show','SpkController@showsik');

    Route::get('/create-vo','SpkController@createvo');
    Route::post('/save-vo','SpkController@savevo');
    Route::post('/detailunit-vo','SpkController@detailunitvo');
    Route::post('/delete-vo','SpkController@deletevo');
    Route::post('/create-progress','SpkController@setprogress');

    Route::get('/sik-unit','SpkController@sikunit');

    //Master SPK
    Route::get('/tipe','SpkMasterController@index');
    Route::post('/delete-tipe','SpkMasterController@destroy');
    Route::post('/save-tipe','SpkMasterController@store');

    //SUPP
    Route::get('/supp','SuppController@create');
    Route::post('/supp/store','SuppController@store');
    Route::get('/supp/show','SuppController@show');
    Route::get('/supp/download','SuppController@downloadsupp');

    //PIC
    Route::post('/addpic','SpkController@addpic');

    //Cetakan
    Route::post('/cetak_bap','SpkController@cetakan_bap');
    Route::get('/cetakspk','DownloadsController@cetakspk');
    
    Route::get('/cetak-counterprogres','SpkController@counterProgres');
    Route::get('/cetak-voucher','SpkController@voucher');

    Route::get('/kelola-ipk','SpkController@addipk');
    Route::get('/progress','SpkController@progresspekerjaan');
    Route::post('/tambah-progress','SpkController@tambahprogress');
    Route::post('/hapus-progress','SpkController@hapusprogress');
    Route::get('/edit-progress/{id_item}','SpkController@editprogress');
    Route::post('/update-progress','SpkController@updateprogress');
    Route::post('/tambah-ipk','SpkController@tambahipk');
    Route::post('/hapus-ipk','SpkController@hapusipk');
    Route::get('/edit-ipk/{id_item}/{id_spk}/{name}',  'SpkController@editipk');
    Route::post('/update-ipk','SpkController@updateipk');
    Route::post('/tahapan-ipk','SpkController@tahapanIpk');

    Route::get('/supp/cetakSupp',  'SuppController@cetakSupp');
    Route::get('/cetak-termyn',  'SpkController@cetakTermyn');
    
    Route::get('/sik',  'SpkController@approvalsik');
    Route::get('/sik/detailsikbiaya',  'SpkController@detailsikbiaya');
    Route::get('/sik/detailsiknon',  'SpkController@detailsiknon');
    Route::get('/sik/request-approval-sik',  'SpkController@requestapproval');

    Route::get('/vo/progress', 'SpkController@progressvo');
    Route::post('/vo/tambah-progress','SpkController@tambahprogressvo');
    Route::get('/edit-progressvo/{id_item}/{id_spk}/{name}','SpkController@editprogressvo');
    Route::post('/update-progressvo','SpkController@updateprogressvo');
    Route::post('/hapus-progressvo','SpkController@hapusprogressvo');
    Route::get('/approval_history_vo','SpkController@approval_history_vo');

    Route::post('/save-termynbayar','SpkController@savetermynbayar');

    Route::get('/percepatan','SpkController@addpercepatan');
    Route::post('/save-percepatan','SpkController@savepercepatan');
    Route::get('/request-approval-percepatan','SpkController@request_percepatan');
    Route::post('/update-sikbiaya','SpkController@update_sikbiaya');

    Route::get('/cetakSpk',  'SpkController@cetakSpk');
    Route::get('/cetakSik','SpkController@cetaksik');
    Route::get('/cetakVo','SpkController@cetakvo');

    Route::post('/addPartner','SpkController@addPartner');

});
