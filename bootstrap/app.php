<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Percayai semua proxy (termasuk ngrok, Cloudflare, load balancer, dll.)
        // agar Laravel membaca header X-Forwarded-Proto/Host dengan benar
        // dan menghasilkan URL HTTPS yang tepat.
        $middleware->trustProxies(at: '*');

        // Redirect authenticated users away from guest-only pages (login, register, etc.)
        $middleware->redirectUsersTo('/dashboard');

        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
