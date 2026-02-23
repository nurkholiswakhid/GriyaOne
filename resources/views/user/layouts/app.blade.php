<!DOCTYPE html>
@php $waNumber = \App\Models\Setting::get('login_wa_number', ''); @endphp
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'GriyaOne - Dashboard')</title>
    @php $__faviconPath = \App\Models\Setting::get('login_logo_path', ''); @endphp
    @if($__faviconPath)
    <link rel="icon" href="{{ asset('storage/' . $__faviconPath) }}">
    <link rel="shortcut icon" href="{{ asset('storage/' . $__faviconPath) }}">
    <link rel="apple-touch-icon" href="{{ asset('storage/' . $__faviconPath) }}">
    @endif
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }
        .fade-in { animation: fadeIn 0.5s ease-out; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        .slide-in { animation: slideIn 0.3s ease-out; }
        @keyframes slideIn { from { transform: translateY(-20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }

        /* Mobile sidebar slide */
        #mobileSidebar {
            transform: translateX(-100%);
            transition: transform 0.3s cubic-bezier(.4,0,.2,1);
        }
        #mobileSidebar.open { transform: translateX(0); }
        #sidebarBackdrop {
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }
        #sidebarBackdrop.open { opacity: 1; pointer-events: auto; }

        /* Hide scrollbar */
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }

        /* Hamburger: mobile only */
        @media (min-width: 1024px) {
            #hamburgerBtn { display: none !important; }
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navbar -->
    <nav class="sticky top-0 z-50 bg-white shadow-sm border-b border-orange-100">
        <div class="max-w-full mx-auto px-3 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-14 sm:h-16">

                <div class="flex items-center gap-2 sm:gap-3 min-w-0">
                    <!-- Hamburger (mobile only) -->
                    <button id="hamburgerBtn" class="lg:hidden p-2 -ml-1 text-gray-600 hover:text-orange-600 hover:bg-orange-50 rounded-lg transition flex-shrink-0" aria-label="Buka menu">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    @php $__navLogo = \App\Models\Setting::get('login_logo_path',''); $__siteName = \App\Models\Setting::get('site_name','GriyaOne'); @endphp
                    @if($__navLogo)
                        <img src="{{ asset('storage/' . $__navLogo) }}" alt="Logo {{ $__siteName }}" class="h-8 w-auto sm:h-9 max-w-[100px] object-contain flex-shrink-0" style="filter:drop-shadow(0 1px 4px rgba(0,0,0,0.15));">
                    @else
                        <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-lg flex items-center justify-center text-white flex-shrink-0 shadow-md" style="background: linear-gradient(135deg, #ea580c, #f97316);">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
                        </div>
                    @endif
                    <div class="hidden xs:block sm:block min-w-0">
                        <h1 class="text-base sm:text-lg font-bold text-gray-900 truncate leading-tight">{{ $__siteName }}</h1>
                        <p class="text-xs text-orange-600 font-semibold hidden sm:block">@yield('role', 'Dashboard')</p>
                    </div>
                </div>

                <!-- Right: notification + (future actions) -->
                <div class="flex items-center gap-1">
                    <button id="notificationBtn" class="relative p-2 text-gray-600 hover:text-orange-600 hover:bg-orange-50 rounded-lg transition flex-shrink-0">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                        <span id="notificationBadge" class="hidden absolute top-1.5 right-1.5 w-2 h-2 bg-orange-500 rounded-full animate-pulse"></span>
                    </button>
                </div>

                <!-- Notification Dropdown -->
                <div id="notificationDropdown" class="hidden absolute top-14 sm:top-16 right-2 sm:right-4 w-[calc(100vw-1rem)] max-w-sm bg-white rounded-xl shadow-2xl border border-gray-200 z-40 max-h-96 overflow-y-auto">
                    <div class="bg-gradient-to-r from-orange-600 to-orange-700 px-4 py-3 sticky top-0 z-10 rounded-t-xl">
                        <h3 class="text-white font-semibold text-sm">Notifikasi</h3>
                    </div>
                    <div id="notificationList" class="divide-y divide-gray-100">
                        <div class="px-4 py-8 text-center text-gray-500 text-sm">Memuat notifikasi...</div>
                    </div>
                </div>

            </div>
        </div>
    </nav>

    <!-- Mobile Sidebar Backdrop -->
    <div id="sidebarBackdrop" class="fixed inset-0 z-40 bg-black/40 backdrop-blur-sm lg:hidden" onclick="closeMobileSidebar()"></div>

    <!-- Mobile Sidebar Overlay -->
    <aside id="mobileSidebar" class="fixed left-0 top-0 bottom-0 z-50 w-72 bg-gradient-to-b from-white to-orange-50 border-r border-gray-200 overflow-y-auto scrollbar-hide lg:hidden pt-0 shadow-2xl">
        <!-- Mobile sidebar header -->
        <div class="flex items-center justify-between px-4 py-4 border-b border-gray-100 bg-white sticky top-0 z-10">
            <div class="flex items-center gap-2">
                @if($__navLogo ?? false)
                    <img src="{{ asset('storage/' . $__navLogo) }}" alt="Logo" class="h-7 w-auto max-w-[80px] object-contain" style="filter:drop-shadow(0 1px 3px rgba(0,0,0,0.12));">
                @else
                    <div class="w-7 h-7 rounded-md flex items-center justify-center text-white" style="background: linear-gradient(135deg, #ea580c, #f97316);">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
                    </div>
                @endif
                <span class="font-bold text-gray-900 text-sm">{{ $__siteName ?? 'GriyaOne' }}</span>
            </div>
            <button onclick="closeMobileSidebar()" class="p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="pt-4">
            @php $authUser = $authUser ?? Auth::user(); @endphp
            <!-- Profile Card -->
            <div class="mx-4 mb-5 bg-white rounded-lg border border-gray-200 p-3 shadow-sm">
                <div class="flex items-center gap-3">
                    @if($authUser && method_exists($authUser, 'profilePhotoUrl') && $authUser->profilePhotoUrl())
                        <img src="{{ $authUser->profilePhotoUrl() }}" alt="Foto Profil" class="w-10 h-10 rounded-full object-cover flex-shrink-0 border border-gray-200">
                    @else
                        <div class="w-10 h-10 bg-gradient-to-br from-orange-400 to-orange-600 rounded-full flex items-center justify-center text-white font-bold text-base flex-shrink-0">
                            {{ $authUser && isset($authUser->name) && strlen($authUser->name) > 0 ? strtoupper(substr($authUser->name, 0, 1)) : 'U' }}
                        </div>
                    @endif
                    <div class="min-w-0 flex-1">
                        <h3 class="font-semibold text-gray-900 text-sm truncate">{{ $authUser?->name ?? 'User' }}</h3>
                        <p class="text-xs text-gray-500 truncate">{{ $authUser?->email ?? '-' }}</p>
                    </div>
                </div>
            </div>
            <nav class="px-2 space-y-1 pb-8">
                <a href="{{ route('user.dashboard') }}" onclick="closeMobileSidebar()" class="bg-gradient-to-r from-orange-600 to-orange-700 flex items-center gap-3 px-4 py-3 rounded-lg text-white font-medium text-sm shadow-md">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-3m0 0l7-4 7 4M5 9v10a1 1 0 001 1h12a1 1 0 001-1V9m-9 5h4"/></svg>
                    <span>Dashboard</span>
                </a>
                <div class="text-xs font-semibold text-gray-500 px-4 py-3 uppercase tracking-wider">Properti</div>
                <a href="{{ route('user.assets.listing') }}" onclick="closeMobileSidebar()" class="flex items-center justify-between px-4 py-3 text-gray-700 hover:text-orange-600 hover:bg-orange-50 font-medium text-sm rounded-lg">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 flex-shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        <span>Properti Saya</span>
                    </div>
                </a>
                <div class="my-3 border-t border-gray-200"></div>
                <div class="text-xs font-semibold text-gray-500 px-4 py-3 uppercase tracking-wider">Akun</div>
                <a href="{{ route('profile.show') }}" onclick="closeMobileSidebar()" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:text-orange-600 hover:bg-orange-50 font-medium text-sm rounded-lg">
                    <svg class="w-5 h-5 flex-shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    <span>Profile</span>
                </a>
                <a href="{{ route('profile.edit') }}" onclick="closeMobileSidebar()" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:text-orange-600 hover:bg-orange-50 font-medium text-sm rounded-lg">
                    <svg class="w-5 h-5 flex-shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span>Pengaturan</span>
                </a>
                <form action="/logout" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-gray-700 hover:text-red-600 hover:bg-red-50 font-medium text-sm rounded-lg">
                        <svg class="w-5 h-5 flex-shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        <span>Logout</span>
                    </button>
                </form>
                @if($waNumber)
                <div class="mt-4 bg-gradient-to-br from-green-50 to-green-100 border-l-4 border-green-400 rounded-lg p-3">
                    <p class="text-xs font-bold text-gray-900 mb-1">Customer Service</p>
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $waNumber) }}" target="_blank" class="text-xs font-semibold text-green-600 hover:text-green-700">Buka WhatsApp →</a>
                </div>
                @endif
            </nav>
        </div>
    </aside>

    <!-- Main Layout -->
    <div class="flex min-h-screen">
        <!-- Desktop Sidebar -->
        <aside class="w-64 bg-gradient-to-b from-white to-orange-50 border-r border-gray-200 min-h-screen pt-4 fixed left-0 top-16 bottom-0 overflow-y-auto lg:relative lg:top-0 hidden lg:block scrollbar-hide flex-shrink-0">
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
                        <div class="w-12 h-12 bg-gradient-to-br from-orange-400 to-orange-600 rounded-full flex items-center justify-center text-white font-bold text-lg flex-shrink-0">
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
                <a href="{{ route('user.dashboard') }}" class="bg-gradient-to-r from-orange-600 to-orange-700 hover:from-orange-700 hover:to-orange-800 flex items-center justify-between gap-3 px-4 py-3 rounded-lg text-white font-medium text-sm shadow-md hover:shadow-lg transition-all duration-200 group">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 flex-shrink-0 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-3m0 0l7-4 7 4M5 9v10a1 1 0 001 1h12a1 1 0 001-1V9m-9 5h4"></path>
                        </svg>
                        <span>Dashboard</span>
                    </div>
                </a>

                <div class="text-xs font-semibold text-gray-500 px-4 py-3 uppercase tracking-wider mt-6">Properti</div>

                <a href="{{ route('user.assets.listing') }}" class="group relative flex items-center justify-between px-4 py-3 text-gray-700 hover:text-orange-600 hover:bg-orange-50 font-medium text-sm transition-colors duration-200 rounded-lg">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 flex-shrink-0 text-gray-400 group-hover:text-orange-600 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        <span>Listing Aset</span>
                    </div>
                    <span class="bg-orange-100 text-orange-600 text-xs font-semibold px-2 py-0.5 rounded-full group-hover:bg-orange-200 transition-colors duration-200">{{ \App\Models\Asset::count() }}</span>
                </a>


                <div class="my-3 border-t border-gray-200"></div>

                <div class="text-xs font-semibold text-gray-500 px-4 py-3 uppercase tracking-wider">Akun</div>

                <a href="{{ route('profile.show') }}" class="group flex items-center gap-3 px-4 py-3 text-gray-700 hover:text-orange-600 hover:bg-orange-50 font-medium text-sm transition-colors duration-200 rounded-lg">
                    <svg class="w-5 h-5 flex-shrink-0 text-gray-400 group-hover:text-orange-600 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <span>Profile</span>
                </a>

                <a href="{{ route('profile.edit') }}" class="group flex items-center gap-3 px-4 py-3 text-gray-700 hover:text-orange-600 hover:bg-orange-50 font-medium text-sm transition-colors duration-200 rounded-lg">
                    <svg class="w-5 h-5 flex-shrink-0 text-gray-400 group-hover:text-orange-600 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

            <div class="mx-4 mt-8 mb-8 bg-gradient-to-br from-green-50 via-green-50 to-green-100 border-l-4 border-green-400 rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow duration-200">
                <div class="flex items-start gap-2">
                    <div class="min-w-0">
                        <h4 class="font-bold text-gray-900 text-sm mb-1">Chat dengan Customer Service</h4>
                        <p class="text-xs text-gray-700 leading-relaxed">Hubungi kami di WhatsApp untuk bantuan, laporan, atau pertanyaan Anda.</p>
                        @if($waNumber)
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $waNumber) }}" target="_blank" rel="noopener noreferrer" class="inline-block mt-2 text-xs font-semibold text-green-600 hover:text-green-700 transition-colors duration-200">Buka WhatsApp →</a>
                        @else
                            <span class="inline-block mt-2 text-xs text-gray-400 italic">Kontak belum dikonfigurasi</span>
                        @endif
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 min-w-0 pt-0">
            <div class="max-w-7xl mx-auto px-3 sm:px-5 lg:px-8 py-4 sm:py-6 lg:py-8">
                @yield('content')
            </div>
        </main>
    </div>

    <script>
        // ============ Mobile Sidebar ============
        function openMobileSidebar() {
            document.getElementById('mobileSidebar').classList.add('open');
            document.getElementById('sidebarBackdrop').classList.add('open');
            document.body.style.overflow = 'hidden';
        }
        function closeMobileSidebar() {
            document.getElementById('mobileSidebar').classList.remove('open');
            document.getElementById('sidebarBackdrop').classList.remove('open');
            document.body.style.overflow = '';
        }
        document.getElementById('hamburgerBtn').addEventListener('click', openMobileSidebar);
        // Close on ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeMobileSidebar();
        });

        // ============ Notification System ============
        const notificationBtn = document.getElementById('notificationBtn');
        const notificationDropdown = document.getElementById('notificationDropdown');
        const notificationList = document.getElementById('notificationList');
        const notificationBadge = document.getElementById('notificationBadge');

        const LAST_VIEWED_TIME_KEY = 'user_notification_last_viewed_time';

        function initializeNotificationTracking() {
            if (!localStorage.getItem(LAST_VIEWED_TIME_KEY)) {
                localStorage.setItem(LAST_VIEWED_TIME_KEY, Math.floor(Date.now() / 1000));
            }
        }

        function getLastViewedTime() {
            return parseInt(localStorage.getItem(LAST_VIEWED_TIME_KEY)) || 0;
        }

        function checkNewNotifications(notifications) {
            const lastViewedTime = getLastViewedTime();
            return notifications.some(notif => notif.timestamp > lastViewedTime);
        }

        notificationBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            const wasHidden = notificationDropdown.classList.contains('hidden');
            notificationDropdown.classList.toggle('hidden');
            if (wasHidden) {
                loadNotifications();
                localStorage.setItem(LAST_VIEWED_TIME_KEY, Math.floor(Date.now() / 1000));
                notificationBadge.classList.add('hidden');
            }
        });

        document.addEventListener('click', function(event) {
            if (!event.target.closest('#notificationBtn') && !event.target.closest('#notificationDropdown')) {
                notificationDropdown.classList.add('hidden');
            }
        });

        async function loadNotifications() {
            try {
                const response = await fetch('/api/notifications');
                const data = await response.json();
                displayNotifications(data);
            } catch (error) {
                notificationList.innerHTML = '<div class="px-4 py-8 text-center text-gray-500 text-sm">Gagal memuat notifikasi</div>';
            }
        }

        function displayNotifications(notifications) {
            // Filter: only new_asset and sold_out
            notifications = (notifications || []).filter(n => n.type === 'new_asset' || n.type === 'sold_out');

            if (!notifications.length) {
                notificationList.innerHTML = '<div class="px-4 py-8 text-center text-gray-500 text-sm">Tidak ada notifikasi</div>';
                notificationBadge.classList.add('hidden');
                return;
            }

            notificationList.innerHTML = notifications.map(notif => `
                <div class="px-4 py-3 hover:bg-orange-50 transition-colors">
                    <div class="flex items-start gap-3">
                        <div class="mt-1 flex-shrink-0">${getNotificationIcon(notif.type)}</div>
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-gray-900 text-sm">${notif.title}</p>
                            <p class="text-gray-600 text-xs mt-1">${notif.message}</p>
                            <p class="text-gray-400 text-xs mt-2">${formatTime(notif.created_at)}</p>
                        </div>
                    </div>
                </div>
            `).join('');

            if (notificationDropdown.classList.contains('hidden')) {
                if (checkNewNotifications(notifications)) {
                    notificationBadge.classList.remove('hidden');
                } else {
                    notificationBadge.classList.add('hidden');
                }
            }
        }

        function getNotificationIcon(type) {
            const icons = {
                'info': '<svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
                'new_asset': '<svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>',
                'sold_out': '<svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m6-4a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
            };
            return icons[type] || icons['info'];
        }

        function formatTime(dateString) {
            const date = new Date(dateString);
            const now = new Date();
            const seconds = Math.floor((now - date) / 1000);
            const minutes = Math.floor(seconds / 60);
            const hours = Math.floor(minutes / 60);
            const days = Math.floor(hours / 24);
            if (days > 0) return `${days} hari yang lalu`;
            if (hours > 0) return `${hours} jam yang lalu`;
            if (minutes > 0) return `${minutes} menit yang lalu`;
            return 'Baru saja';
        }

        initializeNotificationTracking();
        loadNotifications();
        setInterval(loadNotifications, 30000);
    </script>
</body>
</html>


