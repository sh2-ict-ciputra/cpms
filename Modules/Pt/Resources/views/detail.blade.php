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

  @include("master/sidebar")

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>Data PT <strong>{{ $pt->name }}</strong></h1>

    </section>

    <!-- Main content -->
    <section class="content">

      <!-- SELECT2 EXAMPLE -->
      <div class="box box-default">

        <!-- /.box-header -->
        <div class="box-body">
          <div class="row">
            <div class="col-md-6">              
              <h3 class="box-title">Edit Data PT</h3>
              <form action="{{ url('/')}}/pt/update-pt" method="post" name="form1">
                <input type="hidden" class="form-control" name="ptid" value="{{ $pt->id }}">
                {{ csrf_field() }}
              <div class="form-group">
                <label>Nama</label>
                <input type="text" class="form-control" name="name" value="{{ $pt->name }}">
              </div>
              <div class="form-group">
                <label>Kode PT</label>
                <input type="text" class="form-control" name="code" value="{{ $pt->code }}">
              </div>
              <div class="form-group">
                <label>Alamat / Kode Pos</label>
                <textarea class="form-control" name="alamat" rows="3" >{{ $pt->address }}</textarea>
              </div>
              <div class="form-group">
                <label>Kota</label>
                <select class="form-control select2 " name="kota" id="kota">
                  @foreach($city as $key => $value )
                  @if ( $pt->city_id == $value->id )
                    <option value="{{ $value->id }}" selected>{{ $value->name}}</option>
                  @else
                    <option value="{{ $value->id }}">{{ $value->name}}</option>
                  @endif
                  @endforeach
                </select>
              </div>
              <div class="form-group">
                <label>Telepon</label>
                <input type="text" class="form-control" name="telepon" value="{{ $pt->phone }}">
              </div>
              <div class="form-group">
                <label>NPWP</label>
                @if ( $pt->npwp == "" )
                <input type="text" name="npwp" class="form-control" data-inputmask='"mask":"99.999.999.9-999.999"' data-mask>
                @else
                <input type="text" name="npwp" class="form-control" value="{{ $pt->npwp }}">
                @endif
              </div>
              <div class="form-group">
                <label>Description</label>
                <textarea class="form-control" name="keterangan" rows="3">{{ $pt->description }}</textarea>
              </div>              
              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ url('/')}}/pt" class="btn btn-info">Kembali</a>
              </div>
              </form>
              <!-- /.form-group -->
            </div>
            <!-- /.col -->
            <div class="col-md-12">
              <div class="nav-tabs-custom">
                
                <ul class="nav nav-tabs">
                  <li class="active"><a href="#tab_1" data-toggle="tab">Rekening Bank Pembayaran</a></li>
                  <li><a href="#tab_2" data-toggle="tab">Proyek</a></li>
                  <li><a href="#tab_3" data-toggle="tab">Department PT</a></li>
                  <!--li><a href="#tab_3" data-toggle="tab">Departemen</a></li-->
                </ul>
                <div class="tab-content">
                  <div class="tab-pane active" id="tab_1">
                      <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-default">
                      Tambah Rekening
                      </button><br>
                      <!-- nav-tabs-custom -->
                      <div class="col-md-12">
                          <table id="example3" class="table table-bordered table-hover">
                            <thead>
                            <tr style="background-color: greenyellow;">
                              <th>Bank </th>
                              <th>Nomor Rekening</th>
                              <th>Perubahan Data</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ( $pt->rekenings as $key => $value )
                            <tr>
                              <td>
                                <span class="labels" id="label_{{ $value->id}}">{{ $value->bank->name }}</span>
                                <select class="form-control" name="bank" id="bank_{{ $value->id }}" style="display: none">
                                  @foreach( $bank as $key3 => $value3)
                                  @if ( $value3->id == $value->bank_id )
                                  <option value="{{ $value3->id}}" selected>{{ $value3->name }}</option>
                                  @else
                                  <option value="{{ $value3->id}}">{{ $value3->name }}</option>
                                  @endif                                  
                                  @endforeach
                                </select>                       
                              </td>  
                              <td>
                                <span class="labels" id="label_rek_{{ $value->id}}">{{ $value->rekening }}</span>
                                <input type="text" id="rek_bang_{{ $value->id }}" style="display: none;" value="{{ $value->rekening}}" data-attribute="{{ $value->id }}" class="form-control col-xs-4">
                              </td>                 
                              <td>
                                <button class="btn btn-warning" id="btn_status_{{ $value->id }}" onclick="showedit('{{ $value->id}}');">Ubah</button>
                                <button class="btn btn-success" id="btn_save_{{ $value->id }}" onclick="saveEdit('{{ $value->id}}','{{ $value->name}}');" style="display: none;">Ubah</button>
                                <button href="#" onclick="deleteRek('{{ $value->id}}')" class="btn btn-danger">Hapus</button>
                              </td>
                            </tr>
                            @endforeach
                          </table>
                      </div>
                    <br><br>
                  </div>
                  <!-- /.tab-pane -->
                  <div class="tab-pane" id="tab_2">
                      <!-- /.form-group -->
                      <h2 class="page-header">Proyek</h2>
                      <button type="button" class="btn btn-warning btn-md project"  data-target="#editModalProject" style="" id="project">Tambah Project</button>
                      <table class="table">
                        <tr class="head_table">
                          <td>Proyek</td>
                          <td>Hapus</td>
                        </tr>
                        @foreach ( $pt->project as $key3 => $value3 )
                          @if ($value3->project != null)
                            <tr>
                              <td>{{ $value3->project->name }}</td>
                              <td><button onclick="removeproject('{{ $value3->id }}')" class="btn btn-danger">Hapus</button></td>
                            </tr>
                          @endif
                        @endforeach
                      </table>
                  </div>
                  <!-- /.tab-pane -->
                  <div class="tab-pane" id="tab_3">
                    <form action="{{ url('/')}}/pt/add-mapping" method="post" name="form1">
                      {{ csrf_field() }}
                      <input type="hidden" class="form-control" name="pt_mapping" value="{{ $pt->id }}">
                      <div class="form-group">
                          <label for="exampleInputEmail1">Department</label>
                          <select class="form-control" name="departmen_mapping">
                            @foreach ( $department as $key2 => $value2 )
                            <option value="{{ $value2->id }}">{{ $value2->name}}</option>
                            @endforeach
                          </select>
                      </div>
                      <div class="form-group">
                          <label for="exampleInputEmail1">Divisi</label>
                          <select class="form-control" name="divisiion_mapping">
                            @foreach ( $divisi as $key2 => $value2 )
                            <option value="{{ $value2->id }}">{{ $value2->name}}</option>
                            @endforeach
                          </select>
                      </div>
                      <div class="box-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                      </div>
                      </form>
                      <table class="table">
                        <tr class="head_table">
                          <td>Departemen</td>
                          <td>Divisi</td>
                          <td>Hapus</td>
                        </tr>
                        @foreach ( $pt->mapping as $key4 => $value4 )
                        <tr>
                          <td>{{ $value4->department->name }}</td>
                          <td>{{ $value4->division->name }}</td>
                          <td><button onclick="removemap('{{ $value4->id }}')" class="btn btn-danger">Hapus</button></td>
                        </tr>
                        @endforeach
                      </table>
                  </div>
                </div>
                <!-- /.tab-content -->
              </div>
            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->
        </div>
        <!-- /.box-body -->

      </div>
      <!-- /.box -->
      <div class="modal fade" id="editModalProject" role="dialog">
        <div class="modal-dialog modal-lg modal-md" style="width:50%;">
            <!-- Modal content-->
            <div class="modal-content center">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><span id="label_ba"></span></h4>
                    {{-- Berita Acara Tunjuk Langsung --}}
                </div>
                <div class="modal-body" id="">
                    <div id="list_item" class="col-md-12">
                        <div class="form-group col-md-12 panel panel-info">
                            <div id="form_tambah_kategori" class="form-group col-md-12" style="margin-bottom:10px">
      
                                <label class="control-label col-md-12" style="padding-left:0">Project</label>
                                <select class="form-control select2" name="divisiion_mapping" style="width:100%" id="project_id">
                                  @foreach ( $project as $key2 => $value2 )
                                  <option value="{{ $value2->id }}">{{ $value2->name}}</option>
                                  @endforeach
                                </select>
      
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                    <input type='' id='id_korespondensi' value='' hidden/>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                        <button type="button" class="btn btn-primary simpan_project"> Simpan</button>
                    </div>
                </div>
            </div>
        </div>
      </div>

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
  <div class="modal fade" id="modal-default">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Tambah Rekening</h4>
        </div>
        <div class="modal-body">
          <form action="{{ url('/')}}/pt/add-rekening" method="post" name="form1">
            {{ csrf_field() }}
            <input type="hidden" name="pt_id" id="pt_id" value="{{ $pt->id }}">
            <div class="form-group">
                <label>Bank</label>
                <select class="form-control select2" name="bank">
                  @foreach( $bank as $key2 => $value2)
                  <option value="{{ $value2->id}}">{{ $value2->name }}</option>
                  @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Nomor Rekening</label>
                <input type="text" class="form-control" name="rekening">
            </div>  
            <div class="modal-footer">         
              <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
          </form>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
