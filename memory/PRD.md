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
