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
        Schema::create('transit_statuses', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('transit_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->string('status');
            $table->text('narrative')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transit_statuses');
    }
};
