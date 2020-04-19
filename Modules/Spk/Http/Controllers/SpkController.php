<?php

namespace Modules\Spk\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Tender\Entities\TenderMenang;
use Modules\Pekerjaan\Entities\Itempekerjaan;
use Modules\Project\Entities\Project;
use Modules\Spk\Entities\Spk;
use Modules\Spk\Entities\SpkvoUnit;
use Modules\Spk\Entities\SpkDetail;
use Modules\Spk\Entities\SpkTermyn;
use Modules\Spk\Entities\SpkTermynDetail;
use Modules\Spk\Entities\SpkPengembalian;
use Modules\Spk\Entities\SpkRetensi;
use Modules\Spk\Entities\SpkType;
use Modules\Project\Entities\UnitProgress;
use Modules\Spk\Entities\Suratinstruksi;
use Modules\Spk\Entities\Vo;
use Modules\Spk\Entities\Bap;
use Modules\Spk\Entities\BapDetail;
use Modules\Spk\Entities\BapDetailItempekerjaan;
use Modules\Spk\Entities\BapPph;
use Modules\Rekanan\Entities\Rekening;
use Modules\Tender\Entities\Tender;
use Modules\Spk\Entities\SuratInstruksiItem;
use Modules\Spk\Entities\SuratInstruksiUnit;
use Modules\Tender\Entities\TenderUnit;
use Modules\Rab\Entities\RabUnit;
use Modules\User\Entities\User;
use Modules\Globalsetting\Entities\Globalsetting;
use Modules\Pt\Entities\Pt;
use PDFMerger;
use Illuminate\Support\Facades\DB;
use PDF;
use Modules\Spk\Entities\Terbilang;
use Modules\Spk\Entities\IpkTambahan;
use Modules\Spk\Entities\IpkDefault;
use Modules\Spk\Entities\ProgressTambahan;
use Modules\Spk\Entities\ProgressDefault;
use Modules\Satuan\Entities\CoaSatuan;
use Modules\Rab\Entities\RabPekerjaan;
use Modules\Rekanan\Entities\PphRekanan;
use Modules\Progress\Entities\Siks;
use Modules\Progress\Entities\SikDetail;
use Modules\Spk\Entities\NewVo;
use Modules\Spk\Entities\DetailVo;
use Modules\Spk\Entities\SubDetailVo;
use Modules\Spk\Entities\ProgressTambahanVo;
use Modules\Spk\Entities\SpkPercepatan;
use Modules\Spk\Entities\SpkPercepatanUnit;
use Modules\Spk\Entities\BapUnitDetail;
use Modules\Progress\Entities\SikSubDetail;
use Modules\Project\Entities\ProjectPt;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;
use Modules\Spk\Entities\IpkProgressTahapan;
use Modules\Tender\Entities\TenderMenangSubDetail;
use Modules\Tender\Entities\TenderMenangDetail;
use Modules\Tender\Entities\TenderPenawaranDetail;
use Modules\Tender\Entities\TenderPenawaranSubDetail;
use Modules\Pekerjaan\Entities\CoaCpmsFinance;
use Modules\Spk\Entities\SpkPengembalianDp;
use Modules\Approval\Entities\ApprovalReference;

