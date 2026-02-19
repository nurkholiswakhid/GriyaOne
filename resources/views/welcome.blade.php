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
        body {
            font-family: 'Figtree', sans-serif;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Logo -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-white">{{ config('app.name', 'GriyaOne') }}</h1>
            <p class="text-slate-300 mt-2">Sistem Manajemen Properti</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Login</h2>
            <p class="text-gray-600 mb-6">Masukkan kredensial Anda untuk melanjutkan</p>

            <form method="POST" action="/login" class="space-y-4">
                @csrf

                <!-- Email Input -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition"
                        placeholder="nama@contoh.com"
                        required
                        autofocus
                    >
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Input -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition"
                        placeholder="••••••••"
                        required
                    >
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center">
                    <input
                        id="remember"
                        type="checkbox"
                        name="remember"
                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                        {{ old('remember') ? 'checked' : '' }}
                    >
                    <label for="remember" class="ml-2 block text-sm text-gray-700">Ingat saya</label>
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-200 ease-in-out transform hover:scale-105 active:scale-95"
                >
                    Login
                </button>
            </form>

            <!-- Forgot Password Link -->
            <div class="mt-4 text-center">
                <a href="/password-reset" class="text-sm text-blue-600 hover:text-blue-700">
                    Lupa password?
                </a>
            </div>

            <!-- Register Link -->
            <div class="mt-6 pt-6 border-t border-gray-300">
                <p class="text-sm text-gray-600 text-center">
                    Belum memiliki akun?
                    <a href="/register" class="text-blue-600 hover:text-blue-700 font-medium">
                        Daftar di sini
                    </a>
                </p>
            </div>
            <!-- Test Credentials Info -->
            <div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                <p class="text-sm font-semibold text-blue-900 mb-3">📝 Akun Test Tersedia:</p>
                <div class="space-y-2 text-xs text-blue-800">
                    <p><strong>User:</strong> user@test.com / password</p>
                    <p><strong>Marketing:</strong> marketing@test.com / password</p>
                    <p><strong>Admin:</strong> admin@test.com / password</p>
                </div>
            </div>        </div>

        <!-- Footer -->
        <div class="mt-8 text-center">
            <p class="text-slate-400 text-sm">&copy; 2026 {{ config('app.name', 'GriyaOne') }}. Semua hak dilindungi.</p>
        </div>
    </div>
</body>
</html>



