<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\BagianMesin;
use App\Models\KomponenAsset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AssetController extends Controller
{
    /**
     * Display a listing of assets
     */
    public function index(Request $request)
    {
        try {
            // Ambil semua assets dengan relasi
            $assets = Asset::with(['bagianMesin.komponenAssets'])
                ->get();

            // Transformasi data Nested ke Flat (sama seperti Flutter)
            $flatData = [];
            
            foreach ($assets as $asset) {
                $namaAset = $asset->nama_assets ?? '-';
                $kodeAssets = $asset->kode_assets;
                $jenisAset = $asset->jenis_assets ?? '-';
                $foto = $asset->foto;
                $status = $asset->status ?? 'Aktif';
                $mtPriority = $asset->mt_priority;
                $assetId = $asset->id;

                $bagianList = $asset->bagianMesin;

                if ($bagianList->isEmpty()) {
                    $flatData[] = [
                        'id' => $assetId,
                        'nama_aset' => $namaAset,
                        'kode_assets' => $kodeAssets,
                        'jenis_aset' => $jenisAset,
                        'status' => $status,
                        'mt_priority' => $mtPriority,
                        'maintenance_terakhir' => '-',
                        'maintenance_selanjutnya' => '-',
                        'bagian_aset' => '-',
                        'komponen_aset' => '-',
                        'produk_yang_digunakan' => '-',
                        'foto' => $foto,
                    ];
                } else {
                    foreach ($bagianList as $bagian) {
                        $namaBagian = $bagian->nama_bagian ?? '-';
                        $komponenList = $bagian->komponenAssets;

                        if ($komponenList->isEmpty()) {
                            $flatData[] = [
                                'id' => $assetId,
                                'nama_aset' => $namaAset,
                                'kode_assets' => $kodeAssets,
                                'jenis_aset' => $jenisAset,
                                'status' => $status,
                                'mt_priority' => $mtPriority,
                                'maintenance_terakhir' => '-',
                                'maintenance_selanjutnya' => '-',
                                'bagian_aset' => $namaBagian,
                                'komponen_aset' => '-',
                                'produk_yang_digunakan' => '-',
                                'foto' => $foto,
                            ];
                        } else {
                            foreach ($komponenList as $komponen) {
                                $namaKomponen = $komponen->nama_bagian ?? '-';
                                $spesifikasi = $komponen->spesifikasi ?? '-';

                                $flatData[] = [
                                    'id' => $assetId,
                                    'nama_aset' => $namaAset,
                                    'kode_assets' => $kodeAssets,
                                    'jenis_aset' => $jenisAset,
                                    'status' => $status,
                                    'mt_priority' => $mtPriority,
                                    'maintenance_terakhir' => '-',
                                    'maintenance_selanjutnya' => '-',
                                    'bagian_aset' => $namaBagian,
                                    'komponen_aset' => $namaKomponen,
                                    'produk_yang_digunakan' => $spesifikasi,
                                    'foto' => $foto,
                                ];
                            }
                        }
                    }
                }
            }

            // Filter dan sort
            $searchQuery = $request->get('search', '');
            $sortColumn = $request->get('sort', 'nama_aset');
            $sortDirection = $request->get('direction', 'asc');
            $filterJenis = $request->get('jenis_aset');

            // Filter by search
            if ($searchQuery) {
                $searchLower = strtolower($searchQuery);
                $flatData = array_filter($flatData, function ($row) use ($searchLower) {
                    return 
                        strpos(strtolower($row['nama_aset'] ?? ''), $searchLower) !== false ||
                        strpos(strtolower($row['kode_assets'] ?? ''), $searchLower) !== false ||
                        strpos(strtolower($row['jenis_aset'] ?? ''), $searchLower) !== false ||
                        strpos(strtolower($row['bagian_aset'] ?? ''), $searchLower) !== false ||
                        strpos(strtolower($row['komponen_aset'] ?? ''), $searchLower) !== false;
                });
            }

            // Filter by jenis
            if ($filterJenis) {
                $flatData = array_filter($flatData, function ($row) use ($filterJenis) {
                    return $row['jenis_aset'] === $filterJenis;
                });
            }

            // Sort
            usort($flatData, function ($a, $b) use ($sortColumn, $sortDirection) {
                $aValue = $a[$sortColumn] ?? '';
                $bValue = $b[$sortColumn] ?? '';
                
                $result = strcmp($aValue, $bValue);
                return $sortDirection === 'asc' ? $result : -$result;
            });

            // Tandai baris yang memiliki nama aset dan bagian yang sama untuk menghilangkan border
            $processedData = [];
            $totalRows = count($flatData);
            
            // Hitung rowspan untuk setiap grup aset dan bagian
            $assetRowspans = [];
            $bagianRowspans = [];
            
            for ($i = 0; $i < $totalRows; $i++) {
                $row = $flatData[$i];
                $assetKey = $row['nama_aset'] . '|' . $row['kode_assets'] . '|' . $row['id'];
                $bagianKey = $assetKey . '|' . $row['bagian_aset'];
                
                if (!isset($assetRowspans[$assetKey])) {
                    $assetRowspans[$assetKey] = 0;
                }
                $assetRowspans[$assetKey]++;
                
                if (!isset($bagianRowspans[$bagianKey])) {
                    $bagianRowspans[$bagianKey] = 0;
                }
                $bagianRowspans[$bagianKey]++;
            }
            
            for ($i = 0; $i < $totalRows; $i++) {
                $currentRow = $flatData[$i];
                $currentAssetKey = $currentRow['nama_aset'] . '|' . $currentRow['kode_assets'] . '|' . $currentRow['id'];
                $currentBagianKey = $currentAssetKey . '|' . $currentRow['bagian_aset'];
                
                // Cek baris sebelumnya
                $prevAssetKey = null;
                $prevBagianKey = null;
                if ($i > 0) {
                    $prevRow = $flatData[$i - 1];
                    $prevAssetKey = $prevRow['nama_aset'] . '|' . $prevRow['kode_assets'] . '|' . $prevRow['id'];
                    $prevBagianKey = $prevAssetKey . '|' . $prevRow['bagian_aset'];
                }
                
                // Cek baris berikutnya
                $nextAssetKey = null;
                $nextBagianKey = null;
                if ($i < $totalRows - 1) {
                    $nextRow = $flatData[$i + 1];
                    $nextAssetKey = $nextRow['nama_aset'] . '|' . $nextRow['kode_assets'] . '|' . $nextRow['id'];
                    $nextBagianKey = $nextAssetKey . '|' . $nextRow['bagian_aset'];
                }
                
                // Tentukan apakah ini baris pertama dalam grup aset
                $isFirstAssetInGroup = ($prevAssetKey !== $currentAssetKey);
                $hasSameAssetBelow = ($nextAssetKey === $currentAssetKey);
                
                // Tentukan apakah ini baris pertama dalam grup bagian
                $isFirstBagianInGroup = ($prevBagianKey !== $currentBagianKey);
                $hasSameBagianBelow = ($nextBagianKey === $currentBagianKey);
                
                $currentRow['has_same_asset_below'] = $hasSameAssetBelow;
                $currentRow['show_asset_name'] = $isFirstAssetInGroup; // Hanya tampilkan nama aset di baris pertama aset
                $currentRow['show_aksi'] = $isFirstAssetInGroup; // Hanya tampilkan aksi di baris pertama aset
                $currentRow['show_bagian_aset'] = $isFirstBagianInGroup; // Hanya tampilkan bagian aset di baris pertama bagian
                $currentRow['has_same_bagian_below'] = $hasSameBagianBelow;
                $currentRow['asset_rowspan'] = $isFirstAssetInGroup ? $assetRowspans[$currentAssetKey] : 0;
                $currentRow['bagian_rowspan'] = $isFirstBagianInGroup ? $bagianRowspans[$currentBagianKey] : 0;
                
                $processedData[] = $currentRow;
            }

            // Get unique jenis aset untuk filter dropdown
            $jenisAsetList = Asset::select('jenis_assets')
                ->distinct()
                ->whereNotNull('jenis_assets')
                ->pluck('jenis_assets')
                ->toArray();

            return view('assets.index', compact('processedData', 'searchQuery', 'sortColumn', 'sortDirection', 'filterJenis', 'jenisAsetList'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memuat data: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('assets.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_assets' => 'required|string|max:255',
            'kode_assets' => 'nullable|string|max:255',
            'jenis_assets' => 'required|string',
            'mt_priority' => 'nullable|string|in:Low,Medium,High',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'bagian' => 'required|array|min:1',
            'bagian.*.nama_bagian' => 'required|string|max:255',
            'bagian.*.komponen' => 'required|array|min:1',
            'bagian.*.komponen.*.nama_komponen' => 'required|string|max:255',
            'bagian.*.komponen.*.spesifikasi' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Upload foto jika ada
            $fotoPath = null;
            if ($request->hasFile('foto')) {
                $fotoPath = $request->file('foto')->store('assets', 'public');
            }

            // Create asset
            $asset = Asset::create([
                'id' => Str::uuid()->toString(),
                'nama_assets' => $request->nama_assets,
                'kode_assets' => $request->kode_assets,
                'jenis_assets' => $request->jenis_assets,
                'foto' => $fotoPath,
                'status' => 'Aktif',
                'mt_priority' => $request->mt_priority,
            ]);

            // Create bagian mesin dan komponen
            foreach ($request->bagian as $bagianData) {
                $bagian = BagianMesin::create([
                    'id' => Str::uuid()->toString(),
                    'assets_id' => $asset->id,
                    'nama_bagian' => $bagianData['nama_bagian'],
                ]);

                foreach ($bagianData['komponen'] as $komponenData) {
                    KomponenAsset::create([
                        'id' => Str::uuid()->toString(),
                        'assets_id' => $asset->id,
                        'bg_mesin_id' => $bagian->id,
                        'nama_bagian' => $komponenData['nama_komponen'],
                        'spesifikasi' => $komponenData['spesifikasi'] ?? null,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('assets.index')->with('success', 'Asset berhasil ditambahkan');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menambahkan asset: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $asset = Asset::with(['bagianMesin.komponenAssets'])->findOrFail($id);
        return view('assets.show', compact('asset'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $asset = Asset::with(['bagianMesin.komponenAssets'])->findOrFail($id);
        return view('assets.edit', compact('asset'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_assets' => 'required|string|max:255',
            'kode_assets' => 'nullable|string|max:255',
            'jenis_assets' => 'required|string',
            'mt_priority' => 'nullable|string|in:Low,Medium,High',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'bagian' => 'required|array|min:1',
            'bagian.*.nama_bagian' => 'required|string|max:255',
            'bagian.*.komponen' => 'required|array|min:1',
            'bagian.*.komponen.*.nama_komponen' => 'required|string|max:255',
            'bagian.*.komponen.*.spesifikasi' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $asset = Asset::findOrFail($id);

            // Upload foto jika ada
            if ($request->hasFile('foto')) {
                // Hapus foto lama jika ada
                if ($asset->foto) {
                    Storage::disk('public')->delete($asset->foto);
                }
                $fotoPath = $request->file('foto')->store('assets', 'public');
                $asset->foto = $fotoPath;
            }

            // Update asset
            $asset->update([
                'nama_assets' => $request->nama_assets,
                'kode_assets' => $request->kode_assets,
                'jenis_assets' => $request->jenis_assets,
                'mt_priority' => $request->mt_priority,
            ]);

            // Hapus bagian dan komponen lama
            $asset->bagianMesin()->delete();

            // Create bagian mesin dan komponen baru
            foreach ($request->bagian as $bagianData) {
                $bagian = BagianMesin::create([
                    'id' => Str::uuid()->toString(),
                    'assets_id' => $asset->id,
                    'nama_bagian' => $bagianData['nama_bagian'],
                ]);

                foreach ($bagianData['komponen'] as $komponenData) {
                    KomponenAsset::create([
                        'id' => Str::uuid()->toString(),
                        'assets_id' => $asset->id,
                        'bg_mesin_id' => $bagian->id,
                        'nama_bagian' => $komponenData['nama_komponen'],
                        'spesifikasi' => $komponenData['spesifikasi'] ?? null,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('assets.index')->with('success', 'Asset berhasil diperbarui');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memperbarui asset: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {
            $asset = Asset::findOrFail($id);

            // Hapus data terkait (cascading delete)
            // 1. Maintenance request
            DB::table('maintenance_request')->where('assets_id', $id)->delete();
            
            // 2. Maintenance schedule
            DB::table('mt_schedule')->where('assets_id', $id)->delete();
            
            // 3. User assets
            DB::table('user_assets')->where('assets_id', $id)->delete();

            // 4. Cek sheet schedule dan template
            $komponenIds = DB::table('komponen_assets')
                ->where('assets_id', $id)
                ->pluck('id');
            
            $templateIds = DB::table('cek_sheet_template')
                ->whereIn('komponen_assets_id', $komponenIds)
                ->pluck('id');
            
            $scheduleIds = DB::table('cek_sheet_schedule')
                ->whereIn('template_id', $templateIds)
                ->pluck('id');
            
            DB::table('notifikasi')->whereIn('jadwal_id', $scheduleIds)->delete();
            DB::table('cek_sheet_schedule')->whereIn('template_id', $templateIds)->delete();
            DB::table('cek_sheet_template')->whereIn('komponen_assets_id', $komponenIds)->delete();

            // 5. Maintenance template
            $bgMesinIds = DB::table('bg_mesin')
                ->where('assets_id', $id)
                ->pluck('id');
            
            DB::table('mt_template')->whereIn('bg_mesin_id', $bgMesinIds)->delete();

            // 6. Komponen assets
            DB::table('komponen_assets')->where('assets_id', $id)->delete();

            // 7. Bagian mesin
            DB::table('bg_mesin')->where('assets_id', $id)->delete();

            // 8. Hapus foto jika ada
            if ($asset->foto) {
                Storage::disk('public')->delete($asset->foto);
            }

            // 9. Hapus asset
            $asset->delete();

            DB::commit();

            return redirect()->route('assets.index')->with('success', 'Asset berhasil dihapus');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus asset: ' . $e->getMessage());
        }
    }
}
