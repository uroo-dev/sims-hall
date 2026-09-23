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
- **Seeders**: `UserFactory::definition()` includes `username` + `role` (default `user`; password `password`). `php artisan migrate --seed` now works: seeds admin `test@example.com` + `UserSeeder` (super_admin `root` / `super@gmail.com` / `1234`, idempotent via `firstOrCreate`). `UserSeeder` can also be run standalone: `php artisan db:seed --class=UserSeeder`.
- **User model uses Laravel 13 attribute style** (`#[Fillable([...])]`, `#[Hidden([...])]`) while `Fitur` uses legacy `protected $fillable`. Follow the attribute style for new models.
- `.env` defaults to MySQL (local, gitignored); enable the `DB_*` lines before `migrate`. Tests always use in-memory sqlite. Default locale is `en`.
- `laravel/boost` is not installed; this file intentionally replaces the boost bootstrap placeholder.