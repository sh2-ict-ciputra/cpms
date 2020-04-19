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
      <h1>Master Satuan</h1>

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
                      <span>Master Satuan</span>
                  </li>
              </ul>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="col-md-12">
              	<strong>Satuan</strong>	
              	@include('form.a',[
						'href'=> url('/inventory/satuan/create'),
						'class'=>'pull-right',
						'caption' => 'Tambah' 
					])
				<hr/>
				<table class="table table_master" id="table_data">
					<thead>
						<tr>
							<th>No</th>
							<th style="width: 270px;">Satuan</th>
							<th style="width: 270px;">Konversi</th>
							<th>Tanggal Tambah</th>
							<th>Tanggal Perbarui</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
					@foreach($satuans as $key => $each)
						<tr>
							<td>{{ $key + 1 }}</td>
							<td><a href="#" class="editable_header" 
								data-pk="{{ $each->id}}" 
								data-name="name" 
								data-url="{{url('/inventory/satuan/update')}}" 
								data-original-title="Nama"
								data-type="text" 
								data-value="{{ $each->name }}">{{ $each->name }}</a></td>
							<td><a href="#" class="editable_header" 
							data-pk="{{ $each->id}}" 
							data-name="konversi" 
							data-url="{{url('/inventory/satuan/update')}}" 
							data-original-title="Konversi"
							data-type="text" 
							data-value="{{ $each->konversi }}">{{ $each->konversi }}</a></td>
							<td>{{ date('d-m-Y H:m:s',strtotime($each->created_at)) }}</td>
							<td>{{ date('d-m-Y H:m:s',strtotime($each->updated_at)) }}</td>
							<td align="center">
								<button id="{{ $each->id }}" href="#" class="btn btn-xs btn-danger delete-link"><i class="fa fa-trash"></i></button>
							</td>
						</tr>
					@endforeach
					</tbody>
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
@include("master/footer_table")
@include('pluggins.editable_plugin')
@include('pluggins.alertify')

	<script type="text/javascript" charset="utf-8">
		  $.ajaxSetup({
    				headers: {
                  'X-CSRF-Token': $('meta[name=csrf-token]').attr('content')
                }
    });
  $.fn.editable.defaults.mode = 'inline';

		var gentable = null;
		$(document).ready(function() {
			var token = $('input[name=_token]').val();
			gentable = $('#table_data').DataTable(
				{
					"scrollY":"200px",
			        "scrollCollapse": true,
			        "paging":         false
				});

			var sbody = $('#table_data tbody');

			sbody.on('click','.delete-link',function()
			{
				var trParent = $(this).parents('tr');
				var id = $(this).attr("id");
				var data = gentable.row(trParent).data();
				
				$.confirm({
					title: 'Confirm Delete ?',
					icon: 'fa fa-warning',
					content: 'Are you sure delete "' +data[1]+ '" ?',
					autoClose: 'cancelAction|8000',
					buttons: {
						deleteUser: {
							text: 'Delete',
							btnClass: 'btn-red any-other-class',
							action: function () {
								$.post("{{ url('/inventory/brand/delete') }}", 
								{
									id:id,
									_token: token
								}, 
								function(data) {
									if(data)
									{
										trParent[0].remove();
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

			$('.editable_header').editable({
				ajaxOptions: {
				    type: 'post',
				    dataType: 'json'
				},
				success:function(data)
				{
					if(data.return)
					{
						alertify.success('success');
					}
				}
			});
		});
	</script>
</body>
</html>