@extends('marketing.layouts.app')

@section('title', 'Listing Aset - Marketing')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Listing Aset untuk Penjualan</h1>
        <p class="text-gray-600 mt-2">Kelola daftar aset, download foto, dan salin text broadcast untuk strategi penjualan</p>
    </div>

    <!-- Filter & Search -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-8 fade-in">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-gray-900">Filter Aset</h2>
            @php
                $hasFilter = request('search') || request('category') || request('location') || request('status');
            @endphp
            @if($hasFilter)
            <a href="{{ route('marketing.assets') }}" class="text-sm text-orange-600 hover:text-orange-700 font-semibold">Reset Filter</a>
            @endif
        </div>

        <form method="GET" class="space-y-4">
            <!-- Search Input -->
            <div>
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari judul atau lokasi..."
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition"
                >
            </div>

            <!-- Filter Chips -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <div>
                    <select name="category" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent text-gray-700 transition bg-white">
                        <option value="">Semua Kategori</option>
                        <option value="Bank Cessie" {{ request('category') === 'Bank Cessie' ? 'selected' : '' }}>Bank Cessie</option>
                        <option value="AYDA" {{ request('category') === 'AYDA' ? 'selected' : '' }}>AYDA</option>
                        <option value="Lelang" {{ request('category') === 'Lelang' ? 'selected' : '' }}>Lelang</option>
                    </select>
                </div>
                <div>
                    <select name="location" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent text-gray-700 transition bg-white">
                        <option value="">Semua Lokasi</option>
                        @php
                            $locations = \App\Models\Asset::select('location')->distinct()->orderBy('location')->get();
                        @endphp
                        @foreach($locations as $loc)
                            <option value="{{ $loc->location }}" {{ request('location') === $loc->location ? 'selected' : '' }}>{{ $loc->location }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent text-gray-700 transition bg-white">
                        <option value="">Semua Status</option>
                        <option value="Available" {{ request('status') === 'Available' ? 'selected' : '' }}>Tersedia</option>
                        <option value="Sold Out" {{ request('status') === 'Sold Out' ? 'selected' : '' }}>Terjual</option>
                    </select>
                </div>
            </div>

            <!-- Search Button -->
            <div class="flex gap-2">
                <button type="submit" class="flex-1 px-4 py-3 bg-gradient-to-r from-orange-600 to-orange-700 hover:from-orange-700 hover:to-orange-800 text-white font-semibold rounded-lg transition shadow-md hover:shadow-lg">
                    Cari Aset
                </button>
            </div>
        </form>
    </div>

    <!-- Assets Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 fade-in">
        @php
            $assets = \App\Models\Asset::query();

            // Search by title or location
            if(request('search')) {
                $search = request('search');
                $assets->where(function($q) use ($search) {
                    $q->where('title', 'like', '%' . $search . '%')
                      ->orWhere('location', 'like', '%' . $search . '%');
                });
            }

            // Filter by category
            if(request('category')) {
                $assets->where('category', request('category'));
            }

            // Filter by location
            if(request('location')) {
                $assets->where('location', request('location'));
            }

            // Filter by status
            if(request('status')) {
                $assets->where('status', request('status'));
            }

            $assets = $assets->paginate(9);
        @endphp

        @forelse($assets as $asset)
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition group">
            <!-- Image Section with Carousel -->
            <div class="relative h-48 bg-gray-200 overflow-hidden">
                @if($asset->photos && count($asset->photos) > 0)
                    <!-- Image Carousel -->
                    <div class="carousel-container relative h-full" data-asset-id="{{ $asset->id }}">
                        @foreach($asset->photos as $index => $photo)
                            <img
                                src="{{ asset('storage/' . $photo) }}"
                                alt="{{ $asset->title }} - {{ $index + 1 }}"
                                class="carousel-image absolute w-full h-full object-cover transition duration-300 {{ $index === 0 ? 'opacity-100' : 'opacity-0' }}"
                                data-index="{{ $index }}"
                            >
                        @endforeach

                        <!-- Carousel Controls (hanya muncul jika lebih dari 1 foto) -->
                        @if(count($asset->photos) > 1)
                            <!-- Previous Button -->
                            <button onclick="carouselPrev({{ $asset->id }})" class="absolute left-2 top-1/2 -translate-y-1/2 z-10 bg-black bg-opacity-50 hover:bg-opacity-75 text-white p-2 rounded-full transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </button>

                            <!-- Next Button -->
                            <button onclick="carouselNext({{ $asset->id }})" class="absolute right-2 top-1/2 -translate-y-1/2 z-10 bg-black bg-opacity-50 hover:bg-opacity-75 text-white p-2 rounded-full transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>

                            <!-- Carousel Indicators -->
                            <div class="absolute bottom-3 left-1/2 -translate-x-1/2 z-10 flex gap-1">
                                @foreach($asset->photos as $index => $photo)
                                    <button
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
                            <div class="absolute bottom-3 right-3 bg-black bg-opacity-70 text-white px-2 py-1 rounded text-xs font-semibold">
                                1 foto
                            </div>
                        @endif
                    </div>
                @else
                    <div class="w-full h-full flex items-center justify-center bg-gray-300 text-gray-600">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
            <div class="p-4">
                <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $asset->title }}</h3>

                <div class="space-y-2 mb-4">
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span>{{ $asset->location }}</span>
                    </div>
                </div>

                <!-- Broadcast Text Section -->
                <div class="bg-orange-50 rounded-lg p-3 mb-4 border border-orange-200">
                    <p class="text-xs font-semibold text-orange-900 mb-2">Text Broadcast:</p>
                    <p class="text-sm text-orange-800 line-clamp-3">
                        Halo! Ada aset menarik dari kategori {{ $asset->category }} nih!

