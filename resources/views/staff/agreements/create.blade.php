@extends('layouts.app')

@section('title', 'Tambah Perjanjian Baru')

@push('styles')
    {{-- Tambahkan CSS untuk Select2 --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container .select2-selection--single {
            height: 42px;
            border: 1px solid #e5e7eb;
            border-radius: 0.375rem;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 42px;
            padding-left: 1rem;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 40px;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="flex justify-between items-center mb-6">
            <h4 class="text-default-900 text-2xl font-bold">Tambah Perjanjian Baru</h4>
            <a href="{{ route('masterdata.agreements.index') }}"
                class="px-6 py-2 rounded-md text-primary-600 border border-primary-600 hover:bg-primary-600 hover:text-white transition-all">
                Kembali ke Daftar
            </a>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Oops!</strong>
                <ul class="mt-2 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card bg-white shadow rounded-lg p-6">
            <form action="{{ route('masterdata.agreements.store') }}" method="POST">
                @csrf
                {{-- Bagian Detail Perjanjian (tidak ada perubahan signifikan) --}}
                <h5 class="text-lg font-semibold text-default-800 mb-4">Detail Perjanjian</h5>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="agreement_number" class="block text-sm font-medium text-default-700 mb-2">Nomor
                            Perjanjian</label>
                        <input type="text" name="agreement_number" id="agreement_number" class="form-input w-full"
                            value="{{ old('agreement_number') }}" required>
                    </div>
                    <div>
                        <label for="leader_id" class="block text-sm font-medium text-default-700 mb-2">Pimpinan (PIHAK
                            PERTAMA)</label>
                        <select name="leader_id" id="leader_id" class="form-select w-full select2" required>
                            <option></option>
                            @foreach ($leaders as $leader)
                                <option value="{{ $leader->id }}" {{ old('leader_id') == $leader->id ? 'selected' : '' }}>
                                    {{ $leader->user->name ?? 'N/A' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="field_coordinator_id"
                            class="block text-sm font-medium text-default-700 mb-2">Koordinator Lapangan (PIHAK
                            KEDUA)</label>
                        <select name="field_coordinator_id" id="field_coordinator_id" class="form-select w-full select2"
                            required>
                            <option></option>
                            @foreach ($fieldCoordinators as $fc)
                                <option value="{{ $fc->id }}"
                                    {{ old('field_coordinator_id') == $fc->id ? 'selected' : '' }}>
                                    {{ $fc->user->name ?? 'N/A' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="daily_deposit_amount" class="block text-sm font-medium text-default-700 mb-2">Jumlah
                            Setoran Harian (Rp)</label>
                        <input type="number" name="daily_deposit_amount" id="daily_deposit_amount"
                            class="form-input w-full" value="{{ old('daily_deposit_amount') }}" min="0" required>
                    </div>
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-default-700 mb-2">Tanggal
                            Mulai</label>
                        <input type="date" name="start_date" id="start_date" class="form-input w-full"
                            value="{{ old('start_date') }}" required>
                    </div>
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-default-700 mb-2">Tanggal Akhir</label>
                        <input type="date" name="end_date" id="end_date" class="form-input w-full"
                            value="{{ old('end_date') }}" required>
                    </div>
                    <div>
                        <label for="signed_date" class="block text-sm font-medium text-default-700 mb-2">Tanggal TTD</label>
                        <input type="date" name="signed_date" id="signed_date" class="form-input w-full"
                            value="{{ old('signed_date') }}" required>
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-medium text-default-700 mb-2">Status
                            Perjanjian</label>
                        <select name="status" id="status" class="form-select w-full" required>
                            <option value="active" selected>Aktif</option>
                            <option value="expired">Kadaluarsa</option>
                            <option value="terminated">Diakhiri</option>
                            <option value="pending_renewal">Menunggu Perpanjangan</option>
                        </select>
                    </div>
                </div>

                <hr class="my-6 border-default-200">

                <h5 class="text-lg font-semibold text-default-800 mb-4">Lokasi Parkir Terkait</h5>
                <div class="mb-6">
                    <div>
                        <label for="road_section_filter" class="block text-sm font-medium text-default-700 mb-2">Filter
                            Berdasarkan Ruas Jalan</label>
                        {{-- ✅ PERUBAHAN 1: Tambahkan class 'select2' --}}
                        <select id="road_section_filter" class="form-select w-full select2">
                            <option></option>
                            @foreach ($roadSections as $rs)
                                <option value="{{ $rs->id }}">{{ $rs->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-default-700 mb-2">Pilih Lokasi Parkir</label>
                        <div id="parking_location_checkboxes" class="border p-4 max-h-60 overflow-y-auto">
                            @forelse ($availableParkingLocations as $location)
                                <div class="flex items-center mb-2 checkbox-item">
                                    <input type="checkbox" name="parking_location_ids[]"
                                        id="parking_location_{{ $location->id }}" value="{{ $location->id }}"
                                        data-road-section-id="{{ $location->road_section_id }}" class="form-checkbox"
                                        {{ in_array($location->id, old('parking_location_ids', [])) ? 'checked' : '' }}>
                                    <label for="parking_location_{{ $location->id }}" class="ml-2">{{ $location->name }}
                                        ({{ $location->roadSection->name ?? 'N/A' }})
                                    </label>
                                </div>
                            @empty
                                <p class="text-default-500">Tidak ada lokasi parkir tersedia.</p>
                            @endforelse
                        </div>
                        @error('parking_location_ids')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end gap-3">
                    <button type="submit" class="px-6 py-2 rounded-md text-white bg-primary-600">Simpan Perjanjian</button>
                    <a href="{{ route('masterdata.agreements.index') }}" class="px-6 py-2 rounded-md border">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- ✅ PERUBAHAN 2: Tambahkan script untuk jQuery dan Select2 --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            // Inisialisasi Select2 pada semua dropdown dengan class 'select2'
            $('.select2').select2({
                placeholder: "Pilih atau cari...",
                allowClear: true
            });

            // Logika untuk filter checkbox yang sudah ada
            const roadSectionFilter = document.getElementById('road_section_filter');
            const checkboxesContainer = document.getElementById('parking_location_checkboxes');

            function filterParkingLocations() {
                const selectedRoadSectionId = roadSectionFilter.value;
                $(checkboxesContainer).find('.checkbox-item').each(function() {
                    const checkbox = $(this).find('input[type="checkbox"]');
                    const itemRoadSectionId = checkbox.data('road-section-id').toString();
                    if (!selectedRoadSectionId || itemRoadSectionId === selectedRoadSectionId) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            }
            $(roadSectionFilter).on('change', filterParkingLocations);
        });
    </script>
@endpush
