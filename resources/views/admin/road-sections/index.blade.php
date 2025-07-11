@extends('layouts.app')

@section('title', 'Daftar Ruas Jalan')

@section('content')
    <div class="container-fluid">
        <div class="flex justify-between items-center mb-6">
            <h4 class="text-default-900 text-2xl font-bold">Daftar Ruas Jalan</h4>
            <a href="{{ route('masterdata.road-sections.create') }}"
                class="px-6 py-2 rounded-md text-white bg-primary-600 hover:bg-primary-700 transition-all">
                Tambah Ruas Jalan Baru
            </a>
        </div>

        {{-- SweetAlert2 Success Message (akan ditampilkan oleh JS) --}}
        @if (session('success'))
            <div id="success-alert" data-message="{{ session('success') }}" style="display: none;"></div>
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
            <form action="{{ route('masterdata.road-sections.index') }}" method="GET" class="mb-4">
                <div class="flex items-center gap-2">
                    <input type="text" name="search" placeholder="Cari ruas jalan..."
                        class="form-input flex-grow px-4 py-2 border rounded-md text-default-800 focus:ring-primary-500 focus:border-primary-500"
                        value="{{ $search ?? '' }}">
                    <button type="submit"
                        class="px-4 py-2 rounded-md bg-primary-600 text-white hover:bg-primary-700 transition-all">
                        Cari
                    </button>
                    @if ($search)
                        <a href="{{ route('admin.road-sections.index') }}"
                            class="px-4 py-2 rounded-md bg-default-200 text-default-800 hover:bg-default-300 transition-all">
                            Reset
                        </a>
                    @endif
                </div>
            </form>

            <div id="road-sections-table-container" class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left text-default-500">
                    <thead class="text-xs text-default-700 uppercase bg-default-50">
                        <tr>
                            <th scope="col" class="px-6 py-3">Nama Ruas Jalan</th>
                            <th scope="col" class="px-6 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($roadSections as $roadSection)
                            <tr class="bg-white border-b hover:bg-default-50">
                                <td class="px-6 py-4 font-medium text-default-900 whitespace-nowrap">
                                    {{ $roadSection->name }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        <a href="{{ route('masterdata.road-sections.edit', $roadSection) }}"
                                            class="font-medium text-blue-600 hover:underline">Edit</a>
                                        <form id="delete-form-road-section-{{ $roadSection->id }}"
                                            action="{{ route('masterdata.road-sections.destroy', $roadSection->id) }}"
                                            method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                        <button type="button"
                                            class="text-red-600 hover:text-red-900 ml-2 delete-road-section-btn"
                                            data-road-section-id="{{ $roadSection->id }}"
                                            data-road-section-name="{{ $roadSection->name }}">
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr class="bg-white border-b">
                                <td colspan="2" class="px-6 py-4 text-center text-default-500">Tidak ada ruas jalan
                                    ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination Links --}}
            <div class="mt-4">
                {{ $roadSections->appends(['search' => request('search')])->links('vendor.pagination.tailwind') }}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- SweetAlert2 for Delete Confirmation (Road Section) ---
            document.getElementById('road-sections-table-container').addEventListener('click', function(e) {
                if (e.target && e.target.classList.contains('delete-road-section-btn')) {
                    e.preventDefault();

                    const roadSectionId = e.target.dataset.roadSectionId;
                    const roadSectionName = e.target.dataset.roadSectionName;

                    if (typeof Swal === 'undefined') {
                        console.error('SweetAlert2 (Swal) is not loaded.');
                        alert(`Gagal menghapus ${roadSectionName}. SweetAlert2 tidak ditemukan.`);
                        return;
                    }

                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: `Anda akan menghapus ruas jalan "${roadSectionName}". Data yang dihapus tidak dapat dikembalikan! Ini juga akan menghapus lokasi parkir terkait!`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('delete-form-road-section-' + roadSectionId)
                                .submit();
                        }
                    });
                }
            });

            // --- SweetAlert2 Success Message ---
            const successAlert = document.getElementById('success-alert');
            if (successAlert) {
                const message = successAlert.dataset.message;
                if (typeof Swal === 'undefined') {
                    console.error('SweetAlert2 (Swal) is not loaded for success message.');
                    alert(message);
                    return;
                }
                Swal.fire({
                    title: 'Berhasil!',
                    text: message,
                    icon: 'success',
                    confirmButtonText: 'OK',
                    timer: 3000,
                    timerProgressBar: true
                });
            }
        });
    </script>
@endpush
