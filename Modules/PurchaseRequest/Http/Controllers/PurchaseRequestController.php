<?php

namespace Modules\PurchaseRequest\Http\Controllers;

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
use Modules\Inventory\Entities\Satuan;
use Modules\Inventory\Entities\ItemSatuan;
use Modules\Approval\Entities\Approval;
use Modules\Rekanan\Entities\Rekanan;
use Modules\Inventory\Entities\CreateDocument;
use Modules\Approval\Entities\ApprovalHistory;
use Modules\TenderPurchaseRequest\Entities\PurchaseOrder;
use Modules\Project\Entities\ProjectPtUser;
use Modules\TenderPurchaseRequest\Entities\PurchaseOrderDetail;
use Modules\Project\Entities\ProjectPt;
use Modules\Inventory\Entities\ItemPrice;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
// use App\Spk;

use Modules\User\Entities\UserDetail;
use DB;
use Auth;
use PDF;

class PurchaseRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */

    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(Request $request)
    {
        $project_id = $request->session()->get('project_id');
        $project = Project::find($project_id);
        $user = Auth::user();
        $approve = UserDetail::where("user_id", $user->id)->select("can_approve")->first()->can_approve;

        $PR =  PurchaseRequest::select('*')->where('project_for_id', $project_id)->orderBy('created_at', 'desc')->get();

        $isDepartment =   UserDetail::select("mappingperusahaans.department_id")
            ->where("user_details.user_id", $user->id)
            ->join("mappingperusahaans", "mappingperusahaans.id", "user_details.mappingperusahaan_id")
            ->first()->department_id;

        return view('purchaserequest::index', compact("user", "PR", "project", "approve", "isDepartment"));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create(Request $request)
    {
        $user = \Auth::user();
        $approve = UserDetail::where("user_id", $user->id)->select("can_approve")->first()->can_approve;

        $department =   DB::table("users")->where("users.id", $user->id)
            ->join("user_details", "user_details.user_id", "users.id")
            ->join("mappingperusahaans", "mappingperusahaans.id", "user_details.mappingperusahaan_id")
            ->join("departments", "departments.id", "mappingperusahaans.department_id")
            ->where('users.deleted_at', null)
            ->select("departments.name", "departments.id")->first();


        //category & sub
        $categories = ItemCategory::where('parent_id', '<>', 0)->get();
        $parent_categories = ItemCategory::where('parent_id', '=', 0)->get();
        // $coa = Itempekerjaan::get();
        $coa = DB::table("coas")->where('deleted_at', null)->get();
        // $rekanan_group = \App\rekanan_group::get();
        $rekanan_group = DB::table("rekanan_groups")->where('deleted_at', null)->get();
        $test = new Budget();
        date_default_timezone_set('asia/jakarta');
        $date = date("d-m-y");
        
        $item_result = [];
        $item = ItemProject::select('id', 'item_id')->where('project_id', $request->session()->get('project_id'))->get();
        foreach ($item as $key => $value) {
            # code...
            $arr = [
                'id' => $value->id,
                'name' => $value->item->name,
                'category' => is_null($value->item->sub_item_category_id) ? $value->item->item_category_id : $value->item->sub_item_category_id,
            ];

            array_push($item_result, $arr);
        }

        // $item_satuan = \App\item_satuan::select('id','item_id','name')->get();
        $item_satuan = DB::table("item_satuans")->where('deleted_at', null)->get();

        $project = Project::find($request->session()->get('project_id'));

        // $budget = \App\budget::select("id")->where("department_id",$department->id)->where("project_id",$request->session()->get('project_id'))->get();
        $budget = DB::table("budgets")->select("id")->where("department_id", $department->id)->where("project_id", $request->session()->get('project_id'))->where('deleted_at', null)->get();
        // $budget_tahunan = \App\budget_tahunan::select("id","budget_id")->get();
        $budget_tahunan = DB::table("budget_tahunans")->select("id", "budget_id")->where('deleted_at', null)->get();

        // $budget_tahunan_detail = \App\budget_tahunan_detail::select("id","budget_tahunan_id","itempekerjaan_id")->get();
        $budget_tahunan_detail = DB::table("budget_tahunan_details")->select("id", "budget_tahunan_id", "itempekerjaan_id")->where('deleted_at', null)->get();

        $budget =   DB::table("budget_tahunan_details")
            ->join("budget_tahunans", "budget_tahunans.id", "budget_tahunan_details.budget_tahunan_id")
            ->join("budgets", "budgets.id", "budget_tahunans.budget_id")
            ->where("budgets.department_id", $department->id)
            ->where("budgets.project_id", $request->session()->get('project_id'))
            ->join("budget_details", "budget_details.budget_id", "budgets.id")
            ->distinct()
            ->join("itempekerjaans", "itempekerjaans.id", "budget_tahunan_details.itempekerjaan_id")
            ->select(
                // "budgets.project_id as bProject_id",
                // "budgets.department_id as bDepartement_id",
                "budget_tahunans.id as btId",
                "budget_tahunans.no as btNo",
                "budget_tahunan_details.itempekerjaan_id as btdItemPekerjaan",
                "itempekerjaans.name as ipName",
                "itempekerjaans.code as ipCode",
                "itempekerjaans.parent_id as ipId"
            )
            ->where('budget_tahunan_details.deleted_at', null)
            ->get();

        $budget_no = [];
        $btdItemPekerjaan = (object)[];
        //foreach di bawah kurang optimal, kejar tayang

        foreach ($budget as $v) {
            if (!in_array([$v->btNo, $v->btId], $budget_no)) {
                array_push($budget_no, [$v->btNo, $v->btId]);
            }
        }

        $myArray[] = (object)array('name' => 'My name');
        $myArray[] = (object)array('a' => 'My name');

        $input_budget_tahunan = DB::table("budgets")->select("budgets.id", "budgets.project_id", "budgets.department_id", "budget_tahunans.id as id_budget_tahunan", "budget_tahunans.no")->join("budget_tahunans", "budgets.id", "budget_tahunans.budget_id")->where('budgets.deleted_at', null)->get();

        $PO = PurchaseOrder::select("*")->get();

        $SPK = DB::table("spks")->select("*")->get();

        $SPK_department = DB::table("spks")->join("tender_rekanans", "tender_rekanans.id", "spks.tender_rekanan_id")
            ->join("tenders", "tenders.id", "tender_rekanans.tender_id")
            ->join("rabs", "rabs.id", "tenders.rab_id")
            ->join("budget_tahunans", "budget_tahunans.id", "rabs.budget_tahunan_id")
            ->join("budgets", "budgets.id", "budget_tahunans.budget_id")
            ->join("departments", "departments.id", "budgets.department_id")
            ->select("spks.id as spk_id", "spks.no as spk_no", "departments.name as department_name")
            ->get();


        $department_spk = Department::find($department->id);

        $coaHeader = Itempekerjaan::where('parent_id', null)->where('department_id', $user->details[0]->mappingperusahaan->department_id)->get();

        return view('purchaserequest::create', compact("user", "department", "coa", "rekanan_group", "date", "brand", "item_satuan", "budget", "budget_no", "budget_tahunan", "budget_tahunan_detail", "input_budget_tahunan", "project", "approve", "item_result", "categories", "first_child", "parent_categories", "PO", "department_spk", "coaHeader"));
    }

    public function store(Request $request)
    {
        // return $request;
        date_default_timezone_set("Asia/Jakarta");
        // return $request->upload;
        // $file = $request->file('upload');
        // $nama_file = time()."_".$file->getClientOriginalName();
        
        // return $name_file;
        $prePR = (object)[
            "pt" => (int)$request->pt_id,
            "department" => (int)$request->department,
            "butuh_date" => $request->butuh_date,
            "deskripsi_umum" => $request->deskripsi_umum,
            "jumlah_item" => $request->jumlah_item,
            "item" =>   $request->item,
            "brand" => $request->brand,
            "kuantitas" => $request->kuantitas,
            "satuan" => $request->satuan,
            "j_komparasi" => $request->j_komparasi,
            "komparasi_supplier1" => $request->komparasi_supplier1,
            "komparasi_supplier2" => $request->komparasi_supplier2,
            "komparasi_supplier3" => $request->komparasi_supplier3,
            "coa" => $request->coa,
            "coa2" => $request->coa2,
            "deskripsi_item" => $request->deskripsi_item,
            "is_urgent" => $request->is_urgent,
            "date" => $request->waktu_transaksi,
            "spk" => $request->spk,
            "harga_estimasi" => $request->harga_estimasi,
        ];

        for ($i = 0; $i < count($prePR->item); $i++) {
            $prePR->item[$i] = ((int)$prePR->item[$i]);
            $prePR->brand[$i] = ((int)$prePR->brand[$i]);
            // if ($request->budget_tahunan != 0) {
            //     $prePR->coa[$i] = ((int)$prePR->coa[$i]);
            // } else {
            //     $prePR->coa2[$i] = ((int)$prePR->coa2[$i]);
            // }
            $prePR->coa2[$i] = ((int)$prePR->coa2[$i]);
        }

        $user_id = Auth::user()->id;
        $project_id = $request->session()->get('project_id');

        $pt_id = ProjectPtUser::where([['user_id', '=', $user_id], ['project_id', '=', $project_id]])->first()->pt_id;

        $PR = new PurchaseRequest;
        // $PR->budget_tahunan_id = $request->budget_tahunan;
        // $PR->pt_id = DB::table("budgets")->select('pt_id')->where('department_id', $prePR->department)->first()->pt_id;
        $PR->department_id = $prePR->department;
        $PR->project_for_id = $project_id;
        $PR->pt_id = $prePR->pt;
        $PR->location_id = 1;

        $PR->no = CreateDocument::createDocumentNumber('PR', 2, $project_id, $user_id);
        $PR->date = $prePR->date;
        $PR->butuh_date = $prePR->butuh_date;
        $PR->is_urgent = $prePR->is_urgent;
        $PR->description = $prePR->deskripsi_umum;
        $status = $PR->save();

        $create_approval_pr = CreateDocument::make_approval('Modules\PurchaseRequest\Entities\PurchaseRequest', $PR->id, $project_id, $pt_id);

        for ($i = 0; $i < $prePR->jumlah_item; $i++) {
            $PRD = new \Modules\PurchaseRequest\Entities\PurchaseRequestDetail;
            $PRD->purchaserequest_id = $PR->id;
            // if ($request->budget_tahunan != 0) {
            //     $PRD->itempekerjaan_id = $prePR->coa[$i];
            // } else {
            //     $PRD->itempekerjaan_id = $prePR->coa2[$i];
            // }
            $PRD->itempekerjaan_id = $prePR->coa2[$i];
            $PRD->item_id = $prePR->item[$i];
            $PRD->item_satuan_id = $prePR->satuan[$i];
            $PRD->brand_id = $prePR->brand[$i];
            $PRD->recomended_supplier = $prePR->j_komparasi[$i];

            $PRD->quantity = $prePR->kuantitas[$i];
            $PRD->description = $prePR->deskripsi_item[$i];
            $PRD->rec_1 = $prePR->komparasi_supplier1[$i + 1];
            if ($prePR->j_komparasi[$i] > 1)
                $PRD->rec_2 = $prePR->komparasi_supplier2[$i + 1];
            if ($prePR->j_komparasi[$i] > 2)
                $PRD->rec_3 = $prePR->komparasi_supplier3[$i + 1];
            $PRD->delivery_date = $prePR->butuh_date;
            $PRD->spk_id    = $prePR->spk[$i];
            $PRD->harga_estimasi = (int)preg_replace("/([^0-9\\.])/i", "", $prePR->harga_estimasi[$i]);

            $PRD->save();

            $create_approval_pr_detail = CreateDocument::make_approval('Modules\PurchaseRequest\Entities\PurchaseRequestDetail', $PRD->id, $project_id, $pt_id);

        }
        return redirect("/purchaserequest/detail/?id=" . $PR->id);
    }


    public function edit(Request $request, $id)
    {

        $user = Auth::user();
        $project_id = $request->session()->get('project_id');
        $project = Project::find($project_id);
        $approve = UserDetail::where("user_id", $user->id)->select("can_approve")->first()->can_approve;

        $PR = PurchaseRequestDetail::where('purchaserequest_id', $request->id)->get();
        $tmpPR = PurchaseRequest::where('id', $request->id)->first();

        $coa =  DB::table("budget_tahunan_details as btd")
            ->where('btd.deleted_at', null)
            ->where('btd.budget_tahunan_id', $tmpPR->budget_tahunan_id)
            ->join('itempekerjaans as ip', 'ip.id', 'btd.itempekerjaan_id')
            ->select('ip.id', 'ip.name', 'ip.code')
            ->distinct()
            ->get();

        $PRHeader = PurchaseRequest::find($request->id);
        $PRD = PurchaseRequestDetail::find($request->id);

        $PRH = PurchaseRequest::find($id);

        if ($PRHeader->budget_tahunan_id != 0) {
            $sisa_budget = BudgetTahunan::find($PRHeader->budget_tahunan_id)->total_parent_item;
        } else {
            $sisa_budget = 0;
        }

        $pr_id = $request->id;

        $parent_categories = ItemCategory::where('parent_id', '=', 0)->get();
        $categories = ItemCategory::where('parent_id', '<>', 0)->get();
        $brand = DB::table("brands")->select('id', 'name')->where('deleted_at', null)->get();
        $rekanan_group = DB::table("rekanan_groups")->where('deleted_at', null)->get();

        date_default_timezone_set('asia/jakarta');
        $date = date("Y-m-d");
        // $item = \App\item::select('id','name','stock_min')->get();
        $item_result = [];
        $item = ItemProject::select('id', 'item_id')->where('project_id', $request->session()->get('project_id'))->get();
        foreach ($item as $key => $value) {
            # code...
            $arr = [
                'id' => $value->id,
                'name' => $value->item->name,
                'category' => is_null($value->item->sub_item_category_id) ? $value->item->item_category_id : $value->item->sub_item_category_id,
            ];

            array_push($item_result, $arr);
        }
        $item_satuan = DB::table("item_satuans")->where('deleted_at', null)->get();

        $budget = DB::table("budgets")->select("id")->where("department_id", $PRH->department_id)->where("project_id", $request->session()->get('project_id'))->where('budgets.deleted_at', null)->get();

        $budget_tahunan = DB::table("budget_tahunans")->select("id", "budget_id")->where('deleted_at', null)->get();

        $budget_tahunan_detail = DB::table("budget_tahunan_details")->select("id", "budget_tahunan_id", "itempekerjaan_id")->where('budget_tahunan_details.deleted_at', null)->get();

        $budget =   DB::table("budget_tahunan_details")
            ->join("budget_tahunans", "budget_tahunans.id", "budget_tahunan_details.budget_tahunan_id")
            ->join("budgets", "budgets.id", "budget_tahunans.budget_id")
            ->where("budgets.department_id", $PRH->department_id)
            ->where("budgets.project_id", $request->session()->get('project_id'))
            ->join("budget_details", "budget_details.budget_id", "budgets.id")
            ->distinct()
            ->join("itempekerjaans", "itempekerjaans.id", "budget_tahunan_details.itempekerjaan_id")
            ->select(
                // "budgets.project_id as bProject_id",
                // "budgets.department_id as bDepartement_id",
                "budget_tahunans.id as btId",
                "budget_tahunans.no as btNo",
                "budget_tahunan_details.itempekerjaan_id as btdItemPekerjaan",
                "itempekerjaans.name as ipName",
                "itempekerjaans.code as ipCode"
            )
            ->where('budget_tahunan_details.deleted_at', null)
            ->get();



        $budget_no = [];
        $btdItemPekerjaan = (object)[];
        //foreach di bawah kurang optimal, kejar tayang

        foreach ($budget as $v) {
            if (!in_array([$v->btNo, $v->btId], $budget_no)) {
                array_push($budget_no, [$v->btNo, $v->btId]);
            }
        }
        $total = 0;

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

        $totalTerakhir = "Rp " . number_format($totalTerakhir, 2, ',', '.');



        $input_budget_tahunan = DB::table("budgets")
            ->select("budgets.id", "budgets.project_id", "budgets.department_id", "budget_tahunans.id as id_budget_tahunan", "budget_tahunans.no")
            ->join("budget_tahunans", "budgets.id", "budget_tahunans.budget_id")
            ->where('budgets.deleted_at', null)->get();

        $department_spk = Department::find($PRHeader->department_id);

        $coaHeader = Itempekerjaan::where('parent_id', null)->where('department_id', $user->details[0]->mappingperusahaan->department_id)->get();
        $coaChild = Itempekerjaan::where('parent_id', '!=', null)->where('department_id', $user->details[0]->mappingperusahaan->department_id)->get();

        return view('purchaserequest::edit2', compact("user", "project", "PR", "approve", "pr_id", "PRHeader", "categories", "item_result", "PRD", "rekanan_group", "item_satuan", "department", "coa", "date", "brand", "budget", "budget_no", "budget_tahunan", "budget_tahunan_detail", "input_budget_tahunan", "total", "parent_categories", "pengguna_terakhir", "brand", "totalTerakhir", "department_spk", "coaHeader", "coaChild"));
    }


    public function update(Request $request)
    {
        $stat = 0;
        $name = $request->name;
        $pk = $request->pk;
        $value = $request->value;
        $updated = PurchaseRequestDetail::find($pk)->update([$name => $value]);
        if ($updated) {
            $stat = 1;
        }

        return response()->json(['return' => $stat]);
    }


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


        $totalTerakhir = "Rp " . number_format($totalTerakhir, 2, ',', '.');

        return view('purchaserequest::detail', compact("user", "project", "PR", "approve", "pr_id", "PRHeader", "sisa_budget", "total", "pengguna_terakhir", "totalTerakhir"));
    }


    public function approve(Request $request)
    {
        $id_PRD = $request->id;
        $PRHeader = PurchaseRequest::find($request->id);
        $project_id = $request->session()->get('project_id');
        date_default_timezone_set('asia/jakarta');
        $date = date("Y-m-d h:i:s");
        $user_id = Auth::user()->id;

        if ($request->type == "approve") {
            DB::table("approvals")
                ->where("id", DB::table("approvals")->select("id")
                    ->where("document_type", "Modules\PurchaseRequest\Entities\PurchaseRequestDetail")
                    ->where("document_id", $request->id)->first()->id)
                ->where("approval_action_id", 2)
                ->update(["approval_action_id" => 6]);

            $approval_detail = Approval::where([['document_id', '=', $request->id], ['document_type', '=', 'Modules\PurchaseRequest\Entities\PurchaseRequestDetail']])->select("id")->first();

            ApprovalHistory::where("approval_id", $approval_detail->id)
                ->where("approval_action_id", 2)
                ->delete();

            CreateDocument::make_approval_history($approval_detail->id, 'Modules\PurchaseRequest\Entities\PurchaseRequestDetail', $project_id);


            $getHeaderID = PurchaseRequestDetail::find($request->id)->purchaserequest_id;
            $getChildHeader = PurchaseRequestDetail::where('purchaserequest_id', $getHeaderID)->get();
            $arr_temp = [];
            foreach ($getChildHeader as $key => $v) {
                # code...
                $checkApproval = Approval::where([['document_id', '=', $v->id], ['document_type', '=', 'Modules\PurchaseRequest\Entities\PurchaseRequestDetail']])->first()->approval_action_id;
                if ($checkApproval != 6 && $checkApproval != 7) {
                    array_push($arr_temp, 1);
                } else {
                    array_push($arr_temp, 0);
                }
            }

            if (in_array(1, $arr_temp)) {
                Approval::where([['document_id', '=', $request->pr_id], ['document_type', '=', 'Modules\PurchaseRequest\Entities\PurchaseRequest'], ['approval_action_id', '=', 2]])->update(['approval_action_id' => 12]);

                $approval = Approval::where([['document_id', '=', $request->pr_id], ['document_type', '=', 'Modules\PurchaseRequest\Entities\PurchaseRequest']])->select("id")->first();

                $approval_history = ApprovalHistory::where("approval_id", $approval->id)
                    ->where("approval_action_id", 12)
                    ->first();
                if ($approval->approval_action_id == 12) {
                    ApprovalHistory::where("approval_id", $approval->id)
                        ->where("approval_action_id", 2)
                        ->delete();
                } else { }

                if ($approval_history != NULL) { } else {
                    CreateDocument::make_approval_history($approval->id, 'Modules\PurchaseRequest\Entities\PurchaseRequest', $project_id);
                }
            } else {
                Approval::where([['document_id', '=', $request->pr_id], ['document_type', '=', 'Modules\PurchaseRequest\Entities\PurchaseRequest']])->update(['approval_action_id' => 6]);

                $approval = Approval::where([['document_id', '=', $request->pr_id], ['document_type', '=', 'Modules\PurchaseRequest\Entities\PurchaseRequest']])->select("id")->first();

                if ($approval->approval_action_id == 12) {
                    ApprovalHistory::where("approval_id", $approval->id)
                        ->where("approval_action_id", 12)
                        ->delete();
                } else {
                    ApprovalHistory::where("approval_id", $approval->id)
                        ->where("approval_action_id", 2)
                        ->delete();
                }

                CreateDocument::make_approval_history($approval->id, 'Modules\PurchaseRequest\Entities\PurchaseRequest', $project_id);
            }

            return redirect("/access/purchaserequest/detail/?id=" . $request->pr_id);
        } else if ($request->type == "cancel") {
            DB::table("approvals")
                ->where("id", DB::table("approvals")->select("id")
                    ->where("document_type", "Modules\PurchaseRequest\Entities\PurchaseRequestDetail")
                    ->where("document_id", $request->id)->first()->id)
                ->where("approval_action_id", 6)
                ->update(["approval_action_id" => 2]);
            $getHeaderID = PurchaseRequestDetail::find($request->id)->purchaserequest_id;
            $getChildHeader = PurchaseRequestDetail::where('purchaserequest_id', $getHeaderID)->get();
            $arr_temp = [];
            foreach ($getChildHeader as $key => $v) {
                # code...
                $checkApproval = Approval::where([['document_id', '=', $v->id], ['document_type', '=', 'Modules\PurchaseRequest\Entities\PurchaseRequestDetail']])->first()->approval_action_id;
                if ($checkApproval == 6) {
                    array_push($arr_temp, 1);
                } else {
                    array_push($arr_temp, 0);
                }
            }

            if (in_array(1, $arr_temp)) {
                Approval::where([['document_id', '=', $request->pr_id], ['document_type', '=', 'Modules\PurchaseRequest\Entities\PurchaseRequest']])->update(['approval_action_id' => 12]);

                $PRApproval = DB::table("approvals")->where("document_id", $request->pr_id)
                    ->where("document_type", "Modules\PurchaseRequest\Entities\PurchaseRequest")
                    ->select('*')
                    ->first();

                $approval_history = ApprovalHistory::where("approval_id", $PRApproval->id)
                    ->select("approval_action_id")
                    ->get()->last();

                if ($approval_history->approval_action_id == 12) {
                    ApprovalHistory::where("approval_id", $PRApproval->id)
                        ->where("approval_action_id", 6)
                        ->delete();
                } else {
                    ApprovalHistory::where("approval_id", $PRApproval->id)
                        ->where("approval_action_id", 12)
                        ->delete();
                }

                if ($approval_history != NULL) { } else {

                    CreateDocument::make_approval_history($PRApproval->id, 'Modules\PurchaseRequest\Entities\PurchaseRequest', $project_id);
                }
            } else {
                $approval = Approval::where([['document_id', '=', $request->pr_id], ['document_type', '=', 'Modules\PurchaseRequest\Entities\PurchaseRequest']])->update(['approval_action_id' => 2]);

                $PRApproval = DB::table("approvals")->where("document_id", $request->pr_id)
                    ->where("document_type", "Modules\PurchaseRequest\Entities\PurchaseRequest")
                    ->select('*')
                    ->first();

                $approval_history = ApprovalHistory::where("approval_id", $PRApproval->id)
                    ->select("approval_action_id")
                    ->get()->last();

                if ($approval_history->approval_action_id == 12) {
                    ApprovalHistory::where("approval_id", $PRApproval->id)
                        ->where("approval_action_id", 12)
                        ->where("document_type", "Modules\PurchaseRequest\Entities\PurchaseRequest")
                        ->delete();
                } else {
                    ApprovalHistory::where("approval_id", $PRApproval->id)
                        ->where("approval_action_id", 6)
                        ->where("document_type", "Modules\PurchaseRequest\Entities\PurchaseRequest")
                        ->delete();
                }

                CreateDocument::make_approval_history($PRApproval->id, 'Modules\PurchaseRequest\Entities\PurchaseRequest', $project_id);
            }

            $PRApproval = DB::table("approvals")->where("document_id", $request->id)
                ->where("document_type", "Modules\PurchaseRequest\Entities\PurchaseRequestDetail")
                ->select('*')
                ->first();

            ApprovalHistory::where("approval_id", $PRApproval->id)
                ->where("approval_action_id", 6)
                ->where("document_type", "Modules\PurchaseRequest\Entities\PurchaseRequestDetail")
                ->delete();

            CreateDocument::make_approval_history($PRApproval->id, 'Modules\PurchaseRequest\Entities\PurchaseRequestDetail', $project_id);

            return redirect("/purchaserequest/detail/?id=" . $request->pr_id);
        } else if ($request->type == "approveAll") {
            $id_PR = DB::table("purchaserequest_details")->where("purchaserequest_id", $id_PRD)->select("id")->get();
            var_dump($id_PR);

            $id = $PRHeader->id;
            Approval::where([['document_id', '=', $id], ['document_type', '=', 'Modules\PurchaseRequest\Entities\PurchaseRequest'], ['approval_action_id', '=', 2]])->update(['approval_action_id' => 6]);

            $approval = Approval::where([['document_id', '=', $id], ['document_type', '=', 'Modules\PurchaseRequest\Entities\PurchaseRequest'], ["approval_action_id", "<>", "7"]])->select("id")->first();

            if ($approval == NULL) { } else {
                ApprovalHistory::where("approval_id", $approval->id)
                    ->where("approval_action_id", 2)
                    ->delete();

                CreateDocument::make_approval_history($approval->id, 'Modules\PurchaseRequest\Entities\PurchaseRequest', $project_id);
            }

            foreach ($id_PR as $v) {
                DB::table("approvals")->where("id", DB::table("approvals")->select("id")
                    ->where("document_type", "Modules\PurchaseRequest\Entities\PurchaseRequestDetail")
                    ->where("document_id", $v->id)->first()->id)
                    ->where("approval_action_id", "<>", "7")
                    ->where("approval_action_id", 2)
                    ->update(["approval_action_id" => 6]);

                $approval = Approval::where([['document_id', '=', $v->id], ['document_type', '=', 'Modules\PurchaseRequest\Entities\PurchaseRequestDetail'], ["approval_action_id", "<>", "7"]])->select("id")->first();

                if ($approval == NULL) { } else {
                    ApprovalHistory::where("approval_id", $approval->id)
                        ->where("approval_action_id", 2)
                        ->delete();

                    CreateDocument::make_approval_history($approval->id, 'Modules\PurchaseRequest\Entities\PurchaseRequestDetail', $project_id);
                }
            }

            return redirect("/purchaserequest/detail/?id=" . $id_PRD);
        } else if ($request->type == "cancelAll") {
            $id_PR = DB::table("purchaserequest_details")->where("purchaserequest_id", $id_PRD)->select("id")->get();
            var_dump($id_PR);

            $id = $PRHeader->id;
            $approval = Approval::where([['document_id', '=', $id], ['document_type', '=', 'Modules\PurchaseRequest\Entities\PurchaseRequest'], ['approval_action_id', '=', 6]])->update(['approval_action_id' => 2]);

            $PRApproval = DB::table("approvals")->where("document_id", $id)
                ->where("document_type", "Modules\PurchaseRequest\Entities\PurchaseRequest")
                ->select('*')
                ->first();

            $approval_history = ApprovalHistory::where("approval_id", $PRApproval->id)
                ->select("approval_action_id")
                ->get()->last();

            if ($approval_history->approval_action_id == 6) {
                ApprovalHistory::where("approval_id", $PRApproval->id)
                    ->where("approval_action_id", 6)
                    ->where("document_type", "Modules\PurchaseRequest\Entities\PurchaseRequest")
                    ->delete();
            } else {
                ApprovalHistory::where("approval_id", $PRApproval->id)
                    ->where("approval_action_id", 12)
                    ->where("document_type", "Modules\PurchaseRequest\Entities\PurchaseRequest")
                    ->delete();
            }

            CreateDocument::make_approval_history($PRApproval->id, 'Modules\PurchaseRequest\Entities\PurchaseRequest', $project_id);

            foreach ($id_PR as $v) {
                DB::table("approvals")
                    ->where("id", DB::table("approvals")->select("id")
                        ->where("document_type", "Modules\PurchaseRequest\Entities\PurchaseRequestDetail")
                        ->where("document_id", $v->id)->first()->id)
                    ->where("approval_action_id", "<>", "7")
                    ->where("approval_action_id", 6)
                    ->update(["approval_action_id" => 2]);

                $PRDApproval = DB::table("approvals")->where("document_id", $v->id)
                    ->where("document_type", "Modules\PurchaseRequest\Entities\PurchaseRequestDetail")
                    ->where("approval_action_id", "<>", "7")
                    ->select('*')
                    ->first();

                ApprovalHistory::where("approval_id", $PRDApproval->id)
                    ->where("approval_action_id", 6)
                    ->delete();


                CreateDocument::make_approval_history($PRDApproval->id, 'Modules\PurchaseRequest\Entities\PurchaseRequestDetail', $project_id);
            }

            return redirect("/purchaserequest/detail/?id=" . $id_PRD);
        } else if ($request->type == "cancelToApproveAll") { }
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

                $PRApproval = DB::table("approvals")->where("document_id", $request->id)
                    ->where("document_type", "Modules\PurchaseRequest\Entities\PurchaseRequestDetail")
                    ->select('*')
                    ->first();
                ApprovalHistory::where("approval_id", $PRApproval->id)
                    ->where("approval_action_id", 2)
                    ->update(["description" => $request->deskripsi_reject]);

                ApprovalHistory::where("approval_id", $PRApproval->id)
                    ->where("approval_action_id", 2)
                    // ->update("description",$request->deskripsi_reject)
                    ->delete();

                CreateDocument::make_approval_history($PRApproval->id, 'Modules\PurchaseRequest\Entities\PurchaseRequestDetail', $project_id);

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

                    $PRApproval = DB::table("approvals")->where("document_id", $request->pr_id)
                        ->where("document_type", "Modules\PurchaseRequest\Entities\PurchaseRequest")
                        ->select('*')
                        ->first();

                    ApprovalHistory::where("approval_id", $PRApproval->id)
                        ->where("approval_action_id", 2)
                        ->delete();



                    CreateDocument::make_approval_history($PRApproval->id, 'Modules\PurchaseRequest\Entities\PurchaseRequest', $project_id);
                }
            } else if ($approval_obj->first()->approval_action_id == 12) {
                DB::table("approvals")
                    ->where("id", DB::table("approvals")->select("id")
                        ->where("document_type", "Modules\PurchaseRequest\Entities\PurchaseRequestDetail")
                        ->where("document_id", $request->id)->first()->id)
                    ->update(["approval_action_id" => 7, "inactive_at" => $date, "inactive_by" => $user]);

                $PRApproval = DB::table("approvals")->where("document_id", $request->id)
                    ->where("document_type", "Modules\PurchaseRequest\Entities\PurchaseRequestDetail")
                    ->select('*')
                    ->first();

                ApprovalHistory::where("approval_id", $PRApproval->id)
                    ->where("approval_action_id", 2)
                    ->update(["description" => $request->deskripsi_reject]);

                ApprovalHistory::where("approval_id", $PRApproval->id)
                    ->where("approval_action_id", 2)
                    // ->update("description",$request->deskripsi_reject)
                    ->delete();

                CreateDocument::make_approval_history($PRApproval->id, 'Modules\PurchaseRequest\Entities\PurchaseRequestDetail', $project_id);

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

                    $PRApproval = DB::table("approvals")->where("document_id", $request->pr_id)
                        ->where("document_type", "Modules\PurchaseRequest\Entities\PurchaseRequest")
                        ->select('*')
                        ->first();

                    ApprovalHistory::where("approval_id", $PRApproval->id)
                        ->where("approval_action_id", 12)
                        ->delete();

                    CreateDocument::make_approval_history($PRApproval->id, 'Modules\PurchaseRequest\Entities\PurchaseRequest', $project_id);
                }
            }

            // return $PRDApproval[0]->id;

            return redirect("/purchaserequest/detail/?id=" . $request->pr_id);
        } else if ($request->type == "unreject") {
            DB::table("approvals")
                ->where("id", DB::table("approvals")->select("id")
                    ->where("document_type", "Modules\PurchaseRequest\Entities\PurchaseRequestDetail")
                    ->where("document_id", $request->id)->first()->id)
                ->update(["approval_action_id" => 2, "inactive_at" => NULL, "inactive_by" => NULL]);

            $PRApproval = DB::table("approvals")->where("document_id", $request->id)
                ->where("document_type", "Modules\PurchaseRequest\Entities\PurchaseRequestDetail")
                ->select('*')
                ->first();

            ApprovalHistory::where("approval_id", $PRApproval->id)
                ->where("approval_action_id", 7)
                ->delete();

            CreateDocument::make_approval_history($PRApproval->id, 'Modules\PurchaseRequest\Entities\PurchaseRequestDetail', $project_id);

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

                $PRApproval = DB::table("approvals")->where("document_id", $request->pr_id)
                    ->where("document_type", "Modules\PurchaseRequest\Entities\PurchaseRequest")
                    ->select('*')
                    ->first();

                ApprovalHistory::where("approval_id", $PRApproval->id)
                    ->where("approval_action_id", 7)
                    ->delete();

                $approval_history = ApprovalHistory::where("approval_id", $PRApproval->id)
                    ->select("approval_action_id")
                    ->get()->last();

                if ($approval_history != NULL) { } else {

                    CreateDocument::make_approval_history($PRApproval->id, 'Modules\PurchaseRequest\Entities\PurchaseRequest', $project_id);
                }
            } else {
                $approval = Approval::where([['document_id', '=', $request->pr_id], ['document_type', '=', 'Modules\PurchaseRequest\Entities\PurchaseRequest']])->update(['approval_action_id' => 2]);
            }

            return redirect("/purchaserequest/detail/?id=" . $request->pr_id);
        }
    }

    public function changeBrand(Request $request)
    {
        $item_project_id = $request->id;
        $brands = null;
        $sub_category = null;
        $parent_category = null;
        $result_brand = [];
        $satuans = null;
        $items = null;
        if ($item_project_id != 0) {
            $sub_category = ItemProject::find($item_project_id)->item->sub_category;
            $parent_category = ItemProject::find($item_project_id)->item->category;
            if ($sub_category != null) {
                $brands = BrandOfCategory::select('id', 'brand_id')->where('category_id', $sub_category->id)->get();
            } else {
                $sub_category = ItemProject::find($item_project_id)->item->category;
                $parent_category = ItemProject::find($item_project_id)->item->category;
                $brands = BrandOfCategory::select('id', 'brand_id')->where('category_id', $sub_category->id)->get();
            }


            foreach ($brands as $key => $value) {
                # code...
                $arr = [
                    'id' => $value->brand_id,
                    'name' => $value->brand->name
                ];

                array_push($result_brand, $arr);
            }

            //get satuan 
            $item_id = ItemProject::find($item_project_id)->item_id;
            $satuans = ItemSatuan::select('id', 'name')->where('item_id', $item_id)->get();
        } else {
            $sub_category = ItemCategory::select('id', 'name')->where('parent_id', '<>', 0)->get();
            $parent_category = ItemCategory::select('id', 'name')->where('parent_id', '=', 0)->get();
            $items = ItemProject::select('item_projects.id as itemid', 'items.name as itemname')
                ->join('items', 'item_projects.item_id', 'items.id')
                ->where('project_id', $request->session()->get('project_id'))->get();
        }



        return response()->json(['brands' => $result_brand, 'satuans' => $satuans, 'categories' => $sub_category, 'items' => $items, 'parent_categories' => $parent_category]);
    }


    public function request_approval(request $PRHeader)
    {
        $id = $PRHeader->id;
        $purchase_request = PurchaseRequest::find($id);
        $project_id = $PRHeader->session()->get('project_id');

        Approval::where([['document_id', '=', $id], ['document_type', '=', 'Modules\PurchaseRequest\Entities\PurchaseRequest'], ['approval_action_id', '=', 1]])->update(['approval_action_id' => 2]);

        $approval = Approval::where([['document_id', '=', $id], ['document_type', '=', 'Modules\PurchaseRequest\Entities\PurchaseRequest']])->select("*")->first();

        // CreateDocument::make_approval_history($approval->id,'Modules\PurchaseRequest\Entities\PurchaseRequest',$project_id);


        $approval_references = \Modules\Approval\Entities\ApprovalReference::where('document_type', "PurchaseRequest")
            ->where('project_id', $project_id)
            ->where('pt_id', $purchase_request->pt_id )
            ->where('min_value', '<=', $approval->total_nilai)
            //->where('max_value', '>=', $approval->total_nilai)
            ->orderBy('no_urut', 'ASC')
            ->get();
        foreach ($approval_references as $key => $each) {
            ApprovalHistory::create([
                'no_urut' => $each->no_urut,
                'user_id' => $each->user_id,
                'approval_action_id' => $approval->approval_action_id,
                'approval_id' => $approval->id,
                'document_type' => "Modules\PurchaseRequest\Entities\PurchaseRequest",
                'document_id' => $approval->document_id,
                'no_urut' => $each->no_urut
            ]);
        }

        $id_PR = DB::table("purchaserequest_details")->where("purchaserequest_id", $id)->select("id")->get();

        foreach ($id_PR as $v) {
            DB::table("approvals")->where("id", DB::table("approvals")->select("id")
                ->where("document_type", "Modules\PurchaseRequest\Entities\PurchaseRequestDetail")
                ->where("document_id", $v->id)->first()->id)
                ->where("approval_action_id", 1)
                ->update(["approval_action_id" => 2]);

            $approval_detail = Approval::where([['document_id', '=', $v->id], ['document_type', '=', 'Modules\PurchaseRequest\Entities\PurchaseRequestDetail']])->select("*")->first();

            // CreateDocument::make_approval_history( $approval_detail->id,'Modules\PurchaseRequest\Entities\PurchaseRequestDetail',$project_id);
            $approval_references = \Modules\Approval\Entities\ApprovalReference::where('document_type', "PurchaseRequestDetail")
                ->where('project_id', $project_id)
                ->where('pt_id', $purchase_request->pt_id )
                ->where('min_value', '<=', $approval_detail->total_nilai)
                //->where('max_value', '>=', $approval->total_nilai)
                ->orderBy('no_urut', 'ASC')
                ->get();
            foreach ($approval_references as $key => $each) {
                ApprovalHistory::create([
                    'no_urut' => $each->no_urut,
                    'user_id' => $each->user_id,
                    'approval_action_id' => $approval_detail->approval_action_id,
                    'approval_id' => $approval_detail->id,
                    'document_type' => "Modules\PurchaseRequest\Entities\PurchaseRequestDetail",
                    'document_id' => $approval_detail->document_id,
                    'no_urut' => $each->no_urut
                ]);
            }
        }

        $approval_history_pr = \Modules\Approval\Entities\ApprovalHistory::where('document_id',$purchase_request->id)->where('document_type','Modules\PurchaseRequest\Entities\PurchaseRequest')->orderBy('no_urut','DESC')->first();

        \Modules\Approval\Entities\ApprovalHistory::where("id", $approval_history_pr->id)->update(['approval_action_id' => 1]);

        $project_pt = ProjectPt::where("project_id",$project_id)->first();
        $data["email"]=$approval_history_pr->user->email;
        $data["client_name"]=$approval_history_pr->user->user_name;
        $data["subject"]='Approval Purchase Request';
        // $link = 'https://ces.ciputragroup.com/webapps/Ciputra/public/';
        
        $encript = encrypt('https://cpms.ciputragroup.com:81/access/purchaserequest/detail/?id='.$purchase_request->id.'||'.$approval_history_pr->user->id.'||'.date('d/M/Y', strtotime('+ 7 day', strtotime(date("Y-m-d")))));
        $link = 'https://cpms.ciputragroup.com:81/access/login/?code='.$encript;
        $title = "Purchase Request";

        Mail::send('mail.bodyEmailApprove', ['link' => $link, 'title' => $title, 'user' => $approval_history_pr->user, 'project_pt' => $project_pt, 'name' => $purchase_request->no], function($message)use($data) {
            $message->from(env('MAIL_USERNAME'))->to($data["email"], $data["client_name"])->subject($data["subject"]);
        });

        return redirect("/purchaserequest/detail/?id=" . $id);
    }

    public function batalrequest_approval(request $PRHeader)
    {
        $id = $PRHeader->id;
        date_default_timezone_set('asia/jakarta');
        $date = date("Y-m-d h:i:s");
        $user_id = Auth::user()->id;

        $id_PR = DB::table("purchaserequest_details")->where("purchaserequest_id", $id)->select("id")->get();
        var_dump($id_PR);

        $approval_obj = Approval::where([['document_id', '=', $id], ['document_type', '=', 'Modules\PurchaseRequest\Entities\PurchaseRequest']])->first();

        if ($approval_obj->approval_action_id == 2) {
            foreach ($id_PR as $v) {
                DB::table("approvals")->where("id", DB::table("approvals")->select("id")
                    ->where("document_type", "Modules\PurchaseRequest\Entities\PurchaseRequestDetail")
                    ->where("document_id", $v->id)->first()->id)
                    ->where("approval_action_id", 2)
                    ->update(["approval_action_id" => 1]);

                $PRDApproval = DB::table("approvals")->where("document_id", $v->id)
                    ->where("document_type", "Modules\PurchaseRequest\Entities\PurchaseRequestDetail")
                    ->select('*')
                    ->first();

                ApprovalHistory::where("approval_id", $PRDApproval->id)
                    ->where("approval_action_id", 2)
                    ->where("document_type", "Modules\PurchaseRequest\Entities\PurchaseRequestDetail")
                    ->delete();
            }
        }
        $approval = Approval::where([['document_id', '=', $id], ['document_type', '=', 'Modules\PurchaseRequest\Entities\PurchaseRequest'], ['approval_action_id', '=', 2]])->update(['approval_action_id' => 1]);

        ApprovalHistory::where("approval_id", $approval_obj->id)
            ->where("approval_action_id", 2)
            ->where("document_type", "Modules\PurchaseRequest\Entities\PurchaseRequest")
            ->delete();


        return redirect("/purchaserequest/detail/?id=" . $id);
    }

    public function item_pekerjaan_source(Request $request)
    {
        $item_pekerjaans = Itempekerjaan::select('id', 'name')->where('parent_id', NULL)->get();
        $obj = [];
        foreach ($item_pekerjaans as $key => $value) {
            # code...
            $obj[$value->id] = $value->name;
        }
        return response()->json($obj);
    }

    public function item_project_source(Request $request)
    {
        $items = ItemProject::select('id', 'item_id')->get();
        $obj = [];
        foreach ($items as $key => $value) {
            # code...
            $obj[$value->id] = $value->item->name;
        }
        return response()->json($obj);
    }

    public function brand_source(Request $request)
    {
        $brands = Brand::select('id', 'name')->get();
        $obj = [];
        foreach ($brands as $key => $value) {
            # code...
            $obj[$value->id] = $value->name;
        }
        return response()->json($obj);
    }

    public function satuan_source(Request $request)
    {
        $item_satuans = ItemSatuan::select('id', 'name')->get();
        $obj = [];
        foreach ($item_satuans as $key => $value) {
            # code...
            $obj[$value->id] = $value->name;
        }
        return response()->json($obj);
    }

    public function supplier_source(Request $request)
    {
        $item_satuans = Rekanan::select('id', 'name')->get();
        $obj = [];
        foreach ($item_satuans as $key => $value) {
            # code...
            $obj[$value->id] = $value->name;
        }
        return response()->json($obj);
    }

    public function filter_item_pekerjaan(Request $request)
    {
        $budget_id = $request->id;
        $result = [];
        //$getItemPekerjaans = BudgetTahunan::find($budget_id)->getTotalParentItemAttribute();
        $getItemPekerjaans = DB::table('budget_tahunan_details')->where('budget_tahunan_id', $budget_id)
            ->join('itempekerjaans', 'itempekerjaans.id', 'budget_tahunan_details.itempekerjaan_id')
            ->distinct()
            ->select('itempekerjaan_id as id', 'itempekerjaans.name as itempekerjaan', 'itempekerjaans.code as code')
            ->get();


        return response()->json(['item' => $getItemPekerjaans]);
    }

    public function changeItemBaseCategory(Request $request)
    {
        $category_id = $request->category_id;
        $items = null;
        $sub_category = null;
        $parent_category = null;
        if ($category_id != 0) {
            $items = ItemProject::select('item_projects.id as itemid', 'items.name as itemname')
                ->join('items', 'item_projects.item_id', 'items.id')
                ->where('items.sub_item_category_id', $category_id)
                ->where('project_id', $request->session()->get('project_id'))
                ->get();

            $parent_category = ItemCategory::find($category_id)->parent;
            if (count($items) <= 0) {
                $items = ItemProject::select('item_projects.id as itemid', 'items.name as itemname')
                    ->join('items', 'item_projects.item_id', 'items.id')
                    ->where('items.item_category_id', $category_id)
                    ->where('project_id', $request->session()->get('project_id'))->get();

                $parent_category = ItemCategory::find($category_id)->parent;
            }
        } else {

            $sub_category = ItemCategory::select('id', 'name')->where('parent_id', '<>', 0)->get();
            $parent_category = ItemCategory::select('id', 'name')->where('parent_id', '=', 0)->get();
            $items = ItemProject::select('item_projects.id as itemid', 'items.name as itemname')
                ->join('items', 'item_projects.item_id', 'items.id')
                ->where('project_id', $request->session()->get('project_id'))->get();
        }


        return response()->json(['items' => $items, 'all_categories' => $sub_category, 'parent_categories' => $parent_category]);
    }

    public function changeCategoryBaseParent(Request $request)
    {
        $parent = $request->parent;
        $items = null;
        $sub_category = null;
        $parent_category = null;
        if ($parent != 0) {
            $items = ItemProject::select('item_projects.id as itemid', 'items.name as itemname')
                ->join('items', 'item_projects.item_id', 'items.id')
                ->where('items.item_category_id', $parent)
                ->where('project_id', $request->session()->get('project_id'))
                ->get();

            $sub_category = ItemCategory::find($parent)->child;
            // if(count($items) <=0)
            // {

            //     $parent_category = ItemCategory::find($parent);
            // }


        } else {

            $sub_category = ItemCategory::select('id', 'name')->where('parent_id', '!=', 0)->get();
            $parent_category = ItemCategory::select('id', 'name')->where('parent_id', 0)->get();
            $items = ItemProject::select('item_projects.id as itemid', 'items.name as itemname')
                ->join('items', 'item_projects.item_id', 'items.id')
                ->where('project_id', $request->session()->get('project_id'))->get();
        }


        return response()->json(['items' => $items, 'all_categories' => $sub_category, 'parent_categories' => $parent_category]);
    }

    public function delete_detail(Request $request)
    {
        $id = $request->id;
        $delete = PurchaseRequestDetail::find($id)->delete();

        return redirect("/purchaserequest/edit/" . $request->PR);
    }

    public function edit_pr(Request $request)
    {
        date_default_timezone_set("Asia/Jakarta");
        $PRHeader = $request->id;
        $department = $request->department_id;
        $butuh_date = $request->butuh_date;
        $deskripsi_umum = $request->deskripsi_umum;
        $jumlah_item = $request->jumlah_item;
        $quantity = $request->kuantitas;
        $satuan = $request->satuan;
        $brand = $request->brand;
        $item = $request->item;
        $jumlah = $request->jumlah_item;
        $j_komparasi = $request->j_komparasi;
        $komparasi_supplier1 = $request->komparasi_supplier2_1;
        if ((int)$j_komparasi[0] > 1) {
            $komparasi_supplier2 = $request->komparasi_supplier2_2;
        } else {
            $komparasi_supplier2 = NULL;
        }
        if ((int)$j_komparasi[0] > 2) {
            $komparasi_supplier3 = $request->komparasi_supplier2_3;
        } else {
            $komparasi_supplier3 = NULL;
        }
        $coa = $request->coa;
        $coa2 = $request->coa2;
        $deskripsi_item = $request->deskripsi_item;
        $spk = $request->spk;
        $harga_estimasi = $request->harga_estimasi;
        $is_urgent = $request->is_urgen;
        $date = $request->waktu_transaksi;

        $budget = $request->budget_tahunan;
        $pt_id = DB::table("budgets")->select('pt_id')->where('department_id', $department)->first()->pt_id;
        $location = 1;

        $PR = PurchaseRequest::where("id",$request->id)->first();
        if ($PR->budget_tahunan_id != "0") {
            $coa = (int)$coa[0];
        } else {
            $coa = (int)$coa2[0];
        }

        $user_id = Auth::user()->id;
        $edit_details = PurchaseRequestDetail::where([
            ['id', '=', $request->details_id],
            ['purchaserequest_id', '=', $request->id]
        ])
            ->update(
                [
                    'item_id' => (int)$item[0],
                    'item_satuan_id' => (int)$satuan[0],
                    'brand_id' => (int)$brand[0],
                    'itempekerjaan_id' => (int)$coa,
                    'recomended_supplier' => $j_komparasi[0],
                    'rec_1' => (int)$komparasi_supplier1[0],
                    'rec_2' => (int)$komparasi_supplier2[0],
                    'rec_3' => (int)$komparasi_supplier3[0],
                    'quantity' => (int)$quantity[0],
                    'description' => $deskripsi_item[0],
                    'spk_id' => (int)$spk[0],
                    'harga_estimasi' => (int)$harga_estimasi[0],
                    'updated_by' => (int)$user_id
                ]

            );


        return redirect("/purchaserequest/edit/" . $request->id);
    }


    public function tambah(Request $request)
    {
        date_default_timezone_set("Asia/Jakarta");
        $PRHeader = $request->id;
        $department = $request->department_id;
        $butuh_date = $request->butuh_date;
        $deskripsi_umum = $request->deskripsi_umum;
        $jumlah_item = $request->jumlah_item;
        $quantity = $request->kuantitas;
        $satuan = $request->satuan;
        $brand = $request->brand;
        $item = $request->item;
        $jumlah = $request->jumlah_item;
        //$kategori = $request->
        $j_komparasi = $request->j_komparasi;
        $komparasi_supplier1 = $request->komparasi_supplier1;
        $komparasi_supplier2 = $request->komparasi_supplier2;
        $komparasi_supplier3 = $request->komparasi_supplier3;

        $budget_tahunan = PurchaseRequest::where("id", $request->id)
            ->first();

        if ($budget_tahunan->budget_tahunan_id != 0) {
            $coa = $request->coa;
        } else {
            $coa = $request->coa2;
        }
        $deskripsi_item = $request->deskripsi_item;
        $is_urgent = $request->is_urgen;
        $date = $request->waktu_transaksi;
        $spk = $request->spk;
        $harga_estimasi = $request->harga_estimasi;

        $budget = $request->budget_tahunan;
        $pt_id = DB::table("budgets")->select('pt_id')->where('department_id', $department)->first()->pt_id;
        $location = 1;

        $user_id = Auth::user()->id;
        $project_id = $request->session()->get('project_id');

        $PRD = new \Modules\PurchaseRequest\Entities\PurchaseRequestDetail;
        $PRD->purchaserequest_id = (int)$request->id;
        $PRD->itempekerjaan_id = (int)$coa[0];
        $PRD->item_id = (int)$item[0];
        $PRD->item_satuan_id = (int)$satuan[0];
        $PRD->brand_id = (int)$brand[0];
        $PRD->recomended_supplier = $j_komparasi[0];

        $PRD->quantity = (int)$quantity[0];
        $PRD->description = $deskripsi_item[0];
        $PRD->rec_1 = (int)$komparasi_supplier1[1];
        if ($PRD->recomended_supplier > 1) {
            $PRD->rec_2 = (int)$komparasi_supplier2[1];
        } else {
            $PRD->rec_2 = NULL;
        }
        if ($PRD->recomended_supplier > 2) {
            $PRD->rec_3 = (int)$komparasi_supplier3[1];
        } else {
            $PRD->rec_3 = NULL;
        }
        $PRD->delivery_date = $request->delivery_date;
        $PRD->spk_id    = (int)$spk[0];
        $PRD->harga_estimasi = (int)$harga_estimasi[0];
        $PRD->save();

        // \App\Helpers\Document::make_approval('Modules\PurchaseRequest\Entities\PurchaseRequestDetail',$PRD->id);
        CreateDocument::make_approval('Modules\PurchaseRequest\Entities\PurchaseRequestDetail', $PRD->id, $project_id, $pt_id);


        return redirect("/purchaserequest/edit/" . $request->id);
    }
    public function editPR(Request $request)
    {
        date_default_timezone_set("Asia/Jakarta");
        $butuh_date = $request->butuh_date;
        $deskripsi_umum = $request->deskripsi_umum;
        $is_urgent = $request->is_urgent;
        $budget = $request->budget_tahunan;
        $user_id = Auth::user()->id;

        $editpr = PurchaseRequest::where([['id', '=', $request->id]])
            ->update(
                [
                    'budget_tahunan_id' => $budget,
                    'butuh_date' => $butuh_date,
                    'is_urgent' => $is_urgent,
                    'description' => $deskripsi_umum,
                    'updated_by' => $user_id
                ]
            );

        return redirect("/purchaserequest/edit/" . $request->id);
    }


    public function makePDF(Request $request)
    {

        $PRHeader = PurchaseRequest::where("id", $request->id)->first();

        // return $PRHeader;
        $PRDetail = PurchaseRequestDetail::where("purchaserequest_id", $PRHeader->id)->get();
        // return $PRDetail;

        date_default_timezone_set('asia/jakarta');
        $date = date("Y-m-d H:i:s");
        $user = Auth::user();
        
        $pemesan = DB::table("purchaserequests")
                        ->where("purchaserequests.id", $request->id)   
                        ->join("users","users.id","purchaserequests.created_by")
                        ->join("user_details","user_details.user_id","users.id")
                        ->join("user_jabatans","user_jabatans.id","user_details.user_jabatan_id")
                        ->select("users.user_name as name","user_jabatans.name as jabatan")             
                        ->get();
        // return $pemesan;
        $penyetuju = DB::table("users")
                        ->join("user_details","user_details.user_id","users.id")
                        ->join("user_jabatans","user_jabatans.id","user_details.user_jabatan_id")
                        ->join("mappingperusahaans","mappingperusahaans.id","user_details.mappingperusahaan_id")
                        ->join("project_pt_users","project_pt_users.user_id","users.id")
                        ->where("user_details.user_jabatan_id",5)
                        ->where("project_pt_users.project_id",$PRHeader->project_for_id)
                        ->select("users.id as user_id","users.user_name as name","user_jabatans.name as jabatan")
                        ->distinct()
                        ->get();

        $project_pt = ProjectPt::where("project_id", $PRHeader->project_for_id)->first();

        $pdf = PDF::loadView('purchaserequest::pdf', compact('PRHeader', 'PRDetail', 'date', 'user', 'project_pt','pemesan','penyetuju'));


        return $pdf->stream('purchaserequest.pdf');
    }

    public function getSPK(Request $request)
    {
        $department_id = $request->department_id;
        $budget_tahunan = $request->budget_id;
        $department = Department::find($department_id);

        // return $department->spk;

        $result_SPK = [];

        foreach ($department->spk as $key => $value) {
            # code...
            // return $value['name'];

            $arr = [
                'id' => $value['id'],
                'spk_name' => $value['name'],
            ];

            array_push($result_SPK, $arr);
        }


        return response()->json(['result' => $result_SPK]);
    }

    public function harga_estimasi(Request $request)
    {
        // $harga = PurchaseOrderDetail::where('item_id',$request->id)->orderby('id', 'desc')->first();

        $harga = ItemPrice::where('item_id', $request->id)
            ->orderby('id', 'desc')
            ->first();
        if ($harga == null) {
            $satuan_harga = 0;
            $satuan_id = 0;
        } else {
            $satuan_harga = $harga->price;
            $satuan_id = $harga->item_satuan_id;
        }

        return response()->json(['data' => $satuan_harga, 'satuan' => $satuan_id]);
    }

    public function harga_estimasi_satuan(Request $request)
    {
        // $harga = PurchaseOrderDetail::where('item_id',$request->id)->orderby('id', 'desc')->first();

        $harga = ItemPrice::where('item_id', $request->id)
            ->where('item_satuan_id', $request->satuan)
            ->orderby('id', 'desc')
            ->first();
        if ($harga == null) {
            $satuan_harga = 0;
        } else {
            $satuan_harga = $harga->price;
        }

        return response()->json(['data' => $satuan_harga]);
    }

    public function repeat_order(Request $request)
    {

        $purchase_order = [];
        $PO = PurchaseRequest::join('purchaserequest_details', 'purchaserequest_details.purchaserequest_id', 'purchaserequests.id')
            ->join('tender_purchase_request_group_details', 'tender_purchase_request_group_details.id_purchase_request_detail', 'purchaserequest_details.id')
            ->join('tender_purchase_request_groups', 'tender_purchase_request_groups.id', 'tender_purchase_request_group_details.tender_purchase_request_groups_id')
            ->join('tender_purchase_request_group_rekanans', 'tender_purchase_request_group_rekanans.tender_purchase_request_group_id', 'tender_purchase_request_groups.id')
            ->join("tender_menang_pr", "tender_menang_pr.tender_purchase_group_rekanan_id", "tender_purchase_request_group_rekanans.id")
            ->join('purchaseorders', 'purchaseorders.id_tender_menang', 'tender_menang_pr.id')
            ->where("item_id", $request->item)
            ->where("purchaserequests.department_id", $request->department)
            ->select("purchaseorders.id as id_po", "purchaseorders.no as no_po")
            ->distinct()
            ->get();

        foreach ($PO as $key => $value) {
            # code...
            $arr = [
                'id' => $value->id_po,
                'no' => $value->no_po,
            ];


            array_push($purchase_order, $arr);
        }

        return response()->json(['result' => $purchase_order]);
    }

    public function data_po(Request $request)
    {

        $purchase_order = [];
        $PO = PurchaseOrder::where("id", $request->id)->get();

        if ($PO != null) {
            foreach ($PO as $key => $value) {
                # code...
                $PO_detail = PurchaseOrderDetail::where("purchaseorder_id", $value->id)
                    ->where("item_id", $request->item_id)
                    ->first();
                $arr = [
                    'id' => $value->id,
                    'no' => $value->no,
                    'rekanan_id' => $value->rekanan_id,
                    'harga_estimasi' => $PO_detail->harga_satuan,
                ];


                array_push($purchase_order, $arr);
            };
        } else {
            $purchase_order = null;
        }

        return response()->json(['result' => $purchase_order]);
    }

    public function tambah_kategori(Request $request)
    {
        $kategori = strtoupper($request->kategori);
        $cek_kategori = ItemCategory::where('name', $kategori)->first();

        if ($cek_kategori == null) {
            $tambah_kategori = new ItemCategory;
            $tambah_kategori->parent_id = 0;
            $tambah_kategori->name = $kategori;
            $tambah_kategori->save();
        } else {
            // return back()->withErrors(['Kategori Sudah Ada']);
        }
    }

    public function data_item(Request $request)
    {
        $kategori = ItemCategory::where('parent_id', 0)->get();
        $subkategori = ItemCategory::where('parent_id', '!=', 0)->get();
        $project_id = $request->session()->get('project_id');
        $item = ItemProject::where("project_id", $project_id)->get();
        $item_project = [];
        foreach ($item as $key => $value) {
            # code...
            $arr = [
                'id' => $value->id,
                'name' => $value->item->name,
            ];

            array_push($item_project, $arr);
        }

        return response()->json(['kategori' => $kategori, 'subkategori' => $subkategori, 'item' => $item_project]);
    }

    public function tambah_subkategori(Request $request)
    {
        $kategori = $request->kategori;
        $subkategori = strtoupper($request->subkategori);
        $cek_kategori = ItemCategory::where('name', $subkategori)->first();

        if ($cek_kategori == null) {
            $tambah_kategori = new ItemCategory;
            $tambah_kategori->parent_id = $kategori;
            $tambah_kategori->name = $subkategori;
            $tambah_kategori->save();
        } else {
            // return back()->withErrors(['Kategori Sudah Ada']);
        }
    }

    public function tambah_item(Request $request)
    {
        $kategori = $request->kategori;
        $subkategori = $request->subkategori;
        $item = strtoupper($request->item);
        $kode = strtoupper($request->kode);
        $description = $request->description;
        $project_id = $request->session()->get('project_id');
        $cek_item = Item::where('name', $item)->first();

        if ($cek_item == null) {
            $tambah_item = new Item;
            $tambah_item->item_category_id = $kategori;
            $tambah_item->sub_item_category_id = $subkategori;
            $tambah_item->kode = $kode;
            $tambah_item->name = $item;
            $tambah_item->description = $description;
            $tambah_item->save();

            $tambah_item_project = new ItemProject;
            $tambah_item_project->item_id = $tambah_item->id;
            $tambah_item_project->project_id = $project_id;
            $tambah_item_project->save();
        } else {
            $cek_item_project = ItemProject::where("item_id", $cek_item)->first();

            if ($cek_item_project == null) {
                $tambah_item_project = new ItemProject;
                $tambah_item_project->item_id = $cek_item->id;
                $tambah_item_project->project_id = $project_id;
                $tambah_item_project->save();
            } else {
                // return back()->withErrors(['Kategori Sudah Ada']);
            }
        }
    }

    public function tambah_brand(Request $request)
    {
        $kategori = $request->kategori;
        $subkategori = $request->subkategori;
        $brand = strtoupper($request->brand);
        $cek_brand = Brand::where('name', $brand)->first();

        if ($cek_brand == null) {
            $tambah_brand = new Brand;
            $tambah_brand->name = $brand;
            $tambah_brand->save();

            $tambah_brand_kategori = new BrandOfCategory;
            $tambah_brand_kategori->category_id = $subkategori;
            $tambah_brand_kategori->brand_id = $tambah_brand->id;
            $tambah_brand_kategori->save();
        } else {
            $cek_brand_kategori = BrandOfCategory::where("brand_id", $cek_brand->id)
                ->where("category_id", $subkategori)
                ->first();
            if ($cek_brand_kategori == null) {
                $tambah_brand_kategori = new BrandOfCategory;
                $tambah_brand_kategori->category_id = $subkategori;
                $tambah_brand_kategori->brand_id = $cek_brand->id;
                $tambah_brand_kategori->save();
            } else {
                return back()->withErrors(['Kategori Sudah Ada']);
            }
        }
    }

    public function tambah_satuan(Request $request)
    {
        $kategori = $request->kategori;
        $subkategori = $request->subkategori;
        // $item = $request->item;
        $item = ItemProject::where('id', $request->item)->first();
        $satuan = strtoupper($request->satuan);
        $konversi = $request->konversi;
        $cek_satuan = Satuan::where('name', $satuan)->first();

        if ($cek_satuan == null) {
            $tambah_satuan = new Satuan;
            $tambah_satuan->name = $satuan;
            $tambah_satuan->konversi = $konversi;
            $tambah_satuan->save();

            $tambah_satuan_item = new ItemSatuan;
            $tambah_satuan_item->id_satuan = $tambah_satuan->id;
            $tambah_satuan_item->item_id = $item->item_id;
            $tambah_satuan_item->name = $satuan;
            $tambah_satuan_item->konversi = $konversi;
            $tambah_satuan_item->save();
        } else {
            $cek_satuan_item = ItemSatuan::where("id_satuan", $cek_satuan->id)
                ->where("item_id", $item->item_id)
                ->first();
            if ($cek_satuan_item == null) {
                $tambah_satuan_item = new ItemSatuan;
                $tambah_satuan_item->id_satuan = $cek_satuan->id;
                $tambah_satuan_item->item_id = $item->item_id;
                $tambah_satuan_item->name = $satuan;
                $tambah_satuan_item->konversi = $konversi;
                $tambah_satuan_item->save();
            } else {
                return back()->withErrors(['Kategori Sudah Ada']);
            }
        }
    }

    public function coaDetail(Request $request)
    {

        $coaDetail = Itempekerjaan::where('parent_id', $request->coaHeader)->get();

        return response()->json(['result' => $coaDetail]);
    }

    public function cekSinonimKategori(Request $request)
    {
        $stat = 1;
        $data = [];
        $response = file_get_contents("http://kateglo.com/api.php?format=json&phrase=" . $request->key);
        $response = json_decode($response, true);
        $keyKapital = strtoupper($request->key);
        if ($response['kateglo'] != null) {
            if (array_key_exists("all_relation", $response['kateglo'])) {
                $count = count($response['kateglo']['all_relation']);
            } else {
                $count = 0;
            }
        } else {
            $count = 0;
        }
        $category = ItemCategory::all();

        if ($response['kateglo'] != null) {
            if (array_key_exists("all_relation", $response['kateglo'])) {
                foreach ($category as $key => $value) {
                    # code...
                    if (strpos($value->name, $keyKapital) !== FALSE) {
                        $stat = 0;
                        $arr = [
                            'name' => $value->name,
                        ];
                        array_push($data, $arr);
                        $stat = 0;
                    } else {
                        for ($i = 0; $i < $count; $i++) {
                            # code...
                            if (strtoupper($response['kateglo']['all_relation'][$i]['related_phrase']) == $value->name) {
                                $stat = 0;
                                $arr = [
                                    'name' => $value->name,
                                ];
                                array_push($data, $arr);
                                $stat = 0;
                            }
                        }
                    }
                }
            } else {
                foreach ($category as $key => $value) {
                    # code...
                    // return strpos($keyKapital, $value->name);
                    if (strpos($value->name, $keyKapital) !== FALSE) {
                        $stat = 0;
                        $arr = [
                            'name' => $value->name,
                        ];
                        array_push($data, $arr);
                        $stat = 0;
                    }
                }
            }
        } else {
            foreach ($category as $key => $value) {
                # code...
                // return strpos($keyKapital, $value->name);
                if (strpos($value->name, $keyKapital) !== FALSE) {
                    $stat = 0;
                    $arr = [
                        'name' => $value->name,
                    ];
                    array_push($data, $arr);
                    $stat = 0;
                }
            }
        }
        return response()->json(['data' => $data, 'status' => $stat]);
    }

    public function cekSinonimItem(Request $request)
    {
        $project_id = $request->session()->get('project_id');
        $stat = 1;
        $data = [];
        $response = file_get_contents("http://kateglo.com/api.php?format=json&phrase=" . $request->key);
        $response = json_decode($response, true);
        $keyKapital = strtoupper($request->key);
        if ($response['kateglo'] != null) {
            if (array_key_exists("all_relation", $response['kateglo'])) {
                $count = count($response['kateglo']['all_relation']);
            } else {
                $count = 0;
            }
        } else {
            $count = 0;
        }
        $category = ItemProject::where('project_id', $project_id)->get();

        if ($response['kateglo'] != null) {
            if ($response['kateglo']['all_relation'] != null) {
                foreach ($category as $key => $value) {
                    # code...
                    if (strpos($value->item->name, $keyKapital) !== FALSE) {
                        $stat = 0;
                        $arr = [
                            'name' => $value->item->name,
                        ];
                        array_push($data, $arr);
                        $stat = 0;
                    } else {
                        for ($i = 0; $i < $count; $i++) {
                            # code...
                            if (strtoupper($response['kateglo']['all_relation'][$i]['related_phrase']) == $value->item->name) {
                                $stat = 0;
                                $arr = [
                                    'name' => $value->item->name,
                                ];
                                array_push($data, $arr);
                                $stat = 0;
                            }
                        }
                    }
                }
            } else {
                if (strpos($value->item->name, $keyKapital) !== FALSE) {
                    # code...
                    if ($value->item->name == $keyKapital) {
                        $stat = 0;
                        $arr = [
                            'name' => $value->item->name,
                        ];
                        array_push($data, $arr);
                        $stat = 0;
                    }
                }
            }
        } else {
            if (strpos($value->item->name, $keyKapital) !== FALSE) {
                # code...
                if ($value->item->name == $keyKapital) {
                    $stat = 0;
                    $arr = [
                        'name' => $value->item->name,
                    ];
                    array_push($data, $arr);
                    $stat = 0;
                }
            }
        }
        return response()->json(['data' => $data, 'status' => $stat]);
    }
    public function test(Request $request)
    {
        $project_id = $request->session()->get('project_id');
        $project = Project::find($project_id);
        $user = Auth::user();
        $approve = UserDetail::where("user_id", $user->id)->select("can_approve")->first()->can_approve;

        $PR =  PurchaseRequest::select('*')->where('project_for_id', $project_id)->orderBy('created_at', 'desc')->get();

        $isDepartment =   UserDetail::select("mappingperusahaans.department_id")
            ->where("user_details.user_id", $user->id)
            ->join("mappingperusahaans", "mappingperusahaans.id", "user_details.mappingperusahaan_id")
            ->first()->department_id;
        return view('purchaserequest::test', compact("user", "PR", "project", "approve", "isDepartment"));
    }

    public function encrypt(Request $request)
    {

        $encrypt = bcrypt($request->pass);

        return response()->json(['data' => $encrypt]);
    }


}
