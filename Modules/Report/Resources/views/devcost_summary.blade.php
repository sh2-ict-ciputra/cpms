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
                <h3>HPP Development Cost (Summary)</h3>
              <div class="tab-pane table-responsive" id="tab_2">
                  <table id="index" class="table table-bordered bg-white mg-b-0 tx-center" style="font-size:15px; width: 100%; ">
                    <thead class="head_table">
                      <tr style="border: 1px solid black;">
                        <tr>
                          <!-- <td colspan="1"></td> -->
                          <td rowspan="2" style="vertical-align: middle;">Kawasan</td>
                          <td rowspan="2" style="vertical-align: middle;">Efisiensi</td>
                          <td colspan="2" style="vertical-align: middle;">Luasan</td>
                          <td rowspan="2" style="vertical-align: middle;">Total Budget</td>
                          <td colspan="2" style="vertical-align: middle;">HPP Budget</td>
                          <td rowspan="2" style="vertical-align: middle;">Total Kontrak</td>
                          <td colspan="2" style="vertical-align: middle;">HPP Kontrak</td>
                          <td rowspan="2" style="vertical-align: middle;">Total Terbayar</td>
                          <td colspan="2" style="vertical-align: middle;">HPP Realisasi</td>
                        </tr>
                        <tr>
                          <td colspan="" style="vertical-align: middle;">Netto (m2)</td>
                          <td colspan="" style="vertical-align: middle;">Brutto (m2)</td>
                          <td colspan="" style="vertical-align: middle;">Netto</td>
                          <td colspan="" style="vertical-align: middle;">Brutto</td>
                          <td colspan="" style="vertical-align: middle;">Netto</td>
                          <td colspan="" style="vertical-align: middle;">Brutto</td>
                          <td colspan="" style="vertical-align: middle;">Netto</td>
                          <td colspan="" style="vertical-align: middle;">Brutto</td>
                        </tr>
                      </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th colspan="" style="text-align:left">Total:</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                </tfoot>
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
      'paging'      : false,
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
          'searching'   : false,
          "ordering": false,
          // loadingRecords: '&nbsp;',
          // 'info'        : true,
          'autoWidth'   : false,
            "serverSide": true,
            processing: false,
            "ajax":{
                     "url": "{{ url('/report/devcostsummary') }}",
                     "dataType": "json"
                     // "type": "get",
                     // "data":{ id_project: id_project}
                   },
                   
            "columns": [
                { "data": "kawasan" },
                { "data": "efesiensi" },
                { "data": "netto" },
                { "data": "brutto" },
                { "data": "total_budget" },                
                { "data": "budget_netto" },
                { "data": "budget_brutto" },
                { "data": "kontrak_total" },
                { "data": "kontrak_netto" },
                { "data": "kontrak_brutto" },
                { "data": "total_termyn" },
                { "data": "realisasi_netto" },
                { "data": "realisasi_brutto"}
            ],
            "columnDefs":[
            {"targets": [7], render: function(data,type,row){return '<td style="width:350px;">Rp. '+numberWithCommas(data)+'</td>'}},
            {"targets":[8,9,10,11,12], render: function(data,type,row){return '<td>Rp. '+numberWithCommas(Math.round(data))+'</td>'}},
            {"targets":[2,3], render: function(data,type,row){return '<td>'+numberWithCommas(Math.round(data))+'</td>'}}
            ],

            "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;
 
            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
 
            // Total over all pages
            // total = api
            //     .column( 3 )
            //     .data()
            //     .reduce( function (a, b) {
            //         return intVal(a) + intVal(b);
            //     }, 0 );
 
            // Total over this page
            luasanNetto = api
                .column( 2 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

            luasanBrutto = api
                .column( 3 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

            totalKontrak = api
                .column( 7 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

            kontrakNetto = api
                .column( 8 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

            kontrakBrutto = api
                .column( 9 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

            totalTerbayar = api
                .column( 10 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

            realisasiNetto = api
                .column( 11 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

            realisasiBrutto = api
                .column( 12 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Update footer
            $( api.column( 2 ).footer() ).html(numberWithCommas(luasanNetto));
            $( api.column( 3 ).footer() ).html(numberWithCommas(luasanBrutto));
            // $( api.column( 5 ).footer() ).html();
            // $( api.column( 6 ).footer() ).html();
            $( api.column( 7 ).footer() ).html('Rp. '+numberWithCommas(totalKontrak));
            $( api.column( 8 ).footer() ).html('Rp. '+numberWithCommas(Math.round(kontrakNetto)));
            $( api.column( 9 ).footer() ).html('Rp. '+numberWithCommas(Math.round(kontrakBrutto)));
            $( api.column( 10 ).footer() ).html('Rp. '+numberWithCommas(totalTerbayar));
            $( api.column( 11 ).footer() ).html('Rp. '+numberWithCommas(Math.round(realisasiNetto)));
            $( api.column( 12 ).footer() ).html('Rp. '+numberWithCommas(Math.round(realisasiBrutto)));

        }
            
    });


    });

  function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
  }


//     function hapus(no){
//     data_ipk.splice(no,1);
//     tampil();
// }
       
</script>
</body>
</html>
