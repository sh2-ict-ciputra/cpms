<?php

namespace Modules\Tender\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Project\Entities\Project;
use Modules\Workorder\Entities\Workorder;
use Modules\Tender\Entities\Tender;
use Modules\Tender\Entities\TenderUnit;
use Modules\Tender\Entities\TenderRekanan;
use Modules\Tender\Entities\TenderPenawaran;
use Modules\Tender\Entities\TenderPenawaranDetail;
use Modules\Tender\Entities\TenderMenang;
use Modules\Tender\Entities\TenderMenangDetail;
use Modules\Tender\Entities\TenderKorespondensi;
use Modules\Tender\Entities\TenderDocument;
use Modules\Tender\Entities\TenderDocumentApproval;
use Modules\Rab\Entities\Rab;
use Modules\Rekanan\Entities\Rekanan;
use Modules\Pekerjaan\Entities\Itempekerjaan;
use Modules\Project\Entities\Unit;
use Modules\Globalsetting\Entities\Globalsetting;
use Modules\User\Entities\User;
use Modules\Rekanan\Entities\RekananGroup;
use Modules\TenderMaster\Entities\TenderMaster;
use Modules\Country\Entities\City;
use Modules\Tender\Entities\TenderAanwijings;
use Modules\Tender\Entities\TenderBeritaAcaras;
use Modules\Spk\Entities\SpkTermyn;
use Modules\Spk\Entities\SpkRetensi;
use Modules\Spk\Entities\SpkPengembalianDp;
use Modules\Project\Entities\ProjectPt;
use Storage;
use PDF;
use DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;
use Modules\Tender\Entities\TenderJadwalPenawaran;
use App\Http\Controllers\ApiController;
use Modules\Pekerjaan\Entities\CoaCpmsFinance;
use Modules\Tender\Entities\TenderPenawaranSubDetail;
use Modules\Satuan\Entities\CoaSatuan;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Modules\Tender\Entities\TunjukPemenangTender;
use Modules\Tender\Entities\BeritaAcaraTunjukLangsung;
use Modules\Tender\Entities\JenisPembayaran;

