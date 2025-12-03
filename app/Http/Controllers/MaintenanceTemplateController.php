<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceTemplate;
use App\Models\Asset;
use App\Models\MaintenanceSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class MaintenanceTemplateController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = MaintenanceTemplate::with(['asset']);

            // Search
            $searchQuery = $request->get('search', '');
            if ($searchQuery) {
                $query->where(function($q) use ($searchQuery) {
                    $q->where('nama_template', 'ilike', "%{$searchQuery}%")
                      ->orWhere('deskripsi', 'ilike', "%{$searchQuery}%")
                      ->orWhereHas('asset', function($qa) use ($searchQuery) {
                          $qa->where('nama_assets', 'ilike', "%{$searchQuery}%");
                      });
                });
            }

            // Get all templates (no pagination for calendar view)
            $templates = $query->orderBy('created_at', 'desc')->get();

            // Get actual hours from maintenance schedule untuk tahun ini
            $currentYear = date('Y');
            $schedules = MaintenanceSchedule::whereYear('tgl_jadwal', $currentYear)
                ->with('template')
                ->get();

            // Group schedules by template_id and week
            $actualHoursByTemplate = [];
            foreach ($schedules as $schedule) {
                if ($schedule->template_id && $schedule->tgl_selesai) {
                    $week = Carbon::parse($schedule->tgl_selesai)->week;
                    $templateId = $schedule->template_id;
                    
                    if (!isset($actualHoursByTemplate[$templateId])) {
                        $actualHoursByTemplate[$templateId] = [];
                    }
                    
                    // Calculate hours based on actual completion time
                    // If tgl_selesai exists, calculate difference in hours
                    if ($schedule->tgl_selesai && $schedule->tgl_jadwal) {
                        $start = Carbon::parse($schedule->tgl_jadwal);
                        $end = Carbon::parse($schedule->tgl_selesai);
                        $hours = $start->diffInHours($end);
                        if ($hours == 0) $hours = 4; // Default 4 hours if same day
                    } else {
                        $hours = 4; // Default 4 hours
                    }
                    
                    if (!isset($actualHoursByTemplate[$templateId][$week])) {
                        $actualHoursByTemplate[$templateId][$week] = 0;
                    }
                    $actualHoursByTemplate[$templateId][$week] += $hours;
                }
            }

            // Process templates untuk menambahkan data actual dan format lift time
            $processedTemplates = [];
            foreach ($templates as $template) {
                if ($template->asset) {
                    // Calculate lift time format (bulan / jam)
                    // From image: 364 jam = 1 bulan, 728 jam = 2 bulan, 1032 jam = 3 bulan, 1002 jam = 3 bulan
                    $intervalHari = $template->interval_hari;
                    // Assuming 8 hours per day operation
                    $intervalJam = $intervalHari * 8;
                    
                    // Calculate bulan (approximate)
                    if ($intervalJam <= 364) {
                        $intervalBulan = 1;
                    } elseif ($intervalJam <= 728) {
                        $intervalBulan = 2;
                    } elseif ($intervalJam <= 1032) {
                        $intervalBulan = 3;
                    } else {
                        $intervalBulan = round($intervalJam / 364, 1);
                    }
                    
                    $liftTimeText = $intervalBulan . ' bulan / ' . $intervalJam . ' jam';
                    
                    // Get actual hours for this template
                    $templateActualHours = $actualHoursByTemplate[$template->id] ?? [];
                    
                    // Add PLAN row
                    $processedTemplates[] = [
                        'type' => 'plan',
                        'template' => $template,
                        'lift_time' => $liftTimeText,
                        'actual_hours' => [],
                    ];
                    
                    // Add ACTUAL row
                    $processedTemplates[] = [
                        'type' => 'actual',
                        'template' => $template,
                        'lift_time' => $liftTimeText,
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
            'nama_template' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'interval_hari' => 'required|integer|min:1',
            'periode' => 'required|in:Harian,Mingguan,Bulanan',
            'tanggal_mulai' => 'nullable|date',
        ], [
            'assets_id.required' => 'Asset harus dipilih',
            'nama_template.required' => 'Bagian mesin harus dipilih',
            'interval_hari.required' => 'Interval harus diisi',
            'interval_hari.min' => 'Interval minimal 1',
            'periode.required' => 'Periode harus dipilih',
        ]);

        try {
            MaintenanceTemplate::create([
                'id' => Str::uuid()->toString(),
                'assets_id' => $request->assets_id,
                'nama_template' => $request->nama_template,
                'deskripsi' => $request->deskripsi,
                'interval_hari' => $request->interval_hari,
                'periode' => $request->periode,
                'start_date' => $request->tanggal_mulai ?? now(),
            ]);

            return redirect()->route('maintenance-template.index')->with('success', 'Schedule maintenance berhasil ditambahkan');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan schedule: ' . $e->getMessage())->withInput();
        }
    }

    public function edit(string $id)
    {
        $template = MaintenanceTemplate::with(['asset'])->findOrFail($id);
        $assets = Asset::select('id', 'nama_assets', 'kode_assets')
            ->orderBy('nama_assets')
            ->get();
        
        return view('maintenance-template.edit', compact('template', 'assets'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'assets_id' => 'required|exists:assets,id',
            'nama_template' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'interval_hari' => 'required|integer|min:1',
            'periode' => 'required|in:Harian,Mingguan,Bulanan',
        ]);

        try {
            $template = MaintenanceTemplate::findOrFail($id);
            $template->update([
                'assets_id' => $request->assets_id,
                'nama_template' => $request->nama_template,
                'deskripsi' => $request->deskripsi,
                'interval_hari' => $request->interval_hari,
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
}
