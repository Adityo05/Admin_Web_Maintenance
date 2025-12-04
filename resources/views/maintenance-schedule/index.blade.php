@extends('layouts.app')

@section('title', 'Maintenance Schedule')

@section('content')
<div style="margin-bottom: 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h1 style="font-size: 24px; font-weight: bold; color: #022415;">Maintenance Schedule</h1>
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
<form method="GET" action="{{ route('maintenance-schedule.index') }}" style="margin-bottom: 20px;">
    <div style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
        <div style="flex: 1; min-width: 250px; position: relative;">
            <input 
                type="text" 
                name="search" 
                value="{{ $searchQuery }}" 
                placeholder="Cari asset..." 
                class="table-search"
                style="padding-left: 40px;"
            >
            <svg style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); width: 20px; height: 20px; color: #0A9C5D;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"></circle>
                <path d="m21 21-4.35-4.35"></path>
            </svg>
        </div>
        <select name="status" class="table-search" style="width: 200px;">
            <option value="">Semua Status</option>
            @foreach($statusList as $status)
                <option value="{{ $status }}" {{ $filterStatus == $status ? 'selected' : '' }}>
                    {{ $status }}
                </option>
            @endforeach
        </select>
        <input type="date" 
               name="date" 
               value="{{ $filterDate }}" 
               class="table-search"
               style="width: 180px;">
        <button type="submit" class="btn btn-primary" style="width: auto; padding: 8px 20px; height: auto;">Filter</button>
        @if($searchQuery || $filterStatus || $filterDate)
            <a href="{{ route('maintenance-schedule.index') }}" class="btn btn-secondary" style="width: auto; padding: 8px 20px; height: auto;">Reset</a>
        @endif
        <a href="{{ route('maintenance-schedule.create') }}" class="btn btn-primary" style="width: auto; padding: 8px 20px; height: auto; display: flex; align-items: center; gap: 8px;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Tambah Jadwal
        </a>
    </div>
</form>

<!-- Table -->
<div class="table-container">
    <div class="table-scroll-wrapper">
        <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Asset</th>
                <th>Kode Asset</th>
                <th>Tanggal Jadwal</th>
                <th>Tanggal Selesai</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($schedules as $index => $schedule)
            <tr>
                <td>{{ $schedules->firstItem() + $index }}</td>
                <td>{{ $schedule->asset->nama_assets ?? '-' }}</td>
                <td>{{ $schedule->asset->kode_assets ?? '-' }}</td>
                <td>{{ $schedule->tgl_jadwal->format('d M Y') }}</td>
                <td>{{ $schedule->tgl_selesai ? $schedule->tgl_selesai->format('d M Y') : '-' }}</td>
                <td>
                    <span class="badge badge-{{ 
                        $schedule->status === 'Selesai' ? 'success' : 
                        ($schedule->status === 'Sedang Dikerjakan' ? 'info' : 
                        ($schedule->status === 'Overdue' ? 'danger' : 'warning'))
                    }}">
                        {{ $schedule->status }}
                    </span>
                </td>
                <td>
                    <div class="action-buttons">
                        <a href="{{ route('maintenance-schedule.edit', $schedule->id) }}" class="btn-action btn-action-edit" title="Edit">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                            </svg>
                        </a>
                        <form action="{{ route('maintenance-schedule.destroy', $schedule->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-action btn-action-delete" title="Hapus" onclick="return confirmDelete('Apakah Anda yakin ingin menghapus jadwal ini?')">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="3 6 5 6 21 6"></polyline>
                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                </svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">Tidak ada data jadwal maintenance</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>
</div>

<!-- Pagination -->
@if($schedules->hasPages())
<div class="pagination-container">
    {{ $schedules->links() }}
</div>
@endif
@endsection


