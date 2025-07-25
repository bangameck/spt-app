<!DOCTYPE html>
<html>

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Bukti Setoran - #{{ $depositTransaction->id }}</title>
        <style>
            @font-face {
                font-family: 'Bookman Old Style';
                src: url('{{ storage_path(' fonts/bookmanoldstyle.ttf') }}') format('truetype');
                font-weight: normal;
                font-style: normal;
            }

            @font-face {
                font-family: 'Bookman Old Style';
                src: url('{{ storage_path(' fonts/bookmanoldstylebold.ttf') }}') format('truetype');
                font-weight: bold;
                font-style: normal;
            }

            body {
                font-family: 'Bookman Old Style', serif;
                font-size: 12px;
                margin: 1.5cm;
                color: #333;
            }

            .container {
                width: 100%;
                margin: 0 auto;
                padding: 20px;
                border: 1px solid #eee;
            }

            .header {
                text-align: center;
                margin-bottom: 20px;
            }

            .header h3 {
                margin: 0;
                font-size: 18px;
            }

            .header p {
                margin: 5px 0;
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            table td {
                padding: 8px 0;
            }

            .details-table td:first-child {
                width: 40%;
                font-weight: bold;
            }

            /* ✅ STYLE BARU UNTUK CATATAN */
            .notes-section {
                margin-top: 20px;
                padding: 10px;
                border: 1px dashed #ddd;
                background-color: #f9f9f9;
                text-align: left;
            }

            .notes-section p {
                margin: 0;
            }

            .total-section {
                margin-top: 20px;
                padding-top: 10px;
                border-top: 2px dashed #ccc;
                text-align: right;
            }

            .total-section h4 {
                margin: 0;
                font-size: 16px;
            }
        </style>
    </head>

    <body>
        <div class="container">
            <div class="header">
                <h3>BUKTI SETORAN DANA</h3>
                <p>{{ $uptProfile->name }}</p>
                <p>{{ $uptProfile->address }}</p>
            </div>
            <hr>
            <table class="details-table">
                <tr>
                    <td>Referensi</td>
                    <td>: {{ $depositTransaction->referral_code }}</td>
                </tr>
                <tr>
                    <td>Tanggal Setor</td>
                    <td>: {{ $depositTransaction->deposit_date->translatedFormat('d F Y') }}</td>
                </tr>
                <tr>
                    <td>Nomor PKS</td>
                    <td>: {{ $depositTransaction->agreement->agreement_number }}</td>
                </tr>
                <tr>
                    <td>Koordinator Lapangan</td>
                    <td>: {{ $depositTransaction->agreement->fieldCoordinator->user->name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td>Jumlah Setoran / Hari</td>
                    <td>: Rp. {{ number_format($depositTransaction->agreement->daily_deposit_amount, 0, ',', '.') }},-
                    </td>
                </tr>
                <tr>
                    <td>Jumlah Hari bln. {{ $monthName . ' ' . $year }}</td>
                    <td>: {{ $daysInMonth }} Hari</td>
                </tr>
                <tr>
                    <td>Dicatat Oleh</td>
                    <td>: {{ $depositTransaction->creator->name ?? 'Sistem' }}</td>
                </tr>
                <tr>
                    <td>Status</td>
                    <td>: {{ $depositTransaction->is_validated ? 'Tervalidasi' : 'Pending' }}</td>
                </tr>
                @if ($depositTransaction->is_validated)
                    <tr>
                        <td>Tanggal Validasi</td>
                        <td>:
                            {{ $depositTransaction->validation_date ? $depositTransaction->validation_date->locale('id')->translatedFormat('d F Y, H:i') : '-' }}
                        </td>
                    </tr>
                @endif
            </table>

            {{-- ✅ PERUBAHAN DI SINI: Menambahkan Catatan --}}
            @if (!empty($depositTransaction->notes))
                <div class="notes-section">
                    <p><strong>Catatan:</strong><br>{{ $depositTransaction->notes }}</p>
                </div>
            @endif

            <div class="total-section">
                <p>Jumlah Yang Dibayarkan:</p>
                <h4>Rp {{ number_format($depositTransaction->amount, 0, ',', '.') }},-</h4>
            </div>
        </div>
    </body>

</html>
