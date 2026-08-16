<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Certificate extends Model
{
    use HasFactory;

    // Tentukan kolom yang bisa diisi (Mass Assignment)
    protected $fillable = [
        'name',
        'type',
        'additional_class_id',
        'template_path',
        'is_published',
    ];

    // Casting tipe data agar is_published otomatis dibaca sebagai boolean (true/false)
    protected $casts = [
        'is_published' => 'boolean',
    ];

    /**
     * Relasi ke kelas tambahan.
     * Sertifikat bisa jadi terikat dengan satu kelas tertentu (contoh: Workshop AI).
     */
    public function additionalClass(): BelongsTo
    {
        return $this->belongsTo(AdditionalClass::class, 'additional_class_id');
    }
}