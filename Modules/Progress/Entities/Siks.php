<?php

namespace Modules\Progress\Entities;

use App\CustomModel;
use App\Traits\Approval;


class Siks extends CustomModel
{
	use Approval;
    protected $fillable = ['tgl_SIK','status_SIK','spk_id'];

	public function status_sik(){
		return $this->belongsTo("Modules\Progress\Entities\StatusSik");
	}

	public function sik_detail(){
		return $this->hasMany("Modules\Progress\Entities\SikDetail",'sik_id');
	}

	public function spk(){
		return $this->belongsTo("Modules\Spk\Entities\Spk");
	}

	public function vo(){
        return $this->BelongsTo("Modules\Spk\Entities\NewVo","id","sik_id");
	}
	
	public function sik_unit(){
		return $this->hasMany("Modules\Spk\Entities\SikUnit", "sik_id");
	}
}



    
