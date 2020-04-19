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
      <h1>Data Kawasan</h1>

    </section>

    <!-- Main content -->
    <section class="content">

      <!-- SELECT2 EXAMPLE -->
      <div class="box box-default">

        <!-- /.box-header -->
        <div class="box-body">
          <div class="row">
            <div class="col-md-6">              
              <h3 class="box-title">Tambah Data Kawasan</h3>
              <form action="{{ url('/')}}/project/update-kawasan" method="post" name="form1">
                {{ csrf_field() }}
              <input type="hidden" name="project_id" name="project_id" value="{{ $project->id }}">
              <input type="hidden" name="sub_holding" name="sub_holding" value="{{ $project->subholding }}">
               <input type="hidden" name="project_kawasan" name="project_kawasan" value="{{ $project_kawasan->id }}">
              <div class="form-group">
                <label>Project</label>
                <input type="text" class="form-control" value="{{ $project->name }}" readonly>
              </div>
              <div class="form-group">
                <label>Luas Area Bruto</label>
                <input type="text" class="form-control" name="project_luas"  value="{{ number_format($project->luas) }}" readonly>
              </div>
              <div class="form-group">
                <label>Kode Kawasan</label>
                @php
                    $i = 0;
                @endphp
                @foreach ($project_kawasan->bloks as $key => $value)
                    @foreach ($value->units as $key2 => $value2)
                        @if ($value2->status == 3 || $value2->status == 5)
                            @php
                                $i = 1;
                                break;
                            @endphp
                        @endif
                    @endforeach
                @endforeach
                @if ($i == 0)
                  <input type="text" class="form-control" name="kode_kawasan" id="kode_kawasan" value="{{ $project_kawasan->code }}">
                @else
                  <input type="text" class="form-control" name="kode_kawasan" id="kode_kawasan" value="{{ $project_kawasan->code }}" readonly>
                @endif
              </div>
              <div class="form-group">
                <label>Nama Kawasan</label>
                @if ($i == 0)
                <input type="text" class="form-control" name="nama_kawasan" id="nama_kawasan" value="{{ $project_kawasan->name }}">
                @else
                <input type="text" class="form-control" name="nama_kawasan" id="nama_kawasan" value="{{ $project_kawasan->name }}" readonly>
                @endif
              </div>
              <div class="form-group">
                <label>Luas Brutto (m2)</label>
                <input type="text" class="form-control" name="luas_brutto" id="luas_brutto" value="{{ $project_kawasan->lahan_luas }}">
              </div>
              <!-- <div class="form-group">
                <label>Luas Netto (m2)</label>
                <input type="text" class="form-control" name="luas_netto" id="luas_netto" value="{{ $project_kawasan->lahan_sellable }}">
              </div> -->
              <div class="form-group">
                <label>Status Lahan</label>
                <select class='form-control' name='lahan_status' id='lahan_status'>
                  <option value='0'>Open</option>
                  <option value='1'>Deliver</option>
                  <option value='2'>In-Progress</option>
                  <option value='3'>On-Hold</option>
                  <option value='4'>Release</option>
                  <option value='5'>Approved</option>
                  <option value='6'>Rejected</option>
                  <option value='7'>Close</option>
                  <option value='8'>Cancel</option>
                  <option value='9'>Active</option>
                  <option value='10'>Inactive</option>
                </select>
              </div>
              <div class="form-group">
                <label>Tipe Kawasan</label>
                <select class='form-control select2' name='project_type_id' id='project_type_id'>
                  @php
                    $array = array("1" => "Ruko", "2" => "Perumahan", "3" => "Gudang");
                  @endphp

                  @for($i=1; $i < 4 ; $i++ )
                    @if ( $i == $project_kawasan->project_type_id )
                    <option value="{{ $i }}" selected>{{ $array[$i]}}</option>
                    @else
                    <option value="{{ $i }}">{{ $array[$i]}}</option>
                    @endif
                  @endfor
                  
                </select>
              </div>
              <div class="form-group">
                <label>Keterangan</label>
                <textarea class='form-control' name="description" id="description" cols="45" rows="5" placeholder="Descriptions">{{ $project_kawasan->description }}</textarea>
              </div>  
              <div class="form-group">
                <label>Kawasan Sellable</label>
                <select name="is_kawasan" id="is_kawasan" class="form-control">

                  <option value="1" {{ $selected_1 }}>Ya</option>
                  <option value="0" {{ $selected_0 }}>Tidak</option>
                </select>
              </div>    
              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ url('/')}}/project/kawasan" class="btn btn-warning">Kembali</a>
              </div>
              </form>
              <!-- /.form-group -->
            </div>
            <!-- /.col -->
            <div class="col-md-12">
            </div>
            <!-- /.col -->
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
</div>
<!-- ./wrapper -->

@include("master/footer_table")
<!-- Select2 -->
<script src="{{ url('/')}}/assets/bower_components/select2/dist/js/select2.full.min.js"></script>
<script src="{{ url('/')}}/assets/plugins/input-mask/jquery.inputmask.js"></script>
<script src="{{ url('/')}}/assets/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="{{ url('/')}}/assets/plugins/input-mask/jquery.inputmask.extensions.js"></script>
<script type="text/javascript">
  $(function () {
    $("#luas").number(true);
    $("#luas_brutto").number(true);
    $("#luas_netto").number(true);
  });
</script>
@include("pt::app")
</body>
</html>
