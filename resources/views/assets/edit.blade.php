@extends('layouts.app')

@section('title', 'Edit Asset')

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
                    <h2 style="color: white; font-size: 20px; font-weight: bold; margin: 0;">Edit Data Aset</h2>
                </div>
                <a href="{{ route('assets.index') }}" style="color: white; text-decoration: none;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </a>
            </div>
        </div>
        
        <form action="{{ route('assets.update', $asset->id) }}" method="POST" enctype="multipart/form-data" style="padding: 24px;">
            @csrf
            @method('PUT')
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                <div class="form-group">
                    <label for="nama_assets" class="form-label">Nama Aset *</label>
                    <div class="form-input-group">
                        <svg class="form-input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                        </svg>
                        <input type="text" id="nama_assets" name="nama_assets" class="form-input" value="{{ $asset->nama_assets }}" required>
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
                        <input type="text" id="kode_assets" name="kode_assets" class="form-input" value="{{ $asset->kode_assets }}">
                    </div>
                </div>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                <div class="form-group">
                    <label for="jenis_assets" class="form-label">Jenis Aset *</label>
                    <select id="jenis_assets" name="jenis_assets" class="form-input" required>
                        <option value="">Pilih Jenis Aset</option>
                        <option value="Mesin Produksi" {{ $asset->jenis_assets == 'Mesin Produksi' ? 'selected' : '' }}>Mesin Produksi</option>
                        <option value="Alat Berat" {{ $asset->jenis_assets == 'Alat Berat' ? 'selected' : '' }}>Alat Berat</option>
                        <option value="Listrik" {{ $asset->jenis_assets == 'Listrik' ? 'selected' : '' }}>Listrik</option>
                    </select>
                    @error('jenis_assets')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="mt_priority" class="form-label">Prioritas</label>
                    <select id="mt_priority" name="mt_priority" class="form-input">
                        <option value="">Pilih Prioritas</option>
                        <option value="Low" {{ $asset->mt_priority == 'Low' ? 'selected' : '' }}>Low</option>
                        <option value="Medium" {{ $asset->mt_priority == 'Medium' ? 'selected' : '' }}>Medium</option>
                        <option value="High" {{ $asset->mt_priority == 'High' ? 'selected' : '' }}>High</option>
                    </select>
                </div>
            </div>
            
            <div style="margin: 24px 0; border-top: 2px solid #e0e0e0;"></div>
            
            <div id="bagian-container">
                @php
                    $bagianIndex = 0;
                    $bagianList = $asset->bagianMesin;
                @endphp
                @foreach($bagianList as $bagian)
                    <div class="bagian-item" style="margin-bottom: 24px; padding: 16px; border: 1px solid #e0e0e0; border-radius: 8px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                            <h3 style="font-size: 16px; font-weight: bold; color: #022415;">Bagian {{ $bagianIndex + 1 }}</h3>
                            <button type="button" onclick="removeBagian(this)" {{ $bagianIndex == 0 && $bagianList->count() == 1 ? 'style="display: none;"' : 'style="background-color: #F44336; color: white; border: none; border-radius: 6px; padding: 6px 10px; cursor: pointer; width: 36px; height: 36px; transition: all 0.2s;"' }} onmouseover="this.style.transform='scale(1.1)'; this.style.boxShadow='0 2px 8px rgba(244,67,54,0.3)'" onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='none'" title="Hapus Bagian">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="3 6 5 6 21 6"></polyline>
                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                </svg>
                            </button>
                        </div>
                        
                        <div class="form-group" style="margin-bottom: 16px;">
                            <label class="form-label">Nama Bagian *</label>
                            <input type="text" name="bagian[{{ $bagianIndex }}][nama_bagian]" class="form-input" value="{{ $bagian->nama_bagian }}" required>
                        </div>
                        
                        <div class="komponen-container">
                            @php
                                $komponenIndex = 0;
                                $komponenList = $bagian->komponenAssets;
                            @endphp
                            @foreach($komponenList as $komponen)
                                <div class="komponen-item" style="margin-bottom: 12px;">
                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                                        <label class="form-label">Komponen {{ $komponenIndex + 1 }}</label>
                                        <button type="button" onclick="removeKomponen(this)" {{ $komponenIndex == 0 && $komponenList->count() == 1 ? 'style="display: none;"' : 'style="background-color: #F44336; color: white; border: none; border-radius: 6px; padding: 6px 10px; cursor: pointer; width: 36px; height: 36px; transition: all 0.2s;"' }} onmouseover="this.style.transform='scale(1.1)'; this.style.boxShadow='0 2px 8px rgba(244,67,54,0.3)'" onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='none'" title="Hapus Komponen">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                                        <input type="text" name="bagian[{{ $bagianIndex }}][komponen][{{ $komponenIndex }}][nama_komponen]" class="form-input" placeholder="Nama Komponen *" value="{{ $komponen->nama_bagian }}" required>
                                        <input type="text" name="bagian[{{ $bagianIndex }}][komponen][{{ $komponenIndex }}][spesifikasi]" class="form-input" placeholder="Spesifikasi" value="{{ $komponen->spesifikasi }}">
                                    </div>
                                </div>
                                @php $komponenIndex++; @endphp
                            @endforeach
                        </div>
                        
                        <button type="button" class="btn btn-primary btn-sm" onclick="addKomponen(this, {{ $bagianIndex }})" style="margin-top: 8px;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 4px;">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                            Tambah Komponen
                        </button>
                    </div>
                    @php $bagianIndex++; @endphp
                @endforeach
            </div>
            
            <button type="button" class="btn btn-primary" onclick="addBagian()" style="margin-bottom: 24px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 6px;">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                Tambah Bagian
            </button>
            
            <div style="margin: 24px 0; border-top: 2px solid #e0e0e0;"></div>
            
            <div class="form-group">
                <label for="foto" class="form-label">Gambar Aset</label>
                @if($asset->foto)
                <div style="margin-bottom: 12px;">
                    <img src="{{ asset('storage/' . $asset->foto) }}" alt="Current Image" style="width: 160px; height: 100px; object-fit: cover; border-radius: 8px; border: 2px solid #e0e0e0; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <div style="margin-top: 8px; font-size: 12px; color: #666;">Gambar saat ini</div>
                </div>
                @endif
                <input type="file" id="foto" name="foto" accept="image/*" class="form-input" onchange="handleImageUpload(event)" style="margin-bottom: 12px;">
                
                <!-- Image Preview & Cropper -->
                <div id="imagePreviewContainer" style="display: none; margin-top: 16px;">
                    <div style="position: relative; width: 320px; height: 200px; overflow: hidden; border: 2px solid #e0e0e0; border-radius: 8px; margin: 0 auto; background-color: #f5f5f5; cursor: move;">
                        <img id="imagePreview" src="" alt="Preview" style="position: absolute; max-width: none; user-select: none;">
                    </div>
                    <div style="text-align: center; margin-top: 12px; color: #666; font-size: 13px;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle;">
                            <path d="M5 9l-3 3 3 3M9 5l3-3 3 3M15 19l-3 3-3-3M19 9l3 3-3 3"></path>
                        </svg>
                        Geser gambar untuk menyesuaikan posisi
                    </div>
                </div>
                
                <small style="color: #666; font-size: 12px;">Kosongkan jika tidak ingin mengubah gambar</small>
                @error('foto')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            
            <div style="margin-top: 32px; display: flex; justify-content: flex-end; gap: 12px; padding-top: 24px; border-top: 1px solid #e0e0e0;">
                <a href="{{ route('assets.index') }}" class="btn btn-outline">
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

