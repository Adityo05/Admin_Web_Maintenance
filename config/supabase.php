<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Supabase Configuration
    |--------------------------------------------------------------------------
    |
    | Konfigurasi untuk koneksi ke Supabase menggunakan REST API
    | Sama seperti cara Flutter menggunakan Supabase Client
    |
    */

    'url' => env('SUPABASE_URL', 'https://dxzkxvczjdviuvmgwsft.supabase.co'),
    
    'key' => env('SUPABASE_ANON_KEY', 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImR4emt4dmN6amR2aXV2bWd3c2Z0Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3NjM1OTYyMzYsImV4cCI6MjA3OTE3MjIzNn0.cXYHeOepjMX8coJWqTaiz5GlEgAGhm35VMwIqvQhTTw'),
    
    'service_role_key' => env('SUPABASE_SERVICE_ROLE_KEY', ''),
    
    'options' => [
        'auth' => [
            'auto_refresh_token' => true,
            'persist_session' => true,
        ],
    ],
];

