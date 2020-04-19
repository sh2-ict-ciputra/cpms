<?php

namespace Modules\Spk\Entities;

use App\CustomModel;
use App\Traits\Approval;
use Modules\Spk\Entities\NewVo;
use Modules\Spk\Entities\DetailVo;

class Spk extends CustomModel
{
    use Approval;

    protected $fillable = [
        'project_id',
        'rekanan_id',
        'tender_rekanan_id',
        'spk_type_id',
        'spk_parent_id',
        'no',
        'date',
        'name',
        'start_date',
        'finish_date',
        'fa_date',
        'dp_percent',
        'denda_a',
        'denda_b',
        'matauang',
        'nilai_tukar',
        'jenis_kontrak',
        'memo_cara_bayar',
        'memo_lingkup_kerja',
        'is_instruksilangsung',
        'description',
        'coa_pph_default_id',
    ];
    protected $dates = ['date','start_date', 'finish_date', 'fa_date'];

    public function getRekananAttribute()
    {
        if ($this->tender_rekanan == NULL) 
        {
            return NULL;
        }
        return $this->tender_rekanan->rekanan;
    }

    public function rekanans(){
        return $this->belongsTo("Modules\Rekanan\Entities\RekananGroup","rekanan_id");
    }

    public function tender_rekanan()
    {
        return $this->belongsTo('Modules\Tender\Entities\TenderRekanan','tender_rekanan_id');
    }

    public function getPtAttribute()
    {
        if($this->tender->rab->workorder->pt_id != ''){
            return $this->tender->rab->workorder->pt_wo;
        }else{
            return $this->tender->rab->workorder->pt;
        }
        // return $this->tender->rab->workorder->pt_wo;
    }

    public function getTenderAttribute()
    {
        if ($this->tender_rekanan == NULL) 
        {
            return NULL;
        }
       return $this->tender_rekanan()->first()->tender;
    }

    public function type()
    {
        return $this->belongsTo('Modules\Spk\Entities\SpkType','spk_type_id');
    }

    public function parent()
    {
        return $this->belongsTo('Modules\Spk\Entities\Spk');
    }

    public function project()
    {
        return $this->belongsTo('Modules\Project\Entities\Project');
    }

    public function details()
    {
        return $this->hasMany('Modules\Spk\Entities\SpkDetail');
    }

    public function dp_pengembalians()
    {
        return $this->hasMany('Modules\Spk\Entities\SpkPengembalian');
    }


    public function baps()
    {
        return $this->hasMany('Modules\Spk\Entities\Bap')->distinct();
    }


    // Voucher
    public function vouchers()
    {
        /*return \Modules\Voucher\Entities\Voucher::whereHas('bap', function($bap){
            $bap->where('spk_id',$this->id);
        } );*/
        return $this->hasMany("Modules\Voucher\Entities\Voucher");
    }

    public function getVouchersAttribute()
    {
        return $this->vouchers()->get();
    }

    // end of Voucher

    public function retensis()
    {
        return $this->hasMany('Modules\Spk\Entities\SpkRetensi');
    }

    public function suratinstruksis()
    {
        return $this->hasMany('Modules\Spk\Entities\Suratinstruksi');
    }

    public function siks()
    {
        return $this->suratinstruksis()->where('type', '=' , 'kerja');
    }

    public function sils()
    {
        return $this->suratinstruksis()->where('type', '=' , 'lapangan');
    }

    public function vos()
    {
        return $this->hasManyThrough('Modules\Spk\Entities\Vo', 'Modules\Spk\Entities\Suratinstruksi');
    }

    public function units()
    {
        return  \Modules\Project\Entities\Unit::whereHas('spk_details', function($q){ $q->where('spk_id', $this->id); });
    }

    public function getUnitsAttribute()
    {
        return $this->units()->get();
    }

    public function detail_units()
    {
        return $this->morphMany('Modules\Spk\Entities\SpkvoUnit', 'head');
    }

    public function getCoasAttribute()
    {
        return $this->progresses->first()->itempekerjaan->coas;
    }

    public function pics()
    {
        return $this->morphMany('Modules\Spk\Entities\SpkPoPic', 'head');
    }


    public function templatepekerjaans()
    {
       return \Modules\Project\Entities\Templatepekerjaan::whereHas('spkvo_units', function($q) 
        {
            $q->whereHas('spk_detail', function($r) 
            {
                $r->where('spk_id', $this->id);
            });
        });
    }

    public function templatepekerjaan_details()
    {
        return \Modules\Project\Entities\TemplatepekerjaanDetail::whereHas('spkvo_units', function($q) 
        {
            $q->whereHas('spk_detail', function($r) 
            {
                $r->where('spk_id', $this->id);
            });
        });
    }

    public function getTemplatepekerjaanDetailsAttribute()
    {
        return $this->templatepekerjaan_detailss()->get();
    }

