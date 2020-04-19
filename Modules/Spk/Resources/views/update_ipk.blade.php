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

  @include("master/sidebar_progress")

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
            <div class="box-header">
              <div class="col-md-12">
                <h3>No. SPK : {{$spk->no}}</h3>         
              </div>
             <div class="col-md-12">
                <h3>Item Pekerjaan : {{$sub->name}}</h3>         
              </div>
              <div class="col-md-12">
                <h3>PIC : {{ $spk->user_pic->user_name or '' }}</h3>         
              </div>
              <div class="col-md-12">
                <h3>Bobot : </h3>         
              </div>
            </div>
            <div class="col-md-6">   

              <!-- <h3 class="box-title">Tambah Data IPK</h3>   -->
              <!-- <form action="{{ url('/')}}/spk/save" method="post" name="form1"> -->
                {{ csrf_field() }}
              <div class="form-group">
                <input class="form-control" type="hidden" name="id_spk" id="id_spk" value="{{$spk->id}}">
                <input class="form-control" type="hidden" name="id_item" id="id_item" value="{{$sub->id}}">
              </div> 
               <div class="box-footer">
                <i class="fa fa-refresh ld ld-spin" id="loading" style="display: none;"></i>
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
                <h3>Detail Item Pekerjaan</h3>
              <div class="tab-pane table-responsive" id="tab_2">
                  <table class="table table-bordered bg-white mg-b-0 tx-center" id="tabel_ipk">
                    <thead class="head_table">
                      <tr>
                        <td style="width:10%">No.</td>
                        <td>Detail Item Pekerjaan</td>
                        <td>Status</td>
                        <td>Aksi</td>
                        <!-- <td>Aksi</td> -->
                      </tr>
                    </thead>
                    <tbody>
                      @php ($no = 0)
                      @foreach ($ipk_tambahan as $key)
                      <tr>
                        <td>{{ $no+1 }} </td>
                        <td>{{ $key->nama_sub}}</td>
                        @if ($key->status == 0)
                        <td>No</td>
                        @else
                        <td>Yes</td>
                         @endif
                         @if ($key->status !=1)
                         <td> <input type="checkbox" class="get_value" name="" value="{{ $key->id }}" id="yes"><strong>Yes</strong></td>
                         @endif
                      </tr>
                      @php ($no++)
                      @endforeach
                    </tbody>
                  </table>
                </div>
                <center>
                  <button type="button" class="btn btn-primary submitbtn" id="btn_submit">Simpan</button>
                </center>
              </div>

           <!--    <div class="col-md-6">
                <h3>IPK Tambahan</h3>
              <div class="tab-pane table-responsive" id="tab_2">
                  <table class="table table-bordered bg-white mg-b-0 tx-center" id="tabel_ipk">
                    <thead class="head_table">
                      <tr>
                        <td style="width:10%">No.</td>
                        <td>Nama IPK</td>
                        <td>Aksi</td>
                      </tr>
                    </thead>
                    <tbody>
                      @php ($no = 0) 
                      @foreach ($ipk_tambahan as $key)
                      <tr>
                        <td>{{ $no+1 }} </td>
                        <td>{{ $key->nama_sub}}</td>
                        <td> <a href="{{ url('/')}}/spk/hapus-ipkt?id={{ $key->id }}" class="btn btn-danger">Hapus</td>
                      </tr>
                      @php ($no++)
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div> -->
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

    $('#btn_submit').hide();
    var checkboxes = $('input[type=checkbox]');
    $(checkboxes).on('change', function() {
      console.log('checkbox changed');
      if($(checkboxes).is(':checked')) {
        $('#btn_submit').show();
      }else{
        $('#btn_submit').hide();
      }
    });

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


    });

    function input(){
      var _url = '{{ url("/")}}/progress/simpan-ipk';
      // var idipk = $('#yes').val();
      var insert = [];

      $('.get_value').each(function(){
        if($(this).is(':checked')){
          insert.push({'id': $(this).val()});
            console.log(insert);
          }
      })
        $.ajax({
            type : "POST",
            url  : _url,
            dataType : "JSON",
            data :{
             insert:insert
            },
            success : function(data){
                alert(data.success);
                 location.reload();
            }     
          });
          return false;
    }

    function hapus(id){
      var _url = "{{ url('/')}}/spk/hapus-ipk";
      var id_item = id;
      var txt;
      var r = confirm("Apakah Anda Yakin Ingin Menghapus Item ini?");
      if (r == true) {
        $.ajax({
            type : "POST",
            url  : _url,
            dataType : "JSON",
            data :{
              id_item: id_item 
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

//     function hapus(no){
//     data_ipk.splice(no,1);
//     tampil();
// }
       
</script>
</body>
</html>
