<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyColumnTotalOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('total',17,2)->nullable()->default(0)->change();
            $table->decimal('sub_total',17,2)->nullable()->default(0)->change();
            $table->decimal('tax',17,2)->nullable()->default(0)->change();
            $table->decimal('discount',17,2)->nullable()->default(0)->change();
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
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('total',8,2)->nullable()->change();
            $table->decimal('sub_total',8,2)->nullable()->change();
            $table->decimal('tax',8,2)->nullable(false)->default(0)->change();
            $table->decimal('discount',8,2)->nullable(false)->default(0)->change();
        });
    }
}
