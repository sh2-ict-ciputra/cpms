
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
                            <td style="">{{strtoupper($group->project->name)}}</td>
                        </tr>
                        <tr>
                            <td style="font-size: 12px;height: 50px;border: 1px solid black;">{{$group->project->address}}</td>
                        </tr>
                        <tr>
                            <td style="font-size: 12px;border: 1px solid black;">Phone: {{$group->project->phone}} Fax: {{$group->project->fax}}</td>
                        </tr>
                    </table>
                </td>
                <td style="width: 50%">
                   <table style="width: 100%;border: 1px solid black;">
                        <tr>
                            <td style="">{{strtoupper($project_pt->pt->name)}}</td>
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
                    <th style="text-align: center;background-color: gray;font-size: 20px">PENGELOMPOKAN PR</th>
                </tr>
            </table>
        </div>

        <div class="panel-body pull-right">
            <table>
              <tr>
                <td>
                    <table style="width: 100%;border: 1px solid black;">
                        <tr>
                            <th style="text-align: left; font-size: 12px;border: 1px solid black;" rowspan="">NO. PENGELOMPOKAN </th>
                            <th style="font-size: 12px;border: 1px solid black;">{{$group->no}}</th>
                        </tr>
                        <tr>
                            <th style="text-align: left; font-size: 12px;border: 1px solid black;" rowspan="">TANGGAL DIBUAT (dd/mm/yy)</th>
                            <th style="font-size: 12px;border: 1px solid black;">{{date ( "d-m-y" , strtotime ($date_dibuat[0]->created_at))}}</th>
                        </tr>
                        <tr>
                            <th style="text-align: left; font-size: 12px;border: 1px solid black;" rowspan="">NO. PR </th>
                            <th style="font-size: 12px;border: 1px solid black;">
                                @foreach($uraian_pengelompokan as $key => $value)
                                    {{$value->no_pr}},
                                 @endforeach
                            </th>
                        </tr>
                        <tr>
                            <th style="text-align: left; font-size: 12px;border: 1px solid black;" rowspan="">DESKRIPSI </th>
                            <th style="font-size: 12px;border: 1px solid black;">{{$group->description}}</th>
                        </tr>
                    </table>
                </td>
                <td>
                </td>
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
                    @foreach($groupDetail as $key => $value )
                    <tr>
                        <td style="border: 1px solid black;">{{ $key+1 }}</td>
                        <td style="border: 1px solid black;">{{$value->detail_pr->item_project->item->name or 'Kosong'}}</td>
                        <td style="border: 1px solid black;">{{$value->detail_pr->item_project->item->kode or 'Kosong'}}</td>
                        <td style="border: 1px solid black;">{{$value->detail_pr->brand->name or 'Kosong'}}</td>
                        <td class="table-align-right" style="border: 1px solid black;">{{$value->detail_pr->quantity}}</td>
                        <td style="border: 1px solid black;">{{$value->detail_pr->item_satuan->name or 'Kosong'}}</td>
                        <td style="border: 1px solid black;text-align:right">{{number_format(($value->detail_pr->harga_estimasi),2,',','.')}}</td>
                        <td style="border: 1px solid black;text-align:right">{{number_format(($value->detail_pr->harga_estimasi*$value->detail_pr->quantity),2,',','.')}}</td>
                    </tr>
                    <?php
                        $subtotal += $value->detail_pr->harga_estimasi*$value->detail_pr->quantity;
                      ?>
                    @endforeach

                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="6" rowspan=""style="border: 1px solid black;"></th>
                        <th style="text-align: center;border: 1px solid black;">Sub Total (Rp.)</th><th class="text-right" style=";border: 1px solid black;text-align:right">{{ number_format($subtotal,2,".",",") }}</th>
                    </tr>
                </tfoot>
            </table>

            <table>
                <tr>
                    <th style="font-size: 12px;border: 1px solid black;">Disetujui</th>
                    <th style="font-size: 12px;border: 1px solid black;">Diketahui</th>       
                </tr>
                <tr>
                    <th style="font-size: 12px;border: 1px solid black;height: 123px;">
                            {{$penyetuju[0]->jabatan}} 
                            <br/><br/><br/><br/><br/><br/> 
                            {{$penyetuju[0]->name}}
                            <br/><br/>
                    </th>
                    <th style="font-size: 12px;border: 1px solid black;height: 123px;"></th>
                </tr>
            </table>


        </div>

    </div>
</body>
</html>