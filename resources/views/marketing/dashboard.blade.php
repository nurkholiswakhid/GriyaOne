@extends('marketing.layouts.app')

@section('title', 'Dashboard Marketing')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Welcome Section -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Selamat Datang, Tim Marketing! 👋</h1>
        <p class="text-gray-600 mt-2">Dashboard khusus untuk mendukung penjualan dan pengembangan kompetensi tim</p>
    </div>

    <!-- Key Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <!-- Available Assets -->
        <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Aset Tersedia</p>
                    <p class="text-3xl font-bold text-orange-600">{{ \App\Models\Asset::where('status', 'Available')->count() }}</p>
                </div>
                <div class="bg-orange-100 rounded-full p-3">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Sold Assets -->
        <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Aset Terjual</p>
                    <p class="text-3xl font-bold text-green-600">{{ \App\Models\Asset::where('status', 'Sold Out')->count() }}</p>
                </div>
                <div class="bg-green-100 rounded-full p-3">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Learning Materials -->
        <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Materi Pembelajaran</p>
                    <p class="text-3xl font-bold text-blue-600">{{ \App\Models\Content::count() }}</p>
                </div>
                <div class="bg-blue-100 rounded-full p-3">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C6.248 6.253 2 10.998 2 16.5S6.248 26.747 12 26.747s10-4.745 10-10.247S17.752 6.253 12 6.253z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- New Notifications -->
        <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Notifikasi Baru</p>
                    <p class="text-3xl font-bold text-purple-600">{{ \App\Models\Notification::where('user_id', auth()->id())->where('is_read', false)->count() }}</p>
                </div>
                <div class="bg-purple-100 rounded-full p-3">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Listing Aset Card -->
        <a href="{{ route('marketing.assets') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition hover:-translate-y-1 duration-200">
            <div class="flex items-center gap-4 mb-4">
                <div class="bg-orange-100 rounded-full p-3">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Listing Aset</h3>
                    <p class="text-sm text-gray-500">Lihat daftar aset tersedia</p>
                </div>
            </div>
            <p class="text-sm text-gray-600">Kelola & download foto aset, salin text broadcast untuk penjualan</p>
        </a>

        <!-- Learning Media Card -->
        <a href="{{ route('marketing.learning') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition hover:-translate-y-1 duration-200">
            <div class="flex items-center gap-4 mb-4">
                <div class="bg-blue-100 rounded-full p-3">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C6.248 6.253 2 10.998 2 16.5S6.248 26.747 12 26.747s10-4.745 10-10.247S17.752 6.253 12 6.253z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Media Pembelajaran</h3>
                    <p class="text-sm text-gray-500">Tingkatkan kompetensi tim</p>
                </div>
            </div>
            <p class="text-sm text-gray-600">Video pembelajaran, materi PDF, dan dokumen pendukung untuk pengembangan skill</p>
        </a>

        <!-- Info Internal Card -->
        <a href="{{ route('marketing.notifications') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition hover:-translate-y-1 duration-200">
            <div class="flex items-center gap-4 mb-4">
                <div class="bg-purple-100 rounded-full p-3">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Notifikasi & Info</h3>
                    <p class="text-sm text-gray-500">Update terbaru & informasi internal</p>
                </div>
            </div>
            <p class="text-sm text-gray-600">Notifikasi aset baru, sold out, training, challenge, dan bonus terbaru</p>
        </a>
    </div>

    <!-- Recent Notifications -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">📢 Notifikasi Terbaru</h2>
        <div class="space-y-3">
            @php
                $notifications = \App\Models\Notification::where('user_id', auth()->id())
                    ->latest()
                    ->limit(5)
                    ->get();
            @endphp
            
            @forelse($notifications as $notification)
            <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-lg border-l-4 {{ $notification->is_read ? 'border-gray-300' : 'border-orange-500' }}">
                <span class="text-2xl flex-shrink-0">{{ $notification->icon ?? '📢' }}</span>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-gray-900">{{ $notification->title }}</p>
                    <p class="text-sm text-gray-600 mt-1">{{ $notification->message }}</p>
                    <p class="text-xs text-gray-500 mt-2">{{ $notification->created_at->format('d/m/Y H:i') }}</p>
                </div>
                @if(!$notification->is_read)
                <span class="inline-block px-3 py-1 bg-orange-100 text-orange-700 text-xs font-semibold rounded-full flex-shrink-0">Baru</span>
                @endif
            </div>
            @empty
            <div class="text-center py-8 text-gray-500">
                <p>Tidak ada notifikasi baru</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
