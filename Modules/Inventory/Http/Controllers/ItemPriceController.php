<?php

namespace Modules\Inventory\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Modules\Inventory\Entities\ItemPrice;
use Modules\Inventory\Entities\ItemProject;
use Modules\Project\Entities\Project;
use Auth;

class ItemPriceController extends Controller
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
        $user = Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        $projects = Project::where('id','!=',$request->session()->get('project_id'))->get();
        $items = null;
        if($request->id <> null) {

            $items_prices       = ItemPrice::where([['item_id', '=', $request->id],['project_id','=',$request->session()->get('project_id')]])->get();
            $items              = ItemProject::find($request->id);
        }
        else
        {
            $items_prices       = ItemPrice::where('project_id',$request->session()->get('project_id'))->get();
        }

        //$items_prices           = $items_prices->latest()->paginate(1000);

        return view('inventory::items_price.index', compact('request', 'items_prices','items','project','projects','user'));
    }

    public function add(Request $request)
    {
        $user = \Auth::user();
        $items                          = ItemProject::find($request->id); 
        $project_id                     = $request->session()->get('project_id');
        $project                        = Project::find($project_id);
        $satuans                        = ItemProject::find($request->id)->item->satuans;

        $other_project_prices           = ItemPrice::select('its.name as item_name','item_satuans.name as satuan_name','item_prices.price','p.name','item_prices.created_at')
        ->leftJoin('item_projects as ips','item_prices.item_id','ips.id')
        ->join('items as its','ips.item_id','its.id')
        ->join('projects as p','ips.project_id','p.id')
        ->join('item_satuans','item_prices.item_satuan_id','item_satuans.id')
        ->where('its.id',$items->item_id)
        ->where('p.id','<>',$project_id)
        ->orderBy('item_prices.created_at','desc')
        ->distinct()
        ->take(1)->get();

        return view('inventory::items_price.add_form', compact('items', 'project','satuans','user','other_project_prices'));
    }
    
    public function addPost(Request $request)
    {
        $stat = false;
        $items_price                    = new ItemPrice;
        $items_price->item_id           = $request->item_id;
        $items_price->project_id        = $request->session()->get('project_id');
        $items_price->item_satuan_id    = $request->item_satuan_id;
        $items_price->price             = $request->mprice;
        $items_price->ppn               = $request->ppn;
        $items_price->date_price        = $request->date;
        $items_price->description       = $request->description;
        $status                         = $items_price->save();

        if ($status) 
        {
            $stat = true;
        }

        return response()->json($stat);
    }

    public function edit(Request $request)
    {
        $project_id = $request->session()->get('project_id');
        $items_prices                   = ItemPrice::find($request->id);
        $satuans                        = ItemProject::find($items_prices->item_id)->item->satuans;
        $project                       = Project::find($project_id);
        $user                          = Auth::user();

        $other_project_prices           = ItemPrice::select('its.name as item_name','item_satuans.name as satuan_name','item_prices.price','p.name','item_prices.created_at')
        ->leftJoin('item_projects as ips','item_prices.item_id','ips.id')
        ->join('items as its','ips.item_id','its.id')
        ->join('projects as p','ips.project_id','p.id')
        ->join('item_satuans','item_prices.item_satuan_id','item_satuans.id')
        ->where('its.id',$items_prices->item->item->id)
        ->where('p.id','<>',$project_id)
        ->orderBy('item_prices.created_at','desc')
        ->distinct()
        ->take(1)->get();

        return view('inventory::items_price.edit_form', compact('other_project_prices','items_prices', 'satuans','project','user'));
    }

    public function update(Request $request)
    {
        $stat = false;
        $items_price                    = ItemPrice::find($request->id);
        $items_price->item_satuan_id    = $request->item_satuan_id;
        $items_price->price             = $request->mprice;
        $items_price->date_price       = $request->date;
        $items_price->ppn               = $request->ppn;
        $items_price->description       = $request->description;
        $status                         = $items_price->save();

        if ($status) 
        {
            $stat = true;
        }

        return response()->json(['stat'=>$stat]);

    }

    public function delete(Request $request)
    {
        $items_price                    = ItemPrice::find($request->id);
        $status                         = $items_price->delete();
        $stat = false;
        if ($status) 
        {
            $stat = true;
        }

        return response()->json($stat);
    }

    public function comparePrice(Request $request)
    {
        $results = [];
        $project_id = $request->project_id;
        $item_id = $request->item_id;
        $getItems = ItemPrice::select('item_id','item_satuan_id','project_id','price','date_price')->where([
            ['project_id','=',$project_id],
            ['item_id','=',$item_id]
        ])->get();

        foreach ($getItems as $key => $value) {
            # code...
            $arrResults = array(
                'price'=>'Rp.'.number_format($value->price,2,".",","),
                'satuan' =>$value->satuan->name,
                'date_price'=>date('d-m-Y',strtotime($value->date_price))
            );

            array_push($results, $arrResults);
        }

        return response()->json($results);
    }
}