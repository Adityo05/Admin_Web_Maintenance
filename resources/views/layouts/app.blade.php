<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin - Monitoring Maintenance')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('styles')
</head>
<body>
    @if(session('user'))
        <div class="app-container">
            <!-- Header -->
            @include('layouts.header')
            
            <!-- Main Content -->
            <div class="main-content">
                @include('layouts.sidebar')
                
                <div class="sidebar-overlay" onclick="toggleSidebar()"></div>
                
                <div class="content-area">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-error">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    @yield('content')
                </div>
            </div>
        </div>
    @else
        @yield('content')
    @endif

    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>

