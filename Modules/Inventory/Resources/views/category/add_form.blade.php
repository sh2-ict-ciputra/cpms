<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Admin QS | Dashboard</title>
  @include("master/header")
  
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  @include("master/sidebar")

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>Kategori</h1>

    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
  
			  <ul class="breadcrumb">
			                  <li>
			                      <a href="{{ url('/inventory/category/index') }}">Kategori</a>
			                  </li>
			                  <li>
			                  	<span>Tambah</span>
			                  </li>
			              </ul>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="col-md-12">
              	<strong>Tambah Kategori</strong>
              	@include('form.a',[
						'href'=> url('/inventory/category/index'),
						'class'=>'pull-right',
						'caption' => 'Kembali' 
					])
              	<hr/>
<form method="post" id="id-SaveForm" action="{{ url('/inventory/category/create') }}" class="form-horizontal form-label-left" autocomplete="off">
	<input type="hidden" name="allItemStore" id="allItemStore" value="" />
	<div class="item form-group">
	    <label class="control-label col-md-3 col-sm-3 col-xs-12">Kategori Atasan</label>
	    <div class="col-md-7 col-sm-7 col-xs-12">
	    	{{ csrf_field() }}
	    	<select class='form-control' name="parent_id" id="parent_id">
			</select>
	    </div>
  	</div>

  	<div class="item form-group">
	    <label class="control-label col-md-3 col-sm-3 col-xs-12">Nama</label>
	    <div class="col-md-7 col-sm-7 col-xs-12">
	    	<input type='text' id='name' name='name' class='form-control' />
	    </div>
  	</div>
	
	<div align="center">
		<button type="submit" class="btn btn-success green showtoast" name="btn-save" id="showtoast">
			<span class="fa fa-save"></span> Simpan
		</button>
		<button id="item_brg" type="button" class="btn btn-primary"><i class="fa fa-list"></i> Brand / Merek</button>
	</div>
</form>
<table id="datatable-details" class="table table-striped table-bordered table-hover table-responsive table-checkable order-column nowrap">
			<thead>
				<tr>
					<th>#</th>
					<th>Merek/Brand</th>
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
<!-- ./wrapper -->
<!-- Modal -->
<div class="modal fade" id="modal_satuan" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Pilih Item Barang</h4>
      </div>
      <div class="modal-body">
        <table id="datatable-items" class="table table-striped table-bordered table-hover table-responsive table-checkable order-column nowrap table_master">
			<thead>
				
				<tr>
					<th></th>
					<th>Merek</th>
				</tr>
			</thead>
			<tbody>
				@foreach($brands as $key => $value)
				<tr>
					<td >
						<input type="hidden" name="rowIdx" id="rowIdxPenerimaan" value="-1" />
						<input type="checkbox" name="add_brand" id="add_brand" class="add_brand_checkbox" value="{{ $value->id }}" />
					</td>
					<td>
						{{ $value->name }}
					</td>
					
				</tr>
				@endforeach
			</tbody>
		</table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" data-dismiss="modal">OK</button>
      </div>
    </div>
  </div>
</div>

@include("master/footer_table")
@include('pluggins.alertify')
@include('form.datatable_helper')
<script src="{{ URL::asset('vendor/jsvalidation/js/jsvalidation.min.js')}}" type='text/javascript'></script>
{!! JsValidator::formRequest('Modules\Inventory\Http\Requests\RequestCategory', '#id-SaveForm') !!}
<script type="application/javascript">
	var gentable = null;
var fnRefreshSelect = function()
{
	var strHtml='';
	strHtml+="<option value='0'>-- Pilih --</option>";
	$.get("{{ url('/inventory/category/getparent') }}",function(data,status)
	{
		$(data).each(function(i,v){
			strHtml+="<option value='"+v.id+"'>"+v.name+"</option>";
		});

		$('#parent_id').find('option').remove().end().append(strHtml);
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
				{ 'data': 'brand'},
				{ 'data': 'brand_id', 'mRender': _renderEdit}
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
var tableItems = null;
$(document).ready(function()
{
	fnRefreshSelect();
	_initTablePenerimaan();

	tableItems = $('#datatable-items').DataTable();

 	 tBodyItems = $('#datatable-items tbody');

 	 tBodyItems.on('click','.add_brand_checkbox',function()
 	 {
 	 	var idx = $(this).prev('input').val();
 	 	var getdata = tableItems.row($(this).parents('tr')).data();
 	 	
 	 	var id_item = $(this).val();
 	 	var objDataItem ={
 	 					term: id_item
 	 					,_token : $('input[name="_token"]').val()
 	 				};
 	 	
 	 	if($(this).is(':checked'))
 	 	{
 	 		var tableItem = {
								id:id_item ,
								brand: getdata[1],
								brand_id : id_item					
		                  };

          	if(idx ==-1)
 	 		{
 	 			gentable.row.add(tableItem);
		    }
		    gentable.draw();
 	 	}
 	 	else
 	 	{
 	 		var alldata = gentable.rows().data();
 	 		$(alldata).each(function(i,v){
 	 			if(v.id == id_item)
 	 			{
 	 				gentable.row(i).remove().draw();
 	 			}
 	 		});
 	 	}
 	 });
	$('#id-SaveForm').submit(function(e)
	{
		e.preventDefault();
		var _alldataItemSend =[];
		var alldata = gentable.data();
		$(alldata).each(function(i,v)
		{

			_alldataItemSend.push(v);
		});
		$('#allItemStore').val(JSON.stringify(_alldataItemSend));  

		var url 	= $(this).attr('action');
		var data 	= $(this).serialize();
		$('#id-SaveForm input').attr('disabled','disabled');
		$.ajax({
			type:'POST',
			dataType:'json',
			url:url,
			data:data,
			beforeSend:function()
			{
				waitingDialog.show();
			},
			success:function(get)
			{
				if(get.return=='1')
				{

					$('.add_brand_checkbox').prop('checked',false);
					alertify.success('success saved!',3);
					$('#id-SaveForm').trigger('reset');
					gentable.clear().draw();


				}
				else
				{
					alertify.error(get.errMsg);
				}

				return false;
			},
			error:function(xhr,status,message)
			{
				
			},
			complete:function()
			{
				$('#id-SaveForm input').removeAttr('disabled');
				fnRefreshSelect();
				waitingDialog.hide();
			}
		});
	});

	$('.modal-dialog').draggable();

	    $('#modal_satuan').on('show.bs.modal', function() {
		      $(this).find('.modal-body').css({
		        'max-height': '100%'
		      });
	    });

	    $('#item_brg').click(function()
		{
			$('#modal_satuan').modal('show');
		});
});
</script>
</body>
</html>