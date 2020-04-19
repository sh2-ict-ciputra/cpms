<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Admin QS | Dashboard </title>
  @include("master/header")
  <!-- Select2 -->
  <link rel="stylesheet" href="{{ url('/')}}/assets/bower_components/select2/dist/css/select2.min.css">
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  @include("master/sidebar_rekanan")

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>Data Item Pekerjaan <strong>{{ $itempekerjaan->name }}</strong></h1>

    </section>

    <!-- Main content -->
    <section class="content">

      <!-- SELECT2 EXAMPLE -->
      <div class="box box-default">

        <!-- /.box-header -->
        <div class="box-body">
          <div class="row">
            <div class="col-md-12">
              <span>Total Penawaran = Rp. {{ number_format($tenderpenawaran->nilai)}}</span>
              <form action="{{ url('/')}}/rekanan/user/tender/penawaran-update2" method="post" name="form1" enctype="multipart/form-data">
              <a href="{{ url('/')}}/rekanan/user/tender/detail/?id={{ $tenderRekanan->id }}" class="btn btn-warning">Kembali</a>
              @if (date("Y-m-d") <= date("Y-m-d",strtotime($tenderRekanan->tender->tender_jadwal_penawaran[$step-1]->penawaran_date)))
                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-info2">
                  Edit Penawaran
                </button>
              @endif
              @if ( $exist == 1 )
              <button type="submit" class="btn btn-primary">Simpan</button>
              @endif
              {{ csrf_field() }}
              <input type="hidden" name="tender_id" value="{{ $tenderpenawaran->id }}"><br>
              
              <input type="hidden" name="tender_rekanan" value="{{ $tenderRekanan->id }}">
              <h3><center>Rekanan : <strong>{{ $tenderRekanan->rekanan->name }}</strong></center></h3>
              <hr>
              <button type="button" class="btn btn-success" id="btn_upload"><label>Lampiran Tender</label></button> 
              <table class="table table-bordered">
               <thead class="head_table">
                 <tr>
                  <!-- <td>COA Pekerjaan</td> -->
                  <td style="width:30%;">Item Pekerjaan</td>
                  <td style="width:20%;">Volume</td>
                  <td style="width:10%;">Satuan</td>
                  <td style="width:20%;">Harga Satuan</td>
                  <td style="width:20%;">Subtotal</td>
                 </tr>
                </thead>
                <tbody>
                  @php $start=0; @endphp
                  
                    @foreach ( $tenderpenawaran->details as $key3 => $value3 )
                    @if ( $value3->volume > 0 )
                    
                    <tr>
                      <td><strong>{{ $value3->rab_pekerjaan->itempekerjaan->name }}</strong></td>
                      <td><input type="hidden" name="input_rab_id_[{{ $key3}}]" class="form-control" value="{{ $value3->id }}">
                        <input  type="text" name="input_rab_volume_[{{ $key3}}]" id="input_rab_volume_{{ $key3}}" class="form-control" value="{{ bcdiv((float)$value3->volume, 1, 2) }}" style="width: 100%;text-align: right;" readonly></td>
                      <td><input  type="text" name="input_rab_satuan_[{{ $key3}}]"  id="input_rab_satuan_{{ $key3}}" class="form-control" value="{{ $value3->rab_pekerjaan->satuan }}" style="width: 100%;text-align: right;" readonly></td>
                      <td>
                        @if($value3->nilai != 0 || $value3->nilai != null)
                          <input type="text" name="input_rab_nilai_[{{ $key3}}]"  id="input_rab_nilai_{{ $key3}}" class="form-control vol" onKeyUp="showSummary('{{ $key3}}')" value="{{ $value3->nilai }}" style="text-align: right;" autocomplete="off" readonly>
                        @else
                          <input type="hidden" name="input_rab_nilai_[{{ $key3}}]"  id="input_rab_nilai_{{ $key3}}" class="form-control vol" onKeyUp="showSummary('{{ $key3}}')" value="{{ $value3->nilai }}" style="text-align: right;" autocomplete="off" readonly>
                        @endif
                      </td>
                      <td>
                        <input type="text"  id="subtotal_{{$key3}}" value="{{ number_format($value3->total_nilai,2) }}" class="form-control"  style="text-align: right;" autocomplete="off" readonly/>
                      </td>
                    </tr>
                    @if(count( $value3->tender_penawaran_sub_detail) != 0)
                      <tr class="sub" >
                        <td colspan="5">
                          <table class="table sub-table">
                            @foreach ( $value3->tender_penawaran_sub_detail as $key4 => $value4 )
                              <tr class="row_sub">
                                <td style="width:30%;">{{ $value4->name }} </td>
                                <td style="width:20%;">
                                  <input type="hidden" name="input_sub_id_[{{ $key3}}][{{ $key4}}]" class="form-control" value="{{ $value4->id }}">
                                  
                                  @if (bcdiv((float)$value4->volume, 1, 2) == 0)
                                    <input  type="text" name="input_sub_volume_[{{ $key3}}][{{ $key4}}]" id="input_sub_volume_{{ $key4}}" class="form-control sub_volume" value="{{ round($value4->volume, 2) }}" style="width: 100%;text-align: right;" readonly>
                                  @else
                                    <input  type="text" name="input_sub_volume_[{{ $key3}}][{{ $key4}}]" id="input_sub_volume_{{ $key4}}" class="form-control sub_volume" value="{{ bcdiv((float)$value4->volume, 1, 2) }}" style="width: 100%;text-align: right;" readonly>
                                  @endif
                                </td>
                                <td style="width:10%;">
                                  <input  type="text" name="input_sub_satuan_[{{ $key3}}][{{ $key4}}]"  id="input_sub_satuan_{{ $key4}}" class="form-control" value="{{ $value4->satuan }}" style="width: 100%;text-align: right;" readonly>
                                </td>
                                <td style="width:20%;">
                                  <input type="text" name="input_sub_nilai_[{{ $key3}}][{{ $key4}}]"  id="input_sub_nilai_{{ $key4}}" class="form-control sub_nilai" value="{{number_format($value4->nilai)}}" style="text-align: right;" autocomplete="off" readonly>
                                </td>
                                <td style="width:20%;">
                                  <input type="text" name="input_sub_total_nilai_[{{ $key3}}][{{ $key4}}]"  id="sub_subtotal_{{$key4}}" value="{{number_format($value4->total_nilai)}}" class="form-control sub_total_nilai"  style="text-align: right;" autocomplete="off" readonly/>
                                  </td>
                              </tr>
                            @endforeach
                          </table>
                        </td>
                      </tr>
                    @endif
                    @endif
                    @endforeach
                  
                </tbody>
              </table>

              @if($tenderpenawaran->file_attachment == null)
                <h5 style="color:black;"><i><strong>Lampiran Kosong</strong></i></h5>
              <!-- <input type="file" name="fileupload"><br> -->
              @else
                <a class="btn btn-info" href="{{url('/')}}/rekanan/user/tender/downloaddoc?id={{$tenderpenawaran->id}}" data-url="{{$tenderpenawaran->file_attachment}}">Download Lampiran</a>
              @endif
              </form>
            </div>
          </div>
          <!-- /.row -->
        </div>
        <!-- /.box-body -->

      </div>
      <!-- /.box -->


    </section>

    <div class="modal fade" id="modal-info2">
         <div class="modal-dialog modal-lg">
             <div class="modal-content">
                <form action="{{ url('/')}}/rekanan/user/tender/penawaran-update-berulang" method="post" name="form1" enctype="multipart/form-data">
                  {{ csrf_field() }}
                  <div class="modal-body">
                    <table class="table table-bordere">
                      <thead class="head_table">
                        <tr>
                          <!-- <td>COA Pekerjaan</td> -->
                          <td style="width:30%;">Item Pekerjaan</td>
                          <td style="width:20%;">Volume</td>
                          <td style="width:10%;">Satuan</td>
                          <td style="width:20%;">Harga Satuan</td>
                          <td style="width:20%;">Subtotal</td>
                        </tr>
                      </thead>
                      <tbody>
                        @php 
                          $start=0; $id_penawaran_2=0;
                        @endphp
                        @foreach ( $tenderpenawaran->details as $key3 => $value3 )
                          @if ( $value3->volume > 0 )
                            @php
                              $tender_penawaran_detail = \Modules\Tender\Entities\TenderPenawaranDetail::where("tender_penawaran_id",$penawaran_id)->where("rab_pekerjaan_id",$value3->rab_pekerjaan_id)->get();

                              $tender_sebelumnnya = \Modules\Tender\Entities\TenderPenawaranDetail::where("tender_penawaran_id",$id_penawaran_2)->where("rab_pekerjaan_id",$value3->rab_pekerjaan_id)->first();
                            @endphp
                            <tr class="main">
                              <td>
                                <strong>{{ $value3->rab_pekerjaan->itempekerjaan->name }}</strong>
                                <input type="hidden" name="input_rab_id_[{{ $key3}}]" class="form-control" value="{{ $value3->id }}">
                              </td>
                              <td>
                                <input  type="text" name="" id="input_rab_volume_{{ $key3}}" class="form-control" value="{{ bcdiv((float)$value3->volume, 1, 2) }}" style="width: 100%;text-align: right;" readonly>
                              </td>
                              <td>
                                <input  type="text" name=""  id="input_rab_satuan_{{ $key3}}" class="form-control" value="{{ $value3->rab_pekerjaan->satuan }}" style="width: 100%;text-align: right;" readonly>
                              </td>
                              <td>
                                <input type="hidden" name="input_rab_nilai_[{{ $key3}}]"  id="input_rab_nilai_{{ $key3}}" class="form-control vol" value="{{number_format($value3->nilai)}}" style="text-align: right;" autocomplete="off" onKeyUp="showSummary('{{ $key3}}')" required>
                              </td>
                              <td>
                                <input type="text" name="input_rab_total_nilai_[{{ $key3}}]" id="subtotal_{{$key3}}" value="{{number_format($value3->total_nilai)}}" class="form-control main_total_nilai"  style="text-align: right;" autocomplete="off" readonly/>
                              </td>
                            </tr>
                            @if(count($value3->tender_penawaran_sub_detail) != 0)
                              <tr class="sub" >
                                <td colspan="5">
                                  <table class="table sub-table">
                                    @foreach ( $value3->tender_penawaran_sub_detail as $key4 => $value4 )
                                      @php
                                        $tender_sub_sebelumnnya = \Modules\Tender\Entities\TenderPenawaranSubDetail::where("id",$value4->id)->first();
                                      @endphp
                                      <tr class="row_sub">
                                        <td style="width:30%;">{{ $value4->name }} </td>
                                        <td style="width:20%;">
                                          <input type="hidden" name="input_sub_id_[{{ $key3}}][{{ $key4}}]" class="form-control" value="{{ $value4->id }}">
                                          
                                          @if (bcdiv((float)$value4->volume, 1, 2) == 0)
                                          <input  type="text" name="" id="input_sub_volume_{{ $key4}}" class="form-control sub_volume" value="{{ round($value4->volume, 2) }}" style="width: 100%;text-align: right;" readonly>
                                          @else
                                            <input  type="text" name="" id="input_sub_volume_{{ $key4}}" class="form-control sub_volume" value="{{ bcdiv((float)$value4->volume, 1, 2) }}" style="width: 100%;text-align: right;" readonly>
                                          @endif
                                        </td>
                                        <td style="width:10%;">
                                          <input  type="text" name=""  id="input_sub_satuan_{{ $key4}}" class="form-control" value="{{ $value4->satuan }}" style="width: 100%;text-align: right;" readonly>
                                        </td>
                                        <td style="width:20%;">
                                          <input type="text" name="input_sub_nilai_[{{ $key3}}][{{ $key4}}]"  id="input_sub_nilai_{{ $key4}}" class="form-control sub_nilai" value="{{number_format($value4->nilai)}}" style="text-align: right;" autocomplete="off" required>
                                        </td>
                                        <td style="width:20%;">
                                          <input type="text" name="input_sub_total_nilai_[{{ $key3}}][{{ $key4}}]" id="sub_subtotal_{{$key4}}" value="{{number_format($value4->total_nilai) }}" class="form-control sub_total_nilai"  style="text-align: right;" autocomplete="off" readonly/>
                                        </td>
                                      </tr>
                                    @endforeach
                                  </table>
                                </td>
                              </tr>
                            @endif
                          @endif
                        @endforeach
                      </tbody>
                    </table>
                    <input type="hidden" name="tender_id" value="{{ $tenderpenawaran->id }}"><br>
                    <input type="hidden" name="step" value="{{ $step }}">
                    <h6 style="color:black;"><i><strong>Harap upload dengan tipe .pdf,.doc,.docx,.xls,.xlsx</strong></i></h6>
                    <input type="file" name="fileupload"><br>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                      <button type="submit" class="btn btn-primary" id="">Save changes</button>
                  </div>
                </form>
             </div>
             <!-- /.modal-content -->
         </div>
         <!-- /.modal-dialog -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
 {{-- @include("master/copyright") --}}

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
                      <tr class="test">
                        <td>
                          <input type="text" class="form-control kategori" name="kategori[]" autocomplete="off" style="width:100%;" value="{{$value->document_name}}">
                        </td>
                        <td style="text-align:center">
                          <a class="btn btn-info" href="{{url('/')}}/workorder/downloaddoc?id={{$value->id}}" data-url="{{$value->filenames}}">Download </a>
                        </td>
                        <td>
                          <input type="text" class="form-control file_name" name="file_name[]" autocomplete="off" style="width:100%;" value="{{$value->name}}">
                        </td>
                      </tr>
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



