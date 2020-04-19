<?php

namespace Modules\Progress\Entities;

use App\CustomModel;
use Illuminate\Database\Eloquent\Model;

use App\Traits\Approval;


class SikSubDetail extends CustomModel
{
	use Approval;

    protected $fillable = ['satuan','volume','keterangan','nilai'];

	public function sik_detail(){
		return $this->belongsTo("Modules\Progress\Entities\SikDetail");
	}
}


    
