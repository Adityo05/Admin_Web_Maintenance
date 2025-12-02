# Quick Fix - Database Connection

## Status

✅ Extension PostgreSQL sudah aktif  
✅ Koneksi database bisa dilakukan  
❌ Password authentication gagal

## Solusi

### 1. Update Password di .env

Buka file `.env` di folder `D:\admin_web_laravel` dan ganti password:

```env
DB_PASSWORD=your_password_here
```

Dengan password Supabase Anda yang sebenarnya.

**Cara mendapatkan password Supabase:**

1. Login ke Supabase Dashboard
2. Go to Project Settings > Database
3. Copy password dari "Database password" atau reset password jika perlu

### 2. Clear Cache

Setelah mengupdate password, jalankan:

```bash
cd D:\admin_web_laravel
php artisan config:clear
php artisan cache:clear
```

### 3. Test Koneksi

```bash
php artisan serve
```

Kemudian akses `http://localhost:8000`

## Catatan

-   Pastikan password di `.env` tidak ada spasi di awal/akhir
-   Jika menggunakan connection pooling, pastikan menggunakan port yang benar (5432 untuk pooler)
-   Jika masih error, coba gunakan direct connection (bukan pooler)
