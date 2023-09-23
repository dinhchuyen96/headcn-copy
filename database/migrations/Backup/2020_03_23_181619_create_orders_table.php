<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->bigInteger('created_by')->nullable();
            $table->string('order_no')->nullable();
            $table->bigInteger('total_items')->nullable();
            $table->decimal('sub_total')->nullable();
            $table->decimal('tax')->default(0);
            $table->decimal('discount')->default(0);
            $table->decimal('total') ->nullable();
            $table->integer('category')->nullable()->comment('1: phụ tùng, 2: xe máy, 3 Bảo dưỡng, 4 Sửa chữa');
            $table->integer('type')->nullable()->comment('1: bán buôn, 2 bán lẻ');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on('customers')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
