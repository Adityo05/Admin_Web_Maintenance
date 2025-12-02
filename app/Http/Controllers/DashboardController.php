<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    /**
     * Show dashboard
     */
    public function index()
    {
        $stats = $this->getStats();
        $requestHistory = $this->getRequestHistory();
        $upcomingSchedules = $this->getUpcomingSchedules();
        $currentDate = $this->getCurrentDate();

        return view('dashboard.index', compact('stats', 'requestHistory', 'upcomingSchedules', 'currentDate'));
    }

    /**
     * Get dashboard statistics
     */
    private function getStats()
    {
        try {
            // Count unique assets berdasarkan nama_assets
            $uniqueAssets = Asset::select('nama_assets')
                ->distinct()
                ->count('nama_assets');

            // Hitung total karyawan yang memiliki akses ke aplikasi sistem maintenance (MT)
            // dengan role Teknisi atau KASIE Teknisi
            $totalKaryawan = DB::table('karyawan_aplikasi')
                ->join('aplikasi', 'karyawan_aplikasi.aplikasi_id', '=', 'aplikasi.id')
                ->where('aplikasi.kode_aplikasi', 'MT')
                ->whereIn('karyawan_aplikasi.role', ['Teknisi', 'KASIE Teknisi'])
                ->distinct('karyawan_aplikasi.karyawan_id')
                ->count('karyawan_aplikasi.karyawan_id');

            return [
                'totalAssets' => $uniqueAssets,
                'totalKaryawan' => $totalKaryawan,
                'pendingRequests' => 0, // TODO: Calculate from actual data
                'activeMaintenance' => 0, // TODO: Calculate from actual data
                'overdueSchedule' => 0, // TODO: Calculate from actual data
            ];
        } catch (\Exception $e) {
            return [
                'totalAssets' => 0,
                'totalKaryawan' => 0,
                'pendingRequests' => 0,
                'activeMaintenance' => 0,
                'overdueSchedule' => 0,
            ];
        }
    }

    /**
     * Get request history
     */
    private function getRequestHistory()
    {
        // TODO: Get from actual data
        return [
            [
                'title' => 'Breakdown - Mesin Produksi A',
                'status' => 'Disetujui',
                'date' => '2 hari lalu',
            ],
            [
                'title' => 'Cleaning - Alat Berat B',
                'status' => 'Disetujui',
                'date' => '5 hari lalu',
            ],
            [
                'title' => 'Upgrade - Listrik C',
                'status' => 'Ditolak',
                'date' => '1 minggu lalu',
            ],
        ];
    }

    /**
     * Get upcoming schedules
     */
    private function getUpcomingSchedules()
    {
        // TODO: Get from actual data
        return [
            [
                'title' => 'Maintenance - Mesin A',
                'date' => 'Hari ini',
                'isOverdue' => false,
            ],
            [
                'title' => 'Cek Sheet - Komponen B',
                'date' => 'Besok',
                'isOverdue' => false,
            ],
            [
                'title' => 'Maintenance - Mesin C',
                'date' => '2 hari lagi',
                'isOverdue' => true,
            ],
        ];
    }

    /**
     * Get current date in Indonesian format
     */
    private function getCurrentDate()
    {
        $now = now();
        $months = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];
        
        return $now->day . ' ' . $months[$now->month - 1] . ' ' . $now->year;
    }
}
