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
        Schema::create('shippment_documents', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('shippment_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->string('file_name')->nullable();
            $table->string('file_url')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shippment_documents');
    }
};