class TenderController extends Controller
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
        $user = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        if($project == null){
            return redirect("/");
        }
        $tenders = $project->tenders->orderBy("id","desc")->get();
        $tanggal_sekarang = date("Y-m-d H:i:s.u");
        return view('tender::index',compact("user","project","tenders","tanggal_sekarang"));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create(Request $request)
    {
        $user = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        if($project == null){
            return redirect("/");
        }
        $workorder = Workorder::get();
        return view('tender::create',compact("user","project","workorder"));
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $rab = Rab::find($request->tender_rab);
        $itempekerjaan = Itempekerjaan::find($rab->pekerjaans->last()->itempekerjaan->parent->id);
        $department_from = $rab->workorder->department_from;
        $project = Project::find($request->session()->get('project_id'));
        $tender = new Tender;
        $tender_no = \App\Helpers\Document::new_number('TDR', $department_from,$project->id).$rab->budget_tahunan->budget->pt->code;
        $tender->rab_id = $request->tender_rab;
        $tender->name = "Tender-".$itempekerjaan->code."-".$itempekerjaan->name."-".$rab->name;
        $tender->no = $tender_no;
        $tender->save();

        // if (!file_exists("./assets/tender/".$tender->id)) {
        //     mkdir("./assets/tender/".$tender->id);
        // }

        foreach ($rab->units as $key => $value) {
            $tender_unit = new TenderUnit;
            $tender_unit->tender_id = $tender->id;
            $tender_unit->rab_unit_id = $value->id;
            $tender_unit->created_by = \Auth::user()->id;
            $tender_unit->save();
        }
        return redirect("/tender/detail/?id=".$tender->id);
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show(Request $request)
    {
        $tender = Tender::find($request->id);
        $user = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        if($project == null){
            return redirect("/");
        }
        $rekanan = Rekanan::get();
        $rab = $tender->rab;
        $itempekerjaan = Itempekerjaan::find($tender->rab->workorder->detail_pekerjaan[0]->itempekerjaan_id);
        $global_setting = Globalsetting::get();
        $data = array();
        $tendermaster = TenderMaster::get();
        
        foreach ($global_setting as $key => $value) {
            
            if ( $value->parameter == "aanwijzing_date"){
                $data['aanwijzing_date'] = $value->value;
            }

            if ( $value->parameter == "penawaran1_date" ){
                $data['penawaran1_date'] = $value->value;
            }

            if ( $value->parameter == "klarifikasi1_date"){
                $data['klarifikasi1_date'] = $value->value;
            }

            if ( $value->parameter == "penawaran2_date" ){
                $data['penawaran2_date'] = $value->value;
            }

            if ( $value->parameter == "klarifikasi2_date"){
                $data['klarifikasi2_date'] = $value->value;
            }

            if ( $value->parameter == "penawaran3_date" ){
                $data['penawaran3_date'] = $value->value;
            }
        }
        
        foreach ($tender->rekanans as $key => $value) {
            foreach ($value->korespondensis as $key2 => $value2) {
                if ( $value2->no == "" ){
                    $tenderkorespondensi = TenderKorespondensi::find($value2->id);
                    $tenderkorespondensi->no = \App\Helpers\Document::new_number( (strtoupper($value2->type)), $tender->rab->workorder->department_from, $project->id).$tender->rab->budget_tahunan->budget->pt->code;
                    $tenderkorespondensi->save();
                }
            }
        }
        
        $dokumen = array( "Gambar" => "Tidak Ada", "BQ" => "Tidak Ada", "Spes" => "Tidak Ada", "Syarat" => "Tidak Ada");
        if ( isset($tender->tender_document)){
            if ( $tender->tender_document != ""){
                foreach ($tender->tender_document as $key2 => $value2) {
                    if ( $value2->document_name == "Gambar Tender" ){
                        $dokumen["Gambar"] = "Ada";
                    }else if ( $value2->document_name == "BQ / Bill Item"){
                        $dokumen["BQ"] = "Ada";
                    }else if ( $value2->document_name == "Klasifikasi Teknis"){
                        $dokumen["Spes"] = "Ada";
                    }else if ( $value2->document_name == "Syarat-Syarat Khusus yang harus dilengkapi"){
                        $dokumen["Syarat"] = "Ada";
                    }
                }
            }
        }
        
        $ttd = array();
        if ( $tender->approval != "" ){            
            $tender_apprroval = $tender->approval->histories;
            $start = 0;
            foreach ($tender_apprroval as $key => $value) {
                if ( $value->user != "" ){
                    // foreach ($value->user->jabatan as $key2 => $value2) {
                    //     if ( $value2['jabatan'] == "General Manager" || $value2['jabatan'] == "Kepala Departemen" || $value2['jabatan'] == "Kepala Divisi"){
                    //         $ttd[$start] = array("nama" => $value->user->user_name, "jabatan" => $value2["jabatan"] );
                    //         $start++;
                    //     }
                    // }                    
                }
            }
        }

        $tanggal_sekarang = date("Y-m-d H:i:s.u");
        $start_tender = 0 ;
        
        // return $tender->units->rab_unit;
        // foreach ($tender->units as $key => $unit) 
        // {
        //     return $unit->rab_unit->pekerjaans;
        //     $nilai = $nilai + $unit->rab_unit->nilai;
        // }
        // return 
        // $date = date("")
        // return $tender->rab->pekerjaans[0]->itempekerjaan->parent->id;
        // return $tender->menangs;
        $coa_dokumen_tender = CoaCpmsFinance::where("project_id", $project->id)->where("pt_id", $tender->rab->pt->id)->where("tipe_coa_id", 2)->first();

        return view('tender::detail2',compact("tender","user","project","rekanan","itempekerjaan","data","dokumen","ttd","tanggal_sekarang","tendermaster","start_tender","coa_dokumen_tender"));
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit()
    {
        return view('tender::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request)
    {
        //echo strtotime($request->ambil_doc_date);die;
        $tender = Tender::find($request->tender_id);
        $tender->durasi = $request->tender_durasi;
        $tender->name = $request->tender_name;
        if(date('d/M/Y', strtotime($tender->ambil_doc_date)) != $request->ambil_doc_date){
            $tender->ambil_doc_date = date("Y-m-d H:i:s.u",strtotime($request->ambil_doc_date));
        }
        if(date('d/M/Y', strtotime($tender->aanwijzing_date)) != $request->aanwijzing_date){
            $tender->aanwijzing_date = date("Y-m-d H:i:s.u",strtotime($request->aanwijzing_date));
        }
        if(isset($request->penawaran1_date)){
            if(date('d/M/Y', strtotime($tender->penawaran1_date)) != $request->penawaran1_date){
                $tender->penawaran1_date = date("Y-m-d H:i:s.u",strtotime($request->penawaran1_date));

                $jadwal_1 = TenderJadwalPenawaran::where('tender_id', $request->tender_id)->where('penawaran_ke',1)->first();
                if( $jadwal_1 == ''){
                    $tender_jadwal_penawaran = new TenderJadwalPenawaran;
                    $tender_jadwal_penawaran->tender_id = $request->tender_id;
                    $tender_jadwal_penawaran->penawaran_date = date("Y-m-d H:i:s",strtotime($request->penawaran1_date));
                    $tender_jadwal_penawaran->klarifikasi_date = date("Y-m-d H:i:s",strtotime($request->klarifikasi1_date));
                    $tender_jadwal_penawaran->penawaran_ke = 1;
                    $tender_jadwal_penawaran->save();

                    foreach ($tender->rekanans as $key => $value) {
                        if ( $value->approval != "" ){
                            if ( $value->approval->approval_action_id == "6") {
            
                                $tender_penawaran = new TenderPenawaran;
                                $tender_penawaran->tender_rekanan_id = $value->id;
                                $tender_penawaran->no = $value->id;
                                $tender_penawaran->date = date("Y-m-d H:i:s.u");
                                $tender_penawaran->created_by = \Auth::user()->id;
                                $tender_penawaran->save();
                                
                                foreach ($tender->rab->pekerjaans as $key2 => $value2) {
                                    if ( $value2->volume != "" && $value2->volume != 0 ){                    
                                        $tenderpenawarandetail = new TenderPenawaranDetail;
                                        $tenderpenawarandetail->tender_penawaran_id = $tender_penawaran->id;
                                        $tenderpenawarandetail->rab_pekerjaan_id = $value2->id; 
                                        $tenderpenawarandetail->keterangan  = $value2->id; 
                                        $tenderpenawarandetail->nilai = "0"; 
                                        $tenderpenawarandetail->volume = str_replace(",","",$value2->volume);
                                        $tenderpenawarandetail->satuan = str_replace(",","",$value2->satuan);
                                        $tenderpenawarandetail->save();

                                        foreach ($value2->sub_pekerjaan as $key3 => $value3) {
                                            $tenderpenawaransubdetail = new TenderPenawaranSubDetail;
                                            $tenderpenawaransubdetail->tender_penawaran_detail_id = $tenderpenawarandetail->id;
                                            $tenderpenawaransubdetail->name  = $value3->name; 
                                            $tenderpenawaransubdetail->volume = $value3->volume; 
                                            $tenderpenawaransubdetail->satuan = $value3->satuan; 
                                            $tenderpenawaransubdetail->nilai = 0; 
                                            $tenderpenawaransubdetail->total_nilai = 0;
                                            $tenderpenawaransubdetail->rab_sub_pekerjaan_id = $value3->id;
                                            $tenderpenawaransubdetail->save();
                                        }
                                    }

                                }
                            }
                        }
                    }
                }else{
                    $tender_jadwal_penawaran = TenderJadwalPenawaran::find($jadwal_1->id);
                    $tender_jadwal_penawaran->tender_id = $request->tender_id;
                    $tender_jadwal_penawaran->penawaran_date = date("Y-m-d H:i:s.u",strtotime($request->penawaran1_date));
                    $tender_jadwal_penawaran->klarifikasi_date = date("Y-m-d H:i:s.u",strtotime($request->klarifikasi1_date));
                    $tender_jadwal_penawaran->penawaran_ke = 1;
                    $tender_jadwal_penawaran->save();
                }
            }
        }
        if($request->klarifikasi1_date){
            if(date('d/M/Y', strtotime($tender->klarifikasi1_date)) != $request->klarifikasi1_date){
                $tender->klarifikasi1_date = date("Y-m-d H:i:s.u",strtotime($request->klarifikasi1_date));
            }
        }
        // if(date('d/M/Y', strtotime($tender->penawaran2_date)) != $request->penawaran2_date){
        //     $tender->penawaran2_date = date("Y-m-d H:i:s.u",strtotime($request->penawaran2_date));
        // }
        // if(date('d/M/Y', strtotime($tender->klarifikasi2_date)) != $request->klarifikasi2_date){
        //     $tender->klarifikasi2_date = date("Y-m-d H:i:s.u",strtotime($request->klarifikasi2_date));
        // }
        if(date('d/M/Y', strtotime($tender->pengumuman_date)) != $request->pengumuman_date){
            $tender->pengumuman_date = date("Y-m-d H:i:s.u",strtotime($request->pengumuman_date));
        }
        // if(date('d/M/Y', strtotime($tender->penawaran3_date)) != $request->pengumuman_date){
        //     $tender->penawaran3_date = date("Y-m-d H:i:s.u",strtotime($request->pengumuman_date));
        // }
        // if(date('d/M/Y', strtotime($tender->recommendation_date)) != $request->recommendation_date){
        //     $tender->recommendation_date = date("Y-m-d H:i:s.u",strtotime($request->recommendation_date));
        // }
        
        $tender->harga_dokumen = str_replace(",", "", $request->harga_dokumen);
        $tender->sifat_tender = $request->jenis_kontrak;
        $tender->kelas_id =$request->tender_type;
        $tender->save();

        return redirect("/tender/detail?id=".$tender->id);
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy()
    {
    }

    public function saverekanan(Request $request){
        //print_r($request->rekanan);die;
        $tender = Tender::find($request->tender_id);
        if ( $request->rekanan != "" ){
            foreach ($request->rekanan as $key => $value) {
                if ( $request->rekanan[$key] != "" ){
                    $tender_rekanan = new TenderRekanan;
                    $tender_rekanan->tender_id = $request->tender_id;
                    $tender_rekanan->rekanan_id = $request->rekanan[$key];
                    if ( $tender->harga_dokumen <= 0 ){
                        $tender_rekanan->doc_bayar_status = 1;
                    }
                    $tender_rekanan->save();
                }           
                
            }
        }
        

        $tanggal_sekarang = date("Y-m-d H:i:s.u");
        return redirect("/tender/rekanan/referensi?id=".$request->tender_id);
    }

    public function removerekanan(Request $request){
        $tenderrekana = TenderRekanan::find($request->id);
        $status = $tenderrekana->delete();
        if ( $status ){
            return response()->json( ["status" => "0"]);
        }else{
            return response()->json( ["status" => "1"] );
        }
    }

    public function approvalrekanan(Request $request){
        
        $project = Project::find($request->session()->get('project_id'));

        $rekanan_ = $request->rekanan_;
        $count = count($rekanan_);
        for($a=0; $a<$count ;$a++){
            $budget = (int)$rekanan_[$a]['id'];
            $class  = "TenderRekanan";
            $approval = \App\Helpers\Document::make_approval('Modules\Tender\Entities\TenderRekanan',$budget);
        }

        $class  = "Tender";
        $tenders = Tender::find($request->tender_id);
        $approval = \App\Helpers\Document::make_approval('Modules\Tender\Entities\Tender',$tenders->id);

        $approval_history_tender = \Modules\Approval\Entities\ApprovalHistory::where('document_id',$tenders->id)->where('document_type','Modules\Tender\Entities\Tender')->orderBy('no_urut','DESC')->first();

        \Modules\Approval\Entities\ApprovalHistory::where("id", $approval_history_tender->id)->update(['approval_action_id' => 1]);
        
        $project_pt = ProjectPt::where("project_id",$project->id)->first();
        $data["email"]=$approval_history_tender->user->email;
        $data["client_name"]=$approval_history_tender->user->user_name;
        $data["subject"]='Approval Rekanan Tender';
        
        $encript = encrypt('https://cpms.ciputragroup.com:81/access/tender/detail/?id='.$tenders->id.'||'.$approval_history_tender->user->id.'||'.date('d/M/Y', strtotime('+ 7 day', strtotime(date("Y-m-d")))));
        $link = 'https://cpms.ciputragroup.com:81/access/login/?code='.$encript;
        $title = "Tender";

        Mail::send('mail.bodyEmailApprove', ['link' => $link, 'title' => $title, 'user' => $approval_history_tender->user, 'project_pt' => $project_pt, 'name' => $tenders->name], function($message)use($data) {
            $message->from(env('MAIL_USERNAME'))->to($data["email"], $data["client_name"])->subject($data["subject"]);
        });


        $approval_history_rab = \Modules\Approval\Entities\ApprovalHistory::where('document_type',"Modules\Rab\Entities\Rab")->where('document_id',$tenders->rab->id)->orderBy('no_urut','DESC')->first();
        
        $approval_history_rab->update(['approval_action_id' => 1]);

        $data_rab["email"]=$approval_history_rab->user->email;
        $data_rab["client_name"]=$approval_history_rab->user->user_name;
        $data_rab["subject"]='Approval RAB';

        $encript = encrypt('https://cpms.ciputragroup.com:81/access/rab/detail/?id='.$tenders->rab->id.'||'.$approval_history_rab->user->id.'||'.date('d/M/Y', strtotime('+ 7 day', strtotime(date("Y-m-d")))));

        $link_rab = 'https://cpms.ciputragroup.com:81/access/login/?code='.$encript;
        $title_rab = "Rab";

        Mail::send('mail.bodyEmailApprove', ['link' => $link_rab, 'title' => $title_rab, 'user' => $approval_history_rab->user, 'project_pt' => $project_pt, 'name' => $tenders->rab->name], function($message)use($data_rab) {
            $message->from(env('MAIL_USERNAME'))->to($data_rab["email"], $data_rab["client_name"])->subject($data_rab["subject"]);
        });

        $tender_dokumen = "";
        return response()->json(['success'=> 'Data Telah Diubah']);
    }

    public function addpenawaran(Request $request){
        $rekanan = TenderRekanan::find($request->id);
        $rab = $rekanan->tender->rab;
        $itempekerjaan = Itempekerjaan::find($rab->pekerjaans->last()->itempekerjaan->parent->id);
        $user = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        return view("tender::detail_rab",compact("rab","itempekerjaan","rekanan","user","project"));
    }

    public function savepenawaran(Request $request){
        $tenderrekaann = TenderRekanan::find($request->tender_rab_id);
        $tender_penawaran = new TenderPenawaran;
        $tender_penawaran->tender_rekanan_id = $request->tender_rab_id;
        $tender_penawaran->no = $request->tender_rab_id;
        $tender_penawaran->date = date("Y-m-d H:i:s.u");
        $tender_penawaran->created_by = \Auth::user()->id;
        $tender_penawaran->save();
        //print_r($request->input_rab_id_);die;
        foreach ($request->input_rab_id_ as $key => $value) {
            if ( $request->input_rab_nilai_[$key]  != "" && $request->input_rab_volume_[$key] != "" ){

                $tenderpenawarandetail = new TenderPenawaranDetail;
                $tenderpenawarandetail->tender_penawaran_id = $tender_penawaran->id;
                $tenderpenawarandetail->rab_pekerjaan_id = $request->input_rab_id_[$key]; 
                $tenderpenawarandetail->keterangan  = $request->input_rab_id_[$key]; 
                $tenderpenawarandetail->nilai = str_replace(",", "",$request->input_rab_nilai_[$key]); 
                $tenderpenawarandetail->volume = str_replace(",","",$request->input_rab_volume_[$key]);
                $tenderpenawarandetail->satuan = str_replace(",","",$request->input_rab_satuan_[$key]);
                $tenderpenawarandetail->save();
            }
        }

        return redirect("/tender/detail/?id=".$tenderrekaann->tender->id); 
    }

    public function addstep2(Request $request){
        $tender = Tender::find($request->id);
        $rab = $tender->rab;
        $user = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        $itempekerjaan = Itempekerjaan::find($rab->pekerjaans->last()->itempekerjaan->parent->id);
        return view("tender::detail_step2",compact("tender","itempekerjaan","user","project"));
    }

    public function savepenawaran2(Request $request){
        // return $request;
        $tender = Tender::find($request->tender_id);
        foreach ($tender->rekanans as $key => $value) {
            if ( $value->approval != "" ){
                if ( $value->approval->approval_action_id == "6") {
                    foreach ($value->penawarans as $key2 => $value2) {
                        $old_penawaran = TenderPenawaran::find($value2->id);
                        $old_penawaran->updated_by = \Auth::user()->id;
                        $old_penawaran->save();
                    }

                    $tender_penawaran = new TenderPenawaran;
                    $tender_penawaran->tender_rekanan_id = $value->id;
                    $tender_penawaran->no = $value->id;
                    $tender_penawaran->date = date("Y-m-d H:i:s.u");
                    $tender_penawaran->created_by = \Auth::user()->id;
                    $tender_penawaran->save();

                    foreach ($request->input_rab_id_ as $key2 => $value2) {
                        if ( $request->input_rab_volume_[$key2] != "" ){                    
                            $tenderpenawarandetail = new TenderPenawaranDetail;
                            $tenderpenawarandetail->tender_penawaran_id = $tender_penawaran->id;
                            $tenderpenawarandetail->rab_pekerjaan_id = $request->input_rab_id_[$key2]; 
                            $tenderpenawarandetail->keterangan  = $request->input_rab_id_[$key2]; 
                            $tenderpenawarandetail->nilai = "0"; 
                            $tenderpenawarandetail->volume = str_replace(",","",$request->input_rab_volume_[$key2]);
                            $tenderpenawarandetail->satuan = str_replace(",","",$request->input_rab_satuan_[$key2]);
                            $tenderpenawarandetail->save();
                        }
                    }
                }
            }
        }

        if($request->penawaran2_date != ''){
            $tender_jadwal_penawaran = new TenderJadwalPenawaran;
            $tender_jadwal_penawaran->tender_id = $request->tender_id;
            $tender_jadwal_penawaran->penawaran_date = date("Y-m-d H:i:s",strtotime($request->penawaran2_date));
            $tender_jadwal_penawaran->klarifikasi_date = date("Y-m-d H:i:s",strtotime($request->klarifikasi2_date));
            $tender_jadwal_penawaran->penawaran_ke = 2;
            $tender_jadwal_penawaran->save();
        }

        return redirect("/tender/detail/?id=".$request->tender_id); 
        
    }

    public function step2(Request $request){
        $tenderpenawaran = TenderPenawaran::find($request->id);
        $tenderRekanan = $tenderpenawaran->rekanan;
        $rab = $tenderRekanan->tender->rab;
        $itempekerjaan = Itempekerjaan::find($rab->pekerjaans->last()->itempekerjaan->parent->id);
        $user = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        $penawaran_id = "";
        foreach ($tenderRekanan->penawarans as $key => $value) {
            if ( $value->updated_by == null ) {
                $penawaran_id = $value->id;
            }
        }

        return view("tender::detail_penawaran2",compact("rab","itempekerjaan","rekanan","user","project","tenderpenawaran","tenderRekanan","penawaran_id"));
    }

    public function updatepenawaran2(Request $request){
        foreach ($request->input_rab_id_ as $key => $value) {
            if ( $request->input_rab_nilai_[$key] != "" ){
                $tenderpenawarandetail = TenderPenawaranDetail::find($request->input_rab_id_[$key]);
                $tenderpenawarandetail->nilai = str_replace(",","",$request->input_rab_nilai_[$key]);
                $tenderpenawarandetail->save();
            }else{
                echo $request->input_rab_id_[$key];
            }
        }
        $TenderPenawaran = TenderPenawaran::find($request->tender_id);
        if ( $_FILES['fileupload']['tmp_name'] != ""){
            $array_mime = array("application/pdf","application/vnd.openxmlformats-officedocument.wordprocessingml.document","application/vnd.openxmlformats-officedocument.spreadsheetml.sheet","application/vnd.ms-excel","application/msword");
            $mime = mime_content_type($_FILES['fileupload']['tmp_name']);
            if ( in_array($mime, $array_mime)){
                $target_file =  /*$_SERVER["DOCUMENT_ROOT"].*/"../assets/tender/".$TenderPenawaran->rekanan->tender->id;
                move_uploaded_file($_FILES["fileupload"]["tmp_name"], $target_file);
                $TenderPenawaran->file_attachment = $_FILES['fileupload']['name'];
                $TenderPenawaran->save();
            }else{
                print("<script type='text/javascript'>alert('Format file tidak bisa diterima. Silahkan upload sesuai format yang diminta');</script>");
            }
        }
        return redirect("/tender/detail/?id=".$TenderPenawaran->rekanan->tender->id);
    }

    public function savepenawaran3(Request $request){
        // return $request;
        $tender = Tender::find($request->tender_id);
        foreach ($tender->rekanans as $key => $value) {
            if ( $value->approval != "" ){
                if ( $value->approval->approval_action_id == "6") {
                    $tender_penawaran = new TenderPenawaran;
                    $tender_penawaran->tender_rekanan_id = $value->id;
                    $tender_penawaran->no = $value->id;
                    $tender_penawaran->date = date("Y-m-d H:i:s.u");
                    $tender_penawaran->created_by = \Auth::user()->id;
                    $tender_penawaran->save();

                    // return $request->input_rab_id_;
                    foreach ($request->input_rab_id_ as $key2 => $value2) {
                        $tenderpenawarandetail = new TenderPenawaranDetail;
                        $tenderpenawarandetail->tender_penawaran_id = $tender_penawaran->id;
                        $tenderpenawarandetail->rab_pekerjaan_id = $request->input_rab_id_[$key2]; 
                        $tenderpenawarandetail->keterangan  = $request->input_rab_id_[$key2]; 
                        $tenderpenawarandetail->nilai = "0"; 
                        $tenderpenawarandetail->volume = str_replace(",","",$request->input_rab_volume_[$key2]);
                        $tenderpenawarandetail->satuan = str_replace(",","",$request->input_rab_satuan_[$key2]);
                        $tenderpenawarandetail->save();
                    }
                }
            }
        }

        if($request->penawaran3_date != ''){
            $tender_jadwal_penawaran = new TenderJadwalPenawaran;
            $tender_jadwal_penawaran->tender_id = $request->tender_id;
            $tender_jadwal_penawaran->penawaran_date = date("Y-m-d H:i:s",strtotime($request->penawaran3_date));
            $tender_jadwal_penawaran->klarifikasi_date = date("Y-m-d H:i:s",strtotime($request->klarifikasi3_date));
            $tender_jadwal_penawaran->penawaran_ke = 3;
            $tender_jadwal_penawaran->save();
        }


        return redirect("/tender/detail/?id=".$request->tender_id); 
        
    }

    public function addstep3(Request $request){
        $tender = Tender::find($request->id);
        $rab = $tender->rab;
        $user = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        $itempekerjaan = Itempekerjaan::find($rab->pekerjaans->last()->itempekerjaan->parent->id);
        // return $tender->penawarans->last()->details;
        return view("tender::detail_step3",compact("tender","itempekerjaan","user","project"));
    }

    public function updatepenawaran3(Request $request){
        foreach ($request->input_rab_id_ as $key => $value) {
            if ( $request->input_rab_nilai_[$key] != "" ){                 
                $tenderpenawarandetail = TenderPenawaranDetail::find($request->input_rab_id_[$key]);
                $tenderpenawarandetail->nilai = str_replace(",","",$request->input_rab_nilai_[$key]);
                $tenderpenawarandetail->save();
            }
        }
        
        $tenderPenawaran = TenderPenawaran::find($request->tender_id);
        if ( $_FILES['fileupload']['tmp_name'] != ""){
            $array_mime = array("application/pdf","application/vnd.openxmlformats-officedocument.wordprocessingml.document","application/vnd.openxmlformats-officedocument.spreadsheetml.sheet","application/vnd.ms-excel","application/msword");
            $mime = mime_content_type($_FILES['fileupload']['tmp_name']);
            if ( in_array($mime, $array_mime)){
                $target_file = /*$_SERVER["DOCUMENT_ROOT"].*/"../public/assets/tender/".$tenderPenawaran->rekanan->tender->id."/".$_FILES['fileupload']['name'];
                move_uploaded_file($_FILES["fileupload"]["tmp_name"], $target_file);
                $tenderPenawaran->file_attachment = $_FILES['fileupload']['name'];
                $tenderPenawaran->save();
            }else{
                print("<script type='text/javascript'>alert('Format file tidak bisa diterima. Silahkan upload sesuai format yang diminta');</script>");
            }
        }
        return redirect("/tender/detail/?id=".$tenderPenawaran->rekanan->tender->id);
    }

    public function step3(Request $request){
        $tenderpenawaran = TenderPenawaran::find($request->id);
        $tenderRekanan = $tenderpenawaran->rekanan;
        $rab = $tenderRekanan->tender->rab;
        $itempekerjaan = Itempekerjaan::find($rab->pekerjaans->last()->itempekerjaan->parent->id);
        $user = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        $penawaran_id = "";
        foreach ($tenderRekanan->penawarans as $key => $value) {
            if ( $value->updated_by == null ) {
                $penawaran_id = $value->id;
            }
        }
        return view("tender::detail_penawaran3",compact("rab","itempekerjaan","rekanan","user","project","tenderpenawaran","tenderRekanan","penawaran_id"));
    }

    public function ispemenang(Request $request){
        $tender_rekanan = TenderRekanan::find($request->id);
        $tender_rekanan->is_recomendasi = "1";
        $tender_rekanan->save();

        foreach ($tender_rekanan->tender->rab->units as $key => $value) {
            $tender_menang = new TenderMenang;
            $tender_menang->tender_rekanan_id = $request->id;
            $tender_menang->tender_unit_id = $value->id;
            $tender_menang->asset_type = $value->asset_type;
            $tender_menang->asset_id = $value->asset_id;
            $tender_menang->save();

            foreach ($tender_rekanan->tender->penawarans->last()->details as $key2 => $value2) {
                
                $tender_menang_details = new TenderMenangDetail;
                $tender_menang_details->tender_menang_id = $tender_menang->id;
                $tender_menang_details->itempekerjaan_id = $value2->rab_pekerjaan->itempekerjaan_id;
                $tender_menang_details->nilai = $value2->nilai;
                $tender_menang_details->volume = $value2->volume;
                $tender_menang_details->satuan = $value2->satuan;
                if ( $value->asset_type == "Modules\Project\Entities\Unit"){
                    $unit = Unit::find($value->asset_id);
                    $tender_menang_details->templatepekerjaan_detail_id = $unit->templatepekerjaan_id;
                }else{
                    $tender_menang_details->templatepekerjaan_detail_id = "0";
                }
                $tender_menang_details->save();
            }
        }

        $tender_menang_id = $tender_rekanan->tender->id;
        //$class  = "TenderMenang";
        //$approval = \App\Helpers\Document::make_approval('Modules\Tender\Entities\TenderMenang',$tender_menang->id);


        return response()->json( ["status" => "0"]);
        
    }

    public function rekaptender(Request $request){
        $tender = Tender::find($request->id);
        // return $tender->tunjuk_pemenang_tender->tender_rekanan->penawarans[$tender->tunjuk_pemenang_tender->penawaran-1]->details;
        $step   = $request->step - 1 ;
        $user = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        $count_rekanan = 0;
        foreach ( $tender->rekanans as $key1 => $value2 ){
            if ($value2->approval->approval_action_id == 6){
                $count_rekanan++;
            }
        }
        return view("tender::tender_rekap",compact("tender","step","project","user","count_rekanan"));
    }

    public function editpenawaran(Request $request){        
        $user    = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        $tenderpenawaran  = TenderPenawaran::find($request->id);
        return view("tender::edit_penawaran",compact("user","project","tenderpenawaran")); 
    }

    public function saveeditpenawaran(Request $request){
        foreach ($request->id_ as $key => $value) {
            if ( $request->nilai_[$key] != "" && $request->volume_[$key] != "" ){
                $tenderpenawarandetail = TenderPenawaranDetail::find($request->id_[$key]);
                $tenderpenawarandetail->nilai = str_replace(",", "", $request->nilai_[$key]);
                $tenderpenawarandetail->volume = $request->volume_[$key];
                $tenderpenawarandetail->save();
            }
        }

        return redirect("/tender/detail/?id=".$request->tender_id);
    }

    public function download(Request $request){
        $tender_document = TenderPenawaran::find($request->id);
        
        $headers = [
              'Content-Type' => 'application/pdf',
           ];
        if ( $tender_document != "" ){
            $filenames = explode("/", $tender_document->file_attachment);
        }

        $file = public_path()."/".str_replace("public", "", $tender_document->file_attachment);
        // return $tender_document->file_attachment;
        return response()->download($file, $filenames[4], $headers);
        
        // $tenderpenawaran = TenderPenawaran::find($request->id);
        // $tender          = $tenderpenawaran->rekanan->tender->id;
        // $file            =  /*$_SERVER["DOCUMENT_ROOT"].*/"../public/assets/tender/".$tender."/".$tenderpenawaran->file_attachment;
        // if (file_exists($file)) {
        //     header('Content-Description: File Transfer');
        //     header('Content-Type: application/octet-stream');
        //     header('Content-Disposition: attachment; filename="'.basename($file).'"');
        //     header('Expires: 0');
        //     header('Cache-Control: must-revalidate');
        //     header('Pragma: public');
        //     header('Content-Length: ' . filesize($file));
        //     readfile($file);
        //     exit;
        // }
    }

    public function approval_history(Request $request){
        $tender = Tender::find($request->id);
        $user   = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        return view("tender::approval_history",compact("tender","user","project"));
    }

    public function updatedocument(Request $request){
        if ( $request->check != "" ){
            foreach ($request->check as $key => $value) {
                if ( $request->check[$key] != "" ){
                    $tender = TenderDocument::find($request->dokumen[$key]);
                    foreach ($tender->document_approval as $key2 => $value2) {   
                        if ( $value2->tender_document_id == $request->dokumen[$key] ){
                            if ( $value2->status == "7" ){
                                $tender_approval = TenderDocumentApproval::find($value2->id);
                                $tender_approval->status = "1";
                                $tender_approval->save();
                            }
                        }              
                    }  
                }
            }
        }
        
        return redirect("/tender/detail/?id=".$request->tender_docs);
    }

    public function referensi(Request $request){
        $tender = Tender::find($request->id);
        $user   = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        $rekanan_group = RekananGroup::get();
        $itempekerjaan = $tender->rab->pekerjaans->first()->itempekerjaan->parent;
        if ( $itempekerjaan->parent == null ){
            $itemkerjan = Itempekerjaan::find($itempekerjaan->id);
        }else{
            $itemkerjan = Itempekerjaan::find($itempekerjaan->parent->id);

        }

        $project_all = Project::get();

        $pekerjaan = Itempekerjaan::get();
        return view("tender::tender_referensi",compact("tender","user","project","rekanan_group","itemkerjan","pekerjaan","project_all"));
    }

    public function searchreferensi(Request $request){
        $html = "";
        $project_name = "";
        $start = 0;
        $list_rekanan = array();

        if ( $request->rekanan_name != "" ){
            $rekanan_group = RekananGroup::where("name","like","%".$request->rekanan_name."%")->get();
            foreach ($rekanan_group as $key => $value) {
                $rekanan_group = RekananGroup::find($value->id);
                foreach ($rekanan_group->supps as $key2 => $value2) {
                    foreach ( $value2->pt->project as $key3 => $value3 ){
                        $project_name .= $value3->project->name .",";
                    }
                }

                foreach ( $rekanan_group->rekanans as $key4 => $value4 ){                    
                    $list_rekanan[$start] = array ( "id" => $value4->id, "name" => $value->name, "project" => $project_name, "rekanan_group_id" => $rekanan_group->id );
                    $start++;
                }
            }
        }

        //return response()->json(["status" => $list_rekanan]);
        if ( $request->itempekerjaan != "all" ){
            $itempekerjaan = Itempekerjaan::find($request->itempekerjaan);

            foreach ($itempekerjaan->rekanan_specification as $key => $value) { 
                if ( $request->rekanan_name == "" ){
                    $spesfikasi = "";                    
                    foreach ($value->rekanan_group->spesifikasi as $key => $value) {
                        $spesfikasi .= $value->itempekerjaan->name.",";
                    }   

                    $project_name = "";
                    foreach ($value->rekanan_group->supps as $key2 => $value2) {
                        foreach ( $value2->pt->project as $key3 => $value3 ){
                            $project_name .= $value3->project->name .",";
                        }
                    }


                    $html .= "<tr>";
                    $html .= "<td>".$value->rekanan_group->name."</td>"; 
                    $html .= "<td>".$spesfikasi."</td>";   
                    $html .= "<td>".$value->rekanan_group->npwp_alamat."</td>";   
                    $html .= "<td>".$project_name."</td>";   
                    $html .= "<td><input type='checkbox' value='".$value->rekanan_group->rekanans->first()->id."' name='rekanan[".$key3."]'/>Set to Tender</td>";               
                }else{
                    foreach ( $list_rekanan as $key3 => $value3){                    
                        if ( $value3['id'] == $value->rekanan_group_id ){      
                            $spesfikasi = "";
                            
                            foreach ($value->rekanan_group->spesifikasi as $key => $value) {
                                $spesfikasi .= $value->itempekerjaan->name.",";
                            }                  
                            
                            $html .= "<tr>";
                            $html .= "<td>".$value3['name']."</td>"; 
                            $html .= "<td>".$spesfikasi."</td>";   
                            $html .= "<td>".$value->rekanan_group->npwp_alamat."</td>";   
                            $html .= "<td>".$value3['project']."</td>";   
                            $html .= "<td><input type='checkbox' value='".$value->rekanan_group->rekanans->first()->id."' name='rekanan[".$key3."]'/>Set to Tender</td>";      
                        }
                    }
                }
            }            
        }else{   
            foreach ( $list_rekanan as $key3 => $value3){  

                $rekanan_group_detail = RekananGroup::find($value3['id']);
                $spesfikasi = "";
                        
                // foreach ($rekanan_group_detail->spesifikasi as $key => $value) {
                //     $spesfikasi .= $value->itempekerjaan->name.",";
                // }                             
                $html .= "<tr>";
                $html .= "<td>".$value3['name']."</td>"; 
                $html .= "<td>".$spesfikasi."</td>"; 
                if($rekanan_group_detail != null)
                    $html .= "<td>".$rekanan_group_detail->npwp_alamat."</td>"; 
                else{
                    $html .= "<td></td>"; 
                }
                $html .= "<td>".$value3['project']."</td>";   
                $html .= "<td><input type='checkbox' value='".$value3['id']."' name='rekanan[".$key3."]'/>Set to Tender</td>";  
            }   
        }
        if ( $html == "" ){
            $html .= "<tr>";
            $html .= "<td colspan='5'>Data tidak ditemukan</td>";
            $html .= "</tr>";
        }
       // return response()->json(["status" => $list_rekanan]);
        return response()->json( ["status" => "0", "html" => $html]);
    }

    public function addreferensi(Request $request){
        $user = \Auth::user();
        $tender = Tender::find($request->id);
        $city = City::get();
        $project = Project::find($request->session()->get('project_id'));
        $status_perusahaan = \Modules\Rekanan\Entities\StatusPerusahaan::get();

        return view("tender::referensi_add",compact("user","tender","city","project","status_perusahaan"));
    }

    public function savereferensi(Request $request){
        $project = Project::find($request->session()->get('project_id'));
        $rekanan_group = new RekananGroup;
        $rekanan_group->pph_percent = $request->pph;
        $rekanan_group->name = $request->name;
        $rekanan_group->npwp_alamat = $request->alamat;
        $rekanan_group->npwp_kota = $request->kota;
        $rekanan_group->cp_name = $request->contact_name;
        $rekanan_group->cp_jabatan = $request->contact_position;
        $rekanan_group->project_id = $project->id;
        $rekanan_group->npwp_no = $request->npwp;
        $rekanan_group->ktp_no = $request->ktp;
        $rekanan_group->status_perusahaan_id = $request->status_perusahaan;
        $rekanan_group->description = "Disurvey oleh ".$request->survey_name;

        if ( $request->pkp == "" ){
            $rekanan_group->pkp_status = 2;
            $pkp_status = 2;
        }else{
            $rekanan_group->pkp_status = 1;
            $pkp_status = 1;
        }
        $rekanan_group->save();

        $rekanan_group_update = RekananGroup::find($rekanan_group->id);

        if (!file_exists ("./assets/rekanan/".$rekanan_group->id)) {
            mkdir("./assets/rekanan/".$rekanan_group->id);
            chmod("./assets/rekanan/".$rekanan_group->id,0755);
        }

        $target_file = "./assets/rekanan/".$request->rekanan_group_id."/".$_FILES['sertifikat']['name'];
                move_uploaded_file($_FILES["sertifikat"]["tmp_name"], $target_file);

        $target_file_2 = "./assets/rekanan/".$request->rekanan_group_id."/".$_FILES['sertifikat']['name'];
                move_uploaded_file($_FILES["npwp"]["tmp_name"], $target_file);

        $target_file_3 = "./assets/rekanan/".$request->rekanan_group_id."/".$_FILES['sertifikat']['name'];
                move_uploaded_file($_FILES["siup_file"]["tmp_name"], $target_file);

        if ( $_FILES['npwp']['name'] == "" ){
            $rekanan_group_update->npwp_image = "";
        }else{
            $rekanan_group_update->npwp_image = $_FILES['npwp']['name'];
        }

        $rekanan_group_update->save();

        if ( $_FILES['siup_file']['name'] == "" ){
            $siup_file = "";
        }else{
            $siup_file = $_FILES['siup_file']['name'];
        }

        if ( count($rekanan_group->rekanans) <= 0 ){            
            $rekanan_child = new Rekanan;
            $rekanan_child->rekanan_group_id = $rekanan_group->id;
            $rekanan_child->name = $request->name;
            $rekanan_child->surat_alamat = $request->alamat;
            $rekanan_child->surat_kota = $request->kota;
            $rekanan_child->ppn = 10;
            $rekanan_child->survey_status = 1;
            $rekanan_child->survey_date = date("Y-m-d H:i:s",strtotime($request->survey_date));
            $rekanan_child->siup_no = $request->siup_no;
            $rekanan_child->siup_image = $siup_file;
            $rekanan_child->gabung_date = date("Y-m-d H:i:s");
            $rekanan_child->description = "Disurvey oleh ".$request->survey_name;
            $rekanan_child->saksi_name = $request->contact_name;
            $rekanan_child->saksi_jabatan = $request->contact_position;
            $rekanan_child->pkp_status = $pkp_status;
            $rekanan_child->save();

        }elseif ( count($rekanan_group->rekanans) == 1 ){
            foreach ($rekanan_group->rekanans as $key2 => $value2) {
                $rekanan_child = Rekanan::find($value2->id);
                $rekanan_child->name = $request->name;
                $rekanan_child->surat_alamat = $request->alamat;
                $rekanan_child->surat_kota = $request->kota;
                $rekanan_child->ppn = 10;
                $rekanan_child->survey_status = 1;
                $rekanan_child->survey_date = date("Y-m-d H:i:s",strtotime($request->survey_date));
                $rekanan_child->siup_no = $request->siup_no;
                $rekanan_child->siup_image = $siup_file;
                $rekanan_child->gabung_date = date("Y-m-d H:i:s");
                $rekanan_child->description = "Disurvey oleh ".$request->survey_name;
                $rekanan_child->saksi_name = $request->contact_name;
                $rekanan_child->saksi_jabatan = $request->contact_position;
                $rekanan_child->pkp_status = $pkp_status;
                $rekanan_child->save();
            }
        }

        return redirect("/tender/rekanan/referensi?id=".$request->tender_id);
    }

    public function aanwijing(Request $request){
        $tender = Tender::find($request->id);
        $user = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        $jenis_pembayaran = JenisPembayaran::get();
        return view("tender::aanwijing",compact("user","tender","project","jenis_pembayaran"));
    }

    public function saveannwijing(Request $request){
        $tender_aanwijing = new TenderAanwijings;
        $tender_aanwijing->tender_id = $request->tender_id;
        $tender_aanwijing->tanggal = date("Y-m-d");
        $tender_aanwijing->waktu = date("H:i:s.u");
        $tender_aanwijing->tempat = $request->tempat;
        $tender_aanwijing->masa_pelaksanaan  = $request->masa_pelaksaan;
        $tender_aanwijing->masa_penawaran = $request->masa_pemeliharaan;
        $tender_aanwijing->jaminan_penawaran = $request->jaminan_penawaran;
        $tender_aanwijing->jaminan_pelaksanaan = $request->jaminan_pelaksanaan;
        $tender_aanwijing->denda = $request->denda;
        $tender_aanwijing->created_by = \Auth::user()->id;
        $tender_aanwijing->jenis_pembayaran_id = $request->jenis_pembayaran;
        $tender_aanwijing->jumlah_pengembalian = $request->jumlah_pengembalian;
        $tender_aanwijing->save();

        if ( $request->termyn != "" ){
            foreach ($request->termyn as $key => $value) {
                if ( $request->termyn[$key] != "" ){
                    $spk_termyn = new SpkTermyn;
                    $spk_termyn->tender_id = $request->tender_id;
                    $spk_termyn->termin = $request->termyn[$key];
                    // $spk_termyn->tanggal_pembayaran = $request->date[$key];
                    $spk_termyn->progress = 0;
                    $spk_termyn->save();
                }
            }
        }

        if ( $request->percent != "" ){
            foreach ($request->percent as $key => $value) {
                if ( $request->percent[$key] != "" ){
                    $spk_retensi = new SpkRetensi;
                    $spk_retensi->tender_id = $request->tender_id;
                    $spk_retensi->percent = $request->percent[$key] / 100;
                    $spk_retensi->hari = $request->waktu[$key];
                    $spk_retensi->save();
                }
            }
        }

        if ( $request->jumlah_pengembalian > 0 && $request->jenis_pembayaran == 3){
            foreach ($request->pengembalian as $key => $value) {
                if ( $request->pengembalian[$key] != "" ){
                    $spk_pengembalian = new SpkPengembalianDp;
                    $spk_pengembalian->tender_id = $request->tender_id;
                    $spk_pengembalian->nilai = $request->pengembalian[$key];
                    $spk_pengembalian->urutan = $key;
                    $spk_pengembalian->status = 0;
                    $spk_pengembalian->save();
                }
            }
        }

        return redirect("/tender/aanwijing/detail?id=".$tender_aanwijing->id);
    }

    public function showaanwijing(Request $request){
        $aanwijing = TenderAanwijings::find($request->id);
        $user = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        $jenis_pembayaran = JenisPembayaran::get();
        return view("tender::show_aanwijing",compact("user","project","aanwijing","jenis_pembayaran"));
    }

    public function updateaanwijing(Request $request){
        $tender_aanwijing = TenderAanwijings::find($request->aanwijing);
        $tender_aanwijing->tender_id = $request->tender_id;
        $tender_aanwijing->tanggal = date("Y-m-d");
        $tender_aanwijing->waktu = date("H:i:s.u");
        $tender_aanwijing->tempat = $request->tempat;
        $tender_aanwijing->masa_pelaksanaan  = $request->masa_pelaksaan;
        $tender_aanwijing->masa_penawaran = $request->masa_pemeliharaan;
        $tender_aanwijing->jaminan_penawaran = $request->jaminan_penawaran;
        $tender_aanwijing->jaminan_pelaksanaan = $request->jaminan_pelaksanaan;
        $tender_aanwijing->denda = $request->denda;
        $tender_aanwijing->created_by = \Auth::user()->id;
        $tender_aanwijing->jenis_pembayaran_id = $request->jenis_pembayaran;
        $tender_aanwijing->jumlah_pengembalian = $request->jumlah_pengembalian;
        $tender_aanwijing->save();

        if ( $request->termyn != "" ){
            SpkTermyn::where("tender_id",$request->tender_id)->delete();
            foreach ($request->termyn as $key => $value) {
                if ( $request->termyn[$key] != "" ){
                    $spk_termyn = new SpkTermyn;
                    $spk_termyn->tender_id = $request->tender_id;
                    $spk_termyn->termin = $request->termyn[$key];
                    // $spk_termyn->tanggal_pembayaran = $request->date[$key];
                    $spk_termyn->progress = 0;
                    $spk_termyn->save();
                }
            }
        }
        
        if ( $request->percent != "" ){
            SpkRetensi::where("tender_id",$request->tender_id)->delete();
            foreach ($request->percent as $key => $value) {
                if ( $request->percent[$key] != "" ){
                    $spk_retensi = new SpkRetensi;
                    $spk_retensi->tender_id = $request->tender_id;
                    $spk_retensi->percent = $request->percent[$key] / 100;
                    $spk_retensi->hari = $request->waktu[$key];
                    $spk_retensi->save();
                }
            }
        }
        
        if ( $request->jumlah_pengembalian > 0 && $request->jenis_pembayaran == 3){
            SpkPengembalianDp::where("tender_id",$request->tender_id)->delete();
            foreach ($request->pengembalian as $key => $value) {
                if ( $request->pengembalian[$key] != "" ){
                    $spk_pengembalian = new SpkPengembalianDp;
                    $spk_pengembalian->tender_id = $request->tender_id;
                    $spk_pengembalian->nilai = $request->pengembalian[$key];
                    $spk_pengembalian->urutan = $key;
                    $spk_pengembalian->status = 0;
                    $spk_pengembalian->save();
                }
            }
        }
        return redirect("/tender/aanwijing/detail?id=".$tender_aanwijing->id);
    }

    public function berita_acara(Request $request){
        $tender = Tender::find($request->id);
        $user = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        $step = $request->step;
        return view("tender::berita_acara",compact("tender","user","project","step"));
    }

    public function saveberita_acara(Request $request){
        $tender_berita_acara = new TenderBeritaAcaras;
        $tender_berita_acara->tender_id = $request->tender_id;
        $tender_berita_acara->resume = $request->title;
        $tender_berita_acara->step = $request->step;
        $tender_berita_acara->content = $request->editor1;
        $tender_berita_acara->save();
        return redirect("tender/berita_acara/show?id=".$tender_berita_acara->id);

    }

    public function showberita_acara(Request $request){
        $berita_acara = TenderBeritaAcaras::find($request->id);      
        $user = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        return view("tender::show_berita_acara",compact("user","project","berita_acara"));
    }

    public function updberita_acara(Request $request){
        $tender_berita_acara = TenderBeritaAcaras::find($request->berita_acara);
        $tender_berita_acara->resume = $request->title;
        $tender_berita_acara->content = $request->editor1;
        $tender_berita_acara->save();

        return redirect("tender/berita_acara/show?id=".$tender_berita_acara->id);
    }

    public function ceklunas(Request $request){
        $tender_rekanan = TenderRekanan::find($request->id);
        $tender_rekanan->doc_bayar_status = 1;
        $tender_rekanan->doc_bayar_date = date("Y-m-d H:i:s.000");
        $tender_rekanan->updated_at = date("Y-m-d H:i:s.000");
        $tender_rekanan->updated_by = \Auth::user()->id;
        $tender_rekanan->save();

        return response()->json(["status => 0 "]);
    }

    public function kirimUndanganAanwijing(Request $request){
        date_default_timezone_set("Asia/Jakarta");
        $date = date("d-m-Y");
        $project = Project::find($request->session()->get('project_id'));
        $project_pt = ProjectPt::where("project_id",$project->id)->first();
        $korespondensi = TenderKorespondensi::find($request->id_korespondensi);
        $edit_aanwijing = TenderAanwijings::where([
            ['id', '=', $korespondensi->tender_rekanan->tender->aanwijing->id]
        ])
            ->update(
                [
                    'jam_mulai' => $request->jam_mulai,
                    'tempat' => $request->tempat,
                ]

            );
        // return $korespondensi->tender_rekanan->tender->aanwijing->jam_mulai;

        $norek = $request->norek;
        $penyetuju = DB::table("users")
                        ->join("user_details","user_details.user_id","users.id")
                        ->join("user_jabatans","user_jabatans.id","user_details.user_jabatan_id")
                        ->join("mappingperusahaans","mappingperusahaans.id","user_details.mappingperusahaan_id")
                        ->join("project_pt_users","project_pt_users.user_id","users.id")
                        ->where("user_details.user_jabatan_id",5)
                        ->where("project_pt_users.project_id",$project->id)
                        ->where("users.deleted_at", null)
                        ->select("users.id as user_id","users.user_name as name","user_jabatans.name as jabatan")
                        ->distinct()
                        ->get();

        $korespondensi = TenderKorespondensi::find($request->id_korespondensi);
        setLocale(LC_ALL, 'id');
        $tanggal_pelaksanaan = \Carbon\Carbon::parse($korespondensi->tender_rekanan->tender->aanwijzing_date)->formatLocalized('%A, %d %B %Y');
        $pdf = PDF::loadView('tender::cetakan_undangan_aanwijing', compact('project_pt','date','korespondensi','penyetuju','tanggal_pelaksanaan','norek'));

        $data["email"]=$korespondensi->tender_rekanan->rekanan->email;
        $data["client_name"]=$korespondensi->tender_rekanan->rekanan->name;
        $data["subject"]='Surat Undangan Aanwdjzing';

        Mail::send('mail.demo', ['korespondensi' => $korespondensi, 'title' => $data["subject"], 'project_pt' => $project_pt], function($message)use($data,$pdf) {
        $message->from(env('MAIL_USERNAME'))->to($data["email"], $data["client_name"])
        ->subject($data["subject"])
        ->attachData($pdf->output(), "Undangan Aanwijing.pdf");
        });

        $korespondensi = TenderKorespondensi::find($request->id_korespondensi);
        $korespondensi->status_kirim_aanwizing = 1;
        $korespondensi->save();
    }

    public function cetakSuratUndangan(Request $request){
        date_default_timezone_set("Asia/Jakarta");
        $date = date("d-m-Y");
        $project = Project::find($request->session()->get('project_id'));
        $project_pt = ProjectPt::where("project_id",$project->id)->first();
        $korespondensi = TenderKorespondensi::find($request->id_korespondensi);
        $edit_aanwijing = TenderAanwijings::where([
            ['id', '=', $korespondensi->tender_rekanan->tender->aanwijing->id]
        ])
            ->update(
                [
                    'jam_mulai' => $request->jam_mulai,
                    'tempat' => $request->tempat,
                ]

            );
        // return $korespondensi->tender_rekanan->tender->aanwijing->jam_mulai;
        $norek = $request->norek;
        $penyetuju = DB::table("users")
                        ->join("user_details","user_details.user_id","users.id")
                        ->join("user_jabatans","user_jabatans.id","user_details.user_jabatan_id")
                        ->join("mappingperusahaans","mappingperusahaans.id","user_details.mappingperusahaan_id")
                        ->join("project_pt_users","project_pt_users.user_id","users.id")
                        ->where("user_details.user_jabatan_id",5)
                        ->where("project_pt_users.project_id",$project->id)
                        ->where("users.deleted_at", null)
                        ->select("users.id as user_id","users.user_name as name","user_jabatans.name as jabatan")
                        ->distinct()
                        ->get();
        
        $korespondensi = TenderKorespondensi::find($request->id_korespondensi);
        setLocale(LC_ALL, 'id');
        $tanggal_pelaksanaan = \Carbon\Carbon::parse($korespondensi->tender_rekanan->tender->aanwijzing_date)->formatLocalized('%A, %d %B %Y');
        // return $tanggal_pelaksanaan;
        $pdf = PDF::loadView('tender::cetakan_undangan_aanwijing', compact('project_pt','date','korespondensi','penyetuju','tanggal_pelaksanaan','norek'));
        return $pdf->download('Undangan Aanwijing.pdf');
    }

    public function data_Aanwijing(Request $request){
        $korespondensi = TenderKorespondensi::find($request->id_korespondensi);

        $arr=[
            'jam_mulai' => date ( "H:i" , strtotime ($korespondensi->tender_rekanan->tender->aanwijing->jam_mulai) ),
            'tempat' => $korespondensi->tender_rekanan->tender->aanwijing->tempat,
        ];

        
        return response()->json(['data' => $arr]);
    }

    public function cetakSuratPemenangTender(Request $request){
        date_default_timezone_set("Asia/Jakarta");
        $date = date("d-m-Y");
        $project = Project::find($request->session()->get('project_id'));
        $project_pt = ProjectPt::where("project_id",$project->id)->first();
        $korespondensi = TenderKorespondensi::find($request->id_korespondensi);
        $penyetuju = DB::table("users")
                        ->join("user_details","user_details.user_id","users.id")
                        ->join("user_jabatans","user_jabatans.id","user_details.user_jabatan_id")
                        ->join("mappingperusahaans","mappingperusahaans.id","user_details.mappingperusahaan_id")
                        ->join("project_pt_users","project_pt_users.user_id","users.id")
                        ->where("user_details.user_jabatan_id",5)
                        ->where("project_pt_users.project_id",$project->id)
                        ->where("users.deleted_at", null)
                        ->select("users.id as user_id","users.user_name as name","user_jabatans.name as jabatan")
                        ->distinct()
                        ->get();
        
        setLocale(LC_ALL, 'id');
        $pdf = PDF::loadView('tender::cetakan_pemenang_tender', compact('project_pt','date','korespondensi','penyetuju'));
        return $pdf->download('Surat Pemenang Tender.pdf');
    }

    public function kirimSuratPemenangTender(Request $request){
        date_default_timezone_set("Asia/Jakarta");
        $date = date("d-m-Y");
        $project = Project::find($request->session()->get('project_id'));
        $project_pt = ProjectPt::where("project_id",$project->id)->first();
        $korespondensi = TenderKorespondensi::find($request->id_korespondensi);
        $penyetuju = DB::table("users")
                        ->join("user_details","user_details.user_id","users.id")
                        ->join("user_jabatans","user_jabatans.id","user_details.user_jabatan_id")
                        ->join("mappingperusahaans","mappingperusahaans.id","user_details.mappingperusahaan_id")
                        ->join("project_pt_users","project_pt_users.user_id","users.id")
                        ->where("user_details.user_jabatan_id",5)
                        ->where("project_pt_users.project_id",$project->id)
                        ->where("users.deleted_at", null)
                        ->select("users.id as user_id","users.user_name as name","user_jabatans.name as jabatan")
                        ->distinct()
                        ->get();
        
        setLocale(LC_ALL, 'id');
        $pdf = PDF::loadView('tender::cetakan_pemenang_tender', compact('project_pt','date','korespondensi','penyetuju'));

        $data["email"]=$korespondensi->tender_rekanan->rekanan->email;
        $data["client_name"]=$korespondensi->tender_rekanan->rekanan->name;
        $data["subject"]='Surat Pengumuman Pemenang Tender';

        Mail::send('mail.demo', ['korespondensi' => $korespondensi, 'title' => $data["subject"], 'project_pt' => $project_pt], function($message)use($data,$pdf) {
        $message->from(env('MAIL_USERNAME'))->to($data["email"], $data["client_name"])
        ->subject($data["subject"])
        ->attachData($pdf->output(), "Surat Pengumuman Pemenang Tender.pdf");
        });
        $korespondensi = TenderKorespondensi::find($request->id_korespondensi);
        $korespondensi->status_kirim_pemenang = 1;
        $korespondensi->save();
        // return $pdf->download('Surat Pemenang Tender.pdf');
    }

    public function cetakSuratKlarifikasiTender(Request $request){
        date_default_timezone_set("Asia/Jakarta");
        $date = date("d-m-Y");
        $project = Project::find($request->session()->get('project_id'));
        $project_pt = ProjectPt::where("project_id",$project->id)->first();
        $korespondensi = TenderKorespondensi::find($request->id_korespondensi);
        $berita_acara = TenderBeritaAcaras::where('tender_id',$korespondensi->tender_rekanan->tender->id)
                                            ->where('step',1)
                                            ->orderby('id','desc')
                                            ->get();
        // $edit_berita_acara = TenderBeritaAcaras::where([
        //     ['id', '=', $berita_acara[0]->id],['step','=',1]])
        //     ->update(
        //         [
        //             'waktu' => $request->jam_mulai,
        //             'tempat' => $request->tempat,
        //         ]

        //     );

        $edit_aanwijing = TenderAanwijings::where([
            ['id', '=', $korespondensi->tender_rekanan->tender->aanwijing->id]
        ])
            ->update(
                [
                    'jam_mulai' => $request->jam_mulai,
                    'tempat' => $request->tempat,
                ]

            );

        $berita_acara = TenderBeritaAcaras::where('tender_id',$korespondensi->tender_rekanan->tender->id)
                                            ->where('step',1)
                                            ->orderby('id','desc')
                                            ->get();
        $penyetuju = DB::table("users")
                        ->join("user_details","user_details.user_id","users.id")
                        ->join("user_jabatans","user_jabatans.id","user_details.user_jabatan_id")
                        ->join("mappingperusahaans","mappingperusahaans.id","user_details.mappingperusahaan_id")
                        ->join("project_pt_users","project_pt_users.user_id","users.id")
                        ->where("user_details.user_jabatan_id",5)
                        ->where("project_pt_users.project_id",$project->id)
                        ->where("users.deleted_at", null)
                        ->select("users.id as user_id","users.user_name as name","user_jabatans.name as jabatan")
                        ->distinct()
                        ->get();
        
        setLocale(LC_ALL, 'id');
        $tanggal_pelaksanaan = \Carbon\Carbon::parse($korespondensi->tender_rekanan->tender->klarifikasi1_date)->formatLocalized('%A, %d %B %Y');

        $pdf = PDF::loadView('tender::cetakan_klarifikasi_tender', compact('project_pt','date','korespondensi','penyetuju','tanggal_pelaksanaan','berita_acara'));
        return $pdf->download('BA Klarifikasi Tender.pdf');
    }

    public function kirimSuratKlarifikasiTender(Request $request){
        date_default_timezone_set("Asia/Jakarta");
        $date = date("d-m-Y");
        $project = Project::find($request->session()->get('project_id'));
        $project_pt = ProjectPt::where("project_id",$project->id)->first();
        $korespondensi = TenderKorespondensi::find($request->id_korespondensi);
        $berita_acara = TenderBeritaAcaras::where('tender_id',$korespondensi->tender_rekanan->tender->id)
                                            ->where('step',1)
                                            ->orderby('id','desc')
                                            ->get();
        // $edit_berita_acara = TenderBeritaAcaras::where([
        //     ['id', '=', $berita_acara[0]->id],['step','=',1]])
        //     ->update(
        //         [
        //             'waktu' => $request->jam_mulai,
        //             'tempat' => $request->tempat,
        //         ]

        //     );

        $edit_aanwijing = TenderAanwijings::where([
            ['id', '=', $korespondensi->tender_rekanan->tender->aanwijing->id]
        ])
            ->update(
                [
                    'jam_mulai' => $request->jam_mulai,
                    'tempat' => $request->tempat,
                ]

            );

        $berita_acara = TenderBeritaAcaras::where('tender_id',$korespondensi->tender_rekanan->tender->id)
                                            ->where('step',1)
                                            ->orderby('id','desc')
                                            ->get();
        $penyetuju = DB::table("users")
                        ->join("user_details","user_details.user_id","users.id")
                        ->join("user_jabatans","user_jabatans.id","user_details.user_jabatan_id")
                        ->join("mappingperusahaans","mappingperusahaans.id","user_details.mappingperusahaan_id")
                        ->join("project_pt_users","project_pt_users.user_id","users.id")
                        ->where("user_details.user_jabatan_id",5)
                        ->where("project_pt_users.project_id",$project->id)
                        ->where("users.deleted_at", null)
                        ->select("users.id as user_id","users.user_name as name","user_jabatans.name as jabatan")
                        ->distinct()
                        ->get();
        
        setLocale(LC_ALL, 'id');
        $tanggal_pelaksanaan = \Carbon\Carbon::parse($korespondensi->tender_rekanan->tender->klarifikasi1_date)->formatLocalized('%A, %d %B %Y');

        $pdf = PDF::loadView('tender::cetakan_klarifikasi_tender', compact('project_pt','date','korespondensi','penyetuju','tanggal_pelaksanaan','berita_acara'));

        $data["email"]=$korespondensi->tender_rekanan->rekanan->email;
        $data["client_name"]=$korespondensi->tender_rekanan->rekanan->name;
        $data["subject"]='Surat Berita Acara Klarifikasi';

        Mail::send('mail.demo', ['korespondensi' => $korespondensi, 'title' => $data["subject"], 'project_pt' => $project_pt], function($message)use($data,$pdf) {
        $message->from(env('MAIL_USERNAME'))->to($data["email"], $data["client_name"])
        ->subject($data["subject"])
        ->attachData($pdf->output(), "Surat Berita Acara Klarifikasi.pdf");
        });

        $korespondensi = TenderKorespondensi::find($request->id_korespondensi);
        $korespondensi->status_kirim_berita_acara = 1;
        $korespondensi->save();
        // return $pdf->download('BA Klarifikasi Tender.pdf');
    }

    public function data_Klarifikasi(Request $request){
        $korespondensi = TenderKorespondensi::find($request->id_korespondensi);
        // $berita_acara = TenderBeritaAcaras::where('tender_id',$korespondensi->tender_rekanan->tender->id)
        //                                     ->where('step',1)
        //                                     ->orderby('id','desc')
        //                                     ->get();

        $arr=[
            'jam_mulai' => date ( "H:i" , strtotime ($korespondensi->tender_rekanan->tender->aanwijing->jam_mulai) ),
            'tempat' => $korespondensi->tender_rekanan->tender->aanwijing->tempat,
        ];

        
        return response()->json(['data' => $arr]);
    }

    public function Send_Email(Request $request){
        $boy = "4aghiwardani8@gmail.com";
        $user = User::get();
        Mail::to($boy)->send(new SendMail($user[0]));
  
        return "hai";
    }

    public function cetakAanwijing(Request $request){
        $tender =Tender::find($request->id);

        $pdf = PDF::loadView('tender::cetakan_aanwijing', compact('tender'));
        return $pdf->stream('Tender Aanwidjing.pdf', array("Attachment" => false));
        return $pdf->download('Tender Aanwidjing.pdf');
    }

    public function addstep_berulang(Request $request){
        // return "hohohoho";
        $tender = Tender::find($request->id);
        $rab = $tender->rab;
        $user = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        $itempekerjaan = Itempekerjaan::find($rab->pekerjaans->last()->itempekerjaan->parent->id);
        $coa_satuan = CoaSatuan::all();
        return view("tender::detail_step_berulang",compact("tender","itempekerjaan","user","project","coa_satuan"));
    }

    public function savepenawaran_berulang(Request $request){
        // return $request;
        $tender = Tender::find($request->tender_id);
        foreach ($tender->rekanans as $key => $value) {
            if ( $value->approval != "" ){
                if ( $value->approval->approval_action_id == "6") {
                    foreach ($value->penawarans as $key2 => $value2) {
                        $old_penawaran = TenderPenawaran::find($value2->id);
                        $old_penawaran->updated_by = \Auth::user()->id;
                        $old_penawaran->save();
                    }

                    $tender_penawaran = new TenderPenawaran;
                    $tender_penawaran->tender_rekanan_id = $value->id;
                    $tender_penawaran->no = $value->id;
                    $tender_penawaran->date = date("Y-m-d H:i:s.u");
                    $tender_penawaran->created_by = \Auth::user()->id;
                    $tender_penawaran->save();

                    foreach ($request->input_rab_id_ as $key2 => $value2) {
                        if ( $request->input_rab_volume_[$key2] != "" ){                    
                            $tenderpenawarandetail = new TenderPenawaranDetail;
                            $tenderpenawarandetail->tender_penawaran_id = $tender_penawaran->id;
                            $tenderpenawarandetail->rab_pekerjaan_id = $request->input_rab_id_[$key2]; 
                            $tenderpenawarandetail->keterangan  = $request->input_rab_id_[$key2]; 
                            $tenderpenawarandetail->nilai = "0"; 
                            $tenderpenawarandetail->volume = str_replace(",","",$request->input_rab_volume_[$key2]);
                            $tenderpenawarandetail->satuan = str_replace(",","",$request->input_rab_satuan_[$key2]);
                            $tenderpenawarandetail->save();
                        }
                    }
                }
            }
        }

        if($request->penawaran_date != ''){
            $tender_jadwal_penawaran = new TenderJadwalPenawaran;
            $tender_jadwal_penawaran->tender_id = $request->tender_id;
            $tender_jadwal_penawaran->penawaran_date = date("Y-m-d H:i:s",strtotime($request->penawaran_date));
            $tender_jadwal_penawaran->klarifikasi_date = date("Y-m-d H:i:s",strtotime($request->klarifikasi_date));
            $tender_jadwal_penawaran->penawaran_ke = $request->step;
            $tender_jadwal_penawaran->save();
        }

        return redirect("/tender/detail/?id=".$request->tender_id); 
        
    }

    public function cetakba (Request $request){
        $tender = Tender::find($request->tender);
        $resume = "";
        if($tender->berita_acara->last() != null){
            $resume = str_replace('<p>', '', str_replace('</p>', '', $tender->berita_acara->last()->content));
        }
    //     <!-- {{strip_tags(str_replace('<p>', '', str_replace('</p>', ' <br/>\r\n', $tender->berita_acara->last()->content)))}} -->
    //   {{strip_tags($tender->berita_acara->last()->content)}}

        $pdf = PDF::loadView('tender::cetakan_berita_acara', compact('tender', 'resume'));
        return $pdf->stream('Cetakan Berita Acara .pdf');
    }

    public function posting_voucher(Request $request){
        $user = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        $rekanan_ = $request->rekanan_;
        $count = count($rekanan_);
        $request_key = (object)json_decode(ApiController::CallAPI("GET", "http://13.76.184.138:8080/api/cashierapi/index.php/cpms/requestkey"),true);
        $dataValidasi = (object) [];


        for($a=0; $a<$count ;$a++){
            $tender_rekanan = TenderRekanan::find((int)$rekanan_[$a]['id']);
            $korespondensi = TenderKorespondensi::where("tender_rekanan_id", $tender_rekanan->id)->first();
            $coa = CoaCpmsFinance::where("project_id", $project->id)->where("pt_id", $tender_rekanan->tender->rab->workorder->pt_wo->id)->where("tipe_coa_id", 2)->first();
            
            if($coa == null){
                return response()->json( ["response" => "Coa Finance Tidak ada"] ); 
            }
            $dataValidasi->project_id = $project->project_id;
            $dataValidasi->pt_id = $tender_rekanan->tender->rab->workorder->pt_wo->pt_id;
            $dataValidasi->coa_detail = $coa->coa_finance;
            $dataValidasi->sub_unit = null;
            $url = "http://13.76.184.138:8080/api/cashierapi/index.php/cpms/validasivoucher";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $dataValidasi);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataValidasi);
    
            $data = curl_exec($ch);
            $data = json_decode($data);
            $validasi = $data->result;

            if($validasi == 1){
                $datasurat = (object)[];
                $datasurat->request_key = $request_key->key;
                $datasurat->project_id = $project->project_id;
                $datasurat->pt_id = $tender_rekanan->tender->rab->workorder->pt_wo->pt_id;
                $datasurat->idsuratundangan = $korespondensi->id;
                $datasurat->nosuratundangan = $korespondensi->no;
                $datasurat->namarekanan = $tender_rekanan->rekanan->group->name;
                $datasurat->amount = $tender_rekanan->tender->harga_dokumen;
                $url = "http://13.76.184.138:8080/api/cashierapi/index.php/cpms/insertundangan";
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_TIMEOUT, 60);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $datasurat);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $datasurat);
                $data3 = curl_exec($ch);
                $data3 = json_decode($data3);

                $dataUpload = (object)[];
                $date =date("Y-m-d");
                $dataUpload->request_key = $request_key->key;
                $dataUpload->project_id = $project->project_id;
                $dataUpload->pt_id = $tender_rekanan->tender->rab->workorder->pt_wo->pt_id;
                $dataUpload->uploaduniquenumber = $korespondensi->id;
                $dataUpload->department = "C&D";
                $dataUpload->coa_header = null;
                $dataUpload->dataflow = "I";
                $dataUpload->amount_header = $tender_rekanan->tender->harga_dokumen;
                $dataUpload->note = $korespondensi->no;
                $dataUpload->is_customer = 0;
                $dataUpload->is_vendor = 1;
                $dataUpload->vendor_name = $tender_rekanan->rekanan->group->name;
                $dataUpload->pengajuandate = $date;
                $dataUpload->kwitansidate = null;
                $dataUpload->duedate = $date ;
                $dataUpload->receipt_no = null;
                $dataUpload->coa_detail = $coa->coa_finance;
                $dataUpload->description = "pembayaran dokumen Tender";
                $dataUpload->sub_unit = null;
                $dataUpload->seq_detail = $korespondensi->id;
                $dataUpload->amount = $tender_rekanan->tender->harga_dokumen;
                $dataUpload->spk = null;
                if($tender_rekanan->tender->rab->workorder->projectKawasan != null){
                    $dataUpload->kawasan = $tender_rekanan->tender->rab->workorder->projectKawasan->name;
                }else{
                    $dataUpload->kawasan = null;
                }
                $dataUpload->paymentdate = null;
                $dataUpload->user_id = $user->user_id;
                $dataUpload->idsuratundangan = $korespondensi->id;

                // return response()->json( ["request_key" => $dataUpload] );
                $url = "http://13.76.184.138:8080/api/cashierapi/index.php/cpms/uploadvoucher";
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_TIMEOUT, 60);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $dataUpload);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $dataUpload);
                $data2 = curl_exec($ch);
                $data2 = json_decode($data2);
                // return response()->json( ["response" => $data2] );
                if($data2->result == 1){
                    $tender_rekanan_save = TenderRekanan::find((int)$rekanan_[$a]['id']);
                    $tender_rekanan_save->posting_voucher = 1;
                    $tender_rekanan_save->voucher_id = $data2->voucherid;
                    $tender_rekanan_save->save();
                    $hasil[$tender_rekanan->id] = $data2;
                }
            }else{
                return response()->json( ["response" => $data] );
            }
        } 
        return response()->json( ["response" => "Voucher sudah Terposting"] );

    }

    public function itemPekerjaanRab(Request $request){
        $tender = Tender::find($request->tender);
        
        $data = [];
        foreach ($tender->rab->pekerjaans as $key => $value) {
            # code...
            $sub = [];
            foreach ($value->sub_pekerjaan as $key2 => $value2) {
                # code...
                $arr2 = [
                    'id'                => $value2->id,
                    'rab_pekerjaan_id'  => $value2->rab_pekerjaan_id,
                    'name'              => $value2->name,
                    'volume'            => $value2->volume,
                    'satuan'            => $value2->satuan,
                    'nilai'             => $value2->nilai,
                    'total_nilai'       => $value2->total_nilai

                ];
                array_push($sub, $arr2);
            }

            $arr = [
                'id'            => $value->id,
                'code'          => $value->itempekerjaan->code,
                'name'          => $value->itempekerjaan->name,
                'satuan'        => $value->satuan,
                'volume'        => $value->volume,
                'total_nilai'   => $value->total_nilai,
                'sub'           => $sub,
            ];
            array_push($data, $arr);
        }
        return response()->json( ["data" => $data] );
    }

    public function saveAllPekerjaan(Request $request){
        // return $request; 
        $tender = Tender::find($request->tender_id);

        if($request->tanggal_penawaran != ''){
            $tender_jadwal_penawaran = new TenderJadwalPenawaran;
            $tender_jadwal_penawaran->tender_id = $request->tender_id;
            $tender_jadwal_penawaran->penawaran_date = date("Y-m-d H:i:s",strtotime($request->tanggal_penawaran));
            $tender_jadwal_penawaran->klarifikasi_date = date("Y-m-d H:i:s",strtotime($request->tanggal_klarifikasi));
            $tender_jadwal_penawaran->penawaran_ke = $request->step;
            $tender_jadwal_penawaran->save();

            foreach ($tender->rekanans as $key => $value) {
                if ( $value->approval != "" ){
                    if ( $value->approval->approval_action_id == "6") {
                        // foreach ($value->penawarans as $key2 => $value2) {
                        //     $old_penawaran = TenderPenawaran::find($value2->id);
                        //     $old_penawaran->updated_by = \Auth::user()->id;
                        //     $old_penawaran->save();
                        // }
    
                        $tender_penawaran = new TenderPenawaran;
                        $tender_penawaran->tender_rekanan_id = $value->id;
                        $tender_penawaran->no = $value->id;
                        $tender_penawaran->date = date("Y-m-d H:i:s.u");
                        $tender_penawaran->created_by = \Auth::user()->id;
                        $tender_penawaran->save();
    
                        for ($i=0; $i < count($request->data); $i++) { 
                            if ( $request->data[$i][2] != "" && $request->data[$i][2] != 0 ){                    
                                $tenderpenawarandetail = new TenderPenawaranDetail;
                                $tenderpenawarandetail->tender_penawaran_id = $tender_penawaran->id;
                                $tenderpenawarandetail->rab_pekerjaan_id = $request->data[$i][1]; 
                                $tenderpenawarandetail->keterangan  = $request->data[$i][1]; 
                                $tenderpenawarandetail->nilai = "0"; 
                                $tenderpenawarandetail->volume = str_replace(",","",$request->data[$i][2]);
                                $tenderpenawarandetail->satuan = $request->data[$i][3];
                                $tenderpenawarandetail->save();
    
                                if(array_key_exists('4', $request->data[$i])){
                                    for ($j=0; $j < count($request->data[$i][4]); $j++) { 
                                        $tenderpenawaransubdetail = new TenderPenawaranSubDetail;
                                        $tenderpenawaransubdetail->tender_penawaran_detail_id = $tenderpenawarandetail->id;
                                        $tenderpenawaransubdetail->name  = $request->data[$i][4][$j][1]; 
                                        $tenderpenawaransubdetail->volume = $request->data[$i][4][$j][2]; 
                                        $tenderpenawaransubdetail->satuan = $request->data[$i][4][$j][3]; 
                                        $tenderpenawaransubdetail->nilai = 0; 
                                        $tenderpenawaransubdetail->total_nilai = 0;
                                        $tenderpenawaransubdetail->rab_sub_pekerjaan_id = $request->data[$i][4][$j][0];
                                        $tenderpenawaransubdetail->save();
                                    }
                                }   
                            }
                        }
                    }
                }
            }
        }
        return response()->json( ["status" => "status"] );
    }

    public function tunjukPemenang(Request $request){
        // return $request;
        $tender_rekanan = TenderRekanan::find($request->rekanan_id);
        $tender = $tender_rekanan->tender;
        if($tender->tunjuk_pemenang_tender != null){
            $tender->tunjuk_pemenang_tender->delete();
        }
        $usulan_pemenang = new TunjukPemenangTender;
        $usulan_pemenang->tender_id = $tender->id; 
        $usulan_pemenang->tender_rekanan_id = $tender_rekanan->id; 
        $usulan_pemenang->is_pemenang = 0; 
        $usulan_pemenang->description = $request->alasan;
        $usulan_pemenang->penawaran = $request->penawaran;
        $usulan_pemenang->save();
        
        $approval = \App\Helpers\Document::make_approval('Modules\Tender\Entities\TunjukPemenangTender',$usulan_pemenang->id);

        $approval_history_usulan = \Modules\Approval\Entities\ApprovalHistory::where('document_id',$usulan_pemenang->id)->where('document_type','Modules\Tender\Entities\TunjukPemenangTender')->orderBy('no_urut','DESC')->first();

        \Modules\Approval\Entities\ApprovalHistory::where("id", $approval_history_usulan->id)->update(['approval_action_id' => 1]);

        $project_pt = ProjectPt::where("project_id",$tender->project->id)->first();
        $data["email"]=$approval_history_usulan->user->email;
        $data["client_name"]=$approval_history_usulan->user->user_name;
        $data["subject"]='Approval Usulan Pemenang Tender';
        // $link = 'https://ces.ciputragroup.com/webapps/Ciputra/public/';
        
        // $encript = encrypt($approval_history_usulan->user->id);
        $encript = encrypt('https://cpms.ciputragroup.com:81/access/usulanPemenang/detail/?id='.$usulan_pemenang->id.'||'.$approval_history_usulan->user->id.'||'.date('d/M/Y', strtotime('+ 7 day', strtotime(date("Y-m-d")))));
        $link = 'https://cpms.ciputragroup.com:81/access/login/?code='.$encript;

        // $link = 'http://cpms.ciputragroup.com:81/access/tender/detail/?id='.$usulan_pemenang->id.'&code='.$encript;
        $title = "Usulan Pemenang Tender";

        Mail::send('mail.bodyEmailApprove', ['link' => $link, 'title' => $title, 'user' => $approval_history_usulan->user, 'project_pt' => $project_pt, 'name' => $usulan_pemenang->tender->name], function($message)use($data) {
            $message->from(env('MAIL_USERNAME'))->to($data["email"], $data["client_name"])->subject($data["subject"]);
        });
        
        return response()->json( ["data" => 0]);
        // return $tender_rekanan;
    }

    public function closeTender(Request $request){
        $tender = Tender::find($request->id);
        // $tender = $rab->tenders[0];
        // $rab->approval->update(['approval_action_id' => 8]);
        $tender->approval->update(['approval_action_id' => 8]);
        
        foreach ($tender->rab->workorder->details as $key => $value) {
            # code...
            if($value->asset_type == "Modules\Project\Entities\Unit"){
                $unit = Unit::find($value->asset_id);
                $unit->is_readywo = null;
                $unit->save();
            }
        }

        return response()->json( ["status" => "0"] );
    }

    public function saveBA(Request $request){
        // return $request;
        $tender = Tender::find($request->tender_id);
        $user = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        if ($tender->kelas_id == 1) {
            # code...
            $no = \App\Helpers\Document::new_number('BA-TL', $tender->rab->workorder->department_from,$project->id);
        } elseif ($tender->kelas_id == 2){
            # code...
            $no = \App\Helpers\Document::new_number('BA-RO', $tender->rab->workorder->department_from,$project->id);

        }
        

        if ($request->beritaacara_id == null || $request->beritaacara_id == '') {
            # code...
            $berita_acara = new BeritaAcaraTunjukLangsung;
            $berita_acara->tender_id = $request->tender_id;
            $berita_acara->no = $no;
            $berita_acara->isian = $request->isian;
            $berita_acara->tanggal = date("Y-m-d");
            $berita_acara->save();
        } else {
            # code...
            $berita_acara = BeritaAcaraTunjukLangsung::find($request->beritaacara_id);
            $berita_acara->tender_id = $request->tender_id;
            $berita_acara->isian = $request->isian;
            $berita_acara->save();
        }

        return response()->json( ["data" => "1"]);
        
    }

    public function dataBeritaAcara(Request $request){
        if ($request->beritaacara_id == null || $request->beritaacara_id == '') {
            # code...
            return response()->json(['data' => '']);
        } else {
            # code...
            $berita_acara = BeritaAcaraTunjukLangsung::find($request->beritaacara_id);
            
            return response()->json(['data' => $berita_acara->isian]);
        }
    }

    public function cetakBeritaAcara(Request $request){
        // return $request;

        date_default_timezone_set("Asia/Jakarta");
        $date = date("d-m-Y");
        $project = Project::find($request->session()->get('project_id'));
        $berita_acara = BeritaAcaraTunjukLangsung::find($request->ba_id);

        // return $berita_acara;
        $ttd_pertama =  $berita_acara->tender->rab->approval->histories->first()->user;
        foreach($ttd_pertama->jabatan as $key => $value){
            if( $value['pt_id'] == $berita_acara->tender->rab->pt->id){
                $jabatan = $value['jabatan'];
            }
        };
        $user_kadiv = '';
        foreach($berita_acara->tender->project->pt_user as $key => $value){
            if($value->user != null){
                $jabatan_kadiv = $value->user->details->where('user_jabatan_id',7)->where('project_pt_id',$value->id)->first();
                if( $jabatan_kadiv != null){
                    $user_kadiv = $jabatan_kadiv->user->user_name;
                }
            }
        }
        // return $jabatan;
        $pdf = PDF::loadView('tender::cetakan_berita_acara_tunjuk_langsung', compact('project','date','berita_acara', 'jabatan', 'ttd_pertama', 'user_kadiv'));
        return $pdf->stream('Undangan Aanwijing.pdf');
    }

    public function kirimEmailApproval(Request $request){
        // $project = Project::find($request->session()->get('project_id'));

        // $rekanan_ = $request->rekanan_;

        // $tenders = Tender::find($request->tender_id);

        // $approval_history_rab = \Modules\Approval\Entities\ApprovalHistory::where('document_type',"Modules\Rab\Entities\Rab")->where('document_id',$tenders->rab->id)->orderBy('no_urut','DESC')->first();
        
        // $approval_history_rab->update(['approval_action_id' => 1]);
        // $project_pt = ProjectPt::where("project_id",$project->id)->first();

        // $data_rab["email"]=$approval_history_rab->user->email;
        // $data_rab["client_name"]=$approval_history_rab->user->user_name;
        // $data_rab["subject"]='Approval RAB';

        // $encript = encrypt('https://cpms.ciputragroup.com:81/access/rab/detail/?id='.$tenders->rab->id.'||'.$approval_history_rab->user->id.'||'.date('d/M/Y', strtotime('+ 7 day', strtotime(date("Y-m-d")))));

        // $link_rab = 'https://cpms.ciputragroup.com:81/access/login/?code='.$encript;
        // $title_rab = "Rab";

        // Mail::send('mail.bodyEmailApprove', ['link' => $link_rab, 'title' => $title_rab, 'user' => $approval_history_rab->user, 'project_pt' => $project_pt, 'name' => $tenders->rab->name], function($message)use($data_rab) {
        //     $message->from(env('MAIL_USERNAME'))->to($data_rab["email"], $data_rab["client_name"])->subject($data_rab["subject"]);
        // });

        // $tender_dokumen = "";
        return "berhasil";
    }

}
