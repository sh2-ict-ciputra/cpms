<?php

namespace Modules\Library\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// PO : untuk rekanan dengan jenis usaha barang
class LibrarySupplierProjectPO extends Model
{
    //ambil table view
    use SoftDeletes;
    protected $table = 'v_library_supplier_project_po';
}