    public function progresses()
    {
        return \Modules\Project\Entities\UnitProgress::whereHas('spkvo_unit', function($spkvounit){
            $spkvounit->where('head_type','Modules\Spk\Entities\Spk')->where('head_id', $this->id);
        });
    }

    public function getProgressesAttribute()
    {
        return $this->progresses()->get();
    }

    public function getNilaiAttribute()
    {
        $nilai = 0;
        foreach ($this->tender_rekanan->menangs->first()->details as $key => $each) 
        {
            if($each->total_nilai != null && $each->total_nilai != 0){
                $nilai = $nilai + $each->total_nilai;
            }else{
                $nilai = $nilai + ($each->nilai *$each->volume);
            }
        }
        return $nilai * count($this->details);
    }

    # tidak digunakan lagi, karena tidak menghitung retensi
    // public function getPpnAttribute()
    // {
    //     $nilai = 0;
    //     foreach ($this->detail_units as $key => $each) 
    //     {
    //         $nilai_now = $each->ppn * $each->nilai * $each->volume;
    //         $nilai = $nilai + $nilai_now;
    //     }
    //     return $nilai;
    // }
    // 
    public function getNilaiPpnVoAttribute()
    {
        $nilai = 0;
        foreach($this->vos as $key => $each)
        {
            $nilai += $each->nilai_ppn;
        }
        return $nilai;
    }

    public function getNilaiVoAttribute()
    {
        $nilai = 0;
        $total_pervo = 0;
        foreach ($this->new_vo as $key => $each) {
            $approval = \Modules\Approval\Entities\Approval::where("document_type", "Modules\Spk\Entities\NewVo")->where("document_id",$each->id)->first();

            if($approval != null){
                $approval = $approval->approval_action_id;
            }
            if($approval == 6){
                $total_pervo = 0;
                if($each->sik->status_sik_id == 1){
                    foreach ($each->detail as $key => $value) {
                        # code...
                        $total_pervo = $total_pervo + ($value->total_nilai);
                    }
                }elseif($each->sik->status_sik_id == 3){
                    foreach ($each->detail as $key => $value) {
                        # code...
                        $total_pervo = $total_pervo - ($value->total_nilai);
                    }
                }
            }else{
                $total_pervo = 0;
            }
            $nilai = $nilai + $total_pervo;
        }
        return $nilai;
    }

    public function getNilaiPpnVoKontrakAttribute()
    {
        $nilai = 0;
        foreach ($this->vos as $key => $each) 
        {
            $nilai = $nilai + $each->nilai_ppn_kontrak;
        }
        return $nilai;
    }

    public function getNilaiVoucherAttribute()
    {
        $nilai = 0;
        foreach ($this->vouchers as $key => $each) 
        {
            $nilai = $nilai + $each->nilai;
        }
        return $nilai;
    }

    public function getNilaiBapAttribute()
    {
        $nilai = 0;
        if ( $this->baps != "" ){
            return $this->baps->sum("nilai_bap_2");
        }else{
            return 0;
        }
       /* $latest_bap = $this->baps()->latest()->first();
        if ($latest_bap) 
        {
            return $latest_bap->nilai_bap_termin;
        }else{
            return 0;
        }
    */
    }

    public function getReportNilaiBapAttribute(){
        $nilai = 0;
        foreach ($this->baps as $key => $value) {
            # code...
            if ( $value->nilai ){
                $nilai = $nilai + $value->nilai_sertifikat;
            }
        }   
        return round($nilai);
    }

    public function nilai()
    {
        if ($this->baps()->latest()->first()) 
        {
            $latest_bap = $this->baps()->latest()->first();
            return $latest_bap->nilai_sertifikat;
        }else{
            return 0;
        }
    }

    public function getNilaiKumulatifAttribute()
    {
        // nilai spk + vo  
        return $this->nilai + $this->nilai_vo;
    }

