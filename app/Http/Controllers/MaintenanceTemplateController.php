<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceTemplate;
use App\Models\Asset;
use App\Models\BagianMesin;
use App\Models\MaintenanceSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MaintenanceTemplateController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = MaintenanceTemplate::with(['bagianMesin.asset']);

            // Search
            $searchQuery = $request->get('search', '');
            if ($searchQuery) {
                $query->where(function($q) use ($searchQuery) {
                    $q->orWhereHas('bagianMesin', function($qb) use ($searchQuery) {
                        $qb->where('nama_bagian', 'ilike', "%{$searchQuery}%")
                           ->orWhereHas('asset', function($qa) use ($searchQuery) {
                               $qa->where('nama_assets', 'ilike', "%{$searchQuery}%");
                           });
                    });
                });
            }

            // Get all templates (no pagination for calendar view)
            $templates = $query->orderBy('created_at', 'desc')->get();

            // Get schedules untuk tahun yang dipilih (default tahun ini)
            $currentYear = $request->get('year', date('Y'));
            $schedules = MaintenanceSchedule::whereYear('tgl_jadwal', $currentYear)
                ->with('template')
                ->get();

            // Group schedules by template_id
            $schedulesByTemplate = [];
            $actualHoursByTemplate = [];
            
            foreach ($schedules as $schedule) {
                if ($schedule->template_id) {
                    $templateId = $schedule->template_id;
                    
                    // Store all schedules for this template
                    if (!isset($schedulesByTemplate[$templateId])) {
                        $schedulesByTemplate[$templateId] = [];
                    }
                    $schedulesByTemplate[$templateId][] = $schedule;
                    
                    // Calculate actual hours if completed
                    if ($schedule->tgl_selesai) {
                        $date = Carbon::parse($schedule->tgl_selesai);
                        // Calculate week number in year (1-48, 4 weeks per month)
                        $month = $date->month;
                        $day = $date->day;
                        $weekInMonth = (int)ceil($day / 7); // 1-4
                        $weekNumber = ($month - 1) * 4 + $weekInMonth;
                        
                        if (!isset($actualHoursByTemplate[$templateId])) {
                            $actualHoursByTemplate[$templateId] = [];
                        }
                        
                        // Calculate hours
                        if ($schedule->tgl_selesai && $schedule->tgl_jadwal) {
                            $start = Carbon::parse($schedule->tgl_jadwal);
                            $end = Carbon::parse($schedule->tgl_selesai);
                            $hours = $start->diffInHours($end);
                            if ($hours == 0) $hours = 4;
                        } else {
                            $hours = 4;
                        }
                        
                        if (!isset($actualHoursByTemplate[$templateId][$weekNumber])) {
                            $actualHoursByTemplate[$templateId][$weekNumber] = 0;
                        }
                        $actualHoursByTemplate[$templateId][$weekNumber] += $hours;
                    }
                }
            }

            // Process templates untuk menambahkan data actual dan format lift time
            $processedTemplates = [];
            foreach ($templates as $template) {
                if ($template->bagianMesin && $template->bagianMesin->asset) {
                    // Calculate lift time format (bulan / jam)
                    // Based on interval_periode (in days) and periode type
                    $intervalPeriode = $template->interval_periode ?? 1;
                    $periode = $template->periode ?? 'Hari';
                    
                    // Convert to days
                    $intervalHari = $intervalPeriode;
                    switch($periode) {
                        case 'Minggu':
                            $intervalHari = $intervalPeriode * 7;
                            break;
                        case 'Bulan':
                            $intervalHari = $intervalPeriode * 30;
                            break;
                    }
                    
                    // Assuming 8 hours per day operation
                    $intervalJam = $intervalHari * 8;
                    
                    // Format: "XXX jam" (hanya tampilkan jam, sesuai Flutter)
                    $liftTimeText = $intervalJam . ' jam';
                    
                    // Get schedules and actual hours for this template
                    $templateSchedules = $schedulesByTemplate[$template->id] ?? [];
                    $templateActualHours = $actualHoursByTemplate[$template->id] ?? [];
                    
                    // Add PLAN row
                    $processedTemplates[] = [
                        'type' => 'plan',
                        'template' => $template,
                        'lift_time' => $liftTimeText,
                        'schedules' => $templateSchedules,
                        'actual_hours' => [],
                    ];
                    
                    // Add ACTUAL row
                    $processedTemplates[] = [
                        'type' => 'actual',
                        'template' => $template,
                        'lift_time' => $liftTimeText,
                        'schedules' => $templateSchedules,
                        'actual_hours' => $templateActualHours,
                    ];
                }
            }

            $filterStatus = $request->get('status');
            return view('maintenance-template.index', compact('processedTemplates', 'searchQuery', 'filterStatus', 'currentYear'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memuat data: ' . $e->getMessage());
        }
    }

    public function create()
    {
        $assets = Asset::select('id', 'nama_assets', 'kode_assets')
            ->orderBy('nama_assets')
            ->get();
        
        return view('maintenance-template.create', compact('assets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'assets_id' => 'required|exists:assets,id',
            'bg_mesin_id' => 'required|exists:bg_mesin,id',
            'interval_periode' => 'required|integer|min:1',
            'periode' => 'required|in:Hari,Minggu,Bulan',
            'tanggal_mulai' => 'nullable|date',
        ], [
            'assets_id.required' => 'Asset harus dipilih',
            'bg_mesin_id.required' => 'Bagian mesin harus dipilih',
            'interval_periode.required' => 'Interval harus diisi',
            'interval_periode.min' => 'Interval minimal 1',
            'periode.required' => 'Periode harus dipilih',
        ]);

        try {
            // Buat template terlebih dahulu
            $template = MaintenanceTemplate::create([
                'id' => Str::uuid()->toString(),
                'bg_mesin_id' => $request->bg_mesin_id,
                'interval_periode' => $request->interval_periode,
                'periode' => $request->periode,
                'start_date' => $request->tanggal_mulai ?? now(),
            ]);

            // Generate schedules berulang untuk 1 tahun
            $startDate = Carbon::parse($request->tanggal_mulai ?? now());
            $interval = (int) $request->interval_periode; // Convert to integer
            $periode = $request->periode;
            $currentYear = $startDate->year;
            
            $schedules = [];
            $currentDate = $startDate->copy();
            
            // Generate sampai akhir tahun
            while ($currentDate->year == $currentYear) {
                $schedules[] = [
                    'id' => Str::uuid()->toString(),
                    'template_id' => $template->id,
                    'assets_id' => $request->assets_id,
                    'tgl_jadwal' => $currentDate->format('Y-m-d'),
                    'status' => 'Perlu Maintenance',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                
                // Tambahkan interval sesuai periode
                switch ($periode) {
                    case 'Hari':
                        $currentDate->addDays($interval);
                        break;
                    case 'Minggu':
                        $currentDate->addWeeks($interval);
                        break;
                    case 'Bulan':
                        $currentDate->addMonths($interval);
                        break;
                }
            }
            
            // Batch insert schedules
            if (!empty($schedules)) {
                DB::table('mt_schedule')->insert($schedules);
            }

            return redirect()->route('maintenance-template.index')
                ->with('success', 'Schedule maintenance berhasil ditambahkan (' . count($schedules) . ' jadwal dibuat)');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan schedule: ' . $e->getMessage())->withInput();
        }
    }

    public function edit(string $id)
    {
        $template = MaintenanceTemplate::with(['bagianMesin.asset'])->findOrFail($id);
        $assets = Asset::select('id', 'nama_assets', 'kode_assets')
            ->orderBy('nama_assets')
            ->get();
        
        return view('maintenance-template.edit', compact('template', 'assets'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'assets_id' => 'required|exists:assets,id',
            'bg_mesin_id' => 'required|exists:bg_mesin,id',
            'interval_periode' => 'required|integer|min:1',
            'periode' => 'required|in:Hari,Minggu,Bulan',
        ]);

        try {
            $template = MaintenanceTemplate::findOrFail($id);
            $template->update([
                'bg_mesin_id' => $request->bg_mesin_id,
                'interval_periode' => $request->interval_periode,
                'periode' => $request->periode,
            ]);

            return redirect()->route('maintenance-template.index')->with('success', 'Schedule maintenance berhasil diperbarui');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui schedule: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(string $id)
    {
        try {
            $template = MaintenanceTemplate::findOrFail($id);
            $template->delete();
            
            return redirect()->route('maintenance-template.index')->with('success', 'Template maintenance berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus template: ' . $e->getMessage());
        }
    }

    /**
     * Get bagian mesin by asset ID (AJAX)
     */
    public function getBagianMesinByAsset($assetId)
    {
        try {
            $bagianMesin = Asset::findOrFail($assetId)
                ->bagianMesin()
                ->select('id', 'nama_bagian')
                ->orderBy('nama_bagian')
                ->get();
            
            return response()->json($bagianMesin);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal memuat data'], 500);
        }
    }

    /**
     * Helper: Calculate week number (1-48) from date
     * Mapping: Januari W1-W4, Februari W5-W8, Maret W9-W12, dst
     */
    private function calculateWeekNumber(Carbon $date): int
    {
        $month = $date->month; // 1-12
        $day = $date->day; // 1-31
        $weekInMonth = (int)ceil($day / 7); // 1-4 (week dalam bulan)
        $weekNumber = ($month - 1) * 4 + $weekInMonth; // 1-48
        return min($weekNumber, 48); // Cap at 48
    }

    /**
     * Helper: Calculate date range from week number
     */
    private function calculateDateFromWeek(int $weekNumber, int $year): array
    {
        $month = (int)ceil($weekNumber / 4); // 1-12
        $weekInMonth = (($weekNumber - 1) % 4) + 1; // 1-4
        $startDay = (($weekInMonth - 1) * 7) + 1;
        $endDay = min($weekInMonth * 7, Carbon::create($year, $month)->daysInMonth);
        
        return [
            'month' => $month,
            'startDay' => $startDay,
            'endDay' => $endDay,
        ];
    }

    /**
     * Update plan date (tgl_jadwal)
     */
    public function updatePlanDate(Request $request)
    {
        $request->validate([
            'schedule_id' => 'required|exists:mt_schedule,id',
            'date' => 'required|date',
        ]);

        try {
            $schedule = MaintenanceSchedule::findOrFail($request->schedule_id);
            $schedule->update([
                'tgl_jadwal' => $request->date,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Tanggal plan berhasil diperbarui',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah tanggal plan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Set/Edit actual date (tgl_selesai)
     */
    public function setActualDate(Request $request)
    {
        $request->validate([
            'schedule_id' => 'required|exists:mt_schedule,id',
            'date' => 'required|date',
        ]);

        try {
            $schedule = MaintenanceSchedule::findOrFail($request->schedule_id);
            $schedule->update([
                'tgl_selesai' => $request->date,
                'status' => 'Selesai',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Tanggal actual berhasil disimpan',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan tanggal actual: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete actual date (set tgl_selesai to null)
     */
    public function deleteActualDate(Request $request)
    {
        $request->validate([
            'schedule_id' => 'required|exists:mt_schedule,id',
        ]);

        try {
            $schedule = MaintenanceSchedule::findOrFail($request->schedule_id);
            $schedule->update([
                'tgl_selesai' => null,
                'status' => 'Perlu Maintenance',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Tanggal actual berhasil dihapus - status kembali ke Perlu Maintenance',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus tanggal actual: ' . $e->getMessage(),
            ], 500);
        }
    }
}
