<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Admin QS | Dashboard</title>
    @include("master/header")
    <style type="text/css">
        .table-align-right {
            text-align: right;
        }
    </style>
</head>

<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">

        @include("master/sidebar_project")

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->

            <section class="content-header">
                <h1 style="text-align:center">Tambah OE Pengelompokkan PR</h1>
            </section>
            <section class="content-header">
                <div class="" style="float: none">
                    <button class="col-lg-1 col-md-2 col-sm-2 btn btn-primary"
                        onclick="location.href='{{ url('/tenderpurchaserequest/pengelompokanDetail')."?id=".$_GET['id']}}'"
                        style="float: none; border-radius: 20px; padding-left: 0">
                        <i class="fa fa-fw fa-arrow-left"></i>&nbsp;&nbsp;Back
                    </button>
                    <button class="col-lg-1 col-md-2 col-sm-2 btn btn-primary" onclick="window.location.reload()"
                        style="float: right; border-radius: 20px; padding-left: 0;">
                        <i class="fa fa-fw fa-refresh"></i>&nbsp;&nbsp;Refresh
                    </button>
                </div>
            </section>
            <section class="content">
                <!-- Info boxes -->
                <form class="form-horizontal" action="{{ url('/tenderpurchaserequest/store_oe') }}" method="post"
                    autocomplete="off" name="form-oe" id="form-oe">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box box-primary">
                                <div class="box-header with-border">
                                    <h3 class="box-title">

                                    </h3>
                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                <!-- box-header -->
                                <div class="box-body">
                                    <table id="ListTelahKelompok" class="table table-bordered table-striped dataTable"
                                        role="grid">
                                        <thead style="background-color: greenyellow;">
                                            <tr>
                                                <th>No Group Tender</th>
                                                <th style="width: 10%">Item</th>
                                                <th style="width: 10%">Brand</th>
                                                <th style="width: 10%">Satuan</th>
                                                <th class="table-align-right" style="width: 5%">Quantity</th>
                                                <th class="table-align-right" style="width: 15%">Harga Sat. (Rp/...)
                                                </th>
                                                <th class="table-align-right" style="width: 15%">Sub Total (Rp.)</th>
                                                <th class="table-align-right" style="width: 15%">PPN (Rp.)</th>
                                                <th class="text-right" style="width: 20%">Rentang Harga Sat. (Rp.)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $total = 0;
                                                $ppn = 0;
                                            ?>
                                            @foreach($results as $key => $v )
                                                <tr>
                                                    <td>{{ $v['tprg_no'] }}</td>
                                                    <td>{{$v['tprg_itemname']}}</td>
                                                    <td>{{$v['tprg_brand']}}</td>
                                                    <td>{{$v['tprg_satuan']}}</td>
                                                    <!-- <td class="table-align-right">{{$v['tprg_totalqty']}}</td> -->
                                                    <td class="text-right">
                                                        <input type="text" name="" value="{{$v['tprg_totalqty'] }}"
                                                            id="quantity{{$key}}" class="quantity form-control text-right"
                                                            readonly="true"
                                                            style="width: 100%;border: none;background: transparent;">
                                                    </td>
                                                    <input type="text" name="id_detail[]" value="{{$v['tprg_id_detail']}}"
                                                        id="id_detail{{$key}}" hidden="">
                                                    <td class="text-right">
                                                        <input type="text" name="harga_satuan[]" id="harga_satuan{{$key}}"
                                                            value="{{ number_format($v['tprg_price'],2,'.',',') }}"
                                                            class="harga_satuan form-control text-right"
                                                            style="width: 100%;" />
                                                    </td>
                                                    <!-- <td class="text-right">{{ number_format($v['tprg_price'],2,".",",") }}</td> -->
                                                    <td class="text-right">
                                                        <input type="text" name="total_harga[]" id="total_harga{{$key}}"
                                                            value="{{ number_format(($v['tprg_totalqty']*$v['tprg_price']),2,'.',',') }}"
                                                            class="text-right total_harga form-control" readonly="true"
                                                            style="width: 100%;border: none;background: transparent;">
                                                    </td>
                                                    <!-- <td class="text-right">{{ number_format(($v['tprg_totalqty']*$v['tprg_price']),2,".",",") }}</td> -->

                                                    <td class="text-right">
                                                    <input type="text" name="ppn[]"
                                                        value="{{ number_format($v['status_ppn']/100*($v['tprg_totalqty']*$v['tprg_price']),2,'.',',') }}" id="ppn{{$key}}" class="text-right ppn form-control" readonly="true" data-ppn="{{$v['status_ppn']/100}}" style="width: 100%;border: none;background: transparent;" hidden="">
                                                    </td>

                                                    <!-- <td class="text-right">{{ number_format($v['status_ppn']/100*($v['tprg_totalqty']*$v['tprg_price']),2,".",",") }}</td> -->

                                                    <td class="text-right">{{ $v['range'] }}</td>
                                                    <?php 
                                                        if($v['status_ppn'])
                                                        {
                                                        $ppn += $v['status_ppn']/100*($v['tprg_totalqty']*$v['tprg_price']);
                                                        }
                                                        $total+=  ($v['tprg_totalqty']*$v['tprg_price']);

                                                    ?>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <table class="table" id="table_total">
                                        <thead>
                                            <tr>
                                                <th style="width: 10%"></th>
                                                <th style="width: 10%"></th>
                                                <th style="width: 10%"></th>
                                                <th style="width: 5%"></th>
                                                <th class="text-right"  style="width: 15%"><strong>Sub Total (Rp.) :</strong></th>
                                                <th class="text-right" id="total_keseluruhan_harga"  style="width: 15%">
                                                    <input type="text" name="sub_total" id="sub_total" value="{{ number_format($total,2,'.',',') }}" class="text-right form-control" readonly="true" style="width: 100%;border: none; background: transparent;">
                                                </th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                            <tr>
                                                <th style="width: 10%"></th>
                                                <th style="width: 10%"></th>
                                                <th style="width: 10%"></th>
                                                <th style="width: 5%"></th>
                                                <th class="text-right"  style="width: 15%"><strong>PPN (Rp.) :</strong></th>
                                                <th class="text-right" id="total_ppn" style="width: 15%">
                                                <input type="text" name="ppn_total" id="ppn_total" value="{{ number_format($ppn,2,'.',',') }}" class="text-right form-control" readonly="true" style="width: 100%;border: none;background: white">
                                                </th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                            <tr>
                                                <th style="width: 10%"></th>
                                                <th style="width: 10%"></th>
                                                <th style="width: 10%"></th>
                                                <th style="width: 5%"></th>
                                                <th class="text-right"  style="width: 15%"><strong>Grand Total (Rp.) :</strong></th>
                                                <th class="text-right" id=""  style="width: 15%">
                                                <input type="text" name="grand_total" id="grand_total" value="{{ number_format($total+$ppn,2,'.',',') }}" class="text-right form-control" readonly="true" style="width: 100%;border: none;background: white">
                                                </th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="box box-primary">
                                <div class="box-header with-border">
                                    <h3 class="box-title">
                                        Penjadwalan Tender
                                    </h3>
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body">
                                    <div class="col-md-12">
                                        <input type="hidden" name="_alldatasend" id="all_data_send" />
                                        <input type="hidden" name="tprg_id" id="tprg_id"
                                            value="{{ $tender_purchase_request->id }}" />

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="inputEmail3"
                                                    class="col-sm-4 control-label">Aanwijzing</label>
                                                <div class="col-sm-8">
                                                    <div
                                                        class="input-group input-medium date date-picker datePicker_">
                                                        <!--  <input type="text" class="form-control" name="aanwijzing" id="aanwijzing" value="<?php echo date('Y-m-d'); ?>"> -->
                                                        <input class="form-input col-md-12" type="date"
                                                            name="aanwijzing" id="aanwijzing"
                                                            min="<?php echo date('Y-m-d'); ?>"
                                                            style="padding-left:15px;width: 100%"
                                                            value="<?php echo date('Y-m-d'); ?>"
                                                            required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputEmail3"
                                                    class="col-sm-4 control-label">Klarifikasi 1</label>
                                                <div class="col-sm-8">
                                                    <div
                                                        class="input-group input-medium date date-picker datePicker_">
                                                        <!-- <input type="text" class="form-control" name="klarifikasi1" id="klarifikasi1" value="<?php echo date('Y-m-d'); ?>"> -->
                                                        <input class="form-input col-md-12" type="date"
                                                            name="klarifikasi1" id="klarifikasi1"
                                                            min="<?php echo date('Y-m-d'); ?>"
                                                            style="padding-left:15px;width: 100%"
                                                            value="<?php echo date('Y-m-d'); ?>"
                                                            required>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="inputEmail3"
                                                    class="col-sm-4 control-label">Klarifikasi 2</label>
                                                <div class="col-sm-8">
                                                    <div
                                                        class="input-group input-medium date date-picker datePicker_">
                                                        <!-- <input type="text" class="form-control" name="klarifikasi2" id="klarifikasi2" value="<?php echo date('Y-m-d'); ?>"> -->
                                                        <input class="form-input col-md-12" type="date"
                                                            name="klarifikasi2" id="klarifikasi2"
                                                            min="<?php echo date('Y-m-d'); ?>"
                                                            style="padding-left:15px;width: 100%"
                                                            value="<?php echo date('Y-m-d'); ?>"
                                                            required>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="inputEmail3"
                                                    class="col-sm-4 control-label">Negosiasi </label>
                                                <div class="col-sm-8">
                                                    <div
                                                        class="input-group input-medium date date-picker datePicker_">
                                                        <!-- <input type="text" class="form-control" name="negosiasi_date" id="negosiasi_date" value="<?php echo date('Y-m-d'); ?>"> -->
                                                        <input class="form-input col-md-12" type="date"
                                                            name="negosiasi_date" id="negosiasi_date"
                                                            min="<?php echo date('Y-m-d'); ?>"
                                                            style="padding-left:15px;width: 100%"
                                                            value="<?php echo date('Y-m-d'); ?>"
                                                            required>
                                                    </div>
                                                </div>
                                            </div>


                                        </div>
                                        <!---- -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="inputEmail3"
                                                    class="col-sm-4 control-label">Penawaran 1</label>
                                                <div class="col-sm-8">
                                                    <div
                                                        class="input-group input-medium date date-picker datePicker_">
                                                        <!-- <input type="text" class="form-control" name="penawaran1" id="penawaran1" value="<?php echo date('Y-m-d'); ?>"/> -->
                                                        <input class="form-input col-md-12" type="date"
                                                            name="penawaran1" id="penawaran1"
                                                            min="<?php echo date('Y-m-d'); ?>"
                                                            style="padding-left:15px;width: 100%"
                                                            value="<?php echo date('Y-m-d'); ?>"
                                                            required>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="inputEmail3"
                                                    class="col-sm-4 control-label">Penawaran 2</label>
                                                <div class="col-sm-8">
                                                    <div
                                                        class="input-group input-medium date date-picker datePicker_">
                                                        <!-- <input type="text" class="form-control" name="penawaran2" id="penawaran2" value="<?php echo date('Y-m-d'); ?>" /> -->
                                                        <input class="form-input col-md-12" type="date"
                                                            name="penawaran2" id="penawaran2"
                                                            min="<?php echo date('Y-m-d'); ?>"
                                                            style="padding-left:15px;width: 100%"
                                                            value="<?php echo date('Y-m-d'); ?>"
                                                            required>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="inputEmail3"
                                                    class="col-sm-4 control-label">Penawaran 3</label>
                                                <div class="col-sm-8">
                                                    <div
                                                        class="input-group input-medium date date-picker datePicker_">
                                                        <!--  <input type="text" class="form-control" name="penawaran3" id="penawaran3" value="<?php echo date('Y-m-d'); ?>"> -->
                                                        <input class="form-input col-md-12" type="date"
                                                            name="penawaran3" id="penawaran3"
                                                            min="<?php echo date('Y-m-d'); ?>"
                                                            style="padding-left:15px;width: 100%"
                                                            value="<?php echo date('Y-m-d'); ?>"
                                                            required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">

                            <div class="box box-primary">
                                <div class="box-header with-border">
                                    <h3 class="box-title">
                                        Rekanan
                                    </h3>

                                    <div class="form-group pull-right">
                                        <select class="form-control" placeholder="Usulan Rekanan"
                                            id="rekanan_id_usulan" name="rekanan_diusulkan">
                                            <option value="0">Pilih Usulan Rekanan</option>
                                            @foreach($all_rekanans as $key =>$value)
                                                <option value="{{ $value->id }}">{{ $value->name }}</option>
                                            @endforeach
                                        </select>
                                        <button type="button" class="btn btn-primary btn-xs" id="btn-add-rekanan"><i class="fa fa-plus"></i> Tambah</button>
                                    </div>
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body">

                                    <div class="col-md-12">

                                        <div class="col-md-6">
                                            Rekomendasi Rekanan Dari PR
                                            <table id="table_rekanan_pr"
                                                class="table table-bordered table-striped dataTable">
                                                <thead style="background-color: greenyellow;">
                                                    <tr>
                                                        <th>Rekanan 1</th>
                                                        <th>Rekanan 2</th>
                                                        <th>Rekanan 3</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if(count($rekanan_pr) > 0)
                                                    @foreach($rekanan_pr as $key =>$v)
                                                    <tr>
                                                        <td class="td_rekomendasi"><input type="hidden"
                                                                name="rekanan_rekomendasi1"
                                                                id="rekanan_rekomendasi1"
                                                                class="rekanan_rekomendasi"
                                                                value="{{ $v->rec1_id }}" />{{ $v->rekanan1 }}
                                                        </td>
                                                        <td class="td_rekomendasi"><input type="hidden"
                                                                name="rekanan_rekomendasi1"
                                                                id="rekanan_rekomendasi1"
                                                                class="rekanan_rekomendasi"
                                                                value="{{ $v->rec2_id }}" />{{ $v->rekanan2 }}
                                                        </td>
                                                        <td class="td_rekomendasi"><input type="hidden"
                                                                name="rekanan_rekomendasi1"
                                                                id="rekanan_rekomendasi1"
                                                                class="rekanan_rekomendasi"
                                                                value="{{ $v->rec3_id }}" />{{ $v->rekanan3 }}
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                    @else
                                                    <tr>
                                                        <td colspan="9" class="text-center"><strong>Data
                                                                Telah Diproses Pada Tahap
                                                                Selanjutnya</strong></td>
                                                    </tr>
                                                    @endif
                                                </tbody>

                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            Usulan Rekanan

                                            <table id="table_rekanan_usulan"
                                                class="table table-bordered table-striped dataTable"
                                                width="800px;">
                                                <thead style="background-color: greenyellow;">
                                                    <tr>
                                                        <th>Rekanan Usulan</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <button type="submit" class="btn btn-primary" id="btn-save" style="margin-bottom: 10px;">
                                                    <i class="fa fa-save"></i> Simpan
                                                </button>
                                            </div>
                                        </div>   
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        <footer class="main-footer">
            <div class="pull-right hidden-xs">
                <b>Version</b> 2.4.0
            </div>
            <strong>Copyright &copy; 2014-2016 <a href="https://adminlte.io">Almsaeed Studio</a>.</strong> All rights
            reserved.
        </footer>

        <div class="control-sidebar-bg"></div>
    </div>

    @include("master/footer_table")
    @include("pluggins.select2_pluggin")
    @include('pluggins.alertify')
    @include('pluggins.datetimepicker_pluggin')
    @include('pluggins.editable_plugin')
    @include('form.general_form')
    @include('form.datatable_helper')
    <script type="text/javascript">
        var gentable = null;
        var table_penjadwalan = null;
        var arr_rekanan = [];
        var datatable_idUI = {
            "sProcessing": "Sedang memproses...",
            "sLengthMenu": "Tampilkan _MENU_ entri",
            "sZeroRecords": "[Data Kosong]",
            "sInfo": "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
            "sInfoEmpty": "Menampilkan 0 sampai 0 dari 0 entri",
            "sInfoFiltered": "(disaring dari _MAX_ entri keseluruhan)",
            "sInfoPostFix": "",
            "sSearch": "Cari: ",
            "sUrl": "",
            "oPaginate": {
                "sFirst": "Pertama",
                "sPrevious": "Sebelumnya",
                "sNext": "Selanjutnya",
                "sLast": "Terakhir"
            }
        }

        var fnTotalPpn = function () {
            var totalppn = 0;
            $('#ListTelahKelompok > tbody > tr').each(function () {
                if ($(this).find('.total_harga').val() !== undefined) {
                    var temptotal = parseFloat($(this).find('.ppn').autoNumeric('get'));
                    //   var percentage_ppn = $(this).find('.ppn').val();
                    //   var ppn_value = parseFloat(percentage_ppn/100)*temptotal;

                    totalppn += temptotal;
                }
            });
            $('#ppn_total').val(totalppn);
            fnSetAutoNumeric($('#ppn_total'));
            fnSetMoney('#ppn_total',totalppn);
        }

        var fnTotaling = function () {
            var totaling = 0;
            $('#ListTelahKelompok > tbody > tr').each(function () {
                if ($(this).find('.total_harga').val() !== undefined) {
                    var temptotal = parseFloat($(this).find('.total_harga').autoNumeric('get'));
                    totaling += temptotal;
                }
            });

            $('#sub_total').val(totaling);
            fnSetAutoNumeric($('#sub_total'));
            fnSetMoney($('#sub_total'), totaling);

            //   return $('#sub_total').val(totaling);
            // $('#sub_total').text('').text(totaling);
            // fnSetMoney('#sub_total',totaling);
        }

        var fnGrandTotal = function () {
            var sub_total = $('#sub_total').autoNumeric('get');
            /*var ttl_diskon = $('#total_diskon').autoNumeric('get');*/
            var ttl_ppn = $('#ppn_total').autoNumeric('get');

            var grand_total = parseFloat(sub_total) + parseFloat(ttl_ppn);

            $('#grand_total').val(grand_total);
            fnSetAutoNumeric($('#grand_total'));
            fnSetMoney($('#grand_total'), grand_total);

            // $('#grand_total').text('').text(grand_total);
            // fnSetMoney('#grand_total', $('#grand_total').text());
        }

        $(document).ready(function () {
            $('.editable_header').editable({
                ajaxOptions: {
                    type: 'post',
                    dataType: 'json'
                },
                success: function (data) {
                    if (data.return == 1) {
                        alertify.success('Berhasil');
                    }
                }
            });

            $('select').select2();
            $('#ListTelahKelompok').DataTable({
                scrollY: "300px",
                searching: false,
                info: false,
                "language": datatable_idUI,
                scrollY: true,
                scrollCollapse: true,
                paging: false,
                "columnDefs": [{
                    "visible": false,
                    "targets": 0
                }],
                "order": [
                    [0, 'asc']
                ],
                "drawCallback": function (settings) {
                    var api = this.api();
                    var rows = api.rows({
                        page: 'current'
                    }).nodes();
                    var last = null;

                    api.column(0, {
                        page: 'current'
                    }).data().each(function (group, i) {
                        if (last !== group) {
                            $(rows).eq(i).before(
                                '<tr class="group" style="background-color: #3FD5C0;""><td colspan="9"><strong>Nomor Pengelompokkan PR = ' +
                                group + '</strong></td></tr>'
                            );

                            last = group;
                        }
                    });
                }
            });

            gentable = $('#table_rekanan_usulan').DataTable({
                scrollY: "300px",
                searching: false,
                info: false,
                "language": datatable_idUI,
                scrollX: true,
                scrollCollapse: true,
                paging: false,
                "columnDefs": [],
                "order": [
                    [0, 'asc']
                ]
            });

            var tbody_rekananUsulan = $('#table_rekanan_pr tbody');

            tbody_rekananUsulan.on('click', '.td_rekomendasi', function () {
                var id_rekanan = $(this).find('.rekanan_rekomendasi').val();
                var btn_delete = "<button class='btn btn-danger btn-xs btn-del pull-right' btn-id='" +
                    id_rekanan + "'><i class='fa fa-trash'></i></button>";
                var txt_rekanan = $(this).text();
                if ($(this).hasClass('danger') == false) {

                    var eRekananUsulan = txt_rekanan;
                    if (arr_rekanan.length > 0) {
                        if (arr_rekanan.includes(id_rekanan) == false) {
                            arr_rekanan.push(id_rekanan);
                            gentable.row.add([txt_rekanan, btn_delete]);
                            $(this).addClass('danger');
                        } else {
                            alertify.error('rekanan sudah ditambahkan');
                        }
                    } else {
                        $(this).addClass('danger');
                        arr_rekanan.push(id_rekanan);
                        gentable.row.add([txt_rekanan, btn_delete]);
                    }

                    gentable.draw();
                } else {

                    if (arr_rekanan.includes(id_rekanan) == true) {

                        console.log('hapus');
                        var index = arr_rekanan.indexOf(id_rekanan);
                        arr_rekanan.splice(index, 1);
                        $(this).removeClass('danger');

                        $(gentable.data()).each(function (i, v) {

                            if (txt_rekanan == v[0]) {
                                gentable.row(i).remove();
                                gentable.draw();
                            }
                        });

                        alertify.success('berhasil di hapus');
                    }
                }

                $('#all_data_send').val(JSON.stringify(arr_rekanan));
            });

            $('#btn-add-rekanan').click(function () {
                var id_rekanan_usulan = $('#rekanan_id_usulan').val();
                var txt_rekanan_usuluan = $('#rekanan_id_usulan option:selected').text();
                var btn_delete = "<button class='btn btn-danger btn-xs btn-del pull-right' btn-id='" +
                    id_rekanan_usulan + "'><i class='fa fa-trash'></i></button>";
                if (arr_rekanan.length > 0) {
                    if (arr_rekanan.includes(id_rekanan_usulan) == false) {
                        arr_rekanan.push(id_rekanan_usulan);
                        gentable.row.add([txt_rekanan_usuluan, btn_delete]);
                    } else {
                        alertify.error('rekanan sudah ditambahkan');
                    }
                } else {
                    arr_rekanan.push(id_rekanan_usulan);
                    gentable.row.add([txt_rekanan_usuluan, btn_delete]);
                }

                gentable.draw();

                $('#all_data_send').val(JSON.stringify(arr_rekanan));
            });

            var sbody = $('#table_rekanan_usulan tbody');

            sbody.on('click', '.btn-del', function () {
                var tr = $(this).parents('tr');

                var rekanan_del = tr.find('td').eq(0).text();
                var id_del = $(this).attr('btn-id');
                alertify.confirm('Konfirmasi', 'Anda yakin untuk menghapus ?', function () {
                    //tr.remove().toggle();
                    gentable.row(tr).remove();
                    gentable.draw();
                    if (arr_rekanan.includes(id_del) == true) {
                        var index = arr_rekanan.indexOf(id_del);
                        arr_rekanan.splice(index, 1);
                        $('#table_rekanan_pr > tbody > tr > td').each(function () {
                            var rekanan_found = $(this).text();
                            if (rekanan_found == rekanan_del && $(this).attr('class') ==
                                'td_rekomendasi danger') {

                                $(this).removeClass('danger');
                            }
                        });
                    }
                    alertify.success('Berhasil Dihapus');
                    $('#all_data_send').val(JSON.stringify(arr_rekanan));

                }, function () {
                    alertify.error('Cancel')
                });
            });

            table_penjadwalan = $('#jadwal_tender').DataTable({
                scrollY: "300px",
                searching: false,
                info: false,
                "language": datatable_idUI,
                scrollX: true,
                scrollCollapse: true,
                paging: false,
                "columnDefs": [],
                "order": [
                    [0, 'asc']
                ]
            });

            var no_klrifikasi = 0;
            $('#btn-add-date').click(function () {
                no_klrifikasi++;
                var strHtml = '<input type="text" name="penawaran" id="penawaran" />';
                table_penjadwalan.row.add([no_klrifikasi,
                    '<div class="input-group"><input type="text" name="penawaran" id="penawaran" /><div class="input-group-addon"><i class="fa fa-calendar"></i></div></div>',
                    '<div class="input-group"><input type="text" name="penawaran" id="penawaran" /><div class="input-group-addon"><i class="fa fa-calendar"></i></div></div>',
                    '<button class="btn btn-danger btn-xs pull-right"><i class="fa fa-trash"></i></button>'
                ]).draw();
            });

            var tbody = $('#ListTelahKelompok');
            tbody.find('.harga_satuan').each(function (i, v) {
                fnSetAutoNumeric($(this));
                fnSetMoney($(this), $(this).val());
            });

            tbody.find('.total_harga').each(function (i, v) {
                fnSetAutoNumeric($(this));
                fnSetMoney($(this), $(this).val());
            });

            tbody.find('.ppn').each(function (i, v) {
                fnSetAutoNumeric($(this));
                fnSetMoney($(this), $(this).val());
            });

            var tbody = $('#ListTelahKelompok');
            tbody.on('click', 'input', function () {
                $(this).select();
            }).on('keyup', '.harga_satuan', function () {
                // console.log($(this).autoNumeric('get'));
                var tParent = $(this).parents('tr');
                var nilai_harga = $(this).autoNumeric('get');
                var qty = tParent.find(".quantity").val();
                var ppn = tParent.find(".ppn").attr('data-ppn');
                // console.log(ppn);
                var total = parseFloat(qty * nilai_harga);
                tParent.find('.total_harga').val(total);
                fnSetAutoNumeric(tParent.find('.total_harga'));
                fnSetMoney(tParent.find('.total_harga'), total);

                tParent.find('.ppn').val(total * ppn);
                fnSetAutoNumeric(tParent.find('.ppn'));
                fnSetMoney(tParent.find('.ppn'), total * ppn);
                // console.log(tParent.find('.ppn').val());
                // console.log(tParent.find('.total_harga'));
                fnTotaling(tParent.find('.total_harga'));
                fnTotalPpn(tParent.find('.ppn'));
                fnGrandTotal();
            })

        });
    </script>
</body>

</html>