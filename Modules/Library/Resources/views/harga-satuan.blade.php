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
            {{-- HargaSatuan --}}
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box box-primary" id="boxHargaSatuanListTable">
                        <div class="box-header">
                            <h3 class="box-title">Harga Satuan</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body pad">
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    <li class="active">
                                        <a id="hargaSatuanDevCost_tab" href="#hargaSatuanDevCost" data-toggle="tab" aria-expanded="false">List Harga Satuan Development Cost</a>
                                    </li>
                                    <li class=""><a id="hargaSatuanConCost_tab" href="#hargaSatuanConCost" data-toggle="tab" aria-expanded="false">List Harga Satuan Construction Cost</a></li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="hargaSatuanDevCost">
                                        <div class="table-responsive">
                                            <table id="DevCost-list-table" class="table table-bordered table-striped" >
                                                <thead class="head_table">
                                                <tr>
                                                    <th></th>
                                                    <th>COA CPMS</th>
                                                    <th>Item Pekerjaaan</th>
                                                    <th>Harga Min /sat</th>
                                                    <th>Harga Max /sat</th>
                                                    {{-- <th>Detail</th> --}}
                                                </tr>
                                                <tr>
                                                    <th></th>
                                                    <th><input type="text" class="input-sm form-control searchInput"></th>
                                                    <th><input type="text" class="input-sm form-control searchInput"></th>
                                                    <th><input type="text" class="input-sm form-control searchInput"></th>
                                                    <th><input type="text" class="input-sm form-control searchInput"></th>
                                                    {{-- <th></th> --}}
                                                </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="hargaSatuanConCost">
                                        <div class="box table-responsive">
                                            <table id="ConCost-list-table" class="table table-bordered table-striped" >
                                                <thead class="head_table">
                                                <tr>
                                                    <th></th>
                                                    <th>COA CPMS</th>
                                                    <th>Item Pekerjaaan</th>
                                                    <th>Harga Min /sat</th>
                                                    <th>Harga Max /sat</th>
                                                    {{-- <th>Detail</th> --}}
                                                </tr>
                                                <tr>
                                                    <th></th>
                                                    <th><input type="text" class="input-sm form-control searchInput"></th>
                                                    <th><input type="text" class="input-sm form-control searchInput"></th>
                                                    <th><input type="text" class="input-sm form-control searchInput"></th>
                                                    <th><input type="text" class="input-sm form-control searchInput"></th>
                                                    {{-- <th></th> --}}
                                                </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            <!-- /.box-body -->
                        </div>
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
@include("library::app_hargasatuan")
<!-- Select2 -->
<script src="{{ url('/')}}/assets/bower_components/select2/dist/js/select2.full.min.js"></script>

</body>
</html>
