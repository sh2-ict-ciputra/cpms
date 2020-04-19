<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Admin QS | Dashboard </title>
  @include("master/header")
  <!-- Select2 -->  
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="{{ url('/')}}/assets/bower_components/select2/dist/css/select2.min.css">
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  @include("master/sidebar_project")

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>Data Proyek <strong>{{ $budget->project->name }}</strong></h1>

    </section>

    <!-- Main content -->
    <section class="content">

      <!-- SELECT2 EXAMPLE -->
      <div class="box box-default">

        <!-- /.box-header -->
        <div class="box-body">
          <div class="row">
            <div class="col-md-12"><h3 class="box-title">Detail Data Budget Tahunan Proyek</h3></div>
            <div class="col-md-6">             
              <form action="{{ url('/')}}/budget/cashflow/update-cashflow" method="post" name="form1">
                {{ csrf_field() }}
                <input type="hidden" name="budget_id" id="budget_id" value="{{ $budget->id }}">
                <input type="hidden" name="budget_tahunan_id" id="budget_tahunan_id" value="{{ $budget_tahunan->id }}">
                <div class="form-group">
                  <label>No. Budget Global</label>
                  <input type="text" class="form-control" value="{{ $budget->no }}" readonly>
                </div>
                <div class="form-group">
                  <label>No. Budget</label>
                  <input type="text" class="form-control" value="{{ $budget_tahunan->no }}" readonly>
                </div>
                <div class="form-group">
                <label>Project / Kawasan</label>
                <input type="text" class="form-control" value="{{ $budget->project->name }} / {{ $budget->kawasan->name or ''}}" readonly>
              </div> 
                <div class="box-footer">
                  @if ( $budget_tahunan->approval != "" )
                    @if (  $budget_tahunan->approval->approval_action_id == 7 )
                      <button type="submit" class="btn btn-primary">Simpan</button>
                    @endif
                  @else                    
                      <button type="submit" class="btn btn-primary">Simpan</button>
                  @endif
                  <a href="{{ url('/')}}/budget/cashflow/?id={{ $budget_tahunan->budget->id}}" class="btn btn-warning">Kembali</a>
                  @if ( $budget_tahunan->approval != "" )
                  <a class="btn btn-info" href="{{ url('/')}}/budget/cashflow/approval?id={{$budget_tahunan->id}}">Lihat History Approval</a><br>
                  @endif
                </div>      
             
            </div>
            <div class="col-md-6">

                <div class="form-group">
                  <label>Nilai(Rp)</label>
                  <input type="text" class="form-control" value="{{ number_format($budget_tahunan->nilai_tahunan)}}" readonly>
                </div> 
                <div class="form-group">
                  <label>Tahun Anggaran</label>
                  <select name="tahun_anggaran" class="form-control">
                    @for($i=$start_date; $i <= $end_date; $i++ )
                      @if ( $budget_tahunan->tahun_anggaran == $i )
                        <option value="{{ $i }}" selected>{{ $i }}</option>
                      @else
                        <option value="{{ $i }}">{{ $i }}</option>
                      @endif
                    @endfor
                  </select>
                </div>
                <div class="form-group">
                  <label>Keterangan</label>
                  <input type="text" name="description" id="description" class="form-control">
                </div>
            </div>
             </form>
            <!-- /.col -->
            
            <div class="col-md-12">
              <div class="nav-tabs-custom"> 
                <ul class="nav nav-tabs">                      
                  <li class="active"><a href="#tab_1" data-toggle="tab" >Rekap Budget Cashout Bln</a></li>
                  <li ><a href="#tab_2" data-toggle="tab">Data Budget Spk Thn</a></li>
                  <li ><a href="#tab_3" data-toggle="tab">Carry Over</a></li>
                  <li ><a href="#tab_4" data-toggle="tab">Rekap Cashout Carry Over</a></li>
                  <li ><a href="#tab_5" data-toggle="tab">Rekap Keseluruhan</a></li>
                </ul>
                <div class="tab-content"> 
                  <div class="tab-pane active" id="tab_1">
                    <div class="row">
                      <div class="col-md-12">  
                        <div class="col-md-12 table-responsive">
                          <table class="table table-bordered table-striped" style="padding: 0" id="example3">
                            <thead class="head_table">
                              <tr>
                                <td>COA</td>
                                <td>Item Pekerjaan</td>
                                <td>Uraian Pekerjaan</td>
                                <td>Total Budget Tahunan</td>
                                <td>Total Budget Bulanan</td>
                                <td>Jan</td>
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
                              </tr>
                            </thead>
                            <tbody>
                              @foreach ( $budget_tahunan->details as $key => $value )
                                <tr>
                                  <td>{{ $value->itempekerjaans->code or '' }}</td>
                                  <td>{{ $value->itempekerjaans->name }}</td>
                                  <td>{{ $value->uraian_pekerjaan }}</td>
                                  <td>{{ number_format($subtotal = $value->volume * $value->nilai,2)}}</td>
                                  <td>{{ number_format($value->detail_bulanan->sum('nilai_persen'),2)}}</td>
                                  @for($i=1; $i<=12; $i++)
                                    @php
                                      $nilai_per_bulan = $value->detail_bulanan->where('bulan',$i)->first()->nilai_persen;
                                    @endphp
                                    <td>{{number_format($nilai_per_bulan,2) }}</td>
                                  @endfor
                                  <!-- <td>Jan</td>
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
                                  <td>Des</td> -->
                                </tr>
                              @endforeach
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="tab-pane" id="tab_2">
                    <div class="row">
                      <div class="col-md-12">  
                        <div class="col-md-12 table-responsive">
                          <button type="submit" class="btn btn-info" style="margin: 10px 10px 10px 10px" id="edit_tahunan_detail">Edit</button>
                          <table class="table" style="padding: 0" id="example3">
                            <thead class="head_table">
                              <tr>
                                <td>COA</td>
                                <td>Item Pekerjaan</td>
                                <td>Uraian Pekerjaan</td>
                                <td>Volume Budget Global</td>
                                <td>Volume Budget Spk Thn</td>
                                <td>Satuan</td>
                                <td>Harga Satuan(Rp)</td>
                                <td>Subtotal(Rp)</td>
                                <td>Budget Cashout Bln</td>
                              </tr>
                            </thead>
                            <tbody>
                              @foreach ( $budget_tahunan->details as $key => $value )
                                @if ( $value->volume > 0 )
                                  @php $class = "class_1"; @endphp
                                @else
                                  @php $class = "class_0"; @endphp
                                @endif
                                <tr class="nilai {{ $class }}">
                                  <td>
                                    @if ( $value->itempekerjaans->code == "225.01" || $value->itempekerjaans->code == "225.04" || $value->itempekerjaans->code == "225.05" )                    
                                      {{ $value->itempekerjaans->code or '' }}
                                    @else
                                      {{ $value->itempekerjaans->code or '' }}
                                    @endif
                                  </td>
                                  <td>{{ $value->itempekerjaans->name }}</td>
                                  <td>{{ $value->uraian_pekerjaan }}</td>
                                  <td>
                                    <input type="text" name="volume_global" id="volume_global" style="" class="form-control" value="{{ $value->volume_budget }}" Readonly>
                                  </td>
                                  <td>
                                    <input type="text" name="volume_tahunan" id="volume_tahunan" style="" class="form-control" value="{{ $value->volume }}" readonly>
                                  </td>
                                  <td>{{ $value->satuan or 'ls' }}</td>
                                  <td>
                                    <span id="label_nilai{{ $value->id }}">{{ number_format($value->nilai,2) }}</span>
                                    <input type="text" name="nilai_{{ $value->id }}" id="nilai_{{ $value->id }}" style="display: none;" class="form-control" value="{{ $value->nilai }}">
                                  </td>
                                  <td>{{ number_format($subtotal = $value->volume * $value->nilai,2)}}</td>
                                  <td>
                                    <button type="submit" class="btn btn-info" style="" id="detail_budget_bulanan" onclick="edit_Bulanan('{{ $value->id}}')">Buat Cashout Bln</button>
                                  </td>
                                </tr>
                              @endforeach 
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                  
                  <div class="tab-pane" id="tab_3">
                    <div class="row">
                      <div class="col-md-12">  
                        <div class="col-md-12 table-responsive">
                          @if(count($budget_tahunan->carry_over) == 0)
                            <button type="submit" class="btn btn-info" style="margin: 10px 10px 10px 10px" id="create_carryover">Buat Carry Over</button>
                          @endif
                          <button type="submit" class="btn btn-info" style="margin: 10px 10px 10px 10px" id="create_carryoverlama">Buat Carry Over lama</button>
                          <table class="table table-bordered" style="padding: 0;width:100%" id="carryover">
                            <thead class="head_table">
                              <tr>
                                <td></td>
                                <td style="width:20%">no. SPK</td>
                                <td style="width:15%">Status Spk</td>
                                <td style="width:5%">COA Pekerjaan</td>
                                <td style="width:10%">Item Pekerjaan</td>
                                <td style="width:10%">Nilai SPK (excl. PPN)</td>
                                <td style="width:10%">Terbayar</td>
                                <td style="width:10%">Sisa Terbayar</td>
                                <td style="width:10%">cashflow bulanan</td>
                              </tr>
                            </thead>
                            <tbody>
                              @foreach ( $budget_tahunan->carry_over as $key => $value )
                                <tr class="nilai {{ $class }}">
                                  <td>
                                    @if($value->asal_spk == 1)
                                      {{$value->spk->item_pekerjaan->name}}
                                    @else
                                      @php
                                        $pekerjaan = \Modules\Pekerjaan\Entities\Itempekerjaan::where("code", $value->code_coa)->first();
                                      @endphp
                                      @if($pekerjaan != null)
                                        {{$pekerjaan->name}}
                                      @else
                                        noName
                                      @endif
                                    @endif
                                  </td>

                                  <td>
                                    @if($value->asal_spk == 1)
                                      {{$value->spk->no}}
                                    @else
                                      @php
                                        $spk_migrasi = \Modules\Spk\Entities\SpkMigrasi::where("id", $value->spk_id)->first();
                                      @endphp
                                      {{$spk_migrasi->no_spk}}
                                    @endif                                  
                                  </td>
                                  <td>
                                    @if($value->asal_spk == 1)
                                      Spk Sistem baru
                                    @else
                                      Spk Sistem Lama
                                    @endif
                                  </td>
                                  <td>{{$value->code_coa}}</td>
                                  <td>
                                    @if($value->asal_spk == 1)
                                      {{$value->spk->item_pekerjaan->name}}
                                    @else
                                      @php
                                        $pekerjaan = \Modules\Pekerjaan\Entities\Itempekerjaan::where("code", $value->code_coa)->first();
                                      @endphp
                                      @if($pekerjaan != null)
                                        {{$pekerjaan->name}}
                                      @else
                                        noName
                                      @endif
                                    @endif
                                  </td>
                                  <td style="text-align:right">{{number_format($value->nilai_spk)}}</td>
                                  <td style="text-align:right">{{number_format($value->terbayar)}}</td>
                                  <td style="text-align:right">{{number_format($value->hutang_bayar)}}</td>
                                  <td>
                                    <button type="submit" class="btn btn-info" style="" id="detail_budget_bulanan" onclick="edit_Bulanan_carryover('{{ $value->id}}')">Carryover Bulanan</button>
                                  </td>
                                </tr>
                              @endforeach 
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="tab-pane" id="tab_4">
                    <div class="row">
                      <div class="col-md-12">  
                        <div class="col-md-12 table-responsive">
                          <table class="table table-bordered table-striped" style="padding: 0" id="example3">
                            <thead class="head_table">
                              <tr>
                                <td>no SPK</td>
                                <td>Coa</td>
                                <td>Item Pekerjaan</td>
                                <td>Hutang Bayar</td>
                                <td>Total Bulanan</td>
                                <td>Jan</td>
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
                              </tr>
                            </thead>
                            <tbody>
                              @foreach ( $budget_tahunan->carry_over as $key => $value )
                                <tr>
                                <td>
                                    @if($value->asal_spk == 1)
                                      {{$value->spk->no}}
                                    @else
                                      @php
                                        $spk_migrasi = \Modules\Spk\Entities\SpkMigrasi::where("id", $value->spk_id)->first();
                                      @endphp
                                        {{$spk_migrasi->no_spk}}
                                    @endif                                  
                                  </td>
                                  <td>{{$value->code_coa}}</td>
                                  <td>
                                    @if($value->asal_spk == 1)
                                      {{$value->spk->item_pekerjaan->name}}
                                    @else
                                      @php
                                        $pekerjaan = \Modules\Pekerjaan\Entities\Itempekerjaan::where("code", $value->code_coa)->first();
                                      @endphp
                                      @if($pekerjaan != null)
                                        {{$pekerjaan->name}}
                                      @else
                                        noName
                                      @endif
                                    @endif
                                  </td>
                                  <td>{{number_format($value->hutang_bayar)}}</td>
                                  <td>{{ number_format($value->cash_flows->sum('nilai_persen'),2)}}</td>
                                  @for($i=1; $i<=12; $i++)
                                    @php
                                      $nilai_per_bulan = $value->cash_flows->where('bulan',$i)->first()->nilai_persen;
                                    @endphp
                                    <td>{{number_format($nilai_per_bulan,2) }}</td>
                                  @endfor
                                  <!-- <td>Jan</td>
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
                                  <td>Des</td> -->
                                </tr>
                              @endforeach
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="tab-pane" id="tab_5">
                    <div class="row">
                      <div class="col-md-12">  
                        <div class="col-md-12 table-responsive">
                          <table class="table table-bordered table-striped" style="padding: 0" id="table_rekap_seluruh">
                            <thead class="head_table">
                              <tr>
                                <td>Coa</td>
                                <td>Item Pekerjaan</td>
                                <td>Nilai Spk/Budget</td>
                                <td>Terbayar</td>
                                <td>Hutang Bayar</td>
                                <td>Jan</td>
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
                                <td>Total Bulanan</td>
                                <td>Hutang Bayar Tahun depan</td>
                              </tr>
                            </thead>
                            <tbody>
                              @for($i=0 ; $i< count($rekap) ; $i++)
                                <tr>
                                  <td>{{array_keys($rekap)[$i]}}</td>
                                  <td>{{$rekap[array_keys($rekap)[$i]]["name"]}}</td>
                                  <td style="text-align:right;">{{number_format($rekap[array_keys($rekap)[$i]]["total_budget_tahunan"])}}</td>
                                  <td style="text-align:right;">{{number_format($rekap[array_keys($rekap)[$i]]["terbayar"])}}</td>
                                  <td style="text-align:right;">{{number_format($rekap[array_keys($rekap)[$i]]["hutang_bayar"])}}</td>
                                  @for($j=1; $j<=12; $j++)
                                    <td style="text-align:right;">{{number_format($rekap[array_keys($rekap)[$i]][$j])}}</td>
                                  @endfor
                                  <td style="text-align:right;">{{number_format($rekap[array_keys($rekap)[$i]]["total_budget_tahunan_bulanan"])}}</td>
                                  <td style="text-align:right;">{{number_format($rekap[array_keys($rekap)[$i]]["hutang_bayar_depan"])}}</td>
                                </tr>
                              @endfor
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- /.col -->
          </div>
          <!-- /.row -->
        </div>
        <!-- /.box-body -->

      </div>
      <!-- /.box -->


    </section>
    <!-- /.content -->
  </div>

  <div class="modal fade " id="ModalaEdit" tabindex="-1" role="dialog" aria-labelledby="largeModal" aria-hidden="true" style="overflow-y:auto">
    <div style="width: 1200px" class="modal-dialog modal-lg ">
      <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3 class="modal-title" id="myModalLabel">Pekerjaan</h3>
        </div>
        <!-- <form class="form-horizontal" > -->
            <div class="modal-body">
              <div class="tab-pane table-responsive" id="tab_2">
              <table class="table" style="width:100%;" id="table_edit">
                <thead class="head_table">
                  <tr>
                    <td>COA</td>
                    <td>Item Pekerjaan</td>
                    <td>Uraian Pekerjaan</td>
                    <td>Volume Budget</td>
                    <td>Volume Budget Tahunan</td>
                    <td>Satuan</td>
                    <td>Harga Satuan(Rp)</td>
                    <td>Subtotal(Rp)</td>
                  </tr>
                </thead>
              </table>
            </div>
                
                
            </div>

            <div class="modal-footer">
              <input type="hidden" name="all_send" id="all_send" />
              <button type="" id="btn-submit" class="btn btn-primary" style="margin-right:45%">Simpan</button>
                <!-- <button class="btn" data-dismiss="modal" aria-hidden="true">Tutup</button>
                <button class="btn btn-info" id="btn_update">Update</button> -->
            </div>
        <!-- </form> -->
      </div>
    </div>
  </div>

  <div class="modal fade " id="ModalaEditBulanan" tabindex="-1" role="dialog" aria-labelledby="largeModal" aria-hidden="true" style="overflow-y:auto">
    <div style="width: 700px" class="modal-dialog modal-lg ">
      <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3 class="modal-title" id="myModalLabel">Budget Bulanan </h3>
        </div>
        <!-- <form class="form-horizontal" > -->
            <div class="modal-body">
              <div class="tab-pane table-responsive" id="tab_2">
              <table class="table" style="font-size:18px;font-weight:bold">
                  <thead>
                    <tr>
                      <td>Item Pekerjaan</td>
                      <td>:</td>
                      <td>
                        <!-- <input type="text" name="" id="name_itempekerjaan" style="" class="form-control name_itempekerjaan" value="'+v.volume_budget+'" Readonly> -->
                        <p id="name_itempekerjaan"></p>
                      </td>
                    </tr>
                    <tr>
                      <td>Subtotal</td>
                      <td>:</td>
                      <td>
                        <input type="hidden" name="" id="nilai_subtotal_itempekerjaan" style="" class="form-control nilai_subtotal_itempekerjaan" value="'+v.volume_budget+'" Readonly>
                        <p id="subtotal_itempekerjaan"></p>
                      </td>
                    </tr>
                    <tr>
                      <td>Total Persen (%)/Thn</td>
                      <td>:</td>
                      <td>
                        <input type="text" name="" id="total_persen" class="form-control total_persen" value="" style="width:20%" Readonly>
                      </td>
                    </tr>
                    <tr>
                      <td>Total Nilai Cashout Bln/Thn</td>
                      <td>:</td>
                      <td>
                        <input type="text" name="" id="total_bulanan" class="form-control total_bulanan" value="" style="width:40%" Readonly>
                      </td>
                    </tr>
                  </thead>
                </table>
              <table class="table table-bordered" style="width:100%;font-size:20px" id="table_bulanan" >
                <thead class="head_table">
                  <tr>
                    <td style="width:5%">no</td>
                    <td style="width:15%">Bulan</td>
                    <td style="width:25%">Persentase (%)</td>
                    <td>Nilai</td>
                  </tr>
                </thead>
              </table>
            </div>
                
                
            </div>

            <div class="modal-footer">
              <input type="hidden" name="all_send_bulanan" id="all_send_bulanan" />
              <button type="" id="btn-submit-bulanan" class="btn btn-primary" style="margin-right:45%">Simpan</button>
            </div>
        <!-- </form> -->
      </div>
    </div>
  </div>

  <div class="modal fade " id="ModalaEditBulananCarryover" tabindex="-1" role="dialog" aria-labelledby="largeModal" aria-hidden="true" style="overflow-y:auto">
    <div style="width: 700px" class="modal-dialog modal-lg ">
      <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3 class="modal-title" id="myModalLabel">Budget Bulanan </h3>
        </div>
        <!-- <form class="form-horizontal" > -->
            <div class="modal-body">
              <div class="tab-pane table-responsive" id="tab_2">
              <table class="table" style="font-size:18px;font-weight:bold">
                  <thead>
                    <tr>
                      <td>No. SPK</td>
                      <td>:</td>
                      <td>
                        <p id="no_spk"></p>
                      </td>
                    </tr>
                    <tr>
                      <td>Total Nilai</td>
                      <td>:</td>
                      <td>
                        <!-- <input type="hidden" name="" id="nilai_subtotal_itempekerjaan" style="" class="form-control nilai_subtotal_itempekerjaan" value="'+v.volume_budget+'" Readonly> -->
                        <p id="Total_nilai"></p>
                      </td>
                    </tr>
                    <tr>
                      <td>Hutang Bayar</td>
                      <td>:</td>
                      <td>
                        <input type="hidden" name="" id="nilai_hutang_bayar" style="" class="form-control hutang_bayar" value="'+v.volume_budget+'" Readonly>
                        <p id="hutang_bayar"></p>
                      </td>
                    </tr>
                    <tr>
                      <td>Total Persen (%)/Thn</td>
                      <td>:</td>
                      <td>
                        <input type="text" name="" id="total_persen_carryover" class="form-control total_persen_carryover" value="" style="width:20%" Readonly>
                      </td>
                    </tr>
                    <tr>
                      <td>Total Nilai Carryover Bln/Thn</td>
                      <td>:</td>
                      <td>
                        <input type="text" name="" id="total_bulanan_carryover" class="form-control total_bulanan_carryover" value="" style="width:40%" Readonly>
                      </td>
                    </tr>
                  </thead>
                </table>
              <table class="table table-bordered" style="width:100%;font-size:20px" id="table_bulanan_carryover" >
                <thead class="head_table">
                  <tr>
                    <td style="width:5%">no</td>
                    <td style="width:15%">Bulan</td>
                    <td style="width:25%">Persentase (%)</td>
                    <td>Nilai</td>
                  </tr>
                </thead>
              </table>
            </div>
                
                
            </div>

            <div class="modal-footer">
              <input type="hidden" name="all_send_bulanan_carryover" id="all_send_bulanan_carryover" />
              <button type="" id="btn-submit-bulanan_carryover" class="btn btn-primary" style="margin-right:45%">Simpan</button>
            </div>
        <!-- </form> -->
      </div>
    </div>
  </div>

  <div class="modal fade " id="ModalaTambahCarryLama" tabindex="-1" role="dialog" aria-labelledby="largeModal" aria-hidden="true" style="overflow-y:auto">
    <div style="width: 1200px" class="modal-dialog modal-lg ">
      <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3 class="modal-title" id="myModalLabel">Data COA LAMA</h3>
        </div>
        <!-- <form class="form-horizontal" > -->
            <div class="modal-body">
              <div class="tab-pane table-responsive" id="tab_2">
                <button id="addRow" style="margin: 10px 5px 10px 5px">Add new row</button>
                <table class="table table-bordered" id="table_pekerjaan" style="width:100%;margin:10px 10px 10px 10px">
                    <thead class="head_table" style="background-color:">
                        <tr>
                            <th style="width:30%">COA</th>
                            <th style="width:20%">Nilai (Excl. PPN)</th>
                            <th style="width:20%">Terbayar</th>
                            <th style="width:20%">Sisa Terbayar</th>
                            <th style="width: 10%">action</th>
                        </tr>
                    </thead>
                    <tbody>
                      <tr class="test">
                        <td>
                          <select class="itempekerjaan_parent form-control" name="" style="width:100%">
                            <option value="0">Pilih COA Pekerjaan</option>
                            @if($budget->project_kawasan_id != '')
                                @foreach ( $itempekerjaan_parent->where('code','!=',240) as $key => $value )
                                    <option value="{{ $value->id }}">{{ $value->code }} | {{ $value->name }}</option>
                                @endforeach
                            @else
                                @foreach ($itempekerjaan_parent->where('code',240) as $key => $value )
                                    <option value="{{ $value->id }}">{{ $value->code }} | {{ $value->name }}</option>
                                @endforeach
                            @endif
                          </select>
                        </td>
                        <td>
                          <input type='text' name='nilai' class='nilai form-control' style='width:100%' value=""/>
                        </td>
                        <td>
                          <input type='text' name='terbayar' class='terbayar form-control' style='width:100%' value=""/>
                        </td>
                        <td>
                          <input type='text' name='sisa' class='sisa form-control' style='width:100%' value=""/>
                        </td>
                        <td style="text-align:center">
                          <button type="button" class="btn btn-danger hapus" ><i class="fa fa-trash" style="font-size:15px"></i></button>
                        </td>
                      </tr>
                    </tbody>
                </table>
            </div>
                
                
            </div>

            <div class="modal-footer">
              <input type="hidden" name="all_send" id="all_send" />
              <button type="" id="btn-submit-lama" class="btn btn-primary" style="margin-right:45%">Simpan</button>
                <!-- <button class="btn" data-dismiss="modal" aria-hidden="true">Tutup</button>
                <button class="btn btn-info" id="btn_update">Update</button> -->
            </div>
        <!-- </form> -->
      </div>
    </div>
  </div>

  <div id="clone_itempekerjaan" hidden>
    <select class="itempekerjaan_parent form-control" name="" style="width:100%">
      <option value="0">Pilih COA Pekerjaan</option>
      @if($budget->project_kawasan_id != '')
          @foreach ( $itempekerjaan_parent->where('code','!=',240) as $key => $value )
              <option value="{{ $value->id }}">{{ $value->code }} | {{ $value->name }}</option>
          @endforeach
      @else
          @foreach ($itempekerjaan_parent->where('code',240) as $key => $value )
              <option value="{{ $value->id }}">{{ $value->code }} | {{ $value->name }}</option>
          @endforeach
      @endif
    </select>
