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

  @include("master/sidebar_rekanan")

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>Data SPK</h1>

    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
   
            <!-- /.box-header -->
            <div class="box-body">
              <div class="col-md-12">
                <h3 class="box-title">Detail Data SPK</h3>  
                <div class="box-header ">
                <table class="table" style="font-size:18px;font-weight:bold">
                  <thead>
                    <tr>
                      <td>No. SPK</td>
                      <td>:</td>
                      <td>{{$spk->no}}</td>
                    </tr>
                      <tr>
                      <td>Nama Proyek</td>
                      <td>:</td>
                      <td>{{$spk->project->name}} </td>
                    </tr>
                    <tr>
                      <td>Start Date</td>
                      <td>:</td>
                      <td>{{ date('d/M/Y',strtotime($spk->start_date)) }}</td>
                    </tr>
                    <tr>
                      <td>End Date</td>
                      <td>:</td>
                      <td>

                        @if($perpanjanganspk==null)
                          {{ date('d/M/Y',strtotime($spk->finish_date)) }}

                        @elseif($perpanjanganspk!=null)
                          @if($perpanjanganspk->tanggal_disetujui!=null)
                            {{ date('d/M/Y',strtotime($perpanjanganspk->tanggal_disetujui)) }}
                          @else
                            {{ date('d/M/Y',strtotime($spk->finish_date)) }} 
                          @endif
                        @else
                          {{ date('d/M/Y',strtotime($spk->finish_date)) }} 
                        @endif
                      </td>
                    </tr>
                    <tr>
                      <td>Pencapaian progress s/d sekarang</td>
                      <td>:</td>
                      <td>{{ $spk->lapangan }}</td>
                    </tr>
                    <tr>
                  </thead>
                </table>
              </div>
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
                      Nilai VO
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
              </table>
              @if($cekdisetujui == '')
                <a href="{{ url('/')}}/rekanan/spk/perpanjang-spk?id={{$spk->id}}" class="btn btn-primary" style="margin:10px 10px 10px 10px"> Perpanjang</a>
              
              @else
                @if($cekdisetujui->tanggal_disetujui!=null)
                <p class="text-green" style="font-size: 20px;">Permintaan Perpanjangan Sebelumnya di Setujui</p>
                <a href="{{ url('/')}}/rekanan/spk/perpanjang-spk?id={{$spk->id}}" class="btn btn-primary">Perpanjang</a>
                @else
                  @if($cekdisetujui->approval->approval_action_id == 7)
                    <p class="text-red" style="font-size: 20px;">Permintaan Perpanjangan Sebelumnya di Reject</p>
                    <a href="{{ url('/')}}/rekanan/spk/perpanjang-spk?id={{$spk->id}}" class="btn btn-primary">Perpanjang</a>
                  @else
                    <p class="text-yellow" style="font-size: 20px;">Pengajuan Perpanjangan Sedang Diproses</p>
                  @endif
                    <!-- <a href="{{ url('/')}}/rekanan/spk/perpanjang-spk?id={{$spk->id}}" class="btn btn-primary">Perpanjang</a> --> 
                @endif
              @endif
            </div>
              <div class="col-md-12">
                  @if ( $spk->rekanan->group->supps->count() > 0 )
                  <div class="nav-tabs-custom"> 
                    <ul class="nav nav-tabs">                      
                      <li><a href="#tab_8" data-toggle="tab">Retensi</a></li><!-- 
                      <li><a href="#tab_1" data-toggle="tab">Data Pembayaran</a></li> -->
                      <li class="active"><a href="#tab_2" data-toggle="tab">Item Pekerjaan</a></li>
                      <li><a href="#tab_3" data-toggle="tab">Unit</a></li>            
                      <li><a href="#tab_4" data-toggle="tab">Termin Pembayaran</a></li>
                      <li><a href="#tab_5" data-toggle="tab">Variation Order (VO)</a></li>
                    </ul>
                    <div class="tab-content"> 
                      <div class="tab-pane" id="tab_8">
                        <div class="row">
                          <div class="col-md-12">  

                            <table class="table table-bordered">
                              <thead class="head_table">
                                <tr>
                                  <td>Retensi</td>
                                  <td>Hari</td>
                                  <!-- <td>Hapus</td> -->
                                </tr>
                              </thead>
                              <tbody>
                                @foreach($spk->retensis as $key => $value )
                                <tr>
                                  <td>{{ $value->percent * 100 }} %</td>
                                  <td>{{ $value->hari }}</td>
                                  <td>
                                    @if ( $spk->approval == "" )
                                    <!-- <button type="button" class="btn btn-danger" onclick="removeRetensi('{{ $value->id }}')">Hapus</button> -->
                                    @else
                                      @if ( $spk->approval->approval_action_id != "6")
                                        <!-- <button type="button" class="btn btn-danger" onclick="removeRetensi('{{ $value->id }}')">Hapus</button> -->
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
                                    <!-- <button type="submit" class="btn btn-primary">Simpan</button> -->                               
                                  
                                  @endif
                                </div>
                              </form>
                              <!-- /.form-group -->
                          </div>
                        </div>
                      </div>
                      <div class="tab-pane active" id="tab_2">
                        {{-- <a href="{{ url('/')}}/rekanan/spk/pengajuan?id={{$spk->id}}" class="btn btn-warning" style="margin:5px 5px 5px 5px">Pengajuan pengecekan IPK</a> --}}
                        {{-- <table class="table table-bordered">
                          <thead class="head_table">
                            <tr>
                              <!-- <td>COA</td> -->
                              <td>Item Pekerjaan</td>
                              <td>Volume</td>
                              <td>Harga Satuan</td>
                              <td>Satuan</td>
                              <td>Subtotal</td>
                              <!-- <td>Progress</td> -->
                            </tr>
                          </thead>
                          <tbody>
                            @foreach ( $spk->tender_rekanan->menangs->first()->details as $key2 => $value2 )
                              @if($value2->volume != 0)
                                @php 
                                  $itempekerjaan = \Modules\Pekerjaan\Entities\Itempekerjaan::find($value2->itempekerjaan_id);
                                  $rab = \Modules\Rab\Entities\RabPekerjaan::where("itempekerjaan_id",$itempekerjaan->id)->first();
                                @endphp
                                <tr>
                                  <!-- <td>{{ $itempekerjaan->code }}</td> -->
                                  <td>{{ $itempekerjaan->name }}</td>
                                  <td>{{ $value2->volume }}</td>
                                  <td>{{ number_format($value2->nilai) }}</td>
                                  <td>{{ $value2->satuan }}</td>
                                  <td>{{ number_format($value2->volume * $value2->nilai) }}</td>
                                  <!-- <td> 
                                    <a class="btn btn-sm btn-info"  data-toggle="modal" title="Edit" class="modal_progress_detail" onclick="detail('{{$itempekerjaan->id}}','{{$spk->id}}')">Progress Detail</a> 
                                  </td> -->
                                 <!--  <td>
                                    @if ($value2->volume !=0)
                                      <a href="{{ url('/')}}/spk/kelola-ipk?id={{ $itempekerjaan->id }}&id_spk={{$spk->id}}" class="btn btn-warning">IPK</a>
                                      <a href="{{ url('/')}}/spk/progress?id={{ $itempekerjaan->id }}&id_spk={{$spk->id}}" class="btn btn-info">Progress</a>
                                    @endif
                                  </td> -->
                                </tr>
                              @endif
                            @endforeach
                          </tbody>
                        </table> --}}

                        <table class="table table-bordered">
                          <thead class="head_table">
                            <tr>
                              <td>COA</td>
                              <td>Item Pekerjaan</td>
                              <td>Volume</td>
                              <td>Satuan</td>
                              <td>Harga Satuan</td>
                              <td>Total Nilai</td>
                            </tr>
                          </thead>
                            <tbody>
                              @php $total = 0; @endphp
                              @foreach ( $spk->tender_rekanan->menangs->first()->details as $key2 => $value2 )
                                @if($value2->volume != 0)
                                  @php 
                                    $itempekerjaan = \Modules\Pekerjaan\Entities\Itempekerjaan::find($value2->itempekerjaan_id);
                                    $rab = \Modules\Rab\Entities\RabPekerjaan::where("itempekerjaan_id",$itempekerjaan->id)->first();
                                    if($value2->total_nilai == null || $value2->total_nilai == 0){                  
                                      $total += $value2->volume * $value2->nilai;
                                    }else{
                                      $total += $value2->total_nilai;
                                    }
                                  @endphp
                                  {{-- {{$value2}} --}}
                                  <tr>
                                    <td><strong>{{ $itempekerjaan->code }}</strong></td>
                                    <td><strong>{{ $itempekerjaan->name }}</strong></td>
                                    <td style="text-align:right"><strong>{{ $value2->volume }}</strong></td>
                                    <td><strong>{{ $value2->satuan }}</strong></td>
                                    <td style="text-align:right"><strong>{{ number_format($value2->nilai) }}</strong></td>
                                    <td style="text-align:right"><strong>{{ number_format($value2->total_nilai) }}</strong></td>
                                  </tr>
                                  @foreach($value2->tender_menang_sub_detail as $key2 => $value2 )
                                      <tr>
                                          <td></td>
                                          <td>{{$value2->name}}</td>
                                          <td style="text-align:right">{{round($value2->volume, 6)}}</td>
                                          <td >{{$value2->satuan}}</td>
                                          <td style="text-align:right">{{number_format($value2->nilai)}}</td>
                                          <td style="text-align:right">{{number_format($value2->total_nilai)}}</td>
                                      </tr>
                                  @endforeach
                                @endif
                              @endforeach
                            </tbody>
                          <tfoot>
                            <tr>
                              <td colspan="5" style="text-align:right">Subtotal</td>
                              <td style="text-align:right">{{number_format($total)}}</td>
                            </tr>
                            <tr>
                              <td colspan="5" style="text-align:right">PPn</td>
                              @php 
                                $ppn = \Modules\Globalsetting\Entities\Globalsetting::where('id',9)->first()->value;
                              @endphp
                              @if($spk->ppn == 1)
                                <td style="text-align:right">{{number_format($nilai_ppn = $total * $ppn/100)}}</td>
                              @else
                                <td style="text-align:right">{{number_format($nilai_ppn = 0)}}</td>
                              @endif
                            </tr>
                            <tr>
                              <td colspan="5" style="text-align:right">Grand Total</td>
                              <td style="text-align:right">{{number_format($total + $nilai_ppn)}}</td>
                            </tr>
                          </tfoot>
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
                               </tr>
                               @endforeach
                             </tbody>
                           </table>
                           @endif
                        <!--   <center>
                            <button type="submit" class="btn btn-primary" id="btn_submit">Simpan</button>
                          </center> -->
                        </form>
                      </div>
                      <div class="tab-pane" id="tab_5">
                      <table class="table table-bordered" id="table_vo" style="width:100%">
                          <thead class="head_table">
                            <tr>
                              <td colspan="1"></td>
                              <td colspan="1"></td>
                              <td colspan="1"></td>
                              <td colspan="1" style="width:30%">No. SIK</td>
                              <td colspan="3" style="width:40%">No. VO</td>
                              <td colspan="3" style="width:30%">nilai VO</td>
                            </tr>
                            <tr>
                              <td>No. Vo</td>
                              <td>No. SIK</td>
                              <td>nilai Vo</td>
                              <!-- <td style="width:10%">COA</td> -->
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
                                          <!-- <td>{{ $value9->itempekerjaan->code}}</td> -->
                                          <td>{{ $value9->itempekerjaan->name}}</td>
                                          <td style="text-align: right;">{{ $value9->volume}}</td>
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
                                            <a href="{{ url('/')}}/spk/vo/progress?void={{$value7->id}}&void_detail={{$value9->id}}" class="btn btn-info">Progress</a>
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
                                  <!-- <td>{{ $spk->itempekerjaan->code}}</td> -->
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
 
                    </div>
                  </div>                 

                  @endif
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

    <!-- MODAL EDIT -->
    <div class="modal fade" id="ModalProgressDetail" tabindex="-1" role="dialog" aria-labelledby="largeModal" aria-hidden="true">
        <div style="width: 1200px" class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3 class="modal-title" id="myModalLabel"> <span style="color: grey " id="namekaw"></span></h3>
        </div>
        <form class="form-horizontal" >
            <div class="modal-body">
              <div class="tab-pane table-responsive" id="tab_2">
                <table id="index_detail" class="table table-bordered bg-white mg-b-0 tx-center" style="font-size:15px; width: 100%; ">
                  <thead class="head_table">
                    <tr style="border: 1px solid black;">
                        <td rowspan="" style="vertical-align: middle;">No</td>
                        <td rowspan="" style="vertical-align: middle;">nama</td>
                        <td rowspan="" style="vertical-align: middle;">volume</td>
                        <td rowspan="" style="vertical-align: middle;">satuan</td>
                        <td rowspan="" style="vertical-align: middle;">IPK</td>
                        <td rowspan="" style="vertical-align: middle;">status</td>
                    </tr>
                  </thead>
                </table>
              </div>   
            </div>

            <div class="modal-footer">
                <!-- <button class="btn" data-dismiss="modal" aria-hidden="true">Tutup</button>
                <button class="btn btn-info" id="btn_update">Update</button> -->
            </div>
        </form>
        </div>
        </div>
    </div>
    <!--END MODAL EDIT-->

  <!-- /.content-wrapper -->
  @include("master/copyright")
  
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

