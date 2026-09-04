<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use App\Models\Participant;

class SendEidWhatsAppJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $participant;

    public function __construct(Participant $participant)
    {
        $this->participant = $participant;
    }

    public function handle(): void
    {
        $magicLink = route('eid.pass', $this->participant->uuid_barcode);

        $pesan = "Halo *{$this->participant->user->name}*,\n\n"
            . "Berikut adalah *Official E-Pass (Digital ID)* Anda untuk akses masuk ke acara GIHES 2026.\n\n"
            . "Silakan klik link aman di bawah ini untuk melihat dan menyimpan Barcode Anda:\n"
            . "{$magicLink}\n\n"
            . "Harap unduh gambar barcode tersebut dan siapkan di layar HP Anda saat akan melewati meja pemeriksaan (Gatekeeper).\n\n"
            . "Sampai jumpa di lokasi!\n"
            . "Salam,\n*Panitia GIHES 2026*";

        Http::withHeaders([
            'Authorization' => 'HEPveJtJ7oBEwKdJxDM4'
        ])->post('https://api.fonnte.com/send', [
            'target' => $this->participant->phone ?? $this->participant->user->whatsapp,
            'message' => $pesan,
        ]);
    }
}