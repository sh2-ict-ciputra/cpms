<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Admin QS | Dashboard</title>
  @include("master/header")
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  @include("master/sidebar_progress")

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
    </section>

    <!-- Main content -->
    <section class="content">
      {{ csrf_field() }}
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Data SPK </h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <!-- <form action="{{ url('/')}}/progress/saveprogress" method="post" name="form1"> -->
                <input type="hidden" name="spk_id" value="{{ $spk->id }}">
                <input type="hidden" name="unit_id" value="{{ $unit->id }}">
                <input type="hidden" name="unitprogress" id="unitprogress" value="{{ $unitprogress->count() }}">
                <input type="hidden" name="start_date" id="start_date" value="{{ $spk->start_date->format('Y/m/d') }}">   
                <input type="hidden" name="finish_date" id="finish_date" value="{{ $spk->finish_date->format('Y/m/d') }}">               
                <input type="hidden" name="durasi" id="durasi" value="{{ $spk->tender->durasi }}">
                <div class="form-group">
                    <label for="exampleInputEmail1">No. SPK</label>
                    <input type="text" class="form-control" name="document" value="{{ $spk->no}}" readonly>
                </div>   
                <div class="form-group">
                    <label for="exampleInputEmail1">Nama. SPK</label>
                    <input type="text" class="form-control" name="document" value="{{ $spk->name}}" readonly>
                </div>  
                <div class="form-group">
                    <label for="exampleInputEmail1">Unit Name </label>
                    @if($unit->rab_unit->asset != '')
                      <input type="text" class="form-control" value="{{ $unit->rab_unit->asset->name }}" readonly>
                    @else
                     <input type="text" class="form-control" value="Fasilitas Kota" readonly>
                    @endif
                </div>                 
                <div class="form-group">
                  <button type="submit" class="btn btn-primary">Simpan</button>
                  <a href="{{ url('/') }}/progress/show?id={{ $spk->id}}" class="btn btn-warning">Kembali</a>
                </div>   
                {{ csrf_field() }}                  
                <div class="col-md-12">
                @if ( $unit->rab_unit->asset_type == "Modules\Project\Entities\Unit") 
                <table class="table table-bordered">
                  <thead class="head_table">
                    <tr>
                      <td>Escrow Name</td>
                      <td>Progress Saat Ini</td>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>Pondasi</td>
                      <td>{{ number_format($unit->escrow_atap * 100 ,2) }} % </td>
                    </tr>
                    <tr>
                      <td>Atap</td>
                      <td>{{ number_format($unit->escrow_dinding * 100 ,2) }} % </td>
                    </tr>
                    <tr>
                      <td>Progress</td>
                      <td><span id="label_progress"></span></td>
                    </tr>
                  </tbody>
                </table>
                @endif 

                <table class="table table-bordered">
                  <thead class="head_table">
                    <tr>
                      <td>&nbsp;</td>
                      <td>Tanggal Mulai</td>
                      <td>Tanggal Selesai</td>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>SPK</td>
                      <td>{{ $spk->start_date->format("d/M/Y")}}</td>
                      <td>{{ $spk->finish_date->format("d/M/Y")}}</td>
                    </tr>
                    <tr>
                      <td>Fisik</td>
                      @if($spk->start_date_real != null)
                        <td><span id="start_fisik">{{ date("d/M/Y", strtotime($spk->start_date_real))}}</span></td>
                        <td><span id="start_fisik">{{ date("d/M/Y", strtotime($spk->finish_date_real))}}</span></td>
                      @else
                        <td><span id="start_fisik">{{ $spk->start_date->format("d/M/Y")}}</span></td>
                        <td><span id="start_fisik">{{ $spk->finish_date->format("d/M/Y")}}</span></td>
                      @endif
                    </tr>
                  </tbody>
                </table>
 

                <center><h3>Progress</h3></center>
                <hr>
                <div class="table-responsive">
                  {{ csrf_field() }}                      
                  <input type="hidden" name="spk_progress_id" value="{{ $spk->id }}">
                  <input type="hidden" name="unit_progress_id" value="{{ $unit->id }}">
                <i class="fa fa-refresh ld ld-spin" id="loading" style="display: none;"></i>
                  <button type="button" onClick="simpanprogress()" class="btn btn-primary" id="btnsimpan">Simpan Jadwal Progress</button>
                  <table class="table table-bordered">
                    <thead class="head_table">
                      <tr>
                        <td>Item Pekerjaan</td>   
                        <td>Bobot RAB(%)</td> 
                        <td>Bobot Sebelum Vo Kurang(%)</td> 
                        <td>Target Tanggal Mulai</td>
                        <td>Target Tanggal Selesai</td>
                        <td>Real Tanggal 100%</td>
                        <td>Delta Hari</td>  
                        <td>Progress s/d Lalu(%)</td>
                        <td>Progress s/d Skrg(%)</td>    
                        <td>Nilai Bobot(%)</td>    
                        <td>Tambah Progress</td>
                      </tr>
                    </thead>
                   <tbody>
                    @php $nilai = 0; $real_bobot = 0; $total = 0; @endphp
                      @foreach ( $unitprogress as $key2 => $value )
                          @php 
                            $total = $total + ($value->total_nilai);
                          @endphp
                      @endforeach
                      @foreach ( $unitprogress as $key2 => $value2 )
                        @if ( $value2->spkvo_unit->head_type != "Modules\Spk\Entities\Vo")
                          @if( $value2->volume != 0)
                            <tr style="{{ $arrayEscrow[$value2->itempekerjaan->escrow_id]['style'] }}">                                
                                <td>
                                  <input type="hidden" name="unitprogress_id_[{{$key2}}]" value="{{ $value2->id}}">
                                  {{ $value2->itempekerjaan->name }}<br> {{ $arrayEscrow[$value2->itempekerjaan->escrow_id]['label']  }}
                                </td> 
                                <td style="text-align:right">
                                  @php
                                  $vo_pengurangan = $spk->new_vo->where("tipe",1);
                                  $kurang = 0;
                                  $total_kurang = 0;
                                  foreach ($vo_pengurangan as $key => $value) {
                                      # code...
                                      if($value->approval->approval_action_id == 6){
                                        $pengurangan = $value->detail->where('itempekerjaan_id', $value2->itempekerjaan_id)->where('unit_id',$unit->id)->where("volume", "<=", 0)->first();

                                        if($pengurangan != null){
                                          $kurang += $pengurangan['volume'];
                                          $total_kurang += $pengurangan['total_nilai'];
                                        }
                                      }
                                  }
                                  @endphp
                                  @php
                                    $bobot_rab_baru = ($value2->total_nilai - abs($total_kurang))/($total - abs($total_nilaitot_kurang)) *100;
                                  @endphp
                                  {{-- {{$value2->total_nilai - abs($total_kurang)}} --}}
                                  {{ number_format($bobot_rab_baru,2) }}
                                
                                </td>
                                <td style="text-align:right">
                                  @php
                                    $bobot_rab_lama = ($value2->total_nilai)/($total) *100;
                                  @endphp
                                  {{ number_format($bobot_rab_lama,2) }}
                                
                                </td>
                                <td>
                                  @if ( $value2->mulai_jadwal_date == NULL )
                                    <input type="text" name="start_date_[{{$key2}}]" id="start_date_{{$key2}}" class="form-control start_date" value="">
                                  @else
                                    <input type="text" name="start_date_[{{$key2}}]" id="start_date_{{$key2}}" class="form-control start_date" value="{{ $value2->mulai_jadwal_date->format('d/M/Y')}}">                                
                                  @endif
                                </td>
                                <td>
                                  @if ( $value2->selesai_jadwal_date == NULL )                              
                                      <input type="text" name="end_date_[{{$key2}}]" id="end_date_{{$key2}}" class="form-control end_date" >
                                  @else
                                    <input type="text" name="end_date_[{{$key2}}]" id="end_date_{{$key2}}" class="form-control start_date" value="{{ $value2->selesai_jadwal_date->format('d/M/Y') }}"> 
                                  @endif
                                </td>
                                <td>
                                  @if ( $value2->selesai_actual_date == "" )

                                  @else
                                    {{ $value2->selesai_actual_date->format("d/M/Y")}}
                                  @endif
                                </td>
                                <td>
                                  @if ( $value2->selesai_actual_date == "" )

                                  @else
                                    @if ($value2->selesai_jadwal_date <= $value2->selesai_actual_date)
                                      {{ (strtotime($value2->selesai_actual_date) - strtotime($value2->selesai_jadwal_date)) / (60 * 60 * 24)}}
                                    @endif
                                  @endif
                                </td>
                                <td style="text-align:right">
                                  <input type="hidden" name="" id="progres_sebelum" value="{{ $value2->progress_sebelumnya }}">
                                  {{ number_format($value2->progress_sebelumnya*100,2)}}</td> 
                                <td style="text-align:right">
                                  <input type="hidden" name="unit_progress_id[{{ $key2}}]" value="{{ $value2->id }}">
                                  @if ( $value2->mulai_jadwal_date != "" && $value2->selesai_jadwal_date != "" )
                                  <input type="hidden" class="form-control nilai_budget" name="progress_saat_ini_[{{ $key2}}]" value="{{ number_format($value2->progresslapangan_percent,2) * 100 }}" autocomplete="off" /> 
                                  <span>{{ number_format($value2->progresslapangan_percent*100,2)  }}</span>
                                  @endif
                                  </td>      
                                <td style="text-align:right"> {{ number_format ( $real_bobot_s = ( ( $value2->progresslapangan_percent*100 ) / 100 ) * $bobot_rab_baru,2)}}</td> 
                              <!--  <td>
                                  @if ( $value2->mulai_jadwal_date != "" && $value2->selesai_jadwal_date != "" )
                                  <a href="{{ url('/')}}/progress/tambah?id={{$value2->id}}" class="btn btn-info">Tambah Progress</a>
                                  @endif
                                </td>  -->                              
                                <td>
                                  @if ( $value2->mulai_jadwal_date != "" && $value2->selesai_jadwal_date != "" )
                                  <!-- @php $item_pekerjaan = \Modules\Spk\Entities\IpkTambahan::where('itempekerjaan_id',$value2->itempekerjaan_id)->where('spk_id',$spk->id)->where('unit_id',$unit->id)->get();@endphp
                                      @if ($item_pekerjaan->count() != 0 )
                                        @if($item_pekerjaan->where('status',0)->count()!=0)
                                          <a href="{{ url('/')}}/progress/update-ipk?idspk={{$spk->id}}&iditem={{$value2->itempekerjaan_id}}&idunit={{$value2->unit_id}}" class="btn btn-warning">IPK</a>
                                        @else
                                          <a href="{{ url('/')}}/progress/update-progress?idspk={{$spk->id}}&iditem={{$value2->itempekerjaan_id}}&idunit={{$value2->unit_id}}" class="btn btn-info">Progress</a>
                                        @endif
                                      @else
                                      <a href="{{ url('/')}}/progress/update-progress?idspk={{$spk->id}}&iditem={{$value2->itempekerjaan_id}}&idunit={{$value2->unit_id}}" class="btn btn-info">Progress</a>
                                      @endif -->
                                      <a href="{{ url('/')}}/progress/update-progress?idspk={{$spk->id}}&iditem={{$value2->itempekerjaan_id}}&idunit={{$value2->unit_id}}" class="btn btn-info">Progress</a>
                                  @endif
                                </td>
                            </tr>   
                            @php 
                              $nilai = $nilai + ($bobot_rab_baru ); 
                              $real_bobot = $real_bobot + $real_bobot_s; @endphp
                            @endif
                        @endif
                      @endforeach 
                      <tr>
                        <td>Total</td>
                        <td style="text-align:right">{{ number_format($nilai,2) }} %</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td style="text-align:right"><input type="hidden" name="label_progress_val" id="label_progress_val" value="{{ number_format($real_bobot,2) }}"/>{{ number_format($real_bobot,2) }} %</td>
                        <td>&nbsp;</td>
                      </tr>                       
                    </tbody>
                  </table>
                </div>
                
                <!-- <label>Keterangan</label>
                <textarea name="description" rows="3" class="form-control"></textarea> -->

                @php $jml = 0; @endphp
                @foreach($vo->where('tipe',1) as $key => $value3)
                  @if($value3->approval->approval_action_id == 6)
                    @if(count($value3->detail->where("unit_id",$unit->id)) != 0)
                      @foreach($value3->detail as $idvodetail)
                        <input type="hidden" name="idvod[]" id="idvod[]" value="{{$idvodetail->id}}">
                      @endforeach
                      <form class="form-horizontal" action="{{ url('/progress/saveschedulevo') }}" method="post" autocomplete="off" name="form-oe" id="form-oe">
                          {{ csrf_field() }}
                        @php
                            $no = 1;
                        @endphp
                        <center><h3>Variation Order {{$no}} : {{$value3->no}}</h3></center>
                        <input type="hidden" value="{{$id_unit}}" name="id_unit">
                        @php $jml = $key; @endphp
                        <button type="submit" class="btn btn-primary" id="btnsimpan1">Simpan Jadwal Progress VO</button>
                        <table class="table table-bordered">
                            <thead class="head_table">
                              <tr>
                                <td>Item Pekerjaan</td>   
                                <td>Bobot RAB(%)</td> 
                                <td>Target Tanggal Mulai</td>
                                <td>Target Tanggal Selesai</td>
                                <td>Real Tanggal 100%</td>
                                <td>Delta Hari</td>  
                                <td>Progress s/d Lalu(%)</td>
                                <td>Progress s/d Skrg(%)</td>    
                                <td>Nilai Bobot(%)</td>    
                                <td>Tambah Progress</td>
                              </tr>
                            </thead>
                          <tbody>
                            @php $nilai = 0; $real_bobot = 0; $total = 0; @endphp
                              @foreach ( $value3->detail->where("unit_id", $unit->id) as $key2 => $value )
                                  @php 
                                    if(0 <= $value->volume){
                                      $total = $total + ($value->total_nilai);
                                    }
                                    $i = 0;
                                  @endphp
                              @endforeach
                              @foreach ( $value3->detail->where("unit_id", $unit->id) as $key2 => $value2 )
                                  @if(0 <= $value2->volume)
                                    <tr style="{{ $arrayEscrow[$value2->itempekerjaan->escrow_id]['style'] }}">                                
                                        <td>
                                          <input type="hidden" value="{{$value3->spk_id}}" name="idspk">
                                          <input type="hidden" value="{{$value2->id}}" name="idvod[{{$i}}]">
                                          <input type="hidden" name="unitprogress_id_vo_[{{$i}}]" id="unitprogress_id_vo_[{{$key2}}]" value="{{ $value2->id}}">
                                          {{ $value2->itempekerjaan->name }}<br> {{ $arrayEscrow[$value2->itempekerjaan->escrow_id]['label']  }}
                                        </td> 
                                        <td>{{ number_format(($value2->total_nilai)/$total *100,2) }} </td>
                                        <td>
                                          @if ( $value2->mulai_jadwal_date == NULL )
                                            <input type="text" name="start_date_vo_[]" id="" class="form-control start_date" value="">
                                          @else
                                            <input type="text" name="start_date_vo_[]" id="" class="form-control start_date" value="{{ $value2->mulai_jadwal_date}}">                                
                                          @endif
                                        </td>
                                        <td>
                                          @if ( $value2->selesai_jadwal_date == NULL )                              
                                              <input type="text" name="end_date_vo_[]" id="" class="form-control end_date" >
                                          @else
                                            <input type="text" name="end_date_vo_[]" id="" class="form-control start_date" value="{{  $value2->selesai_jadwal_date }}"> 
                                          @endif
                                        </td>
                                        <td>
                                          @if ( $value2->selesai_actual_date == "" )

                                          @else
                                            {{ date("d/M/Y", (strtotime($value2->selesai_actual_date)))}}
                                          @endif
                                        </td>
                                        <td>
                                          @if ( $value2->selesai_actual_date == "" )

                                          @else
                                            @if ($value2->selesai_jadwal_date <= $value2->selesai_actual_date)
                                              {{ (strtotime($value2->selesai_actual_date) - strtotime($value2->selesai_jadwal_date)) / (60 * 60 * 24)}}
                                            @endif
                                          @endif
                                        </td>
                                        <td>
                                          <input type="hidden" name="" id="progres_sebelum" value="{{ $value2->progress_sebelumnya }}">
                                          {{ number_format($value2->progress_sebelumnya*100,2)}}</td> 
                                        <td>
                                          <input type="hidden" name="unit_progress_id[{{ $i}}]" value="{{ $value2->id }}">
                                          @if ( $value2->mulai_jadwal_date != "" && $value2->selesai_jadwal_date != "" )
                                          <input type="hidden" class="form-control nilai_budget" name="progress_saat_ini_[{{ $i}}]" value="{{ number_format($value2->progresslapangan_percent,2) * 100 }}" autocomplete="off" /> 
                                          <span>{{ number_format($value2->progresslapangan_percent*100,2)  }}</span>
                                          @endif
                                          </td>      
                                        <td> {{ number_format ( $real_bobot_s = ( ( $value2->progresslapangan_percent*100 ) / 100 ) * ($value2->total_nilai)/$total *100,2)}}</td> 
                                      <!--  <td>
                                          @if ( $value2->mulai_jadwal_date != "" && $value2->selesai_jadwal_date != "" )
                                          <a href="{{ url('/')}}/progress/tambah?id={{$value2->id}}" class="btn btn-info">Tambah Progress</a>
                                          @endif
                                        </td>  -->                              
                                        <td>
                                          @if ( $value2->mulai_jadwal_date != "" && $value2->selesai_jadwal_date != "" )
                                            <a href="{{ url('/')}}/progress/update-progressvo?idspk={{$spk->id}}&iditem={{$value2->itempekerjaan_id}}&idunit={{$value2->unit_id}}&dvoid={{$value2->id}}" class="btn btn-info">Progress</a>
                                          @endif
                                        </td>
                                    </tr>   
                                    @php $nilai = $nilai + ($value2->bobot_rab * ($spk->details->count()) ); $real_bobot = $real_bobot + $real_bobot_s; 
                                    $i += 1;
                                    @endphp
                                    @endif
                              @endforeach 
                              <tr>
                                <td>Total</td>
                                <td>{{ number_format($nilai,2) }} %</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td><input type="hidden" name="label_progress_val" id="label_progress_val" value="{{ number_format($real_bobot,2) }}"/>{{ number_format($real_bobot,2) }} %</td>
                                <td>&nbsp;</td>
                              </tr>                       
                            </tbody>
                        </table>
                        @php
                            $no++;
                        @endphp
                      </form>
                    @endif
                  @endif
                @endforeach
                  <input type="hidden" name="" id="jml" value="{{$jml}}">
                  <!-- <label>Keterangan</label>
                  <textarea name="description" rows="3" class="form-control"></textarea> -->
               
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
  <!-- /.content-wrapper -->