</div>
  <!-- /.modal -->
  <!-- /.content-wrapper -->
@include("master/copyright")

  
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

@include("master/footer_table")
@include('form.general_form')
<!-- @include("budget::app") -->
<script src="{{ url('/')}}/assets/bower_components/select2/dist/js/select2.full.min.js"></script>
<script type="text/javascript">
  

$( document ).ready(function() {
  $.ajaxSetup({
      headers: {
          'X-CSRF-Token': $('input[name=_token]').val()
      }
    });
    // $(".nilai").number(true);
    // $(".terbayar").number(true);
    // $(".sisa").number(true);
  // $("select").select2();
});

tbody = $('#table_pekerjaan');
    tbody.find('.nilai').each(function (i, v) {
      fnSetAutoNumeric($(this));
      fnSetMoney($(this), $(this).val());
    }); 
    tbody.find('.terbayar').each(function (i, v) {
      fnSetAutoNumeric($(this));
      fnSetMoney($(this), $(this).val());
    }); 
    tbody.find('.sisa').each(function (i, v) {
      fnSetAutoNumeric($(this));
      fnSetMoney($(this), $(this).val());
    }); 

  function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
  }
  $('#carryover').DataTable({
        paging: false,
        "columns":[
                    {data:"pekerjaan",name:"pekerjaan"},
                    {data:"nospk",name:"nospk"},
                    {data:"status",name:"status"},
                    {data:"coa",name:"coa"},
                    {data:"namepekerjaan",name:"namepekerjaan"},
                    {data:"nilai_spk",name:"nilai_spk"},
                    {data:"terbayar",name:"terbayar"},
                    {data:"hutang_bayar",name:"hutang_bayar"},
                    {data:"cashflow",name:"cashflow"},
                  ],
        "columnDefs": [
          { "visible": false, "targets": [0] }
        ],
        "order": [[ 0, 'asc' ]],
        "drawCallback": function ( settings ) {
          var api = this.api();
          var rows = api.rows( {page:'current'} ).nodes();
          var last=null;

          api.column(0, {page:'current'} ).data().each( function ( group, i ) {
                var totspk = api.rows().data().filter( function ( d ) {
                    return d.pekerjaan == group;
                  } ).pluck( 'nilai_spk' );
                var totalspk = totspk.reduce((a, b) => BigInt(a.toString().replace(/,/g,"")) + BigInt(b.toString().replace(/,/g,"")), 0);

                var totterbayar = api.rows().data().filter( function ( d ) {
                    return d.pekerjaan == group;
                  } ).pluck( 'terbayar' );
                var totalterbayar = totterbayar.reduce((a, b) => BigInt(a.toString().replace(/,/g,"")) + BigInt(b.toString().replace(/,/g,"")), 0);

                var tothutang = api.rows().data().filter( function ( d ) {
                    return d.pekerjaan == group;
                  } ).pluck( 'hutang_bayar' );
                var totalhutang = tothutang.reduce((a, b) => BigInt(a.toString().replace(/,/g,"")) + BigInt(b.toString().replace(/,/g,"")), 0);

                if ( last !== group ) {
                      $(rows).eq(i).before(
                          '<tr class="group" style="background-color: white;""><td colspan=4"><strong>'+group+'</strong></td>><td colspan=1" style="text-align:right"><strong>'+numberWithCommas(totalspk)+'</strong></td><td colspan=1" style="text-align:right"><strong>'+numberWithCommas(totalterbayar)+'</strong></td><td colspan=1" style="text-align:right"><strong>'+numberWithCommas(totalhutang)+'</strong></td></tr>'
                      );
                  last = group;
                }
          });
        },
        "initComplete": function(settings, json) {
          $('.group').nextUntil('.group').css( "display", "none" );
        }
  });
  var tbody = $('#carryover tbody');
      tbody.on('click','.group',function()
      {
        $(this).nextUntil('.group').toggle();

      });

  $('#table_edit').DataTable({

      "paging":false,
      "destroy": true,
      "columns":[
              {data:"coa",name:"coa"},
              {data:"itempekerjaan",name:"itempekerjaan"},
              {data:"uraian",name:"uraian"},
              {data:"volume_budget",name:"volume_budget"},
              {data:"volume",name:"volume"},
              {data:"satuan",name:"satuan"},
              {data:"harga_satuan",name:"harga_satuan"},
              {data:"harga_subtotal",name:"harga_subtotal"},
              ],
      "order": [[ 0, 'asc' ]]
  })

  $('#edit_tahunan_detail').click(function() {
    var url = "{{ url('/')}}/budget/cashflow/view_edit_tahunan";
    $('#table_edit').DataTable().clear().draw();
    $.ajax({
        type: 'post',
        dataType: 'json',
        url: url,
        data: {
            id: $("#budget_tahunan_id").val()
        },
        beforeSend: function() {
            waitingDialog.show();
        },
        success: function(data) {
            if (data.data.length > 0) {
                $(data.data).each(function(i, v) { 
                    var ItemTable = {
                        coa: v.coa,
                        itempekerjaan: v.itempekerjaan+'<input type="hidden" class="form-control itempekerjaan_id_edit" value="'+v.itempekerjaan_id+'"> <input type="hidden" class="form-control budget_tahunan_detail_id_edit" value="'+v.budget_tahunan_detail_id+'">',
                        uraian: v.uraian,
                        volume_budget: '<input type="text" name="volume_global_edit" id="" style="" class="form-control volume_global_edit  " value="'+v.volume_budget+'" Readonly>',
                        volume: '<input type="text" name="volume_tahunan_edit" id="" style="" class="form-control volume_tahunan_edit" value="'+v.volume+'">',
                        satuan: v.satuan,
                        harga_satuan : '<input type="text" name="harga_satuan_edit" id="" style="" class="form-control harga_satuan_edit" value="'+v.harga_satuan+'">',
                        harga_subtotal : '<input type="text" name="harga_subtotal_edit" id="" style="" class="form-control harga_subtotal_edit" value="'+v.harga_subtotal+'">',
                    };
                    $('#table_edit').DataTable().row.add(ItemTable);
                });
            }
            $('#table_edit').DataTable().draw();
            $("#table_edit").find('tr').addClass('test');
            $('#table_edit').DataTable().columns.adjust();
            tbody = $('#table_edit');
              tbody.find('.harga_satuan_edit').each(function (i, v) {
                fnSetAutoNumeric($(this));
                fnSetMoney($(this), $(this).val());
              }); 
              tbody.find('.harga_subtotal_edit').each(function (i, v) {
                fnSetAutoNumeric($(this));
                fnSetMoney($(this), $(this).val());
              });

        },
        complete: function() {
            waitingDialog.hide();
            
        }
    });
    $('#ModalaEdit').modal('show');
});

