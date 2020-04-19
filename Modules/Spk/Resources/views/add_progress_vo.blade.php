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
                <a class="btn btn-warning" href="{{ url('/')}}/spk/detail?id={{$vo->spk->id}}">Kembali</a>
                <table class="table" style="font-size:18px;font-weight:bold">
                  <thead>
                    <tr>
                      <td>No. SPK</td>
                      <td>:</td>
                      <td>{{$vo->spk->no}}</td>
                    </tr>
                    <tr>
                      <td>Item Pekerjaan</td>
                      <td>:</td>
                      <td>({{$vo_detail->itempekerjaan->parent->code}}) {{$vo_detail->itempekerjaan->parent->name}}</td>
                    </tr>
                    <tr>
                      <td></td>
                      <td></td>
                      <td>({{$vo_detail->itempekerjaan->code}}) {{$vo_detail->itempekerjaan->name}}</td>
                    </tr>
                    <tr>
                      <td>PIC</td>
                      <td>:</td>
                      <td>{{ $vo->spk->user_pic->user_name or '' }}</td>
                    </tr>
                    <tr>
                      <td>Volume</td>
                      <td>:</td>
                      <td>{{(float)$vo_detail->volume}} {{$vo_detail->satuan}}</td>
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
                <input class="form-control" type="hidden" name="id_spk" id="id_spk" value="{{$vo->spk->id}}">
                <input class="form-control" type="hidden" name="void_detail" id="void_detail" value="{{$vo_detail->id}}">
                <input class="form-control" type="hidden" name="totvolume" id="totvolume" value="{{(float)$vo_detail->volume}}">
                <input class="form-control" type="hidden" name="jumvolume" id="jumvolume" value="{{$jumlah_volume}}">
              </div> 
              <div class="form-group">
                <label>Volume</label>
                <input class="form-control" type="number" name="volume" id="volume" required="">
              </div>
              <div class="form-group">
                <label>Satuan</label>
                <select class="form-control" id="satuan">
                  @foreach ($coa as $key)
                  <option  value="{{$key->satuan}}" required="">{{$key->satuan}}</option>
                  @endforeach
                </select>
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
              <div class="tab-pane table-responsive" id="tab_2">
                  <table class="table table-bordered bg-white mg-b-0 tx-center" id="tabel_ipk">
                    <thead class="head_table">
                      <tr>
                        <td style="width:10%">No.</td>
                        <td>Nama</td>
                        <td>Volume</td>
                        <td>Satuan</td>
                        <td>Status</td>
                        <td>Aksi</td>
                        <!-- <td>Aksi</td> -->
                      </tr>
                    </thead>
                    <tbody>
                      @php ($no = 0)
                      @foreach ($progress_tambahan as $key)
                      <tr>
                        <td>{{ $no+1 }} </td>
                        <td>{{ $key->name}}</td>
                        <td>{{ (float)$key->volume}}</td>
                        <td>{{ $key->satuan}}</td>
                        @if ($key->status == 0)
                        <td>No</td>
                        @else
                        <td>Yes</td>
                         @endif
                         @if ($key->status !=1)
                         <td> <button onclick="hapus('{{ $key->itempekerjaan_id }}',' {{$key->spk_id}}','{{$key->name}}')" class="btn btn-danger">Hapus</button> <button onclick="edit_progresvo('{{ $key->itempekerjaan_id }}','{{$key->spk_id}}','{{$key->name}}')" data-toggle="modal" class="btn btn-info">Edit</button></td>
                         @else
                         <td></td>
                         @endif
                      </tr>
                      @php ($no++)
                      @endforeach
                    </tbody>
                  </table>
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
                    <input name="id_itemp" id="id_itemp" class="form-control" type="hidden" placeholder="idipk" style="width:335px;">
                    <input name="old_name" id="old_name" class="form-control" type="hidden" placeholder="" style="width:335px;">
                    <input name="spk_id" id="spk_id" class="form-control" type="hidden" placeholder="" style="width:335px;">
                    <div class="form-group">
                        <label class="control-label col-xs-3" >Progress Pekerjaan</label>
                        <div class="col-xs-9">
                            <input name="new_name" id="new_name" class="form-control" type="text" placeholder="ipk" style="width:335px;">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-xs-3" >Volume</label>
                        <div class="col-xs-9">
                            <input name="new_volume" id="new_volume" class="form-control" type="text" placeholder="ipk" style="width:335px;">
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

