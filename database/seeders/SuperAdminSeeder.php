<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'superadmin@gihes.com'],
            [
                'name' => 'SuperAdmin',
                'password' => Hash::make('gihes2026'),
                'whatsapp' => '6281277761133',
                'nationality' => 'Indonesia',
                'institution_name' => 'GIHES Core Team',
                'role' => 'admin',
            ]
        );
        
        $this->command->info('Super Admin berhasil dibuat/diupdate!');
    }
}