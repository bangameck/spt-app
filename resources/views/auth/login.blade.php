@extends('layouts.guest')

@section('title', 'Login - Sistem Informasi Perparkiran')

@section('content')
{{-- CSS Kustom untuk Latar Belakang Animasi --}}
<style>
    .auth-bg-gradient {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: -1;
        background: linear-gradient(-45deg, #3e60d5, #4a72ff, #23a6d5, #23d5ab);
        background-size: 400% 400%;
        animation: gradientBG 15s ease infinite;
    }

    @keyframes gradientBG {
        0% {
            background-position: 0% 50%;
        }

        50% {
            background-position: 100% 50%;
        }

        100% {
            background-position: 0% 50%;
        }
    }
</style>

{{-- Latar Belakang Gradien Animasi --}}
<div class="auth-bg-gradient"></div>

{{-- ✅ PERUBAHAN UTAMA: Form diposisikan di tengah --}}
<div class="container relative flex items-center justify-center min-h-screen px-4">

    <div class="w-full max-w-md">
        {{-- Header Form --}}
        <div class="text-center mb-10">
            <a href="{{ url('/') }}">
                <img src="{{ asset('assets/images/logo-light.png') }}" alt="logo" class="h-12 mx-auto">
            </a>
            <h2 class="text-3xl font-bold text-white mt-6">Selamat Datang Kembali</h2>
            <p class="text-base text-white/70 mt-2">Masuk untuk melanjutkan ke dashboard Anda.</p>
        </div>

        {{-- Form Login --}}
        <div class="w-full">
            {{-- Session Status --}}
            <x-auth-session-status class="mb-4 bg-green-500/20 text-green-200 p-3 rounded-md text-sm" :status="session('status')" />

            {{-- Validation Errors --}}
            @if ($errors->any())
            <div class="mb-4 bg-red-500/20 text-red-200 p-3 rounded-md text-sm">
                Email atau Password yang Anda masukkan salah.
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf
                <div>
                    <label for="email" class="text-white/80 text-sm font-medium block mb-2">Alamat Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                        class="form-input w-full px-4 py-2 bg-white/10 border-white/30 text-white rounded-md focus:border-white focus:ring-white/20 placeholder:text-white/50"
                        placeholder="contoh@email.com">
                </div>

                <div>
                    <label for="password" class="text-white/80 text-sm font-medium block mb-2">Password</label>
                    <input type="password" id="password" name="password" required autocomplete="current-password"
                        class="form-input w-full px-4 py-2 bg-white/10 border-white/30 text-white rounded-md focus:border-white focus:ring-white/20 placeholder:text-white/50"
                        placeholder="Masukkan password Anda">
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input type="checkbox" name="remember" id="remember_me" class="form-checkbox text-primary rounded border-white/30 bg-transparent">
                        <label for="remember_me" class="ms-2 text-sm text-white/80">Ingat Saya</label>
                    </div>
                    @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-sm text-white/80 hover:text-white hover:underline">Lupa Password?</a>
                    @endif
                </div>

                <div>
                    <button type="submit"
                        class="w-full px-6 py-3 rounded-md text-primary bg-white hover:bg-white/90 transition-all duration-300 ease-in-out font-semibold text-lg">
                        LOGIN
                    </button>
                </div>
            </form>

            @if (Route::has('register'))
            <p class="text-center text-white/60 text-sm mt-6">
                Belum punya akun?
                <a href="{{ route('register') }}" class="text-white hover:underline ms-1 font-semibold">Daftar Sekarang</a>
            </p>
            @endif
        </div>
    </div>
</div>
@endsection