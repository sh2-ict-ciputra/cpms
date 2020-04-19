
<!DOCTYPE html>
<html>
@include('master.header')
<style type="text/css">
    #example3 th,
    #example3 td {
        white-space: nowrap;
    }
    .card {
        position: relative;
        display: -ms-flexbox;
        display: flex;
        -ms-flex-direction: column;
        flex-direction: column;
        min-width: 0;
        word-wrap: break-word;
        background-color: #fff;
        background-clip: border-box;
        border: 0 solid rgba(0,0,0,.125);
        border-radius: .25rem;
    }
    .card-warning.card-outline {
    border-top: 3px solid #ffc107;
    }

    .card {
        box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
        margin-bottom: 1rem;
    }

    .card-header {
        background-color: transparent;
        border-bottom: 1px solid rgba(0,0,0,.125);
        padding: .75rem 1.25rem;
        position: relative;
        border-top-left-radius: .25rem;
        border-top-right-radius: .25rem;
    }
    .card-body {
        -ms-flex: 1 1 auto;
        flex: 1 1 auto;
        padding: 1.25rem;
    }
    .card-title {
        float: left;
        font-size: 1.1rem;
        font-weight: 400;
        margin: 0;
    }
    .card-header>.card-tools {
        float: right;
        margin-right: -.625rem;
    }

</style>
<link rel="stylesheet" href="{{ url('/') }}/assets/users/plugins/datatables/dataTables.bootstrap4.min.css">
{{ csrf_field() }}
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

    <!-- /.navbar -->
