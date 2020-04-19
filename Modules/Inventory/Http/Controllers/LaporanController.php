<?php

namespace Modules\Inventory\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Modules\Inventory\Entities\Inventory;
use Modules\Inventory\Entities\BarangkeluarDetail;
use Modules\Inventory\Entities\ItemSatuan;
use Modules\Project\Entities\Project;
use datatables;
use DB;
use PDF;
use Auth;

class LaporanController extends Controller
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
        $project = Project::find($request->session()->get('project_id'));
        return view('inventory::laporan.index',compact('user','project'));
    }

    public function getdaftarbarang()
    {
        $result = []; // (ambil)
        $getLists = Inventory::all();
        
        foreach ($getLists as $key => $value) {
            # code...
          
           $arr = array(
            
             'id'=>$value->id,
             'kode_barang'=>$value->id,
             'nama_barang'=> $value->item->item->name,
             'qty_min'=>is_null($value->quantity)?'tidak' : $value->quantity,
             'satuan_min'=>$value->item{"satuans"}[0]{"name"},
             'satuan_besar'=>is_null($value->satuan{"name"})?'tidak' :$value->satuan{"name"},
             'konvensi'=>is_null($value->satuan{"konversi"})?'tidak' :$value->satuan{"konversi"},
             'stok_min'=>$value->item{"stock_min"},
             'flag'=>'tidak ada',
        );
            array_push($result, $arr);
        }
        // echo "<pre>";
        // print_r(datatables()->of($result)->toJson());
        // echo "</pre>";
        return datatables()->of($result)->toJson(); 
    }

    public function printMin(Request $request)
    {
        $BarangkeluarDetail = DB::select('select item_satuans.item_id, items.name as nama_barang, inventories.quantity as qty_Aktual ,items.stock_min as stock_min ,item_satuans.name as nama_satuan, item_satuans.konversi as Satuan_terkecil ,COUNT(item_satuans.item_id) as Jumlah_barang ,item_satuans.konversi as banyak FROM ((item_satuans INNER JOIN items ON item_satuans.item_id = items.id) INNER JOIN inventories ON inventories.item_satuan_id = item_satuans.id)GROUP BY item_satuans.item_id ');

        $pdf = PDF::loadView('inventory::laporan.cetak',compact('BarangkeluarDetail'))->setPaper('a4','landscape');
        return $pdf->stream('StokMinimum.pdf');

    }

    //pemakaian

    public function indexpemakaian(Request $request)
    {
        $project = Project::find($request->session()->get('project_id'));
        $user = Auth::user();
       return view('inventory::laporan.indexpemakaian',compact('project','user'));
    }

    public function getdaftarbarangpemakaian()
    {
        $result = []; 
        $pemakaian = BarangkeluarDetail::select('permintaanbarang_detail_id','item_id','warehouse_id','item_satuan_id',DB::raw('sum(quantity) as total_keluar'))->groupBy('permintaanbarang_detail_id','item_id','warehouse_id','item_satuan_id')->get();
        foreach ($pemakaian as $key => $value) {
            # code...
            $arr = array(
                'nama_barang'=>$value->item->item->name,
                'sumber'=>$value->warehouse->name,
                'satuan'=>$value->satuan->name,
                'ditujukan'=>$value->permintaanbarang_detail->Permintaanbarang->department->name,
                'peruntukan'=>$value->permintaanbarang_detail->Permintaanbarang->StatusPermintaan->name,
                'deskripsi'=>$value->permintaanbarang_detail->Permintaanbarang->description,
                'total'=>$value->total_keluar
            );

            array_push($result, $arr);
        }

        return datatables()->of($result)->toJson();
    }

    public function printMinpemakaian(Request $request)
    {
        $project = Project::find($request->session()->get('project_id'));
        $result = [];
        $start_date = $request->start_opname;
        $end_date = $request->end_opname;
        $pemakaian = BarangkeluarDetail::select('permintaanbarang_detail_id','item_id','warehouse_id','item_satuan_id',DB::raw('sum(quantity) as total_keluar'))->groupBy('permintaanbarang_detail_id','item_id','warehouse_id','item_satuan_id')->whereBetween('created_at',[$start_date,$end_date])->get();
        //return $pemakaian;
        foreach ($pemakaian as $key => $value) {
            # code...
            $arr = array(
                'peruntukan'=>$value->permintaanbarang_detail->Permintaanbarang->StatusPermintaan->name,
                'nama_barang'=>$value->item->item->name,
                'sumber'=>$value->warehouse->name,
                'satuan'=>$value->satuan->name,
                'ditujukan'=>$value->permintaanbarang_detail->Permintaanbarang->department->name,
                'deskripsi'=>$value->permintaanbarang_detail->Permintaanbarang->description,
                'total'=>$value->total_keluar
            );

            array_push($result, $arr);
        }
         //membuat pdfx
        $pdf = PDF::loadView('inventory::laporan.cetakpemakaian',compact('result','project','request'))->setPaper('a4','portrait');
        return $pdf->stream('PemakaianBarang.pdf');

        //return view('laporan.cetakpemakaian',compact('result'));
    }

    public function indexpersediaan(Request $request)
    {
    	$project = Project::find($request->session()->get('project_id'));
        $user = Auth::user();
        return view('inventory::laporan.indexpersediaan',compact('project','user'));
    }

    public function getposisi()
    {
         $result = [];
            $getLists = DB::select('select hasil.*,items.name as nama_barang,
   (select isn2.name from item_satuans as isn2 where isn2.konversi = (select min(konversi) from item_satuans as isn3 where isn3.item_id = isn2.item_id) and isn2.item_id = items.id ) as nama_satuan_terkecil from (select w.name as nama_gudang,
    sum(inv.quantity) as total_stock,
itm.item_id,
    isn.name as satuan_name,
    isn.konversi as nilai_konversi,
    sum(inv.quantity)*isn.konversi as qty_konversi
    from inventories inv,item_projects itm,item_satuans isn,warehouses w where inv.item_id = itm.id and inv.item_satuan_id = isn.id and inv.warehouse_id = w.id 
group by w.name,itm.item_id,isn.name,isn.konversi,inv.item_id) as hasil, items where hasil.item_id = items.id');
             
          foreach ($getLists as $key => $value) {
            # code...
                $arr = array(

                'gudang' => $value->nama_gudang,
                'item' => $value->nama_barang,
                'qty' => $value->total_stock,
                'satuan' => $value->satuan_name,
                'n_konvensi' => $value->nilai_konversi,
                'q_konvensi' => $value->qty_konversi,
                'n_satuan_k' =>$value->nama_satuan_terkecil
           
        );
            array_push($result, $arr);
        }

        return datatables()->of($result)->toJson(); 
    }

    public function cetak(Request $request)
    {
         $start_date = $request->start_opname;
        $end_date = $request->end_opname;
        $project = Project::find($request->session()->get('project_id'));
        $str_query = 'select items.name as nama_barang,(select isn2.name from item_satuans as isn2 where isn2.konversi = (select min(konversi) from item_satuans as isn3 where isn3.item_id = isn2.item_id) and isn2.item_id = hasil.item_id ) as nama_satuan_terkecil,
hasil.* from (select w.name as nama_gudang,
    sum(inv.quantity) as total_stock,
    itm.item_id,
inv.item_id as itemid,
    isn.name as satuan_name,
    isn.konversi as nilai_konversi,
    sum(inv.quantity)*isn.konversi as qty_konversi
    from inventories inv,item_projects itm,item_satuans isn,warehouses w where inv.item_id = itm.id and inv.item_satuan_id = isn.id and inv.warehouse_id = w.id and inv.created_at between "'.$start_date.'" and "'.$end_date.'"
group by w.name,itm.item_id,isn.name,isn.konversi,inv.item_id) as hasil ,items where hasil.item_id = items.id order by hasil.nama_gudang';
        $cetak = DB::select($str_query);

        $pdf = PDF::loadView('inventory::laporan.cetakpersedian',compact('cetak','project','request'))->setPaper('a4','portrait');
        return $pdf->stream('cetak.pdf');
    }
}
