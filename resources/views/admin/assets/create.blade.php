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

                        <!-- Alamat -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat </label>
                            <input type="text" name="location" value="{{ old('location') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-red-500 transition @error('location') border-red-500 @enderror" placeholder="Jakarta Selatan, DKI Jakarta">
                            @error('location')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Upload Foto -->
                <div class="bg-white rounded-xl p-6 shadow-md">
                    <h3 class="text-lg font-bold text-gray-900 mb-6">Upload Foto</h3>

                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center cursor-pointer hover:border-red-500 hover:bg-red-50 transition" id="photo-dropzone">
                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <p class="text-gray-600 font-medium mb-1">Drag dan drop foto di sini</p>
                        <p class="text-gray-500 text-sm mb-3">atau</p>
                        <input type="file" name="photos[]" multiple accept="image/*" class="hidden" id="photo-input">
                        <button type="button" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium text-sm transition" onclick="document.getElementById('photo-input').click()">Pilih Foto</button>
                    </div>

                    <div id="photo-preview" class="mt-6 grid grid-cols-2 md:grid-cols-3 gap-4"></div>
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
                            <p class="text-xs text-gray-600 mt-1">Unggah minimal 3 foto dengan sudut berbeda untuk hasil terbaik.</p>
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
        // ===== PHOTO UPLOAD HANDLING =====
        const dropzone = document.getElementById('photo-dropzone');
        const photoInput = document.getElementById('photo-input');
        const photoPreview = document.getElementById('photo-preview');

        if (dropzone && photoInput && photoPreview) {
            dropzone.addEventListener('dragover', (e) => {
                e.preventDefault();
                dropzone.classList.add('border-red-500', 'bg-red-50');
            });

            dropzone.addEventListener('dragleave', () => {
                dropzone.classList.remove('border-red-500', 'bg-red-50');
            });

            dropzone.addEventListener('drop', (e) => {
                e.preventDefault();
                dropzone.classList.remove('border-red-500', 'bg-red-50');
                photoInput.files = e.dataTransfer.files;
                updatePreview();
            });

            photoInput.addEventListener('change', updatePreview);
        }

        function updatePreview() {
            const photoPreview = document.getElementById('photo-preview');
            const photoInput = document.getElementById('photo-input');
            if (!photoPreview || !photoInput) return;

            photoPreview.innerHTML = '';
            const files = Array.from(photoInput.files);

            files.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const div = document.createElement('div');
                    div.className = 'relative group';
                    div.innerHTML = `
                        <img src="${e.target.result}" class="w-full h-32 object-contain bg-gray-100 rounded-lg border border-gray-200">
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/40 rounded-lg transition flex items-center justify-center">
                            <button type="button" class="hidden group-hover:block bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm font-semibold" onclick="removePhoto(${index})"> Hapus</button>
                        </div>
                    `;
                    photoPreview.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
        }

        function removePhoto(index) {
            const photoInput = document.getElementById('photo-input');
            if (!photoInput) return;

            const files = Array.from(photoInput.files);
            files.splice(index, 1);
            const dt = new DataTransfer();
            files.forEach(file => dt.items.add(file));
            photoInput.files = dt.files;
            updatePreview();
        }

        // ===== QUILL EDITOR =====
        let quillInstance = null;
        const DEBUG = true;

        function log(...args) {
            if (DEBUG) console.log('[Quill Debug]', ...args);
        }

        function initializeQuill() {
            const editorContainer = document.getElementById('editor-container');
            if (!editorContainer) {
                console.error('❌ [FATAL] Editor container not found!');
                return;
            }

            if (!window.Quill) {
                console.error('❌ [FATAL] Quill library not loaded!');
                return;
            }

            log('🚀 Starting Quill initialization...');
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

                log('✅ Quill instance created successfully');

                if (initialContent && initialContent.length > 0) {
                    try {
                        quillInstance.root.innerHTML = initialContent;
                        log('✅ Initial content restored to editor');
                    } catch (err) {
                        console.error('Error restoring content:', err);
                    }
                }

                quillInstance.on('text-change', () => {
                    const currentText = quillInstance.getText().trim();
                    const currentHtml = quillInstance.root.innerHTML;
                    log('📝 Editor changed - Text length:', currentText.length);
                });

            } catch (err) {
                console.error('❌ Error initializing Quill:', err);
            }
        }

        function syncQuillToField() {
            log('🔄 Syncing Quill to hidden field...');

            if (!quillInstance) {
                console.error('❌ Quill instance not available!');
                return false;
            }

            const field = document.getElementById('description_content');
            if (!field) {
                console.error('❌ Hidden field #description_content not found!');
                return false;
            }

            const htmlContent = quillInstance.root.innerHTML;
            const textContent = quillInstance.getText().trim();

            log('Quill content:');
            log('  - Text length:', textContent.length);
            log('  - HTML length:', htmlContent.length);
            log('  - Text preview:', textContent.substring(0, 50));

            if (textContent.length === 0 || textContent === '') {
                console.error('❌ Quill editor is EMPTY!');
                return false;
            }

            field.value = htmlContent;

            log('✅ Field updated:');
            log('  - Field.value length:', field.value.length);
            log('  - Field.value:', field.value.substring(0, 100));

            setTimeout(() => {
                log('🔍 Verification:');
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
                log('❌ SYNC FAILED - Preventing form submission');
                alert('⚠️ Deskripsi kosong! Silakan isi deskripsi terlebih dahulu.');
                return false;
            }

            log('✅ SYNC SUCCESSFUL - Allowing form submission');
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
                log('⚠️ Quill not initialized on DOMContentLoaded, trying again on page load');
                initializeQuill();
            } else {
                log('✅ Quill already initialized');
            }
        });
    </script>
@endsection
