<?php

namespace Modules\Department\Entities;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = [];

    public function coas()
    {
        return $this->belongsToMany('App\Coa');
    }

    public function budgets(){
    	return $this->hasMany("Modules\Budget\Entities\Budget");
    }
    
    public function workorders(){
      return $this->hasMany("Modules\Workorder\Entities\Workorder","department_from");
    }

    public function getSpkAttribute(){
      $spks = array();
      $start = 0;
      foreach ($this->workorders as $key => $value) {
        foreach ($value->rabs as $key2 => $value2) {
          foreach ($value2->tenders as $key3 => $value3) {
            foreach ( $value3->spks as $key4 => $value4){

            $spks[$start] = array(
              "id" => $value4->id,
              "name" => $value4->name,
              "no" =>$value4->no,
            );
            $start++;
            }
          }
        }
      }
      return $spks;
    }
}
