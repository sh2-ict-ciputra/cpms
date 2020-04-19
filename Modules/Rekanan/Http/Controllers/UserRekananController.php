<?php

namespace Modules\Rekanan\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Rekanan\Entities\RekananGroup;
use Modules\Country\Entities\City;
use Modules\Rekanan\Entities\Rekanan;
use Modules\Globalsetting\Entities\Globalsetting;
use Modules\Tender\Entities\Tender;
use Modules\Tender\Entities\TenderDetail;
use Modules\Tender\Entities\TenderRekanan;
use Modules\Tender\Entities\TenderPenawaran;
use Modules\Tender\Entities\TenderPenawaranDetail;
use Modules\Pekerjaan\Entities\Itempekerjaan;
use Modules\Project\Entities\Project;
use Modules\Spk\Entities\Spk;
use Modules\Rekanan\Entities\PerpanjanganSpk;
use Modules\Rekanan\Entities\UserRekanan;
use Modules\User\Entities\User;
use Modules\Spk\Entities\ProgressTambahan;
use Modules\Spk\Entities\ProgressTambahanVo;
use Modules\Spk\Entities\IpkProgressTahapan;
use Modules\Spk\Entities\RekananPengajuanIpk;
use Modules\Spk\Entities\IpkTambahan;
use Modules\Spk\Entities\NewVo;
use Modules\Spk\Entities\DetailVo;

use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Collection;
use Modules\Library\Http\Controllers;
use Modules\Rekanan\Entities\RekananPricelist;
use Modules\Rekanan\Entities\ViewRekananPricelist;
use Modules\Rekanan\Entities\RekananItems;
use Modules\Rekanan\Entities\Items;
use Modules\Rekanan\Entities\ItemCategories;
use Modules\Library\Entities\LibrarySupplierProjectPODetail;
use Modules\Library\Entities\LibraryMOUDetails;
use Modules\Tender\Entities\TenderPenawaranSubDetail;
use Modules\Tender\Entities\TenderDocument;
use Modules\Project\Entities\ProjectPt;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;
use Storage;