    public function getLapanganAttribute()
    {
    //    $nilai = 0;
    //    foreach ($this->tender->units as $key => $value) {
    //        $nilai = $nilai + $value->progress;
    //    }

        // $nilai = 0;
        // $main_nilai = 0;
        // $real_bobot_s = 0;
        // $real_bobot = 0;
        // $main_percent = 0;
        // $real_bobot_spk = 0;
        // $volume_item_vo = 0;
        // $unit = [];
        // $vo_pengurangan = $this->new_vo->where("tipe",3);
        // foreach ($this->tender->units as $key => $value1) {
        //     # code...
        //     $nilai_unit = 0;
        //     foreach ($value1->unit_progress as $key => $value2) {
        //         if($value2->volume != 0){

        //             $vo = NewVo::where("spk_id",$this->id)->where('tipe',1)->get();
        //             $volume_item_vo = 0;
        //             foreach ($vo as $key => $value3) {
        //                 # code...
        //                 if($value3->approval->approval_action_id == 6){
        //                     $volume_item_vo += $value3->detail->where("unit_id",$value2->unit_id)->where("itempekerjaan_id",$value2->itempekerjaan_id)->sum("volume");
        //                 }
        //             } 

        //             $nilai = $nilai + (($value2->volume + $volume_item_vo) * $value2->nilai);
        //             $nilai_unit += (($value2->volume + $volume_item_vo) * $value2->nilai);
        //         }
        //     }
        //     $kurang = 0;
        //     foreach ($vo_pengurangan as $key => $value) {
        //         # code...
        //         if($value->approval->approval_action_id == 6){
        //             $pengurangan = $value->detail->where('unit_id',$value1->id);
        //             foreach ($pengurangan as $key2 => $value2) {
        //                 # code...
        //                 $kurang += $value2->volume * $value2->nilai;
        //             }
        //         }
        //     }
        //     // return $kurang;
        //     $nilai_per_unit[$value1->id] = $nilai_unit - $kurang;

        // }

        // // return $nilai_per_unit[$value1->id];
        // $nilaitot_kurang = 0;
        // foreach ($vo_pengurangan as $key => $value) {
        //     # code...
        //     if($value->approval->approval_action_id == 6){
        //         foreach ($value->detail as $key2 => $value2) {
        //             # code...
        //             $nilaitot_kurang +=  $value2->volume * $value2->nilai;
        //         }
        //     }
        // }
        // //No VO
        // foreach ($this->tender->units as $key => $value1) {
        //     # code...
        //     $percent_item_vo = 0;
        //     $volume_item_vo = 0;
        //     $real_bobot = 0;
        //     foreach ($value1->unit_progress as $key => $value2) {
        //         if ( $value2->spkvo_unit != ""){
        //             if ( $value2->spkvo_unit->head_type == "Modules\Spk\Entities\Spk" ){
        //                 if($value2->volume != 0){
        //                     $volume_item_vo = 0;
        //                     foreach ($vo as $key => $value3) {
        //                         # code...
        //                         if($value3->approval->approval_action_id == 6){
        //                             $volume_item_vo = $volume_item_vo + $value3->detail->where("unit_id",$value2->unit_id)->where("itempekerjaan_id",$value2->itempekerjaan_id)->sum("volume");
        //                         }
        //                     }

        //                     $percent_item_vo = 0;
        //                     $kurang = 0;
        //                     foreach ($vo_pengurangan as $key => $value) {
        //                         # code...
        //                         if($value->approval->approval_action_id == 6){
        //                             $pengurangan = $value->detail->where('itempekerjaan_id', $value2->itempekerjaan_id)->where('unit_id',$value2->unit_id)->first();
                        
        //                             $kurang += $pengurangan['volume'];
        //                         }
        //                     }

        //                     foreach ($vo as $key => $value3) {
        //                         # code...
        //                         if($value3->approval->approval_action_id == 6){
        //                             $item_vo =  $value3->detail->where("unit_id",$value2->unit_id)->where("itempekerjaan_id",$value2->itempekerjaan_id)->first();
                                    
        //                             if(isset($item_vo)){
        //                                 $percent_item_vo =  $percent_item_vo + (($item_vo->volume*($item_vo->progresslapangan_percent/100))/($value2->volume + $volume_item_vo)*100);
        //                             }
        //                         }
        //                     }

                            
        //                     $jml_vo = count($vo);
        //                     $percent_saat_ini = $percent_item_vo + (($value2->volume*($value2->progresslapangan_percent/100))/($value2->volume + $volume_item_vo)*100);
        //                     // return $percent_saat_ini ;
        //                     $kurang = 0;
        //                     foreach ($vo_pengurangan as $key => $value) {
        //                         # code...
        //                         if($value->approval->approval_action_id == 6){
        //                             $pengurangan = $value->detail->where('itempekerjaan_id', $value2->itempekerjaan_id)->where('unit_id',$value2->unit_id)->first();
                    
        //                             $kurang += $pengurangan['volume'];
        //                         }
        //                     }

        //                     $main_nilai = ( ($value2->volume + $volume_item_vo) * $value2->nilai ) + $main_nilai;
        //                     // if($nilai != null){
        //                     // $real_bobot_s =  (( ( $percent_saat_ini ) * 100 ) / 100 ) * ( ((($value2->volume- $kurang) + $volume_item_vo) * $value2->nilai)/(($nilai-$nilaitot_kurang)/count($this->tender->units)) *100 ) ;

        //                     if(($nilai_per_unit[$value1->id]) != 0){
        //                         $real_bobot_s =  (( ( $percent_saat_ini ) * 100 ) / 100 ) * ( ((($value2->volume - $kurang) + $volume_item_vo) * $value2->nilai)/(($nilai_per_unit[$value1->id])) *100 );
        //                     }
        //                     // }

        //                     $real_bobot = $real_bobot + $real_bobot_s;       
        //                 }         
        //             }
        //         }
        //     }

        //     $real_bobot_spk = $real_bobot_spk + $real_bobot;
        // }
        // $real_bobot_spk = $real_bobot_spk/count($this->tender->units);


        $nilai = 0;
        $total_nilai = 0;
        $main_nilai = 0;
        $real_bobot_s = 0;
        $real_bobot = 0;
        $main_percent = 0;
        $real_bobot_spk = 0;
        $volume_item_vo = 0;
        $unit = [];
        $nilai_per_unit = [];
        $total_nilai_per_unit = [];
        $vo_pengurangan = $this->new_vo->where("tipe",1);

        foreach ($this->tender->units as $key => $value1) {
            # code...
            $nilai_unit = 0;
            $total_nilai_unit = 0;
            foreach ($value1->unit_progress as $key => $value2) {
                if($value2->volume != 0){

                    $vo = NewVo::where("spk_id",$this->id)->where('tipe',1)->get();
                    $volume_item_vo = 0;
                    $total_item_vo = 0;
                    foreach ($vo as $key => $value3) {
                        # code...
                        if($value3->approval->approval_action_id == 6){
                            $volume_item_vo += $value3->detail->where("unit_id",$value2->unit_id)->where("itempekerjaan_id",$value2->itempekerjaan_id)->where("volume",">", 0)->sum("volume");

                            $total_item_vo += $value3->detail->where("unit_id",$value2->unit_id)->where("itempekerjaan_id",$value2->itempekerjaan_id)->where("volume",">", 0)->sum("total_nilai");
                        }
                    } 
                    // printf($total_item_vo);
                    // echo("<br>");
                    $nilai += (($value2->volume + $volume_item_vo) * $value2->nilai);
                    $total_nilai += ($value2->total_nilai + $total_item_vo);
                    $nilai_unit += (($value2->volume + $volume_item_vo) * $value2->nilai);
                    $total_nilai_unit += ($value2->total_nilai + $total_item_vo);
                }
            }
            // return $total_nilai;

            $kurang = 0;
            $total_kurang = 0;
            foreach ($vo_pengurangan as $key => $value) {
                # code...
                if($value->approval->approval_action_id == 6){
                    $pengurangan = $value->detail->where('unit_id',$value1->id);
                    foreach ($pengurangan as $key2 => $value2) {
                        # code...
                        if($value2->volume <= 0){
                            $kurang += $value2->volume * $value2->nilai;
                            $total_kurang += $value2->total_nilai;
                        }
                    }
                }
            }
            // printf($total_kurang);
            // echo("<br>");
                // return $kurang;
            $nilai_per_unit[$value1->id] = $nilai_unit - $kurang;
            $total_nilai_per_unit[$value1->id] = $total_nilai_unit - abs($total_kurang);
        }
        // return $nilai_per_unit;
        $total_nilaitot_kurang = 0;
        $nilaitot_kurang = 0;
        foreach ($vo_pengurangan as $key => $value) {
            # code...
            if($value->approval->approval_action_id == 6){
                foreach ($value->detail as $key2 => $value2) {
                    # code...
                    if($value2->volume <= 0){
                        $nilaitot_kurang +=  $value2->volume * $value2->nilai;
                        $total_nilaitot_kurang += $value2->total_nilai;
                    }
                }
            }
        }
        //No VO
        foreach ($this->tender->units as $key => $value1) {
            # code...
            $percent_item_vo = 0;
            $volume_item_vo = 0;
            $real_bobot = 0;
            foreach ($value1->unit_progress as $key => $value2) {
                if ( $value2->spkvo_unit != ""){
                    if ( $value2->spkvo_unit->head_type == "Modules\Spk\Entities\Spk" ){
                        if($value2->volume != 0){
                            $volume_item_vo = 0;
                            $total_item_v = 0;
                            $total_item_vo = 0;
                            foreach ($vo as $key => $value3) {
                                # code...
                                if($value3->approval->approval_action_id == 6){
                                    $volume_item_vo = $volume_item_vo + $value3->detail->where("unit_id",$value2->unit_id)->where("itempekerjaan_id",$value2->itempekerjaan_id)->where("volume",">", 0)->sum("volume");

                                    $total_item_vo += $value3->detail->where("unit_id",$value2->unit_id)->where("itempekerjaan_id",$value2->itempekerjaan_id)->where("volume",">", 0)->sum("total_nilai");
                                }
                            }
                            // return $total_item_vo ;
                            $percent_item_vo = 0;
                            foreach ($vo as $key => $value3) {
                                # code...
                                if($value3->approval->approval_action_id == 6){
                                    $item_vo =  $value3->detail->where("unit_id",$value2->unit_id)->where("itempekerjaan_id",$value2->itempekerjaan_id)->where("volume",">", 0)->first();
                                    // printf($item_vo);
                                    if(isset($item_vo)){
                                        $percent_item_vo =  $percent_item_vo + (($item_vo->volume*($item_vo->progresslapangan_percent/100))/($value2->volume + $volume_item_vo)*100);
                                    }
                                }
                            }
                            // return  $percent_item_vo;
                            
                            $jml_vo = count($vo);

                            $percent_saat_ini = $percent_item_vo + (($value2->volume*($value2->progresslapangan_percent/100))/($value2->volume + $volume_item_vo)*100);
                            // return $percent_saat_ini;
                            $kurang = 0;
                            $total_kurang = 0;
                            foreach ($vo_pengurangan as $key => $value) {
                                # code...
                                if($value->approval->approval_action_id == 6){
                                    $pengurangan = $value->detail->where('itempekerjaan_id', $value2->itempekerjaan_id)->where('unit_id',$value2->unit_id)->where("volume","<", 0)->first();
                    
                                    $kurang += $pengurangan['volume'];
                                    $total_kurang += $pengurangan['total_nilai'];
                                }
                            }
                            // return $total_kurang;
                            $main_nilai = (($value2->total_nilai - $total_kurang) + $total_item_vo) + $main_nilai;
                            if($total_nilai_per_unit[$value1->id] !=null){
                                // printf(($value2->total_nilai - abs($total_kurang)) + $total_item_vo);
                                // echo("<br/>");
                                $real_bobot_s =  (( ( $percent_saat_ini ) * 100 ) / 100 ) * ( ($value2->total_nilai - abs($total_kurang)) + $total_item_vo) /(($total_nilai_per_unit[$value1->id])) *100 ;
                            }
                            // printf($real_bobot_s);
                            // echo("<br/>");
                            $real_bobot = $real_bobot + $real_bobot_s;  
                            
                            // return $real_bobot;
                        }         
                    }
                }
            }

            $real_bobot_spk = $real_bobot_spk + $real_bobot;
        }
        $real_bobot_spk = $real_bobot_spk/count($this->tender->units);

        return round($real_bobot_spk, 2);
    }


