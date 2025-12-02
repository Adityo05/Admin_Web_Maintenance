@extends('layouts.app')

@section('title', 'Tambah Asset')

@section('content')
<div style="max-width: 1200px; margin: 0 auto;">
    <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);">
        <div style="background: linear-gradient(135deg, #0A9C5D 0%, #022415 100%); padding: 20px; border-radius: 12px 12px 0 0;">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    <h2 style="color: white; font-size: 20px; font-weight: bold; margin: 0;">Tambah Data Aset</h2>
                </div>
                <a href="{{ route('assets.index') }}" style="color: white; text-decoration: none;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </a>
            </div>
        </div>
        
        <form action="{{ route('assets.store') }}" method="POST" enctype="multipart/form-data" style="padding: 24px;">
            @csrf
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                <div class="form-group">
                    <label for="nama_assets" class="form-label">Nama Aset *</label>
                    <div class="form-input-group">
                        <svg class="form-input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                        </svg>
                        <input type="text" id="nama_assets" name="nama_assets" class="form-input" required>
                    </div>
                    @error('nama_assets')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="kode_assets" class="form-label">Kode Mesin</label>
                    <div class="form-input-group">
                        <svg class="form-input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="9" y1="3" x2="9" y2="21"></line>
                        </svg>
                        <input type="text" id="kode_assets" name="kode_assets" class="form-input">
                    </div>
                </div>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                <div class="form-group">
                    <label for="jenis_assets" class="form-label">Jenis Aset *</label>
                    <select id="jenis_assets" name="jenis_assets" class="form-input" required>
                        <option value="">Pilih Jenis Aset</option>
                        <option value="Mesin Produksi">Mesin Produksi</option>
                        <option value="Alat Berat">Alat Berat</option>
                        <option value="Listrik">Listrik</option>
                    </select>
                    @error('jenis_assets')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="mt_priority" class="form-label">Prioritas</label>
                    <select id="mt_priority" name="mt_priority" class="form-input">
                        <option value="">Pilih Prioritas</option>
                        <option value="Low">Low</option>
                        <option value="Medium">Medium</option>
                        <option value="High">High</option>
                    </select>
                </div>
            </div>
            
            <div style="margin: 24px 0; border-top: 2px solid #e0e0e0;"></div>
            
            <div id="bagian-container">
                <div class="bagian-item" style="margin-bottom: 24px; padding: 16px; border: 1px solid #e0e0e0; border-radius: 8px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                        <h3 style="font-size: 16px; font-weight: bold; color: #022415;">Bagian 1</h3>
                        <button type="button" class="btn btn-danger btn-sm" onclick="removeBagian(this)" style="display: none;">Hapus</button>
                    </div>
                    
                    <div class="form-group" style="margin-bottom: 16px;">
                        <label class="form-label">Nama Bagian *</label>
                        <input type="text" name="bagian[0][nama_bagian]" class="form-input" required>
                    </div>
                    
                    <div class="komponen-container">
                        <div class="komponen-item" style="margin-bottom: 12px;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                                <label class="form-label">Komponen 1</label>
                                <button type="button" class="btn btn-danger btn-sm" onclick="removeKomponen(this)" style="display: none;">Hapus</button>
                            </div>
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                                <input type="text" name="bagian[0][komponen][0][nama_komponen]" class="form-input" placeholder="Nama Komponen *" required>
                                <input type="text" name="bagian[0][komponen][0][spesifikasi]" class="form-input" placeholder="Spesifikasi">
                            </div>
                        </div>
                    </div>
                    
                    <button type="button" class="btn btn-secondary btn-sm" onclick="addKomponen(this)" style="margin-top: 8px;">+ Tambah Komponen</button>
                </div>
            </div>
            
            <button type="button" class="btn btn-secondary" onclick="addBagian()" style="margin-bottom: 24px;">+ Tambah Bagian</button>
            
            <div style="margin: 24px 0; border-top: 2px solid #e0e0e0;"></div>
            
            <div class="form-group">
                <label for="foto" class="form-label">Gambar Aset</label>
                <input type="file" id="foto" name="foto" accept="image/*" class="form-input">
                @error('foto')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            
            <div style="margin-top: 24px; display: flex; justify-content: flex-end; gap: 12px;">
                <a href="{{ route('assets.index') }}" class="btn btn-secondary" style="text-decoration: none;">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
