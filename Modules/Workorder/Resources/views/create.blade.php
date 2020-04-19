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
</head>
<style>
  .form-group{
    margin-bottom: 0px;
  }
</style>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  @include("master/sidebar_project")

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>Data Workorder</h1>

    </section>

    <!-- Main content -->
    <section class="content">

      <!-- SELECT2 EXAMPLE -->
      <div class="box box-default">

        <!-- /.box-header -->
        <div class="box-body">
          <div class="row">
            <div class="col-md-12">   

              <h3 class="box-title">Tambah Data Workorder</h3>           
              <form action="{{ url('/')}}/workorder/save" method="post" name="form1" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="form-group row col-md-12" style="margin:5px 5px 5px 5px">
                  <div class="form-group col-md-2">   
                    <label >PT</label>
                  </div>
                  <div class="form-group col-md-10">  
                    <select class="form-control" name="pt_id" id="pt_id">
                      @foreach ( $project->pt_user as $key6 => $value6)
                        @if($user->id == $value6->user_id)
                            <option value="{{ $value6->pt->id}}">{{ $value6->pt->name }}</option>
                        @endif
                      @endforeach
                    </select>
                  </div>
                </div>

                <div class="form-group row col-md-12" style="margin:5px 5px 5px 5px">
                  <div class=" form-group col-md-2">   
                    <label >Department In Charge</label>
                  </div>
                  <div class="form-group col-md-10">  
                    <select id="department" class="form-control" name="department_from">
                      @foreach ( $project->budget_department as $key => $value )
                          @if($value == 2)         
                            <option value="{{ $value}}">{{ \Modules\Department\Entities\Department::find($value)->name }}</option>
                          @else          
                            <!-- <option value="{{ $value}}">{{ \Modules\Department\Entities\Department::find($value)->name }} </option> -->
                          @endif
                      @endforeach
                    </select>
                  </div>
                </div>

                <div class="form-group row col-md-12" style="margin:5px 5px 5px 5px">
                  <div class="form-group col-md-2">   
                    <label >Department Support</label>
                  </div>
                  <div class="form-group col-md-10">  
                    <select class="form-control" name="department_to">
                      @foreach ( $project->budget_department as $key => $value )                   
                          @if($value == 2)         
                            <option value="{{ $value}}"selected>{{ \Modules\Department\Entities\Department::find($value)->name }}</option>
                          @else          
                            <!-- <option value="{{ $value}}">{{ \Modules\Department\Entities\Department::find($value)->name }}</option> -->
                          @endif
                      @endforeach
                    </select>
                  </div>
                </div>

                <div class="form-group row col-md-12" style="margin:5px 5px 5px 5px">
                  <div class="form-group col-md-2">   
                    <label>Lokasi</label>
                  </div>
                  <div class="form-group col-md-3">  
                    <select class="form-control" name="cluster_faskot" id="cluster_faskot" style="width:100%" required>
                      <option value="">( pilih Fasilitas Kota / Cluster)</option>
                      <option value="cluster">Cluster</option>
                      <option value="faskot">Faskot</option>
                    </select>
                  </div>
                  <div class="form-group col-md-3 lokasi_cluster" id="lokasi_cluster" hidden>  
                    <select class="form-control" name="kawasan" id="kawasan" style="width:100%">
                      <option value="">( pilih Cluster)</option>
                        @foreach( $project->kawasans as $key => $value )                   
                          <option value="{{$value->id}}">{{$value->name}}</option>
                        @endforeach
                    </select>
                  </div>
                  <div class="form-group col-md-3 lokasi_cluster" id="" hidden>  
                  <label class="container">
                    <input type="radio" class="iradio_flat-green radio-unit" name="radio" value="unit" >
                    <span class="checkmark"></span>
                    Unit
                  </label>
                  <label class="container">
                    <input type="radio" class="iradio_flat-green radio-unit" name="radio" value="non-unit" checked>
                    <span class="checkmark"></span>
                    Non Unit
                  </label>
                  </div>
                </div>

                <div class="form-group row col-md-12 div-unit" style="margin:5px 5px 5px 5px" hidden>
                  <div class="form-group col-md-2">   
                    <label ></label>
                  </div>
                  <div class="form-group col-md-10">  
                    <div class="form-group col-md-12" style="padding: 0px;">   
                      <div class="form-group col-md-2" style="padding: 0px;">  
                        <button type="button" class="btn btn-primary " id="btn_tambah_unit" style="width:90%;margin:0px 5px 0px 5px"><label id="label-unit">unit</label></button>
                      </div>
                      <div class="form-group col-md-3" style="padding: 0px;">  
                        <label id="label-type"></label><br>
                        <label id="label-luas"></label>
                      </div>
                    </div>
                    <textarea class="form-group" id="textarea_unit" name="textarea" style="width:100%; height: 100px;margin:10px 0px 0px 0px"></textarea>
                  </div>
                </div>
                
                <div class="form-group row col-md-12" style="margin:5px 5px 5px 5px">
                  <div class="form-group col-md-2">   
                    <label >Item Pekerjaan</label>
                  </div>
                  <div class="form-group col-md-10">  
                    <select class="form-control" name="item_pekerjaan" id="item_pekerjaan" required>
                      <option value="">Pilih Item Pekerjaan</option>
                      @foreach ( $itempekerjaan_parent as $key => $value)
                        <option value="{{ $value->id}}">{{ $value->code }} | {{ $value->name }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>

                <div class="form-group row col-md-12" style="margin:5px 5px 5px 5px">
                  <div class="form-group col-md-2">   
                    <label ></label>
                  </div>
                  <div class="form-group col-md-10">  
                    <select class="form-control" name="sub_item_pekerjaan" id="sub_item_pekerjaan" required>

                    </select>
                  </div>
                </div>
                <div class="form-group row col-md-12" style="margin:5px 5px 5px 5px">
                  <div class="form-group col-md-2">   
                    <label ></label>
                  </div>
                  <div class="form-group col-md-1">  
                   <button type="button" class="btn btn-success" id="btn_upload"><label>Upload</label></button>
                  </div>
                  <div class="form-group col-md-3">  
                   <label id="label-gambar"></label><br>
                   <label id="label-spesifikasi"></label>
                  </div>
                </div>

                <div class="form-group row col-md-12" style="margin:5px 5px 5px 5px">
                  <div class="form-group col-md-2">   
                    <label>Judul WO</label>
                  </div>
                  <div class="form-group col-md-10">  
                    <input type="text" class="form-control" name="workorder_name" autocomplete="off" required>
                  </div>
                </div>

                <div class="form-group row col-md-12" style="margin:5px 5px 5px 5px">
                  <div class="form-group col-md-2">   
                    <label>Keterangan</label>
                  </div>
                  <div class="form-group col-md-10">  
                    <input type="text" class="form-control" name="workorder_description" autocomplete="off" required>
                  </div>
                </div>

                <div class="modal fade " id="ModalUploadFile" tabindex="-1" role="dialog" aria-labelledby="largeModal" aria-hidden="true" style="overflow-y:auto">
                  <div style="width: 90%" class="modal-dialog modal-lg ">
                    <div class="modal-content">
                      <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                          <h3 class="modal-title" id="">Upload File</h3>
                      </div>
                      <!-- <form class="form-horizontal" > -->
                          <div class="modal-body">
                            <div class="tab-pane table-responsive" id="tab_2">
                              <div class="form-group row col-md-12" style="margin:5px 5px 5px 5px">
                                <button id="addRow" style="margin: 10px 5px 10px 5px">Add new row</button>
                                <i><strong>(file yang diupload total maksimal 10 Mb dan hanya bertype *.doc, *.docx, *.xls, *.xlsx, *.jpg, *.jpeg, *.png, dan *.pdf)</strong></i>
                                <table class="table" style="width:100%;" id="table_upload">
                                  <thead class="head_table">
                                    <tr>
                                      <td style="width:30%;">Kategori</td>
                                      <td style="width:30%;">File</td>
                                      <td style="width:40%;">Name</td>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <tr class="test">
                                      <td>
                                        <select class="form-control kategori" name="kategori[]" id="" style="width:100%;">
                                          <option value="">(pilih Kategori)</option>
                                          <option value="gambar_tender">Gambar Tender</option>
                                          <option value="spesifikasi">Spesifikasi</option>
                                        </select>
                                      </td>
                                      <td>
                                        <input type="file" class="form-control file" name="file[]" style="width:100%;">
                                      </td>
                                      <td>
                                        <input type="text" class="form-control file_name" name="file_name[]" autocomplete="off" style="width:100%;">
                                      </td>
                                    </tr>
                                  </tbody>
                                </table>
                              </div>
                            </div>
                          </div> 
                          <div class="modal-footer" style="text-align: center;">
                            <!-- <input type="hidden" name="all_send" id="all_send" /> -->
                            <a id="btn-submit-upload" class="btn btn-primary">Simpan</a>
                          </div>
                      <!-- </form> -->
                    </div>
                  </div>
                </div>

                <div class="box-footer" style="text-align:center;">
                  <i class="fa fa-refresh ld ld-spin" id="loading" style="display: none;"></i>
                  <input type="hidden" name="all_send_unit" id="all_send_unit" />
                  <!-- <input type="hidden" name="all_send_upload" id="all_send_upload" /> -->
                  <button type="submit" class="btn btn-primary " id="">Simpan</button>
                  <a class="btn btn-warning" href="{{ url('/')}}/workorder">Kembali</a>
                </div>

              </form>
            </div>
            <!-- /.col -->

            
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

  <div class="modal fade " id="ModalUnit" tabindex="-1" role="dialog" aria-labelledby="largeModal" aria-hidden="true" style="overflow-y:auto">
    <div style="width: 90%" class="modal-dialog modal-lg ">
      <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3 class="modal-title" id="myModalLabel">List Unit</h3>
        </div>
        <!-- <form class="form-horizontal" > -->
        <div class="modal-body">
          <div class="tab-pane table-responsive" id="tab_2">
            <div class="form-group row col-md-12" style="margin:5px 5px 5px 5px">
              <input type="hidden"  id="hidden_type_id" />
              <input type="hidden"  id="hidden_type_name" />
              <select class="form-control" name="type" id="type" style="width:30%;margin:5px 5px 5px 5px" required>
                <option value="">(pilih Type Unit)</option>
                  
              </select>
            </div>
            <div class="form-group row col-md-12" style="margin:5px 5px 5px 5px">
              <table class="table" style="width:100%;" id="table_unit">
                <thead class="head_table">
                  <tr>
                    <td>Unit Name</td>
                    <td>Type</td>
                    <td>Luas Tanah</td>
                    <td>Luas Bangunan</td>
                    <td>Set to WO</td>
                    <td>Status Unit</td>
                    <!-- <td>Tgl DP Kons. Lunas-Akad Kredit</td>
                    <td>Target Hrs Mulai Bangun</td>
                    <td>No. SPT</td> -->
                    <td>Renc. Serah Terima ke konsumen</td>
                    <td>% Bayar Kons.</td>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div> 
        <div class="modal-footer" style="text-align: center;">
          <!-- <input type="hidden" name="all_send" id="all_send" /> -->
          <a id="btn-submit-unit" class="btn btn-primary">Simpan</a>
        </div>
        <!-- </form> -->
      </div>
    </div>
  </div>


  <div id="clone_kategori" hidden>
    <select class="form-control kategori" name="kategori[]" id="" style="width:100%;">
      <option value="">(pilih Kategori)</option>
      <option value="gambar_tender">Gambar Tender</option>
      <option value="spesifikasi">Spesifikasi</option>
    </select>
  </div>
  @include("master/copyright")

  
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

@include("master/footer_table")
<!-- Select2 -->
<script src="{{ url('/')}}/assets/bower_components/select2/dist/js/select2.full.min.js"></script>
<script src="{{ url('/')}}/assets/plugins/input-mask/jquery.inputmask.js"></script>
<script src="{{ url('/')}}/assets/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="{{ url('/')}}/assets/plugins/input-mask/jquery.inputmask.extensions.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript">
// $("select").select2();
  // $(document).ready(function(){
  //   // console.log($('#department').val());
  //   changeDep($('#department').val());

  // })

  $(function () {
    $("#luas").number(true);

    $('#start_date').datepicker({
      "dateFormat" : "yy-mm-dd"
    });

    $('#end_date').datepicker({
      "dateFormat" : "yy-mm-dd"
    });

    $("#btn_submit").click(function(){
      $(".submitbtn").hide();
      $("#loading").show();
    });
    
  });

  // $('#department').change(function(){
  //   var dep = $('#department').val();
  //   $.ajax({
  //           url : "{{ url('/')}}/workorder/getfasilitas/" + dep,
  //           type : "GET",
  //           dataType : "JSON",
  //           success : function(data){
  //             console.log(data);
  //             var html = '';
  //                 var i;
  //                 for(i=0; i<data.length; i++){
  //                     html += '<option value="'+data[i].id+'">'+data[i].name+'</option></br>';
  //                 }
  //                 $('#budget_tahunan').html(html);
  //               //untuk mengecek jquery yg double
  //               // jQuery.noConflict();
                
  //           }
  //       });
  // })

  // function changeDep(dep){
  //   $.ajax({
  //           url : "{{ url('/')}}/workorder/getfasilitas/" + dep,
  //           type : "GET",
  //           dataType : "JSON",
  //           success : function(data){
  //             console.log(data);
  //             var html = '';
  //                 var i;
  //                 for(i=0; i<data.length; i++){
  //                     html += '<option value="'+data[i].id+'">'+data[i].name+'</option></br>';
  //                 }
  //                 // console.log(html);
  //                 $('#budget_tahunan').html(html);
  //               //untuk mengecek jquery yg double
  //               // jQuery.noConflict();               
  //           }
  //       });
  // }

  $("#start_date").change(function(){
      var date = $('#start_date').datepicker('getDate');
      date.setTime(date.getTime() + (1000 * 60 * 60 * 24 * $("#workorder_durasi").val()));
      $('#end_date').datepicker("setDate", date);
      //$("#end_date").val(tomorrow);
  });

  $("#cluster_faskot").change(function(){
      if($(this).val() == "cluster"){
        $('.lokasi_cluster').show();
      }else if($(this).val() == "faskot"){
        $('.lokasi_cluster').hide();
      }else{
        $('.lokasi_cluster').hide();
      }
  });

  $( ".radio-unit" ).on( "click", function() {
    if($(this).val() == "unit"){
      var url = "{{ url('/')}}/workorder/list-type";
      // $('#table_unit').DataTable().clear().draw();
      $.ajax({
          type: 'post',
          dataType: 'json',
          url: url,
          data: {
              kawasan_id: $("#kawasan").val()
          },
          beforeSend: function() {
              waitingDialog.show();
          },
          success: function(data) { 
              var html = '';
              var i;
              html += '<option value="0">Pilih Type Unit</option></br>';
              for(i=0; i<data.length; i++){
                if(data[i]['type_id'] == $("#hidden_type_id").val()){
                  html += '<option value="'+data[i]['type_id']+'" selected>'+data[i]['type_name']+'</option></br>';
                }else{
                  html += '<option value="'+data[i]['type_id']+'">'+data[i]['type_name']+'</option></br>';
                }
              }
              $('#type').html(html);      
          },
          complete: function() {
              waitingDialog.hide();
              
          }
      });
      $('#ModalUnit').modal('show');
      $('.div-unit').show();
    }else if($(this).val() == "non-unit"){ 
      $('.div-unit').hide();
    }
  });


  $('#table_unit').DataTable({
    "paging":false,
    "destroy": true,
    "columns":[
            {data:"unit_name",name:"unit_name"},
            {data:"type",name:"type"},
            {data:"luas_tanah",name:"luas_tanah", className: "text-right"},
            {data:"luas_bangunan",name:"luas_bangunan", className: "text-right"},
            {data:"set_to_wo",name:"set_to_wo", className: "text-center"},
            {data:"status",name:"status"},
            {data:"rencana_st",name:"rencana_st", className: "text-center"},
            {data:"bayar",name:"bayar", className: "text-right"},
            ],
    "order": [[ 0, 'asc' ]]
  })

  $("#btn_tambah_unit").click(function(){
    var url = "{{ url('/')}}/workorder/list-type";
    // $('#table_unit').DataTable().clear().draw();
    $.ajax({
        type: 'post',
        dataType: 'json',
        url: url,
        data: {
            kawasan_id: $("#kawasan").val()
        },
        beforeSend: function() {
            waitingDialog.show();
        },
        success: function(data) { 
            var html = '';
            var i;
            html += '<option value="0">Pilih Type Unit</option></br>';
            for(i=0; i<data.length; i++){
              if(data[i]['type_id'] == $("#hidden_type_id").val()){
                html += '<option value="'+data[i]['type_id']+'" selected>'+data[i]['type_name']+'</option></br>';
              }else{
                html += '<option value="'+data[i]['type_id']+'">'+data[i]['type_name']+'</option></br>';
              }
            }
            $('#type').html(html);      
        },
        complete: function() {
            waitingDialog.hide();
            
        }
    });
    $('#ModalUnit').modal('show');
  });

  $('#type').change(function(){
    var url = "{{ url('/')}}/workorder/list-unit";
    $("#hidden_type_id").val($(this).val());
    $("#hidden_type_name").val('');
    $('#table_unit').DataTable().clear().draw();
    $.ajax({
        type: 'post',
        dataType: 'json',
        url: url,
        data: {
          kawasan_id: $("#kawasan").val(),
          type_id: $(this).val()
        },
        beforeSend: function() {
          waitingDialog.show();
        },
        success: function(data) { 
          if (data.data.length > 0) {
                $(data.data).each(function(i, v) { 
                  $("#hidden_type_name").val(v.type);
                    var ItemTable = {
                      unit_name : v.unit_name+'<input class="nama" value="'+v.unit_name+'" hidden>',
                      type : v.type,
                      luas_tanah : v.luas_tanah,
                      luas_bangunan : v.luas_bangunan+'<input class="LBLT" value="'+v.LBLT+'" hidden>',
                      set_to_wo : '<input class="disable_unit" type="checkbox" value="'+v.id+'">',
                      status : v.status_unit,
                      rencana_st : v.rencana_st,
                      bayar : v.bayar
                    };
                    $('#table_unit').DataTable().row.add(ItemTable);
                });
            } 
            $('#table_unit').DataTable().draw();
            $("#table_unit").find('tr').addClass('test');
        },
        complete: function() {
          waitingDialog.hide(); 
        }
    });
  })

  var units = [];

  $("#btn-submit-unit").click(function(){
    units = [];
    var text = '';
    var LBLT = '';
    $('.disable_unit').each(function () {
        if (this.checked){
          var arr = [
            $(this).val(),
            $(this).parents('.test').find('.nama').val(),
          ];
          units.push(arr);
          if(text == ''){
            text = text + $(this).parents('.test').find('.nama').val();
          }else{
            text = text + " , " + $(this).parents('.test').find('.nama').val();
          }
          LBLT = $(this).parents('.test').find('.LBLT').val();
        }
    });
    
    $('#all_send_unit').val(JSON.stringify(units));
    document.getElementById("label-unit").innerHTML = 'Unit ('+units.length+')';
    document.getElementById("label-type").innerHTML = 'Type Unit : '+$("#hidden_type_name").val()+'';
    document.getElementById("label-luas").innerHTML = 'LB/LT : '+LBLT+'';
    $('#textarea_unit').val(text);
    $('#ModalUnit').modal('hide');
  });

  $('#item_pekerjaan').change(function(){
    var url = "{{ url('/')}}/workorder/sub-item-pekerjaan";
    $.ajax({
        type: 'post',
        dataType: 'json',
        url: url,
        data: {
          pekerjaan_id: $(this).val(),
        },
        beforeSend: function() {
          waitingDialog.show();
        },
        success: function(data) { 
          var html = '';
            var i;
            html += '<option value="">Pilih Sub Item Pekerjaan</option></br>';
            for(i=0; i<data.length; i++){
                html += '<option value="'+data[i].id+'">'+data[i].code+' | '+data[i].name+'</option></br>';
            }
            $('#sub_item_pekerjaan').html(html);   
        },
        complete: function() {
          waitingDialog.hide(); 
        }
    });
  })

  $("#btn_upload").click(function(){
    $('#ModalUploadFile').modal('show');
  });

  var t = $('#table_upload').DataTable({
            autoWidth: false,
            paging:false,
        });
  $('#addRow').on( 'click', function () {
      t.row.add( [
          $('#clone_kategori').clone().html(),
          "<input type='file' class='form-control file' name='file[]' style='width:100%;'>",
          "<input type='text' class='form-control file_name' name='file_name[]' autocomplete='off' style='width:100%;'>"
      ] ).draw( false );
      $("#table_upload").find('tr').addClass('test');
      // $("select").select2();
  }); 

  var file = [];
  $("#btn-submit-upload").click(function(){
    file = [];
    var kategori_tot = 0;
    var spesifikasi_tot = 0;
    $('.file').each(function () {
        if ($(this).val() != "" && $(this).parents('.test').find('.kategori').val() != null && $(this).parents('.test').find('.file_name').val() != null){
          console.log($(this).val());
          var arr = [
            $(this).val(),
            $(this).parents('.test').find('.kategori').val(),
            $(this).parents('.test').find('.file_name').val(),
          ];
          file.push(arr);
          if($(this).parents('.test').find('.kategori').val() == "gambar_tender"){
            kategori_tot += 1;
          }else if($(this).parents('.test').find('.kategori').val() == "spesifikasi"){
            spesifikasi_tot += 1;
          }
        }
    });
    console.log(file);
    // $('#all_send_upload').val(JSON.stringify(file));
    document.getElementById("label-gambar").innerHTML = 'Gambar Tender : '+kategori_tot+'';
    document.getElementById("label-spesifikasi").innerHTML = 'spesifikasi : '+spesifikasi_tot+'';
    $('#ModalUploadFile').modal('hide');
  });
</script>
@include("pt::app")
</body>
</html>
