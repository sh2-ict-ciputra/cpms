<?php

namespace Modules\Project\Entities;

use App\CustomModel;
use Modules\Budget\Entities\Budget;
use Illuminate\Support\Facades\DB;

class ProjectKawasan extends CustomModel
{
    protected $fillable = ['project_id','project_type_id','code','name','lahan_status','lahan_luas','hpptanahpermeter','zipcode','is_kawasan','description'];

    public function project()
    {
        return $this->belongsTo('Modules\Project\Entities\Project', 'project_id');
    }

    public function project_type()
    {
        return $this->belongsTo('Modules\Project\Entities\ProjectType', 'project_type_id');
    }

    public function bloks()
    {
        return $this->hasMany('Modules\Project\Entities\Blok')->where('deleted_at',null);;
    }
    
	public function budgets()
    {
        return $this->hasMany('Modules\Budget\Entities\Budget',"project_kawasan_id")->where("deleted_at",null);
    }

    public function units()
    {
        return $this->hasManyThrough('Modules\Project\Entities\Unit', 'Modules\Project\Entities\Blok')->where("bloks.deleted_at",null)->orderBy("status","desc");
        
    }

    public function workorder_details()
    {
        return $this->morphMany('App\WorkorderDetail', 'asset');
    }
    public function rab_units()
    {
        return $this->morphMany('App\RabUnit', 'asset');
    }
    public function tender_units()
    {
        return $this->morphMany('App\TenderUnit', 'asset');
    }
    public function spk_details()
    {
        return $this->morphMany('App\SpkDetail', 'asset');
    }
    public function progresses()
    {
        return $this->morphMany('App\UnitProgress', 'unit');
    }
    public function getCalculateBobotAttribute()
    {
        $total = 0;

        foreach ($this->progresses as $key => $each) 
        {
            $total = $total + ($each->nilai * $each->volume);
        }

        foreach ($this->progresses as $key => $detail) 
        {
            $detail->update([
                'bobot'=> $detail->nilai * $detail->volume / $total
            ]);
        }

        return $total;
    }

    public function getNilaiAttribute()
    {
        $nilai = array();

        foreach ($this->progresses as $key => $each) 
        {
            $nilai[$key] = $each->nilai * $each->volume * ( 1 + $each->itempekerjaan->ppn );
        }

        return array_sum($nilai);
    }

    public function getHppBudgetAttribute()
    {
        $nilai = 0;

        $items = \App\Itempekerjaan::where('group_cost',1)->get(['id']);

        $budget_details = \App\BudgetDetail::whereHas('budget', function($q){
                $q->where('project_kawasan_id', $this->id);
            })
            ->whereHas('itempekerjaan', function($q) use ($items){
                $q->whereIn('id', $items);
            })
            ->get();

        foreach ($budget_details as $key => $detail) 
        {
            $nilai = $nilai + $detail->nilai;
        }

        return $nilai;
    }

    public function getHppKontrakAttribute()
    {
        $nilai = 0;
        if ( $this->total_kontrak == 0 ){
            $nilai = 0;
        }else{
            $nilai = $this->total_kontrak / $this->netto_kawasan;
        }
        return $nilai;
    }

    public function getHppRealisasiAttribute()
    {
        return 0;
    }
    
    public function getDevCostAttribute()
    {
        if (($this->lahan_luas <= 0) OR ($this->project->luas <= 0))
        {
            return 0;
        }

        $nilai = 0;

        foreach ($this->progresses as $key => $progress) 
        {
            $nilai += $progress->nilai * $progress->volume;
        }

        $dev_cost_project = $this->project->dev_cost * $this->lahan_luas / $this->project->luas;

        return $nilai + $dev_cost_project;
    }

    public function getEfisiensiAttribute()
    {
        if ($this->lahan_luas == 0) 
        {
            return 0;
        }

        $efektif = 0;

        /*foreach ($this->units()->where('is_sellable',true)->get() as $key => $unit) 
        {
            $efektif += $unit->tanah_luas;
        }*/
        $efektif = $this->lahan_sellable / $this->lahan_luas;

        return $efektif;
    }
    