<script>
let bagianIndex = {{ $bagianList->count() }};
let komponenIndices = {!! json_encode(array_fill(0, $bagianList->count(), $bagianList->first()?->komponenAssets->count() ?? 1)) !!};

function addBagian() {
    const container = document.getElementById('bagian-container');
    const newBagian = document.createElement('div');
    newBagian.className = 'bagian-item';
    newBagian.style.cssText = 'margin-bottom: 24px; padding: 16px; border: 1px solid #e0e0e0; border-radius: 8px;';
    
    const bagianNum = bagianIndex;
    newBagian.innerHTML = `
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <h3 style="font-size: 16px; font-weight: bold; color: #022415;">Bagian ${bagianNum + 1}</h3>
            <button type="button" onclick="removeBagian(this)" title="Hapus Bagian" style="width: 36px; height: 36px; border-radius: 8px; border: none; background-color: #F44336; color: white; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.transform='scale(1.1)'; this.style.boxShadow='0 2px 8px rgba(244,67,54,0.3)'" onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='none'">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="3 6 5 6 21 6"></polyline>
                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    <line x1="10" y1="11" x2="10" y2="17"></line>
                    <line x1="14" y1="11" x2="14" y2="17"></line>
                </svg>
            </button>
        </div>
        <div class="form-group" style="margin-bottom: 16px;">
            <label class="form-label">Nama Bagian *</label>
            <input type="text" name="bagian[${bagianIndex}][nama_bagian]" class="form-input" required>
        </div>
        <div class="komponen-container">
            <div class="komponen-item" style="margin-bottom: 12px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                    <label class="form-label">Komponen 1</label>
                    <button type="button" onclick="removeKomponen(this)" title="Hapus Komponen" style="width: 36px; height: 36px; border-radius: 8px; border: none; background-color: #F44336; color: white; display: none; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.transform='scale(1.1)'; this.style.boxShadow='0 2px 8px rgba(244,67,54,0.3)'" onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='none'">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="3 6 5 6 21 6"></polyline>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                            <line x1="10" y1="11" x2="10" y2="17"></line>
                            <line x1="14" y1="11" x2="14" y2="17"></line>
                        </svg>
                    </button>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <input type="text" name="bagian[${bagianIndex}][komponen][0][nama_komponen]" class="form-input" placeholder="Nama Komponen *" required>
                    <input type="text" name="bagian[${bagianIndex}][komponen][0][spesifikasi]" class="form-input" placeholder="Spesifikasi">
                </div>
            </div>
        </div>
        <button type="button" class="btn btn-primary btn-sm" onclick="addKomponen(this, ${bagianIndex})" style="margin-top: 8px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 6px;">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Tambah Komponen
        </button>
    `;
    
    container.appendChild(newBagian);
    komponenIndices[bagianIndex] = 1;
    bagianIndex++;
    updateDeleteButtons(newBagian.querySelector('.komponen-container'));
    
    // Show remove button for all bagian items by checking if there are 2+ bagian
    const bagianItems = document.querySelectorAll('.bagian-item');
    bagianItems.forEach((item, index) => {
        const deleteBtn = item.querySelector('[onclick*="removeBagian"]');
        if (deleteBtn) {
            deleteBtn.style.display = bagianItems.length > 1 ? 'flex' : 'none';
        }
    });
}

