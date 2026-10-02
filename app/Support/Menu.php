<?php

namespace App\Support;

use Illuminate\Support\Facades\Route;

/**
 * Resolver menu sidebar berdasarkan role user.
 *
 * Modul dideklarasikan di config/menu.php. Kelas ini menyaring modul yang
 * boleh diakses role tertentu dan menyelesaikan nama route (string atau map
 * per-role) menjadi URL.
 */
class Menu
{
    /**
     * Modul yang boleh diakses oleh role given.
     *
     * @return array<int, array<string, mixed>>
     */
    public static function forRole(?string $role): array
    {
        $modules = config('menu.modules', []);

        if ($role === null) {
            return [];
        }

        return array_values(array_filter(
            $modules,
            fn (array $module) => in_array($role, $module['roles'] ?? [], true)
        ));
    }

    /**
     * Resolve nama route menjadi URL.
     *
     * Mendukung:
     *   'pkl.dashboard'                        -> route() biasa
     *   ['bkk' => 'pkl.dashboard', 'default' => 'dashboard']
     *
     * @param  string|array<string, string>|null  $route
     */
    public static function url(string|array|null $route, ?string $role = null): ?string
    {
        if ($route === null) {
            return null;
        }

        $name = $route;

        if (is_array($route)) {
            $name = $role !== null && isset($route[$role])
                ? $route[$role]
                : ($route['default'] ?? null);
        }

        if ($name === null) {
            return null;
        }

        return Route::has($name) ? route($name) : null;
    }

    /**
     * Apakah halaman saat ini termasuk menu ini?
     *
     * Route null (menu belum punya halaman) tidak pernah dianggap aktif.
     *
     * @param  string|array<string, string>|null  $route
     */
    public static function isActive(string|array|null $route, ?string $role = null): bool
    {
        if ($route === null) {
            return false;
        }

        if (is_array($route)) {
            $route = $role !== null && isset($route[$role])
                ? $route[$role]
                : ($route['default'] ?? null);

            if ($route === null) {
                return false;
            }
        }

        return request()->routeIs($route);
    }
}
