<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Admin QS | Dashboard</title>
  @include("master/header")
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  @include("master/sidebar_project")

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>{{$project->name}}</h1>

    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
             <ul class="breadcrumb">
                  <li>
                      <a href="{{ url('/inventory/inventory/stock/view_stock') }}">Inventory</a>
                  </li>
                  <li>
                      <a href="{{ url('inventory/items_project/index') }}">Barang</a>
                  </li>
                  <li>
                  	<a href="{{ url('/inventory/items_price/index').'?id='.$items_prices->item->id }}">Harga : {{ $items_prices->item->item->name }}</a>
                  </li>
                   <li>
                  	<span>Edit Harga</span>
                  </li>
              </ul>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="col-md-12">
              	<strong>Tambah Harga : {{ $items_prices->item->name }}</strong>

              	@include('form.a',[
						'href'=> url('/inventory/items_price/index').'?id='.$items_prices->item->id,
						'class'=>'pull-right',
						'caption' => 'Kembali' 
					])
					<button class="btn btn-primary col-lg-offset-2 pull-right" data-toggle="modal" data-target="#myModal"><i class="fa fa-list"></i> Daftar Harga</button>
					<hr/>
<form method="post" id="id-SaveForm" action="{{ url('/inventory/items_price/edit') }}" class="form-horizontal">
	{{ csrf_field() }}
	<input type="hidden" id="item_id" name="item_id" value="{{ $items_prices->item->id }}" />
	<input type='hidden' id='id' name='id' value='{{ $items_prices->id }}' />
	<div class="item form-group">
			    <label class="control-label col-md-3 col-sm-3 col-xs-12">Harga Sat.</label>
			    <div class="col-md-7 col-sm-7 col-xs-12">
			    	<div class="input-group">
						<div class="input-group-addon">Rp.</div>
						<input type='text' id='price' name='price' class='form-control' value="{{ $items_prices->price }}" />
						<input type="hidden" id="mprice" name="mprice" />
				</div>
			    </div>
		  	</div>

		  	<div class="item form-group">
			    <label class="control-label col-md-3 col-sm-3 col-xs-12">Satuan</label>
			    <div class="col-md-7 col-sm-7 col-xs-12">
			    	<select style='width:50%' class='form-control' name="item_satuan_id" id="item_satuan_id">
					<option value="">Pilih</option>
					@foreach($satuans as $key =>$value)
						<option value="{{ $value->id }}" {{ ($value->id == $items_prices->item_satuan_id) ? 'selected':''}} >{{ $value->name }}</option>
					@endforeach
				</select>
			    </div>
		  	</div>

		  	<div class="item form-group">
			    <label class="control-label col-md-3 col-sm-3 col-xs-12">Tanggal Harga</label>
			    <div class="col-md-7 col-sm-7 col-xs-12">
			    	<div class="input-group input-append date datePicker_">
					<div class="input-group-addon"><i class="fa fa-calendar"></i>
					</div>
					<input type="text" class="form-control " name='date' id='date' value="{{ $items_prices->date_price }}" />
				</div>
			    </div>
		  	</div>

		  	<div class="item form-group">
			    <label class="control-label col-md-3 col-sm-3 col-xs-12">PPN</label>
			    <div class="col-md-7 col-sm-7 col-xs-12">
			    	<div class="checkbox">
				    <label>
				    @if($items_prices->ppn != null)
				      <input type="checkbox" value="10" name="ppn" id="ppn" checked="true"> 
				     @else
				     	<input type="checkbox" value="10" name="ppn" id="ppn" > 
				     @endif
				    </label>
				  </div>
			    </div>
		  	</div>
		  	

		  	<div class="item form-group">
			    <label class="control-label col-md-3 col-sm-3 col-xs-12">Deskripsi</label>
			    <div class="col-md-7 col-sm-7 col-xs-12">
			    	<textarea class='form-control' name="description" id="description" cols="45" rows="5" placeholder='Descriptions'>{{ $items_prices->description }}</textarea>
			    </div>
		  	</div>

		   <div class="ln_solid"></div>
						<div class="form-group">
							<div class="col-md-6 col-md-offset-3">
								<button id="send" type="submit" class="btn btn-success"><i class="fa fa-save"></i> Simpan Perubahan</button>
								
								
							</div>
						</div>
</form>
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
<!-- ./wrapper -->

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Daftar Harga : {{ $items_prices->item->name }}</h4>
      </div>
      <div class="modal-body">
        <table class="table table-bordered table-striped dataTable" id="table_price">
        	<thead style="background-color: greenyellow;">
        		<tr>
        			<th>Project</th>
        			<th>Harga (Rp.)</th>
        			<th>Satuan</th>
        			<th>Tanggal</th>
        		</tr>
        	</thead>
        	<tbody>
        		@foreach($other_project_prices as $key => $value)
	        		<tr>
	        			<td>{{ $value->name }}</td>
	        			<td class="text-right">{{ number_format($value->price,2,".",",") }}</td>
	        			<td>{{ $value->satuan_name }}</td>
	        			<td class="text-center">{{ date('d-m-Y',strtotime($value->created_at)) }}</td>
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
@include('form.general_form')
@include('pluggins.datetimepicker_pluggin')
@include('pluggins.alertify')
<script type="application/javascript">
$(document).ready(function(){

	$('#table_price').DataTable({
		scrollY: "300px",
      	searching:false,
      	info:false,
      	//scrollCollapse: true,
      	paging: false
	});

	fnSetAutoNumeric('#price');
	$('#id-SaveForm').submit(function(e)
	{
		e.preventDefault();

		var getMoney = $('#price').autoNumeric('get');
		$('#mprice').val(getMoney);
		var url 	= $(this).attr('action');
		var data 	= $(this).serialize();
		$('#id-SaveForm input').attr('disabled','disabled');
		$.ajax({
			type:'POST',
			dataType:'json',
			url:url,
			data:data,
			beforeSend:function()
			{
				waitingDialog.show();
			},
			success:function(get)
			{
				if(get.stat)
				{
					alertify.success('success saved!',2);
					window.location.href="{{ url('/inventory/items_price/index') }}"+"?id="+$('#item_id').val();
				}
			},
			error:function(xhr,status,message)
			{
				alertify.error('Warning'+xhr.responseText);
			},
			complete:function()
			{
				$('#id-SaveForm input').removeAttr('disabled');
				waitingDialog.hide();
			}
		});
	});
});
</script>
</body>
</html>
