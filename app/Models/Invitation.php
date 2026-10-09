<?php
// FILE: app/Models/Invitation.php

namespace App\Models;

use App\Services\GoogleDriveAlbum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Invitation extends Model
{
    // Slug yang tidak boleh dipakai karena bentrok dengan URL aplikasi
    public const RESERVED_SLUGS = [
        'invitation', 'login', 'logout', 'register', 'auth', 'dashboard', 'admin',
        'api', 'storage', 'up', 'rsvp', 'wishes',
    ];

    // Tema yang tersedia
    public const AVAILABLE_THEMES = [
        'netflix'    => 'Netflix (Gelap, Merah, Mewah)',
        'minimalist' => 'Minimalist (Bersih, Putih, Elegan)',
    ];

    protected $fillable = [
        'user_id', 'slug', 'theme',
        'groom_name', 'groom_full_name', 'groom_father', 'groom_mother', 'groom_photo',
        'bride_name',  'bride_full_name',  'bride_father',  'bride_mother',  'bride_photo',
        'akad_date', 'akad_location', 'akad_address',
        'reception_date', 'reception_location', 'reception_address',
        'maps_lat', 'maps_lng', 'music_file', 'hero_image', 'drive_folder_url', 'is_active',
    ];

    protected $casts = [
        'akad_date'      => 'datetime',
        'reception_date' => 'datetime',
        'is_active'      => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (Invitation $invitation) {
            $invitation->slug ??= static::uniqueSlug($invitation->groom_name . '-' . $invitation->bride_name);
        });
    }

    // Undangan diakses lewat /{slug}, bukan id
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public static function uniqueSlug(string $text): string
    {
        $base = Str::slug($text) ?: 'undangan';
        $slug = $base;

        for ($i = 2; in_array($slug, self::RESERVED_SLUGS, true) || static::where('slug', $slug)->exists(); $i++) {
            $slug = $base . '-' . $i;
        }

        return $slug;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

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

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class)->latest();
    }

    // Id folder Google Drive dari link album; null bila belum diisi atau linknya tidak dikenali
    public function getDriveFolderIdAttribute(): ?string
    {
        return GoogleDriveAlbum::folderId($this->drive_folder_url);
    }
}
