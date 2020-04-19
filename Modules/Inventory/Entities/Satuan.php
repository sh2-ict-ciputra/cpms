<?php

namespace Modules\Inventory\Entities;

use App\CustomModel;

class Satuan extends CustomModel
{
	protected $table ='satuans';
    protected $fillable = ['name','konversi'];
}
