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
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/fixedheader/3.1.2/css/fixedHeader.dataTables.min.css">
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
 
  <!-- /.navbar -->
  @include('user.sidebar')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header" style="margin-bottom: 10px; height:100%">
      <div class="container-fluid">
        <div class="row mb-2">
          {{-- <div class="col-sm-12">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
              <li class="breadcrumb-item">RAB</li>
              <li class="breadcrumb-item active">Detail</li>
            </ol>
          </div> --}}
          <div class="col-sm-12">
            <h1>Project <strong>{{ $rab->project->name or '' }}</strong></h1>
          </div>
        </div>
      </div><!-- /.container-fluid -->      
      <a href="{{ url('/') }}/access/" class="btn btn-warning">Kembali</a>  
      {{-- @if($rab->approval->histories->where("user_id",$user->id)->first() != '')
          
      @endif --}}
      {{-- @if ( $rab->approval->histories->where("user_id",$user->id)->first()->approval_action_id == "1")    
      <a href="#" class="btn btn-info" onclick="setapproved('6')" data-toggle="modal" data-target="#myModal">Approve</a>
      <a href="#" class="btn btn-danger" onclick="setapproved('7')" data-toggle="modal" data-target="#myModal">Reject</a>
      @elseif ( $rab->approval->histories->where("user_id",$user->id)->first()->approval_action_id == "6")  
      <span class="badge badge-success" style="font-size: 20px;">Approved</span>  
      @endif --}}
    </section>

    <!-- Main content -->
    <input type="hidden" name="project_id" id="project_id" value="{{ $rab->workorder->project->first()->id }}"/>
    <input type="hidden" name="user_id" id="user_id" value="{{ $user->id }}"/>
    <input type="hidden" name="rab_id" id="rab_id" value="{{ $rab->id }}"/>
    <section class="content" style="font-size:14px;">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
            </div>
            <!-- /.card-header -->
            <div class="card-body col-md-12">
              <h3 class="card-title">Data RAB</h3>
              <table id="example1" class="table table-bordered table-striped" style="width:100%">               
                
                <tr>
                  <td style="background-color: grey;"><span style="color:white"><strong>No RAB</strong></span></td>
                  <td>{{ $rab->no }}</td>
                </tr>
               
                <tr>
                  <td style="background-color: grey;"><span style="color:white"><strong>COA / Pekerjaan</strong></span></td>
                  <td>{{ $rab->pekerjaans->last()->itempekerjaan->parent->code }}/ {{ $rab->pekerjaans->last()->itempekerjaan->parent->name }}</td>
                </tr>
                
                <tr>
                  <td style="background-color: grey;"><span style="color:white"><strong>Dept. From</strong></span></td>
                  <td>{{ $rab->workorder->departmentFrom->name }}</td>
                </tr>
                <tr>
                  <td style="background-color: grey;"><span style="color:white"><strong>Subtotal</strong></span></td>
                  <td>{{ number_format($rab->nilai) }}</td>
                </tr>
                <tr>
                  <td style="background-color: grey;"><span style="color:white"><strong>PPN</strong></span></td>
                  <td>{{ number_format(0.1 * $rab->nilai) }}</td>
                </tr>
                <tr>
                  <td style="background-color: grey;"><span style="color:white"><strong>Grand Total</strong></span></td>
                  <td>{{ number_format($rab->nilai + (0.1 * $rab->nilai )) }}</td>
                </tr>
                <tr>
                  <td style="background-color: grey;"><span style="color:white"><strong>Lokasi</strong></span></td>
                  <td>                    
                    {{-- @foreach ( $rab->units as $key => $value )
                      @if ( $value->asset_type != "\Modules\Project\Entities\Unit")
                      <span style="font-size: 15px;text-transform: uppercase"><strong>{{ $value->asset->name or '' }}</strong></span><br>
                      @endif
                     @endforeach
                    
                    @if ( count($rab->blok_list) > 0 ) 
                    @for ( $i=0; $i < count($rab->blok_list); $i++ )
                      <span style="font-size: 15px;text-transform: uppercase">{{ \Modules\Project\Entities\Blok::find($rab->blok_list[$i])->kawasan->name }}/<strong>{{ \Modules\Project\Entities\Blok::find($rab->blok_list[$i])->name }}</strong></span><br>
                    @endfor
                    @endif --}}
                    @if ($rab->workorder->projectKawasan != null)
                      {{$rab->workorder->projectKawasan->name}}
                      @if ($rab->units[0]->asset_type == "Modules\Project\Entities\Unit")
                        <button type="button" class="btn btn-primary " id="btn_tambah_unit" style="margin:0px 5px 0px 5px"><label id="label-unit">unit ({{count($rab->workorder->details)}})</label></button>
                      @endif
                    @else
                      FASKOT
                    @endif
                   
                  </td>
                </tr>
                <tr>
                  <td style="background-color: grey;"><span style="color:white"><strong>No. Workorder</strong></span></td>
                  <td>{{ $rab->workorder->no }}</td>
                </tr>
                <tr>
                  <td style="background-color: grey;"><span style="color:white"><strong>Lampiran</strong></span></td>
                  <td>
                    <button type="button" class="btn btn-success" id="btn_upload"><label>Lampiran</label></button>
                  </td>
                </tr>
              </table><br>
            </div>

            {{-- <h3><u><center>Unit</center></u></h3>
            <table class="table-bordered table">

              <tbody>

              <tr>
                  @foreach ( $rab->units as $key => $value )
                      <td>
                          @if($value->asset != "")
                              {{ $value->asset->name }}
                          @else
                              {{ $value->project->name }}
                          @endif
                          @if ( $value->asset_type == "Modules\Project\Entities\Unit")<br>
                          <span> Type : {{ $value->asset->type->name }} </span><br>
                          <span> LT : {{ $value->asset->type->luas_tanah }} m</span><br>
                          <span> LB : {{ $value->asset->type->luas_bangunan }} m</span><br>
                          @endif
                      </td>
                  @endforeach
              </tr>

              </tbody>
          </table> --}}

            <h3><u><center>Item Pekerjaan</center></u></h3>
            <div class="col-md-12"> 

            <table class="table table-bordered" id="table_rab">
                <thead class="head_table">
                    <tr>
                        <td style="width:10%">COA Pekerjaan</td>
                        <td style="width:35%">Item Pekerjaan</td>
                        <td style="width:10%">Volume</td>
                        <td style="width:10%">Sat</td>
                        <td style="width:15%">Hrg Sat (Rp/..)</td>
                        <td style="width:15%">Subtotal</td>
                        <td style="width:5%">Bobot(%)</td>
                    </tr>
                </thead>
                <tbody>
                    @if ( $rab->units->count() > 0 )
                        @foreach($rab->pekerjaans as $key => $value )
                            @if($value->volume != 0)
                                <tr>
                                    <td >
                                      <strong>{{ $value->itempekerjaan->code }}</strong>
                                    </td>
                                    <td><strong>{{ $value->itempekerjaan->name }}</strong></td>
                                    <td style="text-align:right">
                                        <span class="labels" id="label_rab_volume_{{ $value->id }}">{{ number_format($value->volume) }}</span>
                                        <input class="values" type="text" id="input_rab_volume_{{ $value->id}}" value="{{ $value->volume }}" style="display: none;">
                                    </td>
                                    <td>
                                        <span class="labels" id="label_rab_satuan_{{ $value->id }}">{{ $value->satuan }}</span>
                                        <input class="values" type="text" id="input_rab_satuan_{{ $value->id}}" value="{{ $value->satuan }}" style="display: none;">
                                    </td>
                                    <td style="text-align:right">
                                        @if ($value->total_nilai == null && $value->total_nilai == 0)
                                          <span class="labels" id="label_rab_nilai_{{ $value->id }}">{{ number_format($value->nilai) }}</span>
                                        @endif
                                    </td>
                                    <td style="text-align:right">
                                        @if ($value->total_nilai == null && $value->total_nilai == 0)
                                          <span class="labels" id="label_rab_nilai_{{ $value->id }}">{{ number_format($value->nilai*$value->volume) }}</span>
                                        @else
                                          <span class="labels" id="label_rab_nilai_{{ $value->id }}">{{ number_format($value->total_nilai) }}</span>
                                        @endif
                                    </td>
                                    {{-- <td style="text-align:right">
                                        @if ($value->total_nilai == null && $value->total_nilai == 0)
                                          <span class="labels" id="label_rab_nilai_{{ $value->id }}">{{ number_format($value->nilai*$value->volume) }}</span>
                                        @else
                                          <span class="labels" id="label_rab_nilai_{{ $value->id }}"> {{ number_format((($value->total_nilai) / ( $rab->nilai / $rab->units->count() ) * 100)) }}</span>
                                        @endif
                                    </td> --}}
                                    <td style="text-align:right">
                                      @if($value->total_nilai != null && $value->total_nilai != 0)
                                          <strong><span class="labels" id="label_rab_nilai_{{ $value->id }}"> {{ number_format((($value->total_nilai) / ( $rab->nilai / $rab->units->count() ) * 100),2) }}</span></strong>
                                      @else
                                          <strong><span class="labels" id="label_rab_nilai_{{ $value->id }}"> {{ number_format((($value->nilai * $value->volume) / ( $rab->nilai / $rab->units->count() ) * 100),2) }}</span></strong>
                                      @endif
                                      <input class="values" type="text" id="input_rab_nilai_{{ $value->id}}" value="{{ $value->nilai * $value->volume}}" style="display: none;">
                                  </td>
                                </tr>
                                @if(count($value->sub_pekerjaan))
                                    @foreach($value->sub_pekerjaan as $key2 => $value2 )
                                        <tr>
                                            <td></td>
                                            <td>{{$value2->name}}</td>
                                            <td style="text-align:right">{{$value2->volume}}</td>
                                            <td>{{$value2->satuan}}</td>
                                            <td style="text-align:right">{{number_format($value2->nilai)}}</td>
                                            <td style="text-align:right">{{number_format($value2->total_nilai)}}</td>
                                            <td></td>
                                        </tr>
                                    @endforeach
                                @endif
                            @endif
                        @endforeach
                    @endif
                </tbody>
            </table>

            @if ( $rab->approval->histories->where("user_id",$user->id)->first()->approval_action_id == "1")
                <a href="#" class="btn btn-info" onclick="setapproved('6')" data-toggle="modal" data-target="#myModal">Approve</a>
                {{-- <a href="#" class="btn btn-info" onclick="setapproved('3')" data-toggle="modal" data-target="#myModal">Revisi</a> --}}
                <a href="#" class="btn btn-danger" onclick="setapproved('7')" data-toggle="modal" data-target="#myModal">Reject</a>
            @elseif ( $rab->approval->histories->where("user_id",$user->id)->first()->approval_action_id == "6")
                <span class="badge badge-success" style="font-size: 20px;">Approved</span>
            @elseif ( $rab->approval->histories->where("user_id",$user->id)->first()->approval_action_id == "7")
                <span class="badge badge-danger" style="font-size: 20px;">Reject</span>
            @endif

          </div>
          <br/><br/>
          <div class="col-md-12" style="padding-top:20px">
              <table class="table table-bordered table-striped">
                <thead class="head_table">
                  <tr class="header_1">
                      <td>Username</td>
                      <td>Request At</td>
                      <td>Status</td>
                      <td>Time Left (days)</td>
                  </tr>
                </thead>
                <tbody>
                  @if ( isset($rab->approval->histories))
                      @foreach ( $rab->approval->histories as $key2 => $value2 )
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
                          </tr>
                      @endforeach
                  @endif
                </tbody>
              </table>
          </div>

          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    {{-- <div class="float-right d-none d-sm-block">
      <b>Version</b> 3.0.0-alpha
    </div>
    <strong>Copyright &copy; 2014-2018 <a href="http://adminlte.io">AdminLTE.io</a>.</strong> All rights
    reserved. --}}
  </footer>


</div>
<!-- ./wrapper -->
{{-- @include('user.footer') --}}
{{-- <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/fixedheader/3.1.6/js/dataTables.fixedHeader.min.js" type="text/javascript"></script> --}}
@include("master/footer_table")
<script type="text/javascript">
  $(document).ready(function() {
      $.ajaxSetup({
          headers: {
              'X-CSRF-Token': $('input[name=_token]').val()
          }
      });
  });

  var table = $('#table_rab').DataTable( {
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

  var table_upload = $('#table_upload').DataTable( {
                    scrollX: true,
                    // scrollY: 100%,
                    paging: false,
                    ordering: false,
                    searching: false,
                    // autoWidth : false,
                  });
  // new $.fn.dataTable.FixedHeader( table );

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
            type :"get",
            dataType :"json",
            data: {
                user_id : $("#user_id").val(),
                rab_id :$("#rab_id").val(),
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

    $("#btn_upload").click(function(){
      $('#ModalUploadFile').modal('show');
    });

    $("#btn_tambah_unit").click(function(){
      $('#ModalUnit').modal('show');
    });

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
                @foreach ( $rab->units as $key => $value )
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
