<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Admin QS | Dashboard </title>
  @include("master/header")
    <!-- Select2 -->
  <link rel="stylesheet" href="{{ url('/')}}/assets/bower_components/select2/dist/css/select2.min.css">
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css">
  <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/css/bootstrap-datetimepicker.min.css">

  
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/css/bootstrap-datetimepicker.css"> -->

</head>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  @include("master/sidebar_project")

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>Data Proyek <strong>{{ $project->name }}</strong></h1>

    </section>

    <!-- Main content -->
    <section class="content">

      <!-- SELECT2 EXAMPLE -->
      <div class="box box-default">

        <!-- /.box-header -->
        <div class="box-body">
          <div class="row">
            <form action="{{ url('/') }}/tender/update" method="post" name="form1"> 
            <input type="hidden" name="aanwijzing_date_val" id="aanwijzing_date_val" value="{{ $data['aanwijzing_date']}}"> 
            <input type="hidden" name="penawaran1_date_val" id="penawaran1_date_val" value="{{ $data['penawaran1_date']}}"> 
            <input type="hidden" name="klarifikasi1_date_val" id="klarifikasi1_date_val" value="{{ $data['klarifikasi1_date']}}"> 
            <input type="hidden" name="penawaran2_date_val" id="penawaran2_date_val" value="{{ $data['penawaran2_date']}}"> 
            <input type="hidden" name="klarifikasi2_date_val" id="klarifikasi2_date_val" value="{{ $data['klarifikasi2_date']}}"> 
            <input type="hidden" name="pengumuman_date_val" id="pengumuman_date_val" value="{{ $data['penawaran3_date']}}"> 
            <div class="col-md-6">              
              <input type="hidden" name="tender_id" value="{{ $tender->id }}" id="tender_id"> 
              <input type="hidden" name="tender_kelas_id" value="{{ $tender->kelas_id }}" id="tender_kelas_id"> 
              <h3 class="box-title">Edit Data Tender</h3>           
                {{ csrf_field() }}
                <div class="form-group">
                  <label>No. RAB</label>
                  <input type="text" class="form-control" name="rab_id" value="{{ $tender->rab->no}}" readonly>
                  @if ( $tender->rab->approval != "" )
                  Approved at : <strong>{{ date("d/M/Y", strtotime($tender->rab->approval->updated_at)) }}</strong>
                  @endif
                </div>
                <div class="form-group">
                  <label>No. Tender</label>
                  <input type="text" class="form-control" name="tender_name" value="{{ $tender->no }}" readonly>
                </div> 
                <div class="form-group">
                  <label>Pekerjaan</label>
                  <input type="text" class="form-control" name="tender_name" value="{{ $itempekerjaan->code }} - {{ $itempekerjaan->name }}" readonly>
                </div>  
                <div class="form-group">
                  <label>Nama</label>
                  <input type="text" class="form-control" name="tender_name" value="{{ $tender->rab->name }}" autocomplete="off" required>
                </div>  
                <div class="form-group">
                  <label>Durasi Pekerjaan(hari kalender)</label> 
                  <!-- {{$tender->aanwijzing_date}} -->
                  <!-- {{$tanggal_sekarang}} -->
                  <!-- {{ date("d/M/Y", strtotime($tanggal_sekarang)) }} -->
                    <input type="text" class="form-control" name="tender_durasi" value="{{ $tender->durasi }}" autocomplete="off" required>
                </div> 
                <div class="form-group">
                  <label>Harga Dokumen</label>
                  <input type="text" class="form-control nilai_budget" name="harga_dokumen" value="{{ $tender->harga_dokumen }}" autocomplete="off" required>
                </div>
                <div class="form-group">
                  <label>Jenis Kontrak</label>
                  <select class='form-control' name='jenis_kontrak' id='jenis_kontrak' class="form-control">
                    <option value='LUMPSUM FIXED PRICE'>LUMPSUM FIXED PRICE</option>
                    <option value='FIXED PRICE'>FIXED PRICE</option>
                    <option value='LUMPSUM'>LUMPSUM</option>
                    <option value='REMEASURE'>REMEASURE</option>
                  </select>
                  <!-- <p>&nbsp;</p>
                  @foreach ( $tendermaster as $key => $value )
                    @if ( $tender->kelas_id == $value->id )
                      <label><input type="radio" name="tender_type" class="flat-red" value="{{ $value->id}}" checked>{{ $value->name }}</label>
                    @else
                      <label><input type="radio" name="tender_type" class="flat-red" value="{{ $value->id}}">{{ $value->name }}</label>
                    @endif
                  @endforeach -->

                </div>
                <div class="form-group">
                  <label>Jenis Tender</label><br/>  
                  @foreach ( $tendermaster as $key => $value )
                    @if ( $tender->kelas_id == $value->id )
                      <label><input type="radio" name="tender_type" class="flat-red" value="{{ $value->id}}" checked>{{ $value->name }}</label>
                    @else
                      <label><input type="radio" name="tender_type" class="flat-red" value="{{ $value->id}}">{{ $value->name }}</label>
                    @endif
                  @endforeach
                </div>
                <div class="form-group">
                  <table class="table borderless" style="margin-bottom:0; font-size: 15px; border: none;">
                    <tr>
                      <td style="width: 22%">Status Tender</td>
                      <td>:</td>
                      <td>
                        @if ( $tender->approval != "" )
                        {{-- {{$tender->approval->approval_action_id}} --}}
                          @if ( $tender->approval->approval_action_id == 7)
                            
                          @else
                            @php
                              $array = array (
                                "6" => array("label" => "Telah ditunjuk Pemenang", "class" => "label label-success"),
                                "7" => array("label" => "Ditolak", "class" => "label label-danger"),
                                "1" => array("label" => "Dalam Proses", "class" => "label label-warning"),
                                "8" => array("label" => "Close", "class" => "label label-danger")
                              )
                            @endphp
                            <span  class="{{ $array[$tender->approval->approval_action_id]['class'] }}">{{ $array[$tender->approval->approval_action_id]['label'] }}</span>
                          @endif
                        @else
                          <span  class=" label label-info"> Belum Proses </span>
                          @if ( count($tender->penawarans) <= 0  )
                            <!-- <button type="submit" class="btn btn-primary">Simpan</button> -->
                          @endif
                        @endif
                      </td>
                    </tr>
                    @if( $tender->tunjuk_pemenang_tender != null)
                      <tr>
                        <td style="width: 22%">Status Usulan Pemenang Tender</td>
                        <td>:</td>
                        <td>
                          @if ($tender->tunjuk_pemenang_tender->is_pemenang == 0 )
                              <span class="label label-warning"> Dalam Proses</span>
                          @elseif($tender->tunjuk_pemenang_tender->is_pemenang == 1)
                        <span class="label label-success"> Approve</span>
                          @elseif($tender->tunjuk_pemenang_tender->is_pemenang == 2)
                            <span class="label label-danger"> 
                              Reject <br>
                            </span>
                            <strong style=""> Alasan : {{$tender->tunjuk_pemenang_tender->approval->histories->where("approval_action_id",7)->first()->description}} </strong>
                          @endif
                        </td>
                      </tr>
                    @endif
                  </table>
                </div>
                <div class="form-group">
                  <a class="btn btn-warning" href="{{ url('/')}}/tender" style="margin: 5px 0px 3px 0px">Kembali</a>
                  <a class="btn btn-success" href="{{ url('/')}}/rab/detail?id={{ $tender->rab->id}}&idpkr={{$tender->rab->pekerjaans[0]->itempekerjaan->parent->id}}" style="margin: 5px 1px 3px 0px">
                  RAB 
                  <br/>
                  @if ( $tender->rab->approval->approval_action_id == 3)
                    <span class="label label-danger">di revisi</span>
                  @endif
                  </a>
                  
                  @if($tender->penawaran1_date == null)
                    @if ($tender->approval != null)
                      @if ($tender->approval->approval_action_id == 8)
                          
                      @else
                        <button type="submit" class="btn btn-primary" style="margin: 5px 0px 5px 0px">Simpan</button>
                      @endif
                    @else
                      <button type="submit" class="btn btn-primary" style="margin: 5px 0px 5px 0px">Simpan</button>
                    @endif
                  @endif
                  @if ( $tender->aanwijing == "" )
                    <a class="btn btn-success" href="{{ url('/')}}/tender/aanwijing?id={{$tender->id}}" style="margin: 5px 0px 5px 0px">AanWijing</a>
                  @else
                    <a class="btn btn-success" href="{{ url('/')}}/tender/aanwijing/detail?id={{$tender->aanwijing->id}}" style="margin: 5px 0px 5px 0px">AanWijing</a>
                    
                    <button class="btn btn-success cetak_aanwijing" id="" type="button" style="margin: 5px 0px 5px 0px">Cetak Aanwijing</button>
                  @endif
                  
                  @if ( $tender->approval != "" )
                  <a href="{{ url('/')}}/tender/approval_history?id={{ $tender->id }}" class="btn btn-primary" style="margin: 5px 0px 3px 0px">Approval History</a>
                  @endif

                  @if ( $tender->approval != null )
                    @if ( $tender->approval->approval_action_id != 8 )
                      <a href="#" class="btn btn-danger" data-toggle="modal" data-target="#exampleModal2">Close Tender</a>
                    @endif
                  @endif

                  @if ( count($tender->menangs) > 0 )
                    @if(count($tender->spks)<=0)
                      @if($tender->approval->approval_action_id== "6")
                      <a href="{{ url('/')}}/spk/create/?id={{ $tender->id }}" class="btn btn-info" style="margin: 5px 0px 5px 0px">Buat SPK</a>
                      @endif
                    @endif
                  @endif

                  @if ( $tender->menangs->count() > 0 )
                    <!-- <button class="btn btn-success" type="button" onClick="print('dvContents')">Cetak Dokumen Rekomendasi Tender</button> -->
                  @endif

                  @if ( $tender->aanwijing == "" )
                  <h4 style="color:red"><strong>Dokumen Aanwijing belum dibuat</strong></h4>
                  @endif
                  
                  <br/>
                  <table class="table borderless" style="margin-bottom:0; font-size: 15px; border: none;">
                     <tr colspan="2">
                      <td style="width:15%;text-align: left">
                          Lampiran
                      </td>
                      <td style="width:3%;">:</td>
                      <td style="width:50%;text-align: left">
                        @if(count($tender->tender_document) !=0)
                          @foreach ( $tender->tender_document as $key => $value )
                              <input type="checkbox" checked disabled>{{ $value->document_name or ''}} </br>
                          @endforeach
                        @else
                          Tidak Ada Lampiran
                        @endif
                      </td>
                    </tr>
                    <tr>
                      
                    </tr>
                  </table>

                  <!-- @foreach ( $tender->tender_document as $key => $value )
                    <input type="checkbox" checked disabled>{{ $value->document_name or ''}}
                  @endforeach -->
                </div>
              <!-- /.form-group -->              
            </div>
            <div class="col-md-6">      
              <h3>&nbsp;</h3> 
              <div class="form-group">
                <label>Tanggal Pengambilan Dokumen </label>
                <input type="text" id="ambil_doc_date" class="form-control" name="ambil_doc_date" value="@if ( $tender->ambil_doc_date != null ) {{ $tender->ambil_doc_date->format('d/M/Y') }} @endif" autocomplete="off" required>
              </div>
              <div class="form-group">
                <label>Tanggal Aanwijing </label>
                <input type="text" id="aanwijzing_date" class="form-control" name="aanwijzing_date" value="@if ( $tender->aanwijzing_date != null ) {{ $tender->aanwijzing_date->format('d/M/Y') }} @endif" autocomplete="off" required>
              </div>
              @if($tender->aanwijzing_date != null && $tender->aanwijzing_date <= $tanggal_sekarang)
                  @if(count($tender->rekanans) != 0 )
                  @php $i = 0 @endphp
                    @foreach($tender->rekanans as $key => $each)
                        @if($each->approval != null)
                          @if($each->approval->approval_action_id == 6)
                            @php $i += 1 @endphp
                          @endif
                        @endif
                    @endforeach
                    @if(1 <= $i)
                        <div class="form-group">
                          <label>Tanggal Penawaran Pertama </label>
                          <input type="text" id="penawaran1_date" class="form-control" name="penawaran1_date" value="@if ( $tender->penawaran1_date != null ) {{ $tender->penawaran1_date->format('d/M/Y') }} @endif" autocomplete="off" required>
                        </div>
                        <div class="form-group">
                          <label>Tanggal Klarifikasi Pertama </label>
                          <input type="text" id="klarifikasi1_date" class="form-control" name="klarifikasi1_date" value="@if ( $tender->klarifikasi1_date  != null ) {{ $tender->klarifikasi1_date->format('d/M/Y') }} @endif" autocomplete="off" required>
                        </div>
                    @else
                      <span><Strong style="color:red"> Belum dapat Memasukan Jadwal Penawaran Pertama Karena belum ada rekanan yang di setujui </strong></span>
                    @endif
                    
                  @else
                    <span><Strong style="color:red"> Untuk Memasukan Jadwal penawaran pertama harap melakukan Proses Request Rekanan Terlebih Dahulu </strong></span>
                  @endif
              @endif
              <!-- <div class="container">
                <div class="row">
                  <div class='col-sm-6'>
                      <div class="form-group">
                          <div class='input-group date' id='datetimepicker2'>
                              <input type='text' class="form-control" />
                              <span class="input-group-addon">
                                  <span class="glyphicon glyphicon-calendar"></span>
                              </span>
                          </div>
                      </div>
                  </div>
                </div>
              </div> -->

              <!-- <div class="form-group">
                <label>Tanggal Penawaran Kedua </label>
                <input type="text" id="penawaran2_date" class="form-control" name="penawaran2_date" value="@if ( $tender->penawaran2_date != null ) {{ $tender->penawaran2_date->format('d/M/Y') }} @endif" autocomplete="off" >
              </div>
              <div class="form-group">
                <label>Tanggal Klarifikasi Kedua </label>
                <input type="text" id="klarifikasi2_date" class="form-control" name="klarifikasi2_date" value="@if ( $tender->klarifikasi2_date != null ) {{ $tender->klarifikasi2_date->format('d/M/Y') }} @endif" autocomplete="off" >
              </div> -->
              <div class="form-group">
                <label>Tanggal Pengumuman Pemenang</label>
                <input type="text" id="pengumuman_date" class="form-control" name="pengumuman_date" value="@if ( $tender->pengumuman_date != null ) {{ $tender->pengumuman_date->format('d/M/Y') }} @endif" autocomplete="off">
              </div>

            </div>
            </form>
            <div class="col-md-12">                
              <div class="nav-tabs-custom">    
                <ul class="nav nav-tabs">                
                  <li  class="active"><a href="#tab_2" data-toggle="tab">1. Rekanan</a></li>
                  <li><a href="#tab_3" data-toggle="tab">2. Korespondensi</a></li>
                  <li><a href="#tab_4" data-toggle="tab">3. Penawaran</a></li>
                  <li><a href="#tab_5" data-toggle="tab">4. Unit</a></li>
                </ul>
                <div class="tab-content">                
                  <!-- /.tab-pane -->

                  <div class="tab-pane active" id="tab_2">                   
                    
                    <input type="hidden" name="disable_param" id="disable_param" value="0">
                          
                    @if ( count($tender->spks)<= 0  )
                      @if ( $tender->ambil_doc_date == "" || $tender->aanwijzing_date == "")
                        <h3><i>Data Tanggal Penawaran belum diisi</i></h3>
                      @else
                        @if ( count($tender->penawarans) <= 0)
                          @if($tender->tender_type != null)
                              <a class="btn btn-info" href="{{ url('/')}}/tender/rekanan/referensi?id={{ $tender->id}}">
                                Tambah Rekanan
                              </a>
                              @if ($tender->kelas_id == 1)
                                <button type="button" class="btn btn-warning btn-md ba_tunjuklangsung" data-id="{{$tender->id}}" data-target="#editModalBA" style="">BA Tunjuk Langsung</button>
                              @elseif($tender->kelas_id == 2)
                                <button type="button" class="btn btn-warning btn-md ba_tunjuklangsung" data-id="{{$tender->id}}" data-target="#editModalBA" style="">BA Repeat Order</button>
                              @endif
                            <br><br>     
                          @else
                            <a class="btn btn-info"  disabled>
                              Tambah Rekanan
                            </a><br>
                            <span><Strong style="color:red"> Type Tender belum di pilih </strong></span><br>  
                          @endif
                        @endif              
                      @endif
                    @else
                      @if ($tender->kelas_id == 1)
                        <button type="button" class="btn btn-warning btn-md ba_tunjuklangsung" data-id="{{$tender->id}}" data-target="#editModalBA" style="">BA Tunjuk Langsung</button>
                      @elseif($tender->kelas_id == 2)
                        <button type="button" class="btn btn-warning btn-md ba_tunjuklangsung" data-id="{{$tender->id}}" data-target="#editModalBA" style="">BA Repeat Order</button>
                      @endif
                    @endif

                    @php $start = 0; @endphp
                    
                    <!-- <form action="{{ url('/')}}/tender/approval-rekanan" method="post" name="form1"> -->
                      {{ csrf_field() }}
                    <input type="hidden" name="tender_id" id="tender_id" value="{{ $tender->id }}">
                    @if ($tender->harga_dokumen > 0)
                      @if ($coa_dokumen_tender == null)
                        <label style="color:red">COA Finance untuk Biaya Dokumen tender belum terisi</label>
                      @endif
                    @endif
                    <table class="table table-bordered" style="width: 50%;">
                     <thead class="head_table">
                       <tr>
                        <td>Rekanan</td>
                        <td>Posting Voucher</td>
                        <td>Status</td>
                        <td>Status Pembayaran</td>
                        <td>Delete</td>
                       </tr>
                     </thead>
                     <tbody>
                        @foreach ( $tender->rekanans as $key => $value )
                        @if($value->rekanan != null)
                          <tr>
                            <td>{{ $value->rekanan->group->name }}</td>
                            <td>
                              @if ( $value->approval != null )
                                @if ( $value->approval->approval_action_id == 6 )
                                  @if($value->doc_bayar_status == 0)
                                      @if ( $value->posting_voucher != 0 && $value->posting_voucher != "")
                                        <span class="label label-success">Voucher sudah di posting</span>
                                      @else
                                        <input type="checkbox" class="paramdisable posting_voucher" name="" value="{{ $value->id }}" >Posting Voucher
                                      @endif
                                  @else
                                    <span class="label label-success">Voucher sudah di posting</span>
                                  @endif
                                @endif
                              @endif
                            </td>
                            <td>
                              @if ( $value->approval == null )
                              <input type="checkbox" class="paramdisable rekanan_approve" name="rekanan_['{{$key}}']" value="{{ $value->id }}" >Request Approve
                              @else
                              @php
                                $array = array (
                                  "6" => array("label" => "Disetujui", "class" => "label label-success"),
                                  "7" => array("label" => "Ditolak", "class" => "label label-danger"),
                                  "1" => array("label" => "Dalam Proses", "class" => "label label-warning")
                                )
                              @endphp
                              <span class="{{ $array[$value->approval->approval_action_id]['class'] }}">{{ $array[$value->approval->approval_action_id]['label'] }}</span>
                              @endif
                            </td>
                            <td>
                              @if ( $value->approval != null)
                                @if ( $value->approval->approval_action_id == 6 )
                                  @if ( $value->doc_bayar_status == 0 )
                                    <!-- <button class="btn-success btn-sm" onClick="cekLunas('{{$value->id}}')" type="button">Cek Bayar</button> -->
                                    <span>Belum Bayar</span>
                                  @else
                                    <span>Sudah Bayar</span>
                                  @endif
                                @endif
                              @endif
                            </td>
                            <td>
                              @if ( $value->approval != null )
                                @if ( $value->approval->approval_action_id == "" )
                                  @if ( count($tender->penawarans) <= 0 )
                                  <button type="button" class="btn btn-danger" onclick="removerekanan('{{ $value->id }}','{{ $value->rekanan->group->name }}')">Delete</button>
                                  @endif
                                @endif
                              @endif
                            </td>
                          </tr>
                        @else
                          {{$value}}
                        @endif
                        @endforeach
                        </tbody>
                      </table>
                      @if ( count($tender->spks)<= 0  )
                        @if ( count($tender->rekanans) > 0 )
                          @if ($tender->kelas_id == 3)
                            <button type="hidden" class="btn btn-primary" id="btn_submit">Submit</button><br>
                          @else
                              @if ($tender->berita_acara_tunjuk_langsung != null)
                                  @if ($tender->berita_acara_tunjuk_langsung->isian != null)
                                    <button type="hidden" class="btn btn-primary" id="btn_submit">Submit</button><br>
                                  @else
                                    <button type="hidden" class="btn btn-primary" id="btn_submit" disabled>Submit</button><br>
                                  @endif
                              @else
                                <button type="hidden" class="btn btn-primary" id="btn_submit" disabled>Submit</button><br>
                              @endif
                          @endif
                          <button type="hidden" class="btn btn-primary" id="btn_posting">Posting</button><br>
                          <i>Harap pastikan kelengkapan dokumen dan checklist status approval sebelulmnya</i>
                        @endif
                      @endif
