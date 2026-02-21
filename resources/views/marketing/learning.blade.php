@extends('marketing.layouts.app')

@section('title', 'Media Pembelajaran - Marketing')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Video Media Pembelajaran</h1>
        <p class="text-gray-600 mt-2">Tingkatkan kompetensi tim dengan video pembelajaran berkualitas tinggi</p>
    </div>

    <!-- Filter & Search -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <form action="{{ route('marketing.learning') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Search Input -->
                <div>
                    <label for="search" class="block text-sm font-semibold text-gray-700 mb-2">Cari Video</label>
                    <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="Cari judul atau deskripsi..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500/20 transition bg-gray-50 focus:bg-white">
                </div>

                <!-- Sort -->
                <div>
                    <label for="sort" class="block text-sm font-semibold text-gray-700 mb-2">Urutkan</label>
                    <select id="sort" name="sort" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500/20 transition bg-gray-50 focus:bg-white">
                        <option value="latest" {{ request('sort', 'latest') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama</option>
                        <option value="title_asc" {{ request('sort') == 'title_asc' ? 'selected' : '' }}>Judul (A-Z)</option>
                        <option value="title_desc" {{ request('sort') == 'title_desc' ? 'selected' : '' }}>Judul (Z-A)</option>
                    </select>
                </div>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="px-6 py-2 bg-orange-600 hover:bg-orange-700 text-white font-semibold rounded-lg transition">
                    Cari
                </button>
                <a href="{{ route('marketing.learning') }}" class="px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Video Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        @forelse($materials as $video)
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition flex flex-col cursor-pointer" onclick="openVideoModal('{{ $video->id }}', '{{ addslashes($video->title) }}', '{{ addslashes($video->description ?? '') }}', '{{ $video->getYoutubeEmbedUrl() }}')">
            <!-- Video Thumbnail/Embed Section -->
            <div class="h-48 bg-gradient-to-br from-red-400 to-red-600 flex items-center justify-center relative overflow-hidden group">
                @if($video->getThumbnailUrl())
                    <img src="{{ $video->getThumbnailUrl() }}" alt="{{ $video->title }}" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 flex items-center justify-center transition">
                        <svg class="w-12 h-12 text-white opacity-0 group-hover:opacity-100 transition" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M8 5v14l11-7z"/>
                        </svg>
                    </div>
                @else
                    <svg class="w-16 h-16 text-white opacity-80" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M8 5v14l11-7z"/>
                    </svg>
                @endif
            </div>

            <!-- Content Info -->
            <div class="p-4 flex flex-col flex-grow">
                <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2">{{ $video->title }}</h3>

                <!-- Description -->
                <p class="text-sm text-gray-600 mb-4 line-clamp-2 flex-grow">{{ $video->description ?? 'Video pembelajaran' }}</p>

                <!-- Action Button -->
                <div onclick="event.stopPropagation();">
                    <a href="{{ $video->getYoutubeWatchUrl() }}" target="_blank" class="w-full px-3 py-2 bg-red-600 text-white text-sm font-semibold rounded-lg hover:bg-red-700 transition flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M8 5v14l11-7z"/>
                        </svg>
                        Tonton Video
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-12">
            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="currentColor" viewBox="0 0 24 24">
                <path d="M8 5v14l11-7z"/>
            </svg>
            <p class="text-gray-500 font-medium">Tidak ada video yang sesuai dengan filter</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mb-8">
        {{ $materials->links() }}
    </div>


</div>

<!-- Video Detail Modal -->
<div id="videoModal" class="fixed inset-0 hidden items-center justify-center z-50 p-4" style="background-color: rgb(0, 0, 0); display: none;">
    <div class="bg-white rounded-xl shadow-lg max-w-3xl w-full max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200 sticky top-0 bg-white">
            <h3 id="videoModalTitle" class="text-2xl font-bold text-gray-900"></h3>
            <button type="button" onclick="closeVideoModal()" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6 space-y-4">
            <!-- Video Embed -->
            <div class="w-full bg-black rounded-lg overflow-hidden">
                <iframe id="videoEmbed" width="100%" height="400" frameborder="0" allowfullscreen=""></iframe>
            </div>

            <!-- Video Description -->
            <div>
                <h4 class="text-lg font-semibold text-gray-900 mb-2">Deskripsi</h4>
                <p id="videoModalDescription" class="text-gray-700 leading-relaxed"></p>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-3 pt-4 border-t border-gray-200">
                <a id="videoWatchLink" href="#" target="_blank" class="flex-1 px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M8 5v14l11-7z"/>
                    </svg>
                    Tonton di YouTube
                </a>
                <button type="button" onclick="closeVideoModal()" class="flex-1 px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function openVideoModal(videoId, title, description, embedUrl) {
        const modal = document.getElementById('videoModal');

        // Parse the embed URL from the onclick attribute properly
        const titleDecoded = title.replace(/&#039;/g, "'").replace(/&quot;/g, '"');
        const descriptionDecoded = description.replace(/&#039;/g, "'").replace(/&quot;/g, '"');

        // Set modal content
        document.getElementById('videoModalTitle').textContent = titleDecoded;
        document.getElementById('videoModalDescription').textContent = descriptionDecoded || 'Tidak ada deskripsi';

        // Set embed iframe
        const iframe = document.getElementById('videoEmbed');
        iframe.src = embedUrl;

        // Set watch link - extract video ID from embed URL
        const videoIdMatch = embedUrl.match(/\/embed\/([a-zA-Z0-9_-]{11})/);
        if (videoIdMatch) {
            document.getElementById('videoWatchLink').href = `https://www.youtube.com/watch?v=${videoIdMatch[1]}`;
        }

        // Prevent body scroll and compensate for scrollbar
        const scrollbarWidth = window.innerWidth - document.documentElement.clientWidth;
        document.body.style.overflow = 'hidden';
        document.body.style.paddingRight = scrollbarWidth + 'px';

        // Show modal
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        modal.style.display = 'flex';
    }

    function closeVideoModal() {
        const modal = document.getElementById('videoModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        modal.style.display = 'none';

        // Restore body scroll
        document.body.style.overflow = 'auto';
        document.body.style.paddingRight = '0';

        // Stop video playback
        const iframe = document.getElementById('videoEmbed');
        iframe.src = '';
    }

    // Close modal when clicking outside
    document.getElementById('videoModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeVideoModal();
        }
    });

    // Close modal on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeVideoModal();
        }
    });
</script>
@endsection


