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
        Schema::create('quotation_taxes', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->foreignId('tax_id')->constrained();
            $table->foreignId('quotation_id')->constrained();
            $table->double('percentage_tax')->nullable();
            $table->double('amount')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotation_taxes');
    }
};
