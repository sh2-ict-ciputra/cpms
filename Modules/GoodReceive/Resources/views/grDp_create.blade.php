<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Admin QS | Dashboard</title>
  @include("master/header")
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
	                <a href="{{ url('/goodreceive/gr_dp') }}">Good Receipt DP</a>
	            </li>
	            
	            <li>
	                <span>Tambah</span>
	            </li>
	        </ul>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="col-md-12">
              	@include('form.a',['href'=> url('/goodreceive/gr_dp'), 'caption' => 'Kembali' ])
		<hr/>
             <form action="#" method="post" class="form-horizontal form-label-left" id="form_data">
				{{ csrf_field() }}
						<div class="item form-group">
							<label class="control-label col-md-4 col-sm-4 col-xs-12">Vendor
							</label>
							<div class="col-md-7 col-sm-7 col-xs-12">
								<select id="rekanan_id" name="rekanan_id" class="form-control">
									<option>Pilih</option>
									@foreach($result_rekanans as $key => $value)
										<option value="{{ $value['id'] }}">{{ $value['name'] }}</option>
									@endforeach
								</select>
							</div>
						</div>
				</form>

		<table id="datatable-po" class="table table-striped table-bordered table-hover table-responsive table-checkable order-column nowrap">
			<thead style="background-color: #3FD5C0;">
				<tr>
					<th>#</th>
					<th>Nomor PO</th>
					<th>Deskripsi</th>
          <th></th>
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

  <!-- Modal -->
