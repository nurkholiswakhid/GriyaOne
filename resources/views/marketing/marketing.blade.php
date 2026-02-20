@extends('marketing.layouts.app')

@section('title', 'Marketing Dashboard - GriyaOne')
@section('role', 'Marketing Dashboard')

@section('content')
            <!-- Header -->
            <div class="mb-8 fade-in">
                <h2 class="text-3xl font-bold text-gray-900 mb-1">Selamat datang, {{ Auth::user()->name }}!</h2>
                <p class="text-gray-600">Dashboard marketing untuk monitoring penjualan dan strategi pemasaran aset</p>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 fade-in">
                <div class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-lg transition">
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 p-6 text-white">
                        <h3 class="text-lg font-semibold mb-2">Total Listing</h3>
                        <p class="text-3xl font-bold">{{ $totalListings }}</p>
                        <p class="text-sm mt-2 opacity-90">Aset yang terdaftar</p>
                    </div>
                </div>
                <div class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-lg transition">
                    <div class="bg-gradient-to-br from-green-500 to-green-600 p-6 text-white">
                        <h3 class="text-lg font-semibold mb-2">Tersedia</h3>
                        <p class="text-3xl font-bold">{{ $availableListings }}</p>
                        <p class="text-sm mt-2 opacity-90">Siap dijual</p>
                    </div>
                </div>
                <div class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-lg transition">
                    <div class="bg-gradient-to-br from-red-500 to-red-600 p-6 text-white">
                        <h3 class="text-lg font-semibold mb-2">Terjual</h3>
                        <p class="text-3xl font-bold">{{ $soldListings }}</p>
                        <p class="text-sm mt-2 opacity-90">Berhasil terjual</p>
                    </div>
                </div>
            </div>

            <!-- Informasi Terbaru Section -->
            <div class="fade-in mb-12">
                <h3 class="text-2xl font-bold text-gray-900 mb-6">Informasi Terbaru</h3>

                @if($informations->isEmpty())
                <div class="bg-white rounded-lg p-8 text-center shadow hover:shadow-md transition">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    <p class="text-gray-600">Belum ada informasi terbaru.</p>
                </div>
                @else
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($informations as $info)
                    <div class="bg-white rounded-lg overflow-hidden shadow hover:shadow-md transition">
                        <div class="bg-gray-100 h-32 flex items-center justify-center border-b border-gray-200">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div class="p-4">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="inline-block px-2 py-1 bg-gray-200 text-gray-700 rounded text-xs font-semibold">{{ $info->category }}</span>
                                <span class="text-xs text-gray-500">{{ $info->published_date?->format('d M Y') ?? $info->created_at->format('d M Y') }}</span>
                            </div>
                            <h4 class="text-base font-semibold text-gray-900 mb-2">{{ $info->title }}</h4>
                            <p class="text-sm text-gray-600 mb-3 line-clamp-2">{!! Str::limit(strip_tags($info->content), 100) !!}</p>
                            <a href="{{ route('marketing.informasi') }}" class="text-sm text-gray-700 hover:text-gray-900 font-semibold">Baca Selengkapnya →</a>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            <!-- Listing by Category & Recent Listings Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-12 fade-in">
                <!-- Kategori Listing -->
                <div class="bg-white rounded-xl p-6 shadow-md">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">Kategori Listing</h3>
                    @if($listingsByCategory->isEmpty())
                    <div class="text-center py-8">
                        <p class="text-gray-600">Belum ada listing dalam kategori apapun</p>
                    </div>
                    @else
                    <div class="space-y-4">
                        @foreach($listingsByCategory as $category)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                            <div class="flex items-center gap-3 flex-1">
                                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-semibold">
                                    {{ substr($category->category, 0, 1) }}
                                </div>
                                <span class="font-semibold text-gray-900">{{ $category->category }}</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="text-2xl font-bold text-blue-600">{{ $category->count }}</span>
                                <span class="text-sm text-gray-500">listing</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>

                <!-- Listing Terbaru -->
                <div class="bg-white rounded-xl p-6 shadow-md">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">Listing Terbaru</h3>
                    @if($recentListings->isEmpty())
                    <div class="text-center py-8">
                        <p class="text-gray-600">Belum ada listing terbaru</p>
                    </div>
                    @else
                    <div class="space-y-3">
                        @foreach($recentListings as $listing)
                        <div class="p-4 border border-gray-200 rounded-lg hover:border-blue-400 transition group">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex-1">
                                    <h5 class="font-semibold text-gray-900 group-hover:text-blue-600 transition">{{ $listing->title }}</h5>
                                    <p class="text-sm text-gray-600 mt-1">{!! Str::limit(strip_tags($listing->description), 60) !!}</p>
                                    <div class="flex items-center gap-3 mt-2">
                                        <span class="inline-block px-2 py-1 bg-gray-100 text-gray-700 rounded text-xs">{{ $listing->category }}</span>
                                        <span class="inline-block px-2 py-1 {{ $listing->status === 'Available' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }} rounded text-xs font-semibold">{{ $listing->status }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
@endsection
