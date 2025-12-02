# Alternatif Koneksi Supabase untuk Laravel

## 📋 Overview

Ada **3 alternatif** untuk menghubungkan Laravel ke Supabase, mirip dengan cara Flutter menggunakan Supabase Client SDK.

## 🔄 Perbandingan Metode

### 1. **PostgreSQL Direct Connection** (Saat Ini - Recommended ✅)

```php
// Menggunakan Eloquent ORM
use App\Models\Asset;

$assets = Asset::with(['bagianMesin.komponenAssets'])->get();
```

**Kelebihan:**

-   ✅ Type safety dengan Model
-   ✅ Auto relationships
-   ✅ Query builder powerful
-   ✅ Validation built-in
-   ✅ Mature & stable

**Kekurangan:**

-   ❌ Tidak bisa pakai Supabase Auth langsung
-   ❌ Tidak bisa pakai Supabase Storage
-   ❌ Tidak bisa pakai Realtime

### 2. **PostgREST REST API** (Alternatif - Mirip Flutter ✅)

```php
// Menggunakan PostgREST Service (sudah dibuat)
use App\Services\SupabaseService;

$supabase = SupabaseService::getInstance();
$response = $supabase->from('assets')
    ->select('*, bg_mesin(*, komponen_assets(*))')
    ->execute();
$assets = $response->data;
```

**Kelebihan:**

-   ✅ Mirip dengan Flutter (konsisten)
-   ✅ REST API (fleksibel)
-   ✅ Bisa extend untuk Auth/Storage
-   ✅ Tidak perlu install package tambahan

**Kekurangan:**

-   ❌ Manual handle relationships
-   ❌ Response format berbeda (array)

### 3. **Supabase PHP Client Package** (Alternatif - Butuh Fix)

```bash
composer require supabase/supabase-php
```

**Status:** Ada dependency conflict, perlu fix manual

## 🚀 Implementasi PostgREST (Recommended Alternative)

### File yang Sudah Dibuat:

1. ✅ `config/supabase.php` - Konfigurasi
2. ✅ `app/Services/PostgrestService.php` - Service utama
3. ✅ `app/Services/SupabaseService.php` - Wrapper singleton
4. ✅ `app/Repositories/SupabaseAssetRepository.php` - Contoh repository

### Cara Menggunakan:

#### 1. Setup Config

File: `.env`

```env
SUPABASE_URL=https://dxzkxvczjdviuvmgwsft.supabase.co
SUPABASE_ANON_KEY=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...
```

#### 2. Query Data

```php
use App\Services\SupabaseService;

$supabase = SupabaseService::getInstance();

// Get all assets
$response = $supabase->from('assets')
    ->select('*')
    ->execute();
$assets = $response->data;

// Get dengan filter
$response = $supabase->from('assets')
    ->select('*, bg_mesin(*, komponen_assets(*))')
    ->eq('status', 'Aktif')
    ->order('created_at', 'desc')
    ->limit(10)
    ->execute();

// Get single
$response = $supabase->from('assets')
    ->select('*')
    ->eq('id', $id)
    ->single()
    ->execute();
$asset = $response->data;
```

#### 3. Insert Data

```php
$response = $supabase->from('assets')
    ->insert([
        'id' => Str::uuid(),
        'nama_assets' => 'Mesin Baru',
        'jenis_assets' => 'Mesin Produksi',
        'status' => 'Aktif',
    ])
    ->execute();
$newAsset = $response->data[0];
```

#### 4. Update Data

```php
$supabase->from('assets')
    ->update(['nama_assets' => 'Updated Name'])
    ->eq('id', $assetId)
    ->execute();
```

#### 5. Delete Data

```php
$supabase->from('assets')
    ->delete()
    ->eq('id', $assetId)
    ->execute();
```

## 🔀 Hybrid Approach (Best Practice)

Gunakan **kedua metode** sesuai kebutuhan:

```php
class AssetController extends Controller
{
    // CRUD: Pakai Eloquent (PostgreSQL Direct)
    public function index()
    {
        $assets = Asset::with(['bagianMesin.komponenAssets'])->get();
        return view('assets.index', compact('assets'));
    }

    // Complex Query: Pakai PostgREST
    public function search(Request $request)
    {
        $supabase = SupabaseService::getInstance();
        $response = $supabase->from('assets')
            ->select('*, bg_mesin(*), komponen_assets(*)')
            ->ilike('nama_assets', $request->search)
            ->eq('status', 'Aktif')
            ->order('created_at', 'desc')
            ->execute();

        return response()->json($response->data);
    }
}
```

## 📝 Contoh Repository Pattern

```php
// app/Repositories/SupabaseAssetRepository.php
class SupabaseAssetRepository
{
    private $supabase;

    public function __construct()
    {
        $this->supabase = SupabaseService::getInstance();
    }

    public function getAllAssets(): array
    {
        $response = $this->supabase->from('assets')
            ->select('*, bg_mesin(*, komponen_assets(*))')
            ->execute();

        return $response->data ?? [];
    }
}
```

## 🎯 Rekomendasi

### Untuk Project Ini:

1. **Tetap Pakai PostgreSQL Direct** untuk:

    - ✅ Semua CRUD operations
    - ✅ Complex queries
    - ✅ Relationships

2. **Tambahkan PostgREST** untuk:

    - 📁 File upload (jika perlu)
    - 🔍 Advanced search
    - 📊 Complex filtering

3. **Jangan Pakai Supabase PHP Package** (ada conflict)

## ✅ Kesimpulan

**PostgreSQL Direct** tetap yang terbaik untuk Laravel karena:

-   Mature & stable
-   Type safety
-   Auto relationships
-   Built-in validation

**PostgREST Service** sudah dibuat sebagai alternatif jika:

-   Ingin konsistensi dengan Flutter
-   Perlu REST API approach
-   Ingin extend ke Auth/Storage nanti

**File sudah siap digunakan!** Tinggal tambahkan ke `.env`:

```env
SUPABASE_URL=https://dxzkxvczjdviuvmgwsft.supabase.co
SUPABASE_ANON_KEY=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...
```
