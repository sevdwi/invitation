<?php
// FILE: app/Models/Attendance.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// Satu baris daftar hadir, diisi tamu sendiri di lokasi acara
class Attendance extends Model
{
    protected $fillable = [
        'invitation_id', 'guest_name', 'guest_phone', 'address', 'guest_count',
    ];

    public function invitation(): BelongsTo
    {
        return $this->belongsTo(Invitation::class);
    }
}
