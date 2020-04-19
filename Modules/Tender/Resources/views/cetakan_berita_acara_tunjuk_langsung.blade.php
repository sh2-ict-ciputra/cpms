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
      font-size: 14px;
    }

    /* #header {
        position: fixed;
        top: -200px;
    } */

    table, tr {
        page-break-inside: auto;
    }
    html {
		margin:50px 65px 0px 80px;
	}
</style>
</head>
<body style="font-size:12px;">
  <div id="head_Content_spk">
    <div id="dvContents_spk" class="result" style="">
      <table width="100%" style="border-collapse:collapse" class='table' id='form_spk'>    
        <tr>
          <td>
            <table border="1" width="100%" style="border:1px solid black; border-collapse: collapse;">
              <tr>
                <td style="width:55%" rowspan="2">
                  <table>
                    <tr>
                      <td style="width:30%">Pekerjaan</td>
                      <td style="width:5%">:</td>
                      <td>
                        {{$berita_acara->tender->rab->workorder_budget_detail->itempekerjaan->name}}
                      </td>   
                    </tr>
                    <tr>
                      <td style="width:30%">Kawasan</td>
                      <td style="width:5%">:</td>
                      <td>
                        @if ($berita_acara->tender->rab->workorder->kawasan_id != null)
                          {{$berita_acara->tender->rab->workorder->projectKawasan->name}} 
                        @else
                          {{$berita_acara->tender->project->name}}
                        @endif
                      </td>   
                    </tr>
                    <tr>
                      <td style="width:30%">Project</td>
                      <td style="width:5%">:</td>
                      <td>
                        {{$berita_acara->tender->project->name}}
                      </td>   
                    </tr>
                    <tr>
                      <td style="width:30%">Jenis</td>
                      <td style="width:5%">:</td>
                      <td>
                        {{$berita_acara->tender->tender_type->name}}
                      </td>   
                    </tr>
                    <tr>
                      <td style="width:30%">Biaya Rab</td>
                      <td style="width:5%">:</td>
                      <td>
                        Rp. {{number_format($berita_acara->tender->rab->nilai)}}
                      </td>   
                    </tr>
                  </table>
                </td>
                
                <td style="width:45%;font-size:20px;font-weight:bold;border:1px solid black;" colspan="">
                  <center>Berita Acara</center>
                </td>
              </tr>
              <tr style="width:45%">
                <td>
                  <table>
                    <tr>
                      <td style="width:30%">Nomor</td>
                      <td style="width:5%">:</td>
                      <td style="width:65%">
                        {{$berita_acara->no}}
                      </td>  
                    </tr>
                    <tr>
                      <td style="width:30%">Tanggal</td>
                      <td style="width:5%">:</td>
                      <td>
                        {{$berita_acara->tanggal}}
                      </td>  
                    </tr>
                  </table>
                </td>
              </tr>
              {{-- <tr>
                <td style="width:30%">Tanggal</td>
                <td style="width:5%">:</td>
                <td>
                  {{$berita_acara->tanggal}}
                </td>  
              </tr> --}}
            </table>
          </td>
        </tr>
        <tr>
          <td>
            <table border="1" width="100%" style="border:1px solid black; border-collapse: collapse;">
              <tr>
                <td>
                  <table>
                    <tr>
                      <td style="width:16%">Perihal</td>
                      <td style="width:5%">:</td>
                      <td style="width:79%">{{$berita_acara->tender->rab->name}}</td>
                    </tr>
                    <tr>
                      <td colspan="3">
                        <br><br>
                        Pada tanggal {{date("d M Y",strtotime($berita_acara->tanggal))}} telah dibuat Berita Acara Penunjukan Langsung untuk mengerjakan pekerjaan tersebut diatas dan ditindak lanjutkan dengan segera dibuatkan surat perintah kerja. Dengan ini kami mengusulkan untuk menunjuk langsung rekanan:
                        <br>
                      </td>
                    </tr>
                    <tr>
                      <td style="width:16%">Nama</td>
                      <td style="width:5%">:</td>
                      <td style="width:79%">{{$berita_acara->tender->rekanans[0]->rekanan->name}}
                    <tr>
                      <td style="width:16%">Contact Person</td>
                      <td style="width:5%">:</td>
                      <td style="width:79%">{{$berita_acara->tender->rekanans[0]->rekanan->cp_name}}</td>
                    </tr>
                    <tr>
                      <td style="width:16%">Alamat</td>
                      <td style="width:5%">:</td>
                      <td style="width:79%">{{$berita_acara->tender->rekanans[0]->rekanan->surat_alamat}}</td>
                    </tr>
                    <tr>
                      <td style="width:16%">No telp/Hp</td>
                      <td style="width:5%">:</td>
                      <td style="width:79%">{{$berita_acara->tender->rekanans[0]->rekanan->telp}}</td>
                    </tr>
                    <tr>
                      <td colspan="3">
                        <br><br>
                        Dengan beberapa pertimbangan sebagai berikut:
                      </td>
                    </tr>
                    <tr>
                      <td colspan="3">
                        {{-- {{html_entity_decode(str_replace('<p>', '<p>', str_replace('</p>', 
                        "\r\n", $berita_acara->isian)))}} --}}
                        <?=$berita_acara->isian?>
                        {{-- <textarea type="" class="form-control" name="ket[]" value="" id="ket" style="width:100%;border:none;height:auto;font-style:aria" readonly> {{str_replace('<p>', '', str_replace('</p>', '', $berita_acara->isian))}}</textarea> --}}
                      </td>
                    </tr>
                    <tr>
                      <td colspan="3">
                        <br>
                        Demikian Berita Acara ini dibuat dan terima kasih
                        <br><br><br>
                      </td>
                    </tr>
                    <tr>
                      <td colspan="3">
                        @if ($berita_acara->tender->project->city != null)
                          {{$berita_acara->tender->project->city->name}}
                        @endif
                          , {{date("d M Y",strtotime($berita_acara->tanggal))}}
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td>
            <table border="1" width="100%" style="border:1px solid black; border-collapse: collapse;">
              <tr>
                  <td>
                      <center><strong><span>Dibuat</span></strong></center>
                  </td>
                  <td>
                      <strong><center>Mengetahui</strong></center>
                  </td>
              </tr> 
              <tr>
                  <td style="width: 50%;">
                      <h1>&nbsp;</h1>
                      <center><u>{{$user_kadiv}}</u><br></span></center>
                      <center><span><strong>Section Head</strong></span></center>
                  </td>
                  <td style="width: 50%;">
                      <h1>&nbsp;</h1>
                      <center><u>{{$ttd_pertama["user_name"]}}</u><br></span></center>
                      <center><span><strong>{{$jabatan}}</strong></span></center>
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