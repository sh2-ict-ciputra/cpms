<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Admin QS | Dashboard </title>
  @include("master/header")
  
    <!-- Select2 -->
  <link rel="stylesheet" href="{{ url('/')}}/assets/bower_components/select2/dist/css/select2.min.css">
  <link rel="stylesheet" href="{{ url('/')}}/assets/selectize/selectize.bootstrap3.css">
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">


    <style>
        .panel-info>.panel-heading {
    color: white;
    background-color: #367fa9;
    border-color: #3c8dbc;
}
        .panel-info {
    border-color: #3c8dbc;
}
.select2-container--default .select2-selection--multiple .select2-selection__choice{
    background-color: #3c8dbc;
    border-color: #367fa9; q
}
    </style>

    <style>
      * {
        box-sizing: border-box;
      }

      body {
        background-color: #f1f1f1;
      }

      h1 {
        text-align: center;  
      }
      /* Mark input boxes that gets an error on validation: */
      input.invalid,select.invalid,textarea.invalid{
        background-color: #ffdddd;
      }

      /* Hide all steps by default: */
      button:hover {
        opacity: 0.8;
      }

      #prevBtn {
        background-color: #bbbbbb;
      }

      /* Make circles that indicate the steps of the form: */
      .step {
        height: 15px;
        width: 15px;
        margin: 0 2px;
        background-color: #bbbbbb;
        border: none;  
        border-radius: 50%;
        display: inline-block;
        opacity: 0.5;
      }

      .step.active {
        opacity: 1;
      }

      /* Mark the steps that are finished and valid: */
      .step.finish {
        background-color: #4CAF50;
      }
      input[type=select-multiple]{
        width:33.3%;
      }

    .optionItem{
      width:24.5%;
    }
    .item{
      color: black;
      background-color: beige;
    }
    select{
      background-color: white;
    }
    .select2-container .select2-search--inline .select2-search__field{
      padding-left: 12px;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__rendered li{
      width: 100%
    }
    .custom1{
      display: none
    }
    .custom2{
      display: block;
      margin-top : 10px;
    }
    .subValueItem{
      margin-top : auto; 
    }
    @media (min-width: 992px) {
      .custom1{
        display: block
      }
      .custom2{
        display: none
      }
      .subValueItem{
        margin-top : 10px; 
      }
    }
    </style>
</head>
<body id="body" class="hold-transition skin-blue sidebar-mini" style="visibility: hidden;">
<div class="wrapper">
  @include("master/sidebar_project")
  <div class="content-wrapper">
    <section class="content-header">
      <h1>Penerimaan Barang PO</h1>
    </section>
    <section class="content-header">
      <div class="" style="float: none">
        <button class="col-lg-1 col-md-2 col-sm-2 btn btn-primary" onclick="location.href='{{ url('/')}}/penerimaanbarangpo/'" style="float: none; border-radius: 20px; padding-left: 0">
        <i class="fa fa-fw fa-arrow-left"></i>&nbsp;&nbsp;Back
        </button>
        <button class="col-lg-1 col-md-2 col-sm-2 btn btn-primary" onclick="window.location.reload()" style="float: right; border-radius: 20px; padding-left: 0;">
          <i class="fa fa-fw fa-refresh"></i>&nbsp;&nbsp;Refresh
        </button>  
      </div>
    </section>
    <section class="content">
      
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">
            Tambah Penerimaan Barang
            <span class="pull-right-container">
              <small class="label pull-right bg-yellow"></small>
            </span>
          </h3>
          <!-- <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse">
              <i class="fa fa-minus"></i>
            </button>
          </div> -->
          </div>
        <div class="box-body">
          <div class="row">
            <div class="col-md-12">              
              <form id="regForm" action="{{ url('/')}}/penerimaanbarangpo/store" method="post" name="form1">
                {{ csrf_field() }}
                <input type="hidden" name="allitems" id="allitems" />
                <input type="hidden" name="allitemsbonus" id="allitemsbonus" />
                <div class="col-lg-6 col-md-6 col-xs-12">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label class="control-label col-md-4 col-sm-5 col-xs-12" style="padding-left:0">Rekanan</label>
                        <div class="col-md-7 col-sm-7 col-xs-12" style="margin-bottom: 10px">
                          <!-- <label class="col-md-12" style="padding-left:0">Rekanan</label> -->
                            <input name="idTender" value="" hidden>
                              <select id="rekanan" class="form-input input col-md-12" name="rekanan" style="width: 100%" required>
                                <option value="" disabled="" selected="">Pilih Rekanan</option>
                                @foreach($result_rekanan as $v)
                                  <option value="{{$v['id']}}">{{$v['name']}}</option>  
                                @endforeach
                              </select>
                        </div>
                    </div>
                  </div>

                  <div class="col-md-12">
                    <div class="form-group">
                        <label class="control-label col-md-4 col-sm-5 col-xs-12" style="padding-left:0">Purchase Order</label>
                          <div id="divPO" class="col-md-7 col-sm-7 col-xs-12" id="addpt" style="margin-bottom: 10px">
                            <select id="PO" class="form-input col-md-12" name="PO" required style="width:100%;padding-left: 12px">
                                <option value="">Pilih Rekanan Terlebih Dahulu</option>  
                            </select>
                          </div>
                    </div>
                  </div>

                  <div class="col-md-12">
                    <div class="item form-group">
                        <label class="control-label col-md-4 col-sm-5 col-xs-12" style="padding-left:0">No Delivery Order</label>
                          <div class="col-md-7 col-sm-7 col-xs-12" style="margin-bottom: 10px">
                            <input type='text' id='no_refrensi' name='no_refrensi' style="width: 100%" class='form-control' value="" required />
                          </div>
                    </div>
                  </div>
                </div>

                <div class="col-lg-6 col-md-6 col-xs-12">
                  <div class="col-md-12">
                    <div class="item form-group">
                      <label class="control-label col-md-5 col-sm-5 col-xs-12" style="padding-left:0">Tgl Penerimaan Barang</label>
                        <div class="col-md-7 col-sm-7 col-xs-12" style="margin-bottom: 10px;">
                          <div class="input-group input-medium date datePicker_" style=" display: -webkit-box">
                          <input type="date" name="date" class="col-md-12 form-input" style="width: 100%;" value="{{date("Y-m-d")}}" required>
                            <div class="input-group-addon"  style="padding: 10px; width: 0%"><i class="fa fa-calendar"></i></div>
                          </div>  
                        </div>
                    </div>
                  </div>
                  <!-- <div class="col-md-12">
                    <div class="item form-group">
                      <label class="control-label col-md-5 col-sm-5 col-xs-12" style="padding-left:0">Gudang</label>
                        <div class="col-md-7 col-sm-7 col-xs-12" style="margin-bottom: 10px">
                            <select id="gudang" class="form-input input col-md-12" name="gudang_penyimpanan" style="width: 100%" required>
                              <option value="" disabled="" selected="">Pilih Gudang</option>
                              @foreach($gudang as $v)
                                <option value="{{$v->id}}">{{$v->name}}</option>  
                              @endforeach
                            </select>
                        </div>
                    </div>
                  </div> -->
                </div>
                <div id="valueItem">
                  <div class="subValueItem col-md-12">
                    <table id="datatable-details" style="width: 100%;" class="table table-striped table-bordered table-hover table-responsive table-checkable order-column nowrap">
                      <thead style="background-color: greenyellow;">
                        <tr>
                          <th>No</th>
                          <th>Item</th>
                          <th>Quantity di Terima</th>
                          <th>Sisa Quantity PO</th>
                          <th >Total Quantity PO</th>
                          <th>Satuan</th>
                          <th>Deskripsi</th>
                          <th>Gudang</th>
                          <th>Deskripsi Item yang Diterima</th>
                          <th HIDDEN>POD_ID</th>
                        </tr>
                      </thead>
                    </table>
                    <br>

                    <h3>Bonus</h3>
                    <button type="button" id="addRow" style="margin: 10px 5px 10px 5px">Add new row</button>

                    <table id="table_bonus" style="width: 100%;" class="table table-striped table-bordered table-hover table-responsive table-checkable order-column nowrap">
                      <thead style="background-color: greenyellow;">
                        <tr>
                          <th>No</th>
                          <th>Item</th>
                          <th style="width:20%">Quantity di Terima</th>
                          <th>Satuan</th>
                          <th>Brand</th>
                          <th>Gudang</th>
                          <th>Deskripsi Item yang Diterima</th>
                          <th>delete</th>
                        </tr>
                      </thead>
                      <tbody id="tbody_tabel_bonus">
                        <input type="" class="nomor" value="1" hidden>
                        <tr class="test">
                          <td>
                            1
                          </td>
                          <td>
                            <select class="form-control item_bonus select3" name="item_bonus[]" id="" style="width:100%;">
                              <option value="" disabled="" selected="">Pilih Item</option>
                              @foreach ($item as $key => $value)
                                <option value="{{$value->id}}">{{$value->item->name}}</option>
                              @endforeach
                            </select>
                          </td>
                          <td>
                            <input type="number" class="input_qty text-right quantity_bonus" name="quantity_bonus" id="" value="" step="1" style="width: 100%;" max="" min="0"/>
                          </td>
                          <td>
                            <select class="form-control satuan_bonus select3" name="satuan_bonus[]" id="" style="width:100%;">
                              <option value="" disabled="" selected="">Pilih Satuan</option>
                              @foreach ($satuan as $key => $value)
                                <option value="{{$value->id}}">{{$value->name}}</option>
                              @endforeach
                            </select>
                          </td>
                          <td>
                            <select class="form-control brand_bonus select3" name="brand_bonus[]" id="" style="width:100%;">
                              <option value="" disabled="" selected="">Pilih Brand</option>
                              @foreach ($brand as $key => $value)
                                <option value="{{$value->id}}">{{$value->name}}</option>
                              @endforeach
                            </select>
                          </td>
                          <td>
                            <select id="gudang_bonus" class="form-control select3 gudang_bonus" name="gudang_bonus" style="width: 100%" >
                              <option value="" disabled="" selected="">Pilih Gudang</option> 
                              @foreach($gudang as $v)
                                <option value="{{$v->id}}">{{$v->name}}</option> 
                              @endforeach
                            </select>
                          </td>
                          <td>
                            <textarea id="deskripsi_item_diterima_bonus" name="deskripsi_umum_bonus" class="form-input col-md-12 deskripsi_item_diterima_bonus" style="min-width: 180px;"></textarea>
                          </td>
                          <td>
                            <button type="button" class="btn btn-danger hapus" ><i class="fa fa-trash" style="font-size:15px"></i></button>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>

                {{-- <div>
                  <span>Bonus</span>
                  <table id="" style="width: 100%;" class="table table-striped table-bordered table-hover table-responsive table-checkable order-column nowrap">
                    <thead style="background-color: greenyellow;">
                      <tr>
                        <th>No</th>
                        <th>Item</th>
                        <th>Quantity di Terima</th>
                        <th>Sisa Quantity PO</th>
                        <th >Total Quantity PO</th>
                        <th>Satuan</th>
                        <th>Deskripsi</th>
                        <th>Gudang</th>
                        <th>Deskripsi Item yang Diterima</th>
                        <th HIDDEN>POD_ID</th>
                      </tr>
                    </thead>
                  </table>
                </div> --}}
              <button id="btn-submit" type="submit" class="col-lg-1 col-md-2 btn btn-primary" style="margin-top: 10px">Simpan</button>
            <!-- <button id="btn-submit" type="submit" class="col-md-1 btn btn-primary" >Simpan</button> -->       
              </form>
              <!-- /.form-group -->
            </div>
            <!-- /.col -->
            <div class="col-md-12">
            </div>
            <!-- /.col -->
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
@include('form.datatable_helper')
@include('form.general_form')
<!-- Select2 -->
<script src="{{ URL::asset('assets/global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
<script type="text/javascript">
  const rowClone = $("#tbody_tabel_bonus").html();
    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('meta[name=csrf-token]').attr('content')
        }
    });

  $("#tender").select2({
      placeholder: "Pilih Tender",
      //closeOnSelect: false

  });
  $(".select3").select2();

  $(document).ready(function(){  
      document.getElementById("body").style.visibility = "visible";
  });

  const valueItem = $("#valueItem")[0].innerHTML;
  var totalKuantitas = 0;

  $('#rekanan').change(function(){
    var url = "{{ url('/')}}/penerimaanbarangpo/getPO";
    var item = $("#PO");
    var send = $(this).val();
    console.log(url + '/' + send);
    document.getElementById("divPO").style.visibility = "hidden";
    var getjson = $.getJSON(url + '/' + send, function (data) {
        item.addClass("item");
        item.empty();
        var option        = document.createElement("option");
        option.value      = "";
        option.innerHTML  = "Pilih PO";
        option.setAttribute("disabled","true");
        option.setAttribute("selected","true");
        item.append(option);
        for(var i = 0; i <= data.length ; i++){
        if(i<data.length){
            var option        = document.createElement("option");
            option.value      = data[i].id;
            option.innerHTML  = data[i].no;
            item.append(option);
        }else
            item.removeClass("item");
        }
        // console.log(item);
        document.getElementById("divPO").style.visibility = "visible";
    }); 
  });

 
  $.ajaxSetup({
  headers: {
                'X-CSRF-Token': $('meta[name=csrf-token]').attr('content')
              }
  });
  var gentable = null;

  var _initTablePenerimaan = function () {

    var arrColumns = [
        { 'data': 'id'}, //
        { 'data': 'item'}, //
        { 'data': 'quantity' }, //
        { 'data': 'quantity_sisa' },
        {'data': 'total_quantity'},
        {'data': 'satuan_name' },
        {'data': 'description'},
        {'data': 'gudang'},
        {'data': 'description_item_diterima'},
        {'data': 'po_detail_id', 'sClass': 'hidden'}

      ];

    _GeneralTable(arrColumns);

    gentable = $('#datatable-details').DataTable(datatableDefaultOptions)
    .on('change','.input_qty',function()
      {
          var tParent = $(this).parents('tr');
          var data = gentable.row(tParent).data();
          var qty = $(this).val();
          var qty_max = data.quantity_sisa;
          var html_qty ='<input type="number" class="text-right input_qty" name="quantity" id="quantity" value="'+qty+'" step="1" min="0" style="width: 50%;" max="'+qty_max+'" />'
          data.quantity = html_qty;
          gentable.row(tParent).data(data).draw();
          tParent.find('.input_qty').val($(this).val());
      })
      .on('click','.text-right',function()
      {
          $(this).select();
      });
}

