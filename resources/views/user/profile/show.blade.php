@extends('user.layouts.app')

@section('title', 'Detail Profil - GriyaOne')

@section('content')
<div class="max-w-4xl mx-auto fade-in">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Detail Profil</h1>
        <p class="text-gray-600 mt-2">Informasi lengkap akun Anda</p>
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Sidebar with Profile Picture -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
                <!-- Profile Avatar -->
                <div class="flex flex-col items-center mb-6">
                    @if($user->profilePhotoUrl())
                        <img src="{{ $user->profilePhotoUrl() }}" alt="Foto Profil" class="w-20 h-20 rounded-full object-cover mb-4 border-4 border-orange-100">
                    @else
                        <div class="w-20 h-20 bg-gradient-to-br from-orange-400 to-orange-600 rounded-full flex items-center justify-center text-white font-bold text-2xl mb-4">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                    <h3 class="text-lg font-semibold text-gray-900 text-center">{{ $user->name }}</h3>
                    <p class="text-sm text-gray-500 mt-1">Pengguna Biasa</p>
                </div>

                <div class="border-t border-gray-200 pt-6">
                    <div class="space-y-4">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Email</p>
                            <p class="text-sm text-gray-700 break-all">{{ $user->email }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Nomor HP</p>
                            <p class="text-sm text-gray-700">{{ $user->phone ?? 'Belum diisi' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Bergabung Sejak</p>
                            <p class="text-sm text-gray-700">{{ $user->created_at->translatedFormat('d F Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info Cards -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Data Pribadi -->
            <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
                <h4 class="font-bold text-gray-900 text-lg mb-6 pb-4 border-b border-gray-200">Data Pribadi</h4>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama -->
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Nama Lengkap</p>
                        <p class="text-sm text-gray-700">{{ $user->name }}</p>
                    </div>

                    <!-- Email -->
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Email</p>
                        <p class="text-sm text-gray-700 break-all">{{ $user->email }}</p>
                    </div>

                    <!-- No HP -->
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Nomor HP</p>
                        <p class="text-sm text-gray-700">{{ $user->phone ?? 'Belum diisi' }}</p>
                    </div>

                    <!-- Role -->
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Role</p>
                        <p class="text-sm text-gray-700">Pengguna</p>
                    </div>
                </div>
            </div>

            <!-- Status Akun -->
            <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
                <h4 class="font-bold text-gray-900 text-lg mb-6 pb-4 border-b border-gray-200">Status Akun</h4>

                <div class="space-y-3">
                    <!-- Bergabung Sejak -->
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Bergabung Sejak</p>
                            <p class="text-sm text-gray-700 font-medium">{{ $user->created_at->translatedFormat('d F Y') }}</p>
                        </div>
                        <svg class="w-6 h-6 text-gray-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>

                    <!-- Status Akun -->
                    <div class="flex items-center justify-between p-4 bg-green-50 rounded-lg border border-green-200">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Status Akun</p>
                            <p class="text-sm text-green-600 font-medium">Aktif</p>
                        </div>
                        <svg class="w-6 h-6 text-green-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>

                    <!-- Email Terverifikasi -->
                    <div class="flex items-center justify-between p-4 bg-orange-50 rounded-lg border border-orange-200">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Email Terverifikasi</p>
                            <p class="text-sm text-orange-600 font-medium">Ya</p>
                        </div>
                        <svg class="w-6 h-6 text-orange-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Aksi Cepat -->
            <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
                <h4 class="font-bold text-gray-900 text-lg mb-6 pb-4 border-b border-gray-200">Aksi Cepat</h4>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-3 bg-orange-50 text-orange-600 hover:bg-orange-100 font-semibold text-sm rounded-lg transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit Profil
                    </a>
                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-3 bg-yellow-50 text-yellow-600 hover:bg-yellow-100 font-semibold text-sm rounded-lg transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        Ubah Password
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Auto fade-in animation
    window.addEventListener('load', () => {
        document.querySelectorAll('.fade-in').forEach(el => {
            el.style.animation = 'fadeIn 0.5s ease-out forwards';
        });
    });
</script>
@endsection
