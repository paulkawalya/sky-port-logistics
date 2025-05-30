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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->date('transaction_date');
            $table->foreignId('user_id')->constrained();
            $table->unsignedBigInteger('source_payment_account_id')->nullable();
            $table->foreignId('payment_account_id')->constrained();
            $table->double('amount');
            $table->string('particular')->nullable();
            $table->enum('transaction_type',['debit','credit']);
            $table->enum('transaction_currency',['USD','UGX'])->default('USD');    
            
            $table->foreign('source_payment_account_id')->references('id')->on('payment_accounts');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
