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
                fileName.textContent = `✓ ${input.files[0].name} (${size} MB)`;
                fileName.className = 'mt-2 text-sm text-green-600 font-medium';
            }
        }
    </script>
@endsection
