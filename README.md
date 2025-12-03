# Admin Web Laravel - Monitoring Maintenance

Aplikasi web admin untuk sistem monitoring maintenance yang di-migrate dari Flutter Web ke Laravel.

## 🚀 Quick Start

### Prerequisites

-   PHP >= 8.2
-   Composer
-   Node.js & NPM
-   PostgreSQL (atau koneksi ke Supabase)

### Installation

1. **Install Dependencies**

    ```bash
    composer install
    npm install
    ```

2. **Setup Environment**

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

3. **Configure Database**
   Edit `.env` file dan set koneksi database:

    ```env
    DB_CONNECTION=pgsql
    DB_HOST=your_supabase_host
    DB_PORT=5432
    DB_DATABASE=postgres
    DB_USERNAME=your_username
    DB_PASSWORD=your_password
    ```

4. **Run Migrations** (jika diperlukan)

    ```bash
    php artisan migrate
    ```

5. **Start Development Server**

    ```bash
    php artisan serve
    ```

    Aplikasi akan berjalan di `http://localhost:8000`

## 📁 Project Structure

```
app/
├── Http/
│   ├── Controllers/     # Application controllers
│   ├── Middleware/      # Custom middleware
│   └── Requests/        # Form request validation
├── Models/              # Eloquent models
└── Services/            # Business logic services

resources/
├── views/               # Blade templates
│   ├── layouts/         # Layout files
│   ├── auth/            # Authentication views
│   ├── dashboard/       # Dashboard views
│   ├── assets/          # Asset management views
│   └── ...
└── assets/              # CSS, JS, images

routes/
├── web.php              # Web routes
└── api.php              # API routes
```

## 🔐 Authentication

Aplikasi menggunakan Laravel Authentication dengan session-based auth. User roles:

-   Superadmin
-   Manajer
-   Admin
-   KASIE Teknisi

## 📝 Features

-   ✅ Login/Authentication
-   ✅ Dashboard dengan statistik
-   ✅ Data Assets/Mesin (CRUD)
-   ✅ Daftar Karyawan
-   ✅ Maintenance Schedule
-   ✅ Cek Sheet Schedule
-   ✅ Kalender Pengecekan

## 📚 Documentation

Lihat [MIGRATION_GUIDE.md](./MIGRATION_GUIDE.md) untuk panduan lengkap migrasi dari Flutter.

## 🛠️ Development

### Menjalankan Tests

```bash
php artisan test
```

### Code Style

```bash
./vendor/bin/pint
```

## 🐛 Troubleshooting

### Error: "could not find driver (Connection: pgsql)"

Error ini muncul ketika PHP tidak memiliki PostgreSQL PDO driver yang diaktifkan/diinstall.

#### **Windows:**

1. **Cek PHP yang digunakan:**
   ```powershell
   php --ini
   php -m
   php -r "print_r(PDO::getAvailableDrivers());"
   ```

2. **Edit php.ini:**
   - Buka file `php.ini` yang ditunjukkan oleh `php --ini`
   - Cari baris berikut dan hapus tanda `;` di depannya:
     ```ini
     ;extension=pdo_pgsql
     ;extension=pgsql
     ```
   - Menjadi:
     ```ini
     extension=pdo_pgsql
     extension=pgsql
     ```

3. **Restart Web Server:**
   - Jika menggunakan Apache: Restart Apache service
   - Jika menggunakan `php artisan serve`: Stop (Ctrl+C) dan jalankan ulang
   - Jika menggunakan Laragon/XAMPP: Restart dari control panel

4. **Verifikasi:**
   ```powershell
   php -m | Select-String pgsql
   php -r "print_r(PDO::getAvailableDrivers());"
   ```
   Harusnya muncul `pgsql` dalam daftar driver.

#### **Linux (Ubuntu/Debian):**

1. **Install PHP PostgreSQL Extension:**
   ```bash
   sudo apt update
   sudo apt install php-pgsql
   # Atau untuk versi spesifik:
   sudo apt install php8.2-pgsql
   ```

2. **Restart Services:**
   ```bash
   sudo systemctl restart php8.2-fpm
   sudo systemctl restart nginx
   # Atau jika pakai Apache:
   sudo systemctl restart apache2
   ```

3. **Verifikasi:**
   ```bash
   php -m | grep pgsql
   php -r "print_r(PDO::getAvailableDrivers());"
   ```

#### **macOS (Homebrew):**

1. **Install Extension:**
   ```bash
   brew install php@8.2
   pecl install pgsql
   ```

2. **Restart PHP:**
   ```bash
   brew services restart php@8.2
   ```

#### **Test Koneksi Database:**

Setelah mengaktifkan driver, test koneksi:

```bash
# Test driver tersedia
php -r "print_r(PDO::getAvailableDrivers());"

# Test koneksi Laravel
php artisan tinker --execute="DB::select('select 1 as ok')"

# Cek status migrasi
php artisan migrate:status
```

#### **Catatan Penting:**

- **Perbedaan PHP CLI vs Web Server:** Kadang PHP yang digunakan oleh terminal berbeda dengan yang digunakan web server. Pastikan kedua `php.ini` sudah diaktifkan extension-nya.
- **Cek .env:** Pastikan konfigurasi database di `.env` sudah benar:
  ```env
  DB_CONNECTION=pgsql
  DB_HOST=127.0.0.1  # atau host Supabase
  DB_PORT=5432
  DB_DATABASE=nama_database
  DB_USERNAME=username
  DB_PASSWORD=password
  ```
- **Logs:** Jika masih error, cek log di:
  - Windows: Event Viewer atau Apache error log
  - Linux: `/var/log/php8.2-fpm.log` atau `/var/log/apache2/error.log`

## 📄 License

Proprietary - Internal Use Only
