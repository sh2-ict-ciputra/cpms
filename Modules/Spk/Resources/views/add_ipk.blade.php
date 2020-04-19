<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Admin QS | Dashboard</title>
  @include("master/header")
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  @include("master/sidebar_project")

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>List IPK</h1>

    </section>

    <!-- Main content -->
    <section class="content">
      {{ csrf_field() }}
      <div class="box box-default">
        <!-- /.box-header -->
        <div class="box-body">
          <div class="row">
            <div class="col-md-12">
              <div class="box-header ">
                <a class="btn btn-warning" href="{{ url('/')}}/spk/detail?id={{$spk->id}}">Kembali</a>
                <table class="table" style="font-size:18px;font-weight:bold">
                  <thead>
                    <tr>
                      <td>No. SPK</td>
                      <td>:</td>
                      <td>{{$spk->no}}</td>
                    </tr>
                    <tr>
                      <td>Item Pekerjaan</td>
                      <td>:</td>
                      <td>({{$sub->parent->code}}) {{$sub->parent->name}}</td>
                    </tr>
                    <tr>
                      <td></td>
                      <td></td>
                      <td>({{$sub->code}}) {{$sub->name}}</td>
                    </tr>
                    <tr>
                      <td>PIC</td>
                      <td>:</td>
                      <td>{{ $spk->user_pic->user_name or '' }}</td>
                    </tr>
                    <tr>
                      <td>Bobot</td>
                      <td>:</td>
                      <td> </td>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>
              
                {{ csrf_field() }}
              <div class="form-group col-md-12">
                <div class="col-md-6">
                  <label>Detail IPK dari untuk Item Pekerjaan</label>
                  <input class="form-control" type="text" name="namaipk" id="namaipk" required="">
                  <div class="form-group">
                    <label>unit</label>
                    <select class='form-control select2' name='unit[]' id='unit' style="width:100%" multiple="multiple">
                      @foreach ( $spk->tender->units as $key => $value )
                        <option class="pilih_unit" value="{{$value->id}}">{{$value->rab_unit->asset->name}}</option>
                      @endforeach
                    </select> 
                    <input type="checkbox" class="get_value" name="" id="unit_all"><strong>Pilih Semua</strong>
                  </div>

                  <label>Tahapan</label>
                  <select class='form-control select2' name='tahapan[]' id='tahapan' style="width:100%" multiple="multiple">
                    @foreach ( $spk->progress_tambahan->where("itempekerjaan_id",$sub->id) as $key => $value )
                      <option class="pilih_tahapan" value="{{$value->id}}">{{$value->name}} - {{$value->units->rab_unit->asset->name}}</option>
                    @endforeach
                  </select> 
                  <input type="checkbox" class="get_value" name="" id="tahapan_all"><strong>Pilih Semua</strong>
                  <input class="form-control" type="hidden" name="id_spk" id="id_spk" value="{{$spk->id}}">
                  <input class="form-control" type="hidden" name="id_item" id="id_item" value="{{$sub->id}}">
                  <div class="box-footer">
                    <i class="fa fa-refresh ld ld-spin" id="loading" style="display: none;"></i>
                    <button type="button" class="btn btn-primary submitbtn" id="btn_submit">Tambah</button>
                    <!-- <button type="button" class="btn btn-success submitbtn" id="simpan">Simpan IPK</button> -->
                    <!-- <a class="btn btn-warning" href="{{ url('/')}}/workorder">Kembali</a> -->
                  </div> 
                </div>
                <div class="box-footer">
                </div>
              </div>      
              <!-- /.form-group -->         
          </div>

            <!-- <div class="row"> -->
               <!-- div class="col-md-12 center">             
              </div> -->
              
              <div class="col-md-12">
                <h3>Detail Item Pekerjaan</h3>
              </div>
              <div class="col-md-12">
              <div class="tab-pane table-responsive" id="tab_2">
                  <table style="text-align: center" class="table table-bordered bg-white mg-b-0 tx-center" id="tabel_ipk">
                    <thead class="head_table">
                      <tr>
                        <td style="width:10%">No.</td>
                        <td>Detail Item Pekerjaan</td>
                        <td>Unit\Kawasan</td>
                        <td>tahapan</td>
                        <td>Status</td>
                        <td>Aksi</td>
                        <td>hapus</td>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($ipk_tambahan as $key => $value)
                        <tr>
                          <td>{{ $key+1 }} </td>
                          <td style="text-align:left;">{{ $value->name}}</td>
                          <td>
                            @if($value->tender_unit->rab_unit->asset != '')
                              {{ $value->tender_unit->rab_unit->asset->name}}
                            @else
                              Fasilitas Kota
                            @endif
                          </td>
                          <td>
                            @foreach ($value->ipk_progress_tahapan as $key2 => $value2)
                              @if ($value2->progress != null)
                                {{$value2->progress->name}}, 
                              @endif
                            @endforeach
                          </td>
                          @if ($value->status == 0)
                            <td>No</td>
                          @else
                            <td>Yes</td>
                          @endif
                          @if ($value->status != 1)
                            <td> 
                              {{-- <button onclick="hapus('{{ $value->itempekerjaan_id }}',' {{$value->spk_id}}','{{$value->name}}')" class="btn btn-danger">Hapus</button> --}}
                              <button onclick="edit_ipk('{{ $value->itempekerjaan_id }}','{{$value->spk_id}}','{{$value->name}}')" data-toggle="modal" class="btn btn-info">Edit</button>
                            </td>
                          @else 
                            <td></td>
                          @endif
                          @if ($value->status !=1)
                            <td> 
                              {{-- <button onclick="hapus('{{ $key->id}}')" class="btn btn-danger">Hapus</button> --}}
                              <input type="checkbox" class="paramdisable hapus" name="hapus" value="{{ $value->id }}" >
                            </td>
                          @else
                              <td></td>
                          @endif
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                  <button type="" class="btn btn-danger pull-center" id="btn_posting" style="">Hapus</button>
                </div>
              </div>
            <!-- </div> -->
            <!-- /.col -->
          </div>
          <!-- /.row -->
        </div>
        <!-- /.box-body -->
    </section>
    <!-- /.content -->
  </div>

  <!-- MODAL EDIT -->
        <div class="modal fade" id="ModalaEdit" tabindex="-1" role="dialog" aria-labelledby="largeModal" aria-hidden="true">
            <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 class="modal-title" id="myModalLabel">Edit IPK</h3>
            </div>
            <!-- <form class="form-horizontal" > -->
                <div class="modal-body">
                    <input name="item_p" id="item_p" class="form-control" type="hidden" placeholder="idipk" style="width:335px;">
                    <input name="name_old" id="name_old" class="form-control" type="hidden" placeholder="" style="width:335px;">
                    <input name="spk_id" id="spk_id" class="form-control" type="hidden" placeholder="" style="width:335px;">
                    <div class="form-group">
                        <label class="control-label col-xs-3" >Item Pekerjaan</label>
                        <div class="col-xs-9">
                            <input name="name_ipk" id="name_ipk" class="form-control" type="text" placeholder="ipk" style="width:335px;">
                        </div>
                    </div>
                </div>
 
                <div class="modal-footer">
                    <button class="btn" data-dismiss="modal" aria-hidden="true">Tutup</button>
                    <button class="btn btn-info" id="btn_update">Update</button>
                </div>
            <!-- </form> -->
            </div>
            </div>
        </div>
        <!--END MODAL EDIT-->
  <!-- /.content-wrapper -->
