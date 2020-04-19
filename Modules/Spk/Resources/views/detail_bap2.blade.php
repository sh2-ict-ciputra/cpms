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
                @php $dps = 0; @endphp
                <div class="">
                  <p class="lead">Detail Nilai</p>


                  <div class="page" style="page-break-after: always;">
                        <div class="panel panel - default">
                            <table style="width: 100%;border: 1px solid black;">
                                <tr>
                                    <th style="text-align: center;background-color: lightgray;font-size: 20px">SERTIFIKAT PEMBAYARAN</th>
                                </tr>
                                <tr>
                                    <th style="text-align: center;background-color: lightgray;font-size: 20px">Ke : {{ $bap->termin }}</th>
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
                                                <th>Nilai VO kumulatif </th><th class="right">Rp</th><th class="right" style="">{{ number_format($bap->nilai_vo)}}</th>
                                            </tr>
                                            <tr>
                                                <th></th>
                                                <th>Nilai Percepatan </th><th class="right">Rp</th><th class="right" style="border-bottom: 3px solid black;">{{ number_format($bap->nilai_percepatan)}}</th>
                                            </tr>
                                            <tr>
                                                <th rowspan="2" style="vertical-align:top;">Jenis Pekerjaan :</th>
                                                <th rowspan="2" style="vertical-align:top;">{{$spk->tender->rab->name}}</th>
                                                <th></th>
                                                <th class="right">Sub Total DPP </th><th class="right">Rp</th><th class="right">{{ number_format($sub_total_dpp = $spk->nilai + $bap->nilai_vo + $bap->nilai_percepatan)}}</th>
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
                                                <th colspan="5">Telah berhak menerima pembayaran atas kemajuan pekerjaan yang sudah diselesaikan sampai dengan tanggal: 
                                                    @php
                                                        $tanggal = null;
                                                    @endphp
                                                    @if ($bap->percentage_lapangan == 0)
                                                        {{date("d M Y" , strtotime($bap->date))}}
                                                    @else
            
                                                        @foreach ($spk->tender->units as $key => $value)
                                                            @foreach ($value->unit_progress as $key2 => $value2)
                                                                @if ( $tanggal == null)
                                                                    @php
                                                                        $tanggal = $value2->updated_at;
                                                                    @endphp
                                                                @elseif($tanggal < $value2->updated_at)
                                                                    @php
                                                                        $tanggal = $value2->updated_at;
                                                                    @endphp
                                                                @endif
                                                            @endforeach
                                                        @endforeach
                                                        {{date("d M Y" , strtotime($tanggal))}}
                                                    @endif
                                                </th>
                                            </tr>
                                            <tr>
                                                <th colspan="5">sesuai dengan kwitansi yang diterima oleh {{ strtoupper($spk->rekanan->group->name) }} tanggal {{date("d M Y" , strtotime($bap->date))}}.</th>
                                            </tr>
                                            <tr>
                                                <th colspan="2">Dengan rincian sebagai berikut :</th>
                                                <th></th><th></th><th></th>
                                            </tr>
                                            <tr>
                                                <th colspan="2">Progres lapangan hingga saat ini </th>
                                                <th class="right">{{ ($bap->percentage_lapangan)}}%
                                                <input type="hidden" name="percentage_lapangan" value="{{  $spk->lapangan }}">
                                                </th>
                                                <th></th><th></th>
                                            </tr>
                                            <tr>
                                                <th colspan="2">Kumulatif progres yang sudah dibayarkan sebelumnya</th>
                                                <th class="right">
                                                {{ ($bap->percentage_sebelumnyas) }}%
                                                <input type="hidden" name="percentage_sebelumnya" value="{{ $spk->progress_sebelumnya }}">
                                                </th>
                                                <th></th><th></th>
                                            </tr>
                                            <tr>
                                                <th colspan="2">Progres yang bisa dibayarkan saat ini </th>
                                                <th class="right">
                                                {{ $bap->percentage_saat_ini }}%
                                                <input type="hidden" name="percentage_sebelumnya" value="{{ $spk->progress_sebelumnya }}">
                                                </th>
                                                <th></th><th></th>
                                            </tr>
                                            <tr>
                                                <th colspan="2">Nilai Progres yang diproses saat ini </th>
                                                <th class="right">{{ $bap->percentage }}%</th>
                                                <th class="right">Rp</th><th class="right"> {{ number_format($bap->nilai_bap_1) }} </th>
                                                
                                                <input type="hidden" name="percentage" value="0">
                                                </td>
                                            </tr>
                                            <tr>
                                                <th colspan="2">Nilai Talangan </th>
                                                <th></th>
                                                <th class="right">+Rp</th><th class="right"> {{number_format($bap->nilai_talangan)}} </th>
                                            </tr>
                                            <tr>
                                              <th colspan="2">Retensi .....% dari Progress </th>
                                              <th class="right">
                                                @if ($bap->percentage_lapangan == 0)
                                                    
                                                @else
                                                  {{round($spk->retensis->sum('percent')*100, 4)}}%
                                                @endif
                                              </th>
                                              <th class="right">-Rp</th><th class="right"> {{number_format($bap->nilai_retensi)}} </th>
                                            </tr>
                                            <tr>
                                                <th colspan="2">Pengembalian DP </th>
                                                <th></th>
                                                <th class="right">-Rp</th><th class="right"> {{number_format($pengembalian_dp = 0)}} </th>
                                            </tr>
                                            <tr>
                                                <th colspan="2">Pengembalian Talangan</th>
                                                <th></th>
                                                <th class="right">-Rp</th><th class="right" style=""> {{number_format($pembayaran_kumulatif = 0)}} </th>
                                            </tr>
                                            <tr>
                                                <th colspan="2">Kekurangan Bayar Vo+Retensi</th>
                                                <th></th>
                                                <th class="right">+Rp</th><th class="right" style="border-bottom: 3px solid black;"> {{number_format($bap->nilai_kurang_bayar_vo)}} </th>
                                            </tr>
                                            <tr>
                                                <th></th><th colspan="">Nilai Progres yg dibayarkan dlm sertifikat ini </th>
                                                <th></th>
                                                <th class="right">Rp</th>
                                                <th class="right"> {{number_format($nilai_progres = $bap->nilai_bap_2)}} 
                                                <input type="hidden" name="nilai_bap_2" value="{{ $nilai_progres }}">
                                                </th>
                                            </tr>
                                            <tr>
                                                @if ($bap->st_status == 1)
                                                    <th></th><th colspan="">PPN </th>
                                                    <th class="right">{{$bap->ppn*100}}%</th>
                                                    <th class="right">+Rp</th><th class="right"> {{ number_format($ppn_nilai_progres = $sub_total_dpp * $bap->ppn * $spk->retensis->sum('percent'))}}</th>
                                                @else
                                                    <th></th><th colspan="">PPN </th>
                                                    <th class="right">{{$bap->ppn*100}}%</th>
                                                    <th class="right">+Rp</th><th class="right"> {{ number_format($ppn_nilai_progres = $nilai_progres * $bap->ppn)}}</th>
                                                @endif
                                            </tr>
                                            <tr>
                                              <th></th><th colspan="">PPh </th>
                                              <th class="right">{{number_format((float)$bap->pph*100, 2, '.', '')}}%</th>
                                              <th class="right">-Rp</th><th class="right" style="border-bottom: 3px solid black;"> {{number_format($pph_nilai_progres =  ($bap->nilai_pph) )}} </th>
                                            </tr>
                                            <tr>
                                                <th></th><th colspan="">Nilai Sertifikat ini </th>
                                                <th></th>
                                                <th class="right">Rp</th>
                                                <th class="right"> {{number_format($nilai_sertifikat = $bap->nilai_bap_3)}}
                                                <input type="hidden" name="nilai_bap_3" value="{{ $nilai_sertifikat }}">
                                                </th>
                                            </tr>
                                            <tr>
                                                <th></th><th colspan="">Potongan meterai .... lbr @ Rp 6.000 </th>
                                                <th></th>
                                                <th class="right">-Rp</th><th class="right"> 
                                                  {{number_format($bap->nilai_administrasi)}}
                                                </th>
                                            </tr>
                                            <tr>
                                                <th></th><th colspan="">Potongan denda keterlambatan </th>
                                                <th></th>
                                                <th class="right" style="border-bottom: 3px solid black;">-Rp</th>
                                                <th class="right" style="border-bottom: 3px solid black;">
                                                    {{number_format($denda = $bap->nilai_denda)}}
                                                    <input type="hidden" id="denda" name="denda" class="form-control" value="0" style="width:50%">
                                                </th>
                                            </tr>
                                            <tr>
                                                <th style="width: 10%"></th><th colspan="" style="width: 35%">Nilai Sertifikat yang dibayarkan </th>
                                                <th style="width: 5%"></th>
                                                <th class="right" style="width: 25%">Rp</th>
                                                <th class="right" style="width: 25%"> 
                                                    {{number_format($nilai_dibayarkan = $bap->nilai_bap_dibayar)}} 
                                                    <input type="hidden" name="nilai_bap_dibayar" value="{{ $nilai_dibayarkan }}">
                                                </th>
                                            </tr>
                                        </thead>
                                    </table>   
                                    
                                    <table style="width: 100%;border: 1px solid black;top-margin: 20px; background-color: lightgray;page-break-after: always;">
                                        <tr>
                                            <th colspan="3">Nilai kumulatif sampai sertifikat ini ( Excl PPN) </th>
                                                @if(1 <= $bap->st_status)
                                                    <th class="right" style="width: 25%">Rp</th><th class="right" style="width: 25%"> {{number_format($nilai_kumulatif_saatini = $spk->baps->where('termin','<=',$bap->termin)->sum('nilai_bap_2') + ($spk->baps->where('st_status',1)->first()->pph * $sub_total_dpp * $spk->retensis->sum('percent')))}} </th>
                                                @else
                                                    <th class="right" style="width: 25%">Rp</th><th class="right" style="width: 25%"> {{number_format($nilai_kumulatif_saatini =   $spk->baps->where('termin','<=',$bap->termin)->sum('nilai_bap_2'))}} </th>
                                                @endif
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
                <div class="col-md-6" style="display: none">
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
                        <td>{{ $value5->asset }}</td>
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
  function countPph()
  {
      var a = parseFloat($("#nilai_setelah_retensi").attr("data-value"));
        var b = 0;
        console.log(a);

      $('.percent').each(function()
      {
          b += parseFloat($(this).val());

          console.log(b);
        });

      console.log(a,b);
      var c = a * b / 100;
      $("#pph").val(c);
  }

  function countTerbayar()
      {
        var a = parseFloat($("#include_ppn").val());

        @if($spk->rekanan->piutangs->count())
          var b = parseFloat($("#piutang").val());
        @else
          var b = 0;
        @endif

        //var c = parseFloat($("#pph").val());
        var c = parseFloat(0);
        var d = parseFloat($("#admin").val());
        var e = parseFloat($("#denda").val());
        var f = parseFloat($("#selisih").val());
        
        if ($("#piutang").val() == "") 
        {
          $("#piutang").val("0");
          countTerbayar();
          return false;
        }

        if ($("#pph").val() == "") 
        {
          $("#pph").val("0");
          countTerbayar();
          return false;
        }

        if ($("#admin").val() == "") 
        {
          $("#admin").val("0");
          countTerbayar();
          return false;
        }

        if ($("#denda").val() == "") 
        {
          $("#denda").val("0");
          countTerbayar();
          return false;
        }

        if ($("#selisih").val() == "") 
        {
          $("#selisih").val("0");
          countTerbayar();
          return false;
        }

        if (b > {{ $spk->rekanan->piutang }}) 
        {
          $("#piutang").val("{{ $spk->rekanan->piutang }}");
          countTerbayar();
        }

        var g = a - b - c - d - e - f;

        console.log(a,b,c,d,e,f);

        $("#total_dibayar").text(g);
        $("#total_total_dibayar").val(g);
        if(g < 0)
        {
          $("#showtoast").attr("disabled","disabled");
        }else{
          $("#showtoast").removeAttr("disabled");
        }


        $("#total_dibayar").number(true,2);
      }
</script>
</body>
</html>
