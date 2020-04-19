<?php

Route::group(['middleware' => 'web', 'prefix' => 'report', 'namespace' => 'Modules\Report\Http\Controllers'], function()
{
    Route::get('/all', 'ReportController@index');
    Route::get('/project/detail','ReportController@show');
    Route::get('/project/hpp/history','ReportController@hpphistory');
    Route::get('/project/document','ReportController@document');
    Route::get('/project/budget',"ReportController@budget");
    Route::get('/project/budgetdetail',"ReportController@budgetdetail");
    Route::get("/project/costreport",'ReportController@costreport');
    Route::get('/project/kontraktor','ReportController@kontraktor');
    Route::get("/project/reportkawasan",'ReportController@reportkawasan');
    Route::get('/project/reportpekerjaan','ReportController@reportpekerjaan');

    Route::post('/project/searchkawasan'.'ReportController@searchkawasan');

    Route::get('/document/budget','DocumentController@budget');
    Route::get('/document/budget/detail','DocumentController@budget_detail');
    Route::get('/document/budget/devcost','DocumentController@budget_devcost');
    Route::get('/document/budget/referensi','DocumentController@budget_referensi');

    Route::get('/document/pekerjaan','DocumentController@pekerjaan');

    Route::get('/cashflow','ReportController@cashflow');
    Route::get('/detailcashflow','ReportController@detailcashflow');
    Route::get('/cashflow/approval','ReportController@approvalcashflow');

    Route::get('/project/rakor/','RakorController@index');
    Route::get('/project/rakor/powerpoint','RakorController@create');

    Route::get('/cost-report','Report2Controller@costreport');
    Route::get('/report-kontraktor','Report2Controller@reportkontraktor');
    Route::get('/report-kawasan','Report2Controller@reportkawasan');
    Route::get('/report-pekerjaan','Report2Controller@reportpekerjaan');
    Route::get('/costreportss','Report2Controller@costreportss');
    Route::get('/reportkontraktor','Report2Controller@reportkontraktorss');
    Route::get('/reportkawasan','Report2Controller@reportkawasanss');
    Route::get('/reportpekerjaan','Report2Controller@reportpekerjaanss');
    Route::get('/concost-detail','Report2Controller@concostdetail');
    Route::get('/concost-summary','Report2Controller@concostsummary');
    Route::post('/detailpek','Report2Controller@detailpek');
    Route::get('/devcost-detail','Report2Controller@devcostdetail');
    Route::get('/devcost-summary','Report2Controller@devcostsummary');
    Route::get('/devcostsummary','Report2Controller@devcostsummaryss');
    Route::post('/devcostdetail','Report2Controller@devcostdetailss');
    Route::post('/detaildevcost','Report2Controller@detaildevcost');
    Route::post('/detail-kawasan','Report2Controller@detailkaw');
    Route::get('/concostdetail','Report2Controller@concostdetailss');
    Route::post('/detail-concost','Report2Controller@detailconcost');

});
