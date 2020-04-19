<?php

namespace Modules\Spk\Entities;

use App\CustomModel;

class IpkDefault extends CustomModel
{
    protected $fillable = ['name','itempekerjaan_id'];


    public function itempekerjaan()
    {
        return $this->belongsTo('Modules\Pekerjaan\Entities\Itempekerjaan', 'itempekerjaan_id');
    }
}
