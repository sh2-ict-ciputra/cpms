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
                <h3>Report by Project/Kawasan</h3>
              <div class="tab-pane table-responsive" id="tab_2">
                  <table id="index" class="table table-bordered bg-white mg-b-0 tx-center" style="font-size:15px; width: 100%; ">
                    <thead class="head_table">
                      <tr style="border: 1px solid black;">
                        <tr>
                          <!-- <td rowspan="3"></td> -->
                          <td colspan="" style="vertical-align: middle;">Proyek Kawasan</td>
                          <!-- <td rowspan="3" style="vertical-align: middle;">Tgl SPK</td>
                          <td rowspan="3" style="vertical-align: middle;">Acuan SPK</td>
                          <td rowspan="3" style="vertical-align: middle;">Pekerjaan</td>
                          <td rowspan="3" style="vertical-align: middle;">Rekanan</td> -->
                          <td rowspan="3" style="vertical-align: middle;">Nilai SPK</td>
                          <td rowspan="3" style="vertical-align: middle;">Nilai VO</td>
                          <td rowspan="3" style="vertical-align: middle;">Nilai Percepatan</td>
                          <td rowspan="3" style="vertical-align: middle;">Total Kontrak</td>
                          <td rowspan="3" style="vertical-align: middle;">Total Termin</td>
                          <td rowspan="3" style="vertical-align: middle;">Sisa Kontrak</td>
                          <td rowspan="3" id="aksi" style="vertical-align: middle;">Aksi</td>
                          <!-- <td rowspan="3" style="vertical-align: middle;">Tgl ST2</td> -->
                        </tr>
                        <tr>
                          <td colspan="" style="vertical-align: middle;">Pekerjaan</td> 
                        </tr>
                        <tr>
                          <td colspan="" style="vertical-align: middle;">No.SPK</td> 
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
                <input type="vertical" class="form-control" name="start_date" id="start_date" autocomplete="off" required>
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

  <!-- MODAL EDIT -->
        <div class="modal fade" id="ModalaEdit" tabindex="-1" role="dialog" aria-labelledby="largeModal" aria-hidden="true">
            <div style="width: 1200px" class="modal-dialog modal-lg">
            <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 class="modal-title" id="myModalLabel"> <span style="color: grey " id="namekaw"></span></h3>
            </div>
            <form class="form-horizontal" >
                <div class="modal-body">
                    <div class="tab-pane table-responsive" id="tab_2">
                  <table id="index_detail" class="table table-bordered bg-white mg-b-0 tx-center" style="font-size:15px; width: 100%; ">
                    <thead class="head_table">
                      <tr style="border: 1px solid black;">
                        <tr>
                          <!-- <td rowspan="3"></td> -->
                          <td colspan="12" style="vertical-align: middle;">Pekerjaan </td>
                          <!-- <td rowspan="3" style="vertical-align: middle;">Tgl ST2</td> -->
                        </tr>
                        <tr>
                          <td></td>
                          <td rowspan="" style="vertical-align: middle;">No SPK</td>
                          <td rowspan="" style="vertical-align: middle;">Tgl SPK</td>
                          <td rowspan="" style="vertical-align: middle;">Rekanan</td>
                          <td rowspan="" style="vertical-align: middle;">Nilai SPK</td>
                          <td rowspan="" style="vertical-align: middle;">Nilai VO</td>
                          <td rowspan="" style="vertical-align: middle;">Nilai Percepatan</td>
                          <td rowspan="" style="vertical-align: middle;">Total Kontrak</td>
                          <td rowspan="" style="vertical-align: middle;">Total Termin</td>
                          <td rowspan="" style="vertical-align: middle;">Sisa Kontrak</td>
                          <td rowspan="" style="vertical-align: middle;">Tgl ST-1</td>
                          <td rowspan="" style="vertical-align: middle;">Tgl ST-2</td>
                        </tr>
                      </tr>
                </thead>
                </table>
                </div>
                    
                    
                </div>
 
                <div class="modal-footer">
                    <!-- <button class="btn" data-dismiss="modal" aria-hidden="true">Tutup</button>
                    <button class="btn btn-info" id="btn_update">Update</button> -->
                </div>
            </form>
            </div>
            </div>
        </div>
        <!--END MODAL EDIT-->
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

    let table = $('#index').DataTable({
      // 'paging'      : false,
          // 'lengthChange': false,
          language: {
          searchPlaceholder: 'Search...',
          sSearch: '',
          lengthMenu: '_MENU_',
          loadingRecords: '&nbsp;',
          "loadingRecords": "Please wait - loading...",
          "processing": '<div class="spinner-grow" role="status">'+
                          '<span class="sr-only">Loading...</span>'+
                        '</div>'
        },
          'searching'   : false,
          "ordering": false,
          // loadingRecords: '&nbsp;',
          // 'info'        : true,
          'autoWidth'   : false,
            "serverSide": true,
            processing: false,
            "ajax":{
                     "url": "{{ url('/report/reportkawasan') }}",
                     "dataType": "json"
                     // "type": "get",
                     // "data":{ id_project: id_project}
                   },
                   
            "columns": [               
                { "data": "kawasan" },
                { "data": "nilai_spk" },
                { "data": "nilai_vo" },
                { "data": "percepatan" },
                { "data": "total_kontrak" },
                { "data": "total_termyn" },                
                { "data": "sisa_kontrak" },
                { "data": "aksi" },

            ],
            "columnDefs": [
            { "targets": [1,2,3,4,5,6], render: function(data,type,row){return '<td style="width:180px;">Rp. '+ numberWithCommas(data)+'</td>'}},
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
            total = api
                .column( 1 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Total over this page
            totalSpk = api
                .column( 1, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            
            totalVo = api
                .column( 2, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

            totalPercepatan = api
                .column( 3, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

            totalKontrak = api
                .column( 4, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

            totalTermyn = api
                .column( 5, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

            sisa = api
                .column( 6, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

            // Update footer
            $( api.column( 1 ).footer() ).html('Rp. '+numberWithCommas(totalSpk));
            $( api.column( 2 ).footer() ).html('Rp. '+numberWithCommas(totalVo));
            $( api.column( 3 ).footer() ).html('Rp. '+numberWithCommas(totalPercepatan));
            $( api.column( 4 ).footer() ).html('Rp. '+numberWithCommas(totalKontrak));
            $( api.column( 5 ).footer() ).html('Rp. '+numberWithCommas(totalTermyn));
            $( api.column( 6 ).footer() ).html('Rp. '+numberWithCommas(sisa));
        },
    });

    var tbody = $('#index_detail tbody');
      tbody.on('click','.group',function()
      {
        $(this).nextUntil('.group').toggle();

      });

    });

    function pekerjaan(id_item,name){
      // alert(id);
      $('#index_detail').DataTable().clear().draw();
     var tablePekerjaan =  $('#index_detail').DataTable({
        "serverSide": true,
        "paging":false,
        "ordering":false,
          "processing": false,
          "destroy": true,
         "ajax":{
                     "url": "{{ url('/')}}/report/detailpek",
                     "dataType": "json",
                     "type": "post",
                     "data":{ id_item: id_item}
                   },
          "columns":[
                  {data:"pekerjaan",name:"pekerjaan"},
                  {data:"nospk",name:"nospk"},
                  {data:"tgl_spk",name:"tgl_spk"},
                  {data:"rekanan",name:"rekanan"},
                  {data:"nilai_spk",name:"nilai_spk"},
                  {data:"nilai_vo",name:"nilai_vo"},
                  {data:"nilai_percepatan",name:"nilai_percepatan"},
                  {data:"nilai_kontrak",name:"nilai_kontrak"},
                  {data:"termyn",name:"termyn"},
                  {data:"sisa",name:"sisa"},
                  {data:"st1",name:"st1"},
                  {data:"st2",name:"st2"},
                  
                  ],
          "columnDefs": [
            { "visible": false, "targets": [0] },
            {"targets": [4,5,6,7,8,9], render: function(data,type, row){return '<td>Rp. '+ numberWithCommas(data) +'</td>' }}
          ],
          "order": [[ 0, 'asc' ]],
          "drawCallback": function ( settings ) {
            var api = this.api();
            var rows = api.rows( {page:'current'} ).nodes();
            var last=null;
            var last2=null;
            var last3=null;
 
            api.column(0, {page:'current'} ).data().each( function ( group, i ) {
              var totspk = api.rows().data().filter( function ( d ) {
                    return d.pekerjaan == group;
                  } ).pluck( 'nilai_spk' );
                var totalspk = totspk.reduce((a, b) => parseInt(a) + parseInt(b), 0);

                var totvo = api.rows().data().filter( function ( d ) {
                    return d.pekerjaan == group;
                  } ).pluck( 'nilai_vo' );
                var totalvo = totvo.reduce((a, b) => parseInt(a) + parseInt(b), 0);

                var percepatan = api.rows().data().filter( function ( d ) {
                    return d.pekerjaan == group;
                  } ).pluck( 'nilai_percepatan' );
                var nilai_per = percepatan.reduce((a, b) => parseInt(a) + parseInt(b), 0);

                var totmyn = api.rows().data().filter( function ( d ) {
                    return d.pekerjaan == group;
                  } ).pluck( 'termyn' );
                var totaltermyn = totmyn.reduce((a, b) => parseInt(a) + parseInt(b), 0);

                var total_kontrak = totalspk + totalvo + nilai_per;

                var sisa = total_kontrak - totaltermyn;
                // console.log(group);
                if ( last !== group ) {
                      $(rows).eq( i ).before(
                          '<tr class="group" style="background-color: white;""><td colspan=3"><strong>'+group+'</strong></td><td colspan=1" style="width:180px;"><strong>Rp. '+numberWithCommas(totalspk)+'</strong></td><td colspan=1" style="width:180px;"><strong>Rp. '+numberWithCommas(totalvo)+'</strong></td><td colspan=1" style="width:180px;"><strong>Rp. '+numberWithCommas(nilai_per)+'</strong></td><td colspan=1" style="width:180px;"><strong>Rp. '+numberWithCommas(total_kontrak)+'</strong></td><td colspan=1" style="width:180px;"><strong>Rp. '+numberWithCommas(totaltermyn)+'</strong></td><td colspan=1" style="width:180px;"><strong>Rp. '+numberWithCommas(sisa)+'</strong></td></tr>'
                      );
                  last = group;
                }
            } );
        },
    "initComplete": function(settings, json) {
                $('.group').nextUntil('.group').css( "display", "none" );
              },
      })

      var display = false;
      $('#index_detail tbody').on('click','.group',function()
      {
        if(display==false){
          $(this).nextUntil('.group').toggle(display=true);
        }
        else{
          $(this).nextUntil('.group').toggle(display=false);
        }
        // $(this).nextUntil('.group').animate({ opacity: "toggle" });
        // $(this).nextUntil('.group').toggle();
      });
      $('#namekaw').text(name);

      $('#ModalaEdit').modal('show');
      
    }

    function numberWithCommas(x) {
      return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

       
</script>
</body>
</html>
