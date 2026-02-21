@extends('user.layouts.app')

@section('content')
            <!-- Header -->
            <div class="mb-8 fade-in">
                <h2 class="text-3xl font-bold text-gray-900 mb-1">Selamat datang, {{ Auth::user()->name }}!</h2>
                <p class="text-gray-600">Kelola properti Anda dan pantau booking terbaru</p>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 fade-in">
                <div class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-lg transition">
                    <div class="btn-blue p-6 text-white">
                        <h3 class="text-lg font-semibold mb-2">Properti Aktif</h3>
                        <p class="text-3xl font-bold">0</p>
                    </div>
                </div>
                <div class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-lg transition">
                    <div class="bg-gradient-to-br from-green-500 to-green-600 p-6 text-white">
                        <h3 class="text-lg font-semibold mb-2">Total Booking</h3>
                        <p class="text-3xl font-bold">0</p>
                    </div>
                </div>
                <div class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-lg transition">
                    <div class="bg-gradient-to-br from-purple-500 to-purple-600 p-6 text-white">
                        <h3 class="text-lg font-semibold mb-2">Pendapatan</h3>
                        <p class="text-3xl font-bold">Rp 0</p>
                    </div>
                </div>
            </div>

            <!-- Properti Section -->
            <div class="fade-in mb-12">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-bold text-gray-900">Properti Saya</h3>
                    <button class="btn-blue px-6 py-2 rounded-lg text-white font-medium text-sm">+ Tambah Properti</button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Empty State -->
                    <div class="col-span-3 bg-white rounded-xl p-12 text-center shadow-md">
                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-3m0 0l7-4 7 4M5 9v10a1 1 0 001 1h12a1 1 0 001-1V9m-9 5h4"></path>
                        </svg>
                        <h4 class="text-gray-900 font-semibold mb-2">Belum Ada Properti</h4>
                        <p class="text-gray-600 text-sm mb-4">Mulai dengan menambahkan properti pertama Anda</p>
                        <button class="btn-blue px-6 py-2 rounded-lg text-white font-medium text-sm">Tambah Properti</button>
                    </div>
                </div>
            </div>

            <!-- Booking Section -->
            <div class="fade-in mb-12">
                <h3 class="text-2xl font-bold text-gray-900 mb-6">Booking Terbaru</h3>

                <div class="bg-white rounded-xl overflow-hidden shadow-md">
                    <div class="p-8 text-center">
                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h4 class="text-gray-900 font-semibold mb-2">Tidak Ada Booking</h4>
                        <p class="text-gray-600 text-sm">Belum ada booking untuk properti Anda</p>
                    </div>
                </div>
            </div>

            <!-- Info Section -->
            <div class="fade-in">
                <h3 class="text-2xl font-bold text-gray-900 mb-6">Info Akun Anda</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white rounded-xl p-6 shadow-md">
                        <h4 class="font-bold text-gray-900 mb-4">Data Pribadi</h4>
                        <div class="space-y-3">
                            <div>
                                <p class="text-gray-600 text-sm">Nama</p>
                                <p class="text-gray-900 font-medium">{{ Auth::user()->name }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600 text-sm">Email</p>
                                <p class="text-gray-900 font-medium">{{ Auth::user()->email }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600 text-sm">Role</p>
                                <p class="text-gray-900 font-medium">User</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl p-6 shadow-md">
                        <h4 class="font-bold text-gray-900 mb-4">Status Akun</h4>
                        <div class="space-y-3">
                            <div>
                                <p class="text-gray-600 text-sm">Bergabung Sejak</p>
                                <p class="text-gray-900 font-medium">{{ Auth::user()->created_at->format('d M Y') }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600 text-sm">Status</p>
                                <p class="text-green-600 font-medium">Aktif</p>
                            </div>
                            <div>
                                <p class="text-gray-600 text-sm">Verifikasi Email</p>
                                <p class="text-orange-600 font-medium">Terverifikasi</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
@endsection


