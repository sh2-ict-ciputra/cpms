<?php

namespace Modules\Budget\Entities;

use Illuminate\Database\Eloquent\Model;
// use App\Traits\Approval;

class BudgetDetailHistory extends Model
{
    // use Approval;

    protected $dates = ['start_date', 'end_date'];
    protected $fillable = ['project_id','name_pekerjaan','itempekerjaan_id','volume','nilai','satuan','description'];
    
    public function itempekerjaan()
    {
        return $this->belongsTo('Modules\Pekerjaan\Entities\Itempekerjaan');
    }

    public function budget_detail()
    {
        return $this->belongsTo('Modules\Budget\Entities\BudgetDetail');
    }
}
