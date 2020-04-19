<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Admin QS | Dashboard</title>
  @include("master/header")
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  @if ($user->level == "superadmin")
    @include("master/sidebar")
  @else
    @include("master/sidebar_project")
  @endif

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>Data Item Pekerjaan</h1>

    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            
            <!-- /.box-header -->
            <div class="box-body table-responsive">

              
              <div class="col-md-12">
                  <div class="panel-body">
                    <label>Info</label>
                  </div>
                  <ul class="list-group">
                    <li class="list-group-item">Project : <strong>{{$project_pt->project->name}}</strong></li>
                    <li class="list-group-item">Pt : <strong>{{$project_pt->pt->name}}</strong></li>
                    <input type="hidden" name="projectpt" value="{{$project_pt->id}}" id="projectpt">
                  </ul>
                </div>

              <div class="col-md-12">
                <button type="" class="btn btn-primary" id="edit" style="margin:10px 10px 10px 10px"> Edit</button>
                <input type="hidden" name="all_send" id="all_send" />
                <button type="" class="btn btn-primary" id="save" style="margin:10px 10px 10px 10px" hidden> Simpan</button>
                <table id="table_coa" class="table table-bordered table-hover">
                  <thead>
                  <tr style="background-color: greenyellow;">
                    <th style="width:30%">Coa Cpms</th>
                    <th style="width:35%">Coa Finance</th>
                    <th style="width:20%">Department</th>
                    <th style="width:20%">peruntukan</th>
                    <!-- <th>aksi</th> -->
                  </tr>
                  </thead>
                  <tbody>
                    @for($i=0 ; $i < count($coa_relasi) ; $i++)
                    <tr>
                      <td>
                        {{$coa_relasi[$i]['coa_cpms']}}
                        <input type="hidden" name="" class="coa_cpms_id" value="{{$coa_relasi[$i]['coa_cpms_id']}}">
                      </td>
                      <td>
                        @if($coa_relasi[$i]['coa_finance'] == null)
                        <select class="form-control select coa_gl_table" name="coa_gl_table" id="" disabled style="width:100%">
                          <option value="0">pilih coa finance</option>
                            @for($j=0; $j < count($coa_gl) ; $j++)
                              <option value="{{ $coa_gl[$j]->coa_id}}">{{ $coa_gl[$j]->coa }} | {{ $coa_gl[$j]->name }}</option>
                            @endfor
                          </select>
                        @else
                          <select class="form-control select coa_gl_table" name="coa_gl_table" id="" disabled style="width:100%">
                            <option value="0" >pilih coa finance</option>
                            @for($j=0; $j < count($coa_gl) ; $j++)
                              @if($coa_relasi[$i]['coa_finance_id'] == $coa_gl[$j]->coa_id)
                                <option value="{{ $coa_gl[$j]->coa_id}}" selected>{{ $coa_gl[$j]->coa }} | {{ $coa_gl[$j]->name }}</option>
                              @else
                              <option value="{{ $coa_gl[$j]->coa_id}}">{{ $coa_gl[$j]->coa }} | {{ $coa_gl[$j]->name }}</option>
                              @endif
                            @endfor
                          </select>
                        @endif
                      </td>
                      <td>
                        {{$coa_relasi[$i]['department']}}
                        <input type="hidden" name="" class="department_id" value="{{$coa_relasi[$i]['department_id']}}">
                      </td>
                      <td>
                        {{$coa_relasi[$i]['peruntukan']}}
                        <input type="hidden" name="" class="peruntukan_id" value="{{$coa_relasi[$i]['peruntukan_id']}}">
                      </td>
                      <!-- <td></td> -->
                    </tr>
                    @endfor
                </table>
              </div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">

  </footer>

  
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

@include("master/footer_table")
@include("pt::app")
@include('form.general_form')
<script src="{{ URL::asset('assets/global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
<script type="text/javascript">
  $.ajaxSetup({
      headers: {
          'X-CSRF-Token': $('meta[name=csrf-token]').attr('content')
      }
  });
  $('.select').select2({
  });

  $(document).ready(function() {
    $('#table_coa').DataTable( {
        "order": [[ 0, "asc" ]],
        "paging":false
    } );
  } );
  $(document).ready(function() {
    $("#save").hide();
    $(document).on('click', '#edit', function() {
      $(".coa_gl_table").prop("disabled", false);
      $(this).hide();
      $("#save").show();
    });
  } );

  $(document).ready(function() {
    $('#save').click(function() {
        var _data = [];
        $('#table_coa > tbody > tr').each(function(i, v) {
            var _objdata = {
                'coa_cpms_id': $(this).find('.coa_cpms_id').val(),
                'coa_finance_id': $(this).find('.coa_gl_table').val(),
                'department_id': $(this).find('.department_id').val(),
                'peruntukan_id': $(this).find('.peruntukan_id').val(),
            };

            _data.push(_objdata);
        });
        $('#all_send').val(JSON.stringify(_data));
        console.log(JSON.stringify(_data));
    });

    $('#save').click(function(){
        var _url = '{{ url("/")}}/pekerjaan/coa/save_masal';
        var data = JSON.parse($('#all_send').val());
        var projectpt = $('#projectpt').val();
        if(data==''){
            alert('Harap Mengisi data');
        }else{
            $.ajax({
                type : "POST",
                url  : _url,
                dataType : "JSON",
                data :{
                data:data,
                projectpt:projectpt 
                },
                  beforeSend: function() {
                  waitingDialog.show();
                },
                success : function(data){
                    // alert(data.success);
                    window.location.replace('{{ url("/")}}/pekerjaan/coa/detail/?id='+projectpt);
                }     
            });
        }
    })
  });
</script>

</body>
</html>
