@extends('marketing.layouts.app')

@section('title', 'Informasi Terbaru - Marketing')

@section('content')
<div class="max-w-7xl mx-auto">

    <!-- Header -->
    <div class="mb-5 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 leading-tight">Informasi Terbaru</h1>
            <p class="text-sm text-gray-500 mt-0.5">Update terkini produk, promosi, dan pengembangan bisnis</p>
        </div>
        <span class="text-sm text-gray-500 font-medium">{{ $informations->total() }} informasi</span>
    </div>

    <!-- Filter Bar -->
    <form method="GET" class="mb-5">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-3">
            <div class="flex flex-wrap gap-2 items-center">
                <!-- Search -->
                <div class="relative flex-1 min-w-44">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul atau kata kunci..." class="w-full pl-9 pr-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-orange-400 focus:ring-1 focus:ring-orange-400/20 bg-gray-50 focus:bg-white transition">
                </div>
                <!-- Category -->
                <select name="category" class="py-2 px-3 border border-gray-200 rounded-lg text-sm bg-gray-50 focus:outline-none focus:border-orange-400 transition">
                    <option value="">Semua Kategori</option>
                    @php $categories = \App\Models\InformationCategory::orderBy('name')->get(); @endphp
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
                <!-- Sort -->
                <select name="sort" class="py-2 px-3 border border-gray-200 rounded-lg text-sm bg-gray-50 focus:outline-none focus:border-orange-400 transition" onchange="this.form.submit()">
                    <option value="terbaru" {{ request('sort','terbaru') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                    <option value="terlama" {{ request('sort') == 'terlama' ? 'selected' : '' }}>Terlama</option>
                    <option value="a-z" {{ request('sort') == 'a-z' ? 'selected' : '' }}>A – Z</option>
                    <option value="z-a" {{ request('sort') == 'z-a' ? 'selected' : '' }}>Z – A</option>
                </select>
                <!-- Search btn -->
                <button type="submit" class="inline-flex items-center gap-1.5 bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg text-sm font-semibold transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    Cari
                </button>
                @if(request('search') || request('category') || (request('sort') && request('sort') != 'terbaru'))
                    <a href="{{ route('marketing.informasi') }}" class="inline-flex items-center gap-1 py-2 px-3 rounded-lg border border-gray-200 text-gray-500 hover:text-orange-600 hover:border-orange-300 hover:bg-orange-50 text-sm font-medium transition" title="Reset filter">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        Reset
                    </a>
                @endif
            </div>
        </div>
    </form>

    <!-- Informasi List — horizontal cards -->
    <div class="space-y-3 mb-6">
        @forelse($informations as $info)
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition overflow-hidden">
            <div class="flex gap-0">
                <!-- Thumbnail -->
                <div class="w-36 sm:w-48 flex-shrink-0 bg-gray-100 overflow-hidden">
                    @if($info->photo)
                        <img src="{{ asset('storage/' . $info->photo) }}" alt="{{ $info->title }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full min-h-[120px] flex items-center justify-center bg-orange-50">
                            <svg class="w-10 h-10 text-orange-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                    @endif
                </div>

                <!-- Content -->
                <div class="flex-1 p-4 flex flex-col justify-between min-w-0">
                    <div>
                        <!-- Badges -->
                        <div class="flex items-center gap-2 mb-1.5">
                            @if($info->category)
                                <span class="inline-block px-2 py-0.5 bg-orange-100 text-orange-600 text-xs font-semibold rounded-full">
                                    {{ is_object($info->category) ? $info->category->name : $info->category }}
                                </span>
                            @endif
                            <span class="text-xs text-gray-400">{{ $info->published_date?->format('d M Y') ?? $info->created_at->format('d M Y') }}</span>
                        </div>
                        <!-- Title -->
                        <h3 class="text-base font-bold text-gray-900 line-clamp-2 leading-snug mb-1.5">{{ $info->title }}</h3>
                        <!-- Preview text -->
                        <p class="text-sm text-gray-500 line-clamp-2">{{ $info->content ?? 'Tidak ada konten' }}</p>
                    </div>

                    <!-- Footer -->
                    <div class="mt-3 flex items-center justify-end">
                        <button onclick="openInfoModal({{ json_encode($info) }})" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-orange-500 hover:bg-orange-600 text-white rounded-lg transition font-semibold text-xs shadow-sm">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            Baca Selengkapnya
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-12 text-center">
            <div class="w-16 h-16 bg-orange-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-orange-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <h3 class="text-base font-semibold text-gray-900 mb-1">Tidak ada informasi ditemukan</h3>
            <p class="text-gray-500 text-sm">Coba ubah filter atau cek kembali nanti</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($informations->hasPages())
    <div class="mt-4">
        {{ $informations->links() }}
    </div>
    @endif
</div>

<!-- Detail Modal -->
<div id="infoModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/75">
    <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <!-- Header -->
        <div class="sticky top-0 bg-gradient-to-r from-orange-500 to-orange-600 px-6 py-4 flex items-center justify-between rounded-t-2xl">
            <h3 class="text-lg font-bold text-white leading-snug pr-4 line-clamp-1" id="modalTitle">Informasi Terbaru</h3>
            <button onclick="closeInfoModal()" class="flex-shrink-0 text-white/80 hover:text-white transition p-1 rounded-lg hover:bg-white/10">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Body -->
        <div class="p-6 space-y-4">
            <!-- Foto -->
            <div id="modalPhotoContainer" class="rounded-xl overflow-hidden border border-gray-200 shadow-sm">
                <img id="modalPhoto" src="" alt="Foto" class="w-full aspect-video object-cover">
            </div>

            <!-- Title -->
            <h4 class="text-xl font-bold text-gray-900 leading-snug" id="modalTitle2">-</h4>

            <!-- Meta -->
            <div class="flex flex-wrap items-center gap-2">
                <span class="inline-block px-3 py-1 bg-orange-100 text-orange-700 rounded-full text-xs font-semibold" id="modalCategory">-</span>
                <span class="text-xs text-gray-400" id="modalPublishDate">-</span>
            </div>

            <div class="border-t border-gray-100"></div>

            <!-- Content -->
            <div class="text-gray-700 text-sm leading-relaxed whitespace-pre-wrap" id="modalContent">-</div>
        </div>
    </div>
</div>

<script>
    function openInfoModal(info) {
        document.getElementById('modalTitle').textContent = info.title || 'Informasi Terbaru';
        document.getElementById('modalTitle2').textContent = info.title || 'Informasi Terbaru';

        const photoContainer = document.getElementById('modalPhotoContainer');
        const photoImg = document.getElementById('modalPhoto');
        if (info.photo) {
            photoImg.src = '/storage/' + info.photo;
            photoContainer.style.display = 'block';
        } else {
            photoContainer.style.display = 'none';
        }

        const categoryName = typeof info.category === 'object' ? (info.category?.name || '-') : (info.category || '-');
        document.getElementById('modalCategory').textContent = categoryName;

        const publishDate = info.published_date
            ? new Date(info.published_date).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' })
            : '-';
        document.getElementById('modalPublishDate').textContent = publishDate;
        document.getElementById('modalContent').textContent = info.content || 'Tidak ada konten';

        document.getElementById('infoModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeInfoModal() {
        document.getElementById('infoModal').classList.add('hidden');
        document.body.style.overflow = '';
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('infoModal').addEventListener('click', function(e) {
            if (e.target === this) closeInfoModal();
        });
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeInfoModal();
    });
</script>

@endsection


