@extends('layouts.app')

@section('title', 'Maintenance Schedule')

@section('content')
<div class="page-header">
    <h1 class="page-title">Maintenance Schedule</h1>
    <a href="{{ route('maintenance-schedule.create') }}" class="btn btn-primary">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="12" y1="5" x2="12" y2="19"></line>
            <line x1="5" y1="12" x2="19" y2="12"></line>
        </svg>
        Tambah Jadwal
    </a>
</div>

<!-- Filter & Search -->
<div class="filter-section">
    <form method="GET" action="{{ route('maintenance-schedule.index') }}" class="filter-form">
        <div class="filter-group">
            <input type="text" 
                   name="search" 
                   value="{{ $searchQuery }}" 
                   placeholder="Cari asset..."
                   class="form-input">
        </div>
        
        <div class="filter-group">
            <select name="status" class="form-input">
                <option value="">Semua Status</option>
                @foreach($statusList as $status)
                    <option value="{{ $status }}" {{ $filterStatus == $status ? 'selected' : '' }}>
                        {{ $status }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="filter-group">
            <input type="date" 
                   name="date" 
                   value="{{ $filterDate }}" 
                   class="form-input">
        </div>
        
        <button type="submit" class="btn btn-secondary">Filter</button>
        @if($searchQuery || $filterStatus || $filterDate)
            <a href="{{ route('maintenance-schedule.index') }}" class="btn btn-secondary">Reset</a>
        @endif
    </form>
</div>

<!-- Table -->
<div class="table-container">
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

<!-- Pagination -->
@if($schedules->hasPages())
<div class="pagination-container">
    {{ $schedules->links() }}
</div>
@endif
@endsection


