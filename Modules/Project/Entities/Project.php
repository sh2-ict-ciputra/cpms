<?php

namespace Modules\Project\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Project\Entities\HppDevCostSummaryReport;
use Modules\Project\Entities\HppConCostSummaryReport;
use Modules\Project\Entities\CostReport;
use Modules\Spk\Entities\Spk;
use Modules\Pekerjaan\Entities\ItempekerjaanHarga;
use Modules\Pekerjaan\Entities\ItempekerjaanHargaDetail;
use Modules\Project\Entities\UnitChangedPrices;

class Project extends Model
{
     protected $fillable = ['subholding','contactperson','code','name','luas','address','zipcode','npwp','phone','fax','email','description','city_id'];


    public function contactperson()
    {
        return $this->belongsTo('Modules\User\Entities\User', 'contactperson');
    }

    public function city()
    {
        return $this->belongsTo('Modules\Country\Entities\City');
    }

    public function kawasans()
    {
        return $this->hasMany('Modules\Project\Entities\ProjectKawasan')->where('deleted_at',null);
    }

    public function budgets()
    {
        return $this->hasMany('Modules\Budget\Entities\Budget')->where("deleted_at",null);
    }

    public function budgets_all()
    {
        return $this->hasMany('Modules\Budget\Entities\Budget');
    }

    public function budget_tahunans()
    {
        return $this->hasManyThrough('Modules\Budget\Entities\BudgetTahunan', 'Modules\Budget\Entities\Budget');
    }

    public function getWorkordersAttribute()
    {
        return \Modules\Workorder\Entities\Workorder::where( 'budget_tahunan_id',  $this->id )->where("deleted_at", null)->orderBy("id","desc")->get();
    }

    public function getPurchaseRequestsAttribute()
    {
        return \Modules\PurchaseRequest\Entities\PurchaseRequest::where( 'project_for_id',  $this->id )->orderBy("id","desc")->get();
    }

    public function getOwnerEstimatesAttribute()
    {
        return \Modules\TenderPurchaseRequest\Entities\TenderPurchaseRequestGroupRekanan::where( 'project_for_id',  $this->id )->orderBy("id","desc")->get();
    }

    public function getTenderPenawaransAttribute()
    {
        $result = [];
        $getDataPenawarans = \Modules\TenderPurchaseRequest\Entities\TenderPurchaseRequestPenawaran::where('project_for_id', $this->id)->select('id_tender_purchase_request_group_rekanan')->distinct()->get();
        foreach ($getDataPenawarans as $key => $value) {
            $getData = \Modules\TenderPurchaseRequest\Entities\TenderPurchaseRequestPenawaran::where('project_for_id', $this->id)->where('id_tender_purchase_request_group_rekanan',$value->id_tender_purchase_request_group_rekanan)->get();
            $checkStatus = [];
            foreach ($getData as $key => $nilai) {
                # code...
                $status_approval = \Modules\Approval\Entities\Approval::where([['document_id', '=', $nilai->id], ['document_type', '=', 'Modules\TenderPurchaseRequest\Entities\TenderPurchaseRequestPenawaran']])->first();
    
                if ($status_approval->approval_action_id == 2) {
                    array_push($checkStatus, 0);
                }
                if ($status_approval->approval_action_id == 6) {
                    $check_pemenang_tender = \Modules\TenderPurchaseRequest\Entities\TenderMenangPR::where('id_penawaran', $nilai->id)->first();
                    if ($check_pemenang_tender != null) {
                        array_push($checkStatus, 1);
                    }
                }
            }

            if(in_array(1,$checkStatus)){
                $status = 6;
            }elseif(count($checkStatus) <= 0){
                $status = 2;
            }elseif($checkStatus == null){
                $status = 1;
            }
            $MAXgetDataPenawarans = \Modules\TenderPurchaseRequest\Entities\TenderPurchaseRequestPenawaran::where('id_tender_purchase_request_group_rekanan', $value->id_tender_purchase_request_group_rekanan)->max('penawaran');
            for ($i = 1; $i <= $MAXgetDataPenawarans; $i++) {
                # code...
                $arr = [
                    'no_tender' => $value->tender_purchase_request_group_rekanan->no,
                    'penawaran' => $i,
                    'status' => $status
                ];
                array_push($result, $arr);
            }
        }

        return $result;
    }

    public function getPurchaseOrdersAttribute()
    {
        return \Modules\TenderPurchaseRequest\Entities\PurchaseOrder::where( 'project_for_id',  $this->id )->orderBy("id","desc")->get();
    }

    public function getProjectAttribute()
    {
        return $this;
    }

    public function getRabsAttribute()
    {
        return \Modules\Rab\Entities\Rab::whereHas( 'workorder', function($workorder){
            $workorder->whereHas('budget_tahunan', function($budgettahunan){
                $budgettahunan->whereHas('budget', function($budget){
                    $budget->where('project_id', $this->id);
                });
            });
        })->get();
    }

    public function getTendersAttribute()
    {
        return 
        \Modules\Tender\Entities\Tender::whereHas('rab', function($rab){ 
            $rab->whereHas( 'workorder', function($workorder){
                $workorder->where('budget_tahunan_id', $this->id);
            });
        });
    }

    public function getHppBudgetAttribute()
    {
        $nilai = 0;

        foreach ($this->kawasans as $key => $kawasan) 
        {
            $nilai = $nilai + $kawasan->hpp_budget;
        }

        return $nilai;
    }

    public function spks()
    {
        return $this->hasMany('Modules\Spk\Entities\Spk');
    }

    public function bloks()
    {
        return $this->hasManyThrough('Modules\Project\Entities\Blok', 'Modules\Project\Entities\ProjectKawasan');
    }

