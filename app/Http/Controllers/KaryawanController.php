<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\Asset;
use App\Models\UserAsset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class KaryawanController extends Controller
{
    /**
     * Display a listing of karyawan
     */
    public function index(Request $request)
    {
        try {
            // Ambil semua karyawan Maintenance dengan assets
            $karyawanQuery = Karyawan::with(['assets'])
                ->where('department', 'Maintenance');

            // Filter hanya yang memiliki akses ke aplikasi MT dengan role Teknisi atau KASIE Teknisi
            $karyawanIds = DB::table('karyawan_aplikasi')
                ->join('aplikasi', 'karyawan_aplikasi.aplikasi_id', '=', 'aplikasi.id')
                ->where('aplikasi.kode_aplikasi', 'MT')
                ->whereIn('karyawan_aplikasi.role', ['Teknisi', 'KASIE Teknisi'])
                ->pluck('karyawan_aplikasi.karyawan_id')
                ->toArray();

            $karyawanQuery->whereIn('id', $karyawanIds);

            // Search
            $searchQuery = $request->get('search', '');
            if ($searchQuery) {
                $karyawanQuery->where(function($q) use ($searchQuery) {
                    $q->where('full_name', 'ilike', "%{$searchQuery}%")
                      ->orWhere('email', 'ilike', "%{$searchQuery}%")
                      ->orWhere('phone', 'ilike', "%{$searchQuery}%")
                      ->orWhere('jabatan', 'ilike', "%{$searchQuery}%");
                });
            }

            // Filter by mesin
            $filterMesin = $request->get('mesin');
            if ($filterMesin) {
                $karyawanQuery->whereHas('assets', function($q) use ($filterMesin) {
                    $q->where('nama_assets', $filterMesin);
                });
            }

            $karyawan = $karyawanQuery->orderBy('created_at', 'desc')->get();

            // Transform data untuk UI
            $karyawanData = $karyawan->map(function($k) {
                $mesinList = $k->assets->pluck('nama_assets')->toArray();
                return [
                    'id' => $k->id,
                    'nama' => $k->full_name ?? '-',
                    'email' => $k->email,
                    'telp' => $k->phone ?? '-',
                    'jabatan' => $k->jabatan ?? '-',
                    'department' => $k->department ?? '-',
                    'mesin' => implode(', ', $mesinList) ?: '-',
                    'is_active' => $k->is_active,
                ];
            })->toArray();

            // Get mesin list untuk filter
            $mesinList = Asset::select('nama_assets')
                ->distinct()
                ->whereNotNull('nama_assets')
                ->pluck('nama_assets')
                ->toArray();

            return view('karyawan.index', compact('karyawanData', 'searchQuery', 'filterMesin', 'mesinList'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memuat data: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $assets = Asset::select('id', 'nama_assets', 'kode_assets')
            ->orderBy('nama_assets')
            ->get();
        
        $jabatanList = ['Teknisi', 'Kasie Teknisi', 'Admin Staff'];
        
        return view('karyawan.create', compact('assets', 'jabatanList'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:karyawan,email',
            'password' => 'required|string|min:6',
            'phone' => 'nullable|string|max:20',
            'jabatan' => 'required|string|in:Teknisi,Kasie Teknisi,Admin Staff',
            'department' => 'nullable|string|max:255',
            'assets' => 'nullable|array',
            'assets.*' => 'exists:assets,id',
        ]);

        DB::beginTransaction();
        try {
            // Create karyawan
            $karyawan = Karyawan::create([
                'id' => Str::uuid()->toString(),
                'email' => $request->email,
                'password_hash' => Hash::make($request->password),
                'full_name' => $request->full_name,
                'phone' => $request->phone,
                'jabatan' => $request->jabatan,
                'department' => $request->department ?? 'Maintenance',
                'is_active' => true,
            ]);

            // Berikan akses ke aplikasi MT
            $aplikasiMt = DB::table('aplikasi')
                ->where('kode_aplikasi', 'MT')
                ->first();

            if ($aplikasiMt) {
                DB::table('karyawan_aplikasi')->insert([
                    'id' => Str::uuid()->toString(),
                    'karyawan_id' => $karyawan->id,
                    'aplikasi_id' => $aplikasiMt->id,
                    'role' => $request->jabatan,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Assign assets jika ada
            if ($request->has('assets') && is_array($request->assets)) {
                foreach ($request->assets as $assetId) {
                    UserAsset::create([
                        'id' => Str::uuid()->toString(),
                        'karyawan_id' => $karyawan->id,
                        'assets_id' => $assetId,
                        'assigned_at' => now(),
                        'created_at' => now(),
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('karyawan.index')->with('success', 'Karyawan berhasil ditambahkan');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menambahkan karyawan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $karyawan = Karyawan::with(['assets'])->findOrFail($id);
        return view('karyawan.show', compact('karyawan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $karyawan = Karyawan::with(['assets'])->findOrFail($id);
        $assets = Asset::select('id', 'nama_assets', 'kode_assets')
            ->orderBy('nama_assets')
            ->get();
        
        $jabatanList = ['Teknisi', 'Kasie Teknisi', 'Admin Staff'];
        $selectedAssets = $karyawan->assets->pluck('id')->toArray();
        
        return view('karyawan.edit', compact('karyawan', 'assets', 'jabatanList', 'selectedAssets'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:karyawan,email,' . $id,
            'password' => 'nullable|string|min:6',
            'phone' => 'nullable|string|max:20',
            'jabatan' => 'required|string|in:Teknisi,Kasie Teknisi,Admin Staff',
            'department' => 'nullable|string|max:255',
            'assets' => 'nullable|array',
            'assets.*' => 'exists:assets,id',
        ]);

        DB::beginTransaction();
        try {
            $karyawan = Karyawan::findOrFail($id);

            // Update karyawan
            $updateData = [
                'full_name' => $request->full_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'jabatan' => $request->jabatan,
                'department' => $request->department ?? 'Maintenance',
            ];

            if ($request->filled('password')) {
                $updateData['password_hash'] = Hash::make($request->password);
            }

            $karyawan->update($updateData);

            // Update role di karyawan_aplikasi
            $aplikasiMt = DB::table('aplikasi')
                ->where('kode_aplikasi', 'MT')
                ->first();

            if ($aplikasiMt) {
                $existing = DB::table('karyawan_aplikasi')
                    ->where('karyawan_id', $id)
                    ->where('aplikasi_id', $aplikasiMt->id)
                    ->first();

                if ($existing) {
                    DB::table('karyawan_aplikasi')
                        ->where('id', $existing->id)
                        ->update(['role' => $request->jabatan]);
                } else {
                    DB::table('karyawan_aplikasi')->insert([
                        'id' => Str::uuid()->toString(),
                        'karyawan_id' => $id,
                        'aplikasi_id' => $aplikasiMt->id,
                        'role' => $request->jabatan,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            // Update assets (replace semua)
            UserAsset::where('karyawan_id', $id)->delete();
            
            if ($request->has('assets') && is_array($request->assets)) {
                foreach ($request->assets as $assetId) {
                    UserAsset::create([
                        'id' => Str::uuid()->toString(),
                        'karyawan_id' => $id,
                        'assets_id' => $assetId,
                        'assigned_at' => now(),
                        'created_at' => now(),
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('karyawan.index')->with('success', 'Karyawan berhasil diperbarui');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memperbarui karyawan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {
            // Hapus user_assets
            UserAsset::where('karyawan_id', $id)->delete();
            
            // Hapus karyawan_aplikasi
            DB::table('karyawan_aplikasi')->where('karyawan_id', $id)->delete();
            
            // Hapus karyawan
            Karyawan::findOrFail($id)->delete();

            DB::commit();

            return redirect()->route('karyawan.index')->with('success', 'Karyawan berhasil dihapus');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus karyawan: ' . $e->getMessage());
        }
    }
}