<div class="modal fade " id="modal_pic" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="overflow: scroll;">
  <div class="modal-dialog modal-lg" role="document" >
    <div class="modal-content " >
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Buat Good Receipt</h4>
      </div>
      <div class="modal-body">
      	<div class="form-horizontal">
      		

      		<div class="form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label">Nomor PO</label>
			    <div class="col-sm-10">
			     	<input type="text" class="form-control" readonly="true" id="nomor_po">
			    </div>
		  	</div>
      	</div>
      	
      	<table id="datatable-details" class="table table-striped table-bordered table-hover table-responsive table-checkable order-column nowrap">
			<thead style="background-color: #3FD5C0;">
				<tr>
					<th>#</th>
					<th>Item</th>
					<th>Harga(Rp.)</th>
					<th>Qty</th>
					<th>Total(Rp.)</th>
					<th>Satuan</th>
          <th hidden></th>
				</tr>
			</thead>
		</table>

        <form class="form-horizontal" id="form-add-dp" method="post" action="{{ url('/goodreceive/gr_dp/store') }}" autocomplete="off">
        	{{ csrf_field() }}
        	<input type="hidden" name="po_id" id="po_id" />
        	<input type="hidden" name="dp_id" id="dp_id" />
        	<!-- <div class="form-group">
			      <label for="inputEmail3" class="col-sm-2 control-label">Total Nilai PO (Rp.)</label>
    			    <div class="col-sm-10">
    			     	<input type="text" class="form-control text-right" readonly="true" id="total_po_value" readonly="true">
    			    </div>
			    </div> -->
          <div class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">Sub Total (Rp.)</label>
              <div class="col-sm-10">
                <input type="text" class="form-control text-right" readonly="true" id="sub_total" readonly="true">
              </div>
          </div>
         <!--  <div class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">Total Diskon (Rp.)</label>
              <div class="col-sm-10">
                <input type="text" class="form-control text-right" readonly="true" id="total_diskon" readonly="true">
              </div>
          </div>
          <div class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">SubTotal dikurangi diskon (Rp.)</label>
              <div class="col-sm-10">
                <input type="text" class="form-control text-right" readonly="true" id="sub_total_kurangi_diskon" readonly="true">
              </div>
          </div> -->
          <div class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">Total PPN (Rp.)</label>
              <div class="col-sm-10">
                <input type="text" class="form-control text-right" readonly="true" id="total_ppn" readonly="true">
              </div>
          </div>
          <div class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">Total PPH (Rp.)</label>
              <div class="col-sm-10">
                <input type="text" class="form-control text-right" readonly="true" id="total_pph" readonly="true">
              </div>
          </div>
          <div class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">Grand Total (Rp.)</label>
              <div class="col-sm-10">
                <input type="text" class="form-control text-right" readonly="true" id="grand_total" readonly="true">
              </div>
          </div>
  			  <div class="form-group">
  			    <label for="inputEmail3" class="col-sm-2 control-label">DP</label>
  			    <div class="col-sm-10">
  			    	<div class="input-group">
  			    		<div class="input-group-addon">
  			    			%
  			    		</div>
  			     		<input type="text" value="0" class="form-control text-right" id="percentage_dp" name="percentage_dp" readonly="true">
  			     		<div class="input-group-addon">
  			     			Rp.
  			     		</div>
  			     		<input type="text" class="form-control text-right" id="new_dp_value" name="new_dp_value" readonly="true">
  			     	</div>
  			    </div>
  			  </div>
			    <div class="form-group">
			      <label for="inputEmail3" class="col-sm-2 control-label">Sisa</label>
    			    <div class="col-sm-10">
    			     	<input type="text" class="form-control text-right" readonly="true" id="sisa_bayar" readonly="true">
    			    </div>
			    </div>
  			  <div class="form-group">
  			    <div class="col-sm-offset-2 col-sm-10">
  			      <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Tambah</button>
  			    </div>
  			  </div>
        </form>

        

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" data-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>
@include("master/footer_table")
@include('pluggins.select2_pluggin')
@include('pluggins.alertify')
@include('form.datatable_helper')
@include('form.general_form')
<script type="text/javascript">
	$.ajaxSetup({
    	headers: {
                  'X-CSRF-Token': $('meta[name=csrf-token]').attr('content')
                }
    });
	var gentable = null;
	var gentable_list_po = null;

	var _initTableListPO = function () {
		var _renderEdit = function (data, type, row) {
				if (type == 'display') {
					return "<button type='button' class='button-addgr btn btn-primary btn-xs'><i class='fa fa-plus'></i> GR</button>";
				}
				return data;
			};

		var arrColumns = [
				{ 'data': 'id'}, //
				{ 'data': 'po_number'},
				{ 'data': 'description'}, 
				{'data':'total_dp_value','mRender': _renderEdit}
			];

		_GeneralTable(arrColumns);

		gentable_list_po = $('#datatable-po').DataTable(datatableDefaultOptions)
		.on('click', '.button-addgr',function (d){
			var data = gentable_list_po.row($(this).parents('tr')).data();
			var po_id = data.id;
			var total_nilai_dp = data.total_dp_value;
			$('#po_id').val(po_id);
			$('#nomor_po').val(data.po_number);
			$('#new_dp_value').val(total_nilai_dp);
			fnSetMoney('#new_dp_value',total_nilai_dp);
			//cek sudah DP atau belum 
			var _url = "{{ url('/goodreceive/gr_dp/getItemPO') }}";
			var _data = {po_id:po_id};
			$.ajax(
			{
				type:'post',
				dataType:'json',
				url:_url,
				data:_data,
				beforeSend:function()
				{
					waitingDialog.show();
				},
				success:function(data)
				{
					$('#total_po_value').val(data.total_po_value);
          $('#total_diskon').val(data.total_diskon);
          $('#sub_total').val(data.subtotal);
          $('#sub_total_kurangi_diskon').val(data.subtotal-data.total_diskon);
          $('#total_ppn').val(data.totalppn);
          $('#total_pph').val(data.totalpph);
          $('#grand_total').val(data.grandtotal);
					$('#percentage_dp').val(data.total_dp);

					fnSetMoney('#percentage_dp',data.total_dp);
					fnSetMoney('#total_po_value',data.total_po_value);
          fnSetMoney('#total_diskon',data.total_diskon);
          fnSetMoney('#sub_total',data.subtotal);
          fnSetMoney('#sub_total_kurangi_diskon',data.subtotal-data.total_diskon);
          fnSetMoney('#total_ppn',data.totalppn);
          fnSetMoney('#total_pph',data.totalpph);
          fnSetMoney('#grand_total',data.grandtotal);
					$('#sisa_bayar').val(data.grandtotal-total_nilai_dp);
					fnSetMoney('#sisa_bayar',data.grandtotal-total_nilai_dp);
					$('#dp_id').val(JSON.stringify(data.dp_id));
					if(data.items.length > 0)
					{
						gentable.clear();
						for(var count=0;count<data.items.length;count++)
						{
							var tableItem = {
	 							id:data.items[count].id,
	 							item:data.items[count].item_name,
	 							price:data.items[count].price,
	 							quantity:data.items[count].qty,
	 							total:data.items[count].qty*data.items[count].price,
	 							satuan:data.items[count].satuan_name,
	 							item_satuan_id:data.items[count].satuan_id,

 							};

 							gentable.row.add(tableItem);
						}
						gentable.draw();
						$('#modal_pic').modal('show');
					}
				},
				complete:function()
				{
					waitingDialog.hide();
				}
			});
		});
}


	var _initTablePenerimaan = function () {
		var _renderEdit = function (data, type, row) {
				if (type == 'display') {
					return "<button type='button' class='button-delete btn btn-danger btn-xs'><i class='fa fa-trash-o'></i></button>";
				}
				return data;
			};

		var arrColumns = [
				{ 'data': 'id'}, //
				{ 'data': 'item'},
				{'data': 'price', 'sClass': 'text-right' },
				{'data': 'quantity', 'sClass': 'text-right' },
				{'data': 'total', 'sClass': 'text-right'},
				{ 'data': 'satuan' },
				{ 'data': 'item_satuan_id', 'mRender': _renderEdit, 'sClass': 'hidden'}
			];

		_GeneralTable(arrColumns);

		gentable = $('#datatable-details').DataTable(datatableDefaultOptions)
		.on('click', '.button-delete',function (d){
			if (confirm('Hapus item ini ?') == false) {
				return;
			}
			var tr = $(this).closest('tr');
			var row = gentable.row(tr);
			row.remove().draw();
		}).
		on('click','.button-add-warehouse',function()
		{
			var Tparent = $(this).parents('tr');
			var data = gentable.row($(this).parents('tr')).data();
			var priceCurrent = Tparent.find('#price').val();
			data.price = '<input class="text-right pricing"  id="price" name="price" type="text" value="'+priceCurrent+'"><input type="hidden" name="mprice" class="mpricing" />';
			data.price
			gentable.row.add(data).draw();
		});
}

