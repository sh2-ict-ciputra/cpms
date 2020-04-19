<?php

namespace Modules\Project\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Project\Entities\Project;
use Modules\Project\Entities\ProjectKawasan;
use Modules\Project\Entities\Blok;
use Modules\Project\Entities\UnitType;
use Modules\Pekerjaan\Entities\Itempekerjaan;
use Modules\Project\Entities\Templatepekerjaan;
use Modules\Project\Entities\TemplatepekerjaanDetail;
use Modules\Project\Entities\Unit;
use Modules\Globalsetting\Entities\Globalsetting;
use Modules\Budget\Entities\HppUpdate;
use Modules\Budget\Entities\HppUpdateDetail;
use Modules\Project\Entities\UnitArah;
use Modules\Country\Entities\City;
use Modules\Project\Entities\ProjectHistory;
use Modules\Category\Entities\Category;
use Modules\Category\Entities\CategoryDetail;
use Modules\Category\Entities\CategoryProject;
use Modules\Project\Entities\UnitTypeCategory;
use Modules\Project\Entities\UnitTypeCategoryDetail;
use Illuminate\Support\Facades\DB;
use Modules\Project\Entities\ProjectPt;
use Modules\Pt\Entities\Pt;
use Modules\Budget\Entities\BudgetTahunanPeriode;
use Modules\Project\Entities\UnitTypeSpecification;
use Modules\TypeSpecification\Entities\TypeSpecification;
use Storage;
use Intervention\Image\Facades\Image ;
use Modules\Department\Entities\Department;
use Modules\Budget\Entities\Budget;
use Modules\Budget\Entities\BudgetTahunan;
use Modules\User\Entities\User;
use Modules\Project\Entities\UnitHistory;
use Modules\Project\Entities\UnitPending;
use Modules\Project\Entities\Purpose;
use Modules\Rekanan\Entities\UserRekanan;

