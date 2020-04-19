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

  @include("master/sidebar_simulasi")

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>Data Voucher</h1>

    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
   
            <!-- /.box-header -->
            <div class="box-body">
              <form action="{{ url('/')}}/simulasi/store" method="post" name="form1"> 
                {{ csrf_field() }}
                <div class="col-md-12">
                <h4>Voucher Terbayar = {{ number_format($terbayar,2)}}</h4>
                <h4>Voucher blm dibayar = {{ number_format($blm,2)}}</h4>
                <button class="btn btn-primary" type="submit">Simpan</button>
              	<table class="table table-bordered table-hover">
                  <thead>
                  <tr style="background-color: greenyellow;">
                    <th>No.</th>
                    <th>No. Voucher </th>
                    <th>Nilai</th>
                    <th>Project</th>
                    <th>Departemen</th>
                    <th>No. SPK</th>
                    <th>Status Bayar</th>
                  </tr>
                  </thead>
                  <tbody>
                  @php $start = 0; @endphp
                  @foreach ( $voucher as $key => $value )
                  @if ( $value->project_id == 9 )
                  @if ( $value->nilai > 0 )
                    <tr>
                      <td>{{ $start + 1  }}</td>
                      <td>{{ $value->no }}</td>
                      <td>{{ number_format($value->nilai) }}</td>
                      <td>{{ $value->project->name }}</td>
                      <td>{{ $value->department->name }}</td>
                      <td>{{ $value->bap->spk->no  }}</td>
                      <td>
                        @if ( $value->pencairan_date == "" )
                        <input type="hidden" name="voucher_id[{{$start}}]" value="{{ $value->id}}">
                        <input type="checkbox" name="terbayar[{{$start}}]" value="{{ $value->id }}">

                        @php $start++; @endphp
                        @else
                        <input type="checkbox" name="terbayar[{{$start}}]" checked> <strong>Terbayar</strong>
                        @endif
                      </td>
                    </tr>
                    @endif
                    @endif
                  @endforeach
                </tbody>
                </table>
              </form>
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
<!-- ./wrapper -->

@include("master/footer_table")
@include("simulasi::app")
<script type="text/javascript">
  $('#example2').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false
    })
</script>
</body>
</html>
