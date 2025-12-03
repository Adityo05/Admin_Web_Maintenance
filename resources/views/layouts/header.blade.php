@php
    $user = session('user');
    $greeting = 'Selamat ' . (date('H') < 12 ? 'Pagi' : (date('H') < 17 ? 'Siang' : 'Malam'));
    $title = $greeting . ', ' . ($user['full_name'] ?? 'Admin');
@endphp

<div class="header">
    <div class="header-content">
        <div class="header-left">
            <button class="menu-toggle" onclick="toggleSidebar()">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <line x1="3" y1="12" x2="21" y2="12"></line>
                    <line x1="3" y1="18" x2="21" y2="18"></line>
                </svg>
            </button>
            <span class="header-title">{{ $title }}</span>
        </div>
        <div class="header-center">
            <img src="{{ asset('images/NKP.png') }}" alt="NKP Logo" style="width:32px; height:32px; object-fit:contain; vertical-align:middle; margin-right:10px;">
            <span class="company-name" style="font-size:18px; font-weight:600; color:white; vertical-align:middle;">PT. NEW KALBAR PROCESSORS</span>
        </div>
        <div class="header-right">
            <div class="avatar">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
            </div>
        </div>
    </div>
</div>

