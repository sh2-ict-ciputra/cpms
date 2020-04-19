<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Admin QS | Dashboard</title>
  @include("master/header")
  <style type="text/css">
    .table-align-right{
      text-align: right;
    }
  </style>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  @include("master/sidebar_project")

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1 style="text-align:center">Data Purchase Request</h1>
    </section>
    <section class="back-button content-header">
      <div class="" style="float: none">
        <button class="col-lg-1 col-md-2 col-sm-2 btn btn-primary" onclick="location.href='{{ url('/')}}/purchaserequest'" style="float: none; border-radius: 20px; padding-left: 0" disabled>
        <i class="fa fa-fw fa-arrow-left"></i>&nbsp;&nbsp;Back
        </button>
        <button class="col-lg-1 col-md-2 col-sm-2 btn btn-primary" onclick="window.location.reload()" style="float: right; border-radius: 20px; padding-left: 0;">
          <i class="fa fa-fw fa-refresh"></i>&nbsp;&nbsp;Refresh
        </button>  
      </div>
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
            <div class="form-group has-feedback">
                <label>Username </label>
                <input type="text" class="form-control" name="user_login" autocomplete="off" id="user">
            </div>
            <div class="form-group has-feedback">
                <label>Password</label>
                <input type="text" class="form-control" name="password" autocomplete="off" id="pass">
            </div>
            <div class="row">
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat" id="tombol">Sign In</button>
                </div>
            <!-- /.col -->
            </div>
            <div class="form-group has-feedback">
                <label>hasil</label>
                <input type="text" class="form-control" name="" autocomplete="off" id="hasil">
            </div>
            <!-- /.box-body -->
            </div>
          <!-- /.box -->
          </div>
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
@include('pluggins.alertify')
<script type="text/javascript">
    $.ajaxSetup({
        headers: {
        'X-CSRF-Token': $('meta[name=csrf-token]').attr('content')
        }
    });
//   $(document).ready(function()
//   {
//   });

  $(document).on('click', '#tombol', function() {
    var item_desk = $(this).val();
        var _url = "{{ url('/purchaserequest/encrypt') }}";
        var user = $('#user').val();
        var pass = $('#pass').val();
        $.ajax({
            type: 'get',
            dataType: 'json',
            url: _url,
            data: {
                pass: pass
            },
            beforeSend: function() {
                waitingDialog.show();
            },
            success: function(data) {
               $("#hasil").val(data.data);
 
            },
            complete: function() {
                waitingDialog.hide();
            }
        });
    });
</script>
</body>
</html>
