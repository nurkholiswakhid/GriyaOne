@extends('user.layouts.app')

@section('title', 'Listing Aset - GriyaOne')
@section('role', 'Listing Aset')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8 fade-in">
        <h2 class="text-3xl font-bold text-gray-900">Listing Aset</h2>
        <p class="text-gray-600 mt-2">Jelajahi daftar aset tersedia, unduh foto, dan salin informasi untuk dibagikan</p>
    </div>

    <!-- Filter & Search Section -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-8 fade-in">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-900">Filter & Cari Aset</h3>
            @php
                $hasFilter = request('search') || request('category') || request('location') || request('status');
            @endphp
            @if($hasFilter)
                <a href="{{ route('user.assets.listing') }}" class="text-sm text-orange-600 hover:text-orange-700 font-semibold">Reset Filter</a>
            @endif
        </div>

        <form method="GET" class="space-y-4">
            <!-- Search Input -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Cari Judul atau Lokasi</label>
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Contoh: Rumah Modern, Jakarta Selatan..."
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition"
                >
            </div>

            <!-- Filter Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                    <select name="category" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent text-gray-700 transition bg-white">
                        <option value="">Semua Kategori</option>
                        <option value="Bank Cessie" {{ request('category') === 'Bank Cessie' ? 'selected' : '' }}>Bank Cessie</option>
                        <option value="AYDA" {{ request('category') === 'AYDA' ? 'selected' : '' }}>AYDA</option>
                        <option value="Lelang" {{ request('category') === 'Lelang' ? 'selected' : '' }}>Lelang</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Lokasi</label>
                    <select name="location" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent text-gray-700 transition bg-white">
                        <option value="">Semua Lokasi</option>
                        @foreach($locations as $loc)
                            @if($loc->location)
                                <option value="{{ $loc->location }}" {{ request('location') === $loc->location ? 'selected' : '' }}>{{ $loc->location }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent text-gray-700 transition bg-white">
                        <option value="">Semua Status</option>
                        <option value="Available" {{ request('status') === 'Available' ? 'selected' : '' }}>Tersedia</option>
                        <option value="Sold Out" {{ request('status') === 'Sold Out' ? 'selected' : '' }}>Terjual</option>
                    </select>
                </div>
            </div>

            <!-- Search Button -->
            <button type="submit" class="w-full px-4 py-3 bg-gradient-to-r from-orange-600 to-orange-700 hover:from-orange-700 hover:to-orange-800 text-white font-semibold rounded-lg transition shadow-md hover:shadow-lg">
                Cari Aset
            </button>
        </form>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg fade-in">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg fade-in">
            {{ session('error') }}
        </div>
    @endif

    <!-- Assets Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 fade-in">
        @forelse($assets as $asset)
            <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition group">
                <!-- Image Section with Carousel -->
                <div class="relative h-56 bg-gray-200 overflow-hidden">
                    @if($asset->photos && count($asset->photos) > 0)
                        <!-- Image Carousel -->
                        <div class="carousel-container relative h-full" data-asset-id="{{ $asset->id }}">
                            @foreach($asset->photos as $index => $photo)
                                <img
                                    src="{{ asset('storage/' . $photo) }}"
                                    alt="{{ $asset->title }} - Foto {{ $index + 1 }}"
                                    class="carousel-image absolute w-full h-full object-cover transition duration-300 {{ $index === 0 ? 'opacity-100' : 'opacity-0' }}"
                                    data-index="{{ $index }}"
                                    loading="lazy"
                                >
                            @endforeach

                            <!-- Carousel Controls (only show if more than 1 photo) -->
                            @if(count($asset->photos) > 1)
                                <!-- Previous Button -->
                                <button type="button" onclick="carouselPrev({{ $asset->id }})" class="absolute left-2 top-1/2 -translate-y-1/2 z-10 bg-black bg-opacity-50 hover:bg-opacity-75 text-white p-2 rounded-full transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                    </svg>
                                </button>

                                <!-- Next Button -->
                                <button type="button" onclick="carouselNext({{ $asset->id }})" class="absolute right-2 top-1/2 -translate-y-1/2 z-10 bg-black bg-opacity-50 hover:bg-opacity-75 text-white p-2 rounded-full transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </button>

                                <!-- Carousel Indicators -->
                                <div class="absolute bottom-3 left-1/2 -translate-x-1/2 z-10 flex gap-1">
                                    @foreach($asset->photos as $index => $photo)
                                        <button
                                            type="button"
                                            onclick="carouselGo({{ $asset->id }}, {{ $index }})"
                                            class="carousel-indicator h-2 rounded-full transition {{ $index === 0 ? 'bg-white w-6' : 'bg-white bg-opacity-50 w-2 hover:bg-opacity-75' }}"
                                            data-index="{{ $index }}"
                                        ></button>
                                    @endforeach
                                </div>

                                <!-- Photo Counter -->
                                <div class="absolute top-3 right-3 bg-black bg-opacity-70 text-white px-3 py-1 rounded text-xs font-semibold z-10">
                                    <span class="carousel-counter">1</span>/{{ count($asset->photos) }}
                                </div>
                            @else
                                <!-- Single Photo Count -->
                                <div class="absolute bottom-3 right-3 bg-black bg-opacity-70 text-white px-2 py-1 rounded text-xs font-semibold z-10">
                                    📷 1 Foto
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-gray-300 text-gray-600">
                            <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    @endif

                    <!-- Category Badge -->
                    <div class="absolute top-3 left-3">
                        @if($asset->category === 'Bank Cessie')
                            <span class="inline-block px-3 py-1 bg-orange-600 text-white text-xs font-bold rounded-full">Bank Cessie</span>
                        @elseif($asset->category === 'AYDA')
                            <span class="inline-block px-3 py-1 bg-green-600 text-white text-xs font-bold rounded-full">AYDA</span>
                        @else
                            <span class="inline-block px-3 py-1 bg-purple-600 text-white text-xs font-bold rounded-full">Lelang</span>
                        @endif
                    </div>

                    <!-- Status Badge -->
                    <div class="absolute top-3 right-3">
                        @if($asset->status === 'Available')
                            <span class="inline-block px-3 py-1 bg-green-500 text-white text-xs font-bold rounded-full animate-pulse">Tersedia</span>
                        @else
                            <span class="inline-block px-3 py-1 bg-red-500 text-white text-xs font-bold rounded-full">Terjual</span>
                        @endif
                    </div>
                </div>

                <!-- Content Section -->
                <div class="p-5">
                    <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2">{{ $asset->title }}</h3>

                    <div class="space-y-2 mb-4">
                        <div class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span>{{ $asset->location ?? 'Lokasi tidak tersedia' }}</span>
                        </div>
                    </div>

                    <!-- Broadcast Text Preview -->
                    <div class="bg-orange-50 rounded-lg p-3 mb-4 border border-orange-200">
                        <p class="text-xs font-semibold text-orange-900 mb-2">📢 Text Broadcast:</p>
                        <p class="text-sm text-orange-800 line-clamp-4">
                            Halo! Ada aset menarik dari kategori {{ $asset->category }} nih!<br>
                            <strong>Judul:</strong> {{ $asset->title }}<br>
                            <strong>Lokasi:</strong> {{ $asset->location ?? '-' }}<br>
                            Hubungi kami untuk info lebih lanjut!
                        </p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="grid grid-cols-2 gap-2 mb-3">
                        <!-- Copy Broadcast -->
                        <button type="button" onclick="copyBroadcast({{ $asset->id }})" class="px-3 py-2 bg-orange-600 text-white text-xs font-semibold rounded-lg hover:bg-orange-700 transition flex items-center justify-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                            Salin
                        </button>

                        <!-- Download Photos -->
                        @if($asset->photos && count($asset->photos) > 0)
                            <button onclick="downloadAllPhotos({{ $asset->id }}, '{{ addslashes($asset->title) }}', this)" class="px-3 py-2 bg-green-600 text-white text-xs font-semibold rounded-lg hover:bg-green-700 transition flex items-center justify-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                                Unduh
                            </button>
                        @endif
                    </div>

                    <!-- View Detail Button -->
                    <button type="button" onclick="showDetail({{ $asset->id }})" class="w-full px-3 py-2 bg-gray-200 text-gray-900 text-sm font-semibold rounded-lg hover:bg-gray-300 transition flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        Lihat Detail
                    </button>
                </div>

                <!-- Hidden broadcast text for copying -->
                <div id="broadcast-{{ $asset->id }}" style="display: none;">Halo! Ada aset menarik dari kategori {{ $asset->category }} nih!

Judul: {{ $asset->title }}
Lokasi: {{ $asset->location ?? '-' }}
Status: {{ $asset->status === 'Available' ? 'Tersedia' : 'Terjual' }}

Tunggu apalagi? Hubungi kami sekarang untuk info lebih lanjut!</div>

                <!-- Hidden asset data for modal -->
                <div id="asset-data-{{ $asset->id }}" style="display: none;">{{ json_encode(['id' => $asset->id, 'title' => $asset->title, 'category' => $asset->category, 'location' => $asset->location, 'status' => $asset->status, 'description' => $asset->description, 'photos_count' => count($asset->photos ?? []), 'photos' => $asset->photos ?? []]) }}</div>
            </div>
        @empty
            <!-- Empty State -->
            <div class="col-span-full text-center py-16 bg-white rounded-xl shadow-md">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                <p class="text-gray-500 font-medium text-lg">Tidak ada aset yang sesuai dengan filter</p>
                <p class="text-gray-400 text-sm mt-1">Coba ubah filter atau cari dengan kata kunci lain</p>
                @if($hasFilter)
                    <a href="{{ route('user.assets.listing') }}" class="inline-block mt-4 px-6 py-2 bg-orange-600 text-white font-semibold rounded-lg hover:bg-orange-700 transition">
                        Reset Filter
                    </a>
                @endif
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($assets->total() > 0)
        <div class="mt-12 flex justify-center">
            {{ $assets->appends(request()->query())->links('pagination::tailwind') }}
        </div>
    @endif
</div>

<!-- Asset Detail Modal -->
<div id="detailModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50 overflow-y-auto">
    <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full my-8 overflow-hidden">
        <!-- Header -->
        <div class="sticky top-0 bg-gradient-to-r from-orange-600 to-orange-700 px-8 py-6 flex items-center justify-between">
            <div>
                <h3 class="text-2xl font-bold text-white" id="modalTitle">Detail Aset</h3>
                <p class="text-orange-100 text-sm mt-1" id="modalSubtitle">Informasi lengkap tentang aset</p>
            </div>
            <button type="button" onclick="closeModal()" class="text-white hover:text-gray-200 transition p-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Body -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-8">
            <!-- Left Column - Photo Carousel -->
            <div>
                <div class="relative bg-gray-200 rounded-lg overflow-hidden h-96 flex items-center justify-center" id="modalPhotoContainer">
                    <!-- Photos will be injected here -->
                    <div class="w-full h-full flex items-center justify-center text-gray-500">
                        <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>

                <!-- Badge Section -->
                <div class="mt-4 flex gap-2 flex-wrap" id="modalBadgesContainer">
                    <span id="modalCategoryBadge" class="inline-block px-4 py-2 bg-orange-600 text-white text-sm font-bold rounded-full">-</span>
                    <span id="modalStatusBadge" class="inline-block px-4 py-2 bg-green-500 text-white text-sm font-bold rounded-full">-</span>
                </div>
            </div>

            <!-- Right Column - Details -->
            <div class="space-y-6">
                <!-- Title -->
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-2" id="modalDetailTitle">-</h2>
                    <div class="flex items-center gap-2 text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span id="modalDetailLocation" class="text-lg font-semibold">-</span>
                    </div>
                </div>

                <!-- Information Grid -->
                <div class="grid grid-cols-2 gap-4 p-4 bg-gray-50 rounded-lg">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Kategori</p>
                        <p class="text-lg font-bold text-gray-900 mt-1" id="modalDetailCategory">-</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</p>
                        <div id="modalDetailStatus" class="mt-1">-</div>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Total Foto</p>
                        <p class="text-lg font-bold text-gray-900 mt-1" id="modalDetailPhotoCount">-</p>
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-3">Deskripsi</h3>
                    <div class="bg-orange-50 border border-orange-200 rounded-lg p-4 prose prose-sm max-w-none" id="modalDetailDescription">-</div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3 pt-4 border-t border-gray-200">
                    <button type="button" onclick="copyFromModal()" class="flex-1 px-4 py-3 bg-orange-600 hover:bg-orange-700 text-white font-semibold rounded-lg transition flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                        Salin Text
                    </button>
                    <button type="button" onclick="closeModal()" class="flex-1 px-4 py-3 bg-gray-200 hover:bg-gray-300 text-gray-900 font-semibold rounded-lg transition">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toast Notification -->
<div id="toast" class="hidden fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center gap-2 z-40 animate-pulse">
    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
    </svg>
    <span id="toastMessage">Berhasil disalin!</span>
</div>

<script>
function downloadAllPhotos(assetId, assetTitle, btn) {
    const assetData = document.getElementById('asset-data-' + assetId);
    if (!assetData) return;

    const data = JSON.parse(assetData.textContent);
    const photos = data.photos || [];
    if (photos.length === 0) return;

    const originalHtml = btn ? btn.innerHTML : null;
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg> Mengunduh...';
    }

    const ext = (p) => p.split('.').pop().split('?')[0] || 'jpg';
    const safeTitle = assetTitle.replace(/[^a-zA-Z0-9_\- ]/g, '').trim();

    let completed = 0;
    photos.forEach((photo, index) => {
        setTimeout(() => {
            fetch('/storage/' + photo)
                .then(res => {
                    if (!res.ok) throw new Error('Failed');
                    return res.blob();
                })
                .then(blob => {
                    const url = URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = safeTitle + '_foto_' + (index + 1) + '.' + ext(photo);
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    setTimeout(() => URL.revokeObjectURL(url), 1000);
                })
                .catch(() => {})
                .finally(() => {
                    completed++;
                    if (completed === photos.length && btn) {
                        btn.disabled = false;
                        btn.innerHTML = originalHtml;
                    }
                });
        }, index * 600);
    });
}

