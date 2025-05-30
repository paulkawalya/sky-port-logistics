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
        Schema::create('requisition_documents', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('requisition_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->string('file_name');
            $table->string('file_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requisition_documents');
    }
};
