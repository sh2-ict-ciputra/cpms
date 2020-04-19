<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Admin QS | Dashboard</title>
  @include("master/header")
  <!--link rel="stylesheet" type="text/css" href="//cdn.datatables.net/fixedcolumns/3.0.2/css/dataTables.fixedColumns.css"-->
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
			                <a href="{{ url('/inventory/asset/daftarAsset') }}">Asset</a>
			            </li>
			            <li>
			                <span>Detail Asset</span>
			            </li>
			        </ul>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="col-md-12">
              	@include('form.a',[
								'href'=>url('/inventory/asset/daftarAsset'), 
								'caption' => 'kembali' 
							])
				<hr/>
				<input type="hidden" name="item_id" id="item_id" value="{{ $item_id }}" />
					<div class="panel panel-success">
				 		<div class="panel-heading"><strong>{{ $item_name }} = {{ $total }}</strong></div>
					 	<div class="panel-body">
							<table class="table table-striped table-bordered table-hover table-responsive table-checkable order-column nowrap" id="detail_asset">
								<colgroup>
									<col style="width: 300px;"/>
									<col/>
									<col/>
									<col/>
									<col />
									<col/>
									<col/>
									<col/>
								</colgroup>
								<thead style="background: #3FD5C0;">
									<tr>
										<th>Departemen</th>
										<th class="text-center">Nilai Perolehan (Rp.)</th>
										<th class="text-center">Nilai Buku (Rp.)</th>
										<th>Batas Umur</th>
										<th>Satuan</th>
										<th>Lokasi</th>
										<th></th>
									</tr>
								</thead>
							</table>
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
<script type="text/javascript">
	var gentable = null;
	$(document).ready(function()
	{
		$.ajaxSetup({
		    headers: {
		        'X-CSRF-Token': $('input[name=_token]').val()
		    }
		});

		var item_id = $('#item_id').val();
		gentable = $('#detail_asset').DataTable({
	        paging:false,
	        //fixedColumns: {leftColumns: 2,rightColumns: 1},	
	          processing: true,
	          ajax: "{{ url('/inventory/asset/getDetailsPerItem/') }}"+"/"+item_id,
	          columns:[
	          		 { data: 'departemen',name: 'departemen',"bSortable": true},
	                 
	                 { data: 'total_price',name: 'total_price',"className":"text-right","bSortable": false},
	                 
					 { data: 'penyusutan',name: 'penyusutan',"className":"text-right","bSortable": false},
					 { data: 'asset_age',name: 'asset_age',"className":"text-right","bSortable": false},
					 { data: 'satuan',name: 'satuan',"bSortable": false},
					 
					 { data: 'location',name: 'location',"bSortable": false},
	                {
	                  "className": "action text-center",
	                  "data": null,
	                  "bSortable": false,
	                  "defaultContent": "" +
	                  "<div class='' role='group'>" +
	                  "<button type=\"button\" class=\"btn btn-success btn-xs detail\" rel='tooltip' data-toggle='tooltip' data-placement='top' title='Detail'><i class='fa fa-list'></i></button>" +
	                  "</div>"
	            }
	          ],
	          "columnDefs": [{targets:[0],visible:false}],
	          "order": [[ 0, 'asc' ]],
	          "drawCallback": function ( settings ) {
	            var api = this.api();
	            var rows = api.rows( {page:'current'} ).nodes();
	            var last=null;
	            api.column(0, {page:'current'} ).data().each( function ( group, i ) {
	                if ( last !== group ) {
	                    $(rows).eq( i ).before(
	                        '<tr class="group success"><td colspan="8" style="text-align:left;padding:10px;"><strong>'+group+'</strong></td></tr>'
	                    );
	 
	                    last = group;
	                }
	            });
        	}
		});

		var tbody = $('#detail_asset tbody');

		tbody.on('click','.detail',function()
		{
			//detail asset transaction
			var data = gentable.row($(this).parents('tr')).data();
			var asset_id = data.id;
			window.location.href = "{{ url('/inventory/asset/detailTransaction/') }}"+"/"+asset_id;
		});

		$('#btn-back').click(function()
		{
			var id = $(this).attr('data-id');
			window.location.href= "{{ url('/inventory/asset/daftarAsset') }}";
		});
	});
</script>
</body>
</html>