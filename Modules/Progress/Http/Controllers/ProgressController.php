<?php

namespace Modules\Progress\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Project\Entities\Project;
use Modules\Project\Entities\UnitProgress;
use Modules\Spk\Entities\Spk;
use Modules\Spk\Entities\SpkvoUnit;
use Modules\Spk\Entities\SpkDetail;
use Modules\Spk\Entities\SpkTermyn;
use Modules\Spk\Entities\SpkTermynDetail;
use Modules\Project\Entities\UnitProgressDetail;
use Modules\Tender\Entities\TenderUnit;
use Modules\User\Entities\User;
use Modules\Project\Entities\UnitProgressDetailPicture;
use Storage;
use Modules\Pekerjaan\Entities\Itempekerjaan;
use Modules\Spk\Entities\IpkTambahan;
use Modules\Spk\Entities\ProgressTambahan;
use Modules\Progress\Entities\Siks;
use Modules\Progress\Entities\SikDetail;
use Modules\Approval\Entities\Approval;
use Modules\Approval\Entities\ApprovalHistory;
use Modules\Spk\Entities\NewVo;
use Modules\Spk\Entities\DetailVo;
use Modules\Spk\Entities\ProgressTambahanVo;
use Modules\Spk\Entities\SpkPercepatan;
use Modules\Spk\Entities\RekananPengajuanIpk;
use Modules\Spk\Entities\IpkProgressTahapan;
use Modules\Spk\Entities\SikUnit;
use Illuminate\Support\Facades\Crypt;
use Modules\Progress\Entities\SikSubDetail;
use Modules\Voucher\Entities\Voucher;
use Modules\Globalsetting\Entities\Globalsetting;


