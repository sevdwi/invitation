<?php
// FILE: database/migrations/2024_01_01_000002_create_rsvps_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rsvps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invitation_id')->constrained()->cascadeOnDelete();
            $table->string('guest_name');
            $table->string('guest_phone')->nullable();
            $table->enum('attendance', ['hadir', 'tidak_hadir', 'belum_pasti'])->default('belum_pasti');
            $table->unsignedTinyInteger('guest_count')->default(1);
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void { Schema::dropIfExists('rsvps'); }
};
