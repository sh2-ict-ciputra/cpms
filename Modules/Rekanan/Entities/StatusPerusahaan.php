<?php

namespace Modules\Rekanan\Entities;

use App\CustomModel;

class StatusPerusahaan extends CustomModel
{
	protected $fillable = ['name'];

    public function rekanan_group(){
        return $this->hasMany("Modules\Rekanan\Entities\RekananGroup");
    }
}
