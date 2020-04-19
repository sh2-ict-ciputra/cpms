<?php

namespace Modules\Budget\Entities;

use Illuminate\Database\Eloquent\Model;
// use App\Traits\Approval;

class BudgetTahunanDetailBulananHistory extends Model
{
    // use Approval;

    protected $dates = [];
    protected $fillable = [];
    
    public function itempekerjaan()
    {
        return $this->belongsTo('Modules\Pekerjaan\Entities\Itempekerjaan');
    }

    public function budget_tahunan_bulanan()
    {
        return $this->belongsTo('Modules\Budget\Entities\BudgetTahunanDetailBulanan');
    }
}
