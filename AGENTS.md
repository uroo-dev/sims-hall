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
- **PKL & BKK dashboard** (branch feature `uroo`): every sidebar menu is its own page (per-form, not merged). All routes under `auth` + `role:admin,super_admin,super_duper_admin`:
  - `dashboard.pkl` → `Admin/PklBkk/dashboard.blade.php` (stat cards only).
  - `pklbkk.loker` → `Admin/bkk/index.blade.php` (list + filter jurusan); `pklbkk.loker.create` & `pklbkk.loker.edit`(?id=) → `Admin/bkk/update.blade.php` (form tambah/edit).
  - `pklbkk.pelamar` → `Admin/bkk/pelamar.blade.php`; `pklbkk.tempat` → `Admin/PklBkk/temppkl.blade.php`; `pklbkk.jurnal` → `Admin/PklBkk/jurnal.blade.php`; `pklbkk.nilai` → `Admin/PklBkk/nilai.blade.php`.
  - Shared header partial `Admin/PklBkk/partials/header.blade.php` (pass `$pklPage`). Sidebar `Admin/layout/sidebar-pklbkk.blade.php` auto-detected in `Admin/layout/app.blade.php` via `routeIs('dashboard.pkl','pklbkk.*')`. `AuthController::store` redirects to `dashboard.pkl` when `auth()->user()->fitur?->nama_fitur === 'pklbkk'`, else `dashboard`.
  - **Sidebar is fixed** (`h-full` + `overflow-y-auto`); the layout wrapper is `h-screen overflow-hidden` and each page's `<main>` is `flex-1 h-full overflow-y-auto`, so only content scrolls.
  - Data is JS mock arrays persisted in `localStorage` — key `pklbkk.{lokers,mitras,jurnals,nilais,pelamars}` (no DB tables yet).
- **Seeders**: `UserFactory::definition()` includes `username` + `role` (default `user`; password `password`). `php artisan migrate --seed` now works: seeds admin `admin`/`test@example.com` + `UserSeeder` (super_admin `root` / `super@gmail.com` / `1234`, `uroo` admin "Admin BKK & PKL" / `pklbkk@smk2nkra.sch.id` / `1234` with `Fitur` `pklbkk`; all idempotent via `firstOrCreate`). `UserSeeder` can also be run standalone: `php artisan db:seed --class=UserSeeder`.
- **Git branches**: `main` holds auth/login/dashboard + landing page base; branch `uroo` holds the PKL/BKK admin feature (based on `main`). Push feature work to `uroo`, stable base to `main`.
- **User model uses Laravel 13 attribute style** (`#[Fillable([...])]`, `#[Hidden([...])]`) while `Fitur` uses legacy `protected $fillable`. Follow the attribute style for new models.
- `.env` defaults to MySQL (local, gitignored); enable the `DB_*` lines before `migrate`. Tests always use in-memory sqlite. Default locale is `en`.
- `laravel/boost` is not installed; this file intentionally replaces the boost bootstrap placeholder.