// Carousel Functions
function carouselNext(assetId) {
    const container = document.querySelector(`.carousel-container[data-asset-id="${assetId}"]`);
    const images = container.querySelectorAll('.carousel-image');
    const indicators = container.querySelectorAll('.carousel-indicator');
    const counter = container.querySelector('.carousel-counter');

    let currentIndex = 0;
    for (let i = 0; i < images.length; i++) {
        if (images[i].classList.contains('opacity-100')) {
            currentIndex = i;
            break;
        }
    }

    images[currentIndex].classList.remove('opacity-100');
    images[currentIndex].classList.add('opacity-0');
    indicators[currentIndex].classList.remove('bg-white', 'w-6');
    indicators[currentIndex].classList.add('bg-opacity-50', 'w-2');

    currentIndex = (currentIndex + 1) % images.length;

    images[currentIndex].classList.remove('opacity-0');
    images[currentIndex].classList.add('opacity-100');
    indicators[currentIndex].classList.remove('bg-opacity-50', 'w-2');
    indicators[currentIndex].classList.add('bg-white', 'w-6');

    if (counter) {
        counter.textContent = currentIndex + 1;
    }
}

function carouselPrev(assetId) {
    const container = document.querySelector(`.carousel-container[data-asset-id="${assetId}"]`);
    const images = container.querySelectorAll('.carousel-image');
    const indicators = container.querySelectorAll('.carousel-indicator');
    const counter = container.querySelector('.carousel-counter');

    let currentIndex = 0;
    for (let i = 0; i < images.length; i++) {
        if (images[i].classList.contains('opacity-100')) {
            currentIndex = i;
            break;
        }
    }

    images[currentIndex].classList.remove('opacity-100');
    images[currentIndex].classList.add('opacity-0');
    indicators[currentIndex].classList.remove('bg-white', 'w-6');
    indicators[currentIndex].classList.add('bg-opacity-50', 'w-2');

    currentIndex = (currentIndex - 1 + images.length) % images.length;

    images[currentIndex].classList.remove('opacity-0');
    images[currentIndex].classList.add('opacity-100');
    indicators[currentIndex].classList.remove('bg-opacity-50', 'w-2');
    indicators[currentIndex].classList.add('bg-white', 'w-6');

    if (counter) {
        counter.textContent = currentIndex + 1;
    }
}

