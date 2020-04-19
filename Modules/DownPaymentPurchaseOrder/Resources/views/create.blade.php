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
	                <a href="{{ url('/purchaseorder/') }}">Purchase Order</a>
	            </li>
	            
	            <li>
	                <span>Tambah Down Payment</span>
	            </li>
	        </ul>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="col-md-12">
              	@include('form.a',[
			'href'=> url('/downpaymentpurchaseorder/'), 
			'caption' => 'Kembali' 
		])
		<hr/>
             <form class="form-horizontal form-label-left">
				{{ csrf_field() }}
						<div class="item form-group">
							<label class="control-label col-md-4 col-sm-4 col-xs-12">Vendor
							</label>
							<div class="col-md-7 col-sm-7 col-xs-12">
								<select id="rekanan_id" name="rekanan_id" class="form-control">
									<option value="">Pilih</option>
									@foreach($result_rekanans as $key => $value)
										<option value="{{ $value->rekanan_id }}">{{ $value->vendor->name }}</option>
									@endforeach
								</select>
							</div>
						</div>
				</form>

		<table id="datatable-po" class="table table-striped table-bordered table-hover table-responsive table-checkable order-column nowrap">
			<thead>
				<tr>
					<th>#</th>
					<th>Nomor PO</th>
					<th>Deskripsi</th>
					<th>Total Nilai(Rp.)</th>
					<th>Total DP(Rp.)</th>
					<th>Sisa Pembayaran(Rp.)</th>
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
<div class="modal fade " id="modal_pic" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content ">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Tambah DownPayment Baru</h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal" id="form-add-dp" method="post" action="{{ url('/downpaymentpurchaseorder/store') }}" autocomplete="off">
        	{{ csrf_field() }}
        	<div class="form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label">Nomor PO</label>
			    <div class="col-sm-10">
			    	<input type="hidden" name="po_id" id="po-id" />
			    	
			     	<input type="text" class="form-control" readonly="true" id="nomor_po">
			    </div>
			  </div>

        	  <div class="form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label">Total Nilai PO (Rp.)</label>
			    <div class="col-sm-10">
			     	<input type="text" class="form-control" readonly="true" id="total_po_value">
			    </div>
			  </div>

              

			  <div class="form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label">DP Baru (%)</label>
			    <div class="col-sm-10">
			    	<div class="input-group">
			     		<input type="number" class="form-control" id="percentage_dp" name="percentage_dp">
			     		<div class="input-group-addon">
			     			Rp.
			     		</div>
			     		<input type="hidden" name="mnew_dp_value" id="mnew_dp_value" />
			     		<input type="text" class="form-control" id="new_dp_value" name="new_dp_value">
			     	</div>
			    </div>
			  </div>

			  <div class="form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label">Description</label>
			    <div class="col-sm-10">
			     	<textarea name="description" class="form-control" id="description"></textarea>
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
	var gentable_list_po = null;

	var _initTableListPO = function () {
		var _renderEdit = function (data, type, row) {
				if (type == 'display') {
					return "<button type='button' class='button-adddp btn btn-primary btn-xs'><i class='fa fa-plus'></i> DP</button>";
				}
				return data;
			};

		var arrColumns = [
				{ 'data': 'id'}, //
				{ 'data': 'po_number'},
				{ 'data': 'description'},
				{'data':'total_po','sClass':'text-right'},
				{'data':'total_dp','sClass':'text-right'},
				{'data':'saldo','sClass':'text-right'},
				{'data':'hidden_id','mRender': _renderEdit}
			];

		_GeneralTable(arrColumns);

		gentable_list_po = $('#datatable-po').DataTable(datatableDefaultOptions)
		.on('click', '.button-adddp',function (d){
			$('#form-add-dp').trigger('reset');
			fnSetAutoNumeric('#total_po_value');
			var data = gentable_list_po.row($(this).parents('tr')).data();
			$('#nomor_po').val(data.po_number);
			$('#po-id').val(data.id);
			$('#total_po_value').val(data.total_po);
			fnSetMoney('#total_po_value',data.total_po);
			$('#modal_pic').modal('show');
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

 	_initTableListPO();
 	fnSetAutoNumeric('#new_dp_value');
 	$('select').select2();
 	

 	$('#rekanan_id').change(function()
 	{
 		var id_vendor = $(this).val();

 		if(id_vendor != '' || id_vendor != undefined)
 		{
 			var _url = "{{ url('/downpaymentpurchaseorder/getVendorListsPO') }}";
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
 						gentable_list_po.clear();
 						for(var count = 0; count < data.data.length;count++)
 						{
 							var tableItem = {
	 							id:data.data[count].id,
	 							po_number:data.data[count].po_number,
	 							total_dp:data.data[count].total_dp,
	 							total_po:data.data[count].total_po,
	 							saldo:data.data[count].total_po-data.data[count].total_dp,
	 							description:data.data[count].description,
	 							hidden_id:data.data[count].total_po,

 							};

 							gentable_list_po.row.add(tableItem);
 						}
 						gentable_list_po.draw();
 					}
 					else
 					{
 						alertify.error('Data Tidak Ditemukan');
 						gentable_list_po.clear().draw();
 					}
 				},
 				complete:function()
 				{
 					waitingDialog.hide();
 				}
 			});
 		}
 	});

 	$('#percentage_dp').on('input',function()
 	{
 		var dp_percentage = $(this).val();
 		if(dp_percentage != '' || dp_percentage != undefined)
 		{
 			dp_percentage = parseFloat(dp_percentage)/100;
	 		var po_value = parseFloat($('#total_po_value').autoNumeric('get'));

	 		var dp_value = dp_percentage*po_value;

	 		$('#new_dp_value').val(dp_value);
	 		fnSetMoney('#new_dp_value',dp_value);
 		}
 		
 	});

 	$('#new_dp_value').keyup(function()
 	{
 		var new_dp_value = $(this).autoNumeric('get');
 		if((new_dp_value !='' || new_dp_value != undefined))
 		{
 			new_dp_value = parseFloat(new_dp_value*100);
 			var po_value = parseFloat($('#total_po_value').autoNumeric('get'));

 			var dp_percentage_get = new_dp_value/po_value;
 			if(dp_percentage_get < 1)
 			{
 				dp_percentage_get = parseFloat(dp_percentage_get.toFixed(4)*100);
 				$('#percentage_dp').val(dp_percentage_get);
 			}
 			else
 			{

 			}
 			
 		}
 	});

 	$('#form-add-dp').submit(function(e)
 	{
 		e.preventDefault();
 		var new_dp = $('#new_dp_value').autoNumeric('get');
 		$('#mnew_dp_value').val(new_dp);

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
 					$('#rekanan_id').trigger('change');
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

 	var tbody = $('#datatable-po tbody');
 	gentable_list_po.on('draw',function(){
 		tbody.find('.text-right').each(function(i,v)
 		{
 			fnSetAutoNumeric($(this));
 			fnSetMoney($(this),$(this).text());
 			
 		});
 	});
 	
 	if($('#rekanan_id').val() != '')
 	{
 		$('#rekanan_id').trigger('change');
 	}


  });
</script>
</body>
</html>