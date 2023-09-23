<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->integer('total_money')->nullable()->comment('Tổng số tiền');
            $table->bigInteger('warehouse_id')->nullable()->comment('Mã kho map master data 5');
            $table->dateTime('date_payment')->nullable()->comment('Hạn thanh toán');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('total_money');
            $table->dropColumn('warehouse_id');
            $table->dropColumn('date_payment');
        });
    }
}
