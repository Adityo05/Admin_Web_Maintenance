@extends('layouts.app')

@section('title', 'Edit Karyawan')

@section('content')
<div style="max-width: 1200px; margin: 0 auto;">
    <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);">
        <div style="background: linear-gradient(135deg, #0A9C5D 0%, #022415 100%); padding: 20px; border-radius: 12px 12px 0 0;">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                    </svg>
                    <h2 style="color: white; font-size: 20px; font-weight: bold; margin: 0;">Edit Data Karyawan</h2>
                </div>
                <a href="{{ route('karyawan.index') }}" style="color: white; text-decoration: none;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </a>
            </div>
        </div>
        
        <form action="{{ route('karyawan.update', $karyawan->id) }}" method="POST" style="padding: 24px;">
            @csrf
            @method('PUT')
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                <div class="form-group">
                    <label for="full_name" class="form-label">Nama Lengkap *</label>
                    <div class="form-input-group">
                        <svg class="form-input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        <input type="text" id="full_name" name="full_name" class="form-input" value="{{ $karyawan->full_name }}" required>
                    </div>
                    @error('full_name')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="email" class="form-label">Email *</label>
                    <div class="form-input-group">
                        <svg class="form-input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                            <polyline points="22,6 12,13 2,6"></polyline>
                        </svg>
                        <input type="email" id="email" name="email" class="form-input" value="{{ $karyawan->email }}" required>
                    </div>
                    @error('email')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                <div class="form-group">
                    <label for="password" class="form-label">Password (Kosongkan jika tidak diubah)</label>
                    <div class="form-input-group">
                        <svg class="form-input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                        </svg>
                        <input type="password" id="password" name="password" class="form-input" minlength="6">
                    </div>
                    @error('password')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="phone" class="form-label">Nomor Telepon</label>
                    <div class="form-input-group">
                        <svg class="form-input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                        </svg>
                        <input type="text" id="phone" name="phone" class="form-input" value="{{ $karyawan->phone }}">
                    </div>
                </div>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                <div class="form-group">
                    <label for="jabatan" class="form-label">Jabatan *</label>
                    <select id="jabatan" name="jabatan" class="form-input" required onchange="toggleMesinSelectionEdit(this.value)">
                        <option value="">Pilih Jabatan</option>
                        <option value="Teknisi" {{ $karyawan->jabatan == 'Teknisi' ? 'selected' : '' }}>Teknisi</option>
                        <option value="KASIE Teknisi" {{ $karyawan->jabatan == 'KASIE Teknisi' ? 'selected' : '' }}>KASIE Teknisi</option>
                    </select>
                    @error('jabatan')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="department" class="form-label">Department</label>
                    <input type="text" id="department" name="department" class="form-input" value="{{ $karyawan->department ?? 'Maintenance' }}" readonly>
                </div>
            </div>
            
            <div style="margin: 24px 0; border-top: 2px solid #e0e0e0;"></div>
            
            <div class="form-group" id="mesinSelectionContainerEdit">
                <label class="form-label">Mesin yang Dikerjakan (Multi-select)</label>
                <p style="font-size: 12px; color: #666; margin-bottom: 8px;">* KASIE Teknisi dapat melihat semua mesin</p>
                <div style="max-height: 300px; overflow-y: auto; border: 1px solid #e0e0e0; border-radius: 8px; padding: 12px;">
                    @foreach($assets as $asset)
                    <label style="display: flex; align-items: center; gap: 8px; padding: 8px; cursor: pointer; border-radius: 4px; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#f5f5f5'" onmouseout="this.style.backgroundColor='transparent'">
                        <input type="checkbox" name="assets[]" value="{{ $asset->id }}" class="asset-checkbox-edit" {{ in_array($asset->id, $selectedAssets) ? 'checked' : '' }} style="width: 18px; height: 18px; cursor: pointer;">
                        <span>{{ $asset->nama_assets }} @if($asset->kode_assets)({{ $asset->kode_assets }})@endif</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <script>
            function toggleMesinSelectionEdit(jabatan) {
                const container = document.getElementById('mesinSelectionContainerEdit');
                const checkboxes = document.querySelectorAll('.asset-checkbox-edit');
                
                if (jabatan === 'KASIE Teknisi') {
                    container.style.opacity = '0.5';
                    container.style.pointerEvents = 'none';
                    checkboxes.forEach(cb => cb.checked = false);
                } else {
                    container.style.opacity = '1';
                    container.style.pointerEvents = 'auto';
                }
            }
            
            // Check on page load
            document.addEventListener('DOMContentLoaded', function() {
                const jabatanSelect = document.getElementById('jabatan');
                toggleMesinSelectionEdit(jabatanSelect.value);
            });
            </script>
            
            <div style="margin-top: 32px; display: flex; justify-content: flex-end; gap: 12px; padding-top: 24px; border-top: 1px solid #e0e0e0;">
                <a href="{{ route('karyawan.index') }}" class="btn btn-outline">
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
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

