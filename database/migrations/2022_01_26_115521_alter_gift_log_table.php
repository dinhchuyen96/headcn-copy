<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterGiftLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gift_log', function (Blueprint $table) {
            $table->bigInteger('warehouse_id')->after('quantity')->unsigned();
            $table->bigInteger('position_in_warehouse_id')->after('warehouse_id')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gift_log', function (Blueprint $table) {
            $table->dropColumn('warehouse_id');
            $table->dropColumn('position_in_warehouse_id');
        });
    }
}
