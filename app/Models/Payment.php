<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'needs_invoice' => 'boolean',
        'verified_at' => 'datetime',
        'base_amount' => 'decimal:2',
        'final_amount' => 'decimal:2',
    ];

    public function registration(): BelongsTo
    {
        return $this->belongsTo(Registration::class);
    }

    // Admin yang melakukan verifikasi pembayaran
    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
