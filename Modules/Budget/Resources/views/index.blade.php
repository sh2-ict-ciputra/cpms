<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Admin QS | Dashboard</title>
  @include("master/header")
</head>
<style>
.head_table tr td {
    text-align: center;
}
</style>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  @include("master/sidebar_project")

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>Data Proyek <strong>{{ $project->name }}</strong></h1>
      <input type="hidden" name="project_id" id="project_id" value="{{$project->id}}">
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">

            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="form-group">
                  <label>Department</label>
                  <select id="department" name="department" class="form-control" margin="">
                    <option value="">Pilih Department</option>
                    @foreach($department as $key => $value)
                      @if($value->id == 2)
                        <option value="{{$value->id}}" selected>{{$value->name}}</option>
                      @else
                        <option value="{{$value->id}}">{{$value->name}}</option>
                      @endif
                    @endforeach
                  </select>
                </div>

              @if ( $project->luas > 0 )
              <a href="{{ url('/')}}/budget/add-budget?id={{ $project->id }}" class="btn-lg btn-primary"><i class="glyphicon glyphicon-plus-sign"></i>Tambah Budget</a>
              <button submit="" class="btn-lg btn-info" id="rekap"><i class="glyphicon"></i>Rekap</button><br><br>
              @else
              <h3 style="color:red"><strong>Luas Proyek belum dibuat</strong></h3>
              @endif
              <table class="table table-bordered" style="width: 50%;">
                <tr>
                  <td style="background-color: grey;color:white;font-weight: bolder" colspan="3">Budget Dev Cost Revisi</td>
                </tr>
                <tr>
                  <td colspan="">
                    <span>Nilai Budget Sisa Renc. </span>
                  </td>
                  <td style="width:5px">:</td>
                  <td style="text-align:right">
                    @php $total = 0; @endphp
                    @if ( $user->project_pt_users != "" )
                      @foreach ( $user->project_pt_users as $key2 => $value2 )
                        @foreach ( $budget as $key => $value )
                          @if ( $value->pt_id == $value2->pt_id )
                            @if ( $value->deleted_at == "" )
                              @php $total = $total + $value->total_rencana_dev_cost;@endphp
                            @endif
                          @endif
                        @endforeach
                      @endforeach
                    @endif

                    Rp. {{ number_format($total = $project->total_dev_cost_con_cost["TotalDevCost"])}}
                  </td>
                </tr>
                <tr>
                  <td colspan="">
                    <span>Total SPK </span>
                  </td>
                  <td style="width:5px">:</td>
                  <td style="text-align:right">
                    Rp. {{ number_format($total_spk = $project->total_spk_detail["total_nilai_spk_dc"])}}
                  </td>
                </tr>
                <tr>
                  <td colspan="">
                    <span>Nilai Dev Cost ( SPK + Sisa Renc. ) </span>
                  </td>
                  <td style="width:5px">:</td>
                  <td style="text-align:right">
                    Rp. {{ number_format($devcost = $total + $total_spk  )}}
                  </td>
                </tr>
              </table>     
              <table class="table table-bordered" style="width: 50%;">
                <tr>
                  <td  style="background-color: grey;color:white;font-weight: bolder">&nbsp;</td>
                  <td style="background-color: grey;color:white;font-weight: bolder">Luas</td>
                  <td style="background-color: grey;color:white;font-weight: bolder"><center>DevCost  Awal <br> (Rp / m2)</center></td>
                  <td style="background-color: grey;color:white;font-weight: bolder"><center>DevCost  Revisi <br> (Rp / m2)</center></td>
                </tr>
                <tr>
                  <td>Brutto</td>
                  @if ( $project->luas > 0 )
                  <td>Luas Brutto  : {{ number_format($project->luas) }} m2</td>
                  <td></td>
                  <td>{{ number_format($devcost/$project->luas,2)}}</td>
                  @else
                  <td>Luas Brutto  : {{ number_format(0) }} m2</td>
                  <td></td>
                  <td>{{ number_format(0,2)}}</td>
                  @endif
                </tr>
                <tr>
                  <td>Netto</td>
                  <td>Luas Netto  : {{ number_format($project->netto) }} m2</td>
                  <td></td>                  
                  @if ( $project->netto == "0")
                  <td>{{ number_format(0,2)}}</td>
                  @else
                  <td>{{ number_format($devcost/$project->netto,2)}}</td>
                  @endif
                </tr>
              </table>        
              <br>
              <button class="btn btn-info" id="btn_approval_{{$value->id}}" onclick="requestapproval('{{ $value->id }}')">Request Approval</button>
              <input type="hidden" name="budget_id_array" id="budget_id_array" value="">
              <table id="table_budget" class="table table-bordered table-hover table_budget">   
                {{ csrf_field() }}              
                <thead class="head_table">
                  <tr>
                    <td>Nomor Budget</td>
                    <td>Total Nilai DevCost <br> (Rp)</td>
                    <td>DevCost Netto Awal <br> (Rp / m2)</td>
                    <td>DevCost Netto Revisi <br> (Rp / m2)</td>
                    <td>Kawasan</td>
                    <td>Start Date</td>
                    <td>End Date</td>                  
                    <!--td>Status</td-->
                    <td>Detail</td>
                    <td>Revisi</td>
                    <td>Approval</td>
                  </tr>
                </thead>
                <tbody>
                  @php $nilai = 0; @endphp
                  @foreach ( $user->project_pt_users as $key2 => $value2 )
                    @foreach ( $budget as $key => $value )
                      @if ( $value2->pt_id == $value->pt_id )
                        @if ( $value->deleted_at == "")

                        @php
                          $array = array (
                            "6" => array("label" => "Disetujui", "class" => "label label-success"),
                            "7" => array("label" => "Ditolak", "class" => "label label-danger"),
                            "1" => array("label" => "Dalam Proses", "class" => "label label-warning")
                          )
                        @endphp
                        <tr>
                          <td>{{ $value->no }}</td>
                          <td>{{ number_format($value->total_dev_cost + $value->total_spk["total_nilai_spk_dc"]) }}</td>
                          {{-- <td>{{ number_format($value->total_dev_cost) }}</td> --}}
                          <td></td>
                          <td>
                            @if($value->hpp_netto != 0)
                              {{number_format($value->hpp_netto)}}
                            @else
                              -
                            @endif
                          </td>

                          <td>{{ $value->kawasan->name or 'Fasilitas Kota' }}</td>
                          <td>{{ $value->start_date}}</td>
                          <td>{{ $value->end_date}}</td>                  
                          <!--td>Status</td-->
                          <td>
                            <a class="btn btn-warning" href="{{ url('/')}}/budget/detail?id={{ $value->id }}">Detail</a>                   
                          </td>
                          <td>
                            @if ( $value->approval == "" )
                            
                            @else
                              @if ( $value->approval->approval_action_id == 6 )
                                @if ( count(\Modules\Budget\Entities\Budget::where("parent_id",$value->id)->get()) <= 0 )
                                  <a class="btn btn-success" href="{{ url('/')}}/budget/revisibudget?id={{ $value->id }}">Revisi</a>
                                @else
                                  <a class="btn btn-success" href="{{ url('/')}}/budget/list-budgetrevisi?id={{ $value->id }}">Daftar Revisi</a>
                                @endif
                              @else
                              
                              @endif
                            @endif
                          </td>
                          <td>
                          <a class="btn btn-primary" href="{{ url('/')}}/budget/cashflow/?id={{ $value->id }}">({{$value->budget_tahunans->count()}})Budget Cash Out</a>
                          </td>
                        </tr>
                        @endif
                      @endif
                    @endforeach
                  @endforeach
                </tbody>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>

  <div class="modal fade " id="ModalRekap" tabindex="-1" role="dialog" aria-labelledby="largeModal" aria-hidden="true" style="overflow-y:auto">
    <div style="width: 90%" class="modal-dialog modal-lg ">
      <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3 class="modal-title" id="myModalLabel">Budget Bulanan </h3>
        </div>
        <!-- <form class="form-horizontal" > -->
            <div class="modal-body">
              <div class="tab-panel " id="tab_2">
              <table class="table" style="font-size:14px;font-weight:bold; width:50%">
                <thead>
                  <tr>
                    <td style="30%">Project</td>
                    <td>:</td>
                    <td>
                      <p id="project"></p>
                    </td>
                  </tr>
                  <tr>
                    <td>Periode Tahun</td>
                    <td>:</td>
                    <td>

                      <p id="periode_tahun"></p>
                    </td>
                  </tr>
                  <tr>
                    <td>Total CO & Budget DC+CC</td>
                    <td>:</td>
                    <td>
                      <p id="total"></p>
                    </td>
                  </tr>
                  <tr>
                    <td>Total sisa CO & Budget DC+CC</td>
                    <td>:</td>
                    <td>
                      <p id="total_sisa"></p>
                    </td>
                  </tr>
                </thead>
              </table>          

              <h3 class=""><strong> DevCost Summary </strong></h3>
              <table class="table table-bordered" style="width:100%;font-size:14px" id="table_dev_cost" >
                <thead class="head_table">
                  <tr>
                    <td style="width:20%"></td>
                    <td style="width:15%">hutang Bayar awal tahun & Budget</td>
                    <td style="text-align: center">Jan</td>
                    <td>Feb</td>
                    <td>Mar</td>
                    <td>Apr</td>
                    <td>Mei</td>
                    <td>Jun</td>
                    <td>Jul</td>
                    <td>Agu</td>
                    <td>Sep</td>
                    <td>Okt</td>
                    <td>Nov</td>
                    <td>Des</td>
                    <td>Total/Tahun</td>
                    <td>Sisa Hutang Bayar</td>
                  </tr>
                </thead>
              </table>

              <h3 class=""><strong> ConCost Summary </strong></h3>
              <table class="table table-bordered" style="width:100%;font-size:14px" id="table_con_cost" >
                <thead class="head_table">
                  <tr>
                    <td style="width:20%"></td>
                    <td style="width:15%">hutang Bayar awal tahun & Budget</td>
                    <td style="text-align: center">Jan</td>
                    <td>Feb</td>
                    <td>Mar</td>
                    <td>Apr</td>
                    <td>Mei</td>
                    <td>Jun</td>
                    <td>Jul</td>
                    <td>Agu</td>
                    <td>Sep</td>
                    <td>Okt</td>
                    <td>Nov</td>
                    <td>Des</td>
                    <td>Total/Tahun</td>
                    <td>Sisa Hutang Bayar</td>
                  </tr>
                </thead>
              </table>
            </div>
                
                
            </div>

            <div class="modal-footer">
              <!-- <input type="hidden" name="all_send_bulanan_carryover" id="all_send_bulanan_carryover" />
              <button type="" id="btn-submit-bulanan_carryover" class="btn btn-primary" style="margin-right:45%">Simpan</button> -->
            </div>
        <!-- </form> -->
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
@include("project::app")
@include('form.general_form')

