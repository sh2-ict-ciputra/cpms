<?php 
namespace Modules\Workorder\Entities;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Approval;

class Workorder extends Model
{
    use Approval;

    protected $fillable = ['budget_tahunan_id','no','department_from','department_to','name','durasi','satuan_waktu','estimasi_nilaiwo','date','posisi_dokumen','description'];
    protected $dates = ['date'];

    public function scopeProject()
    {
        return $this->whereHas('budget_tahunan', function($q){
            $q->whereHas('budget', function($r){
                $r->where('project_id', session('project'));
            });
        });
    }
    public function project()
    {
        //return $this->budget_tahunan->project;
        return $this->belongsTo('Modules\Project\Entities\Project', 'budget_tahunan_id');
    }

    public function budget_tahunan()
    {
        return $this->belongsTo('Modules\Budget\Entities\BudgetTahunan', 'budget_tahunan_id');
    }

    public function supports()
    {
        return $this->hasMany('Modules\Department\Entities\DepartmentSupport');
    }

    public function rabs()
    {
        return $this->hasMany('Modules\Rab\Entities\Rab')->where('deleted_at',null);
    }
    public function tenders()
    {
        return $this->hasManyThrough('Modules\Tender\Entities\Tender', 'Modules\Rab\Entities\Rab');
    }

    public function departmentFrom()
    {
        return $this->belongsTo('Modules\Department\Entities\Department', 'department_from');
    }

    public function departmentTo()
    {
        return $this->belongsTo('Modules\Department\Entities\Department', 'department_to');
    }

    public function details()
    {
        return $this->hasMany('Modules\Workorder\Entities\WorkorderDetail')->where('deleted_at',null);
    }

    public function getPtAttribute()
    {
        return $this->project->pt->first()->pt;
    }

    public function unit()
    {
        return $this->belongsTo('Modules\Project\Entities\Unit');
    }

    public function getNilaiAttribute()
    {
        /*$nilai = 0;

        foreach ($this->detail_pekerjaan as $key => $detail) 
        {
            $nilai = $nilai + ( $detail->volume * $detail->nilai );
        }

        $workorder_unit = $this->details->where("asset_type","App\Unit");
        $devcost = 0;
        
        foreach ($workorder_unit as $key => $value) {
            # code...
            $devcost = $devcost + $value->asset->templatepekerjaan->con_cost ;
        }

        return $nilai + $devcost;*/
        //return $this->estimasi_nilaiwo;
        $nilai = 0;
        foreach ($this->detail_pekerjaan as $key => $value) {
            # code...
            if ( $value->volume != "" && $value->nilai != "" ){
                $nilai = $nilai + ( $value->volume * $value->nilai );
            }
        }
        return $nilai;
    }

    public function approvalhistory(){                                                                             
        return $this->hasManyThrough("Modules\Approval\Entities\ApprovalHistory","App\User","id","approval_id");
    }

    public function detail_pekerjaan(){
        return $this->hasMany("Modules\Workorder\Entities\WorkorderBudgetDetail")->where('deleted_at',null);
    }

    public function getSelfApprovalAttribute(){
        return $this->hasMany("Modules\Approval\Entities\ApprovalHistory","document_id");
    }

    public function itempekerjaan(){
        return $this->hasMany("Modules\Pekerjaan\Entities\Itempekerjaan","itempekerjaan_id");
    }

    public function getParentIdAttribute(){
        $nilai = array();
        $detailworkorder = array();
        $satuan = "";
        $budget_tahunan = "";
        $total_budget = 0;
        $workorder_budget_id = "";
        $start = 0;
        foreach ($this->detail_pekerjaan as $key => $value) {
            $total_budget = 0;
            if ( $value->volume != "" && $value->nilai != "" ){
                $detailworkorder[$start] = array(
                    "deptcode" => $this->departmentFrom->code,
                    "coa_code" => $value->itempekerjaan->code,
                    "item_name" => $value->itempekerjaan->name,
                    "subtotal" => $value->volume * $value->nilai,
                    "satuan" => $value->satuan,
                    "id" => $this->id,
                    "volume" => $value->volume,
                    "unitprice" => $value->nilai,
                    "budget_tahunan" => $value->budget_tahunan->no,
                    "total_budget" => $total_budget,
                    "workorder_budget_id" => $value->id
                );
            $start++;
            }
        }

        return $detailworkorder;
    }