var _GeneralTable = function (arrColumns) {
  var _coldefs = [
        {
          "targets":[],
          "visible": false,
          "searchable": false
        }
      ];
  var fixedColumn = {
    leftColumns: 1
  }
  datatableDefaultOptions.searching = true;
  datatableDefaultOptions.aoColumns = arrColumns;
  datatableDefaultOptions.columnDefs = _coldefs;
  datatableDefaultOptions.autoWidth = false;
  datatableDefaultOptions.ordering = false;
}

  
  $(document).ready(function(){  
      _initTablePenerimaan();
      $('#PO').change(function()
      {
          var _data = {id:parseInt($(this).val())};
          var _url = "{{ url('/')}}/penerimaanbarangpo/getPOD";

          $.ajax({
            type:'get',
            dataType:'json',
            url:_url,
            data:_data,
            beforeSend:function()
            {
              waitingDialog.show();
            },
            success:function(data)
            {
              if(data.length > 0){
                gentable.clear().draw();
                $(data).each(function(i,v)
                {

                    var items = {
                      id:v.item_id,
                      item:v.item_name,
                      quantity:'<input type="number" class="input_qty text-right" name="quantity" id="quantity" value="'+v.sisa_quantity+'" step="1" style="width: 50%;" max="'+v.sisa_quantity+'" min="0"/>',
                      quantity_sisa:v.sisa_quantity,
                      satuan:v.satuan_id,
                      satuan_name:v.satuan_name,
                      gudang:'<select id="gudang" class="form-input input col-md-12" name="gudang_penyimpanan" style="width: 100%" ><option value="" disabled="" selected="">Pilih Gudang</option> @foreach($gudang as $v)<option value="{{$v->id}}">{{$v->name}}</option> @endforeach',
                      description_item_diterima:'<textarea id="deskripsi_item_diterima" name="deskripsi_umum" class="form-input col-md-12" style="min-width: 180px;"></textarea>',
                      total_quantity:v.quantity_total,
                      description:v.description,
                      po_detail_id:v.po_detail_id
                    };

                    gentable.row.add(items);
                });


                gentable.draw();
              }
            },
            complete:function()
            {
              waitingDialog.hide();
              
            }
          });
      });

      $('#btn-submit').click(function()
      {
          var _data = [];
          var _data2 = [];
          $('#datatable-details > tbody > tr').each(function(i,v)
          {
              var _objdata = {
                'item_id':gentable.row(i).data().id,
                'item_name':gentable.row(i).data().item,
                'quantity':$(this).find('#quantity').val(),
                'satuan_id':gentable.row(i).data().satuan,
                'satuan_name':gentable.row(i).data().satuan_name,
                'description':gentable.row(i).data().description,
                'gudang':$(this).find('#gudang').val(),
                'deskripsi_item_diterima':$(this).find('#deskripsi_item_diterima').val(),
                'po_detail_id':gentable.row(i).data().po_detail_id
              };


              _data.push(_objdata);
          });
          $('#allitems').val(JSON.stringify(_data));

          $('#table_bonus > tbody > tr').each(function(i,v)
          {
              var _objdata = {
                'item_id':$(this).find('.item_bonus').val(),
                'quantity':$(this).find('.quantity_bonus').val(),
                'satuan_id':$(this).find('.satuan_bonus').val(),
                'brand_id':$(this).find('.brand_bonus').val(),
                'gudang':$(this).find('.gudang_bonus').val(),
                'deskripsi_item_diterima':$(this).find('.deskripsi_item_diterima_bonus').val(),
              };

              _data2.push(_objdata);
          });

          $('#allitemsbonus').val(JSON.stringify(_data2));
      });

      $('select').select2();
      var id_penawaran = $('#PO').val();
      if(id_penawaran != null)
      {
        $('#PO').trigger('change');
      }
  });

  // var counter
  // var t = $('#table_bonus').DataTable({
  //           autoWidth: false,
  //           paging:false,
  //       });
  $('#addRow').on( 'click', function () {
    var counter = parseInt($('.nomor').val())+1;
    // $(".select3").select2("destroy");
    $("#tbody_tabel_bonus").append(rowClone);
    $(".select3").select2();

    // )
      // t.row.add( [
      //     counter,
      //     $('#clone_item').clone().html(),
      //     "<input type='number' class='input_qty text-right' name='quantity_bonus' id='' value='' step='1' style='width: 100%;' max='' min='0'/>",
      //     $('#clone_satuan').clone().html(),
      //     $('#clone_brand').clone().html(),
      //     $('#clone_gudang').clone().html(),
      //     "<textarea id='deskripsi_item_diterima' name='deskripsi_umum' class='form-input col-md-12' style='min-width: 180px;'></textarea>",
      //     "<button type='button' class='btn btn-danger hapus' ><i class='fa fa-trash' style='font-size:15px'></i></button>",
      // ] ).draw( false );
      // $("#table_bonus").find('tr').addClass('test');
      // $("select").select2();
      $('.nomor').val(counter);

  }); 

  $(document).on('click', '.hapus', function() {
      $(this).parents(".test").remove();
  });


</script>


</body>
</html>
