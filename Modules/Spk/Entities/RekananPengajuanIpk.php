<?php

namespace Modules\Spk\Entities;

use App\CustomModel;

class RekananPengajuanIpk extends CustomModel
{
    protected $fillable = ['progress_tambahan_id','ipk_tambahan_id','status_ceklis','unit_id'];


    public function progress()
    {
        return $this->belongsTo('Modules\Spk\Entities\ProgressTambahan', 'progress_tambahan_id');
    }

    public function progress_vo()
    {
        return $this->belongsTo('Modules\Spk\Entities\ProgressTambahanVo', 'progress_tambahan_id');
    }

    public function rekanan()
    {
        return $this->belongsTo("Modules\Rekanan\Entities\RekananGroup","rekanan_id");
    }

    public function tender_unit()
    {
        return $this->belongsTo('Modules\Tender\Entities\TenderUnit','unit_id','id')->where('deleted_at',null);;
    }

    // public function spk()
    // {
    //     return $this->belongsTo('Modules\Spk\Entities\Spk', 'spk_id', 'id');
    // }
}
