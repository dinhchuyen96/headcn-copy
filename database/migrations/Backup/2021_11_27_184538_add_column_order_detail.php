<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnOrderDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_details', function (Blueprint $table) {
            $table->integer('warehouse_id')->comment('Nhập hàng và xuất hàng từ kho');
            $table->integer('position_in_warehouse_id')->comment('Nhập hàng và xuất hàng từ vị trí kho');
        });
    }

    /**
     * Reverse the migrations.
     * tudn change down detail
     * @return void
     */
    public function down()
    {
        Schema::table('order_details', function (Blueprint $table) {
            $table->dropColumn('warehouse_id');
            $table->dropColumn('position_in_warehouse_id');
        });
    }
}
