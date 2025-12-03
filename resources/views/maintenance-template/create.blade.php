@extends('layouts.app')

@section('title', 'Tambah Maintenance Schedule')

@section('content')
<div class="page-header">
    <h1>Tambah Maintenance Schedule</h1>
</div>

@if(session('error'))
    <div class="alert alert-error">{{ session('error') }}</div>
@endif

<div class="card" style="max-width: 900px; margin: 0 auto;">
    <div style="display: flex; align-items: center; gap: 16px; padding-bottom: 20px; border-bottom: 1px solid #e0e0e0; margin-bottom: 28px;">
        <div style="width: 48px; height: 48px; background: rgba(10, 156, 93, 0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#0a9c5d" stroke-width="2">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                <line x1="16" y1="2" x2="16" y2="6"></line>
                <line x1="8" y1="2" x2="8" y2="6"></line>
                <line x1="3" y1="10" x2="21" y2="10"></line>
            </svg>
        </div>
        <div>
            <h2 style="margin: 0; font-size: 20px; font-weight: 700; color: #022415;">Tambah Maintenance Schedule</h2>
            <p style="margin: 4px 0 0; color: #666; font-size: 13px;">Isi form di bawah ini untuk menambahkan schedule baru</p>
        </div>
    </div>

    <form method="POST" action="{{ route('maintenance-template.store') }}" class="form" id="maintenanceForm">
        @csrf
        
        <div class="form-group">
            <label for="assets_id">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 6px;">
                    <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                    <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                </svg>
                Pilih Mesin <span class="required">*</span>
            </label>
            <select name="assets_id" id="assets_id" class="form-select @error('assets_id') error @enderror" required>
                <option value="">Pilih mesin</option>
                @foreach($assets as $asset)
                    <option value="{{ $asset->id }}" {{ old('assets_id') == $asset->id ? 'selected' : '' }}>
                        {{ $asset->nama_assets }}
                    </option>
                @endforeach
            </select>
            @error('assets_id')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div id="bagianContainer" style="display: none;">
            <div class="form-group">
                <label for="bg_mesin_id">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 6px;">
                        <circle cx="12" cy="12" r="3"></circle>
                        <path d="M12 1v6m0 6v6"></path>
                        <path d="m4.93 4.93 4.24 4.24m5.66 5.66 4.24 4.24"></path>
                        <path d="m19.07 4.93-4.24 4.24m-5.66 5.66-4.24 4.24"></path>
                    </svg>
                    Bagian Mesin <span class="required">*</span>
                </label>
                <select name="bg_mesin_id" id="bg_mesin_id" class="form-select @error('bg_mesin_id') error @enderror" required>
                    <option value="">Pilih bagian mesin</option>
                </select>
                <div id="bagianLoading" style="display: none; padding: 12px; background: #f0f9f4; border-radius: 8px; margin-top: 8px; color: #0a9c5d; font-size: 13px;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="animation: spin 1s linear infinite; vertical-align: middle; margin-right: 8px;">
                        <line x1="12" y1="2" x2="12" y2="6"></line>
                        <line x1="12" y1="18" x2="12" y2="22"></line>
                        <line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line>
                        <line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line>
                        <line x1="2" y1="12" x2="6" y2="12"></line>
                        <line x1="18" y1="12" x2="22" y2="12"></line>
                        <line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line>
                        <line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line>
                    </svg>
                    Memuat bagian mesin...
                </div>
                @error('bg_mesin_id')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #333; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 6px; color: #0a9c5d;">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                    LIFT TIME MESIN / HARI
                </label>
            </div>

            <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label for="interval_periode">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 6px;">
                            <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                            <polyline points="17 6 23 6 23 12"></polyline>
                        </svg>
                        Interval <span class="required">*</span>
                    </label>
                    <input type="number" name="interval_periode" id="interval_periode" 
                           class="form-input @error('interval_periode') error @enderror" 
                           value="{{ old('interval_periode', 1) }}" 
                           min="1" 
                           placeholder="Contoh: 7"
                           required>
                    @error('interval_periode')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="periode">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 6px;">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                        Periode <span class="required">*</span>
                    </label>
                    <select name="periode" id="periode" class="form-select @error('periode') error @enderror" required>
                        <option value="Hari" {{ old('periode') == 'Hari' ? 'selected' : '' }}>Harian</option>
                        <option value="Minggu" {{ old('periode', 'Minggu') == 'Minggu' ? 'selected' : '' }}>Mingguan</option>
                        <option value="Bulan" {{ old('periode') == 'Bulan' ? 'selected' : '' }}>Bulanan</option>
                    </select>
                    @error('periode')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="tanggal_mulai">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 6px;">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                    Tanggal
                </label>
                <input type="date" name="tanggal_mulai" id="tanggal_mulai" 
                       class="form-input @error('tanggal_mulai') error @enderror" 
                       value="{{ old('tanggal_mulai', date('Y-m-d')) }}">
                @error('tanggal_mulai')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-actions" style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 32px; padding-top: 24px; border-top: 1px solid #e0e0e0;">
            <a href="{{ route('maintenance-template.index') }}" class="btn btn-outline">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 6px;">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Batal
            </a>
            <button type="submit" class="btn btn-primary" style="min-width: 140px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 6px;">
                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                    <polyline points="17 21 17 13 7 13 7 21"></polyline>
                    <polyline points="7 3 7 8 15 8"></polyline>
                </svg>
                Simpan
            </button>
        </div>
    </form>
</div>

<style>
@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #333;
    font-size: 14px;
}

.form-group label svg {
    color: #0a9c5d;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const assetsSelect = document.getElementById('assets_id');
    const bagianSelect = document.getElementById('bg_mesin_id');
    const bagianContainer = document.getElementById('bagianContainer');

    // Load bagian mesin when asset is selected
    assetsSelect.addEventListener('change', async function() {
        const assetId = this.value;
        const loadingDiv = document.getElementById('bagianLoading');
        
        if (!assetId) {
            bagianContainer.style.display = 'none';
            bagianSelect.innerHTML = '<option value="">Pilih bagian mesin</option>';
            return;
        }

        // Show loading
        loadingDiv.style.display = 'block';
        bagianSelect.innerHTML = '<option value="">Memuat...</option>';
        bagianSelect.disabled = true;

        try {
            const response = await fetch(`/maintenance-template/bagian-mesin/${assetId}`);
            const bagianMesin = await response.json();

            bagianSelect.innerHTML = '<option value="">Pilih bagian mesin</option>';
            bagianSelect.disabled = false;
            loadingDiv.style.display = 'none';
            
            if (bagianMesin.length === 0) {
                bagianSelect.innerHTML = '<option value="">Tidak ada bagian mesin</option>';
                alert('⚠️ Mesin ini belum memiliki bagian.\n\nSilakan tambahkan bagian mesin terlebih dahulu di menu Data Assets.');
                return;
            }

            bagianMesin.forEach(b => {
                const option = document.createElement('option');
                option.value = b.id;
                option.textContent = b.nama_bagian;
                bagianSelect.appendChild(option);
            });

            bagianContainer.style.display = 'block';
        } catch (error) {
            console.error('Error loading bagian mesin:', error);
            bagianSelect.innerHTML = '<option value="">Error memuat data</option>';
            bagianSelect.disabled = false;
            loadingDiv.style.display = 'none';
            alert('❌ Gagal memuat bagian mesin. Silakan coba lagi.');
        }
    });
});
</script>
@endsection
