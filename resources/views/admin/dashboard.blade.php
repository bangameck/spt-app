@extends('layouts.app')

@section('title', 'Admin Dashboard')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" />
@endpush

@section('content')
    {{-- ✅ PERUBAHAN DI SINI: Form Pencarian dengan Tombol --}}
    <div class="card mb-6">
        <div class="card-body">
            <form action="{{ route('admin.agreements.find') }}" method="POST">
                @csrf
                <label for="search-agreement" class="form-label fw-medium">Pencarian Cepat PKS</label>
                <div class="input-group">
                    <input type="search" id="search-agreement" name="agreement_number" class="form-control"
                        placeholder="Masukkan Nomor PKS..." required>
                    <button class="btn btn-primary" type="submit" id="button-addon2">
                        <i class="icon-base ri ri-search-line"></i>
                    </button>
                </div>
                @if (session('error'))
                    <div class="text-danger small mt-1">{{ session('error') }}</div>
                @endif
            </form>
        </div>
    </div>

    {{-- Baris Info Cards --}}
    <div class="row g-6 mb-6">
        <div class="col-sm-6 col-lg-3">
            <div class="card card-border-shadow-primary h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="avatar me-4">
                            <span class="avatar-initial rounded-3 bg-label-primary"><i
                                    class="icon-base ri ri-user-star-line icon-22px"></i></span>
                        </div>
                        <h4 class="mb-0">{{ $totalCoordinators }}</h4>
                    </div>
                    <p class="mb-0">Total Koordinator</p>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card card-border-shadow-success h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="avatar me-4">
                            <span class="avatar-initial rounded-3 bg-label-success"><i
                                    class="icon-base ri ri-file-text-line icon-22px"></i></span>
                        </div>
                        <h4 class="mb-0">{{ $totalActiveAgreements }}</h4>
                    </div>
                    <p class="mb-0">Total PKS Aktif</p>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card card-border-shadow-warning h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="avatar me-4">
                            <span class="avatar-initial rounded-3 bg-label-warning"><i
                                    class="icon-base ri ri-map-pin-line icon-22px"></i></span>
                        </div>
                        <h4 class="mb-0">{{ $totalParkingLocationsInPKS }}</h4>
                    </div>
                    <p class="mb-0">Total Titik Lokasi</p>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card card-border-shadow-info h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="avatar me-4">
                            <span class="avatar-initial rounded-3 bg-label-info"><i
                                    class="icon-base ri ri-wallet-3-line icon-22px"></i></span>
                        </div>
                        <h4 class="mb-0">Rp {{ number_format($currentYearValidatedDeposit, 0, ',', '.') }}</h4>
                    </div>
                    <p class="mb-0">Total Setoran Tahun {{ now()->year }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-6">
        <div class="col-lg-8">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">Grafik Setoran Tervalidasi ({{ now()->year }})</h5>
                </div>
                <div class="card-body">
                    <div id="deposit-area-chart"></div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="row g-6">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="d-flex align-items-center">
                                    <div class="avatar me-4">
                                        @if (
                                            $currentLeader &&
                                                $currentLeader->user &&
                                                $currentLeader->user->img &&
                                                file_exists(public_path($currentLeader->user->img)))
                                            <img src="{{ asset($currentLeader->user->img) }}" alt="Avatar"
                                                class="rounded-circle">
                                        @else
                                            <img src="{{ asset('assets/img/avatars/1.png') }}" alt="Avatar"
                                                class="rounded-circle">
                                        @endif
                                    </div>
                                    <div class="me-2">
                                        <h5 class="mb-0">{{ $currentLeader->user->name ?? 'Belum Ada' }}</h5>
                                        <p class="card-subtitle mb-0">Pimpinan Saat Ini</p>
                                    </div>
                                </div>
                                <span class="badge bg-label-primary">Pihak Pertama</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            @if ($activeBankAccount)
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <p class="mb-0">Rekening BLUD Aktif</p>
                                        <div class="d-flex align-items-center">
                                            <h5 class="mb-0 me-2">{{ $activeBankAccount->account_number }}</h5>
                                            <span
                                                class="badge bg-label-success rounded-pill">{{ $activeBankAccount->bank_name }}</span>
                                        </div>
                                    </div>
                                    <div class="avatar">
                                        <span class="avatar-initial rounded-3 bg-label-secondary"><i
                                                class="icon-base ri ri-bank-line icon-22px"></i></span>
                                    </div>
                                </div>
                            @else
                                <p class="text-center mb-0">Tidak ada rekening BLUD yang aktif.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="row g-6">
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="card-title m-0 me-2">Setoran Tervalidasi Terbaru</h5>
                        </div>
                        <div class="table-responsive text-nowrap">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Korlap</th>
                                        <th>Jumlah</th>
                                        <th>Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                    @forelse ($recentDeposits as $deposit)
                                        <tr>
                                            <td>{{ Str::limit($deposit->agreement->fieldCoordinator->user->name, 15) ?? 'N/A' }}
                                            </td>
                                            <td><span class="fw-medium">Rp
                                                    {{ number_format($deposit->amount, 0, ',', '.') }}</span></td>
                                            <td>{{ $deposit->deposit_date->format('d M') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center">Tidak ada data setoran.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="card-title m-0 me-2">Lokasi Parkir Terbaru</h5>
                        </div>
                        <div class="table-responsive text-nowrap">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Nama Lokasi</th>
                                        <th>Ruas Jalan</th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                    @forelse ($recentParkingLocations as $location)
                                        <tr>
                                            <td>{{ Str::limit($location->name, 20) }}</td>
                                            <td><span
                                                    class="badge bg-label-info">{{ $location->roadSection->name ?? 'N/A' }}</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="text-center">Tidak ada data lokasi.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card h-100">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="card-title m-0 me-2">Koordinator Terbaru</h5>
                        </div>
                        <div class="table-responsive text-nowrap">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>Telepon</th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                    @forelse ($recentCoordinators as $coordinator)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar avatar-xs me-2">
                                                        @if ($coordinator->user && $coordinator->user->img && file_exists(public_path($coordinator->user->img)))
                                                            <img src="{{ asset($coordinator->user->img) }}"
                                                                alt="Avatar" class="rounded-circle">
                                                        @else
                                                            <img src="{{ asset('assets/img/avatars/2.png') }}"
                                                                alt="Avatar" class="rounded-circle">
                                                        @endif
                                                    </div>
                                                    <span>{{ Str::limit($coordinator->user->name, 15) }}</span>
                                                </div>
                                            </td>
                                            <td>{{ $coordinator->phone_number }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="text-center">Tidak ada data koordinator.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('vendors-js')
    <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
@endpush

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const chartEl = document.querySelector("#deposit-area-chart");
            if (typeof ApexCharts !== 'undefined' && chartEl) {
                const mainChartOptions = {
                    chart: {
                        height: 350,
                        type: 'area',
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
                        data: @json($chartData)
                    }],
                    xaxis: {
                        categories: @json($chartLabels),
                        tooltip: {
                            enabled: false
                        },
                        axisBorder: {
                            show: false
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
                    colors: [config.colors.primary],
                    fill: {
                        type: 'gradient',
                        gradient: {
                            shadeIntensity: 1,
                            opacityFrom: 0.5,
                            opacityTo: 0.1,
                            stops: [0, 90, 100]
                        }
                    },
                    grid: {
                        borderColor: 'rgba(0,0,0,0.05)',
                        strokeDashArray: 5
                    }
                };
                new ApexCharts(chartEl, mainChartOptions).render();
            }
        });
    </script>
@endpush
