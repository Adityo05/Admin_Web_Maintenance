# Setup Instructions - Admin Web Laravel

## 📋 Prerequisites

-   PHP >= 8.2
-   Composer
-   PostgreSQL (atau koneksi ke Supabase)
-   Node.js & NPM (opsional, untuk asset compilation)

## 🚀 Installation Steps

### 1. Install Dependencies

```bash
cd D:\admin_web_laravel
composer install
```

### 2. Setup Environment

```bash
cp .env.example .env
php artisan key:generate
```

### 3. Configure Database

Edit file `.env` dan set koneksi database ke Supabase PostgreSQL:

```env
DB_CONNECTION=pgsql
DB_HOST=aws-1-ap-southeast-2.pooler.supabase.com
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres.dxzkxvczjdviuvmgwsft
DB_PASSWORD=your_password_here
```

**Catatan:** Ganti `your_password_here` dengan password Supabase Anda yang sebenarnya.

### 4. Create Storage Link

```bash
php artisan storage:link
```

Ini akan membuat symbolic link dari `public/storage` ke `storage/app/public` untuk akses file upload.

### 5. Start Development Server

```bash
php artisan serve
```

Aplikasi akan berjalan di `http://localhost:8000`

## 📁 Project Structure

```
admin_web_laravel/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/LoginController.php
│   │   │   ├── DashboardController.php
│   │   │   ├── AssetController.php
│   │   │   ├── KaryawanController.php
│   │   │   ├── MaintenanceScheduleController.php
│   │   │   └── CheckSheetScheduleController.php
│   │   └── Middleware/
│   │       └── AuthMiddleware.php
│   └── Models/
│       ├── Asset.php
│       ├── Karyawan.php
│       ├── BagianMesin.php
│       ├── KomponenAsset.php
│       ├── MaintenanceSchedule.php
│       └── CheckSheetSchedule.php
├── resources/
│   └── views/
│       ├── layouts/
│       │   ├── app.blade.php
│       │   ├── header.blade.php
│       │   └── sidebar.blade.php
│       ├── auth/
│       │   └── login.blade.php
│       ├── dashboard/
│       │   └── index.blade.php
│       ├── assets/
│       │   ├── index.blade.php
│       │   └── create.blade.php
│       └── ...
├── public/
│   ├── css/
│   │   └── app.css
│   └── js/
│       └── app.js
└── routes/
    └── web.php
```

## 🔐 Authentication

Aplikasi menggunakan session-based authentication. User harus login dengan email dan password yang ada di tabel `karyawan` di Supabase.

**Role yang bisa login:**

-   Superadmin
-   Manajer
-   Admin
-   KASIE Teknisi

User harus memiliki akses ke aplikasi dengan kode `MT` (Maintenance) di tabel `karyawan_aplikasi`.

## 📝 Features

### ✅ Completed

-   Login/Authentication
-   Dashboard dengan statistik
-   Data Assets/Mesin (CRUD)
    -   List dengan search, filter, sort
    -   Create dengan form bagian & komponen
    -   Edit
    -   Delete dengan cascading

### ⏳ TODO

-   Daftar Karyawan (CRUD)
-   Maintenance Schedule (CRUD)
-   Cek Sheet Schedule (CRUD)
-   Edit form untuk Assets
-   Image upload handling
-   Form validation improvements

## 🎨 Styling

Aplikasi menggunakan CSS murni (tidak ada framework UI) dengan styling yang sama persis dengan aplikasi Flutter web admin.

Warna utama:

-   Primary: `#0A9C5D` (Hijau)
-   Secondary: `#022415` (Dark Green)
-   Text: `#022415`
-   Background: `#F5F5F5`

## 🔧 Troubleshooting

### Database Connection Error

-   Pastikan kredensial database di `.env` benar
-   Pastikan Supabase database dapat diakses dari IP Anda
-   Cek firewall settings

### Storage Link Error

-   Pastikan folder `storage/app/public` ada
-   Jalankan `php artisan storage:link` lagi

### Session Error

-   Pastikan `storage/framework/sessions` writable
-   Cek `APP_KEY` di `.env` sudah di-generate

## 📚 Next Steps

1. Implementasi fitur Karyawan CRUD
2. Implementasi fitur Maintenance Schedule CRUD
3. Implementasi fitur Cek Sheet Schedule CRUD
4. Tambahkan form edit untuk Assets
5. Improve form validation
6. Add image preview di form
7. Add pagination untuk table
8. Add export functionality
