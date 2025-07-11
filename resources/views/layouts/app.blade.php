<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <title>@yield('title', 'Dashboard | Opatix')</title> {{-- Placeholder untuk judul halaman --}}
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description">
        <meta content="MyraStudio" name="author">

        <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">

        <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

        <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css">

        @yield('styles') {{-- Placeholder untuk CSS tambahan per halaman --}}
    </head>

    <body>

        <div class="wrapper">
            {{-- Sidebar --}}
            @include('layouts.sidebar') {{-- Menggunakan @include untuk sidebar --}}

            <div class="page-content">
                {{-- Topbar --}}
                @include('layouts.topbar') {{-- Menggunakan @include untuk topbar --}}

                <main>
                    <div class="flex items-center md:justify-between flex-wrap gap-2 mb-5">
                        <h4 class="text-default-900 text-lg font-semibold">@yield('page_title', 'Dashboard')</h4> {{-- Placeholder untuk judul halaman utama --}}
                        @yield('breadcrumb') {{-- Placeholder untuk breadcrumb --}}
                    </div>
                    @yield('content') {{-- Ini adalah bagian utama konten yang akan berubah per halaman --}}
                </main>

                {{-- Footer --}}
                @include('layouts.footer') {{-- Menggunakan @include untuk footer --}}
            </div>
        </div>

        <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('assets/libs/preline/preline.js') }}"></script>
        <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
        <script src="{{ asset('assets/libs/iconify-icon/iconify-icon.min.js') }}"></script>
        <script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://unpkg.com/lucide@latest"></script>

        <script src="{{ asset('assets/js/app.js') }}"></script>

        @stack('scripts') {{-- Placeholder untuk JavaScript tambahan per halaman --}}
    </body>

</html>