@include("master/footer_table")
@include("rekanan::user.app")
<!-- Select2 -->
<script src="https://cdn.ckeditor.com/4.11.3/standard/ckeditor.js"></script>
<script src="{{ url('/')}}/assets/bower_components/select2/dist/js/select2.full.min.js"></script>
<script>
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
                            '<tr class="group" style="background-color: white;""><td colspan=1"><strong>'+group+'</strong></td>><td colspan=3"><strong>'+no_sik+'</strong></td><td colspan=1" style="text-align: right;"><strong>'+nilai_vo+'</strong></td><td colspan="><strong></strong></td></tr>'
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
      $(document).ready(function(){
        // console.log($('#tglcount').val());
        for (var i=1;i<=$('#tglcount').val();i++){
            $("#tglbayar" + i).datepicker({
            minDate: new Date($("#tglbayar").val()),
            dateformat : 'yy/mm/dd'
        });
        }
      })

      // function detail(id_pekerjaan, id_spk){
      //   var url = "{{ url('/')}}/rekanan/user/progress";
      //   $('#index_detail').DataTable().clear().draw();
      //   $.ajax({
      //       type: 'post',
      //       dataType: 'json',
      //       url: url,
      //       data: {
      //           id_pekerjaan : id_pekerjaan,
      //           id_spk : id_spk
      //       },
      //       beforeSend: function() {
      //           waitingDialog.show();
      //       },
      //       success: function(data) {
      //           if (data.data.length > 0) {
      //             console.log(data);
      //               $(data.data).each(function(i, v) { 
      //                   var ItemTable = {
      //                       no: i,
      //                       // nama: ,
      //                       volume: v.uraian,
      //                       // satuan: 
      //                       // ipk: ,
      //                       status: v.satuan,
      //                   };
      //                   $('#table_edit').DataTable().row.add(ItemTable);
      //               });
      //           }
      //           $('#index_detail').DataTable().draw();
      //           $('#index_detail').DataTable().columns.adjust();
      //       },
      //       complete: function() {
      //           waitingDialog.hide();
                
      //       }
      //   });
      //   $("#ModalProgressDetail").modal('show');
      // }
    
</script>
</body>
</html>
