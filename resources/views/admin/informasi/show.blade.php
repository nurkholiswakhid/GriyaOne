@extends('admin.layouts.app')

@section('title', $informasi->title . ' - GriyaOne')
@section('role', 'Detail Informasi')

@section('content')
    <!-- Header -->
    <div class="mb-8 fade-in">
        <a href="{{ route('informasi.index') }}" class="text-red-600 hover:text-red-700 font-semibold text-sm mb-4 inline-flex items-center gap-1">
            ← Kembali
        </a>
        <div class="flex justify-between items-start">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">{{ $informasi->title }}</h2>
                <p class="text-gray-600 mt-2">Diterbitkan: {{ $informasi->published_date->format('d M Y H:i') }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('informasi.edit', $informasi->id) }}" class="bg-amber-600 hover:bg-amber-700 text-white px-6 py-2 rounded-lg font-semibold text-sm transition">
                    Edit
                </a>
                <form action="{{ route('informasi.destroy', $informasi->id) }}" method="POST" class="inline" data-confirm="Apakah Anda yakin ingin menghapus informasi ini?">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg font-semibold text-sm transition">
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Content Card -->
    <div class="bg-white rounded-xl shadow-md p-8 fade-in">
        <!-- Meta Information -->
        <div class="flex flex-wrap gap-4 mb-6 pb-6 border-b border-gray-200">
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Kategori</p>
                <span class="inline-block mt-1 bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold">
                    {{ $informasi->category->name ?? 'Umum' }}
                </span>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Status</p>
                <span class="inline-block mt-1 px-3 py-1 rounded-full text-sm font-semibold {{ $informasi->getStatusBadgeColor() === 'green' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                    {{ ucfirst($informasi->status) }}
                </span>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Dibuat</p>
                <p class="mt-1 text-sm text-gray-700">{{ $informasi->created_at->format('d M Y H:i') }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Diperbarui</p>
                <p class="mt-1 text-sm text-gray-700">{{ $informasi->updated_at->format('d M Y H:i') }}</p>
            </div>
        </div>

        <!-- Photo -->
        @if($informasi->photo)
            <div class="mb-8">
                <h3 class="text-sm font-semibold text-gray-700 mb-4">Foto Informasi</h3>
                <img src="{{ asset('storage/' . $informasi->photo) }}" alt="{{ $informasi->title }}" class="w-full h-auto max-h-96 object-cover rounded-lg border border-gray-300">
            </div>
        @endif

        <!-- Content -->
        <div class="prose prose-sm max-w-none text-gray-700 leading-relaxed">
            {!! nl2br(e($informasi->content)) !!}
        </div>

        <!-- Buttons -->
        <div class="flex gap-3 mt-8 pt-6 border-t border-gray-200">
            <a href="{{ route('informasi.index') }}" class="flex-1 text-center px-6 py-2 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition">
                Kembali ke Daftar
            </a>

        </div>
    </div>
@endsection
