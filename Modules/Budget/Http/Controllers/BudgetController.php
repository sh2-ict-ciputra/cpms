<?php

namespace Modules\Budget\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Budget\Entities\Budget;
use Modules\Project\Entities\Project;
use Modules\Project\Entities\ProjectKawasan;
use Modules\Approval\Entities\Approval;
use Modules\Approval\Entities\ApprovalHistory;
use Modules\Pekerjaan\Entities\Itempekerjaan;
use Modules\Budget\Entities\BudgetDetail;
use Modules\Budget\Entities\BudgetTahunan;
use Modules\Budget\Entities\BudgetTahunanDetail;
use Modules\Budget\Entities\BudgetTahunanPeriode;
use Modules\Budget\Entities\BudgetCarryOver;
use Modules\Budget\Entities\BudgetHistoryHpp;
use Modules\Project\Entities\ProjectPtUser;
use Modules\Department\Entities\Department;
use Modules\Pt\Entities\Pt;
use Modules\Project\Entities\ProjectPt;
use Modules\Project\Entities\UnitType;
use Modules\Budget\Entities\BudgetType;
use Modules\Budget\Entities\BudgetCarryOverCashflow;
use Modules\Budget\Entities\BudgetTahunanUnit;
use Modules\Budget\Entities\BudgetTahunanUnitPeriode;
use Modules\Budget\Entities\BudgetTahunanUnitPeriodeDetail;
use Modules\Budget\Entities\BudgetProjectPekerjaan;
use Modules\Satuan\Entities\CoaSatuan;
use Modules\Budget\Entities\BudgetDetailHistory;
use Modules\Budget\Entities\BudgetTahunanDetailBulanan;
use Modules\Budget\Entities\BudgetTahunanDetailHistory;
use Modules\Budget\Entities\BudgetTahunanDetailBulananHistory;
use Modules\Spk\Entities\Spk;
use Modules\Spk\Entities\SpkMigrasi;
use Modules\Voucher\Entities\Voucher;


