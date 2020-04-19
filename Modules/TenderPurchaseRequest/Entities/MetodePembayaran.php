<?php

namespace Modules\TenderPurchaseRequest\Entities;

use Illuminate\Database\Eloquent\Model;

class MetodePembayaran extends Model
{
  protected $table = 'metode_pembayarans';
    protected $fillable = ['id','name'];
}
