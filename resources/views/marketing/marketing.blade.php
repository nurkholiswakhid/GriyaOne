@extends('marketing.layouts.app')

@section('title', 'Marketing Dashboard - GriyaOne')
@section('role', 'Marketing Dashboard')

@section('content')
            <!-- Header -->
            <div class="mb-8 fade-in">
                <h2 class="text-3xl font-bold text-gray-900 mb-1">Selamat datang, {{ Auth::user()?->name ?? 'User' }}!</h2>
                <p class="text-gray-600">Dashboard marketing untuk monitoring penjualan dan strategi pemasaran aset</p>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 fade-in">
                <div class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-lg transition">
                    <div class="bg-gradient-to-br from-orange-500 to-orange-600 p-6 text-white">
                        <h3 class="text-lg font-semibold mb-2">Total Listing</h3>
                        <p class="text-3xl font-bold">{{ $totalListings }}</p>
                        <p class="text-sm mt-2 opacity-90">Aset yang terdaftar</p>
                    </div>
                </div>
                <div class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-lg transition">
                    <div class="bg-gradient-to-br from-green-500 to-green-600 p-6 text-white">
                        <h3 class="text-lg font-semibold mb-2">Tersedia</h3>
                        <p class="text-3xl font-bold">{{ $availableListings }}</p>
                        <p class="text-sm mt-2 opacity-90">Siap dijual</p>
                    </div>
                </div>
                <div class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-lg transition">
                    <div class="bg-gradient-to-br from-red-500 to-red-600 p-6 text-white">
                        <h3 class="text-lg font-semibold mb-2">Terjual</h3>
                        <p class="text-3xl font-bold">{{ $soldListings }}</p>
                        <p class="text-sm mt-2 opacity-90">Berhasil terjual</p>
                    </div>
                </div>
            </div>

            <!-- Informasi Terbaru Section -->
            <div class="fade-in mb-12">
                <h3 class="text-2xl font-bold text-gray-900 mb-6">Informasi Terbaru</h3>

                @if($informations->isEmpty())
                <div class="bg-white rounded-lg p-8 text-center shadow hover:shadow-md transition">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    <p class="text-gray-600">Belum ada informasi terbaru.</p>
                </div>
                @else
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($informations as $info)
                    <div class="bg-white rounded-lg overflow-hidden shadow hover:shadow-md transition">
                        <div class="bg-gray-100 h-32 flex items-center justify-center border-b border-gray-200 overflow-hidden">
                            @if($info->photo)
                                <img src="{{ asset('storage/' . $info->photo) }}" alt="{{ $info->title }}" class="w-full h-full object-cover">
                            @else
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            @endif
                        </div>
                        <div class="p-4">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="inline-block px-2 py-1 bg-gray-200 text-gray-700 rounded text-xs font-semibold">
                                    {{ is_object($info->category) ? $info->category->name : $info->category }}
                                </span>
                                <span class="text-xs text-gray-500">{{ $info->published_date?->format('d M Y') ?? $info->created_at->format('d M Y') }}</span>
                            </div>
                            <h4 class="text-base font-semibold text-gray-900 mb-2">{{ $info->title }}</h4>
                            <p class="text-sm text-gray-600 mb-3 line-clamp-2">{!! Str::limit(strip_tags($info->content), 100) !!}</p>
                            <button onclick="openInformasiModal({{ json_encode($info) }})" class="text-sm text-gray-700 hover:text-gray-900 font-semibold cursor-pointer">Baca Selengkapnya →</button>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            <!-- Listing by Category & Recent Listings Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-12 fade-in">
                <!-- Kategori Listing -->
                <div class="bg-white rounded-xl p-6 shadow-md">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">Kategori Listing</h3>
                    @if($listingsByCategory->isEmpty())
                    <div class="text-center py-8">
                        <p class="text-gray-600">Belum ada listing dalam kategori apapun</p>
                    </div>
                    @else
                    <div class="space-y-4">
                        @foreach($listingsByCategory as $category)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                            <div class="flex items-center gap-3 flex-1">
                                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center text-white font-semibold">
                                    {{ substr($category->category, 0, 1) }}
                                </div>
                                <span class="font-semibold text-gray-900">{{ $category->category }}</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="text-2xl font-bold text-orange-600">{{ $category->count }}</span>
                                <span class="text-sm text-gray-500">listing</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>

                <!-- Listing Terbaru -->
                <div class="bg-white rounded-xl p-6 shadow-md">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">Listing Terbaru</h3>
                    @if($recentListings->isEmpty())
                    <div class="text-center py-8">
                        <p class="text-gray-600">Belum ada listing terbaru</p>
                    </div>
                    @else
                    <div class="space-y-3">
                        @foreach($recentListings as $listing)
                        <div class="p-4 border border-gray-200 rounded-lg hover:border-orange-400 transition group">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex-1">
                                    <h5 class="font-semibold text-gray-900 group-hover:text-orange-600 transition">{{ $listing->title }}</h5>
                                    <p class="text-sm text-gray-600 mt-1">{!! Str::limit(strip_tags($listing->description), 60) !!}</p>
                                    <div class="flex items-center gap-3 mt-2">
                                        <span class="inline-block px-2 py-1 bg-gray-100 text-gray-700 rounded text-xs">{{ $listing->category }}</span>
                                        <span class="inline-block px-2 py-1 {{ $listing->status === 'Available' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }} rounded text-xs font-semibold">{{ $listing->status }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>

<!-- Informasi Modal -->
<div id="informasiModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/75">
    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <!-- Header: Sticky dengan close button -->
        <div class="sticky top-0 bg-gradient-to-r from-orange-600 to-orange-700 px-6 py-5 flex items-center justify-between border-b border-orange-500">
            <h3 class="text-2xl font-bold text-white" id="modalTitle">Informasi Terbaru</h3>
            <button onclick="closeInformasiModal()" class="flex-shrink-0 text-white hover:text-orange-100 transition duration-200 p-1">
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
                <span class="text-sm text-gray-500" id="modalDate">-</span>
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
    function openInformasiModal(info) {
        const modal = document.getElementById('informasiModal');

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
        document.getElementById('modalDate').textContent = publishDate;

        // Set konten
        document.getElementById('modalContent').textContent = info.content || 'Tidak ada konten';

        // Tampilkan modal
        modal.classList.remove('hidden');
    }

    /**
     * Tutup modal informasi
     */
    function closeInformasiModal() {
        document.getElementById('informasiModal').classList.add('hidden');
    }

    /**
     * Event listeners untuk interaksi modal
     */
    document.addEventListener('DOMContentLoaded', function() {
        // Tutup modal saat click di luar (backdrop)
        document.getElementById('informasiModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeInformasiModal();
            }
        });
    });

    // Tutup modal dengan tombol Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeInformasiModal();
        }
    });
</script>
@endsection


