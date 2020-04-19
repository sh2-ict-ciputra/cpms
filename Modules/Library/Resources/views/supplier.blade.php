<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin QS | Dashboard</title>
@include("master/header")
<!-- Select2 -->
    <link rel="stylesheet" href="{{ url('/')}}/assets/bower_components/select2/dist/css/select2.min.css">
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

@include("master/sidebar_project")

<!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            {{-- Supplier --}}
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box box-primary" id="boxSupplierListTable">
                        <div class="box-header">
                            <h3 class="box-title">List Supplier / Barang</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body pad">
                            <form action="#" method="get" class="sidebar-form">
                                <div class="input-group">
                                    <input type="text" name="search_supplier_table" id="search_supplier_table" class="form-control" placeholder="Search..." style="background-color: #fff;">
                                    <span class="input-group-btn">
                                        <button type="submit" name="search" id="search-btn" class="btn btn-flat" style="background-color:#fff" disabled><i class="fa fa-search"></i></button>
                                    </span>
                                </div>
                            </form>
                            <div class="table-responsive">
                                <table id="supplier-list-table" class="table table-bordered table-striped" >
                                    <thead class="head_table">
                                    <tr>
                                        <th style="width:50px;display:none;">ID </th>
                                        <th style="width:50px;display:none;">ID GRUP </th>
                                        <th style="width:250px">Nama Supplier </th>
                                        <th>Nama Barang</th>
                                        <th>Jenis Barang</th>
                                        <th>File</th>
                                        <th>Detail</th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                            <!-- /.box-body -->
                        </div>
                    </div>
                </div>

                <div class="col-xs-12" id="detail-supplier" style="display:none">
                    <button class="btn btn-warning" id="close-form">Close</button><br>
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a id="detail_supplier_project_tab" href="#detail_supplier_project" data-toggle="tab" aria-expanded="false">Detail Supplier</a>
                            </li>
                            <li class=""><a id="kaitan_project_tab" href="#kaitan_project" data-toggle="tab" aria-expanded="false"></a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="detail_supplier_project">
                                <div class="box box-primary">
                                    <div class="box-header">
                                        <h3 class="box-title" id="box-title-supplier"></h3>
                                    </div>
                                    <!-- /.box-header -->
                                    <div class="box-body pad">
                                        <div class="col-md-12">
                                            <div class="col-md-12 col-sm-12">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <label>PIC Owner</label>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <p id="pic_owner_name"></p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>No. Telp</label>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <p id="pic_owner_telp"></p>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <label>PIC Sales</label>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <p id="pic_sales_name"></p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>No. Telp</label>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <p id="pic_sales_telp"></p>
                                                    </div>
                                                </div>
                                                <button class="btn btn-primary" id="historyFile" data-history=""><span class="fa fa-history"></span> History File</button>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="table-responsive">
                                                    <table id="project-list-table" class="table table-bordered table-striped row-border stripe" style="width:100%">
                                                        <thead class="head_table">
                                                            <tr>
                                                                <th style="display:none;">Rekan </th>
                                                                <th style="display:none;">Projek </th>
                                                                <th>Nama Proyek </th>
                                                                <th>Lokasi</th>
                                                                <th>Kaitan</th>
                                                                <th>Last Update</th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                            <!-- /.col -->
                                        </div>
                                        <!-- /.box-body -->
                                    </div>
                                    <!-- /.box -->
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- /.tab-pane -->
                            <div class="tab-pane" id="kaitan_project">
                                <div class="box box-primary">
                                    <div class="box-header">
                                        <h3 class="box-title" id="box-title-kaitan"></h3>
                                    </div>
                                    <div class="box-body pad">
                                        <div class="row">
                                            <div class="center-block text-center">No Data</div>
                                        </div>
                                    </div>
                                    <!-- /.box -->
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- /.tab-pane -->
                        </div>
                        <!-- /.tab-content -->
                    </div>
                </div>
                <!-- /.row -->
            </div>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

@include("master/copyright")


<!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

@include("master/footer_table")
@include("library::app")
<!-- Select2 -->
<script src="{{ url('/')}}/assets/bower_components/select2/dist/js/select2.full.min.js"></script>

</body>
</html>