Judul: {{ $asset->title }}
Lokasi: {{ $asset->location }}
Status: {{ $asset->status === 'Available' ? 'Tersedia' : 'Terjual' }}

Tunggu apalagi? Hubungi kami sekarang untuk info lebih lanjut!
                    </p>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-2">
                    <!-- Copy Broadcast -->
                    <button onclick="copyToClipboard('broadcast-{{ $asset->id }}')" class="flex-1 px-3 py-2 bg-orange-600 text-white text-sm font-semibold rounded-lg hover:bg-orange-700 transition flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                        Salin
                    </button>

                    <!-- Download Photos -->
                    @if($asset->photos && count($asset->photos) > 0)
                    <a href="{{ route('marketing.download-photos', $asset) }}" class="flex-1 px-3 py-2 bg-orange-600 text-white text-sm font-semibold rounded-lg hover:bg-orange-700 transition flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Download
                    </a>
                    @endif

                    <!-- View Detail -->
                    <button onclick="showDetail({{ $asset->id }})" class="flex-1 px-3 py-2 bg-gray-600 text-white text-sm font-semibold rounded-lg hover:bg-gray-700 transition flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        Lihat
                    </button>
                </div>
            </div>

            <!-- Hidden broadcast text for copying -->
            <div id="broadcast-{{ $asset->id }}" style="display: none;">
                Halo! Ada aset menarik dari kategori {{ $asset->category }} nih!

                Judul: {{ $asset->title }}
                Lokasi: {{ $asset->location }}
                Status: {{ $asset->status === 'Available' ? 'Tersedia' : 'Terjual' }}

                Tunggu apalagi? Hubungi kami sekarang untuk info lebih lanjut!
            </div>

            <!-- Hidden asset data for modal -->
            <div id="asset-data-{{ $asset->id }}" style="display: none;">{{ json_encode(['id' => $asset->id, 'title' => $asset->title, 'category' => $asset->category, 'location' => $asset->location, 'status' => $asset->status, 'description' => $asset->description, 'photos_count' => count($asset->photos ?? []), 'photos' => $asset->photos ?? []]) }}</div>
        </div>
        @empty
        <div class="col-span-full text-center py-12">
            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
            <p class="text-gray-500 font-medium">Tidak ada aset yang sesuai dengan filter</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($assets->total() > 0)
    <div class="mt-12 flex justify-center">
        <div class="flex items-center gap-2">
            {{ $assets->appends(request()->query())->links('pagination::tailwind') }}
        </div>
    </div>
    @endif
