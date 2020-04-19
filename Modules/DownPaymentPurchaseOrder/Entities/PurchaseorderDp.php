<?php

namespace Modules\DownPaymentPurchaseOrder\Entities;

use App\CustomModel;

class PurchaseorderDp extends CustomModel
{

    protected $fillable = ['purchaseorder_id','goodreceive_detail_id','goodreceive_detail_id_applied','date','percentage','description'];

    public function purchaseorder()
    {
        return $this->belongsTo('Modules\TenderPurchaseRequest\Entities\PurchaseOrder','purchaseorder_id','id');
    }

    public function goodreceive_detail()
    {
        return $this->belongsTo('Modules\GoodReceive\Entities\GoodreceiveDetail');
    }

    public function goodreceive_detail_applied()
    {
        return $this->belongsTo('Modules\GoodReceive\Entities', 'goodreceive_detail_id_applied');
    }
}
