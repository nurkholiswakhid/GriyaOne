@extends('user.layouts.app')

@section('title', 'Materi Pembelajaran - GriyaOne')
@section('content')
    <div class="min-h-screen bg-gray-50 pt-8 pb-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-12">
                <h1 class="text-4xl font-bold text-gray-900 mb-3">📚 Materi Pembelajaran</h1>
                <p class="text-lg text-gray-600">Kumpulan materi PDF untuk mendukung pembelajaran Anda di GriyaOne</p>
            </div>

            <!-- Filter & Search -->
            <div class="mb-8">
                <form method="GET" action="{{ route('user.materials') }}" class="bg-white rounded-xl shadow-md p-6">
                    <div class="flex items-center gap-2 flex-wrap">
                        <!-- Search Input -->
                        <div class="relative flex-1 min-w-64">
                            <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari materi pembelajaran..." class="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500/20 transition">
                        </div>

                        <!-- Sort -->
                        <select name="sort" class="py-2 px-3 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-red-500 transition" onchange="this.form.submit()">
                            <option value="terbaru" {{ request('sort', 'terbaru') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                            <option value="terlama" {{ request('sort') == 'terlama' ? 'selected' : '' }}>Terlama</option>
                            <option value="judul_az" {{ request('sort') == 'judul_az' ? 'selected' : '' }}>A-Z</option>
                            <option value="judul_za" {{ request('sort') == 'judul_za' ? 'selected' : '' }}>Z-A</option>
                            <option value="populer" {{ request('sort') == 'populer' ? 'selected' : '' }}>Paling Populer</option>
                        </select>

                        <!-- Search Button -->
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition font-medium">
                            Cari
                        </button>

                        @if(request('search') || request('category') || (request('sort') && request('sort') !== 'terbaru'))
                            <a href="{{ route('user.materials') }}" class="text-gray-500 hover:text-red-600 px-3 py-2 rounded-lg hover:bg-red-50 transition font-medium">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Materials Grid -->
            @if($materials->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                    @foreach($materials as $material)
                        <div class="group bg-white rounded-xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                            <!-- Thumbnail -->
                            <div class="relative h-56 bg-gradient-to-br from-purple-100 to-purple-50 overflow-hidden flex items-center justify-center">
                                @if($material->thumbnail)
                                    <img src="{{ asset('storage/' . $material->thumbnail) }}" alt="{{ $material->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                @else
                                    <div class="text-center">
                                        <svg class="w-20 h-20 text-purple-300 mx-auto mb-2" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                        <p class="text-xl text-purple-500 font-bold">PDF</p>
                                    </div>
                                @endif

                                <!-- Download Button on Hover -->
                                <div class="absolute inset-0 bg-black opacity-0 group-hover:opacity-40 transition flex items-center justify-center">
                                    <a href="{{ asset('storage/' . $material->file_path) }}" download class="opacity-0 group-hover:opacity-100 transition transform group-hover:scale-100 scale-75">
                                        <div class="bg-red-600 text-white p-4 rounded-full hover:bg-red-700 transition">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                        </div>
                                    </a>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="p-5">
                                <!-- Title -->
                                <h3 class="font-bold text-gray-900 mb-2 line-clamp-2 group-hover:text-red-600 transition">{{ $material->title }}</h3>

                                <!-- Description -->
                                <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $material->description ?? 'Materi pembelajaran berkualitas' }}</p>

                                <!-- Stats -->
                                <div class="flex items-center justify-between mb-4 text-xs text-gray-500">
                                    <span>{{ $material->created_at->format('d M Y') }}</span>
                                </div>

                                <!-- Download Button -->
                                <a href="{{ asset('storage/' . $material->file_path) }}" download class="w-full bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-semibold py-2 rounded-lg transition-all duration-200 flex items-center justify-center gap-2 text-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    Unduh PDF
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="flex justify-center">
                    {{ $materials->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="bg-white rounded-xl shadow-md p-16 text-center">
                    <svg class="w-20 h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Belum ada materi</h3>
                    <p class="text-gray-600 mb-6">Materi pembelajaran akan segera tersedia. Silakan cek kembali nanti!</p>
                    <a href="{{ route('user.dashboard') }}" class="inline-block bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg font-medium transition">
                        Kembali ke Dashboard
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection
