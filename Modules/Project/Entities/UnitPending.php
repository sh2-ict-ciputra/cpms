<?php

namespace Modules\Project\Entities;

use Illuminate\Database\Eloquent\Model;

class UnitPending extends Model
{
    protected $fillable = [];

    public function status(){
    	return $this->belongsTo("Modules\Project\Entities\UnitResponses","status_reply");
    }

    public function unit(){
    	return $this->belongsTo("Modules\Project\Entities\Unit","unit_id","unit_id");
    }
}
