<?php

namespace Modules\Rekanan\Entities;

use App\CustomModel;

class Rekanan extends CustomModel
{
	protected $fillable = ['kelas_id','description'];

    public function group()
    {
        return $this->belongsTo('Modules\Rekanan\Entities\RekananGroup', 'rekanan_group_id');
    }

    public function kelas()
    {
        return $this->belongsTo('Modules\Rekanan\Entities\RekananKelas', 'kelas_id');
    }

    public function city()
    {
        return $this->belongsTo('Modules\Country\Entities\City', 'surat_kota');
    }

    public function tender_rekanans()
    {
        return $this->hasMany('Modules\Tender\Entities\TenderRekanan')->orderBy("id","desc");
    }

    public function rekenings()
    {
        return $this->hasMany('Modules\Rekanan\Entities\RekananRekening');
    }

    public function supps()
    {
        return $this->hasMany('Modules\Rekanan\Entities\RekananSupp');
    }

    public function piutangs()
    {
        return $this->hasMany('Modules\Spk\Entities\Piutang');
    }

    public function getPiutangAttribute()
    {
        $piutang = $this->piutangs()->sum('nilai');

        foreach ($this->piutangs as $key => $value) 
        {
            $piutang = $piutang - $value->pembayarans()->sum('nilai');
        }

        return $piutang;
    }

    public function spks(){
        return $this->hasMany("Modules\Spk\Entities\Spk");
    }

    public function project(){
        return $this->belongsTo("Modules\Project\Entities\Project");
    }
    public function spkss(){
        return $this->hasMany("Modules\Spk\Entities\Spk",'rekanan_id');
    }

    public function getNilaiByRekananAttribute(){
        $nilai_spk = 0;
        foreach ($this->spkss as $key => $value) {
            # code...
            // $spk = \Modules\Spk\Entities\Spk::where('rekanan_id',$value->rekanan_id)->get();
                // $nilai_spk += $spk->nilai;
            // foreach ($spk as $key2 => $value2) {
                # code...
                $nilai_spk += $value->nilai;
            // }
            
        }
        return $nilai_spk;
    }
}
