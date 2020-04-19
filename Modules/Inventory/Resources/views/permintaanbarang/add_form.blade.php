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
			                      <a href="{{ url('/inventory/inventory/stock/view_stock') }}">Inventory</a>
			                  </li>
			                  <li>
			                      <a href="{{ url('/inventory/permintaan_barang/index') }}">Permintaan Barang</a>
			                  </li>
			                  <li>
			                  	<span>Tambah</span>
			                  </li>
			              </ul>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="col-md-12">
              	<strong>Tambah Permintaan Barang</strong>
              	<hr/>
					<form action="{{ url('/inventory/permintaan_barang/create') }}" method="post" class="form-horizontal form-label-left" id="form_data">
						{{ csrf_field() }}
						<div class="col-lg-6 col-md-6 col-xs-12">
							<div class="item form-group">
							    <label class="control-label col-md-3 col-sm-3 col-xs-12">PT</label>
							    <div class="col-md-9 col-sm-9 col-xs-12">
							    	<div class="">
								    	<select class='form-control select2' name='pt_id' id='pt_id' style="width:100%">
                        <option value="">Pilih PT</option>
                        @foreach($pts as $key => $value)
                          <option value='{{ $value->pt->id }}'>({{ $value->pt->code }}) - {{ $value->pt->name }}</option>
                        @endforeach
                      </select>
										{{-- <div class="input-group-addon"><a href="{{ url('/pt') }}"><i class="fa fa-plus"></i></a></div> --}}
									</div>
							    </div>
						  	</div>

						  	<div class="item form-group">
							    <label class="control-label col-md-3 col-sm-3 col-xs-12">Departemen</label>
							    <div class="col-md-9 col-sm-9 col-xs-12" id="addpt">
							    	<div class="">
                      <select class='form-control select2' name='department_id' id='department_id' style="width:100%">
                      <option value="">Pilih Department</option>
											@foreach($departments as $key => $value)
												<option value='{{ $value->id }}'>({{ $value->code }}) - {{ $value->name }}</option>
											@endforeach
										</select>
										{{-- <div class="input-group-addon"><a href="{{ url('/department') }}"><i class="fa fa-plus"></i></a></div> --}}
									</div>
							    </div>
						  	</div>

                <div class="item form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12">No. SPK</label>
                  <div class="col-md-9 col-sm-9 col-xs-12">
                    <select class='form-control select2' name='spk_id' id='spk_id' style="width:100%">
                      <option value="">Pilih SPK</option>
                      @foreach($spks as $key => $value)
                        <option value='{{ $value->id }}'>{{ $value->no }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>

                <div id="divPilihPR" class="item form-group">
                  <label class="col-md-3 control-label">Purchase request</label>
                  <div class="col-md-9">
                      @if($flag == 1)
                          <select id="pilih_pr" class="form-control select2" name="pilih_pr" required>
                              <option value="{{$purchaserequest->id}}" selected="">{{$purchaserequest->no}}</option>
                          </select>
                      @else
                          <select id="pilih_pr" class="form-control select2" name="pilih_pr">
                              <option value="" disabled="" selected="">Pilih PR</option>
                          </select>
                      @endif
                  </div>
              </div>
            </div>
            

						<div class="col-lg-6 col-md-6 col-xs-12">
							<div class="item form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Status Permintaan</label>
								<div class="col-md-7 col-sm-7 col-xs-12">
									<select class='form-control select2' name='status' id='status'>
										<option value="">Pilih</option>
										@foreach($statusPermintaans as $key => $value)
											<option value="{{ $value->id }}">{{ $value->name }}</option>
										@endforeach
									</select>
								</div>
						    </div>


						  	<div class="item form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12">Tanggal</label>
								<div class="col-md-7 col-sm-7 col-xs-12">
									<div class="input-group input-medium date date-picker datePicker_" >
										<input type="text" class="form-control" name='date' id='date' value="<?php echo date('Y-m-d'); ?>">
										<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
									</div>	
								</div>
							</div>

							<div class="item form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12">Deskripsi</label>
								<div class="col-md-7 col-sm-7 col-xs-12">
									<textarea class='form-control' name="description2" id="description2" cols="45" rows="5" placeholder="Description"></textarea>
								</div>
							</div>
            </div>

            <div class="col-lg-12 col-md-12 col-xs-12">
              <table id="datatable-items" class="table table-striped table-bordered table-hover table-responsive table-checkable order-column nowrap table_master">
                <colgroup>
                  <col style="width: 1px;">
                  <col>
                  <col style="width:120px;">
                </colgroup>
                <thead>
                  
                  <tr>
                    <th>Kategori</th>
                    <th style="width:30%">Item</th>
                    <th style="width:30%">Qty</th>
                    <th hidden>item_id</th>
                    <th hidden>item_satuan_id</th>
                    <th>Satuan</th>
                    <th>Tanggal Dibutuhkan</th>
                    <th>Deskripsi</th>
                    <th>Tersedia</th>
                  </tr>
                </thead>
              </table>
            </div>

						<div class="ln_solid"></div>
						<div class="form-group">
							<div class="col-md-6 col-md-offset-3">
								<button id="send" type="submit" class="btn btn-success"><i class="fa fa-save"></i> Simpan</button>
								<button id="reset" type="reset" class="btn btn-warning"><i class="fa fa-times"></i> Reset</button>
								@include('form.a',[
									'href'=>url('/inventory/permintaan_barang/index'), 
									'caption' => 'Batal' 
								])
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

@include("master/footer_table")
@include('pluggins.select2_pluggin')
@include('pluggins.alertify')
@include('form.general_form')
@include('pluggins.datetimepicker_pluggin')
<script type="text/javascript">
  $.ajaxSetup({
  headers: {
                'X-CSRF-Token': $('meta[name=csrf-token]').attr('content')
              }
  });

  var gentable = null;
  
	$(document).ready(function()
	{

		$('.select2').select2();
		$('#form_data').submit(function(e){
			e.preventDefault();
			var _datasend = $(this).serialize();
			var _url = $(this).attr('action');
			$('#form_data input').attr("disabled", "disabled");
			
              $.ajax({
	                type: 'POST',
	                url: _url,
	                data: _datasend,
	                dataType: 'json',
	                beforeSend:function(){
	                	waitingDialog.show();
	                },
	                success:function(data){
	                	if(data.return=='1'){
	                		alertify.success('success saved!',2);
	                		window.location.href = "{{ url('/inventory/permintaan_barang/index') }}";
	                	}
	                	else{
	                		alertify.error('Warning : Mohon periksa kembali data-data anda');
	                	}
	                },
	                error:function(xhr,status,errormessage)
	                {
	                	alertify.error(xhr.reponseText);
	                },
	                complete:function()
	                {
	                	waitingDialog.hide();
	                	$('#form_data input').removeAttr('disabled');
	                  	$('.form-group').removeClass('has-success');
	                }
              });
		});

        // $('#status').change(function(){
        //   var url = "{{ url('/')}}/inventory/permintaan_barang/getPurchaseRequest";
        //   var item = $("#pilih_pr");
        //   var send = $(this).val();
        //   var department = $("#department_id").val();
        //     if(send == 10){
        //         $.ajax({
        //           type:'post',
        //           dataType:'json',
        //           url:url,
        //           data:{department_id : department},
        //           beforeSend:function()
        //           {
        //             waitingDialog.show();
        //           },
        //           success:function(data)
        //           {
        //               var strHtml='';
        //               strHtml +='<option value="">Pilih PR</option>';
        //               if(data.result.length > 0)
        //               {
        //                   alertify.success(data.result.length+ ' PR ditemukan');
                          
        //                   for(var i=0;i<data.result.length;i++)
        //                   {
        //                       strHtml+='<option value="'+data.result[i].id+'" >'+data.result[i].pr_no+'</option>';
        //                   }
        //               }
        //               //
        //               item.find('option').remove();
        //               item.append(strHtml);
        //           },
        //           complete:function()
        //           {
        //               waitingDialog.hide();
        //           }
        //         });
        //         $('#divPilihPR').show();
        //     }else{
        //         $('#divPilihPR').hide();
        //     }      
        // });

        $('#department_id').change(function(){
          var url = "{{ url('/')}}/inventory/permintaan_barang/getPurchaseRequest";
          var item = $("#pilih_pr");
          var department = $(this).val();
          var send = $("#status").val();
          $.ajax({
            type:'post',
            dataType:'json',
            url:url,
            data:{department_id : department},
            beforeSend:function()
            {
              waitingDialog.show();
            },
            success:function(data)
            {
                var strHtml='';
                strHtml +='<option value="">Pilih PR</option>';
                if(data.result.length > 0)
                {
                    alertify.success(data.result.length+ ' PR ditemukan');
                    
                    for(var i=0;i<data.result.length;i++)
                    {
                        strHtml+='<option value="'+data.result[i].id+'" >'+data.result[i].pr_no+'</option>';
                    }
                }
                //
                item.find('option').remove();
                item.append(strHtml);
            },
            complete:function()
            {
                waitingDialog.hide();
            }
          });
          $('#divPilihPR').show();
        });

        $('#status').select2();

        gentable = $('#datatable-items').DataTable({
        // scrollY:        "300px",
        scrollCollapse: true,
        paging:         false,
        processing: true,
        ajax: "{{ url('/inventory/permintaan_barang/items_stock') }}",
        columns:[
                    { data: 'category',name: 'category',"bSortable": true},
                    { data: 'item_name',name: 'item_name','className':'text-left',"bSortable": false},
                    {       
                          name: 'quantity',
                          data: 'quantity',
                          "className": "action text-center",
                          "data": 'null',
                          "bSortable": false,
                          "defaultContent": "" +
                          "<input type='number' name='qty_item[]' id='qty_item' class='text-right qty_item_add form-control' min='0' value='0' />"
                    },
                    {
                      name: "item_id ", "data": "item_id", 
                      render: function (data, type, row) {
                          return '<input class="form-control item_id" id="item_id" name="item_id[]" type="hidden"  value = "' + row.item_id + '"  >';
                      }
                    },
                    {
                      name: "item_satuan_id ", "data": "item_satuan_id", 
                      render: function (data, type, row) {
                          return '<input class="form-control item_satuan_id" id="item_satuan_id" name="item_satuan_id[]" type="hidden"  value = "' + row.item_satuan_id + '"  >';
                      }
                    },
                    { data: 'satuan',name: 'satuan',"bSortable": false},
                    {       
                      name: 'tanggalbutuh',
                      data: 'tanggalbutuh',
                      "className": "action text-center",
                      "data": 'tanggalbutuh',
                      "bSortable": false,
                      "defaultContent": "" +
                      "<input type='date' class='form-control' name='butuh_date[]' id='butuh_date'  value=''>"
                    },
                    {       
                      name: 'deskripsi',
                      data: 'deskripsi',
                      "className": "action text-center",
                      "data": 'deskripsi',
                      "bSortable": false,
                      "defaultContent": "" +
                      "<textarea name='description[]' id='description' row='5'></textarea>"
                    },
                    { data: 'tersedia',name: 'tersedia','className':'text-right',"bSortable": false},
              ],
              "columnDefs": [
            {targets:[0],visible:false},
            {targets:[3],className: "hidden"},
            {targets:[4],className: "hidden"} 
            
            ],
          "order": [[0,'asc']],//,
              "drawCallback": function ( settings ) {
                  var api = this.api();
                  var rows = api.rows( {page:'current'} ).nodes();
                  var last=null;
                  api.column(0, {page:'current'} ).data().each( function ( group, i ) {
                      if ( last !== group ) {
                          $(rows).eq( i ).before(
                              '<tr class="group success"><td colspan="6" style="text-align:left;padding:10px;"><strong>'+group+'</strong></td></tr>'
                          );
      
                          last = group;
                      }
                  });
              },
              // "initComplete": function(settings, json) {
              //     $('.group').nextUntil('.group').css( "display", "none" );
              //   }

        });

        $('#pilih_pr').change(function(){
          var id_pr = $(this).val();
          var _url = "{{ url('/')}}/inventory/permintaan_barang/getQuantityPr";

          $.ajax({
            type:'post',
            dataType:'json',
            url:_url,
            data: {
              id_pr : id_pr,
            },
            beforeSend:function(){
              waitingDialog.show();
            },
            success:function(data){
              if(data.length > 0){
                gentable.clear().draw();
                $(data).each(function(i,v)
                {

                    var items = {
                      category:v.category,
                      item_name:v.item_name,
                      null:"<input type='number' name='qty_item[]' id='qty_item' class='text-right qty_item_add form-control' min='0' value='"+v.quantity+"' />",
                      item_id:v.item_id,
                      satuan:v.satuan,
                      tanggalbutuh:"<input type='date' class='form-control' name='butuh_date[]' id='butuh_date' value=''>",
                      deskripsi:"<textarea name='description[]' id='description' row='5'></textarea>",
                      tersedia:v.tersedia,
                      item_satuan_id:v.item_satuan_id
                    };

                    gentable.row.add(items);
                });


                gentable.draw();
              }
            },
            complete:function(){
              waitingDialog.hide();     
            }
          });
      });
	});


</script>
</body>
</html>