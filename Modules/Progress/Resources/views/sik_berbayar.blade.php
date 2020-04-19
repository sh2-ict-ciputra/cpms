<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Admin QS | Dashboard</title>
  @include("master/header")
  <!-- Select2 -->
  <link rel="stylesheet" href="{{ url('/')}}/assets/bower_components/select2/dist/css/select2.min.css">
  <style>
    .table_sik thead tr th{
      text-align: center;
      vertical-align: middle;
    }
  </style>
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
          {{-- <form class="form-horizontal" action="{{ url('/progress/input-sikbiaya') }}" method="post"        autocomplete="off" name="form-oe" id="form-oe"> --}}
            {{ csrf_field() }}
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
                            <tr>
                              <td>No. Unit/Kawasan</td>
                              <td>:</td>
                              <td>
                                <select class='form-control select2' name='unit_kawasan[]' id='unit_kawasan' style="width:40%" multiple="multiple">
                                  @foreach ( $spk->tender->units as $key => $value )
                                    @if($value->rab_unit->asset != '')
                                      <option value="{{$value->id}}">{{$value->rab_unit->asset->name}}</option>
                                    @else
                                      <option value="{{$value->id}}">Fasilitas kota</option>
                                    @endif
                                  @endforeach
                                </select> 
                                <input type="checkbox" class="get_value" name="" id="yes"><strong>Pilih Semua</strong>
                              </td>
                            </tr>
                            {{-- <tr>
                              <td>Tipe SIK Berbiaya</td>
                              <td>:</td>
                              <td>
                                <select class='form-control' name='tipe' id='tipe' style="width:40%">
                                  <option value='1' >Penambahan</option>
                                  <option value='3' >Pengurangan</option>
                                </select> 
                              </td>
                            </tr> --}}
                          </thead>
                        </table>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">   
                      {{-- {{ csrf_field() }} --}}
                    <div class="form-group">
                      <input class="form-control" type="hidden" name="id_spk" id="id_spk" value="{{$spk->id}}">
                      <input class="form-control" type="hidden" name="id_item" id="id_item" value="">
                    </div> 
                  </div>                
                        
                <!-- /.form-group -->
                </div>
                <input class="form-control" type="hidden" name="id_spk" id="id_spk" value="{{$spk->id}}">
                <table id="table_sik" class="table table-bordered table-hover table_sik">
                  <thead class="head_table" >
                  <tr>
                    <th style="width : 5%">No.</th>
                    <th style="width : 10%">Coa </th>
                    <th style="width : 20%">Item Pekerjaan</th>
                    <th style="width : 15%">Volume Spk</th>
                    <th style="width : 15%">Volume (Tambah\Kurang)</th>
                    <th style="width : 10%">Satuan</th>
                    <th style="width : 25%">Keterangan</th>
                  </tr>
                  </thead>
                  <tbody>
                  @php ($no = 1); @endphp
                  @foreach( $spk->tender_rekanan->menangs->first()->details->where("volume","!=",0) as $key2 => $value2 )
                    <tr class="test">
                      @php 
                        $itempekerjaan = \Modules\Pekerjaan\Entities\Itempekerjaan::find($value2->itempekerjaan_id);
                      @endphp
                      <td><strong>{{ $no }}</strong></td>
                      <td><strong>{{ $itempekerjaan->code }}</strong></td>
                      <td><strong>{{ $itempekerjaan->name }}</strong></td>
                      {{-- <input type="hidden" name="satuan[]" value="{{ $value2->satuan }}" id="id_detail" class="satuan"> --}}
                      <td><input type="text" class="form-control" name="" value="{{ $value2->volume }}" id="" readonly></td>
                      <td>
                        <input type="text" class="form-control volume" name="vol[]" value="" id="vol" readonly>
                      </td>
                      <td>
                        <strong>{{ $value2->satuan }}</strong>
                        <input type="hidden" name="id_item[]" value="{{ $itempekerjaan->id }}" id="id_detail" class="itempekerjaan_id">
                        <input type="hidden" name="" value="{{ $value2->satuan }}" id="" class="satuan">
                      </td>
                      <td>
                        {{-- <textarea type="" class="form-control" name="ket[]" value="" id="ket" style="height: 33px;min-width: 180px; margin: 0px; min-height: 92px; max-height: 150px; height: 92px; max-width: 300px width: 180px;"></textarea> --}}
                      </td>
                    </tr>
                    @if (count($value2->tender_menang_sub_detail) != 0)
                        <tr class="child">
                          <td colspan="7">
                            <table border="0" style="padding:none;width:100%" class="table child_table {{$value2->itempekerjaan_id}} ">
                              @foreach($value2->tender_menang_sub_detail as $key2 => $value3 )
                                <tr class="test_child">
                                    <td style="width : 5%"></td>
                                    <td style="width : 10%"></td>
                                    <td style="width : 20%">
                                      {{$value3->name}}
                                      <input type="hidden" class="form-control child_name" name="" value="{{$value3->name}}" id="">
                                      <input type="hidden" class="form-control child_nilai" name="" value="{{$value3->nilai}}" id="">
                                    </td>
                                    <td style="text-align:right;width : 15%">
                                      <input type="text" class="form-control" name="" value="{{$value3->volume}}" id="" readonly>
                                    </td>
                                    <td style="text-align:righ;width : 15%">
                                      <input type="text" class="form-control child_volume" name="child_vol[]" value="" id="vol">
                                    </td>
                                    <td style="width : 10%">
                                      {{$value3->satuan}}
                                      <input type="hidden" class="form-control child_satuan" name="" value="{{$value3->satuan}}" id="">
                                    </td>
                                    <td style="width : 25%">
                                      <textarea type="" class="form-control child_keterangan" name="child_ket[]" value="" id="ket" style="height: 33px;min-width: 180px; margin: 0px; min-height: 92px; max-height: 150px; height: 92px; max-width: 300px width: 180px;"></textarea> 
                                    </td>
                                </tr>
                              @endforeach
                            </table>
                          </td>
                        </tr>
                    @endif
                      @php ($no++)
                    @endforeach
                  </tbody>
                </table>
                <div class="form-group">
                  <center>
                    <button type="submit" class="btn btn-primary submitbtn" id="btn_submit">Simpan</button>
                  </center>
                </div>
            
              </div>
                <!-- /.box-body -->
            </div>
          <!-- /.box -->
          {{-- </form> --}}
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
  $(document).ready(function() {
      $.ajaxSetup({
          headers: {
              'X-CSRF-Token': $('input[name=_token]').val()
          }
      });
  });

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

  $(document).on('click', '.submitbtn', function() {
      var main = [];
      $("#table_sik .test").each(function () {

          var id_pekerjaan = $(this).find(".itempekerjaan_id").val();
          var sub = [];
          var total_nilai = 0;
          $("#table_sik ."+id_pekerjaan+" .test_child").each(function () {

              var child_total_nilai =  parseFloat($(this).find(".child_volume").val()) * parseInt($(this).find(".child_nilai").val());
              var arr = [
                  $(this).find(".child_name").val(),
                  $(this).find(".child_volume").val(),
                  $(this).find(".child_satuan").val(),
                  $(this).find(".child_nilai").val(),
                  $(this).find(".child_keterangan").val(),
                  child_total_nilai
              ];
              sub.push(arr);
              // total_nilai = total_nilai + parseInt(child_total_nilai);
              // if(child_total_nilai != "" && child_total_nilai != null && child_total_nilai != 0 && child_total_nilai != undefined && child_total_nilai != NaN){
              //   console.log(child_total_nilai);
              //   total_nilai = total_nilai + child_total_nilai;
              // }else{
              //   // total_nilai = total_nilai ;
              // }

              if(child_total_nilai > 0 || child_total_nilai < 0){
                console.log(child_total_nilai);
                total_nilai = total_nilai + child_total_nilai;
              }else{
                // total_nilai = total_nilai ;
              }
          });
          // console.log(total_nilai);
          var arr2 = [
                  $(this).find(".itempekerjaan_id").val(),
                  $(this).find(".volume").val(),
                  $(this).find(".satuan").val(),
                  total_nilai,
                  sub,
              ];
          main.push(arr2);

      });
      // console.log(main);
      var url = "{{ url('/')}}/progress/input-sikbiaya";
      if($("#unit_kawasan").val() != "" && $("#unit_kawasan").val() != undefined && $("#unit_kawasan").val() != null){
        $.ajax({
            type: 'post',
            dataType: 'json',
            url: url,
            data: {
                data: JSON.stringify(main),
                spk_id: $("#id_spk").val(),
                unit: $("#unit_kawasan").val(),
                // tipe: $("#tipe").val(),
            },
            beforeSend: function() {
            waitingDialog.show();
            },
            success: function(data) { 
                // window.location.reload(true);
                window.location.replace("{{ url('/')}}/progress/sik?idspk="+$("#id_spk").val());
            },
            complete: function() {
            waitingDialog.hide(); 
            },
        });
      }else{
          alert("Data Tidak Lengkap");
          if($("#unit_kawasan").val() == null){
              $("#unit_kawasan").css({"boder":"1px solid red"});
          }
      }
      // window.location.href;
      // window.location.replace(window.location.href);
      // window.location.replace("/rab/detail?id="+$("#rab_id").val()+"&idpkr="+$("#idpkr").val()+"#");
  });

  $(document).on('keyup', '.child_volume', function() {
      var status = 1;
      var total_volume = 0;
      $(this).parents(".child_table").find(".test_child").each(function () {
          if($(this).find(".child_satuan").val() == $(this).parents(".child").prev().find(".satuan").val() && status == 1){
              if($(this).parents(".child").prev().find(".satuan").val() != "Ls"){
                if($(this).find(".child_volume").val() != undefined && $(this).find(".child_volume").val() != null && $(this).find(".child_volume").val() != ""){
                  total_volume += parseFloat($(this).find(".child_volume").val());
                }
              }else{
                  total_volume = 1;
              }
          }else{
              status = 0;
          }
      });
      $(this).parents(".child").prev().find(".volume").val(total_volume);
  });
</script>
</body>
</html>
