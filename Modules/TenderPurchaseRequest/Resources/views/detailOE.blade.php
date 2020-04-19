<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                <h1 style="text-align:center">Harga Owner Estimate - PR</h1>

            </section>
            <section class="content-header">
                <div class="" style="float: none">
                    <button class="col-lg-1 col-md-2 col-sm-2 btn btn-primary" onclick="location.href='{{ url('/')}}/tenderpurchaserequest/indexOE'" style="float: none; border-radius: 20px; padding-left: 0">
                        <i class="fa fa-fw fa-arrow-left"></i>&nbsp;&nbsp;Back
                    </button>
                    <button class="col-lg-1 col-md-2 col-sm-2 btn btn-primary" onclick="window.location.reload()" style="float: right; border-radius: 20px; padding-left: 0;">
                        <i class="fa fa-fw fa-refresh"></i>&nbsp;&nbsp;Refresh
                    </button>
                </div>
            </section>
            <section class="content">
                <!-- Info boxes -->
                <div class="row">
                    <div class="col-md-12">

                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <div>
                                    <h3 class="box-title">
                                        <!-- <strong>Status : {{ $tender_purchase_request_group_rekanans->approval->status->description }}</strong> -->
                                        @if($tender_purchase_request_group_rekanans->approval->status->description == "approved")
                                        <li class="list-group-item">Status : <strong style="color:green;">{{ strtoupper($tender_purchase_request_group_rekanans->approval->status->description) }}</strong></li>
                                        @elseif($tender_purchase_request_group_rekanans->approval->status->description == "delivered")
                                        <li class="list-group-item">Status : <strong style="color:orange;">{{ strtoupper($tender_purchase_request_group_rekanans->approval->status->description) }}</strong></li>
                                        @elseif($tender_purchase_request_group_rekanans->approval->status->description == "partial approved")
                                        <li class="list-group-item">Status : <strong style="color:#40E0D0;">{{ strtoupper($tender_purchase_request_group_rekanans->approval->status->description) }}</strong></li>
                                        @elseif($tender_purchase_request_group_rekanans->approval->status->description == "open")
                                        <li class="list-group-item">Status : <strong style="color:black;">{{ strtoupper($tender_purchase_request_group_rekanans->approval->status->description) }}</strong></li>
                                        @elseif($tender_purchase_request_group_rekanans->approval->status->description == "rejected")
                                        <li class="list-group-item">Status : <strong style="color:red;">{{ strtoupper($tender_purchase_request_group_rekanans->approval->status->description) }}</strong></li>
                                        @endif
                                    </h3>

                                    <h3 class="box-title">
                                        <li class="list-group-item">Tanggal Barang Dibutuhkan : <strong>{{date ("d-m-y" ,  strtotime($uraian_OE->MIN('butuh_date')))}}</strong></li>
                                    </h3>

                                    <div class="box-tools pull-right">
                                        @if($tender_purchase_request_group_rekanans->approval->approval_action_id == 1)
                                            <button type="button" class="btn btn-block btn-primary" onclick="location.href='{{ url('/')}}/tenderpurchaserequest/request_approveOE?id={{ $tender_purchase_request_group_rekanans->id }}'">
                                            <i class="fa fa-fw fa-send-o"></i> Request Approval </button>
                                        @elseif($tender_purchase_request_group_rekanans->approval->approval_action_id == 7)
                                            <a class="btn btn-block btn-warning" href="{{ url('/')}}/tenderpurchaserequest/add_oe_pr?id={{$tender_purchase_request_group_rekanans->kelompok->no}}" rel="tooltip" data-toggle="tooltip" data-placement="left" title="Tambah OE">
                                            <i class="fa fa-fw fa-plus"></i> Revisi OE  </a>
                                        @endif
                                    </div>
                                </div>
                                @if($tender_purchase_request_group_rekanans->approval->status->id != "7")
                                    @if($revisi != "")
                                        <li class="list-group-item" style="margin-top:5px">
                                            Keterangan : OE ini revisi dari no OE {{$revisi}}
                                        </li>
                                    @else
                                    <li class="list-group-item" style="margin-top:5px">
                                            Keterangan : OE ini Baru dibuat
                                        </li>
                                    @endif
                                @elseif($tender_purchase_request_group_rekanans->approval->status->id == "7")
                                    <li class="list-group-item" style="margin-top:5px">
                                        Deskripsi Reject : {{$deskripsi_reject}} 
                                    </li>
                                @endif
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">
                                <table id="ListTelahKelompok" class="table table-bordered table-striped dataTable" role="grid">
                                    <thead style="background-color: greenyellow;">
                                        <tr>
                                            <th>No Group Tender</th>
                                            <th>Kode Item</th>
                                            <th>Item</th>
                                            <th>Brand</th>
                                            <th>Satuan</th>
                                            <th class="table-align-right">Volume</th>
                                            <th class="table-align-right">Harga (Rp/...)</th>
                                            <th class="table-align-right">Sub Total (Rp.)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $total = 0;
                                        ?>
                                        @foreach($results as $key => $v )
                                        <tr>
                                            <td>{{ $v['tprg_no'] }}</td>
                                            <td>{{ $v['tprg_kode'] }}</td>
                                            <td>{{$v['tprg_itemname']}}</td>
                                            <td>{{$v['tprg_brand']}}</td>
                                            <td>{{$v['tprg_satuan']}}</td>
                                            <td class="table-align-right">{{$v['tprg_totalqty'] }}</td>
                                            <td class="text-right">{{ number_format($v['tprg_price'],2,".",",") }}</td>
                                            <td class="text-right">{{ number_format(($v['tprg_totalqty']*$v['tprg_price']),2,".",",") }}</td>
                                            <?php $total += ($v['tprg_totalqty'] * $v['tprg_price']) ?>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <hr />
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <!-- <th></th><th></th><th></th><th></th><th></th><th></th> -->
                                            <th class="text-right">Total Excl. PPN (Rp.)</th>
                                            <th class="text-right">{{ number_format($total,2,".",",") }}</th>
                                        </tr>
                                    </thead>
                                </table>

                                <a href="{{ url('/tenderpurchaserequest/oe_cetakpdf',$tender_purchase_request_group_rekanans->id) }}" class="btn btn-primary pull-left"><i class="fa fa-print"></i> Cetak Dokumen</a>

                            </div>

                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">

                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title">
                                    Usulan Rekanan Tender
                                </h3>

                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">
                                <div class="form-group pull-right">
                                    <select class="form-control" placeholder="Usulan Rekanan" id="rekanan_id_usulan" name="rekanan_diusulkan">
                                        <option value="0">Pilih Usulan Rekanan</option>
                                        @foreach($all_rekanans as $key =>$value)
                                        <option value="{{ $value->id }}">{{ $value->name }}</option>
                                        @endforeach
                                    </select>
                                    <button class="btn btn-primary btn-xs" id="btn-add-rekanan" data-id="{{$tender_purchase_request_group_rekanans->id}}"><i class="fa fa-plus"></i> Tambah</button>
                                </div>
                                <div class="col-md-12">
                                    <table class="table table-bordered table-striped dataTable" role="grid" id="table_rekanans" style="width: 100%">
                                        <thead style="background-color: greenyellow;">
                                            <tr>
                                                <th>Rekanan</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        @if($tender_purchase_request_group_rekanans->approval->status->id != "1"
                                        )
                                            <tbody>
                                                @foreach($rekanans as $key =>$v)
                                                    <tr>
                                                        <td>{{ $v->rekanan1}}</td>
                                                        <td></td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        @else
                                            <tbody>
                                                @foreach($rekanans as $key =>$v)
                                                <tr>
                                                    <!-- <td>{{ $v->rekanan1 }}</td> -->
                                                    <td>
                                                        <a href="#" class="editable_header" data-pk="{{$v->id_detail}}" data-name="rekanan_id" data-url="{{url('/tenderpurchaserequest/update_rekanan')}}" data-original-title="Pilih Rekanan" data-type="select" data-value="{{$v->id1}}" data-source="{{url('/tenderpurchaserequest/rekanan_source')}}">{{ $v->rekanan1}}</a>
                                                    </td>
                                                    <td>
                                                        <button class='btn btn-danger btn-xs btn-del' data-id_rekanan="{{$v->id1}}" data-id="{{$tender_purchase_request_group_rekanans->id}}"><i class='fa fa-trash'></i></button>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        @endif
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

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
    @include('pluggins.alertify');
    @include('pluggins.editable_plugin')
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-Token': $('meta[name=csrf-token]').attr('content')
            }
        });
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

        var gentable = null;
        $.fn.editable.defaults.mode = 'inline';

        $(document).ready(function() {
            $('select').select2();
            $('#ListTelahKelompok').DataTable({
                scrollY: "300px",
                searching: false,
                info: false,
                //scrollX:true,
                scrollCollapse: true,
                paging: false,
                "columnDefs": [{
                    "visible": false,
                    "targets": 0
                }],
                "order": [
                    [0, 'asc']
                ],
                "drawCallback": function(settings) {
                    var api = this.api();
                    var rows = api.rows({
                        page: 'current'
                    }).nodes();
                    var last = null;

                    api.column(0, {
                        page: 'current'
                    }).data().each(function(group, i) {
                        if (last !== group) {
                            $(rows).eq(i).before(
                                '<tr class="group" style="background-color: #3FD5C0;""><td colspan="10"><strong>' + group + '</strong></td></tr>'
                            );

                            last = group;
                        }
                    });
                }
            });

            gentable = $('#table_rekanans').DataTable({
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

            $('#btn-add-rekanan').click(function() {
                var id_rekanan_usulan = $('#rekanan_id_usulan').val();
                //var id_group_tender = ;
                var txt_rekanan_usuluan = $('#rekanan_id_usulan option:selected').text();
                var id = $(this).attr('data-id');

                var _url = "{{  url('/tenderpurchaserequest/tambah_rekanan_oe') }}";
                var btn_delete = "<button class='btn btn-danger btn-xs btn-del' data-id_rekanan='" + id_rekanan_usulan + "' data-id= '" + id + "'><i class='fa fa-trash'></i></button>";

                alertify.confirm('Konfirmasi', 'Anda yakin untuk menambah ?', function() {

                    var status = false;
                    $.ajax({
                        type: 'POST',
                        dataType: 'json',
                        url: _url,
                        data: {
                            id_rekanan_usulan: id_rekanan_usulan,
                            id: id
                        },
                        beforeSend: function() {
                            waitingDialog.show();
                        },
                        success: function(get) {
                            if (get) {
                                alertify.success('Berhasil di tambahkan');
                                gentable.row.add([txt_rekanan_usuluan, btn_delete]);
                            }
                            gentable.draw();
                            return false;
                        },
                        error: function(xhr, status, message) {},
                        complete: function() {
                            waitingDialog.hide();
                        }
                    });

                }, function() {
                    alertify.error('Cancel')
                });



            });

            $('.editable_header').editable({
                ajaxOptions: {
                    type: 'post',
                    dataType: 'json'
                },
                success: function(data) {
                    if (data.return == 1) {
                        alertify.success('Berhasil');
                    }
                }
            });

            var sbody = $('#table_rekanans tbody');

            sbody.on('click', '.btn-del', function() {
                var tr = $(this).parents('tr');
                var id_rekanan = $(this).attr('data-id_rekanan');
                var id = $(this).attr('data-id');
                var _url_del = "{{ url('/tenderpurchaserequest/delete_rekanan_oe') }}";

                alertify.confirm('Konfirmasi', 'Anda yakin untuk menghapus ?', function() {
                    //tr.remove().toggle();
                    gentable.row(tr).remove();
                    gentable.draw();

                    $.ajax({
                        type: 'POST',
                        dataType: 'json',
                        url: _url_del,
                        data: {
                            id: id,
                            id_rekanan: id_rekanan
                        },
                        beforeSend: function() {
                            waitingDialog.show();
                        },
                        success: function(get) {
                            if (get) {
                                alertify.success('Berhasil di hapus');
                            }
                            return false;
                        },
                        error: function(xhr, status, message) {},
                        complete: function() {
                            waitingDialog.hide();
                        }
                    });

                }, function() {
                    alertify.error('Cancel')
                });

            });

        });
    </script>
</body>

</html>