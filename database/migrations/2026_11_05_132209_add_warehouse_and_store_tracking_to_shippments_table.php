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
        Schema::table('shippments', function (Blueprint $table) {
            $table->enum('cargo_status', [
                'In Transit',
                'Received in Store',
                'Sorted & Inspected',
                'Ready for Pickup',
                'Dispatched',
                'Delivered'
            ])->default('In Transit')->after('cbm');

            $table->string('warehouse_location')->nullable()->after('cargo_status'); // e.g. Shelf A-3, Bay 2, Zone B
            $table->timestamp('received_in_store_at')->nullable()->after('warehouse_location');
            $table->timestamp('dispatched_at')->nullable()->after('received_in_store_at');
            $table->string('receiver_name')->nullable()->after('dispatched_at');
            $table->string('receiver_phone')->nullable()->after('receiver_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shippments', function (Blueprint $table) {
            $table->dropColumn([
                'cargo_status',
                'warehouse_location',
                'received_in_store_at',
                'dispatched_at',
                'receiver_name',
                'receiver_phone'
            ]);
        });
    }
};
