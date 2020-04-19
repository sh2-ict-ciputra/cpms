<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Admin QS | Dashboard </title>
  @include("master/header")
  <!-- Select2 -->  
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="{{ url('/')}}/assets/bower_components/select2/dist/css/select2.min.css">
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  @include("master/sidebar_project")

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>Data Proyek <strong>{{ $project->name }}</strong></h1>

    </section>

    <!-- Main content -->
    <section class="content">

      <!-- SELECT2 EXAMPLE -->
      <div class="box box-default">

        <!-- /.box-header -->
        <div class="box-body">
          <div class="row">
            <div class="col-md-12"><h3 class="box-title">Detail Data Draft Budget Tambahan</h3></div>
   
            <!-- /.col -->
            <div class="col-md-12">

                <div class="form-group">                  
                  <a class="btn btn-warning" href="{{ url('/')}}/budget/detail/?id={{ $budget->id }}">Kembali</a>
                </div>
                {{ csrf_field() }}
                <table class="table">
                  <thead class="head_table">
                    <tr>
                      <td>No.</td>
                      <td>Diajukan oleh</td>
                      <td>Tanggal Diajukan</td>
                      <td>Nilai</td>
                      <td>Dokumen Acuan</td>
                      <td>Status Approval</td>
                      <td>Detail</td>
                    </tr>
                  </thead>
                  <tbody id="itemlist">
                    @foreach ( $budget_draft as $key => $value )
                    @php
                      $array = array (
                        "6" => array("label" => "Disetujui", "class" => "label label-success"),
                        "7" => array("label" => "Ditolak", "class" => "label label-danger"),
                        "1" => array("label" => "Dalam Proses", "class" => "label label-warning")
                      )
                    @endphp
                    <tr>
                      <td>{{ $value->no }}</td>
                      <td>{{ $value->created_at->format('d/m/Y') }}</td>
                      <td>{{ \Modules\User\Entities\User::find($value->created_by)->user_name }}</td>
                      <td style="text-align: right;">{{ number_format($value->nilai,2)}}</td>                      
                      <td><a href="{{ url('/')}}/workorder/detail/?id={{ $value->workorder->id }}" class="btn btn-primary">{{ $value->workorder->no }}</a></td>
                      <td>
                        <span class="{{ $array[$value->approval->approval_action_id]['class'] }}">  
                          {{ $array[$value->approval->approval_action_id]['label'] }}
                        </span>
                      <td>
                        <a href="{{ url('/')}}/budgetdraft/detail?id={{ $value->id }}" class="btn btn-warning">Detail</a>
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>

            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->
        </div>
        <!-- /.box-body -->

      </div>
      <!-- /.box -->


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
@include("pt::app")
<!-- Select2 -->

</body>
</html>
