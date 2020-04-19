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
			                <a href="{{ url('/inventory/inventory/stock/view_stock') }}">Inventory</a>
			            </li>
			            <li>
			                <a href="{{ url('/inventory/permintaan_barang/index') }}">Permintaan Barang : {{ is_null($permintaan) ? '' :$permintaan->no }}</a>
			            </li>
			            <li>
			            	<span>Barang Keluar</span>
			            </li>
			        </ul>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="col-md-12">
              	<strong>Data Barang Keluar</strong>
              	<hr/>
              	@if($permintaan!=null)
					@if($permintaan->status_persetujuan == 1)
						@include('form.a',
							[
								'href' => url('/inventory/barang_keluar/add_form').'?id='.$permintaan->id,
								'class'=>'pull-right',
								'caption' => 'Tambah '
							])
							
						@include('form.a',
								[
									'href' => url('/inventory/permintaan_barang/index'),
									'class'=>'btn-danger',
									'caption' => 'Kembali'
								])

					@else
						<div class="alert alert-info"><i class="fa fa-info"></i> Status permintaan barang belum disetujui, anda tidak bisa melakukan transaksi barang keluar.</div>
						@include('form.a',
							[
								'href' => url('/inventory/permintaan_barang/index'),
								'caption' => 'Kembali'
							])
					@endif
				@else
					<form class="form-inline" method="post" action="{{ url('/inventory/barang_keluar/printReport') }}" id="form_cetak">
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
				@endif

				<hr/>
              	@if($permintaan != null)
					<div class="panel panel-success">
						<div class="panel-heading">Permintaan Barang NO <strong>: {{ $permintaan->no}}</strong>
						</div>
						<div class="panel-body">
							
							<div class="col-lg-1 col-md-1 col-xs-1">
								<strong>PT.</strong>
								<br/>
								<strong>SPK</strong>
								<br/>
								<strong>Keterangan</strong>
							</div>
							<div class="col-lg-5 col-md-5 col-xs-5">
								<strong>: {{ $permintaan->pt->name }}</strong>
								<br/>
								<strong>: {{ $permintaan->spk->no or '-' }}</strong>
								<br/>
								<strong> : {{ $permintaan->description or '-' }}</strong>
							</div>

							<div class="col-lg-1 col-md-1 col-xs-1">
								<strong>Pengguna</strong>
								<br/>
								<strong>Tanggal</strong>
								<br/>
								<strong>Status</strong>
								
							</div>
							<div class="col-lg-5 col-md-5 col-xs-5">
								<strong>: {{ $permintaan->user->user_name or 'Kosong' }}</strong>
								<br/>
								<strong>: {{ date('d-m-Y',strtotime($permintaan->date)) }}</strong>
								<br/>
								<strong>: {{ $permintaan->StatusPermintaan->name  or '-' }}</strong>
							</div>

						</div>
					</div>
					@endif
					@include('inventory::barang_keluar.datatable')
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

  <div class="control-sidebar-bg"></div>
</div>
@include("master/footer_table")
@include('pluggins.alertify')
<script type="text/javascript" src="{{ URL::asset('assets/global/plugins/moment.min.js')}}"></script>
<script type="text/javascript" src="{{ URL::asset('assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js')}}"></script>
<script type="text/javascript" charset="utf-8">
	$(document).ready(function() {
		var token = $('input[name=_token]').val();
		$('#table_data').DataTable(
			{
				scrollY:        "300px",
		        scrollCollapse: true,
		        paging:         false,
		        "order": [[ 0, 'asc' ]]
			});

		var sBody = $('#table_data tbody');

		sBody.on('click','.btn-print',function(){
			var id = $(this).attr('data-value');
			$('#barang_keluar_id').val(id);
			$('#frmprint').submit();
		}).
		on('click','.delete-link',function()
		{
			var id = $(this).attr("id");
			var parent = $(this).parents("tr");
			$.confirm({
				title: 'Confirm Delete ?',
				icon: 'fa fa-warning',
				content: 'Are you sure delete?',
				autoClose: 'cancelAction|8000',
				buttons: {
					deleteUser: {
						text: 'Delete',
						btnClass: 'btn-red any-other-class',
						action: function () {
							$.post("{{ url('/inventory/barang_keluar/delete') }}", 
							{
								id:id,
								_token: token
							}, 
							function(data) {
								if(data)
								{
									parent[0].remove();
									alertify.success('success deleted!');
								}
							});	
							
						}
					},
					cancelAction: function () {
						
					}
				}
			});
			return false;
		});
		 $('#periode').daterangepicker({
            //startDate: moment().subtract('days', 29),
           // endDate: moment(),
       format: 'DD/MM/YYYY',
        dateLimit: { days: 60 },
        showDropdowns: true,
        showWeekNumbers: true,
        
        separator: ' to '
      }
        ,function(start,end)
        {
          $('#start_opname').val(start.format('YYYY-MM-DD'));
          $('#end_opname').val(end.format('YYYY-MM-DD'));
        });
	});
</script>

</body>
</html>


