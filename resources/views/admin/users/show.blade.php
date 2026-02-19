@extends('admin.layouts.app')

@section('title', 'Detail User - ' . $user->name)

@section('content')
<div class="max-w-2xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('users.index') }}" class="text-red-600 hover:text-red-700 flex items-center gap-2 mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Kembali
        </a>
        <h1 class="text-3xl font-bold text-gray-900">{{ $user->name }}</h1>
        <p class="text-gray-600 mt-2">Detail akun pengguna</p>
    </div>

    <!-- Profile Card -->
    <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
        <div class="bg-gradient-to-r {{ $user->role === 'admin' ? 'from-red-600 to-red-700' : ($user->role === 'marketing' ? 'from-orange-600 to-orange-700' : 'from-blue-600 to-blue-700') }} p-6 text-white">
            <div class="flex items-center gap-4">
                <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center text-4xl font-bold">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div>
                    <h2 class="text-2xl font-bold">{{ $user->name }}</h2>
                    <p class="text-white/80">{{ $user->email }}</p>
                    @if($user->role === 'admin')
                    <span class="inline-block mt-2 px-3 py-1 bg-white/20 rounded-full text-sm font-medium">
                        Administrator
                    </span>
                    @elseif($user->role === 'marketing')
                    <span class="inline-block mt-2 px-3 py-1 bg-white/20 rounded-full text-sm font-medium">
                        Tim Marketing
                    </span>
                    @else
                    <span class="inline-block mt-2 px-3 py-1 bg-white/20 rounded-full text-sm font-medium">
                        User Reguler
                    </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Info Grid -->
        <div class="p-6">
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Email</p>
                    <p class="text-lg font-medium text-gray-900">{{ $user->email }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">No Telepon</p>
                    <p class="text-lg font-medium text-gray-900">{{ $user->phone ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Role</p>
                    <p class="text-lg font-medium text-gray-900">
                        @if($user->role === 'admin')
                        Admin
                        @elseif($user->role === 'marketing')
                        Marketing
                        @else
                        User
                        @endif
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Tergabung</p>
                    <p class="text-lg font-medium text-gray-900">{{ $user->created_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Role Management -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Kelola Role</h3>
        <form method="POST" action="{{ route('users.update-role', $user) }}" class="space-y-4">
            @csrf
            @method('PATCH')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">Ubah Role Pengguna</label>
                <div class="space-y-3">
                    <label class="flex items-center p-4 border-2 {{ $user->role === 'user' ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-gray-300' }} rounded-lg cursor-pointer transition">
                        <input type="radio" name="role" value="user" {{ $user->role === 'user' ? 'checked' : '' }} class="w-4 h-4 text-blue-600">
                        <div class="ml-3">
                            <p class="font-medium text-gray-900">User Reguler</p>
                            <p class="text-sm text-gray-600">Akses dashboard user dengan fitur dasar</p>
                        </div>
                    </label>

                    <label class="flex items-center p-4 border-2 {{ $user->role === 'marketing' ? 'border-orange-500 bg-orange-50' : 'border-gray-200 hover:border-gray-300' }} rounded-lg cursor-pointer transition">
                        <input type="radio" name="role" value="marketing" {{ $user->role === 'marketing' ? 'checked' : '' }} class="w-4 h-4 text-orange-600">
                        <div class="ml-3">
                            <p class="font-medium text-gray-900">Tim Marketing</p>
                            <p class="text-sm text-gray-600">Akses dashboard marketing dengan fitur penjualan</p>
                        </div>
                    </label>

                    <label class="flex items-center p-4 border-2 {{ $user->role === 'admin' ? 'border-red-500 bg-red-50' : 'border-gray-200 hover:border-gray-300' }} rounded-lg cursor-pointer transition">
                        <input type="radio" name="role" value="admin" {{ $user->role === 'admin' ? 'checked' : '' }} class="w-4 h-4 text-red-600">
                        <div class="ml-3">
                            <p class="font-medium text-gray-900">Administrator</p>
                            <p class="text-sm text-gray-600">Akses penuh ke panel admin dan semua fitur</p>
                        </div>
                    </label>
                </div>
            </div>

            <div class="flex gap-4 pt-4">
                <button type="submit" class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium">
                    Simpan Perubahan Role
                </button>
            </div>
        </form>
    </div>

    <!-- Edit Profile -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Edit Profil</h3>
        <a href="{{ route('users.edit', $user) }}" class="inline-flex items-center px-6 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition font-medium">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            Edit Nama & Email
        </a>
    </div>

    <!-- Reset Password -->
    <div class="bg-white rounded-lg shadow p-6 mb-6 border-l-4 border-yellow-500">
        <h3 class="text-lg font-bold text-yellow-600 mb-4">🔄 Reset Password</h3>
        <p class="text-gray-600 mb-4">Kembalikan password user ke format default:</p>
        <div class="bg-yellow-50 rounded p-3 mb-4">
            <p class="text-sm font-mono text-gray-900"><strong>{{ strtolower(str_replace(' ', '', $user->name)) }}123456</strong></p>
        </div>

        <form method="POST" action="{{ route('users.reset-password', $user) }}" class="inline" data-confirm="Yakin ingin me-reset password user ke format default?">
            @csrf
            @method('PATCH')
            <button type="submit" class="px-6 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition font-medium">
                Reset Password
            </button>
        </form>
    </div>

    <!-- Danger Zone -->
    @if(auth()->id() !== $user->id)
    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-red-500">
        <h3 class="text-lg font-bold text-red-600 mb-2">Zona Berbahaya</h3>
        <p class="text-gray-600 text-sm mb-4">Tindakan berikut tidak dapat dibatalkan. Hati-hati sebelum melanjutkan.</p>

        <form method="POST" action="{{ route('users.destroy', $user) }}" class="inline" data-confirm="Yakin ingin menghapus user {{ $user->name }}? Tindakan ini tidak dapat dibatalkan.">
            @csrf
            @method('DELETE')
            <button type="submit" class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium">
                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
                Hapus User Ini
            </button>
        </form>
    </div>
    @else
    <div class="bg-blue-50 rounded-lg border border-blue-200 p-6">
        <p class="text-blue-900 text-sm">
            <strong>💡 Catatan:</strong> Anda tidak dapat menghapus akun sendiri. Silakan hubungi administrator lain untuk menghapus akun Anda.
        </p>
    </div>
    @endif
</div>
@endsection
