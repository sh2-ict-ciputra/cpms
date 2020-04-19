<?php 

namespace Modules\Voucher\Entities;

use App\CustomModel;

class BankGaransi extends CustomModel
{

    public function voucher()
    {
        return $this->belongsTo('Modules\Voucher\Entities\Voucher');
    }
}
