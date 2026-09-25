# Bali Vision Tour — Laravel + MySQL

Website tour & travel Bali (Blade + Tailwind + Alpine) dengan admin panel di `/admin/login`.

## Menjalankan lokal
```bash
cp .env.example .env            # isi DB_* dan ADMIN_USERNAME / ADMIN_PASSWORD
composer install
php artisan key:generate
php artisan migrate --seed       # membuat tabel + seed data tour/car/activity/article + akun admin
php artisan storage:link         # untuk upload gambar (storage/app/public)
php artisan serve
```

## Struktur
- `app/Http/Controllers/SiteController.php` — halaman publik, booking (`POST /booking`), newsletter.
- `app/Http/Controllers/Admin/*` — login, dashboard, CRUD konten generik (`config/resources.php`), bookings, pengaturan, konten blok, akun, upload.
- `config/site.php` — konten default website (kontak, brand, blok teks/gambar) yang bisa di-override dari admin (tabel `settings`).
- `database/seeders/seed_data.json` — data awal dari project lama.
