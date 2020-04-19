<!DOCTYPE html>
<html>
<head>
  <title>produk PDF </title>
<style>
    table{
        /* border: 1px solid black; */
        /* text-align: center; */
        width: 100%;
    }
    table, tr, td{
        /* border: 1px solid black; */
        vertical-align:top;
    }
    body{
      font-size: 12px;
    }

    /* #header {
        position: fixed;
        top: -200px;
    } */

    table, tr {
        page-break-inside: auto;
    }
    html {
		margin:50px 65px 50px 80px;
	}
</style>
</head>
<body >
  <div id="head_Content_spk">
    @if ( $ttd_pertama != "" )
    <div id="dvContents_spk" class="result">
      <table width="100%" style="border-collapse:collapse" class='table' id='form_spk'>
        
        <tr>
          <td>
          <h1>&nbsp;</h1>
          <h1>&nbsp;</h1>
            <table border="1" width="100%" style="border:1px solid black; border-collapse: collapse;">
              <tr>
                <td width="50%;"><center>SURAT PERINTAH KERJA</center></td>
                <td width="50%;"><center>{{ $spk->project->name }}</center></td>              
              </tr>
              <tr>
                <td width="50%;"><center>{{ $spk->no or '-'}}</center></td>
                <td width="50%;"><center>{{ $spk->tender->rab->workorder->kawasan->name or 'Fasilitas Kota'}}</center></td>
              </tr>
            </table>
            <table width="100%" style="border:1px solid black; border-collapse: collapse;font-size:10px;" border="1">
              <tr>
                <td width="50%">PIHAK PERTAMA</td>
                <td width="50%">PIHAK KEDUA</td>
              </tr>
              <tr>
                <td width="50%">
                  <span>{{ $ttd_pertama["user_name"] }}</span><br>
                  <span>{{$jabatan}}</span><br>
                  <span><strong>{{ $spk->tender->rab->pt->name }}</strong></span><br>
                  <span><strong>{{ $spk->tender->rab->pt->address }}</strong></span><br>
                  <span><strong>Telp : {{ $spk->tender->rab->pt->phone }}</strong></span><br>
                </td>
                <td width="50%">
                  <span>{{ $spk->rekanan->cp_name or '-' }}</span><br>
                  <span>{{ $spk->rekanan->cp_jabatan or '-' }}</span><br>
                  <span><strong>{{ $spk->rekanan->group->name or '-' }}</strong></span><br>
                  <span><strong>{{ $spk->rekanan->surat_alamat or '-' }}</strong></span><br>
                  <span><strong>Telp : {{ $spk->rekanan->telp or '-' }}</strong></span><br>
                </td>
              </tr>
            </table>
            <span style="font-size:10px;">Dalam hal ini Pihak I memberikan Perintah Kerja Kepada Pihak II untuk melaksanakan Pekerjaan sebagai berikut : </span><br>
            <table width="100%" style="border:1px solid black; border-collapse: collapse;font-size:10px;" cellpadding="5" cellspacing="5" border="1">
              <tr>
                <td width="25%;">JENIS PEKERJAAN</td>
                <td>
                  <strong style="font-size:15px;">{{$spk->tender->rab->name or '-'}}<strong>
                </td>
              </tr>
              <tr>
                <td width="25%;">LOKASI PEKERJAAN</td>
                <td>
                  @foreach ( $spk->details as $key => $value )
                    @if ( $value->asset != "" )                    
                      {{ $value->asset->name }},
                    @else
                      {{ $value->spk->project->name }}
                    @endif
                  @endforeach
                </td>
              </tr>
              <tr>
                <td width="25%;">WAKTU PELAKSANAAN</td>
                <td>
                  @if ( $spk->start_date != null && $spk->finish_date != null )
                    {{ $spk->start_date->format("d M Y")}} s/d {{ $spk->finish_date->format("d M Y")}} 
                  @else
                    - 
                  @endif
                </td>
              </tr>
              <tr>
                <!--
                  PKP Status = 1 kena PPn
                  PKP Status = 2 kena PPn 2 kali ppn normal
                -->
                <td width="25%;">NILAI KONTRAK</td>
                <td>
                  <span>DPP   : IDR {{ number_format($spk->nilai,2) }}</span><br>
                  @if ( $spk->rekanan->pkp_status == "1" )
                  <span>PPN   : IDR {{ number_format(($spk->nilai * $ppn ) / 100 ,2 ) }}</span><br>
                  <span><strong>TOTAL : IDR {{ number_format( $total =  $spk->nilai + (($spk->nilai * $ppn ) / 100) ,2 ) }}</strong></span><br>
                  <span>TERBILANG : {{strtoupper($terbilang->terbilang($total))}} RUPIAH </span>
                  @else                
                  <span><strong>TOTAL : IDR {{ number_format( $total = $spk->nilai,2 ) }}</strong></span><br>
                  <span>TERBILANG : {{strtoupper($terbilang->terbilang($total))}} RUPIAH </span>
                  @endif
                  
                </td>
              </tr>
              <tr>
                <td width="25%;">JENIS KONTRAK</td>
                <td>{{ strtoupper($spk->tender->sifat_tender)  }}</td>
              </tr>
              <tr>
                <td width="25%;">PEMBAYARAN</td> <!-- Retensi diganti dgn PEMBAYARAN-->
                <td>
                  
                  @foreach ( $spk->termyn as $key => $value )
                  @if ( $key == 0 )
                    Tagihan pertama {{ $value->termin }} % dapat ditagihkan setelah spk ditandatangani kedua belah pihak.Pada saat pengambilan giro kontraktor harus menyerahkan progress lapangan sebesar {{ ceil($value->termin*1.05+)}} %.
                    <br/>
                  @else
                    Tagihan ke {{ $key + 1}} : {{ $value->termin }} %;
                  @endif
                  @endforeach
                  <br/>
                  @foreach ( $spk->retensis as $key => $value )
                    <span>Retensi ke {{ $key + 1 }} : <i>{{ $value->percent * 100 }} %</i> selama <strong>{{ $value->hari }}</strong> hari kalender<br></span>
                  @endforeach
                </td>
              </tr>
              <tr>
                <td colspan="2" style="vertical-align: top;font-size:12px;">
                  <h3 style="margin:none"><u>LINGKUP PEKERJAAN</u></h3>
                  <ol style="margin:none">
                    <li>Pihak Kedua  bertanggung jawab sepenuhnya atas pekerjaan : <strong>{{ ucwords($spk->tender->rab->name) }} </strong></li>
                    <li>Pihak Pertama akan melakukan dan menyetujui hasil opname pekerjaan Pihak Kedua dilapangan sebelum dilakukan pembayaran untuk tiap termynnya.<strong></strong></li>
                    <li>Apabila terdapat perbedaan antar Gambar & spesifikasi teknis maka penyelesaian berdasarkan urutan pada SUPP Pasal 20.2.</li>
                  <!-- <li>Segala salah interpretasi terhadap Gambar, spesifikasi teknis dan atau BQ akan menjadi tanggung jawab kontraktor sepenuhnya, kontraktor dianggap telah memahami.</li> -->
                  </ol>
                  <h3 style="margin:none"><u>DOKUMEN-2 BERIKUT MERUPAKAN BAGIAN YANG TIDAK TERPISAHKAN DARI SPK INI :</u></h3>
                  
                  <ol style="margin:none">
                    <li>Dokumen Tender: {{ $spk->tender->no or  '' }} <!-- diganti 1. Gambar, Spesifikasi Teknis ,BoQ  no /tgl-->
                      @foreach ( $spk->tender->tender_document as $key2 => $value2 )
                        {{ $value2->document_name }} tgl : {{ $value->updated_at->format("d/m/Y")}}
                      @endforeach
                    </li>
                    <li>Berita Acara Tender:
                      Anwizijng tgl : {{ $spk->tender->aanwijzing_date->format("d/M/Y") }}, 
                      Klarifikasi tgl : {{ $spk->tender->klarifikasi1_date->format("d/M/Y") }}, 
                      @if($spk->tender->tender_jadwal_penawaran->where('penawaran_ke',2)->first() != '')
                        Rev BoQ tgl : {{ date("d/M/Y",strtotime($spk->tender->tender_jadwal_penawaran->where('penawaran_ke',2)->first()->penawaran_date))  }}, 
                        @endif
                      @if ( $spk->tender_rekanan->penawarans->count() > 1 )
                      Negoisasi tgl : {{ date("d/M/Y",strtotime($spk->tender->tender_jadwal_penawaran->where('penawaran_ke',2)->first()->klarifikasi_date))  }}, 
                      @else
                      Negoisasi tgl : {{ $spk->tender->klarifikasi1_date->format("d/m/Y") }}, 
                      @endif
                    </li>
                    <li>Surat Penawaran (RAB) kontraktor final no./tgl
                      @if ( $spk->tender_rekanan->penawarans->count() > 1 )
                      {{ date("d/M/Y",strtotime($spk->tender->tender_jadwal_penawaran->where('penawaran_ke',2)->first()->klarifikasi_date))}}
                      @else
                        {{ $spk->tender->klarifikasi1_date->format("d/m/Y") }}
                      @endif</li>
                    <li> Surat Penunjukan / Pemenang tender / SIPP No : {{ $spk->tender_rekanan->sipp_no or '-'}} </li>
                    <li> Mengacu pada Syarat-Syarat Umum Perjanjian Pemborongan/SUPPNo/tgl  : {{ $spk->rekanan->group->supps->last()->no or '-'}} (Rekanan telah membaca, mempelajari dan memahami isi dari SUPP).</li>
                  </ol>
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td>
            <table width="100%" style="border:1px solid black; border-collapse: collapse;font-size:10px;" border="1">
              <tr>
                <td>                
                  <span style="margin:none">LAIN - LAIN  :</span>
                  <ol style="margin:none">
                    <li>Syarat-2 umum mengikuti SUPP, kecuali hal-2 yang diatur dalam syarat-syarat khusus ( PARTICULAR CONDITIONS) terlampir (bila ada)</li>
                    <li>Struktur Organisasi terlampir</li>
                    <li>Kelengkapan K3L terlampir</li>
                    <ol style="text-align:right;"> @if($spk->project->project->city != ''){{$spk->project->project->city->name}} @else -- @endif, {{$tanggal}}  </ol>
                  </ol>
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td>
            <table width="100%" border="1px" style="border-collapse: collapse;width: 100%;font-size:14px;">
              <tr>
                <td style="text-transform: uppercase;vertical-align: top;">
                  <center><strong><span>PIHAK KEDUA</span></strong></center>
                  <center><span>{{ $spk->rekanan->group->name or '-' }}</span></span>
                </td>
                <td>
                  <strong><center>PIHAK PERTAMA</strong></center>
                  <span><center>{{ $spk->tender->rab->pt->name or '-' }}</center></span>
                </td>
              </tr> 
              <tr>
                <td style="width: 50%;">
                  <h1>&nbsp;</h1>
                  <h1>&nbsp;</h1>
                  <center><u>{{ strtoupper($spk->rekanan->cp_name) }}</u><br></span></center>
                  <center><span><strong>{{ $spk->rekanan->cp_jabatan or '-' }}</strong></span></center>
                </td>
                <td style="width: 50%;">
                  @if ( isset($ttd_pertama))
                  <h1>&nbsp;</h1>
                  <h1>&nbsp;</h1>
                  <center><u>{{ strtoupper($ttd_pertama["user_name"]) }}</u><br></span></center>
                  <center><span><strong>{{$jabatan}}</strong></span></center>
                  @endif
                </td>
              </tr>                 
            </table>
          </td>
        </tr>
      </table>
    </div>
    <h1>&nbsp;</h1>
    <h1>&nbsp;</h1>

    
    <div id="dvContents_spk_detail" class="result" style="page-break-after: always;">
      @if ( $spk->itempekerjaan != "")
        @if ( $spk->itempekerjaan->group_cost == "2")
          <table width="100%" style="border-collapse:collapse;" class='table' id='form_spk'>
            <tr>
              <td>          
                <table border="1" width="100%" style="border:1px solid black; border-collapse: collapse;">
                  <tr>
                    <td width="50%;"><center>SURAT PERINTAH KERJA</center></td>
                    <td width="50%;"><center>{{ $spk->project->name }}</center></td>              
                  </tr>
                  <tr>
                    <td width="50%;"><center>{{ $spk->no or '-'}}</center></td>
                    <td width="50%;"><center>{{ $spk->tender->rab->workorder->projectKawasan->name or 'Fasilitas Kota'}}</center></td>
                  </tr>
                </table>
              </td>
            </tr>
            <tr>
              <td>
                <table width="100%" border="1" style="border-collapse: collapse;border:1px solid black;">
                  <tr>
                    <td>No.</td>
                    <td>Item Pekerjaan</td>
                    <td>Unit ID</td>
                    <td>Volume</td>
                    <td>Satuan</td>
                    <td>Nilai</td>
                  </tr>
                  @foreach ( $spk->details as $key => $value )
                  <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $spk->tender_rekanan->tender->rab->workorder->name }}</td>
                    <td>{{ $value->asset->name }}</td>
                    <td>1</td>
                    <td>Unit</td>
                    <td>Rp. {{ number_format($value->nilai)}}</td>
                  </tr>
                  @endforeach
                </table>
              </td>
            </tr>
          </table>
        @endif
      @endif
    </div>
    
    <div id="" class="result" style="page-break-after: always;">
      <table border="1" width="100%" style="border:1px solid black; border-collapse: collapse;">
        <tr>
          <td width="50%;"><center>SURAT PERINTAH KERJA</center></td>
          <td width="50%;"><center>{{ $spk->project->name }}</center></td>              
        </tr>
        <tr>
          <td width="50%;"><center>{{ $spk->no or '-'}}</center></td>
          <td width="50%;"><center>{{ $spk->tender->rab->workorder->projectKawasan->name or 'Fasilitas Kota'}}</center></td>
        </tr>
      </table>

      <table width="100%" style="border-collapse: collapse;font-size:10px;" cellpadding="5" cellspacing="5" >
        <tr>
          <td width="25%;">Nama Tender menang Rekanan</td>
          <td width="5%;">:</td>
          <td>
            <strong style="font-size:13px;">{{$spk->rekanan->group->name or '-'}}<strong>
          </td>
        </tr>
        <tr>
          <td width="25%;">Nama Tender</td>
          <td width="5%;">:</td>
          <td>
            <strong style="font-size:13px;">{{$spk->tender->rab->name or '-'}}<strong>
          </td>
        </tr>
      </table>

      <table width="100%" border="1" style="border-collapse: collapse;border:1px solid black;">
        <thead class="head_table" style="text-align:center">
          <tr>
            <td>COA</td>
            <td>Item Pekerjaan</td>
            <td>Volume</td>
            <td>Satuan</td>
            <td>Harga Satuan</td>
            <td>Total Nilai</td>
          </tr>
        </thead>
        {{-- <tbody>
          @php $total = 0; @endphp
            @foreach ( $spk->tender_rekanan->menangs->first()->details as $key2 => $value2 )
              @if($value2->volume != 0)
                @php 
                  $itempekerjaan = \Modules\Pekerjaan\Entities\Itempekerjaan::find($value2->itempekerjaan_id);
                  $rab = \Modules\Rab\Entities\RabPekerjaan::where("itempekerjaan_id",$itempekerjaan->id)->first();
                  $total += $value2->volume * $value2->nilai; 
                @endphp
                <tr>
                  <td>{{ $itempekerjaan->code }}</td>
                  <td>{{ $itempekerjaan->name }}</td>
                  <td style="text-align:right">{{ $value2->volume }}</td>
                  <td>{{ $value2->satuan }}</td>
                  <td style="text-align:right">{{ number_format($value2->nilai) }}</td>
                  <td style="text-align:right">{{ number_format($value2->volume * $value2->nilai) }}</td>
                </tr>
              @endif
            @endforeach
          </tbody> --}}
          <tbody>
            @php $total = 0; @endphp
            @foreach ( $spk->tender_rekanan->menangs->first()->details as $key2 => $value2 )
              @if($value2->volume != 0)
                @php 
                  $itempekerjaan = \Modules\Pekerjaan\Entities\Itempekerjaan::find($value2->itempekerjaan_id);
                  $rab = \Modules\Rab\Entities\RabPekerjaan::where("itempekerjaan_id",$itempekerjaan->id)->first();
                  if($value2->total_nilai == null || $value2->total_nilai == 0){                  
                    $total += $value2->volume * $value2->nilai;
                  }else{
                    $total += $value2->total_nilai;
                  }
                @endphp
                {{-- {{$value2}} --}}
                <tr>
                  <td><strong>{{ $itempekerjaan->code }}</strong></td>
                  <td><strong>{{ $itempekerjaan->name }}</strong></td>
                  <td style="text-align:right"><strong>{{ $value2->volume }}</strong></td>
                  <td><strong>{{ $value2->satuan }}</strong></td>
                  <td style="text-align:right"><strong>{{ number_format($value2->nilai) }}</strong></td>
                  <td style="text-align:right"><strong>{{ number_format($value2->total_nilai) }}</strong></td>
                </tr>
                @foreach($value2->tender_menang_sub_detail as $key2 => $value2 )
                    <tr>
                        <td></td>
                        <td>{{$value2->name}}</td>
                        <td style="text-align:right">{{$value2->volume}}</td>
                        <td >{{$value2->satuan}}</td>
                        <td style="text-align:right">{{number_format($value2->nilai)}}</td>
                        <td style="text-align:right">{{number_format($value2->total_nilai)}}</td>
                    </tr>
                @endforeach
              @endif
            @endforeach
          </tbody>
        <tfoot>
          <tr>
            <td colspan="5" style="text-align:right">Subtotal</td>
            <td style="text-align:right">{{number_format($total)}}</td>
          </tr>
          <tr>
            <td colspan="5" style="text-align:right">PPn</td>
            @php 
              $ppn = \Modules\Globalsetting\Entities\Globalsetting::where('id',9)->first()->value;
            @endphp
            @if($spk->ppn == 1)
              <td style="text-align:right">{{number_format($nilai_ppn = $total * $ppn/100)}}</td>
            @else
              <td style="text-align:right">{{number_format($nilai_ppn = 0)}}</td>
            @endif
          </tr>
          <tr>
            <td colspan="5" style="text-align:right">Grand Total</td>
            <td style="text-align:right">{{number_format($total + $nilai_ppn)}}</td>
          </tr>
        </tfoot>
      </table>

    </div>
    @endif
  </div>
</body>
</html>