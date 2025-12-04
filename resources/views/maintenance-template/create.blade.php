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
                <div id="bagianLoading" style="display: none; padding: 12px; background: linear-gradient(135deg, #f0f9f4 0%, #e8f5e9 100%); border-radius: 12px; margin-top: 8px; border: 2px solid #c8e6c9; animation: fadeIn 0.3s ease;">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#0a9c5d" stroke-width="2" style="animation: spin 1s linear infinite;">
                            <line x1="12" y1="2" x2="12" y2="6"></line>
                            <line x1="12" y1="18" x2="12" y2="22"></line>
                            <line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line>
                            <line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line>
                            <line x1="2" y1="12" x2="6" y2="12"></line>
                            <line x1="18" y1="12" x2="22" y2="12"></line>
                            <line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line>
                            <line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line>
                        </svg>
                        <div>
                            <div style="color: #0a9c5d; font-size: 14px; font-weight: 600; margin-bottom: 2px;">
                                Memuat bagian mesin...
                            </div>
                            <div style="color: #66bb6a; font-size: 12px;">
                                Mohon tunggu sebentar
                            </div>
                        </div>
                    </div>
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
                    Tanggal <span class="required">*</span>
                </label>
                <input type="text" name="tanggal_mulai" id="tanggal_mulai" 
                       class="form-input @error('tanggal_mulai') error @enderror" 
                       value="{{ old('tanggal_mulai', date('d F Y')) }}" 
                       readonly 
                       required
                       style="cursor: pointer; background-color: white;"
                       placeholder="Pilih tanggal">
                <input type="hidden" name="tanggal_mulai_value" id="tanggal_mulai_value" value="{{ old('tanggal_mulai_value', date('Y-m-d')) }}">
                @error('tanggal_mulai')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="catatan">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 6px;">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                        <polyline points="10 9 9 9 8 9"></polyline>
                    </svg>
                    Catatan (Opsional)
                </label>
                <textarea name="catatan" id="catatan" 
                          class="form-input @error('catatan') error @enderror" 
                          rows="3" 
                          placeholder="Tambahkan catatan jika diperlukan">{{ old('catatan') }}</textarea>
                @error('catatan')
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

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(20px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

.form-group {
    margin-bottom: 20px;
    animation: fadeIn 0.3s ease;
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

.required {
    color: #d32f2f;
    margin-left: 2px;
}

.form-input,
.form-select {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid #e0e0e0;
    border-radius: 12px;
    font-size: 14px;
    transition: all 0.3s ease;
    background: linear-gradient(135deg, #ffffff 0%, #f9f9f9 100%);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.04);
}

.form-input:hover,
.form-select:hover {
    border-color: #c0c0c0;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.08);
}

.form-input:focus,
.form-select:focus {
    outline: none;
    border-color: #0a9c5d;
    box-shadow: 0 0 0 3px rgba(10, 156, 93, 0.1), 0 4px 12px rgba(0, 0, 0, 0.1);
    background: #ffffff;
}

.form-select {
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg width='12' height='8' viewBox='0 0 12 8' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1 1.5L6 6.5L11 1.5' stroke='%230a9c5d' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 16px center;
    padding-right: 48px;
}

textarea.form-input {
    resize: vertical;
    min-height: 90px;
    line-height: 1.6;
    font-family: inherit;
}

.btn-outline {
    display: inline-flex;
    align-items: center;
    padding: 12px 24px;
    border: 2px solid #e0e0e0;
    background: linear-gradient(135deg, #ffffff 0%, #f9f9f9 100%);
    color: #666;
    border-radius: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.04);
}

.btn-outline:hover {
    background: linear-gradient(135deg, #f5f5f5 0%, #eeeeee 100%);
    border-color: #c0c0c0;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    color: #333;
    text-decoration: none;
}

.btn-primary {
    display: inline-flex;
    align-items: center;
    padding: 12px 24px;
    border: none;
    background: linear-gradient(135deg, #0a9c5d 0%, #088c51 100%);
    color: white;
    border-radius: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    box-shadow: 0 4px 12px rgba(10, 156, 93, 0.3);
}

.btn-primary:hover {
    background: linear-gradient(135deg, #099251 0%, #077d47 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(10, 156, 93, 0.4);
}

.btn-primary:active {
    transform: translateY(0);
    box-shadow: 0 3px 8px rgba(10, 156, 93, 0.3);
}
</style>

<script>
// Format tanggal Indonesia
const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

function formatDateIndonesia(date) {
    const day = date.getDate();
    const month = monthNames[date.getMonth()];
    const year = date.getFullYear();
    return `${day} ${month} ${year}`;
}

function formatDateSQL(date) {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
}

document.addEventListener('DOMContentLoaded', function() {
    const assetsSelect = document.getElementById('assets_id');
    const bagianSelect = document.getElementById('bg_mesin_id');
    const bagianContainer = document.getElementById('bagianContainer');
    const tanggalInput = document.getElementById('tanggal_mulai');
    const tanggalValue = document.getElementById('tanggal_mulai_value');

    // Load bagian mesin when asset is selected
    assetsSelect.addEventListener('change', async function() {
        const assetId = this.value;
        const loadingDiv = document.getElementById('bagianLoading');
        
        if (!assetId) {
            bagianContainer.style.display = 'none';
            bagianSelect.innerHTML = '<option value="">Pilih bagian mesin</option>';
            return;
        }

        // Show container first
        bagianContainer.style.display = 'block';
        
        // Show loading and hide select
        loadingDiv.style.display = 'block';
        bagianSelect.style.display = 'none';

        try {
            const response = await fetch(`/maintenance-template/bagian-mesin/${assetId}`);
            const bagianMesin = await response.json();

            bagianSelect.innerHTML = '<option value="">Pilih bagian mesin</option>';
            
            if (bagianMesin.length === 0) {
                loadingDiv.style.display = 'none';
                bagianSelect.style.display = 'block';
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

            // Hide loading and show select
            loadingDiv.style.display = 'none';
            bagianSelect.style.display = 'block';
        } catch (error) {
            console.error('Error loading bagian mesin:', error);
            loadingDiv.style.display = 'none';
            bagianSelect.style.display = 'block';
            bagianSelect.innerHTML = '<option value="">Error memuat data</option>';
            alert('❌ Gagal memuat bagian mesin. Silakan coba lagi.');
        }
    });

    // Date picker untuk tanggal
    tanggalInput.addEventListener('click', function() {
        // Create modal overlay
        const overlay = document.createElement('div');
        overlay.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            animation: fadeIn 0.2s ease;
        `;

        // Create calendar container
        const calendarContainer = document.createElement('div');
        calendarContainer.style.cssText = `
            background: white;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 400px;
            width: 90%;
            animation: slideUp 0.3s ease;
        `;

        // Get current date or selected date
        let currentDate = tanggalValue.value ? new Date(tanggalValue.value + 'T00:00:00') : new Date();
        let viewYear = currentDate.getFullYear();
        let viewMonth = currentDate.getMonth();
        
        // Store temporary selected date (only applied when clicking Simpan)
        let tempSelectedDate = tanggalValue.value;

        function renderCalendar() {
            const firstDay = new Date(viewYear, viewMonth, 1);
            const lastDay = new Date(viewYear, viewMonth + 1, 0);
            const prevLastDay = new Date(viewYear, viewMonth, 0);
            
            const firstDayIndex = firstDay.getDay();
            const lastDayDate = lastDay.getDate();
            const prevLastDayDate = prevLastDay.getDate();

            let calendarHTML = `
                <div style="margin-bottom: 20px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <button type="button" id="prevMonth" style="background: #f5f5f5; border: none; width: 36px; height: 36px; border-radius: 8px; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#666" stroke-width="2">
                                <polyline points="15 18 9 12 15 6"></polyline>
                            </svg>
                        </button>
                        <h3 style="margin: 0; font-size: 18px; font-weight: 600; color: #022415;">${monthNames[viewMonth]} ${viewYear}</h3>
                        <button type="button" id="nextMonth" style="background: #f5f5f5; border: none; width: 36px; height: 36px; border-radius: 8px; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#666" stroke-width="2">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </button>
                    </div>
                    <div style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 4px; margin-bottom: 8px;">
                        ${['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'].map(day => 
                            `<div style="text-align: center; padding: 8px; font-size: 12px; font-weight: 600; color: #999;">${day}</div>`
                        ).join('')}
                    </div>
                    <div style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 4px;" id="calendarDays">
            `;

            // Previous month days
            for (let i = firstDayIndex - 1; i >= 0; i--) {
                calendarHTML += `<div style="text-align: center; padding: 10px; font-size: 14px; color: #ccc; border-radius: 8px;">${prevLastDayDate - i}</div>`;
            }

            // Current month days
            for (let day = 1; day <= lastDayDate; day++) {
                const date = new Date(viewYear, viewMonth, day);
                const dateStr = formatDateSQL(date);
                const isSelected = tempSelectedDate === dateStr;
                const isToday = new Date().toDateString() === date.toDateString();
                
                let style = 'text-align: center; padding: 10px; font-size: 14px; border-radius: 8px; cursor: pointer; transition: all 0.2s;';
                if (isSelected) {
                    style += 'background: linear-gradient(135deg, #0a9c5d 0%, #088c51 100%); color: white; font-weight: 600; box-shadow: 0 4px 12px rgba(10, 156, 93, 0.3);';
                } else if (isToday) {
                    style += 'border: 2px solid #0a9c5d; color: #0a9c5d; font-weight: 600;';
                } else {
                    style += 'color: #333;';
                }
                
                calendarHTML += `<div class="calendar-day" data-date="${dateStr}" style="${style}">${day}</div>`;
            }

            calendarHTML += `</div></div>`;

            // Action buttons
            calendarHTML += `
                <div style="display: flex; gap: 12px; justify-content: center; margin-top: 20px; padding-top: 20px; border-top: 1px solid #e0e0e0;">
                    <button type="button" id="cancelCalendar" style="padding: 12px 24px; border: 2px solid #e0e0e0; background: white; color: #666; border-radius: 10px; font-weight: 600; cursor: pointer; transition: all 0.2s;">
                        Batal
                    </button>
                    <button type="button" id="confirmCalendar" style="padding: 12px 32px; border: none; background: linear-gradient(135deg, #0a9c5d 0%, #088c51 100%); color: white; border-radius: 10px; font-weight: 600; cursor: pointer; transition: all 0.2s; box-shadow: 0 4px 12px rgba(10, 156, 93, 0.3);">
                        Simpan
                    </button>
                </div>
            `;

            calendarContainer.innerHTML = calendarHTML;

            // Add hover effects
            const style = document.createElement('style');
            style.textContent = `
                .calendar-day:not([style*="background: linear-gradient"]):hover {
                    background: #f0f9f4 !important;
                    color: #0a9c5d !important;
                    transform: scale(1.1);
                }
                #prevMonth:hover, #nextMonth:hover {
                    background: #0a9c5d !important;
                }
                #prevMonth:hover svg, #nextMonth:hover svg {
                    stroke: white !important;
                }
                #cancelCalendar:hover {
                    background: #f5f5f5 !important;
                    border-color: #c0c0c0 !important;
                }
                #confirmCalendar:hover {
                    background: linear-gradient(135deg, #099251 0%, #077d47 100%) !important;
                    transform: translateY(-2px);
                    box-shadow: 0 6px 16px rgba(10, 156, 93, 0.4) !important;
                }
                #confirmCalendar:active {
                    transform: translateY(0);
                }
            `;
            document.head.appendChild(style);

            // Event listeners
            document.getElementById('prevMonth').addEventListener('click', () => {
                viewMonth--;
                if (viewMonth < 0) {
                    viewMonth = 11;
                    viewYear--;
                }
                renderCalendar();
            });

            document.getElementById('nextMonth').addEventListener('click', () => {
                viewMonth++;
                if (viewMonth > 11) {
                    viewMonth = 0;
                    viewYear++;
                }
                renderCalendar();
            });

            document.querySelectorAll('.calendar-day').forEach(day => {
                day.addEventListener('click', function() {
                    const selectedDate = this.getAttribute('data-date');
                    if (selectedDate) {
                        // Only update temporary selection, not the actual input
                        tempSelectedDate = selectedDate;
                        renderCalendar();
                    }
                });
            });

            document.getElementById('cancelCalendar').addEventListener('click', () => {
                // Don't apply changes, just close
                document.body.removeChild(overlay);
            });

            document.getElementById('confirmCalendar').addEventListener('click', () => {
                // Apply the temporary selected date to actual input
                if (tempSelectedDate) {
                    tanggalValue.value = tempSelectedDate;
                    const date = new Date(tempSelectedDate + 'T00:00:00');
                    tanggalInput.value = formatDateIndonesia(date);
                }
                document.body.removeChild(overlay);
            });
        }

        overlay.appendChild(calendarContainer);
        document.body.appendChild(overlay);
        renderCalendar();

        // Close on overlay click
        overlay.addEventListener('click', (e) => {
            if (e.target === overlay) {
                document.body.removeChild(overlay);
            }
        });
    });

    // Set default date saat load
    if (tanggalValue.value) {
        const defaultDate = new Date(tanggalValue.value + 'T00:00:00');
        tanggalInput.value = formatDateIndonesia(defaultDate);
    }
});

// Update form submission untuk menggunakan hidden field
document.getElementById('maintenanceForm').addEventListener('submit', function(e) {
    // Ganti name tanggal_mulai dari display input ke hidden input
    document.getElementById('tanggal_mulai').removeAttribute('name');
    document.getElementById('tanggal_mulai_value').setAttribute('name', 'tanggal_mulai');
});
</script>
@endsection
