<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Admin QS | Dashboard</title>
  @include("master/header")
</head>
<style type="text/css">
    #DetilModal .modal-dialog {
        width: 90vw;
    }
    #DetilTerm .modal-dialog {
        width: 20vw;
    }
    .table-align-right{
        text-align: right;
        font-weight: normal;

    }
    .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th{
        vertical-align : middle;
    }
</style>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  @include("master/sidebar_project")

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <div class="modal fade" id="DetilModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <h3 id="modal-header-h3" style="text-align:center">Detail PO di Item No. </h3>
        </div>
        <div class="modal-body">
            <table id="ListPOPR" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>No. Purchase Request</th>
                        <th>No. Budget Tahunan</th>
                        <th>Departemen</th>
                        <th>Quantity</th>
                        <th>Open Quantity</th>
                        <th>Satuan</th>
                        <th>Urgent</th>
                        <th>Tanggal Di Butuhkan</th>
                    </tr>
                </thead>
                <tbody id="tbodyPOPR">
                    <tr>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
    <!-- Content Header (Page header) -->
    
    <section class="content-header">
      <h1 style="text-align:center">Detail Penerimaan Barang</h1>
    </section>
    <section class="content-header">
      <div class="" style="float: none">
        <button class="col-lg-1 col-md-2 col-sm-2 btn btn-primary" onclick="location.href='{{ url('/')}}/penerimaanbarangpo'" style="float: none; border-radius: 20px; padding-left: 0">
        <i class="fa fa-fw fa-arrow-left"></i>&nbsp;&nbsp;Back
        </button>
        <button class="col-lg-1 col-md-2 col-sm-2 btn btn-primary" onclick="window.location.reload()" style="float: right; border-radius: 20px; padding-left: 0;">
          <i class="fa fa-fw fa-refresh"></i>&nbsp;&nbsp;Refresh
        </button>  
      </div>
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">

                <div class="box-header with-border">
                  <h3 class="box-title text-center">
                      Data Penawaran Barang Detail
                  </h3>
                  <div class="box-tools pull-right">
                    <!-- <button type="button" class="btn btn-primary" id="btn-approve" data-value="{{ $PO_umum[0]->id }}">
                        <i class="fa fa-fw fa-check"></i>Setuju
                      </button> -->
                  
                  </div>
                  
                </div>
                <div class="box-body" style="">
                    <table class="table">
                      <thead>
                        <tr>
                          <th>Nomo PO</th><th>{{$PO_umum[0]->no}}</th>
                        </tr>
                        <tr>
                          <th>Nomo Tender</th><th>{{$PO_umum[0]->tender_menang->tender->no}}</th>
                        </tr>
                        <tr>
                          <th>Nomor Penawaran Final</th><th>{{ $PO_umum[0]->tender_menang->penawaran->no }}</th>
                        </tr>
                         <tr>
                          <th>Supplier</th><th>{{ $PO_umum[0]->vendor->name }}</th>
                        </tr>
                        <tr>
                          <th>Deskripsi</th><th>{{$PO_umum[0]->description}}</th>
                        </tr>
                      </thead>
                      
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">

                <div class="box-header with-border">
                  <h3 class="box-title text-center">
                      Keterangan
                  </h3>
                  
                </div>
                <div class="box-body" style="">
                    <!-- <table class="table">
                      <thead style="background-color: gray;">
                      <tr >
                        <th class="table-align-right">No</th>
                        <th>Item</th>
                        <th>Brand</th>
                        <th>Satuan</th>
                        <th class="text-right">Quantity Total</th>
                        <th class="text-right">Quantity Sisa</th>
                      </tr>
                      </thead>
                      <tbody>
                        @foreach($results as $key => $nilai)
                        <tr>
                          <td class="table-align-right">{{$key+1}}</td>
                          <th>{{ $nilai['item_name']}}</th>
                          <td>{{ $nilai['brand_name']}}</td>
                          <td>{{ $nilai['satuan_name']}}</td>
                          <td class="table-align-right">{{$nilai['quantity_total']}}</td>
                          <td class="table-align-right">{{$nilai['sisa_quantity']}}</td>
                        </tr>
                        @endforeach()
                      </tbody>
                    </table> -->
                    <table class="table">
                      <thead>
                          <tr>
                            <th>Jumlah Item </th><th>{{$PO_umum[0]->details->count("item_id")}} Item</th>
                          </tr>
                          <tr>
                            <th>Jumlah Total Item Barang</th><th>{{$jumlah_seluruh_item[0]->quantity}} Barang</th>
                          </tr>
                          <tr>
                            <th>Jumlah Seluruh Item Barang Diterima</th><th>{{$PBPO2}} Barang</th>
                          </tr>
                          <tr>
                            <th>Jumlah Sisa Item Barang</th><th>{{$jumlah_seluruh_item[0]->quantity - $PBPO2}} Barang</th>
                          </tr>
                      </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

      @php
        $k=0;
        $i=0; 
      @endphp
        @foreach($PBPO as $key => $value)
      <div class="row">
        <div class="col-md-12">
          <div class="box box-primary">
            <div class="box-header with-border" style="height: 50px">
                <h3 class="box-title text-center">
                      Penerimaan Barang {{count($PBPO) - $i}}
                  </h3>
              <div class="box-tools pull-right">
                    @if($value->id_approval_action != 6)
                      {{-- <button type="button" class="btn-request_approve btn btn-primary" id="btn-request_approve" data-value="{{ $value->PBPO_id }}">
                        <i class="fa fa-fw fa-check"></i>Request Approval
                      </button> --}}
                      <button type="button" class="btn-request_setuju btn btn-success" id="btn-request_setuju" data-value="{{ $value->PBPO_id }}">
                        <i class="fa fa-fw fa-check"></i>Approve
                      </button>
                    @elseif($value->id_approval_action == 2)
                      {{-- <button type="button" class="btn-undo_request_approve btn btn-primary" id="btn-undo_request_approve" data-value="{{ $value->PBPO_id }}">
                        <i class="fa fa-fw fa-check"></i>Undo Request Upproval
                      </button> --}}
                      {{-- <button type="button" class="btn-request_setuju btn btn-primary" id="btn-request_setuju" data-value="{{ $value->PBPO_id }}">
                        <i class="fa fa-fw fa-check"></i>Request Approval
                      </button> --}}
                    @endif         
              </div>
            </div>
            <div class="box-body">
              <table class="table">
                <thead>
                  <tr>
                    <th>Nomor Penerimaan Barang</th><th>{{$value->PBPO_no}}</th>
                    <th>Tanggal Penerimaan Barang</th><th>{{$value->date_diterima}}</th>
                  </tr>
                  <!-- <tr>
                    <th>Tanggal Penerimaan Barang</th><th>{{$value->date_diterima}}</th>
                  </tr> -->
                  <?php
                    $jumlah_diterima = DB::table('penerimaan_barang_po_details')->where('penerimaan_barang_po_id',$value->PBPO_id)->select(DB::raw('sum(penerimaan_barang_po_details.quantity) as quantity'))->get();
                    $jumlah_item = DB::table('penerimaan_barang_po_details')->where('penerimaan_barang_po_id',$value->PBPO_id)->count("item_id");
                  ?>
                  <tr>
                    <th>Jumlah Item </th><th>{{$jumlah_item}} Item</th>
                  </tr>
                  <tr>
                    <th>Jumlah Item Barang Diterima</th><th>{{$jumlah_diterima[0]->quantity}} Barang</th>
                  </tr>
                  <tr>
                    <th>Status Approval</th><th>{{$value->action_description}}</th>
                  </tr>
                </thead>
              </table>
                <table id="ListTelahKelompok" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
                    <thead style="background-color: greenyellow;">
                      <tr >
                        <th class="table-align-right">No</th>
                        <th>Item</th>
                        <th>Brand</th>
                        <th>Satuan</th>
                        <th class="text-right">Quantity Total</th>
                        <th class="text-right">Quantity Di terima</th>
                        <th>Gudang</th>
                        <th>Deskripsi Item Diterima</th>
                      </tr>
                      </thead>
                      <tbody>
                        <?php
                          $model = DB::table('purchaseorder_details')->where("purchaseorder_id",$value->PO_id)
                                    ->join("item_projects","item_projects.id","purchaseorder_details.item_id")
                                    ->join("items","items.id","item_projects.item_id")
                                    ->join("penerimaan_barang_po_details","penerimaan_barang_po_details.po_detail_id","purchaseorder_details.id")
                                    ->join("penerimaan_barang_pos","penerimaan_barang_pos.id","penerimaan_barang_po_details.penerimaan_barang_po_id")
                                    ->join("item_satuans","item_satuans.id","purchaseorder_details.satuan_id")
                                    ->join("brands","brands.id","purchaseorder_details.brand_id")
                                    ->join("warehouses","warehouses.id","penerimaan_barang_po_details.gudang_id")
                                    ->where("penerimaan_barang_pos.id",$value->PBPO_id)
                                    ->select("items.id as item_id","items.name as item_name","purchaseorder_details.brand_id as brand_id","purchaseorder_details.satuan_id as satuan_id","purchaseorder_details.description as description","purchaseorder_details.quantity as quantity","purchaseorder_details.id as id","purchaseorder_details.id as pod_id","purchaseorder_details.item_id as item_pod_id","purchaseorder_details.harga_satuan as harga_satuan","purchaseorder_details.ppn as ppn","purchaseorder_details.pph as pph","purchaseorder_details.discon as discon","purchaseorder_details.purchaseorder_id as po_id","penerimaan_barang_po_details.quantity as quantity_diterima","penerimaan_barang_pos.id as PBPO_id","item_satuans.name as satuan_name","brands.name as brand_name","penerimaan_barang_po_details.gudang_id as gudang","penerimaan_barang_po_details.description_item_diterima as description_item_diterima","warehouses.name as gudang_name")->get();
                        ?>
                          @foreach($model as $key => $v)
                            <tr>
                              <td class="table-align-right">{{$key+1}}</td>
                              <th>{{ $v->item_name}}</th>
                              <td>{{ $v->brand_name}}</td>
                              <td>{{ $v->satuan_name}}</td>
                              <td class="table-align-right">{{$v->quantity}}</td>
                              <td class="table-align-right">{{$v->quantity_diterima}}</td>
                              <td>{{$v->gudang_name}}</td>
                              <td>{{$v->description_item_diterima}}</td>
                            </tr>
                          @endforeach

                          @php
                              $no = $key;

                          @endphp
                          @php
                              $bonus = \Modules\PenerimaanBarangPO\Entities\PenerimaanBonus::where('penerimaan_barang_po_id', $value->PBPO_id)->first();
                          @endphp
                          {{-- {{$bonus->penerimaan_bonus_detail}} --}}
                          @if ($bonus != null)
                            @foreach($bonus->penerimaan_bonus_detail as $key2 => $value2)
                              <tr style="background-color:#adff2f4a">
                                <td class="table-align-right">{{$no+1}}</td>
                                <th>{{ $value2->item}}</th>
                                <td>{{ $value2->brand}}</td>
                                <td>{{ $value2->satuan}}</td>
                                <td class="table-align-right"></td>
                                <td class="table-align-right">{{$value2->quantity}}</td>
                                <td>{{$value2->warehouse->name}}</td>
                                <td>{{$value2->description_item_diterima}}</td>
                              </tr>
                              @php
                                  $no++
                              @endphp
                            @endforeach
                          @endif
                      </tbody>
                  </table>
            </div>                
          </div>
        </div>
      </div>
      @php
       $i++;   
      @endphp
    @endforeach
  </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 2.4.0
    </div>
    <strong>Copyright &copy; 2014-2016 <a href="https://adminlte.io">Almsaeed Studio</a>.</strong> All rights
    reserved.
  </footer>

  
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

