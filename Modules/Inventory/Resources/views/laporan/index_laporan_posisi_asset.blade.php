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
                      <span>Daftar Posisi Asset</span>
                  </li>
              </ul>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="col-md-12">
    <div class="panel panel-success">
      <div class="panel-heading"><strong>Daftar Posisi Asset</strong></div>
      <div class="panel-body">
       <form class="form-inline" method="post" action="{{ url('/inventory/asset/laporanposisiasset') }}" id="form_cetak">
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
        <p/>
        <table class="table table-bordered display table_master" id="table_master">
          <thead>
            <tr>
              <th>Departement</th>
              <th>Barang</th>
              <th>Barcode</th>
              <th>Deskripsi</th>
              <th>Umur Ekonomis (Tahun)</th>
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
@include('form.general_form')
<script type="text/javascript" src="{{ URL::asset('assets/global/plugins/moment.min.js')}}"></script>
<script type="text/javascript" src="{{ URL::asset('assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js')}}"></script>
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
  var totalEkonomis = 0;

  $(document).ready(function()
  {

    gentable = $('#table_master').DataTable({
          "language": datatable_idUI,
          paging:false,
          processing: true,
          ajax: "{{ url('/inventory/asset/getPosisi') }}",
          columns:[
                 { data: 'department',name: 'department',"className":"text-left","bSortable": false}, 
                 { data: 'item_name',name: 'item_name',"className":"text-left","bSortable": false },
                 { data: 'barcode',name: 'barcode',"className":"text-left","bSortable": false },
                 { data: 'description',name: 'description',"className":"text-left","bSortable": false },
                 
                 { data: 'umur_ekonomis',name: 'umur_ekonomis',"className":"text-right","bSortable": false },              
                 
          ],
          "columnDefs": [{ "visible": false, "targets": [0] }],
          "order": [[ 0, 'asc' ]],
          "drawCallback": function ( settings ) {
            var api = this.api();
            var rows = api.rows( {page:'current'} ).nodes();
            var last=null;
            
            api.column(0, {page:'current'} ).data().each( function ( group, i ) {
              //console.log(api.row(i).data().item_name);
              var lastval;
              if(group == api.row(i).data().item_name){
                  totalEkonomis+=parseFloat(api.row(i).data().nilai_perkiraan_sisa);
                  lastval = totalEkonomis;
                }
                else
                {
                  totalEkonomis = 0;
                  lastval = totalEkonomis;
                }
              if ( last !== group ) {

                  $(rows).eq( i ).before(
                      '<tr class="group success" rowspan="2"><td colspan="4" style="text-align:left;"><strong>'+group+'</strong></td></tr>'
                  );

                  last = group;
              }
          });
            
        },

        "initComplete":function(settings,json)
        {
            /*var api = this.api();
            var obj = $('.success').each(function(v,i){
              api.row(i).data().nilai_perkiraan_sisa;
            });*/
        }
          
    });

    var tBody = $('#table_master tbody');

    $('#btn_refresh').click(function()
    {
        gentable.ajax.reload();
    });

    gentable.on('draw',function()
    {
      $('.nie').each(function()
      {
          fnSetAutoNumeric($(this));
      });

       $('.nip').each(function()
      {
          fnSetAutoNumeric($(this));
      });

        $('.nipu').each(function()
      {
          fnSetAutoNumeric($(this));
      });

         $('.nibu').each(function()
      {
          fnSetAutoNumeric($(this));
      });
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


