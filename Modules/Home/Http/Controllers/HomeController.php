<?php

namespace Modules\Home\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Project\Entities\Project;
use Modules\Pt\Entities\Pt;
use Modules\Budget\Entities\BudgetTahunan;
use Modules\Budget\Entities\Budget;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */



    public function index(Request $request)
    {

        $user = \App\User::find(\Auth::user()->id);
        $project = Project::get();
	   

        if ( $user->id == "1"){
            return view('home::index',compact("user","project"));
        }else{
            return redirect("user_login?user_login=".$user->user_login);
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('home::create');
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
        return view('home::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit()
    {
        return view('home::edit');
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

    public function setautobudget(Request $request){
        $project = Project::find($request->id);
        /*foreach ($project->kawasans as $key => $value) {
            if ( $value->deleted_at == ""){

                echo $value->id;
                echo "<br/>";
            }
        }*/


        $department_id = $request->department_id;
        $pt  = Pt::find($request->pt);

        foreach ( $project->kawasans as $key => $value ){
            echo "asdcads".count($value->budgets);
            if ( count($value->budgets) <= 0 ){
                $number = \App\Helpers\Document::new_number('BDG', $department_id,$project->id).$pt->code;
                $budget = new Budget;
                $budget->pt_id = $pt->id;
                $budget->department_id = $department_id;
                $budget->project_id = $request->id;
                $budget->project_kawasan_id = $value->id;
                $budget->no = $number;
                $budget->start_date = '2019-01-01 00:00:00.000';
                $budget->end_date = '2029-01-01 00:00:00.000';
                $budget->description = 'Generate by Sistem';
                $budget->created_by = 1;
                $budget->save();

                $budget_tahunan                 = new BudgetTahunan;
                $budget_tahunan->budget_id      = $budget->id;
                $budget_tahunan->no             = \App\Helpers\Document::new_number('BDG-T', $department_id,$project->id).$pt->code;
                $budget_tahunan->tahun_anggaran = 2019;
                $budget_tahunan->description    = 'Generate by Sistem';
                $budget_tahunan->save();
                echo "Auto Budget created at ".date("Y-m-d")."=>".$value->id."<>".$value->department_id."<>".$value->id."<>".$project->id;
                echo "<br/>";
            }
        }
    }

    public function setfasumbudget(Request $request){
        $project = Project::find($request->id);
        $department_id = $request->department_id;
        $pt  = Pt::find($request->pt);
        $is_budget_fasum = 0;

        foreach ($project->budgets as $key => $value) {
            if ( $value->project_kawasan_id == "" ){
                $is_budget_fasum = 1;
            }
        }

        if ( $is_budget_fasum == 0 ){
            $number = \App\Helpers\Document::new_number('BDG', $department_id,$project->id).$pt->code;
            $budget = new Budget;
            $budget->pt_id = $pt->id;
            $budget->department_id = $department_id;
            $budget->project_id = $request->id;
            $budget->project_kawasan_id = NULL;
            $budget->no = $number;
            $budget->start_date = '2019-01-01 00:00:00.000';
            $budget->end_date = '2029-01-01 00:00:00.000';
            $budget->description = 'Generate by Sistem';
            $budget->created_by = 1;
            $budget->save();

            $budget_tahunan                 = new BudgetTahunan;
            $budget_tahunan->budget_id      = $budget->id;
            $budget_tahunan->no             = \App\Helpers\Document::new_number('BDG-T', $department_id,$project->id).$pt->code;
            $budget_tahunan->tahun_anggaran = 2019;
            $budget_tahunan->description    = 'Generate by Sistem';
            $budget_tahunan->save();
            echo "Auto Budget created at ".date("Y-m-d")."=>".$value->id."<>".$value->department_id."<>".$value->id."<>".$project->id;
            echo "<br/>";
        }
    }
}