<!-- 
                      @if ( count($tender->spks)<= 0  )
                        @if ( count($tender->rekanans) > 0 )
                          <button type="submit" class="btn btn-primary" id="btn_posting">Posting</button><br>
                        @endif
                      @endif -->
                    <!-- </form> -->
                  </div>
                  <div class="tab-pane " id="tab_5">
                    <table class="table-bordered table">
                      <thead class="head_table">
                        <tr>
                          <td>Unit Name</td>
                          <td>Type</td>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ( $tender->units as $key => $value )
                        <tr>
                          @if($value->rab_unit->asset != "")
                            <td>{{ $value->rab_unit->asset->name }}</td>
                            @if ( $value->rab_unit->asset_type == "\Modules\Project\Entities\Unit") 
                            <td>{{ $value->rab_unit->asset->type->name }}</td>
                            @else
                            <td>{{ $value->rab_unit->asset_type }}</td>
                            @endif
                          @else
                            <td>{{ $value->rab_unit->project->name }}</td>
                            <td>{{ $value->rab_unit->asset_type }}</td>
                          @endif
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                  <div class="tab-pane" id="tab_3">
                    <table class="table table-bordered">
                      <thead class="head_table">
                        <tr>
                          <td>No.</td>
                          <td>Rekanan</td>
                          <td>Kirim Undangan Aanwidjzing</td>
                          <td>Kirim BA Klarifikasi Tender</td>
                          <td>Kirim Surat Pemenang Tender</td>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ( $tender->rekanans as $key => $value )
                          @foreach ( $value->korespondensis as $key2 => $value2 )
                            <tr>
                              <td>{{ $value2->no }}</td>
                              <td>{{ $value->rekanan->group->name }}</td>
                              <td>
                                @if ( $value2->no != "" )
                                  @if ($value2->tender_rekanan->tender->aanwijing != null)
                                    <button type="button" class="btn btn-info btn-sm kirim_undangan" data-id="{{$value2->id}}" data-target="#editModalKirim" style="">Kirim</button>
                                  @else
                                    <button type="button" class="btn btn-info btn-sm kirim_undangan" data-id="{{$value2->id}}" data-target="#editModalKirim" style="" disabled>Kirim</button><br>
                                    <li style="color:red;">Dokumen Aanwidjzing belum terisi</li>
                                  @endif
                                @endif
                              </td>
                              <td>
                                @if ( $value2->no != "" )
                                  <button type="button" class="btn btn-info btn-sm kirim_surat_klarifikasi_tender" data-id="{{$value2->id}}" data-target="#myModalSuratKlarifikasiTender" style="">Kirim</button>
                                @endif
                              </td>
                              <td>
                                @if(count($tender->menangs) != 0)
                                  @if ( $value2->no != "" )   
                                    <button type="button" class="btn btn-info btn-sm kirim_surat_pemenang_tender" data-id="{{$value2->id}}" data-target="#myModalSuratPemenangTender" style="">Kirim</button>
                                  @endif
                                @else
                                  Pemenang belum di tunjuk
                                @endif
                              </td>
                            </tr>                        
                          @endforeach
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                  <div class="tab-pane" id="tab_4">
                    @if ( $tender->approval != "" )
                      @if ( $tender->approval->approval_action_id != 6 && $tender->approval->approval_action_id != 8)
                        @if ( count($tender->tender_approve) > 0 )
                          @if(count($tender->tender_jadwal_penawaran) != 0)
                            @if(date("Y-m-d", strtotime($tender->tender_jadwal_penawaran->last()->penawaran_date)) < date("Y-m-d", strtotime($tanggal_sekarang)))
                              @if ($tender->tunjuk_pemenang_tender == null || $tender->tunjuk_pemenang_tender->is_pemenang == 2)
                                <a type="button" class="btn btn-info" href="{{ url('/')}}/tender/penawaran-addstep_berulang?id={{ $tender->id}}">
                                  Input Data Volume Terbaru
                                </a>
                                <a href="{{ url('/')}}/tender/berita_acara?id={{$tender->id}}&step={{$tender->tender_jadwal_penawaran->last()->penawaran_ke}}" class="btn btn-info" style="padding-left:20px">Berita Acara Pertama</a>
                              @endif
                            @endif
                          @endif
                        @endif
                      @endif
                    @endif
                    <button class="btn btn-info" type="button" id="cetak_ba"> cetak Berita Acara</button>
                    <br><br>  
                    <table class="table table-bordered ">
                      <thead class="head_table">                          
                        <tr>
                          <td>Rekanan</td>
                          <td></td>
                          @foreach($tender->tender_jadwal_penawaran as $key4 => $kunci)
                            <td>Penawaran {{$key4 + 1}}</td>
                            <!-- <td>Penawaran 2</td>
                            <td>Penawaran 3</td> -->
                          @endforeach
                        </tr>
                      </thead>
                        <tbody>
                          @if ( $tender->aanwijing != "" ) 
                            <tr>
                              <td>Detail Penawaran</td>
                              <td></td>
                              @foreach($tender->tender_jadwal_penawaran as $key4 => $kunci)
                                <td>
                                <!-- {{$tender->penawarans}}
                                <br/><br/>
                                {{$tender->spks}} -->
                                  @if ( count($tender->penawarans) > $kunci->penawaran_ke-1 || count($tender->spks) > 0 ) 
                                    @if( date("Y-m-d",strtotime($kunci->penawaran_date)) < date("Y-m-d",strtotime($tanggal_sekarang)) )
                                      <a href="{{ url('/')}}/tender/detail-penawaran?id={{$tender->id}}&step={{$kunci->penawaran_ke}}" class="btn btn-warning">Detail</a> tanggal akhir penawaran : {{date("d-M-Y",strtotime($kunci->penawaran_date))}}
                                    @else
                                      Belum dapat melihat hasil penawaran<br>
                                      tanggal akhir penawaran : {{date("d-M-Y",strtotime($kunci->penawaran_date))}}
                                    @endif
                                    <!-- <a href="{{ url('/')}}/tender/detail-penawaran?id={{$tender->id}}&step=1" class="btn btn-warning">Detail</a> -->

                                    @elseif ( count($tender->penawarans) == $key4+1 )
                                      @if ( count($tender->berita_acara) > 0 )
                                        @foreach ( $tender->berita_acara as $key => $value )    
                                          @if ( $value->step == $key4+1)                              
                                            <a href="{{ url('/')}}/tender/berita_acara/show?id={{$value->id}}" class="btn btn-info">Berita Acara Pertama</a>
                                          @endif
                                        @endforeach
                                      @else
                                      <a href="{{ url('/')}}/tender/berita_acara?id={{$tender->id}}&step=1" class="btn btn-info">Berita Acara Pertama</a>
                                      @endif
                                  @endif
                                </td>
                              @endforeach
                                  <!-- <td>
                                @if ( count($tender->penawarans) > 1 || count($tender->spks) > 0 )
                                <a href="{{ url('/')}}/tender/detail-penawaran?id={{$tender->id}}&step=1" class="btn btn-warning">Detail</a>
                                @elseif ( count($tender->penawarans) == 1 )
                                  @if ( count($tender->berita_acara) > 0 )
                                    @foreach ( $tender->berita_acara as $key => $value )    
                                      @if ( $value->step == 1)                              
                                        <a href="{{ url('/')}}/tender/berita_acara/show?id={{$value->id}}" class="btn btn-info">Berita Acara Pertama</a>
                                      @endif
                                    @endforeach
                                  @else
                                  <a href="{{ url('/')}}/tender/berita_acara?id={{$tender->id}}&step=1" class="btn btn-info">Berita Acara Pertama</a>
                                  @endif
                                @endif
                              </td>
                              <td>                            
                                @if ( count($tender->penawarans) > 2 || count($tender->spks) > 0)
                                <a href="{{ url('/')}}/tender/detail-penawaran?id={{$tender->id}}&step=2" class="btn btn-warning">Detail</a>
                                @elseif( count($tender->penawarans) == 2 || count($tender->spks) > 0 )
                                <a href="{{ url('/')}}/tender/berita_acara?id={{$tender->id}}&step=1" class="btn btn-info">Berita Acara Kedua</a>
                                @endif
                              </td>
                              <td>                            
                                @if ( count($tender->spks) > 3 || count($tender->spks) > 0 )
                                <a href="{{ url('/')}}/tender/detail-penawaran?id={{$tender->id}}&step=3" class="btn btn-warning">Detail</a>
                                @endif
                              </td> -->
                            </tr>
                            @foreach( $tender->rekanans as $key2 => $value2)
                              @if ( $value2->approval != null)
                                @if ( $value2->approval->approval_action_id != "" )
                                  @if ( $value2->approval->approval_action_id == "6")
                                    <tr>
                                      <td>{{ $value2->rekanan->group->name }}</td>
                                      <td>&nbsp;</td>
                                      @if(count($value2->penawarans) != 0)
                                      {{-- {{$value2->penawarans}} --}}
                                        @foreach ( $value2->penawarans as $key3 => $value3)
                                          <td>
                                            <span style="font-size: 14px;">
                                              @if ( $value3->details->sum("total_nilai") > 0 || $value3->details->sum("nilai") > 0)
                                                Penawaran telah masuk
                                              @else
                                                Rekanan belum mengisi penawaran
                                              @endif
                                            </span>                              
                                          </td>
                                        @endforeach

                                      @endif
                                    </tr>
                                  @endif
                                @endif
                              @endif
                            @endforeach
                          @else
                            <tr>
                              <td colspan="5">Dokumen Aanwijing belum dibuat</td>
                            </tr>
                          @endif
                        </tbody>
                    </table>
                  </div>
                </div>
              </div>
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
  @include("master/copyright")

  
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>

