<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Admin QS | Dashboard</title>
  @include("master/header")
   <link href="{{ URL::asset('assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css')}}" rel="stylesheet" type="text/css" />
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  @include("master/sidebar_project")

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>{{ $project->name }}</h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
  
		  <ul class="breadcrumb">
			            <li>
			                <a href="#">Good Receive dengan DownPayment</a>
			            </li>
			            <li>
			                <span>List</span>
			            </li>
			        </ul>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="col-md-12">
              	<strong>Data Good Receive</strong>
	
				<hr />
				@include('form.a',
							[
								'href' => url('/goodreceive/gr_dp/create'),
								'caption' => 'Tambah',
								'class'=>'pull-right'
							])

	<form class="form-inline" method="post" action="{{ url('/inventory/barangmasuk_hibah/printReport') }}" id="form_cetak">
	  <div class="form-group">
	  	{{ csrf_field() }}
	     <label>Rentang Tanggal </label>
		    <div class="input-group" id="dtpicker">
		        <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
		        	<input type="text" name="periode" id="periode" class="form-control"/>
		        	<input type="hidden" name="start_opname" id="start_opname" value="{{date('Y-m-d')}}" />
		        	<input type="hidden" name="end_opname" id="end_opname" value="{{date('Y-m-d')}}" />
		    </div>
	  </div>
	  <button type="submit" class="btn btn-primary"><i class="fa fa-print"></i> Cetak</button>
	</form>
	<hr/>
		
		<table class="table table-striped table-bordered table-hover table-responsive table-checkable nowrap stripe row-border order-column table_master" id="table_data">
			<thead style="background-color: #3FD5C0;">
				<tr>
					<th>#</th>
					<th>Nomor GR</th>
					<th>Nomor PO</th>
					<th>Vendor</th>
          <th>Total DP</th>
          <!-- <th>Total Value</th> -->
					<th>Tanggal</th>
					<!-- <th>Status</th> -->
					
				</tr>
			</thead>
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
@include("master/footer_table")
@include('pluggins.alertify')
<script type="text/javascript">
	var dtpicker = null;
	// var fnCheckStatus = function(data, type, row)
	
	var gentable = null;
	$(document).ready(function(){
		gentable = $('#table_data').DataTable(
		{
		  	scrollY:"300px",
		  	//scrollX:true,
	        scrollCollapse: true,
	        paging:false,
          processing: true,
          ajax: "{{ url('/goodreceive/getData') }}",
          columns:[
             //     { data: 'nomor',name: 'nomor',"bSortable": false},
             //     { data: 'no',name: 'no',"bSortable": true},
      				   // { data: 'from_project_name',name: 'from_project_name',"bSortable": false},
             //     { data: 'from_pt_name',name: 'from_pt_name',"bSortable": false},
      				   // { data: 'tanggal_hibah',name: 'tanggal_hibah',"bSortable": false},
      				   // { data: 'total',name: 'total',"className":"text-right","bSortable": false},
      				   // { data: 'diisi',name: 'diisi',"className":"text-right","bSortable": false},
      			   	//  { data: 'reject',name: 'reject',"className":"text-right","bSortable": false},
      			   	//  { data: 'status',name:'status',render:fnLabelStatus,"className":"text-center","bSortable":false},
      				 
      				   // { data: 'selisih',name: 'selisih',render:fnSelisih,"className":"text-center","bSortable": false},
                 { data: 'no',name: 'no',"bSortable": true},
                 { data: 'nomor_gr',name: 'nomor_gr',"bSortable": true},
                 { data: 'nomor_po',name: 'nomor_po',"bSortable": false},
                 { data: 'vendor',name: 'vendor',"bSortable": false},
                 { data: 'total_dp',name: 'total_dp',"bSortable": false},
                 // { data: 'total_po_value',name: 'total_po_value',"bSortable": false},
                 { data: 'tanggal',name: 'tanggal',"bSortable": false},
                 // { data: 'status',name:'status',"className":"text-center","bSortable":false}
                 // {
                 //  "className": "action text-center",
                 //  "data": null,
                 //  "bSortable": false,
                 //  "defaultContent": "" +
                 //  "<div class='' role='group'>" +
                 //  "<button class='delete btn btn-danger btn-xs' rel='tooltip' data-toggle='tooltip' data-placement='left' title='Hapus'><i class='fa fa-trash-o'></i></button>" +
                 //  "<button type=\"button\" class=\"btn btn-success btn-xs detail\" rel='tooltip' data-toggle='tooltip' data-placement='left' title='Detail'><i class='fa fa-list'></i></button>" +
                 //  "<span class=\"sr-only\">Action</span>" +
                 //  "</div>"
                 //  }
                 // {
                 //  "className": "action text-center",
                 //  "data": null,
                 //  "bSortable": false,
                 //  "defaultContent": "" +
                 //  "<div class='' role='group'>" +
                 //  "<button class='delete btn btn-danger btn-xs' rel='tooltip' data-toggle='tooltip' data-placement='left' title='Hapus'><i class='fa fa-trash-o'></i></button>" +
                 //  "<button type=\"button\" class=\"btn btn-success btn-xs detail\" rel='tooltip' data-toggle='tooltip' data-placement='left' title='Detail'><i class='fa fa-list'></i></button>" +
                 //  "<button class='listreject btn btn-warning btn-xs' rel='tooltip' data-toggle='tooltip' data-placement='left' title='Detail Tolak'><i class='fa fa-times-circle-o'></i></button>" +
                 //  "<button class='print btn btn-primary btn-xs' rel='tooltip' data-toggle='tooltip' data-placement='left' title='Cetakan'><i class='fa fa-print'></i></button>" +
                 //  "<span class=\"sr-only\">Action</span>" +
                 //  "</div>"
                 // }
          ],
          "columnDefs": [],
          "order": [[ 0, 'asc' ]]//,
        	//fixedColumns :{leftColumns:1}
      	});

      	
      	//tooltip
      $('body').tooltip({
        selector: '[rel=tooltip]'
      });

	});
</script>

</body>
</html>

