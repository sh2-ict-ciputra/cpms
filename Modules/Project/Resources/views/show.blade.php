<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Admin QS | Dashboard</title>
  @include("master/header")
</head>
<body class="hold-transition skin-blue sidebar-mini">
{{ csrf_field() }}
<input type="hidden" name="project_id" id="project_id" value="{{$project->id}}">
<div class="wrapper">
  @include("master/sidebar_project")
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1> Proyek : <strong>{{ $project->name }}</strong></h1>  
    </section>
   <!-- Main content -->
    <section class="content">
       <!-- Small boxes (Stat box) -->
      <div class="row">
       
        <!-- ./col -->
      </div>
      <!-- /.row -->

      <!-- Main row -->

      <div class="row">
       
        <!-- /.Left col -->
        <!-- right col (We are only adding the ID to make the widgets sortable)-->
        <section class="col-lg-12 connectedSortable">

          <!-- TO DO List -->
          <div class="box box-primary">
            <div class="box-header">
              <i class="ion ion-clipboard"></i>

              <h3 class="box-title">To Do List</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered">
                <thead class="head_table" style="background-color:#3c8dbc;">
                  <tr>
                    <td>Dokumen</td>
                    <td>Pekerjaan</td>
                    <td>&nbsp;</td>
                  </tr>
                </thead>
                <tbody id="todo_list">
                  <tr>
                    <td></td>
                    <td>&nbsp;</td>
                  </tr>
                </tbody>
              </table>

              <h4>Rumah Terjual belum SPK</h4>
              <table id="example2" class="table table-bordered">
                <thead class="head_table">
                  <tr>
                    <td>Serah Terima Dalam</td>
                    <td>Jumlah Unit</td>
                    <td>Detail</td>
                  </tr>
                </thead>
                <tbody>
                  @foreach ( $array_serah_terima as $key => $value )
                  <tr>
                    <td>{{ $value['label'] }} bln</td>
                    <td>{{ count($value['unit_id']) }} unit</td>
                    <td><a href="{{url('/')}}/project/unitsold?bln={{$key}}" class="btn btn-primary">Detail</a></td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            <div class="box-body">
              <div class="col-md-6">
                @php $total = 0; $hpp_akhir = 0;@endphp
                @foreach ( $project->budgets as $key => $value )
                @if ( $value->deleted_at == "" )
                  @php $total = $total + $value->total_dev_cost;@endphp
                @endif
                @endforeach
                <h4>Luas Brutto yang belum ada site plan (m2)   : {{ number_format($project->luas_nonpengembangan,2) }} m2 </h4>
                <h4>Luas Brutto yang ada site plan(m2)   : {{ number_format($project->luas)}} m2 </h4>
                <h4>Luas Netto    : {{ number_format($project->netto)}} m2</h4>
                @if ( $project->luas > 0 )
                <h4>Sellable      : {{ number_format(($project->netto / $project->luas) * 100 ,2) }} %</h4>
                @else
                <h4>Sellable      : {{ number_format(0 ,2) }} %</h4>
                @endif
                
                <br/>
                <h4>Nilai Dev Cost ( SPK + Sisa Renc. ): Rp. {{ number_format($total_devcost = $project->total_dev_cost_con_cost["TotalDevCost"] + $project->total_spk_detail["total_nilai_spk_dc"])}}</h4>
                <h4>Nilai Budget Sisa Renc. (DC) : Rp. {{ number_format($project->total_dev_cost_con_cost["TotalRencanaDevCost"])}}</h4>
                <h4>Sisa Pembayaran SPK (DC) : Rp. {{ number_format($project->total_spk_detail["sisa_spk_dc"])}}</h4>
                @if ( $project->netto == "0")
                  <h4>HPP Dev Cost  : Rp. {{ number_format($hpp = 0,2) }} / m2</h4>
                @else
                  <h4>HPP Dev Cost  : Rp. {{ number_format($hpp = $total_devcost/$project->netto,2)}} / m2</h4> 
                @endif


                <br/>
                <h4>Nilai Con Cost ( SPK + Sisa Renc. ): Rp. {{ number_format($project->total_dev_cost_con_cost["TotalConCost"] + $project->total_spk_detail["total_nilai_spk_cc"])}}</h4>
                <h4>Nilai Budget Sisa Renc. (CC) : Rp. {{ number_format($project->total_dev_cost_con_cost["TotalRencanaConCost"])}}</h4>
                <h4>Sisa Pembayaran SPK (CC) : Rp. {{ number_format($project->total_spk_detail["sisa_spk_cc"])}}</h4>
              </div>
              <div class="box box-solid col-md-6">
                <h4>Input Luas Tanah yang sudah dibukukan</h4>
                <form action="{{ url('/')}}/project/save-hppupdate" method="post" name="form1">
                  {{ csrf_field() }}
                  <div class="form-group">
                    <label>Luas EREM</label>
                    <input type="hidden" name="project_id" id="project_id" value="{{ $project->id }}">
                    <input type="text" class="form-control" name="luas_erem" autocomplete="off">
                  </div>
                  <div class="form-group">
                    <label>Luas Book</label>
                    <input type="hidden" name="project_id" value="{{ $project->id }}">
                    <input type="text" class="form-control" name="luas_book" autocomplete="off">
                  </div>
                  <div class="form-group">
                    <button class="btn-primary btn" type="submit">Simpan</button>
                  </div>
                
                <h3>Rekap HPP Devcost</h3>

                <table class="table table-borderd">
                  <thead class="head_table">
                    <tr>
                      <td></td>
                      <td>Budget(Rp)</td>
                      <td>HPP(Rp/m2)</td>
                    </tr>
                  </thead>
                  <tbody>

                  @if ( count($project->hpp_update) > 0 )
                    @if ( $project->hpp_update->first()->netto > 0 )
                    <tr>
                      <td>HPP Awal</td>
                      <td>{{ number_format($project->hpp_update->first()->nilai_budget,2)}}</td>
                      <td>{{ number_format( $project->hpp_update->first()->nilai_budget / $project->hpp_update->first()->netto ,2) }}</td>
                    </tr>
                    <!--tr>
                      <td>HPP Update QS</td>
                      <td>{{ number_format($project->hpp_update->last()->nilai_budget,2)}}</td>
                      <td>{{ number_format( $project->hpp_netto_akhir,2)}}</td>
                    </tr-->
                    <tr>
                      <td>HPP Update Accounting</td>
                      <td><span id="budget_update"></span></td>
                      <td><span id="hpp_update"></span></td>
                    </tr>
                    @else
                    <tr>
                      <td>HPP Awal</td>
                      <td>{{ number_format($project->hpp_update->first()->nilai_budget,2)}}</td>
                      <td>{{ number_format(0,2) }}</td>
                    </tr>
                    <!--tr>
                      <td>HPP Update QS</td>
                      <td>{{ number_format($project->hpp_update->last()->nilai_budget,2)}}</td>
                      <td>{{ number_format( 0,2)}}</td>
                    </tr-->
                    <tr>
                      <td>HPP Update Accounting</td>
                      <td><span id="budget_update"></span></td>
                      <td><span id="hpp_update"></span></td>
                    </tr>
                    @endif
                  @endif
                  </tbody>
                </table>

                <table class="table table-bordered">
                  <thead class="head_table">
                    <tr>
                      <td>Dev Cost yang sudah dibayar (Rp)</td>
                      <td style="text-align: right;">{{ number_format($devcost_sudahbayar = $project->total_spk_sudah_cair,2) }}</td>
                    </tr>
                    <tr>
                      <td>Dev Cost yang sudah dibebankan ke HPP (Rp)</td>
                      <td style="text-align: right;">({{ number_format($devcost_dibebankan = $luasbook*$hpp,2)}})</td>
                    </tr>
                    <tr>
                      <td>Persediaan Dev Cost (Rp)</td>
                      <td style="text-align: right;">{{ number_format($persediaan = $devcost_sudahbayar - $devcost_dibebankan,2)}}</td>
                    </tr>
                    <tr>
                      <td>Hutang Bayar (Rp)</td>
                      <td style="text-align: right;">{{ number_format($hutang_bayar = $project->total_spk_detail["sisa_spk_dc"])}}</td>
                    </tr>
                    <tr>
                      <td>Hutang Bangun (Rp)</td>
                      <td style="text-align: right;">{{ number_format($hutang_bangun = $project->total_dev_cost_con_cost["TotalRencanaDevCost"] ,2) }}</td>
                    </tr>
                    <tr>
                      <td>Total DevCost (Rp)</td>
                      <td style="text-align: right;">{{ number_format($devcost = $persediaan+$hutang_bayar+$hutang_bangun,2) }}</td>
                    </tr>
                    <tr>
                      <td>Luas Gross (m2)</td>
                      <td style="text-align: right;">{{ number_format($project->luas_nonpengembangan,2)}} </td>
                    </tr>
                    <tr>
                      <td>Luas Rencana Netto (m2)</td>
                      <td style="text-align: right;">{{ number_format($project->netto,2)}}</td>
                    </tr>
                    <tr>
                      <td>Luas yang belum dibukukan ( Sales backlog ) (m2)</td>
                      <td style="text-align: right;">{{ number_format( $project->netto-$luasbook,2) }}</td>
                    </tr>
                    <tr>
                      <td>Total Luas Stock Netto (m2)</td>
                      <td style="text-align: right;">{{ number_format($luas_stock_netto = $project->total_stock ,2) }}</td>
                    </tr>
                    <tr>
                      <td>Nilai Analisa HPP Devcost</td>
                      @if($luas_stock_netto != 0)
                        <td style="text-align: right;">{{ number_format( $devcost/$luas_stock_netto ,2 ) }}</td>
                      @else
                        <td style="text-align: right;">{{ number_format( 0 ,2 ) }}</td>
                      @endif
                      <input type="hidden" id="tmp_hpp" name="tmp_hpp" value="{{ number_format($project->hpp_devcost_upd,2) }}">
                      <input type="hidden" id="tmp_budget" value="{{ number_format($project->total_devcost,2) }}">
                    </tr>
                  </thead>
                </table>
                </form>
              </div>
              <!-- <h4>Total Budget Devcost    : Rp. {{ number_format($project->total_budget,2) }} </h4>
              <h4>Total Kontrak Devcost  : Rp. {{ number_format($project->total_nilai_kontrak,2) }} </h4>
              <h4>Total Kontrak Devcost yang dibayar : Rp. {{ number_format($project->dev_cost_terbayar,2) }} </h4>

              <h4>Hutang Bangun Devcost dan Hutang Bayar Devcost: Rp. {{ number_format( ($project->total_budget - $project->total_nilai_kontrak) + ($project->total_nilai_kontrak - $project->dev_cost_terbayar) ,2)}} -->

              <!-- @if ( $project->netto <= 0 )


              <h4>HPP Dev Cost  : Rp. {{ number_format(0,2) }} / m2</h4>
              @else

              <h4>HPP Dev Cost  : Rp. {{ number_format($hpp_akhir = ( $project->total_budget )/ $project->netto,2) }} / m2</h4>
              @endif -->
              <!-- <br> -->
              <!-- <h4>Total Kontrak Concost  : Rp. {{ number_format(0,2) }} </h4>
              <h4>Total Kontrak Concost yang dibayar : Rp. {{ number_format(0,2) }} </h4>
              <h4>Hutang Bangun Concost (0 unit) dan Hutang Bayar Concost : Rp. {{ number_format( 0,2)}}</h4> -->
              <div class="col-md-12">
                <h4>HPP Con Cost </h4>
                <table class="table table-bordered">
                  <thead class="head_table">
                    <tr>
                      <td>Tipe Bangunan (LB/LT)</td>
                      <td>Kategori Bangunan</td>
                      <td>HPP Awal(Rp/m2)</td>
                      <td>Real (Rp/m2)</td>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ( $project->unittype as $key => $value )
                    <tr>
                      <td>{{ $value->name or ''}} &nbsp;{{ $value->luas_bangunan}} / {{ $value->luas_tanah }}</td>
                      <td>{{ $value->category->category_project->category_detail->category->name or ''}} / {{ $value->category->category_project->category_detail->sub_type or ''}}</td>
                      @if ( $value->category != "" )
                      <td>{{ number_format($value->category->nilai,2)}}</td>
                      @else
                      <td>{{ number_format(0,2)}}</td>
                      @endif
                      <td>0</td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>

            </div>
          </div>

          <!-- <div class="box box-primary col-md-6 " >
            <div class="box-header">
              <i class="ion ion-clipboard"></i>
              <h3 class="box-title">Data Umum</h3>      
            </div>

          </div>
          <-- /.box -->
         

        </section>
        <!-- right col -->
      </div>
      <!-- /.row (main row) -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>

