@extends('admin.layouts.app')

@section('title', 'Tambah User Baru')

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
        <h1 class="text-3xl font-bold text-gray-900">Tambah User Baru</h1>
        <p class="text-gray-600 mt-2">Buat akun user atau tim marketing baru di sistem</p>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow p-8">
        <form method="POST" action="{{ route('users.store') }}" class="space-y-6">
            @csrf

            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 @error('name') border-red-500 @enderror"
                    placeholder="Masukkan nama lengkap" required>
                @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 @error('email') border-red-500 @enderror"
                    placeholder="email@example.com" required>
                @error('email')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Phone -->
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">No Telepon</label>
                <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 @error('phone') border-red-500 @enderror"
                    placeholder="08XXXXXXXXXX">
                @error('phone')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                <input type="password" id="password" name="password"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 @error('password') border-red-500 @enderror"
                    placeholder="Minimal 8 karakter (biarkan kosong untuk auto-generate)">
                @error('password')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-gray-500 text-xs mt-1">💡 Jika kosong, password otomatis: <strong>nama_user123456</strong> (contoh: johndoe123456)</p>
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500"
                    placeholder="Ulangi password (jika diisi)">
            </div>

            <!-- Role -->
            <div>
                <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Role / Peran</label>
                <select id="role" name="role" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 @error('role') border-red-500 @enderror" required>
                    <option value="">Pilih Role</option>
                    <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>User Reguler</option>
                    <option value="marketing" {{ old('role') === 'marketing' ? 'selected' : '' }}>Tim Marketing</option>
                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Administrator</option>
                </select>
                @error('role')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <div class="mt-4 p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <p class="text-sm text-blue-900" id="roleDescription">
                        <strong>User Reguler:</strong> Akses dashboard user dengan fitur dasar
                    </p>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex gap-4 pt-4">
                <button type="submit" class="flex-1 px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium">
                    Buat User
                </button>
                <a href="{{ route('users.index') }}" class="flex-1 px-6 py-3 bg-gray-200 text-gray-900 rounded-lg hover:bg-gray-300 transition font-medium text-center">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    const roleSelect = document.getElementById('role');
    const roleDescription = document.getElementById('roleDescription');
    const passwordInput = document.getElementById('password');
    const passwordConfirmInput = document.getElementById('password_confirmation');

    const descriptions = {
        'user': '<strong>User Reguler:</strong> Akses dashboard user dengan fitur dasar seperti melihat aset dan konten',
        'marketing': '<strong>Tim Marketing:</strong> Akses dashboard marketing dengan fitur penjualan aset dan manajemen kampanye',
        'admin': '<strong>Administrator:</strong> Akses penuh ke panel admin termasuk manajemen aset, konten, user, dan notifikasi'
    };

    roleSelect.addEventListener('change', (e) => {
        roleDescription.innerHTML = descriptions[e.target.value] || 'Pilih role untuk melihat deskripsi';
    });

    // Update password confirmation requirement
    passwordInput.addEventListener('input', () => {
        if (passwordInput.value.trim() === '') {
            passwordConfirmInput.removeAttribute('required');
        } else {
            passwordConfirmInput.setAttribute('required', 'required');
        }
    });
</script>
@endsection
