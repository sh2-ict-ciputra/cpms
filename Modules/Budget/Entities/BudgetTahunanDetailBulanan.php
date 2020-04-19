<?php

// use Modules\Budget\Entities\BudgetTahunanPeriode;

namespace Modules\Budget\Entities;

use Illuminate\Database\Eloquent\Model;

class BudgetTahunanDetailBulanan extends Model
{
    protected $fillable = ['budget_tahunan_detail_id','itempekerjaan_id','nilai','max_overbudget','description'];

    public function budget_tahunan_detail()
    {
        return $this->belongsTo('Modules\Budget\Entities\BudgetTahunanDetail');
    }

    public function itempekerjaans()
    {
        return $this->belongsTo('Modules\Pekerjaan\Entities\Itempekerjaan', 'itempekerjaan_id');
    }

}