class BudgetController extends Controller
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
        // $budget = $project->budgets->where("department_id",2)->orderBy("budgets.id","desc");
        $budget = Budget::where("project_id", $project->id)->where("department_id",2)->orderBy("id","desc")->get();
        $spk = Spk::where("project_id",$project->id)->get();
        $total_spk = 0;
        foreach ($spk as $key => $value) {
            # code...
            if($value->tender->rab->workorder->department_from == 2){
                // printf($value->no);
                // echo("<br/>");
                if($value->item_pekerjaan->code != 100){
                    $total_spk += $value->nilai_spk;
                }
            }
        }
        $department = Department::all();
        return view("budget::index",compact("user","project","budget","total_spk","department"));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create(Request $request)
    {
        $project = Project::find($request->id);
        $user= \Auth::user();
        $department = Department::get();
        return view('budget::create_budget_project',compact("project","user","department"));
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $budget = new Budget;
        $project = Project::find($request->session()->get('project_id'));
        $pt = Pt::find($request->pt_id);

        $number = \App\Helpers\Document::new_number('BDG', $request->department,$project->id).$pt->code;
        $budget->pt_id = $request->pt_id;
        $budget->department_id = $request->department;
        $budget->project_id = $request->project_id;
        if ( $request->iskawasan == "" ){
            $budget->project_kawasan_id = null;
        }else{
            $budget->project_kawasan_id = $request->kawasan;
        }
        $budget->no = $number;
        $budget->start_date = $request->start_date;
        $budget->end_date = $request->end_date;
        $budget->description = $request->description;
        $budget->created_by = \Auth::user()->id;
        $budget->save();

        /*$budget_type = BudgetType::get();
        foreach ($budget_type as $key => $value) {
            if ( $value->itempekerjaan_id == 1 ){
                foreach ($value->details as $key2 => $value2) {
                    $budgetDetail = new BudgetDetail;
                    $budgetDetail->budget_id = $budget->id;
                    $budgetDetail->itempekerjaan_id = $value2->itempekerjaan->id;
                    $budgetDetail->nilai = str_replace(",", "", $value2->itempekerjaan->nilai_master_satuan);
                    $budgetDetail->volume = 0;
                    $budgetDetail->satuan = $value2->itempekerjaan->details->satuan;
                    $budgetDetail->save();
                }
            }
        }*/

        $itempekerjaan = Itempekerjaan::get();
        // foreach ($itempekerjaan as $key => $value) {
        //     if ( $value->parent_id == null ){

        //         if ( $request->iskawasan == "" ){
        //             if ( $value->code == "240" ){
        //                 foreach ($value->child_item as $key2 => $value2) {
        //                     $budgetDetail = new BudgetDetail;
        //                     $budgetDetail->budget_id = $budget->id;
        //                     $budgetDetail->itempekerjaan_id = $value2->id;
        //                     $budgetDetail->nilai = str_replace(",", "", $value2->nilai_master_satuan);
        //                     $budgetDetail->volume = 0;
        //                     if ( $value2->details != "" ){
        //                         $budgetDetail->satuan = $value2->details->satuan;
        //                     }else{
        //                         $budgetDetail->satuan = 'ls';
        //                     }
        //                     $budgetDetail->save();
        //                 }
        //             }
        //         }else{
        //             if ( $value->code != "240" ){
        //                 if ( $value->group_cost == 1 ){  
        //                     $budgetDetail = new BudgetDetail;
        //                     $budgetDetail->budget_id = $budget->id;
        //                     $budgetDetail->itempekerjaan_id = $value->id;
        //                     $budgetDetail->nilai = str_replace(",", "", $value->nilai_master_satuan);
        //                     $budgetDetail->volume = 0;
        //                     if ( $value->details != "" ){
        //                         $budgetDetail->satuan = $value->details->satuan;
        //                     }else{
        //                         $budgetDetail->satuan = 'ls';
        //                     }
        //                     $budgetDetail->save();
        //                 } elseif ( $value->id == 292 ){
        //                     $budgetDetail = new BudgetDetail;
        //                     $budgetDetail->budget_id = $budget->id;
        //                     $budgetDetail->itempekerjaan_id = $value->id;
        //                     $budgetDetail->nilai = str_replace(",", "", $value->nilai_master_satuan);
        //                     $budgetDetail->volume = 0;
        //                     if ( $value->details != "" ){
        //                         $budgetDetail->satuan = $value->details->satuan;
        //                     }else{
        //                         $budgetDetail->satuan = 'ls';
        //                     }
        //                     $budgetDetail->save();
        //                 }
        //             }                    
        //         }
        //     }
        // }


        //$approval = \App\Helpers\Document::make_approval('Modules\Budget\Entities\Budget',$budget->id);
        return redirect("budget/detail/?id=".$budget->id);
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show(Request $request)
    {
        if($request->session()->get('project_id') == null){
            return redirect("");
        }
        $budget = Budget::find($request->id);
        $user   = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        $department = Department::get();
        $array = array (
            "6" => array("label" => "Disetujui", "class" => "label label-success"),
            "7" => array("label" => "Ditolak", "class" => "label label-danger"),
            "1" => array("label" => "Dalam Proses", "class" => "label label-warning")
        );
        
        $dev_cost_faskot = 0;
        $nilai_hpp = 0;
        $total_nilai_spk_dc = 0;
        $total_nilai_spk_cc = 0;
        $sisa_spk_dc = 0;
        $sisa_spk_cc = 0;
        $budget_faskot = Budget::where("project_id",$project->id)->where("project_kawasan_id",null)->get();
        $spk = Spk::where("project_id", $budget->project_id)->get();
        foreach ( $budget_faskot as $key => $value) {
            # code...
            $dev_cost_faskot += $value->total_dev_cost; 
        }
        // printf($budget->total_spk["total_nilai_spk_dc"]);
        // echo("<br/>");
        // printf($budget->total_dev_cost);
        // echo("<br/>");
        // return $budget->total_spk["total_nilai_spk_dc"]+$budget->total_dev_cost;
        if($budget->kawasan != null){
            $beban_faskot = ($budget->kawasan->lahan_luas/$project->luas)*$dev_cost_faskot;
            if($budget->kawasan->netto_kawasan != 0){
                $nilai_hpp = ($beban_faskot + ($budget->total_dev_cost + $budget->total_spk["total_nilai_spk_dc"]))/$budget->kawasan->netto_kawasan;
            }else{
                $nilai_hpp = 0;
            }

            // foreach ($budget->kawasan->workorder as $key => $value) {
            //     foreach ($value->rabs as $key2 => $value2) {
            //         if(count($value2->tenders) != 0){
            //             if(count($value2->tenders[0]->spks) != 0){
            //                 if($value2->tenders[0]->spks[0]->tender_rekanan->menangs->first()->details[0]->itempekerjaan->parent->code != 100){
            //                     $spk_nilai_kontrak =$value2->tenders[0]->spks[0]->nilai + $value2->tenders[0]->spks[0]->nilai_vo + $value2->tenders[0]->spks[0]->nilai_percepatan;

            //                     $total_nilai_spk_dc += $spk_nilai_kontrak;
            //                     $spk_dibayarkan_dc = 0;
            //                     foreach ($value2->tenders[0]->spks[0]->baps as $key => $value3){
            //                         # code...
            //                         if($value3->vouchers_date_cair != null){
            //                             if($value3->vouchers_date_cair->pencairan_date != null){
            //                                 if($value3->st_status != 1){
            //                                     $spk_dibayarkan_dc += $value3->nilai_bap2;
            //                                 }else{
            //                                     $spk_dibayarkan_dc += $value3->pph * $nilai_kontrak * $value2->tenders[0]->spks[0]->retensis->sum('percent');
            //                                 }
            //                             }
            //                         }
            //                     }
            //                     $sisa_spk_dc += ($spk_nilai_kontrak - $spk_dibayarkan_dc) ;
            //                 }else{
            //                     $spk_nilai_kontrak =$value2->tenders[0]->spks[0]->nilai + $value2->tenders[0]->spks[0]->nilai_vo + $value2->tenders[0]->spks[0]->nilai_percepatan;

            //                     $total_nilai_spk_cc += $spk_nilai_kontrak;
            //                     $spk_dibayarkan_cc = 0;
            //                     foreach ($value2->tenders[0]->spks[0]->baps as $key => $value3){
            //                         # code...
            //                         if($value3->vouchers_date_cair != null){
            //                             if($value3->vouchers_date_cair->pencairan_date != null){
            //                                 if($value3->st_status != 1){
            //                                     $spk_dibayarkan_cc += $value3->nilai_bap2;
            //                                 }else{
            //                                     $spk_dibayarkan_cc += $value3->pph * $nilai_kontrak * $value2->tenders[0]->spks[0]->retensis->sum('percent');
            //                                 }
            //                             }
            //                         }
            //                     }
            //                     $sisa_spk_cc += ($spk_nilai_kontrak - $spk_dibayarkan_dc) ;
            //                 }
            //             }
            //         }
            //     }
            // }

            foreach ($spk as $key => $value) {
                # code...
                if($value->tender->rab->workorder->kawasan_id != null){
                    if($value->tender->rab->workorder->projectKawasan->id == $budget->kawasan->id){
                        if($value->item_pekerjaan->code != 100){
                            $spk_nilai_kontrak = $value->nilai_spk;
                            $total_nilai_spk_dc += $spk_nilai_kontrak;
                            $spk_dibayarkan_dc = $value->spk_terbayar;
                            $sisa_spk_dc += ($spk_nilai_kontrak - $spk_dibayarkan_dc) ;
                        }else{
                            $spk_nilai_kontrak = $value->nilai_spk;
                            $total_nilai_spk_cc += $spk_nilai_kontrak;
                            $spk_dibayarkan_cc = $value->spk_terbayar;
                            $sisa_spk_cc += ($spk_nilai_kontrak - $spk_dibayarkan_cc) ;
                        }
                    }
                }
            }
        }else{
            // foreach ($budget->budget_tahunans as $key => $value4) {
            //     # code...
            //     foreach ($value4->workorders as $key => $value) {
            //         foreach ($value->rabs as $key2 => $value2) {
            //             if(count($value2->tenders) != 0){
            //                 if(count($value2->tenders[0]->spks) != 0){
            //                     if($value2->tenders[0]->spks[0]->tender_rekanan->menangs->first()->details[0]->itempekerjaan->parent->code != 100){
            //                         $spk_nilai_kontrak =$value2->tenders[0]->spks[0]->nilai + $value2->tenders[0]->spks[0]->nilai_vo + $value2->tenders[0]->spks[0]->nilai_percepatan;
    
            //                         $total_nilai_spk_dc += $spk_nilai_kontrak;
            //                         $spk_dibayarkan_dc = 0;
            //                         foreach ($value2->tenders[0]->spks[0]->baps as $key => $value3){
            //                             # code...
            //                             if($value3->vouchers_date_cair != null){
            //                                 if($value3->vouchers_date_cair->pencairan_date != null){
            //                                     if($value3->st_status != 1){
            //                                         $spk_dibayarkan_dc += $value3->nilai_bap2;
            //                                     }else{
            //                                         $spk_dibayarkan_dc += $value3->pph * $nilai_kontrak * $value2->tenders[0]->spks[0]->retensis->sum('percent');
            //                                     }
            //                                 }
            //                             }
            //                         }
            //                         $sisa_spk_dc += ($spk_nilai_kontrak - $spk_dibayarkan_dc) ;
            //                     }else{
            //                         $spk_nilai_kontrak =$value2->tenders[0]->spks[0]->nilai + $value2->tenders[0]->spks[0]->nilai_vo + $value2->tenders[0]->spks[0]->nilai_percepatan;
    
            //                         $total_nilai_spk_cc += $spk_nilai_kontrak;
            //                         $spk_dibayarkan_cc = 0;
            //                         foreach ($value2->tenders[0]->spks[0]->baps as $key => $value3){
            //                             # code...
            //                             if($value3->vouchers_date_cair != null){
            //                                 if($value3->vouchers_date_cair->pencairan_date != null){
            //                                     if($value3->st_status != 1){
            //                                         $spk_dibayarkan_cc += $value3->nilai_bap2;
            //                                     }else{
            //                                         $spk_dibayarkan_cc += $value3->pph * $nilai_kontrak * $value2->tenders[0]->spks[0]->retensis->sum('percent');
            //                                     }
            //                                 }
            //                             }
            //                         }
            //                         $sisa_spk_cc += ($spk_nilai_kontrak - $spk_dibayarkan_dc) ;
            //                     }
            //                 }
            //             }
            //         }
            //     }
            // }

            foreach ($spk as $key => $value) {
                # code...
                if($value->tender->rab->workorder->kawasan_id == null){
                    if($value->item_pekerjaan->code != 100){
                        $spk_nilai_kontrak = $value->nilai_spk;
                        $total_nilai_spk_dc += $spk_nilai_kontrak;
                        $spk_dibayarkan_dc = $value->spk_terbayar;
                        $sisa_spk_dc += ($spk_nilai_kontrak - $spk_dibayarkan_dc) ;
                    }else{
                        $spk_nilai_kontrak = $value->nilai_spk;
                        $total_nilai_spk_cc += $spk_nilai_kontrak;
                        $spk_dibayarkan_cc = $value->spk_terbayar;
                        $sisa_spk_cc += ($spk_nilai_kontrak - $spk_dibayarkan_cc) ;
                    }
                }
            }
        }

        return view('budget::show',compact("user","budget","project","department","array","nilai_hpp","total_nilai_spk_dc","total_nilai_spk_cc","sisa_spk_dc","sisa_spk_cc"));
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit(Request $request)
    {
        $budget = Budget::find($request->budget_id);
        $budget->department_id = $request->department;
        $budget->project_id = $request->project_id;
        if ( $request->iskawasan == "" ){
            $budget->project_kawasan_id = null;
        }else{
            $budget->project_kawasan_id = $request->kawasan;
        }
        $budget->start_date = date("Y-m-d H:i:s.u",strtotime($request->start_date));
        $budget->end_date = date("Y-m-d H:i:s.u",strtotime($request->end_date));
        $budget->description = $request->description;
        $budget->save();

        
        return redirect("budget/detail/?id=".$budget->id);
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request)
    {
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy()
    {
    }

    public function itempekerjaan(Request $request){
        $budget = Budget::find($request->id);
        $user = \Auth::user();
        $itempekerjaan = Itempekerjaan::where("parent_id",null)->get();
        $project = Project::find($request->session()->get('project_id'));
        return view("budget::item",compact("budget","user","itempekerjaan","project"));
    }

    public function itemdetail(Request $request){
        $itempekerjaan = Itempekerjaan::find($request->id);
        $html = "";
        $start = 0;
         $html .= "<tr>";
        $html .= "<td><strong>".$itempekerjaan->code."</strong></td>";
        $html .= "<td style='background-color: white;color:black;' onclick='showhide(".$itempekerjaan->id.")' data-attribute='1' id='btn_".$itempekerjaan->id."'>".$itempekerjaan->name."</td>";
        $html .= "<td>
                    <input type='hidden' class='form-control ' name='item_id[".$start."]' value='".$itempekerjaan->id."'/>
                    <input type='hidden' class='form-control' name='code[".$start."]' value='".$itempekerjaan->code."'/>
                    <input type='text' class='form-control nilai_budget' name='volume_[".$start."]' autocomplete='off'/></td>";
        $html .= "<td>
                    <input type='hidden' class='form-control' name='satuan_[".$start."]' value='".$itempekerjaan->details->satuan."' autocomplete='off' />
                    <input type='text' class='form-control' value='".$itempekerjaan->details->satuan."' autocomplete='off' disabled />
                    </td>";
        $html .= "<td><input type='text' class='form-control nilai_budget' name='nilai_[".$start."]' autocomplete='off' /></td>";
        $html .= "</tr>";

        $status = "1";
        if ( $status ){
            return response()->json( ["status" => "0", "html" => $html] );
        }else{
            return response()->json( ["status" => "1", "html" => "" ] );
        }
    }

    public function itemsave(Request $request){
        $budgetdetail = BudgetDetail::find($request->budget_detail_id);
        $budgetdetail->nilai = str_replace(",", "", $request->nilai);
        $budgetdetail->volume = str_replace(",", "", $request->volume);
        $budgetdetail->uraian_pekerjaan = $request->uraian;
        $budgetdetail->description = $request->Keterangan;
        $budgetdetail->save();

        return redirect("budget/referensi/?id=".$budgetdetail->id);
    }

    public function itemupdate(Request $request){
        if ( $request->id != ""){
            $budgetDetail = BudgetDetail::find($request->id);
        }else{
            $budgetDetail = new BudgetDetail;
            $budgetDetail->budget_id = $request->budget_id;
            $budgetDetail->itempekerjaan_id = $request->itempekerjaan;
        }
        $budgetDetail->nilai = str_replace(",", "",$request->nilai);
        $budgetDetail->volume = str_replace(",", "",$request->volume);
        $status = $budgetDetail->save();
        if ( $status ){
            return response()->json( ["status" => "0"]);
        }else{
            return response()->json( ["status" => "1"] );
        }
    }

    public function deletebudget(Request $request){
        $budgetDetail = BudgetDetail::find($request->id);
        $status = $budgetDetail->delete();
        if ( $status ){
            return response()->json( ["status" => "0"]);
        }else{
            return response()->json( ["status" => "1"] );
        }
    }

    public function approval(Request $request){
        $explode = explode(",",$request->budget_id_array);

        foreach ($explode as $key => $value) {
            
            if ( $value != "" ){
                $budget = $value;
                $class  = "Budget";
                $approval = \App\Helpers\Document::make_approval('Modules\Budget\Entities\Budget',$budget);
            }
        }
        
        //return response()->json( ["status" => "0"] );
    }

    public function cashlflow(Request $request){
        if($request->session()->get('project_id') == null){
            return redirect("");
        }
        $user = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        $budget = Budget::find($request->id);
        $start_date = $budget->start_date->year;
        $end_date = $budget->end_date->year;
        return view("budget::cashflow",compact("budget","user","start_date","end_date","project"));
    }

    public function addcashflow(Request $request){
        $budget = Budget::find( $request->budget_id);
        $pt = Pt::find($budget->pt_id);
        $project = Project::find($request->session()->get('project_id'));
        $budget_tahunan                 = new BudgetTahunan;
        $budget_tahunan->budget_id      = $request->budget_id;
        $budget_tahunan->no             = \App\Helpers\Document::new_number('BDG-T', $budget->department->id,$project->id).$pt->code;
        $budget_tahunan->tahun_anggaran = $request->tahun_anggaran;
        $budget_tahunan->description    = $request->description;
        $status = $budget_tahunan->save();
        $start = 0;
        foreach ($budget->details as $key => $value) {
            $budgetDetail = new BudgetTahunanDetail;
            $budgetDetail->budget_tahunan_id = $budget_tahunan->id;
            $budgetDetail->itempekerjaan_id = $value->itempekerjaan_id;
            $budgetDetail->nilai = $value->nilai;
            $budgetDetail->volume_budget = $value->volume;
            $budgetDetail->volume = 0;
            $budgetDetail->satuan = $value->satuan;
            $budgetDetail->budget_detail_id = $value->id;
            $budgetDetail->uraian_pekerjaan = $value->uraian_pekerjaan;
            $budgetDetail->save();
        }

        foreach (BudgetTahunanDetail::where("budget_tahunan_id",$budget_tahunan->id)->get() as $key => $value) {
            # code...
            for ($i=1; $i <= 12 ; $i++) { 
                # code...
                $budget_tahunan_detail_bulanan = new BudgetTahunanDetailBulanan;
                $budget_tahunan_detail_bulanan->budget_tahunan_detail_id = $value->id;
                $budget_tahunan_detail_bulanan->bulan = $i;
                $budget_tahunan_detail_bulanan->itempekerjaan_id = $value->itempekerjaan_id;
                $budget_tahunan_detail_bulanan->nilai = $value->nilai;
                $budget_tahunan_detail_bulanan->volume = 0;
                $budget_tahunan_detail_bulanan->satuan = $value->satuan;
                $budget_tahunan_detail_bulanan->persen = 0;
                $budget_tahunan_detail_bulanan->save();
            }
        }
        
        return redirect("/budget/cashflow/detail-cashflow?id=".$budget_tahunan->id);
    }

    public function detailcashflow(Request $request){
        $budget_tahunan = BudgetTahunan::find($request->id);
        $budget_parent = $budget_tahunan->budget->parent_id;
        $user = \Auth::user();
        $budget = $budget_tahunan->budget;
        $project = $budget->project;
        $start_date = $budget->start_date->year;
        $end_date = $budget->end_date->year;
        $array_cashflow = array();
        $start = 0;
        $nilai_sum_temp = 0;
        $spk = $project->spks;
        $array_carryover = array();
        if ( $budget_parent != "" ){

        $budget_parent = Budget::find($budget_parent);
        $budget_devcost = $budget_parent->id;
        }else{
            $budget_devcost = $budget->id;
        }


        if ( $budget_tahunan->budget->kawasan != "" ){
            $asset_id = $budget_tahunan->budget->project_kawasan_id;
        }else{
            $asset_id = $budget_tahunan->budget->project_id;
        }
        
        $rekap = [];
        foreach ($budget_tahunan->details as $key => $value) {
            # code...
            $total_budget_tahuanan = $value->volume * $value->nilai;
            $total_bulanan = $value->detail_bulanan->sum('nilai_persen');
            if(array_key_exists($value->coa_id,$rekap)){
                $rekap[$value->itempekerjaans->parent->code]["total_budget_tahunan"] += $total_budget_tahuanan;
                $rekap[$value->itempekerjaans->parent->code]["terbayar"] += 0;
                $rekap[$value->itempekerjaans->parent->code]["hutang_bayar"] += $total_budget_tahuanan;
                for ($i=1; $i <= 12; $i++) { 
                    # code...
                    $rekap[$value->itempekerjaans->parent->code][$i] += $value->detail_bulanan->where('bulan',$i)->first()->nilai_persen;
                }
                $rekap[$value->itempekerjaans->parent->code]["total_budget_tahunan_bulanan"] += $total_bulanan;
                $rekap[$value->itempekerjaans->parent->code]["hutang_bayar_depan"] += $total_budget_tahuanan - $total_bulanan;        
            }else{
                $rekap[$value->itempekerjaans->parent->code]["name"] = $value->itempekerjaans->parent->name;
                $rekap[$value->itempekerjaans->parent->code]["total_budget_tahunan"] = $total_budget_tahuanan;
                $rekap[$value->itempekerjaans->parent->code]["terbayar"] = 0;
                $rekap[$value->itempekerjaans->parent->code]["hutang_bayar"] = $total_budget_tahuanan;
                for ($i=1; $i <= 12; $i++) { 
                    # code...
                    $rekap[$value->itempekerjaans->parent->code][$i] = $value->detail_bulanan->where('bulan',$i)->first()->nilai_persen;
                }
                $rekap[$value->itempekerjaans->parent->code]["total_budget_tahunan_bulanan"] = $total_bulanan;
                $rekap[$value->itempekerjaans->parent->code]["hutang_bayar_depan"] = $total_budget_tahuanan - $total_bulanan;
            }
        }

        foreach ($budget_tahunan->carry_over as $key => $value) {
            # code...
            // $total_budget_tahuanan = $value->volume * $value->nilai;
            $total_bulanan = $value->cash_flows->sum('nilai_persen');
            if(array_key_exists($value->code_coa,$rekap)){
                $rekap[$value->code_coa]["total_budget_tahunan"] += $value->nilai_spk;
                $rekap[$value->code_coa]["terbayar"] += $value->terbayar;
                $rekap[$value->code_coa]["hutang_bayar"] += $value->hutang_bayar;
                for ($i=1; $i <= 12; $i++) { 
                    # code...
                    $rekap[$value->code_coa][$i] += $value->cash_flows->where('bulan',$i)->first()->nilai_persen;
                }
                $rekap[$value->code_coa]["total_budget_tahunan_bulanan"] += $total_bulanan;
                $rekap[$value->code_coa]["hutang_bayar_depan"] += $value->hutang_bayar - $total_bulanan;        
            }else{
                if($value->asal_spk == 1){
                    $rekap[$value->code_coa]["name"] = $value->spk->item_pekerjaan->name;
                }else{
                    $pekerjaan = Itempekerjaan::where("code", $value->code_coa)->first();
                    if($pekerjaan != null){
                        $rekap[$value->code_coa]["name"] = $pekerjaan->name;
                    }else{
                        $rekap[$value->code_coa]["name"] = "noName";
                    }
                }
                $rekap[$value->code_coa]["total_budget_tahunan"] = $value->nilai_spk;
                $rekap[$value->code_coa]["terbayar"] = $value->terbayar;
                $rekap[$value->code_coa]["hutang_bayar"] = $value->hutang_bayar;
                for ($i=1; $i <= 12; $i++) { 
                    # code...
                    $rekap[$value->code_coa][$i] = $value->cash_flows->where('bulan',$i)->first()->nilai_persen;
                }
                $rekap[$value->code_coa]["total_budget_tahunan_bulanan"] = $total_bulanan;
                $rekap[$value->code_coa]["hutang_bayar_depan"] = $value->hutang_bayar - $total_bulanan;
            }
        }

        // return $rekap;
        $itempekerjaan_parent = Itempekerjaan::where('parent_id')->orderBy("code","ASC")->get();
        
        return view("budget::detail_cashflow2",compact("project","budget_tahunan","user","budget","start_date","end_date","rekap","itempekerjaan_parent"));
    }

    public function updatecashflow(Request $request){
        $budget_tahunan                 = BudgetTahunan::find($request->budget_tahunan_id);
        $budget_tahunan->tahun_anggaran = $request->tahun_anggaran;
        $budget_tahunan->description    = $request->description;
        $status = $budget_tahunan->save();
        return redirect("/budget/cashflow/detail-cashflow?id=".$request->budget_tahunan_id);
    }

    public function itemcashflow(Request $request){
        $budget = BudgetTahunan::find($request->budget);
        $itempekerjaan = Itempekerjaan::where("code",$request->id)->first();
        $user = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        $nilai = 0;

        $check = Itempekerjaan::find($itempekerjaan->id);
        $budget_detail = BudgetDetail::where("itempekerjaan_id",$check->id)->where("budget_id",$budget->budget->id)->get();
        if ( count($budget_detail) > 0 ){
            foreach( $budget_detail as $key => $value ){
                $nilai = $nilai + $value->volume * $value->nilai;
            }
        }
        foreach ($check->child_item as $key => $value) {
            $budget_detail = BudgetDetail::where("itempekerjaan_id",$value->id)->where("budget_id",$budget->budget->id)->get();
            if ( count($budget_detail) > 0 ){
                foreach( $budget_detail as $key => $value ){
                    $nilai = $nilai + $value->volume * $value->nilai;
                }
            }
        }

        $nilai_budget_awal = $nilai;
        return view("budget::cashflow_item",compact("budget","itempekerjaan","user","project","nilai_budget_awal"));
    }

    public function revitemcashflow(Request $request){
        $budget = BudgetTahunan::find($request->budget);
        $itempekerjaan = Itempekerjaan::where("code",$request->id)->first();
        $user = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        return view("budget::rev_cashflow_item",compact("budget","itempekerjaan","user","project"));
    }

    public function newitemcashflow(Request $request){
        $budget = BudgetTahunan::find($request->id);
        $user = \Auth::user();
        $itempekerjaan = Itempekerjaan::where("parent_id",null)->get();
        $project = Project::find($request->session()->get('project_id'));
        return view("budget::newcashflow_item",compact("budget","user","itempekerjaan","project"));
    }

    public function savecashflow(Request $request){
        foreach ($request->item_id as $key => $value) {
            if ( $request->Volume_[$key] != "" ){

                if ( $request->budgetdetail[$key] != "" ){
                    if ( $request->Volume_[$key] != "" && $request->nilai_[$key] != "" ){
                        $budgetDetail = BudgetTahunanDetail::find($request->budgetdetail[$key]);
                        $budgetDetail->nilai = str_replace(",", "",$request->nilai_[$key]);
                        $budgetDetail->volume = str_replace(",", "",$request->Volume_[$key]);
                        $budgetDetail->satuan = $request->satuan_[$key];
                        $budgetDetail->save();
                    }
                }else{
                    if ( $request->Volume_[$key] != "" && $request->nilai_[$key] != "" ){
                        $budgetDetail = new BudgetTahunanDetail;
                        $budgetDetail->budget_tahunan_id = $request->budget_id;
                        $budgetDetail->itempekerjaan_id = $request->item_id[$key];
                        $budgetDetail->nilai = str_replace(",", "",$request->nilai_[$key]);
                        $budgetDetail->volume = str_replace(",", "",$request->Volume_[$key]);
                        $budgetDetail->satuan = $request->satuan_[$key];
                        $budgetDetail->save();
                    }
                }

            }
        }
        return redirect("budget/cashflow/detail-cashflow/?id=".$request->budget_id);
    }

    public function viewcashflow(Request $request){
        $budget = BudgetTahunan::find($request->budget);
        $itempekerjaan = Itempekerjaan::where("code",$request->id)->first();
        $user = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        return view("budget::cashflow_view_item",compact("budget","itempekerjaan","user","project"));
    }

    public function updateitemcashflow(Request $request){
        foreach ($request->item_id as $key => $value) {
            $budgetDetail = BudgetTahunanDetail::find($request->item_id[$key]);
            $budgetDetail->nilai = $request->nilai_[$key];
            $budgetDetail->volume = $request->volume_[$key];
            $budgetDetail->satuan = $request->satuan_[$key];
            $budgetDetail->save();
        }
        return redirect("budget/cashflow/detail-cashflow/?id=".$request->budget_id);
    }

    public function savemonthly(Request $request){
        $budgetperiode = new BudgetTahunanPeriode;
        $budgetperiode->budget_id = $request->budget_tahunan_id;
        $budgetperiode->itempekerjaan_id = $request->item_id_monthly;
        $budgetperiode->januari = $request->januari;
        $budgetperiode->februari = $request->februari;
        $budgetperiode->maret = $request->maret;
        $budgetperiode->april = $request->april;
        $budgetperiode->mei = $request->mei;
        $budgetperiode->juni = $request->juni;
        $budgetperiode->juli = $request->juli;
        $budgetperiode->agustus = $request->agustus;
        $budgetperiode->september = $request->september;
        $budgetperiode->oktober = $request->oktober;
        $budgetperiode->november = $request->november;
        $budgetperiode->desember = $request->desember;
        $budgetperiode->save();
        
        return response()->json( ["status" => "0"]);
    }

    public function updatemonthly(Request $request){
        $budgetperiode = BudgetTahunanPeriode::find($request->id);
        $budgetperiode->januari = $request->jan;
        $budgetperiode->februari = $request->feb;
        $budgetperiode->maret = $request->mar;
        $budgetperiode->april = $request->apr;
        $budgetperiode->mei = $request->mei;
        $budgetperiode->juni = $request->jun;
        $budgetperiode->juli = $request->jul;
        $budgetperiode->agustus = $request->agu;
        $budgetperiode->september = $request->sept;
        $budgetperiode->oktober = $request->okt;
        $budgetperiode->november = $request->nov;
        $budgetperiode->desember = $request->des;
        $status = $budgetperiode->save();
        if ( $status ){
            return response()->json( ["status" => "0"]);
        }else{
            return response()->json( ["status" => "1"] );
        }
    }

    public function deletemonthly(Request $request){
        $budgetperiode = BudgetTahunanPeriode::find($request->id);
        $status = $budgetperiode->delete();
        if ( $status ){
            return response()->json( ["status" => "0"]);
        }else{
            return response()->json( ["status" => "1"] );
        }
    }

    public function approval_cashflow(Request $request){
        $budget = $request->id;
        $class  = "BudgetTahunan";
        $approval = \App\Helpers\Document::make_approval('Modules\Budget\Entities\BudgetTahunan',$budget);
        return response()->json( ["status" => "0"] );
    }

    public function createrobot(Request $request){
        $project = Project::find($request->id);        
        $budget = $project->budget;
        $spk = $project->spks;
        $budget_devcost = "1";
        $budget_concost = "2";

     

        

        foreach ($spk as $key => $value) {
            # code...
            $spk = \Modules\Spk\Entities\Spk::find($value->id);
            $nilai = $spk->nilai;
            if ( ($spk->progresses != "" )) {
                if ( isset($spk->progresses->first()->itempekerjaan)) {
                    $pekerjaan = \Modules\Pekerjaan\Entities\Itempekerjaan::where("code",$spk->progresses->first()->itempekerjaan->code.".00")->get()->first();
                    if ( $pekerjaan->group_cost == "1"){
                        $budgetdetail = \Modules\Budget\Entities\BudgetDetail::where("itempekerjaan_id",$pekerjaan->id)->where("budget_id",$budget_devcost)->get();
                        if ( count($budgetdetail) > 0 ){ 
                            $exp = explode("/", $spk->no);  
                            if ( $exp[5] == "17"){                                              
                                echo $spk->nilai_bap."<>".$spk->id."<>".$nilai."<>".$spk->progresses->first()->itempekerjaan->code.".00"."<>".$pekerjaan->id."<>".($spk->nilai - round($spk->nilai_bap));
                                echo "<br/>";

                           }           
                        }
                    }                    
                    
                }
                
            }
            
        }
    }

    public function revisibudget(Request $request){
        $budget = Budget::find($request->id);
        $project = $budget->project;
        $user = \Auth::user();
        return view("budget::create_revision",compact("budget","project","user"));
    }

    public function saverevisi(Request $request){
        $budget_awal = Budget::find($request->budget_id);
        $budget_awal->deleted_at = date("Y-m-d H:i:s.u");
        $budget_awal->save();

        $budget = new Budget;
        //$number = \App\Helpers\Document::new_number('BDG-R', $budget_awal->department_id);
        $number = $budget_awal->no."-R".(Budget::where("parent_id",$budget_awal->id)->count() + 1 );
        $budget->pt_id = $budget_awal->pt_id;
        $budget->department_id = $budget_awal->department_id;
        $budget->project_id = $budget_awal->project_id;        
        $budget->project_kawasan_id = $budget_awal->project_kawasan_id;        
        $budget->no = $number;
        $budget->start_date = $budget_awal->start_date;
        $budget->end_date = $budget_awal->end_date;
        $budget->description = $request->description;
        $budget->parent_id = $budget_awal->id;
        $budget->created_by = \Auth::user()->id;
        $budget->save();

        /*foreach ($budget_awal->details as $key => $value) {
            $budgetDetail = new BudgetDetail;
            $budgetDetail->budget_id = $budget->id;
            $budgetDetail->itempekerjaan_id = $value->itempekerjaan_id;
            $budgetDetail->nilai = $value->nilai;
            $budgetDetail->volume = $value->volume;
            $budgetDetail->satuan = $value->satuan;
            $budgetDetail->save();
        }*/
        
        $itempekerjaan = Itempekerjaan::get();
        foreach ($itempekerjaan as $key => $value) {
            if ( $value->parent_id == null ){

                if ( $budget_awal->project_kawasan_id == "" ){
                    if ( $value->code == "240" ){
                        foreach ($value->child_item as $key2 => $value2) {
                            $budgetDetail = new BudgetDetail;
                            $budgetDetail->budget_id = $budget->id;
                            $budgetDetail->itempekerjaan_id = $value2->id;
                            $budgetDetail->nilai = str_replace(",", "", $value2->nilai_master_satuan);
                            $budgetDetail->volume = 0;
                            if ( $value2->details != "" ){
                                $budgetDetail->satuan = $value2->details->satuan;
                            }else{
                                $budgetDetail->satuan = 'ls';
                            }
                            $budgetDetail->save();
                        }
                    }
                }else{
                    if ( $value->code != "240" ){
                        if ( $value->group_cost == 1 ){
                            $budgetDetail = new BudgetDetail;
                            $budgetDetail->budget_id = $budget->id;
                            $budgetDetail->itempekerjaan_id = $value->id;
                            $budgetDetail->nilai = str_replace(",", "", $value->nilai_master_satuan);
                            $budgetDetail->volume = 0;
                            if ( $value->details != "" ){
                                $budgetDetail->satuan = $value->details->satuan;
                            }else{
                                $budgetDetail->satuan = 'ls';
                            }
                            $budgetDetail->save();
                        } elseif ( $value->id == 292 ){
                            $budgetDetail = new BudgetDetail;
                            $budgetDetail->budget_id = $budget->id;
                            $budgetDetail->itempekerjaan_id = $value->id;
                            $budgetDetail->nilai = str_replace(",", "", $value->nilai_master_satuan);
                            $budgetDetail->volume = 0;
                            if ( $value->details != "" ){
                                $budgetDetail->satuan = $value->details->satuan;
                            }else{
                                $budgetDetail->satuan = 'ls';
                            }
                            $budgetDetail->save();
                        }
                    }                    
                }
            }
        }
        //$approval = \App\Helpers\Document::make_approval('Modules\Budget\Entities\Budget',$budget->id);
        return redirect("budget/detail/?id=".$budget->id);
    }

    public function detailrevisi(Request $request){
        $budget = Budget::find($request->id);
        $parent = Budget::find($budget->parent_id);
        $project = $budget->project;
        $user = \Auth::user();
        return view("budget::revisi",compact("budget","project","user","parent"));
    }

    public function itemrevisi(Request $request){
        $budget = Budget::find($request->id);
        $itempekerjaan_id = Itempekerjaan::where("id",$request->coa)->get()->first()->id;
        $itempekerjaan = Itempekerjaan::find($itempekerjaan_id);
        $project = $budget->project;
        $user = \Auth::user();
        return view("budget::item_revisi",compact("budget","user","project","itempekerjaan"));
    }

    public function saveitemrevisi(Request $request){
        foreach ($request->item_id as $key => $value) {
            if ( $request->Volume_[$key] != ""  ){                    
                if ( $request->budgetdetail[$key] != "" ){
                    $budgetDetail = BudgetDetail::find($request->budgetdetail[$key]);
                    $budgetDetail->nilai = str_replace(",", "",$request->nilai_[$key]);
                    $budgetDetail->volume = str_replace(",", "",$request->Volume_[$key]);
                    $budgetDetail->satuan = $request->satuan_[$key];
                    $budgetDetail->save();
                }else{
                    $budgetDetail = new BudgetDetail;
                    $budgetDetail->budget_id = $request->budget_id;
                    $budgetDetail->itempekerjaan_id = $request->item_id[$key];
                    $budgetDetail->nilai = str_replace(",", "",$request->nilai_[$key]);
                    $budgetDetail->volume = str_replace(",", "",$request->Volume_[$key]);
                    $budgetDetail->satuan = $request->satuan_[$key];
                    $budgetDetail->save();
                }
                
            }
            
        }
        
        return redirect("budget/show-budgetrevisi/?id=".$request->budget_id);
    }

    public function listrevisi(Request $request){
        $budget = Budget::where("parent_id",$request->id)->get();
        $user = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        return view("budget::index_revisi",compact("project","user","budget"));
    }

    public function savecaryyover(Request $request){

        if ( $request->settospk ){

            foreach ($request->settospk as $key => $value) {
                if ( $request->settospk[$key] != "" ){
                    $budgetDetail = new BudgetCarryOver;
                    $budgetDetail->budget_tahunan_id = $request->budget_id;
                    $budgetDetail->spk_id = $request->settospk[$key];
                    $budgetDetail->save();
                }
            }
        }
         return redirect("budget/cashflow/detail-cashflow/?id=".$request->budget_id);
    }

    public function deletecarryover(Request $request){
        $budgetDetail = BudgetCarryOver::find($request->id);
        $status = $budgetDetail->delete();
        if ( $status ){
            return response()->json( ["status" => "0"]);
        }else{
            return response()->json( ["status" => "1"] );
        }
    }

    public function createhpp(Request $request){
        echo $request->id;
        $project = Project::find($request->id);
        foreach ($project->budgets as $key => $value) {
            $budget = Budget::find($value->id);
            $BudgetHistoryHpp = new BudgetHistoryHpp;

            $BudgetHistoryHpp->project_id = $request->id;
            $BudgetHistoryHpp->project_kawasan_id = $budget->project_kawasan_id;
            $BudgetHistoryHpp->budget_id = $budget->id;
            if ( $budget->project_kawasan_id == "" ){                
                $BudgetHistoryHpp->luas_netto = "0";
                $BudgetHistoryHpp->luas_brutto = $budget->project_kawasan_id;
            }else{
                $projectkawasan = ProjectKawasan::find($budget->project_kawasan_id);
                $BudgetHistoryHpp->luas_netto = $projectkawasan->lahan_sellable;
                $BudgetHistoryHpp->luas_brutto = $projectkawasan->lahan_luas;
            }
            $BudgetHistoryHpp->save();
        }
    }

    public function additemrevisi(Request $request){
        $budget = Budget::find($request->id);
        $user = \Auth::user();
        $itempekerjaan = Itempekerjaan::where("parent_id",null)->get();
        $project = Project::find($request->session()->get('project_id'));
        return view("budget::newitem_revisi",compact("budget","user","itempekerjaan","project"));
    }

    public function savenewitemrevisi(Request $request){

        foreach ($request->item_id as $key => $value) {
            if ( $request->volume_[$key] != ""){
                $budgetDetail = new BudgetDetail;
                $budgetDetail->budget_id = $request->budget_id;
                $budgetDetail->itempekerjaan_id = $request->item_id[$key];
                $budgetDetail->nilai = $request->nilai_[$key];
                $budgetDetail->volume = $request->volume_[$key];
                $budgetDetail->satuan = $request->satuan_[$key];
                $budgetDetail->save();
            }
            
        }
        return redirect("/budget/show-budgetrevisi?id=".$request->budget_id);
    }

    public function savenewitemcashflow(Request $request){

        foreach ($request->item_id as $key => $value) {
            if ( $request->volume_[$key] != ""){
                $budgetDetail = new BudgetTahunanDetail;
                $budgetDetail->budget_tahunan_id = $request->budget_id;
                $budgetDetail->itempekerjaan_id = $request->item_id[$key];
                $budgetDetail->nilai = str_replace(",", "",$request->nilai_[$key]);
                $budgetDetail->volume = $request->volume_[$key];
                $budgetDetail->satuan = $request->satuan_[$key];
                $budgetDetail->save();
            }
            
        }
        return redirect("/budget/cashflow/detail-cashflow?id=".$request->budget_id);
    }

    public function saverevitem(Request $request){
        foreach ($request->item_id as $key => $value) {
            if ( $request->Volume_[$key] != "" ){

                if ( $request->budgetdetail[$key] != "" ){
                    $budgetDetail = BudgetTahunanDetail::find($request->budgetdetail[$key]);
                    $budgetDetail->nilai = str_replace(",", "",$request->nilai_[$key]);
                    $budgetDetail->volume = $request->Volume_[$key];
                    $budgetDetail->satuan = $request->satuan_[$key];
                    $budgetDetail->save();
                }else{
                    $budgetDetail = new BudgetTahunanDetail;
                    $budgetDetail->budget_tahunan_id = $request->budget_id;
                    $budgetDetail->itempekerjaan_id = $request->item_id[$key];
                    $budgetDetail->nilai = str_replace(",", "",$request->nilai_[$key]);
                    $budgetDetail->volume = $request->Volume_[$key];
                    $budgetDetail->satuan = $request->satuan_[$key];
                    $budgetDetail->save();
                }

            }
        }

/*        $document = BudgetTahunan::find($request->budget_id);
        $apprival = $document->approval->id;
        $approval = Approval::find($apprival);
        if ( $approval != ""){
            $approval->approval_action_id = "1";
            $approval->save();

            if ( count($approval->histories) > 0 ){
                foreach ($approval->histories as $key => $value) {
                    $histories = ApprovalHistory::find($value->id);
                    $histories->approval_action_id = "1";
                    $histories->save();
                }
            }else{
                $approval_references = \Modules\Approval\Entities\ApprovalReference::where('document_type', 'BudgetTahunan')
                                    ->where('project_id', session('project_id') )
                                    //->where('pt_id', $pt_id )
                                    ->where('min_value', '<=', $approval->total_nilai)
                                    //->where('max_value', '>=', $approval->total_nilai)
                                    ->orderBy('no_urut','ASC')
                                    ->get();
                foreach ($approval_references as $key => $each)  {
                    $user_level = \App\User::find($each->user_id)->details->first()->user_level;
                    
                        $document->approval_histories()->create([
                        'no_urut' => $each->no_urut,
                        'user_id' => $each->user_id,
                        'approval_action_id' => 1, // open
                        'approval_id' => $approval->id
                         ]);
                         
                }
            }
        }else{
            $budget = $request->id;
            $class  = "BudgetTahunan";
            $approval = \App\Helpers\Document::make_approval('Modules\Budget\Entities\BudgetTahunan',$budget);
        }*/

       
        return redirect("budget/cashflow/detail-cashflow/?id=".$request->budget_id);
    }

    public function edititem(Request $request){
        $itempekerjaan = Itempekerjaan::find($request->item);
        $budget = Budget::find($request->id);
        $user = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        return view("budget::item_global",compact("user","project","budget","itempekerjaan"));
    }

    public function saveitem(Request $request){
        foreach ($request->item_id as $key => $value) {
            if ( $request->Volume_[$key] != "" ){

                if ( $request->budgetdetail[$key] != "" ){
                    $budgetDetail = BudgetDetail::find($request->budgetdetail[$key]);
                    $budgetDetail->nilai = str_replace(",", "",$request->nilai_[$key]);
                    $budgetDetail->volume = $request->Volume_[$key];
                    $budgetDetail->satuan = $request->satuan_[$key];
                    $budgetDetail->save();
                }else{
                    $budgetDetail = new BudgetDetail;
                    $budgetDetail->budget_id = $request->budget_id;
                    $budgetDetail->itempekerjaan_id = $request->item_id[$key];
                    $budgetDetail->nilai = str_replace(",", "",$request->nilai_[$key]);
                    $budgetDetail->volume = $request->Volume_[$key];
                    $budgetDetail->satuan = $request->satuan_[$key];
                    $budgetDetail->save();
                }

            }
        }
        return redirect("budget/detail/?id=".$request->budget_id);
    }

    public function approval_history(Request $request){
        $budget_tahunan = BudgetTahunan::find($request->id);
        $user = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        return view("budget::approval_history",compact("user","project","budget_tahunan"));
    }

    public function draft(Request $request){
        $budget = Budget::find($request->id);
        $budget_draft = $budget->draft;
        $user = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        return view("budget::budget_draft",compact("user","project","budget_draft","budget"));
    }

    public function updateapproval(Request $request){

        
        $approval = Approval::find($request->approval_id);
        $approval->approval_action_id = "1";
        $approval->save();

        $histories = $approval->histories;
        foreach ($histories as $key => $value) {
           $approval_history = ApprovalHistory::find($value->id);
           $approval_history->approval_action_id = "1";
           $approval_history->save();
        }
        return response()->json( ["status" => "0"]);
    }

    public function approvalhistory(Request $request){
        $budget = Budget::find($request->id);
        $approval = $budget->approval;
        $user = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        return view("budget::approval_history_global",compact("budget","approval","user","project"));
    }

    public function reapproval(Request $request){


        $budget = Budget::find($request->id);
        $approval = \App\Approval::find($budget->approval->id);
        $document = $approval->document;

        $project = Project::find($request->session()->get('project_id'));
        $approval_references = \Modules\Approval\Entities\ApprovalReference::where('document_type', "Budget")
            ->where('project_id', $project->id )
            //->where('pt_id', $pt_id )
            ->where('min_value', '<=', $budget->nilai)
            //->where('max_value', '>=', $approval->total_nilai)
            ->orderBy('no_urut','ASC')
            ->get();

        foreach ($approval_references as $key => $each) {
            # code...
            $user = \Modules\User\Entities\User::find($each->user_id);
            if ( $budget->approval->histories != "" ){
                $cek = $budget->approval->histories->where("user_id",$each->user_id);
                if ( count($cek) <= 0 ){
                    $document->approval_histories()->create([
                        'no_urut' => $each->no_urut,
                        'user_id' => $each->user_id,
                        'approval_action_id' => 1, // open
                        'approval_id' => $approval->id,
                        'no_urut' => $each->no_urut
                    ]);
                }
            }else{
                $document->approval_histories()->create([
                    'no_urut' => $each->no_urut,
                    'user_id' => $each->user_id,
                    'approval_action_id' => 1, // open
                    'approval_id' => $approval->id,
                    'no_urut' => $each->no_urut
                ]);
            }
            
        }
        return response()->json( ["status" => "0"]);
    }

    public function referensi(Request $request){
        $budgetdetail = BudgetDetail::find($request->id);
        $user = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        $itempekerjaan = $budgetdetail->itempekerjaan;
        return view("budget::item_referensi",compact("budgetdetail","project","user","itempekerjaan"));
    }

    public function itemrevisisavet(Request $request){
        $budget = Budget::find($request->budget_id);
        foreach ($request->item_id as $key => $value) {
            $itempekerjaan = Itempekerjaan::find($request->item_id[$key]);
            if ( $request->satuan_[$key] != "" ){
                $budgetdetail = new BudgetDetail;
                $budgetdetail->budget_id = $budget->id;
                $budgetdetail->itempekerjaan_id = $request->item_id[$key];
                $budgetdetail->nilai = str_replace(",", "", $request->nilai_[$key]);
                $budgetdetail->volume = str_replace(",", "", $request->volume_[$key]);
                $budgetdetail->satuan = $request->satuan_[$key];
                $budgetdetail->save();                
            }
        }
        return redirect("budget/detail/?id=".$request->budget_id);
    }

    public function itemsavemonthlyco(Request $request){
        $user = \Auth::user();
        $carry_over_cashflow = new BudgetCarryOverCashflow;
        $carry_over_cashflow->budget_carry_over_id = $request->item_id_monthly_co;
        $carry_over_cashflow->created_by = $user->id;
        $carry_over_cashflow->januari = $request->januari_co;
        $carry_over_cashflow->februari = $request->februari_co;
        $carry_over_cashflow->maret = $request->maret_co;
        $carry_over_cashflow->april = $request->april_co;
        $carry_over_cashflow->mei = $request->mei_co;
        $carry_over_cashflow->juni = $request->juni_co;
        $carry_over_cashflow->juli = $request->juli_co;
        $carry_over_cashflow->agustus = $request->agustus_co;
        $carry_over_cashflow->september = $request->september_co;
        $carry_over_cashflow->oktober = $request->oktober_co;
        $carry_over_cashflow->november = $request->november_co;
        $carry_over_cashflow->desember = $request->desember_co;
        $carry_over_cashflow->save();
        return redirect("budget/cashflow/detail-cashflow/?id=".$request->budget_tahunan_id);
    }

    public function cashflowconcost(Request $request){
        $budget_tahunan = BudgetTahunan::find($request->id);
        $user = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        return view("budget::item_tahunan_concost",compact("user","budget_tahunan","project"));
    }

    public function saveitemconcost(Request $request){
        $user = \Auth::user();
        $budget_tahunan = BudgetTahunan::find($request->budget_tahunan_id);
        foreach ($request->item_id_ as $key => $value) {  
                 
            $budget_tahunan_detail = BudgetTahunanDetail::find($request->item_id_[$key]);
            $budget_tahunan_detail->volume = str_replace(",", "", $request->volume_[$key]);
            $budget_tahunan_detail->save();
        }   

        if ( $budget_tahunan->budget_unit != "" ){
            foreach ($budget_tahunan->budget_unit as $key => $value) {
                $del_budget_unit = BudgetTahunanUnit::find($value->id);
                $del_budget_unit = $del_budget_unit->delete();
            }
        }

        foreach ($request->unit_type_ as $key => $value) {
            $unit_type = UnitType::find($request->unit_type_[$key]);
            if ( $request->harga_satuan[$key] != "" ){
                $budget_tahunan_unit = new BudgetTahunanUnit;
                $budget_tahunan_unit->budget_tahunan_id = $request->budget_tahunan_id;
                $budget_tahunan_unit->harga_satuan = str_replace(",", "", $request->harga_satuan[$key]);
                $budget_tahunan_unit->unit_type_id = $request->unit_type_[$key];
                $budget_tahunan_unit->volume = $request->total_unit_type[$key] * $unit_type->luas_bangunan;
                $budget_tahunan_unit->satuan = 'm2';
                $budget_tahunan_unit->total_unit = $request->total_unit_type[$key];
                $budget_tahunan_unit->created_by = $user->id;
                $budget_tahunan_unit->save();

                $budget_tahunan_unit_detail = new BudgetTahunanUnitPeriode;
                $budget_tahunan_unit_detail->budget_tahunan_unit_id = $budget_tahunan_unit->id;
                $budget_tahunan_unit_detail->created_by = $user->id;
                $budget_tahunan_unit_detail->januari = $request->januari_[$key];
                $budget_tahunan_unit_detail->februari = $request->februari_[$key];
                $budget_tahunan_unit_detail->maret = $request->maret_[$key];
                $budget_tahunan_unit_detail->april = $request->april_[$key];
                $budget_tahunan_unit_detail->mei = $request->mei_[$key];
                $budget_tahunan_unit_detail->juni = $request->juni_[$key];
                $budget_tahunan_unit_detail->juli = $request->juli_[$key];
                $budget_tahunan_unit_detail->agustus = $request->agustus_[$key];
                $budget_tahunan_unit_detail->september = $request->september_[$key];
                $budget_tahunan_unit_detail->oktober = $request->oktober_[$key];
                $budget_tahunan_unit_detail->november = $request->november_[$key];
                $budget_tahunan_unit_detail->desember = $request->desember_[$key];
                $budget_tahunan_unit_detail->save();
            }
        }
        
        if ( $budget_tahunan->budget_unit->count() > 0 ){
            foreach ($request->item_id_ as $key => $value) {  
                 
                $budget_tahunan_detail = BudgetTahunanDetail::find($request->item_id_[$key]);
                $budget_tahunan_detail->volume = str_replace(",", "", $request->volume_[$key]);
                $budget_tahunan_detail->nilai = $budget_tahunan->budget_unit->avg("harga_satuan");
                $budget_tahunan_detail->save();
            } 
        }
        return redirect("budget/cashflow/detail-cashflow/?id=".$budget_tahunan_detail->budget_tahunan_id);
    }


    public function viewcf(Request $request){
        $user = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        $budget_tahunan = BudgetTahunan::find($request->id);
        return view("budget::item_cf",compact("user","project","budget_tahunan"));
    }

    public function referensicf(Request $request){
        $itempekerjaan = Itempekerjaan::find($request->id);
        $harga = $itempekerjaan->harga;
        $user = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        $budget_tahunan = BudgetTahunan::find($request->budget_tahunan_id);
        return view("budget::item_referensi_cf",compact("user","project","itempekerjaan","harga","budget_tahunan"));
    }

    public function revitemcashflowcons(Request $request){
        $budget_tahunan = BudgetTahunan::find($request->budget);
        $user = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        $volume_master = 0;
        foreach ($budget_tahunan->budget->details as $key => $value) {
            if ( $value->itempekerjaan->group_cost == 2 ){
                $volume_master = $volume_master + $value->volume;
            }
        }

        return view("budget::item_tahunan_concost",compact("user","project","budget_tahunan","volume_master"));
    }

    public function addcarryover(Request $request){
        $array_spk_co = array();
        $budget_tahunan = BudgetTahunan::find($request->id);
        $user = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        $start = 0;

        if ( $budget_tahunan->budget->kawasan == null ){
            $asset_id = $project->id;
        }else{
            $asset_id = $budget_tahunan->budget->kawasan->id; 
        }

        $spk = $budget_tahunan->budget->project->spks;
        foreach ($spk as $key => $value) {

            if ( $value->itempekerjaan != "" ){
                //if ( $value->itempekerjaan->group_cost == 1 ){
                    if ( $value->details != "" ){
                        foreach ($value->details as $key2 => $value2) {
                            if ( $value2->asset != "" ){
                                if ( $value2->asset->id == $asset_id ){
                                    if ( $value->baps != "" ){
                                        $bayar = ( $value->terbayar_verified / 1.1 );
                                    }else{
                                        $bayar = 0;
                                    }

                                    $sisa = ( $value->nilai + $value->nilai_vo) - $bayar;
                                    if ( $sisa > 0 ){
                                        $array_spk_co[$start] = array(
                                            "no_spk" => $value->no,
                                            "id_spk" => $value->id,
                                            "coa" => $value->itempekerjaan->code,
                                            "nama_pekerjaan" => $value->itempekerjaan->name,
                                            "nilai_spk" => $value->nilai + $value->nilai_vo,
                                            "terbayar" => $bayar,
                                            "sisa" => $value->nilai - $bayar,
                                            "nama_spk" => $value->name
                                        );
                                        $start++;
                                    }                            
                                }
                            }
                            
                        }
                    }
                //}            
            }
        }

        return view("budget::item_co",compact("user","project","budget_tahunan","array_spk_co"));
    }

    public function savecashouttype(Request $request){

        $budget_tahunan_periode = BudgetTahunanUnitPeriode::find($request->budget_unit_id);
        $array_bulanan = array(
            'januari' => 1,
            'februari' => 2,
            'maret' => 3,
            'april' => 4,
            'mei' => 5,
            'juni' => 6,
            'juli' => 7,
            'agustus' => 8,
            'september' => 9,
            'oktober' => 10,
            'november' => 11,
            'desember' => 12
        );

        $budget_tahunan_unit_periode_detail = new BudgetTahunanUnitPeriodeDetail;
        $budget_tahunan_unit_periode_detail->budget_tahunan_periode = $request->budget_unit_id;
        $budget_tahunan_unit_periode_detail->month = $array_bulanan[$request->budget_unit_bulan];
        $budget_tahunan_unit_periode_detail->januari = $request->januari_unit;
        $budget_tahunan_unit_periode_detail->februari = $request->februari_unit;
        $budget_tahunan_unit_periode_detail->maret = $request->maret_unit;
        $budget_tahunan_unit_periode_detail->april = $request->april_unit;
        $budget_tahunan_unit_periode_detail->mei = $request->mei_unit;
        $budget_tahunan_unit_periode_detail->juni = $request->juni_unit;
        $budget_tahunan_unit_periode_detail->juli = $request->juli_unit;
        $budget_tahunan_unit_periode_detail->agustus = $request->agustus_unit;
        $budget_tahunan_unit_periode_detail->september = $request->september_unit;
        $budget_tahunan_unit_periode_detail->oktober = $request->oktober_unit;
        $budget_tahunan_unit_periode_detail->november = $request->november_unit;
        $budget_tahunan_unit_periode_detail->desember = $request->desember_unit;
        $budget_tahunan_unit_periode_detail->save();

        return redirect("/budget/cashflow/detail-cashflow?id=".$budget_tahunan_periode->budget_unit->budget_tahunan->id);
    }

    public function itemviewconcost(Request $request){
        $budget_unit = BudgetTahunanUnitPeriode::find($request->id);
        $data['status'] = 1;
        $data['total'] = 0;
        $array_bulan = array(
            'januari' => 1,
            'februari' => 2, 
            'maret' => 3,
            'april' => 4,
            'mei' => 5,
            'juni' => 6,
            'juli' => 7,
            'agustus' => 8,
            'september' => 9,
            'oktober' => 10,
            'november' => 11,
            'desember' => 12
        );


        $array_cashout = array(
            'januari' => 0,
            'februari' => 0,
            'maret' => 0,
            'april' => 0,
            'mei' => 0,
            'juni' => 0,
            'juli' => 0,
            'agustus' => 0,
            'september' => 0,
            'oktober' => 0,
            'november' => 0,
            'desember' => 0
        );

        foreach ($budget_unit->details as $key => $value) {
            if ( $value->month == $array_bulan[$request->bulan] ){
                $array_cashout = array(
                    'januari' => $value->januari,
                    'februari' => $value->februari,
                    'maret' => $value->maret,
                    'april' => $value->april,
                    'mei' => $value->mei,
                    'juni' => $value->juni,
                    'juli' => $value->juli,
                    'agustus' => $value->agustus,
                    'september' => $value->september,
                    'oktober' => $value->oktober,
                    'november' => $value->november,
                    'desember' => $value->desember
                );
                $data['status'] = "0";
                $data['array_cashout'] = $array_cashout;
                $data['total'] = $value->januari + $value->februari + $value->maret + $value->april + $value->mei + $value->juni + $value->juli + $value->agustus + $value->september + $value->oktober + $value->november + $value->desember ;
             }
        }

        echo json_encode($data);
    }

    public function removeco(Request $request ){
        $budget_carry_over = BudgetCarryOver::find($request->id);
        $status = $budget_carry_over->delete();
        if ( $status ){
            return response()->json( ["status" => "0"] );
        }else{
            return response()->json( ["status" => "1"] );
        }
    }

    public function removecoco(Request $request){
        $budget_carry_over = BudgetCarryOverCashflow::find($request->id);
        $status = $budget_carry_over->delete();
        if ( $status ){
            return response()->json( ["status" => "0"] );
        }else{
            return response()->json( ["status" => "1"] );
        }
    }

    public function getCarryOverDC(Request $request){
        $budgettahunan = BudgetTahunan::find($request->id);
        $array_carryover = array();
        $nilai = 0;

        foreach ($budgettahunan->carry_over as $key => $value) {
            if ( $value->spk->itempekerjaan != "" ){
                if ( $value->spk->itempekerjaan->group_cost == 1 ){
                    if ( $value->hutang_bayar != "" ){
                        $nilai = $nilai + $value->hutang_bayar;
                    }else{
                        $nilai = $nilai + ( $value->sisa );
                    }
                }
            }            
        }

        return response()->json( ["nilai" => $nilai ] );
    }

    public function getCarryOverCC(Request $request){
        $budgettahunan = BudgetTahunan::find($request->id);
        $array_carryover = array();
        $nilai = 0;

        foreach ($budgettahunan->carry_over as $key => $value) {
            if ( $value->spk->itempekerjaan != "" ){
                if ( $value->spk->itempekerjaan->group_cost == 2 ){
                    if ( $value->hutang_bayar != "" ){
                        $nilai = $nilai + $value->hutang_bayar;
                    }else{
                        $nilai = $nilai + ( $value->sisa );
                    }
                }
            }            
        }

        return response()->json( ["nilai" => $nilai ] );
    }

    public function getRencanaDC(Request $request){
        $budgettahunan = BudgetTahunan::find($request->id);
        $array_carryover = array();
        $nilai = 0;

        foreach ($budgettahunan->details as $key => $value) {
            if ( $value->itempekerjaan != "" ){
                if ( $value->itempekerjaan->group_cost == 1 ){
                   $nilai = $nilai + ($value->nilai * $value->volume );
                }
            }            
        }
        return response()->json( ["nilai" => $nilai ] );
    }

    public function tambahPekerjaan(Request $request){
        // $budget_project = new BudgetProjectPekerjaan;
        // $budget_project->name_pekerjaan = "jalan Utama";
        // $budget_project->itempekerjaan_id = 95;
        // $budget_project->volume = 1000;
        // $budget_project->nilai= 1000000;
        // $budget_project->satuan = "m2";
        // $budget_project->project_id = 86;
        // $budget_project->save();
        if($request->session()->get('project_id') == null){
            return redirect("");
        }
        $budget = Budget::find($request->id);
        $user   = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        $department = Department::get();
        $array = array (
            "6" => array("label" => "Disetujui", "class" => "label label-success"),
            "7" => array("label" => "Ditolak", "class" => "label label-danger"),
            "1" => array("label" => "Dalam Proses", "class" => "label label-warning")
        );
        
        $pekerjaan_project = BudgetProjectPekerjaan::where("project_id", $project->id)->orderBy("code","ASC")->get();
        $itempekerjaan = Itempekerjaan::get();
        $itempekerjaan_parent = Itempekerjaan::where('parent_id')->orderBy("code","ASC")->get();
        $satuan =CoaSatuan::get();
        // return $satuan;
        // $pekerjaan = BudgetProjectPekerjaan::all();
        // foreach ($pekerjaan as $key => $value) {
        //     # code...
        //     $test = BudgetProjectPekerjaan::find($value->id);
        //     $test->code = $value->itempekerjaan->code;
        //     $test->save();
        // }
        return view('budget::tambah_pekerjaan',compact("user","budget","project","department","array","pekerjaan_project","itempekerjaan","satuan","itempekerjaan_parent"));
    }

    public function itemPekerjaan_select2(Request $request){
        $itempekerjaan = Itempekerjaan::where("id",$request->parent)->first()->child_item;
        return response()->json(['itempekerjaan' => $itempekerjaan, 'parent_id' => $request->parent]);
    }

    public function satuan(Request $request){
        $satuan = Itempekerjaan::where("id",$request->itempekerjaan_id)->first()->item_satuan;
        return response()->json(['satuan' => $satuan]);
    }

    public function save_itempekerjaan(Request $request){
        $project = Project::find($request->session()->get('project_id'));
        $budget_id = $request->budget_id;
        // return $budget_id;
        for ($i=0; $i < count($request->data); $i++) { 
            # code...
            if($request->data[$i]['volume'] != 0 && $request->data[$i]['volume'] != ''){
                $pekerjaan_project = BudgetProjectPekerjaan::where("project_id", $project->id)->where("itempekerjaan_id", $request->data[$i]['item_pekerjaan'])->where("name_pekerjaan",$request->data[$i]['uraian'])->first();
                if($pekerjaan_project != ''){
                    $budget_detail = BudgetDetail::where("budget_id", $budget_id)->where("itempekerjaan_id", $request->data[$i]['item_pekerjaan'])->where("uraian_pekerjaan",$request->data[$i]['uraian'])->first();
                    // return $budget_detail;
                    if(isset($budget_detail)){
                        if($budget_detail->volume != $request->data[$i]['volume'] || $budget_detail->nilai != $request->data[$i]['harga_satuan']){
                            $budget_detail          = BudgetDetail::find($budget_detail);
                            $budget_detail->volume  = $request->data[$i]['volume'];
                            $budget_detail->nilai   = $request->data[$i]['harga_satuan'];
                            $budget_detail->save();

                            $budget_project_pekerjaan = BudgetProjectPekerjaan::find($pekerjaan_project->id);
                            $budget_project_pekerjaan->nilai = $budget_detail->nilai;
                            $budget_project_pekerjaan->save();

                            $budget_detail_history                      = new BudgetDetailHistory;
                            $budget_detail_history->budget_id           = $budget_detail->budget_id;
                            $budget_detail_history->itempekerjaan_id    = $budget_detail->itempekerjaan_id;
                            $budget_detail_history->nilai               = $budget_detail->nilai;
                            $budget_detail_history->volume              = $budget_detail->volume;
                            $budget_detail_history->satuan              = $budget_detail->satuan;
                            $budget_detail_history->description         = $budget_detail->bdescription;
                            $budget_detail_history->budget_detail_id    = $budget_detail->id;
                            $budget_detail_history->save();
                        }
                    }else{
                            $budget_detail                              = new BudgetDetail;
                            $budget_detail->itempekerjaan_id            = $request->data[$i]['item_pekerjaan'];
                            $budget_detail->uraian_pekerjaan            = $request->data[$i]['uraian'];
                            $budget_detail->volume                      = $request->data[$i]['volume'];
                            $budget_detail->satuan                      = $request->data[$i]['satuan'];
                            $budget_detail->nilai                       = $request->data[$i]['harga_satuan'];
                            $budget_detail->budget_project_pekerjaan_id = $pekerjaan_project->id;
                            $budget_detail->budget_id                   = $budget_id;
                            $budget_detail->save();
                            // return $budget_detail;
                            $budget_project_pekerjaan = BudgetProjectPekerjaan::find($pekerjaan_project->id);
                            $budget_project_pekerjaan->nilai = $budget_detail->nilai;
                            $budget_project_pekerjaan->save();

                            $budget_detail_history                      = new BudgetDetailHistory;
                            $budget_detail_history->budget_id           = $budget_detail->budget_id;
                            $budget_detail_history->itempekerjaan_id    = $budget_detail->itempekerjaan_id;
                            $budget_detail_history->nilai               = $budget_detail->nilai;
                            $budget_detail_history->volume              = $budget_detail->volume;
                            $budget_detail_history->satuan              = $budget_detail->satuan;
                            $budget_detail_history->description         = $budget_detail->bdescription;
                            $budget_detail_history->budget_detail_id    = $budget_detail->id;
                            $budget_detail_history->save();
                    }
                }else{
                    $budget_project_pekerjaan = new BudgetProjectPekerjaan;
                    $budget_project_pekerjaan->itempekerjaan_id = $request->data[$i]['item_pekerjaan'];
                    $budget_project_pekerjaan->name_pekerjaan = $request->data[$i]['uraian'];
                    $budget_project_pekerjaan->nilai = $request->data[$i]['harga_satuan'];
                    $budget_project_pekerjaan->volume = 0;
                    $budget_project_pekerjaan->satuan = $request->data[$i]['satuan'];
                    $budget_project_pekerjaan->project_id = $project->id;
                    $budget_project_pekerjaan->code = Itempekerjaan::where("id", $request->data[$i]['item_pekerjaan'])->first()->code;
                    $budget_project_pekerjaan->save();

                    $budget_detail                              = new BudgetDetail;
                    $budget_detail->itempekerjaan_id            = $request->data[$i]['item_pekerjaan'];
                    $budget_detail->uraian_pekerjaan            = $request->data[$i]['uraian'];
                    $budget_detail->volume                      = $request->data[$i]['volume'];
                    $budget_detail->satuan                      = $request->data[$i]['satuan'];
                    $budget_detail->nilai                       = $request->data[$i]['harga_satuan'];
                    $budget_detail->budget_project_pekerjaan_id = $budget_project_pekerjaan->id;
                    $budget_detail->budget_id                   = $budget_id;
                    $budget_detail->save();

                    $budget_detail_history                      = new BudgetDetailHistory;
                    $budget_detail_history->budget_id           = $budget_detail->budget_id;
                    $budget_detail_history->itempekerjaan_id    = $budget_detail->itempekerjaan_id;
                    $budget_detail_history->nilai               = $budget_detail->nilai;
                    $budget_detail_history->volume              = $budget_detail->volume;
                    $budget_detail_history->satuan              = $budget_detail->satuan;
                    $budget_detail_history->description         = $budget_detail->bdescription;
                    $budget_detail_history->budget_detail_id    = $budget_detail->id;
                    $budget_detail_history->save();
                }
            }
        }

        return response()->json(['success' => "Data berhasil di Simpan"]);
    }

    public function view_edit_tahunan(Request $request){
        $budget_tahunan_detail = BudgetTahunanDetail::where("budget_tahunan_id",$request->id)->get();
        $data = [];
        foreach ($budget_tahunan_detail as $key => $value) {
            # code...
            $arr = [
                'coa' => $value->itempekerjaans->code,
                'itempekerjaan' => $value->itempekerjaans->name,
                'uraian' => $value->uraian_pekerjaan,
                'volume_budget' => $value->volume_budget,
                'volume' => $value->volume,
                'satuan' => $value->satuan,
                'harga_satuan' => $value->nilai,
                'harga_subtotal' =>$value->volume*$value->nilai,
                'itempekerjaan_id' => $value->itempekerjaan_id,
                'budget_tahunan_detail_id' => $value->id,
            ];
            array_push($data, $arr);
        }

        return response()->json(['data' => $data]);
        // return datatables()->of($data)->toJson();
    }

    public function update_budget_tahunan_detail(Request $request){
        for ($i=0; $i < count($request->data); $i++) { 
            $budget_tahunan_detail = BudgetTahunanDetail::find($request->data[$i]['budget_tahunan_detail']);
            if($request->data[$i]['volume'] != $budget_tahunan_detail->volume || $request->data[$i]['harga_satuan'] != $budget_tahunan_detail->nilai){
                $budget_tahunan_detail->volume = $request->data[$i]['volume'];
                $budget_tahunan_detail->nilai = $request->data[$i]['harga_satuan'];
                $budget_tahunan_detail->save();

                $history_tahunan_detail = new BudgetTahunanDetailHistory;
                $history_tahunan_detail->budget_tahunan_detail_id = $budget_tahunan_detail->id;
                $history_tahunan_detail->itempekerjaan_id = $budget_tahunan_detail->itempekerjaan_id;
                $history_tahunan_detail->nilai = $request->data[$i]['harga_satuan'];
                $history_tahunan_detail->volume = $request->data[$i]['volume'];
                $history_tahunan_detail->satuan = $budget_tahunan_detail->satuan;
                $history_tahunan_detail->volume_budget = $budget_tahunan_detail->volume_budget;
                $history_tahunan_detail->uraian_pekerjaan = $budget_tahunan_detail->uraian_pekerjaan;
                $history_tahunan_detail->save();
            }
        }
        return response()->json(['success' => "Data berhasil di Simpan"]);    
    }

    public function view_bulanan(Request $request){
        $budget_tahunan_detail =  BudgetTahunanDetail::find($request->id);
        $data = [];
        $array_bulan = array(
            1 => 'januari',
            2 => 'februari', 
            3 => 'maret',
            4 => 'april',
            5 => 'mei',
            6 => 'juni',
            7 => 'juli',
            8 => 'agustus',
            9 => 'september',
            10 => 'oktober',
            11 => 'november',
            12 => 'desember'
        );
        foreach ($budget_tahunan_detail->detail_bulanan as $key => $value) {
            # code...
            $nilai = $value->nilai_persen;
            if($value->nilai_persen == null){
                $nilai = 0;
            }
            $arr = [
                'id' => $value->id,
                'id_bulan' => $value->bulan,
                'name_bulan' => $array_bulan[$value->bulan],
                'persentase' => $value->persen,
                'nilai' => $nilai,
            ];
            array_push($data, $arr);
        }
        $totpersen = $budget_tahunan_detail->detail_bulanan->sum("persen");
        $totbulanan = $budget_tahunan_detail->detail_bulanan->sum("nilai_persen");
        $subtotal = $budget_tahunan_detail->volume * $budget_tahunan_detail->nilai;
        return response()->json(['data' => $data, 'name_itempekerjaan' => $budget_tahunan_detail->itempekerjaans->name, 'subtotal' => number_format($subtotal,2), 'nilai_subtotal' => $subtotal, 'totpersen' => $totpersen, 'totbulanan' => $totbulanan]);
    }

    public function update_budget_tahunan_bulanan(Request $request){
        for ($i=0; $i < count($request->data); $i++) { 
            $budget_tahunan_bulanan = BudgetTahunanDetailBulanan::find($request->data[$i]['id']);
            if($request->data[$i]['persen'] != $budget_tahunan_bulanan->persen){
                $budget_tahunan_bulanan->persen = $request->data[$i]['persen'];
                $budget_tahunan_bulanan->nilai_persen = $request->data[$i]['nilai_persen'];
                $budget_tahunan_bulanan->save();

                $history_tahunan_detail_bulanan = new BudgetTahunanDetailBulananHistory;
                $history_tahunan_detail_bulanan->budget_tahunan_detail_bulanan_id = $budget_tahunan_bulanan->id;
                $history_tahunan_detail_bulanan->persen = $budget_tahunan_bulanan->persen;
                $history_tahunan_detail_bulanan->nilai_persen = $budget_tahunan_bulanan->nilai_persen;
                $history_tahunan_detail_bulanan->bulan = $budget_tahunan_bulanan->bulan;
                $history_tahunan_detail_bulanan->itempekerjaan_id = $budget_tahunan_bulanan->itempekerjaan_id;
                $history_tahunan_detail_bulanan->nilai =$budget_tahunan_bulanan->nilai;
                $history_tahunan_detail_bulanan->volume = $budget_tahunan_bulanan->volume;
                $history_tahunan_detail_bulanan->satuan =$budget_tahunan_bulanan->satuan;
                $history_tahunan_detail_bulanan->save();
            }
        }
        return response()->json(['success' => "Data berhasil di Simpan"]);    
    }

    public function carryover(Request $request){
        $budget_tahunan = BudgetTahunan::find($request->budget_tahunan_id);
        // return $budget_tahunan->budget->kawasan->id;
        $spk = Spk::where("project_id", $budget_tahunan->budget->project_id)->get();
        $data = [];
        foreach ($spk as $key => $value) {
            # code...
            if($value->pt->id == $budget_tahunan->budget->pt_id){
                if($value->tender->rab->workorder->department_from == $budget_tahunan->budget->department_id){
                    if($value->tender->rab->workorder->kawasan_id != null){
                        if($value->tender->rab->workorder->kawasan_id == $budget_tahunan->budget->kawasan->id){
                            if($value->itempekerjaan->code != 240){
                                if($value->progress_sebelumnya_cair != 100){
                                    if($value->spk_terbayar != $value->nilai_spk){
                                        $carryover = new BudgetCarryOver; 
                                        $carryover->spk_id = $value->id;
                                        $carryover->budget_tahunan_id = $budget_tahunan->id;
                                        $carryover->hutang_bayar = $value->nilai_spk-$value->spk_terbayar;
                                        $carryover->terbayar = $value->spk_terbayar;
                                        $carryover->nilai_spk = $value->nilai_spk;
                                        $carryover->asal_spk = 1;
                                        $carryover->code_coa = $value->itempekerjaan->code;
                                        $carryover->save();

                                        
                                        for ($i=1; $i <= 12 ; $i++) { 
                                            # code...
                                            $carryover_bulanan = new BudgetCarryOverCashflow;
                                            $carryover_bulanan->budget_carry_over_id = $carryover->id;
                                            $carryover_bulanan->bulan = $i;
                                            $carryover_bulanan->persen = 0;
                                            $carryover_bulanan->nilai_persen = 0;
                                            $carryover_bulanan->save();
                                        }
                                    }
                                }
                            }
                        }
                    }else{
                        if($budget_tahunan->budget->project_kawasan_id == null){
                            if($value->itempekerjaan->code == 240){
                                if($value->progress_sebelumnya_cair != 100){
                                    if($value->spk_terbayar != $value->nilai_spk){
                                        $carryover = new BudgetCarryOver; 
                                        $carryover->spk_id = $value->id;
                                        $carryover->budget_tahunan_id = $budget_tahunan->id;
                                        $carryover->hutang_bayar = $value->nilai_spk-$value->spk_terbayar;
                                        $carryover->terbayar = $value->spk_terbayar;
                                        $carryover->nilai_spk = $value->nilai_spk;
                                        $carryover->asal_spk = 1;
                                        $carryover->code_coa = $value->itempekerjaan->code;
                                        $carryover->save();
                                        
                                        for ($i=1; $i <= 12 ; $i++) { 
                                            # code...
                                            $carryover_bulanan = new BudgetCarryOverCashflow;
                                            $carryover_bulanan->budget_carry_over_id = $carryover->id;
                                            $carryover_bulanan->bulan = $i;
                                            $carryover_bulanan->persen = 0;
                                            $carryover_bulanan->nilai_persen = 0;
                                            $carryover_bulanan->save();
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        
        // $spk_migrasi = SpkMigrasi::where("project_id", $budget_tahunan->budget->project_id)->where("pt_id", $budget_tahunan->budget->pt_id)->where("project_kawasan_id", $budget_tahunan->budget->project_kawasan_id)->get();
        // // return response()->json(["success" => $spk_migrasi]);
        // foreach ($spk_migrasi as $key => $value) {
        //     if($budget_tahunan->budget->project_kawasan_id != null && explode(".",$value->coa_baru)[0] != 240){
        //         if($value->hutang_bayar_spk != 0){
        //             $carryover = new BudgetCarryOver; 
        //             $carryover->spk_id = $value->id;
        //             $carryover->budget_tahunan_id = $budget_tahunan->id;
        //             $carryover->hutang_bayar = $value->hutang_bayar_spk;
        //             $carryover->terbayar = $value->bayar_spk;
        //             $carryover->nilai_spk = $value->nilai_kontrak;
        //             $carryover->asal_spk = 2;
        //             $carryover->code_coa = explode(".",$value->coa_baru)[0];
        //             $carryover->save();

                    
        //             for ($i=1; $i <= 12 ; $i++) { 
        //                 # code...
        //                 $carryover_bulanan = new BudgetCarryOverCashflow;
        //                 $carryover_bulanan->budget_carry_over_id = $carryover->id;
        //                 $carryover_bulanan->bulan = $i;
        //                 $carryover_bulanan->persen = 0;
        //                 $carryover_bulanan->nilai_persen = 0;
        //                 $carryover_bulanan->save();
        //             }
        //         }
        //     }elseif($budget_tahunan->budget->project_kawasan_id == null && explode(".",$value->coa_baru)[0] == 240){
        //         if($value->hutang_bayar_spk != 0){
        //             $carryover = new BudgetCarryOver; 
        //             $carryover->spk_id = $value->id;
        //             $carryover->budget_tahunan_id = $budget_tahunan->id;
        //             $carryover->hutang_bayar = $value->hutang_bayar_spk;
        //             $carryover->terbayar = $value->bayar_spk;
        //             $carryover->nilai_spk = $value->nilai_kontrak;
        //             $carryover->asal_spk = 2;
        //             $carryover->code_coa = explode(".",$value->coa_baru)[0];
        //             $carryover->save();

                    
        //             for ($i=1; $i <= 12 ; $i++) { 
        //                 # code...
        //                 $carryover_bulanan = new BudgetCarryOverCashflow;
        //                 $carryover_bulanan->budget_carry_over_id = $carryover->id;
        //                 $carryover_bulanan->bulan = $i;
        //                 $carryover_bulanan->persen = 0;
        //                 $carryover_bulanan->nilai_persen = 0;
        //                 $carryover_bulanan->save();
        //             }
        //         }
        //     }
        // }
        return response()->json(["success" => "sukses"]);
    }

    public function view_bulanan_carryover(Request $request){
        $carryover =  BudgetCarryOver::find($request->id);
        $data = [];
        $array_bulan = array(
            1 => 'januari',
            2 => 'februari', 
            3 => 'maret',
            4 => 'april',
            5 => 'mei',
            6 => 'juni',
            7 => 'juli',
            8 => 'agustus',
            9 => 'september',
            10 => 'oktober',
            11 => 'november',
            12 => 'desember'
        );
        foreach ($carryover->cash_flows as $key => $value) {
            # code...
            $nilai = $value->nilai_persen;
            if($value->nilai_persen == null){
                $nilai = 0;
            }
            $arr = [
                'id' => $value->id,
                'id_bulan' => $value->bulan,
                'name_bulan' => $array_bulan[$value->bulan],
                'persentase' => $value->persen,
                'nilai' => $nilai,
            ];
            array_push($data, $arr);
        }
        $totpersen = $carryover->cash_flows->sum("persen");
        $totbulanan = $carryover->cash_flows->sum("nilai_persen");
        if($carryover->asal_spk == 1){
            $spk_no = $carryover->spk->no;
            $nilai_spk = $carryover->spk->nilai_spk;
        }else{
            $spk_migrasi = SpkMigrasi::where("id", $carryover->spk_id)->first();
            $spk_no = $spk_migrasi->no_spk;
            $nilai_spk = $spk_migrasi->nilai_kontrak;
        }
        return response()->json(['data' => $data, 'no_spk' => $spk_no, 'nilai_spk' => number_format($nilai_spk,2),'hutang_bayar' => number_format($carryover->hutang_bayar,2), 'totpersen' => $totpersen, 'totbulanan' => $totbulanan, 'nilai_hutang_bayar' =>$carryover->hutang_bayar]);
    }

    public function update_budget_tahunan_bulanan_carryover(Request $request){
        for ($i=0; $i < count($request->data); $i++) { 
            $budget_tahunan_bulanan = BudgetCarryOverCashflow::find($request->data[$i]['id']);
            if($request->data[$i]['persen'] != $budget_tahunan_bulanan->persen){
                $budget_tahunan_bulanan->persen = $request->data[$i]['persen'];
                $budget_tahunan_bulanan->nilai_persen = $request->data[$i]['nilai_persen'];
                $budget_tahunan_bulanan->save();

                // $history_tahunan_detail_bulanan = new BudgetTahunanDetailBulananHistory;
                // $history_tahunan_detail_bulanan->budget_tahunan_detail_bulanan_id = $budget_tahunan_bulanan->id;
                // $history_tahunan_detail_bulanan->persen = $budget_tahunan_bulanan->persen;
                // $history_tahunan_detail_bulanan->nilai_persen = $budget_tahunan_bulanan->nilai_persen;
                // $history_tahunan_detail_bulanan->bulan = $budget_tahunan_bulanan->bulan;
                // $history_tahunan_detail_bulanan->itempekerjaan_id = $budget_tahunan_bulanan->itempekerjaan_id;
                // $history_tahunan_detail_bulanan->nilai =$budget_tahunan_bulanan->nilai;
                // $history_tahunan_detail_bulanan->volume = $budget_tahunan_bulanan->volume;
                // $history_tahunan_detail_bulanan->satuan =$budget_tahunan_bulanan->satuan;
                // $history_tahunan_detail_bulanan->save();
            }
        }
        return response()->json(['success' => "Data berhasil di Simpan"]);    
    }

    public function budget_department(Request $request){
        $budget  = Budget::where("project_id",$request->project_id)->where("department_id",$request->department_id)->get();
        $data = [];
        foreach ($budget as $key => $value) {
            # code...
            if($value->kawasan != null){
                $kawasan = $value->kawasan->name;
            }else{
                $kawasan = 'Fasilitas Kota';
            }

            if($value->hpp_netto != null || $value->hpp_netto != 0){
                $hpp_netto = number_format($value->hpp_netto);
            }else{
                $hpp_netto = '-';
            }

            $arr = [
                'id_budget' => $value->id,
                'no_budget' => $value->no,
                'nilai_devcost' => number_format($value->total_dev_cost),
                'hpp_awal' => '-',
                'hpp_revisi' => number_format($value->hpp_netto),
                'kawasan' => $kawasan,
                'start_date' => date("d-m-Y",strtotime($value->start_date)),
                'end_date' => date("d-m-Y",strtotime($value->end_date)),
                'count_tahunan' =>$value->budget_tahunans->count(),
            ];
            array_push($data, $arr);
        }

        return response()->json(['data' => $data]);
    }

    public function rekap_project(Request $request){
        $user   = \Auth::user();
        $project = Project::find($request->project_id);
        $year = date("Y");

        $budget = Budget::where("project_id", $project->id)->where("department_id",$request->department_id)->orderBy("id","desc")->get();

        $data = [];
        $rekap["DC"][1]["name"] = "-";
        $rekap["CC"][1]["name"] = "-";
        $rekap["DC"][2]["name"] = "-";
        $rekap["CC"][2]["name"] = "-";
        $rekap["DC"][0]["name"] = "-";
        $rekap["CC"][0]["name"] = "-";
        $rekap["DC"][0]["hutang_bayar"] = 0;
        $rekap["CC"][0]["hutang_bayar"] = 0;
        foreach ($budget as $key => $value) {
            # code...
            $budget_tahunan = $value->budget_tahunans->where("tahun_anggaran", $year)->last();

            // $budget->carry_over;
            // $voucher_details["CO_DC"]["hutang_bayar"] += $value->nilai;
            if($budget_tahunan != null){
                if(count($budget_tahunan->carry_over) > 0){
                    $carry_over = $budget_tahunan->carry_over;
                    $rekap["DC"][1]["name"] = "Carry Over Baru";
                    $rekap["CC"][1]["name"] = "Carry Over Baru";
                    $rekap["DC"][2]["name"] = "Carry Over Lama";
                    $rekap["CC"][2]["name"] = "Carry Over Lama";
                    $rekap["DC"][1]["hutang_bayar"] = $carry_over->where("asal_spk", 1)->where("code_coa", "!=", 100)->sum("hutang_bayar");
                    $rekap["CC"][1]["hutang_bayar"] = $carry_over->where("asal_spk", 1)->where("code_coa", 100)->sum("hutang_bayar");
                    $rekap["DC"][2]["hutang_bayar"] = $carry_over->where("asal_spk", 2)->where("code_coa", "!=", 100)->sum("hutang_bayar");
                    $rekap["CC"][2]["hutang_bayar"] = $carry_over->where("asal_spk", 2)->where("code_coa", 100)->sum("hutang_bayar");
                    // return response()->json(['data' =>$carry_over]);
                    foreach ($carry_over as $key2 => $value2) {
                        # code...
                        for ($i=1; $i <= 12 ; $i++) { 
                            # code...
                            if($value2->asal_spk == 1){
                                if($value2->code_coa != 100){
                                    if(array_key_exists($i,$rekap["DC"][1])){
                                        $rekap["DC"][1][$i] += $value2->cash_flows->where("bulan",$i)->first()->nilai_persen;
                                    }else{
                                        $rekap["DC"][1][$i] = $value2->cash_flows->where("bulan",$i)->first()->nilai_persen;
                                    }
                                }elseif($value2->code_coa == 100){
                                    if(array_key_exists($i,$rekap["CC"][1])){
                                        $rekap["CC"][1][$i] += $value2->cash_flows->where("bulan",$i)->first()->nilai_persen;
                                    }else{
                                        $rekap["CC"][1][$i] = $value2->cash_flows->where("bulan",$i)->first()->nilai_persen;
                                    }
                                }
                            }elseif($value2->asal_spk == 2){
                                if($value2->code_coa != 100){
                                    if(array_key_exists($i,$rekap["DC"][2])){
                                        $rekap["DC"][2][$i] += $value2->cash_flows->where("bulan",$i)->first()->nilai_persen;
                                    }else{
                                        $rekap["DC"][2][$i] = $value2->cash_flows->where("bulan",$i)->first()->nilai_persen;
                                    }
                                }elseif($value2->code_coa == 100){
                                    if(array_key_exists($i,$rekap["CC"][2])){
                                        $rekap["CC"][2][$i] += $value2->cash_flows->where("bulan",$i)->first()->nilai_persen;
                                    }else{
                                        $rekap["CC"][2][$i] = $value2->cash_flows->where("bulan",$i)->first()->nilai_persen;
                                    }
                                }
                            }
                        }
                        // return response()->json(['data' =>$value]);
                    } 
                    $tahunan_DC_baru = 0;
                    $tahunan_CC_baru = 0;
                    $tahunan_DC_lama = 0;
                    $tahunan_CC_lama = 0;

                    for ($i=1; $i <= 12 ; $i++) { 
                        if(array_key_exists($i,$rekap["DC"][1])){
                            $tahunan_DC_baru += $rekap["DC"][1][$i];
                        }else{
                            $rekap["DC"][1][$i] = 0;
                            $tahunan_DC_baru += $rekap["DC"][1][$i];
                        }

                        if(array_key_exists($i,$rekap["CC"][1])){
                            $tahunan_CC_baru += $rekap["CC"][1][$i];
                        }else{
                            $rekap["CC"][1][$i] = 0;
                            $tahunan_CC_baru += $rekap["CC"][1][$i];
                        }

                        if(array_key_exists($i,$rekap["DC"][2])){
                            $tahunan_DC_lama += $rekap["DC"][2][$i];
                        }else{
                            $rekap["DC"][2][$i] = 0;
                            $tahunan_DC_lama += $rekap["DC"][2][$i];
                        }

                        if(array_key_exists($i,$rekap["CC"][2])){
                            $tahunan_CC_lama += $rekap["CC"][2][$i];
                        }else{
                            $rekap["CC"][2][$i] = 0;
                            $tahunan_CC_lama += $rekap["CC"][2][$i];
                        }
                    }

                    $rekap["DC"][1]["tahunan"] = $tahunan_DC_baru;
                    $rekap["CC"][1]["tahunan"] = $tahunan_CC_baru;
                    $rekap["DC"][2]["tahunan"] = $tahunan_DC_lama;
                    $rekap["CC"][2]["tahunan"] = $tahunan_CC_lama;

                    $rekap["DC"][1]["total_sisa"] = $rekap["DC"][1]["hutang_bayar"] - $rekap["DC"][1]["tahunan"];
                    $rekap["CC"][1]["total_sisa"] = $rekap["CC"][1]["hutang_bayar"] - $rekap["CC"][1]["tahunan"];
                    $rekap["DC"][2]["total_sisa"] = $rekap["DC"][2]["hutang_bayar"] - $rekap["DC"][2]["tahunan"];
                    $rekap["CC"][2]["total_sisa"] = $rekap["CC"][2]["hutang_bayar"] - $rekap["CC"][2]["tahunan"];
                    
                    // return response()->json(['data' =>$rekap["CO_DC_baru"]["hutang_bayar"]]);
                }

                if(count($budget_tahunan->details) > 0){
                    foreach ($budget_tahunan->details as $key2 => $value2) {
                        # code...
                        if($value2->itempekerjaans->parent->code != 100){
                            $rekap["DC"][0]["name"] = "Budget";
                            $rekap["DC"][0]["hutang_bayar"] += $value2->nilai * $value2->volume;
                        }elseif($value2->itempekerjaans->parent->code == 100){
                            $rekap["CC"][0]["name"] = "Budget";
                            $rekap["CC"][0]["hutang_bayar"] += $value2->nilai * $value2->volume;
                        }

                        for ($i=1; $i <= 12 ; $i++) { 
                            if(count($value2->detail_bulanan) > 0){
                                if($value2->itempekerjaans->parent->code != 100){
                                    if(array_key_exists($i,$rekap["DC"][0])){
                                        $rekap["DC"][0][$i] += $value2->detail_bulanan->where("bulan", $i)->first()->nilai_persen;
                                    }else{
                                        $rekap["DC"][0][$i] = $value2->detail_bulanan->where("bulan", $i)->first()->nilai_persen;
                                    }
                                }else{
                                    if(array_key_exists($i,$rekap["CC"][0])){
                                        $rekap["CC"][0][$i] += $value2->detail_bulanan->where("bulan", $i)->first()->nilai_persen;
                                    }else{
                                        $rekap["CC"][0][$i] = $value2->detail_bulanan->where("bulan", $i)->first()->nilai_persen;
                                    }
                                }
                            }
                        }
                    }

                    $budget_DC_tahunan = 0;
                    $budget_CC_tahunan = 0;
                    for ($i=1; $i <= 12 ; $i++) { 
                        if(array_key_exists($i,$rekap["DC"][0])){
                            $budget_DC_tahunan += $rekap["DC"][0][$i];
                        }else{
                            $rekap["DC"][0][$i] = 0;
                            $budget_DC_tahunan += $rekap["DC"][0][$i];
                        }

                        if(array_key_exists($i,$rekap["CC"][0])){
                            $budget_CC_tahunan += $rekap["CC"][0][$i];
                        }else{
                            $rekap["CC"][0][$i] = 0;
                            $budget_CC_tahunan += $rekap["CC"][0][$i];
                        }
                    }

                    $rekap["DC"][0]["tahunan"] = $budget_DC_tahunan;
                    $rekap["CC"][0]["tahunan"] = $budget_CC_tahunan;

                    $rekap["DC"][0]["total_sisa"] = $rekap["DC"][0]["hutang_bayar"] - $rekap["DC"][0]["tahunan"];
                    $rekap["CC"][0]["total_sisa"] = $rekap["CC"][0]["hutang_bayar"] - $rekap["CC"][0]["tahunan"];
                }
            }
        }   

        $hb_dc = 0;
        $hb_cc = 0;
        $t_dc = 0;
        $t_cc = 0;
        $ts_dc = 0;
        $ts_cc = 0;
        $b_dc = 0;
        $b_cc = 0;
        for ($j=1; $j <=12 ; $j++) { 
            $rekap["DC"][3][$j] = 0;
            $rekap["CC"][3][$j] = 0;
        }
        $rekap["DC"][3]["name"] = "Total";
        $rekap["CC"][3]["name"] = "Total";
        for ($i=0; $i <3 ; $i++) { 
            # code...
            if(array_key_exists($i,$rekap["DC"])){
                if(array_key_exists("hutang_bayar",$rekap["DC"][$i])){
                    $hb_dc += $rekap["DC"][$i]["hutang_bayar"];
                }
                if(array_key_exists("tahunan",$rekap["DC"][$i])){
                    $t_dc += $rekap["DC"][$i]["tahunan"];
                }
                if(array_key_exists("total_sisa",$rekap["DC"][$i])){
                    $ts_dc += $rekap["DC"][$i]["total_sisa"];                
                }            
            }
            if(array_key_exists($i,$rekap["CC"])){
                if(array_key_exists("hutang_bayar",$rekap["CC"][$i])){
                    $hb_cc += $rekap["CC"][$i]["hutang_bayar"];
                }
                if(array_key_exists("tahunan",$rekap["CC"][$i])){
                    $t_cc += $rekap["CC"][$i]["tahunan"];
                }
                if(array_key_exists("total_sisa",$rekap["CC"][$i])){
                    $ts_cc += $rekap["CC"][$i]["total_sisa"];
                }
            }

            $rekap["DC"][3]["hutang_bayar"] = $hb_dc;
            $rekap["CC"][3]["hutang_bayar"] = $hb_cc;
            
            $rekap["DC"][3]["tahunan"] = $t_dc;
            $rekap["CC"][3]["tahunan"] = $t_cc;
            
            $rekap["DC"][3]["total_sisa"] = $ts_dc;
            $rekap["CC"][3]["total_sisa"] = $ts_cc;
            for ($j=1; $j <=12 ; $j++) { 
                # code...
                if(array_key_exists($i,$rekap["DC"])){
                    if(array_key_exists($j,$rekap["DC"][$i])){
                        $b_dc = $rekap["DC"][$i][$j];
                    }
                }
                if(array_key_exists($i,$rekap["CC"])){
                    if(array_key_exists($j,$rekap["CC"][$i])){
                        $b_cc = $rekap["CC"][$i][$j];
                    }
                }

                $rekap["DC"][3][$j] = $b_dc;
                $rekap["CC"][3][$j] = $b_cc;
            }
        }

        $total_co_dc_cc = $rekap["DC"][3]["hutang_bayar"] + $rekap["CC"][3]["hutang_bayar"];
        $total_sisa_co_dc_cc = $rekap["DC"][3]["total_sisa"] + $rekap["CC"][3]["total_sisa"];
        
        $rekap["DC"][4]["name"] = "Realisasi";
        $rekap["CC"][4]["name"] = "Realisasi";

        $spk = Spk::where("date","Like","%".$year."%")->where("project_id",$project->id)->get();
        $spk_dc = 0;
        $spk_cc = 0;
        foreach ($spk as $key => $value) {
            # code...
            if($value->pt->id == 73){
                if($value->tender->rab->workorder->department_from == $request->department_id){
                    if($value->itempekerjaan->code != 100){
                        $spk_dc += $value->nilai_spk;
                    }elseif($value->itempekerjaan->code == 100){
                        $spk_cc += $value->nilai_spk;
                    }
                    // return response()->json(['data' => $value]);
                }
            }
        }


        $rekap["DC"][4]["hutang_bayar"] =  $rekap["DC"][1]["hutang_bayar"] +  $rekap["DC"][1]["hutang_bayar"] + $spk_dc;
        $rekap["CC"][4]["hutang_bayar"] = $rekap["CC"][1]["hutang_bayar"] +  $rekap["CC"][1]["hutang_bayar"] + $spk_cc;

        $rekap["DC"][4]["tahunan"] = 0;
        $rekap["DC"][4]["total_sisa"] = 0;

        $rekap["CC"][4]["tahunan"] = 0;
        $rekap["CC"][4]["total_sisa"] = 0;

        for ($j=1; $j <=12 ; $j++) { 
            $rekap["DC"][4][$j] = 0;
            $rekap["CC"][4][$j] = 0;
        }

        $voucher = Voucher::where("pencairan_date","Like","%".$year."%")->where("project_id",$project->id)->where("pt_id", 73)->get();

        foreach ($voucher as $key => $value) {
            # code...
            if($value->bap->spk->itempekerjaan->code != 100){
                $rekap["DC"][4][date('m', strtotime($value->pencairan_date))] = $value->nilai;
            }elseif($value->bap->spk->itempekerjaan->code == 100){
                $rekap["CC"][4][date('m', strtotime($value->pencairan_date))] = $value->nilai;
            }

        }

        for ($j=1; $j <=12 ; $j++) { 
            $rekap["DC"][4]["tahunan"] += $rekap["DC"][4][$j];
            $rekap["CC"][4]["tahunan"] += $rekap["CC"][4][$j];
        }
        $rekap["DC"][4]["total_sisa"] = $rekap["DC"][4]["hutang_bayar"] - $rekap["DC"][4]["tahunan"];
        $rekap["CC"][4]["total_sisa"] = $rekap["DC"][4]["hutang_bayar"] - $rekap["DC"][4]["tahunan"];

        return response()->json(['data' => $rekap, "project_name" => $project->name, "year" => $year, "total_hutang" => number_format($total_co_dc_cc) , "total_sisa" => number_format($total_sisa_co_dc_cc)]);
    }

    public function cashout_coa_lama(Request $request){
        // return str_replace(",","",$request->data[1][1])+1;
        $budget_tahunan = BudgetTahunan::find($request->budget_tahunan_id);
        for ($i=0; $i < count($request->data); $i++) { 
            $item_pekerjaan = Itempekerjaan::find($request->data[$i][0]);
            if($budget_tahunan->budget->project_kawasan_id != null && $item_pekerjaan->code != 240){
                $spk_migrasi = new SpkMigrasi;
                $spk_migrasi->kawasan = $budget_tahunan->budget->kawasan->name;
                $spk_migrasi->coa_baru = $item_pekerjaan->code;
                $spk_migrasi->itempekerjaan_id = $item_pekerjaan->id;
                $spk_migrasi->dpp = (int)str_replace(",","",$request->data[$i][1]);
                $spk_migrasi->bayar_spk = (int)str_replace(",","",$request->data[$i][2]);
                $spk_migrasi->hutang_bayar_spk = (int)str_replace(",","",$request->data[$i][3]);
                $spk_migrasi->nilai_kontrak = (int)str_replace(",","",$request->data[$i][1]);
                $spk_migrasi->project_kawasan_id = $budget_tahunan->budget->project_kawasan_id;
                $spk_migrasi->project_id = $budget_tahunan->budget->project_id;
                $spk_migrasi->pt_id = $budget_tahunan->budget->pt_id;
                $spk_migrasi->save();

                $carryover = new BudgetCarryOver; 
                $carryover->spk_id = $spk_migrasi->id;
                $carryover->budget_tahunan_id = $budget_tahunan->id;
                $carryover->hutang_bayar = $spk_migrasi->hutang_bayar_spk;
                $carryover->terbayar = $spk_migrasi->bayar_spk;
                $carryover->nilai_spk = $spk_migrasi->nilai_kontrak;
                $carryover->asal_spk = 2;
                $carryover->code_coa = $item_pekerjaan->code;
                $carryover->save();

                
                for ($i=1; $i <= 12 ; $i++) { 
                    # code...
                    $carryover_bulanan = new BudgetCarryOverCashflow;
                    $carryover_bulanan->budget_carry_over_id = $carryover->id;
                    $carryover_bulanan->bulan = $i;
                    $carryover_bulanan->persen = 0;
                    $carryover_bulanan->nilai_persen = 0;
                    $carryover_bulanan->save();
                }
            }elseif($budget_tahunan->budget->project_kawasan_id == null && $item_pekerjaan->code == 240){
                $spk_migrasi = new SpkMigrasi;
                $spk_migrasi->kawasan = $budget_tahunan->budget->kawasan->name;
                $spk_migrasi->coa_baru = $item_pekerjaan->code;
                $spk_migrasi->itempekerjaan_id = $item_pekerjaan->id;
                $spk_migrasi->dpp = str_replace(",","",$request->data[$i][1]);
                $spk_migrasi->bayar_spk = str_replace(",","",$request->data[$i][2]);
                $spk_migrasi->hutang_bayar_spk = str_replace(",","",$request->data[$i][3]);
                $spk_migrasi->nilai_kontrak = str_replace(",","",$request->data[$i][1]);
                $spk_migrasi->project_kawasan_id = $budget_tahunan->budget->project_kawasan_id;
                $spk_migrasi->project_id = $budget_tahunan->budget->project_id;
                $spk_migrasi->pt_id = $budget_tahunan->budget->pt_id;
                $spk_migrasi->save();

                $carryover = new BudgetCarryOver; 
                $carryover->spk_id = $spk_migrasi->id;
                $carryover->budget_tahunan_id = $budget_tahunan->id;
                $carryover->hutang_bayar = $spk_migrasi->hutang_bayar_spk;
                $carryover->terbayar = $spk_migrasi->bayar_spk;
                $carryover->nilai_spk = $spk_migrasi->nilai_kontrak;
                $carryover->asal_spk = 2;
                $carryover->code_coa = $item_pekerjaan->code;
                $carryover->save();

                
                for ($i=1; $i <= 12 ; $i++) { 
                    # code...
                    $carryover_bulanan = new BudgetCarryOverCashflow;
                    $carryover_bulanan->budget_carry_over_id = $carryover->id;
                    $carryover_bulanan->bulan = $i;
                    $carryover_bulanan->persen = 0;
                    $carryover_bulanan->nilai_persen = 0;
                    $carryover_bulanan->save();
                }
            }
        }
    }
    
}
