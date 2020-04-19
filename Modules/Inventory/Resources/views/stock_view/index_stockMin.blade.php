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
			                <a href="{{ url('/inventory/stock/view_stock') }}">Inventory</a>
			            </li>
			            <li>
			                <span>Barang Mencapai Stok Min.</span>
			            </li>
			        </ul>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="col-md-12">
                <form class="form-inline" method="post" action="{{ url('/inventory/stock/printMinStock') }}" id="form_cetak">
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
                  </form>
                  <hr/>
              	<table class="table table-striped table-bordered table-hover table-responsive table-checkable order-column nowrap table_master" id="table_data">
                  <thead style="background-color: #3FD5C0;">
                    <tr>
                      <th>Item</th>
                      <th>Stock Aktual</th>
                      <th>Satuan</th>
                    </tr>
                  </thead>
                <tbody>
                  @foreach ($getStocks as $key => $value)
                    <tr>
                      <td>{{ $value->item_name }}</td>
                      <td>{{ number_format($value->stock_afterkonversi,2,".",",") }}</td>
                      <td>{{ $value->satuan_name }}</td>
                    </tr>
                @endforeach 
                </tbody>
              </table>

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
<script type="text/javascript" src="{{ URL::asset('assets/global/plugins/moment.min.js')}}"></script>
<script type="text/javascript" src="{{ URL::asset('assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js')}}"></script>
<script type="text/javascript" charset="utf-8">
  var gentable = null;
  $(document).ready(function() {

    gentable = $('#table_data').DataTable({
      scrollY:        "300px",
      scrollCollapse: true,
      paging:         false
    });
    $('#periode').daterangepicker({
            //startDate: moment().subtract('days', 29),
           // endDate: moment(),
       format: 'DD/MM/YYYY',
        dateLimit: { days: 60 },
        showDropdowns: true,
        showWeekNumbers: true,
        
        separator: ' to '
      }
        ,function(start,end)
        {
          $('#start_opname').val(start.format('YYYY-MM-DD'));
          $('#end_opname').val(end.format('YYYY-MM-DD'));
        });
  });

</script>
</body>
</html>