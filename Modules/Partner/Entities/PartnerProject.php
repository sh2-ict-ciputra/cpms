<?php 
namespace Modules\Partner\Entities;

use App\CustomModel;
use App\Traits\Approval;

class PartnerProject extends CustomModel
{
    public function partner()
    {
        return $this->belongsTo('Modules\Partner\Entities\Partner');
    }

    public function project()
    {
        return $this->belongsTo('Modules\Project\Entities\Project');
    }
}