    public function getNilaiKontrakAttribute()
    {
        return $this->con_cost_kontrak;
    }

    public function getNilaiDevCostBudgetAttribute()
    {
        if ($this->project->luas <=0) 
        {
            return 0;
        }

        $nilai = 0;

        if ($this->lahan_luas <= 0) 
        {
            return 0;
        }

        foreach ($this->budgets as $key => $budget) 
        {
            $nilai += $budget->nilai;
        }

        $nilai_project = $this->project->nilai_budget * $this->lahan_luas / $this->project->luas;

        return ($nilai + $nilai_project);
    }
    public function getDevCostBudgetBrutoAttribute()
    {
        if ($this->lahan_luas > 0) 
        {
            return $this->nilai_dev_cost_budget / $this->lahan_luas;
        }else{
            return 0;
        }
    }
    public function getDevCostBudgetNettoAttribute()
    {
        if ($this->efisiensi == 0) 
        {
            return 0;
        }

        return $this->dev_cost_budget_bruto / $this->efisiensi;
    }
    public function getNilaiDevCostKontrakAttribute()
    {
        if ($this->project->luas <= 0) 
        {
            return 0;
        }

        $spk = 0;

        foreach ($this->progresses as $key => $progress) 
        {
            $spk += $progress->nilai * $progress->volume;
        }

        //
        
        $project = $this->project->dev_cost * $this->lahan_luas / $this->project->luas;
        
        //
        
        $sellable = 0;

        foreach ($this->units as $key => $unit) 
        {
            $sellable += $unit->dev_cost_kontrak;
        }

        return $spk + $project + $sellable;
    }
    public function getDevCostKontrakBrutoAttribute()
    {

    }
    public function getDevCostKontrakNettoAttribute()
    {
        if (($this->lahan_luas > 0) and ($this->efisiensi > 0))
        {
            return $this->nilai_dev_cost_kontrak / $this->lahan_luas / round($this->efisiensi, 2);
        }else{
            return 0;
        }
    }
    public function getDevCostRealisasiBrutoAttribute(){
        $nilai =0;
        if ( $this->lahan_luas == 0 || $this->total_terbayar == 0 ){
            $nilai = 0;
        }
        else {
            $nilai = $this->total_terbayar / $this->lahan_luas;
        }
        return $nilai ;
    }
    public function getDevCostRealisasiNettoAttribute(){
        $nilai =0;
        if ( $this->netto_kawasan == 0 || $this->total_terbayar == 0 ){
            $nilai = 0;
        }
        else {
            $nilai = $this->total_terbayar / $this->netto_kawasan;
        }
        return $nilai ;
    }

    public function getTotalTerbayarAttribute(){
        $nilai_terbayar = 0;
        $project = $this->project;
        $proporsional = 0;

        foreach ($this->project->spks as $key => $value) {
            foreach ($value->details as $key2 => $value2) {
                if ( $value2->asset_id != "" ){
                    if ( $value2->asset_id == $this->id ){
     
                        $nilai_terbayar = $nilai_terbayar + $value->terbayar_verified;
                    }
                }
            }
        }

        return $nilai_terbayar;

    }

    public function getNilaiConCostBudgetAttribute()
    {
        $nilai = 0;

        foreach ($this->units as $key => $unit) 
        {
            $nilai += $unit->nilai_con_cost_budget;
        }

        return $nilai;
    }
    public function getHppConCostBudgetAttribute()
    {
        $nilai = 0;

        foreach ($this->units as $key => $unit) 
        {
            $nilai += $unit->hpp_con_cost_budget;
        }

        return $nilai;
    }

    public function getNilaiConCostKontrakAttribute()
    {
        $nilai = 0;

        foreach ($this->units as $key => $unit) 
        {
            $nilai += $unit->nilai_con_cost_kontrak;
        }

        return $nilai;
    }
    public function getHppConCostKontrakAttribute()
    {
        $nilai = 0;

        foreach ($this->units as $key => $unit) 
        {
            $nilai += $unit->hpp_con_cost_kontrak;
        }

        return $nilai;
    }

