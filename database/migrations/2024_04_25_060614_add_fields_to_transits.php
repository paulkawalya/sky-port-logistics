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
        Schema::table('transits', function (Blueprint $table) {

            $table->enum('payment_status',['paid','not paid','partialy paid'])->default('not paid');
            $table->enum('category',["Normal", "Mixed", "FCL", "Kitagululo"])->nullable();           
            $table->string('size')->nullable();
            $table->string('expected_arrival_time')->nullable();
            $table->string('departure_place')->nullable();
            $table->string('destination_place')->nullable();
            $table->string('contact_information')->nullable();
            $table->string('entry_number')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transits', function (Blueprint $table) {

            $table->dropColumn('payment_status');
            $table->dropColumn('category');           
            $table->dropColumn('size');
            $table->dropColumn('expected_arrival_time');
            $table->dropColumn('departure_place');
            $table->dropColumn('destination_place');
            $table->dropColumn('contact_information');
            $table->dropColumn('entry_number');
            
        });
    }
};
