<?php

namespace Modules\Spk\Entities;

use App\CustomModel;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Approval;

class SikUnit extends CustomModel
{
    use Approval;

    protected $fillable = ['id','no','sik_id'];

    public function sik(){
        return $this->BelongsTo("Modules\Progress\Entities\Siks");
    }

    public function unit(){
        return $this->BelongsTo("Modules\Tender\Entities\TenderUnit", "unit_id");
    }

}



    
