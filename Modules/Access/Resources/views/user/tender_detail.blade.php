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
        <input type="hidden" name="project_id" id="project_id" value="{{ $tender->rab->workorder->project->first()->id }}"/>
        <input type="hidden" name="user_id" id="user_id" value="{{ $user->id }}"/>
        <input type="hidden" name="approval_id" id="approval_id" value="{{ $approval_id }}"/>
        <input type="hidden" name="tender_id" id="tender_id" value="{{ $tender->id }}"/>
        <input type="hidden" name="apporval_value" id="apporval_value">
        <input type="hidden" name="rab_id" id="rab_id" value="{{ $tender->rab->id }}"/>

        {{ csrf_field() }}
        <section class="content" style="font-size:17px;height: 100%;">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">Data Document <strong>Tender</strong></h3>
                        </div>
                        <!-- /.box-header -->

                        <div class="box-body">
                            {{-- <div class="col-md-12">
                                <div class="box-body table-responsive p-0">
                                    <table class="table table-hover table-striped table-bordered">
                                        <tr>
                                            <td style="background-color: grey;"><span style="color:white"><strong>No. Dokument</strong></span></td>
                                            <td>{{ $tender->no }}</td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: grey;"><span style="color:white"><strong>Project / Kawasan</strong></span></td>
                                            <td>{{ $tender->project->name or '' }} / {{ $tender->kawasan->name or 'Fasilitas Kota / Umum' }}
                                            @if ($tender->rab->units[0] == "\Modules\Project\Entities\Unit")
                                                <button type="button" class="btn btn-primary " id="btn_tambah_unit" style="margin:0px 5px 0px 5px"><label id="label-unit">unit ({{count($tender->rab->workorder->details)}})</label></button>
                                            @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: grey;"><span style="color:white"><strong>Paket Pekerjaan</strong></span></td>
                                            <td>
                                                <a href="{{ url('/')}}/access/workorder/detail?id={{ $tender->rab->workorder->id }}">Workorder : {{ $tender->rab->workorder->no or ''}}</a><br>
                                                <small>{{ $tender->rab->pekerjaans->last()->itempekerjaan->parent->code }}/ {{ $tender->rab->pekerjaans->last()->itempekerjaan->parent->name }}</small>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: grey;"><span style="color:white"><strong>RAB</strong></span></td>
                                            <td><a href="{{ url('/')}}/access/rab/detail/?id={{ $tender->rab->id }}">{{ $tender->rab->no }}</a></td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: grey;"><span style="color:white"><strong>Nilai ( Exc. Ppn )</strong></span></td>
                                            <td>Rp. {{ number_format($tender->rab->nilai,2) }}</td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: grey;"><span style="color:white"><strong>Jenis Tender</strong></span></td>
                                            <td>{{ $tender->tender_type->name }}</td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: grey;"><span style="color:white"><strong>Lampiran</strong></span></td>
                                            <td>
                                                <button type="button" class="btn btn-success" id="btn_upload"><label>Lampiran</label></button>    
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div> --}}
                            <div class="card-body col-md-12">
                                <h3 class="card-title">Data RAB</h3>
                                <table id="example1" class="table table-bordered table-striped" style="width:100%">               
                                  
                                  <tr>
                                    <td style="background-color: grey;"><span style="color:white"><strong>No TENDER</strong></span></td>
                                    <td>{{ $tender->no }}</td>
                                  </tr>
                                 
                                  <tr>
                                    <td style="background-color: grey;"><span style="color:white"><strong>COA / Pekerjaan</strong></span></td>
                                    <td>{{ $tender->rab->pekerjaans->last()->itempekerjaan->parent->code }}/ {{ $tender->rab->pekerjaans->last()->itempekerjaan->parent->name }}</td>
                                  </tr>
                                  
                                  <tr>
                                    <td style="background-color: grey;"><span style="color:white"><strong>Dept. From</strong></span></td>
                                    <td>{{ $tender->rab->workorder->departmentFrom->name }}</td>
                                  </tr>
                                  <tr>
                                    <td style="background-color: grey;"><span style="color:white"><strong>Subtotal</strong></span></td>
                                    <td>{{ number_format($tender->rab->nilai) }}</td>
                                  </tr>
                                  <tr>
                                    <td style="background-color: grey;"><span style="color:white"><strong>PPN</strong></span></td>
                                    <td>{{ number_format(0.1 * $tender->rab->nilai) }}</td>
                                  </tr>
                                  <tr>
                                    <td style="background-color: grey;"><span style="color:white"><strong>Grand Total</strong></span></td>
                                    <td>{{ number_format($tender->rab->nilai + (0.1 * $tender->rab->nilai )) }}</td>
                                  </tr>
                                  <tr>
                                    <td style="background-color: grey;"><span style="color:white"><strong>Lokasi</strong></span></td>
                                    <td>                    
                                      @if ($tender->rab->workorder->projectKawasan != null)
                                        {{$tender->rab->workorder->projectKawasan->name}}
                                        @if ($tender->rab->units[0]->asset_type == "Modules\Project\Entities\Unit")
                                          <button type="button" class="btn btn-primary " id="btn_tambah_unit" style="margin:0px 5px 0px 5px"><label id="label-unit">unit ({{count($tender->rab->workorder->details)}})</label></button>
                                        @endif
                                      @else
                                        FASKOT
                                      @endif
                                     
                                    </td>
                                  </tr>
                                  <tr>
                                    <td style="background-color: grey;"><span style="color:white"><strong>No. RAB</strong></span></td>
                                    <td>{{ $tender->rab->no }}</td>
                                  </tr>
                                  <tr>
                                    <td style="background-color: grey;"><span style="color:white"><strong>Lampiran</strong></span></td>
                                    <td>
                                      <button type="button" class="btn btn-success" id="btn_upload"><label>Lampiran</label></button>
                                    </td>
                                  </tr>
                                  @if ($tender->kelas_id != 3)
                                    <tr>
                                        <td style="background-color: grey;"><span style="color:white"><strong>Berita Acara</strong></span></td>
                                        <td>
                                            @if ($tender->berita_acara_tunjuk_langsung != null)
                                                <input type="hidden" id="beritaacara_id" value="{{$tender->berita_acara_tunjuk_langsung->id}}">
                                            @else
                                                <input type="hidden" id="beritaacara_id" value="">
                                            @endif
                                        <button type="button" class="btn btn-success" id="cetak_batl"><label>Lampiran Berita Acara</label></button>
                                        </td>
                                    </tr> 
                                  @endif
                                </table><br>
                              </div>


                            {{-- <div class="col-md-12">
              
                              <!-- <form action="{{ url('/')}}/access/tender/document/save" method="post" name="form1">
                                {{ csrf_field() }}
              
                              <input type="hidden" name="tender_docs" value="{{ $tender->id }}">
                              <table class="table table-hover table-striped table-bordered">
                                <tr style="background-color: #17a2b8;color:white; ">
                                  <td colspan="2">Checked By</td>
                                  @if ( $tender->tender_document->count() > 0 )
                                  @foreach($tender->tender_document->first()->document_approval as $key2 => $value2 )
                                  <td>{{ $value2->user->user_name }}</td>
                                  @endforeach
                                  @endif
                                </tr>
                                @foreach ( $tender->tender_document as $key => $value )
                                <tr>
                                  <td style="background-color: grey;"><strong><span style="color:white">Jenis Dokumen</span></strong></td>
                                  <td>{{ $value->document_name }}</td>
                                  @foreach($value->document_approval as $key2 => $value2 )
                                  @if ( $value2->no_urut < 5)
                                    @php $jabatan = "dir"; @endphp
                                  @else
                                    @php $jabatan = ""; @endphp
                                  @endif
                                  <td>
                                    @if ( $value2->user_id == $user->id )
                                      @if ( $value2->status == 1 )
                                        <input  type="radio" name="status[{{$key}}]" id="approved{{$value2->id}}" value="6" checked>
                                        <span class="badge bg-success"><strong>Approve</strong></span><br>
                                        <input  type="radio" name="status[{{$key}}]" id="rejected{{$value2->id}}" value="7">
                                        <span class="badge bg-danger"><strong>Rejected</strong></span><br>
                                        <input type="hidden" name="document_id[{{ $key  }}]" value="{{ $value2->id }}">
              
                                      @elseif ( $value2->status == null )
                                        <span>Belum Proses</span>
                                      @else
                                         @if ( $value2->status == "6")
                                          <span class="label label-success">Diterima</span>
                                          @else
                                          <span class="label label-danger">Ditolak</span>
                                          @endif
                                      @endif
                                    @else
                                      @if ( $value2->status == "6")
                                      <span class="label label-success">Diterima</span>
                                      @elseif ( $value2->status == "7")
                                      <span class="label label-danger">Ditolak</span>
                                      @elseif ( $value2->status == "1")
                                      <span class="label label-warning">Dalam Proses</span>
                                      @elseif ( $value2->status == null )
                                      <span>Belum Proses</span>
                                      @endif         
                                    
                                    @endif
                                  </td>
                                  @endforeach
                                </tr>
                                @endforeach
                              </table>         
                                 
                              <button type="submit" class="btn btn-primary">Simpan</button>     
                                       
                              </form> -->
              
                            </div> --}}
                            {{-- <table class="table-bordered table">
                                <thead class="head_table">
                                    <tr>
                                        <td>Gambar Tender</td>
                                        <td>BQ / Bill Item</td>
                                        <td>Spesifikasi Teknis</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            @php 
                                            $workorder = Modules\Workorder\Entities\WorkorderBudgetDetail::find($tender->rab->workorder_budget_detail_id);

                                            $gambar = Modules\Tender\Entities\TenderDocument::where('workorder_budget_id',$workorder->id)->where('document_name','Gambar Tender')->get(); @endphp
                                            
                                            @if(count($gambar)>0)
                                                @foreach($gambar as $key)                                
                                                <a class="btn btn-info" href="{{url('/')}}/workorder/downloaddoc?id={{$key->id}}" >Download </a>
                                                @endforeach
                                            @elseif(count($gambar)<=0)
                                                Tidak di upload
                                            @endif
                                        </td>
                                        <td>
                                            @php 
                                            $gambar = Modules\Tender\Entities\TenderDocument::where('workorder_budget_id',$workorder->id)->where('document_name','BQ / Bill Item')->get(); @endphp
                                            
                                            @if(count($gambar)>0)  
                                                @foreach($gambar as $key)                            
                                                <a class="btn btn-info" href="{{url('/')}}/workorder/downloaddoc?id={{$key->id}}" >Download </a>
                                                @endforeach
                                            @elseif(count($gambar)<=0)
                                            Tidak di Upload
                                            @endif
                                        </td>
                                        <td>
                                            @php 
                                            $gambar = Modules\Tender\Entities\TenderDocument::where('workorder_budget_id',$workorder->id)->where('document_name','Spesifikasi Teknis')->get(); @endphp
                                        
                                            @if(count($gambar)>0) 
                                                @foreach($gambar as $key1)                               
                                                <a class="btn btn-info" href="{{url('/')}}/workorder/downloaddoc?id={{$key1->id}}" >Download </a>
                                                @endforeach
                                            @elseif(count($gambar)<=0)
                                                Tidak di Upload
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table> --}}
                            {{-- <h3><u><center>Unit</center></u></h3>
                            <table class="table-bordered table">

                                <tbody>

                                <tr>
                                    @foreach ( $tender->units as $key => $value )
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
                            </table> --}}

                            {{-- <h3><u><center>List Approve</center></u></h3> --}}

                            <h3><u><center>List Approve Rekanan</center></u></h3>
                            <table class="table table-hover table-striped table-bordered" style="width:100%;margin-bottom:15px" id="table_rekanan">
                                <thead class="head_table">
                                    <tr>
                                        <td colspan="5" style="background-color: grey;color:white;font-weight: bolder;"><center>Daftar Rekanan</center></td>
                                    </tr>
                                    <tr style="background-color: #17a2b8 ">
                                        <td style="padding-left: 0xp !important;">Rekanan</td>
                                        <td>Address</td>
                                        <td>Contact Number</td>
                                        {{-- <td>Spesifikasi</td> --}}
                                        <td>Approval Status</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($tender->rekanans as $key2 => $each )
                                            <tr>
                                                <td>{{ $each->rekanan->group->name }} </td>
                                                <td>{{ $each->rekanan->surat_alamat }} </td>
                                                <td>{{ $each->rekanan->telp }} </td>
                                                {{-- <td style="background-color: white;color:white;">
                                                    <ul>
                                                        @foreach ( $each->rekanan->group->spesifikasi as $key => $value )
                                                            <li style="color:black;">{{ $value->itempekerjaan->name  }}</li>
                                                        @endforeach
                                                    </ul>
                                                </td> --}}
                                                @if ( $each->approval->histories->where("approval_id",$each->approval->id)->where("user_id",$user->id)->count() > 0 )
                                                    @if ( $each->approval->histories->where("approval_id",$each->approval->id)->where("user_id",$user->id)->first()->approval_action_id == "6" )
                                                        <td style="background-color: green;color:white;"><strong>APPROVED</strong></td>
                                                    @elseif ( $each->approval->histories->where("approval_id",$each->approval->id)->where("user_id",$user->id)->first()->approval_action_id == "7" )
                                                        <td style="background-color: red;color:white;"><strong>REJECTED</strong></td>
                                                    @endif

                                                    @if ($each->approval->histories->where("approval_id",$each->approval->id)->where("user_id",$user->id)->first()->approval_action_id == "2" || $each->approval->histories->where("approval_id",$each->approval->id)->where("user_id",$user->id)->first()->approval_action_id == "1")
                                                        <td style="background-color: white;color:white;">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="approve{{$each->approval->id}}" id="approved{{$each->approval->id}}" value="6" checked>
                                                                <span class="badge bg-success"><strong>Approve</strong></span><br>
                                                                <input class="form-check-input" type="radio" name="approve{{$each->approval->id}}" id="rejected{{$each->approval->id}}" value="7">
                                                                <span class="badge bg-danger"><strong>Rejected</strong></span><br>
                                                            </div>
                                                        </td>
                                                    @endif
                                                @endif
                                            </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            @if ( $request_tender_rekanan > 0 )
                                @if ( $tender->check_rejected == "0" )
                                    <button class="btn btn-info" onclick="requestRekanan()" data-toggle="modal" data-target="#myModal2" style="margin:10px 0px 10px 0px">Send Approve</button>
                                @endif
                            @endif

                            <table class="table table-bordered table-striped " style="width:100%" id="table_user_approve">
                                <thead class="head_table">
                                    <tr class="header_1">
                                        <td style="width:25%">Username</td>
                                        <td style="width:20%">Request At</td>
                                        <td style="width:3%">PT Status</td>
                                        <td style="width:25%">Time Left (days)</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ( isset($tender->rekanans[0]->approval->histories))
                                        @foreach ( $tender->rekanans[0]->approval->histories as $key2 => $value2 )
                                            <tr>
                                                <td style="width:25%">
                                                    @if ( $value2->approval_action_id == "6")
                                                        <input type="checkbox" name="approval_id" value="" id="" disabled checked> <strong>{{ $value2->user->user_name or '' }}</strong>
                                                    @else
                                                        <input type="checkbox" name="approval_id" value="" id="" disabled>{{ $value2->user->user_name or '' }}
                                                    @endif
                                                </td>
                                                <td style="width:20%">{{ $value2->created_at->format("d M Y ") }}</td>
                                                <td style="width:30%">
                                                    <table style="width:100%">
                                                        @foreach($tender->rekanans as $key2 => $each )
                                                            <tr>
                                                                <td style="width:50%">
                                                                    {{$each->rekanan->group->name}}
                                                                </td>
                                                                <td style="width:50%">
                                                                    @if ( $each->approval->histories->where("user_id", $value2->user->id)->first()->approval_action_id == "7" )
                                                                        <span class=""><strong style="color:red">Reject</strong></span>({{$each->approval->histories->where("user_id", $value2->user->id)->first()->description}})
                                                                    @elseif ( $each->approval->histories->where("user_id", $value2->user->id)->first()->approval_action_id == "6")
                                                                        <span class=""><strong style="color:green">Approve</strong></span>({{$each->approval->histories->where("user_id", $value2->user->id)->first()->description}})
                                                                    @else
                                                                        <span class=""><strong>Waiting</strong></span>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </td>
                                                <td style="width:25%">
                                                    <strong>
                                                        @php
                                                            $str = $value2->created_at;
                                                            $str = strtotime(date("M d Y ")) - (strtotime($str));
                                                            echo ceil($str/3600/24);
                                                        @endphp
                                                    </strong>
                                                    (days)
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
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
{{-- <script type="text/javascript">
    function setpemenang(id){
        if ( confirm("Apakah anda yakin ingin mengusulkan rekanan ini sebagai pemenang ?")){
            var request = $.ajax({
                url : "{{ url('/')}}/access/tender/setpemenang",
                dataType : "json",
                data : {
                    id : $("#tender_rekanan_id").val(),
                    description_pemenang_tender : $("#description_pemenang_tender").val()

                },
                type : "post"
            });

            request.done(function(data){
                if ( data.status == "0"){
                    alert("Rekanan telah diusulkan sebagai pemenang")
                }

                window.location.reload();
            })
        }else{
            return false;
        }
    }
</script> --}}
@include('access::user.tender_js')

