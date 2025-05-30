<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('quotations', function (Blueprint $table) {
            $table->decimal('weight', 10, 3)
                ->nullable()
                ->comment('Total weight in kilograms');

            $table->decimal('volume', 10, 3)
                ->nullable()
                ->comment('Total volume in cubic meters')
                ->after('weight');

            $table->integer('packages')
                ->nullable()
                ->comment('Total number of packages')
                ->after('volume');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('quotations', function (Blueprint $table) {
            $table->dropColumn(['weight', 'volume', 'packages']);
        });
    }
};
