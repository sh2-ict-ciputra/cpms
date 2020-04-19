<!DOCTYPE html>
<html lang="EN">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	  <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Laporan Barang Masuk Hibah</title>
    <style type="text/css">
    .tgfooter  {
        border-collapse:collapse;
        border-spacing:10px;
        border-color:#ccc;
        width: 100%;
        /*font-weight: bold;*/
       }
    .tg1  {
        border-collapse:collapse;
        border-spacing:0;
        border-color:#ccc;
        width: 100%;
        /*font-weight: bold;*/
       }

       .tg  { 
        border-collapse:collapse;
        border-spacing:10px;
        border-color:#ccc;
        width: 100%;
       }
       th{
        text-align:center;
       }
    	.center-text{
			text-align:center;
		}
    .right-text{
      text-align: right;
    }
    </style>
  </head>
  <body>
  	<h3 class="center-text">Laporan Barang Masuk Hibah</h3>
    <h5 class="center-text">{{ $request->start_opname }} - {{ $request->end_opname}}</h5>
    <table width="100%" align="center" class="tg1">
      <thead>
        <tr>
          <td>
            <img src="images/logo-ciputra_original_text.png" alt="logo" class="logo-default" style='height:57%' />
          </td>
          <td>
            <small>PT. SINAR BAHANA MULYA
            Gedung Mall Ciputra Cibubur Lt.3
            Jl. Alternatif Cibubur - Cileungsi Km. 4 Jatikarya, Bekasi 17435
            Phone : (021) 845-93193 Fax:(021) 845-93192</small>
          </td>
        </tr>
      </thead>
    </table>
    <br/>

    <table width="100%" border="1pt" align="center" class="tg">
        <thead style="background-color: #3FD5C0;">
          <tr>
            <th rowspan="2">#</th>
            <th rowspan="2">Nomor</th>
            <th colspan="2" style="text-align:center;">DARI</th>
            <th rowspan="2">Total</th>
            <th rowspan="2">Masuk</th>
            <th rowspan="2">Retur</th>
            <th rowspan="2">Satuan</th>
          </tr>
          <tr>
            <th class="text-center">Proyek</th>
            <th class="text-center">PT</th>
          </tr>
      </thead>
      <tbody>
        @if(count($arrHearBarangMasuk) > 0)
        <?php
          //print_r($arrHearBarangMasuk);
        ?>
          @foreach($arrHearBarangMasuk as $key => $value)
            <tr style="background-color: rgb(221, 221, 221);">
              <td>{{ $key+1 }}</td>
              <td>{{ $value['no'] }}</td>
              <td>{{ $value['from_project_name'] }}</td>
              <td>{{ $value['from_pt_name'] }}</td>
              <td class="right-text">{{ $value['total'] }}</td>
              <td class="right-text">{{ $value['diisi'] }}</td>
              <td class="right-text">{{ $value['reject'] }}</td>
              <td colspan="2"></td>
            </tr>
            @if(count($value['detail']) > 0)
              
              @for($i = 0;$i<count($value['detail']);$i++)
              <tr >
                <td colspan="2"></td>
                <td >{{ $value['detail'][$i]['item_name'] }}</td>
                <td colspan="2">{{ $value['detail'][$i]['warehouse_name'] }}</td>
                <td style="text-align: right;">{{ $value['detail'][$i]['quantity_terima'] }}</td>
                <td style="text-align: right;">{{ $value['detail'][$i]['quantity_reject'] or 0 }}</td>
                <td >{{ $value['detail'][$i]['item_satuan'] }}</td>
              </tr>
              @endfor
            @endif
          @endforeach
        @else
          <tr>
            <td colspan="7" style="text-align: center;">Kosong</td>
          </tr>
        @endif
      </tbody>
    </table>
    <br/>
    <table width="100%" align="center" class="tgfooter" border="1pt">
      <thead>
        <tr>
            <th>Given By</th>
            <th>Received By</th>
          </tr>
         <tr>
          <th><h1>&nbsp;</h1>
          </th>
          <th>
            <h1>&nbsp;</h1>
            
          </th>
        </tr>
      </thead>
    </table>
  </body>
</html>