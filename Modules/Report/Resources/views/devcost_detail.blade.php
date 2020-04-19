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
    <section class="container">
      {{ csrf_field() }}
      <div class="box box-default">
        <!-- /.box-header -->
        <div class="box-body">
            <div class="row" style="margin-bottom: 20px">
               <!-- div class="col-md-12 center">             
              </div> -->
              <div class="col-md-12">
                <h3>HPP Development Cost (Detail)</h3>
              <div class="tab-pane table-responsive" id="tab_2">
                  <table id="index" class="table table-bordered bg-white mg-b-0 tx-center" style="font-size:15px; width: 100%; ">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Tahun</label>
                        <select class="form-control" id="tahun"> 
                          <option value="0" selected="" disabled="">-------------Pilih Tahun-----------</option> 
                          <option value="2018">2018</option>                  
                          <option  value="2019">2019</option>                       
                        </select>
                      </div>
                    </div>
                    <thead class="head_table">
                      <tr style="border: 1px solid black;">
                        <tr>
                          <!-- <td colspan="1"></td> -->
                          <td colspan="" style="vertical-align: middle;">Kawasan</td>
                          <td colspan="2" style="text-align: center;">Budget</td>
                          <td colspan="2" style="text-align: center;">Kontrak</td>
                          <td rowspan="2" style="vertical-align: middle;">Prog Lapangan</td>
                          <td rowspan="2"  style="vertical-align: middle;">Prog BAP</td>
                          <td colspan="2" style="text-align: center;">BAP/Terbayar</td>
                          <td colspan="3" style="text-align: center;">Saldo</td>
                          <td rowspan="2" style="text-align: center;">Aksi</td>
                        </tr>
                        <tr>
                          <!-- <td colspan="" style="vertical-align: middle;">Nama Rekanan</td> -->
                          <td colspan="" style="vertical-align: middle;">Pekerjaan</td>
                          <td rowspan="" style="vertical-align: middle;">Budget Awal</td>
                          <td rowspan="" style="vertical-align: middle;">Budget Tahun </td>
                          <td rowspan="" style="vertical-align: middle;">Kontrak Total</td>
                          <td rowspan="" style="vertical-align: middle;">Kontrak Tahun <span style="color: white " class="year1"></span></td>
                          <td rowspan="" style="vertical-align: middle;">Terbayar Total</td>
                          <td rowspan="" style="vertical-align: middle;">Terbayar Tahun <span style="color: white " class="year1"></span></td>
                          <td rowspan="" style="vertical-align: middle;">Budget Awal</td>
                          <td rowspan="" style="vertical-align: middle;">Budget Tahun <span style="color: white " class="year1"></span></td>
                          <td rowspan="" style="vertical-align: middle;">Kontrak Total</td>      
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
                        <th colspan="2"></th>
                    </tr>
                </tfoot>
                </table>
                </div>
              </div>
            </div>

             <div class="row" >
               <!-- div class="col-md-12 center">             
              </div> -->
              <div class="col-md-4">
             
              <div class="tab-pane table-responsive" id="tab_2">
                  <table id="index" class="table table-bordered bg-white mg-b-0 tx-center" style="font-size:15px; width: 100%; ">
                    <thead class="head_table">
                      <tr style="border: 1px solid black;">
                        <tr>
                          <!-- <td colspan="1"></td> -->
                          <td colspan="2" style="text-align: center;">Luas Area</td>
                        </tr>
                        <tr>
                          <!-- <td colspan="1"></td> -->
                          <td colspan="" style="vertical-align: middle;">Netto / M2</td>
                          <td>
                            <input type="hidden" name="" id="netto" value="{{ $total_unit[0]->luas_netto }}">
                            <input type="hidden" name="" id="netto_nonsal" value="{{ $total_netkaw[0]->luas_kaw }}">
                            {{ $total_unit[0]->luas_netto }}
                          </td>
                        </tr>
                        <tr>
                          <!-- <td colspan="1"></td> -->
                          <td colspan="" style="vertical-align: middle;">Brutto / M2</td>
                          <td>
                            <input type="hidden" name="" id="brutto" value="{{ $total_unit[0]->luas_brutto }}">
                            {{ $total_unit[0]->luas_brutto }}
                          </td>
                        </tr>
                        <tr>
                          <!-- <td colspan="1"></td> -->
                          <td colspan="" style="vertical-align: middle;">Effisiensi (%)</td>
                          <td>{{ $total_unit[0]->luas_netto/$total_unit[0]->luas_brutto }}</td>
                        </tr>
                      </tr>
                </thead>
                </table>
                </div>
              </div>

               <div class="col-md-7">
                
              <div class="tab-pane table-responsive" id="tab_2">
                  <table id="index" class="table table-bordered bg-white mg-b-0 tx-center" style="font-size:15px; width: 100%; ">
                    <thead class="head_table">
                      <tr style="border: 1px solid black;">
                        <tr>
                          <!-- <td colspan="1"></td> -->
                          <td colspan="2" style="vertical-align: middle;">HPP/M2</td>
                          <td colspan="" style="vertical-align: middle;">Kawasan (IDR)</td>
                          <td colspan="" style="vertical-align: middle; ">Project (IDR)</td>
                          <td colspan="" style="vertical-align: middle; ">Total (IDR)</td>
                        </tr>
                        <tr>
                          <td rowspan="3" style="vertical-align: middle;">Netto</td>
                          <td colspan="" style="vertical-align: middle;">Budget</td>
                          <td></td>
                          <td></td> 
                          <td></td>                        
                        </tr>
                       <tr>
                         <td colspan="" style="vertical-align: middle;">Kontrak</td>
                         <td><span style="color: white " id="netto_kontrak"></span></td>
                         <td><span style="color: white " id="netto_project"></span></td> 
                         <td><span style="color: white" id="tot_konkawproj"></span></td>                       
                       </tr>
                       <tr>
                         <td colspan="" style="vertical-align: middle;">Realisasi</td>
                         <td><span style="color: white " id="netto_realisasi"></span></td>
                         <td><span style="color: white " id="realisasi_project"></span></td>
                         <td><span style="color: white" id="tot_realkawproj"></span></td>
                       </tr>
                       <tr>
                         <td rowspan="3" style="vertical-align: middle;">Brutto</td>
                         <td colspan="" style="vertical-align: middle;">Budget</td>
                         <td></td>
                         <td></td>
                         <td></td>
                       </tr>
                       <tr>
                         <td colspan="" style="vertical-align: middle;">Kontrak</td>
                         <td><span style="color: white " id="brutto_kontrak"></span></td>
                         <td><span style="color: white " id="brutto_project"></span></td>
                         <td><span style="color: white " id="totbrut_kon"></span></td>
                       </tr>
                       <tr>
                         <td colspan="" style="vertical-align: middle;">Realisasi</td>
                         <td><span style="color: white " id="brutto_realisasi"></span></td>
                         <td><span style="color: white " id="brutto_project_real"></span></td>
                         <td><span style="color: white " id="totbrut_real"></span></td>
                       </tr>
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
                  <div class="tab-pane table-responsive" id="tab_2">
                  <table id="index_detail" class="table table-bordered bg-white mg-b-0 tx-center" style="font-size:15px; width: 100%; ">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Tahun</label>
                        <input type="hidden" name="" id="nameKawasan">
                        <input type="hidden" name="" id="idItem">
                        <select class="form-control" id="tahun2"> 
                          <option value="" selected="" disabled="">-------------Pilih Tahun-----------</option> 
                          <option value="2018">2018</option>                  
                          <option  value="2019">2019</option>                       
                        </select>
                      </div>
                    </div>
                    <thead class="head_table">
                      <tr style="border: 1px solid black;">
                        <tr>
                          <!-- <td colspan="1"></td> -->
                          <td rowspan="2" style="vertical-align: middle;">Pekerjaan</td>
                          <td colspan="2" style="text-align: center;">Budget</td>
                          <td colspan="2" style="text-align: center; width: 250px;">Kontrak</td>
                          <td rowspan="2" style="vertical-align: middle;">Prog Lapangan</td>
                          <td rowspan="2"  style="vertical-align: middle; width:250px;">Prog BAP</td>
                          <td colspan="2" style="text-align: center;">BAP/Terbayar</td>
                          <td colspan="3" style="text-align: center;">Saldo</td>
                        </tr>
                        <tr>
                          <!-- <td colspan="" style="vertical-align: middle;">Nama Rekanan</td> -->
                          
                          <td rowspan="" style="vertical-align: middle;">Budget Awal</td>
                          <td rowspan="" style="vertical-align: middle;">Budget Tahun</td>
                          <td rowspan="" style="vertical-align: middle; width:280px;">Kontrak Total</td>
                          <td rowspan="" style="vertical-align: middle;">Kontrak Tahun <span style="color: white " class="year"></span></td>
                          <td rowspan="" style="vertical-align: middle;">Terbayar Total</td>
                          <td rowspan="" style="vertical-align: middle;">Terbayar Tahun <span style="color: white " class="year"></span></td>
                          <td rowspan="" style="vertical-align: middle;">Budget Awal</td>
                          <td rowspan="" style="vertical-align: middle;">Budget Tahun <span style="color: white " class="year"></span></td>
                          <td rowspan="" style="vertical-align: middle;">Kontrak Total</td>      
                        </tr>
                      </tr>
                </thead>
                </table>
                </div>
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
  </div>
  <!-- /.content-wrapper -->
