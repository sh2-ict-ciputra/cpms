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
      <h1>Data Proyek <strong>{{ $projectkawasan->project->name }}</strong></h1>

    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ url('/')}}/project/kawasan/">Kawasan {{ $projectkawasan->name }}</a></li>
                <li class="breadcrumb-item active">Blok</li>
              </ol>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive">
              <a href="{{ url('/')}}/project/add-blok?id={{ $projectkawasan->id }}" class="btn btn-primary"><i class="glyphicon glyphicon-plus-sign"></i>Tambah Blok</a>
              <a href="{{ url('/')}}/project/kawasan/" class="btn btn-warning">Kembali</a><br><br>
              <h3>Unit : <strong>{{ number_format($projectkawasan->units->count())}}</strong></h3><br><br>
              <table id="example2" class="table table-bordered table-hover">   
              {{ csrf_field() }}              
              <thead style="background-color: greenyellow;">
                <tr>
                  <td>Blok</td>
                  <td>Luas<br/> Tanah(m2)</td>
                  <td>Luas<br/> Bangunan(m2)</td>
                  <td>Unit</td>
                  <td>Keterangan</td>
                  <td>Edit Unit</td>
                  <td>Edit Blok</td>
                  <td>Delete</td>
                </tr>
              </thead>
                <tbody>
                 @foreach ( $projectkawasan->bloks as $key => $value )
                
                 <tr>
                    <td>{{ $value->name }}</td>
                    <td>{{ number_format($value->total_tanah) }}</td>
                    <td>{{ number_format($value->total_bangunan) }}</td>
                    <td>{{ $value->units->count() }}</td>  
                    <td>{{ $value->description }}</td> 
                    <td><a href="{{ url('/')}}/project/units/?id={{ $value->id }}" class="btn btn-primary">Edit</a></td>             
                    <td><a href="{{ url('/')}}/project/edit-blok?id={{ $value->id }}" class="btn btn-warning">Edit</a></td>
                    <td> 
                    @if($value->units->count() == 0 )
                      <button class="btn btn-danger" onclick="removeblok('{{ $value->id }}','{{ $value->name }}')">Delete</button>
                    @endif
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
  @include("master/copyright")

  
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

@include("master/footer_table")
@include("project::app")
<script type="text/javascript">
  $('#example3').DataTable({
      'paging'      : false,
      'lengthChange': false,
      'searching'   : true,
      'ordering'    : false,
      'info'        : true,
      'autoWidth'   : false,
      fixedColumns:   {
          leftColumns: 4
      }
    });


</script>
</body>
</html>