</div>

<div class="modal fade" id="editModalKirim" role="dialog">
    <div class="modal-dialog modal-lg modal-md" style="width:30%;">
        <!-- Modal content-->
        <div class="modal-content center">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Kirim Undangan</h4>
            </div>
            <div class="modal-body" id="">
                <div id="list_item" class="col-md-12">
                    <div class="form-group col-md-12 panel panel-info">
                        <div id="form_tambah_kategori" class="form-group col-md-12" style="margin-bottom:10px">
                            <label class="col-md-12" style="padding-left:0">Jam Mulai</label>
                            <div class="col-md-12" style="padding:0;">
                              <input type='time' id='input_jammulai' name='input_jammulai' class='form-control input_jammulai' />
                            </div>

                            <label class="control-label col-md-12" style="padding-left:0">Tempat Acara</label>
                            <div class="col-md-12" style="padding:0;">
                                <!-- <input type='text' id='input_tempat' name='input_tempat' class='form-control input_tempat' /> -->
                                <textarea class='form-control' name="input_tempat" id="input_tempat" cols="45" rows="5" style="max-width: 280px"></textarea>
                            </div>
                            <label class="control-label col-md-12" style="padding-left:0">No. Rekening</label>
                            <div class="col-md-12" style="padding:0;">
                              <input type='text' id='input_norek' name='input_norek' class='form-control input_norek' />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                <input type='' id='id_korespondensi' value='' hidden/>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary pull-right cetak_undangan">Cetak</button>
                    <button type="submit" class="btn btn-primary pull-right kirim_undangan_aanwijing"> Kirim</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="myModalSuratPemenangTender" role="dialog">
    <div class="modal-dialog modal-lg modal-md" style="width:30%;">
        <!-- Modal content-->
        <div class="modal-content center">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Kirim Pengumuman Pemenang Tender</h4>
            </div>
            <div class="modal-body" id="">
                <div class="modal-footer">
                <input type='' id='id_korespondensi2' value='' hidden/>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary pull-right cetak_surat_pemenang_tender">Cetak</button>
                    <button type="submit" class="btn btn-primary pull-right kirim_email_surat_pemenang_tender"> Kirim</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="myModalSuratKlarifikasiTender" role="dialog">
    <div class="modal-dialog modal-lg modal-md" style="width:30%;">
        <!-- Modal content-->
        <div class="modal-content center">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Kirim BA Klarifikasi tender</h4>
            </div>
            <div class="modal-body" id="">
            <div id="list_item" class="col-md-12">
                    <div class="form-group col-md-12 panel panel-info">
                        <div id="form_tambah_kategori" class="form-group col-md-12" style="margin-bottom:10px">
                            <label class="col-md-12" style="padding-left:0">Jam Mulai</label>
                            <div class="col-md-12" style="padding:0;">
                              <input type='time' id='input_jammulai_klarifikasi' name='input_jammulai_klarifikasi' class='form-control input_jammulai' />
                            </div>

                            <label class="control-label col-md-12" style="padding-left:0">Tempat Acara</label>
                            <div class="col-md-12" style="padding:0;">
                                <textarea class='form-control' name="input_tempat" id="input_tempat_klarifikasi" cols="45" rows="5" style="max-width: 280px"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                <input type='' id='id_korespondensi3' value='' hidden/>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary pull-right cetak_surat_klarifikasi_tender">Cetak</button>
                    <button type="submit" class="btn btn-primary pull-right kirim_email_surat_klarifikasi_tender"> Kirim</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
      <div class="modal-content">
          <div class="modal-header">
              Close RAB
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
              <strong>
              Apakah anda yakin untuk MengClose Tender ini<br>
              (Data dan penawaran yang  berhubungan dengan Tender ini akan di Close)
              </strong>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary" onclick="closeTender({{$tender->id}})">Submit</button>
              <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
          </div>
      </div>
  </div>
