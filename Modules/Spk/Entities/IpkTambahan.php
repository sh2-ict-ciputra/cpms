<?php

namespace Modules\Spk\Entities;

use App\CustomModel;

class IpkTambahan extends CustomModel
{
    protected $fillable = ['name'];


    public function idSpk()
    {
        return $this->belongsTo('Modules\Pekerjaan\Entities\Itempekerjaan', 'itempekerjaan_id');
    }
    
    public function tender_unit()
    {
        return $this->belongsTo('Modules\Tender\Entities\TenderUnit', 'unit_id');
    }

    public function ipk_default()
    {
        return $this->belongsTo('Modules\Spk\Entities\IpkDefault', 'ipkdefault_id');
    }

    public function ipk_progress_tahapan()
    {
        return $this->hasMany('Modules\Spk\Entities\IpkProgressTahapan', 'ipk_tambahan_id', 'id')->where("tipe", "spk");
    }
}
