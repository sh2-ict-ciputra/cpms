<?php

namespace Modules\Library\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LibrarySupplierPricelistBerlaku extends Model
{
    //ambil table view
    use SoftDeletes;
    protected $table = 'v_library_supplier_pricelist_berlaku';
}
