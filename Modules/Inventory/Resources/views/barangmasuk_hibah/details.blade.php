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
	                <a href="{{ url('/inventory/barangmasuk_hibah/index') }}">Barang Masuk</a>
	            </li>
	            <li>
	                <span>Details</span>
	            </li>
	        </ul>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="col-md-12">
              	<strong>Nomor Barang Masuk : {{ $BarangMasukHibah->no }} </strong>
				@include('form.a',
				[
					'href' => url('/inventory/barangmasuk_hibah/index'),
					'class'=>'pull-right',
					'caption' => 'Kembali'
				])
				<hr/>
				<div class="panel panel-success">
			 		<div class="panel-heading"><strong>{{ $BarangMasukHibah->no }}</strong></div>
				 	<div class="panel-body">
				 		<a href="{{ url('/inventory/barangmasuk_hibah/cetakBarangMasuk',$BarangMasukHibah->id) }}" class="btn btn-primary"><i class="fa fa-print"></i> Cetak</a>
				 		<p/>
						<table class="table table-striped table-bordered table-hover table-responsive table-checkable order-column nowrap">
							<thead style="background: #3FD5C0;">
								<tr>
									<th colspan="2" class="text-center">Project</th>
									<th colspan="2" class="text-center">PT </th>
									<th rowspan="2" class="text-center">Tanggal</th>
									<th rowspan="2" class="text-center">Penerima</th>
									<th rowspan="2" class="text-center">Description</th>
								</tr>
								<tr>
									<th>Dari</th>
									<th>Kepada</th>
									<th>Dari</th>
									<th>Kepada</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>
										<a href="#" class="editable_header" 
										data-pk="{{ $BarangMasukHibah->id}}" 
										data-name="from_project_id" 
										data-url="{{url('/inventory/barangmasuk_hibah/update')}}" 
										data-original-title="Pilih Project"
										data-type="select" 
										data-value="{{ $BarangMasukHibah->from_project_id}}" 
										data-source="{{url('/inventory/barangmasuk_hibah/project_source')}}">{{ $BarangMasukHibah->from_project->name }}</a></td>
									<td><a href="#" class="editable_header" 
										data-pk="{{ $BarangMasukHibah->id}}" 
										data-name="to_project_id" 
										data-url="{{url('/inventory/barangmasuk_hibah/update')}}" 
										data-original-title="Pilih Project"
										data-type="select" 
										data-value="{{ $BarangMasukHibah->to_project_id}}" 
										data-source="{{url('/inventory/barangmasuk_hibah/project_source')}}">{{ $BarangMasukHibah->to_project->name }}</a></td>
									<td><a href="#" class="editable_header" 
										data-pk="{{ $BarangMasukHibah->id}}" 
										data-name="from_pt_id" 
										data-url="{{url('/inventory/barangmasuk_hibah/update')}}" 
										data-original-title="Pilih PT"
										data-type="select" 
										data-value="{{ $BarangMasukHibah->from_pt_id }}" 
										data-source="{{url('/inventory/barangmasuk_hibah/pt_source')}}">{{ $BarangMasukHibah->from_pt->name }}</a></td>
									<td><a href="#" class="editable_header" 
										data-pk="{{ $BarangMasukHibah->id}}" 
										data-name="to_pt_id" 
										data-url="{{url('/inventory/barangmasuk_hibah/update')}}" 
										data-original-title="Pilih PT"
										data-type="select" 
										data-value="{{ $BarangMasukHibah->to_pt_id }}" 
										data-source="{{url('/inventory/barangmasuk_hibah/pt_source')}}">{{ $BarangMasukHibah->to_pt->name }}</a></td>
									<td>
										<a href="#" class="editable_header" 
										data-pk="{{ $BarangMasukHibah->id}}" 
										data-name="tanggal_hibah" 
										data-url="{{url('/inventory/barangmasuk_hibah/update')}}" 
										data-original-title="Pilih Tanggal"
										data-type="date" 
										data-value="{{ date('Y-m-d',strtotime($BarangMasukHibah->tanggal_hibah)) }}" >
											{{ date('d-m-Y',strtotime($BarangMasukHibah->tanggal_hibah))}}
										</a>
									</td>
									<td>
										{{ $BarangMasukHibah->user_recepient->user_name or '-' }}
									</td>
									<td><a href="#" class="editable_header" 
										data-pk="{{ $BarangMasukHibah->id}}" 
										data-name="description" 
										data-url="{{url('/inventory/barangmasuk_hibah/update')}}" 
										data-original-title="description"
										data-type="textarea" 
										data-value="{{ $BarangMasukHibah->description }}">{{ trim($BarangMasukHibah->description) }}</a>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>

				<div class="panel panel-success">
			 		<div class="panel-heading">Details </div>
				 	<div class="panel-body">
						<table class="table table-striped table-bordered table-hover table-responsive table-checkable order-column nowrap">
								<!-- <colgroup>
									<col style="width: 155px;">
									<col style="width: 10px;">
									<col style="width: 10px;">
									<col>
									<col>
									<col>
									<col>
								</colgroup> -->
							<thead style="background: #3FD5C0;">
								<tr>
									<th class="text-center">#</th>
									<th class="text-center">Item Barang</th>
									<th class="text-center">Qty Acuan</th>
									<th class="text-center">Qty Realisasi</th>
									<th class="text-center">Qty Reject</th>
									<th class="text-center">Harga(Rp.)</th>
									<th class="text-center">Total (Rp.)</th>
									<th class="text-center">Satuan</th>
									<th class="text-center">Gudang</th>
									<th class="text-center">Deskripsi</th>
									<th class="text-center">Ditambah</th>
									<th class="text-center">Diperbarui</th>
								</tr>
							</thead>
							<tbody>
								@if(count($detailsbarangmasuk) > 0)
								<?php 
									$nomor = count($detailsbarangmasuk)+1;
								?>
									@foreach($detailsbarangmasuk as $key => $value)
									<tr>
										<td>{{ $nomor-=1 }}</td>
										<td>{{ $value->items->item->name }}</td>
										<td class="text-right">
											<a href="#" class="editable_details" 
											data-pk="{{ $value->id}}" 
											data-name="quantity_acuan" 
											data-url="{{url('/inventory/barangmasuk_hibah_details/update')}}" 
											data-original-title="Quantity"
											data-type="text" 
											data-value="{{ $value->quantity_acuan}}">
												{{ $value->quantity_acuan }}
											</a>
										</td>
										<td class="text-right"><a href="#" class="editable_details" 
											data-pk="{{ $value->id}}" 
											data-name="quantity" 
											data-url="{{url('/inventory/barangmasuk_hibah_details/update')}}" 
											data-original-title="Quantity"
											data-type="text" 
											data-value="{{ $value->quantity}}">{{ is_null($value->quantity) ? 0 : $value->quantity }}</a></td>
										<td class="text-right">
											<a href="#" class="editable_details" 
											data-pk="{{ $value->id}}" 
											data-name="quantity_reject" 
											data-url="{{url('/inventory/barangmasuk_hibah_details/update')}}" 
											data-original-title="Quantity Reject"
											data-type="text" 
											data-value="{{ is_null($value->quantity_reject) ? 0 : $value->quantity_reject}}">{{ $value->quantity_reject or '0' }}</a>
										</td>
										<td class="text-right">
											<a href="#" class="editable_details" 
											data-pk="{{ $value->id}}" 
											data-name="price" 
											data-url="{{url('/inventory/barangmasuk_hibah_details/update')}}" 
											data-original-title="Price"
											data-type="text" 
											data-value="{{ $value->price}}">
												{{ number_format($value->price,0,',','.') }}
											</a>
										</td>
										<td class="text-right">
											{{ number_format($value->price*$value->quantity,0,',','.') }}
										</td>

										<td><a href="#" class="editable_details" 
											data-pk="{{ $value->id}}" 
											data-name="item_satuan_id" 
											data-url="{{url('/inventory/barangmasuk_hibah_details/update')}}" 
											data-original-title="Pilih Satuan"
											data-type="select" 
											data-value="{{ $value->item_satuan_id}}" 
											data-source="{{url('/inventory/barangmasuk_hibah_details/satuan_source')}}">
												{{ is_null($value->item_satuan) ? $value->item_satuan_id : $value->item_satuan->name }}
											</a>
										</td>
										<td>
											<a href="#" class="editable_details" 
											data-pk="{{ $value->id}}" 
											data-name="warehouse_id" 
											data-url="{{url('/inventory/barangmasuk_hibah_details/update')}}" 
											data-original-title="Pilih Warehouse"
											data-type="select" 
											data-value="{{ $value->warehouse_id}}" 
											data-source="{{url('/inventory/barangmasuk_hibah_details/warehouse_source')}}">
												{{ is_null($value->warehouse) ? $value->warehouse_id : $value->warehouse->name }}
											</a>
										</td>
										<td>
											<a href="#" class="editable_details" 
											data-pk="{{ $value->id}}" 
											data-name="description" 
											data-url="{{url('/inventory/barangmasuk_hibah_details/update')}}" 
											data-original-title="Description"
											data-type="textarea" 
											data-value="{{ $value->description}}">
											{{ $value->Description }}</a></td>
										<td>
											{{ date('d-m-Y H:m:s',strtotime($value->created_at))}}
										</td>
										<td>
											{{ date('d-m-Y H:m:s',strtotime($value->updated_at))}}
										</td>
									</tr>
									@endforeach
								@else
									<tr>
										<td colspan="12" style="text-align: center;">Empty</td>
									</tr>

								@endif
							</tbody>
						</table>
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
	  $.ajaxSetup({
    headers: {
                  'X-CSRF-Token': $('meta[name=csrf-token]').attr('content')
                }
    });
  $.fn.editable.defaults.mode = 'inline';
	$(document).ready(function()
	{
		$('.editable_header').editable({
				ajaxOptions: {
				    type: 'post',
				    dataType: 'json'
				},
				success:function(data)
				{
					if(data.return==1)
					{
						$('#div_content').load("{{ url()->full() }}");
					}
				}
			}
		);

		$('.editable_details').editable({
				ajaxOptions: {
				    type: 'post',
				    dataType: 'json'
				},
				success:function(data)
				{
					if(data.return==1)
					{
						$('#div_content').load("{{ url()->full() }}");
					}
				}
			}
		);
	});
</script>
</body>
</html>