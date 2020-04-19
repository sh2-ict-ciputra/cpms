<?php

namespace Modules\Progress\Entities;

use App\CustomModel;
use Illuminate\Database\Eloquent\Model;

use App\Traits\Approval;


class SikDetail extends CustomModel
{
	use Approval;

    protected $fillable = ['satuan','volume','keterangan','nilai'];

	public function item_detail(){
		return $this->belongsTo("Modules\Pekerjaan\Entities\itempekerjaan");
	}

	public function unit(){
        return $this->BelongsTo("Modules\Tender\Entities\TenderUnit", "unit_id");
	}
	
	public function sik_detail(){
		return $this->hasMany("Modules\Progress\Entities\SikSubDetail");
	}
}


    
