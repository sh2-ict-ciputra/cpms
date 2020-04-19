<?php

namespace Modules\Access\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Modules\Department\Entities\Department;
use Modules\Pekerjaan\Entities\Itempekerjaan;
use Modules\PurchaseRequest\Entities\PurchaseRequest;
use Modules\PurchaseRequest\Entities\PurchaseRequestDetail;
use Modules\Budget\Entities\Budget;
use Modules\Budget\Entities\BudgetTahunan;
use Modules\Project\Entities\Project;
use Modules\Inventory\Entities\ItemProject;
use Modules\Inventory\Entities\Item;
use Modules\Inventory\Entities\BrandOfCategory;
use Modules\Inventory\Entities\ItemCategory;
use Modules\Inventory\Entities\Brand;
use Modules\Inventory\Entities\ItemSatuan;
use Modules\Approval\Entities\Approval;
use Modules\Rekanan\Entities\Rekanan;
use Modules\Inventory\Entities\CreateDocument;
use Modules\Approval\Entities\ApprovalHistory;
use Modules\TenderPurchaseRequest\Entities\PurchaseOrder;
use Modules\Project\Entities\ProjectPtUser;
use Modules\TenderPurchaseRequest\Entities\TenderPurchaseRequestGroup;
use Modules\TenderPurchaseRequest\Entities\TenderPurchaseRequestGroupDetail;
use Modules\TenderPurchaseRequest\Entities\TenderPurchaseRequestPenawaran;
use Modules\TenderPurchaseRequest\Entities\TenderPurchaseRequestPenawaranDetail;
use Modules\Rab\Entities\RabPekerjaan;
use Modules\Inventory\Entities\ItemPrice;
use Modules\TenderPurchaseRequest\Entities\TenderPurchaseRequestRekanan;
use Modules\TenderPurchaseRequest\Entities\TenderPurchaseRequestGroupRekanan;
use Modules\TenderPurchaseRequest\Entities\TenderPurchaseRequestGroupRekananDetails;
use Modules\TenderPurchaseRequest\Entities\PemenangTenderPurchaseRequest;
use Modules\TenderPurchaseRequest\Entities\PemenangTenderPurchaseRequestDetail;
use Modules\TenderPurchaseRequest\Entities\TenderMenangPR;
use Modules\TenderPurchaseRequest\Entities\PurchaseOrderDetail;
use Modules\PurchaseOrder\Entities\PurchaseOrderPr;
use Modules\PurchaseOrder\Entities\PurchaseOrderTerm;
use Modules\Tender\Entities\TenderMenang;
use Modules\Inventory\Entities\Warehouse;
use Modules\User\Entities\UserDetail;
use Modules\User\Entities\User;
use Modules\PenerimaanBarangPO\Entities\PenerimaanBarangPO;
use Modules\PenerimaanBarangPO\Entities\PenerimaanBarangPODetail;
use Modules\Inventory\Entities\Inventory;
use App\Mail\EmailPr;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;
use Modules\Project\Entities\ProjectPt;

use DB;
use Auth;

