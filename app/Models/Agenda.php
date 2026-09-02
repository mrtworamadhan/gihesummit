<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agenda extends Model
{
    protected $guarded = [];

    public function additionalClass()
    {
        return $this->belongsTo(AdditionalClass::class);
    }

    public function attendanceLogs()
    {
        return $this->hasMany(AttendanceLog::class);
    }
}