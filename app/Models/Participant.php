<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Participant extends Model
{
    protected $guarded = ['id'];

    // Otomatis generate UUID untuk Barcode saat create data
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->uuid_barcode)) {
                $model->uuid_barcode = (string) Str::uuid();
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function registration(): HasOne
    {
        return $this->hasOne(Registration::class);
    }
    public function room(): HasOne
    {
        return $this->hasOne(Room::class);
    }
}
