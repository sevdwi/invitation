<?php
// FILE: app/Models/Wish.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Wish extends Model
{
    protected $fillable = [
        'invitation_id', 'sender_name', 'message', 'sender_ip', 'is_approved',
    ];

    protected $casts = ['is_approved' => 'boolean'];

    public function invitation(): BelongsTo
    {
        return $this->belongsTo(Invitation::class);
    }
}