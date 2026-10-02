<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(403);
        }

        if (in_array($user->role, $roles, true)) {
            return $next($request);
        }

        // Super admin bypasses admin/module role checks
        $adminRoles = ['admin', 'super_admin', 'super_duper_admin', 'admin_aula', 'admin_master', 'admin_kesiswaan', 'admin_produk', 'admin_produk_unggulan', 'admin_ppdb', 'admin_pklbkk', 'admin_sekolah', 'bkk'];
        if ($user->isSuperAdmin() && array_intersect($roles, $adminRoles)) {
            return $next($request);
        }

        abort(403);
    }
}
