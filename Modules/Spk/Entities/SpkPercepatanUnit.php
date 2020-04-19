<?php

namespace Modules\Spk\Entities;

use App\CustomModel;
use Illuminate\Database\Eloquent\Model;

class SpkPercepatanUnit extends CustomModel
{

    protected $fillable = [];


    public function spk_percepatan(){
        return $this->BelongsTo("Modules\Spk\Entities\SpkPercepatan");
    }

    public function unit(){
        return $this->BelongsTo("Modules\Tender\Entities\TenderUnit", "unit_id");
    }

}



    
