@extends('layouts.app')

@section('title', 'Catat Setoran Baru')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <style>
        .select2-container .select2-selection--single {
            height: 58px !important;
            padding: 0.5rem 0.75rem;
            display: flex;
            align-items: center;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 40px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 56px !important;
        }

        #payment-exists-modal {
            visibility: hidden;
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(255, 255, 255, 0.98);
            z-index: 1050;
            border-radius: 0.5rem;
            backdrop-filter: blur(2px);
        }
    </style>
@endpush

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Catat Setoran Baru</h4>
        <div class="d-flex align-items-center">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-style1 mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Master Data</a></li>
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

    <div class="card position-relative">
        <!-- Modal Informasi -->
        <div id="payment-exists-modal" class="d-flex justify-content-center align-items-center text-center p-4">
            <div>
                <i class="ri-error-warning-line ri-5x text-warning mb-4"></i>
                <h4 class="mb-2">Pembayaran Bulan Ini Sudah Dicatat</h4>
                <div class="mb-4" id="modal-message"></div>
                <button type="button" id="change-agreement-btn" class="btn btn-outline-secondary">Pilih Perjanjian
                    Lain</button>
            </div>
        </div>

        <div class="card-header">
            <h5 class="card-title mb-0">Formulir Setoran Baru</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('masterdata.deposit-transactions.store') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="row g-6">
                    <div class="col-12">
                        <label for="agreement_id" class="form-label">1. Pilih Perjanjian Kerjasama</label>
                        <select name="agreement_id" id="agreement_id" class="form-select select2-agreements"
                            required></select>
                    </div>

                    <fieldset id="deposit-form-fields" disabled>
                        <div class="row g-6 mt-1">
                            <div class="col-12">
                                <hr class="mt-0 mb-4">
                            </div>
                            <div class="col-12">
                                <p class="form-label mb-0">2. Detail Setoran</p>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" id="referral_code" class="form-control" readonly />
                                    <label for="referral_code">Kode Referensi (Otomatis)</label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating form-floating-outline">
                                    <input type="date" name="deposit_date" id="deposit_date" class="form-control"
                                        required />
                                    <label for="deposit_date">Tanggal Setoran</label>
                                </div>
                            </div>

                            {{-- ✅ PERUBAHAN UTAMA DI SINI --}}
                            <div class="col-md-12">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" name="amount_display" id="amount_display" class="form-control"
                                        readonly />
                                    <input type="hidden" name="amount" id="amount" />
                                    <label for="amount_display">Jumlah Setoran (Otomatis)</label>
                                    {{-- Teks info akan diisi oleh JavaScript --}}
                                    <div id="amount-calculation-info" class="alert alert-warning d-none mt-2 p-2"
                                        role="alert" style="font-size: 0.85rem;"></div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-floating form-floating-outline">
                                    <textarea name="notes" id="notes" class="form-control" placeholder="Catatan tambahan (opsional)"
                                        style="height: 80px;"></textarea><label for="notes">Catatan</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Bukti Transfer (Opsional)</label>
                                <div class="card">
                                    <div class="card-body text-center">
                                        <img src="{{ asset('assets/img/illustrations/image-light.png') }}"
                                            alt="proof-placeholder" class="d-block rounded-3 mx-auto mb-4"
                                            id="proof-preview" style="max-height: 150px;" />
                                        <label for="proof-upload" class="btn btn-primary"><i
                                                class="icon-base ri-upload-2-line me-2"></i>Pilih Gambar<input
                                                type="file" id="proof-upload" name="proof_of_transfer"
                                                class="account-file-input" hidden accept="image/png, image/jpeg" /></label>
                                        <div id="proof-error" class="mt-2 text-danger text-sm"></div>
                                        <p class="text-muted mt-2 mb-0">Hanya JPG/PNG. Akan dikompres di bawah 300KB.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="pt-6 text-end">
                            <a href="{{ route('masterdata.deposit-transactions.index') }}"
                                class="btn btn-outline-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary">Simpan Setoran</button>
                        </div>
                    </fieldset>
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
        $(document).ready(function() {
            const agreementSelect = $('#agreement_id');
            const formFields = $('#deposit-form-fields');
            const modal = $('#payment-exists-modal');
            const amountCalculationInfo = $('#amount-calculation-info');

            // --- Inisialisasi Select2 ---
            agreementSelect.select2({
                placeholder: 'Ketik No. PKS atau Nama Korlap untuk mencari...',
                allowClear: true,
                ajax: {
                    url: '{{ route('masterdata.search-active-agreements') }}',
                    dataType: 'json',
                    delay: 250,
                    processResults: function(data) {
                        return {
                            results: data.results
                        };
                    }
                }
            });

            function resetAndDisableForm() {
                formFields.prop('disabled', true);
                formFields.find('input, textarea').val('');
                $('#referral_code_display').val('Pilih perjanjian untuk generate kode');
                $('#proof-preview').attr('src', "{{ asset('assets/img/illustrations/image-light.png') }}");
                $('#proof-upload').val('');
                amountCalculationInfo.addClass('d-none').html('');
                modal.css({
                    'visibility': 'hidden',
                    'opacity': '0'
                });
            }

            agreementSelect.on('select2:select', function(e) {
                const data = e.params.data;
                if (!data.id) return;

                $.ajax({
                    url: `/masterdata/check-transaction/${data.id}`,
                    type: 'GET',
                    success: function(response) {
                        if (response.exists) {
                            const message =
                                `Perjanjian <strong>${response.transaction.agreement_number}</strong> sudah melakukan pembayaran Setoran dengan kode transaksi/kode referensi <a href="${response.transaction.show_url}" target="_blank" class="fw-bold">${response.transaction.referral_code}</a>.`;
                            $('#modal-message').html(message);
                            modal.css({
                                'visibility': 'visible',
                                'opacity': '1'
                            });
                        } else {
                            formFields.prop('disabled', false);

                            // --- Logika Kalkulasi Baru ---
                            const dailyAmount = parseFloat(data.daily_deposit_amount) || 0;

                            const now = new Date(); // Tanggal saat ini (Juli 2025)
                            const nextMonthDate = new Date(now.getFullYear(), now.getMonth() +
                                1, 1); // Tanggal 1 bulan berikutnya (Agustus 2025)

                            // Dapatkan jumlah hari di bulan berikutnya
                            const daysInNextMonth = new Date(nextMonthDate.getFullYear(),
                                nextMonthDate.getMonth() + 1, 0).getDate();

                            // Dapatkan nama bulan & tahun berikutnya dalam format Indonesia
                            const nextMonthName = nextMonthDate.toLocaleString('id-ID', {
                                month: 'long'
                            });
                            const nextMonthYear = nextMonthDate.getFullYear();

                            const totalAmount = dailyAmount * daysInNextMonth;

                            // Fungsi format ke Rupiah
                            const formatRupiah = (number) => new Intl.NumberFormat('id-ID', {
                                style: 'currency',
                                currency: 'IDR',
                                minimumFractionDigits: 0
                            }).format(number);

                            // Buat teks info
                            const infoText =
                                `<i class="ri-information-line me-1"></i> Jumlah diatas berdasarkan perhitungan setoran harian <strong>${formatRupiah(dailyAmount)}</strong> &times; <strong>${daysInNextMonth} hari</strong> pada bulan <strong>${nextMonthName} ${nextMonthYear}</strong>.`;

                            // Tampilkan info dan isi field
                            amountCalculationInfo.html(infoText).removeClass('d-none');
                            $('#amount').val(totalAmount.toFixed(0));
                            $('#amount_display').val(formatRupiah(totalAmount));

                            // Generate kode referensi untuk tampilan
                            const dateCode =
                                `${now.getFullYear()}${(now.getMonth() + 1).toString().padStart(2, '0')}${now.getDate().toString().padStart(2, '0')}`;
                            const randomCode = (Math.random().toString(36) +
                                '00000000000000000').slice(2, 8).toUpperCase();
                            $('#referral_code').val(`TRXPRK-${dateCode}-${randomCode}`);

                            // Atur tanggal minimal ke hari ini dan value ke tanggal 1 bulan depan
                            const today = new Date().toISOString().split('T')[0];
                            $('#deposit_date').attr('min', today).val(today);
                        }
                    }
                });
            });

            agreementSelect.on('select2:unselect', resetAndDisableForm);
            $('#change-agreement-btn').on('click', function() {
                agreementSelect.val(null).trigger('change');
                resetAndDisableForm();
            });

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