class ProgressController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = User::find(\Auth::user()->id);
        $spk = Spk::where("pic_id",$user->id)->orderBy("id","desc")->get();
        return view('progress::index',compact("user","spk"));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create(Request $request)
    {
        $unitprogress = UnitProgress::where("unit_id",$request->id)->get();
        $unitprogress_desc_date = UnitProgress::where("unit_id",$request->id)->where('mulai_jadwal_date','!=',null)->orderBy('mulai_jadwal_date','ASC')->first();
        if( $unitprogress_desc_date != null){
            $spk_update = Spk::find($request->spk);
            $diff = abs(strtotime($spk_update->finish_date) - strtotime($spk_update->start_date));
            $days = floor(($diff)/ (60*60*24));
            $spk_update->start_date_real = $unitprogress_desc_date->mulai_jadwal_date;
            $spk_update->finish_date_real = date('Y-m-d', strtotime('+'.$days.' day', strtotime($unitprogress_desc_date->mulai_jadwal_date)));
            $spk_update->save();
        }
        $user = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        $unit = TenderUnit::find($request->id);
        $spk = Spk::find($request->spk);
        $arrayEscrow = array(
            "1" => array("label" => "Escrow : Pondasi", "style" => "background-color:grey;color:white;font-weight:bolder"),
            "2" => array("label" => "Escrow : Atap", "style" => "background-color:#d58512;color:white;font-weight:bolder"),
            ""  => array("label" => "", "style" => "")
        );
        $id_unit = $request->id;
        $vo = NewVo::where('spk_id',$spk->id)->get();
        // return $unitprogress;
        $vo_pengurangan = $spk->new_vo->where("tipe",1);
        $nilaitot_kurang = 0;
        $total_nilaitot_kurang = 0;
        foreach ($vo_pengurangan as $key => $value) {
            # code...
            if($value->approval->approval_action_id == 6){
                foreach ($value->detail->where("unit_id",$request->id) as $key2 => $value2) {
                    # code...
                    if($value2->volume <= 0){
                        $nilaitot_kurang +=  $value2->volume * $value2->nilai;
                        $total_nilaitot_kurang +=  $value2->total_nilai;
                    }
                }
            }
        }
        return view('progress::create_progress',compact("user","unitprogress","unit","project","spk","arrayEscrow",'vo','id_unit','nilaitot_kurang','total_nilaitot_kurang'));
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show(Request $request)
    {
        $user = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        $spk = Spk::find($request->id);
        $start_date = date_create($spk->start_date);
        $end_date = date_create($spk->finish_date);
        $interval = date_diff($start_date,$end_date);
        $hari = ( 30 * $interval->m ) + $interval->d;
        $minggu = ceil($hari / 7);
        $nilai = 0;
        $total_nilai = 0;
        $main_nilai = 0;
        $real_bobot_s = 0;
        $real_bobot = 0;
        $main_percent = 0;
        $real_bobot_spk = 0;
        $volume_item_vo = 0;
        $unit = [];
        $nilai_per_unit = [];
        $total_nilai_per_unit = [];
        $vo_pengurangan = $spk->new_vo->where("tipe",1);

        foreach ($spk->tender->units as $key => $value1) {
            # code...
            $nilai_unit = 0;
            $total_nilai_unit = 0;
            foreach ($value1->unit_progress as $key => $value2) {
                if($value2->volume != 0){

                    $vo = NewVo::where("spk_id",$spk->id)->where('tipe',1)->get();
                    $volume_item_vo = 0;
                    $total_item_vo = 0;
                    foreach ($vo as $key => $value3) {
                        # code...
                        if($value3->approval->approval_action_id == 6){
                            $volume_item_vo += $value3->detail->where("unit_id",$value2->unit_id)->where("itempekerjaan_id",$value2->itempekerjaan_id)->where("volume",">", 0)->sum("volume");

                            $total_item_vo += $value3->detail->where("unit_id",$value2->unit_id)->where("itempekerjaan_id",$value2->itempekerjaan_id)->where("volume",">", 0)->sum("total_nilai");
                        }
                    } 
                    // printf($total_item_vo);
                    // echo("<br>");
                    $nilai += (($value2->volume + $volume_item_vo) * $value2->nilai);
                    $total_nilai += ($value2->total_nilai + $total_item_vo);
                    $nilai_unit += (($value2->volume + $volume_item_vo) * $value2->nilai);
                    $total_nilai_unit += ($value2->total_nilai + $total_item_vo);
                }
            }
            // return $total_nilai;

            $kurang = 0;
            $total_kurang = 0;
            foreach ($vo_pengurangan as $key => $value) {
                # code...
                if($value->approval->approval_action_id == 6){
                    $pengurangan = $value->detail->where('unit_id',$value1->id);
                    foreach ($pengurangan as $key2 => $value2) {
                        # code...
                        if($value2->volume <= 0){
                            $kurang += $value2->volume * $value2->nilai;
                            $total_kurang += $value2->total_nilai;
                        }
                    }
                }
            }
            // printf($total_kurang);
            // echo("<br>");
                // return $kurang;
            $nilai_per_unit[$value1->id] = $nilai_unit - $kurang;
            $total_nilai_per_unit[$value1->id] = $total_nilai_unit - abs($total_kurang);
        }
        // return $total_nilai_per_unit;

        $total_nilaitot_kurang = 0;
        $nilaitot_kurang  = 0;
        foreach ($vo_pengurangan as $key => $value) {
            # code...
            if($value->approval->approval_action_id == 6){
                foreach ($value->detail as $key2 => $value2) {
                    # code...
                    if($value2->volume <= 0){
                        $nilaitot_kurang +=  $value2->volume * $value2->nilai;
                        $total_nilaitot_kurang += $value2->total_nilai;
                    }
                }
            }
        }

        // return $total_nilaitot_kurang;
        //No VO
        foreach ($spk->tender->units as $key => $value1) {
            # code...
            $percent_item_vo = 0;
            $volume_item_vo = 0;
            $real_bobot = 0;
            foreach ($value1->unit_progress as $key => $value2) {
                if ( $value2->spkvo_unit != ""){
                    if ( $value2->spkvo_unit->head_type == "Modules\Spk\Entities\Spk" ){
                        if($value2->volume != 0){
                            $volume_item_vo = 0;
                            $total_item_v = 0;
                            $total_item_vo = 0;
                            foreach ($vo as $key => $value3) {
                                # code...
                                if($value3->approval->approval_action_id == 6){
                                    $volume_item_vo = $volume_item_vo + $value3->detail->where("unit_id",$value2->unit_id)->where("itempekerjaan_id",$value2->itempekerjaan_id)->where("volume",">", 0)->sum("volume");

                                    $total_item_vo += $value3->detail->where("unit_id",$value2->unit_id)->where("itempekerjaan_id",$value2->itempekerjaan_id)->where("volume",">", 0)->sum("total_nilai");
                                }
                            }
                            // return $total_item_vo ;
                            $percent_item_vo = 0;
                            foreach ($vo as $key => $value3) {
                                # code...
                                if($value3->approval->approval_action_id == 6){
                                    $item_vo =  $value3->detail->where("unit_id",$value2->unit_id)->where("itempekerjaan_id",$value2->itempekerjaan_id)->where("volume",">", 0)->first();
                                    // printf($item_vo);
                                    if(isset($item_vo)){
                                        $percent_item_vo =  $percent_item_vo + (($item_vo->volume*($item_vo->progresslapangan_percent/100))/($value2->volume + $volume_item_vo)*100);
                                    }
                                }
                            }
                            // return  $percent_item_vo;
                            
                            $jml_vo = count($vo);

                            $percent_saat_ini = $percent_item_vo + (($value2->volume*($value2->progresslapangan_percent/100))/($value2->volume + $volume_item_vo)*100);
                            // return $percent_saat_ini;
                            $kurang = 0;
                            $total_kurang = 0;
                            foreach ($vo_pengurangan as $key => $value) {
                                # code...
                                if($value->approval->approval_action_id == 6){
                                    $pengurangan = $value->detail->where('itempekerjaan_id', $value2->itempekerjaan_id)->where('unit_id',$value2->unit_id)->where("volume","<", 0)->first();
                    
                                    $kurang += $pengurangan['volume'];
                                    $total_kurang += $pengurangan['total_nilai'];
                                }
                            }
                            // return $total_kurang;
                            $main_nilai = (($value2->total_nilai - $total_kurang) + $total_item_vo) + $main_nilai;
                            // $real_bobot_s =  (( ( $percent_saat_ini ) * 100 ) / 100 ) * ( ((($value2->volume - $kurang) + $volume_item_vo) * $value2->nilai)/(($nilai-$nilaitot_kurang)/count($spk->tender->units)) *100 ) ;
                            // printf($nilai_per_unit[$value1->id]);
                            // echo("<br/>");
                            if($total_nilai_per_unit[$value1->id] !=null){
                                // printf(($value2->total_nilai - abs($total_kurang)) + $total_item_vo);
                                // echo("<br/>");
                                $real_bobot_s =  (( ( $percent_saat_ini ) * 100 ) / 100 ) * ( ($value2->total_nilai - abs($total_kurang)) + $total_item_vo) /(($total_nilai_per_unit[$value1->id])) *100 ;
                            }
                            // printf($real_bobot_s);
                            // echo("<br/>");
                            $real_bobot = $real_bobot + $real_bobot_s;  
                            
                            // return $real_bobot;
                        }         
                    }
                }
            }
            // printf($real_bobot);
            // echo("<br/>");
            if($value1->rab_unit->asset != ''){
                $name = $value1->rab_unit->asset->name;
            }else{
                $name = "Fasilitas Kota";
            }
            $arr = [
                'id' => $value1->id,
                'name' => $name,
                'progress' => $real_bobot,
            ]; 
            array_push($unit, $arr);
            $real_bobot_spk = $real_bobot_spk + $real_bobot;
        }
        $real_bobot_spk = $real_bobot_spk/count($spk->tender->units);

        // return $unit[0]["name"];
        return view('progress::detail',compact("user","spk","project","minggu","real_bobot","real_bobot_spk","unit"));
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit(Request $request)
    {
        $spk = Spk::find($request->spk_id);
        foreach ($spk->details as $key => $value) {
            
            foreach ($value->details_with_vo as $key2 => $value2) {
                $percentage = 0;
                $check = \Modules\Project\Entities\UnitProgressDetail::where("unit_progress_id",$value2->unit_progress_id)->get();
                foreach ($check as $key3 => $value3) {
                    $percentage = $value3->progress_percent + $percentage;
                }
                $unit_progress = UnitProgress::find($value2->unit_progress_id);
                $unit_progress->progresslapangan_percent = $percentage;
                $unit_progress->save();
                
            }
        }

        foreach ($spk->termyn as $key => $value) {
            if ( $value->termin == $request->termin ){
                $spktermyn = SpkTermyn::find($value->id);
                $spktermyn->status = "2";
                $spktermyn->save();
            }

            if ( ($request->termin + 1 ) == $value->termin ){
                $spktermyn = SpkTermyn::find($value->id);
                $spktermyn->status = "1";
                $spktermyn->save();
            }
        }
        return response()->json( ["status" => "0"] );
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request)
    {
        $user = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        $spk = Spk::find($request->id);
        $start_date = date_create($spk->start_date);
        $end_date = date_create($spk->finish_date);
        $interval = date_diff($start_date,$end_date);
        $hari = ( 30 * $interval->m ) + $interval->d;
        $minggu = ceil($hari / 7);
        $termin = $spk->termyn ; 
        $termin_ke = "";
        $termin_id = "";
        foreach ($termin as $key => $value) {
            if ( $value->status == "1"){
                $termin_id = $value->id;
                $termin_ke = $value->termin;
            }
        }
        return view('progress::progress',compact("user","project","spk","minggu","termin_id","termin_ke"));
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy()
    {
    }

    public function saveprogress(Request $request){
        
        $user = \Auth::user();
        $UnitProgress = UnitProgress::find($request->unit_progress_id);
        if ( $request->percent >= 100 ){
            $UnitProgress->selesai_actual_date = date("Y-m-d");
        }
        $UnitProgress->progresslapangan_percent = str_replace(",", "", $request->percent) / 100  ;
        $UnitProgress->save();

        $UnitProgressDetail = new UnitProgressDetail;
        $UnitProgressDetail->unit_progress_id = $request->unit_progress_id;
        $UnitProgressDetail->progress_date = date("Y-m-d");
        $UnitProgressDetail->progress_percent = str_replace(",", "", $request->percent) / 100 ;
        $UnitProgressDetail->pic_rekanan = $UnitProgress->unit->tender->spks->first()->rekanan_id;
        $UnitProgressDetail->pic_internal = $user->id;
        $UnitProgressDetail->setuju_rekanan_at = date("Y-m-d");
        $UnitProgressDetail->setuju_internal_at = date("Y-m-d");
        $UnitProgressDetail->description = $request->description;
        $UnitProgressDetail->save(); 

        // foreach ($request->file_images as $key => $value) {
        //     if ( $request->file("file_images")[$key] != "" ){ 
        //         $target_file = "./assets/spk/".$UnitProgress->unit->tender->spks->first()->id."/progress/".$request->file("file_images")[$key]->getClientOriginalName();
        //         move_uploaded_file($request->file("file_images")[$key], $target_file);
        //         $lastid = UnitProgressDetailPicture::get()->count() + 1;
        //         $UnitProgressDetailPicture = new UnitProgressDetailPicture;
        //         $UnitProgressDetailPicture->unit_progress_detail_id = $UnitProgressDetail->id;
        //         $UnitProgressDetailPicture->id = $lastid;
        //         $UnitProgressDetailPicture->picture = $request->file("file_images")[$key]->getClientOriginalName();
        //         $UnitProgressDetailPicture->save();
        //     }
        // }                   
        
        return redirect("/progress/tambah/?id=".$request->unit_progress_id);      
    }

    public function saveschedule(Request $request){
        if ( strtotime($request->start_date) != "" && strtotime($request->end_date) != "" ){          
            // echo strtotime($request->start_date)."<>".strtotime($request->end_date) ;
            $unitprogress = UnitProgress::find($request->id);
            $unitprogress->mulai_jadwal_date = date('Y-m-d',strtotime($request->start_date));
            $unitprogress->selesai_jadwal_date = date('Y-m-d',strtotime($request->end_date));
            $unitprogress->save();
            // echo "<br/>";
        }

        return response()->json(["status" => 1 ]);
    }

    public function tambah(Request $request){
        $unit_progress = UnitProgress::find($request->id);
        $user = User::find(\Auth::user()->id);
        return view("progress::detail_pekerjaan",compact("user","unit_progress"));
    }

    public function photo(Request $request){
        $unit_detail = UnitProgressDetail::find($request->id);
        $user = User::find(\Auth::user()->id);
        return view("progress::photo",compact("user","unit_detail"));

    }

    public function updateipk(Request $request){
        $user = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        $spk = Spk::find($request->idspk);
        $idspk = $request->idspk;
        $iditem = $request->iditem;
        $sub = Itempekerjaan::find($request->iditem);
        $ipk_default = $sub->ipk;
        // $ipk_tambahan = $sub->ipkTambahan;
        $ipk_tambahan = IpkTambahan::where('itempekerjaan_id',$sub->id)->where('spk_id',$spk->id)->where('unit_id',$request->idunit)->distinct()->get();
        $id_unit = $request->idunit;

        return view('progress::update_ipk',compact("user","project",'spk','sub','ipk_tambahan','id_unit'));
    }

    public function updateprogress(Request $request){
        $user = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        $unitprogress = UnitProgress::where("unit_id",$request->idunit)->get();
        $spk = Spk::find($request->idspk);
        $idspk = $request->idspk;
        $iditem = $request->iditem;
        $sub = Itempekerjaan::find($request->iditem);
        $progress = ProgressTambahan::where('itempekerjaan_id',$iditem)->where('spk_id',$idspk)->where('unit_id',$request->idunit)->get();
        $volume_real = UnitProgress::where("unit_id",$request->idunit)->get()->where("volume",'!=',0)->where('itempekerjaan_id',$request->iditem)->first()->volume;
        $id_unit = $request->idunit;

        $vo_pengurangan = $spk->new_vo->where("tipe",1);
        $kurang = 0;
        foreach ($vo_pengurangan as $key => $value) {
            # code...
            if($value->approval->approval_action_id == 6){
                $pengurangan = $value->detail->where('itempekerjaan_id', $request->iditem)->where('unit_id',$request->idunit)->first();
                if($pengurangan['volume'] < 0){
                    $kurang += $pengurangan['volume'];
                }
            }
        }

        $volume = $volume_real - $kurang;
        // return $volume;
        $ipk = IpkTambahan::where("spk_id",$spk->id)->where("itempekerjaan_id",$sub->id)->get()->count();
        return view('progress::update_progress',compact("user","project",'spk','sub','progress','volume','id_unit','volume_real','ipk'));
    }

    public function simpanipk(Request $request){
        // $idipk = [];
        $idipk = $request->insert;
        $count = count($idipk);
        for($a=0; $a<$count ;$a++){
           $data = (int)$idipk[$a]['id'];
           $ipk = IpkTambahan::where('id',$data)->update(['status'=>1]);
        } 
        return response()->json(['success'=> 'Data Telah Diubah']);
    }

    public function simpanprogress(Request $request){
        // $idipk = [];
        $volume = $request->volume;
        $idprogress = $request->insert;
        $count = count($idprogress);
        $id_unit = $request->id_unit;
        for($a=0; $a<$count ;$a++){
           $data = (int)$idprogress[$a]['id'];
           $ipk = ProgressTambahan::where('id',$data)->update(['status'=>1]);
           
        } 
        $ipk = ProgressTambahan::where('id',$data)->first();

        $progress_sebelumnya = UnitProgress::where('unit_id',$id_unit)->where('itempekerjaan_id',$request->id_item)->first()->progresslapangan_percent;

        $totvolume = ProgressTambahan::where([
            ['itempekerjaan_id','=',$request->id_item],
            ['spk_id','=',$request->id_spk],
            ['unit_id','=',$request->id_unit],
            ['status','=',1]] )->sum('volume');

        $persentase = ($totvolume/$volume);

        $user = \Auth::user();
        // return $persentase;
        if ( 1 <=  $persentase ){
            // $UnitProgress->selesai_actual_date = date("Y-m-d");
            $persentase = 1;
        }
        if($persentase < 1){
            return "hola";
            $UnitProgress = UnitProgress::where('unit_id',$request->id_unit)
            ->where('itempekerjaan_id',$request->id_item)->update(['progresslapangan_percent'=>$persentase,'progress_sebelumnya'=>$progress_sebelumnya]);
        }else{
            return "hola";
            $UnitProgress = UnitProgress::where('unit_id',$request->id_unit)
            ->where('itempekerjaan_id',$request->id_item)->update(['progresslapangan_percent'=>$persentase,'progress_sebelumnya'=>$progress_sebelumnya,'selesai_actual_date'=>date("Y-m-d")]);
        }
        // $UnitProgress->progresslapangan_percent = $persentase / 100;
        // $UnitProgress->save();
        $unitProgress = UnitProgress::where('unit_id',$request->id_unit)->where('itempekerjaan_id',$request->id_item)->first();
        $UnitProgressDetail = new UnitProgressDetail;
        $UnitProgressDetail->unit_progress_id = $request->id_unit;
        $UnitProgressDetail->progress_date = date("Y-m-d");
        $UnitProgressDetail->progress_percent = $persentase ;
        $UnitProgressDetail->pic_rekanan = $unitProgress->unit->tender->spks->first()->rekanan_id;
        $UnitProgressDetail->pic_internal = $user->id;
        $UnitProgressDetail->setuju_rekanan_at = date("Y-m-d");
        $UnitProgressDetail->setuju_internal_at = date("Y-m-d");
        // $UnitProgressDetail->description = $request->description;   
        // $UnitProgressDetail->save();    

        $spk = $ipk->spk;
        // if($spk->lapangan == 100){
            if(count($spk->percepatan) != 0){
                if($spk->percepatan->status_percepatan != 0){
                    if(date('Y-m-d', strtotime($spk->percepatan->last()->tanggal_finish)) < date("Y-m-d") ){
                        $batal_percepatan = SpkPercepatan::find($spk->percepatan->last()->id);
                        $batal_percepatan->status_percepatan = 0;
                        // $batal_percepatan->save();
                    }
                }
            }
        // }
        return response()->json(['success'=> 'Data Telah Diubah']);
    }

    public function allsik(Request $request){
      $user = \Auth::user();
      $project = Project::find($request->session()->get('project_id'));
      $spk = Spk::find($request->idspk);
      $allsik = Siks::where("spk_id",$request->idspk)->get();
      $idspk = $request->idspk;

      return view('progress::sik',compact("user","project","spk","allsik","idspk"));  
    }

    public function sikbiaya(Request $request){
      $user = \Auth::user();
      $project = Project::find($request->session()->get('project_id'));
      $spk = Spk::find($request->idspk);
      $unitprogress = UnitProgress::where("unit_id",$request->idunit)->get();    

      return view('progress::sik_berbayar',compact("user","project","spk")); 
    }

    public function siknonbiaya(Request $request){
      $user = \Auth::user();
      $project = Project::find($request->session()->get('project_id'));
      $spk = Spk::find($request->idspk);
      $unitprogress = UnitProgress::where("unit_id",$request->idunit)->get();

      return view('progress::sik_nonbiaya',compact("user","project","spk")); 
    }

    public function insiknonbiaya(Request $request){
      // return 
      // $isian = htmlspecialchars($request->insert);
      $idspk = $request->idspk;
      $isian = $request->isian;
      $tgl = date("Y-m-d");
      $status = 2;
      $spk = Spk::find($idspk);
      $bap_no = $spk->no . '/SIK/' . str_pad( ($spk->sik()->count() + 1) , 2, "0", STR_PAD_LEFT);
    
      $project = Project::find($request->session()->get('project_id'));
      $sik = new Siks;
      $sik->no_sik = $bap_no;
      $sik->tgl_sik = $tgl;
      $sik->status_sik_id = $status;
      $sik->spk_id = $idspk;
      $sik->project_id = $spk->project->id;
      $sik->save();
      $idsik = $sik->id;

      $sikdetail = new SikDetail;
      // $sikdetail->itempekerjaan_id = ;
      $sikdetail->sik_id = $idsik;
      $sikdetail->keterangan = $isian;
      $sikdetail->save(); 

      return response()->json(['success'=> 'Data Berhasil Disimpan' ]);
    }

    public function detailsiknon(Request $request){
      $user = \Auth::user();
      $project = Project::find($request->session()->get('project_id'));
      $spk = Spk::find($request->id_spk);
      $idspk  = $request->id_spk;
      $id_sik = $request->idsik;
      $sik = Siks::find($request->idsik);

      return view('progress::edit_siknon',compact("user","project","spk","sik"));
    }

    public function updatesiknon(Request $request){
      $keterangan = $request->isian;
      SikDetail::where('id',$request->idsik)->update(['keterangan'=>$keterangan]);

      return response()->json(['success'=> 'Data Berhasil Diubah' ]);
    }

    public function inputsikbiaya(Request $request){
        // return $request; 
        $data = json_decode($request->data);
        // return $data;
        $id_spk = $request->spk_id;
        //   $id_item = $request->id_item;
        //   $volume = $request->vol;
        //   $satuan = $request->satuan;
        //   $keterangan = $request->ket;
        $tgl = date("Y-m-d");

        $spk = Spk::find($id_spk);
        $bap_no = $spk->no . '/SIK/' . str_pad( ($spk->sik()->count() + 1) , 2, "0", STR_PAD_LEFT);

        $spk_detail_item = Spk::find($id_spk)->tender_rekanan->menangs->first()->details->where("volume","!=",0);
        // return $spk;
        // for($a=0;$a<count($volume);$a++){
        //   return $id_item[$a].' '.$volume[$a].' '.$keterangan[$a];
        // }
        $project = Project::find($spk->project->id);

        $sik = new Siks;
        $sik->no_sik = $bap_no;
        $sik->tgl_sik = $tgl;
        $sik->status_sik_id = 1;
        $sik->spk_id = $id_spk;
        $sik->project_id = $project->id;
        $sik->save();

        $idsik = $sik->id;
        $i = 0;
        for ($i=0; $i < count($data); $i++) { 
            if($data[$i][3] != 0){
                $sikdetail = new SikDetail;
                $sikdetail->sik_id = $idsik;
                $sikdetail->itempekerjaan_id = $data[$i][0];
                $sikdetail->satuan = $data[$i][2];
                $sikdetail->volume = $data[$i][1];
                $sikdetail->volume_admin = $data[$i][1];
                $sikdetail->keterangan = null;
                $sikdetail->nilai = 0;
                $sikdetail->total_nilai = $data[$i][3];
                $sikdetail->save();

                for ($j=0; $j < count($data[$i][4]); $j++) {
                    $siksubdetail = new SikSubDetail;
                    $siksubdetail->sik_detail_id = $sikdetail->id;
                    $siksubdetail->name = $data[$i][4][$j][0];
                    $siksubdetail->satuan = $data[$i][4][$j][2];
                    $siksubdetail->volume = $data[$i][4][$j][1];
                    $siksubdetail->volume_admin = $data[$i][4][$j][1];
                    $siksubdetail->nilai = $data[$i][4][$j][3];
                    $siksubdetail->total_nilai = $data[$i][4][$j][5];
                    $siksubdetail->keterangan = $data[$i][4][$j][4];
                    $siksubdetail->save();
                }
            }
        }

        for ($i=0; $i < count($request->unit) ; $i++) { 
            # code...
            $sik_biaya = new SikUnit;
            $sik_biaya->sik_id = $idsik;
            $sik_biaya->unit_id = $request->unit[$i];
            $sik_biaya->save();
        }
        return response()->json( ["status" => "0"] );       
        // return redirect("/progress/sik?idspk=".$id_spk);
    }

    public function detailsikbiaya(Request $request){
      $user = \Auth::user();
      $project = Project::find($request->session()->get('project_id'));
      $spk = Spk::find($request->id_spk);
      $idspk  = $request->id_spk;
      $id_sik = $request->idsik;
      // $sik = $spk->sik;
      $sik = Siks::find($request->idsik);
      // echo $sik[0]->sik_detail;
      return view('progress::edit_sikbiaya',compact("user","project","spk","sik"));
    }

    public function updatesikbiaya(Request $request){
    //   $id_spk = $request->id_spk;
    //   $id_detail = $request->id_detail;
    //   $volume = $request->vol;
    //   $satuan = $request->satuan;
    //   $keterangan = $request->ket;

    //   $spk = Spk::find($id_spk)->tender_rekanan->menangs->first()->details;
      
    //   foreach ($spk as $key => $value) {
    //     SikDetail::where('id',$id_detail[$key])->update(['volume'=>$volume[$key],'keterangan'=>$keterangan[$key]]);
    //     // echo $idsik.' '.$id_item[$key].' '.$satuan[$key].' '.$volume[$key].' '.$keterangan[$key].' '.$value->nilai.'<br>';
    //   }

      $data = json_decode($request->data);
    //   return $data;
      for ($i=0; $i < count($data); $i++) { 
          if($data[$i][2] != 0){
              $sikdetail = SikDetail::find($data[$i][0]);
              $sikdetail->volume = $data[$i][1];
              $sikdetail->total_nilai = $data[$i][2];
              $sikdetail->save();

              for ($j=0; $j < count($data[$i][3]); $j++) {
                  $siksubdetail = SikSubDetail::find($data[$i][3][$j][0]);
                  $siksubdetail->volume = $data[$i][3][$j][1];
                  $siksubdetail->total_nilai = $data[$i][3][$j][3];
                  $siksubdetail->keterangan = $data[$i][3][$j][2];
                  $siksubdetail->save();
              }
          }
      }
      return response()->json( ["status" => "0"] );       
    //   return redirect("/progress/detailsikbiaya?idsik=".$request->idsik."&id_spk=".$id_spk);
    }

    public function requestapproval(Request $request){
      $id_sik = $request->idsik;
      $id_spk = $request->id_spk;
      $sik = Siks::where('id',$id_sik)->first();
      if($sik->status_sik_id==1 || $sik->status_sik_id==3){
        $approval = \App\Helpers\Document::make_approval('Modules\Progress\Entities\Siks',$sik->id);
        return redirect("/progress/detailsikbiaya?idsik=".$request->idsik."&id_spk=".$request->id_spk);
      }else{
        $approval = \App\Helpers\Document::make_approval('Modules\Progress\Entities\Siks',$sik->id);
        return redirect("/progress/detailsiknon?idsik=".$request->idsik."&id_spk=".$request->id_spk);
      }
    }

    public function saveschedulevo(Request $request){
        $id_unit = $request->id_unit;
        $idvod = $request->idvod;
        $start_date_vo = $request->start_date_vo_;
        $end_date_vo = $request->end_date_vo_;

        for($a=0;$a<count($start_date_vo);$a++){
          if ( strtotime($start_date_vo[$a]) != "" && strtotime($end_date_vo[$a]) != "" ){
               
            $detailvo = DetailVo::find($idvod[$a]);
           
            $detailvo->mulai_jadwal_date = $start_date_vo[$a];
            $detailvo->selesai_jadwal_date = $end_date_vo[$a];
            $detailvo->save();
          }
       }
    // }
     return redirect("/progress/create?id=".$id_unit."&spk=".$request->idspk);
    }

    public function updateprogressvo(Request $request){
        $user = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        $unitprogress = UnitProgress::where("unit_id",$request->idunit)->get();
        $spk = Spk::find($request->idspk);
        $idspk = $request->idspk;
        $iditem = $request->iditem;
        $sub = Itempekerjaan::find($request->iditem);
        // printf($iditem);
        // echo("<br/>");
        // printf($idspk);
        // echo("<br/>");
        // printf($request->idunit);
        // echo("<br/>");
        // printf($request->dvoid);
        // echo("<br/>");
        // return 0 ;
        $progressvo = ProgressTambahanVo::where('itempekerjaan_id',$iditem)->where('spk_id',$idspk)->where('unit_id',$request->idunit)->where("detail_vo_id",$request->dvoid)->get();

        $volume = DetailVo::find($request->dvoid)->volume;

        // if($progressvo == null){
        //     $volume = $spk->new_vo->where("id", $progressvo->detail_vo->vo_id)->first()->detail->where('itempekerjaan_id',$request->iditem)->first()->volume;
        // }else{
        //     $volume = DetailVo::find($request->dvoid)->volume;
        // }
        $id_unit = $request->idunit;
        $ipk = IpkTambahan::where("spk_id",$spk->id)->where("itempekerjaan_id",$sub->id)->get()->count();
        return view('progress::update_progress_vo',compact("user","project",'spk','sub','progressvo','volume','id_unit','ipk'));
    }

     public function simpanprogressvo(Request $request){
        // $idipk = [];
        $volume = $request->volume;
        $idprogress = $request->insert;
        $count = count($idprogress);
        $id_unit = $request->id_unit;
        for($a=0; $a<$count ;$a++){
           $data = (int)$idprogress[$a]['id'];
           $ipk = ProgressTambahanVo::where('id',$data)->update(['status'=>1]);
           
        } 

        $progress_sebelumnya = DetailVo::where('unit_id',$id_unit)->where('itempekerjaan_id',$request->id_item)->first()->progresslapangan_percent;

        $totvolume = ProgressTambahanVo::where([
            ['itempekerjaan_id','=',$request->id_item],
            ['spk_id','=',$request->id_spk],
            ['unit_id','=',$request->id_unit],
            ['status','=',1]] )->sum('volume');

        $persentase = ($totvolume/$volume);

        $user = \Auth::user();

        if ( 1 <=  $persentase ){
            $persentase = 1;
        }
        if($persentase < 1){
            $DetailVo = DetailVo::where('unit_id',$request->id_unit)
            ->where('itempekerjaan_id',$request->id_item)->update(['progresslapangan_percent'=>$persentase,'progress_sebelumnya'=>$progress_sebelumnya]);
        }else{
            $DetailVo = DetailVo::where('unit_id',$request->id_unit)
            ->where('itempekerjaan_id',$request->id_item)->update(['progresslapangan_percent'=>$persentase,'progress_sebelumnya'=>$progress_sebelumnya,'selesai_actual_date'=>date("Y-m-d")]);
        }

        // $UnitProgress->progresslapangan_percent = $persentase / 100;
        // $UnitProgress->save();
        $detailvo = DetailVo::where('unit_id',$request->id_unit)->where('itempekerjaan_id',$request->id_item)->first();
        $UnitProgressDetail = new UnitProgressDetail;
        $UnitProgressDetail->unit_progress_id = $request->id_unit;
        $UnitProgressDetail->progress_date = date("Y-m-d");
        $UnitProgressDetail->progress_percent = $persentase ;
        $UnitProgressDetail->pic_rekanan = $detailvo->unit->tender->spks->first()->rekanan_id;
        $UnitProgressDetail->pic_internal = $user->id;
        $UnitProgressDetail->setuju_rekanan_at = date("Y-m-d");
        $UnitProgressDetail->setuju_internal_at = date("Y-m-d");
        // $UnitProgressDetail->description = $request->description;   
        $UnitProgressDetail->save();    

        return response()->json(['success'=> 'Data Telah Diubah']);
    }

    public function index_pengajuan(Request $request)
    {
        $user = User::find(\Auth::user()->id);
        $pengajuan = RekananPengajuanIpk::where("pic_id",$user->id)->orderBy("id","Desc")->get();
        return view('progress::index_pengajuan',compact("user","pengajuan"));
    }

    public function detail_pengajuan(Request $request)
    {
        $id_pengajuan = Crypt::decryptString($request->id);
        $user = User::find(\Auth::user()->id);
        $pengajuan = RekananPengajuanIpk::find($id_pengajuan);
        return view('progress::detail_pengajuan',compact("user","pengajuan"));
    }

    public function persetujuan_pengajuan(Request $request)
    {
        $id_pengajuan = Crypt::decryptString($request->id);

        $pengajuan = RekananPengajuanIpk::find($id_pengajuan);
        $pengajuan->date_pengecekan_disetujui = $request->tglpengecekan;
        $pengajuan->description_disetujui = $request->keterangan_disetujui;
        $pengajuan->status_pengajuan = 1;
        $pengajuan->save();
        return redirect("/progress/pengajuan");    
    }

    public function progress_ipk(Request $request){
        $data = [];
        // return $request;
        $progress_ipk = IpkProgressTahapan::where("progress_tambahan_id", $request->id_progress)->where("tipe",$request->tipe)->get();
        foreach ($progress_ipk as $key => $value) {
            # code...
                if($value->status_ceklis == 0){
                    $status_name = "no";
                    $check = "<input type='checkbox' class='get_value flat-red check_ipk' name='' value=$value->id id='yes'><strong>Yes</strong>";
                }else{
                    $status_name = "yes";
                    $check = "";
                }
                $arr = [
                    "detail_ipk"    => $value->ipk->name,
                    "status"        => $value->status_ceklis,
                    "id"            => $value->id,
                    "status_name"   => $status_name,
                    "check" => $check,
                ];
                array_push($data, $arr);
        }
        
        return response()->json(['progress_ipk' => $data]);
    }

    public function simpan_ipk_progress(Request $request){
        // $idipk = [];
        $idipk = $request->insert;
        $count = count($idipk);
        for($a=0; $a<$count ;$a++){
           $data = (int)$idipk[$a]['id'];
           $ipk = IpkProgressTahapan::where('id',$data)->update(['status_ceklis'=>1]);
        } 
        return response()->json(['success'=> 'Data Telah Diubah']);
    }

    public function simpanprogress_pertahap(Request $request){
        if($request->tipe == "spk"){
            $volume = $request->volume;
            $progress = ProgressTambahan::where('id',$request->id_progress)->update(['status'=>1]);

            $progress = ProgressTambahan::where('id',$request->id_progress)->first();

            $progress_sebelumnya = UnitProgress::where('unit_id',$progress->unit_id)->where('itempekerjaan_id',$progress->itempekerjaan_id)->first()->progresslapangan_percent;

            $totvolume = ProgressTambahan::where([
                ['itempekerjaan_id','=',$progress->itempekerjaan_id],
                ['spk_id','=',$progress->spk_id],
                ['unit_id','=',$progress->unit_id],
                ['status','=',1]] )->sum('volume');

            $persentase = ($totvolume/$volume);

            $user = \Auth::user();
            
            if ( 1 <=  $persentase ){
                // $UnitProgress->selesai_actual_date = date("Y-m-d");
                $persentase = 1;
            }
            // $UnitProgress = UnitProgress::where('unit_id',$progress->unit_id)
            // ->where('itempekerjaan_id',$progress->itempekerjaan_id)->update(['progresslapangan_percent'=>$persentase,'progress_sebelumnya'=>$progress_sebelumnya]);

            // if ( 1 <=  $persentase ){
            //     // $UnitProgress->selesai_actual_date = date("Y-m-d");
            //     $persentase = 1;
            // }
            if($persentase < 1){
                $UnitProgress = UnitProgress::where('unit_id',$progress->unit_id)
                ->where('itempekerjaan_id',$progress->itempekerjaan_id)->update(['progresslapangan_percent'=>$persentase,'progress_sebelumnya'=>$progress_sebelumnya]);
            }else{
                $UnitProgress = UnitProgress::where('unit_id',$progress->unit_id)
                ->where('itempekerjaan_id',$progress->itempekerjaan_id)->update(['progresslapangan_percent'=>$persentase,'progress_sebelumnya'=>$progress_sebelumnya,'selesai_actual_date'=>date("Y-m-d")]);
            }
            // $UnitProgress->progresslapangan_percent = $persentase / 100;
            // $UnitProgress->save();
            $unitProgress = UnitProgress::where('unit_id',$progress->unit_id)->where('itempekerjaan_id',$progress->itempekerjaan_id)->first();
            $UnitProgressDetail = new UnitProgressDetail;
            $UnitProgressDetail->unit_progress_id = $progress->unit_id;
            $UnitProgressDetail->progress_date = date("Y-m-d");
            $UnitProgressDetail->progress_percent = $persentase ;
            $UnitProgressDetail->pic_rekanan = $unitProgress->unit->tender->spks->first()->rekanan_id;
            $UnitProgressDetail->pic_internal = $user->id;
            $UnitProgressDetail->setuju_rekanan_at = date("Y-m-d");
            $UnitProgressDetail->setuju_internal_at = date("Y-m-d");
            // $UnitProgressDetail->description = $request->description;   
            $UnitProgressDetail->save();    

            $spk = $progress->spk;
            // if($spk->lapangan == 100){
                // if(count($spk->percepatan) != 0){
                //     if($spk->percepatan->status_percepatan != 0){
                //         if(date('Y-m-d', strtotime($spk->percepatan->last()->tanggal_finish)) < date("Y-m-d") ){
                //             $batal_percepatan = SpkPercepatan::find($spk->percepatan->last()->id);
                //             $batal_percepatan->status_percepatan = 0;
                //             $batal_percepatan->save();
                //         }
                //     }
                // }

            $spk = Spk::find($progress->spk_id);
            if($spk->tender->aanwijing->jenis_pembayaran == null || $spk->tender->aanwijing->jenis_pembayaran == 1){
                if (count($spk->baps) != 0) {
                    # code...
                    if ($spk->baps[0]->percentage_lapangan == 0) {
                        # code...
                        if ($spk->baps[0]->vouchers != null) {
                            # code...
                            if ((($spk->baps[0]->percentage*1.05)+ Globalsetting::where("id",1005)->first()->value) <= $spk->lapangan) {
                                # code...
                                $voucher = Voucher::find($spk->baps[0]->vouchers->id);
                                $voucher->pencairan_status = 1;
                                $voucher->save();
                            }
                        }
                    }
                }
            }
            return response()->json(['success'=> 'Data Telah Diubah']);
        }elseif($request->tipe == "vo"){
            $volume = $request->volume;

            $progress = ProgressTambahanVo::where('id',$request->id_progress)->update(['status'=>1]);
            
            $progress = ProgressTambahanVo::where('id',$request->id_progress)->first();
            
            $progress_sebelumnya = DetailVo::where('unit_id',$progress->unit_id)->where('itempekerjaan_id',$progress->itempekerjaan_id)->first()->progresslapangan_percent;

            $totvolume = ProgressTambahanVo::where([
                ['itempekerjaan_id','=',$progress->itempekerjaan_id],
                ['spk_id','=',$progress->spk_id],
                ['unit_id','=',$progress->unit_id],
                ['status','=',1]] )->sum('volume');

            $persentase = ($totvolume/$volume);

            $user = \Auth::user();
            
            if ( 1 <=  $persentase ){
                // $UnitProgress->selesai_actual_date = date("Y-m-d");
                $persentase = 1;
            }
            if($persentase < 1){
                $DetailVo = DetailVo::where('unit_id',$progress->unit_id)
                ->where('itempekerjaan_id',$progress->itempekerjaan_id)->where("id",$progress->detail_vo_id)->update(['progresslapangan_percent'=>$persentase,'progress_sebelumnya'=>$progress_sebelumnya]);
            }else{
                $DetailVo = DetailVo::where('unit_id',$progress->unit_id)
                ->where('itempekerjaan_id',$progress->itempekerjaan_id)->where("id",$progress->detail_vo_id)->update(['progresslapangan_percent'=>$persentase,'progress_sebelumnya'=>$progress_sebelumnya,'selesai_actual_date'=>date("Y-m-d")]);
            }
            // $UnitProgress->progresslapangan_percent = $persentase / 100;
            // $UnitProgress->save();
            $detailvo = DetailVo::where('unit_id',$progress->unit_id)->where('itempekerjaan_id',$progress->itempekerjaan_id)->first();
            $UnitProgressDetail = new UnitProgressDetail;
            $UnitProgressDetail->unit_progress_id = $progress->unit_id;
            $UnitProgressDetail->progress_date = date("Y-m-d");
            $UnitProgressDetail->progress_percent = $persentase ;
            $UnitProgressDetail->pic_rekanan = $detailvo->unit->tender->spks->first()->rekanan_id;
            $UnitProgressDetail->pic_internal = $user->id;
            $UnitProgressDetail->setuju_rekanan_at = date("Y-m-d");
            $UnitProgressDetail->setuju_internal_at = date("Y-m-d");
            // $UnitProgressDetail->description = $request->description;   
            $UnitProgressDetail->save();    

            $spk = Spk::find($progress->spk_id);
            if($spk->tender->aanwijing->jenis_pembayaran == null || $spk->tender->aanwijing->jenis_pembayaran == 1){
                if (count($spk->baps) != 0) {
                    # code...
                    if ($spk->baps[0]->percentage_lapangan == 0) {
                        # code...
                        if ($spk->baps[0]->vouchers != null) {
                            # code...
                            if ((($spk->baps[0]->percentage*1.05)+ Globalsetting::where("id",1005)->first()->value) <= $spk->lapangan) {
                                # code...
                                $voucher = Voucher::find($spk->baps[0]->vouchers->id);
                                $voucher->pencairan_status = 1;
                                $voucher->save();
                            }
                        }
                    }
                }
            }
            
            return response()->json(['success'=> 'Data Telah Diubah']);
        }
    }
}