    public function getNilaiConCostRealisasiAttribute()
    {
        $nilai = 0;

        foreach ($this->units as $key => $unit) 
        {
            $nilai += $unit->con_cost_realisasi;
        }

        return $nilai;
    }

     public function getSellUnitAttribute()
    {
        if ($this->lahan_luas == 0) 
        {
            return 0;
        }

        $efektif = 0;

        foreach ($this->units()->where('is_sellable',true)->get() as $key => $unit) 
        {
            $efektif += $unit->tanah_luas;
        }

        return $efektif ;
    }

    public function getNettoKawasanAttribute(){
        // $efektif = 0;
        // foreach ($this->units()->where('is_sellable',true)->get() as $key => $unit) 
        // {
        //     $efektif += $unit->tanah_luas;
        // }
        $efektif = $this->units()->where('is_sellable',true)->sum('tanah_luas');
        return $efektif;
        
    }

    public function getTotalBudgetAttribute(){
        $nilai = 0;
        $nilai_budget = 0;
        $rev = 0;
        $total_faskot = 0;
        foreach ($this->budgets as $key => $value) {     
            if ( $value->deleted_at == NULL ){
                $nilai = $nilai + $value->total_dev_cost;  
            }                 
        }

        //Proporsional
        foreach ($this->project->budgets as $key2 => $value2) {
            if ( $value2->project_kawasan_id == NULL ) {
                if ( $value2->deleted_at == NULL ){
                    $total_faskot = $value2->total_dev_cost;
                    //$id = $value2->id;
                }
            }
        }

        /*foreach ($this->project->budgets_all as $key => $value) {
            if ( $value->deleted_at == null ){
                $nilai_budget = $nilai_budget + $value->total_dev_cost;
            }else{
                $rev = $rev + 1;
            }
        }*/

        /*
        if ( $rev > 0 ){
            $total_faskot =  $nilai_budget + $this->project->dev_cost_only;
        }else{
            $total_faskot = $nilai_budget + $this->project->dev_cost_only;
        }*/



        if ( $this->lahan_luas > 0 ){
            $proporsional = ( $this->lahan_luas  / $this->project->luas ) * $total_faskot;
        }else{
            $proporsional = 0;
        }
       
        return  $proporsional + $nilai ;
    }

    public function getHppConCostAttribute(){
        $nilai =0;
        $hpp = 0;

        foreach ($this->budgets as $key => $value) {
            # code...
            $nilai = $nilai + $value->nilai_con_cost;
        }

        if ( $this->netto_kawasan != '0'){
            $hpp = $nilai / $this->netto_kawasan;
        }
        
        
        return $hpp;
    }

    public function getTotalBudgetDevCostAttribute(){
        $nilai = 0;
        if ( $this->is_kawasan == "1"){
            foreach ($this->budgets as $key => $value) {
            # code...
                $nilai = $nilai + $value->total_dev_cost;
            }
        }else{
            $budgets = \App\Budget::where("project_id",$this->project->id)->where("project_kawasan_id",null)->get();
            foreach ($budgets as $key => $value) {
            # code...
                $nilai = $nilai + $value->total_dev_cost;
            }
        }
        return $nilai;
    }

    public function getTotalBudgetConCostAttribute(){
        $nilai = 0;
        foreach ($this->budgets as $key => $value) {
            # code...
            $nilai = $nilai + $value->total_con_cost;
        }
        return $nilai;
    }

    public function getNettoBangunanAttribute(){
        $efektif = 0;
        foreach ($this->units()->where('is_sellable',true)->get() as $key => $unit) 
        {
            $efektif += $unit->bangunan_luas;
        }
        return $efektif;
        
    }

    public function getBlokTypeAttribute(){
        $array = array();
        foreach ($this->units as $key => $value) {
            # code...
            $array[$key] = $value->unit_type_id;
        }
        return array_unique($array);
    }