$('#btn-submit').click(function() {
    var _data = [];
    $('#table_edit > tbody > tr').each(function(i, v) {
        var _objdata = {
            'itempekerjaan_id': $(this).find('.itempekerjaan_id_edit').val(),
            'volume': $(this).find('.volume_tahunan_edit').val(),
            'harga_satuan': $(this).find('.harga_satuan_edit').autoNumeric('get'),
            'budget_tahunan_detail': $(this).find('.budget_tahunan_detail_id_edit').val(),
        };

        _data.push(_objdata);
    });
    $('#all_send').val(JSON.stringify(_data));

    var _url = '{{ url("/")}}/budget/cashflow/update_budget_tahunan_detail';
    var data = JSON.parse($('#all_send').val());
    var budget_tahunan_id = $('#budget_tahunan_id').val();
    if(data==''){
        alert('Harap Mengisi data');
    }else{
        $.ajax({
            type : "POST",
            url  : _url,
            dataType : "JSON",
            data :{
            data:data,
            // budget_id:budget_id
            },
            success : function(data){
                // alert(data.success);
                window.location.replace('{{ url("/")}}/budget/cashflow/detail-cashflow?id='+budget_tahunan_id);
            }     
        });
    }
});

$(document).on('keyup', '.harga_satuan_edit', function() {
    var parent_div = $(this).parents('.test');
    if($(this).val()!=''){
        var admin  = parseInt($(this).autoNumeric('get'));
    }else{
        var admin = 0;
    }
    var nilai = parseInt(parent_div.find(".volume_tahunan_edit").val()) * admin;
    parent_div.find(".harga_subtotal_edit").val(nilai).number(true);

});
        
