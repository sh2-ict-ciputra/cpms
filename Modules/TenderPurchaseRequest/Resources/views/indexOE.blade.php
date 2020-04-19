<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Admin QS | Dashboard</title>
  @include("master/header")
  <style type="text/css">
    .table-align-right{
      text-align: right;
    }
  </style>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  @include("master/sidebar_project")

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" style="height:100%">
    <!-- Content Header (Page header) -->
    
    <section class="content-header">
      <h1 style="text-align:center">Data OE Purchase Request {{ $project->name }}</h1>
    </section>
    <section class="content-header">
      <div class="" style="float: none">
        <button class="col-lg-1 col-md-2 col-sm-2 btn btn-primary" onclick="location.href='{{ url('/')}}/'" style="float: none; border-radius: 20px; padding-left: 0" disabled>
        <i class="fa fa-fw fa-arrow-left"></i>&nbsp;&nbsp;Back
        </button>
        <button class="col-lg-1 col-md-2 col-sm-2 btn btn-primary" onclick="window.location.reload()" style="float: right; border-radius: 20px; padding-left: 0;">
          <i class="fa fa-fw fa-refresh"></i>&nbsp;&nbsp;Refresh
        </button>  
      </div>
    </section>
    <section class="content" style="height: 100%">
      <!-- Info boxes -->
      <div class="row">
        <div class="col-md-12">
          <div class="box box-primary">
            {{-- <p/> --}}
            <ul class="nav nav-tabs">
              <li class="active"><a data-toggle="tab" href="#home">Group Tender PR</a></li>
            </ul>

            <div class="tab-content">
              <div id="home" class="tab-pane fade in active">
                <input type="hidden" name="all_item_send" id="all_item_send" value="" />
                  @if(strcmp($user->user_login,"administrator")==0)
                    <div class="box-header with-border" style="background-color:white">
                      
                      <div class="col-md-3">
                        <button type="button" class="btn btn-block btn-primary btn-md" id="btn-req-approval" data-url="{{ url('/tenderpurchaserequest/request_approveOEfromIndex') }}">
                          <i class="fa fa-fw fa-send"></i> Request Approval</button>
                      </div>
                      <div class="col-md-3">
                        <button type="button" class="btn btn-block btn-warning btn-md" id="btn-undo-approval" data-url="{{ url('/tenderpurchaserequest/undo_request_oe_fromindex')}}">
                          <i class="fa fa-fw fa-reply"></i> Undo Approval</button>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <div class="checkbox">
                            <label>
                              <input type="checkbox" name="check_all_item" id="check_all_item" class="check_all_item" /> Check All
                            </label>
                          </div>
                        </div>
                      </div>
                    </div>
                  @endif
                  @if(strcmp($user->user_login,"approval1")==0)
                    <div class="box-header with-border" style="background-color:white">
                      
                      <div class="col-md-3">
                        <button type="button" class="btn btn-block btn-primary btn-md" id="btn-approve" data-url="{{ url('/tenderpurchaserequest/approveOEfromIndex') }}">
                          <i class="fa fa-fw fa-send"></i>Approve</button>
                      </div>
                      <div class="col-md-3">
                        <button type="button" class="btn btn-block btn-warning btn-md" id="btn-undo-approve" data-url="{{ url('/tenderpurchaserequest/undo_approve_oe_fromindex')}}">
                          <i class="fa fa-fw fa-reply"></i> Undo Approve</button>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <div class="checkbox">
                            <label>
                              <input type="checkbox" name="check_all_item" id="check_all_item" class="check_all_item" /> Check All
                            </label>
                          </div>
                        </div>
                      </div>
                    </div>
                    @endif
                <div class="box-body">
                  <table id="ListTelahKelompok" class="table table-bordered table-striped dataTable" role="grid" >
                      <thead style="background-color: greenyellow;">
                        <tr>
                          <th>No Group Tender</th>
                          <th>Kode Item</th>
                          <th>Item</th>
                          <th>Brand</th>
                          <th class="text-right">Quantity</th>
                          <th>Satuan</th>
                          <th>Desc</th>
                          <th>Status</th>
                        </tr>
                      </thead>
                        
                  </table>
                </div>
              </div>
            </div>
        </div>
          
        </div>
      </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    {{-- <div class="pull-right hidden-xs">
      <b>Version</b> 2.4.0
    </div>
    <strong>Copyright &copy; 2014-2016 <a href="https://adminlte.io">Almsaeed Studio</a>.</strong> All rights
    reserved. --}}
  </footer>

  
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

