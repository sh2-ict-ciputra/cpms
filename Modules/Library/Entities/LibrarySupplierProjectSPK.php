<?php

namespace Modules\Library\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


// SPK : untuk rekanan dengan jenis usaha jasa
class LibrarySupplierProjectSPK extends Model
{
    //ambil table view
    use SoftDeletes;
    protected $table = 'v_library_supplier_project_spk';
}
