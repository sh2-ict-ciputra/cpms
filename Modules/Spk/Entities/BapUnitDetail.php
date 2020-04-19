<?php

namespace Modules\Spk\Entities;

use App\CustomModel;

class BapUnitDetail extends CustomModel
{
    public function bap()
    {
        return $this->belongsTo('Modules\Spk\Entities\Bap');
    }
    
}



    
