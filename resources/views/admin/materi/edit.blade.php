@extends('admin.layouts.app')

@section('title', 'Edit Materi PDF - GriyaOne')
@section('role', 'Edit Materi PDF')

@section('content')
    <div class="max-w-3xl mx-auto">
        <!-- Header -->
        <div class="mb-8 fade-in">
            <a href="{{ route('materi.index') }}" class="inline-flex items-center gap-2 text-red-600 hover:text-red-700 font-medium mb-4">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                Kembali
            </a>
            <h2 class="text-3xl font-bold text-gray-900 mb-2">Edit Materi PDF</h2>
            <p class="text-gray-600">Perbarui informasi materi pembelajaran</p>
        </div>

        <!-- Form -->
        <form action="{{ route('materi.update', $material) }}" method="POST" enctype="multipart/form-data" class="fade-in">
            @csrf
            @method('PUT')
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="p-8">
                    <!-- Title -->
                    <div class="mb-6">
                        <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">Judul Materi *</label>
                        <input type="text" id="title" name="title" value="{{ old('title', $material->title) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500/20 transition bg-gray-50 focus:bg-white @error('title') border-red-500 @enderror" placeholder="Masukkan judul materi">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="mb-6">
                        <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi</label>
                        <textarea id="description" name="description" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500/20 transition bg-gray-50 focus:bg-white @error('description') border-red-500 @enderror" placeholder="Jelaskan isi materi ini...">{{ old('description', $material->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Category -->
                    <div class="mb-6">
                        <label for="category_id" class="block text-sm font-semibold text-gray-700 mb-2">Kategori *</label>
                        <div class="flex items-end gap-3">
                            <div class="flex-1">
                                <select id="category_id" name="category_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500/20 transition bg-gray-50 focus:bg-white @error('category_id') border-red-500 @enderror">
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $material->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="button"
                                onclick="openAddCategoryModal()"
                                class="bg-orange-600 hover:bg-orange-700 text-white font-semibold py-2 px-4 rounded-lg transition whitespace-nowrap">
                                + Kategori Baru
                            </button>
                        </div>
                        @error('category_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Thumbnail -->
                    <div class="mb-6">
                        <label for="thumbnail" class="block text-sm font-semibold text-gray-700 mb-2">Gambar Thumbnail</label>
                        <div class="relative">
                            <input type="file" id="thumbnail" name="thumbnail" accept="image/*" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500/20 transition bg-gray-50 focus:bg-white @error('thumbnail') border-red-500 @enderror" onchange="previewImage(this)">
                            <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG. Ukuran maksimal: 2MB (Kosongkan jika tidak perlu diubah)</p>
                        </div>

                        <div id="imagePreview" class="mt-4 hidden">
                            <img id="previewImg" src="" alt="Preview" class="max-w-xs h-auto rounded-lg border border-gray-200">
                        </div>
                        @error('thumbnail')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- PDF File -->
                    <div class="mb-6">
                        <label for="file_path" class="block text-sm font-semibold text-gray-700 mb-2">File Materi (PDF)</label>
                        <div class="relative">
                            <input type="file" id="file_path" name="file_path" accept=".pdf" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500/20 transition bg-gray-50 focus:bg-white @error('file_path') border-red-500 @enderror" onchange="updateFileName(this)">
                            <p class="text-xs text-gray-500 mt-1">Format: PDF. Ukuran maksimal: 50MB (Kosongkan jika tidak perlu diubah)</p>
                        </div>
                        @if($material->file_path)
                            <div class="mt-4 bg-purple-50 p-3 rounded-lg border border-purple-200">
                                <p class="text-xs text-gray-600 mb-1">File saat ini:</p>
                                <a href="{{ asset('storage/' . $material->file_path) }}" target="_blank" class="inline-flex items-center gap-1 text-purple-600 hover:text-purple-700 font-medium text-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                    Lihat PDF
                                </a>
                            </div>
                        @endif
                        <p id="fileName" class="mt-2 text-sm text-gray-600"></p>
                        @error('file_path')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Publish Status -->
                    <div class="mb-6">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" id="is_published" name="is_published" value="1" {{ old('is_published', $material->is_published) ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-red-600">
                            <span class="text-sm font-medium text-gray-700">Publikasikan materi</span>
                        </label>
                        <p class="text-xs text-gray-500 mt-1 ml-7">Materi akan langsung terlihat oleh user jika dipublikasikan</p>
                    </div>

                    <!-- Submit -->
                    <div class="flex gap-3">
                        <button type="submit" class="flex-1 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-semibold py-3 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                            Perbarui Materi
                        </button>
                        <a href="{{ route('materi.index') }}" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 rounded-lg transition text-center">
                            Batal
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Add Category Modal -->
    <div id="addCategoryModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-xl shadow-lg max-w-md w-full">
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h3 class="text-xl font-bold text-gray-900">Tambah Kategori Baru</h3>
                <button type="button" onclick="closeAddCategoryModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form id="addCategoryForm" class="p-6 space-y-4">
                @csrf
                <!-- Name -->
                <div>
                    <label for="catName" class="block text-sm font-semibold text-gray-700 mb-2">Nama Kategori *</label>
                    <input type="text" id="catName" name="name" required placeholder="Contoh: Matematika" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500/20 transition bg-gray-50 focus:bg-white">
                    <span id="catNameError" class="mt-1 text-sm text-red-600 hidden"></span>
                </div>

                <!-- Buttons -->
                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="closeAddCategoryModal()" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2 rounded-lg transition">
                        Batal
                    </button>
                    <button type="submit" id="submitCategoryBtn" class="flex-1 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-semibold py-2 rounded-lg transition">
                        Buat Kategori
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('imagePreview');
                    const img = document.getElementById('previewImg');
                    img.src = e.target.result;
                    preview.classList.remove('hidden');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function updateFileName(input) {
            const fileName = document.getElementById('fileName');
            if (input.files && input.files[0]) {
                const size = (input.files[0].size / 1024 / 1024).toFixed(2);
                fileName.textContent = `${input.files[0].name} (${size} MB)`;
                fileName.className = 'mt-2 text-sm text-green-600 font-medium';
            }
        }

        // Modal Functions
        function openAddCategoryModal() {
            document.getElementById('addCategoryModal').classList.remove('hidden');
            document.getElementById('addCategoryModal').classList.add('flex');
            document.getElementById('addCategoryForm').reset();
            document.getElementById('catNameError').classList.add('hidden');
        }

        function closeAddCategoryModal() {
            document.getElementById('addCategoryModal').classList.add('hidden');
            document.getElementById('addCategoryModal').classList.remove('flex');
        }

        // Close modal when clicking outside
        document.getElementById('addCategoryModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeAddCategoryModal();
            }
        });

        // Handle form submission
        document.getElementById('addCategoryForm')?.addEventListener('submit', async function(e) {
            e.preventDefault();

            const btn = document.getElementById('submitCategoryBtn');
            const originalText = btn.textContent;
            btn.disabled = true;
            btn.textContent = 'Membuat...';

            const formData = new FormData(this);

            try {
                const response = await fetch('{{ route("categories.store") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'Accept': 'application/json',
                    },
                    body: formData
                });

                const data = await response.json();

                if (!response.ok) {
                    if (data.errors?.name) {
                        const errorElement = document.getElementById('catNameError');
                        errorElement.textContent = data.errors.name[0];
                        errorElement.classList.remove('hidden');
                    }
                    throw new Error(data.message || 'Terjadi kesalahan');
                }

                // Success - reload categories
                await reloadCategories();
                closeAddCategoryModal();
                showSuccessMessage('Kategori berhasil ditambahkan!');

            } catch (error) {
                console.error('Error:', error);
                alert('Error: ' + error.message);
            } finally {
                btn.disabled = false;
                btn.textContent = originalText;
            }
        });

        // Reload categories in select dropdown
        async function reloadCategories() {
            try {
                const response = await fetch('{{ route("materi.edit", $material) }}');
                const html = await response.text();

                // Extract the new select options from the response
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newSelect = doc.querySelector('#category_id');

                if (newSelect) {
                    const currentSelect = document.getElementById('category_id');
                    currentSelect.innerHTML = newSelect.innerHTML;
                }
            } catch (error) {
                console.error('Error reloading categories:', error);
            }
        }

        // Show success message (optional - requires notification system)
        function showSuccessMessage(message) {
            // You can implement a toast notification here
            console.log('Success:', message);
        }
    </script>
@endsection