<script type="text/javascript">
  $('#example3').DataTable({
      'paging'      : false,
      'lengthChange': false,
      'searching'   : true,
      'ordering'    : false,
      'info'        : true,
      'autoWidth'   : false,
      fixedColumns:   {
          leftColumns: 4
      }
    });

  function requestapproval(id){
    $(".checkbox_apprvoal").hide();
    $(".checkbox_loading").show();
    if ( confirm("Apakah anda yakin ingin merilis budget ini ? ")){
      $("#btn_approval_" + id).hide();
      var request = $.ajax({
        url : "{{ url('/')}}/budget/approval-add",
        data : {
          id : id,
          budget_id_array : $("#budget_id_array").val()
        },
        type : "post",
        dataType : "json"
      });

      request.done(function(data){
        $(".checkbox_apprvoal").show();
        $(".checkbox_loading").hide();
        $("#btn_approval_" + id).show();
        if ( data.status == "0" ){
          alert("Budget telah dirilis ");
          window.location.reload();
        }else{
          return false;
        }
      })
    }else{
      return false;
    }
  }

  function updateapproval(id,approval_id){
    if ( confirm("Apakah anda yakin ingin merilis budget ini ? ")){
      var request = $.ajax({
        url : "{{ url('/')}}/budget/approval-update",
        data : {
          id : id,
          budget_id_array : $("budget_id_array").val()
        },
        type : "post",
        dataType : "json"
      });

      request.done(function(data){
        if ( data.status == "0" ){
          alert("Budget telah dirilis ");
          window.location.reload();
        }else{
          return false;
        }
      });

    }else{
      return false;
    }
  }

  function setIdApprove(id){
    var budget_id_array = $("#budget_id_array").val();
    if ( $('#budget_id_' + id).is(':checked')){
      $("#budget_id_array").val(budget_id_array + "," + id)
    }else{
      var remo = $("#budget_id_array").val();
      var new_budge_id = remo.replace("," + id, "");
       $("#budget_id_array").val(new_budge_id);
    }
  }

  $('#table_budget').DataTable({
      "columns":[
              {data:"no",name:"no"},
              {data:"nilai_devcost",name:"nilai_devcost"},
              {data:"hpp_awal",name:"hpp_awal"},
              {data:"hpp_revisi",name:"hpp_revisi"},
              {data:"kawasan",name:"kawasan"},
              {data:"start_date",name:"start_date"},
              {data:"end_date",name:"end_date"},
              {data:"detail",name:"detail"},
              {data:"revisi",name:"revisi"},
              {data:"approval",name:"approval"},
              ],
      "order": [[ 0, 'asc' ]]
  })
  
  $(document).on('change', '#department', function() {
        // console.log("hhaalo");
        var department_id = $(this).val();
        var project_id = $("#project_id").val();
        var _url = "{{ url('/budget/budgetDepartment') }}";
        var _data = {
          department_id : department_id,
          project_id : project_id,
        };
        $('#table_budget').DataTable().clear().draw();
        // var parent_div = $(this).parents('tr');
        $.ajax({
            type: 'post',
            dataType: 'json',
            url: _url,
            data: _data,
            beforeSend: function() {
            waitingDialog.show();
            },
            success: function(data) {
                console.log(data);
                if (data.data.length > 0) {
                    $(data.data).each(function(i, v) { 
                        var ItemTable = {
                            no: v.no_budget,
                            nilai_devcost : v.nilai_devcost,
                            hpp_awal : v.hpp_awal,
                            hpp_revisi : v.hpp_revisi,
                            kawasan : v.kawasan,
                            start_date : v.start_date,
                            end_date : v.end_date,
                            detail : '<a class="btn btn-warning" href="{{ url("/")}}/budget/detail?id='+v.id_budget+'">Detail</a>',
                            revisi : null,
                            approval : '<a class="btn btn-primary" href="{{ url("/")}}/budget/cashflow/?id='+v.id_budget+'">('+v.count_tahunan+')Budget Cash Out</a>',
                        };
                        $('#table_budget').DataTable().row.add(ItemTable);
                    });
                }
                $('#table_budget').DataTable().draw();
            },
            complete: function() {
                waitingDialog.hide();
                
            }
        });
    });

    $('#table_dev_cost').DataTable({
    "paging":false,
    "destroy": true,
    "autoWidth": true,
    "responsive": true,
    "scrollX": true,
    "searching": false,
    "columns":[
      {data:"name",name:"name"},
      {data:"hutang_bayar",name:"hutang_bayar", render: $.fn.dataTable.render.number( ',', '.', 2 ), className: "text-right"},
      {data:"jan",name:"jan", render: $.fn.dataTable.render.number( ',', '.', 2 ), className: "text-right"},
      {data:"feb",name:"feb", render: $.fn.dataTable.render.number( ',', '.', 2 ), className: "text-right"},
      {data:"mar",name:"mar", render: $.fn.dataTable.render.number( ',', '.', 2 ), className: "text-right"},
      {data:"apr",name:"apr", render: $.fn.dataTable.render.number( ',', '.', 2 ), className: "text-right"},
      {data:"mei",name:"mei", render: $.fn.dataTable.render.number( ',', '.', 2 ), className: "text-right"},
      {data:"jun",name:"jun", render: $.fn.dataTable.render.number( ',', '.', 2 ), className: "text-right"},
      {data:"jul",name:"jul", render: $.fn.dataTable.render.number( ',', '.', 2 ), className: "text-right"},
      {data:"agu",name:"agu", render: $.fn.dataTable.render.number( ',', '.', 2 ), className: "text-right"},
      {data:"sep",name:"sep", render: $.fn.dataTable.render.number( ',', '.', 2 ), className: "text-right"},
      {data:"okt",name:"okt", render: $.fn.dataTable.render.number( ',', '.', 2 ), className: "text-right"},
      {data:"nov",name:"nov", render: $.fn.dataTable.render.number( ',', '.', 2 ), className: "text-right"},
      {data:"des",name:"des", render: $.fn.dataTable.render.number( ',', '.', 2 ), className: "text-right"},
      {data:"tahunan",name:"tahunan", render: $.fn.dataTable.render.number( ',', '.', 2 ), className: "text-right"},
      {data:"total_sisa",name:"total_sisa", render: $.fn.dataTable.render.number( ',', '.', 2 ), className: "text-right"},
            ],
    "order": false,
    })

    $('#table_con_cost').DataTable({

    "paging":false,
    "destroy": true,
    "autoWidth": true,
    "scrollX": true,  
    "searching": false,
    "responsive": true,
    "columns":[
      {data:"name",name:"name"},
      {data:"hutang_bayar",name:"hutang_bayar", render: $.fn.dataTable.render.number( ',', '.', 2 ), className: "text-right"},
      {data:"jan",name:"jan", render: $.fn.dataTable.render.number( ',', '.', 2 ), className: "text-right"},
      {data:"feb",name:"feb", render: $.fn.dataTable.render.number( ',', '.', 2 ), className: "text-right"},
      {data:"mar",name:"mar", render: $.fn.dataTable.render.number( ',', '.', 2 ), className: "text-right"},
      {data:"apr",name:"apr", render: $.fn.dataTable.render.number( ',', '.', 2 ), className: "text-right"},
      {data:"mei",name:"mei", render: $.fn.dataTable.render.number( ',', '.', 2 ), className: "text-right"},
      {data:"jun",name:"jun", render: $.fn.dataTable.render.number( ',', '.', 2 ), className: "text-right"},
      {data:"jul",name:"jul", render: $.fn.dataTable.render.number( ',', '.', 2 ), className: "text-right"},
      {data:"agu",name:"agu", render: $.fn.dataTable.render.number( ',', '.', 2 ), className: "text-right"},
      {data:"sep",name:"sep", render: $.fn.dataTable.render.number( ',', '.', 2 ), className: "text-right"},
      {data:"okt",name:"okt", render: $.fn.dataTable.render.number( ',', '.', 2 ), className: "text-right"},
      {data:"nov",name:"nov", render: $.fn.dataTable.render.number( ',', '.', 2 ), className: "text-right"},
      {data:"des",name:"des", render: $.fn.dataTable.render.number( ',', '.', 2 ), className: "text-right"},
      {data:"tahunan",name:"tahunan", render: $.fn.dataTable.render.number( ',', '.', 2 ), className: "text-right"},
      {data:"total_sisa",name:"total_sisa", render: $.fn.dataTable.render.number( ',', '.', 2 ), className: "text-right"},
            ],
    "order": false,
    })
    $(document).on('click', '#rekap', function() {
      var url = "{{ url('/')}}/budget/rekap";
      $('#table_dev_cost').DataTable().clear().draw();
      $('#table_con_cost').DataTable().clear().draw();
      $.ajax({
          type: 'post',
          dataType: 'json',
          url: url,
          data: {
              project_id: $("#project_id").val(),
              department_id: $("#department").val(),
          },
          beforeSend: function() {
              waitingDialog.show();
          },
          success: function(data) {
            // console.log(data.data.DC.length);
              if (Object.entries(data.data.DC).length > 0) {
                // console.log(data.data.DC);
                  $(Object.entries(data.data.DC)).each(function(i, v) { 
                      console.log(i);
                      if(v["1"].name != "-"){
                        var ItemTable = {
                            name: v["1"].name,
                            hutang_bayar: v["1"].hutang_bayar,
                            jan: v["1"]["1"],
                            feb: v["1"]["2"],
                            mar: v["1"]["3"],
                            apr: v["1"]["4"],
                            mei: v["1"]["5"],
                            jun: v["1"]["6"],
                            jul: v["1"]["7"],
                            agu: v["1"]["8"],
                            sep: v["1"]["9"],
                            okt: v["1"]["10"],
                            nov: v["1"]["11"],
                            des: v["1"]["12"],
                            tahunan: v["1"].tahunan,
                            total_sisa: v["1"].total_sisa,
                        };
                        $('#table_dev_cost').DataTable().row.add(ItemTable);
                      }
                    });
              }
              $('#table_dev_cost').DataTable().draw();
              // var api = $('#table_dev_cost').DataTable();
              // var rows = api.rows( {page:'current'} ).nodes();
              // api.column(0, {page:'current'} ).data().each( function ( group, i ) {
              //   if ( "Carry Over Baru" == group || "Carry Over Lama" == group) {
              //     $(rows).eq(i).before(
              //         '<tr class="group" style="background-color: white;""><td"><strong>Carry Over</strong>/td></tr>'
              //     );
              //   }
              // });
              // $('.group').nextUntil('.group').css( "display", "none" );

              if (Object.entries(data.data.CC).length > 0) {
                  $(Object.entries(data.data.CC)).each(function(i, v) { 
                    if(v["1"].name != "-"){
                        var ItemTable = {
                            name: v["1"].name,
                            hutang_bayar: v["1"].hutang_bayar,
                            jan: v["1"]["1"],
                            feb: v["1"]["2"],
                            mar: v["1"]["3"],
                            apr: v["1"]["4"],
                            mei: v["1"]["5"],
                            jun: v["1"]["6"],
                            jul: v["1"]["7"],
                            agu: v["1"]["8"],
                            sep: v["1"]["9"],
                            okt: v["1"]["10"],
                            nov: v["1"]["11"],
                            des: v["1"]["12"],
                            tahunan: v["1"].tahunan,
                            total_sisa: v["1"].total_sisa,
                        };
                      $('#table_con_cost').DataTable().row.add(ItemTable);
                    }
                  });
              }

              $('#table_con_cost').DataTable().draw();
              document.getElementById("project").innerHTML = data.project_name;
              document.getElementById("periode_tahun").innerHTML = data.year;
              document.getElementById("total").innerHTML = "Rp."+data.total_hutang;
              document.getElementById("total_sisa").innerHTML = "Rp."+data.total_sisa;
              // $("#table_con_cost").find('tr').addClass('test');
              // $('#table_con_cost').DataTable().columns.adjust();
              // tbody = $('#table_edit');
              //   tbody.find('.harga_satuan_edit').each(function (i, v) {
              //     fnSetAutoNumeric($(this));
              //     fnSetMoney($(this), $(this).val());
              //   }); 
              //   tbody.find('.harga_subtotal_edit').each(function (i, v) {
              //     fnSetAutoNumeric($(this));
              //     fnSetMoney($(this), $(this).val());
              //   });

          },
          complete: function() {
              waitingDialog.hide();
              
          }
      });
      $('#ModalRekap').modal('show');
    });
</script>
</body>
</html>
