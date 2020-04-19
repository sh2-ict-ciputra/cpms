<?php

namespace Modules\Rab\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Rab\Entities\Rab;
use Modules\Rab\Entities\RabDetail;
use Modules\Rab\Entities\RabPekerjaan;
use Modules\Rab\Entities\RabUnit;
use Modules\Workorder\Entities\Workorder;
use Modules\Project\Entities\Project;
use Modules\Pekerjaan\Entities\Itempekerjaan;
use Modules\Asset\Entities\Asset;
use Modules\Budget\Entities\BudgetTahunan;
use Modules\Budget\Entities\BudgetTahunanDetail;
use Modules\Workorder\Entities\WorkorderBudgetDetail;
use Modules\Tender\Entities\Tender;
use Modules\Tender\Entities\TenderUnit;
use Modules\Tender\Entities\TenderDocument;
use Modules\Project\Entities\ProjectPt;
use Illuminate\Support\Facades\Mail;
use Modules\Approval\Entities\Approval;
use Modules\Approval\Entities\ApprovalHistory;
use Modules\Satuan\Entities\CoaSatuan;
use Modules\Rab\Entities\RabSubPekerjaan;
use Modules\Project\Entities\Unit;
use Modules\Spk\Entities\Spk;

use DB;

class RabController extends Controller
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
        if ( isset($request->workorder_id)){
            $workorder = Workorder::find($request->workorder_id);
        }else{
            $workorder = $project->workorder;
        }
        
        $project = Project::find($request->session()->get('project_id'));
        $user = \Auth::user();
        return view('rab::index',compact("project","workorder","user"));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create(Request $request)
    {
        $workorder = Workorder::find($request->id);
        $project = Project::find($request->session()->get('project_id'));
        $user = \Auth::user();
         return view('rab::create',compact("project","workorder","user"));
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $workorder = Workorder::find($request->rab_wo);
        $project = Project::find($request->session()->get('project_id'));
        $rab_no = \App\Helpers\Document::new_number('RAB', $workorder->department_from,$project->id).$rab->pt->code;
        $rab = new Rab;
        $rab->no = $rab_no;
        $rab->workorder_id = $request->rab_wo;
        $rab->name = $request->rab_name;
        $rab->created_by = \Auth::user()->id;
        $rab->save();
        return redirect("/rab/detail?id=".$rab->id."&idpkr=".$request->idpkr);
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show(Request $request)
    {
        $user = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        $rab = Rab::find($request->id);
        // if ( $rab->parent_id != "" ){
        //     $itempekerjaan = Itempekerjaan::find($rab->parent_id);;
        // }else{
        //     $itempekerjaan = "";
        // }
        $itempekerjaan = "";
        $idpkr = $request->idpkr;
        $itempkr = Itempekerjaan::find($idpkr);
        // printf($rab->nilai);
        // echo("<br>");
        // printf($rab->units->count());
        // echo("<br>");
        // return 0;
        $coa_satuan = CoaSatuan::all();
        return view('rab::show',compact("user","project","rab","itempekerjaan","itempkr","coa_satuan"));
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit()
    {
        return view('rab::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request)
    {
        $rab = Rab::find($request->id);
        $rab->name = $request->name;
        $rab->save();
        return redirect("/rab/detail?id=".$request->id);
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy()
    {
    }

    public function itempekerjaan(Request $request){
        $html = "";
        $start = 0;
        $itempekerjaan = Itempekerjaan::find($request->id);
        
        foreach ( $itempekerjaan->child_item as $key3 => $value3 ){            
            if ( count($value3->child_item) > 0 ){
                $html .= "<tr>";
                $html .= "<td><strong>".$value3->code."</strong></td>";
                $html .= "<td style='background-color: white;color:black;' onclick='showhide(".$value3->id.")' data-attribute='1' id='btn_".$value3->id."'>".$value3->name."</td>";
                $html .= "<td>&nbsp;</td>";
                $html .= "<td>&nbsp;</td>";
                $html .= "<td>&nbsp;</td>";
                $html .= "</tr>";
                foreach ( $value3->child_item as $key4 => $value4 ){
                    if ( count($value4->child_item) > 0 ){
                        $html .= "<tr>";
                        $html .= "<td><strong>".$value4->code."</strong></td>";
                        $html .= "<td style='background-color: white;color:black;' onclick='showhide(".$value4->id.")' data-attribute='1' id='btn_".$value4->id."'>".$value4->name."</td>";
                        $html .= "<td>&nbsp;</td>";
                        $html .= "<td>&nbsp;</td>";
                        $html .= "<td>&nbsp;</td>";
                        $html .= "</tr>";
                        
                        foreach ($value4->child_item as $key5 => $value5) {
                            $html .= "<tr>";
                            $html .= "<td><strong>".$value5->code."</strong></td>";
                            $html .= "<td style='background-color: white;color:black;' onclick='showhide(".$value5->id.")' data-attribute='1' id='btn_".$value5->id."'>".$value5->name."</td>";
                            $html .= "<td><input type='hidden' class='form-control' name='item_id[".$start."]' value='".$value5->id."'/><input type='text' class='form-control volume' name='volume_[".$start."]' value='0'  onkeyup='summary(".$start.")'/><input type='hidden' class='form-control' name='code[".$start."]' value='".$value5->code."'/></td>";
                            $html .= "<td><input type='text' class='form-control' name='satuan_[".$start."]' value='".$value5->itempekerjaan->details->satuan."'/></td>";
                            $html .= "<td><input type='text' class='form-control nilai_budgets' name='nilai_[".$start."]' value=''  onkeyup='summary(".$start.")'/></td>";
                            $html .= "<td><span id='total_".$start."'></span></td>";
                            $html .= "</tr>";
                            $start++;  
                        }
                    } else {
                        $html .= "<tr>";
                        $html .= "<td><strong>".$value4->code."</strong></td>";
                        $html .= "<td style='background-color: white;color:black;' onclick='showhide(".$value4->id.")' data-attribute='1' id='btn_".$value4->id."'>".$value4->name."</td>";
                        $html .= "<td><input type='hidden' class='form-control' name='item_id[".$start."]' value='".$value4->id."'/><input type='text' class='form-control volume' name='volume_[".$start."]' id='volume_[".$start."]' value='0' onkeyup='summary(".$start.")'/><input type='hidden' class='form-control' name='code[".$start."]' value='".$value4->code."'/></td>";
                        $html .= "<td><input type='text' class='form-control' name='satuan_[".$start."]' value='".$value4->itempekerjaan->details->satuan."'/></td>";
                        $html .= "<td><input type='text' class='form-control ' name='nilai_[".$start."]' id='nilai_[".$start."]' value='' onkeyup='summary(".$start.")'/></td>";
                        $html .= "<td><span id='total_".$start."'></span></td>";
                        $html .= "</tr>";
                        $start++;  
                    }   
                                    
                }
            }else{
                $html .= "<tr>";
                $html .= "<td><strong>".$value3->code."</strong></td>";
                $html .= "<td style='background-color: white;color:black;' onclick='showhide(".$value3->id.")' data-attribute='1' id='btn_".$value3->id."'>".$value3->name."</td>";
                $html .= "<td><input type='hidden' class='form-control' name='item_id[".$start."]' value='".$value3->id."'/><input type='hidden' class='form-control' name='code[".$start."]' value='".$value3->code."'/><input type='text' class='form-control volume' name='volume_[".$start."]' value='0'/></td>";
                $html .= "<td><input type='text' class='form-control ' name='satuan_[".$start."]' value='' required/></td>";
                $html .= "<td><input type='text' class='form-control nilai_budgets' name='nilai_[".$start."]' value=''/></td>";
                $html .= "<td><span id='total_{{ $start }}'></span></td>";
                $html .= "</tr>";
                $start++;  
            }
        }
        return response()->json( ["status" => "0", "html" => $html] );
    }

    public function saveunit(Request $request){

        foreach ($request->unit_rab_ as $key => $value) {
            if ($request->unit_rab_[$key] != "" ){
                $rabunits = new RabUnit;
                $rabunits->rab_id = $request->rab_unit_id;
                $rabunits->asset_id = $request->unit_rab_[$key];
                $rabunits->asset_type = $request->unit_rab_type_[$key];
                $rabunits->created_by = \Auth::user()->id;
                $rabunits->save();
            }
            
        }
        return redirect("/rab/detail?id=".$request->rab_unit_id."&idpkr=".$request->idpkr);
    }

    public function deleteunit(Request $request){
        $rabunits = RabUnit::find($request->id);
        $rabunits->deleted_at = date("Y-m-d H:i:s.000");
        $rabunits->deleted_by = \Auth::user()->id;
        $status = $rabunits->save();
        if ( $status ){
            return response()->json( ["status" => "0"] );
        }else{
            return response()->json( ["status" => "1"] );
        }
    }

    public function savepekerjaan(Request $request){
        $budget_tahunan_id = BudgetTahunan::find($request->budget_tahunan_id);
        $rab  = Rab::find($request->rab);
        // $no = $rab->no;
        // $rab->budget_tahunan_id = $request->budget_tahunan_id;
        // $rab->no = $no.$budget_tahunan_id->budget->pt->code;
        // $rab->save();

        foreach ($request->item_id as $key => $value) {
            if ( $request->nilai_[$key] != "" && $request->volume_[$key] != "" ){
                $rabpekerjaan = new RabPekerjaan;
                $rabpekerjaan->rab_unit_id = $rab->id;
                $rabpekerjaan->itempekerjaan_id = $request->item_id[$key];
                $rabpekerjaan->nilai = str_replace(",", "", $request->nilai_[$key]);
                $rabpekerjaan->volume = $request->volume_[$key];
                $rabpekerjaan->satuan = $request->satuan_[$key];
                $rabpekerjaan->created_by  = \Auth::user()->id;
                $rabpekerjaan->save();
            }else{
                $rabpekerjaan = new RabPekerjaan;
                $rabpekerjaan->rab_unit_id = $rab->id;
                $rabpekerjaan->itempekerjaan_id = $request->item_id[$key];
                $rabpekerjaan->nilai =0;
                $rabpekerjaan->volume = 0;
                $rabpekerjaan->satuan = $request->satuan_[$key];
                $rabpekerjaan->created_by  = \Auth::user()->id;
                $rabpekerjaan->save();
            }
        }      
        return redirect("/rab/detail?id=".$rab->id."&idpkr=".$request->idpkr);          
    }

    public function updateitem(Request $request){
        $rab = RabPekerjaan::find($request->id);
        $rab->nilai = $request->nilai;
        $rab->volume = $request->volume;
        $rab->satuan = $request->satuan;
        $rab->created_by  = \Auth::user()->id;
        $status = $rab->save();
        if ( $status ){
            return response()->json( ["status" => "0"] );
        }else{
            return response()->json( ["status" => "1"] );
        }
    }

    public function approval(Request $request){
        $rab = $request->id;
        $class  = "Rab";
        $approval = \App\Helpers\Document::make_approval('Modules\Rab\Entities\Rab',$rab);

        // Approval::where([['document_id', '=', $rab], ['document_type', '=', 'Modules\Rab\Entities\Rab'], ['approval_action_id', '=', 1]])->update(['approval_action_id' => 4]);

        // $approval = Approval::where([['document_id', '=', $rab], ['document_type', '=', 'Modules\Rab\Entities\Rab']])->first();

        // $approval_history = ApprovalHistory::where('document_type',"Modules\Rab\Entities\Rab")
        //                                     ->where('document_id',$rab)
        //                                     ->update(['approval_action_id' => 4]);

        // $data_rab = Rab::find($request->id);

        // $approval_history = \Modules\Approval\Entities\ApprovalHistory::where('document_id',$rab)->where('document_type','Modules\Rab\Entities\Rab')->get();
        // $project = Project::find($request->session()->get('project_id'));
        // $project_pt = ProjectPt::where("project_id",$project->id)->first();
        // wibowo.rahardja@ciputra.com
        // arman.djohan@ciputra.com
        // foreach ($approval_history as $key => $value) {
        //     $data["email"]=$value->user->email;
        //     $data["client_name"]=$value->user->user_name;
        //     $data["subject"]='Approval Rab';
        //     $link = 'https://ces.ciputragroup.com/webapps/Ciputra/public/';
        //     $title = "Rab";

        //     // Mail::send('mail.bodyEmailApprove', ['link' => $link, 'title' => $title, 'user' => $value->user, 'project_pt' => $project_pt, 'name' => $data_rab->name], function($message)use($data) {
        //     // $message->from(env('MAIL_USERNAME'))->to($data["email"], $data["client_name"])
        //     // ->subject($data["subject"]);
        //     // });
        // }

        // Approval::where('document_id', $rab)->where('document_type', 'Modules\Rab\Entities\Rab')->update(['approval_action_id' => 6]);

        return response()->json( ["status" => "0"] );
    }

    public function childcoa(Request $request){
        $html = "";
        $budget = 0;
        $start = 0;
        $itempekerjaan = Itempekerjaan::find($request->id); 
        $workorder = Workorder::find($request->workorder);
        $budget_tahunan_id = "";
        $budget_tersisa = 0;
        $workorder_detail = WorkorderBudgetDetail::where("itempekerjaan_id",$request->id)->where("workorder_id",$request->workorder)->get();
        if ( count($workorder_detail) > 0 ){
            $budget_tahunan_id = $workorder_detail->first()->budget_tahunan_id;
            $budget = ($workorder_detail->first()->volume * $workorder_detail->first()->nilai);
        }
        $budget_tersisa = $budget - $workorder->rabs->where("budget_tahunan_id",$budget_tahunan_id)->where("parent_id",$request->id)->sum("nilai");
        $data = [];
        foreach ( $itempekerjaan->child_item as $key3 => $value3 ){            
            
            /*$html .= "<tr>";
            $html .= 
            $html .= "<option value='".$value3->id."'>".$value3->code."-".$value3->name."</option>";*/
            if ( count($value3->child_item) > 0 ){
                // $html .= "<tr>";
                // $html .= "<td><strong>".$value3->code."</strong></td>";
                // $html .= "<td style='background-color: white;color:black;' onclick='showhide(".$value3->id.")' data-attribute='1' id='btn_".$value3->id."'>".$value3->name."</td>";
                // $html .= "<td>&nbsp;</td>";
                // $html .= "<td>&nbsp;</td>";
                // $html .= "<td>&nbsp;</td>";
                // $html .= "<td>&nbsp;</td>";
                // // $html .= "<td>&nbsp;</td>";
                // $html .= "</tr>";
                
                $arr = [
                    'code'      => $value3->code,
                    'id'        => $value3->id,
                    'name'      => $value3->name,
                    'satuan'    => null,
                ];

                array_push($data, $arr);

                foreach ($value3->child_item as $key5 => $value5) {
                    if ( count($value5->child_item) > 0 ){

                        foreach ($value5->child_item as $key6 => $value6) {
                            if ( $value6->details != "" ){
                                $satuan = $value6->details->satuan;
                            }else{
                                $satuan = "Ls";
                            }

                            if(explode(".", $value6->code)[2] != "00"){
                                // $html .= "<tr>";
                                // $html .= "<td><strong>".$value6->code."</strong></td>";
                                // $html .= "<td style='background-color: white;color:black;' onclick='showhide(".$value6->id.")' data-attribute='1' id='btn_".$value6->id."'>".$value6->name."</td>";
                                // $html .= "<td><input type='hidden' class='form-control' name='item_id[".$start."]' value='".$value6->id."'/><input type='text' class='form-control volume' name='volume_[".$start."]' value='0' autocomplete='off'/><input type='hidden' class='form-control' name='code[".$start."]' value='".$value6->code."' autocomplete='off'/></td>";
                                // $html .= "<td><input type='text' class='form-control' name='satuan_[".$start."]' value='".$satuan."' autocomplete='off' readonly/></td>";
                                // $html .= "<td><input type='text' class='form-control nilai_budgets' name='nilai_[".$start."]' value=''/></td>";
                                // // $html .= "<td><span id='total_".$start."'></span></td>";
                                // $html .= "<td>tombol tambah</td>";
                                // $html .= "</tr>";
                                // $start++;  

                                $arr = [
                                    'code'      => $value6->code,
                                    'id'        => $value6->id,
                                    'name'      => $value6->name,
                                    'satuan'    => $satuan,
                                ];

                                array_push($data, $arr);
                            }else{
                                // $html .= "<tr>";
                                // $html .= "<td><strong>".$value6->code."</strong></td>";
                                // $html .= "<td style='background-color: white;color:black;' onclick='showhide(".$value6->id.")' data-attribute='1' id='btn_".$value6->id."'>".$value6->name."</td>";
                                // $html .= "<td><input type='hidden' class='form-control' name='item_id[".$start."]' value='".$value6->id."'/><input type='text' class='form-control volume name='volume_[".$start."]' value='0' autocomplete='off' readonly/><input type='hidden' class='form-control' name='code[".$start."]' value='".$value6->code."' autocomplete='off'/></td>";
                                // $html .= "<td><input type='text' class='form-control' name='satuan_[".$start."]' value='".$satuan."' autocomplete='off' readonly/></td>";
                                // $html .= "<td><input type='text' class='form-control nilai_budgets' name='nilai_[".$start."]' value='' readonly/></td>";
                                // // $html .= "<td><span id='total_".$start."'></span></td>";
                                // $html .= "<td>tombol tambah</td>";
                                // $html .= "</tr>";
                                // $start++;  

                                $arr = [
                                    'code'      => $value6->code,
                                    'id'        => $value6->id,
                                    'name'      => $value6->name,
                                    'satuan'    => null,
                                ];
                                array_push($data, $arr);
                            }
                        }


                    }else{
                        if ( $value5->details != "" ){
                            $satuan = $value5->details->satuan;
                        }else{
                            $satuan = "Ls";
                        }
                        if(explode(".", $value5->code)[2] != "00"){
                            // $html .= "<tr>";
                            // $html .= "<td><strong>".$value5->code."</strong></td>";
                            // $html .= "<td style='background-color: white;color:black;' onclick='showhide(".$value5->id.")' data-attribute='1' id='btn_".$value5->id."'>".$value5->name."</td>";
                            // $html .= "<td><input type='hidden' class='form-control' name='item_id[".$start."]' value='".$value5->id."'/><input type='text' class='form-control volume' name='volume_[".$start."]' value='0' autocomplete='off'/><input type='hidden' class='form-control' name='code[".$start."]' value='".$value5->code."' autocomplete='off'/></td>";
                            // $html .= "<td><input type='text' class='form-control' name='satuan_[".$start."]' value='".$satuan."' autocomplete='off' readonly/></td>";
                            // $html .= "<td><input type='text' class='form-control nilai_budget' name='nilai_[".$start."]' value='' /></td>";
                            // // $html .= "<td><span id='total_".$start."'></span></td>";
                            // $html .= "<td>tombol tambah</td>";
                            // $html .= "</tr>";

                            $arr = [
                                'code'      => $value5->code,
                                'id'        => $value5->id,
                                'name'      => $value5->name,
                                'satuan'    => $satuan,
                            ];
                            array_push($data, $arr);
                        }else{
                            // $html .= "<tr>";
                            // $html .= "<td><strong>".$value5->code."</strong></td>";
                            // $html .= "<td style='background-color: white;color:black;' onclick='showhide(".$value5->id.")' data-attribute='1' id='btn_".$value5->id."'>".$value5->name."</td>";
                            // $html .= "<td><input type='hidden' class='form-control' name='item_id[".$start."]' value='".$value5->id."'/><input type='text' class='form-control volume' name='volume_[".$start."]' value='0' autocomplete='off' readonly/><input type='hidden' class='form-control' name='code[".$start."]' value='".$value5->code."' autocomplete='off'/></td>";
                            // $html .= "<td><input type='text' class='form-control' name='satuan_[".$start."]' value='".$satuan."' autocomplete='off' readonly/></td>";
                            // $html .= "<td><input type='text' class='form-control nilai_budget' name='nilai_[".$start."]' value='' readonly/></td>";
                            // // $html .= "<td><span id='total_".$start."'></span></td>";
                            // $html .= "<td>tombol tambah</td>";
                            // $html .= "</tr>";

                            $arr = [
                                'code'      => $value5->code,
                                'id'        => $value5->id,
                                'name'      => $value5->name,
                                'satuan'    => null,
                            ];
                            array_push($data, $arr);
                        }
                    }
                    
                    // $start++;  
                }
            }else{
                if ( $value3->details != "" ){
                    $satuan = $value3->details->satuan;
                }else{
                    $satuan = "Ls";
                }
                if(explode(".", $value3->code)[2] != "00"){
                    // $html .= "<tr>";
                    // $html .= "<td><strong>".$value3->code."</strong></td>";
                    // $html .= "<td style='background-color: white;color:black;' onclick='showhide(".$value3->id.")' data-attribute='1' id='btn_".$value3->id."'>".$value3->name."</td>";
                    // $html .= "<td><input type='hidden' class='form-control' name='item_id[".$start."]' value='".$value3->id."'/><input type='hidden' class='form-control' name='code[".$start."]' value='".$value3->code."'/><input type='text' class='form-control volume' name='volume_[".$start."]' value='0' autocomplete='off'/></td>";
                    // $html .= "<td><input type='text' class='form-control ' name='satuan_[".$start."]' value='".$satuan."' autocomplete='off'  readonly/></td>";
                    // $html .= "<td><input type='text' class='form-control nilai_budget' name='nilai_[".$start."]'value='' autocomplete='off'/></td>";
                    // // $html .= "<td><span id='total_{{ $start }}' ></span></td>";
                    // $html .= "<td>tombol tambah</td>";
                    // $html .= "</tr>";
                    // $start++;

                    $arr = [
                        'code'      => $value3->code,
                        'id'        => $value3->id,
                        'name'      => $value3->name,
                        'satuan'    => $satuan,
                    ];
                    array_push($data, $arr);
                }else{
                    // $html .= "<tr>";
                    // $html .= "<td><strong>".$value3->code."</strong></td>";
                    // $html .= "<td style='background-color: white;color:black;' onclick='showhide(".$value3->id.")' data-attribute='1' id='btn_".$value3->id."'>".$value3->name."</td>";
                    // $html .= "<td><input type='hidden' class='form-control' name='item_id[".$start."]' value='".$value3->id."'/><input type='hidden' class='form-control' name='code[".$start."]' value='".$value3->code."'/><input type='text' class='form-control volume' name='volume_[".$start."]' value='0' autocomplete='off' readonly/></td>";
                    // $html .= "<td><input type='text' class='form-control ' name='satuan_[".$start."]' value='".$satuan."' autocomplete='off'  readonly/></td>";
                    // $html .= "<td><input type='text' class='form-control nilai_budget' name='nilai_[".$start."]'value='' autocomplete='off' readonly/></td>";
                    // // $html .= "<td><span id='total_{{ $start }}' ></span></td>";
                    // $html .= "<td>tombol tambah</td>";
                    // $html .= "</tr>";
                    // $start++;

                    $arr = [
                        'code'      => $value3->code,
                        'id'        => $value3->id,
                        'name'      => $value3->name,
                        'satuan'    => null,
                    ];
                    array_push($data, $arr);
                }
            }
            
        }
        return response()->json( ["status" => "0", "html" => $html, "budget" => $budget, "budget_tahunan_id" => $budget_tahunan_id, "budget_tersisa" => $budget_tersisa, "data" => $data] );
    }

    public function deletepekerjaan(Request $request){
        $rab = Rab::find($request->id);
        foreach ($rab->pekerjaans as $key => $value) {
            $rab_pekerjaan = RabPekerjaan::find($value->id);
            $rab_pekerjaan->deleted_at = date("Y-m-d H:i:s.000");
            $rab_pekerjaan->deleted_by = \Auth::user()->id;
            $rab_pekerjaan->save();
        }
        $rab = Rab::find($request->id);
        $rab->spk_id = null;
        $rab->save();
         return response()->json( ["status" => "0"] );
    }

    public function approval_history(Request $request){
        $rab = Rab::find($request->id);
        $approval = $rab->approval;
        $project = Project::find($request->session()->get('project_id'));
        $user = \Auth::user();
        return view("rab::approval_history",compact("rab","approval","project","user"));
    }

    public function selectpekerjaan(Request $request){
        $rab = Rab::find($request->id);
        $project = Project::find($request->session()->get('project_id'));
        $user = \Auth::user();
        return view("rab::rab_pekerjaan",compact("user","project","rab"));
    }

    public function generate(Request $Request){
        $rab = Rab::find(3488);
        $itempekerjaan = Itempekerjaan::find(292);
        $start = 0;
        foreach ($itempekerjaan->child_item as $key => $value) {
            if ( count($value3->child_item) > 0 ){
                foreach ($value3->child_item as $key5 => $value5) {
                    if ( count($value5->child_item) > 0 ){
                        foreach ($value5->child_item as $key6 => $value6) {
                            if ( $start >= 9 ){
                                $rabpekerjaan = new RabPekerjaan;
                                $rabpekerjaan->rab_unit_id = $rab->id;
                                $rabpekerjaan->itempekerjaan_id = $value6->id;
                                $rabpekerjaan->nilai = 0;
                                $rabpekerjaan->volume = 1;
                                $rabpekerjaan->satuan = 'm2';
                                $rabpekerjaan->created_by  = \Auth::user()->id;
                                $rabpekerjaan->save();
                            }
                            $start++;
                        }
                    }else{
                        if ( $start >= 9 ){
                            $rabpekerjaan = new RabPekerjaan;
                            $rabpekerjaan->rab_unit_id = $rab->id;
                            $rabpekerjaan->itempekerjaan_id = $value6;
                            $rabpekerjaan->nilai = 0;
                            $rabpekerjaan->volume = 1;
                            $rabpekerjaan->satuan = 'm2';
                            $rabpekerjaan->created_by  = \Auth::user()->id;
                            $rabpekerjaan->save();
                        }
                        $start++;
                    }
                }

            }else{

            }
        }
    }

    public function createtender(Request $request){
        $rab = Rab::find($request->id);
        if ( $rab->tenders->count() > 0 ){
            return redirect("/tender/detail/?id=".$rab->tenders->last()->id);
        }else{
            //echo $rab->units->last()->pekerjaans->last()->id;
            $itempekerjaan = Itempekerjaan::find($rab->pekerjaans->last()->itempekerjaan->parent->id);
            $department_from = $rab->workorder->department_from;
            $project = Project::find($request->session()->get('project_id'));
            $tender = new Tender;
            $tender_no = \App\Helpers\Document::new_number('TENDER', $department_from,$project->id).$rab->pt->code;
            $tender->rab_id = $request->tender_rab;
            $tender->name = "Tender-".$itempekerjaan->code."-".$itempekerjaan->name."-".$rab->name;
            $tender->no = $tender_no;
            $tender->rab_id = $rab->id;
            $tender->save();

            foreach ($rab->workorder_budget_detail->dokumen as $key => $value) {
             
                $tender_document = TenderDocument::find($value->id);
                $tender_document->tender_id = $tender->id;
                $tender_document->save();
            }

            if (!file_exists("./assets/tender/".$tender->id)) {
                mkdir("./assets/tender/".$tender->id);
            }

            foreach ($rab->units as $key => $value) {
                $tender_unit = new TenderUnit;
                $tender_unit->tender_id = $tender->id;
                $tender_unit->rab_unit_id = $value->id;
                $tender_unit->created_by = \Auth::user()->id;
                $tender_unit->save();
            }
            return redirect("/tender/detail/?id=".$tender->id);   
        }        
    }

    public function tender(Request $request){
        if ( count($rab->tenders) > 0 ){
            return redirect("/tender/detail/?id=".$rab->tenders->last()->id);
        }else{
            
            $rab = Rab::find($request->id);
            $itempekerjaan = Itempekerjaan::find($rab->pekerjaans->last()->itempekerjaan->parent->id);
            $department_from = $rab->workorder->department_from;
            $project = Project::find($request->session()->get('project_id'));
            $tender = new Tender;
            $tender_no = \App\Helpers\Document::new_number('TENDER', $department_from,$project->id).$rab->pt->code;
            $tender->rab_id = $request->tender_rab;
            $tender->name = "Tender-".$itempekerjaan->code."-".$itempekerjaan->name."-".$rab->name;
            $tender->no = $tender_no;
            $tender->rab_id = $rab->id;
            $tender->save();

            if (!file_exists("./assets/tender/".$tender->id)) {
                mkdir("./assets/tender/".$tender->id);
            }

            foreach ($rab->units as $key => $value) {
                $tender_unit = new TenderUnit;
                $tender_unit->tender_id = $tender->id;
                $tender_unit->rab_unit_id = $value->id;
                $tender_unit->created_by = \Auth::user()->id;
                $tender_unit->save();
            }
            return redirect("/tender/detail/?id=".$tender->id);

        }
    }

    public function savelink(Request $request){
        $workorder_budget_detail_id = WorkorderBudgetDetail::find($request->id);
        $project = Project::find($request->session()->get('project_id'));
        $rab_no = \App\Helpers\Document::new_number('RAB', $workorder_budget_detail_id->workorder->department_from,$project->id).$rab->pt->code;
        $rab = new Rab;
        $rab->no = $rab_no;
        $rab->workorder_id = $workorder_budget_detail_id->workorder->id;
        $rab->name = $workorder_budget_detail_id->workorder->name;
        $rab->created_by = \Auth::user()->id;
        $rab->workorder_budget_detail_id = $workorder_budget_detail_id->id;
        $rab->save();
        return redirect("/rab/detail?id=".$rab->id."&idpkr=".$request->idpkr);
    }


    public function updateapproval(Request $request){
        $rab = $request->id;
        $class  = "Rab";
        // $approval = \App\Helpers\Document::make_approval('Modules\Rab\Entities\Rab',$rab);

        Approval::where([['document_id', '=', $rab], ['document_type', '=', 'Modules\Rab\Entities\Rab'], ['approval_action_id', '=', 7]])->update(['approval_action_id' => 1]);

        $approval = Approval::where([['document_id', '=', $rab], ['document_type', '=', 'Modules\Rab\Entities\Rab']])->select("*")->first();

        $approval_history = ApprovalHistory::where('document_type',"Modules\Rab\Entities\Rab")->where('document_id',$approval->document_id)->where('approval_action_id','!=', 2)->get();

        foreach ($approval_history as $key => $each) {
            # code...
            ApprovalHistory::create([
                'no_urut' => $each->no_urut,
                'user_id' => $each->user_id,
                'approval_action_id' => 2,
                'approval_id' => $approval->id,
                'document_type' => $each->document_type,
                'document_id' => $approval->document_id,
                'no_urut' => $each->no_urut
            ]);
            $each->delete();
        }

        $rab_data = Rab::find($request->id);

        $project = Project::find($request->session()->get('project_id'));
        $project_pt = ProjectPt::where("project_id",$project->id)->first();

        $approval_history_rab = \Modules\Approval\Entities\ApprovalHistory::where('document_type',"Modules\Rab\Entities\Rab")->where('document_id',$rab_data->id)->orderBy('no_urut','DESC')->first();
        
        $approval_history_rab->update(['approval_action_id' => 1]);

        $data_rab["email"]=$approval_history_rab->user->email;
        $data_rab["client_name"]=$approval_history_rab->user->user_name;
        $data_rab["subject"]='Approval RAB';

        $encript = encrypt('http://cpms.ciputragroup.com:81/access/rab/detail/?id='.$rab_data->id.'||'.$approval_history_rab->user->id.'||'.date('d/M/Y', strtotime('+ 7 day', strtotime(date("Y-m-d")))));

        $link_rab = 'http://cpms.ciputragroup.com:81/access/login/?code='.$encript;
        $title_rab = "Rab";

        Mail::send('mail.bodyEmailApprove', ['link' => $link_rab, 'title' => $title_rab, 'user' => $approval_history_rab->user, 'project_pt' => $project_pt, 'name' => $rab_data->name], function($message)use($data_rab) {
            $message->from(env('MAIL_USERNAME'))->to($data_rab["email"], $data_rab["client_name"])->subject($data_rab["subject"]);
        });

        // $approval_history = \Modules\Approval\Entities\ApprovalHistory::where('document_id',$rab)->where('document_type','Modules\Rab\Entities\Rab')->get();
        // $project = Project::find($request->session()->get('project_id'));
        // $project_pt = ProjectPt::where("project_id",$project->id)->first();
        // // wibowo.rahardja@ciputra.com
        // // arman.djohan@ciputra.com
        // foreach ($approval_history as $key => $value) {
        //     $data["email"]=$value->user->email;
        //     $data["client_name"]=$value->user->user_name;
        //     $data["subject"]='Approval Rab';
        //     $link = 'https://ces.ciputragroup.com/webapps/Ciputra/public/';
        //     $title = "Rab";

        //     Mail::send('mail.bodyEmailApprove', ['link' => $link, 'title' => $title, 'user' => $value->user, 'project_pt' => $project_pt, 'name' => $data_rab->name], function($message)use($data) {
        //     $message->from(env('MAIL_USERNAME'))->to($data["email"], $data["client_name"])
        //     ->subject($data["subject"]);
        //     });
        // }

        // Approval::where('document_id', $rab)->where('document_type', 'Modules\Rab\Entities\Rab')->update(['approval_action_id' => 6]);

        return response()->json( ["status" => "0"] );
    }

    public function saveAllPekerjaan(Request $request){ 
        $rab  = Rab::find($request->rab_id);
        // $no = $rab->no;
        // $rab->no = $no.$rab->pt->code;
        // $rab->save();
        $data = json_decode($request->data);

        for ($i=0; $i < count($data); $i++) { 
            # code...
            if ( $data[$i][3] != "" && $data[$i][3] != null && $data[$i][1] != 0 ){
                $rabpekerjaan = new RabPekerjaan;
                $rabpekerjaan->rab_unit_id = $rab->id;
                $rabpekerjaan->itempekerjaan_id = $data[$i][0];
                $rabpekerjaan->total_nilai = str_replace(",", "", $data[$i][3]);
                $rabpekerjaan->volume = $data[$i][1];
                $rabpekerjaan->satuan = $data[$i][2];
                $rabpekerjaan->created_by  = \Auth::user()->id;
                $rabpekerjaan->save();

                for ($j=0; $j < count($data[$i][4]); $j++) { 
                    $rabsubpekerjaan = new RabSubPekerjaan;
                    $rabsubpekerjaan->rab_pekerjaan_id = $rabpekerjaan->id;
                    $rabsubpekerjaan->name = $data[$i][4][$j][0];
                    $rabsubpekerjaan->volume = $data[$i][4][$j][1];
                    $rabsubpekerjaan->satuan = $data[$i][4][$j][2];
                    $rabsubpekerjaan->nilai = str_replace(",", "", $data[$i][4][$j][3]);
                    $rabsubpekerjaan->total_nilai = str_replace(",", "", $data[$i][4][$j][4]);
                    $rabsubpekerjaan->save();
                }
                
            }else{
                $rabpekerjaan = new RabPekerjaan;
                $rabpekerjaan->rab_unit_id = $rab->id;
                $rabpekerjaan->itempekerjaan_id = $data[$i][0];
                $rabpekerjaan->total_nilai =0;
                $rabpekerjaan->volume = 0;
                $rabpekerjaan->satuan = $data[$i][2];
                $rabpekerjaan->created_by  = \Auth::user()->id;
                $rabpekerjaan->save();
            }
        }
        return response()->json( ["status" => "0"] );       
    }

    public function closeRabTender(Request $request){
        $rab = Rab::find($request->id);
        if(count($rab->tenders) > 0){
            $tender = $rab->tenders[0];
        }else{
            $tender = null;
        }
        if($rab->approval != null){
            $rab->approval->update(['approval_action_id' => 8]);
        }
        if($tender != null){
            $tender->approval->update(['approval_action_id' => 8]);
        }
        foreach ($rab->workorder->details as $key => $value) {
            # code...
            if($value->asset_type == "Modules\Project\Entities\Unit"){
                $unit = Unit::find($value->asset_id);
                $unit->is_readywo = null;
                $unit->save();
            }
        }

        return response()->json( ["status" => "0"] );
    }

    public function updatePekerjaan(Request $request){
        // return $request;
        $data = json_decode($request->data);
        $rab  = Rab::find($request->rab_id);

        for ($i=0; $i < count($data); $i++) { 
            # code...
            if ( $data[$i][3] != "" && $data[$i][3] != null && $data[$i][1] != 0 ){
                $rabpekerjaan = RabPekerjaan::find($data[$i][0]);
                // $rabpekerjaan->rab_unit_id = $rab->id;
                // $rabpekerjaan->itempekerjaan_id = $data[$i][0];
                $rabpekerjaan->total_nilai = str_replace(",", "", $data[$i][3]);
                $rabpekerjaan->volume = $data[$i][1];
                $rabpekerjaan->satuan = $data[$i][2];
                $rabpekerjaan->updated_by  = \Auth::user()->id;
                $rabpekerjaan->save();
                
                $delete = DB::table("rab_sub_pekerjaans")->where("rab_pekerjaan_id", $rabpekerjaan->id)->update(["deleted_at"=> date("Y-m-d")]);

                for ($j=0; $j < count($data[$i][4]); $j++) {
                    if($data[$i][4][$j][0] != null){
                        DB::table("rab_sub_pekerjaans")->where("id", $data[$i][4][$j][0])->update(["deleted_at"=> null]);

                        $rabsubpekerjaan = RabSubPekerjaan::find($data[$i][4][$j][0]);
                        $rabsubpekerjaan->rab_pekerjaan_id = $rabpekerjaan->id;
                        $rabsubpekerjaan->name = $data[$i][4][$j][1];
                        $rabsubpekerjaan->volume = $data[$i][4][$j][2];
                        $rabsubpekerjaan->satuan = $data[$i][4][$j][3];
                        $rabsubpekerjaan->nilai = str_replace(",", "", $data[$i][4][$j][4]);
                        $rabsubpekerjaan->total_nilai = str_replace(",", "", $data[$i][4][$j][5]);
                        $rabsubpekerjaan->save();
                    }else{
                        $rabsubpekerjaan = new RabSubPekerjaan;
                        $rabsubpekerjaan->rab_pekerjaan_id = $rabpekerjaan->id;
                        $rabsubpekerjaan->name = $data[$i][4][$j][1];
                        $rabsubpekerjaan->volume = $data[$i][4][$j][2];
                        $rabsubpekerjaan->satuan = $data[$i][4][$j][3];
                        $rabsubpekerjaan->nilai = str_replace(",", "", $data[$i][4][$j][4]);
                        $rabsubpekerjaan->total_nilai = str_replace(",", "", $data[$i][4][$j][5]);
                        $rabsubpekerjaan->save();
                    }
                }
                
            }else{
                $rabpekerjaan = RabPekerjaan::find($data[$i][0]);
                $rabpekerjaan->rab_unit_id = $rab->id;
                // $rabpekerjaan->itempekerjaan_id = $data[$i][0];
                $rabpekerjaan->total_nilai =0;
                $rabpekerjaan->volume = 0;
                $rabpekerjaan->satuan = $data[$i][2];
                $rabpekerjaan->updated_by  = \Auth::user()->id;
                $rabpekerjaan->save();

                RabSubPekerjaan::where("rab_pekerjaan_id", $data[$i][0])->delete();
            }
        }
        return response()->json( ["status" => "0"] );       
    }

    public function dokumenLama(Request $request){
        // return $request;
        $project = Project::find($request->session()->get('project_id'));
        $main = [];
        if ($request->jenis_dokumen == "RAB") {
            # code...
            $wo = Workorder::where("budget_tahunan_id", $project->id)->get();
            foreach ($wo as $key => $value) {
                # code...
                $pekerjaan = $value->detail_pekerjaan->where("itempekerjaan_id", $request->id_pekerjaan)->first();
                if($pekerjaan != null){
                    $rab = $pekerjaan->rab;
                    if($rab != null){
                        // return $rab->pekerjaans;
                        if(count($rab->pekerjaans) != 0){
                            $arr = [
                                'id' => $rab->id,
                                'no' => $rab->no,
                            ];

                            array_push($main, $arr);
                        }
                    }
                }
            }
            // return $main;
            return response()->json($main);
        }elseif($request->jenis_dokumen == "SPK"){
            $spk = Spk::where("project_id", $project->id)->get();
            foreach ($spk as $key => $value) {
                # code...
                $pekerjaan = $value->tender_rekanan->menangs->first()->details;
                if(count($pekerjaan) != 0){
                    if($pekerjaan[0]->itempekerjaan->parent->id == $request->id_pekerjaan){
                        $arr = [
                            'id' => $value->id,
                            'no' => $value->no,
                        ];

                        array_push($main, $arr);
                    }
                }
            }
            // return $main;
            return response()->json($main);
        }
    }

    public function tambahPekerjaanLama(Request $request){
        // return $request;
        if ($request->jenis_dokumen == "RAB") {
            $rab = Rab::find($request->dokumen);
            // return $rab->pekerjaan;
            if(count($rab->pekerjaans) != 0){
                foreach ($rab->pekerjaans as $key => $value) {
                    # code...
                    $rabpekerjaan = new RabPekerjaan;
                    $rabpekerjaan->rab_unit_id = $request->rab_id;
                    $rabpekerjaan->itempekerjaan_id = $value->itempekerjaan_id;
                    if($value->total_nilai == null){
                        $rabpekerjaan->total_nilai = 0;
                    }else{
                        $rabpekerjaan->total_nilai = $value->total_nilai;
                    }
                    $rabpekerjaan->volume = $value->volume;
                    $rabpekerjaan->satuan = $value->satuan;
                    $rabpekerjaan->save();

                    if(count($value->sub_pekerjaan) != 0){
                        foreach ($value->sub_pekerjaan as $key2 => $value2) {
                            $rabsubpekerjaan = new RabSubPekerjaan;
                            $rabsubpekerjaan->rab_pekerjaan_id = $rabpekerjaan->id;
                            $rabsubpekerjaan->name = $value2->name;
                            $rabsubpekerjaan->volume = $value2->volume;
                            $rabsubpekerjaan->satuan =$value2->satuan;
                            $rabsubpekerjaan->nilai = $value2->nilai;
                            $rabsubpekerjaan->total_nilai = $value2->total_nilai;
                            $rabsubpekerjaan->save();
                        }
                    }
                }
            }
            $rab = Rab::find($request->rab_id);
            $rab->spk_id = null;
            $rab->save();
            return response()->json( ["data" => 0] );
        }elseif($request->jenis_dokumen == "SPK"){
            // return $request;
            $spk = Spk::find($request->dokumen);

            $itempekerjaan = Itempekerjaan::find($request->id_pekerjaan); 
            foreach ( $itempekerjaan->child_item as $key => $value ){ 
                $pekerjaan = $spk->tender_rekanan->menangs->first()->details->where("itempekerjaan_id", $value->id)->first();
                if( $pekerjaan != null){
                        # code...
                        $rabpekerjaan = new RabPekerjaan;
                        $rabpekerjaan->rab_unit_id = $request->rab_id;
                        $rabpekerjaan->itempekerjaan_id = $pekerjaan->itempekerjaan_id;
                        $rabpekerjaan->total_nilai = $pekerjaan->total_nilai;
                        $rabpekerjaan->volume = $pekerjaan->volume;
                        $rabpekerjaan->satuan = $pekerjaan->satuan;
                        $rabpekerjaan->save();
    
                        if(count($pekerjaan->tender_menang_sub_detail) != 0){
                            foreach ($pekerjaan->tender_menang_sub_detail as $key2 => $value2) {
                                $rabsubpekerjaan = new RabSubPekerjaan;
                                $rabsubpekerjaan->rab_pekerjaan_id = $rabpekerjaan->id;
                                $rabsubpekerjaan->name = $value2->name;
                                $rabsubpekerjaan->volume = $value2->volume;
                                $rabsubpekerjaan->satuan =$value2->satuan;
                                $rabsubpekerjaan->nilai = $value2->nilai;
                                $rabsubpekerjaan->total_nilai = $value2->total_nilai;
                                $rabsubpekerjaan->save();
                            }
                        }
                }else{
                    if ( $value->details != "" ){
                        $satuan = $value->details->satuan;
                    }else{
                        $satuan = "Ls";
                    }
                    $rabpekerjaan = new RabPekerjaan;
                    $rabpekerjaan->rab_unit_id = $request->rab_id;
                    $rabpekerjaan->itempekerjaan_id = $value->id;
                    $rabpekerjaan->total_nilai = 0;
                    $rabpekerjaan->volume = 0;
                    $rabpekerjaan->satuan = $satuan;
                    $rabpekerjaan->save();
                }
            }
            $rab = Rab::find($request->rab_id);
            $rab->spk_id = $spk->id;
            $rab->save();
            return response()->json( ["data" => 0] );
        }
    }
}
