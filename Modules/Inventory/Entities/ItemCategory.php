<?php

namespace Modules\Inventory\Entities;

use App\CustomModel;

class ItemCategory extends CustomModel
{
    protected $fillable = ['parent_id','name'];
    public function parent()
    {
        return $this->belongsTo('Modules\Inventory\Entities\ItemCategory', 'parent_id');
    }

    public function child()
    {
        return $this->hasMany('Modules\Inventory\Entities\ItemCategory','parent_id');
    }

    public function items()
    {
        return $this->hasMany('Modules\Inventory\Entities\Item');
    }

    public function users()
    {
        return $this->belongsToMany('Modules\User\Entities\User', 'item_category_user');
    }

    public function brands_category()
    {
        return $this->hasMany('Modules\Inventory\Entities\BrandOfCategory','category_id');
    }
}
