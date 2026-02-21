@extends('marketing.layouts.app')

@section('title', 'Informasi Terbaru - Marketing')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">📰 Informasi Terbaru</h1>
        <p class="text-gray-600 mt-2">Dapatkan update terkini tentang produk, promosi, dan pengembangan bisnis</p>
    </div>

    <!-- Filter & Search -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <form method="GET" class="space-y-4">
            <!-- Search & Category Row -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cari Informasi</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik judul atau kata kunci..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                    <select name="category" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                        <option value="">-- Semua Kategori --</option>
                        @php
                            $categories = \App\Models\InformationCategory::orderBy('name')->get();
                        @endphp
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Urutkan</label>
                    <select name="sort" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                        <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                        <option value="terlama" {{ request('sort') == 'terlama' ? 'selected' : '' }}>Terlama</option>
                        <option value="a-z" {{ request('sort') == 'a-z' ? 'selected' : '' }}>A - Z</option>
                        <option value="z-a" {{ request('sort') == 'z-a' ? 'selected' : '' }}>Z - A</option>
                    </select>
                </div>
            </div>

            <!-- Buttons Row -->
            <div class="flex items-center gap-3">
                <button type="submit" class="px-6 py-2 bg-orange-600 hover:bg-orange-700 text-white rounded-lg transition font-medium">
                    <svg class="w-4 h-4 inline -mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Cari
                </button>
                @if(request('search') || request('category') || request('sort') != 'terbaru')
                <a href="{{ route('marketing.informasi') }}" class="px-6 py-2 border border-gray-300 hover:bg-gray-50 text-gray-700 rounded-lg transition font-medium">
                    <svg class="w-4 h-4 inline -mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Reset
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Informasi List -->
    <div class="space-y-6 mb-8">
        @forelse($informations as $info)
        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition overflow-hidden">
            <div class="bg-gray-100 h-48 flex items-center justify-center border-b border-gray-200 overflow-hidden">
                @if($info->photo)
                    <img src="{{ asset('storage/' . $info->photo) }}" alt="{{ $info->title }}" class="w-full h-full object-cover">
                @else
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                @endif
            </div>
            <div class="p-6">
                <!-- Header -->
                <div class="flex items-start justify-between gap-4 mb-4">
                    <div class="flex-1">
                        <h3 class="text-xl font-bold text-gray-900 mb-2 line-clamp-2">{{ $info->title }}</h3>
                        <div class="flex items-center gap-4 text-sm text-gray-500">
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                {{ $info->created_at->format('d M Y') }}
                            </span>
                            @if($info->category)
                            <span class="inline-block px-2 py-1 bg-orange-100 text-orange-600 text-xs font-semibold rounded">
                                {{ is_object($info->category) ? $info->category->name : $info->category }}
                            </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div class="prose prose-sm max-w-none mb-4">
                    <p class="text-gray-700 line-clamp-3">{{ $info->content ?? 'Tidak ada konten' }}</p>
                </div>

                <!-- Read More Button -->
                <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                    <div class="text-xs text-gray-500">
                        dipublikasikan {{ $info->published_date?->format('d M Y') ?? 'Tanpa tanggal' }}
                    </div>
                    <button onclick="openInfoModal({{ json_encode($info) }})" class="inline-flex items-center gap-2 px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white rounded-lg transition font-medium text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        Baca Selengkapnya
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Tidak ada informasi ditemukan</h3>
            <p class="text-gray-600">Silakan cek kembali nanti untuk update terbaru</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($informations->hasPages())
    <div class="mt-8">
        {{ $informations->links() }}
    </div>
    @endif
</div>

<!-- Detail Modal -->
<div id="infoModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/75">
    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <!-- Header: Sticky dengan close button -->
        <div class="sticky top-0 bg-gradient-to-r from-orange-600 to-orange-700 px-6 py-5 flex items-center justify-between border-b border-orange-500">
            <h3 class="text-2xl font-bold text-white" id="modalTitle">Informasi Terbaru</h3>
            <button onclick="closeInfoModal()" class="flex-shrink-0 text-white hover:text-orange-100 transition duration-200 p-1">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Body: Square image & content -->
        <div class="p-6 space-y-5">
            <!-- Foto: Square aspect ratio -->
            <div id="modalPhotoContainer" class="rounded-lg overflow-hidden border border-gray-300 shadow-sm">
                <img id="modalPhoto" src="" alt="Foto" class="w-full aspect-square object-cover">
            </div>

            <!-- Judul di dalam modal -->
            <h4 class="text-xl font-bold text-gray-900" id="modalTitle2">-</h4>

            <!-- Metadata: Kategori & Tanggal -->
            <div class="flex flex-wrap items-center gap-3">
                <span class="inline-block px-4 py-1.5 bg-orange-100 text-orange-700 rounded-full text-xs font-semibold" id="modalCategory">-</span>
                <span class="text-sm text-gray-500" id="modalPublishDate">-</span>
            </div>

            <!-- Divider -->
            <div class="border-t border-gray-200"></div>

            <!-- Konten: Teks dengan formatting -->
            <div class="text-gray-700 text-sm leading-relaxed whitespace-pre-wrap" id="modalContent">-</div>
        </div>
    </div>
</div>

<script>
    /**
     * Buka modal informasi dengan data lengkap
     */
    function openInfoModal(info) {
        const modal = document.getElementById('infoModal');

        // Set title di header
        document.getElementById('modalTitle').textContent = info.title || 'Informasi Terbaru';

        // Set title di dalam modal body
        document.getElementById('modalTitle2').textContent = info.title || 'Informasi Terbaru';

        // Set foto dengan fallback
        const photoImg = document.getElementById('modalPhoto');
        if (info.photo) {
            photoImg.src = '/storage/' + info.photo;
            photoImg.style.display = 'block';
        } else {
            photoImg.style.display = 'none';
        }

        // Set kategori - handle string atau object
        const categoryName = typeof info.category === 'object'
            ? (info.category.name || '-')
            : info.category;
        document.getElementById('modalCategory').textContent = categoryName;

        // Set tanggal publikasi
        const publishDate = info.published_date
            ? new Date(info.published_date).toLocaleDateString('id-ID', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
              })
            : '-';
        document.getElementById('modalPublishDate').textContent = publishDate;

        // Set konten
        document.getElementById('modalContent').textContent = info.content || 'Tidak ada konten';

        // Tampilkan modal
        modal.classList.remove('hidden');
    }

    /**
     * Tutup modal informasi
     */
    function closeInfoModal() {
        document.getElementById('infoModal').classList.add('hidden');
    }

    /**
     * Event listeners untuk interaksi modal
     */
    document.addEventListener('DOMContentLoaded', function() {
        // Tutup modal saat click di luar (backdrop)
        document.getElementById('infoModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeInfoModal();
            }
        });
    });

    // Tutup modal dengan tombol Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeInfoModal();
        }
    });
</script>

@endsection