    public function getNilaiLapanganAttribute()
    {

        if ($this->progresses->count() == 0) {
            return 0;
           // return $this->nilai * $this->dp_percent / 100 ;

        }

        $progresses = $this->progresses;
        $total = array();
        $total = 0;
        $termin = 0;

        foreach ($progresses as $key => $each) 
        {
            $total = $total +  ($each->volume * $each->nilai) ;
        }

        return $total * ( $this->spk_real_termyn / 100 );
        //return $total * $this;
        /*foreach ($this->termyn as $key2 => $value2) {
            if ( $value2->status == "2"){
                $termin = $value2->progress;
            }
        }
        return $termin;

        if (  $termin / 100 == "0.0"){
            return $total * $this->dp_percent / 100;
        }else{
            return ( $termin / 100 ) * $total;
        }*/
        //return array_sum($total) + $this->nilai_vo;
    }



    public function getNilaiRetensiAttribute()
    {

        $retensi = array();        
        if ($this->lapangan >= 1) 
        {
            foreach ($this->retensis as $key => $ret) 
            {
                $retensi[$key] = $ret->percent * $this->nilai_lapangan;
            }

            
           }else{



            foreach ($this->retensis as $key => $ret) 

            {

                if ($ret->is_progress) 

                {

                    $retensi[$key] = $ret->percent * $this->nilai_lapangan;

                }

            }

        }



        return array_sum($retensi);

    }



