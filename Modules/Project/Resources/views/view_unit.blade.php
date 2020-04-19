<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Admin QS | Dashboard </title>
  @include("master/header")
    <!-- Select2 -->
  <link rel="stylesheet" href="{{ url('/')}}/assets/bower_components/select2/dist/css/select2.min.css">
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  @include("master/sidebar_project")

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>Data Proyek <strong>{{ $project->name }}</strong></h1>

    </section>

    <!-- Main content -->
    <section class="content">

      <!-- SELECT2 EXAMPLE -->
      <div class="box box-default">

        <!-- /.box-header -->
        <div class="box-body">
          <div class="row">
              <h3 class="box-title">Tambah Data Unit</h3>
            <div class="col-md-6">              
              <form action="{{ url('/')}}/project/update-unit" method="post" name="form1">
                {{ csrf_field() }}
              <input type="hidden" name="project_id" id="project_id" value="{{ $unit->blok->kawasan->project->id }}">
              <input type="hidden" name="projectkawasan" id="projectkawasan" value="{{ $unit->blok->kawasan->id }}">
              <input type="hidden" name="blok" value="{{ $unit->blok->id }}">
              <input type="hidden" name="unit" value="{{ $unit->id }}">
              
              <div class="form-group">
                <label>Unit Nomor</label>
                <input type="text" class="form-control" name="unit_nomor" value="{{ $unit->name }}" {{ $readonly }}>
              </div>
              <div class="form-group">
                <label>PT</label>
                <select class="form-control" name="pt_id" id="pt_id" {{ $readonly }}>
                  @foreach ( $project->pt_user as $key6 => $value6)
                    @if($user->id == $value6->user_id)
                      @if ( $value6->pt_id == $unit->pt_id )
                        <option value="{{ $value6->pt->id}}" selected>{{ $value6->pt->name }}</option>
                      @else
                        <option value="{{ $value6->pt->id}}">{{ $value6->pt->name }}</option>
                      @endif
                    @endif
                  @endforeach
                </select>
              </div>

              <div class="form-group">
                <label>Unit Type</label>
                <select class="form-control" name="unit_type" id="unit_type" {{ $readonly }}>
                  <option value="">Pilih Type</option>
                  @foreach ( $unit->blok->kawasan->unit_type as $key5 => $value5 )
                  @if ( $value5->id  == $unit->unit_type_id)
                  <option value="{{ $value5->id }}" selected>{{ $value5->name }}</option>
                  @else
                  <option value="{{ $value5->id }}">{{ $value5->name }}</option>
                  @endif
                  @endforeach
                </select>
              </div>
              <div class="form-group">
                <label>Tipe</label>
                <input type="text" class="form-control" name="kategori" id="kategori" value="{{ $unit->type->category->category_project->category_detail->category->name or '-' }} {{ $unit->type->category->category_project->category_detail->sub_type or '-'}}" {{ $readonly }}>
              </div>
              <div class="form-group">
                <label>Luas Tanah(m2)</label>
                <input type="text" class="form-control" name="luas_tanah" id="luas_tanah" value="{{ $unit->tanah_luas }}" {{ $readonly }}>
              </div>
              <div class="form-group">
                <label>Luas Bangunan(m2)</label>
                <input type="text" class="form-control" name="luas_bangunan" id="luas_bangunan" value="{{ $unit->bangunan_luas }}" {{ $readonly }}>
                <input type="text" id="luas_bangunan_kavling" value="0" style="display: none;">
              </div>

              <div class="form-group">
                <label>Luas Tanah Tambahan(m2)</label>
                <input type="text" class="form-control" name="luas_tanah_tambahan" id="luas_tanah_tambahan" value="@if($unit->luas_tanah_tambahan != null) {{ $unit->luas_tanah_tambahan }} @else 0 @endif" >
              </div>
               
              <div class="box-footer">
                
                @if ( count($unit_pendings) <= 0 )
                  @if ( $unit->inactive_at == NULL )
                    @if ( $unit->status < 3)
                      <button type="submit" class="btn btn-primary" {{ $readonly }}>Simpan</button>
                    @endif
                  @else
                    <strong>Unit ini dipending</strong>
                  @endif
                @else
                  @if ( $unit->status == 0 )
                    @if ( $unit->inactive_at == NULL )
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    @endif
                  @else
                    Status : <strong>{{ $unit_pendings[0]['status']}}</strong><br/>
                    <button class="btn btn-success" onClick="updatestatus('{{$unit->id}}','1','{{ $unit_pendings[0]['id']}}');" type="button">Terima</button>
                    <button class="btn btn-danger" onClick="updatestatus('{{$unit->id}}','0','{{ $unit_pendings[0]['id']}}');" type="button">Tolak</button>
                  @endif
                @endif
                <a href="{{ url('/')}}/project/units/?id={{ $unit->blok->id }}" class="btn btn-warning">Kembali</a>

              </div>
              <!-- /.form-group -->
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Arah Bangunan </label>
                <select class="form-control" name="unit_arah_id" id="unit_arah_id" {{ $readonly }}>
                  <option value='1' {{$array['1']}}>Utara</option>
                  <option value='2' {{$array['2']}}>Timur Laut</option>
                  <option value='3' {{$array['3']}}>Timur</option>
                  <option value='4' {{$array['4']}}>Tenggara</option>
                  <option value='5' {{$array['5']}}>Selatan</option>
                  <option value='6' {{$array['6']}}>Barat Daya</option>
                  <option value='7' {{$array['7']}}>Barat</option>
                  <option value='8' {{$array['8']}}>Barat Laut</option>
                </select>
              </div>
              <div class="form-group">
                <label>Hadap Bangunan</label>
                <select class='form-control' name='unit_position' id='unit_position'>
                  @foreach ( $unit->blok->kawasan->unitarahs as $key => $value )
                    @if($unit->unit_hadap_id == $value->id)
                      <option value="{{ $value->id}}" selected>{{$value->name}}</option>
                    @else
                      <option value="{{ $value->id}}">{{$value->name}}</option>
                    @endif
                  @endforeach
                </select>
              </div>

              <div class="form-group">
                <label>Product Kategori</label>
                <select class='form-control select2' name='tag_kategori' id='tag_kategori' {{ $readonly }}>
                  @if ( $unit->tag_kategori == "B")
                  <option value='B' selected>Bangunan</option>
                  <option value='K'>Kavling</option>
                  @else
                  <option value='B'>Bangunan</option>
                  <option value='K' selected>Kavling</option>
                  @endif
                </select>
              </div>
              <div class="form-group">
                <label>Status Sellable</label>
                <select class='form-control' name='is_sellable' id='is_sellable'>
                  <option value='1' {{ $sellable_1 }}>Ya</option>
                  <option value='0' {{ $sellable_0 }}>Tidak</option>
                </select>
              </div>
              <div class="form-group">
                <label>Purpose</label>
                <select class='form-control' name='purpose' id='purpose' placeholder="pilih purpose">
                <option value="" selected>pilih Purpose</option>
                  @foreach ( $purpose as $key => $value )
                    @if($unit->purpose_id == $value->id)
                      <option value="{{ $value->id}}" selected>{{$value->purpose}}</option>
                    @else
                      <option value="{{ $value->id}}">{{$value->purpose}}</option>
                    @endif
                  @endforeach
                </select>
              </div>

              <div class="form-group">
                <label>Status Unit</label>
                <select class='form-control' name='is_status' id='is_status' readonly>
                  <option value='0' {{ $draft_selected }}>Draft P&D</option>
                  <option value='1' {{ $planning_selected }}>Planning</option>
                </select>
              </div>
              <div class="form-group">
                <label>Keterangan</label>
                <textarea class='form-control' name="description" id="description" cols="45" rows="5" placeholder="Descriptions" {{ $readonly }}></textarea>
              </div>    
            </div>
              </form>
            <!-- /.col -->
            
            <div class="col-md-12">
            <!-- <button type="button" class="btn btn-info" id="btn_generate" data-toggle="modal" data-target="#modalForm">Lihat Data di erems</button> -->
            <div class="modal fade" id="modalForm" role="dialog" >
              <div class="modal-dialog" style="width:70%">
                  <div class="modal-content" >
                      <!-- Modal Header -->
                      <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal">
                              <span aria-hidden="true">&times;</span>
                              <span class="sr-only">Close</span>
                          </button>
                          <h4 class="modal-title" id="myModalLabel">Data Unit {{ $unit->name }} di erems</h4>
                      </div>
                      
                      <!-- Modal Body -->       
                      <div class="modal-body col-md-12" >
                          <p class="statusMsg"></p>
                          <!-- <form action="{{ url('/')}}/project/update-unit" method="post" name="form1"> -->
                            <div class="col-md-12">
                              <div class="col-md-6">       
                                <div class="form-group">
                                  <label>Unit Nomor</label>
                                  <input type="text" class="form-control" name="unit_nomor" value="{{ $unit->name }}" {{ $readonly }}>
                                </div>

                                <div class="form-group">
                                  <label>PT</label>
                                  <select class="form-control" name="pt_id" id="pt_id" >
                                  </select>
                                </div>

                                <div class="form-group">
                                  <label>Unit Type</label>
                                  <select class="form-control" name="unit_type" id="unit_type" >
                                    <option value="">Pilih Type</option>
                                  </select>
                                </div>

                                <div class="form-group">
                                  <label>Tipe</label>
                                  <input type="text" class="form-control" name="" id="" value="">
                                </div>

                                <div class="form-group">
                                  <label>Luas Tanah(m2)</label>
                                  <input type="text" class="form-control" name="" id="" value="" >
                                </div>
                                
                                <div class="form-group">
                                  <label>Luas Bangunan(m2)</label>
                                  <input type="text" class="form-control" name="" id="" value="" >
                                </div>

                                <div class="form-group">
                                  <label>Luas Tanah Tambahan(m2)</label>
                                  <input type="text" class="form-control" name="" id="" value="0" >
                                </div>
                                <!-- /.form-group -->
                              </div>

                              <div class="col-md-6">
                                <div class="form-group">
                                  <label>Arah Bangunan </label>
                                  <select class="form-control" name="unit_arah_id" id="unit_arah_id">
                                    <option value='1' >Utara</option>
                                    <option value='2' >Timur Laut</option>
                                    <option value='3' >Timur</option>
                                    <option value='4' >Tenggara</option>
                                    <option value='5' >Selatan</option>
                                    <option value='6' >Barat Daya</option>
                                    <option value='7' >Barat</option>
                                    <option value='8' >Barat Laut</option>
                                  </select>
                                </div>

                                <div class="form-group">
                                  <label>Hadap Bangunan</label>
                                  <select class='form-control' name='unit_position' id='unit_position'>

                                  </select>
                                </div>

                                <div class="form-group">
                                  <label>Product Kategori</label>
                                  <select class='form-control select2' name='tag_kategori' id='tag_kategori' >
                                    <option value='B'>Bangunan</option>
                                    <option value='K'>Kavling</option>
                                  </select>
                                </div>

                                <div class="form-group">
                                  <label>Status Sellable</label>
                                  <select class='form-control' name='is_sellable' id='is_sellable'>
                                    <option value='1' >Ya</option>
                                    <option value='0' >Tidak</option>
                                  </select>
                                </div>

                                <div class="form-group">
                                  <label>Purpose</label>
                                  <select class='form-control' name='purpose' id='purpose' placeholder="pilih purpose">
                                  <option value="" selected>pilih Purpose</option>

                                  </select>
                                </div>
                                
                                <div class="form-group">
                                  <label>Keterangan</label>
                                  <textarea class='form-control' name="description" id="description" cols="45" rows="5" placeholder="Descriptions" ></textarea>
                                </div>    
                              </div>
                            </div>         
                      </div>
                      <!-- Modal Footer -->
                      <div class="modal-footer">
                          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                      </div>

                  </div>
              </div>
            </div>
              <h3><center>History Unit</center></h3>
            	<table class="table table-bordered">
            		<thead class="head_table">
            			<tr>
            				<td>No.</td>
            				<td>Status Respon</td>
            				<td>Description</td>
                    <td>Tanggal</td>
            			</tr>
            		</thead>
            		<tbody>
            			@foreach ( $history as $key => $value )
                  <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $value['status_reason']}}</td>
                    <td>{{ $value['description']}}</td>
                    <td>{{ $value['tanggal']}}</td>
                  </tr>
                  @endforeach
            		</tbody>
            	</table>
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
<script type="text/javascript">

  $( document ).ready(function() {
      $.ajaxSetup({
          headers: {
              'X-CSRF-Token': $('input[name=_token]').val()
          }
        });
    });



  $(function () {
    $("#luas").number(true);
  });

  $("#unit_type").change(function(){
    var request = $.ajax({
        url : "{{ url('/')}}/project/getluas",
        data : {
          id : $("#unit_type").val()
        },
        type : "post",
        dataType : "json"
    });

    request.done(function(data){
      $("#kategori").val(data.kategori);
      $("#luas_tanah").val(data.luas_tanah);
      $("#luas_bangunan").val(data.luas_bangunan);
    });
  });

  $("#tag_kategori").change(function(){
    if ( $("#tag_kategori").val() == "K"){
      $("#luas_bangunan_kavling").show();
      $("#luas_bangunan").val(0);
      $("#luas_bangunan").hide();
    }else{
      $("#luas_bangunan_kavling").hide();
      $("#luas_bangunan").show();
    }
  });

  function updatestatus(unit_id,status,pending_id){
    if ( confirm("Apakah anda yakin ingin mengupdate data ini ? ")){
      var request = $.ajax({
        url : "{{url('/')}}/project/updatepending",
        dataType : "json",
        data : {
          unit_id : unit_id,
          status : status,
          pending_id : pending_id
        },
        type : "post"
      });

      request.done(function(data){
        if ( data.status == "0"){
          alert("Data telah diupdate");
        }

        window.location.reload();
        
      })
    }else{
      return false;
    }
  }

//   $(".js-example-placeholder-single").select2({
//     placeholder: "Pilih ",
//     allowClear: true
// });

</script>
@include("pt::app")
</body>
</html>
