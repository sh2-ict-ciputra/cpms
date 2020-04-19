
<!DOCTYPE html>
<html>
@include('user.header')
<style type="text/css">
  #example3 th,
    #example3 td {
        white-space: nowrap;
    }
   
</style>
{{ csrf_field() }}
<body class="hold-transition sidebar-mini">
<div class="wrapper">
 
  <!-- /.navbar -->
  @include('user.sidebar')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Project</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Data Tables</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->    
    <input type="hidden" name="approval_list" id="approval_list">
    <section class="content">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Data Approval</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <a href="#" onclick="showsearch();" class="btn btn-success">Search</a>
              <button onclick="submitapprove();" class="btn btn-info">Submit</button><br/>
              <table class="table table-bordered table-striped searchbox" style="display: none;">
                <tr>
                  <td style="background-color: grey;"><span style="color:white"><strong>Tanggal</strong></span></td>
                  <td></td>
                </tr>
                <tr>
                  <td style="background-color: grey;"><span style="color:white"><strong>Proyek</strong></span></td>
                  <td>
                    <select name="search_proyek" id="search_proyek" class="form-control">
                      <option value="">(choose)</option>
                      @foreach ( $project as $key => $value )
                      <option value="{{ $value->id }}">{{ $value->name }}</option>
                      @endforeach
                    </select>
                  </td>
                </tr>
                <tr>
                  <td style="background-color: grey;"><span style="color:white"><strong>Nilai</strong></span></td>
                  <td>
                    <input type="radio" name="clause" id="more" checked> >= <br>
                    <input type="radio" name="clause" id="less"> <=
                    <input type="text" name="nominal" id="nominal" class="form-control">
                  </td>
                </tr>
                <tr>
                  <td style="background-color: grey;"><span style="color:white"><strong>Department</strong></span></td>
                  <td>
                    <select name="search_department" id="search_department" class="form-control">
                      @foreach ( $department as $key => $value )
                      <option value="{{ $value->id }}">{{ $value->name }}</option>
                      @endforeach
                    </select>
                  </td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                  <td><button onclick="search();" class="btn btn-success">Cari</button></td>
                </tr>
              </table><br>  
              <table class="table table-bordered" id="example3">
                <thead>
                    <tr class="header_1">
                      <th class="approve">Yes</th>
                      <th class="reject">No</th>                      
                      <th>Jenis Dokumen</th>
                      <th>Tanggal</th>
                      <th>Nomor Dokumen</th>
                      <th>Perihal Pekerjaan</th>
                      <th>Nilai(Rp)</th>
                      <th>Proyek</th>
                      <th>Kawasan</th>
                      <th>Department</th>
                      <th>Detail</th>
                    </tr>
                </thead>
                <tbody style="background-color: white;">
 
                   <!--  -->

                </tbody>
                
              </table><br>        
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <div class="float-right d-none d-sm-block">
      <b>Version</b> 3.0.0-alpha
    </div>
    <strong>Copyright &copy; 2014-2018 <a href="http://adminlte.io">AdminLTE.io</a>.</strong> All rights
    reserved.
  </footer>

</div>
<!-- ./wrapper -->
@include('user.footer')
<script type="text/javascript" src="https://cdn.datatables.net/fixedcolumns/3.2.4/js/dataTables.fixedColumns.min.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/fixedcolumns/3.0.2/css/dataTables.fixedColumns.css">
<script type="text/javascript">
  $(document).ready(function() {
    $('#example3').DataTable( {
        scrollY:        300,
        scrollX:        true,
        scrollCollapse: true,
        paging:         false,
        fixedColumns:   {
          leftColumns: 3,
        }
    } );

    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('input[name=_token]').val()
        }
    });
   
  });

  function checkapprove(status,doc_id){
      var list = $("#approval_list").val();
      if ( status == "R" ){
        var replace = list.replace("<>A," + doc_id, "");
        $("#approval_list").val(replace + "<>" +  status + "," + doc_id);
      }else{
        var replace = list.replace("<>R" + "," +  doc_id, "");
        $("#approval_list").val(replace + "<>" +  status + "," + doc_id);
      }
  }

  function submitapprove(){
      var request = $.ajax({
        url : "{{ url('/')}}/access/approval/all",
        dataType : "json",
        data : {
          approval_list : $("#approval_list").val(),
          token : $('input[name=_token]').val()
        },
        type : "post"
      });

      request.done(function(data){
        window.location.reload();
      })
  }
</script>
</body>
</html>
