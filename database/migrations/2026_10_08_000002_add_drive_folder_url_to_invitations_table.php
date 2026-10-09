<?php
// FILE: database/migrations/2026_10_08_000002_add_drive_folder_url_to_invitations_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Link folder Google Drive berisi album foto acara
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invitations', function (Blueprint $table) {
            $table->string('drive_folder_url', 500)->nullable()->after('hero_image');
        });
    }

    public function down(): void
    {
        Schema::table('invitations', function (Blueprint $table) {
            $table->dropColumn('drive_folder_url');
        });
    }
};
