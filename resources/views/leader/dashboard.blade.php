@extends('layouts.app') {{-- Ini memberitahu Blade untuk menggunakan layout app.blade.php --}}

@section('title', 'Dashboard - Leader') {{-- Mengisi placeholder 'title' --}}

@section('page_title', 'Dashboard') {{-- Mengisi placeholder 'page_title' --}}

@section('breadcrumb') {{-- Mengisi placeholder 'breadcrumb' --}}
<div class="md:flex hidden items-center gap-3 text-sm font-semibold">
    <a href="#" class="text-sm font-medium text-default-700">Opatix</a>
    <i class="i-tabler-chevron-right text-lg flex-shrink-0 text-default-500 rtl:rotate-180"></i>
    <a href="#" class="text-sm font-medium text-default-700">Menu</a>
    <i class="i-tabler-chevron-right text-lg flex-shrink-0 text-default-500 rtl:rotate-180"></i>
    <a href="#" class="text-sm font-medium text-default-700" aria-current="page">Dashboard</a>
</div>
@endsection

@section('content') {{-- Konten utama halaman dashboard --}}
<div class="grid xl:grid-cols-4 md:grid-cols-2 gap-6 mb-6">
    <div
        class="card group overflow-hidden transition-all duration-500 hover:shadow-lg hover:-translate-y-0.5">
        <div class="card-body">
            <div class="flex items- justify-between">
                <div>
                    <p class="text-xs tracking-wide font-semibold uppercase text-default-700 mb-3">Cost
                        per Unit</p>
                    <h4 class="font-semibold text-2xl text-default-700">$85.50</h4>
                </div>

                <div
                    class="rounded-full flex justify-center items-center size-14 bg-primary/10 text-primary">
                    <i
                        class="material-symbols-rounded text-2xl transition-all group-hover:fill-1">shopping_bag</i>
                </div>
            </div>
        </div>
        <div id="total-order"></div>
    </div>

    <div
        class="card group overflow-hidden transition-all duration-500 hover:shadow-lg hover:-translate-y-0.5">
        <div class="card-body">
            <div class="flex items- justify-between">
                <div>
                    <p class="text-xs tracking-wide font-semibold uppercase text-default-700 mb-3">
                        Market Revenue</p>
                    <h4 class="font-semibold text-2xl text-default-700">$12,548.25</h4>
                </div>

                <div
                    class="rounded-full flex justify-center items-center size-14 bg-secondary/10 text-secondary">
                    <i
                        class="material-symbols-rounded text-2xl transition-all group-hover:fill-1">payments</i>
                </div>
            </div>
        </div>
        <div id="total-sale"></div>
    </div>

    <div
        class="card group overflow-hidden transition-all duration-500 hover:shadow-lg hover:-translate-y-0.5">
        <div class="card-body">
            <div class="flex items- justify-between">
                <div>
                    <p class="text-xs tracking-wide font-semibold uppercase text-default-700 mb-3">
                        Expenses</p>
                    <h4 class="font-semibold text-2xl text-default-700">$8,451.28</h4>
                </div>

                <div
                    class="rounded-full flex justify-center items-center size-14 bg-warning/10 text-warning">
                    <i
                        class="material-symbols-rounded text-2xl transition-all group-hover:fill-1">visibility</i>
                </div>
            </div>
        </div>
        <div id="total-visits"></div>
    </div>

    <div
        class="card group overflow-hidden transition-all duration-500 hover:shadow-lg hover:-translate-y-0.5">
        <div class="card-body">
            <div class="flex items- justify-between">
                <div>
                    <p class="text-xs tracking-wide font-semibold uppercase text-default-700 mb-3">Daily
                        Visit</p>
                    <h4 class="font-semibold text-2xl text-default-700">1,12,584</h4>
                </div>

                <div class="rounded-full flex justify-center items-center size-14 bg-danger/10 text-danger">
                    <i
                        class="material-symbols-rounded text-2xl transition-all group-hover:fill-1">account_balance</i>
                </div>
            </div>
        </div>
        <div id="chart4"></div>
    </div>
</div>

<div class="grid xl:grid-cols-3 md:grid-cols-2 gap-6 mb-6">
    <div class="xl:col-span-2">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Total Revenue</h4>
                <div id="morris-line-example" class="morris-chart"></div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Activity By Teams</h4>
            <div id="morris-donut-example" class="morris-chart"></div>
        </div>
    </div>
</div>
@endsection

@section('scripts') {{-- Menambahkan JavaScript khusus untuk halaman ini --}}
<script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>

<script src="{{ asset('assets/libs/morris.js/morris.min.js') }}"></script>
<script src="{{ asset('assets/libs/raphael/raphael.min.js') }}"></script>

<script src="{{ asset('assets/js/pages/dashboard.js') }}"></script>
@endsection