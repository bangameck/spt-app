@extends('layouts.app')

@section('title', 'Detail Transaksi Setoran')

@section('content')
    <div class="container-fluid">
        <div class="flex justify-between items-center mb-6">
            <h4 class="text-default-900 text-2xl font-bold">Detail Transaksi Setoran:
                {{ $depositTransaction->agreement->agreement_number ?? 'N/A' }}</h4>
            <div class="flex items-center gap-2">
                @if (!$depositTransaction->is_validated && (Auth::user()->isAdmin() || Auth::user()->isLeader()))
                    <form id="validate-form-{{ $depositTransaction->id }}"
                        action="{{ route('masterdata.deposit-transactions.validate', $depositTransaction->id) }}"
                        method="POST" style="display: none;">
                        @csrf
                    </form>
                    <button type="button"
                        class="px-6 py-2 rounded-md text-white bg-green-600 hover:bg-green-700 transition-all validate-deposit-btn"
                        data-transaction-id="{{ $depositTransaction->id }}"
                        data-transaction-amount="{{ number_format($depositTransaction->amount, 0, ',', '.') }}"
                        data-agreement-number="{{ $depositTransaction->agreement->agreement_number ?? 'N/A' }}">
                        Validasi Setoran
                    </button>
                @endif
                <a href="{{ route('masterdata.deposit-transactions.edit', $depositTransaction) }}"
                    class="px-6 py-2 rounded-md text-white bg-blue-600 hover:bg-blue-700 transition-all">
                    Edit Setoran
                </a>
                <a href="{{ route('masterdata.deposit-transactions.index') }}"
                    class="px-6 py-2 rounded-md text-primary-600 border border-primary-600 hover:bg-primary-600 hover:text-white transition-all">
                    Kembali ke Daftar Transaksi Setoran
                </a>
            </div>
        </div>

        <div class="card bg-white shadow rounded-lg p-6 mb-6">
            <h5 class="text-lg font-semibold text-default-800 mb-4">Informasi Transaksi</h5>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm font-medium text-default-700">Nomor Perjanjian:</p>
                    <p class="text-default-900">{{ $depositTransaction->agreement->agreement_number ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-default-700">Koordinator Lapangan:</p>
                    <p class="text-default-900">{{ $depositTransaction->agreement->fieldCoordinator->user->name ?? 'N/A' }}
                    </p>
                </div>
                <div>
                    <p class="text-sm font-medium text-default-700">Tanggal Setoran:</p>
                    <p class="text-default-900">{{ $depositTransaction->deposit_date->format('d M Y') }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-default-700">Jumlah Setoran:</p>
                    <p class="text-default-900">Rp {{ number_format($depositTransaction->amount, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-default-700">Status Validasi:</p>
                    <p class="text-default-900">
                        <span
                            class="inline-flex items-center gap-1.5 py-1 px-3 rounded-full text-xs font-medium {{ $depositTransaction->is_validated ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $depositTransaction->is_validated ? 'Divalidasi' : 'Belum Divalidasi' }}
                        </span>
                    </p>
                </div>
                @if ($depositTransaction->is_validated)
                    <div>
                        <p class="text-sm font-medium text-default-700">Tanggal Validasi:</p>
                        <p class="text-default-900">{{ $depositTransaction->validated_date?->format('d M Y H:i') ?? '-' }}
                        </p>
                    </div>
                @endif
                <div>
                    <p class="text-sm font-medium text-default-700">Dicatat Oleh:</p>
                    <p class="text-default-900">{{ $depositTransaction->creator->name ?? 'N/A' }}</p>
                </div>
                <div class="md:col-span-2">
                    <p class="text-sm font-medium text-default-700">Catatan:</p>
                    <p class="text-default-900">{{ $depositTransaction->notes ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- SweetAlert2 for Validate Deposit Button (same as index, but for show page) ---
            document.addEventListener('click', function(e) {
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
        });
    </script>
@endpush