    public function getNilaiPpnKontrakAttribute()

    {

        $total = array();



        foreach ($this->detail_units as $key => $each) 

        {

            $total[$key] = $each->volume * $each->nilai * $each->ppn ;

        }



        return array_sum($total);

    }



    public function getNilaiPpnAttribute()

    {

        if ($this->detail_units->count() == 0) {

            return 0;

        }



        $detail_units = $this->detail_units;

        $progresses = $this->progresses;



        if ($this->lapangan >= 1) 

        {

            $percent_retensi = $this->retensis()->sum('percent');

        }else{

            $percent_retensi = $this->retensis()->where('is_progress', TRUE)->sum('percent');

        }



        $total = array();



        foreach ($detail_units as $key => $each) 

        {

            $nilai_lapangan = $each->volume * $each->nilai * $progresses[$key]->progresslapangan_percent;



            // termin retensi tidak ada retensi

            

            if ($this->st1_date) 

            {

                $nilai_setelah_retensi = $nilai_lapangan;



            }else{



                $nilai_setelah_retensi = $nilai_lapangan * (1 - $percent_retensi);

            }



            $total[$key] = $nilai_setelah_retensi * $each->ppn ;

        }



        return array_sum($total);

    }



    public function getBapAttribute()

    {

        if ($this->baps->count() <= 0) 

        {

            return 0;

        }



        $latest_bap = $this->baps()->latest()->first();



        return $latest_bap->percentage_kumulatif;

    }



