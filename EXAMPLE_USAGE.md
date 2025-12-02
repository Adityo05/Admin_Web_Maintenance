# Contoh Penggunaan Supabase Client di Laravel

## 🔄 Perbandingan: PostgreSQL vs Supabase Client

### Metode 1: PostgreSQL Direct (Saat Ini - Recommended)

```php
// Menggunakan Eloquent ORM
use App\Models\Asset;

// Get all assets
$assets = Asset::with(['bagianMesin.komponenAssets'])->get();

// Create asset
$asset = Asset::create([
    'nama_assets' => 'Mesin Baru',
    'jenis_assets' => 'Mesin Produksi',
]);

// Update asset
$asset->update(['nama_assets' => 'Updated Name']);

// Delete asset
$asset->delete();
```

**Kelebihan:**

-   ✅ Type safety dengan Model
-   ✅ Auto relationships
-   ✅ Query builder powerful
-   ✅ Validation built-in
-   ✅ Mass assignment protection

### Metode 2: Supabase Client (Alternatif - Mirip Flutter)

```php
// Menggunakan Supabase Client
use App\Services\SupabaseService;

$supabase = SupabaseService::getInstance();

// Get all assets dengan relasi
$response = $supabase->from('assets')
    ->select('*, bg_mesin(*, komponen_assets(*))')
    ->execute();
$assets = $response->data;

// Create asset
$response = $supabase->from('assets')
    ->insert([
        'id' => Str::uuid(),
        'nama_assets' => 'Mesin Baru',
        'jenis_assets' => 'Mesin Produksi',
        'status' => 'Aktif',
    ])
    ->execute();
$newAsset = $response->data[0];

// Update asset
$response = $supabase->from('assets')
    ->update(['nama_assets' => 'Updated Name'])
    ->eq('id', $assetId)
    ->execute();

// Delete asset
$supabase->from('assets')
    ->delete()
    ->eq('id', $assetId)
    ->execute();
```

**Kelebihan:**

-   ✅ Mirip dengan Flutter (konsisten)
-   ✅ Bisa pakai Supabase Auth
-   ✅ Bisa pakai Supabase Storage
-   ✅ Bisa pakai Realtime

## 🎯 Rekomendasi untuk Project Ini

### Gunakan PostgreSQL Direct untuk:

1. ✅ **CRUD Operations** - Lebih mudah dengan Eloquent
2. ✅ **Complex Queries** - Query builder lebih powerful
3. ✅ **Relationships** - Auto-load relationships
4. ✅ **Validation** - Built-in Laravel validation

### Gunakan Supabase Client untuk:

1. 📁 **File Upload** - Upload ke Supabase Storage
2. 🔔 **Realtime** - Real-time updates (jika perlu)
3. 🔐 **Auth** - Jika ingin pakai Supabase Auth (optional)

## 📝 Contoh Hybrid Usage

```php
namespace App\Http\Controllers;

use App\Models\Asset; // Eloquent untuk CRUD
use App\Services\SupabaseService; // Supabase untuk Storage

class AssetController extends Controller
{
    // CRUD: Pakai Eloquent
    public function index()
    {
        $assets = Asset::with(['bagianMesin.komponenAssets'])->get();
        return view('assets.index', compact('assets'));
    }

    // Upload: Pakai Supabase Storage
    public function uploadImage(Request $request)
    {
        $file = $request->file('image');

        $supabase = SupabaseService::getInstance();
        $path = $supabase->storage()
            ->from('assets')
            ->upload(
                $file->getClientOriginalName(),
                file_get_contents($file->getRealPath())
            );

        // Simpan path ke database pakai Eloquent
        $asset = Asset::find($request->asset_id);
        $asset->update(['foto' => $path]);

        return response()->json(['path' => $path]);
    }
}
```

## 🔧 Setup Supabase Client (Optional)

Jika ingin menggunakan Supabase Client:

1. **Install package:**

    ```bash
    composer require supabase/supabase-php
    ```

2. **Update .env:**

    ```env
    SUPABASE_URL=https://dxzkxvczjdviuvmgwsft.supabase.co
    SUPABASE_ANON_KEY=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...
    ```

3. **Gunakan service:**

    ```php
    use App\Services\SupabaseService;

    $supabase = SupabaseService::getInstance();
    $data = $supabase->from('table')->select('*')->execute();
    ```

## ✅ Kesimpulan

**Untuk project ini, saya rekomendasikan tetap menggunakan PostgreSQL Direct (metode saat ini)** karena:

1. ✅ Lebih mature dan stable
2. ✅ Lebih mudah dengan Eloquent ORM
3. ✅ Type safety dengan Model
4. ✅ Auto relationships
5. ✅ Built-in validation

**Supabase Client bisa ditambahkan nanti** jika:

-   Perlu upload file ke Supabase Storage
-   Perlu real-time updates
-   Ingin konsistensi dengan Flutter app
