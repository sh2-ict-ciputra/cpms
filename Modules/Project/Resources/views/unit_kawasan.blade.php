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
      <h1>Data Proyek <strong>{{ $projectkawasan->project->name }}</strong></h1>

    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ url('/')}}/project/kawasan/">Kawasan {{ $blok->kawasan->name }}</a></li>
                <li class="breadcrumb-item"><a href="{{ url('/')}}/project/bloks/?id={{ $blok->kawasan->id}}">Blok {{ $blok->name }}</a></li>
                <li class="breadcrumb-item active">Unit</li>
              </ol>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive">
              <form action="{{ url('/')}}/project/unit/senderems" method="post" name="form1">
                <!-- <a href="{{ url('/')}}/project/add-unit?id={{ $blok->id }}" class="btn btn-primary"><i class="glyphicon glyphicon-plus-sign"></i>Tambah Unit</a> -->
                <input type="hidden" name="blok_id" id="blok_id" value="{{ $blok->id }}">
                <input type="hidden" name="unit_id" id="unit_id"> 

                <div class="col-xs-3">
                  @if ( count($blok->units) > 0 )
                  <span>No. Unit Terakhir = {{ $blok->units->last()->name }}</span>
                  @endif
                  <!-- <input type="text" class="form-control" name="total_unit" id="total_unit" placeholder="total unit yang akan dibangun"> -->
                  <i class="fa fa-refresh ld ld-spin" id="loading" style="display: none;"></i><br/>
                  <!-- <button type="button" class="btn btn-info" onClick="generateunit();" id="btn_generate">Buat Unit</button> -->
                  <button type="button" class="btn btn-info" id="btn_generate" data-toggle="modal" data-target="#modalForm">Buat Unit</button>

                  <br/><br/>
                  <button class="btn btn-warning" type="submit">Simpan</button>
                  <button class="btn btn-danger" type="button" id="btn_del_unit">Delete</button><br><br>
                  <span>Total Unit : <strong>{{ count($blok->units)}}</strong></span><br/>
                  <span>Total Luas Tanah : <strong>{{ number_format($blok->total_tanah) }} m2</strong></span><br/>
                  <span>Total Luas Bangunan : <strong>{{ number_format($blok->total_bangunan )}} m2</strong></span><br><br>
                </div>
                <table id="index" class="table table-bordered table-hover">   
                  {{ csrf_field() }}              
                  <thead style="background-color: greenyellow;" class="head_table">
                    <tr>   
                      <th>No.</th>              
                      <th>Unit No.</th>
                      <th>Type Unit</th>
                      <th>LB(m2)</th>
                      <th>LT(m2)</th>
                      <th>Kategori</th>
                        <!--td>Progress</td-->
                      <th>% Bayar Kons</th>
                      <th>Tanggal Akad</th>
                      <th>Renc.ST-Kons</th>
                      <th>Kirim ke EREMS</th>
                      <th>Status</th>
                      <th>Edit</th>
                    </tr>
                  </thead>
                  <tfoot id="tfoot" style="display:table-header-group;">
                    <tr>   
                      <th>No. </th>              
                      <th>Unit No.</th>
                      <th>Type Unit</th>
                      <th>LB(m2)</th>
                      <th>LT(m2)</th>
                      <th>Kategori</th>
                        <!--td>Progress</td-->
                      <th>% Bayar Kons</th>
                      <th>Tanggal Akad</th>
                      <th>Renc.ST-Kons</th>
                      <th>Kirim ke EREMS</th>
                      <th>Status</th>
                      <th>Edit   </th>
                    </tr>
                  </tfoot>
                  <tbody>
                    @foreach ( $unit as $key => $value )
                    
                    <tr>
                      <td>{{ $key + 1 }}</td>
                      <td>{{ $value->name }}</td>  
                      <td>{{ $value->type->name or '' }}</td>                    
                      <td>{{ number_format($value->bangunan_luas,2) }}</td>
                      <td>{{ number_format($value->tanah_luas+$value->luas_tanah_tambahan,2) }}</td>
                      <td>
                        @if ( $value->is_sellable == 1 )
                          @if (  $value->tag_kategori == 'B' )
                            Bangunan               
                          @else
                            Kavling
                          @endif
                        @else
                          Tidak Dijual
                        @endif
                      </td>
            
                      <!--td>
                        @if ( count($value->progresses) > 0 ) 
                          @if ( $value->status == 7 )
                            ST 1 : <strong>{{ date("d/M/Y",strtotime($value->st_1)) }}</strong>
                          @else
                            ST 2 : <strong>{{ date("d/M/Y",strtotime($value->st_1)) }}</strong>
                          @endif
                        @else
                        0%
                        @endif
                      </td-->
                      <td>{{ number_format($value->pembayaran,2) }}</td>
                      <td>
                        @if ( date("d/m/Y",strtotime($value->tanggal_akad)) != date("d/m/Y",strtotime("1970-01-01")) ) 
                          {{ date("d/M/Y",strtotime($value->tanggal_akad)) }}
                        @endif
                      </td>
                      <td>
                        @if ( date("d/m/Y",strtotime($value->serah_terima_plan)) != date("d/m/Y",strtotime("1970-01-01")) ) 
                          {{ date("d/M/Y",strtotime($value->serah_terima_plan)) }}
                        @endif
                      </td>
                      <td>
                        @if ( $value->unit_type_id != "")
                          @if ( $value->unit_id == "" )
                            @if($value->unit_hadap_id != "" && $value->purpose_id != "")
                              <input type="checkbox" name="unit_[{{$value->id}}]" id="unit_{{$value->id}}" onClick="addunitdelete('{{$value->id}}')" value="{{$value->id}}">
                            @else
                              <span style="color:red">Data Unit tidak lengkap</span>
                            @endif
                          @else
                            @if ( $value->status == 0 )
                              @if($value->unit_hadap_id != "" && $value->purpose_id != "")
                                <input type="checkbox" name="unit_[{{$value->id}}]" id="unit_{{$value->id}}" onClick="addunitdelete('{{$value->id}}')" value="{{$value->id}}">
                              @else
                                <span style="color:red">Data Unit tidak lengkap</span>
                              @endif
                            @endif
                          @endif
                        @endif
                      </td>
            
                      <td>
                        @if ( $value->status == 0 )
                          Draft P&D
                        @elseif ( $value->status == 1 )
                          Planning
                        @elseif ( $value->status == 3)
                          Stok
                        @elseif ( $value->status == 5 )
                          Sold
                        @endif
                      </td>
                      <td>        
                            @if ( $value->status < 3 )     
                              <input type="checkbox" name="delete_unit_{{$value->id}}" id="delete_unit_{{$value->id}}" onClick="addunitdelete('{{$value->id}}')"><span class="label label-danger">Delete </span> 
                            @endif        
                            <a class="btn btn-warning" href="{{ url('/')}}/project/edit-unit?id={{ $value->id }}">Detail</a>                        
                        </td>
                        
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </form>
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
  @include("master/copyright")

  
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
  <div class="modal fade" id="modal-default">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Buat Unit</h4>
          </div>
          <div class="modal-body">
            <p>One fine body&hellip;</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Save changes</button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>

    <div class="modal fade" id="modalForm" role="dialog" >
      <div class="modal-dialog" style="width:70%">
          <div class="modal-content" >
              <!-- Modal Header -->
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">
                      <span aria-hidden="true">&times;</span>
                      <span class="sr-only">Close</span>
                  </button>
                  <h4 class="modal-title" id="myModalLabel">Tambah Unit Baru</h4>
              </div>
              
              <!-- Modal Body -->
              <form action="{{ url('/')}}/project/save-masal-unit" method="post" name="form1">
              {{ csrf_field() }}              
              <input type="hidden" name="blok" value="{{ $blok->id }}" id="blok_id">
              <div class="modal-body col-md-12" >
                  <p class="statusMsg"></p>
                  <!-- <form action="{{ url('/')}}/project/update-unit" method="post" name="form1"> -->
                    <div class="col-md-12">
                      <div class="col-md-6">   
                        
                        <div class="form-group" style="">  
                          <div class="form-group col-md-6" style="padding-left:0">
                            <label>nomor awal unit</label>
                            <input type="number" class="form-control" name="nomor_awal" id="nomor_awal" placeholder="nomor awal" style="width:100%">
                          </div>   

                          <div class="form-group col-md-6" style="">
                            <label>nomor akhir unit</label>
                            <input type="number" class="form-control" name="nomor_akhir" id="nomor_akhir" placeholder="nomor akhir" style="width:100%">
                          </div> 
                        </div>  

                        <div class="form-group" style="">
                          <label class="col-md-12" style="padding-left:0">Nomor</label>
                              <label><input type="radio" name="ganjilgenap" value="1" class="ganjilgenap">Ganjil</label>
                              &ensp; &ensp;
                              <label><input type="radio" name="ganjilgenap" value="0" class="ganjilgenap">Genap</label>
                              &ensp; &ensp; &ensp; &ensp;
                              <label id="warning" hidden><li style="color:red;font-size:20px">No. Unit Sudah Ada <br/> Harap di Cek</li></label>

                        </div>      
                        <div class="form-group">
                          <label>PT</label>
                          <select class="form-control" name="pt_id" id="pt_id" >
                            @foreach ( $project->pt_user as $key6 => $value6)
                              @if($user->id == $value6->user_id)
                                @if ( $value6->id == $user->pt_id )
                                  <option value="{{ $value6->pt->id}}" selected>{{ $value6->pt->name }}</option>
                                @else
                                  <option value="{{ $value6->pt->id}}">{{ $value6->pt->name }}</option>
                                @endif
                              @endif
                            @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                        <label>Unit Type <a href="{{ url('/')}}/project/add-type?id={{$project->id}}" class="btn btn-warning btn-sm">Tambah Type</a></label>
                          <select class="form-control" name="unit_type" id="unit_type" >
                            <option value="">Pilih Type</option>
                            @foreach ( $blok->kawasan->unit_type as $key5 => $value5 )
                                <option value="{{ $value5->id }}">{{ $value5->name }}</option>
                            @endforeach
                          </select>
                        </div>

                        <div class="form-group">
                          <label>Tipe</label>
                          <input type="text" class="form-control" name="kategori" id="kategori" value="">
                        </div>

                        <div class="form-group">
                          <label>Luas Tanah(m2)</label>
                          <input type="text" class="form-control" name="luas_tanah" id="luas_tanah" value="" >
                        </div>
                        
                        <div class="form-group">
                          <label>Luas Bangunan(m2)</label>
                          <input type="text" class="form-control" name="luas_bangunan" id="luas_bangunan" value="" >
                          <input type="text" id="luas_bangunan_kavling" value="0" style="display: none;">
                        </div>

                        <div class="form-group">
                          <label>Luas Tanah Tambahan(m2)</label>
                          <input type="text" class="form-control" name="luas_tanah_tambahan" id="luas_tanah_tambahan" value="0" >
                        </div>
                        <!-- /.form-group -->
                      </div>

                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Arah Bangunan </label>
                          <select class="form-control" name="unit_arah_id" id="unit_arah_id">
                            <option value='1' >Utara</option>
                            <option value='2' >Timur Laut</option>
                            <option value='3' >Timur</option>
                            <option value='4' >Tenggara</option>
                            <option value='5' >Selatan</option>
                            <option value='6' >Barat Daya</option>
                            <option value='7' >Barat</option>
                            <option value='8' >Barat Laut</option>
                          </select>
                        </div>

                        <div class="form-group">
                          <label>Hadap Bangunan <a href="{{ url('/')}}/project/unit-hadap" class="btn btn-warning btn-sm">Tambah Hadap</a></label>
                          <select class='form-control' name='unit_position' id='unit_position'>
                          @foreach ( $blok->kawasan->unitarahs as $key => $value )
                            <option value="{{ $value->id}}">{{$value->name}}</option>
                          @endforeach
                          </select>
                        </div>

                        <div class="form-group">
                          <label>Product Kategori</label>
                          <select class='form-control select2' name='tag_kategori' id='tag_kategori' >
                            <option value='B'>Bangunan</option>
                            <option value='K'>Kavling</option>
                          </select>
                        </div>

                        <div class="form-group">
                          <label>Status Sellable</label>
                          <select class='form-control' name='is_sellable' id='is_sellable'>
                            <option value='1' >Ya</option>
                            <option value='0' >Tidak</option>
                          </select>
                        </div>

                        <div class="form-group">
                          <label>Purpose</label>
                          <select class='form-control' name='purpose' id='purpose' placeholder="pilih purpose">
                          <option value="" selected>pilih Purpose</option>
                          @foreach ( $purpose as $key => $value )
                            <option value="{{ $value->id}}">{{$value->purpose}}</option>
                          @endforeach
                          </select>
                        </div>
                        
                        <div class="form-group">
                          <label>Keterangan</label>
                          <textarea class='form-control' name="description" id="description" cols="45" rows="5" placeholder="Descriptions" ></textarea>
                        </div>    
                      </div>
                    </div>         
              </div>
              <!-- Modal Footer -->
              <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary" id="tombol_tambah_unit">SUBMIT</button>
              </div>
              </form>

          </div>
      </div>
    </div>

    <div class="modal fade" id="modalForm1" role="dialog">
      <div class="modal-dialog">
          <div class="modal-content">
              <!-- Modal Header -->
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">
                      <span aria-hidden="true">&times;</span>
                      <span class="sr-only">Close</span>
                  </button>
                  <h4 class="modal-title" id="myModalLabel">Contact Form</h4>
              </div>
              
              <!-- Modal Body -->
              <div class="modal-body">
                <p class="statusMsg"></p>
                <form role="form">
                  <div class="form-group" style="">
                    <label>Banyak Unit</label>
                    <input type="text" class="form-control" name="total_unit" id="total_unit" placeholder="total unit yang akan dibangun" style="width:100%">
                  </div>
                
                  <div class="form-group" style="">
                    <label class="col-md-12" style="padding-left:0">Nomor</label>
                    <div class="radio">
                        <label><input type="radio" name="sumber" value="1" class="sumber_biaya">Ganjil</label>
                    </div>
                    <div class="radio">
                        <label><input type="radio" name="sumber" value="0" class="sumber_biaya">Genap</label>
                    </div>
                    <input type="text" name="" value="" id="kode_sumber_biaya" hidden="">
                  </div>

                  <div class="form-group">
                      <label>PT</label>
                      <select class="form-control" name="pt_id" id="pt_id" >
                      
                      </select>
                  </div>

                  <div class="form-group">
                      <label>Unit Type</label>
                      <select class="form-control" name="unit_type" id="unit_type" >
                      <option value="">Pilih Type</option>
                      
                      </select>
                  </div>

                  <div class="form-group">
                      <label>Tipe</label>
                      <input type="text" class="form-control" name="kategori" id="kategori" value="">
                  </div>

                  <div class="form-group">
                      <label>Luas Tanah(m2)</label>
                      <input type="text" class="form-control" name="luas_tanah" id="luas_tanah" value="" >
                  </div>
                  
                  <div class="form-group">
                      <label>Luas Bangunan(m2)</label>
                      <input type="text" class="form-control" name="luas_bangunan" id="luas_bangunan" value="" >
                      <input type="text" id="luas_bangunan_kavling" value="0" style="display: none;">
                  </div>

                  <div class="form-group">
                    <label>Arah Bangunan </label>
                    <select class="form-control" name="unit_arah_id" id="unit_arah_id">
                      
                    </select>
                  </div>

                  <div class="form-group">
                    <label>Hadap Bangunan</label>
                    <select class='form-control' name='unit_position' id='unit_position'>
                    </select>
                  </div>

                  <div class="form-group">
                    <label>Product Kategori</label>
                    <select class='form-control select2' name='tag_kategori' id='tag_kategori' >
                    
                    </select>
                  </div>

                  <div class="form-group">
                    <label>Status Sellable</label>
                    <select class='form-control' name='is_sellable' id='is_sellable'>
                      <option value='1' >Ya</option>
                      <option value='0' >Tidak</option>
                    </select>
                  </div>

                  <div class="form-group">
                    <label>Purpose</label>
                    <select class='form-control' name='purpose' id='purpose' placeholder="pilih purpose">
                    <option value="" selected>pilih Purpose</option>
                    
                    </select>
                  </div>
                  
                  <div class="form-group">
                    <label>Keterangan</label>
                    <textarea class='form-control' name="description" id="description" cols="45" rows="5" placeholder="Descriptions" ></textarea>
                  </div>    

                </form>
              </div>
              
              <!-- Modal Footer -->
              <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-primary submitBtn" onclick="submitContactForm()">SUBMIT</button>
              </div>
          </div>
      </div>
    </div>
    <!-- /.modal -->
