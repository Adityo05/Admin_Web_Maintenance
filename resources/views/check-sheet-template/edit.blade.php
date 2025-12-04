@extends('layouts.app')

@section('title', 'Edit Cek Sheet Schedule')

@section('content')
<div class="page-header">
    <h1>Edit Cek Sheet Schedule</h1>
</div>

@if(session('error'))
    <div class="alert alert-error">{{ session('error') }}</div>
@endif

<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div style="display: flex; align-items: center; gap: 16px; padding-bottom: 20px; border-bottom: 1px solid #e0e0e0; margin-bottom: 28px;">
        <div style="width: 48px; height: 48px; background: rgba(33, 150, 243, 0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#2196F3" stroke-width="2">
                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
            </svg>
        </div>
        <div>
            <h2 style="margin: 0; font-size: 20px; font-weight: 700; color: #022415;">Edit Bagian Mesin</h2>
            <p style="margin: 4px 0 0; color: #666; font-size: 13px;">Edit detail bagian dari {{ $template->komponenAsset && $template->komponenAsset->asset ? $template->komponenAsset->asset->nama_assets : '' }}</p>
        </div>
    </div>

    <form method="POST" action="{{ route('check-sheet-template.update', $template->id) }}" class="form" id="checkSheetForm">
        @csrf
        @method('PUT')
        
        {{-- Display Asset Info (Read only) --}}
        @if($template->komponenAsset && $template->komponenAsset->asset)
        <div class="form-group">
            <label>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 6px; color: #0a9c5d;">
                    <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                    <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                </svg>
                Nama Infrastruktur
            </label>
            <div style="padding: 16px; background: #f5f5f5; border-radius: 12px; border: 1px solid #ddd;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#666" stroke-width="2">
                        <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                        <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                    </svg>
                    <div style="flex: 1;">
                        <div style="font-size: 12px; color: #666;">Nama Infrastruktur</div>
                        <div style="font-size: 16px; font-weight: 600; color: #333; margin-top: 4px;">{{ $template->komponenAsset->asset->nama_assets }}</div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <div class="form-group">
            <label for="komponen_assets_id">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 6px; color: #0a9c5d;">
                    <circle cx="12" cy="12" r="3"></circle>
                    <path d="M12 1v6m0 6v6"></path>
                </svg>
                Nama Bagian <span class="required">*</span>
            </label>
            <select name="komponen_assets_id" id="komponen_assets_id" class="form-select @error('komponen_assets_id') error @enderror" required disabled style="background: #f5f5f5;">
                @if($template->komponenAsset)
                <option value="{{ $template->komponenAsset->id }}" selected>
                    {{ $template->komponenAsset->bagianMesin ? $template->komponenAsset->bagianMesin->nama_bagian . ' - ' : '' }}{{ $template->komponenAsset->nama_bagian }}
                </option>
                @endif
            </select>
            <input type="hidden" name="komponen_assets_id" value="{{ $template->komponenAsset->id }}">
            @error('komponen_assets_id')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="periode">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 6px; color: #0a9c5d;">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
                Periode <span class="required">*</span>
            </label>
            <select name="periode" id="periode" class="form-select @error('periode') error @enderror" required>
                <option value="Harian" {{ old('periode', $template->periode) == 'Harian' ? 'selected' : '' }}>Harian</option>
                <option value="Mingguan" {{ old('periode', $template->periode) == 'Mingguan' ? 'selected' : '' }}>Mingguan</option>
                <option value="Bulanan" {{ old('periode', $template->periode) == 'Bulanan' ? 'selected' : '' }}>Bulanan</option>
            </select>
            @error('periode')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group" style="margin-top: 16px;">
            <label for="interval_periode">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 6px; color: #0a9c5d;">
                    <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                    <polyline points="17 6 23 6 23 12"></polyline>
                </svg>
                Interval ({{ $template->periode }}) <span class="required">*</span>
            </label>
            <div style="background: linear-gradient(135deg, #ffffff 0%, #f9fafb 100%); border: 2px solid #e0e7ef; border-radius: 14px; padding: 14px 18px; display: flex; align-items: center; gap: 14px; box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04); transition: all 0.3s ease;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#0a9c5d" stroke-width="2" style="filter: drop-shadow(0 1px 2px rgba(10, 156, 93, 0.2));">
                    <path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"></path>
                    <line x1="4" y1="22" x2="4" y2="15"></line>
                </svg>
                <div style="flex: 1; text-align: center;">
                    <div style="font-size: 11px; color: #0a9c5d; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">Interval ({{ $template->periode }})</div>
                    <div style="font-size: 15px; font-weight: 700; color: #2d3748;">
                        Per <span id="intervalValueDisplay" style="color: #0a9c5d;">{{ $template->interval_periode }}</span> <span id="intervalUnitDisplay" style="color: #0a9c5d;">Minggu</span>
                    </div>
                </div>
                <div style="display: flex; gap: 12px;">
                    <button type="button" onclick="decreaseInterval()" class="interval-btn interval-btn-decrease" id="decreaseBtn">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                    </button>
                    <input type="number" name="interval_periode" id="interval_periode" 
                           class="interval-input @error('interval_periode') error @enderror" 
                           value="{{ old('interval_periode', $template->interval_periode) }}" 
                           min="1" 
                           required
                           style="width: 70px; text-align: center; font-weight: 700; font-size: 20px; color: #0a9c5d; border: 2px solid #d1fae5; border-radius: 10px; padding: 10px; background: #f0fdf4;">
                    <button type="button" onclick="increaseInterval()" class="interval-btn interval-btn-increase">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                    </button>
                </div>
            </div>
            @error('interval_periode')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group" style="margin-top: 16px;">
            <label for="jenis_pekerjaan">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 6px; color: #0a9c5d;">
                    <path d="M20 7h-9"></path>
                    <path d="M14 17H5"></path>
                    <circle cx="17" cy="17" r="3"></circle>
                    <circle cx="7" cy="7" r="3"></circle>
                </svg>
                Jenis Pekerjaan <span class="required">*</span>
            </label>
            <input type="text" name="jenis_pekerjaan" id="jenis_pekerjaan" 
                   class="form-input @error('jenis_pekerjaan') error @enderror" 
                   value="{{ old('jenis_pekerjaan', $template->jenis_pekerjaan) }}" 
                   required>
            @error('jenis_pekerjaan')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group" style="margin-top: 16px;">
            <label for="std_prwtn">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 6px; color: #0a9c5d;">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
                Standar Perawatan <span class="required">*</span>
            </label>
            <textarea name="std_prwtn" id="std_prwtn" 
                      class="form-textarea @error('std_prwtn') error @enderror" 
                      rows="2" 
                      required>{{ old('std_prwtn', $template->std_prwtn) }}</textarea>
            @error('std_prwtn')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group" style="margin-top: 16px;">
            <label for="alat_bahan">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 6px; color: #0a9c5d;">
                    <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path>
                </svg>
                Alat dan Bahan <span class="required">*</span>
            </label>
            <textarea name="alat_bahan" id="alat_bahan" 
                      class="form-textarea @error('alat_bahan') error @enderror" 
                      rows="2" 
                      required>{{ old('alat_bahan', $template->alat_bahan) }}</textarea>
            @error('alat_bahan')
                <span class="error-message">{{ $message }}</span>
            @enderror
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
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.card {
    animation: fadeIn 0.4s ease-out;
}

