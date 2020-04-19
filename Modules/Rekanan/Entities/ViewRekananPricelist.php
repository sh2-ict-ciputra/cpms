<?php

namespace Modules\Rekanan\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ViewRekananPricelist extends Model
{    
    use SoftDeletes;
    protected $table = 'v_rekanan_pricelist_with_item';
}
