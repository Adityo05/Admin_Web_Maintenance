<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

     <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/NKP.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/NKP.png') }}">
    
    <title>Login - Admin Monitoring Maintenance</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="login-container">
        <div class="login-background">
            <!-- Canvas for wave animation (same as Flutter) -->
            <canvas id="waveCanvas"></canvas>
        </div>
        
        <!-- Header Top -->
        <div class="login-header-top">
            <img src="{{ asset('images/NKP.png') }}" alt="NKP Logo" class="login-logo" onerror="this.style.display='none'">
            <span class="login-company-name">PT. New Kalbar Processors</span>
        </div>
        
        <!-- Left Side - Title -->
        <div class="login-left">
            <h1 class="login-title">
                Aplikasi Monitoring<br>Maintenance Mesin
            </h1>
        </div>
        
        <!-- Right Side - Login Form -->
        <div class="login-right">
            <div class="login-form-container">
                <form method="POST" action="{{ route('login') }}" id="loginForm">
                    @csrf
                    
                    <div class="login-form-header">
                        <h2 class="login-form-title">Selamat Datang</h2>
                        <p class="login-form-subtitle">Masuk ke akun admin Anda</p>
                    </div>
                    
                    <div class="form-group">
                        <label for="email" class="form-label">Email</label>
                        <div class="form-input-group">
                            <svg class="form-input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                <polyline points="22,6 12,13 2,6"></polyline>
                            </svg>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                class="form-input @error('email') error @enderror" 
                                placeholder="Masukkan email Anda"
                                value="{{ old('email') }}"
                                required
                                autofocus
                            >
                        </div>
                        @error('email')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <div class="form-input-group">
                            <svg class="form-input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                            </svg>
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                class="form-input @error('password') error @enderror" 
                                placeholder="Masukkan password Anda"
                                required
                            >
                        </div>
                        @error('password')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group" style="margin-top: 40px;">
                        <button type="submit" class="btn btn-primary" id="loginBtn">
                            <span>Masuk</span>
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                <polyline points="12 5 19 12 12 19"></polyline>
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        // Wave Animation - Same as Flutter
        const canvas = document.getElementById('waveCanvas');
        const ctx = canvas.getContext('2d');
        let animationValue = 0;
        
        function resizeCanvas() {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
        }
        
        function drawWave() {
            const width = canvas.width;
            const height = canvas.height;
            
            ctx.clearRect(0, 0, width, height);
            
            // First wave
            const waveHeight = 50;
            const waveLength = width / 1.2;
            const baseY = height * 0.7;
            const waveOffset = animationValue * 2 * Math.PI;
            const segments = 120;
            
            ctx.beginPath();
            const startY = baseY + waveHeight * Math.sin(waveOffset);
            ctx.moveTo(0, startY);
            
            for (let i = 1; i <= segments; i++) {
                const x = (width / segments) * i;
                const y = baseY + waveHeight * Math.sin((x / waveLength * 2 * Math.PI) + waveOffset);
                ctx.lineTo(x, y);
            }
            
            ctx.lineTo(width, height);
            ctx.lineTo(0, height);
            ctx.closePath();
            ctx.fillStyle = 'rgba(255, 255, 255, 0.05)';
            ctx.fill();
            
            // Second wave (opposite direction)
            const baseY2 = height * 0.8;
            const waveLength2 = width / 1.2;
            const waveOffset2 = -animationValue * 2 * Math.PI;
            
            ctx.beginPath();
            const startY2 = baseY2 + waveHeight * 0.7 * Math.sin(waveOffset2);
            ctx.moveTo(0, startY2);
            
            for (let i = 1; i <= segments; i++) {
                const x = (width / segments) * i;
                const y = baseY2 + waveHeight * 0.7 * Math.sin((x / waveLength2 * 2 * Math.PI) + waveOffset2);
                ctx.lineTo(x, y);
            }
            
            ctx.lineTo(width, height);
            ctx.lineTo(0, height);
            ctx.closePath();
            ctx.fillStyle = 'rgba(255, 255, 255, 0.05)';
            ctx.fill();
            
            animationValue += 0.000333; // 1/(5*60) for 5 seconds at 60fps
            if (animationValue >= 1) animationValue = 0;
            
            requestAnimationFrame(drawWave);
        }
        
        resizeCanvas();
        window.addEventListener('resize', resizeCanvas);
        drawWave();
        
        // Form submit handler
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const btn = document.getElementById('loginBtn');
            btn.disabled = true;
            btn.innerHTML = '<div style="width: 20px; height: 20px; border: 2px solid white; border-top-color: transparent; border-radius: 50%; animation: spin 0.8s linear infinite;"></div>';
        });
        
        // Add spin animation
        const style = document.createElement('style');
        style.textContent = '@keyframes spin { to { transform: rotate(360deg); } }';
        document.head.appendChild(style);
    </script>
</body>
</html>

