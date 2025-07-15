@extends('layouts.app')

@section('title', 'Edit Rekening BLUD')

@section('content')
    <div class="container-fluid">
        <div class="flex justify-between items-center mb-6">
            <h4 class="text-default-900 text-2xl font-bold">Edit Rekening BLUD</h4>
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
            <form action="{{ route('admin.blud-bank-accounts.update', $account->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="bank_name" class="block text-sm font-medium text-default-700 mb-2">Nama Bank</label>
                        <input type="text" name="bank_name" id="bank_name" class="form-input w-full"
                            value="{{ old('bank_name', $account->bank_name) }}" required>
                    </div>
                    <div>
                        <label for="account_number" class="block text-sm font-medium text-default-700 mb-2">Nomor
                            Rekening</label>
                        <input type="text" name="account_number" id="account_number" class="form-input w-full"
                            value="{{ old('account_number', $account->account_number) }}" required>
                    </div>
                    <div>
                        <label for="account_name" class="block text-sm font-medium text-default-700 mb-2">Atas Nama</label>
                        <input type="text" name="account_name" id="account_name" class="form-input w-full"
                            value="{{ old('account_name', $account->account_name) }}" required>
                    </div>
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-default-700 mb-2">Tanggal Mulai
                            Efektif</label>
                        <input type="date" name="start_date" id="start_date" class="form-input w-full"
                            value="{{ old('start_date', $account->start_date->format('Y-m-d')) }}" required>
                    </div>
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-default-700 mb-2">Tanggal Berakhir
                            (Opsional)</label>
                        <input type="date" name="end_date" id="end_date" class="form-input w-full"
                            value="{{ old('end_date', $account->end_date ? $account->end_date->format('Y-m-d') : '') }}">
                    </div>
                    <div>
                        <label for="is_active" class="block text-sm font-medium text-default-700 mb-2">Status</label>
                        <select name="is_active" id="is_active" class="form-select w-full">
                            <option value="1" {{ old('is_active', $account->is_active) == 1 ? 'selected' : '' }}>Aktif
                            </option>
                            <option value="0" {{ old('is_active', $account->is_active) == 0 ? 'selected' : '' }}>Tidak
                                Aktif</option>
                        </select>
                        <small class="text-xs text-amber-600">Mengaktifkan rekening ini akan menonaktifkan rekening aktif
                            lainnya.</small>
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="submit"
                        class="px-6 py-2 rounded-md text-white bg-primary-600 hover:bg-primary-700">Simpan
                        Perubahan</button>
                    <a href="{{ route('admin.blud-bank-accounts.index') }}" class="px-6 py-2 rounded-md border">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
