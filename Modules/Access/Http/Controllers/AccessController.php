<?php 
namespace Modules\Access\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\User;
use Modules\Project\Entities\Project;
use Modules\Budget\Entities\Budget;
use Modules\Budget\Entities\BudgetDetail;
use Modules\Budget\Entities\BudgetTahunanDetail;
use Modules\BudgetDraft\Entities\BudgetDraft;
use Modules\BudgetDraft\Entities\BudgetDraftDetail;
use Modules\Approval\Entities\ApprovalHistory;
use Modules\Workorder\Entities\Workorder;
use Modules\Pekerjaan\Entities\Itempekerjaan;
use Modules\Workorder\Entities\WorkorderBudgetDetail;
use Modules\Tender\Entities\Tender;
use Modules\Tender\Entities\TenderRekanan;
use Modules\Tender\Entities\TenderMenang;
use Modules\Tender\Entities\TenderMenangDetail;
use Modules\Tender\Entities\TenderKorespondensi;
use Modules\Tender\Entities\TenderDocument;
use Modules\Tender\Entities\TenderDocumentApproval;
use Modules\Approval\Entities\Approval;
use Modules\Spk\Entities\Spk;
use Modules\Vo\Entities\Vo;
use Modules\Project\Entities\ProjectKawasan;
use Modules\Blok\Entities\Blok;
use Modules\Project\Pekerjaan\Templatepekerjaan;
use Modules\Budget\Entities\BudgetTahunan;
use Modules\Budget\Entities\BudgetTahunanTemplate;
use Modules\Budget\Entities\BudgetTahunanDetailtemplate;
use Modules\Rab\Entities\Rab;
use Modules\Department\Entities\Department;
use Modules\Budget\Entities\HppUpdate;
use Modules\Budget\Entities\HppUpdateDetail;
use Modules\Spk\Entities\NewVo;
use Modules\Spk\Entities\DetailVo;
use Modules\Rekanan\Entities\PerpanjanganSpk;
use Modules\Spk\Entities\SpkPercepatan;
use App\Http\Controllers\ApiController;
use Modules\Tender\Entities\TenderPenawaran;
use Modules\Tender\Entities\TenderMenangSubDetail;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;
use Modules\Project\Entities\ProjectPt;
use Modules\Tender\Entities\TunjukPemenangTender;


