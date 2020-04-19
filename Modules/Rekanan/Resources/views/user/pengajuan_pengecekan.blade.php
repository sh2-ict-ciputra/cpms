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
                <h3 class="box-title">Pengajuan Surat Pengecekan IPK</h3>  
                <div class="box-header ">
                <button type="button" class="btn btn-info tambah_pengajuan" style="margin: 5px 5px 10px 5px">
                  Tambah Pengajuan IPK
                </button>
                <table id="example2" class="table table-bordered table-hover">
                  <thead class="head_table">
                  <tr>
                    <th>No. Pengajuan </th>
                    <th>Item Pekerjaan</th>
                    <th>description</th>
                    <th>No. Unit/Kawasan</th>
                    <th>tanggal pengecekan</th>
                    <th>Tipe</th>
                    <th>Status</th>
                  </tr>
                  </thead>
                  <tbody>
                    @foreach($pengajuan as $key => $value)
                      <tr>
                        <td>{{$value->no}}</td>
                        @if($value->tipe == "spk")
                          <td>{{$value->progress->itempekerjaan->name}}</td>
                          <td>{{$value->progress->name}}</td>
                          <td>
                            @if($value->progress->tender_unit->rab_unit->asset != '')
                              {{$value->progress->tender_unit->rab_unit->asset->name}}
                            @else
                              Fasilitas Kota
                            @endif
                          </td>
                        @else
                          <td>{{$value->progress_vo->itempekerjaan->name}}</td>
                          <td>{{$value->progress_vo->name}}</td>
                          <td>
                            @if($value->progress_vo->tender_unit->rab_unit->asset != '')
                              {{$value->progress_vo->tender_unit->rab_unit->asset->name}}
                            @else
                              Fasilitas Kota
                            @endif
                          </td>
                        @endif
                        <td>
                        <!-- {{$value->date_pengecekan_disetujui}} -->
                          @if(isset($value->date_pengecekan_disetujui))
                            @if($value->date_pengecekan_disetujui != $value->date_pengecekan)
                              {{$value->date_pengecekan_disetujui}}<br/>
                              <span style="color:orange">waktu pengecekan dirubah pengawas</span>
                            @else
                              {{$value->date_pengecekan_disetujui}}
                            @endif
                          @else
                            {{$value->date_pengecekan}}
                          @endif
                        </td>
                        <td>{{$value->tipe}}</td>
                        <td>
                          @if($value->status_pengajuan == 1)
                            Sudah Disetujui
                          @else
                            Belum Disetujui
                          @endif
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
                </div>
                <center>
                </center>
              <!-- </form> -->
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

  <div class="modal fade" id="ModalTambahPengajuan" tabindex="-1" role="dialog" aria-labelledby="largeModal" aria-hidden="true" style="overflow-y:auto;">
      <div style="width: 900px" class="modal-dialog modal-lg">
      <div class="modal-content">
      <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          <h3 class="modal-title" id="myModalLabel"> <span style="color: grey " id="namekaw"></span></h3>
      </div>
      <form action="{{ url('/')}}/rekanan/user/spk/save-pengajuan" method="post">
        {{ csrf_field() }}
        <input type="" value="{{$spk->id}}" id="id_spk" name="spk_id" hidden>
        <input type="" value="{{$rekanan_group->id}}" id="" name="rekanan_group_id" hidden>          
        <div class="modal-body">
          <div class="tab-pane table-responsive" id="tab_2">
            <table class="table" style="font-size:18px;">
              <thead>
                <tr>
                  <td style="width:20%">No SPK</td>
                  <td>:</td>
                  <td style="">
                    {{$spk->no}}
                  </td>
                </tr>
                <tr>
                  <td style="">No Unit/Kawasan</td>
                  <td>:</td>
                  <td style="">
                    <!-- <select class="js-example-basic-multiple" name="states[]" multiple="multiple"> -->
                    <select class='form-control unit' name='unit' id='unit' class="form-control" style="width:70%">
                      <option value="" selected>Pilih Unit</option>
                      @foreach ( $spk->tender->units as $key => $value )
                        @if($value->rab_unit->asset != '')
                          <option value="{{$value->id}}">{{$value->rab_unit->asset->name}}</option>
                        @else
                          <option value="{{$value->id}}">Fasilitas Kota</option>
                        @endif
                      @endforeach
                    </select>
                  </td>
                </tr>
                <tr>
                  <td style="">Spk</td>
                  <td>:</td>
                  <td style="">
                    <select class='form-control spk_vo' id='spk_vo' class="form-control" style="width:70%" name="spk_vo">
                      <option value="spk" selected>SPK</option>
                      {{-- <option value="vo" >SPK - Vo</option> --}}
                    </select>
                  </td>
                </tr>
                <tr hidden id="no_vo">
                  <td style="">No. Vo</td>
                  <td>:</td>
                  <td style="">
                    <select class='form-control vo' id='vo' class="form-control" style="width:70%" name="vo">
                    </select>
                  </td>
                </tr>
                <tr>
                  <td style="">Pekerjaan</td>
                  <td>:</td>
                  <td style="">
                    <select class='form-control pekerjaan' name='pekerjaan' id='pekerjaan' class="form-control" style="width:70%" required>
                      <option value="" selected>Pilih Pekerjaan</option>
                      @foreach ( $spk->tender_rekanan->menangs->first()->details as $key2 => $value2 )
                        @php
                          $ipk = Modules\Spk\Entities\IpkTambahan::where("spk_id",$spk->id)->where("itempekerjaan_id",$value2->itempekerjaan_id)->get()->count();
                        @endphp
                        @if($ipk != 0)
                          @if($value2->volume != 0 && $value2->volume != null)
                            <option value='{{$value2->itempekerjaan->id}}'>{{$value2->itempekerjaan->name}}</option>
                          @endif
                        @endif
                      @endforeach
                    </select>
                  </td>
                </tr>
                <tr>
                  <td style="">Progress Tahap</td>
                  <td>:</td>
                  <td style="">
                    <select class='form-control progress' id='progress' class="form-control" style="width:70%" name="progress" required>
                    </select>
                  </td>
                </tr>
                <tr>
                  <td style="">Tanggal di cek</td>
                  <td>:</td>
                  <td style="">
                    <input type="text" id="tglpengajuan" name="tglpengajuan" class="" style="width:20%;margin-right:none" required><i class="fa fa-fw fa-calendar"></i>
                  </td>
                </tr>
                </tr>
                  <tr>
                  <td style="">Keterangan</td>
                  <td>:</td>
                  <td>
                    <div class="form-group">
                      <textarea class="form-control" style="height: 33px;min-width: 70%; margin: 0px; min-height: 150px; max-height: 350px; height: 150px; max-width: 70%; width: 70%;" rows="7" id="keterangan" name="keterangan"></textarea>
                    </div>
                  </td>
                </tr>
              </thead>
            </table>
          </div>   
        </div>

        <div class="modal-footer">
          <center>
            <button id="btn_simpan" class="btn btn-primary">Simpan</button>
          </center>
        </div>
      </form>
      </div>
      </div>
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
    $(".spk_vo").select2();
    $(".vo").select2();
    
     CKEDITOR.replace( 'keterangan' );
  });
  $(document).ready(function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('input[name=_token]').val()
        }
      });

    var enddate = $('#enddate').val();
    $('#phari').hide();

  });

    $(document).on('change', '.pekerjaan', function() {
        var parent_id = $(this).val();
        var id_spk = $("#id_spk").val();
        var id_unit = $("#unit").val();
        var tipe = $("#spk_vo").val();
        var vo_id = $("#vo").val();
        console.log(vo_id);
        var _url = "{{ url('/rekanan/spk/progress_tahap') }}";
        var _data = {
            itempekerjaan_id : parent_id,
            spk_id : id_spk,
            unit_id : id_unit,
            tipe : tipe,
            vo_id : vo_id,
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
                    // strItemOption += '<option value="0">Pilih Progress Tahap</option>';
                    $(data.progress_tahap).each(function(i, v) {
                      console.log(v);
                        strItemOption += '<option value="' + v.id + '"">' + v.name + ' | ' + parseFloat(v.volume, 4) +'' + v.satuan +'</option>';
                    });
                    parent_div.append(strItemOption);
                }
            },
            complete: function() {
                waitingDialog.hide();
            }
        });
    });

    $(".tambah_pengajuan").click(function() {
      $('#ModalTambahPengajuan').modal('show');
    });

    $(document).on('change', '.spk_vo', function() {
        var id_spk = $("#id_spk").val();
        var _url = "{{ url('/rekanan/user/spk/vo') }}";
        var _url2 = "{{ url('/rekanan/user/spk/pekerjaan') }}";
        var _data = {
            spk_id : id_spk        };
        var parent_div = $('#vo');
        if($(this).val() == "vo"){
          $('#no_vo').show();
          $.ajax({
            type: 'post',
            dataType: 'json',
            url: _url,
            data: _data,
            beforeSend: function() {
                waitingDialog.show();

            },
            success: function(data) {
                var strItemOption = '';
                if (data.progress_tahap != null) {
                    parent_div.find('option').remove();
                    strItemOption += '<option value="0">Pilih No Vo</option>';
                    $(data.progress_tahap).each(function(i, v) {
                        strItemOption += '<option value="' + v.id + '"">' + v.no + '</option>';
                    });
                    parent_div.append(strItemOption);
                }
            },
            complete: function() {
                waitingDialog.hide();
            }
          });
        }else{
          var parent_div = $('#pekerjaan');
          $('#no_vo').hide();
          $.ajax({
            type: 'post',
            dataType: 'json',
            url: _url2,
            data: _data,
            beforeSend: function() {
                waitingDialog.show();

            },
            success: function(data) {
                var strItemOption = '';
                if (data.progress_tahap != null) {
                    parent_div.find('option').remove();
                    strItemOption += '<option value="0">Pilih Pekerjaan</option>';
                    $(data.progress_tahap).each(function(i, v) {
                      // console.log(v);
                      strItemOption += '<option value="' + v.pekerjaan_id + '"">' + v.pekerjaan + '</option>';
                    });
                    parent_div.append(strItemOption);
                }
            },
            complete: function() {
                waitingDialog.hide();
            }
          });
        }
    });

    $(document).on('change', '.vo', function() {
        var id = $(this).val();
        var unit_id = $("#unit").val();
        var _url = "{{ url('/rekanan/user/spk/vo_detail') }}";
        var _data = {
            id : id,
            unit_id : unit_id        
            };
        var parent_div = $('#pekerjaan');
          $.ajax({
            type: 'post',
            dataType: 'json',
            url: _url,
            data: _data,
            beforeSend: function() {
                waitingDialog.show();

            },
            success: function(data) {
                var strItemOption = '';
                if (data.progress_tahap != null) {
                    parent_div.find('option').remove();
                    strItemOption += '<option value="0">Pilih Pekerjaan</option>';
                    $(data.progress_tahap).each(function(i, v) {
                      // console.log(v);
                        strItemOption += '<option value="' + v.pekerjaan_id + '"">' + v.pekerjaan + '</option>';
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