<!-- ./wrapper -->



</body>
@include("master/footer")
<script type="text/javascript">
   $.ajaxSetup({
      headers: {
          'X-CSRF-Token': $('input[name=_token]').val()
      }
    });
  $( document ).ready(function() {
    var request = $.ajax({
      url : "{{ url('/')}}/project/todolist",
      dataType : "json",
      data : {
        id : $("#project_id").val()
      },
      type : "post"
    });

    request.done(function(data){
      $("#todo_list").html(data.html);
      $('#example1').DataTable({
        'paging'      : false,
        'lengthChange': false,
        'searching'   : true,
        'ordering'    : false,
        'info'        : true,
        'autoWidth'   : false,
        "drawCallback": function ( settings ) {
            var api = this.api();
            var rows = api.rows( {page:'current'} ).nodes();
            var last=null;

            api.column(0, {page:'current'} ).data().each( function ( group, i ) {
                if ( last !== group ) {
                  console.log(group);
                  console.log(i);
                    if (group == "<strong>PurchaseRequest</strong>" || group == "<strong>OE PR</strong>" || group == "<strong>Tender PR</strong>" || group == "<strong>PurchaseOrder</strong>"){
                      console.log(group);
                      $(rows).eq( i ).before(
                        '<tr style="background-color: #ffc2c8;" class="group"><td colspan="3"><strong>'+group+'</strong></td></tr>'
                      );
                    }else{
                      $(rows).eq( i ).before(
                          '<tr style="background-color: #3FD5C0;" class="group"><td colspan="3"><strong>'+group+'</strong></td></tr>'
                      );
                    }

                    last = group;
                }
            } );
        },
        "initComplete": function(settings, json) {
            $('.group').nextUntil('.group').css( "display", "none" );
        }
      });

      var tbody = $('#example1 tbody');
      tbody.on('click','.group',function()
      {
        $(this).nextUntil('.group').toggle();

      }).find('.group').each(function(i,v){
        var rowCount = $(this).nextUntil('.group').length;
        $(this).find('td:first').append($('<span />', { 'class': 'rowCount-grid' }).append($('<b />', { 'text': ' (' + rowCount + ')' })));
      });
    })
  });
</script>

<!-- DataTables -->
<script src="{{ url('/')}}/assets/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="{{ url('/')}}/assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script>
  $(function () {
    $('#example2').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : true,
      'ordering'    : false,
      'info'        : true,
      'autoWidth'   : false
    });
  })

  $('input').attr('autocomplete','off');
</script>
</html>