@include("master/copyright")
  
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

@include("master/footer_table")
@include("progress::app")
<script type="text/javascript">
  $(".progress").number(true,2);
  $("#label_progress").text($("#label_progress_val").val() + " %");
  
  $(document).ready(function(){
   
    console.log($("#jml").val());
    //$(".start_date").datepicker();
    //$(".end_date").datepicker();
    $(".start_date").datepicker({
          minDate: new Date($(".start_date").val()),
          dateformat : 'yy/mm/dd'
      });

      $(".end_date").datepicker({
          minDate: new Date($(".start_date").val()),
          dateformat : 'yy/mm/dd'
      });

    for ( var i=0; i < $("#unitprogress").val(); i++ ){

      // $("#start_date_" + i).datepicker({
      //     minDate: new Date($("#start_date").val()),
      //     dateformat : 'yy/mm/dd'
      // });

      // $("#end_date_" + i).datepicker({
      //     maxDate: new Date($("#finish_date").val()),
      //     dateformat : 'yy/mm/dd'
      // });

      // $("#start_date_vo_" + i).datepicker({
      //     minDate: new Date($("#start_date").val()),
      //     dateformat : 'yy/mm/dd'
      // });

      // $("#end_date_vo_" + i).datepicker({
      //     maxDate: new Date($("#finish_date").val()),
      //     dateformat : 'yy/mm/dd'
      // });
    }

    // for ( var i=0; i < parseInt($("#jml").val()); i++ ){
    //   $("#start_date_vo_" + i).datepicker({
    //       minDate: new Date($("#start_date").val()),
    //       dateformat : 'yy/mm/dd'
    //   });

    //   $("#end_date_vo_" + i).datepicker({
    //       maxDate: new Date($("#finish_date").val()),
    //       dateformat : 'yy/mm/dd'
    //   });

    // }
    
  });

</script>
</body>
</html>
