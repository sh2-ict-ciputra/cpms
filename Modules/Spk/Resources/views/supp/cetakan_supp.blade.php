<!DOCTYPE html>
<html>
<head>
    <title>produk PDF </title>
<style>
    table{
        /* border: 1px solid black; */
        text-align: center;
        width: 100%;
    }
    table, tr, td{
        /* border: 1px solid black; */
        vertical-align:top;
    }
    body{
      font-size: 12px;
    }

    /* #header {
        position: fixed;
        top: -200px;
    } */

    table, tr {
        page-break-inside: auto;
    }
    html {  
		margin:160px 65px 50px 80px;
	}
</style>
</head>
<body >
    <div class="panel panel - default " id="header">
        {{-- <h1>&nbsp;</h1>
        <h1>&nbsp;</h1> --}}
        <table  class="table table-striped" style="margin-bottom:0" >
            <tr >
                <td colspan="2" style="border-bottom: 3px solid black;font-size:20px;">
                    <strong> SYARAT- UMUM PERJANJIAN PEMBORONGAN <br/>
                   {{ strtoupper($supp->pt->name)}} dengan  {{strtoupper($supp->rekanan_group->name)}} </strong>
                </td>
            </tr>
            <tr >
                <td colspan="2" style="font-size:20px;">
                    <strong>SUPP NO :  {{$supp->no}}</strong>
                </td>
            </tr>
            <tr style="text-align: left">
                <td colspan="2">
                    <br/><br/>
                    Kami yang bertanda tangan di bawah ini :
                </td>
            </tr>
            <tr style="text-align: justify">
                <td colspan="2">
                    <strong>{{ strtoupper($supp->pt->name)}}</strong>, berkedudukan di {{ $supp->pt->address}}, dalam hal ini diwakili oleh <strong>{{strtoupper($user_ttd)}} ({{$user_ttd_jabatan->name}})</strong>, sehingga oleh dan karenanya berhak menandatangani Perjanjian ini untuk dan atas nama  <strong>{{ strtoupper($supp->pt->name)}}</strong>, selanjutnya disebut sebagai <strong>PIHAK PERTAMA</strong>.
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <br/>
                    bersama
                </td>
            </tr>
            <tr style="text-align: justify">
                <td colspan="2">
                    Kontraktor <strong>{{ strtoupper($supp->rekanan_group->name)}}</strong>, berkedudukan di <strong>{{$supp->rekanan_group->npwp_alamat}}</strong>, dalam hal ini diwakili oleh <strong>{{strtoupper($supp->rekanan_group->cp_name)}} ({{$supp->rekanan_group->cp_jabatan}})</strong>, sehingga oleh dan karenanya berhak menandatangani Perjanjian ini untuk dan atas nama Kontraktor <strong>{{ strtoupper($supp->rekanan_group->name)}}</strong>, selanjutnya disebut sebagai <strong>PIHAK KEDUA<strong>.
                </td>
            </tr>
            <tr style="text-align: justify">
                <td colspan="2">
                    Pihak Pertama dan Pihak kedua bersama-sama disebut sebagai para pihak telah sepakat untuk melaksanakan ketentuan-ketentuan yang ada dalam Syarat Umum Perjanjian Pemborongan ( SUPP ) sebagai berikut :
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <br/><br/>
                    <strong>PASAL 1<br/>
                    JENIS DAN LOKASI PEKERJAAN</strong>
                    <br/><br/>
                </td>
            </tr>
            <tr>
                <td style="width:5%;text-align: left">
                    1.1
                </td>
                <td style="width:95%;text-align: justify">
                    PIHAK PERTAMA memberi Pekerjaan kepada PIHAK KEDUA dan PIHAK KEDUA bersedia menerima pekerjaan tersebut dari PIHAK PERTAMA untuk melaksanakan Pemborongan di Proyek CIPUTRA GROUP berdasarkan gambar pelaksanaan, rencana kerja dan syarat-syarat (RKS) dan uraian detail spesifikasi teknis (spesifikasi material dan metode kerja) untuk jenis dan lokasi pekerjaan terlampir pada masing-masing Surat Perintah Kerja (SPK) yang merupakan bagian yang tidak terpisahkan dari perjanjian ini.
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                    1.2
                </td>
                <td style="text-align: justify">
                    Dalam pekerjaan tersebut, sudah termasuk semua pekerjaan-pekerjaan kecil lainnya yang selayaknya harus dilaksanakan sebagai kelengkapan dan kesempurnaan serta metode pelaksanaanpekerjaan yang berkaitan dengan sistem dan fungsi walaupun pekerjaan-pekerjaan kecil ini tidak terlihat atau dinyatakan dengan jelas pada gambar-gambar, Dokumen Tender atau Dokumen Kontrak.
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                    1.3
                </td>
                <td style="text-align: justify">
                    Dalam pekerjaan tersebut, sudah termasuk semua pekerjaan-pekerjaan kecil lainnya yang selayaknya harus dilaksanakan sebagai kelengkapan dan kesempurnaan serta metode pelaksanaanpekerjaan yang berkaitan dengan sistem dan fungsi walaupun pekerjaan-pekerjaan kecil ini tidak terlihat atau dinyatakan dengan jelas pada gambar-gambar, Dokumen Tender atau Dokumen Kontrak.
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <br/><br/>
                    <strong>PASAL 2<br/>
                    NILAI, WAKTU  & JENIS KONTRAK PEKERJAAN</strong>
                    <br/><br/>
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                    2.1
                </td>
                <td style="text-align: justify">
                    Nilai dan jenis kontrak baik remeasurement, atau fixed unit price, atau Lumpsum fixed price, akan ditentukan dan disepakati melalui proses klarifikasi, negosiasi dan tertera dalam Surat Perintah Kerja (SPK) antara PIHAK PERTAMA dan PIHAK KEDUA (baik melalui proses tender maupun penunjukan langsung).
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                    2.2
                </td>
                <td style="text-align: justify">
                    Waktu pelaksanaan pekerjaan diuraikan pada masing-masing SPK yang ada. Dalam Hal ada pekerjaan tambah/kurang seperti yang dimaksud pada pasal perjanjian, mempengaruhi waktu pelaksanaan pekerjaan, maka PIHAK KEDUA berhak mengajukan perpanjangan waktu yang lamanya akan ditentukan dan disetujui oleh PIHAK PERTAMA.
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                    2.3
                </td>
                <td style="text-align: justify">
                    Harga satuan pekerjaan yang terlampir pada SPK dan BoQ Revisi (Bill of Quantity Revisi) mengikat selama SPK tersebut masih berlaku dan belum berakhir  , termasuk untuk pekerjaan tambah/kurang pada SPK tersebut seperti yang diatur dalam pasal 11 Perjanjian ini
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <br/><br/>
                    <strong>PASAL 3<br/>
                    PIHAK-PIHAK TERKAIT</strong>
                    <br/><br/>
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                    3.1
                </td>
                <td style="text-align: justify">
                    PIHAK PERTAMA adalah pihak yang memberikan pekerjaan dan berhak mengawasi pelaksanaan pekerjaan seperti dimaksud dalam pasal 1 perjanjian ini. PIHAK PERTAMA menunjuk Pengawas Lapangan yang bertindak sebagai pengawas pekerjaan dan memberikan petunjuk/pengarahan kepada PIHAK KEDUA dalam pelaksanaan pekerjaan sehari-hari di lapangan. Dalam melakukan pengawasan dilapangan, Pengawas lapangan ini dibawah koordinasi dan tanggungjawab Head of Division / Manajer Teknik
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                    3.2
                </td>
                <td style="text-align: justify">
                    PIHAK KEDUA adalah pihak yang berhak menandatangani perjanjian ini menerima dan melaksanakan pekerjaan sesuai pasal Perjanjian . Hal-hal lebih lanjut mengenai hak & kewajiban PIHAK KEDUA pada pasal Perjanjian .
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                    3.3
                </td>
                <td style="text-align: justify">
                    PIHAK KETIGA adalah pihak lain yang mempunyai hubungan kerjasama (MOU) dengan Pihak Pertama dalam hal supply barang dan atau pekerjaan dengan harga, kualitas dan syarat-syarat pembayaran yang telah disepakati bersama  oleh PIHAK PERTAMA.
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                    3.4
                </td>
                <td style="text-align: justify">
                    Pemborong Pembantu (Sub Kontraktor) adalah pihak yang mendukung terlaksananya pekerjaan, dimana semua koordinasinya akan dilakukan oleh PIHAK KEDUA. 
            </tr>
            <tr>
                <td colspan="2">
                    <br/><br/>
                    <strong>PASAL 4<br/>
                    KEWAJIBAN PIHAK KEDUA</strong>
                    <br/><br/>
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                    4.1
                </td>
                <td style="text-align: justify">
                    PIHAK KEDUA wajib mengikuti tata cara/prosedur seleksi kontraktor yang ditetapkan oleh PIHAK PERTAMA.
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                    4.2
                </td>
                <td style="text-align: justify">
                    PIHAK KEDUA wajib menyerahkan barang/jasa sesuai dengan spesifikasi dan waktu yang telah ditentukan, serta akan bertanggungjawab atas segala ketidaksesuaian yang ada. Sesuai dengan SPK yang disepakati bersama.
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                    4.3
                </td>
                <td style="text-align: justify">
                    PIHAK KEDUA wajib memberikan harga penawaran terbaik kepada PIHAK PERTAMA.
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                    4.4
                </td>
                <td style="text-align: justify">
                    PIHAK KEDUA tidak diperkenankan memberikan hadiah, jamuan, insentif atau komisi tak resmi kepada karyawan Ciputra Group baik dalam proses seleksi/tender, penyerahan barang/jasa maupun dalam masa pemeliharaan/garansi yang dapat mempengaruhi indipendisi /objektifitas karyawan Ciputra Group dalam menjalankan tugas dan tanggung jawabnya. Apabila terjadi pelanggaran, PIHAK KEDUA bersedia diakhiri/diputus secara sepihak dan menerima konsekuensinya, termasuk denda/penalty.
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                    4.5
                </td>
                <td style="text-align: justify">
                    Dalam melaksanakan pekerjaan, PIHAK KEDUA harus dapat bekerjasama dengan PIHAK PERTAMA atau pihak yang ditunjuk oleh PIHAK PERTAMA, mematuhi dan melaksanakan petunjuk-petunjuk teknis dari tim pengawas. Para Pihak akan melakukan musyawarah dan bersepakat dalam mengambil keputusan di lapangan dalam hubungannya dengan pekerjaan.
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                    4.6
                </td>
                <td style="text-align: justify">
                    PIHAK KEDUA wajib untuk membaca, mempelajari dan memahami dengan seksama semua dokumen yang diberikan, selama proses tender sampai dengan proyek selesai. Pihak Kedua membebaskan Pihak Pertama dari Segala gugatan karena kesalahan dan atau kelalaian Pihak Kedua dalam Hal tidak membaca, tidak memahami atau salah interprestasi dalam menghitung volume dan atau kesalahan memasukan harga penawaran akibat kesalahan analisa harga satuan bahan dan upah kerja. PIHAK KEDUA bertanggung jawab terhadap analisa dan interprestasi atas semua data yang diterima, atas semua pendapat, konklusi dan rekomendasi yang diberikan.
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                    4.7
                </td>
                <td style="text-align: justify">
                    PIHAK KEDUA wajib mengerahkan tenaga kerja mulai dari tenaga pembantu sampai tenaga ahli di dalam usaha menyelesaikan pekerjaan menurut kualitas dan kuantitas yang telah disetujui oleh PIHAK PERTAMA dan menempatkan seorang yang ahli dalam bidangnya sebagai Kuasa Penuh untuk mewakili PIHAK KEDUA dalam pelaksanaan pekerjaan dan dapat menerima serta memutuskan segala sesuatu yang berhubungan dengan pelaksanaan pekerjaan sesuai petunjuk dari PIHAK PERTAMA yang untuk selanjutnya disebut sebagai  “<strong>Pelaksana</strong> ”.
                </td>
            </tr>
             <tr>
                <td style="text-align: left">
                    4.8
                </td>
                <td style="text-align: justify">
                   PIHAK PERTAMA berhak meminta PIHAK KEDUA untuk memberhentikan dan atau mengganti setiap personil yang ditugaskan PIHAK KEDUA yang dianggap kurang baik dan atau lalai dalam menjalankan pekerjaan  dan PIHAK KEDUA  wajib melakukan penggantian  paling lambat dalam waktu 3 (tiga) hari terhitung sejak pemberitahuan secara lisan maupun tertulis.
                </td>
            </tr>
             <tr>
                <td style="text-align: left">
                    4.9
                </td>
                <td style="text-align: justify">
                   PIHAK KEDUA wajib mematuhi syarat perjanjian yang telah disepakati antara PIHAK PERTAMA & PIHAK KETIGA dalam hal pembelian material ditawarkan melalui PIHAK KETIGA yang telah ditentukan khususnya mengenai Spesifikasi Material, Harga dan cara pembayarannya. 
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                    4.10
                </td>
                <td style="text-align: justify">
                   PIHAK KEDUA wajib membeli dan atau mengambil material dari PIHAK KETIGA (supplier) yang ditunjuk PIHAK PERTAMA dalam ikatan MOU dengan melampirkan surat pengantar (berisikan jumlah, waktu dan lokasi gudang) yang disetujui PIHAK PERTAMA. Apabila PIHAK KEDUA tidak membeli dan atau mengambil material pada PIHAK KETIGA (supplier), maka segala resiko dan konsekuensi yang timbul akan menjadi tanggung jawab sepenuhnya dari PIHAK KEDUA.  
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                    4.11
                </td>
                <td style="text-align: justify">
                   PIHAK KEDUA wajib melaksanakan pekerjaan seperti dimaksud dalam pasal 1 perjanjian ini sesuai dengan gambar teknis, uraian cara pelaksanaannya dan spesifikasi yang disiapkan oleh PIHAK PERTAMA dan merupakan bagian yang tidak terpisahkan dari surat perjanjian ini.
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                    4.12
                </td>
                <td style="text-align: justify">
                   PIHAK KEDUA wajib melaksanakan pekerjaan sesuai dengan SPK dan SIK yang telah diterima oleh PIHAK KEDUA dari PIHAK PERTAMA.
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                    4.13
                </td>
                <td style="text-align: justify">
                   PIHAK KEDUA menunjuk perwakilannya yang berhak dan sah untuk melakukan negosiasi.  Dalam hal surat kuasa tidak sesuai dan atau tidak sah, maka PIHAK PERTAMA berhak untuk menolak perwakilan tersebut.
                </td>
            </tr>
             <tr>
                <td style="text-align: left">
                    4.14
                </td>
                <td style="text-align: justify">
                   PIHAK KEDUA wajib membuat RAB final sesuai dengan nilai yang tertera dalam surat penunjukan paling lambat satu minggu setelah dikeluarkannya surat penunjukan. Dalam membuat RAB final, PIHAK KEDUA tidak diperkenankan untuk mengganti volume diluar kesepakatan  klarifikasi dan negosiasi. Apabila PIHAK KEDUA tidak membuat  RAB final, maka PIHAK PERTAMA akan membuat BoQ revisi secara proporsional terhadap harga satuan dan mengikat PIHAK KEDUA.
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                    4.15
                </td>
                <td style="text-align: justify">
                   Kontraktor secara proaktif memberikan masukan untuk meningkatkan kualitas produk yang dikerjakan dan menanggulangi resiko/akibat yang ditimbulkan. Hal tersebut menjadi bagian dari penilaian performa/kinerja kontraktor untuk pemberian pekerjaan berikutnya.
                </td>
            </tr>
              <tr>
                <td style="text-align: left">
                    4.16
                </td>
                <td style="text-align: justify">
                   PIHAK KEDUA wajib menyediakan material dan metode pelaksanaan yang diperlukan sesuai syarat-syarat yang ditentukan dalam peraturan konstruksi yang berlaku di Indonesia : <br>
                   a. Material harus memenuhi syarat-syarat SNI <br>
                   b. Metode pelaksanaan Bangunan dan Prasarana sesuai  Standar Cipta Karya dan Bina Marga <br>
                   c. Metode pelksanaan MEP sesuai Standar PDAM, PLN, TELKOM, Pertambangan <br>
                   d. Menjalankan ketentuan K3L ( Keselamatan dan Kesehatan Kerja Lingkungan ) <br>
                   e. PerMen PU no:20/PRT/M/2010 ttg Pedoman pemanfaatan dan penggunaan bagian-bagian jalan <br>
                   f. PUIL 2000 – SNI 04-0225-2000 ttg Persyaratan Umum Instalasi Listrik <br>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <br/><br/>
                    <strong>PASAL 5<br/>
                    PEMBORONG PEMBANTU (Sub KONTRAKTOR)</strong>
                    <br/><br/>
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                    5.1
                </td>
                <td style="text-align: justify">
                  PIHAK KEDUA Wajib mendapatkan ijin secara tertulis terlebih dahulu dari PIHAK PERTAMA apabila menggunakan sub kontraktor diluar PIHAK KETIGA, dengan catatan bahwa pekerjaan yang di-sub-kan adalah pekerjaan yang bersifat khusus dalam arti yang memerlukan keahlian khusus untuk melaksanakan pekerjaan tersebut.
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                    5.2
                </td>
                <td style="text-align: justify">
                  Apabila penggunaan/penunjukan sub Kontraktor yang dilakukan PIHAK KEDUA telah disetujui oleh PIHAK PERTAMA, maka PIHAK KEDUA tetap bertanggung jawab penuh atas segala pelaksanaaan dan hasil pekerjan yang dilakukan sub kontraktor tersebut.
                </td>
            </tr>
             <tr>
                <td style="text-align: left">
                    5.3
                </td>
                <td style="text-align: justify">
                  PIHAK PERTAMA berhak untuk menolak sub kontraktor tersebut secara sepihak dan semua kerugian/resiko akibat pemutusan secara sepihak menjadi beban dan tanggung jawab PIHAK KEDUA. PIHAK PERTAMA berhak untuk menerima dan atau menolak semua Sub Kontraktor yang akan digunakan oleh PIHAK KEDUA atau sudah digunakan tetapi tidak sesuai dengan standard PIHAK PERTAMA.
                </td>
            </tr>
             <tr>
                <td style="text-align: left">
                    5.4
                </td>
                <td style="text-align: justify">
                 Dalam Hal terdapat masalah dan atas pembayaran antara Sub Kontraktor dengan PIHAK KEDUA maka PIHAK PERTAMA dibebaskan dari tanggung jawab atas permasalahan ini dan sepenuhnya menjadi tanggung jawab PIHAK KEDUA. 
                </td>
            </tr>
             <tr>
                <td colspan="2">
                    <br/><br/>
                    <strong>PASAL 6<br/>
                    LAPORAN</strong>
                    <br/><br/>
                </td>
            </tr>
             <tr>
                <td style="text-align: left">
                    6.1
                </td>
                <td style="text-align: justify">
                 PIHAK KEDUA wajib membuat dan menyerahkan Struktur Organisasi yang berisi penanggung jawab proyek dan penanggung jawab lapangan, disertai Curiculum Vitae/Resume dan Job Description/Uraian Pekerjaan personil yang bersangkutan.
                </td>
            </tr>
             <tr>
                <td style="text-align: left">
                    6.2
                </td>
                <td style="text-align: justify">
                 PIHAK KEDUA wajib membuat schedule pelaksanaan pekerjaan, laporan prestasi kerja yang dicapai dan segala sesuatu yang berkaitan dengan pelaksanaan pekerjaan secara berkala setiap minggunya mulai dari awal pelaksanaan sampai dengan berakhirnya pekerjaan dalam bentuk form yang telah disetujui PIHAK PERTAMA.
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                    6.3
                </td>
                <td style="text-align: justify">
                PIHAK KEDUA wajib membuat dan menyerahkan catatan yang jelas mengenai proses pelaksanaan pekerjaan yang telah dilaksanakan, foto dokumentasi (bertanggal ,berwaktu dan lokasi dokumentasi) dan dokumen lainnya secara berkala sampai berakhirnya pekerjaan dalam bentuk album dan dibuat rangkap 2 (dua).
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                    6.4
                </td>
                <td style="text-align: justify">
                PIHAK KEDUA wajib membuat <strong>Shop Drawing</strong> sebagai pedoman pelaksanaan pekerjaan di lapangan dan memberitahukan setiap perbedaan ukuran, ketentuan, kekurangan dan/ atau kesalahan dari gambar dan/atau penyimpangan-penyimpangan lainnya sebelum melanjutkan pekerjaan. PIHAK KEDUA tidak diperkenankan memutuskan/memperbaiki penyimpangan-penyimpangan yang ada tanpa persetujuan PIHAK PERTAMA. Setiap kerugian yang timbul atas penyimpangan ini menjadi  tanggung jawab dan beban PIHAK KEDUA. <br>
                Apabila PIHAK KEDUA tidak memenuhi kewajibannya membuat shop drawing maka PIHAK PERTAMA berhak menyerahkan pembuatan shop drawing kepada pihak lain dengan membebankan biaya pembuatan kepada PIHAK KEDUA.
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                    6.5
                </td>
                <td style="text-align: justify">
               Sebagai syarat penerbitan Berita Acara Serah Terima I, PIHAK KEDUA wajib melampirkan bukti serah terima As Built Drawing, Test commisioning MEP ( test tekan, test merger, test Grounding dan test fungsi ) ke PIHAK PERTAMA.
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <br/><br/>
                    <strong>PASAL 7<br/>
                    BAHAN dan ALAT KERJA</strong>
                    <br/><br/>
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                    7.1
                </td>
                <td style="text-align: justify">
               Semua bahan material dan peralatan kerja, baik yang umum maupun yang khusus, yang dibutuhkan untuk pelaksanaan pekerjaan seperti dimaksud dalam pasal 1 Perjanjian ini, harus disediakan oleh PIHAK KEDUA yang telah dan rutine dikalibrasikan.
                </td>
            </tr>
             <tr>
                <td style="text-align: left">
                  7.2
                </td>
                <td style="text-align: justify">
               PIHAK KEDUA wajib dan bertanggung jawab untuk menyediakan, menyimpan dan menjaga keamanan material/bahan dan alat bantu, termasuk material MOU yang pada umumnya langsung atau tidak langsung termasuk di dalam usaha menyelesaikan pekerjaan menurut kualitas dan kuantitas yang telah disetujui oleh PIHAK PERTAMA.
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                  7.3
                </td>
                <td style="text-align: justify">
               Dalam hal terdapat keraguan atas kualitas material/bahan dari PIHAK KEDUA, PIHAK PERTAMA berhak melakukan test penelitian atas biaya PIHAK KEDUA. Material/bahan atau alat yang ditolak oleh PIHAK PERTAMA wajib segera diangkut keluar dari lokasi pekerjaan oleh PIHAK KEDUA paling lambat dalam waktu 24 jam terhitung sejak diterimanya pemberitahuan penolakan dari PIHAK PERTAMA.
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                  7.4
                </td>
                <td style="text-align: justify">
               Jika diperlukan PIHAK KEDUA wajib mengajukan contoh-contoh material yang akan digunakan atas biaya PIHAK KEDUA. Apabila material MOU tidak sesuai dengan spesifikasi yang telah ditentukan dan disepakati bersama dengan Pihak Ketiga  maka PIHAK  PERTAMA berhak menolak material yang dikirim.
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <br/><br/>
                    <strong>PASAL 8<br/>
                    TATA TERTIB ADMINISTRASI PEKERJAAN</strong>
                    <br/><br/>
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                  8.1
                </td>
                <td style="text-align: justify">
               Selama pekerjaan  tersebut dalam pasal Perjanjian masih berlangsung, PIHAK KEDUA bertanggungjawab atas keselamatan para personil, material dan peralatan kerja yang menjadi tangggungjawab PIHAK KEDUA.
                </td>
            </tr>
             <tr>
                <td style="text-align: left">
                  8.2
                </td>
                <td style="text-align: justify">
               Setiap tahap hasil prestasi pelaksanaan pekerjaan yang dilakukan PIHAK KEDUA selesai dilaksanakan maka PIHAK KEDUA wajib memberitahukan kepada PIHAK PERTAMA secara tertulis paling lambat dalam waktu 7 (tujuh) hari kalender untuk dilakukan pengecekan oleh pihak pertama.
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                  8.3
                </td>
                <td style="text-align: justify">
               PIHAK KEDUA harus memelihara, memperbaiki dan menyempurnakan hasil pekerjaan yang telah diserahkan kepada PIHAK PERTAMA dengan biaya dari PIHAK KEDUA, Dalam hal terdapat kerusakan yang terjadi selama masa pemeliharaan tersebut, maka PIHAK  KEDUA wajib segera memperbaiki dan segala biaya perbaikan menjadi tanggung jawab Pihak Kedua.
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                  8.4
                </td>
                <td style="text-align: justify">
               Apabila terdapat  kerusakan yang tidak disebabkan oleh KELALAIAN PIHAK KEDUA, maka PIHAK PERTAMA akan mengambil kebijakan untuk menyelesaikan masalah. 
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                  8.5
                </td>
                <td style="text-align: justify">
               Selama pekerjaan masih berlangsung para pihak setuju untuk mengadakan rapat rutin yang waktu dan tempatnya akan ditentukan kemudian, untuk memonitor kelangsungan pelaksanaan pekerjaan dilapangan.  
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                  8.6
                </td>
                <td style="text-align: justify">
               Setelah pemeliharaan seperti dimaksud dalam ayat 3 dan 4 pasal ini selesai, PIHAK KEDUA wajib menyerahkan pekerjaan untuk kedua kalinya kepada PIHAK PERTAMA, yang dinyatakan dalam Berita Acara Serah Terima Pekerjaan Kedua dan ditandatangani oleh kedua belah pihak.  
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <br/><br/>
                    <strong>PASAL 9<br/>
                    CARA PEMBAYARAN</strong>
                    <br/><br/>
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                  9.1
                </td>
                <td style="text-align: justify">
               KETENTUAN CARA PEMBAYARAN SECARA UMUM:  
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                  9.1.1
                </td>
                <td style="text-align: justify">
               Semua proses tagihan harus disertai dengan;
                 <ul>
                  <li>Foto copy SPK yang telah ditandatangani semua pihak,</li>
                  <li>Foto copy VO yang sudah pernah dikeluarkan.</li>
                  <li>Kwitansi yang bermeterai <br> 
                    -  Meterai Rp 3.000,- untuk    Rp. 250.000 < Nilai Kwitansi < Rp. 1.000.000,- <br>
                    -  Meterai Rp. 6.000   untuk   Nilai Kwitansi ≥ Rp. 1.000.000,-
                  </li>
                  <li>Berita Acara Prestasi Pekerjaan yang telah ditandatangani PIHAK PERTAMA dan PIHAK KEDUA (asli).</li>
                  <li>Foto progress lapangan terakhir. ( Foto tertera tanggal, waktu dan lokasi )</li>
                  <li>Semua dokumen di atas harus dibuat rangkap 2 (dua) untuk progress sebelum ST II, rangkap 3 (tiga) untuk tagihan Final Account</li>
                  <li>Berita Acara ST I untuk tagihan 100% dan Berita Acara ST I dan ST II untuk tagihan Final Account.</li>
                  <li>Bagi kontraktor yang menerbitkan faktur pajak (PPN) wajib menyerahkan SPM (Surat Pemberitahuan Masa) tagihan sebelumnya untuk setiap memasukan tagihan kecuali DP.</li>
                  <li>Kwitansi dan Faktur pajak diserahkan kepada PIHAK PERTAMA bersamaan dengan berkas-berkas lainnya sebagai lampiran sertifikat pembayaran yang diterbitkan.</li>
                </ul>  
              </td>
            </tr>
            <tr>
                <td style="text-align: left">
                  9.1.2
                </td>
                <td style="text-align: justify">
               Semua pembayaran atas tahapan pembayaran dilakukan berdasarkan opname di lapangan dengan menyertakan Berita Acara Prestasi pekerjaan yang dibuat PIHAK KEDUA dan disetujui sesuai yang distandarkan oleh  PIHAK PERTAMA. Dengan ditandatanganinya Berita Acara Prestasi pekerjaan, tidak serta merta  PIHAK KEDUA lepas dari tanggung jawab terhadap hasil pekerjaan dan/atas kesalahan sebelumnya sampai dengan berakhirnya masa pemeliharaan. BAP hanya berisikan progress saja tanpa harga satuan dan disertai dengan foto progress lapangan. <br>
               Pekerjaan tambah tidak boleh diajukan oleh PIHAK KEDUA setelah Berita Acara Serah Terima I disetujui PIHAK PERTAMA. Apabila melewati batas waktu tersebut, maka pekerjaan tambah tersebut tidak dapat ditagihkan pada SPK tersebut.
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                  9.1.3
                </td>
                <td style="text-align: justify">
               Untuk dapat mengajukan Tagihan tahapan pembayaran Serah Terima II, PIHAK KEDUA harus melampirkan Berita Acara Serah Terima I dan Pembayaran Serah terima III , PIHAK KEDUA harus melampirkan Berita Acara Serah Terima I dan II, Hasil Pemeriksaan pada Serah Terima I, Berita Acara Pembersihan Bangunan dan foto bangunan (bertanggal, waktu dan lokasi) yang menunjukkan fisik pekerjaan dengan progress 100%. <br>
               PIHAK PERTAMA akan mengeluarkan sertifikat pembayaran yang digunakan PIHAK KEDUA dalam melakukan penagihan ke PIHAK PERTAMA apabila semua syarat pembayaran sudah dipenuhi oleh PIHAK KEDUA. <br>
               Pembayaran dilakukan dalam batas waktu pencairan selama 21 (dua puluh satu) hari terhitung serah diterimanya semua berkas tagihan yang dinyatakan sudah lengkap ,benar dan diterima oleh PIHAK PERTAMA serta Final Account dikeluarkan pada saat tagihan tahapan pembayaran  serah terima II. <br>
               Nilai SPK ≤ lebih kecil/sama dengan 3.000.000,- ( Tiga Juta rupiah ) dibuat tanpa retensi dan dapat langsung dibuatkan Berita Acara Serah Terima II 
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                  9.1.4
                </td>
                <td style="text-align: justify">
               Setiap tagihan akan dipotong Pajak Penghasilan (PPh) oleh PIHAK PERTAMA sesuai dengan ketentuan pemerintah. <br>
               Tagihan untuk pekerjaan tambah hanya dapat dilakukan bersamaan dengan tagihan induk. <br>
               Syarat kelengkapan administrasi tagihan : <br>
                1. Kwitansi <br>
                2. Faktur Pajak <br>
                3. Photocopy SPK <br>
                4. Photocopy LPJK/SIUJK yang berlaku <br>
                5. Photocopy Form A1 dan SSP <br>
                6. Berita Acara Penyelesaian Pekerjaan (Progres pekerjaan setiap bulan berjalan yang  telah disetujui oleh PIHAK PERTAMA). <br>
                Seluruh berkas tagihan diserahkan kepada Bagian Administrasi Teknik PIHAK PERTAMA sebanyak 
            dua rangkap, satu asli dan satu photocopy. <br>
            Jadwal pembayaran tagihan Departemen Keuangan adalah dari tanggal 1 sampai dengan tanggal 27 tiap 
            bulan pada hari Selasa dan Kamis,  Pagi  Jam 10.00 – 12.00  dan  Sore Jam 13.00 – 15.00  WIB. <br>
           Jadwal penerimaan tagihan di Departemen Teknik  adalah pada hari Selasa dan Kamis, Pagi  Jam 08.30        - 12.00  dan  Sore  Jam  13.30 – 16.30  WIB. <br>
            Untuk tagihan yang sudah 30 bulan (2,5) tahun melewati BAST atau berakhirnya masa pelaksanaan        
            pekerjaan, maka sisa pembayaran tidak dapat ditagihkan oleh Pemborong/Kontraktor.
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                  9.1.5
                </td>
                <td style="text-align: justify">
               Dalam hal PIHAK KEDUA akan mengalihkan pembayaran ke PIHAK LAIN maka PIHAK KEDUA wajib membuat dan menyerahkan SURAT KUASA bermeterai cukup sesuai aturan pemerintah yang berlaku dan disetujui oleh PIHAK PERTAMA 
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                  9.2
                </td>
                <td style="text-align: justify">
               PEKERJAAN PEMBANGUNAN RUMAH, RUKO dan GUDANG  
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                  9.2.1
                </td>
                <td style="text-align: justify">
               Cara Pembayaran Khusus Untuk SPK Pembangunan Perumahan. <br>
                 <ul>
                   <li>Tagihan pertama sebagai DP dapat ditagihkan setelah Surat Perintah Kerja ditandatangani oleh Kedua Belah Pihak.Sewaktu pengambilan Giro di bagian keuangan PIHAK PERTAMA, PIHAK KEDUA harus menyerahkan bukti progres minimal yang dicapai atau dapat dicounter dengan Bank Garansi /Asuransi Bank Garansi senilai uang muka tersebut diatas (lembaga Asuransi yang ditunjuk PIHAK PERTAMA) dalam batas waktu pencairan selama 21 (dua puluh satu) hari kalender.</li>
                  <li>Tagihan selanjutnya setelah mencapai progres 100%, dibayarkan 95% dengan melampirkan bukti Serah Terima Pertama (BAST-1) dan Retensi 1 diambil setelah masa pemeliharaan selama 120 hari kalender, dengan melampirkan Barita Acara kedua (BAST-2 ) serta Retensi 2 diambil setelah masa pemeliharaan selama 365 hari kalender, dengan melampirkan Barita Acara ketiga (BAST-3 )</li>
                </ul> 
              </td>
            </tr>
            <tr>
                <td style="text-align: left">
                  9.2.2
                </td>
                <td style="text-align: justify">
               Cara Pembayaran Khusus Untuk SPK Interior Bangunan. <br>
                  <ul>
                    <li>Tagihan pertama sebagai DP dapat ditagihkan setelah Surat Perintah Kerja ditandatangani oleh Kedua Belah Pihak.Sewaktu pengambilan Giro di bagian keuangan PIHAK PERTAMA, PIHAK KEDUA harus menyerahkan bukti progres minimal yang dicapai atau dapat dicounter dengan Bank Garansi /Asuransi Bank Garansi senilai uang muka tersebut diatas (lembaga Asuransi yang ditunjuk PIHAK PERTAMA) dalam batas waktu pencairan selama 21 (dua puluh satu) hari kalender.</li>
                    <li>Tagihan selanjutnya setelah progres 100%, dibayarkan 95% dengan melampirkan bukti Serah Terima Pertama (BAST-1) dan Retensi 5 (lima) % diambil setelah masa pemeliharaan selama 90 hari kalender, dengan melampirkan Barita Acara kedua (BAST-2 )</li>
                  </ul>
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                  9.3
                </td>
                <td style="text-align: justify">
               PEKERJAAN PEMBANGUNAN PRASARANA KOTA  
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                  9.3.1
                </td>
                <td style="text-align: justify">
               Cara Pembayaran Khusus Untuk  perjanjian Pekerjaan Prasarana <br> 
               <ul>
                 <li>Tagihan pertama sebagai DP dapat ditagihkan setelah Surat Perintah Kerja ditandatangani oleh Kedua Belah Pihak. Sewaktu pengambilan Giro di bagian keuangan PEMBERI TUGAS, PENERIMA TUGAS harus menyerahkan bukti progres minimal yang dicapai atau dapat dicounter dengan Bank Garansi /Asuransi Bank Garansi senilai uang muka tersebut diatas (lembaga Asuransi yang ditunjuk PIHAK PERTAMA) dalam batas waktu pencairan selama 21 (dua puluh satu) hari kalender.</li>
                 <li>Tagihan tahapan pembayaran sesuai SPK dengan dikurangi retensi 5% (lima persen) dari nilai kontrak.</li>
                <li>Tagihan selanjutnya setelah progres 100%, dibayarkan 95% dengan melampirkan bukti Serah Terima Pertama (BAST-1) dan Retensi 5 (lima) % diambil setelah masa pemeliharaan selama 120 hari kalender, dengan melampirkan Barita Acara kedua (BAST-2 ).</li>
                <li>  Apabila disepakati ada Material on Site (MOS) maka harus dilampirkan BoQ yang menunjukkan unsur material dan upah, untuk  pengajuan BAP harus dicantumkan material MOS dan material terpasang.</li>
               </ul> 
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                  9.4
                </td>
                <td style="text-align: justify">
               PEKERJAAN LANSEKAP  
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                  9.4.1
                </td>
                <td style="text-align: justify">
               Cara Pembayaran Khusus Untuk SPK Pekerjaan Lansekap. <br>
               <ul>
                 <li>Pembayaran Pertama sebesar 50 % dari nilai kontrak akan dibayarkan apabila tanaman dan media tanam telah tertanam dengan baik </li>
                 <li>Pembayaran Kedua sebesar 40 % dari nilai kontrak akan dibayarkan apabila Berita Acara Serah Terima Pertama telah disetujui PIHAK PERTAMA (Catatan : Tanaman harus hidup sesuai hasil klarifikasi /foto pada saat tender atau sudah tumbuh tunas baru) </li>
                 <li>Pembayaran Ketiga sebesar 10 % dari nilai kontrak akan dibayarkan apabila Berita Acara Serah Terima Kedua telah disetujui PIHAK PERTAMA dengan masa pemeliharaan, selama 90 hari kalender.</li>
                 <li>Apabila diperlukan jaminan hidup di luar masa pemeliharaan untuk jenis pohon tertentu, akan dibicarakan pada saat klarifikasi tender.</li>
               </ul>  
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                  9.4.2
                </td>
                <td style="text-align: justify">
               Cara Pembayaran Khusus Untuk SPK Pekerjaan Keamanan. <br>
               <ul>
                 <li>PIHAK KEDUA  berhak mengajukan penagihan pada setiap pertengahan bulan berjalan, pada saat mengambil giro pembayaran PIHAK KEDUA diwajibkan menyertakan Berita Acara Pelaksanaan tugas penjagaan keamanan dan kenyamanan bulan dimaksud kepada bagian keuangan PIHAK PERTAMA</li>
                 <li>Pada Akhir SPK akan dibuatkan Berita Acara Penyelesaian Tugas Pekerjaan Keamanan</li>
               </ul>  
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                  9.4.3
                </td>
                <td style="text-align: justify">
               Cara Pembayaran Khusus Untuk SPK Pekerjaan Kebersihan. <br>
               <ul>
                 <li>PIHAK KEDUA  berhak mengajukan penagihan pada setiap pertengahan bulan berjalan, pada saat mengambil giro pembayaran PIHAK KEDUA diwajibkan menyertakan Berita Acara Penyelesaian Pekerjaan bulan dimaksud kepada bagian keuangan PIHAK PERTAMA</li>
                 <li>Pada Akhir SPK akan dibuatkan Berita Acara Penyelesaian Pekerjaan Kebersihan</li>
               </ul>  
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                  9.5
                </td>
                <td style="text-align: justify">
               PEKERJAAN KONSULTAN  DAN PERENCANAAN  
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                  9.5.1
                </td>
                <td style="text-align: justify">
               Cara Pembayaran Khusus Untuk SPK Pekerjaan Konsultan dan Perencanaan. <br>
               <ul>
                 <li>Tagihan pertama sebagai DP dapat ditagihkan setelah Surat Perintah Kerja ditandatangani oleh Kedua Belah Pihak.Sewaktu pengambilan Giro di bagian keuangan PIHAK PERTAMA, PIHAK KEDUA harus menyerahkan bukti progres minimal yang dicapai atau dapat dicounter dengan Bank Garansi /Asuransi Bank Garansi senilai uang muka tersebut diatas (lembaga Asuransi yang ditunjuk PIHAK PERTAMA) dalam batas waktu pencairan selama 21 (dua puluh satu) hari kalender.</li>
                 <li>Tagihan selanjutnya setelah progres 100%, dibayarkan dengan melampirkan bukti Berita Acara Serah Terima (BAST) </li>
               </ul>  
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                  9.6
                </td>
                <td style="text-align: justify">
               PEKERJAAN PROMOSI DAN EVENT  
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                  9.6.1
                </td>
                <td style="text-align: justify">
              Cara Pembayaran Khusus Untuk SPK Pekerjaan Promosi dan Event. <br>
              <ul>
                <li>Tagihan pertama sebagai DP dapat ditagihkan setelah Surat Perintah Kerja ditandatangani oleh Kedua Belah Pihak.Sewaktu pengambilan Giro di bagian keuangan PIHAK PERTAMA, PIHAK KEDUA harus menyerahkan bukti progres minimal yang dicapai atau dapat dicounter dengan Bank Garansi /Asuransi Bank Garansi senilai uang muka tersebut diatas (lembaga Asuransi yang ditunjuk PIHAK PERTAMA) dalam batas waktu pencairan selama 21 (dua puluh satu) hari kalender.</li>
                <li>Tagihan selanjutnya setelah progres 100%, dibayarkan dengan melampirkan bukti Berita Acara Serah Terima Pertama (BAST).</li>
              </ul>  
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <br/><br/>
                    <strong>PASAL 10<br/>
                    P   A   J   A   K</strong>
                    <br/><br/>
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                  10.1
                </td>
                <td style="text-align: justify">
                Ketentuan umum pajak <br>
               <ul>
                 <li>PIHAK KEDUA wajib memberitahukan kepada PIHAK PERTAMA tentang status perusahaan PKP atau tidak. Untuk SPK >  Satu Milyar wajib PKP </li>
                 <li>Sebelum pekerjaan dimulai PIHAK KEDUA wajib mengirimkan No. NPWP sebagai  bukti bahwa PIHAK KEDUA kena Pajak (PPN).</li>
                 <li>Pajak Pertambahan Nilai (PPN) sebesar 10% akan dikenakan kepada PIHAK KEDUA yang memiliki status PKP, untuk itu PIHAK KEDUA wajib mengirimkan surat pengukuhan PKP dan NPWP kepada PIHAK PERTAMA.</li>
                 <li>Pajak Penghasilan (PPh) akan dikenakan terhadap segala pekerjaan yang mengandung jasa dengan aturan sesuai dengan peraturan perpajakan yang berlaku</li>
               </ul>  <br>
               PIHAK KEDUA yang menggunakan nama perorangan (bukan PKP), selama 1 ( Satu ) tahun berjalan nilai komulatif SPK dibatasi maksimal sesuai aturan yang berlaku dari pemerintah, selebihanya tidak diperkenankan mendapatkan pekerjaan lagi dengan nama perorangan (bukan PKP).
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <br/><br/>
                    <strong>PASAL 11<br/>
                    PEKERJAAN TAMBAH/KURANG</strong>
                    <br/><br/>
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                  11.1
                </td>
                <td style="text-align: justify">
               PIHAK PERTAMA berhak menambah/mengurangi sebagian pekerjaan yang telah diberikan, dimana semua perintah untuk pekerjaan Tambah atau Kurang tersebut akan disampaikan melalui Surat Instruksi Kerja (SIK) yang ditandatangani oleh GM  dari PIHAK PERTAMA.   
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                  11.2
                </td>
                <td style="text-align: justify">
               PIHAK KEDUA tidak berhak untuk menambah/mengurangi pekerjaan tanpa persetujuan dari PIHAK PERTAMA.  
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                  11.3
                </td>
                <td style="text-align: justify">
               Setiap jenis pekerjaan yang tercantum dalam penawaran atau setiap jenis pekerjaan yang seharusnya ada dalam gambar atau spesifikasi teknis, tetapi tidak dikerjakan, maka akan dihitung  dan dianggap sebagai pekerjaan kurang.  
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                  11.4
                </td>
                <td style="text-align: justify">
               Apabila PIHAK KEDUA dalam menghitung penawaran membuat kelalaian dalam menghitung/memasukkan suatu jenis  pekerjaan atau pekerjaan yang menunjang pekerjaan tersebut, sedangkan dalam hal/dokumen tertulis telah disebutkan,dan khusus untuk pekerjaan MEP yang merupakan keharusan agar sistem dapat bekerja dengan baik/sempurna namun dalam perjanian tidak disebutkan secara terperinci, maka biaya tersebut tidak dapat dianggap dan bukan merupakan biaya tambah pekerjaan tersebut tetap dilaksanakan oleh dan atas biaya PIHAK KEDUA.  
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                  11.5
                </td>
                <td style="text-align: justify">
               Harga satuan pekerjaan SIK wajib memiliki nilai yang sama dengan harga satuan pekerjaan yang terlampir pada SPK (mengacu pada RAB Final). Untuk jenis pekerjaan yang belum ada harga satuannya akan ditentukan bersama para pihak sesuai dengan hasil klarifikasi dan negosiasi  para pihak  
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                  11.6
                </td>
                <td style="text-align: justify">
               PIHAK KEDUA wajib mengajukan pengajuan VO ( Variation Order ) kepada PIHAK PERTAMA paling lambat 14 (empat belas) hari kerja dari tanggal SIK diterima Pihak Kedua.  
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                  11.7
                </td>
                <td style="text-align: justify">
               Apabila dalam SIK tertera/terdapat pekerjaan yang tidak dapat dilaksanakan dalam waktu dekat dan/atau terdapat suatu atau beberapa hal yang menyebabkan pekerjaan tidak dapat dilaksanakan, maka PIHAK KEDUA wajib mengajukan surat tertulis atau melalui Form Tanggapan SIK kepada PIHAK PERTAMA, PIHAK PERTAMA akan memberikan tanggapan kembali , PIHAK KEDUA diberi toleransi waktu, pembatalan pekerjaan dan/atau hal-hal lain yang akan ditentukan kemudian.
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                  11.8
                </td>
                <td style="text-align: justify">
               Jika ada SIK pekerjaan Kurang maka PIHAK PERTAMA dalam hal ini Head of division Quantity Surveyor berhak langsung mengeluarkan VO setelah menerima SIK dari Head of division Construction dan diketahui oleh Head of Dept Teknik,. VO pekerjaan kurang ini dikeluarkan tanpa menunggu pengajuan Berita Acara dari PIHAK KEDUA  
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                  11.9
                </td>
                <td style="text-align: justify">
               VO kurang tetap diberlakukan walaupun perjanjian yang disepakati para pihak adalah lumpsum.  
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                  11.10
                </td>
                <td style="text-align: justify">
               Apabila terjadi force majeure atau waktu pekerjaan mundur disebabkan oleh  kesalahan dari PIHAK PERTAMA, maka dalam hal ini PIHAK KEDUA berhak mengajukan Eskalasi harga material sebatas progress pekerjaan yang belum dikerjakan oleh PIHAK KEDUA yang disesuaikan dengan jadwal pekerjaan (schedule) awal yang diajukan PIHAK KEDUA dan telah disetujui PIHAK PERTAMA. <br>
               Jika PIHAK KEDUA mengajukan eskalasi harga material dikarenakan waktu pekerjaan mundur yang disebabkan kesalahan PIHAK KEDUA (Kontraktor) maka pengajuan eskalasi harga tidak dapat disetujui oleh PIHAK PERTAMA.   
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                  11.11
                </td>
                <td style="text-align: justify">
               Tidak dibenarkan lagi sistem perhitungan Final Account termasuk tambah kurang diakhir pekerjaan saat dan atau setelah Serah Terima I. 
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <br/><br/>
                    <strong>PASAL 12<br/>
                    SERAH TERIMA PEKERJAAN</strong>
                    <br/><br/>
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                  12.1
                </td>
                <td style="text-align: justify">
               Sebelum PIHAK KEDUA menyerahkan pekerjaan kepada pihak pertama , segala kerusakan yang terjadi menjadi tanggung jawab PIHAK KEDUA, kecuali kerusakan yang disebabkan oleh force majeure.  
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                  12.2
                </td>
                <td style="text-align: justify">
               Permohonan check list Serah Terima I diajukan 4 (empat) minggu sebelum tanggal SPK berakhir atau progress fisik lapangan mencapai progress 95%.  
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                  12.3
                </td>
                <td style="text-align: justify">
               Syarat-syarat check list Serah Terima I & II yang disetujui PIHAK PERTAMA adalah sesuai dengan standar check list dari PIHAK PERTAMA (Form terlampir).  
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                  12.4
                </td>
                <td style="text-align: justify">
               Batas penyelesaian check list II adalah 2 (dua) minggu setelah check list I dilakukan. Apabila satu minggu setelah check list I PIHAK KEDUA belum menyelesaikan check list II, maka PIHAK PERTAMA akan menerbitkan Surat Peringatan I. dalam hal  PIHAK KEDUA masih belum menyelesaikan check list II dalam waktu 7 hari setelah Surat Peringatan I, maka PIHAK PERTAMA secara otomatis akan mengambil alih sisa pekerjaan dan seluruh biaya perbaikan dibebankan kepada PIHAK KEDUA.  
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                  12.5
                </td>
                <td style="text-align: justify">
               Syarat-syarat check list Serah Terima I dan II untuk Pekerjaan Lansekap yang disetujui PIHAK PERTAMA adalah sesuai dengan ketentuan-ketentuan yang telah disepakati pada saat klarifikasi dan negosiasi yang merupakan bagian yang tidak terpisahkan dari surat perjanjian ini atau standard PIHAK PERTAMA yang sudah diberikan kepada PIHAK KEDUA (foto terlampir).  
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                  12.6
                </td>
                <td style="text-align: justify">
               Waktu penandatanganan Berita Acara Serah Terima paling lambat sama dengan tanggal selesainya pekerjaan. Dalam hal tidak terdapatnya ijin perpanjangan waktu pelaksanaan dari PIHAK PERTAMA, maka tiap keterlambatan akan dikenai denda sesuai dengan ketentuan pasal perjanjian.  
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <br/><br/>
                    <strong>PASAL 13<br/>
                    MASA PEMELIHARAAN</strong>
                    <br/><br/>
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                  13.1
                </td>
                <td style="text-align: justify">
               Masa pemeliharaan ditentukan sebagai berikut : <br>
               Bangunan :  120 (seratus dua puluh) hari kalender sejak tanggal ST I disetujui PIHAK PERTAMA untuk perbaikan Fisik bangunan dan 365 (tiga ratus enam puluh lima) hari kalender atau satu kali musim hujan sejak tanggal ST-1 disetujui PIHAK PERTAMA untuk perbaikan bangunan bocor <br>
               Prasarana : 120 (seratus dua puluh) hari kalender sejak tanggal ST I disetujui PIHAK PERTAMA <br>
               Jaringan MEP : 120 (seratus dua puluh) hari kalender sejak tanggal ST I disetujui PIHAK PERTAMA <br>
               Lansekap   : 90 (sembilan puluh) hari kalender sejak tanggal ST I semua item pekerjaan yang tertera di SPK disetujui PIHAK PERTAMA <br>
               Siap Huni/Perbaikan komplain :  90 (sembilan puluh) hari kalender sejak tanggal ST I disetujui PIHAK PERTAMA  
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                  13.2
                </td>
                <td style="text-align: justify">
               PIHAK KEDUA wajib membersihkan lingkungan sekitar pekerjaan tersebut dari segala kotoran, dan/ atau sampai paling lambat pada saat Berita Acara Serah Terima I ditandatangani.  
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                  13.3
                </td>
                <td style="text-align: justify">
               Terhitung mulai ditandatanganinya Berita Acara Serah Terima I, PIHAK KEDUA wajib memelihara, memperbaiki dan menyempurnakan hasil pekerjaan. Untuk Pekerjaan bangunan, PIHAK KEDUA juga wajib memperbaiki kebocoran atap selama 1 (satu) masa musim hujan (365 hari kalender) dengan biaya dari PIHAK KEDUA  
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                  13.4
                </td>
                <td style="text-align: justify">
               Apabila PIHAK PERTAMA menganggap PIHAK KEDUA tidak mampu atau terlalu lambat dalam memperbaiki kerusakan tersebut, maka PIHAK PERTAMA berhak menunjuk PIHAK LAIN untuk memperbaiki dengan biaya perbaikan yang dibebankan pada PIHAK KEDUA.  
                </td>
            </tr>
              <tr>
                <td style="text-align: left">
                  13.5
                </td>
                <td style="text-align: justify">
              Apabila selama masa pemeliharaan terjadi serah terima antara PIHAK PERTAMA dengan PEMBELI, maka setiap komplain, baik dari PIHAK PERTAMA ataupun PEMBELI melalui PIHAK PERTAMA wajib diperbaiki secepatnya oleh PIHAK KEDUA sesuai dengan batas waktu yang ditentukan PIHAK PERTAMA  
                </td>
            </tr>
             <tr>
                <td style="text-align: left">
                  13.6
                </td>
                <td style="text-align: justify">
               Setelah masa pemeliharaan, PIHAK KEDUA wajib menyerahkan pekerjaan untuk yang kedua kalinya kepada PIHAK PERTAMA yang dinyatakan dalam Berita Acara Serah Terima II sesuai dengan pasal perjanjian.  
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <br/><br/>
                    <strong>PASAL 14<br/>
                    SANKSI</strong>
                    <br/><br/>
                </td>
            </tr>
             <tr>
                <td style="text-align: left">
                  14.1
                </td>
                <td style="text-align: justify">
               Apabila PIHAK KEDUA tidak dapat mengikuti tender yang sedang diselenggarakan oleh PIHAK PERTAMA, PIHAK KEDUA wajib mengembalikan surat undangan tender dengan mencoret kata berminat seperti contoh (tidak berminat untuk mengikuti tender). Dalam hal tidak dilakukan PIHAK KEDUA maka PIHAK PERTAMA akan memberikan peringatan sampai 2 (dua) kali, apabila peringatan tersebut diabaikan maka PIHAK PERTAMA akan memberikan sanksi untuk tidak mengundang Pihak Kedua tender selama 2 (dua) bulan mendatang disemua proyek Ciputra Group.  
                </td>
            </tr>
             <tr>
                <td style="text-align: left">
                  14.2
                </td>
                <td style="text-align: justify">
               PIHAK KEDUA setelah ditunjuk dan/atau menerima Surat Perintah Kerja kemudian mengundurkan diri terhadap pekerjaan tersebut, maka pihak kedua akan diberikan sanksi tidak akan diikutsertakan dalam tender proyek Ciputra Group selama periode 6 (enam) bulan mendatang.  
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                  14.3
                </td>
                <td style="text-align: justify">
               Apabila PIHAK KEDUA sudah melaksanakan sebagian pekerjaan di lapangan kemudian mengundurkan diri terhadap pekerjaan tersebut (tidak bersedia menyelesaikan), maka akan diberikan sanksi tidak akan diikutsertakan dalam tender proyek Ciputra Group selama periode 1 (satu) tahun mendatang dan dikenakan denda sebesar nilai retensi terhadap nilai kontrak awal sesuai SPK.  
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                  14.4
                </td>
                <td style="text-align: justify">
               Apabila dalam pelaksanaan ada keterlambatan progress  10% (sepuluh persen) dari rencana maka PIHAK PERTAMA akan mengeluarkan SP-1 ( Surat Peringatan pertama ) kepada PIHAK KEDUA .Apabila keterlambatan progress sudah mencapai 20% (dua puluh persen) dari rencana maka PIHAK PERTAMA akan mengeluarkan SP-2 ( Surat Peringatan kedua ) kepada PIHAK KEDUA dengan secara otomatis pekerjaan dapat ambil alih dan untuk selanjutnya  PIHAK PERTAMA berhak mengalihkan pekerjaan kepada pihak lain dengan segala konsekuensi biaya ditanggung oleh PIHAK KEDUA.  
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                  14.5
                </td>
                <td style="text-align: justify">
               Apabila terjadi keterlambatan penyerahan pekerjaan yang diakibatkan oleh kesalahan PIHAK KEDUA, maka PIHAK KEDUA akan dikenakan denda sebesar 1‰ (satu permil) per hari kalender dari harga total perjanjian sampai dengan 60 hari kalender, dan untuk selanjutnya  PIHAK PERTAMA berhak mengalihkan pekerjaan tsb kepada pihak lain dengan segala konsekuensi biaya ditanggung oleh PIHAK KEDUA.  
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                  14.6
                </td>
                <td style="text-align: justify">
               Jika PIHAK KEDUA dalam proses pembayaran tidak menepati kesepakatan pembayaran dengan supplier (PIHAK KETIGA) yang ditunjuk PIHAK PERTAMA dengan berdasarkan progress yang telah dicapai, maka PIHAK PERTAMA berhak memotong tagihan PIHAK KEDUA secara langsung tanpa harus mendapat ijin atau surat kuasa dari PIHAK KEDUA dan langsung membayarkan kepada PIHAK KETIGA tsb  
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                  14.7
                </td>
                <td style="text-align: justify">
               Apabila terjadi kerusakan aset, lingkungan, kebersihan dan gangguan ketertiban/keamanan lingkungan akibat pelaksanaan pekerjaan maupun oleh pekerja PIHAK KEDUA, maka PIHAK PERTAMA akan memberikan sanksi kepada PIHAK KEDUA sesuai dengan kerusakan /gangguan yg di akibatkan dengan menghentikan segala pekerjaan kegiatan pihak Kedua  dan pemberian sanksi lain yang telah ditentukan.   
                </td>
            </tr>
             <tr>
                <td style="text-align: left">
                  14.8
                </td>
                <td style="text-align: justify">
               PIHAK KEDUA tidak akan diikutkan tender disemua proyek Ciputra Group selama 6 (enam) bulan apabila diketahui dari tawaran terjadi kecurangan manipulasi atas data yang dilakukan oleh PIHAK KEDUA, tidak kemudian mengurangi, mengubah spesifikasi dibawah spesifikasi yang ditentukan, Dalam hal Pihak kedua melakukan kecurangan yang lebih berat dan tidak dapat ditolerir oleh pihak pertama, pihak kedua akan dicatat dalam daftar hitam dan tidak akan dipakat lagi sebagai kontraktor diseluruh proyek CIPUTRA GRUP  
                </td>
            </tr>
             <tr>
                <td style="text-align: left">
                  14.9
                </td>
                <td style="text-align: justify">
               Apabila ada kerusakan struktur karena PIHAK KEDUA tidak melaksanakan pekerjaan sesuai dengan gambar dan spesifikasi yang telah ditentukan, maka semua biaya yang timbul akan dibebankan ke PIHAK KEDUA.  
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <br/><br/>
                    <strong>PASAL 15<br/>
                    KESELAMATAN DAN KEAMANAN KERJA LINGKUNGAN</strong>
                    <br/><br/>
                </td>
            </tr>
             <tr>
                <td style="text-align: left">
                  15.1
                </td>
                <td style="text-align: justify">
               PIHAK KEDUA wajib bertanggung jawab atas keselamatan dan keamanan kerja lingkungan pekerjanya, termasuk pekerja yang diperbantukan sesuai dengan peraturan ketenagakerjaan yang berlaku di Indonesia.   
                </td>
            </tr>
             <tr>
                <td style="text-align: left">
                  15.2
                </td>
                <td style="text-align: justify">
               PIHAK KEDUA wajib menyediakan perlengkapan (P3K) dalam keadaan lengkap dan bisa digunakan, air minum tempat buang air untuk pekerjanya serta alat pemadam kebakaran, yang penempatan / lokasi wajib mendapat persetujuan dari PIHAK PERTAMA.  
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <br/><br/>
                    <strong>PASAL 16<br/>
                        KEBERSIHAN LINGKUNGAN </strong>
                    <br/><br/>
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                  16.1
                </td>
                <td style="text-align: justify">
               PIHAK KEDUA wajib menjaga kebersihan dan keamanan lingkungan kerja.   
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                  16.2
                </td>
                <td style="text-align: justify">
               Semua bahan material dan peralatan kerja yang digunakan oleh PIHAK KEDUAuntuk melakukan pekerjaan tidak diperbolehkan di badan jalan toleransi peletakan material dapat di bolrhkan setelah berm jalan.  
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                  16.3
                </td>
                <td style="text-align: justify">
               PIHAK KEDUA dilarang membuat warung-warung makanan atau minuman di lokasi pekerjaan. Dalam hal ada pengecualian maka semua penempatan warung diatur oleh PIHAK PERTAMA  atas permohonan tertulis dari PIHAK KEDUA.  
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                  16.4
                </td>
                <td style="text-align: justify">
              PIHAK KEDUA wajib menjaga kebersihan lapangan dan pembuangan sampah setiap hari, sejak dimulainya pekerjaan sampai serah terima pertama.segala biaya kebersihan menjadi tanggungjawab PIHAK KEDUA. Apabila PIHAK KEDUA tidak melaksanakan kewajibannya untuk menjaga kebersihan maka PIHAK PERTAMA berhak mengambil tindakan Untuk menggunakan perusahaan jasa dengan dibebankan kepada PIHAK KEDUA.  
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <br/><br/>
                    <strong>PASAL 17<br/>
                        RESIKO PEMBORONG </strong>
                    <br/><br/>
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                  17.1
                </td>
                <td style="text-align: justify">
               Segala resiko bahaya kebakaran dan hal-hal lain yang menimbulkan kerugian pada hasil pelaksanaan, bahan, peralatan, dan lain-lain, maupun yang  berdampak pada  kerusakan dan kebakaran bangunan sekitar pekerjan akibat dari kelalaian dari proses pekerjaan PIHAK KEDUA akan menjadi tanggung jawab PIHAK KEDUA.  
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                  17.2
                </td>
                <td style="text-align: justify">
               PIHAK KEDUA diasumsikan telah memperhitungkan kemungkinan perubahan harga bahan ataupun upah kerja selama waktu konstruksi pekerjaan, sehingga  segala harga yang telah disepakati mengikuti para pihak selama jangka perjanjian waktu dalam keadaan apapun hal tersebut menjadi resiko PIHAK KEDUA (tidak ada tuntutan kenaikan harga). 
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <br/><br/>
                    <strong>PASAL 18<br/>
                        FORCE MAJEURE </strong>
                    <br/><br/>
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                  18.1
                </td>
                <td style="text-align: justify">
               Apabila terjadi keterlambatan penyerahan pekerjaan yang disebabkan keadaan FORCE MAJEURE seperti :  pemogokan, peperangan, devaluasi, huru-hara, gempa bumi, banjir, tanah longsor, Peraturan Pemerintah dalam bidang moneter dan lain kejadian di luar jangkauan manusia, maka perjanjian akan ditinjau kembali oleh para belah pihak. Kejadian-kejadian yang terjadi akibat kesalahan PIHAK PERTAMA bukan termasuk force majeure tapi akan dibahas bersama untuk mendapat penyelesaian secara mufakat.  
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                  18.2
                </td>
                <td style="text-align: justify">
               Keterlambatan/sulitnya material, masalah perijinan, hubungan dan/atau maksud-maksud lain yang ada kaitannya dengan instansi-instansi adalah tidak termasuk force majeure  
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                  18.3
                </td>
                <td style="text-align: justify">
               PIHAK KEDUA harus memberitahukan kepada PIHAK PERTAMA secara tertulis dalam waktu 7 (tujuh) hari setelah terjadinya force majeure dan pada waktu force majeure tersebut berakhir.  
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <br/><br/>
                    <strong>PASAL 19<br/>
                    PEMBATALAN KONTRAK</strong>
                    <br/><br/>
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                  19.1
                </td>
                <td style="text-align: justify">
               Dalam hal  PIHAK PERTAMA memberikan Peringatan kepada PIHAK KEDUA sebayak 3 (tiga) kali dan dalam waktu 7 (tujuh) hari sejak dikeluarkannya/diterimanya Peringatan ketiga, PIHAK PERTAMA berhak secara sepihak dapat membatalkan perjanjian apabila PIHAK KEDUA :  
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                  19.1.1
                </td>
                <td style="text-align: justify">
               Dalam waktu 1 (satu) bulan berturut-turut tidak melanjutkan pekerjaan yang terhenti.  
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                  19.1.2
                </td>
                <td style="text-align: justify">
               Secara langsung atau tidak langsung dengan sengaja memperlambat penyelesaian pekerjaan dan atau memberikan keterangan yang tidak benar sehingga merugikan PIHAK PERTAMA.  
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                  19.1.3
                </td>
                <td style="text-align: justify">
               Memberikan sebagian maupun seluruh pekerjaan kepada PIHAK LAIN tanpa persetujuan tertulis dari PIHAK PERTAMA.  
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                  19.1.4
                </td>
                <td style="text-align: justify">
               Telah dicabut Surat Ijin Usaha Jasa Konstruksi (SIUJK) untuk bidang yang bersangkutan untuk sementara atau untuk selama-lamanya.  
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                  19.2
                </td>
                <td style="text-align: justify">
               Apabila terjadi pembatalan kontrak, maka performance bond (jika ada Jaminan ) dinyatakan hangus dan sepenuhnya menjadi milik PIHAK PERTAMA.  
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                  19.3
                </td>
                <td style="text-align: justify">
               PIHAK PERTAMA akan melakukan pembayaran kepada PIHAK KEDUA sejumlah nilai pekerjaan yang telah dilaksanakan oleh PIHAK KEDUA berdasarkan perjanjian sampai dengan dilakukannya pembatalan perjanjian dan sejumlah nilai untuk material/bahan yang berada di lapangan sesuai dengan penilaian PIHAK PERTAMA.  
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <br/><br/>
                    <strong>PASAL 20<br/>
                    PERSELISIHAN</strong>
                    <br/><br/>
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                  20.1
                </td>
                <td style="text-align: justify">
               Apabila terjadi perselisihan dalam melaksanakan pekerjaan seperti dimaksud dalam pasal  perjanjian, maka para pihak sepakat untuk diselesaikan dengan cara musyawarah antara para pihak.  
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                  20.2
                </td>
                <td style="text-align: justify">
               Apabila terjadi perselisihan sesuai pasal 20 ayat 1 perjanjian, pada saat diselesaikan dengan cara musyawarah antara para pihak, maka diambil keputusan berdasarkan urutan utama kontrak sebagai berikut: <br>
               1. Risalah Rapat ( Risalah Tanya Jawab negosiasi final, Risalah Klarifikasi, Risalah Aanwijzing ) <br>
              2. Gambar For Tender dan Gambar for Construction <br>
              3. Spesifikasi Teknis <br>
              Dalam hal terjadi perbedaan notasi spesifikasi teknis di dalam berkas gambar, maka para pihak setuju dan sepakat  untuk memakai spesifikasi dengan notasi terbanyak.
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                  20.3
                </td>
                <td style="text-align: justify">
               dalam hal perselisihan ini tidak dapat diselesaikan secara musyawarah mufakat, maka perselisihan tersebut akan diselesaikan melalui Badan Abitrase Nasional Indonesia (BANI) dengan ketentuan menurut panitia Arbitrase yang terdiri dari wakil PIHAK PERTAMA dan wakil PIHAK KEDUA sebagai anggota dengan PIHAK KETIGA sebagai AHLI yang bertindak sebagai KETUA yang ditunjuk oleh para pihak.  
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                  20.4
                </td>
                <td style="text-align: justify">
               Keputusan yang diambil oleh panitia Arbitrase ini adalah menjadi keputusan akhir dan mengikat bagi para pihak.  
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                  20.5
                </td>
                <td style="text-align: justify">
               Dalam Hal  hasil putusan panitia Arbitrase seperti dimaksud dalam ayat 4 pasal ini tidak dilaksanakan dengan baik, maka putusan panitia Arbitrase tersebut.  
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                  20.6
                </td>
                <td style="text-align: justify">
               Semua biaya yang telah dikeluarkan untuk menyelesaikan perselisihan akan ditanggung bersama oleh para pihak.  
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <br/><br/>
                    <strong>PASAL 21<br/>
                    LAIN - LAIN</strong>
                    <br/><br/>
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                  21.1
                </td>
                <td style="text-align: justify">
               Apabila terdapat  ketidaksamaan atau perbedaan isi ketentuan-ketentuan dalam perjanjian dengan syarat-syarat administrasi pelaksanaan sebelumnya, maka  para pihak sepakat untuk menyatukan isi ketentuan-ketentuan dalam perjanjian ini merupakan ketentuan yang berlaku dan mengikat bagi para pihak.  
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                  21.2
                </td>
                <td style="text-align: justify">
               Segala sesuatu yang belum tercantum dalam syarat-syarat umum perjanjian pemborongan pekerjaan ini akan ditetapkan kemudian oleh kedua belah pihak di dalam Addendum perjanjian yang merupakan bagian yang tidak terpisahkan dari perjanjian pemborongan pekerjaan ini.   
                </td>
            </tr>
             <tr>
                <td style="text-align: left">
                  21.3
                </td>
                <td style="text-align: justify">
               Syarat-syarat Umum Perjanjian Pemborongan ini akan berakhir apabila PIHAK KEDUA sudah tidak berminat bekerjasama dengan PIHAK PERTAMA dan/atau PIHAK KEDUA diputus secara sepihak oleh PIHAK PERTAMA karena tidak bisa memenuhi syarat syarat  perjanjian di atas.  
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <br/><br/>
                    <strong>PASAL 22<br/>
                    P   E   N   U   T   U   P</strong>
                    <br/><br/>
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                  22.1
                </td>
                <td style="text-align: justify">
               Dengan maksud dan tujuan baik, agar dapat dilaksanakan oleh kedua belah pihak, kontrak ini dibuat dalam rangkap 2 (dua), bermeterai cukup dan ditandatangani, mempunyai kekuatan hukum yang sama serta mulai berlaku sejak ditandatangani oleh PIHAK PERTAMA dan PIHAK KEDUA.  
                </td>
            </tr>        
            <h1>&nbsp;</h1>    
            {{-- <tr>
                <td colspan="2">
                    @if($supp->pt->city != null)
                        <ol style="text-align:right;"> {{$supp->pt->city->name}}, {{$tanggal}} </ol>
                    @else
                        <ol style="text-align:right;"> {{$tanggal}} </ol>
                    @endif
                </td>
            </tr> --}}
            <tr>
                <td colspan="2">
                    <table width="100%" style="width: 100%;font-size:14px;">
                        <tr>
                            <td colspan="2"> 
                            @if($supp->pt->city != null)
                                <ol style="text-align:right;"> {{$supp->pt->city->name}}, {{$tanggal}} </ol>
                            @else
                                <ol style="text-align:right;"> {{$tanggal}} </ol>
                            @endif
                            </td>
                        </tr>
                        <tr>
                            <td style="text-transform: uppercase;vertical-align: top;">
                                <center><strong><span>PIHAK KEDUA</span></strong></center>
                                <center><span>{{ strtoupper($supp->rekanan_group->name) }}</span></span>
                            </td>
                            <td>
                                <strong><center>PIHAK PERTAMA</strong></center>
                                <span><center>{{ strtoupper($supp->pt->name) }}</center></span>
                            </td>
                        </tr> 
                        <tr>
                            <td style="width: 50%;">
                                <h1>&nbsp;</h1>
                                <h1>&nbsp;</h1>
                                <center><span><u>{{ strtoupper($supp->rekanan_group->cp_name)}}</u><br></span></center>
                                <center><span><strong>{{ $supp->rekanan_group->cp_jabatan or '-' }}</strong></span></center>
                            </td>
                            <td style="width: 50%;">
                                <h1>&nbsp;</h1>
                                <h1>&nbsp;</h1>
                                <center><span><u>{{ strtoupper($user_ttd) }}</u><br></span></center>
                                <center><span><strong>{{ $user_ttd_jabatan->name }}</strong></span></center>
                            </td>
                        </tr>                 
                    </table>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>