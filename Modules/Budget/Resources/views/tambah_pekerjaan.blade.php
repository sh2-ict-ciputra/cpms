<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Admin QS | Dashboard </title>
  @include("master/header")
  <!-- Select2 -->  
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="{{ url('/')}}/assets/bower_components/select2/dist/css/select2.min.css">
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  @include("master/sidebar_project")

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>Data Proyek <strong>{{ $budget->project->name }}</strong></h1>

    </section>

    <!-- Main content -->
    <section class="content">

      <!-- SELECT2 EXAMPLE -->
      <div class="box box-default">

        <!-- /.box-header -->
        <div class="box-body table-responsive">
          <div class="row">
            <div class="col-md-12"><h3 class="box-title">Tambah Item Pekerjaan</h3></div>
            <!-- {{$budget}} -->
            <div class="col-md-12 ">
                <select class="itempekerjaan_parent form-control" name="" style="width:40%">
                    <option value="0">Pilih COA Pekerjaan</option>
                    @if($budget->project_kawasan_id != '')
                        @foreach ( $itempekerjaan_parent->where('code','!=',240) as $key => $value )
                            <option value="{{ $value->id }}">{{ $value->code }} | {{ $value->name }}</option>
                        @endforeach
                    @else
                        @foreach ($itempekerjaan_parent->where('code',240) as $key => $value )
                            <option value="{{ $value->id }}">{{ $value->code }} | {{ $value->name }}</option>
                        @endforeach
                    @endif
                </select>
                <button id="addRow" style="margin: 10px 5px 10px 5px">Add new row</button>
                <table class="table table-bordered" id="table_pekerjaan" style="margin:10px 10px 10px 10px">
                    <thead style="background-color:">
                        <tr>
                            <th>no</th>
                            <th style="width:20%">COA</th>
                            <th style="width:20%">Uraian</th>
                            <th style="width:15%">Volume</th>
                            <th style="width:10%">Satuan</th>
                            <th style="width:15%">Harga Satuan(Rp)</th>
                            <th style="width:15%">Subtotal</th>
                            <th style="width:">action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i = 0; @endphp

                        @foreach($pekerjaan_project as $key => $value)
                            @if($budget->project_kawasan_id != '')
                                @if($value->itempekerjaan->parent->code != '240')
                                    @php $i += 1; @endphp
                                    <tr class="test">
                                        <td>{{$i}}</td>
                                        <td>
                                            <div id="">
                                                <select class="itempekerjaan form-control" name="item_pekerjaan" style="width:100%">
                                                    <option value="0">Pilih COA Pekerjaan</option>
                                                    @if($budget->project_kawasan_id != '')
                                                        @foreach ( $itempekerjaan->where("id",$value->itempekerjaan->parent_id)->first()->child_item as $key2 => $value2 )
                                                            @if($value2->id == $value->itempekerjaan_id)
                                                                <option value="{{ $value2->id }}" selected>{{ $value2->code }} | {{ $value2->name }}</option>
                                                            @else
                                                                <option value="{{ $value2->id }}">{{ $value2->code }} | {{ $value2->name }}</option>
                                                            @endif
                                                        @endforeach
                                                    @else
                                                        @foreach ($itempekerjaan->where('code',240)->first()->child_item as $key2 => $value2 )
                                                            @if($value2->id == $value->itempekerjaan_id)
                                                                <option value="{{ $value2->id }}" selected>{{ $value2->code }} | {{ $value2->name }}</option>
                                                            @else
                                                                <option value="{{ $value2->id }}">{{ $value2->code }} | {{ $value2->name }}</option>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </select>
                                            <div>
                                        </td>
                                        <td>
                                            <input type='text' name='uraian_coa' class='uraian_coa form-control' style='width:100%' value="{{$value->name_pekerjaan}}"/>
                                        </td>
                                        
                                        @php 
                                            $budget_detail = \Modules\Budget\Entities\BudgetDetail::where("budget_id",$budget->id)->where("budget_project_pekerjaan_id",$value->id)->first();
                                        @endphp
                                        @if($budget_detail == '')
                                            <td>
                                                <input type='text' name='volume' class='volume form-control' style='width:100%' value="{{$value->volume}}"/>
                                            </td>
                                            <td>
                                                <input type='text' name='satuan' class='satuan form-control' style='width:100%' value='{{$value->satuan}}' readonly/>
                                            </td>
                                            <td>
                                                <input type='text' name='harga_satuan' class='harga_satuan form-control' style='width:100%' value="{{number_format($value->nilai,2,'.',',')}}"/>
                                            </td>
                                            <td>
                                                <input type='text' name='harga_total' class='harga_total form-control' style='width:100%' value="{{$value->nilai * $value->volume}}"/>
                                            </td>
                                        @else
                                            <td>
                                                <input type='text' name='volume' class='volume form-control' style='width:100%' value="{{$budget_detail->volume}}"/>
                                            </td>
                                            <td>
                                                <input type='text' name='satuan' class='satuan form-control' style='width:100%' value='{{$budget_detail->satuan}}' readonly/>
                                            </td>
                                            <td>
                                                <input type='text' name='harga_satuan' class='harga_satuan form-control' style='width:100%' value="{{number_format($budget_detail->nilai,2,'.',',')}}"/>
                                            </td>
                                            <td>
                                                <input type='text' name='harga_total' class='harga_total form-control' style='width:100%' value="{{number_format($budget_detail->volume * $budget_detail->nilai,2,'.',',')}}"/>
                                            </td>
                                        @endif
                                        <td>action</th>
                                    </tr>
                                @endif
                            @else
                                @if($value->itempekerjaan->parent->code == '240')
                                    @php $i += 1; @endphp
                                    <tr class="test">
                                        <td>{{$i}}</td>
                                        <td>
                                            <div id="">
                                                <select class="itempekerjaan form-control" name="item_pekerjaan" style="width:100%">
                                                    <option value="0">Pilih COA Pekerjaan</option>
                                                    @if($budget->project_kawasan_id != '')
                                                        @foreach ( $itempekerjaan->where("id",$value->itempekerjaan->parent_id)->first()->child_item as $key2 => $value2 )
                                                            @if($value2->id == $value->itempekerjaan_id)
                                                                <option value="{{ $value2->id }}" selected>{{ $value2->code }} | {{ $value2->name }}</option>
                                                            @else
                                                                <option value="{{ $value2->id }}">{{ $value2->code }} | {{ $value2->name }}</option>
                                                            @endif
                                                        @endforeach
                                                    @else
                                                        @foreach ($itempekerjaan->where('code',240)->first()->child_item as $key2 => $value2 )
                                                            @if($value2->id == $value->itempekerjaan_id)
                                                                <option value="{{ $value2->id }}" selected>{{ $value2->code }} | {{ $value2->name }}</option>
                                                            @else
                                                                <option value="{{ $value2->id }}">{{ $value2->code }} | {{ $value2->name }}</option>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </select>
                                            <div>
                                        </td>
                                        <td>
                                            <input type='text' name='uraian_coa' class='uraian_coa form-control' style='width:100%' value="{{$value->name_pekerjaan}}"/>
                                        </td>
                                        
                                        @php 
                                            $budget_detail = \Modules\Budget\Entities\BudgetDetail::where("budget_id",$budget->id)->where("budget_project_pekerjaan_id",$value->id)->first();
                                        @endphp
                                        @if($budget_detail == '')
                                            <td>
                                                <input type='text' name='volume' class='volume form-control' style='width:100%' value="{{$value->volume}}"/>
                                            </td>
                                            <td>
                                                <input type='text' name='satuan' class='satuan form-control' style='width:100%' value='{{$value->satuan}}' readonly/>
                                            </td>
                                            <td>
                                                <input type='text' name='harga_satuan' class='harga_satuan form-control' style='width:100%' value="{{number_format($value->nilai,2,'.',',')}}"/>
                                            </td>
                                            <td>
                                                <input type='text' name='harga_total' class='harga_total form-control' style='width:100%' value="{{$value->nilai * $value->volume}}"/>
                                            </td>
                                        @else
                                            <td>
                                                <input type='text' name='volume' class='volume form-control' style='width:100%' value="{{$budget_detail->volume}}"/>
                                            </td>
                                            <td>
                                                <input type='text' name='satuan' class='satuan form-control' style='width:100%' value='{{$budget_detail->satuan}}' readonly/>
                                            </td>
                                            <td>
                                                <input type='text' name='harga_satuan' class='harga_satuan form-control' style='width:100%' value="{{number_format($budget_detail->nilai,2,'.',',')}}"/>
                                            </td>
                                            <td>
                                                <input type='text' name='harga_total' class='harga_total form-control' style='width:100%' value="{{number_format($budget_detail->volume * $budget_detail->nilai,2,'.',',')}}"/>
                                            </td>
                                        @endif
                                        <td>action</th>
                                    </tr>                                    
                                @endif
                            @endif
                        @endforeach
                        <input type="" class="nomor" value="{{$i}}" hidden>
                    </tbody>
                </table>
                <input type="hidden" name="all_send" id="all_send" />
                <input type="hidden" name="budget_id" id="budget_id" value="{{$budget->id}}" />
                <button type="submit" id="btn-submit" class="btn btn-primary pull-center">Simpan</button>

            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->
        </div>
        <!-- /.box-body -->

      </div>
      <!-- /.box -->
        <div id="clone_itempekerjaan" hidden>
            <select class="itempekerjaan form-control" name="item_pekerjaan" style="width:100%">
                <option value="0">Pilih Item Pekerjaan</option>
                @if($budget->project_kawasan_id != '')
                    @foreach ( $itempekerjaan as $key => $value )
                        <option value="{{ $value->id }}">{{ $value->code }} | {{ $value->name }}</option>
                    @endforeach
                @else
                    @foreach ($itempekerjaan->where('code',240)->first()->child_item as $key => $value )
                        <option value="{{ $value->id }}">{{ $value->code }} | {{ $value->name }}</option>
                    @endforeach
                @endif
            </select>
        </div>

        <!-- <div id="clone_satuan" hidden>
            <select class="satuan form-control" name="satuan" style="width:100%">
                @foreach ( $satuan as $key2 => $value2 )
                    <option value="{{ $value2->id }}"> {{ $value2->satuan }}</option>
                @endforeach
            </select>
        <div> -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  @include("master/copyright")
  
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

