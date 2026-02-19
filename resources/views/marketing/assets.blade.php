@extends('marketing.layouts.app')

@section('title', 'Listing Aset - Marketing')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">📍 Listing Aset untuk Penjualan</h1>
        <p class="text-gray-600 mt-2">Kelola daftar aset, download foto, dan salin text broadcast untuk strategi penjualan</p>
    </div>

    <!-- Filter & Search -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kategori Aset</label>
                <select name="category" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                    <option value="">Semua Kategori</option>
                    <option value="Bank Cessie" {{ request('category') === 'Bank Cessie' ? 'selected' : '' }}>🏦 Bank Cessie</option>
                    <option value="AYDA" {{ request('category') === 'AYDA' ? 'selected' : '' }}>🏘️ AYDA</option>
                    <option value="Lelang" {{ request('category') === 'Lelang' ? 'selected' : '' }}>🔨 Lelang</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                    <option value="">Semua Status</option>
                    <option value="Available" {{ request('status') === 'Available' ? 'selected' : '' }}>✅ Tersedia</option>
                    <option value="Sold Out" {{ request('status') === 'Sold Out' ? 'selected' : '' }}>❌ Terjual</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">&nbsp;</label>
                <button type="submit" class="w-full px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition">Cari</button>
            </div>
        </form>
    </div>

    <!-- Assets Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @php
            $assets = \App\Models\Asset::query();
            if(request('category')) $assets->where('category', request('category'));
            if(request('status')) $assets->where('status', request('status'));
            $assets = $assets->get();
        @endphp

        @forelse($assets as $asset)
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition group">
            <!-- Image Section -->
            <div class="relative h-48 bg-gray-200 overflow-hidden">
                @if($asset->photos && count($asset->photos) > 0)
                    <img src="{{ asset('storage/' . $asset->photos[0]) }}" alt="{{ $asset->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
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
                        <span class="inline-block px-3 py-1 bg-blue-600 text-white text-xs font-bold rounded-full">🏦 Bank Cessie</span>
                    @elseif($asset->category === 'AYDA')
                        <span class="inline-block px-3 py-1 bg-green-600 text-white text-xs font-bold rounded-full">🏘️ AYDA</span>
                    @else
                        <span class="inline-block px-3 py-1 bg-purple-600 text-white text-xs font-bold rounded-full">🔨 Lelang</span>
                    @endif
                </div>

                <!-- Status Badge -->
                <div class="absolute top-3 right-3">
                    @if($asset->status === 'Available')
                        <span class="inline-block px-3 py-1 bg-green-500 text-white text-xs font-bold rounded-full animate-pulse">✅ Tersedia</span>
                    @else
                        <span class="inline-block px-3 py-1 bg-red-500 text-white text-xs font-bold rounded-full">❌ Terjual</span>
                    @endif
                </div>

                <!-- Photo Count -->
                @if($asset->photos && count($asset->photos) > 0)
                <div class="absolute bottom-3 right-3 bg-black bg-opacity-70 text-white px-2 py-1 rounded text-xs font-semibold">
                    📷 {{ count($asset->photos) }} foto
                </div>
                @endif
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
                        <span>📍 {{ $asset->location }}</span>
                    </div>
                    <div class="text-2xl font-bold text-orange-600">
                        Rp {{ number_format($asset->price, 0, ',', '.') }}
                    </div>
                </div>

                <!-- Broadcast Text Section -->
                <div class="bg-orange-50 rounded-lg p-3 mb-4 border border-orange-200">
                    <p class="text-xs font-semibold text-orange-900 mb-2">📝 Text Broadcast:</p>
                    <p class="text-sm text-orange-800 line-clamp-3">
                        Halo! 👋 Ada aset menarik dari kategori {{ $asset->category }} nih! 🏘️

Judul: {{ $asset->title }} ✨
Lokasi: 📍 {{ $asset->location }}
Harga: Rp {{ number_format($asset->price, 0, ',', '.') }}
Status: {{ $asset->status === 'Available' ? '✅ Tersedia' : '❌ Terjual' }}

Tunggu apalagi? Hubungi kami sekarang untuk info lebih lanjut! 📞💬
                    </p>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-2">
                    <!-- Copy Broadcast -->
                    <button onclick="copyToClipboard('broadcast-{{ $asset->id }}')" class="flex-1 px-3 py-2 bg-orange-600 text-white text-sm font-semibold rounded-lg hover:bg-orange-700 transition flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                        Salin Text
                    </button>

                    <!-- Download Photos -->
                    @if($asset->photos && count($asset->photos) > 0)
                    <a href="{{ route('marketing.download-photos', $asset) }}" class="flex-1 px-3 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition flex items-center justify-center gap-2">
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
Halo! 👋 Ada aset menarik dari kategori {{ $asset->category }} nih! 🏘️

Judul: {{ $asset->title }} ✨
Lokasi: 📍 {{ $asset->location }}
Harga: Rp {{ number_format($asset->price, 0, ',', '.') }}
Status: {{ $asset->status === 'Available' ? '✅ Tersedia' : '❌ Terjual' }}

Tunggu apalagi? Hubungi kami sekarang untuk info lebih lanjut! 📞💬
            </div>
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
</div>

<script>
function copyToClipboard(elementId) {
    const element = document.getElementById(elementId);
    const text = element.innerText;
    
    navigator.clipboard.writeText(text).then(() => {
        // Show success feedback
        alert('✅ Text broadcast berhasil disalin!');
    }).catch(() => {
        alert('❌ Gagal menyalin text');
    });
}

function showDetail(assetId) {
    // Redirect to asset detail (admin view)
    window.location.href = '/assets/' + assetId;
}
</script>
@endsection