    public function getBudgetPekerjaanAttribute(){
        $array = array();
        $budgets = Budget::where("project_kawasan_id",$this->id)->first();
        $itempekerjaan = Itempekerjaan::where("group_cost",1)->get();
        $nilai = 0;
        $nilai_awal = 0;
        $start = 0;
        if ( count(Budget::where("project_kawasan_id",$this->id)->get()) > 0 ){
            foreach ($itempekerjaan as $key => $value) {
                if ( $value->parent_id == "" ){
                    $itempekerjaan = Itempekerjaan::where("code","like",$value->code .".%")->get();
                    foreach ($itempekerjaan as $key1 => $value1) {
                        $budgets_detail = $budgets->details->where("itempekerjaan_id",$value1->id);
                        $budgets_tahunan = $budgets->budget_tahunans->first()->where("tahun_anggaran",date("Y"))->get();
                        if ( count($budgets_detail) > 0 ){
                            $nilai = $nilai +  ( $budgets_detail->first()->nilai * $budgets_detail->first()->volume ) ;
                        }

                        if ( count($budgets_tahunan) > 0 ){
                            $budget_awal = $budgets_tahunan->first()->details->where("itempekerjaan_id",$value1->id);
                            if ( count($budget_awal) > 0 ){
                                $nilai_awal = $nilai_awal +  ( $budget_awal->first()->nilai * $budget_awal->first()->volume );
                            }
                        }
                    }
                    $array[$start] = array("label" => $value->name, "budget_awal" => $nilai_awal, "budget_global" => $nilai); 
                    $start++;
                }
                          
            }

            return $array;
        }
        
    }

    public function getTotalKontrakAttribute(){
        $nilai = 0;
        $nilai_project = 0;
        $project = $this->project;
        foreach ($project->spks as $key => $value) {
            # code...
            $nilai_project = $nilai_project + $value->nilai;
        }

        if ( $this->lahan_luas == 0 ){
            return 0;
        }else{
            $proporsional = 0;
            foreach ($project->spks as $key => $value) {
                if ( $value->itempekerjaan != "" ){
                    if ( $value->itempekerjaan != "" ){
                    if ( $value->itempekerjaan->group_cost == 1 ){
                        foreach ($value->details as $key2 => $value2) {
                            //if ( $value->itempekerjaan->group_cost == 1 ){             
                                if ( $value2->asset_id == $this->id ){
                                    //if ( $value->nilai_vo != 0 ){
                                        echo $value->no."<>".$value->nilai."<>".$value->nilai_vo;
                                        echo "\n";
                                    //}
                                    $nilai = $nilai + ( $value->nilai + $value->nilai_vo );
                                }
                            //}
                        }
                    }
                }
                }
                
            }
            $nilai = $proporsional + $nilai;
        }
        
        
        return $nilai;
    }

    public function getHppBrutoAttribute(){
        $nilai = 0;
        if ( $this->total_kontrak == 0 ){
            $nilai = 0;
        }else{
            $nilai = $this->total_kontrak / $this->lahan_luas;
        }
        return $nilai;
    }