var _GeneralTable = function (arrColumns) {
	var _coldefs = [
				{
					"targets":[],
					"visible": false,
					"searchable": false
				}
			];
	var fixedColumn = {
		leftColumns: 1
	}
	datatableDefaultOptions.searching = true;
	datatableDefaultOptions.aoColumns = arrColumns;
	datatableDefaultOptions.columnDefs = _coldefs;
	datatableDefaultOptions.autoWidth = false;
	datatableDefaultOptions.ordering = false;
	//datatableDefaultOptions.scrollY = "300px";
	//datatableDefaultOptions.scrollX = true;
	//datatableDefaultOptions.scrollCollapse = true;
	//datatableDefaultOptions.fixedColumns = fixedColumn;
	datatableDefaultOptions.fnDrawCallback = function (oSettings) {
		//show row number
		for (var i = 0, iLen = oSettings.aiDisplay.length; i < iLen; i++) {
			$('td:eq(0)', oSettings.aoData[oSettings.aiDisplay[i]].nTr).html((i + 1) + '.');
		}
	};
}

 $(document).ready(function(){

 	_initTablePenerimaan();
 	_initTableListPO();
 	$('select').select2();

 	fnSetAutoNumeric('.text-right');

 	
 	$('#rekanan_id').change(function()
 	{
 		var id_vendor = $(this).val();

 		if(id_vendor != '' || id_vendor != undefined)
 		{
 			var _url = "{{ url('/goodreceive/gr_dp/getVendorListsPO') }}";
 			var _data = {id:id_vendor};
 			$.ajax(
 			{
 				type:'post',
 				dataType:'json',
 				url:_url,
 				data:_data,
 				beforeSend:function()
 				{
 					waitingDialog.show();
 				},
 				success:function(data)
 				{
 					
 					if(data.data.length > 0)
 					{
 						alertify.success('Data Ditemukan');
 						for(var count = 0; count < data.data.length;count++)
 						{
 							var tableItem = {
	 							id:data.data[count].id,
	 							po_number:data.data[count].po_number,
	 							description:data.data[count].description,
	 							total_dp_value:data.data[count].total_dp_value
 							};

 							gentable_list_po.row.add(tableItem);
 						}
 						gentable_list_po.draw();
 					}
 					else
 					{
 						alertify.error('Data Tidak Ditemukan');
 						gentable.clear().draw();
 					}
 				},
 				complete:function()
 				{
 					waitingDialog.hide();
 				}
 			});
 		}
 	});

 	var tbody = $('#datatable-details tbody');
 	gentable.on('draw',function(){
 		tbody.find('.text-right').each(function(i,v)
 		{
 			fnSetAutoNumeric($(this));
 			fnSetMoney($(this),$(this).text());
 			
 		});
 	});

 	$('#form-add-dp').submit(function(e)
 	{
 		e.preventDefault();

 		var _data = $(this).serialize();
 		var _url = $(this).attr('action');

 		$.ajax(
 		{
 			type:'post',
 			url:_url,
 			data:_data,
 			dataType:'json',
 			beforeSend:function()
 			{
 				waitingDialog.show();
 			},
 			success:function(data)
 			{

 				if(data){
 					alertify.success('sukses berhasil disimpan');
 					$('#form-add-dp').trigger('reset');
 				}
 				else
 				{
 					alertify.error('gagal, data tidak berhasil disimpan');
 				}
 			},
 			complete:function()
 			{
 				waitingDialog.hide();
 			}
 		});
 	});
 	


  });
</script>
</body>
</html>