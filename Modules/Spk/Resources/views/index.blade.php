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

  @include("master/sidebar_project")

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>Data Proyek {{ $project->name }}</h1>

    </section>

    <!-- Main content -->
    <section class="content">
      {{ csrf_field() }}
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Data SPK </h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive">
              <table id="index" class="table table-bordered table-hover" >
                <thead class="head_table">
                <tr>
                  <th>no</th>
                  <th>No. SPK </th>
                  <th>Kawasan </th>
                  <th>COA</th>
                  <th>Pekerjaan</th>
                  <th>Department From</th>
                  <th>Nilai Spk Awal</th>
                  <th>Nilai Spk Akhir</th>
                  <th>Tanggal</th>
                  <th>Detail</th>
                  <th>Prog Lap (Byr) </th>
                </tr>
                </thead>
                <tfoot id="tfoot" style="display:table-header-group;">
                  <tr>
                    <th></th>
                    <th>No. SPK </th>
                    <th>Kawasan </th>
                    <th>COA</th>
                    <th>Item Pekerjaan</th>
                    <th>Department From</th>
                    <th>Nilai Awal</th>
                    <th>Nilai Akhir</th>
                    <th>Tanggal</th>
                    <th>Detail</th>
                    <th>Prog Lap (Byr) </th>
                  </tr>
                </tfoot>
                <tbody>
                  @foreach ( $spk as $key => $value )
                  @if ( $value->tender != "" )
                  <tr>
                    <td style="text-align:center;">{{ $key+1 }}</td>
                    <td>{{ $value->no }}</td>
                    <td>
                      @if($value->tender->rab->workorder->kawasan_id != null)
                        {{ $value->tender->rab->workorder->projectKawasan->name }}
                      @else
                        Fasilitas Kota
                      @endif
                    </td>
                    <td>{{ $value->itempekerjaan->code or '' }}</td>
                    <td>{{ $value->name }}</td>
                    <td>{{ $value->tender->rab->workorder->departmentFrom->name or '' }}</td>
                    <td style="text-align:right;">{{ number_format($value->nilai) }}</td>
                    <td style="text-align:right;">{{ number_format($value->nilai_spk) }}</td>
                    <td>{{ $value->date->format("d/M/Y") }}</td>
                    <td><a href="{{ url('/')}}/spk/detail?id={{ $value->id }}" class="btn btn-warning">Detail</a></td>
                    <td>
                      <!-- @if ( $value->approval == "" )
                        <strong style="font-size:16px;color:orange;">Belum di Proses</strong>
                      @else
                        <strong style="font-size:16px;color:green;">Sudah di proses</strong>
                      @endif                -->
                      {{ $value->lapangan }}%({{$value->progress_sebelumnya_cair}}%) 
                      @if(($value->lapangan - $value->retensis->sum('percent')) < $value->progress_sebelumnya_cair && $value->progress_sebelumnya_cair != 0 &&$value->progress_sebelumnya_cair != 100 && $value->lapangan != 0)
                        - <strong> ST </strong>
                      @elseif($value->progress_sebelumnya_cair == 100)
                        - <strong> Final Account </strong>
                      @endif
                    </td>
                  </tr>
                  @endif
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
@include("master/copyright")
  
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

@include("master/footer_table")
@include("spk::app")
</body>

<script>
  $(document).ready(function() {
    $('#index tfoot th').each(function (){
      var title = $(this).text();
      var n = (6+title.length)*8;
      $(this).html('<input type="text" placeholder="Filter '+title+'" / style="width:'+n+'px;">' );
    });

    var table = $('#index').DataTable( {
        // "pageLength" : 10,
        // "order": [[ 0, 'desc' ]],
        // "scrollX": true
        // scrollY: "500px",
        scrollCollapse: true,
    } );
    
    var table = $('#index').DataTable();

    table.columns().every(function (){
      var that = this;
      $('input', this.footer()).on('keyup change', function(){
        if(that.search() !== this.value){
          that
            .search(this.value)
            .draw();
        }
      });
    });
    
  } );
</script>
</html>
