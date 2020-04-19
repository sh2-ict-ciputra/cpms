<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin QS | Dashboard</title>
    @include("master.header")
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
    @include("master.sidebar_rekanan")
    <div class="content-wrapper">
        <section class="content-header">
            <h1>Data Rekanan</h1>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a id="pricelist_rekanan_tab" href="#pricelist_rekanan" data-toggle="tab" aria-expanded="false">Pricelist Rekanan</a>
                            </li>
                            <li class="">
                                <a id="po_tab" href="#po_rekanan" data-toggle="tab" aria-expanded="false">List PO</a>
                            </li>
                            <li class="">
                                <a id="mou_tab" href="#mou_rekanan" data-toggle="tab" aria-expanded="false">List MOU</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="pricelist_rekanan">
                                <div class="box box-primary">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">Pricelist Rekanan</h3>
                                    </div>
                                    <form role="form">
                                        <div class="box-body">
                                            <div class="col-md-12">
                                                <div class="col-md-12"><button class="btn btn-primary addNewPriceList" data-rekan='{{ $rekanan_group->id }}'><span class="fa fa-plus"></span> Tambah Pricelist </button>
                                                </div>
                                                <div class="table-responsive">
                                                    <table class="table table-striped table-pricelist table-bordered">
                                                        <thead>
                                                        <tr>
                                                            <th>Last Update</th>
                                                            <th>Preview</th>
                                                            <th>Jenis Barang</th>
                                                            <th>Nama Barang</th>
                                                            <th>Tanggal Berlaku</th>
                                                            <th>Action</th>
                                                        </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="tab-pane" id="po_rekanan">
                                <div class="box box-primary">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">List PO</h3>
                                    </div>
                                    <form role="form">
                                        <div class="box-body">
                                            <div class="col-md-12">
                                                <div class="table-responsive">
                                                    <table class="table table-striped table-po table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>Last Update</th>
                                                                <th>Jenis Barang</th>
                                                                <th>Nama Barang</th>
                                                                <th>Project</th>
                                                                <th>Alamat Project</th>
                                                                <th>Nomor PO</th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="tab-pane" id="mou_rekanan">
                                <div class="box box-primary">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">List MOU</h3>
                                    </div>
                                    <form role="form">
                                        <div class="box-body">
                                            <div class="col-md-12">
                                                <div class="table-responsive">
                                                    <table class="table table-striped table-mou table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>Nama Proyek</th>
                                                                <th>Nomor MOU</th>
                                                                <th>Nama Barang</th>
                                                                <th>Jenis MOU</th>
                                                                <th>File</th>
                                                                {{-- <th>Detail</th> --}}
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@include("master.copyright")

<!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
<div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

@include("master.footer_table")
@include("rekanan::user.app_databarang")
</body>
</html>
