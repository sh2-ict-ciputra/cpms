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

  @include("master/sidebar_project")

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
                <h3 class="box-title">Percepatan SPK</h3>  
                <input type="hidden" class="form-control right" name="" id="idspk" value="{{$spk->id}}">
                <div class="box-header ">
                  <table class="table" style="font-size:18px;">
                    <thead>
                      <tr>
                        <td style="font-weight:bold;width:27%">No SPK</td>
                        <td>:</td>
                        <td style="font-weight:bold">
                          {{$spk->no}}
                        </td>
                      </tr>
                      <tr>
                        <td style="font-weight:bold">Start Date s/d End Date</td>
                        <td>:</td>
                        <td style="font-weight:bold">
                        {{date('d-M-Y', strtotime($spk->start_date))}} s/d {{date('d-M-Y', strtotime($spk->finish_date))}} ({{$spk->tender->durasi}} hari kerja)
                        </td>
                      </tr>
                      <tr>
                        <td style="font-weight:bold">Nilai SPK per unit/Kawasan</td>
                        <td>:</td>
                        <td style="font-weight:bold">
                          <input type="text" class="form-control right" name="nilai_spk" id="nilai_spk" value="{{number_format($spk->nilai/count($spk->tender->units))}} " style="width:30%" readonly>

                          <input type="hidden" class="form-control right" name="" id="nilai_spk_hitung" value="{{$spk->nilai/count($spk->tender->units)}} " style="width:30%">
                        </td>
                      </tr>
                      <tr>
                        <td style="font-weight:bold">No. Unit/Kawasan</td>
                        <td>:</td>
                        <td>
                          <select class='form-control select2' name='unit_kawasan[]' id='unit_kawasan' style="width:40%" multiple="multiple">
                            @foreach ( $spk->tender->units as $key => $value )
                              @if(empty($value->spkpercepatan_unit))
                                <option value="{{$value->id}}">{{$value->rab_unit->asset->name}}</option>
                              @endif
                            @endforeach
                          </select> 
                          <input type="checkbox" class="get_value" name="" id="yes"><strong>Pilih Semua</strong>
                        </td>
                      </tr>
                      <tr>
                        <td style="font-weight:bold">Nilai %</td>
                        <td>:</td>
                        <td style="font-weight:bold">
                          <input type="text" class="form-control right" name="nilai_percepatan" id="nilai_percepatan" value="" style="width:30%" maxlength="2" required>
                        </td>
                      </tr>
                      <tr>
                        <td style="font-weight:bold">Nilai Rp.</td>
                        <td>:</td>
                        <td style="font-weight:bold">
                          <input type="text" class="form-control right" name="nilai_percepatan_rupiah" id="nilai_percepatan_rupiah" value="" style="width:30%" readonly>
                        </td>
                      </tr>
                      <tr>
                        <td style="font-weight:bold">Tanggal Selesai percepatan</td>
                        <td>:</td>
                        <td>
                          <!-- <div class="form-group"> -->
                            <input type="text" id="tglpercepatan" name="tglpercepatan" class="form-control" style="width:30%;margin-right:3px;" required>
                          <!-- </div> -->
                        </td>
                      </tr>
                        <tr>
                        <td style="font-weight:bold">Keterangan</td>
                        <td>:</td>
                        <td>
                          <div class="form-group">
                            <textarea class="form-control" style="height: 33px;min-width: 250px; margin: 0px; min-height: 250px; max-height: 350px; height: 250px; max-width: 350px width: 300px;" rows="7" id="description" name="description" required></textarea>
                          </div>
                        </td>
                      </tr>
                    </thead>
                  </table>
                </div>
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
@include("spk::app")
<!-- Select2 -->
<script src="{{ url('/')}}/assets/bower_components/select2/dist/js/select2.full.min.js"></script>
<script src="https://cdn.ckeditor.com/4.11.3/standard/ckeditor.js"></script>
<script type="text/javascript">
  var enddate = '';
  var tglperpanjang = '';
  var selisih = 0;
  var jmlhari = 0;

  $('#tglpercepatan').datepicker({
	    	dateFormat: 'dd/M/yy',
	    });
  
  $(document).ready(function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('input[name=_token]').val()
        }
      });
  });

  $(document).on('keyup', '#nilai_percepatan', function() {
    if($(this).val()!=''){
      var admin  = parseInt($(this).val());
    }else{
      var admin = 0;
    }
    var nilai = parseInt($("#nilai_spk_hitung").val()) * (admin/100);
    $("#nilai_percepatan_rupiah").val(nilai).number(true);
  });

  $('#btn_simpan').click(function(){
      var _url = '{{ url("/")}}/spk/save-percepatan';
      for (instance in CKEDITOR.instances) {
          CKEDITOR.instances[instance].updateElement();
         }
      var isian = $('#description').val();
      var idspk = $('#idspk').val(); 
      var nilai = $('#nilai_percepatan').val();
      var tanggal = $('#tglpercepatan').val();
      var unit = $('#unit_kawasan').val();
      
      if(nilai==''||isian==''||tanggal==''||unit==''){
        alert('Harap Mengisi isian');
      }else{
        $.ajax({
              type : "POST",
              url  : _url,
              dataType : "JSON",
              data :{
               isian:isian,
               idspk:idspk,
               nilai:nilai,
               tanggal:tanggal,
               unit:unit
              },
              beforeSend: function() {
                waitingDialog.show();

              },
              success : function(data){
                  alert(data.success);
                  window.location.replace('{{ url("/")}}/spk/detail?id='+idspk);
                  // location.reload();
              },
              complete: function() {
                waitingDialog.hide();
              }     
            });
      }
    })
    $(function () {
      $(".select2").select2();
    });
    $(document).ready(function(){
      var checkboxes = $('input[type=checkbox]');
      $(checkboxes).on('change', function() {
        if($(checkboxes).is(':checked')) {
          $('#unit_kawasan').find('option').attr("selected",true);
          $(".select2").select2();
        }else{
          $('#unit_kawasan').find('option').attr("selected",false);
          $(".select2").select2();
        }
      });
    });
</script>
</body>
</html>
