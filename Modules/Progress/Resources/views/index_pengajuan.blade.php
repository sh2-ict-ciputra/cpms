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

  @include("master/sidebar_progress")

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">

    </section>

    <!-- Main content -->
    <section class="content">
      <!-- {{ csrf_field() }} -->
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Data Pengajuan SPK </h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example2" class="table table-bordered table-hover">
                <thead class="head_table">
                    <tr>
                        <th>No. Pengajuan </th>
                        <th>No. SPK</th>
                        <th>Pekerjaan</th>
                        <th>No. Unit</th>
                        <th>Tipe</th>
                        <th>Detail</th>
                        <th>Status Approval</th>
                    </tr>
                </thead>
                <tbody>
                  @foreach ( $pengajuan as $key => $value )
                    <tr>
                        <td>{{ $value->no }}</td>
                        @if($value->tipe == "spk")
                          @if($value->progress != '')
                            <td>{{ $value->progress->spk->no }}</td>
                            <td>{{ $value->progress->itempekerjaan->name }}</td>
                            <td>
                              @if($value->tender_unit->rab_unit->asset != '')
                                {{ $value->tender_unit->rab_unit->asset->name }}
                              @else
                                Fasilitas Kota
                              @endif
                            </td>
                          @else
                              <td></td>
                              <td></td>
                              <td></td>
                          @endif
                        @else
                          @if($value->progress_vo != '')
                            <td>{{ $value->progress_vo->spk->no }}</td>
                            <td>{{ $value->progress_vo->itempekerjaan->name }}</td>
                            <td>
                              @if($value->tender_unit->rab_unit->asset != '')
                                {{ $value->tender_unit->rab_unit->asset->name }}
                              @else
                                Fasilitas Kota
                              @endif
                            </td>
                          @else
                              <td></td>
                              <td></td>
                              <td></td>
                          @endif
                        @endif
                        <td>{{ $value->tipe }}</td>
                        <td>
                            <a href="{{ url('/')}}/progress/pengajuan/detail?id={{Crypt::encryptString($value->id)}}" class="btn btn-info" style="">Detail</a>
                        </td>
                        <td>
                        @if($value->status_pengajuan == 0)
                            Belum disetujui
                        @else
                            Sudah di setujui
                        @endif
                        </td>   
                        </td>
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
<!-- ./wrapper -->

@include("master/footer_table")
@include("spk::app")
</body>
</html>
