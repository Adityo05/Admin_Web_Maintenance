@extends('layouts.app')

@section('title', 'Maintenance Schedule')

@section('content')
<div style="margin-bottom: 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h1 style="font-size: 24px; font-weight: bold; color: #022415;">Maintenance Schedule - {{ date('Y') }}</h1>
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
        <a href="{{ route('maintenance-template.create') }}" class="btn btn-primary" style="width: auto; padding: 8px 20px; height: auto; text-decoration: none; display: flex; align-items: center; gap: 8px;">
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
        <span id="currentYear" style="font-weight: bold; min-width: 60px; text-align: center;">{{ date('Y') }}</span>
        <button onclick="changeYear(1)" class="btn btn-outline" style="padding: 8px 12px;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="9 18 15 12 9 6"></polyline>
            </svg>
        </button>
    </div>
</div>

<div class="card">
    <div class="table-scroll-wrapper calendar-scroll">
        <table class="maintenance-calendar-table">
            <thead>
                <tr>
                    <th rowspan="2" style="min-width: 120px;">NAMA MESIN</th>
                    <th rowspan="2" style="min-width: 120px;">BAGIAN MESIN</th>
                    <th rowspan="2" style="min-width: 150px;">LIFT TIME<br>MESIN (JAM)</th>
                    <th rowspan="2" style="min-width: 80px;">AKSI</th>
                    <th rowspan="2" style="min-width: 80px;">P/L</th>
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
                                @endphp
                                <td class="week-cell clickable-cell {{ $scheduleInWeek ? 'week-planned' : '' }}" 
                                    data-type="plan"
                                    data-template-id="{{ $template->id }}"
                                    data-week="{{ $weekNum }}"
                                    data-current-date="{{ $scheduleInWeek ? \Carbon\Carbon::parse($scheduleInWeek->tgl_jadwal)->format('Y-m-d') : '' }}"
                                    onclick="event.stopPropagation(); openScheduleModal(this); return false;">
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
                                <td class="week-cell week-actual clickable-cell" 
                                    data-type="actual"
                                    data-template-id="{{ $template->id }}"
                                    data-week="{{ $weekNum }}"
                                    data-current-date="{{ $actualScheduleInWeek ? \Carbon\Carbon::parse($actualScheduleInWeek->tgl_selesai)->format('Y-m-d') : '' }}"
                                    onclick="event.stopPropagation(); openScheduleModal(this); return false;">
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
                        <td colspan="57" class="text-center" style="padding: 60px;">
                            <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="#ccc" stroke-width="1.5" style="margin: 0 auto 20px; display: block;">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                            </svg>
                            <p style="color: #999; font-size: 18px; margin-bottom: 20px;">Belum ada jadwal maintenance</p>
                            <a href="{{ route('maintenance-template.create') }}" class="btn btn-primary">Tambah Jadwal Pertama</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Schedule Modal -->
<div id="scheduleModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modalTitle">Update Schedule</h3>
            <button class="modal-close" onclick="closeScheduleModal()">&times;</button>
        </div>
        <form id="scheduleForm" onsubmit="event.preventDefault(); submitSchedule();">
            <input type="hidden" name="template_id" id="scheduleTemplateId">
            <input type="hidden" name="week_number" id="scheduleWeek">
            <input type="hidden" name="type" id="scheduleType">
            
            <div class="modal-body">
                <div class="form-group-modal">
                    <label for="scheduleDate">Tanggal <span style="color: red;">*</span></label>
                    <input type="date" name="date" id="scheduleDate" required>
                </div>
                
                <div class="form-group-modal" id="regenerateGroup" style="display: none;">
                    <label class="checkbox-label">
                        <input type="checkbox" name="regenerate" value="1">
                        <span>Regenerate semua schedule berdasarkan interval template</span>
                    </label>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn-modal btn-modal-cancel" onclick="closeScheduleModal()">Batal</button>
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
    background: #0a9c5d;
    color: white;
    padding: 12px 8px;
    text-align: center;
    font-size: 10px;
    font-weight: 600;
    border-right: 1px solid rgba(255,255,255,0.3);
    border-bottom: 2px solid #088c51;
    white-space: nowrap;
}


.month-header {
    background: #0a9c5d !important;
    font-size: 10px;
    text-transform: uppercase;
}

.week-header {
    background: #0d7a4a !important;
    font-size: 10px;
    min-width: 45px;
}

.maintenance-calendar-table tbody td {
    padding: 10px 8px;
    border: 1px solid #d0d0d0;
    font-size: 12px;
}

.week-cell {
    text-align: center;
    min-width: 45px;
    height: 35px;
    vertical-align: middle;
}

.week-planned {
    background: #ffd700 !important;
    color: #000;
    font-weight: bold;
}

.week-actual {
    background: #f5f5f5;
}

