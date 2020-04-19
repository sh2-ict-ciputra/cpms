<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Admin QS | Dashboard</title>
  @include("master/header")
  <link href="{{ URL::asset('assets/global/plugins/typeahead/typeahead.css') }}" rel="stylesheet" type="text/css" />
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
	                <a href="{{ url('/inventory/stock/view_stock') }}">Inventory</a>
	            </li>
	            <li>
	                <a href="{{ url('/inventory/pengembalian_barang/index') }}">Pengembalian Barang</a>
	            </li>
	            <li>
	                <span>Detail</span>
	            </li>
	        </ul>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="col-md-12">
              	<strong>Detail Pengembalian Barang </strong>
			@include('form.a',
					[
						'href' => url('/inventory/pengembalian_barang/index'),
						'class'=>'pull-right',
						'caption' => ' Kembali'
					])
		<hr />
		<ul class="nav nav-tabs">
	      <li role="presentation" class="active">
	        <a href="#tab_permintaan" data-toggle="tab">Permintaan Barang</a>
	      </li>
	      <li role="presentation">
	        <a href="#tab_barangkeluar" data-toggle="tab">Barang Keluar</a>
	      </li>
	    </ul>

	   <div class="tab-content">
      <div id="tab_permintaan" class="tab-pane fade in active">
		<div class="col-lg-12 col-md-12 col-xs-12">
		<div class="panel panel-success">
			<div class="panel-heading">Permintaan Barang</div>
			<div class="panel-body">
				<div class="col-lg-1 col-md-1 col-xs-6">
					<strong>Project</strong>
					<br/>
					<strong>Company</strong>
					<br/>
					<strong>Nomor</strong>
				</div>
				<div class="col-lg-3 col-md-3 col-xs-6">
					<strong>: {{ $barangkeluar->permintaanbarang->project->name }}</strong>
					<br/>
					<strong>: {{ $barangkeluar->permintaanbarang->pt->name }}</strong>
					<br/>
					<strong>: {{ $barangkeluar->permintaanbarang->no}}</strong>
				</div>

				<div class="col-lg-1 col-md-1 col-xs-6">
					<strong>SPK</strong>
					<br/>
					<strong>User</strong>
					<br/>
					<strong>Tanggal</strong>
				</div>
				<div class="col-lg-3 col-md-3 col-xs-6">
					<strong>: {{ $barangkeluar->permintaanbarang->spk->no or '-' }}</strong>
					<br/>
					<strong>: {{ $barangkeluar->permintaanbarang->user->user_name }}</strong>
					<br/>
					<strong>: {{ date('d-m-Y',strtotime($barangkeluar->permintaanbarang->date)) }}</strong>
				</div>
				<div class="col-lg-1 col-md-1 col-xs-6">
					<strong>Description</strong>
				</div>
				<div class="col-lg-3 col-md-3 col-xs-6">
					<strong> : {{ $barangkeluar->permintaanbarang->description }}</strong>
				</div>

			</div>
		</div>

	</div>
</div>

 <div id="tab_barangkeluar" class="tab-pane fade in">
	<div class="col-lg-12 col-md-12 col-xs-12">
		<div class="panel panel-success">
			<div class="panel-heading">Barang Keluar</div>
			<div class="panel-body">
				<div class="col-lg-3 col-md-3 col-xs-6">
					<strong>Nomor</strong>
					<br/>
					<strong>Confirmed By Warehouse</strong>
					<br/>
					<strong>Confirmed By Requester</strong>
				</div>
				<div class="col-lg-3 col-md-3 col-xs-6">
					<strong>: {{ $barangkeluar->no }}</strong>
					<br/>
					<strong>: {{ $barangkeluar->confirmed_by_warehouseman ? 'sudah' : 'belum' }}</strong>
					<br/>
					<strong>: {{ $barangkeluar->confirmed_by_requester ? 'sudah' : 'belum'}}</strong>
				</div>

				<div class="col-lg-3 col-md-3 col-xs-6">
					<strong>Tanggal</strong>
					<br/>
					<strong>Description</strong>
				</div>
				<div class="col-lg-3 col-md-3 col-xs-6">
					<strong>: {{ date('d-m-Y',strtotime($barangkeluar->date)) }}</strong>
					<br/>
					<strong>: {{ $barangkeluar->description or '-' }}</strong>
					
				</div>
			</div>
		</div>

	</div>
</div>
			<!-- history -->
	<div class="col-lg-12 col-md-12 col-xs-12">
		<div class="panel panel-success">
	 		<div class="panel-heading"><strong>Pengembalian barang</strong></div>
		 	<div class="panel-body">
				<table class="table table-striped table-bordered table-hover table-responsive table-checkable order-column nowrap">
					<colgroup>
	                  <col style="width: 400px;">
	                  <col style="width: 10px;">
	                  <col style="width: 30px;">
	                  <col style="width: 10px;">
	                  <col>
	                  <col>
                </colgroup>
					<thead style="background: #3FD5C0;">
						<tr>
							<th class="text-center">Item Barang</th>
							<th class="text-center">Qty Pinjam/Keluar</th>
							<th class="text-center">Qty Kembali</th>
							<th class="text-center">Qty Terpakai</th>
							<th class="text-center">Satuan</th>
							<th class="text-center">Tanggal</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$tempqty = 0;
						?>
						@for($count = 0;$count < count($lists); $count++)
							<tr>
							<td class="text-left">{{ $lists[$count]['item_name'] }}</td>
							<td class="text-right">{{ $lists[$count]['qty_pinjam']}}</td>
							<td class="text-right">
								<a href="#" class="editable_details" 
								data-pk="{{ $lists[$count]['id']}}" 
								data-name="quantity_kembali" 
								data-url="{{url('/inventory/pengembalian_barang/update')}}" 
								data-original-title="Jumlah dikembalikan"
								data-type="text" 
								data-value="{{ $lists[$count]['qty_kembali']}}">

									{{ $lists[$count]['qty_kembali']}}

								</a>
							</td>
							<td class="text-right">{{ $lists[$count]['qty_pinjam'] - ($lists[$count]['qty_kembali']+$tempqty)}}</td>
							<td>
								{{ $lists[$count]['item_satuan'] }}
							</td>
							<td>
								{{ $lists[$count]['date'] }}
							</td>
							<?php
								$tempqty += $lists[$count]['qty_kembali'];
							?>
						</tr>
						@endfor
					</tbody>
				</table>
			</div>
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
@include('pluggins.editable_plugin')
<script type="text/javascript">
	$(document).ready(function()
	{
		$.ajaxSetup({
		    headers: {
		        'X-CSRF-Token': $('input[name=_token]').val()
		    }
		});

		$('.editable_details').editable({
				ajaxOptions: {
				    type: 'post',
				    dataType: 'json'
				},
				success:function(data)
				{
					if(data.return==1)
					{
						
					}
				}
			}
		);
	});
</script>
</body>
</html>