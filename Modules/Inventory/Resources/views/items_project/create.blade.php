<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
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
      <h1>{{ $project->name }}</h1>

    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
             <ul class="breadcrumb">
                  <li>
                      <a href="{{ url('/inventory/stock/view_stock') }}">Inventory</a>
                  </li>
                  <li>
                      <a href="{{ url('/inventory/items_project/index') }}">Barang Proyek</a>
                  </li>
                  <li>
                  	<span>Tambah</span>
                  </li>
              </ul>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="col-md-12">
              	<strong>Tambah Barang Proyek</strong>

              	@include('form.a',[
						'href'=> url('/inventory/items_project/index'),
						'class'=>'col-md-offset-1 pull-right',
						'caption' => 'Kembali' 
					])
				<button class="btn btn-primary col-md-offset-1 pull-right" id="btn-all-item" data-toggle="modal" data-target="#modal-pilih-item">Pilih Semua Item</button>
				<button class="btn btn-primary col-md-offset-1 pull-right" id="btn-copy" data-toggle="modal" data-target="#modal-copy-items">Copy Dari Project</button>

					<hr/>
<form method="post" id="id-SaveForm" action="{{ url('/inventory/items_project/store') }}" autocomplete="off" class="form-horizontal form-label-left">
			{{ csrf_field() }}
		  	<div class="item form-group">
			   <label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
			    <div class="col-md-7 col-sm-7 col-xs-12">
			  	<div class="checkbox">
				    <label>
				      <input type="checkbox" name='satuan_warning' id='satuan_warning' value="1"> Aktifkan Pemberitahuan
				    </label>
				  </div>
				</div>
			</div>
		  	
		  	<div class="item form-group">
			   <label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
			    <div class="col-md-7 col-sm-7 col-xs-12">
		  	<div class="checkbox">
			    <label>
			      <input type='checkbox' name='is_inventory' id='is_inventory' /> Inventaris
			    </label>
			  </div>
			</div>
		</div>
		<input type="hidden" name="id_arr_items" id="id_arr_items" value="[]" />
		<div class="item form-group">
			   <label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
			    <div class="col-md-7 col-sm-7 col-xs-12">
			  <div class="checkbox">
			    <label>
			      <input type='checkbox' name='is_consumable' id='is_consumable' /> Pakai Habis
			    </label>
			  </div>
			</div>
		</div>

			  <div class="item form-group">
			    <label class="control-label col-md-3 col-sm-3 col-xs-12">Stok Min.</label>
			    <div class="col-md-7 col-sm-7 col-xs-12">
			    	<input type='number' id='stock_min' name='stock_min' class='form-control' value="0" required style='width:50%'/>
			    </div>
		  	</div>

		  	<div class="item form-group">
			    <label class="control-label col-md-3 col-sm-3 col-xs-12">PPh</label>
			    <div class="col-md-7 col-sm-7 col-xs-12">
			    	<input type='number' id='pph' name='pph' class='form-control' value="0" step =".01" required style='width:50%'/>
			    </div>
		  	</div>

		  	 <div class="item form-group">
			    <label class="control-label col-md-3 col-sm-3 col-xs-12">Deskripsi</label>
			    <div class="col-md-7 col-sm-7 col-xs-12">
			    	<textarea class='form-control' name="description" id="description" cols="45" rows="5"></textarea>
			    </div>
		  	</div>
		  
		  <div class="ln_solid"></div>
						<div class="form-group">
							<div class="col-md-6 col-md-offset-3">
								<button id="send" type="submit" class="btn btn-success"><i class="fa fa-save"></i> Simpan</button>
								
								
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

<!-- Modal Pilih Semua Item -->
<div class="modal fade" id="modal-pilih-item" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Pilih Item</h4>
      </div>
      <div class="modal-body">
      	<div class="checkbox">
	        <label>
	          <input type="checkbox" class="check_all" /> Pilih Semua
	        </label>
	      </div>
        <table class="table display" id="table-items" style="width:100%">
        	<thead style="background-color:greenyellow;">
        		<tr>
        			<th>Pilih</th>
        			<th>Item</th>
        			<th>Kode</th>
        		</tr>
        	</thead>
        	<tbody>
        		@foreach($items as $key => $value)
        			<tr>
        				<td>
        					<div class="checkbox">
						        <label>
						          <input type="checkbox" class="check-item" value="{{ $value->id }}"> Pilih
						        </label>
						      </div>
        				</td>
        				<td>
        					{{ $value->name }}
        				</td>
        				<td>
        					{{ $value->kode }}
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

