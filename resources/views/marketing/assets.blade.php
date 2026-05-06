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

            <!-- Sort -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Urutkan</label>
                <select name="sort" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent text-gray-700 transition bg-white">
                    <option value="newest" {{ request('sort', 'newest') === 'newest' ? 'selected' : '' }}>Terbaru</option>
                    <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Terlama</option>
                    <option value="title_asc" {{ request('sort') === 'title_asc' ? 'selected' : '' }}>Judul A–Z</option>
                    <option value="title_desc" {{ request('sort') === 'title_desc' ? 'selected' : '' }}>Judul Z–A</option>
                </select>
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
    @php
        // Hitung bookmarkedIds untuk marketing user yang sedang login
        $bookmarkedIds = auth()->user()->bookmarkedAssetIds();
        $savedCount = count($bookmarkedIds);
        $isSavedTab = request('saved') === '1';
    @endphp

    <!-- Tab Filter: Semua / Tersimpan -->
    <div class="flex items-center gap-3 mb-6 fade-in">
        <a href="{{ route('marketing.assets', collect(request()->query())->except('saved')->toArray()) }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full text-sm font-semibold shadow-sm transition-all duration-200 {{ !$isSavedTab ? 'bg-orange-600 text-white shadow-md' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
            </svg>
            Semua Aset
        </a>
        <a href="{{ route('marketing.assets', array_merge(collect(request()->query())->except('saved')->toArray(), ['saved' => '1'])) }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full text-sm font-semibold shadow-sm transition-all duration-200 {{ $isSavedTab ? 'bg-orange-600 text-white shadow-md' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">
            <svg class="w-4 h-4" fill="{{ $isSavedTab ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
            </svg>
            Tersimpan
            @if($savedCount > 0)
                <span class="{{ $isSavedTab ? 'bg-white text-orange-600' : 'bg-orange-100 text-orange-700' }} text-xs font-bold px-1.5 py-0.5 rounded-full min-w-[1.25rem] text-center">{{ $savedCount }}</span>
            @endif
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 fade-in">
        @php
            $assets = \App\Models\Asset::query();

            // Filter hanya aset yang di-bookmark
            if ($isSavedTab) {
                $assets->whereIn('id', $bookmarkedIds);
            }

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

            // Sort
            $sort = request('sort', 'newest');
            match($sort) {
                'oldest'     => $assets->orderBy('created_at', 'asc'),
                'title_asc'  => $assets->orderBy('title', 'asc'),
                'title_desc' => $assets->orderBy('title', 'desc'),
                default      => $assets->orderBy('created_at', 'desc'),
            };

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


                    </div>

                    @if(count($asset->photos) > 1)
                        <!-- Previous Button -->
                        <button onclick="carouselPrev({{ $asset->id }})" class="absolute left-0 top-0 h-full w-10 z-20 flex items-center justify-center bg-gradient-to-r from-black/40 to-transparent hover:from-black/60 transition text-white">
                            <svg class="w-5 h-5 drop-shadow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </button>

                        <!-- Next Button -->
                        <button onclick="carouselNext({{ $asset->id }})" class="absolute right-0 top-0 h-full w-10 z-20 flex items-center justify-center bg-gradient-to-l from-black/40 to-transparent hover:from-black/60 transition text-white">
                            <svg class="w-5 h-5 drop-shadow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                    @endif
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

                <!-- Bookmark Button -->
                @php $isBookmarked = in_array($asset->id, $bookmarkedIds); @endphp
                <button
                    type="button"
                    onclick="toggleBookmark({{ $asset->id }}, this)"
                    data-saved="{{ $isBookmarked ? 'true' : 'false' }}"
                    title="{{ $isBookmarked ? 'Hapus dari simpanan' : 'Simpan aset ini' }}"
                    class="absolute bottom-3 right-3 z-20 w-9 h-9 rounded-full flex items-center justify-center shadow-md transition-all duration-200 {{ $isBookmarked ? 'bg-orange-500 text-white' : 'bg-white/90 text-gray-500 hover:bg-orange-50 hover:text-orange-500' }}"
                >
                    <svg class="w-5 h-5" fill="{{ $isBookmarked ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                    </svg>
                </button>
            </div>

            <!-- Photo Counter Bar -->
            @if($asset->photos && count($asset->photos) > 0)
            <div class="flex items-center justify-center gap-2 py-2 bg-gray-50 border-b border-gray-200">
                <svg class="w-3.5 h-3.5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 5a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V7a2 2 0 00-2-2h-1.586a1 1 0 01-.707-.293l-1.121-1.121A2 2 0 0011.172 3H8.828a2 2 0 00-1.414.586L6.293 4.707A1 1 0 015.586 5H4zm6 9a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"></path>
                </svg>
                <span class="text-xs text-gray-500">Foto <span id="carousel-counter-{{ $asset->id }}" class="font-semibold text-gray-700">1</span> / {{ count($asset->photos) }}</span>
            </div>
            @endif

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
                    <p class="text-xs font-semibold text-orange-900 mb-2">Deskripsi:</p>
                    <div class="text-sm text-orange-800 line-clamp-3 prose prose-sm max-w-none">
                        {!! $asset->description ?? '<em>Tidak ada deskripsi</em>' !!}
                    </div>
                    @if($asset->description && strlen(strip_tags($asset->description)) > 120)
                        <p class="text-xs text-orange-500 mt-1 font-medium">Lihat selengkapnya di Detail →</p>
                    @endif
                </div>

                <!-- Action Buttons -->
                <div class="space-y-2">
                    <div class="flex gap-2">
                        <!-- Copy Description -->
                        <button onclick="copyDescription({{ $asset->id }}, this)" class="flex-1 px-3 py-2 bg-orange-600 text-white text-sm font-semibold rounded-lg hover:bg-orange-700 transition flex items-center justify-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                            Salin
                        </button>

                        <!-- Download Photos -->
                        @if($asset->photos && count($asset->photos) > 0)
                        <button onclick="openDownloadModal({{ $asset->id }}, '{{ addslashes($asset->title) }}', this)" class="flex-1 px-3 py-2 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 transition flex items-center justify-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                            Unduh
                        </button>
                        @endif
                    </div>

                    <!-- Copy GMap Link -->
                    @if($asset->gmap_link)
                    <button onclick="copyGmapLink({{ $asset->id }}, this)" class="w-full px-3 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Salin Lokasi
                    </button>
                    @endif

                    <!-- View Detail -->
                    <button onclick="showDetail({{ $asset->id }})" class="w-full px-3 py-2 bg-gray-600 text-white text-sm font-semibold rounded-lg hover:bg-gray-700 transition flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        Lihat Detail
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
            <div id="asset-data-{{ $asset->id }}" style="display: none;">{{ json_encode(['id' => $asset->id, 'title' => $asset->title, 'category' => $asset->category, 'location' => $asset->location, 'gmap_link' => $asset->gmap_link, 'status' => $asset->status, 'description' => $asset->description, 'photos_count' => count($asset->photos ?? []), 'photos' => array_map(fn($p) => asset('storage/' . $p), $asset->photos ?? [])]) }}</div>
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

