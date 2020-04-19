<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Admin QS | Dashboard </title>
  @include("master/header")
  <!-- Select2 -->
  <link rel="stylesheet" href="{{ url('/')}}/assets/bower_components/select2/dist/css/select2.min.css">
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css">
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  @include("master/sidebar_project")

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
            <div class="col-md-12 table-responsive">
              
                <a href="{{ url('/')}}/tender/detail/?id={{ $tender->id }}" class="btn btn-warning">Kembali</a>
                <button type="submit" class="btn btn-primary" id="save_change">Simpan</button>
                <input type="hidden" name="tender_id" value="{{ $tender->id }}" id="tender_id">
                {{ csrf_field() }}
                @php
                    $penawaran_ke = count($tender->tender_jadwal_penawaran);
                @endphp
                <input type="hidden" name="step" value="{{ $penawaran_ke + 1 }}" id="step">
                <div class="form-group">
                    <label>Tanggal Penawaran {{$penawaran_ke + 1}} </label>
                    <input type="text" id="penawaran_date" class="form-control" name="penawaran_date" value="" autocomplete="off" style="width:150px" required>
                </div>
                <div class="form-group">
                    <label>Tanggal Klarifikasi {{$penawaran_ke + 1}} </label>
                    <input type="text" id="klarifikasi_date" class="form-control" name="klarifikasi_date" value="" autocomplete="off" style="width:150px" required>
                </div>

                {{-- <table class="table table-bordered">
                    <thead class="head_table">
                        <tr>
                            <td>COA Pekerjaan</td>
                            <td>Item Pekerjaan</td>
                            <td>Volume</td>
                            <td>Satuan</td>
                            <td>Nilai</td>
                        </tr>
                    </thead>
                    <tbody>
                        @php $start=0; @endphp
                        @foreach( $tender->penawarans->last()->details as $key => $value )
                        <tr>
                            <td>{{ $value->rab_pekerjaan->itempekerjaan->code }}</td>
                            <td>{{ $value->rab_pekerjaan->itempekerjaan->name }}</td>
                            <td>
                                <input type="hidden" name="input_rab_id_[{{ $value->rab_pekerjaan->id}}]" class="form-control" value="{{ $value->rab_pekerjaan->id }}">
                                <input  type="text" name="input_rab_volume_[{{ $value->rab_pekerjaan->id}}]" id="input_rab_volume_[{{ $value->rab_pekerjaan->id}}]" class="form-control" value="{{ $value->volume }}" style="width: 100%;">
                            </td>
                            <td>
                                <input  type="hidden" name="input_rab_satuan_[{{ $value->rab_pekerjaan->id}}]"  id="input_rab_satuan_{{ $key}}" class="form-control" value="{{ $value->satuan }}" style="width: 100%;">
                                <input  type="text" class="form-control" value="{{ $value->satuan }}" style="width: 100%;" readonly>
                            </td>
                            <td><input type="text" name="input_rab_nilai_[{{ $value->rab_pekerjaan->id}}]"  id="input_rab_nilai_{{ $key}}" class="form-control vol" onKeyUp="showSummary('{{ $key}}')" readonly></td>
                        </tr>
                        @php $start = $key; @endphp
                        @endforeach
                    </tbody>
                </table> --}}
                
                <table class="table" id="table_itempekerjaan" style="width:100%">
                    <thead class="head_table">
                        <tr>
                            <td style="width:10%">COA Pekerjaan</td>
                            <td style="width:25%">Item Pekerjaan</td>
                            <td style="width:15%">Volume</td>
                            <td style="width:15%">Satuan</td>
                            <td style="width:5%"></td>
                        </tr>
                    </thead>
                    <tbody id="itempekerjaan">
                        @foreach($tender->rab->pekerjaans as $key => $value)
                            @if ($value->itempekerjaan != null)
                                @php
                                $tender_sebelumnnya = \Modules\Tender\Entities\TenderPenawaranDetail::where("rab_pekerjaan_id",$value->id)->orderBy("id","DESC")->first();
                                @endphp
                                    @if($value->satuan == null)
                                        <tr class="test">
                                            <td><strong>{{$value->itempekerjaan->code}} 
                                                <input class="id_pekerjaan" value="{{$value->itempekerjaan->id}}" hidden>
                                                <input class="id_rab_pekerjaan" value="{{$value->id}}" hidden></strong>
                                            </td>
                                            <td><strong>{{$value->itempekerjaan->name}}</strong></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    @else
                                        <tr class="test">
                                            <td>{{$value->itempekerjaan->code}} 
                                                <input class="id_pekerjaan" value="{{$value->itempekerjaan->id}}" hidden>
                                                <input class="id_rab_pekerjaan" value="{{$value->id}}" hidden>
                                            </td>
                                            <td>{{$value->itempekerjaan->name}}</td>
                                            <td>
                                                @if(count($value->sub_pekerjaan) != 0)
                                                    @if($tender_sebelumnnya == null)
                                                        <input type='text' class='form-control volume' name='volume' value='{{bcdiv((float)$value->volume, 1, 2)}}' autocomplete='off' style='width:100%' readonly/>
                                                    @else
                                                        <input type='text' class='form-control volume' name='volume' value='{{bcdiv((float)$tender_sebelumnnya->volume, 1, 2)}}' autocomplete='off' style='width:100%' readonly/>
                                                    @endif
                                                @else
                                                    @if($tender_sebelumnnya == null)
                                                        <input type='text' class='form-control volume' name='volume' value='{{bcdiv((float)$value->volume, 1, 2)}}' autocomplete='off' style='width:100%' />
                                                    @else
                                                        <input type='text' class='form-control volume' name='volume' value='{{bcdiv((float)$tender_sebelumnnya->volume, 1, 2)}}' autocomplete='off' style='width:100%' />
                                                    @endif
                                                @endif
                                            </td>
                                            <td>
                                                <input type='text' class='form-control satuan' name='satuan' value='{{$value->satuan}}' autocomplete='off' style='width:100%' readonly/>
                                            </td>
                                            @if(strpos($value->itempekerjaan->code, "100.") !== FALSE)
                                                        <td class=" details-control">
                                                            <button type="button" class="btn btn-success" ><i class="fa fa-plus" style="font-size:15px"></i></button>
                                                        </td>   
                                                    @else
                                                        @if(strpos($value->itempekerjaan->code, "00") === FALSE)
                                                            <td class=" details-control">
                                                                <button type="button" class="btn btn-success" ><i class="fa fa-plus" style="font-size:15px"></i></button>
                                                            </td>
                                                        @else
                                                            <td class="">
                                                            </td>
                                                        @endif
                                                    @endif
                                        </tr>
                                        @if($tender_sebelumnnya != null)
                                            @if(count($value->sub_pekerjaan) != 0 || count($tender_sebelumnnya->tender_penawaran_sub_detail) != 0)
                                                <tr class="child">
                                                    <td colspan="5">
                                                    <table border="0" style="padding:none;width:100%" class="table child_table {{$value->itempekerjaan->id}} ">
                                                            <thead hidden>
                                                                <tr>
                                                                    <th style="width:10%;"></th>
                                                                    <th style="width:25%;"></th>
                                                                    <th style="width:15%;"></th>
                                                                    <th style="width:15%;"></th>
                                                                    <th style="width:15%;"></th>
                                                                    <th style="width:15%;"></th>
                                                                    <th style="width:5%;"></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @if(count($tender_sebelumnnya->tender_penawaran_sub_detail) == 0)
                                                                    @foreach($value->sub_pekerjaan as $key2 => $value2)
                                                                        <tr class="test_child"> 
                                                                            <td style="width:10%;text-align:center;border-top:none;">
                                                                                <input class="child_id_pekerjaan" value="" hidden>
                                                                                @if ($value2->rab_sub_pekerjaan != null)
                                                                                    <input class="child_rab_sub_pekerjaan_id" value="{{$value2->rab_sub_pekerjaan->id}}" hidden>
                                                                                @else
                                                                                    <input class="child_rab_sub_pekerjaan_id" value="" hidden>
                                                                                @endif
                                                                            </td> 
                                                                            <td style="width:25%;text-align:center;border-top:none;">
                                                                                <input type="text" class="form-control child_name" name="child_name" value="{{$value2->name}}" style="width:100%" />
                                                                            </td> 
                                                                            <td style="width:15%;text-align:center;border-top:none;">
                                                                                <input type="text" class="form-control child_volume" name="child_volume" value="{{bcdiv((float)$value2->volume, 1, 2)}}" style="width:100%" />
                                                                            </td> 
                                                                            <td style="width:15%;text-align:center;border-top:none;">
                                                                                <select class="form-control list_satuan" name="list_satuan[]" id="" style="width:100%;">
                                                                                    <option value="">(pilih satuan)</option>
                                                                                        @foreach($coa_satuan as $key3 => $value3)
                                                                                            @if($value2->satuan == $value3->satuan)
                                                                                                <option value="{{$value3->satuan}}" selected>{{$value3->satuan}}</option>
                                                                                            @else
                                                                                                <option value="{{$value3->satuan}}">{{$value3->satuan}}</option>
                                                                                            @endif
                                                                                        @endforeach
                                                                                </select>
                                                                            </td> 
                                                                            <td style="width:5%;text-align:center;border-top:none;">
                                                                                <button type="button" class="btn btn-danger hapus" ><i class="fa fa-trash" style="font-size:15px"></i></button>
                                                                            </td> 
                                                                        </tr> 
                                                                    @endforeach
                                                                @else
                                                                    @foreach($tender_sebelumnnya->tender_penawaran_sub_detail as $key2 => $value2)
                                                                        <tr class="test_child"> 
                                                                            <td style="width:10%;text-align:center;border-top:none;">
                                                                                <input class="child_id_pekerjaan" value="" hidden>
                                                                                @if ($value2->rab_sub_pekerjaan != null)
                                                                                    <input class="child_rab_sub_pekerjaan_id" value="{{$value2->rab_sub_pekerjaan->id}}" hidden>
                                                                                @else
                                                                                    <input class="child_rab_sub_pekerjaan_id" value="" hidden>
                                                                                @endif
                                                                            </td> 
                                                                            <td style="width:25%;text-align:center;border-top:none;">
                                                                                <input type="text" class="form-control child_name" name="child_name" value="{{$value2->name}}" style="width:100%" />
                                                                            </td> 
                                                                            <td style="width:15%;text-align:center;border-top:none;">
                                                                                <input type="text" class="form-control child_volume" name="child_volume" value="{{bcdiv((float)$value2->volume, 1, 2)}}" style="width:100%" />
                                                                            </td> 
                                                                            <td style="width:15%;text-align:center;border-top:none;">
                                                                                <select class="form-control list_satuan" name="list_satuan[]" id="" style="width:100%;">
                                                                                    <option value="">(pilih satuan)</option>
                                                                                        @foreach($coa_satuan as $key3 => $value3)
                                                                                            @if($value2->satuan == $value3->satuan)
                                                                                                <option value="{{$value3->satuan}}" selected>{{$value3->satuan}}</option>
                                                                                            @else
                                                                                                <option value="{{$value3->satuan}}">{{$value3->satuan}}</option>
                                                                                            @endif
                                                                                        @endforeach
                                                                                </select>
                                                                            </td> 
                                                                            <td style="width:5%;text-align:center;border-top:none;">
                                                                                <button type="button" class="btn btn-danger hapus" ><i class="fa fa-trash" style="font-size:15px"></i></button>
                                                                            </td> 
                                                                        </tr> 
                                                                    @endforeach
                                                                @endif
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endif
                                    @endif
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>              
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
    <div id="clone_satuan" hidden>
        <select class="form-control list_satuan" name="list_satuan[]" id="" style="width:100%;">
            <option value="">(pilih satuan)</option>
                @foreach($coa_satuan as $key => $value)
                    <option value="{{$value->satuan}}">{{$value->satuan}}</option>
                @endforeach
        </select>
    </div>

    <div id="clone_sub" hidden>
        <table>
        <tr class="child">
            <td colspan="5">
                <table border="0" style="padding:none;width:100%" class="table child_table">
                    <thead hidden>
                        <tr>
                            <th style="width:10%;"></th>
                            <th style="width:25%;"></th>
                            <th style="width:15%;"></th>
                            <th style="width:15%;"></th>
                            <th style="width:15%;"></th>
                            <th style="width:15%;"></th>
                            <th style="width:5%;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="test_child"> 
                            <td style="width:10%;text-align:center;border-top:none;">
                                <input class="child_id_pekerjaan" value="" hidden>
                            </td> 
                            <td style="width:25%;text-align:center;border-top:none;">
                                <input type="text" class="form-control child_name" name="child_name" value="" style="width:100%" />
                            </td> 
                            <td style="width:15%;text-align:center;border-top:none;">
                                <input type="text" class="form-control child_volume" name="child_volume" value="0" style="width:100%" />
                            </td> 
                            <td style="width:15%;text-align:center;border-top:none;">
                                <select class="form-control list_satuan" name="list_satuan[]" id="" style="width:100%;">
                                    <option value="">(pilih satuan)</option>
                                        @foreach($coa_satuan as $key3 => $value3)
                                            <option value="{{$value3->satuan}}">{{$value3->satuan}}</option>
                                        @endforeach
                                </select>
                            </td> 
                            <td style="width:5%;text-align:center;border-top:none;">
                                <button type="button" class="btn btn-danger hapus" ><i class="fa fa-trash" style="font-size:15px"></i></button>
                            </td> 
                        </tr> 
                    </tbody>
                </table>
            </td>
        </tr>
        </table>
    </div> 

  @include("master/copyright")
