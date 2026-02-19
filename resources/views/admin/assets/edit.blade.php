@extends('admin.layouts.app')

@section('title', 'Edit Aset - GriyaOne')
@section('role', 'Edit Aset')

@section('content')
    <!-- Header -->
    <div class="mb-8 flex justify-between items-start fade-in">
        <div>
            <h2 class="text-3xl font-bold text-gray-900 mb-1">Edit Aset</h2>
            <p class="text-gray-600">Perbarui informasi aset: {{ $asset->title }}</p>
        </div>
        <a href="{{ route('assets.show', $asset) }}" class="text-red-600 hover:text-red-700 font-medium text-sm transition">← Kembali ke Detail</a>
    </div>

    <!-- Form -->
    <form action="{{ route('assets.update', $asset) }}" method="POST" enctype="multipart/form-data" class="fade-in" onsubmit="return handleFormSubmit(event)">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Form -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Informasi Dasar -->
                <div class="bg-white rounded-xl p-6 shadow-md">
                    <h3 class="text-lg font-bold text-gray-900 mb-6">Informasi Dasar</h3>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Judul Aset <span class="text-red-500">*</span></label>
                            <input type="text" name="title" value="{{ old('title', $asset->title) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-red-500 transition @error('title') border-red-500 @enderror">
                            @error('title')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <label class="block text-sm font-semibold text-gray-700">Deskripsi <span class="text-red-500">*</span></label>
                            </div>
                            <div id="editor-container" class="bg-white border border-gray-300 rounded-lg overflow-hidden focus-within:border-red-500 transition @error('description') border-red-500 @enderror" style="min-height: 300px; position: relative;">
                                {!! old('description', $asset->description) !!}
                            </div>
                            <input type="hidden" name="description" id="description_content" value="">
                            @error('description')
                                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Kategori <span class="text-red-500">*</span></label>
                                <select name="category" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-red-500 transition @error('category') border-red-500 @enderror">
                                    <option value="Bank Cessie" @selected(old('category', $asset->category) === 'Bank Cessie')>Bank Cessie</option>
                                    <option value="AYDA" @selected(old('category', $asset->category) === 'AYDA')>AYDA</option>
                                    <option value="Lelang" @selected(old('category', $asset->category) === 'Lelang')>Lelang</option>
                                </select>
                                @error('category')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-red-500 transition @error('status') border-red-500 @enderror">
                                    <option value="Available" @selected(old('status', $asset->status) === 'Available')>Available</option>
                                    <option value="Sold Out" @selected(old('status', $asset->status) === 'Sold Out')>Sold Out</option>
                                </select>
                                @error('status')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Lokasi</label>
                                <input type="text" name="location" value="{{ old('location', $asset->location) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-red-500 transition @error('location') border-red-500 @enderror">
                                @error('location')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Foto Existing -->
                @if($asset->photos && count($asset->photos) > 0)
                    <div class="bg-white rounded-xl p-6 shadow-md">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Foto Saat Ini</h3>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4" id="existing-photos-container">
                            @foreach($asset->photos as $index => $photo)
                                <div class="relative group photo-item" data-photo="{{ $photo }}">
                                    <img src="{{ asset('storage/' . $photo) }}" class="w-full h-32 object-contain bg-gray-100 rounded-lg border border-gray-200">
                                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/40 rounded-lg transition flex items-center justify-center">
                                        <button type="button" class="hidden group-hover:block bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm font-semibold" onclick="deleteExistingPhoto('{{ $photo }}', this)">Hapus</button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
                <input type="hidden" name="deleted_photos[]" id="deleted_photos" value="">

                <!-- Upload Foto Tambahan -->
                <div class="bg-white rounded-xl p-6 shadow-md">
                    <h3 class="text-lg font-bold text-gray-900 mb-6">Tambah Foto Baru</h3>

                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center cursor-pointer hover:border-red-500 hover:bg-red-50 transition" id="photo-dropzone">
                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <p class="text-gray-600 font-medium mb-1">Drag dan drop foto baru di sini</p>
                        <input type="file" name="photos[]" multiple accept="image/*" class="hidden" id="photo-input">
                        <button type="button" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium text-sm transition mt-3" onclick="document.getElementById('photo-input').click()">Pilih Foto</button>
                    </div>

                    <div id="photo-preview" class="mt-6 grid grid-cols-2 md:grid-cols-3 gap-4"></div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex gap-4">
                    <button type="submit" class="flex-1 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white px-6 py-3 rounded-lg font-semibold transition-all duration-200 shadow-md hover:shadow-lg">
                        ✓ Perbarui Aset
                    </button>
                    <a href="{{ route('assets.show', $asset) }}" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-900 px-6 py-3 rounded-lg font-semibold transition text-center">
                        Batalkan
                    </a>
                </div>
            </div>

            <!-- Info Card -->
            <div class="lg:col-span-1">
                <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-xl p-6 border border-red-200 sticky top-24">
                    <h4 class="font-bold text-gray-900 mb-4">Tips Edit</h4>
                    <div class="space-y-3 text-sm text-gray-700">
                        <div>
                            <p class="font-semibold text-gray-900">Informasi Akurat</p>
                            <p class="text-xs text-gray-600 mt-1">Pastikan semua informasi aset selalu akurat dan terkini.</p>
                        </div>
                        <div class="border-t border-red-200 pt-3">
                            <p class="font-semibold text-gray-900">Deskripsi Lengkap</p>
                            <p class="text-xs text-gray-600 mt-1">Jelaskan detail aset dengan deskripsi yang komprehensif.</p>
                        </div>
                        <div class="border-t border-red-200 pt-3">
                            <p class="font-semibold text-gray-900">Foto Berkualitas</p>
                            <p class="text-xs text-gray-600 mt-1">Tambah foto dengan kondisi terbaru properti.</p>
                        </div>
                        <div class="border-t border-red-200 pt-3">
                            <p class="font-semibold text-gray-900">Status Realtime</p>
                            <p class="text-xs text-gray-600 mt-1">Update status untuk mencerminkan kondisi aset.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script>
        const dropzone = document.getElementById('photo-dropzone');
        const photoInput = document.getElementById('photo-input');
        const photoPreview = document.getElementById('photo-preview');

        // Drag and drop
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

        function updatePreview() {
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
                            <button type="button" class="hidden group-hover:block bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm font-semibold" onclick="removePhoto(${index})">🗑️ Hapus</button>
                        </div>
                    `;
                    photoPreview.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
        }

        function removePhoto(index) {
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

        // Delete existing photo
        function deleteExistingPhoto(photoPath, button) {
            const photoItem = button.closest('.photo-item');
            const deletedPhotosInput = document.querySelector('input[name="deleted_photos[]"]');
            let deletedPhotos = [];

            // Collect all deleted photos
            document.querySelectorAll('input[value]').forEach(input => {
                if (input.name === 'deleted_photos[]' && input.value) {
                    deletedPhotos.push(input.value);
                }
            });

            // Add current photo to deleted list
            if (!deletedPhotos.includes(photoPath)) {
                deletedPhotos.push(photoPath);
            }

            // Create hidden inputs for all deleted photos
            const container = document.getElementById('existing-photos-container');
            if (container) {
                // Remove existing hidden inputs
                document.querySelectorAll('input[name="deleted_photos[]"]').forEach(input => {
                    if (input.value) input.remove();
                });

                // Add new hidden inputs for all deleted photos
                deletedPhotos.forEach(photo => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'deleted_photos[]';
                    input.value = photo;
                    container.parentElement.appendChild(input);
                });
            }

            // Fade out and remove the photo item
            photoItem.style.transition = 'opacity 0.3s ease-out';
            photoItem.style.opacity = '0';
            setTimeout(() => {
                photoItem.remove();

                // Show message if all photos deleted
                const remainingPhotos = document.querySelectorAll('.photo-item');
                if (remainingPhotos.length === 0) {
                    const container = document.getElementById('existing-photos-container');
                    if (container) {
                        container.innerHTML = '<p class="text-gray-600 italic">Semua foto telah dihapus</p>';
                    }
                }
            }, 300);
        }

        // Copy description from Quill editor
        function copyEditDescription() {
            const quillEditor = document.querySelector('.ql-editor');
            const buttonText = document.getElementById('edit-copy-button-text');
            const originalText = buttonText.textContent;

            if (quillEditor) {
                const text = (quillEditor.innerText || quillEditor.textContent || '').trim();

                if (!text) {
                    alert('Tidak ada teks untuk disalin');
                    return;
                }

                if (navigator.clipboard && window.isSecureContext) {
                    navigator.clipboard.writeText(text).then(() => {
                        buttonText.textContent = '✓ Tersalin!';
                        setTimeout(() => {
                            buttonText.textContent = originalText;
                        }, 2000);
                    }).catch(err => {
                        fallbackCopy(text);
                    });
                } else {
                    fallbackCopy(text);
                }
            }

            function fallbackCopy(text) {
                const textarea = document.createElement('textarea');
                textarea.value = text;
                textarea.style.position = 'fixed';
                textarea.style.left = '-9999px';
                document.body.appendChild(textarea);

                try {
                    textarea.select();
                    document.execCommand('copy');
                    buttonText.textContent = '✓ Tersalin!';
                    setTimeout(() => {
                        buttonText.textContent = originalText;
                    }, 2000);
                } catch (err) {
                    alert('Gagal menyalin: ' + err.message);
                }

                document.body.removeChild(textarea);
            }
        }
    </script>
@endsection
