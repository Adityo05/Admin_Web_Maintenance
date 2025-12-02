@extends('layouts.app')

@section('title', 'Tambah Cek Sheet Schedule')

@section('content')
<div class="page-header">
    <h1>Tambah Cek Sheet Schedule</h1>
</div>

@if(session('error'))
    <div class="alert alert-error">{{ session('error') }}</div>
@endif

<div class="card" style="max-width: 900px; margin: 0 auto;">
    <div style="display: flex; align-items: center; gap: 16px; padding-bottom: 20px; border-bottom: 1px solid #e0e0e0; margin-bottom: 28px;">
        <div style="width: 48px; height: 48px; background: rgba(10, 156, 93, 0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#0a9c5d" stroke-width="2">
                <path d="M9 11l3 3L22 4"></path>
                <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
            </svg>
        </div>
        <div>
            <h2 style="margin: 0; font-size: 20px; font-weight: 700; color: #022415;">Tambah Cek Sheet Schedule</h2>
            <p style="margin: 4px 0 0; color: #666; font-size: 13px;">Isi form di bawah ini untuk menambahkan schedule baru</p>
        </div>
    </div>

    <form method="POST" action="{{ route('check-sheet-template.store') }}" class="form" id="checkSheetForm">
        @csrf
        
        <div class="form-group">
            <label for="assets_id">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 6px;">
                    <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                    <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                </svg>
                Pilih Asset/Infrastruktur <span class="required">*</span>
            </label>
            <select name="assets_id" id="assets_id" class="form-select @error('assets_id') error @enderror" required>
                <option value="">Pilih asset</option>
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

        <div id="komponenContainer" style="display: none;">
            <div class="form-group">
                <label for="komponen_assets_id">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 6px;">
                        <circle cx="12" cy="12" r="3"></circle>
                        <path d="M12 1v6m0 6v6"></path>
                        <path d="m4.93 4.93 4.24 4.24m5.66 5.66 4.24 4.24"></path>
                        <path d="m19.07 4.93-4.24 4.24m-5.66 5.66-4.24 4.24"></path>
                    </svg>
                    Nama Komponen <span class="required">*</span>
                </label>
                <select name="komponen_assets_id" id="komponen_assets_id" class="form-select @error('komponen_assets_id') error @enderror">
                    <option value="">Pilih komponen</option>
                </select>
                <div id="komponenLoading" style="display: none; padding: 12px; background: #f0f9f4; border-radius: 8px; margin-top: 8px; color: #0a9c5d; font-size: 13px;">
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
                    Memuat komponen dari asset...
                </div>
                @error('komponen_assets_id')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
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
                        <option value="Harian" {{ old('periode') == 'Harian' ? 'selected' : '' }}>Harian</option>
                        <option value="Mingguan" {{ old('periode', 'Mingguan') == 'Mingguan' ? 'selected' : '' }}>Mingguan</option>
                        <option value="Bulanan" {{ old('periode') == 'Bulanan' ? 'selected' : '' }}>Bulanan</option>
                    </select>
                    @error('periode')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="interval_periode">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 6px;">
                            <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                            <polyline points="17 6 23 6 23 12"></polyline>
                        </svg>
                        Interval <span class="required">*</span>
                    </label>
                    <div class="interval-box">
                        <button type="button" onclick="decreaseInterval()" class="interval-btn interval-btn-decrease">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                        </button>
                        <div style="flex: 1; text-align: center;">
                            <input type="number" name="interval_periode" id="interval_periode" 
                                   class="interval-input @error('interval_periode') error @enderror" 
                                   value="{{ old('interval_periode', 1) }}" 
                                   min="1" 
                                   required>
                            <div class="interval-label">
                                Per <span id="intervalValue">1</span> <span id="intervalUnit">Minggu</span>
                            </div>
                        </div>
                        <button type="button" onclick="increaseInterval()" class="interval-btn interval-btn-increase">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                        </button>
                    </div>
                    @error('interval_periode')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="jenis_pekerjaan">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 6px;">
                        <path d="M20 7h-9"></path>
                        <path d="M14 17H5"></path>
                        <circle cx="17" cy="17" r="3"></circle>
                        <circle cx="7" cy="7" r="3"></circle>
                    </svg>
                    Jenis Pekerjaan <span class="required">*</span>
                </label>
                <input type="text" name="jenis_pekerjaan" id="jenis_pekerjaan" 
                       class="form-input @error('jenis_pekerjaan') error @enderror" 
                       value="{{ old('jenis_pekerjaan') }}" 
                       placeholder="Contoh: Pengecekan dan pembersihan"
                       required>
                @error('jenis_pekerjaan')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="std_prwtn">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 6px;">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                    Standar Perawatan <span class="required">*</span>
                </label>
                <textarea name="std_prwtn" id="std_prwtn" 
                          class="form-textarea @error('std_prwtn') error @enderror" 
                          rows="3" 
                          placeholder="Deskripsi standar perawatan yang harus dilakukan"
                          required>{{ old('std_prwtn') }}</textarea>
                @error('std_prwtn')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="alat_bahan">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 6px;">
                        <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path>
                    </svg>
                    Alat dan Bahan <span class="required">*</span>
                </label>
                <textarea name="alat_bahan" id="alat_bahan" 
                          class="form-textarea @error('alat_bahan') error @enderror" 
                          rows="3" 
                          placeholder="Daftar alat dan bahan yang diperlukan untuk perawatan"
                          required>{{ old('alat_bahan') }}</textarea>
                @error('alat_bahan')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-actions" style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 32px; padding-top: 24px; border-top: 1px solid #e0e0e0;">
            <a href="{{ route('check-sheet-template.index') }}" class="btn btn-outline">
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

