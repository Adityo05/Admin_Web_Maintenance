<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * PostgREST Service
 * Alternatif untuk koneksi Supabase menggunakan REST API langsung
 * Mirip dengan cara Flutter menggunakan Supabase Client
 */
class PostgrestService
{
    private string $baseUrl;
    private string $apiKey;
    private array $headers;

    public function __construct()
    {
        $this->baseUrl = config('supabase.url', 'https://dxzkxvczjdviuvmgwsft.supabase.co');
        $this->apiKey = config('supabase.key', '');
        
        $this->headers = [
            'apikey' => $this->apiKey,
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
            'Prefer' => 'return=representation',
        ];
    }

    /**
     * Query table (SELECT)
     */
    public function from(string $table)
    {
        return new PostgrestQueryBuilder($this->baseUrl, $table, $this->headers);
    }

    /**
     * Get Supabase Auth endpoint
     */
    public function auth()
    {
        return new SupabaseAuthService($this->baseUrl, $this->apiKey);
    }

    /**
     * Get Supabase Storage endpoint
     */
    public function storage()
    {
        return new SupabaseStorageService($this->baseUrl, $this->apiKey);
    }
}

/**
 * PostgREST Query Builder
 * Mirip dengan Supabase Client query builder
 */
class PostgrestQueryBuilder
{
    private string $baseUrl;
    private string $table;
    private array $headers;
    private array $filters = [];
    private ?string $select = '*';
    private ?string $orderBy = null;
    private ?int $limit = null;
    private ?int $offset = null;

    public function __construct(string $baseUrl, string $table, array $headers)
    {
        $this->baseUrl = $baseUrl;
        $this->table = $table;
        $this->headers = $headers;
    }

    /**
     * Select columns
     */
    public function select(string $columns): self
    {
        $this->select = $columns;
        return $this;
    }

    /**
     * Filter: equals
     */
    public function eq(string $column, $value): self
    {
        $this->filters[] = "$column=eq.$value";
        return $this;
    }

    /**
     * Filter: not equals
     */
    public function neq(string $column, $value): self
    {
        $this->filters[] = "$column=neq.$value";
        return $this;
    }

    /**
     * Filter: greater than
     */
    public function gt(string $column, $value): self
    {
        $this->filters[] = "$column=gt.$value";
        return $this;
    }

    /**
     * Filter: less than
     */
    public function lt(string $column, $value): self
    {
        $this->filters[] = "$column=lt.$value";
        return $this;
    }

    /**
     * Filter: like (case insensitive)
     */
    public function ilike(string $column, string $value): self
    {
        $this->filters[] = "$column=ilike.*$value*";
        return $this;
    }

    /**
     * Order by
     */
    public function order(string $column, string $direction = 'asc'): self
    {
        $this->orderBy = "$column.$direction";
        return $this;
    }

    /**
     * Limit results
     */
    public function limit(int $count): self
    {
        $this->limit = $count;
        return $this;
    }

    /**
     * Offset
     */
    public function offset(int $count): self
    {
        $this->offset = $count;
        return $this;
    }

    /**
     * Get single result
     */
    public function single(): self
    {
        $this->headers['Accept'] = 'application/vnd.pgjson.object+json';
        return $this;
    }

    /**
     * Execute SELECT query
     */
    public function execute()
    {
        $url = "{$this->baseUrl}/rest/v1/{$this->table}";
        
        $params = [];
        if ($this->select) {
            $params['select'] = $this->select;
        }
        if (!empty($this->filters)) {
            $params = array_merge($params, $this->filters);
        }
        if ($this->orderBy) {
            $params['order'] = $this->orderBy;
        }
        if ($this->limit) {
            $params['limit'] = $this->limit;
        }
        if ($this->offset) {
            $params['offset'] = $this->offset;
        }

        try {
            $response = Http::withHeaders($this->headers)
                ->get($url, $params);

            if ($response->successful()) {
                return (object) [
                    'data' => $response->json(),
                    'status' => $response->status(),
                ];
            }

            throw new \Exception('Query failed: ' . $response->body());
        } catch (\Exception $e) {
            Log::error('PostgREST query error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Insert data
     */
    public function insert(array $data)
    {
        $url = "{$this->baseUrl}/rest/v1/{$this->table}";
        
        try {
            $response = Http::withHeaders($this->headers)
                ->post($url, $data);

            if ($response->successful()) {
                return (object) [
                    'data' => $response->json(),
                    'status' => $response->status(),
                ];
            }

            throw new \Exception('Insert failed: ' . $response->body());
        } catch (\Exception $e) {
            Log::error('PostgREST insert error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Update data
     */
    public function update(array $data)
    {
        $url = "{$this->baseUrl}/rest/v1/{$this->table}";
        
        $params = [];
        if (!empty($this->filters)) {
            $params = $this->filters;
        }

        try {
            $response = Http::withHeaders($this->headers)
                ->patch($url . '?' . implode('&', $params), $data);

            if ($response->successful()) {
                return (object) [
                    'data' => $response->json(),
                    'status' => $response->status(),
                ];
            }

            throw new \Exception('Update failed: ' . $response->body());
        } catch (\Exception $e) {
            Log::error('PostgREST update error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Delete data
     */
    public function delete()
    {
        $url = "{$this->baseUrl}/rest/v1/{$this->table}";
        
        $params = [];
        if (!empty($this->filters)) {
            $params = $this->filters;
        }

        try {
            $response = Http::withHeaders($this->headers)
                ->delete($url . '?' . implode('&', $params));

            if ($response->successful()) {
                return (object) [
                    'data' => $response->json(),
                    'status' => $response->status(),
                ];
            }

            throw new \Exception('Delete failed: ' . $response->body());
        } catch (\Exception $e) {
            Log::error('PostgREST delete error: ' . $e->getMessage());
            throw $e;
        }
    }
}

/**
 * Supabase Auth Service (Placeholder)
 */
class SupabaseAuthService
{
    private string $baseUrl;
    private string $apiKey;

    public function __construct(string $baseUrl, string $apiKey)
    {
        $this->baseUrl = $baseUrl;
        $this->apiKey = $apiKey;
    }

    // Implement auth methods jika diperlukan
}

/**
 * Supabase Storage Service (Placeholder)
 */
class SupabaseStorageService
{
    private string $baseUrl;
    private string $apiKey;

    public function __construct(string $baseUrl, string $apiKey)
    {
        $this->baseUrl = $baseUrl;
        $this->apiKey = $apiKey;
    }

    // Implement storage methods jika diperlukan
}

