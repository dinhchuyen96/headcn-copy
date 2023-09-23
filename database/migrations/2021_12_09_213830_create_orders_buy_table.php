<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersBuyTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('orders_buy', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->timestamps();
			$table->bigInteger('suppliers_id')->unsigned()->nullable()->index('orders_buy_suppliers_id_foreign');
			$table->bigInteger('accessories_id')->unsigned()->nullable()->index('orders_buy_accessories_id_foreign');
			$table->bigInteger('motorbikes_id')->unsigned()->nullable()->index('orders_buy_motorbikes_id_foreign');
			$table->softDeletes();
			$table->boolean('status')->nullable()->comment('Trạng thái đơn hàng');
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
		Schema::drop('orders_buy');
	}

}
