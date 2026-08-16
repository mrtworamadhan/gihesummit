<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    // Arahkan ke nama tabel yang tepat sesuai SQL
    protected $table = 'country';

    // Matikan timestamps karena di SQL tidak ada created_at & updated_at
    public $timestamps = false;

    // Izinkan semua kolom untuk diakses
    protected $guarded = [];
}