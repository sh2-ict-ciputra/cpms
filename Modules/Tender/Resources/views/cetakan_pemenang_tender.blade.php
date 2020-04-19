<:DOCTYPE html>
<html>
<head>
    <title>produk PDF </title>
    <link href=" {{ asset ('public bootstrap/css/
bootstrap.min.css') }} "rel="stylesheet">
<style>
    /* table, td, th {
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
    /* body{
      font-size: 12px;
    } */
</style>
</head>
<body>
    <div class="panel panel - default">
        <div class="panel-heading">
            <table style="width: 100%;">
                <tr>
                    <td style="text-align: center;width: 25%;" rowspan="3">LOOGOOO</td>
                    <td style="text-align: center;"><strong>{{strtoupper($project_pt->pt->name)}}</strong></td>
                </tr>
                <tr>
                    <td style="text-align: center;">{{$project_pt->pt->address}}</td>
                </tr>
                <tr>
                    <td style="text-align: center;">Phone: {{$project_pt->pt->phone}}</td>
                </tr>
            </table>
            <table style="width: 100%;margin-top:20px;">
                <tr>
                    <td style="text-align: left;height: 45px;" colspan="2">{{$project_pt->pt->city->name or ''}},{{$date}}</td>
                </tr>
                <tr>
                    <td style="text-align: left;width: 15%;">No. Surat</td>
                    <td style="text-align: left;">: {{$korespondensi->tender_rekanan->tender->menangs[0]->tender_rekanan->sipp_no}}</td>
                </tr>
                <!-- <tr>
                    <td style="text-align: left;width: 15%;">Perihal</td>
                    <td style="text-align: left;">: Undangan Aanwidjzing {{$korespondensi->tender_rekanan->tender->name}}</td>
                </tr> -->
            </table>
        </div>

        <div class="panel-body pull-right">
            <table style="width: 100%;margin-top:20px;">
                <tr>
                    <td style="text-align: left;">Kepada Yth,</td>
                </tr>
                <tr>
                    <td style="text-align: left;"><strong>{{$korespondensi->tender_rekanan->rekanan->name}}</strong></td>
                </tr>
                <tr>
                    <td style="text-align: left;">{{$korespondensi->tender_rekanan->rekanan->surat_alamat}}</td>
                </tr>
                <tr>
                    <td style="text-align: left;">Tlp.{{$korespondensi->tender_rekanan->rekanan->telp}}, Fax.{{$korespondensi->tender_rekanan->rekanan->telp}}</td>
                </tr>
                <tr>
                    <td style="text-align: left;">PIC. {{$korespondensi->tender_rekanan->rekanan->cp_name}}-{{$korespondensi->tender_rekanan->rekanan->jabatan}}</td>
                </tr>
            </table>
            <table style="width: 100%;margin-top:20px;">
                <tr>
                    <td style="text-align: left;">Dengan hormat,</td>
                </tr>
                <tr>
                    <td style="text-align: left;">Bersama ini kami bahwa pemenang pekerjaan <strong>{{$korespondensi->tender_rekanan->tender->name}} adalah</strong></td>
                </tr>
            </table>
            <table style="width: 100%;margin-top:20px;">
                <tr>
                    <td style="text-align: center;font-size: 20px"><strong>{{$korespondensi->tender_rekanan->tender->menangs[0]->tender_rekanan->rekanan->name}}</strong></td>
                </tr>
            </table>
            
        </div>

        <table style="width: 100%;margin-top:20px;">
                <tr>
                    <td style="text-align: left;">
                        Keputusan ini diambil setelah diadakan proses tender dan dengan mempertimbangkan semua aspek penilaian, Bagi kontraktor yang belum terpilih, Kami masih mengharapkan partisipasi dan kerjasamanya pada masa yang akan datang.<br/>
                        Demikian kami sampaikan, atas perhatiannya kami ucapkan terima kasih.
                    </td>
                </tr>
        </table>
        <table style="width: 100%;margin-top:20px;">
                <tr>
                    <td style="width: 60%"></td>
                    <td style="text-align: center;">Hormat kami,</td>
                </tr>
                <tr>
                    <td style="width: 60%"></td>
                    <td style="text-align: center;height: 123px;">
                            <br/><br/><br/><br/><br/><br/> 
                            {{$penyetuju[0]->name}}<br/>
                            {{$penyetuju[0]->jabatan}} 
                            <br/><br/>
                    </td>
                </tr>
        </table>
    </div>
</body>
</html>