class AccessController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }   


    public function project(Request $request){
        $project = Project::get();
        $user = \Auth::user();
        /* Get Budget Document */
        // return $user->project_pt_users;
        $user = \Modules\User\Entities\User::find($user->id);
        $data_project = [];
        // return $user->jabatan;
        foreach ($user->jabatan as $key => $value) {
            # code...
            $status = 1;
            // if(count($data_project) != 0){
            //     for ($i=0; $i < count($data_project); $i++) { 
            //         # code...
            //         if($data_project[$i]["id"] == $value->project->id){
            //             $status = 0;
            //             $i = count($data_project) +1;
            //         }
            //     }
            // }
            $project = Project::find($value['project_id']);
            if($value['jabatan_id'] <= 7){
                // print_r($value->project->id);
                $arr =[
                    "id" => $value['project_id'],
                    "name" =>  $project->name,
                ];
                array_push($data_project, $arr);
            }
        }
        // return $data_project[0]["id"];
        // echo("<br>");
        // return "0";
        $approval = ApprovalHistory::where("user_id",$user->id)->where("approval_action_id",1)->orderBy("id","desc")->get();
        $department = Department::get();
        return view("access::user.index",compact("approval","project","user","department","data_project"));
    }

    public function approval_summary(Request $request){
        $user = $request->user_id;
        $project = Project::find($request->project_id);

        /* Budget */
        $request_budget = 0;
        $approval_budget = 0;
        $rejected_budget = 0;
        foreach ($project->budgets as $key1 => $value1 ) {
            # code...
            $request_budget = $request_budget + $value1->approval->histories->where("user_id",$user)->where("approval_action_id",1)->count();
            $approval_budget = $approval_budget + $value1->approval->histories->where("user_id",$user)->where("approval_action_id",6)->count();
            $rejected_budget = $rejected_budget + $value1->approval->histories->where("user_id",$user)->where("approval_action_id",7)->count();
        }
        $total_request_budget = $request_budget + $approval_budget + $rejected_budget;

        /* Workorder */
        $request_workorder = 0;
        $approval_workorder = 0;
        $rejected_workoder = 0;
        foreach ($project->workorders as $key => $value) {
            # code...
            $request_workorder = $request_workorder + $value->approval->histories->where("user_id",$user)->where("approval_action_id",1)->count();
            $approval_workorder = $approval_workorder + $value->approval->histories->where("user_id",$user)->where("approval_action_id",6)->count();
            $rejected_workoder = $rejected_workoder + $value->approval->histories->where("user_id",$user)->where("approval_action_id",7)->count();
        }

        /* Tender Rekanan */
        $request_tender = 0;
        $approval_tender = 0;
        $rejected_tender = 0;
        $request_tender_rekanan = 0;
        $approval_tender_rekanan = 0;
        $rejected_tender_rekanan = 0;
        $tenders = $project->tenders->get();
        foreach ( $tenders as $key2 => $value2 ) {
            # code...
            $request_tender = $request_tender + $value2->approval->histories->where("user_id",$user)->where("approval_action_id",1)->count();
            $approval_tender = $approval_tender + $value2->approval->histories->where("user_id",$user)->where("approval_action_id",6)->count();
            $rejected_tender = $rejected_tender + $value2->approval->histories->where("user_id",$user)->where("approval_action_id",7)->count();
            foreach ($value2->rekanans as $key3 => $value3 ) {
                if (isset($value3->approval)){
                    $request_tender_rekanan = $request_tender_rekanan + $value3->approval->histories->where("user_id",$user)->where("approval_action_id",1)->count();
                    $approval_tender_rekanan = $approval_tender_rekanan + $value3->approval->histories->where("user_id",$user)->where("approval_action_id",6)->count();
                    $rejected_tender_rekanan = $rejected_tender_rekanan + $value3->approval->histories->where("user_id",$user)->where("approval_action_id",7)->count();
                }
            }
            $request_tender = $request_tender + $request_tender_rekanan;
            $approval_tender = $approval_tender + $approval_tender_rekanan;
            $rejected_tender = $rejected_tender + $rejected_tender_rekanan;

        }
        /*SPK*/
        $request_spk = 0;
        $approval_spk = 0;
        $rejected_spk = 0;

        foreach ($project->spks as $key4 => $value4) {
            # code...
            if (isset($value4->approval)){
                $request_spk = $request_spk + $value4->approval->histories->where("user_id",$user)->where("approval_action_id",1)->count();
                $approval_spk = $approval_spk + $value4->approval->histories->where("user_id",$user)->where("approval_action_id",6)->count();
                $rejected_spk = $rejected_spk + $value4->approval->histories->where("user_id",$user)->where("approval_action_id",7)->count();
            }
        }

        /* Budget Tahunan*/
        $request_budget_tahunan = 0;
        $approval_budget_tahunan = 0;
        $rejected_budget_tahunan = 0;
        foreach ($project->budget_tahunans as $key6 => $value6 ) {
            # code...
            $request_budget_tahunan = $request_budget_tahunan + $value6->approval->histories->where("user_id",$user)->where("approval_action_id",1)->count();
            $approval_budget_tahunan = $approval_budget_tahunan + $value6->approval->histories->where("user_id",$user)->where("approval_action_id",6)->count();
            $rejected_budget_tahunan = $rejected_budget_tahunan + $value6->approval->histories->where("user_id",$user)->where("approval_action_id",7)->count();
        }

        /* Budget Tahunan*/
        $request_rab = 0;
        $approval_rab = 0;
        $rejected_rab = 0;
        foreach ($project->rabs as $key7 => $value7 ) {
            # code...
            $request_rab = $request_rab + $value7->approval->histories->where("user_id",$user)->where("approval_action_id",1)->count();
            $approval_rab = $approval_rab + $value7->approval->histories->where("user_id",$user)->where("approval_action_id",6)->count();
            $rejected_rab = $rejected_rab + $value7->approval->histories->where("user_id",$user)->where("approval_action_id",7)->count();
        }

        $total_request_budget_tahunan = $request_budget_tahunan + $approval_budget_tahunan + $rejected_budget_tahunan;
        $total_request_workorder = $request_workorder + $approval_workorder + $rejected_workoder;
        $total_request_rab = $request_rab + $approval_rab + $rejected_rab;
        $total_request_tender = $request_tender + $approval_tender + $rejected_tender;
        $total_request_spk = $request_spk + $approval_spk + $rejected_spk;

        return response()->json( [
            "request_budget" => $request_budget, "approval_budget" => $approval_budget, "rejected_budget" => $rejected_budget, "total_request_budget" => $total_request_budget, 
            "request_workorder" => $request_workorder, "approval_workorder" => $approval_workorder, "rejected_workoder" => $rejected_workoder, "total_request_workorder" => $total_request_workorder, 
            "total_request_tender" => $total_request_tender, "request_tender" => $request_tender, "approval_tender" => $approval_tender, "rejected_tender" => $rejected_tender, 
            "total_request_spk" => $total_request_spk, "request_spk" => $request_spk, "approval_spk" => $approval_spk, "rejected_spk" => $rejected_spk, 
            "total_request_budget_tahunan" => $total_request_budget_tahunan, "request_budget_tahunan" => $request_budget_tahunan, "approval_budget_tahunan" => $approval_budget_tahunan, "rejected_budget_tahunan" => $rejected_budget_tahunan, "total_request_rab" => $total_request_rab,  "approval_rab" => $approval_rab, "rejected_rab" => $rejected_rab, "request_rab" => $request_rab]); 
    }

    public function budget(Request $request){
        
        $budget = Budget::find($request->id);
        $project = $budget->project;
        $user = \Auth::user();
        $approval = $budget->approval;
        $effisiensi_netto = 0;
        if ( $budget->project->netto > 0 ){
            $effisiensi_netto = $budget->total_devcost / $budget->project->netto;
        }
        return view("access::user.budgets",compact("budget","project","user","approval","effisiensi_netto"));
    }

    public function budget_detail(Request $request){
        $budget = Budget::find($request->id);
        $approval = $budget->approval;
        $user = \Auth::user();
        $project = $budget->project_id;
        $effisiensi_netto = 0;
        if ( $budget->project->netto > 0 ){
            $effisiensi_netto = $budget->total_devcost / $budget->project->netto;
        }
        return view("access::user.budgets",compact("budget","project","user","approval","effisiensi_netto"));
        
    }

    public function budget_approval(Request $request){
        foreach ($request->budget_id as $key => $value) {
            # code...
            $approva_history_id = ApprovalHistory::where("document_id",$request->input('budget_id.'.$key.'.value'))->where("document_type","App\Budget")->where("user_id",$request->user_id)->get()->first();
            $approva_history = ApprovalHistory::find($approva_history_id->id);
            $approva_history->approval_action_id = $request->status;
            $approva_history->description = $request->input('description.'.$key.'.value');
            $status = $approva_history->save();

            $budget = Budget::find($request->input('budget_id.'.$key.'.value'));
            $highest = $budget->approval->histories->min("no_urut");
            if ( $approva_history->no_urut == $highest){        
                $approval_ac = Approval::find($budget->approval->id);
                $approval_ac->approval_action_id = $request->status;
                $approval_ac->updated_at = date("Y-m-d H:i:s.u");
                $approval_ac->save();

                if ( $request->status == "6" ){
                    if ( count($budget->project->hpp_update) > 0 ){
                        $luas_book = $budget->project->hpp_update->last()->luas_book;
                        $luas_erem = $budget->project->hpp_update->last()->luas_erem;
                    }else{
                        $luas_erem = 0;
                        $luas_book = 0;
                    }

                    $hpp_update = new HppUpdate;
                    $hpp_update->project_id = $budget->project_id;
                    $hpp_update->nilai_budget = $budget->project->total_budget_dev_cost;
                    $hpp_update->luas_book = $luas_book;
                    $hpp_update->luas_erem = $luas_erem;
                    $hpp_update->netto = $budget->project->netto;
                    if ( $budget->project->netto > 0 ){
                        $hpp_update->hpp_book = $budget->project->total_budget_dev_cost / $budget->project->netto;
                    }else{
                        $hpp_update->hpp_book = 0.0;
                    }

                    if ( $budget->project->hpp_update != "" ){
                        $hpp_update->hpp_book_before = $budget->project->hpp_update->last()->hpp_book;
                    }else{
                        $hpp_update->hpp_book_before = 0;
                    }

                    foreach ($budget->project->budgets as $key => $value) {                        
                        $hpp_update_detail = new HppUpdateDetail;
                        $hpp_update_detail->hpp_update_id = $hpp_update->id;
                        $hpp_update_detail->budget_id = $value->id;
                        $hpp_update_detail->save();
                    }

                }
            }
        }

        if ( $status ){
            return response()->json( ["status" => "0"] );
        }else{
            return response()->json( ["status" => "1"] );
        }
    }

    public function approval_budget_awal(Request $request){
        $budget_id = Budget::find($request->budget_id);
        $user_id = $request->user_id;
        $status = $request->status;

        $document = $budget_id->approval->histories;
        $approval_id = $document->where("user_id",$user_id)->first();
        $highest = $budget_id->approval->histories->min("no_urut");

        if ( isset($approval_id->id)){
            $approval_history = ApprovalHistory::find($approval_id->id);
            $approval_history->approval_action_id = $status;
            $status = $approval_history->save();
            
            if ( $approval_history->no_urut == $highest){       
                $approval_ac = Approval::find($budget_id->approval->id);
                $approval_ac->approval_action_id = $request->status;
                $approval_ac->save();
            }

            if ( $status ){
                return response()->json( ["status" => "0"] );
            }else{
                return response()->json( ["status" => "1"] );
            }
        }else{
            return response()->json( ["status" => "1"] );
        }
        
    }

    public function budget_faskot(Request $request){
        $budget_id = BudgetTahunan::find($request->budget_id);
        $user_id = $request->user_id;
        $status = $request->status;

        $document = $budget_id->approval->histories;
        $approval_id = $document->where("user_id",$user_id)->first();
        $highest = $budget_id->approval->histories->min("no_urut");

        if ( isset($approval_id->id)){
            $approval_history = ApprovalHistory::find($approval_id->id);
            $approval_history->approval_action_id = $status;
            $status = $approval_history->save();
            
            if ( $approval_history->no_urut == $highest){       
                $approval_ac = Approval::find($budget_id->approval->id);
                $approval_ac->approval_action_id = $request->status;
                $approval_ac->save();
            }

            if ( $status ){
                return response()->json( ["status" => "0"] );
            }else{
                return response()->json( ["status" => "1"] );
            }
        }else{
            return response()->json( ["status" => "1"] );
        }
        
    }

    public function workorder(Request $request){
        $user    = \Auth::user();
        $workorders = Workorder::find($request->id);
        $project = $workorders->project->first();
        return view("access::user.workoder",compact("project","user","workorders"));
    }

    public function workorder_detail( Request $request){
        $workorder = Workorder::find($request->id);
        $pekerjaan = $workorder->detail_pekerjaan;
        $project = $workorder->project;
        $user    = \Auth::user();
        $approval = $workorder->approval;
        $workorder_unit = $workorder->details->where("asset_type","Modules\Project\Entities\Unit");
        $devcost = 0;
        
        
        return view("access::user.workoder_detail",compact("workorder","pekerjaan","project","user","workorder_unit","devcost","approval"));
    }

    public function workorder_approval(Request $request){
        $workorder = Workorder::find($request->workorder_id);
        $approval_id = Workorder::find($request->workorder_id)->approval;

        $approva_history_id = ApprovalHistory::where("approval_id",$approval_id->id)->where("user_id",$request->user_id)->get()->first();
        $approva_history = ApprovalHistory::find($approva_history_id->id);
        $approva_history->approval_action_id = $request->status;
        $approva_history->description = $request->description;
        $status = $approva_history->save();

        $highest = $approval_id->histories->min("no_urut");
        if ( $approva_history_id->no_urut == $highest){     
            $approval_ac = Approval::find($approval_id->id);
            $approval_ac->approval_action_id = $request->status;
            $approval_ac->updated_at = date("Y-m-d H:i:s.u");
            $approval_ac->save();

            if ( $workorder->budget_draft != "" ){
                $budget_draft_approval = $workorder->budget_draft->approval;
                if ( $workorder->budget_draft->approval != "" ){
                    $approval = Approval::find($workorder->budget_draft->approval->id);
                    $approval->approval_action_id = 6;
                    $approval->save();

                    /*$coa = $workorder->itempekerjaan["coa_code"];
                    $explode_coa = explode(".",$coa);
                    
                    if ( count($explode_coa) > 0 ){
                        $itempekerjaan_id = Itempekerjaan::where("code",$explode_coa[0])->first()->id;
                    }else{
                        $itempekerjaan_id = Itempekerjaan::where("code",$coa)->first()->id;
                    }
                    $itempekerjaan = Itempekerjaan::find($itempekerjaan_id);*/
                    
                    /*foreach ($workorder->detail_pekerjaan as $key2 => $value2) {
                        $budget = Budget::find($workorder->budget_draft->budget_tahunan->budget->id);
                        $budget_detail = new BudgetDetail;
                        $budget_detail->budget_id = $budget->id;
                        $budget_detail->itempekerjaan_id = $value2->itempekerjaan->parent->id;
                        $budget_detail->volume = $value2->volume;
                        $budget_detail->nilai = $value2->nilai;
                        $budget_detail->satuan = $value2->satuan;
                        $budget_detail->save();

                        $budget_tahunan = BudgetTahunan::find($workorder->budget_draft->budget_tahunan->id);
                        $budget_tahunan_detail = new BudgetTahunanDetail;
                        $budget_tahunan_detail->budget_tahunan_id = $budget_tahunan->id;
                        $budget_tahunan_detail->itempekerjaan_id = $value2->itempekerjaan_id;
                        $budget_tahunan_detail->volume = $value2->volume;
                        $budget_tahunan_detail->nilai = $value2->nilai;
                        $budget_tahunan_detail->satuan = $value2->satuan;
                        $budget_tahunan_detail->save();


                    }    */           

                }
            }
        }

        if ( $status ){
            return response()->json( ["status" => "0"] );
        }else{
            return response()->json( ["status" => "1"] );
        }
    }

    public function itemdetail(Request $request){
        $item = Itempekerjaan::find($request->parent_id);

        $itempekerjaan = Itempekerjaan::where("parent_id",$request->parent_id)->get();
        $html = "";
        $nilai = 0;
        foreach ( $itempekerjaan as $key => $value ){
            $detailitem = Itempekerjaan::find($value->id);
            $unitworkorder = WorkorderBudgetDetail::where("itempekerjaan_id",$value->id)->where("workorder_id",$request->workorder)->get()->first();
            if (isset($unitworkorder->volume)){
                $html .= "<tr>";
                $html .= "<td>".$detailitem->code."</td>";
                $html .= "<td>".$detailitem->name."</td>";
                $html .= "<td>".number_format($unitworkorder->volume)."</td>";
                $html .= "<td>".$unitworkorder->satuan."</td>";
                $html .= "<td>".number_format($unitworkorder->nilai)."</td>";
                $html .= "<td>".number_format($unitworkorder->nilai * $unitworkorder->volume)."</td>";
                $html .= "</tr>";
                $nilai = $nilai + ($unitworkorder->nilai * $unitworkorder->volume);
            }
        }
        $html .= "<tr>";
        $html .= "<td colspan='5' style='text-align:right;'><i>Total</i></td>";
        $html .= "<td><strong>".number_format($nilai)."</strong></td>";
        $html .= "</tr>";
        return response()->json( ["html" => $html, "coa" => $item->code, "dept" => $item->department->name, "names" => $item->name ] );
    }

    public function tender(Request $request){
        $project = Project::find($request->id);
        $tenders = $project->tenders->get();
        $user = \Auth::user();
        $request_tender = 0;
        $approval_tender = 0;
        $rejected_tender = 0;
        $request_tender_rekanan = 0;
        $approval_tender_rekanan = 0;
        $rejected_tender_rekanan = 0;

        foreach ( $tenders as $key2 => $value2 ) {
        # code...
        $request_tender = $request_tender + $value2->approval->histories->where("user_id",$user)->where("approval_action_id",1)->count();
        $approval_tender = $approval_tender + $value2->approval->histories->where("user_id",$user)->where("approval_action_id",6)->count();
        $rejected_tender = $rejected_tender + $value2->approval->histories->where("user_id",$user)->where("approval_action_id",7)->count();
        foreach ($value2->rekanans as $key3 => $value3 ) {
            if (isset($value3->approval)){
                $request_tender_rekanan = $request_tender_rekanan + $value3->approval->histories->where("user_id",$user->id)->where("approval_action_id",1)->count();
                $approval_tender_rekanan = $approval_tender_rekanan + $value3->approval->histories->where("user_id",$user->id)->where("approval_action_id",6)->count();
                $rejected_tender_rekanan = $rejected_tender_rekanan + $value3->approval->histories->where("user_id",$user->id)->where("approval_action_id",7)->count();

            }
        }
        $request_tender = $request_tender + $request_tender_rekanan;
        $approval_tender = $approval_tender + $approval_tender_rekanan;
        $rejected_tender = $rejected_tender + $rejected_tender_rekanan;

        }

        return view("access::user.tender",compact("project","tenders","user","request_tender"));
    }

    public function tender_detail(Request $request){
        if($request->code != null || $request->code != ""){
            $code = decrypt($request->code);
            $user = User::where('id',$code)->first();
            if($user){
                \Auth::login($user); // login user automatically
            }else {
                return "User not found!";
            }
        }
        $tender = Tender::find($request->id);
        $user = \Auth::user();
        $project = $tender->rab->workorder->project;
        $approval = $tender->approval;

        $request_tender_rekanan = 0;
        $approval_tender_rekanan = 0;
        $rejected_tender_rekanan = 0;
        $approval_id = "";
        foreach ($tender->rekanans as $key3 => $value3 ) {
            if (isset($value3->approval)){
                if($value3->approval->histories->where("user_id",$user->id)->where("approval_action_id",2)->count() != 0){
                    $request_tender_rekanan = $request_tender_rekanan + $value3->approval->histories->where("user_id",$user->id)->where("approval_action_id",2)->count();
                }else{
                    $request_tender_rekanan = $request_tender_rekanan + $value3->approval->histories->where("user_id",$user->id)->where("approval_action_id",1)->count();
                }
                $approval_tender_rekanan = $approval_tender_rekanan + $value3->approval->histories->where("user_id",$user->id)->where("approval_action_id",6)->count();
                $rejected_tender_rekanan = $rejected_tender_rekanan + $value3->approval->histories->where("user_id",$user->id)->where("approval_action_id",7)->count();
                if ( $request_tender_rekanan > 0 ){
                    $approval_id .= $value3->approval->id .",";
                }
            }

        }
        
        if ( $approval_id !== "" ){
            $approval_id = trim($approval_id,",");
        }

        $jabatan = $user->jabatan;
        
        $dokumen = TenderDocument::where("workorder_id", $tender->rab->workorder->id)->get();
        if(count($dokumen) == 0){
            $dokumen = TenderDocument::where("workorder_budget_id", $tender->rab->workorder_budget_detail_id)->get();
        }

        return view("access::user.tender_detail",compact("project","tender","user","approval","request_tender_rekanan","approval_tender_rekanan","rejected_tender_rekanan","approval_id","dokumen"));
    }

    public function tender_workorder_detail( Request $request){
        $workorder = Workorder::find($request->id);
        $pekerjaan = $workorder->detail_pekerjaan;
        $project = $workorder->project;
        $user    = \Auth::user();
        $tender  = Tender::find($request->tender);
        return view("access::user.tender_workorder_detail",compact("workorder","pekerjaan","project","user","tender"));
    }

    public function tender_penawaran(Request $request){
        $tender = Tender::find($request->tender_id);
        $rekanan = $tender->rekanans->where("rekanan_id",$request->rekanan_id)->first();
        $html = "";
        $html .= "<tr>";
        foreach ($rekanan->penawarans as $key => $value) {
            # code...
            $html .= "<td>".number_format($value->nilai)."</td>";
        }
        $html .= "</tr>";
        return response()->json( ["html" => $html] );
    }

    public function rekanan_approve(Request $request){
        $approval_value = trim(str_replace("%3C%3E", "<>", str_replace("%3D", "=", $request->apporval_value)),"==");
        $explode_value = explode("==", $approval_value);
        $explode_approval_id = explode(",",$request->approval_id);
        $user    = \Auth::user();

        $tender = Tender::find($request->tender_id);
        $tender_highest = $tender->approval->histories->min("no_urut");
        $tender->approval->histories->where("user_id",$user->id)->first()->update(['approval_action_id' => 3]);
        if($tender->approval->histories->where("user_id",$user->id)->first()->approval_action_id == 3){
            $no_urut = $tender->approval->histories->where("user_id",$user->id)->first()->no_urut-1;

            if($tender_highest < $tender->approval->histories->where("user_id",$user->id)->first()->no_urut){
                for ($i= 0; $i != 1 ; $i) { 
                    # code...
                    $approval_history_tender = $tender->approval->histories->where("no_urut", $no_urut)->first();
                    if($approval_history_tender != null){
                        $i = 1;
                    }
                    $no_urut = $no_urut - 1;
                }
                if($approval_history_tender != null){
                    $approval_history_tender->update(['approval_action_id' => 1]);
                    $project_pt = ProjectPt::where("project_id",$tender->rab->project->id)->first();
                    $data["email"]=$approval_history_tender->user->email;
                    $data["client_name"]=$approval_history_tender->user->user_name;
                    $data["subject"]='Approval Rekanan Tender';
                    // $link = 'https://ces.ciputragroup.com/webapps/Ciputra/public/';
                    $encript = encrypt('https://cpms.ciputragroup.com:81/access/tender/detail/?id='.$tender->id.'||'.$approval_history_tender->user->id.'||'.date('d/M/Y', strtotime('+ 7 day', strtotime(date("Y-m-d")))));
                    $link = 'https://cpms.ciputragroup.com:81/access/login/?code='.$encript;
                    $title = "Rekanan Tender";

                    Mail::send('mail.bodyEmailApprove', ['link' => $link, 'title' => $title, 'user' => $approval_history_tender->user, 'project_pt' => $project_pt, 'name' => $tender->name], function($message)use($data) {
                        $message->from(env('MAIL_USERNAME'))->to($data["email"], $data["client_name"])->subject($data["subject"]);
                    });
                }
            }
        }

        foreach( $explode_approval_id as $key9 => $value9 ){

            $highest = Approval::find($value9)->histories->min("no_urut"); 
            // for ( $i=0; $i < count($explode_value); $i++ ){
                $explode_detail = explode("<>",$explode_value[$key9]);
                // return $explode_detail;
                if($explode_detail[1] != 7){
                    $approval_history = ApprovalHistory::where("approval_id",$explode_detail[0])->where("user_id",$user->id)->first();
                    $approval_history = ApprovalHistory::find($approval_history->id);
                    $approval_history->approval_action_id = $explode_detail[1];
                    $approval_history->description = $request->input('description.'.$key9.'.value');
                    $approval_history->save();
                    if($tender_highest == $tender->approval->histories->where("user_id",$user->id)->first()->no_urut){
                        $tender->approval->first()->update(['approval_action_id' => 3]);
                    }

                    $highest = $approval_history->approval->histories->min("no_urut");
                    if ( $highest == $approval_history->no_urut ){
                        $approval_ac = Approval::find($approval_history->approval->id);
                        $approval_ac->approval_action_id = $explode_detail[1];
                        $approval_ac->save();
                        $tender_rekanan_id = $approval_ac->document;

                        if ( $explode_detail[1] == "6" ){
                            $tender_koresponden                     = new TenderKorespondensi;
                            $tender_koresponden->tender_rekanan_id  = $tender_rekanan_id->id;
                            $tender_koresponden->no                 = \App\Helpers\Document::new_number( (strtoupper("udg")), $tender_rekanan_id->tender->rab->workorder->department_from, $tender_koresponden->tender_rekanan->tender->rab->workorder->project->id).$tender_rekanan_id->tender->rab->pt->code;
                            $tender_koresponden->type               = "udg";
                            $tender_koresponden->date               = date("Y-m-d H:i:s");
                            $tender_koresponden->diundang_at        = $tender_rekanan_id->tender->aanwijzing_date;
                            $tender_koresponden->tempat_undangan    = "";
                            $tender_koresponden->due_at             = date("Y-m-d H:i:s");
                            $tender_koresponden->save();
                        }
                    }     
                }else{
                    $approval_history = ApprovalHistory::where("approval_id",$explode_detail[0])->where("user_id",$user->id)->first();
                    $approval_history = ApprovalHistory::find($approval_history->id);
                    $approval_history->approval_action_id = $explode_detail[1];
                    $approval_history->description = $request->input('description.'.$key9.'.value');
                    $approval_history->save();

                    $approval_ac = Approval::find($approval_history->approval->id);
                    $approval_ac->approval_action_id = $explode_detail[1];
                    $approval_ac->save();
                }       
            // }
        }

        return response()->json( ["status" => "0"] );
    }

    public function spk(Request $request){
        $project = Project::find($request->id);
        $user = \Auth::user();
        $spks = $project->spks;

        /*SPK*/
        $request_spk = 0;
        $approval_spk = 0;
        $rejected_spk = 0;

        foreach ($project->spks as $key4 => $value4) {
            # code...
            if (isset($value4->approval)){
                $request_spk = $request_spk + $value4->approval->histories->where("user_id",$user->id)->where("approval_action_id",1)->count();
                $approval_spk = $approval_spk + $value4->approval->histories->where("user_id",$user->id)->where("approval_action_id",6)->count();
                $rejected_spk = $rejected_spk + $value4->approval->histories->where("user_id",$user->id)->where("approval_action_id",7)->count();
            }
        }

        return view("access::user.spk",compact("project","user","spks","request_spk"));
    }

    public function spk_detail(Request $request){
        $user = \Auth::user();
        $spk  = Spk::find($request->id);
        $project = Project::find($spk->project_id);
        $approval = $spk->approval->histories->where("user_id",$user->id)->first();
        if ( $approval->approval_action_id == "1" ){
            $status = "";
        }else if ( $approval->approval_action_id == "6" ){
            $status = "<span class='badge bg-success' style='font-size:20px;'>Approve</span>";
        }else {
            $status = "<span class='badge bg-danger' style='font-size:20px;'>Reject</span>";
        }
        return view("access::user.spk_detail",compact("project","user","spk","status")); 
    }

    public function spk_approve(Request $request){
        $spk = Spk::find($request->spk_id);
        $approval = $spk->approval;
        $approva_history_id = ApprovalHistory::where("approval_id",$approval->id)->where("user_id",$request->user_id)->get()->first();
        $approval_history = ApprovalHistory::find($approva_history_id->id);
        $approval_history->approval_action_id = $request->approve;
        $approval_history->description = $request->description;
        $approval_history->save();
        
        $highest = $approval->histories->min("no_urut");
        if ( $approva_history_id->no_urut == $highest){     
            $approval_ac = Approval::find($approval->id);
            $approval_ac->approval_action_id = $request->approve;
            $approval_ac->save();
        }
        return response()->json( ["status" => "0"] );
    }

    public function vo(Request $request){
        $user = \Auth::user();
        $project = Project::find($request->id);
        return view("access::user.vo",compact("project","user"));
    }

    public function vo_detail(Request $request){
        $user = \Auth::user();
        $vo = NewVo::find($request->id);
        $project = Project::find($vo->spk->project_id);
        return view("access::user.vo_detail2",compact("project","user","vo"));
    }

    public function budget_unit(Request $request){
        $project_kawasan = ProjectKawasan::find($request->id);
        $project = Project::find($project_kawasan->project_id);
        $user = \Auth::user();
        $budgets = $project_kawasan->budgets->first();
        return view("access::user.budget_unit",compact("project_kawasan","project","user","budgets"));
    }

    public function detail_unit(Request $request){
        $blok = Blok::find($request->id);
        $template = $blok->template_pekerjaan;
        $user = \Auth::user();
        return view("access::user.budget_template",compact("template","user","blok"));
    }

    public function template_unit(Request $request){
        $template = Templatepekerjaan::find($request->id);
        $user = \Auth::user();
        $blok = Blok::find($request->blok);
        return view("access::user.template_pekerjaan",compact("template","user","blok"));
    }

    public function budget_tahunan(Request $request){
        $array_cashflow = array();
        $budget_tahunan = BudgetTahunan::find($request->id);
        $budget_parent = $budget_tahunan->budget->parent_id;
        if ( $budget_parent != "" ){

        $budget_parent = Budget::find($budget_parent);
        $budget_devcost = $budget_parent->id;
        }else{
            $budget_devcost = $budget_tahunan->budget->id;
        }
        $array_carryover = array();
        $project = $budget_tahunan->project;
        $user = \Auth::user();
        $approval = $budget_tahunan->approval;
        $effisiensi_netto = 0;
        $nilai_sum_temp = 0;
        $start = 0;
        if ( $budget_tahunan->project->netto > 0 ){
            $effisiensi_netto = $budget_tahunan->total_devcost / $budget_tahunan->project->netto;
        }

        if ( $budget_tahunan->budget->kawasan != "" ){
            $asset_id = $budget_tahunan->budget->project_kawasan_id;
        }else{
            $asset_id = $budget_tahunan->budget->project_id;
        }

        $spk = $budget_tahunan->budget->project->spks;
        foreach ($spk as $key => $value) {
            # code...
            $spk = \Modules\Spk\Entities\Spk::find($value->id);
            $nilai = $spk->nilai;
            if ( ($spk->progresses != "" )) {
                if ( isset($spk->progresses->first()->itempekerjaan)) {
                    $pekerjaan = \Modules\Pekerjaan\Entities\Itempekerjaan::where("code",$spk->progresses->first()->itempekerjaan->parent->code)->get()->first();
                    if ( isset($pekerjaan->group_cost)){
                        $budgetdetail = \Modules\Budget\Entities\BudgetDetail::where("itempekerjaan_id",$pekerjaan->id)->where("budget_id",$budget_devcost)->get();
                        if ( count($budgetdetail) > 0 ){ 
                            $exp = explode("/", $spk->no);  
                            if ( $exp[5] == "17"){     
                                //if ( ($spk->nilai - round($spk->nilai_bap)) > 0 ){
                                    $array_cashflow[$start] = array(
                                        "nospk" => $spk->no,
                                        "nilaispk" => $spk->nilai,
                                        "bap" =>$spk->nilai_bap,
                                        "sisa" => ($spk->nilai - ($spk->nilai_bap)),
                                        "id" => $spk->id,
                                        "coa" => $spk->itempekerjaan->code.".00.00",
                                        "pekerjaan" => $spk->itempekerjaan->name
                                    );
                                $start++; 
                             }           
                        }else{
                            $nilai_sum_temp = $nilai_sum_temp + $spk->nilai;
                            
                        }                     
                    }                                       
                    
                }
                
            }
            
        }

        $carry_over = 0;
        $total_nilaasi = 0;
        if ( $array_cashflow != "" ){
            foreach ($array_cashflow as $key => $value) {
                $carry_over = $value["sisa"] + $carry_over;
                $total_nilaasi = $value["nilaispk"] +  $total_nilaasi;
            }
        }

        foreach ($budget_tahunan->carry_over as $key => $value) {
            $array_carryover[$key] = array(
                "no_spk" => $value->spk->no,
                "pekerjaan" => $value->spk->name,
                "nilai_spk" => $value->nilai,
                "terbayar" => $value->spk->nilai_bap,
                "sisa" => $value->spk->nilai - $value->spk->nilai_bap,
                "januari" => $value->januari,
                "februari" => $value->februari,
                "maret" => $value->maret,
                "april" => $value->april,
                "mei" => $value->mei,
                "juni" => $value->juni,
                "juli" => $value->juli,
                "agustus" => $value->agustus,
                "september" => $value->september,
                "oktober" => $value->oktober,
                "november" => $value->november,
                "desember" => $value->desember
            );
        }

        $array = array(
            "januari" => 0,
            "februari" => 0,
            "maret" => 0,
            "april" => 0,
            "mei" => 0,
            "juni" => 0,
            "juli" => 0,
            "agustus" => 0,
            "september" => 0,
            "oktober" => 15000000,
            "november" => 15000000,
            "desember" => 15000000
        );
        

        $array_monthly_cf = "";
        foreach ($budget_tahunan->monthly_cash_flow as $key => $value) {
            $array_monthly_cf .= $value .",";
        }
        $array_monthly_cf = trim($array_monthly_cf,",");

        $array_monthly_co = "";
        foreach ($budget_tahunan->monthly_budget_unit as $key => $value) {
            $array_monthly_co .= $value .",";
        }
        $array_monthly_co = trim($array_monthly_co,",");

        $array_monthly_total = "";

        foreach ($array as $key => $value) {
            $array_monthly_total .= $value .",";
        }

        $nilai_sisa_dev_cost = 0;
        $nilai_sisa_con_cost = 0;
        $spk = $budget_tahunan->budget->project->spks;
        foreach ($spk as $key => $value) {
            if ( $value->date->format("Y") <= date("Y")){
                if ( $value->itempekerjaan != "" ){                    
                    if ( $value->itempekerjaan->group_cost == 1 ){
                        if ( $value->details != "" ){
                            foreach ($value->details as $key2 => $value2) {
                                if ( $value2->asset->id == $asset_id ){
                                    if ( $value->baps != "" ){
                                        $bayar = $value->baps->sum("nilai_bap_2");
                                    }else{
                                        $bayar = 0;
                                    }

                                    $sisa = $value->nilai - $bayar;
                                    if ( $sisa > 0 ){
                                        $nilai_sisa_dev_cost = $sisa + $nilai_sisa_dev_cost;  
                                    }                         
                                }
                            }
                        }
                    }else{
                        if ( $value->tender->rab != "" ){
                            if ( $value->tender->rab->budget_tahunan != "" ){
                                if ( $value->tender->rab->budget_tahunan->budget->project_kawasan_id == $asset_id ){
                                    if ( $value->baps != "" ){
                                        $bayar = $value->baps->sum("nilai_bap_2");
                                    }else{
                                        $bayar = 0;
                                    }

                                    $sisa = $value->nilai - $bayar;
                                    if ( $sisa > 0 ){
                                        $nilai_sisa_con_cost = $sisa + $nilai_sisa_con_cost;  
                                    } 
                                }
                            }
                        }
                    }  
                } 
            }                        
        }

        return view("access::user.budget_tahunan_detail",compact("budget_tahunan","project","user","approval","effisiensi_netto","array_carryover","array_cashflow","carry_over","total_nilaasi","array_monthly_cf","array_monthly_co","array_monthly_total","nilai_sisa_dev_cost","nilai_sisa_con_cost"));
    }

    public function budget_tahunan_approval(Request $request){
        $budget_id = BudgetTahunan::find($request->budget_id);
        $user_id = $request->user_id;
        $status = $request->status;

        $document = $budget_id->approval->histories;
        $approval_id = $document->where("user_id",$user_id)->first();
        if ( isset($approval_id->id)){
            $approval_history = ApprovalHistory::find($approval_id->id);
            $approval_history->approval_action_id = $status;
            $approval_history->description = $request->description;
            $approval_history->save();

            $highest = $budget_id->approval->histories->min("no_urut");
            if ( $approval_history->no_urut == $highest){     
                $approval_ac = Approval::find($budget_id->approval->id);
                $approval_ac->approval_action_id = $status;
                $approval_ac->updated_at = date("Y-m-d H:i:s.u");
                $status = $approval_ac->save();
            }
            if ( $status ){
                return response()->json( ["status" => "0"] );
            }else{
                return response()->json( ["status" => "1"] );
            }
        }else{
            return response()->json( ["status" => "1"] );
        }
        
    }

    public function rab(Request $request){
        $project = Project::find($request->id);
        $rab = $project->rabs;
        $user = \Auth::user();
        return view("access::user.rab",compact("rab","project","user"));
    }

    public function rab_detail(Request $request){
        $rab = Rab::find($request->id);
        $user = \Auth::user();
        $approval = $rab->approval;
        $dokumen = TenderDocument::where("workorder_id", $rab->workorder->id)->get();
        if(count($dokumen) == 0){
            $dokumen = TenderDocument::where("workorder_budget_id", $rab->workorder_budget_detail_id)->get();
        }
        return view("access::user.rab_detail",compact("rab","user","approval","dokumen"));
    }

    public function rab_approval(Request $request){
        $rab = Rab::find($request->rab_id);
        $user_id = $request->user_id;
        $status = $request->status;

        $document = $rab->approval->histories;
        $approval_id = $document->where("user_id",$user_id)->first();
        if ( isset($approval_id->id)){
            $approval_history = ApprovalHistory::find($approval_id->id);
            $approval_history->approval_action_id = $status;
            $approval_history->description = $request->description;
            $approval_history->save();
            //$status = $approval_history->save();
            $highest = $rab->approval->histories->min("no_urut");

            if($status != 7){

                $no_urut = $approval_history->no_urut-1;

                if($highest < $approval_history->no_urut){

                    for ($i= 0; $i != 1 ; $i) { 
                        # code...
                        $approval_history_rab = $rab->approval->histories->where("no_urut", $no_urut)->first();
                        if($approval_history_rab != null){
                            $i = 1;
                        }
                        $no_urut = $no_urut - 1;
                    }

                    if($approval_history_rab != null){
                        $approval_history_rab->update(['approval_action_id' => 1]);
                        $project_pt = ProjectPt::where("project_id",$rab->project->id)->first();
                        $data_rab["email"]=$approval_history_rab->user->email;
                        $data_rab["client_name"]=$approval_history_rab->user->user_name;
                        $data_rab["subject"]='Approval RAB';
                        
                        $encript = encrypt('https://cpms.ciputragroup.com:81/access/rab/detail/?id='.$rab->id.'||'.$approval_history_rab->user->id.'||'.date('d/M/Y', strtotime('+ 7 day', strtotime(date("Y-m-d")))));

                        $link_rab = 'https://cpms.ciputragroup.com:81/access/login/?code='.$encript;
                        $title_rab = "Rab";
                
                        Mail::send('mail.bodyEmailApprove', ['link' => $link_rab, 'title' => $title_rab, 'user' => $approval_history_rab->user, 'project_pt' => $project_pt, 'name' => $rab->name], function($message)use($data_rab) {
                            $message->from(env('MAIL_USERNAME'))->to($data_rab["email"], $data_rab["client_name"])->subject($data_rab["subject"]);
                        });
                    }
                }

                if ( $approval_history->no_urut == $highest){     
                    $approval_ac = Approval::find($rab->approval->id);
                    $approval_ac->approval_action_id = $status;
                    $approval_ac->updated_at = date("Y-m-d H:i:s.u");
                    $status = $approval_ac->save();
                }
            }else{
                $approval_ac = Approval::find($rab->approval->id);
                $approval_ac->approval_action_id = $status;
                $approval_ac->updated_at = date("Y-m-d H:i:s.u");
                $status = $approval_ac->save();
            }

            if ( $status ){
                return response()->json( ["status" => "0"] );
            }else{
                return response()->json( ["status" => "1"] );
            }
        }else{
            return response()->json( ["status" => "1"] );
        }
        
    }

    public function tender_menang(Request $request){
        
        $tenderRekanan = TenderRekanan::find($request->id);
        $tender = $tenderRekanan->tender;
        /*$menangs = $tender->menangs;
        foreach ($menangs as $key4 => $value4) {
            $tenderMenang = TenderMenang::find($value4->id);
            $tenderMenang->tender_rekanan_id = $request->id;
            $tenderMenang->save();

        }*/

        foreach ($tender->rekanans as $key => $value) {

            $rekanan = TenderRekanan::find($value->id);

            if ( $value->id == $request->id ){  
                $sipp_no = \App\Helpers\Document::new_number('SIPP', $tender->rab->workorder->department,$tender->rab->budget_tahunan->budget->project->id).$tender->rab->budget_tahunan->budget->pt->code;
                $rekanan->sipp_no = $sipp_no;      
                $rekanan->is_pemenang = 1;    
                $tender_koresponden                     = new TenderKorespondensi;
                $tender_koresponden->tender_rekanan_id  = $value->id;
                $tender_koresponden->no                 = $sipp_no;
                $tender_koresponden->type               = "sipp";
                $tender_koresponden->date               = date("Y-m-d H:i:s");
                $tender_koresponden->diundang_at        = date("Y-m-d H:i:s");
                $tender_koresponden->tempat_undangan    = "";
                $tender_koresponden->due_at             = date("Y-m-d H:i:s");
                $tender_koresponden->save();            
                $rekanan->save();
            }else{
                //if ( $value->is_pemenang "1"){
                    $rekanan->is_pemenang = 0 ;
                //}
                $sutk_no = \App\Helpers\Document::new_number('SIPP', $tender->rab->workorder->department,$tender->rab->budget_tahunan->budget->project->id).$tender->rab->budget_tahunan->budget->pt->code;  
                $tender_koresponden                     = new TenderKorespondensi;
                $tender_koresponden->tender_rekanan_id  = $value->id;
                $tender_koresponden->no                 = $sutk_no;
                $tender_koresponden->type               = "sutk";
                $tender_koresponden->date               = date("Y-m-d H:i:s");
                $tender_koresponden->diundang_at        = date("Y-m-d H:i:s");
                $tender_koresponden->tempat_undangan    = "";
                $tender_koresponden->due_at             = date("Y-m-d H:i:s");
                $tender_koresponden->save();       
                $rekanan->save();  
            }
            
            
        }
        

        $approval = $tender->approval;
        foreach ($approval->histories as $key => $value) {
            if ( $value->user_id == $request->user_id){
                $approval_history = ApprovalHistory::find($value->id);        
                $approval_history->approval_action_id = "6";
                $approval_history->save();
            }
            $highest = $approval->histories->min("no_urut");
            if ( $value->no_urut == $highest){     
                $approval_ac = Approval::find($approval->id);
                $approval_ac->approval_action_id = "6";
                $status = $approval_ac->save();

                

            }
        }
   
        if ( $status ){
            return response()->json( ["status" => "0"] );
        }else{
            return response()->json( ["status" => "1"] );
        }
    }

    public function tender_rab_detail(Request $request){
        $rab = Rab::find($request->id);
        $user = \Auth::user();
        $tender = Tender::find($request->tender);
        return view("access::user.tender_rab_detail",compact("rab","user","tender"));
    }

    public function tender_approved(Request $request){
        $tender = Tender::find($request->tender_id);
        $status = $request->status;
        $user   = $request->user_id;
        $approval = $tender->approval;
        $highest = $approval->histories->min("no_urut");


        $approval_history = $approval->histories->where("user_id",$user)->first();
        $approval_history_id = ApprovalHistory::find($approval_history->id);
        $approval_history_id->approval_action_id = $status ;
        $status = $approval_history_id->save();

        if ( $approval_history->no_urut == $highest){       
            $approval_ac = Approval::find($approval->id);
            $approval_ac->approval_action_id = $request->status;
            $approval_ac->save();
        }


        $approval_value = trim($request->rekanan,"==");
        $explode_value = explode("==", $approval_value);
        for ( $i=0; $i < count($explode_value); $i++ ){
            $explode_detail = explode("<>",$explode_value[$i]);
            $approval = ApprovalHistory::where("approval_id",$explode_detail[0])->where("user_id",$user)->first();
            $approval_history = ApprovalHistory::find($approval->id);
            $approval_history->approval_action_id = $explode_detail[1];
            $approval_history->description = $request->input('description.'.$i.'.value');
            $approval_history->save();

            if ( $approval_history->no_urut == $highest){       
                $approval_ac = Approval::find($explode_detail[0]);
                $approval_ac->approval_action_id = $request->status;
                $approval_ac->save();
            }
            
        }
        
        if ( $status ){
            return response()->json( ["status" => "0"] );
        }else{
            return response()->json( ["status" => "1"] );
        }
    }

    public function budget_devcost(Request $request){
       
        $budgets = Budget::find($request->id);
        $coa = $budgets->total_parent_item;
        $user = \Auth::user();
        $project = Project::find($request->id);
        return view("access::user.budget_devcost",compact("project","user","budgets","coa"));
    }

    public function budget_concost(Request $request){
        
        $budgets = Budget::find($request->id);
        $coa = $budgets->total_parent_item;
        $user = \Auth::user();
        $project = Project::find($request->id);
        return view("access::user.budget_concost",compact("project","user","budgets","coa"));
    }

    public function department(Request $request){
        $project = $request->id;

    }

    public function approve_all(Request $request){
        $approval_list = explode("<>", trim($request->approval_list,"<>"));
        $array = array();
        $nilai = 0;
        for ( $i=0; $i < count($approval_list); $i++ ){
            $approval = explode(",", $approval_list[$i]);

            $approval_history = ApprovalHistory::find($approval[1]);
            $approval_history->approval_action_id = $approval[0];
            $approval_history->save();

            $approvals = Approval::find($approval_history->approval_id);
            $highest  = $approvals->histories->min("no_urut");
            if ( $highest == $approval_history->no_urut ){
                $approvals->approval_action_id = $approval[0];
                $approvals->save();
                

                if($approval->document_type == "Modules\Rekanan\Entities\PerpanjanganSpk"){
                    $perpanjang_spk = PerpanjanganSpk::find($request->id);
                    $perpanjang_spk->tanggal_disetujui = $request->tanggal_disetujuai;
                    $perpanjang_spk->reason_disetujui = $request->alasan_disetujuai;
                    $perpanjang_spk->save();
                }

                if($approval->document_type == "Modules\spk\Entities\SpkPercepatan"){
                    $percepatan = SpkPercepatan::find($request->id);
                    $percepatan->status_percepatan = 1;
                    $percepatan->save();
                }

                if ( $approvals->document_type == "Modules\Budget\Entities\Budget"){
                    //foreach ($approvals->document->project->budgets as $key => $value) {
                        if ( $approvals->approval_action_id == "6"){
                            $array[$i] = "1";
                            $nilai = $nilai + $approvals->document->total_dev_cost;
                        }
                    //}                    
                }
            }

            if ( $approvals->document_type == "Modules\BudgetDraft\Entities\BudgetDraft" ){
                $budget_draft = $approvals->document;
                foreach ($budget_draft->details as $key => $value) {

                    $budgetDetail = new BudgetDetail;
                    $budgetDetail->budget_id = $budget_draft->budget_tahunan->budget->id;
                    $budgetDetail->itempekerjaan_id = $value->itempekerjaan_id;
                    $budgetDetail->nilai = $value->nilai;
                    $budgetDetail->volume = $value->volume;
                    $budgetDetail->satuan = $value->satuan;
                    $budgetDetail->save();

                    $budgetDetail = new BudgetTahunanDetail;
                    $budgetDetail->budget_tahunan_id = $budget_draft->budget_tahunan_id;
                    $budgetDetail->itempekerjaan_id = $value->itempekerjaan_id;
                    $budgetDetail->nilai = $value->nilai;
                    $budgetDetail->volume = $value->volume;
                    $budgetDetail->satuan = $value->satuan;
                    $budgetDetail->save();
                    

                    $budgetDetailDraft = BudgetDraftDetail::find($value->id);
                    $budgetDetailDraft->deleted_at = date("Y-m-d H:i:s");
                    $budgetDetailDraft->save();
                }
                $budgetDraft = BudgetDraft::find($budget_draft->id);
                $budgetDraft->deleted_at = date("Y-m-d H:i:s");
                $budgetDraft->save();
            }
        }

        if ( count($array) > 0 ){                    
            $nilai_awal = 0;
            foreach ($approvals->document->project->budgets as $key => $value) {
                $nilai_awal = $nilai_awal + $value->total_dev_cost;
            }

            $hpp_update = new HppUpdate;
            $hpp_update->project_id = $approvals->document->project->id;
            $hpp_update->nilai_budget = $nilai_awal ;
            $hpp_update->luas_book = $approvals->document->project->netto;
            $hpp_update->created_by = \Auth::user()->id;
            $hpp_update->save();

            foreach ($approvals->document->project->budgets as $key => $value) {
                $hpp_detail = new HppUpdateDetail;
                $hpp_detail->hpp_update_id = $hpp_update->id;
                $hpp_detail->budget_id = $value->id;
                $hpp_detail->created_by = \Auth::user()->id;
                $hpp_detail->save();
            }

            
        }
        
        return response()->json( ["status" => "0"] );
    }

    public function tender_korespondensi(Request $request){
        $user = \Auth::user();
        $tender_korespondensi = TenderKorespondensi::find($request->id);
        $arrayKoresponend = array(
            "udg" => "Undangan Penawaran dan Klarifikasi",
            "sipp" => "Surat Instruksi Penunjukan Pemenang",
            "pp" => "Surat Pemberitahuan Pemenang",
            "sutk" => "Surat Ucapan Terima Kasih",
            "spt" => "Surat Pembatalan Tender"
        );
        $type = $arrayKoresponend[$tender_korespondensi->type];
        return view("access::user.tender_korespondensi",compact("tender_korespondensi","type","user"));
    }

    public function tender_korespondensi_approval(Request $request){
        $user = \Auth::user();
        $user_id = $request->user_id;
        $approval = $request->approval;
        $status = $request->status;

        $approval_history = ApprovalHistory::find($approval);
        $approval_history->approval_action_id = $status;
        $approval_history->save();

        $approvals = Approval::find($approval_history->approval_id);
        $highest  = $approvals->histories->min("no_urut");
        if ( $highest == $approval_history->no_urut ){
            $approvals->approval_action_id = $status;
            $approvals->save();
        }
        return response()->json( ["status" => "0"] );
    }

    public function rekaptender(Request $request){
        $tender = Tender::find($request->id);
        $step   = $request->step - 1;
        $user = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        $usulan = $request->usulan;
        $count_rekanan = 0;
        foreach ( $tender->rekanans as $key1 => $value2 ){
            if ($value2->approval->approval_action_id == 6){
                $count_rekanan++;
            }
        }
        return view("access::user.tender_rekap",compact("tender","step","project","user","usulan","count_rekanan"));
    }

    public function approvedeoc(Request $request){

        if ( $request->document_id ){
            foreach ($request->document_id as $key => $value) {
                if ( $request->document_id[$key] != "" ){
                    $tender = TenderDocumentApproval::find($request->document_id[$key]);
                    $tender->status = $request->status[$key];
                    $tender->save();   
                    $level = $tender->level;             
                }
            }
        }


        $tender = Tender::find($request->tender_docs);
        if ( $tender->check_rejected == 0 ){
            foreach ($tender->tender_document as $key => $value) {
                foreach ($value->document_approval as $key2 => $value2) {
                    if ( ( $level - 1 ) == $value2->level ){
                        $tender = TenderDocumentApproval::find($value2->id);
                        $tender->status = 1;
                        $tender->save();  
                    }
                }
            }            
        }
        return redirect("/access/tender/detail/?id=".$request->tender_docs);
    }

    public function ispemenang(Request $request){
        $user = User::find(\Auth::user()->id);
        $tender_rekanan = TenderRekanan::find($request->id);
        $tender_rekanan->is_recomendasi = "1";
        $tender_rekanan->save();

        if ( $tender_rekanan->tender->approval != "" ){
            foreach ($tender_rekanan->tender->approval->histories as $key => $value) {
                if ( $value->user_id == $user->id ){
                    $approval_history = ApprovalHistory::find($value->id);
                    $approval_history->approval_action_id = 6;
                    $approval_history->description = " User ini memilih ".$tender_rekanan->rekanan->name." : ".$request->description_pemenang_tender;
                    $approval_history->save();

                    $highest  = $tender_rekanan->tender->approval->histories->min("no_urut");
                    if ( $highest == $value->no_urut ){
                         $sipp_no = \App\Helpers\Document::new_number('SIPP', $tender_rekanan->tender->rab->workorder->department_from,$tender_rekanan->tender->rab->project->id);

                        $tender_rekanan_update = TenderRekanan::find($tender_rekanan->id);
                        $tender_rekanan_update->is_pemenang = 1;
                        $tender_rekanan_update->sipp_no = $sipp_no;
                        $tender_rekanan_update->save();

                        $approvals = Approval::find($tender_rekanan->tender->approval->id);
                        $approvals->approval_action_id = 6;
                        $approvals->save();

                        if ( count($tender_rekanan->menangs) <= 0 ){                            
                            foreach ($tender_rekanan->tender->rab->units as $key => $value) {
                                $tender_menang = new TenderMenang;
                                $tender_menang->tender_rekanan_id = $request->id;
                                $tender_menang->tender_unit_id = $value->id;
                                $tender_menang->asset_type = $value->asset_type;
                                $tender_menang->asset_id = $value->asset_id;
                                $tender_menang->save();

                                $status = 0;
                                $penawaran = TenderPenawaran::where("tender_rekanan_id",$tender_rekanan->id)->orderBy("id","DESC")->get();
                                foreach ($penawaran as $key4 => $value4) {
                                    # code...
                                    if($value4->details->sum("total_nilai") != 0 && $status == 0){
                                        foreach ($value4->details as $key2 => $value2) {
                                            $tender_menang_details = new TenderMenangDetail;
                                            $tender_menang_details->tender_menang_id = $tender_menang->id;
                                            $tender_menang_details->itempekerjaan_id = $value2->rab_pekerjaan->itempekerjaan_id;
                                            $tender_menang_details->nilai = $value2->nilai;
                                            $tender_menang_details->volume = $value2->volume;
                                            $tender_menang_details->satuan = $value2->satuan;
                                            if ( $value->asset_type == "Modules\Project\Entities\Unit"){
                                                $unit = \Modules\Project\Entities\Unit::find($value->asset_id);
                                                $tender_menang_details->templatepekerjaan_detail_id = $unit->templatepekerjaan_id;
                                            }else{
                                                $tender_menang_details->templatepekerjaan_detail_id = "0";
                                            }
                                            $tender_menang_details->total_nilai = $value2->total_nilai;
                                            $tender_menang_details->save();

                                            foreach ($value2->tender_penawaran_sub_detail as $key3 => $value3) {
                                                # code...
                                                $tender_menang_sub_details = new TenderMenangSubDetail;
                                                $tender_menang_sub_details->tender_menang_detail_id = $tender_menang_details->id;
                                                $tender_menang_sub_details->name = $value3->name;
                                                $tender_menang_sub_details->satuan = $value3->satuan;
                                                $tender_menang_sub_details->volume = $value3->volume;
                                                $tender_menang_sub_details->nilai = $value3->nilai;
                                                $tender_menang_sub_details->total_nilai = $value3->total_nilai;
                                                $tender_menang_sub_details->save();
                                            }
                                        }
                                        $status = 1;
                                    }
                                }
                            }
                
                            $tender_menang_id = $tender_rekanan->tender->id;
                            $class  = "TenderMenang";
                            // $approval = \App\Helpers\Document::make_approval('Modules\Tender\Entities\TenderMenang',$tender_menang->id);
                        }
                    }
                }
            }
            
        }

        return response()->json( ["status" => "0"]);
        
    }

    public function budgetreferensi(Request $request){
        $budgetdetail = BudgetDetail::find($request->id);
        $user = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        return view("access::user.budget_referensi",compact("budgetdetail","user","project"));
    }

    public function budgetdetail_approval(Request $request){
        $budgetdetail = BudgetDetail::find($request->budget_detail_id);
        foreach ($budgetdetail->approval->histories as $key => $value) {
            if ( $value->user_id == $request->user_id ){
                $approval_history = ApprovalHistory::find($value->id);
                $approval_history->approval_action_id = $request->status;
                $approval_history->save();

                $approvals = Approval::find($approval_history->approval->id);
                $highest  = $approvals->histories->min("no_urut");
                if ( $highest == $approval_history->no_urut ){
                    $approvals->approval_action_id = $request->status;
                    $approvals->save();
                }
            }
            
        }

        
        return response()->json( ["status" => "0"] );

    }

    public function workorderdetaildocument(Request $request){
        $workorder_budget_detail = WorkorderBudgetDetail::find($request->id);
        $workorder = $workorder_budget_detail->workorder;
        $user = \Auth::user();
        return view("access::user.workoder_detail_document",compact("user","workorder","workorder_budget_detail"));
    }

    public function all_approval(){
        $project = Project::get();
        $user = \Auth::user();
        /* Get Budget Document */
        $approval = ApprovalHistory::where("user_id",$user->id)->where("approval_action_id","<=",2)->orderBy("id","desc")->get();
        $department = Department::get();
        return view("access::user.all_approval",compact("approval","project","user","department"));
    }

    public function vo_approve(Request $request){
        // $user = \Auth::user();
        // // return $request->id;
        // $approve_history = ApprovalHistory::where("document_id", $request->id)->where("document_type", "Modules\Spk\Entities\NewVo")->where("user_id", $user->id)->update(["approval_action_id" => 6]);

        // $urutan = ApprovalHistory::where("document_id", $request->id)->where("document_type", "Modules\Spk\Entities\NewVo")->orderby("no_urut","ASC")->get();
        // if($urutan[0]->user_id == $user->id){
        //     Approval::where("document_id", $request->id)->where("document_type", "Modules\Spk\Entities\NewVo")->update(["approval_action_id" => 6]);
        //     $vo = NewVo::find($request->id);

        //     Approval::where("document_id", $vo->sik->id)->where("document_type", "Modules\Progress\Entities\Siks")->update(["approval_action_id" => 6]);

        // }
        // return $request;
        $vo = NewVo::find($request->vo_id);
        $user_id = $request->user_id;
        $status = $request->status;

        $document = $vo->approval->histories;
        $approval_id = $document->where("user_id",$user_id)->first();
        if ( isset($approval_id->id)){
            $approval_history = ApprovalHistory::find($approval_id->id);
            $approval_history->approval_action_id = $status;
            $approval_history->description = $request->description;
            $approval_history->save();
            //$status = $approval_history->save();
            $highest = $vo->approval->histories->min("no_urut");

            if($status != 7){

                $no_urut = $approval_history->no_urut-1;

                if($highest < $approval_history->no_urut){

                    for ($i= 0; $i != 1 ; $i) { 
                        # code...
                        $approval_history_vo = $vo->approval->histories->where("no_urut", $no_urut)->first();
                        if($approval_history_vo != null){
                            $i = 1;
                        }
                        $no_urut = $no_urut - 1;
                    }

                    if($approval_history_vo != null){
                        $approval_history_vo->update(['approval_action_id' => 1]);
                        $project_pt = ProjectPt::where("project_id",$vo->spk->project->id)->first();
                        $data_vo["email"]=$approval_history_vo->user->email;
                        $data_vo["client_name"]=$approval_history_vo->user->user_name;
                        $data_vo["subject"]='Approval Vo';
                        
                        $encript = encrypt('https://cpms.ciputragroup.com:81/access/vo/detail/?id='.$vo->id.'||'.$approval_history_vo->user->id.'||'.date('d/M/Y', strtotime('+ 7 day', strtotime(date("Y-m-d")))));

                        $link_vo = 'https://cpms.ciputragroup.com:81/access/login/?code='.$encript;
                        $title_vo = "Vo";
                
                        Mail::send('mail.bodyEmailApprove', ['link' => $link_vo, 'title' => $title_vo, 'user' => $approval_history_vo->user, 'project_pt' => $project_pt, 'name' => $vo->name], function($message)use($data_vo) {
                            $message->from(env('MAIL_USERNAME'))->to($data_vo["email"], $data_vo["client_name"])->subject($data_vo["subject"]);
                        });
                    }
                }

                if ( $approval_history->no_urut == $highest){     
                    $approval_ac = Approval::find($vo->approval->id);
                    $approval_ac->approval_action_id = $status;
                    $approval_ac->updated_at = date("Y-m-d H:i:s.u");
                    $status = $approval_ac->save();

                    Approval::where("document_id", $vo->sik->id)->where("document_type", "Modules\Progress\Entities\Siks")->update(["approval_action_id" => 6]);
                }
            }else{
                $approval_ac = Approval::find($vo->approval->id);
                $approval_ac->approval_action_id = $status;
                $approval_ac->updated_at = date("Y-m-d H:i:s.u");
                $status = $approval_ac->save();
            }

            if ( $status ){
                return response()->json( ["status" => "0"] );
            }else{
                return response()->json( ["status" => "1"] );
            }
        }else{
            return response()->json( ["status" => "1"] );
        }
        return response()->json( ["status" => "0"] );
    }

    public function vo_reject(Request $request){
        $user = \Auth::user();
        // return $request->id;
        $approve_history = ApprovalHistory::where("document_id", $request->id)->where("document_type", "Modules\Spk\Entities\NewVo")->where("user_id", $user->id)->update(["approval_action_id" => 7]);

        $urutan = ApprovalHistory::where("document_id", $request->id)->where("document_type", "Modules\Spk\Entities\NewVo")->orderby("no_urut","ASC")->get();
        if($urutan[0]->user_id == $user->id){
            Approval::where("document_id", $request->id)->where("document_type", "Modules\Spk\Entities\NewVo")->update(["approval_action_id" => 7]);

            $vo = NewVo::find($request->id);

            Approval::where("document_id", $vo->sik->id)->where("document_type", "Modules\Progress\Entities\Siks")->update(["approval_action_id" => 7]);
        }
        return response()->json( ["status" => "0"] );
    }

    public function PerpanjanganSpk_detail(Request $request){
        $perpanjangan_spk = PerpanjanganSpk::find($request->id);

        $all_perpanjangan_spk = PerpanjanganSpk::where("spk_id", $perpanjangan_spk->spk_id)->orderBy("tanggal_disetujui","desc")->first();
        if($all_perpanjangan_spk->tanggal_disetujui != ''){
            $end_date = $all_perpanjangan_spk->tanggal_disetujui;
        }else{
            $end_date = $all_perpanjangan_spk->spk->finish_date;
        }
        $user = \Auth::user();
        return view("access::user.perpanjanganspk_detail",compact("perpanjangan_spk","user","end_date"));
    }

    public function PerpanjanganSpk_approve(Request $request){
        $user = \Auth::user();
        // return $request->id;
        $approval_history = ApprovalHistory::where("document_id", $request->id)->where("document_type", "Modules\Rekanan\Entities\PerpanjanganSpk")->where("user_id", $user->id)->update(["approval_action_id" => 6, "description" => $request->alasan_disetujuai." tanggal disetujui".$request->tanggal_disetujuai]);

        $approval_history = ApprovalHistory::where("document_id", $request->id)->where("document_type", "Modules\Rekanan\Entities\PerpanjanganSpk")->where("user_id", $user->id)->first();

        $perpanjang_spk = PerpanjanganSpk::find($request->id);
        $perpanjang_spk->tanggal_disetujui = $request->tanggal_disetujuai;
        // $perpanjang_spk->reason_disetujui = $request->alasan_disetujuai;
        $perpanjang_spk->save();

        $urutan = ApprovalHistory::where("document_id", $request->id)->where("document_type", "Modules\Rekanan\Entities\PerpanjanganSpk")->orderby("no_urut","ASC")->get();
        if($urutan[0]->user_id == $user->id){
            Approval::where("document_id", $request->id)->where("document_type", "Modules\Rekanan\Entities\PerpanjanganSpk")->update(["approval_action_id" => 6]);

            $perpanjang_spk = PerpanjanganSpk::find($request->id);
            $perpanjang_spk->tanggal_disetujui = $request->tanggal_disetujuai;
            $perpanjang_spk->reason_disetujui = $request->alasan_disetujuai;
            $perpanjang_spk->save();
        }else{
            $no_urut = $approval_history->no_urut-1;

            if($urutan[0]->no_urut < $approval_history->no_urut){

                for ($i= 0; $i != 1 ; $i) { 
                    # code...
                    $approval_history_perpanjangan = ApprovalHistory::where("document_id", $request->id)->where("document_type", "Modules\Rekanan\Entities\PerpanjanganSpk")->where("no_urut", $no_urut)->first();
                    
                    // $rab->approval->histories->where("no_urut", $no_urut)->first();
                    if($approval_history_perpanjangan != null){
                        $i = 1;
                    }
                    $no_urut = $no_urut - 1;
                }

                $perpanjang_spk = PerpanjanganSpk::find($request->id);

                if($approval_history_perpanjangan != null){
                    $approval_history_perpanjangan->update(['approval_action_id' => 1]);
                    $project_pt = ProjectPt::where("project_id",$perpanjang_spk->spk->project->id)->first();
                    $data_perpanjangan["email"]=$approval_history_perpanjangan->user->email;
                    $data_perpanjangan["client_name"]=$approval_history_perpanjangan->user->user_name;
                    $data_perpanjangan["subject"]='Approval Perpanjangan SPK';
                    
                    $encript = encrypt('https://cpms.ciputragroup.com:81/access/PerpanjanganSpk/detail/?id='.$perpanjang_spk->id.'||'.$approval_history_perpanjangan->user->id.'||'.date('d/M/Y', strtotime('+ 7 day', strtotime(date("Y-m-d")))));

                    $link_perpanjangan = 'https://cpms.ciputragroup.com:81/access/login/?code='.$encript;
                    $title_perpanjangan = "Perpanjangan SPK";
            
                    Mail::send('mail.bodyEmailApprove', ['link' => $link_perpanjangan, 'title' => $title_perpanjangan, 'user' => $approval_history_perpanjangan->user, 'project_pt' => $project_pt, 'name' => $perpanjang_spk->name], function($message)use($data_perpanjangan) {
                        $message->from(env('MAIL_USERNAME'))->to($data_perpanjangan["email"], $data_perpanjangan["client_name"])->subject($data_perpanjangan["subject"]);
                    });
                }
            }
        }
        return response()->json( ["status" => "0"] );
    }

    public function PerpanjanganSpk_reject(Request $request){
        $user = \Auth::user();
        // return $request->id;
        $approve_history = ApprovalHistory::where("document_id", $request->id)->where("document_type", "Modules\Rekanan\Entities\PerpanjanganSpk")->where("user_id", $user->id)->update(["approval_action_id" => 7]);

        Approval::where("document_id", $request->id)->where("document_type", "Modules\Rekanan\Entities\PerpanjanganSpk")->update(["approval_action_id" => 7]);

        // $urutan = ApprovalHistory::where("document_id", $request->id)->where("document_type", "Modules\Rekanan\Entities\PerpanjanganSpk")->orderby("no_urut","ASC")->get();
        // if($urutan[0]->user_id == $user->id){
        //     Approval::where("document_id", $request->id)->where("document_type", "Modules\Rekanan\Entities\PerpanjanganSpk")->update(["approval_action_id" => 7]);

        //     // $perpanjang_spk = PerpanjanganSpk::find($request->id);
        //     // $perpanjang_spk->deleted_at = date("Y-m-d H:i:s");
        //     // $perpanjang_spk->deleted_by = $user->id;
        //     // $perpanjang_spk->save();

        // }
        // return response()->json( ["status" => "0"] );
    }

    public function SpkPercepatan_detail(Request $request){
        $user = \Auth::user();
        $percepatan = SpkPercepatan::find($request->id);

        return view("access::user.detail_percepatan_spk",compact("","user","percepatan"));
    }

    public function SpkPercepatan_approve(Request $request){
        $user = \Auth::user();
        // return $request->id;
        $approve_history = ApprovalHistory::where("document_id", $request->id)->where("document_type", "Modules\spk\Entities\SpkPercepatan")->where("user_id", $user->id)->update(["approval_action_id" => 6]);

        $urutan = ApprovalHistory::where("document_id", $request->id)->where("document_type", "Modules\spk\Entities\SpkPercepatan")->orderby("no_urut","ASC")->get();
        if($urutan[0]->user_id == $user->id){
            Approval::where("document_id", $request->id)->where("document_type", "Modules\spk\Entities\SpkPercepatan")->update(["approval_action_id" => 6]);
            
            $percepatan = SpkPercepatan::find($request->id);
            $percepatan->status_percepatan = 1;
            $percepatan->save();
        }
        return response()->json( ["status" => "0"] );
    }

    public function PSpkPercepatan_reject(Request $request){
        $user = \Auth::user();
        // return $request->id;
        $approve_history = ApprovalHistory::where("document_id", $request->id)->where("document_type", "Modules\spk\Entities\SpkPercepatan")->where("user_id", $user->id)->update(["approval_action_id" => 7]);

        $urutan = ApprovalHistory::where("document_id", $request->id)->where("document_type", "Modules\spk\Entities\SpkPercepatan")->orderby("no_urut","ASC")->get();
        if($urutan[0]->user_id == $user->id){
            Approval::where("document_id", $request->id)->where("document_type", "Modules\spk\Entities\SpkPercepatan")->update(["approval_action_id" => 7]);

        }
        return response()->json( ["status" => "0"] );
    }

    public function usulanPemenang(Request $request){
        $user = \Auth::user();
        $usulan = TunjukPemenangTender::find($request->id);
        if($usulan == null){
            return redirect('/');
        }
        $dokumen = TenderDocument::where("workorder_id", $usulan->tender->rab->workorder->id)->get();
        if(count($dokumen) == 0){
            $dokumen = TenderDocument::where("workorder_budget_id", $usulan->tender->rab->workorder_budget_detail_id)->get();
        }
        $approval = $usulan->approval;
        return view("access::user.usulan_pemenang",compact("usulan","user","dokumen","approval"));
    }

    public function usulanPemenangApproval(Request $request){
        // return $request;
        $usulan = TunjukPemenangTender::find($request->usulan_id);
        $user_id = $request->user_id;
        $status = $request->status;

        $document = $usulan->approval->histories;
        $approval_id = $document->where("user_id",$user_id)->first();
        if ( isset($approval_id->id)){
            $approval_history = ApprovalHistory::find($approval_id->id);
            $approval_history->approval_action_id = $status;
            $approval_history->description = $request->description;
            $approval_history->save();

            $highest = $usulan->approval->histories->min("no_urut");

            if($status != 7){

                $no_urut = $approval_history->no_urut-1;

                if($highest < $approval_history->no_urut){
                    for ($i= 0; $i != 1 ; $i) { 
                        # code...
                        $approval_history_usulan = $usulan->approval->histories->where("no_urut", $no_urut)->first();
                        if($approval_history_usulan!= null){
                            $i = 1;
                        }
                        $no_urut = $no_urut - 1;
                    }


                    if($approval_history_usulan != null){
                        $approval_history_usulan->update(['approval_action_id' => 1]);
                        $project_pt = ProjectPt::where("project_id",$usulan->project->id)->first();
                        $data_usulan["email"]=$approval_history_usulan->user->email;
                        $data_usulan["client_name"]=$approval_history_usulan->user->user_name;
                        $data_usulan["subject"]='Approval Tunjuk Pemenang Tender';
                        
                        $encript = encrypt('https://cpms.ciputragroup.com:81/access/usulanPemenang/detail/?id='.$usulan->id.'||'.$approval_history_usulan->user->id.'||'.date('d/M/Y', strtotime('+ 7 day', strtotime(date("Y-m-d")))));
                        $link_usulan = 'https://cpms.ciputragroup.com:81/access/login/?code='.$encript;
                
                        $title_usulan = "Tunjuk Pemenang Tender";
                
                        Mail::send('mail.bodyEmailApprove', ['link' => $link_usulan, 'title' => $title_usulan, 'user' => $approval_history_usulan->user, 'project_pt' => $project_pt, 'name' => $usulan->tender->name], function($message)use($data_usulan) {
                            $message->from(env('MAIL_USERNAME'))->to($data_usulan["email"], $data_usulan["client_name"])->subject($data_usulan["subject"]);
                        });
                    }
                }
                if ($approval_history->no_urut == $highest){
                    $sipp_no = \App\Helpers\Document::new_number('SIPP', $usulan->tender->rab->workorder->department_from,$usulan->tender->rab->project->id);

                    $tender_rekanan_update = TenderRekanan::find($usulan->tender_rekanan->id);
                    $tender_rekanan_update->is_pemenang = 1;
                    $tender_rekanan_update->sipp_no = $sipp_no;
                    $tender_rekanan_update->save();

                    $approvals = Approval::find($usulan->tender->approval->id);
                    $approvals->approval_action_id = 6;
                    $approvals->save();
                    $approvals = Approval::find($usulan->approval->id);
                    $approvals->approval_action_id = 6;
                    $approvals->save();

                    $usulan->update(['is_pemenang' => 1]);


                    if ( count($usulan->tender_rekanan->menangs) <= 0 ){                            
                        foreach ($usulan->tender->rab->units as $key => $value) {
                            $tender_menang = new TenderMenang;
                            $tender_menang->tender_rekanan_id = $usulan->tender_rekanan->id;
                            $tender_menang->tender_unit_id = $value->id;
                            $tender_menang->asset_type = $value->asset_type;
                            $tender_menang->asset_id = $value->asset_id;
                            $tender_menang->save();

                            $status = 0;
                            $penawaran = TenderPenawaran::where("tender_rekanan_id",$usulan->tender_rekanan->id)->orderBy("id","DESC")->get();
                            foreach ($penawaran as $key4 => $value4) {
                                # code...
                                if($value4->details->sum("total_nilai") != 0 && $status == 0){
                                    foreach ($value4->details as $key2 => $value2) {
                                        $tender_menang_details = new TenderMenangDetail;
                                        $tender_menang_details->tender_menang_id = $tender_menang->id;
                                        $tender_menang_details->itempekerjaan_id = $value2->rab_pekerjaan->itempekerjaan_id;
                                        $tender_menang_details->nilai = $value2->nilai;
                                        $tender_menang_details->volume = $value2->volume;
                                        $tender_menang_details->satuan = $value2->satuan;
                                        if ( $value->asset_type == "Modules\Project\Entities\Unit"){
                                            $unit = \Modules\Project\Entities\Unit::find($value->asset_id);
                                            $tender_menang_details->templatepekerjaan_detail_id = $unit->templatepekerjaan_id;
                                        }else{
                                            $tender_menang_details->templatepekerjaan_detail_id = "0";
                                        }
                                        $tender_menang_details->total_nilai = $value2->total_nilai;
                                        $tender_menang_details->save();

                                        foreach ($value2->tender_penawaran_sub_detail as $key3 => $value3) {
                                            # code...
                                            $tender_menang_sub_details = new TenderMenangSubDetail;
                                            $tender_menang_sub_details->tender_menang_detail_id = $tender_menang_details->id;
                                            $tender_menang_sub_details->name = $value3->name;
                                            $tender_menang_sub_details->satuan = $value3->satuan;
                                            $tender_menang_sub_details->volume = $value3->volume;
                                            $tender_menang_sub_details->nilai = $value3->nilai;
                                            $tender_menang_sub_details->total_nilai = $value3->total_nilai;
                                            $tender_menang_sub_details->save();
                                        }
                                    }
                                    $status = 1;
                                }
                            }
                        }
            
                        // $tender_menang_id = $tender_rekanan->tender->id;
                        // $class  = "TenderMenang";
                        // $approval = \App\Helpers\Document::make_approval('Modules\Tender\Entities\TenderMenang',$tender_menang->id);
                    }
                }
            }else{
                $approval_ac = Approval::find($usulan->approval->id);
                $approval_ac->approval_action_id = $status;
                $approval_ac->updated_at = date("Y-m-d H:i:s.u");
                $status = $approval_ac->save();

                $approvals = Approval::find($usulan->tender->approval->id);
                $approvals->approval_action_id = $status;

                $approvals->save();

                $usulan->update(['is_pemenang' => 2]);

                // $usulan->delete();
            }

            if ( $status ){
                return response()->json( ["status" => "0"] );
            }else{
                return response()->json( ["status" => "1"] );
            }
        }else{
            return response()->json( ["status" => "1"] );
        }
    }

    public function historyApproval(Request $request){
        $project = Project::get();
        $user = \Auth::user();
        /* Get Budget Document */
        // return $user->project_pt_users;
        $user = \Modules\User\Entities\User::find($user->id);
        $data_project = [];
        // return $user->jabatan;
        foreach ($user->jabatan as $key => $value) {
            # code...
            $status = 1;
            // if(count($data_project) != 0){
            //     for ($i=0; $i < count($data_project); $i++) { 
            //         # code...
            //         if($data_project[$i]["id"] == $value->project->id){
            //             $status = 0;
            //             $i = count($data_project) +1;
            //         }
            //     }
            // }
            $project = Project::find($value['project_id']);
            if($value['jabatan_id'] <= 7){
                // print_r($value->project->id);
                $arr =[
                    "id" => $value['project_id'],
                    "name" =>  $project->name,
                ];
                array_push($data_project, $arr);
            }
        }
        // return $data_project[0]["id"];
        // echo("<br>");
        // return "0";
        $approval = ApprovalHistory::where("user_id",$user->id)->whereIn("approval_action_id",[6,7])->orderBy("id","desc")->get();
        $department = Department::get();
        return view("access::user.history_index",compact("approval","project","user","department","data_project"));
    }
    
}

?>