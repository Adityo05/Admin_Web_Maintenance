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
        <a href="{{ route('assets.create') }}" class="btn btn-primary" style="width: auto; padding: 8px 20px; height: auto; display: flex; align-items: center; gap: 8px;">
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
                    <th style="width: 60px;">No</th>
                    <th style="width: 180px;">
                        <a href="{{ route('assets.index', array_merge(request()->all(), ['sort' => 'nama_aset', 'direction' => $sortColumn == 'nama_aset' && $sortDirection == 'asc' ? 'desc' : 'asc'])) }}" style="text-decoration: none; color: inherit; display: flex; align-items: center; gap: 8px;">
                            Nama Aset
                            @if($sortColumn == 'nama_aset')
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    {{ $sortDirection == 'asc' ? '<polyline points="18 15 12 9 6 15"></polyline>' : '<polyline points="6 9 12 15 18 9"></polyline>' }}
                                </svg>
                            @endif
                        </a>
                    </th>
                    <th style="width: 120px;">
                        <a href="{{ route('assets.index', array_merge(request()->all(), ['sort' => 'kode_assets', 'direction' => $sortColumn == 'kode_assets' && $sortDirection == 'asc' ? 'desc' : 'asc'])) }}" style="text-decoration: none; color: inherit; display: flex; align-items: center; gap: 8px;">
                            Kode Aset
                            @if($sortColumn == 'kode_assets')
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
                    <th style="width: 200px;">Maintenance Terakhir</th>
                    <th style="width: 200px;">Maintenance Selanjutnya</th>
                    <th style="width: 150px;">Bagian Aset</th>
                    <th style="width: 150px;">Komponen Aset</th>
                    <th style="width: 200px;">Spesifikasi</th>
                    <th style="width: 250px;">Gambar Aset</th>
                    <th style="width: 200px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($processedData as $index => $row)
                <tr>
                    @if(!empty($row['show_asset_name']))
                    <td rowspan="{{ $row['asset_rowspan'] }}">{{ $index + 1 }}</td>
                    <td rowspan="{{ $row['asset_rowspan'] }}">{{ $row['nama_aset'] ?? '-' }}</td>
                    <td rowspan="{{ $row['asset_rowspan'] }}">{{ $row['kode_assets'] ?? '-' }}</td>
                    <td rowspan="{{ $row['asset_rowspan'] }}">{{ $row['jenis_aset'] ?? '-' }}</td>
                    <td rowspan="{{ $row['asset_rowspan'] }}">
                        <span class="badge 
                            @if(strtolower($row['status'] ?? '') == 'aktif') badge-success
                            @elseif(strtolower($row['status'] ?? '') == 'breakdown') badge-danger
                            @elseif(strtolower($row['status'] ?? '') == 'perlu maintenance') badge-warning
                            @else badge-secondary
                            @endif">
                            {{ $row['status'] ?? '-' }}
                        </span>
                    </td>
                    <td rowspan="{{ $row['asset_rowspan'] }}">
                        <span class="badge 
                            @if(strtolower($row['mt_priority'] ?? '') == 'high') badge-danger
                            @elseif(strtolower($row['mt_priority'] ?? '') == 'medium') badge-warning
                            @elseif(strtolower($row['mt_priority'] ?? '') == 'low') badge-info
                            @else badge-secondary
                            @endif">
                            {{ strtoupper($row['mt_priority'] ?? '-') }}
                        </span>
                    </td>
                    <td rowspan="{{ $row['asset_rowspan'] }}">{{ $row['maintenance_terakhir'] ?? '-' }}</td>
                    <td rowspan="{{ $row['asset_rowspan'] }}">{{ $row['maintenance_selanjutnya'] ?? '-' }}</td>
                    @endif
                    
                    @if(!empty($row['show_bagian_aset']))
                    <td rowspan="{{ $row['bagian_rowspan'] }}" style="border-right: 1px solid #d0d0d0; border-bottom: 1px solid #d0d0d0;">{{ $row['bagian_aset'] ?? '-' }}</td>
                    @endif
                    
                    <td style="border-right: 1px solid #d0d0d0; border-bottom: 1px solid #d0d0d0;">{{ $row['komponen_aset'] ?? '-' }}</td>
                    <td style="border-right: 1px solid #d0d0d0; border-bottom: 1px solid #d0d0d0;">{{ $row['produk_yang_digunakan'] ?? '-' }}</td>
                    
                    @if(!empty($row['show_asset_name']))
                    <td rowspan="{{ $row['asset_rowspan'] }}" style="text-align: center; padding: 8px;">
                        @if($row['foto'])
                            <img src="{{ asset('storage/' . $row['foto']) }}" alt="Foto Aset" style="width: 220px; height: 130px; object-fit: cover; border-radius: 6px; cursor: pointer; box-shadow: 0 2px 4px rgba(0,0,0,0.1);" onclick="showImagePreview('{{ asset('storage/' . $row['foto']) }}')">
                        @else
                            <div style="width: 220px; height: 130px; background-color: #f0f0f0; border-radius: 6px; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#999" stroke-width="2">
                                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                    <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                    <polyline points="21 15 16 10 5 21"></polyline>
                                </svg>
                            </div>
                        @endif
                    </td>
                    <td rowspan="{{ $row['asset_rowspan'] }}">
                        <div style="display: flex; gap: 8px; align-items: center; justify-content: center;">
                            <button onclick="window.location.href='{{ route('assets.edit', $row['id']) }}'" style="background-color: #2196F3; color: white; border: none; border-radius: 6px; padding: 6px 10px; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; transition: all 0.2s;" onmouseover="this.style.transform='scale(1.1)'; this.style.boxShadow='0 2px 8px rgba(33,150,243,0.3)'" onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='none'" title="Edit">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                </svg>
                            </button>
                            <form action="{{ route('assets.destroy', $row['id']) }}" method="POST" style="display: inline; margin: 0;" onsubmit="return confirmDelete('Apakah Anda yakin ingin menghapus asset ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background-color: #F44336; color: white; border: none; border-radius: 6px; padding: 6px 10px; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; transition: all 0.2s;" onmouseover="this.style.transform='scale(1.1)'; this.style.boxShadow='0 2px 8px rgba(244,67,54,0.3)'" onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='none'" title="Hapus">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="3 6 5 6 21 6"></polyline>
                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                    @endif
                </tr>
                @empty
                <tr>
                    <td colspan="13" style="text-align: center; padding: 40px; color: #666;">
                        Tidak ada data ditemukan
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Image Preview Modal -->
<div id="imagePreviewModal" style="display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.8); align-items: center; justify-content: center;">
    <div style="position: relative; max-width: 90%; max-height: 90%;">
        <img id="previewImage" src="" alt="Preview" style="max-width: 100%; max-height: 90vh; border-radius: 8px;">
        <button onclick="closeImagePreview()" style="position: absolute; top: -40px; right: 0; background: white; border: none; border-radius: 50%; width: 36px; height: 36px; cursor: pointer; font-size: 24px; line-height: 1; color: #333;">×</button>
    </div>
</div>

<script>
function showImagePreview(imageUrl) {
    document.getElementById('previewImage').src = imageUrl;
    document.getElementById('imagePreviewModal').style.display = 'flex';
}

function closeImagePreview() {
    document.getElementById('imagePreviewModal').style.display = 'none';
}

// Close modal when clicking outside image
document.getElementById('imagePreviewModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeImagePreview();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeImagePreview();
    }
});
</script>
@endsection

