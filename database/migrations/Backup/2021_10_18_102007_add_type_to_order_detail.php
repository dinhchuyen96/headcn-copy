<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeToOrderDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_details', function (Blueprint $table) {
            $table->tinyInteger('type')->nullable()->comment(' 1: bán buon, 2:bán lẻ, 3:nhập ');
            $table->bigInteger('actual_price')->nullable()->comment('giá thực tế');
            $table->bigInteger('vat_price')->nullable()->comment('giá in hóa đơn');
            $table->bigInteger('listed_price')->nullable()->comment('giá niêm yết');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_details', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('actual_price');
            $table->dropColumn('vat_price');
            $table->dropColumn('listed_price');
        });
    }
}
