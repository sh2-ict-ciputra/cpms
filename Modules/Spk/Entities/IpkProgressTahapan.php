<?php

namespace Modules\Spk\Entities;

use App\CustomModel;

class IpkProgressTahapan extends CustomModel
{
    protected $fillable = ['progress_tambahan_id','ipk_tambahan_id','status_ceklis'];


    public function progress()
    {
        return $this->belongsTo('Modules\Spk\Entities\ProgressTambahan', 'progress_tambahan_id');
    }

    public function ipk()
    {
        return $this->belongsTo('Modules\Spk\Entities\IpkTambahan', 'ipk_tambahan_id');
    }
}