class PRPOAccessController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('access::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('access::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    { }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return view('access::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit()
    {
        return view('access::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request)
    { }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy()
    { }

    public function detail(Request $request)
    {
        $user = Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        $approve = UserDetail::where("user_id", $user->id)->select("can_approve")->first()->can_approve;

        $PR = PurchaseRequestDetail::where('purchaserequest_id', $request->id)->get();
        $PRHeader = PurchaseRequest::find($request->id);
        $total = 0;
        $totalTerakhir = 0;
        if ($PRHeader->budget_tahunan_id != 0) {
            $sisa_budget = BudgetTahunan::find($PRHeader->budget_tahunan_id)->total_parent_item;
            $summary = BudgetTahunan::find($PRHeader->budget->id)->getTotalParentItemAttribute();
            foreach ($summary as $v) {
                $total = $total + ((int)$v['nilai'] * (int)$v['volume']);
            }
            $terakhir = DB::table('budget_tahunan_details')->where('budget_tahunan_id', $PRHeader->budget->id)->orderBy('created_at', 'DESC')->where('deleted_at', null)->first();

            $pengguna_terakhir = PurchaseRequest::where('budget_tahunan_id', $PRHeader->budget->id)->orderBy('created_at', 'DESC')->first();
            $jumlahNilai_penggunaterakhir = PurchaseRequestDetail::select(
                'itempekerjaans.id as idPekerjaan',
                'itempekerjaans.name as namePekerjaan',
                'budget_tahunan_details.nilai as nilaiPekerjaan',
                'budget_tahunan_details.budget_tahunan_id as budgetPekerjaan'
            )
                ->where('purchaserequest_id', $pengguna_terakhir->id)
                ->where('budget_tahunan_id', $PRHeader->budget->id)
                ->join('itempekerjaans', 'itempekerjaans.id', 'purchaserequest_details.itempekerjaan_id')
                ->join('budget_tahunan_details', 'budget_tahunan_details.itempekerjaan_id', 'itempekerjaans.id')
                ->get();
            if ($jumlahNilai_penggunaterakhir != null) {
                foreach ($jumlahNilai_penggunaterakhir as $key => $value) {
                    $jumlahNilai[] = $value->nilaiPekerjaan;
                }
                $totalTerakhir = array_sum($jumlahNilai);
            } else {
                $totalTerakhir = 0;
            }
        } else {
            $sisa_budget = 0;
            $terakhir = "kosong";
            $pengguna_terakhir = "kosong";
        }

        $pr_id = $request->id;

        $total = "Rp " . number_format($total, 2, ',', '.');



        return view('access::PurchaseRequest.detail', compact("user", "project", "PR", "approve", "pr_id", "PRHeader", "sisa_budget", "total", "pengguna_terakhir", "totalTerakhir"));
        //return view('purchaserequest::index');
    }

    public function approve(Request $request)
    {
        // return $request;
        $PRHeader = PurchaseRequest::find($request->pr_id);
        $project_id = $request->session()->get('project_id');
        date_default_timezone_set('asia/jakarta');
        $date = date("Y-m-d h:i:s");
        $user_id = $request->user_id;
        $status = $request->status;
        
        $document = $PRHeader->approval->histories;
        $approval_id = $document->where("user_id",$user_id)->first();
        if ( isset($approval_id->id)){
            $approval_history = ApprovalHistory::find($approval_id->id);
            $approval_history->approval_action_id = $status;
            $approval_history->description = $request->description;
            $approval_history->save();

            foreach ($PRHeader->details as $key => $value) {
                # code...
                $document = $value->approval->histories;
                $approval_id_detail = $document->where("user_id",$user_id)->first();

                $approval_history_detail = ApprovalHistory::find($approval_id_detail->id);
                $approval_history_detail->approval_action_id = $status;
                $approval_history_detail->description = $request->description;
                $approval_history_detail->save();
            }
            //$status = $approval_history->save();
            $highest = $PRHeader->approval->histories->min("no_urut");

            if($status != 7){

                $no_urut = $approval_history->no_urut-1;

                if($highest < $approval_history->no_urut){

                    for ($i= 0; $i != 1 ; $i) { 
                        # code...
                        $approval_history_pr = $PRHeader->approval->histories->where("no_urut", $no_urut)->first();
                        if($approval_history_pr != null){
                            $i = 1;
                        }
                        $no_urut = $no_urut - 1;
                    }

                    if($approval_history_pr != null){
                        $approval_history_pr->update(['approval_action_id' => 1]);
                        $project_pt = ProjectPt::where("project_id",$PRHeader->project->id)->first();
                        $data_pr["email"]=$approval_history_pr->user->email;
                        $data_pr["client_name"]=$approval_history_pr->user->user_name;
                        $data_pr["subject"]='Approval Purchase Request';
                        
                        $encript = encrypt('http://cpms.ciputragroup.com:81/access/purchaserequest/detail/?id='.$PRHeader->id.'||'.$approval_history_pr->user->id.'||'.date('d/M/Y', strtotime('+ 7 day', strtotime(date("Y-m-d")))));

                        $link_pr = 'http://cpms.ciputragroup.com:81/access/login/?code='.$encript;
                        $title_pr = "Purchase Request";
                
                        Mail::send('mail.bodyEmailApprove', ['link' => $link_pr, 'title' => $title_pr, 'user' => $approval_history_pr->user, 'project_pt' => $project_pt, 'name' => $PRHeader->name], function($message)use($data_pr) {
                            $message->from(env('MAIL_USERNAME'))->to($data_pr["email"], $data_pr["client_name"])->subject($data_pr["subject"]);
                        });
                    }
                }

                if ( $approval_history->no_urut == $highest){     
                    $approval_ac = Approval::find($PRHeader->approval->id);
                    $approval_ac->approval_action_id = $status;
                    $approval_ac->updated_at = date("Y-m-d H:i:s.u");
                    $status = $approval_ac->save();

                    foreach ($PRHeader->details as $key => $value) {
                        $approval_ac = Approval::find($value->approval->id);
                        $approval_ac->approval_action_id = 6;
                        $approval_ac->updated_at = date("Y-m-d H:i:s.u");
                        $status = $approval_ac->save();
                    }
                }
            }else{
                $approval_ac = Approval::find($PRHeader->approval->id);
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
            return response()->json( ["status" => "2"] );
        }

    }

    public function reject(Request $request)
    {
        $id_PRD = $request->id;
        $project_id = $request->session()->get('project_id');
        date_default_timezone_set("Asia/Jakarta");
        $date = date("Y-m-d");
        $user = Auth::user()->id;
        $PRHeader = PurchaseRequestDetail::find($request->id);
        if ($request->type == "reject") {
            $approval_obj = Approval::where([['document_id', '=', $PRHeader->purchaserequest_id], ['document_type', '=', 'Modules\PurchaseRequest\Entities\PurchaseRequest']]);
            if ($approval_obj->first()->approval_action_id == 2) {
                DB::table("approvals")
                    ->where("id", DB::table("approvals")->select("id")
                        ->where("document_type", "Modules\PurchaseRequest\Entities\PurchaseRequestDetail")
                        ->where("document_id", $request->id)->first()->id)
                    ->update(["approval_action_id" => 7, "inactive_at" => $date, "inactive_by" => $user]);

                $PRApproval = Approval::where("document_id", $request->id)
                    ->where("document_type", "Modules\PurchaseRequest\Entities\PurchaseRequestDetail")
                    ->first();

                ApprovalHistory::where("approval_id", $PRApproval->id)
                    ->where("approval_action_id", 2)
                    ->update(["description" => $request->deskripsi_reject]);

                ApprovalHistory::where("approval_id", $PRApproval->id)
                    ->where("approval_action_id", 2)
                    ->delete();

                // CreateDocument::make_approval_history($PRApproval->id, 'Modules\PurchaseRequest\Entities\PurchaseRequestDetail', $PRApproval->document->pr->project_for_id);

                $approval_references = \Modules\Approval\Entities\ApprovalReference::where('document_type', "PurchaseRequestDetail")
                                    ->where('project_id',  $PRApproval->document->pr->project_for_id)
                                    //->where('pt_id', $pt_id )
                                    ->where('min_value', '<=', $PRApproval->total_nilai)
                                    //->where('max_value', '>=', $approval->total_nilai)
                                    ->orderBy('no_urut','ASC')
                                    ->get();
                foreach ($approval_references as $key => $each) 
                {
                    ApprovalHistory::create(['no_urut'=> $each->no_urut,
                        'user_id'=> $each->user_id,
                        'approval_action_id'=>$PRApproval->approval_action_id,
                        'approval_id'=>$PRApproval->id,
                        'document_type'=>"Modules\PurchaseRequest\Entities\PurchaseRequestDetail",
                        'document_id'=>$PRApproval->document_id,
                        'no_urut' => $each->no_urut]);
                }

                $getHeaderID = PurchaseRequestDetail::find($request->id)->purchaserequest_id;
                $getChildHeader = PurchaseRequestDetail::where('purchaserequest_id', $getHeaderID)->get();
                $arr_temp = [];
                foreach ($getChildHeader as $key => $v) {
                    # code...
                    $checkApproval = Approval::where([['document_id', '=', $v->id], ['document_type', '=', 'Modules\PurchaseRequest\Entities\PurchaseRequestDetail']])->first()->approval_action_id;
                    if ($checkApproval != 7) {
                        array_push($arr_temp, 1);
                    } else {
                        array_push($arr_temp, 0);
                    }
                }


                if (in_array(1, $arr_temp)) {
                    $approval = Approval::where([['document_id', '=', $request->pr_id], ['document_type', '=', 'Modules\PurchaseRequest\Entities\PurchaseRequest']])->update(['approval_action_id' => 2]);
                } else {
                    $approval = Approval::where([['document_id', '=', $request->pr_id], ['document_type', '=', 'Modules\PurchaseRequest\Entities\PurchaseRequest']])->update(['approval_action_id' => 7]);

                    $PRApproval = Approval::where("document_id", $request->pr_id)
                        ->where("document_type", "Modules\PurchaseRequest\Entities\PurchaseRequest")
                        ->first();

                    ApprovalHistory::where("approval_id", $PRApproval->id)
                        ->where("approval_action_id", 2)
                        ->delete();

                    // CreateDocument::make_approval_history($PRApproval->id, 'Modules\PurchaseRequest\Entities\PurchaseRequest', $PRApproval->document->project_for_id);

                    $approval_references = \Modules\Approval\Entities\ApprovalReference::where('document_type', "PurchaseRequest")
                                    ->where('project_id',  $PRApproval->document->project_for_id)
                                    //->where('pt_id', $pt_id )
                                    ->where('min_value', '<=', $PRApproval->total_nilai)
                                    //->where('max_value', '>=', $approval->total_nilai)
                                    ->orderBy('no_urut','ASC')
                                    ->get();
                    foreach ($approval_references as $key => $each) 
                    {
                        ApprovalHistory::create(['no_urut'=> $each->no_urut,
                            'user_id'=> $each->user_id,
                            'approval_action_id'=>$PRApproval->approval_action_id,
                            'approval_id'=>$PRApproval->id,
                            'document_type'=>"Modules\PurchaseRequest\Entities\PurchaseRequest",
                            'document_id'=>$PRApproval->document_id,
                            'no_urut' => $each->no_urut]);
                    }
                }
            } else if ($approval_obj->first()->approval_action_id == 12) {
                DB::table("approvals")
                    ->where("id", DB::table("approvals")->select("id")
                        ->where("document_type", "Modules\PurchaseRequest\Entities\PurchaseRequestDetail")
                        ->where("document_id", $request->id)->first()->id)
                    ->update(["approval_action_id" => 7, "inactive_at" => $date, "inactive_by" => $user]);

                $PRApproval = Approval::where("document_id", $request->id)
                    ->where("document_type", "Modules\PurchaseRequest\Entities\PurchaseRequestDetail")
                    ->first();

                ApprovalHistory::where("approval_id", $PRApproval->id)
                    ->where("approval_action_id", 2)
                    ->update(["description" => $request->deskripsi_reject]);

                ApprovalHistory::where("approval_id", $PRApproval->id)
                    ->where("approval_action_id", 2)
                    ->delete();

                // CreateDocument::make_approval_history($PRApproval->id, 'Modules\PurchaseRequest\Entities\PurchaseRequestDetail', $PRApproval->document->pr->project_for_id);

                $approval_references = \Modules\Approval\Entities\ApprovalReference::where('document_type', "PurchaseRequestDetail")
                                    ->where('project_id',  $PRApproval->document->pr->project_for_id)
                                    //->where('pt_id', $pt_id )
                                    ->where('min_value', '<=', $PRApproval->total_nilai)
                                    //->where('max_value', '>=', $approval->total_nilai)
                                    ->orderBy('no_urut','ASC')
                                    ->get();
                foreach ($approval_references as $key => $each) 
                {
                    ApprovalHistory::create(['no_urut'=> $each->no_urut,
                        'user_id'=> $each->user_id,
                        'approval_action_id'=>$PRApproval->approval_action_id,
                        'approval_id'=>$PRApproval->id,
                        'document_type'=>"Modules\PurchaseRequest\Entities\PurchaseRequestDetail",
                        'document_id'=>$PRApproval->document_id,
                        'no_urut' => $each->no_urut]);
                }

                $getHeaderID = PurchaseRequestDetail::find($request->id)->purchaserequest_id;
                $getChildHeader = PurchaseRequestDetail::where('purchaserequest_id', $getHeaderID)->get();
                $arr_temp = [];
                foreach ($getChildHeader as $key => $v) {
                    # code...
                    $checkApproval = Approval::where([['document_id', '=', $v->id], ['document_type', '=', 'Modules\PurchaseRequest\Entities\PurchaseRequestDetail']])->first()->approval_action_id;
                    if ($checkApproval != 7) {
                        array_push($arr_temp, 1);
                    } else {
                        array_push($arr_temp, 0);
                    }
                }

                if (in_array(1, $arr_temp)) {
                    $approval = Approval::where([['document_id', '=', $request->pr_id], ['document_type', '=', 'Modules\PurchaseRequest\Entities\PurchaseRequest']])->update(['approval_action_id' => 12]);
                } else {
                    $approval = Approval::where([['document_id', '=', $request->pr_id], ['document_type', '=', 'Modules\PurchaseRequest\Entities\PurchaseRequest']])->update(['approval_action_id' => 7]);

                    $PRApproval = Approval::where("document_id", $request->pr_id)
                        ->where("document_type", "Modules\PurchaseRequest\Entities\PurchaseRequest")
                        ->first();

                    ApprovalHistory::where("approval_id", $PRApproval->id)
                        ->where("approval_action_id", 12)
                        ->delete();

                    // CreateDocument::make_approval_history($PRApproval->id, 'Modules\PurchaseRequest\Entities\PurchaseRequest', $PRApproval->document->project_for_id);

                    $approval_references = \Modules\Approval\Entities\ApprovalReference::where('document_type', "PurchaseRequest")
                                    ->where('project_id',  $PRApproval->document->project_for_id)
                                    //->where('pt_id', $pt_id )
                                    ->where('min_value', '<=', $PRApproval->total_nilai)
                                    //->where('max_value', '>=', $approval->total_nilai)
                                    ->orderBy('no_urut','ASC')
                                    ->get();
                    foreach ($approval_references as $key => $each) 
                    {
                        ApprovalHistory::create(['no_urut'=> $each->no_urut,
                            'user_id'=> $each->user_id,
                            'approval_action_id'=>$PRApproval->approval_action_id,
                            'approval_id'=>$PRApproval->id,
                            'document_type'=>"Modules\PurchaseRequest\Entities\PurchaseRequest",
                            'document_id'=>$PRApproval->document_id,
                            'no_urut' => $each->no_urut]);
                    }
                }
            }

            // return $PRDApproval[0]->id;

            return redirect("/access/purchaserequest/detail/?id=" . $request->pr_id);
        } else if ($request->type == "unreject") {
            DB::table("approvals")
                ->where("id", DB::table("approvals")->select("id")
                    ->where("document_type", "Modules\PurchaseRequest\Entities\PurchaseRequestDetail")
                    ->where("document_id", $request->id)->first()->id)
                ->update(["approval_action_id" => 2, "inactive_at" => NULL, "inactive_by" => NULL]);

            $PRApproval = Approval::where("document_id", $request->id)
                ->where("document_type", "Modules\PurchaseRequest\Entities\PurchaseRequestDetail")
                ->first();

            ApprovalHistory::where("approval_id", $PRApproval->id)
                ->where("approval_action_id", 7)
                ->delete();

            // CreateDocument::make_approval_history($PRApproval->id, 'Modules\PurchaseRequest\Entities\PurchaseRequestDetail', $PRApproval->document->pr->project_for_id);

            $approval_references = \Modules\Approval\Entities\ApprovalReference::where('document_type', "PurchaseRequestDetail")
                                    ->where('project_id',  $PRApproval->document->pr->project_for_id)
                                    //->where('pt_id', $pt_id )
                                    ->where('min_value', '<=', $PRApproval->total_nilai)
                                    //->where('max_value', '>=', $approval->total_nilai)
                                    ->orderBy('no_urut','ASC')
                                    ->get();
            foreach ($approval_references as $key => $each) 
            {
                ApprovalHistory::create(['no_urut'=> $each->no_urut,
                    'user_id'=> $each->user_id,
                    'approval_action_id'=>$PRApproval->approval_action_id,
                    'approval_id'=>$PRApproval->id,
                    'document_type'=>"Modules\PurchaseRequest\Entities\PurchaseRequestDetail",
                    'document_id'=>$PRApproval->document_id,
                    'no_urut' => $each->no_urut]);
            }

            $getHeaderID = PurchaseRequestDetail::find($request->id)->purchaserequest_id;
            $getChildHeader = PurchaseRequestDetail::where('purchaserequest_id', $getHeaderID)->get();
            $arr_temp = [];
            foreach ($getChildHeader as $key => $v) {
                # code...
                $checkApproval = Approval::where([['document_id', '=', $v->id], ['document_type', '=', 'Modules\PurchaseRequest\Entities\PurchaseRequestDetail']])->first()->approval_action_id;
                if ($checkApproval == 7) {
                    array_push($arr_temp, 1);
                } else {
                    array_push($arr_temp, 0);
                }
            }



            if (in_array(1, $arr_temp)) {
                $approval = Approval::where([['document_id', '=', $request->pr_id], ['document_type', '=', 'Modules\PurchaseRequest\Entities\PurchaseRequest']])->update(['approval_action_id' => 2]);

                $PRApproval = Approval::where("document_id", $request->pr_id)
                    ->where("document_type", "Modules\PurchaseRequest\Entities\PurchaseRequest")
                    ->first();

                ApprovalHistory::where("approval_id", $PRApproval->id)
                    ->where("approval_action_id", 7)
                    ->delete();

                $approval_history = ApprovalHistory::where("approval_id", $PRApproval->id)
                    ->select("approval_action_id")
                    ->get()->last();

                if ($approval_history != NULL) { } else {

                    // CreateDocument::make_approval_history($PRApproval->id, 'Modules\PurchaseRequest\Entities\PurchaseRequest', $PRApproval->document->project_for_id);

                    $approval_references = \Modules\Approval\Entities\ApprovalReference::where('document_type', "PurchaseRequest")
                                    ->where('project_id',  $PRApproval->document->project_for_id)
                                    //->where('pt_id', $pt_id )
                                    ->where('min_value', '<=', $PRApproval->total_nilai)
                                    //->where('max_value', '>=', $approval->total_nilai)
                                    ->orderBy('no_urut','ASC')
                                    ->get();
                    foreach ($approval_references as $key => $each) 
                    {
                        ApprovalHistory::create(['no_urut'=> $each->no_urut,
                            'user_id'=> $each->user_id,
                            'approval_action_id'=>$PRApproval->approval_action_id,
                            'approval_id'=>$PRApproval->id,
                            'document_type'=>"Modules\PurchaseRequest\Entities\PurchaseRequest",
                            'document_id'=>$PRApproval->document_id,
                            'no_urut' => $each->no_urut]);
                    }
                }
            } else {
                $approval = Approval::where([['document_id', '=', $request->pr_id], ['document_type', '=', 'Modules\PurchaseRequest\Entities\PurchaseRequest']])->update(['approval_action_id' => 2]);
            }

            return redirect("/access/purchaserequest/detail/?id=" . $request->pr_id);
        }
    }

    public function detail_oe(Request $request)
    {
        $OE = TenderPurchaseRequestGroupRekanan::find($request->id);
        $results = [];
        $id = $OE->no;
        // return $id;
        $project = Project::find($request->session()->get('project_id'));
        $user = Auth::user();
        // $tender_purchase_request = TenderPurchaseRequestGroup::where('no',$id)->first();

        $itemSiapTender =   TenderPurchaseRequestGroupRekanan::select("items.kode", "tender_purchase_request_group_rekanans.no", "items.name as itemName", "brands.name as brandName", "purchaserequest_details.item_id", "purchaserequest_details.brand_id", "tender_purchase_request_group_details.id as id_group_detail", DB::raw("(sum(purchaserequest_details.quantity)) as totalqty"), "item_satuans.name as satuanName", "tender_purchase_request_groups.description")
            ->groupBy("tender_purchase_request_group_rekanans.no", "items.name", "brands.name", "item_satuans.name", "tender_purchase_request_groups.description", "purchaserequest_details.item_id", "purchaserequest_details.brand_id", "items.kode", 'tender_purchase_request_group_details.id')
            ->join("tender_purchase_request_groups", 'tender_purchase_request_group_rekanans.tender_purchase_request_group_id', 'tender_purchase_request_groups.id')
            ->join("tender_purchase_request_group_details", "tender_purchase_request_group_details.tender_purchase_request_groups_id", "tender_purchase_request_groups.id")
            ->join("purchaserequest_details", "purchaserequest_details.id", "tender_purchase_request_group_details.id_purchase_request_detail")
            ->join('purchaserequests', 'purchaserequests.id', 'purchaserequest_details.purchaserequest_id')
            ->join("brands", "brands.id", "purchaserequest_details.brand_id")
            ->join("item_satuans", "item_satuans.id", "purchaserequest_details.item_satuan_id")
            ->join("item_projects", "item_projects.id", "purchaserequest_details.item_id")
            ->join("items", "items.id", "item_projects.item_id")
            ->join("approvals", "tender_purchase_request_group_rekanans.id", "=", "approvals.document_id")
            ->where('tender_purchase_request_group_rekanans.no', $id)
            ->where('approvals.document_type', 'Modules\TenderPurchaseRequest\Entities\TenderPurchaseRequestGroupRekanan')
            ->get();

        // return $itemSiapTender;


        foreach ($itemSiapTender as $key => $value) {
            # code...
            // $objprice = is_null(ItemPrice::where('item_id',$value->item_id)->orderBy('created_at','desc')->first()) ? 0 : ItemPrice::where('item_id',$value->item_id)->orderBy('created_at','desc')->first()->price;


            $objprice = TenderPurchaseRequestGroupDetail::where("id", $value->id_group_detail)->select("harga_estimasi_oe")->first();

            $arr = [
                'tprg_no' => $value->no,
                'tprg_kode' => $value->kode,
                'tprg_itemname' => $value->itemName,
                'tprg_totalqty' => $value->totalqty,
                'tprg_brand' => $value->brandName,
                'tprg_item_id' => $value->item_id,
                'tprg_satuan' => $value->satuanName,
                'tprg_brand_id' => $value->brand_id,
                'tprg_price' => $objprice->harga_estimasi_oe,
            ];

            array_push($results, $arr);
        }

        $rekanans =   DB::table("tender_purchase_request_group_rekanans")
            ->join('tender_purchase_request_group_rekanan_details', 'tender_purchase_request_group_rekanans.id', 'tender_purchase_request_group_rekanan_details.tender_purchase_request_group_rekanan_id')
            ->join("rekanans as r1", "r1.id", "tender_purchase_request_group_rekanan_details.rekanan_id")
            ->join("approvals", "tender_purchase_request_group_rekanans.id", "=", "approvals.document_id")
            ->select("r1.name as rekanan1", "r1.id as id1", "tender_purchase_request_group_rekanan_details.id as id_detail")
            ->groupBy("r1.name", "r1.id", "tender_purchase_request_group_rekanan_details.id")
            ->where('tender_purchase_request_group_rekanans.no', $id)
            ->where('tender_purchase_request_group_rekanans.deleted_at', null)
            ->where('tender_purchase_request_group_rekanan_details.deleted_at', null)
            ->get();
        // return $rekanans;


        $tender_purchase_request_group_rekanans = TenderPurchaseRequestGroupRekanan::where('no', $id)->first();
        // return $tender_purchase_request_group_rekanans->id;
        $all_rekanans = Rekanan::all();

        $kelompok =  ApprovalHistory::where("document_type","Modules\TenderPurchaseRequest\Entities\TenderPurchaseRequestGroupRekanan")
        ->get();
        
        $revisi = "";
        $deskripsi_reject = "";
        foreach($kelompok as $key => $kunci) {
            if(($kunci->approval_action_id == 7)&&($kunci->document->kelompok->no == $tender_purchase_request_group_rekanans->kelompok->no)){
                $revisi = $kunci->document->no;
                $deskripsi_reject = $kunci->description;
            }
        }

        return view(' access::OE.detail', compact("user", "project", "results", "rekanans", 'tender_purchase_request_group_rekanans', 'all_rekanans','revisi','deskripsi_reject'));
    }

    public function approveOE(Request $request)
    {
        // return $request;
        $id = $request->oe_id;
        $user_id = $request->user_id;
        $status = $request->status;
        $tender_purchase_request_group_rekanans = TenderPurchaseRequestGroupRekanan::find($id);
        // $approval_obj = Approval::where([['document_id', '=', $id], ['document_type', '=', 'Modules\TenderPurchaseRequest\Entities\TenderPurchaseRequestGroupRekanan']]);
        // if ($approval_obj->first()->approval_action_id == 2) {
        //     $updateApproval = $approval_obj->update(['approval_action_id' => 6]);

        //     $approval = Approval::where([['document_id', '=', $id], ['document_type', '=', 'Modules\TenderPurchaseRequest\Entities\TenderPurchaseRequestGroupRekanan']])->first();

        //     ApprovalHistory::where("approval_id", $approval->id)
        //         ->where("approval_action_id", 2)
        //         ->delete();

        //     // CreateDocument::make_approval_history($approval->id, 'Modules\TenderPurchaseRequest\Entities\TenderPurchaseRequestGroupRekanan', $approval->document->project_for_id);

        //     $approval_references = \Modules\Approval\Entities\ApprovalReference::where('document_type', "OwnerEstimate")
        //                             ->where('project_id',  $approval->document->project_for_id)
        //                             //->where('pt_id', $pt_id )
        //                             ->where('min_value', '<=', $approval->total_nilai)
        //                             //->where('max_value', '>=', $approval->total_nilai)
        //                             ->orderBy('no_urut','ASC')
        //                             ->get();

        //     foreach ($approval_references as $key => $each) 
        //     {
        //         ApprovalHistory::create(['no_urut'=> $each->no_urut,
        //             'user_id'=> $each->user_id,
        //             'approval_action_id'=>$approval->approval_action_id,
        //             'approval_id'=>$approval->id,
        //             'document_type'=>"Modules\TenderPurchaseRequest\Entities\TenderPurchaseRequestGroupRekanan",
        //             'document_id'=>$approval->document_id,
        //             'no_urut' => $each->no_urut]);
        //     }
        //     if ($updateApproval) {

        //         return redirect('access/tenderpurchaserequest/oe/detail/?id=' . $tender_purchase_request_group_rekanans->id);
        //     }
        // }

        $document = $tender_purchase_request_group_rekanans->approval->histories;
        $approval_id = $document->where("user_id",$user_id)->first();
        if ( isset($approval_id->id)){
            $approval_history = ApprovalHistory::find($approval_id->id);
            $approval_history->approval_action_id = $status;
            $approval_history->description = $request->description;
            $approval_history->save();

            //$status = $approval_history->save();
            $highest = $tender_purchase_request_group_rekanans->approval->histories->min("no_urut");

            if($status != 7){

                $no_urut = $approval_history->no_urut-1;

                if($highest < $approval_history->no_urut){

                    for ($i= 0; $i != 1 ; $i) { 
                        # code...
                        $approval_history_oe = $tender_purchase_request_group_rekanans->approval->histories->where("no_urut", $no_urut)->first();
                        if($approval_history_oe != null){
                            $i = 1;
                        }
                        $no_urut = $no_urut - 1;
                    }

                    if($approval_history_oe != null){
                        $approval_history_oe->update(['approval_action_id' => 1]);
                        $project_pt = ProjectPt::where("project_id",$tender_purchase_request_group_rekanans->project->id)->first();
                        $data_oe["email"]=$approval_history_oe->user->email;
                        $data_oe["client_name"]=$approval_history_oe->user->user_name;
                        $data["subject"]='Approval OE Pengelompokan Purchase Request';
                        
                        $encript = encrypt('http://cpms.ciputragroup.com:81/access/tenderpurchaserequest/oe/detail/?id='.$tender_purchase_request_group_rekanans->id.'||'.$approval_history_oe->user->id.'||'.date('d/M/Y', strtotime('+ 7 day', strtotime(date("Y-m-d")))));

                        $link_oe = 'http://cpms.ciputragroup.com:81/access/login/?code='.$encript;
                        $title_oe = "OE Pengelompokan Purchase Request";
                
                        Mail::send('mail.bodyEmailApprove', ['link' => $link_oe, 'title' => $title_oe, 'user' => $approval_history_oe->user, 'project_pt' => $project_pt, 'name' => $tender_purchase_request_group_rekanans->name], function($message)use($data_oe) {
                            $message->from(env('MAIL_USERNAME'))->to($data_oe["email"], $data_oe["client_name"])->subject($data_oe["subject"]);
                        });
                    }
                }

                if ( $approval_history->no_urut == $highest){     
                    $approval_ac = Approval::find($tender_purchase_request_group_rekanans->approval->id);
                    $approval_ac->approval_action_id = $status;
                    $approval_ac->updated_at = date("Y-m-d H:i:s.u");
                    $status = $approval_ac->save();
                }
            }else{
                $approval_ac = Approval::find($tender_purchase_request_group_rekanans->approval->id);
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
            return response()->json( ["status" => "2"] );
        }
    }

    public function rejectOE(Request $request)
    {
        $id = $request->id;
        $tender_purchase_request_group_rekanans = TenderPurchaseRequestGroupRekanan::find($id);
        $approval_obj = Approval::where([['document_id', '=', $id], ['document_type', '=', 'Modules\TenderPurchaseRequest\Entities\TenderPurchaseRequestGroupRekanan']]);
        if ($approval_obj->first()->approval_action_id == 2) {
            $updateApproval = $approval_obj->update(['approval_action_id' => 7]);

            $approval = Approval::where([['document_id', '=', $id], ['document_type', '=', 'Modules\TenderPurchaseRequest\Entities\TenderPurchaseRequestGroupRekanan']])->first();

            ApprovalHistory::where("approval_id", $approval->id)
                ->where("approval_action_id", 2)
                ->delete();

            // CreateDocument::make_approval_history($approval->id, 'Modules\TenderPurchaseRequest\Entities\TenderPurchaseRequestGroupRekanan', $approval->document->project_for_id);

            $approval_references = \Modules\Approval\Entities\ApprovalReference::where('document_type', "OwnerEstimate")
                                    ->where('project_id',  $approval->document->project_for_id)
                                    //->where('pt_id', $pt_id )
                                    ->where('min_value', '<=', $approval->total_nilai)
                                    //->where('max_value', '>=', $approval->total_nilai)
                                    ->orderBy('no_urut','ASC')
                                    ->get();

            foreach ($approval_references as $key => $each) 
            {
                ApprovalHistory::create(['no_urut'=> $each->no_urut,
                    'user_id'=> $each->user_id,
                    'approval_action_id'=>$approval->approval_action_id,
                    'approval_id'=>$approval->id,
                    'document_type'=>"Modules\TenderPurchaseRequest\Entities\TenderPurchaseRequestGroupRekanan",
                    'document_id'=>$approval->document_id,
                    'no_urut' => $each->no_urut]);
            }

            ApprovalHistory::where("approval_id", $approval->id)
            ->where("approval_action_id", 7)
            ->update(["description" => $request->deskripsi_reject]);

            if ($updateApproval) {

                return redirect('access/tenderpurchaserequest/oe/detail/?id=' . $tender_purchase_request_group_rekanans->id);
            }
        }
    }

    public function detailPO(Request $request)
    {
        $user = Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        $PO_umum = PurchaseOrder::find($request->id);
        return view('access::PurchaseOrder.detail', compact("user", "project", "PO_umum"));
    }

    public function approvePO(Request $request)
    {
        $stat = false;
        $update = Approval::where('document_id', $request->id)->where('document_type', "Modules\TenderPurchaseRequest\Entities\PurchaseOrder")
            ->update([
                'approval_action_id' => 6
            ]);

        $approval = Approval::where([['document_id', '=', $request->id], ['document_type', '=', 'Modules\TenderPurchaseRequest\Entities\PurchaseOrder']])->first();

        ApprovalHistory::where("approval_id", $approval->id)
            ->where("approval_action_id", 2)
            ->delete();

        CreateDocument::make_approval_history($approval->id, 'Modules\TenderPurchaseRequest\Entities\PurchaseOrder', $approval->document->project_for_id);

        if ($update) {
            $stat = true;
        }

        // return response()->json($stat);
        return redirect('access/purchaseorder/detail/?id=' . $request->id);
    }

    public function rejectPO(Request $request)
    {
        $stat = false;
        $update = Approval::where('document_id', $request->id)->where('document_type', "Modules\TenderPurchaseRequest\Entities\PurchaseOrder")
            ->update([
                'approval_action_id' => 7
            ]);

        $approval = Approval::where([['document_id', '=', $request->id], ['document_type', '=', 'Modules\TenderPurchaseRequest\Entities\PurchaseOrder']])->first();

        ApprovalHistory::where("approval_id", $approval->id)
            ->where("approval_action_id", 2)
            ->update(["description" => $request->deskripsi_reject]);

        ApprovalHistory::where("approval_id", $approval->id)
            ->where("approval_action_id", 2)
            ->delete();

        CreateDocument::make_approval_history($approval->id, 'Modules\TenderPurchaseRequest\Entities\PurchaseOrder', $approval->document->project_for_id);

        if ($update) {
            $stat = true;
        }

        // return response()->json($stat);
        return redirect('access/purchaseorder/detail/?id=' . $request->id);
    }

    public function detail_penerimaan_barangPO(Request $request)
    {
        $user = Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        $PBPO = DB::table('penerimaan_barang_pos')->join('penerimaan_barang_po_details', 'penerimaan_barang_po_details.penerimaan_barang_po_id', 'penerimaan_barang_pos.id')
            ->join('purchaseorder_details', 'purchaseorder_details.id', 'penerimaan_barang_po_details.po_detail_id')
            ->join('purchaseorders', 'purchaseorders.id', 'purchaseorder_details.purchaseorder_id')
            ->join('approvals', 'approvals.document_id', 'penerimaan_barang_pos.id')
            ->join('approval_actions', 'approval_actions.id', 'approvals.approval_action_id')
            ->where('penerimaan_barang_pos.id', $request->id)
            // ->where('approvals.document_id','penerimaan_barang_pos.id')
            ->where('approvals.document_type', 'Modules\PenerimaanBarangPO\Entities\PenerimaanBarangPO')
            ->select('purchaseorders.id as PO_id', 'penerimaan_barang_pos.id as PBPO_id', 'penerimaan_barang_pos.no as PBPO_no', 'approval_actions.description as action_description', 'approvals.id as app_id', 'approvals.document_type', 'approval_actions.id as id_approval_action')
            ->distinct()
            ->get();

        $PO_umum = PurchaseOrder::where("id", $PBPO[0]->PO_id)->get();

        $PBPO2 = DB::table('penerimaan_barang_po_details')->join('penerimaan_barang_pos', 'penerimaan_barang_pos.id', 'penerimaan_barang_po_details.penerimaan_barang_po_id')
            ->join('purchaseorder_details', 'purchaseorder_details.id', 'penerimaan_barang_po_details.po_detail_id')
            ->join('purchaseorders', 'purchaseorders.id', 'purchaseorder_details.purchaseorder_id')
            ->join('approvals', 'approvals.document_id', 'penerimaan_barang_po_details.id')
            ->where('purchaseorders.id', $PO_umum[0]->id)
            ->where('approvals.approval_action_id', 6)
            ->select(DB::raw('sum(penerimaan_barang_po_details.quantity) as quantity'))
            ->distinct()
            ->get();
        
        $results = [];
        $results2 = [];
        $model = PurchaseOrderDetail::where("purchaseorder_id", $PO_umum[0]->id)
            ->join("item_projects", "item_projects.id", "purchaseorder_details.item_id")
            ->join("items", "items.id", "item_projects.item_id")
            // ->join("penerimaan_barang_po_details","penerimaan_barang_po_details.po_detail_id","purchaseorder_details.id")
            // ->join("penerimaan_barang_pos","penerimaan_barang_pos.id","penerimaan_barang_po_details.penerimaan_barang_po_id")
            ->select("items.id as item_id", "items.name as item_name", "purchaseorder_details.brand_id as brand_id", "purchaseorder_details.satuan_id as satuan_id", "purchaseorder_details.description as description", "purchaseorder_details.quantity as quantity", "purchaseorder_details.id as id", "purchaseorder_details.id as pod_id", "purchaseorder_details.item_id as item_pod_id", "purchaseorder_details.harga_satuan as harga_satuan", "purchaseorder_details.ppn as ppn", "purchaseorder_details.pph as pph", "purchaseorder_details.discon as discon", "purchaseorder_details.purchaseorder_id as po_id")->get();
        $i = 0;
        $sisa_total = [];
        $sisa_total[0] = 0;

        $totalpph = 0;
        $subtotal = 0;
        $total_disc = 0;
        $totalppn = 0;
        foreach ($model as $key => $v) {
            $diskon = $v->discon / 100 * ($v->harga_satuan * $v->quantity);
            $total_disc += $diskon;
            $sbtotal = $v->harga_satuan * $v->quantity;
            $subtotal += $sbtotal - $diskon;

            $totalppn += $v->ppn / 100 * ($sbtotal - $diskon);
            $totalpph += $v->pph / 100 * ($sbtotal - $diskon);
        }

        $arr2 = [
            'diskon' => $diskon,
            'total_disc' => $total_disc,
            'sbtotal' => $sbtotal,
            'subtotal' => $subtotal,

            'totalppn' => $totalppn,
            'totalpph' => $totalpph,
        ];
        array_push($results2, $arr2);

        foreach ($model as $key => $value) {
            $i++;
            # code...
            $qty_item = DB::table("penerimaan_barang_po_details")
                ->join('approvals', 'approvals.document_id', 'penerimaan_barang_po_details.id')
                ->where('approvals.approval_action_id', 6)
                ->where("penerimaan_barang_po_details.po_detail_id", $value->id)
                ->select(DB::raw('sum(penerimaan_barang_po_details.quantity) as quantity'))
                // ->select('penerimaan_barang_po_details.quantity as quantity')
                ->first();

            $qty_detailPenerimaan = DB::table("penerimaan_barang_po_details")
                ->where("po_detail_id", $value->id)
                ->select('quantity')
                ->get();
            // return $qty_detailPenerimaan;
            if ($qty_item <=> NULL) {
                $sisa = $value->quantity - $qty_item->quantity;
            } else {
                $sisa = $value->quantity;
            }
            $sisa_total[$i] = $sisa;

            $arr = [
                'po_id' => $value->po_id,
                'item_name' => is_null($value->item_name) ? 'kosong' : $value->item_name,
                'satuan_name' => is_null($value->satuan) ? 'kosong' : $value->satuan->name,
                'satuan_id' => $value->satuan_id,
                'item_id' => $value->item_pod_id,
                'brand_name' => is_null($value->brand) ? 'kosong' : $value->brand->name,
                'quantity' => is_null($qty_item) ? 0 : $qty_item->quantity,
                'description' => $value->description,
                'sisa_quantity' => $sisa,
                'quantity_total' => $value->quantity,
                'po_detail_id' => $value->pod_id,
                'harga_satuan' => $value->harga_satuan,
                'ppn' => $value->ppn,
                'pph' => $value->pph,
                'discon' => $value->discon,
                'quantity_diterima' => $qty_detailPenerimaan[0]->quantity
            ];

            array_push($results, $arr);
        }
        $sisa_total_item = array_sum($sisa_total);

        $jumlah_seluruh_item = DB::table('purchaseorder_details')->where("purchaseorder_id", $PO_umum[0]->id)->select(DB::raw('sum(purchaseorder_details.quantity) as quantity'))->get();

        return view('access::PenerimaanBarangPO.detail', compact("user", "project", "PO_umum", "PODetail", "results", "results2", "PBPO", "model", "sisa_total_item", "jumlah_seluruh_item", "PBPO2", "Penerimaan_barang"));
    }

    public function approve_PB_perdetail(Request $request)
    {
        $user = User::find(Auth::user()->id);
        $project_id = $request->session()->get('project_id');
        $melacak_PR = penerimaanbarangPO::join("penerimaan_barang_po_details", "penerimaan_barang_po_details.penerimaan_barang_po_id", "penerimaan_barang_pos.id")
            ->join("purchaseorder_details", "purchaseorder_details.id", "penerimaan_barang_po_details.po_detail_id")
            ->join("purchaseorders", "purchaseorders.id", "purchaseorder_details.purchaseorder_id")
            ->join("tender_menang_pr", "tender_menang_pr.id", "purchaseorders.id_tender_menang")
            ->join("tender_purchase_request_group_rekanans", "tender_purchase_request_group_rekanans.id", "tender_menang_pr.tender_purchase_group_rekanan_id")
            ->join("tender_purchase_request_groups", "tender_purchase_request_groups.id", "tender_purchase_request_group_rekanans.tender_purchase_request_group_id")
            ->join("tender_purchase_request_group_details", "tender_purchase_request_group_details.tender_purchase_request_groups_id", "tender_purchase_request_groups.id")
            ->join("purchaserequest_details", "purchaserequest_details.id", "tender_purchase_request_group_details.id_purchase_request_detail")
            ->join("purchaserequests", "purchaserequests.id", "purchaserequest_details.purchaserequest_id")
            // ->join("departments","departments.id","purchaserequests.department_id")
            // ->join("item_satuans","item_satuans.id","purchaserequest_details.item_satuan_id")
            // ->join("item_projects","item_projects.id","penerimaan_barang_po_details.item_id")
            // ->join("items","items.id","item_projects.item_id")
            ->join("users", "users.id", "purchaserequests.created_by")
            ->where("penerimaan_barang_pos.id", $request->id)
            ->select("purchaserequests.id as id_pr", "users.id as id_user", "users.user_name as pembuat", "users.email as email", "purchaserequests.no as no_pr", "penerimaan_barang_pos.id as pb_id")
            ->distinct()
            ->get();

        foreach ($melacak_PR as $key => $value) {
            # code...
            $lacak = [];
            // $PR_Detail = PurchaseRequestDetail::where("purchaserequest_id",$value->id_pr)->get();
            // return $PR_Detail;
            $melacak_detail_PR = penerimaanbarangPO::join("penerimaan_barang_po_details", "penerimaan_barang_po_details.penerimaan_barang_po_id", "penerimaan_barang_pos.id")
                ->join("purchaseorder_details", "purchaseorder_details.id", "penerimaan_barang_po_details.po_detail_id")
                ->join("purchaseorders", "purchaseorders.id", "purchaseorder_details.purchaseorder_id")
                ->join("tender_menang_pr", "tender_menang_pr.id", "purchaseorders.id_tender_menang")
                ->join("tender_purchase_request_group_rekanans", "tender_purchase_request_group_rekanans.id", "tender_menang_pr.tender_purchase_group_rekanan_id")
                ->join("tender_purchase_request_groups", "tender_purchase_request_groups.id", "tender_purchase_request_group_rekanans.tender_purchase_request_group_id")
                ->join("tender_purchase_request_group_details", "tender_purchase_request_group_details.tender_purchase_request_groups_id", "tender_purchase_request_groups.id")
                ->join("purchaserequest_details", "purchaserequest_details.id", "tender_purchase_request_group_details.id_purchase_request_detail")
                ->join("purchaserequests", "purchaserequests.id", "purchaserequest_details.purchaserequest_id")
                ->join("departments", "departments.id", "purchaserequests.department_id")
                ->join("item_satuans", "item_satuans.id", "purchaserequest_details.item_satuan_id")
                ->join("item_projects", "item_projects.id", "penerimaan_barang_po_details.item_id")
                ->join("items", "items.id", "item_projects.item_id")
                ->join("users", "users.id", "purchaserequests.created_by")
                ->where("penerimaan_barang_pos.id", $request->id)
                ->where("purchaserequests.id", $value->id_pr)
                ->select("purchaserequests.id as id_pr", "purchaserequests.no as no_pr", "users.user_name as pembuat", "users.email as email", "penerimaan_barang_pos.id as pb_id", "penerimaan_barang_po_details.item_id as id_item_diterima", "items.name as name_item_diterima", "penerimaan_barang_po_details.quantity as quantity_diterima")
                ->distinct()
                ->get();

            Mail::to($value->email)->send(new EmailPr($value));
        }

        // return $melacak_PR;
        $approval_obj = Approval::where([['document_id', '=', $request->id], ['document_type', '=', 'Modules\PenerimaanBarangPO\Entities\PenerimaanBarangPO']]);

        if ($approval_obj->first()->approval_action_id == 2) {
            $update = DB::table("approvals")->where('document_id', $request->id)->where('document_type', "Modules\PenerimaanBarangPO\Entities\PenerimaanBarangPO")
                ->update(['approval_action_id' => 6]);

            $approval = Approval::where([['document_id', '=', $request->id], ['document_type', '=', 'Modules\PenerimaanBarangPO\Entities\PenerimaanBarangPO']])->first();

            ApprovalHistory::where("approval_id", $approval->id)
                ->where("approval_action_id", 2)
                ->delete();

            // CreateDocument::make_approval_history($approval->id, 'Modules\PenerimaanBarangPO\Entities\PenerimaanBarangPO', $approval->document->project_for_id);

            $approval_references = \Modules\Approval\Entities\ApprovalReference::where('document_type', "PenerimaanBarangPO")
                                    ->where('project_id', $project_id)
                                    //->where('pt_id', $pt_id )
                                    ->where('min_value', '<=',  $approval->total_nilai)
                                    //->where('max_value', '>=', $approval->total_nilai)
                                    ->orderBy('no_urut','ASC')
                                    ->get();

            foreach ($approval_references as $key => $each) 
            {
                ApprovalHistory::create(['no_urut'=> $each->no_urut,
                    'user_id'=> $each->user_id,
                    'approval_action_id'=> $approval->approval_action_id,
                    'approval_id'=> $approval->id,
                    'document_type'=>"Modules\PenerimaanBarangPO\Entities\PenerimaanBarangPO",
                    'document_id'=> $approval->document_id,
                    'no_urut' => $each->no_urut]);
            }

            $simpan_inventories = DB::table('penerimaan_barang_po_details')
                ->join('penerimaan_barang_pos', 'penerimaan_barang_pos.id', 'penerimaan_barang_po_details.penerimaan_barang_po_id')
                ->join('purchaseorder_details', 'purchaseorder_details.id', 'penerimaan_barang_po_details.po_detail_id')
                ->join('purchaseorders', 'purchaseorders.id', 'purchaseorder_details.purchaseorder_id')
                ->join('warehouses', 'warehouses.id', 'penerimaan_barang_po_details.gudang_id')
                ->where('penerimaan_barang_po_id', $request->id)
                ->select('penerimaan_barang_po_details.item_id as item_id', 'purchaseorders.rekanan_id as rekanan_id', 'penerimaan_barang_po_details.gudang_id as gudang_id', 'penerimaan_barang_po_details.id as sumber_id', 'penerimaan_barang_pos.date as date', 'penerimaan_barang_po_details.quantity as quantity', 'penerimaan_barang_po_details.satuan_id as satuan_id', 'purchaseorder_details.harga_satuan as harga_satuan', 'purchaseorder_details.ppn as ppn', 'penerimaan_barang_po_details.description as description')
                ->distinct()
                ->get();

            // $simpan_inventories = PenerimaanBarangPODetail::where('penerimaan_barang_po_id',$request->id)
            //                      ->get();

            foreach ($simpan_inventories as $key => $value) {
                $approval_PenerimaanBarangDetail = Approval::where([['document_id', '=', $value->sumber_id], ['document_type', '=', 'Modules\PenerimaanBarangPO\Entities\PenerimaanBarangPODetail']])->update(['approval_action_id' => 6]);

                $approval_detail = Approval::where([['document_id', '=', $value->sumber_id], ['document_type', '=', 'Modules\PenerimaanBarangPO\Entities\PenerimaanBarangPODetail']])->first();

                ApprovalHistory::where("approval_id", $approval->id)
                    ->where("approval_action_id", 2)
                    ->delete();

                // CreateDocument::make_approval_history($approval->id, 'Modules\PenerimaanBarangPO\Entities\PenerimaanBarangPODetail', $approval->document->pbo->project_for_id);

                $approval_references = \Modules\Approval\Entities\ApprovalReference::where('document_type', "PenerimaanBarangPODetail")
                        ->where('project_id', $project_id)
                        //->where('pt_id', $pt_id )
                        ->where('min_value', '<=',   $approval_detail->total_nilai)
                        //->where('max_value', '>=', $approval->total_nilai)
                        ->orderBy('no_urut','ASC')
                        ->get();

                foreach ($approval_references as $key => $each) 
                {
                    ApprovalHistory::create(['no_urut'=> $each->no_urut,
                        'user_id'=> $each->user_id,
                        'approval_action_id'=>  $approval_detail->approval_action_id,
                        'approval_id'=>  $approval_detail->id,
                        'document_type'=>"Modules\PenerimaanBarangPO\Entities\PenerimaanBarangPODetail",
                        'document_id'=>  $approval_detail->document_id,
                        'no_urut' => $each->no_urut]);
                }

                $inventory = new Inventory;
                $inventory->item_id = $value->item_id;
                $inventory->rekanan_id = $value->rekanan_id;
                $inventory->warehouse_id = $value->gudang_id;
                $inventory->sumber_id = $value->sumber_id;
                $inventory->sumber_type = "Modules\PenerimaanBarangPO\Entities\PenerimaanBarangPODetail";
                $inventory->date = $value->date;
                $inventory->quantity = $value->quantity;
                $inventory->item_satuan_id = $value->satuan_id;
                $inventory->quantity_terpakai = NULL;
                $inventory->price = $value->harga_satuan;
                $inventory->ppn = $value->ppn;
                $inventory->description = $value->description;
                $status = $inventory->save();
            }
        }


        return redirect('access/penerimaanbarangPO/detail/?id=' . $request->id);
    }

    public function reject_PB_perdetail(Request $request)
    {

        $approval_obj = Approval::where([['document_id', '=', $request->id], ['document_type', '=', 'Modules\PenerimaanBarangPO\Entities\PenerimaanBarangPO']]);

        if ($approval_obj->first()->approval_action_id == 2) {
            $update = Approval::where('document_id', $request->id)->where('document_type', "Modules\PenerimaanBarangPO\Entities\PenerimaanBarangPO")
                ->update(['approval_action_id' => 7]);

            $approval = Approval::where('document_id', $request->id)->where('document_type', "Modules\PenerimaanBarangPO\Entities\PenerimaanBarangPO")->first();

            ApprovalHistory::where("approval_id", $approval->id)
                ->where("approval_action_id", 2)
                ->delete();

            // CreateDocument::make_approval_history($approval->id, 'Modules\PenerimaanBarangPO\Entities\PenerimaanBarangPO', $approval->document->project_for_id);

            $approval_references = \Modules\Approval\Entities\ApprovalReference::where('document_type', "PenerimaanBarangPO")
                                    ->where('project_id', $project_id)
                                    //->where('pt_id', $pt_id )
                                    ->where('min_value', '<=',  $approval->total_nilai)
                                    //->where('max_value', '>=', $approval->total_nilai)
                                    ->orderBy('no_urut','ASC')
                                    ->get();

            foreach ($approval_references as $key => $each) 
            {
                ApprovalHistory::create(['no_urut'=> $each->no_urut,
                    'user_id'=> $each->user_id,
                    'approval_action_id'=> $approval->approval_action_id,
                    'approval_id'=> $approval->id,
                    'document_type'=>"Modules\PenerimaanBarangPO\Entities\PenerimaanBarangPO",
                    'document_id'=> $approval->document_id,
                    'no_urut' => $each->no_urut]);
            }

            $simpan_inventories = PenerimaanBarangPODetail::where('penerimaan_barang_po_id', $request->id)
                ->get();

            foreach ($simpan_inventories as $key => $value) {
                $approval_PenerimaanBarangDetail = Approval::where([['document_id', '=', $value->id], ['document_type', '=', 'Modules\PenerimaanBarangPO\Entities\PenerimaanBarangPODetail']])->update(['approval_action_id' => 7]);

                $approval_detail = Approval::where([['document_id', '=', $value->id], ['document_type', '=', 'Modules\PenerimaanBarangPO\Entities\PenerimaanBarangPODetail']])->first();

                ApprovalHistory::where("approval_id", $approval_detail->id)
                    ->where("approval_action_id", 2)
                    ->delete();

                // CreateDocument::make_approval_history($approval_detail->id, 'Modules\PenerimaanBarangPO\Entities\PenerimaanBarangPODetail', $approval_detail->document->pbo->project_for_id);

                $approval_references = \Modules\Approval\Entities\ApprovalReference::where('document_type', "PenerimaanBarangPODetail")
                        ->where('project_id', $project_id)
                        //->where('pt_id', $pt_id )
                        ->where('min_value', '<=',   $approval_detail->total_nilai)
                        //->where('max_value', '>=', $approval->total_nilai)
                        ->orderBy('no_urut','ASC')
                        ->get();

                foreach ($approval_references as $key => $each) 
                {
                    ApprovalHistory::create(['no_urut'=> $each->no_urut,
                        'user_id'=> $each->user_id,
                        'approval_action_id'=>  $approval_detail->approval_action_id,
                        'approval_id'=>  $approval_detail->id,
                        'document_type'=>"Modules\PenerimaanBarangPO\Entities\PenerimaanBarangPODetail",
                        'document_id'=>  $approval_detail->document_id,
                        'no_urut' => $each->no_urut]);
                }
            }
        }


        return redirect('access/penerimaanbarangPO/detail/?id=' . $request->id);
    }

    public function detail_tender_penawaran(Request $request)
    {
        $penawaran = TenderPurchaseRequestPenawaran::where('id', $request->id)->first();

        // return $penawaran->approval;
        $user = Auth::user();
        $project_id = $penawaran->project_for_id;
        $project = Project::find($project_id);
        $all_data = [];
        $data_rekanan = [];
        $rekanan = [];


        $id = $penawaran->tender_purchase_request_group_rekanan->no;
        $penawaran = $penawaran->penawaran;

        $getDataTender = TenderPurchaseRequestGroupRekanan::where('no', $id)->first();

        // $details_tawar = TenderPurchaseRequestPenawaran::select(
        //     'r.name as rekanan_name',
        //     'ims.name as item_name',
        //     'tprps.volume',
        //     'isn.name as satuan_name',
        //     DB::raw("((select price from item_prices where item_prices.item_id = ips.id order by item_prices.created_at desc limit 1)) as oe_price"),
        //     'tprps.nilai',
        //     'r.ppn',
        //     'rg.pph_percent'
        // )
        //     ->join('tender_purchase_request_penawarans_details as tprps', 'tender_purchase_request_penawarans.id', 'tprps.tender_penawaran_id')
        //     ->join('tender_purchase_request_group_rekanan_details as tprgrd', 'tender_purchase_request_penawarans.rekanan_id', 'tprgrd.id')
        //     ->join('rekanans as r', 'tprgrd.rekanan_id', 'r.id')
        //     ->join('rekanan_groups as rg', 'r.rekanan_group_id', 'rg.id')
        //     ->join('items as ims', 'tprps.item_id', 'ims.id')
        //     ->join('item_satuans as isn', 'isn.id', 'tprps.item_satuan_id')
        //     ->join('item_projects as ips', 'ips.item_id', 'ims.id')
        //     ->where('tender_purchase_request_penawarans.id_tender_purchase_request_group_rekanan', $getDataTender->id)
        //     ->where('ips.project_id', $project_id)
        //     ->where('tender_purchase_request_penawarans.penawaran', $penawaran)
        //     ->distinct()
        //     ->get();

        $group_item = TenderPurchaseRequestGroupRekanan::select(
            "items.id as item_id",
            "items.name as item_name",
            DB::raw("(sum(purchaserequest_details.quantity)) as volume"),
            "item_satuans.name as satuan_name",
            "item_satuans.id as satuan_id"
        )
            ->join('tender_purchase_request_groups', 'tender_purchase_request_group_rekanans.tender_purchase_request_group_id', 'tender_purchase_request_groups.id')
            ->join("tender_purchase_request_group_details", "tender_purchase_request_group_details.tender_purchase_request_groups_id", "tender_purchase_request_groups.id")
            ->join("purchaserequest_details", "purchaserequest_details.id", "tender_purchase_request_group_details.id_purchase_request_detail")
            ->join("item_satuans", "item_satuans.id", "purchaserequest_details.item_satuan_id")
            ->join("item_projects", "item_projects.id", "purchaserequest_details.item_id")
            ->join("items", "items.id", "item_projects.item_id")
            ->where('tender_purchase_request_group_rekanans.id', $getDataTender->id)
            ->where('item_projects.project_id', $project_id)
            ->groupBy("items.id", "items.name", "item_satuans.name", "item_satuans.id")
            ->get();

        /*$group_item = TenderPurchaseRequestPenawaran::select('br.id as brand_id'
            ,'br.name as brand_name','tprps.item_id','ims.name as item_name',
            'tprps.volume',
            'isn.name as satuan_name')
        ->join('tender_purchase_request_penawarans_details as tprps','tender_purchase_request_penawarans.id','tprps.tender_penawaran_id')
        ->join('items as ims','tprps.item_id','ims.id')
        ->join('item_satuans as isn','isn.id','tprps.item_satuan_id')
        ->join('item_projects as ips','ips.item_id','ims.id')
        ->join('brands as br','br.id','tprps.brand_id')
        ->where('tender_purchase_request_penawarans.id_tender_purchase_request_group_rekanan',$getDataTender->id)
        ->where('ips.project_id',$project_id)
        ->distinct()
        ->get();*/

        $get_data_penawaran = TenderPurchaseRequestPenawaran::select('id')->where('tender_purchase_request_penawarans.id_tender_purchase_request_group_rekanan', $getDataTender->id)->where('tender_purchase_request_penawarans.penawaran', $penawaran)->get();

        $arr_data_penawaran = [];
        foreach ($get_data_penawaran as $key => $value) {
            # code...
            array_push($arr_data_penawaran, $value->id);
        }
        array_splice($arr_data_penawaran, 0, 0, "oe");

        $join_get_data_penawaran = array_merge($arr_data_penawaran, $arr_data_penawaran);
        $join_get_data_penawaran = array_merge($join_get_data_penawaran, $arr_data_penawaran);

        $result = [];
        $result_total = [];
        $total_oe = 0;
        $total_per_supplier = 0;
        foreach ($group_item as $key => $value) {
            # code...
            $arr_satuan_price = [];

            foreach ($join_get_data_penawaran as $k => $v) {
                # code...
                if ($v == 'oe') {
                    if ($k == 0 || $k == count($get_data_penawaran) + 1 || $k == (count($get_data_penawaran) + 1) * 2) {
                        // $oe_price = TenderPurchaseRequestPenawaran::select(DB::raw("((select price from item_prices where item_prices.item_id = ips.id order by item_prices.created_at desc limit 1)) as oe_price"))
                        //     ->join('tender_purchase_request_penawarans_details as tprps', 'tender_purchase_request_penawarans.id', 'tprps.tender_penawaran_id')
                        //     ->join('item_projects as ips', 'ips.item_id', 'tprps.item_id')
                        //     ->where('tender_purchase_request_penawarans.id_tender_purchase_request_group_rekanan', $getDataTender->id)
                        //     ->where('ips.project_id', $project_id)
                        //     ->where('tprps.item_id', $value->item_id)
                        //     ->where('tender_purchase_request_penawarans.penawaran', $penawaran)
                        //     ->first();


                        $oe_price = TenderPurchaseRequestPenawaran::where("tender_purchase_request_penawarans.project_for_id", $project_id)
                            ->join('tender_purchase_request_penawarans_details as tprps', 'tender_purchase_request_penawarans.id', 'tprps.tender_penawaran_id')
                            ->where('tprps.item_id', $value->item_id)
                            ->where('purchaserequest_details.brand_id', $value->brand_id)
                            ->join("tender_purchase_request_group_rekanans", "tender_purchase_request_group_rekanans.id", "tender_purchase_request_penawarans.id_tender_purchase_request_group_rekanan")
                            ->join("tender_purchase_request_groups", "tender_purchase_request_groups.id", "tender_purchase_request_group_rekanans.tender_purchase_request_group_id")
                            ->join("tender_purchase_request_group_details", "tender_purchase_request_group_details.tender_purchase_request_groups_id", "tender_purchase_request_groups.id")
                            ->join("purchaserequest_details", "purchaserequest_details.id", "tender_purchase_request_group_details.id_purchase_request_detail")
                            ->select("tender_purchase_request_group_details.id as id_group_detail", "purchaserequest_details.id as id_prd", "tender_purchase_request_group_details.harga_estimasi_oe as oe_price")
                            ->distinct()
                            ->first();

                        if ($oe_price == null) {
                            $oe_price = 0;
                        } else {
                            $oe_price = $oe_price->oe_price;
                            if ($k == count($get_data_penawaran) + 1) {
                                $oe_price = $oe_price * $value->volume;
                                $total_oe += $oe_price;
                            }
                        }

                        if ($k == (count($get_data_penawaran) + 1) * 2) {

                            $brand_oe = TenderPurchaseRequestGroupRekanan::select('br.name as brand_name')
                                ->join('tender_purchase_request_groups', 'tender_purchase_request_group_rekanans.tender_purchase_request_group_id', 'tender_purchase_request_groups.id')
                                ->join("tender_purchase_request_group_details", "tender_purchase_request_group_details.tender_purchase_request_groups_id", "tender_purchase_request_groups.id")
                                ->join("purchaserequest_details", "purchaserequest_details.id", "tender_purchase_request_group_details.id_purchase_request_detail")
                                ->join("item_projects", "item_projects.id", "purchaserequest_details.item_id")
                                ->join("items", "items.id", "item_projects.item_id")
                                ->join('brands as br', 'br.id', 'purchaserequest_details.brand_id')
                                ->where('tender_purchase_request_group_rekanans.id', $getDataTender->id)
                                ->where('item_projects.project_id', $project_id)
                                ->where('items.id', $value->item_id)
                                ->first();

                            if ($brand_oe == null) {
                                $brand_oe = 'kosong';
                            } else {
                                $brand_oe = $brand_oe->brand_name;
                            }

                            $oe_price = $brand_oe;
                        }


                        array_push($arr_satuan_price, $oe_price);
                    }
                } else {
                    $satuan_price = TenderPurchaseRequestPenawaranDetail::select('nilai')
                        ->where([['tender_penawaran_id', '=', $v], ['item_id', '=', $value->item_id]])
                        ->first();

                    if ($satuan_price == null) {
                        $satuan_price = 0;
                    } else {
                        $satuan_price = $satuan_price->nilai;
                        if ($k > count($get_data_penawaran)) {
                            $satuan_price = $satuan_price * $value->volume;
                            $total_per_supplier += $satuan_price;
                        }

                        if ($k > (count($get_data_penawaran) + 1) * 2) {
                            $brand_rekanan = TenderPurchaseRequestPenawaran::select('br.name as brand_name')
                                ->join('tender_purchase_request_penawarans_details', 'tender_purchase_request_penawarans.id', 'tender_purchase_request_penawarans_details.tender_penawaran_id')
                                ->join('brands as br', 'br.id', 'tender_purchase_request_penawarans_details.brand_id')
                                ->where('tender_purchase_request_penawarans.id_tender_purchase_request_group_rekanan', $getDataTender->id)
                                ->where('tender_purchase_request_penawarans_details.item_id', $value->item_id)
                                ->where('tender_purchase_request_penawarans_details.tender_penawaran_id', $v)
                                ->where('tender_purchase_request_penawarans.penawaran', $penawaran)
                                ->first();

                            if ($brand_rekanan == null) {
                                $brand_rekanan = 'kosong';
                            } else {
                                $brand_rekanan = $brand_rekanan->brand_name;
                            }

                            $satuan_price = $brand_rekanan;
                        }
                    }

                    array_push($arr_satuan_price, $satuan_price);
                }
            }

            $arr = [
                'item_name' => $value->item_name,
                'volume' => $value->volume,
                'satuan_name' => $value->satuan_name,
                'satuan_price' => $arr_satuan_price,
            ];

            $result[] = $arr;
        }

        $checkStatus = [];
        $getDataPenawarans = TenderPurchaseRequestPenawaran::where('id_tender_purchase_request_group_rekanan', $getDataTender->id)->where('tender_purchase_request_penawarans.penawaran', $penawaran)->get();
        foreach ($getDataPenawarans as $key => $value) {
            # code...
            $status_approval = Approval::where([['document_id', '=', $value->id], ['document_type', '=', 'Modules\TenderPurchaseRequest\Entities\TenderPurchaseRequestPenawaran']])->first();

            if ($status_approval->approval_action_id == 2) {
                array_push($checkStatus, 0);
            }
            if ($status_approval->approval_action_id == 6) {
                $check_pemenang_tender = TenderMenangPR::where('id_penawaran', $value->id)->first();
                if ($check_pemenang_tender != null) {
                    $pemenang_tender = $check_pemenang_tender->tender_purchase_request_group_rekanan_detail->rekanan->name;
                    array_push($checkStatus, 1);
                }
            }


            $rekanan_name = $value->tender_purchase_request_group_rekanan_detail->rekanan->name;
            $ppn = $value->tender_purchase_request_group_rekanan_detail->rekanan->ppn;
            $data_rekanan[] = $rekanan_name . "-" . $value->rekanan_id . '-' . $ppn;
            $rekanan[] = $value->tender_purchase_request_group_rekanan_detail->rekanan->id;
            foreach ($value->details as $kunci => $each) {
                $arr = [
                    $rekanan_name => number_format($each->nilai, 2, ".", ",")
                ];
                $all_data[$each->item->name . '-' . $each->brand->name . '-' . $each->volume . '-' . $each->satuan->name][] = $arr;
            }
        }

        // var_dump($getDataPenawarans);
        // foreach($rekanan as $key){
        //     $biodata_vendor = Rekanan::find($key)->get();
        // }

        // return $getDataPenawarans[0]->tender_purchase_request_group_rekanan_detail->rekanan->surat;
        array_splice($data_rekanan, 0, 0, "OE-0-10");

        $join_data_rekanan = array_merge($data_rekanan, $data_rekanan);
        $join_data_rekanan = array_merge($join_data_rekanan, $data_rekanan);

        $checkPemenang = TenderMenangPR::where('tender_purchase_group_rekanan_id', $getDataTender->id)->first();

        $approval_history = ApprovalHistory::where('document_type', 'Modules\TenderPurchaseRequest\Entities\TenderPurchaseRequestPenawaran')->where('document_id', $request->id)->orderBy("id", "desc")->first();

        $tenderPembayaran = TenderPurchaseRequestPenawaran::rightJoin('metode_pembayarans', 'metode_pembayarans.id', 'tender_purchase_request_penawarans.id_metode_pembayaran')->where('penawaran', $penawaran)->where('id_tender_purchase_request_group_rekanan', $getDataTender->id)->select('tender_purchase_request_penawarans.id as id_penawaran', 'tender_purchase_request_penawarans.rekanan_id', 'tender_purchase_request_penawarans.id_metode_pembayaran', 'metode_pembayarans.name as name_pembayaran', 'tender_purchase_request_penawarans.lama_cicilan as lama_cicilan', 'tender_purchase_request_penawarans.DP')->get();

        // var_dump($approval_history);
        // return $approval_history->action->description;
        $penawaran_data = TenderPurchaseRequestPenawaran::where('id', $request->id)->first();

        return view('access::TenderPenawaran.detail', compact('result', 'data_rekanan', 'join_data_rekanan', 'user', 'project', 'getDataTender', 'checkStatus', 'checkPemenang', 'getDataPenawarans', 'approval_history', 'tenderPembayaran', 'penawaran_data'));
    }

    public function approve_tender_penawaran(Request $request)
    {
        // return $request;
        date_default_timezone_set("Asia/Jakarta");
        $tender_menang = TenderMenangPR::find($request->tmenang_id);
        $id = $tender_menang->id_penawaran;
        // $tender_purchase_request_group_rekanans = TenderPurchaseRequestGroupRekanan::find($id);


        // $approval_obj = Approval::where([['document_id', '=', $id], ['document_type', '=', 'Modules\TenderPurchaseRequest\Entities\TenderPurchaseRequestPenawaran']]);
        // if ($approval_obj->first()->approval_action_id == 2) {
        //     $updateApproval = $approval_obj->update(['approval_action_id' => 6]);

        //     $approval = Approval::where([['document_id', '=', $id], ['document_type', '=', 'Modules\TenderPurchaseRequest\Entities\TenderPurchaseRequestPenawaran']])->first();

        //     ApprovalHistory::where("approval_id", $approval->id)
        //         ->where("approval_action_id", 2)
        //         ->delete();

        //     // CreateDocument::make_approval_history($approval->id, 'Modules\TenderPurchaseRequest\Entities\TenderPurchaseRequestPenawaran', $approval->document->project_for_id);

        //     $approval_references = \Modules\Approval\Entities\ApprovalReference::where('document_type', "TenderMenangPR")
        //                             ->where('project_id',  $approval->document->project_for_id)
        //                             //->where('pt_id', $pt_id )
        //                             ->where('min_value', '<=', $approval->total_nilai)
        //                             //->where('max_value', '>=', $approval->total_nilai)
        //                             ->orderBy('no_urut','ASC')
        //                             ->get();

        //     foreach ($approval_references as $key => $each) 
        //     {
        //         ApprovalHistory::create(['no_urut'=> $each->no_urut,
        //             'user_id'=> $each->user_id,
        //             'approval_action_id'=>$approval->approval_action_id,
        //             'approval_id'=>$approval->id,
        //             'document_type'=>"Modules\TenderPurchaseRequest\Entities\TenderPurchaseRequestPenawaran",
        //             'document_id'=>$approval->document_id,
        //             'no_urut' => $each->no_urut]);
        //     }

        //     $user_id = Auth::user()->id;
        //     $rekanan_id_master = TenderPurchaseRequestGroupRekananDetails::find($tender_menang->rekanan_id)->rekanan_id;
        //     $allitems = TenderPurchaseRequestPenawaranDetail::where('tender_penawaran_id', $id)->get();

        //     $PO = new PurchaseOrder;
        //     $PO->id_tender_menang = $tender_menang->id;
        //     $PO->project_for_id = $approval->document->project_for_id;
        //     $PO->no             = CreateDocument::createDocumentNumber('PO', 2, $approval->document->project_for_id, $user_id);
        //     $PO->rekanan_id     = $rekanan_id_master;
        //     $execute = $PO->save();

        //     $total = 0;
        //     if ($execute) {
        //         $approval = new Approval;
        //         $approval->approval_action_id   = 1;
        //         $approval->document_id          = $PO->id;
        //         $approval->document_type        = "Modules\TenderPurchaseRequest\Entities\PurchaseOrder";
        //         $approval->total_nilai          = 0;
        //         $createApproval = $approval->save();
        //         if ($createApproval) {
        //             if (count($allitems) > 0) {
        //                 for ($count = 0; $count < count($allitems); $count++) {
        //                     $check_is_item_project = ItemProject::where([['item_id', '=', $allitems[$count]->item_id], ['project_id', '=', $approval->document->project_for_id]])->first();
        //                     if ($check_is_item_project != null) {
        //                         $POD = new PurchaseOrderDetail;
        //                         $POD->purchaseorder_id  = $PO->id;  // nanti
        //                         $POD->item_id           = $check_is_item_project->id;
        //                         $POD->brand_id          = (int)$allitems[$count]->brand_id;
        //                         $POD->quantity          = (int)$allitems[$count]->volume;
        //                         $POD->satuan_id         = (int)$allitems[$count]->item_satuan_id;
        //                         $POD->harga_satuan      = (int)$allitems[$count]->nilai;
        //                         $POD->ppn               = (int)$allitems[$count]->ppn;
        //                         $POD->description       = $allitems[$count]->description;
        //                         $POD->save();
                                
        //                         $Price              = new ItemPrice;
        //                         $Price->item_id     = (int)$check_is_item_project->id;
        //                         $Price->item_satuan_id  = (int)$allitems[$count]->item_satuan_id;
        //                         $Price->price       = (int)$allitems[$count]->nilai;
        //                         $Price->project_id  = (int)$approval->document->project_for_id;
        //                         $Price->ppn         = (int)$allitems[$count]->ppn;
        //                         $Price->date_price  = date("Y-m-d", strtotime(date("y-m-d")));
        //                         $Price->save();

        //                     } else {
        //                         $default_warehouse_id = Warehouse::where('project_id', $approval->document->project_for_id)->first()->id;
        //                         $createItemProject = ItemProject::create([
        //                             'item_id' => $allitems[$count]->item_id,
        //                             'project_id' => $approval->document->project_for_id,
        //                             'default_warehouse_id' => $default_warehouse_id
        //                         ]);

        //                         if ($createItemProject) {
        //                             $POD = new PurchaseOrderDetail;
        //                             $POD->purchaseorder_id  = $PO->id;  // nanti
        //                             $POD->item_id           = $createItemProject->id;
        //                             $POD->brand_id          = $allitems[$count]->brand_id;
        //                             $POD->quantity          = $allitems[$count]->volume;
        //                             $POD->satuan_id         = $allitems[$count]->item_satuan_id;
        //                             $POD->harga_satuan      = $allitems[$count]->nilai;
        //                             $POD->ppn               = $allitems[$count]->ppn;
        //                             $POD->save();
        //                         }
        //                     }
        //                     $total += ((int)$allitems[$count]->nilai * (int)$allitems[$count]->volume);
        //                 }
        //             }
        //         }

        //         // Approval::where('document_id', $PO->id)->where('document_type', "Modules\TenderPurchaseRequest\Entities\PurchaseOrder")->update(['approval_action_id' => 6]);

        //         $update = Approval::where('document_id', $PO->id)->where('document_type', "Modules\TenderPurchaseRequest\Entities\PurchaseOrder")->update(["total_nilai " => (int)$total,'approval_action_id' => 6]);

        //         $approval = Approval::where([['document_id', '=', $PO->id], ['document_type', '=', 'Modules\TenderPurchaseRequest\Entities\PurchaseOrder']])->first();

        //         ApprovalHistory::where("approval_id",$approval->id)
        //                                      ->where("approval_action_id",2)
        //                                      ->delete();

        //         // CreateDocument::make_approval_history($approval->id,'Modules\TenderPurchaseRequest\Entities\PurchaseOrder',$project_id);

        //          $approval_references = \Modules\Approval\Entities\ApprovalReference::where('document_type', "PurchaseOrder")
        //                                 ->where('project_id', $approval->document->project_for_id)
        //                                 //->where('pt_id', $pt_id )
        //                                 ->where('min_value', '<=', $approval->total_nilai)
        //                                 //->where('max_value', '>=', $approval->total_nilai)
        //                                 ->orderBy('no_urut','ASC')
        //                                 ->get();

        //         foreach ($approval_references as $key => $each) {
        //             ApprovalHistory::create(['no_urut'=> $each->no_urut,
        //                 'user_id'=> $each->user_id,
        //                 'approval_action_id'=>$approval->approval_action_id,
        //                 'approval_id'=>$approval->id,
        //                 'document_type'=>"Modules\TenderPurchaseRequest\Entities\PurchaseOrder",
        //                 'document_id'=>$approval->document_id,
        //                 'no_urut' => $each->no_urut]);
        //         }
        //     }

        //     if ($updateApproval) {

        //         return redirect('access/tenderpenawaran/detail/?id=' . $id);
        //     }
        // }
        $user_id = Auth::user()->id;
        $status = $request->status;

        $document = $tender_menang->penawaran->approval->histories;
        $approval_id = $document->where("user_id",$user_id)->first();
        if ( isset($approval_id->id)){
            $approval_history = ApprovalHistory::find($approval_id->id);
            $approval_history->approval_action_id = $status;
            $approval_history->description = $request->description;
            $approval_history->save();

            //$status = $approval_history->save();
            $highest = $tender_menang->penawaran->approval->histories->min("no_urut");

            if($status != 7){

                $no_urut = $approval_history->no_urut-1;

                if($highest < $approval_history->no_urut){

                    for ($i= 0; $i != 1 ; $i) { 
                        # code...
                        $approval_history_usulan = $tender_menang->penawaran->approval->histories->where("no_urut", $no_urut)->first();
                        if($approval_history_usulan != null){
                            $i = 1;
                        }
                        $no_urut = $no_urut - 1;
                    }

                    if($approval_history_usulan != null){
                        $approval_history_usulan->update(['approval_action_id' => 1]);
                        $project_pt = ProjectPt::where("project_id",$tender_menang->penawaran->project->id)->first();
                        $data_usulan["email"]=$approval_history_usulan->user->email;
                        $data_usulan["client_name"]=$approval_history_usulan->user->user_name;
                        $data_usulan["subject"]='Approval Usulan Pemanang Tender PR';
                        
                        $encript = encrypt('http://cpms.ciputragroup.com:81/access/tenderpenawaran/detail/?id='.$tender_menang->penawaran->id.'||'.$approval_history_usulan->user->id.'||'.date('d/M/Y', strtotime('+ 7 day', strtotime(date("Y-m-d")))));

                        $link_usulan = 'http://cpms.ciputragroup.com:81/access/login/?code='.$encript;
                        $title_usulan = "Approval Usulan Pemanang Tender PR";
                
                        Mail::send('mail.bodyEmailApprove', ['link' => $link_usulan, 'title' => $title_usulan, 'user' => $approval_history_usulan->user, 'project_pt' => $project_pt, 'name' => $tender_menang->penawaran->name], function($message)use($data_usulan) {
                            $message->from(env('MAIL_USERNAME'))->to($data_usulan["email"], $data_usulan["client_name"])->subject($data_usulan["subject"]);
                        });
                    }
                }

                if ( $approval_history->no_urut == $highest){     
                    $approval_ac = Approval::find($tender_menang->penawaran->approval->id);
                    $approval_ac->approval_action_id = $status;
                    $approval_ac->updated_at = date("Y-m-d H:i:s.u");
                    $status = $approval_ac->save();

                    $user_id = Auth::user()->id;
                    $rekanan_id_master = TenderPurchaseRequestGroupRekananDetails::find($tender_menang->rekanan_id)->rekanan_id;
                    $allitems = TenderPurchaseRequestPenawaranDetail::where('tender_penawaran_id', $id)->get();

                    $PO = new PurchaseOrder;
                    $PO->id_tender_menang = $tender_menang->id;
                    $PO->project_for_id = $tender_menang->penawaran->project_for_id;
                    $PO->no             = CreateDocument::createDocumentNumber('PO', 2, $tender_menang->penawaran->project_for_id, $user_id);
                    $PO->rekanan_id     = $rekanan_id_master;
                    $execute = $PO->save();

                    $total = 0;
                    if ($execute) {
                        $approval = new Approval;
                        $approval->approval_action_id   = 1;
                        $approval->document_id          = $PO->id;
                        $approval->document_type        = "Modules\TenderPurchaseRequest\Entities\PurchaseOrder";
                        $approval->total_nilai          = 0;
                        $createApproval = $approval->save();
                        if ($createApproval) {
                            if (count($allitems) > 0) {
                                for ($count = 0; $count < count($allitems); $count++) {
                                    $check_is_item_project = ItemProject::where([['item_id', '=', $allitems[$count]->item_id], ['project_id', '=', $approval->document->project_for_id]])->first();
                                    if ($check_is_item_project != null) {
                                        $POD = new PurchaseOrderDetail;
                                        $POD->purchaseorder_id  = $PO->id;  // nanti
                                        $POD->item_id           = $check_is_item_project->id;
                                        $POD->brand_id          = (int)$allitems[$count]->brand_id;
                                        $POD->quantity          = (int)$allitems[$count]->volume;
                                        $POD->satuan_id         = (int)$allitems[$count]->item_satuan_id;
                                        $POD->harga_satuan      = (int)$allitems[$count]->nilai;
                                        $POD->ppn               = (int)$allitems[$count]->ppn;
                                        $POD->description       = $allitems[$count]->description;
                                        $POD->save();
                                        
                                        $Price              = new ItemPrice;
                                        $Price->item_id     = (int)$check_is_item_project->id;
                                        $Price->item_satuan_id  = (int)$allitems[$count]->item_satuan_id;
                                        $Price->price       = (int)$allitems[$count]->nilai;
                                        $Price->project_id  = (int)$approval->document->project_for_id;
                                        $Price->ppn         = (int)$allitems[$count]->ppn;
                                        $Price->date_price  = date("Y-m-d", strtotime(date("y-m-d")));
                                        $Price->save();

                                    } else {
                                        $default_warehouse_id = Warehouse::where('project_id', $approval->document->project_for_id)->first()->id;
                                        $createItemProject = ItemProject::create([
                                            'item_id' => $allitems[$count]->item_id,
                                            'project_id' => $approval->document->project_for_id,
                                            'default_warehouse_id' => $default_warehouse_id
                                        ]);

                                        if ($createItemProject) {
                                            $POD = new PurchaseOrderDetail;
                                            $POD->purchaseorder_id  = $PO->id;  // nanti
                                            $POD->item_id           = $createItemProject->id;
                                            $POD->brand_id          = $allitems[$count]->brand_id;
                                            $POD->quantity          = $allitems[$count]->volume;
                                            $POD->satuan_id         = $allitems[$count]->item_satuan_id;
                                            $POD->harga_satuan      = $allitems[$count]->nilai;
                                            $POD->ppn               = $allitems[$count]->ppn;
                                            $POD->save();
                                        }
                                    }
                                    $total += ((int)$allitems[$count]->nilai * (int)$allitems[$count]->volume);
                                }
                            }
                        }

                        // Approval::where('document_id', $PO->id)->where('document_type', "Modules\TenderPurchaseRequest\Entities\PurchaseOrder")->update(['approval_action_id' => 6]);

                        $update = Approval::where('document_id', $PO->id)->where('document_type', "Modules\TenderPurchaseRequest\Entities\PurchaseOrder")->update(["total_nilai " => (int)$total,'approval_action_id' => 6]);

                        $approval = Approval::where([['document_id', '=', $PO->id], ['document_type', '=', 'Modules\TenderPurchaseRequest\Entities\PurchaseOrder']])->first();

                        ApprovalHistory::where("approval_id",$approval->id)
                                                    ->where("approval_action_id",2)
                                                    ->delete();

                        // CreateDocument::make_approval_history($approval->id,'Modules\TenderPurchaseRequest\Entities\PurchaseOrder',$project_id);

                        $approval_references = \Modules\Approval\Entities\ApprovalReference::where('document_type', "PurchaseOrder")
                                                ->where('project_id', $approval->document->project_for_id)
                                                //->where('pt_id', $pt_id )
                                                ->where('min_value', '<=', $approval->total_nilai)
                                                //->where('max_value', '>=', $approval->total_nilai)
                                                ->orderBy('no_urut','ASC')
                                                ->get();

                        foreach ($approval_references as $key => $each) {
                            ApprovalHistory::create(['no_urut'=> $each->no_urut,
                                'user_id'=> $each->user_id,
                                'approval_action_id'=>$approval->approval_action_id,
                                'approval_id'=>$approval->id,
                                'document_type'=>"Modules\TenderPurchaseRequest\Entities\PurchaseOrder",
                                'document_id'=>$approval->document_id,
                                'no_urut' => $each->no_urut]);
                        }
                    }
                }
            }else{
                $approval_ac = Approval::find($tender_menang->penawaran->approval->id);
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
            return response()->json( ["status" => "2"] );
        }

        // return redirect("/tenderpurchaserequest/detail/?id=". $request->tpr_id);
    }

    public function reject_tender_penawaran(Request $request)
    {
        $tender_menang = TenderMenangPR::where("id", $request->id)->first();

        // return $tender_menang;
        // return $tender_menang;
        $id = $tender_menang->id_penawaran;
        // return $id;
        $tender_purchase_request_group_rekanans = TenderPurchaseRequestGroupRekanan::find($id);
        $approval_obj = Approval::where([['document_id', '=', $id], ['document_type', '=', 'Modules\TenderPurchaseRequest\Entities\TenderPurchaseRequestPenawaran']]);

        if ($request->optradio == "reject_vendor") {
            if ($approval_obj->first()->approval_action_id == 2) {
                $updateApproval = $approval_obj->update(['approval_action_id' => 7]);

                $approval = Approval::where([['document_id', '=', $id], ['document_type', '=', 'Modules\TenderPurchaseRequest\Entities\TenderPurchaseRequestPenawaran']])->first();

                ApprovalHistory::where("approval_id", $approval->id)
                    ->where("approval_action_id", 2)
                    ->delete();

                // CreateDocument::make_approval_history($approval->id, 'Modules\TenderPurchaseRequest\Entities\TenderPurchaseRequestPenawaran', $approval->document->project_for_id);

                $approval_references = \Modules\Approval\Entities\ApprovalReference::where('document_type', "TenderMenangPR")
                                    ->where('project_id',  $approval->document->project_for_id)
                                    //->where('pt_id', $pt_id )
                                    ->where('min_value', '<=', $approval->total_nilai)
                                    //->where('max_value', '>=', $approval->total_nilai)
                                    ->orderBy('no_urut','ASC')
                                    ->get();

                foreach ($approval_references as $key => $each) 
                {
                    ApprovalHistory::create(['no_urut'=> $each->no_urut,
                        'user_id'=> $each->user_id,
                        'approval_action_id'=>$approval->approval_action_id,
                        'approval_id'=>$approval->id,
                        'document_type'=>"Modules\TenderPurchaseRequest\Entities\TenderPurchaseRequestPenawaran",
                        'document_id'=>$approval->document_id,
                        'no_urut' => $each->no_urut]);
                }

                ApprovalHistory::where("approval_id", $approval->id)
                    ->where("approval_action_id", 7)
                    ->update(["description" => $request->deskripsi_reject]);

                if ($updateApproval) {

                    return redirect('access/tenderpenawaran/detail/?id=' . $id);
                }
            }
        } elseif ($request->optradio == "reject_harga") {
            if ($approval_obj->first()->approval_action_id == 2) {
                $updateApproval = $approval_obj->update(['approval_action_id' => 1]);

                $approval = Approval::where([['document_id', '=', $id], ['document_type', '=', 'Modules\TenderPurchaseRequest\Entities\TenderPurchaseRequestPenawaran']])->first();

                ApprovalHistory::where("approval_id", $approval->id)
                    ->where("approval_action_id", 2)
                    ->delete();

                // CreateDocument::make_approval_history($approval->id, 'Modules\TenderPurchaseRequest\Entities\TenderPurchaseRequestPenawaran', $approval->document->project_for_id);

                $approval_references = \Modules\Approval\Entities\ApprovalReference::where('document_type', "TenderMenangPR")
                                    ->where('project_id',  $approval->document->project_for_id)
                                    //->where('pt_id', $pt_id )
                                    ->where('min_value', '<=', $approval->total_nilai)
                                    //->where('max_value', '>=', $approval->total_nilai)
                                    ->orderBy('no_urut','ASC')
                                    ->get();

                foreach ($approval_references as $key => $each) 
                {
                    ApprovalHistory::create(['no_urut'=> $each->no_urut,
                        'user_id'=> $each->user_id,
                        'approval_action_id'=>$approval->approval_action_id,
                        'approval_id'=>$approval->id,
                        'document_type'=>"Modules\TenderPurchaseRequest\Entities\TenderPurchaseRequestPenawaran",
                        'document_id'=>$approval->document_id,
                        'no_urut' => $each->no_urut]);
                }
                ApprovalHistory::where("approval_id", $approval->id)
                    ->where("approval_action_id", 1)
                    ->update(["description" => $request->deskripsi_reject]);

                TenderMenangPR::where('id', $tender_menang->id)->delete();

                if ($updateApproval) {

                    return redirect('access');
                }
            }
        } elseif ($request->optradio == "reject_keseluruhan") {
            if ($approval_obj->first()->approval_action_id == 2) {
                $updateApproval = $approval_obj->update(['approval_action_id' => 7]);

                $approval = Approval::where([['document_id', '=', $id], ['document_type', '=', 'Modules\TenderPurchaseRequest\Entities\TenderPurchaseRequestPenawaran']])->first();

                ApprovalHistory::where("approval_id", $approval->id)
                    ->where("approval_action_id", 2)
                    ->delete();

                // CreateDocument::make_approval_history($approval->id, 'Modules\TenderPurchaseRequest\Entities\TenderPurchaseRequestPenawaran', $approval->document->project_for_id);

                $approval_references = \Modules\Approval\Entities\ApprovalReference::where('document_type', "TenderMenangPR")
                                    ->where('project_id',  $approval->document->project_for_id)
                                    //->where('pt_id', $pt_id )
                                    ->where('min_value', '<=', $approval->total_nilai)
                                    //->where('max_value', '>=', $approval->total_nilai)
                                    ->orderBy('no_urut','ASC')
                                    ->get();

                foreach ($approval_references as $key => $each) 
                {
                    ApprovalHistory::create(['no_urut'=> $each->no_urut,
                        'user_id'=> $each->user_id,
                        'approval_action_id'=>$approval->approval_action_id,
                        'approval_id'=>$approval->id,
                        'document_type'=>"Modules\TenderPurchaseRequest\Entities\TenderPurchaseRequestPenawaran",
                        'document_id'=>$approval->document_id,
                        'no_urut' => $each->no_urut]);
                }

                ApprovalHistory::where("approval_id", $approval->id)
                    ->where("approval_action_id", 7)
                    ->update(["description" => $request->deskripsi_reject]);

                if ($updateApproval) {

                    return redirect('access/tenderpenawaran/detail/?id=' . $id);
                }
            }
        }
        // if($approval_obj->first()->approval_action_id == 2)
        // {
        //     $updateApproval = $approval_obj->update(['approval_action_id'=>6]);

        //     $approval = Approval::where([['document_id','=',$id],['document_type','=','Modules\TenderPurchaseRequest\Entities\TenderPurchaseRequestPenawaran']])->first();

        //     ApprovalHistory::where("approval_id",$approval->id)
        //                                ->where("approval_action_id",2)
        //                                ->delete();

        //     CreateDocument::make_approval_history($approval->id,'Modules\TenderPurchaseRequest\Entities\TenderPurchaseRequestPenawaran',$approval->document->project_for_id);

        //     if($updateApproval)
        //     {

        //         return redirect('access/tenderpenawaran/detail/?id='.$id);
        //     }
        // }

    }
}