@include("master/footer_table")
@include('pluggins.alertify')
<!--@include("pt::app")-->
</body>
<script type="text/javascript">
  $.ajaxSetup({
      headers: {
        'X-CSRF-Token': $('meta[name=csrf-token]').attr('content')
      }
    });
    function changeDetilModal(val){
        document.getElementById("modal-header-h3").innerHTML  = "Detail PO di Item No. "+val;
        console.log(val);
        var url = "{{ url('/')}}/penerimaanbarangpo/getDetilPOPR";
        var item = $("#tbodyPOPR");
        var send1 = val;
        console.log(url + '/' + send1);
        var getjson = $.getJSON(url + '/' + send1, function (data) {
            item.addClass("item");
            item.empty();
            for(var i = 0; i <= data.length ; i++){
                if(i<data.length){
                    var tr        = document.createElement("tr");
                    tr.value      = data[i].id;
                    tr.innerHTML  = "<tr>"
                                    +"<td>"+[i+1]+"</td>"
                                    +"<td>"+data[i].purchaserequest_no+"</td>"
                                    +"<td>"+data[i].btNo+"</td>"
                                    +"<td>"+data[i].departement_name+"</td>"
                                    +"<td>"+data[i].quantity+"</td>"
                                    +"<td>"+data[i].satuan+"</td>"
                                    +"<td>"+data[i].urgent+"</td>"
                                    +"<td>"+data[i].date_dibutuhkan+"</td>"
                                    +"</tr>";
                    item.append(tr);
                }else
                    item.removeClass("item");
                }
            document.getElementById("divTender").style.visibility = "visible";
        }); 
    }

    $(document).ready(function()
    {
        $('.btn-approve').click(function()
        {
            var obj = $(this);
            var _data = { id:parseInt($(this).attr('data-value')) };
            var _url = "{{ url('/penerimaanbarangpo/approve_perdetail') }}";

            $.ajax({
              type:'post',
              url:_url,
              data:_data,
              dataType:'json',
              beforeSend:function()
              {
                  waitingDialog.show();
              },
              success:function(data)
              {
                  if(data)
                  {
                      alertify.success('Berhasil');
                      // obj.remove();
                  }
              },
              complete:function()
              {
                  waitingDialog.hide();
              }
            });
        });

        $('.btn-undo_approve').click(function()
        {
            var obj = $(this);
            var _data = { id:parseInt($(this).attr('data-value')) };
            var _url = "{{ url('/penerimaanbarangpo/undo_approve_perdetail') }}";

            $.ajax({
              type:'post',
              url:_url,
              data:_data,
              dataType:'json',
              beforeSend:function()
              {
                  waitingDialog.show();
              },
              success:function(data)
              {
                  if(data)
                  {
                      alertify.success('Berhasil');
                      // obj.remove();
                  }
              },
              complete:function()
              {
                  waitingDialog.hide();
              }
            });
        });

        $('.btn-request_approve').click(function()
        {
            var obj = $(this);
            var _data = { id:parseInt($(this).attr('data-value')) };
            var _url = "{{ url('/penerimaanbarangpo/request_approve_perdetail') }}";

            $.ajax({
              type:'post',
              url:_url,
              data:_data,
              dataType:'json',
              beforeSend:function()
              {
                  waitingDialog.show();
              },
              success:function(data)
              {
                  if(data)
                  {
                      alertify.success('Berhasil');
                      // obj.remove();
                  }
              },
              complete:function()
              {
                  waitingDialog.hide();
              }
            });
        });

        $('.btn-undo_request_approve').click(function(){
            var obj = $(this);
            var _data = { id:parseInt($(this).attr('data-value')) };
            var _url = "{{ url('/penerimaanbarangpo/undo_request_approve_perdetail') }}";

            $.ajax({
              type:'post',
              url:_url,
              data:_data,
              dataType:'json',
              beforeSend:function()
              {
                  waitingDialog.show();
              },
              success:function(data)
              {
                  if(data)
                  {
                      alertify.success('Berhasil');
                      // obj.remove();
                  }
              },
              complete:function()
              {
                  waitingDialog.hide();
              }
            });
        });

        $('.btn-request_setuju').click(function(){
            var obj = $(this);
            var _data = { id:parseInt($(this).attr('data-value')) };
            var _url = "{{ url('/penerimaanbarangpo/request_setuju_perdetail') }}";

            $.ajax({
              type:'post',
              url:_url,
              data:_data,
              dataType:'json',
              beforeSend:function()
              {
                  waitingDialog.show();
              },
              success:function(data)
              {
                  if(data)
                  {
                      alertify.success('Berhasil');
                      // obj.remove();
                  }
              },
              complete:function()
              {
                  waitingDialog.hide();
              }
            });
        });
    });
</script>
</html>