</div>
<!-- ./wrapper -->

@include("master/footer_table")
<!-- Select2 -->
<script src="{{ url('/')}}/assets/bower_components/select2/dist/js/select2.full.min.js"></script>
<script src="{{ url('/')}}/assets/plugins/input-mask/jquery.inputmask.js"></script>
<script src="{{ url('/')}}/assets/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="{{ url('/')}}/assets/plugins/input-mask/jquery.inputmask.extensions.js"></script>
<script type="text/javascript">
  $(function () {
    $('.select2').select2();
    $('[data-mask]').inputmask();
  });

  function removeproject(id){
    if ( confirm("Apakah anda yakin ingin menghapus data proyek ini ? ")){
      var request = $.ajax({
        url : "{{ url('/')}}/pt/delete-project",
        data : { 
          id : id
        },
        type : "post",
        dataType : "json"
      });

      request.done(function(data){
        if ( data.status == "0" ){
          alert("Data telah dihapus");
        }
        window.location.reload();
      })
    }else{
      return false;
    }
  }

  function removemap(id){
    if ( confirm("Apakah anda yakin ingin menghapus data department ini ? ")){
      var request = $.ajax({
        url : "{{ url('/')}}/pt/delete-mapping",
        dataType : "json",
        data : {
          id : id
        },
        type : "post"
      });

      request.done(function(data){
        if ( data.status == "0"){
          alert("Data telah dihapus");
          window.location.reload();
        }
      })
    }else{
      return false;
    }
  }

  $(document).on('click', '.project', function() {
    $('#editModalProject').modal('show');
  });

  $(document).on('click', '.simpan_project', function() {
    var _url = "{{ url('/pt/tambah-project') }}";

    var pt_id = $('#pt_id').val();
    var project_id = $("#project_id").val();
    $.ajax({
        type: 'post',
        dataType: 'json',
        url: _url,
        data: {
          pt_id : pt_id,
          project_id : project_id,
        },
        beforeSend: function() {
            waitingDialog.show();
        },
        success: function(data) {
          window.location.reload();
        },
        complete: function() {
            waitingDialog.hide();
        }
    });
  });
</script>
@include("pt::app")
</body>
</html>