<script type="text/javascript">

    $(document).ready(function() {
        $('#example3').DataTable( {
            scrollY:        300,
            scrollX:        true,
            scrollCollapse: true,
            paging:         false,
            fixedColumns:   {
                leftColumns: 1,
            }
        } );
    });

    function disablebtn(id){
        var valor = [];
        $('input.paramdisable[type=checkbox]').each(function () {
            if (this.checked)
                valor.push($(this).val());
        });

        console.log(valor.length);

        if (valor.length < 4 ) {
            $("#btn_approval_rekanan").attr("disabled","disabled");
        }else{
            $("#btn_approval_rekanan").removeAttr("disabled");
        }
    }

    function setrekanan_id(id){
        $("#tender_rekanan_id").val(id);
    }

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
            url : "{{ url('/') }}/access/rab/approval",
            data: {
                user_id : $("#user_id").val(),
                rab_id :$("#rab_id").val(),
                status : $("#btn_save_budgets").attr("data-value"),
                description :  $("#description2").val()
            },
            type :"get",
            dataType :"json"
        });

        request.done(function(data){
            if ( data.status == "0"){
                window.location.reload();
            }else{
                alert("Error When Saving Approval");
                window.location.reload();
            }
        })
    }

    var table = $('#table_rekanan').DataTable( {
        scrollX: true,
        paging: false,
        ordering: false,
        searching: false,
    });

    var table2 = $('#table_user_approve').DataTable( {
        scrollX: true,
        paging: false,
        ordering: false,
        searching: false,
    });

    $("#btn_upload").click(function(){
      $('#ModalUploadFile').modal('show');
    });
    $("#btn_tambah_unit").click(function(){
      $('#ModalUnit').modal('show');
    });

    $(document).on('click', '#cetak_batl', function() {
    var _url = "{{ url('/tender/cetakBeritaAcara') }}";
    var beritaacara_id = $("#beritaacara_id").val();
    window.open("/tender/cetakBeritaAcara?ba_id="+beritaacara_id,'_blank');
  });
