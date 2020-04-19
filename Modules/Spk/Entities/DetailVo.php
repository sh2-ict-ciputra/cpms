<?php

namespace Modules\Spk\Entities;

use App\CustomModel;

class DetailVo extends CustomModel
{
    protected $fillable = [
        'project_id',
        'unit_id',
        'itempekerjaan_id',
        'progresslapangan_percent',
        'mulai_jadwal_date', 
        'selesai_jadwal_date', 
        'selesai_actual_date',
        'urutitem',
        'termin',
        'nilai',
        'volume',
        'satuan',
        'is_pembangunan'
    ];

    public function itempekerjaan()
    {
        return $this->belongsTo('Modules\Pekerjaan\Entities\Itempekerjaan', 'itempekerjaan_id');
    }

    public function progresstambahan_vo()
    {
        return $this->belongsTo('Modules\Spk\Entities\ProgressTambahanVo', 'detail_vo_id');
    }

    public function unit()
    {
        return $this->belongsTo('Modules\Tender\Entities\TenderUnit');
    }

    public function vo()
    {
        return $this->belongsTo('Modules\Spk\Entities\NewVo', 'vo_id');
    }
    
    public function sub_detail_vo(){
        return $this->hasMany('Modules\Spk\Entities\SubDetailVo');
    }
}



    
