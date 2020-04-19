<?php

namespace Modules\GoodReceive\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Project\Entities\Project;
use Modules\Rekanan\Entities\Rekanan;
use Modules\PenerimaanBarangPO\Entities\PenerimaanBarangPO;
use Modules\PenerimaanBarangPO\Entities\PenerimaanBarangPODetail;
use Modules\GoodReceive\Entities\Goodreceive;
use Modules\GoodReceive\Entities\GoodreceiveDetail;
use Modules\TenderPurchaseRequest\Entities\PurchaseOrder;
use Modules\TenderPurchaseRequest\Entities\PurchaseOrderDetail;
use Modules\DownPaymentPurchaseOrder\Entities\PurchaseorderDp;
use Modules\Inventory\Entities\CreateDocument;

use Auth;
use datatables;
use DB;


class GoodReceiveController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function grDp_index(Request $request)
    {
        $project = Project::find($request->session()->get('project_id'));
        $user = Auth::user();
        return view('goodreceive::grDp_index',compact('project','user'));
    }

    public function grDp_create(Request $request)
    {
        $project = Project::find($request->session()->get('project_id'));
        $user = Auth::user();

        $result_rekanans = [];
        $rekanans = Rekanan::select('id','name')->groupBy('id','name')->get();
        foreach ($rekanans as $key => $value) {
            # code...
            $arr = [
                'id'=>$value->id,
                'name'=>$value->name
            ];

            array_push($result_rekanans, $arr);
        }

      

        return view('goodreceive::grDp_create',compact('project','user','result_rekanans'));
    }

    public function grDp_getVendorListsPO(Request $request)
    {
        $vendor_id = $request->id;
        // return $vendor_id;
        $results = [];
        $getListsPo = PurchaseOrder::select('id','no','rekanan_id','description')->where('rekanan_id',$vendor_id)->get();
        // belum di filter po yang sudah di recipt

        foreach ($getListsPo as $key => $value) {
            # code...
            $total_po_value = PurchaseOrderDetail::where('purchaseorder_id',$value->id)->sum('quantity')*PurchaseOrderDetail::where('purchaseorder_id',$value->id)->sum('harga_satuan');
            $total_po = PurchaseOrderDetail::where('purchaseorder_id',$value->id)->get();
            $totalppn =0;
            $totalpph=0;
            $subtotal = 0;
            $total_disc = 0;
            foreach ($total_po as $key => $v) {
                $diskon = $v->discon/100*($v->harga_satuan*$v->quantity);
                $total_disc += $diskon;
                $sbtotal = $v->harga_satuan*$v->quantity;
                $subtotal += $sbtotal-$diskon;

                $totalppn += $v->ppn/100*($sbtotal-$diskon);
                $totalpph += $v->pph/100*($sbtotal-$diskon);
            }

            // return $total_disc;
            $grandtotal=($subtotal-$totalpph)+$totalppn;
            $total_percent_dp = PurchaseOrderDp::where('purchaseorder_id',$value->id)->sum('percentage');
            $total_dp_value = $grandtotal*$total_percent_dp;
            $selisih = $total_po_value - $total_dp_value;

            if($selisih > 0)
            {
                $arr = [
                    'id'=>$value->id,
                    'po_number'=>$value->no,
                    'rekanan_id'=>$value->rekanan_id,
                    'total_dp_value'=>$total_dp_value,
                    'description'=>$value->description
                ];

                array_push($results,$arr);
            }
            
        }

        return datatables()->of($results)->toJson();
    }

    public function grDp_getItemPO(Request $request)
    {
        $results = [];
        $id = $request->po_id;
        $getItems = PurchaseOrderDetail::where('purchaseorder_id',$id)->get();
        $total_po_value = PurchaseOrderDetail::where('purchaseorder_id',$id)->sum('quantity')*PurchaseOrderDetail::where('purchaseorder_id',$id)->sum('harga_satuan');
        $total_dp = PurchaseOrderDp::where('purchaseorder_id',$id)->sum('percentage')*100;
        $getDP_id = PurchaseOrderDp::select('id')->where('purchaseorder_id',$id)->get();
        $totalppn =0;
        $totalpph=0;
        $subtotal = 0;
        $total_disc = 0;
        foreach ($getItems as $key => $value) {
            $diskon = $value->discon/100*($value->harga_satuan*$value->quantity);
            $total_disc += $diskon;
            $sbtotal = $value->harga_satuan*$value->quantity;
            $subtotal += $sbtotal-$diskon;

            $totalppn += $value->ppn/100*($sbtotal-$diskon);
            $totalpph += $value->pph/100*($sbtotal-$diskon);
            # code...
            $arr = [
                'id'=>$value->id,
                'item_id'=>$value->item_id,
                'item_name'=>$value->item->item->name,
                'qty'=>$value->quantity,
                'satuan_name'=>$value->satuan->name,
                'satuan_id'=>$value->satuan_id,
                'price'=>$value->harga_satuan,
            ];

            array_push($results, $arr);
        }

        // return $subtotal;
        return response()->json(['items'=>$results,'total_po_value'=>$total_po_value,'total_dp'=>$total_dp,'dp_id'=>$getDP_id,'total_diskon'=>$total_disc,'subtotal'=>$subtotal,'totalppn'=>$totalppn,'totalpph'=>$totalpph,'grandtotal'=>($subtotal-$totalpph)+$totalppn]);
        //return datatables()->of($results)->toJson();

    }

    public function grDp_store(Request $request)
    {
        $stat = false;
        $project_id = $request->session()->get('project_id');
        $user_id = Auth::user()->id;
        $po_id = $request->po_id;
        $list_dp_id = json_decode($request->dp_id);
        $sumber_type = 'Modules\PurchaseOrder\Entities\PurchaseOrderDp';
        
        if(count($list_dp_id) > 0)
        {
            for($count=0;$count<count($list_dp_id);$count++)
            {
                $no = CreateDocument::createDocumentNumber('GR',2,$project_id,$user_id);
                // $dp = PurchaseorderDp::select('percentage')->where('id',$list_dp_id[$count]->id)->first();
               $execute = Goodreceive::create([
                    'sumber_id'=>$list_dp_id[$count]->id,
                    'purchaseorder_id'=>$po_id,
                    'sumber_type'=>$sumber_type,
                    'no'=>$no,
                    'date'=>date('Y-m-d'),
                    // 'is_downpayment'=>$dp->percentage
                  ]);
            }
                $stat = true;
            
        }
        
        return response()->json($stat);
    }

    public function grDp_getList(Request $request)
    {
        $results = [];
        $getLists = Goodreceive::all();

        foreach ($getLists as $key => $value) {
            # code...
            $arr = [
                'id'=>$value->id,
                'no'=>$value->no,
                
            ];
        }

    }


    public function gr_penerimaan_barang_index(Request $request)
    {
        $project = Project::find($request->session()->get('project_id'));
        // $project_id = $request->session()->get('project_id');
        $user = Auth::user();
        return view('goodreceive::gr_penerimaan_barang_index',compact('project','user'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function gr_penerimaan_barang_create(Request $request)
    {
        $project = Project::find($request->session()->get('project_id'));
        $user = Auth::user();

        $result_rekanans = [];
        $rekanans = Rekanan::select('id','name')->groupBy('id','name')->get();
        foreach ($rekanans as $key => $value) {
            # code...
            $arr = [
                'id'=>$value->id,
                'name'=>$value->name
            ];

            array_push($result_rekanans, $arr);
        }

        
        return view('goodreceive::gr_penerimaan_barang_create',compact('project','user','result_rekanans'));
    }

    public function gr_penerimaan_barang_getListsNomorPenerimaan(Request $request)
    {
        $rekanan_id = $request->id;
        $result = [];
        $temp_penerimaan = '';
        $getNomorPenerimaan = PenerimaanBarangPO::select('id as idpenerimaan','no','cte1.nopo')->join(DB::raw("((select pods.penerimaan_barang_po_id,cte.nopo from penerimaan_barang_po_details pods , (select pods.id as id_detail_po,po.no as nopo from purchaseorders po,purchaseorder_details pods where po.id = pods.purchaseorder_id and po.rekanan_id='".$rekanan_id."') as cte where pods.po_detail_id = cte.id_detail_po)) as cte1"),'penerimaan_barang_pos.id','=','cte1.penerimaan_barang_po_id')
        // ->join('approvals','approvals.document_id','penerimaan_barang_pos.id')
        // ->where('approvals.approval_action_id', 6)
        // ->where("approvals.document_type","Modules\PenerimaanBarangPO\Entities\PenerimaanBarangPO")
          ->groupBy('id','no','cte1.nopo')->orderBy('id')->get();
      //  \
          // return $getNomorPenerimaan;

        if(count($getNomorPenerimaan) != 0){
            foreach ($getNomorPenerimaan as $key => $value) {
                # code...
                $checkdetail_penerimaan = PenerimaanBarangPODetail::where('penerimaan_barang_po_id',$value->idpenerimaan)->get();
                
                foreach ($checkdetail_penerimaan as $key => $each) {
                    # code...
                    $check_qtygr = GoodreceiveDetail::where('penerimaan_barang_po_detail_id',$each->id);
                    if($check_qtygr != null)
                    {
                    $check_qtygr = $check_qtygr->sum('quantity');
                    $selisih = $each->quantity - $check_qtygr;
                    
                    $Approval = DB::table("approvals")->where("document_id",$value->idpenerimaan)
                                            ->where("document_type","Modules\PenerimaanBarangPO\Entities\PenerimaanBarangPO")
                                            ->where('approval_action_id', 6)
                                            ->first();
                    if($Approval != null){
                        if($selisih > 0 && $temp_penerimaan != $value->idpenerimaan)
                        {
                            $arr =[
                                'idpenerimaan'=>$value->idpenerimaan,
                                'no'=>$value->no,
                                'nopo'=>$value->nopo
                            ];
                            $temp_penerimaan = $value->idpenerimaan;
                            array_push($result, $arr);
                        }
                    }

                    }
                    
                }
            }
        }
        // return $result;
        return response()->json($result);
    }

    public function getListItemPenerimaan(Request $request)
    {
        $id_penerimaan = $request->penerimaanbarang_id;
        $results = [];
        // return $id_penerimaan;
        $nomor_po = PenerimaanBarangPODetail::select('po_detail_id')->where('penerimaan_barang_po_id',$id_penerimaan)->first();
        $POD = PurchaseOrderDetail::select('purchaseorder_id','pph','ppn','harga_satuan','quantity')->where('id',$nomor_po->po_detail_id)->get();
        $totalppn =0;
        $totalpph=0;
        $subtotal = 0;
        $total_disc = 0;
        foreach ($POD as $key => $v) {
            $diskon = $v->discon/100*($v->harga_satuan*$v->quantity);
            $total_disc += $diskon;
            $sbtotal = $v->harga_satuan*$v->quantity;
            $subtotal += $sbtotal-$diskon;

            $totalppn += $v->ppn/100*($sbtotal-$diskon);
            $totalpph += $v->pph/100*($sbtotal-$diskon);
            # code...
        }
        // return $totalppn;

        $PO = PurchaseOrder::select('id','no')->where('id',$POD[0]->purchaseorder_id)->get();
        // return $PO[0]->id;
        // $id_po=PenerimaanBarangPODetail::select('po_detail_id')->where('penerimaan_barang_po_id',$id_penerimaan)->first()->po_detail->po->id;
        // return $id_po;
        $getItems = PenerimaanBarangPODetail::select('id','po_detail_id','item_id','satuan_id',DB::raw('sum(quantity) as total_qty'))->where('penerimaan_barang_po_id',$id_penerimaan)->groupBy('id','item_id','satuan_id','po_detail_id')->get();
        // return $getItems;
        $total_dp = PurchaseOrderDp::where('purchaseorder_id',$PO[0]->id)->sum('percentage');
        foreach ($getItems as $key => $value) {
            $qty_gr_applied = GoodreceiveDetail::where('penerimaan_barang_po_detail_id',$value->id)->sum('quantity');
            $qty_sisa = $value->total_qty - $qty_gr_applied;
            $POD2 = PurchaseOrderDetail::select('harga_satuan','pph','ppn')->where('id',$value->po_detail_id)->get();
            // return $harga_satuan;
            if($qty_sisa > 0)
            {
                $arr = [
                    'kode_barang'=>$value->item->item->kode,
                    'id_penerimaan_detail'=>$value->id,
                    'item_id'=>$value->item_id,
                    'item_name'=>$value->item->item->name,
                    'satuan_id'=>$value->satuan_id,
                    'satuan_name'=>$value->satuan->name,
                    'total_qty'=>$qty_sisa,
                    'total_harga_satuan'=>$POD2[0]->harga_satuan,
                    'ppn'=>$POD2[0]->ppn,
                    'pph'=>$POD2[0]->pph
                ];
                array_push($results, $arr);
            }
            

            
        }
        // return $results;

        return response()->json(['items'=>$results,'nomor_po'=>$PO[0]->no,'id_po'=>$PO[0]->id,'nilai_dp'=>$total_dp,'penerimaanbarang_id'=>$id_penerimaan,'total_ppn'=>$totalppn,'total_pph'=>$totalpph]);
    }

    public function gr_penerimaan_barang_getData(Request $request)
    {
        $result = [];
        $getData = DB::table('goodreceives')->where('sumber_type','Modules\PenerimaanBarangPO\Entities\PenerimaanBarangPO')
                                            ->join('purchaseorders','purchaseorders.id','goodreceives.purchaseorder_id')
                                            ->join('rekanans','rekanans.id','purchaseorders.rekanan_id')
                                            ->select('goodreceives.id as id','goodreceives.no as no','purchaseorders.no as po_no','rekanans.name as rekanan_name','goodreceives.date as date')
                                            ->get();
        // return $getData;

        foreach ($getData as $key => $value) {
            # code...
            $arr = [
                'id'=>$value->id,
                'nomor_gr'=>$value->no,
                'nomor_po'=>$value->po_no,
                'vendor'=>$value->rekanan_name,
                'tanggal'=>date('d-m-Y',strtotime($value->date)),
                'status'=>'Kosong'
            ];

            array_push($result, $arr);
        }

        return datatables()->of($result)->toJson();
    }

    public function getData(Request $request)
    {
        $result = [];
        $getData = DB::table('goodreceives')->where('sumber_type','Modules\PurchaseOrder\Entities\PurchaseOrderDp')
                                            ->join('purchaseorders','purchaseorders.id','goodreceives.purchaseorder_id')
                                            ->join('rekanans','rekanans.id','purchaseorders.rekanan_id')
                                            ->join('purchaseorder_dps','purchaseorder_dps.id','goodreceives.sumber_id')
                                            ->select('goodreceives.id as id','goodreceives.no as no','purchaseorders.no as po_no','rekanans.name as rekanan_name','goodreceives.date as date','purchaseorders.id as po_id','purchaseorder_dps.percentage as percentage')
                                            ->get();
        $i = 0;
        foreach ($getData as $key => $value) {
          // $total_dp = PurchaseOrderDp::where('purchaseorder_id',$value->po_id)->sum('percentage')*100;

          $total_po_value = PurchaseOrderDetail::where('purchaseorder_id',$value->po_id)->sum('quantity')*PurchaseOrderDetail::where('purchaseorder_id',$value->po_id)->sum('harga_satuan');

          $total_po = PurchaseOrderDetail::where('purchaseorder_id',$value->po_id)->get();
          // return $total_po;
          $totalppn = 0;
          $totalpph = 0;
          $subtotal = 0;
          $total_disc = 0;
          foreach ($total_po as $key => $v) {
              $diskon = $v->discon/100*($v->harga_satuan*$v->quantity);
              $total_disc += $diskon;
              $sbtotal = $v->harga_satuan*$v->quantity;
              $subtotal += $sbtotal-$diskon;

              $totalppn += $v->ppn/100*($sbtotal-$diskon);
              $totalpph += $v->pph/100*($sbtotal-$diskon);
            }
            // return $total_disc;

            // return $total_disc;
            $grandtotal=($subtotal-$totalpph)+$totalppn;
            // return $grandtotal;
            $total_percent_dp = PurchaseOrderDp::where('purchaseorder_id',$value->po_id)->sum('percentage')*100;
            $total_dp_value = $grandtotal*$value->percentage;
            // return $total_dp_value;
          // return $total_dp;
            $i += 1;
            # code...
            $arr = [
                'no'=>$i,
                'id'=>$value->id,
                'nomor_gr'=>$value->no,
                'nomor_po'=>$value->po_no,
                'vendor'=>$value->rekanan_name,
                'tanggal'=>date('d-m-Y',strtotime($value->date)),
                'status'=>'Kosong',
                'total_dp'=> "Rp " . number_format($total_dp_value,2,',','.'),
                'total_po_value'=>$total_po_value
            ];

            array_push($result, $arr);
        }
        // return $result;

        return datatables()->of($result)->toJson();
    }

    public function gr_penerimaan_barang_store(Request $request)
    {
        $stat = false;
        $insert_detail ='';
        $project_id = $request->session()->get('project_id');
        $user_id = Auth::user()->id;
        date_default_timezone_set('asia/jakarta');
        $date = date('Y-m-d');
        $allitems = json_decode($request->alldatasend);
        $all_id_penerimaan = $request->id_penerimaan_barang;

        $all_id_penerimaan = explode(",", $all_id_penerimaan);
        $po_id = $request->id_po;
        $sumber_type = 'Modules\PenerimaanBarangPO\Entities\PenerimaanBarangPO';

        if(count($all_id_penerimaan) >0 && count($allitems) > 0)
        {
            for($counter = 0;$counter < count($all_id_penerimaan);$counter++)
            {
                $no = CreateDocument::createDocumentNumber('GR',2,$project_id,$user_id);

                $execute = Goodreceive::create([
                    'purchaseorder_id'=>$po_id,
                    'sumber_id'=>$all_id_penerimaan[$counter],
                    'sumber_type'=>$sumber_type,
                    'no'=>$no,
                    'date'=>$date]);

            
                if($execute)
                {
                    for($count=0;$count<count($allitems);$count++)
                    {
                        if($allitems[$count]->id_penerimaan == $all_id_penerimaan[$counter])
                        {
                            $insert_detail = GoodreceiveDetail::create([
                                'goodreceive_id'=>$execute->id,
                                'penerimaan_barang_po_detail_id'=>$allitems[$count]->id_penerimaan_detail,
                                'item_id'=>$allitems[$count]->item_id,
                                'satuan_id'=>$allitems[$count]->satuan_id,
                                'quantity'=>$allitems[$count]->total_qty,
                                'price'=>$allitems[$count]->harga_satuan
                            ]);

                            // return $insert_detail;
                        }
                        
                    }
                }
            }

            
            if($insert_detail)
            {
                $stat = true;
            }
        }
       
        return response()->json($stat);
    }

    /*public function getItemsPenerimaanBarangPO(Request $request)
    {
        $id = $request->id;
        $getPenerimaan = PenerimaanBarangPODetail::select('penerimaan_barang_po_id',
            'item_id',
            'satuan_id',
            'harga_satuan',
            DB::raw("sum(quantity) as total"))->groupBy('penerimaan_barang_po_id','item_id','satuan_id','harga_satuan')->where('penerimaan_barang_po_id',$id)->get();
        $getPoNumbers = PenerimaanBarangPODetail::select('po_detail_id')->groupBy('po_detail_id')->where('penerimaan_barang_po_id',$id)->get();
        $results = [];
        foreach ($getPenerimaan as $key => $value) {
            # code...
            $arr = [
                'id'=>$value->penerimaan_barang_po_id,
                'item_id'=>$value->item_id,
                'item_name'=>$value->item->item->name,
                'satuan_id'=>$value->satuan_id,
                'total'=>$value->total,
                'satuan_name'=>$value->satuan->name,
                'price'=>$value->harga_satuan
            ];

            array_push($results, $arr);
        }

        return datatables()->of($results)->toJson();
    }*/



    /*public function checkisPO_DP(Request $request)
    {
        $result_no_penerimaan_barang = [];
        $results = [];
        $po_id = $request->po_id;
        $check_is_dp = PurchaseOrderDp::where('purchaseorder_id',$po_id)->get();
        if(count($check_is_dp) > 0)
        {
            $getItems = PurchaseOrderDetail::where('purchaseorder_id',$po_id)->get();

            foreach ($getItems as $key => $value) {
                # code...
                $arr = [
                    'id'=>$value->id,
                    'item_id'=>$value->item_id,
                    'item_name'=>$value->item,
                    'total'=>$value->quantity,
                    'satuan_name'=>$value->satuan,
                    'satuan_id'=>$value->satuan_id,
                    'price'=>$value->harga_satuan
                ];
                select pbd.penerimaan_barang_po_id,pod.id from purchaseorder_details pod,penerimaan_barang_po_details pbd where purchaseorder_id = 11 and pbd.po_detail_id = pod.id group by 
                

                array_push($results, $arr);
            }
        }
        else
        {
            $str_query = "select pbp.no,pbp.id from  penerimaan_barang_pos pbp,(select pbd.penerimaan_barang_po_id,pod.id from purchaseorder_details pod,penerimaan_barang_po_details pbd where purchaseorder_id ='".$po_id."' and pbd.po_detail_id = pod.id group by pbd.penerimaan_barang_po_id,pod.id) as cte where 
pbp.id = cte.penerimaan_barang_po_id";
            $getPenerimaanBarangPO_No = DB::raw($str_query);
             array_push($result_no_penerimaan_barang, $getPenerimaanBarangPO_No);
            $getItemFromPenerimaanBarang = PurchaseOrderDetail::where('purchaseorder_id',$po_id)->get();
            foreach ($getItemFromPenerimaanBarang as $key => $value) {
                # code...
                foreach ($value->details_penerimaan as $key => $each) {
                    # code...
                    //print_r($each);
                    $arr = [
                        'id_penerimaanbarang_po'=>$each->penerimaan_barang_po_id,
                        'no_penerimaan_barang'=>is_null($each[0]->po) ? 'Kosong' : $each->po->no
                    ];

                   
                }
            }
        }

        return response()->json(['no_penerimaan_barang'=>$result_no_penerimaan_barang,'dataItem'=>$results]);
    }*/

   
    public function details(Request $request,$id)
    {
        $gr_data = Goodreceive::find($id);
        $user = Auth::user();
        $project = Project::find($request->session()->get('project_id'));

        return view('goodreceive::details',compact('gr_data','project','user'));
    }

    public function getDetailItems(Request $request,$id)
    {
        $result = [];
        $getdata = GoodreceiveDetail::where('goodreceive_id',$id)->get();
        // $getdata = DB::table('goodreceive_details')->where('goodreceive_id',$id)->get();
        // return $getdata;

        foreach ($getdata as $key => $value) {
            # code...
            $arr = [
                'id'=>$value->id,
                'item_name'=>$value->item->item->name,
                'satuan_name'=>$value->satuan->name,
                'kode'=>$value->item->item->kode,
                'qty'=>$value->quantity,
                'price'=>$value->price,
                'total'=>$value->price*$value->quantity
            ];

            array_push($result, $arr);
        }

        return datatables()->of($result)->toJson();
    }



}
