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

  @include("master/sidebar_project")

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
                <a class="btn btn-warning" href="{{ url('/')}}/spk/sik">Kembali</a>
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
                      <td>No. unit/ Kawasan</td>
                      <td>:</td>
                      <td> 
                        @foreach($sik->sik_unit as $key => $value)
                          @if($value->unit->rab_unit->asset != '')
                            @if($key == 0)
                              {{$value->unit->rab_unit->asset->name}}
                            @else
                              , {{$value->unit->rab_unit->asset->name}}
                            @endif
                          @else
                            Fasilitas Kota
                          @endif
                        @endforeach
                      </td>
                    </tr>
                    <tr>
                      <td>Tipe SIK Berbiaya</td>
                      <td>:</td>
                      <td>Berbiaya </td>
                    </tr>
                    @if($sik->vo != '')
                      <tr>
                        <td>No. Vo</td>
                        <td>:</td>
                        <td>{{$sik->vo->no}} </td>
                      </tr>
                      @if($sik->vo->approval != '')
                        <tr>
                          <td>Status Vo</td>
                          <td>:</td>
                          <td>
                            @if($sik->vo->approval->approval_action_id == 1)
                              <strong style="color:orange"> Dalam Proses </strong>
                            @elseif($sik->vo->approval->approval_action_id == 6)
                              <strong style="color:green"> Approved </strong>
                            @elseif($sik->vo->approval->approval_action_id == 7)
                              <strong style="color:red"> Rejected </strong>
                            @endif
                          </td>
                        </tr>
                      @endif
                    @endif
                  </thead>
                </table>
              </div>
            </div>
          </div>
            <div class="col-md-6">   
              @if($sik->vo == '')
                  <a class="btn btn-info" href="{{ url('/')}}/spk/sik/request-approval-sik?idsik={{$sik->id}}&id_spk={{$spk->id}}">Request Approval</a>
              @endif

                <button class="btn btn-info" type="button" id="cetak_sik"> cetak SIK</button>
                @if($sik->vo != '')
                  @if($sik->vo->approval != '')
                    @if($sik->vo->approval->approval_action_id == 6)
                      <button class="btn btn-info" type="button" id="cetak_vo"> cetak Vo</button>
                    @endif
                  @endif
                @endif
                @if ( $sik->vo != "" )
                <a href="{{ url('/')}}/spk/approval_history_vo?id={{ $sik->vo->id }}" class="btn btn-primary" style="margin: 5px 0px 3px 0px">Approval History</a>
                @endif
                {{ csrf_field() }}
              <div class="form-group">
                <input class="form-control" type="hidden" name="id_spk" id="id_spk" value="{{$spk->id}}">
                <input class="form-control" type="hidden" name="id_spk" id="id_sik" value="{{$sik->id}}">
                <input class="form-control" type="hidden" name="id_item" id="id_item" value="">
              </div> 
              </div>                
                      
              <!-- /.form-group -->
            </div>
            {{-- <form class="form-horizontal" action="{{ url('/spk/update-sikbiaya') }}" method="post" autocomplete="off" name="form-oe" id="form-oe"> --}}
               {{ csrf_field() }}

              <table id="table_sik" class="table table-bordered table-hover table_sik">
                <thead class="head_table" >
                <tr>
                  <th style="width : 5%">No.</th>
                  <th style="width : 10%">Coa </th>
                  <th style="width : 20%">Item Pekerjaan</th>
                  <th style="width : 10%">Volume</th>
                  <th style="width : 10%">Volume Admin</th>
                  <th style="width : 5%">Satuan</th>
                  <th style="width : 10%">Harga Satuan</th>
                  <th style="width : 15%">Keterangan Pengawas</th>
                  <th style="width : 15%">Keterangan Admin</th>
                </tr>
                </thead>
                <tbody>
                  @foreach( $sik->sik_detail as $key =>$value2 )
                    <tr class="test">
                      @php 
                        $itempekerjaan = \Modules\Pekerjaan\Entities\Itempekerjaan::find($value2->itempekerjaan_id);
                      @endphp
                      <td><strong>{{ $key+1 }}</strong></td>
                      <td><strong>{{ $itempekerjaan->code }}</strong></td>
                      <td><strong>{{ $itempekerjaan->name }}</strong></td>
                      <td style=text-align:right><strong>{{ round($value2->volume, 4) }}</strong></td>
                      <td><strong>{{round($value2->volume_admin, 4)}}</strong></td>
                      <td>
                        <strong>{{ $value2->satuan }}</strong>
                        <input type="hidden" name="" value="{{ $value2->id }}" id="" class="sik_detail_id">
                        <input type="hidden" name="id_item[]" value="{{ $itempekerjaan->id }}" id="id_detail" class="itempekerjaan_id">
                      </td>
                      <td></td>
                      <td></td>
                      <td></td>
                    </tr>
                    @if (count($value2->sik_detail) != 0)
                        <tr class="child">
                          <td colspan="9">
                            <table border="0" style="padding:none;width:100%" class="table child_table {{$value2->itempekerjaan_id}} ">
                              @foreach($value2->sik_detail as $key2 => $value3 )
                                <tr class="test_child">
                                    <td style="width : 5%"></td>
                                    <td style="width : 10%"></td>
                                    <td style="width : 20%">
                                      {{$value3->name}}
                                      <input type="hidden" class="form-control child_sik_sub_detail_id" name="" value="{{$value3->id}}" id="">
                                    </td>
                                    <td style="text-align:right;width : 10%">
                                      {{round($value3->volume, 4)}}
                                    </td>
                                    <td style="text-align:right;width : 10%">
                                      @if ($value3->volume_admin == null )
                                        <input type="text" class="form-control child_volume_admin" name="child_volume_admin[]" value="{{round($value3->volume, 4)}}" style="width:100%">
                                      @else
                                        <input type="text" class="form-control child_volume_admin" name="child_volume_admin[]" value="{{round($value3->volume_admin, 4)}}" style="width:100%">
                                      @endif
                                    </td>
                                    <td style="width : 5%">
                                      {{$value3->satuan}}
                                    </td>
                                    <td style="width : 10%;text-align:right">
                                      {{number_format($value3->nilai)}}
                                    </td>
                                    <td style="width : 15%">
                                      <textarea type="" class="form-control child_keterangan" name="child_ket[]" value="" id="ket" style="height: 33px;min-width: 135px; margin: 0px; min-height: 92px; max-height: 150px; height: 92px; max-width: 300px width: 135px;" readonly>{{$value3->keterangan}}</textarea> 
                                    </td>
                                    <td style="width : 15%">
                                      <textarea type="" class="form-control child_keterangan_admin" name="child_ket[]" value="" id="ket" style="height: 33px;min-width: 135px; margin: 0px; min-height: 92px; max-height: 150px; height: 92px; max-width: 300px width: 135px;">{{$value3->keterangan_admin}}</textarea> 
                                    </td>
                                </tr>
                              @endforeach
                            </table>
                          </td>
                        </tr>
                    @endif
                  @endforeach
                </tbody>
              </table>
              <div class="form-group">
                <center>
                  @if($sik->vo == '')
                    <button type="submit" class="btn btn-primary submitbtn" id="btn_submit">Update</button>
                  @endif
                </center>
              </div>
              {{-- </form> --}}
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
@include("progress::app")
<!-- Select2 -->
<script src="{{ url('/')}}/assets/bower_components/select2/dist/js/select2.full.min.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('input[name=_token]').val()
        }
    });
  });
