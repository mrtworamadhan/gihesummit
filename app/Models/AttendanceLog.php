<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceLog extends Model
{
    protected $guarded = [];

    public function agenda()
    {
        return $this->belongsTo(Agenda::class);
    }

    public function gatekeeper()
    {
        return $this->belongsTo(Gatekeeper::class);
    }

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }
}