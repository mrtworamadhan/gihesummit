<?php

namespace App\Console\Commands;

use App\Models\Registration;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:fix-classes')]
#[Description('Command description')]
class FixAdditionalClasses extends Command
{

    protected $description = 'Memperbaiki peserta dengan kelas ganda agar dibagi rata (50:50)';

    public function handle()
    {
        $this->info('Menganalisis data kelas peserta...');

        $registrations = Registration::with('additionalClasses')->get()->filter(function ($reg) {
            return $reg->additionalClasses->count() > 1;
        });

        if ($registrations->isEmpty()) {
            $this->info('Aman! Tidak ada peserta dengan kelas ganda.');
            return;
        }

        $this->info('Ditemukan ' . $registrations->count() . ' peserta dengan kelas ganda. Memulai pembagian...');

        $toggle = true; 
        $fixedCount = 0;

        foreach ($registrations as $reg) {
            $classIds = $reg->additionalClasses->pluck('id')->toArray();

            // 3. Logika Bagi Rata: Peserta A masuk kelas 1, Peserta B masuk kelas 2, dst.
            $keptClassId = $toggle ? $classIds[0] : (isset($classIds[1]) ? $classIds[1] : $classIds[0]);

            // 4. Timpa database agar hanya menyisakan 1 kelas tersebut
            $reg->additionalClasses()->sync([$keptClassId]);

            $toggle = !$toggle; 
            $fixedCount++;
        }

        $this->info("Selesai! {$fixedCount} data peserta berhasil diperbaiki dan didistribusikan secara merata.");
    }
}
