<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Admin QS | Dashboard</title>
  {{-- @include("user.header") --}}
  @include("master/header")
  <style type="text/css">
    .table-align-right{
      text-align: right;
    }
    select{
      background-color: white;
      width: 100%;
    }
  </style>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  @include("user.sidebar")

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1 style="text-align:center">Detail Purchase Request</h1>
    </section>
    <section class="back-button content-header">
      <div class="">
        <button class="col-lg-1 col-md-2 col-sm-2 btn btn-primary" onclick="location.href='{{ url('/')}}/access'" style="float: none; border-radius: 20px; padding-left: 0">
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
        <div class="col-xs-12">
          <div class="box">
            
            <!-- /.box-header -->
            <div class="box-body">
              
              <div class="col-md-12">
                  <div class="panel panel-success">
      <!-- Default panel contents -->
                  <div class="panel-heading" style="height: 55px">
                    <div class="col-md-10">
                      Informasi PR Nomor : <strong>{{ $PRHeader->no }}</strong>
                      <input type="hidden" name="pr_id" id="pr_id" value="{{ $PRHeader->id }}"/>
                      <input type="hidden" name="user_id" id="user_id" value="{{ $user->id }}"/>

                    </div>
                   <!--  <div class="col-md-2">
                    @if($approve)
                      @if($PRHeader->approval->approval_action_id === 1)
                        <form method="POST" action="{{ url('/')}}/purchaserequest/request_approval" name="form1" autocomplete="off">
                          <input type="" name="id" value="{{$PRHeader->id}}" hidden>
                          {!! csrf_field() !!}
                          <input type="submit" value="Request Approval" class="btn btn-primary pull-right">
                        </form>
                      @elseif($PRHeader->approval->approval_action_id === 2)
                        <form method="POST" action="{{ url('/')}}/purchaserequest/batalrequest_approval" name="form1" autocomplete="off">
                          <input type="" name="id" value="{{$PRHeader->id}}" hidden>
                          {!! csrf_field() !!}
                          <input type="submit" value="Undo Approval" class="btn btn-primary pull-right">
                        </form>
                        @endif            
                    @endif
                    </div> -->
                    </div>
                  <!-- List group -->
                  
                  <div class="panel-body">

                  <div class="col-md-6">
                      <div class="panel-body">

                        <label>PR Info</label>
                      </div>
                      <ul class="list-group">
                       
                        <li class="list-group-item">PT : <strong>{{ $PRHeader->pt->name }}</strong></li>
                        <li class="list-group-item">Department : <strong>{{ $PRHeader->department->name }}</strong></li>
                        @if($PRHeader->approval->status->description == "approved")
                          <li class="list-group-item">Status : <strong style="color:green;">{{ strtoupper($PRHeader->approval->status->description) }}</strong></li>
                          @elseif($PRHeader->approval->status->description == "delivered")
                          <li class="list-group-item">Status : <strong style="color:orange;">{{ strtoupper($PRHeader->approval->status->description) }}</strong></li>
                          @elseif($PRHeader->approval->status->description == "partial approved")
                          <li class="list-group-item">Status : <strong style="color:#40E0D0;">{{ strtoupper($PRHeader->approval->status->description) }}</strong></li>
                          @elseif($PRHeader->approval->status->description == "open")
                          <li class="list-group-item">Status : <strong style="color:black;">{{ strtoupper($PRHeader->approval->status->description) }}</strong></li>
                          @elseif($PRHeader->approval->status->description == "rejected")
                          <li class="list-group-item">Status : <strong style="color:red;">{{ strtoupper($PRHeader->approval->status->description) }}</strong></li>
                        @endif
                        <li class="list-group-item">Tanggal PR Dibuat : <strong>{{ $PRHeader->date }}</strong></li>
                        <li class="list-group-item">Tanggal Dibutuhkan : <strong>{{ $PRHeader->butuh_date }}</strong></li>
                        
                      </ul>
                  </div>
                  <div class="col-md-6">
                    <div class="panel-body">
                      <label>Budget Info</label>
                    </div>
                    <ul class="list-group">
                      <li class="list-group-item">Nomor Budget : <strong>{{ $PRHeader->budget->no or "kosong"}}</strong></li>
                      <li class="list-group-item">Tahun Budget : <strong>{{ $PRHeader->budget->tahun_anggaran or "kosong"}}</strong></li>
                      <li class="list-group-item">Deskripsi Budget : <strong>{{ $PRHeader->budget->description or 'Kosong' }}</strong></li>

                       <li class="list-group-item">Sisa Budget Sebelum : <strong>{{ $total or 'Kosong' }}</strong></li>
                       <li class="list-group-item">Pengguna Budget Terakhir : <strong>{{ $pengguna_terakhir->department->name or 'Kosong' }}</strong></li>
                       <li class="list-group-item">Jumlah Digunakan terakhir untuk SPK/PO: <strong>{{$totalTerakhir}} </strong></li>
                    </ul>
                  </div>
                  @if($user->user_login<=>"approval1")
                    @if ($PRHeader->approval->approval_action_id == 1)
                    <a href="{{ url('/purchaserequest/edit',$PRHeader->id) }}" class="btn btn-primary pull-right"><i class="fa fa-edit"></i> Edit</a>
                    @endif
                  @endif
                </div>
                </div>
                {{-- @if($approve)
                 @if($PRHeader->approval->status->description<>"open")
                  <div class="row" style="padding-bottom: 20px;margin: 0px 15px">
                    @if($PRHeader->approval->status->description<>"approved")
                    <a href="{{ url('/')}}/access/purchaserequest/detail/approve/?id={{$pr_id}}&type=approveAll" class="btn btn-success col-md-1 pull-left"
                    style="width:9%">Approve All</a>
                    @else
                    <a href="{{ url('/')}}/access/purchaserequest/detail/approve/?id={{$pr_id}}&type=cancelAll" class="btn btn-danger" style="width:9%;margin-left: 1%">UnApprove All</a>
                    @endif
                  </div>
                  @endif
                @endif --}}
                @if ( $PRHeader->approval->histories->where("user_id",$user->id)->first()->approval_action_id == "1")
                  <a href="#" class="btn btn-info" onclick="setapproved('6')" data-toggle="modal" data-target="#myModal">Approve</a>
                  <a href="#" class="btn btn-danger" onclick="setapproved('7')" data-toggle="modal" data-target="#myModal">Reject</a>
                @elseif ( $PRHeader->approval->histories->where("user_id",$user->id)->first()->approval_action_id == "6")
                    <span class="badge badge-success" style="font-size: 20px;">Approved</span>
                @elseif ( $PRHeader->approval->histories->where("user_id",$user->id)->first()->approval_action_id == "7")
                    <span class="badge badge-danger" style="font-size: 20px;">Reject</span>
                @endif
              <table id="table_details" class="table table-bordered table-hover">
                <thead style="" class="head_table">
                <tr>
                  <th rowspan="2">Category</th>
                  {{-- @if($approve)
                  <th rowspan="2">Action</th>
                  @endif --}}
                  <th rowspan="2" >Item Pekerjaan</th>
                  <th rowspan="2" >Item</th>
                  <th rowspan="2" >Kode Item</th>
                  <th rowspan="2" >Brand</th>
                  <th rowspan="2" >Qty</th>
                  <th rowspan="2" >Satuan</th>
                  <th colspan="3" class="text-center">Rekomendasi Supplier</th>
                  <th rowspan="2">Deskripsi</th>
                  {{-- <th rowspan="2">Status</th> --}}
                </tr>
                  <tr>
                    <th>Supplier 1</th>
                    <th>Supplier 2</th>
                    <th>Supplier 3</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach($PR as $key => $value )
                    <tr>
                        <td>{{is_null($value->item_project->item->sub_category) ? $value->item_project->item->category->name : $value->item_project->item->sub_category->name}}</td>
                        {{-- @if($approve)
                          @if($PRHeader->approval->status->description<>"open")
                            @if($value->approval->approval_action_id == 6)
                              <td><a href="{{ url('/')}}/access/purchaserequest/detail/approve/?id={{$value->id}}&type=cancel&pr_id={{$value->purchaserequest_id}}" class="btn btn-danger col-md-12">UnApprove</a></td>
                            @elseif($value->approval->approval_action_id == 7)
                              <td><a href="{{ url('/')}}/access/purchaserequest/detail/reject/?id={{$value->id}}&type=unreject&pr_id={{$value->purchaserequest_id}}" class="btn btn-danger col-md-12">UnReject</a></td>
                            @else
                              <td>
                                <a href="{{ url('/')}}/access/purchaserequest/detail/approve/?id={{$value->id}}&type=approve&pr_id={{$value->purchaserequest_id}}" class="btn btn-success col-md-12" style="margin-bottom:10px">Approve</a>
                                <button type="button" class="btn btn-danger btn-sm col-md-12" data-toggle="modal" data-target="#myModalreject">Reject</button>
                                <!-- Modal -->
                                  <div class="modal fade" id="myModalreject" role="dialog" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog" role="document">

                                      <!-- Modal content-->
                                      <div class="modal-content">
                                        <form method="GET" action="{{ url('/')}}/access/purchaserequest/detail/reject">
                                        <input type="" name="id" value="{{$value->id}}" hidden>
                                        <input type="" name="type" value="reject" hidden>
                                        <input type="" name="pr_id" value="{{$value->purchaserequest_id}}" hidden>
                                        <div class="modal-header">
                                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                                          <h4 class="modal-title">Reject</h4>
                                        </div>
                                        <div class="modal-body" style="height: auto">
                                          
                                            <p>Apa anda yakin ingen mereject item ini?</p>
                                           <!--  <div class="form-group">
                                              <label for="recipient-name" class="col-form-label">Recipient:</label>
                                              <input type="text" class="form-control" id="recipient-name">
                                            </div> -->
                                            <div class="form-group">
                                              <label class="col-md-12" style="padding-left:0">Deskripsi Item</label>
                                              <textarea id="deskripsiReject" name="deskripsi_reject" class="form-input col-md-12 item_desk" style="width: 570px;max-width: 570px" rows="3" required></textarea>
                                            </div>
                                          
                                          <!-- <p>Apa anda yakin ingen mereject item ini?</p>
                                                <label class="col-md-12" style="padding-left:0">Deskripsi Item</label>
                                                <textarea id="deskripsi_item1" name="deskripsi_item[]" class="form-input col-md-12 item_desk" style="margin-bottom: 15px" rows="7" required></textarea>
                                        </div> -->
                                        </div>
                                        <div class="modal-footer">
                                          <button type="button" class="btn btn-default btn-xs" data-dismiss="modal">Close</button>
                                          <input type="submit" class="btn btn-danger pull-right btn-xs btn-delete" value="Reject"></input>
                                        </div>
                                        </form>
                                      </div>
                                    </div>
                                  </div>
                               <!--  <a href="{{ url('/')}}/purchaserequest/reject/?id={{$value->id}}&type=reject&pr_id={{$value->purchaserequest_id}}" class="btn btn-danger col-md-12">Reject</a> -->
                              </td>
                            @endif
                          @else
                            <td><strong>harus request approval terlebih dahulu</strong></td>
                          @endif
                        @endif --}}
                        <td>{{ $value->item_pekerjaan->code }} - {{$value->item_pekerjaan->name or 'Kosong'}}</td>
                        <td>{{$value->item_project->item->name or 'Kosong'}}</td>
                        <td>{{$value->item_project->item->kode or 'Kosong'}}</td>
                        <td>{{$value->brand->name or 'Kosong'}}</td>
                        <td class="table-align-right">{{$value->quantity}}</td>
                        <td>{{$value->item_satuan->name or 'Kosong'}}</td>
                        <td>{{$value->rec1->name or 'Kosong'}}</td>
                        <td>{{$value->rec2->name or 'Kosong'}}</td>
                        <td>{{$value->rec3->name or 'Kosong'}}</td>
                        <td>{{$value->description}}</td>
                        {{-- @if($value->approval->status->description == "approved")
                          <td><strong style="color:green;">{{ strtoupper($value->approval->status->description) }}</strong></td>
                          @elseif($value->approval->status->description == "delivered")
                          <td><strong style="color:yellow;">{{ strtoupper($value->approval->status->description) }}</strong></td>
                          @elseif($value->approval->status->description == "partial approved")
                          <td><strong style="color:#40E0D0;">{{ strtoupper($value->approval->status->description) }}</strong></td>
                          @elseif($value->approval->status->description == "open")
                          <td><strong style="color:black;">{{ strtoupper($value->approval->status->description) }}</strong></td>
                          @elseif($value->approval->status->description == "rejected")
                          <td><strong style="color:red;">{{ strtoupper($value->approval->status->description) }}</strong></td>
                        @endif --}}
                    </tr>
                    @endforeach
                  </tbody>
             <!--      <tfoot>
                      <tr>
                        <th rowspan="2">Category</th>
                        <th rowspan="2" >Item Pekerjaan</th>
                        <th rowspan="2" >Item</th>
                        <th rowspan="2" >Kode Item</th>
                        <th rowspan="2" >Brand</th>
                        <th rowspan="2" >Qty</th>
                        <th rowspan="2" >Satuan</th>
                        <th colspan="3" class="text-center">Rekomendasi Supplier</th>
                        <th rowspan="2">Deskripsi</th>
                        <th rowspan="2">Status</th>
                        @if($approve)
                        <th rowspan="2">Action</th>
                        @endif
                      </tr>
                        <tr>
                          <th></th>
                          <th></th>
                          <th></th>
                        </tr>
                  </tfoot> -->
                </table>
              </div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>

  <div class="modal fade" tabindex="-1" role="dialog" id="myModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><br>
            </div>
            <div class="modal-body">
                <span id="title_approval"><strong></strong></span>
                <p></p>
                <div id="listdetail">
                    <textarea name="description2" id="description2" rows="6" style="width:100%"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btn_save_budgets" data-value="" onclick="requestApproval()">Submit</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
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
@include('pluggins.select2_pluggin')
<script type="text/javascript">
      $.ajaxSetup({
    headers: {
                  'X-CSRF-Token': $('meta[name=csrf-token]').attr('content')
                }
    });
  $(document).ready(function()
  {
      $('#table_details').DataTable({
          scrollY: "500px",
          scrollX:true,
          scrollCollapse: true,
          paging: false,
          "columnDefs": [
            { "visible": false, "targets": 0 }
          ],
          "order": [[ 0, 'asc' ]],
          "drawCallback": function ( settings ) {
            var api = this.api();
            var rows = api.rows( {page:'current'} ).nodes();
            var last=null;
 
            api.column(0, {page:'current'} ).data().each( function ( group, i ) {
                if ( last !== group ) {
                    $(rows).eq( i ).before(
                        '<tr class="group" style="background-color: #3FD5C0;""><td colspan="13"><strong>'+group+'</strong></td></tr>'
                    );
 
                    last = group;
                }
            } );
        },
        initComplete: function () {
            this.api().columns().every( function () {
                var column = this;
                var select = $('<select><option value=""></option></select>')
                    .appendTo( $(column.footer()).empty() )
                    .on( 'change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );

                        column
                            .search( val ? '^'+val+'$' : '', true, false )
                            .draw();
                    } );

                column.data().unique().sort().each( function ( d, j ) {
                    select.append( '<option value="'+d+'">'+d+'</option>' )
                } );
            } );
        }
      });
      // $('select').select2({
      //       dropdownAutoWidth : true,
      //       width: '100%'
      //   })
      
  });

  function setapproved(values,budget_id){
      if ( values == "6" ){
          $("#title_approval").attr("style","color:blue");
          $("#title_approval").text("These Purchase Request will be APPROVED by You");
      }else{
          $("#title_approval").attr("style","color:red");
          $("#title_approval").text("These Purchase Request will be REJECTED by You");
      }
      $("#btn_save_budgets").attr("data-value",values);
      $("#budget_id").val(budget_id);
  }

  function requestApproval(){
      if ( $("#btn_save_budgets").attr("data-value") == "7"){
          if ( $("#description2").val() == ""){
              alert("Silahkan isi dengan pesan terlebih dahulu");
              return false;
          }
      }
      var request = $.ajax({
          url : "{{ url('/') }}/access/purchaserequest/detail/approve",
          type :"post",
          dataType :"json",
          data: {
              user_id : $("#user_id").val(),
              pr_id :$("#pr_id").val(),
              status : $("#btn_save_budgets").attr("data-value"),
              description :  $("#description2").val()
          },
          beforeSend: function() {
            waitingDialog.show();
          },
          success: function(data) { 
            if ( data.status == "0"){
              window.location.reload();
            }else{
              alert("Error When Saving Approval");
              // window.location.reload();
            }
          },
          complete: function() {
            waitingDialog.hide(); 
          },
      });
  }
</script>
</body>
</html>
