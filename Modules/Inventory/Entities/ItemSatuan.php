<?php

namespace Modules\Inventory\Entities;

use App\CustomModel;

class ItemSatuan extends CustomModel
{
	protected $fillable = ['item_id','id_satuan','name','konversi'];

    public function item()
    {
        return $this->belongsTo('Modules\Inventory\Entities\Item', 'item_id');
    }
}
