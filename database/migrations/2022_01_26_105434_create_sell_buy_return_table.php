<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellBuyReturnTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sell_buy_return', function (Blueprint $table) {
            $table->id();
            $table->integer('return_type')->nullable()->default(0); // 0: sell; 1: buy
            $table->integer('return_product_type')->nullable()->default(0); // 0: motobike; 1: accessories
            $table->integer('product_id')->nullable();
            $table->integer('return_quantity')->nullable();
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
        Schema::dropIfExists('sell_buy_return');
    }
}
