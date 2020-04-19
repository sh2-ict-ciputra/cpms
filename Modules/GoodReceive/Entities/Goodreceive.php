<?php

namespace Modules\GoodReceive\Entities;

use App\CustomModel;

class Goodreceive extends CustomModel
{
	protected $table = 'goodreceives';
    protected $fillable = ['purchaseorder_id','sumber_id','sumber_type','approval_status_id','no','date','is_downpayment'];

    public function sumber()
    {
    	return $this->morphTo();
    }

    public function po()
    {
    	return $this->belongsTo("Modules\TenderPurchaseRequest\Entities\PurchaseOrder","purchaseorder_id","id");
    }

    public function detail()
    {
    	return $this->hasMany('Modules\GoodReceive\Entities\GoodreceiveDetail');
    }
}
