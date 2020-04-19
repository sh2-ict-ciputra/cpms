<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin QS | Dashboard</title>
    {{-- @include("user.header") --}}
    @include("master/header")
    <style type="text/css">
        .table-align-right {
            text-align: right;
        }
    </style>
</head>

<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">

        @include("user.sidebar")

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->

            <section class="content-header">
                <h1 style="text-align:center">Harga Owner Estimate - PR</h1>
                <input type="hidden" name="oe_id" id="oe_id" value="{{ $tender_purchase_request_group_rekanans->id }}"/>
                <input type="hidden" name="user_id" id="user_id" value="{{ $user->id }}"/>
            </section>
            <section class="content-header">
                <div class="" style="float: none">
                    <button class="col-lg-1 col-md-2 col-sm-2 btn btn-primary" onclick="location.href='{{ url('/')}}'" style="float: none; border-radius: 20px; padding-left: 0">
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
                                {{-- <div class="box-tools pull-right">
                                    @if($tender_purchase_request_group_rekanans->approval->approval_action_id == 2)
                                    <button type="button" class="btn btn-primary" onclick="location.href='{{ url('/')}}/access/tenderpurchaserequest/oe/approve/?id={{ $tender_purchase_request_group_rekanans->id }}'">
                                        <i class="fa fa-fw fa-check"></i> Approve
                                    </button>
                                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#myModalreject">Reject</button>
                                    <!-- Modal -->
                                    <div class="modal fade" id="myModalreject" role="dialog" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <!-- Modal content-->
                                            <div class="modal-content">
                                                <form method="GET" action="{{ url('/')}}/access/tenderpurchaserequest/oe/reject">
                                                    <input type="" name="id" value="{{$tender_purchase_request_group_rekanans->id}}" hidden>
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <h4 class="modal-title">Reject</h4>
                                                    </div>
                                                    <div class="modal-body" style="height: auto">
                                                        <p>Apa anda yakin ingin mereject dokumen ini?</p>
                                                        <div class="form-group">
                                                            <label class="col-md-12" style="padding-left:0">Deskripsi</label>
                                                            <textarea id="deskripsiReject" name="deskripsi_reject" class="form-input col-md-12 item_desk" style="width: 460px;max-width: 460px" required></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default btn-xs" data-dismiss="modal">Close</button>
                                                        <input type="submit" class="btn btn-danger pull-right btn-xs btn-delete" value="Reject">
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div> --}}
                                @if($tender_purchase_request_group_rekanans->approval->status->id != "7")
                                    @if($revisi != "")
                                        <li class="list-group-item" style="margin-top:5px">
                                            <div>
                                                Keterangan : OE ini revisi dari no OE {{$revisi}}
                                            </div>
                                            <div>
                                                Alasan ditolak : {{$deskripsi_reject}}
                                            </div>
                                        </li>
                                    @else
                                        <li class="list-group-item" style="margin-top:5px">
                                            Keterangan : OE ini Baru dibuat
                                        </li>
                                    @endif
                                @endif
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">
                                @if ( $tender_purchase_request_group_rekanans->approval->histories->where("user_id",$user->id)->first()->approval_action_id == "1")
                                <a href="#" class="btn btn-info" onclick="setapproved('6')" data-toggle="modal" data-target="#myModal">Approve</a>
                                <a href="#" class="btn btn-danger" onclick="setapproved('7')" data-toggle="modal" data-target="#myModal">Reject</a>
                                @elseif ( $tender_purchase_request_group_rekanans->approval->histories->where("user_id",$user->id)->first()->approval_action_id == "6")
                                    <span class="badge badge-success" style="font-size: 20px;">Approved</span>
                                @elseif ( $tender_purchase_request_group_rekanans->approval->histories->where("user_id",$user->id)->first()->approval_action_id == "7")
                                    <span class="badge badge-danger" style="font-size: 20px;">Reject</span>
                                @endif
                                <table id="ListTelahKelompok" class="table table-bordered table-striped dataTable" role="grid" style="width:100%;margin-top:7px">
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

                                {{-- <a href="{{ url('/tenderpurchaserequest/oe_cetakpdf',$tender_purchase_request_group_rekanans->id) }}" class="btn btn-primary pull-left"><i class="fa fa-print"></i> Cetak Dokumen</a> --}}
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
                                <div class="col-md-12">
                                    <table class="table table-bordered table-striped dataTable" role="grid" id="table_rekanans" style="width: 100%">
                                        <thead style="background-color: greenyellow;">
                                            <tr>
                                                <th>Rekanan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($rekanans as $key =>$v)
                                            <tr>
                                                <!-- <td>{{ $v->rekanan1 }}</td> -->
                                                <td>
                                                    {{ $v->rekanan1}}
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </section>
            <!-- /.content -->
        </div>
        
        <div class="modal fade" tabindex="-1" role="dialog" id="myModal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><br>
                    </div>
                    <div class="modal-body">
                        <span id="title_approval"><strong></strong></span>
                        <p></p>
                        <div id="listdetail">
                            <textarea name="description2" id="description2" rows="6" style="width:100%"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="btn_save_budgets" data-value="" onclick="requestApproval()">Submit</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

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

        function setapproved(values,budget_id){
            if ( values == "6" ){
                $("#title_approval").attr("style","color:blue");
                $("#title_approval").text("These Purchase Request will be APPROVED by You");
            }else{
                $("#title_approval").attr("style","color:red");
                $("#title_approval").text("These Purchase Request will be REJECTED by You");
            }
            $("#btn_save_budgets").attr("data-value",values);
            $("#budget_id").val(budget_id);
        }

        function requestApproval(){
            if ( $("#btn_save_budgets").attr("data-value") == "7"){
                if ( $("#description2").val() == ""){
                    alert("Silahkan isi dengan pesan terlebih dahulu");
                    return false;
                }
            }
            var request = $.ajax({
                url : "{{ url('/') }}/access/tenderpurchaserequest/oe/approve",
                type :"post",
                dataType :"json",
                data: {
                    user_id : $("#user_id").val(),
                    oe_id :$("#oe_id").val(),
                    status : $("#btn_save_budgets").attr("data-value"),
                    description :  $("#description2").val()
                },
                beforeSend: function() {
                    waitingDialog.show();
                },
                success: function(data) { 
                    if ( data.status == "0"){
                    window.location.reload();
                    }else{
                    alert("Error When Saving Approval");
                    // window.location.reload();
                    }
                },
                complete: function() {
                    waitingDialog.hide(); 
                },
            });
        }
    </script>
</body>

</html>