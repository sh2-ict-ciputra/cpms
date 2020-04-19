<!DOCTYPE html>
<html>
@include('master.header')

<style type="text/css">
    #example3 th,
    #example3 td {
        white-space: nowrap;
    }

    @media only screen and (max-width: 600px) {
        .table {
            font-size :12px;
        }

        #label_rekap_penawaran {
            display: none;
        }

        .labeltable{
            font-size: 12px !important;
        }

        .box-body.tables{
            padding:0px !important;
        }

        .nav.nav-pills.ml-auto.p-2{
            font-size: 12px;
        }

        #detail_penawaran{
            font-size: 12px !important;
        }

        #example3_filter{
            display: none;
        }
    }

    
</style>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

    <!-- /.navbar -->
@include('user.sidebar')
<!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <h1>Project <strong>{{ $project->name or '' }}</strong></h1>
                    </div>
                </div>
            </div><!-- /.container-fluid -->

        </section>

        <!-- Main content -->
        <input type="hidden" name="project_id" id="project_id" value="{{ $usulan->tender->rab->workorder->project->first()->id }}"/>
        <input type="hidden" name="user_id" id="user_id" value="{{ $user->id }}"/>
        <input type="hidden" name="usulan_id" id="usulan_id" value="{{ $usulan->id }}"/>
        <input type="hidden" name="apporval_value" id="apporval_value">

        {{ csrf_field() }}
        <section class="content" style="font-size:14px;height:100%">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">Data Document <strong>Tender</strong></h3>
                        </div>
                        <!-- /.box-header -->

                        <div class="box-body">
                            <div class="col-md-12">
                                <div class="box-body table-responsive p-0">
                                    <table class="table table-hover table-striped table-bordered">
                                        <tr>
                                            <td style="background-color: grey;"><span style="color:white"><strong>No. Dokument</strong></span></td>
                                            <td>{{ $usulan->tender->no }}</td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: grey;"><span style="color:white"><strong>Project / Kawasan</strong></span></td>
                                            <td>
                                                {{ $usulan->tender->project->name or '' }} / {{ $usulan->tender->kawasan->name or 'Fasilitas Kota / Umum' }}
                                                @if ($usulan->tender->rab->units[0]->asset_type == "Modules\Project\Entities\Unit")
                                                    <button type="button" class="btn btn-primary " id="btn_tambah_unit" style="margin:0px 5px 0px 5px"><label id="label-unit">unit ({{count($usulan->tender->rab->workorder->details)}})</label></button>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: grey;"><span style="color:white"><strong>Paket Pekerjaan</strong></span></td>
                                            <td>
                                                <a href="{{ url('/')}}/access/workorder/detail?id={{ $usulan->tender->rab->workorder->id }}">Workorder : {{ $usulan->tender->rab->workorder->no or ''}}</a><br>
                                                <small>{{ $usulan->tender->rab->pekerjaans->last()->itempekerjaan->parent->code }}/ {{ $usulan->tender->rab->pekerjaans->last()->itempekerjaan->parent->name }}</small>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: grey;"><span style="color:white"><strong>RAB</strong></span></td>
                                            <td><a href="{{ url('/')}}/access/rab/detail/?id={{ $usulan->tender->rab->id }}">{{ $usulan->tender->rab->no }}</a></td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: grey;"><span style="color:white"><strong>Nilai ( Exc. Ppn )</strong></span></td>
                                            <td>Rp. {{ number_format($usulan->tender->rab->nilai,2) }}</td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: grey;"><span style="color:white"><strong>Jenis Tender</strong></span></td>
                                            <td>{{ $usulan->tender->tender_type->name }}</td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: grey;"><span style="color:white"><strong>Lampiran</strong></span></td>
                                            <td>
                                                <button type="button" class="btn btn-success" id="btn_upload"><label>Lampiran</label></button>    
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <h3><u><center>Unit</center></u></h3>
                            <table class="table-bordered table">
                                <tbody>
                                <tr>
                                    @foreach ( $usulan->tender->units as $key => $value )
                                        <td>
                                            @if($value->rab_unit->asset != "")
                                                {{ $value->rab_unit->asset->name }}
                                            @else
                                                {{ $value->rab_unit->project->name }}
                                            @endif
                                            @if ( $value->rab_unit->asset_type == "Modules\Project\Entities\Unit")<br>
                                            <span> Type : {{ $value->rab_unit->asset->type->name }} </span><br>
                                            <span> LT : {{ $value->rab_unit->asset->type->luas_tanah }} m</span><br>
                                            <span> LB : {{ $value->rab_unit->asset->type->luas_bangunan }} m</span><br>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>

                                </tbody>
                            </table>

                            {{-- <h3><u><center>List Approve</center></u></h3> --}}
                            <h3><u><center>Usulan Pemenang</center></u></h3>
                            <table class="table table-bordered" id="tabel_penawaran" style="margin-bottom: 15px;width:100%">
                                <thead style="background-color: #17a2b8;color:white;font-weight: bolder; ">
                                    <tr style="text-align:center">
                                        <td>Rekanan</td>
                                        @foreach( $usulan->tender->rekanans as $key2 => $value2)
                                            @if ($value2->approval->approval_action_id == 6)
                                                <td>{{ $value2->rekanan->group->name }}</td>
                                            @endif
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="" style="background-color: grey;color:white;text-align: right;font-weight: bolder;">Rekap</td>
                                        @foreach( $usulan->tender->rekanans as $key2 => $value2)
                                            @if ($value2->approval->approval_action_id == 6)
                                                <td style="text-align:center">
                                                    @if ($value2->id == $usulan->tender_rekanan_id)
                                                        <strong><h3>Pemenang</h3></strong>
                                                    @endif
                                                </td>
                                            @endif
                                        @endforeach
                                    </tr>
                                    @if(count($usulan->tender->tender_jadwal_penawaran) !=0 )
                                        @foreach($usulan->tender->tender_jadwal_penawaran as $key4 => $value4)
                                            <tr>
                                                <td style="text-align:center">
                                                <a href="{{ url('/')}}/access/tender/detail-penawaran?id={{$usulan->tender->id}}&step={{$value4->penawaran_ke}}&usulan={{$usulan->id}}" class="btn btn-warning">Detail Penawaran {{$value4->penawaran_ke}}</a>
                                                </td>
                                                @foreach( $usulan->tender->rekanans as $key5 => $value5)
                                                    @if ($value5->approval->approval_action_id == 6)
                                                        @php
                                                            $nilai = $value5->penawarans->take($value4->penawaran_ke)->last()->nilai;
                                                        @endphp
                                                        @if ($value5->id == $usulan->tender_rekanan_id && $key4+1 == $usulan->penawaran)
                                                            <td style="text-align:right;background:#7be07b">
                                                                <span style="font-size: 14px;color:black">{{ number_format($nilai) }}</span>
                                                            </td>
                                                        @else
                                                            <td style="text-align:right">
                                                                <span style="font-size: 14px;">{{ number_format($nilai) }}</span>
                                                            </td>
                                                        @endif
                                                    @endif
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                            @if($usulan->approval->histories->where("user_id",$user->id)->first() != '')
                                @if ( $usulan->approval->histories->where("user_id",$user->id)->first()->approval_action_id == "1")
                                    <br>
                                    <a href="#" class="btn btn-info" onclick="setapproved('6')" data-toggle="modal" data-target="#myModal">Approve</a>
                                    <a href="#" class="btn btn-danger" onclick="setapproved('7')" data-toggle="modal" data-target="#myModal">Reject</a>
                                    <br><br>
                                @elseif ( $usulan->approval->histories->where("user_id",$user->id)->first()->approval_action_id == "6")
                                    <span class="badge badge-success" style="font-size: 20px;">Approved</span>
                                @elseif ( $usulan->approval->histories->where("user_id",$user->id)->first()->approval_action_id == "7")
                                    <span class="badge badge-danger" style="font-size: 20px;">Reject</span>
                                @endif
                            @endif

                            <table class="table table-bordered table-striped">
                                <tr class="header_1">
                                    <td style="width: 15%;">Username</td>
                                    <td style="width: 15%;">Request At</td>
                                    <td style="width: 15%;">Status</td>
                                    <td style="width: 15%;">Time Left (days)</td>
                                    <td>Reason</td>
                                </tr>
                                @if ( isset($approval->histories))
                                    @foreach ( $approval->histories as $key2 => $value2 )
                                        <tr>
                                            <td>
                                                @if ( $value2->approval_action_id == "6")
                                                    <strong>{{ $value2->user->user_name or '' }}</strong>
                                                @else
                                                    {{ $value2->user->user_name or '' }}
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
                            <!-- ./box -->
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
    {{-- @include("master.copyright") --}}


