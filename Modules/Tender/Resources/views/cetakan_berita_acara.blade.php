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
            <center><h3><strong>BERITA ACARA KLARIFIKASI TENDER</strong></h3></center>
            <center><h3><strong>{{ $tender->name or ''}}</strong></h3></center>
            <table width="100%">
                <tr>
                    <td style="width: 30%;vertical-align: top;">Hari/ Tanggal</td>
                    <td>{{ date("d/M/Y", strtotime($tender->aanwijzing_date))}}</td>
                </tr>
                <tr>
                    <td style="width: 30%;vertical-align: top;">Waktu</td>
                    <td>{{ date("H:i", strtotime($tender->aanwijing->waktu))}} - selesai</td>
                </tr>
                <tr>
                    <td style="width: 30%;vertical-align: top;">Tempat</td>
                    <td>{{$tender->aanwijing->tempat }}</td>
                </tr>
                <tr>
                    <td colspan="2">Resume</td>
                </tr>
                <tr>
                    <td colspan="2">
                    <textarea type="" class="form-control" name="ket[]" value="" id="ket" style="width:100%;border:none;height:auto" readonly> {{$resume}}</textarea>
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