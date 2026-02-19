@extends('marketing.layouts.app')

@section('title', 'Notifikasi & Info Internal')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">📢 Notifikasi & Informasi Internal</h1>
        <p class="text-gray-600 mt-2">Update terbaru tentang aset baru, aset sold out, training, challenge, dan bonus</p>
    </div>

    <!-- Filter -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <form method="GET" class="flex flex-col md:flex-row gap-4">
            <select name="type" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                <option value="">Semua Tipe Notifikasi</option>
                <option value="asset_new" {{ request('type') === 'asset_new' ? 'selected' : '' }}>🆕 Aset Baru</option>
                <option value="asset_sold" {{ request('type') === 'asset_sold' ? 'selected' : '' }}>✅ Aset Terjual</option>
                <option value="system" {{ request('type') === 'system' ? 'selected' : '' }}>⚠️ Sistem</option>
                <option value="info" {{ request('type') === 'info' ? 'selected' : '' }}>ℹ️ Informasi</option>
            </select>
            <select name="status" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                <option value="">Semua Status</option>
                <option value="unread" {{ request('status') === 'unread' ? 'selected' : '' }}>🔔 Belum Dibaca</option>
                <option value="read" {{ request('status') === 'read' ? 'selected' : '' }}>✓ Sudah Dibaca</option>
            </select>
            <button type="submit" class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">Filter</button>
        </form>
    </div>

    <!-- Notification Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-gray-500 text-sm">Belum Dibaca</p>
            <p class="text-3xl font-bold text-red-600">{{ \App\Models\Notification::where('user_id', auth()->id())->where('is_read', false)->count() }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-gray-500 text-sm">Aset Baru</p>
            <p class="text-3xl font-bold text-orange-600">{{ \App\Models\Notification::where('user_id', auth()->id())->where('type', 'asset_new')->count() }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-gray-500 text-sm">Aset Terjual</p>
            <p class="text-3xl font-bold text-green-600">{{ \App\Models\Notification::where('user_id', auth()->id())->where('type', 'asset_sold')->count() }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-gray-500 text-sm">Total Notifikasi</p>
            <p class="text-3xl font-bold text-purple-600">{{ \App\Models\Notification::where('user_id', auth()->id())->count() }}</p>
        </div>
    </div>

    <!-- Notifications Timeline -->
    <div class="space-y-4">
        @php
            $notifications = \App\Models\Notification::where('user_id', auth()->id());
            if(request('type')) $notifications->where('type', request('type'));
            if(request('status') === 'unread') $notifications->where('is_read', false);
            if(request('status') === 'read') $notifications->where('is_read', true);
            $notifications = $notifications->latest()->paginate(15);
        @endphp

        @forelse($notifications as $notification)
        <div class="bg-white rounded-lg shadow p-6 border-l-4 {{ !$notification->is_read ? 'border-purple-500 bg-purple-50' : 'border-gray-300' }} hover:shadow-lg transition">
            <div class="flex items-start gap-4">
                <!-- Icon -->
                <div class="text-4xl flex-shrink-0">
                    {{ $notification->icon ?? '📢' }}
                </div>

                <!-- Content -->
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-4 mb-2">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">{{ $notification->title }}</h3>
                            @if($notification->type === 'asset_new')
                                <p class="text-xs text-orange-600 font-semibold mt-1">🆕 Aset Baru Tersedia</p>
                            @elseif($notification->type === 'asset_sold')
                                <p class="text-xs text-green-600 font-semibold mt-1">✅ Aset Telah Terjual</p>
                            @elseif($notification->type === 'system')
                                <p class="text-xs text-red-600 font-semibold mt-1">⚠️ Notifikasi Sistem</p>
                            @else
                                <p class="text-xs text-blue-600 font-semibold mt-1">ℹ️ Informasi Penting</p>
                            @endif
                        </div>
                        <div class="text-right flex-shrink-0">
                            @if(!$notification->is_read)
                            <span class="inline-block px-3 py-1 bg-purple-600 text-white text-xs font-semibold rounded-full animate-pulse">Baru</span>
                            @else
                            <span class="inline-block px-3 py-1 bg-gray-300 text-gray-700 text-xs font-semibold rounded-full">Dibaca</span>
                            @endif
                        </div>
                    </div>

                    <!-- Message -->
                    <p class="text-gray-700 mb-3">{{ $notification->message }}</p>

                    <!-- Meta Info -->
                    <div class="flex items-center justify-between text-xs text-gray-500">
                        <span>📅 {{ $notification->created_at->format('d/m/Y H:i') }}</span>
                        @if($notification->read_at)
                        <span>✓ Dibaca pada {{ $notification->read_at->format('d/m/Y H:i') }}</span>
                        @endif
                    </div>

                    <!-- Asset Link (if applicable) -->
                    @if($notification->asset_id && $notification->asset)
                    <div class="mt-3 p-3 bg-gray-50 rounded border border-gray-200">
                        <p class="text-xs text-gray-600 mb-2">📍 Terkait dengan aset:</p>
                        <p class="font-semibold text-gray-900">{{ $notification->asset->title }}</p>
                        <p class="text-xs text-gray-600 mt-1">Kategori: {{ $notification->asset->category }}</p>
                    </div>
                    @endif
                </div>

                <!-- Actions -->
                <div class="flex flex-col gap-2 flex-shrink-0">
                    @if(!$notification->is_read)
                    <form method="POST" action="{{ route('marketing.notification-read', $notification) }}" class="inline">
                        @csrf
                        <button type="submit" class="px-3 py-1 bg-purple-600 text-white text-xs font-semibold rounded hover:bg-purple-700 transition" title="Tandai sudah dibaca">
                            ✓ Baca
                        </button>
                    </form>
                    @endif
                    
                    <form method="POST" action="{{ route('marketing.notification-delete', $notification) }}" class="inline" onsubmit="return confirm('Hapus notifikasi ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-3 py-1 bg-red-600 text-white text-xs font-semibold rounded hover:bg-red-700 transition">
                            🗑️ Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-12 bg-white rounded-lg shadow">
            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
            </svg>
            <p class="text-gray-500 font-medium">Tidak ada notifikasi yang sesuai dengan filter</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $notifications->links() }}
    </div>

    <!-- Info Card -->
    <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
        <h3 class="font-bold text-blue-900 mb-3">💡 Cara Menggunakan Notifikasi</h3>
        <ul class="text-sm text-blue-800 space-y-2">
            <li>✅ <strong>Aset Baru (🆕):</strong> Langsung ada peluang penjualan baru</li>
            <li>✅ <strong>Aset Terjual (✓):</strong> Informasi penjualan yang berhasil</li>
            <li>✅ <strong>Notifikasi Sistem (⚠️):</strong> Update penting dari management</li>
            <li>✅ <strong>Informasi (ℹ️):</strong> Training, challenge, dan bonus untuk tim</li>
        </ul>
    </div>
</div>
@endsection