    public function getBudgetAwalAttribute(){
        
        //Report Dev Cost (Summary)
        $project = \App\HppDevCostSummaryReport::where("project_kawasan_id",$this->id)->get(); 
        if ( count($project) > 0 ){
            foreach ($project as $key => $value) {
                $HppDevCostSummaryReport = HppDevCostSummaryReport::find($value->id);
                $HppDevCostSummaryReport->total_budget = $value->total_budget;
                $HppDevCostSummaryReport->total_kontrak = $value->total_kontrak;
                $HppDevCostSummaryReport->total_kontrak_terbayar = $this->total_terbayar;

                if ( $this->lahan_sellable != "0.0"){
                    $HppDevCostSummaryReport->hpp_netto = $this->total_budget / $this->lahan_sellable;
                    $HppDevCostSummaryReport->hpp_bruto = $this->total_budget / $this->lahan_luas;

                    $HppDevCostSummaryReport->hpp_kontrak_netto = $this->total_kontrak / $this->lahan_sellable;
                    $HppDevCostSummaryReport->hpp_kontrak_bruto = $this->total_kontrak / $this->lahan_luas;

                    $HppDevCostSummaryReport->hpp_realisasi_netto =  $this->total_terbayar / $this->lahan_luas;
                    $HppDevCostSummaryReport->hpp_realisasi_bruto = $this->total_terbayar / $this->lahan_luas;
                }else{
                    $HppDevCostSummaryReport->hpp_netto = "0.0";
                    $HppDevCostSummaryReport->hpp_bruto = $this->total_budget / $this->lahan_luas;

                    $HppDevCostSummaryReport->hpp_kontrak_netto = "0.0";
                    $HppDevCostSummaryReport->hpp_kontrak_bruto = $this->total_kontrak / $this->lahan_luas;

                    $HppDevCostSummaryReport->hpp_realisasi_netto = "0.0";
                    $HppDevCostSummaryReport->hpp_realisasi_bruto = $this->total_terbayar / $this->lahan_luas;
                }

                $HppDevCostSummaryReport->save();
            }
        } else {
            $HppDevCostSummaryReport = new \App\HppDevCostSummaryReport;
            $HppDevCostSummaryReport->project_id = $this->project->id;
            $HppDevCostSummaryReport->project_kawasan_id = $this->id;
            if ( $this->lahan_sellable == "0.0"){
                $HppDevCostSummaryReport->efisiensi = "0.0";
            }else{
                $HppDevCostSummaryReport->efisiensi = $this->lahan_sellable / $this->lahan_luas;
            }
            $HppDevCostSummaryReport->luas_netto = $this->lahan_sellable;
            $HppDevCostSummaryReport->luas_bruto = $this->lahan_luas;
            $HppDevCostSummaryReport->total_budget = $this->total_budget;            
            $HppDevCostSummaryReport->total_kontrak = $this->total_kontrak;
            $HppDevCostSummaryReport->total_kontrak_terbayar = $this->total_terbayar;
            if ( $this->lahan_sellable != "0.0"){
                $HppDevCostSummaryReport->hpp_netto = $this->total_budget / $this->lahan_sellable;
                $HppDevCostSummaryReport->hpp_bruto = $this->total_budget / $this->lahan_luas;

                $HppDevCostSummaryReport->hpp_kontrak_netto = $this->total_kontrak / $this->lahan_sellable;
                $HppDevCostSummaryReport->hpp_kontrak_bruto = $this->total_kontrak / $this->lahan_luas;

                $HppDevCostSummaryReport->hpp_realisasi_netto =  $this->total_kontrak_terbayar / $this->lahan_luas;
                $HppDevCostSummaryReport->hpp_realisasi_bruto = $this->total_kontrak_terbayar / $this->lahan_luas;
            }else{
                $HppDevCostSummaryReport->hpp_netto = "0.0";
                $HppDevCostSummaryReport->hpp_bruto = $this->total_budget / $this->lahan_luas;

                $HppDevCostSummaryReport->hpp_kontrak_netto = "0.0";
                $HppDevCostSummaryReport->hpp_kontrak_bruto = $this->total_kontrak / $this->lahan_luas;

                $HppDevCostSummaryReport->hpp_realisasi_netto = "0.0";
                $HppDevCostSummaryReport->hpp_realisasi_bruto = $this->total_kontrak_terbayar / $this->lahan_luas;
            }
            $HppDevCostSummaryReport->save();
        }   

        //Report Dev Cost (Detail)
        $project_detail = \App\HppDevCostReport::where("project_kawasan_id",$this->id)->get();        
        if ( count($project_detail) > 0 ){
            foreach ($project_detail as $key6 => $value6) {
                $itempekerjaan = Itempekerjaan::where("parent_id",null)->where("id",$value6->itempekerjaan)->get();      
                foreach ($itempekerjaan as $key => $value) {
                    $nilai = 0;
                    $nilai_kontrak = 0;
                    $code = $value->code;
                    $nilai_bap = 0;
                    $nilai_progress = 0;
                    $child = Itempekerjaan::where("code","like",$code."%")->get();
                    foreach ($child as $key2 => $value2) {                    
                        if ( $this->lahan_sellable > 0  ){               
                            $budgets = $this->budgets->first();  
                            
                        }else{
                            $budgets = $this->project->budgets->where("project_kawasan_id",null)->first();     
                            $spks = $this->project->spks;
                            foreach ($spks as $key3 => $value3) {
                                foreach ($value3->details as $key4 => $value4) {
                                    if ( $value4->asset_type == "App\Project" && ( $value4->asset_id == $this->project->id ) ){
                                        foreach ($value3->progresses as $key5 => $value5) {
                                            if ( $value5->itempekerjaan_id == $value2->id ){
                                                $nilai_kontrak = $value5->nilai + $nilai_kontrak;                                                
                                                $nilai_progress = $value5->progresslapangan_percent;
                                                $nilai_bap = $value5->progressbap_percent ;                          
                                            }
                                        }
                                        
                                        if ( $nilai_progress != 0 ){
                                            $nilai_progress = $nilai_progress / count($value3->progresses);
                                        }

                                        if ( $nilai_bap != 0 ){
                                            $nilai_bap = $nilai_bap / count($value3->progresses);
                                        }
                                    }                                                 
                                }                         
                            }                                       
                        }                    

                    }
                    echo $this->id."<>".$value6->id."<>".$value6->project_kawasan_id."<>".$value6->itempekerjaan."<>".$nilai_kontrak."<>".$nilai_progress."<>".$nilai_bap;
                    echo "\n";

                    $HppDevCostReport = HppDevCostReport::find($value6->id);
                    $HppDevCostReport->project_id = $this->project->id;
                    $HppDevCostReport->project_kawasan_id = $this->id;
                    $HppDevCostReport->kontrak_total = $nilai_kontrak;
                    $HppDevCostReport->progress_lapangan = $nilai_progress;
                    $HppDevCostReport->progress_bap = $nilai_bap;
                    $HppDevCostReport->bap_terbayar_total = $nilai_bap * $nilai_kontrak;
                    $HppDevCostReport->bap_terbayar_tahun = 0;
                    $HppDevCostReport->group_cost = $value->group_cost;
                    $HppDevCostReport->save();
                }
            }
            
        }else{
            $itempekerjaan = Itempekerjaan::where("parent_id",null)->where("group_cost",1)->get();      
            foreach ($itempekerjaan as $key => $value) {
                $nilai = 0;
                $nilai_kontrak = 0;
                $code = $value->code;
                $nilai_bap = 0;
                $nilai_progress = 0;
                $child = Itempekerjaan::where("code","like",$code."%")->get();
                foreach ($child as $key2 => $value2) {                    
                    if ( $this->lahan_sellable > 0 ){               
                        $budgets = $this->budgets; 
                        if (isset($budgets->first()->details)){
                            foreach ($budgets->first()->details as $key3 => $value3) {
                                # code...
                                if ( $value3->itempekerjaan_id == $value2->id ){
                                    $nilai = $nilai + ( $value3->nilai * $value3->volume );
                                }
                            }
                        }   
                        
                    }else{
                        $budgets = $this->project->budgets->where("project_kawasan_id",null)->first();
                        foreach ($budgets->details as $key4 => $value4) {
                            # code...
                            if ( $value4->itempekerjaan_id == $value2->id ){
                                $nilai = $nilai + ( $value4->nilai * $value4->volume );
                            }
                        }
                    }                    
                   
                }

                $HppDevCostReport = new \App\HppDevCostReport;
                $HppDevCostReport->project_id = $this->project->id;
                $HppDevCostReport->project_kawasan_id = $this->id;
                $HppDevCostReport->itempekerjaan = $value->id;
                $HppDevCostReport->budget_awal = $nilai;
                $HppDevCostReport->kontrak_total = $nilai_kontrak;
                $HppDevCostReport->progress_lapangan = $nilai_progress;
                $HppDevCostReport->progress_bap = $nilai_bap;
                $HppDevCostReport->bap_terbayar_total = $nilai_bap * $nilai_kontrak;
                $HppDevCostReport->bap_terbayar_tahun = 0;
                $HppDevCostReport->group_cost = $value->group_cost;
                $HppDevCostReport->save();
            }      
        }

        $spks = $this->project->spks;        
        foreach ($spks as $key => $value) {
            foreach ($value->details as $key2 => $value2) {
                if ( $this->lahan_sellable == "0"){
                    if ( $value2->asset_id == $this->project->id){
                        $costReport = new CostReport;
                        $costReport->project_id = $this->project->id;
                        $costReport->project_kawasan_id  = $this->id;
                        $costReport->spk_id = $value->id;
                        $costReport->itempekerjaan = $value->itempekerjaan->id;
                        $costReport->department = $value->item_pekerjaan->department_id;
                        $costReport->progress_lapangan = $value->nilai_progress * 100;
                        $costReport->progress_bap  = $value->nilai_progress_bap * 100;
                        $costReport->nilai = $value->nilai;
                        $costReport->rekanan = $value->rekanan_id;
                        $costReport->rekanan_type = 1;
                        $costReport->save();
                    }
                }else{
                    $costReport = new CostReport;
                    $costReport->project_id = $this->project->id;
                    $costReport->project_kawasan_id  = $this->id;
                    $costReport->spk_id = $value->id;
                    $costReport->itempekerjaan = $value->itempekerjaan->id;
                    $costReport->department = $value->item_pekerjaan->department_id;
                    $costReport->progress_lapangan = $value->nilai_progress * 100;
                    $costReport->progress_bap  = $value->nilai_progress_bap * 100;
                    $costReport->nilai = $value->nilai;
                    $costReport->rekanan = $value->rekanan_id;
                    $costReport->rekanan_type = 1;
                    $costReport->save();
                }
            }
        }
        

    }
    
