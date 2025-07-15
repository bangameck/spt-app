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

        {{-- Info Cards --}}
        <div class="grid xl:grid-cols-4 md:grid-cols-2 gap-6 mb-6">
            {{-- Card 1: Jumlah Korlap --}}
            <div class="card group overflow-hidden transition-all duration-500 hover:shadow-lg hover:-translate-y-0.5">
                <div class="card-body">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs tracking-wide font-semibold uppercase text-default-700 mb-3">Jumlah Korlap</p>
                            <h4 class="font-semibold text-2xl text-default-700">{{ $totalCoordinators }}</h4>
                        </div>
                        <div class="rounded-full flex justify-center items-center size-14 bg-primary/10 text-primary">
                            <i class="i-lucide-users text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card 2: Jumlah Ruas Jalan --}}
            <div class="card group overflow-hidden transition-all duration-500 hover:shadow-lg hover:-translate-y-0.5">
                <div class="card-body">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs tracking-wide font-semibold uppercase text-default-700 mb-3">Jumlah Ruas Jalan
                            </p>
                            <h4 class="font-semibold text-2xl text-default-700">{{ $totalRoadSections }}</h4>
                        </div>
                        <div class="rounded-full flex justify-center items-center size-14 bg-secondary/10 text-secondary">
                            <i class="i-lucide-map text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card 3: PKS Aktif --}}
            <div class="card group overflow-hidden transition-all duration-500 hover:shadow-lg hover:-translate-y-0.5">
                <div class="card-body">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs tracking-wide font-semibold uppercase text-default-700 mb-3">PKS Aktif</p>
                            <h4 class="font-semibold text-2xl text-default-700">{{ $totalActiveAgreements }}</h4>
                        </div>
                        <div class="rounded-full flex justify-center items-center size-14 bg-warning/10 text-warning">
                            <i class="i-lucide-handshake text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card 4: Titik Lokasi Terikat PKS --}}
            <div class="card group overflow-hidden transition-all duration-500 hover:shadow-lg hover:-translate-y-0.5">
                <div class="card-body">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs tracking-wide font-semibold uppercase text-default-700 mb-3">Titik Lokasi
                                Terikat</p>
                            <h4 class="font-semibold text-2xl text-default-700">{{ $totalParkingLocationsInPKS }}</h4>
                        </div>
                        <div class="rounded-full flex justify-center items-center size-14 bg-danger/10 text-danger">
                            <i class="i-lucide-map-pin text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Baris Grafik dan Setoran --}}
        <div class="grid xl:grid-cols-3 gap-6 mb-6">
            {{-- Kolom Grafik Utama --}}
            <div class="xl:col-span-2">
                <div class="card h-full">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Grafik Setoran Tervalidasi (Tahun {{ now()->year }})</h4>
                        <div id="deposit-chart" class="w-full"></div>
                    </div>
                </div>
            </div>

            {{-- Kolom Info Setoran --}}
            <div class="flex flex-col gap-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="text-sm font-medium text-default-600 mb-2">SETORAN HARI INI</h5>
                        <p class="text-3xl font-bold text-green-600">Rp
                            {{ number_format($todayValidatedDeposit, 0, ',', '.') }}</p>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
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
    {{-- Pastikan Anda sudah memuat ApexCharts di layout utama, jika belum, tambahkan ini --}}
    <script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Data dari Controller
            const chartLabels = @json($chartLabels);
            const chartData = @json($chartData);

            var options = {
                chart: {
                    height: 350,
                    type: 'bar',
                    toolbar: {
                        show: false
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '45%',
                        borderRadius: 5,
                    },
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['transparent']
                },
                series: [{
                    name: 'Total Setoran',
                    data: chartData
                }],
                xaxis: {
                    categories: chartLabels
                },
                yaxis: {
                    title: {
                        text: 'Rupiah (Rp)'
                    },
                    labels: {
                        formatter: function(val) {
                            if (val >= 1000000) {
                                return "Rp " + (val / 1000000).toFixed(1) + " Jt";
                            }
                            if (val >= 1000) {
                                return "Rp " + (val / 1000).toFixed(0) + " Rb";
                            }
                            return "Rp " + val;
                        }
                    }
                },
                fill: {
                    opacity: 1
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return "Rp " + val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        }
                    }
                },
                colors: ['#3e60d5', '#45cb85']
            };

            var chart = new ApexCharts(document.querySelector("#deposit-chart"), options);
            chart.render();
        });
    </script>
@endpush
