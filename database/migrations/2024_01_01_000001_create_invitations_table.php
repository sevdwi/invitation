<?php
// FILE: database/migrations/2024_01_01_000001_create_invitations_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invitations', function (Blueprint $table) {
            $table->id();
            // Mempelai Pria
            $table->string('groom_name');
            $table->string('groom_full_name');
            $table->string('groom_father');
            $table->string('groom_mother');
            $table->string('groom_photo')->nullable();
            // Mempelai Wanita
            $table->string('bride_name');
            $table->string('bride_full_name');
            $table->string('bride_father');
            $table->string('bride_mother');
            $table->string('bride_photo')->nullable();
            // Akad
            $table->dateTime('akad_date');
            $table->string('akad_location');
            $table->text('akad_address');
            // Resepsi
            $table->dateTime('reception_date');
            $table->string('reception_location');
            $table->text('reception_address');
            // Maps
            $table->decimal('maps_lat', 10, 7)->nullable();
            $table->decimal('maps_lng', 10, 7)->nullable();
            // Media
            $table->string('music_file')->nullable();
            $table->string('hero_image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void { Schema::dropIfExists('invitations'); }
};
