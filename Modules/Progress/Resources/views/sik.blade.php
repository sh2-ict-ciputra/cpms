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
              <div class="row">
            <div class="box-header">
              <div class="col-md-12">
                <!-- <a class="btn btn-warning" href="{{ url('/')}}/progress/">Kembali</a> -->
              <div class="box-header ">
                <table class="table" style="font-size:18px;font-weight:bold">
                  <thead>
                    <tr>
                      <td>No. SPK</td>
                      <td>:</td>
                      <td>{{$spk->no}}</td>
                    </tr>
                      <tr>
                      <td>Project / Kawasan</td>
                      <td>:</td>
                      <td>{{$spk->project->name}} </td>
                    </tr>
                    <tr>
                      <td>Pengawas</td>
                      <td>:</td>
                      <td>{{ $spk->user_pic->user_name or '' }}</td>
                    </tr>
                    <tr>
                      <td>Pekerjaan</td>
                      <td>:</td>
                      <td>{{$spk->name}} </td>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>
          </div>
            <div class="col-md-6">   

              <!-- <h3 class="box-title">Tambah Data IPK</h3>   -->
              <!-- <form action="{{ url('/')}}/spk/save" method="post" name="form1"> -->
                {{ csrf_field() }}
              <div class="form-group">
                <input class="form-control" type="hidden" name="id_spk" id="id_spk" value="">
                <input class="form-control" type="hidden" name="id_item" id="id_item" value="">
              </div> 
              <!--  <div class="box-footer">
                <i class="fa fa-refresh ld ld-spin" id="loading" style="display: none;"></i> -->
                <!-- <button type="button" class="btn btn-success submitbtn" id="simpan">Simpan IPK</button> -->
                <!-- <a class="btn btn-warning" href="{{ url('/')}}/workorder">Kembali</a> -->
              </div>                
                      
              <!-- /.form-group -->
            </div>
              <a href="{{ url('/')}}/progress/sik-biaya?idspk={{$spk->id}}" class="btn btn-primary">Tambah SIK Berbiaya</a> 
              <a href="{{ url('/')}}/progress/sik-nonbiaya?idspk={{$spk->id}}" class="btn btn-warning">Tambah SIK Non Biaya</a>
              <!-- <i class="glyphicon glyphicon-plus-sign"></i> -->
              <table id="example2" class="table table-bordered table-hover">
                <thead class="head_table">
                <tr>
                  <th>No.</th>
                  <th>No. SIK </th>
                  <th>Tanggal SIK</th>
                  <th>Status</th>
                  <!-- <th>Status Approval</th> -->
                  <th>Aksi</th>
                  <th>Status Approval</th>
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
                    @if ($key->status_sik->id ==1 || $key->status_sik->id == 3)
                    <td>
                      <!-- <input class="form-control" type="hidden" name="id_spk" id="id_spk" value="{{ $idspk }}"> -->
                      <!-- <span class="label label-warning">Rilis</span>   -->
                      <a href="{{ url('/')}}/progress/detailsikbiaya?idsik={{$key->id}}&id_spk={{$idspk}}" class="btn btn-warning">Detail</a>
                    </td>
                    @else
                    <td>
                      <a href="{{ url('/')}}/progress/detailsiknon?idsik={{$key->id}}&id_spk={{$idspk}}" class="btn btn-warning">Detail</a>
                    </td>
                  @endif
                  <td>
                    @if($key->approval != '')
                      @if($key->approval->approval_action_id == 1)
                        <strong style="color:orange;"> Dalam Proses </strong>
                      @elseif($key->approval->approval_action_id == 6)
                        <strong style="color:green;"> Approved </strong>
                      @elseif($key->approval->approval_action_id == 7)
                        <strong style="color:red;"> Rejected </strong>
                      @endif
                    @endif
                  </td>
                  </tr>

                  @php ($no++)
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
