<:DOCTYPE html>
<html>
<head>
    <title>produk PDF </title>
    <link href=" {{ asset ('public bootstrap/css/
bootstrap.min.css') }} "rel="stylesheet">
<style>
    table, td, th {
        /*border: 1px solid black;*/
        text-align: center;
    }

    table {
        border-collapse: collapse;
        width: 100%;
        border-spacing:10px;
    }
    body {
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
                            <td style="text-align: center;widows: 30%;border: 1px solid black;" rowspan="3">LOGO PROYEK</td>
                            <td style="border: 1px solid black;">{{strtoupper($OE->project->name)}}</td>
                        </tr>
                        <tr>
                            <td style="font-size: 12px;height: 50px;border: 1px solid black;">{{$OE->project->address}}</td>
                        </tr>
                        <tr>
                            <td style="font-size: 12px;border: 1px solid black;">Phone: {{$OE->project->phone}} Fax: {{$OE->project->fax}}</td>
                        </tr>
                    </table>
                </td>
                <td style="width: 50%">
                   <table style="width: 100%;border: 1px solid black;">
                        <tr>
                            <td style="border: 1px solid black;">{{strtoupper($project_pt->pt->name)}}</td>
                            <td style="text-align: center;widows: 30%;border: 1px solid black;" rowspan="3">LOGO TAMBAHAN (ISO)</td>
                        </tr>
                        <tr>
                            <td style="font-size: 12px;height: 50px;height: 51px;position: absolute;border: 1px solid black;"></td>
                        </tr>
                        <tr>
                            <td style="font-size: 12px;height: 14px;position: absolute;border: 1px solid black;"></td>
                        </tr>
                    </table>
                </td>
              </tr>
            </table>

            <table style="width: 100%;border: 1px solid black;">
                <tr>
                    <th style="text-align: center;background-color: gray;font-size: 20px">OWNER ESTIMATE</th>
                </tr>
            </table>
        </div>

        <div class="panel-body pull-right">
            <table style="width: 100%;border: 1px solid black;">
                <tr>
                    <th style="text-align: left; font-size: 12px;border: 1px solid black;" rowspan="">NO. PENGELOMPOKAN </th>
                    <th style="font-size: 12px;border: 1px solid black;">{{$group->no}}</th>
                </tr>
                <tr>
                    <th style="text-align: left; font-size: 12px;border: 1px solid black;" rowspan="">NO. OWNER ETIMATE </th>
                    <th style="font-size: 12px;border: 1px solid black;">{{$OE->no}}</th>
                </tr>
                <tr>
                    <th style="text-align: left; font-size: 12px;border: 1px solid black;" rowspan="">TGL DIBUAT (dd/mm/yy) </th>
                    <th style="font-size: 12px;border: 1px solid black;">{{date ( "d-m-y", strtotime ($date_dibuat[0]->created_at))}}</th>
                </tr>

                <tr>
                    <th style="text-align: left; font-size: 12px;border: 1px solid black;" rowspan="">TGL BARANG DIBUTUHKAN (dd/mm/yy)</th>
                    <th style="font-size: 12px;border: 1px solid black;">{{$tanggal_butuh}}</th>
                </tr>
            </table>

            <table id="table_details" class="table" style="width: 100%;border: 1px solid black;">
                <thead class="col-md-12" style="background-color: gray;">
                    <tr>
                        <th style="border: 1px solid black;">No </th>
                        <th style="border: 1px solid black;">Item</th>
                        <th style="border: 1px solid black;">Kode Item</th>
                        <th style="border: 1px solid black;">Brand</th>
                        <th style="border: 1px solid black;">Volume</th>
                        <th style="border: 1px solid black;">Satuan</th>
                        <th style="border: 1px solid black;">Hrg Sat (Rp/...)</th>
                        <th style="border: 1px solid black;">Jumlah Harga (Rp.)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $totalppn =0;
                        $totalpph=0;
                        $subtotal = 0;
                        $total_disc = 0;
                    ?>
                    @foreach($results as $key => $value )

                    <tr>
                        <td style="border: 1px solid black;">{{ $key+1 }}</td>
                        <td style="border: 1px solid black;">{{$value['itemname'] or 'Kosong'}}</td>
                        <td style="border: 1px solid black;">{{$value['item_kode'] or 'Kosong'}}</td>
                        <td style="border: 1px solid black;">{{$value['brand'] or 'Kosong'}}</td>
                        <td class="table-align-right" style="border: 1px solid black;">{{$value['totalqty']}}</td>
                        <td style="border: 1px solid black;">{{$value['satuan'] or 'Kosong'}}</td>
                        <td style="border: 1px solid black;text-align: right;">{{number_format(($value['price']),2,',','.')}}</td>
                        <td style="border: 1px solid black;text-align: right;">{{number_format(($value['price']*$value['totalqty']),2,',','.')}}</td>
                    </tr>
                    <?php
                        $subtotal += $value['price']*$value['totalqty'];
                      ?>
                    @endforeach

                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="6" rowspan=""style="border: 1px solid black;"></th>
                        <th style="text-align: center;border: 1px solid black;text-align: left;">Sub Total (Rp.)</th><th class="text-right" style=";border: 1px solid black;text-align: right;">{{ number_format($subtotal,2,".",",") }}</th>
                    </tr>
                </tfoot>
            </table>

            <table class="table" id="table_rekanans" style="width: 100%;border: 1px solid black;">
                <thead style="background-color: gray;">
                  <tr>
                    <th style="border: 1px solid black;">No</th>
                    <th style="border: 1px solid black;">Rekanan</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($OE_detail as $key =>$v)
                  <tr>
                    <td style="border: 1px solid black;">{{$key+1}}</td>
                    <td style="border: 1px solid black;">{{$v->rekanan->name}}</td>
                  </tr>
                  @endforeach
                </tbody>        
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