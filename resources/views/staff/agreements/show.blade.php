@extends('layouts.app')

@section('title', 'Detail Perjanjian: ' . $agreement->agreement_number)

@section('content')
    <div class="container-fluid">
        <div class="flex justify-between items-center mb-6">
            <h4 class="text-default-900 text-2xl font-bold">Detail Perjanjian: {{ $agreement->agreement_number }}</h4>
            <div class="flex items-center gap-2">
                <a href="{{ route('masterdata.agreements.pdf', $agreement) }}" target="_blank"
                    class="px-6 py-2 rounded-md text-white bg-purple-600 hover:bg-purple-700 transition-all">
                    Cetak PDF
                </a>
                <a href="{{ route('masterdata.agreements.edit', $agreement) }}"
                    class="px-6 py-2 rounded-md text-white bg-blue-600 hover:bg-blue-700 transition-all">
                    Edit Perjanjian
                </a>
                <a href="{{ route('masterdata.agreements.index') }}"
                    class="px-6 py-2 rounded-md text-primary-600 border border-primary-600 hover:bg-primary-600 hover:text-white transition-all">
                    Kembali ke Daftar Perjanjian
                </a>
            </div>
        </div>

        {{-- SweetAlert2 Success/Error Messages --}}
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Sukses!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <div class="card bg-white shadow rounded-lg p-6 mb-6">
            <h5 class="text-lg font-semibold text-default-800 mb-4">Informasi Perjanjian</h5>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm font-medium text-default-700">Nomor Perjanjian:</p>
                    <p class="text-default-900">{{ $agreement->agreement_number }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-default-700">Pimpinan (PIHAK PERTAMA):</p>
                    <p class="text-default-900">{{ $agreement->leader->user->name ?? 'N/A' }} (NIP:
                        {{ $agreement->leader->employee_number ?? 'N/A' }})</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-default-700">Koordinator Lapangan (PIHAK KEDUA):</p>
                    <p class="text-default-900">{{ $agreement->fieldCoordinator->user->name ?? 'N/A' }} (No. KTP:
                        {{ $agreement->fieldCoordinator->id_card_number ?? 'N/A' }})</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-default-700">Tanggal Mulai Perjanjian:</p>
                    <p class="text-default-900">{{ $agreement->start_date->format('d M Y') }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-default-700">Tanggal Akhir Perjanjian:</p>
                    <p class="text-default-900">{{ $agreement->end_date->format('d M Y') }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-default-700">Tanggal Ditandatangani:</p>
                    <p class="text-default-900">{{ $agreement->signed_date->format('d M Y') }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-default-700">Jumlah Setoran Harian:</p>
                    <p class="text-default-900">Rp {{ number_format($agreement->daily_deposit_amount, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-default-700">Status Perjanjian:</p>
                    <p class="text-default-900">
                        <span
                            class="inline-flex items-center gap-1.5 py-1 px-3 rounded-full text-xs font-medium {{ $agreement->status == 'active'
                                ? 'bg-green-100 text-green-800'
                                : ($agreement->status == 'expired'
                                    ? 'bg-red-100 text-red-800'
                                    : ($agreement->status == 'terminated'
                                        ? 'bg-gray-100 text-gray-800'
                                        : 'bg-yellow-100 text-yellow-800')) }}">
                            {{ ucfirst(str_replace('_', ' ', $agreement->status)) }}
                        </span>
                    </p>
                </div>
            </div>
        </div>

        <div class="card bg-white shadow rounded-lg p-6 mb-6">
            <h5 class="text-lg font-semibold text-default-800 mb-4">Lokasi Parkir Terkait</h5>
            {{-- Search input for Parking Locations --}}
            <div class="mb-4">
                <input type="text" id="parking-location-search" placeholder="Cari lokasi parkir terkait..."
                    class="form-input w-full px-4 py-2 border rounded-md text-default-800 focus:ring-primary-500 focus:border-primary-500">
            </div>

            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left text-default-500" id="parking-locations-table">
                    <thead class="text-xs text-default-700 uppercase bg-default-50">
                        <tr>
                            <th scope="col" class="px-6 py-3">Nama Lokasi</th>
                            <th scope="col" class="px-6 py-3">Ruas Jalan</th>
                            <th scope="col" class="px-6 py-3">Status Lokasi</th>
                            <th scope="col" class="px-6 py-3">Tanggal Ditugaskan</th>
                            <th scope="col" class="px-6 py-3">Aksi</th> {{-- New column for action --}}
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($agreement->activeParkingLocations as $location)
                            <tr class="bg-white border-b hover:bg-default-50">
                                <td class="px-6 py-4 font-medium text-default-900 whitespace-nowrap">{{ $location->name }}
                                </td>
                                <td class="px-6 py-4">{{ $location->roadSection->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center gap-1.5 py-1 px-3 rounded-full text-xs font-medium {{ $location->pivot->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ ucfirst(str_replace('_', ' ', $location->pivot->status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">{{ $location->pivot->assigned_date->format('d M Y') }}</td>
                                <td class="px-6 py-4">
                                    {{-- Form untuk detach lokasi parkir --}}
                                    <form id="detach-form-{{ $agreement->id }}-{{ $location->id }}"
                                        action="{{ route('masterdata.agreements.detach-parking-location', [$agreement->id, $location->id]) }}"
                                        method="POST" style="display: none;">
                                        @csrf
                                        {{-- @method('POST') tidak diperlukan karena route sudah POST --}}
                                    </form>
                                    <button type="button"
                                        class="font-medium text-red-600 hover:underline detach-parking-location-btn"
                                        data-agreement-id="{{ $agreement->id }}" data-location-id="{{ $location->id }}"
                                        data-location-name="{{ $location->name }}">
                                        Keluar
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr class="bg-white border-b">
                                <td colspan="5" class="px-6 py-4 text-center text-default-500">Tidak ada lokasi parkir
                                    terkait.</td> {{-- Update colspan --}}
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card bg-white shadow rounded-lg p-6">
            <h5 class="text-lg font-semibold text-default-800 mb-4">Riwayat Perjanjian</h5>
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left text-default-500">
                    <thead class="text-xs text-default-700 uppercase bg-default-50">
                        <tr>
                            <th scope="col" class="px-6 py-3">Tipe Event</th>
                            <th scope="col" class="px-6 py-3">Perubahan Oleh</th>
                            <th scope="col" class="px-6 py-3">Catatan</th>
                            <th scope="col" class="px-6 py-3">Tanggal</th>
                            <th scope="col" class="px-6 py-3">Detail Perubahan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($agreement->histories as $history)
                            <tr class="bg-white border-b hover:bg-default-50">
                                <td class="px-6 py-4">{{ ucfirst(str_replace('_', ' ', $history->event_type)) }}</td>
                                <td class="px-6 py-4">{{ $history->changer->name ?? 'Sistem' }}</td>
                                <td class="px-6 py-4">{{ $history->notes ?? '-' }}</td>
                                <td class="px-6 py-4">{{ $history->created_at->format('d M Y H:i') }}</td>
                                <td class="px-6 py-4">
                                    @if ($history->old_value || $history->new_value)
                                        <button type="button"
                                            class="text-blue-600 hover:underline show-history-details-btn"
                                            data-old-value="{{ json_encode($history->old_value) }}"
                                            data-new-value="{{ json_encode($history->new_value) }}">
                                            Lihat Detail
                                        </button>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr class="bg-white border-b">
                                <td colspan="5" class="px-6 py-4 text-center text-default-500">Tidak ada riwayat
                                    perjanjian.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- SweetAlert2 for History Details ---
            document.querySelectorAll('.show-history-details-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const oldValue = JSON.parse(this.dataset.oldValue);
                    const newValue = JSON.parse(this.dataset.newValue);

                    let htmlContent = '<div>';
                    htmlContent += '<h5 class="text-lg font-bold mb-2">Perubahan Detail:</h5>';

                    if (oldValue) {
                        htmlContent += '<p class="font-semibold mt-4 mb-1">Nilai Lama:</p>';
                        htmlContent +=
                            '<pre class="bg-gray-100 p-2 rounded text-sm overflow-auto max-h-40">' +
                            JSON.stringify(oldValue, null, 2) + '</pre>';
                    }
                    if (newValue) {
                        htmlContent += '<p class="font-semibold mt-4 mb-1">Nilai Baru:</p>';
                        htmlContent +=
                            '<pre class="bg-gray-100 p-2 rounded text-sm overflow-auto max-h-40">' +
                            JSON.stringify(newValue, null, 2) + '</pre>';
                    }
                    htmlContent += '</div>';

                    Swal.fire({
                        title: 'Detail Riwayat',
                        html: htmlContent,
                        icon: 'info',
                        width: '600px',
                        confirmButtonText: 'Tutup',
                        customClass: {
                            container: 'swal-wide',
                            popup: 'swal2-popup' // Add this class to your custom CSS if needed
                        }
                    });
                });
            });

            // --- Client-side Search for Parking Locations Table ---
            const searchInput = document.getElementById('parking-location-search');
            const parkingLocationsTable = document.getElementById(
                'parking-locations-table'); // Get the table itself
            const tableBody = parkingLocationsTable ? parkingLocationsTable.querySelector('tbody') :
                null; // Get tbody from the table
            const tableRows = tableBody ? Array.from(tableBody.querySelectorAll('tr')) : [];

            console.log('Search Input:', searchInput); // Debugging: Check if search input element is found
            console.log('Parking Locations Table:',
                parkingLocationsTable); // Debugging: Check if table element is found
            console.log('Table Body:', tableBody); // Debugging: Check if tbody element is found
            console.log('Table Rows (initial):', tableRows); // Debugging: Check if rows are found

            if (searchInput && tableBody && tableRows.length > 0) {
                searchInput.addEventListener('keyup', function() {
                    const searchTerm = this.value.toLowerCase();
                    console.log('Search term:', searchTerm); // Debugging: Log current search term

                    tableRows.forEach(row => {
                        let rowText = row.textContent.toLowerCase();
                        console.log('Row text:', rowText); // Debugging: Log row content
                        if (rowText.includes(searchTerm)) {
                            row.style.display = ''; // Show row
                        } else {
                            row.style.display = 'none'; // Hide row
                        }
                    });
                });
            } else {
                console.warn('Search functionality not initialized. Missing elements or no rows found.');
            }

            // --- SweetAlert2 and Detach Logic for "Keluar" Button ---
            document.addEventListener('click', function(e) {
                if (e.target && e.target.classList.contains('detach-parking-location-btn')) {
                    e.preventDefault();

                    const agreementId = e.target.dataset.agreementId;
                    const locationId = e.target.dataset.locationId;
                    const locationName = e.target.dataset.locationName;

                    if (typeof Swal === 'undefined') {
                        console.error('SweetAlert2 (Swal) is not loaded.');
                        alert(
                            `Gagal mengeluarkan lokasi parkir ${locationName}. SweetAlert2 tidak ditemukan.`
                        );
                        return;
                    }

                    Swal.fire({
                        title: 'Konfirmasi Pengeluaran Lokasi?',
                        text: `Anda yakin ingin mengeluarkan lokasi parkir "${locationName}" dari perjanjian ini? Status lokasi akan berubah menjadi Tersedia.`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, Keluarkan!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById(`detach-form-${agreementId}-${locationId}`)
                                .submit();
                        }
                    });
                }
            });
        });
    </script>
@endpush
