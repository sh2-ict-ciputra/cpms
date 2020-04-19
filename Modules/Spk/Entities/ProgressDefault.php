<?php

namespace Modules\Spk\Entities;

use App\CustomModel;

class ProgressDefault extends CustomModel
{
    protected $fillable = ['nama_progress','status','volume','spk_id','itempekerjaan_id'];


    // public function idSpk()
    // {
    //     return $this->belongsTo('Modules\Pekerjaan\Entities\Itempekerjaan', 'itempekerjaan_id');
    // }
}