<!-- Download Image Picker Modal -->
<style>
    #downloadModal {
        scroll-behavior: smooth;
    }
    #downloadModal .dm-backdrop {
        animation: dmFadeIn 0.25s ease;
    }
    #downloadModal .dm-panel {
        animation: dmSlideUp 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    @keyframes dmFadeIn { from { opacity: 0; } to { opacity: 1; } }
    @keyframes dmSlideUp { from { opacity: 0; transform: translateY(32px) scale(0.97); } to { opacity: 1; transform: translateY(0) scale(1); } }

    #downloadModal .dm-img-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    #downloadModal .dm-img-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 28px rgba(0,0,0,0.18);
    }
    #downloadModal .dm-img-card.selected {
        outline: 3px solid #16a34a;
        outline-offset: 2px;
    }
    #downloadModal .dm-check {
        transition: all 0.2s ease;
        transform: scale(0.8);
        opacity: 0;
    }
    #downloadModal .dm-img-card.selected .dm-check {
        transform: scale(1);
        opacity: 1;
    }
    #downloadModal .dm-count-badge {
        transition: all 0.25s ease;
    }
    .dm-btn-action {
        transition: all 0.2s ease;
    }
    .dm-btn-action:not(:disabled):hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(0,0,0,0.2);
    }
    .dm-btn-action:not(:disabled):active {
        transform: translateY(0);
    }

    /* Smooth scrolling for image grid */
    #downloadModal .dm-panel {
        scroll-behavior: smooth;
    }

    /* Scrollbar styling for the body area */
    #downloadModal .flex-1::-webkit-scrollbar {
        width: 8px;
    }
    #downloadModal .flex-1::-webkit-scrollbar-track {
        background: #f9fafb;
    }
    #downloadModal .flex-1::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }
    #downloadModal .flex-1::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
</style>

