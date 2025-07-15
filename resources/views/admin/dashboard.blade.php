@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
    <main>
        {{-- Page Title & Breadcrumb --}}
        <div class="flex items-center md:justify-between flex-wrap gap-2 mb-5">
            <h4 class="text-default-900 text-lg font-semibold">Dashboard</h4>
            <div class="md:flex hidden items-center gap-3 text-sm font-semibold">
                <a href="#" class="text-sm font-medium text-default-700">Admin</a>
                <i class="i-tabler-chevron-right text-lg flex-shrink-0 text-default-500 rtl:rotate-180"></i>
                <a href="#" class="text-sm font-medium text-default-700" aria-current="page">Dashboard</a>
            </div>
        </div>

        {{-- Baris Pencarian PKS --}}
        <div class="mb-6">
            <form action="{{ route('admin.agreements.find') }}" method="POST">
                @csrf
                <div class="relative">
                    <i class="i-lucide-search absolute top-1/2 start-4 -translate-y-1/2 text-default-500"></i>
                    <input type="search" name="agreement_number" class="form-input rounded-full ps-12 w-full bg-default-50"
                        placeholder="Cari berdasarkan Nomor PKS (Contoh: PKS-2025-001) dan tekan Enter...">
                </div>
            </form>
            @if (session('error'))
                <p class="text-sm text-red-500 mt-2">{{ session('error') }}</p>
            @endif
        </div>

        {{-- Info Cards dengan Mini Charts --}}
        <div class="grid xl:grid-cols-4 md:grid-cols-2 gap-6 mb-6">
            {{-- Card 1: Jumlah Korlap --}}
            <div class="card overflow-hidden">
                <div class="card-body">
                    <div class="flex items-center justify-between">
                        <p class="text-xs tracking-wide font-semibold uppercase text-default-700">Jumlah Korlap</p>
                        <div class="rounded-full flex justify-center items-center size-12 bg-primary/10 text-primary">
                            <i class="i-lucide-users text-xl"></i>
                        </div>
                    </div>
                    <h4 class="font-semibold text-2xl text-default-700 mt-1">{{ $totalCoordinators }}</h4>
                </div>
                <div id="mini-chart-1" class="-mb-2"></div>
            </div>

            {{-- Card 2: Jumlah Ruas Jalan --}}
            <div class="card overflow-hidden">
                <div class="card-body">
                    <div class="flex items-center justify-between">
                        <p class="text-xs tracking-wide font-semibold uppercase text-default-700">Jumlah Ruas Jalan</p>
                        <div class="rounded-full flex justify-center items-center size-12 bg-secondary/10 text-secondary">
                            <i class="i-lucide-map text-xl"></i>
                        </div>
                    </div>
                    <h4 class="font-semibold text-2xl text-default-700 mt-1">{{ $totalRoadSections }}</h4>
                </div>
                <div id="mini-chart-2" class="-mb-2"></div>
            </div>

            {{-- Card 3: PKS Aktif --}}
            <div class="card overflow-hidden">
                <div class="card-body">
                    <div class="flex items-center justify-between">
                        <p class="text-xs tracking-wide font-semibold uppercase text-default-700">PKS Aktif</p>
                        <div class="rounded-full flex justify-center items-center size-12 bg-warning/10 text-warning">
                            <i class="i-lucide-handshake text-xl"></i>
                        </div>
                    </div>
                    <h4 class="font-semibold text-2xl text-default-700 mt-1">{{ $totalActiveAgreements }}</h4>
                </div>
                <div id="mini-chart-3" class="-mb-2"></div>
            </div>

            {{-- Card 4: Titik Lokasi Terikat --}}
            <div class="card overflow-hidden">
                <div class="card-body">
                    <div class="flex items-center justify-between">
                        <p class="text-xs tracking-wide font-semibold uppercase text-default-700">Titik Lokasi Terikat</p>
                        <div class="rounded-full flex justify-center items-center size-12 bg-danger/10 text-danger">
                            <i class="i-lucide-map-pin text-xl"></i>
                        </div>
                    </div>
                    <h4 class="font-semibold text-2xl text-default-700 mt-1">{{ $totalParkingLocationsInPKS }}</h4>
                </div>
                <div id="mini-chart-4" class="-mb-2"></div>
            </div>
        </div>

        {{-- Baris Grafik Utama dan Setoran --}}
        <div class="grid xl:grid-cols-3 gap-6 mb-6">
            <div class="xl:col-span-2">
                <div class="card h-full">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Grafik Setoran Tervalidasi (Tahun {{ now()->year }})</h4>
                        {{-- ✅ Grafik utama diubah menjadi Area Chart --}}
                        <div id="deposit-area-chart" class="w-full"></div>
                    </div>
                </div>
            </div>
            <div class="flex flex-col gap-6">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="text-sm font-medium text-default-600 mb-2">SETORAN HARI INI</h5>
                        <p class="text-3xl font-bold text-green-600">Rp
                            {{ number_format($todayValidatedDeposit, 0, ',', '.') }}</p>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="text-sm font-medium text-default-600 mb-2">TOTAL SETORAN TAHUN INI</h5>
                        <p class="text-3xl font-bold text-default-900">Rp
                            {{ number_format($currentYearValidatedDeposit, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
    <script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // === GRAFIK UTAMA: AREA CHART ===
            var mainChartOptions = {
                chart: {
                    height: 350,
                    type: 'area', // Tipe diubah menjadi 'area'
                    toolbar: {
                        show: false
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth',
                    width: 2
                },
                series: [{
                    name: 'Total Setoran',
                    data: @json($mainChartData)
                }],
                xaxis: {
                    categories: @json($chartLabels),
                    tooltip: {
                        enabled: false
                    }
                },
                yaxis: {
                    labels: {
                        formatter: (val) => {
                            if (val >= 1000000) return `Rp ${(val / 1000000).toFixed(1)} Jt`;
                            return `Rp ${(val / 1000).toFixed(0)} Rb`;
                        }
                    }
                },
                tooltip: {
                    y: {
                        formatter: (val) => "Rp " + val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
                    }
                },
                colors: ['#3e60d5'],
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.4,
                        opacityTo: 0.1,
                        stops: [0, 90, 100]
                    }
                },
                grid: {
                    borderColor: '#e5e7eb20',
                    strokeDashArray: 5,
                }
            };
            new ApexCharts(document.querySelector("#deposit-area-chart"), mainChartOptions).render();

            // === MINI CHARTS PADA INFO CARDS ===
            function createMiniChart(elementId, data, color) {
                var options = {
                    chart: {
                        type: 'area',
                        height: 60,
                        sparkline: {
                            enabled: true
                        }
                    },
                    stroke: {
                        curve: 'smooth',
                        width: 2
                    },
                    fill: {
                        type: 'gradient',
                        gradient: {
                            shadeIntensity: 1,
                            opacityFrom: 0.4,
                            opacityTo: 0.1,
                        }
                    },
                    series: [{
                        data: data
                    }],
                    colors: [color],
                    tooltip: {
                        enabled: false
                    }
                };
                new ApexCharts(document.querySelector(elementId), options).render();
            }

            createMiniChart("#mini-chart-1", @json($miniChartData1), '#3e60d5'); // Primary
            createMiniChart("#mini-chart-2", @json($miniChartData2), '#45cb85'); // Secondary
            createMiniChart("#mini-chart-3", @json($miniChartData3), '#ffbc00'); // Warning
            createMiniChart("#mini-chart-4", @json($miniChartData4), '#ff675c'); // Danger
        });
    </script>
@endpush
