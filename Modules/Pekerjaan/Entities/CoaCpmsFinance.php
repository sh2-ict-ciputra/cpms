<?php

namespace Modules\Pekerjaan\Entities;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Eloquent\Model;

class CoaCpmsFinance extends Model
{
    public function itempekerjaan(){
        return $this->belongsTo("Modules\Pekerjaan\Entities\Itempekerjaan","itempekerjaan_id", "id");
    }

    public function tipe_coa(){
        return $this->belongsTo("Modules\Pekerjaan\Entities\TipeCoa", "tipe_coa_id",  "id");
    }

    public function project()
    {
        return $this->belongsTo('Modules\Project\Entities\Project', 'project_id');
    }
    public function pt(){
        return $this->belongsTo('Modules\Pt\Entities\Pt', 'pt_id');
    }

    public function getCoaFinanceAttribute(){
        $coa_finance = DB::connection('sqlsrv5')->table('dbo.view_coa')->where("project_id",$this->project->project_id)->where("pt_id", $this->pt->pt_id)->where("is_journal", 1)->select("*")->where("coa_id", $this->coa_finance_id)->get();

        return $coa_finance[0]->coa;
    }
    
}
