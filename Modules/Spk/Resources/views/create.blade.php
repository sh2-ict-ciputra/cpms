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
    <section class="content-header">
      <h1>Data SPK</h1>

    </section>

    <!-- Main content -->
    <section class="content">

      <!-- SELECT2 EXAMPLE -->
      <div class="box box-default">

        <!-- /.box-header -->
        <div class="box-body">
          <div class="row">
            <div class="col-md-6">   

              <h3 class="box-title">Detail Data SPK</h3>      
              <input type="hidden" name="total_termin" id="total_termin" value="{{ $spk->progresses->first()->itempekerjaan->item_progress->count() }}">     
              <form action="{{ url('/')}}/spk/update-date" method="post" name="form1">
              <input type="hidden" name="spk_id" id="spk_id" value="{{ $spk->id }}">
              {{ csrf_field() }}
              <div class="form-group">
                <label>No SPK</label>
                <input type="text" value="{{ $spk->no }}" class="form-control" readonly>
              </div>
              <div class="form-group">
                <label>Nama SPK</label>
                <input type="text" value="{{ $spk->tender->rab->name }}" name="spk_name" id="spk_name" class="form-control">
              </div>    
              <div class="form-group">
                <label>Rekanan</label>
                <input type="text" value="{{ $spk->rekanan->group->name }}" class="form-control" readonly>
              </div>  
              <div class="form-group">
                <label>Jenis SPK</label>
                @php
                  $arrayStatus = array("0" => "Tender", "1" => "Penawaran Langsung")
                @endphp
                <input type="text" class="form-control" name="workorder_durasi" value="{{ $arrayStatus[$spk->is_instruksilangsung]}}" readonly>
              </div>  
              <div class="form-group">
                <label>COA PPh : <strong>{{ $spk->coa_pph_default_id }} %</strong></label>
                <select class='form-control' name='coa_pph' id='coa_pph' class="form-control">
                    @foreach($pph_rekanan as $key => $value3)
                      @if ( $value3->id ==  $spk->pph_rekanan_id )
                        <option value="{{ $value3->id }}" selected>{{ $value3->kode }} &emsp; {{ $value3->name }} ({{ $value3->nilai }}%)</option>
                      @else
                        <option value="{{ $value3->id }}">{{ $value3->kode }} &emsp;   {{ $value3->name }} ({{ $value3->nilai }}%)</option>
                      @endif
                    @endforeach
                </select>
              </div> 
              <div class="form-group">
                <label>Partner</label><br>
                  @if ($spk->with_partner == 0)
                    <label><input type="radio" name="partner" class="flat-red partner" value="0" checked>Tanpa Partner</label>
                  @else
                    <label><input type="radio" name="partner" class="flat-red partner" value="0">Tanpa Partner</label>
                  @endif

                  @if ($spk->with_partner == 1)
                    <label><input type="radio" name="partner" class="flat-red partner" value="1" checked>Dengan  Partner</label>
                  @else
                    <label><input type="radio" name="partner" class="flat-red partner" value="1">Dengan  Partner</label>
                  @endif
                  <br>
                  <button type="button" class="btn btn-primary" onClick="setPartner()">Simpan Partner</button>
              </div>

              @if ( $spk->approval != "" )
                @if ( $spk->approval->approval_action_id == 6 || $spk->approval->approval_action_id == 1)
                    {{-- @if ( $spk->pic_id == NULL ) --}}
                      <div class="form-group">
                        <span id="loading_pic" style="display: none;"><strong>Loading...</strong></span>
                        <label>PIC</label>
                          <select class="form-control" name="pic_id" id="pic_id">
                            @foreach ( $user_pic as $key => $value )
                              <option value="{{ $value['user_id']}}">{{ $value['user_name'] }}</option>
                            @endforeach
                          </select><br>
                        <button type="button" class="btn btn-primary" onClick="setPic()">Simpan PIC</button>
                      </div>    
                    {{-- @endif    --}}
                @endif
              @endif
              
              <!-- /.form-group -->
            </div>
            <!-- /.col -->
            @if(count($spk->perpanjangan) == 0)
              <div class="col-md-6">
                <h3>&nbsp;</h3>
                <div class="form-group">
                  <label>Start Date</label> 
                  <input type="hidden" id="durasi" name="durasi" value="{{ $spk->tender->durasi }}">
                  <input type="text" class="form-control" name="start_date" id="start_date" value="{{ date('d/M/Y',strtotime($spk->start_date)) }}" autocomplete="off" required >
                </div> 
                <div class="form-group">
                  <label>End Date ( Rencana Durasi : <strong>{{ $spk->tender->durasi}}</strong> Hari Kalender )</label>
                  <input type="text" class="form-control" name="end_date" id="end_date" value="@if ( $spk->finish_date != null ) {{ date('d/M/Y',strtotime($spk->finish_date)) }}  @endif" autocomplete="off" disabled>
                </div> 
                
                <div class="form-group">
                  <label>Serah Terima 1</label>
                  <input type="text" class="form-control" name="st_1" id="st_1"  value="@if ( $spk->st_1 != null ) {{ date('d/M/Y',strtotime($spk->st_1)) }}  @endif" autocomplete="off" disabled>
                </div> 
                @if ( count($spk->retensis) > 0 )
                <div class="form-group">
                  <label>Serah Terima 2</label>
                  <input type="text" class="form-control" name="st_2" id="st_2" value="@if ( $spk->st_2 != null ) {{ date('d/M/Y',strtotime($spk->st_2)) }}  @endif" autocomplete="off" disabled>
                </div> 
                <div class="form-group">
                  <label>Serah Terima 3</label>
                  <input type="text" class="form-control" name="st_3" id="st_3" value="@if ( $spk->st_3 != null ) {{ date('d/M/Y',strtotime($spk->st_3)) }}  @endif" disabled>
                </div> 
                @else
                <div class="form-group">
                  <h4><span style="color:red;">Tidak ada Rentensi</span></h4>
                </div>
                @endif
              </div>
            @else
              @if(count($spk->perpanjangan) == 0 ||  $spk->perpanjangan->last()->tanggal_disetujui == '')
                <div class="col-md-6">
                  <h3>&nbsp;</h3>
                  <div class="form-group">
                    <label>Start Date</label> 
                    <input type="hidden" id="durasi" name="durasi" value="{{ $spk->tender->durasi }}">
                    <input type="text" class="form-control" name="start_date" id="start_date" value="{{ date('d/M/Y',strtotime($spk->start_date)) }}" autocomplete="off" required >
                  </div> 
                  <div class="form-group">
                    <label>End Date ( Rencana Durasi : <strong>{{ $spk->tender->durasi}}</strong> Hari Kalender )</label>
                    <input type="text" class="form-control" name="end_date" id="end_date" value="@if ( $spk->finish_date != null ) {{ date('d/M/Y',strtotime($spk->finish_date)) }}  @endif" autocomplete="off" disabled>
                  </div> 
                  
                  <div class="form-group">
                    <label>Serah Terima 1</label>
                    <input type="text" class="form-control" name="st_1" id="st_1"  value="@if ( $spk->st_1 != null ) {{ date('d/M/Y',strtotime($spk->st_1)) }}  @endif" autocomplete="off" disabled>
                  </div> 
                  @if ( count($spk->retensis) > 0 )
                  <div class="form-group">
                    <label>Serah Terima 2</label>
                    <input type="text" class="form-control" name="st_2" id="st_2" value="@if ( $spk->st_2 != null ) {{ date('d/M/Y',strtotime($spk->st_2)) }}  @endif" autocomplete="off" disabled>
                  </div> 
                  <div class="form-group">
                    <label>Serah Terima 3</label>
                    <input type="text" class="form-control" name="st_3" id="st_3" value="@if ( $spk->st_3 != null ) {{ date('d/M/Y',strtotime($spk->st_3)) }}  @endif" disabled>
                  </div> 
                  @else
                  <div class="form-group">
                    <h4><span style="color:red;">Tidak ada Rentensi</span></h4>
                  </div>
                  @endif
                </div>
              @else
                @php $tanggal_perpanjangan = $spk->perpanjangan->where('tanggal_disetujui','!=',null)->last(); @endphp
                <div class="col-md-6">
                  <h3>&nbsp;</h3>
                  <div class="form-group">
                    <label>Start Date (Perpanjangan)</label> 
                    <input type="hidden" id="durasi" name="durasi" value="{{ $spk->tender->durasi }}">
                    <input type="text" class="form-control" name="" id="" value="{{ date('d/M/Y',strtotime($spk->start_date)) }}" autocomplete="off" disabled>
                  </div> 
                  <div class="form-group">
                    <label>End Date ( Rencana Durasi : <strong>{{ (strtotime($tanggal_perpanjangan->tanggal_disetujui) - strtotime($spk->start_date)) / (60 * 60 * 24)}}</strong> Hari Kalender )</label>
                    <input type="text" class="form-control" name="end_date" id="end_date" value="{{ date('d/M/Y', strtotime($tanggal_perpanjangan->tanggal_disetujui)) }}" autocomplete="off" disabled>
                  </div> 
                  
                  <div class="form-group">
                    <label>Serah Terima 1</label>
                    <input type="text" class="form-control" name="st_1" id="st_1"  value="{{ date('d/M/Y', strtotime($tanggal_perpanjangan->tanggal_disetujui)) }}" autocomplete="off" disabled>
                  </div> 
                  @if ( count($spk->retensis) > 0 )
                  
                    @php $st_1 = date('Y-m-d', strtotime($tanggal_perpanjangan->tanggal_disetujui)); @endphp
                    @foreach ($spk->retensis as $key => $value) 
                      @if ( $key == 0 )
                          <div class="form-group">
                            <label>Serah Terima 2</label>
                            <input type="text" class="form-control" name="st_2" id="st_2" value="{{date('d/M/Y', strtotime('+'.$value->hari.' day', strtotime($st_1)))}}" autocomplete="off" disabled>
                          </div>   
                      @endif     

                      @if( $key > 0 )
                          <div class="form-group">
                            <label>Serah Terima 3</label>
                            <input type="text" class="form-control" name="st_3" id="st_3" value="{{date('d/M/Y', strtotime('+'.$value->hari.' day', strtotime($st_1)))}}" disabled>
                          </div> 
                      @endif
                    @endforeach
                  @else
                    <div class="form-group">
                      <h4><span style="color:red;">Tidak ada Rentensi</span></h4>
                    </div>
                  @endif
                </div>
              @endif
            @endif
            <div class="col-md-12">
              <div class="box-footer">
                <a class="btn btn-warning" href="{{ url('/')}}/spk/">Kembali</a>
              @if ( $spk->approval != "" )
                <!-- <button class="btn btn-success" onClick="printtender();" type="button" >Cetak Laporan Tender</button> -->
                <button class="btn btn-success" type="button" onClick="printsipp();">Cetak SIPP</button> 
                @if ( $spk->approval->approval_action_id == 6 || $spk->approval->approval_action_id == 1)
                    <button class="btn btn-info" type="button" id="cetak_spk">Cetak SPK</button>
                @endif
                @if ($spk->spk_id == null)
                  <button type="button" class="btn btn-info" onclick="approval('{{ $spk->id }}');">Kirim Erems</button>
                @endif
              @endif
              {{-- {{$project->id}} --}}
                @if ( $spk->rekanan->group->supps->count() > 0 )
                  <a class="btn bg-purple" href="{{ url('/')}}/spk/supp/show?id={{$spk->id}}">SUPP</a><br/><br/>
                  {{-- <button type="submit" class="btn btn-primary">Simpan</button>xx --}}
                  {{-- <button type="submit" class="btn btn-primary">Simpan</button> --}}
                  @if ( $spk->approval == "" )
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    @if($spk->st_1 != "")
                      <button type="button" class="btn btn-info" onclick="approval('{{ $spk->id }}');">Siap Cetak SPK</button>
                    @endif
                  @else
                    @php
                      $array = array (
                        "6" => array("label" => "Disetujui", "class" => "label label-success"),
                        "7" => array("label" => "Ditolak", "class" => "label label-danger"),
                        "1" => array("label" => "Dalam Proses", "class" => "label label-warning"),
                        "" => array("label" => "","class" => "")
                      )
                    @endphp
                    <a href="{{ url('/')}}/tender/detail?id={{$spk->tender->id}}" class="btn btn-success" target="_blank">Tender</a> <br>
                    <br>
                    Status : <span class="{{ $array[6]['class'] }}">{{ $array[6]['label'] }}</span> <br> <br>
                  @endif
                  
                  @if ( $spk->approval != "" )
                  <!-- <a href="{{ url('/')}}/spk/tender/approval_history?id={{ $spk->tender->id }} class="btn btn-success">Approval History</a> -->
                                    
                  @php
                    $array = array (
                      "6" => array("label" => "Disetujui", "class" => "label label-success"),
                      "7" => array("label" => "Ditolak", "class" => "label label-danger"),
                      "1" => array("label" => "Dalam Proses", "class" => "label label-warning"),
                      "" => array("label" => "","class" => "")
                    )
                  @endphp

                  
                  @if ( count($spk->termyn) <=0 )
                  <h3 style="color:red"><strong>SPK ini belum memiliki bobot. Silahkan isi di kolom Progress Lapangan</strong></h3>
                  @endif
                  
                  @endif
                  <table  class="table borderless" style="margin-bottom:0; font-size: 15px; border: none;"  >
                    <tr>
                      <td style="width:15%;text-align: left;border-top:none;">
                          Nilai
                      </td>
                      <td style="width:3%;border-top:none;">:</td>
                      <td style="width:50%;text-align: left;border-top:none;">
                          Rp. {{ number_format($spk->nilai)}}
                      </td>
                    </tr>
                    <tr>
                      <td style="width:15%;text-align: left;border-top:none;">
                        Nilai VO Kumulatif
                      </td>
                      <td style="width:3%;border-top:none;">:</td>
                      <td style="width:50%;text-align: left;border-top:none;">
                          Rp. {{ number_format($spk->nilai_vo)}}
                      </td>
                    </tr>
                    <tr>
                      <td style="width:15%;text-align: left;border-top:none;">
                          Nilai Percepatan
                      </td>
                      <td style="width:3%;border-top:none;">:</td>
                      <td style="width:50%;text-align: left;border-top:none;">
                          Rp. {{ number_format($spk->nilai_percepatan)}}
                      </td>
                    </tr>
                    <tr>
                      <td style="width:15%;text-align: left;border-top:none;">
                          Nilai SPK (Excl. PPN)
                      </td>
                      <td style="width:3%;border-top:none;">:</td>
                      <td style="width:50%;text-align: left;border-top:none;">
                          Rp. {{ number_format($spk->nilai_vo + $spk->nilai + $spk->nilai_percepatan) }}
                      </td>
                    </tr>
                    <tr>
                      <td style="width:15%;text-align: left;border-top:none;">
                          PIC
                      </td>
                      <td style="width:3%;border-top:none;">:</td>
                      <td style="width:50%;text-align: left;border-top:none;">
                          <strong>{{ $spk->user_pic->user_name or '' }}</strong>
                      </td>
                    </tr>
                    <tr>
                      <td style="width:15%;text-align: left;border-top:none;">
                          Prog Lap s/d saat ini (Pembayaran)
                      </td>
                      <td style="width:3%;border-top:none;">:</td>
                      <td style="width:50%;text-align: left;border-top:none;">
                          <strong>{{ $spk->lapangan }}% ( {{$spk->progress_sebelumnya_cair}}%)</strong>
                      </td>
                    </tr>
                  </table>
                  <!-- <h2>Nilai  : Rp. {{ number_format($spk->nilai)}}</h2>
                  <h2>Nilai VO : Rp. {{ number_format($spk->nilai_vo)}}</h2>
                  <h2>Nilai SPK (Excl. PPN) : Rp. {{ number_format($spk->nilai_vo + $spk->nilai) }}
                  <h2>PIC : <strong>{{ $spk->user_pic->user_name or '' }}</strong></h2> -->
                @else
                <h3 style="color:red;"><strong>Rekanan ini belum memiliki SUPP.<br> Silahkan isi form SUPP menggunakan <a href="{{ url('/')}}/spk/supp/?id={{$spk->id}}" class="btn btn-info">Tombol ini.</a></strong></h3>
                @endif
              </div>
            </div>
            </form>
            <!-- /.col -->

                <div class="col-md-12">
                  @if ( $spk->rekanan->group->supps->count() > 0 )
                  <div class="nav-tabs-custom"> 
                    <ul class="nav nav-tabs">                      
                      <!-- <li class="active"><a href="#tab_7" data-toggle="tab">1. Data DP</a></li> -->
                      <li ><a href="#tab_8" data-toggle="tab" >Retensi</a></li><!-- 
                      <li><a href="#tab_1" data-toggle="tab">Data Pembayaran</a></li> -->
                      <li class="active"><a href="#tab_2" data-toggle="tab">Item Pekerjaan</a></li>
                      <li><a href="#tab_3" data-toggle="tab">Unit</a></li>            
                      <li><a href="#tab_4" data-toggle="tab">Termin Pembayaran</a></li>
                      <li><a href="#tab_5" data-toggle="tab">SIK - VO</a></li>
                      <li><a href="#tab_9" data-toggle="tab">Percepatan SPK</a></li>
                      <li><a href="#tab_6" data-toggle="tab">BAP</a></li>
                    </ul>
                    <div class="tab-content"> 
                      <div class="tab-pane" id="tab_8">
                        <div class="row">
                          <div class="col-md-12">  

                            <form action="{{ url('/')}}/spk/save-retensi" method="post" name="form1">
                              <input type="hidden" class="form-control" name="spk_id" value="{{ $spk->id }}">
                              {{ csrf_field() }}
                              @if ( count($spk->retensis) <= 0 )
                                <div class="form-group"> 
                                  <label>Retensi Persen</label>
                                  <input type="text" class="form-control" name="retensi" autocomplete="off">
                                </div>
                                <div class="form-group"> 
                                  <label>Hari</label>
                                  <input type="text" class="form-control" name="hari" autocomplete="off">
                                </div>
                                <div class="form-group"> 
                                  <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                              @else
                                @if ( $spk->retensis->sum("percent") < "0.05")
                                  <div class="form-group"> 
                                    <label>Retensi Persen</label>
                                    <input type="text" class="form-control" name="retensi" autocomplete="off">
                                  </div>
                                  <div class="form-group"> 
                                    <label>Hari</label>
                                    <input type="text" class="form-control" name="hari" autocomplete="off">
                                  </div>
                                  <div class="form-group"> 
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                  </div>
                                @endif
                              @endif

                             
                            </form>

                            <table class="table table-bordered">
                              <thead class="head_table">
                                <tr>
                                  <td>Retensi</td>
                                  <td>Hari</td>
                                  <td>Hapus</td>
                                </tr>
                              </thead>
                              <tbody>
                                @foreach($spk->retensis as $key => $value )
                                <tr>
                                  <td>{{ round($value->percent * 100, 2) }} %</td>
                                  <td>{{ $value->hari }}</td>
                                  <td>
                                    @if ( $spk->approval == "" )
                                    <button type="button" class="btn btn-danger" onclick="removeRetensi('{{ $value->id }}')">Hapus</button>
                                    @else
                                      @if ( $spk->approval->approval_action_id != "6")
                                        <button type="button" class="btn btn-danger" onclick="removeRetensi('{{ $value->id }}')">Hapus</button>
                                      @endif
                                    @endif
                                  </td>
                                </tr>
                                @endforeach
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>

                      <div class="tab-pane " id="tab_7">
                        <div class="row">
                          <div class="col-md-12">                        
                            <h4><strong>Type DP : {{ $spk->type->description or '' }}</strong></h4>
                            <h4><strong>DP      : Rp. {{ number_format(( $spk->dp_percent / 100 ) * ($spk->nilai + $spk->nilai_vo )) }}</strong></h4>


                        @if ( $spk->approval != "" )
                          @if ( $spk->approval->approval_action_id == "7")
                            
                            
                            @if ( $spk->spk_type_id == "1")
                              @if ( $spk->dp_percent != "" )
                              <h3>DP : {{ number_format(($spk->dp_percent * $spk->nilai)/100,2 )}}</h3>
                              @if ( count($spk->dp_pengembalians) <= 0 )
                              <div class="form-group">
                                  <label>Jumlah Periode Pengembalian DP</label>
                                  <input type="number" value="" name="dp_termin" id="dp_termin" class="form-control" max="4"> 
                                  <button type="button" class="btn btn-info" onClick="generatedptermin();">Generate</button>
                              </div>  
                              @endif
                              @endif
                            @else
                            Minimum Progress : {{ $spk->min_progress_dp or '0'}} %
                            <form action="{{ url('/')}}/spk/minprogress" method="post" name="form1">
                              {{ csrf_field()}}
                              <input type="hidden" name="spk_id" value="{{ $spk->id }}">
                              <div class="form-group">
                                <label>Minimum Progress</label>
                                <input type="text" class="form-control" name="min_progress_dp" autocomplete="off">
                              </div>
                              <div class="form-group">
                                 <button type="submit" class="btn btn-info" onClick="generatedptermin();">Simpan</button>
                              </div>
                            </form>
                            @endif
                            
                            <form action="{{url('/')}}/spk/save-dp" method="post">
                              {{ csrf_field() }}
                              <input type="hidden" name="spk_id_dp" id="spk_id_dp" value="{{ $spk->id }}"> 
                              <div id="form1">
                              </div>
                            </form>
                            <span id="total_dp_percent"></span> %<br>
                          @endif
                        @else
                            
                            
                            @if ( $spk->spk_type_id == "1")
                              @if ( $spk->dp_percent != "" )
                              <h3>DP : {{ number_format(($spk->dp_percent * $spk->nilai)/100,2 )}}</h3>
                              @if ( count($spk->dp_pengembalians) <= 0 )
                              <div class="form-group">
                                  <label>Jumlah Periode Pengembalian DP</label>
                                  <input type="number" value="" name="dp_termin" id="dp_termin" class="form-control" max="4"> 
                                  <button type="button" class="btn btn-info" onClick="generatedptermin();">Generate</button>
                              </div>  
                              @endif
                              @endif
                            @else
                            Minimum Progress : {{ $spk->min_progress_dp or '0'}} %
                            <form action="{{ url('/')}}/spk/minprogress" method="post" name="form1">
                              {{ csrf_field()}}
                              <input type="hidden" name="spk_id" value="{{ $spk->id }}">
                              <div class="form-group">
                                <label>Minimum Progress</label>
                                <input type="text" class="form-control" name="min_progress_dp" autocomplete="off">
                              </div>
                              <div class="form-group">
                                 <button type="submit" class="btn btn-info" onClick="generatedptermin();">Simpan</button>
                              </div>
                            </form>
                            @endif
                            
                            <form action="{{url('/')}}/spk/save-dp" method="post">
                              {{ csrf_field() }}
                              <input type="hidden" name="spk_id_dp" id="spk_id_dp" value="{{ $spk->id }}"> 
                              <div id="form1">
                              </div>
                            </form>
                            <span id="total_dp_percent"></span> %<br>
                        @endif
                            <table class="table table-bordered">
                              <thead class="head_table">
                                <tr>
                                  <td>Periode</td>
                                  <td>Pembayaran ke </td>
                                  <td>Percentage (%)</td>
                                  <td>Percentage Kumulatif(%)</td>
                                  <td>Subtotal(Rp)</td>
                                  <td>Subtotal Kumulatif(Rp)</td>
                                </tr>
                              </thead>
                              <tbody>
                                @php $percent_kumulatif = 0; $total_kumulatif = 0 ; @endphp
                                @foreach ( $spk->dp_pengembalians as $key8 => $value8 )
                                @php
                                  $percent_kumulatif = $percent_kumulatif + $value8->percent;
                                  $total_kumulatif   =  (( $percent_kumulatif / 100 ) * ( ( $spk->dp_percent / 100 ) * $spk->nilai)) ;
                                @endphp
                                <tr>
                                  <td>{{ $key8 + 1 }}</td>
                                  <td>{{ $key8 + 2 }}</td>
                                  <td>{{ $value8->percent }}</td>
                                  <td>{{ number_format($percent_kumulatif,2) }} %</td>
                                  <td>{{ number_format($value8->percent * (($spk->dp_percent * $spk->nilai) / 100) / 100 ,2) }}</td>
                                  <td>{{ number_format($total_kumulatif,2) }}</td>
                                </tr>
                                @endforeach
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div> 
                      <div class="tab-pane" id="tab_1">
                        <div class="row">
                          <div class="col-md-6">   
                            <h3 class="box-title">Detail Data Pembayaran</h3>           
                              <form action="{{ url('/')}}/spk/update-payment" method="post" name="form1">
                                <input type="hidden" name="spk_id" value="{{ $spk->id }}">
                                {{ csrf_field() }}
                                
                                <div class="form-group">
                                  <label>Denda A(Rp)</label>
                                  <input type="text" value="{{ $spk->denda_a }}" name="denda_a" id="denda_a" class="form-control" autocomplete="off">
                                </div>    
                                <div class="form-group">
                                  <label>Denda B(Rp)</label>
                                  <input type="text" value="{{ $spk->denda_b }}" name="denda_b" id="denda_b" class="form-control" autocomplete="off">
                                </div>  
                                <div class="form-group">
                                  <label>Mata Uang</label>
                                  <input type="text" value="{{ $spk->matauang }}" name="matauang" id="matauang" class="form-control" autocomplete="off">
                                </div>                              
                                <div class="form-group">
                                  <label>Nilai Tukar</label>
                                  <input type="text" value="{{ $spk->nilai_tukar }}" name="nilai_tukar" id="nilai_tukar" class="form-control" autocomplete="off">
                                </div>
                                <div class="form-group">
                                  <label>Jenis Kontrak</label>
                                  <select class='form-control' name='jenis_kontrak' id='jenis_kontrak' class="form-control">
                                    <option value='FIXED PRICE & LUMPSUM'>FIXED PRICE & LUMPSUM</option>
                                    <option value='FIXED PRICE'>FIXED PRICE</option>
                                    <option value='LUMPSUM'>LUMPSUM</option>
                                    <option value='REMEASURE'>REMEASURE</option>
                                  </select>
                                </div>

                                <div class="form-group">
                                  <label>Memo Cara Bayar</label>
                                  <input type="text" value="{{ $spk->memo_cara_bayar }}" name="memo_cara_bayar" class="form-control" autocomplete="off">
                                </div> 
                                <div class="form-group">
                                  <label>Cara Pembayaran</label>
                                  <input type="text" value="{{ $spk->carapembayaran }}" name="carapembayaran" class="form-control" autocomplete="off">
                                </div>                             
                                <div class="form-group">
                                  <label>Memo Lingkup Kerja</label>
                                  <input type="text" value="{{ $spk->memo_lingkup_kerja }}" name="memo_lingkup_kerja" class="form-control" autocomplete="off">
                                </div>
                                <div class="form-group">
                                  <label>Nilai Garansi</label>
                                  <input type="text" value="{{ $spk->garansi_nilai }}" name="garansi_nilai" class="form-control" autocomplete="off">
                                </div>
                                <div class="box-footer">
                                  @if ( $spk->approval == "" )
                                    <button type="submit" class="btn btn-primary">Simpan</button>                               
                                  
                                  @endif
                                </div>
                              </form>
                              <!-- /.form-group -->
                          </div>
                        </div>
                      </div>
                      <div class="tab-pane active" id="tab_2">
                        <table class="table table-bordered">
                          <thead class="head_table" style="text-align:center">
                            <tr>
                              <td>COA</td>
                              <td>Item Pekerjaan</td>
                              <td>Volume</td>
                              <td>Satuan</td>
                              <td>Harga Satuan</td>
                              <td>Subtotal</td>
                              <td>Aksi</td>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach ( $spk->tender_rekanan->menangs->first()->details as $key2 => $value2 )
                              @if($value2->volume != 0)
                                @php 
                                  $itempekerjaan = \Modules\Pekerjaan\Entities\Itempekerjaan::find($value2->itempekerjaan_id);
                                  $rab = \Modules\Rab\Entities\RabPekerjaan::where("itempekerjaan_id",$itempekerjaan->id)->first();
                                @endphp
                                {{-- {{$value2}} --}}
                                <tr>
                                  <td><strong>{{ $itempekerjaan->code }}</strong></td>
                                  <td><strong>{{ $itempekerjaan->name }}</strong></td>
                                  <td style="text-align:right"><strong>{{ (float)$value2->volume}}</strong></td>
                                  <td><strong>{{ $value2->satuan }}</strong></td>
                                  <td style="text-align:right"><strong>{{ number_format($value2->nilai) }}</strong></td>
                                  <td style="text-align:right"><strong>{{ number_format($value2->total_nilai) }}</strong></td>
                                  <td>
                                    @if ($value2->volume !=0)
                                      @if(count($spk->progress_tambahan->where("itempekerjaan_id", $itempekerjaan->id)) != 0)
                                        <a href="{{ url('/')}}/spk/kelola-ipk?id={{ $itempekerjaan->id }}&id_spk={{$spk->id}}" class="btn btn-warning">IPK</a>
                                      @else
                                        <a class="btn btn-warning" disabled>IPK</a>
                                      @endif
                                      <a href="{{ url('/')}}/spk/progress?id={{ $itempekerjaan->id }}&id_spk={{$spk->id}}" class="btn btn-info">Tahapan</a>
                                    @endif
                                  </td>
                                </tr>
                                @foreach($value2->tender_menang_sub_detail as $key3 => $value3 )
                                    <tr>
                                        <td></td>
                                        <td>{{$value3->name}}</td>
                                        <td style="text-align:right">{{bcdiv($value3->volume ,1 ,2)}}</td>
                                        <td >{{$value3->satuan}}</td>
                                        <td style="text-align:right">{{number_format($value3->nilai)}}</td>
                                        <td style="text-align:right">{{number_format($value3->total_nilai)}}</td>
                                        <td></td>
                                    </tr>
                                @endforeach
                              @endif
                            @endforeach
                          </tbody>
                        </table>
                      </div>

                      <div class="tab-pane" id="tab_3">
                        <table class="table table-bordered">
                          <thead class="head_table">
                            <tr>
                              <td>Unit Name</td>
                              <td>Type</td>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach ( $spk->details as $key2 => $value2 )
                              @if ( $value2->asset != "" )                    
                                <tr>
                                  <td>{{ $value2->asset->name }}</td>
                                  <td>{{ $value2->asset_type}}</td>
                                </tr>
                              @else
                                <tr>
                                  <td>{{ $value2->spk->project->name }}</td>
                                  <td>{{ $value2->spk->asset->type->name or ''}}</td>
                                </tr>
                              @endif
                            @endforeach
                          </tbody>
                        </table>
                      </div>

                      <div class="tab-pane" id="tab_4">
                        <form action="{{ url('/')}}/spk/save-termynbayar" method="post">
                          {{ csrf_field() }}
                          <input type="hidden" name="idspk" value="{{$spk->id}}">
                           @if ( count($spk->termyn) > 0 )
                           <table class="table table-bordered">
                             <thead class="head_table">
                               <tr>
                                  <td>Termin</td>
                                  <td>Persen Bayar(%)</td>
                                  <td>Tanggal Rencana Bayar</td>
                               </tr>
                             </thead>
                             <tbody>
                               @foreach ( $spk->termyn as $key => $value )
                               <tr>
                                <td>
                                  @if ( $key == 0 )
                                    Termin ke 1 (DP)
                                  @else
                                    Termin {{ $key + 1 }}
                                  @endif
                                </td>
                                <td>{{ $value->termin }}</td>
                                <td>     
                                  <input type="hidden" name="" id="tglcount" value="{{count($spk->termyn)}}"> 
                                  <input type="hidden" name="jml[]" value="{{$key}}">
                                  <input type="hidden" name="idtermyn[]" value="{{$value->id}}">
                                  <input type="date" name="tglbayar[]" id="tglbayar[$key]" value="{{$value->tanggal_pembayaran}}">                           
                                </td>
                               </tr>
                               @endforeach
                             </tbody>
                           </table>
                           @endif
                          <center>
                            <button type="submit" class="btn btn-primary" id="btn_submit">Simpan</button>
                          </center>
                        </form>
                      </div>
                      <div class="tab-pane" id="tab_5">
                        {{-- <table class="table table-bordered" id="table_vo" style="width:100%">
                          <thead class="head_table">
                            <tr>
                              <td colspan="1"></td>
                              <td colspan="1"></td>
                              <td colspan="1"></td>
                              <td colspan="2" style="width:30%">No. SIK</td>
                              <td colspan="3" style="width:40%">No. VO</td>
                              <td colspan="3" style="width:30%">nilai VO</td>
                            </tr>
                            <tr>
                              <td>No. Vo</td>
                              <td>No. SIK</td>
                              <td>nilai Vo</td>
                              <td style="width:10%">COA</td>
                              <td style="width:20%">Item Pekerjaan</td>
                              <td style="width:5%">Volume</td>
                              <td style="width:20%">Harga Satuan</td>
                              <td style="width:5%">Satuan</td>
                              <td style="width:20%">Subtotal</td>
                              <td style="width:10%">No. Unit/Kawasan</td>
                              <td style="width:10%">Detail</td>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach ( $spk->sik as $key7 => $value ) 
                              @if($value->status_sik_id == 1 || $value->status_sik_id == 3)
                                @if($value->vo != '')
                                  @php
                                    $value7 = $value->vo;
                                  @endphp
                                  @if($value7->approval->approval_action_id == 6)
                                    @foreach( $value7->detail as $key9 => $value9)
                                      @if($value9->volume != 0 && $value9->volume != '')
                                        <tr>
                                          <td>{{ $value7->sik->no_sik}}</td>
                                          <td>{{ $value7->no }}</td>
                                          <td style="text-align: right;">
                                            @if($value7->sik->status_sik_id == 1)
                                              {{ number_format($value7->nilai)}}
                                            @else
                                            {{ number_format(-1*$value7->nilai)}}
                                            @endif
                                          </td>
                                          <td>{{ $value9->itempekerjaan->code}}</td>
                                          <td>{{ $value9->itempekerjaan->name}}</td>
                                          <td style="text-align: right;">{{ bcdiv((float)$value9->volume, 1, 2)}}</td>
                                          <td style="text-align: right;">{{ number_format($value9->nilai)}}</td>
                                          <td>{{ $value9->satuan}}</td>
                                          <td style="text-align: right;">{{ number_format($value9->volume * $value9->nilai)}}</td>
                                          <td>
                                            @if($value9->unit->rab_unit->asset != '')
                                              {{ $value9->unit->rab_unit->asset->name}}
                                            @else
                                              Fasilitas Kota
                                            @endif
                                          </td>
                                          <td>
                                            <!-- <a href="{{ url('/')}}/spk/sik-show?id={{ $value7->id}}" class="btn btn-warning">Detail</a> -->
                                            <a href="{{ url('/')}}/spk/vo/progress?void={{$value7->id}}&void_detail={{$value9->id}}" class="btn btn-info">Tahapan</a>
                                          </td>
                                        </tr>
                                      @endif
                                    @endforeach
                                  @endif
                                @endif
                              @else
                                <tr>
                                  <td>{{$value->no_sik}}</td>
                                  <td>
                                    <textarea class="form-control" style="width:100%;max-width:100%" rows="7" id="isian1" name='isian' disabled>{{ $value->sik_detail[0]->keterangan }}</textarea>
                                  </td>
                                  <td style="text-align: right;">-</td>
                                  <td>{{ $spk->itempekerjaan->code}}</td>
                                  <td>{{ $spk->itempekerjaan->name}}</td>
                                  <td colspan=""></td>
                                  <td></td>
                                  <td></td>
                                  <td style="text-align: right;"></td>
                                  <td></td>
                                  <td></td>

                                </tr>
                              @endif
                            @endforeach
                          </tbody>
                        </table> --}}

                        <table class="table table-bordered" id="table_vo_baru" style="width:100%">
                          <thead class="head_table">
                            <tr>
                              <td style="width:10%">No. Vo</td>
                              <td style="width:10%">No. SIK</td>
                              <td style="width:10%">COA</td>
                              <td style="width:10%">Item Pekerjaan</td>
                              <td style="width:5%">Volume</td>
                              <td style="width:5%">Satuan</td>
                              <td style="width:10%">Harga Satuan</td>
                              <td style="width:10%">Subtotal</td>
                              <td style="width:10%">No. Unit/Kawasan</td>
                              <td style="width:10%">Detail</td>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach ( $spk->sik as $key7 => $value ) 
                              @if($value->status_sik_id == 1 || $value->status_sik_id == 3)
                                @if($value->vo != '')
                                  @php
                                    $value7 = $value->vo;
                                  @endphp
                                  @if($value7->approval->approval_action_id == 6)
                                    @foreach( $value7->detail as $key9 => $value9)
                                      @if(0 <= $value9->volume && $value9->volume != '')
                                        <tr style="font-weight: bold">
                                          <td style="word-break: break-all;">{{ $value7->sik->no_sik}}</td>
                                          <td style="word-break: break-all;">{{ $value7->no }}</td>
                                          <td>{{ $value9->itempekerjaan->code}}</td>
                                          <td>{{ $value9->itempekerjaan->name}}</td>
                                          <td style="text-align: right;">{{ bcdiv((float)$value9->volume, 1, 2)}}</td>
                                          <td>{{ $value9->satuan}}</td>
                                          <td style="text-align: right;">{{ number_format($value9->nilai)}}</td>
                                          <td style="text-align: right;">{{ number_format($value9->volume * $value9->nilai)}}</td>
                                          <td>
                                            @if($value9->unit->rab_unit->asset != '')
                                              {{ $value9->unit->rab_unit->asset->name}}
                                            @else
                                              Fasilitas Kota
                                            @endif
                                          </td>
                                          <td>
                                            <!-- <a href="{{ url('/')}}/spk/sik-show?id={{ $value7->id}}" class="btn btn-warning">Detail</a> -->
                                            <a href="{{ url('/')}}/spk/vo/progress?void={{$value7->id}}&void_detail={{$value9->id}}" class="btn btn-info">Tahapan</a>
                                          </td>
                                        </tr>
                                        @if (count($value9->sub_detail_vo) != 0)
                                            @foreach($value9->sub_detail_vo as $key2 => $value3 )
                                                <tr class="test_child">
                                                    <td style="width : 10%"></td>
                                                    <td style="width : 10%"></td>
                                                    <td style="width : 10%"></td>
                                                    <td style="width : 10%">
                                                        {{$value3->name}}
                                                    </td>
                                                    <td style="text-align:right;width : 5%">
                                                        {{bcdiv((float)$value3->volume, 1, 2)}}
                                                    </td>
                                                    <td style="width : 5%">
                                                    {{$value3->satuan}}
                                                    </td>
                                                    <td style="width : 9%;text-align:right">
                                                        {{number_format($value3->nilai)}}
                                                    </td>
                                                    <td style="width : 10%;text-align:right">
                                                        {{number_format($value3->total_nilai)}}
                                                    </td>
                                                    <td style="width : 10%"></td>
                                                    <td style="width : 10%"></td>
                                                </tr>
                                            @endforeach
                                        @endif
                                      @endif
                                    @endforeach
                                  @endif
                                @endif
                              @else
                                <tr>
                                  <td>{{$value->no_sik}}</td>
                                  <td>
                                    <textarea class="form-control" style="width:100%;max-width:100%" rows="7" id="isian1" name='isian' disabled>{{ $value->sik_detail[0]->keterangan }}</textarea>
                                  </td>
                                  <td style="text-align: right;">-</td>
                                  <td>{{ $spk->itempekerjaan->code}}</td>
                                  <td>{{ $spk->itempekerjaan->name}}</td>
                                  <td colspan=""></td>
                                  <td></td>
                                  <td></td>
                                  <td style="text-align: right;"></td>
                                  <td></td>
                                  <td></td>

                                </tr>
                              @endif
                            @endforeach
                          </tbody>
                        </table>
                      </div>

                      <div class="tab-pane" id="tab_6">
                        <span>Jenis Pengembalian DP : <strong>
                          @if ($spk->tender->aanwijing->jenis_pembayaran != null)
                            {{ $spk->tender->aanwijing->jenis_pembayaran->name }}
                          @else
                              Counter Progress
                          @endif
                        </strong></span><br/>
                        <span>Total Nilai Progress Sertifikat = <strong>Rp. {{ number_format($spk->baps->sum('nilai_bap_2'))}}</strong></span><br/>
                        <span>Total Nilai Sertifikat = <strong>Rp. {{ number_format($spk->baps->sum('nilai_bap_dibayar'))}}</strong></span><br/>

                        <a href="{{ url('/')}}/spk/add-bap?id={{$spk->id}}" class="btn btn-primary">Tambah BAP</a>
                         <a href="{{ url('/')}}/spk/cetak-termyn?id={{$spk->id}}" target="_blank" class="btn btn-success">Cetak TermynList</a>

  
                        <table class="table-bordered table">
                          <thead class="head_table">
                            <tr>
                              <td>No. BAP</td>
                              <td>Nilai Progress Sertifikat</td>
                              <td>Nilai Sertifikat</td>
                              <td>Dibuat Oleh</td>
                              <td>Tanggal</td>
                              <td>Voucher</td>
                            </tr>
                          </thead>
                          <tbody>
                            @php $before = 0; @endphp
                            @foreach($spk->baps as $key => $value )                      

                            <tr>
                              <td>{{ $value->no }}</td>
                              <td style="text-align: right;">{{ number_format($value->nilai_bap_2,2) }}</td>
                              <td style="text-align: right;">{{ number_format($value->nilai_bap_dibayar,2) }}</td>
                              <td>{{ \App\User::find($value->created_by)->user_name }}</td>
                              <td>{{ $value->created_at }}</td>
                              <td>
                                <a href="{{ url('/')}}/spk/detail-bap?id={{ $value->id }}" class="btn btn-primary">Detail</a>
                                <a href="{{ url('/')}}/spk/cetak-counterprogres?id={{ $value->id }}" target="_blank" class="btn btn-warning" >Cetak BAP</a>                           
                              </td>
                            </tr>
                            @php 
                            $before = $value->nilai_bap_2 ; 
                            @endphp
                            @endforeach
                          </tbody>
                        </table>
                      </div>

                      <div class="tab-pane" id="tab_9">
                      <a href="{{ url('/')}}/spk/percepatan?id={{$spk->id}}" class="btn btn-primary">Buat Percepatan SPK</a>
                        <!-- @if(count($spk->percepatan) != 0)
                          @if($spk->percepatan->last()->approval != '')
                            @if($spk->percepatan->last()->approval->approval_action_id !=  6 )
                              <a href="{{ url('/')}}/spk/percepatan?id={{$spk->id}}" class="btn btn-primary">Buat Percepatan SPK</a>
                            @endif
                          @endif
                        @else
                          <a href="{{ url('/')}}/spk/percepatan?id={{$spk->id}}" class="btn btn-primary">Buat Percepatan SPK</a>
                        @endif -->
                        <table class="table table-bordered" id="table_percepatan" style="width:100%">
                          <thead class="head_table">
                            <tr>
                              <td style="width:10%">no Percepatan SPK</td>
                              <td style="width:20%">besar persen</td>
                              <td style="width:10%">Nilai Rupiah</td>
                              <td style="width:10%">No. Unit\Kawasan</td>
                              <td style="width:10%">tanggal selesai</td>
                              <td style="width:10%">Status</td>
                              <td style="width:20%">Action</td>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach($spk->percepatan as $key => $value)
                              <tr>
                                <td> {{$value->no}} </td>
                                <td> {{$value->nilai_persen}} %</td>
                                <td> {{number_format(($spk->nilai/count($spk->tender->units))*($value->nilai_persen/100)*count($value->percepatan_unit))}} </td>
                                <td>
                                @foreach($value->percepatan_unit as $key2 => $value2)
                                  @if($key2 == 0)
                                    {{$value2->unit->rab_unit->asset->name}}
                                  @else
                                    , {{$value2->unit->rab_unit->asset->name}}
                                  @endif
                                @endforeach
                                </td>
                                <td> {{ date('d/M/Y',strtotime($value->tanggal_finish)) }} </td>
                                <td> 
                                  @if($value->approval == '')
                                    <strong style="color:black"> Belum Request Approval </strong>
                                  @elseif($value->approval->approval_action_id == 1)
                                    <strong style="color:orange"> Dalam Proses </strong>
                                  @elseif($value->approval->approval_action_id == 6)
                                    <strong style="color:green"> Approved </strong>
                                  @elseif($value->approval->approval_action_id == 7)
                                    <strong style="color:red"> Rejected </strong>
                                  @endif
                                </td>
                                <td> 
                                  @if($value->approval == '')
                                      <a class="btn btn-info" href="{{ url('/')}}/spk/request-approval-percepatan?id={{$value->id}}">Request Approval</a>
                                  @endif 
                                </td>
                              </tr>
                            @endforeach
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>                 

                  @endif
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

  
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>

