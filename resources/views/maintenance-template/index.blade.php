@extends('layouts.app')

@section('title', 'Maintenance Schedule')

@section('content')
<div style="margin-bottom: 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h1 style="font-size: 24px; font-weight: bold; color: #022415;">Maintenance Schedule - <span id="currentYear">{{ request('year', date('Y')) }}</span></h1>
        <button class="btn-icon" onclick="location.reload()" title="Refresh Data">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="23 4 23 10 17 10"></polyline>
                <polyline points="1 20 1 14 7 14"></polyline>
                <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path>
            </svg>
        </button>
    </div>
</div>

<!-- Search and Filter -->
<div style="margin-bottom: 20px;">
    <div style="display: flex; gap: 12px; align-items: center; margin-bottom: 15px;">
        <div style="flex: 1; position: relative;">
            <input 
                type="text" 
                id="searchMesin" 
                placeholder="Cari mesin..." 
                class="table-search"
                style="padding-left: 40px;"
            >
            <svg style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); width: 20px; height: 20px; color: #0A9C5D;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"></circle>
                <path d="m21 21-4.35-4.35"></path>
            </svg>
        </div>
        <select id="filterCategory" class="table-search" style="width: 200px;">
            <option value="">Semua Kategori</option>
            <option value="creeper">Creeper</option>
            <option value="mixer">Mixer</option>
            <option value="conveyor">Conveyor</option>
        </select>
        <a href="{{ route('maintenance-template.create') }}" class="btn btn-primary" style="width: auto; padding: 8px 20px; height: auto; display: flex; align-items: center; gap: 8px;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Tambah
        </a>
    </div>
    
    <!-- Year Navigation -->
    <div style="display: flex; align-items: center; gap: 10px;">
        <button onclick="changeYear(-1)" class="btn btn-outline" style="padding: 8px 12px;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="15 18 9 12 15 6"></polyline>
            </svg>
        </button>
        <span id="currentYearDisplay" style="font-weight: bold; min-width: 60px; text-align: center;">{{ request('year', date('Y')) }}</span>
        <button onclick="changeYear(1)" class="btn btn-outline" style="padding: 8px 12px;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="9 18 15 12 9 6"></polyline>
            </svg>
        </button>
    </div>
</div>

<!-- Legend Keterangan Warna -->
<div style="display: flex; gap: 24px; align-items: center; padding: 12px 20px; background: white; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 20px;">
    <div style="font-weight: 600; color: #333; font-size: 14px;">Keterangan:</div>
    <div style="display: flex; align-items: center; gap: 8px;">
        <div style="width: 24px; height: 24px; background: #ffc107; border-radius: 4px; border: 1px solid #e0a800;"></div>
        <span style="font-size: 13px; color: #666;">Plan (Belum Selesai)</span>
    </div>
    <div style="display: flex; align-items: center; gap: 8px;">
        <div style="width: 24px; height: 24px; background: #2196F3; border-radius: 4px; border: 1px solid #1976D2;"></div>
        <span style="font-size: 13px; color: #666;">Plan (Sudah Selesai)</span>
    </div>
    <div style="display: flex; align-items: center; gap: 8px;">
        <div style="width: 24px; height: 24px; background: #4CAF50; border-radius: 4px; border: 1px solid #388E3C;"></div>
        <span style="font-size: 13px; color: #666;">Actual (Terealisasi)</span>
    </div>
</div>

