<?php

namespace Modules\Budget\Entities;

use Illuminate\Database\Eloquent\Model;
// use App\Traits\Approval;

class BudgetTahunanDetailHistory extends Model
{
    // use Approval;

    protected $dates = [];
    protected $fillable = [];
    
    public function itempekerjaan()
    {
        return $this->belongsTo('Modules\Pekerjaan\Entities\Itempekerjaan');
    }

    public function budget_tahunan_detail()
    {
        return $this->belongsTo('Modules\Budget\Entities\BudgetTahunanDetail');
    }
}
