<?php

namespace Modules\Pekerjaan\Entities;

use Illuminate\Database\Eloquent\Model;

class TipeCoa extends Model
{
    public function coa_cpms_finance(){
        return $this->hasMany("Modules\Pekerjaan\Entities\CoaCpmsFinance","id", "peruntukan");
    }

}