    public function HppDevCostReport(){
        return $this->hasMany("App\HppDevCostReport","project_kawasan_id");
    }

    public function getTotalKontrakDevCostAttribute(){
        $nilai = 0;
        foreach ($this->project->spks as $key => $value) {
            # code...
            $spk_detail = $value->details;
            foreach ($spk_detail as $key2 => $value2) {
                # code...
                if ( $value2 != "App\Unit"){
                    $nilai = $nilai + ( $value2->spk->nilai);
                }
            }
        }
        return $nilai;
    }

    public function HppDevCostReportSummary(){
        return $this->hasMany("Modules\Project\Entities\HppDevCostSummaryReport","project_kawasan_id");
    }

    public function cost_report(){
        return $this->hasMany("Modules\Project\Entities\CostReport");
    }

    public function unit_type(){
        return $this->hasMany("Modules\Project\Entities\UnitType","cluster_id")->where('deleted_at',null);;
    }

    public function getTotalKontrakProporsionalAttribute(){
        $nilai = 0;
        $summary_kontrak = $this->project->dev_cost_only;

        foreach ($this->project->spks as $key => $value) {
            if ( $value->itempekerjaan != "" ){
                if ( $value->itempekerjaan->group_cost == 1 ){  
                    if ( $value->tender->rab->budget_tahunan != "" ){
                        if ( $value->tender->rab->budget_tahunan->kawasan != "" ){
                            if ( $value->tender->rab->budget_tahunan->kawasan->id == $this->id ){
                                $nilai = $value->nilai + $nilai;
                            }
                        }
                    }
                }
            }
        }

        //proporsional 
        if ( $this->lahan_luas > 0 ){
            $proporsional = $summary_kontrak * (  $this->lahan_luas / $this->project->luas ); 
        }else{
            $proporsional = 0;
        }
        return $nilai + $proporsional;
    }

