@extends('layouts.app')

@section('title', 'Edit Maintenance Schedule')

@section('content')
<div class="page-header">
    <h1>Edit Maintenance Schedule</h1>
</div>

@if(session('error'))
    <div class="alert alert-error">{{ session('error') }}</div>
@endif

<div class="card">
    <div style="padding: 0 0 20px 0; border-bottom: 1px solid #e0e0e0; margin-bottom: 24px;">
        <p style="color: #666; font-size: 14px; margin: 0;">Edit informasi schedule maintenance</p>
    </div>

    <form method="POST" action="{{ route('maintenance-template.update', $template->id) }}" class="form">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label for="assets_id">Nama Mesin <span class="required">*</span></label>
            <select name="assets_id" id="assets_id" class="form-select @error('assets_id') error @enderror" required>
                <option value="">Pilih Mesin</option>
                @foreach($assets as $asset)
                    <option value="{{ $asset->id }}" {{ (old('assets_id', $template->assets_id) == $asset->id) ? 'selected' : '' }}>
                        {{ $asset->nama_assets }}
                    </option>
                @endforeach
            </select>
            @error('assets_id')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="nama_template">Bagian Mesin <span class="required">*</span></label>
            <select name="nama_template" id="nama_template" class="form-select @error('nama_template') error @enderror" required>
                <option value="">Pilih Bagian Mesin</option>
                <option value="Roll Atas" {{ old('nama_template', $template->nama_template) == 'Roll Atas' ? 'selected' : '' }}>Roll Atas</option>
                <option value="Roll Bawah" {{ old('nama_template', $template->nama_template) == 'Roll Bawah' ? 'selected' : '' }}>Roll Bawah</option>
                <option value="Bearing" {{ old('nama_template', $template->nama_template) == 'Bearing' ? 'selected' : '' }}>Bearing</option>
                <option value="Motor" {{ old('nama_template', $template->nama_template) == 'Motor' ? 'selected' : '' }}>Motor</option>
                <option value="Gearbox" {{ old('nama_template', $template->nama_template) == 'Gearbox' ? 'selected' : '' }}>Gearbox</option>
                <option value="Belt" {{ old('nama_template', $template->nama_template) == 'Belt' ? 'selected' : '' }}>Belt</option>
                <option value="Chain" {{ old('nama_template', $template->nama_template) == 'Chain' ? 'selected' : '' }}>Chain</option>
                <option value="Hydraulic System" {{ old('nama_template', $template->nama_template) == 'Hydraulic System' ? 'selected' : '' }}>Hydraulic System</option>
            </select>
            @error('nama_template')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label for="interval_hari">Interval <span class="required">*</span></label>
                <input type="number" name="interval_hari" id="interval_hari" 
                       class="form-input @error('interval_hari') error @enderror" 
                       value="{{ old('interval_hari', $template->interval_hari) }}" 
                       min="1" 
                       required>
                @error('interval_hari')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="periode">Periode <span class="required">*</span></label>
                <select name="periode" id="periode" class="form-select @error('periode') error @enderror" required>
                    <option value="Harian" {{ old('periode', 'Mingguan') == 'Harian' ? 'selected' : '' }}>Harian</option>
                    <option value="Mingguan" {{ old('periode', 'Mingguan') == 'Mingguan' ? 'selected' : '' }}>Mingguan</option>
                    <option value="Bulanan" {{ old('periode', 'Mingguan') == 'Bulanan' ? 'selected' : '' }}>Bulanan</option>
                </select>
                @error('periode')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="deskripsi">Catatan (Opsional)</label>
            <textarea name="deskripsi" id="deskripsi" 
                      class="form-textarea @error('deskripsi') error @enderror" 
                      rows="4">{{ old('deskripsi', $template->deskripsi) }}</textarea>
            @error('deskripsi')
                <span class="error-message">{{ $message }}</span>
            @enderror
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
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