    public function getBapSebelumnyaAttribute()

    {

        $baps = $this->baps;

        $total_percent = 0;



        foreach ($baps as $key => $each) 

        {

            $total_percent = $total_percent + $each->percentage_sekarang;

        }



        return $total_percent;

    }



    public function getSt1DateAttribute()

    {

        $bap_st1 = NULL;



        foreach ($this->baps as $key => $bap) 

        {

            if ($bap->percentage_kumulatif >= 1) 

            {

                $bap_st1 = $bap;

            }

        }



        if ($bap_st1) 

        {

            return $bap_st1->date;

        }else{

            return NULL;

        }



    }



    public function getNilaiDpAttribute()

    {

        $nilai = 0;



        foreach ($this->detail_units as $key => $each) 

        {

            $nilai += $each->nilai_dp + $each->nilai_ppn_dp;

        }



        return $nilai;

    }



    public function getNilaiFixAttribute(){

        $nilai = 0;

        foreach ($this->tender_rekanan->penawarans->last()->details as $key => $value) {

            # code...

            $nilai = $nilai + ( $value->nilai * $value->volume );

        }

        return $nilai;

    }



    public function termyn(){

        return $this->hasMany("Modules\Spk\Entities\SpkTermyn");

    }



    public function getCoaAttribute(){

        $progresses = $this->progresses->first();

        $itempekerjaan = Itempekerjaan::find($progresses->itempekerjaan_id);

        $code = explode(".",$itempekerjaan->code);

        $coas_item = Itempekerjaan::where("code",$code[0])->first();

        $item = Itempekerjaan::find($coas_item->id);



        return $item;

    }



    public function getNilaiProgressAttribute(){

        $nilai = 0;

        foreach ($this->progresses as $key => $value) {

            # code...

            $nilai = $nilai + $value->progresslapangan_percent;

        }

        if ( $nilai > 0 ){

            $nilai =  $nilai / count($this->progresses);

        }else{

            $nilai =  $this->dp_percent ;

        }

        return $nilai;

    }



     public function getNilaiProgressBapAttribute(){

        $nilai = 0;

        foreach ($this->progresses as $key => $value) {

            # code...

            $nilai = $nilai + $value->progressbap_percent;

        }

        if ( $nilai > 0 ){

            $nilai =  $nilai / count($this->progresses);

        }

        return $nilai;

    }



    public function getKumulatifBapAttribute(){

        $nilai =0;

        foreach ($this->baps as $key => $value) {

            # code...

            $nilai = $value->nilai + $nilai;

        }

        return $nilai;

    }



    public function getItemPekerjaanAttribute(){

        if ( count($this->progresses) > 0  ){

            $itempekerjaan = $this->progresses->first()->itempekerjaan;
            if ($itempekerjaan != null) {
                # code...
                if ( $itempekerjaan->code != "" ){         
                    $code = explode(".", $itempekerjaan->code);
                    if ( count(\Modules\Pekerjaan\Entities\Itempekerjaan::where("code",$code[0])->get()) > 0 ){
                        $id = \Modules\Pekerjaan\Entities\Itempekerjaan::where("code",$code[0])->first();
                    }else{
                        $id = \Modules\Pekerjaan\Entities\Itempekerjaan::find($itempekerjaan->parent->id);
                    }
    
                    if($id->parent_id == null){
                        return $id;
                    }else{
                        if($id->parent->parent_id == null){
                            return $id->parent;
                        }else{
                            return $id->parent->parent;
                        }
                    }
                }else{
                    return 0;
                }
            } else {
                # code...
                return 0;
            }
            

        }

        

    }



    public function getSpkRealTermynAttribute(){

        $nilai = 0;

        $total_termun = 0;

        $progress = $this->lapangan;

        if ( $progress >= 100 ){
            return 100;
        }

        foreach ($this->termyn as $key => $value) {

            $total_termun = $total_termun + $value->progress;

            if ( $total_termun == $progress){                
                return $total_termun;

            } elseif ( $total_termun > $progress ) {
                if (  $this->termyn[$key-1]->status == "3"){
                    return 0;
                }else{
                    $total_termun = $total_termun - $value->progress;
                    return $total_termun;                    
                }
            }

            

        }

       

    }



    public function getNilaiDibayarAttribute(){

        $nilai = 0;

        foreach ($this->baps as $key => $value) {

            if ( ( $key + 1 ) < count($this->baps) ){

                $nilai = $nilai + $value->nilai;

            }

        }



        return $nilai;

    }



