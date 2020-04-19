<?php

namespace Modules\Rekanan\Entities;

use App\CustomModel;

class PphRekanan extends CustomModel
{
	protected $fillable = ['name','nilai','keterangan'];

    public function rekanan_group(){
        return $this->hasMany("Modules\Rekanan\Entities\RekananGroup");
    }

    public function pasal(){
        return $this->belongsTo("Modules\Rekanan\Entities\PasalPph",'pasal_pph_id');
    }
}