    public function getTotalTerbayarProporsionalAttribute(){
        $nilai = 0;
        $summary_terbayar = $this->project->nilai_realisasi;

        
        //proporsional 
        if ( $this->lahan_luas > 0 ){
            $proporsional = $summary_terbayar * (  $this->lahan_luas / $this->project->luas ); 
        }else{
            $proporsional = 0;
        }
        return $nilai + $proporsional;
    }

    public function budget_tahunan(){
        return $this->hasMany("Modules\Budget\Entities\BudgetTahunan");
    }

    public function getTotalBudgetTahunan($year){
        $nilai = 0;
        if ( $this->budget_tahunan != "" ){
            if ( $year == "" ){
                $nilai = $this->budget_tahunan->last()->nilai;
            }else{
                foreach ($this->budget_tahunan as $key => $value) {
                    if ( $value->tahun_anggaran == $year){
                        $nilai = $value->nilai;
                    }
                }
            }   
        }
        

        return $nilai;
    }

    public function getTotalKontrakTahunProporsionalAttribute($year){
        $nilai = 0;
        $nilai_proporsional = 0;
        if ( $year == "" ){
            foreach ($this->project->spks as $key => $value) {
                if ( $value->itempekerjaan != "" ){
                    if ( $value->itempekerjaan->group_cost == 1 ){                    
                        if ( $value->date->format("Y") == date("Y")){
                            $nilai_proporsional = $value->nilai + $nilai_proporsional;
                        }
                    }
                }
            }

            foreach ($this->project->spks as $key => $value) {
                if ( $value->itempekerjaan->group_cost == 1 ){   
                    if ( $value->tender->rab->budget_tahunan != "" ){
                        if ( $value->tender->rab->budget_tahunan->kawasan != "" ){
                            if ( $value->tender->rab->budget_tahunan->kawasan->id == $this->id ){
                                if ( $value->date->format("Y") == date("Y")){
                                    $nilai = $nilai + $value->nilai;
                                }
                            }
                        }
                    }
                }
            }
        }
        if ( $this->lahan_luas > 0 ){
            $proporsional = $nilai_proporsional * (  $this->lahan_luas / $this->project->luas ); 
        }else{
            $proporsional = 0;
        }

        return $nilai + $proporsional;
    }

