<?php
// FILE: database/migrations/2026_10_08_000001_create_attendances_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Daftar hadir (buku tamu) yang diisi tamu sendiri di lokasi acara setelah scan QR
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invitation_id')->constrained()->cascadeOnDelete();
            $table->string('guest_name');
            $table->string('guest_phone')->nullable();
            $table->string('address')->nullable();
            $table->unsignedTinyInteger('guest_count')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void { Schema::dropIfExists('attendances'); }
};
