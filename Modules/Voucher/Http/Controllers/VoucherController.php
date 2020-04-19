<?php

namespace Modules\Voucher\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Project\Entities\Project;
use Modules\Voucher\Entities\Voucher;
use Modules\Voucher\Entities\VoucherDetail;
use Modules\Spk\Entities\Bap;
use Modules\Rekanan\Entities\RekananGroup;
use Modules\Spk\Entities\SpkRetensi;
use Modules\Pekerjaan\Entities\Itempekerjaan;
use App\Http\Controllers\ApiController;
use Modules\Pekerjaan\Entities\CoaCpmsFinance;
use Modules\Globalsetting\Entities\Globalsetting;
use Modules\Voucher\Entities\BankGaransi;

class VoucherController extends Controller
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
        $arraypph = array(
            "0" => array("label" => "21.40.110 ( PPh 21)", "value" => "pph21"),
            "1" => array("label" => "21.40.130 ( PPh 23)", "value" => "pph23"),
            "2" => array("label" => "21.40.140 (PPh Final)", "value" => "pphfinal")
        );
        $voucher = Voucher::where("project_id", $project->id)->orderBy("id", "DESC")->get();
        return view('voucher::index',compact("user","project","arraypph","voucher"));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create(Request $request)
    {
        $user = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        $arraypph = array(
            "0" => array("label" => "21.40.110 ( PPh 21)", "value" => "pph21"),
            "1" => array("label" => "21.40.130 ( PPh 23)", "value" => "pph23"),
            "2" => array("label" => "21.40.140 (PPh Final)", "value" => "pphfinal")
        );
        return view('voucher::create',compact("user","project","arraypph"));
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $voucher = new Voucher;
        $document = \Modules\Spk\Entities\Bap::find($request->bap);
        $voucher_count = Voucher::count();              
    

        if ( $request->tender_rab == "Bap"){     
            $bap = Bap::find($request->bap);
            $explodespk = explode("/",$bap->spk->no);         
                $voucher_no = /*$bap->spk->no .*/ "00".str_pad( $voucher_count + 1 ,2,"0",STR_PAD_LEFT).'/VCR/'.$explodespk[2]."/".$explodespk[3]."/".$explodespk[4]."/".$bap->spk->project->code."/".$bap->spk->tender->rab->pt->code;
                $voucher->head_id = $request->bap;
                $voucher->head_type = $request->tender_rab;
                $voucher->rekanan_id = $bap->spk->rekanan->group->id;
                $voucher->department_id = $bap->spk->tender_rekanan->tender->rab->workorder->department_from;
                $voucher->pt_id = $bap->spk->pt->id;
                $voucher->project_id = $bap->spk->project_id;
                $voucher->date = date("Y-m-d");
                $voucher->no = $voucher_no;
                $voucher->no_faktur = $request->no_faktur;
                $voucher->spm_status = $request->spm;
                if($bap->percentage_sebelumnyas == 0){
                    $batas = $bap->percentage + ceil($bap->percentage*($bap->spk->retensis->sum("percent")/100)) + \Modules\Globalsetting\Entities\Globalsetting::where('parameter','counter_progress')->first()->value;
                    if($batas <= $bap->spk->lapangan ){
                        $voucher->pencairan_status = 1;
                        // || $bap->spk->tender->aanwijing->jenis_pembayaran == 3
                    }else{
                        $voucher->pencairan_status = 0;
                    }
                }else{
                    $voucher->pencairan_status = 1;
                }
                $voucher->surat_bast = $request->bast;
                $voucher->save();
                
                $explode = explode(" ", $bap->spk->rekanan->group->name);
                

                // foreach ($bap->spk->details as $key3 => $value3) {
                    ;
                    $dpp_string =  $bap->spk->rekanan->group->name."-T".$bap->spk->baps->count()."-".$explodespk[0].$explodespk[1].date("Y")."-".$bap->spk->itempekerjaan->name."-".$bap->spk->tender->rab->name ;
                    $dpp_string_new = substr($dpp_string, 0,100);
                    $voucher_detail = new VoucherDetail;
                    $voucher_detail->voucher_id = $voucher->id;

                    $coa = CoaCpmsFinance::where("project_id", $bap->spk->project_id)->where("pt_id", $bap->spk->pt->id)->where("itempekerjaan_id", $bap->spk->tender->rab->workorder->detail_pekerjaan[0]->itempekerjaan_id)->first();
                    $voucher_detail->coa_id = $coa->coa_finance;  
                    $voucher_detail->nilai = (Int)$bap->nilai_bap_1;
                    $voucher_detail->type = $dpp_string_new;
                    $voucher_detail->mata_uang =  $bap->spk->mata_uang;
                    $voucher_detail->kurs = $bap->spk->nilai_tukar;
                    $voucher_detail->created_by = \Auth::user()->id;
                    $voucher_detail->save();
                // }                

                if ( $bap->nilai_retensi != "" ){
                    $voucher_detail = new VoucherDetail;
                    $voucher_detail->voucher_id = $voucher->id;
                    $coa = CoaCpmsFinance::where("project_id", $bap->spk->project_id)->where("pt_id", $bap->spk->pt->id)->where("itempekerjaan_id", $bap->spk->tender->rab->workorder->detail_pekerjaan[0]->itempekerjaan_id)->first();
                    $voucher_detail->coa_id =  $coa->coa_finance;       
                    $voucher_detail->nilai = -(Int)$bap->nilai_retensi;
                    $voucher_detail->type = "Nilai Retensi";
                    $voucher_detail->mata_uang =  $bap->spk->mata_uang;
                    $voucher_detail->kurs = $bap->spk->nilai_tukar;
                    $voucher_detail->created_by = \Auth::user()->id;
                    $voucher_detail->save();
                }

                if ( $bap->nilai_kurang_bayar_vo != "" ){
                    $voucher_detail = new VoucherDetail;
                    $voucher_detail->voucher_id = $voucher->id;
                    $coa = CoaCpmsFinance::where("project_id", $bap->spk->project_id)->where("pt_id", $bap->spk->pt->id)->where("itempekerjaan_id", $bap->spk->tender->rab->workorder->detail_pekerjaan[0]->itempekerjaan_id)->first();
                    $voucher_detail->coa_id =  $coa->coa_finance;       
                    $voucher_detail->nilai = -(Int)$bap->nilai_kurang_bayar_vo;
                    $voucher_detail->type = "Nilai Kekurangan Bayar Vo+Retensi";
                    $voucher_detail->mata_uang =  $bap->spk->mata_uang;
                    $voucher_detail->kurs = $bap->spk->nilai_tukar;
                    $voucher_detail->created_by = \Auth::user()->id;
                    $voucher_detail->save();
                }

                if ( $bap->nilai_talangan != "" ){
                    $voucher_detail = new VoucherDetail;
                    $voucher_detail->voucher_id = $voucher->id;
                    $coa = CoaCpmsFinance::where("project_id", $bap->spk->project_id)->where("pt_id", $bap->spk->pt->id)->where("itempekerjaan_id", $bap->spk->tender->rab->workorder->detail_pekerjaan[0]->itempekerjaan_id)->first();
                    $voucher_detail->coa_id =  $coa->coa_finance;       
                    $voucher_detail->nilai = (Int)$bap->nilai_talangan;
                    $voucher_detail->type = "Nilai Talangan";
                    $voucher_detail->mata_uang =  $bap->spk->mata_uang;
                    $voucher_detail->kurs = $bap->spk->nilai_tukar;
                    $voucher_detail->created_by = \Auth::user()->id;
                    $voucher_detail->save();
                }

                if ( $bap->nilai_ppn != 0 || $bap->nilai_ppn != null){
                    $ppn_string = "PPN";
                    $substring_ppn = substr($ppn_string, 0,100);
                    $voucher_detail = new VoucherDetail;
                    $voucher_detail->voucher_id = $voucher->id;
                    $coa = CoaCpmsFinance::where("project_id", $bap->spk->project_id)->where("pt_id", $bap->spk->pt->id)->where("tipe_coa_id", 5)->first();
                    $voucher_detail->coa_id =  $coa->coa_finance;   
                    // $voucher_detail->coa_id = \Modules\Globalsetting\Entities\Globalsetting::where('id',1004)->first()->value;      
                    $voucher_detail->nilai = (Int)$bap->nilai_ppn;
                    $voucher_detail->type = $substring_ppn;
                    $voucher_detail->mata_uang =  $bap->spk->mata_uang;
                    $voucher_detail->kurs = $bap->spk->nilai_tukar;
                    $voucher_detail->created_by = \Auth::user()->id;
                    $voucher_detail->save();
                }

                if ( $bap->nilai_pph != 0 || $bap->nilai_pph != null ){

                    $explode_pph = explode("(",$request->pph);
                    $voucher_detail = new VoucherDetail;
                    $voucher_detail->voucher_id = $voucher->id;
                    $coa = CoaCpmsFinance::where("project_id", $bap->spk->project_id)->where("pt_id", $bap->spk->pt->id)->where("pph_rekanan_id", $bap->spk->pph->id)->first();
                    $voucher_detail->coa_id = $coa->coa_finance; 
                    $pph = $bap->nilai_pph;
                    $voucher_detail->nilai = -(int)$pph;
                    $voucher_detail->head_type = "PPh";
                    $voucher_detail->type = "PPH ".$bap->spk->pph->nilai."% ".$bap->spk->rekanans->name." ".$bap->spk->pph->name;
                    $voucher_detail->mata_uang =  $bap->spk->mata_uang;
                    $voucher_detail->kurs = $bap->spk->nilai_tukar;
                    $voucher_detail->created_by = \Auth::user()->id;
                    $voucher_detail->save();
                }

                if ( $bap->nilai_administrasi != "0.0" ){
                    $voucher_detail = new VoucherDetail;
                    $voucher_detail->voucher_id = $voucher->id;
                    $coa = CoaCpmsFinance::where("project_id", $bap->spk->project_id)->where("pt_id", $bap->spk->pt->id)->where("tipe_coa_id", 3)->first();
                    $voucher_detail->coa_id =  $coa->coa_finance;      
                    $voucher_detail->nilai = -(int)$bap->nilai_administrasi;
                    $voucher_detail->type = "Nilai Administrasi";
                    $voucher_detail->mata_uang =  $bap->spk->mata_uang;
                    $voucher_detail->kurs = $bap->spk->nilai_tukar;
                    $voucher_detail->created_by = \Auth::user()->id;
                    $voucher_detail->save();
                }

                if ( $bap->nilai_denda != "0.0" ){
                    $voucher_detail = new VoucherDetail;
                    $voucher_detail->voucher_id = $voucher->id;
                    $coa = CoaCpmsFinance::where("project_id", $bap->spk->project_id)->where("pt_id", $bap->spk->pt->id)->where("tipe_coa_id", 4)->first();
                    $voucher_detail->coa_id =  $coa->coa_finance;      
                    $voucher_detail->nilai = -(int)$bap->nilai_denda;
                    $voucher_detail->type = "Nilai Denda";
                    $voucher_detail->mata_uang =  $bap->spk->mata_uang;
                    $voucher_detail->kurs = $bap->spk->nilai_tukar;
                    $voucher_detail->created_by = \Auth::user()->id;
                    $voucher_detail->save();
                }

            $bap_update = Bap::find($request->bap);
            $bap_update->status_voucher = 1;
            $bap_update->save();
        }
        
        
        
        return redirect("/voucher/show/?id=".$voucher->id);
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show(Request $request)
    {
        $voucher = Voucher::find($request->id);
        $user = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        $arraypph = array(
            "0" => array("label" => "21.40.110 ( PPh 21)", "value" => "pph21"),
            "1" => array("label" => "21.40.130 ( PPh 23)", "value" => "pph23"),
            "2" => array("label" => "21.40.140 (PPh Final)", "value" => "pphfinal")
        );
        $counter = Globalsetting::where("id",1005)->first()->value;
        return view('voucher::detail',compact("user","project","voucher","arraypph","counter"));
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function savedetail(Request $request)
    {
        return view('voucher::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request)
    {
        $voucher = Voucher::find($request->voucher_id);
        // $voucher->rekanan_rekening_id = $request->rekanan_rekening;
        $voucher->tempo_date = date("Y-m-d", strtotime($request->tempo));
        $voucher->save();

        // $voucher->penyerahan_date = $request->diserahkan;
        // $voucher->pencairan_date = $request->pencairan;
        // $voucher->description = $request->description;
        //$voucher->rekanan_rekening_id = $request->rekanan_rekening;


        // $arraypph = array(
        //     "pph21" => array("label" => "PPh 21", "value" => "pph21", "percent" => "2", "coa" => "21.40.110"),
        //     "pph23" => array("label" => "PPh 23", "value" => "pph23", "percent" => "2", "coa" => "21.40.130"),
        //     "pphfinal" => array("label" => "PPh Final", "value" => "pphfinal", "percent" => "2", "coa" => "21.40.140")
        // );

        
        // if ( isset($request->id_detail)){
        //     $voucher_detail = VoucherDetail::find($request->id_detail);
        //     $label = explode("-", $voucher_detail->type);        
        //     if ( $voucher->bap->spk->rekanan->ppn == null ){
        //         $voucher_detail->nilai = "-".$voucher->bap->nilai_bap_dibayar * ( $request->pph_percent / 100 );
        //     }else{
        //         $voucher_detail->nilai = "-".( $voucher->bap->nilai_bap_dibayar / 1.1 ) * ( $request->pph_percent / 100 );
        //     }

        //     $voucher_detail->coa_id = $voucher->bap->spk->pph->pasal->kode;
        //     $voucher_detail->type = $voucher->bap->spk->pph->pasal->label."-".$label[1]."-".$label[2]."-".$label[3]."-".$label[4]."-".$label[5];
        //     $voucher_detail->save();
        // }
        // $rekanangroup = RekananGroup::find($voucher->bap->spk->rekanan->group->id);
        // $rekanangroup->pph_percent = (int)$request->pph_percent;
        // $rekanangroup->save();
        return redirect("/voucher/show?id=".$voucher->id);
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy()
    {
    }

    public function detail_unit(Request $request){
        $voucher = Voucher::find($request->id);
        $user    = \Auth::user();
        $project = $project = Project::find($request->session()->get('project_id'));
        return view("voucher::voucher_unit",compact("voucher","user","project"));
    }

    public function checkbap(Request $request){
        $bap = Bap::find($request->id);

        $coa_pekerjaan = CoaCpmsFinance::where("project_id", $bap->spk->project_id)->where("pt_id", $bap->spk->pt->id)->where("itempekerjaan_id", $bap->spk->tender->rab->workorder->detail_pekerjaan[0]->itempekerjaan_id)->first();

        $coa_pph = CoaCpmsFinance::where("project_id", $bap->spk->project_id)->where("pt_id", $bap->spk->pt->id)->where("pph_rekanan_id", $bap->spk->pph->id)->first();

        $coa_ppn = CoaCpmsFinance::where("project_id", $bap->spk->project_id)->where("pt_id", $bap->spk->pt->id)->where("tipe_coa_id", 5)->first();

        $coa_admin = CoaCpmsFinance::where("project_id", $bap->spk->project_id)->where("pt_id", $bap->spk->pt->id)->where("tipe_coa_id", 3)->first();

        if($bap->nilai_denda != 0){
            $coa_denda = CoaCpmsFinance::where("project_id", $bap->spk->project_id)->where("pt_id", $bap->spk->pt->id)->where("tipe_coa_id", 4)->first();
        }else{
            $coa_denda = 1;
        }

        if($coa_pekerjaan != null && $coa_pph != null && $coa_ppn != null && $coa_admin != null && $coa_denda != null){
            $status = 1;
        }else{
            $status = 0;
        }
        return response()->json( ["status" =>  $status, "pekerjaan" => $coa_pekerjaan, "pph" => $coa_pph, "ppn" => $coa_ppn , "admin" => $coa_admin, "denda" => $coa_denda] );
    }

    public function kirim_kasir(Request $request){
        // http://13.76.184.138:8080/api/cashierapi/index.php/ems/uploadvoucher
        // https://api.ciputragroup.com/cashierapi/index.php/cpms/requestkey
        $user = \Auth::user();
        // return $user;
        $voucher = Voucher::find($request->voucher_id);
        $request_key = (object)json_decode(ApiController::CallAPI("GET", "http://13.76.184.138:8080/api/cashierapi/index.php/cpms/requestkey"),true);
        // return response()->json( ["request_key" => $request_key] );
        // $request_key = json_decode($var);
        $voucher_details = [];
        $coa = (object)[];
        foreach($voucher->details as $key => $value){
            if(array_key_exists($value->coa_id,$voucher_details)){
                $voucher_details["$value->coa_id"]["nilai"] += $value->nilai;                
            }else{
                $voucher_details["$value->coa_id"]["nilai"] = $value->nilai;
                $voucher_details["$value->coa_id"]["type"] = $value->type;
            }
        }
        
        $dataValidasi = (object) [];
        $dataValidasi->project_id = $voucher->bap->spk->project->project_id;
        $dataValidasi->pt_id = $voucher->bap->spk->pt->pt_id;

        $hasil = [];
        $j = 0;
        $validasi = 0;
        for ($i=0; $i <count($voucher_details) ; $i++) { 
            # code...
            foreach ($voucher->bap->spk->details as $key => $value) {
                # code...
                $j++;
                $dataValidasi->coa_detail = array_keys($voucher_details)[$i];
                $dataValidasi->sub_unit = $value->asset->name;
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
                // return response()->json($data);
                // return response()->json( ["data" => $data] );
                $validasi = $data->result;
                if($data->result == 0){
                    return response()->json($data);
                    return response()->json( ["response" => $data->message] );
                    die;
                }
            }
        }


        if($validasi == 1){
            for ($i=0; $i <count($voucher_details) ; $i++) { 
                # code...
                if($i == 0){
                    $note = $voucher_details[array_keys($voucher_details)[$i]]["type"];
                    
                    if( $voucher->no_faktur != null){
                        $note = $note." no faktur pajak ".$voucher->no_faktur;
                    }
                }
                foreach ($voucher->bap->spk->details as $key => $value) {
                    # code...
                    $j++;
                    // $dataValidasi->coa_detail = array_keys($voucher_details)[$i];
                    // $dataValidasi->sub_unit = $value->asset->name;
                    // $url = "http://13.76.184.138:8080/api/cashierapi/index.php/cpms/validasivoucher";
                    // $ch = curl_init();
                    // curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                    // curl_setopt($ch, CURLOPT_URL, $url);
                    // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    // curl_setopt($ch, CURLOPT_TIMEOUT, 60);
                    // curl_setopt($ch, CURLOPT_HTTPHEADER, $dataValidasi);
                    // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    // curl_setopt($ch, CURLOPT_POST, true);
                    // curl_setopt($ch, CURLOPT_POSTFIELDS, $dataValidasi);
            
                    // $data = curl_exec($ch);
                    // $data = json_decode($data);


                    $dataUpload = (object) [];
                    $date =date("Y-m-d");
                    // if($data->result == 1){
                        $dataUpload->request_key = $request_key->key;
                        $dataUpload->project_id = $voucher->bap->spk->project->project_id;
                        $dataUpload->pt_id = $voucher->bap->spk->pt->pt_id;
                        $dataUpload->uploaduniquenumber = $voucher->id;
                        $dataUpload->department = "C&D";
                        $dataUpload->coa_header = null;
                        $dataUpload->dataflow = "O";
                        $dataUpload->amount_header = $voucher->details->sum('nilai');
                        $dataUpload->note = $note;
                        $dataUpload->is_customer = 0;
                        $dataUpload->is_vendor = 1;
                        $dataUpload->vendor_name = $voucher->bap->spk->rekanan->group->name;
                        $dataUpload->pengajuandate = $date;
                        $dataUpload->kwitansidate = null;
                        $dataUpload->duedate = date("Y-m-d",strtotime($voucher->tempo_date)) ;
                        $dataUpload->receipt_no = null;
                        $dataUpload->coa_detail = array_keys($voucher_details)[$i];
                        $dataUpload->description = $voucher_details[array_keys($voucher_details)[$i]]["type"]."-".$value->asset->name;
                        $dataUpload->sub_unit = $value->asset->name;
                        $dataUpload->seq_detail = $j;
                        if(count($voucher->bap->unit_persen) == 0){
                            $dataUpload->amount = $voucher_details[array_keys($voucher_details)[$i]]["nilai"] / count($voucher->bap->spk->details);
                        }else{
                            $dataUpload->amount = $voucher_details[array_keys($voucher_details)[$i]]["nilai"] / count($voucher->bap->spk->details);
                        }
                        $dataUpload->spk = $voucher->bap->no;
                        if($voucher->bap->spk->tender->rab->workorder->projectKawasan != null){
                            $dataUpload->kawasan = $voucher->bap->spk->tender->rab->workorder->projectKawasan->name;
                        }else{
                            $dataUpload->kawasan = null;
                        }
                        $dataUpload->paymentdate = null;
                        $dataUpload->user_id = $user->user_id;
                        // dump($dataUpload);
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
                        // var_dump($dataUpload);
                        // return response()->json( ["data" => $data2] );

                        $hasil[array_keys($voucher_details)[$i]][$value->asset->name] = $data2;
                    // }
                }
            }

            // return response()->json( ["data" => $data2] );
            $voucher_save = Voucher::find($request->voucher_id);
            $voucher_save->voucher_id = $data2->voucherid;
            $voucher_save->save();
        }

        return response()->json( ["response" => "berhasil terkirim"] );
    }


    public function aneh(Request $request){
        $dataValidasi = (object) [];
            $dataValidasi->project_id           = $project_id_erems;
            $dataValidasi->pt_id                = $pt_id_erems;
            $dataValidasi->uploaduniquenumber   = $id_header;
            $dataValidasi->department           = "ESTATE";
            $dataValidasi->dataflow             = "I";
            $dataValidasi->is_customer          = 0;
            $dataValidasi->is_vendor            = 1;
            $dataValidasi->vendor_name          = "ESTATE";
            $dataValidasi->duedate              = "";
            $dataValidasi->status               = "";
            $dataValidasi->vid                  = "";
            $dataValidasi->is_posting           = "";
            $dataValidasi->spk                  = "";
            $dataValidasi->receipt_no           = "";
            $dataValidasi->requestkey           = $key;
            $dataValidasi->amount_header        = $total_nilai;
            $dataValidasi->coa_header           = $data[0]->coa_cara_pembayaran;
            $dataValidasi->note                 = "ESTATE " . $data[0]->cara_pembayaran . " " . date("d/m/Y");
            $jumlahBerhasil = 0;
            $jumlahGagal = 0;
            $data_uploud = [];
            $data_validasi = [];
            foreach ($data as $k => $v) {
                $data_uploud_detail = (object) [];
                // var_dump($v);
                $dataValidasi->pengajuandate        = $v->tgl_bayar2; //tgl_bayar
                $dataValidasi->kwitansidate         = $v->tgl_bayar2; //tgl_bayar
                $dataValidasi->coa_detail           = $v->item_coa;
                $dataValidasi->description          = "ESTATE $v->kawasan $v->blok/$v->no_unit : $v->periode_tagihan";
                $dataValidasi->sub_unit             = "$v->blok/$v->no_unit";
                $dataValidasi->seq_detail           = $k;
                $dataValidasi->amount               = $v->nilai_item;
                $dataValidasi->kawasan              = $v->kawasan;
                $dataValidasi->paymentdate          = $v->tgl_bayar2; //tgl_bayar 

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
            }
    }

    public function validasi_voucher(Request $request){
        // http://13.76.184.138:8080/api/cashierapi/index.php/ems/uploadvoucher
        // https://api.ciputragroup.com/cashierapi/index.php/cpms/requestkey
        $user = \Auth::user();
        // return $user;
        $voucher = Voucher::find($request->voucher_id);
        $request_key = (object)json_decode(ApiController::CallAPI("GET", "http://13.76.184.138:8080/api/cashierapi/index.php/cpms/requestkey"),true);
        // return response()->json( ["request_key" => $request_key] );
        // $request_key = json_decode($var);
        $voucher_details = [];
        $coa = (object)[];
        foreach($voucher->details as $key => $value){
            if(array_key_exists($value->coa_id,$voucher_details)){
                $voucher_details["$value->coa_id"]["nilai"] += $value->nilai;                
            }else{
                $voucher_details["$value->coa_id"]["nilai"] = $value->nilai;
                $voucher_details["$value->coa_id"]["type"] = $value->type;
            }
        }
        
        $dataValidasi = (object) [];
        $dataValidasi->project_id = $voucher->bap->spk->project->project_id;
        $dataValidasi->pt_id = $voucher->bap->spk->pt->pt_id;

        $hasil = [];
        $j = 0;
        $validasi = 0;
        for ($i=0; $i <count($voucher_details) ; $i++) { 
            # code...
            foreach ($voucher->bap->spk->details as $key => $value) {
                # code...
                $j++;
                $dataValidasi->coa_detail = array_keys($voucher_details)[$i];
                $dataValidasi->sub_unit = $value->asset->name;
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
                // return $data;
                if($data->result == 0){
                    // return response()->json($data);
                    return response()->json( ["response" => $data->message] );
                    die;
                }
            }
        }



        return response()->json( ["response" => "berhasil terkirim"] );
    }

    public function simpanBankGaransi(Request $request){
        // return $request;
        $bank_garansi = new BankGaransi;
        $bank_garansi->voucher_id = $request->voucher_id;
        $bank_garansi->no_bank_garansi = $request->no_bg;
        $bank_garansi->nama_bank = $request->nama_bank;
        $bank_garansi->nilai = $request->nilai_bg;
        $bank_garansi->tanggal_bank_garansi = $request->tanggal_bg;
        $bank_garansi->tanggal_jatuh_tempo = $request->tanggal_jatuh_tempo_bg;
        $bank_garansi->save();

        if($bank_garansi->no_bank_garansi != null && $bank_garansi->nama_bank != null && $bank_garansi->nilai != null && $bank_garansi->tanggal_bank_garansi != null && $bank_garansi->tanggal_jatuh_tempo != null){
            $voucher = Voucher::find( $request->voucher_id);
            $voucher->pencairan_status = 1;
            $voucher->save();
        }

        return response()->json( ["response" => "1"] );
    }

    public function cekBankGaransi(Request $request){
        // return $request;
        $voucher = Voucher::find( $request->voucher_id);
        return response()->json( ["data" => $voucher->bank_garansi] );
    }

}
