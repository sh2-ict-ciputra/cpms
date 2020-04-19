<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Admin QS | Dashboard</title>
  @include("master/header")
  <style type="text/css">
    .table-align-right{
      text-align: right;
    }
  </style>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
  @include("master/sidebar_project")

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    
    <section class="content-header">
      <h1 style="text-align:center">Detail Pemenang Tender {{ $project->name }}</h1>
    </section>
    <section class="content-header">
      <div class="" style="float: none">
        <button class="col-lg-1 col-md-2 col-sm-2 btn btn-primary" onclick="location.href='{{ url('/pemenangtender')}}/'" style="float: none; border-radius: 20px; padding-left: 0">
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
              <!-- <a href="{{ url('/purchaseorder/add').'?id='.$tender_menang->id }}" class="btn btn-primary"><i class="fa fa-plus"></i> Buat PO</a> -->
            </div>
            
                <div class="box-body">

                  <input type="hidden" name="tender_menang_id" id="tender_menang_id" value="{{ $tender_menang->id }}" />
                  <table class="table">
                    <thead>
                      <tr>
                        <th>Status PO</th>
                        <th>Belum Terbentuk</th>
                      </tr>
                      <tr>
                        <th>Nomor Tender</th>
                        <th>{{ $tender_menang->tender->no }}</th>
                      </tr>
                      <tr>
                        <th>Nomor Penawaran</th>
                        <th>{{ $tender_menang->penawaran->no }}</th>
                      </tr>
                      <tr>
                        <th>Rekanan</th>
                        <th style="color:blue;">{{ $tender_menang->tender_purchase_request_group_rekanan_detail->rekanan->name }}</th>
                      </tr>
                    </thead>
                    
                  </table>
                  <hr/>
                  <table class="table table-bordered table-striped dataTable" role="grid" id="table_data">
                      <thead style="background-color: greenyellow;">
                        <tr>
                          <th>Item</th>
                          <th>Brand</th>
                          <th class="table-align-right">Volume</th>
                          <th>Satuan</th>
                          <th class="table-align-right">Hrg Satuan (Rp/...)</th>
                          <th class="table-align-right sum">Total Harga (Rp.)</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                          <th colspan="5">
                              Sub Total (Rp.)
                          </th>
                          <th class="text-right"></th>
                      </tr>
                    </tfoot>
                    </table>
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

@include("master/footer_table")
@include('form.general_form')
@include('pluggins.alertify')
<script type="text/javascript">
  $.ajaxSetup({
    headers: {
      'X-CSRF-Token': $('meta[name=csrf-token]').attr('content')
    }
  });

    var gentable = null;
  $(function () {
    gentable = $('#table_data').DataTable({
          scrollY: "300px",
          //scrollX:true,
          scrollCollapse: true,
          paging: false,
          info:false,
          ajax: "{{ url('/pemenangtender/detail_data') }}"+"/"+$('#tender_menang_id').val(),
          columns:[
                 { data: 'item_name',name: 'item_name',"bSortable": true},
                 {data: 'brand_name',name:'brand_name',"bSortable":false},
                 {data: 'qty',name:'qty','sClass':'text-right',"bSortable":false},
                 {data: 'satuan_name',name:'satuan_name',"bSortable":false},
                 {data: 'price',name:'price','sClass':'text-right money',"bSortable":false},
                 {data: 'total',name:'total','sClass':'text-right money sum',"bSortable":false}
          ],
          "columnDefs": [
            {}
          ],
        "order": [[ 0, 'asc' ]],
        "footerCallback": function(row, data, start, end, display) {
                    var api = this.api();
                    api.columns('.sum', { page: 'current' }).every(function () {
                        var sum = api
                            .cells( null, this.index(), { page: 'current'} )
                            .render('display')
                            .reduce(function (a, b) {
                                var x = parseFloat(a) || 0;
                                var y = parseFloat(b) || 0;
                                return x + y;
                            }, 0);
                        $(this.footer()).html(sum);
                        fnSetAutoNumeric($(this.footer()));
                        fnSetMoney($(this.footer()),sum);
                    });
          }
    });

    var tbody = $('#table_data tbody');
    gentable.on('draw',function()
    {
        tbody.find('.money').each(function()
        {
            fnSetAutoNumeric($(this));
            fnSetMoney($(this),$(this).text());
        });
    });

});
</script>
</body>
</html>
