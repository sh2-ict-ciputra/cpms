@extends('layouts.master')
@section('css')
<link href="{{ URL::asset('assets/global/plugins/datatables/datatables.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('assets/global/plugins/datatables/fixedHeader.dataTables.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css')}}" rel="stylesheet" type="text/css" />

@endsection
@section('content')
<div class="panel panel-success">
  <div class="panel-heading"><strong>Daftar Pemakaian Barang</strong></div>
  <div class="panel-body">
    <button class="btn btn-success" type="button" id="btn_refresh"><i class="fa fa-refresh"></i> Refresh</button>
    <p/>
    <table class="table table-bordered display table_master" id="table_master">
      <thead>
        <tr>
          <th>#</th>
          <th>No Permintaan </th>
          <th>No Barang Keluar</th>

          <th>Description</th>
          <th>Tanggal</th>

          <th></th>
        </tr>
      </thead>
    </table>
  </div>
</div>
@endsection
@section('scripts')
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
    $('.panel-success').outerHeight();
    gentable = $('#table_master').DataTable({
          fixedHeader: {
            header:true,
            headerOffset: $('#navMenu').outerHeight()
          },
          "language": datatable_idUI,
          processing: true,
          ajax: "{{ url('/inventory/getPemakaianBarang') }}",
          columns:[

                 /*{ data: 'no',name: 'no',"bSortable": false},
                 { data: 'nomor_barangkeluar',name: 'nomnomor_barangkeluaror_permintaan',"bSortable": false},
                 
                 { data: 'nomor_permintaan',name: 'nomor_permintaan',"bSortable": false},                 
                 { data: 'confirm_by_requester',name: 'confirm_by_requester',"className":"text-center","bSortable": false},
                 { data: 'confirm_by_warehouse',name: 'confirm_by_warehouse',"className":"text-center","bSortable": false },
                 { data: 'tangal_barang_keluar',name: 'tangal_barang_keluar',"className":"text-right","bSortable": false},
                {
                  "className": "action text-center",
                  "data": null,
                  "bSortable": false,
                  "defaultContent": "" +
                  "<div class='' role='group'>" +
                  "<button class='details btn btn-success btn-xs' rel='tooltip' data-toggle='tooltip' data-placement='right' title='Details'><i class='fa fa-list-alt'></i></button>"+

                  "</div>"
                }*/
          ],
          "columnDefs": [
            //{ "visible": false, "targets": [] }
          ],
          "order": [[ 0, 'asc' ]]
    });

    var tBody = $('#table_master tbody');

    tBody.on('click','.details',function(){
        var data =  gentable.row($(this).parents('tr')).data();
        var barangkeluar_id = data.id;
        window.location.href="{{('/inventory/detailsBarangKeluar')}}"+"/"+barangkeluar_id;
    });

    $('#btn_refresh').click(function()
    {
        gentable.ajax.reload();
    });

  });
</script>
@endsection