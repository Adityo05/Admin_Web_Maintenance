@extends('layouts.app')

@section('title', 'Data Assets')

@section('content')
<div style="margin-bottom: 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h1 style="font-size: 24px; font-weight: bold; color: #022415;">Data Aset</h1>
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
<form method="GET" action="{{ route('assets.index') }}" style="margin-bottom: 20px;">
    <div style="display: flex; gap: 12px; align-items: center;">
        <div style="flex: 1; position: relative;">
            <input 
                type="text" 
                name="search" 
                value="{{ $searchQuery }}" 
                placeholder="Cari data aset..." 
                class="table-search"
                style="padding-left: 40px;"
            >
            <svg style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); width: 20px; height: 20px; color: #0A9C5D;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"></circle>
                <path d="m21 21-4.35-4.35"></path>
            </svg>
        </div>
        <select name="jenis_aset" class="table-search" style="width: 200px;">
            <option value="">Semua Jenis</option>
            @foreach($jenisAsetList as $jenis)
                <option value="{{ $jenis }}" {{ $filterJenis == $jenis ? 'selected' : '' }}>{{ $jenis }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-primary" style="width: auto; padding: 8px 20px; height: auto;">Filter</button>
        <a href="{{ route('assets.create') }}" class="btn btn-primary" style="width: auto; padding: 8px 20px; height: auto; text-decoration: none; display: flex; align-items: center; gap: 8px;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Tambah
        </a>
    </div>
    @if($filterJenis)
    <div style="margin-top: 8px;">
        <span class="badge badge-primary" style="display: inline-flex; align-items: center; gap: 8px;">
            Filter: {{ $filterJenis }}
            <a href="{{ route('assets.index', ['search' => $searchQuery]) }}" style="color: inherit; text-decoration: none;">×</a>
        </span>
    </div>
    @endif
</form>

<!-- Table -->
<div class="table-container">
    <div class="table-scroll-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 150px;">
                        <a href="{{ route('assets.index', array_merge(request()->all(), ['sort' => 'kode_assets', 'direction' => $sortColumn == 'kode_assets' && $sortDirection == 'asc' ? 'desc' : 'asc'])) }}" style="text-decoration: none; color: inherit; display: flex; align-items: center; gap: 8px;">
                            Kode Aset
                            @if($sortColumn == 'kode_assets')
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    {{ $sortDirection == 'asc' ? '<polyline points="18 15 12 9 6 15"></polyline>' : '<polyline points="6 9 12 15 18 9"></polyline>' }}
                                </svg>
                            @endif
                        </a>
                    </th>
                    <th style="width: 200px;">
                        <a href="{{ route('assets.index', array_merge(request()->all(), ['sort' => 'nama_aset', 'direction' => $sortColumn == 'nama_aset' && $sortDirection == 'asc' ? 'desc' : 'asc'])) }}" style="text-decoration: none; color: inherit; display: flex; align-items: center; gap: 8px;">
                            Nama Aset
                            @if($sortColumn == 'nama_aset')
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    {{ $sortDirection == 'asc' ? '<polyline points="18 15 12 9 6 15"></polyline>' : '<polyline points="6 9 12 15 18 9"></polyline>' }}
                                </svg>
                            @endif
                        </a>
                    </th>
                    <th style="width: 150px;">
                        <a href="{{ route('assets.index', array_merge(request()->all(), ['sort' => 'jenis_aset', 'direction' => $sortColumn == 'jenis_aset' && $sortDirection == 'asc' ? 'desc' : 'asc'])) }}" style="text-decoration: none; color: inherit; display: flex; align-items: center; gap: 8px;">
                            Jenis Aset
                            @if($sortColumn == 'jenis_aset')
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    {{ $sortDirection == 'asc' ? '<polyline points="18 15 12 9 6 15"></polyline>' : '<polyline points="6 9 12 15 18 9"></polyline>' }}
                                </svg>
                            @endif
                        </a>
                    </th>
                    <th style="width: 100px;">Status</th>
                    <th style="width: 100px;">Prioritas</th>
                    <th style="width: 150px;">Bagian Aset</th>
                    <th style="width: 150px;">Komponen Aset</th>
                    <th style="width: 200px;">Produk yang Digunakan</th>
                    <th style="width: 120px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($processedData as $row)
                <tr style="{{ !empty($row['has_same_asset_below']) ? 'border-bottom: none;' : '' }}">
                    <td style="{{ !empty($row['has_same_asset_below']) ? 'border-bottom: none;' : '' }}">{{ !empty($row['show_asset_name']) ? ($row['kode_assets'] ?? '-') : '' }}</td>
                    <td style="{{ !empty($row['has_same_asset_below']) ? 'border-bottom: none;' : '' }}">{{ !empty($row['show_asset_name']) ? ($row['nama_aset'] ?? '-') : '' }}</td>
                    <td style="{{ !empty($row['has_same_asset_below']) ? 'border-bottom: none;' : '' }}">{{ !empty($row['show_asset_name']) ? ($row['jenis_aset'] ?? '-') : '' }}</td>
                    <td style="{{ !empty($row['has_same_asset_below']) ? 'border-bottom: none;' : '' }}">
                        @if(!empty($row['show_asset_name']))
                            <span class="badge 
                                @if(strtolower($row['status'] ?? '') == 'aktif') badge-success
                                @elseif(strtolower($row['status'] ?? '') == 'breakdown') badge-danger
                                @elseif(strtolower($row['status'] ?? '') == 'perlu maintenance') badge-warning
                                @else badge-secondary
                                @endif">
                                {{ $row['status'] ?? '-' }}
                            </span>
                        @endif
                    </td>
                    <td style="{{ !empty($row['has_same_asset_below']) ? 'border-bottom: none;' : '' }}">
                        @if(!empty($row['show_asset_name']))
                            <span class="badge 
                                @if(strtolower($row['mt_priority'] ?? '') == 'high') badge-danger
                                @elseif(strtolower($row['mt_priority'] ?? '') == 'medium') badge-warning
                                @elseif(strtolower($row['mt_priority'] ?? '') == 'low') badge-info
                                @else badge-secondary
                                @endif">
                                {{ $row['mt_priority'] ?? '-' }}
                            </span>
                        @endif
                    </td>
                    <td style="{{ !empty($row['bagian_aset_has_border']) ? 'border-top: 1px solid #d0d0d0;' : '' }}">{{ $row['bagian_aset'] ?? '-' }}</td>
                    <td style="{{ !empty($row['komponen_aset_has_border']) ? 'border-top: 1px solid #d0d0d0;' : '' }}">{{ $row['komponen_aset'] ?? '-' }}</td>
                    <td style="{{ !empty($row['produk_has_border']) ? 'border-top: 1px solid #d0d0d0;' : '' }}">{{ $row['produk_yang_digunakan'] ?? '-' }}</td>
                    <td style="{{ !empty($row['has_same_asset_below']) ? 'border-bottom: none;' : '' }}">
                        @if(!empty($row['show_aksi']))
                            <div style="display: flex; gap: 8px; align-items: center;">
                                <a href="{{ route('assets.edit', $row['id']) }}" class="btn btn-sm" style="background-color: #0A9C5D; color: white; text-decoration: none; padding: 6px 12px; font-size: 12px; height: 32px; display: inline-flex; align-items: center; box-sizing: border-box;">Edit</a>
                                <form action="{{ route('assets.destroy', $row['id']) }}" method="POST" style="display: inline; margin: 0;" onsubmit="return confirmDelete('Apakah Anda yakin ingin menghapus asset ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" style="padding: 6px 12px; font-size: 12px; height: 32px; display: inline-flex; align-items: center; box-sizing: border-box;">Hapus</button>
                                </form>
                            </div>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" style="text-align: center; padding: 40px; color: #666;">
                        Tidak ada data ditemukan
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

