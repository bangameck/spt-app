@extends('layouts.app')

@section('title', 'Tambah Perjanjian Baru')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
@endpush

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Tambah Perjanjian Baru</h4>
        <div class="d-flex align-items-center">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-style1 mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Master Data</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('masterdata.agreements.index') }}">PKS</a></li>
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
        <div class="card-body">
            <form action="{{ route('masterdata.agreements.store') }}" method="POST">
                @csrf
                <div class="row g-6">
                    {{-- Detail Perjanjian --}}
                    <div class="col-12">
                        <h5 class="mb-0">Informasi Perjanjian</h5>
                        <hr class="mt-2">
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline"><input type="text" class="form-control"
                                id="agreement_number" name="agreement_number" placeholder="Contoh: PKS/2025/001"
                                value="{{ old('agreement_number') }}" required /><label for="agreement_number">Nomor
                                Perjanjian</label></div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline"><select class="form-select select2" id="leader_id"
                                name="leader_id" required>
                                <option value=""></option>
                                @foreach ($leaders as $leader)
                                    <option value="{{ $leader->id }}"
                                        {{ old('leader_id') == $leader->id ? 'selected' : '' }}>
                                        {{ $leader->user->name ?? 'N/A' }}</option>
                                @endforeach
                            </select>
                            <label for="leader_id">Pimpinan (Pihak Pertama)</label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-floating form-floating-outline"><select class="form-select select2"
                                id="field_coordinator_id" name="field_coordinator_id" required>
                                <option value=""></option>
                                @foreach ($fieldCoordinators as $fc)
                                    <option value="{{ $fc->id }}"
                                        {{ old('field_coordinator_id') == $fc->id ? 'selected' : '' }}>
                                        {{ $fc->user->name ?? 'N/A' }}</option>
                                @endforeach
                            </select><label for="field_coordinator_id">Koordinator Lapangan (Pihak Kedua)</label></div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline"><input type="date" class="form-control"
                                id="start_date" name="start_date" value="{{ old('start_date') }}" required /><label
                                for="start_date">Tanggal Mulai</label></div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline"><input type="date" class="form-control"
                                id="end_date" name="end_date" value="{{ old('end_date') }}" required /><label
                                for="end_date">Tanggal Akhir</label></div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline"><input type="date" class="form-control"
                                id="signed_date" name="signed_date" value="{{ old('signed_date', date('Y-m-d')) }}"
                                required /><label for="signed_date">Tanggal TTD</label></div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline"><input type="number" class="form-control"
                                id="daily_deposit_amount" name="daily_deposit_amount" placeholder="Masukkan jumlah setoran"
                                value="{{ old('daily_deposit_amount') }}" min="0" required /><label
                                for="daily_deposit_amount">Jumlah Setoran Harian (Rp)</label></div>
                    </div>

                    {{-- ✅ INPUT STATUS DITAMBAHKAN DI SINI --}}
                    <div class="col-md-12">
                        <div class="form-floating form-floating-outline">
                            <select name="status" id="status" class="form-select" required>
                                <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Aktif
                                </option>
                                <option value="pending_renewal" {{ old('status') == 'pending_renewal' ? 'selected' : '' }}>
                                    Menunggu Perpanjangan</option>
                                <option value="expired" {{ old('status') == 'expired' ? 'selected' : '' }}>Kadaluarsa
                                </option>
                                <option value="terminated" {{ old('status') == 'terminated' ? 'selected' : '' }}>Diakhiri
                                </option>
                            </select>
                            <label for="status">Status Perjanjian</label>
                        </div>
                    </div>

                    {{-- Pilihan Zona dan Lokasi Parkir --}}
                    <div class="col-12 mt-4">
                        <h5 class="mb-0">Lokasi Parkir Terkait</h5>
                        <hr class="mt-2">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">1. Pilih Zona Pengelolaan</label>
                        <div class="d-flex pt-2">
                            <div class="form-check me-4"><input name="zone_filter" class="form-check-input"
                                    type="radio" value="Zona 2" id="zone2" /><label class="form-check-label"
                                    for="zone2"> Zona 2 </label></div>
                            <div class="form-check"><input name="zone_filter" class="form-check-input" type="radio"
                                    value="Zona 3" id="zone3" /><label class="form-check-label" for="zone3"> Zona
                                    3 </label></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="road_section_id" class="form-label">2. Pilih Ruas Jalan</label>
                        <select class="form-select select2" id="road_section_id" name="road_section_id_filter" disabled>
                            <option value="">Pilih Zona terlebih dahulu</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">3. Pilih Lokasi Parkir (Minimal 1)</label>
                        <div id="parking-location-container" class="border rounded-3 p-4" style="min-height: 150px;">
                            <p class="text-muted text-center" id="parking-location-placeholder">Pilih Ruas Jalan terlebih
                                dahulu.</p>
                        </div>
                        @error('parking_location_ids')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div class="pt-6 text-end">
                    <a href="{{ route('masterdata.agreements.index') }}" class="btn btn-outline-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Perjanjian</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('vendors-js')
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
@endpush

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
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
