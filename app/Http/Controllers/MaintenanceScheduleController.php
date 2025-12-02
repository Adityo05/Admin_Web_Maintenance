<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceSchedule;
use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MaintenanceScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = MaintenanceSchedule::with(['asset']);

            // Search
            $searchQuery = $request->get('search', '');
            if ($searchQuery) {
                $query->whereHas('asset', function($q) use ($searchQuery) {
                    $q->where('nama_assets', 'ilike', "%{$searchQuery}%")
                      ->orWhere('kode_assets', 'ilike', "%{$searchQuery}%");
                });
            }

            // Filter by status
            $filterStatus = $request->get('status');
            if ($filterStatus) {
                $query->where('status', $filterStatus);
            }

            // Filter by tanggal
            $filterDate = $request->get('date');
            if ($filterDate) {
                $query->whereDate('tgl_jadwal', $filterDate);
            }

            $schedules = $query->orderBy('tgl_jadwal', 'desc')->paginate(15);

            // Get status list untuk filter
            $statusList = ['Pending', 'Sedang Dikerjakan', 'Selesai', 'Overdue'];

            return view('maintenance-schedule.index', compact('schedules', 'searchQuery', 'filterStatus', 'filterDate', 'statusList'));

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
        
        $statusList = ['Pending', 'Sedang Dikerjakan', 'Selesai', 'Overdue'];
        
        return view('maintenance-schedule.create', compact('assets', 'statusList'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'assets_id' => 'required|exists:assets,id',
            'tgl_jadwal' => 'required|date',
            'status' => 'required|in:Pending,Sedang Dikerjakan,Selesai,Overdue',
        ], [
            'assets_id.required' => 'Asset harus dipilih',
            'tgl_jadwal.required' => 'Tanggal jadwal harus diisi',
            'status.required' => 'Status harus dipilih',
        ]);

        try {
            MaintenanceSchedule::create([
                'id' => Str::uuid()->toString(),
                'assets_id' => $request->assets_id,
                'template_id' => null, // TODO: Implement template
                'tgl_jadwal' => $request->tgl_jadwal,
                'tgl_selesai' => $request->status === 'Selesai' ? now() : null,
                'status' => $request->status,
            ]);

            return redirect()->route('maintenance-schedule.index')->with('success', 'Jadwal maintenance berhasil ditambahkan');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan jadwal: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $schedule = MaintenanceSchedule::with(['asset'])->findOrFail($id);
        return view('maintenance-schedule.show', compact('schedule'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $schedule = MaintenanceSchedule::with(['asset'])->findOrFail($id);
        $assets = Asset::select('id', 'nama_assets', 'kode_assets')
            ->orderBy('nama_assets')
            ->get();
        
        $statusList = ['Pending', 'Sedang Dikerjakan', 'Selesai', 'Overdue'];
        
        return view('maintenance-schedule.edit', compact('schedule', 'assets', 'statusList'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'assets_id' => 'required|exists:assets,id',
            'tgl_jadwal' => 'required|date',
            'status' => 'required|in:Pending,Sedang Dikerjakan,Selesai,Overdue',
        ]);

        try {
            $schedule = MaintenanceSchedule::findOrFail($id);

            $updateData = [
                'assets_id' => $request->assets_id,
                'tgl_jadwal' => $request->tgl_jadwal,
                'status' => $request->status,
            ];

            // Set tgl_selesai jika status berubah ke Selesai
            if ($request->status === 'Selesai' && $schedule->status !== 'Selesai') {
                $updateData['tgl_selesai'] = now();
            } elseif ($request->status !== 'Selesai') {
                $updateData['tgl_selesai'] = null;
            }

            $schedule->update($updateData);

            return redirect()->route('maintenance-schedule.index')->with('success', 'Jadwal maintenance berhasil diperbarui');

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
            MaintenanceSchedule::findOrFail($id)->delete();
            return redirect()->route('maintenance-schedule.index')->with('success', 'Jadwal maintenance berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus jadwal: ' . $e->getMessage());
        }
    }
}
