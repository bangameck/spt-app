@extends('layouts.app')

@section('title', 'Edit Transaksi Setoran')

@section('content')
    <div class="container-fluid">
        <div class="flex justify-between items-center mb-6">
            <h4 class="text-default-900 text-2xl font-bold">Edit Transaksi Setoran:
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
                <a href="{{ route('masterdata.deposit-transactions.index') }}"
                    class="px-6 py-2 rounded-md text-primary-600 border border-primary-600 hover:bg-primary-600 hover:text-white transition-all">
                    Kembali ke Daftar Transaksi Setoran
                </a>
            </div>
        </div>

        {{-- Error Messages dari Laravel Validation --}}
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Oops!</strong>
                <span class="block sm:inline">Ada beberapa masalah dengan input Anda.</span>
                <ul class="mt-3 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
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
            <form action="{{ route('masterdata.deposit-transactions.update', $depositTransaction) }}" method="POST">
                @csrf
                @method('PUT') {{-- Penting: Gunakan method PUT untuk update --}}

                <h5 class="text-lg font-semibold text-default-800 mb-4">Detail Setoran</h5>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="agreement_id" class="block text-sm font-medium text-default-700 mb-2">Pilih Perjanjian
                            Aktif</label>
                        <select name="agreement_id" id="agreement_id"
                            class="form-select w-full px-4 py-2 border rounded-md text-default-800 focus:ring-primary-500 focus:border-primary-500 @error('agreement_id') border-red-500 @enderror select2-agreements"
                            {{-- Tambahkan class select2-agreements --}} required {{ $depositTransaction->is_validated ? 'disabled' : '' }}>
                            {{-- Disabled jika sudah divalidasi --}}
                            @php
                                // Ambil perjanjian yang sedang diedit atau dari old input
                                $currentAgreement = $depositTransaction->agreement;
                                if (old('agreement_id')) {
                                    $currentAgreement = App\Models\Agreement::find(old('agreement_id'));
                                }
                            @endphp
                            @if ($currentAgreement)
                                <option value="{{ $currentAgreement->id }}" selected>
                                    {{ $currentAgreement->agreement_number }} (Korlap:
                                    {{ $currentAgreement->fieldCoordinator->user->name ?? 'N/A' }})
                                    @if ($currentAgreement->parkingLocations->isNotEmpty())
                                        - Lokasi: {{ $currentAgreement->parkingLocations->pluck('name')->join(', ') }}
                                    @endif
                                </option>
                            @else
                                <option value="">Cari atau pilih perjanjian...</option> {{-- Placeholder untuk Select2 --}}
                            @endif
                        </select>
                        @error('agreement_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="deposit_date" class="block text-sm font-medium text-default-700 mb-2">Tanggal
                            Setoran</label>
                        <input type="date" name="deposit_date" id="deposit_date"
                            class="form-input w-full px-4 py-2 border rounded-md text-default-800 focus:ring-primary-500 focus:border-primary-500 @error('deposit_date') border-red-500 @enderror"
                            value="{{ old('deposit_date', $depositTransaction->deposit_date?->format('Y-m-d')) }}" required
                            {{ $depositTransaction->is_validated ? 'readonly' : '' }}> {{-- Readonly jika sudah divalidasi --}}
                        @error('deposit_date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label for="amount" class="block text-sm font-medium text-default-700 mb-2">Jumlah Setoran
                            (Rp)</label>
                        <input type="number" name="amount" id="amount"
                            class="form-input w-full px-4 py-2 border rounded-md text-default-800 focus:ring-primary-500 focus:border-primary-500 @error('amount') border-red-500 @enderror"
                            value="{{ old('amount', $depositTransaction->amount) }}" placeholder="Contoh: 50000"
                            min="0" required {{ $depositTransaction->is_validated ? 'readonly' : '' }}>
                        {{-- Readonly jika sudah divalidasi --}}
                        @error('amount')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label for="notes" class="block text-sm font-medium text-default-700 mb-2">Catatan
                            (Opsional)</label>
                        <textarea name="notes" id="notes" rows="3"
                            class="form-textarea w-full px-4 py-2 border rounded-md text-default-800 focus:ring-primary-500 focus:border-primary-500 @error('notes') border-red-500 @enderror"
                            placeholder="Masukkan catatan tambahan..." {{ $depositTransaction->is_validated ? 'readonly' : '' }}>{{ old('notes', $depositTransaction->notes) }}</textarea> {{-- Readonly jika sudah divalidasi --}}
                        @error('notes')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    {{-- Tombol Simpan hanya jika belum divalidasi --}}
                    @if (!$depositTransaction->is_validated)
                        <button type="submit"
                            class="px-6 py-2 rounded-md text-white bg-primary-600 hover:bg-primary-700 transition-all">
                            Simpan Perubahan
                        </button>
                    @endif
                    <a href="{{ route('masterdata.deposit-transactions.index') }}"
                        class="px-6 py-2 rounded-md text-default-600 border border-default-300 hover:bg-default-50 transition-all">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- Client-side validation for amount to only allow numbers and decimals ---
            const amountInput = document.getElementById('amount');
            if (amountInput) {
                amountInput.addEventListener('input', function(e) {
                    let value = e.target.value;
                    value = value.replace(/[^0-9.]/g, ''); // Remove non-numeric except dot
                    const parts = value.split('.');
                    if (parts.length > 2) {
                        value = parts[0] + '.' + parts.slice(1).join(''); // Allow only one dot
                    }
                    e.target.value = value;
                });
            }

            // --- Initialize Select2 with AJAX for Agreements ---
            const agreementSelect = $('.select2-agreements'); // Get jQuery object for Select2

            agreementSelect.select2({
                placeholder: 'Cari atau pilih perjanjian...',
                allowClear: true, // Allows clearing the selection
                width: 'resolve', // Ensures Select2 takes up 100% width
                ajax: {
                    url: '{{ route('masterdata.search-active-agreements') }}', // Endpoint AJAX
                    dataType: 'json',
                    delay: 250, // Delay dalam ms sebelum request dikirim
                    data: function(params) {
                        return {
                            term: params.term, // Search term
                            page: params.page // Current page (for pagination in Select2)
                        };
                    },
                    processResults: function(data, params) {
                        params.page = params.page || 1;
                        return {
                            results: data.results,
                            pagination: {
                                more: (params.page * 10) < data
                                    .total // Asumsi backend mengembalikan 'total' jika ada pagination
                            }
                        };
                    },
                    cache: true
                },
                minimumInputLength: 1 // Minimum karakter untuk memulai pencarian
            });

            // --- Disable Select2 if the transaction is validated ---
            // Check if the select element itself is disabled by Blade
            const isSelectDisabledByBlade = agreementSelect.prop('disabled');

            if (isSelectDisabledByBlade) {
                agreementSelect.select2('destroy'); // Destroy Select2 instance
                agreementSelect.prop('disabled', true); // Ensure it's truly disabled
                // You might want to add a visual cue that it's disabled, e.g., a gray background
                agreementSelect.next('.select2-container').find('.select2-selection').css('background-color',
                    '#e9ecef');
            }
        });
    </script>
@endpush
