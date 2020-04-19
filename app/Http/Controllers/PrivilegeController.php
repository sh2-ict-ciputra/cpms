<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Rekanan\Entities\UserRekanan;


class PrivilegeController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
       
        $user = \Modules\User\Entities\User::find(\Auth::user()->id);
        $jabatan = $user->jabatan;
       
        if ( $user->user_login == "simulasi"){
            return redirect("simulasi");
        }

        if ( $user->id == "1"){
            $request->session()->put('level', 'superadmin');
            return redirect("home");
        }

        
        if ( $user->is_rekanan == 0 ){           

            foreach ($jabatan as $key => $value) {
     
                if ( $value['level'] == "10" || $value['level'] == "1016" ){
                    $request->session()->put('level', $value['level'] );
                    return redirect("/project/detail?id=".$value['project_id']);
                }elseif($value['level'] <= 7){
                    $request->session()->put('level', '');
                    return redirect("/access");
                }elseif ( $user->is_pic == "1"){
                    $request->session()->put('level', 'pic');
                    return redirect("/progress");
                }
            }
        }else {
            $user_rekanan_group = UserRekanan::where("user_login",$user->user_login)->get();
            if ( count($user_rekanan_group) > 0 ){
                $users = UserRekanan::find($user_rekanan_group->first()->id);
                $rekanan_group = $users->rekanan_group;
                $request->session()->put('rekanan_id', $rekanan_group->id);
                return redirect("rekanan/user");
            }else{
                return redirect("rekanan/user/fail");
            }
        }
        
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('user::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return view('user::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit()
    {
        return view('user::edit');
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
        \Auth::logout();
        return redirect("/");
    }

    public function getJabatan(Request $request){
        // return $request;
        $user_baru = \Modules\User\Entities\User::find($request->user_id);
        $data = [];
        foreach ( $user_baru->jabatan as $key => $value ){
            if($request->project_id == $value['project_id']){
                $arr = [
                    'jabatan_id' => $value['jabatan_id'],
                    'jabatan_name' => $value['jabatan'],
                ];
                array_push($data, $arr);
            }
        }
        return response()->json( ["data" => $data] ); 
    }

    public function changeProjectJabatan(Request $request){
        // return $request;
        $user_baru = \Modules\User\Entities\User::find($request->user_id);
        if ( $request->jabatan_id == "10" || $request->jabatan_id == "1016" ){
                // Session::put("project_id",$request->project_id);
                // Session::put("level",$request->jabatan_id);
                $request->session()->put('level', $request->jabatan_id );
                $request->session()->put('project', $request->project_id );
                // return session('project');
                return response()->json( ["data" => "/project/detail?id=".$request->project_id ] ); 
        }elseif($request->jabatan_id <= 7){
            $request->session()->put('level', '');
            $request->session()->put('project_id', $request->project_id );
            return response()->json( ["data" => "/access" ] ); 
        }elseif( $user_baru->is_pic == "1"){
            $request->session()->put('level', 'pic');
            $request->session()->put('project_id', $request->project_id );
            return response()->json( ["data" => "/progress" ] ); 

            // return redirect("/progress");
        }
        // return response()->json( ["data" => $data] ); 
    }
    
}
