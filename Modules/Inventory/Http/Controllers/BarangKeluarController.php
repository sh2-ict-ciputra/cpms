<?php

namespace Modules\Inventory\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Modules\Inventory\Entities\Permintaanbarang;
use Modules\Inventory\Entities\PermintaanbarangDetail;
use Modules\Inventory\Entities\Barangkeluar;
use Modules\Inventory\Entities\BarangkeluarDetail;
use Modules\Inventory\Entities\Warehouse;
use Modules\Inventory\Entities\Inventory;
use Modules\Inventory\Entities\Item;
use Modules\Inventory\Entities\ItemSatuan;
use Modules\Inventory\Entities\Barangmasuk;
use Modules\Inventory\Entities\Approval;
use Modules\Project\Entities\Project;
use Modules\Inventory\Entities\CreateDocument;
use datatables;
use PDF;
use DB;
use Auth;

class BarangKeluarController extends Controller
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
        $list_barang_keluars = [];
        $user = Auth::user();
        $status_sent = [];
        $is_sent = false;
        $project = Project::find($request->session()->get('project_id'));
        $permintaanbarang_id = $request->id;
        $permintaan = null;
        if($request->id != null){
            $permintaan      = Permintaanbarang::find($permintaanbarang_id);
            $barang_keluars  = Barangkeluar::where('permintaanbarang_id',$permintaanbarang_id)->get();
            
            foreach ($barang_keluars as $key => $value) {
                # code...
                if($value->barangkeluardetails->count() > 0){
                    foreach ($value->barangkeluardetails as $key => $each) {
                        # code...
                        if($each->is_sent)
                        {
                            array_push($status_sent, $value->id);
                        }
                    }
                }

                if(in_array($value->id, $status_sent)){
                    $is_sent = true;
                }
                
                $push_barang_keluar = array(
                    'id' => $value->id,
                    'permintaanbarang_id' => $value->permintaanbarang_id,
                    'no' => $value->no,
                    'confirmed_by_warehouseman'=>$value->confirmed_by_warehouseman,
                    'status_barang'=>$value->permintaanbarang->status_persetujuan,
                    'date' => $value->date,
                    'status_sent'=>$is_sent,
                    'detail_count' => $value->barangkeluardetails->count(),
                    'inventarisir_count' => $value->inventarisirs->count()
                );

                array_push($list_barang_keluars, $push_barang_keluar);
            }

        }
        else{
            $barang_keluars = Barangkeluar::all();
            foreach ($barang_keluars as $key => $value){
                # code...
                if($value->barangkeluardetails->count() > 0)
                {
                    foreach ($value->barangkeluardetails as $key => $each) {
                        # code...
                        if($each->is_sent)
                        {
                            array_push($status_sent, $value->id);
                        }
                    }
                }

                if(in_array($value->id, $status_sent))
                {
                    $is_sent = true;
                }
                
                $push_barang_keluar = array(
                    'id' => $value->id,
                    'permintaanbarang_id' => $value->permintaanbarang->id,
                    'no' => $value->no,
                    'confirmed_by_warehouseman'=>$value->confirmed_by_warehouseman,
                    'status_barang'=>$value->permintaanbarang->status_persetujuan,
                    'date' => $value->date,
                    'status_sent'=>$is_sent,
                    'detail_count' => $value->barangkeluardetails->count(),
                    'inventarisir_count' => $value->inventarisirs->count()
                );

                array_push($list_barang_keluars, $push_barang_keluar);
            }
            //return redirect('/permintaan_barang/index');
        }

        $json_barang_keluars = json_encode($list_barang_keluars);
        return view('inventory::barang_keluar.index',compact('project', 'permintaan','json_barang_keluars','user'));
    }
    
    public function add(Request $request)
    {   
        $user = Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        if($project == null){
            return redirect("/");
        }
        $results = [];

        $permintaanbarang_id = $request->id;
        $permintaan   = Permintaanbarang::find($permintaanbarang_id);

        $permintaanresults = array($permintaanbarang_id,$permintaan->no);
        $gudang = Warehouse::where("project_id", $project->id)->get();
        return view('inventory::barang_keluar.add_form', compact('permintaanresults','project','user','permintaan','gudang'));
    }

    public function getDataBarangKeluar($id)
    {
        $data = [];
        $results = ItemSatuan::select('item_satuans.name as satuan_name','item_satuans.item_id','cte.*',
            'warehouses.name as gudang')
        ->join(DB::raw("((
            select pds.id as permintaan_details_id
            ,pds.permintaanbarang_id
            ,pds.item_id as itemid,
        pds.item_satuan_id,
(select konversi from item_satuans where id = pds.item_satuan_id and item_satuans.deleted_at is null) as nilai_konversi_permintaan,
            inv.warehouse_id,
            (pds.quantity) - (select COALESCE(sum(pds.quantity),0) from barangkeluar_details bs where bs.permintaanbarang_detail_id = pds.id and bs.deleted_at is null) as total_minta,
            (select konversi from item_satuans where id = inv.item_satuan_id) as nilai_konversi_stock,
            sum(inv.quantity) as total_stock,
            ((sum(inv.quantity))*(select konversi from item_satuans where id = inv.item_satuan_id))-(select COALESCE(sum(pds.quantity),0) 
from barangkeluar_details bs where bs.permintaanbarang_detail_id = pds.id and bs.deleted_at is null and bs.warehouse_id=inv.warehouse_id) as total_stock_after_konversi 
            from permintaanbarang_details pds 
            inner join inventories inv on pds.item_id = inv.item_id where inv.deleted_at is null and pds.deleted_at  is null group by pds.id,pds.permintaanbarang_id,pds.item_id,pds.item_satuan_id,inv.warehouse_id,pds.quantity,inv.item_satuan_id)) as cte"),
        'item_satuans.id','=','cte.item_satuan_id')
        ->join('warehouses','warehouses.id','=','cte.warehouse_id')
        ->where([['cte.permintaanbarang_id','=',$id],['cte.total_minta','>',0],['cte.total_stock_after_konversi','>',0]])->get();

        $stock = DB::table("permintaanbarang_details")
                    ->join("permintaanbarangs", "permintaanbarangs.id","permintaanbarang_details.permintaanbarang_id")
                    ->join("item_satuans", "item_satuans.id", "permintaanbarang_details.item_satuan_id")
                    ->join("item_projects", "item_projects.id", "permintaanbarang_details.item_id")
                    ->join("items", "items.id", "item_projects.item_id")

                    ->where("permintaanbarangs.id", $id)
                    ->select("permintaanbarang_details.item_id as item_id",DB::raw("SUM(permintaanbarang_details.quantity) as sum"), "item_satuans.name as satuan","items.name as item_name","item_satuans.id as item_satuan_id")
                    ->groupBy("permintaanbarang_details.item_id","item_satuans.name","items.name","item_satuans.id")
                    ->get();
        
        foreach ($results as $key => $value) {
            # code...
            $arr = array ('gudang'=>$value->gudang,
            'permintaan_details_id'=>$value->permintaan_details_id,
            'satuan_name'=>$value->satuan_name,
            'item_satuan_id'=>$value->item_satuan_id,
            'item_id'=>$value->itemid,
            'item_name'=>$value->item->name,
            'warehouse_id'=>$value->warehouse_id,
            'total_minta'=>$value->total_minta,
            'total_stock_after_konversi'=>number_format((float)$value->total_stock_after_konversi/$value->nilai_konversi_permintaan, 2, '.', ''));

            array_push($data,$arr);
        }

        return datatables()->of($data)->toJson();
    }
    
    public function addPost(Request $request)
    {
        // return $request;
        $stat = false;
        $permintaanbarang_id = $request->permintaanbarang_id;
        $department_id =Permintaanbarang::find($permintaanbarang_id)->department_id;
        $description = $request->description;
        $date = $request->date;
        // $execute = Barangkeluar::create([
        //         'permintaanbarang_id' => $permintaanbarang_id,
        //         'confirmed_by_warehouseman' => false,
        //         'confirmed_by_requester' => true,
        //         'description' => $description,
        //         'date' => $date,
        //         'no' => CreateDocument::createDocumentNumber('BK',$department_id,$request->session()->get('project_id'),Auth::user()->id)
        //     ]);

        $permintaan_barang = Permintaanbarang::find($permintaanbarang_id);

        $barang_keluar                              = new BarangKeluar;
        $barang_keluar->permintaanbarang_id         = $permintaanbarang_id;
        $barang_keluar->confirmed_by_warehouseman   = false;
        $barang_keluar->confirmed_by_requester      = true;
        $barang_keluar->description                 = $description;
        $barang_keluar->date                        = $date;
        $barang_keluar->no                          = CreateDocument::createDocumentNumber('BK',$department_id,$request->session()->get('project_id'),Auth::user()->id);
        $barang_keluar->status_permintaan_id        =$permintaan_barang->status_permintaan_id;
        $execute = $barang_keluar->save();
        
        for ($i=0; $i < count($request->pds); $i++) { 
            # code...
            $barang_keluar_detail                               = new BarangkeluarDetail;
            $barang_keluar_detail->barangkeluar_id              = $barang_keluar->id;
            $barang_keluar_detail->permintaanbarang_detail_id   = $request->pds[$i];
            $barang_keluar_detail->item_id                      = $request->item[$i];
            $barang_keluar_detail->warehouse_id                 = $request->gudang[$i];
            $barang_keluar_detail->inventory_id                 = null;
            $barang_keluar_detail->item_satuan_id               = $request->satuan[$i];
            $barang_keluar_detail->quantity                     = $request->quantity[$i];
            $barang_keluar_detail->price                        = $request->nilai[$i];
            $barang_keluar_detail->ppn                          = null;
            $barang_keluar_detail->is_sent                      = null;
            $barang_keluar_detail->save();
            return  $barang_keluar_detail;
        }

        if($execute)
        {
            $stat = true;
        }

        return response()->json(['stat'=>$stat,'id'=>$barang_keluar->id]);
    }

    public function edit(Request $request)
    {
        $barang_keluar  = Barangkeluar::find($request->id);
        $permintaan    = Permintaanbarang::find($barang_keluar->permintaanbarang_id);

        return view('barang_keluar.edit_form',compact('barang_keluar', 'permintaan'));
    }

    public function update(Request $request)
    {
        $stat =0;
        $name = $request->name;
        $pk = $request->pk;
        $value = $request->value;
        $updated = Barangkeluar::find($pk)->update([$name=>$value]);
        if($updated)
        {
            $stat = 1;
        }
        return response()->json(['return'=>$stat]);
    }

    public function delete(Request $request)
    {
        $barang_keluars                             = Barangkeluar::find($request->id);
        $status                                     = $barang_keluars->delete();
        $stat = false;
        if ($status) 
        {
            $stat = true;
        }

        return response()->json($stat);
    }

    public function print(Request $request)
    {
        $id = $request->barang_keluar_id;
        $barangkeluar = Barangkeluar::find($id);
        $barangkeluardetails = BarangkeluarDetail::select('item_id','warehouse_id','item_satuan_id',DB::raw('sum(quantity) as quantity'))
        ->where('barangkeluar_id',$id)
        ->groupBy('item_id','warehouse_id','item_satuan_id')->get();
        //return view('barang_keluar.print',compact('barangkeluar'));     
        $pdf = PDF::loadView('inventory::barang_keluar.print',compact('barangkeluar','barangkeluardetails'))->setPaper('a4','potrait');
        return $pdf->stream('laporan_barang_keluar.pdf');
    }

   

    public function approve(Request $request)
    {
        $stat = false;
        $inventory ='';
        $id = $request->id;
        $permintaanbarang_id = $request->permintaan_barang_id;

        $approval = Approval::where('document_id',$id)->update(['approval_action_id'=>6]);
        if($approval)
        {
            $barangkeluar = Barangkeluar::find($id);
            foreach ($barangkeluar->barangkeluardetails as $key => $value) {
                # code...
                $inventory = Inventory::create([
                                'item_id' => $value->item_id,
                                //'rekanan_id' => null,
                                'warehouse_id' => $value->warehouse_id,
                                'sumber_id' => $value->id,
                                'sumber_type' => 'App\BarangkeluarDetail',
                                'date' => now(),
                                'quantity' =>(int)0-($value->quantity),
                                //'quantity_terpakai' => null,
                                'price' => ($value->price*$value->quantity),
                                'ppn' => 0
                            ]);
            }
            if($inventory)
            {
                $stat = true;

            }
            
        }

        return response()->json(['stat'=>$stat,'id'=>$permintaanbarang_id]);
    }

    public function checkQty(Request $request)
    {
        $warehouse_id = $request->warehouse_id;
        $item_id = $request->item_id;

        $checkQtyItem = Inventory::where([['warehouse_id','=',$warehouse_id],['item_id','=',$item_id]])->sum('quantity');

        return response()->json($checkQtyItem);
    }

    public function printReport(Request $request)
    {
        $start_date = $request->start_opname;
        $end_date = $request->end_opname;
        $barang_keluar = Barangkeluar::whereBetween('created_at',[$start_date,$end_date])->get();
        $arrhasil = [];
        foreach ($barang_keluar as $key => $value) {
            # code...
            $details = BarangkeluarDetail::select('item_id','item_satuan_id','warehouse_id',DB::raw("sum(quantity) as total"))->where('barangkeluar_id',$value->id)->groupBy('item_id','item_satuan_id','warehouse_id')->get();
            $arrdetail = [];
            foreach ($details as $key => $each) {
                # code...
                $arr = [
                    'item_name'=>$each->item->item->name,
                    'satuan_name'=>$each->satuan->name,
                    'warehouse_name'=>$each->warehouse->name,
                    'quantity'=>$each->total
                ];

                array_push($arrdetail, $arr);
            }

            $arrpermintaan = [
                'department'=>$value->permintaanbarang->department->name,
                'pt'=>$value->permintaanbarang->pt->name,
                'spk'=>is_null($value->permintaanbarang) ? '': $value->permintaanbarang->spk->no,
                'no'=>$value->no,
                'status'=>$value->permintaanbarang->StatusPermintaan->name,
                'detail'=>$arrdetail
            ];

            array_push($arrhasil,$arrpermintaan);
        }

        $pdf = PDF::loadView('inventory::barang_keluar.printReport',compact('arrhasil','request'))->setPaper('a4','potrait');
        return $pdf->stream('laporan_permintaanbarang.pdf');
    }
}
