<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Admin QS | Dashboard</title>
  @include("master/header")
</head>
<body class="hold-transition skin-blue sidebar-mini">
<input type="hidden" name="budget_cashout" id="budget_cashout" value="{{ $budget_cashout}}">
<input type="hidden" name="budget_carryover" id="budget_carryover" value="{{ $budget_carry_over}}">
<input type="hidden" name="real_bulanan" id="real_bulanan" value="{{ $real_bulanan}}">
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
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3>{{ count($project->spks )}}</h3>
              <p>SPK</p>
            </div>
            <div class="icon">
              <i class="ion ion-bag"></i>
            </div>
            <a href="{{ url('/')}}/spks" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3>{{ number_format( $project->percentage_budget,2 )}}<sup style="font-size: 20px">%</sup></h3>
              <p>Realiasasi</p>
            </div>
            <div class="icon">
              <i class="ion ion-stats-bars"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3>{{ number_format(count($project->total_rekanan))}}</h3>
              <p>Rekanan</p>
            </div>
            <div class="icon">
              <i class="ion ion-person-add"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3>{{ number_format($project->total_bap) }}</h3>
              <p>BAP</p>
            </div>
            <div class="icon">
              <i class="ion ion-pie-graph"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
      </div>
      <!-- /.row -->

      <!-- Main row -->

      <div class="row">
        <!-- Left col -->
        <section class="col-lg-7 connectedSortable">
          <!-- Custom tabs (Charts with tabs)-->
          <div class="nav-tabs-custom">
            <!-- Tabs within a box -->
            <ul class="nav nav-tabs pull-right">
              <li class="active"><a href="#revenue-chart" data-toggle="tab">All </a></li><!-- 
              <li><a href="#chart1" data-toggle="tab">CarryOut SPK </a></li>
              <li><a href="#chart2" data-toggle="tab">CashOut CarryOver</a></li>
              <li><a href="#chart3" data-toggle="tab">CashOut Realisasi</a></li> -->
            </ul>
            <div class="tab-content no-padding">
              <div class="chart tab-pane active" id="revenue-chart" style="position: relative; height: 300px;">
                <center><h4>All</h4></center>
                <div class="chart">
                  <canvas id="lineChart" style="height:250px"></canvas>
                </div>
              </div>
              <div class="chart tab-pane" id="chart1" style="position: relative; height: 300px;">
                <h4>Cash Out Rencana SPK</h4>
                <div class="chart">
                  <canvas id="areaChart1" style="height:250px"></canvas>
                </div>
              </div>
              <div class="chart tab-pane" id="chart2" style="position: relative; height: 300px;">
                <h4>Cash Out Carry Over</h4>
                <div class="chart">
                  <canvas id="areaChart2" style="height:250px"></canvas>
                </div>
              </div>
              <div class="chart tab-pane" id="chart3" style="position: relative; height: 300px;">
                <h4>Realisasi</h4>
                <div class="chart">
                  <canvas id="areaChart3" style="height:250px"></canvas>
                </div>
              </div>
              <div class="box-body">
                <small class="label label-danger">&nbsp;&nbsp;</small>Cash Out SPK<br>                
                <small class="label label-primary">&nbsp;&nbsp;</small>Cash Out CarryOver<br>              
                <small class="label label-warning">&nbsp;&nbsp;</small>Realisasi<br><br>
                <table class="table">
                  <thead class="head_table">
                    <tr>
                      <td>Jenis</td>
                      <td>Rencana</td>
                      <td>Realisasi</td>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>Budget Cash Out SPK</td>
                      <td style="text-align: right;">{{ number_format($nilai_cash_out)}}</td>
                      <td style="text-align: right;">{{ number_format(0)}}</td>
                    </tr>
                    <tr>
                      <td>Budget Cash Out Carry Over</td>
                      <td style="text-align: right;">{{ number_format($nilai_carry_over)}}</td>
                      <td style="text-align: right;">{{ number_format(0)}}</td>
                    </tr>
                  </tbody>
                </table>
                <a class="btn btn-primary" href="{{ url('/')}}/report/cashflow/?id={{$project->id}}">Detail</a>
              </div>
            </div>
          </div>
          <!-- /.nav-tabs-custom -->
          <!-- TO DO List -->
          <div class="box box-primary">
            <div class="box-header">
              <i class="ion ion-clipboard"></i>
              <h3 class="box-title">Data Umum</h3>      
            </div>
            <!-- /.box-header -->
            <div class="box-body">
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
              <h4>Total Budget Devcost    : Rp. {{ number_format($project->total_budget,2) }} </h4>
              <h4>Total Kontrak Devcost  : Rp. {{ number_format($project->total_nilai_kontrak,2) }} </h4>
              <h4>Total Kontrak Devcost yang dibayar : Rp. {{ number_format($project->dev_cost_terbayar,2) }} </h4>

              <h4>Hutang Bangun Devcost dan Hutang Bayar Devcost: Rp. {{ number_format( ($project->total_budget - $project->total_nilai_kontrak) + ($project->total_nilai_kontrak - $project->dev_cost_terbayar) ,2)}}

              @if ( $project->netto <= 0 )


              <h4>HPP Dev Cost  : Rp. {{ number_format(0,2) }} / m2</h4>
              @else

              <h4>HPP Dev Cost  : Rp. {{ number_format($hpp_akhir = ( $project->total_budget )/ $project->netto,2) }} / m2</h4>
              @endif
              <br>
              <h4>Total Kontrak Concost  : Rp. {{ number_format(0,2) }} </h4>
              <h4>Total Kontrak Concost yang dibayar : Rp. {{ number_format(0,2) }} </h4>
              <h4>Hutang Bangun Concost (0 unit) dan Hutang Bayar Concost : Rp. {{ number_format( 0,2)}}</h4>
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
         <!-- /.box -->     
        </section>

        <!-- /.Left col -->
        <!-- right col (We are only adding the ID to make the widgets sortable)-->
        <section class="col-lg-5 connectedSortable">

          <!-- TO DO List -->
          <div class="box box-primary">
            <div class="box-header">
              <i class="ion ion-clipboard"></i>

              <h3 class="box-title">To Do List</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered">
                <thead class="head_table">
                  <tr>
                    <td colspan="2">Pekerjaan</td>
                  </tr>
                </thead>
                <tbody id="todo_list">
                  <tr>
                    <td colspan="2">&nbsp;</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <!-- /.box -->
         <!-- Calendar -->
          <div class="box box-solid bg-green">
            <div class="box-header">
              <i class="fa fa-calendar"></i>
              <h3 class="box-title">Calendar</h3>
              <!-- tools box -->
              <div class="pull-right box-tools">
                <!-- button with a dropdown -->
                <div class="btn-group">
                  <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown">
                    <i class="fa fa-bars"></i></button>
                  <ul class="dropdown-menu pull-right" role="menu">
                    <li><a href="#">Add new event</a></li>
                    <li><a href="#">Clear events</a></li>
                    <li class="divider"></li>
                    <li><a href="#">View calendar</a></li>
                  </ul>
                </div>
                <button type="button" class="btn btn-success btn-sm" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-success btn-sm" data-widget="remove"><i class="fa fa-times"></i>
                </button>
              </div>
              <!-- /. tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
              <!--The calendar -->
              <div id="calendar" style="width: 100%"></div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

          <div class="box box-solid">
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
                  <td style="text-align: right;">{{ number_format($project->dev_cost_terbayar,2) }}</td>
                </tr>
                <tr>
                  <td>Dev Cost yang sudah dibebankan ke HPP (Rp)</td>
                  <td style="text-align: right;">({{ number_format($project->dev_cost_dibebankan,2)}})</td>
                </tr>
                <tr>
                  <td>Persediaan Dev Cost (Rp)</td>
                  <td style="text-align: right;">{{ number_format( $project->persediaan_dev_cost ,2)}}</td>
                </tr>
                <tr>
                  <td>Hutang Bayar (Rp)</td>
                  <td style="text-align: right;">{{ number_format( $project->hutang_bayar ,2) }}</td>
                </tr>
                <tr>
                  <td>Hutang Bangun (Rp)</td>
                  <td style="text-align: right;">{{ number_format($project->hutang_bangun ,2) }}</td>
                </tr>
                <tr>
                  <td>Total DevCost (Rp)</td>
                  <td style="text-align: right;">{{ number_format($project->total_devcost,2) }}</td>
                </tr>
                <tr>
                  <td>Luas Gross (m2)</td>
                  <td style="text-align: right;">{{ number_format($project->luas_gross_hpp,2)}} </td>
                </tr>
                 <tr>
                  <td>Luas Rencana Netto (m2)</td>
                  <td style="text-align: right;">{{ number_format($project->luas_rencana_netto_hpp,2)}}</td>
                </tr>
                <tr>
                  <td>Luas yang belum dibukukan ( Sales backlog ) (m2)</td>
                  <td style="text-align: right;">{{ number_format( $project->sales_back_log,2) }}</td>
                </tr>
                <tr>
                  <td>Total Luas Stock Netto (m2)</td>
                  <td style="text-align: right;">{{ number_format( $project->total_stock ,2) }}</td>
                </tr>
                <tr>
                  <td>Nilai Analisa HPP Devcost</td>
                  <td style="text-align: right;">{{ number_format( $project->hpp_devcost_upd ,2 ) }}</td>
                  <input type="hidden" id="tmp_hpp" name="tmp_hpp" value="{{ number_format($project->hpp_devcost_upd,2) }}">
                  <input type="hidden" id="tmp_budget" value="{{ number_format($project->total_devcost,2) }}">
                </tr>
              </thead>
            </table>
            </form>
          </div>
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
  $("#budget_update").text($("#tmp_budget").val());
  $("#hpp_update").text($("#tmp_hpp").val());

  $("#hpp_update").number(true,2);
  $("#budget_update").number(true,2);
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
    })
  });
</script>
@include("report::chart")
</html>

