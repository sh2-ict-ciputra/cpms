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
      <h1>Report</h1>

    </section>

    <!-- Main content -->
    <section class="content">
      {{ csrf_field() }}
      <div class="box box-default">
        <!-- /.box-header -->
        <div class="box-body">
            <div class="row">
               <!-- div class="col-md-12 center">             
              </div> -->
              <div class="col-md-12">
                <h3>HPP ConCost (Summary Unit)</h3>
              <div class="tab-pane table-responsive" id="tab_2">
                  <table id="index" class="table table-bordered bg-white mg-b-0 tx-center" style="font-size:15px; width: 100%; ">
                    <thead class="head_table">
                      <tr style="border: 1px solid black;">
                        <tr>
                          <!-- <td colspan="1"></td> -->
                          <td colspan="" style="vertical-align: middle;">Type Bangunan</td>
                          <td rowspan="4" style="vertical-align: middle;">Luas Bangunan</td>
                          <td rowspan="4" style="vertical-align: middle;">RAB / Budget</td>
                          <td colspan="3" style="text-align: center;">Kontrak</td>
                          <td rowspan="4"  style="vertical-align: middle;">Prog Lap</td>
                          <td rowspan="3" style="vertical-align: middle;">Prog BAP</td>
                          <td rowspan="4" style="vertical-align: middle;">BAP Terbayar</td>
                          <td colspan="3" style="text-align: center;">HPP / M2</td>
                        </tr>
                        <tr>
                          <!-- <td colspan="" style="vertical-align: middle;">Nama Rekanan</td> -->
                          <td colspan="" style="vertical-align: middle;">Kawasan</td>
                          <td rowspan="3" style="vertical-align: middle;">SPK</td>
                          <td rowspan="3" style="vertical-align: middle;">VO</td>
                          <td rowspan="3" style="vertical-align: middle;">Total</td>
                          <td rowspan="3" style="vertical-align: middle;">Budget</td>
                          <td rowspan="3" style="vertical-align: middle;">Kontrak</td>
                          <td rowspan="3" style="vertical-align: middle;">Terbayar</td>      
                        </tr>
                        <tr>
                          <td colspan="" style="vertical-align: middle;">Nomor Unit</td>
                        </tr>
                        <!-- <tr>
                          <td colspan="" style="vertical-align: middle;">Nomor SPK</td>
                        </tr> -->
                      </tr>
                </thead>
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

    // $('#index').DataTable({
    //   // 'paging'      : false,
    //       // 'lengthChange': false,
    //       language: {
    //       searchPlaceholder: 'Search...',
    //       sSearch: '',
    //       lengthMenu: '_MENU_',
    //       loadingRecords: '&nbsp;',
    //       "loadingRecords": "Please wait - loading...",
    //       "processing": '<div class="spinner-grow" role="status">'+
    //                       '<span class="sr-only">Loading...</span>'+
    //                     '</div>'
    //     },
    //       'searching'   : false,
    //       "ordering": false,
    //       // loadingRecords: '&nbsp;',
    //       // 'info'        : true,
    //       'autoWidth'   : false,
    //         "serverSide": true,
    //         processing: false,
    //         "ajax":{
    //                  "url": "{{ url('/report/reportkontraktor') }}",
    //                  "dataType": "json"
    //                  // "type": "get",
    //                  // "data":{ id_project: id_project}
    //                },
                   
    //         "columns": [
    //             { "data": "reknspk" },
    //             { "data": "tgl_spk" },
    //             { "data": "acuan" },
    //             { "data": "pekerjaan" },
    //             { "data": "prokaw" },                
    //             { "data": "nilai_spk" },
    //             { "data": "nilai_vo" },
    //             { "data": "spknvo" },
    //             { "data": "totmin" },
    //             { "data": "sisakontrak" },
    //             { "data": "tgl_st1" },
    //             { "data": "tgl_st2" },
    //         ],
            
    // });


    });


//     function hapus(no){
//     data_ipk.splice(no,1);
//     tampil();
// }
       
</script>
</body>
</html>
