    @extends('layouts.app')

    @section('title', 'Tambah Field Coordinator Baru')

    @section('content')
        <div class="container-fluid">
            <div class="flex justify-between items-center mb-6">
                <h4 class="text-default-900 text-2xl font-bold">Tambah Field Coordinator Baru</h4>
                <a href="{{ route('admin.field-coordinators.index') }}"
                    class="px-6 py-2 rounded-md text-primary-600 border border-primary-600 hover:bg-primary-600 hover:text-white transition-all">
                    Kembali ke Daftar Field Coordinator
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
                <form action="{{ route('admin.field-coordinators.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <h5 class="text-lg font-semibold text-default-800 mb-4">Informasi Akun Field Coordinator (Data User)
                    </h5>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-default-700 mb-2">Nama
                                Lengkap</label>
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
                            <span class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer top-7"
                                id="togglePassword">
                                <svg class="h-5 w-5 text-default-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.418 0-8-3.582-8-8 0-1.391.354-2.709.975-3.875m3.75 3.75a3 3 0 114.242 4.242M21 12c-1.391-.354-2.709-.975-3.875-1.925M3 12c.354 1.391.975 2.709 1.925 3.875m-3.75-3.75a3 3 0 104.242-4.242M1.925 18.825A10.05 10.05 0 0112 19c4.418 0 8-3.582 8-8 0-1.391-.354-2.709-.975-3.875M12 4v.01" />
                                </svg>
                            </span>
                            @error('password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="relative">
                            <label for="password_confirmation"
                                class="block text-sm font-medium text-default-700 mb-2">Ulangi Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="form-input w-full pr-10 px-4 py-2 border rounded-md text-default-800 focus:ring-primary-500 focus:border-primary-500 @error('password_confirmation') border-red-500 @enderror"
                                placeholder="Ketik ulang password" required>
                            <span class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer top-7"
                                id="togglePasswordConfirmation">
                                <svg class="h-5 w-5 text-default-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.418 0-8-3.582-8-8 0-1.391.354-2.709.975-3.875m3.75 3.75a3 3 0 114.242 4.242M21 12c-1.391-.354-2.709-.975-3.875-1.925M3 12c.354 1.391.975 2.709 1.925 3.875m-3.75-3.75a3 3 0 104.242-4.242M1.925 18.825A10.05 10.05 0 0112 19c4.418 0 8-3.582 8-8 0-1.391-.354-2.709-.975-3.875M12 4v.01" />
                                </svg>
                            </span>
                            @error('password_confirmation')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Bagian Upload Gambar (Foto Profil) --}}
                    <div class="mb-6">
                        <label for="img" class="block text-sm font-medium text-default-700 mb-2">Foto Profil
                            (Opsional)</label>
                        <div id="drop-area-profile"
                            class="border-2 border-dashed border-default-300 rounded-lg p-6 text-center cursor-pointer hover:border-primary-500 transition-all">
                            <input type="file" id="img-upload-profile" name="img" accept="image/*" class="hidden">
                            <p class="text-default-500 mb-2">Seret dan lepas gambar di sini, atau</p>
                            <button type="button" id="select-file-btn-profile"
                                class="px-4 py-2 rounded-md bg-primary-100 text-primary-700 hover:bg-primary-200">
                                Pilih File
                            </button>

                            {{-- Elemen untuk menampilkan informasi file --}}
                            <div id="file-info-profile" class="mt-4 text-default-600 text-sm hidden">
                                <p><strong>Nama File:</strong> <span id="file-name-profile"></span></p>
                                <p><strong>Ukuran File:</strong> <span id="file-size-profile"></span></p>
                            </div>
                            {{-- Elemen untuk menampilkan pesan error validasi ukuran file --}}
                            <div id="file-error-profile" class="mt-2 text-red-600 text-sm font-medium hidden"></div>

                            {{-- Elemen untuk menampilkan preview gambar --}}
                            <div id="image-preview-profile" class="mt-4 flex justify-center items-center">
                                {{-- Gambar preview akan muncul di sini --}}
                            </div>

                            {{-- Pesan error dari Laravel Validation --}}
                            @error('img')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-6 border-default-200">

                    <h5 class="text-lg font-semibold text-default-800 mb-4">Detail Field Coordinator (Data Korlap)</h5>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="position" class="block text-sm font-medium text-default-700 mb-2">Posisi</label>
                            <input type="text" name="position" id="position"
                                class="form-input w-full px-4 py-2 border rounded-md text-default-800 focus:ring-primary-500 focus:border-primary-500 @error('position') border-red-500 @enderror"
                                value="{{ old('position', 'Mitra Kerjasama Pengelolaan Perparkiran') }}"
                                placeholder="Masukkan Posisi" required>
                            @error('position')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="id_card_number" class="block text-sm font-medium text-default-700 mb-2">Nomor
                                KTP</label>
                            <input type="text" name="id_card_number" id="id_card_number" maxlength="16"
                                class="form-input w-full px-4 py-2 border rounded-md text-default-800 focus:ring-primary-500 focus:border-primary-500 @error('id_card_number') border-red-500 @enderror"
                                value="{{ old('id_card_number') }}" placeholder="Masukkan Nomor KTP (maks 16 digit)"
                                required>
                            @error('id_card_number')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label for="address" class="block text-sm font-medium text-default-700 mb-2">Alamat
                                Lengkap</label>
                            <textarea name="address" id="address" rows="3"
                                class="form-textarea w-full px-4 py-2 border rounded-md text-default-800 focus:ring-primary-500 focus:border-primary-500 @error('address') border-red-500 @enderror"
                                placeholder="Masukkan alamat lengkap" required>{{ old('address') }}</textarea>
                            @error('address')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        {{-- New: Phone Number Field --}}
                        <div>
                            <label for="phone_number" class="block text-sm font-medium text-default-700 mb-2">Nomor
                                Telepon</label>
                            <input type="text" name="phone_number" id="phone_number"
                                class="form-input w-full px-4 py-2 border rounded-md text-default-800 focus:ring-primary-500 focus:border-primary-500 @error('phone_number') border-red-500 @enderror"
                                value="{{ old('phone_number') }}" placeholder="Masukkan nomor telepon" required>
                            {{-- Removed (opsional) and added required --}}
                            @error('phone_number')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        {{-- Bagian Upload Gambar (Foto KTP) --}}
                        <div class="md:col-span-2">
                            <label for="id_card_img" class="block text-sm font-medium text-default-700 mb-2">Foto
                                KTP</label>
                            <div id="drop-area-idcard"
                                class="border-2 border-dashed border-default-300 rounded-lg p-6 text-center cursor-pointer hover:border-primary-500 transition-all">
                                <input type="file" id="img-upload-idcard" name="id_card_img" accept="image/*"
                                    class="hidden" required>
                                <p class="text-default-500 mb-2">Seret dan lepas gambar KTP di sini, atau</p>
                                <button type="button" id="select-file-btn-idcard"
                                    class="px-4 py-2 rounded-md bg-primary-100 text-primary-700 hover:bg-primary-200">
                                    Pilih File KTP
                                </button>

                                {{-- Elemen untuk menampilkan informasi file --}}
                                <div id="file-info-idcard" class="mt-4 text-default-600 text-sm hidden">
                                    <p><strong>Nama File:</strong> <span id="file-name-idcard"></span></p>
                                    <p><strong>Ukuran File:</strong> <span id="file-size-idcard"></span></p>
                                </div>
                                {{-- Elemen untuk menampilkan pesan error validasi ukuran file --}}
                                <div id="file-error-idcard" class="mt-2 text-red-600 text-sm font-medium hidden"></div>

                                {{-- Elemen untuk menampilkan preview gambar --}}
                                <div id="image-preview-idcard" class="mt-4 flex justify-center items-center">
                                    {{-- Gambar preview akan muncul di sini --}}
                                </div>

                                {{-- Pesan error dari Laravel Validation --}}
                                @error('id_card_img')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 mt-6">
                        <button type="submit"
                            class="px-6 py-2 rounded-md text-white bg-primary-600 hover:bg-primary-700 transition-all">
                            Simpan Field Coordinator
                        </button>
                        <a href="{{ route('admin.field-coordinators.index') }}"
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
                console.log('DOM Content Loaded for Field Coordinator Create Page.');

                const MAX_FILE_SIZE_KB = 300;
                const MAX_FILE_SIZE_BYTES = MAX_FILE_SIZE_KB * 1024;

                // --- Helper Functions for Image Upload ---
                function preventDefaults(e) {
                    e.preventDefault();
                    e.stopPropagation();
                }

                function highlight(dropArea) {
                    dropArea.classList.add('border-primary-600', 'bg-primary-50');
                }

                function unhighlight(dropArea) {
                    dropArea.classList.remove('border-primary-600', 'bg-primary-50');
                }

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

                                let width = img.width;
                                let height = img.height;

                                const MAX_DIMENSION = 1000;
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

                                let quality = 0.8;
                                let compressedDataUrl = canvas.toDataURL('image/jpeg', quality);
                                let compressedBlob = dataURLtoBlob(compressedDataUrl);

                                while (compressedBlob.size > maxSizeInBytes && quality > 0.1) {
                                    quality -= 0.1;
                                    compressedDataUrl = canvas.toDataURL('image/jpeg', quality);
                                    compressedBlob = dataURLtoBlob(compressedDataUrl);
                                }

                                if (compressedBlob.size > maxSizeInBytes) {
                                    canvas.width = width * 0.5;
                                    canvas.height = height * 0.5;
                                    ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
                                    quality = 0.8;
                                    compressedDataUrl = canvas.toDataURL('image/jpeg', quality);
                                    compressedBlob = dataURLtoBlob(compressedDataUrl);

                                    while (compressedBlob.size > maxSizeInBytes && quality > 0.1) {
                                        quality -= 0.1;
                                        compressedDataUrl = canvas.toDataURL('image/jpeg', quality);
                                        compressedBlob = dataURLtoBlob(compressedDataUrl);
                                    }
                                }

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

                function formatBytes(bytes, decimals = 2) {
                    if (bytes === 0) return '0 Bytes';
                    const k = 1024;
                    const dm = decimals < 0 ? 0 : decimals;
                    const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
                    const i = Math.floor(Math.log(bytes) / Math.log(k));
                    return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
                }

                // --- Generic Image Uploader Setup ---
                function setupImageUploader(prefix) {
                    const dropArea = document.getElementById(`drop-area-${prefix}`);
                    const fileInput = document.getElementById(`img-upload-${prefix}`);
                    const selectFileBtn = document.getElementById(`select-file-btn-${prefix}`);
                    const imagePreview = document.getElementById(`image-preview-${prefix}`);
                    const fileInfoDiv = document.getElementById(`file-info-${prefix}`);
                    const fileNameSpan = document.getElementById(`file-name-${prefix}`);
                    const fileSizeSpan = document.getElementById(`file-size-${prefix}`);
                    const fileErrorDiv = document.getElementById(`file-error-${prefix}`);

                    if (!dropArea || !fileInput || !selectFileBtn) {
                        console.warn(`Image uploader elements not found for prefix: ${prefix}`);
                        return;
                    }
                    console.log(`Setting up image uploader for: ${prefix}`);

                    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                        dropArea.addEventListener(eventName, preventDefaults, false);
                    });

                    dropArea.addEventListener('dragenter', () => highlight(dropArea), false);
                    dropArea.addEventListener('dragover', () => highlight(dropArea), false);
                    dropArea.addEventListener('dragleave', () => unhighlight(dropArea), false);
                    dropArea.addEventListener('drop', handleDrop, false);

                    function handleDrop(e) {
                        let dt = e.dataTransfer;
                        let files = dt.files;
                        handleFiles(files);
                    }

                    selectFileBtn.addEventListener('click', () => {
                        fileInput.click();
                    });

                    fileInput.addEventListener('change', (e) => {
                        handleFiles(e.target.files);
                    });

                    async function handleFiles(files) {
                        fileInfoDiv.classList.add('hidden');
                        fileErrorDiv.classList.add('hidden');
                        imagePreview.innerHTML = '';

                        if (files.length === 0) {
                            fileInput.value = '';
                            return;
                        }

                        const originalFile = files[0];

                        if (!originalFile.type.startsWith('image/')) {
                            fileErrorDiv.textContent =
                                'File yang dipilih harus berupa gambar (JPEG, PNG, GIF, SVG).';
                            fileErrorDiv.classList.remove('hidden');
                            fileInput.value = '';
                            return;
                        }

                        imagePreview.innerHTML = '<div class="text-default-500">Memproses gambar...</div>';
                        try {
                            const compressedFile = await compressImage(originalFile, MAX_FILE_SIZE_BYTES);

                            if (compressedFile.size <= MAX_FILE_SIZE_BYTES) {
                                fileNameSpan.textContent = compressedFile.name;
                                fileSizeSpan.textContent = formatBytes(compressedFile.size);
                                fileInfoDiv.classList.remove('hidden');

                                const dataTransfer = new DataTransfer();
                                dataTransfer.items.add(compressedFile);
                                fileInput.files = dataTransfer.files;

                                previewFile(compressedFile);
                            } else {
                                fileErrorDiv.textContent =
                                    `Ukuran file masih terlalu besar (${formatBytes(compressedFile.size)}), harus dibawah ${MAX_FILE_SIZE_KB} KB.`;
                                fileErrorDiv.classList.remove('hidden');
                                fileInput.value = '';
                                imagePreview.innerHTML = '';
                            }
                        } catch (error) {
                            console.error(`Error during ${prefix} image processing:`, error);
                            fileErrorDiv.textContent = `Gagal memproses gambar ${prefix}. Pastikan format valid.`;
                            fileErrorDiv.classList.remove('hidden');
                            fileInput.value = '';
                            imagePreview.innerHTML = '';
                        }
                    }

                    function previewFile(file) {
                        let reader = new FileReader();
                        reader.readAsDataURL(file);
                        reader.onloadend = function() {
                            imagePreview.innerHTML = '';
                            let imgElement = document.createElement('img');
                            imgElement.src = reader.result;
                            imgElement.classList.add('max-w-full', 'h-32', 'object-contain', 'rounded-md');
                            imgElement.alt = `Preview Gambar ${prefix}`;
                            imagePreview.appendChild(imgElement);
                        }
                    }
                }

                // Setup for Profile Image Uploader
                setupImageUploader('profile');
                // Setup for ID Card Image Uploader
                setupImageUploader('idcard');

                // === END: Image Upload ===

                // === START: Validasi dan Transformasi Input (Username, Password, ID Card Number, Phone Number) ===
                const usernameInput = document.getElementById('username');
                const passwordInput = document.getElementById('password');
                const passwordConfirmationInput = document.getElementById('password_confirmation');
                const idCardNumberInput = document.getElementById('id_card_number');
                const phoneNumberInput = document.getElementById('phone_number');

                // Untuk input Username
                if (usernameInput) {
                    usernameInput.addEventListener('input', function(e) {
                        let value = e.target.value;
                        value = value.toLowerCase();
                        value = value.replace(/\s/g, '');
                        value = value.replace(/[^a-z0-9_-]/g, '');
                        e.target.value = value;
                        console.log('Username input transformed:', e.target.value);
                    });
                }

                // Untuk input Password dan Konfirmasi Password (hapus spasi)
                if (passwordInput) {
                    passwordInput.addEventListener('input', function(e) {
                        e.target.value = e.target.value.replace(/\s/g, '');
                        console.log('Password input transformed (no spaces):', e.target.value);
                    });
                }
                if (passwordConfirmationInput) {
                    passwordConfirmationInput.addEventListener('input', function(e) {
                        e.target.value = e.target.value.replace(/\s/g, '');
                        console.log('Password confirmation input transformed (no spaces):', e.target.value);
                    });
                }

                // Untuk input Nomor KTP (maksimal 16 digit, hanya angka)
                if (idCardNumberInput) {
                    idCardNumberInput.addEventListener('input', function(e) {
                        let value = e.target.value;
                        value = value.replace(/[^0-9]/g, '');
                        if (value.length > 16) {
                            value = value.substring(0, 16);
                        }
                        e.target.value = value;
                        console.log('ID Card Number input transformed:', e.target.value);
                    });
                }

                // Untuk input Nomor Telepon (hanya angka)
                if (phoneNumberInput) {
                    phoneNumberInput.addEventListener('input', function(e) {
                        let value = e.target.value;
                        value = value.replace(/[^0-9]/g, ''); // Hanya izinkan angka
                        if (value.length > 14) {
                            value = value.substring(0, 14);
                        }
                        e.target.value = value;
                        console.log('Phone Number input transformed:', e.target.value);
                    });
                }
                // === END: Validasi dan Transformasi Input ===

                // === START: Toggle Password Visibility ===
                const togglePassword = document.getElementById('togglePassword');
                const togglePasswordConfirmation = document.getElementById('togglePasswordConfirmation');

                function setupPasswordToggle(inputElement, toggleElement) {
                    if (inputElement && toggleElement) {
                        console.log(`Setting up password toggle for: ${inputElement.id}`);
                        toggleElement.addEventListener('click', function() {
                            const type = inputElement.getAttribute('type') === 'password' ? 'text' : 'password';
                            inputElement.setAttribute('type', type);

                            const icon = this.querySelector('svg');
                            const eyeClosedPath =
                                "M13.875 18.825A10.05 10.05 0 0112 19c-4.418 0-8-3.582-8-8 0-1.391.354-2.709.975-3.875m3.75 3.75a3 3 0 114.242 4.242M21 12c-1.391-.354-2.709-.975-3.875-1.925M3 12c.354 1.391.975 2.709 1.925 3.875m-3.75-3.75a3 3 0 104.242-4.242M1.925 18.825A10.05 10.05 0 0112 19c4.418 0 8-3.582 8-8 0-1.391-.354-2.709-.975-3.875M12 4v.01";
                            const eyeOpenPath =
                                "M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z";

                            if (type === 'password') {
                                icon.innerHTML =
                                    `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${eyeClosedPath}"/>`;
                            } else {
                                icon.innerHTML =
                                    `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${eyeOpenPath}"/>`;
                            }
                        });
                    } else {
                        console.warn(
                            `Password toggle elements not found for input: ${inputElement ? inputElement.id : 'N/A'} or toggle: ${toggleElement ? toggleElement.id : 'N/A'}`
                        );
                    }
                }

                setupPasswordToggle(passwordInput, togglePassword);
                setupPasswordToggle(passwordConfirmationInput, togglePasswordConfirmation);
                // === END: Toggle Password Visibility ===
            });
        </script>
    @endpush