<div id="downloadModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto" style="background: rgba(10,10,20,0.65); backdrop-filter: blur(6px);">
    <div class="dm-panel bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-hidden flex flex-col my-4" style="box-shadow: 0 32px 80px rgba(0,0,0,0.35);">

        <!-- Header -->
        <div class="relative overflow-hidden px-6 py-5 flex items-center justify-between flex-shrink-0" style="background: linear-gradient(135deg, #065f46 0%, #059669 55%, #10b981 100%);">
            <div class="relative flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-white leading-tight">Unduh Gambar Aset</h3>
                    <p class="text-emerald-100 text-xs mt-0.5" id="downloadModalSubtitle">Pilih satu atau lebih gambar</p>
                </div>
            </div>
            <button type="button" onclick="closeDownloadModal()"
                class="relative w-9 h-9 rounded-full bg-white/15 hover:bg-white/30 flex items-center justify-center text-white transition-all duration-200 flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Selection Toolbar -->
        <div class="px-6 py-3 border-b border-gray-100 bg-gray-50 flex items-center justify-between flex-shrink-0 gap-3">
            <!-- counter pill -->
            <div class="flex items-center gap-2">
                <div class="dm-count-badge inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-semibold"
                    id="downloadSelectionPill"
                    style="background:#dcfce7; color:#15803d;">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                    <span><span id="imageSelectedCount" class="font-bold">0</span> dipilih</span>
                </div>
                <span class="text-xs text-gray-400" id="downloadTotalCount"></span>
            </div>
            <!-- select all -->
            <button type="button" id="toggleAllBtn" onclick="toggleAllImages()"
                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg transition-all duration-200 bg-green-600 text-white hover:bg-green-700">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Pilih Semua
            </button>
        </div>

        <!-- Body / Image Grid -->
        <div class="flex-1 overflow-y-auto p-5" style="background:#f9fafb;">
            <div id="downloadImageGrid" class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                <!-- injected by JS -->
            </div>

            <!-- Empty State -->
            <div id="downloadEmptyState" class="hidden flex flex-col items-center justify-center py-16 text-center">
                <div class="w-20 h-20 rounded-2xl bg-gray-100 flex items-center justify-center mb-4">
                    <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <p class="text-gray-500 font-semibold">Tidak ada gambar tersedia</p>
                <p class="text-gray-400 text-sm mt-1">Aset ini belum memiliki foto</p>
            </div>
        </div>

        <!-- Footer -->
        <div class="flex-shrink-0 px-5 py-4 bg-white border-t border-gray-100" style="box-shadow: 0 -4px 16px rgba(0,0,0,0.06);">
            <div class="flex gap-2.5">
                <!-- Cancel -->
                <button type="button" onclick="closeDownloadModal()"
                    class="dm-btn-action px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-sm transition-all duration-200">
                    Batal
                </button>

                <!-- Download individually -->
                <button type="button" id="downloadIndividualBtn" onclick="downloadSelectedImages()"
                    class="dm-btn-action flex-1 px-4 py-2.5 rounded-xl text-white font-semibold text-sm flex items-center justify-center gap-2 transition-all duration-200 disabled:opacity-40 disabled:cursor-not-allowed disabled:transform-none disabled:shadow-none"
                    style="background: linear-gradient(135deg, #16a34a, #15803d);"
                    disabled>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Download
                </button>

                <!-- Download ZIP -->
                <button type="button" id="downloadZipBtn" onclick="downloadSelectedImagesAsZip()"
                    class="dm-btn-action flex-1 px-4 py-2.5 rounded-xl text-white font-semibold text-sm flex items-center justify-center gap-2 transition-all duration-200 disabled:opacity-40 disabled:cursor-not-allowed disabled:transform-none disabled:shadow-none"
                    style="background: linear-gradient(135deg, #ea580c, #c2410c);"
                    disabled>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h10M4 18h6m8-4v6m0 0l-2-2m2 2l2-2"/>
                    </svg>
                    ZIP
                </button>
            </div>

            <!-- helper text -->
            <p class="text-center text-xs text-gray-400 mt-2.5" id="downloadHelperText">
                Klik gambar untuk memilih • Download akan dimulai otomatis
            </p>
        </div>
    </div>
</div>

<!-- Asset Detail Modal -->
<div id="detailModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full mx-auto my-8 overflow-hidden">
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
                <div class="flex gap-3 pt-4 border-t border-gray-200 flex-wrap">
                    <button onclick="copyFromModal()" class="flex-1 px-4 py-3 bg-orange-600 hover:bg-orange-700 text-white font-semibold rounded-lg transition flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                        Salin Text
                    </button>
                    <button id="modalGmapBtn" onclick="copyGmapFromModal()" class="flex-1 px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition flex items-center justify-center gap-2" style="display:none;">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Salin GMaps
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
// ── iOS Detection ──────────────────────────────────────────
const isIOS = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;

// ── Download Modal State ───────────────────────────────────
let downloadModalState = {
    assetId: null,
    assetTitle: null,
    photos: [],
    selectedPhotos: new Set()
};