</div>
<!-- ./wrapper -->
@include('user.footer')
@include("master/footer_table")
@include('access::user.tender_js')

<script type="text/javascript">
  $('#tabel_penawaran').DataTable( {
      "paging" : false,
      "order": false,
      "scrollX": true,
      "searching":false,
      // scrollY: "500px",
      // scrollCollapse: true,
    } );

    function setapproved(values,budget_id){
        if ( values == "6" ){
            $("#title_approval").attr("style","color:blue");
            $("#title_approval").text("Usulan Pemenang Tener akan di APPROVED");
        }else{
            $("#title_approval").attr("style","color:red");
            $("#title_approval").text("Usulan Pemenang Tender akan di REJECTED");
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
            url : "{{ url('/') }}/access/usulanPemenang/approval",
            type :"get",
            dataType :"json",
            data: {
                user_id : $("#user_id").val(),
                usulan_id :$("#usulan_id").val(),
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

    $("#btn_tambah_unit").click(function(){
      $('#ModalUnit').modal('show');
    });

    $("#btn_upload").click(function(){
      $('#ModalUploadFile').modal('show');
    });
</script>


<div class="modal fade " id="ModalUploadFile" tabindex="-1" role="dialog" aria-labelledby="largeModal" aria-hidden="true" style="overflow-y:auto;">
    <div style="width: 90%; transform: translate(0, 30%);" class="modal-dialog modal-lg ">
      <div class="modal-content">
        <div class="modal-header">
            {{-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> --}}
            <h3 class="modal-title pull-left" id="">Lampiran</h3>
        </div>
        <!-- <form class="form-horizontal" > -->
            <div class="modal-body">
              <div class="tab-pane table-responsive" id="tab_2">
                <div class="form-group row col-md-12" style="margin:5px 5px 5px 5px">
                  <table class="table" style="width:100%;" id="table_upload">
                    <thead class="head_table">
                      <tr>
                        <td>Kategori</td>
                        <td>File</td>
                        <td>Name</td>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach($dokumen as $key => $value)
                            @if ($value->filenames != null)
                                <tr class="test">
                                    <td>
                                        <input type="text" class="form-control kategori" name="kategori[]" autocomplete="off" style="width:100%;" value="{{$value->document_name}}">
                                    </td>
                                    <td style="text-align:center">
                                        <a class="btn btn-info" href="{{url('/')}}/workorder/downloaddoc?id={{$value->id}}" data-url="{{$value->filenames}}">Download </a>
                                        <br>
                                        @if (count(explode("/", $value->filenames)) > 4)
                                          {{explode("/", $value->filenames)[4]}}
                                        @endif
                                    </td>
                                    <td>
                                        <input type="text" class="form-control file_name" name="file_name[]" autocomplete="off" style="width:100%;" value="{{$value->name}}">
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div> 
            <div class="modal-footer" style="text-align: center;">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <!-- <input type="hidden" name="all_send" id="all_send" /> -->
            </div>
        <!-- </form> -->
      </div>
    </div>
</div>


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

<div class="modal fade " id="ModalUnit" tabindex="-1" role="dialog" aria-labelledby="largeModal" aria-hidden="true" style="overflow-y:auto">
    <div style="width: 90%;transform: translate(0, 30%);" class="modal-dialog modal-lg ">
      <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3 class="modal-title" id="myModalLabel"></h3>
        </div>
        <!-- <form class="form-horizontal" > -->
        <div class="modal-body">
          <div class="tab-pane table-responsive" id="tab_2">
            <div class="form-group row col-md-12" style="margin:5px 5px 5px 5px">
            </div>
            <div class="form-group row col-md-12" style="margin:5px 5px 5px 5px">
              <table class="table" style="width:100%;" id="table_unit">
                <thead class="head_table">
                  <tr>
                    <td>Unit Name</td>
                    <td>Type</td>
                    <td>Luas Tanah</td>
                    <td>Luas Bangunan</td>
                  </tr>
                </thead>
                <tbody>
                  @foreach ( $usulan->tender->rab->units as $key => $value )
                  <tr>
                      @if ($value->asset_type == "Modules\Project\Entities\Unit")
                        <td>{{ $value->asset->name }}</td>
                        <td> {{ $value->asset->type->name }} </td>
                        <td>{{ $value->asset->type->luas_tanah }} m2</td>
                        <td>{{ $value->asset->type->luas_bangunan }} m2</td>
                      @endif
                  </tr>
                @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div> 
        <div class="modal-footer" style="text-align: center;">
          <!-- <input type="hidden" name="all_send" id="all_send" /> -->
        </div>
        <!-- </form> -->
      </div>
    </div>
  </div>
</html>
