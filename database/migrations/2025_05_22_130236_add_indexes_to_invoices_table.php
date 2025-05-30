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
        Schema::table('invoices', function (Blueprint $table) {
            // Add indexes for frequently queried columns
            $table->index('shippment_id');
            $table->index('user_id');
            $table->index('date_invoiced');
            $table->index('created_at');
            $table->index('transaction_currency');
            
            // Composite index for common where conditions
            $table->index(['transaction_currency', 'created_at']);
        });
        
        // Add indexes to related tables
        Schema::table('invoice_details', function (Blueprint $table) {
            $table->index('invoice_id');
            $table->index('product_id');
        });
        
        Schema::table('invoice_payments', function (Blueprint $table) {
            $table->index('invoice_id');
            $table->index('payment_date');
        });
        
        Schema::table('invoice_taxes', function (Blueprint $table) {
            $table->index('invoice_id');
            $table->index('tax_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropIndex(['shippment_id']);
            $table->dropIndex(['user_id']);
            $table->dropIndex(['date_invoiced']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['transaction_currency']);
            $table->dropIndex(['transaction_currency', 'created_at']);
        });
        
        Schema::table('invoice_details', function (Blueprint $table) {
            $table->dropIndex(['invoice_id']);
            $table->dropIndex(['product_id']);
        });
        
        Schema::table('invoice_payments', function (Blueprint $table) {
            $table->dropIndex(['invoice_id']);
            $table->dropIndex(['payment_date']);
        });
        
        Schema::table('invoice_taxes', function (Blueprint $table) {
            $table->dropIndex(['invoice_id']);
            $table->dropIndex(['tax_id']);
        });
    }
};
