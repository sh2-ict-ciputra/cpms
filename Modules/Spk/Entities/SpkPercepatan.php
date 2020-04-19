<?php

namespace Modules\Spk\Entities;

use App\CustomModel;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Approval;

class SpkPercepatan extends CustomModel
{
    use Approval;

    protected $fillable = ['id','no','sik_id','tanggal_finih','nilai_persen','description'];

    public function getSelfApprovalAttribute(){
        return $this->hasMany("Modules\Approval\Entities\ApprovalHistory","document_id");
    }

    public function approvalhistory(){                                                                             
        return $this->hasManyThrough("Modules\Approval\Entities\ApprovalHistory","App\User","id","approval_id");
    }

    public function spk(){
        return $this->BelongsTo("Modules\Spk\Entities\Spk");
    }

    public function getProjectAttribute()
    {
        return $this->belongsTo('Modules\Project\Entities\Project');
    }

    // public function getNilaiAttribute()
    // {
    //     $nilai = 0;
    //     return $nilai;
    // }

    public function percepatan_unit(){
        return $this->hasMany("Modules\Spk\Entities\SpkPercepatanUnit");
    }

    public function getNilaiAttribute()
    {
        $nilai = (($this->spk->nilai/count($this->spk->tender->units))*($this->nilai_persen/100)*count($this->percepatan_unit));

        return $nilai;
    }

}



    
