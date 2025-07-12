@extends('layouts.app')

@section('title', 'Daftar Transaksi Setoran')

@section('content')
    <div class="container-fluid">
        <div class="flex justify-between items-center mb-6">
            <h4 class="text-default-900 text-2xl font-bold">Daftar Transaksi Setoran</h4>
            <a href="{{ route('masterdata.deposit-transactions.create') }}"
                class="px-6 py-2 rounded-md text-white bg-primary-600 hover:bg-primary-700 transition-all">
                Catat Setoran Baru
            </a>
        </div>

        {{-- SweetAlert2 Success Message --}}
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
            <form action="{{ route('masterdata.deposit-transactions.index') }}" method="GET" class="mb-4">
                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    <div>
                        <label for="search_date" class="block text-sm font-medium text-default-700 mb-2">Tanggal
                            Spesifik</label>
                        <input type="date" name="search_date" id="search_date"
                            class="form-input w-full px-4 py-2 border rounded-md text-default-800 focus:ring-primary-500 focus:border-primary-500"
                            value="{{ $searchDate ?? '' }}">
                    </div>
                    <div>
                        <label for="search_month" class="block text-sm font-medium text-default-700 mb-2">Bulan</label>
                        <select name="search_month" id="search_month"
                            class="form-select w-full px-4 py-2 border rounded-md text-default-800 focus:ring-primary-500 focus:border-primary-500">
                            <option value="">Semua Bulan</option>
                            @for ($m = 1; $m <= 12; $m++)
                                <option value="{{ sprintf('%02d', $m) }}"
                                    {{ ($searchMonth ?? '') == sprintf('%02d', $m) ? 'selected' : '' }}>
                                    {{ Carbon\Carbon::createFromDate(null, $m, 1)->translatedFormat('F') }}</option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label for="search_year" class="block text-sm font-medium text-default-700 mb-2">Tahun</label>
                        <input type="number" name="search_year" id="search_year" min="2020" max="{{ date('Y') + 5 }}"
                            class="form-input w-full px-4 py-2 border rounded-md text-default-800 focus:ring-primary-500 focus:border-primary-500"
                            value="{{ $searchYear ?? date('Y') }}"> {{-- Default ke tahun sekarang --}}
                    </div>
                    <div class="lg:col-span-1 md:col-span-3"> {{-- Rentang tanggal bisa lebih panjang --}}
                        <label for="start_date_range" class="block text-sm font-medium text-default-700 mb-2">Rentang
                            Waktu</label>
                        <div class="flex items-center gap-2">
                            <input type="date" name="start_date_range" id="start_date_range"
                                class="form-input w-1/2 px-4 py-2 border rounded-md text-default-800 focus:ring-primary-500 focus:border-primary-500"
                                value="{{ $startDateRange ?? '' }}">
                            <span>-</span>
                            <input type="date" name="end_date_range" id="end_date_range"
                                class="form-input w-1/2 px-4 py-2 border rounded-md text-default-800 focus:ring-primary-500 focus:border-primary-500"
                                value="{{ $endDateRange ?? '' }}">
                        </div>
                    </div>
                    <div class="md:col-span-3 lg:col-span-4 flex items-center gap-2 mt-2">
                        <input type="text" name="search" placeholder="Cari nomor perjanjian / nama korlap / kreator..."
                            class="form-input flex-grow px-4 py-2 border rounded-md text-default-800 focus:ring-primary-500 focus:border-primary-500"
                            value="{{ $search ?? '' }}">
                        <button type="submit"
                            class="px-4 py-2 rounded-md bg-primary-600 text-white hover:bg-primary-700 transition-all">
                            Cari
                        </button>
                        @if ($search || $searchDate || $searchMonth || $searchYear || $startDateRange || $endDateRange)
                            <a href="{{ route('masterdata.deposit-transactions.index') }}"
                                class="px-4 py-2 rounded-md bg-default-200 text-default-800 hover:bg-default-300 transition-all">
                                Reset
                            </a>
                        @endif
                    </div>
                </div>
            </form>

            <div id="deposit-transactions-table-container" class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left text-default-500">
                    <thead class="text-xs text-default-700 uppercase bg-default-50">
                        <tr>
                            <th scope="col" class="px-6 py-3">No. Perjanjian</th>
                            <th scope="col" class="px-6 py-3">Korlap</th>
                            <th scope="col" class="px-6 py-3">Tanggal Setoran</th>
                            <th scope="col" class="px-6 py-3">Jumlah Setoran (Rp)</th>
                            <th scope="col" class="px-6 py-3">Status Validasi</th>
                            <th scope="col" class="px-6 py-3">Input Oleh</th>
                            <th scope="col" class="px-6 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($depositTransactions as $transaction)
                            <tr class="bg-white border-b hover:bg-default-50">
                                <td class="px-6 py-4 font-medium text-default-900 whitespace-nowrap">
                                    {{ $transaction->agreement->agreement_number ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $transaction->agreement->fieldCoordinator->user->name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $transaction->deposit_date->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center gap-1.5 py-1 px-3 rounded-full text-xs font-medium {{ $transaction->is_validated ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $transaction->is_validated ? 'Divalidasi' : 'Belum Divalidasi' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    {{ $transaction->creator->name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        @if (!$transaction->is_validated && (Auth::user()->isAdmin() || Auth::user()->isLeader()))
                                            <form id="validate-form-{{ $transaction->id }}"
                                                action="{{ route('masterdata.deposit-transactions.validate', $transaction->id) }}"
                                                method="POST" style="display: none;">
                                                @csrf
                                            </form>
                                            <button type="button"
                                                class="font-medium text-green-600 hover:underline validate-deposit-btn"
                                                data-transaction-id="{{ $transaction->id }}"
                                                data-transaction-amount="{{ number_format($transaction->amount, 0, ',', '.') }}"
                                                data-agreement-number="{{ $transaction->agreement->agreement_number ?? 'N/A' }}">
                                                Validasi
                                            </button>
                                        @endif
                                        <a href="{{ route('masterdata.deposit-transactions.edit', $transaction) }}"
                                            class="font-medium text-blue-600 hover:underline">Edit</a>
                                        <form id="delete-form-deposit-transaction-{{ $transaction->id }}"
                                            action="{{ route('masterdata.deposit-transactions.destroy', $transaction->id) }}"
                                            method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                        <button type="button"
                                            class="font-medium text-red-600 hover:underline ml-2 delete-deposit-transaction-btn"
                                            data-transaction-id="{{ $transaction->id }}"
                                            data-transaction-amount="{{ number_format($transaction->amount, 0, ',', '.') }}"
                                            data-agreement-number="{{ $transaction->agreement->agreement_number ?? 'N/A' }}">
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr class="bg-white border-b">
                                <td colspan="7" class="px-6 py-4 text-center text-default-500">Tidak ada transaksi
                                    setoran ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination Links --}}
            <div class="mt-4">
                {{ $depositTransactions->appends(request()->except('page'))->links('vendor.pagination.tailwind') }}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- SweetAlert2 for Delete Confirmation (Deposit Transaction) ---
            document.getElementById('deposit-transactions-table-container').addEventListener('click', function(e) {
                if (e.target && e.target.classList.contains('delete-deposit-transaction-btn')) {
                    e.preventDefault();

                    const transactionId = e.target.dataset.transactionId;
                    const agreementNumber = e.target.dataset.agreementNumber;
                    const transactionAmount = e.target.dataset.transactionAmount;

                    if (typeof Swal === 'undefined') {
                        console.error('SweetAlert2 (Swal) is not loaded.');
                        alert(`Gagal menghapus setoran. SweetAlert2 tidak ditemukan.`);
                        return;
                    }

                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: `Anda akan menghapus setoran sebesar Rp ${transactionAmount} untuk perjanjian ${agreementNumber}. Data yang dihapus tidak dapat dikembalikan!`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('delete-form-deposit-transaction-' +
                                transactionId).submit();
                        }
                    });
                }
            });

            // --- SweetAlert2 for Validate Deposit Button ---
            document.getElementById('deposit-transactions-table-container').addEventListener('click', function(e) {
                if (e.target && e.target.classList.contains('validate-deposit-btn')) {
                    e.preventDefault();

                    const transactionId = e.target.dataset.transactionId;
                    const agreementNumber = e.target.dataset.agreementNumber;
                    const transactionAmount = e.target.dataset.transactionAmount;

                    if (typeof Swal === 'undefined') {
                        console.error('SweetAlert2 (Swal) is not loaded.');
                        alert(`Gagal memvalidasi setoran. SweetAlert2 tidak ditemukan.`);
                        return;
                    }

                    Swal.fire({
                        title: 'Validasi Setoran?',
                        text: `Anda yakin ingin memvalidasi setoran sebesar Rp ${transactionAmount} untuk perjanjian ${agreementNumber}?`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#28a745',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, Validasi!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('validate-form-' + transactionId).submit();
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
