<?php

namespace Modules\Rab\Entities;

use Illuminate\Database\Eloquent\Model;

class RabSubPekerjaan extends Model
{
    protected $fillable = [];

    public function rab_pekerjaan()
    {
        return $this->belongsTo('Modules\Rab\Entities\RabPekerjaan');
    }
    
}
