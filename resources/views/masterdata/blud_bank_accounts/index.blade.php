@extends('layouts.app')

@section('title', 'Manajemen Rekening BLUD')

@section('content')
    <div class="container-fluid">
        <div class="flex justify-between items-center mb-6">
            <h4 class="text-default-900 text-2xl font-bold">Manajemen Rekening BLUD</h4>
            <a href="{{ route('admin.blud-bank-accounts.create') }}"
                class="px-6 py-2 rounded-md text-white bg-primary-600 hover:bg-primary-700 transition-all">
                <i class="i-lucide-plus-circle me-2"></i> Tambah Rekening
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <div class="card bg-white shadow rounded-lg">
            <div class="p-6">
                <div class="relative overflow-x-auto">
                    <table class="w-full text-sm text-left text-default-500">
                        <thead class="text-xs text-default-700 uppercase bg-default-50">
                            <tr>
                                <th scope="col" class="px-6 py-3">Nama Bank</th>
                                <th scope="col" class="px-6 py-3">Nomor Rekening</th>
                                <th scope="col" class="px-6 py-3">Atas Nama</th>
                                <th scope="col" class="px-6 py-3">Tanggal Mulai</th>
                                <th scope="col" class="px-6 py-3">Status</th>
                                <th scope="col" class="px-6 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($accounts as $account)
                                <tr class="bg-white border-b hover:bg-default-50">
                                    <td class="px-6 py-4 font-medium text-default-900">{{ $account->bank_name }}</td>
                                    <td class="px-6 py-4">{{ $account->account_number }}</td>
                                    <td class="px-6 py-4">{{ $account->account_name }}</td>
                                    <td class="px-6 py-4">{{ $account->start_date->format('d M Y') }}</td>
                                    <td class="px-6 py-4">
                                        @if ($account->is_active)
                                            <span
                                                class="px-2 py-1 font-semibold text-xs leading-tight text-green-700 bg-green-100 rounded-full">Aktif</span>
                                        @else
                                            <span
                                                class="px-2 py-1 font-semibold text-xs leading-tight text-red-700 bg-red-100 rounded-full">Tidak
                                                Aktif</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <a href="{{ route('admin.blud-bank-accounts.edit', $account->id) }}"
                                            class="font-medium text-primary-600 hover:underline">Edit</a>
                                        <form action="{{ route('admin.blud-bank-accounts.destroy', $account->id) }}"
                                            method="POST" class="inline-block"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus rekening ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="font-medium text-red-600 hover:underline ms-3">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr class="bg-white border-b">
                                    <td colspan="6" class="px-6 py-4 text-center text-default-500">Tidak ada data
                                        rekening.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-6">
                    {{ $accounts->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
