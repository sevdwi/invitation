<?php
// FILE: database/migrations/2026_10_05_000001_add_slug_and_user_id_to_invitations_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invitations', function (Blueprint $table) {
            // Pemilik undangan (nullable sampai fitur login tersedia)
            $table->foreignId('user_id')->nullable()->after('id')->constrained()->nullOnDelete();
            $table->string('slug')->nullable()->after('user_id');
        });

        // Isi slug untuk undangan yang sudah ada
        $used = [];
        foreach (DB::table('invitations')->orderBy('id')->get(['id', 'groom_name', 'bride_name']) as $row) {
            $base = Str::slug($row->groom_name . '-' . $row->bride_name) ?: 'undangan';
            $slug = $base;
            for ($i = 2; in_array($slug, $used, true); $i++) {
                $slug = $base . '-' . $i;
            }
            $used[] = $slug;
            DB::table('invitations')->where('id', $row->id)->update(['slug' => $slug]);
        }

        Schema::table('invitations', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->change();
            $table->unique('slug');
        });
    }

    public function down(): void
    {
        Schema::table('invitations', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropConstrainedForeignId('user_id');
            $table->dropColumn('slug');
        });
    }
};
