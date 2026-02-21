@extends('admin.layouts.app')

@section('title', 'Edit User - ' . $user->name)

@section('content')
<div class="max-w-2xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('users.show', $user) }}" class="text-red-600 hover:text-red-700 flex items-center gap-2 mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Kembali
        </a>
        <h1 class="text-3xl font-bold text-gray-900">Edit User</h1>
        <p class="text-gray-600 mt-2">Ubah informasi akun pengguna</p>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow p-8">
        <form method="POST" action="{{ route('users.update', $user) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 @error('name') border-red-500 @enderror"
                    placeholder="Masukkan nama lengkap" required>
                @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 @error('email') border-red-500 @enderror"
                    placeholder="email@example.com" required>
                @error('email')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Phone -->
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">No Telepon</label>
                <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 @error('phone') border-red-500 @enderror"
                    placeholder="08XXXXXXXXXX">
                @error('phone')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Info Box -->
            <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                <p class="text-orange-900 text-sm">
                    <strong>Catatan:</strong> Untuk mengembalikan password ke default, gunakan tombol "Reset Password" di bawah.
                </p>
            </div>

            <!-- Form Actions -->
            <div class="flex gap-4 pt-4">
                <button type="submit" class="flex-1 px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium">
                    Simpan Perubahan
                </button>
                <a href="{{ route('users.show', $user) }}" class="flex-1 px-6 py-3 bg-gray-200 text-gray-900 rounded-lg hover:bg-gray-300 transition font-medium text-center">
                    Batal
                </a>
            </div>
        </form>
    </div>

    <!-- Reset Password Section -->
    <div class="bg-white rounded-lg shadow p-8 mt-8">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Reset Password</h3>
        <p class="text-gray-600 mb-4">Kembalikan password user ke format default: <strong>{{ strtolower(str_replace(' ', '', $user->name)) }}123456</strong></p>

        <form method="POST" action="{{ route('users.reset-password', $user) }}" data-confirm="Yakin ingin me-reset password user ke format default?">
            @csrf
            @method('PATCH')

            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                <p class="text-yellow-900 text-sm">
                    <strong>Perhatian:</strong> Password user akan diubah menjadi <strong>{{ strtolower(str_replace(' ', '', $user->name)) }}123456</strong>. User harus menggunakan password ini untuk login.
                </p>
            </div>

            <button type="submit" class="px-6 py-3 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition font-medium">
                Reset Password ke Default
            </button>
        </form>
    </div>
</div>
@endsection


