<?php

namespace Modules\Library\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LibrarySupplierProjectPODetail extends Model
{
    use SoftDeletes;
    protected $table = 'v_library_supplier_project_po_detail';
    protected $dateFormat = 'Y-m-d H:i:s+';
    //protected $hidden = ['created_at', 'updated_at', 'deleted_at', 'created_by', 'updated_by', 'deleted_by', 'inactive_at', 'inactive_by'];
}