</div>

<div class="modal fade" id="editModalBA" role="dialog">
  <div class="modal-dialog modal-lg modal-md" style="width:80%;">
      <!-- Modal content-->
      <div class="modal-content center">
          <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title"><span id="label_ba"></span></h4>
              {{-- Berita Acara Tunjuk Langsung --}}
          @if ($tender->berita_acara_tunjuk_langsung != null)
            <input type="hidden" id="beritaacara_id" value="{{$tender->berita_acara_tunjuk_langsung->id}}">
          @else
            <input type="hidden" id="beritaacara_id" value="">
          @endif
          </div>
          <div class="modal-body" id="">
              <div id="list_item" class="col-md-12">
                  <div class="form-group col-md-12 panel panel-info">
                      <div id="form_tambah_kategori" class="form-group col-md-12" style="margin-bottom:10px">

                          <label class="control-label col-md-12" style="padding-left:0">isi Berita Acara</label>
                          @if ($tender->berita_acara_tunjuk_langsung != null)
                            <div class="col-md-12" style="padding:0;">
                            <textarea class="form-control" style="height: 33px;min-width: 250px; margin: 0px; min-height: 250px; max-height: 350px; height: 250px; max-width: 350px width: 350px;" rows="7" id="isian1" name="isian">{{$tender->berita_acara_tunjuk_langsung->isian}}</textarea>
                            </div>
                          @else
                            <div class="col-md-12" style="padding:0;">
                              <textarea class="form-control" style="height: 33px;min-width: 250px; margin: 0px; min-height: 250px; max-height: 350px; height: 250px; max-width: 350px width: 350px;" rows="7" id="isian1" name="isian"></textarea>
                            </div>
                          @endif

                      </div>
                  </div>
              </div>
              <div class="modal-footer">
              <input type='' id='id_korespondensi' value='' hidden/>
                  <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                  <button type="button" class="btn btn-primary simpan_ba"> Simpan</button>
                  @if (count($tender->rekanans) != 0)
                    @if ($tender->rekanans[0]->approval != null)
                      @if ($tender->rekanans[0]->approval->approval_action_id == 6)
                        <button type="button" class="btn btn-primary pull-right cetak_batl" id="cetak_batl">Cetak</button>
                      @endif
                    @endif
                  @endif
              </div>
          </div>
      </div>
  </div>