function removeBagian(btn) {
    btn.closest('.bagian-item').remove();
}

function addKomponen(btn, bagianIdx) {
    const bagianItem = btn.closest('.bagian-item');
    const komponenContainer = bagianItem.querySelector('.komponen-container');
    const komponenIndex = komponenIndices[bagianIdx] || 1;
    
    const newKomponen = document.createElement('div');
    newKomponen.className = 'komponen-item';
    newKomponen.style.cssText = 'margin-bottom: 12px;';
    newKomponen.innerHTML = `
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
            <label class="form-label">Komponen ${komponenIndex + 1}</label>
            <button type="button" onclick="removeKomponen(this)" title="Hapus Komponen" style="width: 36px; height: 36px; border-radius: 8px; border: none; background-color: #F44336; color: white; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.transform='scale(1.1)'; this.style.boxShadow='0 2px 8px rgba(244,67,54,0.3)'" onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='none'">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="3 6 5 6 21 6"></polyline>
                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    <line x1="10" y1="11" x2="10" y2="17"></line>
                    <line x1="14" y1="11" x2="14" y2="17"></line>
                </svg>
            </button>
        </div>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
            <input type="text" name="bagian[${bagianIdx}][komponen][${komponenIndex}][nama_komponen]" class="form-input" placeholder="Nama Komponen *" required>
            <input type="text" name="bagian[${bagianIdx}][komponen][${komponenIndex}][spesifikasi]" class="form-input" placeholder="Spesifikasi">
        </div>
    `;
    
    komponenContainer.appendChild(newKomponen);
    komponenIndices[bagianIdx] = komponenIndex + 1;
    updateDeleteButtons(komponenContainer);
}