// Open Download Modal
function openDownloadModal(assetId, assetTitle, btn) {
    const assetData = document.getElementById('asset-data-' + assetId);
    if (!assetData) return;

    const data = JSON.parse(assetData.textContent);
    const photos = data.photos || [];

    if (photos.length === 0) {
        showToast('Tidak ada gambar untuk diunduh', 'error');
        return;
    }

    // Reset state
    downloadModalState = {
        assetId: assetId,
        assetTitle: assetTitle,
        photos: photos,
        selectedPhotos: new Set()
    };

    // Update modal title
    document.getElementById('downloadModalSubtitle').textContent = `${assetTitle} • ${photos.length} foto`;
    const totalEl = document.getElementById('downloadTotalCount');
    if (totalEl) totalEl.textContent = `dari ${photos.length} gambar`;

    // iOS: update helper text & button label
    const helperText = document.getElementById('downloadHelperText');
    const downloadBtn = document.getElementById('downloadIndividualBtn');
    const zipBtn = document.getElementById('downloadZipBtn');
    if (isIOS) {
        if (helperText) helperText.textContent = 'Klik gambar untuk memilih • Gambar akan dibuka di tab baru, tekan lama → Simpan Gambar';
        if (downloadBtn) downloadBtn.innerHTML = downloadBtn.innerHTML.replace('Download', 'Buka di Safari');
        if (zipBtn) zipBtn.innerHTML = zipBtn.innerHTML.replace('ZIP', 'Buka Semua');
    } else {
        if (helperText) helperText.textContent = 'Klik gambar untuk memilih • Download akan dimulai otomatis';
    }

    // Render images grid
    renderDownloadImageGrid();

    // Freeze background
    document.body.style.overflow = 'hidden';

    // Show modal
    document.getElementById('downloadModal').classList.remove('hidden');
}

// Close Download Modal
function closeDownloadModal() {
    document.getElementById('downloadModal').classList.add('hidden');
    downloadModalState.selectedPhotos.clear();
    updateImageSelectedCount();

    // Unfreeze background
    document.body.style.overflow = '';
}

// Render Image Grid in Download Modal
function renderDownloadImageGrid() {
    const grid = document.getElementById('downloadImageGrid');
    const emptyState = document.getElementById('downloadEmptyState');

    grid.innerHTML = '';

    if (downloadModalState.photos.length === 0) {
        emptyState.classList.remove('hidden');
        return;
    }

    emptyState.classList.add('hidden');

    downloadModalState.photos.forEach((photo, index) => {
        // Card wrapper (acts as click target)
        const card = document.createElement('div');
        card.className = 'dm-img-card relative rounded-xl overflow-hidden cursor-pointer bg-gray-200';
        card.style.cssText = 'height:160px; border-radius:12px;';
        card.addEventListener('click', () => {
            if (downloadModalState.selectedPhotos.has(index)) {
                downloadModalState.selectedPhotos.delete(index);
            } else {
                downloadModalState.selectedPhotos.add(index);
            }
            updateImageSelectedCount();
            updateCheckboxStates();
        });

        // Photo
        const img = document.createElement('img');
        img.src = photo;
        img.alt = `Gambar ${index + 1}`;
        img.className = 'w-full h-full object-cover';
        img.style.cssText = 'position:absolute;inset:0;width:100%;height:100%;object-fit:cover;';

        // Dark gradient overlay (bottom)
        const grad = document.createElement('div');
        grad.style.cssText = 'position:absolute;inset:0;background:linear-gradient(to top,rgba(0,0,0,0.55) 0%,transparent 55%);pointer-events:none;';

        // Photo label badge
        const badge = document.createElement('span');
        badge.style.cssText = 'position:absolute;bottom:8px;left:10px;background:rgba(0,0,0,0.55);color:white;font-size:11px;font-weight:700;padding:2px 8px;border-radius:99px;letter-spacing:0.03em;';
        badge.textContent = `Foto ${index + 1}`;

        // Check circle (top-right)
        const checkCircle = document.createElement('div');
        checkCircle.className = 'dm-check';
        checkCircle.style.cssText = 'position:absolute;top:8px;right:8px;width:28px;height:28px;border-radius:50%;background:#16a34a;display:flex;align-items:center;justify-content:center;box-shadow:0 2px 8px rgba(0,0,0,0.3);';
        checkCircle.innerHTML = '<svg width="16" height="16" fill="none" stroke="white" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>';

        // Unchecked ring (visible when not selected)
        const ring = document.createElement('div');
        ring.className = 'dm-ring';
        ring.style.cssText = 'position:absolute;top:8px;right:8px;width:28px;height:28px;border-radius:50%;border:2.5px solid rgba(255,255,255,0.8);box-shadow:0 2px 6px rgba(0,0,0,0.2);transition:opacity 0.2s;';

        card.appendChild(img);
        card.appendChild(grad);
        card.appendChild(badge);
        card.appendChild(ring);
        card.appendChild(checkCircle);

        grid.appendChild(card);
    });
}

