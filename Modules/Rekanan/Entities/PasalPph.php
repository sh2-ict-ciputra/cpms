<?php

namespace Modules\Rekanan\Entities;

use App\CustomModel;

class PasalPph extends CustomModel
{
	protected $fillable = ['pasal'];

    public function pph(){
        return $this->hasMany("Modules\Rekanan\Entities\PphRekanan");
    }
}