function removeKomponen(btn) {
    btn.closest('.komponen-item').remove();
}

// Image Upload Handler with Drag to Position
let isDragging = false;
let startX, startY, initialX, initialY;

function handleImageUpload(event) {
    const file = event.target.files[0];
    if (!file) return;
    
    const reader = new FileReader();
    reader.onload = function(e) {
        const preview = document.getElementById('imagePreview');
        const container = document.getElementById('imagePreviewContainer');
        
        preview.src = e.target.result;
        container.style.display = 'block';
        
        // Set initial position
        preview.onload = function() {
            const containerWidth = 320;
            const containerHeight = 200;
            const imgWidth = preview.naturalWidth;
            const imgHeight = preview.naturalHeight;
            
            // Calculate scale to fill container (cover behavior)
            const scaleX = containerWidth / imgWidth;
            const scaleY = containerHeight / imgHeight;
            const scale = Math.max(scaleX, scaleY);
            
            const newWidth = imgWidth * scale;
            const newHeight = imgHeight * scale;
            
            preview.style.width = newWidth + 'px';
            preview.style.height = newHeight + 'px';
            
            // Center image
            const offsetX = (containerWidth - newWidth) / 2;
            const offsetY = (containerHeight - newHeight) / 2;
            
            preview.style.left = offsetX + 'px';
            preview.style.top = offsetY + 'px';
            
            initialX = offsetX;
            initialY = offsetY;
        };
    };
    reader.readAsDataURL(file);
}

// Drag functionality
document.addEventListener('DOMContentLoaded', function() {
    const preview = document.getElementById('imagePreview');
    const container = preview.parentElement;
    
    container.addEventListener('mousedown', startDrag);
    document.addEventListener('mousemove', drag);
    document.addEventListener('mouseup', stopDrag);
    
    // Touch support
    container.addEventListener('touchstart', handleTouchStart);
    document.addEventListener('touchmove', handleTouchMove);
    document.addEventListener('touchend', stopDrag);
});

function startDrag(e) {
    const preview = document.getElementById('imagePreview');
    if (!preview.src) return;
    
    isDragging = true;
    startX = e.clientX;
    startY = e.clientY;
    initialX = parseFloat(preview.style.left) || 0;
    initialY = parseFloat(preview.style.top) || 0;
    e.preventDefault();
}

function drag(e) {
    if (!isDragging) return;
    
    const preview = document.getElementById('imagePreview');
    const deltaX = e.clientX - startX;
    const deltaY = e.clientY - startY;
    
    preview.style.left = (initialX + deltaX) + 'px';
    preview.style.top = (initialY + deltaY) + 'px';
}

function stopDrag() {
    isDragging = false;
}

function handleTouchStart(e) {
    const touch = e.touches[0];
    const preview = document.getElementById('imagePreview');
    if (!preview.src) return;
    
    isDragging = true;
    startX = touch.clientX;
    startY = touch.clientY;
    initialX = parseFloat(preview.style.left) || 0;
    initialY = parseFloat(preview.style.top) || 0;
    e.preventDefault();
}

function handleTouchMove(e) {
    if (!isDragging) return;
    
    const touch = e.touches[0];
    const preview = document.getElementById('imagePreview');
    const deltaX = touch.clientX - startX;
    const deltaY = touch.clientY - startY;
    
    preview.style.left = (initialX + deltaX) + 'px';
    preview.style.top = (initialY + deltaY) + 'px';
    e.preventDefault();
}
</script>
@endsection

