<?php

namespace Modules\PenerimaanBarangPO\Entities;

use Illuminate\Database\Eloquent\Model;
use App\CustomModel;
use App\Traits\Approval;



class PenerimaanBarangPO extends CustomModel
{
    public $table = "penerimaan_barang_pos";

    protected $fillable = ["id","no"];

    use Approval;


    // public function approval_morphic()
    // {
    //     return $this->morphMany('Modules\Approval\Entities\Approval','document','document_type','document_id');
    // }

    public function details()
    {
    	return $this->hasMany('Modules\PenerimaanBarangPO\Entities\PenerimaanBarangPODetail','penerimaan_barang_po_id');
    }

    public function getNilaiAttribute()
    {
        return 0;
    }

    public function project()
    {
        return $this->belongsTo('Modules\Project\Entities\Project','project_for_id');
    }

    public function penerimaan_bonus()
    {
        return $this->belongsTo('Modules\PenerimaanBarangPO\Entities\PenerimaanBonus','penerimaan_bonus_id','id');
    }

}
