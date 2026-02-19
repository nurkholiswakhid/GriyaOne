@extends('marketing.layouts.app')

@section('title', 'Marketing Dashboard - GriyaOne')
@section('role', 'Marketing Dashboard')

@section('content')
            <!-- Header -->
            <div class="mb-8 fade-in">
                <h2 class="text-3xl font-bold text-gray-900 mb-1">Selamat datang, {{ Auth::user()->name }}!</h2>
                <p class="text-gray-600">Pantau kampanye dan kelola lead dengan efektif</p>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8 fade-in">
                <div class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-lg transition">
                    <div class="bg-gradient-to-br from-orange-500 to-orange-600 p-6 text-white">
                        <h3 class="text-lg font-semibold mb-2">Kampanye Aktif</h3>
                        <p class="text-3xl font-bold">8</p>
                    </div>
                </div>
                <div class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-lg transition">
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 p-6 text-white">
                        <h3 class="text-lg font-semibold mb-2">Total Lead</h3>
                        <p class="text-3xl font-bold">142</p>
                    </div>
                </div>
                <div class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-lg transition">
                    <div class="bg-gradient-to-br from-green-500 to-green-600 p-6 text-white">
                        <h3 class="text-lg font-semibold mb-2">Konversi</h3>
                        <p class="text-3xl font-bold">34%</p>
                    </div>
                </div>
                <div class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-lg transition">
                    <div class="bg-gradient-to-br from-purple-500 to-purple-600 p-6 text-white">
                        <h3 class="text-lg font-semibold mb-2">Total Klik</h3>
                        <p class="text-3xl font-bold">2.5K</p>
                    </div>
                </div>
            </div>

            <!-- Kampanye Saya Section -->
            <div class="fade-in mb-12">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-bold text-gray-900">Kampanye Saya</h3>
                    <button class="bg-gradient-to-r from-orange-600 to-orange-700 hover:from-orange-700 hover:to-orange-800 px-6 py-2 rounded-lg text-white font-medium text-sm transition-all duration-200">+ Buat Kampanye</button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-lg transition">
                        <div class="h-32 bg-gradient-to-br from-orange-400 to-orange-500"></div>
                        <div class="p-4">
                            <h4 class="font-semibold text-gray-900 mb-2">Promosi Properti Baru</h4>
                            <p class="text-xs text-gray-600 mb-3">Target: Investor Muda</p>
                            <div class="space-y-2">
                                <div class="flex justify-between items-center text-xs">
                                    <span class="text-gray-600">Progress</span>
                                    <span class="font-semibold text-orange-600">75%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-1.5">
                                    <div class="bg-orange-500 h-1.5 rounded-full" style="width: 75%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-lg transition">
                        <div class="h-32 bg-gradient-to-br from-blue-400 to-blue-500"></div>
                        <div class="p-4">
                            <h4 class="font-semibold text-gray-900 mb-2">Diskon Spesial</h4>
                            <p class="text-xs text-gray-600 mb-3">Target: Pelanggan Setia</p>
                            <div class="space-y-2">
                                <div class="flex justify-between items-center text-xs">
                                    <span class="text-gray-600">Progress</span>
                                    <span class="font-semibold text-blue-600">45%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-1.5">
                                    <div class="bg-blue-500 h-1.5 rounded-full" style="width: 45%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-lg transition">
                        <div class="h-32 bg-gradient-to-br from-green-400 to-green-500"></div>
                        <div class="p-4">
                            <h4 class="font-semibold text-gray-900 mb-2">Grand Opening</h4>
                            <p class="text-xs text-gray-600 mb-3">Target: Semua Segmen</p>
                            <div class="space-y-2">
                                <div class="flex justify-between items-center text-xs">
                                    <span class="text-gray-600">Progress</span>
                                    <span class="font-semibold text-green-600">90%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-1.5">
                                    <div class="bg-green-500 h-1.5 rounded-full" style="width: 90%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lead Terbaru Section -->
            <div class="fade-in mb-12">
                <h3 class="text-2xl font-bold text-gray-900 mb-6">Lead Terbaru</h3>

                <div class="bg-white rounded-xl overflow-hidden shadow-md">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Nama</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Kampanye</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Tanggal</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">Budi Santoso</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">Promosi Properti</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">2 jam lalu</td>
                                    <td class="px-6 py-4 text-sm"><span class="bg-yellow-100 text-yellow-600 px-3 py-1 rounded-full text-xs font-semibold">Baru</span></td>
                                </tr>
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">Siti Nurhaliza</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">Diskon Spesial</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">5 jam lalu</td>
                                    <td class="px-6 py-4 text-sm"><span class="bg-blue-100 text-blue-600 px-3 py-1 rounded-full text-xs font-semibold">Qualified</span></td>
                                </tr>
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">Ahmad Ramadan</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">Grand Opening</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">1 hari lalu</td>
                                    <td class="px-6 py-4 text-sm"><span class="bg-green-100 text-green-600 px-3 py-1 rounded-full text-xs font-semibold">Terkonfirmasi</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Info Section -->
            <div class="fade-in">
                <h3 class="text-2xl font-bold text-gray-900 mb-6">📋 Info Akun Anda</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white rounded-xl p-6 shadow-md">
                        <h4 class="font-bold text-gray-900 mb-4">Data Profil</h4>
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
                                <p class="text-gray-900 font-medium">Marketing Specialist</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl p-6 shadow-md">
                        <h4 class="font-bold text-gray-900 mb-4">Statistik Kampanye</h4>
                        <div class="space-y-3">
                            <div>
                                <p class="text-gray-600 text-sm">Member Sejak</p>
                                <p class="text-gray-900 font-medium">{{ Auth::user()->created_at->format('d M Y') }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600 text-sm">Total Kampanye</p>
                                <p class="text-orange-600 font-medium">✓ 12 Kampanye</p>
                            </div>
                            <div>
                                <p class="text-gray-600 text-sm">ROI Rata-rata</p>
                                <p class="text-green-600 font-medium">✓ 3.2x</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
@endsection
