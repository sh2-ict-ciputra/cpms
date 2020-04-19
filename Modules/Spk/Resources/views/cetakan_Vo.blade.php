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
<body>
  <div id="head_Content_sik">
    <div id="dvContents_sik" class="result">
      <table width="100%" style="border-collapse:collapse" class='table' id='form_spk'>
        <tr>
          <td>
            <h1>&nbsp;</h1>
            <table border="1" width="100%" style="border:1px solid black; border-collapse: collapse;">
              <tr>
                <td width="100%;"><center>VO (VARIANT ORDER)</center></td>
              </tr>
            </table>

            <table width="100%" style="border:1px solid black; border-collapse: collapse;font-size:10px;" cellpadding="5" cellspacing="5" border="1">
              <tr>
                <td width="">Pihak ke 2</td>
                <td colspan="">
                  <strong style="font-size:15px;">{{$vo->spk->rekanan->group->name or '-'}}<strong>
                </td>
                <td width="20%;">No. Vo</td>
                <td width="30%">
                    {{$vo->no}}
                </td>
              </tr>
              <tr>
                <td>Project Kawasan</td>
                <td>
                  {{$sik->spk->project->name}}
                </td>
                <td width="20%;">No. SPK</td>
                <td width="30%;">
                    {{$vo->spk->no}}
                </td>
              </tr>
              <tr>
                <td >Pengawas </td>
                <td>
                    {{$sik->spk->user_pic->user_name or ''}}
                </td>
                <td >tanggal SPK</td>
                <td >
                    {{$vo->spk->date}}
                </td>
              </tr>
              <tr>
                <td>Jenis Pekerjaan</td>
                <td>{{ $vo->spk->itempekerjaan->name  }}</td>
                <td>No. SIK</td>
                <td>
                    {{$vo->sik->no_sik}}
                </td>
              </tr>
              <tr>
                <td>Perihal</td>
                <td>
                    @if($vo->tipe == 1)
                        Vo Penambahan
                    @elseif($vo->tipe == 3)
                        Vo Pengurangan
                    @endif
                </td>
                <td>Tanggal SIK</td>
                <td>
                    {{$vo->sik->tgl_sik}}
                </td>
              </tr>
            </table>

            <br/>
            <br/>

            <table id="example2" class="table table-bordered table-hover" style="border:1px solid black; border-collapse: collapse;font-size:10px;" cellpadding="5" cellspacing="5" border="1">
                <thead class="head_table" style="background-color: lightgray;">
                    <tr>
                        <th>No.</th>
                        <th>Item Pekerjaan</th>
                        <th>Volume</th>
                        <th>Satuan</th>
                        <th>Harga Satuan</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                @php 
                ($no = 1); 
                $total = 0;
                @endphp
                
                @foreach( $sik->vo->detail as $key =>$value2 )
                    @php 
                    $itempekerjaan = \Modules\Pekerjaan\Entities\Itempekerjaan::find($value2->itempekerjaan_id);
                    @endphp
                    <tr style="font-weight:bold">
                        <td>{{ $no }}</td>
                        <td>{{ $itempekerjaan->name }}</td>
                        <td>
                            {{$volume = (float)$value2->volume}}
                        </td>
                        <td>{{ $value2->satuan }}</td>
                        <td style = "text-align:right">
                            {{number_format($nilai = $value2->nilai)}}
                        </td>
                        <td style = "text-align:right">{{number_format($subtotal = $value2->total_nilai)}}</td>
                        @php
                            $total += $subtotal;
                        @endphp
                    </tr>
                    @if (count($value2->sub_detail_vo) != 0)
                    @foreach ($value2->sub_detail_vo as $key3 => $value3)
                        <tr>
                            <td></td>
                            <td>{{ $value3->name }}</td>
                            <td>
                                {{(float)$value3->volume}}
                            </td>
                            <td>{{ $value3->satuan }}</td>
                            <td style = "text-align:right">
                                {{number_format($value3->nilai)}}
                            </td>
                            <td style = "text-align:right">{{number_format($value3->total_nilai)}}</td>
                        </tr>
                    @endforeach
                    @endif
                    @php ($no++); @endphp
                @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="5" style="text-align:right">Total</th>
                        <th style="text-align:right">{{number_format($total)}}</th>
                    </tr>
                    <tr>
                        <th colspan="5" style="text-align:right">PPn</th>
                        @php 
                        $ppn = \Modules\Globalsetting\Entities\Globalsetting::where('id',9)->first()->value;
                        @endphp
                        @if($vo->spk->ppn == 1)
                            <th style="text-align:right">{{number_format($nilai_ppn = $total * $ppn/100)}}</th>
                        @else
                            <th style="text-align:right">{{number_format($nilai_ppn = 0)}}</th>
                        @endif
                    </tr>
                    <tr>
                        <th colspan="5" style="text-align:right">Total</th>
                        <th style="text-align:right">{{number_format($total+$nilai_ppn)}}</th>
                    </tr>
                </tfoot>
                <tfoot>
                    <tr>
                        <th colspan="5" style="text-align:right">Nilai Spk Awal</th>
                        <th style="text-align:right">{{number_format($vo->spk->nilai)}}</th>
                    </tr>
                    <tr>
                        <th colspan="5" style="text-align:right">Nilai Kumulatif Vo</th>
                            <th style="text-align:right">{{number_format($vo->spk->nilai_vo)}}</th>
                    </tr>
                    <tr>
                        <th colspan="5" style="text-align:right">Nilai Spk update</th>
                        <th style="text-align:right">{{number_format($vo->spk->nilai + $vo->spk->nilai_vo)}}</th>
                    </tr>
                </tfoot>
            </table>

            <br/>
            <br/>
            <br/>

            <table width="100%" border="1px" style="border-collapse: collapse;width: 100%;font-size:14px;">
                <tr>
                    <td style="text-transform: uppercase;vertical-align: top;">
                        <center><strong><span>MENGETAHUI</span></strong></center>
                    </td>
                    <td>
                        <strong><center>MENYETUJUI</strong></center>
                    </td>
                </tr> 
                <tr>
                    <td style="width: 50%;">
                        <!-- <h1>&nbsp;</h1> -->
                        <h1>&nbsp;</h1>
                        <center><u>{{$user_dept}}</u><br></span></center>
                        <center><span><strong>Departemen Head</strong></span></center>
                    </td>
                    <td style="width: 50%;">
                        <!-- <h1>&nbsp;</h1> -->
                        <h1>&nbsp;</h1>
                        <center><u>{{$user_gm}}</u><br></span></center>
                        <center><span><strong>General Manager</strong></span></center>
                    </td>
                </tr>                 
            </table>
          </td>
        </tr>

      </table>
    </div>

  </div>
</body>
</html>