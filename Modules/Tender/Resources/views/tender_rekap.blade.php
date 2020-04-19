<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Admin QS | Dashboard </title>
  @include("master/header")
  <!-- Select2 -->
  <link rel="stylesheet" href="{{ url('/')}}/assets/bower_components/select2/dist/css/select2.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css">
  {{-- <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.1/css/buttons.dataTables.min.css"> --}}
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  @include("master/sidebar_project")

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>Data Tender <strong>{{ $tender->rab->pekerjaans->first()->itempekerjaan->name }}</strong></h1>

    </section>

    <!-- Main content -->
    <section class="content">

      <!-- SELECT2 EXAMPLE -->
      <div class="box box-default">

        <!-- /.box-header -->
        <div class="box-body">
          <div class="row">
            <div class="col-md-12" >              
              <a href="{{ url('/')}}/tender/detail/?id={{ $tender->id }}" class="btn btn-warning">Kembali</a>
              <br><br>
              {{ csrf_field() }}
              <button id="btnExport" class="btn btn-info form-control" style="margin-bottom:10px;width:15%">Export To Excel</button>

              <table class="table table-bordered" id="table_rekap" style="display:inline-block;width:80vw;overflow-x: auto">
                <thead class="head_table" style="font-weight: bolder;text-align:center;">
                  <tr>
                    <td rowspan="2" style="vertical-align: middle;">No.</td>
                    <td rowspan="2" style="vertical-align: middle;">Item Pekerjaan</td>
                    <td rowspan="2" style="vertical-align: middle;">Volume</td>
                    <td rowspan="2" style="vertical-align: middle;">Satuan</td>
                    <td colspan="{{ $count_rekanan+ 1 }}" style="vertical-align: middle;"><center>Harga Satuan</center></td>
                    <td colspan="{{ $count_rekanan + 1 }}" style="vertical-align: middle;"><center>Total Nilai(Rp)</center></td>
                  </tr>
                  <tr>                    
                    <td style="vertical-align: middle;">OE</td>
                    @foreach ( $tender->rekanans as $key1 => $value1 )
                      @if ($value1->approval->approval_action_id == 6)
                        <td style="vertical-align: middle;">{{ $value1->rekanan->group->name}}</td>
                      @endif
                    @endforeach

                    <td style="vertical-align: middle;">OE</td>
                    @foreach ( $tender->rekanans as $key1 => $value2 )
                      @if ($value2->approval->approval_action_id == 6)
                        <td style="vertical-align: middle;">
                          {{ $value2->rekanan->group->name}}<br>
                          @if ($tender->approval->approval_action_id != 8)
                            @if ($tender->tunjuk_pemenang_tender == null || $tender->tunjuk_pemenang_tender->is_pemenang == 2)
                              @if (count($value2->penawarans) == $step+1)
                                <button class="btn btn-warning" onClick="usulkan('{{$value2->id}}','{{ $value2->rekanan->group->name}}')" >Usulkan pemenang </button>
                              @endif
                            @else
                                @if ($value2->tunjuk_pemenang_tender != null)
                                  <button class="btn btn-success" >Usulan Pemenang</button>
                                @endif
                            @endif
                          @endif
                        </td>
                      @endif
                    @endforeach
                  </tr>
                </thead>
                <tbody>
                  @php $nilai_oe_total = 0; @endphp

                  @php
                    $i =1
                  @endphp

                    @foreach($tender->rekanans[0]->penawarans[$step]->details->where("volume","!=",0) as $key => $value )
                      @php 
                          $volume1 = null;
                          $nilai1 = null;
                      @endphp
                      <tr>
                        <td><strong>{{ $i }}</strong></td>
                        <td><strong>{{ $value->rab_pekerjaan->itempekerjaan->name }}</strong></td>
                        <td style="text-align: right;"><strong>{{ bcdiv((float)$volume1 = $value->volume, 1, 2) }}</strong></td>
                        <td><strong>{{ $value->satuan }}</strong></td>
                        <td style="text-align: right;">
                        @if( $value->rab_pekerjaan->nilai == null &&  $value->rab_pekerjaan->nilai == 0)
                          
                        @else
                          <strong>{{ number_format( $nilai1 = $value->rab_pekerjaan->nilai) }}</strong>
                        @endif
                        </td>
                        
                        @foreach ( $tender->rekanans as $key1 => $value2 )
                          @if ($value2->approval->approval_action_id == 6)
                            @foreach ( $value2->penawarans as $key2 => $value3 )
                              @if ( $key2 == $step )
                                @php
                                  $nilai = \Modules\Tender\Entities\TenderPenawaranDetail::where("tender_penawaran_id",$value3->id)->where("rab_pekerjaan_id",$value->rab_pekerjaan_id)->get()->first()->nilai;
                                @endphp
                                @if (isset(\Modules\Tender\Entities\TenderPenawaranDetail::where("tender_penawaran_id",$value3->id)->where("rab_pekerjaan_id",$value->rab_pekerjaan_id)->get()->first()->nilai))
                                  @if($nilai != null && $nilai != 0)
                                    <td style="text-align: right;"><strong>{{ number_format($nilai) }}</strong></td>
                                  @else
                                    <td style="text-align: right;"></td>
                                  @endif
                                @endif
                              @endif
                            @endforeach
                          @endif
                        @endforeach

                        <td style="text-align: right;">
                          @php
                            $total = 0;
                          @endphp
                          @if($nilai1 == 0 && $nilai1 == null)
                            <strong>
                              @foreach ($value->tender_penawaran_sub_detail as $item=> $nilai)
                                  @if($nilai->rab_sub_pekerjaan != null)
                                    @php
                                        $total += $nilai->volume * $nilai->rab_sub_pekerjaan->nilai; 
                                    @endphp
                                  @endif
                              @endforeach
                              {{ number_format($total) }}
                            </strong>
                          @else
                            <strong>{{ number_format($total = $nilai1* $volume1) }}</strong>
                          @endif
                        </td>

                        @foreach ( $tender->rekanans as $key1 => $value2 )
                          @if ($value2->approval->approval_action_id == 6)
                            @foreach ( $value2->penawarans as $key2 => $value3 )
                              @if ( $key2 == $step )
                                @php
                                  $total_nilai = \Modules\Tender\Entities\TenderPenawaranDetail::where("tender_penawaran_id",$value3->id)->where("rab_pekerjaan_id",$value->rab_pekerjaan_id)->get()->first();
                                @endphp
                                @if (isset(\Modules\Tender\Entities\TenderPenawaranDetail::where("tender_penawaran_id",$value3->id)->where("rab_pekerjaan_id",$value->rab_pekerjaan_id)->get()->first()->nilai))
                                  <td style="text-align: right;">
                                    @if( $total_nilai->total_nilai != 0 &&  $total_nilai->total_nilai != null)
                                      <strong>{{ number_format($total_nilai->total_nilai)}}</strong>
                                    @else
                                      <strong>{{ number_format($total_nilai->nilai * $total_nilai->volume) }}</strong>
                                    @endif
                                  </td>
                                @endif
                              @endif
                            @endforeach
                          @endif
                        @endforeach
                        @php 
                        
                          $nilai_oe_total += $total;
                        @endphp
                      </tr>
                      @if(count($value->tender_penawaran_sub_detail) != 0)
                        @foreach($value->tender_penawaran_sub_detail as $key2 => $value2)
                          @php
                              $volume2 = null;
                              $nilai2 = null;
                          @endphp
                          <tr>
                            <td></td>
                            <td>{{ $value2->name }}</td>
                            <td style="text-align: right;">{{ bcdiv((float)$volume2 = $value2->volume, 1, 2) }}</td>
                            <td>{{ $value2->satuan }}</td>
                            <td style="text-align: right;">
                              @if($value2->rab_sub_pekerjaan != null)
                                {{ number_format( $nilai2 = $value2->rab_sub_pekerjaan->nilai) }}
                              @else
                                0
                              @endif
                            </td>
                            
                            @foreach ( $tender->rekanans as $key3 => $value3 )
                              @if ($value3->approval->approval_action_id == 6)
                                @foreach ( $value3->penawarans as $key4 => $value4 )
                                  @if ( $key4 == $step )
                                    @if (isset(\Modules\Tender\Entities\TenderPenawaranDetail::where("tender_penawaran_id",$value4->id)->where("rab_pekerjaan_id",$value->rab_pekerjaan_id)->get()->first()->tender_penawaran_sub_detail[$key2]->nilai))
                                      <td style="text-align: right;">
                                        {{number_format(\Modules\Tender\Entities\TenderPenawaranDetail::where("tender_penawaran_id",$value4->id)->where("rab_pekerjaan_id",$value->rab_pekerjaan_id)->get()->first()->tender_penawaran_sub_detail[$key2]->nilai)}}
                                      </td>
                                    @endif
                                  @endif
                                @endforeach
                              @endif
                            @endforeach
    
                            <td style="text-align: right;">
                              @if( $nilai2 != null)
                                {{ number_format( $nilai2 * $volume2) }}
                              @else
                                0
                              @endif
                            </td>
    
                            @foreach ( $tender->rekanans as $key3 => $value3 )
                              @if ($value3->approval->approval_action_id == 6)
                                @foreach ( $value3->penawarans as $key4 => $value4 )
                                  @if ( $key4 == $step )
                                    @if (isset(\Modules\Tender\Entities\TenderPenawaranDetail::where("tender_penawaran_id",$value4->id)->where("rab_pekerjaan_id",$value->rab_pekerjaan_id)->get()->first()->tender_penawaran_sub_detail[$key2]->total_nilai))
                                      <td style="text-align: right;">
                                        {{number_format(\Modules\Tender\Entities\TenderPenawaranDetail::where("tender_penawaran_id",$value4->id)->where("rab_pekerjaan_id",$value->rab_pekerjaan_id)->get()->first()->tender_penawaran_sub_detail[$key2]->total_nilai)}}
                                      </td>
                                    @endif
                                  @endif
                                @endforeach
                              @endif
                            @endforeach
                            @php
                            @endphp
                          </tr>
                        @endforeach
                      @endif
                      @php
                        $i += 1
                      @endphp
                    @endforeach

                    <tr>
                      <td colspan="{{ 5 + $count_rekanan }}" style="text-align: right;"><strong><i>Subtotal</i></strong></td>
                      <td style="text-align: right;">{{ number_format($nilai_oe_total)}}</td>
                      @foreach ( $tender->rekanans as $key3 => $value3 )
                        @foreach ( $value3->penawarans as $key4 => $value4 )
                          @if ( $key4 == $step )
                          <td style="text-align: right;">{{ number_format($value4->nilai) }}</td>
                          @endif
                        @endforeach
                      @endforeach
                    </tr>
                    <tr>
                      <td colspan="{{ 5 + $count_rekanan }}" style="text-align: right;"><strong><i>Pembulatan</i></strong></td>
                      <td style="text-align: right;">{{ number_format($nilai_oe_total)}}</td>
                         @foreach ( $tender->rekanans as $key3 => $value3 )
                          @foreach ( $value3->penawarans as $key4 => $value4 )
                            @if ( $key4 == $step )
                            <td style="text-align: right;">{{ number_format($value4->nilai) }}</td>
                            @endif
                          @endforeach
                        @endforeach
           
                    </tr>
                    <tr>
                      <td colspan="{{ 5 + $count_rekanan }}" style="text-align: right;"><strong><i>PPn</i></strong></td>
                      <td style="text-align: right;">{{ number_format($nilai_oe_total * 0.1 )}}</td>
                         @foreach ( $tender->rekanans as $key3 => $value3 )
                          @foreach ( $value3->penawarans as $key4 => $value4 )
                            @if ( $key4 == $step )
                            @if($value3->rekanan->group->pkp_status == 1)
                              @php
                                $ppn = \Modules\Globalsetting\Entities\Globalsetting::where("parameter", "ppn")->first()->value;
                              @endphp
                            @else
                              @php
                                $ppn = 0;
                              @endphp
                            @endif
                            <td style="text-align: right;">{{ number_format(($ppn/100) * $value4->nilai) }}</td>
                            @endif
                          @endforeach
                        @endforeach
                     
                    </tr>
                    <tr>
                      <td colspan="{{ 5 + $count_rekanan }}" style="text-align: right;"><strong><i>Grand Total</i></strong></td>
                      <td style="text-align: right;">{{ number_format($nilai_oe_total + ($nilai_oe_total * 0.1 ),2) }}</td>
                      @foreach ( $tender->rekanans as $key3 => $value3 )
                          @foreach ( $value3->penawarans as $key4 => $value4 )
                            @if ( $key4 == $step )
                            @if($value3->rekanan->group->pkp_status == 1)
                              @php
                                $ppn = \Modules\Globalsetting\Entities\Globalsetting::where("parameter", "ppn")->first()->value;
                              @endphp
                            @else
                              @php
                                $ppn = 0;
                              @endphp
                            @endif
                            <td style="text-align: right;">{{ number_format( (($ppn/100) * $value4->nilai) + $value4->nilai ) }}</td>
                            @endif
                          @endforeach
                        @endforeach
                    </tr>
                </tbody>
                <tfoot>
                  
                </tfoot>
              </table>

              <h4>Dokumen Pendukung</h4>
              <table class="table table-bordered">
                <thead class="head_table">
                  <tr>
                    <th>Rekanan</th>
                    <!-- <th>Nama Dokumen</th> -->
                    <th>Download</th>
                  </tr>
                </thead>
                <tbody>                  
                  @foreach( $tender->rekanans as $key3 => $value4 )
                    @foreach ( $value4->penawarans as $key5 => $value5 )
                      @if ( $key5 == $step)
                      <tr>
                        <td>{{ $value5->rekanan->rekanan->group->name }}</td>
                        <!-- <td>{{ $value5->file_attachment }}</td> -->
                        <td>
                          @if ( $value5->file_attachment != "")
                          <a class="btn btn-success" href="{{ url('/') }}/tender/download/?id={{ $value5->id}}">Download</a>
                          @endif
                        </td>
                      </tr>
                      @endif
                    @endforeach
                  @endforeach
                </tbody>
              </table>
            </div>
            <hr>
          </div>
          <!-- /.row -->
        </div>
        <!-- /.box-body -->

      </div>
      <!-- /.box -->


    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <div class="modal fade" tabindex="-1" role="dialog" id="myModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
              <label id="name_usulan" style=""></label>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><br>
            </div>
            <div class="modal-body">
                <span id="title_approval"><strong></strong></span>
                <p></p>
                <div id="listdetail">
                    <input type="hidden" id="id_rekanan" value="">
                    <input type="hidden" id="penawaran_ke" value="{{$step+1}}">
                    <textarea name="description" id="description" rows="6" style="width:100%"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btn_save" data-value="">Save changes</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
  {{-- @include("master/copyright") --}}

  



</div>
<!-- ./wrapper -->

@include("master/footer_table")
<!-- Select2 -->
<script src="{{ url('/')}}/assets/bower_components/select2/dist/js/select2.full.min.js"></script>
<script src="{{ url('/')}}/assets/plugins/jquery.number.min.js"></script>
{{-- <script src="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css"></script> --}}
{{-- @include("url('/')}}/assets/pluginss/jquery.table2excel.min.js") --}}
<script src="{{ url('js/jquery.table2excel.min.js')}}"></script>
<script type="text/javascript">
  $(document).ready(function() {
      $.ajaxSetup({
          headers: {
              'X-CSRF-Token': $('input[name=_token]').val()
          }
      });
  });
  $(".vol").number(true);
  $(document).ready(function(){
    $('#table_rekap').DataTable( {
      "paging" : false,
      "order": false,
      "scrollX": true,
      fixedHeader: true,
      // "dom": 'Bfrtip',
      // "buttons": [
      //     'copy', 'csv', 'excel', 'pdf', 'print'
      // ]
      // "autowidth":false,
      // scrollY: "500px",
      // scrollCollapse: true,
    } );
  })

  $("#btnExport").click(function() {
			$("#table_rekap").table2excel({
				exclude: ".noExl",
				name: "Worksheet",
				filename: "Rekap Tender", //do not include extension
				fileext: ".xls" // file extension
			});
		});

  // $(document).ready(function() {
  //   $("#btnExport").click(function(e) {
  //     e.preventDefault();

  //     //getting data from our table
  //     var data_type = 'data:application/vnd.ms-excel';
  //     var table_div = document.getElementById('table_rekap');
  //     // var table_html = table_div.outerHTML.replace(/ /g, '%20');

  //     var a = document.createElement('a');
  //     a.href = data_type + ', ' + table_div;
  //     a.download = 'exported_table_' + Math.floor((Math.random() * 9999999) + 1000000) + '.xls';
  //     a.click();
  //   });
  // });

  function usulkan(id,name){
    $("#myModal").modal('show');
    $("#id_rekanan").val(id);
    document.getElementById("name_usulan").innerHTML = '<strong>Rekanan '+name+' diusulkan sebagai Pemenang</strong>';
  }

  $("#btn_save").click(function(){
    var url = "{{ url('/')}}/tender/tunjukPemenang";
    $.ajax({
        type: 'post',
        dataType: 'json',
        url: url,
        data: {
          rekanan_id: $("#id_rekanan").val(),
          alasan: $("#description").val(),
          penawaran:$("#penawaran_ke").val(),
        },
        beforeSend: function() {
          waitingDialog.show();
        },
        success: function(data) { 
            window.location.reload();
        },
        complete: function() {
          waitingDialog.hide(); 
        }
    });
  });
</script>
{{-- @include("pekerjaan::app") --}}
</body>
</html>
