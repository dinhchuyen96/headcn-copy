<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableReturnItem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('return_items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('item_id')->comment('item id FK');
            $table->string('item_code')->nullable()->comment('item_code');
            $table->string('item_name')->nullable()->comment('item_name');
            $table->bigInteger('item_type')->nullable()->comment('item_type 1 part 2 motobike');
            $table->bigInteger('item_qty')->default(0)->comment('so luong nhap lai');;
            $table->bigInteger('item_price')->default(0)->comment('gia nhap lai');;
            $table->bigInteger('ref_order_id')->nullable()->comment('ref_order_id');
            $table->bigInteger('customer_id')->nullable()->comment('customer_id');
            $table->bigInteger('warehouse_id')->nullable()->default(0)->comment('warehouse_id');
            $table->bigInteger('position_in_warehouse_id')->default(0)->comment('position_in_warehouse_id');
            $table->string('note')->nullable()->comment('note and comment');
            $table->bigInteger('paid_status')->default(0)->comment('trang thai thanh toan  0 chua 1 da tt');
            $table->bigInteger('bill_id')->nullable()->default(0)->comment('ref payment id');
            $table->string('created_by')->nullable()->comment('created by');
            $table->string('updated_by')->nullable()->comment('updated by');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('table_return_item');
    }
}
