<?php

namespace Modules\Inventory\Entities;

use Illuminate\Database\Eloquent\Model;
use App\CustomModel;

class ItemProject extends CustomModel
{
    protected $fillable = ['item_id','project_id','default_warehouse_id','satuan_warning','stock_min','pph','is_inventory','is_consumable','description'];

    public function item()
    {
    	return $this->belongsTo('Modules\Inventory\Entities\Item');
    }

    public function project()
    {
    	return $this->belongsTo('Modules\Project\Entities\Project');
    }
}
