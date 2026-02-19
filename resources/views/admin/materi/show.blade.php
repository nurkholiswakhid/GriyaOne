@extends('admin.layouts.app')

@section('title', $material->title . ' - GriyaOne')
@section('role', $material->title)

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('materi.index') }}" class="inline-flex items-center gap-2 text-red-600 hover:text-red-700 font-medium mb-4">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                Kembali ke Daftar Materi
            </a>
            <div class="flex items-center gap-3">
                @if($material->is_published)
                    <span class="bg-green-500 text-white px-3 py-1 rounded-lg text-sm font-semibold">Publik</span>
                @else
                    <span class="bg-gray-500 text-white px-3 py-1 rounded-lg text-sm font-semibold">Draft</span>
                @endif
            </div>
        </div>

        <!-- Main Card -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden fade-in">
            <!-- Content -->
            <div class="p-8">
                <!-- Title & Status -->
                <div class="mb-6 pb-6 border-b border-gray-200">
                    <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ $material->title }}</h1>
                    <div class="flex items-center gap-4 flex-wrap">
                        <div class="flex items-center gap-1 text-gray-600">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/></svg>
                            {{ $material->created_at->format('d M Y H:i') }}
                        </div>
                    </div>
                </div>

                <!-- Description -->
                @if($material->description)
                    <div class="mb-8 pb-8 border-b border-gray-200">
                        <p class="text-gray-600 leading-relaxed whitespace-pre-wrap">{{ $material->description }}</p>
                    </div>
                @endif

                <!-- PDF Preview -->
                @if($material->file_path)
                    <div class="mb-8 pb-8 border-b border-gray-200">
                        <div class="relative bg-gray-100 flex items-center justify-center py-8 px-4 -mx-8 -mb-8 p-4">
                            <div class="w-full bg-white rounded-lg shadow-lg overflow-hidden" style="">
                                <iframe src="{{ asset('storage/' . $material->file_path) }}" class="w-full border-0" style="height: 1100px; display: block;"></iframe>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- PDF Preview -->
                @if($material->file_path)
                    <div class="mb-8 pb-8 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">File PDF</h3>
                        <div class="bg-gradient-to-br from-purple-50 to-blue-50 rounded-lg p-6 border border-purple-200">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex items-start gap-4">
                                    <div class="bg-purple-100 p-3 rounded-lg">
                                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ basename($material->file_path) }}</p>
                                        <p class="text-sm text-gray-600 mt-1">Format PDF • Siap diunduh</p>
                                    </div>
                                </div>
                                <a href="{{ asset('storage/' . $material->file_path) }}" class="flex-shrink-0 inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition" download>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    Unduh
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Statistics -->
                <div class="mb-8 pb-8 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Statistik</h3>
                    <div class="grid grid-cols-2 md:grid-cols-2 gap-4">
                        <div class="bg-gradient-to-br from-green-50 to-green-100 p-4 rounded-lg border border-green-200">
                            <p class="text-sm text-green-600 font-medium">Status</p>
                            <p class="text-2xl font-bold text-green-900 mt-2">{{ $material->is_published ? 'Publik' : 'Draft' }}</p>
                        </div>
                        <div class="bg-gradient-to-br from-orange-50 to-orange-100 p-4 rounded-lg border border-orange-200">
                            <p class="text-sm text-orange-600 font-medium">Dibuat</p>
                            <p class="text-lg font-bold text-orange-900 mt-2">{{ $material->created_at->format('d M Y') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex gap-3 flex-wrap">
                    <a href="{{ route('materi.edit', $material) }}" class="flex-1 min-w-40 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold py-3 px-4 rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl border-2 border-blue-500 text-center inline-flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Edit Materi
                    </a>
                    @if($material->is_published)
                        <form action="{{ route('materi.togglePublish', $material) }}" method="POST" class="flex-1 min-w-40" data-confirm="Cabut publikasi materi ini?">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="w-full bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white font-bold py-3 px-4 rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl border-2 border-amber-400 inline-flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                Cabut Publikasi
                            </button>
                        </form>
                    @else
                        <form action="{{ route('materi.togglePublish', $material) }}" method="POST" class="flex-1 min-w-40" data-confirm="Publikasikan materi ini?">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-bold py-3 px-4 rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl border-2 border-green-400 inline-flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                                Publikasikan
                            </button>
                        </form>
                    @endif
                    <form action="{{ route('materi.destroy', $material) }}" method="POST" class="flex-1 min-w-40" data-confirm="Yakin ingin menghapus materi ini? Tindakan ini tidak dapat dibatalkan.">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-bold py-3 px-4 rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl border-2 border-red-500 inline-flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            Hapus Materi
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
