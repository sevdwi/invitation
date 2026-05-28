<?php
// FILE: app/Models/Rsvp.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rsvp extends Model
{
    protected $fillable = [
        'invitation_id', 'guest_name', 'guest_phone',
        'attendance', 'guest_count', 'note',
    ];

    public function invitation(): BelongsTo
    {
        return $this->belongsTo(Invitation::class);
    }
}