@extends('user.layouts.app')

@section('title', 'Dashboard - GriyaOne')
@section('role', 'Dashboard')

@section('content')

{{-- Header --}}
<div class="mb-5">
    <h2 class="text-xl font-semibold text-gray-800">Selamat datang, {{ Auth::user()?->name ?? 'Guest' }}</h2>
    <p class="text-sm text-gray-500 mt-0.5">Statistik properti {{ \App\Models\Setting::get('site_name','GriyaOne') }}</p>
</div>

{{-- Stat Cards — Single Horizontal Bar --}}
<div class="bg-white border border-gray-200 rounded-xl flex divide-x divide-gray-200 mb-3">
    <div class="flex-1 px-6 py-4">
        <p class="text-xs text-gray-400 mb-1">Total Listing</p>
        <p class="text-2xl font-bold text-gray-800">{{ $totalListings }}</p>
    </div>
    <div class="flex-1 px-6 py-4">
        <p class="text-xs text-gray-400 mb-1">Tersedia</p>
        <p class="text-2xl font-bold text-green-600">{{ $availableListings }}</p>
    </div>
    <div class="flex-1 px-6 py-4">
        <p class="text-xs text-gray-400 mb-1">Terjual</p>
        <p class="text-2xl font-bold text-red-500">{{ $soldListings }}</p>
    </div>
</div>

{{-- Horizontal Layout: Left Panel + Right Panel --}}
<div class="flex gap-5 items-start">

    {{-- LEFT PANEL --}}
    <div class="w-56 flex-shrink-0 space-y-4">

        {{-- Category Breakdown --}}
        @if($listingsByCategory->count())
        @php $catColors = ['Bank Cessie'=>'bg-blue-100 text-blue-700','AYDA'=>'bg-purple-100 text-purple-700','Lelang'=>'bg-orange-100 text-orange-700']; @endphp
        <div class="bg-white border border-gray-200 rounded-xl p-4">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Kategori</p>
            <div class="space-y-2">
                @foreach($listingsByCategory as $cat)
                @php $badge = $catColors[$cat->category] ?? 'bg-gray-100 text-gray-600'; @endphp
                <div class="flex items-center justify-between">
                    <span class="text-xs font-medium px-2 py-0.5 rounded-full {{ $badge }}">{{ $cat->category }}</span>
                    <span class="text-sm font-bold text-gray-700">{{ $cat->count }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Account Info --}}
        <div class="bg-white border border-gray-200 rounded-xl p-4">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Akun</p>
            <div class="space-y-2 text-xs">
                <div>
                    <span class="text-gray-400 block">Nama</span>
                    <span class="text-gray-800 font-medium">{{ Auth::user()?->name ?? 'N/A' }}</span>
                </div>
                <div>
                    <span class="text-gray-400 block">Email</span>
                    <span class="text-gray-800 font-medium break-all">{{ Auth::user()?->email ?? 'N/A' }}</span>
                </div>
                <div>
                    <span class="text-gray-400 block">Bergabung</span>
                    <span class="text-gray-800 font-medium">{{ Auth::user()?->created_at_readable ?? 'N/A' }}</span>
                </div>
                <div class="flex items-center justify-between pt-1">
                    <span class="text-gray-400">Status</span>
                    <span class="text-green-600 font-semibold">Aktif</span>
                </div>
            </div>
        </div>

    </div>{{-- end LEFT PANEL --}}

    {{-- RIGHT PANEL: Recent Listings --}}
    <div class="flex-1 min-w-0">
        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                <p class="text-sm font-semibold text-gray-700">Listing Terbaru</p>
                <a href="{{ route('user.assets.listing') }}" class="text-xs text-orange-600 hover:text-orange-700 font-medium">Lihat semua →</a>
            </div>

            @if($recentListings->count())
            <div class="divide-y divide-gray-100">
                @foreach($recentListings as $asset)
                @php
                    $photos   = is_array($asset->photos) ? $asset->photos : [];
                    $thumb    = $photos[0] ?? null;
                    $catBadge = ['Bank Cessie'=>'bg-blue-100 text-blue-700','AYDA'=>'bg-purple-100 text-purple-700','Lelang'=>'bg-orange-100 text-orange-700'][$asset->category] ?? 'bg-gray-100 text-gray-600';
                @endphp
                <div class="flex items-center gap-4 px-5 py-3">
                    <div class="w-10 h-10 rounded-lg overflow-hidden flex-shrink-0 bg-gray-100">
                        @if($thumb)
                            <img src="{{ asset('storage/' . $thumb) }}" alt="" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-800 truncate">{{ $asset->title }}</p>
                        <p class="text-xs text-gray-400 truncate">{{ $asset->location ?? '-' }}</p>
                    </div>
                    <div class="flex items-center gap-2 flex-shrink-0">
                        <span class="text-xs px-2 py-0.5 rounded-full {{ $catBadge }}">{{ $asset->category }}</span>
                        <span class="text-xs px-2 py-0.5 rounded-full {{ $asset->status === 'Available' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600' }}">{{ $asset->status }}</span>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="px-5 py-10 text-center text-sm text-gray-400">Belum ada listing properti.</div>
            @endif
        </div>
    </div>{{-- end RIGHT PANEL --}}

</div>

@endsection



