<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Voucher\Entities\Voucher;
use Modules\Voucher\Entities\VoucherDetail;
use Modules\Tender\Entities\TenderKorespondensi;
use Modules\Tender\Entities\TenderRekanan;
use Illuminate\Support\Facades\Crypt;
use Modules\User\Entities\User;
use Modules\Spk\Entities\Spk;
use Modules\Project\Entities\UnitProgress;
use Modules\Spk\Entities\ProgressTambahan;
use Modules\Spk\Entities\IpkTambahan;
use Modules\Spk\Entities\IpkProgressTahapan;
use Auth;
use Hash;
use Storage;
use DB;

class ApiController extends Controller
{
     /**
     * Display a listing of the resource.
     * @return Response
     */
    public function test(){
        return response("hohohoho");
    }

    public static function CallAPI($method, $url, $data = false)
    {
        $curl = curl_init();

        switch ($method)
        {
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);
                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_PUT, 1);
                break;
            default:
                if ($data)
                    $url = sprintf("%s?%s", $url, http_build_query($data));
        }

        // Optional Authentication:
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, "username:password");

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($curl);

        curl_close($curl);

        return $result;
    }

    public function requestkey(){
        $date = date("Y-m-d");
        $encrypted = "inipassword".$date;
        for ($i=0; $i < 2 ; $i++) { 
            # code...
            $encrypted = Crypt::encryptString($encrypted);
        }

        // $decrypted = $encrypted;
        // for ($i=0; $i < 5 ; $i++) { 
        //     # code...
        //     $decrypted = Crypt::decryptString($decrypted);
        // }
        // $encrypted = Crypt::encryptString('password');
        return response(['key' => $encrypted]);
    }

    public function cekdapat_bayar(Request $request){
        $voucher = Voucher::where("id",$request->uniqnumber)->where("voucher_id",$request->voucher_id)->first();
        if($voucher != null){
            if($voucher->pencairan_status == 1){
                $status = 1;
                $description = "voucher dapat dicairkan";
            }else{
                $status = 0;
                $description = "voucher tidak dapat dicairkan";
            }
        }else{
            $status = 0;
            $description = "uniqnumber dan voucher_id tidak sesuai";
        }

        return response(['status' => $status, 'description' => $description]);
    }

    public function input_tanggalcair(Request $request){
        $decrypted = $request->requestkey;
        for ($i=0; $i < 2 ; $i++) { 
            # code...
            $decrypted = Crypt::decryptString($decrypted);
        }
        $date = date("Y-m-d");
        $pass = "inipassword".$date;
        if($decrypted == $pass){
            $voucher = Voucher::where("id",$request->uniqnumber)->where("voucher_id",$request->voucher_id)->first();
            if($voucher != null){
                $voucher->pencairan_date = $request->tanggal_cair;
                $voucher->voucher_number = $request->voucher_no;
                $voucher->save();
                return response(['result' => 1, 'description' => "berhasil tersimpan"]);
            }else{
                return response(['result' => 0, 'description' => "uniqnumber dan voucher_id tidak sesuai"]);
            }
        }else{
            return response(['result' => 0, 'description' => "request key invalid"]);
        }
    }

    public function input_bayardokumen(Request $request){
        $decrypted = $request->requestkey;
        for ($i=0; $i < 2 ; $i++) { 
            # code...
            $decrypted = Crypt::decryptString($decrypted);
        }
        $date = date("Y-m-d");
        $pass = "inipassword".$date;
        if($decrypted == $pass){
            $korespondensi = TenderKorespondensi::where("id",$request->id_surat)->first();
            if($korespondensi){
                $rekanan_tender = TenderRekanan::find($korespondensi->tender_rekanan->id);
                $rekanan_tender->doc_bayar_date = $request->tanggal_bayar;
                $rekanan_tender->doc_bayar_status = 1;
                $rekanan_tender->save();
                return response(['result' => 1, 'description' => "berhasil tersimpan"]);
            }else{
                return response(['result' => 0, 'description' => "no_surat dan id_surat tidak sesuai"]);
            }
        }else{
            return response(['result' => 0, 'description' => "request key invalid"]);
        }
    }

    
    # API CPMS - NBS Mobile Pengawas

    public function requestkeyMobilePengawas(Request $request){
        $password_api = $request->password_api;
        if($password_api == ""){
            $date = date("Y-m-d");
            $encrypted = "inipasswordMobilePengawas".$date;
            for ($i=0; $i < 2 ; $i++) { 
                # code...
                $encrypted = Crypt::encryptString($encrypted);
            }

            return response(['result'=> 1 ,'key' => $encrypted ,'message' => "Berhasil"]);
        }else{
            return response(['result'=> 1 ,'message' => "Password Salah"]);
        }
    }

    public function loginMobilePengawas(Request $request){
        $password_api = $request->password_api;
        $decrypted = $request->requestkey;
        $username = $request->username;
        $password = $request->password;
        for ($i=0; $i < 2 ; $i++) { 
            # code...
            $decrypted = Crypt::decryptString($decrypted);
        }

        $date = date("Y-m-d");
        $pass = "inipasswordMobilePengawas".$date;
        if($decrypted == $pass && $password_api == ""){
            $user = User::where("user_login", $username)->first();
            if($user != null){
                // return Hash::check($password, $user->password_mobile, []);
                if(Hash::check($password, $user->password_mobile, []) == true){
                    return response(['result'=> 1, 'message' => "OK"]);
                }else{
                    return response(['result'=> 0, 'message' => "Password Salah"]);
                }
            }else{
                return response(['result'=> 0 ,'message' => "User Tidak Ada"]);
            }
        }else{
            return response(['result'=> 0 ,'message' => "Password Salah atau Request Key Salah"]);
        }
    }

    public function forgotPasswordMobilePengawas(Request $request){
        $password_api = $request->password_api;
        $decrypted = $request->requestkey;
        $username = $request->username;
        
        for ($i=0; $i < 2 ; $i++) { 
            # code...
            $decrypted = Crypt::decryptString($decrypted);
        }

        $date = date("Y-m-d");
        $pass = "inipasswordMobilePengawas".$date;
        if($decrypted == $pass && $password_api == ""){
            $user = User::where("user_login", $username)->first();
            if($user != null){
                return response(['result'=> 1, 'message' => "OK", 'link' => 'xxxxx']);
            }else{
                return response(['result'=> 0 ,'message' => "User Tidak Ada"]);
            }
        }else{
            return response(['result'=> 0 ,'message' => "Password Salah atau Request Key Salah"]);
        }
    }

    public function changePasswordMobilePengawas(Request $request){
        $password_api = $request->password_api;
        $decrypted = $request->requestkey;
        $username = $request->username;
        $old_password = $request->old_password;
        $new_password = $request->new_password;

        for ($i=0; $i < 2 ; $i++) { 
            # code...
            $decrypted = Crypt::decryptString($decrypted);
        }

        $date = date("Y-m-d");
        $pass = "inipasswordMobilePengawas".$date;
        if($decrypted == $pass && $password_api == ""){
            $user = User::where("user_login", $username)->first();
            if($user != null){
                // return Hash::check($password, $user->password_mobile, []);
                if(Hash::check($old_password, $user->password_mobile, []) == true){
                    $user_update = User::where("user_login", $username)->first();
                    $user_update->password_mobile = bcrypt($new_password);
                    $status = $user_update->save();
                    if($status == true){
                        return response(['result'=> 1, 'message' => "OK"]);
                    }else{
                        return response(['result'=> 0, 'message' => "Rubah Password Gagal"]);
                    }
                }else{
                    return response(['result'=> 0, 'message' => "Password Lama Salah"]);
                }
            }else{
                return response(['result'=> 0 ,'message' => "User Tidak Ada"]);
            }
        }else{
            return response(['result'=> 0 ,'message' => "Password API Salah atau Request Key Salah"]);
        }
    }

    public function viewProfileMobilePengawas(Request $request){
        $password_api = $request->password_api;
        $decrypted = $request->requestkey;
        $username = $request->username;
        $password = $request->password;

        for ($i=0; $i < 2 ; $i++) { 
            # code...
            $decrypted = Crypt::decryptString($decrypted);
        }

        $date = date("Y-m-d");
        $pass = "inipasswordMobilePengawas".$date;
        if($decrypted == $pass && $password_api == ""){
            $user = User::where("user_login", $username)->first();
            if($user != null){
                // return Hash::check($password, $user->password_mobile, []);
                if(Hash::check($password, $user->password_mobile, []) == true){
                    if ($user->name_file != null && $user->name_file != "") {
                        # code...
                        $path = url('/')."/assets/user/$user->id/$user->name_file";
                        $encode =  base64_encode(file_get_contents($path));
                    } else {
                        # code...
                        $encode = null;
                    }
                    
                    $data = [
                            'name' => $user->user_name,
                            'email'=> $user->user_login,
                            'email_real_untuk_kirim_email' => $user->email,
                            'photo_profile' => $encode,
                    ];
                    return response(['result'=> 1, 'message' => "OK", 'data' => $data ]);
                }else{
                    return response(['result'=> 0, 'message' => "Password Lama Salah"]);
                }
            }else{
                return response(['result'=> 0 ,'message' => "User Tidak Ada"]);
            }
        }else{
            return response(['result'=> 0 ,'message' => "Password API Salah atau Request Key Salah"]);
        }
    }

    public function changeProfilePictureMobilePengawas(Request $request){
        $password_api = $request->password_api;
        $decrypted = $request->requestkey;
        $username = $request->username;
        $password = $request->password;
        $photo_profile = $request->new_photo_profile;

        for ($i=0; $i < 2 ; $i++) { 
            # code...
            $decrypted = Crypt::decryptString($decrypted);
        }

        $date = date("Y-m-d");
        $pass = "inipasswordMobilePengawas".$date;
        if($decrypted == $pass && $password_api == ""){
            $user = User::where("user_login", $username)->first();
            if($user != null){
                // return Hash::check($password, $user->password_mobile, []);
                if(Hash::check($password, $user->password_mobile, []) == true){
                    if (!file_exists ("./assets/user/".$user->id )) {
                        mkdir("./assets/user/".$user->id, 0777, true);
                        chmod("./assets/user/".$user->id,0777);
                    }
                    $name_gambar = 'photo_profile.PNG';

                    $save_gambar = Storage::disk('public2')->put("/assets/user/".$user->id."/".$name_gambar, base64_decode($photo_profile));

                    if($save_gambar == true){
                        $user_update = User::where("user_login", $username)->first();
                        $user_update->filenames = 'public/assets/user/'.$user->id.'/'.$name_gambar;
                        $user_update->name_file = $name_gambar;
                        $status = $user_update->save();
                        if($status == true){
                            return response(['result'=> 1, 'message' => "OK"]);
                        }else{
                            return response(['result'=> 0, 'message' => "Simpan Path Gagal"]);
                        }
                    }else{
                        return response(['result'=> 0, 'message' => "Simpan Gambar Gagal"]);
                    }
                }else{
                    return response(['result'=> 0, 'message' => "Password Lama Salah"]);
                }
            }else{
                return response(['result'=> 0 ,'message' => "User Tidak Ada"]);
            }
        }else{
            return response(['result'=> 0 ,'message' => "Password API Salah atau Request Key Salah"]);
        }
    }

    public function listUnit(Request $request){
        $password_api = $request->password_api;
        $decrypted = $request->requestkey;
        $username = $request->username;
        $password = $request->password;
        $data = [];
        for ($i=0; $i < 2 ; $i++) { 
            # code...
            $decrypted = Crypt::decryptString($decrypted);
        }

        $date = date("Y-m-d");
        $pass = "inipasswordMobilePengawas".$date;
        if($decrypted == $pass && $password_api == ""){
            $user = User::where("user_login", $username)->first();
            if($user != null){
                // return Hash::check($password, $user->password_mobile, []);
                if(Hash::check($password, $user->password_mobile, []) == true){

                    $spk = Spk::where('pic_id', $user->id)->orderBy("id", "desc")->get();

                    foreach ($spk as $key => $value) {
                        # code...
                        foreach ($value->tender->units as $key2 => $value2) {
                            # code...
                            // $value->rab_unit->asset_id
                            if($value2->rab_unit->asset != null){
                                $arr = [
                                    'unit_id' => $value2->id,
                                    'nama_unit'=> $value2->rab_unit->asset->name,
                                    'type' => explode('\\', $value2->rab_unit->asset_type)[3] ,
                                    // 'photo_profile' => $encode,
                                ];
                                array_push($data, $arr);
                            }
                        }
                    }
                    return response()->json(['code'=> 1, 'message' => "OK", 'data' => $data ]);
                }else{
                    return response(['code'=> 422, 'message' => "The given data was invalids", 'timestamp' => date("Y-m-d h:m:s")]);
                }
            }else{
                return response(['code'=> 422 ,'message' => "The given data was invalids", 'timestamp' => date("Y-m-d h:m:s")]);
            }
        }else{
            return response(['code'=> 422 ,'message' => "The given data was invalids", 'timestamp' => date("Y-m-d h:m:s")]);
        }


    }

    public function detailPekerjaan(Request $request){
        $password_api = $request->password_api;
        $decrypted = $request->requestkey;
        $username = $request->username;
        $password = $request->password;
        $unit_id = $request->unit_id;
        $data = [];
        for ($i=0; $i < 2 ; $i++) { 
            # code...
            $decrypted = Crypt::decryptString($decrypted);
        }

        $date = date("Y-m-d");
        $pass = "inipasswordMobilePengawas".$date;
        if($decrypted == $pass && $password_api == ""){
            $user = User::where("user_login", $username)->first();
            if($user != null){
                // return Hash::check($password, $user->password_mobile, []);
                if(Hash::check($password, $user->password_mobile, []) == true){
                    $pekerjaan = UnitProgress::where("unit_id", $unit_id)->get();

                    foreach ($pekerjaan as $key => $value) {
                        # code...
                        $arr = [
                            'unit_id' => $unit_id,
                            'item_pekerjaan_id'=> $value->itempekerjaan_id,
                            'item_pekerjaaan_name' => $value->itempekerjaan->name ,
                            'volume' => $value->volume,
                            'satuan' => $value->satuan,
                            'tanggal_mulai' => $value->mulai_jadwal_date,
                            'tanggal_selesai' => $value->selesai_jadwal_date, 

                        ];
                        array_push($data, $arr);
                    }
                    
                    return response()->json(['code'=> 1, 'message' => "OK", 'data' => $data ]);
                }else{
                    return response(['code'=> 422, 'message' => "The given data was invalids", 'timestamp' => date("Y-m-d h:m:s")]);
                }
            }else{
                return response(['code'=> 422 ,'message' => "The given data was invalids", 'timestamp' => date("Y-m-d h:m:s")]);
            }
        }else{
            return response(['code'=> 422 ,'message' => "The given data was invalids", 'timestamp' => date("Y-m-d h:m:s")]);
        }
    }

    public function detailTahapan(Request $request){
        $password_api = $request->password_api;
        $decrypted = $request->requestkey;
        $username = $request->username;
        $password = $request->password;
        $unit_id = $request->unit_id;
        $itempekerjaan_id = $request->item_pekerjaan_id;
        $data = [];
        for ($i=0; $i < 2 ; $i++) { 
            # code...
            $decrypted = Crypt::decryptString($decrypted);
        }

        $date = date("Y-m-d");
        $pass = "inipasswordMobilePengawas".$date;
        if($decrypted == $pass && $password_api == ""){
            $user = User::where("user_login", $username)->first();
            if($user != null){
                // return Hash::check($password, $user->password_mobile, []);
                if(Hash::check($password, $user->password_mobile, []) == true){
                    $tahapan = ProgressTambahan::where("unit_id", $unit_id)->where('itempekerjaan_id', $itempekerjaan_id)->get();

                    foreach ($tahapan as $key => $value) {
                        # code...
                        $arr = [
                            'tahapan_id' => $value->id,
                            'unit_id' => $value->unit_id,
                            'item_pekerjaan_id'=> $value->itempekerjaan_id,
                            'name' => $value->name,
                            'volume' => (float)$value->volume,
                            'satuan' => $value->satuan,
                            'status' => $value->status, 

                        ];
                        array_push($data, $arr);
                    }
                    
                    return response()->json(['code'=> 1, 'message' => "OK", 'data' => $data ]);
                }else{
                    return response(['code'=> 422, 'message' => "The given data was invalids", 'timestamp' => date("Y-m-d h:m:s")]);
                }
            }else{
                return response(['code'=> 422 ,'message' => "The given data was invalids", 'timestamp' => date("Y-m-d h:m:s")]);
            }
        }else{
            return response(['code'=> 422 ,'message' => "The given data was invalids", 'timestamp' => date("Y-m-d h:m:s")]);
        }
    }

    public function detailIpk(Request $request){
        $password_api = $request->password_api;
        $decrypted = $request->requestkey;
        $username = $request->username;
        $password = $request->password;
        $unit_id = $request->unit_id;
        $itempekerjaan_id = $request->item_pekerjaan_id;
        $tahapan = $request->tahapan_id;
        $data = [];
        for ($i=0; $i < 2 ; $i++) { 
            # code...
            $decrypted = Crypt::decryptString($decrypted);
        }

        $date = date("Y-m-d");
        $pass = "inipasswordMobilePengawas".$date;
        if($decrypted == $pass && $password_api == ""){
            $user = User::where("user_login", $username)->first();
            if($user != null){
                // return Hash::check($password, $user->password_mobile, []);
                if(Hash::check($password, $user->password_mobile, []) == true){
                    $ipk = IpkProgressTahapan::where("progress_tambahan_id", $tahapan)->get();

                    foreach ($ipk as $key => $value) {
                        # code...s
                        if ($value->ipk != null) {
                            # code...
                            // $ipk_real = $ipk->ipk->where("itempekerjaan_id". $itempekerjaan_id)->where("unit_id", $unit_id)->first();
                            // if($ipk_real != null){
                                $arr = [
                                    'ipk_id' => $value->ipk->id,
                                    'tahapan_id' => $value->progress_tambahan_id,
                                    'unit_id' => $value->ipk->unit_id,
                                    'item_pekerjaan_id'=> $value->ipk->itempekerjaan_id,
                                    'name' => $value->ipk->name,
                                    'status' => $value->status_ceklis, 
        
                                ];
                                array_push($data, $arr);
                            // }
                        }

                    }
                    
                    return response()->json(['code'=> 1, 'message' => "OK", 'data' => $data ]);
                }else{
                    return response(['code'=> 422, 'message' => "The given data was invalids", 'timestamp' => date("Y-m-d h:m:s")]);
                }
            }else{
                return response(['code'=> 422 ,'message' => "The given data was invalids", 'timestamp' => date("Y-m-d h:m:s")]);
            }
        }else{
            return response(['code'=> 422 ,'message' => "The given data was invalids", 'timestamp' => date("Y-m-d h:m:s")]);
        }
    }

    public function ProgressLapangan(Request $request){
        $password_api = $request->password_api;
        $decrypted = $request->requestkey;
        $username = $request->username;
        // $password = $request->password;
        for ($i=0; $i < 2 ; $i++) { 
            # code...
            $decrypted = Crypt::decryptString($decrypted);
        }
        $data = [];
        $date = date("Y-m-d");
        $pass = "inipasswordMobilePengawas".$date;
        if($decrypted == $pass && $password_api == ""){
            $user = User::where("user_login", $username)->first();
            if($user != null){
                // return Hash::check($password, $user->password_mobile, []);
                // if(Hash::check($password, $user->password_mobile, []) == true){

                    $spk = Spk::where('pic_id', $user->id)->orderBy("id", "desc")->get();
                    $ListUnit = [];
                    $ListPekerjaan = [];
                    $ListTahapan = [];
                    $ListIpk = [];
                    foreach ($spk as $key => $value) {
                        # code...
                        foreach ($value->tender->units as $key2 => $value2) {
                            # code...
                            // $value->rab_unit->asset_id
                            if($value2->rab_unit->asset != null){
                                $arr = [
                                    'unit_id' => $value2->id,
                                    'nama_unit'=> $value2->rab_unit->asset->name,
                                    'type' => explode('\\', $value2->rab_unit->asset_type)[3] ,
                                    // 'photo_profile' => $encode,
                                ];
                                array_push($ListUnit, $arr);
                            }
                        }
                    }

                    // array_pust($data,$ListIpk);

                    for ($i=0; $i < count($ListUnit); $i++) { 
                        # code...
                        $pekerjaan = UnitProgress::where("unit_id", $ListUnit[$i]['unit_id'])->get();

                        foreach ($pekerjaan as $key => $value) {
                            # code...
                            if($value->itempekerjaan != null){
                                $name = $value->itempekerjaan->name;
                            }else{
                                $name = "-";
                            }
                            if($value->mulai_jadwal_date != null && $value->selesai_jadwal_date != null){
                                $mulai = $value->mulai_jadwal_date->format('Y-m-d');
                                $selesai =$value->selesai_jadwal_date->format('Y-m-d');
                            }else{
                                $mulai = null;
                                $selesai = null;
                            }
                            $arr = [
                                'unit_id' => $ListUnit[$i]['unit_id'],
                                'item_pekerjaan_id'=> $value->itempekerjaan_id,
                                'item_pekerjaaan_name' => $name,
                                'volume' => (double)$value->volume,
                                'satuan' => $value->satuan,
                                'tanggal_mulai' => $mulai,
                                'tanggal_selesai' => $selesai, 
    
                            ];
                            array_push($ListPekerjaan, $arr);
                        }
                    }

                    for ($i=0; $i < count($ListUnit); $i++) { 
                        # code...
                        $pekerjaan = UnitProgress::where("unit_id", $ListUnit[$i]['unit_id'])->get();

                        foreach ($pekerjaan as $key => $value) {
                            # code...
                            $tahapan = ProgressTambahan::where("unit_id", $ListUnit[$i]['unit_id'])->where('itempekerjaan_id', $value->itempekerjaan_id)->get();

                            foreach ($tahapan as $key => $value) {
                                # code...
                                $arr = [
                                    'tahapan_id' => $value->id,
                                    'unit_id' => $value->unit_id,
                                    'item_pekerjaan_id'=> $value->itempekerjaan_id,
                                    'name' => $value->name,
                                    'volume' => (float)$value->volume,
                                    'satuan' => $value->satuan,
                                    'status' => $value->status, 
    
                                ];
                                array_push($ListTahapan, $arr);
                            }
                        }
                    }


                    for ($i=0; $i < count($ListTahapan); $i++) { 
                        # code...
                        $ipk = IpkProgressTahapan::where("progress_tambahan_id", $ListTahapan[$i]['tahapan_id'])->get();

                        foreach ($ipk as $key => $value) {
                            # code...s
                            if ($value->ipk != null) {
                                # code...
                                // $ipk_real = $ipk->ipk->where("itempekerjaan_id". $itempekerjaan_id)->where("unit_id", $unit_id)->first();
                                // if($ipk_real != null){
                                    $arr = [
                                        'ipk_id' => $value->ipk->id,
                                        'tahapan_id' => $value->progress_tambahan_id,
                                        'unit_id' => $value->ipk->unit_id,
                                        'item_pekerjaan_id'=> $value->ipk->itempekerjaan_id,
                                        'name' => $value->ipk->name,
                                        'status' => $value->status_ceklis, 
            
                                    ];
                                    array_push($ListIpk, $arr);
                                // }
                            }
    
                        }
                    }

                    // array_push($data, $ListUnit);
                    // array_push($data, $ListPekerjaan);
                    $data['listUnit'] = $ListUnit;
                    $data['listPekerjaan'] = $ListPekerjaan;
                    $data['listTahapan'] = $ListTahapan;
                    $data['listIpk'] = $ListIpk;
                    return response()->json(['code'=> 1, 'message' => "OK", 'data' => $data ]);
                // }else{
                //     return response(['code'=> 422, 'message' => "The given data was invalids", 'timestamp' => date("Y-m-d h:m:s")]);
                // }
            }else{
                return response(['code'=> 422 ,'message' => "The given data was invalids", 'timestamp' => date("Y-m-d h:m:s")]);
            }
        }else{
            return response(['code'=> 422 ,'message' => "The given data was invalids", 'timestamp' => date("Y-m-d h:m:s")]);
        }


    }

}