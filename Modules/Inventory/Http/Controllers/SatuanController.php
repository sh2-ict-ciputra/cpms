<?php

namespace Modules\Inventory\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Modules\Inventory\Http\Requests\RequestMasterSatuan;

use Modules\Inventory\Entities\Satuan;
use Modules\Inventory\Entities\ItemSatuan;
use Modules\Project\Entities\Project;
use Auth;
class SatuanController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
     
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        $project = Project::all();
        $satuans = Satuan::all();
        return view('inventory::satuans.index',compact('user','project','satuans'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $user = Auth::user();
        $project = Project::all();
        return view('inventory::satuans.create',compact('user','project'));
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(RequestMasterSatuan $request)
    {
        $stat = false;
        $msg = '';
        $name = $request->name;
        $konversi = $request->konversi;

        $check_name_satuan = Satuan::where('name','LIKE','%'.$name.'%')->get();

        if(count($check_name_satuan) <=0)
        {
            $create = Satuan::create(['name'=>$name,'konversi'=>$konversi]);
            if($create)
            {
                $stat = true;
                $msg = 'Berhasil Di Simpan';
            }
        }
        else
        {
            $msg = 'Gagal, '.$name.' Sudah ada!';
        }


        return response()->json(['stat'=>$stat,'msg'=>$msg]);
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
    public function edit()
    {
        return view('inventory::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request)
    {

        $stat =false;
        $name = $request->name;
        $pk = $request->pk;
        $value = $request->value;

        $updated = Satuan::find($pk)->update([$name=>$value]);
        if($updated)
        {
            $updateItemSatuan = ItemSatuan::where('id_satuan',$pk)->update([$name=>$value]);
            $stat = true;
            
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