</div>

<!-- Asset Detail Modal -->
<div id="detailModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50 overflow-y-auto">
    <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full my-8 overflow-hidden">
        <!-- Header -->
        <div class="sticky top-0 bg-gradient-to-r from-orange-600 to-orange-700 px-8 py-6 flex items-center justify-between">
            <div class="px-4 py-3">
                <h3 class="text-2xl font-bold text-white" id="modalTitle">Detail Aset</h3>
                <p class="text-orange-100 text-sm mt-1" id="modalSubtitle">Informasi lengkap aset untuk penjualan</p>
            </div>
            <button onclick="closeModal()" class="text-white hover:text-gray-200 transition p-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Body - Two Columns -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-8">
            <!-- Left Column - Photo Carousel -->
            <div>
                <div class="relative bg-gray-200 rounded-lg overflow-hidden h-96 flex items-center justify-center" id="modalPhotoContainer">
                    <div class="carousel-container relative w-full h-full modal-carousel">
                        <!-- Photos will be injected here -->
                        <div class="w-full h-full flex items-center justify-center text-gray-500">
                            <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Carousel Controls -->
                    <button onclick="modalCarouselPrev()" class="absolute left-3 top-1/2 -translate-y-1/2 z-10 bg-black bg-opacity-60 hover:bg-opacity-80 text-white p-2 rounded-full transition" id="modalPrevBtn" style="display: none;">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>

                    <button onclick="modalCarouselNext()" class="absolute right-3 top-1/2 -translate-y-1/2 z-10 bg-black bg-opacity-60 hover:bg-opacity-80 text-white p-2 rounded-full transition" id="modalNextBtn" style="display: none;">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>

                    <!-- Counter -->
                    <div class="absolute bottom-4 right-4 bg-black bg-opacity-70 text-white px-4 py-2 rounded-full text-sm font-semibold" id="modalPhotoCounter" style="display: none;">
                        <span id="modalCurrentPhoto">1</span>/<span id="modalTotalPhotos">1</span>
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
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Tipe Aset</p>
                        <p class="text-lg font-bold text-gray-900 mt-1" id="modalDetailAssetType">-</p>
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-3">Deskripsi Lengkap</h3>
                    <div class="bg-orange-50 border border-orange-200 rounded-lg p-4 prose prose-sm max-w-none" id="modalDetailDescription">-</div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3 pt-4 border-t border-gray-200">
                    <button onclick="copyFromModal()" class="flex-1 px-4 py-3 bg-orange-600 hover:bg-orange-700 text-white font-semibold rounded-lg transition flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                        Salin Text
                    </button>
                    <button onclick="closeModal()" class="flex-1 px-4 py-3 bg-gray-200 hover:bg-gray-300 text-gray-900 font-semibold rounded-lg transition">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyToClipboard(elementId) {
    const element = document.getElementById(elementId);
    const text = element.innerText;

    navigator.clipboard.writeText(text).then(() => {
        // Show success feedback
        alert('Text broadcast berhasil disalin!');
    }).catch(() => {
        alert('Gagal menyalin text');
    });
}

