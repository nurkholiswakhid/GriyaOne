<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Attempt to log the user in with rate limiting, remember me, and session fixation protection.
     */
    public function login(Request $request)
    {
        // 1. Validate input — tambahkan 'string' agar tidak menerima array injection
        $request->validate([
            'email'    => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'max:255'],
        ]);

        // 2. Rate limiting: maks 5 percobaan per email+IP per 60 detik
        $throttleKey = Str::lower($request->input('email')) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()
                ->withErrors([
                    'email' => "Terlalu banyak percobaan login. Silakan coba lagi dalam {$seconds} detik.",
                ])
                ->withInput($request->only('email', 'remember'));
        }

        // 3. Ambil credentials murni (tanpa field lain dari request)
        $credentials = $request->only('email', 'password');
        $remember    = $request->boolean('remember');

        // 4. Auth::attempt dengan remember me
        if (Auth::attempt($credentials, $remember)) {
            // Bersihkan counter rate limiting setelah berhasil
            RateLimiter::clear($throttleKey);

            // Cegah session fixation attack
            $request->session()->regenerate();

            $user = Auth::user();

            // Tentukan halaman tujuan berdasarkan role
            $roleHome = match ($user->role) {
                'admin'     => '/admin-dashboard',
                'marketing' => '/marketing/dashboard',
                default     => '/user-dashboard',
            };

            // Hapus intended URL yang mungkin dari role lain untuk menghindari
            // cross-role redirect yang berujung halaman 403 setelah login
            $intended = $request->session()->pull('url.intended', $roleHome);
            $rolePrefixes = match ($user->role) {
                'admin'     => ['/', '/admin', '/assets', '/informasi', '/manage-video', '/materi', '/notifications', '/users', '/profile', '/listing-aset', '/api'],
                'marketing' => ['/marketing', '/profile', '/listing-aset'],
                default     => ['/user-dashboard', '/profile', '/listing-aset'],
            };
            $useIntended = collect($rolePrefixes)
                ->contains(fn ($prefix) => str_starts_with($intended, $prefix));

            return redirect($useIntended ? $intended : $roleHome);
        }

        // 5. Catat kegagalan untuk rate limiter (decay 60 detik)
        RateLimiter::hit($throttleKey, 60);

        // 6. Kembalikan dengan pesan error + input lama (kecuali password)
        return back()
            ->withErrors(['email' => 'Email atau password salah.'])
            ->withInput($request->only('email', 'remember'));
    }

    /**
     * Log the user out with full session cleanup.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        // Hapus session & regenerate CSRF token
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