$(document).on('keyup', '.volume_tahunan_edit', function() {
    var parent_div = $(this).parents('.test');
    if($(this).val()!=''){
        var admin  = parseInt($(this).val());
    }else{
        var admin = 0;
    }
    var nilai = parseInt(parent_div.find(".harga_satuan_edit").autoNumeric('get')) * admin;
    parent_div.find(".harga_subtotal_edit").val(nilai).number(true);
});

  $('#table_bulanan').DataTable({
      "paging":false,
      "destroy": true,
      "columns":[
              {data:"no",name:"no"},
              {data:"bulan",name:"bulan"},
              {data:"persentase",name:"persentase"},
              {data:"nilai",name:"nilai"},
              ],
      "order": [[ 0, 'asc' ]]
  })

  function edit_Bulanan(id){
    var url = "{{ url('/')}}/budget/cashflow/view_Bulanan";
    $('#table_bulanan').DataTable().clear().draw();
    $.ajax({
        type: 'post',
        dataType: 'json',
        url: url,
        data: {
            id: id
        },
        beforeSend: function() {
            waitingDialog.show();
        },
        success: function(data) {
            if (data.data.length > 0) {
                $(data.data).each(function(i, v) { 
                    var ItemTable = {
                        no: i+1,
                        bulan: v.name_bulan+'<input type="hidden" name="id_bulan" id="id_bulan"  class="form-control id_bulan" value="'+v.id_bulan+'" ><input type="hidden" name="id_budget_bulanan" id="id_budget_bulanan"  class="form-control id_budget_bulanan" value="'+v.id+'" >',
                        persentase: '<input type="number" name="persen_bulanan" id="" style="width:100px" class="form-control persen_bulanan" value="'+v.persentase+'" max="100" min="0">',
                        nilai: '<input type="text" name="nilai_persen_bulanan" id="" style="" class="form-control nilai_persen_bulanan" value="'+v.nilai+'" readonly>',
                    };
                    $('#table_bulanan').DataTable().row.add(ItemTable);
                });
                
                // $('#name_itempekerjaan').val(data.name_itempekerjaan);
                document.getElementById("name_itempekerjaan").innerHTML = data.name_itempekerjaan;
                $('#nilai_subtotal_itempekerjaan').val(data.nilai_subtotal);
                document.getElementById("subtotal_itempekerjaan").innerHTML = 'Rp. '+data.subtotal;
                $('#total_persen').val(data.totpersen);
                $('#total_bulanan').val(data.totbulanan);
                fnSetAutoNumeric($('#total_bulanan'));
                fnSetMoney($('#total_bulanan'), $('#total_bulanan').val());
            }
            $('#table_bulanan').DataTable().draw();
            $("#table_bulanan").find('tr').addClass('test');
            $('#table_bulanan').DataTable().columns.adjust();
            tbody = $('#table_bulanan');
            tbody.find('.nilai_persen_bulanan').each(function (i, v) {
              fnSetAutoNumeric($(this));
              fnSetMoney($(this), $(this).val());
            }); 
            // tbody.find('.harga_subtotal_edit').each(function (i, v) {
            //   fnSetAutoNumeric($(this));
            //   fnSetMoney($(this), $(this).val());
            // });

        },
        complete: function() {
            waitingDialog.hide();
            
        }
    });
    $('#ModalaEditBulanan').modal('show');
  }


  $('#table_bulanan_carryover').DataTable({
      "paging":false,
      "destroy": true,
      "columns":[
              {data:"no",name:"no"},
              {data:"bulan",name:"bulan"},
              {data:"persentase",name:"persentase"},
              {data:"nilai",name:"nilai"},
              ],
      "order": [[ 0, 'asc' ]]
  })

  function edit_Bulanan_carryover(id){
    var url = "{{ url('/')}}/budget/cashflow/view_Bulanan_carryover";
    $('#table_bulanan_carryover').DataTable().clear().draw();
    $.ajax({
        type: 'post',
        dataType: 'json',
        url: url,
        data: {
            id: id
        },
        beforeSend: function() {
            waitingDialog.show();
        },
        success: function(data) {
            if (data.data.length > 0) {
                $(data.data).each(function(i, v) { 
                    var ItemTable = {
                        no: i+1,
                        bulan: v.name_bulan+'<input type="hidden" name="id_bulan_carryover" id="id_bulan_carryover"  class="form-control id_bulan_carryover" value="'+v.id_bulan+'" ><input type="hidden" name="id_budget_bulanan_carryover" id="id_budget_bulanan_carryover"  class="form-control id_budget_bulanan_carryover" value="'+v.id+'" >',
                        persentase: '<input type="number" name="persen_bulanan_carryover" id="" style="width:100px" class="form-control persen_bulanan_carryover" value="'+v.persentase+'" max="100" min="0">',
                        nilai: '<input type="text" name="nilai_persen_bulanan_carryover" id="" style="" class="form-control nilai_persen_bulanan_carryover" value="'+v.nilai+'" readonly>',
                    };
                    $('#table_bulanan_carryover').DataTable().row.add(ItemTable);
                });
                
                // $('#name_itempekerjaan').val(data.name_itempekerjaan);
                document.getElementById("no_spk").innerHTML = data.no_spk;
                document.getElementById("Total_nilai").innerHTML = 'Rp. '+data.nilai_spk;
                document.getElementById("hutang_bayar").innerHTML = 'Rp. '+data.hutang_bayar;
                $('#nilai_hutang_bayar').val(data.nilai_hutang_bayar);
                $('#total_persen_carryover').val(data.totpersen);
                $('#total_bulanan_carryover').val(data.totbulanan);
                fnSetAutoNumeric($('#total_bulanan_carryover'));
                fnSetMoney($('#total_bulanan_carryover'), $('#total_bulanan_carryover').val());
            }
            $('#table_bulanan_carryover').DataTable().draw();
            $("#table_bulanan_carryover").find('tr').addClass('test');
            $('#table_bulanan_carryover').DataTable().columns.adjust();
            tbody = $('#table_bulanan_carryover');
            tbody.find('.nilai_persen_bulanan_carryover').each(function (i, v) {
              fnSetAutoNumeric($(this));
              fnSetMoney($(this), $(this).val());
            }); 
            tbody.find('.harga_subtotal_edit_carryover').each(function (i, v) {
              fnSetAutoNumeric($(this));
              fnSetMoney($(this), $(this).val());
            });

        },
        complete: function() {
            waitingDialog.hide();
            
        }
    });
    $('#ModalaEditBulananCarryover').modal('show');
  }

  $(document).on('keyup', '.persen_bulanan', function() {
    var parent_div = $(this).parents('.test');
    if($(this).val()!=''){
        var admin  = parseFloat($(this).val());
    }else{
        var admin = 0;
    }
    
    var nilai = parseInt($('#nilai_subtotal_itempekerjaan').val()) * (admin/100);
    parent_div.find(".nilai_persen_bulanan").val(nilai).number(true);

    var sum = 0;

    $(".persen_bulanan").each(function(){
      if($(this).val() == ''){
        sum += 0;
      }else{
        sum += parseFloat($(this).val());
      }
    });
    $("#total_persen").val(sum);
    if ( sum > 100 ){
      alert("Percentage lebih dari 100% ");
      $("#btn-submit-bulanan").attr("disabled", true);
    }else{
      $("#btn-submit-bulanan").attr("disabled", false);
    }

    $(".nilai_persen_bulanan").each(function(){
      if($(this).val() == ''){
        sum += 0;
      }else{
        sum += parseFloat($(this).val());
      }
    });
    $("#total_bulanan").val(sum);
    fnSetAutoNumeric($('#total_bulanan'));
    fnSetMoney($('#total_bulanan'), $('#total_bulanan').val());
  });
  $(document).on('keyup', '.persen_bulanan_carryover', function() {
    var parent_div = $(this).parents('.test');
    if($(this).val()!=''){
        var admin  = parseFloat($(this).val());
    }else{
        var admin = 0;
    }
    
    var nilai = parseInt($('#nilai_hutang_bayar').val()) * (admin/100);
    parent_div.find(".nilai_persen_bulanan_carryover").val(nilai).number(true);

    var sum = 0;

    $(".persen_bulanan_carryover").each(function(){
      if($(this).val() == ''){
        sum += 0;
      }else{
        sum += parseFloat($(this).val());
      }
    });
    $("#total_persen_carryover").val(sum);
    if ( sum > 100 ){
      alert("Percentage lebih dari 100% ");
      $("#btn-submit-bulanan_carryover").attr("disabled", true);
    }else{
      $("#btn-submit-bulanan_carryover").attr("disabled", false);
    }

    var sum2 = 0;
    $(".nilai_persen_bulanan_carryover").each(function(){
      if($(this).val() == ''){
        sum2 += 0;
      }else{
        sum2 += parseFloat($(this).val());
      }
    });
    // console.log(sum);
    $("#total_bulanan_carryover").val(sum2);
    fnSetAutoNumeric($('#total_bulanan_carryover'));
    fnSetMoney($('#total_bulanan_carryover'), $('#total_bulanan_carryover').val());
  });

  $(document).on('change', '.persen_bulanan', function() {
    var parent_div = $(this).parents('.test');
    if($(this).val()!=''){
        var admin  = parseFloat($(this).val());
    }else{
        var admin = 0;
    }
    
    var nilai = parseInt($('#nilai_subtotal_itempekerjaan').val()) * (admin/100);
    parent_div.find(".nilai_persen_bulanan").val(nilai).number(true);
    var sum = 0;
    $(".persen_bulanan").each(function(){
      if($(this).val() == ''){
        sum += 0;
      }else{
        sum += parseFloat($(this).val());
      }
    });
    $("#total_persen").val(sum);
    if ( sum > 100 ){
      alert("Percentage lebih dari 100% ");
      $("#btn-submit-bulanan").attr("disabled", true);
    }else{
      $("#btn-submit-bulanan").attr("disabled", false);
    }

    $(".nilai_persen_bulanan").each(function(){
      if($(this).val() == ''){
        sum += 0;
      }else{
        sum += parseFloat($(this).val());
      }
    });
    $("#total_bulanan").val(sum);
    fnSetAutoNumeric($('#total_bulanan'));
    fnSetMoney($('#total_bulanan'), $('#total_bulanan').val());
    
  });
  

  $('#btn-submit-bulanan').click(function() {
    var _data = [];
    $('#table_bulanan > tbody > tr').each(function(i, v) {
        var _objdata = {
            'id': $(this).find('.id_budget_bulanan').val(),
            'bulan': $(this).find('.id_bulan').val(),
            'persen': $(this).find('.persen_bulanan').val(),
            'nilai_persen': $(this).find('.nilai_persen_bulanan').val(),
        };

        _data.push(_objdata);
    });
    $('#all_send_bulanan').val(JSON.stringify(_data));

    var _url = '{{ url("/")}}/budget/cashflow/update_budget_tahunan_bulanan';
    var data = JSON.parse($('#all_send_bulanan').val());
    var budget_tahunan_id = $('#budget_tahunan_id').val();
    if(data==''){
        alert('Harap Mengisi data');
    }else{
        $.ajax({
            type : "POST",
            url  : _url,
            dataType : "JSON",
            data :{
            data:data,
            // budget_id:budget_id
            },
            success : function(data){
                alert(data.success);
                window.location.replace('{{ url("/")}}/budget/cashflow/detail-cashflow?id='+budget_tahunan_id);
            }     
        });
    }
});