function showDetail(assetId) {
    // Get asset data from hidden element
    const assetData = document.getElementById('asset-data-' + assetId);
    if (!assetData) return;

    const data = JSON.parse(assetData.textContent);

    // Store current asset ID for carousel functions
    window.currentModalAssetId = assetId;
    window.currentModalAssetData = data;

    // Populate modal - Title & Location
    document.getElementById('modalTitle').textContent = data.title;
    document.getElementById('modalSubtitle').textContent = data.location + ' • ' + data.category;
    document.getElementById('modalDetailTitle').textContent = data.title;
    document.getElementById('modalDetailLocation').textContent = data.location;
    document.getElementById('modalDetailCategory').textContent = data.category;

    // Populate modal - Status badge
    const statusBadge = document.getElementById('modalStatusBadge');
    if (data.status === 'Available') {
        statusBadge.className = 'inline-block px-4 py-2 bg-green-500 text-white text-sm font-bold rounded-full animate-pulse';
        statusBadge.textContent = 'Tersedia';
    } else {
        statusBadge.className = 'inline-block px-4 py-2 bg-red-500 text-white text-sm font-bold rounded-full';
        statusBadge.textContent = 'Terjual';
    }

    // Populate modal - Category badge
    let categoryClass = 'bg-orange-600';
    if (data.category === 'AYDA') categoryClass = 'bg-green-600';
    else if (data.category === 'Lelang') categoryClass = 'bg-purple-600';

    const categoryBadge = document.getElementById('modalCategoryBadge');
    categoryBadge.className = `inline-block px-4 py-2 ${categoryClass} text-white text-sm font-bold rounded-full`;
    categoryBadge.textContent = data.category;

    // Populate modal - Status details
    const statusDetail = document.getElementById('modalDetailStatus');
    if (data.status === 'Available') {
        statusDetail.innerHTML = '<span class="inline-block px-3 py-1 bg-green-500 text-white text-sm font-bold rounded-full animate-pulse">Tersedia</span>';
    } else {
        statusDetail.innerHTML = '<span class="inline-block px-3 py-1 bg-red-500 text-white text-sm font-bold rounded-full">Terjual</span>';
    }

    // Populate modal - Photo info
    document.getElementById('modalDetailPhotoCount').textContent = data.photos_count + ' foto';
    document.getElementById('modalDetailAssetType').textContent = 'Property';

    // Populate modal - Description (with HTML formatting)
    document.getElementById('modalDetailDescription').innerHTML = data.description || '<em>Tidak ada deskripsi tersedia</em>';

    // Setup carousel with photos
    setupModalCarousel(data);

    // Show modal
    document.getElementById('detailModal').classList.remove('hidden');
}

function setupModalCarousel(data) {
    const container = document.querySelector('#detailModal .modal-carousel');
    container.innerHTML = '';

    if (data.photos && data.photos.length > 0) {
        // Create images
        data.photos.forEach((photo, index) => {
            const img = document.createElement('img');
            img.src = '/storage/' + photo;
            img.alt = data.title + ' - ' + (index + 1);
            img.className = `modal-carousel-image absolute w-full h-full object-cover transition duration-300 ${index === 0 ? 'opacity-100' : 'opacity-0'}`;
            img.dataset.index = index;
            container.appendChild(img);
        });

        // Initialize carousel state
        window.modalCarouselState = {
            current: 0,
            total: data.photos.length
        };

        // Show/hide controls based on photo count
        if (data.photos.length > 1) {
            document.getElementById('modalPrevBtn').style.display = 'block';
            document.getElementById('modalNextBtn').style.display = 'block';
            document.getElementById('modalPhotoCounter').style.display = 'block';
            document.getElementById('modalCurrentPhoto').textContent = '1';
            document.getElementById('modalTotalPhotos').textContent = data.photos.length;
        } else {
            document.getElementById('modalPrevBtn').style.display = 'none';
            document.getElementById('modalNextBtn').style.display = 'none';
            document.getElementById('modalPhotoCounter').style.display = 'none';
        }
    } else {
        // Show no image placeholder
        const placeholder = document.createElement('div');
        placeholder.className = 'w-full h-full flex items-center justify-center text-gray-500';
        placeholder.innerHTML = `<svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
        </svg>`;
        container.appendChild(placeholder);
    }
}

function updateModalCarousel() {
    const state = window.modalCarouselState;
    const container = document.querySelector('#detailModal .modal-carousel');

    // Update images
    container.querySelectorAll('.modal-carousel-image').forEach((img, index) => {
        img.classList.toggle('opacity-100', index === state.current);
        img.classList.toggle('opacity-0', index !== state.current);
    });

    // Update counter
    document.getElementById('modalCurrentPhoto').textContent = state.current + 1;
}

function modalCarouselNext() {
    const state = window.modalCarouselState;
    state.current = (state.current + 1) % state.total;
    updateModalCarousel();
}

