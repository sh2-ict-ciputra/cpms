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


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>{{ $project->name }}</h1>

    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">

            <!-- /.box-header -->
            <div class="box-body">
              <div class="col-md-12">
                <strong>Tambah Permintaan Barang</strong>
                <hr/>
          <form action="{{ url('/inventory/permintaan_barang/create') }}" method="post" class="form-horizontal form-label-left" id="form_data">
            {{ csrf_field() }}
            <div class="col-lg-6 col-md-6 col-xs-12">
              <div class="item form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12">PT</label>
                  <div class="col-md-7 col-sm-7 col-xs-12">
                    <div class="input-group">
                      <select class='form-control select2' name='pt_id' id='pt_id'>
                      @foreach($pts as $key => $value)
                        <option value='{{ $value->pt->id }}'>({{ $value->pt->code }}) - {{ $value->pt->name }}</option>
                      @endforeach
                    </select>
                    <div class="input-group-addon"><a href="{{ url('/pt') }}"><i class="fa fa-plus"></i></a></div>
                  </div>
                  </div>
                </div>

                <div class="item form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12">Departemen</label>
                  <div class="col-md-7 col-sm-7 col-xs-12" id="addpt">
                    <div class="input-group">
                      <select class='form-control select2' name='department_id' id='department_id'>
                      @foreach($departments as $key => $value)
                        <option value='{{ $value->id }}'>({{ $value->code }}) - {{ $value->name }}</option>
                      @endforeach
                    </select>
                    <div class="input-group-addon"><a href="{{ url('/department') }}"><i class="fa fa-plus"></i></a></div>
                  </div>
                  </div>
                </div>

            <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">No. SPK</label>
              <div class="col-md-7 col-sm-7 col-xs-12">
                <select class='form-control select2' name='spk_id' id='spk_id'>
                  <option value="">Pilih SPK</option>
                  @foreach($spks as $key => $value)
                    <option value='{{ $value->id }}'>{{ $value->no }}</option>
                  @endforeach
                </select>
              </div>
            </div>

            </div>

            <div class="col-lg-6 col-md-6 col-xs-12">
              <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">Status Permintaan</label>
                <div class="col-md-7 col-sm-7 col-xs-12">
                  <select class='form-control select2' name='status' id='status'>
                    <option value="">Pilih</option>
                    @foreach($statusPermintaans as $key => $value)
                      <option value="{{ $value->id }}">{{ $value->name }}</option>
                    @endforeach
                  </select>
                </div>
                  </div>

                            <div id="divPilihPR" class="item form-group" hidden>
                                <label class="col-md-3 control-label">Purchase request</label>
                                <div class="col-sm-7">
                                    @if($flag == 1)
                                        <select id="" class="form-control select2" name="pilih_pr" required>
                                            <option value="{{$purchaserequest->id}}" selected="">{{$purchaserequest->no}}</option>
                                        </select>
                                    @else
                                        <select id="pilih_pr" class="form-control select2" name="pilih_pr" required>
                                            <option value="" disabled="" selected="">Pilih PR</option>
                                        </select>
                                    @endif
                                </div>
                            </div>

                <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Tanggal</label>
                <div class="col-md-7 col-sm-7 col-xs-12">
                  <div class="input-group input-medium date date-picker datePicker_" >
                    <input type="text" class="form-control" name='date' id='date' value="<?php echo date('Y-m-d'); ?>">
                    <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                  </div>  
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Deskripsi</label>
                <div class="col-md-7 col-sm-7 col-xs-12">
                  <textarea class='form-control' name="description" id="description" cols="45" rows="5" placeholder="Description"></textarea>
                </div>
              </div>

            </div>
            <div class="ln_solid"></div>
            <div class="form-group">
              <div class="col-md-6 col-md-offset-3">
                <button id="send" type="submit" class="btn btn-success"><i class="fa fa-save"></i> Simpan</button>
                <button id="reset" type="reset" class="btn btn-warning"><i class="fa fa-times"></i> Reset</button>
                @include('form.a',[
                  'href'=>url('/inventory/permintaan_barang/index'), 
                  'caption' => 'Batal' 
                ])
              </div>
            </div>
          </form> 
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
@include('pluggins.select2_pluggin')
@include('pluggins.alertify')
@include('form.general_form')
@include('pluggins.datetimepicker_pluggin')
<script type="text/javascript">
    $.ajaxSetup({
    headers: {
                  'X-CSRF-Token': $('meta[name=csrf-token]').attr('content')
                }
    });

  $(document).ready(function()
  {
    $('.select2').select2();
    $('#form_data').submit(function(e)
    {
      e.preventDefault();
      var _datasend = $(this).serialize();
      var _url = $(this).attr('action');
      $('#form_data input').attr("disabled", "disabled");
      
              $.ajax({
                  type: 'POST',
                  url: _url,
                  data: _datasend,
                  dataType: 'json',
                  beforeSend:function(){
                    waitingDialog.show();
                  },
                  success:function(data){
                    if(data.return=='1')
                    {
                      alertify.success('success saved!',2);
                      window.location.href = "{{ url('/inventory/permintaan_barang/index') }}";
                    }
                    else
                    {
                      alertify.error('Warning : Mohon periksa kembali data-data anda');
                    }
                  },
                  error:function(xhr,status,errormessage)
                  {
                    alertify.error(xhr.reponseText);
                  },
                  complete:function()
                  {
                    waitingDialog.hide();
                    $('#form_data input').removeAttr('disabled');
                      $('.form-group').removeClass('has-success');
                  }
              });
    });

        $('#status').change(function(){
          var url = "{{ url('/')}}/inventory/permintaan_barang/getPurchaseRequest";
          var item = $("#pilih_pr");
          var send = $(this).val();
          var department = $("#department_id").val();
            if(send == 10){
                $.ajax({
                  type:'post',
                  dataType:'json',
                  url:url,
                  data:{department_id : department},
                  beforeSend:function()
                  {
                    waitingDialog.show();
                  },
                  success:function(data)
                  {
                      var strHtml='';
                      strHtml +='<option value="">Pilih PR</option>';
                      if(data.result.length > 0)
                      {
                          alertify.success(data.result.length+ ' PR ditemukan');
                          
                          for(var i=0;i<data.result.length;i++)
                          {
                              strHtml+='<option value="'+data.result[i].id+'" >'+data.result[i].pr_no+'</option>';
                          }
                      }
                      //
                      item.find('option').remove();
                      item.append(strHtml);
                  },
                  complete:function()
                  {
                      waitingDialog.hide();
                  }
                });
                $('#divPilihPR').show();
            }else{
                $('#divPilihPR').hide();
            }      
        });

        $('#department_id').change(function(){
          var url = "{{ url('/')}}/inventory/permintaan_barang/getPurchaseRequest";
          var item = $("#pilih_pr");
          var department = $(this).val();
          var send = $("#status").val();
            if(send == 10){
                $.ajax({
                  type:'post',
                  dataType:'json',
                  url:url,
                  data:{department_id : department},
                  beforeSend:function()
                  {
                    waitingDialog.show();
                  },
                  success:function(data)
                  {
                      var strHtml='';
                      strHtml +='<option value="">Pilih PR</option>';
                      if(data.result.length > 0)
                      {
                          alertify.success(data.result.length+ ' PR ditemukan');
                          
                          for(var i=0;i<data.result.length;i++)
                          {
                              strHtml+='<option value="'+data.result[i].id+'" >'+data.result[i].pr_no+'</option>';
                          }
                      }
                      //
                      item.find('option').remove();
                      item.append(strHtml);
                  },
                  complete:function()
                  {
                      waitingDialog.hide();
                  }
                });
                $('#divPilihPR').show();
            }else{
                $('#divPilihPR').hide();
            }      
        });
  });
</script>
</body>
</html>