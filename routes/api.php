<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
// API Casier
Route::post('/test', 'ApiController@test');
Route::post('/requestkey', 'ApiController@requestkey');
Route::post('/cekdapat_bayar', 'ApiController@cekdapat_bayar');
Route::post('/input_tanggalcair', 'ApiController@input_tanggalcair');
Route::post('/input_bayardokumen', 'ApiController@input_bayardokumen');

// API MOBILE PENGAWAS (NBS)
Route::post('/requestkeyMobilePengawas', 'ApiController@requestkeyMobilePengawas');
Route::post('/loginMobilePengawas', 'ApiController@loginMobilePengawas');
Route::post('/forgotPasswordMobilePengawas', 'ApiController@forgotPasswordMobilePengawas');
Route::post('/changePasswordMobilePengawas', 'ApiController@changePasswordMobilePengawas');
Route::post('/viewProfileMobilePengawas', 'ApiController@viewProfileMobilePengawas');
Route::post('/changeProfilePictureMobilePengawas', 'ApiController@changeProfilePictureMobilePengawas');
Route::post('/listUnit', 'ApiController@listUnit');
Route::post('/detailPekerjaan', 'ApiController@detailPekerjaan');
Route::post('/detailTahapan', 'ApiController@detailTahapan');
Route::post('/detailIpk', 'ApiController@detailIpk');
Route::post('/progressLapangan', 'ApiController@ProgressLapangan');