@include("master/copyright")
  
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

@include("master/footer_table")
@include("form.general_form")
@include("spk::app")

<script type="text/javascript">
  var data_ipk=[];
  
  $(document).ready(function(){
    var tahun = 2019;
    initTable(tahun);

    $('#tahun').change(function(){
      initTable($(this).val()) 
    });

    $('#tahun2').change(function(){
      initTableDetail($('#idItem').val(),$('#nameKawasan').val(),$(this).val()) 
    });

    });

  function initTable(tahun){
    var change_tahun = 0;
    if(tahun == 2019 ){
      change_tahun = tahun;
    }else{
      change_tahun = $('#tahun').val();
    }

    $('.year1').text(change_tahun);
    $('#index').DataTable({
      // 'paging'      : false,
          // 'lengthChange': false,
          language: {
          // searchPlaceholder: 'Search...',
          // sSearch: '',
          // lengthMenu: '_MENU_',
          // loadingRecords: '&nbsp;',
          // "loadingRecords": "Please wait - loading...",
          "processing": '<div class="spinner-grow" role="status">'+
                          '<span class="sr-only">Loading...</span>'+
                        '</div>'
        },
          'searching'   : false,
          "ordering": false,
          "destroy":true,
          // loadingRecords: '&nbsp;',
          // 'info'        : true,
          'autoWidth'   : false,
            "serverSide": true,
            "processing": true,
            "ajax":{
                     "url": "{{ url('/report/devcostdetail') }}",
                     "dataType": "json",
                     "type": "post",
                     "data":{ op_tahun: change_tahun}
                   },
                   
            "columns": [
                { "data": "kawasan" },
                { "data": "budget_awal" },
                { "data": "budget_akhir" },
                { "data": "kontrak_total" },
                { "data": "kontrak_tahun" },                
                { "data": "proglap" },
                { "data": "progbap" },
                { "data": "terbayar_tot" },
                { "data": "terbayar_tah" },
                { "data": "salbud_awal" },
                { "data": "salbud_tah" },
                { "data": "saldo_total" },
                { "data": "aksi" }
            ],

            "columnDefs":[
            {"targets": [3,4,7,8,11], render: function(data,type,row){return '<td style="width:350px;">Rp. '+numberWithCommas(data)+'</td>'}},
            {"targets": [5], render: function(data,type,row){return '<td style="width:250px;">'+data+' %</td>'}},
            {"targets": [6], render: function(data,type,row){return '<td style="width:250px;">'+data+' %</td>'}},
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
            kontrakTotal = api
                .column( 3 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

             kontrakTahun = api
                .column( 4 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );   

            bapTotal = api
                .column( 7 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 ); 

            bapTahun = api
                .column( 7 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 ); 

            totalSum = api
                .column( 11 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );  

            var netto = $('#netto').val();
            var brutto = $('#brutto').val();
            var netto_nonsal = $('#netto_nonsal').val();
            var netto_kon = Math.ceil(kontrakTotal/netto);
            var netto_real = Math.ceil(bapTotal/netto);
            var brutto_kon = Math.ceil(kontrakTotal/brutto);
            var brutto_real = Math.ceil(bapTotal/brutto);
            var netto_proj = netto_nonsal/netto*kontrakTotal;
            var real_proj = netto_nonsal/netto*bapTotal;
            var brutto_proj = Math.ceil(netto_nonsal/brutto*kontrakTotal);
            var brutto_realproj = netto_nonsal/brutto*bapTotal;
            var totkonkawproj = netto_kon+netto_proj;
            var totrealkawproj = netto_real+real_proj;
            var totbrut_kon = brutto_kon+brutto_proj;
            var totbrut_real = brutto_real+real_proj;

            $('#netto_kontrak').text('Rp. '+ numberWithCommas(netto_kon));
            $('#netto_realisasi').text('Rp. '+ numberWithCommas(netto_real));
            $('#brutto_kontrak').text('Rp. '+ numberWithCommas(brutto_kon));
            $('#brutto_realisasi').text('Rp. '+ numberWithCommas(brutto_real));
            $('#netto_project').text('Rp. '+ numberWithCommas(netto_proj));
            $('#realisasi_project').text('Rp. '+ numberWithCommas(real_proj));
            $('#brutto_project').text('Rp. '+ numberWithCommas(brutto_proj));
            $('#brutto_project_real').text('Rp. '+ numberWithCommas(brutto_realproj));
            $('#tot_konkawproj').text('Rp. '+ numberWithCommas(totkonkawproj));
            $('#tot_realkawproj').text('Rp. '+ numberWithCommas(totrealkawproj));
            $('#totbrut_kon').text('Rp. '+ numberWithCommas(totbrut_kon));
            $('#totbrut_real').text('Rp. '+ numberWithCommas(totbrut_real));
 
            // Update footer
            $( api.column( 3 ).footer() ).html('Rp. '+numberWithCommas(kontrakTotal));
            $( api.column( 4 ).footer() ).html('Rp. '+numberWithCommas(kontrakTahun));
            $( api.column( 5 ).footer() ).html();
            $( api.column( 6 ).footer() ).html();
            $( api.column( 7 ).footer() ).html('Rp. '+numberWithCommas(bapTotal));
            $( api.column( 8 ).footer() ).html('Rp. '+numberWithCommas(bapTahun));
            $( api.column( 11 ).footer() ).html('Rp. '+numberWithCommas(totalSum));
        }
            
    });
  }

  function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
  }

  function initTableDetail(id_item,name,tahun){
    $('#nameKawasan').val(name);
    $('#idItem').val(id_item);

    $('#index_detail').DataTable().clear().draw();
     var tablePekerjaan =  $('#index_detail').DataTable({
        "serverSide": true,
          "processing": false,
          "destroy": true,
          'searching'   : false,
         "ajax":{
                     "url": "{{ url('/')}}/report/detaildevcost",
                     "dataType": "json",
                     "type": "post",
                     "data":{ id_item: id_item, tahun : tahun}
                   },
          "columns":[
                  {data:"pekerjaan",name:"pekerjaan"},
                  {data:"budget_tahun",name:"budget_tahun"},
                  {data:"budget_kontrak",name:"budget_kontrak"},
                  {data:"totnil",name:"totnil"},
                  {data:"kontrak_tahun",name:"kontrak_tahun"},
                  {data:"prog_lap",name:"prog_lap"},
                  {data:"prog_bap",name:"prog_bap"},
                  {data:"terbayar_tot",name:"terbayar_tot"},
                  {data:"terbayar_tah",name:"terbayar_tah"},
                  // {data:"nilai_kontrak",name:"nilai_kontrak"},
                  // {data:"termyn",name:"termyn"},
                  // {data:"sisa",name:"sisa"},
                  // {data:"st1",name:"st1"},
                  // {data:"st2",name:"st2"},
                  
                  ],
          "columnDefs": [
            { "targets": [0], render: function(data,typr,row){ return '<td></td>'} },
            { "targets":[3,7,4,8], render: function(data,type,row){ return '<td style="width:180px;">Rp. '+ numberWithCommas(data) +'</td>'}},
            { "targets": [5,6], render: function(data,typr,row){ return '<td>'+data+' %</td>'} },
          ],
          "order": [[ 0, 'asc' ]],
          "drawCallback": function ( settings ) {
            var api = this.api();
            var rows = api.rows( {page:'current'} ).nodes();
            var last=null;
            var last2=null;
            var last3=null;
 
            api.column(0, {page:'current'} ).data().each( function ( group, i ) {
                // console.log(group);

                if ( last !== group ) {
                  var pertotpek = api.rows().data().filter( function ( d ) {
                      return d.pekerjaan == group;
                    } )
                    .pluck( 'totnil' );

                  var proglap = api.rows().data().filter( function ( d ) {
                      return d.pekerjaan == group;
                    } )
                    .pluck( 'prog_lap' );

                  var progbap = api.rows().data().filter( function ( d ) {
                      return d.pekerjaan == group;
                    } )
                    .pluck( 'prog_bap' );

                  var kontah = api.rows().data().filter( function ( d ) {
                      return d.pekerjaan == group;
                    } )
                    .pluck( 'kontrak_tahun' );

                  var tertot = api.rows().data().filter( function ( d ) {
                      return d.pekerjaan == group;
                    } )
                    .pluck( 'terbayar_tot' );

                  var tertah = api.rows().data().filter( function ( d ) {
                      return d.pekerjaan == group;
                    } )
                    .pluck( 'terbayar_tah' );

                  var jum_proglap = proglap.reduce((a, b) => parseInt(a) + parseInt(b), 0);
                  var jum_progbap = progbap.reduce((a, b) => parseInt(a) + parseInt(b), 0);
                  var totalperpek = pertotpek.reduce((a, b) => parseInt(a) + parseInt(b), 0);
                  var kontrakTahun = kontah.reduce((a,b) => parseInt(a) + parseInt(b), 0);
                  var terbayarTotal = tertot.reduce((a,b) => parseInt(a) + parseInt(b), 0);
                  var terbayarTahun = tertah.reduce((a,b) => parseInt(a) + parseInt(b), 0);
                  var total = totalperpek + terbayarTotal;

                  var per_proglap = jum_proglap/proglap.length;
                  var per_progbap = jum_progbap/progbap.length;
                  // console.log(jum_proglap/proglap.length);
                      $(rows).eq( i ).before(
                          // console.log(row);
                          '<tr class="group" style="background-color: white;"">'+
                            '<td colspan=1" style="width:350px;"><strong>'+group+'</strong></td>'+
                            '<td colspan=1" style="width:250px;"><strong>2</strong></td>'+
                            '<td colspan=1" style="width:250px;"><strong>3</strong></td>'+
                            '<td colspan=1" style="width:250px;"><strong>Rp. '+numberWithCommas(totalperpek)+'</strong></td>'+
                            '<td colspan=1" style="width:250px;"><strong>Rp. '+numberWithCommas(kontrakTahun)+'</strong></td>'+
                            '<td colspan=1" style="width:250px;"><strong>'+per_proglap+' %</strong></td>'+
                            '<td colspan=1" style="width:250px;"><strong>'+per_progbap +' %</strong></td>'+
                            '<td colspan=1" style="width:250px;"><strong>Rp. '+numberWithCommas(terbayarTotal) +'</strong></td>'+'<td colspan=1" style="width:250px;"><strong>Rp. '+numberWithCommas(terbayarTahun) +'</strong></td>'+
                            '<td colspan=1" style="width:250px;"><strong>10</strong></td>'+
                            '<td colspan=1" style="width:250px;"><strong>11</strong></td>'+
                            '<td colspan=1" style="width:250px;"><strong>Rp. '+numberWithCommas(total)+'</strong></td>'+
                          '</tr>'
                      );
                      // console.log(api.column(1, {page:'current'} ).data());
                  last = group;
                }
                
            } );
        },
    "initComplete": function(settings, json) {
                $('.group').nextUntil('.group').css( "display", "none" );
              },
      })

        // var display = false;
        // $('#index_detail tbody').on('click','.group',function()
        // {
        //   if(display==false){
        //     $(this).nextUntil('.group').toggle(display=true);
        //   }
        //   else{
        //     $(this).nextUntil('.group').toggle(display=false);
        //   }
        //   // $(this).nextUntil('.group').animate({ opacity: "toggle" });
        //   // $(this).nextUntil('.group').toggle();
        // });
      $('.year').text(tahun);  
      $('#namekaw').text(name);
  }

  function pekerjaan(id_item,name){
      var item = id_item;
      var nama = name;
      var tgl = new Date();
      var tahun = tgl.getFullYear();

      initTableDetail(item,nama,tahun);

      $('#ModalaEdit').modal('show');
      $('#tahun2').val('');
    }

    // $('#tahun').change(initTable($('#tahun').val()));
       
</script>
</body>
</html>
