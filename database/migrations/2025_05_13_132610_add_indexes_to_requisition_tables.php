<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesToRequisitionTables extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add indexes to requisition_details table
        Schema::table('requisition_details', function (Blueprint $table) {
            $table->index('requisition_id');
            $table->index('expense_category_id');
        });

        // Add indexes to requisition_payments table
        Schema::table('requisition_payments', function (Blueprint $table) {
            $table->index('requisition_detail_id');
            $table->index('supplier_id');
            $table->index('user_id');
            $table->index('payment_date');
        });

        // Add indexes to requisitions table
        Schema::table('requisitions', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('date_requisitioned');
            $table->index('status');
            $table->index('currency');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove indexes from requisition_details table
        Schema::table('requisition_details', function (Blueprint $table) {
            $table->dropIndex(['requisition_id']);
            $table->dropIndex(['expense_category_id']);
        });

        // Remove indexes from requisition_payments table
        Schema::table('requisition_payments', function (Blueprint $table) {
            $table->dropIndex(['requisition_detail_id']);
            $table->dropIndex(['supplier_id']);
            $table->dropIndex(['user_id']);
            $table->dropIndex(['payment_date']);
        });

        // Remove indexes from requisitions table
        Schema::table('requisitions', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['date_requisitioned']);
            $table->dropIndex(['status']);
            $table->dropIndex(['currency']);
        });
    }
}
