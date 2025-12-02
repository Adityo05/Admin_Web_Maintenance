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
    <div class="calendar-scroll">
        <table class="maintenance-calendar-table">
            <thead>
                <tr>
                    <th class="fixed-col" rowspan="2" style="min-width: 120px;">NAMA MESIN</th>
                    <th class="fixed-col" rowspan="2" style="min-width: 120px;">BAGIAN MESIN</th>
                    <th class="fixed-col" rowspan="2" style="min-width: 100px;">LIFT TIME<br>MESIN / HARI</th>
                    <th class="fixed-col" rowspan="2" style="min-width: 80px;">AKSI</th>
                    <th class="fixed-col" rowspan="2" style="min-width: 80px;">PVL</th>
                    <th colspan="4" class="month-header">JANUARI<br>{{ date('Y') }}</th>
                    <th colspan="4" class="month-header">FEBRUARI<br>{{ date('Y') }}</th>
                    <th colspan="5" class="month-header">MARET<br>{{ date('Y') }}</th>
                    <th colspan="4" class="month-header">APRIL<br>{{ date('Y') }}</th>
                    <th colspan="4" class="month-header">MEI<br>{{ date('Y') }}</th>
                    <th colspan="4" class="month-header">JUNI<br>{{ date('Y') }}</th>
                    <th colspan="5" class="month-header">JULI<br>{{ date('Y') }}</th>
                    <th colspan="4" class="month-header">AGUSTUS<br>{{ date('Y') }}</th>
                    <th colspan="4" class="month-header">SEPTEMBER<br>{{ date('Y') }}</th>
                    <th colspan="5" class="month-header">OKTOBER<br>{{ date('Y') }}</th>
                    <th colspan="4" class="month-header">NOVEMBER<br>{{ date('Y') }}</th>
                    <th colspan="4" class="month-header">DESEMBER<br>{{ date('Y') }}</th>
                </tr>
                <tr>
                    @for($w = 1; $w <= 52; $w++)
                        <th class="week-header">W{{ $w }}</th>
                    @endfor
                </tr>
            </thead>
            <tbody id="maintenanceTableBody">
                @forelse($templates as $template)
                    @if($template->asset)
                    <tr class="schedule-row" data-mesin="{{ strtolower($template->asset->nama_assets) }}">
                        <td class="fixed-col">{{ $template->asset->nama_assets }}</td>
                        <td class="fixed-col">{{ $template->nama_template }}</td>
                        <td class="fixed-col" style="text-align: center;">{{ $template->interval_hari }} hari</td>
                        <td class="fixed-col action-cell">
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
                        <td class="fixed-col plan-label">PLAN</td>
                        @php
                            $interval = $template->interval_hari;
                            $weeksInYear = 52;
                        @endphp
                        @for($week = 1; $week <= $weeksInYear; $week++)
                            @php
                                // Calculate if maintenance is scheduled for this week
                                $dayOfYear = $week * 7;
                                $isScheduled = ($dayOfYear % $interval) <= 7 && ($dayOfYear % $interval) > 0;
                            @endphp
                            <td class="week-cell {{ $isScheduled ? 'week-planned' : '' }}">
                                @if($isScheduled)
                                    {{ $week }}
                                @endif
                            </td>
                        @endfor
                    </tr>
                    <tr class="schedule-row actual-row" data-mesin="{{ strtolower($template->asset->nama_assets) }}">
                        <td class="fixed-col" colspan="4"></td>
                        <td class="fixed-col actual-label">ACTUAL</td>
                        @for($week = 1; $week <= $weeksInYear; $week++)
                            <td class="week-cell week-actual"></td>
                        @endfor
                    </tr>
                    @endif
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
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    overflow: hidden;
}

.calendar-scroll {
    overflow-x: auto;
    overflow-y: visible;
    max-width: 100%;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
}

.maintenance-calendar-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    min-width: 3500px;
    margin: 0;
}

.maintenance-calendar-table thead th {
    background: #0a9c5d;
    color: white;
    padding: 12px 8px;
    text-align: center;
    font-size: 11px;
    font-weight: 600;
    border-right: 1px solid rgba(255,255,255,0.2);
    border-bottom: 2px solid #088c51;
    white-space: nowrap;
}

.maintenance-calendar-table th.fixed-col {
    position: sticky;
    left: 0;
    z-index: 20;
    background: #0a9c5d;
    border-right: 2px solid rgba(255,255,255,0.3);
}

.maintenance-calendar-table th.fixed-col:nth-child(1) { left: 0; }
.maintenance-calendar-table th.fixed-col:nth-child(2) { left: 120px; }
.maintenance-calendar-table th.fixed-col:nth-child(3) { left: 240px; }
.maintenance-calendar-table th.fixed-col:nth-child(4) { left: 340px; }
.maintenance-calendar-table th.fixed-col:nth-child(5) { left: 420px; }

.maintenance-calendar-table td.fixed-col {
    position: sticky;
    background: white;
    z-index: 5;
    border-right: 2px solid #ddd;
}

.maintenance-calendar-table td.fixed-col:nth-child(1) { left: 0; }
.maintenance-calendar-table td.fixed-col:nth-child(2) { left: 120px; }
.maintenance-calendar-table td.fixed-col:nth-child(3) { left: 240px; }
.maintenance-calendar-table td.fixed-col:nth-child(4) { left: 340px; }
.maintenance-calendar-table td.fixed-col:nth-child(5) { left: 420px; }

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
    border: 1px solid #e0e0e0;
    font-size: 13px;
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
