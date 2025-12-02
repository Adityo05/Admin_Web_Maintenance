# Fix Database Connection Error

## Error yang Terjadi

```
could not find driver (Connection: pgsql, SQL: select * from "sessions" where "id" = ...)
```

## Solusi

### 1. Pastikan Extension PostgreSQL Aktif

Extension sudah terdeteksi dan aktif. Untuk memastikan, jalankan:

```bash
php -m | findstr pgsql
```

Harus muncul:

-   `pdo_pgsql`
-   `pgsql`

### 2. Update File .env

File `.env` sudah dibuat dan dikonfigurasi dengan:

```env
DB_CONNECTION=pgsql
DB_HOST=aws-1-ap-southeast-2.pooler.supabase.com
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres.dxzkxvczjdviuvmgwsft
DB_PASSWORD=your_password_here
```

**⚠️ PENTING:** Ganti `your_password_here` dengan password Supabase Anda yang sebenarnya!

### 3. Ubah Session Driver ke File

Session driver sudah diubah ke `file` untuk menghindari error saat koneksi database belum siap.

### 4. Test Koneksi Database

Setelah mengupdate password di `.env`, test koneksi dengan:

```bash
php artisan tinker
```

Kemudian jalankan:

```php
DB::connection()->getPdo();
```

Jika berhasil, akan muncul informasi PDO connection.

### 5. Clear Cache

Jalankan perintah berikut untuk clear cache:

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

## Troubleshooting

### Jika masih error "could not find driver":

1. **Cek php.ini**

    - Buka file: `C:\laragon\bin\php\php-8.3.16-Win32-vs16-x64\php.ini`
    - Pastikan baris berikut tidak di-comment (tidak ada `;` di depan):
        ```ini
        extension=pdo_pgsql
        extension=pgsql
        ```

2. **Restart Web Server**

    - Jika menggunakan Laragon, restart Laragon
    - Atau restart Apache/Nginx

3. **Install Extension Manual (jika perlu)**
    - Download DLL dari: https://pecl.php.net/package/pdo_pgsql
    - Copy ke folder `ext` di PHP
    - Aktifkan di php.ini

### Jika error "password authentication failed":

-   Pastikan password di `.env` benar
-   Cek apakah IP Anda sudah di-whitelist di Supabase (jika menggunakan IP restriction)

### Jika error "could not connect to server":

-   Cek koneksi internet
-   Pastikan host Supabase benar
-   Cek firewall settings

## Langkah Selanjutnya

1. Update password di `.env` dengan password Supabase yang benar
2. Clear cache: `php artisan config:clear`
3. Test aplikasi: `php artisan serve`
4. Akses: `http://localhost:8000`
