<style type="text/css">
  #dvContents_spk{
    font-size:8px;
  }
 
  @media print {
    .body {font-size:8px;}
  }

  @media print {
    .result {page-break-after: always;}
  }
</style>
<div id="head_Content_sipp">

<table class="table">
	<thead class="head_table">
		<tr>
			<td style="width: 10%;"></td>
			<td>
				<div id="dvContents_sipp" class="result" style="display: none;">
					<!-- <img src="{{ url('/')}}/assets/dist/img/logo-ciputra_original.png" class="img-circle" alt="User Image"> -->
					<h1>&nbsp;</h1>
					<h1>&nbsp;</h1>
					<h3><strong>{{ $spk->tender->project->name }}</strong></h3>
					<table width="100%" style="border-collapse:collapse" class='table' id='form_spk'>
					
						<tr>
							<td>{{ $spk->tender->project->city->name or '' }}, {{ date("d/M/Y",strtotime($spk->tender->menangs->last()->created_at))}}</td>
						</tr>
						<tr>
							<td>
								<table style="width: 100%">
									<tr>
										<td style="width: 30%;">Nomor Surat : </td>
										<td>{{ $spk->tender_rekanan->sipp_no or ''}}</td>
									</tr>
									<tr>
										<td style="width: 30%;">Nomor Tender : </td>
										<td>{{ $spk->tender->no or ''}}</td>
									</tr>
									<tr>
										<td style="width: 30%;">Perihal : </td>
										<td><strong><i><u>Penunjukan Pemenang {{ $spk->tender->rab->name or ''}}</u></i></strong></td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td>
								<span>Kepada Yth</span><br/>
								<span><strong>{{ $spk->rekanan->name or ''}}</strong></span><br/>
								<span>{{ $spk->rekanan->surat_alamat or ''}}</span><br/>
								<span>Telp/Fax : {{ $spk->rekanan->telp or ''}}/{{ $spk->rekanan->fax or ''}}</span><br/>
								<span>u/p : {{ $spk->rekanan->cp_name}}</span><br/><br/>
							</td>
						</tr>
						<tr>
							<td>
								<h4><strong>Dengan Hormat</strong></h4>
										<p>Mengacu pada penawaran Saudara untuk proyek tersebut diatas, dengan ini diinformasikan bahwa setelah melalui tahapan Klarifikasi dan Negoisasi pada tanggal <strong>{{ $tanggal }}</strong>, kami telah menetapkan untuk penyerahan pelaksanaan pekerjaan : <strong>{{ $spk->tender->rab->name }}</strong> kepada : </p>
							</td>
						</tr>
								<tr>
									<td style="text-align: center;font-size: 20px"><br/><strong>{{ $spk->rekanan->name }}</strong><br/></td>
								</tr>
								<tr>
									<td style="text-align: left;">
									<br/>
										<table style="width: 100%;">
									<tr>
												<td style="width: 20%;" valign="top">Nilai pekerjaan : </td>
												<!-- {{ number_format($spk->tender->menangs->first()->nilai)}} -->
												<td style="text-align: center;">
													<strong>Rp.{{ number_format($spk->nilai)}}</strong>
													<br/>
													{{strtoupper($terbilang->terbilang($spk->nilai))}} RUPIAH
													<br/> 
													(Includ. Jasa, PPh & Excl. PPN)
												</td>
												<td style="width: 20%;"></td>
									</tr>
								</table>
									</td>
								</tr>
								<tr>
							<td>
									<br/>
								<p>Sedangkan hal-hal lebih detail lainnya akan dituangkan ke dalam Surat Perintah Kerja (SPK) yang segera kami buat. Cetak MOU harga satuan mengikat sampai dengan {{ $tanggal_mengikat}}</p>
								<p>Demikian kami sampaikan untuk segera dilaksanakan sesuai dengan ketentuan-ketentuan yang disepakati</p>
							</td>
						</tr>
						<tr>
							<td>
								<br/><br/>
								<h4>Hormat Kami</h4>
								<br/><br/>
								<u>
									@if ( $spk->tender != "" )
									@foreach ( $spk->tender->approval->histories as $key => $value )
										@if ( $value->no_urut == 5 )
										{{ $value->user->user_name }}
										@endif
									@endforeach
									@endif
								</u><br/>
								<strong>General Manager</strong>
							</td>
						</tr>
					</table>
				</div>
			</td>
			<td style="width: 5%;"></td>
		</tr>
	</thead>
</table>


</div>