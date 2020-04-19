<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin QS | Dashboard </title>
    @include("master/header")
    <link href="{{ URL::asset('assets/global/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('assets/global/plugins/select2/css/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />

    <style>
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
        }

        .content-header h1 {
            text-align: center;
        }
    </style>

</head>

<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
        @include("master/sidebar_project")
        <div class="content-wrapper">
            <section class="content-header">
                <h1>Tambah Purchase Request</h1>
            </section>
            <section class="back-button content-header">
                <div class="" style="float: none">
                    <button class="col-lg-1 col-md-2 col-sm-2 btn btn-primary" onclick="location.href='{{ url('/')}}/purchaserequest'" style="float: none; border-radius: 20px; padding-left: 0">
                        <i class="fa fa-fw fa-arrow-left"></i>&nbsp;&nbsp;Back
                    </button>
                    <button class="col-lg-1 col-md-2 col-sm-2 btn btn-primary" onclick="window.location.reload()" style="float: right; border-radius: 20px; padding-left: 0;">
                        <i class="fa fa-fw fa-refresh"></i>&nbsp;&nbsp;Refresh
                    </button>
                </div>
            </section>

            <section class="content">
                <div class="box box-default">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <form action="{{ url('/')}}/purchaserequest/add-pr" method="post" name="form1" autocomplete="off" id="form-pr">
                                    @csrf
                                    <div class="form-group col-md-3">
                                        <label class="col-md-12" style="padding-left:0">PT</label>
                                        <select class="form-control" name="pt_id" id="pt_id">
                                            @foreach ( $project->pt_user as $key6 => $value6)
                                              @if($user->id == $value6->user_id)
                                                  <option value="{{ $value6->pt->id}}">{{ $value6->pt->name }}</option>
                                              @endif
                                            @endforeach
                                          </select>
                                    </div>

                                    <div class="form-group col-md-3">
                                        <label class="col-md-12" style="padding-left:0">Department</label>
                                        <select class="form-input col-md-12" name="department" id="department" onchange="isi_deskripsi_umum(this.value);filter_budget(this.value)" required style="background-color: #eee; cursor: not-allowed;width: 100%">
                                            <option value="{{ $department->id }}" selected>{{ $department->id }} - {{ $department->name}}</option>
                                        </select>
                                    </div>

                                    <div id="sumber_biaya" class="form-group col-md-2">
                                        <label class="col-md-12" style="padding-left:0">Sumber Biaya</label>
                                        <div class="radio">
                                            <label><input type="radio" name="sumber" value="1" class="sumber_biaya">non SPK</label>
                                        </div>
                                        <div class="radio">
                                            <label><input type="radio" name="sumber" value="0" class="sumber_biaya">SPK</label>
                                        </div>
                                        <input type="text" name="" value="" id="kode_sumber_biaya" hidden="">
                                    </div>

                                    <div id="form_waktu_dibutuhkan" class="form-group col-md-3">
                                        <label class="col-md-12" style="padding-left:0">Tgl Transaksi (dd/mm/yy)</label>
                                        <input class="form-input col-md-12" type="date" name="waktu_transaksi" min="{{$date}}" style="padding-left:15px;width: 100%" value="{{ $date }}" required>
                                    </div>
                                    <div id="form_waktu_dibutuhkan" class="form-group col-md-3">
                                        <label class="col-md-12" style="padding-left:0">Tgl dibutuhkan (dd/mm/yy)</label>
                                        <input class="form-input col-md-12" type="date" name="butuh_date" min="{{$date}}" style="padding-left:15px;width: 100%" required>
                                    </div>
                                    <div id="form_diskripsi_umum" class="form-group col-md-10">
                                        <label class="col-md-12" style="padding-left:0">Deskripsi Umum</label>
                                        <textarea name="deskripsi_umum" class="form-input col-md-12" required></textarea>
                                    </div>

                                    <div id="form_is_urgent" class="form-group col-md-3">
                                        <label class="col-md-12" style="padding-left:0">Mendadak (Urgent)</label>
                                        <div class="radio">
                                            <label><input type="radio" name="is_urgent" value="1">Ya</label>
                                        </div>
                                        <div class="radio">
                                            <label><input type="radio" name="is_urgent" value="0" checked>Tidak</label>
                                        </div>
                                    </div>

                                    {{-- <div class="form-group col-md-3" id="divbudget" hidden="">
                                        <label class="col-md-12" style="padding-left:0">Budget Tahunan</label>
                                        <select class="form-input col-md-12" list="data_department" name="budget_tahunan" style="width: 100%" id="budget_tahunan" autocomplete="off" placeholder="Pilih Budget Tahunan">
                                            <option value="0" selected>Pilih Budget Tahunan</option>
                                            @foreach($budget_no as $key => $v )
                                                <option value="{{ $v[1] }}">{{ $v[0]}}</option>
                                            @endforeach
                                        </select>
                                    </div> --}}

                                    {{-- <div class="form-group col-md-3" id="divnotifikasiBudget" hidden="" style="background: pink">
                                        <label class="col-md-12" style="padding-left:0">sumber biaya PO Berasal dari Budget</label>
                                    </div> --}}

                                    <div class="form-group col-md-3" id="divnotifikasiSPK" hidden="" style="background: pink">
                                        <label class="col-md-12" style="padding-left:0">sumber biaya PO Berasal dari SPK</label>
                                        <label class="col-md-12" style="padding-left:0">Harap di isi di </label>
                                    </div>

                                    <div class="form-group col-md-12">
                                        <label class="col-md-12" style="padding-left:0">Jumlah Form Yang Dipesan</label>
                                        <input id="jumlah_item" name="jumlah_item" type="number" class="form-control col-md-12" placeholder="Masukkan jumlah item" min="1" value="1" oninput="f_list_item()" required>
                                    </div>
                                    <div id="list_item" class="col-md-12">
                                        <div class="sub_list_item form-group col-md-12 panel panel-info">
                                            <div class="form-group panel-heading"> Form 1 </div>

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
                                                    <input id="kuantitas1" name="kuantitas[]" type="text" class="kuantitas form-input col-md-12" placeholder="Input" style="width: 100%" min="1" required>
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
                                                    <label class="col-md-12" style="padding-left:0">Order</label>
                                                    <select id="repeat_order1" name="repeat_order[]" class="repeat_order form-input col-md-12" style="width: 100%">
                                                        <option value="1" selected>New Order</option>
                                                        <option value="2">Repeat Order</option>
                                                    </select>
                                                </div>

                                                <div class="po form-group col-md-4" hidden="">
                                                    <label class="col-md-12" style="padding-left:0">Dari Purchase Order No.</label>
                                                    <select id="purchase_order1" name="purchase_order[]" class="purchase_order form-input col-md-12" style="width: 100%">
                                                    </select>
                                                </div>

                                                <!-- <div class="form-group col-md-4">
                                                    <label class="col-md-12" style="padding-left:0">Upload Gambar</label>
                                                    <input type="text" name="harga_estimasi[]" class="harga_estimasi form-control" id="harga_estimasi1" value="">
                                                    <input type="file" name="upload"><i><strong>(file yang diupload hanya bertype *.jpg, *.jpeg, *.png)</strong></i>
                                                </div> -->
                                            </div>

                                            <div class="col-md-12">
                                                <div id="form_jumlah_komparasi_supplier" class="form-group col-md-12 ">
                                                    <label class="col-md-12" style="padding-left:0">Jumlah Usulan Supplier</label>
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
                                                    <label class="col-md-12" style="padding-left:0">Usulan Supplier 1</label>
                                                    <select id="supplier1_1 komparasi_supplier_1 " name="komparasi_supplier1[1]" class="form-input col-md-12 supplier1" style="width: 100%" onchange="recomended_supplier(this.value, this.options[this.selectedIndex].text,1,1)" required>
                                                        <option selected disabled>Pilih Supplier 1</option>
                                                        @foreach($rekanan_group as $key => $value )
                                                            <option value="{{ $value->id }}">{{ $value->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div id="" class="form_komparasi_supplier_2_item1 form-group" hidden>
                                                    <label class="col-md-12" style="padding-left:0">Usulan Supplier 2</label>
                                                    <select id=" supplier2_1 komparasi_supplier_2" name="komparasi_supplier2[1]" class="form-input col-md-12 supplier2" style="width: 100%" onchange="recomended_supplier(this.value, this.options[this.selectedIndex].text,2,1)">
                                                        <option selected disabled>Pilih Supplier 2</option>
                                                        @foreach($rekanan_group as $key => $value )
                                                            <option value="{{ $value->id }}">{{ $value->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div id="" class="form_komparasi_supplier_3_item1 form-group" hidden>
                                                    <label class="col-md-12" style="padding-left:0">Usulan Supplier 3</label>
                                                    <select id="supplier3_1 komparasi_supplier_3" name="komparasi_supplier3[1]" class="form-input col-md-12 supplier3" style="width: 100%" onchange="recomended_supplier(this.value, this.options[this.selectedIndex].text,3,1)">
                                                        <option selected disabled>Pilih Supplier 3</option>
                                                        @foreach($rekanan_group as $key => $value )
                                                            <option value="{{ $value->id }}">{{ $value->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-12 coaNonBudget" id="coaNonBudget1">
                                                <div class="form-group col-md-6">
                                                    <label class="col-md-12" style="padding-left:0">Kode Coa | Item Pekerjaan</label>
                                                    <select id="coaHeader1" class="coaHeader form-input col-md-12" name="" style="width: 100%" placeholder="Pilih Item Pekerjaan" required="">
                                                        <option selected disabled>Pilih Item Pekerjaan</option>
                                                        @foreach($coaHeader as $key => $value )
                                                            <option value="{{ $value->id }}">{{$value->code}} | {{ $value->name}} </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label class="col-md-12" style="padding-left:0">Kode Coa | Item Pekerjaan detail</label>
                                                    <select id="coaDetail1" class="coaDetail form-input col-md-12" name="coa2[]" style="width: 100%" placeholder="Pilih Item Pekerjaan">
                                                    </select>
                                                </div>
                                            </div>

                                            {{-- <div class="col-md-12 coaBudget" hidden="" id="coaBudget1">
                                                <div class="form-group col-md-12">
                                                    <label class="col-md-12" style="padding-left:0">Kode Coa | Item Pekerjaan</label>
                                                    <select id="data_itempekerjaan1" class="data_itempekerjaan form-input col-md-12" name="coa[]" style="width: 100%" placeholder="Pilih Item Pekerjaan">
                                                    </select>
                                                </div>
                                            </div> --}}

                                            <div class="col-md-12">
                                                <div id="form_deskripsi_umum" class="form-group col-md-12">
                                                    <label class="col-md-12" style="padding-left:0">Spesfikasi</label>
                                                    <textarea id="deskripsi_item1" style="max-width: 1100px" name="deskripsi_item[]" class="form-input col-md-12 item_desk" required></textarea>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group col-md-4">
                                                    <label class="col-md-12" style="padding-left:0">Harga Estimasi</label>
                                                    <input type="text" name="harga_estimasi[]" class="harga_estimasi form-control" id="harga_estimasi1" value="">
                                                </div>

                                                <div class="form-group col-md-4">
                                                    <label class="col-md-12" style="padding-left:0">Total Harga Estimasi</label>
                                                    <input type="text" name="total_harga_estimasi[]" class="total_harga_estimasi form-control" id="total_harga_estimasi1" value="" readonly="">
                                                </div>

                                                <div class="divspk form-group col-md-4" id="divspk1" hidden="">
                                                    <label class="col-md-12" style="padding-left:0">SPK</label>
                                                    <select class="spk_data form-control col-md-12" name="spk[]" id="spk_1" placeholder="Pilih SPK" style="width: 100%" required>
                                                        <option value="0">All SPK</option>
                                                        @foreach($department_spk->spk as $key => $v )
                                                            <option value="{{$v['id']}}">{{ $v['name']}} | {{$v['no']}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="col-md-1 btn btn-primary fa fa-save">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <footer class="main-footer">
            <div class="pull-right hidden-xs">
                <b>Version</b> 2.4.0
            </div>
            <strong>Copyright &copy; 2014-2016 <a href="https://adminlte.io">Almsaeed Studio</a>.</strong> All rights
            reserved.
        </footer>
        <div class="control-sidebar-bg"></div>
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

    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">DATA PO YANG AKAN DI LAMPIRKAN</h4>
                </div>
                <div class="modal-body">
                    <table id="table_lampiran_po" class="table table-bordered table-striped dataTable display" role="grid" width="100%">
                        <thead style="background-color: greenyellow;">
                            <tr>
                                <th>Rekanan</th>
                                <th>No PO</th>
                                <th>Pilih</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($PO as $key => $value)
                            <tr>
                                <td>{{ $value->vendor->name }}</td>
                                <td>{{ $value->no }}</td>
                                <td>
                                    <div class="checkbox"><label><input type="checkbox" name="id_po" id="id_po"> Pilih</label></div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    @include("master/footer_table")
    @include('pluggins.alertify')
    @include('form.datatable_helper')
    @include('pluggins.editable_plugin')
    @include('form.general_form')
    <script src="{{ URL::asset('assets/global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
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

            $('select').select2();
        }

        function f_list_item() {
            var jumlah_item_old = $(".sub_list_item").length;
            var jumlah_item_new = $("#jumlah_item").val();
            if (jumlah_item_new > jumlah_item_old) {
                for (i = parseInt(jumlah_item_old) + 1; i <= jumlah_item_new; i++) {
                    tmp = a.replace('Form 1', 'Form ' + i);
                    tmp = tmp.replace('banyak_komparasi(1', 'banyak_komparasi(' + i);
                    tmp = tmp.replace('form_komparasi_supplier_1_item1', 'form_komparasi_supplier_1_item' + i);
                    tmp = tmp.replace('form_komparasi_supplier_2_item1', 'form_komparasi_supplier_2_item' + i);
                    tmp = tmp.replace('form_komparasi_supplier_3_item1', 'form_komparasi_supplier_3_item' + i);
                    tmp = tmp.replace('satuan_item1', 'satuan_item' + i);
                    tmp = tmp.replace('jumlah_komparasi1', 'jumlah_komparasi' + i);
                    tmp = tmp.replace('filter_satuan(this.value,1', 'filter_satuan(this.value,' + i);
                    tmp = tmp.replace('komparasi_supplier1[1', 'komparasi_supplier1[' + i);
                    tmp = tmp.replace('komparasi_supplier2[1', 'komparasi_supplier2[' + i);
                    tmp = tmp.replace('komparasi_supplier3[1', 'komparasi_supplier3[' + i);
                    tmp = tmp.replace('recomended_supplier(this.value, this.options[this.selectedIndex].text,1,1', 'recomended_supplier(this.value, this.options[this.selectedIndex].text,1,' + i);
                    tmp = tmp.replace('recomended_supplier(this.value, this.options[this.selectedIndex].text,2,1', 'recomended_supplier(this.value, this.options[this.selectedIndex].text,2,' + i);
                    tmp = tmp.replace('recomended_supplier(this.value, this.options[this.selectedIndex].text,3,1', 'recomended_supplier(this.value, this.options[this.selectedIndex].text,3,' + i);
                    tmp = tmp.replace('deskripsi_item1', 'deskripsi_item' + i);
                    tmp = tmp.replace('isi_deskripsi_item(this.value,1', 'isi_deskripsi_item(this.value,' + i);
                    tmp = tmp.replace('kuantitas1', 'kuantitas' + i);
                    tmp = tmp.replace(/form-selectize-item1/g, 'form-selectize-item' + i);
                    // tmp = tmp.replace(/data_itempekerjaan1/g, 'data_itempekerjaan' + i);
                    tmp = tmp.replace(/spk_1/g, 'spk_' + i);
                    tmp = tmp.replace(/sub_category_1/g, 'sub_category_' + i);
                    tmp = tmp.replace(/sub_category_1/g, 'sub_category_' + i);
                    tmp = tmp.replace(/item_id_1/g, 'item_id_' + i);
                    tmp = tmp.replace(/parent_category_1/g, ' parent_category_' + i);
                    tmp = tmp.replace(/supplier1_1/g, 'supplier1_' + i);
                    tmp = tmp.replace(/supplier2_1/g, 'supplier1_' + i);
                    tmp = tmp.replace(/supplier3_1/g, 'supplier1_' + i);
                    tmp = tmp.replace(/brand_1/g, 'brand1_' + i);
                    tmp = tmp.replace(/harga_estimasi1/g, 'harga_estimasi' + i);
                    tmp = tmp.replace(/total_harga_estimasi1/g, 'total_harga_estimasi' + i);
                    tmp = tmp.replace(/repeat_order1/g, 'repeat_order' + i);
                    tmp = tmp.replace(/purchase_order1/g, 'purchase_order' + i);
                    tmp = tmp.replace(/jumlah_komparasi1/g, 'jumlah_komparasi' + i);
                    tmp = tmp.replace(/coaHeader1/g, 'coaHeader' + i);
                    tmp = tmp.replace(/coaDetail1/g, 'coaDetail' + i);
                    tmp = tmp.replace(/coaNonBudget1/g, 'coaNonBudget' + i);
                    // tmp = tmp.replace(/coaBudget1/g, 'coaBudget' + i);
                    tmp = tmp.replace(/divspk1/g, 'divspk' + i);
                    $("#list_item").append(tmp);
                    $('select').select2();
                    filter_itempekerjaan($("#budget_tahunan").val(), "data_itempekerjaan" + i);
                    // hide_show_coa($("#budget_tahunan").val());
                    hide_show_spk_budget($("#kode_sumber_biaya").val());
                }
            } else if (jumlah_item_new < jumlah_item_old) {
                beda_item = jumlah_item_new - jumlah_item_old;
                for (i = jumlah_item_old; i > jumlah_item_new; i--)
                    $(".sub_list_item")[$(".sub_list_item").length - 1].remove();
            }

        }

        function filter_budget(val) {
            var val = val.substr(0, val.indexOf(" -"));
        }

        function recomended_supplier(val, txt, ind, item) {
            list_recomended_supplier[ind] = [val, txt];
            for (var j = 1; j <= 3; j++) {

                $("select[name='komparasi_supplier" + j + "[" + item + "]']").find('option').each(function() {
                    $(this).attr("disabled", false);
                });
            }
            for (var i = 1; i <= 3; i++) {
                val = $("select[name='komparasi_supplier" + i + "[" + item + "]']").val();
                if (val != null) {
                    for (var j = 1; j <= 3; j++) {
                        if (j != i) {
                            $("select[name='komparasi_supplier" + j + "[" + item + "]']").find('option').each(function() {
                                if ($(this).val() == val) {
                                    $(this).attr("disabled", true);
                                }
                            });
                        }
                    }
                }
            }
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
            $("#satuan_item" + item).append(option);
            $("#satuan_item" + item).empty();
            for (var i = 0; i < list_satuan.length; i++) {
                if (list_satuan[i].item_id == id_item) {
                    var option = document.createElement("option");
                    option.value = list_satuan[i].id;
                    option.innerHTML = list_satuan[i].name;
                    $("#satuan_item" + item).append(option);
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
                        tmp.innerHTML = '<option value="' + budget[i].ipId + '">' + budget[i].ipName + " | " + budget[i].ipCode + '</option>';

                        // tmp.innerHTML2 = budget[i].ipCode;
                        // strOption+='<option value="'+v.id+'">'+v.itempekerjaan+" | "+v.code+'</option>';
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
                        tmp.innerHTML = '<option value="' + budget[i].ipId + '">' + budget[i].ipCode + " | " + budget[i].ipName + '</option>';
                        //tmp.innerHTML2 = budget[i].ipCode;
                        $(".data_itempekerjaan").append(tmp);
                    }
                }
            }
        }

        function hide_show_coa(val) {
            if (val == 0) {
                $(".coaBudget").attr("hidden", true);
                $(".coaNonBudget").removeAttr('hidden');
                $(".divspk").removeAttr('hidden');
            } else {
                $(".coaBudget").removeAttr('hidden');
                $(".coaNonBudget").attr("hidden", true);
                $(".divspk").attr("hidden", true);
            }
        }

        function hide_show_spk_budget(val) {
            if (val == 0) {
                $("#divbudget").attr("hidden", true);
                $(".divspk").removeAttr('hidden');
            } else {
                $("#divbudget").removeAttr('hidden');
                $(".divspk").attr("hidden", true);
            }
        }


        function isi_deskripsi_umum(val) {
            $("textarea[name='deskripsi_umum']").val(val);
        }

        function isi_deskripsi_item(val, item) {
            $("#deskripsi_item" + item).val(val);
        }



        $(document).ready(function() {
            $('#jumlah_item').on("keydown", function(e) {
                if (e.which === 8 || e.which === 46) {
                    e.preventDefault();
                }
            });
            $('textarea').addClass('form-control');
            $('#jumlah_item').click(function() {
                $(this).select();
            });
            $('select').select2();
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
                            strItemOption += '<option value="0">All Sub Kategori<option>';
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
                                strItemOption += '<option value="' + v.itemid + '">' + v.itemname + '</option>';
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
                //$(".item_data").change(function(){

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

                        if (data.items != null) {
                            strHtml = '';
                            Eparent.find('.item_data').find('option').remove();
                            strHtml += '<option value="0">All Item</option>';
                            $(data.items).each(function(i, v) {
                                strHtml += '<option value="' + v.itemid + '">' + v.itemname + '</option>';
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
                        if (budget_id == 0) {
                            $(".coaBudget").attr("hidden", true);
                            $(".coaNonBudget").removeAttr('hidden');
                            $(".divspk").removeAttr('hidden');
                        } else {
                            $(".coaBudget").removeAttr('hidden');
                            $(".coaNonBudget").attr("hidden", true);
                            $(".divspk").attr("hidden", true);
                            $(".spk_data").val('0').trigger('change');
                            var strOption = '';
                            strOption += '<option value="">Pilih Item Pekerjaan</option>';
                            $(data.item).each(function(i, v) {
                                strOption += '<option value="' + v.id + '">' + v.itempekerjaan + " | " + v.code + '</option>';
                            });

                            $('.data_itempekerjaan').find('option').remove();
                            $('.data_itempekerjaan').append(strOption);
                        }
                    },
                    complete: function() {
                        waitingDialog.hide();
                    }
                });
            });

            $('#btn-submit').click(function() {
                $('#form-pr').submit();
            });

            $('#table_lampiran_po').DataTable({
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
                                '<tr class="group" style="background-color: #3FD5C0;""><td colspan="9"><strong>' + group + '</strong><div class="checkbox pull-right"></td></tr>'
                            );

                            last = group;
                        }
                    });
                }
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
                        fnSetAutoNumeric(parent_div.find(".harga_estimasi"));
                        fnSetMoney(parent_div.find(".harga_estimasi"), data.data);
                        // parent_div.find(".satuan_item").val(data.satuan).trigger('change');
                    },
                    complete: function() {
                        waitingDialog.hide();
                    }
                });
            });

            // $(".kuantitas").number();
            $(document).on('keyup', '.kuantitas', function() {
                var parent_div = $(this).parents('.sub_list_item');
                var harga_estimasi = parent_div.find(".harga_estimasi").autoNumeric('get');
                // var item_desk = parent_div.find(".item_data").val();
                // var _url = "{{ url('/purchaserequest/harga_estimasi') }}";
                var quantity = $(this).val();
                var estimasi = harga_estimasi * quantity;
                parent_div.find(".total_harga_estimasi").val(estimasi);
                fnSetAutoNumeric(parent_div.find(".total_harga_estimasi"));
                fnSetMoney(parent_div.find(".total_harga_estimasi"), estimasi);
            });

            var tbody = $(this).parents('.sub_list_item');
            tbody.find('.harga_estimasi').each(function(i, v) {

                fnSetAutoNumeric($(this));
                fnSetMoney($(this), $(this).val());
            });

            $(document).on('change', '.harga_estimasi', function() {
                var parent_div = $(this).parents('.sub_list_item');
                var harga_estimasi = $(this).autoNumeric('get');
                var quantity = parent_div.find(".kuantitas").val();
                $.ajax({
                    beforeSend: function() {
                        waitingDialog.show();
                    },
                    success: function() {

                        console.log(fnSetAutoNumeric(harga_estimasi));
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
                var department = $("#department").val();
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

            $(document).on('change', '.satuan_item', function() {
                var satuan = $(this).val();
                var _url = "{{ url('/purchaserequest/harga_estimasi_satuan') }}";
                var parent_div = $(this).parents('.sub_list_item');
                var quantity = parent_div.find(".kuantitas").val();
                var item_desk = parent_div.find(".item_data").val();

                $.ajax({
                    type: 'get',
                    dataType: 'json',
                    url: _url,
                    data: {
                        id: item_desk,
                        satuan: satuan
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
                        fnSetAutoNumeric(parent_div.find(".harga_estimasi"));
                        fnSetMoney(parent_div.find(".harga_estimasi"), data.data);
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
                        fnSetAutoNumeric(parent_div.find(".harga_estimasi"));
                        fnSetMoney(parent_div.find(".harga_estimasi"), data.result[0].harga_estimasi);

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

            // $(document).on('change','#budget_tahunan',function(){ 
            //     var parent_div = $(this).parents('.sub_list_item');  
            //     var item = parent_div.find(".spk_data").val();
            //     var url = "{{ url('/')}}/purchaserequest/getSPK";
            //     var department = $("#department").val();
            //     var budget = $("#budget_tahunan").val();
            //           $.ajax({
            //             type:'get',
            //             dataType:'json',
            //             url:url,
            //             data:{department_id : department, budget_id : budget},
            //             beforeSend:function()
            //             {
            //               waitingDialog.show();
            //             },
            //             success:function(data)
            //             {
            //                 var strHtml='';
            //                 strHtml +='<option value="">Pilih SPK</option>';
            //                 if(data.result.length > 0)
            //                 {
            //                     alertify.success(data.result.length+ ' SPK ditemukan');

            //                     for(var i=0;i<data.result.length;i++)
            //                     {
            //                         strHtml+='<option value="'+data.result[i].id+'" >'+data.result[i].spk_name+'</option>';
            //                     }
            //                 }
            //                 //
            //                 item.find('option').remove();
            //                 item.append(strHtml);
            //             },
            //             complete:function()
            //             {
            //                 waitingDialog.hide();
            //             }
            //           });    
            //   });

            // $(".tambah_parent_kategori").click(function(){
            var Modal = '';
            $(document).on('click', '.tambah_parent_kategori', function() {
                Modal = $("#editModalParentKategori").html();
                gentable.clear().draw();
                $('#editModalParentKategori').modal('show');
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
                gentable.clear().draw();
                $('#editModalKategori').modal('show');
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

            // $(document).on('hidden.bs.modal','#editModalKategori',function(){ 
            //      $("#editModalKategori").html(Modal);
            // });

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
                gentable.clear().draw();
                $('#editModalItem').modal('show');
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
                                strHtml += '<option value="' + data.result[i].id + '" >' + data.result[i].code + ' | ' + data.result[i].name + '</option>';
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
            $(document).on('click', '.sumber_biaya[type="radio"]', function() {
                var biaya = $(this).val()
                if (biaya == 0) {
                    // $("#divbudget").attr("hidden", true);
                    // $("#divnotifikasiSPK").removeAttr('hidden');
                    $(".divspk").removeAttr('hidden');
                    $("#divnotifikasiBudget").attr("hidden", true);
                    $('#kode_sumber_biaya').val(biaya);
                } else {
                    // $("#divbudget").removeAttr('hidden');
                    // $("#divnotifikasiBudget").removeAttr('hidden');
                    $(".divspk").attr("hidden", true);
                    $("#divnotifikasiSPK").attr("hidden", true);
                    $('#kode_sumber_biaya').val(biaya);
                }
            });

        });
    </script>
</body>

</html>