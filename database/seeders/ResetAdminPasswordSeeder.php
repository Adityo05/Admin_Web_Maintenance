<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ResetAdminPasswordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Update password untuk admin.mt@mt.local
        DB::table('karyawan')
            ->where('email', 'admin.mt@mt.local')
            ->update([
                'password_hash' => Hash::make('admin123'), // Password baru: admin123
                'updated_at' => now(),
            ]);

        $this->command->info('Password untuk admin.mt@mt.local telah direset ke: admin123');
        
        // Update password untuk manajer juga jika diperlukan
        DB::table('karyawan')
            ->where('email', 'manajer@manager.local')
            ->update([
                'password_hash' => Hash::make('manajer123'), // Password baru: manajer123
                'updated_at' => now(),
            ]);

        $this->command->info('Password untuk manajer@manager.local telah direset ke: manajer123');
    }
}
