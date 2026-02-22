@extends('admin.layouts.app')

@section('title', 'Video Media Pembelajaran - GriyaOne')
@section('role', 'Video Media Pembelajaran')

@section('content')
    <!-- Header -->
    <div class="mb-8 flex justify-between items-start fade-in">
        <div>
            <h2 class="text-3xl font-bold text-gray-900 mb-1">Video Media Pembelajaran</h2>
            <p class="text-gray-600">Kelola video pembelajaran untuk tim marketing GriyaOne</p>
        </div>
        <a href="{{ route('contents.create') }}" class="inline-flex items-center px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Tambah Video Baru
        </a>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4 fade-in">
            <p class="text-green-800 font-medium">{{ session('success') }}</p>
        </div>
    @endif

    <!-- Filter & Search -->
    <form method="GET" action="{{ route('contents.index') }}" class="mb-6 fade-in">
        <div class="bg-white rounded-xl shadow-md p-4">
            <div class="flex items-center gap-2">
                <!-- Search Input -->
                <div class="relative flex-1">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" /></svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="   Cari video..." class="w-full pl-9 pr-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500/20 transition bg-gray-50/80 focus:bg-white">
                </div>

                <!-- Status -->
                <select name="status" class="py-2 px-3 border border-gray-200 rounded-lg text-sm bg-gray-50/80 focus:outline-none focus:border-red-500 transition" onchange="this.form.submit()">
                    <option value="">Status</option>
                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Publik</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                </select>

                <!-- Sort -->
                <select name="sort" class="py-2 px-3 border border-gray-200 rounded-lg text-sm bg-gray-50/80 focus:outline-none focus:border-red-500 transition" onchange="this.form.submit()">
                    <option value="terbaru" {{ request('sort', 'terbaru') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                    <option value="terlama" {{ request('sort') == 'terlama' ? 'selected' : '' }}>Terlama</option>
                    <option value="judul_az" {{ request('sort') == 'judul_az' ? 'selected' : '' }}>A-Z</option>
                    <option value="judul_za" {{ request('sort') == 'judul_za' ? 'selected' : '' }}>Z-A</option>
                </select>

                <!-- Search Button -->
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white p-2 rounded-lg transition shrink-0" title="Cari">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </button>

                @if(request('search') || request('status') || (request('sort') && request('sort') !== 'terbaru'))
                    <a href="{{ route('contents.index') }}" class="text-gray-400 hover:text-red-600 p-2 rounded-lg hover:bg-red-50 transition shrink-0" title="Reset">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </a>
                @endif
            </div>

            <!-- Active Filters -->
            @if(request('search') || request('status'))
                <div class="mt-3 pt-3 border-t border-gray-100 flex items-center gap-2 text-xs text-gray-500">
                    <span>{{ $contents->total() }} hasil</span>
                    <span class="text-gray-300">|</span>
                    @if(request('search'))
                        <a href="{{ route('contents.index', array_merge(request()->except('search', 'page'))) }}" class="inline-flex items-center gap-1 bg-red-50 text-red-600 px-2 py-0.5 rounded-md hover:bg-red-100 transition">
                            "{{ Str::limit(request('search'), 20) }}"
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </a>
                    @endif
                    @if(request('status'))
                        @if(request('status') == 'published')
                            <a href="{{ route('contents.index', array_merge(request()->except('status', 'page'))) }}" class="inline-flex items-center gap-1 bg-green-50 text-green-600 px-2 py-0.5 rounded-md hover:bg-green-100 transition">
                                Publik
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            </a>
                        @else
                            <a href="{{ route('contents.index', array_merge(request()->except('status', 'page'))) }}" class="inline-flex items-center gap-1 bg-gray-100 text-gray-600 px-2 py-0.5 rounded-md hover:bg-gray-200 transition">
                                Draft
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            </a>
                        @endif
                    @endif
                </div>
            @endif
        </div>
    </form>

    <!-- Video Grid -->
    @if($contents->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 fade-in">
            @foreach($contents as $content)
                <div class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-lg transition group">
                    <!-- Video Thumbnail -->
                    <div class="relative h-48 bg-gray-900 overflow-hidden cursor-pointer" onclick="window.location='{{ route('contents.show', $content) }}'">
                        @if($content->thumbnail)
                            <img src="{{ asset('storage/' . $content->thumbnail) }}" alt="{{ $content->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @elseif($content->file_path && $content->getYoutubeId())
                            <img src="https://img.youtube.com/vi/{{ $content->getYoutubeId() }}/hqdefault.jpg" alt="{{ $content->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-red-800 to-red-950">
                                <svg class="w-16 h-16 text-white/30" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M8 5v14l11-7z"/>
                                </svg>
                            </div>
                        @endif

                        <!-- Play Button Overlay -->
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 flex items-center justify-center transition-all duration-300">
                            <div class="w-14 h-14 bg-red-600 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transform scale-75 group-hover:scale-100 transition-all duration-300 shadow-lg">
                                <svg class="w-6 h-6 text-white ml-1" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M8 5v14l11-7z"/>
                                </svg>
                            </div>
                        </div>

                        <!-- Status Badge -->
                        <div class="absolute top-3 right-3">
                            @if($content->is_published)
                                <span class="bg-green-500 text-white px-2.5 py-1 rounded-full text-xs font-semibold shadow">Publik</span>
                            @else
                                <span class="bg-gray-500 text-white px-2.5 py-1 rounded-full text-xs font-semibold shadow">Draft</span>
                            @endif
                        </div>


                    </div>

                    <!-- Video Info -->
                    <div class="p-4">
                        <h3 class="font-bold text-gray-900 mb-2 line-clamp-2 leading-snug">{{ $content->title }}</h3>

                        <!-- Meta Info -->
                        <div class="flex items-center gap-3 text-xs text-gray-500 mb-4">
                            <span>{{ $content->created_at->diffForHumans() }}</span>
                        </div>

                        <!-- Actions -->
                        <div class="flex gap-2">
                            <a href="{{ route('contents.show', $content) }}" class="flex-1 bg-orange-50 hover:bg-orange-100 text-orange-700 px-3 py-2 rounded-lg text-xs font-medium transition text-center">Lihat</a>
                            <a href="{{ route('contents.edit', $content) }}" class="flex-1 bg-yellow-50 hover:bg-yellow-100 text-yellow-700 px-3 py-2 rounded-lg text-xs font-medium transition text-center">Ubah</a>
                            <form action="{{ route('contents.destroy', $content) }}" method="POST" style="flex:1" data-confirm="Yakin ingin menghapus video ini? Tindakan ini tidak dapat dibatalkan.">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full bg-red-50 hover:bg-red-100 text-red-700 px-3 py-2 rounded-lg text-xs font-medium transition">Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($contents->hasPages())
            <div class="mt-8 fade-in">
                {{ $contents->links() }}
            </div>
        @endif
    @else
        <div class="bg-white rounded-xl p-12 text-center shadow-md fade-in">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
            </svg>
            <h4 class="text-gray-900 font-semibold mb-2">Belum Ada Video</h4>
            <p class="text-gray-600 text-sm mb-4">Mulai dengan menambahkan video pembelajaran pertama</p>
            <a href="{{ route('contents.create') }}" class="inline-flex items-center px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Tambah Video Baru
        </a>
        </div>
    @endif
@endsection