</div>
<!-- ./wrapper -->

@include("master/footer_table")
<!-- Select2 -->
<script src="{{ url('/')}}/assets/bower_components/select2/dist/js/select2.full.min.js"></script>
<script src="{{ url('/')}}/assets/plugins/jquery.number.min.js"></script>
<script type="text/javascript">
  $(".vol").number(true);
</script>
@include("tender::app")
<script type="text/javascript">
  $(function(){
    $(".sub_nilai").number(true);
    $(".vol").number(true);
  });

  function showSummary(id){
    var vla = $("#input_rab_nilai_" + id).val();
    console.log($("#input_rab_nilai_" + id).val(),vla);
    var rep = vla.replace(",","");
    var summary = parseInt($("#input_rab_volume_" + id).val()) * parseInt(rep);
    if ( summary == "NaN"){
      $("#subtotal_" + id).val("0");
    }else{
      $("#subtotal_" + id).val(summary);
      $("#subtotal_" + id).number(true);
    }
  }
  $("#btn_upload").click(function(){
      $('#ModalUploadFile').modal('show');
    });

    $(document).on('keyup', '.sub_nilai', function() {
      var volume =  parseFloat($(this).parents(".row_sub").find(".sub_volume").val(), 2);
      var total = volume *  parseInt($(this).val());
      $(this).parents(".row_sub").find(".sub_total_nilai").val(parseInt(total));
      $(".sub_total_nilai").number(true);

      var total_nilai = 0;
      var status = 1;
      $(this).parents(".sub-table").find(".row_sub").each(function () {
          total_nilai += parseInt($(this).find(".sub_total_nilai").val());
      });
      $(this).parents(".sub").prev().find(".main_total_nilai").val(total_nilai);
      $(".main_total_nilai").number(true);
  });

  $(document).on('click', '#save_pekerjaan2', function() {
      var main = [];
      $("#table_itempekerjaan2 .test").each(function () {

          var id_pekerjaan = $(this).find(".id_pekerjaan").val();
          var sub = [];
          $("#table_itempekerjaan2 ."+id_pekerjaan+" .test_child").each(function () {
              var arr = [
                  $(this).find(".child_rab_sub_pekerjaan_id").val(),
                  $(this).find(".child_total_nilai").val(),
              ];

              sub.push(arr);
          });
          console.log($(this).find(".total_nilai").val());
          if($(this).find(".total_nilai").val() != undefined){
              var total_nilai = $(this).find(".total_nilai").val();
          }else{
              var total_nilai = 0;
          }
          var arr2 = [
                  $(this).find(".id_rab_pekerjaan").val(),
                  $(this).find(".volume").val(),
                  $(this).find(".satuan").val(),
                  total_nilai,
                  sub,
              ];
          main.push(arr2);

          // console.log(main);
      });
      
      var url = "{{ url('/')}}/rab/update-pekerjaan";
      $.ajax({
          type: 'post',
          dataType: 'json',
          url: url,
          data: {
              data: JSON.stringify(main),
              rab_id: $("#rab_id").val(),
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
      // window.location.href;
      // window.location.replace(window.location.href);
      // window.location.replace("/rab/detail?id="+$("#rab_id").val()+"&idpkr="+$("#idpkr").val()+"#");
  });
</script>
</body>
</html>
