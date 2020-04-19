
<:DOCTYPE html>
<html>
<head>
    <title>produk PDF </title>
    <link href=" {{ asset ('public bootstrap/css/bootstrap.min.css') }} "rel="stylesheet">
<style>
    table {
        /* border: 1px solid black; */
        /* text-align: center; */
    }

    table {
        border-collapse: collapse;
        width: 100%;
        border-spacing:10px;
        font-size: 12px;
    }

   .right {
        text-align: right;
    }

    body {
      font-size: 12px;
    }
    html {
		margin:50px 65px 0px 80px;
    }
    table, tr {
        page-break-inside: auto;
    }
</style>
</head>
<body>
    <h1>&nbsp;</h1>
    <h1>&nbsp;</h1>
    <div class="page" style="">
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
                                    <th style="width: 15%">Proyek :</th>
                                    <th style="width: 27%">{{$project->name}}</th>
                                    <th style="width: 5%"></th>
                                    <th style="width: 20%;" >No SPK </th>
                                    <th style="width: 5%" class="right"></th>
                                    <th style="width: 28%" >{{ $spk->no }}</th>
                                </tr>
                                <tr>
                                    <th rowspan="2" style="vertical-align:top;">Pekerejaan :</th>
                                    <th rowspan="2" style="vertical-align:top;">
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
                                    <th>Nilai VO kumulatif </th><th class="right">Rp</th><th class="right" style="border-bottom: 3px solid black;">-</th>
                                </tr>
                                <tr>
                                    <th rowspan="2" style="vertical-align:top;">Jenis Pekerjaan :</th>
                                    <th rowspan="2" style="vertical-align:top;">{{$spk->tender->rab->name}}</th>
                                    <th></th>
                                    <th class="right">Sub Total DPP </th><th class="right">Rp</th><th class="right">{{ number_format($sub_total_dpp = $spk->nilai)}}</th>
                                </tr>
                                <tr>
                                    <th></th>
                                    <th class="right">PPN = 10% </th><th class="right">Rp</th><th class="right" style="border-bottom: 3px solid black;">
                                        {{ number_format($ppn_dpp = $sub_total_dpp * $ppn)}}
                                    </th>
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
                                    @if ( $bap->termin == "1" || $spk->progress_sebelumnya == 100)
                                        <th colspan="2">Retensi .....% dari Progress </th>
                                        <th class="right">
                                            @if ($bap->nilai_retensi != 0)
                                            {{-- {{$spk->retensis->sum('percent')}} --}}
                                                {{round((float)$spk->retensis->sum('percent')*100, 4)}}%
                                                {{-- {{bcdiv(((float)$spk->retensis->sum('percent'))*100, 1, 2)}} --}}
                                            @else
                                                0%
                                            @endif
                                        </th>
                                        <th class="right">-Rp</th>
                                        <th class="right"> {{number_format($bap->nilai_retensi)}} </th>
                                    @else
                                        <th colspan="2">Retensi .....% dari Progress </th>
                                        <th class="right">{{round((float)$spk->retensis->sum('percent')*100, 4)}}%</th>
                                        <th class="right">-Rp</th><th class="right"> {{number_format($bap->nilai_retensi)}} </th>
                                    @endif
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
                                    <th colspan="2">Kekurangan Retensi Dp, Kekurang VO</th>
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
                                    <th class="right">-Rp</th>
                                    <th class="right"> 
                                        {{number_format($bap->nilai_administrasi)}}
                                    </th>
                                </tr>
                                <tr>
                                    <th></th><th colspan="">Potongan denda keterlambatan </th>
                                    <th></th>
                                    <th class="right" style="border-bottom: 3px solid black;">-Rp</th>
                                    <th class="right" style="border-bottom: 3px solid black;">
                                        {{number_format($denda = 0)}}
                                        <!--  <input type="hidden" id="denda" name="denda" class="form-control" value="0" style="width:50%"> -->
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
                        
                        <table style="width: 100%;border: 1px solid black;top-margin: 20px; background-color: lightgray;">
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
                                <th class="right">Rp</th><th class="right"> {{ number_format($spk->nilai - $nilai_kumulatif_saatini)}} </th>
                            </tr>
                        </table>
                        <ol style="text-align:right;"> @if($spk->project->project->city != ''){{$spk->project->project->city->name}} @else  @endif, {{$tanggal_cetak}}  </ol>
                        
                        <table width="100%" border="1px" style="border-collapse: collapse;width: 100%;font-size:14px;">
                            <tr>
                                {{-- <td style="text-transform: uppercase;vertical-align: top;">
                                    <center><strong><span>DIBUAT OLEH</span></strong></center>
                                </td> --}}
                                <td style="text-transform: uppercase;vertical-align: top;" colspan="">
                                    <center><strong><span>MENGETAHUI</span></strong></center>
                                </td>
                                <td>
                                    <strong><center>MENYETUJUI</strong></center>
                                </td>
                            </tr> 
                            <tr>
                                {{-- <td style="width: 25%;">
                                    <!-- <h1>&nbsp;</h1> -->
                                    <h1>&nbsp;</h1>
                                    <center><u>{{$user->user_name}}</u><br></span></center>
                                    <center><span><strong>Admin QS</strong></span></center>
                                </td> --}}
                                <td style="width: 50%;">
                                    <!-- <h1>&nbsp;</h1> -->
                                    <h1>&nbsp;</h1>
                                    <center><u>{{$user_kadiv}}</u><br></span></center>
                                    <center><span><strong>Section Head</strong></span></center>
                                </td>
                                {{-- <td style="width: 25%;">
                                    <!-- <h1>&nbsp;</h1> -->
                                    <h1>&nbsp;</h1>
                                    <center><u>{{$user_kadept}}</u><br></span></center>
                                    <center><span><strong>Department Head</strong></span></center>
                                </td> --}}
                                <td style="width: 50%;">
                                    <!-- <h1>&nbsp;</h1> -->
                                    <h1>&nbsp;</h1>
                                    <center><u>{{$user_gm}}</u><br></span></center>
                                    <center><span><strong>General Manager</strong></span></center>
                                </td>
                            </tr>                 
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- <div class="page" style="page-break-before: always;">
        <div class="panel panel - default">
            <table style="width: 100%;border: 1px solid black;">
                <tr>
                    <th style="text-align: center;font-size: 20px">FINAL ACCOUNT</th>
                </tr>
                <tr>
                    <th style="text-align: center;font-size: 20px">SPK Nomor : {{ $bap->spk->no }}</th>
                </tr>
            </table>
            
            <table style="width: 100%;border: 1px solid black;font-size: 14px">
                <tr>
                    <th style="font-size: 17px;padding:0px 50px" colspan="2">FINAL ACCOUNT</th>
                    <th style="text-align: right;font-size: 17px;padding:0px 50px">{{$coa}}</th>
                </tr>
                <tr>
                    <td style="padding:0px 50px" colspan="3">Penyesuaian harga borongan karena adanya pekerjaan tambah/kurang untuk:</td>
                </tr>
                <tr>
                    <td style="width:10%;padding:0px 50px" colspan="">Pekerjaan</td>
                    <td style="width:5%" colspan="">:</td>
                    <th style="width:85%;text-align:left" colspan="">{{ $bap->spk->tender->rab->name }}</th>
                </tr>
                <tr>
                    <td style="width:10%;padding:0px 50px" colspan="">Kawasan</td>
                    <td style="width:5%" colspan="">:</td>
                <th style="width:85%;text-align: left" colspan="">
                    @if ($bap->spk->tender->rab->workorder->projectKawasan != null)
                        {{$bap->spk->tender->rab->workorder->projectKawasan->name}}
                    @else
                    {{$bap->spk->project->name}}
                    @endif
                </th>
                </tr>
                <tr>
                    <th colspan="3" style="padding:0px 50px">
                        <br>
                        <table style="font-size: 16px">
                            <tr>
                                <th style="text-align: right;width:25%" colspan="">Nilai Spk</th>
                                <th style="width:5%" colspan="">:</th>
                                <th style="text-align: right;width:45%" colspan="">Rp. {{number_format($bap->spk->nilai)}}</th>
                                <th style="width:30%" colspan=""></th>
                            </tr>
                            @foreach ($bap->spk->new_vo as $key => $value)
                                @if ($value->approval->approval_action_id == 6)
                                    @if ($key == 0)
                                        <tr>
                                            <th style="text-align: right;width:20%" colspan="">VO</th>
                                            <th style="width:5%" colspan="">:</th>
                                            <th style="width:45%" colspan="">{{$key+1}}.&nbsp; {{number_format($value->nilai)}}</th>
                                            <th style="width:30%" colspan=""></th>
                                        </tr>
                                    @else
                                        <tr>
                                            <th style="text-align: right;width:20%" colspan=""></th>
                                            <th style="width:5%" colspan="">:</th>
                                            <th style="width:45%" colspan="">{{$key+1}}.&nbsp; {number_format($value->nilai)}}</th>
                                            <th style="width:30%" colspan=""></th>
                                        </tr>
                                    @endif
                                @endif
                            @endforeach
                            <tr>
                                <th style="text-align: right;width:20%" colspan=""></th>
                                <th style="width:5%" colspan="">:</th>
                                <th style="width:45%;border-bottom:1pt solid black" colspan=""></th>
                                <th style="width:30%" colspan=""></th>
                            </tr>
                            <tr>
                                <th style="text-align: right;width:20%" colspan=""></th>
                                <th style="width:5%" colspan="">:</th>
                                <th style="width:45%;border-bottom:1pt solid black" colspan=""></th>
                                <th style="width:30%" colspan=""></th>
                            </tr>
                            <tr>
                                <th style="text-align: right;width:20%" colspan="">Kum.VO</th>
                                <th style="width:5%" colspan="">:</th>
                            <th style="text-align: right;width:45%" colspan="">Rp. {{number_format($bap->spk->nilai_vo)}}</th>
                                <th style="width:30%" colspan=""></th>
                            </tr>
                            <tr>
                                <th style="text-align: right;width:20%" colspan="">Total Kontrak</th>
                                <th style="width:5%" colspan="">:</th>
                                <th style="text-align: right;width:45%" colspan="">{{number_format($bap->spk->nilai + $bap->spk->nilai_vo)}}</th>
                                <th style="width:30%" colspan=""></th>
                            </tr>
                            <tr>
                                <th style="text-align: right;width:20%;" colspan="">Terbilang</th>
                                <th style="width:5%" colspan="">:</th>
                                <th style="width:75%;font-size: 13px" colspan="">{{strtoupper($terbilang->terbilang($bap->spk->nilai + $bap->spk->nilai_vo))}} RUPIAH</th>
                            </tr>
                        </table>
                    </th>
                </tr>
                <tr style="margin:0px 10px 0px 10px"> 
                    <td style="padding:0px 50px" colspan="3">
                        <br><br>
                        Demikian Final Account Surat Perintah Kerja Nomor. {{ $bap->spk->no }} ini dibuat dan ditanda tangani oleh kedua belah pihak pada tanggal {{date("d M Y")}}, masing-masih pihak memegang satu lembar dan tidak ada paksaan berupa apapun serta masing-masing saling memenuhi kewajibannya dengan dilandasi itikat baik.
                    </td>
                </tr>   
                <tr style="margin:0px 10px 0px 10px">
                    <td style="text-align:right;padding:0px 50px" colspan="3">
                        {{$project->city->name}}, {{date("d M Y")}}
                    </td>
                </tr>
            </table>
            <table width="100%" border="1px" style="border-collapse: collapse;width: 100%;font-size:14px;">
                <tr>
                  <td style="text-transform: uppercase;vertical-align: top;font-size:12px;">
                    <center><strong><span>PIHAK KEDUA</span></strong></center>
                    <center><span>{{ $bap->spk->rekanan->group->name or '-' }}</span></span>
                  </td>
                  <td style="font-size:12px;">
                    <strong><center>PIHAK PERTAMA</strong></center>
                    <span><center>{{ $bap->spk->tender->rab->pt->name or '-' }}</center></span>
                  </td>
                </tr> 
                <tr>
                  <td style="width: 50%;font-size:12px;">
                    <h1>&nbsp;</h1>
                    <h1>&nbsp;</h1>
                    <center><u>{{ strtoupper($bap->spk->rekanan->cp_name) }}</u><br></span></center>
                    <center><span><strong>{{ $bap->spk->rekanan->cp_jabatan or '-' }}</strong></span></center>
                  </td>
                  <td style="width: 50%;font-size:12px;">
                    @if ( $ttd_pertama != "-")
                    <h1>&nbsp;</h1>
                    <h1>&nbsp;</h1>
                    <center><u>{{ strtoupper($ttd_pertama->user_name or '') }}</u><br></span></center>
                    <center><span><strong>{{$jabatan}}</strong></span></center>
                    @endif
                  </td>
                </tr>                 
            </table>
        </div>
    </div> --}}
                <!-- /.col -->
                <!-- <div class="col-md-6" style="display: none">
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
                        <td>{{ $value5->asset->name }}</td>
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
            </div> -->
</body>
</html>