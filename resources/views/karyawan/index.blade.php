@extends('layouts.app')

@section('title', 'Daftar Karyawan')

@section('content')
<div style="margin-bottom: 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h1 style="font-size: 24px; font-weight: bold; color: #022415;">Daftar Karyawan</h1>
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
<form method="GET" action="{{ route('karyawan.index') }}" style="margin-bottom: 20px;">
    <div style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
        <div style="flex: 1; min-width: 250px; position: relative;">
            <input 
                type="text" 
                name="search" 
                value="{{ $searchQuery }}" 
                placeholder="Cari karyawan..." 
                class="table-search"
                style="padding-left: 40px;"
            >
            <svg style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); width: 20px; height: 20px; color: #0A9C5D;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"></circle>
                <path d="m21 21-4.35-4.35"></path>
            </svg>
        </div>
        <select name="mesin" class="table-search" style="width: 200px;">
            <option value="">Semua Mesin</option>
            @foreach($mesinList as $mesin)
                <option value="{{ $mesin }}" {{ $filterMesin == $mesin ? 'selected' : '' }}>{{ $mesin }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-primary" style="width: auto; padding: 8px 20px; height: auto;">Filter</button>
        <a href="{{ route('karyawan.create') }}" class="btn btn-primary" style="width: auto; padding: 8px 20px; height: auto; display: flex; align-items: center; gap: 8px;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Tambah
        </a>
    </div>
    @if($filterMesin)
    <div style="margin-top: 8px;">
        <span class="badge badge-primary" style="display: inline-flex; align-items: center; gap: 8px;">
            Mesin: {{ $filterMesin }}
            <a href="{{ route('karyawan.index', ['search' => $searchQuery]) }}" style="color: inherit;">×</a>
        </span>
    </div>
    @endif
</form>

<!-- Table -->
<div class="table-container">
    <div class="table-scroll-wrapper">
        <table class="table" style="border-collapse: collapse;">
            <thead>
                <tr style="background: linear-gradient(135deg, #0A9C5D 0%, #088A52 100%); box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <th style="width: 60px; color: white; font-weight: bold; font-size: 13px; padding: 12px 8px; border-right: 0.5px solid rgba(255,255,255,0.2);">NO</th>
                    <th style="color: white; font-weight: bold; font-size: 13px; padding: 12px 8px; border-right: 0.5px solid rgba(255,255,255,0.2);">NAMA PETUGAS</th>
                    <th style="color: white; font-weight: bold; font-size: 13px; padding: 12px 8px; border-right: 0.5px solid rgba(255,255,255,0.2);">JABATAN</th>
                    <th style="color: white; font-weight: bold; font-size: 13px; padding: 12px 8px; border-right: 0.5px solid rgba(255,255,255,0.2);">MESIN YANG DIKERJAKAN</th>
                    <th style="width: 150px; color: white; font-weight: bold; font-size: 13px; padding: 12px 8px; border-right: 0.5px solid rgba(255,255,255,0.2);">NOMOR TELEPON</th>
                    <th style="color: white; font-weight: bold; font-size: 13px; padding: 12px 8px; border-right: 0.5px solid rgba(255,255,255,0.2);">ALAMAT EMAIL</th>
                    <th style="color: white; font-weight: bold; font-size: 13px; padding: 12px 8px; border-right: 0.5px solid rgba(255,255,255,0.2);">PASSWORD</th>
                    <th style="width: 150px; color: white; font-weight: bold; font-size: 13px; padding: 12px 8px;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($karyawanData as $index => $row)
                <tr style="background-color: {{ $index % 2 == 0 ? '#ffffff' : '#f9f9f9' }}; transition: all 0.2s;" onmouseover="this.style.backgroundColor='rgba(10, 156, 93, 0.1)'" onmouseout="this.style.backgroundColor='{{ $index % 2 == 0 ? '#ffffff' : '#f9f9f9' }}'">
                    <td style="text-align: center; padding: 8px; border-right: 0.5px solid #e0e0e0; border-bottom: 0.5px solid #e0e0e0; font-size: 12px; color: #666;">{{ $index + 1 }}</td>
                    <td style="padding: 8px; border-right: 0.5px solid #e0e0e0; border-bottom: 0.5px solid #e0e0e0; font-size: 12px; color: #333;">{{ $row['nama'] }}</td>
                    <td style="padding: 8px; border-right: 0.5px solid #e0e0e0; border-bottom: 0.5px solid #e0e0e0; font-size: 12px; color: #333; text-align: center;">{{ $row['jabatan'] }}</td>
                    <td style="padding: 8px; border-right: 0.5px solid #e0e0e0; border-bottom: 0.5px solid #e0e0e0; font-size: 12px; color: #333; text-align: center;">{{ $row['mesin'] }}</td>
                    <td style="padding: 8px; border-right: 0.5px solid #e0e0e0; border-bottom: 0.5px solid #e0e0e0; font-size: 12px; color: #333; text-align: center;">{{ $row['telp'] }}</td>
                    <td style="padding: 8px; border-right: 0.5px solid #e0e0e0; border-bottom: 0.5px solid #e0e0e0; font-size: 12px; color: #333; text-align: center;">{{ $row['email'] }}</td>
                    <td style="padding: 8px; border-right: 0.5px solid #e0e0e0; border-bottom: 0.5px solid #e0e0e0; font-size: 12px; color: #333; text-align: center;">••••••••</td>
                    <td style="padding: 8px; border-bottom: 0.5px solid #e0e0e0;">
                        <div style="display: flex; gap: 8px; align-items: center; justify-content: center;">
                            <button onclick="window.location.href='{{ route('karyawan.edit', $row['id']) }}'" style="background-color: #2196F3; color: white; border: none; border-radius: 6px; padding: 6px 10px; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; transition: all 0.2s;" onmouseover="this.style.transform='scale(1.1)'; this.style.boxShadow='0 2px 8px rgba(33,150,243,0.3)'" onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='none'" title="Edit">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                </svg>
                            </button>
                            <form action="{{ route('karyawan.destroy', $row['id']) }}" method="POST" style="display: inline; margin: 0;" onsubmit="return confirmDelete('Apakah Anda yakin ingin menghapus karyawan {{ $row['nama'] }}?')">
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
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 40px; color: #666;">
                        <div style="display: flex; flex-direction: column; align-items: center; gap: 16px;">
                            <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="#999" stroke-width="1">
                                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                            </svg>
                            <div>
                                <div style="font-size: 18px; font-weight: 600; color: #666; margin-bottom: 8px;">Tidak ada data karyawan</div>
                                <div style="font-size: 14px; color: #999;">Mulai dengan menambahkan data karyawan baru</div>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