</script>
{{-- <div class="modal fade" tabindex="-1" role="dialog" id="myModal4">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><br>
            </div>
            <div class="modal-body">
                <span id="title_approvaled"><strong></strong></span>
                <p></p>
                <div id="listdetail">
                    <textarea name="description" id="description" rows="6" style="width:100%"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btn_saved_tendered" data-value="" onclick="requestTender()">Save changes</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal --> --}}

<div class="modal fade" tabindex="-1" role="dialog" id="myModal2">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><br>
            </div>
            <div class="modal-body">
                <span id="title_approval"><strong>Preview Approve</strong></span>
                <p></p>
                <table style="width: 100%;" class="table table-striped table-bordered">
                    <thead style="background-color: #17a2b8 ">
                    <tr>
                        <td>Name</td>
                        <td>Status</td>
                        <td>Description</td>
                    </tr>
                    </thead>
                    <tbody id="bodylist">
                    @foreach($tender->rekanans as $key2 => $each )
                        @if ( $each->approval->histories->where("approval_id",$each->approval->id)->first()->approval_action_id == "2" )
                            <tr>
                                <td>{{ $each->rekanan->group->name }}</td>
                                <td id="status{{$each->approval->id}}"></td>
                                <td><input type="text" name="description" id="description{{ $each->approval->id }}"></td>
                            </tr>
                        @endif
                    @endforeach
                    </tbody>
                </table>
                <input type="hidden" name="apporval_value" id="apporval_value">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" id="btn_save_budgets" data-value="" onclick="requestRekananApproval()">Submit</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

{{-- <div class="modal fade" tabindex="-1" role="dialog" id="myModal5">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><br>
            </div>
            <div class="modal-body">
                <span><strong>Rekanan ini diusulkan sebagai pemenang</strong></span>
                <p></p>
                <div id="listdetail">
                    <input type="hidden" name="tender_rekanan_id" id="tender_rekanan_id">
                    <textarea name="description_pemenang_tender" id="description_pemenang_tender" rows="6" style="width:100%"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btn_save_menang"  onclick="setpemenang('{{ $value2->id }}')">Save changes</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

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
                <button type="button" class="btn btn-primary" id="btn_save_budgets" data-value="" onclick="requestApproval()">Save changes</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal --> --}}

<div class="modal fade " id="ModalUploadFile" tabindex="-1" role="dialog" aria-labelledby="largeModal" aria-hidden="true" style="overflow-y:auto;">
    <div style="width: 90%" class="modal-dialog modal-lg ">
      <div class="modal-content">
        <div class="modal-header">
            {{-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> --}}
            <h3 class="modal-title pull-left" id="">Upload File</h3>
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

<div class="modal fade " id="ModalUnit" tabindex="-1" role="dialog" aria-labelledby="largeModal" aria-hidden="true" style="overflow-y:auto">
    <div style="width: 90%" class="modal-dialog modal-lg " style="transform: translate(0, 50%);">
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
                  @foreach ( $tender->rab->units as $key => $value )
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

</body>
</html>