$(document).on('click', '#cetak_sik', function() {
  var sik = $("#id_sik").val();
  window.open("/spk/cetakSik?sik="+sik,'_blank');
});

$(document).on('click', '#cetak_vo', function() {
  var sik = $("#id_sik").val();
  window.open("/spk/cetakVo?sik="+sik,'_blank');
});

$(document).on('click', '.submitbtn', function() {
      var main = [];
      $("#table_sik .test").each(function () {

          var id_pekerjaan = $(this).find(".itempekerjaan_id").val();
          var sub = [];
          $("#table_sik ."+id_pekerjaan+" .test_child").each(function () {
              var arr = [
                  $(this).find(".child_sik_sub_detail_id").val(),
                  $(this).find(".child_keterangan_admin").val(),
                  $(this).find(".child_volume_admin").val(),
              ];
              sub.push(arr);
          });
          console.log(sub);
          var arr2 = [
                  sub,
              ];
          main.push(arr2);

      });
      
      var url = "{{ url('/')}}/spk/update-sikbiaya";
        $.ajax({
            type: 'post',
            dataType: 'json',
            url: url,
            data: {
                data: JSON.stringify(main),
            },
            beforeSend: function() {
            waitingDialog.show();
            },
            success: function(data) { 
                window.location.reload(true);
            },
            complete: function() {
            waitingDialog.hide(); 
            },
        });
  });
</script>
</body>
</html>
