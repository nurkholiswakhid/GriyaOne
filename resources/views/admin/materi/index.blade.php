@extends('admin.layouts.app')

@section('title', 'Materi PDF - GriyaOne')
@section('role', 'Materi PDF')

@section('content')
    <!-- Header -->
    <div class="mb-8 flex justify-between items-start fade-in">
        <div>
            <h2 class="text-3xl font-bold text-gray-900 mb-1">Materi PDF</h2>
            <p class="text-gray-600">Kelola kumpulan materi dalam bentuk PDF untuk pembelajaran</p>
        </div>
        <a href="{{ route('materi.create') }}" class="bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 px-6 py-2 rounded-lg text-white font-medium text-sm transition-all duration-200 shadow-md hover:shadow-lg">
            + Tambah Materi
        </a>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4 fade-in">
            <p class="text-green-800 font-medium">{{ session('success') }}</p>
        </div>
    @endif

    <!-- Filter & Search -->
    <form method="GET" action="{{ route('materi.index') }}" class="mb-6 fade-in">
        <div class="bg-white rounded-xl shadow-md p-4">
            <div class="flex items-center gap-2 flex-wrap">
                <!-- Search Input -->
                <div class="relative flex-1 min-w-64">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" /></svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari materi..." class="w-full pl-9 pr-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500/20 transition bg-gray-50/80 focus:bg-white">
                </div>

                <!-- Status -->
                <select name="status" class="py-2 px-3 border border-gray-200 rounded-lg text-sm bg-gray-50/80 focus:outline-none focus:border-red-500 transition" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Publik</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                </select>

                <!-- Category -->
                <select name="category" class="py-2 px-3 border border-gray-200 rounded-lg text-sm bg-gray-50/80 focus:outline-none focus:border-red-500 transition" onchange="this.form.submit()">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
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
                    <a href="{{ route('materi.index') }}" class="text-gray-400 hover:text-red-600 p-2 rounded-lg hover:bg-red-50 transition shrink-0" title="Reset">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </a>
                @endif
            </div>

            <!-- Active Filters -->
            @if(request('search') || request('status'))
                <div class="mt-3 pt-3 border-t border-gray-100 flex items-center gap-2 text-xs text-gray-500 flex-wrap">
                    <span>{{ $materials->total() }} hasil</span>
                    @if(request('search'))
                        <a href="{{ route('materi.index', array_merge(request()->except('search', 'page'))) }}" class="inline-flex items-center gap-1 bg-red-50 text-red-600 px-2 py-0.5 rounded-md hover:bg-red-100 transition">
                            "{{ Str::limit(request('search'), 20) }}"
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </a>
                    @endif
                    @if(request('status'))
                        <a href="{{ route('materi.index', array_merge(request()->except('status', 'page'))) }}" class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md transition" :class="{
                            'bg-green-50 text-green-600 hover:bg-green-100': '{{ request('status') }}' === 'published',
                            'bg-gray-100 text-gray-600 hover:bg-gray-200': '{{ request('status') }}' === 'draft'
                        }">
                            {{ request('status') === 'published' ? 'Publik' : 'Draft' }}
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </form>

    <!-- Materi Grid -->
    @if($materials->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 fade-in">
            @foreach($materials as $material)
                <div class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-lg transition group">
                    <!-- Thumbnail -->
                    <div class="relative h-48 bg-gradient-to-br from-purple-100 to-purple-50 overflow-hidden cursor-pointer flex items-center justify-center">
                        @if($material->thumbnail)
                            <img src="{{ asset('storage/' . $material->thumbnail) }}" alt="{{ $material->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @else
                            <div class="text-center">
                                <svg class="w-16 h-16 text-purple-300 mx-auto mb-2" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                <p class="text-sm text-purple-500 font-medium">PDF</p>
                            </div>
                        @endif
                        <div class="absolute inset-0 bg-black opacity-0 group-hover:opacity-20 transition"></div>
                        @if($material->is_published)
                            <span class="absolute top-3 right-3 bg-green-500 text-white px-2 py-1 rounded text-xs font-semibold">Publik</span>
                        @else
                            <span class="absolute top-3 right-3 bg-gray-500 text-white px-2 py-1 rounded text-xs font-semibold">Draft</span>
                        @endif
                    </div>

                    <!-- Content -->
                    <div class="p-4">
                        <!-- Title -->
                        <h3 class="font-semibold text-gray-900 mb-1 line-clamp-2 text-sm">{{ $material->title }}</h3>

                        <!-- Description -->
                        <p class="text-xs text-gray-600 mb-3 line-clamp-2">{{ $material->description ?? 'Tidak ada deskripsi' }}</p>

                        <!-- Category -->
                        @if($material->category)
                            <div class="mb-3">
                                <span class="inline-block bg-purple-100 text-purple-700 text-xs font-semibold px-2 py-1 rounded">
                                    {{ $material->category->name }}
                                </span>
                            </div>
                        @endif

                        <!-- Date -->
                        <div class="mb-4 text-xs text-gray-500">
                            <span class="text-gray-400">{{ $material->created_at->format('d M Y') }}</span>
                        </div>

                        <!-- Actions -->
                        <div class="flex gap-2">
                            <a href="{{ route('materi.edit', $material) }}" class="flex-1 bg-orange-600 hover:bg-orange-700 text-white text-xs font-semibold py-2 rounded transition text-center">
                                Edit
                            </a>
                            <a href="{{ route('materi.show', $material) }}" class="flex-1 bg-purple-600 hover:bg-purple-700 text-white text-xs font-semibold py-2 rounded transition text-center">
                                Lihat
                            </a>
                            <form action="{{ route('materi.destroy', $material) }}" method="POST" style="flex: 1;" data-confirm="Yakin ingin menghapus materi ini? Tindakan ini tidak dapat dibatalkan.">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white text-xs font-semibold py-2 rounded transition">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8 fade-in">
            {{ $materials->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-xl shadow-md p-12 text-center fade-in">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Belum ada materi</h3>
            <p class="text-gray-600 mb-6">Mulai tambahkan materi PDF untuk pembelajaran</p>
            <a href="{{ route('materi.create') }}" class="inline-block bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg font-medium transition">
                + Tambah Materi Pertama
            </a>
        </div>
    @endif
@endsection


