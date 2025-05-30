<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddClearingAgentToTransitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transits', function (Blueprint $table) {
            $table->string('clearing_agent')->nullable();
        });
    }

    /**
     * Reverse that migration.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transits', function (Blueprint $table) {
            $table->dropColumn('clearing_agent');
        });
    }
}
