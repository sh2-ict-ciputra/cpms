<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Admin QS | Dashboard</title>
  @include("master/header")
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css" />
  <style type="text/css">
    .bootstrap-tagsinput .tag [data-role="remove"]:after{
        content: unset;
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
      <h1>{{ $project->name }}</h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
    			  <ul class="breadcrumb">
    	            <li>
    	                <a href="{{ url('/goodreceive/gr_penerimaan_barang') }}">Good Receipt Penerimaan Barang</a>
    	            </li>
    	            <li>
    	                <span>Tambah</span>
    	            </li>
    	       </ul>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="col-md-12">
              	@include('form.a',['href'=> url('/goodreceive/gr_penerimaan_barang'), 'caption' => 'Kembali'])
            		<div id="clone_input" style="display: none;">
            			<a href="#" class="editable_price" data-name="price" data-original-title="Harga" data-type="text"></a>
            		</div>
                <form action="{{ url('/goodreceive/store') }}" method="post" class="form-horizontal form-label-left" id="form_data">
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
      						<div class="form-group">
      						  <div class="">
      						    <button type="button" data-toggle="modal" data-target="#modal_pic" class="btn btn-primary"><i class="fa fa-list"></i> Tampil GR</button>
      						  </div>
      						</div>
				        </form>
            		<table id="datatable-po" class="table table-striped table-bordered table-hover table-responsive table-checkable order-column nowrap table_master">
            			<thead>
            				<tr>
            					<th>#</th>
            					<th>Nomor PO</th>
            					<th>Nomor Penerimaan Barang</th>
            					<th>
            						<div class="checkbox">
            						    <label>
            						      <input type="checkbox" class="checkall"> Check Semua
            						    </label>
            						  </div>
            					</th>
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
  		  	<div class="form-group">
  			    <label for="inputEmail3" class="col-sm-2 control-label">Nomor Penerimaan</label>
  			    <div class="col-sm-10">
  			    	<textarea class="form-control" name="nomor_penerimaan" id="nomor_penerimaan"></textarea>
  			     	<!-- <input type="text" > -->
  			    </div>
  		  	</div>
      	</div>     	
      	<table id="datatable-details" class="table table-striped table-bordered table-hover table-responsive table-checkable order-column nowrap table_master">
    			<thead>
    				<tr>
    					<th>#</th>
    					<th>Nomor Penerimaan</th>
    					<th>Kode</th>
    					<th>Item</th>
    					<th>Harga(Rp.)</th>
    					<th>Qty</th>
    					<th>Satuan</th>
    					<th>Total(Rp.)</th>
    					<th></th>
    				</tr>
    			</thead>
    		</table>

        <form class="form-horizontal" id="form-add-dp" method="post" action="{{ url('/goodreceive/gr_penerimaan_barang/store') }}" autocomplete="off">
        	{{ csrf_field() }}
        	<input type="hidden" name="id_po" id="id_po" />
        	<input type="hidden" name="id_penerimaan_barang" id="id_penerimaan_barang" />
			<input type="hidden" name="alldatasend" id="_alldatasend" />
			<div class="form-group">
				<label for="inputEmail3" class="col-sm-2 control-label">Total Nilai GR Sebelum DP(Rp.)</label>
				<div class="col-sm-10">
					 <input type="text" class="form-control text-right" readonly="true" id="total_gr" readonly="true">
				</div>
		  	</div>
        	<div class="form-group">
				<label for="inputEmail3" class="col-sm-2 control-label">Total DP (Rp.)</label>
				<div class="col-sm-2">
					<input type="text" class="form-control text-right" readonly="true" id="persen_dp" readonly="true">
			   </div>
  			    <div class="col-sm-8">
  			     	<input type="text" class="form-control text-right" readonly="true" id="total_dp" readonly="true">
  			    </div>
			</div>
          <div class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">Total Nilai GR Setelah DP(Rp.)</label>
            <div class="col-sm-10">
              <input type="text" class="form-control text-right" readonly="true" id="total_gr_setelah_dp" readonly="true">
            </div>
          </div>
  			  <div class="form-group">
  			    <div class="col-sm-offset-2 col-sm-10">
  			      <button type="submit" name="submit" class="btn btn-primary" id="btn-save"><i class="fa fa-save"></i> Submit</button>
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
@include('pluggins.editable_plugin')
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js"></script>
<script type="text/javascript">
  var arr_penerimaan_barang_id = [];
	$.fn.editable.defaults.mode = 'inline';
	$.ajaxSetup({
    	headers: {
                  'X-CSRF-Token': $('meta[name=csrf-token]').attr('content')
                }
    });
	var gentable_list_po = null;
	var gentable = null;
	var total_gr = 0;
  var total_gr2 = 0;
  var total_ppn = 0;
  var total_pph = 0;
	var _initTableListPO = function () {
		var _renderEdit = function (data, type, row) {
				if (type == 'display') {
					return '<div class="checkbox"><label><input type="checkbox" class="button-addgr"> </label></div>';
				}
				return data;
			};

		var arrColumns = [
				{ 'data': 'id'}, //
				{'data':'nopo'},
				{ 'data': 'po_number'},
				{'data':'rekanan_id','mRender': _renderEdit}
			];

		_GeneralTable(arrColumns,1,3);

		gentable_list_po = $('#datatable-po').DataTable(datatableDefaultOptions)
		.on('click', '.button-addgr',function (d){
			var obj = $(this);
			var data = gentable_list_po.row($(this).parents('tr')).data();
			var penerimaanbarang_id = data.id;
			var nomor_penerimaan = data.po_number;
			if($(this).is(':checked'))
			{
				$('#nomor_penerimaan').tagsinput('add', { id: penerimaanbarang_id, label: nomor_penerimaan });
        arr_penerimaan_barang_id.push(penerimaanbarang_id);
				//cek sudah DP atau belum 
				var _url = "{{ url('/goodreceive/gr_penerimaan_barang/getListItemPenerimaan') }}";
				var _data = {penerimaanbarang_id:penerimaanbarang_id};
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
						
						$('#nomor_po').val(data.nomor_po);
						$('#id_po').val(data.id_po);
						// $('#total_dp').val(data.nilai_dp);
						
						if(data.items.length > 0)
						{
							for(var count=0;count<data.items.length;count++)
							{
								var tableobj = {
		 							id:data.items[count].id_penerimaan_detail,
		 							nomor_barang_terima:nomor_penerimaan,
		 							kode_barang:data.items[count].kode_barang+'<input type="hidden" name="id_penerimaan" id="id_penerimaan" value="'+penerimaanbarang_id+'" />'+'<input type="hidden" name="id_terima" id="id_terima" value="'+data.items[count].id_penerimaan_detail+'" />',
		 							item:data.items[count].item_name,
		 							price:'<input type="text" name="input_price" id="input_price" class="text-right pricing" value="'+data.items[count].total_harga_satuan+'" /><input type="hidden" name="id_item" id="id_item" value="'+data.items[count].item_id+'" />',//data.items[count].total_harga_satuan,
		 							quantity:'<input type="number" class="text-right input_qty" min="1" step="1" name="total_qty" id="total_qty" value="'+data.items[count].total_qty+'" max="'+data.items[count].total_qty+'" />',
		 							total:data.items[count].total_qty*data.items[count].total_harga_satuan,
		 							satuan:data.items[count].satuan_name+'<input type="hidden" name="satuan_id" id="satuan_id" value="'+data.items[count].satuan_id+'" /> ',
		 							penerimaanbarang_id:penerimaanbarang_id,
	 							};
	 							gentable.row.add(tableobj);
	 							total_gr = data.items[count].total_qty*data.items[count].total_harga_satuan;
                total_ppn = data.items[count].ppn/100*total_gr;
                total_pph = data.items[count].pph/100*total_gr;

                total_gr2 += total_gr-total_pph+total_ppn;
                total_dp = data.nilai_dp*total_gr2;
							}
              

              // console.log(total_gr2);
			  $('#persen_dp').val(data.nilai_dp+'%');
              $('#total_dp').val(total_dp);
              fnSetMoney('#total_dp',total_dp);
							$('#total_gr').val(total_gr2);
              fnSetMoney('#total_gr',total_gr2);
              $('#total_gr_setelah_dp').val(total_gr2-total_dp);
              fnSetMoney('#total_gr_setelah_dp',total_gr2-total_dp);
							gentable.draw();
						}

            // 
						else
						{
							obj.prop('checked',false);
							alertify.error('GR telah dibentuk dengan nomor : '+nomor_penerimaan);
						}
					},
					complete:function()
					{
						waitingDialog.hide();
					}
				});
        $('#id_penerimaan_barang').val(arr_penerimaan_barang_id);

			}
			else
			{
        // arr_penerimaan_barang_id.push(penerimaanbarang_id);
        if(arr_penerimaan_barang_id.includes(penerimaanbarang_id) == true)
          {
            var index = arr_penerimaan_barang_id.indexOf(penerimaanbarang_id);
            arr_penerimaan_barang_id.splice(index,1);
          }

				var gentable_data = gentable.data();
				$(gentable_data).each(function(i,v)
				{
					if(v.penerimaanbarang_id = data.id)
					{

						// total_gr -= v.price;
						
						// $('#total_gr').val(total_gr);
						// fnSetMoney('#total_gr',total_gr);
						gentable.row(i).remove().draw();
					}
				});
			}
			 $('#id_penerimaan_barang').val(arr_penerimaan_barang_id);
		});


    
}

	var _initTablePenerimaan = function () {
		var _renderHarga = function(data,type,row)
		{
			return "<input type='text' name='input_price' id='input_price' value='"+data+"' />";
				/*return '<a href="#" class="editable_price" data-pk="'+row.id+'" data-name="price" data-original-title="Harga" data-type="text" data-value="'+data+'">'+data+'</a>';*/
		}
		var _render = function (data, type, row) {
				if (type == 'display') {
					return "<button type='button' class='button-delete btn btn-danger btn-xs'><i class='fa fa-trash-o'></i></button>";
				}
				return data;
			};
		var arrColumns = [
				{ 'data': 'id'},
				{'data':'nomor_barang_terima'},
				{'data':'kode_barang'},
				{ 'data': 'item'},
				{'data': 'price'},
				{'data': 'quantity'},
				{ 'data': 'satuan' },
				{'data': 'total','sClass':'text-right total'},
				{ 'data': 'penerimaanbarang_id','mRender':_render}
			];

		_GeneralTable(arrColumns,1,8);
		gentable = $('#datatable-details').DataTable(datatableDefaultOptions)
		.on('click', '.button-delete',function (d){
			if (confirm('Hapus item ini ?') == false) {
				return;
			}
			var tr = $(this).closest('tr');
			var row = gentable.row(tr);
			row.remove().draw();
		});
}

