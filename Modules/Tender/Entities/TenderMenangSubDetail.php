<?php

namespace Modules\Tender\Entities;

use App\CustomModel;

class TenderMenangSubDetail extends CustomModel
{
    // protected $fillable = ['templatepekerjaan_detail_id','itempekerjaan_id','is_pembangunan','nilai','volume','satuan','ppn','description'];

    public function tender_menang_detail()
    {
        return $this->belongsTo('Modules\Tender\Entities\TenderMenangDetail');
    }


}
