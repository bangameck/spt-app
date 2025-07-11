@extends('layouts.app')

@section('title', 'Tambah Ruas Jalan Baru')

@section('content')
    <div class="container-fluid">
        <div class="flex justify-between items-center mb-6">
            <h4 class="text-default-900 text-2xl font-bold">Tambah Ruas Jalan Baru</h4>
            <a href="{{ route('masterdata.road-sections.index') }}"
                class="px-6 py-2 rounded-md text-primary-600 border border-primary-600 hover:bg-primary-600 hover:text-white transition-all">
                Kembali ke Daftar Ruas Jalan
            </a>
        </div>

        {{-- Error Messages dari Laravel Validation --}}
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Oops!</strong>
                <span class="block sm:inline">Ada beberapa masalah dengan input Anda.</span>
                <ul class="mt-3 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                    <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20">
                        <title>Close</title>
                        <path
                            d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.697l-2.651 2.652a1.2 1.2 0 1 1-1.697-1.697L8.303 10 5.651 7.348a1.2 1.2 0 1 1 1.697-1.697L10 8.303l2.651-2.652a1.2 1.2 0 0 1 1.697 1.697L11.697 10l2.651 2.651a1.2 1.2 0 0 1 0 1.698z" />
                    </svg>
                </span>
            </div>
        @endif

        <div class="card bg-white shadow rounded-lg p-6">
            <form action="{{ route('masterdata.road-sections.store') }}" method="POST">
                @csrf

                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-default-700 mb-2">Nama Ruas Jalan</label>
                    <input type="text" name="name" id="name"
                        class="form-input w-full px-4 py-2 border rounded-md text-default-800 focus:ring-primary-500 focus:border-primary-500 @error('name') border-red-500 @enderror"
                        value="{{ old('name') }}" placeholder="Contoh: Jl. Sudirman" required>
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <button type="submit"
                        class="px-6 py-2 rounded-md text-white bg-primary-600 hover:bg-primary-700 transition-all">
                        Simpan Ruas Jalan
                    </button>
                    <a href="{{ route('masterdata.road-sections.index') }}"
                        class="px-6 py-2 rounded-md text-default-600 border border-default-300 hover:bg-default-50 transition-all">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
