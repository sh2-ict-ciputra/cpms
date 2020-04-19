<?php

namespace Modules\PenerimaanBarangPO\Entities;

use Illuminate\Database\Eloquent\Model;
use App\CustomModel;
use App\Traits\Approval;



class PenerimaanBonusDetail extends CustomModel
{


    use Approval;

    public function penerimaan_bonus()
    {
        return $this->belongsTo('Modules\PenerimaanBarangPO\Entities\PenerimaanBonus','penerimaan_bonus_id','id');
    }


    public function warehouse()
    {
        return $this->belongsTo('Modules\Inventory\Entities\Warehouse', 'gudang_id');
    }

}
