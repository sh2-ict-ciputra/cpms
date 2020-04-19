<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Admin QS | Dashboard</title>
  @include("master/header")
   <link href="{{ URL::asset('assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css')}}" rel="stylesheet" type="text/css" />
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
                <a href="#">Good Receive</a>
            </li>
            <li>
                <span>Detail</span>
            </li>
            <li>
              <span>{{ $gr_data->no }}</span>
            </li>
        </ul>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="col-md-12">
              	<strong>Detail Good Receive {{ $gr_data->no }}</strong>
	
				<hr />
				@include('form.a',
							[
								'href' => url('/goodreceive/gr_dp/create'),
								'caption' => 'Tambah',
								'class'=>'pull-right'
							])
                @include('form.a',
              [
                'href' => url('/goodreceive/gr_penerimaan_barang'),
                'caption' => 'Kembali'
              ])

<!-- 	<form class="form-inline" method="post" action="{{ url('/inventory/barangmasuk_hibah/printReport') }}" id="form_cetak">
	  <div class="form-group">
	  	{{ csrf_field() }}
	     <label>Rentang Tanggal </label>
		    <div class="input-group" id="dtpicker">
		        <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
		        	<input type="text" name="periode" id="periode" class="form-control"/>
		        	<input type="hidden" name="start_opname" id="start_opname" value="{{date('Y-m-d')}}" />
		        	<input type="hidden" name="end_opname" id="end_opname" value="{{date('Y-m-d')}}" />
		    </div>
	  </div>
	  <button type="submit" class="btn btn-primary"><i class="fa fa-print"></i> Cetak</button>
	</form> -->

	<hr/>
		
		<table class="table table-striped" id="table_data_header">
			<thead>
  				<tr>
            <th>Nomor PO</th><td>{{ $gr_data->po->no }}</td>
          </tr>
          <tr>
  					<th>Nomor GR</th><td>{{ $gr_data->no }}</td>
          </tr>
          <tr>
  					<th>Vendor</th><td>{{ $gr_data->po->rekanan_name }}</td>
          </tr>
          <tr>
  					<th>Tanggal</th><td>{{ date('d-m-Y',strtotime($gr_data->date)) }}</td>
          </tr>
          <tr>
  					<th>Status</th><td>Kosong</td>
  				</tr>
			</thead>
		</table>

    <input type="hidden" name="gr_id" id="gr_id" value="{{ $gr_data->id }}" />

    <table class="table table-striped table-bordered table-hover table-responsive table-checkable nowrap stripe row-border order-column table_master" id="table_data">
      <thead style="background-color: #3FD5C0;">
        <tr>
          <th>Kode</th>
          <th>Item</th>
          <th>Harga (Rp.)</th>
          <th>Qty</th>
          <th>Satuan</th>
          <th>Total (Rp.)</th>
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

  <div class="control-sidebar-bg"></div>
</div>
@include("master/footer_table")
@include('pluggins.alertify')
@include('form.general_form')
<script type="text/javascript">
  var gentable = null;
  $(document).ready(function(){
      gentable = $('#table_data').DataTable(
      {
           /*crollY:"300px",
            scrollCollapse: true,
            paging:false,
            processing: true,*/
            ajax: "{{ url('/goodreceive/details/items') }}"+"/"+parseInt($('#gr_id').val()),
            columns:[
                     { data: 'kode',name: 'kode'},
                     { data: 'item_name',name: 'item_name'},
                     { data: 'price',name: 'price','className':'text-right',"bSortable": false},
                     { data: 'qty',name: 'qty','className':'text-right',"bSortable": false},
                     { data: 'satuan_name',name: 'satuan_name'},
                     { data: 'total',name: 'total','className':'text-right',"bSortable": false}
                   ],
            "columnDefs": [],
            "order": [[ 0, 'asc' ]]
        });

    var tbody = $('#table_data tbody');

    gentable.on('draw',function()
    {
      tbody.find('.text-right').each(function(i,v)
      {
        fnSetAutoNumeric($(this));
        fnSetMoney($(this),$(this).text());
        
      });
    })
  });
</script>

</body>
</html>