<div class="card">
    <div class="table-scroll-wrapper calendar-scroll">
        <table class="maintenance-calendar-table">
            <thead>
                <tr>
                    <th rowspan="2" style="min-width: 120px;">NAMA MESIN</th>
                    <th rowspan="2" style="min-width: 120px;">BAGIAN MESIN</th>
                    <th rowspan="2" style="min-width: 150px;">LIFT TIME<br>MESIN / HARI</th>
                    <th rowspan="2" style="min-width: 80px;">AKSI</th>
                    <th rowspan="2" style="min-width: 60px;">PVL</th>
                    @php
                        $currentYear = date('Y');
                        $weeksInYear = 48; // 12 bulan x 4 minggu
                        $weekNumbers = [];
                        // Start from week 1 (first week of January)
                        for ($w = 1; $w <= $weeksInYear; $w++) {
                            $weekNumbers[] = $w;
                        }
                    @endphp
                    <th colspan="4" class="month-header">JANUARI<br>{{ $currentYear }}</th>
                    <th colspan="4" class="month-header">FEBRUARI<br>{{ $currentYear }}</th>
                    <th colspan="4" class="month-header">MARET<br>{{ $currentYear }}</th>
                    <th colspan="4" class="month-header">APRIL<br>{{ $currentYear }}</th>
                    <th colspan="4" class="month-header">MEI<br>{{ $currentYear }}</th>
                    <th colspan="4" class="month-header">JUNI<br>{{ $currentYear }}</th>
                    <th colspan="4" class="month-header">JULI<br>{{ $currentYear }}</th>
                    <th colspan="4" class="month-header">AGUSTUS<br>{{ $currentYear }}</th>
                    <th colspan="4" class="month-header">SEPTEMBER<br>{{ $currentYear }}</th>
                    <th colspan="4" class="month-header">OKTOBER<br>{{ $currentYear }}</th>
                    <th colspan="4" class="month-header">NOVEMBER<br>{{ $currentYear }}</th>
                    <th colspan="4" class="month-header">DESEMBER<br>{{ $currentYear }}</th>
                </tr>
                <tr>
                    @foreach($weekNumbers as $w)
                        <th class="week-header">W{{ $w }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody id="maintenanceTableBody">
                @forelse($processedTemplates as $index => $item)
                    @php
                        $template = $item['template'];
                        $isPlan = $item['type'] === 'plan';
                        $isActual = $item['type'] === 'actual';
                        $isFirstRow = $isPlan;
                        $prevItem = $index > 0 ? $processedTemplates[$index - 1] : null;
                        $isNewBagian = !$prevItem || ($prevItem['template']->id !== $template->id || $prevItem['type'] === 'actual');
                    @endphp
                    <tr class="schedule-row {{ $isActual ? 'actual-row' : '' }}" data-mesin="{{ strtolower($template->bagianMesin->asset->nama_assets ?? '') }}">
                        @if($isFirstRow)
                            <td rowspan="2">{{ $template->bagianMesin->asset->nama_assets ?? '-' }}</td>
                            <td rowspan="2">{{ $template->bagianMesin->nama_bagian ?? '-' }}</td>
                            <td rowspan="2" style="text-align: center; font-size: 11px;">{{ $item['lift_time'] }}</td>
                            <td class="action-cell" rowspan="2">
                                <button onclick="editSchedule('{{ $template->id }}')" class="calendar-btn-edit" title="Edit">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                    </svg>
                                </button>
                                <button onclick="deleteSchedule('{{ $template->id }}')" class="calendar-btn-delete" title="Hapus">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="3 6 5 6 21 6"></polyline>
                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                    </svg>
                                </button>
                            </td>
                        @endif
                        <td class="{{ $isPlan ? 'plan-label' : 'actual-label' }}">{{ $isPlan ? 'PLAN' : 'ACTUAL' }}</td>
                        @php
                            $currentYear = date('Y');
                            $weeksInYear = 48; // 12 bulan x 4 minggu
                            $schedules = $item['schedules'] ?? [];
                            $actualHours = $item['actual_hours'] ?? [];
                        @endphp
                        @for($weekNum = 1; $weekNum <= $weeksInYear; $weekNum++)
                            @if($isPlan)
                                @php
                                    // Hitung bulan dan range tanggal untuk week ini
                                    $month = (int)ceil($weekNum / 4);
                                    $weekInMonth = (($weekNum - 1) % 4) + 1; // 1-4
                                    $startDay = (($weekInMonth - 1) * 7) + 1;
                                    $endDay = $weekInMonth * 7;
                                    
                                    // Cari schedule yang jatuh di week ini
                                    $scheduleInWeek = null;
                                    foreach ($schedules as $schedule) {
                                        if ($schedule->tgl_jadwal) {
                                            $scheduleDate = \Carbon\Carbon::parse($schedule->tgl_jadwal);
                                            if ($scheduleDate->year == $currentYear && 
                                                $scheduleDate->month == $month &&
                                                $scheduleDate->day >= $startDay &&
                                                $scheduleDate->day <= $endDay) {
                                                $scheduleInWeek = $schedule;
                                                break;
                                            }
                                        }
                                    }
                                    
                                    // Tentukan warna: Biru jika selesai, Kuning jika belum
                                    $isCompleted = $scheduleInWeek && $scheduleInWeek->tgl_selesai && strtolower($scheduleInWeek->status) === 'selesai';
                                    $cellClass = $scheduleInWeek ? ($isCompleted ? 'week-completed' : 'week-planned') : '';
                                @endphp
                                <td class="week-cell clickable-cell {{ $cellClass }}" 
                                    data-type="plan"
                                    data-schedule-id="{{ $scheduleInWeek?->id ?? '' }}"
                                    data-template-id="{{ $template->id }}"
                                    data-week="{{ $weekNum }}"
                                    data-asset-name="{{ $template->bagianMesin->asset->nama_assets ?? '' }}"
                                    data-bagian-name="{{ $template->bagianMesin->nama_bagian ?? '' }}"
                                    data-plan-date="{{ $scheduleInWeek ? \Carbon\Carbon::parse($scheduleInWeek->tgl_jadwal)->format('Y-m-d') : '' }}"
                                    data-actual-date="{{ $scheduleInWeek && $scheduleInWeek->tgl_selesai ? \Carbon\Carbon::parse($scheduleInWeek->tgl_selesai)->format('Y-m-d') : '' }}"
                                    data-is-completed="{{ $isCompleted ? '1' : '0' }}"
                                    onclick="event.stopPropagation(); openPlanContextMenu(this); return false;">
                                    @if($scheduleInWeek)
                                        {{ $scheduleInWeek->tgl_jadwal ? \Carbon\Carbon::parse($scheduleInWeek->tgl_jadwal)->day : '' }}
                                    @endif
                                </td>
                            @else
                                @php
                                    // Cari actual schedule di week ini
                                    $actualScheduleInWeek = null;
                                    foreach ($schedules as $schedule) {
                                        if ($schedule->tgl_selesai) {
                                            $actualDate = \Carbon\Carbon::parse($schedule->tgl_selesai);
                                            if ($actualDate->year == $currentYear) {
                                                $actualMonth = $actualDate->month;
                                                $actualDay = $actualDate->day;
                                                $actualWeekInMonth = (int)ceil($actualDay / 7);
                                                $actualWeekNum = ($actualMonth - 1) * 4 + $actualWeekInMonth;
                                                if ($actualWeekNum == $weekNum) {
                                                    $actualScheduleInWeek = $schedule;
                                                    break;
                                                }
                                            }
                                        }
                                    }
                                    $actualHour = $actualHours[$weekNum] ?? null;
                                @endphp
                                <td class="week-cell week-actual clickable-cell {{ $actualScheduleInWeek ? 'week-actual-filled' : '' }}" 
                                    data-type="actual"
                                    data-schedule-id="{{ $actualScheduleInWeek?->id ?? (isset($schedules[0]) ? $schedules[0]->id : '') }}"
                                    data-template-id="{{ $template->id }}"
                                    data-week="{{ $weekNum }}"
                                    data-asset-name="{{ $template->bagianMesin->asset->nama_assets ?? '' }}"
                                    data-bagian-name="{{ $template->bagianMesin->nama_bagian ?? '' }}"
                                    data-actual-date="{{ $actualScheduleInWeek ? \Carbon\Carbon::parse($actualScheduleInWeek->tgl_selesai)->format('Y-m-d') : '' }}"
                                    onclick="event.stopPropagation(); openActualContextMenu(this); return false;">
                                    @if($actualScheduleInWeek)
                                        {{ \Carbon\Carbon::parse($actualScheduleInWeek->tgl_selesai)->day }}
                                    @elseif($actualHour)
                                        {{ $actualHour }}
                                    @endif
                                </td>
                            @endif
                        @endfor
                    </tr>
                @empty
                    <tr>
                        <td colspan="53" class="text-center" style="padding: 60px; border: none;">
                            <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="#ccc" stroke-width="1.5" style="margin: 0 auto 20px; display: block;">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                            </svg>
                            <p style="color: #999; font-size: 18px; margin-bottom: 0; font-weight: 500;">Tidak ada jadwal untuk tahun {{ request('year', date('Y')) }}</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Plan Context Menu Modal -->
<div id="planContextModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="planModalTitle">Detail Maintenance</h3>
            <button class="modal-close" onclick="closePlanContextModal()">&times;</button>
        </div>
        <div class="modal-body">
            <p id="planModalInfo" style="font-size: 12px; color: #666; margin-bottom: 16px;"></p>
            <div id="planModalDetails"></div>
        </div>
        <div class="modal-footer" id="planModalActions">
            <button type="button" class="btn-modal btn-modal-cancel" onclick="closePlanContextModal()">Tutup</button>
        </div>
    </div>
</div>

<!-- Actual Context Menu Modal -->
<div id="actualContextModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Tanggal Actual</h3>
            <button class="modal-close" onclick="closeActualContextModal()">&times;</button>
        </div>
        <div class="modal-body">
            <p id="actualModalInfo" style="font-size: 12px; color: #666; margin-bottom: 8px;"></p>
            <p id="actualModalDate" style="font-size: 11px; font-weight: 500; margin-bottom: 16px;"></p>
            <div style="padding: 10px; background: rgba(33, 150, 243, 0.1); border-radius: 6px; border: 1px solid rgba(33, 150, 243, 0.3);">
                <div style="display: flex; gap: 8px; align-items: flex-start;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#2196F3" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="16" x2="12" y2="12"></line>
                        <line x1="12" y1="8" x2="12.01" y2="8"></line>
                    </svg>
                    <p style="font-size: 9px; color: #1976D2; margin: 0;">Tanggal actual dapat dipilih kapan saja, termasuk sebelum tanggal plan</p>
                </div>
            </div>
        </div>
        <div class="modal-footer" id="actualModalActions">
            <button type="button" class="btn-modal btn-modal-cancel" onclick="closeActualContextModal()">Batal</button>
        </div>
    </div>
</div>

<!-- Date Picker Modal -->
<div id="datePickerModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="datePickerTitle">Pilih Tanggal</h3>
            <button class="modal-close" onclick="closeDatePickerModal()">&times;</button>
        </div>
        <form id="datePickerForm" onsubmit="event.preventDefault(); submitDatePicker();">
            <input type="hidden" id="datePickerScheduleId">
            <input type="hidden" id="datePickerType">
            
            <div class="modal-body">
                <div class="form-group-modal">
                    <label for="pickedDate">Tanggal <span style="color: red;">*</span></label>
                    <input type="date" id="pickedDate" required>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn-modal btn-modal-cancel" onclick="closeDatePickerModal()">Batal</button>
                <button type="submit" class="btn-modal btn-modal-submit">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Loading Overlay -->
<div id="loadingOverlay" class="loading-overlay">
    <div class="loading-spinner"></div>
    <p style="margin-top: 16px; color: #666;">Memproses...</p>
</div>

<style>
.card {
    background: white;
    border-radius: 8px;
    padding: 0;
    box-shadow: 0 2px 12px rgba(0,0,0,0.1);
    overflow: hidden;
    max-width: 100%;
    overflow-x: visible;
}

.calendar-scroll {
    overflow-x: auto;
    overflow-y: visible;
    max-width: 100%;
    border-radius: 8px;
}

.maintenance-calendar-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    min-width: 3200px;
    margin: 0;
}

