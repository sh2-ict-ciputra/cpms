<?php

namespace Modules\PenerimaanBarangPO\Entities;

use Illuminate\Database\Eloquent\Model;
use App\CustomModel;
use App\Traits\Approval;



class PenerimaanBonus extends CustomModel
{


    use Approval;

    public function pbo()
    {
        return $this->belongsTo('Modules\PenerimaanBarangPO\Entities\PenerimaanBarangPO','penerimaan_barang_po_id','id');
    }

    public function penerimaan_bonus_detail()
    {
        return $this->hasMany('Modules\PenerimaanBarangPO\Entities\PenerimaanBonusDetail','penerimaan_bonus_id','id');
    }

}
