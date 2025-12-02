<div class="sidebar" id="sidebar">
    <div class="sidebar-content">
        <div class="sidebar-header">
            <h2>Menu</h2>
        </div>
        <nav class="sidebar-nav">
            <a href="{{ route('dashboard') }}" class="sidebar-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                </svg>
                <span>Beranda</span>
            </a>
            <a href="{{ route('assets.index') }}" class="sidebar-item {{ request()->routeIs('assets.*') ? 'active' : '' }}">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="9" y1="3" x2="9" y2="21"></line>
                </svg>
                <span>Assets</span>
            </a>
            <a href="{{ route('karyawan.index') }}" class="sidebar-item {{ request()->routeIs('karyawan.*') ? 'active' : '' }}">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                </svg>
                <span>Daftar Karyawan</span>
            </a>
            <a href="{{ route('maintenance-template.index') }}" class="sidebar-item {{ request()->routeIs('maintenance-template.*') ? 'active' : '' }}">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path>
                </svg>
                <span>Maintenance Schedule</span>
            </a>
            <a href="{{ route('check-sheet-template.index') }}" class="sidebar-item {{ request()->routeIs('check-sheet-template.*') ? 'active' : '' }}">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M9 11l3 3L22 4"></path>
                    <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                </svg>
                <span>Cek Sheet Schedule</span>
            </a>
            <div class="sidebar-spacer"></div>
            <button type="button" onclick="showLogoutModal()" class="sidebar-item sidebar-item-logout">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                    <polyline points="16 17 21 12 16 7"></polyline>
                    <line x1="21" y1="12" x2="9" y2="12"></line>
                </svg>
                <span>Keluar</span>
            </button>
        </nav>
    </div>
</div>

<!-- Logout Modal -->
<div id="logoutModal" class="modal-overlay" style="display: none;" onclick="if(event.target === this) hideLogoutModal()">
    <div class="modal-container">
        <div class="modal-header">
            <div class="modal-icon">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#f44336" stroke-width="2">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                    <polyline points="16 17 21 12 16 7"></polyline>
                    <line x1="21" y1="12" x2="9" y2="12"></line>
                </svg>
            </div>
            <h3 class="modal-title">Konfirmasi Keluar</h3>
            <p class="modal-subtitle">Apakah Anda yakin ingin keluar dari sistem?</p>
        </div>
        <div class="modal-body">
            <p style="color: #666; text-align: center; margin-bottom: 24px;">
                Anda akan keluar dari akun <strong>{{ session('user')['full_name'] ?? 'Admin' }}</strong>
            </p>
        </div>
        <div class="modal-actions">
            <button type="button" onclick="hideLogoutModal()" class="btn-modal btn-cancel">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 6px;">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
                Batal
            </button>
            <form action="{{ route('logout') }}" method="POST" style="display: inline-block;">
                @csrf
                <button type="submit" class="btn-modal btn-logout">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 6px;">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                        <polyline points="16 17 21 12 16 7"></polyline>
                        <line x1="21" y1="12" x2="9" y2="12"></line>
                    </svg>
                    Ya, Keluar
                </button>
            </form>
        </div>
    </div>
</div>

<style>
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(4px);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    animation: fadeIn 0.2s ease-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

.modal-container {
    background: white;
    border-radius: 16px;
    max-width: 440px;
    width: 90%;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    animation: slideUp 0.3s ease-out;
}

@keyframes slideUp {
    from {
        transform: translateY(30px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.modal-header {
    padding: 32px 32px 16px;
    text-align: center;
}

.modal-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 20px;
    background: rgba(244, 67, 54, 0.1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-title {
    font-size: 24px;
    font-weight: 700;
    color: #022415;
    margin: 0 0 8px 0;
}

.modal-subtitle {
    font-size: 14px;
    color: #666;
    margin: 0;
}

.modal-body {
    padding: 16px 32px;
}

.modal-actions {
    padding: 24px 32px 32px;
    display: flex;
    gap: 12px;
    justify-content: center;
}

.btn-modal {
    padding: 12px 24px;
    border-radius: 8px;
    border: none;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
    min-width: 140px;
}

.btn-cancel {
    background: #f5f5f5;
    color: #666;
}

.btn-cancel:hover {
    background: #e0e0e0;
    transform: translateY(-1px);
}

.btn-logout {
    background: linear-gradient(135deg, #f44336 0%, #d32f2f 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(244, 67, 54, 0.3);
}

.btn-logout:hover {
    box-shadow: 0 6px 16px rgba(244, 67, 54, 0.4);
    transform: translateY(-1px);
}
</style>

<script>
function showLogoutModal() {
    document.getElementById('logoutModal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function hideLogoutModal() {
    document.getElementById('logoutModal').style.display = 'none';
    document.body.style.overflow = '';
}

// Close modal on ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        hideLogoutModal();
    }
});
</script>

