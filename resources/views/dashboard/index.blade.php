@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="dashboard-header">
    <h1 class="dashboard-title">Dashboard</h1>
    <div class="dashboard-actions">
        <button class="btn-icon" onclick="location.reload()" title="Refresh Data">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="23 4 23 10 17 10"></polyline>
                <polyline points="1 20 1 14 7 14"></polyline>
                <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path>
            </svg>
        </button>
        <span style="font-size: 16px; color: #666;">{{ $currentDate }}</span>
    </div>
</div>

<!-- Statistik Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-card-header">
            <span class="stat-card-title">Total Assets</span>
            <div class="stat-card-icon primary">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="9" y1="3" x2="9" y2="21"></line>
                </svg>
            </div>
        </div>
        <div class="stat-card-value">{{ $stats['totalAssets'] }}</div>
    </div>
    
    <div class="stat-card">
        <div class="stat-card-header">
            <span class="stat-card-title">Total Karyawan</span>
            <div class="stat-card-icon secondary">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                </svg>
            </div>
        </div>
        <div class="stat-card-value">{{ $stats['totalKaryawan'] }}</div>
    </div>
    
    <div class="stat-card">
        <div class="stat-card-header">
            <span class="stat-card-title">Pending Requests</span>
            <div class="stat-card-icon warning">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
            </div>
        </div>
        <div class="stat-card-value">{{ $stats['pendingRequests'] }}</div>
    </div>
    
    <div class="stat-card">
        <div class="stat-card-header">
            <span class="stat-card-title">Active Maintenance</span>
            <div class="stat-card-icon info">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
            </div>
        </div>
        <div class="stat-card-value">{{ $stats['activeMaintenance'] }}</div>
    </div>
</div>

<!-- Menu Utama -->
<div style="margin-bottom: 30px;">
    <h2 style="font-size: 20px; font-weight: bold; color: #022415; margin-bottom: 15px;">Menu Utama</h2>
    <div class="stats-grid">
        <a href="{{ route('assets.index') }}" class="stat-card" style="text-decoration: none; color: inherit; cursor: pointer;">
            <div class="stat-card-header">
                <span class="stat-card-title">Data Assets</span>
                <div class="stat-card-icon primary">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="9" y1="3" x2="9" y2="21"></line>
                    </svg>
                </div>
            </div>
            <div style="font-size: 16px; color: #666; margin-top: 8px;">Kelola data mesin dan assets</div>
        </a>
        
        <a href="{{ route('karyawan.index') }}" class="stat-card" style="text-decoration: none; color: inherit; cursor: pointer;">
            <div class="stat-card-header">
                <span class="stat-card-title">Daftar Karyawan</span>
                <div class="stat-card-icon secondary">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                </div>
            </div>
            <div style="font-size: 16px; color: #666; margin-top: 8px;">Kelola data karyawan</div>
        </a>
        
        <a href="{{ route('maintenance-template.index') }}" class="stat-card" style="text-decoration: none; color: inherit; cursor: pointer;">
            <div class="stat-card-header">
                <span class="stat-card-title">Maintenance Schedule</span>
                <div class="stat-card-icon warning">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path>
                    </svg>
                </div>
            </div>
            <div style="font-size: 16px; color: #666; margin-top: 8px;">Jadwal maintenance mesin</div>
        </a>
        
        <a href="{{ route('check-sheet-template.index') }}" class="stat-card" style="text-decoration: none; color: inherit; cursor: pointer;">
            <div class="stat-card-header">
                <span class="stat-card-title">Cek Sheet Schedule</span>
                <div class="stat-card-icon info">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 11l3 3L22 4"></path>
                        <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                    </svg>
                </div>
            </div>
            <div style="font-size: 16px; color: #666; margin-top: 8px;">Jadwal cek sheet</div>
        </a>
    </div>
</div>

<!-- Recent Activities -->
<div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);">
    <h2 style="font-size: 20px; font-weight: bold; color: #022415; margin-bottom: 20px;">Aktivitas Terkini</h2>
    <div style="display: flex; flex-direction: column; gap: 12px;">
        @foreach($requestHistory as $history)
        <div style="padding: 12px; background: #f5f5f5; border-radius: 8px;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <div style="font-weight: 600; color: #022415;">{{ $history['title'] }}</div>
                    <div style="font-size: 14px; color: #666; margin-top: 4px;">{{ $history['date'] }}</div>
                </div>
                <span class="badge {{ $history['status'] === 'Disetujui' ? 'badge-success' : 'badge-danger' }}">
                    {{ $history['status'] }}
                </span>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection

