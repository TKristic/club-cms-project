<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('membership_fee_id')->constrained()->cascadeOnDelete();
            $table->string('invoice_number')->unique();     // broj uplatnice
            $table->string('reference_number');             // poziv na broj (HRxx)
            $table->decimal('amount', 8, 2);
            $table->string('pdf_path')->nullable();         // gdje je spremljen PDF
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
