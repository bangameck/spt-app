@extends('layouts.app')

@section('title', 'Daftar Field Coordinator')

@section('content')
    <div class="container-fluid">
        <div class="flex justify-between items-center mb-6">
            <h4 class="text-default-900 text-2xl font-bold">Daftar Field Coordinator (Korlap)</h4>
            <a href="{{ route('admin.field-coordinators.create') }}"
                class="px-6 py-2 rounded-md text-white bg-primary-600 hover:bg-primary-700 transition-all">
                Tambah Korlap Baru
            </a>
        </div>

        {{-- SweetAlert2 Success Message (akan ditampilkan oleh JS) --}}
        @if (session('success') && session('korlap_name'))
            <div id="success-alert" data-name="{{ session('korlap_name') }}" style="display: none;"></div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline">{{ session('error') }}</span>
                <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                    <svg class="fill-current h-6 w-6 text-red-500" role="button"
                        xmlns="[http://www.w3.org/2000/svg](http://www.w3.org/2000/svg)" viewBox="0 0 20 20">
                        <title>Close</title>
                        <path
                            d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.697l-2.651 2.652a1.2 1.2 0 1 1-1.697-1.697L8.303 10 5.651 7.348a1.2 1.2 0 1 1 1.697-1.697L10 8.303l2.651-2.652a1.2 1.2 0 0 1 1.697 1.697L11.697 10l2.651 2.651a1.2 1.2 0 0 1 0 1.698z" />
                    </svg>
                </span>
            </div>
        @endif

        <div class="card bg-white shadow rounded-lg p-6">
            {{-- START: Search Form --}}
            <form action="{{ route('admin.users.index') }}" method="GET" class="mb-4">
                <div class="flex items-center gap-2">
                    <input type="text" name="search" placeholder="Cari pengguna..."
                        class="form-input flex-grow px-4 py-2 border rounded-md text-default-800 focus:ring-primary-500 focus:border-primary-500"
                        value="{{ $search ?? '' }}"> {{-- Mempertahankan nilai pencarian --}}
                    <button type="submit"
                        class="px-4 py-2 rounded-md bg-primary-600 text-white hover:bg-primary-700 transition-all">
                        Cari
                    </button>
                    @if ($search)
                        <a href="{{ route('admin.field-coordinators.index') }}"
                            class="px-4 py-2 rounded-md bg-default-200 text-default-800 hover:bg-default-300 transition-all">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
            {{-- END: Search Form --}}
            <div id="korlaps-table-container" class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left text-default-500">
                    <thead class="text-xs text-default-700 uppercase bg-default-50">
                        <tr>
                            <th scope="col" class="px-6 py-3">Nama Korlap</th>
                            <th scope="col" class="px-6 py-3">Phone</th> {{-- Diubah dari Posisi --}}
                            <th scope="col" class="px-6 py-3">Alamat</th> {{-- Diubah dari Posisi --}}
                            <th scope="col" class="px-6 py-3">Foto KTP</th> {{-- Diubah dari No. KTP --}}
                            <th scope="col" class="px-6 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($fieldCoordinators as $korlap)
                            <tr class="bg-white border-b hover:bg-default-50">
                                <td class="px-6 py-4 font-medium text-default-900 whitespace-nowrap">
                                    @if ($korlap->user)
                                        <div class="flex items-center gap-3">
                                            @if ($korlap->user->img)
                                                <img src="{{ asset($korlap->user->img) }}" alt="{{ $korlap->user->name }}"
                                                    class="h-10 w-10 rounded-full object-cover">
                                            @else
                                                <div
                                                    class="h-10 w-10 rounded-full bg-default-200 flex items-center justify-center text-default-600 text-lg font-bold">
                                                    {{ substr($korlap->user->name, 0, 1) }}
                                                </div>
                                            @endif
                                            <span>{{ $korlap->user->name }}</span>
                                        </div>
                                    @else
                                        <span class="text-default-500">User Tidak Ditemukan</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">{{ $korlap->phone_number }}</td> {{-- Menampilkan Alamat --}}
                                <td class="px-6 py-4">{{ $korlap->address }}</td> {{-- Menampilkan Alamat --}}
                                <td class="px-6 py-4">
                                    @if ($korlap->id_card_img)
                                        <img src="{{ asset($korlap->id_card_img) }}" alt="Foto KTP"
                                            class="h-10 w-auto object-contain rounded-md">
                                    @else
                                        <span class="text-default-500">Tidak ada foto KTP</span>
                                    @endif
                                </td> {{-- Menampilkan Foto KTP --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        <a href="{{ route('admin.field-coordinators.edit', $korlap) }}"
                                            class="font-medium text-blue-600 hover:underline">Edit</a>
                                        <form id="delete-form-korlap-{{ $korlap->id }}"
                                            action="{{ route('admin.field-coordinators.destroy', $korlap->id) }}"
                                            method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                        <button type="button"
                                            class="text-red-600 hover:text-red-900 ml-2 delete-korlap-btn"
                                            data-korlap-id="{{ $korlap->id }}"
                                            data-korlap-name="{{ $korlap->user->name ?? 'Korlap Tidak Dikenal' }}">
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr class="bg-white border-b">
                                <td colspan="4" class="px-6 py-4 text-center text-default-500">Tidak ada Field
                                    Coordinator ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- START: Pagination Links --}}
            <div class="mt-4">
                {{ $fieldCoordinators->appends(['search' => request('search')])->links('vendor.pagination.tailwind') }}
            </div>
            {{-- END: Pagination Links --}}
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- SweetAlert2 for Delete Confirmation (Korlap) ---
            document.getElementById('korlaps-table-container').addEventListener('click', function(e) {
                if (e.target && e.target.classList.contains('delete-korlap-btn')) {
                    e.preventDefault();

                    const korlapId = e.target.dataset.korlapId;
                    const korlapName = e.target.dataset.korlapName;

                    if (typeof Swal === 'undefined') {
                        console.error('SweetAlert2 (Swal) is not loaded for delete confirmation.');
                        alert(`Gagal menghapus ${korlapName}. SweetAlert2 tidak ditemukan.`);
                        return;
                    }

                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: `Anda akan menghapus data Field Coordinator "${korlapName}". Data yang dihapus tidak dapat dikembalikan!`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('delete-form-korlap-' + korlapId).submit();
                        }
                    });
                }
            });

            // --- SweetAlert2 Success Message (untuk setelah create/update) ---
            const successAlert = document.getElementById('success-alert');
            if (successAlert) {
                const korlapName = successAlert.dataset.name;
                if (typeof Swal === 'undefined') {
                    console.error('SweetAlert2 (Swal) is not loaded for success message.');
                    alert(`Data Field Coordinator "${korlapName}" berhasil ditambahkan.`);
                    return;
                }
                Swal.fire({
                    title: 'Berhasil!',
                    text: `Data Field Coordinator "${korlapName}" berhasil ditambahkan.`,
                    icon: 'success',
                    confirmButtonText: 'OK',
                    timer: 3000,
                    timerProgressBar: true
                });
            }
        });
    </script>
@endpush
