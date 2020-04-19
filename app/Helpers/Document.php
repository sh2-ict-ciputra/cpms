<?php
namespace App\Helpers;


use Illuminate\Support\Facades\Mail;
use \App\Mail\OrderShipped;
use \App\Mail\EmailApproved;
use \App\Mail\EmailApproved2;
use Modules\Approval\Entities\Approval;

class Document
{

    public static function new_number($doc_type, $department_id, $project_id) 
    {
        if ($doc_type == 'BAP')  {         # code...

        }

       	$roman         = [
            '01' => 'I',
            '02' => 'II',
            '03' => 'III',
            '04' => 'IV',
            '05' => 'V',
            '06' => 'VI',
            '07' => 'VII',
            '08' => 'VIII',
            '09' => 'IX',
            '10' => 'X',
            '11' => 'XI',
            '12' => 'XII',
        ];



        $department = \Modules\Department\Entities\Department::find($department_id)->code;
        $bulan      = $roman[\Carbon\Carbon::now()->format('m')];
        $tahun      = \Carbon\Carbon::now()->format('Y');
        $project    = \Modules\Project\Entities\Project::find( $project_id )->code; 
        // use \Session::put('project', value) in command prompt

        
        /*if ( isset(\Auth::user()->project_pt_users()->whereProjectId( session('project_id') )->first()->pt) ){
            
            //$pt         = \Auth::user()->project_pt_users()->whereProjectId( session('project_id') )->first()->pt->code;
            $pt = "";
        }else{
            $pt = "";
           
        }*/
        $pt = "";
        $doc_last = $doc_type.'/'.$department.'/'.$bulan.'/'.$tahun.'/'.$project.'/'.$pt;
        //echo date("Y");die;
        $doc_last_uniq = '/'.$department.'/'.$bulan.'/'.$tahun.'/'.$project.'/'.$pt;
        $count['BDG'] = \Modules\Budget\Entities\Budget::where('no','LIKE', '%'.$doc_last.'%')->where("created_at",'LIKE','%'.date("Y").'%')->where("deleted_at",null)->count();
        $count['BDG-T'] = \Modules\Budget\Entities\BudgetTahunan::where('no','LIKE', '%'.$doc_last.'%')->where("created_at",'LIKE','%'.date("Y").'%')->where("deleted_at",null)->count();
        $count['WO'] = \Modules\Workorder\Entities\Workorder::where('no','LIKE', '%'.$doc_last.'%')->where("created_at",'LIKE','%'.date("Y").'%')->where("deleted_at",null)->count();
        $count['RAB'] = \Modules\Rab\Entities\Rab::where('no','LIKE', '%'.$doc_last.'%')->where("created_at",'LIKE','%'.date("Y").'%')->where("deleted_at",null)->count();
        $count['TENDER'] = \Modules\Tender\Entities\Tender::where('no','LIKE', '%'.$doc_last.'%')->where("created_at",'LIKE','%'.date("Y").'%')->where("deleted_at",null)->count();
        $count['SPK'] = \Modules\Spk\Entities\Spk::where('no','LIKE', '%'.$doc_last_uniq.'%')->where("created_at",'LIKE','%'.date("Y").'%')->where("deleted_at",null)->count();
        $count['SIPP'] = \Modules\Tender\Entities\TenderRekanan::where('sipp_no','LIKE', '%'.$doc_last.'%')->where("created_at",'LIKE','%'.date("Y").'%')->where("deleted_at",null)->count();
        $count['UDG'] = \Modules\Tender\Entities\TenderKorespondensi::where('no','LIKE', '%'.$doc_last.'%')->where("created_at",'LIKE','%'.date("Y").'%')->where("deleted_at",null)->count();
        $count['SUPP'] = \Modules\Rekanan\Entities\RekananSupp::get()->where("deleted_at",null)->count();
        $count['IL'] = \Modules\Spk\Entities\Spk::where('no','LIKE', '%'.$doc_last.'%')->where("created_at",'LIKE','%'.date("Y").'%')->where("deleted_at",null)->count();
        $count['BA-TL'] = \Modules\Tender\Entities\BeritaAcaraTunjukLangsung::where('no','LIKE', '%'.$doc_last_uniq.'%')->where("created_at",'LIKE','%'.date("Y").'%')->where("deleted_at",null)->count();
        $count['BA-RO'] = \Modules\Tender\Entities\BeritaAcaraTunjukLangsung::where('no','LIKE', '%'.$doc_last_uniq.'%')->where("created_at",'LIKE','%'.date("Y").'%')->where("deleted_at",null)->count();
        //$count[6] = \Modules\Spk\Entities\Suratinstruksi::where('no','LIKE', '%'.$doc_last.'%')->where(date("created_at"),date("Y"))->count();
        //$count[7] = \Modules\Spk\Entities\Vo::where('no','LIKE', '%'.$doc_last.'%')->where(date("created_at"),date("Y"))->count();
        //$count[8] = \Modules\Spk\Entities\Bap::where('no','LIKE', '%'.$doc_last.'%')->where(date("created_at"),date("Y"))->count();
        //$count[9] = \Modules\Tender\Entities\TenderRekanan::where('sipp_no','LIKE', '%'.$doc_last.'%')->where(date("created_at"),date("Y"))->count();
        //$count[13] = \Modules\Tender\Entities\TenderKorespondensi::where('no','LIKE', '%'.$doc_last.'%')->where(date("created_at"),date("Y"))->count();
		//$count[10] = \App\PermintaanBarang::where('no','LIKE', '%'.$doc_last)->withTrashed()->count();
		//$count[11] = \App\PurchaseRequest::where('no','LIKE', '%'.$doc_last)->withTrashed()->count();
        //$count[12] = \App\Piutang::where('no','LIKE', '%'.$doc_last)->withTrashed()->count();
        //$count[14] = \App\PurchaseOrder::where('no','LIKE','%'.$doc_last)->withTrashed()->count();
        //$count[15] = \App\GoodReceive::where('no','LIKE','%'.$doc_last)->withTrashed()->count();
        //$count[16] = \App\Barangmasuk::where('no','LIKE','%'.$doc_last)->withTrashed()->count();
        //$count[17] = \App\TenderPurchaseRequest::where('no','LIKE','%'.$doc_last)->withTrashed()->count();
        //$count[17] = \App\TenderPurchaseKorespondensi::where('no','LIKE','%'.$doc_last)->withTrashed()->count();*/
        //$count[18] = \Modules\Voucher\Entities\Voucher::where('no','LIKE','%'.$doc_last.'%')->count();
        //$count[19] = \Modules\Pengajuanbiaya\Entities\Pengajuanbiaya::where("no",'LIKE','%'.$doc_last.'%')->count();
        
        if($doc_type == 'SPK-TDR' || $doc_type == 'SPK-TL' || $doc_type == 'SPK-RO' || $doc_type == 'BA-TL' || $doc_type == 'BA-RO'){
            $number = str_pad( (($count['SPK']) + 1) ,4,"0",STR_PAD_LEFT);

            $doc_last_uniq = $doc_type.'/'.$department.'/'.$bulan.'/'.$tahun.'/'.$project.'/'.$pt;
            return $number."/".$doc_last_uniq;
        }else{
            $number = str_pad( (($count[$doc_type]) + 1) ,4,"0",STR_PAD_LEFT);
            return $number."/".$doc_last;
        }

    }



