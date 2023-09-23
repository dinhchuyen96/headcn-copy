<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterGiftPointTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gift_point', function (Blueprint $table) {
            $table->bigInteger('gift_warehouse_id')->nullable()->after('quantity');
            $table->bigInteger('gift_position_in_warehouse_id')->nullable()->after('gift_warehouse_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gift_point', function (Blueprint $table) {
            $table->dropColumn('gift_warehouse_id');
            $table->dropColumn('gift_position_in_warehouse_id');
        });
    }
}