</div>
<!-- ./wrapper -->

<!--Report -->
@if ( $spk->approval != "" )
  @if ( $spk->approval->approval_action_id == 6 || $spk->approval->approval_action_id == 1)
    @include("spk::cetakan_tender")
    @include("spk::cetakan_sipp")
  @endif
@endif

@include("master/footer_table")
@include("spk::app")
<!-- Select2 -->
<script src="https://cdn.ckeditor.com/4.11.3/standard/ckeditor.js"></script>
<script src="{{ url('/')}}/assets/bower_components/select2/dist/js/select2.full.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
  CKEDITOR.replace( 'isian', {
    toolbar: []
} );
});
$('#table_vo').DataTable({
        //   scrollY: "500px",
        //   scrollX:true,
        //   scrollCollapse: true,
          paging: false,
          "columnDefs": [
            { "visible": false, "targets": [0,1,2] }
          ],
          "order": [[ 0, 'asc' ]],
          "drawCallback": function ( settings ) {
            var api = this.api();
            var rows = api.rows( {page:'current'} ).nodes();
            var last=null;
            var last2=null;
            var last3=null;
 
            api.column(0, {page:'current'} ).data().each( function ( group, i ) {
              // api.column(1, {page:'current'} ).data().each( function ( group2, i ) {
              //   api.column(2, {page:'current'} ).data().each( function ( group3, i ) {
                  if ( last !== group ) {
                    var nilai_vo = api.cell({ row: i, column: 2 }).data();
                    var no_sik = api.cell({ row: i, column: 1 }).data();
                        $(rows).eq(i).before(
                            '<tr class="group" style="background-color: white;""><td colspan=2"><strong>'+group+'</strong></td>><td colspan=3"><strong>'+no_sik+'</strong></td><td colspan=1" style="text-align: right;"><strong>'+nilai_vo+'</strong></td><td colspan="><strong></strong></td></tr>'
                        );
                      //   last3 = group3;
                      // last2 = group2;
                    last = group;
                  }
              //   } ); 
              // } );
            } );
        },
		  "initComplete": function(settings, json) {
                $('.group').nextUntil('.group').css( "display", "none" );
              }
    });

	  var tbody = $('#table_vo tbody');
      tbody.on('click','.group',function()
      {
        $(this).nextUntil('.group').toggle();

      });
      // $(document).ready(function(){
      //   CKEDITOR.replace( 'isian1' );
      // });
      $(document).ready(function(){
        // console.log($('#tglcount').val());
        for (var i=1;i<=$('#tglcount').val();i++){
            $("#tglbayar" + i).datepicker({
            minDate: new Date($("#tglbayar").val()),
            dateformat : 'yy/mm/dd'
        });
        }
      })

$(document).on('click', '#cetak_spk', function() {
  var _url = "{{ url('/spk/cetakSpk') }}";
  var supp = $("#spk_id").val();
  window.open("/spk/cetakSpk?spk_id="+supp,'_blank');
});
</script>
</body>
</html>
