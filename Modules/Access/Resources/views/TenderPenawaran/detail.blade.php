<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin QS | Dashboard</title>
    <link rel="stylesheet" href="{{ url('/')}}/assets/selectize/selectize.bootstrap3.css">
    {{-- @include("user.header") --}}
    @include("master/header")
    <style type="text/css">
        .table-align-right {
            text-align: right;
        }

        .optionItem {
            width: 98%;
        }

        #penjelasan_reject_vendor {
            background-color: pink;
            padding: 0px;
            display: none;
        }

        #penjelasan_reject_harga {
            background-color: pink;
            padding: 0px;
            display: none;
        }

        #penjelasan_reject_keseluruhan {
            background-color: pink;
            padding: 0px;
            display: none;
        }

        #reject_vendor:hover+#penjelasan_reject_vendor {
            display: block;
            z-index: 0;
        }

        #reject_harga:hover+#penjelasan_reject_harga {
            display: block;
            z-index: 0;
        }

        #reject_keseluruhan:hover+#penjelasan_reject_keseluruhan {
            display: block;
            z-index: 0;
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
                <h1 style="text-align:center">Proyek {{ $project->name }}</h1>
                <input type="hidden" name="usulan_id" id="usulan_id" value="{{ $penawaran_data->id }}"/>
                <input type="hidden" name="user_id" id="user_id" value="{{ $user->id }}"/>
                <input type="hidden" name="tmenang_id" id="tmenang_id" value="{{$checkPemenang->id}}"/>
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
                            <div class="box-header">
                                <h4>Rekapitulasi Harga Penawaran</h4>
                                <div class="box-header with-border" style="background-color:white">
                                    @if($errors->any())
                                    <h4 style="color: blue;">{{$errors->first()}}</h4>
                                    @endif
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>No Tender</th>
                                                <th>{{ $getDataTender->no }}</th>
                                            </tr>
                                            <tr>

                                                <th>Status Tender</th>
                                                <th>
                                                    @if($approval_history->action->description == "approved")
                                                        <strong style="color:green;">{{ strtoupper($approval_history->action->description) }}</strong>
                                                    @elseif($approval_history->action->description == "delivered")
                                                        <strong style="color:orange;">{{ strtoupper($approval_history->action->description) }}</strong>
                                                    @elseif($approval_history->action->description == "open")
                                                        <strong style="color:black;">{{ strtoupper($approval_history->action->description) }}</strong>
                                                    @endif

                                                </th>
                                            </tr>
                                            <!-- <tr>
                                                <th>Description Reject</th>
                                                <th>{{$approval_history->description}}</th>
                                            </tr> -->
                                            @if($checkPemenang != null)
                                            <tr>
                                                @if($checkPemenang->status_usulan)
                                                <th>Usulan Pemenang Tender</th>
                                                <th>{{ $approval_history->document->tender_purchase_request_group_rekanan_detail->rekanan->name}}</th>
                                                @else
                                                <th>Pemenang Tender</th>
                                                <th>{{ $checkPemenang->tender_purchase_request_group_rekanan_detail->rekanan->name }}</th>
                                                @endif
                                            </tr>
                                            <tr>
                                                <th>Alasan</th>
                                                <th>{{ $approval_history->description}}</th>
                                            </tr>
                                            @endif

                                            @if(count($checkStatus) > 0)
                                            @if(in_array(1,$checkStatus))
                                            <tr>
                                                <th>Pemenang Tender</th>
                                                <th style="color:blue;">{{ $checkPemenang->tender_purchase_request_group_rekanan_detail->rekanan->name }}</th>
                                            </tr>
                                            @endif
                                            @endif

                                        </thead>
                                    </table>
                                    {{-- @if($approval_history->action->description!="rejected")
                                    <button type="button" class="btn btn-primary" onclick="location.href='{{ url('/')}}/access/tenderpenawaran/detail/approve/?id={{ $checkPemenang->id }}'">
                                        <i class="fa fa-fw fa-check"></i> Approve
                                    </button>
                                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#myModalreject">Reject</button>
                                    <!-- Modal -->
                                    <div class="modal fade" id="myModalreject" role="dialog" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <!-- Modal content-->
                                            <div class="modal-content">
                                                <form method="GET" action="{{ url('/')}}/access/tenderpenawaran/detail/reject/">
                                                    <input type="" name="id" value="{{$checkPemenang->id}}" hidden>
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <h4 class="modal-title">Reject</h4>
                                                    </div>
                                                    <div class="modal-body" style="height: auto">
                                                        <p>Apa anda yakin ingin mereject dokumen ini?</p>
                                                        <div class="form-group">

                                                            <div class="col-md-4">
                                                                <label class="radio-inline">
                                                                    <input id="reject_vendor" type="radio" name="optradio" value="reject_vendor">Reject Vendor
                                                                    <div id="penjelasan_reject_vendor">
                                                                        <li>Bila vendor ingin diganti</li>
                                                                        <li>Bila vendor tidak cocok</li>
                                                                    </div>
                                                                </label>
                                                            </div>

                                                            <div class="col-md-4">
                                                                <label class="radio-inline">
                                                                    <input id="reject_harga" type="radio" name="optradio" value="reject_harga">Reject Harga
                                                                    <div id="penjelasan_reject_harga">
                                                                        <li>Bila harga tidak cocok</li>
                                                                    </div>
                                                                </label>
                                                            </div>

                                                            <div class="col-md-4">
                                                                <label class="radio-inline">
                                                                    <input id="reject_keseluruhan" type="radio" name="optradio" value="reject_keseluruhan">Reject Keseluruhan
                                                                    <div id="penjelasan_reject_keseluruhan">
                                                                        <li>Bila tender ingin dibatalkan</li>
                                                                        <li>Bila ternyata barang sudah ada</li>
                                                                        <li>Bila terjadi masalah dengan tender</li>
                                                                    </div>
                                                                </label>
                                                            </div>

                                                            <label class="col-md-12" style="padding-left:0;margin-top: 10px">Deskripsi</label>
                                                            <textarea id="deskripsiReject" name="deskripsi_reject" class="form-input col-md-12 item_desk" style="width: 470px;max-width: 470px" rows="3" required></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer" style="height: auto">
                                                        <button type="button" class="btn btn-default btn-xs" data-dismiss="modal">Close</button>
                                                        <input type="submit" class="btn btn-danger pull-right btn-xs btn-delete" value="Reject"></input>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    @endif --}}

                                    @if ( $penawaran_data->approval->histories->where("user_id",$user->id)->first()->approval_action_id == "1")
                                        <a href="#" class="btn btn-info" onclick="setapproved('6')" data-toggle="modal" data-target="#myModal2">Approve</a>
                                        <a href="#" class="btn btn-danger" onclick="setapproved('7')" data-toggle="modal" data-target="#myModal2">Reject</a>
                                    @elseif ( $penawaran_data->approval->histories->where("user_id",$user->id)->first()->approval_action_id == "6")
                                        <span class="badge badge-success" style="font-size: 20px;">Approved</span>
                                    @elseif ( $penawaran_data->approval->histories->where("user_id",$user->id)->first()->approval_action_id == "7")
                                        <span class="badge badge-danger" style="font-size: 20px;">Reject</span>
                                    @endif
                                </div>
                            </div>

                            <div class="box-body" style="overflow-x: scroll;">
                                <?php
                                $arr_temp_rekanan = [];
                                ?>
                                <table class="table table-bordered table-striped dataTable" id="table_comparison">
                                    <thead style="background-color: greenyellow;">
                                        <tr>
                                            <th rowspan="2" style="width: 50%;"><button class="btn btn-default" disabled="true">Barang Penawaran</button></th>
                                            <th rowspan="2">Volume</th>
                                            <th rowspan="2">Satuan</th>
                                            <th colspan="{{ count($data_rekanan) }}" class="text-center">Harga Satuan (Rp/...)</th>
                                            <th colspan="{{ count($data_rekanan) }}" class="text-center">Total Harga(Rp.)</th>
                                            <th colspan="{{ count($data_rekanan) }}" class="text-center">Brand</th>
                                        </tr>
                                        <tr>
                                            <?php
                                            foreach ($join_data_rekanan as $key => $value) {
                                                $split_value = explode("-", $value);
                                                if ($key < (count($join_data_rekanan) / 3)) {
                                                    print "<th style='background-color: #0fdee8;'>" . $split_value[0] . "</th>";
                                                } else if ($key == (count($join_data_rekanan) / 3)) {
                                                    print "<th class='sum' data-ppn='" . $split_value[2] . "' style='background-color: #d578ed;'>" . $split_value[0] . "</th>";
                                                } else if ($key >= ((count($join_data_rekanan) / 3) * 2)) {
                                                    print "<th class='sg' style='background-color: #6d77ea;'>" . $split_value[0] . "</th>";
                                                } else {
                                                    print "<th class='sum' data-ppn='" . $split_value[2] . "'' style='background-color: #d578ed;'><input type='hidden' name='rekanan_id' id='rekanan_id' value='" . $split_value[1] . "' /><button class='btn btn-default klik_rekanan' type='button'>" . $split_value[0] . "</button></th>";
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
                                            print "<td>" . $value['item_name'] . "</td>";
                                            print "<td class='text-right'>" . $value['volume'] . "</td>";
                                            print "<td>" . $value['satuan_name'] . "</td>";
                                            foreach ($value['satuan_price'] as $k => $v) {
                                                print "<td class='text-right money'>" . $v . "</td>";
                                            }
                                            print "</tr>";
                                        }
                                        ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="{{ (count($join_data_rekanan)/3)+3 }}" class="text-right">Sub Total</th>
                                            @for($i=0; $i < count($join_data_rekanan)/3;$i++) <th class="text-right sub_total money">
                                                </th>
                                                @endfor
                                                <th colspan="{{ (count($join_data_rekanan)/3) }}"></th>
                                        </tr>
                                        <tr>
                                            <th colspan="{{ (count($join_data_rekanan)/3)+3 }}" class="text-right">PPN (Rp.)</th>
                                            @for($i=0; $i < count($join_data_rekanan)/3;$i++) <th class="text-right ppn_value money">
                                                </th>
                                                @endfor
                                                <th colspan="{{ (count($join_data_rekanan)/3) }}"></th>
                                        </tr>

                                        <tr>
                                            <th colspan="{{ (count($join_data_rekanan)/3)+3 }}" class="text-right">Grand Total</th>
                                            @for($i=0; $i < count($join_data_rekanan)/3;$i++) <th class="text-right grand_total money">
                                                </th>
                                                @endfor
                                                <th colspan="{{ (count($join_data_rekanan)/3) }}"></th>
                                        </tr>

                                        <tr>
                                            <th colspan="{{ (count($join_data_rekanan)/3)+3 }}" class="text-right">Metode Pembayaran</th>
                                            <th></th>
                                            @for($i=0; $i < count($join_data_rekanan)/3-1;$i++) <th class="text-right">
                                                {{ strtoupper($tenderPembayaran[$i]->name_pembayaran)}}
                                                </th>
                                                @endfor
                                                <th colspan="{{ (count($join_data_rekanan)/3) }}"></th>
                                        </tr>

                                        <tr>
                                            <th colspan="{{ (count($join_data_rekanan)/3)+3 }}" class="text-right">DP</th>
                                            <th></th>
                                            @for($i=0; $i < count($join_data_rekanan)/3-1;$i++) <th class="text-right">
                                                {{$tenderPembayaran[$i]->DP}}%
                                                </th>
                                                @endfor
                                                <th colspan="{{ (count($join_data_rekanan)/3) }}"></th>
                                        </tr>

                                        @for($k=0; $k < $tenderPembayaran->max("lama_cicilan") ;$k++)
                                            <tr>
                                                <th colspan="{{ (count($join_data_rekanan)/3)+3 }}" class="text-right">TERMIN / COD {{$k+1}}</th>
                                                <th></th>
                                                @for($i=0; $i < count($join_data_rekanan)/3-1;$i++) @if( $tenderPembayaran[$i]->name_pembayaran == 'termin')
                                                    <?php
                                                    $termin_pembayaran = DB::table('tender_purchase_request_penawaran_pembayaran_termin')
                                                        ->where('tender_purchase_request_penawaran_id', $tenderPembayaran[$i]->id_penawaran)
                                                        ->where('cicilan_ke', $k + 1)
                                                        ->first();
                                                    ?>
                                                    @if($termin_pembayaran != null)
                                                    <th class="text-right">
                                                        {{$termin_pembayaran->percentage}}%
                                                    </th>
                                                    @else
                                                    <th>
                                                    </th>
                                                    @endif
                                                    @elseif($tenderPembayaran[$i]->name_pembayaran == 'cod')
                                                    <?php
                                                    $cod_pembayaran = DB::table('tender_purchase_request_penawaran_pembayaran_cod')->join('items', 'items.id', 'tender_purchase_request_penawaran_pembayaran_cod.item_id')->join('item_satuans', 'item_satuans.id', 'tender_purchase_request_penawaran_pembayaran_cod.item_satuan_id')->where('tender_purchase_request_penawaran_id', $tenderPembayaran[$i]->id_penawaran)->where('cod_ke', $k + 1)->select('items.name as item_name', 'tender_purchase_request_penawaran_pembayaran_cod.quantity as quantity', 'item_satuans.name as satuan')->get();
                                                    ?>
                                                    @if($cod_pembayaran != null)
                                                    <th class="text-right">
                                                        @foreach($cod_pembayaran as $key => $value)
                                                        <div>{{$value->item_name}} | {{$value->quantity}} {{$value->satuan}}</div>
                                                        @endforeach
                                                    </th>
                                                    @else
                                                    <th>
                                                    </th>
                                                    @endif
                                                    @endif
                                                    <!-- <th class="text-right">
                                        {{$tenderPembayaran[$i]->DP}}%
                                    </th> -->
                                                    @endfor
                                                    <th colspan="{{ (count($join_data_rekanan)/3) }}"></th>
                                            </tr>
                                            @endfor
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.row -->

                <div class="row">
                    <div class="col-md-12">
                        <div class="box box-primary">
                            <div class="box-header with-border" style="height: 50px">
                                <div>
                                    <h4>Data Supplier</h4>
                                </div>
                            </div>
                            <div class="box-body">
                                <div>
                                    <table class="table table-bordered dataTable" id="table_pembayaran" style="margin-bottom: 30px">
                                        <thead style="background-color: gray;">
                                            <tr>
                                                <th rowspan="2">No</th>
                                                <th rowspan="2">Name Supplier</th>
                                                <th rowspan="2">Alamat</th>
                                                <th rowspan="2">No. Telp</th>
                                                <th rowspan="2">Cp. Name</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($getDataPenawarans as $key => $v)
                                            <tr>
                                                <th>{{$v->tender_purchase_request_group_rekanan_detail->rekanan->id}}</th>
                                                <th>{{$v->tender_purchase_request_group_rekanan_detail->rekanan->name}}</th>
                                                <th>{{$v->tender_purchase_request_group_rekanan_detail->rekanan->surat_alamat}}</th>
                                                <th>{{$v->tender_purchase_request_group_rekanan_detail->rekanan->telp}}</th>
                                                <th>{{$v->tender_purchase_request_group_rekanan_detail->rekanan->cp_name}}</th>
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
        <!-- /.content-wrapper -->
        <footer class="main-footer">
            <div class="pull-right hidden-xs">
                <b>Version</b> 2.4.0
            </div>
            <strong>Copyright &copy; 2014-2016 <a href="https://adminlte.io">Almsaeed Studio</a>.</strong> All rights
            reserved.
        </footer>


        <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
        <div class="control-sidebar-bg"></div>
    </div>
    <!-- ./wrapper -->

    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Modal title</h4>
                </div>
                <div class="modal-body">
                    <form>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="myModal2">
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

    @include("master/footer_table")
    @include('pluggins.alertify')
    @include('form.general_form')
    <script type="text/javascript" src="https://cdn.datatables.net/fixedcolumns/3.2.6/js/dataTables.fixedColumns.min.js"></script>
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-Token': $('meta[name=csrf-token]').attr('content')
            }
        });

        fnCliCkRekanan = function() {
            $(this).removeClass('btn-default').addClass('btn-primary');
        }
        var gentable = null;
        $(document).ready(function() {

            gentable = $('#table_comparison').DataTable({
                /*scrollY: "400px",
                scrollX:true,
                scrollCollapse: true,*/
                info: false,
                paging: false,
                searching: false,
                ordering: false,
                // fixedColumns: {leftColumns: 4},
                "footerCallback": function(row, data, start, end, display) {
                    var api = this.api();
                    api.columns('.sum', {
                        page: 'current'
                    }).every(function() {
                        var sum = api
                            .cells(null, this.index(), {
                                page: 'current'
                            })
                            .render('display')
                            .reduce(function(a, b) {
                                var x = parseFloat(a) || 0;
                                var y = parseFloat(b) || 0;
                                return x + y;
                            }, 0);

                        $(this.footer()).html(sum);
                        fnSetAutoNumeric($(this.footer()));
                        fnSetMoney($(this.footer()), sum);
                    });
                },
                "initComplete": function(settings, json) {


                    $('.sub_total').each(function(i, v) {
                        var nilai = parseFloat($(this).autoNumeric('get'));
                        var ppn_value = $('.sum').eq(i).attr('data-ppn');
                        if (ppn_value == undefined) {
                            ppn_value = 0;
                        }
                        ppn_value = parseFloat(ppn_value / 100 * nilai);
                        $('.ppn_value').eq(i).text(ppn_value);
                        var grand_total = parseFloat(ppn_value + nilai);
                        $('.grand_total').eq(i).text(grand_total);
                    });

                    fnSetAutoNumeric('.money');
                    fnSetMoney('.money', $('.money').text());
                }
            });

            /*$('#btn_tunjuk_pemenang').click(function()
            {
                var obj = $(this);
                var _idtender = $('#id_tender').val();
                var _idrekanan = parseInt($(this).attr('data-value'));
                var _data = { idtender:_idtender,idrekanan:_idrekanan};
                var _url = "{{ url('/tenderpurchaserequest/tunjuk_pemenang') }}";

                $.ajax({
                  type:'post',
                  url:_url,
                  data:_data,
                  dataType:'json',
                  beforeSend:function()
                  {
                      waitingDialog.show();
                  },
                  success:function(data)
                  {
                      if(data)
                      {
                          alertify.success('Berhasil');
                          obj.remove();
                      }
                  },
                  complete:function()
                  {
                      waitingDialog.hide();
                  }
                });
            });*/

            $('th:has(button)').click(function() {
                var trParent = $(this).parents('tr');
                trParent.find('button').removeClass('btn-primary').addClass('btn-default');
                $(this).find('button').removeClass('btn-default').addClass('btn-primary');
                var id_rekanan = parseInt($(this).find('input').val());
                $('#rekananid').val(id_rekanan);
                $('#btn_tunjuk_pemenang').removeAttr('disabled').removeClass('btn-default').addClass('btn-primary');
            });

            $('th:has(button)').dblclick(function() {
                $(this).find('button').removeClass('btn-primary').addClass('btn-default');
                $('#btn_tunjuk_pemenang').removeAttr('data-value').prop('disabled', true).removeClass('btn-primary').addClass('btn-default');
            });

            /*$('#btn_tunjuk_pemenang').click(function()
       {
          var obj = $(this);
          var _idrekanan = parseInt($(this).attr('data-value'));
          var _data = { idtender:parseInt($('#id_tender').val()),idrekanan:_idrekanan };
          var _url = "{{ url('/tenderpurchaserequest/request_approval_penawaran') }}";

          $.ajax({
              type:'post',
              url:_url,
              data:_data,
              dataType:'json',
              beforeSend:function()
              {
                  waitingDialog.show();
              },
              success:function(data)
              {
                  if(data)
                  {
                      alertify.success('Berhasil');
                      obj.remove();
                  }
              },
              complete:function()
              {
                  waitingDialog.hide();
              }
            });
       });*/
            $('#btn-nexttawar').click(function() {
                var obj = $(this);
                var _data = {
                    id: parseInt($(this).attr('data-value'))
                };
                var _url = "{{ url('/tenderpurchaserequest/lanjut_tawar') }}";

                $.ajax({
                    type: 'post',
                    url: _url,
                    data: _data,
                    dataType: 'json',
                    beforeSend: function() {
                        waitingDialog.show();
                    },
                    success: function(data) {
                        if (data) {
                            alertify.success('Berhasil');
                            obj.remove();

                        }

                    },
                    complete: function() {
                        waitingDialog.hide();
                    }
                });
            });


            $('#btn_approve').click(function() {
                alertify.confirm('Konfirmasi', 'Anda Yakin?', function() {
                    alertify.success('Ok')
                }, function() {
                    alertify.error('Batal')
                });
            });



        });
        function setapproved(values,budget_id){
            if ( values == "6" ){
                $("#title_approval").attr("style","color:blue");
                $("#title_approval").text("Usulan Pemenang Tender PR ini akan di APPROVED oleh anda");
            }else{
                $("#title_approval").attr("style","color:red");
                $("#title_approval").text("Usulan Pemenang Tender PR ini akan di REJECTED oleh anda");
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
                url : "{{ url('/') }}/access/tenderpenawaran/detail/approve",
                type :"post",
                dataType :"json",
                data: {
                    user_id : $("#user_id").val(),
                    usulan_id :$("#usulan_id").val(),
                    tmenang_id :$("#tmenang_id").val(),
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