.plan-label {
    background: #fff8dc !important;
    font-weight: bold;
    text-align: center;
    font-size: 11px;
}

.actual-label {
    background: #f0f0f0 !important;
    font-weight: bold;
    text-align: center;
    font-size: 11px;
}

.actual-row td {
    border-top: none !important;
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
    background: rgba(10, 156, 93, 0.1) !important;
    transform: scale(1.05);
    z-index: 10;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
}

.clickable-cell.week-planned:hover {
    background: rgba(255, 235, 59, 0.3) !important;
}

.clickable-cell.week-actual:hover {
    background: rgba(10, 156, 93, 0.2) !important;
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
function changeYear(delta) {
    const yearSpan = document.getElementById('currentYear');
    const currentYear = parseInt(yearSpan.textContent);
    yearSpan.textContent = currentYear + delta;
    // Here you would reload data for the new year
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

// Schedule Modal Functions
let currentCell = null;

// Ensure modal is hidden on page load
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('scheduleModal');
    const loadingOverlay = document.getElementById('loadingOverlay');
    if (modal) {
        modal.classList.remove('show');
    }
    if (loadingOverlay) {
        loadingOverlay.classList.remove('show');
    }
});

function openScheduleModal(cell) {
    // Prevent if cell is null or undefined
    if (!cell) {
        console.error('Cell is null or undefined');
        return;
    }
    
    // Prevent event bubbling
    event?.stopPropagation();
    event?.preventDefault();
    
    currentCell = cell;
    const type = cell.dataset.type;
    const templateId = cell.dataset.templateId;
    const week = parseInt(cell.dataset.week);
    const currentDate = cell.dataset.currentDate || '';
    
    // Validate required data
    if (!type || !templateId || !week) {
        console.error('Missing required data:', { type, templateId, week });
        return;
    }
    
    // Calculate default date based on week number
    const currentYear = parseInt(document.getElementById('currentYear')?.textContent || new Date().getFullYear());
    let defaultDate = currentDate;
    
    if (!defaultDate) {
        // Calculate date from week number
        // Week 1-4 = Januari, Week 5-8 = Februari, dst
        const month = Math.ceil(week / 4); // 1-12
        const weekInMonth = ((week - 1) % 4) + 1; // 1-4
        const day = ((weekInMonth - 1) * 7) + 1; // 1, 8, 15, 22
        
        // Create date string
        const dateObj = new Date(currentYear, month - 1, day);
        defaultDate = dateObj.toISOString().split('T')[0];
    }
    
    // Set modal title
    const modalTitle = document.getElementById('modalTitle');
    if (modalTitle) {
        modalTitle.textContent = type === 'plan' ? 'Update Plan Schedule' : 'Update Actual Date';
    }
    
    // Set form data
    const templateIdInput = document.getElementById('scheduleTemplateId');
    const weekInput = document.getElementById('scheduleWeek');
    const typeInput = document.getElementById('scheduleType');
    const dateInput = document.getElementById('scheduleDate');
    
    if (templateIdInput) templateIdInput.value = templateId;
    if (weekInput) weekInput.value = week;
    if (typeInput) typeInput.value = type;
    if (dateInput) dateInput.value = defaultDate; // Use calculated default date
    
    // Show/hide regenerate checkbox (only for plan)
    const regenerateGroup = document.getElementById('regenerateGroup');
    if (regenerateGroup) {
        if (type === 'plan') {
            regenerateGroup.style.display = 'block';
        } else {
            regenerateGroup.style.display = 'none';
        }
    }
    
    // Show modal
    const modal = document.getElementById('scheduleModal');
    if (modal) {
        modal.classList.add('show');
    }
}

function closeScheduleModal() {
    document.getElementById('scheduleModal').classList.remove('show');
    currentCell = null;
}

function submitSchedule() {
    const form = document.getElementById('scheduleForm');
    const formData = new FormData(form);
    const type = formData.get('type');
    const url = type === 'plan' 
        ? '{{ route("maintenance-template.update-schedule") }}'
        : '{{ route("maintenance-template.update-actual") }}';
    
    // Show loading
    document.getElementById('loadingOverlay').classList.add('show');
    
    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('loadingOverlay').classList.remove('show');
        
        if (data.success) {
            closeScheduleModal();
            // Reload page to show updated data
            window.location.reload();
        } else {
            alert('Error: ' + (data.message || 'Gagal update schedule'));
        }
    })
    .catch(error => {
        document.getElementById('loadingOverlay').classList.remove('show');
        console.error('Error:', error);
        alert('Error: Gagal update schedule. Silakan coba lagi.');
    });
}

// Close modal on overlay click
document.getElementById('scheduleModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeScheduleModal();
    }
});

// Close modal on ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeScheduleModal();
    }
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
</script>
@endsection
