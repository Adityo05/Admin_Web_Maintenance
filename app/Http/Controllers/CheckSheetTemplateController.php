<?php

namespace App\Http\Controllers;

use App\Models\CheckSheetTemplate;
use App\Models\KomponenAsset;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CheckSheetTemplateController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = CheckSheetTemplate::with(['komponenAsset.asset', 'komponenAsset.bagianMesin']);

            // Search
            $searchQuery = $request->get('search', '');
            if ($searchQuery) {
                $query->where(function($q) use ($searchQuery) {
                    $q->where('jenis_pekerjaan', 'ilike', "%{$searchQuery}%")
                      ->orWhere('std_prwtn', 'ilike', "%{$searchQuery}%")
                      ->orWhere('alat_bahan', 'ilike', "%{$searchQuery}%")
                      ->orWhereHas('komponenAsset', function($qa) use ($searchQuery) {
                          $qa->where('nama_bagian', 'ilike', "%{$searchQuery}%")
                             ->orWhereHas('asset', function($qb) use ($searchQuery) {
                                 $qb->where('nama_assets', 'ilike', "%{$searchQuery}%");
                             })
                             ->orWhereHas('bagianMesin', function($qc) use ($searchQuery) {
                                 $qc->where('nama_bagian', 'ilike', "%{$searchQuery}%");
                             });
                      });
                });
            }

            // Get all templates
            $templates = $query->orderBy('created_at', 'desc')->get();

            // Transform data untuk grouping dengan rowspan seperti di Dart
            $flatData = [];
            foreach ($templates as $template) {
                $assetName = $template->komponenAsset && $template->komponenAsset->asset 
                    ? $template->komponenAsset->asset->nama_assets 
                    : '-';
                $bagianName = $template->komponenAsset && $template->komponenAsset->bagianMesin
                    ? $template->komponenAsset->bagianMesin->nama_bagian
                    : '-';
                $komponenName = $template->komponenAsset 
                    ? $template->komponenAsset->nama_bagian 
                    : '-';
                $periode = $template->periode && $template->interval_periode
                    ? "Per {$template->interval_periode} " . 
                      ($template->periode == 'Harian' ? 'Hari' : 
                       ($template->periode == 'Mingguan' ? 'Minggu' : 
                        ($template->periode == 'Bulanan' ? 'Bulan' : $template->periode)))
                    : '-';

                $flatData[] = [
                    'id' => $template->id,
                    'asset_name' => $assetName,
                    'bagian_name' => $bagianName,
                    'komponen_name' => $komponenName,
                    'periode' => $periode,
                    'jenis_pekerjaan' => $template->jenis_pekerjaan,
                    'std_prwtn' => $template->std_prwtn,
                    'alat_bahan' => $template->alat_bahan,
                ];
            }

            // Hitung rowspan untuk asset dan bagian
            $processedData = [];
            $totalRows = count($flatData);
            
            // Hitung rowspan
            $assetRowspans = [];
            $bagianRowspans = [];
            
            for ($i = 0; $i < $totalRows; $i++) {
                $row = $flatData[$i];
                $assetKey = $row['asset_name'];
                $bagianKey = $assetKey . '|' . $row['bagian_name'];
                
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
                $currentAssetKey = $currentRow['asset_name'];
                $currentBagianKey = $currentAssetKey . '|' . $currentRow['bagian_name'];
                
                // Cek baris sebelumnya
                $prevAssetKey = null;
                $prevBagianKey = null;
                if ($i > 0) {
                    $prevRow = $flatData[$i - 1];
                    $prevAssetKey = $prevRow['asset_name'];
                    $prevBagianKey = $prevAssetKey . '|' . $prevRow['bagian_name'];
                }
                
                // Tentukan apakah ini baris pertama dalam grup
                $isFirstAssetInGroup = ($prevAssetKey !== $currentAssetKey);
                $isFirstBagianInGroup = ($prevBagianKey !== $currentBagianKey);
                
                $currentRow['show_asset_name'] = $isFirstAssetInGroup;
                $currentRow['show_bagian_name'] = $isFirstBagianInGroup;
                $currentRow['asset_rowspan'] = $isFirstAssetInGroup ? $assetRowspans[$currentAssetKey] : 0;
                $currentRow['bagian_rowspan'] = $isFirstBagianInGroup ? $bagianRowspans[$currentBagianKey] : 0;
                
                $processedData[] = $currentRow;
            }

            return view('check-sheet-template.index', compact('processedData', 'searchQuery'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memuat data: ' . $e->getMessage());
        }
    }

    public function create()
    {
        $assets = \App\Models\Asset::select('id', 'nama_assets', 'kode_assets')
            ->orderBy('nama_assets')
            ->get();
        
        return view('check-sheet-template.create', compact('assets'));
    }

    public function store(Request $request)
    {
        // Validate komponen array structure
        $request->validate([
            'komponen' => 'required|array|min:1',
            'komponen.*.id' => 'required|exists:komponen_assets,id',
            'komponen.*.workTypes' => 'required|array|min:1',
            'komponen.*.workTypes.*.periode' => 'required|in:Harian,Mingguan,Bulanan',
            'komponen.*.workTypes.*.interval' => 'required|integer|min:1',
            'komponen.*.workTypes.*.jenis_pekerjaan' => 'required|string',
            'komponen.*.workTypes.*.std_prwtn' => 'required|string',
            'komponen.*.workTypes.*.alat_bahan' => 'required|string',
        ], [
            'komponen.required' => 'Minimal satu komponen harus dipilih',
            'komponen.*.id.required' => 'Komponen ID tidak valid',
            'komponen.*.id.exists' => 'Komponen tidak ditemukan di database',
            'komponen.*.workTypes.required' => 'Minimal satu jenis pekerjaan harus diisi',
            'komponen.*.workTypes.*.periode.required' => 'Periode harus dipilih',
            'komponen.*.workTypes.*.periode.in' => 'Periode harus Harian, Mingguan, atau Bulanan',
            'komponen.*.workTypes.*.interval.required' => 'Interval harus diisi',
            'komponen.*.workTypes.*.interval.min' => 'Interval minimal 1',
            'komponen.*.workTypes.*.jenis_pekerjaan.required' => 'Jenis pekerjaan harus diisi',
            'komponen.*.workTypes.*.std_prwtn.required' => 'Standar perawatan harus diisi',
            'komponen.*.workTypes.*.alat_bahan.required' => 'Alat dan bahan harus diisi',
        ]);

        try {
            $savedCount = 0;

            // Loop through each komponen
            foreach ($request->komponen as $komponenData) {
                $komponenId = $komponenData['id'];
                
                // Loop through each work type for this komponen
                foreach ($komponenData['workTypes'] as $workType) {
                    CheckSheetTemplate::create([
                        'id' => Str::uuid()->toString(),
                        'komponen_assets_id' => $komponenId,
                        'periode' => $workType['periode'],
                        'interval_periode' => $workType['interval'],
                        'jenis_pekerjaan' => $workType['jenis_pekerjaan'],
                        'std_prwtn' => $workType['std_prwtn'],
                        'alat_bahan' => $workType['alat_bahan'],
                    ]);
                    $savedCount++;
                }
            }

            $komponenCount = count($request->komponen);
            $message = $savedCount === 1 
                ? 'Cek sheet schedule berhasil ditambahkan'
                : "Berhasil menambahkan {$savedCount} cek sheet schedule untuk {$komponenCount} komponen";

            return redirect()->route('check-sheet-template.index')->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan schedule: ' . $e->getMessage())->withInput();
        }
    }

    public function edit(string $id)
    {
        $template = CheckSheetTemplate::with(['komponenAsset.asset', 'komponenAsset.bagianMesin'])->findOrFail($id);
        $assets = \App\Models\Asset::select('id', 'nama_assets', 'kode_assets')
            ->orderBy('nama_assets')
            ->get();
        
        return view('check-sheet-template.edit', compact('template', 'assets'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'komponen_assets_id' => 'required|exists:komponen_assets,id',
            'periode' => 'required|in:Harian,Mingguan,Bulanan',
            'interval_periode' => 'required|integer|min:1',
            'jenis_pekerjaan' => 'required|string',
            'std_prwtn' => 'required|string',
            'alat_bahan' => 'required|string',
        ]);

        try {
            $template = CheckSheetTemplate::findOrFail($id);
            $template->update([
                'komponen_assets_id' => $request->komponen_assets_id,
                'periode' => $request->periode,
                'interval_periode' => $request->interval_periode,
                'jenis_pekerjaan' => $request->jenis_pekerjaan,
                'std_prwtn' => $request->std_prwtn,
                'alat_bahan' => $request->alat_bahan,
            ]);

            return redirect()->route('check-sheet-template.index')->with('success', 'Cek sheet schedule berhasil diperbarui');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui schedule: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(string $id)
    {
        try {
            $template = CheckSheetTemplate::findOrFail($id);
            $template->delete();
            
            return redirect()->route('check-sheet-template.index')->with('success', 'Cek sheet schedule berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus template: ' . $e->getMessage());
        }
    }

    /**
     * Get komponen by asset ID (untuk AJAX)
     */
    public function getKomponenByAsset($assetId)
    {
        try {
            $komponen = KomponenAsset::with('bagianMesin')
                ->where('assets_id', $assetId)
                ->orderBy('nama_bagian')
                ->get()
                ->map(function($item) {
                    return [
                        'id' => $item->id,
                        'nama_bagian' => $item->nama_bagian,
                        'bagian_mesin' => $item->bagianMesin ? $item->bagianMesin->nama_bagian : null,
                    ];
                });

            return response()->json($komponen);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
