<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fee_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('club_id')->constrained()->cascadeOnDelete();
            $table->string('name');                               // npr. "U-11 mjesečna"
            $table->decimal('default_amount', 8, 2);              // zadani iznos
            $table->unsignedTinyInteger('billing_day')->default(1); // dan u mjesecu (1-28)
            $table->string('status')->default('aktivna');         // aktivna | suspendirana
            $table->timestamps();
        });
    }
};
