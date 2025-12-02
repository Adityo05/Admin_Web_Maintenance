@extends('layouts.app')

@section('title', 'Cek Sheet Schedule')

@section('content')
<div class="page-header">
    <h1>Cek Sheet Schedule</h1>
    <div style="display: flex; gap: 10px; align-items: center;">
        <button onclick="location.reload()" class="btn btn-outline" style="display: flex; align-items: center; gap: 8px;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="23 4 23 10 17 10"></polyline>
                <polyline points="1 20 1 14 7 14"></polyline>
                <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path>
            </svg>
        </button>
        <a href="{{ route('check-sheet-template.create') }}" class="btn btn-primary">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 8px;">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Tambah
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-error">{{ session('error') }}</div>
@endif

<div class="filter-section" style="margin-bottom: 20px;">
    <form method="GET" action="{{ route('check-sheet-template.index') }}">
        <div style="display: flex; gap: 15px; align-items: center;">
            <div style="position: relative; flex: 1; max-width: 500px;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#666" stroke-width="2" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%);">
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="m21 21-4.35-4.35"></path>
                </svg>
                <input type="text" 
                       name="search" 
                       placeholder="Cari infrastruktur, bagian, periode, jenis pekerjaan..." 
                       value="{{ $searchQuery }}" 
                       class="form-input" 
                       style="padding-left: 40px;">
            </div>
            @if($searchQuery)
                <a href="{{ route('check-sheet-template.index') }}" class="btn btn-outline">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 6px;">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                    Clear
                </a>
            @endif
        </div>
    </form>
</div>

