@if($request->id)
	<input type="hidden" name="item_id" id="item_id" value="{{ $items->item->id }}">
	<select name="project_idcheck" id="project_idcheck" class="form-control">
		<option value="">Check Harga Project Lain</option>
		@foreach($projects as $key=>$value)
			<option value="{{ $value->id }}">{{ $value->name }}</option>
		@endforeach
	</select>
	<br/>
	<table class="table table-striped table-bordered table-hover table-responsive table-checkable order-column table_master" id="table_compare">
		{{ csrf_field() }}
		<thead style="background-color: #3fd5c0;">
			<tr>
				<th>Harga(Rp.)</th>
				<th>PPN</th>
				<th>Satuan</th>
				<th>Tanggal</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td colspan="4">Empty</td>
			</tr>
		</tbody>
	</table>

@endif
{{ csrf_field() }}
<table class="table table-striped table-bordered table-hover table-responsive table-checkable order-column" id="table_data">
	
	<thead style="background-color: #3fd5c0;">
		<tr>
			<th>Item</th>
			<th>Harga(Rp.)</th>
			<th>PPN</th>
			<th>Satuan</th>
			<th>Tanggal</th>
			<!--th>Mata Uang</th>
			<th>Kurs</th>
			<th>Description</th-->
			@if($request->id)
				<th></th>
			@endif
		</tr>
	</thead>
	<tbody>
	@foreach($items_prices as $key => $each)
		<tr>
			<td>{{ is_null($each->item) ? 'Kosong' : $each->item->item->name }}</td>
			<td class="text-right">{{ number_format($each->price,2,".",",") }}</td>
			<td >{{ $each->ppn ? 'Ada' : 'Tidak'}}</td>
			<td >{{ is_null($each->satuan) ? '-' : $each->satuan->name }}</td>
			<td>{{ date('d-m-Y',strtotime($each->date_price)) }}</td>
			@if($request->id)
				<td align="center">
					<button id="{{ $each->id }}" href="#" class="btn btn-xs btn-primary edit-link" rel='tooltip' data-toggle='tooltip' data-placement='left' title='Edit'> 
						<i class="fa fa-edit"></i>
					</button>
					<button id="{{ $each->id }}" href="#" class="btn btn-xs btn-danger delete-link" rel='tooltip' data-toggle='tooltip' data-placement='right' title='Hapus'> 
						<i class="fa fa-trash"></i>
					</button>
				</td>
			@endif
		</tr>
	@endforeach
	</tbody>
</table>