function carouselGo(assetId, index) {
    const container = document.querySelector(`.carousel-container[data-asset-id="${assetId}"]`);
    const images = container.querySelectorAll('.carousel-image');
    const indicators = container.querySelectorAll('.carousel-indicator');
    const counter = container.querySelector('.carousel-counter');

    let currentIndex = 0;
    for (let i = 0; i < images.length; i++) {
        if (images[i].classList.contains('opacity-100')) {
            currentIndex = i;
            break;
        }
    }

    images[currentIndex].classList.remove('opacity-100');
    images[currentIndex].classList.add('opacity-0');
    indicators[currentIndex].classList.remove('bg-white', 'w-6');
    indicators[currentIndex].classList.add('bg-opacity-50', 'w-2');

    images[index].classList.remove('opacity-0');
    images[index].classList.add('opacity-100');
    indicators[index].classList.remove('bg-opacity-50', 'w-2');
    indicators[index].classList.add('bg-white', 'w-6');

    if (counter) {
        counter.textContent = index + 1;
    }
}

// Copy to Clipboard
function copyBroadcast(assetId) {
    const element = document.getElementById(`broadcast-${assetId}`);
    const text = element.innerText;

    navigator.clipboard.writeText(text).then(() => {
        showToast('Text broadcast berhasil disalin ke clipboard!');
    }).catch(err => {
        console.error('Gagal menyalin:', err);
        showToast('Gagal menyalin text', 'error');
    });
}

