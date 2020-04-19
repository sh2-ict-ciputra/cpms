@extends('layouts.master')
@section('css')

<link href="{{ URL::asset('assets/global/plugins/datatables/datatables.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('assets/global/plugins/datatables/fixedHeader.dataTables.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css')}}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="{{ URL::asset('assets/global/plugins/bootstrap-editable/bootstrap-editable/css/bootstrap-editable.css')}}" type="text/css"/>

@endsection
@section('content')
	<div class="panel panel-success">
 		<div class="panel-heading"><strong>{{ $BarangMasukHibah->no }}</strong></div>
	 	<div class="panel-body">
	 		 <a href="{{ url('/inventory/hod_inventory/barangmasuk_hibah/details',$BarangMasukHibah->id) }}" class="btn btn-success" type="button" id="btn_refresh"><i class="fa fa-mail-reply"></i> Back</a>
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
						<th class="text-center">Dari</th>
						<th class="text-center">Kepada</th>
						<th class="text-center">Dari</th>
						<th class="text-center">Kepada</th>
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

			<!-- history -->
			<div class="col-lg-12 col-md-12 col-xs-12">
				<div class="panel panel-success">
			 		<div class="panel-heading">Details </div>
				 	<div class="panel-body">
						<table class="table table-striped table-bordered table-hover table-responsive table-checkable order-column nowrap">
								<colgroup>
									<col>
									<col style="width: 155px;">
									<col>
									<col>
									<col>
									<col style="width: 100px;">
									<col style="width: 100px;">
									<col>
									<col style="width: 155px;">
									<col>
									<col>
									<col>
								</colgroup>
							<thead style="background: #3FD5C0;">
								<tr>
									<th class="text-center">#</th>
									<th class="text-center">Item Barang</th>
									<th class="text-center">Qty Acuan</th>
									<th class="text-center">Qty Realisasi</th>
									<th class="text-center">Qty Reject</th>
									<th class="text-center">Harga</th>
									<th class="text-center">Total</th>
									<th class="text-center">Satuan</th>
									<th class="text-center">Gudang</th>
									<th class="text-center">Description</th>
									<th class="text-center">Created_at</th>
									<th class="text-center">Updated_at</th>
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
										<td>{{ $value->items->name }}</td>
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
										<td>
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
											data-value="{{ $value->price}}">{{ number_format($value->price,0,',','.') }}</a></td>
										<td class="text-right">{{ number_format($value->price*$value->quantity,0,',','.') }}</td>

										<td>
											
											<a href="#" class="editable_details" 
											data-pk="{{ $value->id}}" 
											data-name="item_satuan_id" 
											data-url="{{url('/inventory/barangmasuk_hibah_details/update')}}" 
											data-original-title="Pilih Satuan"
											data-type="select" 
											data-value="{{ $value->item_satuan_id}}" 
											data-source="{{url('/barangmasuk_hibah_details/satuan_source')}}">
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
											data-source="{{url('/barangmasuk_hibah_details/warehouse_source')}}">
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
										<td colspan="12" class="text-center">Empty</td>
									</tr>

								@endif
							</tbody>
						</table>
					</div>
				</div>
			</div>
		
@endsection
@section('scripts')
<script type="text/javascript" src="{{ URL::asset('assets/global/plugins/datatables/datatables.min.js')}}"></script>
<script type="text/javascript" src="{{ URL::asset('assets/global/plugins/datatables/dataTables.fixedHeader.min.js')}}"></script>
<script src="{{ URL::asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js')}}" 
type="text/javascript">
</script>
<script type="text/javascript" src="{{ URL::asset('assets/global/plugins/bootstrap-editable/bootstrap-editable/js/bootstrap-editable.min.js')}}"></script>
<script type="text/javascript">
	$(document).ready(function()
	{
		$.ajaxSetup({
		    headers: {
		        'X-CSRF-Token': $('input[name=_token]').val()
		    }
		});
		$('.editable_header').editable({
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
@endsection