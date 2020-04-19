<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Admin QS | Dashboard</title>
  @include("master/header")
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <!-- Bootstrap time Picker -->
  <link rel="stylesheet" href="{{ url('/')}}/assets/plugins/timepicker/bootstrap-timepicker.min.css">

</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  @include("master/sidebar_project")

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>Data Tender</h1>

    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
   
            <!-- /.box-header -->
            <div class="box-body">
              <form action="{{ url('/')}}/tender/aanwijing/save" method="post" name="form1" id="form1" enctype="multipart/form-data">
                <input type="hidden" name="tender_id"  value="{{$tender->id}}">
                {{ csrf_field() }}
                <h3 class="header">Aanwijing</h3>  
                <center><span style="font-size: 20px;"><strong>Berita Acara Penjelasan Tender</strong></span></center><br/> 
                <center><span style="font-size: 20px;"><strong>{{ $tender->name }}</strong></span></center><br/>
                <center><span style="font-size: 20px;"><strong>@if ( $tender->project != "" ) {{ $tender->project->name }} @endif</strong></span></center><br/>
                <div class="col-md-12">       	   
                  {{ csrf_field() }}                  
                  
                  <div class="form-group col-md-7 col-md-7">
                    <label>Tempat</label>
                    <input type="text" class="form-control" name="tempat" required>
                  </div>       	
                  <div class="form-group col-md-7">
                    <label>Masa Pelaksanaan (hari kalender)</label>
                    <input type="text" class="form-control" name="masa_pelaksaan" autocomplete="off" value="{{ $tender->durasi}}" required>
                  </div>
                  <!-- <div class="form-group col-md-7">
                    <label>Masa Pemeliharaan</label>
                    <input type="text" class="form-control" name="masa_pemeliharaan" autocomplete="off" required>
                  </div> -->
                  <div class="form-group col-md-7">
                    <label>Jaminan Penawaran(Rp)</label>
                    <input type="text" class="nilai_budget form-control" name="jaminan_penawaran" autocomplete="off" required>
                  </div>
                  <div class="form-group col-md-7">
                    <label>Jaminan Pelaksanaan(Rp)</label>
                    <input type="text" class="nilai_budget form-control" name="jaminan_pelaksanaan" autocomplete="off" required>
                  </div>
                  <div class="form-group col-md-7">
                    <label>Denda Keterlambatan per mil/hari</label>
                    <input type="text" class="form-control" name="denda" autocomplete="off" required>
                  </div>
                  <div class="form-group col-md-5">
                    <label>Cara Pengembalian DP</label>
                    <select class='form-control' name='jenis_pembayaran' id='jenis_pembayaran' class="form-control">
                      @foreach ($jenis_pembayaran as $key => $value)
                        <option value={{$value->id}}>{{$value->name}}</option>
                      @endforeach
                    </select>
                  </div>                
                  <div class="form-group col-md-7">
                    <label>Tahap Termin Pembayaran termasuk Retensi </label>
                    <input type="hidden" id="limit_count" value="0">
                    <input type="text" class="form-control nilai_budget" name="sistem_pembayaran" id="sistem_pembayaran" autocomplete="off" required> <br/>
                    <button class="btn btn-info" type="button" onClick="generateTermyn()">Buat Termin</button>

                    Percent Bayar <label style="font-color:red">( harus 100%) </label>: <strong> <span id="label_count"></span>%</strong>
                    <table class="table table-bordered">
                      <thead class="head_table">
                        <tr>
                          <td>No.</td>
                          <td>Termin</td>
                          <td>Persentase Bayar</td>
                          <!-- <td>Tanggal Bayar</td> -->
                        </tr>
                      </thead>
                      <tbody id="list_termyn">
                        
                      </tbody>
                    </table>
                  </div>
                  <div class="form-group col-md-5" id="div_jumlah_pengembalian" hidden>
                    <label>Jumlah Pengembalian</label>
                    <input type="number" class="form-control" name="jumlah_pengembalian" autocomplete="off" value="0" id="jumlah_pengembalian"><br/>

                    % Pengembalian <label style="font-color:red">( harus 100%) </label>: <strong> <span id="label_count_pengembalian"></span>% dari nilai DP</strong>
                    <table class="table table-bordered">
                      <thead class="head_table">
                        <tr>
                          <td>No.</td>
                          <td>Termin</td>
                          <td>Persentase Bayar</td>
                          <!-- <td>Tanggal Bayar</td> -->
                        </tr>
                      </thead>
                      <tbody id="list_pengembalian">
                        
                      </tbody>
                    </table>
                  </div>   
                  <div class="form-group col-md-7">
                    <label>Retensi dibayarkan ... kali</label>
                    <input type="hidden" name="limit_retensi" id="limit_retensi" value="0">
                    <input type="text" class="form-control" name="retensi" id="retensi" autocomplete="off" required><br/>
                    <button class="btn btn-info" type="button" onClick="generateRetensi();">Buat Retensi</button>
                    {{-- Percent Retensi : <strong> <span id="label_retensi"></span>% (maksimal retensi <span id="label_retensi_maksimal"></span>%)</strong> --}}
                    Percent Retensi : <strong> <span id="label_retensi"></span>% (Sudah Termasuk di dalam Termin)</strong>
                    <table class="table table-bordered">
                      <thead class="head_table">
                        <tr>
                          <td>No.</td>
                          <td>Retensi</td>
                          <td>Waktu ( hari kalender)</td>
                        </tr>
                      </thead>
                      <tbody id="list_retensi">
                        
                      </tbody>
                    </table>
                  </div>
                  <div class="form-group col-md-7">                    
                    <button class="btn btn-primary" type="submit" id="tombol_save" disabled>Simpan</button>
                    <a href="{{ url('/')}}/tender/detail?id={{$tender->id}}" class="btn btn-warning">Kembali</a><br/>
                  </div>
                </div>
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
</div>
<!-- ./wrapper -->

@include("master/footer_table")
<!-- Select2 -->
<script src="{{ url('/')}}/assets/bower_components/select2/dist/js/select2.full.min.js"></script>
<script src="{{ url('/')}}/assets/plugins/input-mask/jquery.inputmask.js"></script>
<script src="{{ url('/')}}/assets/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="{{ url('/')}}/assets/plugins/input-mask/jquery.inputmask.extensions.js"></script>
<script src="{{ url('/')}}/assets/plugins/timepicker/bootstrap-timepicker.min.js"></script>
@include("tender::app")
<script type="text/javascript">
  $(function () {
    $(".select2").select2();
    $('.timepicker').timepicker({
      format: 'HH:mm'
    })
  });
</script>
</body>
</html>
