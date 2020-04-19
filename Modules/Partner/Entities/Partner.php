<?php 
namespace Modules\Partner\Entities;

use App\CustomModel;
use App\Traits\Approval;

class Partner extends CustomModel
{
    public function partner_project()
    {
        return $this->hasMany('Modules\Partner\Entities\PartnerProject');
    }
}