<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Admin QS | Dashboard </title>
    @include("master/header")
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ url('/')}}/assets/bower_components/select2/dist/css/select2.min.css">
</head>
<style>
    /* td.details-control {
    background: url('../resources/details_open.png') no-repeat center center;
    cursor: pointer;
}
tr.details td.details-control {
    background: url('../resources/details_close.png') no-repeat center center;
} */
</style>

<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">

        @include("master/sidebar_project")

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>Data Proyek</h1>

            </section>

            <!-- Main content -->
            <section class="content">
                <input type="hidden" name="workorder" id="workorder" value="{{ $rab->workorder->id }}">
                <!-- SELECT2 EXAMPLE -->
                <div class="box box-default">

                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-7">

                                <h3 class="box-title">Edit Data Rab</h3>
                                {{ csrf_field() }}
                                <input type="hidden" name="rab_id" value="{{ $rab->id }}" id="rab_id">
                                <div class="form-group">
                                    <label>No. Workorder</label>
                                    <input type="text" class="form-control" name="workorder_name" value="{{ $rab->workorder->no }}" readonly>
                                    @if ( $rab->workorder->approval != "" )<small>Approve at : <strong>{{ date("d/M/Y", strtotime($rab->workorder->approval->updated_at)) }} @endif</strong></small>
                                </div>
                                <div class="form-group">
                                    <label>No. RAB</label>
                                    <input type="text" class="form-control" name="workorder_name" value="{{ $rab->no }}" readonly>
                                    @if ( $rab->approval != "" )
                                    Approved at : <strong>{{ date("d/M/Y", strtotime($rab->approval->updated_at))}}</strong>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label>Nama</label>
                                    <input type="text" class="form-control" name="workorder_name" value="{{ $rab->name }}" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Nama Pekerjaan</label>
                                    <input type="text" class="form-control" name="workorder_name" value="{{ $rab->workorder_budget_detail->itempekerjaan->name}}" readonly>
                                </div>
                                <!-- /.form-group -->
                                <div class="form-group">
                                    <a class="btn btn-warning" href="{{ url('/')}}/workorder/detail/?id={{ $rab->workorder->id }}">Kembali</a>
                                    @if ( $rab->approval != "")
                                        @if ( $rab->approval->approval_action_id == 7 )
                                            <span class="label label-danger">Reject</span>
                                            <a href="#" class="btn btn-danger" data-toggle="modal" data-target="#exampleModal2">Close RAB</a>
                                            <button onclick="updateapprioval('{{ $rab->id}}')" class="btn btn-primary">Request Approval</button>
                                            <a class="btn btn-info" href="{{ url('/')}}/rab/tender?id={{$rab->id}}">Tender</a>
                                        @elseif( $rab->approval->approval_action_id == 6 )
                                            <span class="label label-success">Rilis</span>
                                            <a class="btn btn-info" href="{{ url('/')}}/rab/tender?id={{$rab->id}}">Tender</a>
                                        @elseif ( $rab->approval->approval_action_id == 3)
                                            <span class="label label-danger">Harus di revisi</span>
                                            <button onclick="updateapprioval('{{ $rab->id}}')" class="btn btn-primary">Request Approval</button>
                                            <a class="btn btn-info" href="{{ url('/')}}/rab/tender?id={{$rab->id}}">Tender</a>
                                        @elseif ( $rab->approval->approval_action_id == 1 || $rab->approval->approval_action_id == 4)
                                            <span class="label label-warning">Dalam Pengecekan</span>
                                            <a class="btn btn-info" href="{{ url('/')}}/rab/tender?id={{$rab->id}}">Tender</a>
                                        @elseif ( $rab->approval->approval_action_id == 8)
                                            <span class="label label-danger">Close</span>
                                            <a class="btn btn-info" href="{{ url('/')}}/rab/tender?id={{$rab->id}}">Tender</a>
                                        @endif

                                        <a class="btn btn-primary" href="{{ url('/')}}/rab/approval_history?id={{ $rab->id }}">Approval History</a>
                                    @else
                                        {{-- <button onclick="apprioval('{{ $rab->id}}')" class="btn btn-primary">Simpan</button> --}}

                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                                            Simpan
                                        </button>
                                    @endif
                                </div>
                                @if($rab->approval != '')
                                @if(count($rab->approval->histories->where("approval_action_id",7)) != 0)
                                    <table class="table table-bordered" style="border:2px solid">
                                        <tr>
                                            <td>
                                                <strong>
                                                    Rejected By : {{$rab->approval->histories->where("approval_action_id",7)->first()->user->user_name}}<br>
                                                </strong>
                                                <strong style=""> Alasan : {{$rab->approval->histories->where("approval_action_id",7)->first()->description}} </strong>
                                            </td>
                                        </tr>
                                    </table>
                                @endif
                                @endif
                            </div>
                            <!-- /.col -->

                            <!-- /.col -->

                        </div>
                        <div class="nav-tabs-custom">

                            @if($rab->units[0]->asset_type == "Modules\Project\Entities\Unit")
                            <h3><strong>Unit</strong></h3>
                                <table class="table" style="width:100%;" id="table_unit">
                                    <thead class="head_table">
                                    <tr>
                                        <td>Unit Name</td>
                                        <td>Type</td>
                                        <td>Luas Tanah</td>
                                        <td>Luas Bangunan</td>
                                        <!-- <td>Tgl DP Kons. Lunas-Akad Kredit</td>
                                        <td>Target Hrs Mulai Bangun</td>
                                        <td>No. SPT</td> -->
                                        <td>Renc. Serah Terima ke konsumen</td>
                                        <td>% Bayar Kons.</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ( $rab->units as $key => $value )
                                        @if($value->pembayaran != 0)
                                            @php $bayar = $value->pembayaran; @endphp
                                        @else
                                            @php $bayar = 0; @endphp
                                        @endif
                                        <tr>
                                            <td>{{ $value->asset->name }}</td>
                                            <td>{{ $value->asset->type->name }}</td>
                                            <td>{{ $value->asset->tanah_luas }}</td>
                                            <td>{{ $value->asset->bangunan_luas }}</td>
                                            <td>{{ date("d/M/Y",strtotime($value->asset->serah_terima_plan)) }}</td>
                                            <td>{{ $bayar }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif

                                {{-- <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-info">
                                    Tambah Item Pekerjaan
                                </button><br><br> --}}
                            @if ( $rab->approval == "" )
                                @if ( count($rab->units) > 0 && count($rab->pekerjaans) <= 0 ) 
                                    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-info">
                                        Tambah Item Pekerjaan
                                    </button>
                                    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-info3">
                                        Item Pekerjaan Data Lama
                                    </button>
                                @elseif ( count($rab->pekerjaans) > 0 )
                                    <button type="button" class="btn btn-danger" onclick="deletepekerjaans('{{ $rab->id}}')">Hapus Pekerjaan</button>
                                    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-info2">
                                        Edit Item Pekerjaan
                                    </button>
                                @endif
                            @else
                                    @php
                                        $array = array (
                                        "6" => array("label" => "Disetujui", "class" => "label label-success"),
                                        "7" => array("label" => "Ditolak", "class" => "label label-danger"),
                                        "1" => array("label" => "Dalam Proses", "class" => "label label-warning"),
                                        "4" => array("label" => "Dalam Proses", "class" => "label label-warning"),
                                        "3" => array("label" => "Harus di Revisi", "class" => "label label-warning"),
                                        "8" => array("label" => "Ditolak", "class" => "label label-danger"),
                                        )
                                    @endphp
                                <!-- <span class="{{ $array[$rab->approval->approval_action_id]['class'] }}">  
                                {{ $array[$rab->approval->approval_action_id]['label'] }}
                            </span> -->
                                @if ( $rab->approval->approval_action_id == "7")
                                    @if ( $rab->pekerjaans->count() > 0 )
                                        <button type="button" class="btn btn-danger" onclick="deletepekerjaans('{{ $rab->id}}')">Hapus Pekerjaan</button>
                                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-info2">
                                            Edit Item Pekerjaan
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-info">
                                        Tambah Item Pekerjaan
                                        </button>
                                    @endif
                                @else

                                @endif
                            @endif
                            <h3><strong>Nilai RAB Rp {{ number_format($rab->nilai) }} </strong></h3>
                            <table class="table table-bordered">
                                <thead class="head_table">
                                    <tr>
                                        <td>COA Pekerjaan</td>
                                        <td>Item Pekerjaan</td>
                                        <td>Volume</td>
                                        <td>Sat</td>
                                        <td>Hrg Sat (Rp/..)</td>
                                        <td>Subtotal</td>
                                        <td>Bobot(%)</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ( $rab->units->count() > 0 )
                                        @foreach($rab->pekerjaans as $key => $value )
                                            @if($value->volume != 0)
                                                <tr>
                                                    <td><strong>{{ $value->itempekerjaan->code }}</strong></td>
                                                    <td><strong>{{ $value->itempekerjaan->name }}</strong></td>
                                                    <td style="text-align:right">
                                                        <strong><span class="labels" id="label_rab_volume_{{ $value->id }}">{{ bcdiv((float)$value->volume, 1, 2) }}</span>
                                                        <input class="values" type="text" id="input_rab_volume_{{ $value->id}}" value="{{ bcdiv((float)$value->volume, 1, 2) }}" style="display: none;"></strong>
                                                    </td>
                                                    <td>
                                                        <strong><span class="labels" id="label_rab_satuan_{{ $value->id }}">{{ $value->satuan }}</span>
                                                        <input class="values" type="text" id="input_rab_satuan_{{ $value->id}}" value="{{ $value->satuan }}" style="display: none;"></strong>
                                                    <td style="text-align:right">
                                                        @if($value->total_nilai != null && $value->total_nilai != 0)
                                                            <strong><span class="labels" id="label_rab_nilai_{{ $value->id }}"></span>
                                                            <input class="values" type="text" id="input_rab_nilai_{{ $value->id}}" value="{{ $value->nilai }}" style="display: none;"></strong>
                                                        @else
                                                            <strong><span class="labels" id="label_rab_nilai_{{ $value->id }}">{{number_format($value->nilai,2)}}</span>
                                                            <input class="values" type="text" id="input_rab_nilai_{{ $value->id}}" value="{{ $value->nilai }}" style="display: none;"></strong>
                                                        @endif
                                                    </td>
                                                    </td>
                                                    <td style="text-align:right">
                                                        @if($value->total_nilai != null && $value->total_nilai != 0)
                                                            <strong><span class="labels" id="label_rab_nilai_{{ $value->id }}">{{ number_format($value->total_nilai,2) }}</span><strong>
                                                            <input class="values" type="text" id="input_rab_nilai_{{ $value->id}}" value="{{ $value->total_nilai}}" style="display: none;">
                                                        @else
                                                            <strong><span class="labels" id="label_rab_nilai_{{ $value->id }}">{{ number_format($value->nilai * bcdiv((float)$value->volume, 1, 2),2) }}</span><strong>
                                                            <input class="values" type="text" id="input_rab_nilai_{{ $value->id}}" value="{{ $value->nilai * bcdiv((float)$value->volume, 1, 2)}}" style="display: none;">
                                                        @endif
                                                    </td>
                                                    <td style="text-align:right">
                                                        @if($value->total_nilai != null && $value->total_nilai != 0)
                                                            <strong><span class="labels" id="label_rab_nilai_{{ $value->id }}"> {{ number_format((($value->total_nilai) / ( $rab->nilai / $rab->units->count() ) * 100),2) }}</span></strong>
                                                        @elseif($value->nilai != null && $value->nilai != 0)
                                                            <strong><span class="labels" id="label_rab_nilai_{{ $value->id }}"> {{ number_format((($value->nilai * bcdiv((float)$value->volume, 1, 2)) / ( $rab->nilai / $rab->units->count() ) * 100),2) }}</span></strong>
                                                        @else
                                                            0
                                                        @endif
                                                        <input class="values" type="text" id="input_rab_nilai_{{ $value->id}}" value="{{ $value->nilai * bcdiv((float)$value->volume, 1, 2)}}" style="display: none;">
                                                    </td>
                                                </tr>
                                                @if(count($value->sub_pekerjaan))
                                                    @foreach($value->sub_pekerjaan as $key2 => $value2 )
                                                        <tr>
                                                            <td></td>
                                                            <td>{{$value2->name}}</td>
                                                            <td style="text-align:right">
                                                                {{bcdiv((float)(float)$value2->volume,1, 2)}}
                                                               <br>
                                                            </td>
                                                            <td >{{$value2->satuan}}</td>
                                                            <td style="text-align:right">{{number_format($value2->nilai)}}</td>
                                                            <td style="text-align:right">{{number_format($value2->total_nilai)}}</td>
                                                            <td></td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            @endif
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                            <!-- /.tab-content -->
                        </div>
                        <!-- /.row -->
                    </div>
                    <!-- /.box-body -->

                </div>
                <!-- /.box -->


            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        {{-- @include("master/copyright") --}}


        <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
        <div class="control-sidebar-bg"></div>
        <div class="modal fade" id="modal-info">
            {{-- <form action="{{ url('/')}}/rab/save-pekerjaan" method="post">
                {{ csrf_field() }} --}}
                <input type="hidden" id="idpkr" value="{{ $itempkr->id }}" name="idpkr">
                <input type="hidden" name="rab" id="rab" value="{{ $rab->id }}">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Pekerjaan</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Pilih Item Pekerjaan</label>
                                <select class="form-control" id="item_coa">
                                    <!-- <option value="">( pilih item pekerjaan )</option> -->

                                    <option value="{{ $itempkr->id}}">{{ $itempkr->code }} - {{ $itempkr->name }}</option>

                                </select>
                            </div>
                            <div class="form-group" style="display: none;">
                                <label></label>
                                <select class="form-control" id="item_child_coa">

                                </select>
                            </div>
                            <h4>Budget Terpakai : <strong><span id="budget_total"></span></strong></h4>
                            <h4>Budget Tersisa : <strong><span id="budget_tersisa"></span></strong></h4>
                            <input type="hidden" id="budget_total_val" value="" name="">
                            <input type="hidden" id="budget_tahunan_id" name="budget_tahunan_id">
                            <input type="hidden" id="budget_tersisa_val" name="">
                            <table class="table" id="table_itempekerjaan" style="width:100%">
                                <thead class="head_table">
                                    <tr>
                                        <td style="width:10%">COA Pekerjaan</td>
                                        <td style="width:25%">Item Pekerjaan</td>
                                        <td style="width:15%">Volume</td>
                                        <td style="width:15%">Satuan</td>
                                        <td style="width:15%">Nilai Satuan</td>
                                        <td style="width:15%">Total Nilai</td>
                                        <td style="width:5%"></td>
                                    </tr>
                                </thead>
                                <tbody id="itempekerjaan">

                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" id="save_pekerjaan">Save changes</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            {{-- </form> --}}

        </div>

        <div class="modal fade" id="modal-info2">
             {{-- <form action="{{ url('/')}}/rab/update-pekerjaan" method="post">
                {{ csrf_field() }} --}}
                <input type="hidden" id="idpkr" value="{{ $itempkr->id }}" name="idpkr">
                <input type="hidden" name="rab" id="rab" value="{{ $rab->id }}">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Pekerjaan</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Pilih Item Pekerjaan</label>
                                <select class="form-control" id="item_coa">
                                    <!-- <option value="">( pilih item pekerjaan )</option> -->

                                    <option value="{{ $itempkr->id}}">{{ $itempkr->code }} - {{ $itempkr->name }}</option>

                                </select>
                            </div>
                            <div class="form-group" style="display: none;">
                                <label></label>
                                <select class="form-control" id="item_child_coa">

                                </select>
                            </div>
                            <h4>Budget Terpakai : <strong><span id="budget_total"></span></strong></h4>
                            <h4>Budget Tersisa : <strong><span id="budget_tersisa"></span></strong></h4>
                            <input type="hidden" id="budget_total_val" value="" name="">
                            <input type="hidden" id="budget_tahunan_id" name="budget_tahunan_id">
                            <input type="hidden" id="budget_tersisa_val" name="">
                            <table class="table" id="table_itempekerjaan2" style="width:100%">
                                <thead class="head_table">
                                    <tr>
                                        <td style="width:10%">COA Pekerjaan</td>
                                        <td style="width:25%">Item Pekerjaan</td>
                                        <td style="width:15%">Volume</td>
                                        <td style="width:15%">Satuan</td>
                                        <td style="width:15%">Nilai Satuan</td>
                                        <td style="width:15%">Total Nilai</td>
                                        <td style="width:5%"></td>
                                    </tr>
                                </thead>
                                <tbody id="itempekerjaan">
                                    @foreach($rab->pekerjaans as $key => $value)
                                        @if ($value->itempekerjaan != null)
                                            @php
                                                $tender_sebelumnnya = \Modules\Tender\Entities\TenderPenawaranDetail::where("rab_pekerjaan_id",$value->id)->orderBy("id","DESC")->first();
                                            @endphp
                                            @if($value->satuan == null)
                                                <tr class="test">
                                                    <td>
                                                        <strong>
                                                            {{$value->itempekerjaan->code}} 
                                                            <input class="id_pekerjaan" value="{{$value->itempekerjaan->id}}" type="hidden">
                                                            <input class="id_rab_pekerjaan" value="{{$value->id}}" type="hidden">
                                                        </strong>
                                                    </td>
                                                    <td><strong>{{$value->itempekerjaan->name}}</strong></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                            @else
                                                <tr class="test">
                                                    <td>{{$value->itempekerjaan->code}} 
                                                        <input class="id_pekerjaan" value="{{$value->itempekerjaan->id}}" type="hidden">
                                                        <input class="id_rab_pekerjaan" value="{{$value->id}}" type="hidden">
                                                    </td>
                                                    <td>{{$value->itempekerjaan->name}}</td>
                                                    <td>
                                                        @if($value->volume != null || $value->volume != 0)
                                                            <input type='number' class='form-control volume' name='volume' value='{{bcdiv((float)$value->volume, 1, 2)}}' autocomplete='off' style='width:100%' readonly/>
                                                        @else
                                                            <input type='text' class='form-control volume' name='volume' value='0' autocomplete='off' style='width:100%' readonly/>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <input type='text' class='form-control satuan' name='satuan' value='{{$value->satuan}}' autocomplete='off' style='width:100%' readonly/>
                                                    </td>
                                                    <td>
                                                    </td>
                                                    <td>
                                                        <input type='text' class='form-control total_nilai' name='total_nilai' value='{{$value->total_nilai}}' style='width:100%' readonly/>
                                                    </td>
                                                    @if(strpos($value->itempekerjaan->code, "100.") !== FALSE)
                                                        <td class=" details-control">
                                                            <button type="button" class="btn btn-success" ><i class="fa fa-plus" style="font-size:15px"></i></button>
                                                        </td>   
                                                    @else
                                                        @if(strpos($value->itempekerjaan->code, "00") === FALSE)
                                                            <td class=" details-control">
                                                                <button type="button" class="btn btn-success" ><i class="fa fa-plus" style="font-size:15px"></i></button>
                                                            </td>
                                                        @else
                                                            <td class="">
                                                            </td>
                                                        @endif
                                                    @endif
                                                </tr>
                                                @if(count($value->sub_pekerjaan) != 0)
                                                    <tr class="child">
                                                        <td colspan="7">
                                                        <table border="0" style="padding:none;width:100%" class="table child_table {{$value->itempekerjaan->id}} ">
                                                                <thead hidden>
                                                                    <tr>
                                                                        <th style="width:10%;"></th>
                                                                        <th style="width:25%;"></th>
                                                                        <th style="width:15%;"></th>
                                                                        <th style="width:15%;"></th>
                                                                        <th style="width:15%;"></th>
                                                                        <th style="width:15%;"></th>
                                                                        <th style="width:5%;"></th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach($value->sub_pekerjaan as $key2 => $value2)
                                                                        <tr class="test_child"> 
                                                                            <td style="width:10%;text-align:center;border-top:none;">
                                                                                <input class="child_id_pekerjaan" value="" hidden>
                                                                                <input class="child_rab_sub_pekerjaan_id" value="{{$value2->id}}" hidden>
                                                                            </td> 
                                                                            <td style="width:25%;text-align:center;border-top:none;">
                                                                                <input type="text" class="form-control child_name" name="child_name" value="{{$value2->name}}" style="width:100%" />
                                                                            </td> 
                                                                            <td style="width:15%;text-align:center;border-top:none;">
                                                                                <input type="text" class="form-control child_volume" name="child_volume" value="{{bcdiv((float)$value2->volume,1,2)}}" style="width:100%" />
                                                                            </td> 
                                                                            <td style="width:15%;text-align:center;border-top:none;">
                                                                                <select class="form-control list_satuan" name="list_satuan[]" id="" style="width:100%;">
                                                                                    <option value="">(pilih satuan)</option>
                                                                                        @foreach($coa_satuan as $key3 => $value3)
                                                                                            @if($value2->satuan == $value3->satuan)
                                                                                                <option value="{{$value3->satuan}}" selected>{{$value3->satuan}}</option>
                                                                                            @else
                                                                                                <option value="{{$value3->satuan}}">{{$value3->satuan}}</option>
                                                                                            @endif
                                                                                        @endforeach
                                                                                </select>
                                                                            </td> 
                                                                            <td style="width:15%;text-align:center;border-top:none;">
                                                                                <input type="text" class="form-control child_nilai" name="child_nilai" value="{{number_format($value2->nilai)}}" style="width:100%" />
                                                                            </td>
                                                                            <td style="width:15%;text-align:center;border-top:none;">
                                                                                <input type="text" class="form-control child_total_nilai" name="child_total_nilai" value="{{number_format($value2->total_nilai)}}" style="width:100%" readonly/>
                                                                            </td>
                                                                            <td style="width:5%;text-align:center;border-top:none;">
                                                                                <button type="button" class="btn btn-danger hapus" ><i class="fa fa-trash" style="font-size:15px"></i></button>
                                                                            </td> 
                                                                        </tr> 
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endif
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" id="save_pekerjaan2">Save changes</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            {{-- </form> --}}

        </div>

        <div class="modal fade" id="modal-info3">
                <div class="modal-dialog modal-md">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Pekerjaan</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Pilih Dokumen</label>
                                <select class="form-control" id="dokumen_lama">
                                    <option value="">Pilih Dokumen</option>
                                    <option value="RAB">RAB</option>
                                    <option value="SPK">SPK</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Pilih No Dokumen</label>
                                <select class="form-control" id="no_dokumenlama">

                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" id="tambahkan_pekerjaan">Tambahkan Pekerjaan</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->

        <div class="modal fade" id="modal-default">
            <form action="{{ url('/')}}/rab/save-units" method="post">
                <input type="hidden" id="idpkr" value="{{ $itempkr->id }}" name="idpkr">
                <input type="hidden" value="{{ $rab->id }}" name="rab_unit_id">
                {{ csrf_field() }}
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title"></h4>
                        </div>

                        <div class="modal-body">

                            <table class="table">
                                <thead class="head_table">
                                    <tr>
                                        <td>Unit</td>
                                        <td>keterangan</td>
                                        <td>
                                            <!--input type="checkbox" value="" id="unit_rab_all" onclick="checkall();"-->
                                        </td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $start=0; $arrayType= array();@endphp
                                    @foreach ( $rab->workorder->details as $key4 => $value4 )
                                    @if ( $value4->asset != "" )
                                    @if ( $value4->asset->type != "" )
                                    @php $arrayType[$value4->asset->type->id] = array("id" => $value4->asset->type->id, "name" => $value4->asset->type->name ); $start++;@endphp
                                    <tr class="type type_{{ $value4->asset->type->id }}" style="display: none;">
                                        <td>{{ $value4->asset->name }}</td>
                                        <td>{{ $value4->asset->type->name or ''}}</td>
                                        <td>
                                            <input type="checkbox" name="unit_rab_[{{$value4->asset_id}}]" value="{{ $value4->asset_id }}">Pilih ke RAB
                                            <input type="hidden" value="{{ $value4->asset_type }}" name="unit_rab_type_[{{$value4->asset_id}}]">
                                        </td>
                                    </tr>
                                    @else
                                    <tr class="">
                                        <td>{{ $value4->asset->name }}</td>
                                        <td>{{ $value4->asset->type->name or ''}}</td>
                                        <td>
                                            <input type="checkbox" name="unit_rab_[{{$value4->asset_id}}]" value="{{ $value4->asset_id }}">Pilih ke RAB
                                            <input type="hidden" value="{{ $value4->asset_type }}" name="unit_rab_type_[{{$value4->asset_id}}]">
                                        </td>
                                    </tr>
                                    @endif
                                    @else
                                    <tr class="">
                                        <td>{{ $value4->project->name }}</td>
                                        <td>{{ $value4->asset->type->name or ''}}</td>
                                        <td>
                                            <input type="checkbox" name="unit_rab_[{{$value4->asset_id}}]" value="{{ $value4->asset_id }}">Pilih ke RAB
                                            <input type="hidden" value="{{ $value4->asset_type }}" name="unit_rab_type_[{{$value4->asset_id}}]">
                                        </td>
                                    </tr>
                                    @endif

                                    @endforeach

                                    @foreach ( $arrayType as $key => $value)
                                    <tr>
                                        <td>Type : </td>
                                        <td><input type="radio" name="type" onClick="showUnitType({{$value['id']}})">{{ $value['name']}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <li style="color:red;">centang unit/kawasan yang ingin di pilih</li>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
            </form>
            <!-- /.modal-dialog -->
        </div>
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <!-- <h5 class="modal-title" id="exampleModalLabel">Modal title</h5> -->
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  Apakah anda yakin untuk merilis dokumen ini
                  @if(count($rab->pekerjaans) == 0 && $rab->nilai ==0)
                    <li style="color:red;">Item Pekerjaan Belum di Isi</li>
                  @endif
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  @if (count($rab->pekerjaans) == 0 || $rab->nilai == 0)
                  <button class="btn btn-primary" disabled>Simpan</button>
                  @else
                    <button onclick="apprioval('{{ $rab->id}}')" class="btn btn-primary">Simpan</button>
                  @endif
                  <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                </div>
              </div>
            </div>
          </div>
        <!-- /.modal -->
    </div>
    <!-- ./wrapper -->
    <div id="clone_satuan" hidden>
        <select class="form-control list_satuan" name="list_satuan[]" id="" style="width:100%;">
            <option value="">(pilih satuan)</option>
                @foreach($coa_satuan as $key => $value)
                    <option value="{{$value->satuan}}">{{$value->satuan}}</option>
                @endforeach
        </select>
    </div>

    <div id="clone_sub" hidden>
        <table>
        <tr class="child">
            <td colspan="7">
                <table border="0" style="padding:none;width:100%" class="table child_table">
                    <thead hidden>
                        <tr>
                            <th style="width:10%;"></th>
                            <th style="width:25%;"></th>
                            <th style="width:15%;"></th>
                            <th style="width:15%;"></th>
                            <th style="width:15%;"></th>
                            <th style="width:15%;"></th>
                            <th style="width:5%;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="test_child"> 
                            <td style="width:10%;text-align:center;border-top:none;">
                                <input class="child_id_pekerjaan" value="" hidden>
                            </td> 
                            <td style="width:25%;text-align:center;border-top:none;">
                                <input type="text" class="form-control child_name" name="child_name" value="" style="width:100%" />
                            </td> 
                            <td style="width:15%;text-align:center;border-top:none;">
                                <input type="text" class="form-control child_volume" name="child_volume" value="0" style="width:100%" />
                            </td> 
                            <td style="width:15%;text-align:center;border-top:none;">
                                <select class="form-control list_satuan" name="list_satuan[]" id="" style="width:100%;">
                                    <option value="">(pilih satuan)</option>
                                        @foreach($coa_satuan as $key3 => $value3)
                                            <option value="{{$value3->satuan}}">{{$value3->satuan}}</option>
                                        @endforeach
                                </select>
                            </td>
                            <td style="width:15%;text-align:center;border-top:none;">
                                <input type="text" class="form-control child_nilai" name="child_nilai" value="" style="width:100%" />
                            </td>
                            <td style="width:15%;text-align:center;border-top:none;">
                                <input type="text" class="form-control child_total_nilai" name="child_total_nilai" value="" style="width:100%" readonly/>
                            </td>
                            <td style="width:5%;text-align:center;border-top:none;">
                                <button type="button" class="btn btn-danger hapus" ><i class="fa fa-trash" style="font-size:15px"></i></button>
                            </td> 
                        </tr> 
                    </tbody>
                </table>
            </td>
        </tr>
        </table>
    </div> 

    <div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    Close RAB
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <strong>
                    Apakah anda yakin untuk MengClose RAB ini<br>
                    (seluruh Tender dan penawaran yang yang berhubungan dengan RAB ini akan di Close)
                    </strong>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="closeRab({{$rab->id}})">Submit</button>
                    <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                </div>
            </div>
        </div>
    </div>

    @include("master/footer_table")
    <script src="{{ url('/')}}/assets/bower_components/select2/dist/js/select2.full.min.js"></script>
    <script type="text/javascript">
    // $("select").select2();
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-Token': $('input[name=_token]').val()
                }
            });

            var idpkr = $('#idpkr').val();
            var wo = $('#workorder').val();

            $(".child_nilai").number(true);
            $(".child_total_nilai").number(true);
            
            detailitem(idpkr, wo);
        });

        function format(d) {
            // `d` is the original data object for the row
            return '<table border="0" style="padding:none;width:100%" class="table child_table">' +
                '<thead hidden>'+
                    '<tr>'+
                        '<th style="width:10%;"></th>'+
                        '<th style="width:25%;"></th>'+
                        '<th style="width:15%;"></th>'+
                        '<th style="width:15%;"></th>'+
                        '<th style="width:15%;"></th>'+
                        '<th style="width:15%;"></th>'+
                        '<th style="width:5%;"></th>'+
                    '</tr>'+
                '</thead>'+
                '<tbody>'+
                    '<tr class="test_child">' +
                        '<td style="width:10%;text-align:center;border-top:none;"><input class="child_id_pekerjaan" value="" hidden></td>' +
                        '<td style="width:25%;text-align:center;border-top:none;"><input type="text" class="form-control child_name" name="child_name" value="" style="width:100%" /></td>' +
                        '<td style="width:15%;text-align:center;border-top:none;"><input type="text" class="form-control child_volume" name="child_volume" value="0" style="width:100%" /></td>' +
                        '<td style="width:15%;text-align:center;border-top:none;">'+$('#clone_satuan').clone().html()+'</td>' +
                        '<td style="width:15%;text-align:center;border-top:none;"><input type="text" class="form-control child_nilai" name="child_nilai" value="" style="width:100%" /></td>' +
                        '<td style="width:15%;text-align:center;border-top:none;"><input type="text" class="form-control child_total_nilai" name="child_total_nilai" value="0" style="width:100%" readonly/></td>' +
                        '<td style="width:5%;text-align:center;border-top:none;"><button type="button" class="btn btn-danger hapus" ><i class="fa fa-trash" style="font-size:15px"></i></button></td>' +
                    '</tr>' +
                '</tbody>'+
                '</table>';
        }

        var table = $('#table_itempekerjaan').DataTable({
            // "ajax": "../ajax/data/objects.txt",
            "columns": [{
                    "data": "coa",
                    "width": "10%"
                },
                {
                    "data": "itempekerjaan",
                    "width": "25%"
                },
                {
                    "data": "volume",
                    "width": "15%"
                },
                {
                    "data": "satuan",
                    "width": "15%"
                },
                {
                    "data": "nilai",
                    "width": "15%"
                },
                {
                    "data": "total",
                    "width": "15%"
                },
                {
                    "className": 'tambah details-control',
                    "orderable": false,
                    "data": "hidden",
                    // "defaultContent": '<button type="button" class="btn btn-success" ><i class="fa fa-plus" style="font-size:15px"></i></button>',
                    "render":  function (data) { 
                                    if (data == 'hidden') {
                                        return '<button type="button" class="btn btn-success" ><i class="fa fa-plus" style="font-size:15px"></i></button>';
                                    }else {
                                        return '';
                                    }
                                },
                    "width": "5%"
                },
            ],
            "autoWidth": false,
            "paging": false,
            // "scrollX": true,
            "order": [
                [0, 'asc']
            ]
        });

        function detailitem(idpkr, workorder) {
            $("#budget_total").html("0");
            $("#budget_total_val").val();
            var request = $.ajax({
                url: "{{ url('/')}}/rab/childcoa",
                dataType: "json",
                data: {
                    id: idpkr,
                    workorder: workorder
                },
                type: "post"
            });

            request.done(function(data) {
                if (data.data.length > 0) {
                    $(data.data).each(function(i, v) {
                        if (v.satuan != null) {
                            var ItemTable = {
                                coa: v.code+'<input class="id_pekerjaan" value="'+v.id+'" hidden>'+'<input class="" id="'+v.id+'" value="" hidden>',
                                itempekerjaan: v.name,
                                volume: "<input type='text' class='form-control volume' name='volume' value='0' autocomplete='off' style='width:100%' readonly/>",
                                satuan: "<input type='text' class='form-control satuan' name='satuan' value='" + v.satuan + "' autocomplete='off' style='width:100%' readonly/>",
                                nilai: null,
                                total: "<input type='text' class='form-control total_nilai' name='total_nilai' value='' style='width:100%' readonly/>",
                                hidden: 'hidden',
                            };
                        } else {
                            var ItemTable = {
                                coa: v.code+'<input class="id_pekerjaan" value="'+v.id+'" hidden>',
                                itempekerjaan: v.name,
                                volume: null,
                                satuan: null,
                                nilai: null,
                                total: null,
                                hidden: '',
                            };
                        }
                        $('#table_itempekerjaan').DataTable().row.add(ItemTable);
                    });
                }
                $('#table_itempekerjaan').DataTable().draw();
                $("#table_itempekerjaan").find('tbody tr').addClass('test');
                $("#table_itempekerjaan").find('tbody').find('tr').each(function () {
                    if($(this).find(".satuan").val() == undefined){
                        $(this).find(".tambah").removeClass("details-control");
                        // console.log($(this).find(".tambah"));
                    }
                    // console.log($(this).find(".satuan").val());
                });
                // $("#itempekerjaan").html(data.html);
                //$("#budget_total").html(data.budget);
                $("#budget_total").number(true);
                $("#budget_total_val").val(data.budget);
                $("#budget_tahunan_id").val(data.budget_tahunan_id);
                $("#budget_tersisa_val").val(data.budget_tersisa);
                $("#budget_tersisa").text(data.budget_tersisa);
                $("#budget_tersisa").number(true);
                $(".total_nilai").number(true);
                // $(".volume").number(true);
            });
        }

        /* Formatting function for row details - modify as you need */



        $(document).ready(function() {
            // Add event listener for opening and closing details
            $('#table_itempekerjaan tbody').on('click', 'td.details-control', function() {
                var tr = $(this).closest('tr');
                var row = table.row(tr);

                if (row.child.isShown()) {
                    $(this).parents(".test").next().find(".child_table").append( '<tr class="test_child">' +
                        '<td style="width:10%;text-align:center;border-top:none;"><input class="child_id_pekerjaan" value="" hidden></td>' +
                        '<td style="width:25%;text-align:center;border-top:none;"><input type="text" class="form-control child_name" name="child_name" value="" style="width:100%" /></td>' +
                        '<td style="width:15%;text-align:center;border-top:none;"><input type="text" class="form-control child_volume" name="child_volume" value="0" style="width:100%" /></td>' +
                        '<td style="width:15%;text-align:center;border-top:none;">'+$('#clone_satuan').clone().html()+'</td>' +
                        '<td style="width:15%;text-align:center;border-top:none;"><input type="text" class="form-control child_nilai" name="child_nilai" value="" style="width:100%" /></td>' +
                        '<td style="width:15%;text-align:center;border-top:none;"><input type="text" class="form-control child_total_nilai" name="child_total_nilai" value="0" style="width:100%" readonly/></td>' +
                        '<td style="width:5%;text-align:center;border-top:none;"><button type="button" class="btn btn-danger hapus" ><i class="fa fa-trash" style="font-size:15px"></i></button></td>' +
                    '</tr>');

                    // $(this).parents(".test").next().find(".child_table").DataTable().destroy();
                    $(this).parents(".test").next().find(".child_table").find('tbody tr').addClass('test_child');
                } else {
                    // Open this row
                    row.child(format(row.data())).show();
                    tr.next().addClass('child');
                    tr.next().find(".child_table").addClass(tr.find('.id_pekerjaan').val());
                    tr.next().find('.child_id_pekerjaan').val(tr.find('.id_pekerjaan').val());
                    tr.next().find('.list_satuan').val(tr.find('.satuan').val()).trigger('change');
                }
                $(".child_nilai").number(true);
                $(".child_total_nilai").number(true);
            });


            $('#table_itempekerjaan2 tbody').on('click', 'td.details-control', function() {

                var tr = $(this).closest('tr');
                // var row = table.row(tr);
                if (tr.next().hasClass("child") == true) {
                    $(this).parents(".test").next().find(".child_table").append( '<tr class="test_child">' +
                        '<td style="width:10%;text-align:center;border-top:none;"><input class="child_id_pekerjaan" value="" hidden></td>' +
                        '<td style="width:25%;text-align:center;border-top:none;"><input type="text" class="form-control child_name" name="child_name" value="" style="width:100%" /></td>' +
                        '<td style="width:15%;text-align:center;border-top:none;"><input type="text" class="form-control child_volume" name="child_volume" value="0" style="width:100%" /></td>' +
                        '<td style="width:15%;text-align:center;border-top:none;">'+$('#clone_satuan').clone().html()+'</td>' +
                        '<td style="width:15%;text-align:center;border-top:none;"><input type="text" class="form-control child_nilai" name="child_nilai" value="" style="width:100%" /></td>' +
                        '<td style="width:15%;text-align:center;border-top:none;"><input type="text" class="form-control child_total_nilai" name="child_total_nilai" value="0" style="width:100%" readonly/></td>' +
                        '<td style="width:5%;text-align:center;border-top:none;"><button type="button" class="btn btn-danger hapus" ><i class="fa fa-trash" style="font-size:15px"></i></button></td>' +
                    '</tr>');

                    // $(this).parents(".test").next().find(".child_table").DataTable().destroy();
                    $(this).parents(".test").next().find(".child_table").find('tbody tr').addClass('test_child');
                } else {
                    // Open this row
                    tr.after($("#clone_sub").find('tbody').html())
                    // tr.next().addClass('child');
                    tr.next().find(".child_table").addClass(tr.find('.id_pekerjaan').val());
                    tr.next().find('.child_id_pekerjaan').val(tr.find('.id_pekerjaan').val());
                    tr.next().find('.list_satuan').val(tr.find('.satuan').val()).trigger('change');
                }
                $(".child_nilai").number(true);
                $(".child_total_nilai").number(true);
            });

        });
        // $( ".child_volume" ).keyup(function() {
        $(document).on('keyup', '.child_volume', function() {
            var nilai = $(this).parents(".test_child").find(".child_nilai").val();
            if(nilai == null){
                $(this).parents(".test_child").find(".child_nilai").val(0);
                $(this).parents(".test_child").find(".child_total_nilai").val(0);
            }else{
                var total = nilai * $(this).val();
                $(this).parents(".test_child").find(".child_total_nilai").val(total);
            }

            var total_nilai = 0;
            var total_volume = 0;
            var status = 1;
            $(this).parents(".child_table").find(".test_child").each(function () {
                if($(this).find(".list_satuan").val() == $(this).parents(".child").prev().find(".satuan").val() && status == 1){
                    if($(this).parents(".child").prev().find(".satuan").val() != "Ls"){
                        total_volume += parseFloat($(this).find(".child_volume").val());
                    }else{
                        total_volume = 1;
                    }
                }else{
                    status = 0;
                }

                total_nilai += parseInt($(this).find(".child_total_nilai").val());
            });
            $(this).parents(".child").prev().find(".volume").val(total_volume);
            $(this).parents(".child").prev().find(".total_nilai").val(total_nilai);
        });

        $(document).on('keyup', '.child_nilai', function() {
            var volume = $(this).parents(".test_child").find(".child_volume").val();
            if(volume == null){
                $(this).parents(".test_child").find(".child_volume").val(0);
                $(this).parents(".test_child").find(".child_total_nilai").val(0);
            }else{
                var total = volume * $(this).val();
                $(this).parents(".test_child").find(".child_total_nilai").val(total);
            }

            var total_nilai = 0;
            var total_volume = 0;
            var status = 1;
            $(this).parents(".child_table").find(".test_child").each(function () {
                
                if($(this).find(".list_satuan").val() == $(this).parents(".child").prev().find(".satuan").val() && status == 1){
                    if($(this).parents(".child").prev().find(".satuan").val() != "Ls"){
                        total_volume += parseFloat($(this).find(".child_volume").val());
                    }else{
                        total_volume = 1;
                    }
                }else{
                    status = 0;
                }
                total_nilai += parseInt($(this).find(".child_total_nilai").val());
            });
            $(this).parents(".child").prev().find(".volume").val(total_volume);
            $(this).parents(".child").prev().find(".total_nilai").val(total_nilai);
        });

        $(document).on('change', '.list_satuan', function() {
            var nilai = $(this).parents(".test_child").find(".child_nilai").val();
            var volume = $(this).parents(".test_child").find(".child_volume").val();
            if(volume == null && nilai == null){
                // $(this).parents(".test_child").find(".child_volume").val(0);
                // $(this).parents(".test_child").find(".child_total_nilai").val(0);
            }else{
                var total = volume * nilai;
                $(this).parents(".test_child").find(".child_total_nilai").val(total);
            }

            var total_nilai = 0;
            var total_volume = 0;
            var status = 1;
            $(this).parents(".child_table").find(".test_child").each(function () {
                if($(this).find(".list_satuan").val() == $(this).parents(".child").prev().find(".satuan").val() && status == 1){
                    if($(this).parents(".child").prev().find(".satuan").val() != "Ls"){
                        total_volume += parseFloat($(this).find(".child_volume").val());
                    }else{
                        total_volume = 1;
                    }
                }else{
                    status = 0;
                }
                total_nilai += parseInt($(this).find(".child_total_nilai").val());
            });
            $(this).parents(".child").prev().find(".volume").val(total_volume);
            $(this).parents(".child").prev().find(".total_nilai").val(total_nilai);
        });

        $(document).on('click', '.hapus', function() {
            // console.log($(this).parents(".test_child"));
            // var parent = $(this).parents(".child_table");
            $(this).parents(".test_child").removeClass( "test_child" ).addClass("for_delete");
            // $(this).parents(".test_child").remove();

            var total_nilai = 0;
            var total_volume = 0;
            var status = 1;
            var tr = 0;
            // console.log($(this).parents(".child_table").find(".test_child").each());
            $(this).parents(".child_table").find(".test_child").each(function () {
                console.log("hoho");
                if($(this).find(".list_satuan").val() == $(this).parents(".child").prev().find(".satuan").val() && status == 1){
                    if($(this).parents(".child").prev().find(".satuan").val() != "Ls"){
                        total_volume += parseFloat($(this).find(".child_volume").val());
                    }else{
                        total_volume = 1;
                    }
                }else{
                    status = 0;
                }
                total_nilai += parseInt($(this).find(".child_total_nilai").val());
                tr++;
            });
            $(this).parents(".child").prev().find(".volume").val(total_volume);
            $(this).parents(".child").prev().find(".total_nilai").val(total_nilai);
            if(tr != 0){
                $(this).parents(".for_delete").remove();
            }else{
                $(this).parents(".child").remove();
                // var tr = $(this).parents(".child").prev();
                // var row = table.row(tr);
                // row.child.hide();
                // tr.removeClass('shown');
                // $(this).parents(".child").remove();
                //     row.child.hide();
                // $(this).parents(".child").hide();
            }
        });

        $(document).on('click', '#save_pekerjaan', function() {
            var main = [];
            $(".test").each(function () {

                var id_pekerjaan = $(this).find(".id_pekerjaan").val();
                var sub = [];
                $("."+id_pekerjaan+" .test_child").each(function () {
                    var arr = [
                        $(this).find(".child_name").val(),
                        $(this).find(".child_volume").val(),
                        $(this).find(".list_satuan").val(),
                        $(this).find(".child_nilai").val(),
                        $(this).find(".child_total_nilai").val(),
                    ];

                    sub.push(arr);
                });
                if($(this).find(".total_nilai").val() != undefined){
                    var total_nilai = $(this).find(".total_nilai").val();
                }else{
                    var total_nilai = 0;
                }
                var arr2 = [
                        $(this).find(".id_pekerjaan").val(),
                        $(this).find(".volume").val(),
                        $(this).find(".satuan").val(),
                        total_nilai,
                        sub,
                    ];
                main.push(arr2);

                // console.log(main);
            });
            var url = "{{ url('/')}}/rab/saveAllPekerjaan";
            $.ajax({
                type: 'post',
                dataType: 'json',
                url: url,
                data: {
                    data: JSON.stringify(main),
                    rab_id: $("#rab_id").val(),
                },
                beforeSend: function() {
                waitingDialog.show();
                },
                success: function(data) { 
                    console.log("hhhuu")
                    window.location.reload(true);
                },
                complete: function() {
                waitingDialog.hide(); 
                },
            });
            // window.location.href;
            // window.location.replace(window.location.href);
            // window.location.replace("/rab/detail?id="+$("#rab_id").val()+"&idpkr="+$("#idpkr").val()+"#");
        });

        $(document).on('click', '#save_pekerjaan2', function() {
            var main = [];
            $("#table_itempekerjaan2 .test").each(function () {

                var id_pekerjaan = $(this).find(".id_pekerjaan").val();
                var sub = [];
                $("#table_itempekerjaan2 ."+id_pekerjaan+" .test_child").each(function () {
                    var arr = [
                        $(this).find(".child_rab_sub_pekerjaan_id").val(),
                        $(this).find(".child_name").val(),
                        $(this).find(".child_volume").val(),
                        $(this).find(".list_satuan").val(),
                        $(this).find(".child_nilai").val(),
                        $(this).find(".child_total_nilai").val(),
                    ];

                    sub.push(arr);
                });
                console.log($(this).find(".total_nilai").val());
                if($(this).find(".total_nilai").val() != undefined){
                    var total_nilai = $(this).find(".total_nilai").val();
                }else{
                    var total_nilai = 0;
                }
                var arr2 = [
                        $(this).find(".id_rab_pekerjaan").val(),
                        $(this).find(".volume").val(),
                        $(this).find(".satuan").val(),
                        total_nilai,
                        sub,
                    ];
                main.push(arr2);

                // console.log(main);
            });
            
            var url = "{{ url('/')}}/rab/update-pekerjaan";
            $.ajax({
                type: 'post',
                dataType: 'json',
                url: url,
                data: {
                    data: JSON.stringify(main),
                    rab_id: $("#rab_id").val(),
                },
                beforeSend: function() {
                waitingDialog.show();
                },
                success: function(data) { 
                    window.location.reload(true);
                },
                complete: function() {
                waitingDialog.hide(); 
                },
            });
            // window.location.href;
            // window.location.replace(window.location.href);
            // window.location.replace("/rab/detail?id="+$("#rab_id").val()+"&idpkr="+$("#idpkr").val()+"#");
        });

        function closeRab(id){
            var request = $.ajax({
                url : "{{ url('/')}}/rab/closeRabTender",
            dataType : "json",
            data : {
                id : id
            },
            type : "post",
            beforeSend: function() {
            waitingDialog.show();
            },
            success: function(data) { 
                window.location.reload();
                waitingDialog.hide();
            }
            });
        }

        $(document).on('change', '#dokumen_lama', function() {
            var url = "{{ url('/')}}/rab/dokumen-lama";
            $.ajax({
                type: 'post',
                dataType: 'json',
                url: url,
                data: {
                    jenis_dokumen: $(this).val(),
                    id_pekerjaan: $("#idpkr").val(),
                },
                beforeSend: function() {
                waitingDialog.show();
                },
                success: function(data) { 
                    // window.location.reload(true);
                    var html = '';
                    var i;
                    html += '<option value="">Pilih No Dokumen</option></br>';
                    for(i=0; i<data.length; i++){
                        html += '<option value="'+data[i].id+'">'+data[i].no+'</option></br>';
                    }
                    $('#no_dokumenlama').html(html);   
                },
                complete: function() {
                waitingDialog.hide(); 
                },
            });
        });

        $(document).on('click', '#tambahkan_pekerjaan', function() {
            var url = "{{ url('/')}}/rab/tambah-pekerjaan-lama";
            $.ajax({
                type: 'post',
                dataType: 'json',
                url: url,
                data: {
                    jenis_dokumen: $("#dokumen_lama").val(),
                    dokumen: $("#no_dokumenlama").val(),
                    rab_id: $("#rab_id").val(),
                    id_pekerjaan: $("#idpkr").val(),
                },
                beforeSend: function() {
                    waitingDialog.show();
                },
                success: function(data) { 
                    window.location.reload(true);  
                },
                complete: function() {
                    waitingDialog.hide(); 
                },
            });
        });
        
    </script>
    @include("rab::app")
    <!-- Select2 -->

</body>

</html>