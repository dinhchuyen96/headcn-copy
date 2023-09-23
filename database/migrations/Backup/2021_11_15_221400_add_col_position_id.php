<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColPositionId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('warehouse_tranfer_history', function (Blueprint $table) {
            $table->bigInteger('from_position_in_warehouse_id')->nullable()->after('from_warehouse_id');
            $table->bigInteger('to_position_in_warehouse_id')->nullable()->after('to_warehouse_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
