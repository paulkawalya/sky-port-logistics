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
        Schema::create('shippments', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('transit_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('customer_id')->constrained();
            $table->foreignId('agent_id')->constrained();
            $table->string('shipper')->nullable();
            $table->string('mark')->nullable();
            $table->string('bl_packages')->nullable();
            $table->string('number_of_packages')->nullable();
            $table->string('unit_of_measure')->nullable();
            $table->string('weight')->nullable();
            $table->string('volume')->nullable();
            $table->string('type')->nullable();
            $table->text('description')->nullable();
            $table->string('cbm')->nullable();      

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shippments');
    }
};
