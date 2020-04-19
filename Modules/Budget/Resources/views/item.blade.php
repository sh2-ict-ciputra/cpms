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
      <h1>Data Proyek <strong>{{ $budget->project->name }}</strong></h1>

    </section>

    <!-- Main content -->
    <section class="content">

      <!-- SELECT2 EXAMPLE -->
      <div class="box box-default">

        <!-- /.box-header -->
        <div class="box-body">
          <div class="row">
            <div class="col-md-12"><h3 class="box-title">Detail Data Budget Proyek</h3></div>
            <div class="col-md-6">             
              <div class="form-group">
                <label>Item Pekerjaan</label>
                <select class="form-control select2" name="coa_id" id="coa_id">
                  <option>( pilih item pekerjaan)</option>
                  @foreach ( $itempekerjaan as $key => $value )  
                    @if ( $value->group_cost == 2 )                  
                      <option value="{{ $value->id }}" selected>{{ $value->code }}  {{ $value->name }}</option>      
                    @endif                
                  @endforeach
                </select>
              </div>
              <div class="form-group">
                <i class="fa fa-refresh ld ld-spin" id="loading" style="display: none;"></i>                
                <button class="btn btn-info submitbtn" onClick="formsubmit();" id="btn_submit">Simpan</button>
                <a class="btn btn-warning submitbtn" href="{{ url('/')}}/budget/detail?id={{ $budget->id }}">Kembali</a>
              </div>
            </div>
   
            <!-- /.col -->
            <div class="col-md-12">
              <form action="{{ url('/')}}/budget/item-saverevisi" method="post" name="form1" id="form1">  
                {{ csrf_field() }}
                <input type="hidden" name="budget_id" id="budget_id" value="{{ $budget->id }}">
                <table class="table">
                  <thead class="head_table">
                    <tr>
                      <td>COA</td>
                      <td>Item Pekerjaan</td>
                      <td>Volume</td>
                      <td>Satuan</td>
                      <td>Nilai</td>
                    </tr>
                  </thead>
                  <tbody id="itemlist"></tbody>
                </table>
              </form>
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
@include("budget::app")
<!-- Select2 -->
<script src="{{ url('/')}}/assets/bower_components/select2/dist/js/select2.full.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript">
  $(function () {
    $("#luas").number(true);
    $("#luas_brutto").number(true);
    $("#luas_netto").number(true);
    $('#start_date').datepicker({
      "dateFormat" : "yy-mm-dd"
    });

     $('#end_date').datepicker({
      "dateFormat" : "yy-mm-dd"
    });

    $(".nilai_budget").number(true);
    $('.select2').select2();
  });

  function setkawasan(){
    if ( $("#iskawasan").is(":checked")){
      $("#kawasan").show();
    }else{
      $("#kawasan").hide();
    }
  }

  $("#coa_id").change(function(){
    $("#itemlist").html("");
    var request = $.ajax({
        url : "{{ url('/')}}/budget/item-detail",
        dataType : "json",
        data : {
          id : $("#coa_id").val()
        },
        type : "post"
    });

    request.done(function(data){
        if ( data.status == "0"){
          $("#itemlist").html(data.html);
          $(".nilai_budget").number(true);
        }else{
          alert("Tidak ada detail Item Pekerjaan");
        }
    });
  });

  function formsubmit(){
    $("#form1").submit();
    $(".submitbtn").hide();
    $("#loading").show();
  }

  
</script>
</body>
</html>
