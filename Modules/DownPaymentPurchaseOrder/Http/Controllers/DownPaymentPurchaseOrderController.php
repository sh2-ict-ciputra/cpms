<?php

namespace Modules\DownPaymentPurchaseOrder\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Project\Entities\Project;
use Modules\DownPaymentPurchaseOrder\Entities\PurchaseorderDp;
use Modules\Rekanan\Entities\Rekanan;
use Modules\TenderPurchaseRequest\Entities\PurchaseOrderDetail;
use Modules\TenderPurchaseRequest\Entities\PurchaseOrder;
use Auth;
use datatables;
use DB;

class DownPaymentPurchaseOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $project = Project::find($request->session()->get('project_id'));
        $user = Auth::user();
        return view('downpaymentpurchaseorder::index',compact('user','project'));
    }

    public function getData()
    {
        $getAll = PurchaseorderDp::select('purchaseorder_id',DB::raw('sum(percentage) as total_dp'))->groupBy('purchaseorder_id')->get();
        $result = [];
        foreach ($getAll as $key => $value) {
            # code...
            $sbtotal = 0;
            $total_ppn = 0;
            $total_pph = 0;
            $details_po = PurchaseOrderDetail::where('purchaseorder_id',$value->purchaseorder_id)->get();
            foreach ($details_po as $key => $v) {
                # code...
                $subtotal = $v->quantity*$v->harga_satuan;
                $disc = $v->discon/100*$subtotal;

                $sb_total_disc = $subtotal - $disc;
                $sbtotal += $sb_total_disc;
                $ppn = $v->ppn/100*$sb_total_disc;
                $total_ppn += $ppn;

                $pph = $v->pph/100*$sb_total_disc;
                $total_pph += $pph;
            }
            $total_po = ($sbtotal-$total_pph)+$total_ppn;

            $nilai_dp = $total_po*$value->total_dp;
            $saldo = $total_po-$nilai_dp;
            $arr = [
                'id_po'=>$value->purchaseorder_id,
                'vendor'=>$value->purchaseorder->vendor->name,
                'po_number'=>$value->purchaseorder->no,
                'total_dp'=>$value->total_dp,
                'percentage_dp'=>$value->total_dp*100,
                'total_po'=>$total_po,
                'saldo'=>$saldo
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
        $project = Project::find($request->session()->get('project_id'));
        $user = Auth::user();
        
        // $result_rekanans = Rekanan::select('id','name')->get();
        $result_rekanans = PurchaseOrder::get();
        return view('downpaymentpurchaseorder::create',compact('user','project','result_rekanans'));
    }

    public function getVendorListsPO(Request $request)
    {
        $vendor_id = $request->id;
        $po = PurchaseOrder::select('purchaseorders.id','purchaseorders.no','purchaseorders.description')
        ->join('approvals','purchaseorders.id','approvals.document_id')
        ->where('approvals.document_type','Modules\TenderPurchaseRequest\Entities\PurchaseOrder')
        ->where('approvals.approval_action_id',6)
        ->where('rekanan_id',$vendor_id)
        ->get();

        $result = [];
        
        foreach ($po as $key => $value) {
            # code...
            $sbtotal = 0;
            $total_ppn = 0;
            $total_pph = 0;
            $details_po = PurchaseOrderDetail::where('purchaseorder_id',$value->id)->get();
            foreach ($details_po as $key => $v) {
                # code...
                $subtotal = $v->quantity*$v->harga_satuan;
                $disc = $v->discon/100*$subtotal;

                $sb_total_disc = $subtotal - $disc;
                $sbtotal += $sb_total_disc;
                $ppn = $v->ppn/100*$sb_total_disc;
                $total_ppn += $ppn;

                $pph = $v->pph/100*$sb_total_disc;
                $total_pph += $pph;
            }
            $total_po = ($sbtotal-$total_pph)+$total_ppn;

            $total_percentage_dp = PurchaseorderDp::where('purchaseorder_id',$value->id)->sum('percentage');

            $nilai_dp = $total_po*$total_percentage_dp;
            $saldo = $total_po-$nilai_dp;

            
            $arr = [
                'id'=>$value->id,
                'po_number'=>$value->no,
                'total_po'=>$total_po,
                'total_dp'=>$nilai_dp,
                'description'=>$value->description
            ];

            array_push($result, $arr);
        }

        return json_encode(['data'=>$result]);
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $stat = false;
        $po_id = $request->po_id;
        $percentage_dp = $request->percentage_dp;
        $description = $request->description;
        $execute = PurchaseorderDp::create(['purchaseorder_id'=>$po_id,'date'=>date('Y-m-d'),'percentage'=>$percentage_dp/100,'description'=>$description]);

        if($execute)
        {
            $stat = true;
        }

        return response()->json($stat);
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function detail(Request $request,$id)
    {
        $project = Project::find($request->session()->get('project_id'));
        $user = Auth::user();
        $po_info = PurchaseOrder::find($id);
        return view('downpaymentpurchaseorder::detail',compact('user','project','po_info'));
    }

    public function getDataDetail($id)
    {
        $result = [];
        $detail_dp = PurchaseorderDp::where('purchaseorder_id',$id)->get();
        // $total_po = PurchaseOrderDetail::where('purchaseorder_id',$id)->sum('quantity')*PurchaseOrderDetail::where('purchaseorder_id',$id)->sum('harga_satuan');
        $total_po = PurchaseOrderDetail::where('purchaseorder_id',$id)->get();
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
          $grandtotal=($subtotal-$totalpph)+$totalppn;

          foreach ($detail_dp as $key => $value) {
              # code...
              $dp_value = $grandtotal*$value->percentage;
              $arr = [
                  'id'=>$value->id,
                  'no'=>$key+1,
                  'dp_percentage'=>$value->percentage*100,
                  'dp_value'=>$dp_value,
                  'description'=>$value->description
              ];

              array_push($result, $arr);
          }

        return datatables()->of($result)->toJson();

    }

    public function update(Request $request)
    {
        $stat =0;
        $name = $request->name;
        $pk = $request->pk;
        $value = $request->value;
        if($name=='percentage')
        {
            $value = $value/100;
        }
        $updated = PurchaseorderDp::find($pk)->update([$name=>$value]);
        if($updated)
        {
            $stat = 1;
        }

        return response()->json(['return'=>$stat]);
        
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy()
    {
    }


}
