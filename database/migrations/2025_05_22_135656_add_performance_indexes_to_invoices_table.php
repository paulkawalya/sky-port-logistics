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
        // Add indexes to invoices table
        if (Schema::hasTable('invoices')) {
            Schema::table('invoices', function (Blueprint $table) {
                $sm = Schema::getConnection()->getDoctrineSchemaManager();
                $indexes = $sm->listTableIndexes('invoices');
                
                // Single column indexes
                if (!isset($indexes['invoices_shippment_id_index'])) {
                    $table->index('shippment_id');
                }
                if (!isset($indexes['invoices_user_id_index'])) {
                    $table->index('user_id');
                }
                if (!isset($indexes['invoices_date_invoiced_index'])) {
                    $table->index('date_invoiced');
                }
                if (!isset($indexes['invoices_created_at_index'])) {
                    $table->index('created_at');
                }
                if (!isset($indexes['invoices_transaction_currency_index'])) {
                    $table->index('transaction_currency');
                }
                
                // Composite index for common where conditions
                if (!isset($indexes['invoices_transaction_currency_created_at_index'])) {
                    $table->index(['transaction_currency', 'created_at']);
                }
            });
        }
        
        // Add indexes to invoice_details table
        if (Schema::hasTable('invoice_details')) {
            Schema::table('invoice_details', function (Blueprint $table) {
                $sm = Schema::getConnection()->getDoctrineSchemaManager();
                $indexes = $sm->listTableIndexes('invoice_details');
                $columns = Schema::getColumnListing('invoice_details');
                
                if (in_array('invoice_id', $columns) && !isset($indexes['invoice_details_invoice_id_index'])) {
                    $table->index('invoice_id');
                }
                if (in_array('product_id', $columns) && !isset($indexes['invoice_details_product_id_index'])) {
                    $table->index('product_id');
                }
            });
        }
        
        // Add indexes to invoice_payments table
        if (Schema::hasTable('invoice_payments')) {
            Schema::table('invoice_payments', function (Blueprint $table) {
                $sm = Schema::getConnection()->getDoctrineSchemaManager();
                $indexes = $sm->listTableIndexes('invoice_payments');
                $columns = Schema::getColumnListing('invoice_payments');
                
                if (in_array('invoice_id', $columns) && !isset($indexes['invoice_payments_invoice_id_index'])) {
                    $table->index('invoice_id');
                }
                if (in_array('payment_date', $columns) && !isset($indexes['invoice_payments_payment_date_index'])) {
                    $table->index('payment_date');
                }
            });
        }
        
        // Add indexes to invoice_taxes table
        if (Schema::hasTable('invoice_taxes')) {
            Schema::table('invoice_taxes', function (Blueprint $table) {
                $sm = Schema::getConnection()->getDoctrineSchemaManager();
                $indexes = $sm->listTableIndexes('invoice_taxes');
                $columns = Schema::getColumnListing('invoice_taxes');
                
                if (in_array('invoice_id', $columns) && !isset($indexes['invoice_taxes_invoice_id_index'])) {
                    $table->index('invoice_id');
                }
                if (in_array('tax_id', $columns) && !isset($indexes['invoice_taxes_tax_id_index'])) {
                    $table->index('tax_id');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop indexes from invoices table
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropIndexIfExists('invoices_shippment_id_index');
            $table->dropIndexIfExists('invoices_user_id_index');
            $table->dropIndexIfExists('invoices_date_invoiced_index');
            $table->dropIndexIfExists('invoices_created_at_index');
            $table->dropIndexIfExists('invoices_transaction_currency_index');
            $table->dropIndexIfExists('invoices_transaction_currency_created_at_index');
        });
        
        // Drop indexes from invoice_details table
        if (Schema::hasTable('invoice_details')) {
            Schema::table('invoice_details', function (Blueprint $table) {
                $table->dropIndexIfExists('invoice_details_invoice_id_index');
                $table->dropIndexIfExists('invoice_details_product_id_index');
            });
        }
        
        // Drop indexes from invoice_payments table
        if (Schema::hasTable('invoice_payments')) {
            Schema::table('invoice_payments', function (Blueprint $table) {
                $table->dropIndexIfExists('invoice_payments_invoice_id_index');
                $table->dropIndexIfExists('invoice_payments_payment_date_index');
            });
        }
        
        // Drop indexes from invoice_taxes table
        if (Schema::hasTable('invoice_taxes')) {
            Schema::table('invoice_taxes', function (Blueprint $table) {
                $table->dropIndexIfExists('invoice_taxes_invoice_id_index');
                $table->dropIndexIfExists('invoice_taxes_tax_id_index');
            });
        }
    }
};
