# Panduan Migrasi Admin Web dari Flutter ke Laravel

## 📋 Daftar Isi

1. [Overview](#overview)
2. [Struktur Project](#struktur-project)
3. [Langkah-langkah Migrasi](#langkah-langkah-migrasi)
4. [Konfigurasi Database](#konfigurasi-database)
5. [Mapping Fitur](#mapping-fitur)

## Overview

Project ini adalah migrasi dari aplikasi admin web Flutter (`apps/admin_web`) ke Laravel. Aplikasi ini memiliki fitur:

-   Login/Authentication
-   Dashboard/Beranda
-   Data Assets/Mesin (CRUD)
-   Daftar Karyawan
-   Maintenance Schedule
-   Cek Sheet Schedule
-   Kalender Pengecekan

## Struktur Project

```
admin_web_laravel/
├── app/
│   ├── Http/
│   │   ├── Controllers/     # Controllers untuk semua fitur
│   │   ├── Middleware/       # Authentication middleware
│   │   └── Requests/         # Form validation
│   ├── Models/              # Eloquent models
│   └── Services/            # Business logic services
├── database/
│   ├── migrations/          # Database migrations
│   └── seeders/             # Database seeders
├── resources/
│   ├── views/               # Blade templates
│   └── assets/              # CSS, JS, images
└── routes/
    ├── web.php              # Web routes
    └── api.php              # API routes (jika diperlukan)
```

## Langkah-langkah Migrasi

### 1. Setup Database Connection

Edit file `.env` dan konfigurasi koneksi ke Supabase PostgreSQL:

```env
DB_CONNECTION=pgsql
DB_HOST=aws-1-ap-southeast-2.pooler.supabase.com
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres.dxzkxvczjdviuvmgwsft
DB_PASSWORD=your_password_here
```

### 2. Install Package yang Diperlukan

```bash
# Install Supabase PHP Client (jika ingin tetap menggunakan Supabase)
composer require supabase/supabase-php

# Install UI Framework (contoh: Laravel UI atau Breeze)
composer require laravel/breeze --dev
php artisan breeze:install blade
npm install && npm run build
```

### 3. Membuat Models

Berdasarkan schema database yang ada, buat Eloquent models:

```bash
php artisan make:model Asset
php artisan make:model Karyawan
php artisan make:model MaintenanceSchedule
php artisan make:model CheckSheetSchedule
php artisan make:model BagianMesin
php artisan make:model KomponenAsset
```

### 4. Membuat Controllers

```bash
php artisan make:controller Auth/LoginController
php artisan make:controller DashboardController
php artisan make:controller AssetController --resource
php artisan make:controller KaryawanController --resource
php artisan make:controller MaintenanceScheduleController --resource
php artisan make:controller CheckSheetScheduleController --resource
```

### 5. Membuat Views (Blade Templates)

Struktur views yang perlu dibuat:

-   `layouts/app.blade.php` - Layout utama
-   `auth/login.blade.php` - Halaman login
-   `dashboard/index.blade.php` - Dashboard/Beranda
-   `assets/index.blade.php` - Daftar Assets
-   `assets/create.blade.php` - Tambah Asset
-   `assets/edit.blade.php` - Edit Asset
-   `karyawan/index.blade.php` - Daftar Karyawan
-   `maintenance-schedule/index.blade.php` - Maintenance Schedule
-   `check-sheet-schedule/index.blade.php` - Cek Sheet Schedule

### 6. Setup Routes

Edit `routes/web.php`:

```php
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

Route::middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('assets', AssetController::class);
    Route::resource('karyawan', KaryawanController::class);
    Route::resource('maintenance-schedule', MaintenanceScheduleController::class);
    Route::resource('check-sheet-schedule', CheckSheetScheduleController::class);
});
```

## Konfigurasi Database

### Option 1: Menggunakan Supabase PostgreSQL Langsung

Laravel dapat terhubung langsung ke Supabase PostgreSQL menggunakan driver `pgsql` standar.

### Option 2: Menggunakan Supabase Client

Jika ingin tetap menggunakan Supabase client library untuk fitur-fitur khusus Supabase (seperti Realtime, Storage, dll):

```php
use Supabase\Client;

$supabase = new Client(
    env('SUPABASE_URL'),
    env('SUPABASE_KEY')
);
```

## Mapping Fitur

### Flutter → Laravel Mapping

| Flutter Component      | Laravel Equivalent                   |
| ---------------------- | ------------------------------------ |
| `AuthProvider`         | Laravel Auth (Session/Token)         |
| `AssetController`      | `AssetController` (Laravel)          |
| `KaryawanController`   | `KaryawanController` (Laravel)       |
| `CheckSheetController` | `CheckSheetScheduleController`       |
| `DashboardController`  | `DashboardController`                |
| `SupabaseService`      | Eloquent Models + Database           |
| `Riverpod State`       | Laravel Session/Cache                |
| Flutter Widgets        | Blade Templates + Alpine.js/Livewire |

### Halaman yang Perlu Dimigrasi

1. **Login Page** (`login_page.dart`)

    - Form email & password
    - Validasi
    - Redirect berdasarkan role

2. **Dashboard** (`dashboard_admin.dart` + `beranda_page.dart`)

    - Statistik cards
    - Charts (jika ada)
    - Quick actions

3. **Data Assets** (`data_assets_page.dart`)

    - Table dengan search & filter
    - CRUD operations
    - Image upload

4. **Daftar Karyawan** (`daftar_karyawan_page.dart`)

    - Table karyawan
    - CRUD operations

5. **Maintenance Schedule** (`maintenance_schedule_page.dart`)

    - List jadwal maintenance
    - Form tambah/edit

6. **Cek Sheet Schedule** (`cek_sheet_schedule_page.dart`)
    - List jadwal cek sheet
    - Form tambah/edit

## Next Steps

1. ✅ Project Laravel sudah dibuat
2. ⏳ Setup database connection
3. ⏳ Buat Models berdasarkan schema
4. ⏳ Buat Controllers
5. ⏳ Buat Views/Blade templates
6. ⏳ Setup Authentication
7. ⏳ Migrasi logika bisnis
8. ⏳ Testing

## Catatan Penting

-   Database schema sudah ada di Supabase, jadi tidak perlu membuat migration untuk tabel yang sudah ada
-   Gunakan Eloquent untuk query database
-   Untuk file upload, gunakan Laravel Storage
-   Untuk UI, bisa menggunakan Tailwind CSS atau Bootstrap yang sudah terintegrasi dengan Laravel Breeze