</div>

<!-- ./wrapper -->
@include("tender::cetakan")
@include("master/footer_table")
@include("tender::app")
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/js/bootstrap-datetimepicker.min.js"></script> -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/locales.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/css/bootstrap-datetimepicker.css"></script>
<script src="https://cdn.ckeditor.com/4.11.3/standard/ckeditor.js"></script>

<script type="text/javascript">
// $('.clockpicker').clockpicker();
// $(function () {
//     $('#datetimepicker').datetimepicker({
//         format: 'LT'
//     });
// });
  // $(function () {
  //     $('#penawaran1_date_baru').datetimepicker({
  //         locale: 'ru'
  //     });
  // });
  $(function () {
    CKEDITOR.replace( 'isian' );
  });

  function setpemenang(id,name){
    if ( confirm("Apakah anda yakin ingin menjadikan " + name + " sebagai pemenang tender ?")){
      var request = $.ajax({
        url : "{{ url('/')}}/tender/ispemenang",
        dataType : 'json',
        data : {
          id : id
        },
        type : "post"
      });

      request.done(function(data){
        if ( data.status == "0"){
            alert("Rekanan telah diajukan sebagai pemenang");
        } 
        window.location.reload();
      })
    }else{
      return false;
    }
  }

  function cetakanpengajuan(id){

  }

  function disablebtn(id){
    var valor = [];
    $('input.paramdisable[type=checkbox]').each(function () {
        if (this.checked)
          valor.push($(this).val());
    });

    if (valor.length < parseInt( $("#disable_param").val()) + 4 ) {
      $("#btn_approval_rekanan").attr("disabled","disabled");
    }else{
      $("#btn_approval_rekanan").removeAttr("disabled");
    }
  }

  function print(data){
     var myPrintContent = document.getElementById('head_Content');
        var myPrintWindow = window.open("", "");
        myPrintWindow.document.write(myPrintContent.innerHTML);
        myPrintWindow.document.getElementById('dvContents').style.display='block'
        myPrintWindow.document.close();
        myPrintWindow.focus();
        myPrintWindow.print();
        myPrintWindow.close();    
        return false;
  }

  $(document).on('click', '.kirim_undangan', function() {
    var _url = "{{ url('/tender/data_Aanwijing') }}";
    id_korespondensi = $(this).attr('data-id');
    $.ajax({
        type: 'get',
        dataType: 'json',
        url: _url,
        data: {
          id_korespondensi: id_korespondensi
        },
        beforeSend: function() {
            waitingDialog.show();
        },
        success: function(data) {
          $('#input_jammulai').val(data.data['jam_mulai']);
          $('#input_tempat').val(data.data['tempat']);
        },
        complete: function() {
            waitingDialog.hide();
        }
    });
    $('#id_korespondensi').val($(this).attr('data-id'));
    $('#editModalKirim').modal('show');
  });

