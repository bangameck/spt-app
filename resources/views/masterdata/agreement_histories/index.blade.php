@extends('layouts.app')

@section('title', 'Riwayat Perjanjian')

@section('content')
    <div class="container-fluid">
        <div class="flex justify-between items-center mb-6">
            <h4 class="text-default-900 text-2xl font-bold">Riwayat Perjanjian</h4>
            {{-- Tidak ada tombol tambah karena history dibuat otomatis --}}
        </div>

        {{-- SweetAlert2 Success/Error Messages (jika ada) --}}
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

        <div class="card bg-white shadow rounded-lg p-6">
            {{-- Filter Form --}}
            <form action="{{ route('masterdata.agreement-histories.index') }}" method="GET" class="mb-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="search" class="block text-sm font-medium text-default-700 mb-2">Cari No. Perjanjian /
                            Tipe Event / Catatan / Pengubah</label>
                        <input type="text" name="search" id="search" placeholder="Cari..."
                            class="form-input w-full px-4 py-2 border rounded-md text-default-800 focus:ring-primary-500 focus:border-primary-500"
                            value="{{ $search ?? '' }}">
                    </div>
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-default-700 mb-2">Rentang
                            Tanggal</label>
                        <div class="flex items-center gap-2">
                            <input type="date" name="start_date" id="start_date"
                                class="form-input w-1/2 px-4 py-2 border rounded-md text-default-800 focus:ring-primary-500 focus:border-primary-500"
                                value="{{ $startDate ?? '' }}">
                            <span>-</span>
                            <input type="date" name="end_date" id="end_date"
                                class="form-input w-1/2 px-4 py-2 border rounded-md text-default-800 focus:ring-primary-500 focus:border-primary-500"
                                value="{{ $endDate ?? '' }}">
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-2 mt-4">
                    <button type="submit"
                        class="px-4 py-2 rounded-md bg-primary-600 text-white hover:bg-primary-700 transition-all">
                        Tampilkan Riwayat
                    </button>
                    @if ($search || $startDate || $endDate)
                        <a href="{{ route('masterdata.agreement-histories.index') }}"
                            class="px-4 py-2 rounded-md bg-default-200 text-default-800 hover:bg-default-300 transition-all">
                            Reset Filter
                        </a>
                    @endif
                </div>
            </form>

            {{-- Tabel Riwayat --}}
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-6">
                <table class="w-full text-sm text-left text-default-500">
                    <thead class="text-xs text-default-700 uppercase bg-default-50">
                        <tr>
                            <th scope="col" class="px-6 py-3">No. Perjanjian</th>
                            <th scope="col" class="px-6 py-3">Tipe Event</th>
                            <th scope="col" class="px-6 py-3">Catatan</th>
                            <th scope="col" class="px-6 py-3">Oleh</th>
                            <th scope="col" class="px-6 py-3">Tanggal</th>
                            <th scope="col" class="px-6 py-3">Detail Perubahan</th>
                            <th scope="col" class="px-6 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($agreementHistories as $history)
                            <tr class="bg-white border-b hover:bg-default-50">
                                <td class="px-6 py-4 font-medium text-default-900 whitespace-nowrap">
                                    {{ $history->agreement->agreement_number ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4">{{ ucfirst(str_replace('_', ' ', $history->event_type)) }}</td>
                                <td class="px-6 py-4">{{ $history->notes ?? '-' }}</td>
                                <td class="px-6 py-4">{{ $history->changer->name ?? 'Sistem' }}</td>
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
                                <td class="px-6 py-4">
                                    @if (isset($history->old_value['parking_locations_snapshot']) ||
                                            isset($history->new_value['parking_locations_snapshot']))
                                        <button type="button"
                                            class="font-medium text-purple-600 hover:underline show-old-agreement-locations-btn"
                                            data-history-id="{{ $history->id }}"
                                            data-agreement-number="{{ $history->agreement->agreement_number ?? 'N/A' }}"
                                            data-event-type="{{ $history->event_type }}"
                                            data-old-locations="{{ json_encode($history->old_value['parking_locations_snapshot'] ?? []) }}"
                                            data-new-locations="{{ json_encode($history->new_value['parking_locations_snapshot'] ?? []) }}">
                                            Lihat Lokasi Lama
                                        </button>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr class="bg-white border-b">
                                <td colspan="7" class="px-6 py-4 text-center text-default-500">Tidak ada riwayat
                                    perjanjian ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination Links --}}
            <div class="mt-4">
                {{ $agreementHistories->appends(request()->except('page'))->links('vendor.pagination.tailwind') }}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- SweetAlert2 for History Details (General Changes) ---
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

            // --- SweetAlert2 for Old Agreement Locations (Timeline View) ---
            document.querySelectorAll('.show-old-agreement-locations-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const agreementNumber = this.dataset.agreementNumber;
                    const eventType = this.dataset.eventType;
                    const oldLocations = JSON.parse(this.dataset.oldLocations);
                    const newLocations = JSON.parse(this.dataset.newLocations);

                    let htmlContent = `
                    <h5 class="text-lg font-bold mb-3">Lokasi Parkir Terkait Perjanjian "${agreementNumber}"</h5>
                    <p class="text-sm text-default-600 mb-4">Tipe Event: ${eventType.replace('_', ' ').split(' ').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ')}</p>
                `;

                    if (eventType === 'location_removed') {
                        // For location_removed, oldLocations contains the single removed location
                        const removedLoc = oldLocations[
                            0]; // Assuming only one location is removed per event
                        htmlContent += `
                        <div class="border-l-2 border-red-500 pl-4 mb-4">
                            <p class="font-semibold text-red-700">Lokasi Dikeluarkan:</p>
                            <p class="text-sm">Nama: ${removedLoc.name}</p>
                            <p class="text-sm">Ruas Jalan: ${removedLoc.road_section}</p>
                            <p class="text-sm">Status: ${removedLoc.status.replace('_', ' ').split(' ').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ')}</p>
                        </div>
                    `;
                    } else if (eventType === 'agreement_updated') {
                        const added = newLocations.filter(newLoc => !oldLocations.some(oldLoc =>
                            oldLoc.id === newLoc.id));
                        const removed = oldLocations.filter(oldLoc => !newLocations.some(newLoc =>
                            newLoc.id === oldLoc.id));
                        const unchanged = newLocations.filter(newLoc => oldLocations.some(oldLoc =>
                            oldLoc.id === newLoc.id));

                        if (added.length > 0) {
                            htmlContent += `
                            <div class="border-l-2 border-green-500 pl-4 mb-4">
                                <p class="font-semibold text-green-700">Lokasi Ditambahkan:</p>
                                <ul class="list-disc list-inside text-sm">
                                    ${added.map(loc => `<li>${loc.name} (${loc.road_section})</li>`).join('')}
                                </ul>
                            </div>
                        `;
                        }
                        if (removed.length > 0) {
                            htmlContent += `
                            <div class="border-l-2 border-red-500 pl-4 mb-4">
                                <p class="font-semibold text-red-700">Lokasi Dikeluarkan:</p>
                                <ul class="list-disc list-inside text-sm">
                                    ${removed.map(loc => `<li>${loc.name} (${loc.road_section})</li>`).join('')}
                                </ul>
                            </div>
                        `;
                        }
                        if (unchanged.length > 0) {
                            htmlContent += `
                            <div class="border-l-2 border-gray-500 pl-4 mb-4">
                                <p class="font-semibold text-gray-700">Lokasi Tidak Berubah:</p>
                                <ul class="list-disc list-inside text-sm">
                                    ${unchanged.map(loc => `<li>${loc.name} (${loc.road_section})</li>`).join('')}
                                </ul>
                            </div>
                        `;
                        }
                    } else if (eventType === 'agreement_created') {
                        htmlContent += `
                        <div class="border-l-2 border-blue-500 pl-4 mb-4">
                            <p class="font-semibold text-blue-700">Lokasi Awal Perjanjian:</p>
                            <ul class="list-disc list-inside text-sm">
                                ${newLocations.map(loc => `<li>${loc.name} (${loc.road_section})</li>`).join('')}
                            </ul>
                        </div>
                    `;
                    }


                    Swal.fire({
                        title: `Riwayat Lokasi Perjanjian ${agreementNumber}`,
                        html: htmlContent,
                        icon: 'info',
                        width: '600px',
                        confirmButtonText: 'Tutup',
                        customClass: {
                            container: 'swal-wide',
                            popup: 'swal2-popup'
                        }
                    });
                });
            });
        });
    </script>
@endpush