<!-- Modal Copy Item -->
<div class="modal fade" id="modal-copy-items" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Copy Dari Project</h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal" action="{{ url('/inventory/item_projects/create_from_project') }}" method="post" id="form_asal_pryek" name="form_asal_pryek">
        	@csrf
		  <div class="form-group">
		    <label for="inputEmail3" class="col-sm-4 control-label">Project Asal</label>
		    <div class="col-sm-8">
		    	<select name="from_project" id="from_project" class="form-control">
		    		<option value="">Pilih</option>
		    		@foreach($all_project as $key => $value)
		    			<option value="{{ $value->id }}">{{ $value->name }}</option>
		    		@endforeach
		    	</select>
		    </div>
		  </div>

		  <div class="form-group">
		    <div class="col-sm-offset-2 col-sm-10">
		      <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> Copy</button>
		    </div>
		  </div>
		</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>
<!-- ./wrapper -->
@include("master/footer_table")
@include('pluggins.alertify')
@include('pluggins.select2_pluggin')
<script type="text/javascript">
$.ajaxSetup({
    headers: {
        'X-CSRF-Token': $('input[name=_token]').val()
    }
});
var gentable = null;
var arritem = [];
$(document).ready(function()
{

	$('input[type="number"]').click(function()
	{
		$(this).select();
	});
	
	$('#from_project').select2();

	$('#id-SaveForm').submit(function(e)
	{
		e.preventDefault();
		var alert_ul = '';

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
					alertify.success('berhasil di simpan!',2);
				}
				else
				{					
					alertify.error('Gagal  '+get.msg);
				}

				if(get.data.length > 0)
				{
					alert_ul += '<ul>';

					$(get.data).each(function(i,v)
					{
						alert_ul += '<li>'+v+'</li>';
					});

					alert_ul += '</ul>';
					alertify.alert('Info',alert_ul + " Sudah ada pada Proyek ini");
				}
				
				return false;
			},
			error:function(xhr,status,message)
			{
				alertify.error('Gagal');
			},
			complete:function()
			{
				$('#id-SaveForm').trigger('reset');
				$('input[type="checkbox"]').prop('checked',false);
				arritem = [];
				$('#id_arr_items').val(arritem);
				$('.has-success').removeClass('has-success');
				$('#id-SaveForm input').removeAttr('disabled');
				waitingDialog.hide();
			}
		});
	});

	gentable = $('#table-items').DataTable({
		scrollY: "300px",
		//scrollX: "900px",
        //scrollX:true,
        scrollCollapse: true,
        paging: false//,
        //"order": [[ 0, 'asc' ]]
	});

	$('.check_all').click(function()
	{
		
		if($(this).is(':checked'))
		{
			$('.check-item').prop('checked',true);
			$('.check-item').each(function(i,v)
			{
				arritem.push($(this).val());
			});
		}
		else
		{
			$('.check-item').prop('checked',false);
			arritem = [];
		}
		$('#id_arr_items').val(JSON.stringify(arritem));
	});

	var tbody = $('#table-items tbody');
	tbody.on('click','.check-item',function()
			{
				if($(this).is(':checked'))
				{
					if(arritem.length > 0)
					{
						if(arritem.includes($(this).val()) == false)
						{
							arritem.push($(this).val());
						}
					}
					else
					{
						arritem.push($(this).val());
					}
				}
				else
				{
					if(arritem.includes($(this).val()) == true)
					{
						var index = arritem.indexOf($(this).val());
						arritem.splice(index,1);
					}
					
				}

				$('#id_arr_items').val(JSON.stringify(arritem));
			});

	$('#form_asal_pryek').submit(function(e)
	{
		e.preventDefault();
		var ul_alert ='';
		var url 	= $(this).attr('action');
		var data 	= $(this).serialize();

		$.ajax({
			type:'POST',
			dataType:'json',
			url:url,
			data:data,
			beforeSend:function()
			{
				waitingDialog.show();
			},
			success:function(data)
			{	
				var ul_alert = '';
				if(data.stat)
				{
					alertify.success('berhasil di simpan!',2);
				}
				else
				{
					alertify.error('Gagal  '+data.msg);
				}

				if(data.data.length > 0)
				{
					ul_alert += '<ul>';
					$(data.data).each(function(i,v){
						ul_alert += '<li>'+v+'</li>';
					});
					ul_alert += '</ul>';

					alertify.alert('Info',ul_alert +' Sudah ada');
				}

				return false;
			},
			error:function(xhr,status,message)
			{
				alertify.error('Gagal');
			},
			complete:function()
			{
				$('input[type="checkbox"]').prop('checked',false);
				arritem = [];
				$('#id_arr_items').val(arritem);

				waitingDialog.hide();

			}
		});
	});

	$('input[type="checkbox"]').prop('checked',false);
});

</script>
</body>
</html>