// Update image selected count
function updateImageSelectedCount() {
    const count = downloadModalState.selectedPhotos.size;
    document.getElementById('imageSelectedCount').textContent = count;

    // Enable/disable buttons based on selection
    const downloadBtn = document.getElementById('downloadIndividualBtn');
    const zipBtn = document.getElementById('downloadZipBtn');
    downloadBtn.disabled = count === 0;
    zipBtn.disabled = count === 0;

    // Update selection pill colour
    const pill = document.getElementById('downloadSelectionPill');
    if (pill) {
        pill.style.background = count > 0 ? '#16a34a' : '#dcfce7';
        pill.style.color = count > 0 ? '#ffffff' : '#15803d';
    }

    // Update toggle-all button
    const toggleBtn = document.getElementById('toggleAllBtn');
    if (!toggleBtn) return;
    const total = downloadModalState.photos.length;
    if (count === total && count > 0) {
        toggleBtn.innerHTML = '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg> Batal Semua';
        toggleBtn.classList.remove('bg-green-600', 'hover:bg-green-700');
        toggleBtn.classList.add('bg-red-500', 'hover:bg-red-600');
    } else {
        toggleBtn.innerHTML = '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> Pilih Semua';
        toggleBtn.classList.remove('bg-red-500', 'hover:bg-red-600');
        toggleBtn.classList.add('bg-green-600', 'hover:bg-green-700');
    }
}

// Toggle all images selection
function toggleAllImages() {
    const count = downloadModalState.selectedPhotos.size;
    const total = downloadModalState.photos.length;

    if (count === total) {
        // Deselect all
        downloadModalState.selectedPhotos.clear();
    } else {
        // Select all
        for (let i = 0; i < total; i++) {
            downloadModalState.selectedPhotos.add(i);
        }
    }

    updateImageSelectedCount();
    updateCheckboxStates();
}

// Update card visual states
function updateCheckboxStates() {
    const grid = document.getElementById('downloadImageGrid');
    const cards = grid.querySelectorAll('.dm-img-card');

    cards.forEach((card, index) => {
        const isSelected = downloadModalState.selectedPhotos.has(index);
        if (isSelected) {
            card.classList.add('selected');
        } else {
            card.classList.remove('selected');
        }
        // Show/hide the ring vs check-circle
        const ring = card.querySelector('.dm-ring');
        if (ring) ring.style.opacity = isSelected ? '0' : '1';
    });
}

// Download individual selected images
async function downloadSelectedImages() {
    const photos = downloadModalState.photos;
    const selectedPhotos = Array.from(downloadModalState.selectedPhotos).sort((a, b) => a - b);

    if (selectedPhotos.length === 0) {
        showToast('Pilih gambar terlebih dahulu', 'error');
        return;
    }

    const btn = document.getElementById('downloadIndividualBtn');
    const originalHtml = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg> Mengunduh...';

    const ext = (p) => p.split('.').pop().split('?')[0] || 'jpg';
    const safeTitle = downloadModalState.assetTitle.replace(/[^a-zA-Z0-9_\- ]/g, '').trim();

    // ── iOS: buka tiap gambar di tab baru (Safari tidak support a.download) ──
    if (isIOS) {
        selectedPhotos.forEach((photoIndex, count) => {
            setTimeout(() => {
                window.open(photos[photoIndex], '_blank');
            }, count * 700);
        });
        btn.disabled = false;
        btn.innerHTML = originalHtml;
        showToast('Gambar dibuka di tab baru. Tekan lama → Simpan Gambar 📥', 'success');
        closeDownloadModal();
        return;
    }

    // ── Non-iOS: download via blob ──
    let completed = 0;
    selectedPhotos.forEach((photoIndex, count) => {
        setTimeout(() => {
            const photo = photos[photoIndex];
            fetch(photo)
                .then(res => {
                    if (!res.ok) throw new Error('Failed');
                    return res.blob();
                })
                .then(blob => {
                    const url = URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = safeTitle + '_foto_' + (photoIndex + 1) + '.' + ext(photo);
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    setTimeout(() => URL.revokeObjectURL(url), 1000);
                })
                .catch(() => showToast('Gagal mengunduh satu gambar', 'error'))
                .finally(() => {
                    completed++;
                    if (completed === selectedPhotos.length) {
                        btn.disabled = false;
                        btn.innerHTML = originalHtml;
                        showToast(`${selectedPhotos.length} gambar berhasil diunduh!`, 'success');
                        closeDownloadModal();
                    }
                });
        }, count * 600);
    });
}

