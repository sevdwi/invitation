<?php
// FILE: app/Models/Gallery.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Gallery extends Model
{
    protected $fillable = ['invitation_id', 'image_path', 'caption', 'order'];

    public function invitation(): BelongsTo
    {
        return $this->belongsTo(Invitation::class);
    }
}