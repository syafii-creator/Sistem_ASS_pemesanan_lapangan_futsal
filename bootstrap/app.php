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
    ->withMiddleware(function (Middleware $middleware) {
        // Arahkan pengunjung yang belum login langsung ke halaman portal login
        $middleware->redirectTo(
            guests: '/auth',
            users: '/dashboard'
        );

        $middleware->alias([
            'player.only' => \App\Http\Middleware\EnsurePlayer::class,
            'tenant.only' => \App\Http\Middleware\EnsureTenant::class,
            'superadmin.only' => \App\Http\Middleware\EnsureSuperAdmin::class,
        ]);

        // Nonaktifkan CSRF untuk webhook resmi Midtrans
        $middleware->validateCsrfTokens(except: [
            'midtrans/notification',
            'midtrans/notification/*',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
