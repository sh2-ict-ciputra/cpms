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
                <h3>Report by Pekerjaan </h3>
              <div class="tab-pane table-responsive" id="tab_2">
                  <table id="index" class="table table-bordered bg-white mg-b-0 tx-center" style="font-size:15px; width: 100%; ">
                    <thead class="head_table">
                      <tr style="border: 1px solid black;">
                        <tr>
                          <!-- <td colspan="" style="vertical-align: middle;"></td> -->
                          <!-- <td rowspan="3" style="vertical-align: middle;">Tgl SPK</td> -->
                          <!-- <td rowspan="" style="vertical-align: middle;"> </td> -->
                          <td rowspan="" style="vertical-align: middle;"></td>
                          <td colspan="1" style="vertical-align: middle;">Pekerjaan</td>
                          
                          <!-- <td rowspan="3" style="vertical-align: middle;">Tgl SPK</td> -->
                          <!-- <td colspan="1" style="vertical-align: middle;">Acuan SPK</td> -->
                         <!--  <td rowspan="3" style="vertical-align: middle;">Pekerjaan</td>
                          <td rowspan="3" style="vertical-align: middle;">Rekanan</td>
                          <td rowspan="3" style="vertical-align: middle;">Nilai SPK</td>
                          <td rowspan="3" style="vertical-align: middle;">Nilai VO</td>
                          <td rowspan="3" style="vertical-align: middle;">Total Kontrak</td>
                          <td rowspan="3" style="vertical-align: middle;">Total Termin</td>
                          <td rowspan="3" style="vertical-align: middle;">Sisa Kontrak</td>
                          <td rowspan="3" style="vertical-align: middle;">Tgl ST1</td>
                          <td rowspan="3" style="vertical-align: middle;">Tgl ST2</td> -->
                        </tr>
                        <!-- <tr>
                          <td></td>
                          <td></td>
                           <td rowspan="" style="vertical-align: middle;">Tgl SPK</td>
                          <td rowspan="" style="vertical-align: middle;">Pekerjaan</td>
                          <td rowspan="" style="vertical-align: middle;">Rekanan</td>
                          <td rowspan="" style="vertical-align: middle;">Nilai SPK</td>
                          <td rowspan="" style="vertical-align: middle;">Nilai VO</td>
                          <td rowspan="" style="vertical-align: middle;">Total Kontrak</td>
                          <td rowspan="" style="vertical-align: middle;">Total Termin</td>
                          <td rowspan="" style="vertical-align: middle;">Sisa Kontrak</td>
                          <td rowspan="" style="vertical-align: middle;">Tgl ST1</td>
                          <td rowspan="" style="vertical-align: middle;">Tgl ST2</td>
                        </tr>
 -->                        <!-- <tr>
                          <td colspan="" style="vertical-align: middle;">Proyek/Kawasan</td> 
                        </tr>
                        <tr>
                          <td colspan="" style="vertical-align: middle;">No.SPK</td> 
                        </tr>  -->  
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

    $('#index').DataTable({
      // 'paging'      : false,
          // 'lengthChange': false,
        //   language: {
        //   searchPlaceholder: 'Search...',
        //   sSearch: '',
        //   lengthMenu: '_MENU_',
        //   loadingRecords: '&nbsp;',
        //   "loadingRecords": "Please wait - loading...",
        //   "processing": '<div class="spinner-grow" role="status">'+
        //                   '<span class="sr-only">Loading...</span>'+
        //                 '</div>'
        // },
          // 'searching'   : false,
          // "ordering": false,
          // loadingRecords: '&nbsp;',
          // 'info'        : true,
          // 'autoWidth'   : false,
            // "serverSide": true,
            // "pageLength": 50,
            paging:false,
            "processing": false,
            "ajax":{
                     "url": "{{ url('/report/reportpekerjaan') }}",
                     "dataType": "json"
                     // "type": "get",
                     // "data":{ id_project: id_project}
                   },
                   
            "columns": [
                { "data": "reknspk"},
                { "data": "acuan" },
                // { "data": "tgl_spk" },
                // { "data": "pekerjaan" },
                // { "data": "rekanan" },                
                // { "data": "nilai_spk" },
                // { "data": "nilai_vo" },
                // { "data": "spknvo" },
                // { "data": "totmin" },
                // { "data": "sisakontrak" },
                // { "data": "tgl_st1" },
                // { "data": "tgl_st2" }, 

            ],
            "columnDefs": [
            { "visible": false, "targets": [0] }
          ],
          "order": [[ 0, 'asc' ]],
          "drawCallback": function ( settings ) {
            var api = this.api();
            var rows = api.rows( {page:'current'} ).nodes();
            var last=null;
            var last2=null;
            var last3=null;
 
            api.column(0, {page:'current'} ).data().each( function ( group, i ) {
              // api.column(1, {page:'current'} ).data().each( function ( group2, i ) {
                  console.log(group);
                  if ( last !== group ) {
                        $(rows).eq( i ).before(
                            '<tr class="group" style="background-color: white;""><td colspan=1"><strong>'+group+'</strong></td></tr>'
                        );
                        // last2 = group2;
                    last = group;
                  }
                // } );
            } );
        },
    "initComplete": function(settings, json) {
                $('.group').nextUntil('.group').css( "display", "none" );
              },
      })

      var tbody = $('#index tbody');
      tbody.on('click','.group',function()
      {
        $(this).nextUntil('.group').toggle();

      });  
    });

    


    // });


//     function hapus(no){
//     data_ipk.splice(no,1);
//     tampil();
// }
       
</script>
</body>
</html>
