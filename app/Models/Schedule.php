<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'day',
        'time_range',
        'session_name',
        'topic_description',
        'speaker',
        'is_break',
    ];

    protected $casts = [
        'is_break' => 'boolean',
    ];
}