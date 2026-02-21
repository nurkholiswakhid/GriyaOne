@extends('marketing.layouts.app')

@section('title', 'Materi PDF - Marketing')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Materi PDF</h1>
        <p class="text-gray-600 mt-2">Akses koleksi materi pembelajaran dalam format PDF untuk peningkatan kompetensi</p>
    </div>

    <!-- Filter & Search -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <form method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Search Input -->
                <div>
                    <label for="search" class="block text-sm font-semibold text-gray-700 mb-2">Cari Materi</label>
                    <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="Cari judul atau deskripsi..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500/20 transition bg-gray-50 focus:bg-white">
                </div>

                <!-- Category Filter -->
                <div>
                    <label for="category" class="block text-sm font-semibold text-gray-700 mb-2">Filter Kategori</label>
                    <select id="category" name="category" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500/20 transition bg-gray-50 focus:bg-white">
                        <option value="">-- Semua Kategori --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
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
                <a href="{{ route('marketing.materi') }}" class="px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Materi Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        @forelse($materials as $material)
        <div onclick="openMaterialModal({{ json_encode(['id' => $material->id, 'title' => $material->title, 'description' => $material->description, 'category' => $material->category?->name ?? 'Umum', 'created_at' => $material->created_at->format('d M Y'), 'file_path' => $material->file_path, 'thumbnail' => $material->thumbnail]) }})" class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition group cursor-pointer">
            <!-- Thumbnail -->
            <div class="relative h-40 bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center overflow-hidden">
                @if($material->thumbnail)
                    <img src="{{ asset('storage/' . $material->thumbnail) }}" alt="{{ $material->title }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                @else
                    <div class="text-center">
                        <svg class="w-16 h-16 text-white mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        <p class="text-white text-sm font-medium mt-2">PDF</p>
                    </div>
                @endif
            </div>

            <!-- Content -->
            <div class="p-4">
                <h3 class="font-semibold text-gray-900 line-clamp-2 mb-2">{{ $material->title }}</h3>
                <p class="text-gray-600 text-sm line-clamp-2 mb-3">{{ $material->description ?? 'Materi pembelajaran' }}</p>

                <!-- Category Badge -->
                <div class="flex items-center gap-2 mb-4">
                    @if($material->category)
                        <span class="inline-block px-2 py-1 bg-orange-100 text-orange-600 text-xs font-semibold rounded">{{ $material->category->name }}</span>
                    @else
                        <span class="inline-block px-2 py-1 bg-gray-100 text-gray-600 text-xs font-semibold rounded">Umum</span>
                    @endif
                </div>

                <!-- Meta Info -->
                <div class="flex items-center justify-between text-xs text-gray-500 mb-4">
                    <span>{{ $material->created_at->format('d M Y') }}</span>
                </div>

                <!-- Action Button -->
                @if($material->file_path)
                    <a href="{{ asset('storage/' . $material->file_path) }}" target="_blank" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white rounded-lg transition font-medium text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Lihat / Download
                    </a>
                @else
                    <button disabled class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 bg-gray-300 text-gray-500 rounded-lg font-medium text-sm cursor-not-allowed">
                        File tidak tersedia
                    </button>
                @endif
            </div>
        </div>
        @empty
        <div class="col-span-full">
            <div class="bg-white rounded-lg shadow p-12 text-center">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                </svg>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Tidak ada materi ditemukan</h3>
                <p class="text-gray-600">Coba ubah filter pencarian Anda</p>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($materials->hasPages())
    <div class="mt-8">
        {{ $materials->links() }}
    </div>
    @endif

    <!-- Statistics -->

</div>

<!-- Material Detail Modal -->
<div id="materialModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4" style="background-color: rgba(0, 0, 0, 0.5);">
    <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[95vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <h2 id="modalTitle" class="text-2xl font-bold text-gray-900">Judul Materi</h2>
            <button onclick="closeMaterialModal()" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6 space-y-4">
            <!-- Category & Date -->
            <div class="flex items-center gap-4">
                <span id="modalCategory" class="inline-block px-3 py-1 bg-orange-100 text-orange-600 text-sm font-semibold rounded">Kategori</span>
                <span id="modalCreatedAt" class="text-sm text-gray-500">Tanggal</span>
            </div>

            <!-- Description -->
            <div>
                <h3 class="text-sm font-semibold text-gray-700 mb-2">Deskripsi</h3>
                <p id="modalDescription" class="text-gray-600 line-clamp-6">Deskripsi materi</p>
            </div>

            <!-- PDF Preview -->
            <div id="pdfContainer" class="hidden">
                <iframe id="pdfViewer" src="" class="w-full h-96 border border-gray-300 rounded-lg"></iframe>
            </div>

            <!-- Thumbnail Preview -->
            <div id="thumbnailContainer" class="hidden">
                <h3 class="text-sm font-semibold text-gray-700 mb-2">Pratinjau</h3>
                <img id="modalThumbnail" src="" alt="Thumbnail" class="w-full h-48 object-cover rounded-lg">
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-3 pt-6 border-t border-gray-200">
                <a id="downloadBtn" href="#" target="_blank" class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-3 bg-orange-600 hover:bg-orange-700 text-white rounded-lg transition font-semibold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Lihat / Download PDF
                </a>
                <button onclick="closeMaterialModal()" class="flex-1 px-4 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition font-semibold">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function openMaterialModal(data) {
    const modal = document.getElementById('materialModal');
    document.getElementById('modalTitle').textContent = data.title;
    document.getElementById('modalCategory').textContent = data.category;
    document.getElementById('modalCreatedAt').textContent = data.created_at;
    document.getElementById('modalDescription').textContent = data.description || 'Tidak ada deskripsi';

    // Set download button
    const downloadBtn = document.getElementById('downloadBtn');
    if (data.file_path) {
        downloadBtn.href = '{{ asset('storage/') }}' + '/' + data.file_path;
        downloadBtn.style.display = 'flex';
    } else {
        downloadBtn.style.display = 'none';
    }

    // Set PDF preview if available
    const pdfContainer = document.getElementById('pdfContainer');
    const thumbnailContainer = document.getElementById('thumbnailContainer');

    if (data.file_path) {
        document.getElementById('pdfViewer').src = '{{ asset('storage/') }}' + '/' + data.file_path;
        pdfContainer.classList.remove('hidden');
        thumbnailContainer.classList.add('hidden');
    } else if (data.thumbnail) {
        document.getElementById('modalThumbnail').src = '{{ asset('storage/') }}' + '/' + data.thumbnail;
        thumbnailContainer.classList.remove('hidden');
        pdfContainer.classList.add('hidden');
    } else {
        pdfContainer.classList.add('hidden');
        thumbnailContainer.classList.add('hidden');
    }

    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';

    // Add padding to compensate for scrollbar
    const scrollbarWidth = window.innerWidth - document.documentElement.clientWidth;
    document.body.style.paddingRight = scrollbarWidth + 'px';
}

function closeMaterialModal() {
    const modal = document.getElementById('materialModal');
    modal.classList.add('hidden');
    document.body.style.overflow = '';
    document.body.style.paddingRight = '';
}

// Close modal when clicking outside
document.getElementById('materialModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeMaterialModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeMaterialModal();
    }
});
</script>
@endsection


