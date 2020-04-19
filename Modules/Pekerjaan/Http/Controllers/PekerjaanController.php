<?php

namespace Modules\Pekerjaan\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Pekerjaan\Entities\Itempekerjaan;
use Modules\Pekerjaan\Entities\Coa;
use Modules\Pekerjaan\Entities\ItempekerjaanCoa;
use Modules\Department\Entities\Department;
use Modules\Budget\Entities\BudgetGroup;
use Modules\Pekerjaan\Entities\ItempekerjaanProgress;
use Modules\Project\Entities\Project;
use Modules\Pekerjaan\Entities\ItempekerjaanDetail;
use Modules\Satuan\Entities\CoaSatuan;
use Modules\Pekerjaan\Entities\ItempekerjaanHarga;
use Modules\Pekerjaan\Entities\ItempekerjaanHargaDetail;
use Modules\Project\Entities\ProjectPt;
use Modules\Pekerjaan\Entities\CoaCpmsFinance;
use Modules\Pekerjaan\Entities\TipeCoa;
use Illuminate\Support\Facades\DB;


class PekerjaanController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {   
        $user = \Auth::user();
        $itempekerjaan = Itempekerjaan::where("parent_id",null)->get();
        $project = Project::get();
        return view('pekerjaan::index',compact("user","itempekerjaan","project"));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $user = \Auth::user();
        $department = Department::get();
        $budgetgroup = BudgetGroup::get();
        $project = Project::get();
        return view('pekerjaan::create',compact("user","department","budgetgroup","project"));
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $itempekerjaan = new Itempekerjaan;
        $itempekerjaan->parent_id = null;
        $itempekerjaan->department_id = $request->department;
        $itempekerjaan->group_cost = $request->group_cost;
        $itempekerjaan->code = $request->code;
        $itempekerjaan->tag = $request->tag;
        $itempekerjaan->name = $request->name;
        $itempekerjaan->ppn = $request->ppn;
        $itempekerjaan->save();
        return redirect("/pekerjaan/detail/?id=".$itempekerjaan->id);
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show(Request $request)
    {
        $user = \Auth::user();
        $itempekerjaan = Itempekerjaan::find($request->id);
        $department = Department::get();
        $budgetgroup = BudgetGroup::get();
        $coa = Coa::get();
        $project = Project::get();
        $start = 0;
        $satuan = CoaSatuan::get();
        return view('pekerjaan::detail',compact("itempekerjaan","user","department","budgetgroup","coa","project","start","satuan"));
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function coas(Request $request)
    {
        $itemcoas = new ItempekerjaanCoa;
        $itemcoas->itempekerjaan_id = $request->coas_itempekerjaan;
        $itemcoas->department_id = $request->department_id;
        $itemcoas->coa_id = $request->coa;
        $itemcoas->save();
        return redirect("/pekerjaan/detail/?id=".$request->coas_itempekerjaan);
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request)
    {
        $itempekerjaan = Itempekerjaan::find($request->id);
        $itempekerjaan->department_id = $request->department;
        $itempekerjaan->group_cost = $request->group_cost;
        $itempekerjaan->code = $request->code;
        $itempekerjaan->tag = $request->tag;
        $itempekerjaan->name = $request->name;
        $itempekerjaan->ppn = $request->ppn;
        $itempekerjaan->description = $request->description;
        $itempekerjaan->save();
        return redirect("/pekerjaan/detail/?id=".$request->id);
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy(Request $request)
    {
        $itempekerjaan = Itempekerjaan::find($request->id);
        $status = $itempekerjaan->delete();
        if ( $status ){
            return response()->json( ["status" => "0"] );
        }else{
            return response()->json( ["status" => "1"] );
        }
    }

    public function subitem(Request $request){

    }

    public function addprogress(Request $request){

        foreach ($request->item as $key => $value) {
            # code...            
            $check = ItempekerjaanProgress::where("item_pekerjaan_id",$request->item_id)->where("termyn", $key + 1)->get();
            if ( count($check) > 0 ){
                $ItempekerjaanProgress = ItempekerjaanProgress::find($check->first()->id);
            }else{                
                $ItempekerjaanProgress = new ItempekerjaanProgress;
            }
            $ItempekerjaanProgress->item_pekerjaan_id = $request->item_id;
            $ItempekerjaanProgress->termyn = $key + 1;
            $ItempekerjaanProgress->percentage =  $request->item[$key];
            $ItempekerjaanProgress->save();
        }

        
        return redirect("/pekerjaan/detail/?id=".$request->coa_id);

    }

    public function addchilditem(Request $request){
        $parent = Itempekerjaan::find($request->item_pekerjaan);
        $itempekerjaan = new Itempekerjaan;
        $itempekerjaan->parent_id = $request->item_pekerjaan;
        $itempekerjaan->department_id = $parent->department_id;
        $itempekerjaan->group_cost = $parent->group_cost;
        if($parent->child_item->count() < 10){
            $code_akhir = '0'.$parent->child_item->count();
        }else{
            $code_akhir = $parent->child_item->count();
        }
        $itempekerjaan->code       = $parent->code.".".$code_akhir;
        $itempekerjaan->name       = $request->item_child;
        $itempekerjaan->ppn        = $parent->ppn / 100;
        $itempekerjaan->tag        = $parent->tag;
        $itempekerjaan->description = $request->item_child;
        $status = $itempekerjaan->save();
        return redirect("/pekerjaan/detail/?id=".$request->item_coa);
    }

    public function savesatuan(Request $request){

        foreach ($request->item_id_ as $key => $value) {
            $detail = Itempekerjaan::find($request->item_id_[$key])->details;
            if ( $detail != ""){
                $itempekerjaan_detail = ItempekerjaanDetail::find($detail->id);
                $itempekerjaan_detail->satuan = $request->item_satuan_[$key];
                $itempekerjaan_detail->save(); 
            }else{            
                $itempekerjaan_detail = new ItempekerjaanDetail;
                $itempekerjaan_detail->itempekerjaan_id = $request->item_id_[$key];
                $itempekerjaan_detail->satuan = $request->item_satuan_[$key];
                $itempekerjaan_detail->save(); 
            }
        }
        return redirect("/pekerjaan/detail/?id=".$request->coa_id);
    }

    public function library(Request $request){
        $user = \Auth::user();
        $project = Project::get();
        $itempekerjaan = Itempekerjaan::find($request->id);
        $class = "";
        $nilai_library_satuan = 0;
        $total_library = 0;
        foreach ($itempekerjaan->harga as $key => $value) {
            if ( $value->project_id == null ){
                $nilai_library_satuan = $value->nilai;
            }
        }

        foreach ($itempekerjaan->child_item as $key => $value) {
            $total_library = $total_library + $value->nilai_library;
        }
        return view("pekerjaan::add_library",compact("user","project","itempekerjaan","class","nilai_library_satuan","total_library"));
    }

    public function savelibrary(Request $request){
        $user = \Auth::user();
        if ( $request->nilai_[0] != "" ){
            $detail_master = Itempekerjaan::find($request->item_id_[0]);   
            $itempekerjaan_harga = new ItempekerjaanHarga;
            $itempekerjaan_harga->itempekerjaan_id = $request->parent_id;
            $itempekerjaan_harga->project_id = null;
            $itempekerjaan_harga->nilai = str_replace(",", "", $request->nilai_[0]);
            $itempekerjaan_harga->satuan = $detail_master->details->satuan;
            $itempekerjaan_harga->created_by = $user->id;
            $itempekerjaan_harga->save();
        }

        foreach ($request->nilai_ as $key => $value) {
            if ( $request->item_id_[$key] != $request->tag ){
                $tag = 0;
            }else{
                $tag = 1;
            }

            $itempekerjaan = Itempekerjaan::find($request->item_id_[$key]);
            $itempekerjaan->tag = $tag;
            $itempekerjaan->save();  

            if ( $request->nilai_[$key] != "" ){
                if ( $key > 0 ){
                    $itempekerjaan_harga_detail = new ItempekerjaanHargaDetail;
                    $itempekerjaan_harga_detail->itempekerjaan_id = $request->item_id_[$key];    
                    $itempekerjaan_harga_detail->itempekerjaan_harga_id = $itempekerjaan_harga->id;
                    $itempekerjaan_harga_detail->project_id = null;
                    $itempekerjaan_harga_detail->nilai = str_replace(",", "", $request->nilai_[$key]);                
                    $itempekerjaan_harga_detail->satuan = $itempekerjaan->details->satuan;
                    $itempekerjaan_harga_detail->created_by = $user->id;
                    $itempekerjaan_harga_detail->save();
                }
            }
        }        
        return redirect("pekerjaan/library-detail/?id=".$request->parent_id);
    }

    // public function migrasi_data(Request $request){
    //     // return "hai";
    //     $data = DB::connection('sqlsrv5')->table('dbo.view_coa')->where("is_journal", 1)->select("*")->get();

    //     for ($i=0; $i <count($data) ; $i++) { 
    //         # code...
    //         printf($data[$i]->name);
    //         echo("<br/>");
    //     }


    //     return count($data);
    // }

    public function index_coa(Request $request)
    {   
        $user = \Auth::user();
        $itempekerjaan = Itempekerjaan::where("parent_id",null)->get();
        $project = Project::find($request->session()->get('project_id'));
        if ($user->level == "superadmin") {
            # code...
            $project_pt = ProjectPt::get();
        } else {
            # code...
            $project_pt = ProjectPt::where("pt_id","!=",null)->where("project_id",$project->id)->orderBy("project_id", "ASC")->get();
        }
        return view('pekerjaan::index_coa',compact("user","itempekerjaan","project","project_pt"));
    }

    public function detail_coa(Request $request)
    {   
        // return "hohoho";
        $user = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        $project_pt = ProjectPt::where("id",$request->id)->first();
        if($project != null){
            if($project_pt->project_id != $project->id && $user->level != "superadmin"){
                return redirect("/");
            }
        }elseif($user->level != "superadmin"){
            return redirect("/");
        }
        $coa = CoaCpmsFinance::where("project_id",$project_pt->project_id)->where("pt_id",$project_pt->pt_id)->get();
        $coa_gl = DB::connection('sqlsrv5')->table('dbo.view_coa')->where("deleted", 0)->where("project_id",$project_pt->project->project_id)->where("pt_id", $project_pt->pt->pt_id)->where("is_journal", 1)->select("*")->get();
        $coa_cpms = Itempekerjaan::where("parent_id", null)->get();
        $tipe_coa = TipeCoa::get();

        $coa_relasi = [];

        foreach($tipe_coa->where("id","!=",1) as $key => $value){
            if($coa->where("tipe_coa_id", $value->id)->first() != null){
                $coa_finance = DB::connection('sqlsrv5')->table('dbo.view_coa')->where("deleted", 0)->where("project_id",$project_pt->project->project_id)->where("pt_id", $project_pt->pt->pt_id)->where("is_journal", 1)->select("*")->where("coa_id", $coa->where("tipe_coa_id", $value->id)->first()->coa_finance_id)->get();
                if (count($coa_finance) != null) {
                    # code...
                    $coa_finance = $coa_finance[0]->coa;
                    $coa_finance_id = $coa->where("tipe_coa_id", $value->id)->first()->coa_finance_id;
                } else {
                    # code...
                    $coa_finance = null;
                }
                
            }else{
                $coa_finance = null;
                $coa_finance_id = null;
            }
            $arr = [
                "coa_cpms" => "--",
                "coa_cpms_id" => 0,
                "coa_finance" => $coa_finance,
                "coa_finance_id" => $coa_finance_id,
                "department" => "--",
                "department_id" => 0,
                "peruntukan" => $value->name,
                "peruntukan_id" => $value->id,
            ];
            array_push($coa_relasi, $arr);
        }

        foreach ($coa_cpms as $key => $value) {
            # code...
            foreach ($value->child_item as $key2 => $value2) {
                # code...
                if($coa->where("itempekerjaan_id", $value2->id)->first() != null){
                    $coa_finance = DB::connection('sqlsrv5')->table('dbo.view_coa')
                    ->where("deleted", 0)
                    ->where("project_id",$project_pt->project->project_id)
                    ->where("pt_id", $project_pt->pt->pt_id)->where("is_journal", 1)
                    ->select("*")
                    ->where("coa_id", $coa->where("itempekerjaan_id", $value2->id)->first()->coa_finance_id)
                    ->get()[0]->coa;

                    $coa_finance_id = $coa->where("itempekerjaan_id", $value2->id)->first()->coa_finance_id;
                }else{
                    $coa_finance = null;
                    $coa_finance_id = null;
                }
                $arr = [
                    "coa_cpms" => $value2->code." | ".$value2->name,
                    "coa_cpms_id" => $value2->id,
                    "coa_finance" => $coa_finance,
                    "coa_finance_id" => $coa_finance_id,
                    "department" => $value2->department->name,
                    "department_id" => $value2->department->id,
                    "peruntukan" => $tipe_coa->where("id", 1)->first()->name,
                    "peruntukan_id" => 1,
                ];
                array_push($coa_relasi, $arr);

            }
        }
        // foreach ($coa as $key => $value) {
        //     # code...
        //     $coa_finance = DB::connection('sqlsrv5')->table('dbo.view_coa')->where("project_id",$project_pt->project->project_id)->where("pt_id", $project_pt->pt->pt_id)->where("is_journal", 1)->select("*")->where("coa_id", $value->coa_finance_id)->get();
        //     // return $coa_finance[0]->coa;
        //     if($value->itempekerjaan_id != null){
        //         $coa_cpms_relasi = $value->itempekerjaan->code;
        //         $department = $value->itempekerjaan->department->name;
        //     }else{
        //         $coa_cpms_relasi = "--";
        //         $department = "--";
        //     }
        //     // return $value->tipe_coa;
        //     $arr = [
        //         "coa_cpms" => $coa_cpms_relasi,
        //         "coa_finance" => $coa_finance[0]->coa,
        //         "department" => $department,
        //         "peruntukan" => $value->tipe_coa->name,
        //     ];
        //     array_push($coa_relasi, $arr);

        // }

        // $coa_relasi = array_sort($coa_relasi);
        return view('pekerjaan::detail_coa',compact("user","coa","project_pt","coa_gl","coa_cpms","tipe_coa","coa_relasi","project"));
    }

    public function save_relasi(Request $request)
    {   
        $project_pt = ProjectPt::where("id",$request->projectpt)->first();
        
        $relasi = new CoaCpmsFinance;
        $relasi->project_id = $project_pt->project_id;
        $relasi->pt_id = $project_pt->pt_id;
        $relasi->tipe_coa_id = $request->tipe_coa;
        $relasi->coa_finance_id = $request->coa_gl;
        if($request->coa_cpms == 0){
            $relasi->itempekerjaan_id = null;
        }else{
            $relasi->itempekerjaan_id = $request->coa_cpms;
        }
        $relasi->save();
        return redirect("/pekerjaan/coa/detail/?id=".$project_pt->id);
    }

    public function save_masal(Request $request)
    {  
        $project_pt = ProjectPt::where("id",$request->projectpt)->first();

        for ($i=0; $i < count($request->data); $i++) { 
            if($request->data[$i]['coa_finance_id'] != 0){
                if($request->data[$i]['peruntukan_id'] != 1){
                    $coa = CoaCpmsFinance::where("project_id",$project_pt->project_id)->where("pt_id",$project_pt->pt_id)->where("tipe_coa_id", $request->data[$i]['peruntukan_id'])->first();
                    if($coa != null){
                        if($coa->coa_finance_id != $request->data[$i]['coa_finance_id']){
                            $coa->coa_finance_id = $request->data[$i]['coa_finance_id'];

                            $tipe_coa = TipeCoa::where("id",$request->data[$i]['peruntukan_id'])->first();
                            $coa->pph_rekanan_id = $tipe_coa->pph_rekanan_id;

                            $coa->save();
                        }
                    }else{
                        $relasi = new CoaCpmsFinance;
                        $relasi->project_id = $project_pt->project_id;
                        $relasi->pt_id = $project_pt->pt_id;
                        $relasi->tipe_coa_id = $request->data[$i]['peruntukan_id'];
                        $relasi->coa_finance_id = $request->data[$i]['coa_finance_id'];
                        $relasi->itempekerjaan_id = null;

                        $tipe_coa = TipeCoa::where("id", $request->data[$i]['peruntukan_id'])->first();
                        $relasi->pph_rekanan_id = $tipe_coa->pph_rekanan_id;

                        $relasi->save();    
                    }
                }elseif($request->data[$i]['peruntukan_id'] == 1){
                    $coa = CoaCpmsFinance::where("project_id",$project_pt->project_id)->where("pt_id",$project_pt->pt_id)->where("itempekerjaan_id", $request->data[$i]['coa_cpms_id'])->first();
                    if($coa != null){
                        if($coa->coa_finance_id != $request->data[$i]['coa_finance_id']){
                            $coa->coa_finance_id = $request->data[$i]['coa_finance_id'];
                            $coa->save();
                        }
                    }else{
                        $relasi = new CoaCpmsFinance;
                        $relasi->project_id = $project_pt->project_id;
                        $relasi->pt_id = $project_pt->pt_id;
                        $relasi->tipe_coa_id = $request->data[$i]['peruntukan_id'];
                        $relasi->coa_finance_id = $request->data[$i]['coa_finance_id'];
                        $relasi->itempekerjaan_id = $request->data[$i]['coa_cpms_id'];
                        $relasi->save();   
                    }
                }
            }
        }
        return response()->json(['success' => "berhasil disimpan"]);
    }
}
