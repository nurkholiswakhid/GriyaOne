<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Catatan: auth middleware seharusnya sudah menjamin user terautentikasi.
        // Guard ini sebagai fail-safe jika middleware diterapkan tanpa auth.
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (empty($roles) || in_array(Auth::user()->role, $roles)) {
            return $next($request);
        }

        abort(403, 'Anda tidak memiliki akses ke halaman ini.');
    }
}
