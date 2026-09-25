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

## Deploy ke Railway (Dockerfile)
Repo ini sudah berisi `Dockerfile`, `docker/entrypoint.sh`, `.dockerignore`, dan `railway.json` (builder Dockerfile, healthcheck `/up`).

1. Buat project di Railway → **New Service → GitHub Repo** (pilih repo ini). Jika folder Laravel ada di sub-folder, set **Root Directory** ke folder tersebut.
2. Tambahkan plugin **MySQL** (`New → Database → MySQL`).
3. Di service Laravel → **Variables**, isi:
   ```
   APP_NAME="Bali Vision Tour"
   APP_ENV=production
   APP_DEBUG=false
   APP_KEY=base64:...            # hasil: php artisan key:generate --show
   APP_URL=https://<domain-railway-anda>

   DB_CONNECTION=mysql
   DB_HOST=${{MySQL.MYSQLHOST}}
   DB_PORT=${{MySQL.MYSQLPORT}}
   DB_DATABASE=${{MySQL.MYSQLDATABASE}}
   DB_USERNAME=${{MySQL.MYSQLUSER}}
   DB_PASSWORD=${{MySQL.MYSQLPASSWORD}}

   SESSION_DRIVER=file
   CACHE_STORE=file
   FILESYSTEM_DISK=public
   SESSION_SECURE_COOKIE=true

   ADMIN_USERNAME=admin
   ADMIN_PASSWORD=<password-kuat>
   ```
   (Alternatif: cukup `DB_URL=${{MySQL.MYSQL_URL}}` menggantikan `DB_HOST/PORT/DATABASE/USERNAME/PASSWORD`.)
4. Deploy. Saat container start, `entrypoint.sh` otomatis menjalankan `storage:link`, `config:cache`, `route:cache`, `view:cache`, `migrate --force`, dan `db:seed --force` (seeder idempoten: admin & data awal hanya dibuat jika belum ada).
   - Set `RUN_MIGRATIONS=false` / `RUN_SEED=false` jika ingin menonaktifkannya.
5. Generate domain di **Settings → Networking**, lalu buka `/admin/login`.

> Catatan: filesystem Railway bersifat ephemeral. Agar gambar yang di-upload dari admin tidak hilang saat redeploy, tambahkan **Volume** dan mount ke `/var/www/html/storage/app/public`.


- `app/Http/Controllers/SiteController.php` — halaman publik, booking (`POST /booking`), newsletter.
- `app/Http/Controllers/Admin/*` — login, dashboard, CRUD konten generik (`config/resources.php`), bookings, pengaturan, konten blok, akun, upload.
- `config/site.php` — konten default website (kontak, brand, blok teks/gambar) yang bisa di-override dari admin (tabel `settings`).
- `database/seeders/seed_data.json` — data awal dari project lama.
