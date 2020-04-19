<?php

namespace Modules\PenerimaanBarangPO\Entities;

use Illuminate\Database\Eloquent\Model;
use App\CustomModel;


class PenerimaanBarangPODetail extends CustomModel
{
    public $table = "penerimaan_barang_po_details";

    protected $fillable = ["id","penerimaan_barang_po_id","po_detail_id","user_id","quantity","satuan","description"];

    
    public function po_detail()
    {
    	return $this->belongsTo('Modules\PurchaseOrder\Entities\PurchaseOrderDetail','po_detail_id','id');
    }

    public function item()
    {
    	return $this->belongsTo('Modules\Inventory\Entities\ItemProject','item_id','id');
    }

    public function satuan()
    {
    	return $this->belongsTo('Modules\Inventory\Entities\ItemSatuan','satuan_id','id');
    }

    public function pbo()
    {
        return $this->belongsTo('Modules\PenerimaanBarangPO\Entities\PenerimaanBarangPO','penerimaan_barang_po_id','id');
    }

}
