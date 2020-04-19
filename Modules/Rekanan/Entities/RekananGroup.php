<?php

namespace Modules\Rekanan\Entities;

use App\CustomModel;

class RekananGroup extends CustomModel
{
    protected $fillable = ['npwp_kota','code','name','pph_percent','npwp_no','npwp_image','npwp_alamat','description'];

    public function rekanans()
    {
        return $this->hasMany('Modules\Rekanan\Entities\Rekanan');
    }

    public function spks(){
    	return $this->hasMany("Modules\Spk\Entities\Spk","rekanan_id");
    }

    public function spesifikasi(){
    	return $this->hasMany("Modules\Rekanan\Entities\RekananSpecification");
    }

    public function user_rekanan(){
    	return $this->hasOne("Modules\Rekanan\Entities\UserRekanan");
    }

    public function project(){
        return $this->belongsTo("Modules\Project\Entities\Project");
    }

    public function supps(){
        return $this->hasMany("Modules\Rekanan\Entities\RekananSupp","rekanan_id");
    }
    
    public function status_perusahaan(){
        return $this->belongsTo("Modules\Rekanan\Entities\StatusPerusahaan");
    }

    public function pph_rekanan(){
        return $this->belongsTo("Modules\Rekanan\Entities\PphRekanan","pph_rekanan_id");
    }
}
