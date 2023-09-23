<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTableInstallment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('installment', function (Blueprint $table) {
            $table->bigInteger('id', true)->unsigned();
            $table->bigInteger('order_id')->unsigned()->comment('Hóa đơn khách hàng trả góp');
            $table->string('contract_number')->comment('Số hợp đồng trả góp');
            $table->bigInteger('money')->comment('Số tiền trả góp');
            $table->bigInteger('installment_company_id')->unsigned()->comment('Công ty tài chính trả góp');
            $table->timestamps();
			$table->softDeletes();
            $table->foreign('order_id')->references('id')->on('orders');

            // $table->foreign('order_id')->references('id')->on('orders');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('installment');
    }
}
