<?php

namespace Modules\Inventory\Entities;

use Illuminate\Database\Eloquent\Model;

class BrandOfCategory extends Model
{
    protected $fillable = ['category_id','brand_id'];

    public function category()
    {
    	return $this->belongsTo('Modules\Inventory\Entities\ItemCategory','category_id','id');
    }
    public function brand()
    {
    	return $this->belongsTo('Modules\Inventory\Entities\Brand');
    }
}
