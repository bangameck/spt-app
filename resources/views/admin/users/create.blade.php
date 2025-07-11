@extends('layouts.app')

@section('title', 'Tambah Pengguna Baru')

@section('content')
    <div class="container-fluid">
        <div class="flex justify-between items-center mb-6">
            <h4 class="text-default-900 text-2xl font-bold">Tambah Pengguna Baru</h4>
            <a href="{{ route('admin.users.index') }}"
                class="px-6 py-2 rounded-md text-default-600 bg-default-100 hover:bg-default-200 transition-all">
                Kembali ke Daftar Pengguna
            </a>
        </div>

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
            <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-default-700 mb-2">Nama Lengkap</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}"
                            class="form-input w-full px-4 py-2 border border-default-200 rounded-md focus:border-primary-500 focus:ring-primary-500 @error('name') border-red-500 @enderror"
                            required autofocus>
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="username" class="block text-sm font-medium text-default-700 mb-2">Username</label>
                        <input type="text" id="username" name="username" value="{{ old('username') }}"
                            class="form-input w-full px-4 py-2 border border-default-200 rounded-md focus:border-primary-500 focus:ring-primary-500 @error('username') border-red-500 @enderror"
                            required>
                        @error('username')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                    <div>
                        <label for="email" class="block text-sm font-medium text-default-700 mb-2">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                            class="form-input w-full px-4 py-2 border border-default-200 rounded-md focus:border-primary-500 focus:ring-primary-500 @error('email') border-red-500 @enderror"
                            required>
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="role" class="block text-sm font-medium text-default-700 mb-2">Role</label>
                        <select id="role" name="role"
                            class="form-select w-full px-4 py-2 border border-default-200 rounded-md focus:border-primary-500 focus:ring-primary-500 @error('role') border-red-500 @enderror"
                            required>
                            <option value="">Pilih Role</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role }}" {{ old('role') == $role ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('_', ' ', $role)) }}
                                </option>
                            @endforeach
                        </select>
                        @error('role')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                    <div>
                        <label for="password" class="block text-sm font-medium text-default-700 mb-2">
                            Password @if (request()->routeIs('admin.users.edit'))
                                (Kosongkan jika tidak ingin mengubah)
                            @endif
                        </label>
                        <div class="relative"> {{-- Tambahkan div wrapper untuk positioning toggle --}}
                            <input type="password" id="password" name="password"
                                class="form-input w-full pr-10 px-4 py-2 border border-default-200 rounded-md focus:border-primary-500 focus:ring-primary-500 @error('password') border-red-500 @enderror"
                                @if (!request()->routeIs('admin.users.edit')) required @endif> {{-- 'required' hanya untuk create --}}
                            <span class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer"
                                id="togglePassword">
                                {{-- Eye icon (closed) --}}
                                <svg class="h-5 w-5 text-default-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.418 0-8-3.582-8-8 0-1.391.354-2.709.975-3.875m3.75 3.75a3 3 0 114.242 4.242M21 12c-1.391-.354-2.709-.975-3.875-1.925M3 12c.354 1.391.975 2.709 1.925 3.875m-3.75-3.75a3 3 0 104.242-4.242M1.925 18.825A10.05 10.05 0 0112 19c4.418 0 8-3.582 8-8 0-1.391-.354-2.709-.975-3.875M12 4v.01" />
                                </svg>
                            </span>
                        </div>
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation"
                            class="block text-sm font-medium text-default-700 mb-2">Konfirmasi Password</label>
                        <div class="relative"> {{-- Tambahkan div wrapper untuk positioning toggle --}}
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                class="form-input w-full pr-10 px-4 py-2 border border-default-200 rounded-md focus:border-primary-500 focus:ring-primary-500"
                                @if (!request()->routeIs('admin.users.edit')) required @endif> {{-- 'required' hanya untuk create --}}
                            <span class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer"
                                id="togglePasswordConfirmation">
                                {{-- Eye icon (closed) --}}
                                <svg class="h-5 w-5 text-default-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.418 0-8-3.582-8-8 0-1.391.354-2.709.975-3.875m3.75 3.75a3 3 0 114.242 4.242M21 12c-1.391-.354-2.709-.975-3.875-1.925M3 12c.354 1.391.975 2.709 1.925 3.875m-3.75-3.75a3 3 0 104.242-4.242M1.925 18.825A10.05 10.05 0 0112 19c4.418 0 8-3.582 8-8 0-1.391-.354-2.709-.975-3.875M12 4v.01" />
                                </svg>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="mb-6">
                    <label for="img" class="block text-sm font-medium text-default-700 mb-2">Gambar Profil (Drag & Drop
                        atau Klik)</label>

                    <div id="drop-area"
                        class="border-2 border-dashed border-default-300 rounded-lg p-6 text-center cursor-pointer hover:border-primary-500 transition-all">
                        <input type="file" id="img-upload" name="img" accept="image/*" class="hidden">
                        <p class="text-default-500 mb-2">Seret dan lepas gambar di sini, atau</p>
                        <button type="button" id="select-file-btn"
                            class="px-4 py-2 rounded-md bg-primary-100 text-primary-700 hover:bg-primary-200">
                            Pilih File
                        </button>

                        {{-- Elemen untuk menampilkan informasi file --}}
                        <div id="file-info" class="mt-4 text-default-600 text-sm hidden">
                            <p><strong>Nama File:</strong> <span id="file-name"></span></p>
                            <p><strong>Ukuran File:</strong> <span id="file-size"></span></p>
                        </div>
                        {{-- Elemen untuk menampilkan pesan error validasi ukuran file --}}
                        <div id="file-error" class="mt-2 text-red-600 text-sm font-medium hidden"></div>

                        {{-- Elemen untuk menampilkan preview gambar --}}
                        <div id="image-preview" class="mt-4 flex justify-center items-center">
                            {{-- Placeholder jika tidak ada gambar (atau di form create) --}}
                            {{-- Di form edit, Anda bisa menampilkan gambar lama di sini --}}
                            @if (isset($user) && $user->img)
                                <img src="{{ asset($user->img) }}" class="max-w-full h-32 object-contain rounded-md"
                                    alt="Gambar Profil Saat Ini">
                            @endif
                        </div>

                        {{-- Pesan error dari Laravel Validation --}}
                        @error('img')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                        class="px-6 py-2 rounded-md text-white bg-primary-600 hover:bg-primary-700 transition-all">
                        Simpan Pengguna
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const dropArea = document.getElementById('drop-area');
        const fileInput = document.getElementById('img-upload');
        const selectFileBtn = document.getElementById('select-file-btn');
        const imagePreview = document.getElementById('image-preview');
        const fileInfoDiv = document.getElementById('file-info'); // New element
        const fileNameSpan = document.getElementById('file-name'); // New element
        const fileSizeSpan = document.getElementById('file-size'); // New element
        const fileErrorDiv = document.getElementById('file-error'); // New element

        // Mencegah perilaku default browser untuk drag
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, preventDefaults, false);
            document.body.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        // Highlight area saat file di-drag di atasnya
        ['dragenter', 'dragover'].forEach(eventName => {
            dropArea.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, unhighlight, false);
        });

        function highlight(e) {
            dropArea.classList.add('border-primary-600', 'bg-primary-50');
        }

        function unhighlight(e) {
            dropArea.classList.remove('border-primary-600', 'bg-primary-50');
        }

        // Handle file drop
        dropArea.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            let dt = e.dataTransfer;
            let files = dt.files;
            handleFiles(files);
        }

        // Handle file selection via button click
        selectFileBtn.addEventListener('click', () => {
            fileInput.click();
        });

        fileInput.addEventListener('change', (e) => {
            handleFiles(e.target.files);
        });

        function handleFiles(files) {
            // Hapus info dan error sebelumnya
            fileInfoDiv.classList.add('hidden');
            fileErrorDiv.classList.add('hidden');
            imagePreview.innerHTML = ''; // Hapus preview gambar yang sudah ada

            if (files.length === 0) {
                // Jika tidak ada file yang dipilih (misal, jendela dialog dibatalkan)
                fileInput.value = ''; // Pastikan input file sebenarnya kosong
                return;
            }

            const file = files[0]; // Ambil file pertama saja (karena ini untuk satu gambar profil)
            const MAX_SIZE_MB = 2; // Ukuran maksimum yang diizinkan dalam MB
            const MAX_SIZE_BYTES = MAX_SIZE_MB * 1024 * 1024; // Konversi ke bytes

            // Validasi sisi klien: Periksa tipe file (opsional tapi praktik yang baik)
            // Walaupun ada accept="image/*", ini menambah lapisan validasi
            if (!file.type.startsWith('image/')) {
                fileErrorDiv.textContent = 'File yang dipilih harus berupa gambar (JPEG, PNG, GIF, SVG).';
                fileErrorDiv.classList.remove('hidden');
                fileInput.value = ''; // Kosongkan input file sebenarnya
                return;
            }

            // Validasi sisi klien: Periksa ukuran file
            if (file.size > MAX_SIZE_BYTES) {
                fileErrorDiv.textContent =
                    `Ukuran file harus dibawah ${MAX_SIZE_MB} MB. (Ukuran saat ini: ${formatBytes(file.size)})`;
                fileErrorDiv.classList.remove('hidden');
                fileInput.value = ''; // Kosongkan input file sebenarnya jika terlalu besar
                return;
            }

            // Tampilkan informasi file
            fileNameSpan.textContent = file.name;
            fileSizeSpan.textContent = formatBytes(file.size);
            fileInfoDiv.classList.remove('hidden'); // Tampilkan div info

            // Set file ke input file yang sebenarnya
            // Ini penting agar file terkirim saat form di-submit
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            fileInput.files = dataTransfer.files;

            // Tampilkan preview gambar
            previewFile(file);
        }

        // Fungsi helper untuk memformat ukuran byte ke format yang mudah dibaca
        function formatBytes(bytes, decimals = 2) {
            if (bytes === 0) return '0 Bytes';

            const k = 1024;
            const dm = decimals < 0 ? 0 : decimals;
            const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];

            const i = Math.floor(Math.log(bytes) / Math.log(k));

            return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
        }

        function previewFile(file) {
            let reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onloadend = function() {
                imagePreview.innerHTML =
                    `<img src="${reader.result}" class="max-w-full h-32 object-contain rounded-md" alt="Preview Gambar">`;
            }
        }

        // === START: Validasi dan Transformasi Input & Toggle Password Visibility ===
        // Deklarasikan variabel-variabel ini HANYA SEKALI di bagian atas script atau di sini
        const usernameInput = document.getElementById('username');
        const passwordInput = document.getElementById('password');
        const passwordConfirmationInput = document.getElementById('password_confirmation');
        const togglePassword = document.getElementById('togglePassword');
        const togglePasswordConfirmation = document.getElementById('togglePasswordConfirmation');


        // Untuk input Username
        if (usernameInput) {
            usernameInput.addEventListener('input', function(e) {
                let value = e.target.value;
                // 1. Ubah ke huruf kecil
                value = value.toLowerCase();
                // 2. Hapus spasi (jika ada)
                value = value.replace(/\s/g, '');
                // 3. Hanya izinkan huruf (a-z), angka (0-9), underscore (_), dan hyphen (-)
                // Karakter lain akan dihapus
                value = value.replace(/[^a-z0-9_-]/g, '');
                e.target.value = value;
            });
        }

        // Untuk input Password
        if (passwordInput) {
            passwordInput.addEventListener('input', function(e) {
                let value = e.target.value;
                // Hapus spasi
                value = value.replace(/\s/g, '');
                e.target.value = value;
            });
        }

        // Untuk input Konfirmasi Password (juga tidak boleh ada spasi)
        if (passwordConfirmationInput) {
            passwordConfirmationInput.addEventListener('input', function(e) {
                let value = e.target.value;
                // Hapus spasi
                value = value.replace(/\s/g, '');
                e.target.value = value;
            });
        }

        // Fungsi untuk setup toggle password
        function setupPasswordToggle(inputElement, toggleElement) {
            if (inputElement && toggleElement) {
                toggleElement.addEventListener('click', function() {
                    const type = inputElement.getAttribute('type') === 'password' ? 'text' : 'password';
                    inputElement.setAttribute('type', type);

                    // Toggle eye icon
                    if (type === 'password') {
                        // Eye closed icon
                        toggleElement.innerHTML = `
                        <svg class="h-5 w-5 text-default-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.418 0-8-3.582-8-8 0-1.391.354-2.709.975-3.875m3.75 3.75a3 3 0 114.242 4.242M21 12c-1.391-.354-2.709-.975-3.875-1.925M3 12c.354 1.391.975 2.709 1.925 3.875m-3.75-3.75a3 3 0 104.242-4.242M1.925 18.825A10.05 10.05 0 0112 19c4.418 0 8-3.582 8-8 0-1.391-.354-2.709-.975-3.875M12 4v.01"/>
                        </svg>
                    `;
                    } else {
                        // Eye open icon
                        toggleElement.innerHTML = `
                        <svg class="h-5 w-5 text-default-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    `;
                    }
                });
            }
        }

        // Panggil fungsi setup untuk kedua field password
        setupPasswordToggle(passwordInput, togglePassword);
        setupPasswordToggle(passwordConfirmationInput, togglePasswordConfirmation);
        // === END: Validasi dan Transformasi Input & Toggle Password Visibility ===
    </script>
@endpush
