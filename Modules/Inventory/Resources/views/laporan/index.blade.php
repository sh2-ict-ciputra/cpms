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
                      <span>Barang Masuk</span>
                  </li>
              </ul>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="col-md-12">

<div class="panel panel-success">
  <div class="panel-heading"><strong>Daftar Barang Yang Mencapai Stock Minimum</strong></div>
  <div class="panel-body">
    <button class="btn btn-success" type="button" id="btn_refresh"><i class="fa fa-refresh"></i> Refresh</button>
    <p/>
    <table class="table table-bordered display table_master" id="table_master">
      <thead>
        <tr>
        
          <th>Nama Barang</th>
          <th>Qty Aktual</th>
          <th>Stock Minimum</th>
          <th>Satuan Besar</th>
          <th>Satuan Minimum</th>
          <th>Konvensi</th>
          
         
          <th></th>
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
@include("master/footer_table")
<script type="text/javascript" src="{{ URL::asset('assets/global/plugins/datatables/datatables.min.js')}}"></script>
<script type="text/javascript" src="{{ URL::asset('assets/global/plugins/datatables/dataTables.fixedHeader.min.js')}}"></script>
<script src="{{ URL::asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js')}}" 
type="text/javascript">
</script>
<script type="text/javascript">
  var gentable = null;
  var notify = null;
  var datatable_idUI = {
    "sProcessing":   "Sedang memproses...",
    "sLengthMenu":   "Tampilkan _MENU_ entri",
    "sZeroRecords":  "[tidak ada data]",
    "sInfo":         "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
    "sInfoEmpty":    "Menampilkan 0 sampai 0 dari 0 entri",
    "sInfoFiltered": "(disaring dari _MAX_ entri keseluruhan)",
    "sInfoPostFix":  "",
    "sSearch":       "Cari: ",
    "sUrl":          "",
    "oPaginate": {
        "sFirst":    "Pertama",
        "sPrevious": "Sebelumnya",
        "sNext":     "Selanjutnya",
        "sLast":     "Terakhir"
    }
}
  $(document).ready(function()
  {

    $('#barangkeluar').addClass('active');
    $('.panel-success').outerHeight();
    gentable = $('#table_master').DataTable({
          fixedHeader: {
            header:true,
            headerOffset: $('#navMenu').outerHeight()
          },
          "language": datatable_idUI,
          processing: true,
          ajax: "{{ url('/inventory/laporan/getdaftarbarang') }}",
          columns:[

                 
                 
                 { data: 'nama_barang',name: 'nama_barang',"bSortable": false},
                 { data: 'qty_min',name: 'qty_min',"className":"text-right","bSortable": false}, 
                 { data: 'stok_min',name: 'stok_min',"className":"text-right","bSortable": false },                
                 { data: 'satuan_besar',name: 'satuan_besar',"className":"text-right","bSortable": false},                 
                 { data: 'satuan_min',name: 'satuan_min',"className":"text-right","bSortable": false},
                 { data: 'konvensi',name: 'konvensi',"className":"text-right","bSortable": false},
                
                {
                  "className": "action text-center",
                  "data": null,
                  "bSortable": false,
                  "defaultContent": "" +
                  "<div class='' role='group'>" +
                  "<button class='print btn btn-danger btn-xs' rel='tooltip' data-toggle='tooltip' data-placement='top' title='Cetak'><i class='fa fa-print'></i></button>"+

                  "</div>"
                }
          ],
          "columnDefs": [{ "visible": false, "targets": [0] }],
          "order": [[ 0, 'asc' ]],
          "drawCallback": function ( settings ) {
            var api = this.api();
            var rows = api.rows( {page:'current'} ).nodes();
            var last=null;
            api.column(0, {page:'current'} ).data().each( function ( group, i ) {
              if ( last !== group ) { 
                  $(rows).eq( i ).before(
                      '<tr class="group success" rowspan="2"><td colspan="9" style="text-align:left;"><strong>'+group+'</strong></td></tr>'
                  );

                  last = group;
              }
          });
            
        }
    });

    var tBody = $('#table_master tbody');

    tBody.on('click','.print',function()
      {
          var data = gentable.row($(this).parents('tr')).data();
          var laporan_id = data.id;
          window.location.href="{{ url('/laporan/printMin') }}"+"/"+laporan_id;
      });

    $('#btn_refresh').click(function()
    {
        gentable.ajax.reload();
    });

  });
</script>
</body>
</html>
