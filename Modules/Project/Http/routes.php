<?php

Route::group(['middleware' => 'web', 'prefix' => 'project', 'namespace' => 'Modules\Project\Http\Controllers'], function()
{
    Route::get('/', 'ProjectController@index');
    Route::get('/add','ProjectController@create');
    Route::post('/add-proyek','ProjectController@store');
    Route::get('/detail','ProjectController@show');
    Route::get('/detail-update','ProjectController@edit');
    Route::post('/edit-proyek','ProjectController@updateproject');
    Route::post('/getluas','ProjectController@getluas');
    Route::get('/data-umum','ProjectController@dataumum');
    Route::post('/updatedata-umum','ProjectController@updatedataumum');

    Route::get('/unit-type/','ProjectController@unittype');
    Route::get('/add-type/','ProjectController@addtype');
    Route::post('/save-type','ProjectController@savetype');
    Route::post('/delete-type','ProjectController@deletetype');
    Route::post('/update-type','ProjectController@updatetype');

    Route::get('/templatepekerjaan','ProjectController@template');
    Route::post('/add-template','ProjectController@addtemplate');
    Route::get('/detail-template','ProjectController@detailtemplate');
    Route::post('/update-template','ProjectController@updatetemplate');
    Route::post('/add-templatedetail','ProjectController@savetemplatedetail');
    Route::get('/spesifikasi-template','ProjectController@spesifikasitemplate');
    Route::post('/spesifikasi-savetemplate','ProjectController@savespectempalte');
    Route::post('/spesifikasi-delete','ProjectController@deletespec');

    Route::get('/kawasan','ProjectController@kawasan');
    Route::get('/add-kawasan','ProjectController@addKawasan');
    Route::post('/save-kawasan','ProjectController@saveKawasan');
    Route::post('/delete-kawasan','ProjectController@deleteKawasan');
    Route::get('/edit-kawasan','ProjectController@editKawasan');
    Route::post('/update-kawasan','ProjectController@updateKawasan');

    Route::get('/bloks','ProjectController@blokKawasan');
    Route::get('/add-blok','ProjectController@addblok');
    Route::post('save-blok/','ProjectController@saveblok');
    Route::get('/edit-blok','ProjectController@editblok');
    Route::post('/update-blok','ProjectController@updateblok');
    Route::post('/delete-blok','ProjectController@deleteblok');

    Route::get('/units','ProjectController@units');
    Route::get('/add-unit','ProjectController@addunit');
    Route::post("/save-unit","ProjectController@saveunit");
    Route::get('/edit-unit','ProjectController@viewunit');
    Route::post('/update-unit','ProjectController@updateunit');
    Route::post('/delete-unit','ProjectController@deleteunit');
    Route::post("/save-masal-unit","ProjectController@savemasalunit");
    Route::post("/cekunit","ProjectController@cekunit");

    Route::post('/itempekerjaan/','ProjectController@itempekerjaan');

    Route::post('/save-hppupdate','ProjectController@savehppupdate');

    Route::get('/unit-hadap','ProjectController@unithadap');
    Route::post('/save-hadap','ProjectController@savehadap');
    Route::post('/delete-hadap','ProjectController@deletehadap');

    Route::post('/unit/senderems','ProjectController@senderems');
    Route::post('/todolist','ProjectController@todolist');
    Route::post('/generateunit','ProjectController@generateunit');

    Route::get('/unitsold','ProjectController@unitsold');
    Route::post('/updatepending','ProjectController@updatepending');
    Route::post('/saveunitchange','ProjectController@updateUnitChange');
    Route::get('/test','ProjectController@test');

    Route::get('/kawasan2','ProjectController@kawasan2');
    Route::post('/allposts','ProjectController@allPosts');
    // Route::post('allposts', 'PostController@allPosts' )->name('allposts');
    Route::get('/migrasi-purpose','ProjectController@migrasiPurpose');
});
