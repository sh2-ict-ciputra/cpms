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
      <h1>Progress Pekerjaan</h1>

    </section>

    <!-- Main content -->
    <section class="content">
      {{ csrf_field() }}
      <div class="box box-default">
        <!-- /.box-header -->
        <div class="box-body">
          <div class="row">
            <div class="col-md-12">
              <div class="box-header">
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
                      <td>Volume</td>
                      <td>:</td>
                      <td>{{$volume}} {{$sub->item_satuan}}</td>
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
            <div class="col-md-6">   
                {{ csrf_field() }}
              <div class="form-group">
                <label>Nama Progress Pekerjaan</label>
                <input class="form-control" type="text" name="namaprogress" id="namaprogress" required="">
                <input class="form-control" type="hidden" name="id_spk" id="id_spk" value="{{$spk->id}}">
                <input class="form-control" type="hidden" name="id_item" id="id_item" value="{{$sub->id}}">
                <input class="form-control" type="hidden" name="totvolume" id="totvolume" value="{{$volume}}">
                <input class="form-control" type="hidden" name="jumvolume" id="jumvolume" value="{{$jumlah_volume}}">
                <input class="form-control" type="hidden" name="jumunit" id="jumunit" value="{{count($spk->tender->units)}}">
              </div> 
              <div class="form-group">
                <label>Volume</label>
                <input class="form-control" type="number" name="volume" id="volume" required="">
              </div>
              <div class="form-group">
                <label>Satuan</label>
                <select class="form-control" id="satuan">
                  @foreach ($coa as $key)
                    @if ( $key->satuan == $sub->item_satuan)
                      <option  value="{{$key->satuan}}" required="" selected>{{$key->satuan}}</option>
                    @else
                      <option  value="{{$key->satuan}}" required="">{{$key->satuan}}</option>
                    @endif
                  @endforeach
                </select>
              </div>
              <div class="form-group">
                <label>unit</label>
                <select class='form-control select2' name='unit[]' id='unit' style="width:100%" multiple="multiple">
                  @foreach ( $spk->tender->units as $key => $value )
                  @if ($progress_tambahan->where("unit_id",$value->id)->sum("volume") < $volume)
                    <option class="pilih_unit" value="{{$value->id}}">{{$value->rab_unit->asset->name}}</option>
                  @endif
                  @endforeach
                </select> 
                <input type="checkbox" class="get_value" name="" id="unit_all"><strong>Pilih Semua</strong>
              </div>
               <div class="box-footer">
                <i class="fa fa-refresh ld ld-spin" id="loading" style="display: none;"></i>
                <button type="button" class="btn btn-primary submitbtn" id="btn_submit">Tambah</button>
                <!-- <button type="button" class="btn btn-success submitbtn" id="simpan">Simpan IPK</button> -->
                <!-- <a class="btn btn-warning" href="{{ url('/')}}/workorder">Kembali</a> -->
              </div>                
                      
              <!-- /.form-group -->
            </div>
          </div>

            <div class="row">
               <!-- div class="col-md-12 center">             
              </div> -->
              
              <div class="col-md-12">
                <h3>Detail Progress Pekerjaan</h3>
                <input class="" type="" name="" id="tot_unit" value="{{count($spk->tender->units)}}" hidden>
              <div class="tab-pane table-responsive" id="tab_2">
                  <table class="table table-bordered bg-white mg-b-0 tx-center" id="tabel_ipk">
                    <thead class="head_table">
                      <tr>
                        <td style="width:10%">No.</td>
                        <td>Nama</td>
                        <td>No. Unit/Kawasan</td>
                        <td>Volume</td>
                        <td>Satuan</td>
                        <td>Status</td>
                        <td>Aksi</td>
                        <td>hapus</td>
                      </tr>
                    </thead>
                    <tbody>
                      @php ($no = 0)
                      @foreach ($progress_tambahan as $key)
                      <tr>
                        <td>{{ $no+1 }} </td>
                        <td>{{ $key->name}}</td>
                        <td>
                          @if($key->tender_unit->rab_unit->asset != '')
                            {{ $key->tender_unit->rab_unit->asset->name}}
                          @else
                            Fasilitas Kota
                          @endif
                        </td>
                        <td>{{ round($key->volume, 4)}}</td>
                        <td>{{ $key->satuan}}</td>
                        @if ($key->status == 0)
                          <td>No</td>
                        @else
                          <td>Yes</td>
                        @endif
                        @if ($key->status !=1)
                          <td> 
                            {{-- <button onclick="hapus('{{ $key->id}}')" class="btn btn-danger">Hapus</button> --}}
                          <!--  <button onclick="hapus('{{ $key->itempekerjaan_id }}',' {{$key->spk_id}}','{{$key->name}}')" class="btn btn-danger">Hapus</button>  -->
                            <button onclick="edit_progres('{{ $key->id}}')" data-toggle="modal" class="btn btn-info">Edit</button>
                          </td>
                        @else
                            <td></td>
                        @endif
                        @if ($key->status !=1)
                          <td> 
                            {{-- <button onclick="hapus('{{ $key->id}}')" class="btn btn-danger">Hapus</button> --}}
                            <input type="checkbox" class="paramdisable hapus" name="hapus" value="{{ $key->id }}" >
                          </td>
                        @else
                            <td></td>
                        @endif
                      </tr>
                      @php ($no++)
                      @endforeach
                    </tbody>
                  </table>
                  <button type="" class="btn btn-danger" id="btn_posting" style="">Hapus</button>
                </div>
              </div>

           
            </div>
            <!-- /.col -->
            <!--  <div class="col-md-6">
              <h3>&nbsp;</h3>              
              <div class="form-group">
                <label>Start Date</label>
                <input type="text" class="form-control" name="start_date" id="start_date" autocomplete="off" required>
              </div> 
              <div class="box-footer">
                <i class="fa fa-refresh ld ld-spin" id="loading" style="display: none;"></i>
                <button type="submit" class="btn btn-primary submitbtn" id="btn_submit">Simpan</button>
                <a class="btn btn-warning" href="{{ url('/')}}/workorder">Kembali</a>
              </div>
            </div> -->
            <!-- </form> -->
            <!-- /.col -->
          </div>
          <!-- /.row -->
        </div>
        <!-- /.box-body -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <!-- MODAL EDIT -->
        <div class="modal fade" id="ModalaEdit" tabindex="-1" role="dialog" aria-labelledby="largeModal" aria-hidden="true">
            <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 class="modal-title" id="myModalLabel">Edit Progress</h3>
            </div>
            <form class="form-horizontal" >
                <div class="modal-body">
                    <input name="id_progress" id="id_progress" class="form-control" type="hidden">
                    <input name="id_itemp" id="id_itemp" class="form-control" type="hidden" placeholder="idipk" style="width:335px;">
                    <input name="id_itemp" id="id_default" class="form-control" type="hidden" placeholder="idipk" style="width:335px;">
                    <input name="old_name" id="old_name" class="form-control" type="hidden" placeholder="" style="width:335px;">
                    <input name="spk_id" id="spk_id" class="form-control" type="hidden" placeholder="" style="width:335px;">
                    <input class="form-control" type="hidden" name="jumvolume2" id="jumvolume2" value="">
                    <div class="form-group">
                        <label class="control-label col-xs-3" >Progress Pekerjaan</label>
                        <div class="col-xs-9">
                            <input name="new_name" id="new_name" class="form-control" type="text" placeholder="nama" style="width:335px;">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-xs-3" >Volume</label>
                        <div class="col-xs-9">
                            <input name="new_volume" id="new_volume" class="form-control" type="text" placeholder="volume" style="width:335px;">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-xs-3" >Satuan</label>
                        <div class="col-xs-9">
                            <select class="form-control" class="form-control"  id="new_satuan" style="width:335px;">
                               @foreach ($coa as $key)
                              <option  value="{{$key->satuan}}" required="">{{$key->satuan}}</option>
                              @endforeach
                            </select>                        
                        </div>
                    </div>
                </div>
 
                <div class="modal-footer">
                    <button class="btn" data-dismiss="modal" aria-hidden="true">Tutup</button>
                    <button class="btn btn-info" id="btn_update">Update</button>
                </div>
            </form>
            </div>
            </div>
        </div>
        <!--END MODAL EDIT-->
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
  $(function () {
    $(".select2").select2();
  });
  var data_ipk=[];
  var totvolume2 = 0;
  $(document).ready(function(){

      $('#btn_submit').click(function(){
          input();
      });
      $('#btn_update').click(function(e){
          e.preventDefault();
          updateprogress();
      });


    });


    function input(){
      var _url = '{{ url("/")}}/spk/tambah-progress';
      var totvolume = $('#totvolume').val();
      var jumvolume = $('#jumvolume').val();
      var name = $('#namaprogress').val();
      var id_spk = $('#id_spk').val();
      var id_item = $('#id_item').val();
      var volume = parseFloat($('#volume').val());
      var satuan = $('#satuan').val();
      var jumunit = $('#jumunit').val();
      var unit = $("#unit").val()
      var hitungvolume = 0;
      hitungvolume = ((parseFloat(jumvolume))+parseFloat(volume));

      if(name == '' || volume==''|| satuan==''){
        // $("#namaprogress").css("border-color", "red");
        alert('Harap Mengisi isian');
      }else if(hitungvolume>totvolume*jumunit){
        alert('Isian Melebihi Volume');
      }else{
        // alert('masuk');
         $.ajax({
            type : "POST",
            url  : _url,
            dataType : "JSON",
            data :{
              name: name,
              id_spk: id_spk,
              id_item: id_item ,
              volume: volume,
              satuan:satuan,
              unit:unit,
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
          // data_ipk.push({
          //   'namaipk' : $('#namaipk').val(),
          // });
          //   $('#namaipk').val("");
          //   $("#namaipk").css("border-color", "black");
          //   tampil();
        }
    }

    function edit_progres(id_item){
      // console.log(idipk);
      var jumvolume = $('#jumvolume').val();
        $.ajax({
            url : "{{ url('/')}}/spk/edit-progress/" + id_item,
            type : "GET",
            // contentType: "application/json; charset=utf-8",
            dataType : "JSON",
            success : function(data){
              totvolume2 = ((parseFloat(jumvolume)/parseFloat($('#tot_unit').val())) - parseFloat(data.volume));
                $('#id_progress').val(id_item);
                $('#new_name').val(data.name);
                $('#id_itemp').val(data.itempekerjaan_id);
                $('#id_default').val(data.progressdefault_id);
                $('[name=spk_id]').val(data.spk_id);
                $('#new_volume').val(parseFloat(data.volume, 4));
                $('#old_name').val(data.name);
                $('#jumvolume2').val(totvolume2);
                $('#ModalaEdit').modal('show');
            }
        });
    }

     function updateprogress(){
        var _url = "{{ url('/')}}/spk/update-progress";
        var id_progress = $('#id_progress').val();
        var id_item = $('#id_itemp').val();
        var id_spk = $('#spk_id').val();
        var old_name = $('#old_name').val();
        var new_name = $('#new_name').val();
        var new_volume = $('#new_volume').val();
        var new_satuan = $('#new_satuan').val();
        var totvolume = $('#totvolume').val();
        var jumvolume = $('#jumvolume2').val();
        var id_default = $('#id_default').val();
        var jumunit = $('#jumunit').val();
        var hitungvolume = 0;
        hitungvolume = ((parseFloat(jumvolume))+parseFloat(new_volume));

        if(new_name == '' || new_volume==''|| new_satuan==''){
        // $("#namaprogress").css("border-color", "red");
          alert('Harap Mengisi isian');
        }else if(hitungvolume>totvolume*jumunit){
          // console.log(jumvolume);
          // console.log(new_volume);
          console.log(hitungvolume);
          // console.log(totvolume);
          alert('Isian Melebihi Volume');
        }else{
           $.ajax({
            type : "POST",
            url  : _url,
            dataType : "JSON",
            data :{
              id_progress: id_progress,
              id_item: id_item,
              id_spk: id_spk,
              old_name:old_name,
              new_name:new_name,
              new_volume:new_volume,
              new_satuan:new_satuan,
              id_default:id_default
            },
            success : function(data){
                alert(data.success);
                location.reload();           
            }
              
          });
         }
     }

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
      var _url = "{{ url('/')}}/spk/hapus-progress";
      // var id_item = itempekerjaan_id;
      // var spk_id = spk_id;
      // var name = name;
      // var txt;
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
