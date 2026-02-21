@extends('admin.layouts.app')

@section('title', 'Admin Dashboard - GriyaOne')
@section('role', 'Admin Dashboard')

@section('content')
            <!-- Header -->
            <div class="mb-8 fade-in">
                <h2 class="text-3xl font-bold text-gray-900 mb-1">Selamat datang, {{ Auth::user()?->name ?? 'Admin' }}!</h2>
                <p class="text-gray-600">Kelola sistem dan monitor aktivitas platform</p>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8 fade-in">
                <div class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-lg transition">
                    <div class="bg-gradient-to-br from-red-500 to-red-600 p-6 text-white">
                        <h3 class="text-lg font-semibold mb-2">Total Pengguna</h3>
                        <p class="text-3xl font-bold">{{ $totalUsers }}</p>
                        <p class="text-sm mt-2 text-red-100">Admin: {{ $adminUsers }} | Marketing: {{ $marketingUsers }} | User: {{ $regularUsers }}</p>
                    </div>
                </div>
                <div class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-lg transition">
                    <div class="bg-gradient-to-br from-orange-500 to-orange-600 p-6 text-white">
                        <h3 class="text-lg font-semibold mb-2">Total Properti</h3>
                        <p class="text-3xl font-bold">{{ $totalAssets }}</p>
                        <p class="text-sm mt-2 text-orange-100">Tersedia: {{ $availableAssets }} | Terjual: {{ $soldAssets }}</p>
                    </div>
                </div>
                <div class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-lg transition">
                    <div class="bg-gradient-to-br from-green-500 to-green-600 p-6 text-white">
                        <h3 class="text-lg font-semibold mb-2">Total Konten</h3>
                        <p class="text-3xl font-bold">{{ $totalContent }}</p>
                        <p class="text-sm mt-2 text-green-100">Dipublikasi: {{ $publishedContent }} | Draft: {{ $draftContent }}</p>
                    </div>
                </div>
                <div class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-lg transition">
                    <div class="bg-gradient-to-br from-purple-500 to-purple-600 p-6 text-white">
                        <h3 class="text-lg font-semibold mb-2">Notifikasi</h3>
                        <p class="text-3xl font-bold">{{ $unreadNotifications }}</p>
                        <p class="text-sm mt-2 text-purple-100">Total: {{ $totalNotifications }} notifikasi</p>
                    </div>
                </div>
            </div>

            <!-- Kelola Pengguna Section -->
            <div class="fade-in mb-12">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-bold text-gray-900">Pengguna Terbaru</h3>
                    <a href="{{ route('users.create') }}" class="bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 px-6 py-2 rounded-lg text-white font-medium text-sm transition-all duration-200">+ Tambah Pengguna</a>
                </div>

                <div class="bg-white rounded-xl overflow-hidden shadow-md">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Nama</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Email</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Role</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Bergabung</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($recentUsers as $user)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $user->name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $user->email }}</td>
                                    <td class="px-6 py-4 text-sm">
                                        @if($user->role === 'admin')
                                            <span class="bg-red-100 text-red-600 px-3 py-1 rounded-full text-xs font-semibold">Admin</span>
                                        @elseif($user->role === 'marketing')
                                            <span class="bg-orange-100 text-orange-600 px-3 py-1 rounded-full text-xs font-semibold">Marketing</span>
                                        @else
                                            <span class="bg-orange-100 text-orange-600 px-3 py-1 rounded-full text-xs font-semibold">User</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $user->created_at->format('d M Y') }}</td>
                                    <td class="px-6 py-4 text-sm"><span class="text-green-600 font-semibold">Aktif</span></td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-600">Belum ada pengguna</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


            <!-- Info Section -->
            <div class="fade-in">
                <h3 class="text-2xl font-bold text-gray-900 mb-6">Ringkasan Sistem</h3>

                <!-- Admin Account Info -->
                <div class="bg-white rounded-xl p-6 shadow-md ">
                    <h4 class="font-bold text-gray-900 mb-4">Data Admin</h4>
                    <div class="space-y-3">
                        <div>
                            <p class="text-gray-600 text-sm">Nama</p>
                            <p class="text-gray-900 font-medium">{{ Auth::user()?->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">Email</p>
                            <p class="text-gray-900 font-medium">{{ Auth::user()?->email ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">Role</p>
                            <p class="text-gray-900 font-medium">Administrator</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">Bergabung</p>
                            <p class="text-gray-900 font-medium">{{ optional(Auth::user())->created_at?->format('d M Y') ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>


@endsection


