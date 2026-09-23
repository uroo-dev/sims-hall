<?php

namespace App\Http\Middleware;

use App\Models\Fitur;
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

        if (! $user || $user->role !== 'admin') {
            abort(403);
        }

        $adminFitur = Fitur::where('user_id', $user->id)->first();

        if (! $adminFitur || ! in_array($adminFitur->nama_fitur, $allowedFitur, true)) {
            abort(403);
        }

        return $next($request);
    }
}
