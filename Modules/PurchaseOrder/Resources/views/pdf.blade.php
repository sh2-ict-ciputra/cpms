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

    th {
        height: 10px;
    }
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
                            <td style="text-align: center;widows: 50%;border: 1px solid black;" rowspan="3">LOOGOOO</td>
                            <td style="border: 1px solid black;">{{strtoupper($PO->project->name)}}</td>
                        </tr>
                        <tr>
                            <td style="font-size: 12px;border: 1px solid black;height: 50px">{{$PO->project->address}}</td>
                        </tr>
                        <tr>
                            <td style="font-size: 12px;border: 1px solid black;">Phone: {{$PO->project->phone}} Fax: {{$PO->project->fax}}</td>
                        </tr>
                    </table>
                </td>
                <td style="width: 50%">
                   <table style="width: 100%;border: 1px solid black;">
                        <tr>
                            <td style="">{{strtoupper($project_pt->pt->name)}}</td>
                            <td style="text-align: center;widows: 50%;border: 1px solid black;" rowspan="3">LOOGOOO</td>
                        </tr>
                        <tr>
                            <td style="font-size: 12px;border: 1px solid black;height: 51px;position: absolute;"></td>
                        </tr>
                        <tr>
                            <td style="font-size: 12px;border: 1px solid black;height: 14px;position: absolute;"></td>
                        </tr>
                </table>
                </td>
              </tr>
            </table>

            <table style="width: 100%;border: 1px solid black;">
                <tr>
                    <th style="text-align: center;background-color: gray;font-size: 20px">PURCHASE ORDER</th>
                </tr>
            </table>
        </div>

        <div class="panel-body pull-right">
            <table style="position: block">
              <tr>
                <td>
                    <table style="width: 100%;border: 1px solid black;position: absolute;">
                        <tr>
                            <th style="text-align: left; font-size: 12px;position: absolute;" >KEPADA YTH. </th>
                            <th style="font-size: 12px;text-align: left;position: absolute;">{{strtoupper($PO->vendor->name)}}</th>
                        </tr>
                        <tr>
                            <th></th>
                            <th style="font-size: 12px;text-align: left">{{$PO->vendor->surat_alamat}}</th>
                        </tr>
                        <tr>
                            <th></th>
                            <th style="font-size: 12px;text-align: left">Phone: {{$PO->vendor->telp or '-'}} Fax: {{$PO->vendor->fax}}</th>
                        </tr>
                        <tr>
                            <th colspan="2" rowspan="" style="border: 1px solid black;position: absolute;text-align: left">{{$PO->description}}</th>
                        </tr>
                        
                    </table>
                </td>
                <td>
                   <table style="width: 100%;border: 1px solid black;">
                        <thead>
                            <tr>
                                <th style="font-size: 12px;border: 1px solid black;">No.PO </th><th style="font-size: 12px;border: 1px solid black;">{{$PO->no}}</th>
                            </tr>
                            <tr>
                                <th style="font-size: 12px;border: 1px solid black;">Tanggal</th><th style="font-size: 12px;border: 1px solid black;">{{$date}}</th>
                            </tr>
                            <tr>
                                <th style="font-size: 12px;border: 1px solid black;">Cara Pembayaran</th><th style="font-size: 12px;border: 1px solid black;">
                                    <table style="width: 100%;">
                                        <tr>
                                            <th colspan="2" style="text-align: center;background-color: white;">{{strtoupper($PO->tender_menang->penawaran->metodePembayaran->name)}}</th>
                                        </tr>
                                        <tr>
                                            <th style="text-align: center;">DP</th>
                                            <th style="text-align: left;">{{$PO->tender_menang->penawaran->DP}}%</th>
                                        </tr>
                                        @if($PO->tender_menang->penawaran->id_metode_pembayaran == 1)
                                            @for($i=1; $i<= $PO->tender_menang->penawaran->lama_cicilan; $i++)
                                                @foreach($cod_pembayaran as $key => $value)
                                                    @if($value->cod_ke == $i)
                                                        <tr>
                                                            <th style="text-align: center;">COD {{$i}}</th>
                                                            <th style="text-align: left;">{{$value->item_name}} | {{$value->quantity}} {{$value->satuan}}</th>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            @endfor
                                         @elseif($PO->tender_menang->penawaran->id_metode_pembayaran == 2)
                                            @foreach($termin_pembayaran as $key => $value)
                                                <tr>
                                                    <th style="text-align: center;">Termin {{$key+1}}</th>
                                                    <th style="text-align: left;">{{$value->percentage}}%</th>
                                                    <th style="text-align: left;">{{date ( "y-m-d" , strtotime ($value->termin_date))}}</th>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </table>
                                </th>
                            </tr>
                            <tr>
                                <th style="font-size: 12px;border: 1px solid black;">Tanggal Kirim</th><th style="font-size: 12px;border: 1px solid black;">{{$date_butuh}}</th>
                            </tr>
                            <tr>
                                <th style="font-size: 12px;border: 1px solid black;">No. PR</th>
                                <th style="font-size: 12px;border: 1px solid black;">
                                    @foreach($uraian_PO as $key => $value)
                                        {{$value->no_pr}},
                                    @endforeach
                                </th>
                            </tr>
                        </thead>
                </table>
                </td>
              </tr>
            </table>

            <table id="table_details" class="table" style="width: 100%;border: 1px solid black;">
                <thead class="col-md-12" style="background-color: gray;">
                    <tr>
                        <th style="border: 1px solid black;">No </th>
                        <th style="border: 1px solid black;">Kode Item</th>
                        <th style="border: 1px solid black;">Item</th>
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
                    @foreach($PODetail as $key => $value )
                    <tr>
                        <td style="border: 1px solid black;">{{ $key+1 }}</td>
                        <td style="border: 1px solid black;">{{$value->item->item->kode or 'Kosong'}}</td>
                        <td style="border: 1px solid black;">
                                <table style="width: 100%;border: 1px solid black;">
                                <tr>
                                    <td style="" rowspan="">{{$value->item->item->name or 'Kosong'}}</td>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid black;">{{$value->description or 'Kosong'}}</td>
                                </tr>
                            </table>
                        </td>
                        <td style="border: 1px solid black;">{{$value->brand->name or 'Kosong'}}</td>
                        <td class="table-align-right" style="border: 1px solid black;">{{$value->quantity}}</td>
                        <td style="border: 1px solid black;">{{$value->satuan->name or 'Kosong'}}</td>
                        <td style="border: 1px solid black;text-align:right;">{{number_format(($value->harga_satuan),2,".",",")}}</td>
                        <td style="border: 1px solid black;text-align:right;">{{number_format(($value->harga_satuan*$value->quantity),2,".",",")}}</td>
                    </tr>
                     <?php
                        $diskon = $value->discon/100*($value->harga_satuan*$value->quantity);
                        $total_disc += $diskon;
                        $sbtotal = $value->harga_satuan*$value->quantity;
                        $subtotal += $sbtotal-$diskon;

                        $totalppn += $PO->vendor->ppn/100*($sbtotal-$diskon);
                        $totalpph += $value->pph/100*($sbtotal-$diskon);
                      ?>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="6" rowspan="3"style="border: 1px solid black;"></th>
                        <th style="text-align: center;border: 1px solid black;text-align:left;">Sub Total (Rp.)</th><th class="text-right" style=";border: 1px solid black;text-align:right;">{{ number_format($subtotal,2,".",",") }}</th>
                    </tr>
                    <tr>
                      <th style="text-align: center;border: 1px solid black;text-align:left;">PPN (Rp.)</th><th class="text-right" style=";border: 1px solid black;text-align:right;">{{ number_format($totalppn,2,".",",") }}</th>
                    </tr>
                    <tr>
                      <th style="text-align: center;border: 1px solid black;text-align:left;">Grand Total (Rp.)</th><th class="text-right" style=";border: 1px solid black;text-align:right;">{{ number_format(($subtotal)+$totalppn,2,".",",") }}</th>
                    </tr>
                </tfoot>
            </table>

            <table style="page-break-after: always;">
              <tr>
                <td style="width: 60%">
                    <table style="width: 100%;border: 1px solid black;">
                        <tr>
                            <th style="text-align: center; font-size: 12px;border: 1px solid black;" colspan="2">KONFIRMASI SUPPLIER</th>
                        </tr>
                        <tr>
                            <th style="text-align: left;font-size: 12px;border: 1px solid black;width: 50%">PO DITERIMA TANGGAL</th>
                            <th style="text-align: left; font-size: 12px;border: 1px solid black;width: 50%"></th>
                        </tr>
                        <tr>
                            <th style="text-align: left;font-size: 12px;border: 1px solid black;width: 50%">BARANG AKAN DIKIRIM TANGGAL</th>
                            <th style="text-align: left; font-size: 12px;border: 1px solid black;width: 50%"></th>
                        </tr>
                        <tr>
                            <th style="font-size: 12px;border: 1px solid black;height: 13%;" colspan="2"> Tanda Tangan Supplier</th>
                        </tr>
                    </table>
                    
                </td>
                <td style="width: 40%">
                    <table style="width: 100%;border: 1px solid black;">
                        <tr>
                            <th style="font-size: 12px;border: 1px solid black;">Disetujui</th>
                        </tr>
                        <tr>
                            <th style="font-size: 12px;border: 1px solid black;height: 17%;"></th>
                        </tr>

                    </table>
                </td>
              </tr>
            </table>

            <table style="width: 100%;border: 1px solid black;">
                <tr>
                    <th style="text-align: center;background-color: white;font-size: 20px;" colspan="4">REKAPITULASI NILAI PO</th>
                </tr>
                <tr>
                    <th colspan="4" style="text-align: center;">{{$project_pt->pt->name}}</th>
                </tr>
                <tr>
                    <th style="text-align: center;background-color: white;font-size: 12px;border: 1px solid black;width: 25%">Supplier </th>
                    <th style="text-align: center;background-color: white;font-size: 12px;border: 1px solid black;width: 25%">{{strtoupper($PO->vendor->name)}}</th>
                    <th style="text-align: center;background-color: white;font-size: 12px;border: 1px solid black;width: 25%">Periode</th>
                    <th style="text-align: center;background-color: white;font-size: 12px;border: 1px solid black;width: 25%">{{$periode_awal}} s/d {{$date}}</th>
                </tr>
            </table>

            <table id="" class="table" style="width: 100%;border: 1px solid black;">
                <thead class="col-md-12" style="background-color: gray;">
                    <tr>
                        <th style="border: 1px solid black;">No </th>
                        <th style="border: 1px solid black;">Tanggal</th>
                        <th style="border: 1px solid black;">No. PO</th>
                        <th style="border: 1px solid black;">Harga Total</th>
                        <th style="border: 1px solid black;">Rekapitulasi Harga Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $total = 0;
                    ?>
                    @foreach($results as $key => $v )
                     <?php
                        $total += $v['grand_total'];
                    ?>
                    <tr>
                        <td style="border: 1px solid black;">{{ $key+1 }}</td>
                        <td style="border: 1px solid black;">{{$v['tanggal'] or 'Kosong'}}</td>
                        <td style="border: 1px solid black;">{{$v['no_po'] or 'Kosong'}}</td>
                        <td style="border: 1px solid black;">{{number_format(($v['grand_total']),2,".",",")}}</td>
                        <td style="border: 1px solid black;">{{number_format(($total),2,".",",")}}</td>
                    </tr>
                   
                    @endforeach
                </tbody>
                <tfoot>
                    
                    <tr>
                        <th colspan="2" rowspan="" style="border: 1px solid black;"></th>
                        <th style="text-align: center;border: 1px solid black;">Total (Rp.)</th><th class="text-right" style=";border: 1px solid black;">{{ number_format($total,2,".",",") }}</th>
                        <th colspan="" rowspan="" style="border: 1px solid black;"></th>
                    </tr>
                </tfoot>
            </table>


        </div>

    </div>
</body>
</html>