class SpkController extends Controller
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
        $spk = Spk::where('project_id',$project->id)->orderBy('id','desc')->get();
        return view('spk::index',compact("user","project","spk"));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create(Request $request)
    {   

        $tender = Tender::find($request->id);
        $project = Project::find($request->session()->get('project_id'));
        if ($tender->kelas_id == 1) {
            $no =  \App\Helpers\Document::new_number('SPK-TL', $tender->rab->workorder->department_from,$project->id);
            $is_instruksilangsung = TRUE;
        }elseif ($tender->kelas_id == 2){
            $no = \App\Helpers\Document::new_number('SPK-RO', $tender->rab->workorder->department_from,$project->id);
            $is_instruksilangsung = FALSE;
        }elseif ($tender->kelas_id == 3){
            $no = \App\Helpers\Document::new_number('SPK-TDR', $tender->rab->workorder->department_from,$project->id);
            $is_instruksilangsung = FALSE;
        }

        $itempekerjaan = Itempekerjaan::find($tender->rab->pekerjaans->last()->itempekerjaan->parent->id);
        // $budget = $tender->rab;
        if ( $tender->rab->project_kawasan_id == NULL ){
            $project_kawasan_id = NULL;
        }else{
            $project_kawasan_id = $tender->rab->project_kawasan_id;
        }

        $spk = new Spk;
        $spk->project_id = $tender->project->id;
        $spk->no = $no.$tender->rab->pt->code;
        $spk->rekanan_id = $tender->menangs->first()->tender_rekanan->rekanan->group->id;
        $spk->tender_rekanan_id = $tender->menangs->first()->tender_rekanan->id;
        $spk->name = $itempekerjaan->name;
        $spk->is_instruksilangsung = $is_instruksilangsung;
        $spk->start_date = date("Y-m-d H:i:s.u");
        $durasi_st1 = Globalsetting::where('parameter','serah_terima_1')->first();
        $spk->finish_date = date('Y-m-d', strtotime('+'.$tender->durasi.' day', strtotime($spk->start_date))); 
        $spk->st_1 = date('Y-m-d', strtotime('+'.$durasi_st1->value.' day', strtotime($spk->finish_date)));
        foreach ($tender->retensi as $key => $value) {
            if ( $key == 0 ){
                $spk->st_2 = date('Y-m-d', strtotime('+'.$value->hari.' day', strtotime($spk->st_1)));
            }else{}
            if ( $key > 0 ){
                $spk->st_3 = date('Y-m-d', strtotime('+'.$value->hari.' day', strtotime($spk->st_1)));
            }else{}
        }
        $spk->created_by = \Auth::user()->id;
        $spk->date = date("Y-m-d H:i:s.u");
        $spk->coa_pph_default_id =  $tender->menangs->first()->tender_rekanan->rekanan->group->pph_percent;
        $spk->pph_rekanan_id =  $tender->menangs->first()->tender_rekanan->rekanan->group->pph_rekanan_id;
        $spk->project_kawasan_id = $project_kawasan_id;
        if ( $tender->aanwijing == "" ){
            $spk_denda_a = 0;
        }else{
            $spk->denda_a = $tender->aanwijing->denda;
        }
        $spk->spk_type_id = 2;
        if($tender->menangs->first()->tender_rekanan->rekanan->group->pkp_status == 1){
            $spk->ppn = 1;
        }else{
            $spk->ppn = 0;
        }
        $spk->start_date_real = date("Y-m-d H:i:s.u");
        $spk->finish_date_real = date('Y-m-d', strtotime('+'.$tender->durasi.' day', strtotime($spk->start_date))); 
        $spk->save();

        foreach ($spk->tender_rekanan->menangs->first()->details as $key => $nilai) {
            # code...
            $itempekerjaan = \Modules\Pekerjaan\Entities\Itempekerjaan::find($nilai->itempekerjaan_id);
            if($itempekerjaan->ipkDefault->count() != 0){
                foreach ($spk->tender->units as $key => $value) {
                    # code...
                    foreach ($itempekerjaan->ipkDefault as $key => $nilai2) {
                        $ipk = new IpkTambahan;
                        $ipk->name = $nilai2->name;
                        $ipk->status = 0;
                        $ipk->spk_id = $spk->id;
                        $ipk->itempekerjaan_id = $nilai2->itempekerjaan_id;
                        $ipk->ipkdefault_id = $nilai2->id;
                        $ipk->unit_id = $value->id;
                        $ipk->save();  
                    }

                    foreach ($itempekerjaan->progressDefault as $key => $nilai2) {
                        $progress = new ProgressTambahan;
                        $progress->name = $nilai2->name;
                        $progress->volume = 0;
                        $progress->spk_id = $spk->id;
                        $progress->status = 0;
                        $progress->itempekerjaan_id = $nilai2->itempekerjaan_id;
                        $progress->satuan = $nilai2->satuan;
                        $progress->progressdefault_id = $nilai2->id;
                        $progress->unit_id = $value->id;
                        $progress->save();  
                    }
                }              
            }
        }

        /* Save Unit */
        foreach ($tender->units as $key => $value) {
            $spkdetail = new SpkDetail;
            $spkdetail->spk_id = $spk->id;
            $spkdetail->asset_id = $value->rab_unit->asset_id;
            $spkdetail->asset_type = $value->rab_unit->asset_type;
            $spkdetail->created_by = \Auth::user()->id;
            $spkdetail->save();


            $tender_menang = $tender->menangs->first();
            foreach ($tender_menang->tender_rekanan->penawarans->last()->details as $key2 => $value2) {
                $unit_progress = \Modules\Project\Entities\UnitProgress::where('unit_id', $value->asset_id )->where('unit_type', $value->asset_type )->where('itempekerjaan_id', $value2->itempekerjaan_id )->first();
                if ( $unit_progress == NULL ){
                    if ( $value2->rab_pekerjaan != "" ){
                        $unit_progress = new UnitProgress;
                        $unit_progress->project_id = $tender->project->id;
                        $unit_progress->unit_id = $value->id;
                        $unit_progress->unit_type = "Modules\Tender\Entities\TenderUnit";
                        $unit_progress->itempekerjaan_id = $value2->rab_pekerjaan->itempekerjaan_id;
                        $unit_progress->urutitem = $key2+1;
                        $unit_progress->termin = $key2+1;
                        $unit_progress->is_pembangunan = TRUE;
                        $unit_progress->progresslapangan_percent = 0;
                        $unit_progress->progressbap_percent = 0;
                        $unit_progress->nilai = $value2->nilai;
                        $unit_progress->volume = $value2->volume;
                        $unit_progress->satuan = $value2->satuan;
                        $unit_progress->total_nilai = $value2->total_nilai;
                        $unit_progress->save();

                        $SpkvoUnit = new SpkvoUnit;
                        $SpkvoUnit->head_id = $spk->id;
                        $SpkvoUnit->spk_detail_id = $spkdetail->id;
                        $SpkvoUnit->head_type = "Modules\Spk\Entities\Spk";
                        $SpkvoUnit->unit_progress_id = $unit_progress->id;
                        $SpkvoUnit->nilai = $value2->nilai;
                        $SpkvoUnit->volume = $value2->volume;
                        $SpkvoUnit->satuan = $value2->satuan;
                        $SpkvoUnit->ppn = $value2->rab_pekerjaan->itempekerjaan->ppn;
                        $SpkvoUnit->total_nilai = $value2->total_nilai;
                        $SpkvoUnit->save();  
                    }                    
                }
            }
        }

        foreach ($tender->termyn as $key => $value) {
            $spk_termyn = SpkTermyn::find($value->id);
            $spk_termyn->spk_id = $spk->id;
            if ( $key == 0 ){
                $spk_termyn->status = 1;
            }else{
                $spk_termyn->status = 0;
            }
            $spk_termyn->save();

            if ( $key == 0 ){
                $spk_dp_val = Spk::find($spk->id);
                $spk_dp = Spk::find($spk->id);
                $spk_dp->dp_nilai = (int)(( $value->termin / 100 ) * $spk_dp_val->nilai);
                $spk_dp->dp_percent = $value->termin;
                $spk_dp->save();
            }
        }

        foreach ($tender->retensi as $key => $value) {
            $retensi = SpkRetensi::find($value->id);
            $retensi->spk_id = $spk->id;
            $retensi->is_progress = 1;
            $retensi->save();

            // // $spk = Spk::find($spk->id);        
            // // $spk->st_2 = date('Y-m-d', strtotime('+'.$value->hari.' day', strtotime($spk->st_1)));
            // // $spk->save();

            // if ( $key > 0 ){
            //     $spk = Spk::find($spk->id);
            //     $spk->st_3 = date('Y-m-d', strtotime('+'.$value->hari.' day', strtotime($spk->st_1)));
            //     $spk->save();
            // }
        }

        foreach ($tender->pengembalian_dp as $key => $value) {
            $pengembalian = SpkPengembalianDp::find($value->id);
            $pengembalian->spk_id = $spk->id;
            $pengembalian->save();
        }

        // $erems = \Config::get('constants.options.erems');
        // if ( $erems == 1 ){ 
        //     $pt = Pt::find($spk->tender->pt->id);
        //     $authuser = \Auth::user();

        //     //Insert ke kontaktor
        //     $rekanan = $tender->menangs->first()->tender_rekanan->rekanan;

        //     // insert into [dbo].[m_contractor] (project_id,pt_id,code,contractorname,address,telp,fax,mobile_phone,npwp,contact_person,email,PIC,city_id,kodepos,country_id,Addon,Addby,Active,Deleted) values (4065,38,'xyz','pt.xyz','Kp.Sawah Baru No.15 Rt 2/11','0867876',null,null,null,null,'4aghiwardani8@gmail.com','Aghi Wardani',0,null,0,null,null,1,0)
        //     // $time = strtotime(date("Y-m-d H:i:s.000"));
        //     // $newformat = date('Y-m-d',$time);
        //     $ins_erem = DB::connection('sqlsrv3')->insert('insert into [dbo].[m_contractor] (project_id,pt_id,code,contractorname,address,telp,fax,mobile_phone,npwp,contact_person,email,PIC,city_id,kodepos,country_id,Addby,Active,Deleted) values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)', [
        //         $project->project_id, 
        //         $pt->pt_id,
        //         'xxx',
        //         $rekanan->name,
        //         $rekanan->surat_alamat,
        //         $rekanan->telp,
        //         $rekanan->fax,
        //         $rekanan->cp_whatsap,
        //         $rekanan->group->npwp_no,
        //         $rekanan->cp_name,
        //         $rekanan->email,
        //         $rekanan->saksi_name,
        //         0,
        //         $rekanan->surat_kodepos,
        //         87,
        //         $authuser->user_id,
        //         1,
        //         0
        //         ]
        //     ); 
        //     $get_last = DB::connection('sqlsrv3')->select(DB::raw("SELECT MAX(contractor_id) as contractor_id FROM dbo.m_contractor"));
            
        //     $kontaktor_id = NULL;     
        //     if ( $get_last[0]->contractor_id != "" ){
        //         $kontaktor_id = $get_last[0]->contractor_id;
        //     }

        //     // $rekanan_project_pt = new RekananProjectPt;
        //     // $rekanan_project_pt->project_id = $project->id;
        //     // $rekanan_project_pt->pt_id = $pt->id;
        //     // $rekanan_project_pt->vendor_id = $kontaktor_id;
        //     // $rekanan_project_pt->created_by = \Auth::user()->id;
        //     // $rekanan_project_pt->created_at = date("Y-m-d H:i:s.000");
        //     // $rekanan_project_pt->save();

        //     $ins_erem = DB::connection('sqlsrv3')->insert('insert into [dbo].[th_spk] (project_id,pt_id,contractor_id,spk_no,spk_date,time_duration,started,ended,job_fee,job_title,description,status,status_note,spktype_id,progress,Addon,jumlah_unit,serahterima_date1,serahterima_date2,serahterima_date3) values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)', [
        //         $project->project_id, 
        //         $pt->pt_id,
        //         $kontaktor_id,
        //         $spk->no,
        //         $spk->date,
        //         $spk->tender->durasi,
        //         $spk->start_date,
        //         $spk->st_1,
        //         $spk->nilai,
        //         $spk->name,
        //         $spk->description,
        //         'OPEN',
        //         '',
        //         1,
        //         0,
        //         $authuser->user_id,
        //         count($spk->details),
        //         $spk->st_1,
        //         $spk->st_2,
        //         $spk->st_3
        //         ]
        //     ); 
        // }
        
        return redirect("/spk/detail?id=".$spk->id);
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $tender_menang = TenderMenang::find($request->id);
        $detail = $tender_menang->details;
        
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show(Request $request){
        $terbilang = new Terbilang();
        $user = \Auth::user();
        // return $user;
        $project = Project::find($request->session()->get('project_id'));
        $spk = Spk::find($request->id);

        // if ($spk->id == 3619) {
        //     # code...
        //     foreach ($spk->tender->termyn as $key => $value) {
        //         $spk_termyn = SpkTermyn::find($value->id);
        //         $spk_termyn->spk_id = $spk->id;
        //         if ( $key == 0 ){
        //             $spk_termyn->status = 1;
        //         }else{
        //             $spk_termyn->status = 0;
        //         }
        //         $spk_termyn->save();
    
        //         if ( $key == 0 ){
        //             $spk_dp_val = Spk::find($spk->id);
        //             $spk_dp = Spk::find($spk->id);
        //             $spk_dp->dp_nilai = (int)(( $value->termin / 100 ) * $spk_dp_val->nilai);
        //             $spk_dp->dp_percent = $value->termin;
        //             $spk_dp->save();
        //         }
        //     }
    
        //     foreach ($spk->tender->retensi as $key => $value) {
        //         $retensi = SpkRetensi::find($value->id);
        //         $retensi->spk_id = $spk->id;
        //         $retensi->is_progress = 1;
        //         $retensi->save();
        //     }
        // }


        $spktype = SpkType::get();
        $progress = $spk->progresses->first()->itempekerjaan->item_progress;
        $termyn = array();
        $ttd_pertama = "";
        $ttd_kedua = "";
        $tmp_ttd_pertama = array();
        $start = 0;
        $jabatan = "";        
        if($spk->ppn == 1){
            $ppn = Globalsetting::where("parameter","ppn")->first()->value;
        }else{
            $ppn = 0;
        }

        $list_ttd_bap = array(
            "qs" => array("username" => "", "jabatan" => ""),
            "5" => array("username" => "", "jabatan" => ""),
            "6" => array("username" => "", "jabatan" => ""),
            "7" => array("username" => "", "jabatan" => "")
        );

        foreach ($progress as $key => $value) {
            $termyn[$key] = 0 ;
        }

        if ( $spk->approval != "" ){
            if ( $spk->approval->histories->count() > 0 ){
                // $ttd_pertama = $spk->approval->histories->min("no_urut");

                // $globalsetting = \Modules\Globalsetting\Entities\Globalsetting::where('parameter','tunjuk_langsung_approval')->first();
                // if ($globalsetting) // maka ini adalah non-tender atau penunjukan
                // {
                //     $max_value_multiplier = $globalsetting->value;
                // }else{
                //     $max_value_multiplier = 1;
                // }
                // $type = $spk->tender->tender_type->id;

                // if ( $type == 1 ){
                //     $nilai = ($spk->nilai + (($spk->nilai * $ppn ) / 100))/$max_value_multiplier;
                // }else{
                //     $nilai = $spk->nilai + (($spk->nilai * $ppn ) / 100);
                // }
                // // $nilai = $spk->nilai + (($spk->nilai * $ppn ) / 100));
                // $approval_references = \Modules\Approval\Entities\ApprovalReference::where('document_type', "Spk")
                //     ->where('project_id', $project->id)
                //     //->where('pt_id', $pt_id )
                //     ->where('min_value', '<=', $nilai)
                //     //->where('max_value', '>=', $approval->total_nilai)
                //     ->orderBy('no_urut', 'ASC')
                //     ->get();
                // if($project->id == 1){
                //     return $approval_references;
                // }
                // // foreach ($spk->approval->histories as $key => $value) {
                // //     $user = User::find($value->user_id);
                // //     foreach ($value->user->jabatan as $key3 => $value3) {
                // //         if ( $value3['pt_id'] == $spk->tender->rab->pt->id ){
                // //             $jabatan = $value3['jabatan'];
                // //         }
                // //     }
                // //     /*$max = $user->approval_reference;
                // //     foreach ($user->approval_reference as $key2 => $value2) {
                // //         if ( $value2->min_value <= $spk->nilai && $value2->project_id == $spk->project->id && $value2->document_type == "Spk"){
                // //             $tmp_ttd_pertama[$start] = array( "level" => $value2->no_urut, "user_name" => ucwords($value2->user->user_name), "user_jabatan" => ucwords($value2->user->jabatan[0]["jabatan"]) );
                // //             $start++;
                // //         }
                // //     }*/
                // //     $tmp_ttd_pertama[$start] = array( "level" => $value->no_urut, "user_name" => ucwords($value->user->user_name), "user_jabatan" => ucwords($jabatan) );
                // //     $start++;
                // // } 
                
                // foreach ($approval_references as $key => $value) {
                //     $user = User::find($value->user_id);
                    
                //     foreach ($value->user->jabatan as $key3 => $value3) {
                //         if ( $value3['pt_id'] == $spk->tender->rab->pt->id ){
                //             $jabatan = $value3['jabatan'];
                //         }
                //     }
                //     $tmp_ttd_pertama[$start] = array( "level" => $value->no_urut, "user_name" => ucwords($value->user->user_name), "user_jabatan" => ucwords($jabatan) );
                //     $start++;
                // } 
                
                // $ttd_pertama = min($tmp_ttd_pertama);

                // if ( $ttd_pertama["level"] < 5 ){
                //     $list_ttd[0] = array("user_name" => $ttd_pertama["user_name"], "user_jabatan" => $ttd_pertama["user_jabatan"]);            
                //     $list_ttd[1] = array("user_name" => $tmp_ttd_pertama[1]["user_name"], "user_jabatan" => $tmp_ttd_pertama[1]["user_jabatan"]);
                //     foreach ($tmp_ttd_pertama as $key => $value) {
                //         if ( $value["level"] == 5 ){
                //             $list_ttd[2] = array("user_name" => $tmp_ttd_pertama[$key]["user_name"], "user_jabatan" => $tmp_ttd_pertama[$key]["user_jabatan"]);
                //         }
                //     }  
                // }else{
                //     $list_ttd[0] = array("user_name" => $ttd_pertama["user_name"], "user_jabatan" => $ttd_pertama["user_jabatan"]);  
                //     $start = 1;          
                //     foreach ($tmp_ttd_pertama as $key => $value) {
                //         if ( $value["level"] > 5 ){
                //             $list_ttd[$start] = array("user_name" => $tmp_ttd_pertama[$start]["user_name"], "user_jabatan" => $tmp_ttd_pertama[$start]["user_jabatan"]);
                //             $start++;
                //         }
                //     } 
                // }
            }
        }

        $sipp = "";
        foreach ($spk->tender->rekanans as $key => $value) {
            foreach ($value->korespondensis as $key2 => $value2) {
                if ( $value2->type == "sipp"){
                    $sipp = $value2->no;
                }
            }
        }

        $start = 0;
        $user_pic = array();
        $users = User::where("is_pic",1)->get();
        foreach ($users as $key => $value) {
            foreach ($value->jabatan as $key2 => $value2) {
                // printf($value2);
                // echo("<br>");
                if ( $value2['project_id'] == $spk->project->id && $value->is_pic == 1 && $value2['pt_id'] == $spk->tender->rab->pt->id){
                    $user_pic[$start] = array(
                        'user_name' => $value->user_name,
                        'user_id' => $value->id
                    );
                    $start++;
                }
            }
        }
        
        $nilai_bap = 0;
        $before = 0;
        foreach ($spk->baps as $key => $value) {
            $nilai_bap = $nilai_bap + ($value->nilai_bap_2 - $before);
            $before = $value->nilai_bap_2;
        }


        if ( $spk->approval != "" ){
            foreach ($tmp_ttd_pertama as $key => $value) {
                $list_ttd_bap[$value['level']]  = array('username' => $value['user_name'], 'jabatan' => $value['user_jabatan']);         
            }            
        }

        if (!("./assets/spk/".$spk->id)) {
            mkdir("./assets/spk/".$spk->id,0777);
        }
        date_default_timezone_set("Asia/Jakarta");
        setLocale(LC_ALL, 'id');
        $tanggal= \Carbon\Carbon::parse(date("y-m-d"))->formatLocalized('%d %B %Y');

        // return $spk->project->project;

        setLocale(LC_ALL, 'id');
        $tanggal = \Carbon\Carbon::parse($spk->tender->created_at)->formatLocalized('%d %B %Y');
        $tanggal_mengikat = \Carbon\Carbon::parse($spk->finish_date)->formatLocalized('%d %B %Y');

        $step = count($spk->tender->menangs->first()->tender_rekanan->penawarans);
        $pph_rekanan = PphRekanan::get();
        $user = \Auth::user();

        // if ($spk->spk_id != null) {
        //     # code...
        //     $get_spk = DB::connection('sqlsrv3')->table('dbo.th_spk')->where("spk_id", $spk->spk_id)->orderby("spk_id","Desc")->select("spk_id")->get();
        // }

        return view('spk::create',compact("project","user","spk","spktype","termyn","list_ttd_bap","ppn","sipp","user_pic","nilai_bap",'list_ttd_bap','step','tanggal','terbilang','tanggal','tanggal_mengikat','pph_rekanan'));
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function editdate(Request $request)
    {
        $spk = Spk::find($request->spk_id);
        if(date('d/M/Y', strtotime($spk->start_date)) != $request->start_date){
            // $spk->start_date = date_format(date_create($request->start_date),"Y-m-d");
            $spk->start_date = date("Y-m-d",strtotime($request->start_date));
        }
        $durasi_st1 = Globalsetting::where('parameter','serah_terima_1')->first();
        $spk->finish_date = date('Y-m-d', strtotime('+'.$spk->tender->durasi.' day', strtotime($spk->start_date))); 
        $spk->st_1 = date('Y-m-d', strtotime('+'.$durasi_st1->value.' day', strtotime($spk->finish_date)));
        $pph_rekanan = PphRekanan::find($request->coa_pph);
        $spk->coa_pph_default_id = $pph_rekanan->nilai;
        $spk->name = $request->spk_name;
        $spk->spk_type_id = 2;
        $spk->finish_date_real = date("Y-m-d",strtotime($request->end_date));
        $spk->pph_rekanan_id = $pph_rekanan->id;
        $spk->start_date_real = date("Y-m-d H:i:s.u");
        $spk->finish_date_real = date('Y-m-d', strtotime('+'.$spk->tender->durasi.' day', strtotime($spk->start_date))); 
        $spk->save();

        $spk_set_retensi = Spk::find($spk->id);
        foreach ($spk->retensis as $key => $value) {
            if ( $key == 0 ){
                echo $value->hari;
                $spk->st_2 = date('Y-m-d', strtotime('+'.$value->hari.' day', strtotime($spk->st_1)));
                $spk->save();    
            }      

            if ( $key > 0 ){
                echo $value->hari;
                $spk = Spk::find($spk->id);
                $spk->st_3 = date('Y-m-d', strtotime('+'.$value->hari.' day', strtotime($spk->st_1)));
                $spk->save();
            }
        }
        return redirect("/spk/detail?id=".$request->spk_id);
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request)
    {
        $spk = Spk::find($request->spk_id);
        $spk->start_date = $request->start_date;
        $spk->finish_date = $request->end_date;
        $spk->st_1 = $request->end_date;
        $spk->name = $request->spk_name;
        $spk->start_date_real = $request->start_date;
        $spk->finish_date_real = date('Y-m-d', strtotime('+'.$spk->tender->durasi.' day', strtotime($request->start_date))); 
        $spk->save();

        $spk_set_retensi = Spk::find($spk->id);
        foreach ($spk->retensis as $key => $value) {
            if ( $key == 0 ){
                echo $value->hari;
                $spk->st_2 = date('Y-m-d', strtotime('+'.$value->hari.' day', strtotime($spk->st_1)));
                $spk->save();    
            }      

            if ( $key > 0 ){
                echo $value->hari;
                $spk = Spk::find($spk->id);
                $spk->st_3 = date('Y-m-d', strtotime('+'.$value->hari.' day', strtotime($spk->st_1)));
                $spk->save();
            }
        }

        return redirect("/spk/detail?id=".$spk->id);
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy()
    {
    }

    public function editpayment(Request $request){
        $spk = Spk::find($request->spk_id);
        if ( $request->denda_a != "" ){            
            $spk->denda_a = str_replace(",","",$request->denda_a);
        }

        if ( $request->denda_b != "" ){
            $spk->denda_b = str_replace(",","",$request->denda_b);
        }
        $spk->matauang = $request->matauang;
        $spk->nilai_tukar = $request->nilai_tukar;
        $spk->jenis_kontrak = $request->jenis_kontrak;
        $spk->memo_cara_bayar = $request->memo_cara_bayar;
        $spk->memo_lingkup_kerja = $request->memo_lingkup_kerja;
        $spk->carapembayaran = $request->carapembayaran;
        $spk->garansi_nilai = $request->garansi_nilai;
        $spk->coa_pph_default_id = $request->coa_pph;
        $spk->save();
        return redirect("/spk/detail?id=".$request->spk_id);
    }

    public function termyn (Request $request){

        /*foreach ($request->termyn as $key => $value) {
            $spk_termyn = new SpkTermyn;
            $spk_termyn->spk_id = $request->spk_termin_id;
            $spk_termyn->termin = $key + 1 ;
            $spk_termyn->progress = $request->termyn[$key];
            if ( $key == 0 ){
                $spk_termyn->status = 1 ;
            }
            $spk_termyn->save();
        }*/
        $termyn = array();
        $spk = Spk::find($request->spk_termin_id);
        $item_progress = $spk->progresses->last()->itempekerjaan->item_progress;
        foreach ($item_progress as $key => $value) {
            $termyn[$key] = "0";
        }
        
        $progress = $spk->progresses;
        foreach ($progress as $key => $value) {
            foreach ($value->itempekerjaan->item_progress as $key2 => $value2) {
                if ( $value2->percentage == null ){
                    $termyn[$key2] = $termyn[$key2] + 0;
                }else{
                    $termyn[$key2] = $termyn[$key2] + $value2->percentage;
                }
            }
        }
        foreach ($termyn as $key3 => $value3) {
            $termyn[$key3] = $termyn[$key3] / $spk->details->count();
            $spk_termyn = new SpkTermyn;
            $spk_termyn->spk_id = $request->spk_termin_id;
            $spk_termyn->termin = $key3; 
            $spk_termyn->progress = $termyn[$key3] ;
            if ( $key3 == 0 ){
                $spk_termyn->status = 1 ;
            }
            $spk_termyn->save();
        }
        
        return redirect("/spk/detail?id=".$request->spk_termin_id);
    }

    public function approval(Request $request){
        $project = Project::find($request->session()->get('project_id'));
        $budget = $request->id;
        $class  = "Spk";
        $spk = Spk::find($request->id);
        if($spk->approval == null){
            $approval = \App\Helpers\Document::make_approval('Modules\Spk\Entities\Spk',$budget);
        }
        $tender = $spk->tender;
        // return $tender->menangs->first()->tender_rekanan->rekanan;
        $erems = \Config::get('constants.options.erems');
        $jumlahunit =  count($spk->details->where("asset_type", "Modules\Project\Entities\Unit"));
        $date = date("Y-m-d H:i:s");
        if ( $erems == 1 ){ 
            if($jumlahunit != 0){
                $pt = Pt::find($spk->tender->pt->id);
                $authuser = \Auth::user();

                //Insert ke kontaktor
                $rekanan = $tender->menangs->first()->tender_rekanan->rekanan;

                if($rekanan->group != null){
                    if($rekanan->group->code != null){
                        $code = $rekanan->group->code;
                    }else{
                        $code = "xxx";
                    }
                }else{
                    $code = "xxx";
                }
                $ins_erem = DB::connection('sqlsrv3')->insert('insert into [dbo].[m_contractor] (project_id,pt_id,code,contractorname,address,telp,fax,mobile_phone,npwp,contact_person,email,PIC,city_id,kodepos,country_id,Addon,Addby,Active,Deleted) values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)', [
                    $project->project_id, 
                    $pt->pt_id,
                    $code,
                    $rekanan->name,
                    $rekanan->surat_alamat,
                    $rekanan->telp,
                    $rekanan->fax,
                    $rekanan->cp_whatsap,
                    $rekanan->group->npwp_no,
                    $rekanan->cp_name,
                    $rekanan->email,
                    $rekanan->saksi_name,
                    0,
                    $rekanan->surat_kodepos,
                    87,
                    date("Y-m-d H:i:s",strtotime($date)),
                    9999,
                    1,
                    0
                    ]
                ); 
                // $get_last = DB::connection('sqlsrv3')->where("project_id", $project->project_id)->where("pt_id", $pt->pt_id)->where("contractorname", $rekanan->name)->select(DB::raw("SELECT MAX(contractor_id) as contractor_id FROM dbo.m_contractor"));
                
                $get_last = DB::connection('sqlsrv3')->table('dbo.m_contractor')->where("project_id", $project->project_id)->where("pt_id", $pt->pt_id)->where("contractorname", $rekanan->name)->select("contractor_id")->orderby("contractor_id","Desc")->get();

                $kontaktor_id = NULL;     
                if ( $get_last[0]->contractor_id != "" ){
                    $kontaktor_id = $get_last[0]->contractor_id;
                }

                if($spk->details[0]->asset_type == 'Modules\Project\Entities\Unit'){
                    $typespk = 2;
                }else{
                    $typespk = 1;
                }

                $ins_erem = DB::connection('sqlsrv3')->insert('insert into [dbo].[th_spk] (project_id,pt_id,contractor_id,code,spk_no,spk_date,time_duration,started,ended,job_fee,job_title,description,status,status_note,spktype_id,progress,Addon,Addby,jumlah_unit,serahterima_date1,serahterima_date2,serahterima_date3) values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)', [
                    $project->project_id, 
                    $pt->pt_id,
                    $kontaktor_id,
                    $spk->no,
                    $spk->no,
                    $spk->date,
                    $spk->tender->durasi,
                    $spk->start_date,
                    $spk->st_1,
                    $spk->nilai,
                    $spk->name,
                    $spk->tender->rab->name,
                    'OPEN',
                    '',
                    $typespk,
                    0,
                    date("Y-m-d H:i:s",strtotime($date)),
                    9999,
                    $jumlahunit,
                    $spk->st_1,
                    $spk->st_2,
                    $spk->st_3
                    ]
                ); 
                
                $get_spk = DB::connection('sqlsrv3')->table('dbo.th_spk')->where("project_id", $project->project_id)->where("pt_id", $pt->pt_id)->where("spk_no", $spk->no)->orderby("spk_id","Desc")->select("spk_id")->get();

                $kontaktor_id = NULL;     
                if ( $get_last[0]->contractor_id != "" ){
                    $spk_id = $get_spk[0]->spk_id;
                }

                $spk_update = Spk::find($request->id);
                $spk_update->spk_id = $spk_id;
                $spk_update->save();
                
                foreach ($spk->details as $key => $value) {
                    # code...
                    $spk_detail = DB::connection('sqlsrv3')->insert('insert into [dbo].[td_spkdetail] (spk_id,unit_id,progress,Addon,Addby,Active,Deleted) values (?,?,?,?,?,?,?)', [
                        $spk_id, 
                        $value->asset->unit_id,
                        0,
                        date("Y-m-d H:i:s",strtotime($date)),
                        9999,
                        1,
                        0,
                        ]
                    ); 
                }
            }
        }

        $pph_rekanan = PphRekanan::find($request->pph);
        $spk_coapph = Spk::find($request->id);
        $spk_coapph->coa_pph_default_id = $pph_rekanan->nilai;
        $spk_coapph->pph_rekanan_id = $pph_rekanan->id;
        $spk_coapph->save();
        
        return response()->json( ["status" => "0"] );
    }

    public function termyndetail(Request $request){

        $spk = Spk::find($request->spk_termin_id);
        foreach ($spk->termyn as $key => $value) {
            $spk_termyn = new SpkTermynDetail;
            $spk_termyn->spk_termyn_id = $value->id;
            $spk_termyn->item_pekerjaan_id = $request->item_id ;
            $spk_termyn->percentage = $request->termyn[$key];
            $spk_termyn->termyn = $key + 1 ;
            $spk_termyn->created_by = \Auth::user()->id;
            $spk_termyn->save();
        }
        return redirect("/spk/detail?id=".$spk->id);
    }

    public function updatetermyn(Request $request){
        return response()->json( ["status" => "0"]);
    }

    public function addbap(Request $request){
        $spk = Spk::find($request->id);
        // $date_last_progress = 0;
        // return $spk->progresses[1]->details;
        $user = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        if ( $spk->baps->count() == "0" ){
            $nilai_sebelumnya = 0;
            $pembayaran_saat_ini = 0;
        }else{
            $nilai_sebelumnya = $spk->baps->last()->nilai_bap_3 ;
            $pembayaran_saat_ini = $spk->baps->last()->nilai_pembayaran_saat_ini;
        }
        
        if ( $spk->ppn == 1 ){
            $ppn = Globalsetting::where("parameter","ppn")->first()->value / 100;
        }else{
            $ppn  = 0;
        }
        $item_pekerjaan = Itempekerjaan::find($spk->tender->rab->pekerjaans->last()->itempekerjaan->parent->id);
        
        $termin_progress = $spk->termyn->where('status',3)->sum('termin');
        // return $termin_progress;
        if( $spk->progresses->sum('progresslapangan_percent')  == "0.0" && ($spk->termyn[0]->status != 3)){
            $status_submit = 1;
            $persen_dibayarkan = $spk->dp_percent;
        }elseif($spk->lapangan == 100){
            if (count($spk->baps) != null) {
                if($spk->baps->last()->st_status == 1){
                    $status_submit = 0;
                }else{
                    $status_submit = 1;
                }
            } else {
                $status_submit = 1;
            }
            

            $persen_dibayarkan = $spk->lapangan - $spk->progress_sebelumnya;
        }else{
            // return $spk->termyn->where('status','!=',3);
            if($spk->termyn->where('status','!=',3)->count() != 0){
                if($spk->termyn->where('status','!=',3)->count() == 1 && $spk->lapangan != 100){
                    $status_submit = 0;
                    $persen_dibayarkan = 0;
                }else{
                    foreach ($spk->termyn->where('status','!=',3) as $key => $value) {
                        # code...
                        $termin_progress += $value->termin;
                        // echo($value->termin);
                        // echo("<br>");
                        // echo($termin_progress." lapangan");
                        // echo("<br>");
                        if($termin_progress <= $spk->lapangan){
                            // return $termin_progress;
                            $status_submit = 1;
                            $persen_dibayarkan = $value->termin;
                            break;
                        }else{
                            $status_submit = 0;
                            $persen_dibayarkan = 0;
                        }
                    }
                }
            }else{
                $status_submit = 0;
                $persen_dibayarkan = 0;
            }
        }
        // return $termin_progress;
        $date_retensi = null;
        $date = date("Y-m-d");
        $percent_retensi = 0;
        $st_status = 0;
        $pembayaran_saat_ppnpph = 0;
        if($spk->baps->count() != 0){
            if($spk->baps->last()->st_status == 0 && $spk->progress_sebelumnya == 100){
                $st_status = 1;
            }elseif(1 <= $spk->baps->last()->st_status){
                $st_status = $spk->baps->last()->st_status;
                $date_stsebelum = $spk->finish_date_real;
                $pembayaran_saat_ppnpph = 0;
                $status_submit = 0;
                foreach ($spk->retensis as $key => $value2) {
                    # code...
                    $date_st = date('Y-m-d', strtotime('+'.$value2->hari.' day', strtotime($spk->start_date_real)));
                    // printf($date_st);
                    // echo("<br/>");
                    // printf($date);
                    if($date_st <= $date){
                        $st_status++;
                        if($key == $spk->baps->last()->st_status-1){
                            $percent_retensi = $value2->percent * (1/$spk->retensis->sum('percent'));
                            $pembayaran_saat_ppnpph = $spk->baps->where('st_status',1)->first()->nilai_pembayaran_saat_ini;
                            $status_submit = 1;
                            break;
                        }
                    }
                }
            }
        }
        
        if(1 <= $spk->termyn->where('status','!=',3)->count() && $spk->lapangan != 100 && $spk->lapangan != 0){
            $jumlah_termyn = 0;
            $termyn =[];
            for ($i=0; $i < 10; $i++) { 
                # code...
                foreach ($spk->tender_rekanan->menangs->first()->details as $key => $value){
                    $itempekerjaan_termin = Itempekerjaan::find($value->itempekerjaan_id)->item_progress->where("termyn",$i+1)->first();
                    
                    if($itempekerjaan_termin != null){
                        $persen_itempekerjaan = (($spk->progresses->where("itempekerjaan_id",$itempekerjaan_termin->item_pekerjaan_id)->first()->bobot_rab * ($spk->details->count())) /100)* $itempekerjaan_termin->percentage;
                    }else{
                        $persen_itempekerjaan = 0;
                    }
                    $jumlah_termyn = $jumlah_termyn + $persen_itempekerjaan;
                }
                $termyn[$i] = $jumlah_termyn;
                if($jumlah_termyn == 100){
                    break;
                }
            }
            
            $nilai = $spk->nilai_vo + $spk->nilai;
            $vo = NewVo::where("spk_id",$spk->id)->get();

            // return $nilai;
            // return $termyn;
            $total_termyn_itempekerjaan = 0;
            $total_termyn_itempekerjaan_sebelum = 0;
            foreach ($termyn as $key => $value2) {
                # code...
                $total_termyn_itempekerjaan = $total_termyn_itempekerjaan + $value2;
                // printf($total_termyn_itempekerjaan);
                // echo("<br/>");
                // printf($value2);
                // echo("<br/>");
                // return $termin_progress;
                if($total_termyn_itempekerjaan_sebelum <= $termin_progress && $termin_progress <= $total_termyn_itempekerjaan){
                    $j = $key;
                    // return $j;
                    // foreach ($spk->tender_rekanan->menangs->first()->details as $key => $value){
                        
                        foreach ($spk->tender->units as $key => $kunci1) {
                            # code...
                            $percent_item_vo = 0;
                            $volume_item_vo = 0;
                            foreach ($kunci1->unit_progress as $key => $kunci2) {
                                if ( $kunci2->spkvo_unit != ""){
                                    if ( $kunci2->spkvo_unit->head_type == "Modules\Spk\Entities\Spk" ){
                                        if($kunci2->volume != 0){
                                            $volume_item_vo = 0;
                                            foreach ($vo as $key => $kunci3) {
                                                # code...
                                                $volume_item_vo = $volume_item_vo + $kunci3->detail->where("unit_id",$kunci2->unit_id)->where("itempekerjaan_id",$kunci2->itempekerjaan_id)->sum("volume");
                                            }
                                            
                                            $percent_item_vo = 0;
                                            foreach ($vo as $key => $kunci3) {
                                                # code...
                                                $item_vo =  $kunci3->detail->where("unit_id",$kunci2->unit_id)->where("itempekerjaan_id",$kunci2->itempekerjaan_id)->first();
                                                
                                                if(isset($item_vo)){
                                                    $percent_item_vo =  $percent_item_vo + (($item_vo->volume*($item_vo->progresslapangan_percent/100))/($kunci2->volume + $volume_item_vo)*100);
                                                }
                                            }

                                            $percent_saat_ini = $percent_item_vo + (($kunci2->volume*($kunci2->progresslapangan_percent/100))/($kunci2->volume + $volume_item_vo)*100);
                                            // return $percent_saat_ini ;
                                            // $main_nilai = ( ($kunci2->volume + $volume_item_vo) * $kunci2->nilai ) + $main_nilai;
                                            $real_bobot_s =  (( ( $percent_saat_ini ) * 100 ) / 100 ) * ( (($kunci2->volume + $volume_item_vo) * $kunci2->nilai)/$nilai *100 ) ;
                                            // printf($percent_saat_ini);
                                            // echo("<br/>");
                                            // printf(( (($kunci2->volume + $volume_item_vo) * $kunci2->nilai)/$nilai *100 ));
                                            // echo("<br/>hai<br/>");
                                            // printf($real_bobot_s);
                                            // echo("<br/>");
                                            $itempekerjaan_termin = Itempekerjaan::find($kunci2->itempekerjaan_id)->item_progress->where("termyn","<=",$j+1)->sum("percentage");

                                            if($percent_saat_ini*100 < $itempekerjaan_termin){
                                                // printf($j);
                                                // echo("<br/>");
                                                // printf($percent_saat_ini);
                                                // echo("<br/>");
                                                // printf($itempekerjaan_termin);
                                                // echo("<br/>kuy<br/>");
                                                // printf(($spk->progresses->where("itempekerjaan_id",$value->itempekerjaan_id)->first()->progresslapangan_percent*100));
                                                // echo("<br/>");
                                                $status_submit = 0;
                                            }
                                        }         
                                    }
                                }
                            }
                        }
  
                    // }
                }
                $total_termyn_itempekerjaan_sebelum = $total_termyn_itempekerjaan;
            }
        }

        $tanggal_last_progress = null;
        foreach ($spk->tender->units as $key => $value1) {
            foreach ($value1->unit_progress as $key => $value2) {
                if($tanggal_last_progress == null){
                    $tanggal_last_progress = $value2->updated_at;
                }elseif($tanggal_last_progress <= $value2->updated_at){
                    $tanggal_last_progress = $value2->updated_at;
                }
            }
        }
        $tanggal_sekarang = date("d-m-Y");
        
        $jumlah_hari_telat = 0;
        if(count($spk->perpanjangan) == 0){
            $date1 = date('Y-m-d', strtotime($spk->finish_date_real));
            $date2 = date('Y-m-d', strtotime($tanggal_sekarang));
            $telat = strtotime($date2) - strtotime($date1);
            // return (int)$telat;
            if((int)$telat < 0){
                // return $telat;
                $diff = abs(strtotime($date2) - strtotime($date1));

                $days = floor(($diff )/ (60*60*24));
                // printf($date2);
                // echo("<br/>");
                // printf($date1);
                // echo("<br/>");
                // return $days;
                $jumlah_hari_telat = 0;
            }
        }else{
            // return $spk->perpanjangan->sortByDesc('tanggal_disetujui')->first()->tanggal_disetujui;
            // return $spk->perpanjangan->orderBy('tanggal_disetujui','desc')->first()->tanggal_disetujui;
            if($spk->perpanjangan->sortByDesc('tanggal_disetujui')->first()->tanggal_disetujui < $tanggal_sekarang){
                $date1 = date('Y-m-d', strtotime($spk->perpanjangan->last()->tanggal_disetujui));
                $date2 = date('Y-m-d', strtotime($tanggal_sekarang));

                $diff = abs(strtotime($date2) - strtotime($date1));

                $days = floor(($diff )/ (60*60*24));
                $jumlah_hari_telat = $days;
            }
        }

        // $spk_pengembalian_dp = $spk->
        return view("spk::create_bap2",compact("project","user","spk","nilai_sebelumnya","ppn","item_pekerjaan","status_submit",'persen_dibayarkan','st_status','percent_retensi','pembayaran_saat_ini','pembayaran_saat_ppnpph','tanggal_last_progress','tanggal_sekarang','jumlah_hari_telat'));
    }

    public function savebap(Request $request){
        $spk = Spk::find($request->spk_bap);
        
        $bap_no = $spk->no . '/BAP/' . str_pad( ($spk->baps()->count() + 1) , 2, "0", STR_PAD_LEFT);        
        $progress_bayar = $spk->nilai_bap_sekarang / ($spk->nilai) ;
        $total = $spk->bap + $progress_bayar;

        $bap = new Bap;
        $bap->spk_id = $spk->id;
        $bap->date = date("Y-m-d");
        $bap->termin = $request->spk_bap_termin;
        $bap->no = $bap_no;
        $bap->nilai_administrasi = (int)str_replace(",", "", $request->admin);
        $bap->nilai_denda = (int)$request->denda;
        // $bap->nilai_selisih = $request->selisih;
        $bap->nilai_dp = $spk->nilai_pengembalian;
        $bap->nilai_bap_1 = (int)$request->nilai_bap_1;
        $bap->nilai_bap_2 = (int)$request->nilai_bap_2;
        $bap->nilai_bap_3 = (int)$request->nilai_bap_3;
        $bap->percentage = $request->percentage;
        $bap->percentage_saat_ini = $request->percentage_saat_ini;
        $bap->percentage_lapangan = round($request->percentage_lapangan,2);
        $bap->percentage_sebelumnyas = $request->percentage_sebelumnya;
        $bap->nilai_bap_dibayar = (int)str_replace(",", "", $request->nilai_bap_dibayar);
        $bap->nilai_retensi = (int)$request->nilai_retensi;
        $bap->nilai_talangan = (int)$request->talangan;
        $bap->status_voucher = 0;
        $bap->nilai_spk = (int)$spk->nilai;
        $bap->ppn = $request->ppn;
        $bap->pph = $request->pph;
        $bap->st_status = $request->st_status;
        $bap->nilai_pembayaran_saat_ini = (int)$request->nilai_pembayaran_saat_ini;
        $bap->nilai_ppn = (int)$request->nilai_ppn;
        $bap->nilai_pph = (int)$request->nilai_pph;
        $bap->nilai_vo = (int)$request->bap_vo;
        $bap->nilai_percepatan = (int)$request->bap_percepatan;
        $bap->nilai_kurang_bayar_vo = (int)$request->kurang_bayar_vo;
        $bap->pph_rekanan_id = $spk->pph_rekanan_id;
        $bap->created_by = \Auth::user()->id;
        $bap->save();

        foreach ($spk->details as $key3 => $value3) {
            $detail                    = new BapDetail;
            $detail->bap_id            = $bap->id;
            $detail->asset_id          = $value3->asset_id;
            $detail->asset_type        = $value3->asset_type;
            $status = $detail->save();

            foreach ($value3->details as $key4 => $value4) {
                $bap_detail_itempekerjaan                       = new BapDetailItempekerjaan;
                $bap_detail_itempekerjaan->bap_detail_id        = $detail->id;
                $bap_detail_itempekerjaan->itempekerjaan_id     = $value4->unit_progress->itempekerjaan_id;
                $bap_detail_itempekerjaan->spkvo_unit_id        = $value4->unit_progress->spkvo_unit->id;
                $bap_detail_itempekerjaan->terbayar_percent     = $total * 100 ;
                $bap_detail_itempekerjaan->lapangan_percent     = $value4->unit_progress->progresslapangan_percent  / 100;
                $bap_detail_itempekerjaan->save();
            }
        }

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
        // return $nilai_per_unit;
        $nilaitot_kurang = 0;
        $total_nilaitot_kurang = 0;
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

            if($value1->rab_unit->asset != ''){
                $name = $value1->rab_unit->asset->name;
            }else{
                $name = "Fasilitas Kota";
            }
            $bap_unit = new BapUnitDetail;
            $bap_unit->bap_id = $bap->id;
            $bap_unit->unit_id = $value1->id;
            $bap_unit->persen_lapangan_unit = $real_bobot;
            $bap_unit->save();
        }
        
        foreach ($spk->progresses as $key => $value) {
            $unit = UnitProgress::find($value->id);
            $unit->progressbap_percent = $total * 100 ;
            $unit->save();
        }
        

        $nilai = 0;
        $total_termun = 0;
        $start = 0;
        $total_progress = $spk->progresses->sum('progresslapangan_percent') ;
        $lapangan = $spk->lapangan;

        if($lapangan == 0 || $spk->termyn[0]->status != "3"){
            $spk_termyn = SpkTermyn::find($spk->termyn[0]->id);
            $spk_termyn->status = "3";
            $spk_termyn->save();
        }else{
            foreach ($spk->termyn as $key => $value) {
                $total_termun = $total_termun + $value->termin;
                if ( $total_termun <= $request->percentage+$value->termin){
                    $start = $key ;
                    $spk_termyn = SpkTermyn::find($value->id);
                    $spk_termyn->status = "3";
                    $spk_termyn->save();            
                }
            }
        }
        
        if ( $total_progress >= 100 ){
            foreach ( $spk->dp_pengembalians as $key2 => $value2 ) {                       
                $spkpengembalian = SpkPengembalian::find($value2->id);
                $spkpengembalian->status = "1";
                $spkpengembalian->save();      
            }
        }else{

            /*if ( $this->spk_real_termyn > ){
                
            }*/
            foreach ( $spk->dp_pengembalians->take($spk->baps->count())->where("status",0) as $key2 => $value2 ) {                       
               $spkpengembalian = SpkPengembalian::find($value2->id);
               $spkpengembalian->status = "1";
               $spkpengembalian->save();      
            }
        }

        
        //die;
        return redirect("/spk/detail?id=".$request->spk_bap);
    }

    public function detailbap(Request $request){
        $bap = Bap::find($request->id);
        $spk = $bap->spk;
        $user = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        //  if ( $spk->rekanan->ppn != null ){
        //     $ppn = $spk->rekanan->ppn / 100;
        // }else{
        //     $ppn  = 0;
        // }
        if ( $spk->ppn == 1 ){
            $ppn = Globalsetting::where("parameter","ppn")->first()->value / 100;
        }else{
            $ppn  = 0;
        }

        $item_pekerjaan = Itempekerjaan::find($spk->tender->rab->pekerjaans->last()->itempekerjaan->parent->id);
        return view("spk::detail_bap2",compact("project","user","spk","bap","ppn","item_pekerjaan"));
    }

    public function addvoucher(Request $request){
        $bap = Bap::find($request->id);
        $user = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        return view("spk::create_voucher",compact("project","user","bap"));
    }

    public function updatedp(Request $request){

        $spk = Spk::find($request->spk_id);
        $spk->dp_percent = $request->dp_percent;
        $spk->spk_type_id = $request->dp_type;
        $spk->save();

        return redirect("/spk/detail?id=".$spk->id);
    }

    public function savedptermin(Request $request){
        $nilai = 0;
        foreach ($request->termyn as $key => $value) {
            if ( $request->termyn[$key] != "" ){                
                $spkpengembaliandp = new SpkPengembalian;
                $spkpengembaliandp->spk_id = $request->spk_id_dp;
                $spkpengembaliandp->termin = $key + 1;
                $spkpengembaliandp->percent = $request->termyn[$key] - $nilai ;
                $spkpengembaliandp->save();
                $nilai = $request->termyn[$key] ;
            }
        }

        return redirect("/spk/detail?id=".$request->spk_id_dp);
        
    }

    public function saveretensis(Request $request){
        $retensi = new SpkRetensi;
        $retensi->spk_id = $request->spk_id;
        $retensi->percent = $request->retensi / 100;
        $retensi->hari = $request->hari;
        $retensi->is_progress = 1;
        $retensi->save();

        
        $spk = Spk::find($request->spk_id);        
        $spk->st_2 = date('Y-m-d', strtotime('+'.$request->hari.' day', strtotime($spk->st_1)));
        $spk->save();

        if ( count($spk->retensis) > 1 ){
            $spk = Spk::find($request->spk_id);
            $spk->st_3 = date('Y-m-d', strtotime('+'.$request->hari.' day', strtotime($spk->st_1)));
            $spk->save();
        }

        return redirect("/spk/detail?id=".$request->spk_id);
    }

    public function saveprogress(Request $request){
        $spk_progress = Spk::find($request->spk_id);
        $spk_progress->min_progress_dp = $request->min_progress_dp;
        $spk_progress->save();
        return redirect("/spk/detail?id=".$request->spk_id);
    }

    public function deleteretensi(Request $request){
        $spk_retensi = SpkRetensi::find($request->id);
        $spk_retensi->delete();
        return response()->json( ["status" => "0"] );
    }

    public function approval_history(Request $request){
        $spk = Spk::find($request->id);
        $approval = $spk->approval;
        $user = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        return view("spk::approval_history",compact("project","user","spk","approval"));
    }

    public function createsik(Request $request){
        $spk = Spk::find($request->id);
        $user = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        return view("spk::create_sik",compact("user","project","spk"));
    }

    public function storesik(Request $request){        
        $spk = Spk::find($request->spk_id);
        $number = \App\Helpers\Document::new_number('SIK', $spk->tender->rab->workorder->department_from,$spk->project_id).$spk->tender->rab->pt->code;
        $suratinstruksi = new Suratinstruksi;
        $suratinstruksi->spk_id = $request->spk_id;
        $suratinstruksi->no = $number;
        $suratinstruksi->date = date("Y-m-d H:i:s.u");
        $suratinstruksi->perihal = $request->perihal;
        $suratinstruksi->content = $request->content;
        $suratinstruksi->type = "sil";
        $suratinstruksi->save();

        
        return redirect("spk/sik-show?id=".$suratinstruksi->id);
    }

    public function showsik(Request $request){
        $ttd_pertama = "";
        $ttd_kedua = "";
        $tmp_ttd_pertama = array();
        $start = 0;

        $suratinstruksi = Suratinstruksi::find($request->id);
        $user = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        $arrayparam = array("+" => array("label" => "Pekerjaan Tambah", "class" => "label label-success"), "-" => array("label" => "Pekerjaan Kurang", "class" => "label label-danger"));
        $asset = "";
        foreach ($suratinstruksi->spk->details as $key => $value) {
            $asset .= $value->asset->name .",";
        }
        $asset = trim($asset,",");

        if ( $suratinstruksi->spk->approval != "" ){
            $ttd_pertama = $suratinstruksi->spk->approval->histories->min("no_urut");
            foreach ($suratinstruksi->spk->approval->histories as $key => $value) {
                $user = User::find($value->user_id);
                $max = $user->approval_reference;
                foreach ($user->approval_reference as $key2 => $value2) {
                    if ( $value2->max_value <= $suratinstruksi->spk->nilai && $value2->project_id == $suratinstruksi->spk->project->id && $value2->document_type == "Spk"){
                        $tmp_ttd_pertama[$start] = array( "level" => $value2->no_urut, "user_name" => ucwords($value2->user->user_name), "user_jabatan" => ucwords($value2->user->jabatan[0]["jabatan"]) );
                        $start++;
                    }
                }
            }            
            $ttd_pertama = min($tmp_ttd_pertama);
        

            if ( $ttd_pertama["level"] < 5 ){
                $list_ttd[0] = array("user_name" => $ttd_pertama["user_name"], "user_jabatan" => $ttd_pertama["user_jabatan"]);            
                $list_ttd[1] = array("user_name" => $tmp_ttd_pertama[1]["user_name"], "user_jabatan" => $tmp_ttd_pertama[1]["user_jabatan"]);
                foreach ($tmp_ttd_pertama as $key => $value) {
                    if ( $value["level"] == 5 ){
                        $list_ttd[2] = array("user_name" => $tmp_ttd_pertama[$key]["user_name"], "user_jabatan" => $tmp_ttd_pertama[$key]["user_jabatan"]);
                    }
                }  
            }else{
                $list_ttd[0] = array("user_name" => $ttd_pertama["user_name"], "user_jabatan" => $ttd_pertama["user_jabatan"]);  
                $start = 1;          
                foreach ($tmp_ttd_pertama as $key => $value) {
                    if ( $value["level"] > 5 ){
                        $list_ttd[$start] = array("user_name" => $tmp_ttd_pertama[$start]["user_name"], "user_jabatan" => $tmp_ttd_pertama[$start]["user_jabatan"]);
                        $start++;
                    }
                } 
            }
        }

        return view("spk::show_sik",compact("user","project_id","suratinstruksi","user","project","arrayparam","asset","list_ttd"));
    }

    public function createvo(Request $request){
        $suratinstruksi = Suratinstruksi::find($request->id);
        $user = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        return view("spk::add_vo",compact("user","project","suratinstruksi"));
    }

    public function storevo(Request $request){
        $sik = Suratinstruksi::find($request->sik_id);
        $vo_count = $sik->spk->vos()->count();
        
        foreach ($request->vo_unit_ as $key => $value) {
            $spkvo_units                        = new SpkvoUnit;
            $spkvo_units->spk_detail_id         = $request->vo_unit_[$key];
            $spkvo_units->head_id               = $variation_order->id;
            $spkvo_units->head_type             = 'Modules\Spk\Entities\Vo';
            $spkvo_units->templatepekerjaan_id  = "";
            $spkvo_units->unit_progress_id      = "";
            $spkvo_units->save();
        }
    }

    public function detailunitvo(Request $request){
        $spkdetail    = SpkDetail::find($request->id);
        $unitprogress = $spkdetail->details_with_vo;
        $html         = "";
        $html         .= "<tr>";
        $html         .= "<td colspan='4'>Unit : <strong>".$spkdetail->asset->name."</strong><input type='hidden' value='".$spkdetail->id."' name='spk_detail_id'/></td>";
        $html         .= "</tr>";
        foreach ($unitprogress as $key => $value) {
            $rab     = \Modules\Rab\Entities\RabPekerjaan::where("itempekerjaan_id",$value->unit_progress->itempekerjaan_id)->get();
            $html    .= "<tr>";
            $html    .= "<td>".$value->unit_progress->itempekerjaan->name."</td>";
            $html    .= "<td>
                            <input type='hidden' value='".($value->unit_progress->id)."' name='unit_progress_id[".$key."]' class='form-control'/>
                            <input type='text' value='".number_format($value->unit_progress->volume)."' name='unit_progress[".$key."]' class='form-control' required/>
                        </td>";
            $html    .= "<td>".$rab->first()->satuan."</td>";
            $html    .= "<td><input type='text' value='".number_format($value->unit_progress->nilai)."' name='unit_progress_nilai[".$key."]' class='form-control' required/></td>";
            $html    .= "</tr>";
        }

        return response()->json( ["status" => "0", "html" => $html] );
    }

    public function savevo(Request $request){
        $spk_detail = SpkDetail::find($request->spk_detail);
        $sik = Suratinstruksi::find($request->suratinstruksi);
        $vo_count = $sik->vos->count();

        $SuratInstruksiUnit = new SuratInstruksiUnit;
        $SuratInstruksiUnit->suratinstruksi_id = $request->suratinstruksi;
        $SuratInstruksiUnit->unit_id = $request->spk_detail;
        $SuratInstruksiUnit->created_by = \Auth::user()->id;
        $SuratInstruksiUnit->save();

        $variation_order                            = new Vo;
        $variation_order->suratinstruksi_id         = $request->suratinstruksi;
        $variation_order->suratinstruksi_unit_id    = $SuratInstruksiUnit->id;
        $variation_order->no                        = $sik->spk->no .'/VO/'. str_pad( $vo_count + 1 ,2,"0",STR_PAD_LEFT).$sik->spk->tender->rab->pt->code;
        $variation_order->date                      = date("Y-m-d H:i:s.u");
        $variation_order->urutan                    = null;
        $variation_order->description               = $request->description;
        $variation_order->save();

        foreach ($request->unit_progress_id as $key => $value) {
            if ( $request->volume_[$key] != "" && $request->nilai_[$key] != "" ){       
                $progress = UnitProgress::find($request->unit_progress_id[$key]);

                $newunitprogress = new UnitProgress;
                $newunitprogress->project_id = $spk_detail->spk->project_id;
                $newunitprogress->unit_id = $progress->unit_id;
                $newunitprogress->unit_type = $progress->unit_type;
                $newunitprogress->itempekerjaan_id = $request->itempekerjaan[$key];
                $newunitprogress->group_tahapan_id = $key;
                $newunitprogress->group_item_id = $key;
                $newunitprogress->urutitem = $progress->urutitem;
                $newunitprogress->termin = $progress->termin;
                $newunitprogress->nilai = str_replace(",", "", $request->nilai_[$key]);
                $newunitprogress->volume = str_replace(",", "",$request->volume_[$key]);
                $newunitprogress->satuan = $progress->satuan;
                $newunitprogress->durasi = $progress->durasi;
                $newunitprogress->is_pembangunan = $progress->is_pembangunan;
                $newunitprogress->progresslapangan_percent = 0;
                $newunitprogress->progressbap_percent = 0;
                $newunitprogress->mulai_jadwal_date = date("Y-m-d H:i:s.u");
                $newunitprogress->selesai_jadwal_date = null;
                $newunitprogress->save();

                $SpkvoUnit = new SpkvoUnit;
                $SpkvoUnit->head_id = $variation_order->id;
                $SpkvoUnit->spk_detail_id = $spk_detail->id;
                $SpkvoUnit->head_type = "Modules\Spk\Entities\Vo";
                $SpkvoUnit->unit_progress_id = $newunitprogress->id;
                $SpkvoUnit->volume = str_replace(",", "", $request->volume_[$key]);
                $SpkvoUnit->nilai = str_replace(",", "", $request->nilai_[$key]);
                $SpkvoUnit->satuan = $request->satuan_[$key];
                $SpkvoUnit->ppn = null;
                $SpkvoUnit->save();  

                $SuratInstruksiItem = new SuratInstruksiItem;
                $SuratInstruksiItem->surat_instruksi_unit_id = $SuratInstruksiUnit->id;
                $SuratInstruksiItem->itempekerjaan_id = $request->itempekerjaan[$key];
                $SuratInstruksiItem->unit_progress_id = $newunitprogress->id;
                $SuratInstruksiItem->created_by = \Auth::user()->id;
                $SuratInstruksiItem->save();

            }
        }
        

        return redirect("spk/sik-unit?id=".$spk_detail->id."&sik=".$sik->id);
    }

    public function setprogress(Request $request){

        /* Save Progress */
        $termyn = array();
        $spk = Spk::find($request->id);
        $item_progress = $spk->progresses->first()->itempekerjaan->item_progress;
        if ( count($item_progress) > 0 ){
            foreach ($item_progress as $key => $value) {
                $termyn[$key] = "0";
            }
            
            if ( count($spk->list_pekerjaan) > 0 ){
                foreach ($spk->list_pekerjaan as $key => $value) {
                    foreach ($value['termyn'] as $key2 => $value2) {
                        $termyn[$key2] = $termyn[$key2] + round( ( $value2 * $value['bobot_coa'] ) / 100 , 2);
                    }
                }
            }

            $spk_termyn = new SpkTermyn;
            $spk_termyn->spk_id = $spk->id;
            $spk_termyn->termin = 0; 
            $spk_termyn->progress = 0 ;        
            $spk_termyn->status = 1 ;        
            $spk_termyn->save();

            foreach ($termyn as $key => $value) {
                $spk_termyn = new SpkTermyn;
                $spk_termyn->spk_id = $spk->id;
                $spk_termyn->termin = $key + 1 ; 
                $spk_termyn->progress = $termyn[$key] ;
                $spk_termyn->status = 0 ;
                $spk_termyn->save();
            }
        }
    }

    public function sikunit(Request $request){
        $spkDetail = SpkDetail::find($request->id);
        $user = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        $suratinstruksi = Suratinstruksi::find($request->sik);
        return view("spk::detail_sik_unit",compact("user","project","spkDetail","suratinstruksi"));
    }

    public function deletevo(Request $request){
        $spkvo_unit_id = SpkvoUnit::find($request->id);
        $unitprogress_id = $spkvo_unit_id->unit_progress;
        $unitprogress = UnitProgress::find($unitprogress_id->id);
        $spkvo_unit_id->delete();
        $unitprogress->delete();
        return response()->json( ["status" => "0"] );
    }

    public function download(Request $request){

    }

    public function addpic(Request $request){
        $spk = Spk::find($request->spk_id);
        $spk->pic_id = $request->id;
        $spk->save();

        return response()->json(["status" => "0"]);
    }

    public function cetakan_bap(Request $request){
        $bap = Bap::find($request->id);
        if ( $bap->spk->pkp_status == 1 ){
            $ppn = ( $bap->spk ) * 0.1;
            $ppn_nilai = ($bap->nilai_bap_2) * 0.1;
        }else{
            $ppn = 0;
            $ppn_nilai = 0;
        }

        $status = "0";
        return response()->json([
            "status" => "0",
            "termyn" => $bap->termin,
            "tgl_bap" => $bap->date->format("d-M-Y"),
            "nilai_spk" => $bap->nilai_spk,
            "nilai_vo" => $bap->nilai_vo,
            "nilai_spk_vo" => $bap->nilai_spk + $bap->nilai_vo,
            "ppn" => $ppn,
            "total_nilai_kontrak" => $bap->nilai_spk + $bap->nilai_vo + $ppn,
            "nilai_dp" => $bap->spk->nilai_dp,
            "ppn_nilai" => $ppn_nilai,
            "nilai_bap" => $bap->nilai_bap_2,
            "nilai_bap_dan_ppn" => $bap->nilai_bap_2 + $ppn_nilai,
            "nilai_sebelumnya" => $bap->nilai_sebelumnya,
            "nilai_dibayar" =>  $bap->nilai_bap_dibayar,
            "createdby" => $bap->user->user_name
        ]);
    }

    public function counterProgres(Request $request){
        date_default_timezone_set("Asia/Jakarta");
        $bap = Bap::find($request->id);
        $spk = $bap->spk;
        $user = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        //  if ( $spk->rekanan->ppn != null ){
        //     $ppn = $spk->rekanan->ppn / 100;
        // }else{
        //     $ppn  = 0;
        // }

        if ( $spk->ppn == 1 ){
            $ppn = Globalsetting::where("parameter","ppn")->first()->value / 100;
        }else{
            $ppn  = 0;
        }

        $item_pekerjaan = Itempekerjaan::find($spk->tender->rab->pekerjaans->last()->itempekerjaan->parent->id);
        $user_jabatan = [];
        $user_kadiv = "-";
        $user_gm = "-";
        foreach($project->pt_user as $key => $value){
            if($value->user != null){
                $jabatan_kadiv = $value->user->details->where('user_jabatan_id',7)->where('project_pt_id',$value->id)->first();
                if( $jabatan_kadiv != null){
                    // return $jabatan_kadiv;
                    $user_kadiv = $jabatan_kadiv->user->user_name;
                }

                $jabatan_dept = $value->user->details->where('user_jabatan_id',6)->where('project_pt_id',$value->id)->first();
                if( $jabatan_dept != null){
                    // return $jabatan_kadiv;
                    $user_kadept = $jabatan_dept->user->user_name;
                }

                $jabatan_gm = $value->user->details->where('user_jabatan_id',5)->where('project_pt_id',$value->id)->first();
                if( $jabatan_gm != null){
                    $user_gm = $jabatan_gm->user->user_name;
                }
            }

        }
        // return $user_kadiv;
        $terbilang = new Terbilang();

        $pekerjaan = $bap->spk->tender->rab->workorder->detail_pekerjaan[0]->itempekerjaan_id;
        if( $pekerjaan != null){
            $coa = CoaCpmsFinance::where("project_id", $bap->spk->project_id)->where("pt_id", $bap->spk->pt->id)->where("itempekerjaan_id", $bap->spk->tender->rab->workorder->detail_pekerjaan[0]->itempekerjaan_id)->first();
                if ($coa != null){
                    $coa = CoaCpmsFinance::where("project_id", $bap->spk->project_id)->where("pt_id", $bap->spk->pt->id)->where("itempekerjaan_id", $bap->spk->tender->rab->workorder->detail_pekerjaan[0]->itempekerjaan_id)->first()->coa_finance;
                }
        }else{
            $coa = null;
        }
        
        
        $ttd_pertama = "-";
        $approval_references = \Modules\Approval\Entities\ApprovalReference::where('document_type', 'TunjukPemenangTender')
                                    ->where('project_id', $bap->spk->project_id )
                                    ->where('pt_id', $bap->spk->pt->id )
                                    ->where('min_value', '<=', abs($bap->spk->nilai+$bap->spk->nilai_vo))
                                    ->orderBy('no_urut','DESC')
                                    ->first();
        // return $approval_references;
        $jabatan = "-";
        if($approval_references != null){
            $ttd_pertama = $approval_references->user;
            foreach($ttd_pertama->jabatan as $key => $value){
                if( $value['pt_id'] == $spk->tender->rab->pt->id){
                    $jabatan = $value['jabatan'];
                }
            };
        }
        // $date = ;
        setLocale(LC_ALL, 'id');
        $tanggal_cetak= \Carbon\Carbon::parse(date("y-m-d"))->formatLocalized('%d %B %Y');

        $pdf = PDF::loadView('spk::cetakan_counterProgres', compact('bap','spk','user','project','ppn','item_pekerjaan','user_kadiv','user_gm','user_kadept','terbilang','coa','ttd_pertama','jabatan','tanggal_cetak'));
        return $pdf->stream('SP Counter Progres.pdf', array("Attachment" => false));
        // return $pdf->download('SP Counter Progres.pdf');
    }


    public function voucher(Request $request){

        $pdf = PDF::loadView('spk::cetakan_voucher');
        return $pdf->stream('Voucher.pdf', array("Attachment" => false));
        // return $pdf->download('Voucher.pdf');
    }

    public function addipk(Request $request)
    {
        $user = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        // $id_spk = $request->id_spk;
        $spk = Spk::find($request->id_spk);
        $sub = Itempekerjaan::find($request->id);
        $ipk_default = $sub->ipk;
        // $ipk_tambahan = $sub->ipkTambahan->where('spk_id',$spk->id);
        $ipk_tambahan = IpkTambahan::where('itempekerjaan_id',$sub->id)->where('spk_id',$spk->id)->get();
        // return $ipk_tambahan;
        return view('spk::add_ipk',compact('spk','ipk_default','ipk_tambahan','sub','user','project'));
    }

    public function progresspekerjaan(Request $request){
        $user = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        $spk = Spk::find($request->id_spk);
        $coa = CoaSatuan::get();
        $sub = Itempekerjaan::find($request->id);
        
        $progress_tambahan = ProgressTambahan::where('itempekerjaan_id',$request->id)->where('spk_id',$request->id_spk)->get();

        $volume = $spk->tender_rekanan->menangs->first()->details->where('itempekerjaan_id',$request->id)->first()->volume;
        $nilaispk = Spk::find($request->id_spk)->nilai;
        $nilai = $spk->tender_rekanan->menangs->first()->details->where('itempekerjaan_id',$request->id)->first()->nilai;
        $jmlunit = Spk::find($request->id_spk)->details->count();
        $subtotal = $volume * $nilai;
        $bobot = ($subtotal/($nilaispk*$jmlunit)*100);
        $jumlah_volume = 0;

        foreach($progress_tambahan as $key){
            $jumlah_volume = $jumlah_volume+$key->volume;
        }

        return view('spk::add_progress',compact('bobot','jumlah_volume','coa','spk','progress_tambahan','sub','user','project','volume'));
    }

    public function tambahipk(Request $request){
        $namaipk = $request->name;
        $spk = Spk::find($request->id_spk);
        $id_spk = $request->id_spk;
        $id_item = $request->id_item;

        $ipkdefault = new IpkDefault;
        $ipkdefault->name = $namaipk;
        $ipkdefault->itempekerjaan_id = $id_item;
        $ipkdefault->save();  
        
        for ($i=0; $i < count($request->unit); $i++) { 
            $ipk = new IpkTambahan;
            $ipk->name = $namaipk;
            $ipk->status = 0;
            $ipk->spk_id = $id_spk;
            $ipk->itempekerjaan_id = $id_item;
            $ipk->ipkdefault_id = $ipkdefault->id;
            $ipk->unit_id = $request->unit[$i];
            $ipk->save();  

            for ($j=0; $j < count($request->tahapan); $j++) { 
                # code...
                $progress = ProgressTambahan::where("id", $request->tahapan[$j])->where("unit_id", $request->unit[$i])->first();
                if($progress != null){
                    $ipk_progress = new IpkProgressTahapan;
                    $ipk_progress->progress_tambahan_id = $request->tahapan[$j];
                    $ipk_progress->ipk_tambahan_id = $ipk->id;
                    $ipk_progress->status_ceklis = 0;
                    $ipk_progress->tipe = "spk";
                    $ipk_progress->save();
                }
            }
        }

        return response()->json(['success'=> 'Data Telah ditambah']);
    }

    public function tambahprogress(Request $request){
        $namaprogress = $request->name;
        $spk = Spk::find($request->id_spk);
        $id_spk = $request->id_spk;
        $id_item = $request->id_item;
        $volume = (float)$request->volume;
        $satuan = $request->satuan;
        $sub = Itempekerjaan::find($id_item);

        $progressdefault = new ProgressDefault;
        $progressdefault->name = $namaprogress;
        $progressdefault->itempekerjaan_id = $id_item;
        $progressdefault->satuan = $sub->item_satuan;
        $progressdefault->save();  

        for ($i=0; $i < count($request->unit) ; $i++) { 
            $progress = new ProgressTambahan;
            $progress->name = $namaprogress;
            $progress->volume = $volume;
            $progress->spk_id = $id_spk;
            $progress->status = 0;
            $progress->itempekerjaan_id = $id_item;
            $progress->satuan = $satuan;
            $progress->unit_id = $request->unit[$i];
            $progress->progressdefault_id = $progressdefault->id;
            $progress->save();  
        }
    
        return response()->json(['success'=> 'Data Telah ditambah']);
    }

    public function hapusipk(Request $request){
        // return $request;
        $spk = Spk::find($request->spk_id);
        for ($i=0; $i <count($request->id_item) ; $i++) { 
            $ipk_tambahan = IpkTambahan::where('id',$request->id_item[$i])->first();
            if( $ipk_tambahan != null){
                if($ipk_tambahan->ipk_default != null){
                    $ipk_tambahan->ipk_default->delete();
                }
            }
            
            foreach (IpkTambahan::where('id',$request->id_item[$i])->first()->ipk_progress_tahapan as $key => $value) {
                # code...
                $value->delete();
            }
            IpkTambahan::where('id',$request->id_item[$i])->delete();
        }
            // IpkTambahan::where('')->delete();
        return response()->json(['success'=> 'Data Telah dihapus']);
    }

    public function editipk(Request $request){
      $name = str_replace("%20"," ",$request->name);
      $data = IpkTambahan::where('itempekerjaan_id',$request->id_item)
      ->where('spk_id',$request->id_spk)
      ->where('name',$name)->first();
      return response()->json($data);
    }

    public function updateipk(Request $request){
        IpkTambahan::where('itempekerjaan_id',$request->id_item)
        ->where('spk_id',$request->id_spk)->where('name',$request->old_name)
        ->update(['name'=>$request->new_name]);
        return response()->json(['success'=> 'Data Telah diubah']);
    }

    public function hapusprogress(Request $request){
        // return $request;
        // $spk = Spk::find($request->spk_id);
        for ($i=0; $i <count($request->id_item) ; $i++) { 
            # code...
            $progress_tambahan = ProgressTambahan::where('id',$request->id_item[$i])->first();
            if( $progress_tambahan != null){
                if( $progress_tambahan->progress_default != null){
                    $progress_tambahan->progress_default->delete();
                }
            }
            ProgressTambahan::where('id',$request->id_item[$i])->delete();
        }

        // ProgressTambahan::find($id_item)->delete();
        return response()->json(['success'=> 'Data Telah dihapus']);
    }

    public function editprogress(Request $request){
      $name = str_replace("%20"," ",$request->name);
      $data = ProgressTambahan::where('id',$request->id_item)->first();
      return response()->json($data);
    }

    public function updateprogress(Request $request){
        ProgressTambahan::where("id",$request->id_progress)
        ->update(['name'=>$request->new_name,'volume'=>$request->new_volume,'satuan'=>$request->new_satuan]);
        return response()->json(['success'=> 'Data Telah diubah']);
    }

    public function cetakTermyn(Request $request){
        $spk = Spk::find($request->id);

        $pdf = PDF::loadView('spk::cetak_termynlist',compact('spk'))->setPaper('a4','landscape');
        return $pdf->stream('Termyn List.pdf', array("Attachment" => false));
    }

    public function approvalsik(Request $request){
        $user = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        $allsik = Siks::where("project_id", $project->id)->orderBy("id","desc")->get();
        // return $allsik;
        return view('spk::index_sik',compact("user","project","allsik"));
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
        return view('spk::edit_sikbiaya',compact("user","project","spk","sik"));
      }

      public function detailsiknon(Request $request){
        $user = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        $spk = Spk::find($request->id_spk);
        $idspk  = $request->id_spk;
        $id_sik = $request->idsik;
        $sik = Siks::find($request->idsik);
  
        return view('spk::edit_siknon',compact("user","project","spk","sik"));
      }

      public function requestapproval(Request $request){
        //   return $request;
        $sik = Siks::find($request->idsik);
        $spk = Spk::find($request->id_spk);
        // return $spk->new_vo;
        $vo_no = $spk->no . '/Vo/' . str_pad( ($spk->new_vo()->count() + 1) , 2, "0", STR_PAD_LEFT);

        $vo         = new NewVo;
        $vo->no     = $vo_no;
        $vo->sik_id = $sik->id;
        $vo->spk_id = $spk->id;
        $vo->project_id = $spk->project_id;
        $vo->tipe = $sik->status_sik_id;
        $vo->save();

        foreach ($sik->sik_unit as $key => $nilai) {
            foreach ($sik->sik_detail as $key2 => $value) {
                # code...
                $vo_detail                              = new DetailVo;
                $vo_detail->project_id                  = $spk->project_id;
                $vo_detail->unit_id                     = $nilai->unit_id; 
                $vo_detail->itempekerjaan_id            = $value->itempekerjaan_id;
                $vo_detail->urutitem                    = $key2+1;
                $vo_detail->termin                      = $key2+1;
                $vo_detail->nilai                       = $value->nilai;
                $vo_detail->volume                      = $value->volume_admin;
                $vo_detail->satuan                      = $value->satuan;
                $vo_detail->is_pembangunan              = 1;
                $vo_detail->progresslapangan_percent    = 0;
                $vo_detail->sikdetail_id                = $value->id;
                $vo_detail->vo_id                       = $vo->id;
                $vo_detail->description                 = $value->keterangan;
                $vo_detail->total_nilai                 = $value->total_nilai;
                $vo_detail->save();

                foreach ($value->sik_detail as $key3 => $value2) {
                    $vo_sub_detail                      = new SubDetailVo;
                    $vo_sub_detail->detail_vo_id        = $vo_detail->id;
                    $vo_sub_detail->name                = $value2->name;
                    $vo_sub_detail->volume              = $value2->volume_admin;
                    $vo_sub_detail->satuan              = $value2->satuan;
                    $vo_sub_detail->nilai               = $value2->nilai;
                    $vo_sub_detail->total_nilai         = $value2->volume_admin * $value2->nilai;
                    $vo_sub_detail->keterangan          = $value2->keterangan;
                    $vo_sub_detail->keterangan_admin    = $value2->keterangan_admin;
                    $vo_sub_detail->sik_sub_detail_id   = $value2->id;
                    $vo_sub_detail->volume_pengawas     = $value2->volume;
                    $vo_sub_detail->save();
                }

                $vo_detail_new = DetailVo::find($vo_detail->id);

                $vo_detail_new->total_nilai = $vo_detail->sub_detail_vo->sum("total_nilai");
                $vo_detail_new->save();
            }
        }

        $approval = \App\Helpers\Document::make_approval('Modules\Spk\Entities\NewVo',$vo->id);

        $approval_history_vo = \Modules\Approval\Entities\ApprovalHistory::where('document_id',$vo->id)->where('document_type','Modules\Spk\Entities\NewVo')->orderBy('no_urut','DESC')->first();

        \Modules\Approval\Entities\ApprovalHistory::where("id", $approval_history_vo->id)->update(['approval_action_id' => 1]);
        
        $project_pt = ProjectPt::where("project_id",$spk->project->id)->first();
        $data["email"]=$approval_history_vo->user->email;
        $data["client_name"]=$approval_history_vo->user->user_name;
        $data["subject"]='Approval Vo';
        // $link = 'https://ces.ciputragroup.com/webapps/Ciputra/public/';
        
        $encript = encrypt('https://cpms.ciputragroup.com:81/access/vo/detail/?id='.$vo->id.'||'.$approval_history_vo->user->id.'||'.date('d/M/Y', strtotime('+ 7 day', strtotime(date("Y-m-d")))));
        $link = 'https://cpms.ciputragroup.com:81/access/login/?code='.$encript;
        $title = "Vo";

        Mail::send('mail.bodyEmailApprove', ['link' => $link, 'title' => $title, 'user' => $approval_history_vo->user, 'project_pt' => $project_pt, 'name' => $spk->tender->rab->name], function($message)use($data) {
            $message->from(env('MAIL_USERNAME'))->to($data["email"], $data["client_name"])->subject($data["subject"]);
        });

        return redirect("/spk/sik/detailsikbiaya?idsik=".$sik->id."&id_spk=".$spk->id);
      }

      public function progressvo(Request $request){
        $user = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        $vo = NewVo::find($request->void);
        $vo_detail = DetailVo::find($request->void_detail);
        $progress_tambahan = ProgressTambahanVo::where('detail_vo_id',$vo_detail->id)->select('name','status','itempekerjaan_id','spk_id','volume','satuan')->distinct(['name'])->get();;
        $jumlah_volume = 0;
        foreach($progress_tambahan as $key){
            $jumlah_volume = $jumlah_volume+$key->volume;
        }
        $coa = CoaSatuan::get();

        // return $vo_detail->itempekerjaan->code;
        return view('spk::add_progress_vo',compact('jumlah_volume','progress_tambahan','user','project','vo_detail','vo','coa'));
    }

    public function editprogressvo(Request $request){
      $name = str_replace("%20"," ",$request->name);
      $data = ProgressTambahanVo::where('itempekerjaan_id',$request->id_item)
      ->where('spk_id',$request->id_spk)
      ->where('name',$name)->first();
      return response()->json($data);
    }

    public function updateprogressvo(Request $request){
        ProgressTambahanVo::where("id",$request->id_progress)
        ->update(['name'=>$request->new_name,'volume'=>$request->new_volume,'satuan'=>$request->new_satuan]);
        return response()->json(['success'=> 'Data Telah diubah']);
    }

    public function hapusprogressvo(Request $request){
        $spk = Spk::find($request->spk_id);
        ProgressTambahanVo::where('itempekerjaan_id',$request->id_item)->where('spk_id',$spk->id)->where('name',$request->name)->delete();
        // ProgressTambahan::find($id_item)->delete();
        return response()->json(['success'=> 'Data Telah dihapus']);
    }

    public function tambahprogressvo(Request $request){
        $namaprogress = $request->name;
        $spk = Spk::find($request->id_spk);
        $id_spk = $request->id_spk;
        $volume = $request->volume;
        $satuan = $request->satuan;
        $vo_detail = DetailVo::find($request->void_detail);

        // foreach ($spk->tender->units as $key => $value) {
            $progress = new ProgressTambahanVo;
            $progress->name = $namaprogress;
            $progress->volume = $volume;
            $progress->spk_id = $id_spk;
            $progress->status = 0;
            $progress->itempekerjaan_id = $vo_detail->itempekerjaan_id;
            $progress->satuan = $satuan;
            $progress->unit_id = $vo_detail->unit_id;
            $progress->detail_vo_id = $vo_detail->id;
            $progress->save();  
        // }
    
        return response()->json(['success'=> 'Data Telah ditambah']);
    }

    public function savetermynbayar(Request $request){
        $idtermyn = $request->idtermyn;
        $tglbayar = $request->tglbayar;
        // if ( $request->termyn != "" ){
        // return count($request->jml);
            foreach ($request->jml as $key => $value) {
                    // if ( $request->termyn[$key] != "" ){
                        SpkTermyn::where('id',$request->idtermyn[$key])
                        ->update(['tanggal_pembayaran'=>$tglbayar[$key]]);
                    //     $spk_termyn = new SpkTermyn;
                    //     $spk_termyn->tender_id = $request->tender_id;
                    //     $spk_termyn->termin = $request->termyn[$key];
                    //     $spk_termyn->tanggal_pembayaran = $request->date[$key];
                    //     $spk_termyn->progress = 0;
                    //     $spk_termyn->save();
                    // }
                // echo $idtermyn[$key].' '.$tglbayar[$key].'<br>';
                }
            // }
            return redirect("/spk/detail?id=".$request->idspk);
    }

    public function addpercepatan(Request $request){
        $user = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        $spk = Spk::find($request->id);

        return view('spk::create_percepatan',compact('user','project','spk'));
    }

    public function savepercepatan(Request $request){
        $spk = Spk::find($request->idspk);
        $percepatan_no = $spk->no . '/Percepatan/' . str_pad( ($spk->percepatan()->count() + 1) , 2, "0", STR_PAD_LEFT);
        $unit = $request->unit;
        $percepatan = new SpkPercepatan;
        $percepatan->spk_id = $request->idspk;
        $percepatan->no = $percepatan_no;
        $percepatan->nilai_persen = $request->nilai;
        $percepatan->tanggal_finish = $request->tanggal;
        $percepatan->description = $request->isian;
        $percepatan->status_percepatan = 0;
        $percepatan->save();

        for ($i=0; $i < count($unit); $i++) { 
            # code...
            $percepatan_unit = new SpkPercepatanUnit;
            $percepatan_unit->spk_percepatan_id = $percepatan->id;
            $percepatan_unit->unit_id = $unit[$i];
            $percepatan_unit->save();
        }

        // 'Pembuatan Percepatan SPK selesai'
        return response()->json(['success'=> 'Pembuatan Percepatan SPK selesai']);
    }

    public function request_percepatan(Request $request){
        $percepatan = SpkPercepatan::find($request->id);

        $approval = \App\Helpers\Document::make_approval('Modules\Spk\Entities\SpkPercepatan',$percepatan->id);
        return redirect("/spk/detail?id=".$percepatan->spk_id);
    }

    public function update_sikbiaya(Request $request){
        // $id_spk = $request->id_spk;
        // $id_detail = $request->id_detail;
        // $volume = $request->vol;
        // $satuan = $request->satuan;
        // $keterangan = $request->ket_admin;
        // // return $keterangan[0];
        // $spk = Spk::find($id_spk)->tender_rekanan->menangs->first()->details;
        
        // for ($i=0; $i < count($id_detail); $i++) { 
        //     SikDetail::where('id',$id_detail[$i])->update(['keterangan_admin'=>$keterangan[$i]]);
        // }

        $data = json_decode($request->data);
        // return $data;
        $total = 0;
        for ($i=0; $i < count($data); $i++) { 
            if($data[$i][0] != 0){
                $total = 0;
                for ($j=0; $j < count($data[$i][0]); $j++) {
                    $siksubdetail = SikSubDetail::find($data[$i][0][$j][0]);
                    $siksubdetail->keterangan_admin = $data[$i][0][$j][1];
                    $siksubdetail->volume_admin = $data[$i][0][$j][2];
                    $siksubdetail->save();
                    $total += $data[$i][0][$j][2];
                }
                $sik_detail = $siksubdetail->sik_detail;
                $sik_detail->volume_admin = $total;
                $sik_detail->save();
            }
        }
        return response()->json( ["status" => "0"] );       
        // return redirect("/spk/sik/detailsikbiaya?idsik=".$request->idsik."&id_spk=".$id_spk);
    }

    public function cetakSpk(Request $request){
        date_default_timezone_set("Asia/Jakarta");
        $date = date("d-m-Y");
        $tanggal= \Carbon\Carbon::parse(date("y-m-d"))->formatLocalized('%d %B %Y');
        $spk = Spk::find($request->spk_id);
        setLocale(LC_ALL, 'id');
        $tanggal = \Carbon\Carbon::parse($spk->created_at)->formatLocalized('%d %B %Y');
        $tanggal_mengikat = \Carbon\Carbon::parse($spk->finish_date)->formatLocalized('%d %B %Y');
        
        if($spk->ppn == 1){
            $ppn = Globalsetting::where("parameter","ppn")->first()->value;
        }else{
            $ppn = 0;
        }
        $globalsetting = \Modules\Globalsetting\Entities\Globalsetting::where('parameter','tunjuk_langsung_approval')->first();
        
        if($spk->rekanan->group->pkp_status != 0){
            $type = $spk->tender->tender_type->id;
            if ( $type == 1 ){
                $nilai = $globalsetting->value * $spk->nilai;
            }else{
                $nilai = $spk->nilai;
            }
            $ttd_pertama = ApprovalReference::where('document_type', 'TunjukPemenangTender')
                                    ->where('project_id', $spk->project->id )
                                    ->where('pt_id', $spk->pt->id )
                                    ->where('min_value', '<=', abs($nilai + (($spk->nilai * $ppn ) / 100)))
                                    //->where('max_value', '>=', $approval->total_nilai)
                                    ->orderBy('no_urut','ASC')
                                    ->first()->user;
        }else{
            $ttd_pertama = $spk->tender->tunjuk_pemenang_tender->approval->histories->first()->user;
        }
       
        // ->where('pt_id', $spk->tender->rab->pt->id)->get()
        $jabatan = "-";
        foreach($ttd_pertama->jabatan as $key => $value){
            if( $value['pt_id'] == $spk->tender->rab->pt->id){
                $jabatan = $value['jabatan'];
            }
        };
        $terbilang = new Terbilang();
        $counter = Globalsetting::where("id",1005)->first()->value;
        $pdf = PDF::loadView('spk::cetakan', compact('spk','ttd_pertama','ppn','terbilang','tanggal', 'jabatan','counter'));
        return $pdf->stream('Cetakan SPK no.'.$spk->no.'.pdf');
    }

    public function cetakSik(Request $request){
        date_default_timezone_set("Asia/Jakarta");
        $date = date("d-m-Y");
        $sik = Siks::find($request->sik);

        $user_dept = "-";
        $user_gm = "-";
        foreach($sik->spk->project->pt_user as $key => $value){
            if($value->user != null){
                $jabatan_dept = $value->user->details->where('user_jabatan_id',6)->where('project_pt_id',$value->id);
                if ($user_dept == "-") {
                    # code...
                    if(count($jabatan_dept) != 0){
                        $user_dept = $jabatan_dept[0]->user->user_name;
                    }
                }

                $jabatan_gm = $value->user->details->where('user_jabatan_id',5)->where('project_pt_id',$value->id);
                // if ($value->user->id == 118) {
                //     # code...
                //     // return $value;
                //     return $value->user->details->where('user_jabatan_id',5)->where('project_pt_id',$value->id);
                // }
                if($user_gm == "-"){
                    if(count($jabatan_gm) != 0){
                        $user_gm = $jabatan_gm[0]->user->user_name;
                    }
                }
            }

        }
        $pdf = PDF::loadView('spk::cetakan_sik', compact('sik','user_dept','user_gm'));
        return $pdf->stream('Cetakan Sik no.'.$sik->no_sik.'.pdf');
    }

    public function cetakvo(Request $request){
        date_default_timezone_set("Asia/Jakarta");
        $date = date("d-m-Y");
        $sik = Siks::find($request->sik);
        $vo = $sik->vo;
        $user_dept = "-";
        $user_gm = "-";
        foreach($sik->spk->project->pt_user as $key => $value){
            if($value->user != null){
                $jabatan_dept = $value->user->details->where('user_jabatan_id',6)->where('project_pt_id',$value->id);
                if(count($jabatan_dept) != 0){
                    $user_dept = $jabatan_dept[0]->user->user_name;
                }

                $jabatan_gm = $value->user->details->where('user_jabatan_id',5)->where('project_pt_id',$value->id);
                if(count($jabatan_gm) != 0){
                    $user_gm = $jabatan_gm[0]->user->user_name;
                }
            }

        }
        $pdf = PDF::loadView('spk::cetakan_Vo', compact('sik','user_dept','user_gm','vo'));
        return $pdf->stream('Cetakan Vo no.'.$vo->no.'.pdf');
    }

    public function tahapanIpk(Request $request){

        $spk = Spk::find($request->spk_id);
        $main = [];

        foreach ( $spk->progress_tambahan->where("itempekerjaan_id",$request->id_item)->whereIn("unit_id",$request->unit) as $key => $value){
            # code...
            if ( $value->status != 1) {
                # code...
                $arr = [
                    'id' => $value->id,
                    'name' => $value->name,
                    'unit' =>  $value->units->rab_unit->asset->name
                ];
    
                array_push($main, $arr);
            }
        }

        return response()->json($main);
    }

    public function approval_history_vo(Request $request){
        $vo = NewVo::find($request->id);
        $approval = $vo->approval;
        $project = Project::find($request->session()->get('project_id'));
        $user = \Auth::user();
        return view("spk::approval_history_vo",compact("vo","approval","project","user"));
    }

    public function addPartner(Request $request){
        // return $request;
        $spk = Spk::find($request->spk_id);
        $spk->with_partner = $request->id;
        $spk->save();
        return response()->json( ["status" => "0"] );
    }
    
}
