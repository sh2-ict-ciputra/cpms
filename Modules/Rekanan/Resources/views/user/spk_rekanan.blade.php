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

  @include("master/sidebar_rekanan")

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>Data SPK</h1>

    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
   
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example2" class="table table-bordered table-hover">
                <thead class="head_table">
                <tr>
                  <th>No. SPK </th>
                  <th>COA</th>
                  <th>Pekerjaan</th>
                  <th>Department From</th>
                  <th>Nilai</th>
                  <th>Tanggal</th>
                  <th>Aksi</th>
                </tr>
                </thead>
                <tbody>
                  @foreach ( $spk_rekanan as $key => $value )
                    @if ( $value->tender != "" )
                      <tr>
                        <td>{{ $value->no }}</td>
                        <td>{{ $value->itempekerjaan->code or '' }}</td>
                        <td>{{ $value->name }}</td>
                        <td>{{ $value->tender->rab->workorder->departmentFrom->name or '' }}</td>
                        <td>{{ number_format($value->nilai) }}</td>
                        <td>{{ $value->date->format("d/M/Y") }}</td>
                        <td><a href="{{ url('/')}}/rekanan/spk/detail?id={{ $value->id }}" class="btn btn-warning">Detail</a></td>
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
@include("rekanan::user.app")
<!-- Select2 -->
<script src="{{ url('/')}}/assets/bower_components/select2/dist/js/select2.full.min.js"></script>
</body>
</html>
