<!DOCTYPE html>
<html>
    @include('master.header')

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin QS | Dashboard</title>
    {{-- @include("user.header") --}}
    @include("master.header")
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
            <h1 style="text-align:center">Detail Variant Order</h1>
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
                            <h3 class="box-title">Data SIK</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="row">
                                <div class="box-header" style="margin:2px 2px 2px 2px;padding-bottom:0px">
                                    <div class="col-md-12" style="padding-bottom:0px">
                                        <div class="box-header" style="padding-bottom:0px">
                                            <input type="hidden" name="user_id" id="user_id" value="{{ $user->id }}"/>
                                            <table class="table" style="font-size:18px;font-weight:bold;width:100%">
                                                <thead>
                                                <tr>
                                                    <td>No. SPK</td>
                                                    <td>:</td>
                                                    <td style="word-break: break-all;">{{$vo->spk->no}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Project</td>
                                                    <td>:</td>
                                                    <td>{{$vo->spk->project->name}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Pengawas</td>
                                                    <td>:</td>
                                                    <td>{{ $vo->spk->user_pic->user_name or '' }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Pekerjaan</td>
                                                    <td>:</td>
                                                    <td>{{$vo->spk->name}} </td>
                                                </tr>
                                                <tr>
                                                    <td>No. unit/ Kawasan</td>
                                                    <td>:</td>
                                                    <td> 
                                                        @foreach($vo->sik->sik_unit as $key => $value)
                                                            @if($value->unit->rab_unit->asset != '' && $value->unit->rab_unit->asset->id != $vo->spk->project->id)
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
                                                {{-- <tr>
                                                    <td>Tipe VO</td>
                                                    <td>:</td>
                                                    <td>
                                                        @if($vo->sik->status_sik_id == 1)
                                                            Penambahan
                                                        @else
                                                            Pengurangan
                                                        @endif
                                                    </td>
                                                </tr> --}}
                                                <tr>
                                                    <td>Nilai VO</td>
                                                    <td>:</td>
                                                    <td>
                                                        @if($vo->sik->status_sik_id == 1)
                                                            {{number_format($vo->nilai)}}
                                                        @else
                                                            -{{number_format($vo->nilai)}}
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Status Vo</td>
                                                    <td>:</td>
                                                    <td>
                                                        @if($vo->approval->histories->where("user_id",$user->id)->first()->approval_action_id == 1)
                                                            <strong style="color:orange"> Dalam Proses </strong>
                                                        @elseif($vo->approval->histories->where("user_id",$user->id)->first()->approval_action_id == 6)
                                                            <strong style="color:green"> Approved </strong>
                                                        @elseif($vo->approval->histories->where("user_id",$user->id)->first()->approval_action_id == 7)
                                                            <strong style="color:red"> Rejected </strong>
                                                        @endif
                                                    </td>
                                                </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="box-header col-md-12" style="margin:2px 2px 2px 2px">
                                    <div class="col-md-12" style="padding-top:0px">
                                        <div class="box-header " style="padding-top:0px">
                                            <h3 class="box-title"><strong>Summary</strong></h3>
                                            <table  class="table borderless" style="margin-bottom:0; font-size: 15px; border: none;"  >
                                                <tr>
                                                    <td style="width:15%;text-align: left;border-top:none;">
                                                        Nilai
                                                    </td>
                                                    <td style="width:3%;border-top:none;">:</td>
                                                    <td style="width:50%;text-align: left;border-top:none;">
                                                        Rp. {{ number_format($vo->spk->nilai)}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="width:15%;text-align: left;border-top:none;">
                                                        Nilai VO Kumulatif
                                                    </td>
                                                    <td style="width:3%;border-top:none;">:</td>
                                                    <td style="width:50%;text-align: left;border-top:none;">
                                                        Rp. {{ number_format($vo->spk->nilai_vo)}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="width:15%;text-align: left;border-top:none;">
                                                        Nilai Percepatan
                                                    </td>
                                                    <td style="width:3%;border-top:none;">:</td>
                                                    <td style="width:50%;text-align: left;border-top:none;">
                                                        Rp. {{ number_format($vo->spk->nilai_percepatan)}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="width:15%;text-align: left;border-top:none;">
                                                        Nilai SPK (Excl. PPN)
                                                    </td>
                                                    <td style="width:3%;border-top:none;">:</td>
                                                    <td style="width:50%;text-align: left;border-top:none;">
                                                        Rp. {{ number_format($vo->spk->nilai_vo + $vo->spk->nilai + $vo->spk->nilai_percepatan) }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="width:15%;text-align: left;border-top:none;">
                                                        PIC
                                                    </td>
                                                    <td style="width:3%;border-top:none;">:</td>
                                                    <td style="width:50%;text-align: left;border-top:none;">
                                                        <strong>{{ $vo->spk->user_pic->user_name or '' }}</strong>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="box-header col-md-12" style="margin:2px 2px 2px 2px">
                                    <div class="col-md-12">
                                        <div class="box-header ">
                                            <input class="form-control" type="hidden" name="vo_id" id="vo_id" value="{{$vo->id}}">
                                        <!-- <a class="btn btn-info" href="{{ url('/')}}/spk/sik/request-approval-sik?idsik={{$vo->id}}&id_spk={{$vo->spk->id}}">Approval</a> -->
                                            @if($vo->approval->histories->where("user_id",$user->id)->first()->approval_action_id == 1)
                                                {{-- <button class="btn btn-info" id="approve">Approve</button>
                                                <button class="btn btn-danger" id="reject">Reject</button> --}}
                                                <a href="#" class="btn btn-info" onclick="setapproved('6')" data-toggle="modal" data-target="#myModal">Approve</a>
                                                <a href="#" class="btn btn-danger" onclick="setapproved('7')" data-toggle="modal" data-target="#myModal">Reject</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- /.form-group -->
                                <div class="col-md-12">
                                    <table id="table_pekerjaan" class="table table-bordered table-hover" style="width:100%">
                                        <thead class="head_table">
                                            <tr>
                                                <th style="width : 5%">No.</th>
                                                <th style="width : 7%">Coa </th>
                                                <th style="width : 20%">Item Pekerjaan</th>
                                                <th style="width : 7%">Volume admin</th>
                                                <th style="width : 7%">Volume pengawas</th>
                                                <th style="width : 5%">Satuan</th>
                                                <th style="width : 9%">Harga Satuan</th>
                                                <th style="width : 10%">Subtotal</th>
                                                <th style="width : 15%">Keterangan pengawas</th>
                                                <th style="width : 15%">Keterangan admin</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php 
                                            ($no = 1); 
                                            $simpan = [];
                                            @endphp
                                            @foreach( $vo->detail as $key =>$value2 )
                                                @php
                                                    $itempekerjaan = \Modules\Pekerjaan\Entities\Itempekerjaan::find($value2->itempekerjaan_id);
                                                @endphp
                                                @if (in_array($itempekerjaan->id, $simpan) == false)
                                                    <tr style="font-weight: bold;">
                                                        <input class="form-control" type="hidden" name="id_spk" id="id_spk" value="{{$vo->spk->id}}">
                                                        <td>{{ $no }}</td>
                                                        <input type="hidden" name="idsik" value="{{ $value2->sik_id }}" id="id_detail" >
                                                        <td>{{ $itempekerjaan->code }}</td>
                                                        <td>{{ $itempekerjaan->name }}</td>
                                                        <input type="hidden" name="satuan[]" value="{{ $value2->satuan }}" id="id_detail" >
                                                        <input type="hidden" name="id_detail[]" value="{{ $value2->id }}" id="id_detail" >
                                                        <td style="text-align:right;">{{ round($value2->volume, 4)}}</td>
                                                        <td style="text-align:right;">{{ round($value2->sub_detail_vo->sum("volume_pengawas"), 4)}}</td>
                                                        <td>{{ $value2->satuan }}</td>
                                                        <td style = "text-align:right">{{number_format($value2->nilai)}}</td>
                                                        <td style = "text-align:right">{{number_format($value2->total_nilai)}}</td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                    @if (count($value2->sub_detail_vo) != 0)
                                                        @foreach($value2->sub_detail_vo as $key2 => $value3 )
                                                            <tr class="test_child">
                                                                <td style="width : 5%"></td>
                                                                <td style="width : 7%"></td>
                                                                <td style="width : 20%">
                                                                    {{$value3->name}}
                                                                    <input type="hidden" class="form-control child_sik_sub_detail_id" name="" value="{{$value3->id}}" id="">
                                                                </td>
                                                                <td style="text-align:right;width : 7%">
                                                                    {{round($value3->volume, 4)}}
                                                                </td>
                                                                <td style="text-align:right;width : 7%">
                                                                    {{round($value3->volume_pengawas, 4)}}
                                                                </td>
                                                                <td style="width : 5%">
                                                                {{$value3->satuan}}
                                                                </td>
                                                                <td style="width : 9%;text-align:right">
                                                                    {{number_format($value3->nilai)}}
                                                                </td>
                                                                <td style="width : 10%;text-align:right">
                                                                    {{number_format($value3->total_nilai)}}
                                                                </td>
                                                                <td style="width : 15%">
                                                                    <textarea type="" class="form-control child_keterangan" name="child_ket[]" value="" id="ket" style="height: 33px;min-width: 135px; margin: 0px; min-height: 92px; max-height: 150px; height: 92px; max-width: 300px width: 135px;" readonly>{{$value3->keterangan}}</textarea> 
                                                                </td>
                                                                <td style="width : 15%">
                                                                    <textarea type="" class="form-control child_keterangan_admin" name="child_ket[]" value="" id="ket" style="height: 33px;min-width: 135px; margin: 0px; min-height: 92px; max-height: 150px; height: 92px; max-width: 300px width: 135px;" readonly>{{$value3->keterangan_admin}}</textarea> 
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                    @php 
                                                    ($no++); 
                                                    $simpan[$key] = $itempekerjaan->id;
                                                    @endphp
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>

                                    {{-- {{$simpan[0]}} --}}
                                </div>

                                <div class="col-md-12">
                                    <table class="table table-bordered table-striped" style="width:100%" id="table_approval">
                                        <thead class="head_table">
                                            <tr >
                                                <td style="width: 15%;">Username</td>
                                                <td style="width: 15%;">Request At</td>
                                                <td style="width: 15%;">Status</td>
                                                <td style="width: 15%;">Time Left (days)</td>
                                                {{-- <td>Reason</td> --}}
                                            </tr>
                                        </thead>
                                        @if ( isset($vo->approval->histories))
                                            <tbody>
                                                @foreach ( $vo->approval->histories as $key2 => $value2 )
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
                                                        {{-- <td>{{ $value2->description or  '' }}</td> --}}
                                                    </tr>
                                                @endforeach
                                            </tbody>
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

    var table = $('#table_pekerjaan').DataTable( {
                scrollX: true,
                // scrollY: 100%,
                paging: false,
                ordering: false,
                searching: false,
                responsive: true,
                fixedHeader: {
                  header: true,
                },
                // autoWidth : false,
              });

    $('#table_approval').DataTable( {
        scrollX: true,
        // scrollY: 100%,
        paging: false,
        ordering: false,
        searching: false,
        responsive: true,
        fixedHeader: {
            header: true,
        },
        // autoWidth : false,
    });

    // $(document).ready(function(){
    //     $("#approve").click(function(){
    //         var vo_id = $("#vo_id").val();

    //         if ( confirm("Apakah anda yakin ingin Approve dokumen ini ?")){
    //             var request = $.ajax({
    //                 url : "{{ url('/')}}/access/vo/approve",
    //                 dataType : "json",
    //                 type : "post",
    //                 data : {
    //                     id :vo_id,
    //                 }
    //             });

    //             request.done(function(data){
    //                 if ( data.status == "0"){
    //                     alert("Dokumen telah diapprove");
    //                 }
    //                 window.location.reload();
    //             })
    //         }else{
    //             return false;
    //         }
    //         console.log(vo_id);
    //     });
    // });

    // $(document).ready(function(){
    //     $("#reject").click(function(){
    //         var vo_id = $("#vo_id").val();

    //         if ( confirm("Apakah anda yakin ingin Reject dokumen ini ?")){
    //             var request = $.ajax({
    //                 url : "{{ url('/')}}/access/vo/reject",
    //                 dataType : "json",
    //                 type : "post",
    //                 data : {
    //                     id :vo_id,
    //                 }
    //             });

    //             request.done(function(data){
    //                 if ( data.status == "0"){
    //                     alert("Dokumen telah direject");
    //                 }
    //                 window.location.reload();
    //             })
    //         }else{
    //             return false;
    //         }
    //         console.log(vo_id);
    //     });
    // });

    function setapproved(values,budget_id){
        if ( values == "6" ){
            $("#title_approval").attr("style","color:blue");
            $("#title_approval").text("These RAB will be APPROVED by You");
        }else{
            $("#title_approval").attr("style","color:red");
            $("#title_approval").text("These RAB will be REJECTED by You");
        }
        $("#btn_save_budgets").attr("data-value",values);
        $("#budget_id").val(budget_id);
    }

    function requestApproval(){
        if ( $("#btn_save_budgets").attr("data-value") == "7"){
            if ( $("#description2").val() == ""){
                alert("Silahkan isi dengan pesan terlebih dahulu");
                return false;
            }
        }
        var request = $.ajax({
            url : "{{ url('/') }}/access/vo/approve",
            type :"post",
            dataType :"json",
            data: {
                user_id : $("#user_id").val(),
                vo_id :$("#vo_id").val(),
                status : $("#btn_save_budgets").attr("data-value"),
                description :  $("#description2").val()
            },
            beforeSend: function() {
              waitingDialog.show();
            },
            success: function(data) { 
              if ( data.status == "0"){
                window.location.reload();
              }else{
                alert("Error When Saving Approval");
                window.location.reload();
              }
            },
            complete: function() {
              waitingDialog.hide(); 
            },
        });
    }
</script>

<div class="modal fade" tabindex="-1" role="dialog" id="myModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><br>
            </div>
            <div class="modal-body">
                <span id="title_approval"><strong></strong></span>
                <p></p>
                <div id="listdetail">
                    <textarea name="description2" id="description2" rows="6" style="width:100%"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btn_save_budgets" data-value="" onclick="requestApproval()">Submit</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
</body>
</html>
