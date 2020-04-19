<?php

namespace Modules\Rekanan\Entities;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Approval;

class PerpanjanganSpk extends Model
{
    use Approval;

    protected $fillable = ['update_finish','duration','reason','description','tanggal_disetujui','reason_disetujui'];
    
    public function getNilaiAttribute()
    {
        $nilai = 0;
        // foreach ($this->detail as $key => $value) {
        //     # code...
        //     if ( $value->volume != "" && $value->nilai != "" ){
        //         $nilai = $nilai + ( $value->volume * $value->nilai );
        //     }
        // }
        return $nilai;
    }

    public function spk(){
        return $this->BelongsTo("Modules\Spk\Entities\Spk");
    }

    public function getSelfApprovalAttribute(){
        return $this->hasMany("Modules\Approval\Entities\ApprovalHistory","document_id");
    }

    public function approvalhistory(){                                                                             
        return $this->hasManyThrough("Modules\Approval\Entities\ApprovalHistory","App\User","id","approval_id");
    }   

    public function getProjectAttribute()
    {
        //return $this->budget_tahunan->project;
        return $this->spk->project;
    }
}
