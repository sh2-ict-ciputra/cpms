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
                <h3>HPP ConCost (Detail Kontrak)</h3>
              <div class="tab-pane table-responsive" id="tab_2">
                  <table id="index" class="table table-bordered bg-white mg-b-0 tx-center" style="font-size:15px; width: 100%; ">
                    <thead class="head_table">
                      <tr style="border: 1px solid black;">
                        <tr>
                          <!-- <td colspan="">Kawasan</td> -->
                          <td colspan="" style="vertical-align: middle;">Type Bangunan</td>
                          <!-- <td rowspan="4" style="vertical-align: middle;">Tgl SPK</td>
                          <td rowspan="4" style="vertical-align: middle;">Acuan SPK</td> -->
                          <td rowspan="" style="vertical-align: middle;">Kontrak</td>
                          <td rowspan=""  style="vertical-align: middle;">RAB / Budget</td>
                          <!-- <td colspan="3" style="text-align: center;">Kontrak</td> -->
                          <td rowspan="" style="vertical-align: middle;">Prog Lab(%)</td>
                          <td rowspan="" style="vertical-align: middle;">Prog BAP (%)</td>
                          <td rowspan="" style="vertical-align: middle;">BAP / Terbayar</td>
                          <td rowspan="" style="vertical-align: middle;">Detail</td>

                          <!-- <td colspan="3" style="text-align: center;">HPP / M2</td> -->
                        </tr>
                        <!-- <tr>
                          <td colspan="" style="vertical-align: middle;">Nama Rekanan</td>
                          <td colspan="" style="vertical-align: middle;">Kawasan</td>
                          <td rowspan="3" style="vertical-align: middle;">SPK</td>
                          <td rowspan="3" style="vertical-align: middle;">VO</td>
                          <td rowspan="3" style="vertical-align: middle;">Total</td>
                          <td rowspan="3" style="vertical-align: middle;">Budget</td>
                          <td rowspan="3" style="vertical-align: middle;">Kontrak</td>
                          <td rowspan="3" style="vertical-align: middle;">Terbayar</td>      
                        </tr> -->
                        <!-- <tr>
                          <td colspan="" style="vertical-align: middle;">Nomor Unit</td>
                        </tr>
                        <tr>
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
                          <!-- <td></td> -->
                          <td colspan="11" style="vertical-align: middle;">Proyek Kawasan</td>
                          <!-- <td></td> -->
                          <!-- <td colspan="" style="vertical-align: middle;">Nilai SPK</td>
                          <td rowspan="" style="vertical-align: middle;">Nilai VO</td>
                          <td rowspan="" style="vertical-align: middle;">Total Kontrak</td>
                          <td rowspan="" style="vertical-align: middle;">Total Termyn</td> -->
                          <!-- <td colspan="3" style="vertical-align: middle;">Sisa Kontrak</td> -->
                        </tr>
                        <tr>
                          <!-- <td></td> -->
                          <td rowspan="" style="vertical-align: middle;">No. SPK</td>
                          <!-- <td></td> -->
                          <td rowspan="" style="vertical-align: middle;">Tgl SPK</td>
                          <!-- <td rowspan="" style="vertical-align: middle;">Acuan SPK</td> -->
                          <td rowspan="" style="vertical-align: middle;">Pekerjaan</td>
                          <!-- <td></td> -->
                          <td rowspan="" style="vertical-align: middle;">Nilai SPK</td>
                          <td rowspan="" style="vertical-align: middle;">Nilai VO</td>
                          <td rowspan="" style="vertical-align: middle;">Total Kontrak</td>
                          <td rowspan="" style="vertical-align: middle;">Total Termyn</td>
                          <td rowspan="" style="vertical-align: middle;">Sisa Kontrak</td>
                          <td rowspan="" style="vertical-align: middle;">Tgl ST1</td>
                          <td rowspan="" style="vertical-align: middle;">Tgl ST2</td>
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
                     "url": "{{ url('/report/concostdetail') }}",
                     "dataType": "json"
                     // "type": "get",
                     // "data":{ id_project: id_project}
                   },
                   
            "columns": [
                // { "data": "kawasan"},
                { "data": "type" },
                { "data": "kontrak" },
                { "data": "rab" },
                { "data": "prog_lap" },
                { "data": "prog_bap" },                
                { "data": "terbayar_tot" },
                { "data": "aksi" },
                // { "data": "spknvo" },
                // { "data": "totmin" },
                // { "data": "sisakontrak" },
                // { "data": "tgl_st1" },
                // { "data": "tgl_st2" },
            ],
            "columnDefs":[
            {"targets": [1], render: function(data,type,row){return '<td style="width:250px;">Rp. '+numberWithCommas(data)+'</td>'}},
            ],
          "drawCallback": function ( settings ) {
            var api = this.api();
            var rows = api.rows( {page:'current'} ).nodes();
            var last=null;
            var last2=null;
            var last3=null;
 
            api.column(0, {page:'current'} ).data().each( function ( group, i ) {
                // console.log(group);

                if ( last !== group ) {
                  var totKontrak = api.rows().data().filter( function ( d ) {
                      return d.type == group;
                    } )
                    .pluck( 'kontrak' );

                  var proglap = api.rows().data().filter( function ( d ) {
                      return d.type == group;
                    } )
                    .pluck( 'prog_lap' );

                  var progbap = api.rows().data().filter( function ( d ) {
                      return d.type == group;
                    } )
                    .pluck( 'prog_bap' );

                  var tertot = api.rows().data().filter( function ( d ) {
                      return d.type == group;
                    } )
                    .pluck( 'terbayar_tot' );

                  // var tertah = api.rows().data().filter( function ( d ) {
                  //     return d.pekerjaan == group;
                  //   } )
                  //   .pluck( 'terbayar_tah' );

                  var totalkontrak = totKontrak.reduce((a, b) => parseInt(a) + parseInt(b), 0);
                  var jum_proglap = proglap.reduce((a, b) => parseInt(a) + parseInt(b), 0);
                  var jum_progbap = progbap.reduce((a, b) => parseInt(a) + parseInt(b), 0);
                  var terbayarTotal = tertot.reduce((a,b) => parseInt(a) + parseInt(b), 0);
                  var idkaw = api.rows().data().filter( function(d){return d.type == group}).pluck('aksi');
                  // var terbayarTahun = tertah.reduce((a,b) => parseInt(a) + parseInt(b), 0);
                  // var total = totalperpek + terbayarTotal;

                  var per_proglap = jum_proglap/proglap.length;
                  var per_progbap = jum_progbap/progbap.length;
                  // console.log(jum_proglap/proglap.length);
                      $(rows).eq( i ).before(
                          // console.log(row);
                          '<tr class="group" style="background-color: white;"">'+
                            '<td colspan=1" style="width:180px;"><strong>'+group+'</strong></td>'+
                            '<td colspan=1" style="width:250px;"><strong>'+numberWithCommas(totalkontrak)+'</strong></td>'+
                            '<td colspan=1" style="width:250px;"><strong>3</strong></td>'+
                            '<td colspan=1" style="width:250px;"><strong>'+per_proglap.toFixed(2)+'</strong></td>'+
                            '<td colspan=1" style="width:250px;"><strong>'+per_progbap.toFixed(2)+'</strong></td>'+
                            '<td colspan=1" style="width:250px;"><strong>Rp. '+numberWithCommas(terbayarTotal)+'</strong></td>'+
                            // '<td colspan=1" style="width:250px;"><strong>'+per_progbap +' %</strong></td>'+
                            // '<td colspan=1" style="width:250px;"><strong>Rp. '+numberWithCommas(terbayarTotal) +'</strong></td>'+'<td colspan=1" style="width:250px;"><strong>Rp. '+numberWithCommas(terbayarTahun) +'</strong></td>'+
                            // '<td colspan=1" style="width:250px;"><strong>10</strong></td>'+
                            // '<td colspan=1" style="width:250px;"><strong>11</strong></td>'+
                            // '<td colspan=1" style="width:250px;"><strong>Rp. '+numberWithCommas(total)+'</strong></td>'+
                          '<td colspan=1"><a class="btn btn-sm btn-primary"  data-toggle="modal" title="Edit" onclick="kawasan('+"'"+group+"'"+','+idkaw[0]+')" >Detail</a></td></tr>'
                      );
                      // console.log(api.column(1, {page:'current'} ).data());
                  last = group;
                }
                
            } );
        },
    "initComplete": function(settings, json) {
                $('.group').nextUntil('.group').css( "display", "none" );
              },
            
    });
     // var display = false;
     //    $('#index tbody').on('click','.group',function()
     //    {
     //      if(display==false){
     //        $(this).nextUntil('.group').toggle(display=true);
     //      }
     //      else{
     //        $(this).nextUntil('.group').toggle(display=false);
     //      }
     //      // $(this).nextUntil('.group').animate({ opacity: "toggle" });
     //      // $(this).nextUntil('.group').toggle();
     //    });

    });

    function numberWithCommas(x) {
      return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
    function kawasan(name,id_rekanan){
      console.log(name+ ' '+id_rekanan);

      var tablePekerjaan =  $('#index_detail').DataTable({
        "serverSide": true,
        "paging":false,
        // "ordering":false,
          "processing": false,
          "destroy": true,
         "ajax":{
                     "url": "{{ url('/')}}/report/detail-concost",
                     "dataType": "json",
                     "type": "post",
                     "data":{ id_rekanan: id_rekanan}
                   },
          "columns":[
                  {data:"kawasan",name:"kawasan"},
                  // {data:"nospk",name:"nospk"},
                  // {data:"tgl_spk",name:"tgl_spk"},
                  // {data:"pekerjaan",name:"pekerjaan"},
                  // {data:"nilai_spk",name:"nilai_spk"},
                  // {data:"nilai_vo",name:"nilai_vo"},
                  // {data:"total_kontrak",name:"total_kontrak"},
                  // {data:"nilai_termyn",name:"nilai_termyn"},
                  // {data:"sisa_kontrak",name:"sisa_kontrak"},
                  // {data:"st1",name:"st1"},
                  // {data:"st2",name:"st2"},
                  
                  ],
          // "columnDefs": [
          // { "targets": [1], render: function(data,type,row){return '<td>'+data+'</td>'}},
          // { "targets": [4,5,6,7,8], render: function(data,type,row){return '<td style="width:180px;">Rp. '+ numberWithCommas(data)+'</td>'}},
          //   // {"visible": false, "targets": [0]},
          //   { "visible": false, "targets": [0] }
            
          // ],
          "order": [[ 0, 'asc' ]],
          "drawCallback": function ( settings ) {
            var api = this.api();
            var rows = api.rows( {page:'current'} ).nodes();
            var last=null;
            var last2=null;
            var last3=null;
 
            api.column(0, {page:'current'} ).data().each( function ( group, i ) {
              // var totspk = api.rows().data().filter( function ( d ) {
              //       return d.kawasan == group;
              //     } ).pluck( 'nilai_spk' );
              // var totalspk = totspk.reduce((a, b) => parseInt(a) + parseInt(b), 0);

                // var totvo = api.rows().data().filter( function ( d ) {
                //     return d.kawasan == group;
                //   } ).pluck( 'nilai_vo' );
                // var totalvo = totvo.reduce((a, b) => parseInt(a) + parseInt(b), 0);

                // var totmyn = api.rows().data().filter( function ( d ) {
                //     return d.kawasan == group;
                //   } ).pluck( 'nilai_termyn' );
                // var totaltermyn = totmyn.reduce((a, b) => parseInt(a) + parseInt(b), 0);

                // var total_kontrak = totalspk + totalvo;
                // var sisa = total_kontrak - totaltermyn;

                console.log(group);
                if ( last !== group ) {
                      $(rows).eq( i ).before(
                          '<tr class="group" style="background-color: white;""><td colspan=3"><strong>'+group+'</strong></td>'
                          // +'<td colspan=1" style="width:180px;"><strong> Rp. '+numberWithCommas(totalspk)+'</strong></td><td colspan=1" style="width:150px;"><strong> Rp. '+numberWithCommas(totalvo)+'</strong></td><td colspan=1" style="width:150px;"><strong> Rp. '+numberWithCommas(total_kontrak)+'</strong></td><td colspan=1" style="width:150px;"><strong> Rp. '+numberWithCommas(totaltermyn)+'</strong></td><td colspan=1" style="width:150px;"><strong> Rp. '+numberWithCommas(sisa)+'</strong></td>'+
                          +'</tr>'
                      );
                  last = group;
                }
            } );
        },
    "initComplete": function(settings, json) {
                $('.group').nextUntil('.group').css( "display", "none" );
              },
      })

      $('#namekaw').text(name);

      $('#ModalaEdit').modal('show');
    }
//     function hapus(no){
//     data_ipk.splice(no,1);
//     tampil();
// }
       
</script>
</body>
</html>
