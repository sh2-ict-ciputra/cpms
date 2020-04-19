
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Admin QS | Dashboard </title>
  @include("master/header")
  <!-- Select2 -->  
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="{{ url('/')}}/assets/bower_components/select2/dist/css/select2.min.css">
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  @include("master/sidebar_project")

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>Data Workorder <strong>{{ $workorder_pekerjaan->workorder->name }}</strong></h1>

    </section>

    <!-- Main content -->
    <section class="content">

      <!-- SELECT2 EXAMPLE -->
      <div class="box box-default">

        <!-- /.box-header -->
        <div class="box-body">
          <div class="row">
           <!--  <div class="col-md-12">
              <h3 class="box-title">Detail Dokumen</h3>
              <form action="{{ url('/')}}/workorder/save-document" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <input type="hidden" name="workorder_budget_id" value="{{ $workorder_pekerjaan->id }}">
                <div class="form-group">
                  <div class="col-xs-4">
                    <div class="form-group">
                      <label>Nama Dokumen</label>
                      <select class="form-control" name="document_name">                      
                        <option value="Gambar Tender">Gambar Tender</option>
                        <option value="BQ / Bill Item">BQ / Bill Item</option>
                        <option value="Spesifikasi Teknis">Spesifikasi Teknis</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <input type="file" name="upload" required><i><strong>(file yang diupload hanya bertype *.doc, *.docx, *.xls, *.xlsx, *.jpg, *.jpeg, *.png, *.pdf, dan autocad)</strong></i>
                    </div>                    
                    <input type="hidden" name="workorder_unit_id" value="{{ $workorder_pekerjaan->id }}">
                    <div class="form-group">                  
                      <a class="btn btn-warning" href="{{ url('/')}}/workorder/detail/?id={{ $workorder_pekerjaan->workorder->id }}">Kembali</a> -->
                      <!-- @if ( $workorder_pekerjaan->workorder->approval != "")
                        @if ( $workorder_pekerjaan->workorder->approval->approval_action_id == 7 )
                          <button type="submit" class="btn btn-primary">Save changes</button>
                        @endif
                      @else
                      <button type="submit" class="btn btn-primary">Save changes</button>
                      @endif -->
                   <!--    <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                  </div>
                </div>
              </form>
            </div> -->
   
            <!-- /.col -->
            <div class="col-md-12">
              <a href="{{ url('/')}}/workorder/detail/?id={{ $idw }}" class="btn btn-warning">
                <span class="glyphicon glyphicon-menu-left"></span>Kembali 
              </a>
              <h3 class="box-title">Detail Dokumen</h3>
                <i><strong>(file yang diupload hanya bertype *.doc, *.docx, *.xls, *.xlsx, *.jpg, *.jpeg, *.png, *.pdf, dan autocad)</strong></i>
                  <table id="example2" class="table-bordered table table-responsive">
                    <thead class="head_table">
                      <tr>
                        <td>Nama Dokumen</td>
                        <td>File</td>
                        <td>Action</td>
                      </tr>
                    </thead>
                    <tbody id="table_item">
                    <!--  	@foreach ( $workorder_pekerjaan->dokumen as $key => $value )
                     	<tr>
                     		<td>{{ $value->document_name }}</td>
                     		<td><a class="btn btn-info" href="{{url('/')}}/workorder/downloaddoc?id={{$value->id}}" data-url="{{$value->filenames}}">Download</a></td>
                     		<td>
                          @if ( $workorder_pekerjaan->workorder->approval != "")
                            @if ( $workorder_pekerjaan->workorder->approval->approval_action_id == 7)
                              <button class="btn btn-danger" onClick="removeDokumen('{{ $value->id }}')">Delete</button>
                            @endif
                          @else
                            <button class="btn btn-danger" onClick="removeDokumen('{{ $value->id }}')">Delete</button>
                          @endif
                        </td>
                     	</tr>
                     	@endforeach -->

                      <tr>
                        <td>Gambar Tender</td>
                        <td>
                          <div class="form-group">
                            <form action="" id="upload_tender" method="post" enctype="multipart/form-data">
                             {{ csrf_field() }}
                              <input type="hidden" name="workorder_budget_id" value="{{ $workorder_pekerjaan->id }}">
                              <input type="hidden" name="workorder_unit_id" value="{{ $workorder_pekerjaan->id }}">
                              <input type="hidden" name="name_doc" value="Gambar Tender">
                              
                              @php 
                              $workorder = Modules\Workorder\Entities\WorkorderBudgetDetail::find($workorder_pekerjaan->id);

                              $gambar = Modules\Tender\Entities\TenderDocument::where('workorder_budget_id',$workorder->id)->where('document_name','Gambar Tender')->get(); @endphp
                            
                              @if(count($gambar)>0)
                                @foreach($gambar as $key)                                
                                <a class="btn btn-info" href="{{url('/')}}/workorder/downloaddoc?id={{$key->id}}" data-url="{{$value->filenames}}">Download </a>
                                @endforeach
                              @elseif(count($gambar)<=0)
                                 <input type="file" name="gambar_tender" id="gambar_tender" required>
                              @endif
                              
                            </form>
                          </div>  
                        </td>
                        <td>
                          @php 
                              $workorder = Modules\Workorder\Entities\WorkorderBudgetDetail::find($workorder_pekerjaan->id);

                              $gambar = Modules\Tender\Entities\TenderDocument::where('workorder_budget_id',$workorder->id)->where('document_name','Gambar Tender')->get(); @endphp
                          
                              @if(count($gambar)>0)
                                @foreach($gambar as $key)                                
                                 <button class="btn btn-danger" onClick="removeDokumen('{{ $key->id }}')">Delete</button>
                                @endforeach
                              @elseif(count($gambar)<=0)
                                 <button type="button" id="btn_gambar" class="btn btn-primary">Upload</button>
                              @endif
                          
                        </td>
                      </tr>
                      <tr>
                        <td>BQ / Bill Item</td>
                        <td>
                           <form action="" id="upload_bq" method="post" enctype="multipart/form-data">
                             {{ csrf_field() }}
                              <input type="hidden" name="workorder_budget_id" value="{{ $workorder_pekerjaan->id }}">
                              <input type="hidden" name="workorder_unit_id" value="{{ $workorder_pekerjaan->id }}">
                              <input type="hidden" name="name_doc" value="BQ / Bill Item">
                              
                              @php 
                              $workorder = Modules\Workorder\Entities\WorkorderBudgetDetail::find($workorder_pekerjaan->id);

                              $gambar = Modules\Tender\Entities\TenderDocument::where('workorder_budget_id',$workorder->id)->where('document_name','BQ / Bill Item')->get(); @endphp
                              
                              @if(count($gambar)>0)  
                                @foreach($gambar as $key)                            
                                <a class="btn btn-info" href="{{url('/')}}/workorder/downloaddoc?id={{$key->id}}" data-url="{{$value->filenames}}">Download </a>
                                @endforeach
                              @elseif(count($gambar)<=0)
                                 <input type="file" name="gambar_bq" id="gambar_bq" required>
                              @endif
                              
                            </form>
                        </td>
                        <td>
                          @php 
                              $workorder = Modules\Workorder\Entities\WorkorderBudgetDetail::find($workorder_pekerjaan->id);

                              $gambar = Modules\Tender\Entities\TenderDocument::where('workorder_budget_id',$workorder->id)->where('document_name','BQ / Bill Item')->get(); @endphp
                          
                              @if(count($gambar)>0)   
                                @foreach($gambar as $key)                             
                                 <button class="btn btn-danger" onClick="removeDokumen('{{ $key->id }}')">Delete</button>
                                @endforeach
                              @elseif(count($gambar)<=0)
                                 <button type="button" id="btn_bq" class="btn btn-primary">Upload</button>
                              @endif
                        </td>
                      </tr>
                      <tr>
                        <td>Spesifikasi Teknis</td>
                        <td><form action="" id="upload_spesifikasi" method="post" enctype="multipart/form-data">
                             {{ csrf_field() }}
                              <input type="hidden" name="workorder_budget_id" value="{{ $workorder_pekerjaan->id }}">
                              <input type="hidden" name="workorder_unit_id" value="{{ $workorder_pekerjaan->id }}">
                              <input type="hidden" name="name_doc" value="Spesifikasi Teknis">
                              
                              @php 
                              $workorder = Modules\Workorder\Entities\WorkorderBudgetDetail::find($workorder_pekerjaan->id);

                              $gambar = Modules\Tender\Entities\TenderDocument::where('workorder_budget_id',$workorder->id)->where('document_name','Spesifikasi Teknis')->get(); @endphp
                          
                              @if(count($gambar)>0) 
                                @foreach($gambar as $key1)                               
                                <a class="btn btn-info" href="{{url('/')}}/workorder/downloaddoc?id={{$key1->id}}" data-url="{{$value->filenames}}">Download </a>
                                @endforeach
                              @elseif(count($gambar)<=0)
                                 <input type="file" name="gambar_spesifikasi" id="gambar_spesifikasi" required>
                              @endif
                              
                            </form>
                          </td>
                        <td>
                          @php 
                              $workorder = Modules\Workorder\Entities\WorkorderBudgetDetail::find($workorder_pekerjaan->id);

                              $gambar = Modules\Tender\Entities\TenderDocument::where('workorder_budget_id',$workorder->id)->where('document_name','Spesifikasi Teknis')->get(); @endphp
                          
                              @if(count($gambar)>0)         
                                @foreach($gambar as $key)                       
                                 <button class="btn btn-danger" onClick="removeDokumen('{{ $key->id }}')">Delete</button>
                                @endforeach
                              @elseif(count($gambar)<=0)
                                 <button type="button" id="btn_spesifikasi" class="btn btn-primary">Upload</button>
                              @endif
                        </td>
                      </tr>
                    </tbody>
                  </table>
                  <!-- <center>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                  </center> -->
               
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
@include("workorder::app")
<!-- Select2 -->
<script type="text/javascript">
  function disablebtn(id){
    var valor = [];
    $('input.disable_unit[type=checkbox]').each(function () {
        if (this.checked)
          valor.push($(this).val());
    });

    console.log(valor.length);

    if (valor.length < 1 ) {
      $("#btn_submit").attr("disabled","disabled");
    }else{
      $("#btn_submit").removeAttr("disabled");
    }
  }

  $(document).ready( function () {
    $('#example1').DataTable({
      
    });
  });

  $('#btn_gambar').click(function(){
    var data = $('#gambar_tender').val();
    if(data == ''){
      alert('File Belum Terpilih');
    }else{
      $(this).hide();
      $.ajax({
        url:"{{ url('/')}}/workorder/save-document",
        data:new FormData($("#upload_tender")[0]),
        dataType:'json',
        async:false,
        type:'post',
        processData: false,
        contentType: false,
        beforeSend: function() {
          waitingDialog.show();
        },
        success:function(response){
          alert(response.status);
          location.reload();
        },
      });
    }
  })

  $('#btn_bq').click(function(){
    var data = $('#gambar_bq').val();
    if(data == ''){
      alert('File Belum Terpilih');
    }else{
      $(this).hide();
      $.ajax({
        url:"{{ url('/')}}/workorder/save-document",
        data:new FormData($("#upload_bq")[0]),
        dataType:'json',
        async:false,
        type:'post',
        processData: false,
        contentType: false,
        beforeSend: function() {
          waitingDialog.show();
        },
        success:function(response){
          alert(response.status);
          location.reload();
        },
      });
    }
  })

  $('#btn_spesifikasi').click(function(){
    var data = $('#gambar_spesifikasi').val();
    if(data == ''){
      alert('File Belum Terpilih');
    }else{
      $(this).hide();
      $.ajax({
        url:"{{ url('/')}}/workorder/save-document",
        data:new FormData($("#upload_spesifikasi")[0]),
        dataType:'json',
        async:false,
        type:'post',
        processData: false,
        contentType: false,
        beforeSend: function() {
          waitingDialog.show();
        },
        success:function(response){
          alert(response.status);
          location.reload();
        },
      });
    }
  })
</script>
</body>
</html>
