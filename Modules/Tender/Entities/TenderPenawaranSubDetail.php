<?php

namespace Modules\Tender\Entities;

use App\CustomModel;

class TenderPenawaranSubDetail extends CustomModel
{
    protected $fillable = ['tender_penawaran_id', 'rab_pekerjaan_id', 'keterangan', 'nilai', 'volume'];

    public function tender_penawaran_detail()
    {
        return $this->belongsTo('Modules\Tender\Entities\TenderPenawaranDetail');
    }

    public function rab_sub_pekerjaan()
    {
        return $this->belongsTo('Modules\Rab\Entities\RabSubPekerjaan');
    }

   
}
