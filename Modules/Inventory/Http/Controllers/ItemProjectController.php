<?php

namespace Modules\Inventory\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Modules\Project\Entities\Project;
use Modules\Inventory\Entities\ItemProject;
use Modules\Inventory\Entities\Item;
use Modules\Inventory\Entities\ItemSatuan;
use Modules\Inventory\Entities\PermintaanbarangDetail;
use Modules\Inventory\Entities\Inventory;
use DB;
use Auth;
class ItemProjectController extends Controller
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
        return view('inventory::items_project.index', compact('project','user'));
    }
    public function getData(Request $request)
    {
        $items = ItemProject::where('project_id',$request->session()->get('project_id'))->get();
        $results = [];
        foreach ($items as $key => $value){
            # code...
            $satuan_name = ItemSatuan::select('name')->where([['konversi','=',DB::raw("(( select min(st.konversi) from item_satuans as st where st.item_id = item_id and st.deleted_at is null ))")],['item_id',$value->item_id]])->first();
            $arr = array(
                'id' => $value->id,
                'item_id'=>$value->item_id,
                'item_category'=> is_null($value->item->category) ? 'Kosong': $value->item->category->name,
                'item_name' => $value->item->name,
                'stock_min' =>$value->stock_min,
                'satuan'=>is_null($satuan_name) ? 'Kosong' : $satuan_name->name,
            );
            array_push($results, $arr);
        }

        return datatables()->of($results)->toJson();

    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create(Request $request)
    {
        $user = \Auth::user();
        $items = Item::all();
        $project_id = $request->session()->get('project_id');
        $project = Project::find($project_id);
        $all_project = Project::select('id','name')->where('id','<>',$project_id)->get();

        return view('inventory::items_project.create',compact('user','project','items','all_project'));
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function create_from_project(Request $request)
    {
        $msg = '';
        $id_project = $request->from_project;
        $project_id_now = $request->session()->get('project_id');
        $all_items_from = ItemProject::where('project_id',$id_project)->get();
        $arr_item_exists = [];
        $stat = false;

        if(count($all_items_from))
        {
            foreach ($all_items_from as $key => $value) {
            # code...
                $check_exist = ItemProject::where([['item_id','=',$value->item_id],['project_id','=',$project_id_now]])->first();

                if($check_exist == null)
                {
                    $create = ItemProject::create(['item_id'=>$value->item_id,
                        'project_id'=>$project_id_now,
                        'stock_min'=>$value->stock_min,
                        'pph'=>$value->pph,
                        'satuan_warning'=>$value->satuan_warning,
                        'is_inventory'=>$value->is_inventory,
                        'is_consumable'=>$value->is_consumable,
                        'description'=>$value->description]);
                    if($create)
                    {
                        $stat = true;
                    }
                }
                else
                {
                    array_push($arr_item_exists, $value->item->name);
                }
            }
        }
        else
        {
            $msg = 'Item pada proyek '.Project::find($id_project)->name.' Kosong';
        }
        
        return response()->json(['stat'=>$stat,'data'=>$arr_item_exists,'msg'=>$msg]);
    }
    public function store(Request $request)
    {
        $stat = false;
        $msg = '';
        $satuan_warning = $request->satuan_warning;
            if($satuan_warning == "on") {
                $nilai_satuan_warning = 1;
            }else{
                $nilai_satuan_warning = 0;
            }
        $inventory = $request->is_inventory;
            if($inventory == "on") {
                $nilai_inventory = 1;
                }else{
                $nilai_inventory = 0;
            }
        $consumable = $request->is_consumable;
            if($consumable == "on") {
                $nilai_consumable = 1;
            }else{
                $nilai_consumable = 0;
            }

        $project_id = $request->session()->get('project_id');
        $all_items = json_decode($request->id_arr_items);
        $all_items_ready = [];

        if(count($all_items) > 0)
        {
            for ($count=0; $count < count($all_items); $count++) { 
            # code...
                $check_exist = ItemProject::where([['item_id','=',$all_items[$count]],['project_id','=',$project_id]])->first();

                if($check_exist == null)
                {
                    $create = ItemProject::create(['item_id'=>$all_items[$count],
                        'project_id'=>$project_id,
                        'stock_min'=>$request->stock_min,
                        'pph'=>$request->pph,
                        'satuan_warning'=>$satuan_warning,
                        'is_inventory'=>$nilai_inventory,
                        'is_consumable'=>$nilai_consumable,
                        'description'=>$request->description]);

                    if($create)
                    {
                        $stat = true;
                    }
                }
                else
                {
                    $item_name = Item::find($all_items[$count])->name;
                    array_push($all_items_ready,$item_name);
                }
                
            }
        }
        else
        {
            $stat = false;
            $msg = 'Item Belum Dipilih';
        }
        
        return response()->json(['stat'=>$stat,'data'=>$all_items_ready,'msg'=>$msg]);
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return view('inventory::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit(Request $request,$id)
    {
        $item = ItemProject::find($id);
        $user = Auth::user();
        $items = Item::all();
        $project = Project::find($request->session()->get('project_id'));
        return view('inventory::items_project.edit',compact('project','item','user','items'));
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request)
    {
        $stat = false;
        $satuan_warning = $request->satuan_warning;
            if($satuan_warning == "on") {
                $nilai_satuan_warning = 1;
            }else{
                $nilai_satuan_warning = 0;
            }
        $inventory = $request->is_inventory;
            if($inventory == "on") {
                $nilai_inventory = 1;
                }else{
                $nilai_inventory = 0;
            }
        $consumable = $request->is_consumable;
            if($consumable == "on") {
                $nilai_consumable = 1;
            }else{
                $nilai_consumable = 0;
            }

        $updated = ItemProject::find($request->id)
        ->update(['item_id'=>$request->item_id,
            'stock_min'=>$request->stock_min,
            'satuan_warning'=>$satuan_warning,
            'pph'=>$request->pph,
            'is_inventory'=>$nilai_inventory,
            'is_consumable'=>$nilai_consumable,
            'description'=>$request->description]);

        if($updated)
        {
            $stat = true;
        }

        return response()->json($stat);
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy()
    {
    }

    public function getCategory(Request $request)
    {
        $category = Item::find($request->item_id)->category->name;
        return response()->json($category);
    }

    public function details(Request $request,$id)
    {
        $user = Auth::user();
        $project = Project::find($request->session()->get('project_id'));
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

        $stocks = Inventory::select('inventories.item_id',DB::raw('sum(inventories.quantity) as total_stock_onhand'),DB::raw('sum(inventories.quantity)-sum(permintaanbarang_details.quantity) as total_stock_avaible'))->leftJoin('permintaanbarang_details','inventories.item_id','=','permintaanbarang_details.item_id')->groupBy('item_id')->get();
        $getItemStockByWarehouse =  Inventory::select('item_id','warehouse_id',DB::raw('sum(quantity) as total_stock'))
        ->where('item_id',$id)->groupBy('item_id','warehouse_id')->get();

        foreach ($getItemStockByWarehouse as $key => $value) {
            # code...
            $sub_data = array(
                'warehouse_name'=> $value->warehouse->name,
                'total_stock' => $value->total_stock,
                'satuan' =>$value->item->satuans[0]->name
            );

            array_push($stockResults, $sub_data);
        }
        
        $stockResults = json_encode($stockResults);
        $results = json_encode($results);
        $resultSatuans = json_encode($arrsatuans);
        return view('inventory::items_project.details',compact('results','stockResults','resultSatuans','booking','project','user'));
       // return response()->json($results);
    }
}
