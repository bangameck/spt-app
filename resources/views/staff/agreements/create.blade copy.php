@extends('layouts.app')

@section('title', 'Tambah Perjanjian Baru')

@section('content')
    <div class="container-fluid">
        <div class="flex justify-between items-center mb-6">
            <h4 class="text-default-900 text-2xl font-bold">Tambah Perjanjian Baru</h4>
            <a href="{{ route('masterdata.agreements.index') }}"
                class="px-6 py-2 rounded-md text-primary-600 border border-primary-600 hover:bg-primary-600 hover:text-white transition-all">
                Kembali ke Daftar Perjanjian
            </a>
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
            <form action="{{ route('masterdata.agreements.store') }}" method="POST">
                @csrf

                <h5 class="text-lg font-semibold text-default-800 mb-4">Detail Perjanjian</h5>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="agreement_number" class="block text-sm font-medium text-default-700 mb-2">Nomor
                            Perjanjian</label>
                        <input type="text" name="agreement_number" id="agreement_number"
                            class="form-input w-full px-4 py-2 border rounded-md text-default-800 focus:ring-primary-500 focus:border-primary-500 @error('agreement_number') border-red-500 @enderror"
                            value="{{ old('agreement_number') }}" placeholder="Contoh: PKS-2025-001" required>
                        @error('agreement_number')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="leader_id" class="block text-sm font-medium text-default-700 mb-2">Pimpinan (PIHAK
                            PERTAMA)</label>
                        <select name="leader_id" id="leader_id"
                            class="form-select w-full px-4 py-2 border rounded-md text-default-800 focus:ring-primary-500 focus:border-primary-500 @error('leader_id') border-red-500 @enderror"
                            required>
                            <option value="">Pilih Pimpinan</option>
                            @foreach ($leaders as $leader)
                                <option value="{{ $leader->id }}" {{ old('leader_id') == $leader->id ? 'selected' : '' }}>
                                    {{ $leader->user->name ?? 'N/A' }} (NIP: {{ $leader->employee_number }})
                                </option>
                            @endforeach
                        </select>
                        @error('leader_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="field_coordinator_id"
                            class="block text-sm font-medium text-default-700 mb-2">Koordinator Lapangan (PIHAK
                            KEDUA)</label>
                        {{-- 2. Tambahkan class 'select2' pada elemen select --}}
                        <select name="field_coordinator_id" id="field_coordinator_id" class="form-select w-full select2"
                            required>
                            <option value="">Pilih Koordinator Lapangan</option>
                            @foreach ($fieldCoordinators as $fc)
                                <option value="{{ $fc->id }}"
                                    {{ old('field_coordinator_id') == $fc->id ? 'selected' : '' }}>
                                    {{ $fc->user->name ?? 'N/A' }} (No. KTP: {{ $fc->id_card_number }})
                                </option>
                            @endforeach
                        </select>
                        @error('field_coordinator_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="daily_deposit_amount" class="block text-sm font-medium text-default-700 mb-2">Jumlah
                            Setoran Harian (Rp)</label>
                        <input type="number" name="daily_deposit_amount" id="daily_deposit_amount"
                            class="form-input w-full px-4 py-2 border rounded-md text-default-800 focus:ring-primary-500 focus:border-primary-500 @error('daily_deposit_amount') border-red-500 @enderror"
                            value="{{ old('daily_deposit_amount') }}" placeholder="Contoh: 30000" min="0" required>
                        @error('daily_deposit_amount')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-default-700 mb-2">Tanggal Mulai
                            Perjanjian</label>
                        <input type="date" name="start_date" id="start_date"
                            class="form-input w-full px-4 py-2 border rounded-md text-default-800 focus:ring-primary-500 focus:border-primary-500 @error('start_date') border-red-500 @enderror"
                            value="{{ old('start_date') }}" required>
                        @error('start_date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-default-700 mb-2">Tanggal Akhir
                            Perjanjian</label>
                        <input type="date" name="end_date" id="end_date"
                            class="form-input w-full px-4 py-2 border rounded-md text-default-800 focus:ring-primary-500 focus:border-primary-500 @error('end_date') border-red-500 @enderror"
                            value="{{ old('end_date') }}" required>
                        @error('end_date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="signed_date" class="block text-sm font-medium text-default-700 mb-2">Tanggal
                            Ditandatangani</label>
                        <input type="date" name="signed_date" id="signed_date"
                            class="form-input w-full px-4 py-2 border rounded-md text-default-800 focus:ring-primary-500 focus:border-primary-500 @error('signed_date') border-red-500 @enderror"
                            value="{{ old('signed_date') }}" required>
                        @error('signed_date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-medium text-default-700 mb-2">Status
                            Perjanjian</label>
                        <select name="status" id="status"
                            class="form-select w-full px-4 py-2 border rounded-md text-default-800 focus:ring-primary-500 focus:border-primary-500 @error('status') border-red-500 @enderror"
                            required>
                            <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Aktif
                            </option>
                            <option value="expired" {{ old('status') == 'expired' ? 'selected' : '' }}>Kadaluarsa</option>
                            <option value="terminated" {{ old('status') == 'terminated' ? 'selected' : '' }}>Diakhiri
                            </option>
                            <option value="pending_renewal" {{ old('status') == 'pending_renewal' ? 'selected' : '' }}>
                                Menunggu Perpanjangan</option>
                        </select>
                        @error('status')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <hr class="my-6 border-default-200">

                <h5 class="text-lg font-semibold text-default-800 mb-4">Lokasi Parkir Terkait (Pilih Minimal 1)</h5>
                <div class="mb-6">
                    <div>
                        <label for="road_section_filter" class="block text-sm font-medium text-default-700 mb-2">Filter
                            Berdasarkan Ruas Jalan</label>
                        <select id="road_section_filter"
                            class="form-select w-full px-4 py-2 border rounded-md text-default-800 focus:ring-primary-500 focus:border-primary-500">
                            <option value="">Tampilkan Semua Ruas Jalan</option>
                            @foreach ($roadSections as $rs)
                                <option value="{{ $rs->id }}"
                                    {{ old('road_section_filter') == $rs->id ? 'selected' : '' }}>{{ $rs->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-default-700 mb-2">Pilih Lokasi Parkir yang
                            Tersedia</label>
                        <div id="parking_location_checkboxes"
                            class="border border-default-200 rounded-md p-4 max-h-60 overflow-y-auto">
                            {{-- Checkboxes will be rendered here by JavaScript --}}
                            @forelse ($availableParkingLocations as $location)
                                <div class="flex items-center mb-2">
                                    <input type="checkbox" name="parking_location_ids[]"
                                        id="parking_location_{{ $location->id }}" value="{{ $location->id }}"
                                        data-road-section-id="{{ $location->road_section_id }}"
                                        class="form-checkbox h-4 w-4 text-primary-600 rounded border-default-300 focus:ring-primary-500"
                                        {{ in_array($location->id, old('parking_location_ids', [])) ? 'checked' : '' }}>
                                    {{-- old() saja karena ini create --}}
                                    <label for="parking_location_{{ $location->id }}"
                                        class="ml-2 text-sm text-default-800">
                                        {{ $location->name }} ({{ $location->roadSection->name ?? 'N/A' }}) - Status:
                                        {{ ucfirst(str_replace('_', ' ', $location->status)) }}
                                    </label>
                                </div>
                            @empty
                                <p class="text-default-500">Tidak ada lokasi parkir tersedia saat ini.</p>
                            @endforelse
                        </div>
                        @error('parking_location_ids')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <button type="submit"
                        class="px-6 py-2 rounded-md text-white bg-primary-600 hover:bg-primary-700 transition-all">
                        Simpan Perjanjian
                    </button>
                    <a href="{{ route('masterdata.agreements.index') }}"
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
            // --- Client-side validation for daily_deposit_amount to only allow numbers ---
            const dailyDepositAmountInput = document.getElementById('daily_deposit_amount');
            if (dailyDepositAmountInput) {
                dailyDepositAmountInput.addEventListener('input', function(e) {
                    let value = e.target.value;
                    value = value.replace(/[^0-9.]/g, '');
                    const parts = value.split('.');
                    if (parts.length > 2) {
                        value = parts[0] + '.' + parts.slice(1).join('');
                    }
                    e.target.value = value;
                });
            }

            // --- Dynamic Parking Location Checkboxes ---
            const roadSectionFilter = document.getElementById('road_section_filter');
            const parkingLocationCheckboxesContainer = document.getElementById('parking_location_checkboxes');
            // Get old selected IDs (from validation failure)
            const oldParkingLocationIds = @json(old('parking_location_ids', []));
            const oldRoadSectionFilter = @json(old('road_section_filter')); // Get old selected road section filter

            // Store all checkbox elements initially
            const allParkingLocationCheckboxes = Array.from(parkingLocationCheckboxesContainer.querySelectorAll(
                'input[type="checkbox"]'));

            // Function to filter and display parking locations checkboxes
            function filterParkingLocationsCheckboxes() {
                const selectedRoadSectionId = roadSectionFilter.value;
                console.log('Filtering checkboxes for road section:', selectedRoadSectionId); // Debugging

                allParkingLocationCheckboxes.forEach(checkbox => {
                    const optionRoadSectionId = checkbox.dataset.roadSectionId;
                    const isChecked = checkbox.checked; // Check if checkbox is currently checked

                    // Show checkbox if:
                    // 1. No road section filter is applied (show all)
                    // 2. Checkbox belongs to the selected road section
                    // 3. Checkbox is currently checked (to persist selections)
                    if (!selectedRoadSectionId || optionRoadSectionId === selectedRoadSectionId ||
                        isChecked) {
                        checkbox.closest('.flex.items-center.mb-2').style.display =
                            ''; // Show the div containing checkbox and label
                        // console.log(`Showing checkbox ${checkbox.value}: ${checkbox.nextElementSibling.textContent}`); // Debugging
                    } else {
                        checkbox.closest('.flex.items-center.mb-2').style.display = 'none'; // Hide the div
                        // console.log(`Hiding checkbox ${checkbox.value}: ${checkbox.nextElementSibling.textContent}`); // Debugging
                    }
                });
                console.log('Checkbox filter applied.'); // Debugging
            }

            if (roadSectionFilter && parkingLocationCheckboxesContainer) {
                console.log(
                    'Road section filter and parking location checkboxes container elements found.'); // Debugging
                // Event listener for road section filter change
                roadSectionFilter.addEventListener('change', filterParkingLocationsCheckboxes);

                // Initial filtering based on old input or default
                if (oldRoadSectionFilter) {
                    roadSectionFilter.value = oldRoadSectionFilter;
                    console.log('Initial filter set from old input:', oldRoadSectionFilter); // Debugging
                }
                filterParkingLocationsCheckboxes(); // Apply filter on page load
            } else {
                console.warn(
                    'Road section filter or parking location checkboxes container elements not found.'
                ); // Debugging
            }
        });
        $(document).ready(function() {
            // Inisialisasi Select2 pada dropdown dengan class 'select2'
            $('.select2').select2({
                placeholder: "Pilih atau cari...",
                allowClear: true
            });
        });
    </script>
@endpush
