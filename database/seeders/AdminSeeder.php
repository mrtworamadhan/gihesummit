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
            ['email' => 'admin1@gihes.com'],
            [
                'name' => 'Fardana Khirzul Haq',
                'password' => Hash::make('password123'),
                'whatsapp' => '6281335444683',
                'nationality' => 'Indonesia',
                'institution_name' => 'GIHES Core Team',
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'admin2@gihes.com'],
            [
                'name' => 'Sayuda Patria',
                'password' => Hash::make('password123'),
                'whatsapp' => '6287771776677',
                'nationality' => 'Indonesia',
                'institution_name' => 'GIHES Core Team',
                'role' => 'admin',
            ]
        );
        
        $this->command->info('2 User Admin berhasil dibuat/diupdate!');
    }
}