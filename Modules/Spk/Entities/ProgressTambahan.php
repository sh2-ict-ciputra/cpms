<?php

namespace Modules\Spk\Entities;

use App\CustomModel;

class ProgressTambahan extends CustomModel
{
    protected $fillable = ['nama_progress','status','volume','spk_id','itempekerjaan_id'];


    public function itempekerjaan()
    {
        return $this->belongsTo('Modules\Pekerjaan\Entities\Itempekerjaan', 'itempekerjaan_id');
    }

    public function ipk_progress()
    {
        return $this->hasMany('Modules\Spk\Entities\IpkProgressTahapan', 'progress_tambahan_id', 'id')->where("ipk_progress_tahapans.tipe","spk");
    }

    public function pengajuan()
    {
        return $this->belongsTo('Modules\Spk\Entities\RekananPengajuanIpk', 'id', 'progress_tambahan_id')->where("rekanan_pengajuan_ipks.tipe","spk");
    }

    public function spk()
    {
        return $this->belongsTo('Modules\Spk\Entities\Spk', 'spk_id');
    }

    public function tender_unit()
    {
        return $this->belongsTo('Modules\Tender\Entities\TenderUnit', 'unit_id');
    }

    public function progress_default()
    {
        return $this->belongsTo('Modules\Spk\Entities\ProgressDefault', 'progressdefault_id');
    }
    
    public function units()
    {
        return $this->belongsTo('Modules\Tender\Entities\TenderUnit','unit_id')->where('deleted_at',null);;
    }

    public function ipk_progress_tahapan()
    {
        return $this->hasMany('Modules\Spk\Entities\IpkProgressTahapan', 'progress_tambahan_id', 'id')->where("tipe", "spk");
    }
}
