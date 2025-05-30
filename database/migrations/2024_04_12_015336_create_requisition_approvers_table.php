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
        Schema::create('requisition_approvers', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('requisition_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->enum('status',['pending','approved','rejected'])->default('pending');
            $table->string('remarks')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requisition_approvers');
    }
};
