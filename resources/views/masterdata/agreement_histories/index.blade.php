@extends('layouts.app')

@section('title', 'Histori Perjanjian Kerjasama')

@section('content')
    {{-- 1. Pindahkan CSS dari @push ke dalam @section('content') --}}
    <style>
        /* === HORIZONTAL TIMELINE STYLES === */
        .timeline-horizontal-container {
            position: relative;
            width: 100%;
            overflow-x: auto;
            /* Ubah ke 'auto' agar scrollbar muncul jika perlu */
            -webkit-overflow-scrolling: touch;
            /* Scroll lebih mulus di mobile */
            padding: 2rem 0;
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
            /* Sedikit lebih tinggi untuk memberi ruang */
            padding: 0 40px;
            white-space: nowrap;
            /* Mencegah item turun baris */
            display: inline-block;
            /* Membuat wrapper mengikuti lebar konten */
        }

        /* Garis horizontal di tengah */
        .timeline-line-h {
            position: absolute;
            top: 50%;
            left: 0;
            width: 100%;
            /* Pastikan garis membentang selebar konten */
            height: 4px;
            background-color: #e2e8f0;
            transform: translateY(-50%);
            z-index: 1;
        }

        /* Kontainer untuk semua item */
        .timeline-items {
            display: flex;
            /* Gunakan flexbox untuk alignment */
            align-items: flex-start;
            position: relative;
            z-index: 2;
        }

        /* Setiap item dalam timeline */
        .timeline-item-h {
            display: inline-flex;
            /* Menggunakan inline-flex */
            flex-direction: column;
            position: relative;
            width: 280px;
            margin: 0 20px;
            padding-top: 50px;
            white-space: normal;
        }

        /* Item di posisi atas */
        .timeline-item-h.item-top {
            padding-top: 0;
            padding-bottom: 50px;
            justify-content: flex-end;
            /* Dorong konten ke bawah */
        }

        /* Pin ikon di garis tengah */
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

        /* Garis penghubung dari pin ke kartu */
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

        /* Konten kartu */
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
        <h4 class="text-default-900 text-2xl font-bold mb-6">Histori Perjanjian Kerjasama</h4>

        {{-- Form Filter --}}
        <div class="card bg-white shadow rounded-lg p-6">
            <form action="{{ route('masterdata.agreement-histories.index') }}" method="GET">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                    <div class="md:col-span-2">
                        <label for="agreement_id" class="block text-sm font-medium text-default-700 mb-2">Pilih
                            Perjanjian</label>
                        <select name="agreement_id" id="agreement_id" class="form-select w-full select2" required>
                            <option value="">Cari dan Pilih Perjanjian...</option>
                            @foreach ($agreementsForFilter as $item)
                                <option value="{{ $item->id }}"
                                    {{ $selectedAgreementId == $item->id ? 'selected' : '' }}>
                                    {{ $item->agreement_number }} - {{ $item->fieldCoordinator->user->name ?? 'N/A' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <button type="submit"
                            class="w-full px-6 py-2 rounded-md text-white bg-primary-600 hover:bg-primary-700">Tampilkan
                            Histori</button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Hasil Timeline --}}
        @if ($agreement)
            <div class="card bg-white shadow rounded-lg p-6 mt-6">
                <h5 class="text-xl font-semibold text-default-800 mb-2 text-center">
                    Timeline untuk: {{ $agreement->agreement_number }}
                </h5>

                <div class="timeline-horizontal-container">
                    <div class="timeline-wrapper">
                        <div class="timeline-line-h"></div>
                        <div class="timeline-items">
                            @forelse ($agreement->histories as $key => $history)
                                @php
                                    $positionClass = $key % 2 == 0 ? 'item-top' : '';
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
                                        case 'agreement_terminated':
                                            $icon = 'i-lucide-shield-x';
                                            $color = 'bg-red-500';
                                            break;
                                    }
                                @endphp
                                <div class="timeline-item-h {{ $positionClass }}">
                                    <div class="timeline-content-h">
                                        <p class="font-semibold text-sm text-default-700">
                                            {{ $history->creator->name ?? 'Sistem' }}</p>
                                        <p class="text-xs text-default-400 mb-2">
                                            {{ $history->created_at->translatedFormat('d M Y') }}</p>
                                        <p class="text-sm text-default-800">{{ $history->notes }}</p>
                                    </div>
                                    <div class="timeline-pin-h {{ $color }}">
                                        <i class="{{ $icon }} text-white size-3"></i>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center w-full py-8 absolute">
                                    <p class="text-default-500">Belum ada riwayat tercatat untuk perjanjian ini.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="card bg-white shadow rounded-lg p-10 mt-6 text-center">
                <i class="i-lucide-history text-5xl text-default-400"></i>
                <p class="mt-4 text-default-600">Pilih perjanjian untuk melihat timeline historinya.</p>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            // Inisialisasi Select2
            $('.select2').select2({
                placeholder: "Cari...",
                allowClear: true
            });

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
                    const walk = (x - startX) * 2; // Kecepatan scroll
                    wrapper.scrollLeft = scrollLeft - walk;
                });
            }
        });
    </script>
@endpush
