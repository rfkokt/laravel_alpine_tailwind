# 🚀 Deployment Guide — Laravel + Vite (Production)

Dokumen ini menjelaskan langkah **build & run di production** untuk proyek Laravel yang merender frontend via **Blade**, dengan **Vite** hanya untuk build JS/CSS (bukan SPA terpisah).

---

## 0) Prasyarat Server

-   PHP **8.1+** (disarankan 8.2/8.3) dengan ekstensi umum (mbstring, openssl, pdo, tokenizer, xml, ctype, gd/Fileinfo)
-   **Composer** (server/CI)
-   **Node.js 18+** (build machine/CI)
-   **Nginx + PHP-FPM** (production)
-   Database (MySQL/PostgreSQL/SQLite) sesuai `.env`

---

## 1) Konfigurasi `.env` untuk Production

Contoh nilai penting:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

# Database (contoh MySQL)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_db
DB_USERNAME=your_user
DB_PASSWORD=your_pass

# Driver umum & aman untuk awal
CACHE_STORE=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync

# (Opsional) CDN/subdomain asset
# ASSET_URL=https://cdn.your-domain.com
```

> Pastikan **APP_KEY** sudah ada (sekali generate di provisioning): `php artisan key:generate`

---

## 2) Install Dependencies (Build Stage)

Jalankan di **CI / build server** (bukan di container runtime yang read-only):

```bash
composer install --no-dev --prefer-dist --optimize-autoloader
npm ci
npm run build   # menghasilkan manifest & asset di public/build
```

---

## 3) Optimisasi Laravel & Migrasi

```bash
php artisan migrate --force
php artisan storage:link      # jika butuh akses storage publik
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
php artisan optimize
```

---

## 4) Permission (Linux)

```bash
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R u+rwX,go-w storage bootstrap/cache
```

> Ganti `www-data` sesuai user PHP-FPM di server Anda.

---

## 5) Jalankan di Production (Nginx + PHP-FPM)

**Jangan** gunakan `php artisan serve` di produksi. Pakai Nginx sebagai web server.

### Contoh Nginx minimal

```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /var/www/your-app/public;

    index index.php;
    charset utf-8;

    # Static/built assets
    location ~* \.(?:css|js|map|jpg|jpeg|png|gif|svg|webp|ico|woff2?)$ {
        try_files $uri =404;
        access_log off;
        expires 1y;
    }

    # Aplikasi
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    # PHP-FPM
    location ~ \.php$ {
        include fastcgi_params;
        fastcgi_pass unix:/run/php/php8.2-fpm.sock; # sesuaikan
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        fastcgi_param DOCUMENT_ROOT $realpath_root;
        fastcgi_read_timeout 300;
    }

    # Sembunyikan file dot
    location ~ /\. {
        deny all;
    }
}
```

Reload services:

```bash
sudo systemctl reload nginx
sudo systemctl restart php8.2-fpm
```

---

## 6) Queue/Jobs (Opsional)

Jika menggunakan queue:

```bash
php artisan queue:table
php artisan migrate --force
# Jalankan dengan supervisor/systemd
php artisan queue:work --sleep=3 --tries=3 --max-time=3600
```

**Supervisor** contoh program:

```
[program:your-app-queue]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/your-app/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
numprocs=1
redirect_stderr=true
stdout_logfile=/var/log/supervisor/your-app-queue.log
stopwaitsecs=3600
```

---

## 7) Troubleshooting Cepat

-   **Blank/Style tidak ter-load** → Pastikan `npm run build` sukses dan `@vite([...])` ada di layout. Cek folder `public/build` & `manifest.json`.
-   **`APP_KEY` error** → Jalankan `php artisan key:generate` (sekali saat provisioning), lalu `php artisan config:cache`.
-   **Permission denied** → Pastikan `storage/` & `bootstrap/cache/` dimiliki user PHP-FPM.
-   **Route 404 untuk asset** → Cek Nginx `root` mengarah ke `public/`. Pastikan `try_files` benar.
-   **Debug aktif di prod** → Set `APP_DEBUG=false` dan `APP_ENV=production`, lalu `php artisan config:cache`.
-   **Migrasi gagal** → Cek kredensial DB di `.env`, pastikan DB bisa diakses dari server.

---

## 8) Ringkasan Perintah (Once & Done)

```bash
# Build
composer install --no-dev --prefer-dist --optimize-autoloader
npm ci && npm run build

# Optimize & migrate
php artisan migrate --force
php artisan optimize

# Serve via Nginx + PHP-FPM
# (bukan artisan serve)
```
