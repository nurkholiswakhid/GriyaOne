@extends('marketing.layouts.app')

@section('title', 'Media Pembelajaran - Marketing')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Video Media Pembelajaran</h1>
        <p class="text-gray-600 mt-2">Tingkatkan kompetensi tim dengan video pembelajaran berkualitas tinggi</p>
    </div>

    <!-- Filter & Search -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <p class="text-gray-700">Semua materi pembelajaran tersedia di bawah ini.</p>
    </div>

    <!-- Content Grid -->
    @php
        $contents = \App\Models\Content::where('is_published', true)->latest()->paginate(12);
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        @forelse($contents as $content)
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
            <!-- Content Image/Icon Section -->
            <div class="h-48 bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center relative overflow-hidden">
                <svg class="w-16 h-16 text-white opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C6.248 6.253 2 10.998 2 16.5S6.248 26.747 12 26.747s10-4.745 10-10.247S17.752 6.253 12 6.253z"></path>
                </svg>
            </div>

            <!-- Content Info -->
            <div class="p-4">
                <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $content->title }}</h3>

                <!-- Description -->
                <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $content->description ?? 'Materi pembelajaran' }}</p>

                <!-- Action Buttons -->
                <div class="flex gap-2">
                    <a href="#" class="flex-1 px-3 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        Lihat
                    </a>
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
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Statistik Pembelajaran</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="text-center">
                <p class="text-4xl font-bold text-blue-600">{{ \App\Models\Content::where('is_published', true)->count() }}</p>
                <p class="text-sm text-gray-600 mt-2">Total Materi Pembelajaran</p>
            </div>
            <div class="text-center">
                <p class="text-4xl font-bold text-blue-600">{{ \App\Models\Content::count() }}</p>
                <p class="text-sm text-gray-600 mt-2">Total Konten</p>
            </div>
        </div>
    </div>
</div>
@endsection
