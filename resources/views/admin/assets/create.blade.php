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
    <form id="asset-form" action="{{ route('assets.store') }}" method="POST" enctype="multipart/form-data" class="fade-in" onsubmit="return handleFormSubmit(event)">
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
                <div class="bg-white rounded-xl p-6 shadow-md border border-gray-100">
                    <!-- Header -->
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-orange-500 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">Upload Foto Produk</h3>
                                <p class="text-xs text-gray-500 mt-0.5">JPG, PNG, WEBP • Maks 5MB per foto</p>
                            </div>
                        </div>
                        <div id="photo-counter" class="hidden items-center gap-2 bg-green-50 text-green-700 text-sm font-bold px-3 py-1.5 rounded-full border border-green-200">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            <span id="photo-count-text">0/0 foto</span>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <!-- Overall progress bar (hidden until uploading) -->
                        <div id="overall-progress-wrap" class="hidden space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-semibold text-gray-600">Sedang mengupload...</span>
                                <span id="overall-progress-pct" class="text-xs font-bold text-orange-600">0%</span>
                            </div>
                            <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                                <div id="overall-progress-bar" class="h-full bg-orange-500 rounded-full transition-all duration-300" style="width:0%"></div>
                            </div>
                        </div>

                        <!-- Dropzone -->
                        <div id="photo-dropzone" class="relative border-2 border-dashed border-gray-300 rounded-xl p-8 text-center cursor-pointer transition-all duration-200 group hover:border-orange-400 hover:bg-orange-50/50">
                            <input type="file" id="photo-input" multiple accept="image/jpeg,image/jpg,image/png,image/webp" class="hidden">
                            <div class="flex flex-col items-center justify-center gap-3">
                                <div class="w-16 h-16 bg-orange-50 rounded-2xl flex items-center justify-center group-hover:bg-orange-100 transition">
                                    <svg class="w-8 h-8 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                </div>
                                <div>
                                    <p class="text-gray-800 font-semibold mb-1">Seret &amp; lepas foto di sini</p>
                                    <p class="text-sm text-gray-500 mb-3">atau klik tombol untuk memilih file</p>
                                    <p class="text-xs text-gray-400">Foto akan diupload otomatis di background</p>
                                </div>
                                <button type="button" id="pick-btn" class="mt-3 inline-flex items-center gap-2 bg-orange-500 hover:bg-orange-600 text-white font-semibold text-sm px-6 py-2.5 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 hover:-translate-y-0.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    Pilih Foto
                                </button>
                            </div>
                        </div>

                        <!-- Error box -->
                        <div id="upload-errors" class="hidden p-4 bg-red-50 border border-red-200 rounded-lg flex gap-3 items-start">
                            <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                            <div>
                                <p class="text-sm font-bold text-red-700 mb-1">File tidak valid:</p>
                                <ul id="upload-error-list" class="text-xs text-red-600 space-y-0.5 list-disc list-inside"></ul>
                            </div>
                        </div>

                        <!-- Photo grid -->
                        <div id="file-list-wrapper" class="hidden">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="text-sm font-semibold text-gray-700">Foto yang dipilih</h4>
                                <button type="button" id="clear-all-btn" class="text-xs text-red-600 hover:text-red-700 font-semibold hover:underline">Hapus Semua</button>
                            </div>
                            <div id="photo-preview" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3"></div>
                        </div>

                        <!-- Pending upload warning -->
                        <div id="pending-warning" class="hidden p-4 bg-yellow-50 border border-yellow-200 rounded-lg flex items-center gap-3">
                            <svg class="w-5 h-5 text-yellow-600 flex-shrink-0 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/></svg>
                            <p id="pending-warning-text" class="text-sm font-semibold text-yellow-700">Sedang mengunggah foto...</p>
                        </div>

                        @error('photos')
                            <p class="text-red-500 text-xs flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
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
        // ===== BACKGROUND PHOTO UPLOAD =====
        (function () {
            const ALLOWED_TYPES = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
            const MAX_MB        = 5;
            const COMPRESS_MAX  = 1280;
            const COMPRESS_SKIP = 300 * 1024;
            const COMPRESS_Q    = 0.72;
            const UPLOAD_URL    = '{{ route("assets.upload-photo") }}';
            const CSRF          = '{{ csrf_token() }}';

            // STATE: one entry per selected file
            // { id, file, thumbUrl, status: 'compressing'|'uploading'|'done'|'error', tempPath, retryFn }
            let items = [];

            const dropzone      = document.getElementById('photo-dropzone');
            const photoInput    = document.getElementById('photo-input');
            const preview       = document.getElementById('photo-preview');
            const errorBox      = document.getElementById('upload-errors');
            const errorList     = document.getElementById('upload-error-list');
            const counter       = document.getElementById('photo-counter');
            const counterText   = document.getElementById('photo-count-text');
            const listWrapper   = document.getElementById('file-list-wrapper');
            const clearAllBtn   = document.getElementById('clear-all-btn');
            const pickBtn       = document.getElementById('pick-btn');
            const pendingWarn   = document.getElementById('pending-warning');
            const pendingText   = document.getElementById('pending-warning-text');
            const progressWrap  = document.getElementById('overall-progress-wrap');
            const progressBar   = document.getElementById('overall-progress-bar');
            const progressPct   = document.getElementById('overall-progress-pct');

            // ── compress ──────────────────────────────────────────────
            function compress(file) {
                return new Promise(resolve => {
                    if (file.size <= COMPRESS_SKIP) { resolve(file); return; }
                    const img = new Image();
                    const url = URL.createObjectURL(file);
                    img.onload = () => {
                        URL.revokeObjectURL(url);
                        let { width, height } = img;
                        if (width > COMPRESS_MAX || height > COMPRESS_MAX) {
                            const r = Math.min(COMPRESS_MAX / width, COMPRESS_MAX / height);
                            width = Math.round(width * r); height = Math.round(height * r);
                        }
                        const c = document.createElement('canvas');
                        c.width = width; c.height = height;
                        c.getContext('2d').drawImage(img, 0, 0, width, height);
                        const t = file.type === 'image/png' ? 'image/png' : 'image/jpeg';
                        c.toBlob(b => {
                            if (!b) { resolve(file); return; }
                            const ext = t === 'image/png' ? '.png' : '.jpg';
                            resolve(new File([b], file.name.replace(/\.[^.]+$/, '') + ext, { type: t, lastModified: Date.now() }));
                        }, t, t === 'image/png' ? undefined : COMPRESS_Q);
                    };
                    img.onerror = () => { URL.revokeObjectURL(url); resolve(file); };
                    img.src = url;
                });
            }

            // ── upload one file ──────────────────────────────────────
            async function uploadItem(item) {
                item.status = 'uploading';
                renderCard(item);

                const fd = new FormData();
                fd.append('photo', item.file);
                fd.append('_token', CSRF);

                try {
                    const res  = await fetch(UPLOAD_URL, { method: 'POST', body: fd });
                    const json = await res.json();

                    if (!res.ok || !json.success) throw new Error(json.message || 'Server error');

                    item.status   = 'done';
                    item.tempPath = json.path;
                    // inject hidden input so form submission knows the temp path
                    const inp = document.createElement('input');
                    inp.type = 'hidden'; inp.name = 'temp_photos[]'; inp.value = json.path;
                    inp.id = 'tp_' + item.id;
                    document.getElementById('asset-form').appendChild(inp);

                } catch (e) {
                    item.status = 'error';
                    item.errorMsg = e.message;
                }

                renderCard(item);
                updateCounter();
            }

            // ── process new files ────────────────────────────────────
            async function addFiles(fileList) {
                const errs = [], valids = [];
                Array.from(fileList).forEach(f => {
                    if (!ALLOWED_TYPES.includes(f.type))            { errs.push(`"${f.name}" – format tidak didukung`); return; }
                    if (f.size > MAX_MB * 1024 * 1024)              { errs.push(`"${f.name}" – melebihi ${MAX_MB}MB`);  return; }
                    if (items.some(i => i.file.name === f.name && i.file.size === f.size)) return;
                    valids.push(f);
                });
                showErrors(errs);
                if (!valids.length) return;

                // Create placeholder cards first
                for (const f of valids) {
                    const id   = Date.now() + Math.random();
                    const blob = URL.createObjectURL(f);
                    const item = { id, file: f, thumbUrl: blob, status: 'compressing', tempPath: null };
                    item.retryFn = () => uploadItem(item);
                    items.push(item);
                    renderCard(item);
                }
                listWrapper.classList.remove('hidden');
                updateCounter();

                // Compress + upload in parallel
                await Promise.all(valids.map(async (f, i) => {
                    const item = items[items.length - valids.length + i];
                    const compressed = await compress(f);
                    URL.revokeObjectURL(item.thumbUrl);
                    item.file     = compressed;
                    item.thumbUrl = URL.createObjectURL(compressed);
                    await uploadItem(item);
                }));
            }

            // ── render single card ───────────────────────────────────
            function renderCard(item) {
                let card = document.getElementById('card_' + item.id);
                if (!card) {
                    card = document.createElement('div');
                    card.id        = 'card_' + item.id;
                    card.className = 'relative rounded-xl overflow-hidden border shadow-sm bg-gray-100';
                    preview.appendChild(card);
                }

                const statusHtml = {
                    compressing: `<div class="absolute inset-0 bg-black/50 flex flex-col items-center justify-center gap-1">
                        <svg class="w-5 h-5 text-white animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/></svg>
                        <p class="text-white text-[10px] font-bold">Mengompresi…</p></div>`,
                    uploading: `<div class="absolute inset-0 bg-black/50 flex flex-col items-center justify-center gap-1">
                        <svg class="w-5 h-5 text-orange-400 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/></svg>
                        <p class="text-white text-[10px] font-bold">Mengupload…</p></div>`,
                    done: `<div class="absolute top-1.5 right-1.5 bg-green-500 rounded-full p-0.5">
                        <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg></div>
                        <button type="button" onclick="window.__rmPhoto('${item.id}')" class="absolute top-1.5 left-1.5 bg-red-600 hover:bg-red-700 text-white text-[9px] font-bold px-1.5 py-0.5 rounded-full transition">✕</button>`,
                    error: `<div class="absolute inset-0 bg-red-800/70 flex flex-col items-center justify-center gap-1 p-1">
                        <p class="text-white text-[9px] font-bold text-center">${item.errorMsg || 'Gagal'}</p>
                        <button type="button" onclick="window.__retryPhoto('${item.id}')" class="bg-white text-red-700 text-[9px] font-bold px-2 py-0.5 rounded-full mt-1">Coba lagi</button></div>`,
                }[item.status] || '';

                card.innerHTML = `
                    <img src="${item.thumbUrl}" class="w-full h-32 object-cover" alt="" decoding="async" loading="lazy">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/10 to-transparent pointer-events-none"></div>
                    <div class="absolute bottom-0 left-0 right-0 px-2 py-1.5 pointer-events-none">
                        <p class="text-white text-[9px] font-semibold truncate leading-tight">${item.file.name}</p>
                        <p class="text-white/50 text-[8px]">${(item.file.size/1024).toFixed(0)} KB</p>
                    </div>
                    ${statusHtml}`;
            }

            // ── remove / retry ───────────────────────────────────────
            window.__rmPhoto = id => {
                const idx = items.findIndex(i => String(i.id) === String(id));
                if (idx === -1) return;
                URL.revokeObjectURL(items[idx].thumbUrl);
                const inp = document.getElementById('tp_' + id);
                if (inp) inp.remove();
                document.getElementById('card_' + id)?.remove();
                items.splice(idx, 1);
                updateCounter();
                if (!items.length) listWrapper.classList.add('hidden');
            };
            window.__retryPhoto = id => {
                const item = items.find(i => String(i.id) === String(id));
                if (item) item.retryFn();
            };

            // ── counter, progress bar & warning ─────────────────────
            function updateCounter() {
                const total   = items.length;
                const pending = items.filter(i => i.status === 'compressing' || i.status === 'uploading').length;
                const doneOk  = items.filter(i => i.status === 'done').length;

                // Badge
                if (total === 0) {
                    counter.classList.add('hidden'); counter.classList.remove('inline-flex');
                } else {
                    counterText.textContent = `${doneOk}/${total} foto`;
                    counter.classList.remove('hidden'); counter.classList.add('inline-flex');
                }

                // Overall progress bar
                if (progressWrap) {
                    if (pending > 0 || (total > 0 && doneOk < total)) {
                        progressWrap.classList.remove('hidden');
                        const pct = total > 0 ? Math.round((doneOk / total) * 100) : 0;
                        if (progressBar) progressBar.style.width = pct + '%';
                        if (progressPct) progressPct.textContent = pct + '%';
                    } else {
                        progressWrap.classList.add('hidden');
                    }
                }

                // Pending warning
                pendingWarn.classList.toggle('hidden', pending === 0);
                if (pending > 0) pendingText.textContent = `Menunggu ${pending} foto selesai diupload…`;
            }

            function showErrors(errs) {
                if (!errs.length) { errorBox.classList.add('hidden'); return; }
                errorList.innerHTML = errs.map(e => `<li>${e}</li>`).join('');
                errorBox.classList.remove('hidden');
            }

            // ── event listeners ──────────────────────────────────────
            pickBtn.addEventListener('click', e => { e.stopPropagation(); photoInput.click(); });
            photoInput.addEventListener('change', () => addFiles(photoInput.files));
            clearAllBtn.addEventListener('click', () => {
                items.forEach(i => URL.revokeObjectURL(i.thumbUrl));
                document.querySelectorAll('input[name="temp_photos[]"]').forEach(el => el.remove());
                items = []; preview.innerHTML = '';
                listWrapper.classList.add('hidden');
                updateCounter();
            });
            dropzone.addEventListener('dragover',  e => { e.preventDefault(); dropzone.classList.add('border-orange-400','bg-orange-50'); });
            dropzone.addEventListener('dragleave', ()  => dropzone.classList.remove('border-orange-400','bg-orange-50'));
            dropzone.addEventListener('drop', e => {
                e.preventDefault(); dropzone.classList.remove('border-orange-400','bg-orange-50');
                addFiles(e.dataTransfer.files);
            });

            // ── block submit if uploads still in progress ─────────────
            window.__bgUploadReady = function () {
                const pending = items.filter(i => i.status === 'compressing' || i.status === 'uploading').length;
                if (pending > 0) {
                    pendingWarn.classList.remove('hidden');
                    pendingText.textContent = `Menunggu ${pending} foto selesai diupload…`;
                    pendingWarn.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    return false;
                }
                return true;
            };
        })();

        // ===== QUILL EDITOR =====
        let quillInstance = null;

        function log() {} // no-op in production

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
            // 1. Block if background uploads still in progress
            if (window.__bgUploadReady && !window.__bgUploadReady()) {
                event.preventDefault();
                return false;
            }

            // 2. Sync Quill description
            if (!syncQuillToField()) {
                event.preventDefault();
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


