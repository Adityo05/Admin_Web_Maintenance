<?php

namespace App\Repositories;

use App\Services\SupabaseService;
use Illuminate\Support\Collection;

/**
 * Asset Repository menggunakan Supabase Client
 * Mirip dengan AssetSupabaseRepository di Flutter
 */
class SupabaseAssetRepository
{
    private $supabase;

    public function __construct()
    {
        $this->supabase = SupabaseService::getInstance();
    }

    /**
     * Get all assets dengan relasi
     */
    public function getAllAssets(): array
    {
        try {
            $response = $this->supabase->from('assets')
                ->select('*, bg_mesin(*, komponen_assets(*))')
                ->execute();

            return $response->data ?? [];
        } catch (\Exception $e) {
            throw new \Exception('Gagal mengambil data assets: ' . $e->getMessage());
        }
    }

    /**
     * Get asset by ID
     */
    public function getAssetById(string $id): ?array
    {
        try {
            $response = $this->supabase->from('assets')
                ->select('*, bg_mesin(*, komponen_assets(*))')
                ->eq('id', $id)
                ->single()
                ->execute();

            return $response->data ?? null;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Create asset
     */
    public function createAsset(array $data): array
    {
        try {
            $response = $this->supabase->from('assets')
                ->insert($data)
                ->execute();

            return $response->data[0] ?? [];
        } catch (\Exception $e) {
            throw new \Exception('Gagal membuat asset: ' . $e->getMessage());
        }
    }

    /**
     * Update asset
     */
    public function updateAsset(string $id, array $data): array
    {
        try {
            $response = $this->supabase->from('assets')
                ->update($data)
                ->eq('id', $id)
                ->execute();

            return $response->data[0] ?? [];
        } catch (\Exception $e) {
            throw new \Exception('Gagal mengupdate asset: ' . $e->getMessage());
        }
    }

    /**
     * Delete asset dengan cascade delete
     */
    public function deleteAsset(string $id): void
    {
        try {
            // Hapus maintenance_request
            $this->supabase->from('maintenance_request')
                ->delete()
                ->eq('assets_id', $id)
                ->execute();

            // Hapus mt_schedule
            $this->supabase->from('mt_schedule')
                ->delete()
                ->eq('assets_id', $id)
                ->execute();

            // Hapus user_assets
            $this->supabase->from('user_assets')
                ->delete()
                ->eq('assets_id', $id)
                ->execute();

            // Hapus komponen dan bagian mesin
            $komponenList = $this->supabase->from('komponen_assets')
                ->select('id')
                ->eq('assets_id', $id)
                ->execute();

            foreach ($komponenList->data ?? [] as $komponen) {
                // Hapus cek_sheet_template dan schedule terkait
                $templates = $this->supabase->from('cek_sheet_template')
                    ->select('id')
                    ->eq('komponen_assets_id', $komponen['id'])
                    ->execute();

                foreach ($templates->data ?? [] as $template) {
                    $schedules = $this->supabase->from('cek_sheet_schedule')
                        ->select('id')
                        ->eq('template_id', $template['id'])
                        ->execute();

                    foreach ($schedules->data ?? [] as $schedule) {
                        $this->supabase->from('notifikasi')
                            ->delete()
                            ->eq('jadwal_id', $schedule['id'])
                            ->execute();
                    }

                    $this->supabase->from('cek_sheet_schedule')
                        ->delete()
                        ->eq('template_id', $template['id'])
                        ->execute();
                }

                $this->supabase->from('cek_sheet_template')
                    ->delete()
                    ->eq('komponen_assets_id', $komponen['id'])
                    ->execute();
            }

            // Hapus komponen_assets
            $this->supabase->from('komponen_assets')
                ->delete()
                ->eq('assets_id', $id)
                ->execute();

            // Hapus bg_mesin dan mt_template
            $bgMesinList = $this->supabase->from('bg_mesin')
                ->select('id')
                ->eq('assets_id', $id)
                ->execute();

            foreach ($bgMesinList->data ?? [] as $bgMesin) {
                $this->supabase->from('mt_template')
                    ->delete()
                    ->eq('bg_mesin_id', $bgMesin['id'])
                    ->execute();
            }

            $this->supabase->from('bg_mesin')
                ->delete()
                ->eq('assets_id', $id)
                ->execute();

            // Hapus asset
            $this->supabase->from('assets')
                ->delete()
                ->eq('id', $id)
                ->execute();

        } catch (\Exception $e) {
            throw new \Exception('Gagal menghapus asset: ' . $e->getMessage());
        }
    }
}

