<?php

namespace Modules\Tender\Entities;

use App\CustomModel;

class TenderJadwalPenawaran extends CustomModel
{
    protected $fillable = ['tender_id', 'penawaran_date', 'penawaran_ke', 'klarifikasi_date'];

    public function tender()
    {
        return $this->belongsTo('Modules\Tender\Entities\Tender')->orderBy("id","desc");
    }

}
