<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Admin QS | Dashboard</title>
  @include("master/header")
   <link href="{{ URL::asset('assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css')}}" rel="stylesheet" type="text/css" />
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  @include("master/sidebar_project")

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>{{ $project->name }}</h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
  
		  <ul class="breadcrumb">
			            <li>
			                <a href="{{ url('/downpaymentpurchaseorder/') }}">Down Payment Purchase Order</a>
			            </li>
			            <li>
			                <span>Detail</span>
			            </li>
			        </ul>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="col-md-12">
              	<strong>List DP PO</strong>
	
				
				@include('form.a',
							[
								'href' => url('/downpaymentpurchaseorder/create'),
								'caption' => 'Tambah',
								'class'=>'pull-right'
							])
	<hr/>
		<table class="table table-striped" id="table_data_header">
      <thead>
          <tr>
            <th>Nomor PO</th><td>{{ $po_info->no }}</td>
          </tr>
          <tr>
            <th>Rekanan</th><td>{{ $po_info->vendor->name }}</td>
          </tr>
          <tr>
            <th>Description</th><td>{{ $po_info->description }}</td>
          </tr>
          <tr>
            {{-- <th>Tanggal Input</th><td>{{ date('d-m-Y',strtotime($po_info->created_at)) }}</td> --}}
          </tr>
          <tr>
            {{-- <th>Tanggal Update</th><td>{{ date('d-m-Y',strtotime($po_info->updated_at)) }}</td> --}}
          </tr>
      </thead>
    </table>
    <input type="hidden" name="po_id" id="po_id" value="{{ $po_info->id }}" />
		<table class="table table-striped table-bordered table-hover table-responsive table-checkable nowrap stripe row-border order-column table_master" id="table_detail_dp">
			<thead style="background-color: #3FD5C0;">
				<tr>
					<th>#</th>
					<th>DP (%)</th>
					<th>Dp (Rp.) Nilai (Rp.)</th>
          <th>Deskripsi</th>
				</tr>
			</thead>
		</table>

					</div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

        </div>
        <!-- /.col -->
      </div>
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
@include("master/footer_table")
@include('pluggins.editable_plugin')
@include('pluggins.alertify')
@include('form.general_form')
<script type="text/javascript">
	var dtpicker = null;
	var gentable = null;
  var fnEditableDPPercen = function(data,type,row,meta)
  {
     return '<a href="#" class="editable_header" data-pk="'+row.id+'" data-name="percentage" data-url="{{url("/downpaymentpurchaseorder/update")}}" data-original-title="Persentase DP" data-type="text" data-value="'+data+'">'+data+'</a>';

  }
  $.ajaxSetup({
    headers: {
                  'X-CSRF-Token': $('meta[name=csrf-token]').attr('content')
                }
    });
  $.fn.editable.defaults.mode = 'inline';
	$(document).ready(function(){
		gentable = $('#table_detail_dp').DataTable(
		{
		  	 scrollY:"300px",
	        scrollCollapse: true,
	        paging:false,
	        //fixedColumns: {leftColumns: 2,rightColumns: 1},	
          processing: true,
          ajax: "{{ url('/downpaymentpurchaseorder/detail/step_dp/') }}"+"/"+$('#po_id').val(),
          columns:[
                  { data: 'no',name: 'no','sClass':'text-right',"bSortable": false},
                 { data: 'dp_percentage',name: 'dp_percentage','sClass':'text-right',"mRender":fnEditableDPPercen,"bSortable": false},
                 { data: 'dp_value',name: 'dp_value','sClass':'text-right nilai',"bSortable": true},
				         { data: 'description',name: 'description',"bSortable": false}
          ],
          "columnDefs": [],
          "order": [[ 0, 'asc' ]],
      	});

      	
      	//tooltip
      $('body').tooltip({
        selector: '[rel=tooltip]'
      });

      var tbody = $("#table_detail_dp tbody");
      gentable.on('draw',function()
      {
          

          
        $('.editable_header').editable({

          display: function(value) {
          },
          ajaxOptions: {
                type: 'post',
                dataType: 'json'
            },
            params : function(params)
            {
              params.value = $(priceInput).autoNumeric('get');
              return params;
            },
            success:function(data)
            {
              if(data.return==1)
              {
                alertify.success('success update');
                gentable.ajax.reload();
              }
            }
          
        });

         $('.editable_header').on('shown',function(e,editable)
          {
            priceInput = editable.input.$input;
            $(priceInput).autoNumeric('init',{
                aSign:'',
                aDec:'.',
                aSep:',',
                mDec:'2',
                vMin:'-99',
                vMax:'9999999999'
              });

          });

         tbody.find('.nilai').each(function(i,v)
          {
            fnSetAutoNumeric($(this));
            fnSetMoney($(this),$(this).text());
            
          });

      });

	});
</script>

</body>
</html>

