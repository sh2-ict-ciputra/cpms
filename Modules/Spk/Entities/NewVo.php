<?php

namespace Modules\Spk\Entities;

use App\CustomModel;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Approval;

class NewVo extends CustomModel
{
    use Approval;

    protected $fillable = ['id','no','sik_id'];

    public function getSelfApprovalAttribute(){
        return $this->hasMany("Modules\Approval\Entities\ApprovalHistory","document_id");
    }

    public function approvalhistory(){                                                                             
        return $this->hasManyThrough("Modules\Approval\Entities\ApprovalHistory","App\User","id","approval_id");
    }

    public function detail(){
        return $this->hasMany("Modules\Spk\Entities\DetailVo","vo_id");
    }

    public function spk(){
        return $this->BelongsTo("Modules\Spk\Entities\Spk");
    }

    public function getNilaiAttribute()
    {
        $nilai = 0;
        foreach ($this->detail as $key => $value) {
            # code...
            if($value->total_nilai != null){
                $nilai = $nilai + ( $value->total_nilai );
            }
        }
        return $nilai;
    }

    public function project()
    {
        return $this->belongsTo('Modules\Project\Entities\Project', 'project_id');
    }

    public function sik(){
        return $this->BelongsTo("Modules\Progress\Entities\Siks");
    }

    public function getNameAttribute(){
        return $this->spk->tender->rab->name;
    }
    
}



    