.maintenance-calendar-table thead th {
    background: linear-gradient(135deg, #0a9c5d 0%, #088a52 100%);
    color: white;
    padding: 12px 8px;
    text-align: center;
    font-size: 10px;
    font-weight: 600;
    border-right: 1px solid rgba(255,255,255,0.5);
    border-bottom: 1px solid rgba(255,255,255,0.5);
    white-space: nowrap;
}


.month-header {
    background: linear-gradient(135deg, #0a9c5d 0%, #088a52 100%) !important;
    font-size: 10px;
    text-transform: uppercase;
}

.week-header {
    background: linear-gradient(135deg, #0a9c5d 0%, #088a52 100%) !important;
    font-size: 9px;
    min-width: 45px;
}

.maintenance-calendar-table tbody td {
    padding: 8px 6px;
    border-right: 0.5px solid #999;
    border-bottom: 0.5px solid #999;
    font-size: 10px;
}

.week-cell {
    text-align: center;
    min-width: 45px;
    height: 40px;
    vertical-align: middle;
    font-size: 10px;
}

.week-planned {
    background: #ffc107 !important;
    color: #000;
    font-weight: bold;
}

.week-completed {
    background: #2196F3 !important;
    color: white;
    font-weight: bold;
}

.week-actual {
    background: transparent;
}

.week-actual-filled {
    background: #4CAF50 !important;
    color: white;
    font-weight: bold;
}

.plan-label {
    background: transparent !important;
    font-weight: bold;
    text-align: center;
    font-size: 10px;
    color: #333;
}

.actual-label {
    background: transparent !important;
    font-weight: bold;
    text-align: center;
    font-size: 10px;
    color: #333;
}

.actual-row td {
    border-top: none !important;
}

.schedule-row:nth-child(even) td {
    background: #f9f9f9;
}

.schedule-row:nth-child(odd) td {
    background: white;
}

.action-cell {
    text-align: center;
}

.calendar-btn-edit, .calendar-btn-delete {
    background: none;
    border: none;
    cursor: pointer;
    padding: 4px;
    margin: 0 2px;
    border-radius: 4px;
    transition: all 0.2s;
}

.calendar-btn-edit:hover {
    background: #e3f2fd;
}

.calendar-btn-edit svg {
    stroke: #2196F3;
}

.calendar-btn-delete:hover {
    background: #ffebee;
}

.calendar-btn-delete svg {
    stroke: #f44336;
}

.clickable-cell {
    cursor: pointer;
    transition: all 0.2s;
    position: relative;
}

.clickable-cell:hover {
    transform: scale(1.05);
    z-index: 10;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    cursor: pointer;
}

/* Modal Styles */
.modal-overlay {
    display: none !important;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    z-index: 1000;
    align-items: center;
    justify-content: center;
}

.modal-overlay.show {
    display: flex !important;
}

.modal-content {
    background: white;
    border-radius: 12px;
    padding: 24px;
    min-width: 400px;
    max-width: 500px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.2);
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 16px;
    border-bottom: 1px solid #e0e0e0;
}

.modal-header h3 {
    margin: 0;
    font-size: 20px;
    color: #022415;
}

.modal-close {
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: #999;
    padding: 0;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: all 0.2s;
}

.modal-close:hover {
    background: #f0f0f0;
    color: #333;
}

.modal-body {
    margin-bottom: 20px;
}

.form-group-modal {
    margin-bottom: 16px;
}

.form-group-modal label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #333;
    font-size: 14px;
}

.form-group-modal input[type="date"] {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    font-size: 14px;
    transition: all 0.2s;
}

.form-group-modal input[type="date"]:focus {
    outline: none;
    border-color: #0a9c5d;
    box-shadow: 0 0 0 3px rgba(10, 156, 93, 0.1);
}

.form-group-modal input[type="checkbox"] {
    margin-right: 8px;
    width: 18px;
    height: 18px;
    cursor: pointer;
}

.checkbox-label {
    display: flex;
    align-items: center;
    cursor: pointer;
    font-size: 14px;
    color: #666;
}

.modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    padding-top: 16px;
    border-top: 1px solid #e0e0e0;
}

