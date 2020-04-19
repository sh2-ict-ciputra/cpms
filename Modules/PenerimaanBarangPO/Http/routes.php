<?php

Route::group(['middleware' => 'web', 'prefix' => 'penerimaanbarangpo', 'namespace' => 'Modules\PenerimaanBarangPO\Http\Controllers'], function()
{
    Route::get('/', 'PenerimaanBarangPOController@index');
    Route::get('/add', 'PenerimaanBarangPOController@create');
    Route::post('/store', 'PenerimaanBarangPOController@store');
    Route::get('/detail', 'PenerimaanBarangPOController@detail');
    Route::get('/getPOD','PenerimaanBarangPOController@getDataDetailPO');
    Route::get("/getPBPO",'PenerimaanBarangPOController@getPBPO');
    Route::post("/approve",'PenerimaanBarangPOController@approvePB');
    Route::post("/undo_request_approve",'PenerimaanBarangPOController@undo_request_approvePBPO');
    Route::post("/approveall",'PenerimaanBarangPOController@approvePBPOall');
    Route::post("/undo_approve",'PenerimaanBarangPOController@undo_approvePBPO');
    Route::post("/request_approve",'PenerimaanBarangPOController@request_approvePBPO');

    Route::post("/request_approve_perdetail",'PenerimaanBarangPOController@request_approve_PB_perdetail');
    Route::post("/undo_request_approve_perdetail",'PenerimaanBarangPOController@undo_request_approve_PB_perdetail');
    Route::post("/approve_perdetail",'PenerimaanBarangPOController@approve_PB_perdetail');
    Route::post("/undo_approve_perdetail",'PenerimaanBarangPOController@undo_approve_PB_perdetail');

    Route::post("/request_setuju_perdetail",'PenerimaanBarangPOController@request_setuju_PB_perdetail');

    // Route::get('/getDetilPOPR/{data}/',function($data){
    //     $POPR = DB::table("purchaseorder_prs as popr")
    //               ->where("popr.purchaseorder_detail_id",$data)
    //               ->join("budget_tahunans as bt","bt.id","popr.budget_tahunan_id")
    //               ->select("popr.purchaserequest_no","bt.no as btNo","popr.departement_name","popr.quantity","popr.satuan","popr.urgent","popr.date_dibutuhkan")
    //               ->get();
    //     return Response::json($POPR);
    // Route::get('/getPO','PenerimaanBarangPOController@getPO');
    Route::get('/getPO/{data?}',function($data){
      $result_PO = [];
    	$PO = DB::table("purchaseorders as po")
    		  ->where("po.rekanan_id",$data)
              ->join("approvals as ap","ap.document_id","po.id")
              ->where("ap.document_type","Modules\TenderPurchaseRequest\Entities\PurchaseOrder")
              ->where("ap.approval_action_id",6)
              ->select("po.id as id","po.no as no")
              ->get();
      foreach ($PO as $key => $v) {
      $model = DB::table("purchaseorder_details")->where("purchaseorder_id",$v->id)
                                  ->join("item_projects","item_projects.id","purchaseorder_details.item_id")
                                  ->join("items","items.id","item_projects.item_id")
                                  // ->join("penerimaan_barang_po_details","penerimaan_barang_po_details.po_detail_id","purchaseorder_details.id")
                                  ->select("items.id as item_id","items.name as item_name","purchaseorder_details.brand_id as brand_id","purchaseorder_details.satuan_id as satuan_id","purchaseorder_details.description as description","purchaseorder_details.quantity as quantity","purchaseorder_details.id as id","purchaseorder_details.id as pod_id","purchaseorder_details.item_id as item_pod_id")->get();
        $i = 0;
        $sisa_total = [];
        $sisa_total[0] = 0;

        foreach ($model as $key => $value) {
          # code...
          $i++;
          $qty_item = DB::table("penerimaan_barang_po_details")
                      ->where("po_detail_id",$value->id)
                      ->select(DB::raw('sum(penerimaan_barang_po_details.quantity) as quantity'))
                      ->first();
            if($qty_item<=>NULL){
              $sisa = $value->quantity-$qty_item->quantity;
            }else{
              $sisa = $value->quantity;
            }
            $sisa_total[$i] = $sisa;        
          }

          if(array_sum($sisa_total) != 0)
          {
            $arr = [
              'id'=>$v->id,
              'no'=>$v->no,
            ];
            array_push($result_PO, $arr);
          }
        }
        return Response::json($result_PO);
    });
    Route::get('/getItem/{data?}',function($data){
    	$Item = DB::table("purchaseorder_details")
    		      ->where("purchaseorder_id",$data)
              ->select("id","item","brand","satuan","quantity")
              ->get();
      $i=0;
      foreach ($Item as $v) {
        $tmp = 0;
        $quantityItem =  DB::table("penerimaan_barang_po_details as pbpod")
                          ->where("pbpod.po_detail_id",$v->id)
                          ->join("penerimaan_barang_pos as pbpo","pbpo.id","pbpod.penerimaan_barang_po_id")
                          ->join("approvals as a","a.document_id","pbpo.id")
                          ->where("a.document_type","Modules\PenerimaanBarangPO\Entities\PenerimaanBarangPO")
                          ->where("a.approval_action_id","<>","9")
                          ->get();
        foreach ($quantityItem as $v2) {
          $tmp += $v2->quantity;
        }
        $Item[$i]->sisa = $Item[$i]->quantity - $tmp;

        $i++;
      }
      $Item->sisa = 1;
      return Response::json($Item);
    });
    Route::get('/getTerm/{data?}',function($data){
    	$term = DB::table("penerimaan_barang_pos as pbpo")
    		  ->where("po_detail_id",$data)
    		  ->join("purchaseorder_term_pengiriman as potp","potp.id","pbpo.po_term_id")
    		  ->leftJoin("users as u","u.id","pbpo.user_id")
    		  ->select("pbpo.id","potp.date","u.user_name","pbpo.quantity","pbpo.description")
              ->get();
        $totalKuantitasKosong = 0;
      	foreach ($term as $v) {
        	if($v->quantity == null)
        		$totalKuantitasKosong +=1;
        }
		$a = array(
			"term" 	=> $term,
			"satuan"=> DB::table("purchaseorder_details")
        					->where("id",$data)
							->select("satuan")
							->first()
							->satuan,
			"totalKuantitas" => DB::table("purchaseorder_details")
        					->where("id",$data)
							->select("quantity")
							->first()
							->quantity,
			"totalKuantitasKosong" => $totalKuantitasKosong
		);

		return Response::json($a);
    });
    Route::get('/getPenerimaan/{data?}',function($data){
      $term = DB::table("penerimaan_barang_po_details as pbpod")
          ->where("pbpod.po_detail_id",$data)
          ->join("users as u","u.id","pbpod.user_id")
          ->join("penerimaan_barang_pos as pbpo","pbpo.id","pbpod.penerimaan_barang_po_id")
          ->select("pbpod.id","pbpo.date","pbpo.no","u.user_name","pbpod.satuan","pbpod.quantity","pbpod.description")
          ->get();

    return Response::json($term);
    });
});