    public function workorder_details()
    {
        return $this->morphMany('Modules\Workorder\Entities\WorkorderDetail', 'asset');
    }
    public function rab_units()
    {
        return $this->morphMany('Modules\Rab\Entities\RabUnit', 'asset');
    }
    public function tender_units()
    {
        return $this->morphMany('Modules\Tender\Entities\TenderUnit', 'asset');
    }
    public function spk_details()
    {
        return $this->morphMany('Modules\Spk\Entities\SpkDetail', 'asset');
    }
    public function progresses()
    {
        return $this->morphMany('App\UnitProgress', 'unit');
    }
    public function getCalculateBobotAttribute()
    {
        $total = 0;

        $progresses = $this->progresses()->whereHas('spkvo_unit')->where('is_pembangunan', true)->get();

        foreach ($progresses as $key => $each) 
        {
            $total = $total + ($each->nilai * $each->volume);
        }

        foreach ($progresses as $key => $detail) 
        {
            if ($detail->is_pembangunan) {
                # code...
                $detail->update([
                    'bobot'=> $detail->nilai * $detail->volume / $total
                ]);
            }else{
                $detail->update([
                    'bobot'=> 0
                ]);
            }
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

    public function getNilaiBudgetAttribute()
    {
        $nilai = array();

        foreach ($this->budgets->where('project_kawasan_id', null) as $key => $each) 
        {
            $nilai[$key] = $each->nilai;
        }

        return array_sum($nilai);
    }

    public function units()
    {
        return \Modules\Project\Entities\Unit::whereHas('blok', function($q){
            $q->whereHas('kawasan', function($r){
                $r->where('project_id', $this->id);
            });
        });
    }
    public function getUnitsAttribute()
    {
        return $this->units()->get();
    }

    public function getEfisiensiAttribute()
    {
        if ($this->luas > 0) 
        {
            return $this->units()->where('is_sellable',true)->sum('tanah_luas') / $this->luas;
        }else{
            return 0;
        }
    }

    public function getNilaiKontrakAttribute(){}
    public function getDevCostBudgetBrutoAttribute()
    {
        $nilai = 0;

        foreach ($this->kawasans as $key => $kawasan) 
        {
            $nilai += $kawasan->dev_cost_budget_netto;
        }

        if ($this->luas > 0) 
        {
            return $nilai / $this->luas;
        }else{
            return 0;
        }

    }
    public function getDevCostBudgetNettoAttribute()
    {
        if ($this->efisiensi > 0) 
        {
            return $this->dev_cost_budget_bruto / $this->efisiensi;
        }else{
            return 0;
        }
    }
    public function getDevCostKontrakBrutoAttribute(){}
    public function getDevCostKontrakNettoAttribute(){}
    public function getDevCostRealisasiBrutoAttribute(){}
    public function getDevCostRealisasiNettoAttribute(){}
    
    public function getConCostBudgetAttribute(){
        $nilai = 0;
        foreach ($this->budgets as $key => $value) {
            $nilai = $value->total_con_cost + $nilai ;        
        }
        return $nilai;
    }
    
    public function getConCostKontrakAttribute(){}
    public function getConCostRealisasiAttribute(){}


    public function getConCostAttribute()
    {
        $nilai = 0;

        foreach ($this->units as $key => $unit) 
        {
            $nilai += $unit->con_cost;
        }

        return $nilai;
    }

    public function getDevCostAttribute()
    {
        $nilai = 0;

        foreach ($this->progresses as $key => $progress) 
        {
            $nilai += $progress->nilai * $progress->volume;
        }

        return $nilai;
    }


    public function pt_user(){
        return $this->hasMany("Modules\Project\Entities\ProjectPtUser");
    }

    public function progress(){
        return $this->hasMany("App\UnitProgress");
    }

    public function unit_progress($itemid){
        return $this->hasMany('App\UnitProgress')->where('project_id',$this->id)->where('itempekerjaan_id',$itemid)->get();
    }

    public function unit_progressbyYear($itemid,$year){
        return $items = $this->hasMany('App\UnitProgress')->where('project_id',$this->id)->where('itempekerjaan_id',$itemid)->whereYear('created_at',$year)->get();
    }

    public function nilai_kontrak($itemid){
        $nilai = 0;
        $items = $this->unit_progress($itemid);
        foreach ( $items as $value ){
            $nilai = $nilai + $value->nilai;
        }
        return $nilai;
    }

    public function nilai_year_kontrak($itemid,$year){
        $nilai = 0;
        $items = $this->unit_progress($itemid,$year);
        foreach ( $items as $value ){
            $nilai = $nilai + $value->nilai;
        }
        return $nilai;
    }

    public function nilai_percentage($itemid){
       return $items = $this->unit_progress($itemid)->avg('progresslapangan_percent');        
    }

    public function nilai_by_year_percentage($itemid,$year){
        return $items = $this->unit_progressbyYear($itemid,$year)->avg('progresslapangan_percent');     
    }

    public function nilai_bap_terbayar($itemid){
        $nilai = 0;
        $items = $this->unit_progress($itemid);
        foreach ($items as $value) {
            $nilai =  $nilai + (  ( $value->nilai * $value->progressbap_percent ) * $value->volume ) ;
        }
        return $nilai;
    }

    public function nilai_bap_year_terbayar($itemid,$year){
        $nilai = 0;
        $items = $this->unit_progressbyYear($itemid,$year);
        foreach ($items as $value) {
            $nilai =  $nilai + (  ( $value->nilai * $value->progressbap_percent ) * $value->volume ) ;
        }
        return $nilai;
    }

    public function getSellUnitAttribute()
    {
        return $this->units()->where('is_sellable',true)->sum('tanah_luas');
    }

    public function getKawasanAttribute(){
        $nilai = 0;
        foreach ($this->kawasans as $key => $value) {
            # code...
            $efisiensi = $nilai + $value->lahan_luas;
        } 
        return $nilai;
    }

    public function getNilaiBudgetTahunAwalAttribute(){
        $nilai = 0;
        foreach ($this->kawasans as $each ) {
            # code...
            foreach ( $each->budgets as $key2 => $each2 ){
                foreach ( $each2->details as $key3 => $each3 ){
                    $nilai = $nilai + $each3->itempekerjaan->budget_tahunan->first()->nilai;
                }
            }
        }
        return $nilai;
    }

    public function getNilaiBudgetAwalAttribute(){
        $nilai = 0;
        foreach ($this->kawasans as $each ) {
            # code...
            foreach ( $each->budgets as $key2 => $each2 ){
                foreach ( $each2->details as $key3 => $each3 ){
                    $nilai = $nilai + $each3->nilai;
                }
            }
        }
        return $nilai;
    }

    public function getNilaiBudgetTotalAttribute()
    {
        $nilai = 0;

        foreach ( $this->budgets as $key => $each ) 
        {
            if ( $each->deleted_at == null ){
                foreach ($each->details as $key2 => $value2 ) {
                    # code...
                    $nilai = ( $value2->nilai * $value2->volume ) + $nilai;
                }
            }
            
        }

        return $nilai;
    }

    public function getApprovalSuccessAttribute(){
        $result = array();
        $start = 0;
        $start_date = date("Y-m-d H:i:s");
        $array_class = array(
            "6" => array("class" => "label label-success", "status" => "Disetujui"),
            "7" => array("class" => "label label-danger", "status" => "Ditolak"),
            "1" => array("class" => "label label-warning", "status" => "Dalam Pengerjaan"),
            "2" => array("class" => "label label-warning", "status" => "Delivered")
        );
        //Get All Workorder
        foreach ($this->workorders as $key => $value) {
            if ( $value->approval != "" ){
                if ( $value->approval->approval_action_id != 1 ){
                    if ( count($value->detail_pekerjaan) > count($value->rabs) ){
                        $result[$start] = array ("document_type" => "Workorder","url" => "/workorder/detail/?id=",  "id" => $value->id, "name" => $value->name, "class" => $array_class[$value->approval->approval_action_id]['class'], "status" => $array_class[$value->approval->approval_action_id]['status'], "last_updated" => date("d/M/Y",strtotime($value->approval->updated_at)) );
                        $start++;
                    }
                }
            }else{
                if ( (count($value->detail_pekerjaan) > count($value->rabs)) || count($value->detail_pekerjaan) <= 0 ){
                    $result[$start] = array ("document_type" => "Workorder","url" => "/workorder/detail/?id=",  "id" => $value->id, "name" => $value->name, "class" => "label label-warning", "status" => "Dalam Pengerjaan", "last_updated" => date("d/M/Y",strtotime($value->created_at) ));
                    $start++;
                }
            }
        }

        //Get All Rab

        //"idpkr" => $value->workorder_budget_detail->itempekerjaan_id,
        foreach ($this->workorders as $key2 => $value2) {
            foreach ($value2->rabs as $key => $value) {
                if ( $value->approval != "" ){
                    if ( $value->approval->approval_action_id != 1 ){
                        if ( count($value->tenders) <= 0 ){
                            $result[$start] = array ( "document_type" => "RAB / OE WO", "url" => "/rab/detail/?idpkr=".$value->workorder_budget_detail->itempekerjaan->id."&id=", "id" => $value->id, "name" => $value->name, "class" => $array_class[$value->approval->approval_action_id]['class'], "status" => $array_class[$value->approval->approval_action_id]['status'], "last_updated" => date("d/M/Y",strtotime($value->approval->updated_at) ));
                            $start++;
                        }
                    }
                }else{
                    //if ( $value->tenders != "" ){
                        $result[$start] = array ( "document_type" => "RAB / OE WO", "url" => "/rab/detail/?idpkr=".$value->workorder_budget_detail->itempekerjaan->id."&id=", "id" => $value->id, "name" => $value->name, "class" => "label label-warning", "status" => "Dalam Pengerjaan", "last_updated" => date("d/M/Y",strtotime($value->updated_at) ));
                        $start++;
                    //}
                }
            }
        }
        
        //Get All Tender
        foreach ($this->tenders->get() as $key => $value) {
            $approval_rekanan = 0;
            if ( $value->approval != "" ){
                if ( $value->approval->approval_action_id != 1 ){
                    if ( $value->approval->approval_action_id == 6 ){
                        if ( count($value->spks) <= 0 ){
                            $result[$start] = array( "document_type" => "Tender WO", "url" => "/tender/detail?id=", "id" => $value->id, "name" => $value->name, "class" => $array_class[$value->approval->approval_action_id]['class'], "status" => "Pemenang telah dipilih");
                            $start++;
                        }
                    }elseif ( $value->approval->approval_action_id == 6 ){
                        if ( count($value->spks) <= 0 ){
                            $result[$start] = array( "document_type" => "Tender WO", "url" => "/tender/detail?id=", "id" => $value->id, "name" => $value->name, "class" => $array_class[$value->approval->approval_action_id]['class'], "status" => "Pemenang Ditolak");
                            $start++;
                        }
                    }

                    
                }elseif ( $value->approval->approval_action_id == 1) {
                    foreach ($value->rekanans as $key2 => $value2) {
                        if ( $value2->approval != "" ){
                            if ( $value2->approval->approval_action_id == "6" || $value2->approval->approval_action_id == "7" ){
                                $approval_rekanan++;
                            }
                        }
                    }

                    if ( $approval_rekanan == count($value->rekanans)){
                        if ( count($value->spks) <= 0 ){
                            $result[$start] = array( "document_type" => "Tender WO", "url" => "/tender/detail?id=", "id" => $value->id, "name" => $value->name, "class" => "label label-success" , "status" => "Rekanan telah direview");
                            $start++;
                        }
                    }
                }
            }else{
                if ( count($value->spks) <= 0 ){
                    $result[$start] = array( "document_type" => "Tender WO", "url" => "/tender/detail?id=", "id" => $value->id, "name" => $value->name, "class" => "label label-warning", "status" => "Dalam Pengerjaan" );
                    $start++;
                }
            }
        }

        //Get All PR
        foreach ($this->purchaserequests as $key => $value) {
            if ( $value->approval != "" ){
                $result[$start] = array ("document_type" => "PurchaseRequest","url" => "/purchaserequest/detail/?id=",  "id" => $value->id, "name" => $value->no, "class" => $array_class[$value->approval->approval_action_id]['class'], "status" => $array_class[$value->approval->approval_action_id]['status'] );
                $start++;
            }
        }

        //Get All OE
        foreach ($this->ownerestimates as $key => $value) {
            if ( $value->approval != "" ){
                $result[$start] = array ("document_type" => "OE PR","url" => "/tenderpurchaserequest/detail_oe/?id=",  "id" => $value->no, "name" => $value->no, "class" => $array_class[$value->approval->approval_action_id]['class'], "status" => $array_class[$value->approval->approval_action_id]['status'] );
                $start++;
            }
        }

        //Get All Tender Penawaran
        foreach ($this->tenderpenawarans as $key => $value){
            $result[$start] = array ("document_type" => "Tender PR","url" => "/tenderpurchaserequest/detailTender?id=".$value['no_tender']."&penawaran=",  "id" => $value['penawaran'], "name" => $value['no_tender']." Penawaran ".$value['penawaran'],  "class" => $array_class[$value['status']]['class'], "status" => $array_class[$value['status']]['status']  );
            $start++;
        }

        //Get All PO
        // foreach ($this->purchaseorders as $key => $value) {
        //     if ( $value->approval != "" ){
        //         $result[$start] = array ("document_type" => "PurchaseOrder","url" => "/purchaseorder/detail/?id=",  "id" => $value->id, "name" => $value->no, "class" => $array_class[$value->approval->approval_action_id]['class'], "status" => $array_class[$value->approval->approval_action_id]['status'] );
        //         $start++;
        //     }
        // }

        return $result;
    }

    public function getNettoAttribute(){
        $nilai = 0;
        foreach ($this->kawasans as $key => $value) {
            # code...
            //$nilai = $nilai + $value->lahan_sellable;
      
            $nilai = $nilai + $value->netto_kawasan;
        }
        return $nilai;
    }

    public function unittype(){
        return $this->hasMany("Modules\Project\Entities\UnitType");
    }

    public function getHppDevCostReportAttribute(){
        foreach ($this->kawasans as $key => $value) {
            if ( $value->is_kawasan == "1"){
                $status = $value->budget_awal;
            }
        }
    }

    public function voucher(){
        return $this->hasMany("Modules\Voucher\Entities\Voucher");
    }

    public function getBudgetDepartmentAttribute(){
        $dept = array();
        foreach ($this->budgets as $key => $value) {
           $dept[$key] = $value->department->id; 
        }

        return array_values(array_unique($dept));
    }

    public function getNilaiTotalBapAttribute(){
        $nilai = 0;
        foreach ($this->spks as $key => $value) {
            $nilai = $nilai + $value->terbayar_verified;
        }
        return $nilai;
    }

    public function getPercentageBudgetAttribute(){
        $nilai = 0;
        /*if ( $this->nilai_budget_total == 0 ){
            return $nilai ;
        }else{
            $nilai = $this->nilai_total_bap / $this->nilai_budget_total;
        }*/
        foreach ($this->voucher as $key => $value) {
            if ( $value->date->format("Y") == date("Y")){
                $nilai = $nilai + $value->nilai;
            }
        }

        if ( $this->total_budget > 0 ){
            return ( $nilai / $this->total_budget ) * 100 ;
        }else{
            return 0;
        }

        //return $nilai;
    }

    public function getTotalRekananAttribute(){
        $nilai = array();
        foreach($this->spks as $key => $value){
            $nilai[$key] = $value->rekanan_id;
        }

        return array_unique($nilai);
    }

    public function getTotalBapAttribute(){
        $nilai = 0;
        foreach ($this->spks as $key => $value) {
            $nilai = $nilai + $value->baps->count();
        }

        return $nilai;
    }

    public function getHppNettoAttribute(){
        $nilai = 0;
        if ( $this->netto <= 0 ){
            return $nilai;
        }else{
            return $this->total_budget_dev_cost / $this->netto;
        }
    }

    public function getTotalBudgetDevCostAttribute(){
        $nilai = 0;
        foreach ($this->budgets as $key => $value) {
            $nilai = $nilai + $value->total_dev_cost;
        }
        return $nilai;
    }

    public function hpp_update(){
        return $this->hasMany("Modules\Budget\Entities\HppUpdate");
    }

    public function getDevCostOnlyAttribute(){
        $nilai_kontrak = 0;
        $asa = array();
        
        if ( count($this->summary_kontrak) > 0 ){
            $nilai_kontrak = $this->summary_kontrak->sum("total_kontrak");
        }else{
            return 0;
        }

        

        return $nilai_kontrak;
    }

    public function getDevCostTerbayarAttribute(){
        $nilai = 0;
        $devcost = $this->dev_cost_only;
        
        if ( count($this->summary_kontrak) > 0 ){
            $nilai = $this->summary_kontrak->sum("total_kontrak_terbayar");
        }else{
            return 0;
        }
        return $nilai;
    }

    
    public function hadap(){
        return $this->hasMany("Modules\Project\Entities\UnitArah");
    }

    public function getHppNettoAwalAttribute(){
        $update = $this->hpp_update;
        if ( count($update) > 0 ){
            if ( $this->hpp_update->first()->netto > 0 ){
                $hpp = $this->hpp_update->first()->nilai_budget / $this->hpp_update->first()->netto;
                return $hpp;
            }else {
                return 0 ;
            }
        }else{
            return 0;
        }
    }

    public function getHppNettoAkhirAttribute(){
        $update = $this->hpp_update;
        if ( count($update) > 0 ){
            if ( $this->hpp_update->last()->netto > 0 ){
                $hpp = $this->hpp_update->last()->nilai_budget / $this->hpp_update->last()->netto;
                return $hpp;
            }else{
                return 0;
            }
        }else{
            return 0;
        }
    }

    public function summary_kontrak(){
        return $this->hasMany("Modules\Project\Entities\HppDevCostSummaryReport");
    }

    public function all_budgets(){
        return $this->hasMany('Modules\Budget\Entities\Budget');
    }

    public function getUnitTerjualAttribute(){
        $nilai = 0;
        foreach ($this->kawasans as $key => $value) {
            foreach ($value->bloks as $key2 => $value2) {
                foreach ($value2->units as $key3 => $value3) {
                    if ( $value3->is_sellable == 1 ){
                        $nilai = $value3->tanah_luas + $nilai;
                    }
                }
            }
        }

        return $nilai;
    }

    public function getUnitRencanaAttribute(){
        //Get Unit Planning

        $nilai = 0;
        foreach ($this->kawasans as $key => $value) {
            foreach ($value->bloks as $key2 => $value2) {
                foreach ($value2->units as $key3 => $value3) {
                    if ( $value3->status == 1 ){
                        $nilai = $value3->tanah_luas + $nilai;
                    }
                }
            }
        }

        return $nilai;
    }

    public function getUnitSaleAttribute(){
        //Get Unit Ready for Sale

        $nilai = 0;
        foreach ($this->kawasans as $key => $value) {
            foreach ($value->bloks as $key2 => $value2) {
                foreach ($value2->units as $key3 => $value3) {
                    if ( $value3->status == 0 ){
                        $nilai = $value3->tanah_luas + $nilai;
                    }
                }
            }
        }

        return $nilai;
    }

    public function getDevCostDibebankanAttribute(){
        $nilai = 0;
        foreach ($this->budgets as $key => $value) {
            if ( $value->deleted_at == null ){
                $nilai = $value->total_dev_cost + $nilai ;
            }
        }

        if ( $this->netto <= 0 ){
            $hpp_akhir = 0;
        }else{
            $hpp_akhir = $nilai / $this->netto;
        }

        $nilai_dev_cost = 0;
        foreach ($this->hpp_update as $key => $value) { 
            $nilai_dev_cost = $nilai_dev_cost + ( $value->hpp_book * $value->luas_book);
        }
        return $nilai_dev_cost;

        if ( count($this->hpp_update) > 0 ){           
            return ( $this->hpp_update->last()->luas_book * $hpp_akhir ) + $nilai_dev_cost;
        }else{
            return 0;
        }
    }

    public function getPersediaanDevCostAttribute(){
        return $this->dev_cost_terbayar - $this->dev_cost_dibebankan;
    }

    public function getHutangBayarAttribute(){
        return $this->dev_cost_only - $this->dev_cost_terbayar;
    }

    public function getHutangBangunAttribute(){
        $rev = 0;
        $nilai_budget = 0;
        $kontrak = $this->dev_cost_only;
        foreach ($this->budgets_all as $key => $value) {
            if ( $value->deleted_at == null ){
                $nilai_budget = $nilai_budget + $value->total_dev_cost;
            }else{
                $rev = $rev + 1;
            }
        }
        
        if ( $rev > 0 ){
            return $this->total_budget - $kontrak;
        }else{            
            return $this->total_budget_dev_cost - $this->total_nilai_kontrak ;
        }
    }

    public function getSalesBackLogAttribute(){
        $total_book = 0;
        if ( count($this->hpp_update) > 0 ){
            $nilai = 0;

            foreach ($this->hpp_update as $key => $value) {
                $array[$value->luas_book] = $value->luas_book;
            }

            $total_book =  array_sum(array_unique($array));
            return ( $this->hpp_update->last()->luas_erem - $total_book );
        }else{
            return 0;
        }
    }

    public function getTotalStockAttribute(){
        if ( count($this->hpp_update) > 0 ){
            return $this->luas_rencana_netto_hpp + $this->sales_back_log;
        }else{
            return 0;
        }
    }

    public function getHppDevCostUpdAttribute(){
        if ( $this->total_stock <= 0 ){
            return 0;
        }else{
            return $this->total_dev_cost / $this->total_stock;
        }
    }

    public function pt(){
        return $this->hasMany("Modules\Project\Entities\ProjectPt")->where("deleted_at", null);
    }

    public function getUpdateHppAttribute(){
        $nilai_budget = 0;
        foreach ($this->budgets as $key => $value) {
            $nilai_budget = $value->total_dev_cost + $nilai_budget;
        }
        
        $hpp_update = $this->hpp_update;
        if ( count($hpp_update) > 0  ){
            $luas_book = $hpp_update->last()->luas_book;
            $luas_erem = $hpp_update->last()->luas_erem;
        }else{
            $luas_book = 0;
            $luas_erem = 0;
        }

        $hpp_update = new \Modules\Budget\Entities\HppUpdate;
        $hpp_update->project_id = $this->id;
        $hpp_update->nilai_budget = $nilai_budget;
        $hpp_update->luas_book = $luas_book;
        $hpp_update->luas_erem = $luas_erem;
        $hpp_update->created_at = date("Y-m-d H:i:s.u");
        $hpp_update->created_by = 1;
        $hpp_update->netto = $this->netto;
        $hpp_update->save();

        foreach ($this->budgets as $key => $value) {
            $hpp_update_detail = new \Modules\Budget\Entities\HppUpdateDetail;
            $hpp_update_detail->hpp_update_id = $hpp_update->id;
            $hpp_update_detail->budget_id = $value->id;
            $hpp_update_detail->created_at = date("Y-m-d H:i:s.u");
            $hpp_update_detail->created_by = 1;
            $hpp_update_detail->save();
        }
    }

    public function getLuasGrossHppAttribute(){
        $nilai = 0;
        if ( $this->luas_rencana_netto_hpp > 0 && $this->netto > 0 && $this->luas > 0 ){
            $nilai =  $this->luas_rencana_netto_hpp / ( ( $this->netto / $this->luas )) ; 
        }

        return $nilai;
    }

    public function getLuasRencanaNettoHppAttribute(){
        $nilai = 0;
        
        if ( count($this->hpp_update) > 0 ){
            $luas_erem  = $this->hpp_update->last()->luas_erem;
            $nilai = $this->netto - $luas_erem;
        }
        return $nilai;
    }

    public function getTotalNilaiKontrakAttribute(){
        $nilai = 0;
        /*foreach ($this->spks as $key => $value) {
            if ( $value->itempekerjaan != "" ){
                if ( $value->itempekerjaan->group_cost == 1 ){
                    $nilai = $nilai + ( $value->nilai + $value->nilai_vo);
                }
            }
   
        }*/
        foreach ($this->hpp_dev_cost_summary_report as $key => $value) {
            $nilai = $value->total_kontrak + $nilai;
        }
        return $nilai;
    }

    public function getTotalBudgetAttribute(){
        $kontrak = $this->dev_cost_only;
        $kontrak = $this->total_nilai_kontrak;
        $nilai_budget = 0;
        $rev = 0;
        foreach ($this->budgets_all as $key => $value) {
            if ( $value->deleted_at == null ){
                $nilai_budget = $nilai_budget + $value->total_dev_cost;
            }else{
                $rev = $rev + 1;
            }
        }

        if ( $rev > 0 ){
            return $nilai_budget ;
        }else{
            return $nilai_budget;
        }
    }

    public function history_luas(){
        return $this->hasMany("Modules\Project\Entities\ProjectHistory")->orderBy('id','DESC');
    }

    public function getNilaiRealisasiAttribute(){
        $nilai = 0;
        foreach ($this->voucher as $key => $value) {
            if ( $value->pencairan_date != "" ){
                $nilai = $nilai + $value->nilai;
            }
        }

        return $nilai;
    }

    public function getBudgetDevCostAwalAttribute(){
        $nilai = 0;
        if ( count($this->hpp_update) > 0 ){
            $nilai = $this->hpp_update->first()->nilai_budget;
        }
        return $nilai;
    }

    public function getBudgetDevCostUpdAttribute(){
        $nilai = 0;
        if ( count($this->hpp_update) > 0 ){
            $nilai = $this->hpp_update->last()->nilai_budget;
        }
        return $nilai;
    }

    public function getHppDevCostAwalNettoAttribute(){
        $nilai = 0;
        if ( count($this->hpp_update) > 0 ){
            $nilai = $this->hpp_update->first()->nilai_budget / $this->netto ;
        }
        return $nilai;
    }

    public function getHppDevCostAwalBruttoAttribute(){
        $nilai = 0;
        if ( count($this->hpp_update) > 0 ){
            $nilai = $this->hpp_update->first()->nilai_budget / $this->luas ;
        }
        return $nilai;
    }

    public function getHppDevCostUpdNettoAttribute(){
        $nilai = 0;
        if ( count($this->hpp_update) > 0 ){
            $nilai = $this->hpp_update->last()->nilai_budget / $this->netto ;
        }
        return $nilai;
    }

    public function getHppDevCostUpdBruttoAttribute(){
        $nilai = 0;
        if ( count($this->hpp_update) > 0 ){
            $nilai = $this->hpp_update->last()->nilai_budget / $this->luas ;
        }
        return $nilai;
    }

    public function hpp_dev_cost_summary_report(){
        return $this->hasMany("Modules\Project\Entities\HppDevCostSummaryReport");
    }

    public function getTotalKontrakDevCostAttribute(){
        $nilai = 0;
        $nilai_faskot = 0;
        $array_kawasan = array();
        $total_kontrak_terbayar = 0;

        /*foreach ($this->spks as $key => $value) {
            if ( $value->itempekerjaan->group_cost == 1 ){
                foreach ($value->details as $key2 => $value2) {
                    if ( $value2->asset->id == $this->id ){
                        $nilai_faskot = ( $value->nilai + $value->nilai_vo )+ $nilai_faskot;
                        $total_kontrak_terbayar = $total_kontrak_terbayar + $value->terbayar_verified;
                    }
                }
            }
        }

        foreach ($this->project->budgets_all as $key => $value) {
            if ( $value->project_kawasan_id == NULL ) {
                if ( $value->deleted_at == NULL ){
                    $total_faskot = $value->total_dev_cost;
                }
            }
        }

        $array_kawasan[0] = array(
            "project_id" => $this->id,
            "project_kawasan_id" => NULL,
            "total_kontrak" => $nilai_faskot,
            "efisiensi" => $this->netto / $this->luas,
            "luas_netto" => $this->netto,
            "luas_brutto" => $this->luas,
            "total_budget" => $total_faskot,
            "total_kontrak_terbayar" => $total_kontrak_terbayar
        ); 

        $start = 1;
        foreach ($this->kawasans as $key => $value) {
            $nilai = 0;
            $nilai_budget = 0;
            $total_kontrak_terbayar = 0;
            foreach ($this->spks as $key2 => $value2) {
                if ( $value2->itempekerjaan->group_cost == 1 ){
                    foreach ($value2->details as $key3 => $value3) {
                        if ( $value3->asset->id == $value->id ){
                            $nilai = $nilai + ( $value2->nilai + $value2->nilai_vo );
                            $total_kontrak_terbayar = $total_kontrak_terbayar + $value2->terbayar_verified;
                        }
                    }
                }
            }   

            foreach ($value->budgets as $key4 => $value4) {     
                if ( $value4->deleted_at == NULL ){
                    $nilai_budget = $nilai_budget + $value4->total_dev_cost;  
                }                 
            }

            $array_kawasan[$start] = array(
                "project_id" => $this->id,
                "project_kawasan_id" => $value->id,
                "total_kontrak" => $nilai,
                "efisiensi" => $value->netto_kawasan / $value->lahan_luas,
                "luas_netto" => $value->netto_kawasan,
                "luas_brutto" => $value->lahan_luas,
                "total_budget" => $nilai_budget,
                "total_kontrak_terbayar" => $total_kontrak_terbayar
            );  
            $start++;     
        }*/


        /*foreach ($array_kawasan as $key => $value) {
            $new_hpp_dev_cost = new HppDevCostSummaryReport;
            $new_hpp_dev_cost->project_id = $value['project_id'];
            $new_hpp_dev_cost->project_kawasan_id = $value['project_kawasan_id'];
            $new_hpp_dev_cost->efisiensi = $value['efisiensi'] * 100;
            $new_hpp_dev_cost->luas_netto = $value['luas_netto'];
            $new_hpp_dev_cost->luas_bruto = $value['luas_brutto'];
            $new_hpp_dev_cost->total_kontrak = $value['total_kontrak'];
            $new_hpp_dev_cost->total_kontrak_terbayar = $value['total_kontrak_terbayar'];
            $new_hpp_dev_cost->save();
        }*/

        foreach ($this->spks as $key => $value) {
            if ( $value->itempekerjaan != "" ){               
                if ( $value->itempekerjaan->group_cost == 1 ){
                    foreach ($value->details as $key2 => $value2) {
                        if ( $value2->asset->id == $this->id ){
                            $nilai_faskot = ( $value->nilai + $value->nilai_vo )+ $nilai_faskot;
                            $total_kontrak_terbayar = $total_kontrak_terbayar + $value->terbayar_verified;
                        }
                    }
                } 
            }
        }

        $new_hpp_dev_cost = new HppDevCostSummaryReport;
        $new_hpp_dev_cost->project_id = $this->id;
        $new_hpp_dev_cost->project_kawasan_id = NULL;
        $new_hpp_dev_cost->efisiensi = 0 ;
        $new_hpp_dev_cost->luas_netto = 0;
        $new_hpp_dev_cost->luas_bruto = 0;
        $new_hpp_dev_cost->total_kontrak = $nilai_faskot;
        $new_hpp_dev_cost->total_kontrak_terbayar = $total_kontrak_terbayar;
        $new_hpp_dev_cost->save();

        foreach ($this->kawasans as $key => $value) {
            $new_hpp_dev_cost = new HppDevCostSummaryReport;
            $new_hpp_dev_cost->project_id = $this->id;
            $new_hpp_dev_cost->project_kawasan_id = $value->id;
            if ( $value->netto_kawasan > 0 ){                
                $new_hpp_dev_cost->efisiensi = $value->netto_kawasan / $value->lahan_luas;
            }else{
                $new_hpp_dev_cost->efisiensi = 0 ;
            }
            $new_hpp_dev_cost->luas_netto = $value->netto_kawasan;
            $new_hpp_dev_cost->luas_bruto = $value->lahan_luas;
            $new_hpp_dev_cost->total_kontrak = $value->total_kontrak;
            $new_hpp_dev_cost->total_kontrak_terbayar = $value['total_kontrak_terbayar'];
            $new_hpp_dev_cost->save();
        }
    }

    public function getUpdateDetailAttribute(){
        foreach ($this->spks as $key => $value) {
            foreach ($value->details as $key2 => $value2) {
                $spk_detail = \Modules\Spk\Entities\SpkDetail::find($value2->id);
                $spk_detail->asset_id = $this->id;
                $spk_detail->save();
            }
        }
    }

    public function getRealCashOutDevCostAttribute(){
        $nilai = 0;
        $item_bln = 0;
        $total_cash_out = 0;
        //foreach ($this->budgets as $key => $value) {
            foreach ($this->budget_tahunans as $key2 => $value2) {
                if ( $value2->tahun_anggaran == date("Y")){
                    $item_bln = 0;
                    foreach ( $value2->details as $key3 => $value3 ){

                        $budgetcf = \Modules\Budget\Entities\BudgetTahunanPeriode::where("budget_id",$value2->id)->where("itempekerjaan_id",$value3->itempekerjaans->id)->get();
                        if ( count($budgetcf) > 0 ){
                            foreach ( $budgetcf as $key4 => $value4 ){
                                $spk = $value3->volume * $value3->nilai;
                                $total_cash_out = (($value4->januari/100) * $spk ) + ( ($value4->februari/100) * $spk ) + ( ($value4->maret/100) * $spk ) + ( ($value4->april/100) * $spk ) + (($value4->mei/100) * $spk ) + ( ($value4->juni/100) * $spk ) + ( ($value4->juli/100) * $spk ) + ( ($value4->agustus/100) * $spk ) + ( ($value4->september/100) * $spk ) + ( ($value4->oktober/100) * $spk ) + ( ($value4->november/100) * $spk ) + ( ($value4->desember/100) * $spk ) ;
                                $item_bln = $item_bln + $total_cash_out;                               
                            }
                        }
                    }
                    
                    $nilai = $nilai + ( $item_bln + $value2->carry_nilai_dev_cost );

                }
            }
        //}

        return $nilai;
    }


    public function getRealCashOutConCostAttribute(){
        $nilai = 0;
        //foreach ($this->budgets as $key => $value) {
            foreach ($this->budget_tahunans as $key2 => $value2) {
                if ( $value2->tahun_anggaran == date("Y")){
                    $nilai = $nilai + ( $value2->carry_nilai_con_cost + $value2->nilai_cash_out_con_cost ); 
                    
                }
            }
        //}

        return $nilai;
    }

    public function getTotalKontrakConCostAttribute(){
        $nilai = 0;
        $array_kawasan = array();
        $array_spk = array();
        $total_kontrak = 0;
        $total_terbayar = 0;

        foreach ($this->kawasans as $key => $value) {
            $total_kontrak_terbayar = 0;
            $array_kawasan[$key] = array(
                "project_id" => $this->id,
                "project_kawasan_id" => $value->id,
                "total_kontrak" => 0,
                "total_terbayar" => 0
            );

            foreach( $value->units as $key2 => $value2 ){
                if ( $value2->nilai > 0 ){
                    $total_kontrak = $total_kontrak + $value2->nilai;
                    $array_spk[$key] = array("id" => $value2->spk_detail->spk->id);
                }
            }  

            if ( count($array_spk) > 0 ){
                foreach ($array_spk as $key3 => $value3) {
                    $spk = Spk::find($value3->id);
                    $total_terbayar = $total_terbayar + $spk->terbayar_verified;
                }
            }
            

            $array_kawasan[$key] = array(
                "project_id" => $this->id,
                "project_kawasan_id" => $value->id,
                "total_kontrak" => $total_kontrak,
                "total_terbayar" => $total_terbayar
            ) ;        
        }

        foreach ($array_kawasan as $key => $value) {
            $new_hpp_con_cost = new HppConCostSummaryReport;
            $new_hpp_con_cost->project_id = $value['project_id'];
            $new_hpp_con_cost->project_kawasan_id = $value['project_kawasan_id'];
            $new_hpp_con_cost->total_kontrak = $value['total_kontrak'] ;
            $new_hpp_con_cost->total_bayar = $value['total_terbayar'];
            $new_hpp_con_cost->save();
        }

    }

    public function new_hpp_con_cost_summary(){
        return $this->hasMany("Modules\Project\Entities\HppConCostSummaryReport");
    }

    public function getHutangBangunConCostAttribute(){
        $nilai =  0;
        $luas = 0;
        foreach ($this->units as $key => $value) {
            if ( $value->status == 5 && $value->is_readywo == NULL ){
                if ( $value->nilai == 0 ){
                    $luas = $luas + $value->bangunan_luas;
                }
            }
        }

        foreach ($this->budgets as $key => $value) {
            foreach ($value->details as $key2 => $value2) {
                if ( $value2->itempekerjaan->group_cost == 2 ){
                    if ( $value2->itempekerjaan->code == 100 ){
                        $nilai = $value2->nilai;
                    }
                }
            }
        }
        return $nilai * $luas;
    }

    public function getTotalCostReportAttribute(){
        $array_cost_report[0] = array(
            "project_id" => $this->id,
            "project_kawasan_id" => "",
            "itempekerjaan" => "",
            "spk_id" => ""
        );

        foreach ($this->spks as $key => $value) {
            /*
            Dihidupkan setelah migrasi
            if ( $value->tender->rab->budget_tahunan != "" ){
                if ( $value->tender->rab->budget_tahunan->budget->kawasan == NULL ){
                    $array_cost_report[0] = array(
                        "project_id" => $this->id,
                        "project_kawasan_id" => "",
                        "itempekerjaan" => $value->itempekerjaan->id,
                        "spk_id" => $value->id
                    );
                }
            }*/
            foreach ($value->details as $key2 => $value2) {
                if ( $value2->asset_id == $this->id ){
                    $array_cost_report[$value->id] = array(
                        "project_id" => $this->id,
                        "project_kawasan_id" => NULL,
                        "itempekerjaan" => $value->itempekerjaan->id,
                        "spk_id" => ($value->id)
                    );
                }
            }
            
        }


        foreach ($this->spks as $key => $value) {
            /*if ( $value->tender->rab->budget_tahunan != "" ){
                if ( $value->tender->rab->budget_tahunan->budget->kawasan != NULL ){
                    $array_cost_report[$value->tender->rab->budget_tahunan->budget->kawasan->id] = array(
                        "project_id" => $this->id,
                        "project_kawasan_id" => $value->tender->rab->budget_tahunan->budget->kawasan->id,
                        "itempekerjaan" =>  $value->itempekerjaan->id,
                        "spk_id" => $value->id
                    );
                }
            }*/
            foreach ($value->details as $key2 => $value2) {
                if ( $value2->asset_id != $this->id ){
                    $array_cost_report[$value->id] = array(
                        "project_id" => $this->id,
                        "project_kawasan_id" => $value2->asset_id,
                        "itempekerjaan" =>  $value->itempekerjaan->id,
                        "spk_id" => $value->id
                    );
                }
            }

        }

        foreach ($array_cost_report as $key => $value) {
          
            $spk = Spk::find($array_cost_report[$key]['spk_id']);

            if ( $spk != "" ){
            $cost_report = new CostReport;
            $cost_report->project_id = $this->id;
            $cost_report->project_kawasan_id = $value['project_kawasan_id'];
            $cost_report->spk_id = $spk->id;
            $cost_report->itempekerjaan = $spk->itempekerjaan->id;
            $cost_report->department = 2;
            $cost_report->progress_lapangan = 0;
            $cost_report->nilai = $spk->nilai + $spk->nilai_vo;
            $cost_report->rekanan = $spk->rekanan_id;
            $cost_report->rekanan_type = $spk->rekanan->pkp_status;
            $cost_report->save();
            }

        }
    }

    public function cost_report(){
        return $this->hasMany("Modules\Project\Entities\CostReport");
    }

    public function getUpdateIndexHargaAttribute(){
        $nilai = 0;
        $volume_param = 0;
        foreach ($this->spks as $key => $value) {
            $nilai = $value->nilai + $value->nilai_vo;
            foreach ($value->detail_units as $key2 => $value2) {
                if ( $value2->unit_progress->itempekerjaan->tag == 1 ){
                    $volume_param = $value2->volume;
                    $satuan = $value2->satuan;
                }
            }
            if ( $volume_param > 0 ){
                $rata2 = $nilai / $volume_param;
                $id_pekerjaan = $value->itempekerjaan->id;
                $itempekerjaan_harga = new ItempekerjaanHarga;
                $itempekerjaan_harga->itempekerjaan_id = $id_pekerjaan;
                $itempekerjaan_harga->project_id = $this->id;
                $itempekerjaan_harga->nilai = round($rata2);
                $itempekerjaan_harga->satuan = $satuan;
                $itempekerjaan_harga->save();

                foreach ($value->detail_units as $key3 => $value3) {
                    $itempekerjaan_detail = new ItempekerjaanHargaDetail;
                    $itempekerjaan_detail->itempekerjaan_harga_id = $itempekerjaan_harga->id;
                    $itempekerjaan_detail->itempekerjaan_id = $value3->unit_progress->itempekerjaan->id;
                    $itempekerjaan_detail->project_id = $this->id;
                    $itempekerjaan_detail->nilai = $value3->unit_progress->nilai;
                    $itempekerjaan_detail->satuan = $value3->unit_progress->satuan;
                    $itempekerjaan_detail->save();
                }
            }            
        }
        //echo $nilai;

    }

    public function getNilaiReportRealisasiDevCostAttribute(){
        $nilai = 0;
        $array = array();
        foreach ($this->hpp_dev_cost_summary_report as $key => $value) {
            $array[$value->project_kawasan_id] = $value->total_kontrak;
        }

        return array_sum($array);
    }

    public function getNilaiReportRealisasiConCostAttribute(){
        $nilai = 0;
        $array = array();
        foreach ($this->new_hpp_con_cost_summary as $key => $value) {
            $array[$value->project_kawasan_id] = $value->total_kontrak;
        }

        return array_sum($array);
    }

    public function getNilaiReportTerbayarDevCostAttribute(){
        $nilai = 0;
        $array = array();
        /*foreach ($this->hpp_dev_cost_summary_report as $key => $value) {
            $array[$value->project_kawasan_id] = $value->total_kontrak_terbayar;
        }

        return array_sum($array);*/

        foreach ($this->voucher as $key => $value) {
            if ( $value->pencairan_date != "" ){
                    $nilai = $nilai + $value->nilai;
                
            }
        }
        return $nilai;
    }

    public function getNilaiReportTerbayarConCostAttribute(){
        $nilai = 0;
        $array = array();
        foreach ($this->new_hpp_con_cost_summary as $key => $value) {
            $array[$value->project_kawasan_id] = $value->total_bayar;
        }

        return array_sum($array);
    }

    public function getNilaiBulananReportRealisasiDevCostAttribute(){
        $array_bulanan = array(
            "01" => 0,
            "02" => 0,
            "03" => 0,
            "04" => 0,
            "05" => 0,
            "06" => 0,
            "07" => 0,
            "08" => 0,
            "09" => 0,
            "10" => 0,
            "11" => 0,
            "12" => 0
        );

        $tahun = date("Y") - 1;
        foreach ($this->spks as $key => $value) {
            if ( $value->itempekerjaan != "" ){
                if ( $value->itempekerjaan->group_cost == 1 ){
                    foreach ($value->baps as $key2 => $value2) {
                        foreach ($value2->vouchers as $key3 => $value3) {
                            if ( $value3->pencairan_date != NULL ){
                                if ( $value3->date->format("Y") == $tahun ){
                                    $bulan = $value3->pencairan_date->format("m");
                                    $array_bulanan[$bulan] = $array_bulanan[$bulan] + $value3->nilai;                                
                                }
                            }
                        }
                    }                
                }
            }
        }

        return ($array_bulanan);
    }

    public function getNilaiBulananReportRealisasiConCostAttribute(){
        $array_bulanan = array(
            "01" => 0,
            "02" => 0,
            "03" => 0,
            "04" => 0,
            "05" => 0,
            "06" => 0,
            "07" => 0,
            "08" => 0,
            "09" => 0,
            "10" => 0,
            "11" => 0,
            "12" => 0
        );


        $tahun = date("Y") - 1;
        foreach ($this->spks as $key => $value) {
            if ( $value->itempekerjaan != "" ){                
                if ( $value->itempekerjaan->group_cost == 2 ){
                    foreach ($value->baps as $key2 => $value2) {
                        foreach ($value2->vouchers as $key3 => $value3) {
                            if ( $value3->pencairan_date != NULL ){
                                if ( $value3->date->format("Y") == $tahun ){
                                    $bulan = $value3->pencairan_date->format("m");
                                    $array_bulanan[$bulan] = $array_bulanan[$bulan] + $value3->nilai;
                                }
                            }
                        }
                    }                
                }
            }
        }
        return ($array_bulanan);

    }

    public function getListHutangBayarDevCostAttribute(){
        $tahun_sebelumnya = date("Y") - 1; 
        $array_list_dev_cost = array();
        foreach ($this->cost_report as $key => $value) {
            $spk = Spk::find($value->spk_id);
            if ( $spk->itempekerjaan->group_cost == 1 ){
                if ( $spk->date->format("Y") >= $tahun_sebelumnya){
                    $sisa = $spk->nilai - $spk->terbayar_verified;
                    if ( $sisa > 0 ){
                        if ( !(isset($array_list_dev_cost[$spk->date->format("Y")]))){
                            $array_list_dev_cost[$spk->date->format("Y")] = array ( 
                                "tahun" => $spk->date->format("Y"),
                                "nilai_kontrak_dev_cost" =>  ($spk->nilai + $spk->nilai_vo),
                                "terbayar_dev_cost" => $spk->terbayar_verified,
                                "hutang_bayar_dev_cost" => $sisa,
                                "nilai_kontrak_con_cost" =>  0,
                                "terbayar_con_cost" => 0,
                                "hutang_bayar_con_cost" => 0
                            );
                        }else{
                            $array_list_dev_cost[$spk->date->format("Y")] = array ( 
                                "nilai_kontrak_dev_cost" =>  $array_list_dev_cost[$spk->date->format("Y")]["nilai_kontrak_dev_cost"] +  ($spk->nilai + $spk->nilai_vo),
                                "terbayar_dev_cost" => $array_list_dev_cost[$spk->date->format("Y")]["terbayar_dev_cost"] + $spk->terbayar_verified ,
                                "hutang_bayar_dev_cost" => $array_list_dev_cost[$spk->date->format("Y")]["hutang_bayar_dev_cost"] + $sisa,
                                "tahun" => $spk->date->format("Y"),
                                "nilai_kontrak_con_cost" =>  $array_list_dev_cost[$spk->date->format("Y")]["nilai_kontrak_con_cost"] ,
                                "terbayar_con_cost" => $array_list_dev_cost[$spk->date->format("Y")]["terbayar_con_cost"] ,
                                "hutang_bayar_con_cost" => $array_list_dev_cost[$spk->date->format("Y")]["hutang_bayar_con_cost"] 
                             );
                        }
                    }
                }
            }elseif($spk->itempekerjaan->group_cost == 2 ){
                if ( $spk->date->format("Y") >= $tahun_sebelumnya){
                    $sisa = $spk->nilai - $spk->terbayar_verified;
                    if ( $sisa > 0 ){
                        if ( !(isset($array_list_dev_cost[$spk->date->format("Y")]))){
                            $array_list_dev_cost[$spk->date->format("Y")] = array ( 
                                "tahun" => $spk->date->format("Y"),
                                "nilai_kontrak_dev_cost" =>  0,
                                "terbayar_dev_cost" => 0,
                                "hutang_bayar_dev_cost" => 0,
                                "nilai_kontrak_con_cost" =>  $array_list_dev_cost[$spk->date->format("Y")]["nilai_kontrak_con_cost"] ,
                                "terbayar_con_cost" => $array_list_dev_cost[$spk->date->format("Y")]["terbayar_con_cost"] ,
                                "hutang_bayar_con_cost" => $array_list_dev_cost[$spk->date->format("Y")]["hutang_bayar_con_cost"]
                            );
                        }else{
                            $array_list_dev_cost[$spk->date->format("Y")] = array ( 
                                "nilai_kontrak_dev_cost" =>  $array_list_dev_cost[$spk->date->format("Y")]["nilai_kontrak_dev_cost"],
                                "terbayar_dev_cost" => $array_list_dev_cost[$spk->date->format("Y")]["terbayar_dev_cost"] ,
                                "hutang_bayar_dev_cost" => $array_list_dev_cost[$spk->date->format("Y")]["hutang_bayar_dev_cost"] ,
                                "tahun" => $spk->date->format("Y"),
                                "nilai_kontrak_con_cost" =>  $array_list_dev_cost[$spk->date->format("Y")]["nilai_kontrak_con_cost"]  +  ($spk->nilai + $spk->nilai_vo) ,
                                "terbayar_con_cost" => $array_list_dev_cost[$spk->date->format("Y")]["terbayar_con_cost"]  + $spk->terbayar_verified ,
                                "hutang_bayar_con_cost" => $array_list_dev_cost[$spk->date->format("Y")]["hutang_bayar_con_cost"] + $sisa
                             );
                        }
                    }
                }
            }
        }
        return $array_list_dev_cost;
    }

    public function getNilaiKawasanHutangBayarDevCostAttribute(){
        $array_pekerjaan = array("");
        foreach ($this->cost_report as $key => $value) {
            if ( $value->project_kawasan_id == 0 ){
                $array_pekerjaan[0] = array(
                    "kawasan" => "Fasilitas Kota",
                    "pekerjaan" => ""
                );

                $nilai_spk = $value->spk->nilai + $value->spk->nilai_vo;
                if ( $nilai_spk - $value->spk->terbayar_verified > 0 ){
                    $itempekerjaan_id = $value->spk->itempekerjaan->id;
                    if ( !(isset($array_pekerjaan[0]['pekerjaan'][$itempekerjaan_id]))) {
                        $array_pekerjaan[0] = array(
                            "kawasan" => "Fasilitas Kota",
                            "pekerjaan" => array(
                                $itempekerjaan_id => "",
                                "name" => $value->spk->itempekerjaan->name,
                            )
                        );
                    }
                } 
            }else{
                if ( !(isset($array_pekerjaan[$value->project_kawasan_id]))) {
                    $array_pekerjaan[$value->project_kawasan_id] = array(
                        "kawasan" => $value->kawasan->name,
                        "pekerjaan" => ""
                    );

                    $nilai_spk = $value->spk->nilai + $value->spk->nilai_vo;
                    if ( $nilai_spk - $value->spk->terbayar_verified > 0 ){
                        $itempekerjaan_id = $value->spk->itempekerjaan->id;
                        if ( !(isset($array_pekerjaan[0]['pekerjaan'][$itempekerjaan_id]))) {
                            $array_pekerjaan[$value->project_kawasan_id] = array(
                                "kawasan" => $value->kawasan->name,
                                "pekerjaan" => array(
                                    $itempekerjaan_id => "",
                                    "name" => $value->spk->itempekerjaan->name,
                                )
                            );
                        }
                    } 
                }
            }
        }

        return $array_pekerjaan;
    }

    public function hpp_con_cost_detail(){
        return $this->hasMany("Modules\Project\Entities\HppConCocstDetailReport","project_id");
    }

    public function getTotalTerbangunByTipeAttribute(){

        foreach ($this->unittype as $key => $value) {
            $total_terbangun = 0;

            foreach ($this->spks as $key2 => $value2) {
                if ( $value2->itempekerjaan->group_cost == 2 ){
                    foreach ($value2->details as $key3 => $value3) {
                        if ( $value3->asset->unit_type_id == $value->id ){
                            $total_terbangun = $total_terbangun + 1;
                        }
                    }
                }    
            }
            
            $hpp_concost = new HppConCostDetailReport;
            $hpp_concost->project_id = $this->id;
            $hpp_concost->project_kawasan_id = $value->cluster->id;
            $hpp_concost->unit_type_id = $value->id;
            $hpp_concost->total_terbangun = $total_terbangun;
            $hpp_concost->save();
        }
    }

    public function getNilaiLuasHutangBangunDevCostAttribute(){
        $nilai = 0;
        if ( $this->netto > 0 ){
           
            $efektif = ( $this->netto / $this->luas ) ;
            $gross = 100 - $efektif;
            $persentase = ( $efektif / $gross ) * $this->nilai_luas_pending_workorder["luas"] ;
            $nilai = $persentase;
        }
        return $nilai;
    }

    public function getNilaiLuasPendingWorkorderAttribute(){
        $nilai = array("total" => 0, "luas" => 0 );
        foreach ($this->units as $key => $value) {
            if ( $value->status == 5 && $value->is_readywo == NULL ){
                $nilai["luas"] = $nilai["luas"] + $value->tanah_luas;
                $nilai["total"] = $nilai["total"] + 1;
            }
        }     
        return $nilai;
    }

    public function getHutangBangunKawasanAttribute(){
        $array_kawasan = array();
        foreach ($this->kawasans as $key => $value) {
            $terjual = 0;
            $stock = 0;
            foreach($value->units as $key2 => $value2 ){
                if ( $value2->status == 5 && $value2->is_readywo == NULL ){
                    $terjual = $terjual + 1;
                }elseif ( $value2->status == 2 && $value2->is_readywo == NULL ){
                    $stock = $stock + 1 ;
                }
            }
            $array_kawasan[$key] = array(
                "name" => $value->name,
                "terjual" => $terjual,
                "stock" => $stock
            );
        }

        return $array_kawasan;
    }
    

    public function getUnitStockTerbangunAttribute(){
        $array_kawasan = array();
        foreach ($this->kawasans as $key => $value) {
            $terjual = 0;
            $stock = 0;
            $luas_bangunan = 0;
            foreach($value->units as $key2 => $value2 ){
                if ( $value2->status == 3 ){
                    if ( $value2->progresses != "" ){
                        $stock = $stock + 1 ;
                        $terjual = $terjual + $value2->nilai;
                        $luas_bangunan = $luas_bangunan + $value2->bangunan_luas;
                    }
                }
            }

            $array_kawasan[$key] = array(
                "name" => $value->name,
                "stock" => $stock,
                "terjual" => $terjual,
                "luas_bangunan" => $luas_bangunan
            );
        }

        return $array_kawasan;
    }

    public function getNilaiHutangBayarConCostAttribute(){
        $nilai = 0;
        foreach ($this->spks as $key => $value) {
            if ( $value->itempekerjaan->group_cost == 2){
                $sisa = ( $value->nilai + $value->nilai_vo ) + $value->terbayar_verified;
                $nilai = $nilai + $sisa;
            }
        }
        return $nilai;
    }

    public function rekanans(){
        return $this->hasMany("Modules\Rekanan\Entities\RekananGroup");
    }

    public function getTotalSpkFaskotAttribute(){
        $nilai = 0;
        $start = 0;
        $nilai_all = 0;
        foreach ($this->spks as $key => $value) {
            foreach ($value->details as $key2 => $value2) {
                if ( $value2->asset_id == $this->id ){
                    $nilai = $nilai + ($value->nilai + $value->nilai_vo);
                    
                }
            }
        }
        echo "Faskot => ".$nilai;
        echo "\n";
        $nilai = 0;
        foreach ($this->kawasans as $key2 => $value2) {
            foreach ($this->spks as $key3 => $value3) {
                if ( $value3->asset_id == $this->id ){
                    $nilai = $nilai + ($value3->nilai + $value->nilai_vo);                    
                }
            }
            echo "Cluster => ".$value2->name."<>".$nilai;
            echo "\n";
        }
        


        return $nilai + $nilai_all;
    }

    public function getTotalAllKontrakConCostAttribute(){
        $nilai = 0;
        foreach ($this->kawasans as $key => $value) {
            $nilai = $nilai + $value->total_kontrak_con_cost;
        }
        return $nilai;
    }

    public function getAllOldBudgetAttribute(){
        $nilai = 0;
        foreach ($this->budgets_all as $key => $value) {
            if ( $value->deleted_at == NULL ){
                $nilai = $value->nilai + $nilai;
            }
        }

        return $nilai;
    }

    public function getNilaiBudgetBeforeAttribute(){
        $nilai = 0;
        $tahun = 2018;
        foreach ($this->budgets as $key => $value) {
            if ( $value->deleted_at != NULL ){
                if ( $value->created_at->format("Y") == $tahun){
                    $nilai = $nilai + $value->total_dev_cost;
                }
            }
        }
        return $nilai;
    }

    public function getNilaiRakorTerbayarDevCostAttribute(){
        $nilai = 0;
        $array = array();
        /*foreach ($this->hpp_dev_cost_summary_report as $key => $value) {
            $array[$value->project_kawasan_id] = $value->total_kontrak_terbayar;
        }

        return array_sum($array);*/
        $tahun = 2018;
        foreach ($this->voucher as $key => $value) {
            if ( $value->bap != "" ){
                if ( $value->bap->spk != "" ){
                    if ( $value->bap->spk->itempekerjaan != "" ){
                        if ( $value->bap->spk->itempekerjaan->group_cost == 1 ){
                            if ( $value->pencairan_date != "" ){
                                if ( $value->date->format("Y") == $tahun ){
                                    $nilai = $nilai + $value->nilai;
                                }
                            }
                        }
                    }
                }
            }
            
        }
        return $nilai;
    }

    public function getNilaiRakorTerbayarConCostAttribute(){
        $nilai = 0;
        $array = array();
        $tahun = 2018;
        foreach ($this->voucher as $key => $value) {
            if ( $value->bap != "" ){
                if ( $value->bap->spk != "" ){
                    if ( $value->bap->spk->itempekerjaan != "" ){
                        if ( $value->bap->spk->itempekerjaan->group_cost == 2 ){
                            if ( $value->pencairan_date != "" ){
                                if ( $value->date->format("Y") == $tahun ){
                                    $nilai = $nilai + $value->nilai;
                                }
                            }
                        }
                    }
                }
            }
            
        }
        return $nilai;

    }

    public function getTotalSpkFaskotTerbayarAttribute(){
        $nilai = 0;
        $start = 0;
        $nilai_all = 0;
        foreach ($this->spks as $key => $value) {
            foreach ($value->details as $key2 => $value2) {
                if ( $value2->asset_id == $this->id ){
                    $nilai = $nilai + $value->terbayar_verified ;
                    
                }
            }
        }
        echo "Faskot => ".$nilai;
        echo "\n";
        $nilai_all = 0;
        foreach ($this->kawasans as $key2 => $value2) {
            foreach ($this->spks as $key3 => $value3) {
                if ( $value3->asset_id == $this->id ){
                    $nilai_all = $nilai_all +  $value->terbayar_verified;                    
                }
            }
            echo "Cluster => ".$value2->name."<>".$nilai_all;
            echo "\n";
        }
        


        return $nilai + $nilai_all ; 
    }


    public function getTotalSoldAttribute(){
        $total_sold = array( 
            "6" => array( "unit_id" => array(), "label" => "0-6" ),
            "12" => array( "unit_id" => array(), "label" => "7-12" ), 
            "24" => array( "unit_id" => array(), "label" => "13-24" ),
            "36" => array( "unit_id" => array(), "label" => "25-36" )
        );
        $index_a = 0;
        $index_b = 0;
        $index_c = 0;
        $index_d = 0;

        foreach ($this->units as $key => $value) {
            if ( $value->status == 5 ){
                if ( $value->serah_terima_plan != "" ){ 
                    if ( $value->is_readywo == ""){   
                        if ( $value->is_spk == 0){      
                            if($value->tag_kategori == "B"){          
                                $hari_ini = strtotime(date("Y-m-d H:i:s.u"));
                                $serah_terima_plan = strtotime($value->serah_terima_plan);
                                $selisih = $serah_terima_plan - $hari_ini;
                                $hari = floor($selisih / (60 * 60 * 24)) ;  
                                $bulan = ceil($hari / 30);
                                if ( $bulan > 0 && $bulan <= 6 ){
                                    $total_sold["6"]['unit_id'][$index_a] = $value->id;
                                    $index_a++;
                                }elseif ( $bulan > 6 && $bulan <=13 ){
                                    $total_sold["12"]['unit_id'][$index_b] = $value->id;
                                    $index_b++;
                                }elseif ( $bulan > 13 && $bulan <=25 ){
                                    $total_sold["24"]['unit_id'][$index_c] = $value->id;
                                    $index_c++;
                                }elseif ( $bulan > 25 && $bulan <= 37 ){
                                    $total_sold["36"]['unit_id'][$index_d] = $value->id;
                                    $index_d++;
                                }
                            }
                        }
                    }
                }
            }
        }

        return $total_sold;
    }

    public function getUnitPendingAttribute(){
        $arr_pending = array();
        $start = 0;
        $array_status = array(
            "1" => "Perubahan Cacah ( luas, type )",
            "2" => "Beda Ukuran",
            "3" => "Ditunda / Pending",
            "4" => "Alih fungsi",
            "5" => "Perubahan Konsumen",
            "6" => "Ubah Nomor Unit"
        );
        foreach ($this->units as $key => $value) {
            if ( count($value->pending) > 0  ){
                $arr_pending[$start] = array(
                    "unit_id" => $value->id,
                    "description" => $value->pending['description'],
                    "status" => $array_status[$value->pending['name']],
                    "blok" => $value->blok->name,
                    "cluster" => $value->blok->kawasan->name,
                    "name" => $value->code,
                    "id" => $value->pending['id'],
                    "status_id" => $value->pending['name']
                );
                $start++;
            }
        }
        return $arr_pending;
    }

    public function unit_change_prices(){
        return $this->hasMany("Modules\Project\Entities\UnitChangedPrices","project_id");
    }

    public function getUnitVoAttribute(){
        $unit_vo = array();
        $project = $this->project_id;
        $get_change_price = UnitChangedPrices::where("project_id",$project)->get(); 
        $start = 0;
        if ( count($get_change_price) > 0 ){
            foreach ($get_change_price as $key => $value) {

                $unit = Unit::where("unit_id",$value->unit_id)->get();

                if ( count($unit) > 0 ){
                    if ( $value->deleted_at == "" ){
                

                        $unit_vo[$start] = array(
                            "code" => $unit->first()->code,
                            "blok" => $unit->first()->blok->name,
                            "kawasan" => $unit->first()->blok->kawasan->name,
                            "tanah_luas" => $value->luas_tanah,
                            "bangunan_luas" => $value->luas_bangunan,
                            "no_spk" => "",
                            "keterangan" => $value->description,
                            "id" => $unit->first()->id,
                            "action" => $value->updated_at
                        );
                        $start++;
                    }      
                }
            }
        }
        return $unit_vo;
    }

    public function getUnitPendingBaruAttribute(){
        $arr_pending = array();
        $start = 0;
        $array_status = array(
            "1" => "Perubahan Cacah ( luas, type )",
            "2" => "Beda Ukuran",
            "3" => "Ditunda / Pending",
            "4" => "Alih fungsi",
            "5" => "Perubahan Konsumen",
            "6" => "Ubah Nomor Unit"
        );
        // return $this;
        foreach (\Modules\Project\Entities\UnitPending::where("deleted_at",null)->get() as $key => $value) {
            // return $value->unit;
            // if ( $value->deleted_at == "" ){
                if($value->unit != ''){
                    // return $value->unit->blok;
                    if ( $value->unit->blok->project_id == $this->id ){
                        $arr_pending[$start] = array(
                            "unit_id" => $value->unit->id,
                            "description" => $value->description,
                            "status" => $array_status[$value->status_reply_id],
                            "blok" => $value->unit->blok->name,
                            "cluster" => $value->unit->blok->kawasan->name,
                            "name" => $value->unit->code,
                            "id" => $value->id,
                            "status_id" => $value->status_reply_id
                        );
                        $start++;
                    }
                }
            // }
        }
        return $arr_pending;
    }

    public function getTotalDevCostConCostAttribute(){
        $nilai1 = 0;
        $nilai2 = 0;
        $nilai3 = 0;
        $nilai4 = 0;
        foreach ($this->budgets as $key => $value) {
            # code...
            if($value->department_id == 2){
                if($value->deleted_at == null){
                    $nilai1 += $value->total_dev_cost;
                    $nilai2 += $value->total_rencana_dev_cost;
                    $nilai3 += $value->total_con_cost;
                    $nilai4 += $value->total_rencana_con_cost;
                }
            }
        }
        $total = [];
        $total["TotalDevCost"] = $nilai1;
        $total["TotalRencanaDevCost"] = $nilai2;
        $total["TotalConCost"] = $nilai3;
        $total["TotalRencanaConCost"] = $nilai4;
        return $total;
    }

    public function getTotalSpkDetailAttribute(){
        $total_nilai_spk_dc = 0;
        $sisa_spk_dc = 0;
        $total_nilai_spk_cc = 0;
        $sisa_spk_cc = 0;
        foreach ($this->spks as $key => $value) {
            # code...
            if($value->tender->rab->workorder->department_from == 2){
            // if($value->tender->rab->workorder->kawasan_id != null){
            //     if($value->tender->rab->workorder->projectKawasan->id == $budget->kawasan->id){
                if ($value->item_pekerjaan != null) {
                    # code...
                    if($value->item_pekerjaan->code != 100){
                        $spk_nilai_kontrak = $value->nilai_spk;
                        $total_nilai_spk_dc += $spk_nilai_kontrak;
                        $spk_dibayarkan_dc = $value->spk_terbayar;
                        $sisa_spk_dc += ($spk_nilai_kontrak - $spk_dibayarkan_dc) ;
                    }else{
                        $spk_nilai_kontrak = $value->nilai_spk;
                        $total_nilai_spk_cc += $spk_nilai_kontrak;
                        $spk_dibayarkan_cc = $value->spk_terbayar;
                        $sisa_spk_cc += ($spk_nilai_kontrak - $spk_dibayarkan_cc) ;
                    }
                }
            //     }
            // }
            }
        }

        $total = [];
        $total["total_nilai_spk_dc"] = $total_nilai_spk_dc;
        $total["sisa_spk_dc"] = $sisa_spk_dc;
        $total["total_nilai_spk_cc"] = $total_nilai_spk_cc;
        $total["sisa_spk_cc"] = $sisa_spk_cc;
        return $total;
    }

    public function getTotalSpkSudahCairAttribute(){
        $nilai = 0;
        foreach ($this->spks as $key => $value) {
            # code...
            $nilai += $value->spk_terbayar;
        }

        return $nilai;
    }

    public function partner_project(){
        return $this->hasMany('Modules\Partner\Entities\PartnerProject');
    }

}