$('#create_carryover').click(function() {
    var budget_tahunan_id = $('#budget_tahunan_id').val();
    var _url = '{{ url("/")}}/budget/cashflow/create_carryover';
    $.ajax({
        type : "POST",
        url  : _url,
        dataType : "JSON",
        data :{
          budget_tahunan_id:budget_tahunan_id,
        },
        beforeSend: function() {
          waitingDialog.show();
        },
        success : function(data){
          // alert(data.success);
          // location.reload();
          window.location.replace('{{ url("/")}}/budget/cashflow/detail-cashflow?id='+budget_tahunan_id);
        },   
        // complete: function() {
        //   waitingDialog.hide();
        // } 
    });
});

$('#btn-submit-bulanan_carryover').click(function() {
    var _data = [];
    $('#table_bulanan_carryover > tbody > tr').each(function(i, v) {
        var _objdata = {
            'id': $(this).find('.id_budget_bulanan_carryover').val(),
            'bulan': $(this).find('.id_bulan_carryover').val(),
            'persen': $(this).find('.persen_bulanan_carryover').val(),
            'nilai_persen': $(this).find('.nilai_persen_bulanan_carryover').val(),
        };

        _data.push(_objdata);
    });
    // console.log(_data);
    $('#all_send_bulanan_carryover').val(JSON.stringify(_data));
    // console.log($('#all_send_bulanan_carryover').val())
    var _url = '{{ url("/")}}/budget/cashflow/update_budget_tahunan_bulanan_carryover';
    var data = JSON.parse($('#all_send_bulanan_carryover').val());
    var budget_tahunan_id = $('#budget_tahunan_id').val();
    if(data==''){
        alert('Harap Mengisi data');
    }else{
        $.ajax({
            type : "POST",
            url  : _url,
            dataType : "JSON",
            data :{
            data:data,
            // budget_id:budget_id
            },
            beforeSend: function() {
              waitingDialog.show();
            },
            success : function(data){
                // alert(data.success);
                window.location.replace('{{ url("/")}}/budget/cashflow/detail-cashflow?id='+budget_tahunan_id);
            }     
        });
    }
});

