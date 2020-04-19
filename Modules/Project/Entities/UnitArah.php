<?php

namespace Modules\Project\Entities;

use App\CustomModel;

class UnitArah extends CustomModel
{
    protected $fillable = ['name','description'];
    
	public function unit()
    {
        return $this->hasMany('Modules\Project\Entities\Unit');
    }

    public function kawasan(){
    	return $this->belongsTo("Modules\Project\Entities\ProjectKawasan","project_kawasan_id");
    }
}