    public function getNilaiTerbayarTahunAttribute(){
        $nilai = 0;
        foreach ($this->project->spks as $key => $value) {
            if ( $value->itempekerjaan->group_cost == 1 ){  
                foreach ($value->baps as $key2 => $value2) {
                    if ( $value2->date->format("Y") == date("Y") ){
                        foreach ($value2->vouchers as $key3 => $value3) {
     
                            if ( $value3->pencairan_date != "" ){
                                $nilai = $nilai + $value3->nilai;
                            }
                        }
                    }
                }
            }
        }
        if ( $this->lahan_luas > 0 ){
            $proporsional = $nilai * (  $this->lahan_luas / $this->project->luas ); 
        }else{
            $proporsional = 0;
        }

        return $proporsional;
    }
    
    public function getNilaiLalaAttribute(){
        $total_faskot = 0;
        foreach ($this->project->budgets as $key => $value) {
            if ( $value->project_kawasan_id == NULL ) {
                if ( $value->deleted_at == NULL ){
                    $total_faskot = $value->total_dev_cost;
                }
            }
        }

        return $total_faskot;
    }

     public function getTotalKontrakConCostAttribute(){
        $nilai = 0;
       

        if ( $this->lahan_luas == 0 ){
            return 0;
        }else{
            $proporsional = 0;
            foreach ($this->project->spks as $key => $value) {
                if ( $value->itempekerjaan != "" ){
                    if ( $value->itempekerjaan->group_cost == 2 ){ 
                        if ( $this->id == $value->project_kawasan_id ){
                          
                            $nilai = $nilai + ($value->nilai + $value->nilai_vo);
                        }
                    }
                }
            }


        }
        
        
        return $nilai;
    }

    public function getExcelSpkAttribute(){
        foreach ($this->project->spks as $key => $value) {
            if ( $value->itempekerjaan != "" ){
                if ( $value->itempekerjaan->group_cost == 1 ){                    
                    foreach ($value->details as $key2 => $value2) {
                        if ( $value2->asset_id == $this->id ){
                          
                        }
                    } 
                }
            }       
        }    
    }

    public function unitarahs(){
        return $this->hasMany("Modules\Project\Entities\UnitArah");
    }
    public function workorder(){
        return $this->hasMany('Modules\Workorder\Entities\Workorder','kawasan_id');
    }

    public function getNettoKawasanBaruAttribute(){
        $efektif = 0;
        foreach ($this->bloks as $key => $blok) 
        {
            $efektif += $blok->units->where('is_sellable',true)->sum('tanah_luas');
        }
        // $efektif = $this->units()->where('is_sellable',true)->sum('tanah_luas');
        return $efektif;
        
    }

    public function jumlah_unit(){
        return $this;
    //                 ->join('bloks','bloks.project_kawasan_id','project_kawasans.id')
    //                 // ->join('units','units.blok')
    //                 ->select('*')->get();
    }
}