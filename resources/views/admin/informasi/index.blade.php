@extends('admin.layouts.app')

@section('title', 'Informasi Terbaru - GriyaOne')
@section('role', 'Informasi Terbaru')

@section('content')
    <!-- Header + CTA -->
    <div class="mb-5 flex items-center justify-between fade-in">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 leading-tight">Informasi Terbaru</h2>
            <p class="text-sm text-gray-500 mt-0.5">
                Total <span class="font-semibold text-orange-600">{{ $informations->total() }}</span> informasi tersimpan
            </p>
        </div>
        <a href="{{ route('informasi.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-orange-500 hover:bg-orange-600 text-white rounded-lg font-semibold text-sm transition shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Tambah Baru
        </a>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="mb-4 bg-green-50 border border-green-200 rounded-lg px-4 py-3 fade-in flex items-center gap-2">
            <svg class="w-4 h-4 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <p class="text-green-800 text-sm font-medium">{{ session('success') }}</p>
        </div>
    @endif

    <!-- Filter Bar -->
    <form method="GET" action="{{ route('informasi.index') }}" class="mb-5 fade-in">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-3">
            <div class="flex flex-wrap gap-2 items-center">
                <!-- Search -->
                <div class="relative flex-1 min-w-44">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul..." class="w-full pl-9 pr-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-orange-400 focus:ring-1 focus:ring-orange-400/20 bg-gray-50 focus:bg-white transition">
                </div>
                <!-- Category -->
                <select name="category" class="py-2 px-3 border border-gray-200 rounded-lg text-sm bg-gray-50 focus:outline-none focus:border-orange-400 transition">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
                <!-- Status -->
                <select name="status" class="py-2 px-3 border border-gray-200 rounded-lg text-sm bg-gray-50 focus:outline-none focus:border-orange-400 transition">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Arsip</option>
                </select>
                <!-- Date Range -->
                <div class="flex items-center gap-1.5">
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="py-2 px-3 border border-gray-200 rounded-lg text-sm bg-gray-50 focus:outline-none focus:border-orange-400 transition w-36">
                    <span class="text-gray-400 text-xs font-medium">s/d</span>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="py-2 px-3 border border-gray-200 rounded-lg text-sm bg-gray-50 focus:outline-none focus:border-orange-400 transition w-36">
                </div>
                <!-- Actions -->
                <button type="submit" class="inline-flex items-center gap-1.5 bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg text-sm font-semibold transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    Cari
                </button>
                @if(request('search') || request('category') || request('status') || request('date_from') || request('date_to'))
                    <a href="{{ route('informasi.index') }}" class="inline-flex items-center gap-1 py-2 px-3 rounded-lg border border-gray-200 text-gray-500 hover:text-orange-600 hover:border-orange-300 hover:bg-orange-50 text-sm font-medium transition" title="Reset filter">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        Reset
                    </a>
                @endif
            </div>
        </div>
    </form>

    <!-- Table -->
    <div class="bg-white rounded-xl overflow-hidden shadow-sm border border-gray-100 fade-in">
        @if($informations->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Informasi</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kategori & Status</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap">Tgl. Publikasi</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($informations as $info)
                            <tr class="hover:bg-orange-50/30 transition-colors duration-150">
                                <!-- Foto + Judul gabung -->
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        @if($info->photo)
                                            <img src="{{ asset('storage/' . $info->photo) }}" alt="{{ $info->title }}" class="w-12 h-12 object-cover rounded-lg border border-gray-200 flex-shrink-0">
                                        @else
                                            <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                                <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            </div>
                                        @endif
                                        <div class="min-w-0">
                                            <p class="font-semibold text-gray-900 truncate max-w-xs">{{ Str::limit($info->title, 55) }}</p>
                                            <p class="text-xs text-gray-400 mt-0.5 truncate max-w-xs">{{ Str::limit(strip_tags($info->content), 70) }}</p>
                                        </div>
                                    </div>
                                </td>
                                <!-- Kategori + Status gabung -->
                                <td class="px-4 py-3">
                                    <div class="flex flex-col gap-1.5">
                                        <span class="inline-block bg-orange-100 text-orange-700 px-2.5 py-0.5 rounded-full text-xs font-semibold w-fit">
                                            {{ $info->category->name ?? 'Umum' }}
                                        </span>
                                        <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-semibold w-fit {{ $info->getStatusBadgeClass() }}">
                                            {{ $info->getStatusLabel() }}
                                        </span>
                                    </div>
                                </td>
                                <!-- Tanggal -->
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <p class="text-gray-700 font-medium">{{ $info->published_date->format('d M Y') }}</p>
                                    <p class="text-xs text-gray-400">{{ $info->published_date->format('H:i') }}</p>
                                </td>
                                <!-- Aksi ikon -->
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-center gap-1">
                                        <a href="{{ route('informasi.show', $info->id) }}" title="Lihat" class="p-1.5 rounded-lg text-orange-500 hover:bg-orange-50 hover:text-orange-700 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </a>
                                        <a href="{{ route('informasi.edit', $info->id) }}" title="Edit" class="p-1.5 rounded-lg text-amber-500 hover:bg-amber-50 hover:text-amber-700 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>
                                        <form action="{{ route('informasi.destroy', $info->id) }}" method="POST" class="inline" data-confirm="Apakah Anda yakin ingin menghapus informasi ini?">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Hapus" class="p-1.5 rounded-lg text-red-400 hover:bg-red-50 hover:text-red-600 transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="bg-gray-50 border-t border-gray-100 px-4 py-3">
                {{ $informations->links() }}
            </div>
        @else
            <div class="flex flex-col items-center justify-center py-16">
                <div class="w-16 h-16 bg-orange-50 rounded-2xl flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-orange-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h3 class="text-base font-semibold text-gray-900 mb-1">Tidak ada informasi ditemukan</h3>
                <p class="text-gray-500 text-sm mb-5">Mulai buat informasi baru atau ubah filter pencarian</p>
                <a href="{{ route('informasi.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-orange-500 hover:bg-orange-600 text-white rounded-lg font-semibold text-sm transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    Tambah Informasi Baru
                </a>
            </div>
        @endif
    </div>
@endsection


