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
- **PKL & BKK module** (branch `uroo`, fully rewritten from scratch — DB-backed, NOT `localStorage`): all routes under `auth` + `role:bkk,admin,super_admin,super_duper_admin`, prefix `/dashboard/pkl-bkk`:
  - `pkl.dashboard` → `Admin/bkk/dashboard.blade.php`; `pkl.dudi.index` / `pkl.dudi.update` / `pkl.dudi.acc-landing`; `pkl.lowongan.*` (CRUD + `toggle`); `pkl.siswa.index`; `pkl.index` (daftar penempatan) + `pkl.penempatan.status` / `pkl.penempatan.bulk-status`; `pkl.create` / `pkl.store`; `pkl.surat.show` / `.download` / `.regenerate`.
  - Migrations `2026_09_29_*` create `gurus`, `siswas`, `dudis`, `surat_pengajuans`, `penempatan_pkls`, `lowongans`, plus `add_bkk_role_to_users_table` (baseline `users.role` enum had no `bkk`).
  - `users.role` gained `bkk`; the old `fiturs.pklbkk` redirect is gone. `AuthController::store` redirects role `bkk` to `pkl.dashboard`, everyone else to `dashboard`.
  - Sidebar is role-driven from `config/menu.php` + `App\Support\Menu`; role `bkk` sees only Dashboard + PKL & BKK. Placeholder modules render as disabled `href="#"` links.
  - Domain rules: DUDI baru default `is_mitra_resmi=false` + `tampil_di_landing=false`; public lowongan = `is_active` AND `deadline >= today` with external `link_daftar`; penempatan status only `pengajuan` → `FIX`/`ditolak`; `Siswa::belumPkl()` = belum punya FIX (a `pengajuan` student is counted in BOTH `menunggu` and `belum_pkl`).
  - `StorePengajuanPklRequest` payload: `dudi_id` XOR `dudi_baru[nama|alamat|kota|bidang_usaha|kontak_person|no_hp|kuota_maksimal]`. Must stay a single nested array — a key cannot be both the name string and a detail array in PHP. Laravel has NO `prohibited_with` rule; use `Rule::prohibitedIf()`.
  - `SuratPengajuanService` builds surat + penempatan in one transaction, then renders DomPDF to `storage/app/public/surat-pkl/surat-pkl-{year}-{index}.pdf`. PDF failure is non-fatal (logged, `file_pdf_path` left null, retryable via `regenerate`). Requires `barryvdh/laravel-dompdf`.
  - Public API (no auth, `throttle:60,1`, names `api.pkl.*`): `GET /api/pkl/dudi`, `/lowongan`, `/rekap`, `/siswa/{nis}`.
  - Tests: `tests/Feature/PklBkkTest.php` (25) + `tests/Feature/BkkSeederTest.php` (5). `BkkSeeder` is idempotent — look up the surat by `dudi_id` + `whereDate('tanggal_surat')`, never by a freshly generated number.

- **Seeders**: `UserFactory::definition()` includes `username` + `role` (default `user`; password `password`). `php artisan migrate --seed` now works: seeds admin `admin`/`test@example.com` + `UserSeeder` (super_admin `root` / `super@gmail.com` / `1234`, `uroo` and `bkk` both role `bkk` / password `1234`; all idempotent via `firstOrCreate`) + `BkkSeeder` (2 guru, 5 siswa, 3 DUDI, 2 lowongan, 1 surat, 1 penempatan FIX). `UserSeeder` can also be run standalone: `php artisan db:seed --class=UserSeeder`.
- **Git branches**: `main` holds auth/login/dashboard + landing page base; branch `uroo` holds the PKL/BKK admin feature (based on `main`). Push feature work to `uroo`, stable base to `main`.
- **User model uses Laravel 13 attribute style** (`#[Fillable([...])]`, `#[Hidden([...])]`) while `Fitur` uses legacy `protected $fillable`. Follow the attribute style for new models.
- `.env` defaults to MySQL (local, gitignored); enable the `DB_*` lines before `migrate`. Tests always use in-memory sqlite. Default locale is `en`.
- `laravel/boost` is not installed; this file intentionally replaces the boost bootstrap placeholder.