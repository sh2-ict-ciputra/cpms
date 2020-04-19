<?php

namespace Modules\Spk\Entities;

use App\CustomModel;

class SpkDetail extends CustomModel
{
    protected $fillable = ['spk_id','asset_id','asset_type','description'];

    public function spk()
    {
        return $this->belongsTo('Modules\Spk\Entities\Spk');
    }

    public function project()
    {
        if ($this->asset_type == 'Modules\Project\Entities\Project') {
            
            return $this->belongsTo('Modules\Project\Entities\Project', 'asset_id');

        }else{
            return NULL;
        }
    }
    public function kawasan()
    {
        if ($this->asset_type == 'Modules\Project\Entities\ProjectKawasan') {
            
            return $this->belongsTo('Modules\Project\Entities\ProjectKawasan', 'asset_id');

        }else{
            return NULL;
        }
    }
    public function unit()
    {
        if ($this->asset_type == 'Modules\Project\Entities\Unit') {
            
            return $this->belongsTo('Modules\Project\Entities\Unit', 'asset_id');

        }elseif($this->asset_type == 'Modules\Project\Entities\Project'){
            
            return $this->belongsTo('Modules\Project\Entities\Project', 'asset_id');

        }elseif($this->asset_type == 'Modules\Project\Entities\ProjectKawasan'){

            return $this->belongsTo('Modules\Project\Entities\ProjectKawasan', 'asset_id');
        }
    }

    public function details()
    {
        return $this->hasMany('Modules\Spk\Entities\SpkvoUnit');
    }

    public function details_with_vo()
    {
        return $this->hasMany('Modules\Spk\Entities\SpkvoUnit');
    }

    public function rab_unit()
    {
        return $this->belongsTo('Modules\Rab\Entities\RabUnit','asset_id');
    }

    public function spkvo_unit()
    {
        return $this->belongsTo('Modules\Spk\Entities\SpkvoUnit', 'head_id');
    }

    public function asset()
    {
        return $this->morphTo();
    }

    public function rab_detail(){
        return $this->hasMany("Modules\Rab\Entities\RabPekerjaan","rab_unit_id");
    }

    public function getNilaiAttribute(){
        $nilai = 0;
        foreach ($this->details_with_vo as $key => $value) {
            if($value->total_nilai == null){
                $nilai = $nilai + ($value->nilai * $value->volume);
            }else{
                $nilai = $nilai + ($value->total_nilai);
            }
        }
        return $nilai;
    }

     public function getProgressAttribute(){
        $total = 0;
        
        foreach ($this->details_with_vo as $key => $value) {
            $prg = $value->unit_progress->progresslapangan_percent;
            $total = $total + $prg;

        }
        return $total;
    }

    public function details_vo()
    {
        return $this->hasMany('Modules\Spk\Entities\SpkvoUnit')->where('head_type','Modules\Spk\Entities\Vo');
    }
}