@include('user.sidebar')
<!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <h1 style="text-align:center">Project</h1>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <input type="hidden" name="approval_list" id="approval_list">
        <section class="content" style="height:100%">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">Data Approval</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="box-body">
                            <div class="form-group row col-md-12" style="margin:5px 5px 5px 5px">
                                <div class="form-group col-md-1" style="margin-bottom: 0px;">   
                                    <label >Project</label>
                                </div>
                                <div class="form-group col-md-3">  
                                    <select class="form-control" name="list_project" id="list_project">
                                        @for( $i=0 ; $i < count($data_project) ; $i++)
                                            <option value="{{ $data_project[$i]['id']}}">{{ $data_project[$i]["name"]}}</option>
                                        @endfor
                                    </select>
                                </div>

                                <div class="form-group col-md-1" style="margin-bottom: 0px;">   
                                    <label >Jenis Dokumen</label>
                                </div>
                                <div class="form-group col-md-3">  
                                    <select class="form-control" name="list_jenis_dokumen" id="list_jenis_dokumen">
                                        <option value="Rab">Rab</option>
                                        <option value="Tender Rekanan">Tender Rekanan</option>
                                        <option value="Vo">Vo</option>
                                        <option value="Percepatan SPK">Percepatan SPK</option>
                                        <option value="Usulan Pemenang Tender">Usulan Pemenang Tender</option>
                                        <option value="Perpanjangan Spk">Perpanjangan Spk</option>
                                    </select>
                                </div>
                            </div>

                            <table class="table table-bordered col-md-12" id="table_approval" style="width:100%">
                                <thead>
                                    <tr class="head_table">
                                        {{-- <th style="width:15%">Proyek</th> --}}
                                        <th style="width:10%">Jenis Dokumen</th>
                                        <th class="col-md-3" style="width:35%">Perihal Pekerjaan</th>
                                        <th style="width:15%">Tanggal</th>
                                        <th style="width:10%">Detail</th>
                                    </tr>
                                </thead>
                                <tbody style="background-color: white;">
                                    @foreach ( $approval as $key => $value )
                                        @if ($value->approval != null)
                                            {{-- @if ($value->approval->approval_action_id == 6 || $value->approval->approval_action_id == 7 || $value->approval->approval_action_id == 8) --}}
                                                @php
                                                    $bool = 1;
                                                @endphp
                                                @if ($value->document_type == "Modules\Tender\Entities\Tender")
                                                    @if ($value->document != null)
                                                        @if ($value->document->rekanans[0]->approval->approval_action_id == 1)
                                                            @php
                                                                $bool = 0;
                                                            @endphp
                                                        @endif
                                                    @endif
                                                @endif
                                                @if ($bool == 1)
                                                    @if ( $value->document_type != "Modules\Tender\Entities\TenderRekanan" && $value->document_type != "Modules\Tender\Entities\TenderMenang"  && $value->document_type != "Modules\Budget\Entities\BudgetDetail" && $value->document_type != "Modules\BudgetDraft\Entities\BudgetDraft" && $value->document_type != "Modules\PurchaseRequest\Entities\PurchaseRequestDetail" && $value->document_type != "Modules\PenerimaanBarangPO\Entities\PenerimaanBarangPODetail")
                                                        @php
                                                            $arrayDocument = array(
                                                            "Modules\Budget\Entities\Budget" => array("label" => "Budget Awal", "url" => "budget" ),
                                                            "Modules\Budget\Entities\BudgetTahunan" => array("label" => "Budget Tahunan", "url" => "budget_tahunan" ),
                                                            "Modules\Workorder\Entities\Workorder" => array("label" => "Workorder", "url" => "workorder" ),
                                                            "Modules\Tender\Entities\Tender" => array("label" => "Tender Rekanan", "url" => "tender" ),
                                                            "Modules\Tender\Entities\TenderKorespondensi" => array("label" => "Korespondensi" , "url" => "tender_korespondensi"),
                                                            "Modules\Tender\Entities\TenderRekanan" => array("label" => "Rekanan" , "url" => "tender_rekanan"),
                                                            "Modules\Spk\Entities\Spk" => array("label" => "Surat Perintah Kerja" , "url" => "spk"),
                                                            "Modules\BudgetDraft\Entities\BudgetDraft" => array("label" => "BudgetDraft" , "url" => "budgetdraft"),
                                                            "Modules\PurchaseRequest\Entities\PurchaseRequest" => array("label" => "PR", "url" => "purchaserequest"),
                                                            "Modules\TenderPurchaseRequest\Entities\TenderPurchaseRequestGroupRekanan" => array("label" => "OE", "url" => "tenderpurchaserequest/oe"),
                                                            "Modules\TenderPurchaseRequest\Entities\PurchaseOrder" => array("label" => "PO", "url" => "purchaseorder"),
                                                            "Modules\PenerimaanBarangPO\Entities\PenerimaanBarangPO" => array("label" => "Penerimaan Barang PO", "url" => "penerimaanbarangPO"),
                                                            "Modules\TenderPurchaseRequest\Entities\TenderPurchaseRequestPenawaran" => array("label" => "Tender Penawaran", "url" => "tenderpenawaran"),
                                                            "Modules\Spk\Entities\NewVo" => array("label" => "Vo", "url" => "vo"),"Modules\Rekanan\Entities\PerpanjanganSpk" => array("label" => "Perpanjangan Spk", "url" => "PerpanjanganSpk"),
                                                            "Modules\Spk\Entities\SpkPercepatan" => array("label" => "Percepatan SPK", "url" => "SpkPercepatan"),
                                                            "Modules\Rab\Entities\Rab" => array("label" => "Rab", "url" => "rab"),
                                                            "Modules\Tender\Entities\TunjukPemenangTender" => array("label" => "Usulan Pemenang Tender", "url" => "usulanPemenang" ),
                                                            );
                                
                                                            $arrayKoresponend = array(
                                                            "udg" => "Undangan Penawaran dan Klarifikasi",
                                                            "sipp" => "Surat Instruksi Penunjukan Pemenang",
                                                            "pp" => "Surat Pemberitahuan Pemenang",
                                                            "sutk" => "Surat Ucapan Terima Kasih",
                                                            "spt" => "Surat Pembatalan Tender"
                                                            );
                                                        @endphp
                                                        @if (isset($value->document->nilai))
                                                            @if ( $value->document->project != "")
                                                                <tr class="baris">
                                                                    {{-- <td>{{ $value->document->project->name or '' }}</td> --}}
                                                                    <td>
                                                                        {{ $arrayDocument[$value->document_type]['label'] }}
                                                                        <input type="hidden" value="{{$value->document->project->id or '' }}" class="name_project">
                                                                        <input type="hidden" value="{{ $arrayDocument[$value->document_type]['label'] }}" class="name_dokumen">
                                                                    </td>
                                                                    @if ( $value->document_type != "App\TenderKorespondensi" )
                                                                        @if($value->document_type == "Modules\TenderPurchaseRequest\Entities\TenderPurchaseRequestPenawaran")
                                                                            <td>{{ $value->document->name or '' }}</td>
                                                                        @else
                                                                            <td>{{ $value->document->name or '' }}</td>
                                                                        @endif
                                                                    @else
                                                                        <td>{{ $arrayKoresponend[$value->document->type] }}</td>
                                                                        <td></td>
                                                                    @endif
                                                                    <td>
                                                                        {{ $value->created_at->format("d M Y") }}
                                                                        @if ($value->document_type == "Modules\Tender\Entities\Tender")
                                                                            @php
                                                                                $i = 0;
                                                                                $j = 0;
                                                                            @endphp
                                                                            {{$value->document->tender_rekanan}}
                                                                            {{-- @foreach ($value->document->rekanan_tender as $item => $barang)
                                                                                @if ($barang->approval->histories->approval_action_id == 6)
                                                                                    @php
                                                                                        $i += 1;
                                                                                    @endphp
                                                                                @elseif($barang->approval->histories->approval_action_id == 7)
                                                                                    @php
                                                                                        $j += 1;
                                                                                    @endphp
                                                                                @endif
                                                                            @endforeach
                                                                            <span class="label label-success">Approved{{$i}}</span>
                                                                            <span class="label label-danger">Reject{{$j}}</span> --}}
                                                                        @else
                                                                            @if ($value->approval->approval_action_id == 6)
                                                                                <span class="label label-success">Approved</span>
                                                                            @elseif($value->approval->approval_action_id == 7)
                                                                                <span class="label label-danger">Reject</span>
                                                                            @else
                                                                                <span class="label label-warning">Tidak di Proses</span>
                                                                            @endif
                                                                        @endif
                                                                    </td>
                                                                    <td><a href="{{ url('/')}}/access/{{ $arrayDocument[$value->document_type]['url'] }}/detail/?id={{ $value->document->id }}" class="btn btn-success">Detail</a></td>
                                                                </tr>
                                                            @endif
                                                        @endif
                                                    @endif
                                                @endif
                                            {{-- @endif --}}
                                        @endif
                                    @endforeach
                                </tbody>
                            </table><br>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <footer class="main-footer">
        <div class="float-right d-none d-sm-block">
        </div>
    </footer>

