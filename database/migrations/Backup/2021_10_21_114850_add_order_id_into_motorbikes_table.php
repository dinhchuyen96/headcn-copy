<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrderIdIntoMotorbikesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('motorbikes', function (Blueprint $table) {
            $table->foreignId('order_id')->nullable()->comment('Đơn mua')->constrained('orders');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('motorbikes', function (Blueprint $table) {
            $table->dropColumn('order_id');
        });
    }
}