    public static function make_approval($class, $id){
        $document = $class::find($id);

        if (($class == 'App\Tender') AND ($document->sumber <> 1) )
        {
            $class = 'App\Nontender';
        }

        $approval = new Approval;
        

        if ( $class != "Modules\Budget\Entities\Budget"){
            $budget = \Modules\Budget\Entities\Budget::find($id);

            $approval->approval_action_id = 1;
            if($document->nilai == null){
                $approval->total_nilai = 0;
            }else{
                $approval->total_nilai = $document->nilai;
            }
            $approval->document_id = $id;
            $approval->document_type = $class;
            $approval->save();
            self::make_approval_history($approval->id,$class);
            
        }elseif ( $class == "Modules\Budget\Entities\Budget" || $class == "Modules\Budget\Entities\BudgetTahunan" || $class == "Modules\Spk\Entities\Spk" ) {
            /*foreach ($budget->details as $key => $value) {

                $approval = new \App\Approval;
                $approval->approval_action_id = 1;
                $approval->total_nilai = $value->nilai * $value->volume ;
                $approval->document_id = $value->id;
                $approval->document_type = "Modules\Budget\Entities\BudgetDetail";
                $approval->save();
                self::make_approval_history($approval->id,"Modules\Budget\Entities\BudgetDetail");
            }
            */


            $approval = new Approval;
            $approval->approval_action_id = 6;
            $approval->total_nilai = $document->nilai ;
            $approval->document_id = $value->id;
            $approval->document_type = "Modules\Spk\Entities\Spk";
            $approval->save();
        }

        return $document;

    }