$(document).on('click', '.kirim_surat_pemenang_tender', function() {
  $('#id_korespondensi2').val($(this).attr('data-id'));
  $('#myModalSuratPemenangTender').modal('show');
});

$(document).on('click', '.kirim_surat_klarifikasi_tender', function() {
  var _url = "{{ url('/tender/data_Klarifikasi') }}";
  id_korespondensi = $(this).attr('data-id');
  $.ajax({
      type: 'get',
      dataType: 'json',
      url: _url,
      data: {
        id_korespondensi: id_korespondensi
      },
      beforeSend: function() {
          waitingDialog.show();
      },
      success: function(data) {
        $('#input_jammulai_klarifikasi').val(data.data['jam_mulai']);
        $('#input_tempat_klarifikasi').val(data.data['tempat']);
      },
      complete: function() {
          waitingDialog.hide();
      }
  });
  $('#id_korespondensi3').val($(this).attr('data-id'));
  $('#myModalSuratKlarifikasiTender').modal('show');
});

$(document).on('click', '.cetak_undangan', function() {
  var _url = "{{ url('/tender/cetakSuratUndangan') }}";
  var id_korespondensi = $("#id_korespondensi").val();
  var jam_mulai = $("#input_jammulai").val();
  var tempat = $("#input_tempat").val();
  var norek = $("#input_norek").val();
  window.open("/tender/cetakSuratUndangan?id_korespondensi="+id_korespondensi+"&jam_mulai="+jam_mulai+"&tempat="+tempat+"&norek="+norek,'_blank');
  // $.ajax({
  //     type: 'get',
  //     dataType: 'json',
  //     url: _url,
  //     data: {
  //       id_korespondensi : id_korespondensi,
  //       jam_mulai : jam_mulai,
  //       tempat : tempat
  //     },
  //     beforeSend: function() {
  //         waitingDialog.show();
  //     },
  //     success: function(data) {

  //     },
  //     complete: function() {
  //         waitingDialog.hide();
  //     }
  // });
});