</div>
<!-- ./wrapper -->
@include('user.footer')
<script type="text/javascript" src="{{ url('/')}}/assets/bower_components/datatables.net-bs/fixed-columns/js/dataTables.fixedColumns.min.js"></script>
<link rel="stylesheet" type="text/css" href="{{ url('/')}}/assets/bower_components/datatables.net-bs/fixed-columns/css/fixedColumns.bootstrap.min.css">
@include("master/footer_table")
<script type="text/javascript">

    $(document).ready(function() {

        $('#table_approval').DataTable( {
            scrollY:        500,
            scrollX:        true,
            // autoWidth : false,
            // "autoWidth": false,
            // scrollCollapse: true,
            paging:         false,
            "columnDefs": [
                // { "visible": false, "targets": [0] }
            ],
            order: [[2, "asc"]],
        //     "drawCallback": function ( settings ) {
        //         var api = this.api();
        //         var rows = api.rows( {page:'current'} ).nodes();
        //         var last=null;
        //         var lastGroup = null;
        //         var lastSub = null;  
        //         var mySubGroup = null;
        //         api.column(0, {page:'current'} ).data().each( function ( group, i ) {
        //             // console.log(rows);
        //             if ( last !== group ) {
        //                 if(group != ""){
        //                     $(rows).eq(i).before(
        //                         '<tr show="0" class="group" style="background-color: white;""><td colspan=4"><strong>'+group+'</strong></td></tr>'
        //                     );
        //                 }else{
        //                     $(rows).eq(i).before(
        //                         '<tr show="0" class="group" style="background-color: white;""><td colspan=4"><strong> -- </strong></td></tr>'
        //                     );
        //                 }
        //                 last = group;
        //             }
        //             // if(lastSub !== rows.data()[i][1] && lastGroup !== group){
        //             //     $(rows).eq(i).before(
        //             //         '<tr class="subgroup" style="background-color: white;""><td colspan=3"><strong>'+rows.data()[i][1]+'</strong></td></tr>'
        //             //     );
        //             //     lastGroup = group;
        //             //     lastSub = rows.data()[i][1];
        //             // }
        //         } );
        //     },
		//   "initComplete": function(settings, json) {
        //         $('.group').nextUntil('.group').hide();
        //         // $('.subgroup').nextUntil('.subgroup .group  ').css( "display", "none" );
        //     }
            // fixedColumns:   {
            //     leftColumns: 3,
            // }
        } );

        // var tbody = $('#table_approval tbody');
        // tbody.on('click','.group',function()
        // {
        //     if($(this).attr('show') == 0){
        //         $(this).attr('show',1);
        //         $(this).nextUntil('.group').show();
        //         $("#list_project").trigger('change');
        //     $("#list_jenis_dokumen").trigger('change');
        //     }else{
        //         $(this).attr('show',0);
        //         $(this).nextUntil('.group').hide();
        //     }
            

        // });
        // tbody.on('click','.subgroup',function()
        // {
        //     $(this).nextUntil('.subgroup').toggle();

        // });
        $.ajaxSetup({
            headers: {
                'X-CSRF-Token': $('input[name=_token]').val()
            }
        });

        $('.DTFC_LeftBodyLiner').css('margin-bottom', '-7px')
        
        $(document).on('change', '#list_project', function() {
            var select_dokumen = $("#list_jenis_dokumen").val();
            var select_project = $("#list_project").val();
            $("#table_approval").find(".baris").each(function () {
                if(select_dokumen == ""){
                    if(select_project != ""){
                        if($(this).find(".name_project").val() != select_project){
                            $(this).hide();
                        }else{
                            $(this).show();
                        }
                    }else{
                        $(this).show();
                    }
                }else{
                    if(select_project != ""){
                        if(($(this).find(".name_project").val() == select_project) && ($(this).find(".name_dokumen").val() == select_dokumen)){
                            $(this).show();

                            // $(this).hide();
                        }else{
                            $(this).hide();
                        }
                    }else{
                        if($(this).find(".name_dokumen").val() != select_dokumen){
                            $(this).hide();
                        }else{
                            $(this).show();
                        }
                    }
                }
            });
        });

        $(document).on('change', '#list_jenis_dokumen', function() {
            var select_dokumen = $("#list_jenis_dokumen").val();
            var select_project = $("#list_project").val();
            $("#table_approval").find(".baris").each(function () {
                if(select_dokumen == ""){
                    if(select_project != ""){
                        if($(this).find(".name_project").val() != select_project){
                            $(this).hide();
                        }else{
                            $(this).show();
                        }
                    }else{
                        $(this).show();
                    }
                }else{
                    if(select_project != ""){
                        if(($(this).find(".name_project").val() == select_project) && ($(this).find(".name_dokumen").val() == select_dokumen)){
                            $(this).show();

                            // $(this).hide();
                        }else{
                            $(this).hide();
                        }
                    }else{
                        if($(this).find(".name_dokumen").val() != select_dokumen){
                            $(this).hide();
                        }else{
                            $(this).show();
                        }
                    }
                }
            });
        });
        $("#list_jenis_dokumen").trigger('change');
        $("#list_project").trigger('change');
    });

    function checkapprove(status,doc_id){
        var list = $("#approval_list").val();
        if ( status == "R" ){
            var replace = list.replace("<>A," + doc_id, "");
            $("#approval_list").val(replace + "<>" +  status + "," + doc_id);
        }else{
            var replace = list.replace("<>R" + "," +  doc_id, "");
            $("#approval_list").val(replace + "<>" +  status + "," + doc_id);
        }
    }

    function submitapprove(){
        var request = $.ajax({
            url : "{{ url('/')}}/access/approval/all",
            dataType : "json",
            data : {
                approval_list : $("#approval_list").val(),
                token : $('input[name=_token]').val()
            },
            type : "post"
        });

        request.done(function(data){
            window.location.reload();
        })
    }

    function showsearch(){
        //$('.table-search').show()
    }

    
</script>
</body>
</html>
