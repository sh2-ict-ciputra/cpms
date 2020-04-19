<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Admin QS | Dashboard </title>
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
      <h1>Data Proyek</h1>

    </section>

    <!-- Main content -->
    <section class="content">
      <input type="hidden" name="workorder" id="workorder" value="{{ $rab->workorder->id }}">
      <!-- SELECT2 EXAMPLE -->
      <div class="box box-default">

        <!-- /.box-header -->
        <div class="box-body">
          <div class="row">
            <div class="col-md-6">   

              <h3 class="box-title">Edit Data Rab</h3>           
                {{ csrf_field() }}
              <input type="hidden" name="rab_id" value="{{ $rab->id }}">
              <div class="form-group">
                <label>No. Workorder</label>
                <input type="text" class="form-control" name="workorder_name" value="{{ $rab->workorder->no }}" readonly>
                @if ( $rab->workorder->approval != "" )<small>Approve at : <strong>{{ date("d/M/Y", strtotime($rab->workorder->approval->updated_at)) }} @endif</strong></small>
              </div> 
              <div class="form-group">
                <label>No. RAB</label>
                <input type="text" class="form-control" name="workorder_name" value="{{ $rab->no }}" readonly>
                @if ( $rab->approval != "" ) 
                Approved at : <strong>{{ date("d/M/Y", strtotime($rab->approval->updated_at))}}</strong>
                @endif
              </div> 
              <div class="form-group">
                <label>Nama</label>
                <input type="text" class="form-control" name="workorder_name" value="{{ $rab->name }}" readonly>
              </div>   
               <div class="form-group">
                <label>Nama Pekerjaan</label>
                <input type="text" class="form-control" name="workorder_name" value="{{ $rab->workorder_budget_detail->itempekerjaan->name}}" readonly>
              </div>           
              <!-- /.form-group -->
              <div class="form-group">
                <a class="btn btn-warning" href="{{ url('/')}}/rab/?workorder_id={{ $rab->workorder->id }}">Kembali</a>
                @if ( $rab->approval != "")
                    @if ( $rab->approval->approval_action_id == 7 )
                      <button onclick="apprioval('{{ $rab->id}}')" class="btn btn-primary">Simpan</button>
                    @elseif( $rab->approval->approval_action_id == 6 )    
                      <span class="label label-success">Rilis</span>                
                      <a class="btn btn-info" href="{{ url('/')}}/rab/tender?id={{$rab->id}}">Tender</a>  
                    @elseif ( $rab->approval->approval_action_id == 3)
                      <span class="label label-danger">Harus di revisi</span>
                      <button onclick="updateapprioval('{{ $rab->id}}')" class="btn btn-primary">Request Approval</button>
                      <a class="btn btn-info" href="{{ url('/')}}/rab/tender?id={{$rab->id}}">Tender</a>
                    @elseif ( $rab->approval->approval_action_id == 1)
                      <span class="label label-warning">Dalam Pengecekan</span>
                      <a class="btn btn-info" href="{{ url('/')}}/rab/tender?id={{$rab->id}}">Tender</a>
                    @endif

                  <a class="btn btn-primary" href="{{ url('/')}}/rab/approval_history?id={{ $rab->id }}">Approval History</a>
                @else
                  <button onclick="apprioval('{{ $rab->id}}')" class="btn btn-primary">Simpan</button>
                @endif
              </div>
              @if($rab->approval != '')
                @if(count($rab->approval->histories->where("approval_action_id",3)) != 0)
                <strong> Alasan : {{$rab->approval->histories->where("approval_action_id",3)->first()->description}} </strong>
                @endif
              @endif
            </div>
            <!-- /.col -->

            <!-- /.col -->

          </div>
          <div class="nav-tabs-custom">

              <h3><strong>Nilai Workorder Rp {{ number_format($rab->workorder->nilai)}}</strong></h3>
              <h3><strong>Nilai RAB Rp {{ number_format($rab->nilai) }} </strong></h3>
              <ul class="nav nav-tabs">                
                <li class="active"><a href="#tab_2" data-toggle="tab">1. Unit</a></li>
                <li><a href="#tab_3" data-toggle="tab">2. Item Pekerjaan</a></li>
              </ul>
              <div class="tab-content">                
                <!-- /.tab-pane -->
                <div class="tab-pane " id="tab_3">

                  @if ( $rab->approval == "" )
                    @if ( count($rab->units) > 0 && count($rab->pekerjaans) <= 0 )
                    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-info">
                      Tambah Item Pekerjaan
                    </button><br><br>
                    @elseif ( count($rab->pekerjaans) > 0 )
                    <button type="button" class="btn btn-danger" onclick="deletepekerjaans('{{ $rab->id}}')">Hapus Pekerjaan</button>
                    @endif
                  @else
                    @php
                      $array = array (
                        "6" => array("label" => "Disetujui", "class" => "label label-success"),
                        "7" => array("label" => "Ditolak", "class" => "label label-danger"),
                        "1" => array("label" => "Dalam Proses", "class" => "label label-warning"),
                        "3" => array("label" => "Harus di Revisi", "class" => "label label-warning")
                      )
                    @endphp
                    <!-- <span class="{{ $array[$rab->approval->approval_action_id]['class'] }}">  
                          {{ $array[$rab->approval->approval_action_id]['label'] }}
                    </span> -->
                    @if ( $rab->approval->approval_action_id == "3")
                      @if ( $rab->pekerjaans->count() > 0 )
                      <button type="button" class="btn btn-danger" onclick="deletepekerjaans('{{ $rab->id}}')">Hapus Pekerjaan</button>
                      @endif
                    @else
                    
                    @endif
                  @endif<br><br>

                  @if ( count($rab->units) > 0 )
                  <table class="table table-bordered">
                   <thead class="head_table">
                     <tr>
                      <td>COA Pekerjaan</td>
                      <td>Item Pekerjaan</td>
                      <td>Volume</td>
                      <td>Sat</td>
                      <td>Hrg Sat (Rp/..)</td>
                      <td>Subtotal</td>
                      <td>Bobot(%)</td>
                      <td>Perubahan Data</td>
                     </tr>
                   </thead>                     
                   <tbody>   
                    <tr>
                      <td>{{ $rab->pekerjaans->first()->itempekerjaan->parent->code or '' }}</td>
                      <td colspan="4">{{ $rab->pekerjaans->first()->itempekerjaan->parent->name or ''}}</td>
                      <td></td>
                      <td style="text-align:right">
                        @if ( $rab->units->count() > 0 )
                          @php $total=0; @endphp
                          @foreach($rab->pekerjaans as $key => $value ) 
                          @php
                            if($rab->nilai != 0){
                              $total = $total + ( (($value->nilai * $value->volume) / ( $rab->nilai / $rab->units->count()) ) * 100 ); 
                            }
                          @endphp
                          @endforeach

                          {{ $total }}    
                        @endif
                      </td>
                    </tr>

                    @if ( $rab->units->count() > 0 )
                      @foreach($rab->pekerjaans as $key => $value )        
                        @if($value->volume != 0)               
                          <tr>
                            <td><strong>{{ $value->itempekerjaan->code }}</strong></td>
                            <td><strong>{{ $value->itempekerjaan->name }}</strong></td>
                            <td style="text-align:right">
                              <span class="labels" id="label_rab_volume_{{ $value->id }}">{{ number_format($value->volume,2) }}</span>
                              <input class="values" type="text" id="input_rab_volume_{{ $value->id}}" value="{{ $value->volume }}" style="display: none;">
                            </td>
                            <td>
                              <span class="labels" id="label_rab_satuan_{{ $value->id }}">{{ $value->satuan }}</span>
                              <input class="values" type="text" id="input_rab_satuan_{{ $value->id}}" value="{{ $value->satuan }}" style="display: none;">
                            <td style="text-align:right">
                              <span class="labels" id="label_rab_nilai_{{ $value->id }}">{{ number_format($value->nilai,2) }}</span>
                              <input class="values" type="text" id="input_rab_nilai_{{ $value->id}}" value="{{ $value->nilai }}" style="display: none;">
                            </td>
                            </td>
                            <td style="text-align:right">
                              <span class="labels" id="label_rab_nilai_{{ $value->id }}">{{ number_format($value->nilai * $value->volume,2) }}</span>
                              <input class="values" type="text" id="input_rab_nilai_{{ $value->id}}" value="{{ $value->nilai * $value->volume}}" style="display: none;">
                            </td>
                            <td style="text-align:right">
                              <span class="labels" id="label_rab_nilai_{{ $value->id }}"> {{ number_format((($value->nilai * $value->volume) / ( $rab->nilai / $rab->units->count() ) * 100),2) }}</span>
                              <input class="values" type="text" id="input_rab_nilai_{{ $value->id}}" value="{{ $value->nilai * $value->volume}}" style="display: none;">
                            </td>
                            <td>
                              @if ( $rab->approval == "" )
                              <button class="btn-edit1 btn btn-warning" onclick="viewdite('{{ $value->id }}')" id="btn_edit_{{ $value->id}}">Edit</button>
                              <button class="btn-edit2 btn btn-success" onclick="saveedit('{{ $value->id }}')" style="display: none;" id="btn_edit2_{{ $value->id }}">Save</button>
                              @else
                                @if ( $rab->approval->approval_action_id == "3")
                                <button class="btn-edit1 btn btn-warning" onclick="viewdite('{{ $value->id }}')" id="btn_edit_{{ $value->id}}">Edit</button>
                                <button class="btn-edit2 btn btn-success" onclick="saveedit('{{ $value->id }}')" style="display: none;" id="btn_edit2_{{ $value->id }}">Save</button>
                                @endif
                              @endif
                            </td>
                          </tr>
                        @endif
                      @endforeach
                    @endif
                  </tbody>
                  </table>
                  @else
                    <h3>Pilih Unit Terlebih Dahulu</h3>
                  @endif
                </div>
                <!-- /.tab-pane -->
                <div class="tab-pane active" id="tab_2">
                  @if ( $rab->approval == "" )
                  <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-default">
                    Tambah Unit
                  </button><br><br>
                  @else
                    @if ( $rab->approval->approval_action_id == "3")
                    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-default">
                    Tambah Unit
                    </button><br><br>
                    @endif
                  @endif
                <table class="table">
                     <thead class="head_table">
                       <tr>
                        <td>Unit</td>
                        <td>Delete</td>
                       </tr>
                     </thead>
                     <tbody>
                        @foreach ( $rab->units as $key => $value )
                        <tr>
                          <td>{{ $value->asset->name or $rab->project->name }}</td>
                          <td>
                            @if ( $rab->approval == "" )
                            <button type="button" class="btn btn-danger" onclick="removeunit('{{ $value->id }}')">Delete</button>
                            @else
                              @if ( $rab->approval->approval_action_id == "3")
                                <button type="button" class="btn btn-danger" onclick="removeunit('{{ $value->id }}')">Delete</button>
                              @endif
                            @endif
                          </td>
                        </tr>
                        @endforeach
                     </tbody>
                   </table>
                </div>
              </div>
              <!-- /.tab-content -->
            </div>
          <!-- /.row -->
        </div>
        <!-- /.box-body -->

      </div>
      <!-- /.box -->


    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  @include("master/copyright")

  
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
  <div class="modal fade" id="modal-info">
    <form action="{{ url('/')}}/rab/save-pekerjaan" method="post">
      {{ csrf_field() }}
      <input type="hidden" id="idpkr" value="{{ $itempkr->id }}" name="idpkr">
      <input type="hidden" name="rab" id="rab" value="{{ $rab->id }}">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Pekerjaan</h4>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label>Pilih Item Pekerjaan</label>
              <select class="form-control" id="item_coa">
                <!-- <option value="">( pilih item pekerjaan )</option> -->
                
                <option value="{{ $itempkr->id}}">{{ $itempkr->code }} - {{ $itempkr->name }}</option>
                
              </select>
            </div>
            <div class="form-group" style="display: none;">
              <label></label>
              <select class="form-control" id="item_child_coa">
                
              </select>
            </div>
            <h4>Budget Terpakai : <strong><span id="budget_total"></span></strong></h4>
            <h4>Budget Tersisa : <strong><span id="budget_tersisa"></span></strong></h4>
            <input type="hidden" id="budget_total_val" value="" name="">
            <input type="hidden" id="budget_tahunan_id" name="budget_tahunan_id" >
            <input type="hidden" id="budget_tersisa_val" name="">
            <table class="table">
              <thead class="head_table">
                <tr>
                  <td>COA Pekerjaam</td>
                  <td>Item Pekerjaan</td>
                  <td>Volume</td>
                  <td>Satuan</td>
                  <td>Nilai Satuan</td>
                  <td>Tambah</td>
                </tr>
              </thead>
              <tbody id="itempekerjaan">
                
              </tbody>
            </table>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save changes</button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </form>

  </div>
  <!-- /.modal -->

  <div class="modal fade" id="modal-default">
    <form action="{{ url('/')}}/rab/save-units" method="post">
    <input type="hidden" id="idpkr" value="{{ $itempkr->id }}" name="idpkr">
    <input type="hidden" value="{{ $rab->id }}" name="rab_unit_id">
    {{ csrf_field() }}
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title"></h4>
        </div>

        <div class="modal-body">

          <table class="table">
            <thead class="head_table">
              <tr>
                <td>Unit</td>
                <td>keterangan</td>
                <td><!--input type="checkbox" value="" id="unit_rab_all" onclick="checkall();"--></td>
              </tr>
            </thead>
            <tbody>
              @php $start=0; $arrayType= array();@endphp
              @foreach ( $rab->workorder->details as $key4 => $value4 )
              @if ( $value4->asset != "" )
                @if ( $value4->asset->type != "" )
                  @php $arrayType[$value4->asset->type->id] = array("id" => $value4->asset->type->id, "name" => $value4->asset->type->name ); $start++;@endphp                
                  <tr class="type type_{{ $value4->asset->type->id }}" style="display: none;">
                    <td>{{ $value4->asset->name }}</td>
                    <td>{{ $value4->asset->type->name or ''}}</td>
                    <td>
                      <input type="checkbox" name="unit_rab_[{{$value4->asset_id}}]" value="{{ $value4->asset_id }}">Pilih ke RAB
                      <input type="hidden" value="{{ $value4->asset_type }}" name="unit_rab_type_[{{$value4->asset_id}}]">
                    </td>
                  </tr>
                  @else
                  <tr class="">
                      <td>{{ $value4->asset->name }}</td>
                      <td>{{ $value4->asset->type->name or ''}}</td>
                      <td>
                        <input type="checkbox" name="unit_rab_[{{$value4->asset_id}}]" value="{{ $value4->asset_id }}">Pilih ke RAB
                        <input type="hidden" value="{{ $value4->asset_type }}" name="unit_rab_type_[{{$value4->asset_id}}]">
                      </td>
                    </tr>
                  @endif
                @else
                    <tr class="">
                      <td>{{ $value4->project->name }}</td>
                      <td>{{ $value4->asset->type->name or ''}}</td>
                      <td>
                        <input type="checkbox" name="unit_rab_[{{$value4->asset_id}}]" value="{{ $value4->asset_id }}">Pilih ke RAB
                        <input type="hidden" value="{{ $value4->asset_type }}" name="unit_rab_type_[{{$value4->asset_id}}]">
                      </td>
                    </tr>
                  @endif

              @endforeach

              @foreach ( $arrayType as $key => $value)
              <tr>
                <td>Type : </td>
                <td><input type="radio" name="type" onClick="showUnitType({{ $value['id']}})">{{ $value['name']}}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
          <li style="color:red;">centang unit/kawasan yang ingin di pilih</li>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    </form>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->
