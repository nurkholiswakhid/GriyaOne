@extends('admin.layouts.app')

@section('title', $content->title . ' - GriyaOne')
@section('role', 'Detail Video Pembelajaran')

@section('content')
    <!-- Header -->
    <div class="mb-8 flex justify-between items-start fade-in">
        <div>
            <h2 class="text-3xl font-bold text-gray-900 mb-1">{{ $content->title }}</h2>
            <p class="text-gray-600 text-sm">Diupload {{ $content->created_at->diffForHumans() }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('contents.edit', $content) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-5 py-2 rounded-lg font-medium text-sm transition">Edit Video</a>
            <a href="{{ route('contents.index') }}" class="text-red-600 hover:text-red-700 font-medium text-sm transition py-2">← Kembali</a>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4 fade-in">
            <p class="text-green-800 font-medium">✓ {{ session('success') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 fade-in">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Video Player -->
            @php $youtubeId = $content->getYoutubeId(); @endphp
            @if($youtubeId)
                <div class="bg-black rounded-xl overflow-hidden shadow-md">
                    <div class="aspect-video">
                        <iframe class="w-full h-full" src="{{ $content->getYoutubeEmbedUrl() }}" frameborder="0" allowfullscreen allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"></iframe>
                    </div>
                </div>
            @elseif($content->thumbnail)
                <div class="bg-white rounded-xl overflow-hidden shadow-md">
                    <img src="{{ asset('storage/' . $content->thumbnail) }}" alt="{{ $content->title }}" class="w-full h-80 object-cover">
                </div>
            @else
                <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-xl p-16 text-center shadow-md">
                    <svg class="w-16 h-16 text-gray-500 mx-auto" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                    <p class="text-gray-400 text-sm mt-4">Tidak ada video</p>
                </div>
            @endif



            <!-- Description -->
            <div class="bg-white rounded-xl p-6 shadow-md">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h12"/></svg>
                    Deskripsi
                </h3>
                <p class="text-gray-700 leading-relaxed whitespace-pre-wrap">{{ $content->description ?? 'Tidak ada deskripsi' }}</p>
            </div>

            @if($content->file_path)
                <!-- Link YouTube -->
                <div class="bg-white rounded-xl p-6 shadow-md">
                    <h3 class="text-sm font-bold text-gray-900 mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5 text-red-600" viewBox="0 0 24 24" fill="currentColor"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                        Link YouTube
                    </h3>
                    @php
                        $watchUrl = $content->getYoutubeWatchUrl();
                    @endphp
                    <a href="{{ $watchUrl }}" target="_blank" class="inline-flex items-center gap-2 text-red-600 hover:text-red-700 break-all text-sm font-medium bg-red-50 hover:bg-red-100 px-4 py-2.5 rounded-lg transition">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        {{ $watchUrl }}
                    </a>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Publish Toggle -->
            <div class="bg-white rounded-xl p-6 shadow-md">
                <h3 class="text-sm font-bold text-gray-900 mb-4">Status Publikasi</h3>
                <form action="{{ route('contents.togglePublish', $content) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="w-full {{ $content->is_published ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} px-4 py-3 rounded-lg font-semibold text-sm transition">
                        {{ $content->is_published ? '✓ Dipublikasikan — Klik untuk Draft' : 'Draft — Klik untuk Publikasi' }}
                    </button>
                </form>
            </div>

            <!-- Info Card -->
            <div class="bg-white rounded-xl p-6 shadow-md">
                <h3 class="text-sm font-bold text-gray-900 mb-4">Informasi Video</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Dibuat</span>
                        <span class="font-semibold text-gray-900">{{ $content->created_at->format('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Diperbarui</span>
                        <span class="font-semibold text-gray-900">{{ $content->updated_at->format('d M Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Delete Button -->
            <form action="{{ route('contents.destroy', $content) }}" method="POST" data-confirm="Yakin ingin menghapus video ini? Tindakan ini tidak dapat dibatalkan.">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-3 rounded-lg font-semibold transition text-sm">
                    Hapus Video
                </button>
            </form>
        </div>
    </div>
@endsection
