# AGENTS.md

Laravel 13 (PHP ^8.3) + Blade + Tailwind 4 (Vite) app: school facility/website for SMK N 2 Kra (SIMS Sarpras — aula booking, PPDB, PKL/BKK, kesiswaan). **Early scaffold**: only route is `/`; README describes the *target* architecture that is mostly unimplemented. Trust code over README.

## Commands

- Full bootstrap: `composer setup` (composer install → copy .env → key:generate → `migrate --force` → `npm install --ignore-scripts` → build). `.npmrc` sets `ignore-scripts=true`, so npm install never runs postinstall hooks.
- Dev servers: `composer dev` (= `php artisan dev`). Assets: `npm run dev` / `npm run build`.
- Tests: `composer test` (runs `config:clear` then `php artisan test`). phpunit.xml forces `sqlite :memory:` — no DB setup needed. Only bootstrap/example tests exist.
- Lint: `vendor/bin/pint` (installed, no `pint.json` → framework defaults). No CI, no pre-commit hooks.

## Structure & conventions

- Views split into capitalized dirs `resources/views/{Admin,Public,Auth}`; controllers (`app/Http/Controllers`) are basically empty. Layout skeletons: `Admin/layout/app.blade.php`, `Public/layout/app.blade.php`.
- Vite (`vite.config.js`): Tailwind 4 via `@tailwindcss/vite`, fonts via `laravel-vite-plugin` `bunny()` helper, CSS/JS inputs only (no blade glob). Watch ignores `storage/framework/views`.
- RBAC middleware aliases registered in `bootstrap/app.php`:
  - `role:super_admin,admin` → `CheckRole` (403 if `users.role` not in list).
  - `adminFitur:pklbkk` → `AdminFiturMiddleware` (requires role `admin` + matching row in `fiturs`).
  - `users.role` enum: `admin, user, guru, kepala_sekolah, super_admin, super_duper_admin, pelanggan`. `fiturs.nama_fitur` enum: `produk_unggulan, master, pklbkk, aula, kesiswaan` (unique per user).

## Gotchas

- **Auth**: custom minimal auth (NO Breeze — not in `composer.json`). Routes: `GET/POST /login` (guest, login by `username`+`password`, POST rate-limited `throttle:6,1`), `POST /logout`, `GET /dashboard` guarded by `auth` + `role:admin,super_admin,super_duper_admin`. Login view: `resources/views/Auth/login.blade.php` (extends `Auth.layout.app`); dashboard: `views/Admin/dashboard.blade.php` (extends `Admin.layout.app`), logged-out users are redirected to `route('login')`.
- **Landing page**: `GET /` returns `resources/views/Public/landing.blade.php` (extends `Public.layout.app`). It is a fixed-width 1280px Figma-style artboard (overflow-x-auto wrapper); decorative `+` clusters use the `.plus-tex` CSS class defined in `resources/css/app.css`. Public layout loads Google Fonts (Poppins/Inter/Montserrat/Nunito/Public Sans).
- **PKL & BKK dashboard** (branch feature `uroo`): `GET /dashboard/pkl-bkk` (`PklBkkDashboardController@index`, `views/Admin/PklBkk/dashboard.blade.php`) guarded by `auth` + `role:admin,super_admin,super_duper_admin`. `AuthController::store` redirects there when `auth()->user()->fitur?->nama_fitur === 'pklbkk'`, else `dashboard`. `Admin/layout/app.blade.php` yields `@yield('sidebar')` when `@hasSection('sidebar')`, else default `Admin.layout.sidebar`; PKL page yields `Admin.layout.sidebar-pklbkk`. Dashboard data is JS mock arrays (no DB tables yet). `resources/views/Admin/bkk/{index,update}.blade.php` are empty user scaffolds.
- **Seeders**: `UserFactory::definition()` includes `username` + `role` (default `user`; password `password`). `php artisan migrate --seed` now works: seeds admin `admin`/`test@example.com` + `UserSeeder` (super_admin `root` / `super@gmail.com` / `1234`, `uroo` admin "Admin BKK & PKL" / `pklbkk@smk2nkra.sch.id` / `1234` with `Fitur` `pklbkk`; all idempotent via `firstOrCreate`). `UserSeeder` can also be run standalone: `php artisan db:seed --class=UserSeeder`.
- **Git branches**: `main` holds auth/login/dashboard + landing page base; branch `uroo` holds the PKL/BKK admin feature (based on `main`). Push feature work to `uroo`, stable base to `main`.
- **User model uses Laravel 13 attribute style** (`#[Fillable([...])]`, `#[Hidden([...])]`) while `Fitur` uses legacy `protected $fillable`. Follow the attribute style for new models.
- `.env` defaults to MySQL (local, gitignored); enable the `DB_*` lines before `migrate`. Tests always use in-memory sqlite. Default locale is `en`.
- `laravel/boost` is not installed; this file intentionally replaces the boost bootstrap placeholder.