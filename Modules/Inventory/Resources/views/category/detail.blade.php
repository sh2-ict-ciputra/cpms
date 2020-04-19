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
			                  	<span>Detail {{ $categories->name }}</span>
			                  </li>
			              </ul>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="col-md-12">
              	<strong>{{ $categories->name }}</strong>
              	@include('form.a',[
						'href'=> url('/inventory/category/index'),
						'class'=>'pull-right',
						'caption' => 'Kembali' 
					])
              	<hr/>

              	<div class="panel panel-success">
			  	<!-- Default panel contents -->
				  <div class="panel-heading">
				  	Detail <button class="btn btn-primary pull-right" id="item_brg"><i class="fa fa-plus"></i> Brand</button>
				  	<p/>
				  </div>
				  <div class="panel-body">
				  	<input type="hidden" id="id_category" name="id_category" value="{{ $categories->id }}" />
				  	<table class="table">
				  		<thead>
				  			<tr>
				  				<th>
				  					Kategori Induk
				  				</th>
				  				<th><a href="#" class="editable_details" 
											data-pk="{{ $categories->id}}" 
											data-name="parent_id" 
											data-url="{{url('/inventory/category/update')}}" 
											data-original-title="Pilih"
											data-type="select" 
											data-value="{{ $categories->parent_id}}" 
											data-source="{{url('/inventory/category/categorySource')}}">{{ $categories->parent->name }}</a>
				  				</th>
				  				
				  			</tr>
				  			<tr>
				  				<th>Kategori</th>
				  				<th><a href="#" class="editable_details" 
											data-pk="{{ $categories->id}}" 
											data-name="name" 
											data-url="{{url('/inventory/category/update')}}" 
											data-original-title="Nama Kategori"
											data-type="text" 
											data-value="{{ $categories->name}}" >{{ $categories->name }}</a></th>
				  			</tr>
				  		</thead>
				  	</table>
				  	<strong>List Merek / Brand</strong>
				  	<br/>
				  	<table class="table table-striped table-bordered table-hover table-responsive table-checkable order-column nowrap table_master" id="table_brand">
				  		<thead>
				  			
				  			<tr>
				  				<th>
				  					#
				  				</th>
				  				<th>
				  					
				  				</th>
				  			</tr>
				  			
				  		</thead>
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
@include('pluggins.editable_plugin')
<script src="{{ URL::asset('vendor/jsvalidation/js/jsvalidation.min.js')}}" type='text/javascript'></script>
{!! JsValidator::formRequest('Modules\Inventory\Http\Requests\RequestEditCategory', '#id-SaveForm') !!}
<script type="application/javascript">
	 $.ajaxSetup({
	    headers: {
	                  'X-CSRF-Token': $('meta[name=csrf-token]').attr('content')
	                }
    });
  $.fn.editable.defaults.mode = 'inline';
  var gentable = null;
$(document).ready(function()
{
	gentable = $('#table_brand').DataTable({
			scrollY:        "300px",
	        scrollCollapse: true,
	        paging:         false,
			  processing: true,
	          ajax: "{{ url('/inventory/category_detail/getData') }}"+"/"+$('#id_category').val(),
	          columns:[
	                 { data: 'brand_name',name: 'brand_name',"bSortable": true},
	                {
	                  "className": "action text-center",
	                  "data": null,
	                  "bSortable": false,
	                  "defaultContent": "" +
	                  "<div class='' role='group'>" +
	                  
	                  "<button class='delete btn btn-danger btn-xs' rel='tooltip' data-toggle='tooltip' data-placement='right' title='Hapus'><i class='fa fa-trash-o'></i></button>" +
	                  "</div>"
	            }
	          ],
	          "columnDefs": [],
	          "order": [[ 0, 'asc' ]]//,
	});
	$('.editable_details').editable({
				ajaxOptions: {
				    type: 'post',
				    dataType: 'json'
				},
				success:function(data)
				{
					if(data)
					{
						alertify.success('success update');
					}
				}
			}
		);

	//$('.modal-dialog').draggable();

	    $('#modal_satuan').on('show.bs.modal', function() {
		      $(this).find('.modal-body').css({
		        'max-height': '100%'
		      });
	    });

	    $('#item_brg').click(function()
		{
			$('#modal_satuan').modal('show');
		});

	var sbody = $('#table_brand tbody');
	sbody.on('click','.delete',function()
	{
		var parent = $(this).parents('tr');
		var data = gentable.row(parent).data();
		var del_id = data.id;
			$.confirm({
				title: 'Confirm Delete ?',
				icon: 'fa fa-warning',
				content: 'Are you sure delete ?',
				autoClose: 'cancelAction|8000',
				buttons: {
					deleteUser: {
						text: 'Delete',
						btnClass: 'btn-red any-other-class',
						action: function () {
							$.post("{{ url('/inventory/brand_of_category/delete') }}", 
							{
								id:del_id
							}, 
							function(data) {
								if(data)
								{
									gentable.ajax.reload();
									alertify.success('success deleted!',1);
								}
							});	
						}
					},
					cancelAction: function () {
						
					}
				}
			});
	});

	$('#datatable-items').DataTable();

 	 tBodyItems = $('#datatable-items tbody');

 	 tBodyItems.on('click','.add_brand_checkbox',function()
 	 {
 	 	var obj = $(this);
 	 	var id_item = $(this).val();
 	 	var objDataItem ={
 	 					brand_id: id_item,
 	 					category_id:$('#id_category').val()
 	 				};
 	 	
 	 	if($(this).is(':checked'))
 	 	{
 	 		$.ajax({
 	 			type:'post',
 	 			url :"{{ url('/inventory/brand_of_category/addBrand') }}",
 	 			data : objDataItem,
 	 			dataType :'json',
 	 			beforeSend:function()
				{
					waitingDialog.show();
				},
 	 			success : function(data)
 	 			{
 	 				if(data)
 	 				{
 	 					
 	 					alertify.success('Merek Berhasil Ditambahkan');
 	 					gentable.ajax.reload();
 	 				}
 	 				else
 	 				{
 	 					alertify.error('Warning : Brand sudah ada');
 	 				}
 	 			},
 	 			complete:function()
 	 			{
 	 				obj.prop('checked',false);
 	 				$('#modal_satuan').modal('hide');
 	 				waitingDialog.hide();
 	 			}
 	 		});
 	 	}
 	 	else
 	 	{
 	 		
 	 	}
 	 });
});
</script>
</body>
</html>