.interval-box {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    background: white;
    border: 1px solid #e0e0e0;
    border-radius: 12px;
    transition: all 0.2s;
}

.interval-box:focus-within {
    border-color: #0a9c5d;
    box-shadow: 0 0 0 3px rgba(10, 156, 93, 0.1);
}

.interval-input {
    width: 80px;
    text-align: center;
    font-weight: 700;
    font-size: 20px;
    color: #0a9c5d;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    padding: 8px;
    margin: 0 auto;
    display: block;
}

.interval-input:focus {
    outline: none;
    border-color: #0a9c5d;
}

.interval-btn {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
    flex-shrink: 0;
}

.interval-btn-decrease {
    background: #f44336;
    color: white;
}

.interval-btn-decrease:hover:not(:disabled) {
    background: #d32f2f;
    transform: scale(1.05);
}

.interval-btn-decrease:disabled {
    background: #e0e0e0;
    cursor: not-allowed;
    opacity: 0.6;
}

.interval-btn-increase {
    background: #0a9c5d;
    color: white;
}

.interval-btn-increase:hover {
    background: #088c51;
    transform: scale(1.05);
}

.interval-label {
    margin-top: 8px;
    color: #666;
    font-size: 13px;
    font-weight: 500;
}

.interval-label span {
    color: #0a9c5d;
    font-weight: 600;
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
    const komponenSelect = document.getElementById('komponen_assets_id');
    const komponenContainer = document.getElementById('komponenContainer');
    const periodeSelect = document.getElementById('periode');
    const intervalInput = document.getElementById('interval_periode');
    const intervalValue = document.getElementById('intervalValue');
    const intervalUnit = document.getElementById('intervalUnit');

    // Load komponen when asset is selected
    assetsSelect.addEventListener('change', async function() {
        const assetId = this.value;
        const loadingDiv = document.getElementById('komponenLoading');
        
        if (!assetId) {
            komponenContainer.style.display = 'none';
            komponenSelect.innerHTML = '<option value="">Pilih komponen</option>';
            return;
        }

        // Show loading
        loadingDiv.style.display = 'block';
        komponenSelect.innerHTML = '<option value="">Memuat...</option>';
        komponenSelect.disabled = true;

        try {
            const response = await fetch(`/check-sheet-template/komponen/${assetId}`);
            const komponen = await response.json();

            komponenSelect.innerHTML = '<option value="">Pilih komponen</option>';
            komponenSelect.disabled = false;
            loadingDiv.style.display = 'none';
            
            if (komponen.length === 0) {
                komponenSelect.innerHTML = '<option value="">Tidak ada komponen</option>';
                alert('⚠️ Asset ini belum memiliki komponen.\n\nSilakan tambahkan komponen terlebih dahulu di menu Data Assets.');
                return;
            }

            komponen.forEach(k => {
                const option = document.createElement('option');
                option.value = k.id;
                option.textContent = k.bagian_mesin ? `${k.bagian_mesin} - ${k.nama_bagian}` : k.nama_bagian;
                komponenSelect.appendChild(option);
            });

            komponenContainer.style.display = 'block';
        } catch (error) {
            console.error('Error loading komponen:', error);
            komponenSelect.innerHTML = '<option value="">Error memuat data</option>';
            komponenSelect.disabled = false;
            loadingDiv.style.display = 'none';
            alert('❌ Gagal memuat komponen. Silakan coba lagi.');
        }
    });

    // Update interval label
    function updateIntervalLabel() {
        const periode = periodeSelect.value;
        const interval = intervalInput.value || '1';
        
        let unit = '';
        switch(periode) {
            case 'Harian': unit = 'Hari'; break;
            case 'Mingguan': unit = 'Minggu'; break;
            case 'Bulanan': unit = 'Bulan'; break;
        }
        
        intervalValue.textContent = interval;
        intervalUnit.textContent = unit;
    }

    periodeSelect.addEventListener('change', updateIntervalLabel);
    intervalInput.addEventListener('input', function() {
        updateIntervalLabel();
        updateDecreaseButtonState();
    });

    // Initialize
    updateIntervalLabel();
    updateDecreaseButtonState();
});

function increaseInterval() {
    const input = document.getElementById('interval_periode');
    input.value = parseInt(input.value || 1) + 1;
    input.dispatchEvent(new Event('input'));
}

function decreaseInterval() {
    const input = document.getElementById('interval_periode');
    const currentValue = parseInt(input.value || 1);
    if (currentValue > 1) {
        input.value = currentValue - 1;
        input.dispatchEvent(new Event('input'));
    }
}

function updateDecreaseButtonState() {
    const input = document.getElementById('interval_periode');
    const decreaseBtn = document.querySelector('.interval-btn-decrease');
    decreaseBtn.disabled = parseInt(input.value || 1) <= 1;
}
</script>
@endsection
