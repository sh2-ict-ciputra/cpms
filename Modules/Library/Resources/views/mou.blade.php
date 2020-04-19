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
            {{-- mou --}}
        </section>

        <!-- Main content -->
        <section class="content">
            

            <div class="row">
                <div class="col-xs-12">
                    <div class="box box-primary">
                        <div class="box-header">
                            <h3 class="box-title">Memorandum of Understanding (MOU)</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body pad">
                            <form action="#" method="get" class="sidebar-form">
                                <div class="input-group">
                                    <input type="text" name="search_mou_table" id="search_mou_table" class="form-control" placeholder="Search..." style="background-color: #fff;">
                                    <span class="input-group-btn">
                                        <button type="submit" name="search" id="search-btn" class="btn btn-flat" style="background-color:#fff" disabled><i class="fa fa-search"></i></button>
                                    </span>
                                </div>
                            </form>
                            <div class="col-md-12">
                                <button class="btn btn-primary" id="addNewMOU"><span class="fa fa-plus"> Tambah MOU</button>
                            </div>
                            <div class="table-responsive">
                                <table id="mou-list-table" class="table table-bordered table-hover" >
                                    <thead class="head_table">
                                    <tr>
                                        <th>Nama Proyek</th>
                                        <th>Nomor MOU</th>
                                        <th>Nama Supplier</th>
                                        <th>Nama Barang</th>
                                        <th>Jenis MOU</th>
                                        <th>File</th>
                                        {{-- <th>Detail</th> --}}
                                    </tr>
                                    </thead>
                                </table>
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
@include("library::app_mou")
<!-- Select2 -->
<script src="{{ url('/')}}/assets/bower_components/select2/dist/js/select2.full.min.js"></script>

</body>
</html>