use Modules\Project\Entities\UnitChangedPrices;
class ProjectController extends Controller
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
        $project = Project::get();
        return view('project::index',compact("user","project"));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $user = \Auth::user();
        $project = Project::get();
        $cities = City::get();
        return view('project::create',compact("user","project","cities","cities"));
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $project = new Project;
        $project->subholding = $request->subholding;
        $project->code = $request->code;
        $project->name = $request->name;
        $project->luas = str_replace(",","",$request->luas);
        $project->address = $request->address;
        $project->zipcode = $request->zipcode;
        $project->phone = $request->phone;
        $project->fax    = $request->fax ;
        $project->email = $request->email;
        $project->description = $request->description;
        $project->city_id = $request->city_id;
        $project->save();

        return redirect("project/detail/?id=".$project->id);
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show(Request $request)
    {
        $request->session()->put('project_id', $request->id);
        // return $request;
        $user = \Auth::user();
        $users = User::find($user->id);
        //Validasi User
        $jabatan = $users->jabatan;
        // return $jabatan;
        $cek = 0;
        foreach ($jabatan as $key => $value) {
            if($value['project_id'] == $request->id){
                $level = $value['level'];
                $cek = 1;
            } 
        } 

        if($cek == 0){
            return redirect("/");
        }
        if ( $user->user_login == "simulasi"){
            return redirect("simulasi");
        }

        if ( $user->id == "1"){
            // $request->session()->put('level', 'superadmin');
            return redirect("home");
        }
        
        if ( $user->is_rekanan == 0 ){           

            // foreach ($jabatan as $key => $value) {
                // return $value['level'];
                if ( $level == "10" || $level == "1016" ){
                    // if($value['project_id'] != $request->id){
                        $request->session()->put('level', $level );
                        // return redirect("/project/detail?id=".$request->id);
                    // }
                }elseif($level <= 7){
                    $request->session()->put('level', '');
                    return redirect("/access");

                }elseif( $user->is_pic == "1"){
                    $request->session()->put('level', 'pic');
                    return redirect("/progress");
                }
            // }
        }else {
            $user_rekanan_group = UserRekanan::where("user_login",$user->user_login)->get();
            if ( count($user_rekanan_group) > 0 ){
                $users = UserRekanan::find($user_rekanan_group->first()->id);
                $rekanan_group = $users->rekanan_group;
                // $request->session()->put('rekanan_id', $rekanan_group->id);
                return redirect("rekanan/user");
            }else{
                return redirect("rekanan/user/fail");
            }
        }

        //Validasi user
        $project = Project::find($request->id);
        $level = "" ;
        if ( ($request->session()->get('level'))) {
            $level = "superadmin";
        }

        if ( ($request->session()->get('level') == "1016") ) {
            return redirect("project/kawasan");
        }

        

        $arrayBulananCashOut = array(
            "januari" => 0,
            "februari" => 0,
            "maret" => 0,
            "april" => 0,
            "mei" => 0,
            "juni" => 0,
            "juli" => 0,
            "agustus" => 0,
            "september" => 0,
            "oktober" => 0,
            "november" => 0,
            "desember" => 0
        );

        $arrayCarryOverCashOut = array(
            "januari" => 0,
            "februari" => 0,
            "maret" => 0,
            "april" => 0,
            "mei" => 0,
            "juni" => 0,
            "juli" => 0,
            "agustus" => 0,
            "september" => 0,
            "oktober" => 0,
            "november" => 0,
            "desember" => 0
        );

        $arrayRealisasi = array(
            "januari" => 0,
            "februari" => 0,
            "maret" => 0,
            "april" => 0,
            "mei" => 0,
            "juni" => 0,
            "juli" => 0,
            "agustus" => 0,
            "september" => 0,
            "oktober" => 0,
            "november" => 0,
            "desember" => 0
        );


        // Enable Fase 3 
        foreach ($project->budget_tahunans as $key => $value) {
            if ( $value->tahun_anggaran == date("Y")){
                //Budget SPK 
                foreach ($value->details as $key2 => $value2) {
                    $budget_cf = BudgetTahunanPeriode::where("budget_id",$value->id)->where("itempekerjaan_id",$value2->itempekerjaan_id)->get();
                    if ( count($budget_cf) > 0 ){
                        $spk = $value2->volume * $value2->nilai;
                        foreach ($budget_cf as $key3 => $value3) {

                            $arrayBulananCashOut["januari"] =  $arrayBulananCashOut["januari"] + (($value3->januari/100) * $spk);
                            $arrayBulananCashOut["februari"] = $arrayBulananCashOut["februari"] + (($value3->februari/100) * $spk);
                            $arrayBulananCashOut["maret"] = $arrayBulananCashOut["maret"] + (($value3->maret/100) * $spk);
                            $arrayBulananCashOut["april"] = $arrayBulananCashOut["april"] + (($value3->april/100) * $spk);
                            $arrayBulananCashOut["mei"] = $arrayBulananCashOut["mei"] + (($value3->mei/100) * $spk);
                            $arrayBulananCashOut["juni"] = $arrayBulananCashOut["juni"] + (($value3->juni/100) * $spk);
                            $arrayBulananCashOut["juli"] = $arrayBulananCashOut["juli"] + (($value3->juli/100) * $spk);
                            $arrayBulananCashOut["agustus"] = $arrayBulananCashOut["agustus"] + (($value3->agustus/100) * $spk);
                            $arrayBulananCashOut["september"] = $arrayBulananCashOut["september"] + (($value3->september/100) * $spk);
                            $arrayBulananCashOut["oktober"] = $arrayBulananCashOut["oktober"] + (($value3->oktober/100) * $spk);
                            $arrayBulananCashOut["november"] = $arrayBulananCashOut["november"] + (($value3->november/100) * $spk);
                            $arrayBulananCashOut["desember"] = $arrayBulananCashOut["desember"] + (($value3->desember/100) * $spk);
                        }
                    }
                }

                //Budget Carry Over 
                foreach ($value->carry_over as $key3 => $value3) {
                    if ( $value3->hutang_bayar != "" ){
                        $sisa = $value3->hutang_bayar;
                    }else{
                        $sisa = $value3->spk->nilai - $value3->spk->terbayar_verified;
                    }
                    foreach ($value3->cash_flows as $key4 => $value4) {
                        $arrayCarryOverCashOut["januari"] =  $arrayCarryOverCashOut["januari"] + (($value4->januari/100) * $sisa);
                        $arrayCarryOverCashOut["februari"] = $arrayCarryOverCashOut["februari"] + (($value4->februari/100) * $sisa);
                        $arrayCarryOverCashOut["maret"] = $arrayCarryOverCashOut["maret"] + (($value4->maret/100) * $sisa);
                        $arrayCarryOverCashOut["april"] = $arrayCarryOverCashOut["april"] + (($value4->april/100) * $sisa);
                        $arrayCarryOverCashOut["mei"] = $arrayCarryOverCashOut["mei"] + (($value4->mei/100) * $sisa);
                        $arrayCarryOverCashOut["juni"] = $arrayCarryOverCashOut["juni"] + (($value4->juni/100) * $sisa);
                        $arrayCarryOverCashOut["juli"] = $arrayCarryOverCashOut["juli"] + (($value4->juli/100) * $sisa);
                        $arrayCarryOverCashOut["agustus"] = $arrayCarryOverCashOut["agustus"] + (($value4->agustus/100) * $sisa);
                        $arrayCarryOverCashOut["september"] = $arrayCarryOverCashOut["september"] + (($value4->september/100) * $sisa);
                        $arrayCarryOverCashOut["oktober"] = $arrayCarryOverCashOut["oktober"] + (($value4->oktober/100) * $sisa);
                        $arrayCarryOverCashOut["november"] = $arrayCarryOverCashOut["november"] + (($value4->november/100) * $sisa);
                        $arrayCarryOverCashOut["desember"] = $arrayCarryOverCashOut["desember"] + (($value4->desember/100) * $sisa);
                    }
                }
            }
        }
        
        foreach ($project->voucher as $key => $value) {
            if ( $value->pencairan_date != NULL ){
                $month = $value->pencairan_date->format("M");

                if ( $month == "01"){
                    $arrayRealisasi["januari"] = $arrayRealisasi["januari"] + $value->nilai;
                }elseif( $month == "02"){
                    $arrayRealisasi["februari"] = $arrayRealisasi["februari"] + $value->nilai;
                }elseif( $month == "03"){
                    $arrayRealisasi["maret"] = $arrayRealisasi["maret"] + $value->nilai;
                }elseif( $month == "04"){
                    $arrayRealisasi["april"] = $arrayRealisasi["april"] + $value->nilai;
                }elseif( $month == "05"){
                    $arrayRealisasi["mei"] = $arrayRealisasi["mei"] + $value->nilai;
                }elseif( $month == "06"){
                    $arrayRealisasi["juni"] = $arrayRealisasi["juni"] + $value->nilai;
                }elseif( $month == "07"){
                    $arrayRealisasi["juli"] = $arrayRealisasi["juli"] + $value->nilai;
                }elseif( $month == "08"){
                    $arrayRealisasi["agustus"] = $arrayRealisasi["agustus"] + $value->nilai;
                }elseif( $month == "09"){
                    $arrayRealisasi["september"] = $arrayRealisasi["september"] + $value->nilai;
                }elseif( $month == "10"){
                    $arrayRealisasi["oktober"] = $arrayRealisasi["oktober"] + $value->nilai;
                }elseif( $month == "11"){
                    $arrayRealisasi["november"] = $arrayRealisasi["november"] + $value->nilai;
                }elseif( $month == "12"){
                    $arrayRealisasi["desember"] = $arrayRealisasi["desember"] + $value->nilai;
                }
            }
        }
        
        $variabel_cash_out = "";
        $nilai_cash_out = 0;
        foreach ($arrayBulananCashOut as $key => $value) {
            $nilai_cash_out = $nilai_cash_out + $value;
        }
        
        $nilai_con_cost = 0;
        foreach ($project->budget_tahunans as $key => $value) {
            if ( $value->tahun_anggaran == date("Y")){
                $nilai_con_cost = $nilai_con_cost + $value->nilai_cash_out_con_cost;
            }
        }
        $nilai_cash_out = $nilai_cash_out + $nilai_con_cost;
        $variabel_cash_out = trim($variabel_cash_out,",");

        $variabel_carry_over = "";
        $nilai_carry_over= 0;
        foreach ($arrayCarryOverCashOut as $key => $value) {
            $nilai_carry_over = $nilai_carry_over + $value;
        }
        //echo $variabel_carry_over."<>".$nilai_carry_over;
        $variabel_carry_over = trim($variabel_carry_over,",");

      
        $budget_cashout = "";
        foreach ($arrayBulananCashOut as $key => $value) {
            $budget_cashout .= ($value) .",";
        }
        $budget_cashout = $budget_cashout;

        $budget_carry_over = "";
        foreach ($arrayCarryOverCashOut as $key => $value) {
            $budget_carry_over .= ($value) .",";
        }
        $budget_carry_over = $budget_carry_over;

        $real_bulanan = "";
        foreach ($arrayRealisasi as $key => $value) {
            $real_bulanan .= ($value) .",";
        }
        $real_bulanan = $real_bulanan;
        


        $array_serah_terima = $project->total_sold;
        $array_bulan = array(
            "6" => "0-6",
            "12" => "6-12",
            "24" => "12-24",
            "36" => "24-36" 
        );

        $hppupdate = HppUpdate::where("project_id", $project->id)->orderBy("id","DESC")->get(); 
        $luasbook = 0;
        if(count($hppupdate) != 0){
            $luasbook = $hppupdate->sum("luas_book");
        }
        return view('project::show',compact("project","user","array_serah_terima","array_bulan","level","variabel_cash_out","variabel_carry_over","nilai_cash_out","nilai_carry_over","budget_cashout","budget_carry_over","real_bulanan","luasbook"));
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit(Request $request)
    {
        $project_detail = Project::find($request->id);
        $user = \Auth::user();
        $project = Project::get();
        $cities = City::get();
        return view('project::edit_project',compact("project","user","project_detail","cities"));
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
    public function deleteKawasan(Request $request)
    {   
        $project_kawasan = ProjectKawasan::find($request->id);
        $project_kawasan->deleted_at = date("Y-m-d H:i:s.000");
        $project_kawasan->deleted_by = \Auth::user()->id;
        $status = $project_kawasan->save();

        $user = User::find(\Auth::user()->id);
        $erems = \Config::get('constants.options.erems');
        if ( $erems == 1 ){   
            $update_cluster = DB::connection('sqlsrv3')->table('dbo.mh_cluster')->where('cluster_id', $project_kawasan->cluster_id)->update(
                ['Deleteon' => date("Y-m-d H:i:s.000"),
                'Deleteby' => $user->user_id,
                'Deleted' => 1]
            );
        }
        if ( $status ){
            return response()->json( ["status" => "0"] );
        }else{
            return response()->json( ["status" => "1"] );
        }
    }

    public function kawasan(Request $request){
        $user = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        // $unit_pending = UnitPending::all();
        // return  $project->unit_pending_baru;
        // $total_unit = count($project->units);
        $total_unit = DB::table('projects')
                        ->where("projects.id",$project->id)
                        ->where("project_kawasans.deleted_at",null)
                        ->where("bloks.deleted_at",null)
                        ->where("units.deleted_at",null)
                        ->leftJoin('project_kawasans','project_kawasans.project_id','projects.id')
                        ->leftJoin('bloks','bloks.project_kawasan_id','project_kawasans.id')
                        ->leftJoin('units','units.blok_id','bloks.id')
                        ->groupBy('projects.id')
                        ->select('projects.id as id', DB::raw("(sum(units.tanah_luas)) as luas_netto"),DB::raw("(COUNT (units.id)) as jumlah_unit"))
                        ->get();
        // return $total_unit;
        return view('project::project_kawasan2',compact("project","user","total_unit"));
    }

    public function addKawasan(Request $request){
        $user = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        $limit = 0;
        foreach ($project->kawasans as $key => $value) {
            $limit = $value->lahan_luas + $limit;
        }

        $limit = $project->luas - $limit;
        return view('project::create_kawasan',compact("project","user","limit"));
    }

    public function saveKawasan(Request $request){
        $authuser = \Auth::user();
        $project_kawasan                         = new ProjectKawasan;
        $project_kawasan->project_id             = $request->project_id;
        $project_kawasan->code                   = strtoupper($request->kode_kawasan);
        $project_kawasan->name                   = strtoupper($request->nama_kawasan);
        $project_kawasan->lahan_status           = $request->lahan_status;
        $project_kawasan->lahan_luas             = str_replace(",","",$request->luas_brutto);
        // $project_kawasan->lahan_sellable         = str_replace(",","",$request->luas_netto);
        $project_kawasan->is_kawasan             = $request->is_kawasan;
        $project_kawasan->project_type_id        = $request->project_type_id;
        $project_kawasan->description            = $request->description;
        $status = $project_kawasan->save();

        //Create Budget
        //Fungsi ini dihapus jika menu BUdget sudah Live
        $project = Project::find($request->project_id);
        // return $project->pt;
        foreach ($project->pt as $key => $value) {
           $department = Department::get();
            foreach ($department as $key2 => $value2) {

                if ( $value2->id == 1 || $value2->id == 2 ){
                    $budget = new Budget;
                    $project = Project::find($request->session()->get('project_id'));
                    $pt = Pt::find($value->pt_id);
                    // return $pt;
                    $number = \App\Helpers\Document::new_number('BDG', $value2->id,$project->id).$pt->code;
                    $budget->pt_id = $value->pt_id;
                    $budget->department_id = $value2->id;
                    $budget->project_id = $request->project_id;
                    $budget->project_kawasan_id = $project_kawasan->id;
                    $budget->no = $number;
                    $budget->start_date = date("Y-m-d H:i:s.u");
                    $budget->end_date = $request->end_date;
                    $budget->description = "Budget Generate Otomtasi Fase 1 CPMS";
                    $budget->created_by = \Auth::user()->id;
                    $budget->save();

                    $budget_tahunan                 = new BudgetTahunan;
                    $budget_tahunan->budget_id      = $budget->id;
                    $budget_tahunan->no             = \App\Helpers\Document::new_number('BDG-T', $value2->id,$project->id).$pt->code;
                    $budget_tahunan->tahun_anggaran = date("Y");
                    $budget_tahunan->description    = "Budget Tahunan Generate Otomtasi Fase 1 CPMS";
                    $status = $budget_tahunan->save();
                }
            }        
        }
        

        //Save to EREM
        $erems = \Config::get('constants.options.erems');
        if ( $erems == 1 ){
	
            $project = Project::find($request->project_id);
            $project_id_erem = $project->project_id;

            $project_pt = ProjectPt::where("project_id",$request->project_id)->get();
            if ( count($project_pt) > 0 ){
                $project_pt_id = $project_pt->first()->id;
                $project_pts = ProjectPt::find($project_pt_id);
                $pt = Pt::find($project_pts->pt_id)->pt_id;
            }else{
                $pt = "";
            }
			
            /* //$users = DB::connection('sqlsrv3')->table('erems.dbo.mh_cluster')->get();
			$users = DB::connection('sqlsrv3')->table('dbo.mh_cluster')->get();
			echo 
            //$ins_erem = DB::connection('sqlsrv3')->insert('insert into [dbo].[mh_cluster] (project_id, pt_id,code,cluster,description,Addon,Addby,Modion,Modiby) values (?, ?, ?, ?, ?, ?, ?, ?, ?)', [$project_id_erem, $pt,$request->kode_kawasan,$request->nama_kawasan,$request->nama_kawasan,date("Y-m-d H:i:s.000"),7534,date("Y-m-d H:i:s.000"),$authuser->user_id]);
            //$get_last = DB::connection('sqlsrv3')->table('dbo.mh_cluster')->where('project_id', $project_id_erem)->get();
			// user id 7534 => 14404
            //$ins_erem = DB::connection('sqlsrv3')->insert('insert into [erems].[dbo].[mh_cluster] (project_id, pt_id,code,cluster,description,Addon,Addby,Modion,Modiby) values (?, ?, ?, ?, ?, ?, ?, ?, ?)', [$project_id_erem, $pt]);
           /*  var_dump($project_id_erem);
            var_dump($pt);
            var_dump($request->kode_kawasan); 
			$kawasan = "ABC";
			$ins_erem = DB::connection('sqlsrv3')->insert("insert into [erems].[dbo].[mh_cluster] (project_id, pt_id,code) values ( '.$project_id_erem.','.$pt.','.$kawasan.'));
            //$ins_erem = DB::connection('sqlsrv3')->insert('insert into [erems].[dbo].[mh_cluster] (project_id, pt_id,code,cluster,description,Addon,Addby,Modion,Modiby) values ( '.$project_id_erem.','. $pt.','.$request->kode_kawasan.','.$request->nama_kawasan.','.$request->nama_kawasan.','.date("Y-m-d H:i:s.000").',14404,'.date("Y-m-d H:i:s.000").','.$authuser->user_id.')');
            
			//$ins_erem = DB::connection('sqlsrv3')->insert('insert into erems.dbo.mh_cluster (project_id, pt_id,code) values (1,1,1)');
			
			//$get_last = DB::connection('sqlsrv3')->table('dbo.mh_cluster')->where('project_id', $project_id_erem)->get();
            
            //$cluster_id = $get_last->last();
            //$project_kawasan_upd = ProjectKawasan::find($project_kawasan->id);
            //$project_kawasan_upd->cluster_id = $cluster_id->cluster_id;
            //$project_kawasan_upd->save(); */
			
			$users = DB::connection('sqlsrv3')->table('dbo.mh_cluster')->get();
            $ins_erem = DB::connection('sqlsrv3')->insert('insert into [dbo].[mh_cluster] (project_id, pt_id,code,cluster,description,Addon,Addby,Modion,Modiby) values (?, ?, ?, ?, ?, ?, ?, ?, ?)', [$project_id_erem, $pt,$request->kode_kawasan,$request->nama_kawasan,$request->nama_kawasan,date("Y-m-d H:i:s.000"),$authuser->user_id,date("Y-m-d H:i:s.000"),$authuser->user_id]);
            $get_last = DB::connection('sqlsrv3')->table('dbo.mh_cluster')->where('project_id', $project_id_erem)->get();
			
			$cluster_id = $get_last->last();
            $project_kawasan_upd = ProjectKawasan::find($project_kawasan->id);
            $project_kawasan_upd->cluster_id = $cluster_id->cluster_id;
            $project_kawasan_upd->save(); 

        }
        return redirect("/project/kawasan/");
    }

    public function editKawasan(Request $request){
        $user = \Auth::user();
        $project_kawasan = ProjectKawasan::find($request->id);
        $project = $project_kawasan->project;
        if ( $project_kawasan->is_kawasan == 1 ){
            $selected_1 = "selected";
            $selected_0 = "";
        }else{
            $selected_1 = "";
            $selected_0 = "selected";
        }

        
        return view('project::edit_kawasan',compact("project_kawasan","user","project","selected_1","selected_0"));
    }

    public function updateKawasan(Request $request){
        $project_kawasan = ProjectKawasan::find($request->project_kawasan);
        $project_kawasan->project_id             = $request->project_id;
        $project_kawasan->code                   = $request->kode_kawasan;
        $project_kawasan->name                   = $request->nama_kawasan;
        $project_kawasan->lahan_status           = $request->lahan_status;
        $project_kawasan->lahan_luas             = str_replace(",","",$request->luas_brutto);
        // $project_kawasan->lahan_sellable         = str_replace(",","",$request->luas_netto);
        $project_kawasan->is_kawasan             = $request->is_kawasan;
        $project_kawasan->description            = $request->description;
        $project_kawasan->project_type_id        = $request->project_type_id;
        $project_kawasan->save();
        return redirect("/project/kawasan/");
    }

    public function blokKawasan(Request $request){
        $user = \Auth::user();
        $projectkawasan = ProjectKawasan::find($request->id);
        $bloks = $projectkawasan->bloks;
        $project = $projectkawasan->project;
        return view("project::blok_kawasan",compact("projectkawasan","bloks","user","project"));
    }

    public function addblok(Request $request){
        $user = \Auth::user();
        $projectkawasan = ProjectKawasan::find($request->id);
        $project = $projectkawasan;

        
        return view("project::create_blok",compact("user","projectkawasan","project"));
    }

    public function saveblok(Request $request){
        $blok = new Blok;
        $blok->kode = $request->kode;
        $blok->project_id = $request->project_id;
        $blok->project_kawasan_id = $request->projectkawasan;
        $blok->name = $request->name;
        $blok->luas = str_replace(",","",$request->luas);
        $blok->description = $request->description;
        $status  = $blok->save();

        //Save to EREM
        $erems = \Config::get('constants.options.erems');
        if ( $erems == 1 ){
            $authuser = \Auth::user();
            $project = Project::find($request->project_id);
            $project_id_erem = $project->project_id;

            $projectkawasan = ProjectKawasan::find($request->projectkawasan);
            $cluster_id  = $projectkawasan->cluster_id;

            $project_pt = ProjectPt::where("project_id",$request->project_id)->get();
            if ( count($project_pt) > 0 ){
                $project_pt_id = $project_pt->first()->id;
                $project_pts = ProjectPt::find($project_pt_id);
                $pt = Pt::find($project_pts->pt_id)->pt_id;
            }else{
                $pt = "";
            }
            $users = DB::connection('sqlsrv3')->table('dbo.m_block')->get();
            $ins_erem = DB::connection('sqlsrv3')->insert('insert into [dbo].[m_block] (project_id, pt_id,cluster_id,code,block,description,Addon,Addby,Modion,Modiby) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [$project_id_erem, $pt,$cluster_id,$request->kode,$request->name,$request->description,date("Y-m-d H:i:s.000"),$authuser->user_id,date("Y-m-d H:i:s.000"),$authuser->user_id]);
            $get_last = DB::connection('sqlsrv3')->table('dbo.m_block')->where('cluster_id', $cluster_id)->get();
            $block_id = $get_last->last();

            $blok = Blok::find($blok->id);
            $blok->block_id = $block_id->block_id;
            $blok->save();
        }
        
        return redirect("/project/bloks/?id=".$request->projectkawasan);
    }

    public function editblok(Request $request){
        $blok = Blok::find($request->id);
        $user = \Auth::user();
        $project = $blok->kawasan->project;
        return view("project::edit_blok",compact("user","blok","project"));
    }

    public function updateblok(Request $request){

        $authuser = \Auth::user();
        $blok = Blok::find($request->blok_id);
        $blok->name = $request->name;
        $blok->luas = str_replace(",","",$request->luas);
        $blok->description = $request->description;
        $blok->save();

        $cluster_id = $blok->kawasan->cluster_id;
        $erems = \Config::get('constants.options.erems');
        if ( $erems == 1 ){
        $update_cluster = DB::connection('sqlsrv3')->table('dbo.m_block')->where('block_id', $blok->block_id)->update(['cluster_id' => $cluster_id,'Modiby' => $authuser->user_id, "Modion" => date("Y-m-d H:i:s.000")]);
        }
        return redirect("/project/bloks/?id=".$blok->kawasan->id);
    }

    public function deleteblok(Request $request){
        $blok = Blok::find($request->id);        
        $blok->deleted_at = date("Y-m-d H:i:s.000");
        $blok->deleted_by = \Auth::user()->id;
        $status = $blok->save();

        $user = User::find(\Auth::user()->id);
        $erems = \Config::get('constants.options.erems');
        if ( $erems == 1 ){   
            $update_cluster = DB::connection('sqlsrv3')->table('dbo.m_block')->where('block_id', $blok->block_id)->update(
                ['Deleteon' => date("Y-m-d H:i:s.000"),
                'Deleteby' => $user->user_id,
                'Deleted' => 1]
            );
        }

        if ( $status ){
            return response()->json( ["status" => "0"] );
        }else{
            return response()->json( ["status" => "1"] );
        }
    }

    public function units(Request $request){
        $blok = Blok::find($request->id);
        $user = \Auth::user();
        $projectkawasan = $blok->kawasan;
        $project = $projectkawasan->project;
        $purpose = Purpose::where('project_id',$project->id)->get();
        $unit = Unit::where("blok_id", $blok->id)->orderBy("name", "ASC")->get();
        return view("project::unit_kawasan",compact("user","blok","projectkawasan","project","purpose","unit"));
    }

    public function addunit(Request $request){
        $user = \Auth::user();
        $blok = Blok::find($request->id);
        $project = $blok->project_kawasan->project;
        $unittype = $project->unittype;
        $units = $blok->units;
        $pt = $project->pts;
        $global_setting = Globalsetting::where("parameter","length_number")->first()->value;
        $start = "";
        for ( $i=0;  $i < ( $global_setting - (strlen(count($units)))) ; $i++ ){
            $start .= "0";
        }
        return view("project::create_unit",compact("user","blok","project","unittype","start"));
    }

    public function unittype(Request $request){
        $project = Project::find($request->session()->get('project_id'));
        $type = $project->unittype;
        $user = \Auth::user();
        return view("project::unit_type",compact("user","type","project"));
    }

    public function addtype(Request $request){
        $project = Project::find($request->id);
        $user = \Auth::user();
        return view("project::create_type",compact("user","project"));
    }

    public function savetype(Request $request){
        $unit_type = new UnitType;
        $projectkawasan = ProjectKawasan::find($request->kawasan);

        $unit_type->kode = $request->code;
        $unit_type->project_id = $request->project_id;
        $unit_type->name = $request->name;
        $unit_type->luas_bangunan = $request->luas;
        $unit_type->luas_tanah = str_replace(",", "", $request->luas_tanah);
        $unit_type->description = $request->description;
        $unit_type->listrik = str_replace(",", "", $request->elektrik);
        $unit_type->cluster_id = $projectkawasan->id;
        $unit_type->lantai = $request->lantai;
        $unit_type->save();

        //Save to EREM
        $erems = \Config::get('constants.options.erems');
        if ( $erems == 1 ){
            if ( $request->luas > 0 ){
                $productcategory = 1;
            }else{
                $productcategory = 2;
            }

            $authuser = \Auth::user();

            $users = DB::connection('sqlsrv3')->table('dbo.mh_type')->get();
            $ins_erem = DB::connection('sqlsrv3')->insert('insert into [dbo].[mh_type] (productcategory_id,cluster_id,code,name,land_size,building_size,electricity,building_class,floor_size,floor,bedroom,bathroom,Addon,Addby,Modion,Modiby) values (?, ?, ?, ?, ?, ?, ?, ?, ?,?,?,?,?,?,?,?)', [
                $productcategory, 
                $projectkawasan->cluster_id,
                $request->code,
                $request->name,
                str_replace(",", "", $request->luas_tanah),
                $request->luas,
                str_replace(",", "", $request->elektrik),
                '',
                $request->luas,
                $request->lantai,
                0,
                0,
                date("Y-m-d H:i:s.000"),
                $authuser->user_id,
                date("Y-m-d H:i:s.000"),
                $authuser->user_id]
            );

            $get_last = DB::connection('sqlsrv3')->table('dbo.mh_type')->get();
            $type_id = $get_last->last();
            $type = UnitType::find($unit_type->id);
            $type->type_id = $type_id->type_id;
            $type->save();
        }

        return redirect("project/templatepekerjaan?id=".$unit_type->id);
    }

    public function deletetype(Request $request){
        $unit_type = UnitType::find($request->id);             
        $unit_type->deleted_at = date("Y-m-d H:i:s.000");
        $unit_type->deleted_by = \Auth::user()->id;
        $status = $unit_type->save();

        $user = User::find(\Auth::user()->id);
        $erems = \Config::get('constants.options.erems');
        if ( $erems == 1 ){   
            $update_cluster = DB::connection('sqlsrv3')->table('dbo.mh_type')->where('type_id', $unit_type->type_id)->update(
                ['Deleteon' => date("Y-m-d H:i:s.000"),
                'Deleteby' => $user->user_id,
                'Deleted' => 1]
            );
        }
        if ( $status ){
            return response()->json( ["status" => "0"] );
        }else{
            return response()->json( ["status" => "1"] );
        }
    }

    public function updatetype(Request $request){
        $unit_type = UnitType::find($request->id);
        $unit_type->name = $request->name;
        $unit_type->luas_bangunan = $request->luas;
        $unit_type->luas_tanah = $request->luas_tanah;
        $unit_type->listrik = $request->listrik;
        $status = $unit_type->save();
        if ( $status ){
            return response()->json( ["status" => "0"] );
        }else{
            return response()->json( ["status" => "1"] );
        }
    }

    public function template(Request $request){
        $project = Project::find($request->session()->get('project_id'));
        $unit_type = UnitType::find($request->id);
       // $template = $unit_type->category;
        $user = \Auth::user();
        $category = Category::get();
        $unit_category = $unit_type->category;
        return view("project::index_template",compact("user","unit_type","project","category","unit_category"));
    }

    public function addtemplate(Request $request){
        $satuan = "";
        $volume = 0;
        $unit_type = UnitType::find($request->unit_type);
        $category_detail = CategoryDetail::find($request->tipe);

        if ( $unit_type->category == "" ){
            $category_project = new CategoryProject;
            $category_project->category_detail_id = $request->tipe;
            $category_project->project_id = $request->project_id;
            $category_project->unit_type_id = $request->unit_type;
            $category_project->created_by = \Auth::user()->id;
            $category_project->save();
        }else{
            if ( $unit_type->category->category_project != "" ){
                $category_project = CategoryProject::find($unit_type->category->category_project->id);
                $category_project->category_detail_id = $request->tipe;
                $category_project->updated_by = \Auth::user()->id;
                $category_project->updated_at = date("Y-m-d H:i:s.000");
                $category_project->save();
            }

        }

        if ( $unit_type->category == "" ){
            $unit_category = new UnitTypeCategory;
            $unit_category->unit_type_id = $request->unit_type;
            $unit_category->category_project_id = $category_project->id;
            $unit_category->type = $request->tipe;
            $unit_category->created_by = \Auth::user()->id;
            $unit_category->save();
        }else{
            $unit_category = UnitTypeCategory::find($unit_type->category->id);
            $unit_category->type = $request->tipe;
            $unit_category->updated_by = \Auth::user()->id;
            $unit_category->updated_at = date("Y-m-d H:i:s.000");
            $unit_category->save();
        }

        $itempekerjaan = Itempekerjaan::get();
        if ( $unit_type->category == "" ){
            foreach ($itempekerjaan as $key => $value) {
                if ( $value->parent_id == null && $value->group_cost == "2"){
                    if ( $value->code == "100" || $value->code == "200" ){
                        $luas = $unit_type->luas_bangunan;
                    }else{
                        $luas = 0;
                    }

                    if ( $value->code == 100 ){ 

                        $unit_category_detail = new UnitTypeCategoryDetail;
                        $unit_category_detail->unit_type_category_id = $unit_category->id;
                        $unit_category_detail->itempekerjaan_id = $value->id;
                        $unit_category_detail->volume = $luas;
                        $unit_category_detail->satuan = 'm2';
                        $unit_category_detail->nilai = 0;
                        $unit_category_detail->created_by = \Auth::user()->id;
                        $unit_category_detail->save();

                        $unit_category_detail = new UnitTypeCategoryDetail;
                        $unit_category_detail->unit_type_category_id = $unit_category->id;
                        $unit_category_detail->itempekerjaan_id = $value->id;
                        $unit_category_detail->volume = $category_detail->percentage ;
                        $unit_category_detail->satuan = '%';
                        $unit_category_detail->nilai = 0;
                        $unit_category_detail->created_by = \Auth::user()->id;
                        $unit_category_detail->save();

                    } else if ( $value->code == 200 ){

                        $unit_category_detail = new UnitTypeCategoryDetail;
                        $unit_category_detail->unit_type_category_id = $unit_category->id;
                        $unit_category_detail->itempekerjaan_id = $value->id;
                        $unit_category_detail->volume = $luas;
                        $unit_category_detail->satuan = 'm2';
                        $unit_category_detail->nilai = 0;
                        $unit_category_detail->created_by = \Auth::user()->id;
                        $unit_category_detail->save();
                    }

                    elseif ( $value->code == 300 ){
                        foreach ($value->child_item as $key2 => $value2) {
                            if ( $value2->code == "300.01" ||  $value2->code == "300.02" || $value2->code == "300.03" || $value2->code == "300.04"){ 
                                if ( str_replace(" ", "", $value2->name) == "BiayaSR(Air,KWHMeter)") {
                                    $satuan = "unit";
                                    $volume = 1;
                                } elseif ( str_replace(" ", "", $value2->name) == "BiayaSR(Telp,TV,Internet)"){
                                    $satuan = "unit";
                                    $volume = 1;
                                } else if ( str_replace(" ", "", $value2->name) == "BiayaSR(Listrik)"){
                                    $satuan = "va";
                                    $volume = $unit_type->listrik;
                                } else if ( str_replace(" ", "", $value2->name) == "BiayaSR(Gas)"){
                                    $satuan = "unit";
                                    $volume = 1;
                                }   
                                $unit_category_detail = new UnitTypeCategoryDetail;
                                $unit_category_detail->unit_type_category_id = $unit_category->id;
                                $unit_category_detail->itempekerjaan_id = $value2->id;
                                $unit_category_detail->volume = $volume;
                                $unit_category_detail->satuan = $satuan;
                                $unit_category_detail->nilai = 0;
                                $unit_category_detail->created_by = \Auth::user()->id;
                                $unit_category_detail->save();                            
                            }
                        }
                    }
                }          

            }
        }

        //Save to EREM
        $erems = \Config::get('constants.options.erems');
        if ( $erems == 1 ){
            $category = Category::find($request->master_tipe);
            $authuser = \Auth::user();
            $update_cluster = DB::connection('sqlsrv3')->table('dbo.mh_type')->where('type_id', $unit_type->type_id)->update(['building_class' => $category->name,'Modiby' => $authuser->user_id, "Modion" => date("Y-m-d H:i:s.000")]);
        }
        return redirect("/project/templatepekerjaan/?id=".$request->unit_type);
    }

    public function detailtemplate(Request $request){
        $unit_category = UnitTypeCategory::find($request->id);
        $user = \Auth::user();
        $project = Project::find(($request->session()->get('project_id')));
        return view("project::detail_template",compact("unit_category","user","project"));
    }

    public function updatetemplate(Request $request){

        foreach ($request->id_ as $key => $value) {

            $unit_category_detail = UnitTypeCategoryDetail::find($request->id_[$key]);
            $unit_category_detail->volume = (int)str_replace(",", "", $request->volume_[$key]);
            $unit_category_detail->satuan = $request->satuan_[$key];
            $unit_category_detail->nilai =  (int)str_replace(",","", $request->nilai_[$key]);
            $unit_category_detail->save();
        }
        
        return redirect("/project/templatepekerjaan/?id=".$request->unit_category);
    }

    public function itempekerjaan(Request $request){
        $itempekerjaan = Itempekerjaan::find($request->id);
        $html = "";
        $start = 0;
        foreach ( $itempekerjaan->child_item as $key3 => $value3 ){
            $html .= "<tr>";
            $html .= "<td><strong>".$value3->code."</strong></td>";
            $html .= "<td style='background-color: white;color:black;' onclick='showhide(".$value3->id.")' data-attribute='1' id='btn_".$value3->id."'>".$value3->name."</td>";
            $html .= "<td>&nbsp;</td>";
            $html .= "<td>&nbsp;</td>";
            $html .= "</tr>";
            if ( count($value3->child_item) > 0 ){
                foreach ( $value3->child_item as $key4 => $value4 ){
                  if ( count($value4->child_item) > 0 ){
                    foreach ( $value4->child_item as $key5 => $value5 ){
                        $html .= "<tr>";
                        $html .= "<td><strong>".$value5->code."</strong></td>";
                        $html .= "<td style='background-color: white;color:black;' onclick='showhide(".$value5->id.")' data-attribute='1' id='btn_".$value5->id."'>".$value5->name."</td>";
                        $html .= "<td><input type='hidden' class='form-control' name='item_id_[".$start."]' value='".$value5->id."'/><input type='text' class='form-control' name='volume_[".$start."]' value='".rand(1,500)."'/></td>";
                        $html .= "<td><input type='text' class='form-control' name='satuan_[".$start."]' value='m2'/></td>";
                        $html .= "</tr>";
                        $start++;
                    }
                  }else{
                    $html .= "<tr>";
                    $html .= "<td><strong>".$value4->code."</strong></td>";
                    $html .= "<td style='background-color: white;color:black;' onclick='showhide(".$value4->id.")' data-attribute='1' id='btn_".$value4->id."'>".$value4->name."</td>";
                    $html .= "<td><input type='hidden' class='form-control' name='item_id[".$start."]' value='".$value4->id."'/><input type='text' class='form-control' name='volume_[".$start."]' value=''/></td>";
                    $html .= "<td><input type='text' class='form-control' name='satuan_[".$start."]' value=''/></td>";
                    $html .= "</tr>";
                    $start++;
                  }
                }
            }
        }
    
        $status = "1";
        if ( $status ){
            return response()->json( ["status" => "0", "html" => $html] );
        }else{
            return response()->json( ["status" => "1", "html" => "" ] );
        }

    }

    public function savetemplatedetail(Request $request){
        //print_r($request->item_id);die;
        foreach ($request->item_id_ as $key => $value) {
            if ( $request->volume_[$key] != "" && $request->satuan_[$key] != "" ){
                $TemplatepekerjaanDetail = new TemplatepekerjaanDetail;
                $TemplatepekerjaanDetail->templatepekerjaan_id = $request->template_id;
                $TemplatepekerjaanDetail->itempekerjaan_id = $request->item_id_[$key];
                $TemplatepekerjaanDetail->volume = $request->volume_[$key];
                $TemplatepekerjaanDetail->satuan = $request->satuan_[$key];
                $TemplatepekerjaanDetail->save();
            }
        }
        return redirect("/project/detail-template/?id=".$request->template_id);
    }

    public function updateproject(Request $request)
    {
        $project = Project::find($request->project_id);
        $project->subholding = $request->subholding;
        $project->code = $request->code;
        $project->name = $request->name;
        $project->luas = str_replace(",","",$request->luas);
        $project->address = $request->address;
        $project->zipcode = $request->zipcode;
        $project->phone = $request->phone;
        $project->fax    = $request->fax ;
        $project->email = $request->email;
        $project->description = $request->description;
        $project->city_id = $request->city_id;
        $project->save();

        return redirect("project/detail-update/?id=".$request->project_id);
    }

    public function saveunit(Request $request){
        $start = "";
        /* Status Unit */
        $array = array(
            "0" => "Planning",
            "1" => "Ready for Stock",
            "2" => "Sudah di WO dan blm SPK",
            "3" => "Sudah di WO dan sdh SPK"
        );

        $blok = Blok::find($request->blok);
        for ($i=1; $i <= $request->quantity ; $i++) 
        { 
            
            $units = $blok->units;
            $global_setting = Globalsetting::where("parameter","length_number")->first()->value;

            $start = "";
            for ( $j=0;  $j < ( $global_setting - (strlen(count($units) + 1) )) ; $j++ ){
                $start .= "0";
            }

            $unit_no = str_replace(" ","",$blok->kode) .'/'. $start.($request->starting_number - 1 +$i);
            $project_units                         = new Unit;
            $project_units->blok_id                = $blok->id;
            $project_units->peruntukan_id          = $request->peruntukan_id;
            $project_units->pt_id                  = $request->pt_id;
            $project_units->name                   = $unit_no;
            $project_units->code                   = $unit_no;
            $project_units->tag_kategori           = $request->tag_kategori;

            $project_units->templatepekerjaan_id   = $request->unit_template;
            $project_units->bangunan_luas          = str_replace(",", "", $request->luas_bangunan);

            $project_units->tanah_luas             = str_replace(",", "", $request->luas_tanah);
            $project_units->unit_arah_id           = $request->unit_arah_id;
            $project_units->unit_type_id           = $request->unit_type;
            $project_units->unit_hadap_id          = $request->unit_hadap;
            $project_units->is_sellable            = $request->is_sellable;
            $project_units->status = $request->is_status;
            $status = $project_units->save();

            $unit_history = new UnitHistory;
            $unit_history->unit_id = "";
            $unit_history->status = 0;
            $unit_history->status_reply_id = "";
            $unit_history->created_at = date("Y-m-d H:i:s.000");
            $unit_history->created_by = \Auth::user()->id;
            $unit_history->description = "Buat unit baru";
            $unit_history->save();

            //Save to EREM 
            $erems = \Config::get('constants.options.erems');
            if ( $erems == 1 ){   
                if ( $request->is_status == 1 ){
                    $authuser = \Auth::user();   
                    if ( $request->luas_bangunan > 0 ){
                        $productcategory = 1;
                    }else{
                        $productcategory = 2;
                    }
                    $authuser = \Auth::user();
                    $project_pt_erem = Project::find($request->project_id)->project_id;
                    $projectkawasan = ProjectKawasan::find($request->projectkawasan);
                    $pt = Pt::find($request->pt_id);
                    $datatype = UnitType::find($request->unit_type);

                    if ( $request->is_status == 0 ){
                        $is_readystock = 0;
                    }else{
                        $is_readystock = 1;
                    }

                    //$users = DB::connection('sqlsrv3')->table('dbo.m_unit')->get();
                    $ins_erem = DB::connection('sqlsrv3')->insert('insert into [dbo].[m_unit] (project_id,pt_id,cluster_id,unit_number,productcategory_id,type_id,land_size,building_size,floor_size,floor,electricity,block_id,is_readystock,is_readysell,is_readylegal,state_admistrative,Addon,Addby,Modion,Modiby) values (?, ?, ?, ?, ?, ?, ?, ?, ?,?,?,?,?,?,?,?,?,?)', [
                        $project_pt_erem, 
                        $pt->pt_id,
                        $projectkawasan->cluster_id,
                        $unit_no,
                        $productcategory,
                        $datatype->type_id,
                        str_replace(",", "", $request->luas_tanah),
                        str_replace(",", "", $request->luas_bangunan),
                        $request->luas_bangunan,
                        $datatype->lantai,
                        $datatype->electricity,
                        $blok->block_id,
                        $is_readystock,
                        1,
                        1,
                        1,
                        date("Y-m-d H:i:s.000"),
                        $authuser->user_id,
                        date("Y-m-d H:i:s.000"),
                        $authuser->user_id]
                    );

                    $get_last = DB::connection('sqlsrv3')->table('dbo.m_unit')->get();
                    $unit_id = $get_last->last();

                    $unit = Unit::find($project_units->id);
                    $unit->unit_id = $unit_id->unit_id;
                    $unit->save();
                } 
            }
        }

        return redirect("project/units/?id=".$request->blok);
    }

    public function getluas(Request $request){
        $type = UnitType::find($request->id);
        $data['luas_tanah'] = $type->luas_tanah;
        $data['luas_bangunan'] = $type->luas_bangunan;
        $date['kategori'] = "--";
        $kategori = "--";
        if($type->category != null){
            if($type->category->category_project != null){
                $date['kategori'] = $type->category->category_project->category_detail->category->name;
                $kategori = $type->category->category_project->category_detail->category->name." ".$type->category->category_project->category_detail->sub_type;
            }
        }
        $luas_tanah = $type->luas_tanah;
        $luas_bangunan = $type->luas_bangunan;
        json_encode($data);
        return response()->json( ["status" => "0", "data" => $data, "luas_tanah" => $type->luas_tanah, "luas_bangunan" => $type->luas_bangunan, "kategori" => $kategori] );
    }

    public function viewunit(Request $request ){
        $unit = Unit::find($request->id);
        $project = Project::find($request->session()->get('project_id'));
        $user = \Auth::user();
        if ( count($unit->progresses) > 0  ){
            $readonly = "disabled";
        }else{
            $readonly = "";
        }

        if ( $unit->is_sellable == 1 ){
            $sellable_1 = "selected";
            $sellable_0 = "";
        }else{
            $sellable_1 = "";
            $sellable_0 = "selected";
        }

        $array = array(
            "1" => "",
            "2" => "",
            "3" => "",
            "4" => "",
            "5" => "",
            "6" => "",
            "7" => "",
            "8" => "",
            "" => ""
        );

        $array_status = array(
            0 => "Draft P&D",
            1 => "Planning",
            3 => "Stock",
            5 => "Sold"
        );

        $array_reason = array(
            "1" => "Perubahan Cacah ( luas, type )",
            "2" => "Beda Ukuran",
            "3" => "Ditunda / Pending",
            "4" => "Alih fungsi",
            "5" => "Perubahan Konsumen",
            "6" => "Ubah Nomor Unit",
            "7" => "",
            "14" => "",
            "" => "",
            "0" => ""
        );


        $array[$unit->unit_arah_id] = "selected";
        $history = array();
        $unit_history = UnitHistory::where("unit_id",$unit->unit_id)->orderBy("id","desc")->get();
        // if($unit_history[0]->unit_id == null){
        //     $unit_history = UnitHistory::where("id",$unit->id)->orderBy("id","desc")->get();
        // }
        $start = 0;
        $enabled = "";
        if ( count($unit_history) > 0 ){
            foreach ($unit_history as $key => $value) {
                $history[$start] = array(
                    "status" => "",
                    "status_reason" => $array_reason[$value->status_reply_id],
                    "description" => $value->description,
                    "tanggal" => date("d/M/Y H:i:s", strtotime($value->created_at))
                );
                $start++;
            }
        }
        

        $unit_pendings = array();
        if ( count($unit->pending ) > 0 ){
            $start = 0;
            foreach ($unit->pending as $key => $value) {
                if ( $unit->pending['name'] == 3 ){
                    $enabled = "disabled";
                }else{
                    $enabled = "";
                }
                $unit_pendings[$start] = array(
                    "id" => $unit->pending['id'],
                    "status" => $array_reason[$unit->pending['name']]
                );
            }
        }

        if ( $unit->status == 1 ){
            $planning_selected = "selected";
            $draft_selected = "";
        }elseif ( $unit->status == 0 ){
            $planning_selected = "";
            $draft_selected = "selected";
        }else{
            $planning_selected = "";
            $draft_selected = "";
        }
        $purpose = Purpose::where('project_id',$project->id)->get();
        // $unit = DB::connection('sqlsrv3')->table('dbo.m_unit')->where('project_id',$project->project_id)->where('block_id',$unit->blok->block_id)->select("*")->get();
        // return $unit;
        return view("project::view_unit",compact("enabled","unit_pendings","array","project","user","unit","readonly","sellable_1","sellable_0","history","planning_selected","draft_selected",'purpose'));
    }

    public function updateunit(Request $request){
        $unit = Unit::find($request->unit);
        $unit->pt_id = $request->pt_id;
        $unit->unit_arah_id = $request->unit_arah_id;
        $unit->unit_type_id = $request->unit_type;
        $unit->name = $request->unit_nomor;
        $unit->tanah_luas = $request->luas_tanah;
        $unit->bangunan_luas = $request->luas_bangunan;
        $unit->is_sellable = $request->is_sellable;
        $unit->tag_kategori = $request->tag_kategori;
        $unit->unit_hadap_id = $request->unit_position;
        $unit->status = $request->is_status;
        $unit->purpose_id = $request->purpose;
        $unit->updated_at = date("Y-m-d H:i:s.000");
        $unit->updated_by = \Auth::user()->id;
        $unit->luas_tanah_tambahan = $request->luas_tanah_tambahan;
        $unit->save();

        if ( $request->is_status == 1 ){
            $unit_pending = UnitPending::where("unit_id",$unit->unit_id)->get();
            if ( count($unit_pending) > 0 ){
                $unit_pending_id = $unit_pending->first();
                $pending = UnitPending::find($unit_pending_id->id);
                $pending->deleted_at = date("Y-m-d H:i:s.000");
                $pending->deleted_by = \Auth::user()->id;
                $pending->save();

                if ( $pending->status_reply_id == 3 ){
                    $unit_inactive = Unit::find($request->unit);
                    $unit_inactive->inactive_at = date("Y-m-d H:i:s.000");
                    $unit_inactive->inactive_by = \Auth::user()->id;
                    $unit_inactive->save();
                }

            }
        }

        $arrayarrah = array(
            "1" => 4,
            "2" => 38,
            "3" => 1,
            "4" => 28,
            "5" => 2,
            "6" => 9,
            "7" => 3,
            "8" => 27
        );

        //Save to EREM    
        $erems = \Config::get('constants.options.erems');
        if ( $erems == 1 ){   
            if ( $unit->status == 1 ){
                $authuser = \Auth::user();   
                if ( $request->luas_bangunan > 0 ){
                    $productcategory = 1;
                }else{
                    $productcategory = 2;
                }
                $authuser = \Auth::user();
                $datatype = UnitType::find($unit->unit_type_id);
                $unitarah =  UnitArah::find($request->unit_position);
                $pt = Pt::find($request->pt_id);
                // $users = DB::connection('sqlsrv3')->table('dbo.m_unit')->get();
                if ( $unit->unit_id != "" ){

                    $update_cluster = DB::connection('sqlsrv3')->table('dbo.m_unit')->where('unit_id', $unit->unit_id)->update(
                        ['unit_number' => $request->unit_nomor,
                        'productcategory_id' => $productcategory,
                        'type_id' => $datatype->type_id,
                        'building_size' => str_replace(",", "", $request->luas_bangunan),
                        'land_size' => str_replace(",", "", $request->luas_tanah),
                        'floor_size' => str_replace(",", "", $request->luas_tanah),
                        'floor' => $datatype->lantai,
                        'electricity' => $datatype->electricity,
                        'position_id' => $unitarah->position_id,
                        'Modiby' => $authuser->user_id, 
                        'side_id' => $arrayarrah[$request->unit_arah_id],
                        "Modion" => date("Y-m-d H:i:s.000"),
                        "pt_id" => $pt->pt_id]
                    );
                }else{
                    // $update_cluster = DB::connection('sqlsrv3')->table('dbo.m_unit')->where('unit_id', $unit->unit_id)->update(
                    //     ['Deleteon' => "",
                    //     'Deleteby' => $user->user_id,
                    //     'Modiby' => $authuser->user_id,
                    //     'Modion' => date("Y-m-d H:i:s.000"),
                    //     'is_readystock' => 1,
                    //     'state_admistrative' => 1
                    //     ]
                    // );
                }
                

                /*$ins_erem = DB::connection('sqlsrv3')->insert('insert into [dbo].[m_unit] (project_id,pt_id,cluster_id,unit_number,productcategory_id,type_id,land_size,building_size,floor_size,floor,electricity,block_id,is_readystock,state_admistrative,Addon,Addby,Modion,Modiby) values (?, ?, ?, ?, ?, ?, ?, ?, ?,?,?,?,?,?,?,?,?,?)', [
                    $project_pt_erem, 
                    $pt->pt_id,
                    $projectkawasan->cluster_id,
                    $unit_no,
                    $productcategory,
                    $datatype->type_id,
                    str_replace(",", "", $request->luas_tanah),
                    str_replace(",", "", $request->luas_bangunan),
                    $request->luas_bangunan,
                    $datatype->lantai,
                    $datatype->electricity,
                    $blok->block_id,
                    $is_readystock,
                    1,
                    date("Y-m-d H:i:s.000"),
                    $authuser->user_id,
                    date("Y-m-d H:i:s.000"),
                    $authuser->user_id]
                );

                $get_last = DB::connection('sqlsrv3')->table('dbo.m_unit')->get();
                $unit_id = $get_last->last();

                $unit = Unit::find($project_units->id);
                $unit->unit_id = $unit_id->unit_id;
                $unit->save();*/
            }  
        }
        
        $unit_history = new UnitHistory;
        $unit_history->unit_id = $unit->unit_id;
        $unit_history->status = $request->is_status;
        $unit_history->created_at = date("Y-m-d H:i:s.000");
        $unit_history->created_by = \Auth::user()->id;
        $unit_history->description = "Update Unit Luas Tanah : ".$request->luas_tanah.', Luas Bangunan : '.$request->luas_bangunan;
        $unit_history->save();
        return redirect("/project/units/?id=".$unit->blok->id);
    }

    public function savehppupdate(Request $request){
        $project = Project::find($request->project_id);
        /*$hpp = $project->hpp_update;
        $hpp = HppUpdate::find($hpp->last()->id);
        $hpp->luas_book = str_replace(",", "", $request->luas_book);
        $hpp->save();*/
        if ( $project->total_nilai_kontrak > 0 ){
            $budget = ( $project->total_budget - $project->total_nilai_kontrak ) + $project->total_nilai_kontrak;
        }else{
            $budget = $project->total_budget;
        }

        if ( $project->netto > 0 ){
            $hpp_akhir = $project->total_budget / $project->netto;
        }else{
            $hpp_akhir = 0;
        }


        $hpp = new HppUpdate;
        $hpp->project_id = $project->id;
        $hpp->nilai_budget = $budget;
        $hpp->luas_book = $request->luas_book;
        $hpp->luas_erem = $request->luas_erem;
        $hpp->netto = $project->netto;
        $hpp->hpp_book = $hpp_akhir;
        $hpp->save();
        $nilai = 0;

        // foreach ($project->budgets as $key => $value) {
                
        // }

        return redirect("/project/detail?id=".$project->id);
    }

    public function getDevCostTerbayarAttribute(){
        $nilai = 0;
        foreach ($this->spks as $key => $value) {
            $nilai = ( $value->bap * $spk->nilai ) + $nilai;
        }
        return $nilai;
    }

    public function getHutangBayarAttribute(){
        $nilai = 0;
        foreach ($this->spks as $key => $value) {
            $nilai = ( $value->bap * $spk->nilai ) + $nilai;
        }
        return $nilai;
    }

    public function unithadap(Request $request){
        $user = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        $hadap = $project->hadap;
        return view('project::unit_arah',compact("user","project","hadap"));
    }

    public function savehadap(Request $request){
        $unitarah = new UnitArah;
        $unitarah->name = $request->arah;
        $unitarah->project_id = $request->project_id;
        //$unitarah->code = $request->code;
        $unitarah->project_kawasan_id = $request->project_kawasan_id;
        $unitarah->pt_id = $request->pt_id;
        $unitarah->save();

        $pt_id = NULL;
        $cluster_id = NULL;
        $project_id = NULL;
        $erems = \Config::get('constants.options.erems');
        if ( $erems == 1 ){   
            $cluster = ProjectKawasan::find($request->project_kawasan_id);
            if ( $cluster != "" ){
                $cluster_id = $cluster->cluster_id;
            }

            $pt = Pt::find($request->pt_id);
            if ( $pt != "" ){
                $pt_id = $pt->pt_id;
            }

            $project = Project::find($request->project_id);
            if ( $project != "" ){
                $project_id = $project->project_id;
            }
            
            $authuser = User::find(\Auth::user()->id)->user_id;
            $users = DB::connection('sqlsrv3')->table('dbo.m_position')->get();
            $ins_erem = DB::connection('sqlsrv3')->insert('insert into [dbo].[m_position] (position,cluster_id,project_id,pt_id,code,Addby,Modiby,Active,Deleted) values (?,?,?,?,?,?,?,?,?)', [
                $request->arah,
                $cluster_id,
                $project_id,
                $pt_id,
                $request->code,
                $authuser,
                $authuser,
                1,
                0]
            );

            $get_last = DB::connection('sqlsrv3')->table('dbo.m_position')->get();
            $unitarah_id = $get_last->last();
            $unit_arah = UnitArah::find($unitarah->id);
            $unit_arah->position_id = $unitarah_id->position_id;
            $unit_arah->save();
        }
        return redirect("/project/unit-hadap");
    }

    public function deletehadap(Request $request){
        $user = User::find(\Auth::user()->id);
        $unitarah = UnitArah::find($request->id);
        $unitarah->deleted_at = date("Y-m-d H:i:s.000");
        $unitarah->deleted_by = $user->id;
        $status = $unitarah->delete();
        $erems = \Config::get('constants.options.erems');
        if ( $erems == 1 ){   
            $update_cluster = DB::connection('sqlsrv3')->table('dbo.m_position')->where('position_id', $unitarah->position_id)->update(
                ['Deleteon' => date("Y-m-d H:i:s.000"),
                'Deleteby' => $user->user_id,
                'Deleted' => 1]
            );
        }
        if ( $status ){
            return response()->json( ["status" => "0"] );
        }else{
            return response()->json( ["status" => "1"] );
        }
    }

    public function dataumum(Request $request) {
        $user = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));  
        $cities = City::get();  
        return view('project::data_umum',compact("project","user","level","cities"));
    }

    public function updatedataumum(Request $request)
    {
        $project = Project::find($request->session()->get('project_id'));
        $project->luas = str_replace(",","",$request->luas);
        $project->address = $request->address;
        $project->zipcode = $request->zipcode;
        $project->phone = $request->phone;
        $project->fax    = $request->fax ;
        $project->email = $request->email;
        $project->description = $request->description;
        $project->city_id = $request->city_id;
        $project->luas_nonpengembangan = str_replace(",","",$request->luas_nonpengembangan);
        $project->save();

        $project_history = new ProjectHistory;
        $project_history->project_id = $request->session()->get('project_id');
        $project_history->luas_dikembangkan = str_replace(",","",$request->luas);
        $project_history->luas_non_pengembangan  = str_replace(",","",$request->luas_nonpengembangan);
        $project_history->created_by = \Auth::user()->id;
        $project_history->pt_id = $request->pt_id;
        $project_history->save();

        return redirect("project/data-umum/");
    }


    public function senderems(Request $request) {
        $user_detail = User::find(\Auth::user()->id);
        if ( isset($request->unit_) ){
            foreach ($request->unit_ as $key => $value) {
                $unit = Unit::find($request->unit_[$key]);
                $unit->status = 1;
                $unit->save();
                
                $erems = \Config::get('constants.options.erems');
                if ( $erems == 1 ){   
                    $authuser = \Auth::user();   
                    if ( $unit->bangunan_luas > 0 ){
                        $productcategory = 1;
                    }else{
                        $productcategory = 2;
                    }
                    $authuser = \Auth::user();
                    $project_pt_erem = Project::find($request->session()->get('project_id'))->project_id;
                    $projectkawasan = ProjectKawasan::find($unit->blok->kawasan->id);
                    $pt = Pt::find($unit->pt_id);
                   
					
					if ( $unit->unit_type_id ) {
						$lantai = $unit->type->lantai;
						$listrik = $unit->type->listrik;
					}else{
						$lantai = null;
						$listrik = null;
					}
                    

                    if ( $unit->is_status == 0 ){
                        $is_readystock = 0;
                    }else{
                        $is_readystock = 1;
                    }

                    $datatype = UnitType::find($unit->unit_type_id);
                    if ( $datatype != "" ){
                        $type = $datatype->type_id;
                        $lantai = $datatype->lantai;
                        $listrik = $datatype->listrik;
                    }else{
                        $type = NULL;
                        $listrik = 0;
                        $lantai = 0;
                    }
                    // $users = DB::connection('sqlsrv3')->table('dbo.m_unit')->where("project_id", $project_pt_erem)->select("unit_id")->get();
                    // return $users;
                    if ( $unit->unit_id == "" ){
                        $ins_erem = DB::connection('sqlsrv3')->insert('insert into [dbo].[m_unit] (project_id,pt_id,cluster_id,unit_number,productcategory_id,type_id,land_size,building_size,floor_size,floor,electricity,block_id,is_readystock,is_readysell,is_readylegal,state_admistrative,Addon,Addby,Modion,Modiby,position_id,purpose_id,kelebihan) values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)', [
                            $project_pt_erem, 
                            $pt->pt_id,
                            $projectkawasan->cluster_id,
                            $unit->name,
                            $productcategory,
                            $datatype->type_id,
                            str_replace(",", "", $unit->tanah_luas),
                            str_replace(",", "", $unit->bangunan_luas),
                            $unit->bangunan_luas,
                            $lantai,
                            $listrik,
                            $unit->blok->block_id,
                            $is_readystock,
                            1,
                            1,
                            1,
                            date("Y-m-d H:i:s.000"),
                            $authuser->user_id,
                            date("Y-m-d H:i:s.000"),
                            $authuser->user_id,
                            $unit->arah->position_id,
                            $unit->purpose->purpose_id,
                            $unit->luas_tanah_tambahan
                            ]
                        );

                        $get_last = DB::connection('sqlsrv3')->table('dbo.m_unit')->where("project_id", $project_pt_erem)->where("pt_id", $pt->pt_id)->where("unit_number", $unit->name)->select("unit_id")->orderBy("unit_id","Desc")->get();
                        // $get_last = DB::connection('sqlsrv3')->select(DB::raw("SELECT MAX(unit_id) as unit_id FROM dbo.m_unit"));
                        
                        if ( $get_last[0]->unit_id != "" ){
                            $unit_id =  $get_last[0]->unit_id;
                            $unit = Unit::find($unit->id);
                            $unit->unit_id = $unit_id;
                            $unit->save();
                        }

                        $unit_history = new UnitHistory;
                        $unit_history->unit_id = $unit->unit_id;
                        $unit_history->status = 1;
                        $unit_history->status_reply_id = "";
                        $unit_history->created_at = date("Y-m-d H:i:s.000");
                        $unit_history->created_by = \Auth::user()->id;
                        $unit_history->description = "Dikirim ke EREMS";
                        $unit_history->save();

                    }else{
						
                        $update_cluster = DB::connection('sqlsrv3')->table('erems.dbo.m_unit')->where('unit_id', $unit->unit_id)->update(
                            ['Deleteon' => "",
                            'Modiby' => $authuser->user_id,
                            'Modion' => date("Y-m-d H:i:s.000"),
                            'is_readystock' => 1,
                            'state_admistrative' => 1,
                            'type_id' => $unit->type->type_id,
							'land_size' => str_replace(",", "", $unit->tanah_luas),
							'building_size' => str_replace(",", "", $unit->bangunan_luas),
                            'floor_size' => $unit->bangunan_luas,
                            'productcategory_id' => $productcategory,
                            ]
                        );

                        $unit_pending_data = UnitPending::where("unit_id",$unit->unit_id)->get();
                        if ( count($unit_pending_data) > 0 ){
                            $unit_pending = UnitPending::find($unit_pending_data->first()->id);
                            $unit_pending->delete();
                            
                        }

                        $unit_history = new UnitHistory;
                        $unit_history->unit_id = $unit->unit_id;
                        $unit_history->status = 1;
                        $unit_history->status_reply_id = "";
                        $unit_history->created_at = date("Y-m-d H:i:s.000");
                        $unit_history->created_by = \Auth::user()->id;
                        $unit_history->description = "Update unit ke erems Luas tanah : ".str_replace(",", "", $unit->tanah_luas).", Luas bangunan : ". $unit->bangunan_luas;
                        $unit_history->save();

                    }

                }   


            }
        }
        return redirect("/project/units/?id=".$request->blok_id);
    }

    public function deleteunit(Request $request){
        $user = User::find(\Auth::user()->id);
        $explode = explode(",", $request->unit_id);
        foreach ($explode as $key => $value) {
            if ( $value != "" ){                
                $unit = Unit::find($value);                   
                $unit->deleted_at = date("Y-m-d H:i:s.000");
                $unit->deleted_by = \Auth::user()->id;
                $unit->save();

                $erems = \Config::get('constants.options.erems');
                if ( $erems == 1 ){   
                    $update_cluster = DB::connection('sqlsrv3')->table('dbo.m_unit')->where('unit_id', $unit->unit_id)->update(
                        ['Deleteon' => date("Y-m-d H:i:s.000"),
                        'Deleteby' => $user->user_id,
                        'Deleted' => 1]
                    );
                }
            }
        }

        return response()->json(["status" => "0"]);
    }

    public function spesifikasitemplate(Request $request){
        $unit_type = UnitType::find($request->id);
        $user = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        $type_spec = TypeSpecification::get();
        return view("project::type_spec",compact("user","unit_type","project","type_spec"));
    }

    public function savespectempalte(Request $request){
        
        $array_type = array(
            "image/jpeg",
            "image/png"
        );

        $type =  $request->file('filename')->getMimeType();

        $unit_type_spec = new UnitTypeSpecification;
        $unit_type_spec->unit_type_id = $request->unit_type_id;
        $unit_type_spec->gambar = $request->type_spec;
        $unit_type_spec->save();

        $unit_latest = UnitTypeSpecification::find($unit_type_spec->id);
        if ( $request->filename != "" ){
            $uploadedFile = $request->file('filename');  
            $pathpdf = $uploadedFile->store('public/planning/'.$request->unit_type_id); 
            $unit_latest->file = $pathpdf;

            if ( $type == "image/jpeg" || $type = "image/png"){
               /* $path = "/public/storage/planning/".$request->unit_type_id;
                $paththumbs = $uploadedFile->store('public/planning/'.$request->unit_type_id); 
                $tmp_thumbs = explode("/",$paththumbs);
                
                $thumbs = Image::make($path.'/'.$tmp_thumbs[3]);
                $thumbs->fit(300,200);
                $thumbs->save();*/
                $unit_latest->thumbs = $pathpdf;
            }

            $unit_latest->save();
        }

        return redirect("project/spesifikasi-template?id=".$request->unit_type_id);
    }

    public function deletespec(Request $request){
        $unit_type_spec = UnitTypeSpecification::find($request->id);
        $unit_type_spec->deleted_at = date("Y-m-d H:i:s.000");
        $unit_type_spec->deleted_by = \Auth::user()->id;
        $unit_type_spec->save();
        return response()->json(["status" => "0"]);
    }

    public function todolist(Request $request){
        $project = Project::find($request->id);
        $todolist = $project->approval_success;
        $html = "";
        if ( count($todolist) > 0 ){
            foreach ($todolist as $key => $value) {
                if ( $value['name'] != "" || $value['url'] != "" ||$value['id'] != "" ){
                    $html .= "<tr>";
                    $html .= "<td><strong>".$value['document_type']."</strong></td>";
                    $html .= "<td>".$value['name']." <span class='".$value['class']."'>".$value['status']."</span></td>";
                    $html .= "<td><a class='btn btn-success' href='".$value['url'].$value['id']."'>Detail</a></td>";
                    $html .= "</tr>";
                }
            }
        }else{
            $html .= "<tr><td>(0) items</td></tr>";
        }

        return response()->json(["status" => "0", "html" => $html]);
    }

    public function generateunit(Request $request){
        $blok = Blok::find($request->blok);
        for ($i=1; $i <= $request->total_unit ; $i++) { 
            
            $units = $blok->units;
            $global_setting = Globalsetting::where("parameter","length_number")->first()->value;

            $start = "";
            for ( $j=0;  $j < ( $global_setting - (strlen(count($units) + 1) )) ; $j++ ){
                $start .= "0";
            }

            $unit_no = str_replace(" ","",$blok->kode) .'/'. $start.(count($units) +$i);
            $number = $start.(count($units) +$i);
            if ( strlen($number) > 3 ){
                $number = substr($number, 1);
                $unit_no = str_replace(" ","",$blok->kode) .'/'.$number;
            }

            $project_units                         = new Unit;
            $project_units->blok_id                = $blok->id;
            $project_units->pt_id                  = NULL;
            $project_units->name                   = $unit_no;
            $project_units->code                   = $unit_no;
            $project_units->tag_kategori           = 'B';
            $project_units->status = 0;
            $project_units->is_sellable = 1;
            $status = $project_units->save();
        }
        return response()->json(["status" => 0]);
    }

    public function unitsold(Request $request){    
        $project = Project::find($request->session()->get('project_id'));   
        $bln = $request->bln;
        $user = \Auth::user();
        $array_total_sold = $project->total_sold;
        $start = 0;
        $array_unit = array();
        foreach ($array_total_sold as $key => $value) {
            if ( $key == $bln ){
                foreach ($value['unit_id'] as $key2 => $value2) {
                    $unit = Unit::find($value2);
                    if ( $unit->type != "" ){
                        $type = $unit->type->name;
                    }else{
                        $type = "";
                    }
                    if($unit->tag_kategori == "B"){
                        if($unit->is_spk == 0){
                            $array_unit[$start] = array(
                                "id" => $unit->id,
                                "kawasan" => $unit->blok->kawasan->name,
                                "blok" => $unit->blok->name,
                                "name" => $unit->name,
                                "serah_terima" => date("d/M/Y",strtotime($unit->serah_terima_plan)),
                                "pembayaran" => $unit->pembayaran,
                                "type" => $type
                            );
                            $start++;
                        }
                    }
                }
            }
        }
        
        return view("project::unit_sold",compact("user","bln","project","array_unit"));
    }

    public function updatepending(Request $request){
    	if ( $request->status == "0" ){
            $unit_pending = UnitPending::find($request->pending_id);
            $unit_pending->deleted_at = date("Y-m-d H:i:s.000");
            $unit_pending->deleted_by = \Auth::user()->id;
            $unit_pending->save();
        }else{
            $unit_pending = UnitPending::find($request->pending_id);
            $status = 0;
            if ( $unit_pending->status_reply_id == 3 ){
                $status = 1;
            }


            $unit = Unit::find($request->unit_id);
            $unit->status = 0;
            $unit->updated_by = \Auth::user()->id;
            $unit->updated_at = date("Y-m-d H:i:s.000");
            $unit->save();

            if ( $unit_pending->status_reply_id == 3 ){
                $unit_inactive = Unit::find($request->unit_id);
                $unit_inactive->inactive_at = date("Y-m-d H:i:s.000");
                $unit_inactive->inactive_by = \Auth::user()->id;
                $unit_inactive->save();
            }

            //Insert to History 
            /*$unit_history = new UnitHistory;
            $unit_history->status = 0;
            $unit_history->status_reply_id  = $unit_pending->status_reply_id;
            $unit_history->description = "Update Unit";
            $unit_history->created_by = \Auth::user()->id;
            $unit_history->created_at = date("Y-m-d H:i:s.000");
            $unit_history->save();*/
        }

        return response()->json(["status" => 0 ]);
    }

    public function updateUnitChange(Request $request){

        if ( $request->unit_id != "" ){
            foreach ($request->unit_id as $key => $value) {
                
                $unit = Unit::find($request->unit_id[$key]);
                $unit->tanah_luas = $request->tanah_luas[$key];
                $unit->bangunan_luas = $request->bangunan_luas[$key];
                $unit->updated_by = \Auth::user()->id;
                $unit->updated_at = date("Y-m-d H:i:s.000");
                $unit->save();

                $unit_history = new UnitHistory;
                $unit_history->unit_id = $unit->unit_id;
                $unit_history->status = NULL;
                $unit_history->created_at = date("Y-m-d H:i:s.000");
                $unit_history->created_by = \Auth::user()->id;
                $unit_history->description = "Update Unit Luas Tanah : ".$request->tanah_luas[$key].', Luas Bangunan : '.$request->bangunan_luas[$key]. ' From Change Price';
                $unit_history->save();

                $unit_write = UnitChangedPrices::where("unit_id",$unit->unit_id)->get();
                if ( count($unit_write) > 0 ){
                    $unit_write_s = UnitChangedPrices::find($unit_write->first()->id);
                    $unit_write_s->updated_by = \Auth::user()->id;
                    $unit_write_s->updated_at = date("Y-m-d H:i:s.000");
                    $unit_write_s->save();
                }
            }
        }

        return redirect("project/kawasan");
    }

    public function test(Request $request){
        // $cluster = DB::connection('sqlsrv3')->table('dbo.mh_project')->insert('select * from [dbo].[mh_cluster] where project_id = 2076 ')->get();

        // return "hai fungsi ini saya matikan";
        $project = Project::find($request->project_id);

        // $workorder = \Modules\Workorder\Entities\Workorder::where('budget_tahunan_id',$project->id)->get();

        // foreach ($workorder as $key => $value) {
        //     # code...
        //     if($value->rabs != null){
        //         foreach ($value->rabs as $key2 => $value2){
        //             // if($value2->tenders != null){
        //             //     foreach ($value2->tenders as $key3 => $value3){
        //             //        $value3->delete();
        //             //     }

        //             // }
        //             $value2->delete();
        //         }

        //     }
        //     $value->delete();
        // }

        // foreach ($project->spks as $key => $value) {
        //     # code...
        //     $value->delete();
        // }

        // return $workorder;

        // foreach ($project->units as $key => $value) {
        //     # code...
        //     $value->delete();
        // }

        // foreach ($project->bloks as $key => $value) {
        //     # code...
        //     $value->delete();
        // }

        // foreach ($project->unittype as $key => $value) {
        //     # code...
        //     $value->delete();
        // }
        
        // foreach ($project->hadap as $key => $value) {
        //     # code...
        //     $value->delete();
        // }
        
        // foreach ($project->budget_tahunans as $key => $value) {
        //     # code...
        //     $value->delete();
        // }

        // foreach ($project->budgets as $key => $value) {
        //     # code...
        //     $value->delete();
        // }

        // foreach ($project->kawasans as $key => $value) {
        //     # code...
        //     $value->delete();
        // }

        // return "hai";
        // foreach ($project->workorders as $key => $value) {
        //     # code...
        //     $value->delete();
        // }
        

        // return \Modules\Rab\Entities\Rab::where('project_id',61)->get();

        //
        //berikut fungsi untuk menarik townplanning dari erems (Aghi Wardani) "hati-hati! ketika di ubah"
        //
        
        $cluster = DB::connection('sqlsrv3')->table('dbo.mh_cluster')->where('project_id',$project->project_id)->where('Deleted',0)->select("*")->get();
        $cek_kawasan = ProjectKawasan::where('project_id', $request->project_id)->get();
        for ($i = 0; $i < count($cluster); $i++) {
            $boolean = 0;
            foreach($cek_kawasan as $key=> $nilai){
                // if($cluster[$i]->cluster_id == 2101){
                //     return $cluster[$i]->cluster_id;
                // }
                if($nilai->cluster_id == $cluster[$i]->cluster_id){
                    $boolean = 1;
                    break;
                }
            }
            if($boolean == 0){
                $authuser = \Auth::user();

                $project_kawasan                         = new ProjectKawasan;
                $project_kawasan->project_id             = $request->project_id;
                $project_kawasan->code                   = strtoupper($cluster[$i]->code);
                $project_kawasan->name                   = strtoupper($cluster[$i]->cluster);
                $project_kawasan->lahan_status           = 1;
                $project_kawasan->lahan_luas             = 0;
                $project_kawasan->lahan_sellable         = 0;
                $project_kawasan->is_kawasan             = 1;
                $project_kawasan->project_type_id        = 2;
                $project_kawasan->description            = $cluster[$i]->description;
                $project_kawasan->cluster_id             = $cluster[$i]->cluster_id;
                $status = $project_kawasan->save();

                //Create Budget
                //Fungsi ini dihapus jika menu BUdget sudah Live
                // $project = Project::find($request->project_id);
                foreach ($project->pt as $key => $value) {
                $department = Department::get();
                    foreach ($department as $key2 => $value2) {

                        if ( $value2->id == 1 || $value2->id == 2 ){
                            $budget = new Budget;
                            $project = Project::find($request->project_id);
                            $pt = Pt::find($value->pt_id);

                            $number = \App\Helpers\Document::new_number('BDG', $value2->id,$project->id).$pt->code;
                            $budget->pt_id = $value->pt_id;
                            $budget->department_id = $value2->id;
                            $budget->project_id = $request->project_id;
                            $budget->project_kawasan_id = $project_kawasan->id;
                            $budget->no = $number;
                            $budget->start_date = date("Y-m-d H:i:s.u");
                            $budget->end_date = $request->end_date;
                            $budget->description = "Budget Generate Otomtasi Fase 1 CPMS";
                            $budget->created_by = \Auth::user()->id;
                            $budget->save();

                            $budget_tahunan                 = new BudgetTahunan;
                            $budget_tahunan->budget_id      = $budget->id;
                            $budget_tahunan->no             = \App\Helpers\Document::new_number('BDG-T', $value2->id,$project->id).$pt->code;
                            $budget_tahunan->tahun_anggaran = date("Y");
                            $budget_tahunan->description    = "Budget Tahunan Generate Otomtasi Fase 1 CPMS";
                            $budget_tahunan->created_by     = \Auth::user()->id;
                            $status = $budget_tahunan->save();
                        }
                    }        
                }
            }
        }  

        $cek_kawasan = ProjectKawasan::where('project_id', $request->project_id)->get();
        foreach($cek_kawasan as $key=> $nilai2){
            $unit_type = DB::connection('sqlsrv3')->table('dbo.mh_type')->where('cluster_id',$nilai2->cluster_id)->where('Deleted',0)->select("*")->get();
            $cek_type = UnitType::where('cluster_id', $nilai2->cluster_id)->get();

            for ($k = 0; $k < count($unit_type); $k++) {
                $boolean = 0;
                foreach($cek_type as $key=> $nilai){
                    if($nilai->type_id == $unit_type[$k]->type_id){
                        $boolean = 1;
                        break;
                    }
                }
                if($boolean == 0){
                    $unit_type_simpan                  = new UnitType;
                    $kawasan = ProjectKawasan::where('cluster_id', $nilai2->cluster_id)->first();
                    $unit_type_simpan->kode            = $unit_type[$k]->code;
                    $unit_type_simpan->project_id      = $request->project_id;
                    $unit_type_simpan->name            = $unit_type[$k]->name;
                    $unit_type_simpan->luas_bangunan   = $unit_type[$k]->building_size;
                    $unit_type_simpan->luas_tanah      = $unit_type[$k]->land_size;
                    $unit_type_simpan->description     = $unit_type[$k]->description;
                    $unit_type_simpan->listrik         = (int)str_replace(",", "", $unit_type[$k]->electricity);
                    $unit_type_simpan->cluster_id      = $kawasan->id;
                    $unit_type_simpan->type_id         = $unit_type[$k]->type_id;
                    $unit_type_simpan->building_class  = $unit_type[$k]->building_class;
                    $unit_type_simpan->save();
                }
            }
        }

        $hadap = DB::connection('sqlsrv3')->table('dbo.m_position')->where('project_id',$project->project_id)->where('Deleted',0)->select("*")->get();
        $cek_hadap = UnitArah::where('project_id', $request->project_id)->get();
        for ($l = 0; $l < count($hadap); $l++) {
            $boolean = 0;
            foreach($cek_hadap as $key=> $nilai){
                if($nilai->position_id == $hadap[$l]->position_id){
                    $boolean = 1;
                    break;
                }
            }

            if($boolean == 0){
                $kawasan    = ProjectKawasan::where('cluster_id', $hadap[$l]->cluster_id)->first();
                $pt_hadap   = Pt::where('pt_id',$hadap[$l]->pt_id)->first();
                if($kawasan != null){
                    $unitarah                       = new UnitArah;
                    $unitarah->name                 = $hadap[$l]->position;
                    $unitarah->project_id           = $request->project_id;
                    //$unitarah->code = $request->code;
                    $unitarah->project_kawasan_id   = $kawasan->id;
                    $unitarah->pt_id                = $pt_hadap->id;
                    $unitarah->position_id          = $hadap[$l]->position_id;
                    $unitarah->save();
                }
            }
        }

        $block = DB::connection('sqlsrv3')->table('dbo.m_block')->where('project_id',$project->project_id)->where('Deleted',0)->select("*")->get();
        // return $block;
            $cek_blok = Blok::where('project_id', $request->project_id)->get();

            for ($j = 0; $j < count($block); $j++) {
                $boolean = 0;
                foreach($cek_blok as $key=> $nilai){
                    if($nilai->block_id == $block[$j]->block_id){
                        $boolean = 1;
                        break;
                    }
                }
                // return $boolean;
                if($boolean == 0){
                    $kawasan = ProjectKawasan::where('cluster_id', $block[$j]->cluster_id)->first();
                    // return $kawasan;
                    if($kawasan != null){
                        if($kawasan->id != null){
                            $blok                       = new Blok;
                            $blok->kode                 = $block[$j]->code;
                            $blok->project_id           = $request->project_id;
                            $blok->project_kawasan_id   = $kawasan->id;
                            $blok->name                 = $block[$j]->block;
                            $blok->luas                 = 1;
                            $blok->description          = $block[$j]->description;
                            $blok->block_id             = $block[$j]->block_id;
                            $status                     = $blok->save();
                        }
                    }
                }
            }
            
        // $unit_type = DB::connection('sqlsrv3')->table('dbo.mh_type')->where('Deleted',0)->where('type_id',265)->select("*")->get();
        // return $unit_type;
        // $hadap = DB::connection('sqlsrv3')->table('dbo.m_position')->where('project_id',$project->project_id)->where('Deleted',0)->where('position_id',45)->select("*")->get();
        // return $hadap;
        // $block = DB::connection('sqlsrv3')->table('dbo.m_block')->where('project_id',$project->project_id)->where('Deleted',0)->where('block_id',15292)->select("*")->get();
        // return $block;
        // $cluster = DB::connection('sqlsrv3')->table('dbo.mh_cluster')->where('project_id',$project->project_id)->where('Deleted',0)->where('cluster_id',2092)->select("*")->get();
        // return $cluster;
        // $cluster = DB::connection('sqlsrv3')->table('dbo.mh_cluster')->where('project_id',$project->project_id)->where('Deleted',0)->where('cluster_id',2068)->select("*")->get();
        // return $cluster;

        // $unit = DB::connection('sqlsrv3')->table('dbo.m_unit')->where('project_id',$project->project_id)->where('unit_number','BG.11/03')->select("*")->get();
        // return $unit;

        
        $unit = DB::connection('sqlsrv3')->table('dbo.m_unit')->where('project_id',$project->project_id)->where('Deleted',0)->where('state_admistrative','!=',10)->select("*")->get();
        // return $unit[0];
        for ($m = 0; $m < count($unit); $m++) {

            $purchaseletter = DB::connection('sqlsrv3')->table('dbo.th_purchaseletter')->where('unit_id',$unit[$m]->unit_id)->where('Deleted',0)->select("*")->OrderBy('purchaseletter_id','DESC')->first();
            // return $purchaseletter->rencana_serahterima_date;
            $spk_detail = DB::connection('sqlsrv3')->table('dbo.td_spkdetail')->where('unit_id',$unit[$m]->unit_id)->where('Deleted',0)->select("*")->first();
            // if($m == 1){
            //     return $spk_detail;
            // }
            // return $spk_detail;
            if($unit[$m]->block_id != null){
                $boolean = 0;
                $blok_unit  = Blok::where('block_id', $unit[$m]->block_id)->first();
                // if($blok_unit == ''){
                //     $block = DB::connection('sqlsrv3')->table('dbo.m_block')->where('project_id',$project->project_id)->where('block_id',$unit[$m]->block_id)->select("*")->get();

                //     $cluster = DB::connection('sqlsrv3')->table('dbo.mh_cluster')->where('project_id',$project->project_id)->where('cluster_id',$block[0]->cluster_id)->select("*")->get();
                    
                //     // printf($block);
                //     // echo("<br/>");
                //     // printf($cluster);
                //     // echo("<br/>");
                //     return $cluster;
                //     // return $unit[$m]->block_id;
                // }
                
                // if($cek_unit == ''){
                //     $unit[$m]->block_id;
                // }

                if($blok_unit == ''){
                    $boolean = 1;
                }else{
                    $cek_unit = Unit::where('blok_id', $blok_unit->id)->get();
                    foreach($cek_unit as $key=> $nilai){
                        if($nilai->unit_id == $unit[$m]->unit_id){
                            $id_unit = $nilai->id;
                            $boolean = 1;
                            break;
                        }
                    }
                    // $cek_unit = Unit::where('unit_id', $unit[$m]->unit_id)->get();
                    // if(count($cek_unit ) != 0){
                    //     //         // $id_unit = $nilai->id;
                    //             $boolean = 1;
                    //         }
                }
                
                // $cek_unit = Unit::where('unit_id', $unit[$m]->unit_id)->get();
                //      if(count($cek_unit ) != 0){
                //         // $id_unit = $nilai->id;
                //         $boolean = 1;
                //     }
                if($boolean == 0){
                    $kawasan    = ProjectKawasan::where('cluster_id', $unit[$m]->cluster_id)->first();
                    $pt_unit   = Pt::where('pt_id',$unit[$m]->pt_id)->first();
                    $blok_unit  = Blok::where('block_id', $unit[$m]->block_id)->first();
                    $type_unit  = UnitType::where('type_id', $unit[$m]->type_id)->first();
                    $hadap_unit = UnitArah::where('position_id', $unit[$m]->position_id)->first();

                    if($kawasan != null && $pt_unit != null && $blok_unit != null ){
                        if($kawasan->id != null && $pt_unit->id != null && $blok_unit->id != null ){
                            $project_units                         = new Unit;
                            $project_units->blok_id                = $blok_unit->id;
                            $project_units->peruntukan_id          = $request->peruntukan_id;
                            $project_units->pt_id                  = $pt_unit->id;
                            $project_units->name                   = $unit[$m]->unit_number;
                            $project_units->code                   = $unit[$m]->unit_number;
                            if ( $unit[$m]->building_size == 0 ){
                                $project_units->tag_kategori           = 'K';
                            }else{
                                $project_units->tag_kategori           = 'B';
                            }
                            $project_units->templatepekerjaan_id   = $request->unit_template;
                            $project_units->bangunan_luas          = $unit[$m]->building_size;

                            $project_units->tanah_luas             = $unit[$m]->land_size;
                            $project_units->luas_tanah_tambahan    = $unit[$m]->kelebihan;
                            $project_units->unit_arah_id           = $request->unit_arah_id;
                            if($type_unit != null){
                                $project_units->unit_type_id           = $type_unit->id;
                            }
                            if($hadap_unit != null){
                                $project_units->unit_hadap_id          = $hadap_unit->id;
                            }
                            $project_units->is_sellable            = 1;
                            if( $unit[$m]->state_admistrative == 8){
                                $project_units->status                 = 5;
                            }else{
                                $project_units->status                 = $unit[$m]->state_admistrative;
                            }
                            $project_units->unit_id                = $unit[$m]->unit_id;
                            $project_units->serah_terima_plan      = $unit[$m]->serahterima_plan;
                            if($spk_detail == null){
                                $project_units->is_spk             = 0;
                            }else{
                                $project_units->is_spk             = 1;
                            }
                            if($type_unit != null){
                                $project_units->building_class         = $type_unit->building_class;
                            }
                            if($purchaseletter == null){
                                $project_units->purchaseletter_id  = null;
                                $project_units->serah_terima_plan  = null;
                                $project_units->tanggal_akad       = null;
                            }else{
                                $project_units->purchaseletter_id  = $purchaseletter->purchaseletter_id;
                                $project_units->serah_terima_plan  = $purchaseletter->rencana_serahterima_date;
                                $project_units->tanggal_akad  = $purchaseletter->akad_realisasiondate;
                            }
                            $status = $project_units->save();

                            $unit_history                   = new UnitHistory;
                            $unit_history->unit_id          = "";
                            $unit_history->status           = 0;
                            $unit_history->status_reply_id  = "";
                            $unit_history->created_at       = date("Y-m-d H:i:s.000");
                            $unit_history->created_by       = \Auth::user()->id;
                            $unit_history->description      = "Buat unit baru";
                            $unit_history->save();
                        }
                    }
                }else{
                    // $kawasan    = ProjectKawasan::where('cluster_id', $unit[$m]->cluster_id)->first();
                    // $pt_unit   = Pt::where('pt_id',$unit[$m]->pt_id)->first();
                    // $blok_unit  = Blok::where('block_id', $unit[$m]->block_id)->first();
                    // $type_unit  = UnitType::where('type_id', $unit[$m]->type_id)->first();
                    // $hadap_unit = UnitArah::where('position_id', $unit[$m]->position_id)->first();

                    // if($kawasan != null && $pt_unit != null && $blok_unit != null && $type_unit != null && $hadap_unit != null){
                    //     if($kawasan->id != null && $pt_unit->id != null && $blok_unit->id != null && $type_unit->id != null && $hadap_unit->id != null){
                    //         $project_units                         = Unit::find($id_unit);
                    //         $project_units->blok_id                = $blok_unit->id;
                    //         $project_units->peruntukan_id          = $request->peruntukan_id;
                    //         $project_units->pt_id                  = $pt_unit->id;
                    //         $project_units->name                   = $unit[$m]->unit_number;
                    //         $project_units->code                   = $unit[$m]->unit_number;
                    //         if ( $unit[$m]->building_size == 0 ){
                    //             $project_units->tag_kategori           = 'K';
                    //         }else{
                    //             $project_units->tag_kategori           = 'B';
                    //         }
                    //         $project_units->templatepekerjaan_id   = $request->unit_template;
                    //         $project_units->bangunan_luas          = $unit[$m]->building_size;

                    //         $project_units->tanah_luas             = $unit[$m]->land_size;
                    //         $project_units->luas_tanah_tambahan    = $unit[$m]->kelebihan;
                    //         $project_units->unit_arah_id           = $request->unit_arah_id;
                    //         $project_units->unit_type_id           = $type_unit->id;
                    //         $project_units->unit_hadap_id          = $hadap_unit->id;
                    //         $project_units->is_sellable            = 1;
                    //         if( $unit[$m]->state_admistrative == 8){
                    //             $project_units->status                 = 5;
                    //         }else{
                    //             $project_units->status                 = $unit[$m]->state_admistrative;
                    //         }
                    //         $project_units->unit_id                = $unit[$m]->unit_id;
                    //         $project_units->serah_terima_plan      = $unit[$m]->serahterima_plan;
                    //         if($spk_detail == null){
                    //             $project_units->is_spk             = 0;
                    //         }else{
                    //             $project_units->is_spk             = 1;
                    //         }
                    //         $project_units->building_class         = $type_unit->building_class;
                    //         if($purchaseletter == null){
                    //             $project_units->purchaseletter_id  = null;
                    //             $project_units->serah_terima_plan  = null;
                    //             $project_units->tanggal_akad       = null;
                    //         }else{
                    //             $project_units->purchaseletter_id  = $purchaseletter->purchaseletter_id;
                    //             $project_units->serah_terima_plan  = $purchaseletter->rencana_serahterima_date;
                    //             $project_units->tanggal_akad  = $purchaseletter->akad_realisasiondate;
                    //         }
                    //         $status = $project_units->save();

                    //         $unit_history                   = new UnitHistory;
                    //         $unit_history->unit_id          = "";
                    //         $unit_history->status           = 0;
                    //         $unit_history->status_reply_id  = "";
                    //         $unit_history->created_at       = date("Y-m-d H:i:s.000");
                    //         $unit_history->created_by       = \Auth::user()->id;
                    //         $unit_history->description      = "Buat unit baru";
                    //         $unit_history->save();
                    //     }
                    // }
                }
            }
        }

        $project = Project::find($request->project_id);
        $project->description = $project->description.'; Migrasi Data Town Planning Dari Erems '.date("d-m-Y");
        $project->save();

        return redirect("project/detail-update/?id=".$request->project_id);
    }

    public function kawasan2(Request $request){
        $user = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        // $unit_pending = $project->unit_pending;
        // return $project;
        $total_unit = count($project->units);
        return view('project::project_kawasan',compact("project","user","total_unit"));
    }

    public function allPosts(Request $request)
    {
        $columns = array( 
                            0 => 'project_kawasans.name',
                            1 => 'project_kawasans.lahan_luas', 
                            2 => DB::raw("(sum(units.tanah_luas))"),
                            3 => DB::raw("(COUNT ( DISTINCT bloks.id ))"),
                            4 => DB::raw("(COUNT (units.id))"),
                            5 => 'project_kawasans.id',
                            6 => 'project_kawasans.id',
                            7 => 'project_kawasans.id',
                            8 => DB::raw("(COUNT (bloks.id))"),

                            // 0 =>'name',
                            // 1 =>'lahan_luas', 
                            // 2 =>'luas_netto',
                            // 3=> 'jumlah_blok',
                            // 4=> 'jumlah_unit',
                            // 5=> 'status_lahan',
                            // 6=> 'edit_blok',
                            // 7=> 'edit_kawasan',
                            // 8=> 'deleted',
                        );
        
        $project = Project::find($request->id_project);
        $totalData = ProjectKawasan::where("project_id",$project->id)->where("deleted_at",null)->count();
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
            
        if(empty($request->input('search.value')))
        {            
            // $posts = ProjectKawasan::where("project_id",$project->id)->offset($start)
            //              ->limit($limit)
            //             //  ->orderBy($order,$dir)
            //              ->get();
            // return DB::table('project_kawasans')
            //             ->offset($start)
            //             ->limit($limit)
            //             ->orderBy($order,$dir)
            //             ->get();

            $posts = DB::table('project_kawasans')
                        ->where("project_kawasans.project_id",$project->id)
                        ->where("project_kawasans.deleted_at",null)
                        ->where("bloks.deleted_at",null)
                        ->where("units.deleted_at",null)
                        ->leftJoin('bloks','bloks.project_kawasan_id','project_kawasans.id')
                        ->leftJoin('units','units.blok_id','bloks.id')
                        ->groupBy('project_kawasans.id','project_kawasans.name', 'project_kawasans.lahan_luas')
                        ->select('project_kawasans.id as id_kawasan','project_kawasans.name as name', DB::raw("(sum(units.tanah_luas)) as luas_netto"),DB::raw("(COUNT ( DISTINCT bloks.id )) AS jumlah_blok"),DB::raw("(COUNT (units.id)) as jumlah_unit"), 'project_kawasans.id as edit_blok', 'project_kawasans.id as edit_kawasan', DB::raw("(COUNT (bloks.id)) as deleted"), 'project_kawasans.id as status_lahan','project_kawasans.lahan_luas as lahan_luas')
                        ->orderBy($order,$dir)
                        ->offset($start)
                        ->limit($limit)
                        ->get();

            // $posts =

            // $posts = $posts->offset($start)
            //                 ->limit($limit);
            // return $posts;
        }
        else {
            $search = $request->input('search.value'); 

            // $posts =  ProjectKawasan::where("project_id",$project->id)->where('id','LIKE',"%{$search}%")
            //                 ->orWhere('name', 'LIKE',"%{$search}%")
            //                 ->offset($start)
            //                 ->limit($limit)
            //                 // ->orderBy($order,$dir)
            //                 ->get();
                            
            $posts = DB::table('project_kawasans')
                        ->where("project_kawasans.project_id",$project->id)
                        ->where("project_kawasans.deleted_at",null)
                        ->where("bloks.deleted_at",null)
                        ->where("units.deleted_at",null)
                        ->leftJoin('bloks','bloks.project_kawasan_id','project_kawasans.id')
                        ->leftJoin('units','units.blok_id','bloks.id')
                        ->groupBy('project_kawasans.id','project_kawasans.name', 'project_kawasans.lahan_luas')
                        ->select('project_kawasans.id as id_kawasan','project_kawasans.name as name', DB::raw("(sum(units.tanah_luas)) as luas_netto"),DB::raw("(COUNT ( DISTINCT bloks.id )) AS jumlah_blok"),DB::raw("(COUNT (units.id)) as jumlah_unit"), 'project_kawasans.id as edit_blok', 'project_kawasans.id as edit_kawasan', DB::raw("(COUNT (units.id)) as deleted"), 'project_kawasans.id as status_lahan','project_kawasans.lahan_luas as lahan_luas')
                        ->where('project_kawasans.name','LIKE',"%{$search}%")
                        ->orWhere('project_kawasans.lahan_luas', 'LIKE',"%{$search}%")
                        // ->orWhere(DB::raw("(sum(units.tanah_luas))"), 'LIKE',"%{$search}%")
                        // ->orWhere(DB::raw("(COUNT ( DISTINCT bloks.id ))"), 'LIKE',"%{$search}%")
                        // ->orWhere(DB::raw("(COUNT (units.id))"), 'LIKE',"%{$search}%")
                        ->orderBy($order,$dir)
                        ->offset($start)
                        ->limit($limit)
                        ->get();

            // $totalFiltered = ProjectKawasan::where("project_id",$project->id)
            //                                 ->where('id','LIKE',"%{$search}%")
            //                                 ->orWhere('name', 'LIKE',"%{$search}%")
            //                                 ->count();

            // $totalFiltered = $posts->count();

            $totalFiltered = DB::table('project_kawasans')
                                ->where("project_kawasans.project_id",$project->id)
                                ->where("project_kawasans.deleted_at",null)
                                ->where("bloks.deleted_at",null)
                                ->where("units.deleted_at",null)
                                ->leftJoin('bloks','bloks.project_kawasan_id','project_kawasans.id')
                                ->leftJoin('units','units.blok_id','bloks.id')
                                ->groupBy('project_kawasans.id','project_kawasans.name', 'project_kawasans.lahan_luas')
                                ->select('project_kawasans.id as id_kawasan','project_kawasans.name as name', DB::raw("(sum(units.tanah_luas)) as luas_netto"),DB::raw("(COUNT ( DISTINCT bloks.id )) AS jumlah_blok"),DB::raw("(COUNT (units.id)) as jumlah_unit"), 'project_kawasans.id as edit_blok', 'project_kawasans.id as edit_kawasan', DB::raw("(COUNT (units.id)) as deleted"), 'project_kawasans.id as status_lahan','project_kawasans.lahan_luas as lahan_luas')
                                ->where('project_kawasans.name','LIKE',"%{$search}%")
                                ->orWhere('project_kawasans.lahan_luas', 'LIKE',"%{$search}%")
                                ->get();

            $totalFiltered = $totalFiltered->count();
        }

        $data = array();
        if(!empty($posts))
        {
            foreach ($posts as $post)
            {
                // $show =  route('posts.show',$post->id_kawasan);
                // $edit =  route('posts.edit',$post->id_kawasan);

                // $nestedData['kawasan'] = $post->name;
                // $nestedData['luas_lahan_bruto'] = number_format($post->lahan_luas);
                // $nestedData['luas_lahan_netto'] = number_format($post->netto_kawasan);
                // $nestedData['jumlah_blok'] = number_format($post->bloks->count());
                // $nestedData['jumlah_unit'] = number_format($post->units->count());
                // $nestedData['status_lahan'] = "Planning";
                // $nestedData['edit_blok'] = "<a href='/project/bloks/?id=$post->id' class='btn btn-primary'>Edit</a>";
                // $nestedData['edit_kawasan'] = "<a class='btn btn-warning' href='/public/project/edit-kawasan?id=$post->id'>Edit</a>";
                // if($post->bloks->count() == 0){
                // $nestedData['delete'] = "<button class='btn btn-danger' onclick='removeKawasan(".$post->id.","."$post->name".")'>Hapus</button>";
                // }else{
                //     $nestedData['delete'] = "";
                // }
                // $data[] = $nestedData;
                $type = null;
                $hadap = null;
                $kawasan = ProjectKawasan::find($post->id_kawasan);
                if(count($kawasan->unit_type) == 0){
                    $type = "<label style='color:red'>Unit Type Belum ada</label>";
                }
                
                if(count($kawasan->unitarahs) == 0){
                    $hadap = "<label style='color:red'>Unit Hadap Belum ada</label>";
                }
                $nestedData['kawasan'] = $post->name."<br>".$type."<br>".$hadap;
                $nestedData['luas_lahan_bruto'] = number_format($post->lahan_luas);
                $nestedData['luas_lahan_netto'] = number_format($post->luas_netto);
                $nestedData['jumlah_blok'] = number_format($post->jumlah_blok);
                $nestedData['jumlah_unit'] = number_format($post->jumlah_unit);
                $nestedData['status_lahan'] = "Planning";
                $nestedData['edit_blok'] = "<a href='/project/bloks/?id=$post->id_kawasan' class='btn btn-primary'>Edit</a>";
                $nestedData['edit_kawasan'] = "<a class='btn btn-warning' href='/project/edit-kawasan?id=$post->id_kawasan'>Edit</a>";
                if($post->jumlah_blok == 0){
                $nestedData['delete'] = "<button class='btn btn-danger' onclick='removeKawasan(\"$post->id_kawasan\",\"$post->name\")'>Hapus</button>";
                }else{
                    $nestedData['delete'] = "";
                }
                $data[] = $nestedData;


            }
        }
        
        $json_data = array(
                    "draw"            => intval($request->input('draw')),  
                    "recordsTotal"    => intval($totalData),  
                    "recordsFiltered" => intval($totalFiltered), 
                    "data"            => $data   
                    );

        echo json_encode($json_data); 
        
    }

    public function migrasiPurpose(Request $request){
        $purpose = DB::connection('sqlsrv3')->table('dbo.m_purpose')->where('Deleted',0)->select("*")->get();
        for ($i = 0; $i < count($purpose); $i++) {
            $boolean = 0;
                $cek_purpose = Purpose::where('purpose_id', $purpose[$i]->purpose_id)->first();
                    if($cek_purpose == ""){
                        $pt    = Pt::where('pt_id',$purpose[$i]->pt_id)->first();
                        $project    = Project::where('project_id',$purpose[$i]->project_id)->first();
                        if(isset($project) && isset($pt)){
                        $save_purpose               = new Purpose;
                        $save_purpose->project_id   = $project->id;
                        $save_purpose->pt_id        = $pt->id;
                        $save_purpose->code         = $purpose[$i]->code;
                        $save_purpose->purpose      = $purpose[$i]->purpose;
                        $save_purpose->description  = $purpose[$i]->description;
                        $save_purpose->purpose_id   = $purpose[$i]->purpose_id;
                        $save_purpose->save();
                        }
                    }
        }
        return "selesai";
    }

    public function savemasalunit(Request $request){
        $start = "";
        /* Status Unit */

        $blok = Blok::find($request->blok);
        for ($i=$request->nomor_awal; $i <= $request->nomor_akhir ; $i++) 
        { 
            if($i % 2 == 0){
                if($request->ganjilgenap == 0){
                    $cek = true;
                }else{
                    $cek = false;
                }         
            }else{
                if($request->ganjilgenap == 1){
                    $cek = true;
                }else{
                    $cek = false;
                }
            }

            $units = $blok->units;
            $global_setting = Globalsetting::where("parameter","length_number")->first()->value;

            $start = "";
            for ( $j=0;  $j < ( $global_setting - (strlen(count($units) + 1) )) ; $j++ ){
                $start .= "0";
            }
            $no_ada = 0;
            if($cek == true){
                $unit_no = str_replace(" ","",$blok->kode) .'/'. $start.($i);
                $unit_ada = Unit::where("blok_id",$blok->id)->get();
                foreach ($unit_ada as $key => $value) {
                    # code...
                    if(1 < (count(explode("/",$value->name)))){
                        if(((int)explode("/",$value->name)[1]) == (int)explode("/", $unit_no)[1]){
                            $no_ada = 1;
                            break;
                        }
                    }
                    // (int)explode("/",$value->name)[1])

                }
                // return $no_ada;
                // return $unit_ada;
                if($no_ada == 0){
                    $project_units                         = new Unit;
                    $project_units->blok_id                = $blok->id;
                    $project_units->peruntukan_id          = $request->peruntukan_id;
                    $project_units->pt_id                  = $request->pt_id;
                    $project_units->name                   = $unit_no;
                    $project_units->code                   = $unit_no;
                    $project_units->tag_kategori           = $request->tag_kategori;

                    $project_units->templatepekerjaan_id   = $request->unit_template;
                    $project_units->bangunan_luas          = str_replace(",", "", $request->luas_bangunan);

                    $project_units->tanah_luas             = str_replace(",", "", $request->luas_tanah);
                    $project_units->unit_arah_id           = $request->unit_arah_id;
                    $project_units->unit_type_id           = $request->unit_type;
                    $project_units->unit_hadap_id          = $request->unit_position;
                    $project_units->is_sellable            = $request->is_sellable;
                    $project_units->status = 0;
                    $project_units->luas_tanah_tambahan = $request->luas_tanah_tambahan;
                    $project_units->purpose_id = $request->purpose;
                    $status = $project_units->save();

                    $unit_history = new UnitHistory;
                    $unit_history->unit_id = "";
                    $unit_history->status = 0;
                    $unit_history->status_reply_id = "";
                    $unit_history->created_at = date("Y-m-d H:i:s.000");
                    $unit_history->created_by = \Auth::user()->id;
                    $unit_history->description = "Buat unit baru";
                    $unit_history->save();
                }
            }

        }

        return redirect("project/units/?id=".$request->blok);
    }

    public function cekunit(Request $request){
        // return $request;
        $blok = Blok::find($request->blok_id);
        $unit_avail = 1;
        for ($i=(int)$request->nomor_awal; $i <= (int)$request->nomor_akhir ; $i++) 
        { 
            if($i % 2 == 0){
                if($request->ganjilgenap == 0){
                    $cek = true;
                }else{
                    $cek = false;
                }         
            }else{
                if($request->ganjilgenap == 1){
                    $cek = true;
                }else{
                    $cek = false;
                }
            }

            $units = $blok->units;
            $global_setting = Globalsetting::where("parameter","length_number")->first()->value;

            $start = "";
            for ( $j=0;  $j < ( $global_setting - (strlen(count($units) + 1) )) ; $j++ ){
                $start .= "0";
            }
            $no_ada = 0;
            if($cek == true){
                $unit_no = str_replace(" ","",$blok->kode) .'/'. $start.((int)$i);
                $unit_ada = Unit::where("blok_id",$blok->id)->get();
                foreach ($unit_ada as $key => $value) {
                    # code...
                    if(((int)explode("/",$value->name)[1]) == (int)explode("/", $unit_no)[1]){
                        $no_ada = 1;
                        $unit_avail = 0;
                        // printf(((int)explode("/",$value->name)[1]));
                        // echo("<br/>");
                        return response()->json( ["data" => $unit_avail, "status" => 0] );
                        break;
                    }
                    // (int)explode("/",$value->name)[1])

                }
                // return $no_ada;
                // return $unit_ada;
                if($no_ada == 0){
                    $unit_avail = 1;
                    return response()->json( ["data" => $unit_avail] );
                    break;
                }
            }
            
        }
        return response()->json( ["data" => $unit_avail, "status" => 0] );
    }
}
