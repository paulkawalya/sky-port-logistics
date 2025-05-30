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
        Schema::create('invoice_payments', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('invoice_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->date('payment_date');
            $table->double('amount_paid');
            $table->enum('mode_of_payment',['Cash','Bank','Mobile Money','Digital'])->nullable();
            $table->text('narrative')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_payments');
    }
};
