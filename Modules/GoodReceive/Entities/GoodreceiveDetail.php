<?php

namespace Modules\GoodReceive\Entities;

use Illuminate\Database\Eloquent\Model;

class GoodreceiveDetail extends Model
{
	protected $table = 'goodreceive_details';
    protected $fillable = ['goodreceive_id','penerimaan_barang_po_detail_id','item_id','satuan_id','quantity','price','ppn','pph'];

    public function item()
    {
    	return $this->belongsTo('Modules\Inventory\Entities\ItemProject','item_id','id');
    }
    public function satuan()
    {
    	return $this->belongsTo('Modules\Inventory\Entities\ItemSatuan','satuan_id','id');
    }
}
