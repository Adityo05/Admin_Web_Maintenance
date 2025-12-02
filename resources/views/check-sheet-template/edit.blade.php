@extends('layouts.app')

@section('title', 'Edit Cek Sheet Schedule')

@section('content')
<div class="page-header">
    <h1>Edit Cek Sheet Schedule</h1>
</div>

@if(session('error'))
    <div class="alert alert-error">{{ session('error') }}</div>
@endif

<div class="card">
    <div style="padding: 0 0 20px 0; border-bottom: 1px solid #e0e0e0; margin-bottom: 24px;">
        <p style="color: #666; font-size: 14px; margin: 0;">Edit informasi schedule cek sheet</p>
    </div>

    <form method="POST" action="{{ route('check-sheet-template.update', $template->id) }}" class="form" id="checkSheetForm">
        @csrf
        @method('PUT')
        
        {{-- Display Asset Info (Read only) --}}
        @if($template->komponenAsset && $template->komponenAsset->asset)
        <div class="form-group">
            <label>Nama Infrastruktur</label>
            <div style="padding: 12px; background: #f5f5f5; border-radius: 8px; border: 1px solid #ddd;">
                <strong>{{ $template->komponenAsset->asset->nama_assets }}</strong>
            </div>
        </div>
        @endif

        <div class="form-group">
            <label for="komponen_assets_id">Nama Komponen <span class="required">*</span></label>
            <select name="komponen_assets_id" id="komponen_assets_id" class="form-select @error('komponen_assets_id') error @enderror" required>
                @if($template->komponenAsset)
                <option value="{{ $template->komponenAsset->id }}" selected>
                    {{ $template->komponenAsset->bagianMesin ? $template->komponenAsset->bagianMesin->nama_bagian . ' - ' : '' }}{{ $template->komponenAsset->nama_bagian }}
                </option>
                @endif
            </select>
            @error('komponen_assets_id')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label for="periode">Periode <span class="required">*</span></label>
                <select name="periode" id="periode" class="form-select @error('periode') error @enderror" required>
                    <option value="Harian" {{ old('periode', $template->periode) == 'Harian' ? 'selected' : '' }}>Harian</option>
                    <option value="Mingguan" {{ old('periode', $template->periode) == 'Mingguan' ? 'selected' : '' }}>Mingguan</option>
                    <option value="Bulanan" {{ old('periode', $template->periode) == 'Bulanan' ? 'selected' : '' }}>Bulanan</option>
                </select>
                @error('periode')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="interval_periode">Interval <span class="required">*</span></label>
                <div class="interval-controls">
                    <button type="button" onclick="decreaseInterval()" class="interval-btn interval-btn-decrease">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                    </button>
                    <input type="number" name="interval_periode" id="interval_periode" 
                           class="form-input interval-input @error('interval_periode') error @enderror" 
                           value="{{ old('interval_periode', $template->interval_periode) }}" 
                           min="1" 
                           required>
                    <button type="button" onclick="increaseInterval()" class="interval-btn interval-btn-increase">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                    </button>
                </div>
                <small class="interval-label">Per <span id="intervalValue">{{ $template->interval_periode }}</span> <span id="intervalUnit">Minggu</span></small>
                @error('interval_periode')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="jenis_pekerjaan">Jenis Pekerjaan <span class="required">*</span></label>
            <input type="text" name="jenis_pekerjaan" id="jenis_pekerjaan" 
                   class="form-input @error('jenis_pekerjaan') error @enderror" 
                   value="{{ old('jenis_pekerjaan', $template->jenis_pekerjaan) }}" 
                   required>
            @error('jenis_pekerjaan')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="std_prwtn">Standar Perawatan <span class="required">*</span></label>
            <textarea name="std_prwtn" id="std_prwtn" 
                      class="form-textarea @error('std_prwtn') error @enderror" 
                      rows="3" 
                      required>{{ old('std_prwtn', $template->std_prwtn) }}</textarea>
            @error('std_prwtn')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="alat_bahan">Alat dan Bahan <span class="required">*</span></label>
            <textarea name="alat_bahan" id="alat_bahan" 
                      class="form-textarea @error('alat_bahan') error @enderror" 
                      rows="3" 
                      required>{{ old('alat_bahan', $template->alat_bahan) }}</textarea>
            @error('alat_bahan')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-actions" style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px; padding-top: 24px; border-top: 1px solid #e0e0e0;">
            <a href="{{ route('check-sheet-template.index') }}" class="btn btn-outline">Batal</a>
            <button type="submit" class="btn btn-primary" style="min-width: 120px;">Update</button>
        </div>
    </form>
</div>

<style>
.interval-controls {
    display: flex;
    align-items: center;
    gap: 12px;
}

.interval-input {
    text-align: center;
    font-weight: bold;
    font-size: 16px;
    color: #0a9c5d;
    max-width: 100px;
}

.interval-btn {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
}

.interval-btn-decrease {
    background: #f44336;
    color: white;
}

.interval-btn-decrease:hover:not(:disabled) {
    background: #d32f2f;
}

.interval-btn-decrease:disabled {
    background: #ccc;
    cursor: not-allowed;
}

.interval-btn-increase {
    background: #0a9c5d;
    color: white;
}

.interval-btn-increase:hover {
    background: #088c51;
}

.interval-label {
    display: block;
    margin-top: 8px;
    color: #666;
    font-size: 14px;
    font-weight: 500;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const periodeSelect = document.getElementById('periode');
    const intervalInput = document.getElementById('interval_periode');
    const intervalValue = document.getElementById('intervalValue');
    const intervalUnit = document.getElementById('intervalUnit');

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
