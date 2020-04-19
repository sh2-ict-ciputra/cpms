<!DOCTYPE html>
<html lang="EN">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Laporan Posisi Persediaan</title>
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
    
    <h2 class="center-text">{{ $project->name }}</h2>
    <h3 class="center-text">DAFTAR POSISI PERSEDIAAN BARANG </h3>
    <strong class="center-text">{{ date('d-m-Y',strtotime($request->start_opname)) }} Sampai {{ date('d-m-Y',strtotime($request->end_opname)) }}</strong>
    <h5 class="center-text">PT. SINAR BAHANA MULYA
            Gedung Mall Ciputra Cibubur Lt.3
            Jl. Alternatif Cibubur - Cileungsi Km. 4 Jatikarya, Bekasi 17435
            Phone : (021) 845-93193 Fax:(021) 845-93192</h5>

    <table width="100%" align="center" class="tg1">
      <thead>
  
       </thead>
       <div class="panel-body">

        <div class="col-lg-1 col-md-1 col-xs-6">
         
          
        </div><br/><br/><br/>
      </div><div>
       <table width="100%" border="1pt" align="center" class="tg">
  <thead style="background: #3FD5C0;">
    <tr>
            
          <th>Posisi</th>
          <th>Barang</th>
          <th>Stok</th>
          <th>Satuan</th>  
          <th>Konversi</th>
          <th>Konversi Stok</th>
          <th>Satuan Terkecil</th>
          
    </tr>
  </thead>
  <tbody>
    <?php
      $arrRowspan = [];
      foreach ($cetak as $key => $value) {
        # code...
        if(isset($arrRowspan[$value->nama_gudang]['count']))
        {
          $arrRowspan[$value->nama_gudang]['count']+=1;
        }else
        {
          $arrRowspan[$value->nama_gudang]['count']=1;
        }
        
      }
      $gudang = '';
    ?>
     @foreach ($cetak as $key=>$value)
            <tr>
            @if($gudang != $value->nama_gudang)
              <td rowspan="{{ $arrRowspan[$value->nama_gudang]['count'] }}"><?php echo  $value->nama_gudang; $gudang=$value->nama_gudang?></td>
            @endif           
              
              <td><?php echo $value->nama_barang?></td>
              <td class="right-text"><?php echo $value->total_stock;?></td>
              <td><?php echo $value->satuan_name;?></td>
              <td class="right-text"><?php echo $value->nilai_konversi;?></td>
              <td class="right-text"><?php echo $value->qty_konversi;?></td>    
              <td><?php echo $value->nama_satuan_terkecil;?></td>         
            </tr>
          @endforeach
  </tbody>
</table>
    </div>
  </body>
</html>