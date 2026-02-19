@extends('admin.layouts.app')

@section('title', 'Edit Video - GriyaOne')
@section('role', 'Edit Video Pembelajaran')

@section('content')
    <!-- Header -->
    <div class="mb-8 flex justify-between items-start fade-in">
        <div>
            <h2 class="text-3xl font-bold text-gray-900 mb-1">Edit Video Pembelajaran</h2>
            <p class="text-gray-600">Perbarui: {{ $content->title }}</p>
        </div>
        <a href="{{ route('contents.show', $content) }}" class="text-red-600 hover:text-red-700 font-medium text-sm transition">← Kembali ke Detail</a>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4 fade-in">
            <p class="text-green-800 font-medium">✓ {{ session('success') }}</p>
        </div>
    @endif

    <!-- Form -->
    <form action="{{ route('contents.update', $content) }}" method="POST" enctype="multipart/form-data" class="fade-in">
        @csrf
        @method('PUT')
        <input type="hidden" name="type" value="Video">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Form -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Informasi Video -->
                <div class="bg-white rounded-xl p-6 shadow-md">
                    <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                        Informasi Video
                    </h3>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Judul Video <span class="text-red-500">*</span></label>
                            <input type="text" name="title" value="{{ old('title', $content->title) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-red-500 transition @error('title') border-red-500 @enderror">
                            @error('title')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi Video</label>
                            <textarea name="description" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-red-500 transition @error('description') border-red-500 @enderror">{{ old('description', $content->description) }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>
                </div>

                <!-- Link YouTube -->
                <div class="bg-white rounded-xl p-6 shadow-md">
                    <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-red-600" viewBox="0 0 24 24" fill="currentColor"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                        Link YouTube
                    </h3>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">URL Video YouTube <span class="text-red-500">*</span></label>
                            <input type="url" name="file_path" value="{{ old('file_path', $content->file_path) }}" id="youtube_url" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-red-500 transition @error('file_path') border-red-500 @enderror" placeholder="https://www.youtube.com/watch?v=dQw4w9WgXcQ">
                            @error('file_path')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- YouTube Preview -->
                        <div id="youtube-preview" class="{{ old('file_path', $content->file_path) && $content->getYoutubeId() ? '' : 'hidden' }}">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Preview Video</label>
                            <div class="rounded-xl overflow-hidden border border-gray-200 bg-black aspect-video shadow-sm">
                                <iframe id="youtube-iframe" class="w-full h-full" src="{{ $content->getYoutubeEmbedUrl() ?? '' }}" frameborder="0" allowfullscreen allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"></iframe>
                            </div>
                            <div class="mt-2 flex items-center gap-2">
                                <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                <p class="text-green-600 text-xs font-medium">Video berhasil terdeteksi</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Current Thumbnail -->
                @if($content->thumbnail)
                    <div class="bg-white rounded-xl p-6 shadow-md">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold text-gray-900">Thumbnail Saat Ini</h3>
                            <form action="{{ route('contents.deleteThumbnail', $content) }}" method="POST" data-confirm="Yakin ingin menghapus thumbnail?">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center gap-1.5 text-red-600 hover:text-red-700 text-xs font-medium bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    Hapus Thumbnail
                                </button>
                            </form>
                        </div>
                        <img src="{{ asset('storage/' . $content->thumbnail) }}" alt="{{ $content->title }}" class="h-36 rounded-lg border border-gray-200 object-cover">
                    </div>
                @endif

                <!-- Thumbnail Upload -->
                <div class="bg-white rounded-xl p-6 shadow-md">
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Update Thumbnail (Opsional)</h3>
                    <p class="text-gray-500 text-xs mb-4">Upload gambar baru untuk mengganti thumbnail saat ini</p>

                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center cursor-pointer hover:border-red-500 hover:bg-red-50 transition" id="thumbnail-dropzone">
                        <svg class="w-10 h-10 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <p class="text-gray-600 font-medium text-sm mb-1">Drag & drop atau klik untuk upload</p>
                        <input type="file" name="thumbnail" accept="image/*" class="hidden" id="thumbnail-input">
                        <button type="button" class="mt-2 bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-1.5 rounded-lg font-medium text-xs transition" onclick="document.getElementById('thumbnail-input').click()">Pilih Gambar</button>
                    </div>
                    <div id="thumbnail-preview" class="mt-4"></div>
                </div>

                <!-- Publish Settings -->
                <div class="bg-white rounded-xl p-6 shadow-md">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $content->is_published)) class="w-4 h-4 text-red-600 rounded focus:ring-2 focus:ring-red-500">
                        <div>
                            <span class="text-sm font-semibold text-gray-700">Publikasikan video ini</span>
                            <p class="text-gray-500 text-xs mt-0.5">Video hanya terlihat oleh tim marketing jika dipublikasikan</p>
                        </div>
                    </label>
                </div>

                <!-- Submit Buttons -->
                <div class="flex gap-4">
                    <button type="submit" class="flex-1 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white px-6 py-3 rounded-lg font-semibold transition-all duration-200 shadow-md hover:shadow-lg">
                        ✓ Perbarui Video
                    </button>
                    <a href="{{ route('contents.show', $content) }}" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-900 px-6 py-3 rounded-lg font-semibold transition text-center">
                        Batalkan
                    </a>
                </div>
            </div>

            <!-- Info Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-xl p-6 border border-red-200 sticky top-24">
                    <h4 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Info Video
                    </h4>
                    <div class="space-y-3 text-sm text-gray-700">
                        <div>
                            <p class="text-xs text-gray-500">Dibuat</p>
                            <p class="font-semibold text-gray-900">{{ $content->created_at->format('d M Y H:i') }}</p>
                        </div>
                        <div class="border-t border-red-200 pt-3">
                            <p class="text-xs text-gray-500">Terakhir diupdate</p>
                            <p class="font-semibold text-gray-900">{{ $content->updated_at->format('d M Y H:i') }}</p>
                        </div>
                        <div class="border-t border-red-200 pt-3">
                            <p class="text-xs text-gray-500">Status</p>
                            @if($content->is_published)
                                <span class="inline-flex items-center bg-green-100 text-green-700 px-2.5 py-1 rounded-full text-xs font-semibold mt-1">Dipublikasikan</span>
                            @else
                                <span class="inline-flex items-center bg-gray-100 text-gray-700 px-2.5 py-1 rounded-full text-xs font-semibold mt-1">Draft</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script>
        const youtubeUrl = document.getElementById('youtube_url');
        const youtubePreview = document.getElementById('youtube-preview');
        const youtubeIframe = document.getElementById('youtube-iframe');
        const dropzone = document.getElementById('thumbnail-dropzone');
        const thumbnailInput = document.getElementById('thumbnail-input');
        const thumbnailPreview = document.getElementById('thumbnail-preview');

        function getYoutubeId(url) {
            if (!url) return null;
            const patterns = [
                /(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]{11})/,
                /^([a-zA-Z0-9_-]{11})$/
            ];
            for (const pattern of patterns) {
                const match = url.match(pattern);
                if (match) return match[1];
            }
            return null;
        }

        let debounceTimer;
        youtubeUrl.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                const videoId = getYoutubeId(this.value);
                if (videoId) {
                    youtubeIframe.src = `https://www.youtube.com/embed/${videoId}?origin=${window.location.origin}`;
                    youtubePreview.classList.remove('hidden');
                } else {
                    youtubeIframe.src = '';
                    youtubePreview.classList.add('hidden');
                }
            }, 500);
        });

        dropzone.addEventListener('dragover', (e) => { e.preventDefault(); dropzone.classList.add('border-red-500', 'bg-red-50'); });
        dropzone.addEventListener('dragleave', () => { dropzone.classList.remove('border-red-500', 'bg-red-50'); });
        dropzone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropzone.classList.remove('border-red-500', 'bg-red-50');
            thumbnailInput.files = e.dataTransfer.files;
            updateThumbnailPreview();
        });
        thumbnailInput.addEventListener('change', updateThumbnailPreview);

        function updateThumbnailPreview() {
            thumbnailPreview.innerHTML = '';
            if (thumbnailInput.files.length > 0) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    thumbnailPreview.innerHTML = `
                        <div class="relative inline-block">
                            <img src="${e.target.result}" class="h-28 rounded-lg border border-gray-200 object-cover">
                            <button type="button" class="absolute -top-2 -right-2 bg-red-600 hover:bg-red-700 text-white w-6 h-6 rounded-full text-xs font-bold flex items-center justify-center shadow" onclick="document.getElementById('thumbnail-input').value=''; updateThumbnailPreview()">✕</button>
                        </div>
                    `;
                };
                reader.readAsDataURL(thumbnailInput.files[0]);
            }
        }
    </script>
@endsection