<script type="text/javascript">
  var data_ipk=[];
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
      $('#btn_update').click(function(e){
          e.preventDefault();
          updateprogress();
      });


    });


    function input(){
      var _url = '{{ url("/")}}/spk/vo/tambah-progress';
      var totvolume = $('#totvolume').val();
      var jumvolume = $('#jumvolume').val();
      var name = $('#namaprogress').val();
      var id_spk = $('#id_spk').val();
      var void_detail = $('#void_detail').val();
      var volume = $('#volume').val();
      var satuan = $('#satuan').val();
      var hitungvolume = 0;
      hitungvolume = (parseInt(jumvolume)+parseInt(volume));

      if(name == '' || volume==''|| satuan==''){
        // $("#namaprogress").css("border-color", "red");
        alert('Harap Mengisi isian');
      }else if(hitungvolume>totvolume){
        alert('Isian Melebihi Volume');
      }
       else{
        // alert('masuk');
         $.ajax({
            type : "POST",
            url  : _url,
            dataType : "JSON",
            data :{
              name: name,
              id_spk: id_spk,
              void_detail: void_detail ,
              volume: volume,
              satuan:satuan
            },
            success : function(data){
                alert(data.success);
                $('#namaprogress').val('');
                $('#volume').val('');
                 location.reload();

               // tampil_data_pengemudi();
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

    function hapus(itempekerjaan_id,spk_id,name){
      var _url = "{{ url('/')}}/spk/hapus-progressvo";
      var id_item = itempekerjaan_id;
      var spk_id = spk_id;
      var name = name;
      var txt;
      var r = confirm("Apakah Anda Yakin Ingin Menghapus Item ini?");
      if (r == true) {
        $.ajax({
            type : "POST",
            url  : _url,
            dataType : "JSON",
            data :{
              id_item: id_item,
              spk_id: spk_id,
              name: name
            },
            success : function(data){
                alert(data.success);
                location.reload();
              
            }
            
        });
         return false;
      } 
    }

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

    function edit_progresvo(id_item,id_spk,name){
      // console.log(idipk);
        $.ajax({
            url : "{{ url('/')}}/spk/edit-progressvo/" + id_item+'/'+id_spk+'/'+name,
            type : "GET",
            dataType : "JSON",
            success : function(data){
              console.log(data);
                $('#new_name').val(data.name);
                $('#id_itemp').val(data.itempekerjaan_id);
                $('[name=spk_id]').val(data.spk_id);
                $('#new_volume').val(parseFloat(data.volume));
                $('#old_name').val(data.name);
                //untuk mengecek jquery yg double
                // jQuery.noConflict();
                $('#ModalaEdit').modal('show');
            }
        });
    }

    function updateprogress(){
        var _url = "{{ url('/')}}/spk/update-progressvo";
        var id_item = $('#id_itemp').val();
        var id_spk = $('#spk_id').val();
        var old_name = $('#old_name').val();
        var new_name = $('#new_name').val();
        var new_volume = $('#new_volume').val();
        var new_satuan = $('#new_satuan').val();


         $.ajax({
          type : "POST",
          url  : _url,
          dataType : "JSON",
          data :{
            id_item: id_item,
            id_spk: id_spk,
            old_name:old_name,
            new_name:new_name,
            new_volume:new_volume,
            new_satuan:new_satuan
          },
          success : function(data){
              alert(data.success);
              location.reload();           
          }
            
        });
       }


//     function hapus(no){
//     data_ipk.splice(no,1);
//     tampil();
// }
       
</script>
</body>
</html>
