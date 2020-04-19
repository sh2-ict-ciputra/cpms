
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
        font-size: 12px;
    }

/*    th {
        height: 50px;
    }*/

    body {
      font-size: 12px;
    }

/*    table { page-break-inside:auto }
    tr    { page-break-inside:avoid; page-break-after:auto }*/
</style>
</head>
<body>
    <div class="page" style="page-break-after: always;">
        <div class="panel panel - default">

            <table>
              <tr>
                <td style="width: 50%">
                    <table style="width: 100%;border: 1px solid black;">
                        <tr>
                            <td style="text-align: center;widows: 30%" rowspan="3">LOGO PROYEK</td>
                            <td style="">{{strtoupper($PRHeader->project->name)}}</td>
                        </tr>
                        <tr>
                            <td style="font-size: 12px;height: 50px">{{$PRHeader->project->address}}</td>
                        </tr>
                        <tr>
                            <td style="font-size: 12px">Phone: {{$PRHeader->project->phone}} Fax: {{$PRHeader->project->fax}}</td>
                        </tr>
                    </table>
                </td>
                <td style="width: 50%">
                   <table style="width: 100%;border: 1px solid black;">
                        <tr>
                            <td style="">{{strtoupper($project_pt->pt->name)}}</td>
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
                    <td style="">{{strtoupper($PRHeader->project->name)}}</td>
                </tr>
                <tr>
                    <td style="font-size: 12px">{{$PRHeader->project->address}}</td>
                </tr>
                <tr>
                    <td style="font-size: 12px">Phone: {{$PRHeader->project->phone}} Fax: {{$PRHeader->project->fax}}</td>
                </tr>
            </table> -->

            <table style="width: 100%;border: 1px solid black;">
                <tr>
                    <th style="text-align: center;background-color: gray;font-size: 20px">PURCHASE REQUEST</th>
                </tr>
            </table>
        </div>

        <div class="page panel-body">
          <div class="row">
            <div class="col-md-12"> 

                <table class="table">
                    <thead>
                        <tr>
                            <th>Kepada </th><th>Purchasing Dept.</th>
                            <th>No. </th><th>{{$PRHeader->no}}</th>
                        </tr>
                        <tr>
                            <th>Dari </th><th>{{$PRHeader->department->name}}</th>
                            <th>Tanggal </th><th>{{date ( "d-m-y" , strtotime ($PRHeader->date))}}</th>

                        </tr>
                        <tr>
                            <th></th><th></th>
                            <th>Keterangan </th><th>{{$PRHeader->description}}</th>
                        </tr>
                    </thead>
                </table>     
                <div id="valueItem">
                  <div class="subValueItem col-md-12">
                    <table id="table_details" class="PRD page table table-bordered">
                        <thead class="col-md-12" style="background-color: gray;">
                            <tr>
                                <th rowspan="2">No </th>
                                <th rowspan="2">Nama Barang</th>
                                <th rowspan="2">Brand</th>
                                <th rowspan="2">Qty</th>
                                <th rowspan="2">Satuan</th>
                                <th rowspan="2">Total Harga Estimasi (Rp.)</th>
                                <th colspan="3" class="text-center">Rekomendasi Supplier</th>
                                <th rowspan="2">Deskripsi</th>
                                <th rowspan="2">SPK / Budget</th>
                            </tr>
                            <tr>
                                <th>Supplier 1</th>
                                <th>Supplier 2</th>
                                <th>Supplier 3</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($PRDetail as $key => $value )
                            <tr class="page">
                                <td>{{ $key+1 }}</td>
                                <td>{{$value->item_project->item->sub_category->name}} {{$value->item_project->item->name or 'Kosong'}}</td>
                                <td>{{$value->brand->name or 'Kosong'}}</td>
                                <td>{{$value->quantity}}</td>
                                <td>{{$value->item_satuan->name or 'Kosong'}}</td>
                                <td>{{number_format($value->harga_estimasi,2,',','.')}}</td>
                                <td>{{$value->rec1->name or 'Kosong'}}</td>
                                <td>{{$value->rec2->name or 'Kosong'}}</td>
                                <td>{{$value->rec3->name or 'Kosong'}}</td>
                                <td>{{$value->description}}</td>
                                <td>{{$value->spk->no or ''}}</td>
                            </tr>
                            @endforeach
                          </tbody>
                    </table>
                  </div>
                </div>

                <table class="table">
                    <thead>
                        <tr>
                            <th style="width: 70%;text-align: left; border: 1px transparant;"></th>
                            <th style="width: 40%; border: 1px transparant;">Tgl Dibutuhkan (dd/mm/yy) {{date ( "d-m-y" , strtotime ($PRHeader->butuh_date))}}</th>
                        </tr>
                    </thead>
                </table>

                <table style="width: 100%;border: 1px solid black;" class="">
                    <tr>
                        <th style="font-size: 12px;border: 1px solid black;">Pemesan</th>
                        <th style="font-size: 12px;border: 1px solid black;">Disetujui</th>
                        <th style="font-size: 12px;border: 1px solid black;">Diketahui</th>
                    </tr>
                    <tr>
                        <th style="font-size: 12px;border: 1px solid black;height: 123px;vertical-align: bottom">
                            {{$pemesan[0]->jabatan}} 
                            <br/><br/><br/><br/><br/><br/> 
                            {{$pemesan[0]->name}}
                            <br/><br/>
                        </th>
                        <th style="font-size: 12px;border: 1px solid black;height: 123px;">
                            {{$penyetuju[0]->jabatan}} 
                            <br/><br/><br/><br/><br/><br/> 
                            {{$penyetuju[0]->name}}
                            <br/><br/>
                        </th>
                        <th style="font-size: 12px;border: 1px solid black;height: 123px;"></th>
                    </tr>
                </table>

                <div class="panel-heading">
                    <h3 style="text-align: right;">(dd/mm/yy) {{date ( "d-m-y h:m:s" , strtotime ($date))}} - {{$user->user_name}}</h3>
                    <h3 style="text-align: left;">catatan : {{$PRHeader->description}}</h3>
                </div>
              <!-- /.form-group -->
            </div>
            <!-- /.col -->
            <div class="col-md-12">
            </div>
            <!-- /.col -->
          </div>
        </div>
    </div>
</body>
</html>