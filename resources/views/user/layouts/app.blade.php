<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'GriyaOne - Dashboard')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }
        .fade-in { animation: fadeIn 0.5s ease-out; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        .slide-in { animation: slideIn 0.3s ease-out; }
        @keyframes slideIn { from { transform: translateY(-20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navbar -->
    <nav class="sticky top-0 z-50 bg-white shadow-sm border-b border-blue-100">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="bg-blue-600 hover:bg-blue-700 w-10 h-10 rounded-lg flex items-center justify-center text-white font-bold text-lg flex-shrink-0 transition-colors duration-200">G</div>
                    <div class="hidden sm:block min-w-0">
                        <h1 class="text-lg sm:text-xl font-bold text-gray-900 truncate">GriyaOne</h1>
                        <p class="text-xs text-blue-600 font-semibold">@yield('role', 'User Dashboard')</p>
                    </div>
                </div>
                <button id="notificationBtn" class="relative p-2 text-gray-600 hover:text-blue-600 transition flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                    <span id="notificationBadge" class="hidden absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                </button>
            </div>
        </div>
    </nav>

    <!-- Main Layout -->
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-gradient-to-b from-white to-blue-50 border-r border-gray-200 min-h-screen pt-4 fixed left-0 top-16 bottom-0 overflow-y-auto lg:relative lg:top-0 hidden lg:block scrollbar-hide">
            <!-- User Profile Card -->
            <div class="mx-4 mb-6 bg-white rounded-lg border border-gray-200 p-4 shadow-sm hover:shadow-md transition-shadow duration-200">
                @php
                    /** @var \App\Models\User|null $authUser */
                    $authUser = Auth::user();
                @endphp
                <div class="flex items-center gap-3">
                    @if($authUser && $authUser->profilePhotoUrl())
                        <img src="{{ $authUser->profilePhotoUrl() }}" alt="Foto Profil" class="w-12 h-12 rounded-full object-cover flex-shrink-0 border border-gray-200">
                    @else
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white font-bold text-lg flex-shrink-0">
                            {{ $authUser ? strtoupper(substr($authUser->name, 0, 1)) : 'U' }}
                        </div>
                    @endif
                    <div class="min-w-0 flex-1">
                        <h3 class="font-semibold text-gray-900 text-sm truncate">{{ $authUser?->name ?? 'User' }}</h3>
                        <p class="text-xs text-gray-500 truncate">{{ $authUser?->email ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <nav class="px-2 space-y-1">
                <!-- Main Navigation -->
                <a href="#" class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 flex items-center justify-between gap-3 px-4 py-3 rounded-lg text-white font-medium text-sm shadow-md hover:shadow-lg transition-all duration-200 group">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 flex-shrink-0 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-3m0 0l7-4 7 4M5 9v10a1 1 0 001 1h12a1 1 0 001-1V9m-9 5h4"></path>
                        </svg>
                        <span>Dashboard</span>
                    </div>
                    <span class="text-xs font-semibold bg-white bg-opacity-20 px-2 py-0.5 rounded">Active</span>
                </a>

                <div class="text-xs font-semibold text-gray-500 px-4 py-3 uppercase tracking-wider mt-6">Properti</div>

                <a href="#" class="group relative flex items-center justify-between px-4 py-3 text-gray-700 hover:text-blue-600 hover:bg-blue-50 font-medium text-sm transition-colors duration-200 rounded-lg">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 flex-shrink-0 text-gray-400 group-hover:text-blue-600 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        <span>Properti Saya</span>
                    </div>
                    <span class="bg-blue-100 text-blue-600 text-xs font-semibold px-2 py-0.5 rounded-full group-hover:bg-blue-200 transition-colors duration-200">5</span>
                </a>

                <a href="#" class="group relative flex items-center justify-between px-4 py-3 text-gray-700 hover:text-blue-600 hover:bg-blue-50 font-medium text-sm transition-colors duration-200 rounded-lg">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 flex-shrink-0 text-gray-400 group-hover:text-blue-600 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Booking</span>
                    </div>
                    <span class="bg-orange-100 text-orange-600 text-xs font-semibold px-2 py-0.5 rounded-full group-hover:bg-orange-200 transition-colors duration-200">2</span>
                </a>

                <a href="#" class="group flex items-center gap-3 px-4 py-3 text-gray-700 hover:text-blue-600 hover:bg-blue-50 font-medium text-sm transition-colors duration-200 rounded-lg">
                    <svg class="w-5 h-5 flex-shrink-0 text-gray-400 group-hover:text-blue-600 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <span>Laporan</span>
                </a>

                <div class="my-3 border-t border-gray-200"></div>

                <div class="text-xs font-semibold text-gray-500 px-4 py-3 uppercase tracking-wider">Pembelajaran</div>

                <a href="{{ route('user.materials') }}" class="group flex items-center gap-3 px-4 py-3 text-gray-700 hover:text-purple-600 hover:bg-purple-50 font-medium text-sm transition-colors duration-200 rounded-lg">
                    <svg class="w-5 h-5 flex-shrink-0 text-gray-400 group-hover:text-purple-600 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                    <span>📚 Materi PDF</span>
                </a>

                <div class="text-xs font-semibold text-gray-500 px-4 py-3 uppercase tracking-wider">Akun</div>

                <a href="#" class="group flex items-center gap-3 px-4 py-3 text-gray-700 hover:text-blue-600 hover:bg-blue-50 font-medium text-sm transition-colors duration-200 rounded-lg">
                    <svg class="w-5 h-5 flex-shrink-0 text-gray-400 group-hover:text-blue-600 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <span>Profile</span>
                </a>

                <a href="#" class="group flex items-center gap-3 px-4 py-3 text-gray-700 hover:text-blue-600 hover:bg-blue-50 font-medium text-sm transition-colors duration-200 rounded-lg">
                    <svg class="w-5 h-5 flex-shrink-0 text-gray-400 group-hover:text-blue-600 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span>Pengaturan</span>
                </a>

                <form action="/logout" method="POST">
                    @csrf
                    <button type="submit" class="w-full group flex items-center gap-3 px-4 py-3 text-gray-700 hover:text-red-600 hover:bg-red-50 font-medium text-sm transition-colors duration-200 rounded-lg">
                        <svg class="w-5 h-5 flex-shrink-0 text-gray-400 group-hover:text-red-600 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        <span>Logout</span>
                    </button>
                </form>
            </nav>

            <div class="mx-4 mt-8 mb-8 bg-gradient-to-br from-blue-50 via-blue-50 to-blue-100 border-l-4 border-blue-400 rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow duration-200">
                <div class="flex items-start gap-2">
                    <span class="text-xl flex-shrink-0 animate-bounce">💡</span>
                    <div class="min-w-0">
                        <h4 class="font-bold text-gray-900 text-sm mb-1">Tips Properti</h4>
                        <p class="text-xs text-gray-700 leading-relaxed">Update foto dan deskripsi properti Anda secara berkala untuk hasil penjualan maksimal.</p>
                        <button class="mt-2 text-xs font-semibold text-blue-600 hover:text-blue-700 transition-colors duration-200">Pelajari lebih lanjut →</button>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 lg:ml-0 pt-4">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                @yield('content')
            </div>
        </main>
    </div>

    <script>
        // ============ Notification System ============
        const notificationBtn = document.getElementById('notificationBtn');
        const notificationBadge = document.getElementById('notificationBadge');

        // Toggle notification badge visibility (for user dashboard)
        // In future, this can be extended with a dropdown like admin/marketing

        // Simple notification check - hide badge by default
        notificationBadge.classList.add('hidden');
    </script>
</body>
</html>
