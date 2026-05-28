<?php
// FILE: app/Models/Invitation.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invitation extends Model
{
    protected $fillable = [
        'groom_name', 'groom_full_name', 'groom_father', 'groom_mother', 'groom_photo',
        'bride_name',  'bride_full_name',  'bride_father',  'bride_mother',  'bride_photo',
        'akad_date', 'akad_location', 'akad_address',
        'reception_date', 'reception_location', 'reception_address',
        'maps_lat', 'maps_lng', 'music_file', 'hero_image', 'is_active',
    ];

    protected $casts = [
        'akad_date'      => 'datetime',
        'reception_date' => 'datetime',
        'is_active'      => 'boolean',
    ];

    public function rsvps(): HasMany
    {
        return $this->hasMany(Rsvp::class);
    }

    public function galleries(): HasMany
    {
        return $this->hasMany(Gallery::class)->orderBy('order');
    }

    public function wishes(): HasMany
    {
        return $this->hasMany(Wish::class)->latest();
    }
}