let bagianIndex = 1;
let komponenIndices = {0: 1};

function addBagian() {
    const container = document.getElementById('bagian-container');
    const newBagian = document.createElement('div');
    newBagian.className = 'bagian-item';
    newBagian.style.cssText = 'margin-bottom: 24px; padding: 16px; border: 1px solid #e0e0e0; border-radius: 8px;';
    
    const bagianNum = bagianIndex;
    newBagian.innerHTML = `
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <h3 style="font-size: 16px; font-weight: bold; color: #022415;">Bagian ${bagianNum + 1}</h3>
            <button type="button" class="btn btn-danger btn-sm" onclick="removeBagian(this)">Hapus</button>
        </div>
        <div class="form-group" style="margin-bottom: 16px;">
            <label class="form-label">Nama Bagian *</label>
            <input type="text" name="bagian[${bagianIndex}][nama_bagian]" class="form-input" required>
        </div>
        <div class="komponen-container">
            <div class="komponen-item" style="margin-bottom: 12px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                    <label class="form-label">Komponen 1</label>
                    <button type="button" class="btn btn-danger btn-sm" onclick="removeKomponen(this)" style="display: none;">Hapus</button>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <input type="text" name="bagian[${bagianIndex}][komponen][0][nama_komponen]" class="form-input" placeholder="Nama Komponen *" required>
                    <input type="text" name="bagian[${bagianIndex}][komponen][0][spesifikasi]" class="form-input" placeholder="Spesifikasi">
                </div>
            </div>
        </div>
        <button type="button" class="btn btn-secondary btn-sm" onclick="addKomponen(this)" style="margin-top: 8px;">+ Tambah Komponen</button>
    `;
    
    container.appendChild(newBagian);
    komponenIndices[bagianIndex] = 1;
    bagianIndex++;
    
    // Show remove button for all bagian items
    document.querySelectorAll('.bagian-item').forEach((item, index) => {
        if (index > 0) {
            item.querySelector('.btn-danger').style.display = 'block';
        }
    });
}

function removeBagian(btn) {
    btn.closest('.bagian-item').remove();
}

function addKomponen(btn) {
    const bagianItem = btn.closest('.bagian-item');
    const bagianIndex = Array.from(document.querySelectorAll('.bagian-item')).indexOf(bagianItem);
    const komponenContainer = bagianItem.querySelector('.komponen-container');
    const komponenIndex = komponenIndices[bagianIndex] || 1;
    
    const newKomponen = document.createElement('div');
    newKomponen.className = 'komponen-item';
    newKomponen.style.cssText = 'margin-bottom: 12px;';
    newKomponen.innerHTML = `
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
            <label class="form-label">Komponen ${komponenIndex + 1}</label>
            <button type="button" class="btn btn-danger btn-sm" onclick="removeKomponen(this)">Hapus</button>
        </div>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
            <input type="text" name="bagian[${bagianIndex}][komponen][${komponenIndex}][nama_komponen]" class="form-input" placeholder="Nama Komponen *" required>
            <input type="text" name="bagian[${bagianIndex}][komponen][${komponenIndex}][spesifikasi]" class="form-input" placeholder="Spesifikasi">
        </div>
    `;
    
    komponenContainer.appendChild(newKomponen);
    komponenIndices[bagianIndex] = komponenIndex + 1;
    
    // Show remove button for all komponen items in this bagian
    bagianItem.querySelectorAll('.komponen-item').forEach((item, index) => {
        if (index > 0) {
            item.querySelector('.btn-danger').style.display = 'block';
        }
    });
}

function removeKomponen(btn) {
    btn.closest('.komponen-item').remove();
}
</script>
@endsection

