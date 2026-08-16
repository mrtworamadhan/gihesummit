<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'is_available' => 'boolean',
    ];

    // Relasi: Satu kamar bisa diisi oleh beberapa registrasi (jika Twin)
    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }

    public function checkAndLockAvailability()
    {
        // 1. Hitung HANYA penghuni di kamar ini yang statusnya sudah 'paid'
        $paidOccupants = $this->registrations()
            ->whereHas('payment', function ($query) {
                $query->where('payment_status', 'paid');
            })->count();

        // 2. Jika jumlah yang lunas sudah sama dengan atau lebih dari kapasitas, kunci kamar!
        if ($paidOccupants >= $this->capacity) {
            $this->update(['is_available' => false]);
        } else {
            // Jika ada yang batal / pindah kamar, buka lagi kamarnya
            $this->update(['is_available' => true]);
        }
    }
}