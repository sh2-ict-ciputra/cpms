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
                <td width="100%;"><center>SURAT INTRUKSI KONTRAK</center></td>
              </tr>
            </table>

            <table width="100%" style="border:1px solid black; border-collapse: collapse;font-size:10px;" cellpadding="5" cellspacing="5" border="1">
              <tr>
                <td width="">Kepada</td>
                <td colspan="3">
                  <strong style="font-size:15px;">{{$sik->spk->rekanan->group->name or '-'}}<strong>
                </td>
              </tr>
              <tr>
                <td width="20%;">No. SPK</td>
                <td width="30%;">
                    {{$sik->spk->no}}
                </td>
                <td width="20%;">No. SIK</td>
                <td width="30%">
                    {{$sik->no_sik}}
                </td>
              </tr>
              <tr>
                <td>Project Kawasan</td>
                <td>
                  {{$sik->spk->project->name}}
                </td>
                <td >tanggal sik</td>
                <td >
                    {{$sik->tgl_sik}}
                </td>
              </tr>
              <tr>
                <td >Pengawas </td>
                <td>
                    {{$sik->spk->user_pic->user_name or ''}}
                </td>
                <td  rowspan="3">Tembusan</td>
                <td  rowspan="3">
                    
                </td>
              </tr>
              <tr>
                <td >Jenis Pekerjaan</td>
                <td>{{ $sik->spk->itempekerjaan->name  }}</td>
              </tr>
              <tr>
                <td>Perihal</td> <!-- Retensi diganti dgn PEMBAYARAN-->
                <td>
                    {{$sik->status_sik->name}}
                </td>
              </tr>
            </table>
            
            <table width="100%" style="border:1px solid black; border-collapse: collapse;font-size:10px;" cellpadding="5" cellspacing="5" border="1">
              <tr>
                <td width="">
                    <span style="margin:none">Dengan Hormat</span><br/><br/>
                    <span style="margin:none">Sehubungan dengan Pekerjaan yang tertuang dalam SPK diatas dan melihat kondisi lapangan maka perlu diperhatikan hal -hal sebagai berikut :</span>
                    <br/>
                    <br/>
                    @if($sik->status_sik_id != 2)
                        <table id="example2" class="table table-bordered table-hover" style="border:1px solid black; border-collapse: collapse;font-size:10px;" cellpadding="5" cellspacing="5" border="1">
                            <thead class="head_table">
                                <tr>
                                    <th>No.</th>
                                    <th>Item Pekerjaan</th>
                                    <th>Satuan</th>
                                    <th>Volume</th>
                                </tr>
                            </thead>
                            <tbody>
                            @php ($no = 1); @endphp
                            @foreach( $sik->sik_detail as $key =>$value2 )
                                @php 
                                $itempekerjaan = \Modules\Pekerjaan\Entities\Itempekerjaan::find($value2->itempekerjaan_id);
                                @endphp
                                <tr style="font-weight:bold">
                                    <td>{{ $no }}</td>
                                    <td>{{ $itempekerjaan->name }}</td>
                                    <td>{{ $value2->satuan }}</td>
                                    <td>{{ $value2->volume}}</td>
                                </tr>
                                @foreach ($value2->sik_detail as $key2 => $value3)
                                  <tr>
                                    <td></td>
                                    <td>{{ $value3->name }}</td>
                                    <td>{{ $value3->satuan }}</td>
                                    <td>{{ $value3->volume}}</td>
                                  </tr>
                                @endforeach
                            @php ($no++)
                            @endforeach
                            </tbody>
                        </table>
                        <br/><br/>
                        <span style="margin:none">Segala biaya yang akan dikeluarkan/timbul untuk pihak ke 3 menjadi pekerjaan kurang {{$sik->spk->rekanan->group->name or '-'}} . Dengan ini kami instruksikan kepada {{$sik->spk->rekanan->group->name or '-'}} segera melaksanakan pekerjaan Tersebut.</span><br/>
                        <span style="margin:none">Untuk menghindari kesalahan proses administrasi, rekanan dihimbau untuk berkoordinasi dengan Divisi Qs Demikian disampaikan untuk dilaksanakan dan terima kasih</span><br/>
                        <span style="margin:none"> Hormat kami</span><br/>
                    @else
                        <table>
                            <tbody>
                                <tr>
                                    <td>
                                        <textarea type="" class="form-control" name="ket[]" value="" id="ket" style="width:100%;border:none;height:auto" readonly> {{str_replace('<p>', '', str_replace('</p>', '', $sik->sik_detail[0]->keterangan))}}</textarea>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <!-- <textarea class="form-control" style="width:100%;border:none;" rows="7" id="isian1" name="isian" readonly>{{ $sik->sik_detail[0]->keterangan }}</textarea> -->

                        <span style="margin:none">Segala instruksi yang dikeluarkan untuk ...... (rekanan) agar segera melaksanakan pekerjaan tersebut Untuk menghindari kesalahan proses administrasi, rekanan dihimbau untuk berkoordinasi dengan Divisi Qs Demikian disampaikan untuk dilaksanakan dan terima kasih</span><br/><br/>
                        <span style="margin:none"> Hormat kami</span><br/>
                    @endif
                </td>
              </tr>
            </table>

            {{-- <table width="100%" border="1px" style="border-collapse: collapse;width: 100%;font-size:14px;">
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
            </table> --}}
          </td>
        </tr>

      </table>
    </div>

  </div>
</body>
</html>