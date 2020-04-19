<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Admin QS | Dashboard</title>
  @include("master/header")
  <!-- Select2 -->
  <link rel="stylesheet" href="{{ url('/')}}/assets/bower_components/select2/dist/css/select2.min.css">
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  @include("master/sidebar_progress")

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>Data SIK</h1>

    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Data SIK</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
             <!--  <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal-default">
                Cari Workorder
              </button> -->
              <div class="col-md-12">
              <!-- <i class="glyphicon glyphicon-plus-sign"></i> -->
                <table id="example2" class="table table-bordered table-hover">
                  <thead class="head_table">
                  <tr>
                    <th>No.</th>
                    <th>No. SIK </th>
                    <th>Tanggal SIK</th>
                    <th>Status</th>
                    <th>Aksi</th>
                  </tr>
                  </thead>
                  <tbody>
                    @php ($no =0)
                    @foreach ($allsik as $key)
                    <tr>
                      <td>{{ $no+1 }}</td>
                      <td>{{ $key->no_sik }}</td>
                      <td>{{ date("d/M/Y", strtotime($key->tgl_sik)) }}</td>
                      <td>{{ $key->status_sik->name }}</td>
                      @if ($key->status_sik->id ==1)
                      <td>
                        <!-- <input class="form-control" type="hidden" name="id_spk" id="id_spk" value="{{ $idspk }}"> -->
                        <a href="{{ url('/')}}/progress/detailsikbiaya?idsik={{$key->id}}&id_spk={{$idspk}}" class="btn btn-warning">Detail</a>
                      </td>
                      @else
                      <td>
                        <a href="{{ url('/')}}/progress/detailsiknon?idsik={{$key->id}}&id_spk={{$idspk}}" class="btn btn-warning">Detail</a>
                      </td>
                    </tr>
                    @endif
                    @php ($no++)
                    @endforeach
                  </tbody>
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

  @include("master/copyright")
  <!-- /.modal -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

@include("master/footer_table")
@include("progress::app")
<!-- Select2 -->
<script src="{{ url('/')}}/assets/bower_components/select2/dist/js/select2.full.min.js"></script>
</body>
</html>
