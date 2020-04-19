<?php

namespace Modules\Rekanan\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Rekanan\Entities\Rekanan;
use Modules\Rekanan\Entities\RekananGroup;
use Modules\Rekanan\Entities\RekananSpecification;
use Modules\Country\Entities\City;
use Modules\Pekerjaan\Entities\Itempekerjaan;
use Modules\Rekanan\Entities\UserRekanan;
use Modules\User\Entities\User;
use Modules\Rekanan\Entities\StatusPerusahaan;
use Modules\Rekanan\Entities\PphRekanan;

class RekananController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $user = \Auth::user();
        $rekanan_group = RekananGroup::get();
        return view('rekanan::index',compact("user","rekanan_group"));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create(Request $request)
    {
        $user = \Auth::user();
        return view('rekanan::create',compact("user"));
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $rekanan = new RekananGroup;
        $rekanan->npwp_no = $request->npwp_no;
        $rekanan->coa_ppn = 0;
        $rekanan->save();
        $status = "0";
        $url = "/rekanan/detail?id=".$rekanan->id;

        if (!file_exists ("./assets/rekanan/".$rekanan->id )) {
            mkdir("./assets/rekanan/".$rekanan->id);
            chmod("./assets/rekanan/".$rekanan->id,0755);
        }


        return response()->json( ["status" => $status, "url" => $url] );
        //return redirect("rekanan/detail?id=".$rekanan->id);
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show(Request $request)
    {
        $user = \Auth::user();
        $rekanan_group = RekananGroup::find($request->id);
        $itempekerjaan = Itempekerjaan::get();
        $city = City::get();
        $email = "";
        if ( count($rekanan_group->rekanans) != 0 ){
                $email = $rekanan_group->rekanans->first()->email;
                // User::where("user_login",$request->name)->first();
                if($rekanan_user_id = $rekanan_group->user_rekanan != null){
                    $rekanan_user_id = $rekanan_group->user_rekanan->user_login;
                }else{
                    $rekanan_user_id = 0;
                }
        }
        
        // return $rekanan_group->user_rekanan->user_login;
        $status_perusahaan = StatusPerusahaan::get();
        $pph_rekanan = PphRekanan::get();
        return view('rekanan::show',compact("user","rekanan_group","city","itempekerjaan","email","rekanan_user_id","status_perusahaan","pph_rekanan"));
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit()
    {
        return view('rekanan::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request)
    {
        //Pkp Status
        $arraypkp = array(
            "pribadi" => "2",
            "pkp/perusahaan" => "1"
        );


        $pph_rekanan = PphRekanan::find($request->pph);

        $rekanan_group = RekananGroup::find($request->rekanan_group_id);
        $rekanan_group->pph_percent = $pph_rekanan->nilai;
        $rekanan_group->name = $request->name;
        $rekanan_group->npwp_alamat = $request->alamat;
        $rekanan_group->npwp_kota = $request->kota;
        $rekanan_group->ktp_no = $request->ktp;
        $rekanan_group->status_perusahaan_id = $request->status_perusahaan;
        $rekanan_group->pph_rekanan_id = $pph_rekanan->id;
        if($rekanan_group->npwp_no == null){
            $rekanan_group->npwp_no = $request->npwp;
        }
        if ( $_FILES['sertifikat']['name'] == "" ){
            $rekanan_group->npwp_image = $request->images;
        }else{
            $rekanan_group->npwp_image = $_FILES['sertifikat']['name'];
        }

        if ( $request->pkp == null ){
            $rekanan_group->coa_ppn = 2;
            $rekanan_group->pkp_status = 2;
        }else{
            $rekanan_group->coa_ppn = 1;
            $rekanan_group->pkp_status = 1;
        }

        $rekanan_group->save();

        if ( count($rekanan_group->rekanans) <= 0 ){            
            $rekanan_child = new Rekanan;
            $rekanan_child->rekanan_group_id = $request->rekanan_group_id;
            $rekanan_child->name = $request->name;
            $rekanan_child->surat_alamat = $request->alamat;
            $rekanan_child->surat_kota = $request->kota;
            $rekanan_child->email = $request->email;
            $rekanan_child->ppn = 10;
            $rekanan_child->save();
        }elseif ( count($rekanan_group->rekanans) == 1 ){
            foreach ($rekanan_group->rekanans as $key2 => $value2) {
                $rekanan_child = Rekanan::find($value2->id);
                $rekanan_child->name = $request->name;
                $rekanan_child->surat_alamat = $request->alamat;
                $rekanan_child->surat_kota = $request->kota;
                $rekanan_child->email = $request->email;
                $rekanan_child->ppn = 10;
                $rekanan_child->save();
            }
        }

        if (!file_exists ("./assets/rekanan/".$request->rekanan_group_id )) {
            mkdir("./assets/rekanan/".$request->rekanan_group_id);
            chmod("./assets/rekanan/".$request->rekanan_group_id,0755);
        }

        $target_file = "./assets/rekanan/".$request->rekanan_group_id."/".$_FILES['sertifikat']['name'];
                move_uploaded_file($_FILES["sertifikat"]["tmp_name"], $target_file);
                
        return redirect("/rekanan/detail?id=".$request->rekanan_group_id);
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy()
    {
    }

    public function ceknpwp(Request $request){

        $cek_npwp = RekananGroup::where("npwp_no",$request->npwp_no)->get();
        if ( count($cek_npwp) > 0 ){
            $status = "1";
            $nama = $cek_npwp->first()->name;
        }else{
            $status = "0";
            $nama = "";
        }
        return response()->json( ["status" => $status, "nama" => $nama] );
    }

    public function spesifikasi(Request $request){
        $spesifikasi = new RekananSpecification;
        $spesifikasi->rekanan_group_id = $request->rekanan_group_id;
        $spesifikasi->itempekerjaan_id = $request->itempekerjaan;
        $spesifikasi->save();
        return redirect("/rekanan/detail?id=".$request->rekanan_group_id);  
    }

    public function deletespesifikasi(Request $request){
        $spesifikasi = RekananSpecification::find($request->id);
        $status = $spesifikasi->delete();
        if ( $status  ){
            return response()->json( ["status" => 0] );
        }else{
            return response()->json( ["status" => 1] );
        }
    }

    public function blacklist(Request $request){
        $rekanan_group = RekananGroup::find($request->id);
        if ( $request->status == "1"){
            $rekanan_group->inactive_at = date("Y-m-d H:i:s.u");
            $rekanan_group->inactive_by = \Auth::user()->id;
        }else{
            $rekanan_group->updated_at = date("Y-m-d H:i:s.u");
            $rekanan_group->updated_by = \Auth::user()->id;
            $rekanan_group->inactive_at = null;
        }
        $rekanan_group->save();
        return response()->json( ["status" => 0] );
    }

    public function useradd(Request $request){
        $user_rekanan = UserRekanan::where("user_login",$request->name)->first();
        if ($user_rekanan != null) {
            # code...
            $user = User::where("user_login", $user_rekanan->user_login)->first();
        } else {
            # code...
            $user = null;
        }
        
        if($user_rekanan == null){
            $rekanan_group_user = new UserRekanan;
            $rekanan_group_user->user_login = $request->name;
            $rekanan_group_user->user_name = $request->name;
            $rekanan_group_user->rekanan_group_id = $request->rekanan_group_id;
            $rekanan_group_user->is_rekanan = 1;
            $rekanan_group_user->password = bcrypt($request->password);
            $rekanan_group_user->save();

            $rekanan_user = new User;
            $rekanan_user->user_login = $request->name;
            $rekanan_user->user_name = $request->name;
            $rekanan_user->is_rekanan = 1;
            $rekanan_user->password = bcrypt($request->password);
            $rekanan_user->email = $request->email_2;
            $rekanan_user->save();
        }elseif($user == null){
            $rekanan_user = new User;
            $rekanan_user->user_login = $request->name;
            $rekanan_user->user_name = $request->name;
            $rekanan_user->is_rekanan = 1;
            $rekanan_user->password = bcrypt($request->password);
            $rekanan_user->email = $request->email_2;
            $rekanan_user->save();
        }elseif($user != null){
            $rekanan_user = User::find($user->id);
            $rekanan_user->user_login = $request->name;
            $rekanan_user->user_name = $request->name;
            $rekanan_user->is_rekanan = 1;
            $rekanan_user->password = bcrypt($request->password);
            $rekanan_user->email = $request->email_2;
            $rekanan_user->save();
        }
        return redirect("/rekanan/detail?id=".$request->rekanan_group_id);  
    }

    public function userupdate(Request $request){
        $rekanan_group_user = UserRekanan::find($request->user_rekanan_group_id);
        $rekanan_group_user->user_login = $request->name;
        $rekanan_group_user->user_name = $request->name;
        $rekanan_group_user->rekanan_group_id = $request->rekanan_group_id;
        $rekanan_group_user->is_rekanan = 1;
        $rekanan_group_user->password = bcrypt($request->password);
        $rekanan_group_user->save();

        // return $request->rekanan_user_id;
        $validasi_rekanan_user = User::where("user_login",$request->rekanan_user_id)->first();
        if ( $validasi_rekanan_user != null ){
            $rekanan_user = User::where("user_login",$request->name)->first();
        }else{
            $rekanan_user = new User;
            
        }
        // return $rekanan_user;
        $rekanan_user->user_login = $request->name;
        $rekanan_user->user_name = $request->name;
        $rekanan_user->is_rekanan = 1;
        $rekanan_user->password = bcrypt($request->password);
        $rekanan_user->email = $request->email_2;
        $rekanan_user->save();
        return redirect("/rekanan/detail?id=".$request->rekanan_group_id);  
    }

    public function usulan(Request $request){
        $rekanan_group = RekananGroup::get();
        $user = \Auth::user();
        return view("rekanan::usulan",compact("rekanan_group","user"));
    }

    public function saveusulan(Request $request){
        if ( isset($request->status_)){
            foreach ($request->status_ as $key => $value) {
                echo $request->status_[$key];
                /*$rekanan = Rekanan::find($request->status_[$key]);
                $rekanan->gabung_date = date("Y-m-d H:i:s.u");
                $rekanan->save();*/
            }
        }
        $rekanan_group = RekananGroup::get();
        $user = \Auth::user();
        return redirect("/rekanan/usulan");
    }

    public function create_folder(Request $request)
    {
        $rekanan = RekananGroup::all();
        // return $rekanan;

        foreach ($rekanan as $key => $value) {
            # code...
            if (!file_exists ("./assets/rekanan/".$value->id )) {
                mkdir("./assets/rekanan/".$value->id);
                chmod("./assets/rekanan/".$value->id,0755);
            }
        }
        

        return "sukses";
        //return redirect("rekanan/detail?id=".$rekanan->id);
    }

    public function redirectLogin(Request $request){
        $user = User::where('user_login',$request->user_login)->first();
        \Auth::login($user);
        return redirect("/");
    }
    
}
