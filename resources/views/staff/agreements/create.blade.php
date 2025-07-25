    {{-- (Kode lengkap dari jawaban sebelumnya, sudah mencakup semua fitur) --}}
    @extends('layouts.app')

    @section('title', 'Tambah Perjanjian Baru')

    @push('styles')
        <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />
    @endpush

    @section('content')
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
            <h4 class="fw-bold mb-0">Tambah Perjanjian Kerjasama Baru</h4>
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

        <form action="{{ route('masterdata.agreements.store') }}" method="POST">
            @csrf
            <div class="row g-6">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Detail Perjanjian</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-6">
                                <div class="col-md-12">
                                    <div class="form-floating form-floating-outline"><input type="text"
                                            class="form-control" id="agreement_number" name="agreement_number"
                                            placeholder="Contoh: PKS/2025/001" value="{{ old('agreement_number') }}"
                                            required /><label for="agreement_number">Nomor
                                            Perjanjian</label></div>
                                </div>
                                <div class="col-md-6">
                                    <label for="field_coordinator_id" class="form-label">Koordinator Lapangan</label>
                                    <select class="form-select select2" id="field_coordinator_id"
                                        name="field_coordinator_id" required>
                                        <option value=""></option>
                                        @foreach ($fieldCoordinators as $fc)
                                            <option value="{{ $fc->id }}"
                                                {{ old('field_coordinator_id') == $fc->id ? 'selected' : '' }}>
                                                {{ $fc->user->name ?? 'N/A' }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="leader_id" class="form-label">Pimpinan (Pihak Pertama)</label>
                                    <select class="form-select select2" id="leader_id" name="leader_id" required>
                                        <option value="">Pilih Pimpinan</option>
                                        @foreach ($leaders as $leader)
                                            <option value="{{ $leader->id }}"
                                                {{ old('leader_id') == $leader->id ? 'selected' : '' }}>
                                                {{ $leader->user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" class="form-control" id="start_date" name="start_date"
                                            placeholder="YYYY-MM-DD" value="{{ old('start_date') }}" required />
                                        <label for="start_date">Tanggal Mulai Berlaku</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" class="form-control" id="end_date" name="end_date"
                                            placeholder="YYYY-MM-DD" value="{{ old('end_date') }}" required />
                                        <label for="end_date">Tanggal Selesai Berlaku</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" class="form-control" id="signed_date" name="signed_date"
                                            value="{{ old('signed_date', date('Y-m-d')) }}" required /><label
                                            for="signed_date">Tanggal TTD</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline">
                                        <select name="status" id="status" class="form-select" required>
                                            <option value="active"
                                                {{ old('status', 'active') == 'active' ? 'selected' : '' }}>
                                                Aktif
                                            </option>
                                            <option value="pending_renewal"
                                                {{ old('status') == 'pending_renewal' ? 'selected' : '' }}>
                                                Menunggu Perpanjangan</option>
                                            <option value="expired" {{ old('status') == 'expired' ? 'selected' : '' }}>
                                                Kadaluarsa
                                            </option>
                                            <option value="terminated"
                                                {{ old('status') == 'terminated' ? 'selected' : '' }}>
                                                Diakhiri
                                            </option>
                                        </select>
                                        <label for="status">Status Perjanjian</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating form-floating-outline">
                                        <input type="number" class="form-control" id="daily_deposit_amount"
                                            name="daily_deposit_amount" placeholder="Contoh: 50000"
                                            value="{{ old('daily_deposit_amount') }}" required min="0"
                                            step="1000" />
                                        <label for="daily_deposit_amount">Setoran Harian</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" class="form-control" id="monthly_deposit"
                                            placeholder="Akan terisi otomatis" readonly />
                                        <label for="monthly_deposit">Estimasi Setoran Bulanan</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" class="form-control" id="total_deposit"
                                            placeholder="Akan terisi otomatis" readonly />
                                        <label for="total_deposit">Total Setoran Kontrak</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-4">
                                    <h5 class="mb-0">Lokasi Parkir Terkait</h5>
                                    <hr class="mt-2">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">1. Pilih Zona Pengelolaan</label>
                                    <div class="d-flex pt-2">
                                        <div class="form-check me-4"><input name="zone_filter" class="form-check-input"
                                                type="radio" value="Zona 2" id="zone2" /><label
                                                class="form-check-label" for="zone2"> Zona 2 </label>
                                        </div>
                                        <div class="form-check"><input name="zone_filter" class="form-check-input"
                                                type="radio" value="Zona 3" id="zone3" /><label
                                                class="form-check-label" for="zone3"> Zona
                                                3 </label></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="road_section_id" class="form-label">2. Pilih Ruas Jalan</label>
                                    <select class="form-select select2" id="road_section_id"
                                        name="road_section_id_filter" disabled>
                                        <option value="">Pilih Zona terlebih dahulu</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">3. Pilih Lokasi Parkir (Minimal 1)</label>
                                    <div id="parking-location-container" class="border rounded-3 p-4"
                                        style="min-height: 150px;">
                                        <p class="text-muted text-center" id="parking-location-placeholder">Pilih Ruas
                                            Jalan
                                            terlebih
                                            dahulu.</p>
                                    </div>
                                    @error('parking_location_ids')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 text-end">
                    <a href="{{ route('masterdata.agreements.index') }}" class="btn btn-outline-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Perjanjian</button>
                </div>
            </div>
        </form>
    @endsection

    @push('vendors-js')
        <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
        <script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
        <script src="{{ asset('assets/vendor/libs/moment/moment.js') }}"></script>
    @endpush

    @push('scripts')
        {{-- (Script lengkap dari jawaban sebelumnya) --}}
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                $('.select2').select2();
                const startDatePicker = flatpickr(document.getElementById('start_date'), {
                    dateFormat: 'Y-m-d',
                    onChange: (selectedDates, dateStr) => {
                        endDatePicker.set('minDate', dateStr);
                        calculateTotals();
                    }
                });
                const signedDatePicker = flatpickr(document.getElementById('signed_date'), {
                    dateFormat: 'Y-m-d',
                });
                const endDatePicker = flatpickr(document.getElementById('end_date'), {
                    dateFormat: 'Y-m-d',
                    onChange: () => calculateTotals()
                });
                const dailyDepositInput = document.getElementById('daily_deposit_amount');
                const monthlyDepositInput = document.getElementById('monthly_deposit');
                const totalDepositInput = document.getElementById('total_deposit');

                function calculateTotals() {
                    const dailyAmount = parseFloat(dailyDepositInput.value) || 0;
                    const startDate = startDatePicker.selectedDates[0];
                    const endDate = endDatePicker.selectedDates[0];
                    const formatRupiah = (number) => new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0
                    }).format(number);
                    monthlyDepositInput.value = dailyAmount > 0 ? formatRupiah(dailyAmount * 30) : '';
                    if (dailyAmount > 0 && startDate && endDate && endDate >= startDate) {
                        const durationInDays = moment(endDate).diff(moment(startDate), 'days') + 1;
                        totalDepositInput.value = durationInDays > 0 ? formatRupiah(dailyAmount * durationInDays) : '';
                    } else {
                        totalDepositInput.value = '';
                    }
                }
                dailyDepositInput.addEventListener('input', calculateTotals);
                calculateTotals();

                const roadSectionSelect = $('#road_section_id');
                const parkingContainer = $('#parking-location-container');
                const parkingPlaceholder = $('#parking-location-placeholder');

                $('.select2').each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Pilih salah satu',
                        dropdownParent: $this.parent(),
                        allowClear: true
                    });
                });

                $('input[name="zone_filter"]').on('change', function() {
                    const selectedZone = $(this).val();
                    roadSectionSelect.empty().append('<option value="">Memuat...</option>').prop('disabled',
                        true);
                    parkingContainer.html(parkingPlaceholder.text('Pilih Ruas Jalan terlebih dahulu.'));

                    const url = `{{ route('masterdata.road-sections.getByZone', ':zone') }}`.replace(
                        ':zone', selectedZone);

                    $.ajax({
                        url: url,
                        type: 'GET',
                        success: function(data) {
                            roadSectionSelect.empty().append(
                                '<option value="">Pilih Ruas Jalan</option>').prop('disabled',
                                false);
                            if (data.length > 0) {
                                $.each(data, function(key, value) {
                                    roadSectionSelect.append($('<option></option>').attr(
                                        'value', value.id).text(value.name));
                                });
                            } else {
                                roadSectionSelect.empty().append(
                                    '<option value="">Tidak ada ruas jalan</option>').prop(
                                    'disabled', true);
                            }
                        },
                        error: function() {
                            roadSectionSelect.empty().append(
                                '<option value="">Gagal memuat</option>').prop('disabled', true);
                        }
                    });
                });

                roadSectionSelect.on('change', function() {
                    const selectedRoadSectionId = $(this).val();
                    parkingContainer.html(parkingPlaceholder.text('Memuat...'));

                    if (selectedRoadSectionId) {
                        const url =
                            `{{ route('masterdata.get-parking-locations-by-road-section', ':roadSectionId') }}`
                            .replace(':roadSectionId', selectedRoadSectionId);

                        $.ajax({
                            url: url,
                            type: 'GET',
                            success: function(data) {
                                parkingContainer.empty();
                                if (data.length > 0) {
                                    let html = '<div class="row">';
                                    data.forEach(location => {
                                        html +=
                                            `<div class="col-md-4"><div class="form-check"><input class="form-check-input" type="checkbox" name="parking_location_ids[]" value="${location.id}" id="loc-${location.id}"><label class="form-check-label" for="loc-${location.id}">${location.name}</label></div></div>`;
                                    });
                                    html += '</div>';
                                    parkingContainer.html(html);
                                } else {
                                    parkingContainer.html(parkingPlaceholder.text(
                                        'Tidak ada lokasi parkir tersedia di ruas jalan ini.'
                                    ));
                                }
                            },
                            error: function() {
                                parkingContainer.html(parkingPlaceholder.text(
                                    'Gagal memuat lokasi parkir.'));
                            }
                        });
                    } else {
                        parkingContainer.html(parkingPlaceholder.text('Pilih Ruas Jalan terlebih dahulu.'));
                    }
                });
            });
        </script>
    @endpush
