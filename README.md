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

## 📄 License

Proprietary - Internal Use Only
