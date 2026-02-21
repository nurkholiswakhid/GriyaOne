@extends('admin.layouts.app')

@section('title', 'Informasi Terbaru - GriyaOne')
@section('role', 'Informasi Terbaru')

@section('content')
    <!-- Header -->
    <div class="mb-8 flex justify-between items-start fade-in">
        <div>
            <h2 class="text-3xl font-bold text-gray-900 mb-1">Informasi Terbaru</h2>
            <p class="text-gray-600">Kelola informasi yang diupdate setiap hari</p>
        </div>
        <a href="{{ route('informasi.create') }}" class="bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 px-6 py-2 rounded-lg text-white font-medium text-sm transition-all duration-200 shadow-md hover:shadow-lg">
            + Tambah Informasi Baru
        </a>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4 fade-in">
            <p class="text-green-800 font-medium">{{ session('success') }}</p>
        </div>
    @endif

    <!-- Filter & Search -->
    <form method="GET" action="{{ route('informasi.index') }}" class="mb-6 flex gap-4 flex-wrap items-center fade-in">
        <div class="flex gap-2 flex-1 min-w-64">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul atau konten..." class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-red-500 transition">
        </div>
        <select name="category" class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-red-500 transition">
            <option value="">Semua Kategori</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
            @endforeach
        </select>
        <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-red-500 transition">
            <option value="">Semua Status</option>
            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
            <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Arsip</option>
        </select>
        <input type="date" name="date_from" value="{{ request('date_from') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-red-500 transition">-
        <input type="date" name="date_to" value="{{ request('date_to') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-red-500 transition">
        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg font-medium text-sm transition">Cari</button>
        @if(request('search') || request('category') || request('status') || request('date_from') || request('date_to'))
            <a href="{{ route('informasi.index') }}" class="text-gray-500 hover:text-gray-700 text-sm font-medium transition">✕ Reset</a>
        @endif
    </form>

    <!-- Informations Table -->
    <div class="bg-white rounded-xl overflow-hidden shadow-md fade-in">
        @if($informations->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100 border-b-2 border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Foto</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Judul</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Kategori</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Tanggal Publikasi</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($informations as $info)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-6 py-4">
                                    @if($info->photo)
                                        <img src="{{ asset('storage/' . $info->photo) }}" alt="{{ $info->title }}" class="w-16 h-16 object-cover rounded-lg border border-gray-200">
                                    @else
                                        <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-medium text-gray-900">{{ Str::limit($info->title, 50) }}</p>
                                    <p class="text-xs text-gray-500 mt-1">{{ Str::limit(strip_tags($info->content), 80) }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-block bg-orange-100 text-orange-800 px-3 py-1 rounded-full text-xs font-semibold">
                                        {{ $info->category->name ?? 'Umum' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm text-gray-600">{{ $info->published_date->format('d M Y') }}</p>
                                    <p class="text-xs text-gray-400">{{ $info->published_date->format('H:i') }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold {{ $info->getStatusBadgeClass() }}">
                                        {{ $info->getStatusLabel() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex gap-2">
                                        <a href="{{ route('informasi.show', $info->id) }}" class="text-orange-600 hover:text-orange-900 font-semibold text-sm">
                                            Lihat
                                        </a>
                                        <a href="{{ route('informasi.edit', $info->id) }}" class="text-amber-600 hover:text-amber-900 font-semibold text-sm">
                                            Edit
                                        </a>
                                        <form action="{{ route('informasi.destroy', $info->id) }}" method="POST" class="inline" data-confirm="Apakah Anda yakin ingin menghapus informasi ini?">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 font-semibold text-sm">
                                                Hapus
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
            <div class="bg-gray-50 border-t border-gray-200 px-6 py-4">
                {{ $informations->links() }}
            </div>
        @else
            <div class="flex flex-col items-center justify-center py-12">
                <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="text-xl font-semibold text-gray-900 mb-1">Tidak ada informasi</h3>
                <p class="text-gray-600 mb-4">Mulai buat informasi baru untuk menampilkan di sini</p>
                <a href="{{ route('informasi.create') }}" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg font-medium text-sm transition">
                    + Tambah Informasi
                </a>
            </div>
        @endif
    </div>
@endsection