</div>
<!-- ./wrapper -->

@include("master/footer_table")
<script type="text/javascript">
  $(document).ready(function(){
    $.ajaxSetup({
      headers: {
          'X-CSRF-Token': $('input[name=_token]').val()
      }
    });

    var idpkr = $('#idpkr').val();
    var wo = $('#workorder').val();

    detailitem(idpkr,wo);
  });

  function detailitem(idpkr,workorder){
  $("#budget_total").html("0");
  $("#budget_total_val").val();
  var request = $.ajax({
    url : "{{ url('/')}}/rab/childcoa",
    dataType : "json",
    data : {
      id : idpkr,
      workorder : workorder
    },
    type : "post"
  });

  request.done(function(data){
    $("#itempekerjaan").html(data.html);
    //$("#budget_total").html(data.budget);
    $("#budget_total").number(true);
    $("#budget_total_val").val(data.budget);
    $("#budget_tahunan_id").val(data.budget_tahunan_id);
    $("#budget_tersisa_val").val(data.budget_tersisa);
    $("#budget_tersisa").text(data.budget_tersisa);
    $("#budget_tersisa").number(true);
    $(".nilai_budget").number(true);
    // $(".volume").number(true);
  });
}
</script>
@include("rab::app")
<!-- Select2 -->

</body>
</html>
