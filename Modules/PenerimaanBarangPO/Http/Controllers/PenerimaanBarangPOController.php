<?php

namespace Modules\PenerimaanBarangPO\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Project\Entities\Project;
use Modules\TenderPurchaseRequest\Entities\PurchaseOrder;
use Modules\PenerimaanBarangPO\Entities\PenerimaanBarangPO;
use Modules\TenderPurchaseRequest\Entities\PurchaseOrderDetail;
use Modules\Approval\Entities\ApprovalHistory;
use Modules\Inventory\Entities\Inventory;
use Modules\Inventory\Entities\CreateDocument;
use App\Mail\EmailPr;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;
use Modules\User\Entities\User;
use Modules\Inventory\Entities\ItemProject;
use Modules\Inventory\Entities\Item;
use Modules\Inventory\Entities\Brand;
use Modules\Inventory\Entities\Satuan;
use Modules\Inventory\Entities\ItemSatuan;
use Modules\PenerimaanBarangPO\Entities\PenerimaanBonus;
use Modules\PenerimaanBarangPO\Entities\PenerimaanBonusDetail;


use Modules\PenerimaanBarangPO\Entities\PenerimaanBarangPODetail;
use Modules\Approval\Entities\Approval;
use Auth;
use DB;

class PenerimaanBarangPOController extends Controller
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
        $user = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        $PO1 = DB::table("purchaseorders as po")
              ->join("rekanans","rekanans.id","po.rekanan_id")
              ->select("po.id","po.no","rekanans.name as name","po.description")
              ->join("approvals as ap","ap.document_id","po.id")
              ->where("ap.document_type","Modules\TenderPurchaseRequest\Entities\PurchaseOrder")
              ->where("ap.approval_action_id",6)
              ->leftJoin("purchaseorder_details as pod","pod.purchaseorder_id","po.id")
              ->leftJoin("penerimaan_barang_po_details as pbpod","pbpod.po_detail_id","pod.id")
              ->distinct()
              ->get();
             
        return view('penerimaanbarangpo::index',compact("user","project","PO1"));
    }

    public function getPBPO(Request $request)
    {
        $result = [];
        $PO = DB::table("purchaseorders as po")
              ->join("rekanans","rekanans.id","po.rekanan_id")
              ->join("approvals as ap","ap.document_id","po.id")
              ->where("ap.document_type","Modules\TenderPurchaseRequest\Entities\PurchaseOrder")
              ->where("ap.approval_action_id",6)
              ->leftJoin("purchaseorder_details as pod","pod.purchaseorder_id","po.id")
              ->leftJoin("penerimaan_barang_po_details as pbpod","pbpod.po_detail_id","pod.id")
              ->join("penerimaan_barang_pos","penerimaan_barang_pos.id","pbpod.penerimaan_barang_po_id")
              ->distinct()
              ->select("po.id as id","po.no as no","rekanans.name as name","po.description","po.id_tender_menang as id_tender_menang","penerimaan_barang_pos.no as pbpo_no","penerimaan_barang_pos.id as pbpo_id")
              ->get();

        foreach ($PO as $key => $value) {
          $Approval = DB::table("approvals")->where("document_id",$value->pbpo_id)
                                            ->where("document_type","Modules\PenerimaanBarangPO\Entities\PenerimaanBarangPO")
                                            ->join('approval_actions','approval_actions.id','approvals.approval_action_id')
                                            ->select("approval_actions.description as status")
                                            ->get();
      
          $arr = [
            'id'=>$value->id,
            'no_po'=>$value->no,
            'nomor_penerimaanbarang'=>$value->pbpo_no,
            'supplier'=>$value->name,
            'status_approve'=>$Approval[0]->status
          ];

          array_push($result, $arr);
        }

        return datatables()->of($result)->toJson();
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create(Request $request)
    {
        $user = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        $result_rekanan = [];
        $result_cek = [];

        // $project=Project::find($request->session()->get('project_id'));
        $po_approved = PurchaseOrder::where('project_for_id',$project->id)->select('*')->get();
        $rekanan_po = PurchaseOrder::select('rekanan_id')->distinct()->get();
        // $rekanan_po = DB::table('purchaseorders')->select(DB::raw('SELECT * FROM SELECT(id, rekanan_id,
        //                 ROW_NUMBER() OVER (PARTITION BY rekanan_id ORDER BY id) AS RowNumber
        //                 FROM   purchaseorders) WHERE   a.RowNumber = 1'));
        // ->select([DB::RAW('DISTINCT(my_column)'), 'product.organization', 'revision'])
        
        // WHERE   a.RowNumber = 1
        // return $rekanan_po;
        $gudang = DB::table("warehouses")->select("*")->get();
        // return $gudang;

        // return $po_approved;

        // $Approval = DB::table("approvals")->where("document_id",$po_approved[6]->id)
        //                                   ->where("document_type","Modules\PurchaseOrder\Entities\PurchaseOrder")
        //                                   ->select('*')
        //                                   ->get();
        // return $Approval[0]->approval_action_id;

        foreach ($po_approved as $key => $value) {
          # code...
          // return $value;
          $model = PurchaseOrderDetail::where("purchaseorder_id",$value->id)
                                    ->join("item_projects","item_projects.id","purchaseorder_details.item_id")
                                    ->join("items","items.id","item_projects.item_id")
                                    // ->join("penerimaan_barang_po_details","penerimaan_barang_po_details.po_detail_id","purchaseorder_details.id")
                                    ->select("items.id as item_id","items.name as item_name","purchaseorder_details.brand_id as brand_id","purchaseorder_details.satuan_id as satuan_id","purchaseorder_details.description as description","purchaseorder_details.quantity as quantity","purchaseorder_details.id as id","purchaseorder_details.id as pod_id","purchaseorder_details.item_id as item_pod_id")->get();

          foreach ($model as $key => $v) {
          # code...
          $qty_item = DB::table("penerimaan_barang_po_details")
                      ->where("po_detail_id",$v->id)
                      ->select(DB::raw('sum(penerimaan_barang_po_details.quantity) as quantity'))
                      ->first();

            if($qty_item<=>NULL){
              $sisa = $v->quantity-$qty_item->quantity;
            }else{
              $sisa = $v->quantity;
            }
            $sisa += $sisa;
          }
          // return $sisa;
          $Approval = DB::table("approvals")->where("document_id",$value->id)
                                               ->where("document_type","Modules\TenderPurchaseRequest\Entities\PurchaseOrder")
                                               ->select('*')
                                               ->get();
                                               
          if($Approval[0]->approval_action_id == 6)
          {
            if(in_array($value->rekanan_id, $result_cek) != true){
              $arr = [
                'id'=>$value->rekanan_id,
                'name'=>$value->vendor->name,
              ];
              array_push($result_rekanan, $arr);
              array_push($result_cek, $value->rekanan_id);
            }
          }
        }
        $user_gudang = DB::table("user_warehouses as uw")
                        ->join("warehouses as w","w.id","uw.warehouse_id")
                        ->where("w.project_id",$request->session()->get('project_id'))
                        ->join("users as u","u.id","uw.user_id")
                        ->select("uw.user_id as id","u.user_name as name")
                        ->get();

        $item = ItemProject::where('project_id', $project->id)->get();

        $satuan = Satuan::get();
        
        $brand = Brand::where('deleted_at', null)->get();

        // $gudang = ;

        return view('penerimaanbarangpo::create',compact("user","project","result_rekanan","user_gudang","gudang","rekanan_po","item","satuan",'brand'));
    }

    public function getPO(Request $request){
      $PO = DB::table("purchaseorders as po")
          ->where("po.rekanan_id",$request)
              ->join("approvals as ap","ap.document_id","po.id")
              ->where("ap.document_type","Modules\TenderPurchaseRequest\Entities\PurchaseOrder")
              ->where("ap.approval_action_id",6)
              ->select("po.id as id","po.no")
              ->get();
      // return $PO;
      // foreach ($PO as $key => $v) {
      // $model = PurchaseOrderDetail::where("purchaseorder_id",$v->id)
      //                             ->join("item_projects","item_projects.id","purchaseorder_details.item_id")
      //                             ->join("items","items.id","item_projects.item_id")
      //                             // ->join("penerimaan_barang_po_details","penerimaan_barang_po_details.po_detail_id","purchaseorder_details.id")
      //                             ->select("items.id as item_id","items.name as item_name","purchaseorder_details.brand_id as brand_id","purchaseorder_details.satuan_id as satuan_id","purchaseorder_details.description as description","purchaseorder_details.quantity as quantity","purchaseorder_details.id as id","purchaseorder_details.id as pod_id","purchaseorder_details.item_id as item_pod_id")->get();

      //   foreach ($model as $key => $value) {
      //     # code...
      //     $qty_item = DB::table("penerimaan_barang_po_details")
      //                 ->where("po_detail_id",$value->id)
      //                 ->select(DB::raw('sum(penerimaan_barang_po_details.quantity) as quantity'))
      //                 ->first();
      //       if($qty_item<=>NULL){
      //         $sisa = $value->quantity-$qty_item->quantity;
      //       }else{
      //         $sisa = $value->quantity;
      //       }
      //     }
      //   }

        return Response::json($PO);
    }

    public function getDataDetailPO(Request $request)
    {
        $results = [];
        $id = PurchaseOrder::find($request->id)->id;
        $model = PurchaseOrderDetail::where("purchaseorder_id",$id)
                                    ->join("item_projects","item_projects.id","purchaseorder_details.item_id")
                                    ->join("items","items.id","item_projects.item_id")
                                    // ->join("penerimaan_barang_po_details","penerimaan_barang_po_details.po_detail_id","purchaseorder_details.id")
                                    ->select("items.id as item_id","items.name as item_name","purchaseorder_details.brand_id as brand_id","purchaseorder_details.satuan_id as satuan_id","purchaseorder_details.description as description","purchaseorder_details.quantity as quantity","purchaseorder_details.id as id","purchaseorder_details.id as pod_id","purchaseorder_details.item_id as item_pod_id")->get();

        foreach ($model as $key => $value) {
          # code...
          $qty_item = DB::table("penerimaan_barang_po_details")
                      ->where("po_detail_id",$value->id)
                      ->select(DB::raw('sum(penerimaan_barang_po_details.quantity) as quantity'))
                      // ->select('penerimaan_barang_po_details.quantity as quantity')
                      ->first();

            if($qty_item<=>NULL){
              $sisa = $value->quantity-$qty_item->quantity;
            }else{
              $sisa = $value->quantity;
            }

            if ($sisa!=0) {
            $arr = [
                'item_name'=>is_null($value->item_name) ? 'kosong' : $value->item_name,
                'satuan_name'=>is_null($value->satuan) ? 'kosong' : $value->satuan->name,
                'satuan_id'=>$value->satuan_id,
                'item_id'=>$value->item_pod_id,
                'brand_name'=>is_null($value->brand) ? 'kosong' : $value->brand->name,
                'quantity'=>is_null($qty_item) ? 0 :$qty_item->quantity,
                'description'=>$value->description,
                'sisa_quantity'=>$sisa,
                'quantity_total'=>$value->quantity,
                'po_detail_id'=>$value->pod_id
            ];

            array_push($results, $arr);
            }
        }

        return response()->json($results);
    }


    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {

        // return $request;
        $allitems = json_decode($request->allitems);
        $allitems_bonus = json_decode($request->allitemsbonus);
        // return $allitems_bonus;

        $PBPO = new \Modules\PenerimaanBarangPO\Entities\PenerimaanBarangPO;
        $PBPO->project_for_id = Project::find($request->session()->get('project_id'))->id;
        $PBPO->no   = CreateDocument::createDocumentNumber('PBPO', 2,$request->session()->get('project_id'), Auth::user()->id);
        $PBPO->date = $request->date;
        $PBPO->user_id = Auth::user()->id;
        $PBPO->no_do = $request->no_refrensi;
        $PBPO->save();

        $approval = new Approval;
        $approval->approval_action_id   = 1;
        $approval->document_id          = $PBPO->id;
        $approval->document_type        = "Modules\PenerimaanBarangPO\Entities\PenerimaanBarangPO";
        $approval->total_nilai          = 0;
        $createApproval = $approval->save();

        if($PBPO)
            {
                // $approval = new Approval;
                // $approval->approval_action_id   = 1;
                // $approval->document_id          = $PO->id;
                // $approval->document_type        = "Modules\PurchaseOrder\Entities\PurchaseOrder";
                // $createApproval = $approval->save();
                // if($createApproval)
                // {
                   if(count($allitems) > 0){
                      for($count = 0;$count < count($allitems);$count++)
                      {
                        // return $allitems[$count]->gudang_id;
                            // $terakhir = DB::table('penerimaan_barang_pos')->orderBy('id', 'DESC')->first();
                            $PBPOD = new PenerimaanBarangPODetail;
                            $PBPOD->penerimaan_barang_po_id       = $PBPO->id;  // nanti
                            $PBPOD->po_detail_id                  = $allitems[$count]->po_detail_id;
                            $PBPOD->item_id                       = $allitems[$count]->item_id;
                            $PBPOD->item                          = $allitems[$count]->item_name;
                            $PBPOD->quantity                      = $allitems[$count]->quantity;
                            $PBPOD->satuan_id                     = $allitems[$count]->satuan_id;
                            $PBPOD->satuan                        = $allitems[$count]->satuan_name;
                            $PBPOD->description                   = $allitems[$count]->description;
                            $PBPOD->gudang_id                     = $allitems[$count]->gudang;
                            $PBPOD->description_item_diterima     = $allitems[$count]->deskripsi_item_diterima;
                            $PBPOD->save();

                            $approval = new Approval;
                            $approval->approval_action_id   = 1;
                            $approval->document_id          = $PBPOD->id;
                            $approval->document_type        = "Modules\PenerimaanBarangPO\Entities\PenerimaanBarangPODetail";
                            $approval->total_nilai          = 0;
                            $createApproval = $approval->save();
                         
                          
                      }
                   }

                   if(count($allitems_bonus) > 0){
                     $bonus = new PenerimaanBonus;
                     $bonus->penerimaan_barang_po_id = $PBPO->id;
                     $bonus->save();

                     for($count = 0;$count < count($allitems_bonus);$count++){

                      $item = ItemProject::find($allitems_bonus[$count]->item_id);

                      $satuan = Satuan::find($allitems_bonus[$count]->satuan_id);
                      
                      $brand = Brand::find($allitems_bonus[$count]->brand_id);

                      $bonus_detail = new PenerimaanBonusDetail;
                      $bonus_detail->penerimaan_bonus_id           = $bonus->id;  // nanti
                      $bonus_detail->item_id                       = $allitems_bonus[$count]->item_id;
                      $bonus_detail->item                          = $item->item->name;
                      $bonus_detail->quantity                      = $allitems_bonus[$count]->quantity;
                      $bonus_detail->satuan_id                     = $allitems_bonus[$count]->satuan_id;
                      $bonus_detail->satuan                        = $satuan->name;
                      $bonus_detail->brand_id                      = $allitems_bonus[$count]->brand_id;
                      $bonus_detail->brand                         = $brand->name;
                      $bonus_detail->gudang_id                     = $allitems_bonus[$count]->gudang;
                      $bonus_detail->description_item_diterima     = $allitems_bonus[$count]->deskripsi_item_diterima;
                      $bonus_detail->save();

                     }
                   }
            }
        return redirect("/penerimaanbarangpo/");

    }

    public function detail(Request $request){
        $user = Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        $PO_umum= PurchaseOrder::where("no",$request->id)->get();

        $uraian_PO = PurchaseOrder::join("tender_menang_pr","tender_menang_pr.id","purchaseorders.id_tender_menang")
                                ->join("tender_purchase_request_group_rekanans","tender_purchase_request_group_rekanans.id","tender_menang_pr.tender_purchase_group_rekanan_id")
                                ->join("tender_purchase_request_groups","tender_purchase_request_groups.id","tender_purchase_request_group_rekanans.tender_purchase_request_group_id")
                                ->join("tender_purchase_request_group_details","tender_purchase_request_group_details.tender_purchase_request_groups_id","tender_purchase_request_groups.id")
                                ->join("purchaserequest_details","purchaserequest_details.id","tender_purchase_request_group_details.id_purchase_request_detail")
                                ->join("purchaserequests","purchaserequests.id","purchaserequest_details.purchaserequest_id")
                                // ->join("purchaseorder_details","purchaseorder_details.purchaseorder_id","purchaseorders.id")
                                ->join("departments","departments.id","purchaserequests.department_id")
                                ->join("item_satuans","item_satuans.id","purchaserequest_details.item_satuan_id")
                                // ->join("purchaseorder_details","purchaseorder_details.purchaseorder_id","purchaseorders.id")
                                // ->where("purchaserequest_details.item_id",$id)
                                ->where("purchaseorders.id",$PO_umum[0]->id)
                                ->select("purchaseorders.id as PO_id","purchaserequests.id as PR_id","purchaserequest_details.id as PRD_id","purchaserequest_details.item_id","purchaserequest_details.quantity as PRD_quantity","purchaserequests.is_urgent as urgent","purchaserequests.butuh_date as butuh_date","purchaserequests.department_id as department_id","departments.name as department_name","item_satuans.name as item_satuan_name","purchaserequests.date as dibuat_date")
                                ->orderBy("purchaserequests.is_urgent","DESC")
                                ->orderBy("purchaserequests.butuh_date","ASC")
                                ->orderBy("purchaserequests.date","ASC")
                                ->get();
        // return $uraian_PO;

        $PBPO = DB::table('penerimaan_barang_pos')->join('penerimaan_barang_po_details','penerimaan_barang_po_details.penerimaan_barang_po_id','penerimaan_barang_pos.id')
                                         ->join('purchaseorder_details','purchaseorder_details.id','penerimaan_barang_po_details.po_detail_id')
                                         ->join('purchaseorders','purchaseorders.id','purchaseorder_details.purchaseorder_id')
                                         ->join('approvals','approvals.document_id','penerimaan_barang_pos.id')
                                         ->join('approval_actions','approval_actions.id','approvals.approval_action_id')
                                         ->where('purchaseorders.id',$PO_umum[0]->id)
                                         // ->where('approvals.document_id','penerimaan_barang_pos.id')
                                         ->where('approvals.document_type','Modules\PenerimaanBarangPO\Entities\PenerimaanBarangPO')
                                         ->orderby("penerimaan_barang_pos.id", "desc")
                                         ->select('purchaseorders.id as PO_id','penerimaan_barang_pos.id as PBPO_id','penerimaan_barang_pos.no as PBPO_no','approval_actions.description as action_description','approvals.id as app_id','approvals.document_type','approval_actions.id as id_approval_action','penerimaan_barang_pos.date as date_diterima')
                                         ->distinct()
                                         ->get();

        $PBPO2 = DB::table('penerimaan_barang_po_details')->join('penerimaan_barang_pos','penerimaan_barang_pos.id','penerimaan_barang_po_details.penerimaan_barang_po_id')
                                         ->join('purchaseorder_details','purchaseorder_details.id','penerimaan_barang_po_details.po_detail_id')
                                         ->join('purchaseorders','purchaseorders.id','purchaseorder_details.purchaseorder_id')
                                         ->join('approvals','approvals.document_id','penerimaan_barang_pos.id')
                                         ->where('purchaseorders.id',$PO_umum[0]->id)
                                         ->where('approvals.approval_action_id',6)
                                         ->where('approvals.document_type','Modules\PenerimaanBarangPO\Entities\PenerimaanBarangPO')
                                         ->select("*")
                                         // ->distinct()
                                         // ->get();
                                         ->sum("penerimaan_barang_po_details.quantity");
        // return $PBPO2;
        $results = [];
        $results2 = [];
        $model = PurchaseOrderDetail::where("purchaseorder_id",$PO_umum[0]->id)
                                    ->join("item_projects","item_projects.id","purchaseorder_details.item_id")
                                    ->join("items","items.id","item_projects.item_id")
                                    // ->join("penerimaan_barang_po_details","penerimaan_barang_po_details.po_detail_id","purchaseorder_details.id")
                                    // ->join("penerimaan_barang_pos","penerimaan_barang_pos.id","penerimaan_barang_po_details.penerimaan_barang_po_id")
                                    ->select("items.id as item_id","items.name as item_name","purchaseorder_details.brand_id as brand_id","purchaseorder_details.satuan_id as satuan_id","purchaseorder_details.description as description","purchaseorder_details.quantity as quantity","purchaseorder_details.id as id","purchaseorder_details.id as pod_id","purchaseorder_details.item_id as item_pod_id","purchaseorder_details.harga_satuan as harga_satuan","purchaseorder_details.ppn as ppn","purchaseorder_details.pph as pph","purchaseorder_details.discon as discon","purchaseorder_details.purchaseorder_id as po_id")->get();

        $i = 0;
        $sisa_total = [];
        $sisa_total[0] = 0;

        $totalpph=0;
        $subtotal = 0;
        $total_disc = 0;
        $totalppn = 0;
        foreach ($model as $key => $v) {
          $diskon = $v->discon/100*($v->harga_satuan*$v->quantity);
          $total_disc += $diskon;
          $sbtotal = $v->harga_satuan*$v->quantity;
          $subtotal += $sbtotal-$diskon;

          $totalppn += $v->ppn/100*($sbtotal-$diskon);
          $totalpph += $v->pph/100*($sbtotal-$diskon);
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
                        ->join('approvals','approvals.document_id','penerimaan_barang_po_details.id')
                        ->where('approvals.approval_action_id',6)
                        ->where('approvals.document_type','Modules\PenerimaanBarangPO\Entities\PenerimaanBarangPODetail')
                        ->where("penerimaan_barang_po_details.po_detail_id",$value->id)
                        ->select("penerimaan_barang_po_details.quantity as quantity")
                         // ->select('penerimaan_barang_po_details.quantity as quantity')
                        ->distinct()
                        // ->get();
                        ->sum("penerimaan_barang_po_details.quantity");
            // return $qty_item;            
          $qty_detailPenerimaan = DB::table("penerimaan_barang_po_details")
                                ->where("po_detail_id",$value->id)
                                ->select('quantity')
                                ->get();
           // return $value->quantity;
            if($qty_item!=NULL){
              $sisa = $value->quantity-$qty_item;
            }else{
              $sisa = $value->quantity;
            }
            $sisa_total[$i] = $sisa;
            // return $sisa;
            $arr = [
                'po_id'=>$value->po_id,
                'item_name'=>is_null($value->item_name) ? 'kosong' : $value->item_name,
                'satuan_name'=>is_null($value->satuan) ? 'kosong' : $value->satuan->name,
                'satuan_id'=>$value->satuan_id,
                'item_id'=>$value->item_pod_id,
                'brand_name'=>is_null($value->brand) ? 'kosong' : $value->brand->name,
                'quantity'=>is_null($qty_item) ? 0 :$qty_item,
                'description'=>$value->description,
                'sisa_quantity'=>$sisa,
                'quantity_total'=>$value->quantity,
                'po_detail_id'=>$value->pod_id,
                'harga_satuan'=>$value->harga_satuan,
                'ppn'=>$value->ppn,
                'pph'=>$value->pph,
                'discon'=>$value->discon,
                'quantity_diterima'=>$qty_detailPenerimaan[0]->quantity
            ];

            array_push($results, $arr);
        }
        $sisa_total_item = array_sum($sisa_total);
        // return $sisa_total_item;
        $jumlah_seluruh_item = DB::table('purchaseorder_details')->where("purchaseorder_id",$PO_umum[0]->id)->select(DB::raw('sum(purchaseorder_details.quantity) as quantity'))->get();

        // ->select(DB::raw('sum(barangkeluar_details.quantity) as quantity'))->get();

        return view('penerimaanbarangpo::detail',compact("user","project","PO_umum","PODetail","results","results2","PBPO","model","sisa_total_item","jumlah_seluruh_item","PBPO2","Penerimaan_barang"));
    }

    public function approvePB(Request $request)
    {
        $id ->
        $stat = false;
        // $PBPO = DB::table('penerimaan_barang_po_details')->join('penerimaan_barang_pos','penerimaan_barang_pos.id','penerimaan_barang_po_details.penerimaan_barang_po_id')
        //                                  ->join('purchaseorder_details','purchaseorder_details.id','penerimaan_barang_po_details.po_detail_id')
        //                                  ->join('purchaseorders','purchaseorders.id','purchaseorder_details.purchaseorder_id')
        //                                  ->join('warehouses','warehouses.id','penerimaan_barang_pos.gudang_id')
        //                                  ->where('purchaseorders.id',$request->id)
        //                                  ->select('purchaseorders.id as PO_id','penerimaan_barang_pos.id as PBPO_id','warehouses.name as gudang_name','penerimaan_barang_pos.no as PBPO_no')
        //                                  ->distinct()
        //                                  ->get();
           // return $PBPO;                              
          $update = DB::table("approvals")->where('document_id', $request->id)->where('document_type', "Modules\PenerimaanBarangPO\Entities\PenerimaanBarangPO")
              ->update([
                  'approval_action_id' => 6
              ]);

          if($update)
          {
            $stat = true;
          }

        return response()->json($stat);
    }

    public function request_approve_PB_perdetail(Request $request)
    {
        $stat = false;
        $project_id = $request->session()->get('project_id');

        $approval_obj = Approval::where([['document_id','=',$request->id],['document_type','=','Modules\PenerimaanBarangPO\Entities\PenerimaanBarangPO']]);

                if($approval_obj->first()->approval_action_id == 1)
                {               
                    $update = Approval::where('document_id', $request->id)->where('document_type', "Modules\PenerimaanBarangPO\Entities\PenerimaanBarangPO")
                      ->update(['approval_action_id' => 2 ]);

                    $approval = Approval::where('document_id', $request->id)->where('document_type', "Modules\PenerimaanBarangPO\Entities\PenerimaanBarangPO")->first();

                    ApprovalHistory::where("approval_id",$approval->id)
                                                       ->where("approval_action_id",1)
                                                       ->delete();

                    // CreateDocument::make_approval_history($approval->id,'Modules\PenerimaanBarangPO\Entities\PenerimaanBarangPO',$project_id);

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

                    $simpan_inventories = PenerimaanBarangPODetail::where('penerimaan_barang_po_id',$request->id)
                                         ->get();
                                                                                            
                    foreach ($simpan_inventories as $key => $value) {
                        $approval_PenerimaanBarangDetail = Approval::where([['document_id','=',$value->id],['document_type','=','Modules\PenerimaanBarangPO\Entities\PenerimaanBarangPODetail']])->update(['approval_action_id'=>2]);

                        $approval_detail = Approval::where([['document_id','=',$value->id],['document_type','=','Modules\PenerimaanBarangPO\Entities\PenerimaanBarangPODetail']])->first();

                        ApprovalHistory::where("approval_id",$approval->id)
                                                       ->where("approval_action_id",1)
                                                       ->delete();

                        // CreateDocument::make_approval_history( $approval_detail->id,'Modules\PenerimaanBarangPO\Entities\PenerimaanBarangPODetail',$project_id);

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

          if($update)
          {
            $stat = true;
          }

        return response()->json($stat);
    }

    public function undo_request_approve_PB_perdetail(Request $request)
    {
        $stat = false;     
         $approval_obj = Approval::where([['document_id','=',$request->id],['document_type','=','Modules\PenerimaanBarangPO\Entities\PenerimaanBarangPO']]);
                // return $approval_obj;

                if($approval_obj->first()->approval_action_id == 2)
                {                               
                    $update = DB::table("approvals")->where('document_id', $request->id)->where('document_type', "Modules\PenerimaanBarangPO\Entities\PenerimaanBarangPO")
                            ->update(['approval_action_id' => 1]);
                    $approval = Approval::where('document_id', $request->id)->where('document_type', "Modules\PenerimaanBarangPO\Entities\PenerimaanBarangPO")->first();

                    ApprovalHistory::where("approval_id",$approval->id)
                                                       ->where("approval_action_id",2)
                                                       ->delete();

                    // CreateDocument::make_approval_history($approval->id,'Modules\PenerimaanBarangPO\Entities\PenerimaanBarangPO',$project_id);

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
                                         ->where('penerimaan_barang_po_id',$request->id)
                                         ->select('id')
                                         ->get();
                      // return $simpan_inventories;
                                                                                            
                    foreach ($simpan_inventories as $key => $value) {
                        $approval_PenerimaanBarangDetail = Approval::where([['document_id','=',$value->id],['document_type','=','Modules\PenerimaanBarangPO\Entities\PenerimaanBarangPODetail']])->update(['approval_action_id'=>1]);

                        $approval_detail = Approval::where([['document_id','=',$value->id],['document_type','=','Modules\PenerimaanBarangPO\Entities\PenerimaanBarangPODetail']])->first();

                        ApprovalHistory::where("approval_id",$approval->id)
                                                       ->where("approval_action_id",2)
                                                       ->delete();

                        // CreateDocument::make_approval_history( $approval_detail->id,'Modules\PenerimaanBarangPO\Entities\PenerimaanBarangPODetail',$project_id);

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

          if($update)
          {
            $stat = true;
          }

        return response()->json($stat);
    }

    public function approve_PB_perdetail(Request $request)
    {
        $stat = false;     
         $approval_obj = Approval::where([['document_id','=',$request->id],['document_type','=','Modules\PenerimaanBarangPO\Entities\PenerimaanBarangPO']]);
                // return $approval_obj;

                if($approval_obj->first()->approval_action_id == 2)
                {                               
                  $update = DB::table("approvals")->where('document_id', $request->id)->where('document_type', "Modules\PenerimaanBarangPO\Entities\PenerimaanBarangPO")
                            ->update(['approval_action_id' => 6]);
                  $simpan_inventories = DB::table('penerimaan_barang_po_details')
                                         ->join('penerimaan_barang_pos','penerimaan_barang_pos.id','penerimaan_barang_po_details.penerimaan_barang_po_id')
                                         ->join('purchaseorder_details','purchaseorder_details.id','penerimaan_barang_po_details.po_detail_id')
                                         ->join('purchaseorders','purchaseorders.id','purchaseorder_details.purchaseorder_id')
                                         ->join('warehouses','warehouses.id','penerimaan_barang_pos.gudang_id')
                                         ->where('penerimaan_barang_po_id',$request->id)
                                         ->select('penerimaan_barang_po_details.item_id as item_id','purchaseorders.rekanan_id as rekanan_id','penerimaan_barang_pos.gudang_id as gudang_id','penerimaan_barang_po_details.id as sumber_id','penerimaan_barang_pos.date as date','penerimaan_barang_po_details.quantity as quantity','penerimaan_barang_po_details.satuan_id as satuan_id','purchaseorder_details.harga_satuan as harga_satuan','purchaseorder_details.ppn as ppn','penerimaan_barang_po_details.description as description')
                                         ->distinct()
                                         ->get();
                      // return $simpan_inventories;
                                                                                            
                    foreach ($simpan_inventories as $key => $value) {
                      $approval_PenerimaanBarangDetail = Approval::where([['document_id','=',$value->sumber_id],['document_type','=','Modules\PenerimaanBarangPO\Entities\PenerimaanBarangPODetail']])->update(['approval_action_id'=>6]);

                      // $PRDApproval = DB::table("approvals")->where("document_id",$value->sumber_id)
                      //                                    ->where("document_type","Modules\PenerimaanBarangPO\Entities\PenerimaanBarangPODetail")
                      //                                    ->select('*')
                      //                                    ->get();

                      // $AH = new ApprovalHistory;
                      // $AH->user_id = Auth::user()->id;
                      // $AH->approval_id = $PRDApproval[0]->id;
                      // $AH->approval_action_id = $PRDApproval[0]->approval_action_id;

                      // $AH->document_id = $PRDApproval[0]->document_id;
                      // $AH->document_type = $PRDApproval[0]->document_type;
                      // $status = $AH->save();

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
          // $PRDApproval = DB::table("approvals")->where("document_id",$request->id)
          //                                                ->where("document_type","Modules\PenerimaanBarangPO\Entities\PenerimaanBarangPO")
          //                                                ->select('*')
          //                                                ->get();

          //   $AH = new ApprovalHistory;
          //   $AH->user_id = Auth::user()->id;
          //   $AH->approval_id = $PRDApproval[0]->id;
          //   $AH->approval_action_id = $PRDApproval[0]->approval_action_id;

          //   $AH->document_id = $PRDApproval[0]->document_id;
          //   $AH->document_type = $PRDApproval[0]->document_type;
          //   $status = $AH->save();

          if($update)
          {
            $stat = true;
          }

        return response()->json($stat);
    }

    public function undo_approve_PB_perdetail(Request $request)
    {
        $stat = false;     
         $approval_obj = Approval::where([['document_id','=',$request->id],['document_type','=','Modules\PenerimaanBarangPO\Entities\PenerimaanBarangPO']]);
                // return $approval_obj;

                if($approval_obj->first()->approval_action_id == 6)
                {                               
                  $update = DB::table("approvals")->where('document_id', $request->id)->where('document_type', "Modules\PenerimaanBarangPO\Entities\PenerimaanBarangPO")
                            ->update(['approval_action_id' => 2]);
                  $simpan_inventories = DB::table('penerimaan_barang_po_details')
                                         ->where('penerimaan_barang_po_id',$request->id)
                                         ->select('id')
                                         ->get();
                      // return $simpan_inventories;
                                                                                            
                    foreach ($simpan_inventories as $key => $value) {
                      $approval_PenerimaanBarangDetail = Approval::where([['document_id','=',$value->id],['document_type','=','Modules\PenerimaanBarangPO\Entities\PenerimaanBarangPODetail']])->update(['approval_action_id'=>2]);

                      // $PRDApproval = DB::table("approvals")->where("document_id",$value->id)
                      //                                    ->where("document_type","Modules\PenerimaanBarangPO\Entities\PenerimaanBarangPODetail")
                      //                                    ->select('*')
                      //                                    ->get();

                      // $AH = new ApprovalHistory;
                      // $AH->user_id = Auth::user()->id;
                      // $AH->approval_id = $PRDApproval[0]->id;
                      // $AH->approval_action_id = $PRDApproval[0]->approval_action_id;

                      // $AH->document_id = $PRDApproval[0]->document_id;
                      // $AH->document_type = $PRDApproval[0]->document_type;
                      // $status = $AH->save();
                    }
                }
          // $PRDApproval = DB::table("approvals")->where("document_id",$request->id)
          //                                                ->where("document_type","Modules\PenerimaanBarangPO\Entities\PenerimaanBarangPO")
          //                                                ->select('*')
          //                                                ->get();

          //   $AH = new ApprovalHistory;
          //   $AH->user_id = Auth::user()->id;
          //   $AH->approval_id = $PRDApproval[0]->id;
          //   $AH->approval_action_id = $PRDApproval[0]->approval_action_id;

          //   $AH->document_id = $PRDApproval[0]->document_id;
          //   $AH->document_type = $PRDApproval[0]->document_type;
          //   $status = $AH->save();

          if($update)
          {
            $stat = true;
          }

        return response()->json($stat);
    }

    public function approvePBPOall(Request $request)
    {
         $stat = false;
         $arr_id = json_decode($request->id);
         if(count($arr_id) > 0)
         {
            $checkInsert = 0;
            for ($i=0; $i < count($arr_id) ; $i++) { 
                # code...
                $po = PenerimaanBarangPO::where('no',$arr_id[$i])->first();
                $approval_obj = Approval::where([['document_id','=',$po->id],['document_type','=','Modules\PenerimaanBarangPO\Entities\PenerimaanBarangPO']]);

                if($approval_obj->first()->approval_action_id == 2)
                {
                    $change_status = $approval_obj->update(['approval_action_id'=>6]);


                    $simpan_inventories = DB::table('penerimaan_barang_po_details')
                                         ->join('penerimaan_barang_pos','penerimaan_barang_pos.id','penerimaan_barang_po_details.penerimaan_barang_po_id')
                                         ->join('purchaseorder_details','purchaseorder_details.id','penerimaan_barang_po_details.po_detail_id')
                                         ->join('purchaseorders','purchaseorders.id','purchaseorder_details.purchaseorder_id')
                                         ->join('warehouses','warehouses.id','penerimaan_barang_po_details.gudang_id')
                                         ->where('penerimaan_barang_po_id',$po->id)
                                         ->select('penerimaan_barang_po_details.item_id as item_id','purchaseorders.rekanan_id as rekanan_id','penerimaan_barang_po_details.gudang_id as gudang_id','penerimaan_barang_po_details.id as sumber_id','penerimaan_barang_pos.date as date','penerimaan_barang_po_details.quantity as quantity','penerimaan_barang_po_details.satuan_id as satuan_id','purchaseorder_details.harga_satuan as harga_satuan','purchaseorder_details.ppn as ppn','penerimaan_barang_po_details.description as description')
                                         ->distinct()
                                         ->get();
                      // return $simpan_inventories;
                                                                                            
                    foreach ($simpan_inventories as $key => $value) {
                      $approval_PenerimaanBarangDetail = Approval::where([['document_id','=',$value->sumber_id],['document_type','=','Modules\PenerimaanBarangPO\Entities\PenerimaanBarangPODetail']])->update(['approval_action_id'=>6]);

                      $PRDApproval = DB::table("approvals")->where("document_id",$value->sumber_id)
                                                         ->where("document_type","Modules\PenerimaanBarangPO\Entities\PenerimaanBarangPODetail")
                                                         ->select('*')
                                                         ->get();

                      $AH = new ApprovalHistory;
                      $AH->user_id = Auth::user()->id;
                      $AH->approval_id = $PRDApproval[0]->id;
                      $AH->approval_action_id = $PRDApproval[0]->approval_action_id;

                      $AH->document_id = $PRDApproval[0]->document_id;
                      $AH->document_type = $PRDApproval[0]->document_type;
                      $status = $AH->save();

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

                    $PRDApproval = DB::table("approvals")->where("document_id",$po->id)
                                                         ->where("document_type","Modules\PenerimaanBarangPO\Entities\PenerimaanBarangPO")
                                                         ->select('*')
                                                         ->get();

                    $AH = new ApprovalHistory;
                    $AH->user_id = Auth::user()->id;
                    $AH->approval_id = $PRDApproval[0]->id;
                    $AH->approval_action_id = $PRDApproval[0]->approval_action_id;

                    $AH->document_id = $PRDApproval[0]->document_id;
                    $AH->document_type = $PRDApproval[0]->document_type;
                    $status = $AH->save();

                    if($change_status)
                    {
                        $checkInsert++;
                    }
                }
            }

            if($checkInsert > 0)
            {
                $stat = true;
            }
         }


         return response()->json($stat);
    }

    public function undo_approvePBPO(Request $request)
    {
        $stat = false;
           $arr_id = json_decode($request->id);
           if(count($arr_id) > 0)
           {
              $checkInsert = 0;
              for ($i=0; $i < count($arr_id) ; $i++) { 
                  # code...
                  $po = PenerimaanBarangPO::where('no',$arr_id[$i])->first();
                  $approval_obj = Approval::where([['document_id','=',$po->id],['document_type','=','Modules\PenerimaanBarangPO\Entities\PenerimaanBarangPO']]);
                  if($approval_obj->first()->approval_action_id == 6)
                  {
                      $change_status = $approval_obj->update(['approval_action_id'=>2]);

                       $simpan_inventories = DB::table('penerimaan_barang_po_details')
                                         ->where('penerimaan_barang_po_id',$po->id)
                                         ->select('id')
                                         ->get();                                                                                            
                    foreach ($simpan_inventories as $key => $value) {
                      $approval_PenerimaanBarangDetail = Approval::where([['document_id','=',$value->id],['document_type','=','Modules\PenerimaanBarangPO\Entities\PenerimaanBarangPODetail']])->update(['approval_action_id'=>2]);

                      $PRDApproval = DB::table("approvals")->where("document_id",$value->id)
                                                         ->where("document_type","Modules\PenerimaanBarangPO\Entities\PenerimaanBarangPODetail")
                                                         ->select('*')
                                                         ->get();

                      $AH = new ApprovalHistory;
                      $AH->user_id = Auth::user()->id;
                      $AH->approval_id = $PRDApproval[0]->id;
                      $AH->approval_action_id = $PRDApproval[0]->approval_action_id;

                      $AH->document_id = $PRDApproval[0]->document_id;
                      $AH->document_type = $PRDApproval[0]->document_type;
                      $status = $AH->save();
                    }

                       $PRDApproval = DB::table("approvals")->where("document_id",$po->id)
                                                         ->where("document_type","Modules\PenerimaanBarangPO\Entities\PenerimaanBarangPO")
                                                         ->select('*')
                                                         ->get();

                      $AH = new ApprovalHistory;
                      $AH->user_id = Auth::user()->id;
                      $AH->approval_id = $PRDApproval[0]->id;
                      $AH->approval_action_id = $PRDApproval[0]->approval_action_id;

                      $AH->document_id = $PRDApproval[0]->document_id;
                      $AH->document_type = $PRDApproval[0]->document_type;
                      $status = $AH->save();

                      if($change_status)
                      {
                          $checkInsert++;
                      }
                  }
              }

              if($checkInsert > 0)
              {
                  $stat = true;
              }
           }


           return response()->json($stat);
    }

    public function request_approvePBPO(Request $request)
    {
         $stat = false;
         $arr_id = json_decode($request->id);
         if(count($arr_id) > 0)
         {
          // return $arr_id;
            $checkInsert = 0;
            for ($i=0; $i < count($arr_id) ; $i++) { 
                # code...
                $po = PenerimaanBarangPO::where('no',$arr_id[$i])->first();
                $approval_obj = Approval::where([['document_id','=',$po->id],['document_type','=','Modules\PenerimaanBarangPO\Entities\PenerimaanBarangPO']]);
                // return $approval_obj;

                if($approval_obj->first()->approval_action_id == 1)
                {
                    $change_status = $approval_obj->update(['approval_action_id'=>2]);

                    $approval = Approval::where([['document_id','=',$po->id],['document_type','=','Modules\TenderPurchaseRequest\Entities\TenderPurchaseRequestGroupRekanan']])->first();

                    ApprovalHistory::where("approval_id",$approval->id)
                                                   ->where("approval_action_id",2)
                                                   ->delete();

                    CreateDocument::make_approval_history($approval->id,'Modules\TenderPurchaseRequest\Entities\TenderPurchaseRequestGroupRekanan',$project_id);

                   $simpan_inventories = DB::table('penerimaan_barang_po_details')
                                         ->where('penerimaan_barang_po_id',$po->id)
                                         ->select('id')
                                         ->get();  
                                                                                            
                    foreach ($simpan_inventories as $key => $value) {
                        $approval_PenerimaanBarangDetail = Approval::where([['document_id','=',$value->id],['document_type','=','Modules\PenerimaanBarangPO\Entities\PenerimaanBarangPODetail']])->update(['approval_action_id'=>2]);

                        $approval = Approval::where([['document_id','=',$value->id],['document_type','=','Modules\TenderPurchaseRequest\Entities\TenderPurchaseRequestGroupRekanan']])->first();

                        ApprovalHistory::where("approval_id",$approval->id)
                                                   ->where("approval_action_id",2)
                                                   ->delete();

                        CreateDocument::make_approval_history($approval->id,'Modules\TenderPurchaseRequest\Entities\TenderPurchaseRequestGroupRekanan',$project_id);
                    }

                    if($change_status)
                    {
                        $checkInsert++;
                    }
                }
            }

            if($checkInsert > 0)
            {
                $stat = true;
            }
         }


         return response()->json($stat);
    }

    public function undo_request_approvePBPO(Request $request)
    {
        $stat = false;
           $arr_id = json_decode($request->id);
           if(count($arr_id) > 0)
           {
              $checkInsert = 0;
              for ($i=0; $i < count($arr_id) ; $i++) { 
                  # code...
                  $po = PenerimaanBarangPO::where('no',$arr_id[$i])->first();
                  $approval_obj = Approval::where([['document_id','=',$po->id],['document_type','=','Modules\PenerimaanBarangPO\Entities\PenerimaanBarangPO']]);

                  if($approval_obj->first()->approval_action_id == 2)
                  {
                      $change_status = $approval_obj->update(['approval_action_id'=>1]);

                      $approval = Approval::where([['document_id','=',$po->id],['document_type','=','Modules\TenderPurchaseRequest\Entities\TenderPurchaseRequestGroupRekanan']])->first();

                      ApprovalHistory::where("approval_id",$approval->id)
                                                   ->where("approval_action_id",2)
                                                   ->delete();

                      CreateDocument::make_approval_history($approval->id,'Modules\TenderPurchaseRequest\Entities\TenderPurchaseRequestGroupRekanan',$project_id);

                      $simpan_inventories = DB::table('penerimaan_barang_po_details')
                                         ->where('penerimaan_barang_po_id',$po->id)
                                         ->select('id')
                                         ->get();  
                      // return $simpan_inventories;
                                                                                            
                    foreach ($simpan_inventories as $key => $value) {
                        $approval_PenerimaanBarangDetail = Approval::where([['document_id','=',$value->id],['document_type','=','Modules\PenerimaanBarangPO\Entities\PenerimaanBarangPODetail']])->update(['approval_action_id'=>1]);

                        $approval = Approval::where([['document_id','=',$value->id],['document_type','=','Modules\TenderPurchaseRequest\Entities\TenderPurchaseRequestGroupRekanan']])->first();

                        ApprovalHistory::where("approval_id",$approval->id)
                                                   ->where("approval_action_id",2)
                                                   ->delete();

                        CreateDocument::make_approval_history($approval->id,'Modules\TenderPurchaseRequest\Entities\TenderPurchaseRequestGroupRekanan',$project_id);

                    }

                      if($change_status)
                      {
                          $checkInsert++;
                      }
                  }
              }

              if($checkInsert > 0)
              {
                  $stat = true;
              }
           }

           return response()->json($stat);
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return view('penerimaanbarangpo::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit()
    {
        return view('penerimaanbarangpo::edit');
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

    public function request_setuju_PB_perdetail(Request $request){
      // return $request;

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
          ->select("purchaserequests.id as id_pr", "users.id as id_user", "users.user_name as pembuat", "users.email as email", "purchaserequests.no as no_pr", "penerimaan_barang_pos.id as pb_id", "purchaserequests.date as date_dibuat")
          ->distinct()
          ->orderby("id_pr", "asc")
          ->get();

        // return $melacak_PR;
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

              // return $value;
          Mail::to($value->email)->send(new EmailPr($value));
      }

        // return $melacak_PR;
       

 

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

          $inventory = new Inventory;
          $inventory->project_id = $project_id;
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
      $rekanan = $value->rekanan_id;
      $bonus = PenerimaanBonus::where('penerimaan_barang_po_id', $request->id)->first();
      if($bonus != null){
        foreach ($bonus->penerimaan_bonus_detail as $key => $value) {
          # code...
          $item_project = ItemProject::where("id", $value->item_id)->first();
          $satuan_project = ItemSatuan::where("item_id",  $item_project->item->id)->where("id_satuan", $value->satuan_id)->first();

          $inventory = new Inventory;
          $inventory->project_id = $project_id;
          $inventory->item_id = $value->item_id;
          $inventory->rekanan_id = $rekanan;
          $inventory->warehouse_id = $value->gudang_id;
          $inventory->sumber_id = $value->id;
          $inventory->sumber_type = "Modules\PenerimaanBarangPO\Entities\PenerimaanBonusDetail";
          $inventory->date = $value->penerimaan_bonus->pbo->date;
          $inventory->quantity = $value->quantity;
          $inventory->item_satuan_id = $satuan_project->id;
          $inventory->quantity_terpakai = NULL;
          $inventory->price = 0;
          $inventory->ppn = 0;
          $inventory->description = $value->description_item_diterima;
          $status = $inventory->save();
        }
      }

      $update = Approval::where('document_id', $request->id)->where('document_type', "Modules\PenerimaanBarangPO\Entities\PenerimaanBarangPO")
                      ->update(['approval_action_id' => 6 ]);
    }
}
