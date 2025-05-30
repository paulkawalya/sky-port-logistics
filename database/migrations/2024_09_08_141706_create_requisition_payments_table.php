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
        Schema::create('requisition_payments', function (Blueprint $table) {

            $table->id();
            $table->timestamps();
            $table->foreignId('supplier_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('requisition_detail_id')->constrained();
            $table->date('payment_date');
            $table->double('amount');
            $table->enum('mode_of_payment',['Cash','Bank','Mobile Money','Digital'])->nullable();
            $table->enum('currency',['UGX','USD'])->default('UGX');
            $table->string('particular')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requisition_payments');
    }
};
