<?php

namespace Modules\Library\Entities;

use Illuminate\Database\Eloquent\Model;
use App\CustomModel;

class MOU extends CustomModel
{
    protected $table = 'mou';
    protected $fillable = ['nomor_mou', 'rekan_id', 'project_id', 'item_id', 'jenis_mou', 'file_mou'];
}
