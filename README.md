# MyGallery

Panduan ini menjelaskan cara setup ulang proyek MyGallery di komputer lain.

## Prasyarat

Pastikan lingkungan pengembangan sudah terpasang:

- PHP 8.1 atau lebih tinggi
- Composer
- Node.js dan npm/yarn
- Database MySQL atau MariaDB
- Server lokal seperti Laragon / XAMPP / Valet

## Clone Repository

1. Clone repo ke folder lokal:

```bash
git clone <URL_REPO> MyGallery
cd MyGallery
```

2. Copy file konfigurasi environment:

```bash
copy .env.example .env
```

## Install Dependensi

1. Install dependensi PHP:

```bash
composer install
```

2. Install dependensi JavaScript:

```bash
npm install
```

atau jika pakai yarn:

```bash
yarn install
```

## Konfigurasi Environment

Buka file `.env` dan sesuaikan:

- `APP_NAME=MyGallery`
- `APP_URL=http://localhost`
- `DB_CONNECTION=mysql`
- `DB_HOST=127.0.0.1`
- `DB_PORT=3306`
- `DB_DATABASE=nama_database_anda`
- `DB_USERNAME=user_database`
- `DB_PASSWORD=password_database`


## Generate Application Key

```bash
php artisan key:generate
```

## Migrasi dan Seed Database

Jalankan migrasi database:

```bash
php artisan migrate
```

Jika ingin mengisi data awal (seed):

```bash
php artisan db:seed
```

> Jika menggunakan database kosong, pastikan database sudah dibuat terlebih dahulu melalui phpMyAdmin atau command line.

## Storage Link

Buat symbolic link agar file foto bisa diakses dari `public/storage`:

```bash
php artisan storage:link
```

## Build Aset Frontend

Untuk mengompilasi file CSS/JS:

```bash
npm run build
```

Untuk mode development dengan hot reload (opsional):

```bash
npm run dev
```

## Jalankan Server Lokal

```bash
php artisan serve
```

Lalu buka di browser:

```text
http://127.0.0.1:8000
```

## Hal yang Perlu Dicek Jika Error

- Pastikan file `.env` sudah benar
- Pastikan database berhasil terhubung
- Pastikan folder `storage` dan `bootstrap/cache` memiliki izin tulis
- Jalankan ulang `composer install` jika ada dependensi hilang

## Catatan

- Gunakan versi PHP yang sesuai dengan pengaturan `composer.json`
- Jika menggunakan Laragon, pastikan `Document Root` diarahkan ke folder `MyGallery/public`
- Jika ada masalah upload foto, periksa konfigurasi `FILESYSTEM_DRIVER` di `.env`

---

Jika butuh bantuan lebih lanjut, tambahkan dokumentasi atau langkah instalasi khusus sesuai konfigurasi server yang digunakan.