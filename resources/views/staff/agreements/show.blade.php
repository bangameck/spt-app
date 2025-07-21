@extends('layouts.app')

@section('title', 'Detail Perjanjian: ' . $agreement->agreement_number)

@section('content')
    {{-- CSS untuk timeline horizontal --}}
    <style>
        .timeline-horizontal-container {
            position: relative;
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            padding: 2rem 0;
            cursor: grab;
            scrollbar-width: thin;
            scrollbar-color: #a0aec0 #e2e8f0
        }

        .timeline-horizontal-container::-webkit-scrollbar {
            height: 8px
        }

        .timeline-horizontal-container::-webkit-scrollbar-track {
            background: #e2e8f0;
            border-radius: 10px
        }

        .timeline-horizontal-container::-webkit-scrollbar-thumb {
            background-color: #a0aec0;
            border-radius: 10px
        }

        .timeline-wrapper {
            position: relative;
            height: 220px;
            padding: 0 40px;
            white-space: nowrap;
            display: inline-block
        }

        .timeline-line-h {
            position: absolute;
            top: 50%;
            left: 0;
            width: 100%;
            height: 4px;
            background-color: #e2e8f0;
            transform: translateY(-50%);
            z-index: 1
        }

        .timeline-items {
            display: flex;
            align-items: flex-start;
            position: relative;
            z-index: 2
        }

        .timeline-item-h {
            display: inline-flex;
            flex-direction: column;
            position: relative;
            width: 280px;
            margin: 0 20px;
            padding-top: 50px;
            white-space: normal
        }

        .timeline-item-h.item-top {
            padding-top: 0;
            padding-bottom: 50px;
            justify-content: flex-end
        }

        .timeline-pin-h {
            position: absolute;
            left: 50%;
            top: -12px;
            transform: translateX(-50%);
            width: 24px;
            height: 24px;
            border-radius: 50%;
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 3px solid #f8fafc;
            background-clip: padding-box
        }

        .item-top .timeline-pin-h {
            top: auto;
            bottom: -12px
        }

        .timeline-item-h::after {
            content: '';
            position: absolute;
            left: 50%;
            top: 0;
            width: 2px;
            height: 40px;
            background-color: #cbd5e1;
            transform: translateX(-50%)
        }

        .item-top::after {
            top: auto;
            bottom: 0
        }

        .timeline-content-h {
            padding: 1rem;
            background-color: white;
            border-radius: .5rem;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 6px -1px #0000001a, 0 2px 4px -2px #0000001a;
            text-align: left
        }
    </style>

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-xl-4 col-lg-5 col-md-5 order-1 order-md-0">
                <div class="card mb-6">
                    <div class="card-body pt-12">
                        <div class="user-avatar-section">
                            <div class="d-flex align-items-center flex-column">
                                @if (
                                    $agreement->fieldCoordinator->user &&
                                        $agreement->fieldCoordinator->user->img &&
                                        file_exists(public_path($agreement->fieldCoordinator->user->img)))
                                    <img class="img-fluid rounded-3 mb-4"
                                        src="{{ asset($agreement->fieldCoordinator->user->img) }}" height="120"
                                        width="120" alt="Korlap Avatar" />
                                @else
                                    <div class="avatar avatar-xl mb-4"><span
                                            class="avatar-initial rounded-3 bg-label-warning">{{ strtoupper(substr($agreement->fieldCoordinator->user->name ?? 'K', 0, 2)) }}</span>
                                    </div>
                                @endif
                                <div class="user-info text-center">
                                    <h5 class="mb-2">{{ $agreement->fieldCoordinator->user->name ?? 'N/A' }}</h5>
                                    {{-- ✅ PERUBAHAN 1: Menambahkan data Zona --}}
                                    @php
                                        $zone = $agreement->activeParkingLocations->first()->roadSection->zone ?? null;
                                    @endphp
                                    <span class="badge bg-label-warning rounded-pill">
                                        Koordinator Lapangan @if ($zone)
                                            {{ $zone }}
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-around flex-wrap my-6 gap-0 gap-md-3 gap-lg-4">
                            <div class="d-flex align-items-center me-5 gap-4">
                                <div class="avatar">
                                    <div class="avatar-initial bg-label-primary rounded-3"><i
                                            class="icon-base ri ri-file-text-line ri-24px"></i></div>
                                </div>
                                <div>
                                    <h5 class="mb-0">{{ $agreement->activeParkingLocations->count() }}</h5><span>Titik
                                        Lokasi</span>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-4">
                                <div class="avatar">
                                    <div class="avatar-initial bg-label-success rounded-3"><i
                                            class="icon-base ri ri-wallet-3-line ri-24px"></i></div>
                                </div>
                                <div>
                                    <h5 class="mb-0">Rp {{ number_format($totalDepositThisYear, 0, ',', '.') }}</h5>
                                    <span>Setoran Tahun Ini</span>
                                </div>
                            </div>
                        </div>
                        <h5 class="pb-4 border-bottom mb-4">Details Perjanjian</h5>
                        <div class="info-container">
                            <ul class="list-unstyled mb-6">
                                <li class="mb-2"><span class="fw-medium text-heading me-2">No.
                                        PKS:</span><span>{{ $agreement->agreement_number }}</span></li>
                                <li class="mb-2"><span class="fw-medium text-heading me-2">Status:</span><span
                                        class="badge rounded-pill bg-label-{{ $agreement->status == 'active' ? 'success' : 'danger' }}">{{ ucfirst(str_replace('_', ' ', $agreement->status)) }}</span>
                                </li>
                                <li class="mb-2"><span
                                        class="fw-medium text-heading me-2">Pimpinan:</span><span>{{ $agreement->leader->user->name ?? 'N/A' }}</span>
                                </li>
                                <li class="mb-2"><span class="fw-medium text-heading me-2">Masa
                                        Berlaku:</span><span>{{ $agreement->start_date->translatedFormat('d M y') }} -
                                        {{ $agreement->end_date->translatedFormat('d M y') }}</span></li>
                            </ul>
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('masterdata.agreements.edit', $agreement->id) }}"
                                    class="btn btn-primary"><i class="icon-base ri ri-pencil-line me-2"></i>Edit</a>
                                <a href="{{ route('masterdata.agreements.pdf', $agreement->id) }}" target="_blank"
                                    class="btn btn-outline-danger"><i class="icon-base ri ri-printer-line me-2"></i>Cetak
                                    PKS</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-8 col-lg-7 col-md-7 order-0 order-md-1">
                <div class="card mb-6">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Daftar Lokasi Parkir Aktif</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive text-nowrap" style="max-height: 300px; overflow-y: auto;">
                            <table class="table table-sm">
                                <tbody>
                                    @forelse ($agreement->activeParkingLocations as $location)
                                        <tr>
                                            <td><i
                                                    class="icon-base ri ri-map-pin-2-line text-primary me-2"></i>{{ $location->name }}
                                            </td>
                                            <td><span class="text-muted">{{ $location->roadSection->name ?? 'N/A' }}</span>
                                            </td>
                                            <td><span
                                                    class="badge bg-label-dark rounded-pill">{{ $location->roadSection->zone ?? 'N/A' }}</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td class="text-center text-muted">Tidak ada lokasi parkir aktif.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Riwayat Setoran</h5>
                    </div>
                    <div class="table-responsive text-nowrap" style="max-height: 300px; overflow-y: auto;">
                        <table class="table table-sm table-hover">
                            <tbody>
                                @forelse ($agreement->depositTransactions as $transaction)
                                    <tr>
                                        <td>{{ $transaction->deposit_date->translatedFormat('d F Y') }}</td>
                                        <td class="fw-medium">Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                                        </td>
                                        <td>
                                            @if ($transaction->is_validated)
                                                <span class="badge bg-label-success">Tervalidasi</span>
                                            @else
                                                <span class="badge bg-label-warning">Pending</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">Belum ada riwayat setoran.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-12 order-2 mt-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0 text-center">Timeline Perjanjian</h5>
                    </div>
                    <div class="card-body">
                        <div class="timeline-horizontal-container">
                            <div class="timeline-wrapper">
                                <div class="timeline-line-h"></div>
                                <div class="timeline-items">
                                    @forelse ($agreement->histories as $key => $history)
                                        @php
                                            $positionClass = $key % 2 != 0 ? 'item-top' : '';
                                            $icon = 'ri-file-text-line';
                                            $color = 'bg-secondary';
                                            switch ($history->event_type) {
                                                case 'agreement_created':
                                                    $icon = 'ri-file-add-line';
                                                    $color = 'bg-primary';
                                                    break;
                                                case 'location_added':
                                                    $icon = 'ri-map-pin-add-line';
                                                    $color = 'bg-success';
                                                    break;
                                                case 'location_removed':
                                                    $icon = 'ri-map-pin-user-line';
                                                    $color = 'bg-warning';
                                                    break;
                                                case 'deposit_changed':
                                                    $icon = 'ri-money-dollar-circle-line';
                                                    $color = 'bg-info';
                                                    break;
                                                case 'status_changed':
                                                case 'agreement_renewed':
                                                    $icon = 'ri-refresh-line';
                                                    $color = 'bg-success';
                                                    break;
                                                case 'agreement_terminated':
                                                    $icon = 'ri-shield-x-line';
                                                    $color = 'bg-danger';
                                                    break;
                                            }
                                        @endphp
                                        <div class="timeline-item-h {{ $positionClass }}">
                                            <div class="timeline-content-h">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <p class="fw-medium text-sm text-heading mb-0">
                                                        {{ $history->creator->name ?? 'Sistem' }}</p>
                                                    <p class="text-muted small mb-0">
                                                        {{ $history->created_at->translatedFormat('d M, H:i') }}</p>
                                                </div>
                                                <p class="text-body-secondary small mt-1">{{ $history->notes }}</p>
                                            </div>
                                            <div class="timeline-pin-h {{ $color }}"><i
                                                    class="icon-base {{ $icon }} text-white"></i></div>
                                        </div>
                                    @empty
                                        <div class="text-center w-100 py-4 absolute">
                                            <p class="text-muted">Belum ada riwayat tercatat.</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Logika Drag to Scroll
        const wrapper = document.querySelector('.timeline-horizontal-container');
        if (wrapper) {
            let isDown = false,
                startX, scrollLeft;
            wrapper.addEventListener('mousedown', (e) => {
                isDown = true;
                wrapper.style.cursor = 'grabbing';
                startX = e.pageX - wrapper.offsetLeft;
                scrollLeft = wrapper.scrollLeft;
            });
            wrapper.addEventListener('mouseleave', () => {
                isDown = false;
                wrapper.style.cursor = 'grab';
            });
            wrapper.addEventListener('mouseup', () => {
                isDown = false;
                wrapper.style.cursor = 'grab';
            });
            wrapper.addEventListener('mousemove', (e) => {
                if (!isDown) return;
                e.preventDefault();
                const x = e.pageX - wrapper.offsetLeft;
                const walk = (x - startX) * 2;
                wrapper.scrollLeft = scrollLeft - walk;
            });
        }
    </script>
@endpush
