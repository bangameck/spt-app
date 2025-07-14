@extends('layouts.app')

@section('title', 'Laporan Transaksi Setoran')

@section('content')
    <div class="container-fluid">
        <div class="flex justify-between items-center mb-6">
            <h4 class="text-default-900 text-2xl font-bold">Laporan Transaksi Setoran</h4>
            {{-- Tombol untuk kembali atau aksi lain jika diperlukan --}}
            <a href="{{ route('masterdata.deposit-transactions.index') }}"
                class="px-6 py-2 rounded-md text-primary-600 border border-primary-600 hover:bg-primary-600 hover:text-white transition-all">
                Kembali ke Transaksi
            </a>
        </div>

        {{-- SweetAlert2 Success Message --}}
        @if (session('success'))
            <div id="success-alert" data-message="{{ session('success') }}" style="display: none;"></div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline">{{ session('error') }}</span>
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
            {{-- Filter Form --}}
            <form action="{{ route('masterdata.deposit-reports.index') }}" method="GET" class="mb-4">
                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    <div>
                        <label for="report_type" class="block text-sm font-medium text-default-700 mb-2">Tipe
                            Laporan</label>
                        <select name="report_type" id="report_type"
                            class="form-select w-full px-4 py-2 border rounded-md text-default-800 focus:ring-primary-500 focus:border-primary-500">
                            <option value="daily" {{ ($reportType ?? 'daily') == 'daily' ? 'selected' : '' }}>Harian
                            </option>
                            <option value="monthly" {{ ($reportType ?? 'daily') == 'monthly' ? 'selected' : '' }}>Bulanan
                            </option>
                            <option value="yearly" {{ ($reportType ?? 'daily') == 'yearly' ? 'selected' : '' }}>Tahunan
                            </option>
                            <option value="custom_range" {{ ($reportType ?? 'daily') == 'custom_range' ? 'selected' : '' }}>
                                Rentang Waktu Kustom</option>
                        </select>
                    </div>

                    <div id="daily-filter" class="{{ ($reportType ?? 'daily') == 'daily' ? '' : 'hidden' }}">
                        <label for="specific_date" class="block text-sm font-medium text-default-700 mb-2">Tanggal</label>
                        <input type="date" name="specific_date" id="specific_date"
                            class="form-input w-full px-4 py-2 border rounded-md text-default-800 focus:ring-primary-500 focus:border-primary-500"
                            value="{{ $specificDate ?? date('Y-m-d') }}">
                    </div>

                    <div id="monthly-filter" class="{{ ($reportType ?? 'daily') == 'monthly' ? '' : 'hidden' }}">
                        <label for="specific_month" class="block text-sm font-medium text-default-700 mb-2">Bulan</label>
                        <select name="specific_month" id="specific_month"
                            class="form-select w-full px-4 py-2 border rounded-md text-default-800 focus:ring-primary-500 focus:border-primary-500">
                            @for ($m = 1; $m <= 12; $m++)
                                <option value="{{ sprintf('%02d', $m) }}"
                                    {{ ($specificMonth ?? date('m')) == sprintf('%02d', $m) ? 'selected' : '' }}>
                                    {{ Carbon\Carbon::createFromDate(null, $m, 1)->translatedFormat('F') }}</option>
                            @endfor
                        </select>
                    </div>

                    <div id="yearly-filter"
                        class="{{ ($reportType ?? 'daily') == 'yearly' || ($reportType ?? 'daily') == 'monthly' ? '' : 'hidden' }}">
                        <label for="specific_year" class="block text-sm font-medium text-default-700 mb-2">Tahun</label>
                        <input type="number" name="specific_year" id="specific_year" min="2020"
                            max="{{ date('Y') + 5 }}"
                            class="form-input w-full px-4 py-2 border rounded-md text-default-800 focus:ring-primary-500 focus:border-primary-500"
                            value="{{ $specificYear ?? date('Y') }}">
                    </div>

                    <div id="custom-range-filter"
                        class="{{ ($reportType ?? 'daily') == 'custom_range' ? '' : 'hidden' }} md:col-span-2">
                        <label for="start_date" class="block text-sm font-medium text-default-700 mb-2">Rentang
                            Tanggal</label>
                        <div class="flex items-center gap-2">
                            <input type="date" name="start_date" id="start_date"
                                class="form-input w-1/2 px-4 py-2 border rounded-md text-default-800 focus:ring-primary-500 focus:border-primary-500"
                                value="{{ $startDate ?? '' }}">
                            <span>-</span>
                            <input type="date" name="end_date" id="end_date"
                                class="form-input w-1/2 px-4 py-2 border rounded-md text-default-800 focus:ring-primary-500 focus:border-primary-500"
                                value="{{ $endDate ?? '' }}">
                        </div>
                    </div>

                    {{-- Filter Pencarian Umum (Nomor Perjanjian / Nama Korlap) --}}
                    <div> {{-- Dulu ini md:col-span-3 lg:col-span-4, sekarang dipecah --}}
                        <label for="search" class="block text-sm font-medium text-default-700 mb-2">Cari No. Perjanjian /
                            Nama Korlap</label>
                        <input type="text" name="search" id="search" placeholder="Cari..."
                            class="form-input w-full px-4 py-2 border rounded-md text-default-800 focus:ring-primary-500 focus:border-primary-500"
                            value="{{ $search ?? '' }}">
                    </div>

                    {{-- Filter Korlap Dropdown --}}
                    <div>
                        <label for="field_coordinator_id" class="block text-sm font-medium text-default-700 mb-2">Filter
                            Korlap</label>
                        <select name="field_coordinator_id" id="field_coordinator_id"
                            class="form-select w-full px-4 py-2 border rounded-md text-default-800 focus:ring-primary-500 focus:border-primary-500 select2-korlap-filter">
                            <option value="">Semua Korlap</option>
                            @foreach ($fieldCoordinators as $fc)
                                <option value="{{ $fc->id }}"
                                    {{ ($fieldCoordinatorId ?? '') == $fc->id ? 'selected' : '' }}>
                                    {{ $fc->user->name ?? 'N/A' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                </div>

                <div class="flex items-center gap-2 mt-4">
                    <button type="submit"
                        class="px-4 py-2 rounded-md bg-primary-600 text-white hover:bg-primary-700 transition-all">
                        Tampilkan Laporan
                    </button>
                    @if ($specificDate || $specificMonth || $specificYear || $startDate || $endDate || $search || $fieldCoordinatorId)
                        <a href="{{ route('masterdata.deposit-reports.index') }}"
                            class="px-4 py-2 rounded-md bg-default-200 text-default-800 hover:bg-default-300 transition-all">
                            Reset Filter
                        </a>
                    @endif
                    {{-- Tombol Cetak PDF --}}
                    <button type="submit" name="print_pdf" value="true" formtarget="_blank"
                        class="px-4 py-2 rounded-md bg-red-600 text-white hover:bg-red-700 transition-all">
                        Cetak PDF
                    </button>
                </div>
            </form>

            {{-- Judul Laporan Dinamis --}}
            <h5 class="text-lg font-semibold text-default-800 mt-6 mb-4">{{ $reportTitle }}</h5>

            {{-- Laporan Tabel --}}
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-6">
                <table class="w-full text-sm text-left text-default-500">
                    <thead class="text-xs text-default-700 uppercase bg-default-50">
                        <tr>
                            <th scope="col" class="px-6 py-3">No. Perjanjian</th>
                            <th scope="col" class="px-6 py-3">Korlap</th>
                            <th scope="col" class="px-6 py-3">Tanggal Setoran</th>
                            <th scope="col" class="px-6 py-3">Jumlah Setoran (Rp)</th>
                            <th scope="col" class="px-6 py-3">Status Deposit</th>
                            <th scope="col" class="px-6 py-3">Catatan Deposit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reports as $report)
                            <tr class="bg-white border-b hover:bg-default-50">
                                <td class="px-6 py-4 font-medium text-default-900 whitespace-nowrap">
                                    {{ $report->agreement->agreement_number ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $report->agreement->fieldCoordinator->user->name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $report->deposit_date->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    Rp {{ number_format($report->amount, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $report->is_validated ? 'Divalidasi' : 'Belum Divalidasi' }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $report->notes ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr class="bg-white border-b">
                                <td colspan="6" class="text-center">Tidak ada data setoran untuk filter ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr class="bg-default-100 font-bold text-default-900">
                            <td colspan="5" class="text-right">Total Setoran:</td>
                            <td class="text-right">Rp {{ number_format($totalAmount, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const reportTypeSelect = document.getElementById('report_type');
            const dailyFilter = document.getElementById('daily-filter');
            const monthlyFilter = document.getElementById('monthly-filter');
            const yearlyFilter = document.getElementById('yearly-filter');
            const customRangeFilter = document.getElementById('custom-range-filter');

            function toggleFilterVisibility() {
                const selectedType = reportTypeSelect.value;

                dailyFilter.classList.add('hidden');
                monthlyFilter.classList.add('hidden');
                yearlyFilter.classList.add('hidden');
                customRangeFilter.classList.add('hidden');

                if (selectedType === 'daily') {
                    dailyFilter.classList.remove('hidden');
                } else if (selectedType === 'monthly') {
                    monthlyFilter.classList.remove('hidden');
                    yearlyFilter.classList.remove('hidden'); // Tahun juga muncul untuk bulanan
                } else if (selectedType === 'yearly') {
                    yearlyFilter.classList.remove('hidden');
                } else if (selectedType === 'custom_range') {
                    customRangeFilter.classList.remove('hidden');
                }
            }

            // Initial call to set correct visibility on page load
            toggleFilterVisibility();

            // Event listener for report type change
            reportTypeSelect.addEventListener('change', toggleFilterVisibility);

            // Initialize Select2 for Korlap filter
            $('.select2-korlap-filter').select2({
                placeholder: 'Pilih Korlap...',
                allowClear: true,
                width: 'resolve'
            });
        });
    </script>
@endpush