.interval-btn {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.12);
}

.interval-btn:active:not(:disabled) {
    transform: scale(0.95);
}

.interval-btn-decrease {
    background: linear-gradient(135deg, #f44336 0%, #d32f2f 100%);
    color: white;
}

.interval-btn-decrease:hover:not(:disabled) {
    background: linear-gradient(135deg, #d32f2f 0%, #c62828 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(244, 67, 54, 0.3);
}

.interval-btn-decrease:disabled {
    background: linear-gradient(135deg, #e0e0e0 0%, #d0d0d0 100%);
    color: #999;
    cursor: not-allowed;
    box-shadow: none;
}

.interval-btn-increase {
    background: linear-gradient(135deg, #0a9c5d 0%, #088c51 100%);
    color: white;
}

.interval-btn-increase:hover {
    background: linear-gradient(135deg, #088c51 0%, #067d48 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(10, 156, 93, 0.3);
}

.interval-input:focus {
    outline: none;
    border-color: #0a9c5d !important;
    box-shadow: 0 0 0 3px rgba(10, 156, 93, 0.1);
}

.form-group label {
    display: block;
    margin-bottom: 10px;
    font-weight: 700;
    color: #2d3748;
    font-size: 14px;
    letter-spacing: 0.3px;
}

.form-group label svg {
    filter: drop-shadow(0 1px 2px rgba(10, 156, 93, 0.2));
}

.form-input, .form-select, .form-textarea {
    transition: all 0.3s ease;
    border: 2px solid #e0e7ef;
    border-radius: 12px;
    padding: 12px 16px;
    font-size: 14px;
    background: linear-gradient(135deg, #ffffff 0%, #fafbfc 100%);
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
}

.form-select {
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg width='12' height='12' viewBox='0 0 12 12' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M2 4l4 4 4-4' stroke='%230a9c5d' stroke-width='2' stroke-linecap='round'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 16px center;
    padding-right: 44px;
    cursor: pointer;
}

.form-textarea {
    resize: vertical;
    min-height: 90px;
    font-family: inherit;
    line-height: 1.6;
}

.form-input:focus, .form-select:focus, .form-textarea:focus {
    border-color: #0a9c5d;
    box-shadow: 0 0 0 4px rgba(10, 156, 93, 0.12), 0 4px 12px rgba(10, 156, 93, 0.08);
    transform: translateY(-2px);
    background: white;
}

.form-input:hover, .form-select:hover, .form-textarea:hover {
    border-color: #a5d6a7;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.06);
}

.form-input::placeholder, .form-textarea::placeholder {
    color: #a0aec0;
    font-style: italic;
}

.btn-outline {
    background: white;
    border: 2px solid #e0e7ef;
    color: #4a5568;
    padding: 12px 24px;
    border-radius: 12px;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.04);
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
}

.btn-outline:hover {
    background: linear-gradient(135deg, #f7f9fb 0%, #edf2f7 100%);
    border-color: #cbd5e0;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.08);
    color: #2d3748;
    text-decoration: none;
}

.btn-primary {
    background: linear-gradient(135deg, #0a9c5d 0%, #088c51 100%);
    border: none;
    color: white;
    padding: 12px 28px;
    border-radius: 12px;
    font-weight: 700;
    transition: all 0.3s ease;
    box-shadow: 0 4px 8px rgba(10, 156, 93, 0.25);
    display: inline-flex;
    align-items: center;
    gap: 8px;
    letter-spacing: 0.3px;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #088c51 0%, #067d48 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(10, 156, 93, 0.35);
}

.btn-primary:active {
    transform: translateY(0);
    box-shadow: 0 2px 4px rgba(10, 156, 93, 0.3);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const periodeSelect = document.getElementById('periode');
    const intervalInput = document.getElementById('interval_periode');
    const intervalValueDisplay = document.getElementById('intervalValueDisplay');
    const intervalUnitDisplay = document.getElementById('intervalUnitDisplay');
    const decreaseBtn = document.getElementById('decreaseBtn');

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
        
        intervalValueDisplay.textContent = interval;
        intervalUnitDisplay.textContent = unit;

        // Update decrease button state
        decreaseBtn.disabled = parseInt(interval) <= 1;
    }

    periodeSelect.addEventListener('change', updateIntervalLabel);
    intervalInput.addEventListener('input', updateIntervalLabel);

    // Initialize
    updateIntervalLabel();
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
</script>
@endsection
