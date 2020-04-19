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
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    
    <section class="content-header">
      <h1 style="text-align:center">List Pemenang Tender {{ $project->name }}</h1>
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
    <section class="content">
      <!-- Info boxes -->
      <div class="row">
        <div class="col-md-12">
          <div class="box box-primary">
                <div class="box-body">
                	
                  <table class="table table-bordered table-striped dataTable" role="grid" id="table_data">
                      <thead style="background-color: greenyellow;">
                        <tr>
                          <th>No Tender</th>
                          <th>Nomor Penawaran</th>
                          <th>Rekanan</th>
                          <th class="table-align-right">Total (Rp.)</th>
                          <th></th>
                        </tr>
                        </thead>
                    </table>
                </div>
        </div>
          
        </div>
      </div>
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
<script type="text/javascript">
  $.ajaxSetup({
    headers: {
      'X-CSRF-Token': $('meta[name=csrf-token]').attr('content')
    }
  });

    var gentable = null;
  $(function () {
    gentable = $('#table_data').DataTable({
          scrollY: "300px",
          //scrollX:true,
          scrollCollapse: true,
          paging: false,
          ajax: "{{ url('/pemenangtender/show') }}",
          columns:[
                 { data: 'tender_no',name: 'tender_no',"bSortable": true},
                 {data: 'penawaran_no',name:'penawaran_no',"bSortable":false},
                 {data: 'rekanan_name',name:'rekanan_name',"bSortable":false},
                 {data: 'total',name:'total','sClass':'text-right',"bSortable":false},
                 {
                 	 "className": "action text-center",
	                  "data": null,
	                  "bSortable": false,
	                  "defaultContent": "" +
	                  "<div class=''>" +
	                  "<button class='details btn btn-primary btn-xs' rel='tooltip' data-toggle='tooltip' data-placement='left' title='Detail'><i class='fa fa-list'></i></button>" +
	                  "</div>"
                 }
          ],
          "columnDefs": [
            {}
          ],
        "order": [[ 0, 'asc' ]]
    });

    var tbody = $('#table_data tbody');

    tbody.on('click','.details',function()
    {
    	var id=gentable.row($(this).parents('tr')).data().id;
    	var _url = "{{ url('/pemenangtender/detail/') }}";

    	window.location.href=_url+"/"+id;
    });

});
</script>
</body>
</html>
