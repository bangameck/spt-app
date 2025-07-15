@extends('layouts.app')

@section('title', 'Detail Perjanjian: ' . $agreement->agreement_number)

@section('content')
    {{-- CSS kustom untuk timeline horizontal --}}
    <style>
        .timeline-horizontal-container {
            position: relative;
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            padding: 2rem 0;
            cursor: grab;
            scrollbar-width: thin;
            scrollbar-color: #a0aec0 #e2e8f0;
        }

        .timeline-horizontal-container::-webkit-scrollbar {
            height: 8px;
        }

        .timeline-horizontal-container::-webkit-scrollbar-track {
            background: #e2e8f0;
            border-radius: 10px;
        }

        .timeline-horizontal-container::-webkit-scrollbar-thumb {
            background-color: #a0aec0;
            border-radius: 10px;
        }

        .timeline-wrapper {
            position: relative;
            height: 220px;
            padding: 0 40px;
            white-space: nowrap;
            display: inline-block;
        }

        .timeline-line-h {
            position: absolute;
            top: 50%;
            left: 0;
            width: 100%;
            height: 4px;
            background-color: #e2e8f0;
            transform: translateY(-50%);
            z-index: 1;
        }

        .timeline-items {
            display: flex;
            align-items: flex-start;
            position: relative;
            z-index: 2;
        }

        .timeline-item-h {
            display: inline-flex;
            flex-direction: column;
            position: relative;
            width: 280px;
            margin: 0 20px;
            padding-top: 50px;
            white-space: normal;
        }

        .timeline-item-h.item-top {
            padding-top: 0;
            padding-bottom: 50px;
            justify-content: flex-end;
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
            background-clip: padding-box;
        }

        .item-top .timeline-pin-h {
            top: auto;
            bottom: -12px;
        }

        .timeline-item-h::after {
            content: '';
            position: absolute;
            left: 50%;
            top: 0;
            width: 2px;
            height: 40px;
            background-color: #cbd5e1;
            transform: translateX(-50%);
        }

        .item-top::after {
            top: auto;
            bottom: 0;
        }

        .timeline-content-h {
            padding: 1rem;
            background-color: white;
            border-radius: 0.5rem;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.05);
            text-align: left;
        }
    </style>

    <div class="container-fluid">
        <div class="flex justify-between items-center mb-6">
            <h4 class="text-default-900 text-2xl font-bold">Detail Perjanjian</h4>
            <div class="flex items-center gap-2">
                <a href="{{ route('masterdata.agreements.index') }}"
                    class="px-4 py-2 rounded-md text-default-600 border border-default-300 hover:bg-default-50 transition-all">Kembali</a>
                <a href="{{ route('masterdata.agreements.edit', $agreement->id) }}"
                    class="px-4 py-2 rounded-md text-white bg-primary-600 hover:bg-primary-700 transition-all"><i
                        class="i-lucide-edit size-4 me-2"></i> Edit</a>
                <a href="{{ route('masterdata.agreements.pdf', $agreement->id) }}" target="_blank"
                    class="px-4 py-2 rounded-md text-white bg-red-600 hover:bg-red-700 transition-all" title="Cetak PDF"><i
                        class="i-lucide-printer size-4 me-2"></i> Cetak PKS</a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Kolom Kiri: Detail Utama --}}
            <div class="lg:col-span-2">
                <div class="card bg-white shadow rounded-lg p-6">
                    {{-- Detail Header --}}
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h5 class="text-xl font-bold text-default-800">{{ $agreement->agreement_number }}</h5>
                            <p class="text-sm text-default-500">
                                Status: <span
                                    class="font-semibold px-2 py-1 rounded-full text-xs @if ($agreement->status == 'active') bg-green-100 text-green-800 @elseif($agreement->status == 'expired') bg-amber-100 text-amber-800 @elseif($agreement->status == 'terminated') bg-red-100 text-red-800 @else bg-blue-100 text-blue-800 @endif">{{ ucfirst(str_replace('_', ' ', $agreement->status)) }}</span>
                            </p>
                        </div>
                    </div>
                    <hr class="my-5">
                    {{-- Detail Konten --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                        <div>
                            <p class="text-sm text-default-600">Pimpinan</p>
                            <p class="font-semibold">{{ $agreement->leader->user->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-default-600">Koordinator Lapangan</p>
                            <p class="font-semibold">{{ $agreement->fieldCoordinator->user->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-default-600">Tanggal Mulai</p>
                            <p class="font-semibold">{{ $agreement->start_date->translatedFormat('d F Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-default-600">Tanggal Berakhir</p>
                            <p class="font-semibold">{{ $agreement->end_date->translatedFormat('d F Y') }}</p>
                        </div>
                    </div>
                    <hr class="my-5">
                    {{-- Detail Lokasi --}}
                    <div>
                        <h6 class="text-md font-semibold text-default-800 mb-3">Lokasi Parkir Aktif</h6>
                        <ul class="list-disc list-inside space-y-2">
                            @forelse ($agreement->activeParkingLocations as $location)
                                <li>{{ $location->name }} <span
                                        class="text-sm text-default-500">({{ $location->roadSection->name ?? 'N/A' }})</span>
                                </li>
                            @empty
                                <li>Tidak ada lokasi parkir aktif.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Kolom Kanan: Riwayat Setoran --}}
            <div>
                <div class="card bg-white shadow rounded-lg p-6 flex flex-col h-full">
                    <h6 class="text-md font-semibold text-default-800 mb-4">Riwayat Setoran</h6>
                    {{-- ✅ PERUBAHAN 1: Membuat area ini bisa di-scroll --}}
                    <div class="flex-grow space-y-3 max-h-80 overflow-y-auto pr-2">
                        @forelse ($agreement->depositTransactions->sortByDesc('deposit_date') as $transaction)
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="font-medium text-default-800">Rp
                                        {{ number_format($transaction->amount, 0, ',', '.') }}</p>
                                    <p class="text-xs text-default-500">
                                        {{ $transaction->deposit_date->translatedFormat('d M Y') }}</p>
                                </div>
                                @if ($transaction->is_validated)
                                    <span
                                        class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Tervalidasi</span>
                                @else
                                    <span
                                        class="px-2 py-1 text-xs font-semibold text-amber-800 bg-amber-100 rounded-full">Pending</span>
                                @endif
                            </div>
                        @empty
                            <p class="text-sm text-center text-default-500 py-4">Belum ada transaksi.</p>
                        @endforelse
                    </div>
                    {{-- ✅ PERUBAHAN 2: Menambahkan footer untuk total setoran --}}
                    <div class="border-t border-default-200 mt-4 pt-4">
                        <p class="text-sm text-default-600">Total Setoran Tervalidasi ({{ now()->year }})</p>
                        <p class="text-lg font-bold text-default-900">Rp
                            {{ number_format($totalDepositThisYear, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Bagian Timeline Riwayat Perjanjian --}}
        <div class="card bg-white shadow rounded-lg p-6 mt-6">
            <h5 class="text-lg font-semibold text-default-800 mb-2 text-center">Riwayat Perjanjian</h5>
            <div class="timeline-horizontal-container">
                <div class="timeline-wrapper">
                    <div class="timeline-line-h"></div>
                    <div class="timeline-items">
                        @forelse ($agreement->histories->sortByDesc('created_at') as $key => $history)
                            @php
                                $positionClass = $key % 2 != 0 ? 'item-top' : '';
                                $icon = 'i-lucide-file-text';
                                $color = 'bg-gray-400';
                                switch ($history->event_type) {
                                    case 'agreement_created':
                                        $icon = 'i-lucide-file-plus-2';
                                        $color = 'bg-primary-500';
                                        break;
                                    case 'location_added':
                                        $icon = 'i-lucide-map-pin';
                                        $color = 'bg-green-500';
                                        break;
                                    case 'location_removed':
                                        $icon = 'i-lucide-map-pin-off';
                                        $color = 'bg-amber-500';
                                        break;
                                    case 'deposit_changed':
                                        $icon = 'i-lucide-receipt';
                                        $color = 'bg-blue-500';
                                        break;
                                    case 'status_changed':
                                        $icon = 'i-lucide-toggle-right';
                                        $color = 'bg-teal-500';
                                        break;
                                }
                            @endphp
                            <div class="timeline-item-h {{ $positionClass }}">
                                <div class="timeline-content-h">
                                    <div class="flex justify-between items-center">
                                        <p class="font-semibold text-sm">{{ $history->creator->name ?? 'Sistem' }}</p>
                                        <p class="text-xs text-default-400">
                                            {{ $history->created_at->translatedFormat('d M Y, H:i') }}</p>
                                    </div>
                                    <p class="text-sm mt-1">{{ $history->notes }}</p>
                                </div>
                                <div class="timeline-pin-h {{ $color }}"><i
                                        class="{{ $icon }} text-white size-3"></i></div>
                            </div>
                        @empty
                            <div class="text-center w-full py-8 absolute">
                                <p>Belum ada riwayat tercatat.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        // Logika Drag to Scroll untuk timeline
        const wrapper = document.querySelector('.timeline-horizontal-container');
        if (wrapper) {
            let isDown = false;
            let startX;
            let scrollLeft;

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
