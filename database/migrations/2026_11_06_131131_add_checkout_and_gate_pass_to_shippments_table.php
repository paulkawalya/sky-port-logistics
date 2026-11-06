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
            $table->string('receiver_id_number')->nullable()->after('receiver_phone');
            $table->string('receiver_vehicle_no')->nullable()->after('receiver_id_number');
            $table->string('gate_pass_number')->nullable()->after('receiver_vehicle_no');
            $table->foreignId('checked_out_by')->nullable()->after('gate_pass_number')->constrained('users')->nullOnDelete();
            $table->text('checkout_notes')->nullable()->after('checked_out_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shippments', function (Blueprint $table) {
            $table->dropForeign(['checked_out_by']);
            $table->dropColumn([
                'receiver_id_number',
                'receiver_vehicle_no',
                'gate_pass_number',
                'checked_out_by',
                'checkout_notes',
            ]);
        });
    }
};
