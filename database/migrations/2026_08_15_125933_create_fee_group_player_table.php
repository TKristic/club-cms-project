<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fee_group_player', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fee_group_id')->constrained()->cascadeOnDelete();
            $table->foreignId('player_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount_override', 8, 2)->nullable(); // popust: prazno = grupni iznos
            $table->timestamps();
            $table->unique(['fee_group_id', 'player_id']);        // igrač jednom po grupi
        });
    }
};
