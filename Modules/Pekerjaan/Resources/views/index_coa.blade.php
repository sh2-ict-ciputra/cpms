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

  @if ($user->level == "superadmin")
    @include("master/sidebar")
  @else
    @include("master/sidebar_project")
  @endif

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>Data Item Pekerjaan</h1>

    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            
            <!-- /.box-header -->
            <div class="box-body">
              <div class="col-md-6"><br>
                <!-- <a href="{{ url('/')}}/pekerjaan/add" class="btn-lg btn-primary"><i class="glyphicon glyphicon-plus-sign"></i>Tambah Item Pekerjaan</a> -->
              </div>
              <div class="col-md-12">
            	<table id="example3" class="table table-bordered table-hover">
                <thead>
                <tr style="background-color: greenyellow;">
                  <th>No</th>
                  <th>Project</th>
                  <th>Pt</th>
                  <th>Detail</th>
                </tr>
                </thead>
                <tbody>
                @foreach ( $project_pt as $key => $value )
                <tr>
                  <td>{{ $key+1 }}</td>
                  <td>
                    @if($value->project != null)
                        <span class="labels" id="label_{{ $value->id}}">{{ $value->project->name}}</span>
                    @else
                        <span class="labels" id="label_{{ $value->id}}">--</span>
                    @endif
                  	
                  </td>
                  <td>
                    @if($value->pt != null)
                        <span class="labels" id="label_{{ $value->id}}">{{ $value->pt->name }}</span>
                    @else
                        <span class="labels" id="label_{{ $value->id}}">--</span>
                    @endif
                  </td>                  
                  <td>
                  	<a href="{{ url('/')}}/pekerjaan/coa/detail/?id={{ $value->id }}" class="btn btn-success">Detail COA</a>
                  </td>
                </tr>
                @endforeach
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
@include("pt::app")
</body>
</html>
