<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin QS | Dashboard</title>
  @include("user.header")
  @include("master/header")
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
      <h1 style="text-align:center">Detail Spk Percepatan</h1>
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
            <!-- <div class="box-header">
              <h3 class="box-title">Data SIK</h3>
            </div> -->
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <div class="box-header col-md-12">
                        <div class="col-md-12">
                            <h3 class="box-title">Percepatan SPK</h3>  
                            <div class="box-header ">
                            <table class="table" style="font-size:18px;">
                                <thead>
                                <tr>
                                    <td style="font-weight:bold;width:40%">No SPK Percepatan</td>
                                    <td>:</td>
                                    <td >
                                    {{$percepatan->no}}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-weight:bold">End Date SPK</td>
                                    <td>:</td>
                                    <td >
                                        {{date('d-M-Y', strtotime($percepatan->spk->finish_date))}}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-weight:bold">Nilai SPK</td>
                                    <td>:</td>
                                    <td >
                                        Rp. {{number_format($percepatan->spk->nilai)}}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-weight:bold">Nilai SPK per unit</td>
                                    <td>:</td>
                                    <td >
                                        Rp. {{number_format($percepatan->spk->nilai/count($percepatan->spk->tender->units))}}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-weight:bold">Nilai %</td>
                                    <td>:</td>
                                    <td style="">
                                        {{$percepatan->nilai_persen}} %
                                    </td>
                                </tr>
                                <tr>
                                  <td style="font-weight:bold">No. unit/ Kawasan</td>
                                  <td>:</td>
                                  <td> 
                                    @foreach($percepatan->percepatan_unit as $key2 => $value2)
                                      @if($key2 == 0)
                                        {{$value2->unit->rab_unit->asset->name}}
                                      @else
                                        , {{$value2->unit->rab_unit->asset->name}}
                                      @endif
                                    @endforeach
                                  </td>
                                </tr>
                                <tr>
                                    <td style="font-weight:bold">Nilai Rp.</td>
                                    <td>:</td>
                                    <td style="">
                                        Rp. {{number_format(($percepatan->spk->nilai/count($percepatan->spk->tender->units))*($percepatan->nilai_persen/100)*count($percepatan->percepatan_unit))}}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-weight:bold">Tanggal Selesai percepatan</td>
                                    <td>:</td>
                                    <td style="">
                                        {{date('d-M-Y', strtotime($percepatan->tanggal_finish))}}
                                    </td>
                                </tr>
                                    <tr>
                                    <td style="font-weight:bold">Keterangan</td>
                                    <td>:</td>
                                    <td>
                                    <div class="form-group">
                                        <textarea class="form-control" style="height: 33px;min-width: 250px; margin: 0px; min-height: 250px; max-height: 350px; height: 250px; max-width: 350px width: 300px;" rows="7" id="description" name="description">{{$percepatan->description}}</textarea>
                                    </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Status</td>
                                    <td>:</td>
                                    <td>
                                    @if($percepatan->approval->histories->where("user_id",$user->id)->first()->approval_action_id == 1)
                                        <strong style="color:orange"> Dalam Proses </strong>
                                    @elseif($percepatan->approval->histories->where("user_id",$user->id)->first()->approval_action_id == 6)
                                        <strong style="color:green"> Approved </strong>
                                    @elseif($percepatan->approval->histories->where("user_id",$user->id)->first()->approval_action_id == 7)
                                        <strong style="color:red"> Rejected </strong>
                                    @endif
                                    </td>
                                </tr>
                                </tr>
                                </thead>
                            </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">   
                        <input class="form-control" type="hidden" name="percepatan_id" id="percepatan_id" value="{{$percepatan->id}}">
                        @if($percepatan->approval->histories->where("user_id",$user->id)->first()->approval_action_id == 1)
                          <button class="btn btn-info" id="approve">Approve</button>
                          <button class="btn btn-danger" id="reject">Reject</button>
                        @endif
                    </div>                
                      
                    <!-- /.form-group -->

                    <div class="col-md-12 table-responsive">
                      <table class="table table-bordered table-striped">
                        <tr class="header_1">
                          <td style="width: 15%;">Username</td>
                          <td style="width: 15%;">Request At</td>
                          <td style="width: 15%;">Status</td>
                          <td style="width: 15%;">Time Left (days)</td>
                          <td>Reason</td>
                        </tr>
                        @if ( isset($percepatan->approval->histories))
                        @foreach ( $percepatan->approval->histories as $key2 => $value2 )
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
<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('meta[name=csrf-token]').attr('content')
        }
    });

    $(document).ready(function(){
        $("#approve").click(function(){
            var percepatan_id = $("#percepatan_id").val();

            if ( confirm("Apakah anda yakin ingin Approve dokumen ini ?")){
            var request = $.ajax({
                url : "{{ url('/')}}/access/SpkPercepatan/approve",
                dataType : "json",
                type : "post",
                data : {
                id :percepatan_id,
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
    });

    $(document).ready(function(){
        $("#reject").click(function(){
            var percepatan_id = $("#percepatan_id").val();

            if ( confirm("Apakah anda yakin ingin Reject dokumen ini ?")){
            var request = $.ajax({
                url : "{{ url('/')}}/access/SpkPercepatan/reject",
                dataType : "json",
                type : "post",
                data : {
                id :percepatan_id,
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
    });
</script>
</body>
</html>
