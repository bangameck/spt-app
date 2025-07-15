<!DOCTYPE html>
<html>

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>PKS - {{ $agreement->agreement_number }}</title>
        <style>
            @font-face {
                font-family: 'Bookman Old Style';
                src: url('{{ storage_path('fonts/bookman-old-style.ttf') }}') format('truetype');
                font-weight: normal;
                font-style: normal;
            }

            @font-face {
                font-family: 'Bookman Old Style';
                src: url('{{ storage_path('fonts/bookman-old-style-bold.ttf') }}') format('truetype');
                font-weight: bold;
                font-style: normal;
            }

            body {
                font-family: 'Bookman Old Style', serif;
                font-size: 11pt;
                line-height: 1.5;
                margin: 1.5cm;
            }

            .cover-page {
                text-align: center;
                height: 90vh;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
            }

            .cover-logo-container img {
                height: 100px;
                margin: 0 30px;
            }

            .cover-page h1,
            .cover-page h2,
            .cover-page h3,
            .cover-page p {
                font-weight: bold;
                margin: 5px 0;
            }

            .cover-page h1 {
                font-size: 18pt;
                margin-top: 50px;
            }

            .cover-page h2 {
                font-size: 16pt;
            }

            .cover-page h3 {
                font-size: 14pt;
            }

            .cover-page .cover-year {
                margin-top: 50px;
            }

            .page-break {
                page-break-after: always;
            }

            .content {
                text-align: justify;
            }

            .title {
                text-align: center;
                font-weight: bold;
                text-decoration: underline;
                text-transform: uppercase;
                font-size: 14pt;
                margin-bottom: 5px;
            }

            .number {
                text-align: center;
                margin-bottom: 20px;
            }

            .paragraph {
                margin-bottom: 10px;
            }

            .pasal-title {
                text-align: center;
                font-weight: bold;
                margin-top: 20px;
                margin-bottom: 10px;
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

            table.no-border {
                width: 100%;
                border: none;
            }

            table.no-border td {
                border: none;
                padding: 1px 0;
                vertical-align: top;
            }

            .list {
                padding-left: 20px;
                margin-left: 30px;
            }

            .list li,
            .sub-list li {
                margin-bottom: 5px;
            }

            .sub-list {
                padding-left: 20px;
            }

            .signature-block {
                margin-top: 50px;
                width: 100%;
            }

            .signature-column {
                display: inline-block;
                width: 49%;
                text-align: center;
                vertical-align: top;
            }
        </style>
    </head>

    <body>
        {{-- Halaman Sampul --}}
        <div class="cover-page">
            <div class="cover-logo-container">
                <img src="{{ public_path('assets/images/pekanbaru.png') }}" alt="Logo Pekanbaru">
                <img src="{{ public_path('assets/images/dishub.png') }}" alt="Logo Dishub">
            </div>
            <h1>PERJANJIAN KERJASAMA</h1>
            <h2>ANTARA</h2>
            <h2>DINAS PERHUBUNGAN KOTA PEKANBARU</h2>
            <h3>DENGAN</h3>
            <h3>{{ $agreement->fieldCoordinator->user->name ?? 'MITRA KERJASAMA' }}</h3>
            <h3>TENTANG</h3>
            <h3>PENGELOLAAN JASA LAYANAN PARKIR TEPI JALAN UMUM</h3>
            <p>NOMOR : {{ $agreement->agreement_number }}</p>
            <p class="cover-year">TAHUN {{ $agreement->signed_date->format('Y') }}</p>
        </div>
        <div class="page-break"></div>

        {{-- Konten Perjanjian --}}
        <div class="content">
            <p class="title">PERJANJIAN KERJASAMA</p>
            <p class="number">Nomor : {{ $agreement->agreement_number }}</p>

            <p>Pada hari ini {{ $agreement->signed_date->translatedFormat('l') }} tanggal
                {{ \App\Helpers\NumberToWords::convert($agreement->signed_date->format('d')) }} bulan
                {{ $agreement->signed_date->translatedFormat('F') }} tahun
                {{ \App\Helpers\NumberToWords::convert($agreement->signed_date->format('Y')) }}, bertempat di
                Pekanbaru, yang bertanda tangan di bawah ini:</p>

            <table class="no-border" style="margin-top: 15px;">
                <tr>
                    <td style="width: 5%;">I.</td>
                    <td style="width: 25%;">Nama</td>
                    <td style="width: 2%;">:</td>
                    <td class="font-bold">{{ $agreement->leader->user->name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td>NIP</td>
                    <td>:</td>
                    <td>{{ $agreement->leader->employee_number ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td>Jabatan</td>
                    <td>:</td>
                    <td>Kepala UPT Perparkiran Dinas Perhubungan Kota Pekanbaru</td>
                </tr>
                <tr>
                    <td></td>
                    <td>Alamat</td>
                    <td>:</td>
                    <td>Jl. Lintas Timur KM 11, Kel. Sail, Kec. Tenayan Raya, Kota Pekanbaru</td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="3" style="padding-top: 10px;">Dalam hal ini bertindak dalam jabatannya tersebut
                        untuk dan atas nama UPT Perparkiran Dinas Perhubungan Kota Pekanbaru, yang selanjutnya disebut
                        sebagai <span class="font-bold">PIHAK PERTAMA</span>.</td>
                </tr>
            </table>

            <table class="no-border" style="margin-top: 15px;">
                <tr>
                    <td style="width: 5%;">II.</td>
                    <td style="width: 25%;">Nama</td>
                    <td style="width: 2%;">:</td>
                    <td class="font-bold">{{ $agreement->fieldCoordinator->user->name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td>NIK</td>
                    <td>:</td>
                    <td>{{ $agreement->fieldCoordinator->id_card_number ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td>Alamat</td>
                    <td>:</td>
                    <td>{{ $agreement->fieldCoordinator->address ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td>No. Telepon/HP</td>
                    <td>:</td>
                    <td>{{ $agreement->fieldCoordinator->phone_number ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="3" style="padding-top: 10px;">Dalam hal ini bertindak untuk dan atas nama diri
                        sendiri, yang selanjutnya disebut sebagai <span class="font-bold">PIHAK KEDUA</span>.</td>
                </tr>
            </table>

            <p style="margin-top: 15px;">PIHAK PERTAMA dan PIHAK KEDUA secara bersama-sama disebut Para Pihak, sepakat
                untuk mengadakan Perjanjian Kerjasama Pengelolaan Jasa Layanan Parkir di Tepi Jalan Umum (selanjutnya
                disebut Perjanjian) dengan syarat-syarat dan ketentuan sebagai berikut:</p>

            <p class="pasal-title">Pasal 1<br>MAKSUD DAN TUJUAN</p>
            <p class="paragraph">PIHAK PERTAMA menunjuk PIHAK KEDUA dan PIHAK KEDUA menerima penunjukan tersebut untuk
                melaksanakan Pengelolaan Jasa Layanan Parkir di Tepi Jalan Umum pada lokasi yang telah ditetapkan.</p>

            <p class="pasal-title">Pasal 2<br>OBJEK PERJANJIAN</p>
            <p class="paragraph">Objek perjanjian ini adalah pengelolaan jasa layanan parkir pada ruas jalan sebagai
                berikut:</p>
            <ol type="A" class="list">
                @foreach ($agreement->activeParkingLocations->groupBy('roadSection.name') as $roadSectionName => $locations)
                    <li>Segmen Lokasi Parkir di <span class="font-bold">Jalan {{ $roadSectionName }}</span>, pada titik
                        lokasi:
                        <ol class="sub-list" style="list-style-type: decimal;">
                            @foreach ($locations as $location)
                                <li>{{ $location->name }};</li>
                            @endforeach
                        </ol>
                    </li>
                @endforeach
            </ol>

            <p class="pasal-title">Pasal 3<br>JANGKA WAKTU</p>
            <p class="paragraph">Perjanjian ini berlaku untuk jangka waktu
                {{ $agreement->start_date->diffInMonths($agreement->end_date) }}
                ({{ \App\Helpers\NumberToWords::convert($agreement->start_date->diffInMonths($agreement->end_date)) }})
                bulan, terhitung sejak tanggal {{ $agreement->start_date->translatedFormat('d F Y') }} sampai dengan
                tanggal {{ $agreement->end_date->translatedFormat('d F Y') }}.</p>

            <p class="pasal-title">Pasal 4<br>KEWAJIBAN SETORAN</p>
            <p class="paragraph">PIHAK KEDUA berkewajiban menyetorkan hasil pemungutan jasa layanan parkir kepada PIHAK
                PERTAMA sebesar <span class="font-bold">Rp.
                    {{ number_format($agreement->daily_deposit_amount, 0, ',', '.') }},-
                    ({{ \App\Helpers\NumberToWords::convert(round($agreement->daily_deposit_amount)) }} Rupiah)</span>
                per hari, yang disetorkan setiap hari kerja ke rekening Kas BLUD UPT Perparkiran.</p>

            <p class="pasal-title">Pasal 5<br>HAK DAN KEWAJIBAN PARA PIHAK</p>
            <p class="paragraph">1. Hak dan Kewajiban PIHAK PERTAMA :</p>
            <ol type="a" class="list">
                <li>PIHAK PERTAMA berhak menerima setoran hasil pengelolaan jasa layanan parkir dari PIHAK KEDUA.</li>
                <li>PIHAK PERTAMA berhak melakukan pengawasan dan evaluasi terhadap pelaksanaan pengelolaan jasa layanan
                    parkir yang dilakukan oleh PIHAK KEDUA.</li>
                <li>PIHAK PERTAMA berkewajiban menyerahkan objek perjanjian kepada PIHAK KEDUA dalam keadaan baik.</li>
            </ol>
            <p class="paragraph">2. Hak dan Kewajiban PIHAK KEDUA :</p>
            <ol type="a" class="list">
                <li>PIHAK KEDUA berhak mengelola objek perjanjian dan memungut biaya jasa layanan parkir sesuai dengan
                    ketentuan yang berlaku.</li>
                <li>PIHAK KEDUA berkewajiban menjaga dan memelihara objek perjanjian dengan baik.</li>
                <li>PIHAK KEDUA berkewajiban menyediakan atribut dan perlengkapan bagi juru parkir.</li>
                <li>PIHAK KEDUA berkewajiban menempatkan juru parkir yang sopan, ramah, dan bertanggung jawab.</li>
            </ol>

            <p class="pasal-title">Pasal 6<br>SANKSI</p>
            <p class="paragraph">Apabila PIHAK KEDUA tidak melaksanakan kewajibannya sesuai dengan pasal 4 perjanjian
                ini, maka PIHAK PERTAMA akan memberikan sanksi berupa teguran lisan, teguran tertulis, hingga pemutusan
                perjanjian kerjasama.</p>

            <p class="pasal-title">Pasal 7<br>KEADAAN MEMAKSA (FORCE MAJEURE)</p>
            <p class="paragraph">Yang dimaksud dengan keadaan memaksa adalah suatu keadaan yang terjadi di luar
                kemampuan Para Pihak yang tidak dapat diperhitungkan sebelumnya, seperti bencana alam, huru-hara,
                peperangan, dan kebijakan pemerintah di bidang moneter.</p>

            <p class="pasal-title">Pasal 8<br>PENYELESAIAN PERSELISIHAN</p>
            <p class="paragraph">Apabila terjadi perselisihan antara Para Pihak sehubungan dengan perjanjian ini, akan
                diselesaikan secara musyawarah untuk mufakat.</p>

            <p class="pasal-title">Pasal 9<br>LAIN-LAIN</p>
            <p class="paragraph">Hal-hal lain yang belum diatur dalam perjanjian ini akan diatur kemudian dalam suatu
                Addendum yang merupakan bagian yang tidak terpisahkan dari perjanjian ini.</p>

            <p class="pasal-title">Pasal 10<br>PENUTUP</p>
            <p class="paragraph">Demikian Perjanjian ini dibuat dalam rangkap 2 (dua), masing-masing bermeterai cukup
                dan mempunyai kekuatan hukum yang sama setelah ditandatangani oleh Para Pihak.</p>

            <div class="signature-block">
                <div class="signature-column">
                    <p class="font-bold">PIHAK KEDUA,</p>
                    <div style="height: 80px;"></div>
                    <p class="font-bold underline">{{ $agreement->fieldCoordinator->user->name ?? 'N/A' }}</p>
                </div>
                <div class="signature-column">
                    <p class="font-bold">PIHAK PERTAMA,</p>
                    <p class="font-bold">KEPALA UPT PERPARKIRAN<br>DINAS PERHUBUNGAN KOTA PEKANBARU</p>
                    <div style="height: 80px;"></div>
                    <p class="font-bold underline">{{ $agreement->leader->user->name ?? 'N/A' }}</p>
                    <p>NIP. {{ $agreement->leader->employee_number ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </body>

</html>
