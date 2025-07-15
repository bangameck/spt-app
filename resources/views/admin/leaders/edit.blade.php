@extends('layouts.app')

@section('title', 'Edit Pimpinan: ' . $leader->user->name)

@section('content')
    {{-- Page Title & Breadcrumb --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Edit Pimpinan: {{ $leader->user->name }}</h4>
        <div class="d-flex align-items-center">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-style1 mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Admin</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.leaders.index') }}">Leaders</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </nav>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            <p class="mb-0"><strong>Oops! Terjadi beberapa kesalahan:</strong></p>
            <ul class="mt-2 mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.leaders.update', $leader->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        <div class="row g-6">
            <!-- Kolom Kiri: Detail User & Pimpinan -->
            <div class="col-lg-8">
                <div class="card mb-6">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Informasi Akun Login</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" class="form-control" id="name" name="name"
                                        placeholder="Masukkan Nama Lengkap" value="{{ old('name', $leader->user->name) }}"
                                        required />
                                    <label for="name">Nama Lengkap</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" class="form-control" id="username" name="username"
                                        placeholder="Username" value="{{ old('username', $leader->user->username) }}"
                                        required />
                                    <label for="username">Username</label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-floating form-floating-outline">
                                    <input type="email" class="form-control" id="email" name="email"
                                        placeholder="contoh@email.com" value="{{ old('email', $leader->user->email) }}"
                                        required />
                                    <label for="email">Email</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-password-toggle">
                                    <label class="form-label" for="password">Password Baru</label>
                                    <div class="input-group input-group-merge">
                                        <div class="form-floating form-floating-outline">
                                            <input type="password" id="password" class="form-control" name="password"
                                                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                                            <label for="password">Kosongkan jika tidak diubah</label>
                                        </div>
                                        <span class="input-group-text cursor-pointer"><i
                                                class="icon-base ri ri-eye-off-line"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-password-toggle">
                                    <label class="form-label" for="password_confirmation">Ulangi Password Baru</label>
                                    <div class="input-group input-group-merge">
                                        <div class="form-floating form-floating-outline">
                                            <input type="password" id="password_confirmation" class="form-control"
                                                name="password_confirmation"
                                                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                                            <label for="password_confirmation">Ulangi Password Baru</label>
                                        </div>
                                        <span class="input-group-text cursor-pointer"><i
                                                class="icon-base ri ri-eye-off-line"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Detail Pimpinan</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            <div class="col-md-12">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" class="form-control" id="employee_number" name="employee_number"
                                        placeholder="Masukkan NIP (18 digit)"
                                        value="{{ old('employee_number', $leader->employee_number) }}" maxlength="18"
                                        required />
                                    <label for="employee_number">NIP (Nomor Induk Pegawai)</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating form-floating-outline">
                                    <input type="date" class="form-control" id="start_date" name="start_date"
                                        value="{{ old('start_date', $leader->start_date->format('Y-m-d')) }}" required />
                                    <label for="start_date">Mulai Menjabat</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating form-floating-outline">
                                    <input type="date" class="form-control" id="end_date" name="end_date"
                                        value="{{ old('end_date', $leader->end_date ? $leader->end_date->format('Y-m-d') : '') }}" />
                                    <label for="end_date">Akhir Menjabat (Opsional)</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Foto Profil -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Foto Profil</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-column align-items-center">
                            @if ($leader->user && $leader->user->img && file_exists(public_path($leader->user->img)))
                                <img src="{{ asset($leader->user->img) }}" alt="user-avatar"
                                    class="d-block w-px-120 h-px-120 rounded-circle mb-4" id="uploadedAvatar" />
                            @else
                                <img src="{{ asset('assets/img/avatars/1.png') }}" alt="user-avatar"
                                    class="d-block w-px-120 h-px-120 rounded-circle mb-4" id="uploadedAvatar" />
                            @endif
                            <div class="button-wrapper">
                                <label for="img-upload" class="btn btn-primary me-3" tabindex="0">
                                    <span class="d-none d-sm-block">Ubah Foto</span>
                                    <i class="icon-base ri-upload-2-line d-sm-none"></i>
                                    <input type="file" id="img-upload" name="img" class="account-file-input"
                                        hidden accept="image/png, image/jpeg" />
                                </label>
                                <button type="button" class="btn btn-outline-secondary account-image-reset">
                                    <i class="icon-base ri-refresh-line d-block d-sm-none"></i>
                                    <span class="d-none d-sm-block">Reset</span>
                                </button>
                                <div id="file-error" class="mt-2 text-danger text-sm text-center"></div>
                                <p class="text-muted mt-3 mb-0 text-center">Hanya JPG/PNG. Akan dikompres di bawah 300KB.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="col-12 text-end mt-4">
                <a href="{{ route('admin.leaders.index') }}" class="btn btn-outline-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/browser-image-compression@2.0.1/dist/browser-image-compression.js"></script>
    <script>
        // Kode JavaScript yang sama persis dengan halaman create user
        // karena ID elemen dan fungsionalitasnya identik.
        document.addEventListener("DOMContentLoaded", function() {
            const usernameInput = document.getElementById('username');
            if (usernameInput) {
                usernameInput.addEventListener('input', e => {
                    e.target.value = e.target.value.toLowerCase().replace(/\s+/g, '').replace(/[^a-z0-9_]/g,
                        '');
                });
            }

            document.querySelectorAll('input[type="password"]').forEach(input => {
                if (input) {
                    input.addEventListener('input', e => {
                        e.target.value = e.target.value.replace(/\s/g, '');
                    });
                }
            });

            const employeeNumberInput = document.getElementById('employee_number');
            if (employeeNumberInput) {
                employeeNumberInput.addEventListener('input', function(e) {
                    e.target.value = e.target.value.replace(/[^0-9]/g, '').substring(0, 18);
                });
            }

            const fileInput = document.getElementById('img-upload');
            const uploadedAvatar = document.getElementById('uploadedAvatar');
            const resetButton = document.querySelector('.account-image-reset');
            const fileErrorDiv = document.getElementById('file-error');
            if (fileInput && uploadedAvatar && resetButton) {
                const defaultAvatar = uploadedAvatar.src;
                fileInput.addEventListener('change', async (e) => {
                    const imageFile = e.target.files[0];
                    if (!imageFile) return;
                    fileErrorDiv.textContent = '';
                    if (!['image/jpeg', 'image/png'].includes(imageFile.type)) {
                        fileErrorDiv.textContent = 'Hanya file JPG atau PNG.';
                        fileInput.value = '';
                        return;
                    }
                    const options = {
                        maxSizeMB: 0.3,
                        maxWidthOrHeight: 1024,
                        useWebWorker: true,
                        fileType: imageFile.type
                    }
                    try {
                        const compressedFile = await imageCompression(imageFile, options);
                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(new File([compressedFile], imageFile.name, {
                            type: compressedFile.type
                        }));
                        fileInput.files = dataTransfer.files;
                        uploadedAvatar.src = URL.createObjectURL(compressedFile);
                    } catch (error) {
                        fileErrorDiv.textContent = "Gagal mengkompres gambar.";
                        fileInput.value = '';
                    }
                });
                resetButton.addEventListener('click', () => {
                    uploadedAvatar.src = defaultAvatar;
                    fileInput.value = '';
                    fileErrorDiv.textContent = '';
                });
            }
        });
    </script>
@endpush