function copyFromModal() {
    const currentAssetId = window.currentAssetId;
    const element = document.getElementById(`broadcast-${currentAssetId}`);
    const text = element.innerText;

    navigator.clipboard.writeText(text).then(() => {
        showToast('Text broadcast berhasil disalin ke clipboard!');
    }).catch(err => {
        console.error('Gagal menyalin:', err);
        showToast('Gagal menyalin text', 'error');
    });
}

// Modal Functions
function showDetail(assetId) {
    const dataElement = document.getElementById(`asset-data-${assetId}`);
    const data = JSON.parse(dataElement.innerText);

    window.currentAssetId = assetId;

    // Update modal content
    document.getElementById('modalTitle').innerText = data.title;
    document.getElementById('modalDetailTitle').innerText = data.title;
    document.getElementById('modalDetailLocation').innerText = data.location || '-';
    document.getElementById('modalDetailCategory').innerText = data.category;
    document.getElementById('modalDetailPhotoCount').innerText = data.photos_count;
    document.getElementById('modalDetailDescription').innerText = data.description || 'Tidak ada deskripsi';

    // Status badge
    const statusBadge = document.getElementById('modalDetailStatus');
    if (data.status === 'Available') {
        statusBadge.innerHTML = '<span class="inline-block px-3 py-1 bg-green-500 text-white text-xs font-bold rounded-full">Tersedia</span>';
    } else {
        statusBadge.innerHTML = '<span class="inline-block px-3 py-1 bg-red-500 text-white text-xs font-bold rounded-full">Terjual</span>';
    }

    // Category badge
    const categoryBadge = document.getElementById('modalCategoryBadge');
    if (data.category === 'Bank Cessie') {
        categoryBadge.innerHTML = 'Bank Cessie';
        categoryBadge.className = 'inline-block px-4 py-2 bg-orange-600 text-white text-sm font-bold rounded-full';
    } else if (data.category === 'AYDA') {
        categoryBadge.innerHTML = 'AYDA';
        categoryBadge.className = 'inline-block px-4 py-2 bg-green-600 text-white text-sm font-bold rounded-full';
    } else {
        categoryBadge.innerHTML = 'Lelang';
        categoryBadge.className = 'inline-block px-4 py-2 bg-purple-600 text-white text-sm font-bold rounded-full';
    }

    // Photos
    const photoContainer = document.getElementById('modalPhotoContainer');
    photoContainer.innerHTML = '';

    if (data.photos && data.photos.length > 0) {
        data.photos.forEach((photo, index) => {
            const img = document.createElement('img');
            img.src = '/storage/' + photo;
            img.alt = data.title + ' - Foto ' + (index + 1);
            img.className = `absolute w-full h-full object-cover transition duration-300 ${index === 0 ? 'opacity-100' : 'opacity-0'}`;
            img.setAttribute('data-index', index);
            photoContainer.appendChild(img);
        });

        if (data.photos.length > 1) {
            document.getElementById('modalPrevBtn').style.display = 'block';
            document.getElementById('modalNextBtn').style.display = 'block';
            document.getElementById('modalPhotoCounter').style.display = 'block';
        }
    } else {
        photoContainer.innerHTML = '<div class="w-full h-full flex items-center justify-center text-gray-500"><svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></div>';
    }

    document.getElementById('detailModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('detailModal').classList.add('hidden');
}

// Toast notification
function showToast(message, type = 'success') {
    const toast = document.getElementById('toast');
    const messageEl = document.getElementById('toastMessage');

    messageEl.textContent = message;

    if (type === 'error') {
        toast.classList.remove('bg-green-500');
        toast.classList.add('bg-red-500');
    } else {
        toast.classList.remove('bg-red-500');
        toast.classList.add('bg-green-500');
    }

    toast.classList.remove('hidden');

    setTimeout(() => {
        toast.classList.add('hidden');
    }, 3000);
}

// Close modal on escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeModal();
    }
});

// Close modal on background click
document.getElementById('detailModal')?.addEventListener('click', function(event) {
    if (event.target === this) {
        closeModal();
    }
});
</script>
@endsection


