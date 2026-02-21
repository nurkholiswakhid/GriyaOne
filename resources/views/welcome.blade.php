<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - {{ config('app.name', 'GriyaOne') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif

    <style>
        body { font-family: 'Figtree', sans-serif; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4" style="background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 50%, #1a1a1a 100%);">

    <!-- Decorative background shapes -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-20 -right-20 w-96 h-96 rounded-full opacity-10" style="background: radial-gradient(circle, #ea580c, transparent);"></div>
        <div class="absolute -bottom-20 -left-20 w-80 h-80 rounded-full opacity-10" style="background: radial-gradient(circle, #ea580c, transparent);"></div>
    </div>

    <div class="w-full max-w-md relative z-10">
        <!-- Brand Logo -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl mb-4 shadow-2xl" style="background: linear-gradient(135deg, #ea580c, #f97316);">
                <svg class="w-11 h-11 text-white" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-white tracking-tight">{{ config('app.name', 'GriyaOne') }}</h1>
            <p class="text-gray-400 mt-1 text-sm">Sistem Manajemen Properti</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
            <!-- Card top accent -->
            <div class="h-1.5 w-full" style="background: linear-gradient(90deg, #ea580c, #f97316, #ea580c);"></div>

            <div class="p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-1">Selamat Datang</h2>
                <p class="text-gray-500 text-sm mb-7">Masukkan kredensial Anda untuk melanjutkan</p>

                <form method="POST" action="/login" class="space-y-5">
                    @csrf

                    <!-- Email Input -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">Email</label>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent outline-none transition text-sm"
                            style="--tw-ring-color: #ea580c;"
                            onfocus="this.style.boxShadow='0 0 0 3px rgba(234,88,12,0.15)'; this.style.borderColor='#ea580c';"
                            onblur="this.style.boxShadow=''; this.style.borderColor='#d1d5db';"
                            placeholder="nama@contoh.com"
                            required
                            autofocus
                        >
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Input -->
                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5">Password</label>
                        <input
                            id="password"
                            type="password"
                            name="password"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg outline-none transition text-sm"
                            onfocus="this.style.boxShadow='0 0 0 3px rgba(234,88,12,0.15)'; this.style.borderColor='#ea580c';"
                            onblur="this.style.boxShadow=''; this.style.borderColor='#d1d5db';"
                            placeholder="••••••••"
                            required
                        >
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input
                                id="remember"
                                type="checkbox"
                                name="remember"
                                class="h-4 w-4 rounded border-gray-300 text-orange-600"
                                style="accent-color: #ea580c;"
                                {{ old('remember') ? 'checked' : '' }}
                            >
                            <span class="text-sm text-gray-600">Ingat saya</span>
                        </label>
                        <a href="/password-reset" class="text-sm font-medium" style="color: #ea580c;">
                            Lupa password?
                        </a>
                    </div>

                    <!-- Submit Button -->
                    <button
                        type="submit"
                        class="w-full text-white font-semibold py-3 px-4 rounded-lg transition duration-200 ease-in-out active:scale-95 shadow-lg"
                        style="background: linear-gradient(135deg, #ea580c, #f97316);"
                        onmouseover="this.style.background='linear-gradient(135deg, #c2410c, #ea580c)';"
                        onmouseout="this.style.background='linear-gradient(135deg, #ea580c, #f97316)';"
                    >
                        Masuk
                    </button>
                </form>

                <!-- Register Link -->
                <div class="mt-6 pt-6 border-t border-gray-100 text-center">
                    <p class="text-sm text-gray-500">
                        Belum memiliki akun?
                        <a href="/register" class="font-semibold hover:underline" style="color: #ea580c;">
                            Daftar di sini
                        </a>
                    </p>
                </div>

                <!-- Test Credentials Info -->
                <div class="mt-4 p-4 rounded-xl border" style="background-color: #fff7ed; border-color: #fed7aa;">
                    <p class="text-xs font-bold mb-2" style="color: #9a3412;">Akun Test Tersedia:</p>
                    <div class="space-y-1 text-xs" style="color: #7c2d12;">
                        <p><strong>User:</strong> user@test.com / password</p>
                        <p><strong>Marketing:</strong> marketing@test.com / password</p>
                        <p><strong>Admin:</strong> admin@test.com / password</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-6 text-center">
            <p class="text-gray-500 text-xs">&copy; 2026 {{ config('app.name', 'GriyaOne') }}. Semua hak dilindungi.</p>
        </div>
    </div>
</body>
</html>





