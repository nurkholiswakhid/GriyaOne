@extends('marketing.layouts.app')

@section('title', 'Materi PDF - Marketing')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">📄 Materi PDF</h1>
        <p class="text-gray-600 mt-2">Akses koleksi materi pembelajaran dalam format PDF untuk peningkatan kompetensi</p>
    </div>

    <!-- Filter & Search -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Cari Materi</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik nama materi..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                <select name="category" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                    <option value="">Semua Kategori</option>
                    <option value="Training" {{ request('category') === 'Training' ? 'selected' : '' }}>🎓 Training</option>
                    <option value="Challenge" {{ request('category') === 'Challenge' ? 'selected' : '' }}>🎯 Challenge</option>
                    <option value="Bonus" {{ request('category') === 'Bonus' ? 'selected' : '' }}>🎁 Bonus</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">&nbsp;</label>
                <button type="submit" class="w-full px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition">Cari</button>
            </div>
        </form>
    </div>

    <!-- Materi Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        @forelse($materials as $material)
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition group">
            <!-- Thumbnail -->
            <div class="relative h-40 bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center overflow-hidden">
                @if($material->thumbnail_url)
                    <img src="{{ $material->thumbnail_url }}" alt="{{ $material->title }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                @else
                    <div class="text-center">
                        <svg class="w-16 h-16 text-white mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        <p class="text-white text-sm font-medium mt-2">{{ $material->type }}</p>
                    </div>
                @endif
            </div>

            <!-- Content -->
            <div class="p-4">
                <h3 class="font-semibold text-gray-900 line-clamp-2 mb-2">{{ $material->title }}</h3>
                <p class="text-gray-600 text-sm line-clamp-2 mb-3">{{ $material->description ?? 'Materi pembelajaran' }}</p>

                <!-- Category Badge -->
                <div class="flex items-center gap-2 mb-4">
                    <span class="inline-block px-2 py-1 bg-orange-100 text-orange-600 text-xs font-semibold rounded">{{ $material->category ?? 'Umum' }}</span>
                </div>

                <!-- Meta Info -->
                <div class="flex items-center justify-between text-xs text-gray-500 mb-4">
                    <span>{{ $material->created_at->format('d M Y') }}</span>
                </div>

                <!-- Action Button (Read Only - View Only) -->
                <a href="{{ $material->url ?? '#' }}" target="_blank" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white rounded-lg transition font-medium text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Lihat / Download
                </a>
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
</div>
@endsection
