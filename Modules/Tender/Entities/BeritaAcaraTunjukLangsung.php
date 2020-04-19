<?php

namespace Modules\Tender\Entities;

use Illuminate\Database\Eloquent\Model;

class BeritaAcaraTunjukLangsung extends Model
{
    protected $fillable = [];

    public function tender(){
    	return $this->belongsTo("Modules\Tender\Entities\Tender");
    }
}