@include("master/copyright")
  
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

@include("master/footer_table")
@include("spk::app")
<script src="{{ url('/')}}/assets/bower_components/select2/dist/js/select2.full.min.js"></script>

<script type="text/javascript">
  var data_ipk=[];
  $(function () {
    $(".select2").select2();
  });
  $(document).ready(function(){

    // table = $('#mydata').DataTable({ 
 
    //         "processing": true, 
    //         "serverSide": true, 
    //         "order": [], 
             
    //         "ajax": {
    //             "url": "{{ url('/')}}/spk/get-ipkdefault}}",
    //             "type": "POST"
    //         },
 
             
    //         "columnDefs": [
    //         { 
    //             "targets": [ 0 ], 
    //             "orderable": false, 
    //         },
    //         ],
 
    //     });

      $('#btn_submit').click(function(){
           input();
      });

      $('#btn_update').click(function(){
        updateipk();
      })


    });


    function input(){
      var _url = '{{ url("/")}}/spk/tambah-ipk';
      var name = $('#namaipk').val();
      var id_spk = $('#id_spk').val();
      var id_item = $('#id_item').val();
      var unit = $('#unit').val();
      var tahapan = $('#tahapan').val();
      if(name == ''){
        $("#namaipk").css("border-color", "red");
      }
       else{

         $.ajax({
            type : "POST",
            url  : _url,
            dataType : "JSON",
            data :{
              name: name,
              id_spk: id_spk,
              id_item: id_item,
              tahapan: tahapan,
              unit: unit,
            },
            beforeSend: function() {
              waitingDialog.show();
            },
            success : function(data){
                // alert(data.success);
                 location.reload();
               // tampil_data_pengemudi();
            }
            
        });
         return false;
        }
    }

    // function hapus(id){
    //   var _url = "{{ url('/')}}/spk/hapus-ipk";
    //   var id_item = id;
    //   var txt;
    //   var r = confirm("Apakah Anda Yakin Ingin Menghapus Item ini?");
    //   if (r == true) {
    //     $.ajax({
    //         type : "POST",
    //         url  : _url,
    //         dataType : "JSON",
    //         data :{
    //           id_item: id_item 
    //         },
    //         success : function(data){
    //             alert(data.success);
    //             location.reload();
              
    //         }
            
    //     });
    //      return false;
    //   } 
    // }

    $('#btn_posting').hide();
    //  var checkboxes = $('.posting_voucher');
    $('.hapus').on('change', function() {
        if($('.hapus').is(':checked')) {
          $('#btn_posting').show();
        }else{
          $('#btn_posting').hide();
        }
      });

    $('#btn_posting').on('click', function() {
      var insert = [];
      $('.hapus').each(function(){
        if($(this).is(':checked')){
          insert.push({'id': $(this).val()});
          }
      })
      var _url = "{{ url('/')}}/spk/hapus-ipk";
      // var id_item = itempekerjaan_id;
      // var spk_id = spk_id;
      // var name = name;
      var txt;
      var r = confirm("Apakah Anda Yakin Ingin Menghapus Item ini?");
      if (r == true) {
        $.ajax({
            type : "POST",
            url  : _url,
            dataType : "JSON",
            data :{
              id_item: insert,
            },
            beforeSend: function() {
              waitingDialog.show();
            },
            success : function(data){
                // alert(data.success);
                location.reload();
              
            }
            
        });
         return false;
      } 
    });

    function tampil(){
    $('#tabel_ipk tbody').empty();
      var i=0;
      for( i;i<data_ipk.length; i++){
        var str =
        '<tr>'+ 
            '<td>'+ (i+1)+'.' +'</td>'+
        '<td>'+data_ipk[i].namaipk+'</td>'+
            '<td><button type="button" onClick="hapus('+i+')" class="btn btn-danger"><i class="icon ion-trash-a"></i></a></td>'+
        '<tr>'; 
        $('#tabel_ipk tbody').append(str);
      } 
    }

    function edit_ipk(id_item,id_spk,name){
      // console.log(idipk);
        $.ajax({
            url : "{{ url('/')}}/spk/edit-ipk/" + id_item+'/'+id_spk+'/'+name,
            type : "GET",
            dataType : "JSON",
            success : function(data){
              console.log(data);
                $('#item_p').val(data.itempekerjaan_id);
                $('[name=spk_id]').val(data.spk_id);
                $('#name_old').val(data.name);
                $('#name_ipk').val(data.name);
                //untuk mengecek jquery yg double
                // jQuery.noConflict();
                $('#ModalaEdit').modal('show');
            }
        });
    }

    function updateipk(){
        var _url = "{{ url('/')}}/spk/update-ipk";
        var id_item = $('#item_p').val();
        var id_spk = $('#spk_id').val();
        var old_name = $('#name_old').val();
        var new_name = $('#name_ipk').val();

         $.ajax({
          type : "POST",
          url  : _url,
          dataType : "JSON",
          data :{
            id_item: id_item,
            id_spk: id_spk,
            old_name:old_name,
            new_name:new_name
          },
          success : function(data){
              alert(data.success);
              location.reload();
            
          }
            
        });
       }

      $('#unit').on('change', function() {
        var _url = '{{ url("/")}}/spk/tahapan-ipk';
        $.ajax({
            type : "POST",
            url  : _url,
            dataType : "JSON",
            data :{
              unit: $(this).val(),
              spk_id : $('#id_spk').val(),
              id_item: $("#id_item").val(),
            },
            beforeSend: function() {
              waitingDialog.show();
            },
            success : function(data){
              var html = '';
              var i;
              // html += '<option value="">Pilih No Dokumen</option></br>';
              for(i=0; i<data.length; i++){
                  html += '<option class="pilih_tahapan" value="'+data[i].id+'">'+data[i].name+' - '+data[i].unit+'</option></br>';
              }
              $('#tahapan').html(html);   
            },
            complete: function() {
              waitingDialog.hide(); 
            },
            
        });
      });

      $('#tahapan_all').on('change', function() {
        if($('#tahapan_all').is(':checked')) {
          $('.pilih_tahapan').attr( "selected",true);
          $(".select2").select2();
        }else{
          $('.pilih_tahapan').removeAttr( "selected",true);
          $(".select2").select2();
        }
      });

      $('#unit_all').on('change', function() {
        if($('#unit_all').is(':checked')) {
          $('.pilih_unit').attr( "selected",true);
          $(".select2").select2();
        }else{
          $('.pilih_unit').removeAttr( "selected",true);
          $(".select2").select2();
        }
      });

//     function hapus(no){
//     data_ipk.splice(no,1);
//     tampil();
// }
       
</script>
</body>
</html>
