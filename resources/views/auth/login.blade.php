@extends('layouts.guest')

@section('title', 'Login - Opatix') {{-- Judul halaman untuk tab browser --}}

@section('content')
<div class="md:flex hidden bg-cover bg-left bg-no-repeat w-full h-full absolute end-0 top-0">
    {{-- Jika template Opatix Anda memiliki background gambar untuk halaman login --}}
    {{-- Contoh: <img src="{{ asset('assets/images/background/auth-bg.jpg') }}" alt="Auth background" class="w-full h-full object-cover"> --}}
</div>

<div class="container relative">
    <div class="grid lg:grid-cols-2 grid-cols-1 gap-6">
        <div class="relative overflow-hidden flex-center h-full">
            {{-- Anda bisa menempatkan gambar atau teks branding di sini --}}
            <div class="text-center">
                <a href="{{ url('/') }}">
                    <img src="{{ asset('assets/images/logo-dark.png') }}" alt="logo" class="h-10 mx-auto">
                </a>
                <h5 class="text-default-700 text-xl font-bold mt-4">Sistem Informasi Perparkiran</h5>
                <p class="text-default-600 mt-2">Selamat datang kembali! Silakan login untuk melanjutkan.</p>
            </div>
        </div>

        <div class="flex-center">
            <div class="lg:w-[500px] w-full bg-white shadow-lg rounded-xl p-6">
                <h4 class="text-default-900 text-2xl font-bold text-center mb-6">Masuk ke Akun Anda</h4>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label for="email" class="text-default-800 text-base font-semibold block mb-2">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                            class="form-input w-full px-4 py-2 border border-default-200 rounded-md focus:border-primary-500 focus:ring-primary-500">
                        @error('email')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="text-default-800 text-base font-semibold block mb-2">Password</label>
                        <input type="password" id="password" name="password" required autocomplete="current-password"
                            class="form-input w-full px-4 py-2 border border-default-200 rounded-md focus:border-primary-500 focus:ring-primary-500">
                        @error('password')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input type="checkbox" name="remember" id="remember_me"
                                class="form-checkbox text-primary-600 rounded border-default-300 focus:ring-primary-500">
                            <label for="remember_me" class="ms-2 text-sm text-default-600">Ingat Saya</label>
                        </div>
                        @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm text-primary-600 hover:underline">Lupa Password?</a>
                        @endif
                    </div>

                    <div class="text-center">
                        <button type="submit"
                            class="w-full px-6 py-3 rounded-md text-white bg-primary-600 hover:bg-primary-700 transition-all">
                            Masuk
                        </button>
                    </div>
                </form>

                {{-- Link ke halaman register, jika masih diaktifkan --}}
                @if (Route::has('register'))
                <p class="text-center text-default-600 text-sm mt-6">Belum punya akun?
                    <a href="{{ route('register') }}" class="text-primary-600 hover:underline ms-1">Daftar Sekarang</a>
                </p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection