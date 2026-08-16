<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AdditionalClass extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function registrations(): BelongsToMany
    {
        return $this->belongsToMany(Registration::class);
    }
}