    public function getNilaiPengembalianAttribute(){

        $nilai = 0;

        if ( $this->spk_type_id == "2"){
            return $nilai;
        }

        $total_termun = 0;
        if ( $this->pic_id != null ){
            return ( ($this->dp_percent / 100 ) * $this->nilai) ; 
        }

        $total_progress = $this->lapangan ;
        if ( $this->baps->count() > 0 ){
            /*if ( $total_progress >= 100 ){
                return ( ($this->dp_percent / 100 ) * $this->nilai) ; 
            } else { 
                $pengembalian = $this->dp_pengembalians->take($this->baps->count())->where("status",0)->sum("percent");
                return ( ($this->dp_percent / 100 ) * $this->nilai) * ($pengembalian / 100 ) ;
            }*/
            return ( ($this->dp_percent / 100 ) * $this->nilai) * ( $this->spk_real_termyn / 100 );
           
        } else {
            return ( ($this->dp_percent / 100 ) * $this->nilai) * 0 ; 
        }     

        /*foreach ($this->termyn as $key => $value) {

            $total_termun = $total_termun + $value->progress;

            if ( $total_termun > $total_progress){

               $start = $key  ;
               foreach ( $this->dp_pengembalians as $key2 => $value2 ){
                   
                    if ( $key2 < $start ){

                        //if ( $value2->status == "0"){

                            $nilai = $nilai + $value2->percent; 

                        //}                       

                    }

               }

               if ( $this->progresses->sum('progresslapangan_percent') == "0"){
                    return 0;
               }

               if ( $nilai == "0"){
                    return ( ($this->dp_percent / 100 ) * $this->nilai) ;
               }else{
                    return ( ($this->dp_percent / 100 ) * $this->nilai) * ($nilai / 100 ) ;
               }

            }elseif ( $total_termun == $total_progress ){
                
                $start = $key ;
                foreach ( $this->dp_pengembalians as $key2 => $value2 ){
                   
                    if ( $key2 < $start ){

                        //if ( $value2->status == "0"){

                            $nilai = $nilai + $value2->percent; 

                        //}                       

                    }

               }

               if ( $this->progresses->sum('progresslapangan_percent') == "0"){
                    return 0;
               }

               if ( $nilai == "0"){
                    return ( ($this->dp_percent / 100 ) * $this->nilai) ;
               }else{
                    return ( ($this->dp_percent / 100 ) * $this->nilai) * ($nilai / 100 ) ;
               }
            }

            

        }    */    

    }



    public function getNilaiBapSekarangAttribute(){

        $progress = ( ( $this->nilai_progress / 100 ) * $this->nilai ) - ( $this->nilai_bap );

        return $progress;

    }


    public function getNilaiTotalSebelumnyaAttribute(){
        return $this->baps()->orderBy('id','DESC')->where('id','<',$this->id)->sum("nilai_bap_dibayar");
    }
    
    public function getListPekerjaanAttribute(){
        $all = array();
        $termyn = array();
        $nilai = 0;
        foreach ($this->progresses as $key => $value) {
            $nilai = 0;
            foreach ($value->itempekerjaan->item_progress as $key2 => $value2) {
                if ( $value2->percentage == null ){
                    $termyn[$key2] = 0 ;
                }else{
                    $termyn[$key2] = $value2->percentage - $nilai ;     
                    $nilai = $value2->percentage;               
                }
                
                
            }
            if ( count($this->tender->rab->pekerjaans->where("itempekerjaan_id",$value->itempekerjaan->id)) > 0 ){
                $rab_detail = $this->tender->rab->pekerjaans->where("itempekerjaan_id",$value->itempekerjaan->id);
                $bobot_coa = ( ( $rab_detail->first()->nilai * $rab_detail->first()->volume ) / ( $this->tender->rab->nilai / $this->tender->rab->units->count() ) ) * 100;
            }else{
                $bobot_coa = 0;
            }
            
            $all[$value->itempekerjaan->id] = array(
                "itempekerjaan_id" => $value->itempekerjaan->id,
                "pekerjaan_name"   => $value->itempekerjaan->name,
                "pekerjaan_coa"    => $value->itempekerjaan->code,
                "termyn"           => $termyn,
                "bobot_coa"        => $bobot_coa
            );
        }

     
        return $all;
    }

    public function getTotalVoAttribute(){
        $nilai = array();
        foreach ($this->suratinstruksis as $key => $value) {
            foreach ($value->vos as $key2 => $value2) {
                $nilai[$key] = $value2->suratinstruksi_id;
            }
        }

        return array_unique($nilai);
    }

