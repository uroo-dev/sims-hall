<?php

use App\Http\Middleware\AdminFiturMiddleware;
use App\Http\Middleware\CheckRole;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => CheckRole::class,
            'adminFitur' => AdminFiturMiddleware::class,
        ]);

        $middleware->redirectUsersTo(function (Request $request) {
            $role = $request->user()?->role;

            return match ($role) {
                'pelanggan' => route('customer.dashboard'),
                'kepala_sekolah' => route('kepala-sekolah.dashboard'),
                'bkk', 'admin_pklbkk' => route('pkl.dashboard'),
                'admin_produk', 'admin_produk_unggulan' => route('produk-unggulan.index'),
                'admin_ppdb' => route('index.dashboard.ppdb'),
                'admin_kesiswaan' => route('admin.kesiswaan.index'),
                'admin_master' => route('datamaster.index'),
                'admin_sekolah' => route('admin.artikel.index'),
                'admin_aula', 'admin', 'super_admin', 'super_duper_admin' => route('admin.peminjaman.dashboard'),
                default => route('dashboard'),
            };
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();
