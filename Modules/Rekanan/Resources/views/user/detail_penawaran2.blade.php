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

  @include("master/sidebar_rekanan")

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>Data Item Pekerjaan <strong>{{ $itempekerjaan->name }}</strong></h1>

    </section>

    <!-- Main content -->
    <section class="content">

      <!-- SELECT2 EXAMPLE -->
      <div class="box box-default">

        <!-- /.box-header -->
        <div class="box-body">
          <div class="row">
            <div class="col-md-12">
              @if(isset($tenderpenawaran))
                <span>Total Penawaran = Rp. {{ number_format($tenderpenawaran->nilai)}}</span>
              @endif
              <form action="{{ url('/')}}/rekanan/user/tender/penawaran-update2" method="post" name="form1" enctype="multipart/form-data">
              <a href="{{ url('/')}}/rekanan/user/tender/detail/?id={{ $tenderRekanan->id }}" class="btn btn-warning">Kembali</a>
              @if ( $exist == 1 )
              <button type="submit" class="btn btn-primary">Simpan</button>
              @endif
              {{ csrf_field() }}
              <input type="hidden" name="tender_id" value="{{ $tenderpenawaran->id }}"><br>
              
              <input type="hidden" name="tender_rekanan" value="{{ $tenderRekanan->id }}">
              <h3><center>Rekanan : <strong>{{ $tenderRekanan->rekanan->name }}</strong></center></h3>
              <hr>
              <table class="table table-bordered">
               <thead class="head_table">
                 <tr>
                  <td>COA Pekerjaan</td>
                  <td>Item Pekerjaan</td>
                  <td>Volume</td>
                  <td style="width:4%;">Satuan</td>
                  <td>Harga Satuan</td>
                  <td>Subtotal</td>
                 </tr>
                </thead>
                <tbody>
                  @php $start=0; @endphp
                  @foreach( $tenderRekanan->penawarans as $key => $value )
                    @if ( $key == 1)
                      @foreach ( $value->details as $key3 => $value3 )
                        @if ( $value3->volume > 0 )
                          @php
                            $tender_penawaran_detail = \Modules\Tender\Entities\TenderPenawaranDetail::where("tender_penawaran_id",$penawaran_id)->where("rab_pekerjaan_id",$value3->rab_pekerjaan_id)->get()
                          @endphp
                          <tr>
                            <td>{{ $value3->rab_pekerjaan->itempekerjaan->code }}</td>
                            <td>{{ $value3->rab_pekerjaan->itempekerjaan->name }}</td>
                            <td><input type="hidden" name="input_rab_id_[{{ $key3}}]" class="form-control" value="{{ $value3->id }}"><input  type="text" name="input_rab_volume_[{{ $key3}}]" id="input_rab_volume_{{ $key3}}" class="form-control" value="{{ $value3->volume }}" style="width: 100%;text-align: right;" readonly></td>
                            <td><input  type="text" name="input_rab_satuan_[{{ $key3}}]"  id="input_rab_satuan_{{ $key3}}" class="form-control" value="{{ $value3->rab_pekerjaan->satuan }}" style="width: 100%;text-align: right;" readonly></td>
                            <td><input type="text" name="input_rab_nilai_[{{ $key3}}]"  id="input_rab_nilai_{{ $key3}}" class="form-control vol" onKeyUp="showSummary('{{ $key3}}')" value="{{ $value3->nilai }}" style="text-align: right;" autocomplete="off"></td>
                            <td><input type="text"  id="subtotal_{{$key3}}" value="{{ number_format($tender_penawaran_detail->first()->nilai * $tender_penawaran_detail->first()->volume,2) }}" class="form-control"  style="text-align: right;" autocomplete="off" /></td>
                          </tr>
                          @php $start = $key; @endphp
                        @endif
                      @endforeach
                    @endif
                  @endforeach
                  
                </tbody>
              </table>

              
              <h6 style="color:black;"><i><strong>Harap upload dengan tipe .pdf,.doc,.docx,.xls,.xlsx</strong></i></h6>
              <input type="file" name="fileupload"><br>
              </form>
            </div>
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

  



</div>
<!-- ./wrapper -->

@include("master/footer_table")
<!-- Select2 -->
<script src="{{ url('/')}}/assets/bower_components/select2/dist/js/select2.full.min.js"></script>
<script src="{{ url('/')}}/assets/plugins/jquery.number.min.js"></script>
<script type="text/javascript">
  $(".vol").number(true);
</script>
@include("tender::app")
<script type="text/javascript">
  $(function(){
    $(".vol").number(true);
  });

  function showSummary(id){
    var vla = $("#input_rab_nilai_" + id).val();
    console.log($("#input_rab_nilai_" + id).val(),vla);
    var rep = vla.replace(",","");
    var summary = parseInt($("#input_rab_volume_" + id).val()) * parseInt(rep);
    if ( summary == "NaN"){
      $("#subtotal_" + id).val("0");
    }else{
      $("#subtotal_" + id).val(summary);
      $("#subtotal_" + id).number(true);
    }
  }
</script>
</body>
</html>
