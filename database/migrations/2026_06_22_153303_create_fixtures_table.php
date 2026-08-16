<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('fixtures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('club_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('opponent');                       
            $table->boolean('is_home')->default(true);        
            $table->dateTime('kickoff_at');                   
            $table->unsignedInteger('goals_for')->nullable(); 
            $table->unsignedInteger('goals_against')->nullable();
            $table->string('competition')->nullable();        
            $table->string('season')->nullable();             
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fixtures');
    }
};