// Download selected images as ZIP (server-side generation - Safari compatible)
async function downloadSelectedImagesAsZip() {
    const selectedPhotos = Array.from(downloadModalState.selectedPhotos).sort((a, b) => a - b);

    if (selectedPhotos.length === 0) {
        showToast('Pilih gambar terlebih dahulu', 'error');
        return;
    }

    // ── iOS: Download individual images instead of ZIP ──
    if (isIOS) {
        const photos = downloadModalState.photos;
        selectedPhotos.forEach((photoIndex, count) => {
            setTimeout(() => {
                window.open(photos[photoIndex], '_blank');
            }, count * 700);
        });
        showToast('Gambar dibuka di tab baru. Tekan lama → Simpan Gambar 📥', 'success');
        closeDownloadModal();
        return;
    }

    // Server-side ZIP generation (compatible with Safari and all browsers)
    const btn = document.getElementById('downloadZipBtn');
    const originalHtml = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg> Membuat ZIP...';

    try {
        const assetId = downloadModalState.assetId;
        const downloadRoute = `{{ route('marketing.download-selected', ['asset' => ':assetId']) }}`.replace(':assetId', assetId);

        // Send POST request to server with selected photo indices
        const response = await fetch(downloadRoute, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify({ indices: selectedPhotos })
        });

        if (!response.ok) {
            const errorData = await response.json().catch(() => ({}));
            throw new Error(errorData.error || `HTTP error! status: ${response.status}`);
        }

        // Get the blob from response
        const blob = await response.blob();

        // Create download link and trigger download
        const url = URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.href = url;
        link.download = `Aset_${downloadModalState.assetTitle.replace(/[^a-zA-Z0-9_\- ]/g, '').trim()}_${new Date().toISOString().split('T')[0]}.zip`;

        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);

        // Clean up
        setTimeout(() => URL.revokeObjectURL(url), 100);

        btn.disabled = false;
        btn.innerHTML = originalHtml;
        showToast(`Berhasil membuat ZIP dengan ${selectedPhotos.length} gambar!`, 'success');
        closeDownloadModal();
    } catch (error) {
        console.error('ZIP download error:', error);
        btn.disabled = false;
        btn.innerHTML = originalHtml;
        showToast('Gagal membuat file ZIP: ' + error.message, 'error');
    }
}

// Perform ZIP download
async function performZipDownload(photos, selectedPhotos) {
    const btn = document.getElementById('downloadZipBtn');
    const originalHtml = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg> Membuat ZIP...';

    try {
        const zip = new JSZip();
        const ext = (p) => p.split('.').pop().split('?')[0] || 'jpg';
        const safeTitle = downloadModalState.assetTitle.replace(/[^a-zA-Z0-9_\- ]/g, '').trim();

        // Fetch all selected images
        const imagePromises = selectedPhotos.map((photoIndex) => {
            return fetch(photos[photoIndex])
                .then(res => {
                    if (!res.ok) throw new Error('Failed to fetch');
                    return res.blob();
                })
                .then(blob => {
                    const filename = safeTitle + '_foto_' + (photoIndex + 1) + '.' + ext(photos[photoIndex]);
                    zip.file(filename, blob);
                });
        });

        await Promise.all(imagePromises);

        // Generate ZIP file
        const content = await zip.generateAsync({ type: 'blob' });

        // Download ZIP
        const url = URL.createObjectURL(content);
        const a = document.createElement('a');
        a.href = url;
        a.download = safeTitle + '_gambar_' + new Date().getTime() + '.zip';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        setTimeout(() => URL.revokeObjectURL(url), 1000);

        btn.disabled = false;
        btn.innerHTML = originalHtml;
        showToast(`Berhasil membuat ZIP dengan ${selectedPhotos.length} gambar!`, 'success');
        closeDownloadModal();
    } catch (error) {
        console.error('ZIP download error:', error);
        btn.disabled = false;
        btn.innerHTML = originalHtml;
        showToast('Gagal membuat file ZIP', 'error');
    }
}

