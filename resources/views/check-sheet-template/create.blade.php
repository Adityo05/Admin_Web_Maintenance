@extends('layouts.app')

@section('title', 'Tambah Cek Sheet Schedule')

@section('content')
<div class="page-header">
    <h1>Tambah Cek Sheet Schedule</h1>
</div>

@if(session('error'))
    <div class="alert alert-error">{{ session('error') }}</div>
@endif

<div class="card" style="max-width: 1000px; margin: 0 auto;">
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

        <div id="komponenLoading" style="display: none; padding: 16px; background: #f0f9f4; border-radius: 8px; margin-top: 16px; color: #0a9c5d; font-size: 13px; text-align: center;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="animation: spin 1s linear infinite; vertical-align: middle; margin-right: 8px;">
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

        <div id="komponenContainer" style="display: none; margin-top: 24px;">
            <div id="komponenList"></div>
        </div>

        <div class="form-actions" style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 32px; padding-top: 24px; border-top: 1px solid #e0e0e0;">
            <a href="{{ route('check-sheet-template.index') }}" class="btn btn-outline">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 6px;">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Batal
            </a>
            <button type="submit" class="btn btn-primary" id="submitBtn" style="min-width: 140px;">
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

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.komponen-card {
    background: linear-gradient(135deg, #f7f9fb 0%, #ffffff 100%);
    border: 2px solid #e0e7ef;
    border-radius: 16px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
    animation: fadeIn 0.3s ease-out;
    transition: all 0.3s ease;
}

.komponen-card:hover {
    box-shadow: 0 6px 20px rgba(10, 156, 93, 0.12);
    border-color: #0a9c5d;
    transform: translateY(-2px);
}

.komponen-header {
    display: flex;
    align-items: center;
    padding: 16px;
    background: linear-gradient(135deg, #f0f9f4 0%, #e8f5e9 100%);
    border-radius: 12px;
    border: 2px solid #c8e6c9;
    margin-bottom: 20px;
    box-shadow: 0 2px 6px rgba(10, 156, 93, 0.1);
}

.work-type-item {
    background: white;
    border: 2px solid rgba(10, 156, 93, 0.2);
    border-radius: 12px;
    padding: 18px;
    margin-bottom: 16px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
}

.work-type-item:hover {
    border-color: rgba(10, 156, 93, 0.4);
    box-shadow: 0 4px 16px rgba(10, 156, 93, 0.1);
    transform: translateY(-2px);
}

.work-type-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 16px;
    padding-bottom: 12px;
    border-bottom: 2px solid #f0f9f4;
}

.work-type-badge {
    display: inline-block;
    padding: 6px 14px;
    background: linear-gradient(135deg, #0a9c5d 0%, #088c51 100%);
    color: white;
    font-size: 11px;
    font-weight: 700;
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(10, 156, 93, 0.25);
    letter-spacing: 0.5px;
}

.interval-box {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 14px 18px;
    background: linear-gradient(135deg, #ffffff 0%, #f9fafb 100%);
    border: 2px solid #e0e7ef;
    border-radius: 14px;
    transition: all 0.3s ease;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
}

.interval-box:focus-within {
    border-color: #0a9c5d;
    box-shadow: 0 0 0 4px rgba(10, 156, 93, 0.12), 0 4px 12px rgba(10, 156, 93, 0.08);
    transform: translateY(-1px);
}

.interval-input {
    width: 70px;
    text-align: center;
    font-weight: 700;
    font-size: 20px;
    color: #0a9c5d;
    border: 2px solid #d1fae5;
    border-radius: 10px;
    padding: 10px;
    background: #f0fdf4;
    transition: all 0.2s;
}

.interval-input:focus {
    outline: none;
    border-color: #0a9c5d;
    background: white;
    box-shadow: 0 0 0 3px rgba(10, 156, 93, 0.1);
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
    cursor: not-allowed;
    opacity: 0.5;
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

.interval-label {
    margin-top: 6px;
    color: #666;
    font-size: 13px;
    font-weight: 500;
}

.interval-label span {
    color: #0a9c5d;
    font-weight: 700;
}

.btn-add-work {
    background: linear-gradient(135deg, rgba(10, 156, 93, 0.1) 0%, rgba(8, 140, 81, 0.1) 100%);
    border: 2px solid rgba(10, 156, 93, 0.3);
    color: #0a9c5d;
    font-size: 13px;
    font-weight: 700;
    padding: 10px 16px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 6px;
    border-radius: 10px;
    transition: all 0.3s ease;
}

.btn-add-work:hover {
    background: linear-gradient(135deg, rgba(10, 156, 93, 0.15) 0%, rgba(8, 140, 81, 0.15) 100%);
    border-color: #0a9c5d;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(10, 156, 93, 0.2);
}

.btn-remove {
    background: linear-gradient(135deg, rgba(244, 67, 54, 0.1) 0%, rgba(211, 47, 47, 0.1) 100%);
    border: 2px solid rgba(244, 67, 54, 0.3);
    color: #f44336;
    cursor: pointer;
    padding: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.btn-remove:hover {
    background: linear-gradient(135deg, rgba(244, 67, 54, 0.15) 0%, rgba(211, 47, 47, 0.15) 100%);
    border-color: #f44336;
    transform: scale(1.1);
    box-shadow: 0 2px 6px rgba(244, 67, 54, 0.2);
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
    color: #0a9c5d;
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

#komponenLoading {
    animation: fadeIn 0.3s ease-out;
}
</style>

<script>
let komponenData = [];
let selectedAssetName = '';

document.addEventListener('DOMContentLoaded', function() {
    const assetsSelect = document.getElementById('assets_id');
    
    assetsSelect.addEventListener('change', async function() {
        const assetId = this.value;
        const loadingDiv = document.getElementById('komponenLoading');
        const komponenContainer = document.getElementById('komponenContainer');
        const komponenList = document.getElementById('komponenList');
        
        if (!assetId) {
            komponenContainer.style.display = 'none';
            komponenData = [];
            return;
        }

        // Get selected asset name
        selectedAssetName = this.options[this.selectedIndex].text;

        // Show loading
        loadingDiv.style.display = 'block';
        komponenContainer.style.display = 'none';

        try {
            const response = await fetch(`/check-sheet-template/komponen/${assetId}`);
            const komponen = await response.json();

            loadingDiv.style.display = 'none';
            
            if (komponen.length === 0) {
                alert('⚠️ Asset ini belum memiliki komponen.\\n\\nSilakan tambahkan komponen terlebih dahulu di menu Data Assets.');
                return;
            }

            // Store komponen data and render
            komponenData = komponen.map(k => ({
                id: k.id,
                nama_bagian: k.nama_bagian,
                bagian_mesin: k.bagian_mesin || '',
                workTypes: [{
                    periode: 'Mingguan',
                    interval: 1,
                    jenis_pekerjaan: '',
                    std_prwtn: '',
                    alat_bahan: ''
                }]
            }));

            renderKomponenList();
            komponenContainer.style.display = 'block';

        } catch (error) {
            console.error('Error loading komponen:', error);
            loadingDiv.style.display = 'none';
            alert('❌ Gagal memuat komponen. Silakan coba lagi.');
        }
    });
});

function renderKomponenList() {
    const komponenList = document.getElementById('komponenList');
    
    let html = `<div style="margin-bottom: 16px; font-weight: 600; font-size: 16px; color: #333;">
        Komponen dan Detail Pekerjaan (${komponenData.length} komponen)
    </div>`;

    komponenData.forEach((komponen, komponenIndex) => {
        const displayName = komponen.bagian_mesin 
            ? `${komponen.bagian_mesin} - ${komponen.nama_bagian}` 
            : komponen.nama_bagian;

        html += `
        <div class="komponen-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <span style="display: inline-block; padding: 6px 14px; background: linear-gradient(135deg, #0a9c5d 0%, #088c51 100%); color: white; font-size: 12px; font-weight: 700; border-radius: 8px; box-shadow: 0 2px 6px rgba(10, 156, 93, 0.25);">
                    Komponen ${komponenIndex + 1}
                </span>
            </div>

            <div class="komponen-header">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#0a9c5d" stroke-width="2.5" style="margin-right: 12px; filter: drop-shadow(0 1px 2px rgba(10, 156, 93, 0.3));">
                    <circle cx="12" cy="12" r="3"></circle>
                    <path d="M12 1v6m0 6v6"></path>
                </svg>
                <div style="flex: 1;">
                    <div style="font-size: 11px; color: #0a9c5d; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Nama Komponen</div>
                    <div style="font-size: 15px; font-weight: 700; color: #2d3748; margin-top: 4px;">${displayName}</div>
                </div>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <div style="font-size: 14px; font-weight: 700; color: #2d3748;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 4px;">
                        <path d="M9 11l3 3L22 4"></path>
                        <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                    </svg>
                    Jenis Pekerjaan <span style="color: #0a9c5d;">(${komponen.workTypes.length})</span>
                </div>
                <button type="button" onclick="addWorkType(${komponenIndex})" class="btn-add-work">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="16"></line>
                        <line x1="8" y1="12" x2="16" y2="12"></line>
                    </svg>
                    Tambah Pekerjaan
                </button>
            </div>

            <div id="workTypes_${komponenIndex}">`;

        komponen.workTypes.forEach((workType, workTypeIndex) => {
            html += renderWorkType(komponenIndex, workTypeIndex, workType);
        });

        html += `
            </div>
        </div>`;
    });

    komponenList.innerHTML = html;
    updateAllIntervalLabels();
}

function renderWorkType(komponenIndex, workTypeIndex, workType) {
    const canRemove = komponenData[komponenIndex].workTypes.length > 1;
    
    return `
    <div class="work-type-item">
        <div class="work-type-header">
            <span class="work-type-badge">Pekerjaan ${workTypeIndex + 1}</span>
            ${canRemove ? `
            <button type="button" onclick="removeWorkType(${komponenIndex}, ${workTypeIndex})" class="btn-remove">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 6h18"></path>
                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                </svg>
            </button>
            ` : ''}
        </div>

        <input type="hidden" name="komponen[${komponenIndex}][id]" value="${komponenData[komponenIndex].id}">

        <div class="form-group">
            <label>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 6px;">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
                Periode <span class="required">*</span>
            </label>
            <select name="komponen[${komponenIndex}][workTypes][${workTypeIndex}][periode]" 
                    class="form-select" required
                    onchange="updateIntervalLabel(${komponenIndex}, ${workTypeIndex})">
                <option value="Harian" ${workType.periode === 'Harian' ? 'selected' : ''}>Harian</option>
                <option value="Mingguan" ${workType.periode === 'Mingguan' ? 'selected' : ''}>Mingguan</option>
                <option value="Bulanan" ${workType.periode === 'Bulanan' ? 'selected' : ''}>Bulanan</option>
            </select>
        </div>

        <div class="form-group" style="margin-top: 12px;">
            <label>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 6px;">
                    <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                    <polyline points="17 6 23 6 23 12"></polyline>
                </svg>
                Interval <span class="required">*</span>
            </label>
            <div class="interval-box">
                <button type="button" onclick="decreaseInterval(${komponenIndex}, ${workTypeIndex})" 
                        class="interval-btn interval-btn-decrease" 
                        id="decreaseBtn_${komponenIndex}_${workTypeIndex}">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                </button>
                <div style="flex: 1; text-align: center;">
                    <input type="number" name="komponen[${komponenIndex}][workTypes][${workTypeIndex}][interval]" 
                           id="interval_${komponenIndex}_${workTypeIndex}"
                           class="interval-input" 
                           value="${workType.interval}" 
                           min="1" 
                           required
                           oninput="updateIntervalLabel(${komponenIndex}, ${workTypeIndex})">
                    <div class="interval-label" id="intervalLabel_${komponenIndex}_${workTypeIndex}">
                        Per <span class="intervalValue">1</span> <span class="intervalUnit">Minggu</span>
                    </div>
                </div>
                <button type="button" onclick="increaseInterval(${komponenIndex}, ${workTypeIndex})" 
                        class="interval-btn interval-btn-increase">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                </button>
            </div>
        </div>

        <div class="form-group" style="margin-top: 12px;">
            <label>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 6px;">
                    <path d="M20 7h-9"></path>
                    <path d="M14 17H5"></path>
                    <circle cx="17" cy="17" r="3"></circle>
                    <circle cx="7" cy="7" r="3"></circle>
                </svg>
                Jenis Pekerjaan <span class="required">*</span>
            </label>
            <input type="text" name="komponen[${komponenIndex}][workTypes][${workTypeIndex}][jenis_pekerjaan]" 
                   class="form-input" value="${workType.jenis_pekerjaan}" 
                   placeholder="Contoh: Pengecekan dan pembersihan" required>
        </div>

        <div class="form-group" style="margin-top: 12px;">
            <label>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 6px;">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
                Standar Perawatan <span class="required">*</span>
            </label>
            <textarea name="komponen[${komponenIndex}][workTypes][${workTypeIndex}][std_prwtn]" 
                      class="form-textarea" rows="2" 
                      placeholder="Deskripsi standar perawatan yang harus dilakukan" 
                      required>${workType.std_prwtn}</textarea>
        </div>

        <div class="form-group" style="margin-top: 12px;">
            <label>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 6px;">
                    <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path>
                </svg>
                Alat dan Bahan <span class="required">*</span>
            </label>
            <textarea name="komponen[${komponenIndex}][workTypes][${workTypeIndex}][alat_bahan]" 
                      class="form-textarea" rows="2" 
                      placeholder="Daftar alat dan bahan yang diperlukan untuk perawatan" 
                      required>${workType.alat_bahan}</textarea>
        </div>
    </div>
    `;
}

function addWorkType(komponenIndex) {
    komponenData[komponenIndex].workTypes.push({
        periode: 'Mingguan',
        interval: 1,
        jenis_pekerjaan: '',
        std_prwtn: '',
        alat_bahan: ''
    });
    renderKomponenList();
}

function removeWorkType(komponenIndex, workTypeIndex) {
    if (komponenData[komponenIndex].workTypes.length > 1) {
        komponenData[komponenIndex].workTypes.splice(workTypeIndex, 1);
        renderKomponenList();
    }
}

function increaseInterval(komponenIndex, workTypeIndex) {
    const input = document.getElementById(`interval_${komponenIndex}_${workTypeIndex}`);
    input.value = parseInt(input.value || 1) + 1;
    updateIntervalLabel(komponenIndex, workTypeIndex);
}

function decreaseInterval(komponenIndex, workTypeIndex) {
    const input = document.getElementById(`interval_${komponenIndex}_${workTypeIndex}`);
    const currentValue = parseInt(input.value || 1);
    if (currentValue > 1) {
        input.value = currentValue - 1;
        updateIntervalLabel(komponenIndex, workTypeIndex);
    }
}

function updateIntervalLabel(komponenIndex, workTypeIndex) {
    const periodeSelect = document.querySelector(`select[name="komponen[${komponenIndex}][workTypes][${workTypeIndex}][periode]"]`);
    const intervalInput = document.getElementById(`interval_${komponenIndex}_${workTypeIndex}`);
    const intervalLabel = document.getElementById(`intervalLabel_${komponenIndex}_${workTypeIndex}`);
    const decreaseBtn = document.getElementById(`decreaseBtn_${komponenIndex}_${workTypeIndex}`);

    const periode = periodeSelect.value;
    const interval = intervalInput.value || '1';

    let unit = '';
    switch(periode) {
        case 'Harian': unit = 'Hari'; break;
        case 'Mingguan': unit = 'Minggu'; break;
        case 'Bulanan': unit = 'Bulan'; break;
    }

    const valueSpan = intervalLabel.querySelector('.intervalValue');
    const unitSpan = intervalLabel.querySelector('.intervalUnit');
    valueSpan.textContent = interval;
    unitSpan.textContent = unit;

    decreaseBtn.disabled = parseInt(interval) <= 1;
}

function updateAllIntervalLabels() {
    komponenData.forEach((komponen, komponenIndex) => {
        komponen.workTypes.forEach((workType, workTypeIndex) => {
            updateIntervalLabel(komponenIndex, workTypeIndex);
        });
    });
}
</script>
@endsection
