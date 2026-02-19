@extends('marketing.layouts.app')

@section('title', 'Media Pembelajaran - Marketing')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">📚 Media Pembelajaran</h1>
        <p class="text-gray-600 mt-2">Tingkatkan kompetensi tim dengan video pembelajaran, materi PDF, dan dokumen pendukung</p>
    </div>

    <!-- Filter & Search -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Konten</label>
                <select name="type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Tipe</option>
                    <option value="Video" {{ request('type') === 'Video' ? 'selected' : '' }}>📹 Video</option>
                    <option value="Materi" {{ request('type') === 'Materi' ? 'selected' : '' }}>📄 Materi</option>
                    <option value="Info" {{ request('type') === 'Info' ? 'selected' : '' }}>ℹ️ Informasi</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                <select name="category" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Kategori</option>
                    <option value="Training" {{ request('category') === 'Training' ? 'selected' : '' }}>🎓 Training</option>
                    <option value="Challenge" {{ request('category') === 'Challenge' ? 'selected' : '' }}>🎯 Challenge</option>
                    <option value="Bonus" {{ request('category') === 'Bonus' ? 'selected' : '' }}>🎁 Bonus</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">&nbsp;</label>
                <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Cari</button>
            </div>
        </form>
    </div>

    <!-- Content Grid -->
    @php
        $contents = \App\Models\Content::where('is_published', true);
        if(request('type')) $contents->where('type', request('type'));
        if(request('category')) $contents->where('category', request('category'));
        $contents = $contents->latest()->paginate(12);
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        @forelse($contents as $content)
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
            <!-- Content Image/Icon Section -->
            <div class="h-48 bg-gradient-to-br flex items-center justify-center relative overflow-hidden
                @if($content->type === 'Video')
                    from-red-400 to-red-600
                @elseif($content->type === 'Materi')
                    from-blue-400 to-blue-600
                @else
                    from-green-400 to-green-600
                @endif
            ">
                @if($content->type === 'Video')
                    <svg class="w-16 h-16 text-white opacity-80" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M8 5v14l11-7z"></path>
                    </svg>
                @elseif($content->type === 'Materi')
                    <svg class="w-16 h-16 text-white opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                @else
                    <svg class="w-16 h-16 text-white opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                @endif

                <!-- Type Badge -->
                <div class="absolute top-3 left-3">
                    @if($content->type === 'Video')
                        <span class="inline-block px-3 py-1 bg-white bg-opacity-90 text-red-600 text-xs font-bold rounded-full">📹 Video</span>
                    @elseif($content->type === 'Materi')
                        <span class="inline-block px-3 py-1 bg-white bg-opacity-90 text-blue-600 text-xs font-bold rounded-full">📄 Materi</span>
                    @else
                        <span class="inline-block px-3 py-1 bg-white bg-opacity-90 text-green-600 text-xs font-bold rounded-full">ℹ️ Info</span>
                    @endif
                </div>

                <!-- Category Badge -->
                <div class="absolute top-3 right-3">
                    @if($content->category === 'Training')
                        <span class="inline-block px-3 py-1 bg-yellow-500 text-white text-xs font-bold rounded-full">🎓 Training</span>
                    @elseif($content->category === 'Challenge')
                        <span class="inline-block px-3 py-1 bg-purple-500 text-white text-xs font-bold rounded-full">🎯 Challenge</span>
                    @else
                        <span class="inline-block px-3 py-1 bg-pink-500 text-white text-xs font-bold rounded-full">🎁 Bonus</span>
                    @endif
                </div>
            </div>

            <!-- Content Info -->
            <div class="p-4">
                <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $content->title }}</h3>

                <div class="flex items-center gap-4 text-sm text-gray-600 mb-4">
                    @if($content->type === 'Video' && $content->duration)
                    <div class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 2m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>{{ $content->duration }} menit</span>
                    </div>
                    @endif

                    <div class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        <span>{{ $content->views }} views</span>
                    </div>
                </div>

                <!-- Description -->
                <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ substr($content->title, 0, 50) }}...</p>

                <!-- Action Buttons -->
                <div class="flex gap-2">
                    @if($content->type === 'Video')
                    <a href="#" class="flex-1 px-3 py-2 bg-red-600 text-white text-sm font-semibold rounded-lg hover:bg-red-700 transition flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M8 5v14l11-7z"></path>
                        </svg>
                        Tonton
                    </a>
                    @elseif($content->type === 'Materi')
                    <a href="#" class="flex-1 px-3 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Download
                    </a>
                    @else
                    <a href="#" class="flex-1 px-3 py-2 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 transition flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        Baca
                    </a>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-12">
            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C6.248 6.253 2 10.998 2 16.5S6.248 26.747 12 26.747s10-4.745 10-10.247S17.752 6.253 12 6.253z"></path>
            </svg>
            <p class="text-gray-500 font-medium">Tidak ada konten yang sesuai dengan filter</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mb-8">
        {{ $contents->links() }}
    </div>

    <!-- Learning Stats -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">📊 Statistik Pembelajaran</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="text-center">
                <p class="text-4xl font-bold text-blue-600">{{ \App\Models\Content::where('type', 'Video')->where('is_published', true)->count() }}</p>
                <p class="text-sm text-gray-600 mt-2">📹 Video Pembelajaran</p>
            </div>
            <div class="text-center">
                <p class="text-4xl font-bold text-blue-600">{{ \App\Models\Content::where('type', 'Materi')->where('is_published', true)->count() }}</p>
                <p class="text-sm text-gray-600 mt-2">📄 Materi PDF</p>
            </div>
            <div class="text-center">
                <p class="text-4xl font-bold text-blue-600">{{ \App\Models\Content::where('type', 'Info')->where('is_published', true)->count() }}</p>
                <p class="text-sm text-gray-600 mt-2">ℹ️ Informasi</p>
            </div>
            <div class="text-center">
                <p class="text-4xl font-bold text-blue-600">{{ \App\Models\Content::where('is_published', true)->sum('views') }}</p>
                <p class="text-sm text-gray-600 mt-2">👁️ Total Views</p>
            </div>
        </div>
    </div>
</div>
@endsection
