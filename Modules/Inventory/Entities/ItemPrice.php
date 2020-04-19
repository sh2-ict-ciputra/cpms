<?php

namespace Modules\Inventory\Entities;

use App\CustomModel;

class ItemPrice extends CustomModel
{
    protected $fillable = ['id','item_id','item_satuan_id','price','project_id','ppn','date_price'];

    public function item()
    {
        return $this->belongsTo('Modules\Inventory\Entities\ItemProject', 'item_id');
    }

    public function project()
    {
        return $this->belongsTo('Modules\Project\Entities\Project', 'project_id');
    }

    public function satuan()
    {
    	return $this->belongsTo('Modules\Inventory\Entities\ItemSatuan','item_satuan_id');
    }
}
