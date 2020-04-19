<?php

namespace Modules\Workorder\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Project\Entities\Project;
use Modules\Project\Entities\Unit;
use Modules\Workorder\Entities\Workorder;
use Modules\Workorder\Entities\WorkorderBudgetDetail;
use Modules\Workorder\Entities\WorkorderDetail;
use Modules\Department\Entities\Department;
use Modules\Budget\Entities\BudgetTahunan;
use Modules\Pekerjaan\Entities\Itempekerjaan;
use Modules\BudgetDraft\Entities\BudgetDraft;
use Modules\BudgetDraft\Entities\BudgetDraftDetail;
use Illuminate\Support\Facades\Mail;
use \App\Mail\OrderShipped;
use \App\Mail\EmailApproved;
use \App\Mail\EmailApproved2;
use Modules\Approval\Entities\Approval;
use Modules\Tender\Entities\TenderDocument;
use Storage;
use Modules\Project\Entities\ProjectKawasan;
use Modules\Project\Entities\ProjectPt;
use Modules\Rab\Entities\Rab;
use Modules\Rab\Entities\RabUnit;
use DB;

class WorkorderController extends Controller
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
        // foreach (Workorder::all() as $key => $value) {
        //     # code...
        //     if(count($value->detail_pekerjaan) != 0){
        //         if($value->detail_pekerjaan[0]->budget_tahunan->budget->kawasan != null){
        //             $workorder = Workorder::find($value->id);
        //             $workorder->real_budget_tahunan_id = $value->detail_pekerjaan[0]->budget_tahunan->id;
        //             $workorder->kawasan_id = $value->detail_pekerjaan[0]->budget_tahunan->budget->kawasan->id;
        //             $workorder->save();
        //         }else{
        //             $workorder = Workorder::find($value->id);
        //             $workorder->real_budget_tahunan_id = $value->detail_pekerjaan[0]->budget_tahunan->id;
        //             $workorder->kawasan_id = null;
        //             $workorder->save();
        //         }
        //     }
        // }
        $user = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        //$workorder = $project->workorder;
        $workorder = $project->workorders;
        $itempekerjaan = Itempekerjaan::get();
        $department = Department::get();
        return view('workorder::index',compact("user","project","workorder","itempekerjaan","department"));
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
        $itempekerjaan_parent = Itempekerjaan::where('parent_id', null)->orderBy("code", "ASC")->get();
        return view('workorder::create',compact("project","user","itempekerjaan_parent"));
    }

    public function getfasilitas(Request $request){
        $dep = $request->dep;
        $user = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        $user_pt = $user->project_pt_users->where("project_id",$project->id);
        $budget = Department::where('id',$dep)->first()->budgets->where("pt_id",$user_pt[0]->pt_id);
        $data = [];
        $year =Date('Y');
        // return $project->kawasans;
        foreach ( $budget as $key => $value ){
            if ( $value->deleted_at == ""){
                if ( $value->project_id == $project->id ){
                    // foreach ( $value->budget_tahunans as $key2 => $value2 ){
                    // $value2 = $value->budget_tahunans->where('tahun_anggaran',(string)$year)->last();
                    $value2 = $value->budget_tahunans->last();
                    // return $value2->budget->kawasan;
                        if($value2 != null){
                            $row['id'] = $value2['id'];
                            if($value2->budget->kawasan != null){
                                $row['name'] = $value2->budget->kawasan->name;
                            }elseif($value2->budget->project_kawasan_id == null){
                                $row['name'] = 'Fasilitas Kota';
                            }                    
                            $data[] = $row;
                        }
                    // }
                }
            }
        }
        // return $data;
        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $start_date = date("Y-m-d");
        $project = Project::find($request->session()->get('project_id'));
        $work_order_no = \App\Helpers\Document::new_number('WO', $request->department_from,$project->id);
        $work_order = new Workorder;
        $work_order->budget_tahunan_id = $request->session()->get('project_id');
        $work_order->department_from = $request->department_from;
        $work_order->department_to = $request->department_to;
        $work_order->no = $work_order_no;
        $work_order->name = $request->workorder_name;
        $work_order->durasi = '0';
        $work_order->satuan_waktu = '0';
        $work_order->date = $start_date;
        $work_order->estimasi_nilaiwo = '0';
        $work_order->description = $request->workorder_description;
        $work_order->created_by = \Auth::user()->id;
        $work_order->end_date = NULL;
        // $work_order->real_budget_tahunan_id = $request->budget_tahunan;
        // $budget_tahunan = BudgetTahunan::find($request->budget_tahunan);
        // if($budget_tahunan->budget->kawasan != null){
        //     $kawasan = $budget_tahunan->budget->kawasan->id;
        // }else{
        //     $kawasan = null;
        // }
        $work_order->kawasan_id = $request->kawasan;
        $work_order->pt_id = $request->pt_id;
        $work_order->save();
            
        $wo  = Workorder::find($work_order->id);
        $no = $wo->no;
        $wo->no = $no.$wo->pt_wo->code;
        $wo->save();

        $id_workorder = $work_order->id;
        
        $itempekerjaan = Itempekerjaan::find($request->sub_item_pekerjaan);
        $workorder = new WorkorderBudgetDetail;
        $workorder->workorder_id = $id_workorder;
        $workorder->budget_tahunan_id = null;
        $workorder->itempekerjaan_id = $request->sub_item_pekerjaan;
        $workorder->tahun_anggaran = date('Y');
        $workorder->volume = 0;
        $workorder->satuan = $itempekerjaan->satuan;
        $workorder->nilai = 0;
        $workorder->save();
        
        $units = json_decode($request->all_send_unit);

        if ( $request->cluster_faskot == "faskot" || $request->kawasan == ""){
            $WorkorderDetail = new WorkorderDetail;
            $WorkorderDetail->workorder_id = $id_workorder;
            $WorkorderDetail->asset_id = $project->id;
            $WorkorderDetail->asset_type = "Modules\Project\Entities\Project";
            $WorkorderDetail->save();
        }else{
            if($request->radio == "unit"){
                for ($i=0; $i < count($units); $i++) { 
                    # code...
                    if ( $units[$i][0] != "" ){
        
                        $asset_exist = WorkorderDetail::where("asset_id",str_replace("Unit_","",$units[$i][0]))->where("workorder_id",$id_workorder)->where("asset_type","Modules\Project\Entities\Unit")->get();
                        
                        if ( count($asset_exist) <= 0 ){
                            $workorder_unit = new WorkorderDetail;
                            $workorder_unit->workorder_id = $id_workorder;
                            $workorder_unit->asset_id = $units[$i][0];
                            $workorder_unit->asset_type = "Modules\Project\Entities\Unit";
                            $workorder_unit->description = 'auto';
                            $workorder_unit->save();
    
                            $unit = Unit::find($workorder_unit->asset_id);
                            $unit->is_readywo = 1;
                            $unit->save();
                        }
                    }
                    
                }
            }else if($request->radio == "non-unit"){
                $workorder_unit = new WorkorderDetail;
                $workorder_unit->workorder_id = $id_workorder;
                $workorder_unit->asset_id = $request->kawasan;
                $workorder_unit->asset_type = "Modules\Project\Entities\ProjectKawasan";
                $workorder_unit->description = 'auto';
                $workorder_unit->save();
            }
        }

        if (!file_exists ("./assets/workorder/".$id_workorder )) {
            mkdir("./assets/workorder/".$id_workorder, 0777, true);
            chmod("./assets/workorder/".$id_workorder,0777);
        }

        foreach ($_FILES["file"]["error"] as $key => $error) {
            if ($error == 0 && $request->kategori[$key] != null && $request->file_name[$key] != null) {
                if($request->kategori[$key] == 'gambar_tender'){
                    $kategori = 'Gambar Tender';
                }else if($request->kategori[$key] == 'spesifikasi'){
                    $kategori = 'Spesifikasi Teknis';
                }
                $tender_document = new TenderDocument;
                $tender_document->tender_id = NULL;
                $tender_document->workorder_budget_id = $workorder->id;
                $tender_document->document_name = $kategori;
                $uploadedFile = $request->file('file')[$key];  
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

                $name = $_FILES['file']['name'][$key];
                $checkpdf = array_search($type, $array_file);
                if( $checkpdf != "" ) {
                    $pathpdf = $uploadedFile->storeAs('public/assets/workorder/'.$id_workorder, $name);
                    $new_file_name = explode("/", $pathpdf);
                    // return $new_file_name[4];
                    $tmp_name = $_FILES['file']['tmp_name'][$key];
                    move_uploaded_file($tmp_name, "./assets/workorder/".$id_workorder.'/'.$new_file_name[4]);
                    $tender_document->filenames = $pathpdf;
                    $tender_document->name = $request->file_name[$key];
                    $tender_document->workorder_id = $id_workorder;
                }

                $tender_document->save();
              }
        }

        return redirect("/workorder/detail/?id=".$work_order->id);
    }

    /**
     * Show the specified resource.
     * @return Response
     */

    public function CallAPI($method, $url, $data = false)
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

    public function show(Request $request)
    {
        // $test = (object)json_decode($this->CallAPI("GET", "https://ems.ciputragroup.com:11443/index.php/api/sales_force/sales_force_permission/18/bill"),true);
        // return $test->tagihan;
        $workorder = Workorder::find($request->id);
        $user = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        if($project == null){
            return redirect("/");
        }
        $itempekerjaan_parent = Itempekerjaan::where('parent_id', null)->orderBy("code", "ASC")->get();
        $itempekerjaan_child = Itempekerjaan::where('parent_id', $workorder->detail_pekerjaan[0]->itempekerjaan->parent_id)->orderBy("code", "ASC")->get();
        $department = Department::get();
        $department_from = Department::find($workorder->department_from);
        $department_to = Department::find($workorder->department_to);
        $user_pt = $user->project_pt_users->where("project_id",$project->id);
        $budget = $workorder->departmentFrom->budgets->where("pt_id",$user_pt[0]->pt_id);
        // $budget_tahunan = BudgetTahunan::find($request->budget);
        // $kawasan = \Modules\Project\Entities\ProjectKawasan::where("project_id", $project->id)->get();

        // foreach ($kawasan as $key => $nilai){
        //     foreach ($project->pt as $key => $value) {
        //         $department = Department::get();
        //         foreach ($department as $key2 => $value2) {
    
        //             if ( $value2->id == 1 || $value2->id == 2 ){
        //                 $budget = new \Modules\Budget\Entities\Budget;
        //                 // $project = Project::find($request->session()->get('project_id'));
        //                 $pt = \Modules\Pt\Entities\Pt::find($value->pt_id);
    
        //                 $number = \App\Helpers\Document::new_number('BDG', $value2->id,$project->id).$pt->code;
        //                 $budget->pt_id = $value->pt_id;
        //                 $budget->department_id = $value2->id;
        //                 $budget->project_id = $project->id;
        //                 $budget->project_kawasan_id = $nilai->id;
        //                 $budget->no = $number;
        //                 $budget->start_date = date("Y-m-d H:i:s.u");
        //                 $budget->end_date = $request->end_date;
        //                 $budget->description = "Budget Generate Otomtasi Fase 1 CPMS";
        //                 $budget->created_by = \Auth::user()->id;
        //                 $budget->save();
    
        //                 $budget_tahunan                 = new \Modules\Budget\Entities\BudgetTahunan;
        //                 $budget_tahunan->budget_id      = $budget->id;
        //                 $budget_tahunan->no             = \App\Helpers\Document::new_number('BDG-T', $value2->id,$project->id).$pt->code;
        //                 $budget_tahunan->tahun_anggaran = date("Y");
        //                 $budget_tahunan->description    = "Budget Tahunan Generate Otomtasi Fase 1 CPMS";
        //                 $status = $budget_tahunan->save();
        //             }
        //         }        
        //     }
        // }
        // return $kawasan;
        $dokumen = TenderDocument::where("workorder_id", $workorder->id)->get();
        if(count($dokumen) == 0){
            $dokumen = TenderDocument::where("workorder_budget_id", $workorder->detail_pekerjaan[0]->id)->get();
        }
        $upload = 0;
        $sudah_upload = 0;
        foreach ( $workorder->detail_pekerjaan as $key => $value ){
            if ( count($value->dokumen) > 0 ){
                $upload++;
            }
        }
        if(count($workorder->detail_pekerjaan) == $upload){
            $sudah_upload = 1;
        }

        $textarea = '';
        foreach($workorder->details as $key => $value){
            if($key == 0){
                $textarea = $textarea.''.$value->asset->name;
            }else{
                $textarea = $textarea.", ".$value->asset->name;
            }
        }
        return view('workorder::detail2',compact("workorder","project","user","department","budget","sudah_upload","department_from","department_to","itempekerjaan_parent","textarea","itempekerjaan_child","dokumen"));
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit()
    {
        return view('workorder::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request)
    {
        $work_order = Workorder::find($request->workorder_id);
        // $work_order->department_from = $request->department_from;
        // $work_order->department_to = $request->department_to;
        $work_order->name = $request->workorder_name;
        $work_order->description = $request->workorder_description;
        if(date('d/M/Y', strtotime($work_order->date)) != $request->start_date){
            $work_order->date = date("Y-m-d",strtotime($request->start_date));
        }
        $status = $work_order->save();
        return redirect("/workorder/detail/?id=".$request->workorder_id);
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy()
    {
    }

    public function budgettahunan(Request $request){
        $project = $request->session()->get('project_id');
        $department_id = Department::find($require->department_id);
        $budgets = $department_id->budgets;
        $html = "";
        foreach ($budgets as $key => $value) {
            if ( $value->project_id == $request->session()->get('project_id')){
                foreach ($value->budget_tahunans as $key2 => $value2) {
                    if ( $value2->tahun_anggaran == date('Y')){
                        $html .= "<option value='".$value2->id."'>".$value->no."</option>";
                    }
                }
            }
        }
        return response()->json( ["status" => "0", "html" => $html] );
    }

    public function itempekerjaan(Request $request){
        $budgettahunan = BudgetTahunan::find($request->id);
        $html = "";

        foreach ($budgettahunan->total_parent_item as $key => $value) {
            if ( isset($value['nilai'])){
                //if ( count( \Modules\Pekerjaan\Entities\Itempekerjaan::where("code",$value['id'])->get() ) > 0 ){
                    $itempekerjaan = \Modules\Pekerjaan\Entities\Itempekerjaan::where("code",$value['code'])->get()->first();
                    $html .= "<tr style='background-color:grey;color:white;font-weight:bolder;'>";
                    $html .= "<td>".$itempekerjaan->code."</td>";
                    $html .= "<td>".$itempekerjaan->name."</td>";
                    $html .= "<td>".number_format($value['nilai'])."</td>";
                    $html .= "<td>&nbsp;</td>";
                    $html .= "<td>&nbsp;</td>";
                    $html .= "<td>&nbsp;</td>";
                    $html .= "</tr>";
                    foreach ($itempekerjaan->child_item as $key2 => $value2) {
                        $html .= "<tr>";
                        $html .= "<td>".$value2->code."</td>";
                        $html .= "<td>".$value2->name."</td>";
                        $html .= "<td><input type='hidden' class='form-control nilai_budgets' value='".$value2->id."' name='item_id[".$key2."]'/></td>";
                        $html .= "<td><input type='text' class='form-control nilai_budgets' name='nilai[".$key2."]'/></td>";
                        $html .= "<td><input type='text' class='form-control nilai_budgets' name='volume[".$key2."]'/></td>";
                        $html .= "<td><input type='text' class='form-control' value='m2' name='satuan[".$key2."]' required/></td>";
                        $html .= "</tr>";
                    }
                //}
                
            }
            
        }
        return response()->json( ["status" => "0", "html" => $html] );
    }

    public function savepekerjaan (Request $request){

        if ( $request->setwo ){
            foreach ($request->setwo as $key => $value) {
                if ( $request->setwo[$key] != ""  && $request->volume[$value] != "" && $request->satuan[$value] != "" && $request->nilai[$value] != "" ){

                    $workorder = new WorkorderBudgetDetail;
                    $workorder->workorder_id = $request->workorder_id;
                    $workorder->budget_tahunan_id = $request->budget_tahunan;
                    $workorder->itempekerjaan_id = $request->item_id[$value];
                    $workorder->tahun_anggaran = date('Y');
                    $workorder->volume = str_replace(",", "",$request->volume[$value]);
                    $workorder->satuan = $request->satuan[$value];
                    $workorder->nilai = str_replace(",", "", $request->nilai[$value]);
                    $workorder->save();
                    
                }
            }
        }

        $budgettahunan = BudgetTahunan::find($request->budget_tahunan);
        if ( $budgettahunan->budget->project_kawasan_id == "" ){
            $WorkorderDetail = new WorkorderDetail;
            $WorkorderDetail->workorder_id = $request->workorder_id;
            $WorkorderDetail->asset_id = $budgettahunan->budget->project->id;
            $WorkorderDetail->asset_type = "Modules\Project\Entities\Project";
            $WorkorderDetail->save();
        }
        return redirect("/workorder/detail?id=".$request->workorder_id);
    }

    public function saveunits(Request $request){

        foreach ($request->asset as $key => $value) {
            if ( $request->asset[$key] != "" ){

                $asset_exist = WorkorderDetail::where("asset_id",str_replace("Unit_","",$request->asset[$key]))->where("workorder_id",$request->workorder_unit_id)->where("asset_type","Modules\Project\Entities\Unit")->get();
                
                if ( count($asset_exist) <= 0 ){
                    $explode = explode("_", $request->asset[$key]);
                    if ( count($explode) < 2 ){
                        $workorder_unit = new WorkorderDetail;
                        $workorder_unit->workorder_id = $request->workorder_unit_id;
                        $workorder_unit->asset_id = $request->asset[$key];
                        $workorder_unit->asset_type = "Modules\Project\Entities\ProjectKawasan";
                        $workorder_unit->description = 'auto';
                        $workorder_unit->save();
                    }else{
                        $workorder_unit = new WorkorderDetail;
                        $workorder_unit->workorder_id = $request->workorder_unit_id;
                        $workorder_unit->asset_id = str_replace("Unit_","",$request->asset[$key]);
                        $workorder_unit->asset_type = "Modules\Project\Entities\Unit";
                        $workorder_unit->description = 'auto';
                        $workorder_unit->save();

                        $unit = Unit::find($workorder_unit->asset_id);
                        $unit->is_readywo = 1;
                        $unit->save();
                    }
                }
            }
            
        }
        return redirect("/workorder/detail?id=".$request->workorder_unit_id);
    }

    public function deleteunit(Request $request){
        $workorder = WorkorderDetail::find($request->id);
        $workorder->deleted_at = date("Y-m-d H:i:s.000");
        $workorder->deleted_by = \Auth::user()->id;
        $status = $workorder->save();
        if ( $status ){
            return response()->json( ["status" => "0"] );
        }else{
            return response()->json( ["status" => "1"] );
        }
    }

    public function approve(Request $request){
        $workorder = Workorder::find($request->id);
        $approval = \App\Helpers\Document::make_approval('Modules\Workorder\Entities\Workorder',$workorder->id);
        
        $approval_history = \Modules\Approval\Entities\ApprovalHistory::where('document_id',$workorder->id)->where('document_type','Modules\Workorder\Entities\Workorder')->get();
        $project = Project::find($request->session()->get('project_id'));
        $project_pt = ProjectPt::where("project_id",$project->id)->first();

        Approval::where('document_id', $workorder->id)->where('document_type', 'Modules\Workorder\Entities\Workorder')->update(['approval_action_id' => 6]);
        
        $workorder_budget_detail_id = WorkorderBudgetDetail::find($workorder->detail_pekerjaan[0]->id);
        $project = Project::find($request->session()->get('project_id'));
        $rab_no = \App\Helpers\Document::new_number('RAB', $workorder->department_from,$project->id);
        $rab = new Rab;
        $rab->no = $rab_no;
        $rab->workorder_id = $workorder->id;
        $rab->name = $workorder->name;
        $rab->created_by = \Auth::user()->id;
        $rab->workorder_budget_detail_id = $workorder_budget_detail_id->id;
        $rab->save();

        foreach ($workorder->details as $key => $value) {
            $rabunits = new RabUnit;
            $rabunits->rab_id = $rab->id;
            $rabunits->asset_id = $value->asset_id;
            $rabunits->asset_type =  $value->asset_type;
            $rabunits->created_by = \Auth::user()->id;
            $rabunits->save();
        }
        // wibowo.rahardja@ciputra.com
        // arman.djohan@ciputra.com
        // foreach ($approval_history as $key => $value) {
        //     $data["email"]=$value->user->email;
        //     $data["client_name"]=$value->user->user_name;
        //     $data["subject"]='Approval Workorder';
        //     $link = 'https://ces.ciputragroup.com/webapps/Ciputra/public/';
        //     $title = "Workorder";

        //     Mail::send('mail.bodyEmailApprove', ['link' => $link, 'title' => $title, 'user' => $value->user, 'project_pt' => $project_pt, 'name' => $workorder->name], function($message)use($data) {
        //     $message->from(env('MAIL_USERNAME'))->to($data["email"], $data["client_name"])
        //     ->subject($data["subject"]);
        //     });
        // }
        // $workorder = Workorder::find($request->id);
        
        return response()->json( ["rab_id" => $rab->id, "idpkr" => $workorder_budget_detail_id->itempekerjaan->id] );
        
    }

    public function choosebudget(Request $request){
        $budget_tahunan = BudgetTahunan::find($request->budget_tahunan);
        $user = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));    
        $workorder = Workorder::find($request->workoder_par_id);  
        return redirect("/workorder/non-budget?id=".$workorder->id."&budget=".$budget_tahunan->id); 
        //return view("workorder::detail_budget",compact("budget_tahunan","user","project","workorder"));
    }

    public function approval_history(Request $request){
        $workorder = Workorder::find($request->id);
        $approval = $workorder->approval;
        $user = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));   
        return view("workorder::approval_history",compact("workorder","user","project"));
    }

    public function nonbudget(Request $request){
        $budget_tahunan = BudgetTahunan::find($request->budget);
        $workorder = Workorder::find($request->id);
        $budget = $budget_tahunan->budget;
        $itempekerjaan = Itempekerjaan::get();
        $user = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));    
        return view("workorder::item_non_budget",compact("workorder","user","project","budget_tahunan","itempekerjaan","budget"));
    }

    public function savenonbudget(Request $request){
        $budget_tahunan = BudgetTahunan::find($request->budget_tahunan);
        $budget_draft = BudgetDraft::find($budget_tahunan->budget->id);
        if ( $budget_draft == "" ){
            $budget_draft = new BudgetDraft;
            $budget_draft->budget_parent_id = $budget_tahunan->id;
            $budget_draft->workorder_id = $request->workorder_id;
            $budget_draft->no = $budget_tahunan->budget->no."/R".(count($budget_tahunan->budget->draft) + 1 );
            $budget_draft->created_by = \Auth::user()->id;
            $budget_draft->save();       
        }

        foreach ($request->item_id as $key => $value) {
            if(array_key_exists($key,$request->volume)){
                if ( $request->volume[$key]['value'] != "" && $request->nilai[$key]['value'] != 0 ){
                    $budget_draft_detail = new BudgetDraftDetail;
                    $budget_draft_detail->budget_draft_id = $budget_draft->id;
                    $budget_draft_detail->itempekerjaan_id = $request->item_id[$key]['value'];
                    $budget_draft_detail->volume = str_replace(",", "",$request->volume[$key]['value']);
                    $budget_draft_detail->satuan = $request->satuan[$key]['value'];
                    $budget_draft_detail->nilai  = str_replace(",", "",$request->nilai[$key]['value']); 
                    $budget_draft_detail->save();
                
                    $workorder = new WorkorderBudgetDetail;
                    $workorder->workorder_id = $request->workorder_id;
                    $workorder->budget_tahunan_id = $budget_tahunan->id;
                    $workorder->itempekerjaan_id = $request->item_id[$key]['value'];
                    $workorder->tahun_anggaran = date('Y');
                    $workorder->volume = str_replace(",", "",$request->volume[$key]['value']);
                    $workorder->satuan = $request->satuan[$key]['value'];
                    $workorder->nilai = str_replace(",", "", $request->nilai[$key]['value']);
                    $workorder->save();
                }
            }
        }

        //$approval = \App\Helpers\Document::make_approval('Modules\BudgetDraft\Entities\BudgetDraft',$budget_draft->id);
        return response()->json(["url" => "/workorder/detail?id=".$request->workorder_id]);
        //return redirect("workorder/detail?id=".$request->workorder_id);

    }

    public function updapprove(Request $request ){
        $workorder = Workorder::find($request->id);
        if ( $workorder->approval != "" ){
            $workorder_approval = \Modules\Approval\Entities\Approval::find($workorder->approval->id);
            $workorder_approval->approval_action_id = 1;
            $workorder_approval->save();

            foreach ($workorder->approval->histories as $key => $value) {
                $approval_history = \Modules\Approval\Entities\ApprovalHistory::find($value->id);
                $approval_history->approval_action_id = 1;
                $approval_history->save();
            }
        }
        return response()->json( ["status" => "0"] );
    }

    public function deletepekerjaan(Request $request){

        $workorder = WorkorderBudgetDetail::find($request->id);
        if ( $workorder->workorder->budget_draft != "" ){
            $draft_id = BudgetDraft::find($workorder->workorder->budget_draft->id);
            $draft_id->deleted_at = date("Y-m-d H:i:s.000");
            $draft_id->deleted_by = \Auth::user()->id;
            $draft_id->delete();
        }
        $workorder->deleted_at = date("Y-m-d H:i:s.000");
        $workorder->deleted_by = \Auth::user()->id;
        $status = $workorder->save();

        

        if ( $status ){
            return response()->json( ["status" => "0"] );
        }else{
            return response()->json( ["status" => "1"] );
        }
    }

    public function getallunit(Request $request){
        $workorder = Workorder::find($request->id);
        $user = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));    
        $array = array(
            "0" => "Planning - P&D",
            "1" => "Planning - EREMS",
            "3" => "Ready for Stock ( from Erem )",
            "5" => "Sold(from Erem)"
        );
        $limit_bangun = \Modules\Globalsetting\Entities\Globalsetting::where("parameter","limit_bangun")->first()->value;
        $standar_limit = $limit_bangun;
        $limit_bangun = '+'.$limit_bangun.'day';
        return view("workorder::workorder_unit",compact("workorder","project","user","array","limit_bangun","standar_limit"));
    }

    public function searchworkorder(Request $request){

    }

    public function itemdetail(Request $request){
        $itempekerjaan = Itempekerjaan::find($request->id);
        $html = "";
         foreach ($itempekerjaan->child_item as $key2 => $value2) {
            if ( $value2->details != "" ){
                $satuan = $value2->details->satuan;
            }else{
                $satuan = "";
            }

            $html .= "<tr>";
            $html .= "<td>".$value2->code."</td>";
            $html .= "<td>".$value2->name."</td>";
            $html .= "<td><input type='hidden' class='form-control nilai_budgets' value='".$value2->id."' name='item_id[".$key2."]'/>";
            if(count(explode(".",$value2->code)) > 1){
                if(explode(".",$value2->code)[0] != "100"){
                    if(count($value2->child_item) != 0 && explode(".",$value2->code)[1] != "00"){
                        $html .= "<input type='text' class='form-control nilai_budgets'  name='volume[".$key2."]' autocomplete='off' value=''/></td>";
                        $html .= "<td><input type='hidden' class='form-control' value='".$satuan."' name='satuan[".$key2."]' required/><input type='text' class='form-control' value='".$satuan."' autocomplete='off' disabled/></td>";
                        $html .= "<td><input type='text' class='form-control nilai_budgets'  name='nilai[".$key2."]' autocomplete='off' value=''/></td>";
                    }else{
                        $html .= "<input type='text' class='form-control nilai_budgets'  name='volume[".$key2."]' autocomplete='off' value='' readonly/></td>";
                        $html .= "<td><input type='hidden' class='form-control' value='".$satuan."' name='satuan[".$key2."]' required/><input type='text' class='form-control' value='".$satuan."' autocomplete='off' disabled/></td>";
                        $html .= "<td><input type='text' class='form-control nilai_budgets'  name='nilai[".$key2."]' autocomplete='off' value='' readonly/></td>";
                    }
                }else{
                    $html .= "<input type='text' class='form-control nilai_budgets'  name='volume[".$key2."]' autocomplete='off' value=''/></td>";
                    $html .= "<td><input type='hidden' class='form-control' value='".$satuan."' name='satuan[".$key2."]' required/><input type='text' class='form-control' value='".$satuan."' autocomplete='off' disabled/></td>";
                    $html .= "<td><input type='text' class='form-control nilai_budgets'  name='nilai[".$key2."]' autocomplete='off' value=''/></td>";
                }
            }else{
                if(count($value2->child_item) != 0){
                    $html .= "<input type='text' class='form-control nilai_budgets'  name='volume[".$key2."]' autocomplete='off' value=''/></td>";
                    $html .= "<td><input type='hidden' class='form-control' value='".$satuan."' name='satuan[".$key2."]' required/><input type='text' class='form-control' value='".$satuan."' autocomplete='off' disabled/></td>";
                    $html .= "<td><input type='text' class='form-control nilai_budgets'  name='nilai[".$key2."]' autocomplete='off' value=''/></td>";
                }else{
                    $html .= "<input type='text' class='form-control nilai_budgets'  name='volume[".$key2."]' autocomplete='off' value='' readonly/></td>";
                    $html .= "<td><input type='hidden' class='form-control' value='".$satuan."' name='satuan[".$key2."]' required/><input type='text' class='form-control' value='".$satuan."' autocomplete='off' disabled/></td>";
                    $html .= "<td><input type='text' class='form-control nilai_budgets'  name='nilai[".$key2."]' autocomplete='off' value='' readonly/></td>";
                }
            }
            $html .= "</tr>";
        }

        return response()->json(["html" => $html, "status" => "0" ]);
    }

    public function search(Request $request){
        $array_params = array(
            "itempekerjaan" => $itempekerjaan->id,
            "judul_pekerjaan" => $judul_pekerjaan,
            "nilai" => $nilai,
            "params" => $params
        );
    }

    public function dokumen(Request $request){
        $user = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        $workorder_pekerjaan = WorkorderBudgetDetail::find($request->id);
        $idw = $request->idw;
        // return $workorder_pekerjaan->dokumen;
        return view("workorder::document",compact("user","workorder_pekerjaan","project","idw"));
    }

    public function savedocument(Request $request){
        if (!file_exists ("./assets/workorder/".$request->workorder_budget_id )) {
            mkdir("./assets/workorder/".$request->workorder_budget_id);
            chmod("./assets/workorder/".$request->workorder_budget_id,0777);
        }

        $date = date("d-m-Y");
        $tender_document = new TenderDocument;
        $tender_document->tender_id = NULL;
        $tender_document->workorder_budget_id = $request->workorder_budget_id;
        $tender_document->document_name = $request->name_doc;
        
        // if ( $_FILES['gambar_tender']['name'] == ""){
        //     $tender_document->filenames = $request->images;
        // }else{
            if($request->file('gambar_tender')){
                $uploadedFile = $request->file('gambar_tender');  
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
                    $name = $_FILES['gambar_tender']['name'];
                    $pathpdf = $uploadedFile->storeAs('public/assets/workorder/'.$request->workorder_budget_id, $date.' -- '.$name);
                    $new_file_name = explode("/", $pathpdf);
                    $tmp_name = $_FILES['gambar_tender']['tmp_name'];
                    move_uploaded_file($tmp_name, "./assets/workorder/".$request->workorder_budget_id.'/'.$new_file_name[4]);
                    $tender_document->filenames = $pathpdf;
                }else{     
                    return response()->json(["status" => "Data Berhasil diupload"]);     
                    // return redirect("/workorder/dokument?id=".$request->workorder_budget_id);
                }
            }elseif($request->file('gambar_bq')){
                $uploadedFile = $request->file('gambar_bq');  
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
                    $name = $_FILES['gambar_bq']['name'];
                    $pathpdf = $uploadedFile->storeAs('public/assets/workorder/'.$request->workorder_budget_id, $date.' -- '.$name);
                    $new_file_name = explode("/", $pathpdf);
                    $tmp_name = $_FILES['gambar_bq']['tmp_name'];
                    move_uploaded_file($tmp_name, "./assets/workorder/".$request->workorder_budget_id.'/'.$new_file_name[4]);
                    $tender_document->filenames = $pathpdf;
                }else{     
                    return response()->json(["status" => "Data Berhasil diupload"]);     
                    // return redirect("/workorder/dokument?id=".$request->workorder_budget_id);
                }
            }elseif($request->file('gambar_spesifikasi')){
                $uploadedFile = $request->file('gambar_spesifikasi');  
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
                    $name = $_FILES['gambar_spesifikasi']['name'];
                    $pathpdf = $uploadedFile->storeAs('public/assets/workorder/'.$request->workorder_budget_id, $date.' -- '.$name);
                    $new_file_name = explode("/", $pathpdf);
                    $tmp_name = $_FILES['gambar_spesifikasi']['tmp_name'];
                    move_uploaded_file($tmp_name, "./assets/workorder/".$request->workorder_budget_id.'/'.$new_file_name[4]);
                    $tender_document->filenames = $pathpdf;
                }else{     
                    return response()->json(["status" => "Data Berhasil diupload"]);     
                    // return redirect("/workorder/dokument?id=".$request->workorder_budget_id);
                }
            }
        // }

        $tender_document->save();
        return response()->json(["status" => "Data Berhasil diupload"]);
        // return redirect("/workorder/dokument?id=".$request->workorder_budget_id);
    }

    public function deletedocument(Request $request){
        $tender_document = TenderDocument::find($request->id);
        $tender_document->delete();

        return response()->json(["status" => "0"]);
    }

    public function downloaddoc(Request $request){
        $tender_document = TenderDocument::find($request->id);
        
        $headers = [
              'Content-Type' => 'application/pdf',
           ];
        if ( $tender_document != "" ){
            $filenames = explode("/", $tender_document->filenames);
        }

        if(count($filenames) != 5 ){
            $name = "Gambar Lampiran";
        }else{
            $name = $filenames[4];
        }

        $file = public_path()."/".str_replace("public", "", $tender_document->filenames);
        return response()->download($file, $name, $headers);
    }

    public function savequick(Request $request){
        
        if ( $request->unit != "" ){     
            $kawasan = "";

            $department = Department::find(2);
            $project = Project::find($request->session()->get('project_id'));
            $unit = Unit::find($request->unit[0]);
            $work_order_no = \App\Helpers\Document::new_number('WO', $department->id,$project->id);

            $work_order = new Workorder;
            $work_order->budget_tahunan_id = $project->id;
            $work_order->department_from = $department->id;
            $work_order->department_to = $department->id;
            $work_order->no = $work_order_no;
            $work_order->name = "Workorder Pembangunan Rumah";
            $work_order->durasi = '0';
            $work_order->satuan_waktu = '0';
            $work_order->date = date("Y-m-d H:i:s.000");
            $work_order->estimasi_nilaiwo = '0';
            $work_order->description = "Workorder Pembangunan Rumah";
            $work_order->created_by = \Auth::user()->id;
            $work_order->end_date = NULL;
            $work_order->kawasan_id = $unit->blok->kawasan->id;
            $work_order->pt_id = $project->pt[0]->pt->id;
            $status = $work_order->save();

            

            foreach ($request->unit as $key => $value) {
                $workorder_unit = new WorkorderDetail;
                $workorder_unit->workorder_id = $work_order->id;
                $workorder_unit->asset_id = $request->unit[$key];
                $workorder_unit->asset_type = "Modules\Project\Entities\Unit";
                $workorder_unit->description = 'Save Unit Sold Workorder Quick';
                $workorder_unit->save();
            }

            $array_type = array();

            foreach ($request->unit as $key => $value) {
                $unit = Unit::find($request->unit[$key]);
                if ( $unit->type != "" ){
                    if ( isset($array_type[$unit->type->id])){
                        $array_type[$unit->type->id]["bangunan_luas"] = $array_type[$unit->type->id]["bangunan_luas"] + $unit->bangunan_luas;
                    }else{
                        $array_type[$unit->type->id]["bangunan_luas"] = $unit->bangunan_luas;
                    }                    
                }

                if ( $unit->blok != "" ){
                    if ( $unit->blok->kawasan != "" ){
                        $project_kawasan_id = ProjectKawasan::find($unit->blok->kawasan->id);
                        foreach ($project_kawasan_id->budgets as $key => $value) {
                            if ( $value->deleted_at == "" ){
                                if ( $value->department_id == 2 ){
                                    foreach ($value->budget_tahunans as $key2 => $value2) {
                                        if ( $value2->tahun_anggaran == date("Y")){
                                            $array_type[$unit->type->id]["budget_tahunan"] = $value2->id;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                $unit->is_readywo = 1;
                $unit->save();
            }

            foreach ($array_type as $key => $value) {
                $workorder = new WorkorderBudgetDetail;
                $workorder->workorder_id = $work_order->id;
                $workorder->budget_tahunan_id = $value['budget_tahunan'];
                $workorder->itempekerjaan_id = 293;
                $workorder->tahun_anggaran = date('Y');
                $workorder->volume = $value['bangunan_luas'];
                $workorder->satuan = 'm2';
                $workorder->nilai = 0;
                $workorder->save();
            }

            return redirect("/workorder/detail?id=".$work_order->id);

        }else{
            return view("/workorder");
        }
    }

    public function updatepekerjaan(Request $request){
        $workorder_pekerjaan = WorkorderBudgetDetail::find($request->wokorder_detailpekerjaan_id);
        $workorder_pekerjaan->nilai = str_replace(",", "", $request->nilai);
        $workorder_pekerjaan->volume = str_replace(",", "", $request->volume);
        $workorder_pekerjaan->save();

        return redirect("workorder/detail?id=".$workorder_pekerjaan->workorder_id);
    }

    public function listUnit(Request $request){
        $kawasan = ProjectKawasan::find($request->kawasan_id);
        $data = [];
        $array = array(
            "0" => "Planning - P&D",
            "1" => "Planning - EREMS",
            "3" => "Ready for Stock ( from Erem )",
            "5" => "Sold(from Erem)"
        );
        foreach ($kawasan->units as $key => $value) {
            # code...
            if($value->bangunan_luas != 0){
                if($value->unit_type_id == $request->type_id){
                    if($value->is_readywo != 1){
                        if($value->status == 3 || $value->status == 5){
                            if($value->pembayaran != 0){
                                $bayar = $value->pembayaran;
                            }else{
                                $bayar = 0;
                            }
                            
                            $arr = [
                                'id'            => $value->id,
                                'unit_name'     => $value->name,
                                'type'          => $value->type->name,
                                'luas_tanah'    => $value->tanah_luas,
                                'luas_bangunan' => $value->bangunan_luas,
                                'status_unit'   => $array[$value->status],
                                'rencana_st'    => date("d/M/Y",strtotime($value->serah_terima_plan)),
                                'bayar'         => $bayar,
                                'LBLT'          => $value->bangunan_luas.'/'.$value->tanah_luas,
                                // 'tanggal_dp'    => 
                            ];

                            array_push($data, $arr);
                        }
                    }
                }
            } 
        }
        return response()->json( ["data" => $data] );
    }

    public function listType(Request $request){
        // $kawasan = ProjectKawasan::find($request->kawasan_id);
        $kawasan = DB::table('project_kawasans')
                        ->join('bloks','bloks.project_kawasan_id','project_kawasans.id')
                        ->join('units','units.blok_id','bloks.id')
                        ->join('unit_types','unit_types.id','units.unit_type_id')
                        ->select('unit_types.id as id_unit_type','unit_types.name as name_unit_type')
                        ->where('project_kawasans.id', $request->kawasan_id)
                        ->distinct('unit_types.id')
                        ->get();

        $data = [];
        foreach($kawasan as $key => $value) {
            # code...
            $arr = [
                'type_id' => $value->id_unit_type,
                'type_name' => $value->name_unit_type,
            ];

            array_push($data,$arr);
        }
        return response()->json($data);
        // return response()->json($kawasan->unit_type);
        // return response()->json( ["data" => $kawasan->unit_type] );
    }

    public function subItemPekerjaan(Request $request){
        $sub_pekerjaan = Itempekerjaan::where("parent_id", $request->pekerjaan_id)->where("code","not like","%.00%")->orderBy("code", "ASC")->get();

        return response()->json($sub_pekerjaan);
    }

    public function savegambar(Request $request){
        if (!file_exists ("./assets/workorder/".$request->workorder_id )) {
            mkdir("./assets/workorder/".$request->workorder_id, 0777, true);
            chmod("./assets/workorder/".$request->workorder_id,0777);
        }
        
        
        if($request->file != null){
            if ($_FILES["file"] != null) {
                # code...
                foreach ($_FILES["file"]["error"] as $key => $error) {
                    if ($error == 0 && $request->kategori[$key] != null && $request->file_name[$key] != null) {
                        if($request->kategori[$key] == 'gambar_tender'){
                            $kategori = 'Gambar Tender';
                        }else if($request->kategori[$key] == 'spesifikasi'){
                            $kategori = 'Spesifikasi Teknis';
                        }
                        $tender_document = new TenderDocument;
                        $tender_document->tender_id = NULL;
                        $tender_document->workorder_budget_id = $request->workorderbudget_id;
                        $tender_document->document_name = $kategori;
                        $uploadedFile = $request->file('file')[$key];  
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
        
                        $name = $_FILES['file']['name'][$key];
                        $checkpdf = array_search($type, $array_file);
                        if( $checkpdf != "" ) {
                            $pathpdf = $uploadedFile->storeAs('public/assets/workorder/'.$request->workorder_id, $name);
                            $new_file_name = explode("/", $pathpdf);
                            // return $new_file_name[4];
                            $tmp_name = $_FILES['file']['tmp_name'][$key];
                            move_uploaded_file($tmp_name, "./assets/workorder/".$request->workorder_id.'/'.$new_file_name[4]);
                            $tender_document->filenames = $pathpdf;
                            $tender_document->name = $request->file_name[$key];
                            $tender_document->workorder_id = $request->workorder_id;
                        }
        
                        $tender_document->save();
                    }
                }
            }
        }
        return redirect("/workorder/detail/?id=".$request->workorder_id);
    }
}
