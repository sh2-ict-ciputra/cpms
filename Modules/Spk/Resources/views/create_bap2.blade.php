<!DOCTYPE html>

<html>

<head>

  <meta charset="utf-8">

  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <title>Admin QS | Dashboard </title>



  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

  @include("master/header")

    <!-- Select2 -->

  <link rel="stylesheet" href="{{ url('/')}}/assets/bower_components/select2/dist/css/select2.min.css">

  <style>
    .right {
           text-align: right;
      }
  </style>
</head>

<body class="hold-transition skin-blue sidebar-mini">

<div class="wrapper">



  @include("master/sidebar_project")



  <!-- Content Wrapper. Contains page content -->

  <div class="content-wrapper">

    <!-- Content Header (Page header) -->

    <section class="content-header">

      <h1>Data SPK</h1>



    </section>



    <!-- Main content -->

    <section class="content">



      <!-- SELECT2 EXAMPLE -->

      <div class="box box-default">



        <!-- /.box-header -->

        <div class="box-body">

          <div class="row">

            <div class="col-md-12">   



            <h3 class="box-title">Detail Data BAP</h3>           

              <!-- Main content -->

            <section class="invoice">

              <!-- title row -->

              <div class="row">

                <div class="col-xs-12">

                  <h2 class="page-header">

                    <i class="fa fa-globe"></i> SPK NO : <strong>{{ $spk->no }}</strong>

                    <small class="pull-right">Tanggal  : {{ $spk->date }}</small>

                  </h2>

                </div>

                <!-- /.col -->

              </div>






              <div class="row">

                <!-- accepted payments column -->

                <!-- /.col -->

                <form action="{{ url('/')}}/spk/save-bap" method="post" name="form1">

                <input type="hidden" name="spk_bap" value="{{ $spk->id }}">

                <input type="hidden" name="spk_bap_termin" value="{{ $spk->baps->count() + 1 }}">

                {{ csrf_field() }}

                <div class="">

                  <p class="lead">Detail Nilai</p>

                    <div class="page" style="page-break-after: always;">
                        <div class="panel panel - default">
                            <table style="width: 100%;border: 1px solid black;">
                                <tr>
                                    <th style="text-align: center;background-color: lightgray;font-size: 20px">SERTIFIKAT PEMBAYARAN</th>
                                </tr>
                                <tr>
                                    <th style="text-align: center;background-color: lightgray;font-size: 20px">Ke : {{ $spk->baps->count() + 1 }}</th>
                                </tr>
                            </table>
                        </div>

                        <div class="page panel-body">
                            <div class="row">
                                <div class="col-md-12"> 
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th style="width: 15%">Proyek :</th><th style="width: 30%">{{$project->name}}</th>
                                                <th style="width: 5%"></th>
                                                <th style="width: 20%;" >No SPK </th><th style="width: 5%" class="right"></th><th style="width: 25%" >{{ $spk->no }}</th>
                                            </tr>
                                            <tr>
                                                <th rowspan="3" style="vertical-align:top;">Pekerejaan :</th>
                                                <th rowspan="3" style="vertical-align:top;">
                                                ({{$item_pekerjaan->code}})
                                                  <br/>
                                                {{$item_pekerjaan->name}}
                                                </th>
                                                <th></th>
                                                <th>Nilai DPP </th><th class="right">Rp</th>
                                                <th class="right">{{ number_format($spk->nilai)}}</th>
                                            </tr>
                                            <tr>
                                                <th></th>
                                                <th>Nilai VO kumulatif </th>
                                                <th class="right">Rp</th>
                                                <th class="right" style="">{{ number_format($spk->nilai_vo) }}</th>
                                                <input type="hidden" name="bap_vo" value="{{ $spk->nilai_vo }}">
                                            </tr>
                                            <tr>
                                                <th></th>
                                                <th>Nilai Percepatan </th>
                                                <th class="right">Rp</th>
                                                <th class="right" style="border-bottom: 3px solid black;">{{ number_format($spk->nilai_percepatan) }}</th>
                                                <input type="hidden" name="bap_percepatan" value="{{ $spk->nilai_percepatan }}">
                                            </tr>
                                            <tr>
                                                <th rowspan="2" style="vertical-align:top;">Jenis Pekerjaan :</th>
                                                <th rowspan="2" style="vertical-align:top;">{{$spk->tender->rab->name}}</th>
                                                <th></th>
                                                <th class="right">Sub Total DPP </th><th class="right">Rp</th><th class="right">{{ number_format($sub_total_dpp = $spk->nilai + $spk->nilai_vo + $spk->nilai_percepatan)}}</th>
                                            </tr>
                                            <tr>
                                                <th></th>
                                                <th class="right">PPN = 10% </th><th class="right">Rp</th><th class="right" style="border-bottom: 3px solid black;">{{ number_format($ppn_dpp = $sub_total_dpp * $ppn)}}</th>
                                            </tr>
                                            <tr>
                                                <th></th><th></th>
                                                <th></th>
                                                <th class="right">Nilai Akhir SPK </th><th class="right">Rp</th><th class="right">{{ number_format($sub_total_dpp + $ppn_dpp)}}</th>
                                            </tr>
                                            <tr>
                                                <th></th><th></th>
                                                <th></th>
                                                <th></th><th></th><th></th>
                                            </tr>
                                            <tr>
                                                <th colspan="2" rowspan="3" style="vertical-align:top;">Bersama ini menerangkan bahwa    : </th>
                                                <th colspan="2">{{ strtoupper($spk->rekanan->group->name) }} </th><th></th><th></th>
                                            </tr>
                                            <tr>
                                                <th colspan="2">{{ ($spk->rekanan->surat_alamat) }} </th><th></th><th></th>
                                            </tr>
                                            <tr>
                                                <th colspan="2"></th><th></th><th></th>
                                            </tr>
                                        </thead>
                                    </table>     

                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th colspan="5">Telah berhak menerima pembayaran atas kemajuan pekerjaan yang sudah diselesaikan sampai dengan tanggal: {{date("d-M-Y",strtotime($tanggal_last_progress))}}</th>
                                            </tr>
                                            <tr>
                                                <th colspan="5">sesuai dengan kwitansi yang diterima oleh <strong> {{$spk->tender->rab->pt->name}} </strong> tanggal {{date("d-M-Y",strtotime($tanggal_sekarang))}} .</th>
                                            </tr>
                                            <tr>
                                                <th colspan="2">Dengan rincian sebagai berikut :</th>
                                                <th></th><th></th><th></th>
                                            </tr>
                                            <tr>
                                                <th colspan="2">Progres lapangan hingga saat ini </th>
                                                <th class="right">{{ ($spk->lapangan) }}%
                                                <input type="hidden" name="percentage_lapangan" value="{{  $spk->lapangan }}">
                                                </th>
                                                <th></th><th></th>
                                            </tr>
                                            <tr>
                                                <th colspan="2">Kumulatif progres yang sudah dibayarkan sebelumnya </th>
                                                <th class="right">
                                                {{ ($spk->progress_sebelumnya)}}%
                                                <input type="hidden" name="percentage_sebelumnya" value="{{ $spk->progress_sebelumnya }}">
                                                </th>
                                                <th></th><th></th>
                                            </tr>
                                            <tr>
                                                <th colspan="2">Progres yang bisa dibayarkan saat ini </th>
                                                <th class="right">
                                                {{ $spk->lapangan - $spk->progress_sebelumnya }}%
                                                <input type="hidden" name="percentage_saat_ini" value="{{ $spk->lapangan - $spk->progress_sebelumnya }}">
                                                </th>
                                                <th></th><th></th>
                                            </tr>
                                            <tr>

                                                <th colspan="2">Nilai Progres yang diproses saat ini</th>
                                                <th class="right">{{ $progress = $persen_dibayarkan}}%</th>
                                                @if(2 <= $st_status)
                                                    <th class="right">Rp</th><th class="right"> {{number_format($nilai_progres_hingga_saat_ini = ($sub_total_dpp - $pembayaran_saat_ppnpph)*$percent_retensi )}}</th>
                                                @else
                                                    <th class="right">Rp</th><th class="right"> {{number_format($nilai_progres_hingga_saat_ini = ($progress/100)*$sub_total_dpp)}}</th>
                                                @endif
                                                <input type="hidden" name="nilai_bap_1" value="{{ $nilai_progres_hingga_saat_ini }}">
                                                <input type="hidden" name="percentage" value="{{  $progress }}"></td>
                                                <input type="hidden" name="st_status" value="{{$st_status}}">
                                            </tr>
                                            <tr>
                                                <th colspan="2">Nilai Talangan </th>
                                                <th></th>
                                                <th class="right">(+) Rp</th><th class="right"> {{number_format($nilai_talangan = 0)}} </th>
                                                <input type="hidden" name="talangan" value="{{  $nilai_talangan }}"></td>
                                            </tr>
                                            <tr>
                                                @if ($spk->lapangan != null)
                                                    @if ( $spk->progress_sebelumnya == 100)
                                                        <th colspan="2">Retensi .....% dari Progress </th>
                                                        <th class="right">0%</th>
                                                        <th class="right">(-) Rp</th><th class="right"> {{number_format($retensi = 0)}} </th>
                                                        <input type="hidden" name="nilai_retensi" value="{{ $retensi }}">
                                                    @else
                                                        <th colspan="2">Retensi .....% dari Progress </th>
                                                        <th class="right">{{round($spk->retensis->sum('percent')*100, 4)}}%</th>
                                                        <th class="right">(-) Rp</th><th class="right"> {{number_format($retensi = $nilai_progres_hingga_saat_ini * ($spk->retensis->sum('percent')))}} </th>
                                                        <input type="hidden" name="nilai_retensi" value="{{ $retensi }}">
                                                    @endif
                                                @else
                                                    <th colspan="2">Retensi .....% dari Progress </th>
                                                    <th class="right">0%</th>
                                                    <th class="right">(-) Rp</th><th class="right"> {{number_format($retensi = 0)}} </th>
                                                    <input type="hidden" name="nilai_retensi" value="{{ $retensi }}">
                                                @endif
                                                
                                            </tr>
                                            <tr>
                                                <th colspan="2">Pengembalian DP </th>
                                                <th>
                                                    @if ($spk->tender->aanwijing->jenis_pembayaran != null)
                                                        @if ($spk->tender->aanwijing->jenis_pembayaran->id == 3)
                                                            @if ($spk->lapangan != null && $spk->baps->count() + 1 != 1)
                                                                @if ($spk->pengembalian_dp->where("urutan", $spk->baps->count()) != null)
                                                                    {{$spk->pengembalian_dp->where("urutan", $spk->baps->count())->nilai}}%
                                                                @else
                                                                    
                                                                @endif
                                                            @endif
                                                        @endif
                                                    @else
                                                        
                                                    @endif

                                                </th>
                                                <th class="right">(-) Rp</th>
                                                <th class="right">
                                                    @if ($spk->tender->aanwijing->jenis_pembayaran != null)
                                                        @if ($spk->tender->aanwijing->jenis_pembayaran->id == 3) 
                                                            @if ($spk->lapangan != null && $spk->baps->count() + 1 != 1)
                                                                @if ($spk->pengembalian_dp->where("urutan", $spk->baps->count()) != null)
                                                                    {{number_format($pengembalian_dp = $spk->pengembalian_dp->where("urutan", $spk->baps->count())->nilai * $spk->baps[0]->nilai_bap_2 / 100)}}
                                                                @else
                                                                    {{number_format($pengembalian_dp = 0)}}
                                                                @endif
                                                            @else
                                                                {{number_format($pengembalian_dp = 0)}} 
                                                            @endif
                                                        @else
                                                            {{number_format($pengembalian_dp = 0)}} 
                                                        @endif
                                                    @else
                                                     {{number_format($pengembalian_dp = 0)}}
                                                    @endif
                                                    <input type="hidden" name="nilai_pengembalian" value="{{ $pengembalian_dp}}">
                                                </th>
                                            </tr>
                                            <tr>
                                                <th colspan="2">Pengembalian Talangan </th>
                                                <th></th>
                                                <th class="right">(-) Rp</th><th class="right" style=""> {{number_format($pembayaran_kumulatif = 0)}} </th>
                                            </tr>
                                            <tr>
                                                <th colspan="2">Kekurangan Bayar Vo </th>
                                                <th></th>
                                                <th class="right">(+) Rp</th>
                                                <th class="right" style="border-bottom: 3px solid black;">
                                                @if($spk->lapangan == 100 && $st_status < 1)
                                                    {{number_format($kekurangan_bayar_vo =$sub_total_dpp - (( $nilai_progres_hingga_saat_ini + $nilai_talangan - $retensi - $pengembalian_dp - $pembayaran_kumulatif) + $spk->baps->sum('nilai_bap_2')) - ($sub_total_dpp*$spk->retensis->sum('percent')))}}
                                                    
                                                    {{-- {{number_format($kekurangan_bayar_vo = 0)}} --}}
                                                @else
                                                    {{number_format($kekurangan_bayar_vo = 0)}}
                                                @endif
                                                </th>
                                                <input type="hidden" name="kurang_bayar_vo" value="{{ $kekurangan_bayar_vo }}">
                                            </tr>
                                            <tr>
                                                <th></th><th colspan="">Nilai Progres yg dibayarkan dlm sertifikat ini </th>
                                                <th></th>
                                                <th class="right">Rp</th>
                                                <th class="right"> {{number_format($nilai_progres = $nilai_progres_hingga_saat_ini + $nilai_talangan - $retensi - $pengembalian_dp - $pembayaran_kumulatif + $kekurangan_bayar_vo)}} 
                                                <input type="hidden" name="nilai_bap_2" value="{{ $nilai_progres }}">
                                                </th>
                                            </tr>
                                            <tr>
                                              @if(1 < $st_status)
                                                  <th></th><th colspan="">PPN </th>
                                                  <th class="right">0%</th>
                                                  <th class="right">(+) Rp</th><th class="right"> {{ number_format($ppn_nilai_progres = 0)}}
                                                  <input type="hidden" name="ppn" value="0">
                                                  </th>
                                              @elseif ($spk->progress_sebelumnya == 100)
                                                  <th></th><th colspan="">PPN </th>
                                                  <th class="right">{{$ppn*100}}%</th>
                                                  <th class="right">(+) Rp</th><th class="right"> {{ number_format($ppn_nilai_progres = $sub_total_dpp * $ppn * ($spk->retensis->sum('percent')))}}
                                                  <input type="hidden" name="ppn" value="{{  $ppn }}">
                                                  </th>
                                                  </th>
                                              @else
                                                  <th></th><th colspan="">PPN </th>
                                                  <th class="right">{{$ppn*100}}%</th>
                                                  <th class="right">(+) Rp</th><th class="right"> {{ number_format($ppn_nilai_progres = $nilai_progres * $ppn)}}
                                                  <input type="hidden" name="ppn" value="{{  $ppn }}">
                                                  </th>
                                              @endif
                                              <input type="hidden" name="nilai_ppn" value="{{$ppn_nilai_progres}}">
                                            </tr>
                                            <tr>
                                              @if(1 < $st_status)
                                                  <th></th><th colspan="">PPh </th>
                                                  <th class="right">0%</th>
                                                  <th class="right">(-) Rp</th><th class="right" style="border-bottom: 3px solid black;"> {{number_format($pph_nilai_progres = 0)}}
                                                  <input type="hidden" name="pph" value="0">
                                                  </th>
                                              @elseif ($spk->progress_sebelumnya == 100)
                                                  <th></th><th colspan="">PPh </th>
                                                  <th class="right">{{$spk->coa_pph_default_id}}%</th>
                                                  <th class="right">(-) Rp</th><th class="right" style="border-bottom: 3px solid black;"> {{number_format($pph_nilai_progres = ($sub_total_dpp * ($spk->coa_pph_default_id/100) * ($spk->retensis->sum('percent')))+(($sub_total_dpp*($spk->coa_pph_default_id/100))-($spk->baps->sum('nilai_pph')+($sub_total_dpp * ($spk->coa_pph_default_id/100) * ($spk->retensis->sum('percent'))))))}}
                                                  <input type="hidden" name="pph" value="{{  $spk->coa_pph_default_id/100 }}">
                                                  </th>
                                              @else
                                                  <th></th><th colspan="">PPh </th>
                                                  <th class="right">{{$spk->coa_pph_default_id}}%</th>
                                                  <th class="right">(-) Rp</th><th class="right" style="border-bottom: 3px solid black;"> {{number_format($pph_nilai_progres = $nilai_progres * ($spk->coa_pph_default_id /100))}}
                                                  <input type="hidden" name="pph" value="{{  $spk->coa_pph_default_id/100 }}">
                                                  </th>
                                              @endif
                                              <input type="hidden" name="nilai_pph" value="{{$pph_nilai_progres}}">
                                            </tr>
                                            <tr>
                                                <th></th><th colspan="">Nilai Sertifikat ini </th>
                                                <th></th>
                                                <th class="right">Rp</th>
                                                <th class="right"> {{number_format($nilai_sertifikat = $nilai_progres + $ppn_nilai_progres - $pph_nilai_progres)}}
                                                <input type="hidden" name="nilai_bap_3" value="{{ $nilai_sertifikat }}">
                                                </th>
                                            </tr>
                                            <tr>
                                                <th></th><th colspan="">Potongan meterai .... lbr @ Rp 6.000 </th>
                                                <th></th>
                                                <th class="right">(-) Rp</th>
                                                <th class="right"> 
                                                    <input type="text" id="admin" name="admin" class="form-control right" value="0" style="text-align:right;margin-right=0px">
                                                </th>
                                            </tr>
                                            <tr>
                                                <th></th><th colspan="">Potongan denda keterlambatan </th>
                                                <th></th>
                                                <th class="right" style="border-bottom: 3px solid black;">(-) Rp</th>
                                                <th class="right" style="border-bottom: 3px solid black;">
                                                  @if($spk->progress_sebelumnya != 100 && $spk->lapangan == 100)
                                                    {{number_format($denda = 0)}}
                                                  @else
                                                    {{number_format($denda = 0)}}
                                                    {{-- {{number_format($denda = $spk->nilai*($spk->tender->aanwijing->denda/1000) * $jumlah_hari_telat)}} --}}
                                                  @endif
                                                  <input type="hidden" id="denda" name="denda" class="form-control" value="{{$denda}}" style="width:50%">
                                                </th>
                                            </tr>
                                            <tr>
                                                <th style="width: 10%"></th><th colspan="" style="width: 35%">Nilai Sertifikat yang dibayarkan </th>
                                                <th style="width: 5%"></th>
                                                <th class="right" style="width: 25%">Rp</th>
                                                <th class="right" style="width: 25%"> 
                                                    <input type="hidden" class="form-control right" name=""  id="nilai_bap_dibayar_untuk_dihitung" value="{{round($nilai_dibayarkan = $nilai_sertifikat - $denda)}} ">
                                                    {{-- {{$nilai_sertifikat}}<br>
                                                    {{$denda}} --}}
                                                    <input type="text" class="form-control right" name="nilai_bap_dibayar" id="nilai_bap_dibayar" value="{{number_format($nilai_dibayarkan = $nilai_sertifikat - $denda)}} " readonly>
                                                </th>
                                            </tr>
                                        </thead>
                                    </table>   
                                    
                                    <table style="width: 100%;border: 1px solid black;top-margin: 20px; background-color: lightgray;page-break-after: always;">
                                        <tr>
                                            <th colspan="3">Nilai kumulatif sampai sertifikat ini ( Excl PPN) 
                                            </th>
                                            <th class="right" style="width: 25%">Rp</th>
                                            @if ($spk->progress_sebelumnya == 100)
                                            <!-- {{$st_status}} -->
                                                @if(1 < $st_status)
                                                    <th class="right" style="width: 25%"> 
                                                    {{number_format($nilai_kumulatif_saatini =  $pembayaran_saat_ini + $nilai_progres)}}
                                                    </th>
                                                @else
                                                    <th class="right" style="width: 25%"> 
                                                    {{number_format($nilai_kumulatif_saatini =  $pph_nilai_progres + $spk->baps->sum('nilai_bap_2') )}}
                                                    </th>
                                                @endif
                                            @else
                                                <th class="right" style="width: 25%"> 
                                                {{number_format($nilai_kumulatif_saatini =  $nilai_progres + $spk->baps->sum('nilai_bap_2'))}}
                                                 </th>
                                            @endif
                                            <input type="hidden" name="nilai_pembayaran_saat_ini" value="{{$nilai_kumulatif_saatini}}">
                                        </tr>
                                        <tr>
                                            <th colspan="3">Nilai sisa kontrak ( Excl PPN) </th>
                                            <th class="right">Rp</th><th class="right"> {{ number_format($sub_total_dpp - $nilai_kumulatif_saatini)}} </th>
                                        </tr>
                                    </table>

                                    <!-- <table style="width: 100%;border: 1px solid black;">
                                        <tr>
                                            <th style="text-align: center;background-color: lightgray;font-size: 20px">RINCIAN NILAI AKHIR SPK</th>
                                        </tr>
                                    </table>
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Nilai DPP</th> <th style="width: 30%" class="right"></th> <th style="width: 30%" class="right">2900000000</th>
                                            </tr>
                                            <tr>
                                                <th>Nilai VO-1</th> <th style="border-bottom: 3px solid black;" class="right"></th> <th class="right"></th>
                                            </tr>
                                            <tr>
                                                <th class="right">Sum Nilai VO</th> <th class="right"></th> <th class="right" style="border-bottom: 3px solid black;"></th>
                                            </tr>
                                            <tr>
                                                <th class="right">Sub Total</th> <th class="right"></th> <th class="right" style="border-bottom: 3px solid black;"></th>
                                            </tr>
                                            <tr>
                                                <th class="right">PPN = 10 %</th> <th class="right"></th> <th class="right" style="border-bottom: 3px solid black;"></th>
                                            </tr>
                                            <tr>
                                                <th class="right">Nilai Akhir SPK</th> <th class="right"></th> <th class="right"></th>
                                            </tr>
                                        </thead>
                                    </table>  

                                    <h1>&nbsp;</h1>

                                    <table style="width: 100%;border: 1px solid black;">
                                        <tr>
                                            <th style="text-align: center;background-color: lightgray;font-size: 20px">RINCIAN DANA TALANGAN</th>
                                        </tr>
                                    </table>
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Dana Talangan</th> <th style="width: 30%" class="right"></th> <th style="width: 30%" class="right"></th>
                                            </tr>
                                            <tr>
                                                <th>GROSS UP PPh</th> <th class="right">3%</th> <th class="right"></th>
                                            </tr>
                                            <tr>
                                                <th>Nilai Dana Talangan stlh gross up</th> <th class="right"></th> <th class="right" ></th>
                                            </tr>
                                            <tr>
                                                <th class="right">PPN</th> <th class="right"> 10%</th> <th class="right" style="border-bottom: 3px solid black;"></th>
                                            </tr>
                                            <tr>
                                                <th>Total Akhir Dana Tanganan</th> <th class="right"></th> <th class="right"></th>
                                            </tr>
                                        </thead>
                                    </table>   -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- /.col -->

                <div class="col-md-6" style="display: none;">

                  <table class="table table-bordered">

                    <thead class="head_table">

                      <tr>

                        <td>Unit</td>

                        <td>Progress Lapangan</td>

                        <td>Persentase Dibayar</td>

                      </tr>

                    </thead>

                    <tbody>

                      @foreach ( $spk->details as $key5 => $value5 )

                      <tr>

                        <td>
                        {{ $value5->asset }}
                        </td>

                        <td>

                          {{ $value5->progress }}

                          <input type="hidden" value="{{ $value5->progress }}" name="progress_summary">

                          @foreach ( $value5->details_with_vo as $key7 => $value7 )

                            <li>{{ $value7->unit_progress->itempekerjaan->name }}</li>

                          @endforeach

                        </td>

                        <td>

                            <span>Input BAP Persentase</span>

                            @foreach ( $value5->details_with_vo as $key6 => $value6 )

                            <input type="hidden" class="form-control" name="spkvo_unit_id[{{$key6}}]" value="{{ $value6->unit_progress_id }}">

                            <input type="hidden" class="form-control" name="terbayar_percent[{{$key6}}]" value="">

                            @endforeach

                        </td>

                      </tr>

                      @endforeach

                    </tbody>

                  </table>

                </div>

              </div>

              <!-- /.row -->



              <!-- this row will not appear when printing -->

              <div class="row no-print">

                <div class="col-xs-12">
                  @if ( $status_submit == 1)
                  <button type="submit" class="btn btn-success pull-right"><i class="fa fa-credit-card"></i> Submit BAP
                  @else
                  <h3><span style="color:red"><strong>BAP Tidak bisa diproses karena progress lapangan tidak memenuhi syarat </strong></span></h3>
                  Alasan :
                  <ul>
                    @if ( $spk->nilai_pengembalian > $spk->nilai_setelah_retensi )
                    <li>Nilai Pengembalian DP lebih besar dari Nilai BAP setelah retensi</li>
                    @endif
                  </ul>
                  @endif
                  </button>

                </div>

              </div>

            </section>

            <!-- /.content -->

            </div>



            </form>

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

  <!-- /.content-wrapper -->

  <footer class="main-footer">

    <div class="pull-right hidden-xs">

      <b>Version</b> 2.4.0

    </div>

    <strong>Copyright &copy; 2014-2016 <a href="https://adminlte.io">Almsaeed Studio</a>.</strong> All rights

    reserved.

  </footer>



  

  <!-- Add the sidebar's background. This div must be placed

       immediately after the control sidebar -->

  <div class="control-sidebar-bg"></div>



</div>

<!-- ./wrapper -->



@include("master/footer_table")

@include("spk::app")

<script type="text/javascript">

$(document).on('keyup', '#admin', function() {
 
  console.log($("#nilai_bap_dibayar_untuk_dihitung").val());
  if($(this).val()!=''){
    var admin  = parseInt($(this).val());
  }else{
    var admin = 0;
  }

  console.log(admin);
  var nilai = parseInt($("#nilai_bap_dibayar_untuk_dihitung").val())-admin;
  $(this).number(true);
  $("#nilai_bap_dibayar").val(nilai).number(true);
  // fnSetAutoNumeric($("#nilai_bap_dibayar"));
  // fnSetMoney($("#nilai_bap_dibayar"), nilai);
});

</script>

</body>

</html>