.btn-modal {
    padding: 10px 20px;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-modal-cancel {
    background: #f0f0f0;
    color: #333;
}

.btn-modal-cancel:hover {
    background: #e0e0e0;
}

.btn-modal-submit {
    background: #0a9c5d;
    color: white;
}

.btn-modal-submit:hover {
    background: #088c51;
}

.btn-modal-submit:disabled {
    background: #ccc;
    cursor: not-allowed;
}

.loading-overlay {
    display: none !important;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.9);
    z-index: 2000;
    align-items: center;
    justify-content: center;
    flex-direction: column;
}

.loading-overlay.show {
    display: flex !important;
}

.loading-spinner {
    width: 40px;
    height: 40px;
    border: 4px solid #f0f0f0;
    border-top: 4px solid #0a9c5d;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>

<script>
let currentYear = {{ request('year', date('Y')) }};

function changeYear(delta) {
    currentYear += delta;
    document.getElementById('currentYear').textContent = currentYear;
    document.getElementById('currentYearDisplay').textContent = currentYear;
    
    // Reload dengan parameter tahun
    window.location.href = '{{ route('maintenance-template.index') }}?year=' + currentYear;
}

function editSchedule(id) {
    window.location.href = '{{ route('maintenance-template.index') }}/' + id + '/edit';
}

function deleteSchedule(id) {
    if (confirm('Yakin ingin menghapus jadwal ini?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route('maintenance-template.index') }}/' + id;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        
        form.appendChild(csrfToken);
        form.appendChild(methodField);
        document.body.appendChild(form);
        form.submit();
    }
}

// Search functionality
document.getElementById('searchMesin')?.addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('.schedule-row');
    
    rows.forEach(row => {
        const mesin = row.dataset.mesin || '';
        if (mesin.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

// Category filter
document.getElementById('filterCategory')?.addEventListener('change', function(e) {
    const category = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('.schedule-row');
    
    rows.forEach(row => {
        const mesin = row.dataset.mesin || '';
        if (!category || mesin.includes(category)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

// Ensure modals are hidden on page load
document.addEventListener('DOMContentLoaded', function() {
    ['planContextModal', 'actualContextModal', 'datePickerModal', 'loadingOverlay'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.classList.remove('show');
    });
});

// PLAN Context Menu
let currentPlanCell = null;

function openPlanContextMenu(cell) {
    if (!cell) return;
    event?.stopPropagation();
    event?.preventDefault();
    
    currentPlanCell = cell;
    const assetName = cell.dataset.assetName || '';
    const bagianName = cell.dataset.bagianName || '';
    const planDate = cell.dataset.planDate || '';
    const actualDate = cell.dataset.actualDate || '';
    const isCompleted = cell.dataset.isCompleted === '1';
    const scheduleId = cell.dataset.scheduleId || '';
    
    const modal = document.getElementById('planContextModal');
    const title = document.getElementById('planModalTitle');
    const info = document.getElementById('planModalInfo');
    const details = document.getElementById('planModalDetails');
    const actions = document.getElementById('planModalActions');
    
    title.innerHTML = isCompleted 
        ? '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#2196F3" stroke-width="2" style="vertical-align: middle; margin-right: 8px;"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>Detail Maintenance (Selesai)'
        : '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#0A9C5D" stroke-width="2" style="vertical-align: middle; margin-right: 8px;"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>Edit Tanggal Plan';
    
    info.textContent = `${assetName} - ${bagianName}`;
    
    if (isCompleted) {
        const planDateObj = new Date(planDate + 'T00:00:00');
        const actualDateObj = new Date(actualDate + 'T00:00:00');
        details.innerHTML = `
            <div style="display: flex; gap: 8px; align-items: flex-start; margin-bottom: 12px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#666" stroke-width="2" style="margin-top: 2px;">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
                <div>
                    <p style="font-size: 10px; color: #666; margin: 0 0 2px 0;">Plan:</p>
                    <p style="font-size: 11px; font-weight: bold; margin: 0;">${planDateObj.getDate()}-${planDateObj.getMonth() + 1}-${planDateObj.getFullYear()}</p>
                </div>
            </div>
            <div style="display: flex; gap: 8px; align-items: flex-start;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#4CAF50" stroke-width="2" style="margin-top: 2px;">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
                <div>
                    <p style="font-size: 10px; color: #666; margin: 0 0 2px 0;">Actual:</p>
                    <p style="font-size: 11px; font-weight: bold; margin: 0;">${actualDateObj.getDate()}-${actualDateObj.getMonth() + 1}-${actualDateObj.getFullYear()}</p>
                </div>
            </div>
        `;
        actions.innerHTML = '<button type="button" class="btn-modal btn-modal-cancel" onclick="closePlanContextModal()">Tutup</button>';
    } else {
        const planDateObj = planDate ? new Date(planDate + 'T00:00:00') : null;
        details.innerHTML = planDateObj 
            ? `<p style="font-size: 11px; font-weight: 500;">Plan: ${planDateObj.getDate()}-${planDateObj.getMonth() + 1}-${planDateObj.getFullYear()}</p>`
            : '<p style="font-size: 11px; font-weight: 500;">Plan: -</p>';
        
        actions.innerHTML = `
            <button type="button" class="btn-modal btn-modal-cancel" onclick="closePlanContextModal()">Tutup</button>
            <button type="button" class="btn-modal" style="background: #4CAF50; color: white;" onclick="tambahActual('${scheduleId}', '${planDate}')">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 4px;">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="16"></line>
                    <line x1="8" y1="12" x2="16" y2="12"></line>
                </svg>
                Tambah Actual
            </button>
            <button type="button" class="btn-modal btn-modal-submit" onclick="ubahTanggalPlan('${scheduleId}', '${planDate}')">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 4px;">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
                Ubah Tanggal
            </button>
        `;
    }
    
    modal.classList.add('show');
}

function closePlanContextModal() {
    document.getElementById('planContextModal').classList.remove('show');
    currentPlanCell = null;
}

// ACTUAL Context Menu
let currentActualCell = null;

function openActualContextMenu(cell) {
    if (!cell) return;
    event?.stopPropagation();
    event?.preventDefault();
    
    currentActualCell = cell;
    const assetName = cell.dataset.assetName || '';
    const bagianName = cell.dataset.bagianName || '';
    const actualDate = cell.dataset.actualDate || '';
    const scheduleId = cell.dataset.scheduleId || '';
    
    const modal = document.getElementById('actualContextModal');
    const info = document.getElementById('actualModalInfo');
    const dateText = document.getElementById('actualModalDate');
    const actions = document.getElementById('actualModalActions');
    
    info.textContent = `${assetName} - ${bagianName}`;
    
    if (actualDate) {
        const actualDateObj = new Date(actualDate + 'T00:00:00');
        dateText.textContent = `Tanggal actual: ${actualDateObj.getDate()}-${actualDateObj.getMonth() + 1}-${actualDateObj.getFullYear()}`;
        
        actions.innerHTML = `
            <button type="button" class="btn-modal btn-modal-cancel" onclick="closeActualContextModal()">Batal</button>
            <button type="button" class="btn-modal" style="background: #f44336; color: white;" onclick="hapusActual('${scheduleId}')">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 4px;">
                    <polyline points="3 6 5 6 21 6"></polyline>
                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                </svg>
                Hapus
            </button>
            <button type="button" class="btn-modal btn-modal-submit" onclick="setActual('${scheduleId}', '${actualDate}')">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 4px;">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
                Edit
            </button>
        `;
    } else {
        dateText.textContent = 'Belum ada tanggal actual';
        
        actions.innerHTML = `
            <button type="button" class="btn-modal btn-modal-cancel" onclick="closeActualContextModal()">Batal</button>
            <button type="button" class="btn-modal btn-modal-submit" onclick="setActual('${scheduleId}', '')">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 4px;">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
                Set Tanggal
            </button>
        `;
    }
    
    modal.classList.add('show');
}

function closeActualContextModal() {
    document.getElementById('actualContextModal').classList.remove('show');
    currentActualCell = null;
}

// Date Picker Modal
function tambahActual(scheduleId, currentDate) {
    closePlanContextModal();
    openDatePicker('Tambah Tanggal Actual', scheduleId, 'tambah-actual', currentDate || new Date().toISOString().split('T')[0]);
}

function setActual(scheduleId, currentDate) {
    closeActualContextModal();
    openDatePicker(currentDate ? 'Edit Tanggal Actual' : 'Set Tanggal Actual', scheduleId, 'set-actual', currentDate || new Date().toISOString().split('T')[0]);
}

function ubahTanggalPlan(scheduleId, currentDate) {
    closePlanContextModal();
    openDatePicker('Ubah Tanggal Plan', scheduleId, 'ubah-plan', currentDate);
}

function openDatePicker(title, scheduleId, type, initialDate) {
    document.getElementById('datePickerTitle').textContent = title;
    document.getElementById('datePickerScheduleId').value = scheduleId;
    document.getElementById('datePickerType').value = type;
    document.getElementById('pickedDate').value = initialDate;
    document.getElementById('datePickerModal').classList.add('show');
}

function closeDatePickerModal() {
    document.getElementById('datePickerModal').classList.remove('show');
}

function submitDatePicker() {
    const scheduleId = document.getElementById('datePickerScheduleId').value;
    const type = document.getElementById('datePickerType').value;
    const date = document.getElementById('pickedDate').value;
    
    if (!scheduleId || !date) {
        alert('Data tidak lengkap');
        return;
    }
    
    document.getElementById('loadingOverlay').classList.add('show');
    
    let url = '';
    let data = { date: date };
    
    if (type === 'ubah-plan') {
        url = '{{ route("maintenance-template.update-plan-date") }}';
        data.schedule_id = scheduleId;
    } else {
        url = '{{ route("maintenance-template.set-actual-date") }}';
        data.schedule_id = scheduleId;
    }
    
    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('loadingOverlay').classList.remove('show');
        closeDatePickerModal();
        
        if (data.success) {
            window.location.reload();
        } else {
            alert('Error: ' + (data.message || 'Gagal menyimpan tanggal'));
        }
    })
    .catch(error => {
        document.getElementById('loadingOverlay').classList.remove('show');
        console.error('Error:', error);
        alert('Error: Gagal menyimpan tanggal. Silakan coba lagi.');
    });
}

function hapusActual(scheduleId) {
    if (!confirm('Yakin ingin menghapus tanggal actual?')) return;
    
    closeActualContextModal();
    document.getElementById('loadingOverlay').classList.add('show');
    
    fetch('{{ route("maintenance-template.delete-actual-date") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
        body: JSON.stringify({ schedule_id: scheduleId })
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('loadingOverlay').classList.remove('show');
        
        if (data.success) {
            window.location.reload();
        } else {
            alert('Error: ' + (data.message || 'Gagal menghapus tanggal actual'));
        }
    })
    .catch(error => {
        document.getElementById('loadingOverlay').classList.remove('show');
        console.error('Error:', error);
        alert('Error: Gagal menghapus tanggal actual. Silakan coba lagi.');
    });
}

// Close modals on overlay click
['planContextModal', 'actualContextModal', 'datePickerModal'].forEach(id => {
    document.getElementById(id)?.addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.remove('show');
        }
    });
});

// Close modals on ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closePlanContextModal();
        closeActualContextModal();
        closeDatePickerModal();
    }
});
</script>
@endsection
