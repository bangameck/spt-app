@extends('layouts.app')

@section('title', 'Tambah Pimpinan Baru')

@section('content')
<div class="container-fluid">
    <div class="flex justify-between items-center mb-6">
        <h4 class="text-default-900 text-2xl font-bold">Tambah Pimpinan Baru</h4>
        <a href="{{ route('admin.leaders.index') }}"
            class="px-6 py-2 rounded-md text-primary-600 border border-primary-600 hover:bg-primary-600 hover:text-white transition-all">
            Kembali ke Daftar Pimpinan
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
            <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="[http://www.w3.org/2000/svg](http://www.w3.org/2000/svg)"
                viewBox="0 0 20 20">
                <title>Close</title>
                <path
                    d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.697l-2.651 2.652a1.2 1.2 0 1 1-1.697-1.697L8.303 10 5.651 7.348a1.2 1.2 0 1 1 1.697-1.697L10 8.303l2.651-2.652a1.2 1.2 0 0 1 1.697 1.697L11.697 10l2.651 2.651a1.2 1.2 0 0 1 0 1.698z" />
            </svg>
        </span>
    </div>
    @endif

    <div class="card bg-white shadow rounded-lg p-6">
        <form action="{{ route('admin.leaders.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <h5 class="text-lg font-semibold text-default-800 mb-4">Informasi Akun Pimpinan (Data User)</h5>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-default-700 mb-2">Nama Pimpinan Lengkap</label>
                    <input type="text" name="name" id="name"
                        class="form-input w-full px-4 py-2 border rounded-md text-default-800 focus:ring-primary-500 focus:border-primary-500 @error('name') border-red-500 @enderror"
                        value="{{ old('name') }}" placeholder="Masukkan nama lengkap" required>
                    @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="username" class="block text-sm font-medium text-default-700 mb-2">Username</label>
                    <input type="text" name="username" id="username"
                        class="form-input w-full px-4 py-2 border rounded-md text-default-800 focus:ring-primary-500 focus:border-primary-500 @error('username') border-red-500 @enderror"
                        value="{{ old('username') }}" placeholder="Masukkan username" required>
                    @error('username')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-default-700 mb-2">Email</label>
                    <input type="email" name="email" id="email"
                        class="form-input w-full px-4 py-2 border rounded-md text-default-800 focus:ring-primary-500 focus:border-primary-500 @error('email') border-red-500 @enderror"
                        value="{{ old('email') }}" placeholder="Masukkan alamat email" required>
                    @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                {{-- Field password dan konfirmasi password --}}
                <div class="relative">
                    <label for="password" class="block text-sm font-medium text-default-700 mb-2">Password</label>
                    <input type="password" name="password" id="password"
                        class="form-input w-full pr-10 px-4 py-2 border rounded-md text-default-800 focus:ring-primary-500 focus:border-primary-500 @error('password') border-red-500 @enderror"
                        placeholder="Minimal 8 karakter" required>
                    <span class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer top-7" id="togglePassword">
                        <svg class="h-5 w-5 text-default-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.418 0-8-3.582-8-8 0-1.391.354-2.709.975-3.875m3.75 3.75a3 3 0 114.242 4.242M21 12c-1.391-.354-2.709-.975-3.875-1.925M3 12c.354 1.391.975 2.709 1.925 3.875m-3.75-3.75a3 3 0 104.242-4.242M1.925 18.825A10.05 10.05 0 0112 19c4.418 0 8-3.582 8-8 0-1.391-.354-2.709-.975-3.875M12 4v.01" />
                        </svg>
                    </span>
                    @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="relative">
                    <label for="password_confirmation" class="block text-sm font-medium text-default-700 mb-2">Ulangi Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        class="form-input w-full pr-10 px-4 py-2 border rounded-md text-default-800 focus:ring-primary-500 focus:border-primary-500 @error('password_confirmation') border-red-500 @enderror"
                        placeholder="Ketik ulang password" required>
                    <span class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer top-7" id="togglePasswordConfirmation">
                        <svg class="h-5 w-5 text-default-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.418 0-8-3.582-8-8 0-1.391.354-2.709.975-3.875m3.75 3.75a3 3 0 114.242 4.242M21 12c-1.391-.354-2.709-.975-3.875-1.925M3 12c.354 1.391.975 2.709 1.925 3.875m-3.75-3.75a3 3 0 104.242-4.242M1.925 18.825A10.05 10.05 0 0112 19c4.418 0 8-3.582 8-8 0-1.391-.354-2.709-.975-3.875M12 4v.01" />
                        </svg>
                    </span>
                    @error('password_confirmation')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Bagian Upload Gambar (Foto Profil) --}}
            <div class="mb-6">
                <label for="img" class="block text-sm font-medium text-default-700 mb-2">Foto Profil (Drag & Drop atau Klik)</label>
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
                        {{-- Gambar preview akan muncul di sini --}}
                    </div>

                    {{-- Pesan error dari Laravel Validation --}}
                    @error('img')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <hr class="my-6 border-default-200">

            <h5 class="text-lg font-semibold text-default-800 mb-4">Detail Pimpinan (Data Leader)</h5>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="employee_number" class="block text-sm font-medium text-default-700 mb-2">NIP (Nomor Induk Pegawai)</label>
                    <input type="text" name="employee_number" id="employee_number" maxlength="18" {{-- Maxlength 18 digit --}}
                        class="form-input w-full px-4 py-2 border rounded-md text-default-800 focus:ring-primary-500 focus:border-primary-500 @error('employee_number') border-red-500 @enderror"
                        value="{{ old('employee_number') }}" placeholder="Masukkan NIP (maks 18 digit)" required>
                    @error('employee_number')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="start_date" class="block text-sm font-medium text-default-700 mb-2">Mulai Menjabat</label>
                    <input type="date" name="start_date" id="start_date"
                        class="form-input w-full px-4 py-2 border rounded-md text-default-800 focus:ring-primary-500 focus:border-primary-500 @error('start_date') border-red-500 @enderror"
                        value="{{ old('start_date') }}" required>
                    @error('start_date')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="end_date" class="block text-sm font-medium text-default-700 mb-2">Akhir Menjabat (Opsional)</label>
                    <input type="date" name="end_date" id="end_date"
                        class="form-input w-full px-4 py-2 border rounded-md text-default-800 focus:ring-primary-500 focus:border-primary-500 @error('end_date') border-red-500 @enderror"
                        value="{{ old('end_date') }}">
                    @error('end_date')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <button type="submit"
                    class="px-6 py-2 rounded-md text-white bg-primary-600 hover:bg-primary-700 transition-all">
                    Simpan Pimpinan
                </button>
                <a href="{{ route('admin.leaders.index') }}"
                    class="px-6 py-2 rounded-md text-default-600 border border-default-300 hover:bg-default-50 transition-all">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // === START: Image Upload (Drag & Drop, Preview, Compression) ===
        const dropArea = document.getElementById('drop-area');
        const fileInput = document.getElementById('img-upload');
        const selectFileBtn = document.getElementById('select-file-btn');
        const imagePreview = document.getElementById('image-preview');
        const fileInfoDiv = document.getElementById('file-info');
        const fileNameSpan = document.getElementById('file-name');
        const fileSizeSpan = document.getElementById('file-size');
        const fileErrorDiv = document.getElementById('file-error');

        const MAX_FILE_SIZE_KB = 300; // Ukuran maksimum yang diizinkan dalam KB
        const MAX_FILE_SIZE_BYTES = MAX_FILE_SIZE_KB * 1024; // Konversi ke bytes

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

        async function handleFiles(files) {
            // Hapus info dan error sebelumnya
            fileInfoDiv.classList.add('hidden');
            fileErrorDiv.classList.add('hidden');
            imagePreview.innerHTML = ''; // Hapus preview gambar yang sudah ada

            if (files.length === 0) {
                fileInput.value = ''; // Pastikan input file sebenarnya kosong
                return;
            }

            const originalFile = files[0];

            // Validasi sisi klien: Periksa tipe file
            if (!originalFile.type.startsWith('image/')) {
                fileErrorDiv.textContent = 'File yang dipilih harus berupa gambar (JPEG, PNG, GIF, SVG).';
                fileErrorDiv.classList.remove('hidden');
                fileInput.value = '';
                return;
            }

            // Tampilkan loading indicator
            imagePreview.innerHTML = '<div class="text-default-500">Memproses gambar...</div>';

            try {
                const compressedFile = await compressImage(originalFile, MAX_FILE_SIZE_BYTES);

                // Jika kompresi berhasil dan ukuran sesuai
                if (compressedFile.size <= MAX_FILE_SIZE_BYTES) {
                    // Tampilkan informasi file yang sudah dikompresi
                    fileNameSpan.textContent = compressedFile.name;
                    fileSizeSpan.textContent = formatBytes(compressedFile.size);
                    fileInfoDiv.classList.remove('hidden');

                    // Set file yang sudah dikompresi ke input file yang sebenarnya
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(compressedFile);
                    fileInput.files = dataTransfer.files;

                    // Tampilkan preview gambar yang sudah dikompresi
                    previewFile(compressedFile);
                } else {
                    // Jika setelah kompresi masih terlalu besar
                    fileErrorDiv.textContent = `Ukuran file masih terlalu besar (${formatBytes(compressedFile.size)}), harus dibawah ${MAX_FILE_SIZE_KB} KB.`;
                    fileErrorDiv.classList.remove('hidden');
                    fileInput.value = ''; // Kosongkan input file
                    imagePreview.innerHTML = ''; // Hapus loading indicator
                }
            } catch (error) {
                console.error('Error during image processing:', error);
                fileErrorDiv.textContent = 'Gagal memproses gambar. Pastikan format valid.';
                fileErrorDiv.classList.remove('hidden');
                fileInput.value = '';
                imagePreview.innerHTML = '';
            }
        }

        // Fungsi untuk kompresi gambar
        async function compressImage(file, maxSizeInBytes) {
            return new Promise((resolve, reject) => {
                const reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onload = event => {
                    const img = new Image();
                    img.src = event.target.result;
                    img.onload = () => {
                        const canvas = document.createElement('canvas');
                        const ctx = canvas.getContext('2d');

                        // Atur dimensi awal
                        let width = img.width;
                        let height = img.height;

                        // Jika gambar terlalu besar, resize dulu
                        const MAX_DIMENSION = 1000; // Batasi dimensi maksimal (misal 1000px)
                        if (width > MAX_DIMENSION || height > MAX_DIMENSION) {
                            if (width > height) {
                                height *= MAX_DIMENSION / width;
                                width = MAX_DIMENSION;
                            } else {
                                width *= MAX_DIMENSION / height;
                                height = MAX_DIMENSION;
                            }
                        }

                        canvas.width = width;
                        canvas.height = height;
                        ctx.drawImage(img, 0, 0, width, height);

                        let quality = 0.8; // Kualitas awal JPEG
                        let compressedDataUrl = canvas.toDataURL('image/jpeg', quality);
                        let compressedBlob = dataURLtoBlob(compressedDataUrl);

                        // Loop untuk mengurangi kualitas sampai ukuran sesuai atau kualitas terlalu rendah
                        while (compressedBlob.size > maxSizeInBytes && quality > 0.1) {
                            quality -= 0.1;
                            compressedDataUrl = canvas.toDataURL('image/jpeg', quality);
                            compressedBlob = dataURLtoBlob(compressedDataUrl);
                        }

                        // Jika masih terlalu besar setelah kompresi maksimal, coba resize lebih agresif
                        if (compressedBlob.size > maxSizeInBytes) {
                            // Coba resize ke 50% dan kompres lagi
                            canvas.width = width * 0.5;
                            canvas.height = height * 0.5;
                            ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
                            quality = 0.8; // Reset quality for new resize
                            compressedDataUrl = canvas.toDataURL('image/jpeg', quality);
                            compressedBlob = dataURLtoBlob(compressedDataUrl);

                            while (compressedBlob.size > maxSizeInBytes && quality > 0.1) {
                                quality -= 0.1;
                                compressedDataUrl = canvas.toDataURL('image/jpeg', quality);
                                compressedBlob = dataURLtoBlob(compressedDataUrl);
                            }
                        }


                        // Buat File baru dari Blob yang dikompresi
                        const compressedFile = new File([compressedBlob], file.name, {
                            type: 'image/jpeg',
                            lastModified: Date.now()
                        });
                        resolve(compressedFile);
                    };
                    img.onerror = error => reject(error);
                };
                reader.onerror = error => reject(error);
            });
        }

        // Fungsi helper untuk mengkonversi Data URL ke Blob
        function dataURLtoBlob(dataurl) {
            let arr = dataurl.split(','),
                mime = arr[0].match(/:(.*?);/)[1],
                bstr = atob(arr[1]),
                n = bstr.length,
                u8arr = new Uint8Array(n);
            while (n--) {
                u8arr[n] = bstr.charCodeAt(n);
            }
            return new Blob([u8arr], {
                type: mime
            });
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
                imagePreview.innerHTML = ''; // Pastikan preview lama terhapus
                let imgElement = document.createElement('img');
                imgElement.src = reader.result;
                imgElement.classList.add('max-w-full', 'h-32', 'object-contain', 'rounded-md');
                imgElement.alt = 'Preview Gambar';
                imagePreview.appendChild(imgElement);
            }
        }
        // === END: Image Upload ===

        // === START: Validasi dan Transformasi Input (Username, Password, NIP) ===
        const usernameInput = document.getElementById('username');
        const passwordInput = document.getElementById('password');
        const passwordConfirmationInput = document.getElementById('password_confirmation');
        const employeeNumberInput = document.getElementById('employee_number'); // NIP input

        // Untuk input Username
        if (usernameInput) {
            usernameInput.addEventListener('input', function(e) {
                let value = e.target.value;
                value = value.toLowerCase(); // Ubah ke huruf kecil
                value = value.replace(/\s/g, ''); // Hapus spasi
                value = value.replace(/[^a-z0-9_-]/g, ''); // Hanya izinkan a-z, 0-9, _, -
                e.target.value = value;
            });
        }

        // Untuk input Password dan Konfirmasi Password (hapus spasi)
        if (passwordInput) {
            passwordInput.addEventListener('input', function(e) {
                e.target.value = e.target.value.replace(/\s/g, '');
            });
        }
        if (passwordConfirmationInput) {
            passwordConfirmationInput.addEventListener('input', function(e) {
                e.target.value = e.target.value.replace(/\s/g, '');
            });
        }

        // Untuk input NIP (maksimal 18 digit, hanya angka)
        if (employeeNumberInput) {
            employeeNumberInput.addEventListener('input', function(e) {
                let value = e.target.value;
                value = value.replace(/[^0-9]/g, ''); // Hanya izinkan angka
                if (value.length > 18) { // Batasi panjang
                    value = value.substring(0, 18);
                }
                e.target.value = value;
            });
        }
        // === END: Validasi dan Transformasi Input ===

        // === START: Toggle Password Visibility ===
        // Variabel sudah dideklarasikan di bagian "Validasi dan Transformasi Input"
        // const passwordInput = document.getElementById('password'); // Tidak perlu deklarasi ulang
        // const passwordConfirmationInput = document.getElementById('password_confirmation'); // Tidak perlu deklarasi ulang
        const togglePassword = document.getElementById('togglePassword');
        const togglePasswordConfirmation = document.getElementById('togglePasswordConfirmation');

        function setupPasswordToggle(inputElement, toggleElement) {
            if (inputElement && toggleElement) {
                toggleElement.addEventListener('click', function() {
                    const type = inputElement.getAttribute('type') === 'password' ? 'text' : 'password';
                    inputElement.setAttribute('type', type);

                    // Toggle eye icon
                    const icon = this.querySelector('svg');
                    const eyeClosedPath = "M13.875 18.825A10.05 10.05 0 0112 19c-4.418 0-8-3.582-8-8 0-1.391.354-2.709.975-3.875m3.75 3.75a3 3 0 114.242 4.242M21 12c-1.391-.354-2.709-.975-3.875-1.925M3 12c.354 1.391.975 2.709 1.925 3.875m-3.75-3.75a3 3 0 104.242-4.242M1.925 18.825A10.05 10.05 0 0112 19c4.418 0 8-3.582 8-8 0-1.391-.354-2.709-.975-3.875M12 4v.01";
                    const eyeOpenPath = "M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z";

                    if (type === 'password') {
                        icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${eyeClosedPath}"/>`;
                    } else {
                        icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${eyeOpenPath}"/>`;
                    }
                });
            }
        }

        // Panggil fungsi setup untuk kedua field password
        // PASTIKAN MENGGUNAKAN passwordInput dan passwordConfirmationInput
        setupPasswordToggle(passwordInput, togglePassword);
        setupPasswordToggle(passwordConfirmationInput, togglePasswordConfirmation);
        // === END: Toggle Password Visibility ===

        // SweetAlert2 Success Message AKAN DITAMPILKAN DI index.blade.php
    });
</script>
@endpush