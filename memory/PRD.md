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
