
<:DOCTYPE html>
<html>
<head>
    <title>produk PDF </title>
    <link href=" {{ asset ('public bootstrap/css/bootstrap.min.css') }} "rel="stylesheet">
<style>
    table,tr,th {
        /* border: 1px solid black; */
        /* text-align: center; */
    }

    table {
        border-collapse: collapse;
        width: 100%;
        border-spacing:10px;
        font-size: 12px;
    }

   .right {
        text-align: right;
    }

    th, .left {
        text-align: left;
    }

    body {
      font-size: 12px;
    }

</style>
</head>
<body>
    <div class="page" style="page-break-after: always;">
        <div class="panel panel - default">
            <table style="width: 100%;">
                <tr>
                    <th style="text-align: center;background-color: lightgray;font-size: 20px">BUKTI PEMBAYARAN UANG</th>
                </tr>
            </table>
        </div>

        <div class="page panel-body">
          <div class="row">
            <div class="col-md-12"> 

                <table class="table">
                    <thead>
                        <tr>
                            <th style="width: 20%"></th>
                            <th style="width: 2%"></th>
                            <th style="width: 36%"></th>
                            <th style="width: 5%"></th>
                            <th style="width: 10%;"> Voucher no</th>
                            <th style="width: 2%">:</th>
                            <th style="width: 25%"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th>PROJECT</th>
                            <th>:</th>
                            <th>JO CIPUTRA YASMIN</th>
                            <th></th>
                            <th>NO</th>
                            <th>:</th>
                            <th>XXXXXXXXXXXXX</th>
                        </tr>
                        <tr>
                            <th>DIBAYARKAN KEPADA</th>
                            <th>:</th>
                            <th>PT. XXXXX</th>
                            <th></th>
                            <th>TGL</th>
                            <th>:</th>
                            <th>XXXXXX</th>
                        </tr>
                    </tbody>
                </table>     

                <table class="table">
                    <thead>
                        <tr>
                            <th style="width: %">KDODE</th>
                            <th style="width: %">KETERANGAN</th>
                            <th style="width: %">JUMLAH</th>
        
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                        <tr>
                            <th></th>
                            <th>PPN 10%</th>
                            <th>..........</th>
                        </tr>
                        <tr>
                            <th></th>
                            <th>PPh 3%</th>
                            <th>..........</th>
                        </tr>
                    </tbody>
                </table> 
                <table>
                    <thead>
                        <tr>
                            <th style="width: 70%" rowspan="2"></th>
                            <th style="width: 15%"> TOTAL :</th>
                            <th style="width: 15%"></th>
                        </tr>
                        <tr>
                            <th style="width: 15%">Jatuh Tempo</th>
                            <th style="width: 15%"></th>
                        </tr>
                    </thead>
                </table>

                <table>
                    <thead>
                        <tr>
                            <th style="width: 20%" rowspan="3">DIBUAT</th>
                            <th style="width: 20%" rowspan="3">DISETUJUI</th>
                            <th style="width: 20%" rowspan="3">DITERIMA</th>
                            <th style="width: 20%" rowspan="3">DIBAYARKA:</th>
                            <th style="width: 10%">TUNAI</th>
                        </tr>
                        <tr>
                            <th style="width: 10%">CHEQUE/B.G</th>
                        </tr>
                        <tr>
                            <th style="width: 10%">JUMLAH</th>
                        </tr>
                    </thead>
                </table>
        </div>
    </div>
</body>
</html>