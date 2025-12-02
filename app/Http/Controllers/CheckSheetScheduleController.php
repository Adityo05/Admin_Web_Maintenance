<?php

namespace App\Http\Controllers;

use App\Models\CheckSheetSchedule;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CheckSheetScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = CheckSheetSchedule::with(['completedBy']);

            // Search
            $searchQuery = $request->get('search', '');
            if ($searchQuery) {
                $query->where(function($q) use ($searchQuery) {
                    $q->where('catatan', 'ilike', "%{$searchQuery}%")
                      ->orWhereHas('completedBy', function($qk) use ($searchQuery) {
                          $qk->where('full_name', 'ilike', "%{$searchQuery}%");
                      });
                });
            }

            // Filter by tanggal
            $filterDate = $request->get('date');
            if ($filterDate) {
                $query->whereDate('tgl_jadwal', $filterDate);
            }

            // Filter by status (completed or not)
            $filterStatus = $request->get('status');
            if ($filterStatus === 'completed') {
                $query->whereNotNull('tgl_selesai');
            } elseif ($filterStatus === 'pending') {
                $query->whereNull('tgl_selesai');
            }

            $schedules = $query->orderBy('tgl_jadwal', 'desc')->paginate(15);

            $statusList = [
                'pending' => 'Belum Selesai',
                'completed' => 'Sudah Selesai'
            ];

            return view('check-sheet-schedule.index', compact('schedules', 'searchQuery', 'filterDate', 'filterStatus', 'statusList'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memuat data: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $karyawan = Karyawan::where('is_active', true)
            ->where('department', 'Maintenance')
            ->orderBy('full_name')
            ->get();
        
        return view('check-sheet-schedule.create', compact('karyawan'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tgl_jadwal' => 'required|date',
            'completed_by' => 'nullable|exists:karyawan,id',
            'foto_sblm' => 'nullable|image|max:2048',
            'foto_sesudah' => 'nullable|image|max:2048',
            'catatan' => 'nullable|string',
        ], [
            'tgl_jadwal.required' => 'Tanggal jadwal harus diisi',
            'foto_sblm.image' => 'File harus berupa gambar',
            'foto_sesudah.image' => 'File harus berupa gambar',
        ]);

        try {
            $data = [
                'id' => Str::uuid()->toString(),
                'template_id' => null, // TODO: Implement template
                'tgl_jadwal' => $request->tgl_jadwal,
                'tgl_selesai' => $request->has('completed_by') ? now() : null,
                'catatan' => $request->catatan,
                'completed_by' => $request->completed_by,
            ];

            // Handle foto_sblm upload
            if ($request->hasFile('foto_sblm')) {
                $path = $request->file('foto_sblm')->store('check-sheets', 'public');
                $data['foto_sblm'] = $path;
            }

            // Handle foto_sesudah upload
            if ($request->hasFile('foto_sesudah')) {
                $path = $request->file('foto_sesudah')->store('check-sheets', 'public');
                $data['foto_sesudah'] = $path;
            }

            CheckSheetSchedule::create($data);

            return redirect()->route('check-sheet-schedule.index')->with('success', 'Jadwal cek sheet berhasil ditambahkan');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan jadwal: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $schedule = CheckSheetSchedule::with(['completedBy'])->findOrFail($id);
        return view('check-sheet-schedule.show', compact('schedule'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $schedule = CheckSheetSchedule::with(['completedBy'])->findOrFail($id);
        $karyawan = Karyawan::where('is_active', true)
            ->where('department', 'Maintenance')
            ->orderBy('full_name')
            ->get();
        
        return view('check-sheet-schedule.edit', compact('schedule', 'karyawan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'tgl_jadwal' => 'required|date',
            'completed_by' => 'nullable|exists:karyawan,id',
            'foto_sblm' => 'nullable|image|max:2048',
            'foto_sesudah' => 'nullable|image|max:2048',
            'catatan' => 'nullable|string',
        ]);

        try {
            $schedule = CheckSheetSchedule::findOrFail($id);

            $updateData = [
                'tgl_jadwal' => $request->tgl_jadwal,
                'catatan' => $request->catatan,
                'completed_by' => $request->completed_by,
            ];

            // Set tgl_selesai jika ada completed_by
            if ($request->has('completed_by') && $request->completed_by) {
                $updateData['tgl_selesai'] = $schedule->tgl_selesai ?? now();
            } else {
                $updateData['tgl_selesai'] = null;
            }

            // Handle foto_sblm upload
            if ($request->hasFile('foto_sblm')) {
                // Delete old file
                if ($schedule->foto_sblm) {
                    Storage::disk('public')->delete($schedule->foto_sblm);
                }
                $path = $request->file('foto_sblm')->store('check-sheets', 'public');
                $updateData['foto_sblm'] = $path;
            }

            // Handle foto_sesudah upload
            if ($request->hasFile('foto_sesudah')) {
                // Delete old file
                if ($schedule->foto_sesudah) {
                    Storage::disk('public')->delete($schedule->foto_sesudah);
                }
                $path = $request->file('foto_sesudah')->store('check-sheets', 'public');
                $updateData['foto_sesudah'] = $path;
            }

            $schedule->update($updateData);

            return redirect()->route('check-sheet-schedule.index')->with('success', 'Jadwal cek sheet berhasil diperbarui');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui jadwal: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $schedule = CheckSheetSchedule::findOrFail($id);
            
            // Delete uploaded files
            if ($schedule->foto_sblm) {
                Storage::disk('public')->delete($schedule->foto_sblm);
            }
            if ($schedule->foto_sesudah) {
                Storage::disk('public')->delete($schedule->foto_sesudah);
            }
            
            $schedule->delete();
            
            return redirect()->route('check-sheet-schedule.index')->with('success', 'Jadwal cek sheet berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus jadwal: ' . $e->getMessage());
        }
    }
}
