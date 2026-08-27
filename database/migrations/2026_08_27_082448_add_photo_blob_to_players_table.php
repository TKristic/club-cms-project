<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('players', function (Blueprint $table) {
            $table->binary('photo_blob')->nullable()->after('photo');
        });

        // podigni na MEDIUMBLOB (do 16 MB) — BLOB bi bio premalen za sliku
        DB::statement('ALTER TABLE players MODIFY photo_blob MEDIUMBLOB NULL');
    }

    public function down(): void
    {
        Schema::table('players', function (Blueprint $table) {
            $table->dropColumn('photo_blob');
        });
    }
};
