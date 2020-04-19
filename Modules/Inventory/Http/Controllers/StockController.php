<?php

namespace Modules\Inventory\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Modules\Inventory\Entities\Inventory;
use Modules\Inventory\Entities\PermintaanbarangDetail;
use Modules\Inventory\Entities\Item;
use Modules\Inventory\Entities\ItemProject;
use Modules\Inventory\Entities\ItemSatuan;
use Modules\Project\Entities\Project;
use Modules\TenderPurchaseRequest\Entities\PurchaseOrder;
use datatables;
use DB;
use PDF;
use Auth;

class StockController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function getStocks(Request $request)
    {
        $results = [];
        $project = Project::find($request->session()->get('project_id'));
        $user = \Auth::user();
//         $stocks = ItemSatuan::select('item_satuans.name as satuan_name','item_satuans.item_id','results.*')
//         ->join(DB::raw("((select hasil.*,items.name as item_name,item_projects.project_id,
//     (select stn.id from item_satuans stn where stn.konversi = (select min(st.konversi) from item_satuans st where st.item_id = stn.item_id and st.deleted_at is null) and stn.item_id = hasil.id_item and stn.deleted_at is null) as id_satuan_afterkonversi 

// from (select tbl1.*,tbl1.stock_afterkonversi - tbl2.sisabookingafterkonversi as stock_avaible from (select cte.itemid,cte.id_item,sum(cte.stock_afterkonversi) as stock_afterkonversi from 
// (select inv.item_id as itemid,itms.item_id as id_item,sum(inv.quantity) * itns.konversi as stock_afterkonversi from inventories inv  
//             inner join item_satuans itns on inv.item_satuan_id = itns.id 
//             inner join item_projects itms on inv.item_id = itms.id where inv.deleted_at is null and itms.deleted_at is null and itns.deleted_at is null group by inv.item_id,itms.item_id,itns.konversi) as cte 
// group by cte.id_item,cte.itemid) as tbl1 
// left join (select cte1.id_item_project, sum(cte1.sisabooking*itsn.konversi) as sisabookingafterkonversi from (select cte.id_item_project,
// cte.id_satuan,
// coalesce(sum(bd.quantity),0) as totalkeluar,
// cte.totalminta - coalesce(sum(bd.quantity),0) as sisabooking 
// from (select pd.item_id as id_item_project,
// pd.item_satuan_id as id_satuan,
// sum(pd.quantity) as totalminta,
// sum(pd.quantity)*ts.konversi as total_afterkonversi
// from 
// permintaanbarang_details pd,item_satuans ts 
// where pd.item_satuan_id = ts.id and pd.deleted_at is null and ts.deleted_at is null
// group by pd.item_id,pd.item_satuan_id,ts.konversi) as cte left join barangkeluar_details bd on cte.id_item_project = bd.item_id and cte.id_satuan = bd.item_satuan_id where bd.deleted_at is null group by cte.id_item_project,cte.id_satuan,cte.totalminta) as cte1 
// inner join item_satuans itsn on cte1.id_satuan = itsn.id group by id_item_project) as tbl2 on tbl1.itemid = tbl2.id_item_project) as hasil,items,item_projects where hasil.id_item = items.id and items.deleted_at is null and hasil.itemid = item_projects.id)) as results"),'item_satuans.id','=','results.id_satuan_afterkonversi')->where('results.project_id',$request->session()->get('project_id'))->get();
        //Inventory::select('item_id',DB::raw('sum(quantity) as total_stock'))->groupBy('item_id')->get();

        $stock = DB::table("inventories")
                    ->join("item_satuans", "item_satuans.id", "inventories.item_satuan_id")
                    ->join("item_projects", "item_projects.id", "inventories.item_id")
                    ->join("items", "items.id", "item_projects.item_id")
                    ->where("inventories.project_id", $project->id)
                    ->select("inventories.item_id as item_id",DB::raw("SUM(inventories.quantity) as sum"), "item_satuans.name as satuan","items.name as item_name")
                    ->groupBy("inventories.item_id","item_satuans.name","items.name")
                    ->get();

        foreach ($stock as $key => $value) {
            # code...
            // return $value->satuan;
            $item_project = $item_project = ItemProject::where("id", $value->item_id)->first();
            // return $category;
            $listResults = array(
                'id'=>$value->item_id,
                'item_id'=>$value->item_id,
                'category' => $item_project->item->category->name,
                'item_name' => $value->item_name,
                'total_stock_onhand'=>number_format($value->sum,2,".",","),
                'total_stock_avaible'=>number_format($value->sum,2,".",","),
                'satuan' =>$value->satuan,
            );
            array_push($results, $listResults);
        }

        // return $results;
        return datatables()->of($results)->toJson();
    }

    public function viewStock(Request $request)
    {
        $project = Project::find($request->session()->get('project_id'));
        $user = \Auth::user();
        return view('inventory::stock_view.index',compact('project','user'));
    }

    public function detailsStock(Request $request,$id)
    {
        $results = null;
        $project = Project::find($request->session()->get('project_id'));
        $user = \Auth::user();
        $stockResults = [];
        $arrsatuans = [];
        if($id > 0)
        {
            $item = ItemProject::find($id);

            foreach ($item->item->satuans as $key => $value) {
                # code...
                $allsatuans = array(
                    'no' =>$key+1,
                    'satuan_name' => $value->name
                );
                array_push($arrsatuans, $allsatuans);
            }
            $results = array(
                'name' => $item->item->name,
                'item_category' => $item->item->category->name,
                'satuan_warning' => $item->satuan_warning,
                'stock_min' => $item->stock_min,
                'is_inventory' => $item->is_inventory,
                'is_consumable' => $item->is_consumable,
                'description' => $item->description,
                'default_warehouse' => is_null($item->warehouse) ? '' : $item->warehouse->name
            );
        }

        $booking = PermintaanbarangDetail::select('item_id',DB::raw('sum(quantity) as booking'))->where('item_id',$id)->groupBy('item_id')->first();

        $getItemStockByWarehouse =  Inventory::select('item_id','item_satuan_id','warehouse_id',DB::raw('sum(quantity) as total_stock'))->where('item_id',$id)->groupBy('item_id','warehouse_id','item_satuan_id')->get();

        foreach ($getItemStockByWarehouse as $key => $value) {
            # code...
            if ($value->satuan != null) {
                # code...
                $sub_data = array(
                    'warehouse_name'=> $value->warehouse->name,
                    'total_stock' => $value->total_stock,
                    'satuan' =>$value->satuan->name
                );
                array_push($stockResults, $sub_data);

            }else{
                $sub_data = array(
                    'warehouse_name'=> $value->warehouse->name,
                    'total_stock' => $value->total_stock,
                    'satuan' => ''
                );
                array_push($stockResults, $sub_data);
            }

        }
        
        $stockResults = json_encode($stockResults);
        $results = json_encode($results);
        $resultSatuans = json_encode($arrsatuans);

        $uraian_PO = PurchaseOrder::join("tender_menang_pr","tender_menang_pr.id","purchaseorders.id_tender_menang")
                                ->join("tender_purchase_request_group_rekanans","tender_purchase_request_group_rekanans.id","tender_menang_pr.tender_purchase_group_rekanan_id")
                                ->join("tender_purchase_request_groups","tender_purchase_request_groups.id","tender_purchase_request_group_rekanans.tender_purchase_request_group_id")
                                ->join("tender_purchase_request_group_details","tender_purchase_request_group_details.tender_purchase_request_groups_id","tender_purchase_request_groups.id")
                                ->join("purchaserequest_details","purchaserequest_details.id","tender_purchase_request_group_details.id_purchase_request_detail")
                                ->join("purchaserequests","purchaserequests.id","purchaserequest_details.purchaserequest_id")
                                ->join("departments","departments.id","purchaserequests.department_id")
                                ->join("item_satuans","item_satuans.id","purchaserequest_details.item_satuan_id")
                                // ->join("purchaseorder_details","purchaseorder_details.purchaseorder_id","purchaseorders.id")
                                ->where("purchaserequest_details.item_id",$id)
                                ->select("purchaseorders.id as PO_id","purchaserequests.id as PR_id","purchaserequest_details.id as PRD_id","purchaserequest_details.item_id","purchaserequest_details.quantity as PRD_quantity","purchaserequests.is_urgent as urgent","purchaserequests.butuh_date as butuh_date","purchaserequests.department_id as department_id","departments.name as department_name","item_satuans.name as item_satuan_name","purchaserequests.date as dibuat_date")
                                ->orderBy("purchaserequests.is_urgent","DESC")
                                ->orderBy("purchaserequests.butuh_date","ASC")
                                ->orderBy("purchaserequests.date","ASC")
                                ->get();
        // return $uraian_PO;
        return view('inventory::stock_view.details',compact('results','stockResults','resultSatuans','booking','project','user','uraian_PO'));
       // return response()->json($results);
    }

    public function print(Request $request)
    {
        $projectname = Project::find($request->session()->get('project_id'))->name;
        $results = [];

        $stocks =  ItemSatuan::select('item_satuans.name as satuan_name','item_satuans.item_id','results.*')
        ->join(DB::raw("((select hasil.*,items.name as item_name,item_projects.project_id,item_categories.name as category,
    (select stn.id from item_satuans stn where stn.konversi = (select min(st.konversi) from item_satuans st where st.item_id = stn.item_id and st.deleted_at is null) and stn.item_id = hasil.id_item and stn.deleted_at is null) as id_satuan_afterkonversi 

from (select tbl1.*,tbl1.stock_afterkonversi - tbl2.sisabookingafterkonversi as stock_avaible from (select cte.itemid,cte.id_item,sum(cte.stock_afterkonversi) as stock_afterkonversi from 
(select inv.item_id as itemid,itms.item_id as id_item,sum(inv.quantity) * itns.konversi as stock_afterkonversi from inventories inv  
            inner join item_satuans itns on inv.item_satuan_id = itns.id 
            inner join item_projects itms on inv.item_id = itms.id where inv.deleted_at is null and itms.deleted_at is null and itns.deleted_at is null group by inv.item_id,itms.item_id,itns.konversi) as cte 
group by cte.id_item,cte.itemid) as tbl1 
left join (select cte1.id_item_project, sum(cte1.sisabooking*itsn.konversi) as sisabookingafterkonversi from (select cte.id_item_project,
cte.id_satuan,
coalesce(sum(bd.quantity),0) as totalkeluar,
cte.totalminta - coalesce(sum(bd.quantity),0) as sisabooking 
from (select pd.item_id as id_item_project,
pd.item_satuan_id as id_satuan,
sum(pd.quantity) as totalminta,
sum(pd.quantity)*ts.konversi as total_afterkonversi
from 
permintaanbarang_details pd,item_satuans ts 
where pd.item_satuan_id = ts.id and pd.deleted_at is null and ts.deleted_at is null
group by pd.item_id,pd.item_satuan_id,ts.konversi) as cte left join barangkeluar_details bd on cte.id_item_project = bd.item_id and cte.id_satuan = bd.item_satuan_id 
where bd.deleted_at is null group by cte.id_item_project,cte.id_satuan,cte.totalminta) as cte1 
inner join item_satuans itsn on cte1.id_satuan = itsn.id group by id_item_project) as tbl2 on tbl1.itemid = tbl2.id_item_project) as hasil,items,item_projects,item_categories 
where hasil.id_item = items.id and items.deleted_at is null and hasil.itemid = item_projects.id and items.sub_item_category_id = item_categories.id)) as results"),'item_satuans.id','=','results.id_satuan_afterkonversi')->where('results.project_id',$request->session()->get('project_id'))->orderBy('results.category')->get();

        $stock = Inventory::get();
        // return $stock;

        $pdf = PDF::loadView('inventory::stock_view.print',compact('stocks','projectname'))->setPaper('a4','potrait');
        return $pdf->stream('laporan_persediaan.pdf');
        //return view('stock_view.print',compact('stocks','projectname'));

    }

    public function indexStockMin(Request $request)
    {
        $project = Project::find($request->session()->get('project_id'));
        $user = Auth::user();
        $getStocks = ItemSatuan::select('item_satuans.name as satuan_name','item_satuans.item_id','results.*')
        ->join(DB::raw("((select hasil.*,items.name as item_name,item_projects.project_id,
    (select stn.id from item_satuans stn where stn.konversi = (select min(st.konversi) from item_satuans st where st.item_id = stn.item_id and st.deleted_at is null) and stn.item_id = hasil.id_item and stn.deleted_at is null) as id_satuan_afterkonversi 

from (select tbl1.*,tbl1.stock_afterkonversi - tbl2.sisabookingafterkonversi as stock_avaible from (select cte.itemid,cte.id_item,sum(cte.stock_afterkonversi) as stock_afterkonversi from 
(select inv.item_id as itemid,itms.item_id as id_item,sum(inv.quantity) * itns.konversi as stock_afterkonversi from inventories inv  
            inner join item_satuans itns on inv.item_satuan_id = itns.id 
            inner join item_projects itms on inv.item_id = itms.id where inv.deleted_at is null and itms.deleted_at is null and itns.deleted_at is null group by inv.item_id,itms.item_id,itns.konversi) as cte 
group by cte.id_item,cte.itemid) as tbl1 
left join (select cte1.id_item_project, sum(cte1.sisabooking*itsn.konversi) as sisabookingafterkonversi from (select cte.id_item_project,
cte.id_satuan,
coalesce(sum(bd.quantity),0) as totalkeluar,
cte.totalminta - coalesce(sum(bd.quantity),0) as sisabooking 
from (select pd.item_id as id_item_project,
pd.item_satuan_id as id_satuan,
sum(pd.quantity) as totalminta,
sum(pd.quantity)*ts.konversi as total_afterkonversi
from 
permintaanbarang_details pd,item_satuans ts 
where pd.item_satuan_id = ts.id and pd.deleted_at is null and ts.deleted_at is null
group by pd.item_id,pd.item_satuan_id,ts.konversi) as cte left join barangkeluar_details bd on cte.id_item_project = bd.item_id and cte.id_satuan = bd.item_satuan_id where bd.deleted_at is null group by cte.id_item_project,cte.id_satuan,cte.totalminta) as cte1 
inner join item_satuans itsn on cte1.id_satuan = itsn.id group by id_item_project) as tbl2 on tbl1.itemid = tbl2.id_item_project) as hasil,items,item_projects where hasil.id_item = items.id and items.deleted_at is null and hasil.itemid = item_projects.id and hasil.stock_afterkonversi = item_projects.stock_min )) as results"),'item_satuans.id','=','results.id_satuan_afterkonversi')->where('results.project_id',$request->session()->get('project_id'))->get();

        return view('inventory::stock_view.index_stockMin',compact('getStocks','project','user'));
    }

    public function printMinimumStock(Request $request)
    {
        $project = Project::find($request->session()->get('project_id'));
        $start_date = $request->start_opname;
        $end_date = $request->end_opname;
        $stocks = ItemSatuan::select('item_satuans.name as satuan_name','item_satuans.item_id','results.*')
        ->join(DB::raw("((select hasil.*,items.name as item_name,item_projects.project_id,item_projects.stock_min,
    (select stn.id from item_satuans stn where stn.konversi = (select min(st.konversi) from item_satuans st where st.item_id = stn.item_id and st.deleted_at is null) and stn.item_id = hasil.id_item and stn.deleted_at is null) as id_satuan_afterkonversi 

from (select tbl1.*,tbl1.stock_afterkonversi - tbl2.sisabookingafterkonversi as stock_avaible from (select cte.itemid,cte.id_item,sum(cte.stock_afterkonversi) as stock_afterkonversi from 
(select inv.item_id as itemid,itms.item_id as id_item,sum(inv.quantity) * itns.konversi as stock_afterkonversi from inventories inv  
            inner join item_satuans itns on inv.item_satuan_id = itns.id 
            inner join item_projects itms on inv.item_id = itms.id where inv.deleted_at is null and itms.deleted_at is null and itns.deleted_at is null and inv.date between '".$start_date."' and '".$end_date."' group by inv.item_id,itms.item_id,itns.konversi) as cte 
group by cte.id_item,cte.itemid) as tbl1 
left join (select cte1.id_item_project, sum(cte1.sisabooking*itsn.konversi) as sisabookingafterkonversi from (select cte.id_item_project,
cte.id_satuan,
coalesce(sum(bd.quantity),0) as totalkeluar,
cte.totalminta - coalesce(sum(bd.quantity),0) as sisabooking 
from (select pd.item_id as id_item_project,
pd.item_satuan_id as id_satuan,
sum(pd.quantity) as totalminta,
sum(pd.quantity)*ts.konversi as total_afterkonversi
from 
permintaanbarang_details pd,item_satuans ts 
where pd.item_satuan_id = ts.id and pd.deleted_at is null and ts.deleted_at is null 
and pd.created_at between '".$start_date."' and '".$end_date."' group by pd.item_id,pd.item_satuan_id,ts.konversi) as cte left join barangkeluar_details bd on cte.id_item_project = bd.item_id and cte.id_satuan = bd.item_satuan_id where bd.deleted_at is null group by cte.id_item_project,cte.id_satuan,cte.totalminta) as cte1 
inner join item_satuans itsn on cte1.id_satuan = itsn.id group by id_item_project) as tbl2 on tbl1.itemid = tbl2.id_item_project) as hasil,items,item_projects where hasil.id_item = items.id and items.deleted_at is null and hasil.itemid = item_projects.id and hasil.stock_afterkonversi = item_projects.stock_min )) as results"),'item_satuans.id','=','results.id_satuan_afterkonversi')->where('results.project_id',$request->session()->get('project_id'))->get();

        
        $pdf = PDF::loadView('inventory::stock_view.print_stokminimum',compact('stocks','project'))->setPaper('a4','potrait');
        
        return $pdf->stream('laporan_stokminimum.pdf');
    }

    public function printInventoryTransaction(Request $request)
    {
        $project = Project::find($request->session()->get('project_id'));
        $start_date = $request->start_opname;
        $end_date = $request->end_opname;
        $user = Auth::user();
        $str_query = "((select hasil.*,item_projects.item_id as itemid,items.name as item_name,
(select stn.id from item_satuans stn where stn.konversi = (select min(st.konversi) from item_satuans st where st.item_id = stn.item_id) and stn.item_id = item_projects.item_id) as id_satuan_afterkonversi from (select 
    cte_masuk.item_id,
    cte_masuk.total_masuk,
    coalesce(cte_keluar.total_keluar,0) as total_keluar,
    coalesce(cte_masuk.total_masuk-cte_keluar.total_keluar,cte_masuk.total_masuk) as saldo from 
(select 
    inv.item_id,
    sum(inv.quantity*itns.konversi) as total_masuk 
    from inventories inv inner join item_satuans itns on inv.item_satuan_id = itns.id where sumber_type not like '%BarangkeluarDetail%' and inv.date between '".$start_date."' and '".$end_date."' group by inv.item_id,itns.konversi order by inv.item_id) as cte_masuk 
left join (
select 
    inv.item_id,
    sum(quantity*itns.konversi *(-1)) as total_keluar 
    from inventories inv inner join item_satuans itns on inv.item_satuan_id = itns.id where  sumber_type like '%BarangkeluarDetail%' and inv.date between '".$start_date."' and '".$end_date."' group by inv.item_id,itns.konversi order by inv.item_id
) as cte_keluar on cte_masuk.item_id = cte_keluar.item_id group by cte_masuk.item_id,cte_masuk.total_masuk,cte_keluar.total_keluar) as hasil,item_projects,items where hasil.item_id = item_projects.id and item_projects.item_id = items.id)) as results";
            $query = ItemSatuan::select('item_satuans.name as name_satuan','results.*')
        ->join(DB::raw($str_query),'item_satuans.id','=','results.id_satuan_afterkonversi')->get();
        $pdf = PDF::loadView('inventory::stock_view.printArusBarang',compact('query','project','user','request'))->setPaper('a4','potrait');
        return $pdf->stream('laporan_arusbarang.pdf');
        
    }


    public function indexArusBarang(Request $request)
    {
        $project = Project::find($request->session()->get('project_id'));
        $user = Auth::user();

        $query = ItemSatuan::select('item_satuans.name as name_satuan','results.*')
        ->join(DB::raw("((select hasil.*,item_projects.item_id as itemid,items.name as item_name,
(select stn.id from item_satuans stn where stn.konversi = (select min(st.konversi) from item_satuans st where st.item_id = stn.item_id) and stn.item_id = item_projects.item_id) as id_satuan_afterkonversi from (select 
    cte_masuk.item_id,
    cte_masuk.total_masuk,
    coalesce(cte_keluar.total_keluar,0) as total_keluar,
    coalesce(cte_masuk.total_masuk-cte_keluar.total_keluar,cte_masuk.total_masuk) as saldo from 
(select 
    inv.item_id,
    sum(inv.quantity*itns.konversi) as total_masuk 
    from inventories inv inner join item_satuans itns on inv.item_satuan_id = itns.id where sumber_type not like '%BarangkeluarDetail%' 
    group by inv.item_id,itns.konversi order by inv.item_id) as cte_masuk 
left join (
select 
    inv.item_id,
    sum(quantity*itns.konversi *(-1)) as total_keluar 
    from inventories inv inner join item_satuans itns on inv.item_satuan_id = itns.id where  sumber_type like '%BarangkeluarDetail%' 
    group by inv.item_id,itns.konversi order by inv.item_id
) as cte_keluar on cte_masuk.item_id = cte_keluar.item_id group by cte_masuk.item_id,cte_masuk.total_masuk,cte_keluar.total_keluar) as hasil,item_projects,items where hasil.item_id = item_projects.id and item_projects.item_id = items.id)) as results"),'item_satuans.id','=','results.id_satuan_afterkonversi')->get();

        return view('inventory::stock_view.index_arus',compact('project','user','query'));
    }

}
