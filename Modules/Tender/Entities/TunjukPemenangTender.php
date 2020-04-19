<?php 
namespace Modules\Tender\Entities;

use App\CustomModel;
use App\Traits\Approval;

class TunjukPemenangTender extends CustomModel
{
    use Approval;

    protected $fillable = ['is_pemenang'];

    public function tender()
    {
        return $this->belongsTo('Modules\Tender\Entities\Tender')->orderBy("id","desc");
    }
    
    public function tender_rekanan()
    {
        return $this->belongsTo('Modules\Tender\Entities\TenderRekanan');
    }

    public function getProjectAttribute(){
        if ( $this->tender != null ){
            if ( $this->tender->rab->workorder != null ){
                return $this->tender->rab->workorder->project;
            }else{
                return $this->tender->rab;
            }
        }else{
            return null;
            // return "1";
        }
    }

    public function getPtAttribute()
    {
        // return $this->rab->workorder->project->first()->pt->first()->pt;
        if($this->tender->rab->workorder->pt_id != ''){
            return $this->tender->rab->workorder->pt_wo;
        }else{
            return $this->tender->rab->workorder->pt;
        }
    }

    public function getNilaiAttribute()
    {
        $nilai = 0;
        
        foreach ($this->tender_rekanan->penawarans[$this->penawaran-1]->details as $key => $value) {
            # code...
            if($value->total_nilai != null && $value->total_nilai != 0){
                $nilai += $value->total_nilai;
            }else{
                $nilai += $value->nilai*$value->volume;
            }

        }
        // $nilai = $this->tender_rekanan->penawaran;

        return $nilai;
    }

    public function getNameAttribute(){
        return $this->tender->name;
    }


}
