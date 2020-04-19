<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\User\Entities\User;
use Modules\PenerimaanBarangPO\Entities\PenerimaanBarangPO;
use Modules\PurchaseRequest\Entities\PurchaseRequest;
use Modules\PurchaseRequest\Entities\PurchaseRequestDetail;

class EmailPr extends Mailable
{
    use Queueable, SerializesModels;
    public $Melacak_Pr;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(PenerimaanBarangPO $Melacak_Pr)
    {
        $this->Lacak = $Melacak_Pr;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $lacak = [];
        $melacak_detail_PR = penerimaanbarangPO::join("penerimaan_barang_po_details","penerimaan_barang_po_details.penerimaan_barang_po_id","penerimaan_barang_pos.id")
                                        ->join("purchaseorder_details","purchaseorder_details.id","penerimaan_barang_po_details.po_detail_id")
                                        ->join("purchaseorders","purchaseorders.id","purchaseorder_details.purchaseorder_id")
                                        ->join("tender_menang_pr","tender_menang_pr.id","purchaseorders.id_tender_menang")
                                        ->join("tender_purchase_request_group_rekanans","tender_purchase_request_group_rekanans.id","tender_menang_pr.tender_purchase_group_rekanan_id")
                                        ->join("tender_purchase_request_groups","tender_purchase_request_groups.id","tender_purchase_request_group_rekanans.tender_purchase_request_group_id")
                                        ->join("tender_purchase_request_group_details","tender_purchase_request_group_details.tender_purchase_request_groups_id","tender_purchase_request_groups.id")
                                        ->join("purchaserequest_details","purchaserequest_details.id","tender_purchase_request_group_details.id_purchase_request_detail")
                                        ->join("purchaserequests","purchaserequests.id","purchaserequest_details.purchaserequest_id")
                                        ->join("departments","departments.id","purchaserequests.department_id")
                                        ->join("item_satuans","item_satuans.id","purchaserequest_details.item_satuan_id")
                                        ->join("item_projects","item_projects.id","penerimaan_barang_po_details.item_id")
                                        ->join("items","items.id","item_projects.item_id")
                                        ->join("users","users.id","purchaserequests.created_by")
                                        ->where("penerimaan_barang_pos.id",$this->Lacak->pb_id)
                                        ->where("purchaserequests.id",$this->Lacak->id_pr)
                                        ->select("purchaserequests.id as id_pr","purchaserequests.no as no_pr","users.user_name as pembuat","users.email as email","penerimaan_barang_pos.id as pb_id","penerimaan_barang_po_details.item_id as id_item_diterima","items.name as name_item_diterima","penerimaan_barang_po_details.quantity as quantity_diterima","users.user_login as user_login","users.password as user_password","purchaserequests.project_for_id as project_id","purchaserequests.pt_id as pt_id","purchaserequests.department_id as department_id")
                                        ->distinct()
                                        ->get();

        foreach ($melacak_detail_PR as $key => $nilai) {
            # code...
            $item_diterima = PurchaseRequestDetail::where("purchaserequest_id",$this->Lacak->id_pr)->where("item_id",$nilai->id_item_diterima)->first();
            // return $item_diterima;
            $arr=[
                "id_pr"             => $nilai->id_pr,
                "no_pr"             => $nilai->no_pr,
                "user"              => $nilai->pembuat,
                "item_name"         => $nilai->name_item_diterima,
                "quantity_diterima" => $nilai->quantity_diterima,
                "quantity_diminta"  => $item_diterima->quantity,
                "user_login"        => $nilai->user_login,
                "password"          => $nilai->user_password,
                "id_project"        => $nilai->project_id,
                "PT"                => $nilai->pt_id,
                "department"       => $nilai->department_id

            ];

            array_push($lacak, $arr);
        }
        // return $melacak_detail_PR;
        // return $this->view('Mail.Demo');
        return $this->from(env('MAIL_USERNAME'))->view('mail.emailKePr')
        ->with([
            'isi_email' => $lacak,
        ]);
    }
}