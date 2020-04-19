<?php

namespace Modules\Library\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LibrarySupplierDetails extends Model
{
    use SoftDeletes;
    
    protected $table = 'v_library_supplier_details';
}
