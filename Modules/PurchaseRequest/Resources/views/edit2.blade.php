<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin QS | Dashboard</title>
    @include("master/header")
    <link rel="stylesheet" href="{{ url('/')}}/assets/bower_components/select2/dist/css/select2.min.css">
    <style type="text/css">
        .table-align-right {
            text-align: right;
        }

        .panel-info>.panel-heading {
            color: white;
            background-color: #367fa9;
            border-color: #3c8dbc;
        }

        .panel-info {
            border-color: #3c8dbc;
        }

        select {
            background-color: white;
            width: 100%;
        }

        .content-header h1 {
            text-align: center;
        }

        .select2-selection {
            width: 100%
        }

        .table {
            overflow: auto;
        }

        /*    .modal{
        overflow-y:auto;
    }*/
    </style>
</head>

<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">

        @include("master/sidebar_project")

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1 style="text-align:center">Data Purchase Request Detail</h1>
            </section>
            <section class="back-button content-header">
                <div class="">
                    <button class="col-lg-1 col-md-2 col-sm-2 btn btn-primary" onclick="location.href='{{ url('/')}}/purchaserequest'" style="float: none; border-radius: 20px; padding-left: 0">
                        <i class="fa fa-fw fa-arrow-left"></i>&nbsp;&nbsp;Back
                    </button>
                    <button class="col-lg-1 col-md-2 col-sm-2 btn btn-primary" onclick="window.location.reload()" style="float: right; border-radius: 20px; padding-left: 0;">
                        <i class="fa fa-fw fa-refresh"></i>&nbsp;&nbsp;Refresh
                    </button>
                </div>
            </section>
            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="box">

                            <!-- /.box-header -->
                            <div class="box-body">

                                <div class="col-md-12">
                                    <div class="panel panel-success">
                                        <!-- Default panel contents -->
                                        <div class="panel-heading" style="height: 55px">
                                            <div class="col-md-10">
                                                Informasi PR Nomor : <strong>{{ $PRHeader->no }}</strong>
                                            </div>
                                            <div class="col-md-2">
                                                @if($PRHeader->approval->approval_action_id === 1)
                                                <form method="POST" action="{{ url('/')}}/purchaserequest/request_approval" name="form1" autocomplete="off">
                                                    <input type="" name="id" value="{{$PRHeader->id}}" hidden>
                                                    {!! csrf_field() !!}
                                                    <input type="submit" value="Request Approval" class="btn btn-primary pull-right">
                                                </form>
                                                <p />

                                                @endif
                                            </div>
                                            <p />
                                        </div>
                                        @if ($PRHeader->approval->approval_action_id <=> 6)
                                            <button type="button" class="btn btn-info btn-sm pull-left" data-toggle="modal" data-target="#myModaleditPR" style="margin:2px 15px">Edit PR</button>

                                            <div class="modal fade" id="myModaleditPR" role="dialog">

                                                <div class="modal-dialog modal-lg modal-md" style="width:80%;">

                                                    <!-- Modal content-->
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            <h4 class="modal-title">Tambah Detail</h4>
                                                        </div>
                                                        <div class="modal-body" id="modal" style="height: 70vh">
                                                            <form action="{{ url('/')}}/purchaserequest/editPR" method="post" name="form1" autocomplete="off">
                                                                {!! csrf_field() !!}
                                                                <input type="" name="id" value="{{$PRHeader->id}}" hidden>
                                                                <input type="" name="delivery_date" value="{{$PRHeader->butuh_date}}" hidden>
                                                                <input type="" name="department_id" value="{{$PRHeader->department_id}}" hidden>
                                                                <div class="form-group col-md-3">
                                                                    <label class="col-md-12" style="padding-left:0">Budget Tahunan</label>
                                                                    <select class="form-input col-md-12" list="data_department" name="budget_tahunan" id="budget_tahunan" autocomplete="off" placeholder="Pilih Budget Tahunan">
                                                                        <option value="" selected disabled>Pilih Budget Tahunan</option>
                                                                        @foreach($budget_no as $key => $v )
                                                                        <option value="{{ $v[1] }}" {{ $v[1]==$PRHeader->budget_tahunan_id ? 'selected' : '' }}>{{ $v[0]}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div id="form_waktu_dibutuhkan" class="form-group col-md-3">
                                                                    <label class="col-md-12" style="padding-left:0">Waktu dibutuhkan</label>
                                                                    <input class="form-input col-md-12" type="date" name="butuh_date" min="<?= $date ?>" value="{{ $PRHeader->butuh_date }}" style="padding-left:15px" required>
                                                                </div>
                                                                <div id="form_diskripsi_umum" class="form-group col-md-10">
                                                                    <label class="col-md-12" style="padding-left:0">Deskripsi Umum</label>
                                                                    <textarea name="deskripsi_umum" class="form-input col-md-12" required>{{ $PRHeader->description }}</textarea>
                                                                </div>
                                                                <div id="form_is_urgent" class="form-group col-md-2">
                                                                    <label class="col-md-12" style="padding-left:0">Mendadak (Urgent)</label>
                                                                    <div class="radio">
                                                                        <label><input type="radio" name="is_urgent" value="1">Ya</label>
                                                                    </div>
                                                                    <div class="radio">
                                                                        <label><input type="radio" name="is_urgent" value="0" checked>Tidak</label>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <input type="submit" value="Edit" class="btn btn-primary pull-right">
                                                                </div>
                                                            </form>
                                                        </div>

                                                    </div>
                                                </div>

                                            </div>
                                            @endif
                                            <br />
                                            <!-- List group -->

                                            <div class="panel-body">


                                                <div class="col-md-6">
                                                    <div class="panel-body">

                                                        <label>PR Info</label>
                                                    </div>
                                                    <ul class="list-group">

                                                        <li class="list-group-item" id="department_id" value="{{$PRHeader->department_id}}">Department : <strong>{{ $PRHeader->department->name }}</strong></li>
                                                        <li class="list-group-item">PT : <strong>{{ $PRHeader->pt->name }}</strong></li>
                                                        @if($PRHeader->approval->status->description == "approved")
                                                        <li class="list-group-item">Status : <strong style="color:green;">{{ strtoupper($PRHeader->approval->status->description) }}</strong></li>
                                                        @elseif($PRHeader->approval->status->description == "delivered")
                                                        <li class="list-group-item">Status : <strong style="color:orange;">{{ strtoupper($PRHeader->approval->status->description) }}</strong></li>
                                                        @elseif($PRHeader->approval->status->description == "partial approved")
                                                        <li class="list-group-item">Status : <strong style="color:#40E0D0;">{{ strtoupper($PRHeader->approval->status->description) }}</strong></li>
                                                        @elseif($PRHeader->approval->status->description == "open")
                                                        <li class="list-group-item">Status : <strong style="color:black;">{{ strtoupper($PRHeader->approval->status->description) }}</strong></li>
                                                        @elseif($PRHeader->approval->status->description == "rejected")
                                                        <li class="list-group-item">Status : <strong style="color:red;">{{ strtoupper($PRHeader->approval->status->description) }}</strong></li>
                                                        @endif
                                                        <li class="list-group-item">Tanggal PR Dibuat : <strong>{{ $PRHeader->date }}</strong></li>
                                                        <li class="list-group-item">Tanggal Dibutuhkan : <strong>{{ $PRHeader->butuh_date }}</strong></li>
                                                    </ul>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="panel-body">
                                                        <label>Budget Info</label>
                                                    </div>
                                                    <ul class="list-group">
                                                        <li class="list-group-item">Nomor Budget : <strong>{{ $PRHeader->budget->no or "kosong"}}</strong></li>
                                                        <li class="list-group-item">Tahun Budget : <strong>{{ $PRHeader->budget->tahun_anggaran or "kosong"}}</strong></li>
                                                        <li class="list-group-item">Deskripsi Budget : <strong>{{ $PRHeader->budget->description or 'Kosong' }}</strong></li>

                                                        <li class="list-group-item">Sisa Budget Sebelum : <strong>{{ $total or 'Kosong' }}</strong></li>
                                                        <li class="list-group-item">Pengguna Budget Terakhir : <strong>{{ $pengguna_terakhir->department->name or 'Kosong' }}</strong></li>
                                                        <li class="list-group-item">Jumlah Digunakan terakhir untuk SPK/PO: <strong>{{$totalTerakhir}} </strong></li>
                                                    </ul>
                                                </div>
                                                @if ($PRHeader->approval->approval_action_id <=> 6)
                                                    <button type="button" class="tambah-detail btn btn-info btn-sm pull-left" data-toggle="modal">tambah Item</button>

                                                    @endif
                                            </div>
                                    </div>
                                </div>
                            </div>
                            @if($approve)
                            <div class="row" style="padding-bottom: 20px;margin: 0px 15px">
                                <a href="{{ url('/')}}/purchaserequest/approve/?id={{$pr_id}}&type=approveAll" class="btn btn-success col-md-1 col-md-offset-10">Approve All</a>
                                <a href="{{ url('/')}}/purchaserequest/approve/?id={{$pr_id}}&type=cancelAll" class="btn btn-danger" style="width:7%;margin-left: 1%">Cancel All</a>
                            </div>
                            @endif
                            <table id="table_details" class="table table-bordered table-hover">
                                <thead style="background-color: greenyellow;">
                                    <tr>
                                        <th rowspan="2">Category</th>
                                        <th rowspan="2">Item Pekerjaan</th>
                                        <th rowspan="2">Item</th>
                                        <th rowspan="2">Kode Item</th>
                                        <th rowspan="2">Brand</th>
                                        <th rowspan="2">Qty</th>
                                        <th rowspan="2">Satuan</th>
                                        <th colspan="3" class="text-center">Rekomendasi Supplier</th>
                                        <th rowspan="2">Deskripsi</th>
                                        <th rowspan="2">SPK</th>
                                        <th rowspan="2">Status</th>
                                        @if($approve)
                                        <th rowspan="2">Action</th>
                                        @endif
                                        <th rowspan="2"></th>
                                        <th rowspan="2"></th>
                                    </tr>
                                    <tr>
                                        <th>Supplier 1</th>
                                        <th>Supplier 2</th>
                                        <th>Supplier 3</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php ($i=0)
                                    @foreach($PR as $key => $value )
                                    @php ($i++)
                                    <tr>

                                        <td>{{is_null($value->item_project->item->sub_category) ? $value->item_project->item->category->name : $value->item_project->item->sub_category->name}}</td>
                                        <td>{{ $value->item_pekerjaan->code }} - {{$value->item_pekerjaan->name or 'Kosong'}}</td>
                                        <td>{{$value->item_project->item->name or 'Kosong'}}</td>
                                        <td>{{$value->item_project->item->kode or 'Kosong'}}</td>
                                        <td>{{$value->brand->name or 'Kosong'}}</td>
                                        <td class="table-align-right">{{$value->quantity}}</td>
                                        <td>{{$value->item_satuan->name or 'Kosong'}}</td>
                                        <td>{{$value->rec1->name or 'Kosong'}}</td>
                                        <td>{{$value->rec2->name or 'Kosong'}}</td>
                                        <td>{{$value->rec3->name or 'Kosong'}}</td>
                                        <td>{{$value->description}}</td>
                                        <td>{{$value->spk->no or 'kosong'}}</td>
                                        @if($value->approval->status->description == "approved")
                                        <td><strong style="color:green;">{{ strtoupper($value->approval->status->description) }}</strong></td>
                                        @elseif($value->approval->status->description == "delivered")
                                        <td><strong style="color:orange;">{{ strtoupper($value->approval->status->description) }}</strong></td>
                                        @elseif($value->approval->status->description == "partial approved")
                                        <td><strong style="color:#40E0D0;">{{ strtoupper($value->approval->status->description) }}</strong></td>
                                        @elseif($value->approval->status->description == "open")
                                        <td><strong style="color:black;">{{ strtoupper($value->approval->status->description) }}</strong></td>
                                        @elseif($value->approval->status->description == "rejected")
                                        <td><strong style="color:red;">{{ strtoupper($value->approval->status->description) }}</strong></td>
                                        @endif
                                        @if($approve)
                                        @if($value->approval->approval_action_id == 6)
                                        <td><a href="{{ url('/')}}/purchaserequest/approve/?id={{$value->id}}&type=cancel&pr_id={{$value->purchaserequest_id}}" class="btn btn-danger col-md-12">UnApprove</a></td>
                                        @else
                                        <td><a href="{{ url('/')}}/purchaserequest/approve/?id={{$value->id}}&type=approve&pr_id={{$value->purchaserequest_id}}" class="btn btn-success col-md-12">Approve</a><a href="{{ url('/')}}/purchaserequest/approve/?id={{$value->id}}&type=approve&pr_id={{$value->purchaserequest_id}}" class="btn btn-danger col-md-12">Reject</a></td>
                                        @endif
                                        @endif
                                        <td>@if ($value->approval->approval_action_id <=> 6)
                                                <?php
                                                $parentCoa = DB::table('itempekerjaans')->where("id", "$value->itempekerjaan_id")->select("*")->first();
                                                ?>
                                                <button class="edit-modal btn btn-info" data-id="{{$value->id}}" data-parentcategory="{{$value->item_project->item->item_category_id}}" data-category="{{$value->item_project->item->sub_item_category_id}}" data-item="{{$value->item_id}}" data-brand="{{$value->brand_id}}" data-kuantitas="{{$value->quantity}}" data-satuan="{{$value->item_satuan_id}}" data-komparasi="{{$value->recomended_supplier}}" data-rec1="{{$value->rec_1}}" data-rec2="{{$value->rec_2}}" data-rec3="{{$value->rec_3}}" data-coa="{{$value->itempekerjaan_id}}" data-deskripsi="{{$value->description}}" data-SPK="{{$value->spk->id or '0'}}" data-harga="{{$value->harga_estimasi}}" , data-headerCoa="{{$parentCoa->parent_id}}">

                                                    <span class="glyphicon glyphicon-edit"></span> Edit</button>
                                                @endif


                                        </td>
                                        <td>@if ($value->approval->approval_action_id <=> 6)
                                                <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#myModaldelete{{$i}}">Delete</button>

                                                <!-- Modal -->
                                                <div class="modal fade" id="myModaldelete{{$i}}" role="dialog">
                                                    <div class="modal-dialog">

                                                        <!-- Modal content-->
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                <h4 class="modal-title">Delete</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p>Are you sure you wish to delete 1 row?</p>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-default btn-xs" data-dismiss="modal">Tutup</button>
                                                                <a type="submit" href="{{ url('/')}}/purchaserequest/delete_detail/?id={{$value->id}}&&PR={{$PRHeader->id}}" class="btn btn-danger pull-right btn-xs btn-delete" data-value="{{ $value->id }}"><i class="fa fa-times"></i> Hapus</a>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                                @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <!-- <tfoot>
                      <tr>
                  <th rowspan="2">Category</th>
                  <th rowspan="2" >Item Pekerjaan</th>
                  <th rowspan="2" >Item</th>
                  <th rowspan="2" >Kode Item</th>
                  <th rowspan="2" >Brand</th>
                  <th rowspan="2" >Qty</th>
                  <th rowspan="2" >Satuan</th> -->
                                <!-- <th colspan="3" class="text-center">Rekomendasi Supplier</th> -->
                                <!--  <th rowspan="2">Deskripsi</th>
                  <th rowspan="2">Status</th>
                  @if($approve)
                  <th rowspan="2">Action</th>
                  @endif
                  <th rowspan="2"></th>
                  <th rowspan="2"></th>
                </tr>
                  <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                  </tr>
                  </tfoot> -->
                            </table>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->

                <!--         </div>
        /.col
      </div> -->
                <!-- /.row -->
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

    <div class="modal fade" id="myModaltambah" role="dialog">
        <div class="modal-dialog modal-lg modal-md" style="width:80%;">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" onclick="window.location.reload()">&times;</button>
                    <h4 class="modal-title">Tambah Detail</h4>
                </div>
                <div class="modal-body" id="modal" style="height: auto">
                    <form action="{{ url('/')}}/purchaserequest/tambah" method="post" name="form1" autocomplete="off">
                        {!! csrf_field() !!}
                        <input type="" name="id" value="{{$PRHeader->id}}" hidden>
                        <input type="" name="delivery_date" value="{{$PRHeader->butuh_date}}" hidden>
                        <input type="" name="department_id" value="{{$PRHeader->department_id}}" hidden>
                        <div id="list_item" class="col-md-12">
                            <div id="list_item" class="col-md-12">
                                <div class="sub_list_item form-group col-md-12 panel panel-info">
                                    <!-- <div class="form-group panel-heading"> Item 1 </div> -->
                                    <div class="col-md-12">
                                        <div class="form-group col-md-4">
                                            <label class="col-md-12" style="padding-left:0;width: 100%">Kategori</label>
                                            <div class="col-md-12" style="padding:0;width: 100%">
                                                <div class="col-md-10" style="padding:0;">
                                                    <select class="form-control col-md-12 parentcategory_data" name="parentcategory_name[]" id="parent_category_1 parentcategory_name" placeholder="Pilih Item" style="width: 100%" required>
                                                        <option value="0">All Kategori</option>
                                                        @foreach($parent_categories as $key => $value)
                                                        <option data-value="{{ $value['items'] }}" value="{{ $value->id }}">{{ $value->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-2" style="padding:0;">
                                                    <div class="col-md-11" style="padding: 0;width: 100%">
                                                        <button type="button" class="btn btn-info btn-sm tambah_parent_kategori" data-toggle="modal_tambah_parent_kategori" data-target="#myModalTambahParentKategori" style=""><i class="fa fa-plus"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label class="col-md-12" style="padding-left:0;width: 100%">Sub Kategori</label>
                                            <div class="col-md-12" style="padding:0;width: 100%">
                                                <div class="col-md-10" style="padding:0;">
                                                    <select class="form-control col-md-12 category_data" name="category_name[]" id="sub_category_1 category_name" placeholder="Pilih Item" style="width: 100%" required>
                                                        <option value="0">All Sub Kategori</option>
                                                        @foreach($categories as $key => $value)
                                                        <option data-value="{{ $value['items'] }}" value="{{ $value->id }}">{{ $value->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-2" style="padding:0;">
                                                    <div class="col-md-11" style="padding: 0;width: 100%">
                                                        <button type="button" class="btn btn-info btn-sm tambah_kategori" data-toggle="modal_tambah_kategori" data-target="#myModalTambahKategori" style=""><i class="fa fa-plus"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label class="col-md-12" style="padding-left:0;width: 100%">Item</label>
                                            <div class="col-md-12" style="padding:0;width: 100%">
                                                <div class="col-md-10" style="padding:0;">
                                                    <select class="form-control col-md-12 item_data" name="item[]" id="item_id_1 item_id" placeholder="Pilih Item" style="width: 100%" required>
                                                        <option value="0">All Item</option>
                                                        @foreach($item_result as $key => $value)
                                                        <option data-value="{{ $value['category'] }}" value="{{ $value['id'] }}">{{ $value['name'] }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-2" style="padding:0;">
                                                    <div class="col-md-11" style="padding: 0;width: 100%">
                                                        <button type="button" class="btn btn-info btn-sm tambah_item" data-toggle="modal_tambah_item" data-target="#myModalTambahItem" style=""><i class="fa fa-plus"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group col-md-4">
                                            <label class="col-md-12" style="padding-left:0">Brand</label>
                                            <div class="col-md-12" style="padding:0;width: 100%">
                                                <div class="col-md-10" style="padding:0;">
                                                    <select class="col-md-12 form-control brand_id" id="brand_1 brand_id" list="data_brand" name="brand[]" style="width: 100%" autocomplete="off" placeholder="Pilih Brand" required>
                                                    </select>
                                                </div>
                                                <div class="col-md-2" style="padding:0;">
                                                    <div class="col-md-11" style="padding: 0;width: 100%">
                                                        <button type="button" class="btn btn-info btn-sm tambah_brand" data-toggle="modal_tambah_brand" data-target="#myModalTambahBrand" style=""><i class="fa fa-plus"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label class="col-md-12" style="padding-left:0">Qty</label>
                                            <input id="kuantitas1" name="kuantitas[]" type="number" class="kuantitas form-input col-md-12" placeholder="Input" style="width: 100%" min="1" required>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label class="col-md-12" style="padding-left:0">Satuan</label>
                                            <div class="col-md-12" style="padding:0;width: 100%">
                                                <div class="col-md-10" style="padding:0;">
                                                    <select id="satuan_item1" name="satuan[]" class="form-input col-md-12 satuan_item" style="width: 100%" required>
                                                    </select>
                                                </div>
                                                <div class="col-md-2" style="padding:0;">
                                                    <div class="col-md-11" style="padding: 0;width: 100%">
                                                        <button type="button" class="btn btn-info btn-sm tambah_satuan" data-toggle="modal_tambah_satuan" data-target="#myModalTambahSatuan" style=""><i class="fa fa-plus"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group col-md-4">
                                            <label class="col-md-12" style="padding-left:0">Repeat Order</label>
                                            <select id="repeat_order1" name="repeat_order[]" class="repeat_order form-input col-md-12" style="width: 100%">
                                                <option value="1" selected>Buat Baru</option>
                                                <option value="2">Repeat Order</option>
                                            </select>
                                        </div>

                                        <div class="po form-group col-md-4" hidden="">
                                            <label class="col-md-12" style="padding-left:0">Purchase Order</label>
                                            <select id="purchase_order1" name="purchase_order[]" class="purchase_order form-input col-md-12" style="width: 100%">
                                            </select>
                                        </div>


                                    </div>

                                    <div class="col-md-12">
                                        <div id="form_jumlah_komparasi_supplier" class="form-group col-md-12 ">
                                            <label class="col-md-12" style="padding-left:0">Jumlah Komparasi Supplier</label>
                                            <select id="jumlah_komparasi1" name="j_komparasi[]" class="form-input jumlah_komparasi1 col-md-12 komparasi" style="width: 100%" onchange="banyak_komparasi(1)" required>
                                                <option value="-1" selected disabled>Pilih jumlah suplier (1 - 3)</option>
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div id="" class="form_komparasi_supplier_1_item1 form-group" hidden>
                                            <label class="col-md-12" style="padding-left:0">Komparasi Supplier 1</label>
                                            <select id="supplier1_1 komparasi_supplier_1 " name="komparasi_supplier1[1]" class="form-input col-md-12 supplier1" style="width: 100%" onchange="recomended_supplier(this.value, this.options[this.selectedIndex].text,1,1)" required>
                                                <option selected disabled>Pilih Supplier 1</option>
                                                @foreach($rekanan_group as $key => $value )
                                                <option value="{{ $value->id }}">{{ $value->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div id="" class="form_komparasi_supplier_2_item1 form-group" hidden>
                                            <label class="col-md-12" style="padding-left:0">Komparasi Supplier 2</label>
                                            <select id=" supplier2_1 komparasi_supplier_2" name="komparasi_supplier2[1]" class="form-input col-md-12 supplier2" style="width: 100%" onchange="recomended_supplier(this.value, this.options[this.selectedIndex].text,2,1)">
                                                <option selected disabled>Pilih Supplier 2</option>
                                                @foreach($rekanan_group as $key => $value )
                                                <option value="{{ $value->id }}">{{ $value->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div id="" class="form_komparasi_supplier_3_item1 form-group" hidden>
                                            <label class="col-md-12" style="padding-left:0">Komparasi Supplier 3</label>
                                            <select id="supplier3_1 komparasi_supplier_3" name="komparasi_supplier3[1]" class="form-input col-md-12 supplier3" style="width: 100%" onchange="recomended_supplier(this.value, this.options[this.selectedIndex].text,3,1)">
                                                <option selected disabled>Pilih Supplier 3</option>
                                                @foreach($rekanan_group as $key => $value )
                                                <option value="{{ $value->id }}">{{ $value->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    @if($PRHeader->budget_tahunan_id == 0)
                                    <div class="col-md-12 coaNonBudget" id="coaNonBudget1">
                                        <div class="form-group col-md-6">
                                            <label class="col-md-12" style="padding-left:0">Kode Coa | Item Pekerjaan</label>
                                            <select id="coaHeader1" class="coaHeader form-input col-md-12" name="" style="width: 100%" placeholder="Pilih Item Pekerjaan" required="">
                                                <option selected disabled>Pilih Item Pekerjaan</option>
                                                @foreach($coaHeader as $key => $value )
                                                <option value="{{ $value->id }}">{{ $value->name}} | {{$value->code}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="col-md-12" style="padding-left:0">Kode Coa | Item Pekerjaan detail</label>
                                            <select id="coaDetail1" class="coaDetail form-input col-md-12" name="coa2[]" style="width: 100%" placeholder="Pilih Item Pekerjaan">
                                            </select>
                                        </div>
                                    </div>
                                    @else
                                    <div class="col-md-12">
                                        <div class="form-group col-md-12">
                                            <label class="col-md-12" style="padding-left:0">Kode Coa | Item Pekerjaan</label>
                                            <select id="data_itempekerjaan1" class="data_itempekerjaan form-input col-md-12" name="coa[]" style="width: 100%" placeholder="Pilih Item Pekerjaan" required>
                                                @foreach($coa as $key => $v )
                                                <option value="{{ $v->id }}" {{ $v->id == $PRHeader->budget->itempekerjaan_id ? 'selected' : ''}}>{{ $v->name}} | {{$v->code}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    @endif

                                    <div class="col-md-12">
                                        <div id="form_deskripsi_umum" class="form-group col-md-12">
                                            <label class="col-md-12" style="padding-left:0">Spesfikasi</label>
                                            <textarea id="deskripsi_item1" style="max-width: 1100px" name="deskripsi_item[]" class="form-input col-md-12 item_desk" required=""></textarea>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group col-md-4">
                                            <label class="col-md-12" style="padding-left:0">SPK</label>
                                            <select class="spk_data form-control col-md-12" name="spk[]" id="spk1" placeholder="Pilih SPK" style="width: 100%" required>
                                                <option value="0">All SPK</option>
                                                @foreach($department_spk->spk as $key => $v )
                                                    <option value="{{$v['id']}}">{{ $v['name']}} | {{$v['no']}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label class="col-md-12" style="padding-left:0">Harga Estimasi</label>
                                            <input type="number" name="harga_estimasi[]" class="harga_estimasi form-control" id="harga_estimasi1" value="">
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label class="col-md-12" style="padding-left:0">Total Harga Estimasi</label>
                                            <input type="text" name="total_harga_estimasi[]" class="total_harga_estimasi form-control" id="total_harga_estimasi1" value="" readonly="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" id="close" class="btn btn-default" data-dismiss="modal" onclick="window.location.reload()">Tutup</button>
                            <input type="submit" value="tambah" class="btn btn-primary pull-right">
                        </div>
                    </form>
                </div>

            </div>
        </div>

    </div>


    <div class="modal fade" id="editModal" role="dialog">

        <div class="modal-dialog modal-lg modal-md" style="width:80%;">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" onclick="window.location.reload()">&times;</button>
                    <h4 class="modal-title">Edit Detail</h4>
                </div>
                <div class="modal-body" id="modal{{$i}}">
                    <form action="{{ url('/')}}/purchaserequest/edit_pr" method="post" name="form1" autocomplete="off">
                        {!! csrf_field() !!}
                        <input type="" name="id" value="{{$PRHeader->id}}" hidden>
                        <input type="" name="department_id" value="{{$PRHeader->department_id}}" hidden>
                        <!--       <input type="" name="details_id" value="{{$PRHeader->id}}" hidden>
 -->
                        <div id="list_item" class="col-md-12">
                            <div class="sub_list_item form-group col-md-12 panel panel-info">
                                <input id="details_id" type="" name="details_id[]" value="" hidden>

                                <div class="col-md-12">
                                    <div class="form-group col-md-4">
                                        <label class="col-md-12" style="padding-left:0;width: 100%">Kategori</label>
                                        <div class="col-md-12" style="padding:0;width: 100%">
                                            <div class="col-md-10" style="padding:0;">
                                                <select class="form-control col-md-12 parentcategory_data" name="parentcategory_name[]" id="parentcategory_name" placeholder="Pilih Item" style="width: 100%" required>
                                                    <option value="0">All Kategori</option>
                                                    @foreach($parent_categories as $key => $v)
                                                    <option value="{{ $v->id }}">{{ $v->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-2" style="padding:0;">
                                                <div class="col-md-11" style="padding: 0;width: 100%">
                                                    <button type="button" class="btn btn-info btn-sm tambah_parent_kategori" data-toggle="modal_tambah_parent_kategori" data-target="#myModalTambahParentKategori" style=""><i class="fa fa-plus"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label class="col-md-12" style="padding-left:0;width: 100%">Sub Kategori</label>
                                        <div class="col-md-12" style="padding:0;width: 100%">
                                            <div class="col-md-10" style="padding:0;">
                                                <select class="form-control col-md-12 category_data" name="category_name[]" id="category_name" placeholder="Pilih Item" style="width: 100%" required>
                                                    <option value="0">All Sub Kategori</option>
                                                    @foreach($categories as $key => $v)
                                                    <option value="{{ $v->id }}">{{ $v->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-2" style="padding:0;">
                                                <div class="col-md-11" style="padding: 0;width: 100%">
                                                    <button type="button" class="btn btn-info btn-sm tambah_kategori" data-toggle="modal_tambah_kategori" data-target="#myModalTambahKategori" style=""><i class="fa fa-plus"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label class="col-md-12" style="padding-left:0;width: 100%">Item</label>
                                        <div class="col-md-12" style="padding:0;width: 100%">
                                            <div class="col-md-10" style="padding:0;">
                                                <select class="form-control col-md-12 item_data" name="item[]" id="item_name" placeholder="Pilih Item" style="width: 100%" required>
                                                    <option value="0">All Item</option>
                                                    @foreach($item_result as $key => $v)
                                                    <option value="{{ $v['id'] }}">{{ $v['name'] }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-2" style="padding:0;">
                                                <div class="col-md-11" style="padding: 0;width: 100%">
                                                    <button type="button" class="btn btn-info btn-sm tambah_item" data-toggle="modal_tambah_item" data-target="#myModalTambahItem" style=""><i class="fa fa-plus"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group col-md-4">
                                        <label class="col-md-12" style="padding-left:0">Brand</label>
                                        <div class="col-md-12" style="padding:0;width: 100%">
                                            <div class="col-md-10" style="padding:0;">
                                                <select class="col-md-12 form-control brand_id" id="brand_idForm" list="data_brand" name="brand[]" placeholder="Pilih Brand" style="width: 100%" required>
                                                    <option value="0">All Brand</option>
                                                    @foreach($brand as $key => $v)
                                                    <option value="{{ $v->id }}">{{ $v->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-2" style="padding:0;">
                                                <div class="col-md-11" style="padding: 0;width: 100%">
                                                    <button type="button" class="btn btn-info btn-sm tambah_brand" data-toggle="modal_tambah_brand" data-target="#myModalTambahBrand" style=""><i class="fa fa-plus"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label class="col-md-12" style="padding-left:0">Qty</label>
                                        <input id="kuantitas" name="kuantitas[]" type="number" value="" class="kuantitas form-input col-md-12" placeholder="Input" min="1" required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label class="col-md-12" style="padding-left:0">Satuan</label>
                                        <div class="col-md-12" style="padding:0;width: 100%">
                                            <div class="col-md-10" style="padding:0;">
                                                <select id="satuan_item" name="satuan[]" class="form-input col-md-12 satuan_item" style="width: 100%" required>
                                                    @foreach($item_satuan as $key => $v)
                                                    <option value="{{ $v->id }}">{{ $v->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-2" style="padding:0;">
                                                <div class="col-md-11" style="padding: 0;width: 100%">
                                                    <button type="button" class="btn btn-info btn-sm tambah_satuan" data-toggle="modal_tambah_satuan" data-target="#myModalTambahSatuan" style=""><i class="fa fa-plus"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div id="form_jumlah_komparasi_supplier" class="form-group col-md-12 ">
                                        <label class="col-md-12" style="padding-left:0">Jumlah Komparasi Supplier</label>
                                        <select id="komparasi" name="j_komparasi[]" class="form-input jumlah_komparasi2 col-md-12" onchange="banyak_komparasi(2)" style="width: 100%" required>
                                            @for ($j = 1; $j < 4; $j++) <option value="{{$j}}">{{$j}}</option>
                                                @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div id="" class="form_komparasi_supplier_1_item2 form-group" hidden>
                                        <label class="col-md-12" style="padding-left:0">Komparasi Supplier 1</label>
                                        <select id="supplier_1" name="komparasi_supplier2_1[]" class="form-input col-md-12" onchange="recomended_supplier(this.value, this.options[this.selectedIndex].text,1,2)" style="width: 100%" required>
                                            <option selected disabled>Pilih Komparasi Supplier 1</option>
                                            @foreach($rekanan_group as $key => $v )
                                            <option value="{{ $v->id }}">{{ $v->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div id="" class="form_komparasi_supplier_2_item2 form-group" hidden>
                                        <label class="col-md-12" style="padding-left:0">Komparasi Supplier 2</label>
                                        <select id="supplier_2" name="komparasi_supplier2_2[]" class="form-input col-md-12 supplier2" style="width: 100%" onchange="recomended_supplier(this.value, this.options[this.selectedIndex].text,2,1)">
                                            <option selected disabled>Pilih Supplier 2</option>
                                            @foreach($rekanan_group as $key => $value )
                                            <option value="{{ $value->id }}">{{ $value->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div id="" class="form_komparasi_supplier_3_item2 form-group" hidden>
                                        <label class="col-md-12" style="padding-left:0">Komparasi Supplier 3</label>
                                        <select id="supplier_3" name="komparasi_supplier2_3[]" class="form-input col-md-12" onchange="recomended_supplier(this.value, this.options[this.selectedIndex].text,3,2)" style="width: 100%">
                                            <option selected disabled>Pilih Komparasi Supplier 3</option>
                                            @foreach($rekanan_group as $key => $v )
                                            <option value="{{ $v->id }}">{{$v->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @if($PRHeader->budget_tahunan_id == 0)
                                <div class="col-md-12 coaNonBudget" id="coaNonBudget1">
                                    <div class="form-group col-md-6">
                                        <label class="col-md-12" style="padding-left:0">Kode Coa | Item Pekerjaan</label>
                                        <select id="coaHeader" class="coaHeader form-input col-md-12" name="" style="width: 100%" placeholder="Pilih Item Pekerjaan" required="">
                                            <option selected disabled>Pilih Item Pekerjaan</option>
                                            @foreach($coaHeader as $key => $value )
                                            <option value="{{ $value->id }}">{{ $value->name}} | {{$value->code}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="col-md-12" style="padding-left:0">Kode Coa | Item Pekerjaan detail</label>
                                        <select id="coaDetail" class="coaDetail form-input col-md-12" name="coa2[]" style="width: 100%" placeholder="Pilih Item Pekerjaan">
                                            @foreach($coaChild as $key => $v )
                                            <option value="{{ $v->id }}">{{ $v->name}} | {{$v->code}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @else
                                <div class="col-md-12 coaBudget">
                                    <div class="form-group col-md-12">
                                        <label class="col-md-12" style="padding-left:0">Kode Coa | Item Pekerjaan</label>
                                        <select id="data_itempekerjaan" class="data_itempekerjaan form-input col-md-12" name="coa[]" placeholder="Pilih Item Pekerjaan" style="width: 100%" required>
                                            @foreach($coa as $key => $v )
                                            <option value="{{ $v->id }}">{{ $v->name}} | {{$v->code}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @endif

                                <div class="col-md-12">
                                    <div id="form_deskripsi_umum" class="form-group col-md-12">
                                        <label class="col-md-12" style="padding-left:0">Spesfikasi</label>
                                        <textarea id="deskripsi_item" name="deskripsi_item[]" class="form-input col-md-12 item_desk" required>{{ $value->description }}</textarea>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group col-md-4">
                                        <label class="col-md-12" style="padding-left:0">SPK</label>
                                        <select class="spk_data form-control col-md-12" name="spk[]" id="spk" placeholder="Pilih SPK" style="width: 100%" required>
                                            <option value="0">All SPK</option>
                                            @foreach($department_spk->spk as $key => $v )
                                                <option value="{{$v['id']}}">{{ $v['name']}} | {{$v['no']}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label class="col-md-12" style="padding-left:0">Harga Estimasi</label>
                                        <input type="number" name="harga_estimasi[]" class="harga_estimasi form-control" id="harga_estimasi">
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label class="col-md-12" style="padding-left:0">Total Harga Estimasi</label>
                                        <input type="text" name="total_harga_estimasi[]" class="total_harga_estimasi form-control" id="total_harga_estimasi" value="" readonly="">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal" onclick="window.location.reload()">Tutup</button>
                            <input type="submit" value="Ubah" class="btn btn-primary pull-right">
                        </div>
                    </form>
                </div>

            </div>
        </div>

    </div>

    <div class="modal fade" id="editModalParentKategori" role="dialog">
        <div class="modal-dialog modal-lg modal-md" style="width:30%;">
            <!-- Modal content-->
            <div class="modal-content center">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Tambah Kategori</h4>
                </div>
                <div class="modal-body" id="">
                    <div id="list_item" class="col-md-12">
                        <div class="form-group col-md-12 panel panel-info">
                            <div id="form_tambah_kategori" class="form-group col-md-12" style="margin-bottom:10px">
                                <label class="control-label col-md-12" style="padding-left:0">Nama Kategori</label>
                                <input type='text' id='input_parent_kategori' name='input_parent_kategori' class='input_parent_kategori form-control' />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary pull-right tambah_input_parent_kategori"> Tambah</button>

                        <label id="labelParentKategori" class="col-md-12" style="width: 100%;margin-top: 10px;background: red;text-align: center;color: white" hidden="">Kategori sudah ada</label>
                        <table class="tableDataSinonim table table-striped table-bordered" style="width: 100%;margin-top: 10px">
                            <thead>
                                <tr>
                                    <th style="width: 10px;text-align: center;">no</th>
                                    <th style="text-align: center;">Name</th>
                                </tr>
                            </thead>
                        </table>
                        <!-- <button type="submit" id="btn-submit" class="btn btn-primary" >Simpan </button>          -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModalKategori" role="dialog">
        <div class="modal-dialog modal-lg modal-md" style="width:30%;">
            <!-- Modal content-->
            <div class="modal-content center">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Tambah SubKategori</h4>
                </div>
                <div class="modal-body" id="">
                    <div id="list_item" class="col-md-12">
                        <div class="form-group col-md-12 panel panel-info">
                            <div id="form_tambah_kategori" class="form-group col-md-12" style="margin-bottom:10px">
                                <label class="col-md-12" style="padding-left:0">Kategori</label>
                                <div class="col-md-12" style="padding:0;">
                                    <select class="col-md-12 form-control tambah_kategori" id="input_kategori_subkategori" list="input_kategori_subkategori" name="input_kategori_subkategori" style="width: 100%" autocomplete="off" placeholder="Pilih Kategori" required>
                                        <option value="0">All Kategori</option>
                                    </select>
                                </div>

                                <label class="control-label col-md-12" style="padding-left:0">SubKategori</label>
                                <div class="col-md-12" style="padding:0;">
                                    <input type='text' id='input_subkategori' name='input_kategori' class='form-control input_kategori' />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary pull-right tambah_input_subkategori"> Tambah</button>

                        <label id="labelKategori" class="col-md-12" style="width: 100%;margin-top: 10px;background: red;text-align: center;color: white" hidden="">Kategori sudah ada</label>
                        <table class="tableDataSinonim table table-striped table-bordered" style="width: 100%;margin-top: 10px">
                            <thead>
                                <tr>
                                    <th style="width: 10px;text-align: center;">no</th>
                                    <th style="text-align: center;">Name</th>
                                </tr>
                            </thead>
                        </table>
                        <!-- <input type="submit" value="Tambah" class="btn btn-primary pull-right tambah_input_kategori">               -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModalItem" role="dialog">
        <div class="modal-dialog modal-lg modal-md" style="width:30%;">
            <!-- Modal content-->
            <div class="modal-content center">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Tambah Item</h4>
                </div>
                <div class="modal-body" id="">
                    <div id="list_item" class="col-md-12">
                        <div class="form-group col-md-12 panel panel-info">
                            <div id="form_tambah_kategori" class="form-group col-md-12" style="margin-bottom:10px">
                                <label class="col-md-12" style="padding-left:0">Kategori</label>
                                <div class="col-md-12" style="padding:0;">
                                    <select class="col-md-12 form-control" id="input_kategori_item" list="input_p_kategori" name="" style="width: 100%" autocomplete="off" placeholder="Pilih Kategori" required>
                                    </select>
                                </div>

                                <label class="col-md-12" style="padding-left:0">SubKategori</label>
                                <div class="col-md-12" style="padding:0;">
                                    <select class="col-md-12 form-control" id="input_subkategori_item" list="input_p_kategori" name="" style="width: 100%" autocomplete="off" placeholder="Pilih Kategori" required>
                                    </select>
                                </div>

                                <label class="col-md-12" style="padding-left:0">Nama Barang</label>
                                <div class="col-md-12" style="padding:0;">
                                    <input type='text' id='input_item_item' name='name' class='form-control' />
                                </div>

                                <label class="col-md-12" style="padding-left:0">Kode Barang</label>
                                <div class="col-md-12" style="padding:0;">
                                    <input type='text' id='input_kode_item' name='name' class='form-control' />
                                </div>

                                <label class="col-md-12" style="padding-left:0">Deskripsi</label>
                                <div class="col-md-12" style="padding:0;">
                                    <textarea class='form-control' name="description" id="input_description_item" cols="45" rows="5" style="max-width: 280px"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary pull-right tambah_input_item"> Tambah</button>

                        <label id="labelItem" class="col-md-12" style="width: 100%;margin-top: 10px;background: red;text-align: center;color: white" hidden="">Kategori sudah ada</label>
                        <table class="tableDataSinonim table table-striped table-bordered" style="width: 100%;margin-top: 10px">
                            <thead>
                                <tr>
                                    <th style="width: 10px;text-align: center;">no</th>
                                    <th style="text-align: center;">Name</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModalBrand" role="dialog">
        <div class="modal-dialog modal-lg modal-md" style="width:30%;">
            <!-- Modal content-->
            <div class="modal-content center">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Tambah Brand</h4>
                </div>
                <div class="modal-body" id="">
                    <div id="list_item" class="col-md-12">
                        <div class="form-group col-md-12 panel panel-info">
                            <div id="form_tambah_kategori" class="form-group col-md-12" style="margin-bottom:10px">
                                <label class="col-md-12" style="padding-left:0">Kategori</label>
                                <div class="col-md-12" style="padding:0;">
                                    <select class="col-md-12 form-control" id="input_kategori_brand" list="input_kategori_brand" name="" style="width: 100%" autocomplete="off" placeholder="Pilih Kategori" required>
                                    </select>
                                </div>

                                <label class="col-md-12" style="padding-left:0">SubKategori</label>
                                <div class="col-md-12" style="padding:0;">
                                    <select class="col-md-12 form-control" id="input_subkategori_brand" list="input_subkategori_brand" name="" style="width: 100%" autocomplete="off" placeholder="Pilih Kategori" required>
                                    </select>
                                </div>

                                <label class="col-md-12" style="padding-left:0">Nama Brand</label>
                                <div class="col-md-12" style="padding:0;">
                                    <input type='text' id='input_brand_brand' name='name' class='form-control' />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary pull-right tambah_input_brand"> Tambah</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModalSatuan" role="dialog">
        <div class="modal-dialog modal-lg modal-md" style="width:30%;">
            <!-- Modal content-->
            <div class="modal-content center">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Tambah Satuan</h4>
                </div>
                <div class="modal-body" id="">
                    <div id="list_item" class="col-md-12">
                        <div class="form-group col-md-12 panel panel-info">
                            <div id="form_tambah_kategori" class="form-group col-md-12" style="margin-bottom:10px">
                                <label class="col-md-12" style="padding-left:0">Kategori</label>
                                <div class="col-md-12" style="padding:0;">
                                    <select class="col-md-12 form-control" id="input_kategori_satuan" list="input_p_kategori" name="" style="width: 100%" autocomplete="off" placeholder="Pilih Kategori" required>
                                    </select>
                                </div>

                                <label class="col-md-12" style="padding-left:0">SubKategori</label>
                                <div class="col-md-12" style="padding:0;">
                                    <select class="col-md-12 form-control" id="input_subkategori_satuan" list="input_p_kategori" name="" style="width: 100%" autocomplete="off" placeholder="Pilih Kategori" required>
                                    </select>
                                </div>

                                <label class="col-md-12" style="padding-left:0">Item</label>
                                <div class="col-md-12" style="padding:0;">
                                    <select class="col-md-12 form-control" id="input_item_satuan" list="input_p_kategori" name="" style="width: 100%" autocomplete="off" placeholder="Pilih Item" required>
                                    </select>
                                </div>

                                <label class="col-md-12" style="padding-left:0">Nama Satuan</label>
                                <div class="col-md-12" style="padding:0;">
                                    <input type='text' id='input_satuan_satuan' name='name' class='form-control' />
                                </div>

                                <label class="col-md-12" style="padding-left:0">Konversi Satuan</label>
                                <div class="col-md-12" style="padding:0;">
                                    <input type='number' id='input_konversi_satuan' name='name' class='form-control' />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary pull-right tambah_input_satuan"> Tambah</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ./wrapper -->

    @include("master/footer_table")
    @include('pluggins.alertify')
    @include('form.datatable_helper')
    @include('form.general_form')
    <script src="{{ url('/')}}/assets/bower_components/select2/dist/js/select2.full.min.js"></script>
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-Token': $('meta[name=csrf-token]').attr('content')
            }
        });

        var gentable = null;
        var _initTablePenerimaan = function() {

            var arrColumns = [{
                    'data': 'no'
                }, //
                {
                    'data': 'name'
                }, //
            ];

            _GeneralTable(arrColumns);

            gentable = $('.tableDataSinonim').DataTable(datatableDefaultOptions)
                .on('change', '.input_qty', function() {
                    var tParent = $(this).parents('tr');
                    var data = gentable.row(tParent).data();
                    gentable.row(tParent).data(data).draw();
                })
                .on('click', '.text-right', function() {
                    $(this).select();
                });
        }

        var _GeneralTable = function(arrColumns) {
            var _coldefs = [{
                "targets": [],
                "visible": false,
                "searchable": false
            }];
            var fixedColumn = {
                leftColumns: 1
            }
            datatableDefaultOptions.searching = false;
            datatableDefaultOptions.aoColumns = arrColumns;
            datatableDefaultOptions.columnDefs = _coldefs;
            datatableDefaultOptions.autoWidth = false;
            datatableDefaultOptions.ordering = false;
        }

        $(document).ready(function() {

            // $('select').select2({ width: '100%' });
            $('#table_details').DataTable({
                // .columns.adjust();
                scrollY: "500px",
                scrollX: true,
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
                                '<tr class="group" style="background-color: #3FD5C0;""><td colspan="14"><strong>' + group + '</strong></td></tr>'
                            );

                            last = group;
                        }
                    });
                },
                initComplete: function() {
                    this.api().columns().every(function() {
                        var column = this;
                        var select = $('<select><option value=""></option>Pilih</select>')
                            .appendTo($(column.footer()).empty())
                            .on('change', function() {
                                var val = $.fn.dataTable.util.escapeRegex(
                                    $(this).val()
                                );

                                column
                                    .search(val ? '^' + val + '$' : '', true, false)
                                    .draw();
                            });

                        column.data().unique().sort().each(function(d, j) {
                            select.append('<option value="' + d + '">' + d + '</option>')
                        });
                    });
                }
            });
            $('select').select2({
                dropdownAutoWidth: true,
                width: '100%'
            })

        });

        // $(document).ready(function() {
        //   $('#table_details').DataTable( {
        //       initComplete: function () {
        //           this.api().columns().every( function () {
        //               var column = this;
        //               var select = $('<select><option value=""></option></select>')
        //                   .appendTo( $(column.footer()).empty() )
        //                   .on( 'change', function () {
        //                       var val = $.fn.dataTable.util.escapeRegex(
        //                           $(this).val()
        //                       );

        //                       column
        //                           .search( val ? '^'+val+'$' : '', true, false )
        //                           .draw();
        //                   } );

        //               column.data().unique().sort().each( function ( d, j ) {
        //                   select.append( '<option value="'+d+'">'+d+'</option>' )
        //               } );
        //           } );
        //       }
        //   } );
        // } );

        const item_struct = $("#list_item");
        var list_recomended_supplier = [];
        const a = item_struct[0].innerHTML;
        var jumlah_item_old = 1;
        var list_satuan = <?php echo ($item_satuan) ?>;
        //var list_itempekerjaan = 
        //var list_item = ;
        var budget = <?= $budget ?>;
        var budget_tahunan = <?= $budget_tahunan ?>;
        var budget_tahunan_detail = <?= $budget_tahunan_detail ?>;
        var input_budget = <?= $input_budget_tahunan ?>;

        function banyak_komparasi(item) {
            $(".form_komparasi_supplier_1_item" + item).removeClass('col-md-12 col-md-6 col-md-4');
            $(".form_komparasi_supplier_2_item" + item).removeClass('col-md-12 col-md-6 col-md-4');
            $(".form_komparasi_supplier_3_item" + item).removeClass('col-md-12 col-md-6 col-md-4');
            $(".form_komparasi_supplier_1_item" + item, ".form_komparasi_supplier_2_item" + item, ".form_komparasi_supplier_3_item" + item).prop('selectedIndex', 0);
            $(".form_komparasi_supplier_1_item" + item).hide();
            $(".form_komparasi_supplier_2_item" + item).hide();
            $(".form_komparasi_supplier_3_item" + item).hide();
            for (i = 1; i <= $(".jumlah_komparasi" + item).val(); i++) {
                $(".form_komparasi_supplier_" + i + "_item" + item).addClass('col-md-' + 12 / $(".jumlah_komparasi" + item).val());
                $(".form_komparasi_supplier_" + i + "_item" + item).show();
            }
            for (i = 1; i <= 3; i++)
                if ($(".form_komparasi_supplier_" + i + "_item" + item).is(":hidden"))
                    $('.form_komparasi_supplier_' + i + '_item' + item + 'option:first').prop('selected', true);
            if ($(".jumlah_komparasi" + item).val() == 2)
                list_recomended_supplier.splice(2, 1);
            if ($(".jumlah_komparasi" + item).val() == 1) {
                list_recomended_supplier.splice(2, 1);
                list_recomended_supplier.splice(1, 1);
            }
        }

        function filter_budget(val) {
            var val = val.substr(0, val.indexOf(" -"));
        }

        function recomended_supplier(val, txt, ind, item) {
            list_recomended_supplier[ind] = [val, txt];
            for (var j = 1; j <= 3; j++) {

                $("select[name='komparasi_supplier" + item + "_" + j + "[]']").find('option').each(function() {
                    $(this).attr("disabled", false);
                });
            }
            for (var i = 1; i <= 3; i++) {
                val = $("select[name='komparasi_supplier" + item + "_" + i + "[]']").val();
                if (val != null) {
                    for (var j = 1; j <= 3; j++) {
                        if (j != i) {
                            $("select[name='komparasi_supplier" + item + "_" + j + "[]']").find('option').each(function() {
                                if ($(this).val() == val) {
                                    $(this).attr("disabled", true);
                                }
                            });
                        }
                    }
                }

            }
            $("select").select2();
        }

        function create_componen_supplier(value, text) {
            var div = document.createElement("div");
            var label = document.createElement("label");
            var input = document.createElement("input");
            input.name = "rs[]";
            input.type = "checkbox";
            input.value = value;
            label.innerHTML = text;
            label.innerHTML = input.outerHTML + label.innerHTML;
            div.classList.add("checkbox");
            div.innerHTML = label.outerHTML;
            return div;
        }

        function filter_satuan(val, item) {
            var id_item = val.substr(0, val.indexOf(" -"));
            $(".satuan_item" + item).append(option);
            $(".satuan_item" + item).empty();
            for (var i = 0; i < list_satuan.length; i++) {
                if (list_satuan[i].item_id == id_item) {
                    var option = document.createElement("option");
                    option.value = list_satuan[i].id;
                    option.innerHTML = list_satuan[i].name;
                    $(".satuan_item" + item).append(option);
                }
            }
        }

        function filter_itempekerjaan(val, val2) {
            if (val2) {
                $("#" + val2).empty();
                var tmp = document.createElement("option");
                tmp.value = "";
                tmp.innerHTML = "Pilih Item Pekerjaan";
                tmp.setAttribute("disabled", "true");
                tmp.setAttribute("selected", "true");
                $("#" + val2).append(tmp);
                for (var i = 0; i < budget.length; i++) {
                    if (budget[i].btId == val) {
                        var tmp = document.createElement("option");
                        tmp.value = budget[i].btdItemPekerjaan;
                        tmp.innerHTML = budget[i].ipName;
                        $("#" + val2).append(tmp);
                    }
                }
            } else {
                $(".data_itempekerjaan").empty();
                var tmp = document.createElement("option");
                tmp.value = "";
                tmp.innerHTML = "Pilih Item Pekerjaan";
                tmp.setAttribute("disabled", "true");
                tmp.setAttribute("selected", "true");
                $(".data_itempekerjaan").append(tmp);
                for (var i = 0; i < budget.length; i++) {
                    if (budget[i].btId == val) {
                        var tmp = document.createElement("option");
                        tmp.value = budget[i].btdItemPekerjaan;
                        tmp.innerHTML = budget[i].ipName;
                        $(".data_itempekerjaan").append(tmp);
                    }
                }
            }
        }


        function isi_deskripsi_umum(val) {
            $("textarea[name='deskripsi_umum']").val(val);
        }

        function isi_deskripsi_item(val, item) {
            $("#deskripsi_item" + item).val(val);
        }




        $(document).ready(function() {

            var url = "{{ url('/')}}/purchaserequest/getBudgetTahunan";
            var item = $("#budget_tahunan");
            $('#department').change(function() {
                var send = parseInt($(this).val()) + "|" + <?= $project->id ?>;
                var getjson = $.getJSON(url + '/' + send, function(data) {
                    item.addClass("item");
                    item.empty();
                    var option = document.createElement("option");
                    option.value = "";
                    option.innerHTML = "Pilih Budget Tahunan";
                    option.setAttribute("disabled", "true");
                    option.setAttribute("selected", "true");
                    item.append(option);
                    for (var i = 0; i <= data.length; i++) {
                        if (i < data.length) {
                            var option = document.createElement("option");
                            option.value = data[i].id;
                            option.innerHTML = data[i].no;
                            item.append(option);
                        } else
                            item.removeClass("item");
                    }

                });
            });

            $(document).on('change', '.parentcategory_data', function() {
                var category_id = $(this).val();
                var _url = "{{ url('/purchaserequest/changeCategoryBaseParent') }}";
                var _data = {
                    parent: category_id
                };
                var parent_div = $(this).parents('.sub_list_item');
                $.ajax({
                    type: 'post',
                    dataType: 'json',
                    url: _url,
                    data: _data,
                    beforeSend: function() {
                        waitingDialog.show();

                    },
                    success: function(data) {
                        var strItemOption = '';

                        if (data.items != null) {
                            parent_div.find('.item_data').find('option').remove();
                            strItemOption += '<option value="0">All Item</option>';
                            $(data.items).each(function(i, v) {
                                strItemOption += '<option value="' + v.itemid + '"">' + v.itemname + '</option>';
                            });
                            parent_div.find('.item_data').append(strItemOption);
                        }

                        if (data.all_categories != null) {
                            parent_div.find('.category_data').find('option').remove();
                            strItemOption = '';
                            strItemOption += '<option value="0">All Sub Kategori</option>';
                            $(data.all_categories).each(function(i, v) {
                                strItemOption += '<option value="' + v.id + '">' + v.name + '</option>';
                            });

                            parent_div.find('.category_data').append(strItemOption);
                        }



                        if (data.parent_categories != null) {
                            parent_div.find('.parentcategory_data').find('option').remove();
                            strItemOption = '';
                            strItemOption += '<option value="0">All Kategori</option>';
                            $(data.parent_categories).each(function(i, v) {
                                strItemOption += '<option value="' + v.id + '">' + v.name + '</option>';
                            });

                            parent_div.find('.parentcategory_data').append(strItemOption);
                        }

                    },
                    complete: function() {
                        waitingDialog.hide();
                    }
                });
            });

            $(document).on('change', '.category_data', function() {
                var category_id = $(this).val();
                var _url = "{{ url('/purchaserequest/changeItemBaseCategory') }}";
                var _data = {
                    category_id: category_id
                };
                var parent_div = $(this).parents('.sub_list_item');
                $.ajax({
                    type: 'post',
                    dataType: 'json',
                    url: _url,
                    data: _data,
                    beforeSend: function() {
                        waitingDialog.show();

                    },
                    success: function(data) {
                        var strItemOption = '';

                        if (data.items != null) {
                            parent_div.find('.item_data').find('option').remove();
                            strItemOption += '<option value="0">All Item</option>';
                            $(data.items).each(function(i, v) {
                                strItemOption += '<option value="' + v.itemid + '"">' + v.itemname + '</option>';
                            });
                            parent_div.find('.item_data').append(strItemOption);
                        }

                        if (data.all_categories != null) {
                            parent_div.find('.category_data').find('option').remove();
                            strItemOption = '';
                            strItemOption += '<option value="0">All Sub Kategori</option>';
                            $(data.all_categories).each(function(i, v) {
                                strItemOption += '<option value="' + v.id + '">' + v.name + '</option>';
                            });

                            parent_div.find('.category_data').append(strItemOption);
                        }

                        if (data.parent_categories != null) {
                            parent_div.find('.parentcategory_data').find('option').remove();
                            strItemOption = '';
                            strItemOption += '<option value="0">All Kategori</option>';
                            var id = 0;
                            $(data.parent_categories).each(function(i, v) {
                                strItemOption += '<option value="' + v.id + '">' + v.name + '</option>';
                                id = v.id;
                            });

                            parent_div.find('.parentcategory_data').append(strItemOption);
                            parent_div.find('.parentcategory_data').val(id);
                            if (category_id == 0) {
                                parent_div.find('.parentcategory_data').val(0).trigger('change.select2');
                            }
                        }




                    },
                    complete: function() {
                        waitingDialog.hide();
                    }
                });
            });

            $(document).on('change', '.item_data', function() {
                var item_desk = $(this).val();
                var Eparent = $(this).parents('.sub_list_item');
                var item_split = $(this).val().split("-");
                var getItemId = item_split[0].trim();
                var _url = "{{ url('/purchaserequest/changeBrand') }}"
                $.ajax({
                    type: 'post',
                    dataType: 'json',
                    url: _url,
                    data: {
                        id: getItemId
                    },
                    beforeSend: function() {
                        waitingDialog.show();
                    },
                    success: function(data) {

                        Eparent.find('.brand_id').find('option').remove();
                        Eparent.find('.satuan_item').find('option').remove();
                        Eparent.find('.category_data').find('option').remove();
                        Eparent.find('.parentcategory_data').find('option').remove();

                        var strHtml = '';
                        if (data.brands != null) {
                            strHtml += '<option value="0">All Brands</option>';
                            $(data.brands).each(function(i, v) {
                                strHtml += '<option value="' + v.id + '">' + v.name + '</option>';
                            });
                            Eparent.find('.brand_id').append(strHtml);
                        }

                        if (data.satuans != null) {
                            strHtml = '';
                            $(data.satuans).each(function(i, v) {
                                strHtml += '<option value="' + v.id + '">' + v.name + '</option>';
                            });

                            Eparent.find('.satuan_item').append(strHtml);
                        }

                        if (data.categories != null) {
                            strHtml = '';
                            strHtml += '<option value="0">All Sub Kategori</option>';
                            var id_category = 0;
                            $(data.categories).each(function(i, v) {
                                strHtml += '<option value="' + v.id + '">' + v.name + '</option>';
                                id_category = v.id;
                            });
                            Eparent.find('.category_data').append(strHtml);
                            Eparent.find('.category_data').val(id_category);
                            if (item_desk == 0) {
                                Eparent.find('.category_data').val(0).trigger('change.select2');
                            }

                        }

                        if (data.parent_categories != null) {
                            strHtml = '';
                            strHtml += '<option value="0">All Kategori</option>';
                            var id_category = 0;
                            $(data.parent_categories).each(function(i, v) {
                                strHtml += '<option value="' + v.id + '">' + v.name + '</option>';
                                id_category = v.id;
                            });
                            Eparent.find('.parentcategory_data').append(strHtml);
                            Eparent.find('.parentcategory_data').val(id_category);
                            if (item_desk == 0) {
                                Eparent.find('.parentcategory_data').val(0).trigger('change.select2');
                            }

                        }

                        if (data.items != null) {
                            strHtml = '';
                            Eparent.find('.item_data').find('option').remove();
                            strHtml += '<option value="0">All Item</option>';
                            $(data.items).each(function(i, v) {
                                strHtml += '<option value="' + v.itemid + '"">' + v.itemname + '</option>';
                            });
                            Eparent.find('.item_data').append(strHtml);
                        }

                        // Eparent.find('.item_desk').val('Spesifikasi & Deskripsi'+item_split[1]);
                    },
                    complete: function() {
                        waitingDialog.hide();
                    }
                });

            });

            $('#budget_tahunan').change(function() {
                var budget_id = $(this).val();
                var _url = "{{  url('/purchaserequest/filter_item_pekerjaan') }}";
                var _data = {
                    id: budget_id
                };
                $.ajax({
                    type: 'post',
                    dataType: 'json',
                    url: _url,
                    data: _data,
                    beforeSend: function() {
                        waitingDialog.show();
                    },
                    success: function(data) {
                        var strOption = '';
                        strOption += '<option value="">Pilih Item Pekerjaan</option>';
                        $(data).each(function(i, v) {
                            strOption += '<option value="' + v.id + '">' + v.itempekerjaan + " | " + v.code + '</option>';
                        });

                        $('.data_itempekerjaan').find('option').remove();
                        $('.data_itempekerjaan').append(strOption);
                    },
                    complete: function() {
                        waitingDialog.hide();
                    }
                });
            });


            $(document).on('click', '.btn-delete', function() {
                var id = $(this).attr('data-value');
                var parent = $(this).parents('.list_item');
                var _url = "{{  url('/purchaserequest/delete_detail') }}";
                var _data = {
                    id: id
                };
                $.ajax({
                    type: 'post',
                    dataType: 'json',
                    url: _url,
                    data: _data,
                    beforeSend: function() {
                        waitingDialog.show();
                    },
                    // success:function(data)
                    // {
                    //   if(data.stat)
                    //   {
                    //       parent.remove();
                    //       alertify.success('Berhasil dihapus');
                    //       $('.modal').modal('hide');

                    //   }
                    // },
                    complete: function() {
                        waitingDialog.hide();
                    }
                });
            });

            $(document).on('change', '.item_data', function() {
                var item_desk = $(this).val();
                var _url = "{{ url('/purchaserequest/harga_estimasi') }}";
                var parent_div = $(this).parents('.sub_list_item');
                var quantity = parent_div.find(".kuantitas").val();
                // var satuan = parent_div.find(".satuan_item").val();
                $.ajax({
                    type: 'get',
                    dataType: 'json',
                    url: _url,
                    data: {
                        id: item_desk
                    },
                    beforeSend: function() {
                        waitingDialog.show();
                    },
                    success: function(data) {
                        var estimasi = data.data * quantity;
                        parent_div.find(".harga_estimasi").val(data.data);
                        parent_div.find(".total_harga_estimasi").val(estimasi);
                        fnSetAutoNumeric(parent_div.find(".total_harga_estimasi"));
                        fnSetMoney(parent_div.find(".total_harga_estimasi"), estimasi);
                        parent_div.find(".satuan_item").val(data.satuan).trigger('change');
                    },
                    complete: function() {
                        waitingDialog.hide();
                    }
                });
            });

            // $(document).on('change','.satuan_item',function(){          
            //     var satuan = $(this).val();
            //     var _url = "{{ url('/purchaserequest/harga_estimasi_satuan') }}";
            //     var parent_div = $(this).parents('.sub_list_item');
            //     var quantity = parent_div.find(".kuantitas").val();
            //     var item_desk = parent_div.find(".item_data").val();

            //     $.ajax({
            //       type:'get',
            //       dataType:'json',
            //       url:_url,
            //       data:{id:item_desk, satuan:satuan},
            //       beforeSend:function()
            //       {
            //         waitingDialog.show();
            //       },
            //       success:function(data)
            //       {

            //         var estimasi = data.data*quantity;
            //         parent_div.find(".harga_estimasi").val(data.data);
            //         parent_div.find(".total_harga_estimasi").val(estimasi);
            //         fnSetAutoNumeric(parent_div.find(".total_harga_estimasi"));
            //         fnSetMoney(parent_div.find(".total_harga_estimasi"),estimasi);
            //       },
            //       complete:function()
            //       {
            //         waitingDialog.hide();
            //       }
            //     });
            // });

            $(document).on('change', '.kuantitas', function() {
                var parent_div = $(this).parents('.sub_list_item');
                var harga_estimasi = parent_div.find(".harga_estimasi").val();
                // var item_desk = parent_div.find(".item_data").val();
                // var _url = "{{ url('/purchaserequest/harga_estimasi') }}";
                var quantity = $(this).val();
                $.ajax({
                    beforeSend: function() {
                        waitingDialog.show();
                    },
                    success: function() {
                        var estimasi = harga_estimasi * quantity;
                        parent_div.find(".total_harga_estimasi").val(estimasi);
                        fnSetAutoNumeric(parent_div.find(".total_harga_estimasi"));
                        fnSetMoney(parent_div.find(".total_harga_estimasi"), estimasi);
                    },
                    complete: function() {
                        waitingDialog.hide();
                    }
                });
            });

            $(document).on('change', '.harga_estimasi', function() {
                var parent_div = $(this).parents('.sub_list_item');
                var harga_estimasi = $(this).val();
                var quantity = parent_div.find(".kuantitas").val();
                $.ajax({
                    beforeSend: function() {
                        waitingDialog.show();
                    },
                    success: function() {
                        var estimasi = harga_estimasi * quantity;
                        parent_div.find(".total_harga_estimasi").val(estimasi);
                        fnSetAutoNumeric(parent_div.find(".total_harga_estimasi"));
                        fnSetMoney(parent_div.find(".total_harga_estimasi"), estimasi);
                    },
                    complete: function() {
                        waitingDialog.hide();
                    }
                });
            });

            $(document).on('change', '.item_data', function() {
                var parent_div = $(this).parents('.sub_list_item');
                var url = "{{ url('/purchaserequest/repeat_order') }}";
                var item_desk = parent_div.find(".item_data").val();
                var item = parent_div.find(".purchase_order");
                var department = $("#department_id").val();
                $.ajax({
                    type: 'get',
                    dataType: 'json',
                    url: url,
                    data: {
                        department: department,
                        item: item_desk
                    },
                    beforeSend: function() {
                        waitingDialog.show();
                    },
                    success: function(data) {
                        var strHtml = '';
                        strHtml += '<option value="">Pilih PO</option>';
                        if (data.result.length > 0) {
                            alertify.success(data.result.length + ' PO ditemukan');

                            for (var i = 0; i < data.result.length; i++) {
                                strHtml += '<option value="' + data.result[i].id + '" >' + data.result[i].no + '</option>';
                            }
                        }
                        //
                        item.find('option').remove();
                        item.append(strHtml);
                    },
                    complete: function() {
                        waitingDialog.hide();
                    }
                });
            });

            $(document).on('change', '.purchase_order', function() {
                var parent_div = $(this).parents('.sub_list_item');
                var po = parent_div.find(".purchase_order").val();
                var _url = "{{ url('/purchaserequest/data_po') }}";
                var quantity = parent_div.find(".kuantitas").val();
                var item_desk = parent_div.find(".item_data").val();
                $.ajax({
                    type: 'get',
                    dataType: 'json',
                    url: _url,
                    data: {
                        id: po,
                        item_id: item_desk
                    },
                    beforeSend: function() {
                        waitingDialog.show();
                    },
                    success: function(data) {
                        parent_div.find(".komparasi").val('1').trigger('change');
                        parent_div.find(".supplier1").val(data.result[0].rekanan_id).trigger('change');

                        var estimasi = data.result[0].harga_estimasi * quantity;
                        parent_div.find(".harga_estimasi").val(data.result[0].harga_estimasi);
                        parent_div.find(".total_harga_estimasi").val(estimasi);
                        fnSetAutoNumeric(parent_div.find(".total_harga_estimasi"));
                        fnSetMoney(parent_div.find(".total_harga_estimasi"), estimasi);

                    },
                    complete: function() {
                        waitingDialog.hide();
                    }
                });
            });

            $(document).on('change', '.repeat_order', function() {
                var parent_div = $(this).parents('.sub_list_item');
                // var po = parent_div.find(".purchase_order").val();
                var nilai = parent_div.find(".repeat_order").val();

                $.ajax({
                    beforeSend: function() {
                        waitingDialog.show();
                    },
                    success: function() {
                        if (nilai == 2) {
                            parent_div.find(".po").show();
                        } else {
                            parent_div.find(".po").hide();
                        }
                    },
                    complete: function() {
                        waitingDialog.hide();
                    }
                });
            });

            var Modal = '';
            $(document).on('click', '.tambah_parent_kategori', function() {
                Modal = $("#editModalParentKategori").html();
                $('#editModalParentKategori').modal('show');
                gentable.clear().draw();
            });

            $(document).on('keyup', '#input_parent_kategori', function() {
                const proxyurl = "https://cors-anywhere.herokuapp.com/";
                url = "http://kateglo.com/api.php?format=json&phrase=" + $("#input_parent_kategori").val();
                var hasil = '';
                fetch(proxyurl + url).then(response => hasil = response.json()).then(contents => console.log(contents)).catch(() => console.log("Can’t access " + url + " response. Blocked by browser?"));

                console.log(hasil);
            });

            $(document).on('click', '.tambah_input_parent_kategori', function() {
                var _url = "{{ url('/purchaserequest/tambah_kategori') }}";
                var kategori = $("#input_parent_kategori").val();
                $.ajax({
                    type: 'get',
                    dataType: 'json',
                    url: _url,
                    data: {
                        kategori: kategori
                    },
                    beforeSend: function() {
                        waitingDialog.show();
                    },
                    success: function(data) {

                    },
                    complete: function() {
                        waitingDialog.hide();
                    }
                });

                $('#editModalParentKategori').modal('hide');
                $("#editModalParentKategori").html(Modal);
            });

            $(document).on('hidden.bs.modal', '#editModalParentKategori', function() {
                $("#editModalParentKategori").html(Modal);
            });

            $(document).on('click', '.tambah_kategori', function() {
                var _url = "{{ url('/purchaserequest/data_item') }}";
                item = $("#input_kategori_subkategori");
                Modal = $("#editModalKategori").html();
                $.ajax({
                    type: 'get',
                    dataType: 'json',
                    url: _url,
                    data: {},
                    beforeSend: function() {
                        waitingDialog.show();
                    },
                    success: function(data) {
                        var strHtml = '';
                        strHtml += '<option value="">Pilih Kategori</option>';
                        if (data.kategori.length > 0) {
                            alertify.success(data.kategori.length + ' Kategori ditemukan');

                            for (var i = 0; i < data.kategori.length; i++) {
                                strHtml += '<option value="' + data.kategori[i].id + '" >' + data.kategori[i].name + '</option>';
                            }
                        }
                        //
                        item.find('option').remove();
                        item.append(strHtml);
                    },
                    complete: function() {
                        waitingDialog.hide();
                    }
                });
                $('#editModalKategori').modal('show');
                gentable.clear().draw();
            });

            $(document).on('click', '.tambah_input_subkategori', function() {
                var _url = "{{ url('/purchaserequest/tambah_subkategori') }}";
                var kategori = $("#input_kategori_subkategori").val();
                var subkategori = $("#input_subkategori").val();
                $.ajax({
                    type: 'get',
                    dataType: 'json',
                    url: _url,
                    data: {
                        kategori: kategori,
                        subkategori: subkategori
                    },
                    beforeSend: function() {
                        waitingDialog.show();
                    },
                    success: function(data) {

                    },
                    complete: function() {
                        waitingDialog.hide();
                    }
                });

                $('#editModalKategori').modal('hide');
                $("#editModalKategori").html(Modal);
            });

            $(document).on('click', '.tambah_item', function() {
                var _url = "{{ url('/purchaserequest/data_item') }}";
                kategori = $("#input_kategori_item");
                subkategori = $("#input_subkategori_item");
                Modal = $("#editModalItem").html();
                $.ajax({
                    type: 'get',
                    dataType: 'json',
                    url: _url,
                    data: {},
                    beforeSend: function() {
                        waitingDialog.show();
                    },
                    success: function(data) {
                        var strHtml = '';
                        strHtml += '<option value="">Pilih Kategori</option>';
                        if (data.kategori.length > 0) {
                            alertify.success(data.kategori.length + ' Kategori ditemukan');

                            for (var i = 0; i < data.kategori.length; i++) {
                                strHtml += '<option value="' + data.kategori[i].id + '" >' + data.kategori[i].name + '</option>';
                            }
                        }
                        //
                        kategori.find('option').remove();
                        kategori.append(strHtml);

                        var strHtml = '';
                        strHtml += '<option value="">Pilih SubKategori</option>';
                        if (data.subkategori.length > 0) {
                            alertify.success(data.subkategori.length + ' SubKategori ditemukan');

                            for (var i = 0; i < data.subkategori.length; i++) {
                                strHtml += '<option value="' + data.subkategori[i].id + '" >' + data.subkategori[i].name + '</option>';
                            }
                        }
                        //
                        subkategori.find('option').remove();
                        subkategori.append(strHtml);
                    },
                    complete: function() {
                        waitingDialog.hide();
                    }
                });

                $('#editModalItem').modal('show');
                gentable.clear().draw();
            });

            $(document).on('click', '.tambah_input_item', function() {
                var _url = "{{ url('/purchaserequest/tambah_item') }}";
                var kategori = $("#input_kategori_item").val();
                var subkategori = $("#input_subkategori_item").val();
                var item = $("#input_item_item").val();
                var kode = $("#input_kode_item").val();
                var description = $("#input_description_item").val();
                $.ajax({
                    type: 'get',
                    dataType: 'json',
                    url: _url,
                    data: {
                        kategori: kategori,
                        subkategori: subkategori,
                        item: item,
                        kode: kode,
                        description: description
                    },
                    beforeSend: function() {
                        waitingDialog.show();
                    },
                    success: function(data) {

                    },
                    complete: function() {
                        waitingDialog.hide();
                    }
                });

                $('#editModalItem').modal('hide');
                $("#editModalItem").html(Modal);
            });

            $(document).on('click', '.tambah_brand', function() {
                var _url = "{{ url('/purchaserequest/data_item') }}";
                kategori = $("#input_kategori_brand");
                subkategori = $("#input_subkategori_brand");
                Modal = $("#editModalBrand").html();
                $.ajax({
                    type: 'get',
                    dataType: 'json',
                    url: _url,
                    data: {},
                    beforeSend: function() {
                        waitingDialog.show();
                    },
                    success: function(data) {
                        var strHtml = '';
                        strHtml += '<option value="">Pilih Kategori</option>';
                        if (data.kategori.length > 0) {
                            alertify.success(data.kategori.length + ' Kategori ditemukan');

                            for (var i = 0; i < data.kategori.length; i++) {
                                strHtml += '<option value="' + data.kategori[i].id + '" >' + data.kategori[i].name + '</option>';
                            }
                        }
                        //
                        kategori.find('option').remove();
                        kategori.append(strHtml);

                        var strHtml = '';
                        strHtml += '<option value="">Pilih SubKategori</option>';
                        if (data.subkategori.length > 0) {
                            alertify.success(data.subkategori.length + ' SubKategori ditemukan');

                            for (var i = 0; i < data.subkategori.length; i++) {
                                strHtml += '<option value="' + data.subkategori[i].id + '" >' + data.subkategori[i].name + '</option>';
                            }
                        }
                        //
                        subkategori.find('option').remove();
                        subkategori.append(strHtml);
                    },
                    complete: function() {
                        waitingDialog.hide();
                    }
                });
                $('#editModalBrand').modal('show');
            });

            $(document).on('click', '.tambah_input_brand', function() {
                var _url = "{{ url('/purchaserequest/tambah_brand') }}";
                var kategori = $("#input_kategori_brand").val();
                var subkategori = $("#input_subkategori_brand").val();
                var brand = $("#input_brand_brand").val();
                $.ajax({
                    type: 'get',
                    dataType: 'json',
                    url: _url,
                    data: {
                        kategori: kategori,
                        subkategori: subkategori,
                        brand: brand
                    },
                    beforeSend: function() {
                        waitingDialog.show();
                    },
                    success: function(data) {

                    },
                    complete: function() {
                        waitingDialog.hide();
                    }
                });

                $('#editModalBrand').modal('hide');
                $("#editModalBrand").html(Modal);
            });


            $(document).on('click', '.tambah_satuan', function() {
                var _url = "{{ url('/purchaserequest/data_item') }}";
                kategori = $("#input_kategori_satuan");
                subkategori = $("#input_subkategori_satuan");
                item = $("#input_item_satuan");
                Modal = $("#editModalSatuan").html();
                $.ajax({
                    type: 'get',
                    dataType: 'json',
                    url: _url,
                    data: {},
                    beforeSend: function() {
                        waitingDialog.show();
                    },
                    success: function(data) {
                        var strHtml = '';
                        strHtml += '<option value="">Pilih Kategori</option>';
                        if (data.kategori.length > 0) {
                            alertify.success(data.kategori.length + ' Kategori ditemukan');

                            for (var i = 0; i < data.kategori.length; i++) {
                                strHtml += '<option value="' + data.kategori[i].id + '" >' + data.kategori[i].name + '</option>';
                            }
                        }
                        //
                        kategori.find('option').remove();
                        kategori.append(strHtml);

                        var strHtml = '';
                        strHtml += '<option value="">Pilih SubKategori</option>';
                        if (data.subkategori.length > 0) {
                            alertify.success(data.subkategori.length + ' SubKategori ditemukan');

                            for (var i = 0; i < data.subkategori.length; i++) {
                                strHtml += '<option value="' + data.subkategori[i].id + '" >' + data.subkategori[i].name + '</option>';
                            }
                        }
                        //
                        subkategori.find('option').remove();
                        subkategori.append(strHtml);

                        var strHtml = '';
                        strHtml += '<option value="">Pilih Item</option>';
                        if (data.item.length > 0) {
                            alertify.success(data.item.length + ' Item ditemukan');

                            for (var i = 0; i < data.item.length; i++) {
                                strHtml += '<option value="' + data.item[i].id + '" >' + data.item[i].name + '</option>';
                            }
                        }
                        //
                        item.find('option').remove();
                        item.append(strHtml);

                    },
                    complete: function() {
                        waitingDialog.hide();
                    }
                });
                $('#editModalSatuan').modal('show');
            });

            $(document).on('click', '.tambah_input_satuan', function() {
                var _url = "{{ url('/purchaserequest/tambah_satuan') }}";
                var kategori = $("#input_kategori_satuan").val();
                var subkategori = $("#input_subkategori_satuan").val();
                var item = $("#input_item_satuan").val();
                var satuan = $("#input_satuan_satuan").val();
                var konversi = $("#input_konversi_satuan").val();
                $.ajax({
                    type: 'get',
                    dataType: 'json',
                    url: _url,
                    data: {
                        kategori: kategori,
                        subkategori: subkategori,
                        item: item,
                        satuan: satuan,
                        konversi: konversi
                    },
                    beforeSend: function() {
                        waitingDialog.show();
                    },
                    success: function(data) {

                    },
                    complete: function() {
                        waitingDialog.hide();
                    }
                });

                $('#editModalSatuan').modal('hide');
                $("#editModalSatuan").html(Modal);
            });

            _initTablePenerimaan();
            $(document).on('keyup', '#input_parent_kategori', function() {
                var _url = "{{ url('/purchaserequest/cekSinonimKategori') }}";
                var key = $(this).val();
                $.ajax({
                    type: 'get',
                    dataType: 'json',
                    url: _url,
                    data: {
                        key: key
                    },
                    success: function(data) {
                        if (data.status == 0) {
                            $("#labelParentKategori").show();
                            if (data.data.length > 0) {
                                gentable.clear().draw();
                                $(data.data).each(function(i, v) {
                                    var items = {
                                        no: i + 1,
                                        name: v.name,
                                    };

                                    gentable.row.add(items);
                                });
                                gentable.draw();
                            }
                        } else {
                            $("#labelParentKategori").hide();
                            gentable.clear().draw();
                        }


                    },
                });
            });


            $(document).on('keyup', '.input_kategori', function() {
                var _url = "{{ url('/purchaserequest/cekSinonimKategori') }}";
                var key = $(this).val();
                $.ajax({
                    type: 'get',
                    dataType: 'json',
                    url: _url,
                    data: {
                        key: key
                    },
                    success: function(data) {
                        if (data.status == 0) {
                            $("#labelKategori").show();
                            if (data.data.length > 0) {
                                gentable.clear().draw();
                                $(data.data).each(function(i, v) {
                                    var items = {
                                        no: i + 1,
                                        name: v.name,
                                    };

                                    gentable.row.add(items);
                                });
                                gentable.draw();
                            }
                        } else {
                            $("#labelKategori").hide();
                            gentable.clear().draw();
                        }

                    }
                });
            });

            $(document).on('keyup', '#input_item_item', function() {
                var _url = "{{ url('/purchaserequest/cekSinonimItem') }}";
                var key = $(this).val();
                $.ajax({
                    type: 'get',
                    dataType: 'json',
                    url: _url,
                    data: {
                        key: key
                    },
                    success: function(data) {
                        if (data.status == 0) {
                            $("#labelItem").show();
                            if (data.data.length > 0) {
                                gentable.clear().draw();
                                $(data.data).each(function(i, v) {
                                    var items = {
                                        no: i + 1,
                                        name: v.name,
                                    };

                                    gentable.row.add(items);
                                });
                                gentable.draw();
                            }
                        } else {
                            $("#labelItem").hide();
                            gentable.clear().draw();
                        }

                    }
                });
            });

            $(document).on('change', '.coaHeader', function() {
                var parent_div = $(this).parents('.sub_list_item');
                var url = "{{ url('/purchaserequest/coaDetail') }}";
                var coaHeader = parent_div.find(".coaHeader").val();
                var item = parent_div.find(".coaDetail");
                $.ajax({
                    type: 'get',
                    dataType: 'json',
                    url: url,
                    data: {
                        coaHeader: coaHeader
                    },
                    beforeSend: function() {
                        waitingDialog.show();
                    },
                    success: function(data) {
                        var strHtml = '';
                        strHtml += '<option value="">Pilih Item Pekerjaan</option>';
                        if (data.result.length > 0) {
                            alertify.success(data.result.length + ' Item Pekerjaan ditemukan');

                            for (var i = 0; i < data.result.length; i++) {
                                strHtml += '<option value="' + data.result[i].id + '" >' + data.result[i].name + ' | ' + data.result[i].code + '</option>';
                            }
                        }
                        //
                        item.find('option').remove();
                        item.append(strHtml);
                    },
                    complete: function() {
                        waitingDialog.hide();
                    }
                });
            });

        });

        function openmodal(id) {
            var id = id;
            var other = "<a onclick='openmodal(1)' style='cursor:pointer'>product 1</a><a onclick='openmodal(2)' style='cursor:pointer'>product 2</a><a onclick='openmodal(3)' style='cursor:pointer' >product 3</a>";
            $('#item_modal').fadeOut("slow", function() {
                $(this).modal('hide')
            }).fadeIn("slow", function() {
                $("#target_title").text(id);
                $("#target_other").html(other);
                $(this).modal('show')
            });
        }

        // $(document).on('click', '.edit-modal', function() {
        //           // $('.modal-title').text('Edit');
        //            $('#category_name').val($(this).data('category'));
        //           // $('#title_edit').val($(this).data('title'));
        //           // $('#content_edit').val($(this).data('content'));
        //           // id = $('#id_edit').val();
        //           $('#editModal').modal('show');
        //       });

        $(".edit-modal").click(function() {
            refresh: true,
            $("#details_id").val($(this).attr('data-id'));
            $("#parentcategory_name").val($(this).attr('data-parentcategory'));
            $("#category_name").val($(this).attr('data-category'));
            $("#item_name").val($(this).attr('data-item'));
            $("#brand_idForm").val($(this).attr('data-brand'));
            $("#kuantitas").val($(this).attr('data-kuantitas'));
            $("#satuan_item").val($(this).attr('data-satuan'));
            $("#komparasi").val($(this).attr('data-komparasi'));
            $("#supplier_1").val($(this).attr('data-rec1'));
            $("#supplier_2").val($(this).attr('data-rec2'));
            $("#supplier_3").val($(this).attr('data-rec3'));
            $("#data_itempekerjaan").val($(this).attr('data-coa'));
            $("#coaDetail").val($(this).attr('data-coa'));
            $("#coaHeader").val($(this).attr('data-headerCoa'));
            $("#deskripsi_item").val($(this).attr('data-deskripsi'));
            $("#spk").val($(this).attr('data-SPK'));
            $("#harga_estimasi").val($(this).attr('data-harga'));
            $("#total_harga_estimasi").val($(this).attr('data-harga') * $(this).attr('data-kuantitas'));
            fnSetAutoNumeric($("#total_harga_estimasi").val($(this).attr('data-harga') * $(this).attr('data-kuantitas')));
            fnSetMoney($("#total_harga_estimasi").val($(this).attr('data-harga') * $(this).attr('data-kuantitas')), $(this).attr('data-harga') * $(this).attr('data-kuantitas'));
            banyak_komparasi(2);

            var nilai = $(this).attr('data-komparasi');

            for (i = 1; i <= nilai; i++) {
                //$(".form_komparasi_supplier_"+i+"_item"+nilai).addClass('col-md-'+12/$(".jumlah_komparasi").val());

                $(".form_komparasi_supplier_" + i + "_item2").show();
                $(".form_komparasi_supplier_" + i + "_item2").addClass('col-md-' + 12 / nilai);
                $(".form_komparasi_supplier_" + i + "_item2").trigger("change");

            }

            //$('select').select2('destroy');
            //$('select').select2();



            $('#editModal').modal('show');
            $('select').select2();
        });

        $(".tambah-detail").click(function() {
            refresh: true,
            $('#myModaltambah').modal('show');
        });
    </script>
</body>

</html>