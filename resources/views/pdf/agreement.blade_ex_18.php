<!DOCTYPE html>

<html>

    <!DOCTYPE html>
    <html>

        <head>
            <meta charset="utf-8">
            <title>Perjanjian Kerjasama - {{ $agreement->agreement_number }}</title>
            <style>
                /* ✅ PERBAIKAN UTAMA DI SINI */

                /* 1. Definisikan font kustom menggunakan @font-face */
                @font-face {
                    font-family: 'Bookman Old Style';
                    src: url('{{ storage_path('fonts/bookmanoldstyle.ttf') }}') format('truetype');
                    font-weight: normal;
                    font-style: normal;
                }

                @font-face {
                    font-family: 'Bookman Old Style';
                    src: url('{{ storage_path('fonts/bookmanoldstyle_bold.ttf') }}') format('truetype');
                    font-weight: bold;
                    font-style: normal;
                }

                /* 2. Terapkan font ke seluruh dokumen */
                body {
                    font-family: 'Bookman Old Style', serif;
                    margin: 1.5cm;
                    font-size: 11pt;
                    line-height: 1.5;
                }

                /* (Sisa dari CSS Anda tidak perlu diubah) */
                .container {
                    width: 100%;
                    padding: 0px;
                    box-sizing: border-box;
                }

                .cover-page {
                    text-align: center;
                    height: 100vh;
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    align-items: center;
                    padding: 0 60px;
                    box-sizing: border-box;
                }

                .cover-page .cover-logo-container {
                    width: 100%;
                    text-align: center;
                    margin-bottom: 50px;
                }

                .cover-page .cover-logo-container img {
                    height: 100px;
                    width: auto;
                    display: inline-block;
                    margin: 0 30px;
                    vertical-align: middle;
                }

                .cover-page h1,
                .cover-page h2,
                .cover-page h3,
                .cover-page p {
                    font-weight: bold;
                    line-height: 1.5;
                    margin-bottom: 10px;
                    text-align: center;
                }

                .cover-page h1 {
                    font-size: 20pt;
                }

                .cover-page h2 {
                    font-size: 18pt;
                }

                .cover-page h3 {
                    font-size: 16pt;
                }

                .cover-page .cover-year {
                    margin-top: 50px;
                    font-size: 14pt;
                }

                .content {
                    line-height: 1.6;
                    text-align: justify;
                }

                .section-title {
                    text-align: center;
                    font-weight: bold;
                    margin-top: 25px;
                    margin-bottom: 15px;
                    font-size: 12pt;
                    text-transform: uppercase;
                }

                .paragraph {
                    margin-bottom: 10px;
                    text-indent: 0;
                }

                .list-item {
                    margin-bottom: 5px;
                }

                .indent {
                    margin-left: 30px;
                    padding-left: 0;
                }

                .signature-block {
                    margin-top: 50px;
                    width: 100%;
                    display: table;
                    table-layout: fixed;
                }

                .signature-column {
                    display: table-cell;
                    width: 50%;
                    text-align: center;
                }

                .signature-column.left {
                    text-align: left;
                    padding-left: 50px;
                }

                .signature-column.right {
                    text-align: right;
                    padding-right: 50px;
                }

                .signature-line {
                    margin-top: 60px;
                    border-bottom: 1px solid black;
                    width: 200px;
                    margin-left: auto;
                    margin-right: auto;
                }

                .page-break {
                    page-break-after: always;
                }

                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-top: 10px;
                }

                table,
                th,
                td {
                    border: 1px solid black;
                    padding: 8px;
                    text-align: left;
                }

                .no-border,
                .no-border td {
                    border: none;
                }

                .no-border td {
                    padding: 2px 0;
                }

                .text-center {
                    text-align: center;
                }

                .font-bold {
                    font-weight: bold;
                }

                .underline {
                    text-decoration: underline;
                }
            </style>

        </head>

        <body>
            {{-- Halaman Sampul --}}
            <div class="cover-page">
                <div class="cover-logo-container">
                    <img src="{{ public_path('assets/images/pekanbaru.png') }}" alt="Logo Kota Pekanbaru">
                    <img src="{{ public_path('assets/images/dishub.png') }}" alt="Logo Dishub">
                </div>
                <h1>KONTRAK PERJANJIAN KERJASAMA</h1>
                <h2>PERJANJIAN KERJASAMA</h2>
                <h3>ANTARA</h3>
                <h3>DINAS PERHUBUNGAN KOTA PEKANBARU</h3>
                <h3>DENGAN</h3>
                <h3>MITRA KERJASAMA PERPARKIRAN</h3>
                <h3>TENTANG</h3>
                <h3>KONTRAK KERJASAMA PENGELOLAAN PERPARKIRAN DI KOTA PEKANBARU</h3>
                <p>PEKANBARU</p>
                <p class="cover-year">TAHUN {{ $agreement->signed_date->format('Y') }}</p>
            </div>

            <div class="page-break"></div> {{-- Memaksa halaman baru setelah sampul --}}

            {{-- Konten Perjanjian (Mulai dari halaman 2) --}}
            <div class="container">
                <div class="content">
                    <p class="section-title">PERJANJIAN KERJASAMA</p>
                    <p class="text-center">Nomor : {{ $agreement->agreement_number }}</p>

                    {{-- Format tanggal Indonesia --}}
                    <p>Pada hari ini {{ $agreement->signed_date->translatedFormat('l') }} tanggal
                        {{ Carbon\Carbon::parse($agreement->signed_date)->isoFormat('D MMMM YYYY') }}, Kami yang
                        bertanda
                        tangan di bawah ini :</p>

                    <table class="no-border">
                        <tr>
                            <td style="width: 5%; vertical-align: top;">I</td>
                            <td style="width: 20%; vertical-align: top;">Nama</td>
                            <td style="width: 5%; vertical-align: top;">:</td>
                            <td style="width: 70%; vertical-align: top;">{{ $agreement->leader->user->name ?? 'N/A' }}
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>Jabatan</td>
                            <td>:</td>
                            <td>Kepala UPT. Perparkiran</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>Alamat</td>
                            <td>:</td>
                            <td>Jl. Abdul Rahman hamid Komp. Perkantoran tenayan raya Gedung B9 Lt. I dan II Kec.
                                Tenayan
                                Raya</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td colspan="3">Bertindak dalam jabatannya tersebut, untuk dan atas nama Pemerintah Kota
                                Pekanbaru selanjutnya disebut PIHAK PERTAMA.</td>
                        </tr>
                    </table>

                    <table class="no-border" style="margin-top: 15px;">
                        <tr>
                            <td style="width: 5%; vertical-align: top;">II</td>
                            <td style="width: 20%; vertical-align: top;">Nama</td>
                            <td style="width: 5%; vertical-align: top;">:</td>
                            <td style="width: 70%; vertical-align: top;">
                                {{ $agreement->fieldCoordinator->user->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>Jabatan</td>
                            <td>:</td>
                            <td>{{ $agreement->fieldCoordinator->position ?? 'Mitra Kerjasama Pengelolaan Perparkiran' }}
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>Alamat</td>
                            <td>:</td>
                            <td>{{ $agreement->fieldCoordinator->address ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>NIK</td>
                            <td>:</td>
                            <td>{{ $agreement->fieldCoordinator->id_card_number ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>No. Telepon</td>
                            <td>:</td>
                            <td>{{ $agreement->fieldCoordinator->phone_number ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td colspan="3">Bertindak dalam jabatannya tersebut, untuk dan atas nama berkedudukan
                                sebagai
                                mitra kerjasama selanjutnya disebut PIHAK KEDUA.</td>
                        </tr>
                    </table>

                    <p style="margin-top: 15px;">PIHAK PERTAMA dan PIHAK KEDUA (secara bersama-sama untuk selanjutnya
                        disebut PARA PIHAK) menerangkan terlebih dahulu sebagai berikut:</p>
                    <p class="paragraph">Bahwa untuk mengoptimalkan pelayanan parkir di dalam ruang milik jalan dalam
                        wilayah Kota Pekanbaru, PIHAK PERTAMA bermaksud untuk melakukan kerjasama Pengelolaan
                        Perparkiran di
                        Kota Pekanbaru.</p>
                    <p class="paragraph">Bahwa PIHAK KEDUA merupakan Mitra Kerjasama Pengelolaan Perparkiran dan
                        bermaksud
                        untuk melakukan kerjasama Pengelolaan Perparkiran di wilayah Kota Pekanbaru, diterima baik oleh
                        PIHAK PERTAMA.</p>
                    <p class="paragraph">Berdasarkan hal-hal yang diuraikan tersebut diatas, maka PARA PIHAK setuju dan
                        mufakat serta berkomitmen untuk membuat Perjanjian Kerjasama tentang Kerjasama Pengelolaan
                        Perparkiran di wilayah Kota Pekanbaru dengan ketentuan-ketentuan sebagai berikut:</p>

                    <p class="section-title">Pasal 1<br>Ruang Lingkup</p>
                    <p class="paragraph">Ruang lingkup Perjanjian Kerjasama ini adalah meliputi :</p>
                    <ol class="indent">
                        <li class="list-item">Pengelolaan manajemen dan kegiatan layanan parkir di dalam ruang milik
                            jalan
                            pada titik/segmen yang telah ditentukan.</li>
                        <li class="list-item">Pemungutan tarif jasa layanan perparkiran didalam ruang milik jalan.</li>
                    </ol>

                    <p class="section-title">Pasal 2<br>Objek</p>
                    <p class="paragraph">Objek Perjanjian Kerjasama ini adalah dalam rangka pelaksanaan kewenangan PIHAK
                        PERTAMA yaitu Kerjasama Pengelolaan Perparkiran di dalam ruang milik jalan pada Wilayah Kota
                        Pekanbaru yang dikuasai PIHAK PERTAMA yaitu:</p>
                    @foreach ($agreement->activeParkingLocations->groupBy('roadSection.name') as $roadSectionName => $locations)
                        <p class="paragraph font-bold">{{ chr(64 + $loop->iteration) }}. Segmen lokasi parkir di Jalan
                            {{ $roadSectionName }}, pada titik lokasi:</p>
                        <ol class="indent">
                            @foreach ($locations as $location)
                                <li class="list-item">{{ $location->name }};</li>
                            @endforeach
                        </ol>
                    @endforeach

                    <p class="section-title">Pasal 3<br>Jangka Waktu</p>
                    <p class="paragraph">Perjanjian Kerjasama ini berlaku untuk jangka waktu
                        {{ $agreement->start_date->diffInMonths($agreement->end_date) }}
                        ({{ \App\Helpers\NumberToWords::convert($agreement->start_date->diffInMonths($agreement->end_date)) }})
                        bulan terhitung sejak ditandatanganinya Perjanjian Kerjasama ini, yaitu tanggal
                        {{ Carbon\Carbon::parse($agreement->start_date)->isoFormat('D MMMM YYYY') }} s/d
                        {{ Carbon\Carbon::parse($agreement->end_date)->isoFormat('D MMMM YYYY') }}.</p>

                    <p class="section-title">Pasal 4<br>Pembayaran</p>
                    <p class="paragraph">Pendapatan layanan parkir sebagaimana yang sudah ditetapkan dan tertuang
                        didalam
                        kontrak perjanjian kerjasama ini dengan jumlah setoran sebesar Rp.
                        {{ number_format($agreement->daily_deposit_amount, 0, ',', '.') }}
                        ({{ \App\Helpers\NumberToWords::convert(round($agreement->daily_deposit_amount)) }} Rupiah) /
                        hari
                        dan disetorkan langsung ke rekening pendapatan kas BLUD Perparkiran dengan nomor rekening :
                        _______________ (BRI) atas nama BLUD PERPARKIRAN KOTA PEKANBARU dan bukti penyetoran disampaikan
                        ke
                        bendahara penerimaan untuk dilakukan validasi.</p>
                    <p class="paragraph">PIHAK KEDUA wajib menyerahkan dana jaminan pelaksanaan sebesar jumlah nilai
                        setoran
                        selama jangka waktu kerjasama ini dilaksanakan;</p>
                    <p class="paragraph">Jumlah Setoran dana sebagaimana dimaksud pada ayat (2) harus disetorkan sebelum
                        pelaksanaan kerjasama dimulai;</p>
                    <p class="paragraph">Apabila terjadi keterlambatan penyetoran/kekurangan penyetoran maka PIHAK
                        PERTAMA
                        berhak melakukan pemotongan uang jaminan secara sepihak sebesar kekurangan setoran yang
                        ditetapkan
                        ke kas penampungan BLUD.</p>
                    <p class="paragraph">Apabila PIHAK KEDUA tidak menyetorkan kewajiban 3 hari berturut-turut pertama
                        maka
                        PIHAK PERTAMA memberikan surat teguran tertulis I dan di ikuti penarikan setoran, selanjutnya
                        dalam
                        3 hari berturut-turut kedua masih juga tidak dilakukan penyetoran maka dapat diberikan surat
                        teguran
                        II, dan dalam 3 hari berturut-turut ketiga tetap tidak melakukan penyetoran maka PIHAK PERTAMA
                        memberikan surat teguran III sekaligus dengan pemutusan kerjasama;</p>
                    <p class="paragraph">Dalam hal PIHAK KEDUA telah melakukan penyetoran kewajiban dalam jangka waktu 6
                        (enam) hari berturut-turut dengan lancar maka surat teguran I dinyatakan tidak berlaku dengan
                        sendirinya;</p>
                    <p class="paragraph">Selanjutnya PIHAK KEDUA telah melakukan penyetoran kewajiban dalam jangka waktu
                        2
                        (dua) minggu berturut-turut dengan lancar maka surat teguran II dinyatakan gugur dengan
                        sendirinya.
                    </p>

                    {{-- ... Tambahkan Pasal 5 hingga Pasal 17 sesuai dokumen Anda ... --}}
                    <p class="section-title">Pasal 5<br>Hak dan Kewajiban PIHAK PERTAMA</p>
                    <p class="paragraph">PIHAK PERTAMA berhak :</p>
                    <ol class="indent">
                        <li class="list-item">Memperoleh setoran tarif layanan parkir sebesar : Rp.
                            {{ number_format($agreement->daily_deposit_amount, 0, ',', '.') }}.-
                            ({{ \App\Helpers\NumberToWords::convert(round($agreement->daily_deposit_amount)) }} Rupiah)
                            /hari.</li>
                        <li class="list-item">Melakukan pengawasan langsung atas pengelolaan dan pelayanan perparkiran
                            yang
                            dilaksanakan oleh PIHAK KEDUA;</li>
                        <li class="list-item">Memutuskan Kontrak Kerjasama ini apabila PIHAK KEDUA dianggap tidak cakap
                            dan
                            dalam penilaian maka PIHAK PERTAMA kerjasama oprasional perparkiran tidak dapat
                            dilaksanakan.
                        </li>
                    </ol>
                    <p class="paragraph">PIHAK PERTAMA berkewajiban :</p>
                    <ol class="indent">
                        <li class="list-item">Menentukan dan menetapkan wilayah parkir yang akan dikelola oleh PIHAK
                            KEDUA;
                        </li>
                        <li class="list-item">Memberikan data wilayah parkir yang akan dikelola oleh PIHAK KEDUA untuk
                            dapat
                            melakukan pengelolaan dan pelayanan parkir sesuai kewenangan PIHAK KEDUA;</li>
                        <li class="list-item">Melakukan pengawasan terhadap pengelolaan dan pelayanan parkir oleh PIHAK
                            KEDUA.</li>
                    </ol>

                    <p class="section-title">Pasal 6<br>Hak dan Kewajiban PIHAK KEDUA</p>
                    <p class="paragraph">PIHAK KEDUA berhak :</p>
                    <ol class="indent">
                        <li class="list-item">Menerima data wilayah parkir yang diserahkan oleh PIHAK PERTAMA</li>
                        <li class="list-item">Mengelola dan melakukan pelayanan parkir pada wilayah parkir yang telah
                            disepakati bersama oleh PARA PIHAK;</li>
                        <li class="list-item">Memperoleh keuntungan dari pengelolaan dan pelayanan parkir yang telah
                            disepakati bersama oleh PARA PIHAK;</li>
                    </ol>
                    <p class="paragraph">PIHAK KEDUA berkewajiban :</p>
                    <ol class="indent">
                        <li class="list-item">Melaksanakan tugas pengelolaan parkir sesuai Peraturan Perundang –
                            undangan
                            yang berlaku;</li>
                        <li class="list-item">Melakukan Pemungutan Jasa Layanan Perparkiran sesuai tarif yang telah
                            ditentukan pada Peraturan Wali Kota Pekanbaru Nomor 02 Tahun 2025 tentang Peninjauan Tarif
                            Retribusi Jasa Umum atas Pelayanan Parkir di Tepi Jalan Umum;</li>
                        <li class="list-item">Menyetorkan hasil pungutan jasa layanan perparkiran setiap hari (1x24) jam
                            secara non tunai ke rekening Kas BLUD UPT Perparkiran melalui Bendahara Penerimaan BLUD UPT
                            Perparkiran Dinas Perhubungan Kota Pekanbaru;</li>
                        <li class="list-item">Melaksanakan jasa layananan perparkiran sesuai dengan Standar Pelayanan
                            Minimal (SPM) berdasarkan Peraturan Walikota Nomor 132 tahun 2020 tentang Standar Pelayanan
                            Minimal Unit Pelaksana Teknis Perparkiran Dinas Perhubungan Kota Pekanbaru;</li>
                        <li class="list-item">Melengkapi atribut juru parkir berupa :</li>
                        <ul class="indent">
                            <li class="list-item">Buku Saku;</li>
                            <li class="list-item">Rompi;</li>
                            <li class="list-item">Peluit;</li>
                            <li class="list-item">Karcis;</li>
                            <li class="list-item">Topi;</li>
                            <li class="list-item">Kartu Tanda Anggota (KTA);</li>
                            <li class="list-item">Payung;</li>
                            <li class="list-item">Jas Hujan, dan</li>
                            <li class="list-item">Menyediakan Asuransi Keselamatan Kerja.</li>
                        </ul>
                        <li class="list-item">Memberikan sosialisasi dan pembekalan kepada juru parkir tentang Jasa
                            Layanan
                            Parkir;</li>
                        <li class="list-item">Menyediakan fasilitas pembayaran non tunai (cashless) seperti
                            e-ticket/e-payment/e-money/QRIS sesuai dengan kebutuhan untuk kelancaran pelaksanaan
                            pengelola
                            parkir secara profesional;</li>
                        <li class="list-item">Berkoordinasi dan melaporkan hasil pelaksanaan tugas kepada UPT
                            Perparkiran
                            secara berkala;</li>
                        <li class="list-item">PIHAK KEDUA diwajibkan untuk melaporkan setiap penambahan potensi dan
                            titik
                            lokasi parkir yang berlaku.</li>
                    </ol>

                    <p class="section-title">Pasal 7<br>Tarif Parkir</p>
                    <p class="paragraph">PIHAK KEDUA memungut besaran tarif layanan parkir berdasarkan tarif layanan
                        parkir
                        yang ditetapkan yaitu Rp. 1000,- (seribu rupiah) untuk kendaraan roda 2 dan Rp. 2000,- (dua ribu
                        rupiah) untuk kendaraan roda 4 dan Rp. 6.000,- (enam ribu rupiah) untuk roda 6.</p>

                    <p class="section-title">Pasal 8<br>Pelayanan dan Pelaksanaan</p>
                    <p class="paragraph">Dalam rangka pelaksanaan pelayanan parkir, PIHAK KEDUA wajib mempedomani
                        Standar
                        Pelayanan Minimal (SPM) pelayanan parkir yang ditetapkan oleh PIHAK PERTAMA;</p>
                    <p class="paragraph">PIHAK PERTAMA melakukan monitoring dan evaluasi terhadap kinerja pelaksanaan
                        pelayanan parkir yang dilakukan oleh PIHAK KEDUA sesuai SPM yang ditetapkan PIHAK PERTAMA;</p>
                    <p class="paragraph">Dalam rangka monitoring dan evaluasi sebagaimana dimaksud pada ayat (2) pasal
                        ini,
                        PIHAK PERTAMA berhak mengakses data administrasi yang dikelola oleh PIHAK KEDUA;</p>
                    <p class="paragraph">Dalam rangka peningkatan pelayanan parkir, juru parkir wajib menerapkan 3S
                        (Sapa,Senyum,Salam) kepada pengguna jasa layanan parkir.</p>

                    <p class="section-title">Pasal 9<br>Pendapatan</p>
                    <p class="paragraph">Pendapatan yang diperoleh PIHAK KEDUA dari tarif layananparkirwajib disetorkan
                        kepada PIHAK PERTAMA secara non tunai melalui bendaraha penerimaan sebagaimana diatur dalam
                        pasal 4
                        ayat (1);</p>
                    <p class="paragraph">Apabila terjadi perubahan penambahan potensi pendapatan layanan parkir maka
                        PARA
                        PIHAK dapat menyesuaikan jumlah nilai setoran sebagaimana dimaksud pada ayat (1);</p>

                    <p class="section-title">Pasal 10<br>Koordinasi</p>
                    <p class="paragraph">Dalam rangka pelaksanaan Perjanjian Kerjasama ini akan dilakukan koordinasi
                        antara
                        PARA PIHAK paling kurang 1 (satu) minggu sekali dan/atau pada waktu tertentu yang disepakati
                        oleh
                        PARA PIHAK;</p>

                    <p class="section-title">Pasal 11<br>Adendum</p>
                    <p class="paragraph">Adendum perjanjian kerjasama ini dapat dilakukan apabila :</p>
                    <ol class="indent">
                        <li class="list-item">Terjadinya perubahan kebijakan ketenagakerjaan;</li>
                        <li class="list-item">Terjadinya perubahan potensi parkir;</li>
                        <li class="list-item">Terjadinya perubahan Tarif Layanan Parkir;</li>
                        <li class="list-item">Hal-hal lain yang disepakati PARA PIHAK.</li>
                    </ol>

                    <p class="section-title">Pasal 12<br>Keadaan Memaksa (Force Majeure)</p>
                    <p class="paragraph">Keadaan memaksa ( force majeure ) adalah keadaan yang terjadi diluar jangkauan
                        dan
                        kemauan PARA PIHAK seperti kerusuhan sosial, peperangan, kebakaran, peledakan, sabotase, badai,
                        banjir, gempa bumi, tsunami yang mengakibatkan keterlambatan atau kegagalan salah satu Pihak
                        dalam
                        memenuhi kewajibannya sebagaimana tercantum dalam Perjanjian Kerjasama ini;</p>
                    <p class="paragraph">Apabila terjadi force majeure sebagaimana dimaksud pada ayat (1) pasal ini
                        maka
                        Pihak yang mengalami force majeure wajib memberitahukan secara tertulis kepada Pihak lainnya
                        selambat-lambatnya 7 (tujuh) hari kalender terhitung sejak tanggal terjadinya force majeure ;
                    </p>
                    <p class="paragraph">Keterlambatan atau kelalaian atas pemberitahuan tersebut mengakibatkan tidak
                        diakuinya peristiwa tersebut sebagai keadaan force majeure .</p>

                    <p class="section-title">Pasal 13<br>Larangan</p>
                    <p class="paragraph">Selama Perjanjian Kerjasama ini berlaku, PIHAK KEDUA dilarang :</p>
                    <ol class="indent">
                        <li class="list-item">Melakukan pengelolaan perparkiran tidak sesuai dengan Peraturan
                            Perundang-undangan yang berlaku;</li>
                        <li class="list-item">Memungut tarif layanan parkir melebihi besaran tarif layanan parkir yang
                            berlaku yang telah diatur dalam Peraturan Perundang-undangan yang berlaku;</li>
                        <li class="list-item">PIHAK KEDUA tidak dibenarkan mengalihkan pegelolaan dan pemungutan jasa
                            layanan perparkiran kepada pihak lain;</li>
                        <li class="list-item">PIHAK KEDUA tidak dibenarkan mengelola dan memungut jasa layanan
                            perparkiran
                            pada titik lokasi yang tidak tercantum dalam perjanjian kerjasama ini;</li>
                        <li class="list-item">Melaksanakan pelayanan parkir tidak berdasarkan Standar Pelayanan
                            Minimal(SPM) yang ditetapkan PIHAK PERTAMA. </li>
                    </ol>

                    <p class="section-title">Pasal 14<br>Sanksi</p>
                    <p class="paragraph">Dalam hal PIHAK KEDUA melanggar ketentuan sebagaimana dimaksud dalam Pasal 14
                        huruf c, maka PIHAK PERTAMA berhak memutus Perjanjian Kerjasama ini secara sepihak;</p>
                    <p class="paragraph">Dalam hal PIHAK KEDUA melanggar ketentuan sebagaimana dimaksud dalam Pasal 14
                        huruf a, maka PIHAK KEDUA dikenakan sanksi dan ketentuan peraturan perundang-undangan yang
                        berlaku;
                    </p>
                    <p class="paragraph">Dalam hal ayat (1) dan (2) terbukti dan terjadi pemutusan perjanjian kerjasama
                        maka PIHAK PERTAMA berhak mengambil alih secara utuh pengelolaan pelayanan parkir didalam ruang
                        milik jalan sesuai peraturan perundang-undangan yang berlaku;</p>

                    <p class="section-title">Pasal 15<br>Berakhirnya Perjanjian Kerjasama</p>
                    <p class="paragraph">Perjanjian Kerjasama ini dapat berakhir disebabkan oleh :</p>
                    <ol class="indent">
                        <li class="list-item">Berakhirnya jangka waktu;</li>
                        <li class="list-item">Diputus oleh salah satu Pihak; dan</li>
                        <li class="list-item">Terjadinya keadaan memaksa.</li>
                    </ol>
                    <p class="paragraph">PIHAK KEDUA melanggar ketentuan sebagaimana dimaksud dalam Pasal 15.</p>
                    <p class="paragraph">Pemutusan Perjanjian Kerjasama sebagaimana dimaksud ayat (1) huruf b,
                        dilakukan
                        oleh PIHAK KEDUA dalam hal PIHAK PERTAMA tidak dapat melaksanakan kewajiban sebagaimana diatur
                        dalam
                        Perjanjian Kerjasama ini;</p>
                    <p class="paragraph">Jika dikemudian hari terjadi pemutusan kerjasama sebagaimana dimaksud ayat (1)
                        maka PIHAK KEDUA tidak dapat menuntut secara hukum yang berlaku.</p>

                    <p class="section-title">Pasal 16<br>Perselisihan</p>
                    <p class="paragraph">Segala perbedaan pendapat atau perselisihan yang timbul dalam Perjanjian
                        Kerjasama
                        ini akan diselesaikan secara musyawarah dan mufakat oleh PARA PIHAK;</p>

                    <p class="section-title">Pasal 17<br>Lain-lain</p>
                    <p class="paragraph">Segala sesuatu yang belum atau tidak cukup diatur dalam Perjanjian Kerjasama
                        ini
                        akan dituangkan dalam suatu perjanjian tambahan (addendum) tersendiri yang merupakan satu
                        kesatuan
                        yang tidak dapat terpisahkan dengan Perjanjian Kerjasama ini dan mempunyai kekuatan hukum yang
                        sama;
                    </p>
                    <p class="paragraph">Perjanjian Kerjasama ini tetap berlaku walaupun terjadi perubahan
                        kepemimpinan/jabatan dan bentuk badan hukum pada salah satu Pihak</p>
                    <p class="paragraph">Pengajuan untuk perpanjangan Perjanjian Kerjasama wajib diajukan 10 (sepuluh)
                        hari
                        sebelum kerjasama ini berakhir;</p>
                    <p class="paragraph">Dalam pengambilan keputusan terkait pengajuan,penetapan dan pengelolaan titik
                        lokasi parkir kewenangan sepenuhnya berada di Dinas Perhubungan Kota Pekanbaru melalui BLUD UPT
                        Perparkiran.</p>

                    <p class="section-title">Pasal 18<br>Penutup</p>
                    <p class="paragraph">Demikian Perjanjian Kerjasama ini dibuat dan ditandatangani di Pekanbaru pada
                        hari
                        dan tanggal tersebut di atas dalam rangkap 2 (dua) bermaterai cukup,masing-masing mempunyai
                        kekuatan
                        hukum yang sama.</p>

                    <div class="signature-block">
                        <div class="signature-column left">
                            <p class="font-bold">PIHAK PERTAMA,</p>
                            <p class="font-bold">KEPALA UPT PERPARKIRAN</p>
                            <p class="font-bold">SELAKU PIMPINAN BLUD</p>
                            <div style="height: 80px;"></div> {{-- Ruang untuk tanda tangan --}}
                            <p class="font-bold underline">{{ $agreement->leader->user->name ?? 'N/A' }}</p>
                            <p>NIP : {{ $agreement->leader->employee_number ?? 'N/A' }}</p>
                        </div>
                        <div class="signature-column right">
                            <p class="font-bold">PIHAK KEDUA,</p>
                            <p class="font-bold">MITRA KERJA SAMA</p>
                            <div style="height: 80px;"></div> {{-- Ruang untuk tanda tangan --}}
                            <p class="font-bold underline">{{ $agreement->fieldCoordinator->user->name ?? 'N/A' }}</p>
                            <p>NIK : {{ $agreement->fieldCoordinator->id_card_number ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

        </body>

    </html>
