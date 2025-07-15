@extends('layouts.app')

@section('title', 'Tambah Rekening BLUD Baru')

@section('content')
    <div class="container-fluid">
        <div class="flex justify-between items-center mb-6">
            <h4 class="text-default-900 text-2xl font-bold">Tambah Rekening BLUD Baru</h4>
            <a href="{{ route('admin.blud-bank-accounts.index') }}"
                class="px-6 py-2 rounded-md text-primary-600 border border-primary-600 hover:bg-primary-600 hover:text-white transition-all">
                Kembali ke Daftar
            </a>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Oops!</strong>
                <ul class="mt-2 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card bg-white shadow rounded-lg p-6">
            <form action="{{ route('admin.blud-bank-accounts.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="bank_name" class="block text-sm font-medium text-default-700 mb-2">Nama Bank</label>
                        <input type="text" name="bank_name" id="bank_name" class="form-input w-full"
                            value="{{ old('bank_name') }}" placeholder="Contoh: Bank BRI" required>
                    </div>
                    <div>
                        <label for="account_number" class="block text-sm font-medium text-default-700 mb-2">Nomor
                            Rekening</label>
                        <input type="text" name="account_number" id="account_number" class="form-input w-full"
                            value="{{ old('account_number') }}" placeholder="Masukkan nomor rekening" required>
                    </div>
                    <div>
                        <label for="account_name" class="block text-sm font-medium text-default-700 mb-2">Atas Nama</label>
                        <input type="text" name="account_name" id="account_name" class="form-input w-full"
                            value="{{ old('account_name') }}" placeholder="Masukkan nama pemilik rekening" required>
                    </div>
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-default-700 mb-2">Tanggal Mulai
                            Efektif</label>
                        <input type="date" name="start_date" id="start_date" class="form-input w-full"
                            value="{{ old('start_date', date('Y-m-d')) }}" required>
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="submit"
                        class="px-6 py-2 rounded-md text-white bg-primary-600 hover:bg-primary-700">Simpan Rekening</button>
                    <a href="{{ route('admin.blud-bank-accounts.index') }}" class="px-6 py-2 rounded-md border">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
