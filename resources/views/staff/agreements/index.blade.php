@extends('layouts.app')

@section('title', 'Daftar Perjanjian Kerjasama')

@section('content')
    <div class="container-fluid">
        <div class="flex justify-between items-center mb-6">
            <h4 class="text-default-900 text-2xl font-bold">Daftar Perjanjian Kerjasama</h4>
            <a href="{{ route('masterdata.agreements.create') }}"
                class="px-6 py-2 rounded-md text-white bg-primary-600 hover:bg-primary-700 transition-all">
                Tambah Perjanjian Baru
            </a>
        </div>

        {{-- SweetAlert2 Success Message (akan ditampilkan oleh JS) --}}
        @if (session('success') && session('agreement_number'))
            <div id="success-alert" data-name="{{ session('agreement_number') }}" style="display: none;"></div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline">{{ session('error') }}</span>
                <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                    <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20">
                        <title>Close</title>
                        <path
                            d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.697l-2.651 2.652a1.2 1.2 0 1 1-1.697-1.697L8.303 10 5.651 7.348a1.2 1.2 0 1 1 1.697-1.697L10 8.303l2.651-2.652a1.2 1.2 0 0 1 1.697 1.697L11.697 10l2.651 2.651a1.2 1.2 0 0 1 0 1.698z" />
                    </svg>
                </span>
            </div>
        @endif

        <div class="card bg-white shadow rounded-lg p-6">
            {{-- Search Form --}}
            <form action="{{ route('masterdata.agreements.index') }}" method="GET" class="mb-4">
                <div class="flex items-center gap-2">
                    <input type="text" name="search" placeholder="Cari perjanjian..."
                        class="form-input flex-grow px-4 py-2 border rounded-md text-default-800 focus:ring-primary-500 focus:border-primary-500"
                        value="{{ $search ?? '' }}">
                    <button type="submit"
                        class="px-4 py-2 rounded-md bg-primary-600 text-white hover:bg-primary-700 transition-all">
                        Cari
                    </button>
                    @if ($search)
                        <a href="{{ route('masterdata.agreements.index') }}"
                            class="px-4 py-2 rounded-md bg-default-200 text-default-800 hover:bg-default-300 transition-all">
                            Reset
                        </a>
                    @endif
                </div>
            </form>

            <div id="agreements-table-container" class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left text-default-500">
                    <thead class="text-xs text-default-700 uppercase bg-default-50">
                        <tr>
                            <th scope="col" class="px-6 py-3">Nomor Perjanjian</th>
                            <th scope="col" class="px-6 py-3">Pimpinan</th>
                            <th scope="col" class="px-6 py-3">Koordinator Lapangan</th>
                            <th scope="col" class="px-6 py-3">Lokasi Parkir</th>
                            <th scope="col" class="px-6 py-3">Mulai - Akhir</th>
                            <th scope="col" class="px-6 py-3">Setoran Harian</th>
                            <th scope="col" class="px-6 py-3">Status</th>
                            <th scope="col" class="px-6 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($agreements as $agreement)
                            <tr class="bg-white border-b hover:bg-default-50">
                                <td class="px-6 py-4 font-medium text-default-900 whitespace-nowrap">
                                    {{ $agreement->agreement_number }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $agreement->leader->user->name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $agreement->fieldCoordinator->user->name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4">
                                    @forelse ($agreement->activeParkingLocations as $pl)
                                        <span class="block">{{ $pl->name }}
                                            ({{ $pl->roadSection->name ?? 'N/A' }})
                                        </span>
                                    @empty
                                        <span class="text-default-500">Tidak ada lokasi</span>
                                    @endforelse
                                </td>
                                <td class="px-6 py-4">
                                    {{ $agreement->start_date->format('d M Y') }} -
                                    {{ $agreement->end_date->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    Rp {{ number_format($agreement->daily_deposit_amount, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4">
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
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        <a href="{{ route('masterdata.agreements.pdf', $agreement) }}" target="_blank"
                                            class="font-medium text-purple-600 hover:underline">Cetak PDF</a>
                                        <a href="{{ route('masterdata.agreements.show', $agreement) }}"
                                            class="font-medium text-blue-600 hover:underline">Lihat</a>
                                        <a href="{{ route('masterdata.agreements.edit', $agreement) }}"
                                            class="font-medium text-blue-600 hover:underline">Edit</a>
                                        <form id="delete-form-agreement-{{ $agreement->id }}"
                                            action="{{ route('masterdata.agreements.destroy', $agreement->id) }}"
                                            method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                        <button type="button"
                                            class="font-medium text-red-600 hover:underline ml-2 delete-agreement-btn"
                                            data-agreement-id="{{ $agreement->id }}"
                                            data-agreement-number="{{ $agreement->agreement_number }}">
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr class="bg-white border-b">
                                <td colspan="8" class="px-6 py-4 text-center text-default-500">Tidak ada perjanjian
                                    ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination Links --}}
            <div class="mt-4">
                {{ $agreements->appends(['search' => request('search')])->links('vendor.pagination.tailwind') }}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- SweetAlert2 for Delete Confirmation (Agreement) ---
            document.getElementById('agreements-table-container').addEventListener('click', function(e) {
                if (e.target && e.target.classList.contains('delete-agreement-btn')) {
                    e.preventDefault();

                    const agreementId = e.target.dataset.agreementId;
                    const agreementNumber = e.target.dataset.agreementNumber;

                    if (typeof Swal === 'undefined') {
                        console.error('SweetAlert2 (Swal) is not loaded.');
                        alert(
                            `Gagal menghapus perjanjian ${agreementNumber}. SweetAlert2 tidak ditemukan.`
                        );
                        return;
                    }

                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: `Anda akan menghapus perjanjian "${agreementNumber}". Data yang dihapus tidak dapat dikembalikan! Ini juga akan membebaskan lokasi parkir terkait.`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('delete-form-agreement-' + agreementId)
                                .submit();
                        }
                    });
                }
            });

            // --- SweetAlert2 Success Message ---
            const successAlert = document.getElementById('success-alert');
            if (successAlert) {
                const agreementNumber = successAlert.dataset.name;
                if (typeof Swal === 'undefined') {
                    console.error('SweetAlert2 (Swal) is not loaded for success message.');
                    alert(`Perjanjian "${agreementNumber}" berhasil ditambahkan.`);
                    return;
                }
                Swal.fire({
                    title: 'Berhasil!',
                    text: `Perjanjian "${agreementNumber}" berhasil ditambahkan.`,
                    icon: 'success',
                    confirmButtonText: 'OK',
                    timer: 3000,
                    timerProgressBar: true
                });
            }
        });
    </script>
@endpush
