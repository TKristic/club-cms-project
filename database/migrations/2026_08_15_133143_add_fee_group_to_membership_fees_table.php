<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('membership_fees', function (Blueprint $table) {
            $table->foreignId('fee_group_id')->nullable()->after('club_id')->constrained()->nullOnDelete();
            $table->string('period')->nullable()->after('season'); // npr. "2026-08" (mjesec naplate)
        });
    }

    public function down(): void
    {
        Schema::table('membership_fees', function (Blueprint $table) {
            $table->dropConstrainedForeignId('fee_group_id');
            $table->dropColumn('period');
        });
    }
};