    public function getTerbayarVerifiedAttribute(){
        $nilai_verified = 0;
        $pph = 0;
        if ( $this->baps->count() > 0 ){
            foreach ($this->baps as $key => $value) {
                if ( $value->vouchers != "" ){
                    foreach ($value->vouchers as $key2 => $value2) {
                        if ( $value2->pencairan_date != NULL ){
                            $nilai_verified = $nilai_verified + $value2->nilai;
                            foreach ($value2->details as $key3 => $value3) {
                                if ( $value3->type == "pph"){
                                    $pph = str_replace("-", "", $value3->nilai);
                                }
                            }
                            $nilai_verified = $nilai_verified + $pph;
                        }
                    }
                }
            }
            return $nilai_verified;
        }else{
            return 0;
        }
    }

    public function getProgressSebelumnyaAttribute(){
        if($this->baps->count() == 0){
            $nilai = 0;
        }else{
            $nilai = $this->baps->sum('percentage') ;
        }
        return $nilai;
    }

    public function user_pic(){
        return $this->belongsTo("Modules\User\Entities\User","pic_id");
    }

    public function user(){
        return $this->belongsTo("Modules\User\Entities\User","created_by");
    }

    public function sik(){
        return $this->hasMany("Modules\Progress\Entities\Siks");
    }

    public function pph(){
        return $this->belongsTo("Modules\Rekanan\Entities\PphRekanan",'pph_rekanan_id');
    }

    public function new_vo(){
        return $this->hasMany("Modules\Spk\Entities\NewVo", "spk_id");
    }

    public function perpanjangan(){
        return $this->hasMany("Modules\Rekanan\Entities\PerpanjanganSpk", "spk_id");
    }

    public function percepatan(){
        return $this->hasMany("Modules\Spk\Entities\SpkPercepatan", "spk_id");
    }

    public function getNilaiPercepatanAttribute()
    {
        $nilai = 0;
        $total_per_percepatan = 0;
        foreach ($this->percepatan as $key => $each) {
            $approval = \Modules\Approval\Entities\Approval::where("document_type", "Modules\Spk\Entities\SpkPercepatan")->where("document_id",$each->id)->first();
            if($approval != null){
                if($approval->approval_action_id == 6 && $each->status_percepatan == 1){
                    $total_per_percepatan = ($each->spk->nilai/count($each->spk->tender->units)) * ($each->nilai_persen/100)*(count($each->percepatan_unit));
                }else{
                    $total_per_percepatan = 0;
                }
            }else{
                $total_per_percepatan = 0;
            }
            $nilai = $nilai + $total_per_percepatan;
        }
        return $nilai;
    }

    public function pengajuan_ipk(){
        return $this->hasMany("Modules\Spk\Entities\RekananPengajuanIpk", "spk_id");
    }

    public function getProgressSebelumnyaCairAttribute(){
        $nilai = 0;
        $nilai_kumulatif_saatini = 0;
        if($this->baps->count() == 0){
            $nilai = 0;
        }else{
            foreach ($this->baps as $key => $value) {
                # code...
                if ($value->vouchers_date_cair != null){
                    if ($value->vouchers_date_cair->pencairan_date != null){
                        if(1 == $value->st_status){
                            $nilai_kumulatif_saatini = $nilai_kumulatif_saatini + ($value->pph * ($this->nilai_vo + $this->nilai + $this->nilai_percepatan) * $this->retensis->sum('percent'));
                        }else{
                            $nilai_kumulatif_saatini += $value->nilai_bap_2;
                        }
                        // $nilai +=  $value->nilai_bap_2;
                    }
                }
            }
            // $nilai = $this->baps->sum('percentage') ;
        }
        $kontrak = ($this->nilai_vo + $this->nilai + $this->nilai_percepatan);
        if($kontrak != 0){
            $nilai = $nilai_kumulatif_saatini/$kontrak*100;
        }
        return number_format($nilai, 2, '.', '');
    }

    public function getNilaiSpkAttribute(){
        return $this->nilai + $this->nilai_vo + $this->nilai_percepatan;
    }

    public function getSpkTerbayarAttribute(){
        $nilai = 0;
        $nilai_kumulatif_saatini = 0;
        if($this->baps->count() == 0){
            $nilai = 0;
        }else{
            foreach ($this->baps as $key => $value) {
                # code...
                if ($value->vouchers_date_cair != null){
                    if ($value->vouchers_date_cair->pencairan_date != null){
                        $nilai += $value->nilai_bap_2;
                        if(1 == $value->st_status){
                            $nilai += ($value->pph * ($this->nilai_vo + $this->nilai + $this->nilai_percepatan) * $this->retensis->sum('percent'));
                        }else{
                            $nilai += $value->nilai_bap_2;
                        }
                    }
                }
            }
        }
        return $nilai;
    }

    public function progress_tambahan(){
        return $this->hasMany('Modules\Spk\Entities\ProgressTambahan');

    }

    public function pengembalian_dp()
    {
        return $this->hasMany('Modules\Spk\Entities\SpkPengembalianDp');
    }
}

