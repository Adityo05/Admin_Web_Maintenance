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
        <a href="{{ route('karyawan.create') }}" class="btn btn-primary" style="width: auto; padding: 8px 20px; height: auto; text-decoration: none; display: flex; align-items: center; gap: 8px;">
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
            <a href="{{ route('karyawan.index', ['search' => $searchQuery]) }}" style="color: inherit; text-decoration: none;">×</a>
        </span>
    </div>
    @endif
</form>

<!-- Table -->
<div class="table-container">
    <div style="overflow-x: auto;">
        <table class="table">
            <thead>
                <tr style="background: linear-gradient(135deg, #0A9C5D 0%, #088A52 100%);">
                    <th style="width: 60px; color: white;">NO</th>
                    <th style="color: white;">NAMA PETUGAS</th>
                    <th style="color: white;">JABATAN</th>
                    <th style="color: white;">MESIN YANG DIKERJAKAN</th>
                    <th style="width: 150px; color: white;">NOMOR TELEPON</th>
                    <th style="color: white;">ALAMAT EMAIL</th>
                    <th style="color: white;">PASSWORD</th>
                    <th style="width: 150px; color: white;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($karyawanData as $index => $row)
                <tr style="background-color: {{ $index % 2 == 0 ? '#ffffff' : '#f9f9f9' }};">
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>{{ $row['nama'] }}</td>
                    <td>{{ $row['jabatan'] }}</td>
                    <td>{{ $row['mesin'] }}</td>
                    <td>{{ $row['telp'] }}</td>
                    <td>{{ $row['email'] }}</td>
                    <td>••••••••</td>
                    <td>
                        <div style="display: flex; gap: 8px;">
                            <a href="{{ route('karyawan.edit', $row['id']) }}" class="btn btn-sm" style="background-color: #0A9C5D; color: white; text-decoration: none; padding: 4px 12px; font-size: 12px;">Edit</a>
                            <form action="{{ route('karyawan.destroy', $row['id']) }}" method="POST" style="display: inline;" onsubmit="return confirmDelete('Apakah Anda yakin ingin menghapus karyawan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" style="padding: 4px 12px; font-size: 12px;">Hapus</button>
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