$(document).on('click', '.kirim_undangan_aanwijing', function() {
  var _url = "{{ url('/tender/kirimUndanganAanwijing') }}";
  var id_korespondensi = $("#id_korespondensi").val();
  var jam_mulai = $("#input_jammulai").val();
  var tempat = $("#input_tempat").val();
  var norek = $("#input_norek").val();
  $.ajax({
      type: 'get',
      dataType: 'json',
      url: _url,
      data: {
        id_korespondensi : id_korespondensi,
        jam_mulai : jam_mulai,
        tempat : tempat,
        norek : norek,
      },
      beforeSend: function() {
          waitingDialog.show();
      },
      success: function(data) {

      },
      complete: function() {
          waitingDialog.hide();
      }
  });
});

$(document).on('click', '.cetak_surat_pemenang_tender', function() {
  var _url = "{{ url('/tender/cetakSuratPemenangTender') }}";
  var id_korespondensi = $("#id_korespondensi2").val();
  window.open("/tender/cetakSuratPemenangTender?id_korespondensi="+id_korespondensi,'_blank');
  // http://cpms.ciputragroup.com:81/public/tender/cetakSuratPemenangTender?id_korespondensi=1297
  // $.ajax({
  //     type: 'get',
  //     dataType: 'json',
  //     url: _url,
  //     data: {
  //       id_korespondensi : id_korespondensi,
  //     },
  //     beforeSend: function() {
  //         waitingDialog.show();
  //     },
  //     success: function(data) {

  //     },
  //     complete: function() {
  //         waitingDialog.hide();
  //     }
  // });
});

$(document).on('click', '.kirim_email_surat_pemenang_tender', function() {
  var _url = "{{ url('/tender/kirimSuratPemenangTender') }}";
  var id_korespondensi = $("#id_korespondensi2").val();
  $.ajax({
      type: 'get',
      dataType: 'json',
      url: _url,
      data: {
        id_korespondensi : id_korespondensi,
      },
      beforeSend: function() {
          waitingDialog.show();
      },
      success: function(data) {

      },
      complete: function() {
          waitingDialog.hide();
      }
  });
});

