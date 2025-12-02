<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

/**
 * Supabase Service
 * Singleton service untuk mengelola Supabase client
 * Menggunakan PostgREST REST API (mirip dengan Supabase Client di Flutter)
 * 
 * Alternatif: Menggunakan PostgrestService yang lebih simple
 */
class SupabaseService
{
    private static ?SupabaseService $instance = null;
    private PostgrestService $client;

    private function __construct()
    {
        $this->client = new PostgrestService();
    }

    /**
     * Get singleton instance
     */
    public static function getInstance(): SupabaseService
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Get PostgREST client
     */
    public function getClient(): PostgrestService
    {
        return $this->client;
    }

    /**
     * Query table (mirip dengan Supabase Client)
     */
    public function from(string $table)
    {
        return $this->client->from($table);
    }

    /**
     * Get auth client
     */
    public function auth()
    {
        return $this->client->auth();
    }

    /**
     * Get storage client
     */
    public function storage()
    {
        return $this->client->storage();
    }
}

