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

  @include("master/sidebar_project")

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
              <div class="col-md-12 table-responsive">
              <!-- <i class="glyphicon glyphicon-plus-sign"></i> -->
                <table id="example2" class="table table-bordered table-hover">
                  <thead class="head_table">
                  <tr>
                    <th>No.</th>
                    <th>No. SPK</th>
                    <th>No. SIK </th>
                    <th>No. Vo </th>
                    <th>Tanggal SIK</th>
                    <th>Status</th>
                    <th>Aksi</th>
                    <th>Status Vo</th>
                  </tr>
                  </thead>
                  <tbody>
                    @php ($no =0)
                    @foreach ($allsik as $key)
                      @if(\Modules\Approval\Entities\Approval::where("document_type", "Modules\Progress\Entities\Siks")->where("document_id", $key->id)->first() != null)
                        <tr>
                          <td>{{ $no+1 }}</td>
                          <td>{{ $key->spk->no }}</td>
                          <td>{{ $key->no_sik }}</td>
                          <td>
                          @if($key->vo!='')
                          {{ $key->vo->no }}
                          @endif
                          </td>
                          <td>{{ date("d/M/Y", strtotime($key->tgl_sik)) }}</td>
                          <td>{{ $key->status_sik->name }}</td>
                          @if ($key->status_sik->id == 1 || $key->status_sik->id == 3)
                            <td>
                              <a href="{{ url('/')}}/spk/sik/detailsikbiaya?idsik={{$key->id}}&id_spk={{$key->spk->id}}" class="btn btn-warning">Detail</a>
                            </td>
                            @else
                            <td>
                              <a href="{{ url('/')}}/spk/sik/detailsiknon?idsik={{$key->id}}&id_spk={{$key->spk->id}}" class="btn btn-warning">Detail</a>
                            </td>
                          @endif
                          <td>
                            @if($key->vo!='')
                              @if($key->vo->approval != '')
                                @if($key->vo->approval->approval_action_id == 1)
                                  <strong style="color:orange;"> Dalam Proses </strong>
                                @elseif($key->vo->approval->approval_action_id == 6)
                                  <strong style="color:green;"> Approved </strong>
                                @elseif($key->vo->approval->approval_action_id == 7)
                                  <strong style="color:red;"> Rejected </strong>
                                @endif
                              @endif
                            @endif
                          </td>
                        </tr>
                        @php ($no++)
                      @endif
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
<script>

</script>
</body>
</html>
