<?php

namespace Modules\Inventory\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Modules\Inventory\Http\Requests\RequestItemSatuan;

use Modules\Inventory\Entities\ItemSatuan;
use Modules\Inventory\Entities\Satuan;
use Modules\Inventory\Entities\Item;
use Modules\Project\Entities\Project;

use datatables;


class ItemSatuanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = \Auth::user();
        $project = Project::all();
        $items = Item::find($request->id);
        return view('inventory::items_satuan.index', compact('request', 'items','project','user'));
    }

    public function getItemSatuan(Request $request)
    {
        $item_id = $request->id;
        $items_satuans = null;
        if($item_id <> null) {

            $items_satuans      = ItemSatuan::where('item_id', '=', $item_id)->get();
        }
        else
        {
            $items_satuans      = ItemSatuan::all();
        }
        $results = [];
        foreach ($items_satuans as $key => $value) {
            # code...
            $arr = [
                'id'=>$value->id,
                'item_name'=>$value->item->name,
                'satuan_name'=>$value->name,
                'konversi'=>$value->konversi,
                'created_at'=>date('d-m-Y H:m:s',strtotime($value->created_at)),
                'updated_at'=>date('d-m-Y H:m:s',strtotime($value->updated_at))
            ];

            array_push($results, $arr);
        }

        return datatables()->of($results)->toJson();
    }

    public function add(Request $request)
    {
        $items = Item::find($request->id);
        $user = \Auth::user();
        $project = Project::all();
        $satuans = Satuan::all();
        return view('inventory::items_satuan.add_form', compact('items','user','project','satuans'));
    }
    
    public function addPost(RequestItemSatuan $request) 
    {
        $stat = false;
        $satuan = Satuan::find($request->id_satuan);
        $items_satuan                   = new ItemSatuan;
        $items_satuan->id_satuan        = $request->id_satuan;
        $items_satuan->item_id          = $request->item_id;
        $items_satuan->konversi         = $satuan->konversi;
        $items_satuan->name             = $satuan->name;
        $status                         = $items_satuan->save();

        if ($status) 
        {
            $stat = true;
        }

        return response()->json($stat);
    }

    public function edit(Request $request)
    {
        $items_satuans                      = ItemSatuan::find($request->id);
        return view('items_satuan.edit_form', compact('items_satuans'));
    }

   public function update(Request $request)
    {
        $stat =0;
        $name = $request->name;
        $pk = $request->pk;
        $value = $request->value;
        $updated ='';
        if($name == 'id_satuan')
        {
            $getSatuan = Satuan::find($value);
            $updated = ItemSatuan::find($pk)->update([$name=>$value,'name'=>$getSatuan->name,'konversi'=>$getSatuan->konversi]);
        }else
        {
            $updated = ItemSatuan::find($pk)->update([$name=>$value]);
        }
        
        if($updated)
        {
            $stat = 1;
        }

        return response()->json(['return'=>$stat]);

    }

    public function delete(Request $request)
    {
        $items_satuan               = ItemSatuan::find($request->id);
        $status                     = $items_satuan->delete();
        $stat = false;
        if ($status)
        {
            $stat = true;
        }

        return response()->json($stat);
    }

    public function typeSatuan(Request $request)
    {
        $results = [];
        $term = $request->terms;
        $getSatuan = ItemSatuan::select('name')->where('name','like','%'.$term.'%')->get();
        $temp = '';
        foreach ($getSatuan as $key => $value) {
            # code...
            if(strcmp($value->name ,$temp) != 0)
            {
                array_push($results, $value->name);
            }
            $temp = $value->name;

        }
        return response()->json($results);
    }
}