    public static function make_approval_history($id){

        $approval = Approval::find($id);
        $document = $approval->document;
        $class = class_basename($document);
        $nilai = $approval->total_nilai;

        if ( $class == "Purchaseorder"){
            $pt_id = $document->purchaserequest->pt->id;
        }elseif($class == "Siks"){
            $pt_id = null;
        }elseif($class == "NewVo"){
            $pt_id = $document->spk->tender->rab->pt->id;
        }elseif($class == "PerpanjanganSpk"){
            $pt_id = $document->spk->tender->rab->pt->id;
        }elseif($class == "SpkPercepatan"){
            $pt_id = $document->spk->tender->rab->pt->id;
        }else{
            $pt_id = $document->pt->id;
        }

        if ( $class == "Budget"){
            $department_from = $document->department_from;
        }else if ( $class == "Workorder" ){
            $department_from = $document->department_from;
            $pt_id = $document->pt_id;
        }else if ( $class == "Rab" ){
            $department_from = $document->workorder->department_from;
            $pt_id = $document->pt->id;
        }else if ( $class == "Tender"){
            $department_from = $document->rab->workorder->department_from;
            $pt_id = $document->rab->pt->id;
        }else if ( $class == "TunjukPemenangTender"){
            $department_from = $document->tender->rab->workorder->department_from;
            $pt_id = $document->pt->id;
        }else if ( $class == "Spk"){
            $department_from = $document->tender_rekanan->tender->rab->workorder->department_from;
        }else if ( $class == "Voucher"){
            $department_from = $document->bap->spk->tender_rekanan->tender->rab->workorder->department_from;
        }else if ( $class == "TenderRekanan" ){
            $department_from = $document->department_from;
            $pt_id = $document->tender->rab->pt->id;
        }else if ( $class == "Purchaserequest"){
            $department_from = $document->department_id;
        } else if ( $class == "BudgetDraft"){
            $department_from = $document->budget->department_id;
        }else if ( $class == "BudgetDetail"){
            $department_from = $document->budget->department_from;
        }
        
        // cari di ApprovalReference, user mana yang akan approve dokumen ini
        // cari berdasarkan document type, nilai min, nilai max
        //$detailDOc = $class::find()
        $globalsetting = \Modules\Globalsetting\Entities\Globalsetting::where('parameter','tunjuk_langsung_approval')->first();
        if ($globalsetting) // maka ini adalah non-tender atau penunjukan
        {
            $max_value_multiplier = $globalsetting->value;
        }else{
            $max_value_multiplier = 1;
        }

        if ( $class == "TenderRekanan" || $class == "TenderMenang" || $class == "Spk"){
            $type = $document->tender->tender_type->id;
            if ( $type == 1 ){
                $nilai = $max_value_multiplier * $approval->total_nilai;
            }else{
                $nilai = $approval->total_nilai;
            }
        }

        if ( $class == "NewVo"){
            
            $nilai = $max_value_multiplier * ($approval->total_nilai + $approval->document->spk->nilai_vo + $approval->document->spk->nilai);
            $class = "Vo";
        }else{
            $nilai = $approval->total_nilai;
        }  

        if($class == "TunjukPemenangTender"){
            $type = $document->tender->tender_type->id;
            if ( $type == 1 ){
                $nilai = $max_value_multiplier * $approval->total_nilai;
            }else{
                $nilai = $approval->total_nilai;
            }
        }
        
        if($class != "PerpanjanganSpk"){
            if($class != "SpkPercepatan"){
                $approval_references = \Modules\Approval\Entities\ApprovalReference::where('document_type', $class)
                                    ->where('project_id', session('project_id') )
                                    ->where('pt_id', $pt_id )
                                    ->where('min_value', '<=', abs($nilai))
                                    //->where('max_value', '>=', $approval->total_nilai)
                                    ->orderBy('no_urut','DESC')
                                    ->get();
            }else{
                $approval_references = \Modules\Approval\Entities\ApprovalReference::where('document_type', $class)
                                    ->where('project_id', $document->spk->project_id )
                                    ->where('pt_id', $pt_id )
                                    ->where('min_value', '<=', abs($nilai))
                                    //->where('max_value', '>=', $approval->total_nilai)
                                    ->orderBy('no_urut','DESC')
                                    ->get();
            }                            
        }else{
            $approval_references = \Modules\Approval\Entities\ApprovalReference::where('document_type', $class)
                                    ->where('project_id', $document->spk->project_id )
                                    ->where('pt_id', $pt_id )
                                    ->where('min_value', '<=', abs($nilai))
                                    //->where('max_value', '>=', $approval->total_nilai)
                                    ->orderBy('no_urut','DESC')
                                    ->get();
        }

        foreach ($approval_references as $key => $each) 
        {
            /* Departmen ID = 11 is Direksi */
            //$department_id = \App\User::find($each->user_id)->details->first()->mappingperusahaan->department_id;
            //$user_level = \App\User::find($each->user_id)->details->first()->user_level;
            //if ( $user_level <= 4 ){

            if ( $class == "Spk" || $class == "Budget" || $class == "BudgetTahunan"){                
                $status = 6;
            }else{
                $status = 2;
            }

            if($each != null){
                $user = \Modules\User\Entities\User::find($each->user_id);
                if ( isset($user->jabatan ) ) {                    
                    $jabatan = $user->jabatan;
                    $document->approval_histories()->create([
                        'no_urut' => $each->no_urut,
                        'user_id' => $each->user_id,
                        'approval_action_id' => $status, // open
                        'approval_id' => $approval->id
                    ]);
                    
                    /*Mail::to("arifiradat@ciputra.com")->send(new EmailApproved($value->user));*/
                }
            }
        }
        return $document;

    }



    public static function make_approval_signature($id,$class){
        $direksi = "";
        $gm = "";
        $dept = "";
        $div = "";
        $flags = 4 ; // Value = 4 menunjukan level terendah direksi
        $document = $class::find($id);
        $signature = array();
        if ( isset($document->approval)){
            foreach( $document->approval->histories as $histories ){          
                if ( $histories->user->details->first()->user_level < 5 ){
                    $direksi = "exist";             
                    if ( $histories->user->details->first()->user_level < $flags ){
                        $flags = $histories->user->details->first()->user_level;
                        $signature["direksi"] = "exist";
                        $signature["user_id"] = $histories->user_id;   
                    }                  
                }
                if ( $histories->user->details->first()->user_level == 5 ){
                    $gm = $histories->user_id;     
                    $signature["gm"] = $gm;          
                }

                if ( $histories->user->details->first()->user_level == 6 ){
                    $dept = $histories->user_id;
                    $signature["dept"] = $dept;
                }

                if ( $histories->user->details->first()->user_level == 7 ){
                    $div = $histories->user_id;
                    $signature["div"] = $histories->user_id;
                }
            }

            return $signature;

        }
    }    

}