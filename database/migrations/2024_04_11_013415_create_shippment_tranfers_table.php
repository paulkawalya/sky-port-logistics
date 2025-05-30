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
        Schema::create('shippment_tranfers', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('shippment_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->date('date_transfered');
            $table->string('product_name');
            $table->string('destination')->nullable();
            $table->text('narrative')->nullable();
            $table->string('reference')->nullable();
            $table->string('quantity')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shippment_tranfers');
    }
};
