@extends('layouts.app')

@section('title', 'Catat Setoran Baru')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
@endpush

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Catat Setoran Baru</h4>
        <div class="d-flex align-items-center">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-style1 mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Master Data</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('masterdata.deposit-transactions.index') }}">Transaksi
                            Setoran</a></li>
                    <li class="breadcrumb-item active">Tambah</li>
                </ol>
            </nav>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            <p class="mb-0"><strong>Oops! Terjadi beberapa kesalahan:</strong></p>
            <ul class="mt-2 mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Detail Setoran</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('masterdata.deposit-transactions.store') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="row g-6">
                    <div class="col-12">
                        <label for="agreement_id" class="form-label">Pilih Perjanjian Aktif</label>
                        <select name="agreement_id" id="agreement_id" class="form-select select2-agreements" required>
                            @if (old('agreement_id'))
                                @php $oldAgreement = \App\Models\Agreement::find(old('agreement_id')); @endphp
                                @if ($oldAgreement)
                                    <option value="{{ $oldAgreement->id }}" selected>
                                        {{ $oldAgreement->agreement_number }} (Korlap:
                                        {{ $oldAgreement->fieldCoordinator->user->name ?? 'N/A' }})
                                    </option>
                                @endif
                            @endif
                        </select>
                    </div>

                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input type="date" name="deposit_date" id="deposit_date" class="form-control"
                                value="{{ old('deposit_date', date('Y-m-d')) }}" required />
                            <label for="deposit_date">Tanggal Setoran</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input type="number" name="amount" id="amount" class="form-control"
                                placeholder="Contoh: 50000" value="{{ old('amount') }}" min="0" required />
                            <label for="amount">Jumlah Setoran (Rp)</label>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-floating form-floating-outline">
                            <textarea name="notes" id="notes" class="form-control" placeholder="Masukkan catatan tambahan jika ada..."
                                style="height: 80px;">{{ old('notes') }}</textarea>
                            <label for="notes">Catatan (Opsional)</label>
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Bukti Transfer (Opsional)</label>
                        <div class="card">
                            <div class="card-body text-center">
                                <img src="{{ asset('assets/img/illustrations/image-light.png') }}" alt="proof-placeholder"
                                    class="d-block rounded-3 mx-auto mb-4" id="proof-preview" style="max-height: 150px;" />
                                <label for="proof-upload" class="btn btn-primary">
                                    <i class="icon-base ri-upload-2-line me-2"></i>Pilih Gambar
                                    <input type="file" id="proof-upload" name="proof_of_transfer"
                                        class="account-file-input" hidden accept="image/png, image/jpeg" />
                                </label>
                                <div id="proof-error" class="mt-2 text-danger text-sm"></div>
                                <p class="text-muted mt-2 mb-0">Hanya JPG/PNG. Akan dikompres di bawah 300KB.</p>
                            </div>
                        </div>
                        @error('proof_of_transfer')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="pt-6 text-end">
                    <a href="{{ route('masterdata.deposit-transactions.index') }}"
                        class="btn btn-outline-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Setoran</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('vendors-js')
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/browser-image-compression@2.0.1/dist/browser-image-compression.js"></script>
@endpush

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // --- Inisialisasi Select2 dengan AJAX ---
            const agreementSelect = $('#agreement_id');
            if (agreementSelect.length) {
                agreementSelect.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Cari atau pilih perjanjian...',
                    dropdownParent: agreementSelect.parent(),
                    allowClear: true,
                    ajax: {
                        url: '{{ route('masterdata.search-active-agreements') }}',
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                term: params.term
                            };
                        },
                        processResults: function(data) {
                            return {
                                results: data.results
                            };
                        },
                        cache: true
                    },
                    minimumInputLength: 2
                });
            }

            // --- Logika Upload & Kompresi Gambar ---
            const fileInput = document.getElementById('proof-upload');
            const imagePreview = document.getElementById('proof-preview');
            const errorDiv = document.getElementById('proof-error');
            const defaultSrc = "{{ asset('assets/img/illustrations/image-light.png') }}";

            if (fileInput) {
                fileInput.addEventListener('change', async (e) => {
                    const imageFile = e.target.files[0];
                    if (!imageFile) {
                        imagePreview.src = defaultSrc;
                        return;
                    }

                    errorDiv.textContent = '';
                    if (!['image/jpeg', 'image/png'].includes(imageFile.type)) {
                        errorDiv.textContent = 'Hanya file JPG atau PNG.';
                        fileInput.value = '';
                        imagePreview.src = defaultSrc;
                        return;
                    }

                    const options = {
                        maxSizeMB: 0.3,
                        maxWidthOrHeight: 1024,
                        useWebWorker: true
                    };
                    try {
                        const compressedFile = await imageCompression(imageFile, options);
                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(new File([compressedFile], imageFile.name, {
                            type: compressedFile.type
                        }));
                        fileInput.files = dataTransfer.files;
                        imagePreview.src = URL.createObjectURL(compressedFile);
                    } catch (error) {
                        errorDiv.textContent = "Gagal memproses gambar.";
                        fileInput.value = '';
                        imagePreview.src = defaultSrc;
                    }
                });
            }
        });
    </script>
@endpush
