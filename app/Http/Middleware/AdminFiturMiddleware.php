<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminFiturMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$allowedFitur): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(403);
        }

        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        if ($user->role !== 'admin') {
            abort(403);
        }

        $adminFitur = $user->fitur;

        if (! $adminFitur || ! in_array($adminFitur->nama_fitur, $allowedFitur, true)) {
            abort(403);
        }

        return $next($request);
    }
}
