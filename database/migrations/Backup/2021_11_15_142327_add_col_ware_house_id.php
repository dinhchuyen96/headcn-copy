<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColWareHouseId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('accessories', function (Blueprint $table) {
            $table->bigInteger('admin_id')->nullable(); //tudn add more this column due to error migration
            $table->bigInteger('warehouse_id')->nullable()->after('admin_id');
            $table->bigInteger('position_in_warehouse_id')->nullable()->after('warehouse_id');
        });
    }

    /**
     * Reverse the migrations.
     * TUDN them down function detail
     * @return void
     */
    public function down()
    {
        //
        $table->dropColumn('admin_id');
        $table->dropColumn('warehouse_id');
        $table->dropColumn('position_in_warehouse_id');
    }
}