// Convert HTML to WhatsApp-friendly formatted text
function htmlToFormattedText(html) {
    const tmp = document.createElement('div');
    tmp.innerHTML = html;

    function processNode(node) {
        if (node.nodeType === Node.TEXT_NODE) {
            return node.textContent;
        }
        if (node.nodeType !== Node.ELEMENT_NODE) return '';

        const tag = node.tagName.toLowerCase();
        const inner = Array.from(node.childNodes).map(processNode).join('');

        switch (tag) {
            case 'strong':
            case 'b':
                return `*${inner}*`;
            case 'em':
            case 'i':
                return `_${inner}_`;
            case 's':
            case 'del':
            case 'strike':
                return `~${inner}~`;
            case 'u':
                return inner;
            case 'br':
                return '\n';
            case 'p':
                return inner.trim() ? inner.trim() + '\n\n' : '';
            case 'h1': case 'h2': case 'h3':
            case 'h4': case 'h5': case 'h6':
                return `*${inner.trim()}*\n\n`;
            case 'li':
                return `• ${inner.trim()}\n`;
            case 'ul':
            case 'ol':
                return inner + '\n';
            case 'blockquote':
                return inner.split('\n').map(l => l.trim() ? '> ' + l.trim() : '').filter(Boolean).join('\n') + '\n\n';
            case 'code':
                return '`' + inner + '`';
            case 'pre':
                return '```\n' + inner + '\n```\n';
            case 'hr':
                return '\n---\n\n';
            default:
                return inner;
        }
    }

    return Array.from(tmp.childNodes).map(processNode).join('').replace(/\n{3,}/g, '\n\n').trim();
}

function doClipboardCopy(htmlContent, onSuccess, onFail) {
    const formattedText = htmlToFormattedText(htmlContent);

    try {
        navigator.clipboard.write([
            new ClipboardItem({
                'text/html': new Blob([htmlContent], { type: 'text/html' }),
                'text/plain': new Blob([formattedText], { type: 'text/plain' })
            })
        ]).then(onSuccess).catch(() => {
            navigator.clipboard.writeText(formattedText).then(onSuccess).catch(onFail);
        });
    } catch(e) {
        navigator.clipboard.writeText(formattedText).then(onSuccess).catch(onFail);
    }
}

function copyDescription(assetId, btn) {
    const assetData = document.getElementById('asset-data-' + assetId);
    if (!assetData) return;

    const data = JSON.parse(assetData.textContent);
    const description = data.description || '';

    const doSuccess = () => {
        if (btn) {
            const original = btn.innerHTML;
            btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Tersalin!';
            btn.classList.replace('bg-orange-600', 'bg-green-600');
            btn.classList.replace('hover:bg-orange-700', 'hover:bg-green-700');
            setTimeout(() => {
                btn.innerHTML = original;
                btn.classList.replace('bg-green-600', 'bg-orange-600');
                btn.classList.replace('hover:bg-green-700', 'hover:bg-orange-700');
            }, 2000);
        }
    };
    const doFail = () => {
        if (btn) {
            const original = btn.innerHTML;
            btn.innerHTML = 'Gagal!';
            setTimeout(() => { btn.innerHTML = original; }, 2000);
        }
    };

    doClipboardCopy(description, doSuccess, doFail);
}

// Copy Google Maps Link
function copyGmapLink(assetId, btn) {
    const assetData = document.getElementById('asset-data-' + assetId);
    if (!assetData) return;
    const data = JSON.parse(assetData.textContent);
    const link = data.gmap_link || '';
    if (!link) return;

    const originalHtml = btn ? btn.innerHTML : null;
    navigator.clipboard.writeText(link).then(() => {
        if (btn) {
            btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Tersalin!';
            btn.classList.replace('bg-blue-600', 'bg-green-600');
            btn.classList.replace('hover:bg-blue-700', 'hover:bg-green-700');
            setTimeout(() => {
                btn.innerHTML = originalHtml;
                btn.classList.replace('bg-green-600', 'bg-blue-600');
                btn.classList.replace('hover:bg-green-700', 'hover:bg-blue-700');
            }, 2000);
        }
        showToast('Link Google Maps berhasil disalin!', 'success');
    }).catch(() => showToast('Gagal menyalin link', 'error'));
}

