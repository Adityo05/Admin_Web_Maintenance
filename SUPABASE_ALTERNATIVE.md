# Alternatif Koneksi Supabase untuk Laravel

## 📋 Overview

Saat ini Laravel menggunakan **koneksi PostgreSQL langsung** ke Supabase. Ada alternatif lain yang lebih mirip dengan cara Flutter menggunakan Supabase Client SDK.

## 🔄 Perbandingan Metode

### 1. **PostgreSQL Direct Connection** (Saat Ini)

```php
// Menggunakan Eloquent ORM dengan koneksi PostgreSQL
DB::connection('pgsql')->table('assets')->get();
```

**Kelebihan:**

-   ✅ Menggunakan Eloquent ORM (familiar untuk Laravel)
-   ✅ Type safety dengan model
-   ✅ Query builder yang powerful
-   ✅ Relationship management otomatis

**Kekurangan:**

-   ❌ Tidak bisa menggunakan fitur Supabase Auth langsung
-   ❌ Tidak bisa menggunakan Supabase Storage
-   ❌ Tidak bisa menggunakan Supabase Realtime
-   ❌ Perlu manage koneksi database manual

### 2. **Supabase PHP Client** (Alternatif - Mirip Flutter)

```php
// Menggunakan Supabase Client SDK
$supabase = SupabaseService::getInstance();
$assets = $supabase->from('assets')->select('*')->execute();
```

**Kelebihan:**

-   ✅ Mirip dengan cara Flutter (konsisten)
-   ✅ Bisa menggunakan Supabase Auth
-   ✅ Bisa menggunakan Supabase Storage
-   ✅ Bisa menggunakan Supabase Realtime
-   ✅ REST API (lebih fleksibel)
-   ✅ Auto-refresh token

**Kekurangan:**

-   ❌ Tidak bisa menggunakan Eloquent ORM
-   ❌ Perlu manual handle relationships
-   ❌ Response format berbeda (array, bukan model)

## 🚀 Implementasi Supabase PHP Client

### Step 1: Install Package

```bash
composer require supabase/supabase-php
```

### Step 2: Setup Config

File: `config/supabase.php` (sudah dibuat)

```php
return [
    'url' => env('SUPABASE_URL'),
    'key' => env('SUPABASE_ANON_KEY'),
];
```

File: `.env`

```env
SUPABASE_URL=https://dxzkxvczjdviuvmgwsft.supabase.co
SUPABASE_ANON_KEY=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...
```

### Step 3: Gunakan Service

```php
use App\Services\SupabaseService;

// Get instance
$supabase = SupabaseService::getInstance();

// Query data
$assets = $supabase->from('assets')
    ->select('*, bg_mesin(*, komponen_assets(*))')
    ->execute();

// Insert data
$newAsset = $supabase->from('assets')
    ->insert([
        'nama_assets' => 'Mesin Baru',
        'jenis_assets' => 'Mesin Produksi',
    ])
    ->execute();

// Update data
$supabase->from('assets')
    ->update(['nama_assets' => 'Updated Name'])
    ->eq('id', $assetId)
    ->execute();

// Delete data
$supabase->from('assets')
    ->delete()
    ->eq('id', $assetId)
    ->execute();
```

## 🔀 Hybrid Approach (Recommended)

Anda bisa menggunakan **kedua metode** secara bersamaan:

1. **PostgreSQL Direct** untuk:

    - CRUD operations yang kompleks
    - Relationships yang banyak
    - Query yang memerlukan Eloquent

2. **Supabase Client** untuk:
    - Authentication (jika ingin pakai Supabase Auth)
    - File Storage (upload ke Supabase Storage)
    - Realtime subscriptions
    - Edge Functions calls

### Contoh Hybrid:

```php
// Di Controller
class AssetController extends Controller
{
    // Untuk CRUD kompleks, pakai Eloquent
    public function index()
    {
        $assets = Asset::with(['bagianMesin.komponenAssets'])->get();
        return view('assets.index', compact('assets'));
    }

    // Untuk upload file, pakai Supabase Storage
    public function uploadImage(Request $request)
    {
        $supabase = SupabaseService::getInstance();
        $file = $request->file('image');

        $path = $supabase->storage()
            ->from('assets')
            ->upload($file->getClientOriginalName(), $file->getContent());

        return response()->json(['path' => $path]);
    }
}
```

## 📝 File yang Sudah Dibuat

1. ✅ `config/supabase.php` - Konfigurasi Supabase
2. ✅ `app/Services/SupabaseService.php` - Service singleton (mirip Flutter)
3. ✅ `app/Repositories/SupabaseAssetRepository.php` - Contoh repository

## 🔧 Cara Menggunakan

### Option 1: Tetap Pakai PostgreSQL (Current)

```php
// Tetap menggunakan Eloquent seperti sekarang
$asset = Asset::find($id);
```

### Option 2: Pakai Supabase Client

```php
use App\Services\SupabaseService;

$supabase = SupabaseService::getInstance();
$asset = $supabase->from('assets')
    ->select('*')
    ->eq('id', $id)
    ->single()
    ->execute();
```

### Option 3: Hybrid (Recommended)

```php
// CRUD: Pakai Eloquent
$assets = Asset::all();

// Storage: Pakai Supabase
$supabase = SupabaseService::getInstance();
$url = $supabase->storage()->from('bucket')->getPublicUrl('file.jpg');
```

## 🎯 Rekomendasi

Untuk project ini, saya rekomendasikan:

1. **Tetap pakai PostgreSQL Direct** untuk:

    - Semua CRUD operations
    - Complex queries
    - Relationships

2. **Tambahkan Supabase Client** untuk:

    - File upload ke Supabase Storage (jika perlu)
    - Realtime updates (jika perlu)
    - Edge Functions (jika perlu)

3. **Untuk Authentication**:
    - Tetap pakai Laravel Auth (sudah bagus)
    - Atau migrasi ke Supabase Auth jika ingin konsisten dengan Flutter

## 📚 Dokumentasi

-   Supabase PHP Client: https://github.com/supabase/supabase-php
-   Supabase REST API: https://supabase.com/docs/reference/javascript/introduction
-   Laravel Eloquent: https://laravel.com/docs/eloquent

## ⚠️ Catatan

-   Supabase PHP Client masih dalam development aktif
-   Untuk production, pastikan test thoroughly
-   PostgreSQL Direct lebih mature dan stable untuk Laravel
-   Pilih metode sesuai kebutuhan project
