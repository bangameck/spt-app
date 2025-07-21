@extends('layouts.app')

@section('title', 'Detail Transaksi Setoran')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row invoice-preview">
            <div class="col-xl-9 col-md-8 col-12 mb-md-0 mb-6">
                <div class="card invoice-preview-card p-sm-12 p-6">
                    <div class="card-body invoice-preview-header rounded-4 p-6"
                        style="background-color: rgba(38, 43, 64, .03);">
                        <div
                            class="d-flex justify-content-between flex-xl-row flex-md-column flex-sm-row flex-column text-heading align-items-xl-center align-items-md-start align-items-sm-center flex-wrap gap-6">
                            <div>
                                <div class="d-flex svg-illustration align-items-center gap-2 mb-6">
                                    <span class="app-brand-logo demo">
                                        {{-- Ganti dengan logo Anda jika perlu --}}
                                        <img src="{{ asset('assets/img/logo-spt.png') }}" alt="Logo" height="32">
                                    </span>
                                    <span class="mb-0 app-brand-text fw-semibold">SPK-APP</span>
                                </div>
                                <p class="mb-1">UPT Perparkiran Dishub Pekanbaru</p>
                                <p class="mb-0">Jl. Lintas Timur KM 11, Tenayan Raya</p>
                            </div>
                            <div>
                                <h5 class="mb-6 text-nowrap">BUKTI SETORAN #{{ $depositTransaction->id }}</h5>
                                <div class="mb-1">
                                    <span>Tanggal Setor:</span>
                                    <span
                                        class="fw-medium">{{ $depositTransaction->deposit_date->translatedFormat('d F Y') }}</span>
                                </div>
                                <div>
                                    <span>Status:</span>
                                    @if ($depositTransaction->is_validated)
                                        <span class="badge bg-label-success rounded-pill">Tervalidasi</span>
                                    @else
                                        <span class="badge bg-label-warning rounded-pill">Pending</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body py-6 px-0">
                        <div class="d-flex justify-content-between flex-wrap gap-6">
                            <div>
                                <h6>Informasi Perjanjian:</h6>
                                <p class="mb-1 fw-medium">{{ $depositTransaction->agreement->agreement_number }}</p>
                                <p class="mb-1">Korlap:
                                    {{ $depositTransaction->agreement->fieldCoordinator->user->name ?? 'N/A' }}</p>
                                <p class="mb-0">Pimpinan:
                                    {{ $depositTransaction->agreement->leader->user->name ?? 'N/A' }}</p>
                            </div>
                            <div class="text-end">
                                <h6>Dicatat Oleh:</h6>
                                <p class="mb-1">{{ $depositTransaction->creator->name ?? 'N/A' }}</p>
                                <p class="mb-0"><small>Pada:
                                        {{ $depositTransaction->created_at->translatedFormat('d M Y, H:i') }}</small></p>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive border rounded-4 border-bottom-0">
                        <table class="table m-0">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Deskripsi</th>
                                    <th class="text-end">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-nowrap text-heading">Setoran Harian</td>
                                    <td class="text-nowrap">Pembayaran untuk tanggal
                                        {{ $depositTransaction->deposit_date->translatedFormat('d F Y') }}</td>
                                    <td class="text-end">Rp {{ number_format($depositTransaction->amount, 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="table-responsive">
                        <table class="table m-0 table-borderless">
                            <tbody>
                                <tr>
                                    <td class="align-top px-0 py-6">
                                        <p class="mb-1"><span class="me-2 fw-medium text-heading">Catatan:</span></p>
                                        <span>{{ $depositTransaction->notes ?? 'Tidak ada catatan.' }}</span>
                                    </td>
                                    <td class="text-end pe-0 py-6 w-px-100">
                                        <p class="mb-0 pt-2 fw-bold text-heading">Total:</p>
                                    </td>
                                    <td class="text-end px-0 py-6 w-px-100">
                                        <p class="fw-bold mb-0 pt-2">Rp
                                            {{ number_format($depositTransaction->amount, 0, ',', '.') }}</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    {{-- ✅ Bagian Bukti Transfer --}}
                    @if ($depositTransaction->proof_of_transfer)
                        <hr class="mt-0 mb-6" />
                        <div class="card-body p-0">
                            <h6 class="mb-4">Bukti Transfer:</h6>
                            <a href="{{ asset($depositTransaction->proof_of_transfer) }}" target="_blank">
                                <img src="{{ asset($depositTransaction->proof_of_transfer) }}" alt="Bukti Transfer"
                                    class="img-fluid rounded-3 border">
                            </a>
                        </div>
                    @endif
                </div>
            </div>
            <div class="col-xl-3 col-md-4 col-12 invoice-actions">
                <div class="card">
                    <div class="card-body">
                        {{-- Tombol Validasi --}}
                        @if (!$depositTransaction->is_validated && (Auth::user()->isAdmin() || Auth::user()->isLeader()))
                            <form action="{{ route('masterdata.deposit-transactions.validate', $depositTransaction->id) }}"
                                method="POST" class="form-validate">
                                @csrf
                                <button type="submit" class="btn btn-success d-grid w-100 mb-4">
                                    <span class="d-flex align-items-center justify-content-center text-nowrap">
                                        <i class="icon-base ri ri-check-double-line me-2"></i>Validasi Setoran
                                    </span>
                                </button>
                            </form>
                        @endif

                        {{-- ✅ TOMBOL CETAK DITAMBAHKAN DI SINI --}}
                        <a href="{{ route('masterdata.deposit-transactions.pdf', $depositTransaction->id) }}"
                            target="_blank" class="btn btn-outline-primary d-grid w-100 mb-4">
                            <span class="d-flex align-items-center justify-content-center text-nowrap">
                                <i class="icon-base ri ri-printer-line me-2"></i>Cetak Bukti Setor
                            </span>
                        </a>

                        {{-- Tombol Edit & Kembali --}}
                        <a href="{{ route('masterdata.deposit-transactions.edit', $depositTransaction->id) }}"
                            class="btn btn-outline-secondary d-grid w-100 mb-4 {{ $depositTransaction->is_validated ? 'disabled' : '' }}">Edit</a>
                        <a href="{{ route('masterdata.deposit-transactions.index') }}"
                            class="btn btn-outline-secondary d-grid w-100">Kembali</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Konfirmasi Validasi
            $('.form-validate').on('submit', function(event) {
                event.preventDefault();
                const form = this;
                Swal.fire({
                    title: 'Validasi Setoran Ini?',
                    text: "Tindakan ini tidak dapat dibatalkan.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6f6b7d',
                    confirmButtonText: 'Ya, Validasi!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush
