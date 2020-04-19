<?php

Route::group(['middleware' => 'web', 'prefix' => 'budget', 'namespace' => 'Modules\Budget\Http\Controllers'], function()
{
    Route::get('/', 'BudgetController@index');
    Route::get('/proyek','BudgetController@index');
    Route::get('/add-budget','BudgetController@create');
    Route::post('/save-budget','BudgetController@store');
    Route::get('/detail','BudgetController@show');
    Route::post('/update-budget','BudgetController@edit');
    Route::get('/approval','BudgetController@approvalhistory');
    Route::get('/referensi','BudgetController@referensi');

    Route::get('/item-budget','BudgetController@itempekerjaan');
    Route::post('/item-detail','BudgetController@itemdetail');

    Route::post('/item-save','BudgetController@itemsave');
    Route::post('/item-saveedit','BudgetController@itemupdate');
    Route::post('/delete-itembudget/','BudgetController@deletebudget');
    Route::post('/update-itembudget','BudgetController@itemupdate');
    Route::get('/edit-itembudget','BudgetController@edititem');
    Route::post('/save-itembudget','BudgetController@saveitem');

    Route::post("/approval-add",'BudgetController@approval');

    Route::get("/cashflow/", 'BudgetController@cashlflow');
    Route::post("/cashflow/add-cashflow", 'BudgetController@addcashflow');
    Route::get("/cashflow/detail-cashflow", 'BudgetController@detailcashflow');
    Route::post("/cashflow/update-cashflow",'BudgetController@updatecashflow');
    Route::get("/cashflow/add-item/","BudgetController@itemcashflow");
    Route::post("/cashflow/save-item","BudgetController@savecashflow");
    Route::get("/cashflow/view-item","BudgetController@viewcashflow");
    Route::post("/cashflow/update-item","BudgetController@updateitemcashflow");
    Route::post('/cashflow/save-monthly','BudgetController@savemonthly');
    Route::post('/cashflow/update-monthly','BudgetController@updatemonthly');
    Route::post('/cashflow/delete-monthly','BudgetController@deletemonthly');
    Route::post('/cashflow/approval','BudgetController@approval_cashflow');
    Route::get("/cashflow/newadd-item/","BudgetController@newitemcashflow");
    Route::post('/cashflow/savenewadd-item','BudgetController@savenewitemcashflow');
    Route::get('/cashflow/views','BudgetController@viewcf');
    Route::get('/cashflow/referensi','BudgetController@referensicf');
    Route::get('/cashflow/viewitemconcost','BudgetController@cashflowconcost');
    Route::post('/cashflow/save-cashouttype','BudgetController@savecashouttype');
    Route::post('/cashflow/removeco','BudgetController@removeco');
    Route::post('/cashflow/removecoco','BudgetController@removecoco');
    Route::post('/cashflow/view_edit_tahunan','BudgetController@view_edit_tahunan');
    Route::post('/cashflow/update_budget_tahunan_detail','BudgetController@update_budget_tahunan_detail');
    Route::post('/cashflow/view_Bulanan','BudgetController@view_bulanan');
    Route::post('/cashflow/update_budget_tahunan_bulanan','BudgetController@update_budget_tahunan_bulanan');
    Route::post("/cashflow/create_carryover", 'BudgetController@carryover');
    Route::post('/cashflow/view_Bulanan_carryover','BudgetController@view_bulanan_carryover');
    Route::post('/cashflow/update_budget_tahunan_bulanan_carryover','BudgetController@update_budget_tahunan_bulanan_carryover');

    Route::get('/revisibudget','BudgetController@revisibudget');
    Route::post('/save-budgetrevisi','BudgetController@saverevisi');
    Route::get('/show-budgetrevisi','BudgetController@detailrevisi');
    Route::get('/item-budgetrevisi','BudgetController@itemrevisi');
    Route::post('/saveitem-budgetrevisi','BudgetController@saveitemrevisi');
    Route::get('/list-budgetrevisi','BudgetController@listrevisi');
    Route::get('/item-revisi','BudgetController@additemrevisi');
    Route::post('/save-itemrevisi','BudgetController@savenewitemrevisi');

    Route::get('/add-carryover','BudgetController@addcarryover');
    Route::post('/save-carryover','BudgetController@savecaryyover');
    Route::post('/delete-carryover','BudgetController@deletecarryover');

    Route::get('/createrobot','BudgetController@createrobot');
    Route::get('/createhpp','BudgetController@createhpp');
    
    Route::get("/cashflow/revisi-item/","BudgetController@revitemcashflow");
    Route::post("/cashflow/save-revitem","BudgetController@saverevitem");
    Route::get("/cashflow/approval","BudgetController@approval_history");
    Route::get("/cashflow/revisi-item-cons/","BudgetController@revitemcashflowcons");

    Route::get("/draft","BudgetController@draft");
    Route::post("/approval-update","BudgetController@updateapproval");
    Route::post("/approval/manual","BudgetController@reapproval");

    Route::get("/master","BudgetMasterController@index");
    Route::get("/master/add","BudgetMasterController@create");
    Route::post("/master/save","BudgetMasterController@store");
    Route::get("/master/detail","BudgetMasterController@show");
    Route::post("/master/removepekerjaan","BudgetMasterController@destroy");
    Route::post("/master/reload","BudgetMasterController@update");

    Route::post("/item-saverevisi","BudgetController@itemrevisisavet");
    Route::post("/cashflow/save-monthlyco","BudgetController@itemsavemonthlyco");
    Route::get("/budget_tahunan/cashflow-concost","BudgetController@cashflowconcost");
    Route::post("/item-saveitemconcost","BudgetController@saveitemconcost");

    Route::post("/item-viewconcost","BudgetController@itemviewconcost");

    Route::post("/carryoverdc","BudgetController@getCarryOverDC");
    Route::post("/carryovercc","BudgetController@getCarryOverCC");
    Route::post("/rencanadc","BudgetController@getRencanaDC");

    Route::get('/tambah_pekerjaan','BudgetController@tambahPekerjaan');
    Route::post('/itempekerjaan','BudgetController@itemPekerjaan_select2');
    Route::post('/satuan','BudgetController@satuan');
    Route::post('/save-itempekerjaan','BudgetController@save_itempekerjaan');
    Route::post('/budgetDepartment','BudgetController@budget_department');
    Route::post('/rekap','BudgetController@rekap_project');
    Route::post('/cashout_coa_lama','BudgetController@cashout_coa_lama');
});
