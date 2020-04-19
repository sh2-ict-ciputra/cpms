<?php

namespace Modules\Spk\Entities;

use App\CustomModel;

class SubDetailVo extends CustomModel
{
    protected $fillable = [];

    public function detail_vo()
    {
        return $this->belongsTo('Modules\Spk\Entities\DetailVo', 'detail_vo_id');
    }
    
}



    
