<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Gatekeeper extends Model
{
    protected $guarded = [];

    // Otomatis membuat magic token saat gatekeeper baru ditambahkan
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->magic_token)) {
                $model->magic_token = Str::random(32); // Generate 32 karakter acak
            }
        });
    }

    public function attendanceLogs()
    {
        return $this->hasMany(AttendanceLog::class);
    }
}