var _GeneralTable = function (arrColumns,coldef,colspan) {
	var _coldefs = [
				{
					"targets":[coldef],
					"visible": false
				}
			];
	
	 
	var fixedColumn = {
		leftColumns: 1
	}
	datatableDefaultOptions.searching = false;
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

		var api = this.api();
        var rows = api.rows( {page:'current'} ).nodes();
        var last=null;

        if(colspan != '' || colspan != undefined)
        {
	        api.column(coldef, {page:'current'} ).data().each( function ( group, i ) {
	            if ( last !== group ) {
	                $(rows).eq( i ).before(
	                    '<tr class="group" style="background-color: #3FD5C0;"><td colspan="'+colspan+'"><strong>'+group+'</strong></td></tr>'
	                );

	                last = group;
	            }
	        } );
	    }


	};
}

 $(document).ready(function(){
 	_initTableListPO();
 	_initTablePenerimaan();

 	$('#nomor_penerimaan').tagsinput({
    	itemValue: 'id',
    	itemText: 'label'
	});

 	$('select').select2();

 	fnSetAutoNumeric('#total_gr');
  fnSetAutoNumeric('#total_dp');
  fnSetAutoNumeric('#total_gr_setelah_dp');

 	$('#rekanan_id').change(function()
 	{
 		var id_vendor = $(this).val();

 		if(id_vendor != '' || id_vendor != undefined)
 		{
 			var _url = "{{ url('/goodreceive/gr_penerimaan_barang/getListsNomorPenerimaan') }}";
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
 					if(data.length > 0)
 					{
 						gentable_list_po.clear();
 						for(var count=0;count<data.length;count++)
 						{
 							var tableItem = {
	 							id:data[count].idpenerimaan,
	 							nopo:data[count].nopo,
	 							po_number:data[count].no,
	 							rekanan_id:data[count].idpenerimaan
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

 	$('#btn-save').click(function()
 	{

 		var _datasend = [];

 		$('#datatable-details > tbody > tr').each(function(i,v)
 		{
 			if($(this).find('td').length > 1)
 			{
 				var items = {
 					'id_penerimaan':$(this).find('#id_penerimaan').val(),
	 				'id_penerimaan_detail':$(this).find('#id_terima').val(),
	 				'item_id':$(this).find('#id_item').val(),
	 				'satuan_id':$(this).find('#satuan_id').val(),
	 				'total_qty':$(this).find('#total_qty').val(),
	 				'harga_satuan':$(this).find('#input_price').autoNumeric('get')
 				};

 				_datasend.push(items);
 			}
 			
 		});
 		$('#_alldatasend').val(JSON.stringify(_datasend));
 	});

 	$('#form-add-dp').submit(function(e)
 	{
 		e.preventDefault();

 		var _data = $(this).serializeArray();
 		var _url = $(this).attr('action');

 		$.ajax({
 			type:'post',
 			dataType:'json',
 			url : _url,
 			data : _data,
 			beforeSend : function()
 			{
 				waitingDialog.show();
 			},
 			success(data)
 			{
 				if(data)
 				{
 					alertify.success('Berhasil ditambahkan');
 					$('.button-addgr,.checkall').prop('checked',false);
 					$('#modal_pic').modal('hide');
 					$('#form-add-dp').trigger('reset');
 					$('select').select2("val","");
 					$('#nomor_po,#_alldatasend,#id_penerimaan_barang#id_po').val('');
 					$('#nomor_penerimaan').tagsinput('removeAll');
 					$('#rekanan_id').trigger('change');
 					gentable.clear().draw();

 					total_gr = 0;
          arr_penerimaan_barang_id = [];
 				}
 			},
 			complete:function()
 			{
 				waitingDialog.hide();
 			}
 		});
 	})

 	var tbody = $('#datatable-details tbody');
 	gentable.on('draw',function(){
 		tbody.find('.total').each(function(i,v)
 		{
 			
 			fnSetAutoNumeric($(this));
 			fnSetMoney($(this),$(this).text());
 		});

 		tbody.find('.pricing').each(function()
		{
			fnSetAutoNumeric($(this));
			fnSetMoney($(this),$(this).val());
		});
 		//$('.editable_price').editable();

 	});
 	tbody.on('input','.input_qty',function()
 	{
 		var Tparent = $(this).parents('tr');
 		var getQty = $(this).val();
 		var objprice = Tparent.find('#input_price');
 		var price = $(objprice).autoNumeric('get');
 		var total_price = getQty*price;
 		Tparent.find(".total").text(total_price);
 		fnSetMoney(Tparent.find(".total"),total_price);
 		total_gr = 0;
 		$('.total').each(function()
 		{
 			fnSetAutoNumeric($(this));
 			var getTotalPrice = parseFloat($(this).autoNumeric('get'));
 			total_gr += getTotalPrice;

 		});
 		
 		$('#total_gr').val(total_gr);
 		fnSetMoney('#total_gr',total_gr);
 	}).
 	on('keyup','.pricing',function()
 	{
 		var Tparent = $(this).parents('tr');
 		var getPrice = $(this).autoNumeric('get');
 		var qty = Tparent.find('#input_qty').val();
 		var total_price = qty*getPrice;
 		Tparent.find(".total").text(total_price);
 		fnSetMoney(Tparent.find(".total"),total_price);
 		total_gr = 0;
 		$('.total').each(function()
 		{
 			fnSetAutoNumeric($(this));
 			var getTotalPrice = parseFloat($(this).autoNumeric('get'));
 			total_gr += getTotalPrice;

 		});
 		
 		$('#total_gr').val(total_gr);
 		fnSetMoney('#total_gr',total_gr);
 	});


 	$('.checkall').click(function()
 	{
 		if($(this).is(':checked'))
 		{
 			$('#datatable-po tbody').find('.button-addgr').trigger('click');
 		}
 	});

 	/*$('.editable_price').on('shown',function(e,editable)
	{
		console.log('input editable');
		priceInput = editable.input.$input;
		fnSetAutoNumeric(priceInput);
	});*/

  });
</script>
</body>
</html>