</div>
<!-- ./wrapper -->

@include("master/footer_table")
<!-- Select2 -->
<script src="{{ url('/')}}/assets/bower_components/select2/dist/js/select2.full.min.js"></script>
<script src="{{ url('/')}}/assets/plugins/jquery.number.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript">
  // $(".vol").number(true);

  $("#penawaran_date").datepicker({
        "dateformat" : "yy-mm-dd"
    });

  $("#klarifikasi_date").datepicker({
      "dateformat" : "yy-mm-dd"
  });

  $(document).ready(function() {
      $.ajaxSetup({
          headers: {
              'X-CSRF-Token': $('input[name=_token]').val()
          }
      });

      var idpkr = $('#idpkr').val();
      var wo = $('#workorder').val();

    //   detailitem(idpkr, wo);
  });

  function format(d) {
            // `d` is the original data object for the row
            return '<table border="0" style="padding:none;width:100%" class="table child_table">' +
                '<thead hidden>'+
                    '<tr>'+
                        '<th style="width:10%;"></th>'+
                        '<th style="width:25%;"></th>'+
                        '<th style="width:15%;"></th>'+
                        '<th style="width:15%;"></th>'+
                        '<th style="width:15%;"></th>'+
                        '<th style="width:15%;"></th>'+
                        '<th style="width:5%;"></th>'+
                    '</tr>'+
                '</thead>'+
                '<tbody>'+
                    '<tr class="test_child">' +
                        '<td style="width:10%;text-align:center;border-top:none;"><input class="child_id_pekerjaan" value="" hidden></td>' +
                        '<td style="width:25%;text-align:center;border-top:none;"><input type="text" class="form-control child_name" name="child_name" value="" style="width:100%" /></td>' +
                        '<td style="width:15%;text-align:center;border-top:none;"><input type="text" class="form-control child_volume" name="child_volume" value="0" style="width:100%" /></td>' +
                        '<td style="width:15%;text-align:center;border-top:none;">'+$('#clone_satuan').clone().html()+'</td>' +
                        '<td style="width:15%;text-align:center;border-top:none;"><input type="text" class="form-control child_nilai" name="child_nilai" value="" style="width:100%" /></td>' +
                        '<td style="width:15%;text-align:center;border-top:none;"><input type="text" class="form-control child_total_nilai" name="child_total_nilai" value="0" style="width:100%" readonly/></td>' +
                        '<td style="width:5%;text-align:center;border-top:none;"><button type="button" class="btn btn-danger hapus" ><i class="fa fa-trash" style="font-size:15px"></i></button></td>' +
                    '</tr>' +
                '</tbody>'+
                '</table>';
        }


        /* Formatting function for row details - modify as you need */



        $(document).ready(function() {
            // Add event listener for opening and closing details
            $("#table_itempekerjaan").find('tbody').find('tr').each(function () {
                if($(this).find(".satuan").val() == undefined){
                    $(this).find(".tambah").removeClass("details-control");
                    // console.log($(this).find(".tambah"));
                }
                // console.log($(this).find(".satuan").val());
            });

            $('#table_itempekerjaan tbody').on('click', 'td.details-control', function() {

                var tr = $(this).closest('tr');
                // var row = table.row(tr);
                if (tr.next().hasClass("child") == true) {
                    $(this).parents(".test").next().find(".child_table").append( '<tr class="test_child">' +
                        '<td style="width:10%;text-align:center;border-top:none;"><input class="child_id_pekerjaan" value="" hidden></td>' +
                        '<td style="width:25%;text-align:center;border-top:none;"><input type="text" class="form-control child_name" name="child_name" value="" style="width:100%" /></td>' +
                        '<td style="width:15%;text-align:center;border-top:none;"><input type="text" class="form-control child_volume" name="child_volume" value="0" style="width:100%" /></td>' +
                        '<td style="width:15%;text-align:center;border-top:none;">'+$('#clone_satuan').clone().html()+'</td>' +
                        '<td style="width:5%;text-align:center;border-top:none;"><button type="button" class="btn btn-danger hapus" ><i class="fa fa-trash" style="font-size:15px"></i></button></td>' +
                    '</tr>');

                    // $(this).parents(".test").next().find(".child_table").DataTable().destroy();
                    $(this).parents(".test").next().find(".child_table").find('tbody tr').addClass('test_child');
                } else {
                    // Open this row
                    tr.after($("#clone_sub").find('tbody').html())
                    // tr.next().addClass('child');
                    tr.next().find(".child_table").addClass(tr.find('.id_pekerjaan').val());
                    tr.next().find('.child_id_pekerjaan').val(tr.find('.id_pekerjaan').val());
                    tr.next().find('.list_satuan').val(tr.find('.satuan').val()).trigger('change');
                }
                $(".child_nilai").number(true);
                $(".child_total_nilai").number(true);
            });

        });

        $(document).on('keyup', '.child_volume', function() {
            var nilai = $(this).parents(".test_child").find(".child_nilai").val();

            var total_volume = 0;
            var status = 1;
            $(this).parents(".child_table").find(".test_child").each(function () {
                if($(this).find(".list_satuan").val() == $(this).parents(".child").prev().find(".satuan").val() && status == 1){
                    if($(this).parents(".child").prev().find(".satuan").val() != "Ls"){
                        total_volume += parseFloat($(this).find(".child_volume").val());
                    }else{
                        total_volume = 1;
                    }
                }else{
                    status = 0;
                }

            });
            $(this).parents(".child").prev().find(".volume").val(total_volume);
        });

        $(document).on('change', '.list_satuan', function() {

            var total_volume = 0;
            var status = 1;
            $(this).parents(".test_child").find(".child_volume").val(1);
            $(this).parents(".child_table").find(".test_child").each(function () {
                if($(this).find(".list_satuan").val() == $(this).parents(".child").prev().find(".satuan").val() && status == 1){
                    if($(this).parents(".child").prev().find(".satuan").val() != "Ls"){
                        total_volume += parseFloat($(this).find(".child_volume").val());
                    }else{
                        total_volume = 1;
                    }
                }else{
                    status = 0;
                }
            });
            $(this).parents(".child").prev().find(".volume").val(total_volume);
        });
        
        $(document).on('click', '.hapus', function() {
            // console.log($(this).parents(".test_child"));
            // var parent = $(this).parents(".child_table");
            $(this).parents(".test_child").removeClass( "test_child" ).addClass("for_delete");
            // $(this).parents(".test_child").remove();

            var total_volume = 0;
            var status = 1;
            var tr = 0;
            // console.log($(this).parents(".child_table").find(".test_child").each());
            $(this).parents(".child_table").find(".test_child").each(function () {
                if($(this).find(".list_satuan").val() == $(this).parents(".child").prev().find(".satuan").val() && status == 1){
                    if($(this).parents(".child").prev().find(".satuan").val() != "Ls"){
                        total_volume += parseFloat($(this).find(".child_volume").val());
                    }else{
                        total_volume = 1;
                    }
                }else{
                    status = 0;
                }
                tr++;
            });
            $(this).parents(".child").prev().find(".volume").val(total_volume);
            if(tr != 0){
                $(this).parents(".for_delete").remove();
            }else{
                // var tr = $(this).parents(".child").prev();
                // var row = table.row(tr);
                // row.child.hide();
                // tr.removeClass('shown');
                $(this).parents(".child").remove();
                //     row.child.hide();
                // $(this).parents(".child").hide();
            }
        });

        $(document).on('click', '#save_change', function() {
            var main = [];
            $(".test").each(function () {
                var id_pekerjaan = $(this).find(".id_pekerjaan").val();
                var sub = [];
                $("."+id_pekerjaan+" .test_child").each(function () {
                    var arr = [
                        $(this).find(".child_rab_sub_pekerjaan_id").val(),
                        $(this).find(".child_name").val(),
                        $(this).find(".child_volume").val(),
                        $(this).find(".list_satuan").val(),
                    ];

                    sub.push(arr);
                });

                var arr2 = [
                        $(this).find(".id_pekerjaan").val(),
                        $(this).find(".id_rab_pekerjaan").val(),
                        $(this).find(".volume").val(),
                        $(this).find(".satuan").val(),
                        sub,
                    ];
                main.push(arr2);
            });
            
            var url = "{{ url('/')}}/tender/saveAllPekerjaan";
            if($("#penawaran_date").val() != "" && $("#klarifikasi_date").val() != ""){
                $.ajax({
                    type: 'post',
                    dataType: 'json',
                    url: url,
                    data: {
                        data: main,
                        tanggal_penawaran: $("#penawaran_date").val(),
                        tanggal_klarifikasi: $("#klarifikasi_date").val(),
                        tender_id: $("#tender_id").val(),
                        step: $("#step").val(),
                    },
                    beforeSend: function() {
                    waitingDialog.show();
                    },
                    success: function(data) { 
                        window.location.replace("{{ url('/')}}/tender/detail/?id="+$("#tender_id").val());
                    },
                    complete: function() {
                    waitingDialog.hide(); 
                    }
                });
            }else{
                alert("Data Tidak Lengkap");
                if($("#penawaran_date").val() == null){
                    $("#penawaran_date").css({"boder":"1px solid red"});
                }
                if($("#klarifikasi_date").val() == null){
                    $("#klarifikasi_date").css({"boder":"1px solid red"});
                }
            }
            
        });
</script>
@include("pekerjaan::app")
</body>
</html>
