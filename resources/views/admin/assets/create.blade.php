@extends('admin.layouts.app')

@section('title', 'Tambah Aset Baru - GriyaOne')
@section('role', 'Tambah Aset Baru')

@section('content')
    <!-- Header -->
    <div class="mb-8 flex justify-between items-start fade-in">
        <div>
            <h2 class="text-3xl font-bold text-gray-900 mb-1">Tambah Aset Baru</h2>
            <p class="text-gray-600">Isi formulir untuk membuat listing aset baru</p>
        </div>
        <a href="{{ route('assets.index') }}" class="text-red-600 hover:text-red-700 font-medium text-sm transition">← Kembali ke Daftar</a>
    </div>

    <!-- Form -->
    <form action="{{ route('assets.store') }}" method="POST" enctype="multipart/form-data" class="fade-in" onsubmit="return handleFormSubmit(event)">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Form -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Informasi Dasar -->
                <div class="bg-white rounded-xl p-6 shadow-md">
                    <h3 class="text-lg font-bold text-gray-900 mb-6"> Informasi Dasar</h3>

                    <div class="space-y-4">
                        <!-- Judul -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Judul Aset <span class="text-red-500">*</span></label>
                            <input type="text" name="title" value="{{ old('title') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-red-500 transition @error('title') border-red-500 @enderror" placeholder="Rumah Mewah di Jakarta Selatan">
                            @error('title')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Deskripsi dengan WYSIWYG Quill -->
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <label class="block text-sm font-semibold text-gray-700">Deskripsi <span class="text-red-500">*</span></label>
                            </div>
                            <div id="editor-container" class="bg-white border border-gray-300 rounded-lg overflow-hidden focus-within:border-red-500 transition @error('description') border-red-500 @enderror" style="min-height: 300px; position: relative;">
                        {!! old('description') !!}
                    </div>
                    <input type="hidden" name="description" id="description_content" value="">
                    <p id="desc_error" class="hidden text-red-500 text-xs mt-2">Deskripsi kosong! Silakan isi deskripsi terlebih dahulu.</p>
                    @error('description')
                        <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                    @enderror
                        </div>

                        <!-- Kategori & Status -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Kategori <span class="text-red-500">*</span></label>
                                <select name="category" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-red-500 transition @error('category') border-red-500 @enderror">
                                    <option value="">Pilih Kategori</option>
                                    <option value="Bank Cessie" @selected(old('category') === 'Bank Cessie')>Bank Cessie</option>
                                    <option value="AYDA" @selected(old('category') === 'AYDA')>AYDA</option>
                                    <option value="Lelang" @selected(old('category') === 'Lelang')>Lelang</option>
                                </select>
                                @error('category')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-red-500 transition @error('status') border-red-500 @enderror">
                                    <option value="">Pilih Status</option>
                                    <option value="Available" @selected(old('status') === 'Available' || old('status') === null)>Available</option>
                                    <option value="Sold Out" @selected(old('status') === 'Sold Out')>Sold Out</option>
                                </select>
                                @error('status')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Link Google Maps -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Link Google Maps
                                <span class="text-gray-400 font-normal">(opsional)</span>
                            </label>
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-red-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <input type="url" name="gmap_link" value="{{ old('gmap_link') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-red-500 transition @error('gmap_link') border-red-500 @enderror" placeholder="https://maps.google.com/...">
                            </div>
                            @error('gmap_link')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-400 mt-1">Tempel link Google Maps lokasi aset agar bisa disalin oleh tim marketing dan user.</p>
                        </div>

                        <!-- Lokasi Wilayah Indonesia -->
                        <div class="space-y-3">
                            <label class="block text-sm font-semibold text-gray-700">Lokasi <span class="text-gray-400 font-normal">(Provinsi / Kota / Kecamatan)</span></label>
                            <div class="grid grid-cols-1 gap-3">
                                <select id="sel_provinsi" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-red-500 transition">
                                    <option value="">-- Pilih Provinsi --</option>
                                </select>
                                <select id="sel_kota" disabled class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-red-500 transition disabled:bg-gray-50 disabled:text-gray-400">
                                    <option value="">-- Pilih Kabupaten / Kota --</option>
                                </select>
                                <select id="sel_kecamatan" disabled class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-red-500 transition disabled:bg-gray-50 disabled:text-gray-400">
                                    <option value="">-- Pilih Kecamatan --</option>
                                </select>
                            </div>
                            <input type="hidden" name="location" id="location_value" value="{{ old('location') }}">
                            <p id="lokasi_preview" class="text-xs text-gray-500 italic hidden"></p>
                            @error('location')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Upload Foto -->
                <div class="bg-white rounded-xl p-6 shadow-md">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-900">Upload Foto</h3>
                        <span id="photo-counter" class="hidden items-center gap-1.5 bg-orange-100 text-orange-700 text-xs font-bold px-3 py-1.5 rounded-full">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 5a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V7a2 2 0 00-2-2h-1.586a1 1 0 01-.707-.293l-1.121-1.121A2 2 0 0011.172 3H8.828a2 2 0 00-1.414.586L6.293 4.707A1 1 0 015.586 5H4zm6 9a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/></svg>
                            <span id="photo-count-text">0 foto</span>
                        </span>
                    </div>

                    <!-- Dropzone -->
                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-orange-400 hover:bg-orange-50/40 transition-all duration-200 group" id="photo-dropzone">
                        <div class="w-14 h-14 bg-orange-50 rounded-2xl flex items-center justify-center mx-auto mb-3 group-hover:bg-orange-100 transition pointer-events-none">
                            <svg class="w-7 h-7 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                        </div>
                        <p class="text-gray-700 font-semibold mb-1 pointer-events-none">Seret &amp; lepas foto di sini</p>
                        <p class="text-gray-500 text-sm pointer-events-none">atau klik tombol di bawah</p>
                        <p class="text-xs text-gray-400 mt-2 pointer-events-none">JPG, PNG, WEBP &bull; Maks. 5MB per foto</p>
                        <input type="file" name="photos[]" id="photo-input" multiple accept="image/jpeg,image/jpg,image/png,image/webp" class="hidden">
                        <button type="button" id="pick-btn"
                            class="mt-4 inline-flex items-center gap-2 bg-orange-500 hover:bg-orange-600 text-white px-5 py-2 rounded-lg font-semibold text-sm transition shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Pilih Foto
                        </button>
                    </div>

                    <!-- Error box -->
                    <div id="upload-errors" class="hidden mt-3 p-3 bg-red-50 border border-red-200 rounded-lg">
                        <p class="text-xs font-semibold text-red-700 mb-1">File diabaikan:</p>
                        <ul id="upload-error-list" class="text-xs text-red-600 space-y-0.5 list-disc list-inside"></ul>
                    </div>

                    <!-- File List (tampil setelah dipilih) -->
                    <div id="file-list-wrapper" class="hidden mt-5">
                        <div class="flex items-center justify-between mb-3">
                            <p class="text-sm font-semibold text-gray-700">Foto yang akan diupload:</p>
                            <button type="button" id="clear-all-btn"
                                class="text-xs text-red-500 hover:text-red-700 font-semibold underline">Hapus Semua</button>
                        </div>
                        <!-- Grid preview -->
                        <div id="photo-preview" class="grid grid-cols-2 sm:grid-cols-3 gap-3"></div>
                    </div>

                    @error('photos')
                        <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Buttons -->
                <div class="flex gap-4">
                    <button type="submit" class="flex-1 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white px-6 py-3 rounded-lg font-semibold transition-all duration-200 shadow-md hover:shadow-lg">
                        Simpan Aset
                    </button>
                    <a href="{{ route('assets.index') }}" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-900 px-6 py-3 rounded-lg font-semibold transition text-center">
                        Batalkan
                    </a>
                </div>
            </div>

            <!-- Info Card -->
            <div class="lg:col-span-1">
                <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-xl p-6 border border-red-200 sticky top-24">
                    <h4 class="font-bold text-gray-900 mb-4"> Tips Listing</h4>
                    <div class="space-y-3 text-sm text-gray-700">
                        <div>
                            <p class="font-semibold text-gray-900">Judul Menarik</p>
                            <p class="text-xs text-gray-600 mt-1">Gunakan judul yang deskriptif dan menarik untuk menarik perhatian pembeli.</p>
                        </div>
                        <div class="border-t border-red-200 pt-3">
                            <p class="font-semibold text-gray-900">Foto Berkualitas</p>
                            <p class="text-xs text-gray-600 mt-1">Unggah foto dengan sudut berbeda untuk hasil terbaik.</p>
                        </div>
                        <div class="border-t border-red-200 pt-3">
                            <p class="font-semibold text-gray-900">Deskripsi Lengkap</p>
                            <p class="text-xs text-gray-600 mt-1">Jelaskan fitur, lokasi, dan keunggulan properti secara detail.</p>
                        </div>
                        <div class="border-t border-red-200 pt-3">
                            <p class="font-semibold text-gray-900">Alamat Jelas</p>
                            <p class="text-xs text-gray-600 mt-1">Sertakan informasi alamat yang lengkap dan mudah ditemukan.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script>
        // ===== PHOTO UPLOAD — robust array-based approach =====
        (function() {
            const ALLOWED_TYPES = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
            const MAX_MB = 5;
            let photoFiles = []; // master array of File objects

            const dropzone     = document.getElementById('photo-dropzone');
            const photoInput   = document.getElementById('photo-input');
            const photoPreview = document.getElementById('photo-preview');
            const errorBox     = document.getElementById('upload-errors');
            const errorList    = document.getElementById('upload-error-list');
            const counter      = document.getElementById('photo-counter');
            const counterText  = document.getElementById('photo-count-text');
            const listWrapper  = document.getElementById('file-list-wrapper');
            const clearAllBtn  = document.getElementById('clear-all-btn');
            const pickBtn      = document.getElementById('pick-btn');

            // Pilih foto
            pickBtn.addEventListener('click', () => photoInput.click());

            // Hapus semua
            clearAllBtn.addEventListener('click', () => {
                photoFiles = [];
                syncInputFiles();
                render();
            });

            // Drag & drop
            dropzone.addEventListener('dragover', e => {
                e.preventDefault();
                dropzone.classList.add('border-orange-400', 'bg-orange-50');
            });
            dropzone.addEventListener('dragleave', () => {
                dropzone.classList.remove('border-orange-400', 'bg-orange-50');
            });
            dropzone.addEventListener('drop', e => {
                e.preventDefault();
                dropzone.classList.remove('border-orange-400', 'bg-orange-50');
                addFiles(e.dataTransfer.files);
            });

            // File input change
            photoInput.addEventListener('change', () => {
                addFiles(photoInput.files);
                // Reset input agar file yang sama bisa dipilih lagi
                photoInput.value = '';
            });

            function addFiles(fileList) {
                const errors = [];
                Array.from(fileList).forEach(file => {
                    if (!ALLOWED_TYPES.includes(file.type)) {
                        errors.push(`"${file.name}" – format tidak didukung`);
                        return;
                    }
                    if (file.size > MAX_MB * 1024 * 1024) {
                        errors.push(`"${file.name}" – melebihi ${MAX_MB}MB (${(file.size/1024/1024).toFixed(1)}MB)`);
                        return;
                    }
                    // Deduplikasi berdasarkan nama+ukuran
                    const dup = photoFiles.some(f => f.name === file.name && f.size === file.size);
                    if (!dup) photoFiles.push(file);
                });
                showErrors(errors);
                syncInputFiles();
                render();
            }

            function removeFile(idx) {
                photoFiles.splice(idx, 1);
                syncInputFiles();
                render();
            }

            // Sinkronisasi photoFiles → input.files (untuk dikirim via form)
            function syncInputFiles() {
                const dt = new DataTransfer();
                photoFiles.forEach(f => dt.items.add(f));
                photoInput.files = dt.files;
            }

            function render() {
                photoPreview.innerHTML = '';

                if (photoFiles.length === 0) {
                    listWrapper.classList.add('hidden');
                    counter.classList.add('hidden');
                    counter.classList.remove('inline-flex');
                    return;
                }

                listWrapper.classList.remove('hidden');
                counterText.textContent = photoFiles.length + ' foto';
                counter.classList.remove('hidden');
                counter.classList.add('inline-flex');

                photoFiles.forEach((file, idx) => {
                    const reader = new FileReader();
                    reader.onload = ev => {
                        const card = document.createElement('div');
                        card.className = 'relative rounded-xl overflow-hidden border border-gray-200 shadow-sm bg-gray-100';
                        card.dataset.idx = idx;
                        card.innerHTML = `
                            <img src="${ev.target.result}" class="w-full h-32 object-cover" alt="Foto ${idx+1}">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent pointer-events-none"></div>
                            <div class="absolute bottom-0 left-0 right-0 p-2 pointer-events-none">
                                <p class="text-white text-[10px] font-semibold truncate">${file.name}</p>
                                <p class="text-white/60 text-[9px]">${(file.size/1024).toFixed(0)} KB</p>
                            </div>
                            <span class="absolute top-1.5 left-1.5 bg-black/50 text-white text-[9px] font-bold px-1.5 py-0.5 rounded-full pointer-events-none">Foto ${idx+1}</span>
                            <button type="button"
                                onclick="window.__removePhoto(${idx})"
                                title="Hapus foto ini"
                                class="absolute top-1.5 right-1.5 flex items-center gap-1 bg-red-600 hover:bg-red-700 text-white text-[10px] font-bold px-2 py-1 rounded-full shadow transition hover:scale-105">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                                Hapus
                            </button>
                        `;
                        photoPreview.appendChild(card);
                    };
                    reader.readAsDataURL(file);
                });
            }

            function showErrors(errors) {
                if (!errors.length) { errorBox.classList.add('hidden'); return; }
                errorList.innerHTML = errors.map(e => `<li>${e}</li>`).join('');
                errorBox.classList.remove('hidden');
            }

            // Expose removeFile ke global scope (dipanggil dari onclick di innerHTML)
            window.__removePhoto = function(idx) { removeFile(idx); };
        })();

        // ===== QUILL EDITOR =====
        let quillInstance = null;
        const DEBUG = true;

        function log(...args) {
            if (DEBUG) console.log('[Quill Debug]', ...args);
        }

        function initializeQuill() {
            const editorContainer = document.getElementById('editor-container');
            if (!editorContainer) {
                console.error('[FATAL] Editor container not found!');
                return;
            }

            if (!window.Quill) {
                console.error('[FATAL] Quill library not loaded!');
                return;
            }

            log('Starting Quill initialization...');
            const initialContent = editorContainer.innerHTML.trim();
            log('Initial content length:', initialContent.length);
            log('Initial content preview:', initialContent.substring(0, 80));

            editorContainer.innerHTML = '';

            try {
                quillInstance = new window.Quill('#editor-container', {
                    theme: 'snow',
                    placeholder: 'Jelaskan detail aset secara lengkap...',
                    modules: {
                        toolbar: [
                            [{ 'header': [1, 2, 3, false] }],
                            ['bold', 'italic', 'underline', 'strike'],
                            ['blockquote', 'code-block'],
                            [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                            [{ 'align': [] }],
                            ['link', 'image'],
                            ['clean']
                        ]
                    }
                });

                log('Quill instance created successfully');

                if (initialContent && initialContent.length > 0) {
                    try {
                        quillInstance.root.innerHTML = initialContent;
                        log('Initial content restored to editor');
                    } catch (err) {
                        console.error('Error restoring content:', err);
                    }
                }

                quillInstance.on('text-change', () => {
                    const currentText = quillInstance.getText().trim();
                    const currentHtml = quillInstance.root.innerHTML;
                    log('Editor changed - Text length:', currentText.length);
                });

            } catch (err) {
                console.error('Error initializing Quill:', err);
            }
        }

        function syncQuillToField() {
            log('Syncing Quill to hidden field...');

            if (!quillInstance) {
                console.error('Quill instance not available!');
                return false;
            }

            const field = document.getElementById('description_content');
            if (!field) {
                console.error('Hidden field #description_content not found!');
                return false;
            }

            const htmlContent = quillInstance.root.innerHTML;
            const textContent = quillInstance.getText().trim();

            log('Quill content:');
            log('  - Text length:', textContent.length);
            log('  - HTML length:', htmlContent.length);
            log('  - Text preview:', textContent.substring(0, 50));

            if (textContent.length === 0 || textContent === '') {
                console.error('Quill editor is EMPTY!');
                return false;
            }

            field.value = htmlContent;

            log('Field updated:');
            log('  - Field.value length:', field.value.length);
            log('  - Field.value:', field.value.substring(0, 100));

            setTimeout(() => {
                log('Verification:');
                log('  - Field.value:', field.value.substring(0, 100));
            }, 10);

            return true;
        }

        window.handleFormSubmit = function(event) {
            log('========================================');
            log('📤 FORM SUBMISSION STARTED');
            log('========================================');

            // Sync first, then decide
            if (!syncQuillToField()) {
                event.preventDefault();
                log('SYNC FAILED - Preventing form submission');
                const descErr = document.getElementById('desc_error');
                if (descErr) {
                    descErr.classList.remove('hidden');
                    descErr.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
                return false;
            }
            const descErr = document.getElementById('desc_error');
            if (descErr) descErr.classList.add('hidden');

            log('SYNC SUCCESSFUL - Allowing form submission');
            log('========================================');

            // Return true to allow default form submission
            // Do NOT prevent default or manually submit - let browser handle it naturally
            return true;
        };

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => {
                log('DOM loaded - initializing Quill');
                initializeQuill();
            });
        } else {
            log('DOM already loaded - initializing Quill immediately');
            initializeQuill();
        }

        window.addEventListener('load', () => {
            if (!quillInstance) {
                log('Quill not initialized on DOMContentLoaded, trying again on page load');
                initializeQuill();
            } else {
                log('Quill already initialized');
            }
        });
    </script>

    {{-- ===== WILAYAH INDONESIA CASCADE ===== --}}
    <script>
    (function() {
        const API = 'https://www.emsifa.com/api-wilayah-indonesia/api';
        const selProv  = document.getElementById('sel_provinsi');
        const selKota  = document.getElementById('sel_kota');
        const selKec   = document.getElementById('sel_kecamatan');
        const hidLoc   = document.getElementById('location_value');
        const preview  = document.getElementById('lokasi_preview');

        function setOptions(sel, items, valKey, nameKey, placeholder) {
            sel.innerHTML = `<option value="">${placeholder}</option>`;
            items.forEach(item => {
                const opt = document.createElement('option');
                opt.value = item[valKey];
                opt.dataset.name = item[nameKey];
                opt.textContent = item[nameKey];
                sel.appendChild(opt);
            });
        }

        function updateHidden() {
            const pOpt = selProv.options[selProv.selectedIndex];
            const kOpt = selKota.options[selKota.selectedIndex];
            const cOpt = selKec.options[selKec.selectedIndex];
            const parts = [];
            if (cOpt && cOpt.value) parts.push('Kec. ' + cOpt.dataset.name);
            if (kOpt && kOpt.value) parts.push(kOpt.dataset.name);
            if (pOpt && pOpt.value) parts.push(pOpt.dataset.name);
            const val = parts.join(', ');
            hidLoc.value = val;
            if (val) {
                preview.textContent = val;
                preview.classList.remove('hidden');
            } else {
                preview.classList.add('hidden');
            }
        }

        // Load provinces
        fetch(`${API}/provinces.json`)
            .then(r => r.json())
            .then(data => {
                setOptions(selProv, data, 'id', 'name', '-- Pilih Provinsi --');
                // Restore old value if any
                const oldVal = hidLoc.value;
                if (oldVal) {
                    preview.textContent = oldVal;
                    preview.classList.remove('hidden');
                }
            })
            .catch(() => console.warn('Gagal memuat data provinsi'));

        selProv.addEventListener('change', function() {
            const provId = this.value;
            selKota.innerHTML = '<option value="">-- Pilih Kabupaten / Kota --</option>';
            selKota.disabled = true;
            selKec.innerHTML  = '<option value="">-- Pilih Kecamatan --</option>';
            selKec.disabled   = true;
            hidLoc.value = '';
            preview.classList.add('hidden');
            if (!provId) return;
            fetch(`${API}/regencies/${provId}.json`)
                .then(r => r.json())
                .then(data => {
                    setOptions(selKota, data, 'id', 'name', '-- Pilih Kabupaten / Kota --');
                    selKota.disabled = false;
                })
                .catch(() => console.warn('Gagal memuat kota'));
        });

        selKota.addEventListener('change', function() {
            const kotaId = this.value;
            selKec.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
            selKec.disabled  = true;
            updateHidden();
            if (!kotaId) return;
            fetch(`${API}/districts/${kotaId}.json`)
                .then(r => r.json())
                .then(data => {
                    setOptions(selKec, data, 'id', 'name', '-- Pilih Kecamatan --');
                    selKec.disabled = false;
                })
                .catch(() => console.warn('Gagal memuat kecamatan'));
        });

        selKec.addEventListener('change', updateHidden);
    })();
    </script>
@endsection


