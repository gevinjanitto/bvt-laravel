<<<<<<< HEAD
# Bali Vision Tour (bvt-laravel) — Railway deploy fix

## Problem statement
Clone https://github.com/gevinjanitto/bvt-laravel dan perbaiki agar bisa di-build/deploy di Railway.
Error di log Railway: `AH00534: apache2: Configuration error: More than one MPM loaded.`

## User choices
- Tetap pakai Apache (php:8.2-apache), perbaiki Dockerfile saja
- Database: MySQL (plugin Railway)

## Root cause
Paket apache2 di image php:8.2-apache ter-update saat `apt-get install` sehingga `mpm_event` ikut aktif
bersama `mpm_prefork` (mod_php butuh prefork saja) -> Apache menolak start.

## Implemented (2026-06)
- `Dockerfile` & `laravel/Dockerfile`: a2dismod mpm_event/mpm_worker, hapus symlink mods-enabled/mpm_event.*/mpm_worker.*,
  a2enmod mpm_prefork, dan `apache2ctl -t` (config test) saat build agar error muncul di tahap build.
- `laravel/docker/entrypoint.sh`: safety net yang sama sebelum `exec apache2-foreground`.

## Notes
- Docker tidak tersedia di environment ini, build tidak bisa diverifikasi lokal; verifikasi dilakukan dengan deploy ulang di Railway.
- Env Railway yang dibutuhkan ada di `laravel/README.md` (APP_KEY, DB_* dari plugin MySQL, ADMIN_USERNAME/PASSWORD).

## Backlog
- Volume untuk `/var/www/html/storage/app/public` agar upload tidak hilang saat redeploy.
=======
# PRD — Bali Vision Tour (Laravel)

## Problem statement (asli)
"saya punya kode di <code-server URL>. tolong perbaiki kode tersebut agar jalan dan bentuknya laravel. jangan ubah apapun perbaiki saja kalo ada yg rusak. dan buat agar bisa di deploy di railway"
Pilihan user: kode diambil dari URL VS Code (code-server), DB deploy = MySQL (Railway plugin), deploy via Dockerfile.

## Arsitektur
- Laravel 12 (PHP 8.2) di `/app/laravel` — Blade + Tailwind CDN + Alpine.js, admin panel `/admin/login`.
- DB: SQLite (dev) / MySQL (Railway). Preview lokal: MariaDB db `bvt` (user bvt / secret), server `php artisan serve --port=3000` via supervisor program `frontend`.
- Konten default di `config/site.php`, override via tabel `settings`. CRUD generik via `config/resources.php`.

## Yang dikerjakan (25 Sep 2026)
- Mengambil source dari code-server (login + terminal + port proxy), disalin ke `/app/laravel` (termasuk vendor).
- Install PHP 8.2 + ekstensi, MariaDB; verifikasi `migrate:fresh --seed` di SQLite **dan** MySQL — OK.
- Bug fix minimal: `AdminController::updateSettings` — `$data[$k] ?? []` (500 "Undefined array key social" bila grup kosong).
- Railway: `Dockerfile` (multi-stage composer → php:8.2-apache, port dari `$PORT`), `docker/entrypoint.sh` (storage:link, config/route/view cache, migrate, seed idempoten), `.dockerignore`, `railway.json` (healthcheck `/up`), README bagian deploy.
- Testing agent: backend 41/41, semua UI flow (booking dialog, newsletter, admin login/CRUD/settings/content/upload/logout) lolos.

## Backlog / catatan
- P1: Upload gambar di Railway ephemeral → perlu Volume mount `/var/www/html/storage/app/public`.
- P2: Preview URL publik pod ini tidak ter-route (platform), pengujian dilakukan via localhost:3000.
- P2: Dockerfile belum bisa di-build di pod (tidak ada docker) — langkah entrypoint diverifikasi manual via artisan.
>>>>>>> cc3625a21d58469944ed2bc96649c5cbbd81f861
