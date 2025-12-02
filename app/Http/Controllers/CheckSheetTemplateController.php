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
                             });
                      });
                });
            }

            // Get all templates (no pagination for calendar view)
            $templates = $query->orderBy('created_at', 'desc')->get();

            return view('check-sheet-template.index', compact('templates', 'searchQuery'));

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
        $request->validate([
            'komponen_assets_id' => 'required|exists:komponen_assets,id',
            'periode' => 'required|in:Harian,Mingguan,Bulanan',
            'interval_periode' => 'required|integer|min:1',
            'jenis_pekerjaan' => 'required|string',
            'std_prwtn' => 'required|string',
            'alat_bahan' => 'required|string',
        ], [
            'komponen_assets_id.required' => 'Komponen harus dipilih',
            'periode.required' => 'Periode harus dipilih',
            'interval_periode.required' => 'Interval harus diisi',
            'interval_periode.min' => 'Interval minimal 1',
            'jenis_pekerjaan.required' => 'Jenis pekerjaan harus diisi',
            'std_prwtn.required' => 'Standar perawatan harus diisi',
            'alat_bahan.required' => 'Alat dan bahan harus diisi',
        ]);

        try {
            CheckSheetTemplate::create([
                'id' => Str::uuid()->toString(),
                'komponen_assets_id' => $request->komponen_assets_id,
                'periode' => $request->periode,
                'interval_periode' => $request->interval_periode,
                'jenis_pekerjaan' => $request->jenis_pekerjaan,
                'std_prwtn' => $request->std_prwtn,
                'alat_bahan' => $request->alat_bahan,
            ]);

            return redirect()->route('check-sheet-template.index')->with('success', 'Cek sheet schedule berhasil ditambahkan');

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
