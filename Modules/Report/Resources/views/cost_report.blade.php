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
                <h3>Cost Report</h3>
              <div class="box-body table-responsive" >
                <input type="hidden" name="id_project" id="id_project" value="{{ $project->id }}">
                  <table id="index" class="table table-bordered bg-white mg-b-0 tx-center" style="font-size:15px; width: 100%; ">
                    <thead class="head_table">
                      <tr style="border: 1px solid black;">
                        <tr>
                          <th colspan="" style="vertical-align: middle;">Kawasan</th>
                          <th rowspan="3" style="vertical-align: middle;">Tgl SPK</th>
                          <th rowspan="3" style="vertical-align: middle;">Acuan SPK</th>
                          <th rowspan="3" style="vertical-align: middle;">Pekerjaan</th>
                          <th rowspan="3" style="vertical-align: middle;">Rekanan</th>
                          <th rowspan="3" style="vertical-align: middle;">Tgl - ST 1</th>
                          <th rowspan="3" style="vertical-align: middle;">Tgl - ST 2</th>
                          <th rowspan="3" style="vertical-align: middle;">RAB/Budget</th>
                          <th colspan="3" style="text-align: center;">Kontrak</th>
                          <th rowspan="3" style="vertical-align: middle;">Lap Prog</th>
                          <th rowspan="3" style="vertical-align: middle;">Prog BAP</th>
                          <th rowspan="3" style="vertical-align: middle;">BAP Terbayar</th>
                          <th colspan="2" style="text-align: center;">Saldo</th>
                        </tr>
                        <tr>
                          <th colspan="" style="vertical-align: middle;">Pekerjaan</th>
                          <th rowspan="2" style="vertical-align: middle;">SPK</th>
                          <th rowspan="2" style="vertical-align: middle;">VO</th>
                          <th rowspan="2" style="vertical-align: middle;">Total</th>
                          <th rowspan="2" style="vertical-align: middle;">Budget</th>
                          <th rowspan="2" style="vertical-align: middle;">Kontrak</th>
                        </tr>
                        <tr>
                          <th colspan="" style="vertical-align: middle;">No.SPK</th>
                        </tr>
                      </tr>
                    </thead>
                    <tbody>
                      <!-- @foreach ($report as $key)
                      <tr>
                        <td colspan=""> -->
                          <!-- {{ $key->project->name }} <br> -->
                          <!-- {{ $key->name}} <br> -->
                        <!--   {{ $key->no}}
                        </td>
                        <td>{{ date('d/M/Y',strtotime($key->date)) }}</td>
                        <td>3</td>
                        <td>{{$key->name}}</td>
                        <td>{{ $key->rekanan->name }}</td>
                        <td>{{ date('d/M/Y',strtotime($key->st_1)) }}</td>
                        <td>{{ date('d/M/Y',strtotime($key->st_2)) }}</td>
                        <td>8</td>
                        <td>
                          @php 
                            $nilai_spk = $key->nilai;
                          @endphp

                           Rp. {{ number_format($key->nilai) }}
                        </td>
                        <td>
                          @php
                            $nilai_vo = $key->nilai_vo;
                          @endphp
                          Rp. {{ number_format($key->nilai_vo) }}
                        </td>

                        <td>
                          @php
                            $tot = $nilai_spk + $nilai_vo;
                          @endphp
                          {{ number_format($tot) }}
                        </td>
                        <td>
                         12
                        </td>
                        <td>13</td>
                        <td>Rp. {{ number_format($bap = $key->baps->sum('nilai_bap_dibayar'))}}</td>
                        <td>15</td>
                        <td>Rp. {{ number_format($tot - $bap)}}</td>
                      </tr>
                      @endforeach -->
                    </tbody>
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
@include("master/copyright")
  
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

@include("master/footer_table")
<!-- @include("spk::app") -->

<script type="text/javascript">
  var data_ipk=[];
  $(document).ready(function(){
    var id_project = $("#id_project").val();
    // var table = $('#index').DataTable({
    //   "pageLength" : 10,
    //     "order": [[ 0, 'desc' ]],
        // "scrollX": true,
        // scrollY: "500px",
        // scrollCollapse: true,
    // });

    $('#index').DataTable({
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
          'ordering'    : false,
          // loadingRecords: '&nbsp;',
          // 'info'        : true,
          'autoWidth'   : false,
            "serverSide": true,
            processing: false,
            "ajax":{
                     "url": "{{ url('/report/costreportss') }}",
                     "dataType": "json"
                     // "type": "get",
                     // "data":{ id_project: id_project}
                   },
                   
            "columns": [
                { "data": "kapekno" },
                { "data": "tgl_spk" },
                { "data": "acuan" },
                { "data": "pekerjaan" },
                { "data": "rekanan" },
                { "data": "tgl_st1" },
                { "data": "tgl_st2" },
                { "data": "budget" },
                { "data": "nilai_spk" },
                { "data": "nilai_vo" },
                { "data": "spknvo" },
                { "data": "progres_lap" },
                { "data": "prog_bap" },
                { "data": "bap_terbayar" },
                { "data": "budget" },
                { "data": "kontrak" },
            ],  
    });

    });









//     function hapus(no){
//     data_ipk.splice(no,1);
//     tampil();
// }
       
</script>
</body>
</html>
