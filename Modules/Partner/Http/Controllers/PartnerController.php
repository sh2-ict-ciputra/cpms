<?php

namespace Modules\Partner\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Modules\Partner\Entities\Partner;
use Modules\Partner\Entities\PartnerProject;
use Modules\Project\Entities\Project;

class PartnerController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $user = \Auth::user();
        $partner = Partner::get();
        return view('partner::index',compact("partner","user"));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $user = \Auth::user();
        return view('partner::create',compact("user"));
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $partner = new Partner;
        $partner->nama_partner = $request->nama;
        $partner->save();
        $url = "/partner/detail?id=".$partner->id;
        $status = 1;
        return response()->json( ["status" => $status, "url" => $url] );
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show(Request $request)
    {
        $user = \Auth::user();
        $partner = Partner::find($request->id);
        $project = Project::get();
        return view('partner::detail',compact("user","partner","project"));
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit()
    {
        return view('partner::edit');
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

    public function tambahProject(Request $request){
        // return $request;
        $partner_project = new PartnerProject;
        $partner_project->partner_id = $request->partner_id;
        $partner_project->project_id = $request->project_id;
        $partner_project->save();
        return response()->json( ["status" => "1"] );

    }
}