$(document).on('click', '.cetak_surat_klarifikasi_tender', function() {
  var _url = "{{ url('/tender/cetakSuratKlarifikasiTender') }}";
  var id_korespondensi = $("#id_korespondensi3").val();
  var jam_mulai = $("#input_jammulai_klarifikasi").val();
  var tempat = $("#input_tempat_klarifikasi").val();
  window.open("/tender/cetakSuratKlarifikasiTender?id_korespondensi="+id_korespondensi+"&jam_mulai="+jam_mulai+"&tempat="+tempat,'_blank');
  // http://cpms.ciputragroup.com:81/public/tender/cetakSuratKlarifikasiTender?id_korespondensi=1297&jam_mulai=07%3A00&tempat=Ruang%20Meeting%20Site%20Office
  // $.ajax({
  //     type: 'get',
  //     dataType: 'json',
  //     url: _url,
  //     data: {
  //       id_korespondensi : id_korespondensi,
  //       jam_mulai : jam_mulai,
  //       tempat : tempat
  //     },
  //     beforeSend: function() {
  //         waitingDialog.show();
  //     },
  //     success: function(data) {

  //     },
  //     complete: function() {
  //         waitingDialog.hide();
  //     }
  // });
});

$(document).on('click', '.kirim_email_surat_klarifikasi_tender', function() {
  var _url = "{{ url('/tender/kirimSuratKlarifikasiTender') }}";
  var id_korespondensi = $("#id_korespondensi3").val();
  var jam_mulai = $("#input_jammulai_klarifikasi").val();
  var tempat = $("#input_tempat_klarifikasi").val();
  $.ajax({
      type: 'get',
      dataType: 'json',
      url: _url,
      data: {
        id_korespondensi : id_korespondensi,
        jam_mulai : jam_mulai,
        tempat : tempat
      },
      beforeSend: function() {
          waitingDialog.show();
      },
      success: function(data) {

      },
      complete: function() {
          waitingDialog.hide();
      }
  });
});

$(document).on('click', '.cetak_aanwijing', function() {
  var _url = "{{ url('/tender/cetakAanwijing') }}";
  var id_tender = $("#tender_id").val();
  window.open("/tender/cetakAanwijing?id="+id_tender,'_blank');
  // $.ajax({
  //     type: 'get',
  //     dataType: 'json',
  //     url: _url,
  //     data: {
  //       id : id_tender
  //     },
  //     beforeSend: function() {
  //         waitingDialog.show();
  //     },
  //     success: function(data) {

  //     },
  //     complete: function() {
  //         waitingDialog.hide();
  //     }
  // });
});

$(document).ready(function(){

$('#btn_submit').hide();
var checkboxes1 = $('.rekanan_approve');
$(checkboxes1).on('change', function() {
  if($(checkboxes1).is(':checked')) {
    $('#btn_submit').show();
  }else{
    $('#btn_submit').hide();
  }
});

  $('#btn_submit').click(function(){
       input('approve');
  });

$('#btn_posting').hide();
var checkboxes = $('.posting_voucher');
$(checkboxes).on('change', function() {
  if($(checkboxes).is(':checked')) {
    $('#btn_posting').show();
  }else{
    $('#btn_posting').hide();
  }
});
$('#btn_posting').click(function(){
    $(this).hide();
    input('posting_voucher');
  });
  // $('#penawaran1_date_baru').datetimepicker({
  //     locale: 'ru'
  // });
});
function input(variable){
  var insert = [];
  if(variable == 'approve'){
    var _url = '{{ url("/")}}/tender/approval-rekanan';
    $('.rekanan_approve').each(function(){
      if($(this).is(':checked')){
        insert.push({'id': $(this).val()});
        }
    })
  }else if(variable == 'posting_voucher'){
    var _url = '{{ url("/")}}/tender/posting-voucher';
    $('.posting_voucher').each(function(){
      if($(this).is(':checked')){
        insert.push({'id': $(this).val()});
        }
    })
  }
  // var idipk = $('#yes').val();
  var tender_id = $("#tender_id").val();
    $.ajax({
        type : "POST",
        url  : _url,
        dataType : "JSON",
        data :{
          rekanan_:insert,
          tender_id:tender_id
        },
        beforeSend: function() {
          waitingDialog.show();
          },
        success : function(data){
            // alert(data.response);
            location.reload();
        },
        complete: function() {
            waitingDialog.hide();
          }
      });
      return false;
}
$(document).on('click', '#cetak_ba', function() {
  var tender = $("#tender_id").val();
  window.open("/tender/cetakBa?tender="+tender,'_blank');
});

function closeTender(id){
    var request = $.ajax({
        url : "{{ url('/')}}/tender/closeTender",
    dataType : "json",
    data : {
        id : id
    },
    type : "post",
    beforeSend: function() {
      waitingDialog.show();
    },
    success: function(data) { 
        window.location.reload();
        waitingDialog.hide();
    }
    });
}

  $(document).on('click', '.ba_tunjuklangsung', function() {
    var _url = "{{ url('/tender/data_Berita_Acara') }}";
    var beritaacara_id = $("#beritaacara_id").val();
    
    if($("#tender_kelas_id") == 1){
      var label = "Berita Acara Tunjuk Langsung";
    }else{
      var label = "Berita Acara Repeat Order";
    }

    $("#label_ba").text(label.toString());
    $('#editModalBA').modal('show');
  });

  $(document).on('click', '.simpan_ba', function() {
    var _url = "{{ url('/tender/save-ba') }}";
    for (instance in CKEDITOR.instances) {
        CKEDITOR.instances[instance].updateElement();
       }

    var isian = $('#isian1').val();
    var tender_id = $("#tender_id").val();
    var beritaacara_id = $("#beritaacara_id").val();
    $.ajax({
        type: 'post',
        dataType: 'json',
        url: _url,
        data: {
          isian : isian,
          tender_id : tender_id,
          beritaacara_id: beritaacara_id,
        },
        beforeSend: function() {
            waitingDialog.show();
        },
        success: function(data) {
          window.location.reload();
        },
        complete: function() {
            waitingDialog.hide();
        }
    });
  });

  $(document).on('click', '#cetak_batl', function() {
    var _url = "{{ url('/tender/cetakBeritaAcara') }}";
    var beritaacara_id = $("#beritaacara_id").val();
    window.open("/tender/cetakBeritaAcara?ba_id="+beritaacara_id,'_blank');
  });
</script>
@if ( $tender->spks->count() > 0 )
  @if (  $tender->spks->count() > 0 )
  <style type="text/css">
    #dvContents_spk{
      font-size:8px;
    }

    @media print body {
      font-size:8px;
    }

    @media print {
      .result {page-break-after: always;}
    }
  </style>
  <div id="head_Content_spk">
    <div id="dvContents_spk" class="result" style="display: none;">
    </div>
  </div>
  @endif
@endif
</body>
</html>
