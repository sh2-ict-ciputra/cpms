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
                <a class="btn btn-warning" href="{{ url('/')}}/progress/sik?idspk={{ $spk->id}}">Kembali</a>
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
                <input class="form-control" type="hidden" name="id_spk" id="id_spk" value="{{$spk->id}}">
                <input class="form-control" type="hidden" name="id_item" id="id_item" value="">
              </div> 
              <!--  <div class="box-footer">
                <i class="fa fa-refresh ld ld-spin" id="loading" style="display: none;"></i> -->
                <!-- <button type="button" class="btn btn-success submitbtn" id="simpan">Simpan IPK</button> -->
                <!-- <a class="btn btn-warning" href="{{ url('/')}}/workorder">Kembali</a> -->
              </div>                
                      
              <!-- /.form-group -->
            </div>

            <div class="row">
              <!-- <div class="col-md-3"></div> -->
              <div class="col-md-8">
                <!-- <form action="{{ url('/')}}/progress/input-siknonbiaya" method="post"> -->
                  <div class="form-group">
                    <label for="exampleFormControlTextarea3">Isian</label>
                    <textarea class="form-control" style="height: 33px;min-width: 250px; margin: 0px; min-height: 250px; max-height: 350px; height: 250px; max-width: 350px width: 350px;" rows="7" id="isian1" name="isian"></textarea>
                  </div>
                  <div class="form-group">
                    <center>
                      <button type="button" class="btn btn-primary submitbtn" id="btn_submit">Simpan</button>
                    </center>
                  </div>
                <!-- </form> -->
              </div>
            </div>
            <!--   <a href="{{ url('/')}}/sik-biaya?idspk={{$spk->id}}&idunit=" class="btn btn-primary">Tambah SIK Berbiaya</a> 
              <a href="{{ url('/')}}/sik-nonbiaya?idspk={{$spk->id}}" class="btn btn-warning">Tambah SIK Non Biaya</a>
 -->              <!-- <i class="glyphicon glyphicon-plus-sign"></i> -->
              
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
@include("workorder::app")
<!-- Select2 -->
<script src="{{ url('/')}}/assets/plugins/timepicker/bootstrap-timepicker.min.js"></script>
<script src="https://cdn.ckeditor.com/4.11.3/standard/ckeditor.js"></script>
<script src="{{ url('/')}}/assets/bower_components/select2/dist/js/select2.full.min.js"></script>
<script type="text/javascript">
  $(function () {
    $(".select2").select2();
    $('.timepicker').timepicker({
      format: 'HH:mm'
    });
     CKEDITOR.replace( 'isian' );
  });
   $(document).ready(function(){

      $('#btn_submit').click(function(){
           input();
      });
    });

   function input(){
      var _url = '{{ url("/")}}/progress/input-siknonbiaya';
      // var idipk = $('#yes').val();
      for (instance in CKEDITOR.instances) {
        CKEDITOR.instances[instance].updateElement();
       }
       
      var isian = $('#isian1').val();
      var idspk = $('#id_spk').val();
      if(isian==''){
        alert("Data Harus Diisi");
      }else{

        $.ajax({
            type : "POST",
            url  : _url,
            dataType : "JSON",
            data :{
             isian:isian,
             idspk:idspk
            },
            success : function(data){
                alert(data.success);
                 location.reload();
            }     
          });
          return false;
        }
    }
</script>
</body>
</html>
