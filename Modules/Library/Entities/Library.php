<?php

namespace Modules\Library\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Library extends Model
{
    protected $fillable = [];

    public function viewSupplierList(){
        return DB::table('v_library_supplier_list')->get();
    }

    
}
