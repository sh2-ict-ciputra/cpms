<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Admin QS | Dashboard </title>

  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
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
    <section class="content-header" style="display: none;">
      <h1>Data Voucher</h1>

    </section>

    <!-- Main content -->
    <section class="content">

      <!-- SELECT2 EXAMPLE -->
      <div class="box box-default">

        <!-- /.box-header -->
        <div class="box-body">
          <div class="row">
            <div class="col-md-12">   

            <h3 class="box-title" style="display: none;">Detail Data Voucher</h3>           
              <!-- Main content -->
            <section class="invoice">

              <form action="{{ url('/')}}/voucher/update" method="post" name="form1" >
                <input type="hidden" name="voucher_id" value="{{ $voucher->id }}" id="voucher_id">
                {{ csrf_field() }}
              <!-- title row -->
              <div class="row">
                <div class="col-xs-12">
                  <h2 class="page-header">
                    <i class="fa fa-globe"></i> Voucher NO : <strong>{{ $voucher->no or  ''}}</strong>
                    <small class="pull-right">Dok No  : {{ $voucher->bap->no or ''}}</small>
                  </h2>
                </div>
                <!-- /.col -->
              </div>

              <div class="row">
                <div class="col-xs-6">
                  <div class="form-group">
                    <span>Project</span>
                    <input type="text" class="form-control" value="{{ $voucher->bap->spk->project->name or '' }}" readonly>
                  </div>
                  <div class="form-group">
                    <span>PT</span>
                    <input type="text" class="form-control" value="{{ $voucher->bap->spk->tender->rab->pt->name or '' }}" readonly>
                  </div>
                  <div class="form-group">
                    <span>Dibayarkan kepada</span>
                    <input type="text" class="form-control" value="{{ $voucher->bap->spk->rekanan->group->name or '' }}" readonly>
                  </div>
                  <div class="form-group">
                    <span>Rekening Rekanan</span>
                    <select class="form-control" name="rekanan_rekening">
                      @foreach ( $voucher->bap->spk->rekanan->rekenings as $key3 => $value3 )
                      <option value="{{ $value3->id}}">{{ $value3->bank->name or '' }} / {{ $value3->name or '' }}-{{ $value3->no or '' }}</option>
                      @endforeach
                    </select>
                  </div>
                  @if ($voucher->bap->spk->tender->aanwijing->jenis_pembayaran == '2')
                    @if ($voucher->bap->termin == 1 && $voucher->bap->percentage_lapangan == 0)
                      <div class="form-group">
                        <button type="button" class="btn btn-warning btn-md bank_garansi" data-target="#editModalBG" style="" id="bank_garansi">Isi Bank Garansi</button>
                      </div>
                    @endif
                  @endif
                </div>
                <div class="col-xs-6">                  
                  <div class="form-group">
                    <span>Tanggal Voucher Dibuat</span>
                    <input type="text" class="form-control" value="{{ $voucher->created_at->format('d/m/Y')}}" id="tgl_voucher_dibuat" readonly>
                  </div>
                  <div class="form-group">
                    <span>Tanggal Voucher Diserahkan ke Keuangan</span>
                    <input type="text" class="form-control"  id="diserahkan" name="diserahkan" value="{{ date('Y-m-d')}}" readonly>
                  </div>
                  <div class="form-group">
                    <span>Tanggal Jatuh Tempo Voucher</span>
                    <input type="text" class="form-control" value="{{ $voucher->tempo_date}}" id="tempo" name="tempo" autocomplete="off" required>
                  </div>
                  <div class="form-group">
                    @if ( $voucher->bap->percentage_lapangan == 0)
                      <span>Tanggal Voucher Dicairkan / Giro diserahkan</span>
                      <input type="text" class="form-control" value="" id="pencairan" name="pencairan" autocomplete="off" disabled>
                      
                      <p>Belum memenuhi progress lapangan minimal <i><strong><span style="color:red;">{{ ceil(($voucher->bap->spk->termyn[0]->termin*1.05) + $counter)}}%</span></strong></i></p>

                    @else       
                    <span>Tanggal Voucher Dicairkan / Giro diserahkan</span>             
                    <div class="row">
                      <div class="col-3">
                        <!-- <input type="text" class="form-control" placeholder=".col-3" value="{{ date('m/d/Y')}}"> -->
                        @if($voucher->pencairan_date != null)
                          <input type="text" class="form-control" value="{{ date('d M Y',strtotime($voucher->pencairan_date))}}" id="pencairan" name="pencairan" autocomplete="off" readonly>
                        @else
                          <input type="text" class="form-control" value="" id="pencairan" name="pencairan" autocomplete="off" readonly>
                        @endif
                      </div>
                      <div class="col-4">
                        <input type="text" class="form-control" placeholder="voucher number" value="{{$voucher->voucher_number}}" readonly>
                      </div>
                    </div>
                    @endif
                  </div>
                </div>
              </div>
              <!-- /.row -->
              <div class="row">
                <div class="col-xs-12">
                  <table class="table table-bordered">
                    <thead class="head_table">
                      <tr>
                        <td>Kode</td>
                        <td>Keterangan</td>
                        <td>Nilai</td>
                      </tr>
                    </thead>
                    <tbody>
                      @if ( $voucher->head_type == "Bap")
                        @foreach( $voucher->details as $key => $value )
                          @if($value->nilai != 0)
                            <tr>
                              <td>
                                @if ( $value->type == "Nilai PPh")
                                <select class="form-control" name="coa_pph" style="width: 30%;">
                                    @for( $i = 0 ; $i < count($arraypph); $i++ )
                                    <option value="{{ $arraypph[$i]['value']}}">{{ $arraypph[$i]['label']}}</option>
                                    @endfor
                                @else
                                {{ $value->coa_id }}
                                @endif
                              </td>
                              <td>                            
                                {{ $value->type }}<br>
                                @if ( $value->head_type == "PPh")
                                  <input type="hidden" name="id_detail" value="{{ $value->id }}">
                                  <input type="text" name="pph" value="{{ $voucher->bap->spk->pph->kode }} ({{ $voucher->bap->spk->pph->pasal->pasal }})" style="width:30%"readonly>
                                  <!-- <select name="pph" id="pph" style="width: 30%;">
                                    @for( $i = 0 ; $i < count($arraypph); $i++ )
                                      <option value="{{ $arraypph[$i]['value']}}">{{ $arraypph[$i]['label']}}</option>
                                    @endfor
                                  </select>                               -->
                                  <input type="text" name="pph_percent" id="pph_percent" value="{{  
                                    (float)$voucher->bap->pph * 100}}" style="width: 10%" maxlength="4" readonly> %
                                @endif
                              </td>
                              @if ( $value->nilai < 0 )
                              @php $nilai = str_replace("-","",$value->nilai) @endphp
                              <td style="text-align: right;">Rp. ( {{ number_format($nilai,2) }} )</td>
                              @else
                              <td style="text-align: right;">Rp. {{ number_format($value->nilai,2 ) }}</td>
                              @endif
                            </tr>
                          @endif
                        @endforeach
                      @endif
                      <tr>
                        <td colspan="2" style="text-align: right;">Total</td>
                        <td style="text-align: right;">Rp. {{ number_format($voucher->details->sum('nilai'),2)}}</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <!-- this row will not appear when printing -->
              <div class="row no-print">
                <div class="col-xs-12">

                  <a href="{{ url('/')}}/voucher" class="btn btn-warning">Kembali</a>
                  <button class="btn btn-primary" id="btn_update_pph" type="submit">Simpan</button>
                  <a href="{{ url('/')}}/voucher/detail-units?id={{ $voucher->id }}" class="btn btn-success">Detail Unit</a>
                  
                </div>
              </div>
               </form>
               <button class="btn btn-primary" id="validasi_voucher">Validasi Voucher</button>
               <button class="btn btn-primary" id="kirim_kasir">Kirim Kasir</button>
               @if ($voucher->voucher_id != null)
                   voucher sudah di kirim ke kasir
               @endif
            </section>
            <!-- /.content -->
            </div>

            </form>
            <!-- /.col -->

            
          </div>
          <!-- /.row -->
        </div>
        <!-- /.box-body -->

      </div>
      <!-- /.box -->
      <div class="modal fade" id="editModalBG" role="dialog">
        <div class="modal-dialog modal-lg modal-md" style="width:40%;">
            <!-- Modal content-->
            <div class="modal-content center">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><span id="label_ba"></span></h4>
                    Isi Bank Garansi
                </div>
                <div class="modal-body" id="">
                    <div id="list_item" class="col-md-12">
                        <div class="form-group col-md-12 panel panel-info">
                            <div id="form_tambah_kategori" class="form-group col-md-12" style="margin-bottom:10px">
                                <label class="control-label col-md-12" style="padding-left:0">No Bank Garansi</label>
                                <input type='text' id='no_bank_garansi' value='' class="form-control"/>
                            </div>
                            <div id="form_tambah_kategori" class="form-group col-md-12" style="margin-bottom:10px">
                              <label class="control-label col-md-12" style="padding-left:0">Nama Bank</label>
                              <input type='text' id='nama_bank' value='' class="form-control"/>
                            </div>
                            <div id="form_tambah_kategori" class="form-group col-md-12" style="margin-bottom:10px">
                              <label class="control-label col-md-12" style="padding-left:0">Nilai</label>
                              <input type='text' id='nilai_bank_garansi' value='' class="form-control"/>
                            </div>
                            <div id="form_tambah_kategori" class="form-group col-md-12" style="margin-bottom:10px">
                              <label class="control-label col-md-12" style="padding-left:0">Tanggal Bank Garansi</label>
                              <input type='date' id='tanggal_bank_garansi' value='' class="form-control"/>
                            </div>
                            <div id="form_tambah_kategori" class="form-group col-md-12" style="margin-bottom:10px">
                              <label class="control-label col-md-12" style="padding-left:0">Tanggal Jatuh Tempo Bank Garansi</label>
                              <input type='date' id='tanggal_jatuh_tempo_bank_garansi' value='' class="form-control"/>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                    <input type='' id='id_korespondensi' value='' hidden/>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                        <button type="button" class="btn btn-primary simpan_bank_garansi" id="simpan_bank_garansi"> Simpan</button>
                    </div>
                </div>
            </div>
        </div>
      </div>

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
@include("voucher::app")
<script type="text/javascript">
$(document).ready(function(){
  $("#nilai_bank_garansi").number(true);
  $('#kirim_kasir').click(function(){
    var _url = '{{ url("/")}}/voucher/kirim_kasir';
    var voucher_id = $("#voucher_id").val();
      $.ajax({
        type : "POST",
        url  : _url,
        dataType : "JSON",
        data :{
          voucher_id: voucher_id,
        },
        beforeSend: function() {
          waitingDialog.show();
        },
        success : function(data){
            alert(data.response);
            window.location.replace('{{ url("/")}}/voucher/show?id='+voucher_id);
        }
        
    });
  });
  $('#validasi_voucher').click(function(){
    var _url = '{{ url("/")}}/voucher/validasi_voucher';
    var voucher_id = $("#voucher_id").val();
      $.ajax({
        type : "POST",
        url  : _url,
        dataType : "JSON",
        data :{
          voucher_id: voucher_id,
        },
        beforeSend: function() {
          waitingDialog.show();
        },
        success : function(data){
            alert(data.response);
            window.location.replace('{{ url("/")}}/voucher/show?id='+voucher_id);
        }
        
    });
  });
  
  $('#bank_garansi').click(function(){
    var _url = '{{ url("/")}}/voucher/cekBankGaransi';
    var voucher_id = $("#voucher_id").val();
      $.ajax({
        type : "POST",
        url  : _url,
        dataType : "JSON",
        data :{
          voucher_id: voucher_id,
        },
        // beforeSend: function() {
        //   waitingDialog.show();
        // },
        success : function(data){
            if(data.data != null){
              $("#no_bank_garansi").val(data.data.no_bank_garansi);
              $("#nama_bank").val(data.data.nama_bank);
              $("#nilai_bank_garansi").val(data.data.nilai);
              $("#tanggal_bank_garansi").val(data.data.tanggal_bank_garansi);
              $("#tanggal_jatuh_tempo_bank_garansi").val(data.data.tanggal_jatuh_tempo);
            }
        },
        // afterSend: function() {
        //   waitingDialog.hide();
        // },
      });

    $('#editModalBG').modal('show');
  });

  $('#simpan_bank_garansi').click(function(){
    var _url = '{{ url("/")}}/voucher/simpanBankGaransi';
    var voucher_id = $("#voucher_id").val();
      $.ajax({
        type : "POST",
        url  : _url,
        dataType : "JSON",
        data :{
          voucher_id: voucher_id,
          no_bg:$("#no_bank_garansi").val(),
          nama_bank:$("#nama_bank").val(),
          nilai_bg:$("#nilai_bank_garansi").val(),
          tanggal_bg:$("#tanggal_bank_garansi").val(),
          tanggal_jatuh_tempo_bg:$("#tanggal_jatuh_tempo_bank_garansi").val(),
        },
        beforeSend: function() {
          waitingDialog.show();
        },
        success : function(data){
            // alert(data.response);
            window.location.replace('{{ url("/")}}/voucher/show?id='+voucher_id);
        }
        
    });
  });
});
</script>
</body>
</html>
