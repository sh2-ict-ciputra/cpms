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
      <h1>List Progress</h1>

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
                <a class="btn btn-warning" href="{{ url('/')}}/progress/create?id={{$id_unit}}&spk={{$spk->id}}">Kembali</a>
                <div class="box-header">
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
              </div>
            </div>
            <div class="col-md-6">   

              <!-- <h3 class="box-title">Tambah Data IPK</h3>   -->
              <!-- <form action="{{ url('/')}}/spk/save" method="post" name="form1"> -->
                {{ csrf_field() }}
              <div class="form-group">
                <input class="form-control" type="hidden" name="id_spk" id="id_spk" value="{{$spk->id}}">
                <input class="form-control" type="hidden" name="id_item" id="id_item" value="{{$sub->id}}">
                <input class="form-control" type="hidden" name="id_unit" id="id_unit" value="{{$id_unit}}">
                <input class="form-control" type="hidden" name="volume" id="volume" value="{{$volume}}">
              </div> 
               <div class="box-footer">
                <i class="fa fa-refresh ld ld-spin" id="loading" style="display: none;"></i>
                <!-- <button type="button" class="btn btn-success submitbtn" id="simpan">Simpan IPK</button> -->
              </div>                
                      
              <!-- /.form-group -->
            </div>
          </div>

            <div class="row">
               <!-- div class="col-md-12 center">             
              </div> -->
              <div class="box-body">
                <div class="col-md-12">
                  <h3>Detail Item Pekerjaan</h3>
                <div class="tab-pane table-responsive" id="tab_2">
                    <table class="table table-bordered bg-white mg-b-0 tx-center" id="tabel_ipk">
                      <thead class="head_table">
                        <tr>
                          <td style="width:10%">No.</td>
                          <td>Detail Item Pekerjaan</td>
                          <td>Volume</td>
                          <td>Satuan</td>
                          <td>Status</td>
                          <td>Aksi</td>
                          <!-- <td>Aksi</td> -->
                        </tr>
                      </thead>
                      <tbody>
                        @php ($no = 0)
                        @if($progressvo != '')
                          @foreach ($progressvo as $key)
                          <tr>
                            <td>{{ $no+1 }} </td>
                            <td>{{ $key->name}}</td>
                            <td>{{ $key->volume}}</td>
                            <td>{{ $key->satuan}}</td>
                            @if ($key->status == 0)
                            <td>No</td>
                            @else
                            <td>Yes</td>
                            @endif
                            <!-- @if ($key->status !=1)
                            <td> <input type="checkbox" class="get_value" name="" value="{{ $key->id }}" id="yes"><strong>Yes</strong></td>
                            @else
                            <td></td>
                            @endif -->
                            @if ($key->status !=1)
                              <td> 
                                {{-- <!-- {{$key->ipk_progress}} -->
                                @if($ipk != 0)
                                  @if(count($key->ipk_progress) != 0)
                                    @if($key->pengajuan->status_pengajuan != ''  && $key->pengajuan->status_pengajuan != 0)
                                      @if(count($key->ipk_progress->where("status_ceklis",0)) == 0)
                                        <button type="button" class="btn btn-info progress_modal" data-toggle="modal" data-id="{{$key->id}}">
                                          Progress
                                        </button>
                                      @else
                                        <a class="btn btn-sm btn-warning"  data-toggle="modal" title="Edit" class="modal_progress_ipk" onclick="detail('{{$key->id}}')">IPK</a> 
                                      @endif
                                    @else
                                        Pengajuan Pengecekan Belum di setujui
                                    @endif
                                  @else
                                    @if(isset($key->pengajuan))
                                      <button type="button" class="btn btn-info progress_modal" data-toggle="modal" data-id="{{$key->id}}">
                                          Progress
                                      </button>
                                    @else
                                      Belum ada pengajuan IPK
                                    @endif
                                  @endif
                                @else
                                  <button type="button" class="btn btn-info progress_modal" data-toggle="modal" data-id="{{$key->id}}">
                                    Progress
                                  </button>
                                @endif --}}
                                <button type="button" class="btn btn-info progress_modal" data-toggle="modal" data-id="{{$key->id}}">
                                  Progress
                                </button>
                              </td>
                            @else
                              <td><strong style="color:green">Tahapan sudah selesai</strong></td>
                            @endif
                          </tr>
                          @php ($no++)
                          @endforeach
                        @endif
                      </tbody>
                    </table>
                  </div>
                  <div class="form-group">
                    <center>
                      <button type="button" class="btn btn-primary submitbtn" id="btn_submit">Simpan</button>
                    </center>
                  </div>
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
    <div class="modal fade" id="ModalProgressDetail" tabindex="-1" role="dialog" aria-labelledby="largeModal" aria-hidden="true" style="overflow-y:auto;">
      <div style="width: 1200px" class="modal-dialog modal-lg">
      <div class="modal-content">
      <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          <h3 class="modal-title" id="myModalLabel"> <span style="color: grey " id="namekaw"></span></h3>
      </div>
      <form class="form-horizontal" >
          <div class="modal-body">
            <div class="tab-pane table-responsive" id="tab_2">
              <table id="index_detail" class="table table-bordered bg-white mg-b-0 tx-center" style="font-size:15px; width: 100%; ">
                <thead class="head_table">
                  <tr style="border: 1px solid black;">
                      <td rowspan="" style="vertical-align: middle;">No</td>
                      <td rowspan="" style="vertical-align: middle;">Detail IPK</td>
                      <td rowspan="" style="vertical-align: middle;">Status</td>
                      <td rowspan="" sty  le="vertical-align: middle;">Aksi</td>
                  </tr>
                </thead>
              </table>
            </div>   
          </div>

          <div class="modal-footer">
            <center>
              <button type="button" class="btn btn-primary submitbtn" id="btn_submit_ipk">Simpan</button>
            </center>
          </div>
      </form>
      </div>
      </div>
  </div>

  <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <!-- <h5 class="modal-title" id="exampleModalLabel">Modal title</h5> -->
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          Apakah anda yakin untuk memprogress tahapan ini
          <input type="" id="id_progress" value="" hidden>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" onclick="progress()">Submit</button>
          <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
        </div>
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

      $('#btn_submit_ipk').click(function(){
           inputIpk();
      });


    });

    function input(){
      var _url = '{{ url("/")}}/progress/simpan-progressvo';
      var id_unit = $('#id_unit').val();
      var volume = $('#volume').val();
      var id_spk = $('#id_spk').val();
      var id_item = $('#id_item').val();

      var insert = [];

      $('.get_value').each(function(){
        if($(this).is(':checked')){
          insert.push({'id':$(this).val()});
            console.log(insert);
          }
      })
        $.ajax({
            type : "POST",
            url  : _url,
            dataType : "JSON",
            data :{
             insert:insert,
             id_unit:id_unit,
             volume:volume,
             id_spk:id_spk,
             id_item:id_item
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

    $('#index_detail').DataTable({
        "paging":false,
        "destroy": true,
        "columns":[
                {data:"no",name:"no"},
                {data:"detail_ipk",name:"detail_ipk"},
                {data:"status",name:"status"},
                {data:"aksi",name:"aksi"},
        ],
        "order": [[ 0, 'asc' ]]
    })

    function detail(id_progress){
        var url = "{{ url('/')}}/progress/progress_ipk";
        $('#index_detail').DataTable().clear().draw();
        $.ajax({
            type: 'post',
            dataType: 'json',
            url: url,
            data: {
                id_progress : id_progress,
                tipe : "vo",
            },
            beforeSend: function() {
                waitingDialog.show();
            },
            success: function(data) {
                if (data.progress_ipk.length > 0) {
                  // console.log(data);
                    $(data.progress_ipk).each(function(i, v) { 
                      // console.log(v.status);
                        var ItemTable = {
                            no: i+1,
                            detail_ipk: v.detail_ipk,
                            status: v.status_name,
                            aksi: v.check,
                        };
                        $('#index_detail').DataTable().row.add(ItemTable);
                    });
                }
                $('#index_detail').DataTable().draw();
                // $('#index_detail').DataTable().columns.adjust();
            },
            complete: function() {
                waitingDialog.hide();
                
            }
        });
        $("#ModalProgressDetail").modal('show');
      }

      function inputIpk(){
      var _url = '{{ url("/")}}/progress/simpan-ipk-progress';
      var insert = [];

      $('.check_ipk').each(function(){
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

    $(".progress_modal").click(function() {
      $("#id_progress").val($(this).attr('data-id'));
      $('#exampleModal').modal('show');
    });

    function progress(){
      var _url = '{{ url("/")}}/progress/simpan-progress-pertahap';
      var id_progress = $('#id_progress').val();
      var volume = $('#volume').val();
      console.log(id_progress);
        $.ajax({
            type : "POST",
            url  : _url,
            dataType : "JSON",
            data :{
             id_progress : id_progress,
             volume : volume,
             tipe : "vo",
            },
            success : function(data){
                alert(data.success);
                 location.reload();
            }     
          });
          return false;
       
    }

//     function hapus(no){
//     data_ipk.splice(no,1);
//     tampil();
// }
       
</script>
</body>
</html>
