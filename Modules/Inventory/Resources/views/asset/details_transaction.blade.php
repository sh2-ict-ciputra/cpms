<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Admin QS | Dashboard</title>
  @include("master/header")
  <!--link rel="stylesheet" type="text/css" href="//cdn.datatables.net/fixedcolumns/3.0.2/css/dataTables.fixedColumns.css"-->
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
			                <a href="{{ url('/inventory/stock/view_stock') }}">Asset</a>
			            </li>
			            <li>
			                <span>Detail Transaksi Asset</span>
			            </li>
			        </ul>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="col-md-12">
              	@include('form.a',[
								'href'=>url('/inventory/asset/details',$item_id), 
								'caption' => 'kembali' 
							])
				<hr/>
			<input type="hidden" name="asset_id" id="asset_id" value="{{ $asset_id }}" />
				<div class="panel panel-success">
			 		<div class="panel-heading"><strong>Rotasi {{ $item_name }}</strong></div>
				 	<div class="panel-body">
						<table class="table table-striped table-bordered table-hover table-responsive table-checkable order-column nowrap" id="detail_asset">
							<thead style="background: #3FD5C0;">
								<tr>
					              <th rowspan="2" class="text-center">Pemberi</th>
					              <th rowspan="2" class="text-center">Penerima</th>
					              <th colspan="2" class="text-center">Departmen</th>
					              <th colspan="2" class="text-center">Kepada Ruangan/Lokasi</th>
					              <th rowspan="2" class="text-center">Tanggal</th>
					            </tr>
					            <tr>
					            	<td>Dari</td><td>Tujuan</td><td>Dari</td><td>Tujuan</td>
					            </tr>
							</thead>
						</table>
					</div>
				</div>
			
		</div>
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
<script type="text/javascript">
	var gentable = null;
	$(document).ready(function()
	{
		$.ajaxSetup({
		    headers: {
		        'X-CSRF-Token': $('input[name=_token]').val()
		    }
		});

		var asset_id = $('#asset_id').val();
		gentable = $('#detail_asset').DataTable({
	        paging:false,
	        //fixedColumns: {leftColumns: 2,rightColumns: 1},	
	          processing: true,
	          ajax: "{{ url('/inventory/asset/getDetailTransactionPerItem/') }}"+"/"+asset_id,
	          columns:[
	          		 { data: 'pemberi',name: 'pemberi',"bSortable": true},
	                 { data: 'penerima',name: 'penerima',"className":"text-right","bSortable": false},
	                 { data: 'from_departement',name: 'from_departement',"className":"text-right","bSortable": false},
					 { data: 'to_department',name: 'to_department',"className":"text-right","bSortable": false},
	                 { data: 'from_room',name: 'from_room',"className":"text-right","bSortable": false},
	                 
					 { data: 'to_room',name: 'to_room',"className":"text-right","bSortable": false},
					 { data: 'date',name: 'date',"bSortable": false}	                
	          ],
	          "columnDefs": [],
	          "order": [[ 0, 'asc' ]],
		});

		$('#btn-back').click(function()
		{
			var id = $(this).attr('data-id');
			window.location.href = "{{ url('/inventory/asset/details/') }}"+"/"+id;
		});
	});
</script>
</body>
</html>