function copyGmapFromModal() {
    const data = window.currentModalAssetData;
    if (!data || !data.gmap_link) return;

    const btn = document.getElementById('modalGmapBtn');
    const originalHtml = btn ? btn.innerHTML : null;
    navigator.clipboard.writeText(data.gmap_link).then(() => {
        if (btn) {
            btn.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Tersalin!';
            btn.classList.replace('bg-blue-600', 'bg-green-600');
            btn.classList.replace('hover:bg-blue-700', 'hover:bg-green-700');
            setTimeout(() => {
                btn.innerHTML = originalHtml;
                btn.classList.replace('bg-green-600', 'bg-blue-600');
                btn.classList.replace('hover:bg-green-700', 'hover:bg-blue-700');
            }, 2000);
        }
        showToast('Link Google Maps berhasil disalin!', 'success');
    }).catch(() => showToast('Gagal menyalin link', 'error'));
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

    // Show/hide GMaps button in modal
    const gmapBtn = document.getElementById('modalGmapBtn');
    if (gmapBtn) {
        if (data.gmap_link) {
            gmapBtn.style.removeProperty('display');
            gmapBtn.style.display = 'flex';
        } else {
            gmapBtn.style.display = 'none';
        }
    }

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
            img.src = photo;
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
    const description = data.description || '';

    const btn = document.querySelector('#detailModal button[onclick="copyFromModal()"]');

    const doSuccess = () => {
        if (btn) {
            const original = btn.innerHTML;
            btn.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Tersalin!';
            btn.classList.replace('bg-orange-600', 'bg-green-600');
            btn.classList.replace('hover:bg-orange-700', 'hover:bg-green-700');
            setTimeout(() => {
                btn.innerHTML = original;
                btn.classList.replace('bg-green-600', 'bg-orange-600');
                btn.classList.replace('hover:bg-green-700', 'hover:bg-orange-700');
            }, 2000);
        }
    };
    const doFail = () => {
        if (btn) {
            const original = btn.innerHTML;
            btn.innerHTML = 'Gagal!';
            setTimeout(() => { btn.innerHTML = original; }, 2000);
        }
    };

    doClipboardCopy(description, doSuccess, doFail);
}

function closeModal() {
    document.getElementById('detailModal').classList.add('hidden');
}

// Toggle bookmark (simpan/hapus aset)
async function toggleBookmark(assetId, btn) {
    const isSaved = btn.dataset.saved === 'true';
    setBookmarkState(btn, !isSaved);
    try {
        const response = await fetch(`/listing-aset/${assetId}/bookmark`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept':       'application/json',
                'Content-Type': 'application/json',
            },
        });
        if (!response.ok) throw new Error('Server error');
        const data = await response.json();
        setBookmarkState(btn, data.saved);
        showToast(data.message, 'success');
        updateBookmarkTabBadge(data.saved);
    } catch (e) {
        setBookmarkState(btn, isSaved);
        showToast('Gagal menyimpan. Silakan coba lagi.', 'error');
    }
}

function setBookmarkState(btn, saved) {
    btn.dataset.saved = saved ? 'true' : 'false';
    btn.title = saved ? 'Hapus dari simpanan' : 'Simpan aset ini';
    const svg = btn.querySelector('svg');
    if (saved) {
        btn.classList.remove('bg-white/90', 'text-gray-500', 'hover:bg-orange-50', 'hover:text-orange-500');
        btn.classList.add('bg-orange-500', 'text-white');
        svg.setAttribute('fill', 'currentColor');
    } else {
        btn.classList.remove('bg-orange-500', 'text-white');
        btn.classList.add('bg-white/90', 'text-gray-500', 'hover:bg-orange-50', 'hover:text-orange-500');
        svg.setAttribute('fill', 'none');
    }
}

function updateBookmarkTabBadge(saved) {
    const savedTab = document.querySelector('a[href*="saved=1"]');
    if (!savedTab) return;
    let badge = savedTab.querySelector('span');
    let currentCount = badge ? parseInt(badge.textContent) || 0 : 0;
    const newCount = saved ? currentCount + 1 : Math.max(0, currentCount - 1);
    if (badge) {
        badge.textContent = newCount;
        if (newCount === 0) badge.remove();
    } else if (newCount > 0) {
        const span = document.createElement('span');
        span.className = 'bg-orange-100 text-orange-700 text-xs font-bold px-1.5 py-0.5 rounded-full min-w-[1.25rem] text-center';
        span.textContent = newCount;
        savedTab.appendChild(span);
    }
}

function showToast(message, type = 'success') {
    let toast = document.getElementById('bookmark-toast');
    if (!toast) {
        toast = document.createElement('div');
        toast.id = 'bookmark-toast';
        toast.className = 'fixed bottom-6 left-1/2 -translate-x-1/2 z-[100] px-5 py-3 rounded-xl text-white text-sm font-semibold shadow-xl transition-all duration-300';
        document.body.appendChild(toast);
    }
    toast.textContent = message;
    toast.style.background = type === 'error' ? '#ef4444' : '#22c55e';
    toast.style.opacity = '1';
    toast.style.display = 'block';
    clearTimeout(toast._timeout);
    toast._timeout = setTimeout(() => { toast.style.opacity = '0'; setTimeout(() => { toast.style.display = 'none'; }, 300); }, 3000);
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

    // Update counter
    const counter = document.getElementById('carousel-counter-' + assetId);
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

// Close download modal when clicking outside
document.getElementById('downloadModal')?.addEventListener('click', function(event) {
    if (event.target === this) {
        closeDownloadModal();
    }
});

// Close download modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const downloadModal = document.getElementById('downloadModal');
        if (downloadModal && !downloadModal.classList.contains('hidden')) {
            closeDownloadModal();
        }
    }
});
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


