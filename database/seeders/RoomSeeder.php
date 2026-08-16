<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Room;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        // Buat 10 Kamar Single (Kapasitas 1, Harga 4.5 Juta / $300)
        for ($i = 101; $i <= 110; $i++) {
            Room::create([
                'room_number' => 'S-' . $i,
                'type' => 'Single',
                'capacity' => 1,
                'booked_count' => 0,
                'price_idr' => 4500000,
                'price_usd' => 300,
                'is_available' => true,
            ]);
        }

        // Buat 10 Kamar Twin (Kapasitas 2, Harga 3 Juta / $200)
        // Saya buat salah satu kamar (T-201) sudah terisi 1 orang sebagai simulasi Roommate!
        Room::create([
            'room_number' => 'T-201',
            'type' => 'Twin',
            'capacity' => 2,
            'booked_count' => 1, // <--- Simulasi sudah ada 1 orang di sini
            'price_idr' => 3000000,
            'price_usd' => 200,
            'is_available' => true,
        ]);

        for ($i = 202; $i <= 210; $i++) {
            Room::create([
                'room_number' => 'T-' . $i,
                'type' => 'Twin',
                'capacity' => 2,
                'booked_count' => 0,
                'price_idr' => 3000000,
                'price_usd' => 200,
                'is_available' => true,
            ]);
        }
    }
}