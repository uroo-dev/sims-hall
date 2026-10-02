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

        foreach ($allowedFitur as $fitur) {
            if ($user->hasFitur($fitur)) {
                return $next($request);
            }
        }

        abort(403);
    }
}
