@extends('admin.layouts.app')

@section('title', 'Edit Informasi - GriyaOne')
@section('role', 'Edit Informasi')

@section('content')
    <!-- Header -->
    <div class="mb-8 fade-in">
        <a href="{{ route('informasi.index') }}" class="text-red-600 hover:text-red-700 font-semibold text-sm mb-4 inline-flex items-center gap-1">
            ← Kembali
        </a>
        <h2 class="text-3xl font-bold text-gray-900">Edit Informasi</h2>
        <p class="text-gray-600 mt-2">Update informasi yang ada</p>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-xl shadow-md p-8 fade-in">
        <form action="{{ route('informasi.update', $informasi->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Title -->
            <div>
                <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">Judul Informasi <span class="text-red-500">*</span></label>
                <input type="text" id="title" name="title" value="{{ old('title', $informasi->title) }}" placeholder="Masukkan judul informasi..." class="w-full px-4 py-2 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-500 focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition @error('title') border-red-500 @enderror">
                @error('title')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Category -->
            <div>
                <label for="category" class="block text-sm font-semibold text-gray-700 mb-2">Kategori <span class="text-red-500">*</span></label>
                <select id="category" name="category" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition @error('category') border-red-500 @enderror">
                    <option value="">Pilih Kategori</option>
                    <option value="General" {{ old('category', $informasi->category) == 'General' ? 'selected' : '' }}>General</option>
                    <option value="Update" {{ old('category', $informasi->category) == 'Update' ? 'selected' : '' }}>Update</option>
                    <option value="Pengumuman" {{ old('category', $informasi->category) == 'Pengumuman' ? 'selected' : '' }}>Pengumuman</option>
                    <option value="Event" {{ old('category', $informasi->category) == 'Event' ? 'selected' : '' }}>Event</option>
                </select>
                @error('category')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Published Date -->
            <div>
                <label for="published_date" class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Publikasi <span class="text-red-500">*</span></label>
                <input type="datetime-local" id="published_date" name="published_date" value="{{ old('published_date', $informasi->published_date->format('Y-m-d\TH:i')) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition @error('published_date') border-red-500 @enderror">
                @error('published_date')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Content -->
            <div>
                <label for="content" class="block text-sm font-semibold text-gray-700 mb-2">Konten <span class="text-red-500">*</span></label>
                <textarea id="content" name="content" rows="8" placeholder="Masukkan konten informasi..." class="w-full px-4 py-2 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-500 focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition @error('content') border-red-500 @enderror">{{ old('content', $informasi->content) }}</textarea>
                @error('content')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
                <p class="text-gray-500 text-xs mt-2">Minimal 10 karakter</p>
            </div>

            <!-- Status -->
            <div>
                <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                <div class="space-y-2">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="radio" name="status" value="active" {{ old('status', $informasi->status) == 'active' ? 'checked' : '' }} class="w-4 h-4 text-red-600">
                        <span class="text-sm text-gray-700">Aktif - Tampilkan ke pengguna</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="radio" name="status" value="archived" {{ old('status', $informasi->status) == 'archived' ? 'checked' : '' }} class="w-4 h-4 text-red-600">
                        <span class="text-sm text-gray-700">Arsip - Sembunyikan dari pengguna</span>
                    </label>
                </div>
                @error('status')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Buttons -->
            <div class="flex gap-3 pt-6 border-t border-gray-200">
                <a href="{{ route('informasi.index') }}" class="flex-1 text-center px-6 py-2 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit" class="flex-1 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-semibold py-2 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                    Perbarui Informasi
                </button>
            </div>
        </form>
    </div>
@endsection
