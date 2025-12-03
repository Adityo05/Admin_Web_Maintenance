@extends('layouts.app')

@section('title', 'Maintenance Schedule')

@section('content')
<div class="page-header">
    <h1>Maintenance Schedule - {{ date('Y') }}</h1>
    <div style="display: flex; gap: 10px; align-items: center;">
        <button onclick="location.reload()" class="btn btn-outline" style="display: flex; align-items: center; gap: 8px;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="23 4 23 10 17 10"></polyline>
                <polyline points="1 20 1 14 7 14"></polyline>
                <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path>
            </svg>
        </button>
        <a href="{{ route('maintenance-template.create') }}" class="btn btn-primary">Tambah</a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-error">{{ session('error') }}</div>
@endif

<div class="filter-section" style="margin-bottom: 20px;">
    <div style="display: flex; gap: 15px; align-items: center;">
        <div style="position: relative; flex: 1; max-width: 400px;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#666" stroke-width="2" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%);">
                <circle cx="11" cy="11" r="8"></circle>
                <path d="m21 21-4.35-4.35"></path>
            </svg>
            <input type="text" id="searchMesin" placeholder="Cari mesin..." class="form-input" style="padding-left: 40px;">
        </div>
        
        <select id="filterCategory" class="form-select" style="min-width: 200px;">
            <option value="">Semua Kategori</option>
            <option value="creeper">Creeper</option>
            <option value="mixer">Mixer</option>
            <option value="conveyor">Conveyor</option>
        </select>
        
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
                    <tr class="schedule-row {{ $isActual ? 'actual-row' : '' }}" data-mesin="{{ strtolower($template->asset->nama_assets) }}">
                        @if($isFirstRow)
                            <td rowspan="2">{{ $template->asset->nama_assets }}</td>
                            <td rowspan="2">{{ $template->nama_template }}</td>
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
                            $interval = $template->interval_hari;
                            $currentYear = date('Y');
                            $weeksInYear = 48; // 12 bulan x 4 minggu
                            $weekNumbers = [];
                            // Start from week 1 (first week of January)
                            for ($w = 1; $w <= $weeksInYear; $w++) {
                                $weekNumbers[] = $w;
                            }
                            $actualHours = $item['actual_hours'] ?? [];
                        @endphp
                        @foreach($weekNumbers as $week)
                            @if($isPlan)
                                @php
                                    // Calculate if maintenance is scheduled for this week based on interval
                                    // Maintenance occurs every $interval days
                                    $startDate = $template->start_date ?? now();
                                    $startWeek = \Carbon\Carbon::parse($startDate)->week;
                                    $weeksSinceStart = $week - $startWeek;
                                    if ($weeksSinceStart < 0) {
                                        $weeksSinceStart += 52;
                                    }
                                    
                                    $daysSinceStart = $weeksSinceStart * 7;
                                    $isScheduled = ($daysSinceStart % $interval) < 7 && $daysSinceStart >= 0;
                                    
                                    // Calculate planned hours (default 4 hours per maintenance)
                                    $plannedHours = $isScheduled ? 4 : null;
                                @endphp
                                <td class="week-cell {{ $isScheduled ? 'week-planned' : '' }}">
                                    @if($isScheduled && $plannedHours)
                                        {{ $plannedHours }}
                                    @endif
                                </td>
                            @else
                                @php
                                    $actualHour = $actualHours[$week] ?? null;
                                @endphp
                                <td class="week-cell week-actual">
                                    @if($actualHour)
                                        {{ $actualHour }}
                                    @endif
                                </td>
                            @endif
                        @endforeach
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