@include("master/footer_table")
@include('form.general_form')
@include("budget::app")
<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('meta[name=csrf-token]').attr('content')
        }
    });
    $(document).ready(function() {
        var t = $('#table_pekerjaan').DataTable({
            paging:false,
        });

        // var counter = $('.nomor').val()+1;

        tbody = $('#table_pekerjaan');
            tbody.find('.harga_satuan').each(function (i, v) {
            fnSetAutoNumeric($(this));
            fnSetMoney($(this), $(this).val());
        }); 
    
        $('#addRow').on( 'click', function () {
            counter = parseInt($('.nomor').val())+1;
            console.log($('.nomor').val());
            t.row.add( [
                counter,
                $('#clone_itempekerjaan').clone().html(),
                "<input type='text' name='uraian_coa' class='uraian_coa form-control' style='width:100%'/>",
                "<input type='text' name='volume' class='volume form-control' style='width:100%'/>",
                // $('#clone_satuan').clone().html(),
                "<input type='text' name='satuan' class='satuan form-control' style='width:100%' value='' readonly/>",
                "<input type='text' name='harga_satuan' class='harga_satuan form-control' style='width:100%'/>",
                "<input type='text' name='harga_total' class='harga_total form-control' style='width:100%'/>",
                "<button class='btn btn-danger'>Delete</button>"
            ] ).draw( false );
            $("#table_pekerjaan").find('tr').addClass('test');
            $('.nomor').val(counter)  

            tbody = $('#table_pekerjaan');
            tbody.find('.harga_satuan').each(function (i, v) {
                fnSetAutoNumeric($(this));
                fnSetMoney($(this), $(this).val());
            }); 
        } );

        $('#table_pekerjaan').on("click", "button", function(){
            t.row($(this).parents('.test')).remove().draw(false);
        });

        $('#btn-submit').click(function() {
            var _data = [];
            $('#table_pekerjaan > tbody > tr').each(function(i, v) {
                var _objdata = {
                    'item_pekerjaan': $(this).find('.itempekerjaan').val(),
                    'uraian': $(this).find('.uraian_coa').val(),
                    'volume': $(this).find('.volume').val(),
                    'satuan': $(this).find('.satuan').val(),
                    'harga_satuan': $(this).find('.harga_satuan').autoNumeric('get'),
                };

                _data.push(_objdata);
            });
            $('#all_send').val(JSON.stringify(_data));
        });

        $('#btn-submit').click(function(){
            var _url = '{{ url("/")}}/budget/save-itempekerjaan';
            var data = JSON.parse($('#all_send').val());
            var budget_id = $('#budget_id').val();
            if(data==''){
                alert('Harap Mengisi data');
            }else{
                $.ajax({
                    type : "POST",
                    url  : _url,
                    dataType : "JSON",
                    data :{
                    data:data,
                    budget_id:budget_id
                    },
                    success : function(data){
                        alert(data.success);
                        window.location.replace('{{ url("/")}}/budget/detail?id='+budget_id);
                    }     
                });
            }
        })
    } );

    $(document).on('change', '.itempekerjaan_parent', function() {
        var parent_id = $(this).val();
        var _url = "{{ url('/budget/itempekerjaan') }}";
        var _data = {
            parent: parent_id
        };
        var parent_div = $('.itempekerjaan').parent('#clone_itempekerjaan');
        $.ajax({
            type: 'post',
            dataType: 'json',
            url: _url,
            data: _data,
            beforeSend: function() {
                waitingDialog.show();

            },
            success: function(data) {
                var strItemOption = '';
                if (data.itempekerjaan != null) {
                    parent_div.find('.itempekerjaan').find('option').remove();
                    strItemOption += '<option value="0">Pilih Item Pekerjaan</option>';
                    $(data.itempekerjaan).each(function(i, v) {
                        strItemOption += '<option value="' + v.id + '"">' + v.code + '|' + v.name +'</option>';
                    });
                    parent_div.find('.itempekerjaan').append(strItemOption);
                }
            },
            complete: function() {
                waitingDialog.hide();
            }
        });
    });

    $(document).on('keyup', '.harga_satuan', function() {
        var parent_div = $(this).parents('.test');
        if($(this).val()!=''){
            var admin  = parseInt($(this).autoNumeric('get'));
        }else{
            var admin = 0;
        }
        var nilai = parseInt(parent_div.find(".volume").val()) * admin;
        parent_div.find(".harga_total").val(nilai).number(true);

    });
        
    $(document).on('keyup', '.volume', function() {
        var parent_div = $(this).parents('.test');
        if($(this).val()!=''){
            var admin  = parseInt($(this).val());
        }else{
            var admin = 0;
        }
        var nilai = parseInt(parent_div.find(".harga_satuan").autoNumeric('get')) * admin;
        parent_div.find(".harga_total").val(nilai).number(true);
    });

    $(document).on('change', '.itempekerjaan', function() {
        var itempekerjaan_id = $(this).val();
        var _url = "{{ url('/budget/satuan') }}";
        var _data = {
            itempekerjaan_id: itempekerjaan_id
        };
        var parent_div = $(this).parents('tr');
        $.ajax({
            type: 'post',
            dataType: 'json',
            url: _url,
            data: _data,
            beforeSend: function() {
                waitingDialog.show();

            },
            success: function(data) {
                if (data.satuan != null) {
                    parent_div.find('.satuan').val(data.satuan);
                }
                
            },
            complete: function() {
                waitingDialog.hide();
            }
        });
    });


    
</script>
</body>
</html>
