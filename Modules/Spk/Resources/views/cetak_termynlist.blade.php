
<:DOCTYPE html>
<html>
<head>
    <title>produk PDF </title>
    <link href=" {{ asset ('public bootstrap/css/bootstrap.min.css') }} "rel="stylesheet">
<style>
    table, tr, td {
        /* border: 1px solid black; */

        /* text-align: center; */
        vertical-align: top;
    }

    table {
        border-collapse: collapse;
        width: 100%;
        border-spacing:10px;
        font-size: 12px;
    }

   .right {
        text-align: right;
    }

    body {
      font-size: 12px;
    }

</style>
</head>
<body>
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <center>
        <h1>DAFTAR TERMYN</h1>
      </center>

    </section>

    <!-- Main content -->
    <section class="content">
      {{ csrf_field() }}
      <div class="box box-default">
        <!-- /.box-header -->
        <div class="box-body">
          
          <div class="row">
            <div class="col-md-3"></div>
              <div class="col-md-6" style="margin-left: 20%; margin-right: 20% ">
                <div class="box-header">
                  <table class="table" style="font-size:18px; width: 100%;">
                    <tr>
                      <td>Nama Perusahaan </td>
                      <td>:</td>
                      <td>{{$spk->tender->rab->pt->name}}</td>
                    </tr>
                    <tr>
                      <td>No. SPK</td>
                      <td>:</td>
                      <td>{{ $spk->no }}</td>
                    </tr>
                     <tr>
                      <td>Nilai Awal SPK (Rp.)</td>
                      <td>:</td>
                      <td> Rp. {{ number_format($spk->nilai)}} </td>
                    </tr>
                     <tr>
                      <td>Pek. Tambah/Kurang</td>
                      <td>:</td>
                      <td> Rp. {{ number_format($spk->nilai_vo)}}</td>
                    </tr>
                    </tr>
                     <tr>
                      <td>Nilai Percepatan</td>
                      <td>:</td>
                      <td> Rp. {{ number_format($spk->nilai_percepatan)}}</td>
                    </tr>
                    <tr>
                      <td>Nilai Akhir SPK(Rp.) (Excl. PPn)</td>
                      <td>:</td>
                      <td> Rp. {{ number_format($spk->nilai + $spk->nilai_vo + $spk->nilai_percepatan)}}</td>
                    </tr> 
                    <tr>
                      <td>Tanggal Mulai SPK</td>
                      <td>:</td>
                      <td>{{ date('d/M/Y',strtotime($spk->start_date_real)) }}</td>
                    </tr>
                    <tr>
                      <td>Tanggal Berakhir SPK</td>
                      <td>:</td>
                      <td>{{ date('d/M/Y',strtotime($spk->finish_date_real)) }}</td>
                    </tr>
                    <tr>
                      <td>Uraian Pekerjaan</td>
                      <td>:</td>
                      <td>{{ $spk->name }}</td>
                    </tr>
                  </table>
                </div>
              </div>
           <div class="col-md-3"></div>
          </div>
          <br>
        <section class="content-header">
          <center>
            <h3>KETERANGAN TERMYN LIST</h3>
          </center>

        </section>
            <div class="row">
              <div class="col-md-12">
                <table  class="table" style="font-size:15px; width: 100%; ">
                  <tr style="border: 1px solid black;">
                    <td rowspan="2" style="border: 1px solid black; text-align: center; width: 22%">No. BAP</td>
                    <td colspan="2" style="border: 1px solid black; text-align: center">Termin</td>
                    <td rowspan="2" style="border: 1px solid black; text-align: center">Nilai Progres Saat ini</td>
                    <!-- <td rowspan="2" style="border: 1px solid black; text-align: center">Jumlah Tagihan s/d Saat ini</td> -->
                    <td colspan="2" style="border: 1px solid black; text-align: center">Retensi</td>
                    <td rowspan="2" style="border: 1px solid black; text-align: center">Nilai Progres Dalam Sertifikat ini</td>
                    <td colspan="2" style="border: 1px solid black; text-align: center">PPN</td>
                    <td colspan="2" style="border: 1px solid black; text-align: center">PPH</td>  
                    <td rowspan="2" style="border: 1px solid black; text-align: center">Nilai Sertifikat ini</td>
                    <td rowspan="2" style="border: 1px solid black; text-align: center">Tgl Dicairkan</td>
                    <tr>
                      <td style="border: 1px solid black; text-align: center">%</td>
                      <td style="border: 1px solid black; text-align: center">Total(%)</td>
                      <td style="border: 1px solid black; text-align: center">%</td>
                      <td style="border: 1px solid black; text-align: center">(Rp.)</td>
                      <td style="border: 1px solid black; text-align: center">%</td>
                      <td style="border: 1px solid black; text-align: center">(Rp.)</td>
                      <td style="border: 1px solid black; text-align: center">%</td>
                      <td style="border: 1px solid black; text-align: center">(Rp.)</td>
                    </tr>
                  </tr>
                  @foreach ($spk->baps as $key)
                  @php $tagihan_skr = 0 @endphp
                  <tr>
                    <td style="text-align: center;"> {{ $key->no }} </td>
                    <td style="text-align: center;">{{ $key->percentage }}</td>
                    <td style="text-align: center;">{{ $key->percentage_sebelumnyas + $key->percentage }} </td>
                    <td style="text-align: center;"> {{ number_format($key->nilai_bap_1) }}</td>
                    
                    <td style="text-align: center;">
                      @if ( $spk->progress_sebelumnya == 100)
                      0 %
                      @else
                        {{round($spk->retensis->sum('percent')*100, 4)}}%
                      @endif
                    </td>
                    <td style="text-align: center;">{{number_format($key->nilai_retensi)}}</td>
                    <td style="text-align: center;">{{number_format($nilai_progres = $key->nilai_bap_2)}}</td>
                    <td style="text-align: center;">
                      {{$key->ppn*100}}%


                    </td>
                     <td style="text-align: center;">
                      {{ number_format($ppn_nilai_progres = $key->nilai_ppn) }}                    
                    </td>
                    <td style="text-align: center;">{{number_format((float)$key->pph*100, 2, '.', '')}}%</td>
                    <td style="text-align: center;">{{ number_format($key->nilai_pph) }}</td>
                    <td style="text-align: center;">{{number_format($nilai_sertifikat = $key->nilai_bap_3)}}</td>
                    
                    @if ($key->vouchers_date_cair != null)
                      <td style="text-align: center;">{{ $key->vouchers_date_cair->pencairan_date}}</td>
                    @else
                      <td style="text-align: center;">-</td>
                    @endif
                  </tr>
                  @endforeach
                </table>
                
              </div>
            </div>
            <!-- /.col -->
            <!--  <div class="col-md-6">
              <h3>&nbsp;</h3>              
              <div class="form-group">
                <label>Start Date</label>
                <input type="text" class="form-control" name="start_date" id="start_date" autocomplete="off" required>
              </div> 
              <div class="box-footer">
                <i class="fa fa-refresh ld ld-spin" id="loading" style="display: none;"></i>
                <button type="submit" class="btn btn-primary submitbtn" id="btn_submit">Simpan</button>
                <a class="btn btn-warning" href="{{ url('/')}}/workorder">Kembali</a>
              </div>
            </div> -->
            <!-- </form> -->
            <!-- /.col -->
          </div>
          <!-- /.row -->
        </div>
        <!-- /.box-body -->
    </section>
    <!-- /.content -->
  </div>
                  
</body>
</html>