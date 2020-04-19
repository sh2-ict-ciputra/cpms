<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}" />

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
      <h1>Data Proyek <strong>{{ $project->name }}</strong></h1>
      <h4>Total Luas Brutto : {{ number_format($project->luas) }} m2</h4>
      <h4>Total Luas Netto  : {{ number_format( $total_unit[0]->luas_netto) }} m2</h4>
      <h4>Total Unit  : {{ number_format( $total_unit[0]->jumlah_unit ) }} </h4>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item active">Kawasan</li>
              </ol>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive">
              <a href="{{ url('/')}}/project/add-kawasan" class="btn-lg btn-primary"><i class="glyphicon glyphicon-plus-sign"></i>Tambah Kawasan</a><br/><br/>
              <input type="hidden" name="id_project" id="id_project" value="{{ $project->id }}">
              <table id="table_kawasan" class="table table-bordered table-hover ">   
                <thead class="head_table">
                  <tr>
                    <td rowspan="">Kawasan</td>
                    <td rowspan="">Luas<br/>Lahan Brutto(m2)</td>
                    <td rowspan="">Luas<br/>Lahan Netto(m2)</td>
                    <td rowspan="">Jumlah<br/> Blok</td>
                    <td rowspan="">Jumlah<br/> Unit</td>
                    <td rowspan="">Status Lahan<br>(PL,UC,F)</td>
                    <td rowspan="">Edit Blok</td>
                    <td rowspan="">Edit Kawasan</td>
                    <td rowspan="">Delete</td>
                  </tr>                
                </thead>
              </table>

              <center><h3>Unit Pending </h3></center>
              
                {{ csrf_field() }}
                <table class="table table-bordered table-hover">
                  <thead class="head_table">
                    <tr>
                      <td>Kawasan</td>
                      <td>Blok</td>
                      <td>Nomor</td>
                      <td>Status</td>
                      <td>Keterangan</td>
                      <td>Action</td>
                    </tr>
                  </thead>
                  <tbody>
                    <!-- @if ( count($project->unit_pending_baru) > 0 ) -->
                      @foreach ( $project->unit_pending_baru as $key => $value )
                        <tr>
                          <td>{{ $value['cluster'] }}</td>
                          <td>{{ $value['blok'] }}</td>
                          <td>{{ $value['name'] }}</td>
                          <td>{{ $value['status'] }}</td>
                          <td>{{ $value['description'] }}</td>
                          <td>
                            @if ( $value['status_id'] != 3 )
                            <!-- <input type="radio" name="id[{{$key}}]" value="{{ $value['unit_id']}},1"> <label class="label label-success">Terima</label>
                            <input type="radio" name="id[{{$key}}]" value="{{ $value['unit_id']}},0"> <label class="label label-danger">Tolak</label> -->
                            @endif
                            <a href="{{url('/')}}/project/edit-unit?id={{$value['unit_id']}}" class="btn btn-warning" target="_blank">Detail</a>
                          </td>
                        </tr>
                      @endforeach
                    <!-- @endif -->
                  </tbody>
                </table>
              

              <center><h3>Unit Change Price </h3></center>
                <form action="{{ url('/')}}/project/saveunitchange" method="post" name="form1">
                {{ csrf_field() }}
                <button type="submit" class="btn btn-success">Simpan</button>
                <table class="table table-bordered table-hover">
                  <thead class="head_table">
                    <tr>
                      <td>Kawasan</td>
                      <td>Blok</td>
                      <td>Nomor</td>
                      <td>Luas Tanah</td>
                      <td>Luas Bangunan</td>
                      <td>No.SPK</td>
                      <td>Keterangan</td>
                      <td>Action</td>
                    </tr>
                  </thead>
                  <tbody>
                  @if ( count($project->unit_vo) > 0 )
                   @foreach ( $project->unit_vo as $key => $value )
                   <tr>
                    <td>{{ $value['kawasan']}}</td>
                    <td>{{ $value['blok']}}</td>
                    <td>{{ $value['code']}}</td>
                    <td>{{ $value['tanah_luas']}}</td>
                    <td>{{ $value['bangunan_luas']}}</td>
                    <td>{{ $value['no_spk']}}</td>
                    <td>{{ $value['keterangan']}}</td>
                    <td>
                      @if ( $value['action'] != "")
                        <span class="label label-success">Done</span>
                      @else
                      <input type="checkbox" name="unit_id[{{$key}}]" value="{{ $value['id']}}">
                      <input type="hidden" name="tanah_luas[{{$key}}]" value="{{ $value['tanah_luas']}}">
                      <input type="hidden" name="bangunan_luas[{{$key}}]" value="{{ $value['bangunan_luas']}}">
                      @endif
                    </td>
                   </tr>
                   @endforeach
                  @endif
                  </tbody>
                </table>
                </form>
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

  
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

@include("master/footer_table")
@include("project::app")
<script type="text/javascript">
  $.ajaxSetup({
    headers: {
        'X-CSRF-Token': $('meta[name=csrf-token]').attr('content')
      }
    });
  // $('#example3').DataTable({
  //     'paging'      : false,
  //     'lengthChange': false,
  //     'searching'   : true,
  //     'ordering'    : false,
  //     'info'        : true,
  //     'autoWidth'   : false,
  //     fixedColumns:   {
  //         leftColumns: 4
  //     }
  //   })

    $(document).ready(function () {
      var id_project = $("#id_project").val();
        $('#table_kawasan').DataTable({
          // 'paging'      : false,
          // 'lengthChange': false,
          'searching'   : true,
          'ordering'    : true,
          // 'info'        : true,
          // 'autoWidth'   : false,
            "processing": true,
            "serverSide": true,
            "ajax":{
                     "url": "{{ url('/project/allposts') }}",
                     "dataType": "json",
                     "type": "post",
                     "data":{ id_project: id_project}
                   },
                   
            "columns": [
                { "data": "kawasan" },
                { "data": "luas_lahan_bruto" },
                { "data": "luas_lahan_netto" },
                { "data": "jumlah_blok" },
                { "data": "jumlah_unit" },
                { "data": "status_lahan" },
                { "data": "edit_blok" },
                { "data": "edit_kawasan" },
                { "data": "delete" },
            ]	 
        });
    });

</script>
</body>
</html>
