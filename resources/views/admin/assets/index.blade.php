@extends('admin.layouts.app')

@section('title', 'Manajemen Listing Aset - GriyaOne')
@section('role', 'Manajemen Listing Aset')

@section('content')
    <!-- Header -->
    <div class="mb-8 flex justify-between items-start fade-in">
        <div>
            <h2 class="text-3xl font-bold text-gray-900 mb-1">Manajemen Listing Aset</h2>
            <p class="text-gray-600">Kelola aset Bank Cessie, AYDA, dan Lelang</p>
        </div>
        <a href="{{ route('assets.create') }}" class="bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 px-6 py-2 rounded-lg text-white font-medium text-sm transition-all duration-200 shadow-md hover:shadow-lg">
            + Tambah Aset Baru
        </a>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4 fade-in">
            <p class="text-green-800 font-medium">{{ session('success') }}</p>
        </div>
    @endif

    <!-- Filter & Search -->
    <form method="GET" action="{{ route('assets.index') }}" class="mb-6 flex gap-4 flex-wrap items-center fade-in">
        <div class="flex gap-2 flex-1 min-w-64">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul atau lokasi aset..." class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-red-500 transition">
        </div>
        <select name="category" class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-red-500 transition">
            <option value="">Semua Kategori</option>
            <option value="Bank Cessie" {{ request('category') == 'Bank Cessie' ? 'selected' : '' }}>Bank Cessie</option>
            <option value="AYDA" {{ request('category') == 'AYDA' ? 'selected' : '' }}>AYDA</option>
            <option value="Lelang" {{ request('category') == 'Lelang' ? 'selected' : '' }}>Lelang</option>
        </select>
        <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-red-500 transition">
            <option value="">Semua Status</option>
            <option value="Available" {{ request('status') == 'Available' ? 'selected' : '' }}>Available</option>
            <option value="Sold Out" {{ request('status') == 'Sold Out' ? 'selected' : '' }}>Sold Out</option>
        </select>
        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg font-medium text-sm transition">Cari</button>
        @if(request('search') || request('category') || request('status'))
            <a href="{{ route('assets.index') }}" class="text-gray-500 hover:text-gray-700 text-sm font-medium transition">✕ Reset</a>
        @endif
    </form>

    <!-- Assets Table -->
    <div class="bg-white rounded-xl overflow-hidden shadow-md fade-in">
        @if($assets->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Judul</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Kategori</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Alamat</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Foto</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($assets as $asset)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $asset->title }}</td>
                                <td class="px-6 py-4 text-sm">
                                    @php
                                        $colors = [
                                            'Bank Cessie' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-700'],
                                            'AYDA' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-700'],
                                            'Lelang' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-700'],
                                        ];
                                        $color = $colors[$asset->category] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-700'];
                                    @endphp
                                    <span class="{{ $color['bg'] }} {{ $color['text'] }} px-3 py-1 rounded-full text-xs font-semibold">{{ $asset->category }}</span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $asset->location ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm">
                                    @if($asset->status === 'Available')
                                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">Available</span>
                                    @else
                                        <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-semibold">Sold Out</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    @if($asset->photos && count($asset->photos) > 0)
                                        <div class="flex items-center gap-1">
                                            @foreach(array_slice($asset->photos, 0, 3) as $photo)
                                                <img src="{{ asset('storage/' . $photo) }}" alt="Foto" class="w-10 h-10 rounded object-cover border border-gray-200">
                                            @endforeach
                                            @if(count($asset->photos) > 3)
                                                <span class="w-10 h-10 rounded bg-gray-100 border border-gray-200 flex items-center justify-center text-xs font-semibold text-gray-500">+{{ count($asset->photos) - 3 }}</span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-gray-400 text-xs">Tidak ada</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center gap-2">
                                        <a href="{{ route('assets.show', $asset) }}" class="text-orange-600 hover:text-orange-700 font-medium text-xs transition">Lihat</a>
                                        <a href="{{ route('assets.edit', $asset) }}" class="text-yellow-600 hover:text-yellow-700 font-medium text-xs transition">Ubah</a>
                                        <form action="{{ route('assets.destroy', $asset) }}" method="POST" style="display:inline;" data-confirm="Yakin ingin menghapus aset ini? Tindakan ini tidak dapat dibatalkan.">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-700 font-medium text-xs transition">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $assets->links() }}
            </div>
        @else
            <div class="p-12 text-center">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                </svg>
                <h4 class="text-gray-900 font-semibold mb-2">Belum Ada Aset</h4>
                <p class="text-gray-600 text-sm mb-4">Mulai dengan menambahkan aset pertama Anda</p>
                <a href="{{ route('assets.create') }}" class="inline-block bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg font-medium text-sm transition">Tambah Aset</a>
            </div>
        @endif
    </div>

    <!-- Stats Cards -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-4 gap-6 fade-in">
        <div class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-lg transition">
            <div class="bg-gradient-to-br from-orange-500 to-orange-600 p-6 text-white">
                <h3 class="text-sm font-semibold mb-2 opacity-90">Bank Cessie</h3>
                <p class="text-3xl font-bold">{{ \App\Models\Asset::where('category', 'Bank Cessie')->count() }}</p>
            </div>
        </div>
        <div class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-lg transition">
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 p-6 text-white">
                <h3 class="text-sm font-semibold mb-2 opacity-90">AYDA</h3>
                <p class="text-3xl font-bold">{{ \App\Models\Asset::where('category', 'AYDA')->count() }}</p>
            </div>
        </div>
        <div class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-lg transition">
            <div class="bg-gradient-to-br from-orange-500 to-orange-600 p-6 text-white">
                <h3 class="text-sm font-semibold mb-2 opacity-90">Lelang</h3>
                <p class="text-3xl font-bold">{{ \App\Models\Asset::where('category', 'Lelang')->count() }}</p>
            </div>
        </div>
        <div class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-lg transition">
            <div class="bg-gradient-to-br from-green-500 to-green-600 p-6 text-white">
                <h3 class="text-sm font-semibold mb-2 opacity-90">Available</h3>
                <p class="text-3xl font-bold">{{ \App\Models\Asset::where('status', 'Available')->count() }}</p>
            </div>
        </div>
    </div>
@endsection


