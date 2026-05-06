@extends('admin.layouts.app')

@section('title', $asset->title . ' - GriyaOne')
@section('role', 'Detail Aset')

@if($asset->photos && count($asset->photos) > 0)
@push('preload')
    {{-- Preload gambar utama (LCP element) agar browser fetch lebih awal --}}
    <link rel="preload" as="image" href="{{ asset('storage/' . $asset->photos[0]) }}" fetchpriority="high">
@endpush
@endif

@section('content')
    <!-- Header -->
    <div class="mb-8 flex justify-between items-start fade-in">
        <div>
            <h2 class="text-3xl font-bold text-gray-900 mb-2">{{ $asset->title }}</h2>
            <p class="text-gray-600">Dibuat {{ $asset->created_at->diffForHumans() }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('assets.edit', $asset) }}" class="bg-yellow-600 hover:bg-yellow-700 text-white px-6 py-2 rounded-lg font-medium text-sm transition">Ubah</a>
            <a href="{{ route('assets.index') }}" class="text-red-600 hover:text-red-700 font-medium text-sm transition">← Kembali</a>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4 fade-in">
            <p class="text-green-800 font-medium">{{ session('success') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 fade-in">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Photo Gallery -->
            @if($asset->photos && count($asset->photos) > 0)
                <div class="bg-white rounded-xl overflow-hidden shadow-md">
                    <!-- Main image: dimensi eksplisit + fetchpriority=high untuk LCP -->
                    <div class="bg-gray-900 relative overflow-hidden" style="height:384px;">
                        <img
                            id="main-image"
                            src="{{ asset('storage/' . $asset->photos[0]) }}"
                            class="w-full h-full object-cover"
                            alt="{{ $asset->title }}"
                            width="800"
                            height="384"
                            fetchpriority="high"
                            decoding="async"
                        >
                    </div>
                    <div class="p-4 flex items-center justify-between bg-gray-50">
                        <div class="flex gap-2 overflow-x-auto flex-1">
                            @foreach($asset->photos as $index => $photo)
                                <img
                                    src="{{ asset('storage/' . \App\Helpers\ImageHelper::thumbnail($photo)) }}"
                                    alt="Foto {{ $index + 1 }}"
                                    class="h-20 w-20 object-contain bg-gray-100 rounded-lg cursor-pointer border-2 border-transparent hover:border-red-500 transition flex-shrink-0"
                                    width="80"
                                    height="80"
                                    loading="{{ $index === 0 ? 'eager' : 'lazy' }}"
                                    decoding="async"
                                    onclick="switchMainImage('{{ asset('storage/' . $photo) }}')"
                                >
                            @endforeach
                        </div>
                        <button type="button" onclick="downloadAllPhotos()" class="ml-4 flex-shrink-0 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium text-sm transition flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                            <span id="download-btn-text">Download Semua ({{ count($asset->photos) }})</span>
                        </button>
                    </div>
                </div>
            @else
                <div class="bg-white rounded-xl p-12 text-center shadow-md">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <p class="text-gray-500">Belum ada foto</p>
                </div>
            @endif

            <!-- Description -->
            <div class="bg-white rounded-xl p-6 shadow-md">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-gray-900">Deskripsi</h3>
                    <button type="button" class="bg-red-100 hover:bg-red-200 text-red-700 px-3 py-1 rounded-lg text-sm font-medium transition flex items-center gap-2" onclick="copyDescription()" title="Salin deskripsi ke clipboard">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                        <span id="copy-button-text">Salin</span>
                    </button>
                </div>
                <div id="description-content" class="prose prose-sm max-w-none text-gray-700 leading-relaxed
                    prose-p:my-2 prose-p:text-gray-700
                    prose-strong:text-gray-900 prose-strong:font-bold
                    prose-em:text-gray-700
                    prose-ul:my-2 prose-ul:ml-4
                    prose-ol:my-2 prose-ol:ml-4
                    prose-li:my-1 prose-li:text-gray-700
                    prose-blockquote:border-l-4 prose-blockquote:border-red-500 prose-blockquote:pl-4 prose-blockquote:italic
                    prose-code:bg-gray-100 prose-code:px-2 prose-code:py-1 prose-code:rounded prose-code:text-sm">
                    @if($asset->description)
                        {!! $asset->description !!}
                    @else
                        <p class="text-gray-500 italic">Tidak ada deskripsi</p>
                    @endif
                </div>
            </div>

            <!-- Location & Category -->
            <div class="bg-white rounded-xl p-6 shadow-md">
                <h3 class="text-lg font-bold text-gray-900 mb-4"> Informasi Dasar</h3>
                <div class="space-y-4">
                    @if($asset->location)
                        <div>
                            <p class="text-sm font-semibold text-gray-700 mb-1">Alamat</p>
                            <p class="text-gray-900">{{ $asset->location }}</p>
                        </div>
                    @endif
                    @if($asset->category)
                        <div>
                            <p class="text-sm font-semibold text-gray-700 mb-1">Kategori</p>
                            <p class="text-gray-900">{{ $asset->category }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Status & Category Card -->
            <div class="bg-white rounded-xl p-6 shadow-md">
                <h3 class="text-lg font-bold text-gray-900 mb-4"> Status</h3>

                <div class="space-y-4">
                    <div>
                        <p class="text-sm font-semibold text-gray-700 mb-2">Kategori</p>
                        @php
                            $colors = [
                                'Bank Cessie' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-700'],
                                'AYDA' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-700'],
                                'Lelang' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-700'],
                            ];
                            $color = $colors[$asset->category] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-700'];
                        @endphp
                        <span class="{{ $color['bg'] }} {{ $color['text'] }} px-4 py-2 rounded-full text-sm font-semibold">{{ $asset->category }}</span>
                    </div>

                    <div>
                        <p class="text-sm font-semibold text-gray-700 mb-2">Status Listing</p>
                        <form action="{{ route('assets.updateStatus', $asset) }}" method="POST" class="flex gap-2">
                            @csrf
                            @method('PATCH')
                            <select name="status" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-red-500 transition">
                                <option value="Available" @selected($asset->status === 'Available')>Available</option>
                                <option value="Sold Out" @selected($asset->status === 'Sold Out')>Sold Out</option>
                            </select>
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium text-sm transition">Update</button>
                        </form>
                    </div>

                    @if($asset->status === 'Available')
                        <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                            <p class="text-green-700 font-semibold text-sm">Aset Tersedia</p>
                        </div>
                    @else
                        <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                            <p class="text-red-700 font-semibold text-sm">Aset Terjual</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Photo Count -->
            <div class="bg-white rounded-xl p-6 shadow-md">
                <p class="text-sm font-semibold text-gray-700 mb-3"> Foto</p>
                @if($asset->photos && count($asset->photos) > 0)
                    <div class="flex items-center justify-between">
                        <p class="text-gray-900 font-semibold">{{ count($asset->photos) }} Foto</p>
                        <span class="bg-orange-100 text-orange-700 px-3 py-1 rounded-full text-xs font-semibold">Terlengkap</span>
                    </div>
                @else
                    <p class="text-gray-600">Tidak ada foto</p>
                @endif
            </div>

            <!-- Timeline -->
            <div class="bg-white rounded-xl p-6 shadow-md">
                <p class="text-sm font-semibold text-gray-700 mb-4"> Timeline</p>
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-gray-600">Dibuat pada</p>
                        <p class="text-gray-900 font-semibold">{{ $asset->created_at->format('d M Y H:i') }}</p>
                    </div>
                    <div class="border-t border-gray-200 pt-3">
                        <p class="text-gray-600">Terakhir diupdate</p>
                        <p class="text-gray-900 font-semibold">{{ $asset->updated_at->format('d M Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Delete Button -->
            <form action="{{ route('assets.destroy', $asset) }}" method="POST" data-confirm="Yakin ingin menghapus aset ini? Tindakan ini tidak dapat dibatalkan.">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-3 rounded-lg font-semibold transition">
                     Hapus Aset
                </button>
            </form>
        </div>
    </div>

    <script>
        // Switch main image WITHOUT causing reflow
        function switchMainImage(src) {
            const mainImg = document.getElementById('main-image');
            if (mainImg) mainImg.src = src;
        }

        // Download all photos one by one
        const photoUrls = @json($asset->photos ? array_map(fn($p) => asset('storage/' . $p), $asset->photos) : []);
        const photoNames = @json($asset->photos ?? []);

        async function downloadAllPhotos() {
            const btnText = document.getElementById('download-btn-text');
            const total = photoUrls.length;

            if (total === 0) {
                alert('Tidak ada foto untuk didownload');
                return;
            }

            btnText.textContent = `Downloading 0/${total}...`;

            for (let i = 0; i < total; i++) {
                btnText.textContent = `Downloading ${i + 1}/${total}...`;

                try {
                    const response = await fetch(photoUrls[i]);
                    const blob = await response.blob();
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    // Use original filename from path
                    const filename = photoNames[i].split('/').pop();
                    a.download = filename;
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    window.URL.revokeObjectURL(url);

                    // Small delay between downloads so browser doesn't block them
                    if (i < total - 1) {
                        await new Promise(r => setTimeout(r, 500));
                    }
                } catch (err) {
                    console.error('Download failed for:', photoUrls[i], err);
                }
            }

            btnText.textContent = `Selesai!`;
            setTimeout(() => {
                btnText.textContent = `Download Semua (${total})`;
            }, 2000);
        }

        function copyDescription() {
            const descriptionElement = document.getElementById('description-content');
            const buttonText = document.getElementById('copy-button-text');
            const originalText = buttonText.textContent;

            if (!descriptionElement) {
                console.error('Description element not found');
                return;
            }

            // Get text content (removes HTML tags automatically)
            const text = (descriptionElement.innerText || descriptionElement.textContent || '').trim();

            if (!text) {
                alert('Tidak ada teks untuk disalin');
                return;
            }

            // Try modern clipboard API first
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(text).then(() => {
                    showCopySuccess();
                }).catch(err => {
                    console.warn('Clipboard API failed:', err);
                    fallbackCopy(text);
                });
            } else {
                // Fallback for older browsers or non-secure contexts
                fallbackCopy(text);
            }

            function showCopySuccess() {
                buttonText.textContent = 'Tersalin!';
                setTimeout(() => {
                    buttonText.textContent = originalText;
                }, 2000);
            }

            function fallbackCopy(text) {
                const textarea = document.createElement('textarea');
                textarea.value = text;
                textarea.style.position = 'fixed';
                textarea.style.left = '-9999px';
                document.body.appendChild(textarea);

                try {
                    textarea.select();
                    document.execCommand('copy');
                    showCopySuccess();
                } catch (err) {
                    console.error('Copy failed:', err);
                    alert('Gagal menyalin: ' + err.message);
                }

                document.body.removeChild(textarea);
            }
        }
    </script>
@endsection


