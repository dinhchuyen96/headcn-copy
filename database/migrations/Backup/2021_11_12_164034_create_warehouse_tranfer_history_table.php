<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWarehouseTranferHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warehouse_tranfer_history', function (Blueprint $table) {
            $table->id();
            $table->integer('from_warehouse_id')->comment('Từ kho');
            $table->integer('to_warehouse_id')->comment('Đến kho');
            $table->integer('product_id')->comment('Đối tượng chuyển');
            $table->integer('tranfer_type')->comment('0: Chuyển xe , 1: Chuyển phụ tùng');
            $table->timestamp('tranfer_date')->comment('Ngày chuyển');
            $table->integer('quantity')->comment('Số lượng');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('warehouse_tranfer_history');
    }
}
