<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin QS | Dashboard</title>
  {{-- @include("user.header") --}}
  @include("master/header")
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <style type="text/css">
    .table-align-right{
      text-align: right;
    }
    select{
      background-color: white;
      width: 100%;
    }
  </style>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  @include("user.sidebar")

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1 style="text-align:center">Detail Perpanjangan SPK</h1>
    </section>
    <section class="back-button content-header">
      <div class="">
        <button class="col-lg-1 col-md-2 col-sm-2 btn btn-primary" onclick="location.href='{{ url('/')}}/access'" style="float: none; border-radius: 20px; padding-left: 0">
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
            <div class="box-header">
              <h3 class="box-title">Data Perpanjangan SPk</h3>
              <input type="hidden" id="perpanjangan_spk_id" name="perpanjangan_spk_id" value="{{$perpanjangan_spk->id}}">
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <div class="box-header">
                        <div class="col-md-12">
                            <div class="box-header ">
                                <table class="table" style="font-size:18px;font-weight:bold;width;100%">
                                    <thead>
                                        <tr>
                                          <td>Status</td>
                                          <td>:</td>
                                          <td>
                                            @if($perpanjangan_spk->approval->histories->where("user_id",$user->id)->first()->approval_action_id == 1)
                                                <strong style="color:orange"> Dalam Proses </strong>
                                            @elseif($perpanjangan_spk->approval->histories->where("user_id",$user->id)->first()->approval_action_id == 6)
                                                <strong style="color:green"> Approved </strong>
                                            @elseif($perpanjangan_spk->approval->histories->where("user_id",$user->id)->first()->approval_action_id == 7)
                                                <strong style="color:red"> Rejected </strong>
                                            @endif
                                          </td>
                                        </tr>
                                        <tr>
                                          <td>No. SPK</td>
                                          <td>:</td>
                                          <td>{{$perpanjangan_spk->spk->no}}</td>
                                        </tr>
                                        <tr>
                                          <td>Project</td>
                                          <td>:</td>
                                          <td>{{$perpanjangan_spk->spk->project->name}} </td>
                                        </tr>
                                        <tr>
                                          <td>Rekanan</td>
                                          <td>:</td>
                                          <td>{{$perpanjangan_spk->spk->rekanan->group->name}}</td>
                                        </tr>
                                        <tr>
                                          <td>Pekerjaan</td>
                                          <td>:</td>
                                          <td>{{$perpanjangan_spk->spk->tender->rab->name}}</td>
                                        </tr>
                                        <tr>
                                          <td>End Date (Sebelumnya)</td>
                                          <td>:</td>
                                          <td>
                                          <input type="hidden" id="enddate" value="{{date('d/M/Y',strtotime($end_date))}}" name="">
                                          {{date("d-M-Y",strtotime($end_date))}}</td>
                                        </tr>
                                        <tr>
                                          <td>Request Perpanjangan</td>
                                          <td>:</td>
                                          <td>{{date("d-M-Y",strtotime($perpanjangan_spk->update_finish))}} ( {{$perpanjangan_spk->duration}} hari )</td>
                                        </tr>
                                        <tr>
                                          <td>Alasan Perpanjangan</td>
                                          <td>:</td>
                                          <td>
                                              <textarea class="form-control" style="height: 100px;min-width: 250px; margin: 0px; min-height: 100px; max-height: 100px; height: 100px; max-width: 350px width: 350px;" rows="7" id="isian1" name="isian" readonly> {{$perpanjangan_spk->reason}}</textarea>
                                          </td>
                                        </tr>
                                        <tr>
                                          <td>Tanggal Disetujui</td>
                                          <td>:</td>
                                          <td>
                                              @if(isset($perpanjangan_spk->tanggal_disetujui))
                                                <input type="text" id="tglperpanjang_disetujui" name="tglperpanjang" required value="{{date('d/M/Y',strtotime($perpanjangan_spk->tanggal_disetujui))}}"><i class="fa fa-fw fa-calendar" ></i>
                                                <p class="text-mutted" id="phari"> (<input style="background-color: transparent; border: none; width: 30px" type="text" id="jmlhari" name=""  value="{{(strtotime($perpanjangan_spk->tanggal_disetujui)-strtotime($perpanjangan_spk->spk->finish_date))/(60*60*24)}}" readonly>Hari)</p>
                                              @else
                                                <input type="text" id="tglperpanjang_disetujui" name="tglperpanjang" required value="{{date('d/M/Y',strtotime($perpanjangan_spk->update_finish))}}"><i class="fa fa-fw fa-calendar" ></i>
                                                <p class="text-mutted" id="phari"> (<input style="background-color: transparent; border: none; width: 30px" type="text" id="jmlhari" name=""  value="{{$perpanjangan_spk->duration}}" readonly>Hari)</p>
                                              @endif
                                          </td>
                                        </tr>
                                        </tr>
                                        <tr>
                                          <td>Alasan Penyetujuan</td>
                                          <td>:</td>
                                          <td>
                                              <textarea class="form-control" style="height: 100px;min-width: 250px; margin: 0px; min-height: 100px; max-height: 100px; height: 100px; max-width: 350px width: 350px;" rows="7" id="alasan_disetujui" name="alasan_penyetujuan" >{{$perpanjangan_spk->reason_disetujui}}</textarea>
                                          </td>
                                        </tr>
                                        <tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">  
                      @if($perpanjangan_spk->approval->histories->where("user_id",$user->id)->first()->approval_action_id == 1) 
                        <button class="btn btn-info" id="approve">Approve</button>
                        <button class="btn btn-danger" id="reject">Reject</button>
                      @endif
                    </div>                
                    <br/>
                    <div class="col-md-12 table-responsive">
                      <table class="table table-bordered table-striped" style="width:100%">
                        <tr class="header_1">
                          <td style="width: 15%;">Username</td>
                          <td style="width: 15%;">Request At</td>
                          <td style="width: 15%;">Status</td>
                          <td style="width: 15%;">Time Left (days)</td>
                          <td>Reason</td>
                        </tr>
                        @if ( isset($perpanjangan_spk->approval->histories))
                        @foreach ( $perpanjangan_spk->approval->histories as $key2 => $value2 )
                        <tr>
                          <td>
                            @if ( $value2->approval_action_id == "6")
                            <input type="checkbox" name="approval_id" value="" id="" disabled checked> <strong>{{ $value2->user->user_name or '' }}</strong>
                            @else
                            <input type="checkbox" name="approval_id" value="" id="" disabled>{{ $value2->user->user_name or '' }}
                            @endif
                          </td>
                          <td>{{ $value2->created_at->format("d M Y ") }}</td>
                          <td>
                            @if ( $value2->approval_action_id == "7" )
                            <span class="reject"><strong>Reject</strong></span>                   
                            @elseif ( $value2->approval_action_id == "6")
                            <span class="approve"><strong>Approve</strong></span>
                            @else
                            <span class="waiting"><strong>Waiting</strong></span>
                            @endif
                          </td>
                          <td>
                            <strong>
                              @php
                              $str = $value2->created_at;
                              $str = strtotime(date("M d Y ")) - (strtotime($str));
                              echo ceil($str/3600/24);
                              @endphp
                            </strong>
                            (days)
                          </td>
                          <td>{{ $value2->description or  '' }}</td>
                        </tr>
                        @endforeach
                        @endif
                      </table>
                    </div>
                    <!-- /.form-group -->
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
<!-- @include('pluggins.select2_pluggin') -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="{{ url('/')}}/assets/plugins/timepicker/bootstrap-timepicker.min.js"></script>
<script src="https://cdn.ckeditor.com/4.11.3/standard/ckeditor.js"></script>
<script type="text/javascript">

    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('meta[name=csrf-token]').attr('content')
        }
    });
    $(function () {
        CKEDITOR.replace( 'isian' );
    });

     $(document).ready(function(){
        var enddate = $('#enddate').val();

        $("#approve").click(function(){
            var perpanjangan_spk_id = $("#perpanjangan_spk_id").val();
            var tanggal_disetujuai = $("#tglperpanjang_disetujui").val();
            var alasan_disetujuai = $("#alasan_disetujui").val();
            console.log(perpanjangan_spk_id);
            console.log(tanggal_disetujuai);
            console.log(alasan_disetujuai);
            if ( confirm("Apakah anda yakin ingin Approve dokumen ini ?")){
            var request = $.ajax({
                url : "{{ url('/')}}/access/PerpanjanganSpk/approve",
                dataType : "json",
                type : "post",
                data : {
                  id :perpanjangan_spk_id,
                  tanggal_disetujuai :tanggal_disetujuai,
                  alasan_disetujuai :alasan_disetujuai,
                }
            });

            request.done(function(data){
                if ( data.status == "0"){
                alert("Dokumen telah diapprove");
                }
                window.location.reload();
            })
            }else{
            return false;
            }

        });

        $("#reject").click(function(){
            var perpanjangan_spk_id = $("#perpanjangan_spk_id").val();
            if ( confirm("Apakah anda yakin ingin Reject dokumen ini ?")){
            var request = $.ajax({
                url : "{{ url('/')}}/access/PerpanjanganSpk/reject",
                dataType : "json",
                type : "post",
                data : {
                  id :perpanjangan_spk_id,
                }
            });

            request.done(function(data){
                if ( data.status == "0"){
                alert("Dokumen telah direject");
                }
                window.location.reload();
            })
            }else{
            return false;
            }

        });

         $('#tglperpanjang_disetujui').datepicker({
            dateFormat: 'dd/M/yy',
            minDate: new Date(enddate)
        })
        $('#tglperpanjang_disetujui').change(function(){
            tglperpanjang = $('#tglperpanjang_disetujui').val();
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
    
</script>
</body>
</html>