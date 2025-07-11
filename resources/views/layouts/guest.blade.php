<!DOCTYPE html>
<html lang="en" data-layout-mode="detached" data-menu-style="dark" data-topbar-color="light" data-sidenav-size="full" data-sidenav-user-info="false">

<head>
    <meta charset="utf-8">
    <title>@yield('title', 'Login | Opatix')</title> {{-- Placeholder untuk judul halaman login --}}
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description">
    <meta content="MyraStudio" name="author">
    <meta name="csrf-token" content="{{ csrf_token() }}"> {{-- Penting untuk Laravel --}}

    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">

    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css">
    {{-- Jika ada CSS khusus untuk halaman auth seperti login/register, tambahkan di sini --}}

    @yield('styles') {{-- Placeholder untuk CSS tambahan per halaman login/register --}}
</head>

<body class="bg-gradient-to-r from-rose-100 to-teal-100"> {{-- Sesuaikan background body sesuai template login --}}

    {{-- Ini adalah bagian utama tempat formulir login/register akan disisipkan --}}
    <div class="flex flex-col items-center justify-center min-h-screen">
        @yield('content')
    </div>

    <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/libs/preline/preline.js') }}"></script>
    <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/libs/iconify-icon/iconify-icon.min.js') }}"></script>
    <script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>

    <script src="{{ asset('assets/js/app.js') }}"></script> {{-- JS utama template --}}

    @yield('scripts') {{-- Placeholder untuk JavaScript tambahan per halaman login/register --}}

</body>

</html>