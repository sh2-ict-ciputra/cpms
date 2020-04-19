<?php

namespace Modules\Inventory\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Modules\Country\Entities\City;
use Modules\Department\Entities\Department;
use Modules\User\Entities\User;
use Modules\Inventory\Entities\Room;
use Modules\Inventory\Entities\Satuan;

class GeneralSourceController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function DepartmentSource(Request $request)
    {
    	$departments = Department::select('id','name')->get();
    	$obj = [];
        foreach ($departments as $key => $value) {
            # code...
            $obj[$value->id] = $value->name;
        }
        return response()->json($obj);
    }

    public function UserSource(Request $request)
    {
        $users = User::select('id','user_name')->get();
        $obj = [];
        foreach ($users as $key => $value) {
            # code...
            /*$arr = array('value'=>$value->id,'text'=>$value->user_name);
            array_push($obj, $arr);*/
            $obj[$value->id] = $value->user_name;
        }
        //$result = array('results'=>$obj);
        return response()->json($obj);
    }

    public function CitySource()
    {
    	$cities = City::select('id','name')->get();
    	$obj = [];
        foreach ($cities as $key => $value) {
            # code...
            $obj[$value->id] = $value->name;
        }
        return response()->json($obj);

    }

    public function RoomSource()
    {
        $rooms = Room::select('id','name')->get();

        foreach ($rooms as $key => $value) {
            # code...
            /*$arr = array('value'=>$value->id,'text'=>$value->user_name);
            array_push($obj, $arr);*/
            $obj[$value->id] = $value->name;
        }
        //$result = array('results'=>$obj);
        return response()->json($obj);

        //return response()->json($rooms);
    }

    public function typeDepartmentSource(Request $request)
    {
        $term = $request->get('q');
        $getRooms = Department::select('id','name')->where('name','like','%'.$term.'%')->get();

        return response()->json($getRooms);
    }

    public function masterSatuanSource()
    {
        $getSatuans = Satuan::all();
        $results = [];
        foreach ($getSatuans as $key => $value) {
            # code...
            $arr=[
                'id' => $value->id,
                'text'=> $value->name
            ];
            
            array_push($results,$arr);
        }

        return response()->json($results);
    }

   
}
