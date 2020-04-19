<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
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
        Library
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Library Menu</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="form-group">
                <a class="text-left" href="{{ url('/library/item-pekerjaan')}}" >1. Harga Satuan</a><br>
                <a class="text-left" href="{{ url('/library/supplier')}}" >2. Supplier</a><br>
                <a class="text-left" href="{{ url('/library/mou')}}" >3. MOU</a><br>
                <a class="text-left" href="{{ url('/library/mou')}}" >4. Analisis Harga Satuan</a><br>
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

  
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

@include("master/footer_table")
@include("library::app")
<!-- Select2 -->
<script src="{{ url('/')}}/assets/bower_components/select2/dist/js/select2.full.min.js"></script>

</body>
</html>
