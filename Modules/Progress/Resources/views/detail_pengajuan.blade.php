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

  @include("master/sidebar_progress")

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
              <form action="{{ url('/')}}/progress/pengajuan/persetujuan-pengajuan" method="post">
                {{ csrf_field() }}
                <input type="hidden" value="{{Crypt::encryptString($pengajuan->id)}}" name="id">
                <h3 class="box-title">Pengajuan Surat Pengecekan IPK</h3>  
                <div class="box-header ">
                  <table class="table" style="font-size:18px;">
                    <thead>
                        <tr>
                        <td style="width:20%">No Pengajuan</td>
                        <td>:</td>
                        <td style="">
                          {{$pengajuan->no}}
                        </td>
                      </tr>
                      <tr>
                        <td style="">No Unit</td>
                        <td>:</td>
                        <td style="">
                          @if($pengajuan->tender_unit->rab_unit->asset != '')
                            {{$pengajuan->tender_unit->rab_unit->asset->name}}
                          @else
                            Fasilitas Kota
                          @endif
                        </td>
                      </tr>
                      <tr>
                        <td style="">Pekerjaan</td>
                        <td>:</td>
                        <td style="">
                          @if($pengajuan->tipe == "spk")
                            {{$pengajuan->progress->itempekerjaan->name}}
                          @else
                            {{$pengajuan->progress_vo->itempekerjaan->name}}
                          @endif
                        </td>
                      </tr>
                      <tr>
                        <td style="">Progress Tahap</td>
                        <td>:</td>
                        <td style="">
                          @if($pengajuan->tipe == "spk")
                            {{$pengajuan->progress->name}}
                          @else
                            {{$pengajuan->progress_vo->name}}
                          @endif

                        </td>
                      </tr>
                      <tr>
                        <td style="">Tanggal Permintaan di cek</td>
                        <td>:</td>
                        <td style="">
                            {{date("d-M-Y",strtotime($pengajuan->date_pengecekan))}}
                        </td>
                      </tr>
                      </tr>
                        <tr>
                        <td style="">Keterangan</td>
                        <td>:</td>
                        <td>
                          <div class="form-group">
                            <textarea class="form-control" style="min-width: 70%; margin: 0px; min-height: 50px; max-height: 50px; height: 50px; max-width: 70%; width: 70%;" rows="7" id="keteranagn" name="keterangan" readonly>{{$pengajuan->description}}</textarea>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td style="">Tanggal di cek</td>
                        <td>:</td>
                        <td style="">
                        @if(isset($pengajuan->date_pengecekan_disetujui))
                          <input type="text" id="tglpengecekan" name="tglpengecekan" class="" style="width:20%;margin-right:none" value="{{date('d-M-Y',strtotime($pengajuan->date_pengecekan_disetujui))}}" disabled><i class="fa fa-fw fa-calendar"></i>
                        @else
                          <input type="text" id="tglpengecekan" name="tglpengecekan" class="" style="width:20%;margin-right:none" value="{{date('d-M-Y',strtotime($pengajuan->date_pengecekan))}}" ><i class="fa fa-fw fa-calendar"></i>
                        @endif
                        </td>
                      </tr>
                      </tr>
                        <tr>
                        <td style="">Keterangan/Alasan Disetujui</td>
                        <td>:</td>
                        <td>
                          <div class="form-group">
                            @if($pengajuan->status_pengajuan != 1)
                              <textarea class="form-control" style="min-width: 70%; margin: 0px; min-height: 50px; max-height: 50px; height: 50px; max-width: 70%; width: 70%;" rows="7" id="keterangan_disetujui" name="keterangan_disetujui" ></textarea>
                            @else
                              <textarea class="form-control" style="min-width: 70%; margin: 0px; min-height: 50px; max-height: 50px; height: 50px; max-width: 70%; width: 70%;" rows="7" id="keterangan_disetujui" name="keterangan_disetujui" readonly>{{$pengajuan->description_disetujui}}</textarea>
                            @endif
                          </div>
                        </td>
                      </tr>
                    </thead>
                  </table>
                </div>
                <center>
                @if($pengajuan->status_pengajuan != 1)
                  <button id="btn_simpan" class="btn btn-primary">Simpan</button>
                @endif
                </center>
              </form>
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
@include('form.general_form')
@include("rekanan::user.app")
<!-- Select2 -->
<script src="{{ url('/')}}/assets/bower_components/select2/dist/js/select2.full.min.js"></script>
<script src="https://cdn.ckeditor.com/4.11.3/standard/ckeditor.js"></script>
<script type="text/javascript">
  var enddate = '';
  // var tglperpanjang = '';
  var selisih = 0;
  var jmlhari = 0;

   $(function () {
    $(".select2").select2();
    $('.unit').select2();
    $(".pekerjaan").select2();
    $(".progress").select2();
    CKEDITOR.replace( 'keterangan' );
    CKEDITOR.replace( 'keterangan_disetujui' );
  });
  //   $(document).ready(function() {
  //     $('.js-example-basic-multiple').select2();
  // });
  $(document).ready(function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('input[name=_token]').val()
        }
      });

    var enddate = $('#enddate').val();
    $('#phari').hide();

    $('#tglpengecekan').datepicker({
        dateFormat: 'dd/M/yy',
    })
    // $('#tglperpanjang').change(function(){
    // tglperpanjang = $('#tglperpanjang').val();
    // // varibel miliday sebagai pembagi untuk menghasilkan hari
    // var miliday = 24 * 60 * 60 * 1000;
    // //buat object Date
    // var tanggal1 = new Date(enddate);
    // var tanggal2 = new Date(tglperpanjang);
    // // Date.parse akan menghasilkan nilai bernilai integer dalam bentuk milisecond
    // var tglPertama = Date.parse(tanggal1);
    // var tglKedua = Date.parse(tanggal2);
    // selisih = (tglKedua - tglPertama) / miliday;
    // // console.log(selisih);
    // jmlhari = selisih;
    // $('#jmlhari').val(selisih);
    // $('#phari').show();
    // });

  });

  // $('#btn_simpan').click(function(){
  //     var _url = '{{ url("/")}}/rekanan/spk/input-perpanjang';
  //     for (instance in CKEDITOR.instances) {
  //         CKEDITOR.instances[instance].updateElement();
  //        }
  //     var isian = $('#isian1').val();
  //     var idspk = $('#idspk').val();  
       
  //     if(tglperpanjang==''||isian==''){
  //       alert('Harap Mengisi isian');
  //     }else{
  //       $.ajax({
  //             type : "POST",
  //             url  : _url,
  //             dataType : "JSON",
  //             data :{
  //              isian:isian,
  //              idspk:idspk,
  //              tglperpanjang:tglperpanjang,
  //              jmlhari:jmlhari
  //             },
  //             success : function(data){
  //                 alert(data.success);
  //                 window.location.replace('{{ url("/")}}/rekanan/user/spk');
  //                 // location.reload();
  //             }     
  //           });
  //       // console.log(tglperpanjang);
  //       // console.log(jmlhari);
  //       // console.log(isian);
  //     }
  //   })

    $(document).on('change', '.pekerjaan', function() {
        var parent_id = $(this).val();
        var id_spk = $("#id_spk").val();
        var id_unit = $("#unit").val();
        var _url = "{{ url('/rekanan/spk/progress_tahap') }}";
        var _data = {
            itempekerjaan_id : parent_id,
            spk_id : id_spk,
            unit_id : id_unit
        };
        var parent_div = $('#progress');
        $.ajax({
            type: 'post',
            dataType: 'json',
            url: _url,
            data: _data,
            beforeSend: function() {
                waitingDialog.show();

            },
            success: function(data) {
              // console.log(data);
                var strItemOption = '';
                if (data.progress_tahap != null) {
                    parent_div.find('option').remove();
                    strItemOption += '<option value="0">Pilih Progress Tahap</option>';
                    $(data.progress_tahap).each(function(i, v) {
                      console.log(v);
                        strItemOption += '<option value="' + v.id + '"">' + v.name + ' | ' + v.volume +'' + v.satuan +'</option>';
                    });
                    parent_div.append(strItemOption);
                }
            },
            complete: function() {
                waitingDialog.hide();
            }
        });
    });

</script>
</body>
</html>
