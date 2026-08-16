<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan kolom role sudah ada di tabel users ya bro
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@gihes.com',
            'password' => Hash::make('password123'), // Ganti password sesukamu
            'whatsapp' => '6281234567890',
            'nationality' => 'Indonesia',
            'institution_name' => 'GIHES Admin',
            'role' => 'admin', // Ini kuncinya
        ]);
    }
}