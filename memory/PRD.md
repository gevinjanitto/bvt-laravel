<<<<<<< HEAD
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
=======
# Bali Vision Tour — Laravel + MySQL conversion

## Original problem statement
"https://github.com/gevinjanitto/bali-vision-tour ubah project ini jadi laravel dan database di mysql."
User choices: Laravel full-stack (Blade + Tailwind, no React), local storage uploads, seed same data as old project.

## Architecture
- Laravel 12 (PHP 8.2) at /app/laravel, MariaDB/MySQL (`bali_vision_tour`), Blade views, Tailwind (Play CDN), Alpine.js, Lucide icons.
- Public: SiteController (home, tours, cars, activities, about, articles, policies, POST /booking, POST /newsletter).
- Admin (/admin, session auth by username, 5 attempts/15 min lockout): dashboard, generic CRUD for tours/cars/activities/articles (config/resources.php), bookings status, site settings (contact/social/brand), content blocks JSON editor (config/site.php defaults + `settings` table override), account (username/password/idle timeout), image upload to storage/app/public.
- Seed: database/seeders/seed_data.json (copied from old backend) -> 9 tours, 6 cars, 9 activities, 7 articles + admin user from env.
- Supervisor: /etc/supervisor/conf.d/laravel.conf (laravel on :3000). Old frontend/backend programs stopped.

## Implemented (2026-06)
- Full public website parity with the React version (all pages, filters, booking dialog -> WhatsApp, newsletter).
- Full admin panel parity (CRUD, bookings, settings, content, account, upload).

## Backlog / P1
- Rich text WYSIWYG editor in admin (currently plain HTML/textarea) and structured list editors instead of JSON textareas.
- Idle auto-logout timer JS on admin pages (preference is stored, timer not enforced client-side).
- Rollback/ordering (drag & drop) for resources.
>>>>>>> 3d75822977b8fa74ecaa4dc0a07e5dc1508a4a17