$('#create_carryoverlama').click(function() {
  $('#ModalaTambahCarryLama').modal('show');
}); 
// var t = $('#table_pekerjaan').DataTable({
//             paging:false,
//         });

$('#addRow').on( 'click', function () {
    $('#table_pekerjaan').append( '<tr class="test">' +
        '<td>'+$('#clone_itempekerjaan').clone().html()+'</td>' +
        '<td><input type="text" name="nilai" class="nilai form-control" style="width:100%" value=""/></td>'+
        '<td><input type="text" name="terbayar" class="terbayar form-control" style="width:100%" value=""/></td>' +
        '<td><input type="text" name="sisa" class="sisa form-control" style="width:100%" value=""/></td>' +
        '<td style="text-align:center"><button type="button" class="btn btn-danger hapus" ><i class="fa fa-trash" style="font-size:15px"></i></button></td>' +
    '</tr>');
    $("#table_pekerjaan").find('tr').addClass('test');

    tbody = $('#table_pekerjaan');
    tbody.find('.nilai').each(function (i, v) {
      fnSetAutoNumeric($(this));
      fnSetMoney($(this), $(this).val());
    }); 
    tbody.find('.terbayar').each(function (i, v) {
      fnSetAutoNumeric($(this));
      fnSetMoney($(this), $(this).val());
    }); 
    tbody.find('.sisa').each(function (i, v) {
      fnSetAutoNumeric($(this));
      fnSetMoney($(this), $(this).val());
    }); 
});
  // $(".itempekerjaan_parent").select2();

  $(document).on('click', '.hapus', function() {
      $(this).parents(".test").remove();
  });

  // $(document).on('click', '#btn-submit-lama', function() {

  // });
  $(document).on('click', '#btn-submit-lama', function() {
      var main = [];
      $("#table_pekerjaan .test").each(function () {
          var arr = [
                  $(this).find(".itempekerjaan_parent").val(),
                  $(this).find(".nilai").val(),
                  $(this).find(".terbayar").val(),
                  $(this).find(".sisa").val(),
              ];
          main.push(arr);

          // console.log(main);
      });
      var url = "{{ url('/')}}/budget/cashout_coa_lama";
      $.ajax({
          type: 'post',
          dataType: 'json',
          url: url,
          data: {
              data: main,
              budget_tahunan_id: $("#budget_tahunan_id").val(),
          },
          beforeSend: function() {
          waitingDialog.show();
          },
          success: function(data) { 
              // window.location.reload(true);
          },
          complete: function() {
          waitingDialog.hide(); 
          },
      });
      // window.location.href;
      // window.location.replace(window.location.href);
      // window.location.replace("/rab/detail?id="+$("#rab_id").val()+"&idpkr="+$("#idpkr").val()+"#");
  });

  $(document).on('keyup', '.nilai', function() {
    var nilai = parseInt($(this).autoNumeric('get'));
    if($(this).parents(".test").find(".terbayar").val() != undefined && $(this).parents(".test").find(".terbayar").val() != null  && $(this).parents(".test").find(".terbayar").val() != ''){
      var terbayar = parseInt($(this).parents(".test").find(".terbayar").autoNumeric('get'));
    }else{
      var terbayar = 0;
    }

      $(this).parents(".test").find(".sisa").val(nilai-terbayar).number(true);;
  });
  
  $(document).on('keyup', '.terbayar', function() {
    var terbayar = parseInt($(this).autoNumeric('get'));
    if($(this).parents(".test").find(".nilai").val() != undefined && $(this).parents(".test").find(".nilai").val() != null && $(this).parents(".test").find(".nilai").val() != ''){
      var nilai = parseInt($(this).parents(".test").find(".nilai").autoNumeric('get'));
    }else{
      var nilai = 0;
    }
      $(this).parents(".test").find(".sisa").val(nilai-terbayar).number(true);
  });
</script>
</body>
</html>
