<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Registration extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'preferred_working_group' => 'array',
        'collaboration_interest' => 'array',
        'estimated_arrival' => 'datetime',
        'estimated_departure' => 'datetime',
        'needs_accommodation_assist' => 'boolean',
        'requires_visa_letter' => 'boolean',
        'consent_data_use' => 'boolean',
        'is_waiting_list' => 'boolean',
        // Tambahan casting dari migrasi terbaru
        'tour_guide_needed' => 'boolean',
        'is_requested_confirmation' => 'boolean',
        
    ];

    public static function getRemainingQuota()
    {
        $maxQuota = 300;
        
        $paidCount = self::where('is_waiting_list', false)
            ->whereHas('payment', function($query) {
                $query->where('payment_status', 'paid');
            })->count();

        return max(0, $maxQuota - $paidCount);
    }
    
    public function participant(): BelongsTo
    {
        return $this->belongsTo(Participant::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    // Tambahan Relasi Many-to-Many ke Additional Class
    public function additionalClasses(): BelongsToMany
    {
        return $this->belongsToMany(AdditionalClass::class);
    }
}