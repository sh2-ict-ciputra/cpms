<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
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
      <h1>Data SUPP</h1>

    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
   
            <!-- /.box-header -->
            <div class="box-body">
              <!-- <form action="{{ url('/')}}/spk/supp/store" method="post" name="form1"> -->
              <input type="hidden" name="spk_id" value="{{ $spk->id}}">
              <div class="col-md-6">
                <h3 class="header">Data Rekanan</h3><hr>
                  {{ csrf_field() }}                  
                  <div class="form-group">
                    <label for="exampleInputEmail1">Nama Rekanan</label>
                    <input type="hidden" name="rekanan_id" value="{{ $spk->rekanan->group->id }}">
                    <h4>{{ $spk->rekanan->group->name or ''}}</h4>
                  </div>
                  <div class="form-group">
                      <label for="exampleInputEmail1">Nama Wakil Rekanan</label>
                      <h4>{{ $spk->rekanan->group->supps->last()->saksi_rekanan_name or  ''}}</h4>
                  </div>
                  <div class="box-footer">
                    @if ( $spk->rekanan->group->supps->count() > 0 )
                   <!--  <a href="{{url('/')}}/spk/supp/download/?id={{$spk->rekanan->group->supps->last()->id}}" class="btn btn-success"> -->
                    <!-- <a href="http://117.53.46.38/home/supp/download/?supp_no={{ $spk->rekanan->group->supps->last()->no}}&rekanan_name={{$spk->rekanan->group->name}}&cp_name={{$spk->rekanan->group->cp_name}}&user_name={{$spk->rekanan->group->supps->first()->user_penandatangan->user_name}}&jabatan=" class="btn btn-success">Download SUPP No. {{ $spk->rekanan->group->supps->last()->no or  ''}}</a> -->
                    <input type="hidden" id="supp" name="supp_no" value="{{ $spk->rekanan->group->supps->last()->no}}">
                    <button type="submit" class="btn btn-success cetak_supp">Cetak SUPP No. {{ $spk->rekanan->group->supps->last()->no or  ''}}</button>
                    @endif
                    <a href="{{ url('/')}}/spk/detail/?id={{$spk->id}}" class="btn btn-warning">Kembali</a>
                  </div>
                
              </div>
              <div class="col-md-6">
                <h3 class="header">Data Proyek</h3><hr>                               
                  <div class="form-group">
                    <label for="exampleInputEmail1">Nama PT Proyek</label>
                    <h4>{{ $spk->tender->rab->pt->name or  ''}}</h4>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1">Nama Wakil PT Proyek</label>  
                    <h4>{{ $spk->rekanan->group->supps->last()->user_penandatangan->user_name or  ''}}</h4>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1">Nama Saksi Wakil PT Proyek</label>  
                    <h4>{{ $spk->rekanan->group->supps->last()->user_saksi->user_name or  ''}}</h4>                
                  </div>
              </div>
              <!-- </form> -->

            </div>

          </div>
          <!-- /.box-body -->
        </div>
      <!-- /.box -->
      </div>
      <!-- /.col -->
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
@include("spk::supp.app")
<script>
  $(document).on('click', '.cetak_supp', function() {
  var _url = "{{ url('/supp/cetakSupp') }}";
  var supp = $("#supp").val();
  window.open("/spk/supp/cetakSupp?supp_no="+supp,'_blank');
});
</script>
</body>
</html>
