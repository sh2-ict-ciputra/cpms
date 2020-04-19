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
			                <span>Daftar Asset</span>
			            </li>
			        </ul>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="col-md-12">
              	@include('form.a',[
								'href'=>url('/inventory/stock/view_stock'), 
								'caption' => ' Stock' 
							])
							<hr/>
			<table class="table table-striped table-bordered table-hover table-responsive table-checkable order-column nowrap table_master" id="table_asset">
				<thead >
					<tr>
						<th>Category</th>
						<th>Item</th>
						<th>Qty</th>
						<th>Satuan</th>
						<th></th>
					</tr>
				</thead>
			</table>
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
		gentable = $('#table_asset').DataTable(
		{
			scrollY:"300px",
		  	//scrollX:true,
	        scrollCollapse: true,
	        paging:false,
	        //fixedColumns: {leftColumns: 2,rightColumns: 1},	
          processing: true,
          ajax: "{{ url('/inventory/asset/getListAssets') }}",
          columns:[
                 { data: 'category',name: 'category',"bSortable": false},
				 { data: 'item_name',name: 'item_name',"bSortable": false},
                 { data: 'total',name: 'total',"bSortable": false},
                 { data: 'satuan',name: 'satuan',"bSortable": true},
                 
				 
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
          "columnDefs": [
				{targets:[0],visible:false}
				],
			"order": [[0,'asc']],//,
        	"drawCallback": function ( settings ) {
	            var api = this.api();
	            var rows = api.rows( {page:'current'} ).nodes();
	            var last=null;
	            api.column(0, {page:'current'} ).data().each( function ( group, i ) {
	                if ( last !== group ) {
	                    $(rows).eq( i ).before(
	                        '<tr class="group success"><td colspan="4" style="text-align:left;padding:10px;"><strong>'+group+'</strong></td></tr>'
	                    );
	 
	                    last = group;
	                }
	            });
        	},
        	"initComplete": function(settings, json) {
        			$('.group').nextUntil('.group').css( "display", "none" );
        		}
		});

		var tbody = $('#table_asset tbody');

		tbody.on('click','.group',function()
			{
				$(this).nextUntil('.group').toggle();

			}).find('.group').each(function(i,v){
				var rowCount = $(this).nextUntil('.group').length;
				$(this).find('td:first').append($('<span />', { 'class': 'rowCount-grid' }).append($('<b />', { 'text': ' (' + rowCount + ')' })));
			});

		tbody.on('click','.detail',function()
			{
				var data = gentable.row($(this).parents('tr')).data();
				var id = data.item_id;
				window.location.href = "{{ url('/inventory/asset/details/') }}"+"/"+id;
			});

	});
</script>
</body>
</html>