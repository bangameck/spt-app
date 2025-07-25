@extends('layouts.app')

@section('title', 'Edit Perjanjian: ' . $agreement->agreement_number)

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
@endpush

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Edit Perjanjian</h4>
        <div class="d-flex align-items-center">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-style1 mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Master Data</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('masterdata.agreements.index') }}">PKS</a></li>
                    <li class="breadcrumb-item active">Edit</li>
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
            <form action="{{ route('masterdata.agreements.update', $agreement->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="row g-6">
                    {{-- Detail Perjanjian --}}
                    <div class="col-12">
                        <h5 class="mb-0">Informasi Perjanjian</h5>
                        <hr class="mt-2">
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline"><input type="text" class="form-control"
                                id="agreement_number" name="agreement_number"
                                value="{{ old('agreement_number', $agreement->agreement_number) }}" readonly /><label
                                for="agreement_number">Nomor Perjanjian</label></div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline"><select class="form-select select2" id="leader_id"
                                name="leader_id" required>
                                <option value=""></option>
                                @foreach ($leaders as $leader)
                                    <option value="{{ $leader->id }}"
                                        {{ old('leader_id', $agreement->leader_id) == $leader->id ? 'selected' : '' }}>
                                        {{ $leader->user->name ?? 'N/A' }}</option>
                                @endforeach
                            </select><label for="leader_id">Pimpinan (Pihak Pertama)</label></div>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Koordinator Lapangan (Pihak Kedua)</label>
                        <input type="text" class="form-control"
                            value="{{ $agreement->fieldCoordinator->user->name ?? 'N/A' }}" disabled />
                        <input type="hidden" name="field_coordinator_id" value="{{ $agreement->field_coordinator_id }}">
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline"><input type="date" class="form-control"
                                id="start_date" name="start_date"
                                value="{{ old('start_date', $agreement->start_date->format('Y-m-d')) }}" required /><label
                                for="start_date">Tanggal Mulai</label></div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline"><input type="date" class="form-control"
                                id="end_date" name="end_date"
                                value="{{ old('end_date', $agreement->end_date->format('Y-m-d')) }}" required /><label
                                for="end_date">Tanggal Akhir</label></div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline"><input type="date" class="form-control"
                                id="signed_date" name="signed_date"
                                value="{{ old('signed_date', $agreement->signed_date->format('Y-m-d')) }}"
                                required /><label for="signed_date">Tanggal TTD</label></div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline"><input type="number" class="form-control"
                                id="daily_deposit_amount" name="daily_deposit_amount"
                                value="{{ old('daily_deposit_amount', $agreement->daily_deposit_amount) }}" min="0"
                                required /><label for="daily_deposit_amount">Jumlah Setoran Harian (Rp)</label></div>
                    </div>
                    @php
                        $endDate = \Carbon\Carbon::parse($agreement->end_date);
                        $canChangeStatus = $endDate->isPast() || now()->diffInDays($endDate, false) <= 10;
                    @endphp
                    <div class="col-md-12">
                        <div class="form-floating form-floating-outline">
                            <select name="status" id="status" class="form-select" required
                                {{ !$canChangeStatus ? 'disabled' : '' }}>
                                <option value="active"
                                    {{ old('status', $agreement->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="pending_renewal"
                                    {{ old('status', $agreement->status) == 'pending_renewal' ? 'selected' : '' }}>Menunggu
                                    Perpanjangan</option>
                                <option value="expired"
                                    {{ old('status', $agreement->status) == 'expired' ? 'selected' : '' }}>Kadaluarsa
                                </option>
                                <option value="terminated"
                                    {{ old('status', $agreement->status) == 'terminated' ? 'selected' : '' }}>Diakhiri
                                </option>
                            </select><label for="status">Status Perjanjian</label>
                        </div>
                        @if (!$canChangeStatus)
                            <input type="hidden" name="status" value="{{ $agreement->status }}">
                            <small class="text-danger small">Status hanya bisa diubah jika PKS akan berakhir dalam 10 hari
                                atau sudah kadaluarsa.</small>
                        @endif
                    </div>

                    {{-- Pilihan Zona dan Lokasi Parkir --}}
                    <div class="col-12 mt-4">
                        <h5 class="mb-0">Lokasi Parkir Terkait</h5>
                        <hr class="mt-2">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Zona Pengelolaan</label>
                        <div class="d-flex pt-2">
                            <div class="form-check me-4">
                                <input name="zone_filter" class="form-check-input" type="radio" value="Zona 2"
                                    id="zone2" {{ $initialZone == 'Zona 2' ? 'checked' : '' }} disabled />
                                <label class="form-check-label" for="zone2"> Zona 2 </label>
                            </div>
                            <div class="form-check">
                                <input name="zone_filter" class="form-check-input" type="radio" value="Zona 3"
                                    id="zone3" {{ $initialZone == 'Zona 3' ? 'checked' : '' }} disabled />
                                <label class="form-check-label" for="zone3"> Zona 3 </label>
                            </div>
                        </div>
                        <small class="text-muted">Zona tidak dapat diubah saat mengedit perjanjian.</small>
                    </div>
                    <div class="col-md-6">
                        <label for="road_section_filter" class="form-label">Filter Ruas Jalan</label>
                        <select class="form-select select2" id="road_section_filter" name="road_section_id_filter">
                            <option value="">Tampilkan Semua Lokasi</option>
                            {{-- Filter ini akan di-handle oleh JavaScript --}}
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Pilih Lokasi Parkir (Minimal 1)</label>
                        <div id="parking-location-container" class="border rounded-3 p-4"
                            style="min-height: 150px; max-height: 400px; overflow-y: auto;">
                            {{-- ✅ Logika baru untuk menampilkan checkbox --}}
                            @if ($parkingLocationsForCheckboxes->isEmpty())
                                <p class="text-muted text-center">Tidak ada lokasi tersedia atau terikat pada zona ini.</p>
                            @else
                                @foreach ($parkingLocationsForCheckboxes->groupBy('road_section_id') as $roadSectionId => $locations)
                                    <div class="mb-3 location-group" data-section-id="{{ $roadSectionId }}">
                                        <p class="fw-medium mb-2">
                                            {{ $locations->first()->roadSection->name ?? 'Lainnya' }}</p>
                                        <div class="row">
                                            @foreach ($locations as $location)
                                                <div class="col-md-4">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="parking_location_ids[]" value="{{ $location->id }}"
                                                            id="loc-{{ $location->id }}"
                                                            {{ in_array($location->id, old('parking_location_ids', $currentParkingLocationIds)) ? 'checked' : '' }}>
                                                        <label class="form-check-label"
                                                            for="loc-{{ $location->id }}">{{ $location->name }}</label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        @error('parking_location_ids')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="pt-6 text-end">
                    <a href="{{ route('masterdata.agreements.index') }}" class="btn btn-outline-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
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
            // Inisialisasi Select2
            $('.select2').each(function() {
                $(this).wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih salah satu',
                    dropdownParent: $(this).parent(),
                    allowClear: true
                });
            });

            const roadSectionFilter = $('#road_section_filter');
            const parkingContainer = $('#parking-location-container');

            // ✅ Mengisi dropdown filter ruas jalan dari data yang sudah ada
            const roadSectionsInZone = @json($parkingLocationsForCheckboxes->pluck('roadSection')->unique('id')->sortBy('name'));

            roadSectionFilter.empty().append('<option value="">Tampilkan Semua Lokasi</option>');
            roadSectionsInZone.forEach(section => {
                if (section) { // Cek jika section tidak null
                    roadSectionFilter.append($('<option></option>').attr('value', section.id).text(section
                        .name));
                }
            });

            // Event listener untuk filter Ruas Jalan
            roadSectionFilter.on('change', function() {
                const selectedSectionId = $(this).val();
                if (selectedSectionId) {
                    // Sembunyikan semua grup, lalu tampilkan yang cocok
                    parkingContainer.find('.location-group').hide();
                    parkingContainer.find(`.location-group[data-section-id="${selectedSectionId}"]`).show();
                } else {
                    // Jika filter dikosongkan, tampilkan semua grup
                    parkingContainer.find('.location-group').show();
                }
            });
        });
    </script>
@endpush
