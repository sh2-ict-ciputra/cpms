<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Admin QS | Dashboard</title>
  @include("master/header")

  <link rel="stylesheet" href="{{ url('/')}}/assets/bower_components/select2/dist/css/select2.min.css">
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  @include("master/sidebar_rekanan")

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>Data SPK</h1>
      {{ csrf_field() }}
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
   
            <!-- /.box-header -->
            <div class="box-body">
              <div class="col-md-12">
                <h3 class="box-title">Pengajuan Surat Perpanjangan Waktu</h3>  
                <div class="box-header ">
                <table class="table" style="font-size:18px;">
                  <thead>
                    <tr>
                      <td style="font-weight:bold">End Date</td>
                      <td>:</td>
                      <td style="font-weight:bold">
                        <input type="hidden" id="idspk" value="{{$spk->id}}" name="">
                        @if($perpanjanganspk==null)
                          {{ date('d/M/Y',strtotime($spk->finish_date)) }}
                          <input type="hidden" id="enddate" value="{{date('d/M/Y',strtotime($spk->finish_date))}}" name="">

                        @elseif($perpanjanganspk!=null)
                          @if($perpanjanganspk->tanggal_disetujui!=null)
                            {{ date('d/M/Y',strtotime($perpanjanganspk->tanggal_disetujui)) }}
                            <input type="hidden" id="enddate" value="{{date('d/M/Y',strtotime($perpanjanganspk->tanggal_disetujui))}}" name="">
                          @else
                            {{ date('d/M/Y',strtotime($spk->finish_date)) }}
                            <input type="hidden" id="enddate" value="{{date('d/M/Y',strtotime($spk->finish_date))}}" name="">
                          @endif
                        @else
                          {{ date('d/M/Y',strtotime($spk->finish_date)) }} 
                          <input type="hidden" id="enddate" value="{{date('d/M/Y',strtotime($spk->finish_date))}}" name="">
                        @endif

                    
                      </td>
                    </tr>
                    <tr>
                      <td style="font-weight:bold">Request Perpanjangan</td>
                      <td>:</td>
                      <td>
                        <!-- <div class="form-group"> -->
                          <input type="text" id="tglperpanjang" name="tglperpanjang"><i class="fa fa-fw fa-calendar"></i>
                          <p class="text-mutted" id="phari"> (<input style="background-color: transparent; border: none; width: 30px" type="text" id="jmlhari" name="" readonly> Hari)</p>
                        <!-- </div> -->
                      </td>
                    </tr>
                      <tr>
                      <td style="font-weight:bold">Alasan</td>
                      <td>:</td>
                      <td>
                        <div class="form-group">
                          <textarea class="form-control" style="height: 33px;min-width: 250px; margin: 0px; min-height: 250px; max-height: 350px; height: 250px; max-width: 350px width: 350px;" rows="7" id="isian1" name="isian"></textarea>
                        </div>
                      </td>
                    </tr>
                  </thead>
                </table>
              </div>
              <center>
                <button id="btn_simpan" class="btn btn-primary">Simpan</button>
              </center>
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
@include("rekanan::user.app")
<!-- Select2 -->
<script src="{{ url('/')}}/assets/bower_components/select2/dist/js/select2.full.min.js"></script>
<script src="https://cdn.ckeditor.com/4.11.3/standard/ckeditor.js"></script>
<script type="text/javascript">
  var enddate = '';
  var tglperpanjang = '';
  var selisih = 0;
  var jmlhari = 0;

   $(function () {
    $(".select2").select2();
     CKEDITOR.replace( 'isian' );
  });

  $(document).ready(function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('input[name=_token]').val()
        }
      });

    var enddate = $('#enddate').val();
    $('#phari').hide();

    $('#tglperpanjang').change(function(){
    tglperpanjang = $('#tglperpanjang').val();
    // varibel miliday sebagai pembagi untuk menghasilkan hari
    var miliday = 24 * 60 * 60 * 1000;
    //buat object Date
    var tanggal1 = new Date(enddate);
    var tanggal2 = new Date(tglperpanjang);
    // Date.parse akan menghasilkan nilai bernilai integer dalam bentuk milisecond
    var tglPertama = Date.parse(tanggal1);
    var tglKedua = Date.parse(tanggal2);
    selisih = (tglKedua - tglPertama) / miliday;
    // console.log(selisih);
    jmlhari = selisih;
    $('#jmlhari').val(selisih);
    $('#phari').show();
    });

  });

  $('#btn_simpan').click(function(){
      var _url = '{{ url("/")}}/rekanan/spk/input-perpanjang';
      for (instance in CKEDITOR.instances) {
          CKEDITOR.instances[instance].updateElement();
         }
      var isian = $('#isian1').val();
      var idspk = $('#idspk').val();  
       
      if(tglperpanjang==''||isian==''){
        alert('Harap Mengisi isian');
      }else{
        $.ajax({
              type : "POST",
              url  : _url,
              dataType : "JSON",
              data :{
               isian:isian,
               idspk:idspk,
               tglperpanjang:tglperpanjang,
               jmlhari:jmlhari
              },
              success : function(data){
                  alert(data.success);
                  window.location.replace('{{ url("/")}}/rekanan/spk/detail?id='+idspk);
                  // location.reload();
              }     
            });
        // console.log(tglperpanjang);
        // console.log(jmlhari);
        // console.log(isian);
      }
    })

</script>
</body>
</html>
