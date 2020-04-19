<?php

namespace Modules\User\Entities;

use App\CustomModel;

class UserWarehouse extends CustomModel
{
    //
    // protected $table = 'user_warehouse';
    protected $fillable = ['user_id','warehouse_id'];

    public function user()
    {
        return $this->belongsTo('Modules\User\Entities\User');
    }

    public function warehouse()
    {
        return $this->belongsTo('Modules\Inventory\Entities\Warehouse', 'warehouse_id');
    }
}