@include("master/footer_table")
@include('pluggins.alertify')
<script>
  $.ajaxSetup({
    headers: {
      'X-CSRF-Token': $('meta[name=csrf-token]').attr('content')
    }
  });
    var arr_item_checked = [];
    var gentable = null;
    var fnLabelStatus = function(data,type,row)
    {
      var retVal = "";
      if (type == 'display') {

        if(data=="approved")
        {
          retVal ="<strong style='color:green;'> APPROVED </strong>";
        }else if(data=="delivered")
        {
          retVal ="<strong style='color:orange;'> DELIVERED </strong>";
        }else if(data=="partial approved")
        {
          retVal ="<strong style='color:#40E0D0;'> PARTIAL APPROVED </strong>";
        }else if(data=="open")
        {
          retVal ="<strong style='color:black;'> OPEN</strong>";
        }else if(data=="rejected")
        {
          retVal ="<strong style='color:red;'> REJECTED </strong>";
        }
      }
      return retVal;

    }
  $(function () {
    gentable = $('#ListTelahKelompok').DataTable({
          // scrollY: "300px",
          ordering: true,
          //scrollX:true,
          // scrollCollapse: true,
          paging: false,
          ajax: "{{ url('/tenderpurchaserequest/getItemOE') }}",
          columns:[
                 { data: 'no',name: 'no',"bSortable": true},
                 {data: 'kode',name:'kode',"bSortable":true},
                 {data: 'itemName',name:'itemName',"bSortable":true},
                 {data: 'brandName',name:'brandName',"bSortable":true},
                 {data: 'totalqty',name:'totalqty','sClass':'text-right',"bSortable":true},
                 {data: 'satuanName',name:'satuanName',"bSortable":true},
                 {data: 'description',name:'description',"bSortable":true},
                 {data: 'approvDescription',name:'approvDescription',render:fnLabelStatus,"bSortable":true}
          ],
          "columnDefs": [
            { "visible": false, "targets": 0 }
          ],
        "order": [[ 0, 'desc' ]],
        "drawCallback": function ( settings ) {
            var api = this.api();
            var rows = api.rows( {page:'current'} ).nodes();
            var last=null;
 
            api.column(0, {page:'current'} ).data().each( function ( group, i ) {
                if ( last !== group ) {
                    $(rows).eq( i ).before(
                        '<tr class="group" style="background-color: #3FD5C0;""><td colspan="9"><strong><div class="checkbox check_item"><label><input type="checkbox" name="check_item" id="check_item" class="check_item" value="'+group+'" />'+group+'</strong></label></div><a href="'+"{{ url('/tenderpurchaserequest/detail_oe') }}"+"?id="+group+'" class="btn btn-primary pull-right btn-xs" rel="tooltip" data-toggle="tooltip" data-placement="left" title="Details"><i class="fa fa-list"></i></a></td></tr>'
                    );
 
                    last = group;
                }
            } );
        },
        "initComplete": function(settings, json) {
          $('.group').nextUntil('.group').css( "display", "none" );
        }
    });

    var sBody = $('#ListTelahKelompok tbody');
      sBody.on('click','.group',function()
      {
        $(this).nextUntil('.group').toggle();

      }).find('.group').each(function(i,v){
        var rowCount = $(this).nextUntil('.group').length;
        $(this).find('td:first').append($('<span />', { 'class': 'rowCount-grid' }).append($('<b />', { 'text': ' (' + rowCount + ')' })));
      });

       $(document).on('click','.check_all_item',function()
    {
        if($(this).is(':checked'))
        {
          sBody.find('.check_item').prop('checked',true);
          sBody.find('input.check_item').each(function(i,v)
          {
              arr_item_checked.push($(this).val());
          });
        }
        else
        {
          sBody.find('.check_item').prop('checked',false);
          arr_item_checked = [];
        }

        $('#all_item_send').val(JSON.stringify(arr_item_checked));
        
    });

    sBody.on('click','input.check_item',function()
    {
        var nilai_item = $(this).val();
        if($(this).is(':checked'))
        {

            if(arr_item_checked.length > 0)
            {
              if(arr_item_checked.includes(nilai_item) == false)
              {
                arr_item_checked.push(nilai_item);
              }
            }
            else
            {
              arr_item_checked.push(nilai_item);
            }
            console.log('check item dengan nilai '+nilai_item);
        }else
        {

          if(arr_item_checked.includes(nilai_item) == true)
          {
              var index = arr_item_checked.indexOf(nilai_item);
              arr_item_checked.splice(index,1);

              console.log('uncheck item dengan nilai'+nilai_item);
          }
        }

        $('#all_item_send').val(JSON.stringify(arr_item_checked));
    });

    $('#btn-req-approval').click(function()
    {

          var _data_sendApproval = {id : $('#all_item_send').val()};
          $.ajax({
              type: 'POST',
              url: $(this).attr('data-url'),
              data: _data_sendApproval,
              dataType: 'json',
              beforeSend:function(){
                waitingDialog.show();
              },
              success:function(data){
                if(data)
                {
                    alertify.success('success !',4);
                    gentable.ajax.reload();
                }
                else
                {
                  alertify.error('Gagal, Periksa Kembali Data Anda');
                }
              },
              error:function(xhr,status,errormessage)
              {
              },
              complete:function()
              {arr_item_checked = [];$('.check_item,.check_all_item').prop('checked',false);waitingDialog.hide();}
            });
    });

    $('#btn-undo-approval').click(function()
    {
        var _data_sendUndo = {id : $('#all_item_send').val()};
        $.ajax({
              type: 'POST',
              url: $(this).attr('data-url'),
              data: _data_sendUndo,
              dataType: 'json',
              beforeSend:function(){
                waitingDialog.show();
              },
              success:function(data){
                if(data)
                {
                    alertify.success('success !',4);
                    gentable.ajax.reload();
                }
                else
                {
                  alertify.error('Gagal, Periksa Kembali Data Anda');
                }
              },
              error:function(xhr,status,errormessage)
              {
              },
              complete:function()
              {arr_item_checked = [];$('.check_item,.check_all_item').prop('checked',false);waitingDialog.hide();}
            });
    });

    $('#btn-approve').click(function()
    {

          var _data_sendApproval = {id : $('#all_item_send').val()};
          $.ajax({
              type: 'POST',
              url: $(this).attr('data-url'),
              data: _data_sendApproval,
              dataType: 'json',
              beforeSend:function(){
                waitingDialog.show();
              },
              success:function(data){
                if(data)
                {
                    alertify.success('success !',4);
                    gentable.ajax.reload();
                }
                else
                {
                  alertify.error('Gagal, Periksa Kembali Data Anda');
                }
              },
              error:function(xhr,status,errormessage)
              {
              },
              complete:function()
              {arr_item_checked = [];$('.check_item,.check_all_item').prop('checked',false);waitingDialog.hide();}
            });
    });

    $('#btn-undo-approve').click(function()
    {
        var _data_sendUndo = {id : $('#all_item_send').val()};
        $.ajax({
              type: 'POST',
              url: $(this).attr('data-url'),
              data: _data_sendUndo,
              dataType: 'json',
              beforeSend:function(){
                waitingDialog.show();
              },
              success:function(data){
                if(data)
                {
                    alertify.success('success !',4);
                    gentable.ajax.reload();
                }
                else
                {
                  alertify.error('Gagal, Periksa Kembali Data Anda');
                }
              },
              error:function(xhr,status,errormessage)
              {
              },
              complete:function()
              {arr_item_checked = [];$('.check_item,.check_all_item').prop('checked',false);waitingDialog.hide();}
            });
    });

  });

</script>
</body>
</html>
