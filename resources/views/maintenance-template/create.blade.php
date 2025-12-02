@extends('layouts.app')

@section('title', 'Tambah Maintenance Schedule')

@section('content')
<div class="page-header">
    <h1>Tambah Maintenance Schedule</h1>
</div>

@if(session('error'))
    <div class="alert alert-error">{{ session('error') }}</div>
@endif

<div class="card">
    <div style="padding: 0 0 20px 0; border-bottom: 1px solid #e0e0e0; margin-bottom: 24px;">
        <p style="color: #666; font-size: 14px; margin: 0;">Isi form di bawah ini untuk menambahkan schedule baru</p>
    </div>

    <form method="POST" action="{{ route('maintenance-template.store') }}" class="form">
        @csrf
        
        <div class="form-group">
            <label for="assets_id">Nama Mesin <span class="required">*</span></label>
            <select name="assets_id" id="assets_id" class="form-select @error('assets_id') error @enderror" required>
                <option value="">Pilih Mesin</option>
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

        <div class="form-group">
            <label for="nama_template">Bagian Mesin <span class="required">*</span></label>
            <select name="nama_template" id="nama_template" class="form-select @error('nama_template') error @enderror" required>
                <option value="">Pilih Bagian Mesin</option>
                <option value="Roll Atas" {{ old('nama_template') == 'Roll Atas' ? 'selected' : '' }}>Roll Atas</option>
                <option value="Roll Bawah" {{ old('nama_template') == 'Roll Bawah' ? 'selected' : '' }}>Roll Bawah</option>
                <option value="Bearing" {{ old('nama_template') == 'Bearing' ? 'selected' : '' }}>Bearing</option>
                <option value="Motor" {{ old('nama_template') == 'Motor' ? 'selected' : '' }}>Motor</option>
                <option value="Gearbox" {{ old('nama_template') == 'Gearbox' ? 'selected' : '' }}>Gearbox</option>
                <option value="Belt" {{ old('nama_template') == 'Belt' ? 'selected' : '' }}>Belt</option>
                <option value="Chain" {{ old('nama_template') == 'Chain' ? 'selected' : '' }}>Chain</option>
                <option value="Hydraulic System" {{ old('nama_template') == 'Hydraulic System' ? 'selected' : '' }}>Hydraulic System</option>
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
                       value="{{ old('interval_hari', 7) }}" 
                       min="1" 
                       placeholder="Contoh: 7" 
                       required>
                @error('interval_hari')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="periode">Periode <span class="required">*</span></label>
                <select name="periode" id="periode" class="form-select @error('periode') error @enderror" required>
                    <option value="Harian" {{ old('periode') == 'Harian' ? 'selected' : '' }}>Harian</option>
                    <option value="Mingguan" {{ old('periode') == 'Mingguan' ? 'selected' : '' }}>Mingguan</option>
                    <option value="Bulanan" {{ old('periode') == 'Bulanan' ? 'selected' : '' }}>Bulanan</option>
                </select>
                @error('periode')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="tanggal_mulai">Tanggal</label>
            <input type="date" name="tanggal_mulai" id="tanggal_mulai" 
                   class="form-input @error('tanggal_mulai') error @enderror" 
                   value="{{ old('tanggal_mulai', date('Y-m-d')) }}">
            @error('tanggal_mulai')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="deskripsi">Catatan (Opsional)</label>
            <textarea name="deskripsi" id="deskripsi" 
                      class="form-textarea @error('deskripsi') error @enderror" 
                      rows="4" 
                      placeholder="Tambahkan catatan jika diperlukan">{{ old('deskripsi') }}</textarea>
            @error('deskripsi')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-actions" style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px; padding-top: 24px; border-top: 1px solid #e0e0e0;">
            <a href="{{ route('maintenance-template.index') }}" class="btn btn-outline">Batal</a>
            <button type="submit" class="btn btn-primary" style="min-width: 120px;">Simpan</button>
        </div>
    </form>
</div>
@endsection