</div>
<!-- ./wrapper -->

@include("master/footer_table")
@include("project::app")
<script type="text/javascript">
  // $('#example3').DataTable({
  //     'paging'      : false,
  //     'lengthChange': false,
  //     'searching'   : true,
  //     'ordering'    : false,
  //     'info'        : true,
  //     'autoWidth'   : false,
  //     fixedColumns:   {
  //         leftColumns: 4
  //     }
  // });

  function generateunit(){
    if ( confirm("Apakah anda yakin ingin membuat ini sebanyak " + $("#total_unit").val() + " unit ?")){
      $("#btn_generate").hide();
      $("#loading").show();
      var request = $.ajax({
        url : "{{url('/')}}/project/generateunit",
        dataType : "json",
        data : {
          blok : $("#blok_id").val(),
          total_unit : $("#total_unit").val()
        },
        type : "post"
      });

      request.done(function(data){
        $("#btn_generate").show();
        $("#loading").hide();

        if ( data.status == "0"){
          alert("Unit telah dibuat");
        }
        window.location.reload();
      })
    }else{
      return false;
    }
  }

  $("#unit_type").change(function(){
    var request = $.ajax({
        url : "{{ url('/')}}/project/getluas",
        data : {
          id : $("#unit_type").val()
        },
        type : "post",
        dataType : "json"
    });

    request.done(function(data){
      $("#kategori").val(data.kategori);
      $("#luas_tanah").val(data.luas_tanah);
      $("#luas_bangunan").val(data.luas_bangunan);
    });
  });

  $(".ganjilgenap").change(function(){
    var ganjilgenap = $(this).val();
    var nomor_awal = $("#nomor_awal").val();
    var nomor_akhir = $("#nomor_akhir").val();
    var blok_id = $("#blok_id").val();

    // console.log($(this).val());
    // console.log(nomor_awal);
    // console.log(nomor_akhir);
    // console.log(blok_id);

    var request = $.ajax({
        url : "{{ url('/')}}/project/cekunit",
        data : {
          ganjilgenap : ganjilgenap,
          nomor_awal : nomor_awal,
          nomor_akhir : nomor_akhir,
          blok_id : blok_id,
        },
        type : "post",
        dataType : "json"
    });

    request.done(function(data){
      if(data.data == 0){
        $("#warning").show();
        $("#tombol_tambah_unit").attr("disabled", true);
      }else{
        $("#warning").hide();
        $("#tombol_tambah_unit").attr("disabled", false);
      }
    });

  });

  $(document).ready(function() {
    $('#index tfoot th').each(function (){
      var title = $(this).text();
      var n = (title.length)*8;
      $(this).html('<input type="text" placeholder="'+title+'" / style="width:'+n+'px;">' );
    });

    var table = $('#index').DataTable( {
        // "pageLength" : 10,
        // "order": [[ 0, 'desc' ]],
        // "scrollX": true
        // scrollY: "500px",
        // sorting: true,
        scrollCollapse: true,
    } );
    
    var table = $('#index').DataTable();

    table.columns().every(function (){
      var that = this;
      $('input', this.footer()).on('keyup change', function(){
        if(that.search() !== this.value){
          that
            .search(this.value)
            .draw();
        }
      });
    });
    
  } );

</script>
</body>
</html>
