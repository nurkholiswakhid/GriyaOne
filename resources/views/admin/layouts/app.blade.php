<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'GriyaOne - Admin Dashboard')</title>
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
    <nav class="sticky top-0 z-50 bg-white shadow-sm border-b border-orange-100">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center gap-3 min-w-0">
                    @php $__navLogo = \App\Models\Setting::get('login_logo_path',''); $__siteName = \App\Models\Setting::get('site_name','GriyaOne'); @endphp
                    @if($__navLogo)
                        <img src="{{ asset('storage/' . $__navLogo) }}" alt="Logo {{ $__siteName }}" class="w-10 h-10 rounded-lg object-contain bg-white p-1 shadow-md flex-shrink-0">
                    @else
                        <div class="w-10 h-10 rounded-lg flex items-center justify-center text-white flex-shrink-0 transition-colors duration-200 shadow-md" style="background: linear-gradient(135deg, #ea580c, #f97316);">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
                        </div>
                    @endif
                    <div class="hidden sm:block min-w-0">
                        <h1 class="text-lg sm:text-xl font-bold text-gray-900 truncate">{{ $__siteName }}</h1>
                        <p class="text-xs text-orange-600 font-semibold">@yield('role', 'Admin Dashboard')</p>
                    </div>
                </div>
                <button id="notificationBtn" class="relative p-2 text-gray-600 hover:text-orange-600 transition flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                    <span id="notificationBadge" class="hidden absolute top-1 right-1 w-2 h-2 bg-orange-500 rounded-full animate-pulse"></span>
                </button>

                <!-- Notification Dropdown -->
                <div id="notificationDropdown" class="hidden absolute top-16 right-4 w-80 bg-white rounded-lg shadow-2xl border border-gray-200 z-40 max-h-96 overflow-y-auto">
                    <div class="bg-gradient-to-r from-orange-600 to-orange-700 px-4 py-3 sticky top-0 z-10">
                        <h3 class="text-white font-semibold text-sm">Notifikasi</h3>
                    </div>
                    <div id="notificationList" class="divide-y divide-gray-100">
                        <div class="px-4 py-8 text-center text-gray-500 text-sm">
                            Memuat notifikasi...
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Layout -->
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-gradient-to-b from-white to-orange-50 border-r border-gray-200 min-h-screen pt-4 fixed left-0 top-16 bottom-0 overflow-y-auto lg:relative lg:top-0 hidden lg:block scrollbar-hide">
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
                            {{ $authUser ? strtoupper(substr($authUser->name, 0, 1)) : 'A' }}
                        </div>
                    @endif
                    <div class="min-w-0 flex-1">
                        <h3 class="font-semibold text-gray-900 text-sm truncate">{{ $authUser?->name ?? 'Admin System' }}</h3>
                        <p class="text-xs text-gray-500 truncate">{{ $authUser?->email ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <nav class="px-2 space-y-1">
                <!-- Main Navigation -->
                <a href="{{ route('admin.dashboard') }}" class="bg-gradient-to-r from-orange-600 to-orange-700 hover:from-orange-700 hover:to-orange-800 flex items-center justify-between gap-3 px-4 py-3 rounded-lg text-white font-medium text-sm shadow-md hover:shadow-lg transition-all duration-200 group">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 flex-shrink-0 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-3m0 0l7-4 7 4M5 9v10a1 1 0 001 1h12a1 1 0 001-1V9m-9 5h4"></path>
                        </svg>
                        <span>Dashboard</span>
                    </div>
                </a>

                <div class="text-xs font-semibold text-gray-500 px-4 py-3 uppercase tracking-wider mt-6">Manajemen</div>

                <a href="{{ route('users.index') }}" class="group relative flex items-center justify-between px-4 py-3 text-gray-700 hover:text-orange-600 hover:bg-orange-50 font-medium text-sm transition-colors duration-200 rounded-lg">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 flex-shrink-0 text-gray-400 group-hover:text-orange-600 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0zM16 11h6"></path>
                        </svg>
                        <span>Manajemen User</span>
                    </div>
                    <span class="bg-orange-100 text-orange-600 text-xs font-semibold px-2 py-0.5 rounded-full group-hover:bg-orange-200 transition-colors duration-200">{{ \App\Models\User::count() }}</span>
                </a>

                <a href="{{ route('assets.index') }}" class="group relative flex items-center justify-between px-4 py-3 text-gray-700 hover:text-orange-600 hover:bg-orange-50 font-medium text-sm transition-colors duration-200 rounded-lg">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 flex-shrink-0 text-gray-400 group-hover:text-orange-600 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        <span>Manajemen Listing Aset</span>
                    </div>
                    <span class="bg-orange-100 text-orange-600 text-xs font-semibold px-2 py-0.5 rounded-full group-hover:bg-orange-200 transition-colors duration-200">{{ \App\Models\Asset::count() }}</span>
                </a>

                <a href="{{ route('contents.index') }}" class="group relative flex items-center justify-between px-4 py-3 text-gray-700 hover:text-orange-600 hover:bg-orange-50 font-medium text-sm transition-colors duration-200 rounded-lg">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 flex-shrink-0 text-gray-400 group-hover:text-orange-600 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                        <span>Video Pembelajaran</span>
                    </div>
                    <span class="bg-orange-100 text-orange-600 text-xs font-semibold px-2 py-0.5 rounded-full group-hover:bg-orange-200 transition-colors duration-200">{{ \App\Models\Content::count() }}</span>
                </a>

                <a href="{{ route('materi.index') }}" class="group relative flex items-center justify-between px-4 py-3 text-gray-700 hover:text-orange-600 hover:bg-orange-50 font-medium text-sm transition-colors duration-200 rounded-lg">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 flex-shrink-0 text-gray-400 group-hover:text-orange-600 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        <span>Materi PDF</span>
                    </div>
                    <span class="bg-orange-100 text-orange-600 text-xs font-semibold px-2 py-0.5 rounded-full group-hover:bg-orange-200 transition-colors duration-200">{{ \App\Models\Material::count() }}</span>
                </a>

                <a href="{{ route('informasi.index') }}" class="group relative flex items-center justify-between px-4 py-3 text-gray-700 hover:text-orange-600 hover:bg-orange-50 font-medium text-sm transition-colors duration-200 rounded-lg">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 flex-shrink-0 text-gray-400 group-hover:text-orange-600 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Informasi Terbaru</span>
                    </div>
                    <span class="bg-orange-100 text-orange-600 text-xs font-semibold px-2 py-0.5 rounded-full group-hover:bg-orange-200 transition-colors duration-200">{{ \App\Models\Information::count() }}</span>
                </a>

                <div class="my-3 border-t border-gray-200"></div>

                <div class="text-xs font-semibold text-gray-500 px-4 py-3 uppercase tracking-wider">Sistem</div>

                <a href="{{ route('profile.edit') }}" class="group flex items-center gap-3 px-4 py-3 text-gray-700 hover:text-orange-600 hover:bg-orange-50 font-medium text-sm transition-colors duration-200 rounded-lg">
                    <svg class="w-5 h-5 flex-shrink-0 text-gray-400 group-hover:text-orange-600 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Akun</span>
                </a>

                <a href="{{ route('admin.login-settings.edit') }}" class="group flex items-center gap-3 px-4 py-3 text-gray-700 hover:text-orange-600 hover:bg-orange-50 font-medium text-sm transition-colors duration-200 rounded-lg">
                    <svg class="w-5 h-5 flex-shrink-0 text-gray-400 group-hover:text-orange-600 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                    </svg>
                    <span>Pengaturan Web</span>
                </a>

                <form action="/logout" method="POST">
                    @csrf
                    <button type="submit" class="w-full group flex items-center gap-3 px-4 py-3 text-gray-700 hover:text-orange-600 hover:bg-orange-50 font-medium text-sm transition-colors duration-200 rounded-lg">
                        <svg class="w-5 h-5 flex-shrink-0 text-gray-400 group-hover:text-orange-600 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        <span>Logout</span>
                    </button>
                </form>
            </nav>


        </aside>

        <!-- Main Content -->
        <main class="flex-1 lg:ml-0 pt-4">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Confirmation Modal -->
    <div id="confirmModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4" style="background: rgba(0, 0, 0, 0); transition: all 0.3s ease;">
        <style>
            #confirmModal:not(.hidden) {
                background: rgba(0, 0, 0, 0.4) !important;
                backdrop-filter: blur(8px);
            }
            #confirmModal:not(.hidden) > div {
                transform: scale(1) translateY(0) !important;
                opacity: 1 !important;
            }
        </style>

        <div class="bg-white rounded-2xl shadow-lg max-w-xs w-full" style="transform: scale(0.95) translateY(10px); opacity: 0; transition: all 0.3s ease;">

            <!-- Header -->
            <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900" id="modalTitle">Konfirmasi</h3>
                <button onclick="closeConfirmModal()" class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Body -->
            <div class="px-6 py-4">
                <p class="text-gray-600 text-sm leading-relaxed" id="modalMessage">Apakah Anda yakin?</p>
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 bg-gray-50 rounded-b-2xl flex gap-3 justify-end">
                <button onclick="closeConfirmModal()" class="px-4 py-2 text-gray-700 font-medium text-sm rounded-lg border border-gray-300 hover:bg-gray-100 transition">
                    Batal
                </button>
                <button onclick="confirmAction()" class="px-4 py-2 bg-red-600 text-white font-medium text-sm rounded-lg hover:bg-red-700 transition">
                    Hapus
                </button>
            </div>
        </div>
    </div>


    <script>
        let pendingForm = null;

        // ============ Notification System ============
        const notificationBtn = document.getElementById('notificationBtn');
        const notificationDropdown = document.getElementById('notificationDropdown');
        const notificationList = document.getElementById('notificationList');
        const notificationBadge = document.getElementById('notificationBadge');

        // Storage keys
        const LAST_VIEWED_TIME_KEY = 'notification_last_viewed_time';

        // Initialize: Set initial timestamp (now)
        function initializeNotificationTracking() {
            if (!localStorage.getItem(LAST_VIEWED_TIME_KEY)) {
                localStorage.setItem(LAST_VIEWED_TIME_KEY, Math.floor(Date.now() / 1000));
            }
        }

        // Get last viewed time
        function getLastViewedTime() {
            return parseInt(localStorage.getItem(LAST_VIEWED_TIME_KEY)) || 0;
        }

        // Check if there are new notifications
        function checkNewNotifications(notifications) {
            const lastViewedTime = getLastViewedTime();
            return notifications.some(notif => notif.timestamp > lastViewedTime);
        }

        // Toggle notification dropdown
        notificationBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            const wasHidden = notificationDropdown.classList.contains('hidden');

            notificationDropdown.classList.toggle('hidden');

            if (wasHidden) {
                // Dropdown is now open
                loadNotifications();
                // Mark as viewed
                localStorage.setItem(LAST_VIEWED_TIME_KEY, Math.floor(Date.now() / 1000));
                // Hide badge immediately
                notificationBadge.classList.add('hidden');
            }
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            if (!event.target.closest('#notificationBtn') && !event.target.closest('#notificationDropdown')) {
                notificationDropdown.classList.add('hidden');
            }
        });

        // Fetch and load notifications
        async function loadNotifications() {
            try {
                const response = await fetch('/api/notifications');
                const data = await response.json();
                displayNotifications(data);
            } catch (error) {
                console.error('Error loading notifications:', error);
                notificationList.innerHTML = '<div class="px-4 py-8 text-center text-gray-500 text-sm">Gagal memuat notifikasi</div>';
            }
        }

        // Display notifications
        function displayNotifications(notifications) {
            if (!notifications || notifications.length === 0) {
                notificationList.innerHTML = '<div class="px-4 py-8 text-center text-gray-500 text-sm">Tidak ada notifikasi</div>';
                notificationBadge.classList.add('hidden');
                return;
            }

            notificationList.innerHTML = notifications.map(notif => `
                <div class="px-4 py-3 hover:bg-orange-50 transition-colors cursor-pointer">
                    <div class="flex items-start gap-3">
                        <div class="mt-1 flex-shrink-0">
                            ${getNotificationIcon(notif.type)}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-gray-900 text-sm">${notif.title}</p>
                            <p class="text-gray-600 text-xs mt-1">${notif.message}</p>
                            <p class="text-gray-400 text-xs mt-2">${formatTime(notif.created_at)}</p>
                        </div>
                    </div>
                </div>
            `).join('');

            // Update badge visibility based on whether there are new notifications
            if (notificationDropdown.classList.contains('hidden')) {
                // Only show badge if dropdown is closed and there are new notifications
                if (checkNewNotifications(notifications)) {
                    notificationBadge.classList.remove('hidden');
                } else {
                    notificationBadge.classList.add('hidden');
                }
            }
        }

        // Get icon based on notification type
        function getNotificationIcon(type) {
            const icons = {
                'info': '<svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
                'new_asset': '<svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>',
                'sold_out': '<svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m6-4a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
            };
            return icons[type] || icons['info'];
        }

        // Format time to relative time
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
            return `Baru saja`;
        }

        // Initialize tracking on page load
        initializeNotificationTracking();

        // Load notifications on page load
        loadNotifications();

        // Refresh notifications every 30 seconds to check for new ones
        setInterval(loadNotifications, 30000);

        // ============ Confirmation Modal ============
        function openConfirmModal(message = 'Apakah Anda yakin?', form = null) {
            document.getElementById('modalMessage').textContent = message;
            const modal = document.getElementById('confirmModal');
            modal.classList.remove('hidden');
            pendingForm = form;

            // Trigger animation
            setTimeout(() => {
                modal.style.background = 'rgba(0, 0, 0, 0.4)';
                const modalContent = modal.querySelector('div');
                modalContent.style.transform = 'scale(1)';
                modalContent.style.opacity = '1';
                modalContent.style.backdropFilter = 'blur(8px)';
            }, 10);
        }

        function closeConfirmModal() {
            const modal = document.getElementById('confirmModal');
            const modalContent = modal.querySelector('div');

            modalContent.style.transform = 'scale(0.95)';
            modalContent.style.opacity = '0';
            modal.style.background = 'rgba(0, 0, 0, 0)';
            modal.style.backdropFilter = 'blur(0px)';

            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);

            pendingForm = null;
        }

        function confirmAction() {
            if (pendingForm) {
                pendingForm.submit();
            }
            closeConfirmModal();
        }

        // Close modal dengan Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && !document.getElementById('confirmModal').classList.contains('hidden')) {
                closeConfirmModal();
            }
        });

        // Close modal saat click backdrop
        document.getElementById('confirmModal').addEventListener('click', function(event) {
            if (event.target === this) {
                closeConfirmModal();
            }
        });

        // Handle semua form dengan data-confirm
        document.addEventListener('submit', function(event) {
            const form = event.target;
            const confirmMessage = form.getAttribute('data-confirm');

            if (confirmMessage) {
                event.preventDefault();
                openConfirmModal(confirmMessage, form);
            }
        });
    </script>
</body>
</html>