class UserRekananController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $rekanan_group = RekananGroup::find($request->session()->get('rekanan_id'));
        $rekanan = $rekanan_group->rekanans->where("parent_id",null);
        return view('rekanan::user.index',compact("rekanan_group","rekanan"));
    }
    

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('rekanan::create');
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
    public function show()
    {
        return view('rekanan::show');
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
        $rekanangroup = RekananGroup::find($request->rekanan_group_id);
        $rekanangroup->npwp_alamat = $request->alamat;
        $rekanangroup->cp_name = $request->cp_name;
        $rekanangroup->cp_jabatan = $request->cp_jabatan;
        $rekanangroup->saksi_name = $request->saksi_name;
        $rekanangroup->saksi_jabatan = $request->saksi_jabatan;
        $rekanangroup->ktp_no = $request->ktp_no;
        $rekanangroup->save();

        if (!file_exists (public_path()."/assets/rekanan/".$request->rekanan_group_id )) {
            mkdir(public_path()."/assets/rekanan/".$request->rekanan_group_id, 0755, true);
            chmod(public_path()."/assets/rekanan/".$request->rekanan_group_id,0755);
        }
        
        $target_file = "./assets/rekanan/".$request->rekanan_group_id."/".$_FILES['siup_img']['name'];
        move_uploaded_file($_FILES["siup_img"]["tmp_name"], $target_file);
        $rekanan = Rekanan::find($request->rekanan_id);
        $rekanan->surat_kota = $rekanangroup->npwp_kota;
        $rekanan->surat_alamat = $request->alamat;
        $rekanan->email = $request->email;
        $rekanan->surat_kodepos = $request->kodepos;
        $rekanan->email = $request->email;
        $rekanan->telp = $request->telpon; 
        $rekanan->fax = $request->fax;
        $rekanan->siup_no = $request->siup;
        
        if ( $_FILES['siup_img']['name'] == "" ){
            $rekanan->siup_image = $rekanan->siup_image;
        }else{
            $rekanan->siup_image = $_FILES['siup_img']['name'];
        }

        $rekanan->cp_name = $request->cp_name;
        $rekanan->cp_jabatan = $request->cp_jabatan;
        $rekanan->saksi_name = $request->saksi_name;
        $rekanan->saksi_jabatan = $request->saksi_jabatan;
        $rekanan->save();
        return redirect("/rekanan/user");
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function fail()
    {
        return view("auth/fail");
    }

    public function contact(Request $request){
        $rekanan_group = RekananGroup::find($request->session()->get('rekanan_id'));
        return view("rekanan::user.contact",compact("rekanan_group"));
    }

    public function storecontact(Request $request){
        $rekanan_group = RekananGroup::find($request->rekanan_group_id);
        $rekanan_group->cp_name = $request->cp_name;
        $rekanan_group->cp_jabatan = $request->cp_jabatan;
        $rekanan_group->saksi_name = $request->saksi_name;
        $rekanan_group->saksi_jabatan = $request->saksi_jabatan;
        $rekanan_group->save();

        return redirect("/rekanan/user/contact");
    }

    public function cabang(Request $request){
        $rekanan_group = RekananGroup::find($request->session()->get('rekanan_id'));
        $city = City::get();
        return view("rekanan::user.cabang",compact("rekanan_group","city"));
    }

    public function savecabang(Request $request){
        $ppn = 0;
        $global_ppn = Globalsetting::where("parameter","ppn")->get();
        
        if ( count($global_ppn) > 0 ){
            $ppn = $global_ppn->first()->value;
        }

        $rekanan_group = RekananGroup::find($request->rekanan_group_id);
        if ( $rekanan_group->pkp_status == "2"){
            $coa_ppn = 10;
        }
        $rekanan = new Rekanan;
        $rekanan->kelas_id = 1;
        $rekanan->rekanan_group_id = $request->rekanan_group_id;
        $rekanan->surat_kota = $request->kota;
        $rekanan->name = $request->name;
        $rekanan->surat_alamat = $request->alamat;
        $rekanan->surat_kodepos = $request->kodepost;
        $rekanan->email = $request->email;
        $rekanan->telp = $request->telepon;
        $rekanan->fax = $request->fax;
        $rekanan->cp_name = $request->cp_name;
        $rekanan->cp_jabatan = $request->cp_jabatan;
        $rekanan->saksi_name = $request->saksi_name;
        $rekanan->saksi_jabatan = $request->saksi_jabatan;
        $rekanan->ppn = $ppn;
        $rekanan->pkp_status = $rekanan_group->coa_ppn;
        $rekanan->save();
        return redirect("rekanan/user/cabang");

    }

    public function pricelist(Request $request){
        $rekanan_group = RekananGroup::find($request->session()->get('rekanan_id'));
        return view("rekanan::user.price_list",compact("rekanan_group"));
    }

    public function tender(Request $request){
        $rekanan_group = RekananGroup::find($request->session()->get('rekanan_id'));
        if($rekanan_group == null){
            return redirect("/");
        }
        return view("rekanan::user.tender",compact("rekanan_group"));
    }

    public function tender_detail(Request $request){
        $rekanan_group = RekananGroup::find($request->session()->get('rekanan_id'));
        if($rekanan_group == null){
            return redirect("/");
        }
        $tender_rekanan = TenderRekanan::find($request->id);
        $tender = $tender_rekanan->tender;
        $i = 0;
        foreach($tender->rekanans as $key => $value) {
                if($value->approval->approval_action_id == 6){
                    $i++;
                }
        }
        $tanggal_sekarang = date("Y-m-d H:i:s.u");
        // return $tender_rekanan->id;
        $date = date("Y-m-d");
        // return $date;
        return view("rekanan::user.tender_detail",compact("rekanan_group","tender","tanggal_sekarang","tender_rekanan","i","date"));
    }

    public function addpenawaran(Request $request){
        $rekanan = TenderRekanan::find($request->id);
        $rab = $rekanan->tender->rab;
        $itempekerjaan = Itempekerjaan::find($rab->pekerjaans->last()->itempekerjaan->parent->id);
        $user = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        $rekanan_group = RekananGroup::find($request->session()->get('rekanan_id'));
        return view("rekanan::user.detail_rab",compact("rab","itempekerjaan","rekanan","user","project","rekanan_group"));
    }

    public function savepenawaran(Request $request){
        $tenderrekaann = TenderRekanan::find($request->tender_rab_id);


        $tender_penawaran = new TenderPenawaran;
        $tender_penawaran->tender_rekanan_id = $request->tender_rab_id;
        $tender_penawaran->no = $request->tender_rab_id;
        $tender_penawaran->date = date("Y-m-d H:i:s.u");
        $tender_penawaran->created_by = \Auth::user()->id;
        $tender_penawaran->save();
        $keterangan = "";
        //print_r($request->input_rab_id_);die;
        foreach ($request->input_rab_id_ as $key => $value) {
            if ( $request->input_rab_nilai_[$key]  != "" && $request->input_rab_volume_[$key] != "" ){
                if ( isset($request->input_rab_keterangan[$key])){
                    $keterangan = $request->input_rab_keterangan[$key];
                }
                $tenderpenawarandetail = new TenderPenawaranDetail;
                $tenderpenawarandetail->tender_penawaran_id = $tender_penawaran->id;
                $tenderpenawarandetail->rab_pekerjaan_id = $request->input_rab_id_[$key]; 
                $tenderpenawarandetail->keterangan  = $keterangan;
                $tenderpenawarandetail->nilai = str_replace(",", "",$request->input_rab_nilai_[$key]); 
                $tenderpenawarandetail->volume = str_replace(",","",$request->input_rab_volume_[$key]);
                $tenderpenawarandetail->satuan = str_replace(",","",$request->input_rab_satuan_[$key]);
                $tenderpenawarandetail->save();
            }
        }

        return redirect("/rekanan/user/tender/detail/?id=".$tenderrekaann->id); 
    }

    public function step2(Request $request){
        $tenderpenawaran = TenderPenawaran::find($request->id);
        $tenderRekanan = $tenderpenawaran->rekanan;
        $rab = $tenderRekanan->tender->rab;
        $itempekerjaan = Itempekerjaan::find($rab->pekerjaans->last()->itempekerjaan->parent->id);
        $user = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        $penawaran_id = "";
        $rekanan_group = RekananGroup::find($request->session()->get('rekanan_id'));
        $exist = $request->exist;
        foreach ($tenderRekanan->penawarans as $key => $value) {
            if ( $value->updated_by == null ) {
                $penawaran_id = $value->id;
            }
        }
        $exist = $request->exist;
        return view("rekanan::user.detail_penawaran2",compact("rab","itempekerjaan","rekanan","user","project","tenderpenawaran","tenderRekanan","penawaran_id","rekanan_group","exist"));
    }

    public function step3(Request $request){
        $tenderpenawaran = TenderPenawaran::find($request->id);
        $tenderRekanan = $tenderpenawaran->rekanan;
        $rab = $tenderRekanan->tender->rab;
        $itempekerjaan = Itempekerjaan::find($rab->pekerjaans->last()->itempekerjaan->parent->id);
        $user = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        $penawaran_id = "";
        $rekanan_group = RekananGroup::find($request->session()->get('rekanan_id'));
        foreach ($tenderRekanan->penawarans as $key => $value) {
            if ( $value->updated_by == null ) {
                $penawaran_id = $value->id;
            }
        }
        $exist = $request->exist;
        return view("rekanan::user.detail_penawaran3",compact("rab","itempekerjaan","rekanan","user","project","tenderpenawaran","tenderRekanan","penawaran_id","rekanan_group","exist"));
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
        $TenderPenawaran = TenderPenawaran::find($request->penawaran_id);
        if ( $_FILES['fileupload']['tmp_name'] != ""){
            $array_mime = array("application/pdf","application/vnd.openxmlformats-officedocument.wordprocessingml.document","application/vnd.openxmlformats-officedocument.spreadsheetml.sheet","application/vnd.ms-excel","application/msword","application/zip","application/x-rar-compressed","application/octet-stream","application/x-zip-compressed","multipart/x-zip","application/zip");
            $mime = mime_content_type($_FILES['fileupload']['tmp_name']);
            if ( in_array($mime, $array_mime)){
                $target_file =  /*$_SERVER["DOCUMENT_ROOT"].*/"./assets/tender/".$TenderPenawaran->rekanan->tender->id;
                move_uploaded_file($_FILES["fileupload"]["tmp_name"], $target_file.'/'.$_FILES['fileupload']['name']);
                $TenderPenawaran->file_attachment = $_FILES['fileupload']['name'];
                $TenderPenawaran->save();
            }else{
                print("<script type='text/javascript'>alert('Format file tidak bisa diterima. Silahkan upload sesuai format yang diminta');</script>");
            }
        }
        
        return redirect("/rekanan/user/tender/penawaran-step2/?id=".$request->tender_id."&step=2&exist=1");
    }

    public function addstep3(Request $request){
        $tender = Tender::find($request->id);
        $rab = $tender->rab;
        $user = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        $itempekerjaan = Itempekerjaan::find($rab->pekerjaans->last()->itempekerjaan->parent->id);
        $rekanan_group = RekananGroup::find($request->session()->get('rekanan_id'));
        return view("rekanan::user.detail_step3",compact("tender","itempekerjaan","user","project","rekanan_group"));
    }

    public function updatepenawaran3(Request $request){
        foreach ($request->input_rab_id_ as $key => $value) {
            if ( $request->input_rab_nilai_[$key] != "" ){                 
                $tenderpenawarandetail = TenderPenawaranDetail::find($request->input_rab_id_[$key]);
                $tenderpenawarandetail->nilai = str_replace(",","",$request->input_rab_nilai_[$key]);
                $tenderpenawarandetail->save();
            }
        }
        
        $tenderPenawaran = TenderPenawaran::find($request->penawaran_id);
        if ( $_FILES['fileupload']['tmp_name'] != ""){
            $array_mime = array("application/pdf","application/vnd.openxmlformats-officedocument.wordprocessingml.document","application/vnd.openxmlformats-officedocument.spreadsheetml.sheet","application/vnd.ms-excel","application/msword","application/zip","application/x-rar-compressed","application/octet-stream","application/x-zip-compressed","multipart/x-zip","application/zip");
            $mime = mime_content_type($_FILES['fileupload']['tmp_name']);
            if ( in_array($mime, $array_mime)){
                $target_file = /*$_SERVER["DOCUMENT_ROOT"].*/"./assets/tender/".$tenderPenawaran->rekanan->tender->id."/".$_FILES['fileupload']['name'];
                move_uploaded_file($_FILES["fileupload"]["tmp_name"], $target_file.','.$_FILES['fileupload']['name']);
                $tenderPenawaran->file_attachment = $_FILES['fileupload']['name'];
                $tenderPenawaran->save();
            }else{
                print("<script type='text/javascript'>alert('Format file tidak bisa diterima. Silahkan upload sesuai format yang diminta');</script>");
            }
        }
        return redirect("/rekanan/user/tender/penawaran-step3/?id=".$request->tender_id."&step=3&exist=1");
    }

    public function viewstep1(Request $request){
        
    }

    public function step1(Request $request){
       
        $tenderpenawaran = TenderPenawaran::find($request->id);

        $tenderRekanan = $tenderpenawaran->rekanan;
        $rab = $tenderRekanan->tender->rab;
        $itempekerjaan = Itempekerjaan::find($rab->pekerjaans->last()->itempekerjaan->parent->id);
        $user = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        $penawaran_id = "";
        $rekanan_group = RekananGroup::find($request->session()->get('rekanan_id'));
        if($rekanan_group == null){
            return redirect("/");
        }
        foreach ($tenderRekanan->penawarans as $key => $value) {
            if ( $value->updated_by == null ) {
                $penawaran_id = $value->id;
            }
        }
        $tenderRekanan = $tenderpenawaran->rekanan;
        $step = $request->step;
        $exist = $request->exist;
        // return $tenderpenawaran->file_attachment;
        $dokumen = TenderDocument::where("workorder_id", $rab->workorder->id)->get();
        if(count($dokumen) == 0){
            $dokumen = TenderDocument::where("workorder_budget_id", $rab->workorder_budget_detail_id)->get();
        }
        return view("rekanan::user.detail_penawaran1",compact("rab","itempekerjaan","user","project","tenderpenawaran","tenderRekanan","penawaran_id","rekanan_group","exist","dokumen","step"));
    }

    public function updatepenawaran1(Request $request){
        foreach ($request->input_rab_id_ as $key => $value) {
            if ( $request->input_rab_nilai_[$key] != "" ){
                $tenderpenawarandetail = TenderPenawaranDetail::find($request->input_rab_id_[$key]);
                $tenderpenawarandetail->nilai = str_replace(",","",$request->input_rab_nilai_[$key]);
                $tenderpenawarandetail->save();
            }else{
                echo $request->input_rab_id_[$key];
            }
        }
        $TenderPenawaran = TenderPenawaran::find($request->penawaran_id);

        if ( $_FILES['fileupload']['tmp_name'] != ""){
            $array_mime = array("application/pdf","application/vnd.openxmlformats-officedocument.wordprocessingml.document","application/vnd.openxmlformats-officedocument.spreadsheetml.sheet","application/vnd.ms-excel","application/msword","application/zip","application/x-rar-compressed","application/octet-stream","application/x-zip-compressed","multipart/x-zip","application/zip");
            $mime = mime_content_type($_FILES['fileupload']['tmp_name']);
          
            if ( in_array($mime, $array_mime)){

                $target_file =  /*$_SERVER["DOCUMENT_ROOT"].*/"./assets/tender/".$TenderPenawaran->rekanan->tender->id;
                move_uploaded_file($_FILES["fileupload"]["tmp_name"], $target_file.'/'.$_FILES['fileupload']['name']);
                $TenderPenawaran->file_attachment = $_FILES['fileupload']['name'];
                $TenderPenawaran->save();
            }else{
                print("<script type='text/javascript'>alert('Format file tidak bisa diterima. Silahkan upload sesuai format yang diminta');</script>");
            }
        }
        return redirect("/rekanan/user/tender/penawaran-update/?id=".$TenderPenawaran->rekanan->id."&step=".$request->step."&exist=1");
    }

    public function view_spk(Request $request){
        $rekanan_group = RekananGroup::find($request->session()->get('rekanan_id'));
        // return $rekanan_group;
        $spk_rekanan = Spk::where('rekanan_id', $rekanan_group->id)->orderBy("id","desc")->get();
        // return $spk_rekanan;
        return view('rekanan::user.spk_rekanan',compact("rekanan_group","spk_rekanan"));
    }

    public function detailspk(Request $request){
        $rekanan_group = RekananGroup::find($request->session()->get('rekanan_id'));
        $spk_rekanan = Spk::where('rekanan_id', $rekanan_group->id)->get();
        $spk = Spk::find($request->id);
        $perpanjanganspk = PerpanjanganSpk::where('spk_id',$spk->id)->orderBy('tanggal_disetujui','desc')->first();
        // $cekperpanjang = PerpanjanganSpk::where('spk_id',$spk->id)->get();
        $cekdisetujui = PerpanjanganSpk::where('spk_id',$spk->id)->orderBy('id','desc')->first();
        // return $cekdisetujui;
        // return $perpanjanganspk;
        // return $cekdisetujui->approval->document->spk->project_id;
        // $approval_references = \Modules\Approval\Entities\ApprovalReference::where('document_type', 'PerpanjanganSpk')
        // ->where('project_id', $cekdisetujui->approval->document->spk->project_id )
        // //->where('pt_id', $pt_id )
        // ->where('min_value', '<=', 0)
        // //->where('max_value', '>=', $approval->total_nilai)
        // ->orderBy('no_urut','ASC')
        // ->get();

        // // return $approval_references[0]->user_id;
        // return \Modules\User\Entities\User::find($approval_references[0]->user_id)->jabatan;
        return view('rekanan::user.detail_spk_rekanan',compact("rekanan_group","spk_rekanan","spk",'perpanjanganspk','cekdisetujui'));
    }

    public function perpanjangspk(Request $request){
        $rekanan_group = RekananGroup::find($request->session()->get('rekanan_id'));
        $spk_rekanan = Spk::where('rekanan_id', $rekanan_group->id)->get();
        $spk = Spk::find($request->id);
        $perpanjanganspk = PerpanjanganSpk::where('spk_id',$spk->id)->orderBy('tanggal_disetujui','desc')->first();

        return view('rekanan::user.perpanjang_enddate',compact("rekanan_group","spk_rekanan","spk",'perpanjanganspk'));
    }

    public function inperpanjangspk(Request $request){
        $idspk = $request->idspk;
        $isian = $request->isian;
        $durasi = $request->jmlhari;
        $tglperpanjang = $request->tglperpanjang;

        $perpanjangspk = new PerpanjanganSpk;
        $perpanjangspk->spk_id = $idspk;
        $perpanjangspk->update_finish = $tglperpanjang;
        $perpanjangspk->duration = $durasi;
        $perpanjangspk->reason = $isian;
        $perpanjangspk->save();

        $approval = \App\Helpers\Document::make_approval('Modules\Rekanan\Entities\PerpanjanganSpk',$perpanjangspk->id);

        $approval_history_perpanjangan = \Modules\Approval\Entities\ApprovalHistory::where('document_id',$perpanjangspk->id)->where('document_type','Modules\Rekanan\Entities\PerpanjanganSpk')->orderBy('no_urut','DESC')->first();

        \Modules\Approval\Entities\ApprovalHistory::where("id", $approval_history_perpanjangan->id)->update(['approval_action_id' => 1]);
        
        $project_pt = ProjectPt::where("project_id",$perpanjangspk->spk->project->id)->first();
        $data["email"]=$approval_history_perpanjangan->user->email;
        $data["client_name"]=$approval_history_perpanjangan->user->user_name;
        $data["subject"]='Approval perpanjangan SPK';
        // $link = 'https://ces.ciputragroup.com/webapps/Ciputra/public/';
        
        $encript = encrypt('https://cpms.ciputragroup.com:81/access/PerpanjanganSpk/detail/?id='.$perpanjangspk->id.'||'.$approval_history_perpanjangan->user->id.'||'.date('d/M/Y', strtotime('+ 7 day', strtotime(date("Y-m-d")))));
        $link = 'https://cpms.ciputragroup.com:81/access/login/?code='.$encript;
        $title = "perpanjangan SPK";

        Mail::send('mail.bodyEmailApprove', ['link' => $link, 'title' => $title, 'user' => $approval_history_perpanjangan->user, 'project_pt' => $project_pt, 'name' => $perpanjangspk->spk->tender->rab->name], function($message)use($data) {
            $message->from(env('MAIL_USERNAME'))->to($data["email"], $data["client_name"])->subject($data["subject"]);
        });

        return response()->json(['success'=> 'Pengajuan Surat Perpanjang SPK Telah disimpan']);
    }

    public function stepberulang(Request $request){
        // return "haui";
        $tenderpenawaran = TenderPenawaran::find($request->id);
        // return $tenderpenawaran;
        $tenderRekanan = $tenderpenawaran->rekanan;
        $rab = $tenderRekanan->tender->rab;
        $itempekerjaan = Itempekerjaan::find($rab->pekerjaans->last()->itempekerjaan->parent->id);
        $user = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        $penawaran_id = "";
        $rekanan_group = RekananGroup::find($request->session()->get('rekanan_id'));
        if($rekanan_group == null){
            return redirect("/");
        }
        $exist = $request->exist;
        foreach ($tenderRekanan->penawarans as $key => $value) {
            if ( $value->updated_by == null ) {
                $penawaran_id = $value->id;
            }
        }
        $exist = $request->exist;
        $step = $request->step;

        $dokumen = TenderDocument::where("workorder_id", $rab->workorder->id)->get();
        if(count($dokumen) == 0){
            $dokumen = TenderDocument::where("workorder_budget_id", $rab->workorder_budget_detail_id)->get();
        }
        // return $tenderRekanan->penawarans;
        return view("rekanan::user.detail_penawaran_berulang",compact("rab","itempekerjaan","user","project","tenderpenawaran","tenderRekanan","penawaran_id","rekanan_group","exist","step","dokumen"));
    }

    public function updatepenawaran_berulang(Request $request){
        // return "wow";
        // return $request;
        foreach ($request->input_rab_id_ as $key => $value) {
            if ( $request->input_rab_nilai_[$key] != "" ){
                $tenderpenawarandetail = TenderPenawaranDetail::find($request->input_rab_id_[$key]);
                $tenderpenawarandetail->nilai = str_replace(",","",$request->input_rab_nilai_[$key]);
                $tenderpenawarandetail->total_nilai = str_replace(",","",$request->input_rab_total_nilai_[$key]);
                $tenderpenawarandetail->save();
                if($request->input_sub_id_ != null){
                    if (array_key_exists($key, $request->input_sub_id_)) {
                        if($request->input_sub_id_[$key] != null){
                            foreach ($request->input_sub_id_[$key] as $key2 => $value2) {
                                if( $request->input_sub_nilai_[$key][$key2] != ""){
                                    $tenderpenawaransubdetail = TenderPenawaranSubDetail::find($request->input_sub_id_[$key][$key2]);
                                    $tenderpenawaransubdetail->nilai = str_replace(",","",$request->input_sub_nilai_[$key][$key2]);
                                    $tenderpenawaransubdetail->total_nilai = str_replace(",","",$request->input_sub_total_nilai_[$key][$key2]);
                                    $tenderpenawaransubdetail->save();
                                }
                            }
                        }
                    }
                }
            }else{
                echo $request->input_rab_id_[$key];
            }
        }

        $TenderPenawaran = TenderPenawaran::find($request->tender_id);

        if (!file_exists ("./assets/tender_penawaran/".$TenderPenawaran->rekanan->tender->id )) {
            mkdir("./assets/tender_penawaran/".$TenderPenawaran->rekanan->tender->id);
            chmod("./assets/tender_penawaran/".$TenderPenawaran->rekanan->tender->id,0777);
        }

        if($request->file('fileupload')){
                $uploadedFile = $request->file('fileupload');  
                $type = $uploadedFile->getClientMimeType();
        
                $array_file = array(
                    "application/msword",
                    "application/pdf",
                    "image/jpeg",
                    "image/pjpeg",
                    "image/png",
                    "application/excel",
                    "application/vnd.ms-excel",
                    "application/x-excel",
                    "application/x-msexcel",
                    "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
                    "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
                    'application/zip', 
                    'application/x-zip-compressed', 
                    'multipart/x-zip', 
                    'application/x-compressed',
                    // 'application/rar',
                    // 'application/x-rar-compressed', 
                    // 'multipart/x-rar', 
                );

                // return  $_FILES['gambar_tender']['name'];

                $checkpdf = array_search($type, $array_file);
                if ( $checkpdf != "" ) {
                    $pathpdf = $uploadedFile->store('public/assets/tender_penawaran/'.$TenderPenawaran->rekanan->tender->id);
                    $new_file_name = explode("/", $pathpdf);
                    $tmp_name = $_FILES['fileupload']['tmp_name'];
                    move_uploaded_file($tmp_name, "./assets/tender_penawaran/".$TenderPenawaran->rekanan->tender->id.'/'.$new_file_name[4]);
                    $TenderPenawaran->file_attachment = $pathpdf;
                    $TenderPenawaran->save();
                    // $tender_document->filenames = $pathpdf;
                }else{     
                    // return $_FILES['fileupload']['tmp_name'];
                    // return response()->json(["status" => "Data Berhasil diupload"]);     
                    // return redirect("/workorder/dokument?id=".$request->workorder_budget_id);
                }
            }

        return redirect("/rekanan/user/tender/penawaran-update?id=".$request->tender_id."&step=".$request->step."&exist=2");
    }

    public function downloaddoc(Request $request){
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
    }

    public function ganti_password(Request $request){
        $rekanan_user = UserRekanan::where("rekanan_group_id",$request->id)->first();
        $change = User::where("user_login",$rekanan_user->user_login)->first();
        $change->password = bcrypt($request->pass_baru);
        $change->save();
        
        return response()->json(['success'=> 'Pergantian Password berhasil']);
    }

    public function progress_detail(Request $request){
        $data = [];
        $progress = ProgressTambahan::where("spk_id",$request->id_spk)->where("itempekerjaan_id",$request->id_pekerjaan)->get();

        foreach ($progress as $key => $value) {
            # code...
            $arr = [
                'nama' => $value->itempekerjaans->code,
                'volume' => $value->itempekerjaans->name,
                'uraian' => $value->uraian_pekerjaan,
                'satuan' => $value->volume_budget,
                'ipk' => $value->volume,
                'status' => $value->satuan,
                'unit' => $value->nilai,
                'harga_subtotal' =>$value->volume*$value->nilai,
                'itempekerjaan_id' => $value->itempekerjaan_id,
                'budget_tahunan_detail_id' => $value->id,
            ];
            array_push($data, $arr);
        }

        return response()->json(['data' => $progress]);
    }

    public function pengajuan(Request $request){
        $rekanan_group = RekananGroup::find($request->session()->get('rekanan_id'));
        $spk = Spk::find($request->id);
        $pengajuan = RekananPengajuanIpk::where("spk_id",$spk->id)->get();
        return view("rekanan::user.pengajuan_pengecekan",compact("spk","rekanan_group","pengajuan"));
    }

    public function progress_tahap(Request $request){
        $data = [];
        if($request->tipe == "spk"){
            $progress_tahap = ProgressTambahan::where("spk_id",$request->spk_id)->where("itempekerjaan_id",$request->itempekerjaan_id)->where("unit_id",$request->unit_id)->where('volume','!=',0)->get();
            // return $progress_tahap;
            foreach ($progress_tahap as $key => $value) {
                # code...
                if($value->pengajuan == null){
                    $arr = [
                        "id"        => $value->id,
                        "name"      => $value->name,
                        "volume"    => $value->volume,
                        "satuan"    => $value->satuan,
                    ];
                    array_push($data, $arr);
                }
            }
        }else{
            $vo = NewVo::find($request->vo_id);
            // $progress_tahap = ProgressTambahanVo::where("spk_id",$request->spk_id)->where("itempekerjaan_id",$request->itempekerjaan_id)->where("unit_id",$request->unit_id)->->get();
            // return $progress_tahap;
            foreach ($vo->detail->where("unit_id",$request->unit_id)->where("itempekerjaan_id",$request->itempekerjaan_id) as $key => $value) {
                # code...
                $progress_tahap = ProgressTambahanVo::where("spk_id",$request->spk_id)->where("itempekerjaan_id",$request->itempekerjaan_id)->where("unit_id",$request->unit_id)->where("detail_vo_id",$value->id)->where('volume','!=',0)->get();
                foreach ($progress_tahap as $key2 => $value2) {
                    if($value2->pengajuan == null){
                        $arr = [
                            "id"        => $value2->id,
                            "name"      => $value2->name,
                            "volume"    => $value2->volume,
                            "satuan"    => $value2->satuan,
                        ];
                        array_push($data, $arr);
                    }
                }
            }
        }
        
        return response()->json(['progress_tahap' => $data]);
    }

    public function save_pengajuan(Request $request){
        
        if($request->tglpengajuan != null && $request->progress != null){
            $spk = Spk::find($request->spk_id);
            $pengajuan_no = $spk->no . '/IPK/' . str_pad( ($spk->pengajuan_ipk()->count() + 1) , 2, "0", STR_PAD_LEFT);
            $pengajuan = new RekananPengajuanIpk;
            $pengajuan->rekanan_id = $request->rekanan_group_id;
            $pengajuan->progress_tambahan_id = $request->progress;
            $pengajuan->status_pengajuan = 0;
            $pengajuan->date_pengecekan = $request->tglpengajuan;
            $pengajuan->unit_id = $request->unit;
            $pengajuan->description = $request->keterangan;
            $pengajuan->pic_id = $spk->pic_id;
            $pengajuan->spk_id = $spk->id;
            $pengajuan->no = $pengajuan_no;
            $pengajuan->tipe = $request->spk_vo;
            $pengajuan->save();
            
            if($pengajuan->progress->ipk_progress_tahapan->count() == 0){
                if($request->spk_vo == "spk"){
                    $ipk = IpkTambahan::where("spk_id",$request->spk_id)->where("itempekerjaan_id",$request->pekerjaan)->where("unit_id",$request->unit)->get();
        
                    foreach ($ipk as $key => $value) {
                        # code...
                        $ipk_progress = new IpkProgressTahapan;
                        $ipk_progress->progress_tambahan_id = $request->progress;
                        $ipk_progress->ipk_tambahan_id = $value->id;
                        $ipk_progress->status_ceklis = 0;
                        $ipk_progress->tipe = "spk";
                        $ipk_progress->save();
                    }
                }else{
                    $ipk = IpkTambahan::where("spk_id",$request->spk_id)->where("itempekerjaan_id",$request->pekerjaan)->where("unit_id",$request->unit)->get();
        
                    foreach ($ipk as $key => $value) {
                        # code...
                        $ipk_progress = new IpkProgressTahapan;
                        $ipk_progress->progress_tambahan_id = $request->progress;
                        $ipk_progress->ipk_tambahan_id = $value->id;
                        $ipk_progress->status_ceklis = 0;
                        $ipk_progress->tipe = "vo";
                        $ipk_progress->save();
                    }
                }
            }
        }
        

        return redirect("/rekanan/spk/detail?id=".$request->spk_id);
    }

    public function vo(Request $request){
        $spk = Spk::find($request->spk_id);
        $data = [];
        foreach ($spk->new_vo->where("tipe",1) as $key => $value) {
            # code...
            $arr = [
                "id"        => $value->id,
                "no"        => $value->no,
            ];
            array_push($data, $arr);
        }
        return response()->json(['progress_tahap' => $data]);
    }
    public function vo_detail(Request $request){
        $data = [];
        $detail_vo = DetailVo::where("vo_id",$request->id)->where("unit_id",$request->unit_id)->get();
        foreach ($detail_vo as $key => $value) {
            # code...
            $arr = [
                "id"        => $value->id,
                "pekerjaan_id" => $value->itempekerjaan_id,
                "pekerjaan" => $value->itempekerjaan->name,
            ];
            array_push($data, $arr);
        }
        return response()->json(['progress_tahap' => $data]);
    }

    public function pekerjaan_spk(Request $request){
        $data = [];
        $spk = Spk::find($request->spk_id);
        foreach ($spk->tender_rekanan->menangs->first()->details as $key => $value) {
            # code...
            $ipk = Modules\Spk\Entities\IpkTambahan::where("spk_id",$spk->id)->where("itempekerjaan_id",$value->itempekerjaan_id)->get()->count();
            if($ipk != 0){
                if($value->volume != 0 && $value->volume != null){
                    $arr = [
                        "pekerjaan_id" => $value->itempekerjaan->id,
                        "pekerjaan" => $value->itempekerjaan->name,
                    ];
                    array_push($data, $arr);
                }
            }
        }
        return response()->json(['progress_tahap' => $data]);
    }

    public function showDataBarang(Request $request)
    {
        $rekanan_group = RekananGroup::find($request->session()->get('rekanan_id'));
        $rekanan = $rekanan_group->rekanans->where("parent_id",null);
        return view('rekanan::user.data-barang',compact("rekanan_group","rekanan"));
    }

    public function ajaxPricelistDataTable(Request $request)
    {
        $rekanan = $request->rekanan;
        $pricelist = RekananPricelist::where('rekanan_group_id', $rekanan)
                                        ->orderBy('updated_at', 'desc')
                                        ->get();
        $arr = array();
        foreach($pricelist as $p){
            $obj = new \stdClass();
            $items = ViewRekananPricelist::select('item_name','item_id')
                                    ->where('pricelist_id', $p->id)
                                    ->where('rekan_group_id', $rekanan)
                                    ->groupBy('item_name', 'item_id')
                                    ->get();
            $item_category = ViewRekananPricelist::select('item_category_name', 'item_category_id')
                                    ->where('pricelist_id', $p->id)
                                    ->where('rekan_group_id', $rekanan)
                                    ->first();
            $item = "";
            $itemCat = $item_category['item_category_name'];
            $ii = 0;
            foreach($items as $i){
                $ii++;
                $item .= $i->item_name;
                if($ii != count($items)){
                    $item .= ",";
                }
            }
            $obj->id = $p->id;
            $obj->rekanan_group_id = $p->rekanan_group_id;
            $obj->price_file = $p->price_file;
            $obj->berlaku_dari_tanggal = $p->berlaku_dari_tanggal;
            $obj->berlaku_sampai_tanggal = $p->berlaku_sampai_tanggal;
            $obj->keterangan = $p->keterangan;
            $obj->created_at = $p->created_at;
            $obj->created_by = $p->created_by;
            $obj->updated_at = $p->updated_at;
            $obj->updated_by = $p->updated_by;
            $obj->deleted_at = $p->deleted_at;
            $obj->deleted_by = $p->deleted_by;
            $obj->inactive_by = $p->inactive_by;
            $obj->inactive_at = $p->inactive_at;
            $obj->item = $item;
            $obj->item_category = $itemCat;
            $arr[] = $obj;
        }
        $data_ = new Collection($arr);
        $data = DataTables::of($data_)->make(true);
        return $data;
    }

    public function select2GetItemCat(Request $request)
    {
        $search = $request->search;
        $data = array();
        if($search == ''){
            $item = ItemCategories::select('id','name')
                                    ->where('parent_id', 0)
                                    ->orderBy('name','asc')
                                    //->limit(10)
                                    ->get();
        }else{
            $item = ItemCategories::select('id','name')
                                    ->where('parent_id', 0)
                                    ->where('name', 'like', '%' .$search . '%')
                                    ->orderBy('name','asc')
                                    //->limit(10)
                                    ->get();
        }
        foreach ($item as $i) {
            $data[] = array("id" => $i->id, "text" => $i->name);
        }
        return response()->json($data, 200);
    }

    public function select2GetItemSubCat(Request $request)
    {
        $search = $request->search;
        $catId = $request->category;
        $data = array();
        if($search == ''){
            $item = ItemCategories::orderby('name','asc')
                            ->select('id','name')
                            ->where('parent_id', $catId)
                            ->where('parent_id', '<>', 0)
                            ->limit(10)
                            ->get();
        }else{
            $item = ItemCategories::orderby('name','asc')
                            ->select('id','name')
                            ->where('parent_id', $catId)
                            ->where('parent_id', '<>', 0)
                            ->where('name', 'like', '%' .$search . '%')
                            ->limit(10)
                            ->get();
        }
        foreach ($item as $i) {
            $data[] = array("id" => $i->id, "text" => $i->name);
        }
        return response()->json($data, 200);
    }

    public function select2GetItem(Request $request)
    {
        $search = $request->search;
        $catId = $request->category;
        $subCatId = $request->subcategory;
        $data = array();
        if($search == ''){
            $item = Items::select('id','name')
                            ->where('item_category_id', $catId)
                            //->limit(10)
                            ->groupBy('id','name')
                            ->orderBy('name','asc')
                            ->get();
        }else{
            $item = Items::select('id','name')
                            ->where('item_category_id', $catId)
                            ->where('name', 'like', '%' .$search . '%')
                            //->limit(10)
                            ->groupBy('id','name')
                            ->orderBy('name','asc')
                            ->get();
        }
        foreach ($item as $i) {
            $data[] = array("id" => $i->id, "text" => $i->name);
        }
        return response()->json($data, 200);
    }

    public function storeSupplierPricelist(Request $request)
    {
        $response = "";
        try {
            if($request->rekanan_group_id != null && $request->tanggal_berlaku != null && $request->pricefile != "undefined" && $request->items != null){
                if (!file_exists (public_path()."/assets/rekanan/".$request->rekanan_group_id )) {
                    mkdir(public_path()."/assets/rekanan/".$request->rekanan_group_id, 0755, true);
                    chmod(public_path()."/assets/rekanan/".$request->rekanan_group_id,0755);
                }
                $dateNow = date('YmdHis');
                $pricelist = new RekananPricelist();
                if ($_FILES['pricefile']['size'] != 0 && ($_FILES['pricefile']['error'] != 0 || $_FILES['pricefile']['error'] != 4))
                {
                    try {
                        $filename = $dateNow.'_'.$_FILES['pricefile']['name'];
                        $target_file = public_path()."/assets/rekanan/".$request->rekanan_group_id."/".$filename;
                        $move = move_uploaded_file($_FILES["pricefile"]["tmp_name"], $target_file);
                        if($move){
                            $pricelist->price_file = $filename;
                            $exBerlaku = explode('-', $request->tanggal_berlaku);
                            $berlaku_dari_tanggal = $exBerlaku[0];
                            $berlaku_sampai_tanggal = $exBerlaku[1];
                            $pricelist->berlaku_dari_tanggal = $berlaku_dari_tanggal;
                            $pricelist->berlaku_sampai_tanggal = $berlaku_sampai_tanggal;
                            $pricelist->keterangan = ($request->keterangan != null) ? $request->keterangan : "";
                            $pricelist->rekanan_group_id = $request->rekanan_group_id;
                            $pricelist->save();
                            $items = json_decode($request->items);
                            foreach($items as $i){
                                $item = new RekananItems();
                                $item->rekanan_group_id = $request->rekanan_group_id;
                                $item->item_id = $i;
                                $item->pricelist_id = $pricelist->id;
                                $item->save();
                            }
                            if(($pricelist) && ($item)){
                                $response = [ "status" => "success", "msg" => "Pricelist tersimpan"];
                            }else{
                                $response = [ "status" => "error", "msg" => "Pricelist tidak tersimpan"];
                            }
                        }                        
                    }catch(Exception $ex){
                        $response = ["status" => "error", "msg" => $ex];
                    }
                }
            }else{
                $response = [ "status" => "error", "msg" => "Data tidak boleh kosong"];
            }
        }catch(Exception $e){
            $response = ["status" => "error", "msg" => $e];
        }
        return response()->json($response, 200);
    }

    public function getSupplierProject(Request $request){
        $idSupplier = $request->rekanan;
        $po = LibrarySupplierProjectPODetail::where('rekan_id', $idSupplier)->get();
        $project = DataTables::of($po)->make(true);
        return $project;
    }


    //List MOU
    public function getMOUDataTable(Request $request)
    {
        $rekan = $request->rekanan;
        $mou_ = LibraryMOUDetails::select('nomor_mou')
                                ->where('rekan_id', $rekan)
                                ->groupBy('nomor_mou')
                                ->get();
        $arr = array();
        foreach($mou_ as $m_){
            $obj = new \stdClass();
            $mou = LibraryMOUDetails::where('nomor_mou', $m_->nomor_mou)
                                        ->where('rekan_id', $rekan)
                                        ->get();
            $item = "";
            $ii = 0;
            foreach($mou as $m){
                $ii++;
                $item .= $m->item_name;
                if($ii != count($mou)){
                    $item .= ",";
                }
                $obj->proj_name = $m->proj_name;
                $obj->jenis_mou = $m->jenis_mou;
                $obj->file_mou = $m->file_mou;
                $obj->rekan_id = $m->rekan_id;
                $obj->project_id = $m->project_id;
                $obj->id = $m->id;
            }
            $obj->nomor_mou = $m_->nomor_mou;
            $obj->item_name = $item;
            $arr[] = $obj;
        }

        $data = new Collection($arr);
        $dt = DataTables::of($data)->make(true);
        return $dt;
    }
}
