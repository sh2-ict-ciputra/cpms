<?php

namespace Modules\Inventory\Entities;

use Modules\Approval\Entities\Approval;
use Modules\Approval\Entities\ApprovalHistory;
use Modules\Department\Entities\Department;
use Modules\Project\Entities\Project;
use Modules\Inventory\Entities\BarangMasukHibah;
use Modules\Project\Entities\ProjectPtUser;
use Modules\Inventory\Entities\Permintaanbarang;
use Modules\Inventory\Entities\Pengembalianbarang;
use Modules\GoodReceive\Entities\Goodreceive;
use Modules\PurchaseRequest\Entities\PurchaseRequest;
use Modules\TenderPurchaseRequest\Entities\TenderPurchaseRequest;
use Modules\TenderPurchaseRequest\Entities\TenderPurchaseRequestGroup;
use Modules\TenderPurchaseRequest\Entities\TenderPurchaseRequestGroupRekanan;
use Modules\TenderPurchaseRequest\Entities\TenderPurchaseRequestPenawaran;
use Modules\TenderPurchaseRequest\Entities\PurchaseOrder;
use Modules\PenerimaanBarangPO\Entities\PenerimaanBarangPO;

class CreateDocument 
{
    //
    public static function createDocumentNumber($doc_type,$department_id,$project_id,$user_id)
    {
        $roman         = [
            '1' => 'I',
            '2' => 'II',
            '3' => 'III',
            '4' => 'IV',
            '5' => 'V',
            '6' => 'VI',
            '7' => 'VII',
            '8' => 'VIII',
            '9' => 'IX',
            '10' => 'X',
            '11' => 'XI',
            '12' => 'XII',
        ];

        $department = Department::find($department_id)->code;
        $bulan      = $roman[date('n')];
        $tahun      = date('Y');
        $project    = Project::find($project_id)->code;    // use \Session::put('project', value) in command prompt

        $pt         = ProjectPtUser::where('user_id',$user_id)->first()->pt->code;
        $doc_last = '/'.$doc_type.'/'.$department.'/'.$bulan.'/'.$tahun.'/'.$project.'/'.$pt;
        $count[0] = BarangMasukHibah::where('no','LIKE', '%'.$doc_last)->withTrashed()->count();
        $count[1] = Permintaanbarang::where('no','LIKE', '%'.$doc_last)->withTrashed()->count();
        $count[2] = Barangkeluar::where('no','LIKE', '%'.$doc_last)->withTrashed()->count();
        $count[3] = Pengembalianbarang::where('no','LIKE','%'.$doc_last)->withTrashed()->count();
        $count[4] = Goodreceive::where('no','LIKE','%'.$doc_last)->withTrashed()->count();
        $count[5] = PurchaseRequest::where('no','LIKE','%'.$doc_last)->withTrashed()->count();
        $count[6] = TenderPurchaseRequestGroup::where('no','LIKE','%'.$doc_last)->withTrashed()->count();
        $count[7] = TenderPurchaseRequest::where('no','LIKE','%'.$doc_last)->withTrashed()->count();
        $count[8] = TenderPurchaseRequestGroupRekanan::where('no','LIKE','%'.$doc_last)->withTrashed()->count();
        $count[9] = TenderPurchaseRequestPenawaran::where('no','LIKE','%'.$doc_last)->withTrashed()->count();
        $count[10] = PurchaseOrder::where('no','LIKE','%'.$doc_last)->withTrashed()->count();
        $count[11] = PenerimaanBarangPO::where('no','LIKE','%'.$doc_last)->withTrashed()->count();
        $number = str_pad( (array_sum($count) + 1) ,4,"0",STR_PAD_LEFT);

        return $number."/".$doc_last;
    }

    public static function make_approval($class,$id,$project_id,$pt_id)
    {

        $document = $class::find($id);
        
        $approval = new Approval;
        $approval->approval_action_id = 1;
        $approval->total_nilai = is_null($document->nilai) ? 0 : $document->nilai;
        $approval->document_id = $id;
        $approval->document_type = $class;
        $approval->save();
        self::make_approval_history($approval->id,$class,$project_id);
        
        return $document;

    }


    public static function make_approval_history($id,$class,$project_id)
    {
        $approval = \App\Approval::find($id);

        $approval_references = \Modules\Approval\Entities\ApprovalReference::where('document_type', $class)
                                    ->where('project_id', $project_id)
                                    //->where('pt_id', $pt_id )
                                    ->where('min_value', '<=', $approval->total_nilai)
                                    //->where('max_value', '>=', $approval->total_nilai)
                                    ->orderBy('no_urut','ASC')
                                    ->get();
        
        foreach ($approval_references as $key => $each) 
        {
            ApprovalHistory::create(['no_urut'=> $each->no_urut,
                'user_id'=> $each->user_id,
                'approval_action_id'=>$approval->approval_action_id,
                'approval_id'=>$approval->id,
                'document_type'=>$class,
                'document_id'=>$approval->document_id,
                'no_urut' => $each->no_urut]);
        }

    }
}