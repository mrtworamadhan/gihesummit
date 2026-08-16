<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AdditionalClass;

class AdditionalClassSeeder extends Seeder
{
    public function run(): void
    {
        $classes = [
            [
                'name' => 'Campus Tour & Cultural Visit',
                'description' => 'A guided tour to explore the educational facilities and cultural showcase of the host institution.',
                'quota' => 50,
                'price_idr' => 0,
                'price_usd' => 0,
                'enrolled_count' => 0,
                'is_active' => true,
            ],
            [
                'name' => 'EdTech & AI in Islamic Education Workshop',
                'description' => 'Hands-on workshop discussing the implementation of AI and digital tools in modern pesantren and universities.',
                'quota' => 100,
                'price_idr' => 250000,
                'price_usd' => 15,
                'enrolled_count' => 0,
                'is_active' => true,
            ],
            [
                'name' => 'VIP Networking Gala Dinner',
                'description' => 'An exclusive networking dinner session with keynote speakers, rectors, and government officials.',
                'quota' => 30,
                'price_idr' => 750000,
                'price_usd' => 50,
                'enrolled_count' => 0,
                'is_active' => true,
            ],
            [
                'name' => 'International Journal Publication Clinic',
                'description' => 'Intensive coaching session on how to write and publish papers in Scopus-indexed Islamic studies journals.',
                'quota' => 40,
                'price_idr' => 0,
                'price_usd' => 0,
                'enrolled_count' => 0,
                'is_active' => true,
            ],
        ];

        foreach ($classes as $class) {
            AdditionalClass::create($class);
        }
    }
}