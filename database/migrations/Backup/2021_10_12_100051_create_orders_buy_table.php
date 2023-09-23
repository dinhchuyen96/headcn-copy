<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersBuyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders_buy', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('suppliers_id')->nullable()->constrained('suppliers')->comment('Map với suppliers');
            $table->foreignId('accessories_id')->nullable()->constrained('accessories')->comment('Map với accessories');
            $table->foreignId('motorbikes_id')->nullable()->constrained('motorbikes')->comment('Map với motorbikes');
            $table->softDeletes();
            $table->tinyInteger('status')->nullable()->comment('Trạng thái đơn hàng');
            $table->bigInteger('admin_id')->nullable()->comment('Nguoi tao');
            $table->bigInteger('total_money')->nullable()->comment('Tổng số tiền đơn hàng');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders_buy');
    }
}
