<?php

namespace Modules\Inventory\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Modules\Inventory\Http\Requests\RequestPermintaanbarang;
use Modules\Project\Entities\Project;
use Modules\Pt\Entities\Pt;
use Modules\Department\Entities\Department;
use Modules\Spk\Entities\Spk;
use Modules\User\Entities\User;
use Modules\Inventory\Entities\Permintaanbarang;
use Modules\Inventory\Entities\PermintaanbarangDetail;
use Modules\Inventory\Entities\StatusPermintaan;
use Modules\Inventory\Entities\CreateDocument;

use Modules\Approval\Entities\Approval;
use Modules\Approval\Entities\ApprovalAction;
use Modules\Approval\Entities\ApprovalHistory;
use Modules\Approval\Entities\ApprovalReference;

use Modules\PurchaseRequest\Entities\PurchaseRequest;
use Modules\PurchaseRequest\Entities\PurchaseRequestDetail;

use Modules\Inventory\Entities\ItemProject;
use Modules\Inventory\Entities\ItemSatuan;
use PDF;
use Auth;
use DB;

class PermintaanBarangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware(['auth']);
    }
    
    public function index(Request $request)
    {
        $user = Auth::user();
        $permintaans = Permintaanbarang::all();
        $project = Project::find($request->session()->get('project_id'));
        return view('inventory::permintaanbarang.index', compact('permintaans','project','user'));
    }
    
    public function add(Request $request)
    {   
      // $md = \Illuminate\Support\Facades\Crypt::encrypt("1234|abcd");
      // return $md;
        if($request->code != NULL){
            $decrypt = \Illuminate\Support\Facades\Crypt::decrypt($request->code);

          // return $decrypt;
            $flag=1;

            $data = explode("|" , $decrypt);
            $id = $data[0];
            $pass = $data[1];
            $project_id = $data[2];
            $PT_id = $data[3];
            $department_id = $data[4];
            $StatusPermintaan_id = $data[5];
            $PurchaseRequest_id = $data[6];

            $project        = Project::find($request->session()->get('project_id'));
            $projects       = Project::get();
            $pts            = User::find(Auth::user()->id)->project_pt_users->where('project_id',$request->session()->get('project_id'));
            $departments    = Department::where("id",$department_id)->get();
            $spks           = Spk::select('id','no')->get();
            $users          = User::get();
            $user           = \Auth::user();
            $statusPermintaans = StatusPermintaan::where("id",$StatusPermintaan_id)->select('id','name')->get();
            $purchaserequest = PurchaseRequest::where("id",$PurchaseRequest_id)->first();
            
            return view('inventory::permintaanbarang.add_form', compact('project', 'projects', 'pts', 'departments', 'users', 'spks','statusPermintaans','user','purchaserequest','flag'));
        }else{
            $flag=0;
            $project = Project::find($request->session()->get('project_id'));
            $projects       = Project::get();
            $pts            = User::find(Auth::user()->id)->project_pt_users->where('project_id',$request->session()->get('project_id'));
            $departments    = Department::get();
            $spks           = Spk::select('id','no')->get();
            $users          = User::get();
            $user           = \Auth::user();
            $statusPermintaans = StatusPermintaan::select('id','name')->get();

            return view('inventory::permintaanbarang.add_form', compact('project', 'projects', 'pts', 'departments', 'users', 'spks','statusPermintaans','user','purchaserequest','flag'));
        }
        
    }
    
    public function addPost(RequestPermintaanbarang $request)
    {
        // return $request;
        $stat =0;
        $project_id = $request->session()->get('project_id');
        $user_id = Auth::user()->id;
            $permintaans                            = new Permintaanbarang;
            $permintaans->project_id                = $project_id;
            $permintaans->pt_id                     = $request->pt_id;
            $permintaans->department_id             = $request->department_id;
            $permintaans->spk_id                    = $request->spk_id;
            $permintaans->user_id                   = $user_id;
            $permintaans->no                        = CreateDocument::createDocumentNumber('PB',$request->department_id,$project_id,$user_id);
            $permintaans->date                      = $request->date;
            $permintaans->status_permintaan_id      = $request->status;
            $permintaans->description               = $request->description2;
            $permintaans->purchaserequest_id        = $request->pilih_pr;
            $status                                 = $permintaans->save();
        

        for ($i=0; $i < count($request->qty_item); $i++) { 
            # code...
            if ($request->qty_item[$i] != 0 && $request->qty_item[$i] != null && $request->qty_item[$i] != "" ) {
                # code...
                $action = PermintaanbarangDetail::create(
                    [
                        'permintaanbarang_id' => $permintaans->id,
                        'item_id' => $request->item_id[$i],
                        'item_satuan_id'=> $request->item_satuan_id[$i],
                        'is_inventarisasi' => $request->status,
                        'quantity' => $request->qty_item[$i],
                        'butuh_date' => $request->butuh_date[$i],
                        'description' => $request->description[$i],
                    ]
                );
            }
        }

        
        if($status)
        {
           $stat =1;
        }
        return response()->json(['return'=>$stat]);
    }

    public function edit(Request $request)
    {
        $permintaan                = Permintaanbarang::find($request->id);
        $projects                   = Project::get();
        $pts            = User::find(Auth::user()->id)->project_pt_users->where('project_id',$request->session()->get('project_id'));
        $departments                = Department::get();
        $spks                       = Spk::get();
        $users                      = User::get();
        $user = Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        $statusPermintaans = StatusPermintaan::select('id','name')->get();
        return view('inventory::permintaanbarang.edit_form', compact('project','permintaan', 'projects', 'pts', 'departments', 'spks', 'users','statusPermintaans','user'));
    }

    public function update(Request $request)
    {
        $user_id = Auth::user()->id;
        $stat = 1;
        $permintaans                            = Permintaanbarang::find($request->id);
        $permintaans->project_id                = $request->session()->get('project_id');
        $permintaans->pt_id                     = $request->pt_id;
        $permintaans->department_id             = $request->department_id;
        $permintaans->spk_id                    = $request->spk_id;
        $permintaans->user_id                   = $user_id;
        $permintaans->status_permintaan_id      = $request->status;
        $permintaans->date                      = $request->date;
        $permintaans->description               = $request->description;
        $status                                 = $permintaans->save();

        if($status)
        {
            $stat =1;
        }

        return response()->json(['return'=>$stat]);
    }

    public function delete(Request $request)
    {
        $permintaans = Permintaanbarang::find($request->id);
        $status      = $permintaans->delete();

        if ($status) 
        {
            return $permintaans;
        }else{
            return 'Failed';
        }
    }

    public function print(Request $request)
    {
        $id_permintaan = $request->permintaan_barang_id;
        $permintaan = Permintaanbarang::find($id_permintaan);
       // $details = 
        //return view('permintaan_barang.print',compact('permintaan'));     
        $pdf = PDF::loadView('inventory::permintaanbarang.print',compact('permintaan'))->setPaper('a4','potrait');
        return $pdf->stream('laporan_permintaan_barang.pdf');

    }

    public function printReport(Request $request)
    {
        $start_date = $request->start_opname;
        $end_date = $request->end_opname;
        $permintaans = Permintaanbarang::whereBetween('created_at',[$start_date,$end_date])->get();
        $arrhasil = [];
        foreach ($permintaans as $key => $value) {
            # code...
            $details = PermintaanbarangDetail::select('item_id','item_satuan_id',DB::raw("sum(quantity) as total"))->where('permintaanbarang_id',$value->id)->groupBy('item_id','item_satuan_id')->get();
            $arrdetail = [];
            foreach ($details as $key => $each) {
                # code...
                $arr = [
                    'item_name'=>$each->item->item->name,
                    'satuan_name'=>$each->satuan->name,
                    'quantity'=>$each->total
                ];

                array_push($arrdetail, $arr);
            }

            $arrpermintaan = [
                'department'=>$value->department->name,
                'pt'=>$value->pt->name,
                'spk'=>is_null($value->spk) ? '': $value->spk->no,
                'no'=>$value->no,
                'status'=>$value->StatusPermintaan->name,
                'detail'=>$arrdetail
            ];

            array_push($arrhasil,$arrpermintaan);
        }

        $pdf = PDF::loadView('inventory::permintaanbarang.printReport',compact('arrhasil','request'))->setPaper('a4','potrait');
        return $pdf->stream('laporan_permintaanbarang.pdf'); 
    }

    public function approvePermintaanBarang(Request $request)
    {
        $stat = false;
        $document_id = $request->id;
        $approval_action_id = 6;
        $documentType = 'Modules\Inventory\Entities\Permintaanbarang';
        $user_id = Auth::user()->id;
        
        $approvePermintaanbarang = Permintaanbarang::find($document_id)->update(['confirm_by_requester'=>(int)1]);
        if($approvePermintaanbarang)
        {
            $createApproval = Approval::create(
                [
                    'approval_action_id'=>$approval_action_id,
                    'document_id'=>$document_id,
                    'document_type'=>$documentType
                ]
            );
            if($createApproval)
            {
                $createHistory =ApprovalHistory::create([
                    'no_urut'=>$user_id,
                    'user_id'=>$user_id,
                    'approval_action_id'=>$approval_action_id,
                    'approval_id'=>$createApproval->id,
                    'document_id'=>$document_id,
                    'document_type'=>$documentType,
                    'description'=>'Permintaanbarang'
                ]);

                if($createHistory)
                {
                    $stat = true;
                }
            }
        }

        return response()->json($stat);
        
    }

    public function getPurchaseRequest(Request $request)
    {
        $department_id = $request->department_id;
        $user = Auth::user();
         // return $department_id;
        $result_PR = [];
        $getPR = PurchaseRequest::where("department_id",$department_id)->where("created_by",$user->id)->get();

        foreach ($getPR as $key => $value) {
            # code...
            $jumlah = 0;

            foreach ($value->details as $key => $nilai) {
                # code...
                $jumlah += $nilai->permintaanbarang_detail->sum("quantity");
            }

            $sisa = $value->details->sum("quantity") - $jumlah;

            if($sisa != 0){
                $arr = [
                    'id'=>$value->id,
                    'pr_no'=>$value->no
                ];

                array_push($result_PR, $arr);
            }
        }

        return response()->json(['result'=>$result_PR]);
    }

    public function items_stock(Request $request){
        // return "halo";
        
        $results = [];
        $project = Project::find($request->session()->get('project_id'));
        $user = \Auth::user();
        $stock = DB::table("inventories")
                    ->join("item_satuans", "item_satuans.id", "inventories.item_satuan_id")
                    ->join("item_projects", "item_projects.id", "inventories.item_id")
                    ->join("items", "items.id", "item_projects.item_id")
                    ->where("inventories.project_id", $project->id)
                    ->select("inventories.item_id as item_id",DB::raw("SUM(inventories.quantity) as sum"), "item_satuans.name as satuan","items.name as item_name","item_satuans.id as item_satuan_id")
                    ->groupBy("inventories.item_id","item_satuans.name","items.name","item_satuans.id")
                    ->get();

        foreach ($stock as $key => $value) {
            # code...
            // return $value->satuan;
            $item_project = ItemProject::where("id", $value->item_id)->first();
            // return $category;
            $listResults = array(
                'id'=>$value->item_id,
                'item_id'=>$value->item_id,
                'category' => $item_project->item->category->name,
                'item_name' => $value->item_name,
                'tersedia'=>number_format($value->sum,2,".",","),
                'satuan' =>$value->satuan,
                'quantity' => 0,
                'item_satuan_id'=> $value->item_satuan_id
            );
            array_push($results, $listResults);
        }

        // return $results;
        return datatables()->of($results)->toJson();
    }

    public function getQuantityPr(Request $request){
        // return $request;


        $results = [];
        $project = Project::find($request->session()->get('project_id'));
        $user = \Auth::user();
        $stock = DB::table("inventories")
                    ->join("item_satuans", "item_satuans.id", "inventories.item_satuan_id")
                    ->join("item_projects", "item_projects.id", "inventories.item_id")
                    ->join("items", "items.id", "item_projects.item_id")
                    ->where("inventories.project_id", $project->id)
                    ->select("inventories.item_id as item_id",DB::raw("SUM(inventories.quantity) as sum"), "item_satuans.name as satuan","items.name as item_name","item_satuans.id as item_satuan_id")
                    ->groupBy("inventories.item_id","item_satuans.name","items.name","item_satuans.id")
                    ->get();

        $pr = PurchaseRequest::where("id", $request->id_pr)->first();
        // return $pr;
        if($pr != null){
            foreach ($stock as $key => $value) {
                # code...
                $item_project =  ItemProject::where("id", $value->item_id)->first();

                if(count($pr->details->where("item_id", $value->item_id)) != 0){
                    $pr_detail_quantity = $pr->details->where("item_id", $value->item_id)->sum("quantity");
                }else{
                    $pr_detail_quantity = 0;
                }

                $listResults = array(
                    'id'=>$value->item_id,
                    'item_id'=>$value->item_id,
                    'category' => $item_project->item->category->name,
                    'item_name' => $value->item_name,
                    'tersedia'=>number_format($value->sum,2,".",","),
                    'satuan' =>$value->satuan,
                    'quantity' => $pr_detail_quantity,
                    'item_satuan_id'=> $value->item_satuan_id
                );
                array_push($results, $listResults);
            }
        }else{
            foreach ($stock as $key => $value) {
                # code...
                $item_project = ItemProject::where("id", $value->item_id)->first();

                $listResults = array(
                    'id'=>$value->item_id,
                    'item_id'=>$value->item_id,
                    'category' => $item_project->item->category->name,
                    'item_name' => $value->item_name,
                    'tersedia'=>number_format($value->sum,2,".",","),
                    'satuan' =>$value->satuan,
                    'quantity' => 0,
                    'item_satuan_id'=> $value->item_satuan_id
                );
                array_push($results, $listResults);
            }
        }
        
        // return $results;
        return response()->json($results);
    }
    


}
