<?php

namespace Modules\PemenangTender\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Project\Entities\Project;
use Modules\Tender\Entities\TenderMenang;
use Modules\Inventory\Entities\CreateDocument;
use Modules\TenderPurchaseRequest\Entities\TenderPurchaseRequestPenawaran;
use Modules\TenderPurchaseRequest\Entities\TenderPurchaseRequestPenawaranDetail;
use Modules\TenderPurchaseRequest\Entities\TenderMenangPR;
use Auth;
use datatables;

class PemenangTenderController extends Controller
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
        $user = Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        return view('pemenangtender::index',compact('user','project'));
    }


    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function detail(Request $request,$id)
    {
        $user = Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        $tender_menang = TenderMenangPR::find($id);
        // return $tender_menang;
        // if($tender_menang->penawaran->id_metode_pembayaran == 1){
        //     $cara_pembayaran = TenderPurchaseRequestPenawaranPembayaranCoD::where('tender_purchase_request_penawaran_id',$tender_menang->penawaran->id)->get();
        // }elseif($tender_menang->penawaran->id_metode_pembayaran == 2){
        //     $cara_pembayaran = TenderPurchaseRequestPenawaranPembayaranTermin::where('tender_purchase_request_penawaran_id',$tender_menang->penawaran->id)->get();
        // }
        // return $cara_pembayaran;
        
        return view('pemenangtender::detail',compact('user','project','tender_menang'));
    }

    public function detail_data(Request $request,$id)
    {
        $tender_menang = TenderMenangPR::find($id);

        $details = TenderPurchaseRequestPenawaranDetail::where('tender_penawaran_id',$tender_menang->id_penawaran)->get();
        $result = [];
        foreach ($details as $key => $value) {
            # code...
            $arr = [
                'item_name'=>$value->item->name,
                'brand_name'=>$value->brand->name,
                'satuan_name'=>is_null($value->satuan) ? 'kosong' : $value->satuan->name,
                'qty'=>$value->volume,
                'price'=>$value->nilai,
                'total'=>$value->volume*$value->nilai
            ];

            array_push($result,$arr);
        }

        return datatables()->of($result)->toJson();
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show(Request $request)
    {
        $project_id = $request->session()->get('project_id');
        $tender_all = TenderMenangPR::where('project_for_id',$project_id)->get();
        $result = [];
        foreach ($tender_all as $key => $value) {
            # code...
            $total = 0;
            $details = TenderPurchaseRequestPenawaranDetail::where('tender_penawaran_id',$value->id_penawaran)->get();
            foreach ($details as $key => $v) {
                $ppn = $v->penawaran->tender_purchase_request_group_rekanan_detail->rekanan->ppn;
                $sbtotal = $v->nilai*$v->volume;
                $total += $sbtotal+($sbtotal*$ppn/100);
            }
            $arr = [
                'id'=>$value->id,
                'rekanan_name'=>$value->tender_purchase_request_group_rekanan_detail->rekanan->name,
                'tender_no'=>$value->tender->no,
                'penawaran_no'=>$value->penawaran->no,
                'total'=>number_format($total,2,",",".")
            ];

            array_push($result,$arr);
        }

        return datatables()->of($result)->toJson();
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit()
    {
        return view('pemenangtender::edit');
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
}
