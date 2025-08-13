# 🚀 Instalasi Project Laravel + Alpine.js + Tailwind CSS

Proyek ini menggunakan **Laravel** sebagai backend sekaligus frontend, dengan **Vite** untuk proses build JavaScript dan CSS.  
Tidak ada frontend terpisah — semua view dikelola menggunakan Blade template Laravel.

---

## 1️⃣ Clone Repository

```bash
git clone https://github.com/username/nama-repo.git
cd nama-repo
```

---

## 2️⃣ Install Dependencies

### Install dependency untuk Laravel (PHP)

```bash
composer install
```

### Install dependency untuk Asset Build (Node.js)

```bash
npm install
```

---

## 3️⃣ Konfigurasi Environment

Copy file `.env.example` menjadi `.env`:

```bash
cp .env.example .env
```

---

## 4️⃣ Generate APP Key

```bash
php artisan key:generate
```

---

## 5️⃣ Jalankan Aplikasi

Buka **dua terminal** terpisah:

### Terminal 1 - Laravel Server

```bash
php artisan serve
```

Aplikasi akan berjalan di:

```
http://localhost:8000
```

### Terminal 2 - Vite Dev Server (untuk asset build)

```bash
npm run dev
```

Vite akan meng-compile **JavaScript** dan **Tailwind CSS** untuk digunakan oleh Laravel.

---

## ✅ Selesai

Sekarang kamu bisa mengakses project di browser dan mulai mengembangkan 🎉

---

## ⚠️ Catatan

-   Semua halaman di-render oleh Laravel (Blade).
-   **Vite** hanya digunakan untuk kompilasi asset (JavaScript, CSS).
-   Pastikan **PHP ≥ 8.1**, **Composer**, dan **Node.js ≥ 18** sudah terpasang.
-   Jika menggunakan SQLite, buat file `database/database.sqlite` sebelum menjalankan migrasi:

```bash
mkdir -p database && touch database/database.sqlite
php artisan migrate
```
