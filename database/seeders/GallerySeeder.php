<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Gallery;

class GallerySeeder extends Seeder
{
    public function run(): void
    {
        $photos = [
            ['title' => 'Opening Ceremony', 'image_path' => 'https://picsum.photos/seed/gihes1/800/600'],
            ['title' => 'Keynote Speaker Session', 'image_path' => 'https://picsum.photos/seed/gihes2/800/600'],
            ['title' => 'Gala Dinner at Monas', 'image_path' => 'https://picsum.photos/seed/gihes3/800/600'],
            ['title' => 'Networking Coffee Break', 'image_path' => 'https://picsum.photos/seed/gihes4/800/600'],
            ['title' => 'Parallel Dialogue Room 1', 'image_path' => 'https://picsum.photos/seed/gihes5/800/600'],
            ['title' => 'Cultural City Tour', 'image_path' => 'https://picsum.photos/seed/gihes6/800/600'],
        ];

        foreach ($photos as $photo) {
            Gallery::create([
                'title' => $photo['title'],
                'image_path' => $photo['image_path'],
                'is_published' => true,
            ]);
        }
    }
}