    public function getSubTotalUnitAttribute(){
        $nilai = 0;
        foreach ($this->parent_id as $key => $value) {
            # code...
            $itempekerjaan = Itempekerjaan::where("parent_id",$value)->get();
            foreach ($itempekerjaan as $key => $value) {
                # code...
                $volume = WorkorderBudgetDetail::where("itempekerjaan_id",$value->id)->where("workorder_id",$this->id)->get()->first()->volume;
            }
        }
        return $nilai ;
    }

    public function getParentCoaAttribute(){
        $array = array();
        $array_coa = array();
        foreach ($this->detail_pekerjaan as $key => $value) {
            //if ( \Modules\Rab\Entities\RabPekerjaan::where("itempekerjaan_id",$value->itempekerjaan_id)->count() <= 0 ){
                    if ( \Modules\Pekerjaan\Entities\Itempekerjaan::find($value->itempekerjaan_id)->group_cost == "1" ){
                    $code = explode(".", \Modules\Pekerjaan\Entities\Itempekerjaan::find($value->itempekerjaan_id)->code );
                    $array[$key] = $code[0];
                }  
            //}
                      
        }
        
        $uniqe = array_values(array_unique($array));
        for ( $i=0; $i < count($uniqe); $i++ ){
            $item = \Modules\Pekerjaan\Entities\Itempekerjaan::where("code",$uniqe[$i])->first();
            $array_coa[$i] = array(
                "id" => $item->id,
                "code" => $uniqe[$i],
                "label" => $item->name
            );
        }
        return $array_coa;
    }

    public function getUnitCoaAttribute(){
        $array = array();
        $array_coa = array();
        foreach ($this->details as $key => $value) {
            if ( \Modules\Rab\Entities\RabUnit::where("asset_id",$value->asset_id)->count() <= 0 ){
                if ( $value->asset_type == "App\Unit") {
                    $array[$key] = $value->unit->unit_type_id;
                }   
            }
            
        }
        $uniqe = array_values(array_unique($array));
        for ( $i=0; $i < count($uniqe); $i++ ){            
            $type = UnitType::find($uniqe[$i]);
            $template = Unit::where("unit_type_id",$type->id)->first();
            $array_coa[$i] = array(
                "id" => $type->id,
                "type" => $type->name,
                "template" => $template->templatepekerjaan->name,
                "budget" => $template->templatepekerjaan->budget_details->first()->nilai
            );
        }
        return $array_coa;
    }

    public function getBudgetParentAttribute(){
        $nilai = array();
        foreach ($this->detail_pekerjaan as $key => $value) {
            $nilai[$key] = $value->budget_tahunan_id;
        }

        return array_values(array_unique($nilai));
    }

    public function getAllSpkAttribute(){
        $nilai = 0;
        $pekerjaan = $this->detail_pekerjaan->count();
        foreach ($this->rabs as $key => $value) {
            if ( $value->tender != "" ){
                if ( $value->tender->spks->count() > 0 ){
                    $nilai = $nilai + 1;
                }
            }
        }

        if ( $pekerjaan > 0 ){            
            if ( $pekerjaan <= $nilai ){
                return 1;
            }else{
                return 0;
            }
        }else{
            return 0;
        }
    }

    public function getAllBudgetAttribute(){
        $nilai = 0;
        if ( $this->detail_pekerjaan->count() <= 0 ){
            $nilai = 1;
        }

        foreach ($this->detail_pekerjaan as $key => $value) {
            if ( $value->budget_tahunan != "" ){
                $nilai = $nilai + 1;
            }
        }

        return $nilai;
    }

    public function budget_draft(){
        return $this->hasOne("Modules\BudgetDraft\Entities\BudgetDraft");
    }
    
    public function getKawasanAttribute(){
        $kawasan = array() ;
        $start = 0;
        $value_kawasan = "";
        foreach ($this->detail_pekerjaan as $key => $value) {
            if ( $value->budget_tahunan != "" ){
                $budget = $value->budget_tahunan->budget;
                if ( $budget->project_kawasan_id == "" ){
                    $kawasan[$start] = "Fasilitas Umum";
                }else{
                    $kawasan[$start] = $budget->kawasan->name;
                }
            }
        }

        $list = array_unique($kawasan);
        foreach ($kawasan as $key => $value) {
           $value_kawasan .= $kawasan[$key];
        }

        return $value_kawasan;
    }

    public function projectKawasan(){
     return $this->belongsTo('Modules\Project\Entities\ProjectKawasan', 'kawasan_id');
    }

    public function pt_wo(){
        return $this->belongsTo('Modules\Pt\Entities\Pt', 'pt_id');
    }
}