<div class="card">
    <div class="table-scroll">
        <table class="table-checksheet">
            <thead>
                <tr>
                    <th style="width: 50px;">NO</th>
                    <th style="min-width: 180px;">NAMA INFRASTRUKTUR</th>
                    <th style="min-width: 150px;">BAGIAN</th>
                    <th style="min-width: 120px;">PERIODE</th>
                    <th style="min-width: 200px;">JENIS PEKERJAAN</th>
                    <th style="min-width: 220px;">STANDAR PERAWATAN</th>
                    <th style="min-width: 200px;">ALAT DAN BAHAN</th>
                    <th style="width: 160px;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($templates as $index => $template)
                    <tr>
                        <td style="text-align: center;">{{ $index + 1 }}</td>
                        <td>
                            @if($template->komponenAsset && $template->komponenAsset->asset)
                                <strong style="color: #022415;">{{ $template->komponenAsset->asset->nama_assets }}</strong>
                            @else
                                <span style="color: #999;">-</span>
                            @endif
                        </td>
                        <td>
                            @if($template->komponenAsset)
                                <div style="color: #444;">
                                    @if($template->komponenAsset->bagianMesin)
                                        <small style="color: #0a9c5d; font-weight: 600;">{{ $template->komponenAsset->bagianMesin->nama_bagian }}</small><br>
                                    @endif
                                    {{ $template->komponenAsset->nama_bagian }}
                                </div>
                            @else
                                <span style="color: #999;">-</span>
                            @endif
                        </td>
                        <td>
                            @if($template->periode && $template->interval_periode)
                                <span class="badge badge-info" style="white-space: nowrap; background: #2196F3; color: white; padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: 600;">
                                    Per {{ $template->interval_periode }} 
                                    @if($template->periode == 'Harian') 
                                        Hari
                                    @elseif($template->periode == 'Mingguan') 
                                        Minggu
                                    @elseif($template->periode == 'Bulanan') 
                                        Bulan
                                    @else
                                        {{ $template->periode }}
                                    @endif
                                </span>
                            @else
                                <span style="color: #999;">-</span>
                            @endif
                        </td>
                        <td>
                            <div style="color: #444; line-height: 1.4;">
                                {{ $template->jenis_pekerjaan }}
                            </div>
                        </td>
                        <td>
                            <div style="color: #666; font-size: 13px; line-height: 1.4;">
                                {{ $template->std_prwtn }}
                            </div>
                        </td>
                        <td>
                            <div style="color: #666; font-size: 13px; line-height: 1.4;">
                                {{ $template->alat_bahan }}
                            </div>
                        </td>
                        <td>
                            <div style="display: flex; gap: 8px; justify-content: center;">
                                <a href="{{ route('check-sheet-template.edit', $template->id) }}" 
                                   class="btn-icon btn-icon-edit" 
                                   title="Edit">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('check-sheet-template.destroy', $template->id) }}" style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="btn-icon btn-icon-delete" 
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus:\n{{ $template->komponenAsset && $template->komponenAsset->asset ? $template->komponenAsset->asset->nama_assets : '' }} - {{ $template->komponenAsset ? $template->komponenAsset->nama_bagian : '' }}?')" 
                                            title="Hapus">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <polyline points="3 6 5 6 21 6"></polyline>
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                            <line x1="10" y1="11" x2="10" y2="17"></line>
                                            <line x1="14" y1="11" x2="14" y2="17"></line>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center" style="padding: 60px 40px;">
                            <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="#ccc" stroke-width="1.5" style="margin: 0 auto 20px; display: block;">
                                <circle cx="11" cy="11" r="8"></circle>
                                <path d="m21 21-4.35-4.35"></path>
                                <line x1="11" y1="8" x2="11" y2="14"></line>
                                <line x1="8" y1="11" x2="14" y2="11"></line>
                            </svg>
                            <p style="color: #999; font-size: 18px; font-weight: 500; margin-bottom: 8px;">Data tidak ditemukan</p>
                            <p style="color: #aaa; font-size: 14px; margin-bottom: 20px;">
                                @if($searchQuery)
                                    Coba kata kunci lain atau bersihkan pencarian
                                @else
                                    Belum ada cek sheet schedule
                                @endif
                            </p>
                            @if($searchQuery)
                                <a href="{{ route('check-sheet-template.index') }}" class="btn btn-outline">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 6px;">
                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                    </svg>
                                    Bersihkan Pencarian
                                </a>
                            @else
                                <a href="{{ route('check-sheet-template.create') }}" class="btn btn-primary">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 6px;">
                                        <line x1="12" y1="5" x2="12" y2="19"></line>
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                    </svg>
                                    Tambah Schedule Pertama
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<style>
.table-scroll {
    overflow-x: auto;
    overflow-y: visible;
    max-width: 100%;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
}

.table-checksheet {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    min-width: 1200px;
    margin: 0;
}

.table-checksheet thead th {
    background: linear-gradient(135deg, #0a9c5d 0%, #088c51 100%);
    color: white;
    padding: 14px 16px;
    text-align: left;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-right: 1px solid rgba(255,255,255,0.2);
    border-bottom: 2px solid #088c51;
    white-space: nowrap;
}

.table-checksheet thead th:last-child {
    border-right: none;
}

.table-checksheet tbody td {
    padding: 14px 16px;
    border-right: 1px solid #e0e0e0;
    border-bottom: 1px solid #e0e0e0;
    font-size: 13px;
    background: white;
    vertical-align: top;
}

.table-checksheet tbody td:last-child {
    border-right: none;
}

.table-checksheet tbody tr:last-child td {
    border-bottom: none;
}

.table-checksheet tbody tr:hover td {
    background: #f7fcf9;
}

.btn-icon {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.btn-icon-edit {
    background: #2196F3;
    color: white;
}

.btn-icon-edit:hover {
    background: #1976D2;
    box-shadow: 0 4px 8px rgba(33, 150, 243, 0.3);
}

.btn-icon-delete {
    background: #f44336;
    color: white;
}

.btn-icon-delete:hover {
    background: #d32f2f;
    box-shadow: 0 4px 8px rgba(244, 67, 54, 0.3);
}

.btn-icon svg {
    stroke: currentColor;
}
</style>
@endsection
