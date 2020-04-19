
<:DOCTYPE html>
<html>
<head>
    <title>produk PDF </title>
    <link href=" {{ asset ('public bootstrap/css/
bootstrap.min.css') }} "rel="stylesheet">
<style>
    table, td, th {
        border: 1px solid black;
        text-align: center;
    }

    table {
        border-collapse: collapse;
        width: 100%;
        border-spacing:10px;
    }

/*    th {
        height: 30px;
    }*/
    body{
      font-size: 12px;
    }
</style>
</head>
<body>
    <div class="panel panel - default">
        <div class="panel-heading">

           <table style="border: 1px solid black;">
           <tr>
                <td style="width: 50%">
                    <table style="width: 100%;border: 1px solid black;">
                        <tr>
                            <td style="text-align: center;widows: 30%" rowspan="3">LOGO PROYEK</td>
                            <td style="font-size: 17px">{{strtoupper($project->name)}}</td>
                        </tr>
                        <tr>
                            <td style="font-size: 12px;height: 50px">{{$project->address}}</td>
                        </tr>
                        <tr>
                            <td style="font-size: 12px">Phone: {{$project->phone}} Fax: {{$project->fax}}</td>
                        </tr>
                    </table>
                </td>
                <td style="width: 50%">
                   <table style="width: 100%;border: 1px solid black;">
                        <tr>
                            <td style="font-size: 17px">{{strtoupper($project_pt->pt->name)}}</td>
                            <td style="text-align: center;widows: 30%" rowspan="3">LOGO TAMBAHAN (ISO)</td>
                        </tr>
                        <tr>
                            <td style="font-size: 12px;height: 50px;height: 51px;position: absolute;"></td>
                        </tr>
                        <tr>
                            <td style="font-size: 12px;height: 14px;position: absolute;"></td>
                        </tr>
                    </table>
                </td>
              </tr>
            </table>

            <!-- <table style="width: 100%;">
                <tr>
                    <td style="text-align: center;" rowspan="3">LOGO</td>
                    <td style="">{{strtoupper($project->name)}}</td>
                </tr>
                <tr>
                    <td style="font-size: 12px">{{$project->address}}</td>
                </tr>
                <tr>
                    <td style="font-size: 12px">Phone: {{$project->phone}} Fax: {{$project->fax}}</td>
                </tr>
            </table> -->

            <table style="width: 100%;border: 1px solid black;">
                <tr>
                    <th style="text-align: center;background-color: gray;font-size: 20px">REKPITULASI HARGA PENAWARAN</th>
                </tr>
            </table>
        </div>

        <div class="panel-body pull-right">
            <table>
              <tr>
                <td>
                    <table style="width: 100%;border: 1px solid black;">
                        <tr>
                            <th style="text-align: left; font-size: 12px;border: 1px solid black;" rowspan="">NO. TENDER </th>
                            <th style="font-size: 12px;border: 1px solid black;">{{$getDataTender->no}}</th>
                        </tr>
                        <tr>
                            <th style="text-align: left; font-size: 12px;border: 1px solid black;" rowspan="">PENAWARAN HARGA KE</th>
                            <th style="font-size: 12px;border: 1px solid black;">{{$penawaran}}</th>
                        </tr>

                    </table>
                </td>
                <td>
                   <table style="width: 100%;border: 1px solid black;">
                        <thead>
                            <tr>
                                <th style="font-size: 12px;border: 1px solid black;">USULAN PEMENANG </th><th style="font-size: 12px;border: 1px solid black;">{{$checkPemenang->tender_purchase_request_group_rekanan_detail->rekanan->name or "belum dipilih"}}</th>
                            </tr>
                            <tr>
                                <th style="font-size: 12px;border: 1px solid black;">TANGGAL TENDER </th>
                                <th style="font-size: 12px;border: 1px solid black;">{{date( "d-m-y" , strtotime ($penawaran_date->penawaran_date))}} s/d {{date( "d-m-y" , strtotime ($penawaran_date->klarifikasi_date))}}</th>
                            </tr>
                        </thead>
                </table>
                </td>
              </tr>
            </table>

            <table class="table table-bordered table-striped dataTable" id="table_comparison">
                <thead style="background-color: lightgray;">
                    <tr>
                        <th rowspan="2" style="font-size: 12px;">Barang Penawaran</th>
                        <th rowspan="2" style="font-size: 12px;">Volume</th>
                        <th rowspan="2" style="font-size: 12px;">Satuan</th>
                        <th colspan="{{ count($data_rekanan) }}" class="text-center" style="font-size: 12px;">Harga Satuan (Rp/...)</th>
                        <th colspan="{{ count($data_rekanan) }}" class="text-center" style="font-size: 12px;">Total Harga(Rp.)</th>
                        <th colspan="{{ count($data_rekanan) }}" class="text-center" style="font-size: 12px;">Brand</th>
                    </tr>
                    <tr>
                       <?php
                            foreach($join_data_rekanan as $key => $value){
                              $split_value = explode("-", $value);
                            if($key < (count($join_data_rekanan)/3))
                            {
                              print "<th style='background-color: lightgray;font-size: 12px;'>".$split_value[0]."</th>";
                            }
                            else if($key == (count($join_data_rekanan)/3))
                            {
                              print "<th class='sum' data-ppn='".$split_value[2]."' style='background-color: lightgray;font-size: 12px;'>".$split_value[0]."</th>";
                            }
                            else if($key >= ((count($join_data_rekanan)/3)*2))
                            {
                              print "<th class='sg' style='background-color: lightgray;font-size: 12px;'>".$split_value[0]."</th>";
                            }
                            else
                            {
                                print "<th class='sum' data-ppn='".$split_value[2]."'' style='background-color: lightgray;font-size: 12px;'><input type='hidden' name='rekanan_id' id='rekanan_id' value='".$split_value[1]."' />".$split_value[0]."</th>";
                            }

                          }
                        ?>  
                    </tr>
                </thead>

                <tbody>
                      <?php

                       foreach ($result as $key => $value) {
                         # code...
                          print "<tr>";
                          print "<td style='font-size: 12px;'>".$value['item_name']."</td>";
                          print "<td class='text-right' style='font-size: 12px;'>".$value['volume']."</td>";
                          print "<td style='font-size: 12px;'>".$value['satuan_name']."</td>";
                          foreach ($value['satuan_price'] as $k => $v) {
                              print "<td class='text-right money' style='font-size: 12px;text-align:right;'>".$v."</td>";
                            
                          }
                          print "</tr>"; 
                       }
                     ?>
                </tbody>
                <tfoot>
                        <tr>
                          <th colspan="{{ (count($join_data_rekanan)/3)+3 }}" class="text-right" style="font-size: 12px;text-align: right;">Sub Total</th>
                          @for($i=0; $i < count($join_data_rekanan)/3;$i++)
                            <?php
                                $subtotal = 0;
                                foreach ($result as $key => $nilai){
                                    $subtotal += $nilai['satuan_price'][count($join_data_rekanan)/3+$i];
                                }
                            ?>
                            <th class="text-right sub_total money" style="text-align:right;">{{number_format(($subtotal),2,".",",")}}
                            </th>
                          @endfor
                          <th colspan="{{ (count($join_data_rekanan)/3) }}"></th>
                        </tr>

                        <tr>
                          <th colspan="{{ (count($join_data_rekanan)/3)+3 }}" class="text-right" style="font-size: 12px;text-align: right;">PPN (Rp.)</th>
                          @for($i=0; $i < count($join_data_rekanan)/3;$i++)
                            <?php
                                    $subppn = 0;
                                    $subtotal = 0;
                                    $split_value = explode("-", $join_data_rekanan[$i]);

                                    foreach ($result as $key => $nilai){
                                        $subtotal += $nilai['satuan_price'][count($join_data_rekanan)/3+$i];
                                    }
                            ?>
                            <th class="text-right ppn_value money" style="text-align:right;">{{number_format(($split_value[2]*$subtotal/100),2,".",",")}}
                            </th>
                          @endfor
                          <th colspan="{{ (count($join_data_rekanan)/3) }}"></th>
                        </tr>

                        <tr>
                          <th colspan="{{ (count($join_data_rekanan)/3)+3 }}" class="text-right" style="font-size: 12px;text-align: right;">Grand Total</th>
                          @for($i=0; $i < count($join_data_rekanan)/3;$i++)
                            <?php
                                    $subppn = 0;
                                    $subtotal = 0;
                                    $grand_total = 0;
                                    $split_value = explode("-", $join_data_rekanan[$i]);

                                    foreach ($result as $key => $nilai){
                                        $subtotal += $nilai['satuan_price'][count($join_data_rekanan)/3+$i];
                                    }
                                    $grand_total = $subtotal + ($split_value[2]*$subtotal/100);
                            ?>
                            <th class="text-right grand_total money" style="text-align:right;">{{number_format(($grand_total),2,".",",")}}
                            </th>
                          @endfor
                          <th colspan="{{ (count($join_data_rekanan)/3) }}"></th>
                        </tr>
                </tfoot>
            </table>

            <table>
                <tr>
                    <th style="font-size: 12px;border: 1px solid black;">Disetujui</th>
                    <th style="font-size: 12px;border: 1px solid black;">Diketahui</th>
                </tr>
                <tr>
                    <th style="font-size: 12px;border: 1px solid black;height: 123px;"></th>
                    <th style="font-size: 12px;border: 1px solid black;height: 123px;"></th>
                </tr>
            </table>


        </div>

    </div>
</body>
</html>