function modalCarouselPrev() {
    const state = window.modalCarouselState;
    state.current = (state.current - 1 + state.total) % state.total;
    updateModalCarousel();
}

function copyFromModal() {
    const data = window.currentModalAssetData;
    const broadcastText = `Halo! Ada aset menarik dari kategori ${data.category} nih!

Judul: ${data.title}
Lokasi: ${data.location}
Status: ${data.status === 'Available' ? 'Tersedia' : 'Terjual'}

Tunggu apalagi? Hubungi kami sekarang untuk info lebih lanjut!`;

    navigator.clipboard.writeText(broadcastText).then(() => {
        alert('Text broadcast berhasil disalin!');
    }).catch(() => {
        alert('Gagal menyalin text');
    });
}

function closeModal() {
    document.getElementById('detailModal').classList.add('hidden');
}

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeModal();
});

// Close modal when clicking outside
document.getElementById('detailModal')?.addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});

// Carousel Management
const carouselStates = {};

function getCarouselState(assetId) {
    if (!carouselStates[assetId]) {
        carouselStates[assetId] = {
            current: 0,
            total: document.querySelectorAll(`[data-asset-id="${assetId}"] .carousel-image`).length
        };
    }
    return carouselStates[assetId];
}

function updateCarousel(assetId) {
    const state = getCarouselState(assetId);
    const container = document.querySelector(`[data-asset-id="${assetId}"]`);

    // Update images
    container.querySelectorAll('.carousel-image').forEach((img, index) => {
        img.classList.toggle('opacity-100', index === state.current);
        img.classList.toggle('opacity-0', index !== state.current);
    });

    // Update indicators
    container.querySelectorAll('.carousel-indicator').forEach((dot, index) => {
        dot.classList.toggle('bg-opacity-50', index !== state.current);
        dot.classList.toggle('w-6', index === state.current);
        dot.classList.toggle('w-2', index !== state.current);
    });

    // Update counter
    const counter = container.querySelector('.carousel-counter');
    if (counter) {
        counter.textContent = state.current + 1;
    }
}

function carouselNext(assetId) {
    const state = getCarouselState(assetId);
    state.current = (state.current + 1) % state.total;
    updateCarousel(assetId);
}

function carouselPrev(assetId) {
    const state = getCarouselState(assetId);
    state.current = (state.current - 1 + state.total) % state.total;
    updateCarousel(assetId);
}

function carouselGo(assetId, index) {
    const state = getCarouselState(assetId);
    state.current = index;
    updateCarousel(assetId);
}
</script>

<style>
#modalDetailDescription p {
    margin-bottom: 1rem;
    line-height: 1.6;
}

#modalDetailDescription h1,
#modalDetailDescription h2,
#modalDetailDescription h3,
#modalDetailDescription h4,
#modalDetailDescription h5,
#modalDetailDescription h6 {
    margin: 1rem 0 0.5rem 0;
    font-weight: 600;
}

#modalDetailDescription h1 { font-size: 1.5rem; }
#modalDetailDescription h2 { font-size: 1.25rem; }
#modalDetailDescription h3 { font-size: 1.1rem; }

#modalDetailDescription ul,
#modalDetailDescription ol {
    margin: 1rem 0;
    padding-left: 2rem;
}

#modalDetailDescription li {
    margin-bottom: 0.5rem;
}

#modalDetailDescription strong {
    font-weight: 600;
    color: #1f2937;
}

#modalDetailDescription em {
    font-style: italic;
    color: #6b7280;
}

#modalDetailDescription br {
    display: block;
    content: "";
}

#modalDetailDescription blockquote {
    border-left: 4px solid #f97316;
    padding-left: 1rem;
    margin: 1rem 0;
    color: #6b7280;
}

#modalDetailDescription code {
    background-color: #f3f4f6;
    padding: 0.2rem 0.4rem;
    border-radius: 0.25rem;
    font-family: monospace;
}

#modalDetailDescription a {
    color: #f97316;
    text-decoration: underline;
}

#modalDetailDescription a:hover {
    color: #ea580c;
}
</style>

@endsection


