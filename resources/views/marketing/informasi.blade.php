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
        <form method="GET" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Cari Informasi</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik judul atau kata kunci..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">&nbsp;</label>
                <button type="submit" class="w-full px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition">Cari</button>
            </div>
        </form>
    </div>

    <!-- Informasi List -->
    <div class="space-y-6 mb-8">
        @forelse($informations as $info)
        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition overflow-hidden">
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
                            <span class="inline-block px-2 py-1 bg-orange-100 text-orange-600 text-xs font-semibold rounded">{{ $info->category }}</span>
                            @endif
                            @if($info->status)
                            <span class="inline-block px-2 py-1 {{ $info->status === 'active' ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-600' }} text-xs font-semibold rounded">{{ ucfirst($info->status) }}</span>
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
<div id="infoModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50">
    <div class="bg-white rounded-lg shadow-lg max-w-2xl w-full max-h-96 overflow-y-auto">
        <!-- Header -->
        <div class="sticky top-0 bg-gradient-to-r from-orange-600 to-orange-700 px-6 py-4 flex items-center justify-between">
            <h3 class="text-xl font-bold text-white" id="modalTitle">Informasi Terbaru</h3>
            <button onclick="closeInfoModal()" class="text-white hover:text-gray-200 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Body -->
        <div class="p-6">
            <div class="mb-4">
                <h4 class="text-sm text-gray-500 mb-2">Tanggal Publikasi</h4>
                <p class="text-gray-700" id="modalPublishDate">-</p>
            </div>
            <div class="mb-4">
                <h4 class="text-sm text-gray-500 mb-2">Kategori</h4>
                <p class="text-gray-700" id="modalCategory">-</p>
            </div>
            <div class="mb-4">
                <h4 class="text-sm text-gray-500 mb-2">Status</h4>
                <p class="text-gray-700" id="modalStatus">-</p>
            </div>
            <div class="border-t border-gray-200 pt-4">
                <h4 class="text-sm text-gray-500 mb-2">Konten</h4>
                <div class="prose prose-sm max-w-none text-gray-700" id="modalContent">
                    -
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function openInfoModal(info) {
        const modal = document.getElementById('infoModal');
        document.getElementById('modalTitle').textContent = info.title || 'Informasi Terbaru';
        document.getElementById('modalPublishDate').textContent = info.published_date ? new Date(info.published_date).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' }) : '-';
        document.getElementById('modalCategory').textContent = info.category || '-';
        document.getElementById('modalStatus').textContent = info.status ? (info.status === 'active' ? 'Aktif' : 'Tidak Aktif') : '-';
        document.getElementById('modalContent').textContent = info.content || 'Tidak ada konten';

        modal.classList.remove('hidden');
        modal.addEventListener('click', function(e) {
            if (e.target === modal) closeInfoModal();
        });
    }

    function closeInfoModal() {
        document.getElementById('infoModal').classList.add('hidden');
    }

    // Close with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeInfoModal();
    });
</script>

@endsection
